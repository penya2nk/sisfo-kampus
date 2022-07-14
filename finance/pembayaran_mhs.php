<?php
// error_reporting(0);
include_once "$_SESSION[ndelox].lib.php";

$TahunID = GainVariabelx('TahunID');
$MhswID = GainVariabelx('MhswID');
BayarMhswScript();

TitleApps("HER REGISTRASI DAN PEMBAYARAN MAHASISWA");
TampilkanHeaderBayar();

$lungo = (empty($_REQUEST['lungo']))? 'MahasiswaDetail' : $_REQUEST['lungo'];
$lungo();

function TampilkanHeaderBayar() {
  $print = (empty($_SESSION['TahunID']))? "" : "<a href='#' onClick=\"javascript:CetakTagihan('$_SESSION[TahunID]', '$_SESSION[MhswID]')\" title='Tagihan Administrasi'><i class='fa fa-print'></i></a>";
  echo "
	<script>
	  function CetakTagihan(thn, mhsw) {
		lnk = '$_SESSION[ndelox].tagihan.php?TahunID='+thn+'&MhswID='+mhsw;
		win2 = window.open(lnk, '', 'width=800, height=600, scrollbars, status');
		if (win2.opener == null) childWindow.opener = self;
	  }
    </script>
    
    <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
    <table id='example' class='table table-sm table-striped' style='width:30%' align='center' />
    <form action='?' method=POST>
    <input type=hidden name='lungo' value='MahasiswaDetail' />
    <tr>
      
        <td >
          <input style='height:30px' type=text placeholder='ex: 20221' name='TahunID' value='$_SESSION[TahunID]'size=12 maxlength=6 />
          </td>
       
        <td >
          <input style='height:30px' type=text placeholder='NIM' name='MhswID' value='$_SESSION[MhswID]' size=20 maxlength=12 />
          <input class='btn btn-success btn-sm' type=submit name='CariMhsw' value='Lihat Data' />
          </td>
          
		<td>$print</td>
        </tr>
    
    </form>
    </table>
    </div>
</div>
</div>";
}
function MahasiswaDetail() {
  if (!empty($_SESSION['MhswID']) && !empty($_SESSION['TahunID'])) {
    $mhsw = PeriksaDataMhs($_SESSION['MhswID']);
    if (!empty($mhsw)) {
      $khs = PeriksaSemesterMhs($_SESSION['TahunID'], $_SESSION['MhswID']);
      if (!empty($khs)) {
        ViewDataMhs($mhsw, $khs);
        TampilkanBIPOTMhsw($mhsw, $khs);
        TampilkanBayarMhsw($mhsw, $khs);
      }
    }
  }
}
function PeriksaDataMhs($mhswid) {
  $mhsw = AmbilFieldx("mhsw m
      left outer join prodi prd on prd.ProdiID = m.ProdiID and prd.KodeID = '".KodeID."'
      left outer join program prg on prg.ProgramID = m.ProgramID and prg.KodeID = '".KodeID."' 
      left outer join dosen d on d.Login = m.PenasehatAkademik and d.KodeID = '".KodeID."' 
      left outer join statusmhsw sm on sm.StatusMhswID = m.StatusMhswID",
    "m.KodeID = '".KodeID."' and m.MhswID", $mhswid,
    "m.*,
    prd.Nama as _PRD, prg.Nama as _PRG,
    sm.Nama as _STT, sm.Keluar,
    if (d.Nama is NULL or d.Nama = '', 'Belum diset', concat(d.Nama, ', ' , d.Gelar)) as _DSN");
  if (empty($mhsw))
    echo PesanError('Error',
      "Mahasiswa dengan NIM <b>$mhswid</b> tidak ditemukan.<br />
      Hubungi Administrator untuk informasi lebih lanjut.");
  return $mhsw;
}

function PeriksaSemesterMhs($tahunid, $mhswid) {
  $khs = AmbilFieldx("khs k",
    "k.KodeID = '".KodeID."' and k.TahunID = '$tahunid' and k.MhswID",
    $mhswid, "k.*");
  if (empty($khs))
    echo Info("Data Semester",
      "Data Tahun Akademik <b>$tahunid</b> untuk mahasiswa ini belum dibuat.<br />
      Apakah mahasiswa akan didaftarkan untuk Tahun Akademik ini?
      <hr size=1 color=silver />
        <input class='btn btn-success btn-sm' type=button name='BuatData' value='Daftarkan Mhsw'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=DaftarkanSemesterMhsw&MhswID=$mhswid&TahunID=$tahunid&BypassMenu=1'\" />
        <input class='btn btn-primary btn-sm' type=button name='Batalkan' value='Jangan Daftarkan'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&MhswID='\" />
        &raquo;
        <input class='btn btn-warning btn-sm' type=button name='LihatSemester' value='Lihat Sejarah Semester Mhsw'
        onClick=\"javascript:InquirySemesterMhsw('$mhswid')\" />");
  return $khs;
}

function ViewDataMhs($mhsw, $khs) {
  ViewHeaderMhs($mhsw, $khs);
}

function BuatSummaryKeu($mhsw, $khs) {
  $_Biaya = number_format($khs['Biaya']);
  $_Potongan = number_format($khs['Potongan']);
  $_Bayar = number_format($khs['Bayar']);
  $_Tarik = number_format($khs['Tarik']);
  $Sisa = $khs['Biaya'] - $khs['Potongan'] + $khs['Tarik'] - $khs['Bayar'];
  $_Sisa = number_format($Sisa);
  $color = ($Sisa > 0)? 'color=red' : '';
  $NamaBipot = AmbilOneField('bipot', 'BIPOTID', $mhsw['BIPOTID'], 'Tahun');
  $NamaBipot = (empty($NamaBipot))? 'Blm diset' : $NamaBipot;
  return <<<ESD
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table class=box cellspacing=1 align=center width='100%'>
  <tr style='background:purple;color:white'>
	  <th class=inp width=15%>Biaya & Potongan</th>
      <th class=inp width=15% style='text-align:right'>Total Biaya</th>
      <th class=inp width=15% style='text-align:right'>Total Potongan</th>
      <th class=inp width=15% style='text-align:right'>Total Bayar</th>
      <th class=inp width=15% style='text-align:right'>Total Penarikan</th>
      <th class=inp style='text-align:right'>SISA</th>
      </tr>
  <tr><td class=ul align=left>$NamaBipot
      <a href='#' onClick="javascript:EditBipot('$mhsw[MhswID]')"><i class='fa fa-edit'></i></a>
      </td>
      <td class=ul align=right>$_Biaya</td>
      <td class=ul align=right>$_Potongan</td>
      <td class=ul align=right>$_Bayar</td>
      <td class=ul align=right>$_Tarik</td>
      <td class=ul align=right><font size=+1 $color>$_Sisa</font></td>
  </table>
  </div>
</div>
</div>
  
  <script>
  function EditBipot(mhswid) {
    lnk = "$_SESSION[ndelox].bipotmhsw.php?MhswID="+mhswid;
    win2 = window.open(lnk, "", "width=400, height=300, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
ESD;
}
function ViewHeaderMhs($mhsw, $khs) {
  $summary = BuatSummaryKeu($mhsw, $khs);
  $tombol = <<<ESD
    <i>Lakukan Proses BIPOT Terlebih Dahulu Untuk Memastikan Biaya Mahasiswa</i><br />
    <input class='btn btn-success btn-sm' type=button name='Proses' value='Proses BIPOT'
      onClick="location='?ndelox=$_SESSION[ndelox]&BypassMenu=1&lungo=ProsesBIPOT&MhswID=$mhsw[MhswID]&TahunID=$khs[TahunID]'" />
    <input class='btn btn-warning btn-sm' type=button name='HapusSemua' value='Hapus Semua BIPOT' 
      onClick="javascript:BIPOTDELALLCONF('$mhsw[MhswID]','$khs[TahunID]')" />
    <input class='btn btn-primary btn-sm' type=button name='TambahBipot' value='Tambah Bipot'
      onClick="javascript:BIPOTEdit('$mhsw[MhswID]', '$khs[TahunID]', 1, 0)" />
    <input class='btn btn-danger btn-sm' type=button name='TambahBayar' value='Tambah Pembayaran'
      onClick="javascript:ByrEdit('$mhsw[MhswID]', $khs[KHSID], 1, '')" />
    <input class='btn btn-secondary btn-sm' type=button name='btnTarikan' value='Penarikan'
      onClick="javascript:fnTarikan('$mhsw[MhswID]', $khs[KHSID], 1, '')" />
	<input class='btn btn-success btn-sm' type=button name='btnHistoryBeasiswa' value='Sejarah Beasiswa'
	  onClick="javascript:fnHistoryBeasiswa('$mhsw[MhswID]', $khs[KHSID], 1)" />
ESD;
//function ByrEdit(mhswid, khsid, md, bayarid) {
  $Stt = AmbilOneField('statusmhsw', 'StatusMhswID', $khs['StatusMhswID'], 'Nama');
  $khslalustring = '';
  if($khs['Sesi'] > 1)
  {
  	$sesilalu = $khs['Sesi']-1;
  	$khslalu = AmbilFieldx('khs', "Sesi='$sesilalu' and MhswID='$mhsw[MhswID]' and KodeID", KodeID, 'IP, IPS, TahunID, SKS'); 
      $khslalustring = "<tr><td class=inp>SKS Tahun $khslalu[TahunID] / ComboProdiProgramx</td>
     					<td class=ul1>: $khslalu[SKS]</td>
						<td class=inp>IPS / IP Tahun $khslalu[TahunID]:</td>
						<td class=ul1>: $khslalu[IPS] / $khslalu[IP]</td>
      				</tr>";
  }
  else
  {	$NilaiSekolah = AmbilOneField('pmb', "MhswID='$mhsw[MhswID]' and KodeID", KodeID, 'NilaiSekolah');
      $NilaiSekolah = (empty($NilaiSekolah))? "<b><tidak ada data></b>" : $NilaiSekolah;
      $khslalustring = "<tr><td class=inp>Nilai Sekolah</td>
      				<td class=ul1>: $NilaiSekolah</td></tr>";

  }
  echo "
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:60%' align='center'>
  <tr><td class=inp width=220>Mahasiswa</td>
      <td class=ul1><b>: $mhsw[Nama]</b> <sup>($mhsw[_STT])</sup></td>
      <td class=inp width=160>NIM</td>
      <td class=ul1><b>: $mhsw[MhswID]</b></td>
      </tr>
  <tr><td class=inp>Program Studi</td>
      <td class=ul1>: $mhsw[_PRD] <sup>($mhsw[ProdiID])</sup></td>
      <td class=inp>Program</td>
      <td class=ul1>: $mhsw[_PRG] - ($mhsw[ProgramID])</td>
      </tr>
  <tr><td class=inp>Penasehat Akademik</td>
      <td class=ul1>: $mhsw[_DSN]</td>
      <td class=inp>Masa Studi</td>
      <td class=ul1>
        : $mhsw[TahunID] - $mhsw[BatasStudi]
        </td>
        </tr>
  <tr><td class=inp>Jumlah SKS</td>
      <td class=ul1>: $khs[SKS] - $khs[MaxSKS]</td>
      <td class=inp>Status Smt</td>
      <td class=ul1>: $Stt <sup>($khs[StatusMhswID])</sup>
      </td></tr>
  $khslalustring
  <tr><td colspan=4>
      $summary
      </td></tr>
  <tr><td class=ul1 colspan=4 align=center>
      $tombol
      </td></tr>
  </table>
  </div>
</div>
</div>";
  // Cek apakah mhsw sudah keluar?
  if ($mhsw['Keluar'] == 'Y')
    die(PesanError('Error',
      "Mahasiswa sudah keluar.<br />
      Status: <b>$mhsw[_STT]</b> <sup>($mhsw[StatusMhswID])</sup>.<br />
      Data sudah tidak bisa diakses lagi.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut."));
}
function DaftarkanSemesterMhsw() {
	global $koneksi;
  $MhswID = sqling($_REQUEST['MhswID']);
  $TahunID = sqling($_REQUEST['TahunID']);
  $ada = AmbilOneField('khs', "KodeID='".KodeID."' and TahunID='$TahunID' and MhswID",
    $MhswID, 'KHSID')+0;
  if ($ada > 0) {
    echo PesanError("Error",
      "Mahasiswa <b>$MhswID</b> sudah terdaftar utk Tahun <b>$TahunID</b>.<br />
      Silakan mengecek data mahasiswa, mungkin ada kesalahan.
      <hr size=1 color=silver />
      <input type=button name='Kembali' value='Kembali'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />");
  }
  else {
    $mhsw = AmbilFieldx('mhsw', "KodeID='".KodeID."' and MhswID", $MhswID,
      "Nama, ProgramID, ProdiID, BIPOTID, StatusMhswID");
    // Ambil semester terakhir mhsw
    $_sesiakhir = AmbilOneField('khs', "KodeID='".KodeID."' and MhswID", $MhswID,
      "max(Sesi)")+0;
    if ($_sesiakhir > 0) {
      $_khs = AmbilFieldx('khs', "KodeID='".KodeID."' and MhswID='$MhswID' and Sesi", 
        $_sesiakhir, '*');
      $Sesi = $_khs['Sesi']+1;
      $MaxSKS = AmbilOneField('maxsks', "KodeID='".KodeID."' 
        and DariIP <= $_khs[IPS] and $_khs[IPS] <= SampaiIP
        and ProdiID", $mhsw['ProdiID'], 'SKS')+0;
    }
    else {
      $Sesi = 1;
      $MaxSKS = AmbilOneField('prodi', "KodeID='".KodeID."' and ProdiID",
        $mhsw['ProdiID'], 'DefSKS');
    }
    //$StatusMhswID = AmbilOneField('statusmhsw', 'Def', 'Y', 'StatusMhswID');
    //$StatusMhswID = (empty($StatusMhswID))? 'A' : $StatusMhswID;
	$StatusMhswID = $mhsw['StatusMhswID'];
	
    // Simpan
    $s = "insert into khs
      (TahunID, KodeID, ProgramID, ProdiID, 
      MhswID, StatusMhswID,
      Sesi, IP, MaxSKS,
      LoginBuat, TanggalBuat, NA)
      values
      ('$TahunID', '".KodeID."', '$mhsw[ProgramID]', '$mhsw[ProdiID]',
      '$MhswID', '$StatusMhswID',
      '$Sesi', 0, $MaxSKS,
      '$_SESSION[_Login]', now(), 'N')";
    $r = mysqli_query($koneksi, $s);
    SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=", 100);
  }
}
function TampilkanBIPOTMhsw($mhsw, $khs) {
		global $koneksi;
  BIPOTScript();
  $s = "select bm.*, s.Nama as _saat,
    format(bm.Jumlah, 0) as JML,
    format(bm.TrxID*bm.Besar, 0) as BSR,
    format(bm.Dibayar, 0) as BYR
    from bipotmhsw bm
	  left outer join bipot2 b2 on b2.BIPOT2ID = bm.BIPOT2ID
      left outer join saat s on b2.SaatID = s.SaatID
    where bm.PMBMhswID = 1
      and bm.NA = 'N'
	  and bm.BIPOT2ID != 0
      and bm.KodeID = '".KodeID."'
      and bm.MhswID = '$mhsw[MhswID]'
      and bm.TahunID = '$khs[TahunID]'
    order by b2.Prioritas, bm.TrxID DESC, bm.BIPOTMhswID ";
  $r = mysqli_query($koneksi, $s); $n = 0;
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:80%' align='center'>";
  echo "<tr>
    <th class=ttl colspan=10>Daftar Biaya & Potongan (BIPOT)</th>
    </tr>";
  echo "<tr style='background:purple;color:white'>
    <th class=ttl colspan=2>#</th>
    <th class=ttl>Nama Biaya/Potongan</th>
    <th class=ttl style='text-align:right'>Jumlah &times; Besar</th>
    <th class=ttl style='text-align:right'>Total</th>
    <th class=ttl style='text-align:right'>Dibayar</th>
    <th class=ttl>Catatan</th>
    <th class=ttl>&times;</th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    $sub = $w['Jumlah'] * $w['Besar'] * $w['TrxID'];
    $_sub = number_format($sub);
    $ttl += $sub;
    $byr += $w['Dibayar'];
    if ($_SESSION['_LevelID'] == 1) {
      $del = "<a href='#' onClick=\"BIPOTDELCONF($w[BIPOTMhswID], '$mhsw[MhswID]', '$khs[TahunID]')\"><i class='fa fa-trash'></i></a>";
    }
    $ctt = TRIM($w['Catatan']);
    $ctt = str_replace("\r", "<br />", $ctt);
    echo "<tr>
      <td class=inp width=15>$n</td>
      <td class=ul width=10>
        <a href='#' onClick=\"javascript:BIPOTEdit('$mhsw[MhswID]', '$khs[TahunID]', 0, $w[BIPOTMhswID])\"><i class='fa fa-edit'></i></a>
        </td>
      <td class=ul>$w[Nama] $w[TambahanNama] $w[_saat]</td>
      <td class=ul norwap style='text-align:right'>$w[JML] &times; $w[BSR]</td>
      <td class=ul align=right nowrap>$_sub</td>
      <td class=ul align=right nowrap>
        $w[BYR] <!--
        <a href='#' onClick=\"javascript:ByrEdit('$pmb[PMBID]', 1, 0, $w[PMBMhswID])\"><i class='fa fa-edit'></i></a>
        -->
        </td>
      <td class=ul1>$ctt&nbsp;</td>
      <td class=ul1 align=center width=10>
        $del
        </td>
      </tr>";
  }
  
  $s = "select bm.*, s.Nama as _saat,
    format(bm.Jumlah, 0) as JML,
    format(bm.TrxID*bm.Besar, 0) as BSR,
    format(bm.Dibayar, 0) as BYR
    from bipotmhsw bm
	  left outer join bipot2 b2 on b2.BIPOT2ID = bm.BIPOT2ID
      left outer join saat s on b2.SaatID = s.SaatID
    where bm.PMBMhswID = 1
      and bm.NA = 'N'
	  and bm.BIPOT2ID = 0
      and bm.KodeID = '".KodeID."'
      and bm.MhswID = '$mhsw[MhswID]'
      and bm.TahunID = '$khs[TahunID]'
    order by b2.Prioritas, bm.TrxID DESC, bm.BIPOTMhswID";
  $r = mysqli_query($koneksi, $s);
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    $sub = $w['Jumlah'] * $w['Besar'] * $w['TrxID'];
    $_sub = number_format($sub);
    $ttl += $sub;
    $byr += $w['Dibayar'];
    if ($_SESSION['_LevelID'] == 1) {
      $del = "<a href='#' onClick=\"BIPOTDELCONF($w[BIPOTMhswID], '$mhsw[MhswID]', '$khs[TahunID]')\"><i class='fa fa-trash'></i></a>";
    }
    $ctt = TRIM($w['Catatan']);
    $ctt = str_replace("\r", "<br />", $ctt);
    echo "<tr>
      <td class=inp width=15>$n</td>
      <td class=ul width=10>
        <a href='#' onClick=\"javascript:BIPOTEdit('$mhsw[MhswID]', '$khs[TahunID]', 0, $w[BIPOTMhswID])\"><i class='fa fa-edit'></i></a>
        </td>
      <td class=ul>
        $w[Nama] <br />
        <sup>$w[TambahanNama]</sup>
		<div align=right><sub>$w[_saat]</sub></div>
        </td>
      <td class=ul norwap>
        <sup>$w[JML] &times;</sup><br />
        <div align=right>$w[BSR]</div>
        </td>
      <td class=ul align=right nowrap>$_sub</td>
      <td class=ul align=right nowrap>
        $w[BYR] <!--
        <a href='#' onClick=\"javascript:ByrEdit('$pmb[PMBID]', 1, 0, $w[PMBMhswID])\"><i class='fa fa-edit'></i></a>
        -->
        </td>
      <td class=ul1>$ctt&nbsp;</td>
      <td class=ul1 align=center width=10>
        $del
        </td>
      </tr>";
  }
  
  $TTL = number_format($ttl);
  $BYR = number_format($byr);
  $SS = number_format($ttl - $byr);
  echo "<tr><td bgcolor=silver colspan=10 height=1></td></tr>";
  echo "<tr>
    <td class=ul1 colspan=4 align=right><b>Total:</td>
    <td class=ul1 align=right><b>$TTL</b></td>
    <td class=ul1 align=right><b>$BYR</b></td>
    <td class=ul1 colspan=2>Sisa: <font size=+1>$SS</font></td>
    </tr>";
  echo "</table>
";
}
function HapusSemuaBIPOT() {
		global $koneksi;
  $MhswID = sqling($_REQUEST['MhswID']);
  $TahunID = sqling($_REQUEST['TahunID']);

  $s = "update bipotmhsw
    set NA = 'Y'
    where PMBMhswID = 1
      and NA = 'N'
      and MhswID = '$MhswID'
      and Dibayar = 0
      and TahunID = '$TahunID'
      and KodeID = '".KodeID."' ";
  $r = mysqli_query($koneksi, $s);
  HitungUlangBIPOTMhsw($MhswID, $TahunID);
  echo "<script>window.location='?ndelox=$_SESSION[ndelox]&lungo=&MhswID=$MhswID&TahunID=$TahunID'</script>";
}
function HapusBIPOT() {
		global $koneksi;
  $_BIPOTMhswID = $_REQUEST['_BIPOTMhswID']+0;
  $MhswID = sqling($_REQUEST['MhswID']);
  $TahunID = sqling($_REQUEST['TahunID']);
  /*
  $s = "delete from bipotmhsw where BIPOTMhswID = '$_BIPOTMhswID' ";
  $r = mysqli_query($koneksi, $s); 
  */
  $s = "update bipotmhsw set NA = 'Y' where BIPOTMhswID = '$_BIPOTMhswID' ";
  $r = mysqli_query($koneksi, $s);
  HitungUlangBIPOTMhsw($MhswID, $TahunID);
  echo "<script>window.location='?ndelox=$_SESSION[ndelox]&lungo=&MhswID=$MhswID&TahunID=$TahunID'</script>";
}
function ProsesBIPOT() {
  global $koneksi;
  $MhswID = sqling($_REQUEST['MhswID']);
  $TahunID = sqling($_REQUEST['TahunID']);
  // Get data
  $mhsw = AmbilFieldx('mhsw', "KodeID='".KodeID."' and MhswID", $MhswID, "*");
  $khs = AmbilFieldx('khs', "KodeID = '".KodeID."' and TahunID = '$TahunID' and MhswID", $MhswID, "*");
  $khslalu = array();
  if($khs['Sesi'] > 1)
  {
	  $sesilalu = $khs['Sesi']-1;
	  $khslalu = AmbilFieldx('khs', "KodeID = '".KodeID."' and Sesi = '$sesilalu' and MhswID", $MhswID, "*");
	  /*while(!empty($khslalu))
	  {	if($khslalu['StatusMhswID'] != 'A')
		{	$sesilalu = $sesilalu-1;
			$khslalu = AmbilFieldx('khs', "KodeID = '".KodeID."' and Sesi = '$sesilalu' and MhswID", $MhswID, "*");
		}
		else
		{	break;
		}
	  }*/
  }
  
  // Ambil BIPOT-nya
  $s = "select * 
    from bipot2 
    where BIPOTID = '$mhsw[BIPOTID]'
      and Otomatis = 'Y'
      and PerMataKuliah = 'N'
	  and PerLab = 'N'
	  and Remedial = 'N'
	  and PraktekKerja = 'N'
	  and NA = 'N'
    order by TrxID, Prioritas";
  $r = mysqli_query($koneksi, $s);
  $MsgList = array();
  while ($w = mysqli_fetch_array($r)) {
    $MsgList[] = '';
	$MsgList[] = "Memproses $w[BIPOT2ID], Rp. $w[Jumlah]";
	
	$oke = true;
    // Apakah sesuai dengan status awalnya?
    $pos = strpos($w['StatusAwalID'], ".".$mhsw['StatusAwalID'].".");
    $oke = $oke && !($pos === false);
	$MsgList[] =  "Sesuai dengan status awalnya ($w[StatusAwalID] ~ $mhsw[StatusAwalID])? $oke";
	
	// Apakah sesuai dengan status mahasiswanya?
    $pos = strpos($w['StatusMhswID'], ".".$khs['StatusMhswID'].".");
    $oke = $oke && !($pos === false);
	$MsgList[] =  "Sesuai dengan status mahasiswanya ($w[StatusMhswID] ~ $khs[StatusMhswID])? $oke";
	
    // Apakah grade-nya?
    if ($oke) {
      if ($w['GunakanGradeNilai'] == 'Y') {
        $pos = strpos($w['GradeNilai'], ".".$mhsw['GradeNilai'].".");
        $oke = $oke && !($pos === false);
		$MsgList[] = "Gunakan Grade Nilai? $oke";
	  }
    }
	
	// Apakah Jumlah SKS Tahun lalu mencukupi?
	if ($oke) {
	  if ($w['GunakanGradeIPK'] == 'Y') {
		$_SKS = AmbilOneField('gradeipk', "IPKMin <= '$khslalu[IPS]' and '$khslalu[IPS]' <= IPKMax and KodeID", KodeID, 'SKSMin');
		if($_SKS > $khslalu[SKS]) $oke = false;
		else $oke = true;
		
		$MsgList[] = "Jumlah SKS Tahun Mencukupi($_SKS ~ $khslalu[SKS])? $oke";
	  }
	}
	
	// Apakah Grade IPK-nya OK?
	if ($oke) {
      if ($w['GunakanGradeIPK'] == 'Y') {
		if(!empty($khslalu))
		{   $_GradeIPK = AmbilOneField('gradeipk', "IPKMin <= $khslalu[IPS] and $khslalu[IPS] <= IPKMax and KodeID", KodeID, 'GradeIPK');
			$pos = strpos($w['GradeIPK'], ".".$_GradeIPK.".");
			$oke = $oke && !($pos === false);
			$MsgList[] = "Grade IPK OK ($_GradeIPK ~ $w[GradeIPK])? $oke";
		}
		else
		{	$oke = false;
		}
		
      }
    }
	
    // Apakah dimulai pada sesi ini?
    if ($oke) {
      if ($w['MulaiSesi'] <= $khs['Sesi'] or $w['MulaiSesi'] == 0) $oke = true;
	  else $oke = false;
	  $MsgList[] = "Mulai pada sesi ini ($khs[Sesi] ~ $w[MulaiSesi])? $oke";
    }
	
	// Apakah ada setup berapa kali ambil?
    if ($oke && $w['KaliSesi'] > 0) {
      $_kali = AmbilOneField('bipotmhsw', "MhswID='$MhswID' and NA='N' and PMBMhswID=1 and KodeID",
        KodeID, "count(BIPOTMhswID)")+0;
      $oke = $_kali < $w['KaliSesi'];
	  $MsgList[] = "Berapa Kali Ambil - ($_kali ~ $w[KaliSesi])? $oke";
    }
	
	if($oke) $MsgList[] = "ALL OK! GO FOR IT!";
  
    // Simpan data
    if ($oke) {
      // Cek, sudah ada atau belum? Kalau sudah, ambil ID-nya
      $ada = AmbilOneField('bipotmhsw',
        "KodeID='".KodeID."' and MhswID = '$mhsw[MhswID]'
        and NA = 'N'
        and PMBMhswID = 1
        and TahunID='$khs[TahunID]' and BIPOT2ID",
        $w['BIPOT2ID'], "BIPOTMhswID") +0;
      // Cek apakah memakai script atau tidak?
      if ($w['GunakanScript'] == 'Y') BipotGunakanScript($mhsw, $khs, $w, $ada, 1);
      // Jika tidak perlu pakai script
      else {
        // Jika tidak ada duplikasi, maka akan di-inserdt. Tapi jika sudah ada, maka abaiakn aja.
        if ($ada == 0) {
          // Simpan
          $Nama = AmbilOneField('bipotnama', 'BIPOTNamaID', $w['BIPOTNamaID'], 'Nama');
          $s1 = "insert into bipotmhsw
            (KodeID, COAID, PMBMhswID, MhswID, TahunID,
            BIPOT2ID, BIPOTNamaID, TambahanNama, Nama, TrxID,
            Jumlah, Besar, Dibayar,
            Catatan, NA,
            LoginBuat, TanggalBuat)
            values
            ('".KodeID."', '$w[COAID]', 1, '$mhsw[MhswID]', '$khs[TahunID]',
            '$w[BIPOT2ID]', '$w[BIPOTNamaID]', '$w[TambahanNama]', '$Nama', '$w[TrxID]',
            1, '$w[Jumlah]', 0,
            'Auto', 'N',
            '$_SESSION[_Login]', now())";
          $r1 = mysqli_query($koneksi, $s1);
        }// end $ada=0
      } // end if $ada
    }   // end if $oke
  }     // end while
  
  // Ambil BIPOT Biaya Per Mata Kuliah
  $s = "select k.MKKode, k.Nama, k.SKS, j.BiayaKhusus, j.Biaya, j.NamaBiaya, j.AdaResponsi
			from krs k 
				left outer join jadwal j on k.JadwalID=j.JadwalID and j.KodeID='".KodeID."'
				left outer join mk mk on mk.MKID=k.MKID and mk.KodeID='".KodeID."'
			where k.MhswID='$MhswID' and k.TahunID='$_SESSION[TahunID]' and mk.PraktekKerja='N' and k.KodeID='".KodeID."'";
  $r = mysqli_query($koneksi, $s);
  while($w = mysqli_fetch_array($r))
  {	  $s1 = "select * 
	   from bipot2 
		where BIPOTID = '$mhsw[BIPOTID]'
		  and Otomatis = 'Y'
		  and (PerMataKuliah = 'Y' or PerLab = 'Y')
		  and NA = 'N'
		order by TrxID, Prioritas";
	  $r1 = mysqli_query($koneksi, $s1);
	  while ($w1 = mysqli_fetch_array($r1)) 
	  {	
		$MsgList[] = '-----------------------------------------------------------------';
		$MsgList[] = "Memproses $w1[BIPOT2ID], Rp. $w1[Jumlah]";
	    
		$oke = true;
		// Cek apakah mata kuliah ini dapat dikenakan biaya Lab
		if($w1['PerLab'] == 'Y') 
		{	if($w['AdaResponsi'] == 'Y') $oke = true;
			else $oke = false;
		}
		else $oke = true;
		
		// Apakah sesuai dengan status awalnya?
		$pos = strpos($w1['StatusAwalID'], ".".$mhsw['StatusAwalID'].".");
		$oke = $oke && !($pos === false);
		$MsgList[] =  "Sesuai dengan status awalnya ($w1[StatusAwalID] ~ $mhsw[StatusAwalID])? $oke";
		
		// Apakah sesuai dengan status mahasiswanya?
		$pos = strpos($w1['StatusMhswID'], ".".$khs['StatusMhswID'].".");
		$oke = $oke && !($pos === false);
		$MsgList[] =  "Sesuai dengan status mahasiswanya ($w1[StatusMhswID] ~ $khs[StatusMhswID])? $oke";
		
		// Apakah grade-nya?
		if ($oke) {
		  if ($w1['GunakanGradeNilai'] == 'Y') {
			$pos = strpos($w1['GradeNilai'], ".".$mhsw['GradeNilai'].".");
			$oke = $oke && !($pos === false);
			$MsgList[] = "Gunakan Grade Nilai? $oke";
		  }
		}
		
		// Apakah Jumlah SKS Tahun lalu mencukupi?
		if ($oke) {
		  if ($w1['GunakanGradeIPK'] == 'Y') {
			$_SKS = AmbilOneField('gradeipk', "IPKMin <= '$khslalu[IPS]' and '$khslalu[IPS]' <= IPKMax and KodeID", KodeID, 'SKSMin');
			if($_SKS > $khslalu[SKS]) $oke = false;
			else $oke = true;
			
			$MsgList[] = "Jumlah SKS Tahun Mencukupi($_SKS ~ $khslalu[SKS])? $oke";
		  }
		}
		
		// Apakah Grade IPK-nya OK?
		if ($oke) {
		  if ($w1['GunakanGradeIPK'] == 'Y') {
			if(!empty($khslalu))
			{   $_GradeIPK = AmbilOneField('gradeipk', "IPKMin <= $khslalu[IPS] and $khslalu[IPS] <= IPKMax and KodeID", KodeID, 'GradeIPK');
				$pos = strpos($w1['GradeIPK'], ".".$_GradeIPK.".");
				$oke = $oke && !($pos === false);
				$MsgList[] = "Grade IPK OK ($_GradeIPK ~ $w1[GradeIPK])? $oke";
			}
			else
			{	$oke = false;
			}		
		  }
		}
		
		// Apakah dimulai pada sesi ini?
		if ($oke) {
		  if ($w1['MulaiSesi'] <= $khs['Sesi'] or $w1['MulaiSesi'] == 0) $oke = true;
		  else $oke = false;
		  $MsgList[] = "Mulai pada sesi ini ($khs[Sesi] ~ $w1[MulaiSesi])? $oke";
		}
		
		// Apakah ada setup berapa kali ambil?
		if ($oke && $w1['KaliSesi'] > 0) {
		  $_kali = AmbilOneField('bipotmhsw', "MhswID='$MhswID' and NA='N' and PMBMhswID=1 and BIPOTNamaID='$w1[BIPOTNamaID]' and TambahanNama='$w[MKKode] - $w[Nama] - $w[SKS] SKS' and KodeID",
			KodeID, "count(BIPOTMhswID)")+0;
		  $oke = $_kali < $w1['KaliSesi'];
		  $MsgList[] = "Berapa Kali Ambil - ($_kali ~ $w1[KaliSesi])? $oke";
		}
		
		if($oke) $MsgList[] = "ALL OK! GO FOR IT!";
	
		// Simpan data
		if ($oke) {
		 
			$ada = AmbilOneField('bipotmhsw',
				"KodeID='".KodeID."' and MhswID = '$mhsw[MhswID]'
				and NA = 'N'
				and PMBMhswID = 1
				and TahunID='$khs[TahunID]'
				and BIPOTNamaID = '$w1[BIPOTNamaID]'
				and TambahanNama='$w[MKKode] - $w[Nama] - $w[SKS] SKS'
				and BIPOT2ID",
				$w1['BIPOT2ID'], "BIPOTMhswID") +0;
			
			if ($ada == 0) {
			  // Simpan
			  $Nama = AmbilOneField('bipotnama', 'BIPOTNamaID', $w1['BIPOTNamaID'], 'Nama');
			  if($w1['PerSKS'] == 'Y') $Jumlah = $w['SKS'];
			  else $Jumlah = 1;
			  $Besar = $w1['Jumlah'];
			  
			  $s2 = "insert into bipotmhsw
				(KodeID, COAID, PMBMhswID, MhswID, TahunID,
				BIPOT2ID, BIPOTNamaID, TambahanNama, Nama, TrxID, 
				Jumlah, Besar, Dibayar,
				Catatan, NA,
				LoginBuat, TanggalBuat)
				values
				('".KodeID."', '$w1[COAID]', 1, '$mhsw[MhswID]', '$khs[TahunID]',
				'$w1[BIPOT2ID]', '$w1[BIPOTNamaID]', '".$w['MKKode']." - ".$w['Nama']." - ".$w['SKS']." SKS', '$Nama', '$w1[TrxID]', 
				'$Jumlah', '$Besar', 0,
				'Auto', 'N',
				'$_SESSION[_Login]', now())";
			  $r2 = mysqli_query($koneksi, $s2);
		    }
	     }
	  }
  }
  
  
  // Ambil BIPOT Biaya Praktek Kerja
  $s = "select k.MKKode, k.Nama, k.SKS, j.BiayaKhusus, j.Biaya, j.NamaBiaya, j.AdaResponsi
			from krs k 
				left outer join jadwal j on k.JadwalID=j.JadwalID and j.KodeID='".KodeID."'
				left outer join mk mk on mk.MKID=k.MKID and mk.KodeID='".KodeID."'
			where k.MhswID='$MhswID' and k.TahunID='$_SESSION[TahunID]' and mk.PraktekKerja='Y' and k.KodeID='".KodeID."'";
  $r = mysqli_query($koneksi, $s);
  while($w = mysqli_fetch_array($r))
  {	  $s1 = "select * 
	   from bipot2 
		where BIPOTID = '$mhsw[BIPOTID]'
		  and Otomatis = 'Y'
		  and (PraktekKerja = 'Y')
		  and NA = 'N'
		order by TrxID, Prioritas";
	  $r1 = mysqli_query($koneksi, $s1);
	  while ($w1 = mysqli_fetch_array($r1)) 
	  {	
		$MsgList[] = '-----------------------------------------------------------------';
		$MsgList[] = "Memproses $w1[BIPOT2ID], Rp. $w1[Jumlah]";
	    
		$oke = true;
		
		// Apakah sesuai dengan status awalnya?
		$pos = strpos($w1['StatusAwalID'], ".".$mhsw['StatusAwalID'].".");
		$oke = $oke && !($pos === false);
		$MsgList[] =  "Sesuai dengan status awalnya ($w1[StatusAwalID] ~ $mhsw[StatusAwalID])? $oke";
		
		// Apakah sesuai dengan status mahasiswanya?
		$pos = strpos($w1['StatusMhswID'], ".".$khs['StatusMhswID'].".");
		$oke = $oke && !($pos === false);
		$MsgList[] =  "Sesuai dengan status mahasiswanya ($w1[StatusMhswID] ~ $khs[StatusMhswID])? $oke";
		
		// Apakah grade-nya?
		if ($oke) {
		  if ($w1['GunakanGradeNilai'] == 'Y') {
			$pos = strpos($w1['GradeNilai'], ".".$mhsw['GradeNilai'].".");
			$oke = $oke && !($pos === false);
			$MsgList[] = "Gunakan Grade Nilai? $oke";
		  }
		}
		
		// Apakah Jumlah SKS Tahun lalu mencukupi?
		if ($oke) {
		  if ($w1['GunakanGradeIPK'] == 'Y') {
			$_SKS = AmbilOneField('gradeipk', "IPKMin <= '$khslalu[IPS]' and '$khslalu[IPS]' <= IPKMax and KodeID", KodeID, 'SKSMin');
			if($_SKS > $khslalu[SKS]) $oke = false;
			else $oke = true;
			
			$MsgList[] = "Jumlah SKS Tahun Mencukupi($_SKS ~ $khslalu[SKS])? $oke";
		  }
		}
		
		// Apakah Grade IPK-nya OK?
		if ($oke) {
		  if ($w1['GunakanGradeIPK'] == 'Y') {
			if(!empty($khslalu))
			{   $_GradeIPK = AmbilOneField('gradeipk', "IPKMin <= $khslalu[IPS] and $khslalu[IPS] <= IPKMax and KodeID", KodeID, 'GradeIPK');
				$pos = strpos($w1['GradeIPK'], ".".$_GradeIPK.".");
				$oke = $oke && !($pos === false);
				$MsgList[] = "Grade IPK OK ($_GradeIPK ~ $w1[GradeIPK])? $oke";
			}
			else
			{	$oke = false;
			}		
		  }
		}
		
		// Apakah dimulai pada sesi ini?
		if ($oke) {
		  if ($w1['MulaiSesi'] <= $khs['Sesi'] or $w1['MulaiSesi'] == 0) $oke = true;
		  else $oke = false;
		  $MsgList[] = "Mulai pada sesi ini ($khs[Sesi] ~ $w1[MulaiSesi])? $oke";
		}
		
		// Apakah ada setup berapa kali ambil?
		if ($oke && $w1['KaliSesi'] > 0) {
		  $_kali = AmbilOneField('bipotmhsw', "MhswID='$MhswID' and NA='N' and PMBMhswID=1 and BIPOTNamaID='$w1[BIPOTNamaID]' and TambahanNama='$w[MKKode] - $w[Nama] - $w[SKS] SKS' and KodeID",
			KodeID, "count(BIPOTMhswID)")+0;
		  $oke = $_kali < $w1['KaliSesi'];
		  $MsgList[] = "Berapa Kali Ambil - ($_kali ~ $w1[KaliSesi])? $oke";
		}
		
		if($oke) $MsgList[] = "ALL OK! GO FOR IT!";
		
		// Simpan data
		if ($oke) {
		 
			$ada = AmbilOneField('bipotmhsw',
				"KodeID='".KodeID."' and MhswID = '$mhsw[MhswID]'
				and NA = 'N'
				and PMBMhswID = 1
				and TahunID='$khs[TahunID]'
				and BIPOTNamaID = '$w1[BIPOTNamaID]'
				and TambahanNama='$w[MKKode] - $w[Nama] - $w[SKS] SKS'
				and BIPOT2ID",
				$w1['BIPOT2ID'], "BIPOTMhswID") +0;
			
			if ($ada == 0) {
			  // Simpan
			  $Nama = AmbilOneField('bipotnama', 'BIPOTNamaID', $w1['BIPOTNamaID'], 'Nama');
			  if($w1['PerSKS'] == 'Y') $Jumlah = $w['SKS'];
			  else $Jumlah = 1;
			  $Besar = $w1['Jumlah'];
			  
			  $s2 = "insert into bipotmhsw
				(KodeID, COAID, PMBMhswID, MhswID, TahunID,
				BIPOT2ID, BIPOTNamaID, TambahanNama, Nama, TrxID, 
				Jumlah, Besar, Dibayar,
				Catatan, NA,
				LoginBuat, TanggalBuat)
				values
				('".KodeID."', '$w1[COAID]', 1, '$mhsw[MhswID]', '$khs[TahunID]',
				'$w1[BIPOT2ID]', '$w1[BIPOTNamaID]', '".$w[MKKode]." - ".$w['Nama']." - ".$w['SKS']." SKS', '$Nama', '$w1[TrxID]', 
				'$Jumlah', '$Besar', 0,
				'Auto', 'N',
				'$_SESSION[_Login]', now())";
			  $r2 = mysqli_query($koneksi, $s2);
		    }
	     }
	  }
  }
  
  // Masukkan Biaya Khusus dari tiap mata kuliah (termasuk biaya khusus mata kuliah praktek kerja - bila ada)
  $s = "select k.MKKode, k.Nama, k.SKS, j.BiayaKhusus, j.Biaya, j.NamaBiaya from krs k left outer join jadwal j on k.JadwalID=j.JadwalID and j.KodeID='".KodeID."'
			where k.MhswID='$MhswID' and k.TahunID='$_SESSION[TahunID]' and j.BiayaKhusus='Y' and k.KodeID='".KodeID."'";
  $r = mysqli_query($koneksi, $s);
  while($w = mysqli_fetch_array($r))			  
  {	$ada = AmbilOneField('bipotmhsw',
	"KodeID='".KodeID."' and MhswID = '$mhsw[MhswID]'
	and NA = 'N'
	and PMBMhswID = 1
	and TahunID='$khs[TahunID]' 
	and Nama='$w[NamaBiaya]'
	and TambahanNama='$w[MKKode] - $w[Nama] - $w[SKS] SKS'
	and BIPOT2ID",
	0, "BIPOTMhswID") +0;
	
	if ($ada == 0) {
	  // Simpan
	  
	  $s2 = "insert into bipotmhsw
		(KodeID, COAID, PMBMhswID, MhswID, TahunID,
		BIPOT2ID, BIPOTNamaID, Nama, TambahanNama, TrxID, 
		Jumlah, Besar, Dibayar,
		Catatan, NA,
		LoginBuat, TanggalBuat)
		values
		('".KodeID."', '', 1, '$mhsw[MhswID]', '$khs[TahunID]',
		0, 0, '$w[NamaBiaya]', '$w[MKKode] - $w[Nama] - $w[SKS] SKS', 1, 
		1, '$w[Biaya]', 0,
		'Biaya Khusus', 'N',
		'$_SESSION[_Login]', now())";
	  $r2 = mysqli_query($koneksi, $s2);
	}
  }
  
  // Ambil BIPOT Remedial
  $s = "select k.MKKode, k.Nama, k.SKS
			from krsremedial k 
			where k.MhswID='$MhswID' and k.TahunID='$_SESSION[TahunID]' and k.KodeID='".KodeID."'";
  $r = mysqli_query($koneksi, $s);
  while($w = mysqli_fetch_array($r))
  {	  $MsgList[] = '-----------------------------------------------------------------';
	  $MsgList[] = '---------------------------REMEDIAL---------------------------';
	  $s1 = "select * 
	   from bipot2 
		where BIPOTID = '$mhsw[BIPOTID]'
		  and Otomatis = 'Y'
		  and Remedial = 'Y'
		  and NA = 'N'
		order by TrxID, Prioritas";
	  $r1 = mysqli_query($koneksi, $s1);
	  while ($w1 = mysqli_fetch_array($r1)) 
	  {	
		$MsgList[] = '-----------------------------------------------------------------';
		$MsgList[] = "Memproses $w1[BIPOT2ID] - $w[MKKode] - $w[Nama], Rp. $w1[Jumlah]";
	    
		$oke = true;
		
		// Apakah sesuai dengan status awalnya?
		$pos = strpos($w1['StatusAwalID'], ".".$mhsw['StatusAwalID'].".");
		$oke = $oke && !($pos === false);
		$MsgList[] =  "Sesuai dengan status awalnya ($w1[StatusAwalID] ~ $mhsw[StatusAwalID])? $oke";
		
		// Apakah sesuai dengan status mahasiswanya?
		$pos = strpos($w1['StatusMhswID'], ".".$khs['StatusMhswID'].".");
		$oke = $oke && !($pos === false);
		$MsgList[] =  "Sesuai dengan status mahasiswanya ($w1[StatusMhswID] ~ $khs[StatusMhswID])? $oke";
		
		// Apakah grade-nya?
		if ($oke) {
		  if ($w1['GunakanGradeNilai'] == 'Y') {
			$pos = strpos($w1['GradeNilai'], ".".$mhsw['GradeNilai'].".");
			$oke = $oke && !($pos === false);
			$MsgList[] = "Gunakan Grade Nilai? $oke";
		  }
		}
		
		// Apakah Jumlah SKS Tahun lalu mencukupi?
		if ($oke) {
		  if ($w1['GunakanGradeIPK'] == 'Y') {
			$_SKS = AmbilOneField('gradeipk', "IPKMin <= '$khslalu[IPS]' and '$khslalu[IPS]' <= IPKMax and KodeID", KodeID, 'SKSMin');
			if($_SKS > $khslalu[SKS]) $oke = false;
			else $oke = true;
			
			$MsgList[] = "Jumlah SKS Tahun Mencukupi($_SKS ~ $khslalu[SKS])? $oke";
		  }
		}
		
		// Apakah Grade IPK-nya OK?
		if ($oke) {
		  if ($w1['GunakanGradeIPK'] == 'Y') {
			if(!empty($khslalu))
			{   $_GradeIPK = AmbilOneField('gradeipk', "IPKMin <= $khslalu[IPS] and $khslalu[IPS] <= IPKMax and KodeID", KodeID, 'GradeIPK');
				$pos = strpos($w1['GradeIPK'], ".".$_GradeIPK.".");
				$oke = $oke && !($pos === false);
				$MsgList[] = "Grade IPK OK ($_GradeIPK ~ $w1[GradeIPK])? $oke";
			}
			else
			{	$oke = false;
			}		
		  }
		}
		
		// Apakah dimulai pada sesi ini?
		if ($oke) {
		  if ($w1['MulaiSesi'] <= $khs['Sesi'] or $w1['MulaiSesi'] == 0) $oke = true;
		  else $oke = false;
		  $MsgList[] = "Mulai pada sesi ini ($khs[Sesi] ~ $w1[MulaiSesi])? $oke";
		}
		
		// Apakah ada setup berapa kali ambil?
		if ($oke && $w1['KaliSesi'] > 0) {
		  $_kali = AmbilOneField('bipotmhsw', "MhswID='$MhswID' and NA='N' and PMBMhswID=1 and BIPOTNamaID='$w1[BIPOTNamaID]' and TambahanNama='$w[MKKode] - $w[Nama] - $w[SKS] SKS' and KodeID",
			KodeID, "count(BIPOTMhswID)")+0;
		  $oke = $_kali < $w1['KaliSesi'];
		  $MsgList[] = "Berapa Kali Ambil - ($_kali ~ $w1[KaliSesi])? $oke";
		}
		
		if($oke) $MsgList[] = "ALL OK! GO FOR IT!";

		// Simpan data
		if ($oke) {
		 
			$ada = AmbilOneField('bipotmhsw',
				"KodeID='".KodeID."' and MhswID = '$mhsw[MhswID]'
				and NA = 'N'
				and PMBMhswID = 1
				and TahunID='$khs[TahunID]'
				and BIPOTNamaID = '$w1[BIPOTNamaID]'
				and TambahanNama='Remedial: $w[MKKode] - $w[Nama] - $w[SKS] SKS'
				and BIPOT2ID",
				$w1['BIPOT2ID'], "BIPOTMhswID") +0;
			
			if ($ada == 0) {
			  // Simpan
			  $Nama = AmbilOneField('bipotnama', 'BIPOTNamaID', $w1['BIPOTNamaID'], 'Nama');
			  if($w1['PerSKS'] == 'Y') $Jumlah = $w['SKS'];
			  else $Jumlah = 1;
			  $Besar = $w1['Jumlah'];
			  
			  $s2 = "insert into bipotmhsw
				(KodeID, COAID, PMBMhswID, MhswID, TahunID,
				BIPOT2ID, BIPOTNamaID, TambahanNama, Nama, TrxID, 
				Jumlah, Besar, Dibayar,
				Catatan, NA,
				LoginBuat, TanggalBuat)
				values
				('".KodeID."', '$w1[COAID]', 1, '$mhsw[MhswID]', '$khs[TahunID]',
				'$w1[BIPOT2ID]', '$w1[BIPOTNamaID]', 'Remedial: ".$w['MKKode']." - ".$w['Nama']." - ".$w['SKS']." SKS', '$Nama', '$w1[TrxID]', 
				'$Jumlah', '$Besar', 0,
				'Auto', 'N',
				'$_SESSION[_Login]', now())";
			  $r2 = mysqli_query($koneksi, $s2);
		    }
	     }
	  }
  }
  
  // Masukkan Biaya Khusus dari tiap mata kuliah remedial
  $s = "select k.MKKode, k.Nama, k.SKS, j.BiayaKhusus, j.Biaya, j.NamaBiaya 
			from krsremedial k left outer join jadwalremedial j on k.JadwalRemedialID=j.JadwalRemedialID and j.KodeID='".KodeID."'
			where k.MhswID='$MhswID' and k.TahunID='$_SESSION[TahunID]' and j.BiayaKhusus='Y' and k.KodeID='".KodeID."'";
  $r = mysqli_query($koneksi, $s);
  while($w = mysqli_fetch_array($r))			  
  {	$ada = AmbilOneField('bipotmhsw',
	"KodeID='".KodeID."' and MhswID = '$mhsw[MhswID]'
	and NA = 'N'
	and PMBMhswID = 1
	and TahunID='$khs[TahunID]' 
	and Nama='$w[NamaBiaya]'
	and TambahanNama='Remedial: $w[MKKode] - $w[Nama] - $w[SKS] SKS'
	and BIPOT2ID",
	0, "BIPOTMhswID") +0;
	
	if ($ada == 0) {
	  // Simpan
	  
	  $s2 = "insert into bipotmhsw
		(KodeID, COAID, PMBMhswID, MhswID, TahunID,
		BIPOT2ID, BIPOTNamaID, Nama, TambahanNama, TrxID, 
		Jumlah, Besar, Dibayar,
		Catatan, NA,
		LoginBuat, TanggalBuat)
		values
		('".KodeID."', '', 1, '$mhsw[MhswID]', '$khs[TahunID]',
		0, 0, '$w[NamaBiaya]', 'Remedial: $w[MKKode] - $w[Nama] - $w[SKS] SKS', 1, 
		1, '$w[Biaya]', 0,
		'Biaya Khusus', 'N',
		'$_SESSION[_Login]', now())";
	  $r2 = mysqli_query($koneksi, $s2);
	}
  }
  // Uncomment lines below to print debugging messages
  /*echo "COUNT: ".count($MsgList);
  if(!empty($MsgList))
	{	foreach($MsgList as $Msg)
		{	echo "$Msg<br>";
		}
	}*/
	

  
  HitungUlangBIPOTMhsw($MhswID, $TahunID);
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=&MhswID=$MhswID&TahunID=$TahunID", 10);
  
}
function TampilkanBayarMhsw($mhsw, $khs) {
		global $koneksi;
  $s = "select bm.*, format(bm.TrxID * bm.Jumlah, 0) as JML,
    date_format(bm.Tanggal, '%d-%m-%Y') as TGL
    from bayarmhsw bm
    where bm.KodeID = '".KodeID."'
      and bm.NA = 'N'
      and bm.TahunID = '$khs[TahunID]'
      and bm.MhswID = '$mhsw[MhswID]'
      and bm.PMBMhswID = 1
    order by bm.Tanggal";
  $r = mysqli_query($koneksi, $s); $n = 0;
  //echo "<pre>$s</pre>";
  echo "
  <table id='example' class='table table-sm table-striped' style='width:80%' align='center'>";
  echo "<tr><th class=ttl colspan=8>Daftar Pembayaran & Penarikan</th></tr>";
  echo "<tr style='background:purple;color:white'>
    <th class=ttl width=20>#</th>
    <th class=ttl width=150 style='text-align:center'>Tanggal</th>
    <th class=ttl width=190 style='text-align:center'>Nomor Bukti</th>
    <th class=ttl width=150 style='text-align:right'>Jumlah</th>
    <th class=ttl>Keterangan</th>
    <th class=ttl>Print Bukti</th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    $del = ($_SESSION['_LevelID'] == 1)? "<a href='#' onClick=\"javascript:ByrDel('$w[BayarMhswID]', '$mhsw[MhswID]', '$khs[TahunID]')\"><i class='fa fa-trash'></i></a>" : '&nbsp;';
    echo "<tr>
      <td class=inp>$n</td>
      <td class=ul align=center>$w[TGL]</td>
      <td class=ul align=center>$w[BayarMhswID]</td>
      <td class=ul align=right>$w[JML]</td>
      <td class=ul>$w[Keterangan]&nbsp;</td>
      <td class=ul1 align=center width=120>
        <a href='#' onClick=\"javascript:CetakBPM('$w[BayarMhswID]', $w[TrxID])\"><i class='fa fa-print'></i></a>
        </td>
      </tr>";
  }
  echo "</table></div>
  </div>
  </div></p>";
}
function HapusBayar() {
		global $koneksi;
  $BayarMhswID = sqling($_REQUEST['BayarMhswID']);
  $MhswID = sqling($_REQUEST['MhswID']);
  $TahunID = sqling($_REQUEST['TahunID']);
  // Hapus header
  /*
  $s = "delete from bayarmhsw
    where BayarMhswID = '$BayarMhswID' 
      and MhswID = '$MhswID'
      and KodeID = '".KodeID."' "; */
  $s = "update bayarmhsw
    set NA = 'Y'
    where BayarMhswID = '$BayarMhswID'
      and MhswID = '$MhswID'
      and KodeID = '".KodeID."' ";
  $r = mysqli_query($koneksi, $s);
  // Hapus detailnya
  $s = "update bayarmhsw2
    set NA = 'Y'
    where BayarMhswID = '$BayarMhswID' ";
  $r = mysqli_query($koneksi, $s);
  
  HitungUlangBIPOTMhsw($MhswID, $TahunID);
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=", 100);
}
function BayarMhswScript() {
  echo <<<SCR
  <script>
  function InquirySemesterMhsw(mhswid) {
    lnk = "inq/mhsw_semester.php?mhswid=" + mhswid;
    win2 = window.open(lnk, "", "width=700, height=500, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
SCR;
}
function BIPOTScript() {
  RandomStringScript();
  echo <<<SCR
  <script>
  function BIPOTDELCONF(id, mhswid, tahunid) {
    if (confirm("Benar Anda akan menghapus BIPOT ini?")) {
      window.location="?ndelox=$_SESSION[ndelox]&lungo=HapusBIPOT&BypassMenu=1&_BIPOTMhswID="+id+"&MhswID="+mhswid+"&TahunID="+tahunid;
    }
  }
  function BIPOTDELALLCONF(mhswid, tahunid) {
    if (confirm("Benar Anda akan menghapus semua biaya di bawah ini? Biaya yang sudah terbayar tidak akan dihapus.")) {
      window.location="?ndelox=$_SESSION[ndelox]&lungo=HapusSemuaBIPOT&BypassMenu=1&MhswID="+mhswid+"&TahunID="+tahunid;
    }
  }
  function BIPOTEdit(mhswid, tahunid, md, id) {
    _rnd = randomString();
    lnk = "$_SESSION[ndelox].bipotedit.php?MhswID="+mhswid+"&TahunID="+tahunid+"&md="+md+"&id="+id+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=400, height=200, left=550, top=250, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function ByrEdit(mhswid, khsid, md, bayarid) {
    _rnd = randomString();
    lnk = "$_SESSION[ndelox].bayar.php?MhswID="+mhswid+"&KHSID="+khsid+"&md="+md+"&BayarID="+bayarid+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=600, height=500, left=450, top=200, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function fnTarikan(mhswid, khsid, md, bayarid) {
    _rnd = randomString();
    lnk = "$_SESSION[ndelox].tarik.php?MhswID="+mhswid+"&KHSID="+khsid+"&md="+md+"&BayarID="+bayarid+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=600, height=400, left=500, top=150, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function fnHistoryBeasiswa(mhswid, khsid, md) {
    _rnd = randomString();
    lnk = "$_SESSION[ndelox].historybeasiswa.php?MhswID="+mhswid+"&KHSID="+khsid+"&md="+md+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=700, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function CetakBPM(id, trx) {
    _rnd = randomString();
    lnk = "$_SESSION[ndelox].bpm.php?id="+id+"&_rnd="+_rnd+"&trx="+trx;
    win2 = window.open(lnk, "", "width=600, height=400, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function ByrDel(BayarMhswID, MhswID, TahunID) {
    if (confirm("Benar Anda akan menghapus pembayaran ini? Mungkin daftar BIPOT di atas menjadi tidak balance lagi.")) {
      window.location="?ndelox=$_SESSION[ndelox]&lungo=HapusBayar&BayarMhswID="+BayarMhswID+"&MhswID="+MhswID+"&TahunID="+TahunID;
    }
  }
  </script>
SCR;
}
?>
