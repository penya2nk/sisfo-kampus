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
$ProdiID   = $mhs[ProdiID];

$program   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProgramID,Nama FROM program where ProgramID='$ProgramID'"));
$prodi     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='$ProdiID'"));

$ss       = mysqli_fetch_array(mysqli_query($koneksi, "SELECT KHSID,Sesi,TahunID,MhswID FROM khs where MhswID='".strfilter($_GET[MhswID])."' and TahunID='".strfilter($_GET[tahun])."'"));

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
        
$sq = mysqli_query($koneksi, "SELECT * from vw_krs where MhswID='".strfilter($_GET[MhswID])."' and TahunID='".strfilter($_GET[tahun])."'");
while($r=mysqli_fetch_array($sq)){
$no++;	 
//$tugas 		= $r[Tugas1]+ $r[Tugas2] + $r[Tugas3];
//$ratatugas 	= $tugas/3;
//$nilai 		= (0.25*$ratatugas)+(0.15*$r[Presensi])+(0.25*$r[UTS])+(0.35*$r[UAS]);      
$nilai = $r[NilaiAkhir];
if ($nilai >= 80 AND $nilai <= 100){
	$huruf = "A";
}
elseif ($nilai >= 68 AND $nilai <= 79.99){
	$huruf = "B";
}
elseif ($nilai >= 56 AND $nilai <= 67.99){
	$huruf = "C";
}
elseif ($nilai >= 45 AND $nilai <= 55.99){
	$huruf = "D";
}
elseif ($nilai >= 0 AND $nilai <= 44.99){
	$huruf = "E";
}

//$huruf= $r[GradeNilai];
if ($huruf=='A'){
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
	<td align=center >$huruf</td>
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