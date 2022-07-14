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

if (empty($_SESSION['_Login']) && empty($_SESSION['_LevelID'])){
	header("Location: ../login.php");
}
else{

/**/
$namaFile = "$mboh[NamaDos]_".$matKecil."DataWisuda.xls";
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=$namaFile");
header("Content-Transfer-Encoding: binary ");

echo"<table align='center'> 
<table  width='800px' border='1' align='center' cellspacing='0'>
<tr style='background-color:#E6E6E6'>
  <td height='30' align='center'> NO.</td>
  <td align='center'>NIM</td>
  <td style='width:85px'  align='left' >Nama Wisudawan/Ti</td>
  <td style='width:15px' align='left'>Tempat Lahir</td>
  <td style='width:15px' align='left'>Tgl Lahir</td>
  <td align='left'>Alamat</td>
  <td  align='center'>Prodi</td>
   <td  align='center'>IPK</td>
  <td  align='left'>Judul Skripsi</td>
  <td  align='center'>Tanggal Ujian</td>
  <td  align='left'>Pembimbing 1</td>
  <td  align='left'>Pembimbing 2</td>
  <td  align='left'>Nama Ayah</td>
  <td  align='left'>Nama Ibu</td>
  <td  align='center'>TahunID</td>
  </tr>
</tr>";
        
$sq = mysqli_query($koneksi, "SELECT * FROM vw_wisuda WHERE TahunID='".strfilter($_GET[tahun])."'");
while($r=mysqli_fetch_array($sq)){
$no++;	
$p1=mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama as NamaDos1,Gelar FROM dosen where Login='$r[PembimbingSkripsi1]'"));
$NamaP1x = strtolower($p1[NamaDos1]);
$NamaP1	= ucwords($NamaP1x);

$p2=mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama as NamaDos2,Gelar FROM dosen where Login='$r[PembimbingSkripsi2]'"));
$NamaP2x 	= strtolower($p2[NamaDos2]);
$NamaP2	= ucwords($NamaP2x);

$NamaMhsx 	= strtolower($r[Nama]);
$NamaMhs	= ucwords($NamaMhsx);  
    
$TempatLahirx 	= strtolower($r[TempatLahir]);
$TempatLahir	= ucwords($TempatLahirx);

$Alamatx 	= strtolower($r[Alamat]);
$Alamat	= ucwords($Alamatx);

$NamaAyahx 	= strtolower($r[NamaAyah]);
$NamaAyah	= ucwords($NamaAyahx);

$NamaIbux 	= strtolower($r[NamaIbu]);
$NamaIbu	= ucwords($NamaIbux);

$Judulx 	= strtolower($r[judul]);
$Judul	= ucwords($Judulx);

echo"<tr>
	<td height='30' align=center> $no</td>
	<td align=center>$r[MhswID]</td>
	<td>&nbsp;$NamaMhs</td>
	<td align='left'>$TempatLahir</td>
	<td align='left'>$r[TanggalLahir]</td>
	<td align='left'>$Alamat</td>
	<td align='left'>$r[ProdiID]</td>
	<td align='left'>$r[IPK]</td>
	<td align='left'>$Judul</td>
	<td align='left'>$r[TglUjianSkripsi]</td>
	<td align='left'>$NamaP1, $p1[Gelar]</td>
	<td align='left'>$NamaP2, $p2[Gelar]</td>
	<td align='left'>$NamaAyah</td>
	<td align='left'>$NamaIbu</td>
	<td align='center'>$r[TahunID]</td>	
    </tr>";
}

echo"</table>";

} //session login
?>	