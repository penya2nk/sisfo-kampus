<?php
$gelombang = AmbilOneField('pmbperiod', "KodeID='".KodeID."' and NA", 'N', "PMBPeriodID");
$_pmbNama = GainVariabelx('_pmbNama');
$_pmbFrmID = GainVariabelx('_pmbFrmID');
$_pmbPrg = GainVariabelx('_pmbPrg');
$_pmbNomer = GainVariabelx('_pmbNomer');
$_pmbPage = GainVariabelx('_pmbPage');
$_pmbUrut = GainVariabelx('_pmbUrut', 0);
$arrUrut = array('Nomer PMB~p.PMBID asc, p.Nama', 'Nomer PMB (balik)~p.PMBID desc, p.Nama', 'Nama~p.Nama');
RandomStringScript();

TitleApps("PENGISIAN FORMULIR - $gelombang");
if (empty($gelombang)) {
  echo PesanError("Error",
    "Tidak ada gelombang PMB yang aktif.<br />
    Hubungi Kepala PMB untuk mengaktifkan gelombang.");
}
else {
  $lungo = (empty($_REQUEST['lungo']))? 'DftrForm' : $_REQUEST['lungo'];
  $lungo($gelombang);
}

function IsiFormulirScript($gel) {
  echo <<<SCR
  <script>
  function IsiFormulir(MD,GEL,ID) {
    _rnd = randomString();
    lnk = "$_SESSION[ndelox].isi.php?md="+MD+"&gel="+GEL+"&id="+ID+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=700, height=600, left=500 top=150  scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
SCR;
}
function CetakKartuScript() {
  echo <<<SCR
  <script>
  function CetakKartu(ID) {
    _rnd = randomString();
    lnk = "$_SESSION[ndelox].kartutest.php?id="+ID+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=500, left=550, top=200, height=300, scrollbars, status, resizable");
    if (win2.opener == null) childWindow.opener = self;
    window.location = "?ndelox=$_SESSION[ndelox]";
  }
  function PilihKursi(ID, gel)
  {	_rnd = randomString();
    lnk = "$_SESSION[ndelox].pilihkursi.php?id="+ID+"&_rnd="+_rnd+"&gel="+gel;
    win2 = window.open(lnk, "", "width=500, left=500, top=200, height=300, scrollbars, status, resizable");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
SCR;
}

function GetUrutanPMB() {
  global $arrUrut;
  $a = ''; $i = 0;
  foreach ($arrUrut as $u) {
    $_u = explode('~', $u);
    $sel = ($i == $_SESSION['_pmbUrut'])? 'selected' : '';
    $a .= "<option value='$i' $sel>". $_u[0] ."</option>";
    $i++;
  }
  return $a;
}

function TampilkanHeader($gel) {
  IsiFormulirScript($gel);
  CetakKartuScript();
  $optfrm = AmbilCombo2('pmbformulir', 'Nama', 'Nama', $_SESSION['_pmbFrmID'],
    "KodeID='".KodeID."'", 'PMBFormulirID');
  $optprg = AmbilCombo2('program', "concat(ProgramID, ' - ', Nama)", 'ProgramID', $_SESSION['_pmbPrg'], "KodeID='".KodeID."'", 'ProgramID');
  $opturut = GetUrutanPMB();
  if($_SESSION['_LevelID'] != 33)
  {	$AmbilLalu = "<input  class='btn-danger btn-sm' type=button name='btnAmbilPMBLalu' value='Periode Lalu'
        onClick=\"javascript:AmbilPMBLalu('$gel')\" />"; 
  
  echo "<div class='card'>
  <div class='card-header'>
  <div align='center'>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='lungo' value='' />
  <input type=hidden name='_pmbPage' value='0' />
<input style='height:30px' placeholder='NAMA' type=text name='_pmbNama' value='$_SESSION[_pmbNama]' size=15 maxlength=30 />
FORMULIR
<select style='height:30px' name='_pmbFrmID'>$optfrm</select>
<input style='height:30px' placeholder='NO FORMULIR' type=text name='_pmbNomer' value='$_SESSION[_pmbNomer]' size=15 maxlength=30 />
PROGRAM <select style='height:30px' name='_pmbPrg'>$optprg</select>
      <input  class='btn btn-success btn-sm' type=submit name='Submit' value='Lihat Data' />
      <input  class='btn btn-primary btn-sm' type=button name='IsiFrm' value='Isi Formulir' onClick=\"javascript:IsiFormulir(1,'$gel','')\" />
      $AmbilLalu
     
  </form>
  </div>
  </div>
  </div>";
  }
  // Javascript
  echo <<<ESD
  <script>
  function AmbilPMBLalu(gel) {
    lnk = "$_SESSION[ndelox].lalu.php?gel="+gel;
    win2 = window.open(lnk, "", "width=820, left=400 top=150, height=400, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
ESD;
}

function DftrForm($gel) {
  TampilkanHeader($gel);
  
  global $arrUrut, $koneksi;
  $_maxbaris = 10;
  // Urutan
  
  if($_SESSION['_LevelID'] != 33)
  {
	  $_urut = $arrUrut[$_SESSION['_pmbUrut']];
	  $__urut = explode('~', $_urut);
	  $urut = "order by ".$__urut[1];
	  // Filter formulir
	  $whr = array();
	  if (!empty($_SESSION['_pmbFrmID'])) $whr[] = "p.PMBFormulirID='$_SESSION[_pmbFrmID]'";
	  if (!empty($_SESSION['_pmbPrg']))   $whr[] = "p.ProgramID = '$_SESSION[_pmbPrg]' ";
	  if (!empty($_SESSION['_pmbNama']))  $whr[] = "p.Nama like '%$_SESSION[_pmbNama]%'";
	  if (!empty($_SESSION['_pmbNomer'])) $whr[] = "p.PMBID like '%$_SESSION[_pmbNomer]%'";
	  
	  $_whr = implode(' and ', $whr);
	  $_whr = (empty($_whr))? '' : 'and '.$_whr;
  }
  else
  {	$_whr = "and p.PMBID = $_SESSION[_Login]";
  }
  $pagefmt = "<a href='?ndelox=$_SESSION[ndelox]&lungo=&_pmbPage==PAGE='>=PAGE=</a>";
  $pageoff = "<b>=PAGE=</b>";

  $brs = "<hr size=1 color=silver />";
  $gantibrs = "<tr><td bgcolor=silver height=1 colspan=11></td></tr>";

  $s= "select 
p.PMBID, p.Nama, p.Kelamin, p.ProdiID, p.Pilihan1, p.Pilihan2, p.Pilihan3, p.LulusUjian,
    f.Nama as FRM, _p1.Nama as P1, 
	if(f.JumlahPilihan <= 2, _p2.Nama, '-') as P2, 
	if(f.JumlahPilihan <= 2, p.NA, 'Y') as NA2,
	if(f.JumlahPilihan <= 3, _p3.Nama, '-') as P3,
	if(f.JumlahPilihan <= 3, p.NA, 'Y') as NA3,
	if(p.StatusAwalID='S', concat('<font color=blue>',_sta.Nama,'<font>') , _sta.Nama) as STA,
    _prg.Nama as PRG, p.CetakKartu, p.NA,
	if(f.Wawancara = 'Y' and f.USM = 'Y',
		(
		if (EXISTS(select ru.RuangUSMID from ruangusm ru where ru.PMBID=p.PMBID and KodeID='".KodeID."')
			and (EXISTS(select w.WawancaraUSMID from wawancara w where w.PMBID=p.PMBID and PMBPeriodID='$gel' and KodeID='".KodeID."')),
			'kursiN', 'kursiY')
		),
		(
			if(f.Wawancara = 'N' and f.USM = 'Y',
			(
			if (EXISTS(select ru.RuangUSMID from ruangusm ru where ru.PMBID=p.PMBID and KodeID='".KodeID."'),
				'kursiN', 'kursiY')
			),
			(
			if(f.Wawancara = 'Y' and f.USM = 'N',
				(
				if (EXISTS(select w.WawancaraUSMID from wawancara w where w.PMBID=p.PMBID and PMBPeriodID='$gel' and KodeID='".KodeID."'),
					'kursiN', 'kursiY')
				),'kursiN')
			))
		)) as _JenisKursi
		from
  pmb p 
    left outer join pmbformulir f on p.PMBFormulirID = f.PMBFormulirID
    left outer join prodi _p1 on p.Pilihan1 = _p1.ProdiID
    left outer join prodi _p2 on p.Pilihan2 = _p2.ProdiID
    left outer join prodi _p3 on p.Pilihan3 = _p3.ProdiID
    left outer join program _prg on p.ProgramID = _prg.ProgramID
    left outer join statusawal _sta on p.StatusAwalID = _sta.StatusAwalID
    where p.KodeID = '".KodeID."' 
      and p.PMBPeriodID='$gel'
      $_whr";
 echo"<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example1' class='table table-sm table-striped' style='width:100%' align='center'>
      <thead>
    <tr style='background:purple;color:white'>
    <th class=ttl>No</th>
	<th class=ttl>Aksi</th>
    <th class=ttl>PMB #</th>
    <th class=ttl>Nama</th>
    <th class=ttl>JKelamin</th>
	<th class=ttl>Status</th>
    <th class=ttl>Formulir Program</th>
    <th class=ttl>Pilihan1</th>
    <th class=ttl>Pilihan2</th>
    <th class=ttl>Pilihan3</th>
	 <th class=ttl>Pilihan Kursi</th>
    <th class=ttl>Cetak</th>
    </tr> </thead>
                    <tbody>";
   global $no;                   
	 $r = mysqli_query($koneksi, $s);
	  while ($w = mysqli_fetch_array($r)) {
      $no++;
      if ($w['LulusUjian']=='Y'){
          $c="style=color:green";
      }else{
          $c="style=color:black";
      }
  echo"<tr $c>
    <td class=inp width=10>$no</td>
    <td class=ul1 width=70>
      <a href='#' onClick=\"javascript:IsiFormulir(0,'$gel','$w[PMBID]')\" />
      <i class='fa fa-edit'></i></a>
      </td>
    <td class=ul1 width=80>$w[PMBID]</td>
    <td class=cna=NA=>$w[Nama]</td>
	  <td>$w[Kelamin]</td>
    
    <td class=cna=NA= width=70>$w[STA]</td>
    <td class=cna=NA= width=220>$w[FRM] - $w[PRG]</td>
    <td class=cna=NA= width=140>$w[P1]</td>
    <td class=cna=NA2= width=140>$w[P2]</td>
    <td class=cna=NA3= width=140>$w[P3]</td>
	<td class=cna=NA= width=150 align=center><a href='#' onClick=\"PilihKursi('$w[PMBID]', '$gel')\">$w[_JenisKursi]</a></td>
    <td class=ul1 width=80 align=center>
      <a href='#' onClick=\"javascript:CetakKartu('$w[PMBID]')\" /><i class='fa fa-print'></i></a>
      $w[CetakKartu]
      </td>
	
    </tr>";
	  }
  echo"</tbody></table></div>
  </div>
  </div>";

}

?>
