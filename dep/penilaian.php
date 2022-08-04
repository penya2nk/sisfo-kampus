<?php
// error_reporting(0);
$TahunID = GainVariabelx('TahunID');
$ProdiID = GainVariabelx('ProdiID');
$_nilaiJadwalID = GainVariabelx('_nilaiJadwalID');
$tabNilai = GainVariabelx('tabNilai', 'Bobot');
$arrNilai = array("<b>BOBOT PENILAIAN</b>~Bobot~Nilai2",
  "<b>NILAI MAHASISWA</b>~NilaiMhsw~Nilai2"
  );

TitleApps("PENILAIAN MAHASISWA");
$lungo = (empty($_REQUEST['lungo']))? 'ListMK' : $_REQUEST['lungo'];
$lungo();

function TampilkanHeaderPenilaian() {
	global $koneksi;
  $s = "select DISTINCT(TahunID) from tahun where KodeID='".KodeID."' order by TahunID DESC";
  $r = mysqli_query($koneksi, $s);
  $opttahun = "<option value=''></option>";
  while($w = mysqli_fetch_array($r))
  {  $ck = ($w['TahunID'] == $_SESSION['TahunID'])? "selected" : '';
     $opttahun .=  "<option value='$w[TahunID]' $ck>$w[TahunID]</option>";
  }

  $optprodi = AmbilPenggunaProdi($_SESSION['_Login'], $_SESSION['ProdiID']);
  if (!empty($_SESSION['TahunID']) && !empty($_SESSION['ProdiID']) && ($_SESSION['_LevelID'] != 100)) {
    $ExportXL = "<input class='btn-warning' type=button name='ExportXL' value='Export ke XL (untuk SMS)'
        onClick=\"location='$_SESSION[ndelox].exportxl.php?TahunID=$_SESSION[TahunID]&ProdiID=$_SESSION[ProdiID]'\" />";
  }
  else {
    $ExportXL = '';
  }
  $CetakBelumAdaUAS = "<input class='btn-primary' type=button name='BelumAdaUAS' value='Cetak Dosen Yang Belum Entry Nilai UAS'
		onClick=\"CetakBelumUAS('$_SESSION[TahunID]', '$_SESSION[ProdiID]')\">";
  // Jika dosen yg login
  if ($_SESSION['_LevelID'] == 100) {
    $frmProdi = '';
  }
  else { // Jika staff
    $frmProdi = "
      PROGRAM STUDI <select style='height:30px' name='ProdiID' onChange='this.form.submit()'>$optprodi</select>";
  }
  echo "
  <script>
	
	function CetakBelumUAS(thn, prd) {
      lnk = \"$_SESSION[ndelox].takadauas.php?TahunID=\"+thn+\"&ProdiID=\"+prd;
      win2 = window.open(lnk, \"\", \"width=800, height=600, scrollbars, status\");
      if (win2.opener == null) childWindow.opener = self;
	}
  </script>
  <div class='card'>
<div class='card-header'>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='lungo' value='' />
  <div align='center'>
    TAHUN AK <select style='height:30px' name='TahunID' />$opttahun </select>      
    $frmProdi
    <input style='margin-top:-5px' class='btn btn-success btn-sm' type=submit name='Tampilkan' value='Lihat Data' />
    </div>
    </div>
    </div>
   
  </form>
  <div class='card'>
    <div class='card-header'>
    <div align='center'>$ExportXL $CetakBelumAdaUAS </div>
    </div>
</div>";
}
function ListMK() {
  TampilkanHeaderPenilaian();
  global $koneksi;
  if ($_SESSION['_LevelID'] == 100) {
    $whr_dsn = "and j.DosenID = '$_SESSION[_Login]' ";
    $whr_prd = '';
  }
  else {
    $whr_dsn = '';
    $whr_prd = "and j.ProdiID = '$_SESSION[ProdiID]'";
  }
/*
  $s = "select j.*, h.Nama as HR, p.Nama as _PRD,
      concat(d.Nama, ' <sup>', d.Gelar, '</sup>') as DSN,
      left(j.JamMulai, 5) as _JM, left(j.JamSelesai, 5) as _JS,
      if (j.Final = 'Y', 'Final', 'Draft') as STT,
	  jj.Nama as _NamaJenisJadwal, jj.Tambahan
    from jadwal j
      left outer join dosen d on d.Login = j.DosenID and d.KodeID = '".KodeID."'
      left outer join hari h on j.HariID = h.HariID
      left outer join prodi p on p.ProdiID = j.ProdiID and p.KodeID = '".KodeID."'
	  left outer join jenisjadwal jj on jj.JenisJadwalID=j.JenisJadwalID
    where j.KodeID = '".KodeID."'
      and j.TahunID = '$_SESSION[TahunID]'
      $whr_prd
      $whr_dsn
	  and jj.Tambahan = 'N'
    order by d.Nama, j.HariID, j.JamMulai, j.JamSelesai";
*/  
  $s = "select j.*, h.Nama as HR, p.Nama as _PRD,
      concat(d.Nama, ' <sup style=color:yellow>', d.Gelar, '</sup>') as DSN,
      left(j.JamMulai, 5) as _JM, left(j.JamSelesai, 5) as _JS,
      jj.Nama as _NamaJenisJadwal, jj.Tambahan,
	  mk.TugasAkhir, mk.PraktekKerja, k.Nama AS namaKelas
    from jadwal j
      left outer join dosen d on d.Login = j.DosenID and d.KodeID = '".KodeID."'
	  left outer join mk mk on mk.MKID=j.MKID and mk.KodeID='".KodeID."'
      left outer join hari h on j.HariID = h.HariID
      left outer join prodi p on p.ProdiID = j.ProdiID and p.KodeID = '".KodeID."'
	  left outer join jenisjadwal jj on jj.JenisJadwalID=j.JenisJadwalID 
	  LEFT OUTER JOIN kelas k ON k.KelasID = j.NamaKelas
    where j.KodeID = '".KodeID."'
      and j.TahunID = '$_SESSION[TahunID]'
	  and j.NA = 'N'
      $whr_prd
      $whr_dsn
	order by d.Nama, j.HariID, j.JamMulai, j.JamSelesai";
	
  $r = mysqli_query($koneksi, $s); $n=0;
  $dsn = 'laskdjfoaiurhfasdlasdkjf';
  echo"<br>";
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>";
  echo "<tr style='background:purple;color:white'>
    <th class=ttl width=20>#</th>
    <th class=ttl width=80>Kode</th>
    <th class=ttl width=240>Mata Kuliah</th>
    <th class=ttl width=60>SKS</th>
    <th class=ttl width=100>Kelas / Program </th>
    <th class=ttl width=60>Jadwal</th>
    <th class=ttl width=70>Jam</th>
    <th class=ttl width=40>Jml Mhsw</th>
    <th class=ttl width=40>Jml Hadir</th>
    <th class=ttl width=40>Isi NILAI</th>
    <th class=ttl width=40>Status</th>
	<th class=ttl width=40>Cetak</th>
    </tr>";
  $kanan = "<img src='img/kanan.gif' />";
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    if ($dsn != $w['DosenID']) {
      $dsn = $w['DosenID'];
      echo "<tr style='background:#8c798c;color:white'><td class=ul colspan=15><b>$w[DSN]</b></td></tr>";
    }
    $c = ($w['Final'] == 'Y')? 'class=nac' : 'class=ul';
    $TagTambahan = ($w['Tambahan'] == 'Y')? "<b>( $w[_NamaJenisJadwal] )</b>" : "";
	$gos2 = ($w['Tambahan'] == 'Y')? "Nilai2" : "Nilai2";
	echo "<tr>
      <td class=inp width=25>$n</td>
      <td $c>$w[MKKode]</td>
      <td $c>$w[Nama] $TagTambahan $w[SKS]</td>
      <td $c>$w[SKS]</td>
      <td $c>$w[namaKelas] ($w[ProgramID] - $w[ProdiID]) </td>
      <td $c>$w[HR]</td>
      <td $c align=center>$w[_JM] - $w[_JS]</td>
      <td $c align=center>$w[JumlahMhsw]<sup>&#2000;</sup></td>
      <td $c align=center>$w[Kehadiran]<sup>&times;</sup></td>
      <td $c align=center><input class='btn-success' type=button name='Isi' value='Nilai' 
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=$gos2&_nilaiJadwalID=$w[JadwalID]'\" /></td>
      <td $c align=center>$w[STT]</td>
	    <td $c nowrap>
        <a href='#$w[JadwalID]' onClick=\"javascript:CetakNilai($w[JadwalID])\" >Nilai</a> |
        <a href='#$w[JadwalID]' onClick=\"javascript:CetakNilaiDetail($w[JadwalID])\" >Detail</a>
        </td>
      </tr>";
  }
  echo "</table>
  </div>
</div>
</div>
</p>";
  echo <<<SCR
  <script>
  <!--
  function CetakKosong(id) {
    lnk = "$_SESSION[ndelox].kosong.php?JadwalID="+id;
    win2 = window.open(lnk, "", "width=600, height=400, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function CetakNilai(id) {
      lnk = "$_SESSION[ndelox].pdf.php?JadwalID="+id;
      win2 = window.open(lnk, "", "width=600, height=400, scrollbars, status");
      if (win2.opener == null) childWindow.opener = self;
  }
  function CetakNilaiDetail(id) {
      lnk = "$_SESSION[ndelox].detail.php?JadwalID="+id;
      win2 = window.open(lnk, "", "width=600, height=400, scrollbars, status");
      if (win2.opener == null) childWindow.opener = self;
  }
  //-->
  </script>
SCR;
}
function Nilai2() {
	global $koneksi;
  if (!empty($_SESSION['_nilaiJadwalID'])) {
    $jdwl = AmbilFieldx("jadwal j
    left outer join dosen d on d.Login = j.DosenID and d.KodeID = '".KodeID."'
    left outer join prodi prd on prd.ProdiID = j.ProdiID and prd.KodeID = '".KodeID."'
    left outer join hari hr on j.HariID = hr.HariID
    left outer join hari hruas on hruas.HariID = date_format(j.UASTanggal, '%w')
	left outer join jenisjadwal jj on jj.JenisJadwalID = j.JenisJadwalID
	left outer join jadwaluas ju on ju.JadwalID = j.JadwalID
	left outer join hari huas on huas.HariID = date_format(ju.Tanggal, '%w') 
	LEFT OUTER JOIN kelas k ON k.KelasID = j.NamaKelas
    ", 
    "j.JadwalID", $_SESSION['_nilaiJadwalID'],
    "j.*, concat(d.Nama, ' <sup style=color:purple>', d.Gelar, '</sup>') as DSN,
    prd.Nama as _PRD, hr.Nama as _HR, huas.Nama as _HRUAS,
    LEFT(j.JamMulai, 5) as _JM, LEFT(j.JamSelesai, 5) as _JS,
    LEFT(ju.JamMulai, 5) as _JMUAS, LEFT(ju.JamSelesai, 5) as _JSUAS,
    date_format(ju.Tanggal, '%d-%m-%Y') as _UASTanggal,
	jj.Nama as _NamaJenisJadwal, jj.Tambahan, k.Nama AS namaKelas
    ");
    //CekHakAksesJadwal($_SESSION['_nilaiJadwalID']);
    TampilkanTabNilai();
    TampilkanHeaderMK($jdwl);
    TampilkanPenilaian($jdwl);
  }
}
function TampilkanTabNilai() {
  global $arrNilai, $koneksi;;
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:50%' align='center'>";
  echo "<tr >";
  foreach ($arrNilai as $a) {
    $isi = explode('~', $a);
    $c = ($_SESSION['tabNilai'] == $isi[1])? 'class=menuaktif' : 'class=menuitem';
    echo "<td $c id='tab_$isi[1]'>
      <a href='?ndelox=$_SESSION[ndelox]&tabNilai=$isi[1]&lungo=$isi[2]'>$isi[0]</a>
      </td>";
  }
  echo "<td class=menuitem>
    <input class='btn btn-success btn-sm' type=button name='Kembali' value='Kembali' onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" /></td>";
  echo "</tr>";
  echo "</table>
  </div>
</div>
</div>";
}
function tampilkanHeaderMK($jdwl) {
  if ($jdwl['Final'] == 'Y') {
    $logo = "<font size=+1>&#9762;</font>";
    if ($jdwl['Gagal'] == 'Y')
      $FINAL = "<tr><th class=wrn colspan=4>$logo Mata Kuliah sudah digagalkan. Data penilaian sudah tidak dapat diubah $logo</th></tr>
        <tr><th class=ul colspan=4>Ket: $jdwl[CatatanGagal]</th></tr>";
    else
      $FINAL = "<tr><th class=wrn colspan=4>$logo Mata Kuliah sudah di-Finalisasi. Data penilaian sudah tidak dapat diubah $logo</th></tr>";
  }
  else $FINAL = '';
  
  $param = AmbilFieldx("jadwal","JadwalID",$_SESSION['_nilaiJadwalID'],"*");
  $tglAkhir = AmbilOneField("tahun", "TahunID = '$param[TahunID]' and ProdiID = '$param[ProdiID]' and ProgramID", $param['ProgramID'],"TglNilai");
  
  $now = date('Y-m-d');
  
  if ($now >= $tglAkhir){
  	$TIMEOUT = "<tr><th class=wrn colspan=4>$logo Batas akhir penilaian sudah lewat. Data penilaian sudah tidak dapat diubah  $logo</th></tr>";
	$_SESSION['_timeout'] = true;
  } else {
  	$TIMEOUT = "";
	$_SESSION['_timeout'] = false;
  }
  
  $TagTambahan = ($jdwl['Tambahan'] == 'Y')? "<b>( $jdwl[_NamaJenisJadwal] )</b>" : "";
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:70%' align='center'>
  <tr><th class=inp width=130>Tahun Akademik</th>
      <th class=ul>: $jdwl[TahunID]</th>
      <th class=inp width=130>Program / Prodi</th>
      <th class=ul>: $jdwl[ProgramID] - $jdwl[_PRD]</th>
      </tr>
  <tr><th class=inp>Matakuliah</th>
      <th class=ul>: $jdwl[Nama] $TagTambahan</th>
      <th class=inp>Dosen:</th>
      <th class=ul>: $jdwl[DSN]</th>
      </tr>
  <tr><th class=inp>SKS</th>
      <th class=ul>: $jdwl[SKS], Peserta: $jdwl[JumlahMhsw] <sup title='Jumlah Mahasiswa'>&#2000;</sup></th>
      <th class=inp>Kelas</th>
      <th class=ul>: $jdwl[namaKelas] <sup>$jdwl[ProgramID]</sup></th>
      </tr>
  <tr><th class=inp>Jadwal Kuliah:</th>
      <th class=ul>: $jdwl[_HR], $jdwl[_JM] - $jdwl[_JS] WIB, Presensi: $jdwl[Kehadiran] &times;</th>
      <th class=inp>Jadwal Ujian</th>
      <th class=ul>: $jdwl[_UASTanggal], $jdwl[_HRUAS] WIB, $jdwl[_JMUAS] - $jdwl[_JSUAS]</th>
  $FINAL.$TIMEOUT
  </table></div></div></div>";
}
function TampilkanPenilaian($jdwl) {
  if (!empty($_SESSION['tabNilai']))
    $_SESSION['tabNilai']($jdwl);
}
function CheckPersentaseScript() {
  echo <<<SCR
  <script>
  <!--
  function HitungBobot(frm) {
    var tm = parseFloat(frm.TugasMandiri.value);
    if (tm == 0) {
      var tot = parseFloat(frm.Tugas1.value) +
        parseFloat(frm.Tugas2.value) +
        parseFloat(frm.Tugas3.value) +
        parseFloat(frm.Tugas4.value) +
        parseFloat(frm.Tugas5.value) +
        parseFloat(frm.Presensi.value) +
        parseFloat(frm.UTS.value) +
        parseFloat(frm.UAS.value);
    }
    else {
      var tot = parseFloat(frm.TugasMandiri.value) +
        parseFloat(frm.Presensi.value) +
        parseFloat(frm.UTS.value) +
        parseFloat(frm.UAS.value);
    }
    frm.TOT.value = tot;
  }
  function CheckBobot(frm) {
    var tm = parseFloat(frm.TugasMandiri.value);
    if (tm == 0) {
      var tot = parseFloat(frm.Tugas1.value) +
        parseFloat(frm.Tugas2.value) +
        parseFloat(frm.Tugas3.value) +
        parseFloat(frm.Tugas4.value) +
        parseFloat(frm.Tugas5.value) +
        parseFloat(frm.Presensi.value) +
        parseFloat(frm.UTS.value) +
        parseFloat(frm.UAS.value);
    }
    else {
      var tot = parseFloat(frm.TugasMandiri.value) +
        parseFloat(frm.Presensi.value) +
        parseFloat(frm.UTS.value) +
        parseFloat(frm.UAS.value);
    }
    if (tot != 100) alert('Tidak dapat disimpan karena jumlah bobot tidak 100%');
    return tot == 100;
  }
  //-->  </script>
SCR;
}
function Bobot($jdwl) {
  $ro = ($jdwl['Final'] == 'Y' || $_SESSION['_timeout'] == true)? "readonly=true disabled=true" : '';
  CheckPersentaseScript();

  if($jdwl['Presensi'] == 0.00) $jdwl['Presensi']=10.00;
  if($jdwl['Tugas1'] == 0.00) $jdwl['Tugas1']=10.00;
  if($jdwl['Tugas4'] == 0.00) $jdwl['Tugas4']=15.00;
  if($jdwl['UTS'] == 0.00) $jdwl['UTS']=25.00;
  if($jdwl['UAS'] == 0.00) $jdwl['UAS']=40.00;

  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:100%' align='center'>
  <form name='bobot' action='?' method=POST $ro onSubmit='return CheckBobot(this)'>
  <input type=hidden name='lungo' value='BobotSimpan' />
  <input type=hidden name='_nilaiJadwalID' value='$jdwl[JadwalID]' />
  <input type=hidden name='BypassMenu' value='1' />
  <tr><th class=ttl colspan=2>BOBOT PENILAIAN</th></tr>
  <tr style='background:purple;color:white'><th class=ttl colspan=2>ABSENSI</th></tr>
  <tr><td class=inp width=280>Absensi</td>
      <td class=ul>
      <input type=text name='Presensi' value='$jdwl[Presensi]' size=6 maxlength=6 onChange='HitungBobot(bobot)' $ro /> %</td>
      </tr>
      <tr style='background:purple;color:white'><th class=ttl colspan=2>TUGAS</th></tr>    
  <tr><td class=inp>Tugas Mandiri</td>
      <td class=ul>
        <input type=text name='TugasMandiri' value='$jdwl[TugasMandiri]' size=6 maxlength=6 onChange='HitungBobot(bobot)' $ro /> %
        *) Terdiri dari tugas 1-3, 
        Isikan di sini jika pembagian % setiap tugas dilakukan secara otomatis.
        </td></tr>
  <tr><td class=inp>Tugas 1</td>
      <td class=ul>
      &nbsp;&nbsp;&nbsp;&nbsp;<input type=text name='Tugas1' value='$jdwl[Tugas1]' size=6 maxlength=6 onChange='HitungBobot(bobot)' $ro /> %</td>
        </tr>
  <tr><td class=inp>Tugas 2</td>
      <td class=ul>
      &nbsp;&nbsp;&nbsp;&nbsp;<input type=text name='Tugas2' value='$jdwl[Tugas2]' size=6 maxlength=6 onChange='HitungBobot(bobot)' $ro /> %</td>
        </tr>
  <tr><td class=inp>Tugas 3</td>
      <td class=ul>
      &nbsp;&nbsp;&nbsp;&nbsp;<input type=text name='Tugas3' value='$jdwl[Tugas3]' size=6 maxlength=6 onChange='HitungBobot(bobot)' $ro /> %</td>
        </tr>
  <tr style='background:purple;color:white'><th class=ttl colspan=2>PRESENTASI DAN LAB</th></tr>        
  <tr><td class=inp>Presentasi</td>
      <td class=ul>
        <input type=text name='Tugas4' value='$jdwl[Tugas4]' size=6 maxlength=6 onChange='HitungBobot(bobot)' $ro /> %</td>
        </tr>
  <tr><td class=inp>Lab</td>
      <td class=ul>
        <input type=text name='Tugas5' value='$jdwl[Tugas5]' size=6 maxlength=6 onChange='HitungBobot(bobot)' $ro /> %</td>
        </tr>
        <tr style='background:purple;color:white'><th class=ttl colspan=2>UTS DAN UAS</th></tr>        
  <tr><td class=inp>Ujian Tengah Semester:</td>
      <td class=ul><input type=text name='UTS' value='$jdwl[UTS]' size=6 maxlength=6 onChange='HitungBobot(bobot)' $ro /> %</td>
      </tr>
  <tr><td class=inp>Ujian Akhir Semester:</td>
      <td class=ul><input type=text name='UAS' value='$jdwl[UAS]' size=6 maxlength=6 onChange='HitungBobot(bobot)' $ro /> %</td>
      </tr>
  <tr><td bgcolor=silver colspan=2 height=1></td></tr>
  <tr><td class=inp>TOTAL</td>
      <td class=ul><input type=text name='TOT' value='$TOT' size=6 maxlength=6 readonly=true /> %</td></tr>
  <tr><td class=ul colspan=2 align=left>
      <input class='btn btn-success btn-sm' type=submit name='Simpan' value='Simpan Perubahan' $ro />
      </td></tr>
  </form>
  </table>

  <script>HitungBobot(bobot)</script>";
}
function BobotSimpan() {
	global $koneksi;
  $jid = $_REQUEST['_nilaiJadwalID']+0;
  $Presensi = $_REQUEST['Presensi']+0;
  $TugasMandiri = $_REQUEST['TugasMandiri']+0;
  $Tugas1 = $_REQUEST['Tugas1']+0;
  $Tugas2 = $_REQUEST['Tugas2']+0;
  $Tugas3 = $_REQUEST['Tugas3']+0;
  $Tugas4 = $_REQUEST['Tugas4']+0;
  $Tugas5 = $_REQUEST['Tugas5']+0;
  $UTS = $_REQUEST['UTS']+0;
  $UAS = $_REQUEST['UAS']+0;
  // Simpan
  $s = "update jadwal
    set Presensi = '$Presensi', TugasMandiri = '$TugasMandiri',
        Tugas1 = '$Tugas1', Tugas2 = '$Tugas2', Tugas3 = '$Tugas3', 
        Tugas4 = '$Tugas4', Tugas5 = '$Tugas5',
        UTS = '$UTS', UAS = '$UAS',
        LoginEdit = '$_SESSION[_Login]', TglEdit = now()
    where JadwalID = '$jid' ";
  $r = mysqli_query($koneksi, $s);
  // Kembali
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=Nilai2&_nilaiJadwalID=$jid", 100);
}
function NilaiMhsw($jdwl) {
	global $koneksi;
  $s = "select k.*, m.Nama as NamaMhsw
    from krs k
      left outer join mhsw m on k.MhswID = m.MhswID and m.KodeID = '".KodeID."'
    where k.JadwalID = '$jdwl[JadwalID]'
      and k.NA = 'N'
    order by k.MhswID";
  $r = mysqli_query($koneksi, $s); $n = 0;
  echo "<table id='example' class='table table-sm table-striped' style='width:100%' align='center'>";

  if ($jdwl['Final'] == 'Y' || $_SESSION['_timeout'] == true) {
    $frm = '';
    $ro = 'readonly=TRUE disabled=TRUE';
    $btnSimpan = '';
    $btnHitungUlang = '';
    $btnFinal = '';
    $btnGagal = '';
  }
  else {
    $frm = "<form action='?' method=POST>";
    $ro = '';
    $btnSimpan = "<input class='btn btn-success btn-sm' type=submit name='SimpanSemua' value='Simpan Semua' />";
    $btnHitungUlang = "<input class='btn btn-primary btn-sm' type=button name='Hitung' value='Hitung Penilaian' onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=HitungNilai&BypassMenu=1&_nilaiJadwalID=$jdwl[JadwalID]'\" />";
    $btnFinal = "<input class='btn btn-danger btn-sm' type=button name='Finalisasi' value='Finalisasi' onClick=\"javascript:Finalisasikan($jdwl[JadwalID])\" />";
    //$btnGagal = "<input type=button name='Gagal' value='Gagal Penilaian' onClick=\"javascript:Gagalkan($jdwl[JadwalID])\" />";
    // Javascript
    echo <<<SCR
    <script>
    <!--
    function Finalisasikan(id) {
      lnk = "$_SESSION[ndelox].final.php?id="+id;
      win2 = window.open(lnk, "", "width=400, height=400, scrollbars, status");
    }
    function Gagalkan(id) {
      lnk = "$_SESSION[ndelox].gagal.php?id="+id;
      win2 = window.open(lnk, "", "width=400, height=440, scrollbars, status");
    }
    //-->
    </script>
SCR;
  }
  echo "$frm
    <input type=hidden name='lungo' value='NilaiMhswSimpan' />
    <input type=hidden name='BypassMenu' value=1 />
    <input type=hidden name='_nilaiJadwalID' value='$jdwl[JadwalID]' />";
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>
  <tr>
    <td class=ul colspan=15 align='center'>
    $btnSimpan
    <input class='btn btn-warning btn-sm' type=button name='Refresh' value='Refresh' onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=Nilai2&_nilaiJadwalID=$jdwl[JadwalID]'\" />
    $btnHitungUlang
    $btnFinal
    $btnGagal
    </td></tr></table>";
    
    
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>
  <tr style='background:purple;color:white'>
	<th class=ttl rowspan=2 style='vertical-align:middle;text-align:center;'>No.</th>
    <th class=ttl rowspan=2 style='vertical-align:middle;text-align:center;'>NIM</th>
    <th class=ttl rowspan=2 style='vertical-align:middle;'>Mahasiswa</th>
    <th class=ttl rowspan=2 title='Presensi Mahasiswa' style='vertical-align:middle;'>&sum;<br />PRS</th>
    <th class=ttl rowspan=2 title='Nilai Presensi Mhsw' style='vertical-align:middle;'>PRS<br />$jdwl[Presensi]%</th>
    <th class=ttl colspan=5 style='vertical-align:middle;;text-align:center;' >Tugas Mandiri &#9889; $jdwl[TugasMandiri]%</th>

    <th class=ttl rowspan=2 style='vertical-align:middle;text-align:center;'>UTS<br />$jdwl[UTS]%</th>
    <th class=ttl rowspan=2 style='vertical-align:middle;text-align:center;'>UAS<br />$jdwl[UAS]%</th>
    <th class=ttl rowspan=2 style='vertical-align:middle;text-align:center;'>Nilai<br />Ahir</th>
    <th class=ttl rowspan=2 style='vertical-align:middle;text-align:center;'>Grade<br />&#9889;</th>
    </tr>
    <tr style='background:purple;color:white'>
    <th class=ttl style='vertical-align:middle;;text-align:center;'>Tgs 1<br />$jdwl[Tugas1]%</th>
    <th class=ttl style='vertical-align:middle;;text-align:center;'>Tgs 2<br />$jdwl[Tugas2]%</th>
    <th class=ttl style='vertical-align:middle;;text-align:center;'>Tgs 3<br />$jdwl[Tugas3]%</th>
    <th class=ttl style='vertical-align:middle;;text-align:center;'>P'tasi<br />$jdwl[Tugas4]%</th>
    <th class=ttl style='vertical-align:middle;;text-align:center;'>Lab<br />$jdwl[Tugas5]%</th>
    </tr>";
  $wd = "width=30"; $nomer = 0;
  $jml = mysqli_num_rows($r);
  while ($w = mysqli_fetch_array($r)) {
    $nomer++;
    $_pr = $nomer;
    $_t1 = $nomer + $jml;
    $_t2 = $nomer + $jml *2;
    $_t3 = $nomer + $jml *3;
    $_t4 = $nomer + $jml *4;
    $_t5 = $nomer + $jml *5;
    $_ut = $nomer + $jml *6;
    $_ua = $nomer + $jml *7;
    $n = $w['KRSID'];

	$countPresensi = AmbilOneField('presensi', 'JadwalID', $w['JadwalID'], 'count(PresensiID)');
	$Presensi = ($countPresensi == 0)? 0 : number_format($w['_Presensi']/$countPresensi*100, 0);
    echo "<tr>
      <input type=hidden name='krsid[]' value='$w[KRSID]' />
      <input type=hidden name='KRS_$n' value='$w[KRSID]' />
	  <td class=ul align=center>$nomer</td>
      <td class=inp width=120 align=center>$w[MhswID]</td>
      <td class=ul>$w[NamaMhsw]</td>
      <td class=ul align=right>$w[_Presensi]<sup>&times;</sup></td>
      <td class=ul $wd><input type=text name='Presensi_$n' value='$Presensi' size=3 maxlength=4 tabindex=$_pr readonly=true /></td>
      <td class=ul $wd><input type=text name='Tugas1_$n' value='$w[Tugas1]' size=3 maxlength=4 tabindex=$_t1 $ro /></td>
      <td class=ul $wd><input type=text name='Tugas2_$n' value='$w[Tugas2]' size=3 maxlength=4 tabindex=$_t2 $ro /></td>
      <td class=ul $wd><input type=text name='Tugas3_$n' value='$w[Tugas3]' size=3 maxlength=4 tabindex=$_t3 $ro /></td>
      <td class=ul $wd><input type=text name='Tugas4_$n' value='$w[Tugas4]' size=3 maxlength=4 tabindex=$_t4 $ro /></td>
      <td class=ul $wd><input type=text name='Tugas5_$n' value='$w[Tugas5]' size=3 maxlength=4 tabindex=$_t5 $ro /></td>
      <td class=ul $wd><input type=text name='UTS_$n' value='$w[UTS]' size=3 maxlength=4 tabindex=$_ut $ro /></td>
      <td class=ul $wd><input type=text name='UAS_$n' value='$w[UAS]' size=3 maxlength=4 tabindex=$_ua $ro /></td>
      <td class=ul align=center><b>$w[NilaiAkhir]</b></td>
      <td class=ul align=center><b>$w[GradeNilai] ($w[BobotNilai])</b></td>
      </tr>";
  }
  echo "<input type=hidden name='JumlahMhsw' value='$jml' />";
  echo "</form></table>
  </div>
</div>
</div>";
}
function NilaiMhswSimpan() {
	global $koneksi;
  $_nilaiJadwalID = $_REQUEST['_nilaiJadwalID'];
  $krsid = array();
  $krsid = $_REQUEST['krsid'];
  foreach ($krsid as $id) {
    $Presensi = $_REQUEST['Presensi_'.$id]+0;
    $Tugas1 = $_REQUEST['Tugas1_'.$id]+0;
    $Tugas2 = $_REQUEST['Tugas2_'.$id]+0;
    $Tugas3 = $_REQUEST['Tugas3_'.$id]+0;
    $Tugas4 = $_REQUEST['Tugas4_'.$id]+0;
    $Tugas5 = $_REQUEST['Tugas5_'.$id]+0;
    $UTS = $_REQUEST['UTS_'.$id]+0;
    $UAS = $_REQUEST['UAS_'.$id]+0;
    // Simpan
    $s = "update krs
      set Presensi = '$Presensi',
          Tugas1 = '$Tugas1', Tugas2 = '$Tugas2', Tugas3 = '$Tugas3',
          Tugas4 = '$Tugas4', Tugas5 = '$Tugas5',
          UTS = '$UTS', UAS = '$UAS',
          TanggalEdit = now(), LoginEdit = '$_SESSION[_Login]'
      where KRSID = $id ";
    $r = mysqli_query($koneksi, $s);
    //echo "<pre>$s</pre>";
  }
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=Nilai2&_nilaiJadwalID=$_nilaiJadwalID", 1);
}
function HitungNilai() {
	global $koneksi;
//function HitungNilai1($jadwalid, $jdwl) {
  $jadwalid = $_REQUEST['_nilaiJadwalID'];
  $jdwl = AmbilFieldx('jadwal', 'JadwalID', $jadwalid, '*');
  // lihat persentase Tugas Mandiri
  if ($jdwl['TugasMandiri'] > 0) {
    // Ambil jumlah tugas2 utk distribusi nilai tugas
    $TGS = AmbilFieldx('krs', 'JadwalID', $jadwalid,
      "sum(Tugas1) as T1, sum(Tugas2) as T2, sum(Tugas3) as T3, sum(Tugas4) as T4, sum(Tugas5) as T5");
    $_T1 = ($TGS['T1'] > 0)? 1 : 0;
    $_T2 = ($TGS['T2'] > 0)? 1 : 0;
    $_T3 = ($TGS['T3'] > 0)? 1 : 0;
    $_T4 = ($TGS['T4'] > 0)? 1 : 0;
    $_T5 = ($TGS['T5'] > 0)? 1 : 0;
    $JumlahTugas = $_T1 + $_T2 + $_T3 + $_T4 + $_T5;
    // Distribusikan persentase tugas
    $PersenTugas = $jdwl['TugasMandiri'] / $JumlahTugas;
    $SisaTugas = $jdwl['TugasMandiri'] % $JumlahTugas;
    $_fld = array();
    for ($i = 1; $i <= 5; $i++) {
      $fld = "_T$i";
      $_PersenTugas = ($$fld == 1)? $PersenTugas : 0;
      $jdwl["Tugas$i"] = $_PersenTugas;
      //$persen = ($i == 1)? $PersenTugas + $SisaTugas : $PersenTugas;
      $_fld[] = "Tugas$i=$_PersenTugas";
    }
    $fld = implode(', ', $_fld);
    $s0 = "update jadwal set $fld where JadwalID=$jadwalid";
    $r0 = mysqli_query($koneksi, $s0);
  }
  // Proses
  $s = "select * from krs where JadwalID=$jadwalid and NA='N' order by MhswID";
  $r = mysqli_query($koneksi, $s);
  while ($w = mysqli_fetch_array($r)) {
    $nilai = ($w['Tugas1'] * $jdwl['Tugas1']) +
      ($w['Tugas2'] * $jdwl['Tugas2']) +
      ($w['Tugas3'] * $jdwl['Tugas3']) +
      ($w['Tugas4'] * $jdwl['Tugas4']) +
      ($w['Tugas5'] * $jdwl['Tugas5']) +
      ($w['Presensi'] * $jdwl['Presensi']) +
      ($w['UTS'] * $jdwl['UTS']) +
      ($w['UAS'] * $jdwl['UAS'])
      ;
    $nilai = ($nilai / 100) +0;
    if ($jdwl['Responsi'] > 0) {
      $nilai = ($nilai * (100 - $jdwl['Responsi'])/100) +
        ($w['Responsi'] * ($jdwl['Responsi'])/100);
    }
    $ProdiID = AmbilOneField('mhsw', "MhswID", $w['MhswID'], "ProdiID");
    $arrgrade = AmbilFieldx('nilai', 
      "KodeID='$_SESSION[KodeID]' and NilaiMin <= $nilai and $nilai <= NilaiMax and ProdiID",
      $ProdiID, "Nama, Bobot");
    // Simpan
    $s1 = "update krs set NilaiAkhir='$nilai', GradeNilai='$arrgrade[Nama]', BobotNilai='$arrgrade[Bobot]'
      where KRSID=$w[KRSID] ";
    $r1 = mysqli_query($koneksi, $s1);
  }
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=Nilai2&_nilaiJadwalID=$jadwalid", 100);
}
?>
