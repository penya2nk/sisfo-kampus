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


$namaFile = "$prodi[ProdiID]_".$_GET[tahun]."_JadwalNilaiSeminarHasilKP.xls";
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
<td colspan='2'>Seminar Hasil Kerja Praktek Tahun</td> <td>: $_GET[tahun]</td>
</tr>

<tr>
<td colspan='2'>Program Studi </td> <td>: $prodi[Nama]</td>           
</tr>

</table>
<br>
<table  border='1' align='center' cellspacing='0'>
<tr>
<th width='5' height='30' align='center'> NO.</th>
<th width='80' align='center'>NIM</th>
<th width='800' align='center'>NAMA MAHASISWA</th>
<th width='60' align='center'>NILAI PENGUJI 1</th>
<th width='60' align='center'>NILAI PENGUJI 2</th>
<th width='60' align='center'>NILAI PENGUJI 3</th>
<th width='60' align='center'>ANGKA</th>
<th width='60' align='center'>HURUF</th>
</tr>";	
$qrs = mysqli_query($koneksi, "SELECT * from vw_headerkp where TahunID='".strfilter($_GET[tahun])."' and ProdiID='".strfilter($_GET[prodi])."' order by TglMulaiSidang desc");//and ProgramID like '%$_GET[program]%'
			  while ($h = mysqli_fetch_array($qrs)){
			   $p1 = mysqli_fetch_array(mysqli_query("select Login,Nama from dosen where Login='$h[Penguji1]'"));
		       $p2 = mysqli_fetch_array(mysqli_query("select Login,Nama from dosen where Login='$h[Penguji2]'"));
			   $p3 = mysqli_fetch_array(mysqli_query("select Login,Nama from dosen where Login='$h[Penguji3]'"));  
				  $hd++;
			  echo"<tr style='background-color:#DFF4FF'>
				 <td colspan='8' height='30'><b>&nbsp;$hd. KLP: $h[KelompokID] [ $h[Nama] ] -  $h[Judul]</b></td>
				 </tr>"; 	
$sq = mysqli_query($koneksi, "SELECT * from vw_jadwalkp_anggota where KelompokID='$h[KelompokID]' AND  TahunID='".strfilter($_GET[tahun])."' and ProdiID='".strfilter($_GET[prodi])."'");
while($r=mysqli_fetch_array($sq)){
$p1     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$r[PembimbingSkripsi1]'"));
$p2     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$r[PembimbingSkripsi2]'"));

$namamhsx 		= $r[Nama];
$namamhs_kecil 	= strtolower($namamhsx);
$namamhs 		= ucwords($namamhs_kecil);


$pembimbingx1 	= $p1[Nama];
$namap1_kecil 	= strtolower($pembimbingx1);
$pembimbing1	= ucwords($namap1_kecil);

$pembimbingx2	= $p2[Nama];
$namap2_kecil 	= strtolower($pembimbingx2);
$pembimbing2	= ucwords($namap2_kecil);
$nilai 			= ($r[NilaiPengujiSidang1] + $r[NilaiPengujiSidang2] + $r[NilaiPengujiSidang3])/3;
if ($nilai >= 85 AND $nilai <= 100){
	$GradeNilai = "A";
	$bobot = "4";
}
elseif ($nilai >= 80 AND $nilai <= 84.99){
	$GradeNilai = "A-";
	$bobot = "3.75";
}
elseif ($nilai >= 75 AND $nilai <= 79.99){
	$GradeNilai = "B+";
	$bobot = "3.25";
}
elseif ($nilai >= 70 AND $nilai <= 74.99){
	$GradeNilai = "B";
	$bobot = "3";
}
elseif ($nilai >= 65 AND $nilai <= 69.99){
	$GradeNilai = "B-";
	$bobot = "2.75";
}
elseif ($nilai >= 60 AND $nilai <= 64.99){
	$GradeNilai = "C+";
	$bobot = "2.25";
}
elseif ($nilai >= 55 AND $nilai <= 59.99){
	$GradeNilai = "C";
	$bobot = "2";
}
elseif ($nilai >= 50 AND $nilai <= 54.99){
	$GradeNilai = "C-";
	$bobot = "1.75";
}
elseif ($nilai >= 45 AND $nilai <= 49.99){
	$GradeNilai = "D";
	$bobot = "1";
}
elseif ($nilai < 45){
	$GradeNilai = "E";
	$bobot = "0";
}
else{
	$GradeNilai = "TL";
	$bobot = "0";
	}
$no++;	       
	 
echo"<tr>
	<td  align=center> $no</td>
	<td align=center>$r[MhswID]</td>
	<td width='600px'>$namamhsx</td>
	 
	<td align=left >".substr($r[NilaiPengujiSidang1],0,2)."</td>
	<td align=left >".substr($r[NilaiPengujiSidang2],0,2)."</td>
	<td align=left >".substr($r[NilaiPengujiSidang3],0,2)."</td> 
	<td align=center >".substr($nilai,0,2)."</td>
	<td align=center >$GradeNilai</td>         
</tr>";
}
			  }
echo"</table>

 ";
	
}
?>