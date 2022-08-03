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
$ProdiID = GainVariabelx('ProdiID');

ob_start();
$pdf = new FPDF();
$pdf->SetTitle("Daftar Mahasiswa Yang Mengambil KRS");
$lbr = 190;

//BuatHeadernya($TahunID, $ProdiID, $sta, $pdf);
BuatIsinya($TahunID, $ProdiID, $pdf);

$pdf->Output();

// *** FUnctions ***
function BuatIsinya($TahunID, $ProdiID, $p) {
  global $koneksi;
  $whr_prodi = (empty($ProdiID))? '' : "and h.ProdiID = '$ProdiID' ";
  $s = "select h.*,
      m.Nama as NamaMhsw,
      d.Nama as NamaPA, d.Gelar
    from khs h
      left outer join mhsw m on m.MhswID = h.MhswID and m.KodeID = '".KodeID."'
      left outer join dosen d on d.Login = m.PenasehatAkademik and d.KodeID = '".KodeID."'
    where h.KodeID = '".KodeID."'
      and h.TahunID = '$TahunID'
      and h.SKS > 0
      $whr_prodi
    order by h.ProgramID, h.MhswID";
  $r = mysqli_query($koneksi, $s);
  
  $n = 0; $t = 5; $_prd = 'laksdjfalksdfh';
  while ($w = mysqli_fetch_array($r)) {
    if ($_prd != $w['ProdiID']) {
      $_prd = $w['ProdiID'];
      $NamaProdi = AmbilOneField('prodi', "KodeID='".KodeID."' and ProdiID", $_prd, 'Nama');
      $p->AddPage();
      BuatHeader($TahunID, $NamaProdi, $p);
    }
    $n++;
    $NamaPA = (empty($w['NamaPA']))? '(Belum diset)' : $w['NamaPA'];
    $p->SetFont('Helvetica', '', 10);
    $p->Cell(15, $t, $n, 'LB', 0); 
    $p->Cell(22, $t, $w['MhswID'], 'B', 0);
    $p->Cell(60, $t, $w['NamaMhsw'], 'B', 0);
    $p->Cell(10, $t, $w['Sesi'], 'B', 0, 'R');
    $p->Cell(10, $t, $w['SKS'], 'B', 0, 'R');
    $p->Cell(10, $t, $w['MaxSKS'], 'B', 0, 'R');
	$p->Cell(22, $t, $w['ProgramID'], 'B', 0);
    $p->Cell(40, $t, $NamaPA, 'BR', 0);
    $p->Ln($t);
  }
}
function BuatHeader($TahunID, $NamaProdi, $p) {
  global $lbr;
  $t = 6;
  $p->SetFont('Helvetica', 'B', 14);
  $p->Cell($lbr, $t, "Daftar Mahasiswa Yang Mengambil KRS - $TahunID", 0, 1, 'C');
  $p->Cell($lbr, $t, "Program Studi: $NamaProdi", 0, 1, 'C');
  $p->Ln($t+2);
  // Header tabel
  $p->SetFont('Helvetica', 'B', 10);
  $p->Cell(15, $t, 'Nmr', 1, 0);
  $p->Cell(22, $t, 'N I M', 1, 0);
  $p->Cell(60, $t, 'Nama Mahasiswa', 1, 0);
  $p->Cell(10, $t, 'Smtr', 1, 0);
  $p->Cell(10, $t, 'SKS', 1, 0);
  $p->Cell(10, $t, 'Max', 1, 0);
  $p->Cell(22, $t, 'Prog', 1, 0);
  $p->Cell(40, $t, 'Penasehat Akd.', 1, 0);
  $p->Ln($t);
}
?>
