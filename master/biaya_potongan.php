<?php
function bipot() {
  include "biaya_potongan.nama.php";
  $sub = (empty($_REQUEST['sub']))? 'DftrBipotNama' : $_REQUEST['sub'];
  $sub();
}
function bipotmhsw() {
  include "biaya_potongan.master.php";
  $sub = (empty($_REQUEST['sub']))? 'DftrBipotMaster' : $_REQUEST['sub'];
  $sub();
}
function gradeipk() {
  include "biaya_potongan.gradeipk.php";
  $sub = (empty($_REQUEST['sub']))? 'SetupGradeIPK' : $_REQUEST['sub'];
  $sub();
}
function bipotdef() {
}

$arrBipot = array("MASTER BIAYA DAN POTONGAN->bipot",
  "BIAYA & POTONGAN MAHASISWA->bipotmhsw",
  "SETTING BEASISWA->gradeipk");

$prodi = GainVariabelx('prodi');
$prid = GainVariabelx('prid');
$bipotid = GainVariabelx('bipotid');
$tok = (empty($_REQUEST['tok']))? "bipot" : $_REQUEST['tok'];

TitleApps("SETTING BIAYA DAN POTONGAN MAHASISWA");
//TampilkanPilihanInstitusi($ndelox);

if (!empty($tok) && !empty($_SESSION['KodeID'])) {
  TabulasiSubMenu($_SESSION['ndelox'], $arrBipot, 'tok', $tok);
  $tok();
}
?>
