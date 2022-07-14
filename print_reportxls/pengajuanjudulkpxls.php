<?php 
session_start();
//error_reporting(0);
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
$prodi     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='".strfilter($_GET[prodi])."'"));

$namaFile = "$prodi[Nama]_".$_GET[tahun]."_PengajuanJudulKP.xls";
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
<td colspan='3'>Pengajuan Judul Kerja Praktek Tahun: $_GET[tahun]</td>
</tr>

<tr>
<td colspan='3'>Program Studi: $prodi[Nama]</td>           
</tr>

</table>
<br>
<table  border='1' align='center' cellspacing='0'>
<tr>
<th width='3' height='30' align='center'> NO.</th>
<th width='30' align='center'> NIM</th>
<th width='900px' align='left'>Nama Mahasiswa</th>
</tr>";	
	
$sq = mysqli_query($koneksi, "SELECT jadwal_kptemp.*,dosen.Nama 
from jadwal_kptemp,dosen
where jadwal_kptemp.DosenID=dosen.Login
and jadwal_kptemp.TahunID='".strfilter($_GET[tahun])."' 
and jadwal_kptemp.ProdiID='".strfilter($_GET[prodi])."'");

while($r=mysqli_fetch_array($sq)){
$Judulx 	= strtolower($r[Judul]);
$Judul 		= ucwords($Judulx);

$p1     		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$r[DosenID]'"));
$Namax 			= strtolower($r[Nama]);
$NamaDos 		= ucwords($Namax);

$no++;	       
	 
echo"<tr>
	<td  align=left colspan=3 width=1200px> $no | <b style=color:green>$NamaDos, $p1[Gelar] </b>| $Judul </td> 	      
</tr>";

$sqx = mysqli_query($koneksi, "SELECT jadwal_kp_anggotatemp.*,mhsw.Nama 
from jadwal_kp_anggotatemp,mhsw
where jadwal_kp_anggotatemp.MhswID=mhsw.MhswID
and jadwal_kp_anggotatemp.JadwalID='$r[JadwalID]' ");
$nom=0;
while($rx=mysqli_fetch_array($sqx)){
$nom++;
$NamaMhsx 	= strtolower($rx[Nama]);
$NamaMhs 		= ucwords($NamaMhsx);
echo"<tr>
	<td  align=center> $nom</td>
	<td align=center>$rx[MhswID]</td>
	<td >$NamaMhs</td>      
</tr>";
}
}
echo"</table>";
	
}
?>