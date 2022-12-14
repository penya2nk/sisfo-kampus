<?php
error_reporting(0);
session_start();

  include_once "../pengembang.lib.php";
  include_once "../konfigurasi.mysql.php";
  include_once "../sambungandb.php";
  include_once "../setting_awal.php";
  include_once "../check_setting.php";
  include_once "../fpdf.php";
  // define('FPDF_FONTPATH','fpdf/font/');

$TahunID = GainVariabelx('_jdwlTahun');
$ProdiID = GainVariabelx('_jdwlProdi');
$ProgramID = GainVariabelx('_jdwlProg');
$_jdwlHari  = GainVariabelx('_jdwlHari');
$_jdwlKelas = GainVariabelx('_jdwlKelas');
$_jdwlSemester = GainVariabelx('_jdwlSemester');

$thn = AmbilFieldx('tahun', "TahunID='$TahunID' and KodeID='".KodeID."' and ProdiID='$ProdiID' and ProgramID", $ProgramID, "*");

$lbr = 280;

//leweh add ob_start(); while error at php higher version
ob_start();
$pdf = new FPDF();
//end leweh add

//$pdf = new FPDF('L');
$pdf->SetTitle("Jadwal Kuliah - $TahunID");
$pdf->SetAutoPageBreak(true, 5);
$pdf->AddPage('L');
// $pdf->SetFont('Arial', 'B', 14);

HeaderLogo("Jadwal Kuliah", $pdf, 'L');
// Buat header dulu
BuatHeader($thn, $pdf);
// Tampilkan datanya
AmbilJadwal($thn, $pdf);
// Buat footer
BuatFooter($thn, $pdf);

$pdf->Output();

function BuatFooter($thn, $p) {
  global $arrID;
  $mrg = 220;
  $t = 6;
  // Tanda tangan
  $pjbt = AmbilFieldx('pejabat', "KodeID='".KodeID."' and KodeJabatan", 'PUKET1', "*");
  $p->Ln(4);
  $p->Cell($mrg);
  // $p->Cell(60, $t, $arrID['Kota'] . ", " . date('d M Y'), 0, 1);
  $p->Cell($mrg);
  // $p->Cell(60, $t, $pjbt['Jabatan'], 0 , 1);
  $p->Ln(15);

  $p->Cell($mrg);
  $p->SetFont('Arial', 'B', 9);
  // $p->Cell(60, $t, $pjbt['Nama'], 0, 1);
  $p->Cell($mrg);
  $p->SetFont('Arial', '', 9);
  //$p->Cell(60, $t, 'NIP: ' . $pjbt['NIP'], 0, 1);
}
function AmbilJadwal($thn, $p) {
  global $koneksi;
  // Buat headernya dulu
  $p->SetFont('Arial', 'B', 9);
  $t = 6;
  
  $p->Cell(8, $t, 'No', 1, 0);
  $p->Cell(14, $t, 'Hari', 1, 0);
  $p->Cell(22, $t, 'Jam', 1, 0);

  $p->Cell(20, $t, 'Kode MK', 1, 0);
  $p->Cell(90, $t, 'Matakuliah', 1, 0);
  $p->Cell(9, $t, 'SKS', 1, 0);
  $p->Cell(70, $t, 'Dosen Pengasuh', 1, 0);
  $p->Cell(14, $t, 'Kelas', 1, 0, 'C');
  $p->Cell(18, $t, 'Ruangan', 1, 0, 'C');
  $p->Cell(10, $t, 'Mhs', 1, 0, 'R');
  $p->Ln($t);

  $whr_hari = ($_SESSION['_jdwlHari'] == '')? '' : "and j.HariID = '$_SESSION[_jdwlHari]' ";
  $whr_kelas = ($_SESSION['_jdwlKelas'] == '')? '' : "and j.NamaKelas = '$_SESSION[_jdwlKelas]' ";
  $whr_smt  = ($_SESSION['_jdwlSemester'] == '')? '' : "and mk.Sesi = '$_SESSION[_jdwlSemester]' ";
  // Ambil Isinya
  $s = "select j.*,
      j.Nama as MK,
      h.Nama as HR, 
      LEFT(j.JamMulai, 5) as JM, LEFT(j.JamSelesai, 5) as JS,
      if (d.Nama is NULL or d.Nama = '', 'Belum diset', concat(d.Nama, ', ', d.Gelar)) as DSN,
      date_format(j.UASTanggal, '%d-%m-%Y') as _UASTanggal,
      date_format(j.UASTanggal, '%w') as _UASHari,
      huas.Nama as HRUAS,
	  if (j.JadwalRefID != 0,'(LAB)','') as _lab,
      LEFT(j.UASJamMulai, 5) as _UASJamMulai, LEFT(j.UASJamSelesai, 5) as _UASJamSelesai,
	  k.Nama AS namaKelas
    from jadwal j
      left outer join hari h on h.HariID = j.HariID
      left outer join dosen d on d.Login = j.DosenID and d.KodeID = '".KodeID."'
      left outer join hari huas on huas.HariID = date_format(j.UASTanggal, '%w')
      left outer join mk on mk.MKID = j.MKID 
	  LEFT OUTER JOIN kelas k ON k.KelasID = j.NamaKelas
    where j.KodeID = '".KodeID."'
      and j.TahunID = '$_SESSION[_jdwlTahun]'
      and j.ProdiID = '$_SESSION[_jdwlProdi]'
      and j.ProgramID = '$_SESSION[_jdwlProg]'
      $whr_hari $whr_kelas $whr_smt
    order by j.ProgramID, j.HariID, j.JamMulai";
  $r = mysqli_query($koneksi, $s);
  //die("<pre>$s</pre>");
  $n = 0; $_h = 'akjsdfh'; $_p = 'la;skdjfadshg';
  $t = 6;

  while ($w = mysqli_fetch_array($r)) {
    $n++;
    /*
    if ($_p != $w['ProgramID']) {
      $_p = $w['ProgramID'];
      $_prg = AmbilOneField('program', "KodeID='".KodeID."' and ProgramID", $_p, 'Nama');
      $p->SetFont('Arial', 'B', 10);
      $p->Cell(190, $t, $_prg, 1, 1, 'C');
    }
    */
    //if ($_h != $w['HR']) {
    //  $_h = $w['HR'];
      $hr = $w['HR'];
    //} else $hr = '-';
    $p->SetFont('Arial', '', 8);
    $p->Cell(8, $t, $n, 1, 0, 'R');
    $p->Cell(14, $t, $hr, 1);
    $p->Cell(22, $t, $w['JM'] . ' - ' . $w['JS'], 1);
    $p->Cell(20, $t, $w['MKKode'], 1);
    $p->Cell(90, $t, $w['MK'].' '.$w['_lab'], 1);
    $p->Cell(9, $t, $w['SKS'], 1, 0, 'C');
    $p->Cell(70, $t, $w['DSN'], 1);
    $p->Cell(14, $t, $w['namaKelas'], 1, 0, 'C');
    $p->Cell(18, $t, $w['RuangID'], 1, 0, 'C');
    $p->Cell(10, $t, $w['JumlahMhsw'], 1, 0, 'R');
    $p->Ln($t);
  }
}
function BuatHeader($thn, $p) {
  $p->SetFont('Arial', 'B', 10);
  
  $prodi = AmbilOneField('prodi', "KodeID='".KodeID."' and ProdiID", $thn['ProdiID'], 'Nama');
  $prg   = AmbilOneField('program', "KodeID='".KodeID."' and ProgramID", $thn['ProgramID'], 'Nama');
  //Header
  $p->Cell(90, 6, "Thn Akd.: " . $thn['Nama'], 0, 0);
  $p->Cell(90, 6, "Prg Studi: " . $prodi, 0, 0);
  $p->Cell(90, 6, "Prg Pendidikan: " . $prg, 0, 0);
  $p->Ln(6);
  // Filter
  $hari = ($_SESSION['_jdwlHari'] == '')? '(Semua)' : AmbilOneField('hari', 'HariID', $_SESSION['_jdwlHari'], 'Nama');
  $kelas = ($_SESSION['_jdwlKelas'] == '')? '(Semua)' : $_SESSION['_jdwlKelas'];
  $smt = ($_SESSION['_jdwlSemester'] == '')? '(Semua)' : $_SESSION['_jdwlSemester'];
  $p->Cell(90, 6, "Hari: ". $hari, 0, 0);
  $p->Cell(90, 6, "Kelas: $kelas", 0, 0);
  $p->Cell(90, 6, "Semester: $smt", 0, 0);
  $p->Ln(6);
  
  $p->Ln(2);
}
function HeaderLogo($jdl, $p, $orientation='P')
{	$pjg = 110;
	$logo = (file_exists("../img/logo.jpg"))? "../img/logo.jpg" : "img/logo.jpg";
    $identitas = AmbilFieldx('identitas', 'Kode', KodeID, 'Nama, Alamat1, Telepon, Fax');
	$p->Image($logo, 12, 8, 18);
	$p->SetY(5);
    $p->SetFont("Arial", '', 8);
    $p->Cell($pjg, 5, $identitas['Yayasan'], 0, 1, 'C');
    $p->SetFont("Arial", 'B', 10);
    $p->Cell($pjg, 7, $identitas['Nama'], 0, 0, 'C');
    
	//Judul
	if($orientation == 'L')
	{
		$p->SetFont("Arial", 'B', 16);
		$p->Cell(20, 7, '', 0, 0);
		$p->Cell($pjg, 7, $jdl, 0, 1, 'C');
	}
	else
	{	$p->SetFont("Arial", 'B', 12);
		$p->Cell(80, 7, $jdl, 0, 1, 'R');
	}
	
    $p->SetFont("Arial", 'I', 6);
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
