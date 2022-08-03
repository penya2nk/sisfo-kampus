<?php
error_reporting(0);
session_start();
include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
  include_once "../header_pdf.php";

$TahunID = GainVariabelx('TahunID');
$DosenID = GainVariabelx('DosenID');
$dsn = AmbilFieldx('dosen', "Login='$DosenID' and KodeID", KodeID, "*");

ob_start();
$pdf = new FPDF();
$pdf->SetTitle("Jadwal Dosen");
$pdf->AddPage();

BuatHeader($TahunID, $dsn, $pdf);
AmbilJadwal($TahunID, $dsn, $pdf);

$pdf->Output();

function BuatHeader($TahunID, $dsn, $p) {
  $lbr = 190;
  $t = 5;
  $p->SetFont('Helvetica', 'B', 14);
  $p->Cell($lbr, $t, "Jadwal Mengajar - $TahunID", 0, 1, 'C');
  $p->Cell($lbr, $t, "Dosen: $dsn[Nama], $dsn[Gelar]", 0, 1, 'C');
  $p->Ln(2);
}
function AmbilJadwal($TahunID, $dsn, $p) {
	global $koneksi;
  $s = "select j.*,
      left(j.JamMulai, 5) as _JM,
      left(j.JamSelesai, 5) as _JS, 
	  k.Nama AS namaKelas
    from jadwal j
	LEFT OUTER JOIN kelas k ON k.KelasID = j.NamaKelas
    where j.TahunID = '$TahunID'
      and j.DosenID = '$dsn[Login]'
      and j.KodeID = '".KodeID."'
    order by j.HariID, j.JamMulai, j.JamSelesai";
  $r = mysqli_query($koneksi, $s);
  $n = 0; $t = 6; $hr = -25; $ttl = 0;
  while ($w = mysqli_fetch_array($r)) {
    if ($hr != $w['HariID']) {
      $hr = $w['HariID'];
      $NamaHari = AmbilOneField('hari', 'HariID', $hr, 'Nama');
      TampilkanHeaderTabel($NamaHari, $p);
    }
    $n++;
    $ttl += $w['SKS'];
    $p->SetFont('Helvetica', '', 9);
    $p->Cell(7, $t, $n, 1, 0);
    $p->Cell(20, $t, $w['_JM'] . '-' . $w['_JS'], 1, 0);
    $p->Cell(24, $t, $w['MKKode'], 1, 0);
    $p->Cell(70, $t, $w['Nama'], 1, 0);
    $p->Cell(8, $t, $w['SKS'], 1, 0, 'R');
    $p->Cell(18, $t, $w['namaKelas'], 1, 0);
    $p->Cell(18, $t, $w['RuangID'], 1, 0);
    $p->Cell(14, $t, $w['ProgramID'], 1, 0);
    $p->Cell(14, $t, $w['ProdiID'], 1, 0);
    
    $p->Ln($t);
  }
  $p->SetFont('Helvetica', '', 10);
  $p->Cell(100, $t, "Total SKS: ". $ttl, 0, 1);
}
function TampilkanHeaderTabel($NamaHari, $p) {
  $t = 5;
  $p->Ln(2);
  $p->SetFont('Helvetica', 'B', 10);
  $p->Cell(100, $t, $NamaHari, 0, 1);
  
  $p->SetFont('Helvetica', 'BI', 8);
  $p->Cell(7, $t, 'Nr', 1, 0);
  $p->Cell(20, $t, 'Jam Kuliah', 1, 0);
  $p->Cell(24, $t, 'Kode', 1, 0);
  $p->Cell(70, $t, 'Matakuliah', 1, 0);
  $p->Cell(8, $t, 'SKS', 1, 0);
  $p->Cell(18, $t, 'Kelas', 1, 0);
  $p->Cell(18, $t, 'Ruang', 1, 0);
  $p->Cell(14, $t, 'PRG', 1, 0);
  $p->Cell(14, $t, 'Prodi', 1, 0);
  $p->Ln($t);
}
?>