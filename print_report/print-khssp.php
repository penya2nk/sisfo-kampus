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
$tgl = tgl_indo(date('Y-m-d'));

if (empty($_SESSION['_Login']) && empty($_SESSION['_LevelID'])){
	header("Location: ../login.php");
}

else{

include "headerx-rpt.php"; $content = ob_get_clean();
$mhs       = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,ProgramID,MhswID,Nama FROM mhsw where MhswID='".strfilter($_GET[MhswID])."'"));
$ProgramID = $mhs[ProgramID];

$program   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProgramID,Nama FROM program where ProgramID='$ProgramID'"));
$prodi     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='".strfilter($_GET[prodi])."'"));

$content .= "

<table align='center'> 
<tr>
<td><b>KARTU HASIL STUDI SEMESTER PENDEK</b></td>
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
<td>Semester</td> <td>: $_GET[tahun]/ Semester Pendek</td>
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
        
$sq = mysqli_query($koneksi, "SELECT * from vw_sp where MhswID='".strfilter($_GET[MhswID])."' and TahunID='".strfilter($_GET[tahun])."'");
while($r=mysqli_fetch_array($sq)){
$no++;	       

$nilai = $r[NilaiAkhir];
if ($nilai >= 85 AND $nilai <= 100){
	$huruf = "A";
	$bobot = 4;
}
elseif ($nilai >= 80 AND $nilai <= 84.99){
	$huruf = "A-";
	$bobot = 3.70;
}
elseif ($nilai >= 75 AND $nilai <= 79.99){
	$huruf = "B+";
	$bobot = 3.30;
}
elseif ($nilai >= 70 AND $nilai <= 74.99){
	$huruf = "B";
	$bobot = 3;
}
elseif ($nilai >= 65 AND $nilai <= 69.99){
	$huruf = "B-";
	$bobot = 2.70;
}
elseif ($nilai >= 60 AND $nilai <= 64.99){
	$huruf = "C+";
	$bobot = 2.30;
}
elseif ($nilai >= 55 AND $nilai <= 59.99){
	$huruf = "C";
	$bobot = 2;
}
elseif ($nilai >= 50 AND $nilai <= 54.99){
	$huruf = "C-";
	$bobot = 1.70;
}
elseif ($nilai >= 40 AND $nilai <= 49.99){
	$huruf = "D";
	$bobot = 1;
}
elseif ($nilai < 40){
	$huruf = "E";
	$bobot = 0;
}

$total_sks 	  	= $r['SKS'];
$total_bobot  	= $r['SKS'] * $bobot;

$tsks 			+= $total_sks;
$tbobottotal 	+= $total_bobot;
$ips = number_format($tbobottotal / $tsks,2);


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
	<td>&nbsp; $r[NamaMK]</td>
	<td align=center >$r[SKS]</td>
	<td align=center >$huruf</td>
	<td align=center >$bobot</td>              
</tr>";
}

$content .= "
   <tr>
  <td colspan=3>&nbsp;&nbsp;IP Smt: $ips
  </td>
  <td  height='15' align=center>$tsks</td>
  <td colspan=2></td> 
  <td ></td>

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


try
	{
		$html2pdf = new HTML2PDF('P','Letter','en', false, 'ISO-8859-15',array(10, 10, 10, 10)); //setting ukuran kertas dan margin pada dokumen anda
		// $html2pdf->setModeDebug();
		$html2pdf->setDefaultFont('Arial');
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output($filename);
	}
	catch(HTML2PDF_exception $e) { echo $e; }
	
} //session login
?>	