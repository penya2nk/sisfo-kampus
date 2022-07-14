<?php

// error_reporting(0);
session_start();
include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
include_once "../header_pdf.php";
  
// *** Parameters ***
$_PMBPeriodID = GainVariabelx('_PMBPeriodID');
// Tgl Mulai
$TglMulai_y = GainVariabelx('TglMulai_y', date('Y'));
$TglMulai_m = GainVariabelx('TglMulai_m', date('m'));
$TglMulai_d = GainVariabelx('TglMulai_d', date('d'));
$_SESSION['TglMulai'] = "$TglMulai_y-$TglMulai_m-$TglMulai_d";
// Tgl Selesai
$TglSelesai_y = GainVariabelx('TglSelesai_y', date('Y'));
$TglSelesai_m = GainVariabelx('TglSelesai_m', date('m'));
$TglSelesai_d = GainVariabelx('TglSelesai_d', date('d'));
$_SESSION['TglSelesai'] = "$TglSelesai_y-$TglSelesai_m-$TglSelesai_d";

// *** Main ***
$lungo = (empty($_REQUEST['lungo']))? 'KonfirmasiTgl' : $_REQUEST['lungo'];
$lungo();

// *** Functions ***
function KonfirmasiTgl() {
  KonfirmasiTanggal("../$_SESSION[ndelox].jualformulir.php", "Cetak");
}

function Cetak() {
  ob_start();
  $pdf = new PDF();
  $pdf->SetTitle("Penjualan Formulir");
  $pdf->AddPage();
  $lbr = 190;
  BuatJudulLaporan($_SESSION['_PMBPeriodID'], $pdf);
  BuatIsinya($_SESSION['_PMBPeriodID'], $pdf);

  $pdf->Output();
}
function BuatJudulLaporan($_fid, $p) {
  $lbr = 190; $t = 6;
  $Mulai = FormatTanggal($_SESSION['TglMulai']);
  $Selesai = FormatTanggal($_SESSION['TglSelesai']);
  $p->SetFont('Helvetica', 'B', 14);
  $p->Cell($lbr, $t, "Laporan Penjualan Formulir", 0, 1, 'C');
  $p->Cell($lbr, $t, "Periode: $Mulai ~ $Selesai", 0, 1, 'C');
  $p->Ln(2);
}
function BuatIsinya($period, $p) {
  global $koneksi;
  $s = "select j.*,
      date_format(j.Tanggal, '%d-%m-%Y') as TGL,
      format(j.Jumlah, 0) as JML
    from pmbformjual j
    where j.PMBPeriodID = '$period'
      and j.KodeID = '".KodeID."'
      and '$_SESSION[TglMulai]' <= j.Tanggal
      and j.Tanggal <= '$_SESSION[TglSelesai]'
      and j.NA = 'N'
    order by j.PMBFormulirID, j.Tanggal";
  //echo "Select: $s";
  $r = mysqli_query($koneksi, $s);
  
  $n = 0; $t = 5; $jml = 0; $_fid = 'a9879sadf'; $_fid0 = $_fid;
  while ($w = mysqli_fetch_array($r)) {
    if ($_fid != $w['PMBFormulirID']) {
      if ($_fid != $_fid0) {
        BuatTotalnya($jml, $p);
      }
      $_fid = $w['PMBFormulirID'];
      $jml = 0; $n = 0;
      BuatHeaderTabel($_fid, $p);
    }
    $n++;
    $jml += $w['Jumlah'];
    $p->SetFont('Helvetica', '', 10);
    $p->Cell(13, $t, $n, 'LB', 0);
    $p->Cell(22, $t, $w['TGL'], 'B', 0);
    $p->Cell(30, $t, $w['BuktiSetoran'], 'B', 0);
    $p->Cell(50, $t, $w['Nama'], 'B', 0);
    $p->Cell(22, $t, $w['JML'], 'B', 0, 'R');
    $p->Cell(55, $t, $w['Keterangan'], 'BR', 0);
    
    $p->Ln($t);
  }
  BuatTotalnya($jml, $p);
}
function BuatHeaderTabel($fid, $p) {
  $FRM = AmbilOneField('pmbformulir', 'PMBFormulirID', $fid, 'Nama');
  $t = 6;
  $p->SetFont('Helvetica', 'B', 10);
  // Judul Formulir
  $p->Cell(190, $t, $FRM, 0, 1);
  
  // Judul Kolom
  $p->Cell(13, $t, 'Nmr', 1, 0);
  $p->Cell(22, $t, 'Tanggal', 1, 0);
  $p->Cell(30, $t, 'Bukti Setoran', 1, 0);
  $p->Cell(50, $t, 'Nama Pembeli', 1, 0);
  $p->Cell(22, $t, 'Jumlah', 1, 0, 'R');
  $p->Cell(55, $t, 'Keterangan', 1, 0);
  
  $p->Ln($t);
}
function BuatTotalnya($jml, $p) {
  $t = 6;
  $_jml = number_format($jml);
  $p->SetFont('Helvetica', 'B', 10);
  $p->Cell(112, $t, 'TOTAL :', 0, 0, 'R');
  $p->Cell(25, $t, $_jml, 0, 0, 'R');
  $p->Ln($t+1);
}
?>
