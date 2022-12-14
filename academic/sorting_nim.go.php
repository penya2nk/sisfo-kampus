<?php
error_reporting(0);
session_start();
include_once "../academic_sisfo1.php";
ViewHeaderApps("GENERATE NIM SEMENTARA");

$TahunID = GainVariabelx('TahunID');
$ProdiID = GainVariabelx('ProdiID');
$ProgramID = GainVariabelx('ProgramID');
$StatusAwalID = GainVariabelx('StatusAwalID');

TitleApps("GENERATE NIM SEMENTARA");
$lungo = (empty($_REQUEST['lungo']))? 'AmbilData' : $_REQUEST['lungo'];
$lungo();

function AmbilData() {
	global $koneksi;
  $tmp = AmbilOneField('prodi', 'ProdiID', $_SESSION['ProdiID'], 'FormatNIMSementara'); 
  $tmp = str_replace('~YY~', substr($_SESSION['TahunID'], 2, 2), $tmp);
  $tmp = str_replace('~YYYY~', substr($_SESSION['TahunID'], 0, 4), $tmp);
  $tmp = str_replace('~PRG~', $_SESSION['ProgramID'], $tmp);
  $tmp = str_replace('~STAWAL~', $_SESSION['StatusAwalID'], $tmp);
  $digit = substr($tmp, strpos($tmp, '~NMR')+4, 1)+0;
  $tmp = str_replace('~NMR3~', '~NMR~', $tmp); 
  $tmp = str_replace('~NMR4~', '~NMR~', $tmp);
  $tmp = str_replace('~NMR5~', '~NMR~', $tmp);  
  $pos = strpos($tmp, '~NMR~');
  $pattern = substr($tmp, 0, $pos);
  $rpattern = substr($tmp, $pos+5);
  $whr_pattern = (!empty($pattern))? "and LEFT(m.MhswID, ".strlen($pattern).") = '$pattern'" : ""; 
  $whr_rpattern = (!empty($rpattern))? "and RIGHT(m.MhswID, ".strlen($rpattern).") = '$rpattern'" : "";
  
  $s = "select m.MhswID, m.Nama
    from mhsw m left outer join statusmhsw sm on m.StatusMhswID=sm.StatusMhswID
    where m.KodeID = '".KodeID."'
      and m.TahunID = '$_SESSION[TahunID]'
      and m.ProdiID = '$_SESSION[ProdiID]'
	  $whr_pattern
	  $whr_rpattern
	  and sm.Keluar='N'
      and m.NA = 'N'
    order by m.MhswID";
  $r = mysqli_query($koneksi, $s);
  $n = 0;
  while ($w = mysqli_fetch_array($r)) {
  	$_SESSION['PRC_URUTNIMS_MhswID_'.$n] = $w['MhswID'];
	$n++;
  }
  $_SESSION['PRC_URUTNIMS_TahunID'] = $_SESSION['TahunID'];
  $_SESSION['PRC_URUTNIMS_ProdiID'] = $_SESSION['ProdiID'];
  $_SESSION['PRC_URUTNIMS_ProgramID'] = $_SESSION['ProgramID'];
  $_SESSION['PRC_URUTNIMS_StatusAwalID'] = $_SESSION['StatusAwalID'];
  $_SESSION['PRC_URUTNIMS_JML'] = $n;
  $_SESSION['PRC_URUTNIMS_PRC'] = 0;
  // Tampilkan konfirmasi
  echo Info("Info Eksekusi",
    "Anda akan memproses NIM tetap dari mahasiswa yang aktif pada: </br>
	Prodi: <b>$_SESSION[ProdiID]</b></br>
	Tahun Akd: <b>$_SESSION[TahunID]</b></br>
	Jumlah yg akan diproses: <b>$_SESSION[PRC_URUTNIMS_JML]</b>.<br />
    Anda yakin akan memprosesnya?
    <hr size=1 color=silver />
    Opsi: <input type=button name='Eksekusi' value='Eksekusi Sekarang'
      onClick=\"window.location='../$_SESSION[ndelox].go.php?lungo=Eksekusi'\" />
      <input type=button name='Batal' value='Batal' onClick=\"window.close()\" />");
}

function Eksekusi() {
	global $koneksi;
  $jml = $_SESSION['PRC_URUTNIMS_JML']+0;
  $prc = $_SESSION['PRC_URUTNIMS_PRC']+0;
  
  if ($prc < $jml) {
  	// Parameter
  	$MhswIDLama = $_SESSION['PRC_URUTNIMS_MhswID_'.$prc];
    $NIM = GetNextNIM($_SESSION['TahunID'], AmbilFieldx('mhsw', "MhswID='$MhswIDLama' and KodeID", KodeID, '*'));
	// Eksekusi
    // PMB
	$arrTablesToUpdate = array('bayarmhsw', 'bipotmhsw', 'khs', 'krs', 'pmb', 'presensimhsw', 'prosesstatusmhsw');
	foreach($arrTablesToUpdate as $table)
	{	$s1 = "update $table set MhswID = '$NIM' where MhswID='$MhswIDLama'";
		$r1 = mysqli_query($koneksi, $s1);
	}
	$s1 = "update mhsw set MhswID = '$NIM', NIMSementara = 'N', Login = '$NIM', MhswIDLama='$MhswIDLama' where MhswID='$MhswIDLama'";
    $r1 = mysqli_query($koneksi, $s1);
    // Tampilkan
    $persen = ($jml > 0)? $prc/$jml*100 : 0;
    $sisa = ($jml > 0)? 100-$persen : 0;
    $persen = number_format($persen);
    echo "<p align=center>
    <font size=+1>$persen %</font><br />
    <img src='../img/B1.jpg' width=1 height=20 /><img src='../img/B2.jpg' width=$persen height=20 /><img src='../img/B3.jpg' width=$sisa height=20 /><img src='../img/B1.jpg' width=1 height=20 /><br />
    Memproses: #$prc<br />
    <sup>$MhswIDLama</sup><br />
    <h1 align=center>
      MhswID Baru : $NIM
    </h1>
    </p>
    <hr size=1 color=silver />
    <p align=center>
      <input type=button name='Batal' value='Batalkan' onClick=\"window.close()\" />
    </p>";

    // Next
    $_SESSION['PRC_URUTNIMS_PRC']++;
    // Reload
    $tmr = 10;
    echo <<<SCR
    <script>
    window.onload=setTimeout("window.location='../$_SESSION[ndelox].go.php?lungo=Eksekusi'", $tmr);
    </script>
SCR;
  }
  else echo Info("Eksekusi Selesai",
    "Eksekusi telah selesai.<br />
    Data yang berhasil diproses: <b>$_SESSION[PRC_URUTNIMS_PRC]</b>.
    <hr size=1 color=silver />
    <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" />");
}
?>
