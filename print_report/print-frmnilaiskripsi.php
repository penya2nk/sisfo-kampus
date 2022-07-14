<?php 
session_start();
error_reporting(0);
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
$dt        = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM vw_jadwal_skripsi_ujian where JadwalID='".strfilter($_GET[JadwalID])."'"));
$ProdiID   = $dt[ProdiID];

if ($ProdiID=='SI'){ $prod="Sistem Informasi"; $kaprodi="Herianto, M.Kom";}else{ $prod="Teknik Informatika"; $kaprodi="Eka Sabna, M.Pd, M.Kom";}

//$tgllahir  = tgl_indo($dt[TanggalLahir]);
//$program   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProgramID,Nama FROM program where ProgramID='$ProgramID'"));
//$prodi     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='$_GET[prodi]'"));

$content .= "<table width='700' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td width='8%' rowspan='4'><img width='80' width='80' src='logo_uti.png'></td>       
<td width='84%' align='center' ><strong><font style='font-size:16px'>SEKOLAH TINGGI MANAJEMEN INFORMATIKA DAN KOMPUTER</font></strong></td>           
<td width='8%' rowspan='4'>&nbsp;</td> 
</tr>

<tr>
<td align='center' ><strong><font style='font-size:25px'>(STMIK) HANG TUAH PEKANBARU</font></strong></td>
</tr>

<tr>
<td align='center' >Jl. ZA. Pagar Alam No.9 -11, Labuhan Ratu, Kec. Kedaton, Kota Bandar Lampung, Lampung 35132</td>
</tr>

<tr>
<td align='center' >Email: stmikhtp@yahoo.co.id, Website: http://www.stmikhtp.ac.id</td>
</tr>
<tr>

<td colspan='3'><hr></td>            
</tr>
</table><br>

<table width='700' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td align='center'><b>FORMULIR PENILAIAN  SIDANG SKRIPSI DAN COMPREHENSIVE</b></td>
</tr>
<tr>
<td align='center'>Program Studi: $prod</td>
</tr>
</table>
<br>
<br>";

$sql= mysqli_query($koneksi, "SELECT * FROM vw_jadwal_skripsi_ujian where JadwalID='".strfilter($_GET[JadwalID])."'");
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
    <td width='250'>Kriteria Penilaian</td>
    <td width='100' align=center >Bobot (%)</td>
    <td width='100' align=center>Nilai</td>
    <td width='100' align=center>Bobot x Nilai</td>
  </tr>
  
  <tr>
    <td width='41' height='20' align=center>1.</td>
    <td>Konsultasi/Bimbingan Skripsi</td>
    <td width='100' align=center>10%</td>
    <td width='100'>&nbsp;</td>
    <td width='100'>&nbsp;</td>
  </tr>
  
  <tr>
    <td height='20' align=center>2.</td>
    <td >Presentasi + Demo Program</td>
    <td align=center>15%</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  
   <tr>
    <td height='20' align=center>3.</td>
    <td >Penulisan</td>
    <td align=center>20%</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  
   <tr>
     <td height='20' align=center>4.</td>
     <td >Penguasaan Materi</td>
     <td align=center>30%</td>
     <td >&nbsp;</td>
     <td >&nbsp;</td>
   </tr>
   <tr>
    <td height='20' align=center>5.</td>
    <td >Penguasaan Program</td>
    <td align=center>15%</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
   <tr>
    <td height='20' align=center>6.</td>
    <td >Attitude/Sikap Saat Sidang</td>
    <td align=center>10%</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
   <tr>
     <td height='20' colspan='2' align=right> Total &nbsp;</td>
     <td align=center>100%</td>
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