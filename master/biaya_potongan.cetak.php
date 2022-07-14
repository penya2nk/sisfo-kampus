<?php
session_start();
include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
include_once "../header_pdf.php";

$bipotid = $_REQUEST['bipotid'];
$lbr = 190;

ob_start();
$pdf = new PDF('P', 'mm', 'A4');
$pdf->SetTitle("Daftar Biaya dan Potongan");
$pdf->AddPage('P');

BuatHeaderLap($bipotid, $pdf);
$pdf->Ln(2);
TampilkanIsinya($bipotid, $pdf);

$pdf->Output();

function TampilkanHeader($p) {
  $t = 6;
  $p->SetFont('Helvetica', 'B', 9);
  $p->Cell(12, $t, 'No.', 1, 0, 'R');
  $p->Cell(80, $t, 'Nama', 1, 0, 'L');
  $p->Cell(30, $t, 'Jumlah', 1, 0, 'R');
  $p->Cell(15, $t, 'Brapa x', 1, 0, 'R');
  $p->Cell(20, $t, 'Grade USM', 1, 0, 'C');
  $p->Ln($t);
}
function TampilkanIsinya($bipotid, $p) {
  global $koneksi;
  $s = "select b2.*, bn.Nama, format(b2.Jumlah, 0) as JML,
      t.Nama as NMTRX, s.Nama as SAAT
      from bipot2 b2
        left outer join bipotnama bn on b2.BIPOTNamaID=bn.BIPOTNamaID
        left outer join saat s on b2.SaatID=s.SaatID
        left outer join trx t on b2.TrxID=t.TrxID
      where b2.BIPOTID='$bipotid' and KodeID='".KodeID."'
      order by b2.TrxID, b2.Prioritas, b2.GradeNilai";
  $r = mysqli_query($koneksi, $s);
  $n = 0; $t = 6;

  TampilkanHeader($p);
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    $p->SetFont('Helvetica', '', 9);
    $p->Cell(12, $t, $w['Prioritas'], 'LB', 0, 'R');
    $p->Cell(80, $t, $w['Nama'], 'B', 0, 'L');
    $p->Cell(30, $t, $w['JML'], 'B', 0, 'R');
    $p->Cell(15, $t, $w['KaliSesi'], 'B', 0, 'R');
    $_GradeNilai = ($w['GunakanGradeNilai'] == 'Y') ? $w['GradeNilai'] : '-';
	$p->Cell(20, $t, $_GradeNilai, 'BR', 0, 'C');
	$p->Ln($t);
  }
}
function BuatHeaderLap($bipotid, $p) {
  global $lbr, $koneksi;
  $p->SetFont('Helvetica', 'B', 12);
  $p->Cell($lbr, 8, "Daftar Biaya dan Potongan", 0, 1, 'C');
  $p->SetFont('Helvetica', 'B', 10);
  $s = "select b.*
	  from bipot b 
      where b.BIPOTID='$bipotid' and KodeID='".KodeID."'";
  $r = mysqli_query($koneksi, $s);
  $w = mysqli_fetch_array($r);
  
  $prodi = AmbilOneField('prodi', "KodeID='".KodeID."' and ProdiID", $w['ProdiID'], 'Nama');
  $prg   = AmbilOneField('program', "KodeID='".KodeID."' and ProgramID", $w['ProgramID'], 'Nama');
  //Header
  $p->Cell(40, 6, "Thn Akd.: " . $w['Tahun'], 0, 0);
  $p->Cell(70, 6, "Prg Studi: " . $prodi, 0, 0);
  $p->Cell(70, 6, "Prg Pendidikan: " . $prg, 0, 0);
  $p->Ln(6);
}

?>
