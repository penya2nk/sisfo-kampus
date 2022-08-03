<?php
// error_reporting(0);
include_once "$_SESSION[ndelox].lib.php";

if($_SESSION['_LevelID'] == 120)
{   $mhsw = AmbilFieldx('mhsw', "MhswID='$_SESSION[_Login]' and KodeID", KodeID, "ProgramID, ProdiID");
    $TahunAktif = AmbilOneField('tahun', "ProgramID='$mhsw[ProgramID]' and ProdiID='$mhsw[ProdiID]' and NA='N' and KodeID", KodeID, "TahunID");
	$_SESSION['_krsTahunID'] = $TahunAktif;
	$_SESSION['_krsMhswID'] = $_SESSION['_Login'];
}
$_krsTahunID = GainVariabelx('_krsTahunID');
$_krsMhswID = GainVariabelx('_krsMhswID');
$_krsHariID = GainVariabelx('_krsHariID');

TitleApps("KARTU RENCANA STUDI");
CekBolehAksesModul();
ViewPencarianKRS();
if (!empty($_krsTahunID) && !empty($_krsMhswID)) {
  $oke = BolehAksesData($_krsMhswID);
  if ($oke) $oke = ValidasiDataMhsw($_krsTahunID, $_krsMhswID, $khs);
  if ($oke) {
    $mhsw = AmbilFieldx("mhsw m
      left outer join statusawal sta on sta.StatusAwalID = m.StatusAwalID", 
      "m.KodeID = '".KodeID."' and m.MhswID", $_krsMhswID, 
      "m.*, sta.Nama as STAWAL");
    $thn = AmbilFieldx("tahun",
      "KodeID = '".KodeID."' and ProdiID = '$khs[ProdiID]' and ProgramID = '$khs[ProgramID]' and TahunID", $_krsTahunID, "*");
    $lungo = sqling($_REQUEST['lungo']);
    if (empty($lungo)) {
      if ($khs['StatusMhswID'] == 'A') {
        TampilkanHeaderMhsw($thn, $mhsw, $khs);
        TampilkanDaftarKRSMhsw($thn, $mhsw, $khs);
      }
      else {
        $status = AmbilOneField('statusmhsw', 'StatusMhswID', $khs['StatusMhswID'], 'Nama');
        echo PesanError('Error',
          "Mahasiswa <b>$mhsw[Nama]</b> <sup>$mhsw[MhswID]</sup> tidak dapat mengambil KRS.<br />
          Berikut adalah alasannya:
          <hr size=1 color=silver />
          
          Status mahasiswa: <font size=+1>$status</font>.<br />
          Mahasiswa dengan status ini tidak dapat mengambil KRS.<br />
          Hanya mahasiswa Aktif saja yg boleh mengambil KRS.<br />
          Hubungi BAA untuk informasi status mahasiswa.");
      }
    }
    else $lungo();
  }
}

function ViewPencarianKRS() {
global $koneksi;

  if($_SESSION['_LevelID'] == 120) {	  
	  $_inputTahun = "<b>$_SESSION[_krsTahunID]</b>";
	  $_inputNIM = "<b>$_SESSION[_krsMhswID]</b>";
  } else {
	  $s = "select DISTINCT(TahunID) from tahun where KodeID='".KodeID."' order by TahunID DESC";
	  $r = mysqli_query($koneksi, $s);
	  $opttahun = "<option value=''></option>";
	  while($w = mysqli_fetch_array($r))
		{  $ck = ($w['TahunID'] == $_SESSION['_krsTahunID'])? "selected" : '';
		   $opttahun .=  "<option value='$w[TahunID]' $ck>$w[TahunID]</option>";
		}
	  
	  $_inputTahun = "<select style='width:80px;height:30px' name='_krsTahunID' onChange='this.form.submit()'>$opttahun</select>&nbsp;&nbsp;&nbsp;";
	  $_inputNIM = "<input  style='height:30px' type=text name='_krsMhswID' value='$_SESSION[_krsMhswID]' style='height:25px' placeholder='NIM'  size=20 maxlength=15 onFocus='select()'/>";
	  $_inputCari = "<input style='height:30px;margin-top:-4px' class='btn btn-success btn-sm' type=submit name='Cari' value='Cari' />";
  }
  
  echo "
  <div class='card'>
  <div class='card-header'>

  <form action='?' method=POST>
  <input  type=hidden name='_krsHariID' value='' />
  <div align=center>
      Tahun Akademik $_inputTahun
        $_inputNIM
        $_inputCari
  </div>
  </form>


</div>
</div>";
}
function CekBolehAksesModul() {
  $arrAkses = array(1, 20, 40, 41, 120);
  $key = array_search($_SESSION['_LevelID'], $arrAkses);
  if ($key === false)
    die(PesanError('Error',
      "Anda tidak berhak mengakses modul ini.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut."));
}
function BolehAksesData($nim) {
  if ($_SESSION['_LevelID'] == 120 && $_SESSION['_Login'] != $nim) {
    echo PesanError('Error',
      "Anda tidak boleh melihat data KRS mahasiswa lain.<br />
      Anda hanya boleh mengakses data dari NIM: <b>$_SESSION[_Login]</b>.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut");
    return false;
  } else return true;
}
function ValidasiDataMhsw($thn, $nim, &$khs) {
  $khs = AmbilFieldx("khs k
    left outer join statusmhsw s on s.StatusMhswID = k.StatusMhswID", 
    "k.KodeID = '".KodeID."' and k.TahunID = '$thn' and k.MhswID",
    $nim, 
    "k.*, s.Nama as STA");
  if (empty($khs)) {
    if($_SESSION['_LevelID'] == 120)
	{ echo PesanError("Error",
      "Anda tidak terdaftar di Tahun Akd <b>$thn</b>.<br />
      Hubungi Bagian <b>KEUANGAN</b> Untuk Aktivasi Tahun Akademik <b>$thn</b>.");
	}
	else
	{ 
	  echo PesanError("Error",
      "Mahasiswa <b>$nim</b> tidak terdaftar di Tahun Akd <b>$thn</b>.<br />
      Masukkan data yang valid. Hubungi Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      Opsi: Buat data semester Mhsw");
	}
    return false;
  }
  else {
    return true;
  }
}
function TampilkanHeaderMhsw($thn, $mhsw, $khs) {
  $KRSMulai = FormatTanggal($thn['TglKRSMulai']);
  $KRSSelesai = FormatTanggal($thn['TglKRSSelesai']);
  $BayarMulai = FormatTanggal($thn['TglBayarMulai']);
  $BayarSelesai = FormatTanggal($thn['TglBayarSelesai']);
  $pa = AmbilFieldx('dosen', "KodeID='".KodeID."' and Login", $mhsw['PenasehatAkademik'], 'Nama, Gelar');
  // batas waktu
  $skrg = date('Y-m-d');
  //if ($thn['TglKRSMulai'] <= $skrg && $skrg <= $thn['TglKRSSelesai']) {
    if ($_SESSION['_LevelID'] == 120) {
//      $CetakKRS = "<a href='#' onClick=\"alert('Hubungi Staf TU/Adm Akademik untuk mencetak LRS/KRS.')\"><img src='img/print.png' /></a>";
//      $CetakLRS = '';
      $CetakKRS = "<input class='btn btn-success btn-xs' type=button name='CetakKRS' value='Cetak KRS' onClick=\"javascript:CetakKRS($khs[KHSID])\" />";
      $CetakLRS = "<input class='btn btn-danger btn-xs' type=button name='CetakLRS' value='Cetak LRS' onClick=\"javascript:CetakLRS($khs[KHSID])\"/>";
    }
    else {
      $CetakKRS = "<input class='btn btn-success btn-xs' type=button class='btn btn-danger btn-xs' name='CetakKRS' value='Cetak KRS' onClick=\"javascript:CetakKRS($khs[KHSID])\" />";
      $CetakLRS = "<input class='btn btn-danger btn-xs' type=button class='btn btn-primary btn-xs' name='CetakLRS' value='Cetak LRS' onClick=\"javascript:CetakLRS($khs[KHSID])\"/>";
    }
	KRSScript();
  //}
  //else {
  //  $CetakKRS = '&nbsp;';
  //  $CetakLRS = '&nbsp;';
  // }
  
  echo "
  <div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:70%' align='center'>
  <tr>
      <th class=inp width=120>NIM</th>
      <th class=ul width=250>: $mhsw[MhswID]</th>
      <th class=inp width=130>Program</th>
      <th class=ul>: $khs[ProgramID]</th>
      <th class=inp width=140>Thn Akademik</th>
      <th class=ul width=160>: $khs[TahunID]</th>
      </tr>
  <tr>
      <th class=inp>Mahasiswa</th>
      <th class=ul >: $mhsw[Nama]</th>
      <th class=inp >Prodi</th>
      <th class=ul>: $khs[ProdiID]</th>
      <th class=inp >Status</th>
      <th class=ul >: $khs[STA] ($khs[StatusMhswID])</th>
      </tr>	  
  <tr><th class=inp>Batas KRS</th>
      <th class=ul>: $KRSMulai - $KRSSelesai</th>
      <th class=inp>Jml SKS</th>
      <th class=ul>: $khs[SKS] - $khs[MaxSKS]</th>
      <th class=inp>Status Awal</th>
      <th class=ul>: $mhsw[STAWAL] - ($mhsw[StatusAwalID])</th>
      </tr>
  <tr><th class=inp>Batas Bayar</th>
      <th class=ul>: $BayarMulai - $BayarSelesai</th>
      <th class=inp title='Dosen Pembimbing Akademik'>Dosen PA</th>
      <th class=ul>: $pa[Nama]$pa[Gelar]</th>
      <th class=ul colspan=2>
        $CetakLRS
        $CetakKRS
       
       
        $khs[CetakKRS]&times; print
        </th>
      </tr>
  </table>
  </div>
</div>
</div>";
}
function TampilkanPesanKRSSelesai() {
  echo "<table id='example' class='table table-sm table-striped' style='width:70%' align='center'>
  <tr><th class=wrn>Batas pengisian/pengubahan KRS sudah selesai, Data tidak dapat diubah.</th></tr>
  </table>";
}
function TampilkanDaftarKRSMhsw($thn, $mhsw, $khs) {
  global $koneksi;
  $whr_hari = ($_SESSION['_krsHariID'] == '')? '' : "and j.HariID='$_SESSION[_krsHariID]'";
  $s = "SELECT k.*, j.JadwalID,
    j.MKID, j.Nama AS MKNama, j.HariID, j.NamaKelas,
    LEFT(j.JamMulai, 5) AS JM, LEFT(j.JamSelesai, 5) AS JS,
    j.RuangID, mk.Sesi, j.AdaResponsi,
    CONCAT(d.Nama, ' <sup>', d.Gelar, '</sup>') AS DSN, j.JenisJadwalID, jj.Nama AS _NamaJenisJadwal, jj.Tambahan, kl.Nama AS NamaKelas
    FROM krs k
         LEFT OUTER JOIN jadwal j 
         ON j.JadwalID = k.JadwalID 
            LEFT OUTER JOIN dosen d
            ON d.Login = j.DosenID and d.KodeID = '".KodeID."'
                LEFT OUTER JOIN mk 
                ON mk.MKID = k.MKID 
                    LEFT OUTER JOIN jenisjadwal jj 
                    ON jj.JenisJadwalID = j.JenisJadwalID
                        LEFT OUTER JOIN kelas kl
                        ON kl.KelasID = j.NamaKelas       
	WHERE k.KHSID = '$khs[KHSID]'
      AND k.NA = 'N'
      $whr_hari
    ORDER BY j.HariID, j.RuangID, j.JamMulai, j.JamSelesai";
  $r = mysqli_query($koneksi, $s);
  
  // Apakah sudah melebihi batas waktu ambil/ubah KRS?
  $skrg = date('Y-m-d');
  if ($thn['TglKRSMulai'] <= $skrg && $skrg <= $thn['TglKRSSelesai']) {
    KRSScript();
    $ambil = "<input class='btn btn-success btn-xs' type=button name='TambahMK' value='Ambil MK' onClick=\"javascript:AmbilKRS('$mhsw[MhswID]', '$khs[KHSID]')\" />";
    $paket = "<input class='btn btn-primary btn-xs' type=button name='AmbilPaket' value='Ambil Paket' onClick=\"javascript:AmbilPaket('$mhsw[MhswID]', '$khs[KHSID]')\" />";
    $hapus = "<input class='btn btn-danger btn-xs' type=button name='HapusSemua' value='Hapus Semua' onClick=\"javascript:HapusSemua('$khs[KHSID]')\" />";
    $boleh = true;
  }
  else {
    TampilkanPesanKRSSelesai();
    $boleh = false;
    $ambil = '';
    $paket = '';
    $hapus = '';
  }

  // Tampilkan
  $opthari = AmbilCombo2('hari', 'Nama', 'HariID', $_SESSION['_krsHariID'], '', 'HariID');
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>";
  echo "<tr >
    <script>
    function KeHari(frm) {
      window.location = '?ndelox=$_SESSION[ndelox]&_krsHariID='+frm[frm.selectedIndex].value;
    }
    </script>
    <td class=ul1 colspan=10>
      <select name='_krsHariID' onChange=\"javascript:KeHari(this)\">$opthari</select>
      $ambil
      $paket
      $hapus
      <img src='img/kanan.gif' /> <b>Daftar Matakuliah Yang Diambil</b>
    </td></tr>";
  $hdr = "<tr style='background:purple;color:white'>
    <th class=ttl width=30>#</th>
    <th class=ttl width=100 style='text-align:center'>Jam Kuliah</th>
    <th class=ttl width=30 style='text-align:center'>Ruang</th>
	  <th class=ttl width=30 style='text-align:center'>Kode </th>
    <th class=ttl width=240>Matakuliah <sup style='color:yellow'>Semester</sup></th>
    <th class=ttl width=20 style='text-align:center'>SKS</th>
    <th class=ttl width=250>Dosen</th>
    <th class=ttl width=80 style='text-align:center'>Kelas</th>
    <th class=ttl width=20 title='Hapus KRS' style='text-align:center'>Del</th>
    </tr>";
  $n = 0;
  $hr = -3;

  while ($w = mysqli_fetch_array($r)) {
    if ($hr != $w['HariID']) {
      $hr = $w['HariID'];
      $_hr = AmbilOneField('hari', 'HariID', $hr, 'Nama');
      echo "<tr><td class=ul1 colspan=10><b>$_hr</b> </td></tr>";
      echo $hdr;
    }
    $n++;
    $del = ($boleh)? "<a href='#' onClick=\"javascript:HapusKRS($w[KHSID],$w[KRSID])\" title='Hapus KRS' /><i class='fa fa-trash'></a>" : '&times;';
    
	// Bila ditandai bukan kuliah biasa, diarsir....
	if($w['Tambahan'] == 'Y')
	{	$class='cnaY';
		$TagTambahan = "<b>( $w[_NamaJenisJadwal] ) </b>";
		$FieldResponsi = '';
	}
	else
	{	$class='ul1';
		$TagTambahan = '';
		$FieldResponsi = '<br>';
		if($w['AdaResponsi'] == 'Y')
		{	$FieldResponsi .= AmbilResponsi($w['JadwalID'], $w['KRSID'], $w['MhswID'], $thn['TahunID']);
		}	
	}
		
	echo "<tr>
      <td class=inp align=center>$n</td>
      <td class=$class align=center >$w[JM] - $w[JS]</td>
      <td class=$class align=center>$w[RuangID]&nbsp;</td>
	    <td class=$class align=center>$w[MKKode]</td>
      <td class=$class>$w[Nama](<sup>$w[Sesi]</sup>) $TagTambahan $FieldResponsi</td>
      <td class=$class align=center>$w[SKS]</td>
      <td class=$class>$w[DSN]</td>
      <td class=$class align=center>$w[NamaKelas]&nbsp;</td>
      <td class=$class align=center>$del</td>
      </tr>";
  }
  echo "</table>
  </div>
</div>
</div></p>";
}
function HapusKRS() {
	global $koneksi;
  $krsid = $_REQUEST['krsid']+0;
  $khsid = $_REQUEST['khsid']+0;
  $jdwlid = AmbilOneField('krs', 'KRSID', $krsid, 'JadwalID');
  // Penghapusan
  $s = "delete from krs where KRSID = $krsid ";
  $r = mysqli_query($koneksi, $s);
  // Hapus data presensi
  $s1 = "delete from presensimhsw where KRSID = $krsid";
  $r1 = mysqli_query($koneksi, $s1);
  // update data
  HitungPeserta($jdwlid);
  HitungUlangKRS($khsid);
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=", 1);
}
function HapusSemua() {
	global $koneksi;
  $khsid = $_REQUEST['khsid']+0;
  // Ambil data KRS siswa
  $s = "select JadwalID, KRSID
    from krs
    where KHSID = '$khsid' ";
  $r = mysqli_query($koneksi, $s);
  // Hapus 1-per-1 & update data
  while ($w = mysqli_fetch_array($r)) {
    $ss = "delete from krs where KRSID = $w[KRSID] ";
    $rr = mysqli_query($koneksi, $ss);
    // Hapus data presensi
    $s1 = "delete from presensimhsw where KRSID = $w[KRSID]";
    $r1 = mysqli_query($koneksi, $s1);
    HitungPeserta($w['JadwalID']);
  }
  HitungUlangKRS($khsid);
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=", 1);
}
function HapusSemua_xxx() {
	global $koneksi;
  $khsid = $_REQUEST['khsid']+0;
  $s = "delete from krs where KHSID = '$khsid' ";
  $r = mysqli_query($koneksi, $s);
  // update data
  $jdwlid = AmbilOneField('krs', 'KRSID', $krsid, 'JadwalID');
  HitungPeserta($jdwlid);
  HitungUlangKRS($khsid);
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=", 1);
}

function KRSScript() {
  //width=1000, height=600, scrollbars, status, resizable
  RandomStringScript();
  echo <<<SCR
  
  <script>
  <!--
  function AmbilKRS(mhswid, khsid) {
    lnk = "$_SESSION[ndelox].ambil.php?mhswid="+mhswid+"&khsid="+khsid;
    win2 = window.open(lnk, "", "width=900, height=600,left=300,top=100,toolbar=0,status=0");
    if (win2.opener == null) childWindow.opener = self;
  }
  function AmbilPaket(mhswid, khsid) {
    lnk = "$_SESSION[ndelox].ambilpaket.php?mhswid="+mhswid+"&khsid="+khsid;
    win2 = window.open(lnk, "", "width=600,height=600,left=450,top=200,toolbar=0,status=0");
    if (win2.opener == null) childWindow.opener = self;
  }
  function HapusKRS(khsid,krsid) {
    if (confirm("Anda yakin akan menghapus matakuliah ini dari KRS Anda?")) {
      window.location = "?ndelox=$_SESSION[ndelox]&lungo=HapusKRS&khsid="+khsid+"&krsid="+krsid;
    }
  }
  function HapusSemua(khsid) {
    if (confirm("Anda yakin akan menghapus semua matakuliah di KRS? Matakuliah yang sudah dihapus tidak dapat dikembalikan lagi.")) {
      window.location = "?ndelox=$_SESSION[ndelox]&lungo=HapusSemua&khsid="+khsid;
    }
  }
  function CetakKRS(khsid) {
    _rnd = randomString();
    lnk = "$_SESSION[ndelox].cetak.php?khsid="+khsid+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=800, height=600, scrollbars, status, resizable");
    if (win2.opener == null) childWindow.opener = self;
    window.location = "?ndelox=$_SESSION[ndelox]&lungo=CetakKRS&BypassMenu=1&khsid="+khsid;
  }
  function CetakLRS(khsid) {
    _rnd = randomString();
    lnk = "$_SESSION[ndelox].lrs.php?khsid="+khsid+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=800, height=600, scrollbars, status, resizable");
    if (win2.opener == null) childWindow.opener = self;
  }
  function KRSLabEdt(md, jid, krsid, krsresid, jenis) {
    lnk = "$_SESSION[ndelox].resedit.php?md="+md+"&jid="+jid+"&krsid="+krsid+"&krsresid="+krsresid+"&jenis="+jenis;
	win2 = window.open(lnk, "", "width=600, height=300, scrollbars, status, resizable");
    if (win2.opener == null) childWindow.opener = self;
  }
  -->
  </script>
SCR;
}
function CetakKRS() {
	global $koneksi;
  $khsid = $_REQUEST['khsid']+0;
  if ($khsid > 0) {
    $s = "update khs set CetakKRS = CetakKRS+1 where KHSID='$khsid' ";
    $r = mysqli_query($koneksi, $s);
  }
  echo "<script>
  window.location = '?ndelox=$_SESSION[ndelox]&lungo=';
  </script>";
}
function AmbilResponsi($id, $krsid, $mhswid, $tahunid) {
	global $koneksi;
   $arrEkstra = array();
   $a = array();
   // Cek apakah ada jadwal tambahan yang harus diambil. Bila ada 1 saja yang dijadwalkan berarti harus diambil
   $s = "select DISTINCT(jr.JenisJadwalID) as _JenisJadwalID from jadwal jr
			where jr.JadwalRefID='$id' and jr.TahunID='$tahunid' and jr.KodeID='".KodeID."'";
   $r = mysqli_query($koneksi, $s);
   while($w = mysqli_fetch_array($r)) $arrEkstra[] = $w['_JenisJadwalID'];

	if(!empty($arrEkstra))
	{	foreach($arrEkstra as $ekstra)
		{	$s = "select k.KRSID, jr.JadwalID, jr.JadwalRefID, h.Nama as _NamaHari, LEFT(jr.JamMulai, 5) as _JM, LEFT(jr.JamSelesai, 5) as _JS, 
					jr.RuangID, r.Nama as _NamaRuang, jr.JenisJadwalID, jj.Nama as _NamaJenisJadwal 
				from krs k left outer join jadwal jr on k.JadwalID=jr.JadwalID 
					left outer join ruang r on jr.RuangID = r.RuangID and r.KodeID = '".KodeID."'
					left outer join hari h on h.HariID = jr.HariID
					left outer join jenisjadwal jj on jj.JenisJadwalID=jr.JenisJadwalID
				where jr.JenisJadwalID='$ekstra' 
					and k.MhswID='$mhswid' 
					and k.TahunID='$tahunid'
					and k.KodeID='".KodeID."'
				order by jj.JenisJadwalID, jr.HariID, jr.JamMulai, jr.JamSelesai";
			$r = mysqli_query($koneksi, $s);
			$n = mysqli_num_rows($r);
			if($n == 0)
			{	$NamaJenisJadwal = AmbilOneField('jenisjadwal', "JenisJadwalID", $ekstra, "Nama");
				$a[] = "&rsaquo; <font color=red>$NamaJenisJadwal ( belum terjadwal ) </font><a href='#' onClick=\"KRSLabEdt(1, '$id', '$krsid', '$w[KRSID]', '$ekstra')\"><font size=0.8m>Tambah</font></a>";
			}
			else if($n == 1)
			{	$w = mysqli_fetch_array($r);
				$a[] = "&rsaquo; <b>$w[_NamaJenisJadwal] &rsaquo;&rsaquo;</b> $w[_NamaHari], $w[_JM] - $w[_JS], $w[_NamaRuang]($w[RuangID]) <a href='#' onClick=\"KRSLabEdt(0, '$id', '$krsid', '$w[KRSID]', '$w[JenisJadwalID]')\"><font size=0.8m>Edit</font></a>";
			}
			else
			{   $a[] = "Seharusnya ga ke sini";
			}
		}
	}
	$a = (!empty($a))? "<br />".implode("<br />", $a) : '';
   return $a;
   /*$s = "select jr.JadwalID, jr.JadwalRefID, h.Nama as _NamaHari, LEFT(jr.JamMulai, 5) as _JM, LEFT(jr.JamSelesai, 5) as _JS, 
			jr.RuangID, r.Nama as _NamaRuang, jr.JenisJadwalID, jj.Nama as _NamaJenisJadwal
    from krs k 
	  left outer join jadwal jr on k.JadwalID = jr.JadwalID and jr.JadwalRefID = '$id'
             left outer join ruang r on jr.RuangID = r.RuangID and r.KodeID = '".KodeID."'
	  left outer join hari h on h.HariID = jr.HariID
	  left outer join jenisjadwal jj on jj.JenisJadwalID=jr.JenisJadwalID
	where k.KodeID='".KodeID."' and k.MhswID='$mhswid' and k.TahunID='$tahunid' and jj.Tambahan='Y'
    order by jj.JenisJadwalID, jr.HariID, jr.JamMulai, jr.JamSelesai";
  $r = mysqli_query($s);
  //die("<pre>$s</pre>");
  $a = array();;
  $n = 0; $jj = 'K';
  while ($w = mysqli_fetch_array($r)) {
    if($jj != $w['JenisJadwalID'])
	{	$n = 0;
		$jj = $w['JenisJadwalID'];
	}
	$n++;
	$a[] = "&rsaquo; <b>$w[_NamaJenisJadwal] #$n</b> $w[_NamaHari], $w[_JM] - $w[_JS], $w[_NamaRuang]($w[RuangID]) <a href='#' onClick=\"JdwlLabEdt(0, '$w[JadwalRefID]', '$w[JadwalID]')\"><img src='img/edit.png' /></a>";
  }
  $a = (!empty($a))? "<br />".implode("<br />", $a) : '';
  return $a;*/
}
?>
