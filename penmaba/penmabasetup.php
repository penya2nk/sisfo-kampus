<?php
$arrSetupPMB = array('Gelombang PMB~gelombang',
  'Daftar Presenter~presenter',
  'Sumber Informasi~sumberinfo',
  'Prasyarat Formulir~prasyarat',
  'Formulir PMB~formulir',
  'Komponen USM~usm',
  'Prodi-USM~prodiusm',
  'Prodi-Wawancara~wawancarausm',
  'Status Awal~stawal',
  'PMB Grade~pmbgrade');
$idxPMB = GainVariabelx('idxPMB', 0);

TitleApps("Setup PMB");
TampilkanMenuPMB($arrSetupPMB, $idxPMB);
if (empty($_REQUEST['lungo'])) {
  $_gos = $arrSetupPMB[$idxPMB];
  $_gos1 = explode('~', $_gos);
  $lungo = $_gos1[1];
}
else $lungo = $_REQUEST['lungo'];
include_once $_SESSION['ndelox'].'.'.$lungo.'.php';


function TampilkanMenuPMB($arr, $idx) {
  $i = 0;
  echo "
  <div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:50%;background:white' align='center' border='1'>";
  foreach ($arr as $a) {
    $_a = explode('~', $a);
    $sel = ($idx == $i)? 'class=menuaktif' : 'class=menuitem';
    echo "<td $sel style='text-align:center'><a href='?ndelox=$_SESSION[ndelox]&idxPMB=$i&lungo=".$_a[1]."'>".$_a[0]."</a></td>";
    $i++;
  }
  echo "</table>
  </div>
  </div>
  </div>";
}
?>
