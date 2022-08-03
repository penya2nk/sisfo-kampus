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
$m = mysqli_fetch_array(mysqli_query($koneksi, "SELECT mhsw.MhswID, mhsw.Nama AS NamaMhs, 
mhsw.ProdiID, mhsw.ProgramID, 
prodi.Nama AS NamaProdi 
FROM mhsw,prodi,program 
WHERE mhsw.ProdiID=prodi.ProdiID
AND mhsw.ProgramID=program.ProgramID
AND mhsw.MhswID='".strfilter($_GET[MhswID])."'")); 

$mhs 	= strtolower($m[NamaMhs]);
$mhsKecil 	= ucwords($mhs);

if ($m[ProdiID]=='SI'){
	$k = mysqli_fetch_array(mysqli_query($koneksi, "SELECT KurikulumID,NA,ProdiID from kurikulum WHERE ProdiID='$m[ProdiID]' AND NA='N'"));
	$p = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from prodi WHERE ProdiID='$m[ProdiID]'"));
	$pejabat =$p[Pejabat]; 
}else{
	$k = mysqli_fetch_array(mysqli_query($koneksi, "SELECT KurikulumID,NA,ProdiID from kurikulum WHERE ProdiID='$m[ProdiID]' AND NA='N'"));
	$p = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from prodi WHERE ProdiID='$m[ProdiID]'"));
	$pejabat =$p[Pejabat]; 
}      
$content .= "

<table align='center'> 
<tr>
<td><b>DAFTAR MATAKULIAH BELUM DISELESAIKAN</b></td>
</tr>
</table>     
<br>
<br>


<table width='800' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td width='150'>Nama Mahasiswa</td>       
<td width='200'> : $mhsKecil</td>           
</tr>

<tr>
<td >NIM</td> 
<td >: $m[MhswID]</td>
</tr>

<tr>
<td>Program Studi</td>            
<td > : $m[NamaProdi]</td>             
<td></td> <td></td>
</tr>
</table>
<br>
<table  width='800' border='0.3' align='center' cellspacing='0'>
<tr style='background-color:#E6E6E6'>
<th width='30' height='20' align='center'> No.</th>
<th width='80' align='center'>Kode</th>
<th width='300' align='left'>&nbsp;Matakuliah</th>
<th width='60' align='center'> SKS</th>
<th width='80' align='center'>Semester</th>
</tr>";
$no = 1;									
$tampil = mysqli_query($koneksi, "SELECT mk.MKID, mk.MKKode,mk.Nama AS Matakuliah, mk.KurikulumID, mk.Sesi,mk.SKS,mk.NA, 
krs.KRSID
FROM mk
LEFT OUTER JOIN krs ON mk.MKID=krs.MKID 
AND krs.MhswID='".strfilter($_GET[MhswID])."'  
WHERE krs.KRSID IS NULL
AND mk.KurikulumID='$k[KurikulumID]'
AND mk.NA='N'
ORDER BY mk.Sesi ASC ");  								
while($r=mysqli_fetch_array($tampil)){  
$Matakul 	= strtolower($r[Matakuliah]);
$matKecil 	= ucwords($Matakul);	 					         
$content .= "  <tr bgcolor=$warna>
	<td align='center'>$no</td>
	<td align='center'>$r[MKKode]</td>
	<td >&nbsp;$matKecil</td>
	<td align='center'>$r[SKS]</td>
	<td align='center'>$r[Sesi]</td>	
	</tr>";
$no++;
$tsks += $r[SKS];
}

$content .= "</table>
<br>
<table border='0' width='800' align='center' cellspacing='0'>
  <tr>
  <td width='500' height='15'>Total SKS: $tsks SKS</td>
  </tr>  
</table>

<br>
<br>
<br>

<table border=0 width='800' align='center'>
<tr>
<td width='423' align='center'>&nbsp;</td>
<td width='367' align='left'>Pekanbaru, $tgl  <br>Ketua Program Studi</td>
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
<td align='left'>$pejabat</td>
</tr>
</table> ";
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