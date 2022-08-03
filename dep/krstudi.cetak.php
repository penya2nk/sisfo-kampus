<?php
error_reporting(0);
session_start();

  include_once "../pengembang.lib.php";
  include_once "../konfigurasi.mysql.php";
  include_once "../sambungandb.php";
  include_once "../setting_awal.php";
  include_once "../check_setting.php";
  include_once "../header_pdf.php";

$khsid = $_REQUEST['khsid'];
$khs = AmbilFieldx("khs", "KHSID", $khsid, "*");
if (empty($khs))
  die(PesanError("Error",
    "Data mahasiswa tidak ditemukan.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    <input type=button name='Tutup' value='Tutup'
      onClick=\"window.close()\" />"));

$mhsw = AmbilFieldx("mhsw m
  left outer join dosen d on d.Login = m.PenasehatAkademik and d.KodeID = '".KodeID."' ",
  "m.KodeID='".KodeID."' and m.MhswID", $khs['MhswID'],
  "m.MhswID, m.Nama, m.PenasehatAkademik, m.StatusAwalID, m.StatusMhswID,
  m.TotalSKS,
  if (d.Nama is NULL or d.Nama = '', 'Belum diset', concat(d.Nama, ', ', d.Gelar)) as PA");

$lbr = 190;
//leweh add ob_start(); while error at php higher version
ob_start();
//end leweh add

$pdf = new FPDF();
$pdf->SetTitle("Kartu Rencana Studi");
$pdf->AddPage();
$pdf->SetFont('Helvetica', 'B', 16);
$pdf->Cell($lbr, 9, "Kartu Rencana Studi", 0, 1, 'C');

// Buat header dulu
BuatHeader($khs, $mhsw, $pdf);
// Tampilkan datanya
AmbilKRS($khs, $mhsw, $pdf);
// Buat footer
$pdf->Cell($lbr, 1, '', 1, 1);
BuatFooter($khs, $mhsw, $pdf);

$pdf->Output();

// *** Functions ***
function BuatFooter($khs, $mhsw, $p) {
  global $arrID;
  $t = 6;
  // Yang diambil
  $p->Cell(98, $t, "Jumlah SKS yang diambil:", 'LB', 0, 'R');
  $p->Cell(10, $t, $khs['SKS'], 'B', 0, 'C');
  $p->Cell(82, $t, ' ', 'BR', 1);
  // Yang sudah ditempuh
  $p->Cell(98, $t, "Jumlah SKS yang telah ditempuh:", 'LB', 0, 'R');
//  $p->Cell(10, $t, $khs['TotalSKS'], 'B', 0, 'C'); Dalam Perbaikan Team BAAK
  $p->Cell(10, $t, ' ', 'B', 0, 'C');
  $p->Cell(82, $t, ' ', 'BR', 1);
  // Tanda tangan
  //$pjbt = AmbilFieldx('pejabat', "KodeID='".KodeID."' and KodeJabatan", 'Kaprodi "$prodi"', "*");
    $pjbt = AmbilFieldx('pejabat', "KodeID='".KodeID."' and KodeJabatan", 'Kaprodi-'.$khs['ProdiID'], "*");
  $p->Ln(4);
  $p->Cell(10);
  // $p->Cell(100, $t, $arrID['Kota'] . ", " . date('d M Y'), 0, 1);
 
  $p->Cell(10);
  $p->Cell(50, $t, "Mengetahui,", 0, 0);
  $p->Cell(15);
  $p->Cell(50, $t, "Pembimbing Akademik," , 0, 0);
  $p->Cell(30);
  $p->Cell(50, $t, "Mahasiswa," , 0, 1);  
  $p->ln(15);
  
  // $fn = "../ttd/$pjbt[KodeJabatan].ttd.gif";
  if (file_exists($fn)) {
    $p->Cell(22);
    $p->Image($fn, null, null, 20);
    $p->Ln(2);
  }
  else $p->Ln(20);

  $p->Cell(10);
  $p->SetFont('Helvetica', 'B', 9);
  // $p->Cell(50, $t, $pjbt['Nama'], 0, 0);
  
  $p->Cell(15);
  $p->SetFont('Helvetica', '', 9);
  $p->Cell(50, $t, '(                                                    )', 0, 0);
  
  $p->Cell(30);
  $p->SetFont('Helvetica', '', 9);
  $p->Cell(50, $t, $mhsw['Nama'], 0, 1);
  
  $p->Cell(10);
  $p->SetFont('Helvetica', 'B', 9);
  // $p->Cell(50, $t, $pjbt['Jabatan'], 0 , 0);
  
  $p->ln(20);
  $p->Cell(1 ,7, '');$p->Cell(0 ,0, '1. KRS diprint empat rangkap dan diserahkan ke Prodi, BAAK, dan Pembimbing Akademik', 0,0,"L");
  $p->ln(5);
  $p->Cell(1 ,7, '');$p->Cell(0 ,0, '2. KRS dianggap sah setelah ditanda tangani oleh Mahasiswa, Pembimbing Akademik, dan Ketua Prodi', 0,0,"L");
  $p->ln(5);
  $p->Cell(1 ,7, '');$p->Cell(0 ,0, '3. Bagi mahasiswa yang tidak menyerahkan KRS ke Prodi, BAAK, dan Pembimbing Akademik dianggap', 0,0,"L");
  $p->ln(5);
  $p->Cell(3 ,7, '');$p->Cell(0 ,0, ' PASIF/ALFA STUDI pada semester berjalan', 0,0,"L");
}
function AmbilKRS($khs, $mhsw, $p) {
	global $koneksi;
  // Buat headernya dulu
  $p->SetFont('Helvetica', 'B', 9);
  $t = 6;
  
  $p->Cell(8, $t, 'No', 1, 0);
  //$p->Cell(14, $t, 'Hari', 1, 0);
  $p->Cell(20, $t, 'Kode MK', 1, 0, 'C');
  $p->Cell(70, $t, 'Matakuliah', 1, 0);
  $p->Cell(10, $t, 'SKS', 1, 0, 'C');
  $p->Cell(50, $t, 'Dosen Pengajar', 1, 0);
  $p->Cell(22, $t, 'Jam', 1, 0, 'C');
  $p->Cell(10, $t, 'Kelas', 1, 1, 'C');

  // Ambil Isinya
  $s = "select k.KRSID, j.Nama, j.MKID, j.MKKode, j.SKS, j.NamaKelas, j.RuangID,
      LEFT(j.JamMulai, 5) as JM, LEFT(j.JamSelesai, 5) as JS,
      h.Nama as HR, j.DosenID,
      left(j.Nama, 40) as MK,
      if (d.Nama is NULL or d.Nama = '', 'Belum diset', left(concat(d.Nama, ', ', d.Gelar), 25)) as DSN,
	  jj.Nama as _NamaJenisJadwal, jj.Tambahan
    from krs k
      left outer join jadwal j on j.JadwalID = k.JadwalID
      left outer join hari h on h.HariID = j.HariID
      left outer join dosen d on d.Login = j.DosenID and d.KodeID = '".KodeID."'
	  left outer join jenisjadwal jj on jj.JenisJadwalID=j.JenisJadwalID
    where k.KHSID = $khs[KHSID]
    order by j.HariID, j.JamMulai ";
  $r = mysqli_query($koneksi, $s);
  $n = 0; $_h = 'akjsdfh';
  $t = 6;
  $p->SetFont('Helvetica', '', 8);
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    if ($_h != $w['HR']) {
      $_h = $w['HR'];
	  $hr = $w['HR'];
	  
	  $p->SetFont('Helvetica', 'B', 8);
	  $p->Cell(190, $t, $w['HR'], 'LBR', 1, 'L');
	  $p->SetFont('Helvetica', '', 8);
    } //else $hr = '-';
    //function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false)
    $p->Cell(8, $t, $n, 'LB', 0, 'R');
    //$p->Cell(14, $t, $hr, 'B');
    
    $p->Cell(20, $t, $w['MKKode'], 'B', 0, 'C');
    $TagTambahan = ($w['Tambahan'] == 'Y')? "( $w[_NamaJenisJadwal] )" : "";
	$p->Cell(70, $t, $w['MK'].' '.$TagTambahan, 'B');
    $p->Cell(10, $t, $w['SKS'], 'B', 0, 'C');
    $p->Cell(50, $t, $w['DSN'], 'B');
    $p->Cell(22, $t, $w['JM'] . ' - ' . $w['JS'], 'B', 0, 'C');
	$p->Cell(10, $t, $w['RuangID'], 'BR', 1, 'C');
  }
}
function BuatHeader($khs, $mhsw, $p) {
  $prodi = AmbilOneField('prodi', "KodeID='".KodeID."' and ProdiID", $khs['ProdiID'], 'Nama');
  $prg   = AmbilOneField('program', "KodeID='".KodeID."' and ProgramID", $khs['ProgramID'], 'Nama');
  $thn = AmbilOneField('tahun', "KodeID='".KodeID."' and TahunID='$khs[TahunID]' and ProdiID='$khs[ProdiID]' and ProgramID", $khs['ProgramID'], 'Nama');
  //$tahn=AmbilFieldx('krs',"KodeID='".KodeID."' and TahunID='$khs[TahunID]'");
  $data = array();
  $data[] = array('Nama', ':', $mhsw['Nama'], 'Tahun Akademik', ':', $khs['TahunID']);
  $data[] = array('NIM', ':', $mhsw['MhswID'], 'Program Studi', ':', $prodi);
  $data[] = array('Dosen PA', ':', $mhsw['PA'], 'Prg Pendidikan', ':', $prg);
  
  foreach ($data as $d) {
    $p->SetFont('Helvetica', 'I', 9);
    $p->Cell(24, 5, $d[0], 0, 0);
    $p->Cell(4, 5, $d[1], 0, 0);
    
    $p->SetFont('Helvetica', 'B', 9);
    $p->Cell(74, 5, $d[2], 0, 0);
    
    $p->SetFont('Helvetica', 'I', 9);
    $p->Cell(26, 5, $d[3], 0, 0);
    $p->Cell(4, 5, $d[4], 0, 0);
    
    $p->SetFont('Helvetica', 'B', 9);
    $p->Cell(50, 5, $d[5], 0, 1);
  }
  $p->Ln(2);
}
?>
