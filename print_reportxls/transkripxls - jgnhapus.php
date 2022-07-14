<?php 
session_start();
error_reporting(0);
include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
require ("../punksi/html2pdf/html2pdf.class.php");
$filename='namafile.pdf';
$tgl = tgl_indo(date('Y-m-d'));

if (empty($_SESSION['_Login']) && empty($_SESSION['_LevelID'])){
	header("Location: ../login.php");
}

else{

//$awal 		= $_GET['awal'];
//$akhir 		= $_GET['akhir'];
//$periode 	= tgl_indo($awal)." s/d ".tgl_indo($akhir);

$mhs       = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,ProgramID,MhswID,Nama FROM mhsw where MhswID='".strfilter($_GET[MhswID])."'"));
$ProgramID = $mhs[ProgramID];

$program   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProgramID,Nama FROM program where ProgramID='$ProgramID'"));
$prodi     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='".strfilter($_GET[prodi])."'"));


$namaFile = "$mhs[Nama]_".$_GET[MhswID]."_TraskripNilai.xls";
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=$namaFile");
header("Content-Transfer-Encoding: binary ");

echo "
<table  border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td colspan='2'>NIM</td> <td>: $mhs[MhswID]</td>
</tr>

<tr>
<td colspan='2'>Nama </td> <td>: $mhs[Nama]</td>           
</tr>

<tr>
<td colspan='2'>Program/Prodi </td> <td>: $program[Nama] / $prodi[Nama]</td>             
</tr>
</table>
<br>
<table  border='1' align='center' cellspacing='0'>
<tr>
<th width='5' height='30' align='center'> NO.</th>
<th width='80' align='center'>KODE</th>
<th width='600' align='center'>MATA KULIAH</th>
<th width='60' align='center'> SKS</th>
<th width='60' align='center'>HURUF</th>
<th width='60' align='center'>BOBOT</th>
</tr>";	

$jenis = mysqli_query($koneksi, "SELECT * FROM jenismk where ProdiID='".strfilter($_GET[prodi])."'");  
while ($j = mysqli_fetch_array($jenis)){
echo "<tr style='background-color:#E6E6E6'>
<td colspan='6' height='20' ><b>$j[Singkatan] ($j[Nama])</b></td>
</tr>"; 	
$sq = mysqli_query($koneksi, "SELECT * FROM vw_transkrip WHERE MhswID='".strfilter($_GET[MhswID])."' AND JenisMK='$j[Nama]' ORDER BY MKKode,substr(MKKode,0,4) ASC");
while($r=mysqli_fetch_array($sq)){
$Matakul 	= $r[NamaMK];
$Matakul_kecil 	= strtolower($Matakul);
$matKecil 	= ucwords($Matakul_kecil);
	  $no++;	       
	  $huruf= $r[GradeNilai];
	  if ($huruf=='A'){
		  $bobot=4;
	  }
	  elseif ($huruf=='A-'){
		  $bobot=3.70;
	  }
	  elseif ($huruf=='B+'){
		  $bobot=3.30;
	  }
	  elseif ($huruf=='B'){
		  $bobot=3;
	  }
	  elseif ($huruf=='B-'){
		  $bobot=2.70;
	  }
	  elseif ($huruf=='C+'){
		  $bobot=2.30;
	  }
	  elseif ($huruf=='C'){
		  $bobot=2;
	  }
	  elseif ($huruf=='C-'){
		  $bobot=1.70;
	  }
	  elseif ($huruf=='D'){
		  $bobot=1;
	  }	  
	  else{
		  $bobot=0; 
	  }
	  $total_sks 	+= $r['SKS'];
	  $total_bobot  = $r['SKS'] * $bobot;
	  
	  $bobot 	+= $bobot;
	  $bobot_total += $total_bobot;
	  
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
echo"<tr>
	<td  align=center> $no</td>
	<td align=center>$r[MKKode]</td>
	<td width='600px'>$matKecil</td>
	<td align=center >$r[SKS]</td>
	<td align=center >$r[GradeNilai]</td>
	<td align=center >$bobot</td>              
</tr>";
}
}
echo"</table>
<br>

<table border='0.3'  align='center' cellspacing='0'>
  <tr>
  <td >Total SKS</td> 
  <td> $total_sks </td><td>SKS</td>
  </tr>
  
  <tr>
  <td>Total Bobot</td> 
  <td align='right'>$bobot_total</td> 
  </tr>
  
  <tr>
  <td>IPK</td> 
  <td align='right'> $ipk</td> 
  </tr>
</table>

<br>
 ";
	
}
?>