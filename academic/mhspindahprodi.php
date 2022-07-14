<?php
//error_reporting(0);
$crMhswID = GainVariabelx('crMhswID');
$crNamaMhsw = GainVariabelx('crNamaMhsw');
$_mhswdropinPage = GainVariabelx('_mhswdropinPage');

TitleApps("MAHASISWA PINDAH PRODI / PROGRAM");
$lungo = (empty($_REQUEST['lungo']))? 'CariMhsw' : $_REQUEST['lungo'];
$lungo();

function HeaderCariMhsw() {
  echo <<<ESD
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:40%' align='center'>
  <form name='frmCariMhsw' action='?' method=POST>
  <tr>
      <td class=ul>
        <input type=text placeholder='NIM' name='crMhswID' value='$_SESSION[crMhswID]' size=20 maxlength=50 />
      </td>
      <td class=ul nowrap>
        <input type=text placeholder='Nama Mahasiswa' name='crNamaMhsw' value='$_SESSION[crNamaMhsw]' size=30 maxlength=50 /> 
        <input style='margin-top:-5px' class='btn btn-success btn-xs' type=submit name='btnCari' value='Lihat Data' />
        <input style='margin-top:-5px' class='btn btn-primary btn-xs' type=button name='btnReset' value='Reset' onClick="location='?ndelox=$_SESSION[ndelox]&lungo=&crMhswID=&crNamaMhsw='" />
      </td>
  </tr>
  </form>
  </table>
  
</div>
</div>
</div>
ESD;
}
function CariMhsw() {
	global $koneksi;
  HeaderCariMhsw();
  TampilkanFotoScript();
  $whr = array();
  if ($_SESSION['crMhswID'] != '') $whr[] = "and m.MhswID like '$_SESSION[crMhswID]%'";
  if ($_SESSION['crNamaMhsw'] != '') $whr[] = "and m.Nama like '%$_SESSION[crNamaMhsw]%'";
  $strwhr = implode("\n", $whr);
  

  $s = "select m.MhswID, m.Nama, m.StatusAwalID, m.StatusMhswID,
    m.Kelamin,
    m.Telepon, m.Handphone, m.Email, 
    if (m.Foto is NULL or m.Foto = '', 'img/tux001.jpg', m.Foto) as _Foto,
    if (m.StatusAwalID = 'D', concat('<a href=\'?ndelox=$_SESSION[ndelox]&lungo=fnKonversi&MhswID=', m.MhswID,'\'><img src=img/edit.png /></a>'), '&times;') as _Konversi,
    m.ProgramID, m.ProdiID, m.Alamat, m.Kota,
    prd.Nama as PRD, sm.Nama as SM, sm.Keluar, sa.Nama as SA
  from mhsw m
    left outer join prodi prd on m.ProdiID=prd.ProdiID
    left outer join statusmhsw sm on m.StatusMhswID=sm.StatusMhswID
    left outer join statusawal sa on m.StatusAwalID=sa.StatusAwalID
    where m.KodeID='".KodeID."'
    $strwhr $ord limit 50";
     $r = mysqli_query($koneksi, $s); 
	
	
echo"<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example1' class='table table-sm table-bordered table-striped' style='width:100%' align='center'>
    <thead>
	<tr style='background:purple;color:white'>
	<th class=ttl>No.</th>
    <th class=ttl>NIM</th>
	<th class=ttl>Pindahkan</th>
    <th class=ttl>Nama</th>
	 <th class=ttl>JKelamin</th>
    <th class=ttl>Program Studi</th>
    <th class=ttl>Status - Masuk</th>
    <th class=ttl>Handphone</th>
    <th class=ttl width=20>KonversiMK</th>
    </tr>
	 </thead>
	 <tbody>";
	while ($w = mysqli_fetch_array($r)) {
		$no++;
	echo"<tr>
    <td class=inp width=10><a name='$w[MhswID]'>$no</a></td>
    <td class=cna=Keluar= width=150 align=left>$w[MhswID]</td>
    <td class=cna=Keluar= nowrap><b> 
	  <a href='?ndelox=$_SESSION[ndelox]&lungo=fnPindahkan&MhswID=$w[MhswID]'>Prodi</a> -
	  <a href='?ndelox=$_SESSION[ndelox]&lungo=fnDropinProgram&MhswID=$w[MhswID]'>Program</a></b>
	</td>
	<td class=cna=Keluar= nowrap><b>$w[Nama]</b></td>
	<td>$w[Kelamin]</td>
    <td class=cna=Keluar=>$w[ProgramID] - $w[PRD]</td>
    <td class=cna=Keluar= width=120 align=center>$w[SM] - $w[SA]</td>
    <td class=cna=Keluar=>$w[Handphone]</td>
    <td class=cna=Keluar= align=center>$w[_Konversi]</td>
    </tr>";
	}
	echo"</tbody>
	</table>";
}


function TampilkanFotoScript() {
  echo <<<SCR
  <script>
  function TampilkanFoto(MhswID, Nama, Foto) {
    jQuery.facebox("<font size=+1>"+Nama+"</font> <sup>(" + MhswID + ")</sup><hr size=1 color=silver /><img src='"+Foto+"' />");
  }
  </script>
SCR;
}
function fnPindahkan() {
  $MhswID = sqling($_REQUEST['MhswID']);
  $mhsw = AmbilFieldx('mhsw', "MhswID = '$MhswID' and KodeID", KodeID, '*');
  $prd = AmbilOneField('prodi', "ProdiID = '$mhsw[ProdiID]' and KodeID", KodeID, "Nama");
  $prg = AmbilOneField('program', "ProgramID = '$mhsw[ProgramID]' and KodeID", KodeID, "Nama");
  $_mk = AmbilFieldx('krs', 'MhswID', $MhswID, "count(KRSID) as _JmlMK, sum(SKS) as _JmlSKS");
  $_smt = AmbilOneField('khs', 'MhswID', $MhswID, "max(Sesi)")+0;
  $sta = AmbilFieldx('statusmhsw', 'StatusMhswID', $mhsw['StatusMhswID'], 'Nama, Keluar');
  // Cek apakah mhsw sudah keluar?
  if ($sta['Keluar'] == 'Y')
    die(PesanError('Error',
      "Mahasiswa $mhsw[Nama] <sup>($MhswID)</sup> sudah keluar dengan status: $sta[Nama].<br />
      Mahasiswa tidak dapat pindah prodi lagin.
      <hr size=1 color=silver />
      <input class='btn btn-success btn-sm' type=button name='btnKembali' value='Kembali'
      onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />"));
  
  // Penasehat Akademik
  $stawal = AmbilOneField('statusawal', 'StatusAwalID', $mhsw['StatusAwalID'], 'Nama');
  if (empty($mhsw['PenasehatAkademik'])) {
    $pa = "&times; Belum diset";
  }
  else {
    $dsn = AmbilFieldx('dosen', "Login='$mhsw[PenasehatAkademik]' and KodeID", KodeID, "Nama, Gelar");
    $pa = "$dsn[Nama] <sup>$dsn[Gelar]</sup>";
  }
  $rowspan = 20;
  
  $_JmlSKS = $_mk['_JmlSKS']+0;
  $optprodi = AmbilCombo2('prodi', "concat(ProdiID, ' - ', Nama)", 'ProdiID', '', "KodeID='".KodeID."'", 'ProdiID');
  $optprogram = AmbilCombo2('program', "concat(ProgramID, ' - ', Nama)", 'ProgramID', '', "KodeID='".KodeID."'", 'ProgramID');
  CheckFormScript('ProdiID, ProgramID');
  echo <<<ESD
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:70%' align='center'>
  <tr style='background:purple;color:white'><td class=wrn width=1 rowspan=$rowspan></td>
      <td class=ul align=center colspan=4>
        Anda akan memindahkan mahasiswa berikut ini ke <b>prodi</b> lain.<br />
        Mohon untuk dicek detailnya sebelum melakukan proses.
      </td>
      <td class=wrn width=1 rowspan=$rowspan></td>
      </tr>
  <tr><td class=inp>NIM</td>
      <td class=ul><b>: $mhsw[MhswID]</td>
      <td class=inp>Nama Mahasiswa</td>
      <td class=ul><b>: $mhsw[Nama]&nbsp;</td>
      </tr>
  <tr><td class=inp>Program Studi</td>
      <td class=ul>: $prd <sup>$prg</sup></td>
      <td class=inp>Penasehat Akademik</td>
      <td class=ul>: $pa</td>
      </tr>
  <tr><td class=inp>Total MK</td>
      <td class=ul>: $_mk[_JmlMK] MK, Total SKS: $_JmlSKS, Semester: $_smt</td>
      <td class=inp>Status Mhsw</td>
      <td class=ul>: $sta[Nama] <sup>&minus; $stawal</sup></td>
      </tr>
  
  <form action='?' method=POST onSubmit='return CheckForm(this)'>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='lungo' value='fnProses' />
  <input type=hidden name='MhswID' value='$MhswID' />
  <input type=hidden name='BypassMenu' value='1' />
  
  <tr><th class=ttl colspan=4>Pindah Ke Prodi:</th></tr>
  <tr><td class=inp>Program Studi:</td>
      <td class=ul><select name='ProdiID'>$optprodi</select></td>
      <td class=inp>Program Pendidikan:</td>
      <td class=ul><select name='ProgramID'>$optprogram</select></td>
      </tr>
  <tr style='background:purple;color:white'><th class=ul colspan=4 align=center>Catatan:</th></tr>
  <tr><td class=ul colspan=4>
      <img src='img/warn.png' align=right />
      <ol><li>Status mahasiswa akan diset sebagai 'Keluar'.</li>
          <li>Mahasiswa akan dibuatkan NIM baru di prodi/program baru.</li>
          <li>Status mahasiswa di data baru diset sebagai 'Drop-in'.</li>
          <li>Setelah itu lakukan konversi MK di modul 'Prodi &raquo; Konversi Mhsw Pindahan'.</li>
      </td></tr>
  
  <tr><td class=ul colspan=4 align=center>
      <input class='btn btn-danger btn-sm' type=submit name='btnProses' value='Proses Pindah Prodi' />
      <input class='btn btn-primary btn-sm' type=button name='btnBatal' value='Batal Pindah' 
        onClick="location='?ndelox=$_SESSION[ndelox]&lungo='" />
      </td></tr>
  </form>
  </table>
  
</div>
</div>
</div>
  <p>
ESD;
}
function fnProses() {
  $MhswID = sqling($_REQUEST['MhswID']);
  $ProdiID = sqling($_REQUEST['ProdiID']);
  $ProgramID = sqling($_REQUEST['ProgramID']);
  
  $mhsw = AmbilFieldx('mhsw', "MhswID='$MhswID' and KodeID", KodeID, '*');
  // Cek apakah prodi-nya sama?
  if ($ProdiID == $mhsw['ProdiID'])
    die(PesanError('Error',
      "Anda tidak bisa memindahkan Mhsw: <b>$mhsw[nama]</b> <sup>($MhswID)</sup><br />
      ke Prodi yang sama ($ProdiID &raquo; $mhsw[ProdiID]).<br />
      Hubungi Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      <input type=button name='btnKembali' value='Kembali'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=fnPindahkan&MhswID=$MhswID'\" />
      <input type=button name='btnBatal' value='Batal'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />"));
  // Info sekali lagi
  $sta = AmbilOneField('statusmhsw', 'StatusMhswID', $mhsw['StatusMhswID'], 'Nama');
  $stawal = AmbilOneField('statusawal', 'StatusAwalID', $mhsw['StatusAwalID'], 'Nama');
  $TahunID = AmbilOneField('tahun', "ProgramID='$ProgramID' and ProdiID='$ProdiID' and KodeID",
    KodeID, 'TahunID');
  
 // CheckFormScript('TahunID');
  echo Info("Info",
    "<div class='card'>
    <div class='card-header'>
    <div class='table-responsive'>
	<table id='example' class='table table-sm table-stripedx' style='width:100%' align='center'>
    <form action='?' method=POST onSubmit='return CheckForm(this)'>
    <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
    <input type=hidden name='lungo' value='fnProsesnya' />
    <input type=hidden name='MhswID' value='$MhswID' />
    <input type=hidden name='ProdiID' value='$ProdiID' />
    <input type=hidden name='OldProdiID' value='$mhsw[ProdiID]' />
    <input type=hidden name='ProgramID' value='$ProgramID' />
    <input type=hidden name='OldProgramID' value='$mhsw[ProgramID]' />
    
    <tr style='background:purple;color:white'><td class=ul1 colspan=2 align=center>
        Anda akan memproses pemindahan program studi mhsw berikut:
        </td></tr>
    <tr><td class=inp width=180>NIM</td>
        <td class=ul1>: $MhswID</td>
        </tr>
    <tr><td class=inp>Nama</td>
        <td class=ul1>: $mhsw[Nama]</td>
        </tr>
    <tr><td class=inp>Status</td>
        <td class=ul1>: $sta <sup>$stawal</sup></td>
        </tr>
    <tr><td class=inp>Perpindahan</td>
        <td class=ul1>
        : $mhsw[ProdiID] <sup>$mhsw[ProgramID]</sup> &raquo; $ProdiID <sup>$ProgramID</sup>
        </td></tr>
    <tr><td class=inp>Tahun Akademik</td>
        <td class=ul1>
        <input type=text name='TahunID' value='$TahunID' size=5 maxlength=5 />
        </td></tr>
    <tr><td class=ul1 colspan=2 align=center>
        <input class='btn btn-danger btn-sm' type=submit name='btnProses' value='Proses' />
        <input class='btn btn-success btn-sm' type=button name='btnBatal' value='Batal'
          onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />
        </td></tr>
    </form>
    </table>
    
</div>
</div>
</div>");
}
function fnProsesnya() {
	global $koneksi;
  $MhswID = sqling($_REQUEST['MhswID']);
  $ProdiID = sqling($_REQUEST['ProdiID']);
  $ProgramID = sqling($_REQUEST['ProgramID']);
  $TahunID = sqling($_REQUEST['TahunID']);
  // Cek Tahun
  $ada = AmbilFieldx('tahun', "ProdiID='$ProdiID' and ProgramID='$ProgramID' and KodeID",
    KodeID, '*');
  if (empty($ada))
    die(PesanError("Error - $TahunID",
    "Kalendar akademik dengan kode: <b>$TahunID</b> tidak ditemukan<br />
    untuk Program Studi: $ProdiID dan Program Pendidikan: $ProgramID.<br />
    Hubungi Kepala BAA untuk memastikan tahun akademik yang aktif.<br />
    Atau hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    <input class='btn btn-success btn-sm' type=button name='btnKembali' value='Kembali'
      onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=fnPindahkan&MhswID=$MhswID'\" />
    <input class='btn btn-warning btn-sm' type=button name='btnBatal' value='Batal'
      onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />")); 
  
  $mhsw = AmbilFieldx('mhsw', "MhswID='$MhswID' and KodeID", KodeID, '*');
  $baru = $mhsw;
  $baru['ProdiID'] = $ProdiID;
  $baru['ProgramID'] = $ProgramID;
  $baru['StatusAwalID'] = 'D';
  // Edit: Ilham
  $NIM = (AmbilOneField('prodi', 'ProdiID', $mhsw['ProdiID'], 'GunakanNIMSementara')=='Y')? 
	  GetNextNIM($TahunID, $baru) : GetNextNIMSementara($TahunID, $baru);
  $BIPOTID = AmbilOneField('bipot', "ProdiID='$ProdiID' and ProgramID='$ProgramID' and Def='Y' and KodeID",
    KodeID, 'BIPOTID');
  $BatasStudi = HitungBatasStudi($TahunID, $ProdiID);
  // Salin data mhsw
  $s = "insert into mhsw
    (MhswID, Login, LevelID, `Password`,
    KDPIN, PMBID, TahunID, KodeID, BIPOTID,
    Autodebet, Nama, Foto,
    StatusAwalID, StatusMhswID, ProgramID, ProdiID,
    PenasehatAkademik, Kelamin, WargaNegara, Kebangsaan,
    TempatLahir, TanggalLahir, Agama, StatusSipil,
    TinggiBadan, BeratBadan,
    Alamat, Kota, RT, RW, KodePos, Propinsi, Negara, Telephone, Handphone, Email,
    AlamatAsal, KotaAsal, RTAsal, RWAsal, KodePosAsal, PropinsiAsal, NegaraAsal, TeleponAsal,
    AnakKe, JumlahSaudara,
    NamaAyah, AgamaAyah, PendidikanAyah, PekerjaanAyah, HidupAyah,
    NamaIbu, AgamaIbu, PendidikanIbu, PekerjaanIbu, HidupIbu,
    AlamatOrtu, KotaOrtu, RTOrtu, RWOrtu, KodePosOrtu, PropinsiOrtu, NegaraOrtu, TeleponOrtu,
    HandphoneOrtu, EmailOrtu,
    PendidikanTerakhir, AsalSekolah, AsalSekolah1, 
    AlamatSekolah, KotaSekolah, JurusanSekolah, NilaiSekolah,
    TahunLulus, IjazahSekolah,
    AsalPT, MhswIDAsalPT, ProdiAsalPT, LulusAsalPT, TglLulusAsalPT, IPKAsalPT,
    BatasStudi, NA,
    NamaBank, NomerRekening,
    LoginBuat, TanggalBuat)
    values
    ('$NIM', '$NIM', 120, LEFT(PASSWORD('$NIM'), 10),
    '$baru[KDPIN]', '$MhswID', '$TahunID', '".KodeID."', '$BIPOTID',
    '$baru[Autodebet]', '$baru[Nama]', '$mhsw[Foto]',
    '$baru[StatusAwalID]', '$baru[StatusMhswID]', '$ProgramID', '$ProdiID',
    '$baru[PenasehatAkademik]', '$baru[Kelamin]', '$baru[WargaNegara]', '$baru[Kebangsaan]',
    '$baru[TempatLahir]', '$baru[TanggalLahir]', '$baru[Agama]', '$baru[StatusSipil]',
    '$baru[TinggiBadan]', '$baru[BeratBadan]',
    '$baru[Alamat]', '$baru[Kota]', '$baru[RT]', '$baru[RW]', '$baru[KodePos]', '$baru[Propinsi]', '$baru[Negara]', '$baru[Telephone]', '$baru[Handphone]', '$baru[Email]',
    '$baru[AlamatAsal]', '$baru[KotaAsal]', '$baru[RTAsal]', '$baru[RWAsal]', '$baru[KodePosAsal]', '$baru[PropinsiAsal]', '$baru[NegaraAsal]', '$baru[TeleponAsal]',
    '$baru[AnakKe]', '$baru[JumlahSaudara]',
    '$baru[NamaAyah]', '$baru[AgamaAyah]', '$baru[PendidikanAyah]', '$baru[PekerjaanAyah]', '$baru[HidupAyah]',
    '$baru[NamaIbu]', '$baru[AgamaIbu]', '$baru[PendidikanIbu]', '$baru[PekerjaanIbu]', '$baru[HidupIbu]',
    '$baru[AlamatOrtu]', '$baru[KotaOrtu]', '$baru[RTOrtu]', '$baru[RWOrtu]', '$baru[KodePosOrtu]', '$baru[PropinsiOrtu]', '$baru[NegaraOrtu]', '$baru[TeleponOrtu]',
    '$baru[HandphoneOrtu]', '$baru[EmailOrtu]',
    '$baru[PendidikanTerakhir]', '$baru[AsalSekolah]', '$baru[AsalSekolah1]',
    '$baru[AlamatSekolah]', '$baru[KotaSekolah]', '$baru[JurusanSekolah]', '$baru[NilaiSekolah]',
    '$baru[TahunLulus]', '$baru[IjazahSekolah]',
    '$baru[AsalPT]', '$baru[MhswIDAsalPT]', '$baru[ProdiAsalPT]', '$baru[LulusAsalPT]', '$baru[TglLulusAsalPT]', '$baru[IPKAsalPT]',
    '$BatasStudi', 'N',
    '$baru[NamaBank]', '$baru[NomerBank]',
    '$_SESSION[_Login]', now())";
  $r = mysqli_query($koneksi, $s);
  // Non aktifkan data mhsw lama --> status: Keluarkan
  $sk = "update mhsw 
    set StatusMhswID = 'D', 
        Keluar = 'Y', TahunKeluar = '$TahunID',
        CatatanKeluar = 'Pindah Prodi ke: $ProdiID, Program: $ProgramID'
    where MhswID = '$MhswID' and KodeID = '".KodeID."' ";
  $rk = mysqli_query($koneksi, $sk);
  // Kembali
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=&crNamaMhsw=$baru[Nama]", 1);
}
function fnKonversi() {
  $MhswID = sqling($_REQUEST['MhswID']);
  $mhsw = AmbilFieldx('mhsw', "MhswID='$MhswID' and KodeID", KodeID, '*');
  
  TampilkanHeaderMhsw($mhsw);
}
function TampilkanHeaderMhsw($mhsw) {
  $Prodi = AmbilOneField('prodi', "ProdiID='$mhsw[ProdiID]' and KodeID", KodeID, 'Nama');
  $Program = AmbilOneField('program', "ProgramID='$mhsw[ProgramID]' and KodeID", KodeID, 'Nama');
  $sta = AmbilOneField('statusmhsw', 'StatusMhswID', $mhsw['StatusMhswID'], 'Nama');
  $stawal = AmbilOneField('statusawal', 'StatusAwalID', $mhsw['StatusAwalID'], 'Nama');
  $h = 500;
  echo <<<ESD
  <table class=box cellspacing=1 align=center width=800>
  <tr><td class=inp width=80>NIM/NPM:</td>
      <td class=ul>$mhsw[MhswID]</td>
      <td class=inp width=80>Nama Mhsw:</td>
      <td class=ul>$mhsw[Nama]</td>
      <td class=inp width=80>Status:</td>
      <td class=ul>$sta <sup>$stawal</sup>
      </tr>
  <tr><td class=inp>Program Studi:</td>
      <td class=ul>$Prodi</td>
      <td class=inp>Program:</td>
      <td class=ul>$Program</td>
      <td class=ul colspan=2>
      <input type=button name='btnKembali' value='Kembali'
        onClick="location='?ndelox=$_SESSION[ndelox]&lungo='" />
      <input type=button name='btnRefresh' value='Refresh'
        onClick="location='?ndelox=$_SESSION[ndelox]&lungo=fnKonversi&MhswID=$mhsw[MhswID]'" />
      </td></tr>
  </table>
  <table class=box cellspacing=1 align=center width=800>
  <tr><td class=ul width=50%>
      <iframe id='FRAMEDETAIL1' src="$_SESSION[ndelox].krs.php?MhswID=$mhsw[MhswID]" frameborder=0 width=100% height=$h>
      </iframe>
      </td>
      <td class=ul width=50%>
      <iframe id='FRAMEDETAIL2' src="$_SESSION[ndelox].oldkrs.php?MhswID=$mhsw[MhswID]" frameborder=0 width=100% height=$h>
      </iframe>
      </td>
      </tr>
  </table>
ESD;
}

function fnDropinProgram() {
  $MhswID = sqling($_REQUEST['MhswID']);
  $mhsw = AmbilFieldx('mhsw', "MhswID = '$MhswID' and KodeID", KodeID, '*');
  $prd = AmbilOneField('prodi', "ProdiID = '$mhsw[ProdiID]' and KodeID", KodeID, "Nama");
  $prg = AmbilOneField('program', "ProgramID = '$mhsw[ProgramID]' and KodeID", KodeID, "Nama");
  $_mk = AmbilFieldx('krs', 'MhswID', $MhswID, "count(KRSID) as _JmlMK, sum(SKS) as _JmlSKS");
  $_smt = AmbilOneField('khs', 'MhswID', $MhswID, "max(Sesi)")+0;
  $sta = AmbilFieldx('statusmhsw', 'StatusMhswID', $mhsw['StatusMhswID'], 'Nama, Keluar');
  // Cek apakah mhsw sudah keluar?
  if ($sta['Keluar'] == 'Y')
    die(PesanError('Error',
      "Mahasiswa $mhsw[Nama] <sup>($MhswID)</sup> sudah keluar dengan status: $sta[Nama].<br />
      Mahasiswa tidak dapat pindah prodi lagin.
      <hr size=1 color=silver />
      <input class='btn btn-success btn-xs' type=button name='btnKembali' value='Kembali'
      onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />"));
  
  // Penasehat Akademik
  $stawal = AmbilOneField('statusawal', 'StatusAwalID', $mhsw['StatusAwalID'], 'Nama');
  if (empty($mhsw['PenasehatAkademik'])) {
    $pa = "&times; Belum diset";
  }
  else {
    $dsn = AmbilFieldx('dosen', "Login='$mhsw[PenasehatAkademik]' and KodeID", KodeID, "Nama, Gelar");
    $pa = "$dsn[Nama] <sup>$dsn[Gelar]</sup>";
  }
  $rowspan = 20;
  
  $_JmlSKS = $_mk['_JmlSKS']+0;
  $optprodi = AmbilCombo2('prodi', "concat(ProdiID, ' - ', Nama)", 'ProdiID', '', "KodeID='".KodeID."'", 'ProdiID');
  $optprogram = AmbilCombo2('program', "concat(ProgramID, ' - ', Nama)", 'ProgramID', '', "KodeID='".KodeID."'", 'ProgramID');
  
  $buttons = '';
  $programs = "select * from program where KodeID='".KodeID."' and ProgramID!='$mhsw[ProgramID]' and NA='N' order by Nama";
  $programr = mysqli_query($koneksi, $programs);
  while($programw = mysqli_fetch_array($programr))
	$buttons .= "<input type=button name='$programw[ProgramID]' value='Pindahkan ke Program $programw[Nama]' onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=fnProsesProgram&MhswID=$MhswID&ProgramID=$programw[ProgramID]'\" />";
  
  CheckFormScript('ProdiID, ProgramID');
  echo <<<ESD
  <table id='example' class='table table-sm table-striped' style='width:80%' align='center'>
  <tr><td class=wrn width=1 rowspan=$rowspan></td>
      <td class=ul align=center colspan=4>
        Anda akan memindahkan mahasiswa berikut ini ke <b>program</b> lain.<br />
        Mohon untuk dicek detailnya sebelum melakukan proses.
      </td>
      <td class=wrn width=1 rowspan=$rowspan></td>
      </tr>
  <tr><td class=inp>NIM</td>
      <td class=ul><b>: $mhsw[MhswID]</td>
      <td class=inp>Nama Mahasiswa</td>
      <td class=ul><b>: $mhsw[Nama]&nbsp;</td>
      </tr>
  <tr><td class=inp>Program Studi</td>
      <td class=ul>: $prd <sup>$prg</sup></td>
      <td class=inp>Penasehat Akd</td>
      <td class=ul>: $pa</td>
      </tr>
  <tr><td class=inp>Total MK</td>
      <td class=ul>: $_mk[_JmlMK] MK, Total SKS: $_JmlSKS, Semester: $_smt</td>
      <td class=inp>Status Mahasiswa</td>
      <td class=ul>: $sta[Nama] <sup>&minus; $stawal</sup></td>
      </tr>
  
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='lungo' value='fnProses' />
  <input type=hidden name='MhswID' value='$MhswID' />
  <input type=hidden name='BypassMenu' value='1' />
  
  <tr><td class=ul colspan=4 align=center>
      $buttons
      <input class='btn btn-warning btn-sm' type=button name='btnBatal' value='Batal Pindah' 
        onClick="location='?ndelox=$_SESSION[ndelox]&lungo='" />
      </td></tr>
  </form>
  </table>
  <p>
ESD;
}
function fnProsesProgram() {
	global $koneksi;
  $MhswID = sqling($_REQUEST['MhswID']);
  $ProgramID = sqling($_REQUEST['ProgramID']);
  
  $mhsw = AmbilFieldx('mhsw', "MhswID='$MhswID' and KodeID", KodeID, '*');
  // Cek apakah prodi-nya sama?
  if ($ProgramID == $mhsw['ProgramID'])
    die(PesanError('Error',
      "Anda tidak bisa memindahkan Mhsw: <b>$mhsw[nama]</b> <sup>($MhswID)</sup><br />
      ke Program yang sama ($ProgramID &raquo; $mhsw[ProgramID]).<br />
      Hubungi Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      <input class='btn btn-success btn-sm' type=button name='btnKembali' value='Kembali'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=fnDropinProgram&MhswID=$MhswID'\" />
      <input type=button name='btnBatal' value='Batal'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />"));
  // Info sekali lagi
  $sta = AmbilOneField('statusmhsw', 'StatusMhswID', $mhsw['StatusMhswID'], 'Nama');
  $stawal = AmbilOneField('statusawal', 'StatusAwalID', $mhsw['StatusAwalID'], 'Nama');
  $TahunID = AmbilOneField('khs', "MhswID='$MhswID' and Sesi=(select max(Sesi) from khs where MhswID='$MhswID' and KodeID='".KodeID."') and KodeID",
    KodeID, 'TahunID');
  
  CheckFormScript('TahunID');
  echo 
    "<table class=bsc cellspacing=1 width=500>
    <form action='?' method=POST onSubmit='return CheckForm(this)'>
    <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
    <input type=hidden name='lungo' value='fnProsesnyaProgram' />
    <input type=hidden name='MhswID' value='$MhswID' />
	<input type=hidden name='TahunID' value='$TahunID' />
    <input type=hidden name='ProgramID' value='$ProgramID' />
    <input type=hidden name='OldProgramID' value='$mhsw[ProgramID]' />
    
	<tr><th class=ttl colspan=4>Info</th></tr>
    <tr><td class=ul1 colspan=4 align=center>
        Anda akan memproses pemindahan program mhsw berikut:
        </td></tr>
    <tr><td class=inp width=100>NIM/NPM:</td>
        <td class=ul1>$MhswID</td>
        <td class=inp width=100>Nama:</td>
        <td class=ul1>$mhsw[Nama]</td>
        </tr>
    <tr><td class=inp>Status:</td>
        <td class=ul1>$sta <sup>$stawal</sup></td>
		<td class=inp>Tahun Akademik:</td>
        <td class=ul1>$TahunID
        <input type=hidden name='TahunID' value='$TahunID'/>
        </td></tr>
    <tr><td class=inp>Perpindahan:</td>
        <td class=ul1>
        $mhsw[ProdiID] <sup>$mhsw[ProgramID] &raquo; $ProgramID</sup>
        </td></tr>
	<tr size=1 bgcolor=silver><td colspan=5></td></tr>
	";

	$s = "select k.KRSID, k.MKID, k.MKKode, k.Nama, k.TahunID, j.HariID, left(j.JamMulai, 5) as _JM, left(j.JamSelesai, 5) as _JS 
			from krs k left outer join jadwal j on k.JadwalID=j.JadwalID and j.KodeID='".KodeID."'
						left outer join jenisjadwal jj on jj.JenisJadwalID=j.JenisJadwalID
			where k.MhswID='$MhswID' and k.Final='N' and jj.Tambahan='N' and k.KodeID='".KodeID."'
			order by k.MKKode";
	$r = mysqli_query($koneksi, $s);
	$n = mysqli_num_rows($r);
	
	if($n == 0)
	{	echo "<tr><td colspan=5 align=center>Mahasiswa tidak memiliki mata kuliah yang dapat dijadwalkan untuk Program $ProgramID.<br>
				Mahasiswa dapat dipindahkan dan anda dapat menset mata kuliah di penjadwalan kuliah seperti biasa</td></tr>";
	}
	else
	{	$count = 0; 
		echo "<tr><td colspan=5 align=center><font color=red>Catatan: Untuk dapat memproses pemindahan mahasiswa ini, SETIAP penjadwalan kuliah yang BELUM di-finalisasi harus memiliki jadwal di program yang baru.</td></tr>";
		while($w = mysqli_fetch_array($r))
		{	$count++; 
			echo "<tr><td colspan=2>$w[Nama]<font size=1 color=teal>($w[MKKode])</font></br>
									<div align=right><font size=1 color=gray>".UbahKeHariIndonesia($w[HariID])." $w[_JM]&rarr;$w[_JS]</font></div>
									<input type=hidden name='KRS[]' value='$w[KRSID]'</td>";
			
			$s1 = "select j.JadwalID, j.MKID, j.MKKode, j.Nama, j.HariID, j.RuangID, left(j.JamMulai, 5) as _JM, left(j.JamSelesai, 5) as _JS, 
							j.Kapasitas, j.JumlahMhsw 
						from jadwal j
						where j.ProdiID='$mhsw[ProdiID]' and j.ProgramID='$ProgramID' and j.MKID=$w[MKID] and j.TahunID='$TahunID' and j.KodeID='".KodeID."'
						order by j.HariID, j.JamMulai, j.JamSelesai";
			$r1 = mysqli_query($koneksi, $s1);
			$JCount = mysqli_num_rows($r1);
			$countkrs = 0;
			$w1 = mysqli_fetch_array($r1);
			$da = ($w1[JumlahMhsw] >= $w1[Kapasitas])? 'disabled=true' : '';
			if(!empty($w1))
				echo "<td colspan=2>&raquo;<input type=checkbox id='$w1[MKKode]$countkrs' name='Pilihan[]' value='$w1[JadwalID]' $da onClick=\"EmptyOthers('$w1[MKKode]', '$countkrs', '$JCount')\">
								$w1[Nama]<font size=1 color=teal>($w1[MKKode])</font></br>
									<div align=right><font size=1 color=gray>$w1[RuangID], ".UbahKeHariIndonesia($w1[HariID])." $w1[_JM]&rarr;$w1[_JS]</font> 
													 <font size=1 color=teal>Quota: $w1[JumlahMhsw] / $w1[Kapasitas]</font> </div>";
			else echo "<td colspan=2 align=center><b>- Tidak ada penjadwalan pada Program $ProgramID -</b></td>";
			
			while($w1 = mysqli_fetch_array($r1))
			{	$countkrs++; 
				$da = ($w1[JumlahMhsw] >= $w1[Kapasitas])? 'disabled=true' : '';
				echo "<hr size=1 color=silver>
						  &raquo;<input type=checkbox id='$w1[MKKode]$countkrs' name='Pilihan[]' value='$w1[JadwalID]' $da onClick=\"EmptyOthers('$w1[MKKode]', '$countkrs', '$JCount')\">
								$w1[Nama]<font size=1 color=teal>($w1[MKKode])</font></br>
									<div align=right><font size=1 color=gray>$w1[RuangID], ".UbahKeHariIndonesia($w1[HariID])." $w1[_JM]&rarr;$w1[_JS]</font> 
													 <font size=1 color=teal>Quota: $w1[JumlahMhsw] / $w1[Kapasitas]</font></div>";
			
			}
			echo "</td></tr>";
			echo "<tr><td colspan=4><hr size=1 color=silver></td></tr>";
		}
	}
	
	echo "<tr size=1 bgcolor=silver><td colspan=4></td></tr>
    <tr><td class=ul1 colspan=4 align=center>
        <input type=submit name='btnProses' value='Proses' />
        <input type=button name='btnBatal' value='Batal'
          onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />
        </td></tr>
    </form>
    </table>";
	PilihKRSBaruScript();
}
function fnProsesnyaProgram()

{	
global $koneksi;
$MhswID = sqling($_REQUEST['MhswID']);
	$ProgramID = sqling($_REQUEST['ProgramID']);
	$TahunID = sqling($_REQUEST['TahunID']);
	$KRS = $_REQUEST['KRS'];
	$Pilihan = $_REQUEST['Pilihan'];
	
	// Cek Apa Semua KRS Baru ada pasangannya pada Program yang baru
	if(!empty($KRS))
	{
		$matched = array();
		
		foreach($KRS as $perkrs)
		{	$mkkrs = AmbilOneField('krs', 'KRSID', $perkrs, 'MKID');
			$matched[$mkkrs] = $mkkrs;
			
			if(!empty($Pilihan))
			{
				foreach($Pilihan as $perjadwal)
				{	$mkjadwal = AmbilOneField('jadwal', 'JadwalID', $perjadwal, 'MKID');
					if($mkkrs == $mkjadwal)
					{	$matched[$mkkrs] = ''; 
						break;
					}
				}
			}
		}
		$errMsg = array(); 
		foreach($matched as $isMatched)
		{	if(empty($Pilihan))
			{	$mk = AmbilFieldx('mk', 'MKID', $isMatched, 'MKKode, Nama');
				$errMsg[] = "Mata Kuliah $mk[Nama] ($mk[MKKode])";
			}
			else
			{	if(!empty($isMatched)) 
				{	$mk = AmbilFieldx('mk', 'MKID', $isMatched, 'MKKode, Nama');
					$errMsg[] = "Mata Kuliah $mk[Nama] ($mk[MKKode])";
				}
			}
		}
		
		if (!empty($errMsg))
		{	foreach($errMsg as $error) $errorstring .= "<br>&bull; <b>$error</b>";
			die(PesanError("Gagal",
			"Mata Kuliah di bawah ini tidak mendapat pilihan KRS Baru untuk dipindahkan ke Program $ProgramID.<br>".$errorstring."
			<br>
			<br>Siswa tidak dapat dipindahkan ke Program $ProgramID.
			<hr size=1 color=silver />
			<input class='btn btn-success btn-sm' type=button name='btnKembali' value='Kembali'
			  onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=fnProsesProgram&MhswID=$MhswID&ProgramID=$ProgramID'\" />
			<input type=button name='btnBatal' value='Batal'
			  onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />")); 
		}
		
		foreach($KRS as $perkrs)
		{
			$krs = AmbilFieldx('krs', "KRSID='$perkrs' and KodeID", KodeID, '*');
			$baru = $krs;
			$baru['ProgramID'] = $ProgramID;
			if(!empty($Pilihan))
			{	foreach($Pilihan as $perjadwal)
				{	$mkjadwal = AmbilFieldx('jadwal', 'JadwalID', $perjadwal, 'MKID, DosenID');
					if($krs[MKID] == $mkjadwal[MKID])
					{	$baru['JadwalID'] = $perjadwal; 
						$baru['DosenID'] = $mkjadwal['DosenID'];
						break;
					}
				}
			}
			// Salin data mhsw
			$s = "update krs
				set JadwalID='$baru[JadwalID]', DosenID='$baru[DosenID]', 
					LoginEdit='$_SESSION[_Login]', TanggalEdit=now() 
				where KRSID='$perkrs'";
			$r = mysqli_query($koneksi, $s);
		}
	}
	
	// Ubah semua Status Program Mhsw di sini
	$sm = "update mhsw 
		set ProgramID = '$ProgramID'
		where MhswID = '$MhswID' and KodeID = '".KodeID."' ";
	$rm = mysqli_query($koneksi, $sm);
	$sk = "update khs
		set ProgramID = '$ProgramID'
		where MhswID = '$MhswID' and KodeID = '".KodeID."' and TahunID='$TahunID'";
	$rk = mysqli_query($koneksi, $sk);
	// Kembali
	SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=&crMhswID=$MhswID", 1);
	
}

function PilihKRSBaruScript()
{	echo <<< SCR
		<script>
			function EmptyOthers(mkkode, target, count)
			{	
				for(i = 0; i < count; i++)
				{	document.getElementById(mkkode+i).checked = false;
				}
				document.getElementById(mkkode+target).checked = true;
			}
		</script>
SCR;
}
function UbahKeHariIndonesia($integer)
{	$arrHari = array('Minggu', 'Senin', 'Selasa', 'Rabu','Kamis', 'Jumat', 'Sabtu');
	return $arrHari[$integer+0];
}
?>
