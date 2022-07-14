<?php
error_reporting(0);
session_start();

include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
  
$id = sqling($_REQUEST['id']);
//$id = '200820009';

$kwi = AmbilFieldx('pmbformjual', "KodeID='".KodeID."' and PMBFormJualID", $id, "*");
if (empty($kwi))
  die(PesanError('Error',
    "Data penjualan tidak ditemukan.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" />"));
$gels = AmbilFieldx('pmbperiod', "KodeID='".KodeID."' and PMBPeriodID", $kwi['PMBPeriodID'], '*');

// PDF
require("../fpdf.php");


class PDF extends FPDF {
  function Header() {
  }
}

ob_start();
$lbr = 154;

$pdf = new PDF('P', 'mm', 'A4');
$pdf->SetTitle("Kwitansi Penjualan Formulir");
$pdf->AddPage();

BuatHalaman(0, $kwi, $gels, $pdf);
//BuatHalaman(98, $kwi, $gels, $pdf);
//BuatHalaman(196, $kwi, $gels, $pdf);

$pdf->Output();

function BuatHalaman($Y, $kwi, $gels, $p)
{

if($Y!=0) $p->SetY($Y);
$p->Image("../img/kwitansi.jpg", 8, $Y+8, 190);
if($Y!=0) $p->SetY($Y+10);
$p->SetFont('Helvetica', 'B', 14);
$p->Cell(30);
$p->Cell(@$lbr, 9, "", 0, 1, 'C');

BuatIsinya($kwi, $gels, $p);
BuatFooter($kwi, $gels, $p);

}

// *** Functions ***
function BuatFooter($kwi, $gels, $p) {
  global $koneksi;
  $mrg = 130; $knn = 49; $t = 5;
  $p->SetFont('Helvetica', '', 9);
  $p->SetFillColor(224,235,255);
  
  $p->Ln(10.5);
  $p->Cell($mrg);
  //$tempstring = AmbilOneField('aplikan a left outer join presenter p on a.PresenterID=p.PresenterID',  "a.AplikanID='$kwi[AplikanID]' and a.KodeID", KodeID, 'p.Nama');
  //$s = "select p.Nama from aplikan a left outer join presenter p on a.PresenterID=p.PresenterID 
  //			where a.AplikanID='$kwi[AplikanID]' and a.KodeID='".KodeID."'";
  $s = "select Nama from karyawan where KodeID='".KodeID."' and Login='$_SESSION[_Login]' and NA='N'";
  $r = mysqli_query($koneksi, $s);
  $w = mysqli_fetch_array($r);
  $p->Cell($knn, $t, $w['Nama'], 0, 1, 'C');
}

function BuatIsinya($kwi, $gels, $p) {
  include_once "../punksi/terbilang.php";
  $terbilang = ucwords(SpellNumberID($kwi['Jumlah'])) . ' Rupiah';
  
  $mrg = 30; $t = 5; $k = 71; $k2=43;

  $p->SetFont('Helvetica', '', 10);
  
  // Baris 1 - No. Kwitansi
  $p->Ln(2);
  $p->Cell($mrg);
  $p->Cell(30, $t, '', 0, 0);
  $p->Cell(103, $t, $kwi['PMBFormJualID'], 0, 1, 'R');
  $p->Ln(6);
  
  // Baris 2 - Nama
  $p->Cell($mrg);
  $p->Cell($k2, $t, '', 0, 0);
  $p->Cell($k, $t, $kwi['Nama'], 0, 1);

  // Baris 3 -  Terbilang
  $p->Cell($mrg);
  $p->Cell($k2, $t, '', 0, 0);
  $p->Cell($k, $t, $terbilang, 0, 1);
  
  // Baris 4 - Untuk pembayaran
  $p->Cell($mrg);
  $p->Cell($k2, $t, '', 0, 0);
  $p->Cell($k, $t, 'Pembelian formulir PMB  ' . $gels['Nama'], 0, 1);
  
  // Baris 5 - Program Studi
	$nilai = '';
  $arr = explode(',', $kwi['ProdiID']);
  foreach($arr as $isi){
  $NamaProdi = AmbilOneField('prodi', 'ProdiID', $isi, 'Nama');
  $nilai.= $NamaProdi.',';
  }
  $nilai=substr($nilai,0,-1);

  $p->Cell($mrg);
  $p->Cell($k2, $t, '', 0, 0);
  $p->Cell($k, $t, $nilai, 0, 1);
  
  // Baris 6 - Gelombang & Nomor Aplikan
  $p->Cell($mrg);
  $p->Cell($k2+2, $t, '', 0, 0);
  $p->Cell($k, $t, $gels['PMBPeriodID'], 0, 0);
  $p->Cell(0, $t, $kwi['AplikanID'], 0, 1);
  
  // Baris 7 - Rupiahnya & Tanggal Cetak Kwitansi
  $current_date = date('Y-m-d');
  $p->Cell($mrg);
  $p->Cell($k2+8, $t, '', 0, 0);
  $p->Cell($k-15, $t, $kwi['Jumlah'], 0, 0);
  $p->Cell(0, $t, AmbilOneField('identitas', 'Kode', KodeID, 'Kota').', '.$current_date, 0, 1);
  
}
?>
