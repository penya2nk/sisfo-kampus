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

$mhs       = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM t_yudisium where TahunID='".strfilter($_GET[tahun])."' and ProdiID='".strfilter($_GET[prodi])."'"));
$ProgramID = $mhs[ProgramID];

$program   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProgramID,Nama FROM program where ProgramID='$ProgramID'"));
$prodi     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='".strfilter($_GET[prodi])."'"));

$namaFile = "".date('d-m-Y')."-".$prodi[ProdiID]."-DataWisuda.xls";
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
<th width='80' align='center'>Nama Mahasiswa</th>
<th width='800' align='center'>NIM</th>
<th width='800' align='center'>Tempat Lahir</th>
<th width='100' align='center'>Tgl Lahir</th>
<th width='800' align='center'>Jenis Kelamin</th>
<th width='800' align='center'>Alamat</th>
<th width='800' align='center'>Prodi</th>
<th width='800' align='center'>IPK</th>
<th width='800' align='center'>Judul Penelitian</th>
<th width='800' align='center'>Tgl Ujian</th>
<th width='800' align='center'>Pembimbing 1</th>
<th width='800' align='center'>Pembimbing 2</th>
<th width='800' align='center'>Nama Ayah</th>
<th width='800' align='center'>Nama Ibu</th>
</tr>";	
$qrs = mysqli_query($koneksi, "SELECT * from t_yudisium where ProdiID='".strfilter($_GET[prodi])."' and TahunID='".strfilter($_GET[tahun])."'");//and ProgramID like '%$_GET[program]%'
while($r=mysqli_fetch_array($qrs)){
$mhs=mysqli_fetch_array(mysqli_query($koneksi, "SELECT mhsw.Nama,mhsw.MhswID,mhsw.TempatLahir,mhsw.TanggalLahir,mhsw.Kelamin,mhsw.Alamat,mhsw.ProdiID,
mhsw.NamaAyah,mhsw.NamaIbu from mhsw where MhswID='$r[MhswID]'"));
$judul=mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from jadwal_skripsi where MhswID='$r[MhswID]'"));
$pembimbing1=mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar from dosen where Login='$judul[PembimbingSkripsi1]'"));
$pembimbing2=mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar from dosen where Login='$judul[PembimbingSkripsi2]'"));

$no++;	
if ($r[Status]=='Belum Lunas'){
	$c="style=color:red";
	$ket="Belum Lunas";
}
elseif ($r[Status]=='Menunggu Verifikasi'){
	$c="style=color:black";
	$ket="Menunggu Verifikasi";
}
else{
	$ket="Lunas";
	$c="style=color:green";
}
echo "<tr><td>$no</td>
		  <td>$r[Nama]</td>
		  <td>$r[MhswID]</td>
		  <td>$mhs[TempatLahir]</td>
		  <td>$mhs[TanggalLahir]</td>
		   <td>$mhs[Kelamin]</td>
		 <td>$mhs[Alamat]</td>
		 <td>$mhs[ProdiID]</td>
		 <td>$r[IPK]</td>
		 <td>$judul[Judul]</td>
		 <td>$judul[TglUjianSkripsi]</td>
		 <td>$pembimbing1[Nama], $pembimbing1[Gelar]</td> 
		 <td>$pembimbing2[Nama], $pembimbing2[Gelar]</td> 
		 <td>$mhs[NamaAyah]</td>
		 <td>$mhs[NamaIbu]</td>
	</tr>";
}
echo"</table><br>";

	
}
?>