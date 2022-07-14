<?php

$gelombang = AmbilOneField('pmbperiod', "KodeID='".KodeID."' and NA", 'N', "PMBPeriodID");
$_pmbwebNama = GainVariabelx('_pmbwebNama');
$_pmbwebFrmID = GainVariabelx('_pmbwebFrmID');
$_pmbwebPrg = GainVariabelx('_pmbwebPrg');
$_pmbwebNomer = GainVariabelx('_pmbwebNomer');
$_pmbwebPage = GainVariabelx('_pmbwebPage');
$_pmbwebUrut = GainVariabelx('_pmbwebUrut', 0);
$arrUrut = array('Nomer PMB~p.PMBWebID asc, p.Nama', 'Nomer PMB (balik)~p.PMBWebID desc, p.Nama', 'Nama~p.Nama');
RandomStringScript();

// *** Main ***
TitleApps("Pemrosesan PMB Web - $gelombang");
if (empty($gelombang)) {
  echo PesanError("Error",
    "Tidak ada gelombang PMB yang aktif.<br />
    Hubungi Kepala PMB untuk mengaktifkan gelombang.");
}
else {
  $lungo = (empty($_REQUEST['lungo']))? 'DftrForm' : $_REQUEST['lungo'];
  $lungo($gelombang);
}

// *** Functions ***

function GetUrutanPMB() {
  global $arrUrut;
  $a = ''; $i = 0;
  foreach ($arrUrut as $u) {
    $_u = explode('~', $u);
    $sel = ($i == $_SESSION['_pmbwebUrut'])? 'selected' : '';
    $a .= "<option value='$i' $sel>". $_u[0] ."</option>";
    $i++;
  }
  return $a;
}

function TampilkanHeader($gel) {
  $optfrm = AmbilCombo2('pmbformulir', 'Nama', 'Nama', $_SESSION['_pmbwebFrmID'],
    "KodeID='".KodeID."'", 'PMBFormulirID');
  $optprg = AmbilCombo2('program', "concat(ProgramID, ' - ', Nama)", 'ProgramID', $_SESSION['_pmbwebPrg'], "KodeID='".KodeID."'", 'ProgramID');
  $opturut = GetUrutanPMB();
  
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:50%' align='center'>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='lungo' value='' />
  <input type=hidden name='_pmbwebPage' value='0' />
  
  <tr>
      <td class=inp>Cari Nama:</td>
      <td class=ul1><input type=text name='_pmbwebNama' value='$_SESSION[_pmbwebNama]' size=20 maxlength=30 /></td>
      </tr>
  <tr>
      <td class=inp>Cari No. Formulir:</td>
      <td class=ul1><input type=text name='_pmbwebNomer' value='$_SESSION[_pmbwebNomer]' size=20 maxlength=30 /></td>
      <td class=inp>Urutkan:</td>
      <td class=ul1><select name='_pmbwebUrut'>$opturut</select></td>
      </tr>
  <tr>
      <td class=inp>Program:</td>
      <td class=ul1><select name='_pmbwebPrg'>$optprg</select></td>
      <td class=ul1 colspan=2 align=center nowrap>
      <input type=submit name='Submit' value='Submit' />
      <input type=button name='Reset' value='Reset'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=&_pmbwebPage=0&_pmbwebNama=&_pmbwebNomer='\" />
      </td>
  </form>
  </table></div>
  </div>
  </div>";
}

function DftrForm($gel) {
  TampilkanHeader($gel);
  
  global $arrUrut;
  $_maxbaris = 10;
  include_once "class/dwolister.class.php";
  // Urutan
  
  if($_SESSION['_LevelID'] != 33)
  {
	  $_urut = $arrUrut[$_SESSION['_pmbwebUrut']];
	  $__urut = explode('~', $_urut);
	  $urut = "order by ".$__urut[1];
	  // Filter formulir
	  $whr = array();
	  if (!empty($_SESSION['_pmbwebFrmID'])) $whr[] = "p.PMBFormulirID='$_SESSION[_pmbwebFrmID]'";
	  if (!empty($_SESSION['_pmbwebPrg']))   $whr[] = "p.ProgramID = '$_SESSION[_pmbwebPrg]' ";
	  if (!empty($_SESSION['_pmbwebNama']))  $whr[] = "p.Nama like '%$_SESSION[_pmbwebNama]%'";
	  if (!empty($_SESSION['_pmbwebNomer'])) $whr[] = "p.PMBWebID like '%$_SESSION[_pmbwebNomer]%'";
	  
	  $_whr = implode(' and ', $whr);
	  $_whr = (empty($_whr))? '' : 'and '.$_whr;
  }
  else
  {	$_whr = "and p.PMBWebID = $_SESSION[_Login]";
  }
  $pagefmt = "<a href='?ndelox=$_SESSION[ndelox]&lungo=&_pmbwebPage==PAGE='>=PAGE=</a>";
  $pageoff = "<b>=PAGE=</b>";

  $brs = "<hr size=1 color=silver />";
  $gantibrs = "<tr><td bgcolor=silver height=1 colspan=11></td></tr>";
  $lst = new dwolister;
  $lst->tables = "pmbweb p 
    left outer join pmbformulir f on p.PMBFormulirID = f.PMBFormulirID
    left outer join prodi _p1 on p.Pilihan1 = _p1.ProdiID
    left outer join prodi _p2 on p.Pilihan2 = _p2.ProdiID
    left outer join prodi _p3 on p.Pilihan3 = _p3.ProdiID
    left outer join program _prg on p.ProgramID = _prg.ProgramID
    left outer join statusawal _sta on p.StatusAwalID = _sta.StatusAwalID
    where p.KodeID = '".KodeID."' 
      $_whr
	  and p.Diproses = 'N'
      $urut";
  $lst->fields = "p.PMBWebID, p.Nama, p.Kelamin, p.ProdiID, p.Pilihan1, p.Pilihan2, p.Pilihan3,
    f.Nama as FRM, _p1.Nama as P1, 
	if(f.JumlahPilihan <= 2, concat('<br>', _p2.Nama), '') as P2, 
	if(f.JumlahPilihan <= 3, concat('<br>', _p3.Nama), '') as P3,
	if(p.StatusAwalID='S', concat('<font color=blue>',_sta.Nama,'<font>') , _sta.Nama) as STA,
    _prg.Nama as PRG, p.NA, p.NilaiSekolah";
  //$lst->startrow = $_SESSION['_pmbwebPage']+0;
  $lst->maxrow = $_maxbaris;
  $lst->pages = $pagefmt;
  $lst->pageactive = $pageoff;
  $lst->page = $_SESSION['_pmbwebPage']+0;
  $lst->headerfmt = "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:70%' align='center'>
    
    <tr style='background:purple;color:white'>
    <th class=ttl>#</th>
    <th class=ttl>PMB Web ID</th>
    <th class=ttl colspan=2>Nama</th>
    <th class=ttl>Status</th>
	<th class=ttl>Program</th>
    <th class=ttl>Pilihan</th>
	<th class=ttl>Nilai Sekolah</th>
	<th class=ttl>Proses?</th>
    </tr>";
  $lst->detailfmt = "<tr>
    <td class=inp width=10>=NOMER=</td>
    <td class=ul1 width=80>=PMBWebID=</td>
    <td class=cna=NA=>=Nama=</td>
    <td class=cna=NA= width=10 align=center><img src='img/=Kelamin=.bmp' /></td>
    <td class=cna=NA= width=70 align=center>=STA=</td>
    <td class=cna=NA= width=120 align=center>=PRG=</td>
    <td class=cna=NA= width=140 align=center>=P1==P2==P3=</td>
     <td class=cna=NA= width=100 align=center>=NilaiSekolah=</td>
	 <td class=cna=NA= width=70 align=center><input type=button name='Proses' value='Proses' onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=Proses&pmbwebid==PMBWebID='\"></td>
	 </tr>".$gantibrs;
  $lst->footerfmt = "</table></div>
  </div>
  </div>";

  $hal = $lst->TampilkanHalaman($pagefmt, $pageoff);
  $ttl = $lst->MaxRowCount;
  echo $lst->TampilkanData();
  echo "<p align=center>Hal: $hal <br />(Tot: $ttl)</p>";
}

function Proses($gel)
{	$PMBWebID = $_REQUEST['pmbwebid'];
	$pmbweb = AmbilFieldx('pmbweb', 'PMBWebID', $PMBWebID, '*');

	if(!empty($pmbweb))
	{   
		$PMBID = GetNextPMBID($gel);
		
		$frm = AmbilFieldx('pmbformulir', 'WebDef', 'Y', '*');
		  $pil = array();
		  $vpil = array();
		  $epil = array();
		  for ($i = 1; $i <= $frm['JumlahPilihan']; $i++) {
			$pil[] = 'Pilihan'.$i;
			$vpil[] = "'".$pmbweb['Pilihan'.$i]."'";
			$epil[] = 'Pilihan'.$i."='".$pmbweb['Pilihan'.$i]."'";
		  }
		  $_pil = implode(', ', $pil);
		  $_vpil = implode(', ', $vpil);
		  $_epil = implode(', ', $epil);
		
		$s = "insert into pmb
		  (PMBID, PMBPeriodID, KodeID, StatusAwalID, Nama,
		  TempatLahir, TanggalLahir, Kelamin, GolonganDarah,
		  Agama, StatusSipil, TinggiBadan, BeratBadan,
		  WargaNegara, Kebangsaan,
		  Alamat, RT, RW, KodePos, Kota, Propinsi, 
		  Telepon, Handphone, Email,
		  PendidikanTerakhir, AsalSekolah,
		  TahunLulus, NilaiSekolah, PrestasiTambahan, 
		  NamaAyah, AgamaAyah, PendidikanAyah, PekerjaanAyah, HidupAyah, PenghasilanAyah,
		  NamaIbu, AgamaIbu, PendidikanIbu, PekerjaanIbu, HidupIbu, PenghasilanIbu,
		  AlamatOrtu, RTOrtu, RWOrtu, KodePosOrtu, KotaOrtu, PropinsiOrtu,
		  TeleponOrtu, HandphoneOrtu, EmailOrtu,
		  PMBFormulirID, ProgramID, ProdiID, $_pil,
		  
		  LoginBuat, TanggalBuat)
		  values
		  ('$PMBID', '$gel', '".KodeID."', '$pmbweb[StatusAwalID]', '$pmbweb[Nama]', 
		  '$pmbweb[TempatLahir]', '$pmbweb[TanggalLahir]', '$pmbweb[Kelamin]', '$pmbweb[GolonganDarah]',
		  '$pmbweb[Agama]', '$pmbweb[StatusSipil]', '$pmbweb[TinggiBadan]', '$pmbweb[BeratBadan]',
		  '$pmbweb[WargaNegara]', '$pmbweb[Kebangsaan]',
		  '$pmbweb[Alamat]', '$pmbweb[RT]', '$pmbweb[RW]', '$pmbweb[KodePos]', '$pmbweb[Kota]', '$pmbweb[Propinsi]', 
		  '$pmbweb[Telepon]', '$pmbweb[Handphone]', '$pmbweb[Email]',
		  '$pmbweb[PendidikanTerakhir]', '$pmbweb[AsalSekolah]',
		  '$pmbweb[TahunLulus]', '$pmbweb[NilaiSekolah]', '$pmbweb[PrestasiTambahan]', 
		  '$pmbweb[NamaAyah]', '$pmbweb[AgamaAyah]', '$pmbweb[PendidikanAyah]', '$pmbweb[PekerjaanAyah]', '$pmbweb[HidupAyah]', '$pmbweb[PenghasilanAyah]', 
		  '$pmbweb[NamaIbu]', '$pmbweb[AgamaIbu]', '$pmbweb[PendidikanIbu]', '$pmbweb[PekerjaanIbu]', '$pmbweb[HidupIbu]', '$pmbweb[PenghasilanIbu]',
		  '$pmbweb[AlamatOrtu]', '$pmbweb[RTOrtu]', '$pmbweb[RWOrtu]', '$pmbweb[KodePosOrtu]', '$pmbweb[KotaOrtu]', '$pmbweb[PropinsiOrtu]',
		  '$pmbweb[TeleponOrtu]', '$pmbweb[HandphoneOrtu]', '$pmbweb[EmailOrtu]',
		  '$frm[PMBFormulirID]', '$pmbweb[ProgramID]', '$pmbweb[ProdiID]', $_vpil,
		  
		  '$_SESSION[_Login]', now())";
		$r = mysqli_query($koneksi, $s);
		
		$s = "update pmbweb set Diproses='Y' where PMBWebID='$PMBWebID'";
		$r = mysqli_query($koneksi, $s);
		DftrForm($gel);
	}
	else
	{	die(PesanError("Tidak ditemukan", "Data dengan PMBWebID $PMBWebID tidak ditemukan. Harap menghubungi administrator"));
	}
}

?>
