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

$tgl 	   = tgl_indo(date('Y-m-d'));

include "headerx-rpt.php"; 
$content = ob_get_clean();
$mhs       = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,ProgramID,MhswID,Nama FROM mhsw where MhswID='".strfilter($_GET[MhswID])."'"));
$ProgramID = $mhs[ProgramID];

$program   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProgramID,Nama FROM program where ProgramID='$ProgramID'"));
$prodi     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='".strfilter($_GET[prodi])."'"));

$content .= "

<table align='center'> 
<tr>
<td><b>TRANSKRIP NILAI SEMESTER PENDEK</b></td>
</tr>
</table>     
<br>
<br>


<table width='800' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td width='100'>Nama </td>       
<td width='222'> : $mhs[Nama]</td>           
</tr>

<tr>
<td width='100'>NIM</td> 
<td width='155'>: $mhs[MhswID]</td>
</tr>

<tr>
<td>Program/Prodi</td>            
<td > : $program[Nama] / $prodi[Nama]</td>             
<td></td> <td></td>
</tr>
</table>
<br>
<table  width='800' border='0.3' align='center' cellspacing='0'>
<tr >
<th width='30' height='30' align='center'> NO.</th>
<th width='80' align='center'>KODE</th>
<th width='300' align='center'>MATA KULIAH</th>
<th width='60' align='center'> SKS</th>
<th width='60' align='center'>HURUF</th>
<th width='60' align='center'>BOBOT</th>
</tr>";
//$jenis = mysqli_query($koneksi, "SELECT * FROM jenismk where ProdiID='".strfilter($_GET[prodi])."'");  
// while ($j = mysqli_fetch_array($jenis)){

$content .= "<tr style='background-color:#E6E6E6'>
<td colspan='6' height='20' ><b>&nbsp;  j[Singkatan] (j[Nama])</b></td>
</tr>";        

$sq = mysqli_query($koneksi, "SELECT * from vw_transkripsp where MhswID='".strfilter($_GET[MhswID])."'  ORDER BY MKKode ASC"); //AND JenisMK='$j[Nama]'
$no = 1;
while($r=mysqli_fetch_array($sq)){
$Matakul = $r[NamaMK];
$Matakul_kecil 	= strtolower($Matakul);
$matKecil 	=ucwords($Matakul_kecil);	       
$nilai		= $r[NilaiAkhir];
/*if ($huruf=='A'){
	$bobot=4;
}
elseif ($huruf=='B'){
	$bobot=3;
}
elseif ($huruf=='C'){
	$bobot=2;
}
elseif ($huruf=='D'){
	$bobot=1;
}

else{
    $bobot=0; 
}
*/

if ($nilai >= 85 AND $nilai <= 100){
	$mutu = "A";
	$bobot = "4";
}
elseif ($nilai >= 80 AND $nilai <= 84.99){
	$mutu = "A-";
	$bobot = "3.75";
}
elseif ($nilai >= 75 AND $nilai <= 79.99){
	$mutu = "B+";
	$bobot = "3.25";
}
elseif ($nilai >= 70 AND $nilai <= 74.99){
	$mutu = "B";
	$bobot = "3";
}
elseif ($nilai >= 65 AND $nilai <= 69.99){
	$mutu = "B-";
	$bobot = "2.75";
}
elseif ($nilai >= 60 AND $nilai <= 64.99){
	$mutu = "C+";
	$bobot = "2.25";
}
elseif ($nilai >= 55 AND $nilai <= 59.99){
	$mutu = "C";
	$bobot = "2";
}
elseif ($nilai >= 50 AND $nilai <= 54.99){
	$mutu = "C-";
	$bobot = "1.75";
}
elseif ($nilai >= 45 AND $nilai <= 49.99){
	$mutu = "D";
	$bobot = "1";
}
elseif ($nilai < 45){
	$mutu = "E";
	$bobot = "0";
}

$total_sks 	 += $r['SKS'];
$total_bobot  = $r['SKS'] * $bobot;

$bobot 		 += $bobot;
$bobot_total += $total_bobot;

$bobotx      += $bobot;
$ipk = number_format($bobot_total / $total_sks,2);
if ($ipk >= 3.00) {
	$YAD=24;
	}
if ($ipk < 3.00) {
	$YAD=21;
	}
if ($ipk <= 2.49) {
	$YAD=18;
	}
if ($ipk <= 1.99) {
	$YAD=15;
	}
if ($ipk <= 1.4) {
	$YAD=12;
	}
$content .= "  <tr>
	<td height='15' align=center> $no</td>
	<td align=center>$r[MKKode]</td>
	<td>&nbsp;$matKecil</td>
	<td align=center >$r[SKS]</td>
	<td align=center >$r[GradeNilai]</td>
	<td align=center >$bobot</td>              
</tr>";
$no++;
}
// }
$content .= "</table>
<br>
<table border='0' align='left' cellspacing='0'>
  <tr>
  <td width='100' height='15'></td>
  <td width='100' height='15'>Total SKS </td><td>: $total_sks SKS</td>
  </tr>
  
  
  <tr>
  <td width='100' height='15'></td>
  <td>Total Bobot: </td><td>: $bobotx</td>
  </tr>
  
  <tr>
  <td width='100' height='15'></td>
  <td>IPK</td><td>: $ipk</td>
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
<td align='left'>$prodi[Pejabat]</td>
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