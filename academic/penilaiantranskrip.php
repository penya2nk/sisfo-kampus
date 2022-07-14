<?php
error_reporting(0);
$lungo = (empty($_REQUEST['lungo']))? 'HeaderTranskrip' : $_REQUEST['lungo'];
$lungo();

function HeaderTranskrip() {
  $MhswID = GainVariabelx('MhswID');
  TitleApps("Cetak Transkrip Nilai");
  $tombols = '&nbsp;';
  if (empty($_SESSION['MhswID'])) {
    $mhsw = array();
  }
  else {
    $mhsw = AmbilFieldx("mhsw m 
      left outer join prodi prd on m.ProdiID=prd.ProdiID and prd.KodeID='".KodeID."'
	  left outer join jenjang j on prd.JenjangID=j.JenjangID
      left outer join program prg on m.ProgramID=prg.ProgramID and prg.KodeID='".KodeID."'
      left outer join dosen d on m.PenasehatAkademik=d.Login and d.KodeID='".KodeID."'", 
      "m.MhswID='$_SESSION[MhswID]' and m.KodeID", KodeID, 
      "m.MhswID, m.Nama, m.TempatLahir, m.TanggalLahir, m.ProgramID, m.ProdiID, m.PenasehatAkademik,
      d.Nama as NamaDosen, d.Gelar, j.Nama as _Jenjang,
      prd.Nama as _PRD, prg.Nama as _PRG");
    if (empty($mhsw)) $mhsw = array();
    else {
      if (empty($mhsw['NamaDosen'])) $mhsw['NamaDosen'] = "<font color=red>&times;</font> Belum diset";
      RandomStringScript();
      $tombols = <<<ESD
      <input class='btn-success' type=button name='btnTranskrip' value='Transkrip Nilai'
        onClick="javascript:fnCetakTranskrip('$mhsw[MhswID]', 0)" />
      <input class='btn-danger' type=button name='btnTranskripPerJenis' value='Transkrip Per Jenis MK'
        onClick="javascript:fnCetakTranskrip('$mhsw[MhswID]', 1)" />
      <input class='btn-warning' type=button name='btnTranskrip' value='Transkrip Nilai Sementara'
        onClick="javascript:fnCetakTranskrip('$mhsw[MhswID]', 2)" />
	  
      <script>
      function fnCetakTranskrip(MhswID, jen) {
        var _rnd = randomString();
        lnk = "$_SESSION[ndelox].php?lungo=_CetakTranskrip&MhswID="+MhswID+"&_rnd="+_rnd+"&jen="+jen;
        win2 = window.open(lnk, "", "width=700, height=500, scrollbars");
        if (win2.opener == null) childWindow.opener = self;
      }
      </script>
ESD;
    }
  }
  echo <<<ESD
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='examplex' class='tablex table-sm table-stripedx' style='width:60%' align='center'>
  <form name='frmHeader' action='?' method=POST>
  <input type=hidden name='lungo' value='' />
  
  <tr><td class=inp width=200>NIM</td>
      <td class=ul width=220>
        <input style='height:30px' type=text name='MhswID' value='$_SESSION[MhswID]' size=15 maxlength=50 />
        <input class='btn btn-success btn-sm' type=submit name='btnCari' value='Cari' />
      </td>
      <td class=inp width=220>Nama Mahasiswa</td>
      <td class=ul>
        <b>: $mhsw[Nama]</b> &nbsp;
      </td>
      </tr>
  <tr><td class=inp>Program / Prodi</td>
      <td class=ul>: $mhsw[_PRD] / $mhsw[_PRG]</td>
      <td class=inp>Penasehat Akademik</td>
      <td class=ul>: $mhsw[NamaDosen] <sup>$mhsw[Gelar]</sup>&nbsp;</td>
   </tr>
     <tr><td class=inp>Tempat Lahir</td>
      <td class=ul>: $mhsw[TempatLahir] </td>
      <td class=inp>Tanggal Lahir</td>
      <td class=ul>: $mhsw[TanggalLahir] </td>
   </tr>
  <tr>

  
  
  </table>
  </div>
  
</div>
</div>


		<div align=center>
			<div class='card'>
			<div class='card-header'>
			 $tombols
			 </form>
			</div>
		</div>
</div>
  </p>
ESD;
}

function BuatHeaderTranskrip($mhsw, $jen, $p) {
  global $koneksi;
  $lbr = 190;
  $p->SetFont('Times', 'B', 14);
  
  if($jen < 2) $p->Cell($lbr, 8, "Transkrip Nilai Akademik", 0, 1, 'C');
  else if($jen == 2) $p->Cell($lbr, 8, "Transkrip Nilai Akademik Sementara", 0, 1, 'C');
  else $p->Cell($lbr, 8, "Transkrip Nilai Akademik", 0, 1, 'C');
  
  $s = "select DISTINCT(m.KonsentrasiID) as _KonsentrasiID, COUNT(k.KRSID) as _countKID  
			from krs k left outer join mk m on m.MKID=k.MKID and m.KodeID='".KodeID."'
			where k.MhswID='$mhsw[MhswID]' and m.KonsentrasiID!=0 and k.KodeID='".KodeID."'
			group by m.KonsentrasiID
			order by _countKID DESC";
  $r = mysqli_query($koneksi, $s);
  $w = mysqli_fetch_array($r);
  
  $konsentrasi = (empty($w['_KonsentrasiID']))? "-" : AmbilOneField("konsentrasi", "KonsentrasiID='$w[_KonsentrasiID]' and KodeID", KodeID, "Nama");
  
  $arr = array();
  $arr[] = array("NIM", ':', $mhsw['MhswID'], 'Jenjang', ':', $mhsw['_Jenjang']);
  $arr[] = array('Nama', ':', $mhsw['Nama'], 'Program Studi', ':', $mhsw['_PRD']);
  $arr[] = array('Tempat/Tgl Lahir', ':', $mhsw['TempatLahir'] . ', ' . $mhsw['_TanggalLahir'], 'Konsentrasi', ':', $konsentrasi);
  
  $t = 6;
  foreach ($arr as $a) {
    // Kolom 1
    $p->SetFont('Helvetica', '', 10);
    $p->Cell(30, $t, $a[0], 0, 0);
    $p->Cell(3, $t, $a[1], 0, 0);
    
    $p->SetFont('Helvetica', 'B', 10);
    $p->Cell(60, $t, $a[2], 0, 0);
    $p->Cell(10);
    // Kolom 2
    $p->SetFont('Helvetica', '', 10);
    $p->Cell(30, $t, $a[3], 0, 0);
    $p->Cell(3, $t, $a[4], 0, 0);
    
    $p->SetFont('Helvetica', 'B', 10);
    $p->Cell(50, $t, $a[5], 0, 0);
    
    $p->Ln($t);
  }
  $p->Ln(2);
  
  // Judul tabel
  $t = 6;
  $p->SetFont('Helvetica', 'B', 9);
  $p->Cell(10, $t, 'No.', 1, 0, 'C');
  $p->Cell(24, $t, 'Kode MK', 1, 0, 'C');
  $p->Cell(90, $t, 'Nama Mata Kuliah', 1, 0, 'C');
  $p->Cell(15, $t, 'SKS', 1, 0, 'C');
  $p->Cell(15, $t, 'Nilai', 1, 0, 'C');
  $p->Cell(15, $t, 'Bobot', 1, 0, 'C');
  $p->Cell(15, $t, 'Mutu', 1, 0, 'C');
  $p->Ln($t); 
}

function BuatIsiTranskrip0($mhsw, $p) {
  global $koneksi;
  // Reset nilai tertinggi
  ResetNilaiTertinggi($mhsw);
  BuatNilaiTertinggi($mhsw);
  // Tampilkan isinya
  $s = "select k.KRSID, k.MKKode, k.Nama, k.BobotNilai, k.GradeNilai, k.SKS, k.Tinggi
    from krs k left outer join jadwal j on k.JadwalID=j.JadwalID
				left outer join jenisjadwal jj on jj.JenisJadwalID=j.JenisJadwalID
    where k.KodeID = '".KodeID."'
      and k.MhswID = '$mhsw[MhswID]'
      and k.Tinggi = '*'
    order by k.MKKode";
  $r = mysqli_query($koneksi, $s); $n = 0;
  
  $p->SetFont('Helvetica', '', 8);
  $t = 5; $_sks = 0; $_nxk = 0;
  
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    $mutu = $w['SKS'] * $w['BobotNilai'];
    $_nxk += $mutu;
    $_sks += $w['SKS'];
    $p->Cell(10, $t, $n, 1, 0, 'C');
    $p->Cell(24, $t, $w['MKKode'], 1, 0);
    $p->Cell(90, $t, $w['Nama'], 1, 0);
    $p->Cell(15, $t, $w['SKS'], 1, 0, 'C');
    $p->Cell(15, $t, $w['GradeNilai'], 1, 0, 'C');
    $p->Cell(15, $t, $w['BobotNilai'], 1, 0, 'C');
    $p->Cell(15, $t, $mutu, 1, 0, 'C');
    $p->Ln($t);
  }
  // Tampilkan jumlahnya
  $p->SetFont('Helvetica', 'B', 9);
  $p->Cell(124, $t, 'JUMLAH:', 'LB', 0, 'R');
  $p->Cell(15, $t, $_sks, 'B', 0, 'C');
  $p->Cell(30, $t, '', 'B', 0);
  $p->Cell(15, $t, $_nxk, 'BR', 0, 'C');
  $p->Ln($t);
  $p->Ln(2);
}
function BuatIsiTranskrip1($mhsw, $p) {
  global $koneksi;
  // Reset nilai tertinggi
  ResetNilaiTertinggi($mhsw);
  BuatNilaiTertinggi($mhsw);
  // Tampilkan isinya
  $s = "select k.KRSID, k.MKKode, k.Nama, k.BobotNilai, k.GradeNilai, k.SKS, k.Tinggi,
      j.JenisMKID, j.Urutan, j.Singkatan, j.Nama as JenisMK
    from krs k
      left outer join mk m on k.MKID=m.MKID and m.KodeID='".KodeID."'
      left outer join jenismk j on m.JenisMKID = j.JenisMKID and j.KodeID='".KodeID."'
	  left outer join jadwal jd on jd.JadwalID=k.JadwalID
	  left outer join jenisjadwal jj on jd.JenisJadwalID = jj.JenisJadwalID
	where k.KodeID = '".KodeID."'
      and k.MhswID = '$mhsw[MhswID]'
      and k.Tinggi = '*'
    order by j.Urutan, k.MKKode";
  $r = mysqli_query($koneksi, $s); $n = 0;
  
  $t = 5; $_sks = 0; $_nxk = 0;
  $lbr = 184;
  $jenismkid = '-19721222';
  
  while ($w = mysqli_fetch_array($r)) {
    if ($jenismkid != $w['JenisMKID']) {
      $jenismkid = $w['JenisMKID'];
      $p->SetFont('Helvetica', 'B', 8);
      $p->Cell($lbr, $t, $w['JenisMK'] . ' (' . $w['Singkatan']. ')', 'LBR', 1);
      $n = 0;
    }
    $p->SetFont('Helvetica', '', 8);
    $n++;
    $mutu = $w['SKS'] * $w['BobotNilai'];
    $_nxk += $mutu;
    $_sks += $w['SKS'];
    $p->Cell(10, $t, $n, 'LB', 0, 'C');
    $p->Cell(24, $t, $w['MKKode'], 'B', 0);
    $p->Cell(90, $t, $w['Nama'], 'B', 0);
    $p->Cell(15, $t, $w['SKS'], 'B', 0, 'C');
    $p->Cell(15, $t, $w['GradeNilai'], 'B', 0, 'C');
    $p->Cell(15, $t, $w['BobotNilai'], 'B', 0, 'C');
    $p->Cell(15, $t, $mutu, 'BR', 0, 'C');
    $p->Ln($t);
  }
  // Tampilkan jumlahnya
  $p->SetFont('Helvetica', 'B', 9);
  $p->Cell(124, $t, 'JUMLAH:', 'LB', 0, 'R');
  $p->Cell(15, $t, $_sks, 'B', 0, 'C');
  $p->Cell(30, $t, '', 'B', 0);
  $p->Cell(15, $t, $_nxk, 'BR', 0, 'C');
  $p->Ln($t);
  $p->Ln(2);
}
function BuatIsiTranskrip2($mhsw, $p) {
  global $koneksi;
  // Reset nilai tertinggi
  ResetNilaiTertinggi($mhsw);
  BuatNilaiTertinggi($mhsw);
  // Tampilkan isinya
  $s = "select k.KRSID, k.MKKode, k.Nama, k.BobotNilai, k.GradeNilai, k.SKS, k.Tinggi
    from krs k left outer join jadwal j on k.JadwalID=j.JadwalID
				left outer join jenisjadwal jj on jj.JenisJadwalID=j.JenisJadwalID
    where k.KodeID = '".KodeID."'
      and k.MhswID = '$mhsw[MhswID]'
      and k.Tinggi = '*'
	  and k.Final = 'Y'
    order by k.MKKode";
  $r = mysqli_query($koneksi, $s); $n = 0;
  
  $p->SetFont('Helvetica', '', 8);
  $t = 5; $_sks = 0; $_nxk = 0;
  
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    $mutu = $w['SKS'] * $w['BobotNilai'];
    $_nxk += $mutu;
    $_sks += $w['SKS'];
    $p->Cell(10, $t, $n, 1, 0, 'C');
    $p->Cell(24, $t, $w['MKKode'], 1, 0);
    $p->Cell(90, $t, $w['Nama'], 1, 0);
    $p->Cell(15, $t, $w['SKS'], 1, 0, 'C');
    $p->Cell(15, $t, $w['GradeNilai'], 1, 0, 'C');
    $p->Cell(15, $t, $w['BobotNilai'], 1, 0, 'C');
    $p->Cell(15, $t, $mutu, 1, 0, 'C');
    $p->Ln($t);
  }
  // Tampilkan jumlahnya
  $p->SetFont('Helvetica', 'B', 9);
  $p->Cell(124, $t, 'JUMLAH:', 'LB', 0, 'R');
  $p->Cell(15, $t, $_sks, 'B', 0, 'C');
  $p->Cell(30, $t, '', 'B', 0);
  $p->Cell(15, $t, $_nxk, 'BR', 0, 'C');
  $p->Ln($t);
  $p->Ln(2);
}
function BuatFooterTranskrip($mhsw, $p) {
  global $koneksi;
  $krs = AmbilFieldx('krs', "MhswID='$mhsw[MhswID]' and Tinggi='*' and KodeID",
    KodeID, "sum(SKS) as _SKS, sum(SKS*BobotNilai) as _NXK");
  $s = "select * from nilai where ProdiID='$mhsw[ProdiID]' and Lulus='N' and KodeID='".KodeID."'";
  $r = mysqli_query($koneksi, $s);
  $whr_gagal = '';
  while($w = mysqli_fetch_array($r))
  {	$whr_gagal .= " and GradeNilai != '$w[Nama]' ";
  }
  $SKSLulus = AmbilOneField('krs', "MhswID='$mhsw[MhswID]' and Tinggi='*' $whr_gagal and GradeNilai != '-' and KodeID",
    KodeID, "sum(SKS)");	
  $_sks = $krs['_SKS']+0;
  $_nxk = $krs['_NXK']+0;
  // Buat footernya
  $ipk = ($_sks > 0)? $_nxk / $_sks : 0;
  $_ipk = number_format($ipk, 2);
  $predikat = AmbilOneField("predikat", "ProdiID='$mhsw[ProdiID]' and IPKMin <= $_ipk and $_ipk <= IPKMax and KodeID", 
    KodeID, 'Nama');
  $identitas = AmbilFieldx('identitas', 'Kode', KodeID, '*');
  $tgl = date('d M Y');
  
  $prd = AmbilFieldx('prodi', "ProdiID='$mhsw[ProdiID]' and KodeID", KodeID, '*');
  //$pjbt = AmbilFieldx('pejabat', "KodeJabatan='KETUA' and KodeID", KodeID, '*');
  $pjbt = AmbilFieldx('pejabat', "KodeID='".KodeID."' and KodeJabatan", 'Kaprodi-'.$mhsw['ProdiID'], "*");
  
  $arr = array();
  $arr[] = array('Jumlah SKS yang lulus', ':', $SKSLulus . ' SKS');
  $arr[] = array('Jumlah SKS yang diperoleh', ':', $_sks . ' SKS', $identitas['Kota'] . ', '. $tgl);
  $arr[] = array('Jumlah SKS yang harus ditempuh', ':', $prd['TotalSKS'] . ' SKS', $pjbt['Jabatan']);
  $arr[] = array('Jumlah Nilai Mutu (N x K)', ':', $_nxk);
  $arr[] = array();
  $arr[] = array('~Indeks Prestasi Kumulatif (IPK)', ':', $_ipk);
  $arr[] = array('~Predikat Kelulusan', ':', $predikat, $pjbt['Nama']);
  $arr[] = array('', '', '', 'NIP. ' . $pjbt['NIP']);
  // Tampilkan
  $t = 4;
  foreach ($arr as $a) {
    $b = ($a[0][0] == '~')? 'B' : '';
    $a[0] = str_replace('~', '', $a[0]);
    $p->SetFont('Helvetica', $b, 9);
    $p->Cell(55, $t, $a[0], 0, 0);
    $p->Cell(3, $t, $a[1], 0, 0);
    $p->Cell(60, $t, $a[2], 0, 0);
    
    $p->Cell(10);
    $p->Cell(60, $t, $a[3], 0, 0);
    $p->Ln($t);
  }
}

function ResetNilaiTertinggi($mhsw) {
  global $koneksi;
  $s = "update krs set Tinggi = '' where MhswID='$mhsw[MhswID]' and KodeID='".KodeID."' ";
  $r = mysqli_query($koneksi, $s);
}

function BuatNilaiTertinggi($mhsw) {
  global $koneksi;
  // Ambil semuanya dulu
  $s = "select k.KRSID, k.MKKode, k.BobotNilai, k.GradeNilai, k.SKS, k.Tinggi
    from krs k left outer join jadwal j on k.JadwalID=j.JadwalID
				left outer join jenisjadwal jj on jj.JenisJadwalID=j.JenisJadwalID
    where k.KodeID = '".KodeID."'
      and k.MhswID = '$mhsw[MhswID]'
	  and jj.Tambahan = 'N'
    order by k.MKKode";
  $r = mysqli_query($koneksi, $s);
  
  while ($w = mysqli_fetch_array($r)) {
    $ada = AmbilFieldx('krs', "Tinggi='*' and KRSID<>'$w[KRSID]' and MhswID='$mhsw[MhswID]' and MKKode", $w['MKKode'], '*');
    // Jika nilai sekarang lebih tinggi
    if ($w['BobotNilai'] > $ada['BobotNilai']) {
      $s1 = "update krs set Tinggi='*' where KRSID='$w[KRSID]' ";
      $r1 = mysqli_query($koneksi, $s1);
      // Cek yg lalu, kalau tinggi, maka reset
      if ($ada['Tinggi'] == '*') {
        $s1a = "update krs set Tinggi='' where KRSID='$ada[KRSID]' ";
        $r1a = mysqli_query($koneksi, $s1a);
      }
    }
    // Jika yg lama lebih tinggi, maka ga usah diapa2in
    else {
    }
  }
}

function _CetakTranskrip() {
  session_start();
  include_once "../pengembang.lib.php";
  include_once "../konfigurasi.mysql.php";
  include_once "../sambungandb.php";
  include_once "../setting_awal.php";
  include_once "../check_setting.php";
  include_once "../header_pdf.php";
  
  //leweh add ob_start(); while error at php higher version
ob_start();
//end leweh add
  $pdf = new PDF();
  $pdf->SetTitle("Transkrip Nilai");
  $pdf->AddPage();
  $lbr = 190;
  
  $MhswID = $_REQUEST['MhswID'];
  $mhsw = AmbilFieldx("mhsw m 
      left outer join prodi prd on m.ProdiID=prd.ProdiID and prd.KodeID='".KodeID."'
      left outer join jenjang j on j.JenjangID=prd.JenjangID
	  left outer join program prg on m.ProgramID=prg.ProgramID and prg.KodeID='".KodeID."'
      left outer join dosen d on m.PenasehatAkademik=d.Login and d.KodeID='".KodeID."'", 
      "m.MhswID='$_SESSION[MhswID]' and m.KodeID", KodeID, 
      "m.MhswID, m.Nama, m.ProgramID, m.ProdiID, m.PenasehatAkademik,
      m.TempatLahir, m.TanggalLahir,
      date_format(m.TanggalLahir, '%d %M %Y') as _TanggalLahir,
      d.Nama as NamaDosen, d.Gelar, j.Nama as _Jenjang,
      prd.Nama as _PRD, prg.Nama as _PRG");
  
  
  $jen = $_REQUEST['jen']+0;
  BuatHeaderTranskrip($mhsw, $jen, $pdf);
  $cetak = 'BuatIsiTranskrip'.$jen;
  $cetak($mhsw, $pdf);
  BuatFooterTranskrip($mhsw, $pdf);
  
  $pdf->Output();
}
?>
