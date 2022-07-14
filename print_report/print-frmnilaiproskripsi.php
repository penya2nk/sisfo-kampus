<?php 
session_start();
// error_reporting(0);
include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
require ("../punksi/html2pdf/html2pdf.class.php");

$filename="namafile.pdf";
$tglnow = tgl_indo(date('Y-m-d'));


if (empty($_SESSION['_Login']) && empty($_SESSION['_LevelID'])){
	header("Location: ../login.php");
}
else{

include "headerx-rpt.php"; $content = ob_get_clean();
$dt        = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM vw_jadwal_skripsi_ujian where JadwalID='".strfilter($_GET['JadwalID'])."'"));
$ProdiID   = $dt['ProdiID'];

if ($ProdiID=='SI'){ $prod="Sistem Informasi"; $kaprodi="Herianto, M.Kom";}else{ $prod="Teknik Informatika"; $kaprodi="Eka Sabna, M.Pd, M.Kom";}


$content .= "

<table width='700' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td align='center'><b>FORMULIR PENILAIAN PROPOSAL SKRIPSI</b></td>
</tr>
<tr>
<td align='center'></td>
</tr>
</table>
<br>
<br>";

$sql= mysqli_query($koneksi, "SELECT * FROM vw_jadwal_skripsi_ujian where JadwalID='".strfilter($_GET['JadwalID'])."'");
while($dt=mysqli_fetch_array($sql)){

$content .= " 
<table width='635' border='0' cellpadding='0' cellspacing='0' align='center'>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width='4'>&nbsp;</td>
  </tr>
  <tr>
    <td width='45'>&nbsp;</td>
    
    <td width='189'>Nama</td>
    <td width='13'>:</td>
    <td width='449'> $dt[Nama]</td>
  </tr>
  <tr>
    <td width='45'>&nbsp;</td>
   
    <td width='189'>NIM</td>
    <td width='13'>:</td>
    <td width='449'>$dt[MhswID]</td>
  </tr>
  <tr>
    <td width='45'>&nbsp;</td>
   
    <td width='189'>Program Studi</td>
    <td width='13'>:</td>
    <td width='449'> $prod</td>
  </tr>
</table>

<br>
<table width='0' border='0.1' cellpadding='0' cellspacing='0' align='center'>
  <tr style='background-color:#E6E6E6'>
    <td width='41' height='25' align=center>No</td>
    <td width='250'>&nbsp;Kriteria Penilaian</td>
    <td width='100' align=center >Bobot (%)</td>
    <td width='100' align=center>Nilai</td>
    <td width='100' align=center>Bobot x Nilai</td>
  </tr>
  
  <tr>
    <td width='41' height='20' align=center>1.</td>
    <td>&nbsp;Permasalahan</td>
    <td width='100' align=center>20%</td>
    <td width='100'>&nbsp;</td>
    <td width='100'>&nbsp;</td>
  </tr>
  
  <tr>
    <td height='20' align=center>2.</td>
    <td >&nbsp;Teori yang mendukung</td>
    <td align=center>20%</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  
   <tr>
    <td height='20' align=center>3.</td>
    <td >&nbsp;Metode Penelitian</td>
    <td align=center>20%</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  
   <tr>
     <td height='20' align=center>4.</td>
     <td >&nbsp;Daftar Pustaka</td>
     <td align=center>20%</td>
     <td >&nbsp;</td>
     <td >&nbsp;</td>
   </tr>
   <tr>
    <td height='20' align=center>5.</td>
    <td >&nbsp;Penyajian (Sikap)</td>
    <td align=center>20%</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
   <tr>
     <td height='20' colspan='4' align=right> Total &nbsp;</td>
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
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<table border=0>
<tr>
<td width='30'></td>
<td width='300'><font style='font-size:8px'>Login by: $_SESSION[_Login] ".tgl_indo(date('Y-m-d'))." ".date('H:i:s') . " WIB - Univ Tekno Indo Support System</font></td>
</tr>
</table>";


try
	{
		$html2pdf = new HTML2PDF('P','Letter','en', false, 'ISO-8859-15',array(25, 10, 25, 10)); //setting ukuran kertas dan margin pada dokumen anda
		// $html2pdf->setModeDebug();
		$html2pdf->setDefaultFont('Arial');
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output($filename);
	}
	catch(HTML2PDF_exception $e) { echo $e; }
	
}
?>	