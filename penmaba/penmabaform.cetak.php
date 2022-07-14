<?php

error_reporting(0);
session_start();
include_once "../sisfokampus1.php";
include_once "../header_surat.php";

ViewHeaderApps("Kartu Peserta Test");

$id = sqling($_REQUEST['id']);
$w = AmbilFieldx('pmb', "KodeID='".KodeID."' and PMBID", $id, '*');
$arrID = AmbilFieldx('identitas', 'Kode', KodeID, '*');

CetakKartu($arrID, $w);

function CetakKartu($arrID, $w) {
  BuatHeader($arrID);
}

?>
