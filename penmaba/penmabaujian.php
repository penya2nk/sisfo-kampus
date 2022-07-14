<?php
function CetakKartuScript() {
  echo 
  "<script>
  function CetakKartuUSM(prodi, prodiusmid, pmbusmid) {
	lnk = '$_SESSION[ndelox].cetakusm.php?prodi='+prodi+'&prodiusmid='+prodiusmid+'&pmbusmid='+pmbusmid;
	win2 = window.open(lnk, '', 'width=800, height=600, scrollbars, status, resizable');
    if (win2.opener == null) childWindow.opener = self;
    window.location = '?ndelox=$_SESSION[ndelox]';
  }
  
  function CetakDHU(gel, ruang, prodi, pmbusm) {
    lnk = '$_SESSION[ndelox].cetakdhu.php?prodi='+prodi+'&gel='+gel+'&ruang='+ruang+'&pmbusm='+pmbusm;
	win2 = window.open(lnk, '', 'width=800, height=600, scrollbars, status, resizable');
    if (win2.opener == null) childWindow.opener = self;
    window.location = '?ndelox=$_SESSION[ndelox]';
  }
  </script>";
}

function loadJavaScripts()
	{	echo "
			<SCRIPT LANGUAGE='JavaScript'>

			function CheckAll(chk)
			{	
				total = (document.getElementById('JumlahCount')).value;
				for (i = 1; i <= total; i++)
				{	if(document.getElementById(chk+i).disabled==false)
						(document.getElementById(chk+i)).checked = true;
				}
			}
			
			function UnCheckAll(chk)
			{
				total = (document.getElementById('JumlahCount')).value;
				for (i = 1; i <= total; i++)
				{	if(document.getElementById(chk+i).disabled==false)
						document.getElementById(chk+i).checked = false;
				}
			}
			
			function ChangeGos(lungo, myform)
			{	(document.getElementById('lungo')).value = lungo;
				myform.submit();
			}
			
			function ignoreCheckBoxDisable(chk, myform)
			{	total = (document.getElementById('JumlahCount')).value;
				for (i = 1; i <= total; i++)
				{	
					(document.getElementById(chk+i)).disabled = false;
				}
				myform.submit();
			}
			</script>
		";		
	}

function LihatPerRuang($gel)
{	
global $koneksi;
$optprodi = AmbilCombo2('prodi', "concat(ProdiID, ' - ', Nama)", 'ProdiID', $_SESSION['_usm_prodi'], "KodeID='".KodeID."'", 'ProdiID');
    $usmpmbopt = AmbilCombo2('pmbusm', "concat(PMBUSMID, ' - ', Nama)", 'PMBUSMID', $_SESSION['_usm_pmbusm'], "KodeID='".KodeID."'", 'PMBUSMID');
	
	$pmbusmstring = (empty($_SESSION['_usm_pmbusm'])) ? "" : "and pu.PMBUSMID='$_SESSION[_usm_pmbusm]'";
	$s = "select distinct(ru.RuangID) as _RuangID 
			from ruangusm ru left outer join prodiusm pu on ru.ProdiUSMID=pu.ProdiUSMID
			where ru.KodeID='".KodeID."' 
			and ru.PMBPeriodID='$gel' $pmbusmstring 
			order by ru.RuangID";
	$r = mysqli_query($koneksi, $s);
	$w = mysqli_fetch_array($r);
	$ruangdef = $w['_RuangID'];
	$arrruang[] = $w['_RuangID'];
	while($w = mysqli_fetch_array($r))
	{	$arrruang[] = $w['_RuangID'];
	}
	//$ruangdef = (!empty($_SESSION['_usm_ruang']))? $_SESSION['_usm_ruang'] : ((empty($ruangdef))? "" : $ruangdef);
	//$ruangopt = GetOptionsFromData($arrruang, $ruangdef, 1);
	//$_SESSION['_usm_ruang'] = $ruangdef;				
	
	CetakKartuScript();
	
	$classHadir = ($_SESSION['_usm_jenisx'] == 0)? 'class=menuaktif' : 'class=menuitem';
	$classNilai = ($_SESSION['_usm_jenisx'] == 1)? 'class=menuaktif' : 'class=menuitem';
	echo "
	<div class='card'>
	<div class='card-header'>
	<div align='center'>
	  <form action='?' method=POST>
	  <input type=hidden name='ndelox' value='$_SESSION[ndelox]'>
	  $classHadir><a href='?ndelox=$_SESSION[ndelox]&lungo=&_usm_jenisx=0'>Daftar Hadir</a>
	  Periode : <input type=text name=tahunpmb value='$gel' size=10 disabled>
	  $classNilai><a href='?ndelox=$_SESSION[ndelox]&lungo=&_usm_jenisx=1'>Daftar Nilai</a>
	  Program Studi: <select name='_usm_prodi' onChange='this.form.submit()'>$optprodi</select>
	  Mata Uji: <select name='_usm_pmbusm' onChange='this.form.submit()'>$usmpmbopt</select>
		  <a href='#' onClick=\"javascript:CetakDHU('$gel', '$_SESSION[_usm_ruang]', '$_SESSION[_usm_prodi]')\" /><img title='Print Denah USM' src='img/print.png' />
	  </form>
	  </div>
	  </div>
	  </div>";
	 
	$reltitle = (empty($_SESSION['_usm_pmbusm']))? "" : "<th class=ttl width=40>Hadir?</th>";
	$topbutton = (empty($_SESSION['_usm_pmbusm']))? "" : "<input type=button name='CheckAllMember' value='Hadir Semua' onClick=\"CheckAll('Hadir')\" />";
	$botbutton = (empty($_SESSION['_usm_pmbusm']))? "" : "<input type=button name='SubmitButton' value='Simpan' onClick=\"ignoreCheckBoxDisable('Hadir', this.form)\" />";
	$botbutton2 = (empty($_SESSION['_usm_pmbusm']))? "" : "<input type=button name='UnCheckAllMember' value='Clear Semua' onClick=\"UnCheckAll('Hadir')\" />";
	
	loadJavaScripts();

	$ProdiUSMID = AmbilOneField('prodiusm', "INSTR(concat('|', ProdiID, '|'), concat('|', '$_SESSION[_usm_prodi]', '|'))!=0 and PMBUSMID='$_SESSION[_usm_pmbusm]' and PMBPeriodID = '$gel' and KodeID", KodeID, 'ProdiUSMID'); 
	
	if(empty($ProdiUSMID)) echo PesanError("Tidak ada data", "Tidak ada jadwal USM yang ditemukan.<br>
									Harap menghubungi Kepala PMB untuk men-setup jadwal terlebih dahulu");
	else echo  "<Iframe name='frame$n' src='$_SESSION[ndelox].frame.php?ProdiUSMID=$ProdiUSMID&gel=$gel' align=center width=800 height=750 frameborder=0></Iframe>";
	
}

function GetOptionsFromData($arr, $default, $blank=0)
{	$a = '';

	if($blank==0)
	{	$a.= "<option value=''></option>";
	}
	foreach($arr as $index)
	{	$element = explode('~', $index);
		$selected = ($element[0] == $default)? 'selected' : '';
		$valstring = implode(' - ', $element);
		$a.= "<option value='$element[0]' $selected>$valstring</option>";
	}

	return $a;
}

$gelombang = AmbilOneField('pmbperiod', "KodeID='".KodeID."' and NA", 'N', "PMBPeriodID");
$_usm_prodi = GainVariabelx('_usm_prodi');
$_usm_ruang = GainVariabelx('_usm_ruang');
$_usm_pmbusm = GainVariabelx('_usm_pmbusm');
$_usm_daftar = GainVariabelx('_usm_daftar', 1);
$_usm_jenisx = GainVariabelx('_usm_jenisx', 0);
$lungo = (empty($_REQUEST['lungo']))? 'LihatPerRuang' : $_REQUEST['lungo'];

// *** Main ***
TitleApps ("UJIAN SARINGAN MASUK");
//PilihProdiPMB($gelombang);
//TampilkanCetakLabel($pmbid);
$lungo($gelombang);

?>
