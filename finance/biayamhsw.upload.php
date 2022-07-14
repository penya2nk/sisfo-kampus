<?php
//error_reporting(0);
session_start();
include_once "../academic_sisfo1.php";

ViewHeaderApps("Proses Biaya Cama");

$_upJumlah = GainVariabelx('_upJumlah')+0;
$_upProses = GainVariabelx('_upProses')+0;

if ($_upProses <= $_upJumlah) {
    if ($_upProses > 0) JalankanProses($_upJumlah, $_upProses);
}
else Selesai($_upJumlah);

function JalankanProses($_upJumlah, $_upProses) {
	global $koneksi;
  $arr = $_SESSION['_up_'.$_upProses];
  $dat = explode('|', $arr);
  // persentase
  $_sudah = ($_upJumlah > 0)? ($_upProses/$_upJumlah) * 100 : 0;
  $_sisa  = 100 - $_sudah;
  // Parameter
  $BayarMhswID = 'BTN-'.$dat[23];
  $NamaTahun = $dat[0];
  $MhswID = $dat[3];
  $NamaMhsw = $dat[4];
  $ProdiID = $dat[6];
  $NamaProdi = $dat[7];
  $Angkatan = $dat[8];
  $RekeningID = "4201390002572";
  $Jumlah = $dat[10]+0;
  $NamaBIPOT = $dat[11];
  $BuktiSetoran = $dat[23].'|'.$dat[24];
  $Catatan = $dat[27];
  
  // Cek data mahasiswa
  $ketemu = false;
  $mhsw = AmbilFieldx('mhsw', "KodeID='".KodeID."' and MhswID", $MhswID, '*');
  if (!empty($mhsw)) { // *** Berarti account Mhsw
    $_MhswID = $MhswID;
    $_PMBID = $mhsw['PMBID'];
    $_PMBMhswID = 1;
    $ketemu = true;
  }
  else { // Cek apakah account PMB?
    $mhsw = AmbilFieldx('pmb', "KodeID='".KodeID."' and PMBID", $MhswID, '*');
    if (!empty($mhsw)) { // *** Account-nya PMB
      $_MhswID = '';
      $_PMBID = $mhsw['PMBID'];
      $_PMBMhswID = 0;
      $ketemu = true;
    }
    else $ketemu = false; // Tidak ketemu account-nya siapa
  }
  // Jika ketemu
  if ($ketemu) {
    // Prosesnya
    $thn = explode('-', $NamaTahun);
    $Tahun = $thn[0];
    $Semester = (substr($thn[1], 0, 2) == 'GA')? 1 : 2;
    $TahunID = $Tahun.$Semester;
    // Cek KHS
    $khs = AmbilFieldx('khs', "KodeID='".KodeID."' and MhswID='$MhswID' and TahunID",
      $TahunID, "*");
    if (empty($khs)) { // Jika tidak ketemu, maka insert KHS
      // Ambil Total SKS
      if ($khs['Sesi'] <= 1) 
        $MaxSKS = AmbilOneField('prodi', "ProdiID='$mhsw[ProdiID]' and KodeID", KodeID, 'DefSKS')+0;
      else {
        $MaxSKS = AmbilOneField('maxsks', 
          "KodeID='".KodeID."' and NA = 'N'
          and DariIP <= $khs[IPS] and $khs[IPS] <= SampaiIP and ProdiID", 
          $khs['ProdiID'], 'SKS')+0;
      }
      $_sesi = AmbilOneField('khs', "MhswID='$MhswID' and KodeID", KodeID, "max(Sesi)")+1;
      $s0 = "insert into khs
        (TahunID, KodeID, ProgramID, ProdiID, MhswID,
        StatusMhswID, Sesi, SKS,
        MaxSKS, LoginBuat, TanggalBuat)
        values
        ('$TahunID', '".KodeID."', '$mhsw[ProgramID]', '$mhsw[ProdiID]', '$MhswID',
        'A', $_sesi, 0,
        $MaxSKS, '$_SESSION[_Login]', now())";
      $r0 = mysqli_query($koneksi, $s0);
      $KHSID = GetLastID();
      $khs = AmbilFieldx('khs', 'KHSID', $KHSID, '*');
    } // end KHS
    // Cek, apakah sudah dibayarkan sebelumnya atau belum?
    $ada = AmbilOneField('bayarmhsw', "KodeID='".KodeID."' and BayarMhswID",
      $BayarMhswID, "count(BayarMhswID)")+0;
    if ($ada > 0) {
      echo "<p style='text-align:center;background:red;color:yellow'><b>Sudah pernah dibayarkan.</b></p>";
      // Set status
      if ($khs['StatusMhswID'] == 'P') {
        $sa = "update khs 
          set StatusMhswID = 'A'
          where KHSID = '$khs[KHSID]' ";
        $ra = mysqli_query($koneksi, $sa);
      }
    }
    else {
      include_once "../keu/pembayaran_mhs.lib.php";
      // Tambahkan di catatan pembayaran
      $s = "insert into bayarmhsw
        (BayarMhswID, KodeID, TahunID, RekeningID, MhswID, PMBID, TrxID, PMBMhswID,
        Bank, BuktiSetoran, Tanggal, Jumlah,
        Keterangan, LoginBuat, TanggalBuat, NA)
        values
        ('$BayarMhswID', '".KodeID."', '$TahunID', '$RekeningID', '$_MhswID', '$_PMBID', 1, '$_PMBMhswID',
        'BTN', '$BuktiSetoran', now(), $Jumlah,
        '$Catatan', '$_SESSION[_Login]', now(), 'N')";
      $r = mysqli_query($koneksi, $s);
      // Update summary
      $_StatusMhswID = ($khs['StatusMhswID'] == 'P')? 'A' : $khs['StatusMhswID'];
      HitungUlangBIPOTMhsw($MhswID, $TahunID);
    }
  }
  else $_ketemu = "<div style='text-align:center; background: red; color: yellow'><b>Account tidak ditemukan...</b></div><br />";
  // Tampilan proses
  $_Jumlah = number_format($Jumlah);
  echo "
  <p align=center>
  <font size=+1>$_upProses</font> <sup>~$_upJumlah</sup><br />
    <img src='../img/B1.jpg' height=20 width=1 /><img src='../img/B2.jpg' height=20 width=$_sudah /><img src='../img/B3.jpg' height=20 width=$_sisa /><img src='../img/B1.jpg' height=20 width=1 />
    <br />
    $_ketemu
    Tahun Akd: $TahunID<br />
    NIM: $MhswID <br />
    Nama: <b>$NamaMhsw</b><br />
    Prodi: <b>$NamaProdi</b> <sup>$ProdiID</sup><br />
    Rekening: <b>$RekeningID</b><br />
    Jumlah: <b>$_Jumlah</b><br />
    Catatan: <b>$Catatan</b><br />
  </p>";
  
  // Next...
  $tmr = 10;
  $_SESSION['_upProses']++;
  echo <<<SCR
    <script>
    window.onload=setTimeout("window.location='../$_SESSION[ndelox].upload.php'", $tmr);
    </script>
SCR;
}

function Selesai($_pmbJumlah) {
  $namafile = basename($_SESSION['_byaFile']);
  echo "<p align=center>
  Proses upload telah selesai.<br />
  Sistem telah memproses <font size=+1>$_pmbJumlah</font> data pembayaran Mahasiswa.<br />
  </p>";
  //echo "<script>parent.window.location='../index.php?ndelox=$_SESSION[ndelox]'</script>";
  
  echo "<p align=center>
  <input type=button name='Kembali' value='Kembali' onClick=\"parent.window.location='../index.php?ndelox=$_SESSION[ndelox]'\" />
  </p>";
}
?>

