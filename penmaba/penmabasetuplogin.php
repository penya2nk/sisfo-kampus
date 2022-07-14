<?php

$gelombang = AmbilOneField('pmbperiod', "KodeID='".KodeID."' and NA", 'N', "PMBPeriodID");
$_pmbLoginNama = GainVariabelx('_pmbLoginNama');
$_pmbLoginAplikanID = GainVariabelx('_pmbLoginAplikanID');
$_pmbLoginPage = GainVariabelx('_pmbLoginPage');
$_pmbLoginUrut = GainVariabelx('_pmbLoginUrut', 1);
$arrUrut = array('Nomer Aplikan~a.AplikanID asc, a.Nama', 'Nomer Aplikan (balik)~a.AplikanID desc, a.Nama', 'Nama~a.Nama');
RandomStringScript();

TitleApps("LOGIN DAN PASSWORD APLIKAN - $gelombang");
if (empty($gelombang)) {
  echo PesanError("Error",
    "Tidak ada gelombang PMB yang aktif.<br />
    Harap setup gelombang dulu.");
}
else {
  $lungo = (empty($_REQUEST['lungo']))? 'DftrLoginForm' : $_REQUEST['lungo'];
  $lungo($gelombang);
}

function DftrLoginForm($gel)
{	$l = DftrLoginList($gel);
	$i = EditEntry($gel);
	echo "
<div class='row'>
		<div class='col-md-6'>
    $l
    </div>
		<div class='col-md-6'>
		$i
    </div>
</div>";
}

function GetUrutanAplikan() {
  global $arrUrut;
  $a = ''; $i = 0;
  foreach ($arrUrut as $u) {
    $_u = explode('~', $u);
    $sel = ($i == $_SESSION['_pmbLoginUrut'])? 'selected' : '';
    $a .= "<option value='$i' $sel>". $_u[0] ."</option>";
    $i++;
  }
  return $a;
}

function TampilkanHeader($gel) {
  $opturut = GetUrutanAplikan();
  $a = "
  <table >
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='lungo' value='' />
  <input type=hidden name='_pmbLoginPage' value='0' />
  
  <tr>
      <td class=inp>Cari Nama:</td>
      <td class=ul1><input type=text name='_pmbLoginNama' value='$_SESSION[_pmbLoginNama]' size=20 maxlength=30 /></td>
      <td class=inp width=100>Urutkan:</td>
      <td class=ul1><select name='_pmbLoginUrut'>$opturut</select></td>
      </tr>
  <tr>
      <td class=inp></td>
      <td class=ul1></td>
      <td class=ul1 colspan=2 align=center nowrap>
      <input type=submit name='Submit' value='Submit' />
      <input type=button name='Reset' value='Reset'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=&_pmbLoginPage=0&_pmbLoginNama=&_pmbLoginNomer='\" />
      </td>
  </form>
  </table>
";
  return $a;
}

function DftrLoginList($gel) {
  $a = TampilkanHeader($gel);
  
  global $arrUrut, $koneksi;

  // Filter formulir
  $whr = array();
  if (!empty($_SESSION['_pmbLoginNama']))  $whr[] = "a.Nama like '%$_SESSION[_pmbLoginNama]%'";
  
  $_whr = implode(' and ', $whr);
  $_whr = (empty($_whr))? '' : 'and '.$_whr;
  
  $s = "select a.AplikanID, a.Nama, a.JmlReset, if(a.PasswordBaru='Y', '<font color=red>Default</font>', '<font color=blue>Set</font>') as PASSSTATUS, a.NA, if(a.AplikanID='$_SESSION[_pmbLoginAplikanID]', 'inp1', 'ul1') as CLASS 
  from aplikan a 
    where a.KodeID = '".KodeID."' 
      and a.PMBPeriodID='$gel'
      $_whr";
  echo"
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example1' class='table table-sm table-striped'>
    <thead>
    <tr style='background:purple;color:white'>
    <th class=ttl>#</th>
    <th class=ttl>PMB #</th>
    <th class=ttl>Nama</th>
    <th class=ttl>Status</th>
    <th class=ttl>Reset</th>
    </tr></thead><tbody>";
    $r = mysqli_query($koneksi, $s);
    $no=0;
    while($w=mysqli_fetch_array($r)){
      $no++;
  echo "<tr>
    <td class=inp width=10>$no</td>
    <td class==CLASS= width=80>$w[AplikanID]</td>
    <td class==CLASS=><a href='?ndelox=$_SESSION[ndelox]&lungo=&_pmbLoginAplikanID=$w[AplikanID]'>$w[Nama]</td>
    <td class==CLASS= width=50 align=center>$w[PASSSTATUS]</td>
	  <td class==CLASS= width=50 align=center>$w[JmlReset]x</td>
    </tr>";
    }
    echo"</tbody></table>
";
}

function EditEntry($gel)
{	$aplikan = AmbilFieldx('aplikan', "AplikanID='$_SESSION[_pmbLoginAplikanID]' and KodeID", KodeID, '*');
	
	if(empty($aplikan))
	{	$aplikan['AplikanID'] = "<font color=red>Auto-generated</font>";
		$aplikanhidden = '';
		$aplikan['TanggalLahir'] = (date('Y')-18).'-'.date('m-d');
		$ResetPassword = '';
	}
	else
	{	$aplikanhidden = "<input type=hidden name='AplikanID' value='$aplikan[AplikanID]'>";
		$ResetPassword = "<input type=button name='DefPass' value='Reset Password' 
								onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=ResetPassword'\"/>"; 
	}
	$opttgllahir = AmbilComboTgl($aplikan['TanggalLahir'], 'TanggalLahir');
	
	CheckFormScript("Nama");
	$a = "
  <table id='example' class='table table-sm table-stripedx' style='width:100%'>
  <form action='?' method=POST onSubmit=\"return CheckForm(this)\">
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='lungo' value='Simpan' />
  
  <tr><td colspan=2><input type=button name='Reset' value='&raquo&raquo Form Baru &laquo&laquo'
			onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=&_pmbLoginAplikanID='\" /></td>
	  <td rowspan=3><a href='#' onClick=\"CetakKartu('$aplikan[AplikanID]', '$gel')\"><i class='fa fa-print'></i></a></td></tr>
  <tr>
      <td class=inp>No. PMB:</td>
      <td class=ul1>$aplikanhidden$aplikan[AplikanID]</td>
  </tr>
  <tr>
      <td class=inp>Nama Peserta:</td>
      <td class=ul1><input type=text name='Nama' value='".@$aplikan['Nama']."' size=20 maxlength=50></td>
  </tr>
  <tr>
      <td class=inp>Tanggal Lahir:</td>
      <td class=ul1 colspan=2>$opttgllahir</td>
  </tr>
  <tr>
      <td class=ul1 colspan=3 align=center nowrap>
		  <input type=submit name='Submit' value='Simpan' />
		  $ResetPassword
	  </td>
  </tr>
  </form>
  </table>

  <script>
	function CetakKartu(id, gel) {
    lnk = '$_SESSION[ndelox].kartu.php?gel='+gel+'&id='+id;
	win2 = window.open(lnk, '', 'width=800, height=700, scrollbars, status, resizable');
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>";
  return $a;
}

function Simpan($gel)
{	
global $koneksi;
	$AplikanID = $_REQUEST['AplikanID'];
	$Nama = $_REQUEST['Nama'];
	$TanggalLahir = "$_REQUEST[TanggalLahir_y]-$_REQUEST[TanggalLahir_m]-$_REQUEST[TanggalLahir_d]";
	
	if(empty($AplikanID) or $AplikanID=='')
	{
		$aplikanid = GetNextAplikanID($gel);
	
		$s = "insert into aplikan 
                set AplikanID='$aplikanid', 
                Nama='$Nama', 
                TanggalLahir='$TanggalLahir', 
                Login='$aplikanid', Password=PASSWORD('$TanggalLahir'), 
								KodeID='".KodeID."', PMBPeriodID='$gel', 
								LoginBuat='$_SESSION[_Login]', 
                TanggalBuat=now(), 
                LoginEdit='$_SESSION[_Login]', 
                TanggalEdit=now()";
		$r = mysqli_query($koneksi, $s);
		$page = 0;
	}
	else
	{	$aplikanid = $AplikanID;
		$s = "update aplikan set Nama='$Nama', TanggalLahir='$TanggalLahir',
								LoginEdit='$_SESSION[_Login]', TanggalEdit=now() where AplikanID='$aplikanid' and KodeID='".KodeID."'";
		$r = mysqli_query($koneksi, $s);
		
		$ttl2 = AmbilOneField('aplikan', "PMBPeriodID='$gel' and LEFT(AplikanID, 5)=LEFT('$AplikanID', 5) and AplikanID <= '$AplikanID' and KodeID", KodeID, 'count(AplikanID)');
		$ttl = AmbilOneField('pmb', "PMBPeriodID='$gel' and LEFT(AplikanID, 5)=LEFT('$AplikanID', 5) and KodeID", KodeID, 'count(AplikanID)');
		$page = ceil(($ttl-$ttl2)/10);
	}
	SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=&_pmbLoginAplikanID=$aplikanid&_pmbLoginPage=$page", 10);
}

function ResetPassword($gel)
{	
global $koneksi;
if(empty($_SESSION['_pmbLoginAplikanID']))
	{	echo PesanError("Gagal", "Tidak ada Login Aplikan yang dipilih untuk di-reset.");	
	}
	else
	{	$TanggalLahir = AmbilOneField('aplikan', "AplikanID='$_SESSION[_pmbLoginAplikanID]' and KodeID", KodeID, "TanggalLahir");
		
		$s = "update aplikan set Password=LEFT(PASSWORD('$TanggalLahir'), 10), PasswordBaru='Y', Hint='', HintAnswer='', JmlReset = JmlReset+1 where AplikanID='$_SESSION[_pmbLoginAplikanID]' and KodeID='".KodeID."'";
		$r = mysqli_query($koneksi, $s);
		echo Info("Berhasil", 
		"Reset berhasil.<br />
		Tampilan akan kembali ke semula dalam 3 detik.
		<hr size=1 color=silver />
		Atau klik: <a href='?ndelox=$_SESSION[ndelox]'>[ Kembali ]</a>");
		echo "<script type='text/javascript'>window.onload=setTimeout('window.location=\"?ndelox=$_SESSION[ndelox]\"', 3000);</script>";
	}
}

function GetNextAplikanID($gel) {
  $gelombang = AmbilFieldx('pmbperiod', "PMBPeriodID='$gel' and KodeID", KodeID, "FormatNoAplikan, DigitNoAplikan");
  // Buat nomer baru
  $nomer = str_pad('', $gelombang['DigitNoAplikan'], '_', STR_PAD_LEFT);
  $nomer = $gelombang['FormatNoAplikan'].$nomer;
  $akhir = AmbilOneField('aplikan',
    "AplikanID like '$nomer' and KodeID", KodeID, "max(AplikanID)");
  $nmr = str_replace($gelombang['FormatNoAplikan'], '', $akhir);
  $nmr++;
  $baru = str_pad($nmr, $gelombang['DigitNoAplikan'], '0', STR_PAD_LEFT);
  $baru = $gelombang['FormatNoAplikan'].$baru;
  return $baru;
}
?>
