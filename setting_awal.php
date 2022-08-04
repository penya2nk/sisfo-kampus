<?php
$AppNamex      = "SISFO_UTI";
$Organisasix   = 'TEKNOKRAT INDONESIA';
$Inisial       = "SISFO";
$Developerx    = "SISFO CAMP";
$DeveoperEmail = "admin@uti.ac.id";
$xURL          = "";
$themas        = "default";


if (!defined('KodeID')) define('KodeID', $Inisial);
global $koneksi;
// $oxID = AmbilFieldx('identitas', 'Kode', KodeID, '*');
$oxID = mysqli_fetch_array(mysqli_query($koneksi, "select * from identitas where Kode='$Inisial'"));

$_lf        = "\r\n";
$_defndelox = 'log_in';
$_maxbaris  = 10;

$_PMBDigit  = 4;

$arrBulan   = array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli','Agustus', 'September', 'Oktober', 'November', 'Desember');
$arrHari    = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');

?>
