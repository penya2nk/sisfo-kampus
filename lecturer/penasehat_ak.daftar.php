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
$pdf = new PDF();
$pdf->SetTitle("Jadwal Dosen");
$pdf->AddPage();

// Buat header dulu
BuatHeader($dsn, $pdf);
// Tampilkan datanya
DaftarMhsw($dsn, $pdf);

$pdf->Output();

function BuatHeader($dsn, $p) {
  $lbr = 190;
  $t = 6;
  $p->SetFont('Helvetica', 'B', 14);
  $p->Cell($lbr, $t, "Daftar Mahasiswa", 0, 1, 'C');
  $p->SetFont('Helvetica', 'B', 12);
  $p->Cell($lbr, $t, "Dosen PA: $dsn[Nama], $dsn[Gelar]", 0, 1, 'C');
  $p->Ln(2);
}
function DaftarMhsw($dsn, $p) {
	global $koneksi;
  $s = "select m.MhswID, m.Nama as NamaMhsw, m.TahunID,
    m.ProdiID, p.Nama as _Prodi, m.ProdiID
    from mhsw m
      left outer join prodi p on p.ProdiID = m.ProdiID and p.KodeID = '".KodeID."'
    where m.KodeID = '".KodeID."'
      and m.PenasehatAkademik = '$dsn[Login]'
      and m.Keluar = 'N'
    order by m.TahunID, m.ProdiID, m.MhswID";
  $r = mysqli_query($koneksi, $s);
  $n = 0; $t = 5; $_prd = 'lkasjdhfaksdjkhf-19823';
  $lbr = 190;
  while ($w = mysqli_fetch_array($r)) {
    if ($_prd != $w['ProdiID']) {
      $_prd = $w['ProdiID'];
      $p->SetFont('Helvetica', 'B', 9);
      $p->Cell($lbr, $t+1, $w['ProdiID'] . ' - ' . $w['_Prodi'], 0, 1);
      TampilkanHeaderTabel($p);
      $n = 0;
    }
    $n++;
    $p->SetFont('Helvetica', '', 9);
    $p->Cell(18, $t, $n, 1, 0);
    $p->Cell(30, $t, $w['MhswID'], 1, 0);
    $p->Cell(100, $t, $w['NamaMhsw'], 1, 0);
    $p->Cell(30, $t, $w['TahunID'], 1, 0);
    $p->Ln($t);
  }
}
function TampilkanHeaderTabel($p) {
  $t = 6;
  $p->SetFont('Helvetica', 'BI', 9);
  $p->Cell(18, $t, 'Nmr', 1, 0);
  $p->Cell(30, $t, 'NIM/NPM', 1, 0);
  $p->Cell(100, $t, 'Nama Mahasiswa', 1, 0);
  $p->Cell(30, $t, 'Angkatan', 1, 0);
  $p->Ln($t);
}
?>
