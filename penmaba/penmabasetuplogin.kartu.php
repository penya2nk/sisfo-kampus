<?php

error_reporting(0);
session_start();

include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
  include_once "../util.lib.php";
  include_once "../header_pdf.php";

$gel = $_REQUEST['gel'];
//$gels = AmbilFieldx('pmbperiod', "KodeID='".KodeID."' and PMBPeriodID", $gel, "*");
$id = $_REQUEST['id'];

$lbr = 190;

//leweh add ob_start(); while error at php higher version
ob_start();
//end leweh add

$pdf = new PDF('P', 'mm', 'A4');
$pdf->SetTitle("Keterangan Login Aplikan");
$pdf->AddPage('P');

TampilkanIsinya($gel, $id, $pdf);

$pdf->Output();

// *** Functions ***
function TampilkanIsinya($gel, $id, $p) {
  $t = 6;
  $Kolom1 = 50; $Kolom2 = 3; $Kolom3 = 100;
  
  $aplikan = AmbilFieldx('aplikan', "AplikanID='$id' and KodeID", KodeID, "*");
  
  $p->SetFont('Helvetica', '', 11, 'C');
  
  $p->Cell($Kolom1, $t, 'Aplikan ID', 0, 0);
  $p->Cell($Kolom2, $t, ':', 0, 0, 'C');
  $p->Cell($Kolom3, $t, $aplikan['AplikanID'], 0, 0);
  $p->Ln($t);
  
  $p->Cell($Kolom1, $t, 'Nama', 0, 0);
  $p->Cell($Kolom2, $t, ':', 0, 0, 'C');
  $p->Cell($Kolom3, $t, $aplikan['Nama'], 0, 0);
  $p->Ln($t);
  
  $p->Cell($Kolom1, $t, 'Tanggal Lahir', 0, 0);
  $p->Cell($Kolom2, $t, ':', 0, 0, 'C');
  $p->Cell($Kolom3, $t, GetDateInWords($aplikan['TanggalLahir']), 0, 0);
  $p->Ln($t);
  $p->Ln($t);
  
  $p->SetFont('Helvetica', 'B', 11, 'C');
  $p->Cell(0, $t, 'Catatan: Password default untuk aplikan baru adalah Tanggal Lahir yang tertera.', 0, 0);
  $p->Ln($t);
  $p->Cell(0, $t, '         Masukkan password dengan format TTTT-BB-HH. Contoh: 1999-12-31 untuk 31 Desember 1999', 0, 0);
  $p->Ln($t);
}
?>
