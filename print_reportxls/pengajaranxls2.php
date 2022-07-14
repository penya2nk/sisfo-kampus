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

$mhs       = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,ProgramID,MhswID,Nama FROM mhsw where MhswID='".strfilter($_GET[MhswID])."'"));
$ProgramID = $mhs[ProgramID];

$program   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProgramID,Nama FROM program where ProgramID='$ProgramID'"));
$prodi     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='".strfilter($_GET[prodi])."'"));


$namaFile = "$mhs[Nama]_".$_GET[tahun]."_Pengajaran.xls";
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=$namaFile");
header("Content-Transfer-Encoding: binary ");

echo "

<table  border='1' align='center' cellspacing='0'>
<tr>
<th colspan='6' height='30'> PENGAJARAN $_GET[tahun]</th>
</tr>
<tr>
<th width='60' height='30' align='center'> NO.</th>
<th width='80' align='center'>KODE</th>
<th width='600' align='center'>MATA KULIAH</th>
<th width='60' align='center'>SKS</th>
<th width='60' align='center'> Kelas</th>
<th width='130' align='center'>Program Studi</th>

</tr>";	

$dosen = mysqli_query($koneksi, "SELECT Login,Nama,Gelar,Handphone FROM dosen where NA='N'");  
$no=0;
while ($j = mysqli_fetch_array($dosen)){
	$totsks = mysqli_fetch_array(mysqli_query($koneksi, "SELECT DosenID,sum(SKS) as TotSKS FROM jadwal_rekapsks where DosenID='$j[Login]' "));
$hd++;
//style='background-color:#DFF4FF'
echo"<tr >
<th colspan='6' height='20' ><div align=left><b>&nbsp;  $hd.  $j[Nama],$j[Gelar] - $j[Handphone] - $totsks[TotSKS] SKS</b> </div></th>
</tr>";	
$sq = mysqli_query($koneksi, "SELECT  
		  dosen.Login,dosen.Nama as NamaDosen,dosen.Gelar,
		  mk.MKID,mk.MKKode,mk.Nama as NamaMK,
		  jadwal_rekapsks.JadwalID,
		   jadwal_rekapsks.DosenID,
		  jadwal_rekapsks.ProdiID,
		  jadwal_rekapsks.SKS,
		  jadwal_rekapsks.NamaKelas,
		  jadwal_rekapsks.TahunID
		  from dosen,mk,jadwal_rekapsks
		  WHERE dosen.Login=jadwal_rekapsks.DosenID
		  AND mk.MKID=jadwal_rekapsks.MKID
		  AND jadwal_rekapsks.TahunID='".strfilter($_GET[tahun])."'		 
		  AND Jadwal_rekapsks.DosenID='$j[Login]'
		  order by NamaDosen");
while($r=mysqli_fetch_array($sq)){
	$no++;
$Matakul 	= $r[NamaMK];
$Matakul_kecil 	= strtolower($Matakul);
$matKecil 	= ucwords($Matakul_kecil);
echo"<tr>
	<td  align=center> $no</td>
	<td align=center>$r[MKKode]</td>
	<td width='600px'>$matKecil</td>
	<td align=center >$r[SKS]</td>
	<td align=center >$r[NamaKelas]</td>
	<td align=center >$r[ProdiID]</td>              
</tr>";
}
}
echo"</table>";
	
}
?>