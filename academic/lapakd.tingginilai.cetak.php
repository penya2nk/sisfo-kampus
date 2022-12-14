<?php
error_reporting(0);
session_start();

include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
include_once "../header_pdf.php";

function TampilkanFilterNPM() {
  $optprg = AmbilCombo2("program", "concat(ProgramID, ' - ', Nama)", "ProgramID", $_SESSION['prid'], '', 'ProgramID');
  $optprd = AmbilCombo2("prodi", "concat(ProdiID, ' - ', Nama)", "ProdiID", $_SESSION['prodi'], '', 'ProdiID');
  echo <<<END
  <p><table class=box cellspacing=1 cellpadding=4>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='akd.lap.tingginilai.cetak'>
  <tr><td class=ul colspan=2><b>$_SESSION[KodeID]</td></tr>
  <tr><td class=inp>Tahun Akd</td><td class=ul><input type=text name='tahun' value='$_SESSION[tahun]' size=10 maxlength=10></td></tr>
  <tr><td class=inp>Program</td><td class=ul><select name='prid' onChange='this.form.submit()'>$optprg</select></td></tr>
  <tr><td class=inp>Program Studi</td><td class=ul><select name='prodi' onChange='this.form.submit()'>$optprd</select></td></tr>
  <tr><td class=inp>Dari NPM</td>
      <td class=ul><input type=text name='DariNPM' value='$_SESSION[DariNPM]' size=20 maxlength=50> s/d
      <input type=text name='SampaiNPM' value='$_SESSION[SampaiNPM]' size=20 maxlength=50>
      </td></tr>
  
  <tr><td colspan=2><input type=submit name='Filter' value='Filter'></td></tr>
  </form></table></p>
END;
}

  
function CetakTinggiNilai() {
	global $koneksi;
  if (!empty($_SESSION['DariNPM'])) {
    $_SESSION['SampaiNPM'] = (empty($_SESSION['SampaiNPM']))? $_SESSION['DariNPM'] : $_SESSION['SampaiNPM'];
    $_npm = "and '$_SESSION[DariNPM]' <= khs.MhswID and khs.MhswID <= '$_SESSION[SampaiNPM]' ";
  } else $_npm = '';
  $s = "select khs.*, sm.Nama as STT, m.Nama
    from khs khs
      left outer join statusmhsw sm on khs.StatusMhswID=sm.StatusMhswID
	  left outer join mhsw m on khs.MhswID = m.MhswID
    where khs.TahunID='$_SESSION[tahun]'
      and m.ProgramID='$_SESSION[prid]'
      and khs.ProdiID='$_SESSION[prodi]'
      and khs.StatusMhswID in ('A','P')
	    $_npm
    order by khs.mhswid, khs.Sesi";
  $r = mysqli_query($koneksi, $s);
  $n = 1;
  //echo "<pre>$s</pre>";
  echo "<p><table class=box cellspacing=1 cellpadding=4>";
  echo "<form action='?' method=POST>
	<input type=hidden name='ndelox' value='akd.lap.tingginilai.cetak'>
	<input type=hidden name='lungo' value='CetakNilaiMhsw'>
	<tr><th class=ttl>#</th>
	<th class=ttl>NPM</th>
    <th class=ttl>Nama</th>
    <th class=ttl>SKS</th>
    <th class=ttl>MK</th>
    <th class=ttl><input type=submit name='Cetak' value='Cetak'></th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
    if ($w['TahunID'] == $_SESSION['tahun']) {
      $c = "class=ul";
      //$ctk = "<a href='kss.cetak.php?tahun=$w[TahunID]&mhswid=$w[MhswID]&khsid=$w[KHSID]' target=_blank><img src='img/printer.gif'></a>";
      $ctk1 = "<a href='?ndelox=akd.lap.nilaitinggi.cetak&lungo=CetakNilaiMhsw&tahun=$w[TahunID]&mhswid=$w[MhswID]&khsid=$w[KHSID]'>
        <img src='img/printer.gif'></a>";
      
    }
    else {
      $c = "class=ul";
      $ctk = "&nbsp;";
    } 
    if ($w['TahunID'] == $_SESSION['tahun']) { 
        $ctk = "<a href='?ndelox=akd.lap.tingginilai.cetak&lungo=CetakNilaiMhsw&khsid[]=$w[KHSID]'><img src='img/printer.gif'></a>";
    } else $ctk = '&nbsp;';
    echo "<tr><td class=inp>$n</td>
	  <td $c>$w[MhswID]</td>
    <td $c>$w[Nama]</td>
      <td $c align=right>$w[TotalSKS]</td>
      <td $c align=right>$w[JumlahMK]</td>
      <td $c align=center><input type=checkbox name='khsid[]' value='$w[KHSID]' checked>$ctk</td>
      </tr>";
	  $n++;
  }
  echo "</form></table></p>";
}


function CetakNilaiMhsw() {
  // Parameter
  $khsid = array();
  $khsid = $_REQUEST['khsid'];
  $_SESSION['khsid'] = $khsid;
  $nmf = "tmp/$_SESSION[_Login].dwoprn";
  $jml = sizeof($khsid);
  $_SESSION['NILAI-FILE'] = $nmf;
  $_SESSION['NILAI-POS'] = 0;
  $_SESSION['NILAI-MAX'] = $jml;
  // Buat file
  $f = fopen($nmf, 'w');
  fwrite($f, '');
  fclose($f);

  echo "<p>Anda akan mencetak <font size=+2>".$jml."</font> mahasiswa</p>";
  // buat IFrame
  echo "<p><iframe src='akd.lap.tingginilai.php' frameborder=0>
  </iframe></p>";
}

// *** Parameters ***
//$crmhswid = GainVariabelx('crmhswid');
$tahun = GainVariabelx('tahun');
$DariNPM = GainVariabelx('DariNPM');
$SampaiNPM = GainVariabelx('SampaiNPM');
$prid = GainVariabelx('prid');
$prodi = GainVariabelx('prodi');
$mhswid1 = GainVariabelx('mhswid1');
$lungo = (empty($_REQUEST['lungo']))? "CetakTinggiNilai" : $_REQUEST['lungo'];


// *** Main ***
TitleApps("Cetak History Nilai Tertinggi Per Mahasiswa");
TampilkanFilterNPM();
//TampilkanPilihanAutodebet();
//RadioAuto();
if (!empty($tahun) && !empty($prid) && !empty($prodi)) {
  $lungo();
}
  
?>
