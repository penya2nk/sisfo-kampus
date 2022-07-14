<?php
error_reporting(0);
$_krsTahunID = GainVariabelx('_krsTahunID');
$_krsMhswID = GainVariabelx('_krsMhswID');

TitleApps("NILAI SEMESTER");
CekBolehAksesModul();
TampilkanCariMhswnya();
if (!empty($_krsTahunID) && !empty($_krsMhswID)) {
  $oke = BolehAksesData($_krsMhswID);
  if ($oke) $oke = ValidasiDataMhsw($_krsTahunID, $_krsMhswID, $khs);
  if ($oke) {
    $mhsw = AmbilFieldx("mhsw m
      left outer join statusawal sta on sta.StatusAwalID = m.StatusAwalID 
	  left outer join dosen d on d.Login = m.PenasehatAkademik", 
      "m.KodeID = '".KodeID."' and m.MhswID", $_krsMhswID, 
      "m.*, sta.Nama as STAWAL, d.Nama as namaDosen");
    $thn = AmbilFieldx("tahun",
      "KodeID = '".KodeID."' and TahunID", $_krsTahunID, "*");
    $lungo = sqling($_REQUEST['lungo']);
    if (empty($lungo)) {
      TampilkanHeaderMhsw($thn, $mhsw, $khs);
      TampilkanDaftarKRSMhsw($thn, $mhsw, $khs);
    }
    else $lungo();
  }
}

function TampilkanCariMhswnya() {
  global $koneksi;
  $s = "select DISTINCT(TahunID) from tahun where KodeID='".KodeID."' order by TahunID DESC";
  $r = mysqli_query($koneksi, $s);
  $opttahun = "<option value=''></option>";
  while($w = mysqli_fetch_array($r)) {
	  $ck = ($w['TahunID'] == $_SESSION['_krsTahunID'])? "selected" : '';
	  $opttahun .=  "<option value='$w[TahunID]' $ck>$w[TahunID]</option>";
  }

  $_inputTahun = "<select style='height:30px' name='_krsTahunID' onChange='this.form.submit()'>$opttahun</select>";
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:20%' align='center'>
  <form action='?' method=POST>
  <input type=hidden name='_krsHariID' value='' />
  <tr>
      <td >$_inputTahun</td>
      <td ><input style='height:30px' placeholder='NIM' style='height:23px' type=text name='_krsMhswID' value='$_SESSION[_krsMhswID]' size=20 maxlength=50 /></td>
      <td >
        <input class='btn btn-success btn-sm' type=submit name='Cari' value='Lihat Data' />
        </td>
      </tr>
  </form>
  </table>
  </div>
</div>
</div>";
}
function CekBolehAksesModul() {
  $arrAkses = array(1, 20, 41, 120);
  $key = array_search($_SESSION['_LevelID'], $arrAkses);
  if ($key === false)
    die(PesanError('Error',
      "Anda tidak berhak mengakses modul ini.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut."));
}
function BolehAksesData($nim) {
  if ($_SESSION['_LevelID'] == 120 && $_SESSION['_Login'] != $nim) {
    echo PesanError('Error',
      "Anda tidak boleh melihat data KRS mahasiswa lain.<br />
      Anda hanya boleh mengakses data dari NIM: <b>$_SESSION[_Login]</b>.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut");
    return false;
  } else return true;
}
function ValidasiDataMhsw($thn, $nim, &$khs) {
  $khs = AmbilFieldx("khs k
    left outer join statusmhsw s on s.StatusMhswID = k.StatusMhswID", 
    "k.KodeID = '".KodeID."' and k.TahunID = '$thn' and k.MhswID",
    $nim, 
    "k.*, s.Nama as STA");
  if (empty($khs)) {
    $buat = ($_SESSION['_LevelID'] == 120)? '' :
      "<hr size=1 color=silver />
      Opsi: Buat data semester Mhsw";
    echo PesanError("Error",
      "Mahasiswa <b>$nim</b> tidak terdaftar di Tahun Akd <b>$thn</b>.<br />
      Masukkan data yang valid. Hubungi Sysadmin untuk informasi lebih lanjut.
      $buat");
    return false;
  }
  else {
    return true;
  }
}
function TampilkanHeaderMhsw($thn, $mhsw, $khs) {
  $KRSMulai = FormatTanggal($thn['TglKRSMulai']);
  $KRSSelesai = FormatTanggal($thn['TglKRSSelesai']);
  $BayarMulai = FormatTanggal($thn['TglBayarMulai']);
  $BayarSelesai = FormatTanggal($thn['TglBayarSelesai']);
  $GelarPA = AmbilOneField('dosen', "KodeID='".KodeID."' and Login", $mhsw['PenasehatAkademik'], 'Gelar');
  // batas waktu
  $skrg = date('Y-m-d');
  if ($thn['TglKRSMulai'] <= $skrg && $skrg <= $thn['TglKRSSelesai']) {
    if ($_SESSION['_LevelID'] == 120) {
      $CetakKRS = "<a href='#' onClick=\"alert('Hubungi Staf TU/Adm Akademik untuk mencetak LRS/KRS.')\"><img src='img/print.png' /></a>";
      $CetakLRS = '';
    }
    else {
      $CetakKRS = "<input type=button name='CetakKRS' value='Cetak KRS' onClick=\"javascript:CetakKRS($khs[KHSID])\" />";
      $CetakLRS = "<input type=button name='CetakLRS' value='Cetak LRS' onClick=\"javascript:CetakLRS($khs[KHSID])\"/>";
    }
  }
  else {
    $CetakKRS = '&nbsp;';
    $CetakLRS = '&nbsp;';
  }
  $keu = BuatSummaryKeu($mhsw, $khs);
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:60%' align='center'>
  <tr>
      <th class=inp width=140>Mahasiswa</th>
      <th class=ul width=300>: $mhsw[Nama] - ($mhsw[MhswID])</th>
      <th class=inp width=80>Sesi</th>
      <th class=ul>: $khs[Sesi]</th>
      <th class=inp width=100>Status:</th>
      <th class=ul width=100>: $khs[STA] <sup>($khs[StatusMhswID])</sup></th>
      </tr>
  <tr>
      <th class=inp title='Dosen Pembimbing Akademik'>Dosen PA</th>
      <th class=ul>: $mhsw[namaDosen] <sup>$GelarPA</sup>&nbsp;</th>
      <th class=inp>Jml SKS</td>
      <th class=ul>: $khs[SKS]<sub title='Maksimum SKS yg boleh diambil'>&minus;$khs[MaxSKS]</sub></td>
      <th class=inp>Status Awal</td>
      <th class=ul>: $mhsw[STAWAL] <sup>($mhsw[StatusAwalID])</sup></th>
      </tr>
  <tr><td class=ul colspan=6>$keu</td></tr>
  </table>
  </div>
  </div>
  </div>";
}
function BuatSummaryKeu($mhsw, $khs) {
  $_Biaya = number_format($khs['Biaya']);
  $_Potongan = number_format($khs['Potongan']);
  $_Bayar = number_format($khs['Bayar']);
  $_Tarik = number_format($khs['Tarik']);
  $Sisa = $khs['Biaya'] - $khs['Potongan'] + $khs['Tarik'] - $khs['Bayar'];
  $_Sisa = number_format($Sisa);
  $color = ($Sisa > 0)? 'color=red' : '';
  $NamaBipot = AmbilOneField('bipot', 'BIPOTID', $mhsw['BIPOTID'], 'Tahun');
  $NamaBipot = (empty($NamaBipot))? 'Blm diset' : $NamaBipot;
  return <<<ESD
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>
  <tr style='background:purple;color:white'><td class=inp width=15% style='text-align:left'>Bipot</td>
      <td class=inp width=15% style='text-align:right'>Total Biaya</td>
      <td class=inp width=15% style='text-align:right'>Total Potongan</td>
      <td class=inp width=15% style='text-align:right'>Total Bayar</td>
      <td class=inp width=15% style='text-align:right'>Total Penarikan</td>
      <td class=inp style='text-align:right'>SISA</td>
      </tr>
  <tr><td class=ul align=left>$NamaBipot
      </td>
      <td class=ul align=right>$_Biaya</td>
      <td class=ul align=right>$_Potongan</td>
      <td class=ul align=right>$_Bayar</td>
      <td class=ul align=right>$_Tarik</td>
      <td class=ul align=right><font size=+1 $color>$_Sisa</font></td>
  </table>
  </div>
</div>
</div>
ESD;
}

function TampilkanDaftarKRSMhsw($thn, $mhsw, $khs) {
  global $koneksi;
  $s = "select k.*
    from krs k
    where k.KHSID = '$khs[KHSID]'
    order by k.MKKode";
  $r = mysqli_query($koneksi, $s); $n = 0;
  
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:70%' align='center'>
    <tr style='background:purple;color:white'> 
        <th class=ttl>#</th>
        <th class=ttl style='text-align:center'>Kode</th>
        <th class=ttl>Nama Matakuliah</th>
        <th class=ttl style='text-align:center'>SKS</th>
        <th class=ttl style='text-align:right'>Nilai Akhir</th>
        <th class=ttl style='text-align:center'>Grade</th>
        <th class=ttl style='text-align:center'>Bobot</th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    echo <<<ESD
    <tr>
        <td class=inp width=30>$n</td>
        <td class=ul width=100>$w[MKKode]</td>
        <td class=ul>$w[Nama]</td>
        <td class=ul align=right width=20>$w[SKS]</td>
        <td class=ul align=right width=120>$w[NilaiAkhir]</td>
        <td class=ul width=80 align=center>$w[GradeNilai]</td>
        <td class=ul width=30 align=right>$w[BobotNilai]</td>
        </tr>
ESD;
  }
  echo "</table>
  </div>
</div>
</div>";
}
?>
