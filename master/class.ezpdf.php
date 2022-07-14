<?php
function Def() {
  global $ndelox, $pref, $token;
  include_once "$ndelox.$_SESSION[$pref].php";
  $sub = (!empty($_REQUEST['sub']))? $_REQUEST['sub'] : "Def$_SESSION[$pref]";
  $sub();
}

// *** Parameters ***
$pref = 'mk';
$arrMK = array(
  'Kurikulum->Kur', 
  'Konsentrasi->Kons',
  'Jenis Mata Kuliah->Jen', 
  'Pilihan Wajib->Pil',
  'Jenis Kurikulum->JenKur',
  'Mata Kuliah->MK',
  'Mata Kuliah Setara->MKSet',
  'Nilai->Nil',
  'MaxSKS->MaxSKS',
  //'Kehadiran SKS->HadirSKS', 
  'Paket Matakuliah->Pkt',
  "Predikat->pred");
$tokendef = 'MK';
$ndelox = $_SESSION['ndelox'];
$token = GainVariabelx($pref, $tokendef);
$prodi = GainVariabelx('prodi');
$kurid = GainVariabelx("kurid_$prodi");
$mkkode = GainVariabelx("mkkode_$prodi");
if (empty($kurid) && !empty($prodi)) {
  $_kurid = AmbilOneField("kurikulum", "NA='N' and ProdiID", $prodi, "KurikulumID");
  $_SESSION["kurid_$prodi"] = $_kurid;
  $kurid = $_kurid;
}

// *** Main ***
TitleApps("Administrasi Mata Kuliah");
if (empty($_SESSION['_ProdiID'])) echo PesanError('Tidak Ada Hak Akses',
  "Anda tidak memiliki hak akses terhadap modul ini.<br>
  Hubungi Superuser/Administrator untuk memberikan hak akses terhadap program studi.");
else {
  TampilkanSubMenu($_SESSION['ndelox'], $arrMK, $pref, $token);
  if (!empty($token)) Def();
}
?>
