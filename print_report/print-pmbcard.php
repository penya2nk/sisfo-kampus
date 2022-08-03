<?php 
error_reporting(0);
session_start();

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
$dta 		= mysqli_fetch_array(mysqli_query($koneksi, "select * from pmb where PMBID='".strfilter($_GET['PMBID'])."'"));	
$Namax 		= strtolower($dta['Nama']);
$Nama		= ucwords($Namax);

$tglujian	= mysqli_fetch_array(mysqli_query($koneksi, "select * from pmbperiod where PMBPeriodID='$dta[PMBPeriodID]'"));
$tanggal 	= $tglujian['UjianMulai'];
$day 		= date('D', strtotime($tanggal));
$dayList 	= array(
	'Sun' => 'Minggu',
	'Mon' => 'Senin',
	'Tue' => 'Selasa',
	'Wed' => 'Rabu',
	'Thu' => 'Kamis',
	'Fri' => 'Jumat',
	'Sat' => 'Sabtu'
);
$prodi 		= mysqli_fetch_array(mysqli_query($koneksi, "select * from prodi where ProdiID='$dta[ProdiID]'"));
$program 	= mysqli_fetch_array(mysqli_query($koneksi, "select * from program where ProgramID='$dta[ProgramID]'"));
$petugas 	= mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from karyawan 
											 where Login='$_SESSION[_Login]'"));

$content .= "
<head>
<style>
.garis_tepi0 {
     border:  2px dotted #e62899; 
	 width:700px;
}
.garis_tepi {
     border:  2px dashed #e62899;
	 width:750px;
}
</style>
</head>
<center>
<body>
<div class='garis_tepi0'>
<div class='garis_tepi'>
<table border=0  align='center'>
    
	
<tr class='brs_isi'>
<td valign='top'>
<br>
<table  border='0' align='center' cellpadding='0' cellspacing='0' '>
<tr  align='center'>
<td  rowspan='3' align='center' ></td>
<td align='center' style=text-align:center;font-size:16px;font-weight:reguler;>YAYASAN TEKNOKRAT INDONESIA</td>
<td align='left'>&nbsp;</td>
</tr>
<tr  align='center'>
<td style=text-align:center;font-size:16px;font-weight:reguler;>SELEKSI PENERIMAAN MAHASISWA BARU TAHUN 2022/2023</td>
<td align='left' width=60>&nbsp;</td>
</tr>
<tr  align='center'>
<td height='18' style=text-align:center;font-size:25px;font-weight:bold;>UNIVERSITAS TEKNOKRAT INDONESIA</td>
<td height='18' >&nbsp;</td>
</tr>
<tr  align='center'>
<td >&nbsp;</td>
<td  style=text-align:center;font-size:10px;font-style:italic;>Jl. ZA. Pagar Alam No.9 -11, Labuhan Ratu, Kec. Kedaton, Kota Bandar Lampung, Lampung 35132 </td>
</tr>
<tr  align='center'>
<td colspan='3' align='left'>
	<hr style=width:650px; ; >
</td>
</tr>
<tr  align='center'>
<td colspan='3' align='left'>&nbsp;</td>
</tr>
<tr>
<td colspan='3' width=600 height=20 style=text-align:center;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;><b>.::  TANDA PESERTA UJIAN ::.</b>  </td>
</tr>
</table>
<br>
<table width='20' border='0' align='center' cellpadding='0' cellspacing='0' class='keliling'>
<tr class='batas2' align='left'>
<td width='120' rowspan='8' align='center'><img class='img-thumbnail' style='width:100px' src='20190316143146-blank.png'></td>
<td width=180>&nbsp;No Ujian</td>
<td>:</td>
<td width=320>&nbsp;$dta[PMBRef]</td>
</tr>
<tr class='batas2' align='left'>
<td>&nbsp;Nama</td>
<td>:</td>
<td>&nbsp;$Nama</td>
</tr>

<tr class='batas2' align='left'>
<td >&nbsp;Tempat dan Tanggal Lahir</td>
<td >:</td>
<td >&nbsp;$dta[TempatLahir], ".tgl_indo($dta['TanggalLahir'])."</td>
</tr>

<tr class='batas2' align='left'>
<td>&nbsp;Program Studi</td>
<td>:</td>
<td>&nbsp;$prodi[Nama]</td>
</tr>

<tr class='batas2' align='left'>
<td>&nbsp;Jalur</td>
<td>:</td>
<td>&nbsp;$program[Nama]</td>
</tr>


<tr class='batas2' align='left'>
<td >&nbsp;Hari / Tanggal Ujian</td>
<td >:</td>
<td >&nbsp;$dayList[$day], ".tgl_indo($tglujian['UjianMulai'])."</td>
</tr>
<tr class='batas2' align='left'>
<td >&nbsp;Waktu</td>
<td >:</td>
<td >&nbsp;08:00 s/d 10:00 WIB</td>
</tr>
<tr class='batas2' align='left'>
<td >&nbsp;Lokasi Ujian</td>
<td >:</td>
<td >&nbsp;Aula Universitas Teknokrat Indonesia</td>
</tr>
</table>        
<br>



<table  border='0' align='center' cellpadding='0' cellspacing='0' '>
<tr  align='center'>
<td width='350' center='left'></td>
<td width='300' align='left'>Pekanbaru, ".tgl_indo(date('Y-m-d'))."</td>
</tr>
<tr  align='center'>
<td align='left'>&nbsp;</td>
<td align='left'>Petugas Pendaftaran</td>
</tr>

<tr  align='center'>
<td align='left'>&nbsp;</td>
<td align='left'>&nbsp;</td>
</tr>
<tr  align='center'>
<td align='left'>&nbsp;</td>
<td align='left'>&nbsp;</td>
</tr>
<tr  align='center'>
<td align='center'>&nbsp;</td>
<td align='left'>$petugas[Nama]</td>
</tr>

<tr  align='center'>
<td align='left'>&nbsp;</td>
<td align='left'>&nbsp;</td>
</tr>

</table>

<table  border='0'  align=center' cellpadding='0' cellspacing='0' '>
<tr style=background-color:#e62899; >
<td  width=320 style=text-align:left;color:#FFFFFF;font-size:8px;font-style:italic;height:20;>
&nbsp; - Cetaklah kartu ujian menggunakan printer berwarna<br>
&nbsp; - Ketika ujian anda perlu membawa kartu ujian serta perlengkapan tulis
</td>
<td width=300 style=text-align:right;color:#FFFFFF;font-size:8px;font-style:italic;height:20;>
<font style='font-size:8px'>Login by: $_SESSION[_Login] ".tgl_indo(date('Y-m-d'))." ".date('H:i:s') . " WIB - Univ Tekno Indo Support System</font>
&nbsp;
</td>
</tr>
</table><br>
</td>
</tr>
</table>
</div>
</div>
</body>
</center>
";
try {
	ob_start();
	$html2pdf = new Html2Pdf('P','Legal','fr', true, 'UTF-8', array(5, 10, 5, 10), false); 
	$html2pdf->writeHTML($content);
	$html2pdf->output();
  } catch (Html2PdfException $e) {
	$html2pdf->clean();
  
	$formatter = new ExceptionFormatter($e);
	echo $formatter->getHtmlMessage();
  }
  
  }	
  ?>	
