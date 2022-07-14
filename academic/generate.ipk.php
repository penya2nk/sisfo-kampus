<?php
// error_reporting(0);
$TahunID = GainVariabelx('TahunID');
$ProdiID = GainVariabelx('ProdiID');

TitleApps("GENERATE IPK SEMESTER");
$lungo = (empty($_REQUEST['lungo']))? 'ViewGenerateIPK' : $_REQUEST['lungo'];
$lungo();

function ViewGenerateIPK() {
  $optprodi = AmbilPenggunaProdi($_SESSION['_Login'], $_SESSION['ProdiID']);
  CheckFormScript('TahunID,ProdiID');
  echo "
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:50%' align='center'>
  <form action='?' method=POST onSubmit='return CheckForm(this)'>
  <input type=hidden name='lungo' value='TarikData' />
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <tr><td class=wrn width=2 rowspan=3></td>
      <td class=inp>Tahun Akademik</td>
      <td class=ul><input type=text name='TahunID' 
      value='$_SESSION[TahunID]' size=5 maxlength=5 /></td>
      </tr>
  <tr>
      <td class=inp>Program Studi</td>
      <td class=ul><select name='ProdiID'>$optprodi</select></td>
      </tr>
  <tr><td class=ul colspan=2>
      <input class='btn btn-success btn-sm' type=submit name='Submit' value='Generate IPK' />
      </td></tr>
  </form>
  </table>
  </div>
</div>
</div>";
}


function TarikData() {
  global $koneksi;
  $s = "select k.KHSID, k.MhswID, m.Nama
    from khs k
      left outer join mhsw m on k.MhswID = m.MhswID and m.KodeID = '".KodeID."'
    where k.KodeID = '".KodeID."'
      and k.TahunID = '$_SESSION[TahunID]'
      and k.ProdiID = '$_SESSION[ProdiID]'
      and k.NA = 'N'
    order by k.MhswID";
  $r = mysqli_query($koneksi, $s);
  $n = 0;
  while ($w = mysqli_fetch_array($r)) {
  	$_SESSION['PRC_IPK_KHSID_'.$n] = $w['KHSID'];
  	$_SESSION['PRC_IPK_MhswID_'.$n] = $w['MhswID'];
  	$_SESSION['PRC_IPK_Nama_'.$n] = $w['Nama'];
	$n++;
  }
  $_SESSION['PRC_IPK_TahunID'] = $_SESSION['TahunID'];
  $_SESSION['PRC_IPK_ProdiID'] = $_SESSION['ProdiID'];
  $_SESSION['PRC_IPK_JML'] = $n;
  $_SESSION['PRC_IPK_PRC'] = 0;

  echo Info("  Informasi",
    "Data IPK akan diproses dari Program Studi: <b>$_SESSION[ProdiID]</b> Tahun Akademik: <b>$_SESSION[TahunID]</b>.<br />
    Jumlah Data: <b>$_SESSION[PRC_IPK_JML]</b>.<br />
    Yakin akan diproses?<br>
	<br>
    <input class='btn btn-danger btn-sm' type=button name='Eksekusi' value='Klik Untuk Eksekusi'
      onClick=\"window.location='?ndelox=$_SESSION[ndelox]&lungo=Eksekusi'\" />
      <input class='btn btn-warning btn-sm' type=button name='Batal' value='Batal' 
      onClick=\"window.location='?ndelox=$_SESSION[ndelox]'\" />");
}
function KalkulasiIPS($TahunID, $MhswID, $KHSID) {
  // IPS menghitung semua nilai walau pun belum di finalisasi.
  //lama $data = AmbilFieldx('krs', "NA='N' and Tinggi='*' and KHSID", $KHSID,
  $data = AmbilFieldx('krs', "Final='Y' and Tinggi='*' and KHSID", $KHSID,
    "sum(BobotNilai * SKS)/sum(SKS) as BBT,
    sum(BobotNilai * SKS) as NK,
    sum(SKS) as TotSKS");
  return $data['BBT']+0;
}
function KalkulasiIPK($TahunID, $MhswID, $KHSID) {
  // Hitung IPK
  //and Final='Y' and
  global $koneksi;
  $SesiSkrg = AmbilOneField('khs', 'KHSID', $KHSID, 'Sesi')+0;
  $IPK = AmbilOneField('krs left outer join khs on krs.KHSID=khs.KHSID', "krs.KodeID='".KodeID."' and krs.Tinggi='*' and krs.NA='N' and (khs.Sesi <= $SesiSkrg or krs.KHSID=0) and krs.MhswID",
    $MhswID,
    "sum(krs.BobotNilai * krs.SKS)/sum(krs.SKS)");
  
  return $IPK+0;
}
function Eksekusi() {
	global $koneksi;
  $jml = $_SESSION['PRC_IPK_JML']+0;
  $prc = $_SESSION['PRC_IPK_PRC']+0;
  
  $TahunID = $_SESSION['PRC_IPK_TahunID'];
  $ProdiID = $_SESSION['PRC_IPK_ProdiID'];
  if ($prc < $jml) {
  	// Parameter
  	$KHSID = $_SESSION['PRC_IPK_KHSID_'.$prc]+0;
  	$MhswID = $_SESSION['PRC_IPK_MhswID_'.$prc];
  	$Nama = $_SESSION['PRC_IPK_Nama_'.$prc];
    // Eksekusi
	NilaiMaksReset($MhswID);
	NilaiMaksCreate($MhswID);
	
    $ips = KalkulasiIPS($TahunID, $MhswID, $KHSID);
    $ipk = KalkulasiIPK($TahunID, $MhswID, $KHSID);
    $s_ips = "update khs
      set IPS = $ips, IP = $ipk
      where KHSID = '$KHSID' ";
    $r_ips = mysqli_query($koneksi, $s_ips);

    // Tampilkan
    $persen = ($jml > 0)? $prc/$jml*100 : 0;
    $sisa = ($jml > 0)? 100-$persen : 0;
    $persen = number_format($persen);
    echo "<p align=center>
    <font size=+1>$persen %</font><br />
    <img src='img/B1.jpg' width=1 height=20 /><img src='img/B2.jpg' width=$persen height=20 /><img src='img/B3.jpg' width=$sisa height=20 /><img src='img/B1.jpg' width=1 height=20 /><br />
    Memproses: #$prc<br />
    $MhswID<br />
    <b>$Nama</b><br />
    <h1 align=center>
      IPS: $ips<br />
      IPK: $ipk
    </h1>
    </p>
    <hr size=1 color=silver />
    <p align=center>
      <input class='btn btn-danger btn-sm' type=button name='Batal' value='Batalkan' 
      onClick=\"window.location='?ndelox=$_SESSION[ndelox]'\" />
    </p>";

    // Next
    $_SESSION['PRC_IPK_PRC']++;
    // Reload
    $tmr = 10;
    echo <<<SCR
    <script>
    window.onload=setTimeout("window.location='?ndelox=$_SESSION[ndelox]&lungo=Eksekusi'", $tmr);
    </script>
SCR;
  }
  else echo Info("Proses Generate Selesai",
    "Generate IPK telah selesai.<br />
    Data yang berhasil digenerate: <b>$_SESSION[PRC_IPK_PRC]</b>.
    <hr size=1 color=silver />
    <input class='btn btn-success btn-sm' type=button name='Tutup' value='Kembali' 
    onClick=\"window.location='?ndelox=$_SESSION[ndelox]'\" />");
}

function NilaiMaksReset($MhswID) {
	global $koneksi;
  $s = "update krs set Tinggi = '' where MhswID='$MhswID' and KodeID='".KodeID."' ";
  $r = mysqli_query($koneksi, $s);
}

function NilaiMaksCreate($MhswID) {
  // Ambil semuanya dulu
  global $koneksi;
  $s = "select KRSID, MKKode, BobotNilai, GradeNilai, SKS, Tinggi
    from krs
    where KodeID = '".KodeID."'
      and MhswID = '$MhswID'
    order by MKKode";
  $r = mysqli_query($koneksi, $s);
  while ($w = mysqli_fetch_array($r)) {
    $ada = AmbilFieldx('krs', "Tinggi='*' and KRSID<>'$w[KRSID]' and MhswID='$MhswID' and MKKode", $w['MKKode'], '*');
    // Jika nilai sekarang lebih tinggi
    if ($w['BobotNilai'] > $ada['BobotNilai']) {
      $s1 = "update krs set Tinggi='*' where KRSID='$w[KRSID]' ";
      $r1 = mysqli_query($koneksi, $s1);
      // Cek yg lalu, kalau tinggi, maka reset
      if ($ada['Tinggi'] == '*') {
        $s1a = "update krs set Tinggi='' where KRSID='$ada[KRSID]' ";
        $r1a = mysqli_query($koneksi, $s1a);
      }
    }
    // Jika yg lama lebih tinggi, maka ga usah diapa2in
    else {
    }
  }
}

?>
