<?php
session_start();

  include_once "../pengembang.lib.php";
  include_once "../konfigurasi.mysql.php";
  include_once "../sambungandb.php";
  include_once "../setting_awal.php";
  include_once "../check_setting.php";
  include_once "../header_pdf.php";

$ProdiID = GainVariabelx('ProdiID');

$pdf = new PDF();
$pdf->SetTitle("Rekap Penasehat Akademik");
$pdf->AddPage();

Headernya($ProdiID, $pdf);
RekapPA($ProdiID, $pdf);

$pdf->Output();

function Headernya($ProdiID, $p) {
  $NamaProdi = AmbilOneField('prodi', "ProdiID = '$ProdiID' and KodeID", KodeID, "Nama");
  $lbr = 180;
  $t = 6;
  $p->SetFont('Helvetica', 'B', 14);
  $p->Cell($lbr, $t, "Rekap Dosen Penasehat Akademik", 0, 1, 'C');
  $p->SetFont('Helvetica', 'B', 12);
  $p->Cell($lbr, $t, "Program Studi: $NamaProdi", 0, 1, 'C');
  $p->Ln(2);
}
function RekapPA($ProdiID, $p) {
global $koneksi;
  $t = 6;
  $p->SetFont('Helvetica', 'B', 10);
  $p->Cell(20, $t, 'Nmr', 1, 0);
  $p->Cell(40, $t, 'Kode Dosen', 1, 0);
  $p->Cell(100, $t, 'Nama Dosen', 1, 0);
  $p->Cell(20, $t, 'Mhsw', 1, 1, 'R');
  
  $s = "select count(MhswID) as JML,
      m.PenasehatAkademik,
      d.Nama as NamaDosen, d.Gelar
    from mhsw m
      left outer join dosen d on d.Login = m.PenasehatAkademik and d.KodeID = '".KodeID."'
    where m.KodeID = '".KodeID."'
      and m.ProdiID = '$ProdiID'
      and m.Keluar = 'N'
    group by m.PenasehatAkademik";
  $r = mysqli_query($koneksi, $s);
  $n = 0; $t = 5;
  
  $p->SetFont('Helvetica', '', 9);
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    $NamaDosen = (empty($w['NamaDosen']))? 'Belum diset' : $w['NamaDosen'] . ', ' . $w['Gelar'];
    $p->Cell(20, $t, $n, 'LB', 0);
    $p->Cell(40, $t, $w['PenasehatAkademik'], 'B', 0);
    $p->Cell(100, $t, $NamaDosen, 'B', 0);
    $p->Cell(20, $t, $w['JML'], 'BR', 0, 'R');
    $p->Ln($t);
  }
}
?>
