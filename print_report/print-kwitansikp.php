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
$dta 		= mysqli_fetch_array(mysqli_query($koneksi, "select * from vw_jadwal_skripsi_ujian where JadwalID='".strfilter($_GET[JadwalID])."'"));	
$Namax 		= strtolower($dta[Nama]);
$Nama		= ucwords($Namax);

//pembimbing 1 ----------------------------------------------------------------------------------------------------
$dtp 			= mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$dta[PembimbingSkripsi1]'"));	
$Namaa 			= strtolower($dtp[Nama]);
$Pembimbing1	= ucwords($Namaa);

//penguji 1 ----------------------------------------------------------------------------------------------------
$dtu1 			= mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$dta[PengujiSkripsi1]'"));	
$Namac 			= strtolower($dtu1[Nama]);
$Penguji1		= ucwords($Namac);

//penguji 2 ----------------------------------------------------------------------------------------------------
$dtu2 			= mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$dta[PengujiSkripsi2]'"));	
$Namad 			= strtolower($dtu2[Nama]);
$Penguji2		= ucwords($Namad);

//penguji 3 ----------------------------------------------------------------------------------------------------
$dtu3 			= mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$dta[PengujiSkripsi3]'"));	
$Namae 			= strtolower($dtu3[Nama]);
$Penguji3		= ucwords($Namae);

$prodi 			= mysqli_fetch_array(mysqli_query($koneksi, "select * from prodi where ProdiID='$dta[ProdiID]'"));
$program 		= mysqli_fetch_array(mysqli_query($koneksi, "select * from program where ProgramID='$dta[ProgramID]'"));
$petugas 		= mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from karyawan 
											 where Login='$_SESSION[_Login]'"));
$n1 =290000;
$NominalSkripsi =terbilang($n1);

$o1 =120000;
$NominalPenguji =terbilang($o1);
$header ="<table border=0  align='center'>
<tr class='brs_isi'>
<td valign='top'>
<br>
<table  border='0' align='center' cellpadding='0' cellspacing='0' '>
<tr  align='center'>
<td width='80'  rowspan='3' align='center' ><img width='60' src='logo_uti.png' ></td>
<td width='460' align='center' style=text-align:center;font-size:16px;font-weight:reguler;>YAYASAN PENDIDIKAN TEKNOKRAT</td>
<td width='80' align='left'>&nbsp;</td>
</tr>

<tr  align='center'>
<td height='18' style=text-align:center;font-size:25px;font-weight:bold;>UNIVERSITAS TEKNOKRAT INDONESIA</td>
<td height='18' >&nbsp;</td>
</tr>
<tr  align='center'>

<td  style=text-align:center;font-size:10px;font-style:italic;>Jl. ZA. Pagar Alam No.9 -11, Labuhan Ratu, Kec. Kedaton, Kota Bandar Lampung, Lampung 35132 </td>
</tr>
<tr  align='center'>
<td colspan='3' align='left'>
	<hr style=width:650px;>
</td>
</tr>
<tr  align='center'>
<td colspan='3' align='left'>&nbsp;</td>
</tr>
<tr>
<td colspan='3' height=20 style=text-align:center;font-size:18px;font-weight:reguler;background-color:#FF8533;>  <b style=color:#FFFFFF>:: KWITANSI ::</b>  </td>
</tr>
</table>";





//pembimbing 1 ================================================================================================================
$content .= "
<head>
<style>
.garis_tepi0 {
     border:  2px dotted #FF8533;
	 width:700px;
	 margin:auto;
}
.garis_tepi {
     border:  2px dashed #FF8533;
	 width:750px;
	 margin:auto;
}
</style>
</head>
<center>
<body>
<div class='garis_tepi0'>
<div class='garis_tepi'>

$header

<br>
<table width='20' border='0' align='center' cellpadding='0' cellspacing='0' class='keliling'>
<tr class='batas2' align='left'>

<td width=180>&nbsp;Sudah terima dari</td>
<td>:</td>
<td width=420>&nbsp;Ketua Universitas Teknokrat Indonesia</td>
</tr>
<tr class='batas2' align='left'>
<td>&nbsp;Uang sejumlah</td>
<td>:</td>
<td>&nbsp;Rp. 290.000</td>
</tr>

<tr class='batas2' align='left' >
<td style=font-style:italic>&nbsp;Terbilang:</td>
<td >:</td>
<td style=font-style:italic>&nbsp;<textarea cols=49 rows=2 >$NominalSkripsi rupiah</textarea></td>
</tr>

<tr class='batas2' align='left'>
<td >&nbsp;Untuk pembayaran</td>
<td >:</td>
<td >&nbsp;Honor Pembimbing Kerja Praktek Tahun $dta[TahunID]</td>
</tr>

<tr class='batas2' align='left'>
<td >&nbsp;Mahasiswa</td>
<td >:</td>
<td >&nbsp;$dta[MhswID] - $Nama</td>
</tr>
<tr class='batas2' align='left'>
<td >&nbsp;Program Studi</td>
<td >:</td>
<td >&nbsp;($program[Nama]) $prodi[Nama] </td>
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
  <td align='center'>Bendaharawan</td>
<td align='center'>Setuju Dibayar</td>
<td align='center'>Yang Menerima</td>
</tr>
<tr  align='center'>
  <td align='center'>&nbsp;</td>
<td align='center'>Universitas Teknokrat Indonesia</td>
<td align='center'>&nbsp;</td>
</tr>

<tr  align='center'>
  <td align='left'>&nbsp;</td>
<td align='center'>Ketua</td>
<td align='center'>&nbsp;</td>
</tr>
<tr  align='center'>
  <td align='center'>&nbsp;</td>
  <td align='center'>&nbsp;</td>
  <td align='left'>&nbsp;</td>
</tr>
<tr  align='center'>
  <td align='center'>&nbsp;</td>
<td align='center'>&nbsp;</td>
<td align='center'>&nbsp;</td>
</tr>

<tr  align='center'>
  <td align='center'>Zupri Henra Hartomi, S.Kom</td>
<td align='center'>Hendry Fonda, M.Kom</td>
<td align='center'>$Pembimbing1, $dtp[Gelar]</td>
</tr>
<tr  align='center'>
  <td align='center'>&nbsp;</td>
<td align='center'>&nbsp;</td>
<td align='center'></td>
</tr>
</table>

<table  border='0'  align=center' cellpadding='0' cellspacing='0' '>
<tr style=background-color:#FFB482; >
<td  width=300 style=text-align:left;color:#FFFFFF;font-size:8px;font-style:italic;height:20;>
- Mohon hitung kembali nominal uang yang diterima <br>
- Komplain tidak diterima setelah meninggalkan program studi 
</td>
<td width=320 style=text-align:right;color:#FFFFFF;font-size:8px;font-style:italic;height:20;>
<font style='font-size:8px'>Login by: $_SESSION[_Login] ".tgl_indo(date('Y-m-d'))." ".date('H:i:s') . " WIB - Univ Tekno Indo Support System</font>
&nbsp;
</td>
</tr>
</table>

<br>
</td>
</tr>
</table>
</div>
</div>
<br>";




//penguji 1================================================================================================================
$content .="<div class='garis_tepi0'>
<div class='garis_tepi'>
$header

<br>
<table width='20' border='0' align='center' cellpadding='0' cellspacing='0' class='keliling'>
<tr class='batas2' align='left'>

<td width=180>&nbsp;Sudah terima dari</td>
<td>:</td>
<td width=420>&nbsp;Ketua Universitas Teknokrat Indonesia</td>
</tr>
<tr class='batas2' align='left'>
<td>&nbsp;Uang sejumlah</td>
<td>:</td>
<td>&nbsp;Rp. 120.000</td>
</tr>



<tr class='batas2' align='left' >
<td style=font-style:italic>&nbsp;Terbilang:</td>
<td >:</td>
<td style=font-style:italic>&nbsp;<textarea cols=49 rows=2 >$NominalPenguji rupiah</textarea></td>
</tr>

<tr class='batas2' align='left'>
<td >&nbsp;Untuk pembayaran</td>
<td >:</td>
<td >&nbsp;Honor Penguji Seminar Hasil Kerja Praktek Tahun $dta[TahunID]</td>
</tr>

<tr class='batas2' align='left'>
<td >&nbsp;Mahasiswa</td>
<td >:</td>
<td >&nbsp;$dta[MhswID] - $Nama</td>
</tr>
<tr class='batas2' align='left'>
<td >&nbsp;Program Studi</td>
<td >:</td>
<td >&nbsp;($program[Nama]) $prodi[Nama] </td>
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
  <td align='center'>Bendaharawan</td>
<td align='center'>Setuju Dibayar</td>
<td align='center'>Yang Menerima</td>
</tr>
<tr  align='center'>
  <td align='center'>&nbsp;</td>
<td align='center'>Universitas Teknokrat Indonesia</td>
<td align='center'>&nbsp;</td>
</tr>

<tr  align='center'>
  <td align='left'>&nbsp;</td>
<td align='center'>Ketua</td>
<td align='center'>&nbsp;</td>
</tr>
<tr  align='center'>
  <td align='center'>&nbsp;</td>
  <td align='center'>&nbsp;</td>
  <td align='left'>&nbsp;</td>
</tr>
<tr  align='center'>
  <td align='center'>&nbsp;</td>
<td align='center'>&nbsp;</td>
<td align='center'>&nbsp;</td>
</tr>

<tr  align='center'>
  <td align='center'>Zupri Henra Hartomi, S.Kom</td>
<td align='center'>Hendry Fonda, M.Kom</td>
<td align='center'>$Penguji1, $dtu1[Gelar]</td>
</tr>
<tr  align='center'>
  <td align='center'>&nbsp;</td>
<td align='center'>&nbsp;</td>
<td align='center'></td>
</tr>

</table>

<table  border='0'  align=center' cellpadding='0' cellspacing='0' '>
<tr style=background-color:#FFB482; >
<td  width=300 style=text-align:left;color:#FFFFFF;font-size:8px;font-style:italic;height:20;>
- Mohon hitung kembali nominal uang yang diterima <br>
- Komplain tidak diterima setelah meninggalkan program studi 
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
</div><br>";

//Penguji 2 =======================================================================================================
$content .="<div class='garis_tepi0'>
<div class='garis_tepi'>
$header
<br>
<table width='20' border='0' align='center' cellpadding='0' cellspacing='0' class='keliling'>
<tr class='batas2' align='left'>

<td width=180>&nbsp;Sudah terima dari</td>
<td>:</td>
<td width=420>&nbsp;Ketua Universitas Teknokrat Indonesia</td>
</tr>
<tr class='batas2' align='left'>
<td>&nbsp;Uang sejumlah</td>
<td>:</td>
<td>&nbsp;Rp. 120.000</td>
</tr>

<tr class='batas2' align='left' >
<td style=font-style:italic>&nbsp;Terbilang:</td>
<td >:</td>
<td style=font-style:italic>&nbsp;<textarea cols=49 rows=2 >$NominalPenguji rupiah</textarea></td>
</tr>

<tr class='batas2' align='left'>
<td >&nbsp;Untuk pembayaran</td>
<td >:</td>
<td >&nbsp;Honor Penguji Seminar Hasil Kerja Praktek Tahun $dta[TahunID]</td>
</tr>

<tr class='batas2' align='left'>
<td >&nbsp;Mahasiswa</td>
<td >:</td>
<td >&nbsp;$dta[MhswID] - $Nama</td>
</tr>
<tr class='batas2' align='left'>
<td >&nbsp;Program Studi</td>
<td >:</td>
<td >&nbsp;($program[Nama]) $prodi[Nama] </td>
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
  <td align='center'>Bendaharawan</td>
<td align='center'>Setuju Dibayar</td>
<td align='center'>Yang Menerima</td>
</tr>
<tr  align='center'>
  <td align='center'>&nbsp;</td>
<td align='center'>Universitas Teknokrat Indonesia</td>
<td align='center'>&nbsp;</td>
</tr>

<tr  align='center'>
  <td align='left'>&nbsp;</td>
<td align='center'>Ketua</td>
<td align='center'>&nbsp;</td>
</tr>
<tr  align='center'>
  <td align='center'>&nbsp;</td>
  <td align='center'>&nbsp;</td>
  <td align='center'>&nbsp;</td>
</tr>
<tr  align='center'>
  <td align='center'>&nbsp;</td>
<td align='center'>&nbsp;</td>
<td align='center'>&nbsp;</td>
</tr>

<tr  align='center'>
  <td align='center'>Zupri Henra Hartomi, S.Kom</td>
<td align='center'>Hendry Fonda, M.Kom</td>
<td align='center'>$Penguji2, $dtu2[Gelar]</td>
</tr>
<tr  align='center'>
  <td align='center'>&nbsp;</td>
<td align='center'>&nbsp;</td>
<td align='center'></td>
</tr>

</table>

<table  border='0'  align=center' cellpadding='0' cellspacing='0' '>
<tr style=background-color:#FFB482; >
<td  width=300 style=text-align:left;color:#FFFFFF;font-size:8px;font-style:italic;height:20;>
- Mohon hitung kembali nominal uang yang diterima <br>
- Komplain tidak diterima setelah meninggalkan program studi 
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
</div>";


//Penguji 3 =======================================================================================================
$content .="
<div class='garis_tepi0'>
<div class='garis_tepi'>
$header


<br>
<table width='20' border='0' align='center' cellpadding='0' cellspacing='0' class='keliling'>
<tr class='batas2' align='left'>

<td width=180>&nbsp;Sudah terima dari</td>
<td>:</td>
<td width=420>&nbsp;Ketua Universitas Teknokrat Indonesia</td>
</tr>
<tr class='batas2' align='left'>
<td>&nbsp;Uang sejumlah</td>
<td>:</td>
<td>&nbsp;Rp. 120.000</td>
</tr>



<tr class='batas2' align='left' >
<td style=font-style:italic>&nbsp;Terbilang:</td>
<td >:</td>
<td style=font-style:italic>&nbsp;<textarea cols=49 rows=2 >$NominalPenguji rupiah</textarea></td>
</tr>

<tr class='batas2' align='left'>
<td >&nbsp;Untuk pembayaran</td>
<td >:</td>
<td >&nbsp;Honor Penguji Seminar Hasil Kerja Praktek Tahun $dta[TahunID]</td>
</tr>

<tr class='batas2' align='left'>
<td >&nbsp;Mahasiswa</td>
<td >:</td>
<td >&nbsp;$dta[MhswID] - $Nama</td>
</tr>
<tr class='batas2' align='left'>
<td >&nbsp;Program Studi</td>
<td >:</td>
<td >&nbsp;($program[Nama]) $prodi[Nama] </td>
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
  <td align='center'>Bendaharawan</td>
<td align='center'>Setuju Dibayar</td>
<td align='center'>Yang Menerima</td>
</tr>
<tr  align='center'>
  <td align='center'>&nbsp;</td>
<td align='center'>Universitas Teknokrat Indonesia</td>
<td align='center'>&nbsp;</td>
</tr>

<tr  align='center'>
  <td align='left'>&nbsp;</td>
<td align='center'>Ketua</td>
<td align='center'>&nbsp;</td>
</tr>
<tr  align='center'>
  <td align='center'>&nbsp;</td>
  <td align='center'>&nbsp;</td>
  <td align='left'>&nbsp;</td>
</tr>
<tr  align='center'>
  <td align='center'>&nbsp;</td>
<td align='center'>&nbsp;</td>
<td align='center'>&nbsp;</td>
</tr>

<tr  align='center'>
  <td align='center'>Zupri Henra Hartomi, S.Kom</td>
<td align='center'>Hendry Fonda, M.Kom</td>
<td align='center'>$Penguji3, $dtu3[Gelar]</td>
</tr>
<tr  align='center'>
  <td align='center'>&nbsp;</td>
<td align='center'>&nbsp;</td>
<td align='center'></td>
</tr>

</table>

<table  border='0'  align=center' cellpadding='0' cellspacing='0' '>
<tr style=background-color:#FFB482; >
<td  width=300 style=text-align:left;color:#FFFFFF;font-size:8px;font-style:italic;height:20;>
- Mohon hitung kembali nominal uang yang diterima <br>
- Komplain tidak diterima setelah meninggalkan program studi 
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
</div>


</body>
</center>
";

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
