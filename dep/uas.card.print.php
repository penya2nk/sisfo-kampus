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
$Angkatan = GainVariabelx('Angkatan', date('Y'));
$MhswID = sqling($_REQUEST['MhswID']);
if (!empty($MhswID)) {
  $whr_mhsw = "and h.MhswID = '$MhswID' ";
}
else {
  $whr_mhsw = "and LEFT(m.TahunID, 4) = LEFT('$_SESSION[Angkatan]', 4)";
}

//leweh add ob_start(); while error at php higher version
ob_start();
//end leweh add
$pdf = new PDF();
$pdf->SetTitle("Kartu UAS Mahasiswa");
$pdf->SetFillColor(200, 200, 200);

global $koneksi;
$sudahbayar = "and (h.Biaya - h.Bayar + h.Tarik - h.Potongan) <= 0";
$s = "select h.KHSID, h.MhswID, m.Nama, h.IP, h.IPS,
      h.TahunID, m.ProgramID, m.ProdiID,
      prd.Nama as _PRD, prg.Nama as _PRG, t.Nama as _THN,
      if (d.Nama is NULL or d.Nama = '', 'Belum diset', concat(d.Nama, ', ', d.Gelar)) as _PA
    from khs h
      left outer join prodi prd on prd.ProdiID = h.ProdiID and prd.KodeID = '".KodeID."'
      left outer join program prg on prg.ProgramID = h.ProgramID and prg.KodeID = '".KodeID."'
      left outer join tahun t on t.TahunID = h.TahunID and t.ProdiID = h.ProdiID and t.KodeID = '".KodeID."'
      left outer join mhsw m on m.MhswID = h.MhswID and m.KodeID = '".KodeID."'
      left outer join dosen d on d.Login = m.PenasehatAkademik and d.KodeID = '".KodeID."'
    where h.TahunID = '$_SESSION[TahunID]'
      and h.ProdiID = '$_SESSION[ProdiID]'
      $whr_mhsw
      $sudahbayar
    order by h.MhswID";
$r = mysqli_query($koneksi, $s);
  
while ($w = mysqli_fetch_array($r)) {
  BuatHeaderKHS($w, $pdf);
  BuatIsinya($w, $pdf);
}

$pdf->Output();


// *** Functions ***
function BuatFooter($khs, $jml, $sks, $p) {
  global $arrID, $koneksi;
  $MaxSKS = AmbilOneField('maxsks',
    "KodeID='".KodeID."' and NA = 'N'
    and DariIP <= $khs[IPS] and $khs[IPS] <= SampaiIP and ProdiID", 
    $khs['ProdiID'], 'SKS')+0;
  // Pejabat
  $pjbt = AmbilFieldx('pejabat', "KodeID='".KodeID."' and KodeJabatan", 'Kaprodi-'.$khs['ProdiID'], "*");
  // Array Isi
  $tgl = date('d M Y');
  $arr = array();
  $arr[] = array('Jumlah Matakuliah yg Diambil', ':', $jml, $arrID['Kota'].', '.$tgl);
  $arr[] = array('Jumlah SKS yg Ditempuh', ':', $sks, 'A.n. KETUA');
  $arr[] = array('', '', '', $pjbt['Jabatan']);
  $arr[] = array('~IMG~');
  $arr[] = array('CATATAN:', '', '', $pjbt['Nama']);
  $arr[] = array('1. Kartu ujian ini harus dibawa setiap mengikuti ujian', '', '', 'NIDN: '.$pjbt['NIP']);
  $arr[] = array('2. Kartu ujian ini tidak boleh hilang');
  
  // Tampilkan
  $p->Ln(2);
  $t = 5;
  $p->SetFont('Helvetica', '', 9);
  foreach ($arr as $a) {
    if ($a[0] == '~IMG~') {
      $fn = "../ttd/$pjbt[KodeJabatan].ttd.gif";
      if (file_exists($fn)) {
        $p->Cell(132);
        $p->Image($fn, null, null, 20);
        $p->Ln(1);
      }
      else $p->Ln($t+2);
    }
    else {
      $p->Cell(50, $t, $a[0], 0, 0);
      $p->Cell(2, $t, $a[1], 0, 0, 'C');
      $p->Cell(30, $t, $a[2], 0, 0);
      $p->Cell(48, $t, '', 0, 0);
      $p->Cell(63, $t, $a[3], 0, 0);
      $p->Ln($t);
    }
  }
}
function BuatIsinya($khs, $p) {
  global $arrHari, $koneksi;
  BuatHeaderDetail($p);
  $s = "select k.MKKode, k.SKS, j.NamaKelas, k.KRSID,
      left(k.Nama, 40) as MKNama,
      d.Nama as DSN, d.Gelar as GLR,
      dayofweek(ja.Tanggal) as HRUAS,
      date_format(ja.Tanggal, '%d-%m-%y') as TGLUAS,
	  j.MaxAbsen
    from krs k
      left outer join jadwal j on j.JadwalID = k.JadwalID
	  left outer join jadwaluas ja on ja.JadwalID = j.JadwalID
      left outer join dosen d on d.Login = j.DosenID and d.KodeID = '".KodeID."'
    where k.KHSID = $khs[KHSID]
    order by j.UASTanggal, k.MKKode";
  $r = mysqli_query($koneksi, $s);
  $t = 8;
  $n = 0; $l = 'TB'; $_sks = 0;
  $p->SetFont('Helvetica', '', 7);
  while ($w = mysqli_fetch_array($r)) {
    $HitungMangkir = AmbilOneField('presensimhsw p left outer join jenispresensi jp on p.JenisPresensiID=jp.JenisPresensiID',
						"p.KRSID='$w[KRSID]' and jp.Nilai", 0, "count(p.PresensiID)");
	
	if($HitungMangkir <= $w['MaxAbsen'])
	{
		$n++;
		$_sks += $w['SKS'];
		$p->SetFont('Helvetica', '', 7);
		$p->Cell(20, $t, $w['MKKode'], $l, 0);
		$p->Cell(65, $t, $w['MKNama'], $l, 0);
		$p->Cell(8, $t, $w['SKS'], $l, 0, 'C');
		$p->Cell(45, $t, $w['DSN'] . ', '. $w['GLR'], $l, 0);
		$p->Cell(15, $t, $arrHari[$w['HRUAS']-1], $l, 0);
		$p->Cell(15, $t, $w['TGLUAS'], $l, 0);
		$p->Cell(15, $t, '...', $l, 0, 'C');
		$p->Cell(10, $t, '...', $l, 0, 'C');
		$p->Ln($t);
	}
	else
	{	$p->SetFont('Helvetica', '', 7);
		$p->Cell(20, $t, $w['MKKode'], $l, 0, '', true);
		$p->Cell(65, $t, $w['MKNama'], $l, 0, '', true);
		$p->Cell(8, $t, $w['SKS'], $l, 0, 'C', true);
		$p->Cell(45, $t, $w['DSN'] . ', '. $w['GLR'], $l, 0, '', true);
		$p->SetFont('Helvetica', 'B', 7);
		$p->Cell(55, $t, "TIDAK MEMENUHI PERSYARATAN", $l, 0, 'C', true);
		$p->Ln($t);
	}
  }
  BuatFooter($khs, $n, $_sks, $p);
}
function BuatHeaderDetail($p) {
  $t = 6; $l = 'BT';
  $p->SetFont('Helvetica', 'B', 7);
  $p->Cell(20, $t, 'Kode', $l, 0);
  $p->Cell(65, $t, 'Mata Kuliah', $l, 0);
  $p->Cell(8, $t, 'SKS', $l, 0);
  $p->Cell(45, $t, 'Dosen Pengasuh', $l, 0);
  $p->Cell(15, $t, 'H.UAS', $l, 0);
  $p->Cell(15, $t, 'Tgl.UAS', $l, 0);
  $p->Cell(15, $t, 'P.PWS', $l, 0);
  $p->Cell(10, $t, 'Nilai', $l, 0);
  $p->Ln($t);
}
function BuatHeaderKHS($khs, $p) {
  global $lbr;
  
  $p->AddPage();
  $p->SetFont('Helvetica', 'B', 14);
  $p->Cell($lbr, 8, "Kartu Ujian Akhir Semester", 0, 1, 'C');
  // parameter
  $prodi = $khs['_PRD'];
  $prg   = $khs['_PRG'];
  $thn   = $khs['_THN'];
  
  $data = array();
  $data[] = array('Nama', ':', $khs['Nama'], 'Tahun Akademik', ':', $thn);
  $data[] = array('NIM', ':', $khs['MhswID'], 'Program Studi', ':', $prodi);
  $data[] = array('Dosen PA', ':', $khs['_PA'], 'Prg Pendidikan', ':', $prg);
  // Tampilkan
  foreach ($data as $d) {
    $p->SetFont('Helvetica', 'I', 9);
    $p->Cell(20, 5, $d[0], 0, 0);
    $p->Cell(4, 5, $d[1], 0, 0);
    
    $p->SetFont('Helvetica', 'B', 9);
    $p->Cell(78, 5, $d[2], 0, 0);
    
    $p->SetFont('Helvetica', 'I', 9);
    $p->Cell(26, 5, $d[3], 0, 0);
    $p->Cell(4, 5, $d[4], 0, 0);
    
    $p->SetFont('Helvetica', 'B', 9);
    $p->Cell(50, 5, $d[5], 0, 1);
  }
  $p->Ln(2);
}
?>
