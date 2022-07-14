<?php
error_reporting(0);
session_start();

include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
  include_once "../header_pdf.php";

$PMBPeriodID = GainVariabelx('PMBPeriodID');
$gels = AmbilFieldx('pmbperiod', "KodeID='".KodeID."' and PMBPeriodID", $PMBPeriodID, "*");

$lbr = 190;

ob_start();
$pdf = new PDF();
$pdf->SetTitle("Pengumuman PMB");
$pdf->AddPage('P');

CetakHeader($gels, $pdf);
CetakDataLulus($gels, $pdf);
CetakFooter($pdf);

$pdf->Output();

// *** Function ***
function CetakFooter($p) {
  global $arrID;
  $t = 6;
  $mrg = 120; $lbr = 80;
  
  $p->Ln(3);
  $p->Cell($mrg);
  $p->Cell($lbr, $t, $arrID['Kota'] . ', ' . date('d-m-Y'), 0, 1);

  $ketua = AmbilFieldx('pejabat', "KodeID = '".KodeID."' and KodeJabatan", 'KETUA', "*");
  $p->Cell($mrg);
  $p->Cell($lbr, $t, $ketua['Jabatan'], 0, 1);
  $p->Ln(10);
  $p->Cell($mrg);
  $p->Cell($lbr, $t, $ketua['Nama'], 0, 1);
  $p->Cell($mrg);
  $p->Cell($lbr, $t, "NIP: " . $ketua['NIP'], 0, 1);
}
function CetakDataLulus($gels, $p) {
	global $koneksi;
  $s = "select p.PMBID, p.Nama, p.AsalSekolah, p.NilaiUjian, p.NilaiSekolah,
      p.ProdiID, p.ProgramID,
      prg.Nama as _PRG, prd.Nama as _PRD
    from pmb p
      left outer join program prg on prg.ProgramID = p.ProgramID and prg.KodeID='".KodeID."'
      left outer join prodi prd on prd.ProdiID = p.ProdiID and prd.KodeID='".KodeID."'
    where p.KodeID = '".KodeID."'
      and p.PMBPeriodID = '$gels[PMBPeriodID]'
      and p.LulusUjian = 'Y'
    order by p.ProdiID, p.ProgramID, p.PMBID ";
  $r = mysqli_query($koneksi, $s);
  $n = 0;
  $t = 6;
  
  $pr = 'alskdjflaksjdf';
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    if ($pr != $w['ProdiID'].$w['ProgramID']) {
      $pr = $w['ProdiID'].$w['ProgramID'];
      $p->Ln(1);
      $p->SetFont('Helvetica', 'B', 11);
      $p->Cell(190, 8, "Program Studi: $w[_PRD] ~ $w[_PRG]", 0, 1);
      BuatHeaderTabel($p);
    }
    $p->SetFont('Helvetica', '', 10);
    $p->Cell(16, $t, $n, 'LB', 0, 'R');
    $p->Cell(24, $t, $w['PMBID'], 'B', 0);
    $p->Cell(60, $t, $w['Nama'], 'B', 0);
    $p->Cell(70, $t, $w['AsalSekolah'], 'B', 0);
    $p->Cell(10, $t, $w['NilaiSekolah'], 'B', 0, 'R');
    $p->Cell(10, $t, $w['NilaiUjian'], 'BR', 0, 'R');
    
    $p->Ln($t);
  }
}

function BuatHeaderTabel($p) {
  $t = 7;
  $p->SetFont('Helvetica', 'B', 9);
  $p->Cell(16, $t, 'No.', 1, 0, 'C');
  $p->Cell(24, $t, 'No. PMB', 1, 0, 'C');
  $p->Cell(60, $t, 'Nama Calon Mhsw', 1, 0);
  $p->Cell(70, $t, 'Asal Sekolah', 1, 0);
  $p->Cell(10, $t, 'NEM', 1, 0);
  $p->Cell(10, $t, 'USM', 1, 0);
  
  $p->Ln($t);
}
function CetakHeader($gels, $pdf) {
  $pdf->SetFont('Helvetica', 'B', 14);
  $pdf->Cell($lbr, 9, "Pengumuman Kelulusan Ujian Saringan Masuk PMB", 0, 1, 'C');
  $pdf->SetFont('Helvetica', 'B', 12);
  $pdf->Cell($lbr, 8, $gels['Nama'], 0, 1, 'C');
}
?>
