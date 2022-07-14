<?php
error_reporting(0);
session_start();
include_once "../academic_sisfo1.php";

ViewHeaderApps("Formulir");

$md = $_REQUEST['md']+0;
$id = sqling($_REQUEST['id']);
$bck = sqling($_REQUEST['bck']);

$lungo = (empty($_REQUEST['lungo']))? 'Edit' : $_REQUEST['lungo'];
$lungo($md, $id, $bck);

function Edit($md, $id, $bck) {
  if ($md == 0) {
    $jdl = "Edit Formulir";
    $w = AmbilFieldx('pmbformulir', "KodeID='".KodeID."' and PMBFormulirID", $id, "*");
  }
  elseif ($md == 1) {
    $jdl = "Tambah Formulir";
    $w = array();
    $w['JumlahPilihan'] = 1;
	$w['USM'] = 'N';
	$w['Wawancara'] = 'N';
	$w['Prasyarat'] = 'N';
    $w['NA'] = 'N';
  }
  else die(PesanError('Error', "Mode edit tidak dikenali."));
  
  TitleApps($jdl);
  // Parameters
  $optjml = AmbilComboNomor(1, 3, $w['JumlahPilihan']);
  $na = ($w['NA'] == 'Y')? 'checked' : '';
  $usmchecked = ($w['USM'] == 'Y')? 'checked' : '' ;
  $wawancarachecked = ($w['Wawancara'] == 'Y')? 'checked' : '';
  $prasyaratchecked = ($w['Prasyarat'] == 'Y')? 'checked' : '';
  $prasyaratvisibility = ($w['Prasyarat'] == 'Y')? 'visible' : 'hidden';
  $Prasyarat = GetPrasyarat($w['PrasyaratExtra']);
  $webdefchecked = ($w['WebDef'] == 'Y')? 'checked' : '' ;
  CheckFormScript('Nama,Harga');
  //$TestMasuk = GetTestMasuk($w['USM']);
  echo "<p>
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:70%' align='center'>
  <form action='../$_SESSION[ndelox].formulir.edit.php' method=POST onSubmit=\"return CheckForm(this)\">
  <input type=hidden name='lungo' value='Simpan' />
  <input type=hidden name='md' value='$md' />
  <input type=hidden name='id' value='$id' />
  <input type=hidden name='bck' value='$bck' />
  
  <tr><td class=inp>Formulir:</td>
      <td class=ul1><input type=text name='Nama' value='$w[Nama]'
        size=30 maxlength=50 />
      </td></tr>
  <tr><td class=inp>Jumlah Pilihan:</td>
      <td class=ul1><select name='JumlahPilihan'>$optjml</select></td>
      </tr>
  <tr><td class=inp>Harga:</td>
      <td class=ul1><input type=text name='Harga' value='$w[Harga]' size=10 maxlength=15 /></td>
      </tr>
  <tr><td class=inp>Ada USM?</td>
      <td class=ul1><input type=checkbox name='USM' value='Y' $usmchecked></td>
      </tr>
  <tr><td class=inp>Ada Wawancara?</td>
	  <td class=ul1><input type=checkbox name='Wawancara' value='Y' $wawancarachecked></td>
	  </tr>
  <tr><td class=inp>Ada Prasyarat?</td>
	  <td class=ul1>
	  <div style='float:left'><input type=checkbox name='Prasyarat' value='Y' $prasyaratchecked onClick=\"toggleDiv('PrasyaratPlus');\"></div>
	  <div id='PrasyaratPlus' style='float:left; padding-left:15px; padding-right:15px; background-color:#DDDDDD; visibility:$prasyaratvisibility;'>
		$Prasyarat
	  </div>
	  </td>
	  </tr>
  <tr><td class=inp>Keterangan:</td>
      <td class=ul1>
      <textarea name='Keterangan' cols=30 rows=3>$w[Keterangan]</textarea>
      </td></tr>
  <tr><td class=inp>NA (tidak aktif)?</td>
      <td class=ul1>
      <input type=checkbox name='NA' value='Y' $na /> *) Beri centang jika tidak aktif
      </td>
      </tr>
  
  <tr><td class=ul1 colspan=2 align=center>
      <input type=submit name='Simpan' value='Simpan' />
      <input type=button name='Batal' value='Batal'
        onClick=\"window.close()\" />
      </td>
      </tr>
  </form>
  </table></div>
  </div>
  </div></p>
  <script>
	function toggleDiv(name)
	{	if(document.getElementById(name).style.visibility == 'hidden')
			document.getElementById(name).style.visibility = 'visible';
		else
			document.getElementById(name).style.visibility = 'hidden';
	}
  </script>";
}

function GetPrasyarat($string)
{	
global $koneksi;
$a = '';
	if(empty($string))
	{	$s = "select * from pmbformsyarat where KodeID='".KodeID."' and NA='N'";
		$r = mysqli_query($koneksi, $s);
		$n = 0; $n1 = 0;
		while($w = mysqli_fetch_array($r))
		{	$n++;
			$a .= "<input type=checkbox name='Prasyarat$n' value='$w[PMBFormSyaratID]'>$w[Nama] ";
			if($w['AdaScript'] == 'Y')
			{	$pos = strpos($w['Script'], '=INPUT=');
				if($pos) 
				{	$n1++;
					$a .= ": <input type=text name='PrasyaratExtra$n' value='' size=2 maxlength=5>";
				}
			}
			$a .= "<br>";
		}
	}
	else
	{
	  	/*
		$_pra = ''; $Name = array();
    $arrpra = GetArrayprasyarat($Name); 
    for ($i=0; $i<sizeof($arrpra); $i++) {
      $arr = explode('~', $arrPrasyarat);			
      $ck = (array_search($arrpra[$i], $arr[0]) === true and $arr[1]=='Y')? 'checked' : '';
      $a .= "<input type=checkbox name='Prasyarat$n' value='$arr[0]' $ck> " . $Name[$i] . "<br />";
      $pos = strpos($pmbformsyarat['Script'], '=INPUT=');
			if($pos > 0) 
				{	$a .= ": <input type=text name='PrasyaratExtra$n' value='$arr[2]' size=2 maxlength=5>";
				}
			$a .= "<br>";
    }
		*/
		
	
		$arrPrasyarat = explode('|', $string);
		$n = 0;
		
		foreach($arrPrasyarat as $persyarat)
		{	$n++;
			$arr = explode('~', $persyarat);
			// $arr[0] adalah PMBFormSyaratID, $arr[1] adalah 'Y' atau 'N' digunakan, $arr[2] adalah Tambahan input untuk prasyarat
			
			$pmbformsyarat = AmbilFieldx('pmbformsyarat', "PMBFormSyaratID='$n' and NA='N' and KodeID", KodeID, "*");
			if(!empty($pmbformsyarat)){
        $prasyaratchecked = ($arr[1] == 'Y')? 'checked' : '';
  			$a .= "<input type=checkbox name='Prasyarat$n' value='$n' $prasyaratchecked>$pmbformsyarat[Nama] ";
  			if($arr[1] == 'Y')
  			{   $pos = strpos($pmbformsyarat['Script'], '=INPUT=');
  				if($pos > 0) 
  				{	$a .= ": <input type=text name='PrasyaratExtra$n' value='$arr[2]' size=2 maxlength=5>";
  				}
  			}
  			$a .= "<br>";
      }			
		}
		
	}
	
	$a .= "<input type=hidden name='JumlahPrasyarat' value = '$n'>";
	return $a;
}
function GetArrayprasyarat(&$Name=array()) {
	global $koneksi;
  $s = "select * from pmbformsyarat where KodeID='".KodeID."' and NA='N'";
  $r = mysqli_query($koneksi, $s);
  $arr = array();
  while ($w = mysqli_fetch_array($r)) {
    $arr[] = $w['PMBFormSyaratID'];
    $Name[] = $w['Nama'];
  }
  return $arr;
}
function Simpan($md, $id, $bck) {
	global $koneksi;
  TutupScript();
  $Nama = sqling($_REQUEST['Nama']);
  $JumlahPilihan = $_REQUEST['JumlahPilihan']+0;
  $Harga = $_REQUEST['Harga']+0;
  $Keterangan = sqling($_REQUEST['Keterangan']);
  $USM = (empty($_REQUEST['USM']))? 'N' : 'Y';
  $Wawancara = (empty($_REQUEST['Wawancara']))? 'N' : 'Y';
  $Prasyarat = (empty($_REQUEST['Prasyarat']))? 'N' : 'Y';
  $WebDef = (empty($_REQUEST['WebDef']))? 'N' : 'Y';
  $NA = (empty($_REQUEST['NA']))? 'N' : 'Y';
  
  $arrPrasyarat = array();
  if($Prasyarat == 'Y')
  {	$JumlahPrasyarat = $_REQUEST['JumlahPrasyarat'];
	for($i = 1; $i <= $JumlahPrasyarat; $i++)
	{	$Syarat = $_REQUEST['Prasyarat'.$i];
		if(!empty($Syarat))
		{	$PrasyaratExtra = $_REQUEST['PrasyaratExtra'.$i];
			$arrPrasyarat[] = "$Syarat~Y~$PrasyaratExtra";
		}
		else
		{	$arrPrasyarat[] = "$Syarat~N~";
		}
	}
  }
  $prasyaratstring = implode('|', $arrPrasyarat);
  /*
  if($WebDef == 'Y')
  {	$s = "update pmbformulir set WebDef='N'";
	$r = mysqli_query($koneksi, $s);
  }
  */
  if ($md == 0) {
    $s = "update pmbformulir
      set Nama = upper('$Nama'),
          JumlahPilihan = '$JumlahPilihan',
          Harga = '$Harga',
          Keterangan = '$Keterangan',
		  USM = '$USM',
		  Wawancara = '$Wawancara',
		  Prasyarat = '$Prasyarat',
		  PrasyaratExtra = '$prasyaratstring',
          NA = '$NA',
          LoginEdit = '$_SESSION[_Login]',
          TglEdit = now()
      where KodeID = '".KodeID."' and PMBFormulirID = $id ";
    $r = mysqli_query($koneksi, $s);
    echo "<script>ttutup('$_SESSION[ndelox]');</script>";
  }
  elseif ($md == 1) {
    $s = "insert into pmbformulir
      (Nama, KodeID, JumlahPilihan, Harga, 
      Keterangan, USM, Wawancara, Prasyarat, PrasyaratExtra, LoginBuat, TglBuat, NA)
      values
      (upper('$Nama'), '".KodeID."', '$JumlahPilihan', '$Harga',
      '$Keterangan', '$USM', '$Wawancara', '$Prasyarat', '$prasyaratstring', '$_SESSION[_Login]', now(), '$NA')";
    $r = mysqli_query($koneksi, $s);
    echo "<script>ttutup('$_SESSION[ndelox]');</script>";
  }
  else die(PesanError('Error', "Mode edit tidak ditemukan."));
}

function TutupScript() {
echo <<<SCR
<SCRIPT>
  function ttutup(bck) {
    opener.location='../index.php?ndelox=$_SESSION[ndelox]';
    self.close();
    return false;
  }
</SCRIPT>
SCR;
}
?>
