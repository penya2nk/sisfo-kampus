<?php
error_reporting(0);
$TahunID = GainVariabelx('TahunID');
$ProdiID = GainVariabelx('ProdiID');
$ProgramID = GainVariabelx('ProgramID');
$HariID = GainVariabelx('HariID');

TitleApps("ABSENSI PERKULIAHAN");
$lungo = (empty($_REQUEST['lungo']))? 'ListJadwal' : $_REQUEST['lungo'];
$lungo();

function ViewHeaderAbsensi() {
	global $koneksi;
  //$optprodi = AmbilCombo2('prodi', "concat(ProdiID, ' - ', Nama)", 'ProdiID', $_SESSION['ProdiID'], "KodeID='".KodeID."'", 'ProdiID');

  $s = "select DISTINCT(TahunID) from tahun where KodeID='".KodeID."' order by TahunID DESC";
  $r = mysqli_query($koneksi, $s);
  $opttahun = "<option value=''></option>";
  while($w = mysqli_fetch_array($r)) {  
	  $ck = ($w['TahunID'] == $_SESSION['TahunID'])? "selected" : '';
      $opttahun .=  "<option value='$w[TahunID]' $ck>$w[TahunID]</option>";
  }

  $optprodi = ($_SESSION['_LevelID'] == 100)? 
     AmbilCombo3("prodi", "ProdiID", "concat(ProdiID, ' - ', Nama) as NM", "NM",  $w['ProdiID'], '.') : 
	 AmbilPenggunaProdi($_SESSION['_Login'], $_SESSION['ProdiID']);
  $optprg = AmbilCombo2('program', "concat(Nama, ' - ', ProgramID)", 'ProgramID', $_SESSION['ProgramID'], "KodeID='".KodeID."'", 'ProgramID');
  $opthari = AmbilCombo2('hari', 'Nama', 'HariID', $_SESSION['HariID'], '', 'HariID');
  $buttons = ($_SESSION['_LevelID'] == 100)? "" : 
	 "<input class='btn btn-success btn-sm' type=button name='CetakRekap' value='PRINT REKAP' onClick='javascript:CetakRekap()' />
      <input class='btn btn-primary btn-sm' type=button name='CetakDetail' value='PRINT DETAIL ABSENSI' onClick=\"javascript:CetakDetail()\" />
      <input class='btn btn-danger btn-sm' type=button name='CetakPresMhsw' value='PRINT ABSENSI MHS' onClick=\"javascript:CetakDetailMhsw()\" />"; 
  
  
  echo "<div class='card'>
  <div class='card-header'>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='lungo' value='' />
  <div align='center'>
    TAHUN AK <select style='height:30px' name='TahunID'/>$opttahun</select>
    PRODI <select style='height:30px' name='ProdiID'>$optprodi</select>
  
    PROGRAM <select style='height:30px' name='ProgramID'>$optprg</select>
    HARI <select style='height:30px' name='HariID'>$opthari</select>
    <input class='btn btn-success btn-sm' type=submit name='Tampilkan' value='Lihat Jadwal' />
    </div>  
    </form>
    </div>
    </div>

    <div class='card'>
    <div class='card-header'>   
      <div align='center'>
      $buttons
      </div>
    </div>
    </div>";

echo <<<SCR
  <script>
  <!--
  function CetakRekap() {
    lnk = "$_SESSION[ndelox].rekap.php";
    win2 = window.open(lnk, "", "width=600, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function CetakDetail() {
    lnk = "$_SESSION[ndelox].detail.php";
    win2 = window.open(lnk, "", "width=600, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function CetakDetailMhsw() {
    lnk = "$_SESSION[ndelox].mhsw.php";
    win2 = window.open(lnk, "", "width=600, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  //-->
  </script>
SCR;
}
function ListJadwal() {
  ViewHeaderAbsensi();
  if (empty($_SESSION['TahunID']) || empty($_SESSION['ProdiID']))
    echo Info("INPUTKAN TAHUN AKADEMIK DAN PROGRAM STUDI UNTUK MELIHAT JADWAL",
      "Filter Hari dan Program Pendidikan Untuk Menampilkan data lebih spesifik");
  else ListJadwal1();
}
function ListJadwal1() {
  global $koneksi;
  $whr_hari = ($_SESSION['HariID'] == '')? '' : "and j.HariID = '$_SESSION[HariID]' ";
  $whr_prg  = ($_SESSION['ProgramID'] == '')? '' : "and j.ProgramID = '$_SESSION[ProgramID]' ";
  $whr_dosen = ($_SESSION['_LevelID'] == 100) ? " and j.DosenID = '$_SESSION[_Login]' " : "";

    $s = "select j.*,
      left(j.JamMulai, 5) as _JM, left(j.JamSelesai, 5) as _JS,
      concat(d.Nama, ' <sup>', d.Gelar, '</sup>') as DSN,
      jj.Nama as _NamaJenisJadwal, jj.Tambahan,
	  mk.TugasAkhir, mk.PraktekKerja, k.Nama AS namaKelas
    from jadwal j
      left outer join dosen d on d.Login = j.DosenID and d.KodeID = '".KodeID."'
	  left outer join jenisjadwal jj on jj.JenisJadwalID = j.JenisJadwalID
	  left outer join mk mk on mk.MKID=j.MKID and mk.KodeID='".KodeID."' 
	  LEFT OUTER JOIN kelas k ON k.KelasID = j.NamaKelas
    where j.TahunID = '$_SESSION[TahunID]'
      and j.ProdiID = '$_SESSION[ProdiID]'
      and j.KodeID = '".KodeID."'
	  $whr_hari
      $whr_prg
	  $whr_dosen
      and j.NA = 'N'
    order by j.HariID , j.JamMulai, j.JamSelesai";

  $r = mysqli_query($koneksi, $s);
  $n = 0; $_hr = 'lasdjfalsjh';
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>";
  $PrintDaftar = ($_SESSION['_LevelID'] == 100)? '' : 
	"<th class=ttl title='List Absensi Dosen'>LAD</th>
	<th class=ttl title='List Absensi Mahasiswa'>LAM</th>";
  $hdr = "<tr style='background:purple;color:white'>
    <th class=ttl>#</th>
    <th class=ttl>Jam</th>
    <th class=ttl>Kode MK</th>
    <th class=ttl>Mata Kuliah</th>
    <th class=ttl >SKS</th>
    <th class=ttl>Kelas</th>
    <th class=ttl>Dosen</th>
    <th class=ttl>Mhsw</th>
	$PrintDaftar
    <th class=ttl colspan=2>Presensi</th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
    if ($_hr != $w['HariID']) {
      $_hr = $w['HariID'];
      $hari = AmbilOneField('hari', 'HariID', $_hr, 'Nama');
      echo "<tr><td class=ul colspan=12><font size=+1>$hari</font></td></tr>";
      echo $hdr;
    }
    $n++;
    if ($w['Final'] == 'Y') {
      $c = 'class=nac';
      $edt = "<img src='img/final.png' width=25 title='Sudah difinalisasi. Tidak dapat diubah.' />";
    }
    else {
      $c = 'class=ul';
      $edt = "<a href='#' onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=Edit&JadwalID=$w[JadwalID]'\"><i title='Absensi Mhs' class='fa fa-check-double'></i></a>";
    }
	$PrintDaftar2 = ($_SESSION['_LevelID'] == 100)? '' : 
	    "<td $c align=center>
        <a href='#' onClick='javascript:CetakDAD($w[JadwalID], $w[SKS])' title='List Absensi Dosen'><i class='fa fa-print'></i></a>
        </td>
		<td $c align=center>
        <a href='#' onClick='javascript:CetakDHK($w[JadwalID], $w[SKS])' title='List Absensi Mahasiswa'><i class='fa fa-print'></i></a>
        </td>";
    $TagTambahan = ($w['Tambahan'] == 'Y')? "<b>( $w[_NamaJenisJadwal] )</b>" : "";
    echo "<tr>
      <td class=inp width=15>$n</td>
      <td $c>$w[_JM] - $w[_JS]</td>
      <td $c>$w[MKKode] <sup>$w[Sesi]</sup></td>
      <td $c>$w[Nama] $TagTambahan</td>
      <td $c align=center>$w[SKS]</td>
      <td $c>$w[namaKelas] <sup>$w[ProgramID]</sup></td>
      <td $c>$w[DSN]</td>
      <td $c align=right>$w[JumlahMhsw] <sup>&#2000;</sup></td>
	  $PrintDaftar2
      <td class=ul1 align=right>$w[Kehadiran]<sub>&times;</sub></td>
      <td class=ul align=center>
        $edt
        </td>
      </tr>";
  }
  echo <<<ESD
  </table>
  </div>
</div>
</div>
  <p></p>
  
  <script>
  function CetakDHK(JadwalID, SKS) {
    lnk = "$_SESSION[ndelox].dhk.php?JadwalID="+JadwalID+"&SKS="+SKS;
    win2 = window.open(lnk, "", "width=800, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function CetakDAD(JadwalID, SKS) {
    lnk = "$_SESSION[ndelox].dad.php?JadwalID="+JadwalID+"&SKS="+SKS;
    win2 = window.open(lnk, "", "width=800, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
ESD;
}
function Edit() {
	global $koneksi;
  $JadwalID = GainVariabelx('JadwalID');
  $jdwl = AmbilFieldx("jadwal j
    left outer join dosen d on d.Login = j.DosenID and d.KodeID = '".KodeID."'
    left outer join prodi prd on prd.ProdiID = j.ProdiID and prd.KodeID = '".KodeID."'
    left outer join hari hr on j.HariID = hr.HariID
    left outer join hari hruas on hruas.HariID = date_format(j.UASTanggal, '%w')
    left outer join jenisjadwal jj on jj.JenisJadwalID = j.JenisJadwalID 
	LEFT OUTER JOIN kelas k ON k.KelasID = j.NamaKelas
	", 
    "j.JadwalID", $JadwalID,
    "j.*, concat(d.Nama, ' <sup>', d.Gelar, '</sup>') as DSN,
    prd.Nama as _PRD, hr.Nama as _HR, hruas.Nama as _HRUAS,
    LEFT(j.JamMulai, 5) as _JM, LEFT(j.JamSelesai, 5) as _JS,
    LEFT(j.UASJamMulai, 5) as _JMUAS, LEFT(j.UASJamSelesai, 5) as _JSUAS,
    date_format(j.UASTanggal, '%d-%m-%Y') as _UASTanggal,
	jj.Nama as _NamaJenisJadwal, jj.Tambahan, k.Nama AS namaKelas
    ");
  // Cek apakah jadwal valid?
  if (empty($jdwl)) 
    die(PesanError('Error',
      "Jadwal tidak ditemukan.<br />
      Mungkin jadwal sudah dihapus.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      <input type=button name='Kembali' value='Kembali' onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" >"));
  // Cek apakah sudah di-finalisasi?
  if ($jdwl['Final'] == 'Y')
    die(PesanError('Error',
      "Jadwal sudah difinalisasi.<br />
      Anda sudah tidak dapat mengubah data ini lagi.
      <hr size=1 color=silver />
      <input type=button name='Kembali' value='Kembali' onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" >"));
  // Jika sudah valid semua, maka tampilkan menu edit yg sebenarnya
  Edits($jdwl);
}
function Edits($jdwl) {
  PresensiScript();
  ViewHeaderx($jdwl);
  ViewAbsensix($jdwl);
}
function ViewAbsensix($jdwl) {
  global $koneksi;
  if($_SESSION['_LevelID'] == 100)
	if($jdwl['DosenID'] != $_SESSION['_Login'])
	   die(PesanError("Anda tidak berhak mengakses data presensi dari Mata Kuliah: <b>$jdwl[Nama], Hari: $jdwl[_HRUAS], Jam: $jdwl[_JM] - $jdwl[_JS]</b>. 
					<br>Bila anda seharusnya berhak mengakses data ini, harap menghubungi Kepala Prodi."));
  
  $s = "select p.*,
    date_format(p.Tanggal, '%d-%m-%Y') as _Tanggal,
    date_format(p.Tanggal, '%w') as _Hari,
    d.Nama as DSN, d.Gelar,
    h.Nama as _HR,
    left(p.JamMulai, 5) as _JM, left(p.JamSelesai, 5) as _JS,
      (select sum(Nilai)
      from presensimhsw 
      where PresensiID=p.PresensiID) as JmlHadir
    from presensi p
      left outer join hari h on h.HariID = date_format(p.Tanggal, '%w')
      left outer join dosen d on d.Login = p.DosenID and d.KodeID = '".KodeID."'
    where p.JadwalID = '$jdwl[JadwalID]'
    order by p.Pertemuan";
  $r = mysqli_query($koneksi, $s);

  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>";
  echo "<tr>
    <td class=ul1 colspan=6>
    <input class='btn-success' type=button name='TambahPresensi' value='Tambah Presensi' 
      onClick=\"javascript:PrsnEdit(1, $jdwl[JadwalID], 0)\" />
    <input class='btn-warning' type=button name='Refresh' value='Refresh'
      onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=Edit&JadwalID=$jdwl[JadwalID]'\" />
    <input class='btn-primary' type=button name='Kembali' value='Kembali ke Daftar'
      onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />
    </td></tr>";
  echo "<tr style='background:purple;color:white'>
    <th class=ttl width=40 colspan=2>#</th>
    <th class=ttl width=80>Hari</th>
    <th class=ttl width=100>Tanggal</th>
    <th class=ttl width=100>Waktu</th>
    <th class=ttl width=280>Dosen Pengampu</th>
    <th class=ttl >Catatan</th>
    <th class=ttl width=100>Kehadiran</th>
    </tr>";
  
  $n = 0;
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    $Jumlah = $w['JmlHadir']+0;
    echo "<tr>
      <td class=inp width=20>$w[Pertemuan]</td>
      <td class=ul width=10 align=center><a href='#' onClick='javascript:PrsnEdit(0, $w[JadwalID], $w[PresensiID])'><i class='fa fa-edit'></i></a></td>
      <td class=ul>$w[_HR]</td>
      <td class=ul> $w[_Tanggal]</td>
      <td class=ul align=center>$w[_JM] - $w[_JS]</td>
      <td class=ul>$w[DSN], <font style='color:purple'>$w[Gelar]</font></td>
      <td class=ul>$w[Catatan]&nbsp;</td>
      <td class=ul align=right>
        $Jumlah &nbsp;
        <a href='#' onClick='javascript:PrsnMhswEdit($w[PresensiID])'><i title='Absensi Mhs' class='fa fa-check-double'></i></a>
        </td>
      
      </tr>";
  }
  
  echo "</table></div>
  </div>
  </div>
  ";
}
function ViewHeaderx($jdwl) {
  $TagTambahan = ($jdwl['Tambahan'] == 'Y')? "<b>( $jdwl[_NamaJenisJadwal] )</b>" : "";
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:60%' align='center'>
  <tr><th class=inp width=130>Tahun Akademik</th>
      <th class=ul>: $jdwl[TahunID]</th>
      <th class=inp width=130>Program Studi</th>
      <th class=ul>: $jdwl[_PRD] <sup>$jdwl[ProdiID]</sup></th>
      </tr>
  <tr><th class=inp>Matakuliah</th>
      <th class=ul>: $jdwl[Nama] $TagTambahan</th>
      <th class=inp>Dosen</th>
      <th class=ul>: $jdwl[DSN]</th>
      </tr>
  <tr><th class=inp>SKS</th>
      <th class=ul>: $jdwl[SKS], Peserta: $jdwl[JumlahMhsw] <sup title='Jumlah Mahasiswa'>&#2000;</sup></th>
      <th class=inp>Kelas</th>
      <th class=ul>: $jdwl[namaKelas] <sup>$jdwl[ProgramID]</sup></th>
      </tr>
  <tr><th class=inp>Jadwal Kuliah</th>
      <th class=ul>: $jdwl[_HR], $jdwl[_JM] - $jdwl[_JS] WIB</th>
      <th class=inp>Jadwal Ujian</th>
      <th class=ul>: $jdwl[_UASTanggal], $jdwl[_HRUAS], $jdwl[_JMUAS] - $jdwl[_JSUAS]</th>
  </table></div>
  </div>
  </div>
  ";
}

function PresensiScript() {
  echo <<<SCR
  <script>
  function PrsnEdit(md, jid, pid) {
    lnk = "$_SESSION[ndelox].edit.php?md="+md+"&jid="+jid+"&pid="+pid;
    win2 = window.open(lnk, "", "width=450,height=350,left=500,top=250,toolbar=0,status=0");
    if (win2.opener == null) childWindow.opener = self;
  }
  function PrsnMhswEdit(pid) {
    lnk = "$_SESSION[ndelox].mhswedit.php?pid="+pid;
    win2 = window.open(lnk, "", "width=500,height=800,left=500,top=50,toolbar=0,status=0");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
SCR;
}

function AmbilCombo3($table, $key, $Fields, $Label, $Nilai='', $Separator=',', $whr = '', $antar='<br />') {
	global $koneksi;
  $_whr = (empty($whr))? '' : "and $whr";
  $s = "select $key, $Fields
    from $table
    where NA='N' $_whr order by $key";
  $r = mysqli_query($koneksi, $s);
  $_arrNilai = explode($Separator, $Nilai);
  $str = '';
  while ($w = mysqli_fetch_array($r)) {
    $_ck = (array_search($w[$key], $_arrNilai) === false)? '' : 'selected';
    $str .= "<option value='$w[$key]'>$w[$Label]</option>";
	//$str .= "<input type=checkbox name='".$key."[]' value='$w[$key]' $_ck> $w[$Label]$antar";
  }
  return $str;
}
?>