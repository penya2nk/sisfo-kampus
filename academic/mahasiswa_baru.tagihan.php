<?php
error_reporting(0);
session_start();
include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
include_once "../fpdf.php";

global $koneksi;
$lbr = 190;
$mrg = 10;
$PMBID = sqling($_REQUEST['pmbid']);

ob_start();
$pdf = new FPDF();
$pdf->SetTitle("Tagihan Administrasi");
$pdf->SetAutoPageBreak(true, 5);

if(empty($PMBID))
{	
global $koneksi;
$s = "select PMBID from pmb where KodeID='".KodeID."' order by PMBID";
	$r = mysqli_query($koneksi, $s);
	while($w = mysqli_fetch_array($r))
	{	
		$pdf->AddPage();
		HeaderLogo('TAGIHAN ADMINISTRASI', $pdf, 'P');
		BuatHeader($w['PMBID'], $pdf);
		TampilkanDetailBiaya($w['PMBID'], $pdf);
		BuatFooter($w['PMBID'], $pdf);
	}
}
else
{
	$pdf->AddPage();
	HeaderLogo('TAGIHAN ADMINISTRASI', $pdf, 'P');
	BuatHeader($PMBID, $pdf);
	TampilkanDetailBiaya($PMBID, $pdf);
	BuatFooter($PMBID, $pdf);
}

$pdf->Output();

function BuatFooter($PMBID, $p) {
  $t = 5;
  $p->Ln(2*$t);
  
  $identitas = AmbilFieldx('identitas', 'Kode', KodeID, '*');
  $arr = array();
  $arr[] = array('', $identitas['Kota'].', '.date('d M Y'));
  $arr[] = array('Mengetahui,', 'Mahasiswa,');
  $arr[] = array('', '');
  $arr[] = array('', '');
  $arr[] = array('', '');
  $arr[] = array('', '');
  $arr[] = array('', '');
  $arr[] = array(AmbilOneField('pejabat', "KodeJabatan='PUKET4' and KodeID", KodeID, 'Nama'), AmbilOneField('pmb', "PMBID='$PMBID' and KodeID", KodeID, 'Nama'));
  $arr[] = array('Biro Akademik', '');
  
  // Tampilkan
  $p->SetFont('Helvetica', '', 9);
  foreach ($arr as $a) {
    $p->Cell(10, $t, '', 0, 0);
	$p->Cell(50, $t, $a[0], 0, 0, 'C');
	$p->Cell(60, $t, '', 0, 0);
    $p->Cell(50, $t, $a[1], 0, 0, 'C');
	
	$p->Ln($t);
  }
  $p->Ln(2*$t);
  $t = 4;
  $p->SetFont('Helvetica', '', 7);
  $p->Cell(10, $t, '', 0, 0);
  $p->Cell(100, $t, 'NB:', 0, 1);
  $p->Cell(10, $t, '', 0, 0);
  $p->Cell(100, $t, 'Pembayaran ditransfer ke rekening', 0, 1);
  $rekening = AmbilFieldx('rekening', "Def='Y' and KodeID", KodeID, '*');
  $p->Cell(10, $t, '', 0, 0);
  $p->Cell(100, $t, 'An: '.$rekening['Nama'], 0, 1);
  $p->Cell(10, $t, '', 0, 0);
  $p->Cell(100, $t, 'No. Rekening: '.$rekening['RekeningID'], 0, 1);
  $p->Cell(10, $t, '', 0, 0);
  $p->Cell(100, $t, $rekening['Bank'], 0, 1);
  $p->Cell(10, $t, '', 0, 0);
  $p->Cell(100, $t, $rekening['Cabang'], 0, 1);
}

function TampilkanDetailBiaya($PMBID, $p) {
  global $arrID, $koneksi;
  $pmb = AmbilFieldx("pmb
    left outer join prodi prd on pmb.ProdiID = prd.ProdiID
    left outer join program prg on pmb.ProgramID = prg.ProgramID
    left outer join statusawal sta on pmb.StatusAwalID = sta.StatusAwalID 
    left outer join asalsekolah a on pmb.AsalSekolah = a.SekolahID
	left outer join perguruantinggi pt on pmb.AsalSekolah = pt.PerguruanTinggiID",
	"pmb.KodeID='".KodeID."' and pmb.PMBID", 
    $PMBID,
    "pmb.*, prd.Nama as PROD, prg.Nama as PROG,
	if(a.Nama like '_%', a.Nama, 
		if(pt.Nama like '_%', pt.Nama, pmb.AsalSekolah)) as _NamaSekolah,
    sta.Nama as STAWAL"
    );
  $Sisa = $pmb['TotalBiaya'] - $pmb['TotalBayar'];
  $_Sisa = number_format($Sisa);
 
  $s = "select bm.*, s.Nama as _saat,
    format(bm.Jumlah, 0) as JML,
    format(bm.TrxID*bm.Besar, 0) as BSR,
    format(bm.Dibayar, 0) as BYR,
	b2.Prioritas
    from bipotmhsw bm
	  left outer join bipot2 b2 on b2.BIPOT2ID = bm.BIPOT2ID
      left outer join saat s on b2.SaatID = s.SaatID
    where bm.PMBMhswID = 0
      and bm.KodeID = '".KodeID."'
      and bm.PMBID = '$PMBID'
    order by b2.Prioritas, bm.TrxID DESC, bm.BIPOTMhswID";
  $r = mysqli_query($koneksi, $s);
  $t = 5; $n = 0; $ttl = 0;
  
  // Datanya
  $p->SetFont('Helvetica', '', 9);
  $tempPrioritas = "24nloqiinnrg";
  while ($w = mysqli_fetch_array($r)) {
    $n++;
	$sub = $w['Jumlah'] * $w['Besar'];
    $_sub = number_format($sub, 0, ',', '.');
    $ttl += $sub * $w['TrxID'];
    $ctt = TRIM($w['Catatan']);
	
	if($tempPrioritas != $w['Prioritas'])
	{	$p->Ln(3);
		$tempPrioritas = $w['Prioritas'];
	}
	
	$TambahanNama = (empty($w['TambahanNama']))? "" : ' ('.$w['TambahanNama'].')';
    $p->Cell(10, $t, '', 0, 0);
	$p->Cell(100, $t, $w['Nama'].$TambahanNama, 0, 0);
	if($w['TrxID'] < 0)
	{	$p->Cell(15, $t, number_format($w['Jumlah'], 0, ',', '.').' x', 0, 0, 'R');
		$p->Cell(28, $t, '(Rp. '.number_format($w['Besar'], 0, ',', '.').') =', 0, 0, 'R');
		$p->Cell(28, $t, '(Rp. '.$_sub.')', 0, 0, 'R');
	}
	else
	{
		$p->Cell(15, $t, number_format($w['Jumlah'], 0, ',', '.').' x', 0, 0, 'R');
		$p->Cell(28, $t, 'Rp. '.number_format($w['Besar'], 0, ',', '.').' =', 0, 0, 'R');
		$p->Cell(28, $t, 'Rp. '.$_sub, 0, 0, 'R');
    }
	$p->Ln($t);
  }
  
  $_ttl = number_format($ttl, 0, ',', '.');
  $p->Ln(3);
  $t = 7;
  $p->SetFont('Helvetica', 'B', 12);
  $p->Cell(10, $t, '', 0, 0);
  $p->Cell(100, $t, 'Total yang harus dibayarkan:', 'LBT', 0, 'L');
  $p->Cell(70, $t, 'Rp. '.$_Sisa, 'BTR', 0, 'R');
  $p->Ln($t);
}

function BuatHeader($PMBID, $p) {
  $pmb = AmbilFieldx('pmb', "KodeID='".KodeID."' and PMBID", $PMBID, 'PMBID, Nama, ProdiID');
  
  $pmbperiod = AmbilFieldx('pmbperiod', "KodeID='".KodeID."' and NA", 'N', "PMBPeriodID, Nama");
  $t = 5; $lbr = 200;

  $arr = array();
  $arr[] = array('Program Studi', ':', AmbilOneField('prodi', "ProdiID='$pmb[ProdiID]' and KodeID", KodeID, 'Nama'));
  $arr[] = array('Jenjang', ':', AmbilOneField('prodi p left outer join jenjang j on p.JenjangID=j.JenjangID', 
									"p.ProdiID='$pmb[ProdiID]' and p.KodeID", KodeID, "concat(j.Nama, ' - ', j.Keterangan)"));
  $arr[] = array('Gelombang', ':', $pmbperiod['PMBPeriodID'].' - '.$pmbperiod['Nama']);
  $arr[] = array('No. PMB', ':', $pmb['PMBID']);
  $arr[] = array('Nama', ':', $pmb['Nama']);
  // Tampilkan
  $p->SetFont('Helvetica', '', 9);
  foreach ($arr as $a) {
    $p->SetFont('Helvetica', 'I', 9);
    $p->Cell(30, $t, $a[0], 0, 0);
    $p->Cell(4, $t, $a[1], 0, 0, 'C');
    $p->SetFont('Helvetica', 'B', 9);
    $p->Cell(85, $t, $a[2], 0, 0);
	
	if(!empty($a[3]))
	{
		$p->SetFont('Helvetica', 'B', 9);
		$p->Cell(50, $t, $a[3], 'BLT', 0);
		$p->Cell(4, $t, $a[4], 'BT', 0, 'C');
		$p->SetFont('Helvetica', 'B', 9);
		$p->Cell(8, $t, $a[5], 'BRT', 0);
	}
	$p->Ln($t);
  }
  $p->Ln(4);
  $p->Cell(0, 0, '', 'T', 0);
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
		$p->Cell(80, 7, $jdl, 0, 1, 'C');
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
