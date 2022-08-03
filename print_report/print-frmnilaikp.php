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
$dt        = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM vw_jadwalkp where JadwalID='".strfilter($_GET[JadwalID])."'"));
$ProdiID   = $dt[ProdiID];

if ($ProdiID=='SI'){ $prod="Sistem Informasi"; $kaprodi="Herianto, M.Kom";}else{ $prod="Teknik Informatika"; $kaprodi="Eka Sabna, M.Pd, M.Kom";}

//$tgllahir  = tgl_indo($dt[TanggalLahir]);
//$program   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProgramID,Nama FROM program where ProgramID='$ProgramID'"));
//$prodi     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='$_GET[prodi]'"));

$content .= "

<table width='700' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td align='center'><b>FORM PENILAIAN SEMINAR HASIL KERJA PRAKTEK</b></td>
</tr>
<tr>
<td align='center'></td>
</tr>
</table>
<br>
<br>";

$sql= mysqli_query($koneksi, "SELECT * FROM vw_jadwalkp_anggota where JadwalID='".strfilter($_GET[JadwalID])."'");
while($data=mysqli_fetch_array($sql)){

$content .= "<table width='600' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td width='100'>Nama</td>
<td width='5'>:</td>       
<td width='580'>$data[Nama]</td>           
</tr>

<tr>
<td>NIM</td>
<td>:</td>            
<td >$data[MhswID]</td>             
</tr>

</table>
<br>
<table width='0' border='0.1' cellpadding='0' cellspacing='0' align='center'>
  <tr style='background-color:#E6E6E6'>
    <td width='41' height='25' align=center>No</td>
    <td width='27'>&nbsp;</td>
    <td width='250'>Komponen Penilaian</td>
    <td width='100' align=center >Bobot (%)</td>
    <td width='100' align=center>Nilai</td>
    <td width='100' align=center>Bobot x Nilai</td>
  </tr>
  
  <tr>
    <td width='41' height='15' align=center>1.</td>
    <td colspan='2'>Format Penulisan</td>
    <td width='100'>&nbsp;</td>
    <td width='100'>&nbsp;</td>
    <td width='100'>&nbsp;</td>
  </tr>
  
  <tr>
    <td height='15'>&nbsp;</td>
    <td align=center>a.</td>
    <td >Penggunaan Bahasa Indonesia yang benar</td>
    <td align=center>10%</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  
   <tr>
    <td height='15'>&nbsp;</td>
    <td align=center >b.</td>
    <td >Sesuai dengan Format Penulisan</td>
    <td align=center>10%</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  
   <tr>
     <td height='15'>&nbsp;</td>
     <td align=center>c.</td>
     <td >Daftar Pustaka</td>
     <td align=center>10%</td>
     <td >&nbsp;</td>
     <td >&nbsp;</td>
   </tr>
   <tr>
    <td height='15' align=center>2.</td>
    <td >&nbsp;</td>
    <td >Sikap (Attitude)</td>
    <td align=center>30%</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
   <tr>
     <td height='15' align=center>3.</td>
     <td >&nbsp;</td>
     <td >Penguasaan Materi</td>
     <td align=center>40%</td>
     <td >&nbsp;</td>
     <td >&nbsp;</td>
   </tr>
   <tr>
     <td height='15' colspan='5' align=right> Total &nbsp;</td>
     <td >&nbsp;</td>
   </tr>
</table>

<br>";
}
$content .="<br>
<br>
<table border=0 width='700' align='center' cellpadding='0' cellspacing='0' >
<tr>
<td width='423' align='center'>&nbsp;</td>
<td width='367' align='left'>Pekanbaru, $tglnow</td>
</tr>

<tr>
  <td align='left'></td>
  <td align='left' >Penguji I / II / III</td>
</tr>

<tr>
  <td align='left'></td>
  <td align='left' >&nbsp;</td>
</tr>
<tr>
  <td align='left'></td>
  <td align='left' >&nbsp;</td>
</tr>

<tr>
  <td align='left'></td>
  <td align='left' >&nbsp;</td>
</tr>

<tr>
<td align='left'></td>
<td align='left'>------------------------------------------</td>
</tr>
</table>

<font style='font-size:8px'>Login by: $_SESSION[_Login]</font>";
try {
  ob_start();
  $html2pdf = new Html2Pdf('P','A4','fr', true, 'UTF-8', array(15, 15, 15, 15), false); 
  $html2pdf->writeHTML($content);
  $html2pdf->output();
} catch (Html2PdfException $e) {
  $html2pdf->clean();

  $formatter = new ExceptionFormatter($e);
  echo $formatter->getHtmlMessage();
}

}	
?>	