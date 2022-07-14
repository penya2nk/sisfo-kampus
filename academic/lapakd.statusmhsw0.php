<?php

error_reporting(0);
session_start();

include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
  include_once "../fpdf.php";
  include_once "../util.lib.php";


$TahunID = GainVariabelx('TahunID');
$ProdiID = GainVariabelx('ProdiID');

ob_start();
$pdf = new FPDF();
$pdf->SetTitle("Rekapitulasi Jumlah Mahasiswa Per Angkatan");
$pdf->AddPage();
	$lbr = 190;
	
	BuatIsinya($pdf);
$pdf->Output();

// *** Functions ***
function BuatHeadernya($klmn, $stt, $p) {
  global $lbr;
  HeaderLogo("Rekapitulasi Jumlah Mahasiswa Per Angkatan", $p, 'P');
  $p->Ln($t);
  
  $t = 5;
  $p->SetFont('Helvetica', 'BI', 9);
  // Baris 1
  $p->Cell(15, $t, 'Tahun', 'LTR', 0, 'C');
  $p->Cell(13, $t, 'Total', 'LTR', 0, 'R');
  $p->Cell(13*sizeof($klmn), $t, 'Gender', 1, 0, 'C');
  $p->Cell(13*sizeof($stt), $t, 'Status Mhsw', 1, 0, 'C');
  $p->Ln($t);
  
  // Baris 2
  $p->Cell(15, $t, 'Angktn', 'LBR', 0, 'C');
  $p->Cell(13, $t, 'Mhsw', 'LBR', 0, 'R');
  // Kelamin
  foreach ($klmn as $k) {
    $p->Cell(13, $t, $k, 1, 0, 'R');
  }
  // Status
  foreach ($stt as $_stt) {
    $p->Cell(13, $t, $_stt, 1, 0, 'R');
  }
  
  $p->Ln($t);
}
function BuatIsinya($p) {
	global $koneksi;
  $t = 6;
  $arrAngkatan = GetArrayAngkatan($arrJml);
  $arrKelamin = GetArrayKelamin($arrJmlKelamin);
  $arrStatus = GetArrayStatus($arrJmlStatus);
  BuatHeadernya($arrKelamin, $arrStatus, $p);
  
  $total = 0;
  $det = array();
  for ($i = 0; $i < sizeof($arrAngkatan); $i++) {
    $angk = $arrAngkatan[$i];
    // Jumlah
    $p->SetFont('Helvetica', 'B', 10);
    $p->Cell(15, $t, $arrAngkatan[$i], 'B', 0);
    
    $p->SetFont('Helvetica', '', 10);
    $jml = number_format($arrJml[$arrAngkatan[$i]]);
    $p->Cell(13, $t, $jml, 'B', 0, 'R');
    $total += $arrJml[$arrAngkatan[$i]];
    
    
    // Kelamin
    foreach ($arrKelamin as $klmn) {
      $jmlkel = $arrJmlKelamin[$arrAngkatan[$i]][$klmn];
      $_jmlkel = ($jmlkel == 0)? '-' : number_format($jmlkel);
      $p->Cell(13, $t, $_jmlkel, 'B', 0, 'R');
      $det['kelamin_'.$klmn] += $jmlkel;
    }
    // Status
    foreach ($arrStatus as $stt) {
      $jmlstt = $arrJmlStatus[$arrAngkatan[$i]][$stt];
      $_jmlstt = ($jmlstt == 0)? '-' : number_format($jmlstt);
      $p->Cell(13, $t, $_jmlstt, 'B', 0, 'R');
      $det['status_'.$stt] += $jmlstt;
    }
    
    $p->Ln($t);
  }
  
  $p->SetFont('Helvetica', 'B', 10);
  // Menampilkan total
  $p->Cell(15, $t, "TOTAL :", 0, 0, 'R');
  $p->Cell(13, $t, number_format($total), 0, 0, 'R');
  // Total Kelamin
  foreach ($arrKelamin as $klmn) {
    $jml = $det['kelamin_'.$klmn];
    $_jml = number_format($jml);
    $p->Cell(13, $t, $_jml, 0, 0, 'R');
  }
  // Status
  foreach ($arrStatus as $stt) {
    $jml = $det['status_'.$stt];
    $_jml = number_format($jml);
    $p->Cell(13, $t, $_jml, 0, 0, 'R');
  }

  $p->Ln($t*2);
  $p->SetFont('Helvetica', '', 8);
  $p->Cell(13, $t, 'Keterangan :', 0, 0);
  $p->Ln($t/2);

  global $koneksi;
  $s = "select DISTINCT(m.Kelamin)
    from mhsw m
    group by m.Kelamin";
  $r = mysqli_query($koneksi, $s);
  while($w = mysqli_fetch_array($r)){
  $Ket = AmbilOneField('kelamin', "NA='N' and Kelamin", $w[Kelamin], 'Nama');
  $p->Cell(8, $t, $w[Kelamin], 0, 0);
  $p->Cell(15, $t, ': '.$Ket, 0, 0);
  $p->Ln($t/2);
  }

  global $koneksi;
  $s = "select DISTINCT(m.StatusMhswID)
    from mhsw m
    group by m.StatusMhswID, m.TahunID";
  $r = mysqli_query($koneksi, $s);
  while($w = mysqli_fetch_array($r)){
  $Ket = AmbilOneField('statusmhsw', "NA='N' and StatusMhswID", $w[StatusMhswID], 'Nama');
  $p->Cell(8, $t, $w[StatusMhswID], 0, 0);
  $p->Cell(15, $t, ': '.$Ket, 0, 0);
  $p->Ln($t/2);
  }
}
function GetArrayAngkatan(&$arrJml) {
  global $koneksi;
  $s = "select m.TahunID, count(m.MhswID) as JML
    from mhsw m
    group by m.TahunID
    order by m.TahunID desc";
  $r = mysqli_query($koneksi, $s);
  $arr = array();
  while ($w = mysqli_fetch_array($r)) {
    $arr[] = $w['TahunID'];
    $arrJml[$w['TahunID']] = $w['JML'];
  }
  return $arr;
}
function GetArrayKelamin(&$arrJmlKelamin) {
  global $koneksi;
  $s = "select m.TahunID, count(m.MhswID) as JML, m.Kelamin
    from mhsw m
    group by m.Kelamin, m.TahunID";
  $r = mysqli_query($koneksi, $s);
  $arr = array();
  while ($w = mysqli_fetch_array($r)) {
    if (array_search($w['Kelamin'], $arr) === false) $arr[] = $w['Kelamin'];
    $arrJmlKelamin[$w['TahunID']][$w['Kelamin']] = $w['JML'];
    
  }
  return $arr;
}
function GetArrayStatus(&$arrJmlStatus) {
  global $koneksi;
  $s = "select m.TahunID, count(m.MhswID) as JML, m.StatusMhswID
    from mhsw m
    group by m.StatusMhswID, m.TahunID";
  $r = mysqli_query($koneksi, $s);
  $arr = array();
  while ($w = mysqli_fetch_array($r)) {
    if (array_search($w['StatusMhswID'], $arr) === false) $arr[] = $w['StatusMhswID'];
    $arrJmlStatus[$w['TahunID']][$w['StatusMhswID']] = $w['JML'];
  }
  // *** Jika ada yg tidak beres dgn status mhsw
  if (sizeof($arr) <= 1) {
    $arr = array();
    $s = "select StatusMhswID
      from statusmhsw
      where NA = 'N'
      order by StatusMhswID";
    $r = mysqli_query($koneksi, $s);
    while ($w = mysqli_fetch_array($r)) {
      $arr[] = $w['StatusMhswID'];
    }
  }
  return $arr;
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
