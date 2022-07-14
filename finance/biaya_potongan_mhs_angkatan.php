<?php
error_reporting(0);
$Angkatan = GainVariabelx('Angkatan');
$ProdiID = GainVariabelx('ProdiID');
$BIPOTID = GainVariabelx('BIPOTID');

TitleApps("SETTING BIAYA POTONGAN PER ANGKATAN");
$lungo = (empty($_REQUEST['lungo']))? 'TampilkanHeaderAngkatan' : $_REQUEST['lungo'];
$lungo();

function TampilkanHeaderAngkatan() {
  $optprodi = AmbilCombo2('prodi', "concat(ProdiID, ' - ', Nama)", 'ProdiID',
    $_SESSION['ProdiID'], "KodeID='".KodeID."'", 'ProdiID');
  $optbipot = AmbilCombo2('bipot', "concat(Tahun, ' - ', Nama)", 'Tahun Desc',
    $_SESSION['BIPOTID'], "KodeID='".KodeID."' and ProdiID='$_SESSION[ProdiID]'", 'BIPOTID');
  $rs = 6;
  if (!empty($_SESSION['ProdiID'])) {
    $mm = AmbilFieldx('mhsw', "KodeID='".KodeID."' and ProdiID", $_SESSION['ProdiID'],
      "min(TahunID) as _min, max(TahunID) as _max");
    $min = $mm['_min'];
    $max = $mm['_max'];
    $_mm = "$min &#8594; $max";
  }
  else {
    $_mm = '&nbsp;';
  }
  CheckFormScript('ProdiID,BIPOTID');
  echo <<<ESD
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:50%' align='center'>
  <form name='frm' action='?' method=POST onSubmit="return CheckForm(this)">
  <input type=hidden name='lungo' value='PeriksaDuluYa' />
  
  <tr style='background:purple;color:white'>
      <td class=ul colspan=2>
      <ul>
      <li>Anda akan mengisi field BIPOT ke master mahasiswa secara massal.</li>
      <li>Tentukan angkatan mahasiswa yang akan diproses dan pilih BIPOT yang akan digunakan.</li>
      <li>Mahasiswa yang telah memiliki BIPOT tidak akan diproses lagi.</li>
      </ul>
      </td>
     
      </tr>
  <tr>
      <td class=inp>Program Studi</td>
      <td class=ul><select name='ProdiID' onChange="location='?ndelox=$_SESSION[ndelox]&ProdiID='+frm.ProdiID.value">$optprodi</select></td>
      </tr>
  <tr><td class=inp>Angkatan Program Studi</td>
      <td class=ul>$_mm</td></tr>
  <tr><td class=inp>Angkatan</td>
      <td class=ul><input type=text name='Angkatan' value='$_SESSION[Angkatan]' size=4 maxlength=4></td>
      </tr> 
  <tr><td class=inp>Biaya dan Potongan</td>
      <td class=ul><select name='BIPOTID'>$optbipot</select></td>
      </tr>
	  <tr style='background:purple;color:white'><td colspan=2></td></tr>
  <tr><td class=ul colspan=2 align=left>
      <input class='btn btn-success btn-sm' type=submit name='Proses' value='Proses Biaya & Potongan Per Angkatan' />
      </td></tr>
  </form>
  </table>
  </div>
</div>
</div>
ESD;
}
function PeriksaDuluYa() {
	global $koneksi;
  $Angkatan = sqling($_REQUEST['Angkatan']);
  $ProdiID = sqling($_REQUEST['ProdiID']);
  $BIPOTID = $_REQUEST['BIPOTID'];
  
  $s = "select m.MhswID, m.ProdiID, m.ProgramID, k.TahunID
    from mhsw m
	left outer join khs k on k.MhswID = m.MhswID
    where m.KodeID = '".KodeID."'
      and m.ProdiID = '$ProdiID'
      and LEFT(k.TahunID,4) = '$Angkatan'
      and k.BIPOTID = 0
    order by k.MhswID";
  $r = mysqli_query($koneksi, $s); $n = 0;
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    $_SESSION['_bipotMhswID_'.$n] = $w['MhswID'];
    $_SESSION['_bipotProdiID_'.$n] = $w['ProdiID'];
    $_SESSION['_bipotProgramID_'.$n] = $w['ProgramID'];
    $_SESSION['_bipotTahunID_'.$n] = $w['TahunID'];
  }
  $_SESSION['_bipotBIPOTID'] = $BIPOTID;
  $_SESSION['_bipotJumlah'] = $n;
  $_SESSION['_bipotProgress'] = 1;
  echo Info("Proses Isi Biaya dan Potongan Mahasiswa",
    "Ada <b>$n</b> data mahasiswa yg akan diproses.<br />
    Klik Proses Untuk Melanjutkan.
    <hr size=1 color=silver />
    <input class='btn btn-success btn-sm' type=button name='Proses' value='Proses'
      onClick=\"javascript:ProsesBipotMhsw('$ProdiID', '$Angkatan')\" />
      <input class='btn btn-danger btn-sm' type=button name='Batal' value='Batal'
      onClick=\"location='?ndelox=$_SESSION[ndelox]'\" />");
  echo <<<ESD
  <script>
  <!--
  function ProsesBipotMhsw(prd, angk) {
    lnk = "$_SESSION[ndelox].go.php?ProdiID="+prd+"&Angkatan="+angk;
    win2 = window.open(lnk, "", "width=300, height=250, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  //-->
  </script>
ESD;
}

?>
