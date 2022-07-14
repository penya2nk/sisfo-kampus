<?php

error_reporting(0);
session_start();

include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
  include_once "../header_pdf.php";

$gel = sqling($_REQUEST['gel']);
$presenterid = $_SESSION['_curPres'];

$lbr = 190;

ob_start();
$pdf = new PDF();
$pdf->SetTitle("List Aplikan - $gel");
$pdf->AddPage();

BuatJudulLaporan($presenterid, $gel, $pdf);
BuatDaftarAplikan($presenterid, $gel, $pdf);

$pdf->Output();

function BuatJudulLaporan($id, $gel, $p) {
  global $lbr;
  $p->SetFont('Helvetica', 'B', 14);
  $p->Ln(3);
  $p->Cell($lbr, 5, "Daftar Aplikan yang Datang pada Tanggal ".date('Y-m-d'), 0, 1, 'C');
  $presenter = AmbilOneField('presenter', "KodeID='".KodeID."' and PresenterID", $id, 'Nama');
  if(!empty($id)) { $p->SetFont('Helvetica', 'BI', 12); $p->Ln(3); $p->Cell($lbr, 5, "Presenter: ".$id." - $presenter" , 0, 1 ); }
  $p->Ln(3);
}
function BuatDaftarAplikan($id, $gel, $p) {
  global $lbr, $koneksi;
  $presenter = (empty($id))? '' : "and a.PresenterID='$id'";
  $current_date=date('Y-m-d');
  $s = "select a.PMBID, a.Nama, a.TempatLahir, a.TanggalLahir,
    date_format(a.TanggalLahir, '%Y-%m-%d') as _TanggalLahir,
    a.Telepon, a.Kota, a.Pilihan1, a.Pilihan2, a.ProgramID, a.PresenterID, 
    if(b.Nama like '_%', b.Nama, 
		if(pt.Nama like '_%', pt.Nama, a.AsalSekolah)) as _NamaSekolah, 
	a.LoginBuat, a.TanggalBuat
    from aplikan a
		left outer join asalsekolah b on a.AsalSekolah=b.SekolahID
		left outer join perguruantinggi pt on a.AsalSekolah=pt.PerguruanTinggiID
    where a.KodeID='".KodeID."' $presenter and left(a.TanggalBuat,10)='$current_date'
    order by Nama";
  $r = mysqli_query($koneksi, $s);
  $nx = mysqli_num_rows($r);
  //$p->Cell(10, 5, $nx, 1, 1, 'C');
  $t = 5; 
  
  //$p->SetFont('Helvetica', 'I', 8);
  //$p-> Cell(10, $t, $s, 1, 1, 'C');
  
  BuatHeaderTabel($p, $id);
  
  while ($w = mysqli_fetch_array($r)) {
    
	/*if ($pr != $w['LoginBuat']) {
      $pr = $w['LoginBuat'];
      $p->SetFont('Helvetica', 'B', 10);
      $p->Cell($lbr, $t, $pr, 'B', 1);
      
      $n = 0;
    }*/
    $n++;
	$prodi = implode(',', array($w['Pilihan1'], $w['Pilihan2']));
    $p->SetFont('Helvetica', '', 8);
    $p->Cell(7, $t, $n.'.', 1, 0, 'C');
    $p->Cell(35, $t, $w['Nama'], 1, 0);
	$p->Cell(20, $t, $w['TempatLahir'], 1, 0, 'C');
    $p->Cell(20, $t, $w['_TanggalLahir'], 1, 0, 'C');
    $p->Cell(64, $t, substr($w['_NamaSekolah'], 0, 36), 1, 0);
    $p->Cell(26, $t, $prodi, 1, 0);
	$p->Cell(10, $t, $w['ProgramID'], 1, 0);
	if(empty($id))$p->Cell(10, $t, $w['PresenterID'], 1, 0);
	
    $p->Ln($t);
  }
  $p->Ln(2);
  $p->Cell($lbr, $t, "Dicetak oleh: " . $_SESSION['_Login'] . ", " . date('Y-m-d'), 0, 1);
}
function BuatHeaderTabel($p, $id) {
   $t = 6;
   $p->SetFont('Helvetica', 'B', 9);
   $p->Cell(7, $t, 'No.', 1, 0);
   $p->Cell(35, $t, 'Nama Aplikan', 1, 0);
   $p->Cell(20, $t, 'Tempat Lahir', 1, 0, 'C'); 
   $p->Cell(20, $t, 'Tgl Lahir', 1, 0, 'C');
   $p->Cell(64, $t, 'Asal Sekolah', 1, 0);
   $p->Cell(26, $t, 'Pilihan D3/S1', 1, 0);
   $p->Cell(10, $t, 'Prog.', 1, 0);
   if(empty($id))$p->Cell(10, $t, 'Pres.', 1, 0);
   $p->Ln($t); 
}

?>
