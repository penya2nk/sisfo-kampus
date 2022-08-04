<?php 
session_start();
error_reporting(0);
include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
require_once ("../punksi/html2pdf/vendor/autoload.php");
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

if (empty($_SESSION['_Login']) && empty($_SESSION['_LevelID'])){
	header("Location: ../login.php");
}
else{

include "headerx-rpt.php"; 	

$jdw  	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jadwal_kp WHERE JadwalID='".strfilter($_GET['JadwalID'])."'"));
$dt  		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT MhswID,Nama,ProdiID,Handphone FROM mhsw WHERE MhswID='".$jdw['MhswID']."'"));
if ($dt['Handphone']==''){$hp='-';}else{$hp=$dt['Handphone'];}
$namamhsx 	= strtolower($dt['Nama']);
$namamhs	= ucwords($namamhsx);	

$p  		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jadwal_kp WHERE JadwalID='".strfilter($_GET['JadwalID'])."'"));
$judulx 	= strtolower($p['Judul']);
$Judul		= ucwords($judulx);

// $isi_judul 		= strip_tags($Judul); 
// $isijudulawal 	= substr($isi_judul,0,79); 
// $isijudulawal 	= substr($isi_judul,0,strrpos($isijudulawal," "));
// $judulberikutnya1= substr($isi_judul,-40,65);


//08/Prodi-TI/STMIK-HTP/III/2016/
$ProdiID   	= $dt['ProdiID'];
if ($ProdiID=='SI'){ 
	$prod	="Sistem Informasi"; 
	$kaprodi="Herianto, S.Kom, M.Kom"; 
	$KD		="07"; 
	$KDPROD	="Prodi-SI"; 
	$nidn	="1008068202";
	}
else{ 
	$prod="Teknik Informatika"; 
	$kaprodi="Yuda Irawan, S.Kom, M.TI"; 
	$KD		="08"; 
	$KDPROD	="Prodi-TI";
	$nidn	="1016079101";
}

$bln	=date('m');
$tahun	=date('Y');
if($bln=='1'){$BlnRomawi="I";}elseif($bln=='2'){$BlnRomawi="II";} elseif($bln=='3'){$BlnRomawi="III";} elseif($bln=='4'){$BlnRomawi="IV";} elseif($bln=='5'){$BlnRomawi="V";}
elseif($bln=='6'){$BlnRomawi="VI";} elseif($bln=='7'){$BlnRomawi="VII";} elseif($bln=='8'){$BlnRomawi="VIII";} elseif($bln=='9'){$BlnRomawi="IX";} elseif($bln=='10'){$BlnRomawi="X";}
elseif($bln=='11'){$BlnRomawi="XI";}else{{$BlnRomawi="XII";}}

$content .= "



<table width='700' border='0' cellpadding='0' cellspacing='0' align='center'>
  <tr>
    <td  align='center'><b>PENGAJUAN JUDUL KERJA PRAKTEK $_GET[tahun]</b></td>
  </tr>
</table>
<br>
<table>  
  <tr>
    <td colspan='3'>&nbsp;</td>
  </tr>
  <tr>
    <td colspan='3'>Dengan hormat,</td>
  </tr>
  <tr>
    <td colspan='3'>Saya yang bertanda tangan di bawah ini:</td>
  </tr>
  <tr>
    <td colspan='3'>&nbsp;</td>
  </tr>
  <tr>
    <td width='120'>Kelompok</td>
    <td width='6'>:</td>
    <td width='879'>$p[KelompokID]</td>
  </tr>
  ";
  $a=0;
  	$mhs = mysqli_query($koneksi, "SELECT
			jadwal_kp_anggota.JadwalID,jadwal_kp_anggota.MhswID,jadwal_kp_anggota.KelompokID,
			mhsw.Nama FROM mhsw,jadwal_kp_anggota 
			WHERE mhsw.MhswID=jadwal_kp_anggota.MhswID 
			AND jadwal_kp_anggota.JadwalID='".strfilter($_GET['JadwalID'])."'");	
	while($m=mysqli_fetch_array($mhs)){
	$a++;
	$Namag 		= strtolower($m['Nama']);
	$NamaMhs	= ucwords($Namag);
	$content .=" <tr class='batas2' align='left'>
	<td align=right></td>
	<td >:</td><td >$a. $m[MhswID] - $NamaMhs</td>
	</tr>";
	}
  $content .="

  <tr>
    <td>Program Studi</td>
    <td>:</td>
    <td>$prod</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan='3'>Bermaksud mengajukan judul kerja praktek sebagai berikut:</td>
  </tr>
  <tr>
    <td>Judul</td>
    <td>:</td>
    <td></td>
  </tr>

  <tr>
    <td colspan=3 align=justify><textarea cols=73 rows=2>$Judul</textarea></td>
  </tr>

  <tr>
    <td>Tempat Kerja Praktek</td>
    <td>:</td>
    <td>$p[TempatPenelitian]</td>
  </tr>
  <tr>
    <td>Deskripsi</td>
    <td>:</td>
    <td></td>
  </tr>

<tr>
    <td colspan=3 align=justify><textarea cols=73 rows=10>$p[Deskripsi]</textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

<table border=0 width='100%' align='left' cellpadding='0' cellspacing='0' >
<tr>
  <td width='10' align='left'>Menyetujui</td>
  <td width='250' align='left'>&nbsp;</td>
  <td width='250' align='left'>Pekanbaru, ".tgl_indo(date('Y-m-d'))."</td>
</tr>

<tr>
  <td align='left' >Ka. Prodi </td>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  </tr>

<tr>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  </tr>

<tr>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
</tr>
  
<tr>
  <td align='left' ><u>$kaprodi</u></td>
  <td align='left' ></td>
  <td align='left' ><u>$namamhs</u></td>
  
  </tr>  
  
<tr>
  <td align='left' >NIDN: $nidn</td>
  <td align='left' ></td>
  <td align='left' ></td>
  </tr>
<tr>
  <td align='left' ></td>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
</tr>
<tr>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
</tr>
</table>

<table border=0 width='100%' align='left' cellpadding='0' cellspacing='0' >
<tr>
  <td align='left' width='0'>Pembimbing</td>
  <td align='left' width='0'>:</td>
  <td width='0' align='left'> __________________________________ </td>
</tr>

<tr>
  <td colspan='3' align='left' ><font style='font-size:8px'><i>(Ditentukan dan diisi oleh Ka. Program Studi)</i></font></td>
  </tr>
<tr>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
</tr>
</table>
<table border=0 width='100%' align='left' cellpadding='0' cellspacing='0' >
<tr>
  <td  align='left' >Catatan Perbaikan: </td>
</tr>

<tr>
  <td  align='left' > _________________________________________________ </td>
</tr>


</table>


<font style='font-size:8px'>Login by: $_SESSION[_Login] ".tgl_indo(date('Y-m-d'))." ".date('H:i:s') . " WIB - Univ Tekno Indo Support System</font>";

try {
  ob_start();
  $html2pdf = new Html2Pdf('P','Legal','fr', true, 'UTF-8', array(15, 15, 15, 15), false); 
  $html2pdf->writeHTML($content);
  $html2pdf->output();
} catch (Html2PdfException $e) {
  $html2pdf->clean();

  $formatter = new ExceptionFormatter($e);
  echo $formatter->getHtmlMessage();
}

}	
?>	