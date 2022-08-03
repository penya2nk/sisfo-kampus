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
$dta = mysqli_fetch_array(mysqli_query("select * from mhsw where MhswID='$_SESSION[_Login]'"));	
$Namax 		= strtolower($dta[Nama]);
$Nama		= ucwords($Namax);

$tglujian	= mysqli_fetch_array(mysqli_query("select * from pmbperiod where PMBPeriodID='$dta[PMBPeriodID]'"));
$tanggal 	= $tglujian[UjianMulai];

$prodi 		= mysqli_fetch_array(mysqli_query("select * from prodi where ProdiID='$dta[ProdiID]'"));
$program 	= mysqli_fetch_array(mysqli_query("select * from program where ProgramID='$dta[ProgramID]'"));
$kelamin 	= mysqli_fetch_array(mysqli_query("select * from kelamin where kelamin='$dta[Kelamin]'"));
$yudisium 	= mysqli_fetch_array(mysqli_query("select * from t_yudisium where MhswID='$_SESSION[_Login]'"));

if ($yudisium[Status]<>'Lunas'){$berkas="Belum Lengkap";}else{$berkas="Lengkap";}

$dts = mysqli_fetch_array(mysqli_query("select * from jadwal_skripsi where MhswID='$_SESSION[_Login]' order by JadwalID DESC limit 1"));	
$Judulx 		= strtolower($dts[Judul]);
$Judul		= ucwords($Judulx);
//pembimbing 1 ----------------------------------------------------------------------------------------------------
$dtp = mysqli_fetch_array(mysqli_query("select Login,Nama,Gelar from dosen where Login='$dts[PembimbingSkripsi1]'"));	
$Namaa 		= strtolower($dtp[Nama]);
$Pembimbing1	= ucwords($Namaa);

//pembimbing 2 ----------------------------------------------------------------------------------------------------
$dtp2 = mysqli_fetch_array(mysqli_query("select Login,Nama,Gelar from dosen where Login='$dts[PembimbingSkripsi2]'"));	
$Namab 		= strtolower($dtp2[Nama]);
$Pembimbing2= ucwords($Namab);
$petugas 	= mysqli_fetch_array(mysqli_query("select Login,Nama from karyawan 
											 where Login='$_SESSION[_Login]'"));
if (trim($dta[Foto])=='-'){
  $foto = '../pto_stud/no-image.jpg';
}else{
  $foto	= '../pto_stud/'.$dta[Foto];
}
$content .= "
<head>
<style>

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
<td  rowspan='3' align='center' ><img width='80' src='logo_uti.png' ></td>
<td align='center' style=text-align:center;font-size:16px;font-weight:reguler;>YAYASAN PENDIDIKAN TEKNOKRAT</td>
<td align='left'>&nbsp;</td>
</tr>
<tr  align='center'>
<td style=text-align:center;font-size:16px;font-weight:reguler;>PANITIA WISUDA UNIVERSITAS TEKNOKRAT INDONESIA</td>
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
<td colspan='3' width=650 height=20 style=text-align:center;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;><b>::  BIODATA WISUDAWAN/TI  ::</b></td>
</tr>
</table>
<br>
<table width='20' border='0' align='center' cellpadding='0' cellspacing='0' class='keliling'>
<tr class='batas2' align='left'>
<td width='120' rowspan='20' align='center'><img class='img-thumbnail' style='width:100px' src='$foto'></td>
<td width=180>&nbsp;NIM</td>
<td>:</td>
<td width=320>&nbsp;$dta[MhswID]</td>
</tr>

<tr class='batas2' align='left'>
<td>&nbsp;Nama</td>
<td>:</td>
<td>&nbsp;$Nama</td>
</tr>

<tr class='batas2' align='left'>
<td >&nbsp;Tempat dan Tanggal Lahir</td>
<td >:</td>
<td >&nbsp;$dta[TempatLahir], ".tgl_indo($dta[TanggalLahir])."</td>
</tr>

<tr class='batas2' align='left'>
<td >&nbsp;Jenis Kelamin</td>
<td >:</td>
<td >&nbsp;$kelamin[Nama]</td>
</tr>

<tr class='batas2' align='left'>
<td>&nbsp;Program Studi</td>
<td>:</td>
<td>&nbsp;$prodi[Nama]</td>
</tr>

<tr class='batas2' align='left'>
<td>&nbsp;Perguruan Tinggi</td>
<td>:</td>
<td>&nbsp;Universitas Teknokrat Indonesia</td>
</tr>

<tr class='batas2' align='left'>
<td>&nbsp;Alamat</td>
<td>:</td>
<td>&nbsp;$dta[Alamat]</td>
</tr>

<tr class='batas2' align='left'>
<td>&nbsp;Handphone</td>
<td>:</td>
<td>&nbsp;$dta[Handphone]</td>
</tr>

<tr class='batas2' align='left'>
<td>&nbsp;Email</td>
<td>:</td>
<td>&nbsp;$dta[Email]</td>
</tr>



<tr class='batas2' align='left'>
<td >&nbsp;Tahun Masuk</td>
<td >:</td>
<td >&nbsp;$dta[TahunID]</td>
</tr>
<tr class='batas2' align='left'>
<td >&nbsp;Tahun Lulus</td>
<td >:</td>
<td >&nbsp;".tgl_indo($yudisium[TglYudisium])."</td>
</tr>

<tr class='batas2' align='left'>
<td >&nbsp;IPK</td>
<td >:</td>
<td >&nbsp;$yudisium[IPK]</td>
</tr>


<tr class='batas2' align='left'>
<td >&nbsp;Nama Ayah</td>
<td >:</td>
<td >&nbsp;$dta[NamaAyah]</td>
</tr>

<tr class='batas2' align='left'>
<td >&nbsp;Nama Ibu</td>
<td >:</td>
<td >&nbsp;$dta[NamaIbu]</td>
</tr>

</table>        
<br>

<table  border='0'  align=center' cellpadding='0' cellspacing='0' '>
<tr style=background-color:#e62899; >
<td width=720 style=text-align:center;color:#FFFFFF;font-size:12px;font-style:italic;height:20;>
Judul Skripsi : $Judul <br>
Pembimbing 1 : $Pembimbing1, $dtp[Gelar] <br>
Pembimbing 2 : $Pembimbing2, $dtp2[Gelar] <br>
Status Keuangan : $yudisium[Status] <br>
Status Berkas : $berkas <br>

</td>
</tr>
</table>


<br>

<table  border='0' align=center' cellpadding='0' cellspacing='0' '>
<tr>
<td width='206'  align='center'></td>
<td width='206' align='center'></td>
<td width='206' align='center'>Pekanbaru, ".tgl_indo(date('Y-m-d'))."</td>
</tr>
<tr  align='center'>
  <td align='center'>Sekretaris Wisuda</td>
<td align='center'></td>
<td align='center'>Bagian Keuangan</td>
</tr>
<tr  align='center'>
  <td align='center'>&nbsp;</td>
<td align='center'></td>
<td align='center'>&nbsp;</td>
</tr>

<tr  align='center'>
  <td align='left'>&nbsp;</td>
<td align='center'>&nbsp;</td>
<td align='center'>&nbsp;</td>
</tr>
<tr  align='center'>
  <td align='center'>&nbsp;</td>
  <td align='center'>&nbsp;</td>
  <td align='left'>&nbsp;</td>
</tr>


<tr  align='center'>
  <td align='center'>Uci Rahmalisa, S.Kom, M.TI</td>
<td align='center'></td>
<td align='center'>Zupri Henra Hartomi, S.Kom</td>
</tr>
<tr  align='center'>
  <td align='center'>&nbsp;</td>
  <td align='center'>&nbsp;</td>
  <td align='left'>&nbsp;</td>
</tr>

</table>

<table  border='0'  align=center' cellpadding='0' cellspacing='0' '>
<tr style=background-color:#e62899; >
<td  width=400 style=text-align:left;color:#FFFFFF;font-size:8px;font-style:italic;height:20;>
- Mohon dicetak sebanyak 3(tiga) lembar <br>
- Pastikan semua data sudah lengkap sebelum dicetak 
</td>
<td width=320 style=text-align:right;color:#FFFFFF;font-size:8px;font-style:italic;height:20;>
<font style='font-size:8px'>Login by: $_SESSION[_Login] ".tgl_indo(date('Y-m-d'))." ".date('H:i:s') . " WIB - Univ Tekno Indo Support System</font>
&nbsp;
</td>
</tr>
</table><br>
</td>
</tr>
</table>
</div>
</div><br>



</body>
</center>
";
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