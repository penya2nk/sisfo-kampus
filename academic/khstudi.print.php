<?php
error_reporting(0);
session_start();

include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
include_once "../header_pdf.php";

global $koneksi;

$lbr = 190;
$mrg = 10;

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

ob_start();
$pdf = new PDF();
$pdf->SetTitle("Kartu Hasil Studi");

$s = "select h.KHSID, h.MhswID, m.Nama, h.IP, h.IPS, h.Sesi,
      h.TahunID, m.ProgramID, m.ProdiID,
      prd.NamaSesi,
      prd.Nama as _PRD, prg.Nama as _PRG, t.Nama as _THN,
      if (d.Nama is NULL or d.Nama = '', 'Belum diset', concat(d.Nama, ', ', d.Gelar)) as _PA,
      (h.Biaya - h.Bayar + h.Tarik - h.Potongan) as _Sisa
    from khs h
      left outer join prodi prd on prd.ProdiID = h.ProdiID and prd.KodeID = '".KodeID."'
      left outer join program prg on prg.ProgramID = h.ProgramID and prg.KodeID = '".KodeID."'
      left outer join tahun t on t.TahunID = h.TahunID and t.ProdiID = h.ProdiID and t.KodeID = '".KodeID."'
      left outer join mhsw m on m.MhswID = h.MhswID and m.KodeID = '".KodeID."'
      left outer join dosen d on d.Login = m.PenasehatAkademik and d.KodeID = '".KodeID."'
    where h.TahunID = '$_SESSION[TahunID]'
      and h.ProdiID = '$_SESSION[ProdiID]'
      $whr_mhsw
    order by h.MhswID";
$r = mysqli_query($koneksi, $s);
  
while ($w = mysqli_fetch_array($r)) {
  BuatHeaderKHS($w, $pdf);
  BuatIsinya($w, $pdf);
  BuatFooter($w, $pdf);
}

$pdf->Output();

function BuatFooter($khs, $p) {
  global $arrID, $mrg;
  
  $SKSPerolehan = AmbilOneField("krs k left outer join khs h on k.KHSID=h.KHSID and h.KodeID='".KodeID."'", "k.MhswID='$khs[MhswID]' and k.Tinggi='*' and (h.Sesi <= $khs[Sesi] or k.KHSID=0) and k.KodeID",
    KodeID, "sum(k.SKS)");
  $SKSLulus = AmbilOneField("krs k left outer join khs h on k.KHSID=h.KHSID and h.KodeID='".KodeID."'", "k.MhswID='$khs[MhswID]' and k.Tinggi='*' and (h.Sesi <= $khs[Sesi] or k.KHSID=0) and k.GradeNilai != 'E' and k.KodeID",
    KodeID, "sum(k.SKS)");	
  
  $MaxSKS = AmbilOneField('maxsks',
    "KodeID='".KodeID."' and NA = 'N'
    and DariIP <= $khs[IPS] and $khs[IPS] <= SampaiIP and ProdiID", 
    $khs['ProdiID'], 'SKS')+0;
  // Pejabat
  $pjbt = AmbilFieldx('pejabat', "KodeID='".KodeID."' and KodeJabatan", 'KABAA', "*");
  // Array Isi
  $tgl = date('d M Y');
  $arr = array();
  $arr[] = array('Index Prestasi Semester', ':', $khs['IPS'], $arrID['Kota'].', '.$tgl);
  $arr[] = array('Index Prestasi Kumulatif', ':', $khs['IP'], "Mengetahui:");
  $arr[] = array('Total SKS Lulus', ':', $SKSLulus+0, $pjbt['Jabatan']);
  $arr[] = array('Total SKS Perolehan', ':', $SKSPerolehan+0);
  $arr[] = array('Max SKS Semester Depan', ':', $MaxSKS);
  $arr[] = array('~IMG~');
  $arr[] = array('', '', '', $pjbt['Nama']);
  $arr[] = array('', '', '', 'NIDN: '.$pjbt['NIP']);
  
  // Tampilkan
  $p->Ln(2);
  $t = 5;
  $p->SetFont('Times', '', 10);
  foreach ($arr as $a) {
    $p->Cell($mrg);
    if ($a[0] == '~IMG~') {
      $fn = "../ttd/$pjbt[KodeJabatan].ttd.gif";
      if (file_exists($fn)) {
        $p->Cell(130);
        $p->Image($fn, null, null, 20);
        $p->Ln(1);
      }
      else $p->Ln($t);
    }
    else {
      $p->Cell($mrg);
      $p->Cell(40, $t, $a[0], 0, 0);
      $p->Cell(2, $t, $a[1], 0, 0, 'C');
      $p->Cell(15, $t, $a[2], 0, 0);
      $p->Cell(30, $t, '', 0, 0);
      $p->Cell(63, $t, $a[3], 0, 0);
      $p->Ln($t);
    }
  }
  
  $p->Ln(2);
  $p->SetFont('Times', 'BIU', 9);
  $p->Cell($mrg);
  $p->Cell($lbr, $t, 'Keterangan:', 0, 1);
  $p->SetFont('Times', '', 9);
  $p->Cell($mrg);
  $p->Cell($lbr, $t, "( - ) Nilai Matakuliah belum masuk dari jurusan/dosen.", 0, 1);
  $p->Cell($mrg);
  $p->Cell($lbr, $t, "( BL ) Nilai belum lengkap.", 0, 1);
  $p->Cell($mrg);
}
function BuatIsinya($khs, $p) {
  global $mrg, $koneksi;
  BuatHeaderDetail($p);
  $s = "select k.*, left(k.Nama, 35) as MKNama,
      format((k.SKS * k.BobotNilai), 2) as NXK,
	  jj.Tambahan, jj.Nama as _NamaJenisJadwal
    from krs k left outer join jadwal j on j.JadwalID=k.JadwalID
			   left outer join jenisjadwal jj on jj.JenisJadwalID=j.JenisJadwalID
    where k.KHSID = $khs[KHSID]
    order by k.MKKode";
  $r = mysqli_query($koneksi, $s);
  $t = 5;
  $n = 0;
  $p->SetFont('Times', '', 8);
  $_sks = 0; $_nxk = 0;
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    $_sks += $w['SKS'];
    $_nxk += $w['NXK'];
    $p->Cell($mrg);
    $p->Cell(10, $t, $n, 'LB', 0, 'R');
    $p->Cell(24, $t, $w['MKKode'], 'B', 0);
	$TagTambahan = ($w['Tambahan'] == 'Y')? "( $w[_NamaJenisJadwal] )" : "";
    $p->Cell(70, $t, $w['MKNama'].' '.$TagTambahan, 'B', 0);
    $p->Cell(15, $t, $w['SKS'], 'B', 0, 'R');
    $p->Cell(15, $t, $w['GradeNilai'], 'B', 0, 'C');
    $p->Cell(15, $t, $w['BobotNilai'], 'B', 0, 'R');
    $p->Cell(15, $t, $w['NXK'], 'B', 0, 'R');
    $p->Cell(2, $t, '', 'BR', 0);
    $p->Ln($t);
  }
  // Tampilkan jumlahnya
  $__nxk = number_format($_nxk, 2);
  $p->Cell($mrg);
  $p->Cell(104, $t, 'Jumlah :', 'LB', 0, 'R');
  $p->Cell(15, $t, $_sks, 'B', 0, 'R');
  $p->Cell(45, $t, $__nxk, 'B', 0, 'R');
  $p->Cell(2, $t, '', 'BR', 0);
  $p->Ln($t);
}
function BuatHeaderDetail($p) {
  global $mrg;
  $t = 6;
  $p->SetFont('Times', 'B', 9);
  $p->Cell($mrg);
  $p->Cell(10, $t, 'No.', 'LBT', 0, R);
  $p->Cell(24, $t, 'Kode', 'BT', 0);
  $p->Cell(70, $t, 'Mata Kuliah', 'BT', 0);
  $p->Cell(15, $t, 'SKS', 'BT', 0, 'R');
  $p->Cell(15, $t, 'Nilai', 'BT', 0, 'C');
  $p->Cell(15, $t, 'Bobot', 'BT', 0, 'R');
  $p->Cell(15, $t, 'BxK', 'BT', 0, 'R');
  $p->Cell(2, $t, '', 'BTR', 0);
  $p->Ln($t);
}
function BuatHeaderKHS($khs, $p) {
  global $lbr, $mrg;
  $t = 5;
  
  $p->AddPage();
  $p->SetFont('Times', 'B', 14);
  $p->Cell($lbr, 7, "KARTU HASIL STUDI", 0, 1, 'C');
  $p->Ln(2);
  // parameter
  $prodi = $khs['_PRD'];
  $prg   = $khs['_PRG'];
  $thn   = $khs['_THN'];
  
  $data = array();
  $data[] = array('Nama', ':', $khs['Nama'], 'Tahun Akademik', ':', $thn);
  $data[] = array('NIM', ':', $khs['MhswID'], 'Program Studi', ':', $prodi);
  $data[] = array('Dosen PA', ':', $khs['_PA'], $khs['NamaSesi'], ':', $khs['Sesi']);
  // Tampilkan
  foreach ($data as $d) {
    $p->SetFont('Times', '', 9);
    $p->Cell($mrg);
    $p->Cell(20, 5, $d[0], 0, 0);
    $p->Cell(4, 5, $d[1], 0, 0);
    
    $p->SetFont('Times', 'B', 9);
    $p->Cell(68, 5, $d[2], 0, 0);
    
    $p->SetFont('Times', '', 9);
    $p->Cell(26, 5, $d[3], 0, 0);
    $p->Cell(4, 5, $d[4], 0, 0);
    
    $p->SetFont('Times', 'B', 9);
    $p->Cell(50, 5, $d[5], 0, 1);
  }
  $p->Ln(2);
  
  /*if ($khs['_Sisa'] > 0) {
    $_Sisa = number_format($khs['_Sisa']);
    $p->SetFont('Times', 'B', 12);
    $p->SetTextColor(255, 255, 255);
    $p->SetFillColor(250, 0, 0);
    $p->Cell($lbr, $t+2, "Mahasiswa memiliki hutang sebesar: Rp. $_Sisa", 1, 1, 'C', true);
    $p->Ln(2);
    
    $p->SetFillColor(0);
    $p->SetTextColor(0, 0, 0);
  } */
  
}
?>
