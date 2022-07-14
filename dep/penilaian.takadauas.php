<?php
error_reporting(0);
session_start();

include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
include_once "../fpdf.php";

$TahunID = GainVariabelx('TahunID');
$ProdiID = GainVariabelx('ProdiID');


ob_start();
$pdf = new FPDF();
$pdf->SetTitle("Daftar Dosen Yang Belum Entry Nilai UAS");
$pdf->SetAutoPageBreak(true, 5);
$pdf->SetFillColor(200, 200, 200);


$pdf->AddPage();
$tahunstring = (empty($TahunID))? '' : "Tahun $TahunID";
HeaderLogo("Dosen yang Belum Entry UAS $tahunstring", $pdf, 'P');
BuatHeaderTable($TahunID, $ProdiID, $pdf);
$lbr = 190;

BuatIsinya($TahunID, $ProdiID, $pdf);

$pdf->Output();

// *** Functions ***
function BuatIsinya($TahunID, $ProdiID, $p) {
  global $koneksi;
  $whr_prodi = (empty($ProdiID))? '' : "and j.ProdiID = '$ProdiID' ";
  $whr_tahun = (empty($TahunID))? '' : "and j.TahunID = '$TahunID'";
  $s = "select j.JadwalID, j.MKKode, j.Nama, j.JumlahMhsw, d.Login,
        d.Nama as DSN
    from jadwal j 
      left outer join dosen d on j.DosenID=d.Login 
    where j.KodeID='".KodeID."'
	  $whr_tahun
    $whr_prodi
    order by d.Nama";
  $r = mysqli_query($koneksi, $s); $n = 0;
  $t = 5; $ttl = 0; $_dosenid = 'alskdjfalsdhguairgsofjhjg9e8rgjpsofjg';
  
  while ($w = mysqli_fetch_array($r)) {
  if($_dosenid != $w['Login'])
	{	$_dosenid = $w['Login'];}
	
	$BelumIsi = AmbilOneField('krs', "JadwalID='$w[JadwalID]' and UAS = 0 and KodeID", KodeID,  "count(KRSID)"); 
	$n++;
    $ttlbelumisi += $BelumIsi;
	$ttljmlhmhsw += $w['JumlahMhsw'];
    $p->SetFont('Helvetica', '', 8);
    $p->Cell(10, $t, $n, 'LB', 0);
    $p->Cell(35, $t, $w['DSN'], 'B', 0);
    $p->Cell(25, $t, $w['MKKode'], 'B', 0);
    $p->Cell(60, $t, $w['Nama'], 'B', 0);
	$p->Cell(20, $t, $BelumIsi, 'B', 0, 'R');
	$p->Cell(20, $t, $w['JumlahMhsw'], 'B', 0, 'R');
	$Persentase = ($w['JumlahMhsw'] <= 0)? 0 : ($BelumIsi/$w['JumlahMhsw']*100);
	$p->Cell(20, $t, number_format($Persentase, 2).' %', 1, 0, 'R', ($Persentase > 0)? true : false) ;
    $p->Ln($t); 
  }
  $_ttl = number_format($ttl+0);
  $p->SetFont('Helvetica', 'B', 10);
  $p->Cell($lbr, 1,  ' ', 1, 1);
  $p->Cell(130, $t, 'TOTAL :', 0, 0, 'R');
  $p->Cell(20, $t, ($n <= 0)? 0.00 : number_format($ttlbelumisi/$n, 2).' %', 0, 0, 'R');
  $p->Cell(20, $t, ($n <= 0)? 0.00 : number_format($ttljmlhmhsw/$n, 2).' %', 0, 0, 'R');
  $p->Cell(20, $t, ($ttljmlhmhsw <= 0)? 0.00 : number_format($ttlbelumisi/$ttljmlhmhsw*100, 2).' %', 0, 0, 'R'); 
  $p->Ln($t+2);
}
function BuatHeadertable($TahunID, $ProdiID, $p) {
  global $lbr;
  $t = 5;
  $p->SetFont('Helvetica', 'BI', 10);
  $p->Cell(10, $t, 'Nmr', 1, 0);
  $p->Cell(35, $t, 'Dosen', 1, 0);
  $p->Cell(25, $t, 'Kode MK', 1, 0);
  $p->Cell(60, $t, 'Nama', 1, 0);
  $p->Cell(20, $t, 'Belum Isi', 1, 0, 'R');
  $p->Cell(20, $t, 'Jml. Mhsw', 1, 0, 'R');
  $p->Cell(20, $t, 'Persen', 1, 0, 'R');
  $p->Ln($t);
}

function HeaderLogo($jdl, $p, $orientation='P')
{	$pjg = 110;
	$logo = (file_exists("../img/logo.jpg"))? "../img/logo.jpg" : "img/logo.jpg";
    $identitas = AmbilFieldx('identitas', 'Kode', KodeID, 'Nama, Alamat1, Telepon, Fax');
	$p->Image($logo, 12, 8, 18);
	$p->SetY(5);
    $p->SetFont("Helvetica", '', 8);
    $p->Cell($pjg, 5, $identitas['Yayasan'], 0, 1, 'C');
    $p->SetFont("Helvetica", 'B', 10);
    $p->Cell($pjg, 7, $identitas['Nama'], 0, 0, 'C');
    
	//Judul
	if($orientation == 'L')
	{
		$p->SetFont("Helvetica", 'B', 16);
		$p->Cell(20, 7, '', 0, 0);
		$p->Cell($pjg, 7, $jdl, 0, 1, 'C');
	}
	else
	{	$p->SetFont("Helvetica", 'B', 12);
		$p->Cell(80, 7, $jdl, 0, 1, 'R');
	}
	
    $p->SetFont("Helvetica", 'I', 6);
	$p->Cell($pjg, 3,
      $identitas['Alamat1'], 0, 1, 'C');
    $p->Cell($pjg, 3,
      "Telp. ".$identitas['Telepon'].", Fax. ".$identitas['Fax'], 0, 1, 'C');
    $p->Ln(3);
	if($orientation == 'L') $length = 275;
	else $length = 190;
    $p->Cell($length, 0, '', 1, 1);
    $p->Ln(2);
}

?>
