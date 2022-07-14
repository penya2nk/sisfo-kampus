<?php
error_reporting(0);
$gelombang = AmbilOneField('pmbperiod', "KodeID='".KodeID."' and NA", 'N', "PMBPeriodID");
$_apliNama = GainVariabelx('_apliNama');
$_apliPage = GainVariabelx('_apliPage');
$_curPres = GainVariabelx('_curPres');
if (isset($_REQUEST['sbmt']) && $_REQUEST['sbmt'] == 1) {
  $_SESSION['_apliPre'] = ($_REQUEST['_apliPre'] == 'Y')? 'Y' : 'N';
  $_SESSION['_apliGel'] = ($_REQUEST['_apliGel'] == 'Y')? 'Y' : 'N';
}

TitleApps("PENDAFTAR - $gelombang");
if (empty($gelombang)) {
  echo PesanError("Error",
    "Tidak ada gelombang PMB yang aktif.<br />
    Hubungi Kepala PMB untuk mengaktifkan gelombang.");
}
else {
  $lungo = (empty($_REQUEST['lungo']))? 'ListPendaftar' : $_REQUEST['lungo'];
  $lungo($gelombang);
}

function TampilkanHeaderAplikan($gelombang) {
  $ck = (isset($_SESSION['_apliPre']) && $_SESSION['_apliPre'] == 'Y')? 'checked' : '';
  $ckgel = (isset($_SESSION['_apliGel']) && $_SESSION['_apliGel'] == 'Y')? 'checked' : '';
  $optpresenter = AmbilCombo2('presenter', "concat(PresenterID, ' - ', Nama)", 'PresenterID', $_SESSION['_curPres'], "KodeID='".KodeID."'", 'PresenterID');
  RandomStringScript();
  

  echo "<div class='card'>
  <div class='card-header'>
  <div align='center'>
  ";

  echo <<<ESD
  <form name='frmAplikan' action='?' method=POST>
  <input type=hidden name='_apliPage' value=1 />
  <input type=hidden name='lungo' value='' />
  <input type=hidden name='sbmt' value=1 />

        <input style='height:30px' placeholder='NAMA' type=text name='_apliNama' value='$_SESSION[_apliNama]' size=20 maxlength=50 />
        <input class='btn btn-success btn-sm' type=submit name='btnCari' value='Lihat Data' />
        <input class='btn btn-danger btn-sm' type=button name='btnReset' value='Reset '
          onClick="window.location='?ndelox=$_SESSION[ndelox]&lungo=&_apliNama='" />
        
PANITIA <select style='height:30px' name='_curPres' 
			onChange='this.form.submit()'  />$optpresenter</select>
      <input class='btn btn-primary btn-sm' type=button name='btnTambahAplikan' value='Tambah Pendaftar'
        onClick="javascript:fnEditAplikan('$gelombang', 1, 0 )" />
     
      GELOMBANG INI SAJA
      <input type=checkbox name='_apliGel' value='Y' $ckgel onClick='this.form.submit()' />
<input class='btn  btn-info btn-sm' type=button name='btnDaftarAplikan' value='Print Pendaftar Hari Ini'
        onClick="javascript:fnCetakAplikan('$gelombang')" />

  </form>



</div>
  </div>
</div>


  <script>
  function fnEditAplikan(gel, md, id) {
    _rnd = randomString();
    lnk = "$_SESSION[ndelox].edt.php?md="+md+"&gel="+gel+"&id="+id+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=700,height=600,left=450,top=100,toolbar=0,status=0");
    if (win2.opener == null) childWindow.opener = self;
  }
  function fnCetakAplikan(gel) {
    _rnd = randomString();
    lnk = "$_SESSION[ndelox].cetakaplikan.php?gel="+gel+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=800, height=600,left=500,top=200, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function fnPMB(id) {
    if (confirm("Anda akan mendaftarkan aplikan ke PMB?")) {
      window.location = "?ndelox=$_SESSION[ndelox]&gel=$gelombang&lungo=fnDaftarkanPMB&BypassMenu=1&id="+id;
    }
  }
  function fnEditHistory(gel, md, id) {
	_rnd = randomString();
	lnk = "$_SESSION[ndelox].hst.php?gel="+gel+"&md="+md+"&id="+id+"&_rnd="+_rnd;
	win2 = window.open(lnk, "", "width=350,height=400,left=500,top=200,toolbar=0,status=0");
	if(win2.opener == null) childWindow.opener = self;
  }
  </script>
ESD;
}

function ListPendaftar($gelombang) {
	global $koneksi;
  TampilkanHeaderAplikan($gelombang);
  
  $_maxbaris = 10;
  
  
  $s = "select DISTINCT(j.JenjangID), j.Nama from prodi p left outer join jenjang j on p.JenjangID=j.JenjangID where p.KodeID='".KodeID."' and p.NA='N' order by JenjangID DESC"; 
  $r = mysqli_query($koneksi, $s);
  $n = 0;
  $arrPilihan = array();
  while($w = mysqli_fetch_array($r)) 
  {	$n++;
	$arrPilihan[]  = $w['JenjangID'].'~'.$w['Nama'].'~'.$n;
  }
  
  $titlePilihan = ""; $listPilihan = ""; $entryPilihan = "";
  foreach($arrPilihan as $pilih) 
  {	$arrPilih = explode('~', $pilih);
	$titlePilihan .= (empty($titlePilihan))? "Pilihan $arrPilih[1]" : "<hr size=1 color=silver />Pilihan $arrPilih[1]";
	  
	  $ss = "select ProdiID from prodi where KodeID='".KodeID."' and JenjangID='$arrPilih[0]'";
	  $rr = mysqli_query($koneksi, $ss);
	  $listPilihan .= "SUBSTR(concat(";
	  $nn = 0;
	  while($ww = mysqli_fetch_array($rr))
	  {	if($nn == 0) $listPilihan .= "if(concat(',', a.ProdiID ,',') like '%,$ww[ProdiID],%', ',$ww[ProdiID]', '')";
		else $listPilihan .= ", if(concat(',', a.ProdiID ,',') like '%,$ww[ProdiID],%', ',$ww[ProdiID]', '')";
		$nn++;
	  }
	  $listPilihan.= "), 2) as _Pilihan$arrPilih[2],";
	  
	  $entryPilihan .= (empty($entryPilihan))? "=_Pilihan$arrPilih[2]=&nbsp" : "<hr size=1 color=silver />=_Pilihan$arrPilih[2]=&nbsp";
  }
  
  // Filter formulir
  $whr = array();
  if (!empty($_SESSION['_apliNama'])) $whr[] = "a.Nama like '$_SESSION[_apliNama]%' ";
  if (isset($_SESSION['_apliPre']) && $_SESSION['_apliPre'] == 'Y')   $whr[] = "a.LoginBuat = '$_SESSION[_Login]' ";
  if (isset($_SESSION['_apliGel']) && $_SESSION['_apliGel'] == 'Y')   $whr[] = "a.PMBPeriodID = '$gelombang' ";
  if (!empty($_SESSION['_curPres'])) $whr[] = "a.PresenterID = '$_SESSION[_curPres]' ";
  
  $_whr = implode(' and ', $whr);
  $_whr = (empty($_whr))? '' : ' and ' . $_whr;
  
  $pagefmt = "<a href='?ndelox=$_SESSION[ndelox]&lungo=&_apliPage==PAGE='>=PAGE=</a>";
  $pageoff = "<b>=PAGE=</b>";

  $brs = "<hr size=1 color=silver />";
  $gantibrs = "<tr><td bgcolor=silver height=1 colspan=11></td></tr>";

  $s = "select a.PMBPeriodID, a.PresenterID, a.StatusAplikanID, 
    a.AplikanID, a.Nama, a.Kelamin, a.Telepon, a.Handphone, a.Email,
    a.ProdiID,
    if(b.Nama like '_%', b.Nama, 
		if(pt.Nama like '_%', pt.Nama, a.AsalSekolah)) as _NamaSekolah, 
	a.TempatLahir,
	a.StatusAplikanID, a.StatusMundur, 
	a.ProdiID, 
	$listPilihan
	date_format(a.TanggalLahir, '%d %b %Y') as _TanggalLahir,
    if(a.StatusMundur = 'N', 
      \"<i title='Edit Profil Aplikan' class='fa fa-edit'></i>\", '&times') as EDIT, 
		if(a.StatusMundur = 'Y',
			'class=wrn',
			if (a.PMBID is NULL or a.PMBID = '',
			'class=ul', 
			'class=nac')) as _kelas from aplikan a
    left outer join asalsekolah b on a.AsalSekolah = b.SekolahID
	left outer join perguruantinggi pt on a.AsalSekolah = pt.PerguruanTinggiID
	where a.KodeID = '".KodeID."'
    $_whr
  order by a.PMBPeriodID desc, a.Nama limit 50";
  $r = mysqli_query($koneksi, $s);

  echo"
  <div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example1' class='table table-sm table-bordered table-striped'>
					   
    <thead>
	<tr style='background:purple;color:white'>
    <th class=ttl width=90>#</th>
	<th class=ttl width=90>Status</th>
    <th class=ttl width=320>Nama</th>
    <th class=ttl width=180>Tmpt Lahir</th>
    <th class=ttl width=100>Tgl Lahir</th>
    <th class=ttl width=340>Asal Sekolah</th>
    <th class=ttl >PresenterID</th>
    <th class=ttl >PMBPeriodID</th>
    </tr>
	</thead>
	</tbody>";
	  while ($w = mysqli_fetch_array($r)) {
    $n++;
	echo"<tr>
    <td class=inp1 align=center>      
      <a href='#' onClick=\"javascript:fnEditAplikan('$gelombang', 0, '$w[AplikanID]')\" /><i class='fas fa-edit'></i></a>
      $w[AplikanID]
      </td>
	<td =_kelas=>
	  <a href='#' onClick=\"javascript:fnEditHistory('$gelombang', 0, '$w[AplikanID]')\" />
	  <i title='Edit Tahap Pendaftaran Aplikan' class='fa fa-edit'></i></a>$w[StatusAplikanID]</td>
    <td =_kelas=>$w[Nama]</td>
    <td =_kelas= align=left>$w[TempatLahir]</td>
    <td =_kelas= align=left>$w[_TanggalLahir]</td>
    <td =_kelas=>$w[_NamaSekolah]</td>
    <td =_kelas=>$w[PresenterID]</td>
    <td =_kelas=>$w[PMBPeriodID]</td>

    </tr>";
	  }
  echo"</table>
  </tbody>
  </div>
  </div>
  </div>";
}

function fnDaftarkanPMB() {
	global $koneksi;
  $id = sqling($_REQUEST['id']);
  $gel = sqling($_REQUEST['gel']);
  $a = AmbilFieldx('aplikan', "AplikanID", $id, '*');
  if (empty($a)) {
    echo PesanError("Error",
    "Data aplikan tidak ditemukan.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    <input type=button name='btnKembali' value='Kembali'
    onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />");
  }
  else {

	$FormulirID = GetAField('pmbformjual', 'AplikanID', $id, 'PMBFormulirID');
	if(empty($FormulirID))
	{	echo PesanError("Gagal", "Aplikan belum membeli formulir.<br> Data tidak disimpan. <br>
							<input type=button name='Kembali' value='Kembali'
								onClick=\"javascript:history.go(-1)\" />");
	}
	else
	{
	
    $PMBID = GetNextPMBID($gel);
	$arrayPilihan = explode(',', $a['Pilihan1']);
	$arrayPilihan2 = explode(',', $a['Pilihan2']);
	$arrayGabung = array();
	foreach($arrayPilihan as $pilih) {  if(!empty($pilih))  $arrayGabung[] = $pilih; } 
	foreach($arrayPilihan2 as $pilih) {  if(!empty($pilih))  $arrayGabung[] = $pilih; } 
    $s = "insert into pmb
      (PMBID, PMBPeriodID, KodeID, AplikanID,
      Nama, StatusAwalID, Kelamin, Pilihan1, Pilihan2, ProgramID, 
      TempatLahir, TanggalLahir, Agama, PMBFormulirID, PMBFormJualID, 
      Alamat, Kota, RT, RW, KodePos, Propinsi, Negara,
      Telepon, Handphone, Email, 
      PendidikanTerakhir, AsalSekolah, JenisSekolahID,
      AlamatSekolah, KotaSekolah, JurusanSekolah,
      NilaiSekolah, TahunLulus,
      LoginBuat, TanggalBuat)
      values
      ('$PMBID', '$gel', '".KodeID."', '$a[AplikanID]',
      '$a[Nama]', 'B', '$a[Kelamin]', '$arrayGabung[0]', '$arrayGabung[1]', '$a[ProgramID]',
      '$a[TempatLahir]', '$a[TanggalLahir]', '$a[Agama]', '$a[PMBFormulirID]', '$a[PMBFormJualID]',
      '$a[Alamat]', '$a[Kota]', '$a[RT]', '$a[RW]', '$a[KodePos]', '$a[Propinsi]', '$a[Negara]',
      '$a[Telepon]', '$a[Handphone]', '$a[Email]',
      '$a[PendidikanTerakhir]', '$a[AsalSekolah]', '$a[JenisSekolahID]',
      '$a[AlamatSekolah]', '$a[KotaSekolah]', '$a[JurusanSekolah]',
      '$a[NilaiSekolah]', '$a[TahunLulus]',
      '$_SESSION[_Login]', now())";
    $r = mysqli_query($koneksi, $s);
    // Set Status Aplika menjadi DFT
    SetStatusAplikan('DFT', $a['AplikanID']);
    // Tampilkan pesan //$_pmbNomer
    echo Info("Proses Selesai",
    "Proses pendaftaran Aplikan ke PMB telah selesai.<br />
    Nomer PMB: <font size=+1>$PMBID</font>
    <hr size=1 color=silver /> 
    <input type=button name='btnKembali' value='Kembali'
      onClick=\"window.location='?ndelox=$_SESSION[ndelox]&lungo='\" />
    <input type=button name='btnDataPMB' value='$PMBID'
      onClick=\"location='?ndelox=pmb/pmbform&lungo=&_pmbNomer=$PMBID'\" /> (klik untuk masuk ke data PMB).<br />
      ");
    
    // Reload
    $tmr = 10000;
    $_tmr = $tmr / 1000;
    echo <<<SCR
    <p align=center>
    <font color=red>Jika dalam $_tmr detik tidak ada respons,<br />
    maka sistem akan mengembalikan ke modul presenter.</font> 
    <script>
    window.onload=setTimeout("window.location='?ndelox=$_SESSION[ndelox]&lungo='", $tmr);
    </script>
SCR;
    }
  }
}
?>
