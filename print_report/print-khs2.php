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
$mhs       = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,ProgramID,MhswID,Nama FROM mhsw where MhswID='".strfilter($_GET['MhswID'])."'"));
$ProgramID = $mhs['ProgramID'];
$ProdiID   = $mhs['ProdiID'];

$program   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProgramID,Nama FROM program where ProgramID='$ProgramID'"));
$prodi     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='$ProdiID'"));

$ss       = mysqli_fetch_array(mysqli_query($koneksi, "SELECT KHSID,Sesi,TahunID,MhswID FROM khs where MhswID='".strfilter($_GET['MhswID'])."' and TahunID='".strfilter($_GET['tahun'])."'"));

// if ($ProdiID=='SI'){
// 	$ttd="<img width='100' width='120' src='ttd_herix000.png'>";
// }else{
// 	$ttd="<img width='100' width='120' src='ttd_yudax001.png'>";
// }


include "headerx-rpt.php";
$content .= "
<table align='center'> 
<tr>
<td><b>KARTU HASIL STUDI</b></td>
</tr>
</table>     
<br>
<br>


<table width='800' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td width='100'>Nama </td>       
<td width='222'> : $mhs[Nama]</td>           
<td width='100'>NIM</td> 
<td width='155'>: $mhs[MhswID]</td>
</tr>

<tr>
<td>Program/Prodi</td>            
<td > : $program[Nama] / $prodi[Nama]</td>             
<td>Semester</td> <td>: $_GET[tahun]/ $ss[Sesi]</td>
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
        
$sq = mysqli_query($koneksi, "SELECT * from vw_krs where MhswID='".strfilter($_GET['MhswID'])."' and TahunID='".strfilter($_GET['tahun'])."'");
while($r=mysqli_fetch_array($sq)){
$no++;	 
//$tugas 		= $r[Tugas1]+ $r[Tugas2] + $r[Tugas3];
//$ratatugas 	= $tugas/3;
//$nilai 		= (0.25*$ratatugas)+(0.15*$r[Presensi])+(0.25*$r[UTS])+(0.35*$r[UAS]);      
$nilai = $r['NilaiAkhir'];
if ($nilai >= 85 AND $nilai <= 100){
						$huruf = "A";
						$bobot = "4";
}
elseif ($nilai >= 80 AND $nilai <= 84.99){
	$huruf = "A-";
	$bobot = "3.70";
}
elseif ($nilai >= 75 AND $nilai <= 79.99){
	$huruf = "B+";
	$bobot = "3.30";
}
elseif ($nilai >= 70 AND $nilai <= 74.99){
	$huruf = "B";
	$bobot = "3";
}
elseif ($nilai >= 65 AND $nilai <= 69.99){
	$huruf = "B-";
	$bobot = "2.70";
}
elseif ($nilai >= 60 AND $nilai <= 64.99){
	$huruf = "C+";
	$bobot = "2.30";
}
elseif ($nilai >= 55 AND $nilai <= 59.99){
	$huruf = "C";
	$bobot = "2";
}
elseif ($nilai >= 50 AND $nilai <= 54.99){
	$huruf = "C-";
	$bobot = "1.70";
}
elseif ($nilai >= 40 AND $nilai <= 49.99){
	$huruf = "D";
	$bobot = "1";
}
elseif ($nilai < 40){
	$huruf = "E";
	$bobot = "0";
}

$total_sks 	  	= $r['SKS'];
$total_bobot  	= $r['SKS'] * $bobot;

$tsks 			+= $total_sks;
$tbobottotal 	+= $total_bobot;
$ips = number_format($tbobottotal / $tsks,2);

if ($ips >= 3.00) {
	$YAD=24;
	}
if ($ips < 3.00) {
	$YAD=21;
	}
if ($ips <= 2.49) {
	$YAD=18;
	}
if ($ips <= 1.99) {
	$YAD=15;
	}
if ($ips <= 1.4) {
	$YAD=12;
	}
	

$content .= "  <tr>
	<td height='15' align=center> $no</td>
	<td align=center>$r[MKKode]</td>
	<td>&nbsp; $r[NamaMK]</td>
	<td align=center >$r[SKS]</td>
	<td align=center >$r[GradeNilai]</td>
	<td align=center >$total_bobot</td>              
</tr>";
}

$content .= "
  <tr>
  <td colspan=3>&nbsp;&nbsp;IP Smt: $ips&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SKS YAD: $YAD SKS
  $bobot_total
  </td>
  <td  height='15' align=center>$tsks</td>
  <td colspan=2></td> 
  <td ></td>

  </tr>
</table>
<br>

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
  <td align='left' align='left'></td>
</tr>

<tr>
<td align='left'></td>
<td align='left'>$prodi[Pejabat]</td>
</tr>
</table> ";
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