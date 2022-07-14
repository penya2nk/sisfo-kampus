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


//$namaFile = "".date('d-m-Y')."-".$_GET[tahun]."-".$prodi[ProdiID]."-PembayaranKuliah.xls";
$namaFile = "".date('d-m-Y')."-".$prodi[ProdiID]."-TunggakanSPP.xls";
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
<td colspan='2'>Program Studi </td> <td>: $prodi[Nama]</td>           
</tr>
<tr>
<td colspan='2'>Tahun</td> <td>: $_GET[tahun]</td>           
</tr>

</table>
<br>
<table  border='1' align='center' cellspacing='0'>
<tr>
<th width='5' height='30' align='center'> NO.</th>
<th width='80' align='center'>NIM</th>
<th width='800' align='center'>NAMA MAHASISWA</th>
<th width='100' align='center'>TAHUN</th>
<th width='800' align='center'>KETERANGAN</th>
</tr>";	
$qrs = mysqli_query($koneksi, "SELECT khs.*,mhsw.Nama from mhsw,khs 
		WHERE khs.MhswID=mhsw.MhswID 
		AND khs.TahunID='".strfilter($_GET[tahun])."' 
		AND khs.ProdiID='".strfilter($_GET[prodi])."'
		AND khs.StatusMhswID<>'A'
		AND mhsw.NA='N'
		AND mhsw.StatusMhswID='A' 
		ORDER BY khs.MhswID Desc");//and ProgramID like '%$_GET[program]%'
while($r=mysqli_fetch_array($qrs)){
$no++;	
if ($r[StatusMhswID]<>'A'){
	$c="style=color:red";
	$ket="Belum Lunas";
}else{
	$ket="Lunas";
	$c="style=color:green";
}
echo "<tr><td>$no</td>
		  <td>$r[MhswID]</td>
		  <td>$r[Nama]</td>
		  <td>$r[TahunID]</td>
		  <td $c>$ket</td>
	</tr>";
}
echo"</table><br>";

	
}
?>