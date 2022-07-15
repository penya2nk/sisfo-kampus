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
$dt        		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM vw_aktifkul where MhswID='".strfilter($_GET['MhswID'])."'")); // and TahunID='$_SESSION[tahun_akademik]'
$ProgramID 		= $dt['ProgramID'];

$NamaMhsx 		= strtolower($dt['Nama']); //strtoupper($kalimat);
$NamaMhs		= ucwords($NamaMhsx);

$Alamatx 		= strtolower($dt['Alamat']); //strtoupper($kalimat);
$Alamat			= ucwords($Alamatx);

$TempatLahirx 	= strtolower($dt['TempatLahir']); //strtoupper($kalimat);
$TempatLahir	= ucwords($TempatLahirx);

$tgllahir  = tgl_indo($dt['TanggalLahir']);
$program   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProgramID,Nama FROM program where ProgramID='$ProgramID'"));
$prodi     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='".strfilter($_GET['prodi'])."'"));

if ($_GET['prodi']=='SI'){
   $noreg="1031230515011";
}else{
   $noreg="1031231111004";
}



$content .= "

<table width='700' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr style=font-size:18px;>
<td align='center '><b ><u>SURAT KETERANGAN AKTIF KULIAH</u></b></td>
</tr>
<tr style=font-size:15px;font-family:Arial;>
<td align='center' >Nomor: $dt[TextSurat]</td>
</tr>
</table>     
<br>
<br>

<table width='700' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr style=font-size:15px;font-family:Arial;>
  <td width='700' >Saya yang bertanda tangan di bawah ini:</td>
</tr>
</table>
<br>
<table width='700' border='0' cellpadding='0' cellspacing='0' align='center'>

<tr style=font-size:15px;font-family:Arial;>
  <td width='152' >Nama</td>
<td width='5' >:</td>       
<td width='580' > $prodi[Pejabat]</td>           
</tr>

<tr style=font-size:15px;font-family:Arial;>
  <td >Jabatan</td>
<td >:</td>            
<td > Ka. Prodi $prodi[Nama]</td>             
</tr>
<tr>
  <td></td>
<td></td>            
<td > $nbsp</td>             
</tr>
</table>
<br>
<br>
<table width='700' border='0' cellpadding='0' cellspacing='0' align='justify'>
  <tr style=font-size:15px;font-family:Arial;>
    <td width='630' align='justify' >Dengan ini menerangkan bahwa: </td>
  </tr>
</table>
<br>
<table width='700' border='0' cellpadding='0' cellspacing='0' align='center'>
  <tr style=font-size:15px;font-family:Arial;>
    <td width='152' >Nama Mahasiswa</td>
    <td width='5' >:</td>
    <td width='580' > $NamaMhs</td>
  </tr>
  
  <tr style=font-size:15px;font-family:Arial;>
    <td width='152' >NIM</td>
    <td width='5' >:</td>
    <td width='580' > $dt[MhswID]</td>
  </tr>
  
  <tr style=font-size:15px;font-family:Arial;>
    <td>Program Studi</td>
    <td> :</td>
    <td>$prodi[Nama]</td>
  </tr>
  
   <tr style=font-size:15px;font-family:Arial;>
    <td >Tempat/Tanggal lahir</td>
    <td >:</td>
    <td > $TempatLahir / $tgllahir </td>
  </tr>
  
   <tr style=font-size:15px;font-family:Arial;>
    <td >Alamat</td>
    <td >:</td>
    <td > $Alamat</td>
  </tr> 
  <tr>
  <td></td>
<td></td>            
<td style=font-size:15px;font-family:Arial;> $nbsp</td>             
</tr>
</table>
<br>
<br>
<table width='700' border='0' cellpadding='0' cellspacing='0' align='justify'>
  <tr style=font-size:15px;font-family:Arial;>
    <td width='630' align='justify' >Adalah <u>benar</u> mahasiswa Sekolah Tinggi Manajemen Informatika (STMIK) Hang Tuah Pekanbaru Program Studi $prodi[Nama] dan aktif  kuliah di Semester $dt[Semester] TA $_SESSION[tahun_akademik].

</td>
</tr>

<tr style=font-size:15px;font-family:Arial;>
<td width='630' align='justify'>&nbsp;
</td>
  </tr>  
  
    <tr style=font-size:15px;font-family:Arial;>
    <td width='630' align='justify' >Demikianlah Surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
</td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<br>
<table border=0 width='700' align='center' cellpadding='0' cellspacing='0' >
<tr style=font-size:15px;font-family:Arial;>
<td width='330' >&nbsp;</td>
<td width='120' >Dikeluarkan di</td>
<td width='200' >: Pekanbaru</td>
</tr>
<tr style=font-size:15px;font-family:Arial;text-align:left;>
<td>&nbsp;</td>
<td>Pada Tanggal</td>
<td>: ".tgl_indo(date('Y-m-d'))."</td>
</tr>

<tr style=font-size:15px;font-family:Arial;text-align:left;>
  <td align='left'></td>
  <td colspan='3'>----------------------------------------------</td>
</tr>

<tr>
  <td align='left'></td>
  <td colspan='3' align='left' style=font-size:15px;font-family:Arial;>Ketua Prodi $prodi[Nama]</td>
</tr>
<tr>
  <td align='left'></td>
  <td colspan='3' align='left' >&nbsp;</td>
</tr>
<tr>
  <td align='left'></td>
  <td colspan='3' align='left' >&nbsp;</td>
</tr>
<tr>
  <td align='left'></td>
  <td colspan='3' align='left' >&nbsp;</td>
</tr>
<tr>
  <td align='left'></td>
  <td colspan='3' align='left' >&nbsp;</td>
</tr>

<tr>
<td align='left'></td>
<td colspan='3' align='left' style=font-size:15px;font-family:Arial;>$prodi[Pejabat]</td>
</tr>

<tr>
<td align='left'></td>
<td colspan='3'>----------------------------------------------------</td>
</tr>

<tr>
<td align='left'></td>
<td colspan='3' align='left' style=font-size:15px;font-family:Arial;>No Reg: $noreg</td>
</tr>
</table>";


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