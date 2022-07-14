<?php 
session_start();
//error_reporting(0);
include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
require ("../punksi/html2pdf/html2pdf.class.php");

$filename="namafile.pdf";
$tgl = tgl_indo(date('Y-m-d'));

if (empty($_SESSION['_Login']) && empty($_SESSION['_Login'])){
	header("Location: ../login.php");
}

else{

$prodi     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='".strfilter($_GET['prodi'])."'"));


$namaFile = "$prodi[Nama]_".$_GET['tahun']."_Jadwal_Proposal_Skripsi.xls";
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
<td colspan='2'>Skripsi Tahun</td> <td>: $_GET[tahun]</td>
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
<th width='600' align='center'>NAMA MAHASISWA</th>
<th width='60' align='center'> JUDUL</th>
<th width='60' align='center'>PEMBIMBING</th>
<th width='60' align='center'>WAKTU DAN TEMPAT</th>
<th width='60' align='center'>PENGUJI</th>
<th width='120px' align='center'>NILAI</th>



</tr>";	
$no=0;	
$sq = mysqli_query($koneksi, "SELECT * from vw_jadwal_skripsi_ujian where TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."'");
while($r=mysqli_fetch_array($sq)){
$tanggal = $r['TglUjianProposal'];
//$day = date('D', strtotime($tanggal));
$day = date('D', strtotime($tanggal));
$dayList = array(
	'Sun' => 'Minggu',
	'Mon' => 'Senin',
	'Tue' => 'Selasa',
	'Wed' => 'Rabu',
	'Thu' => 'Kamis',
	'Fri' => 'Jumat',
	'Sat' => 'Sabtu'
);	
$d     	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT RuangID,Nama FROM ruang where RuangID='$r[TempatUjian]'"));

$p1     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$r[PembimbingSkripsi1]'"));
$p2     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$r[PembimbingSkripsi2]'"));

$x1     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$r[PengujiPro1]'"));
$x2     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$r[PengujiPro2]'"));
$x3     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$r[PengujiPro3]'"));


$namamhsx 		= $r['Nama'];
$namamhs_kecil 	= strtolower($namamhsx);
$namamhs 		= ucwords($namamhs_kecil);

//Pembimbing ------------------------------------------
$pembimbingx1 	= $p1['Nama'];
$namap1_kecil 	= strtolower($pembimbingx1);
$pembimbing1	= ucwords($namap1_kecil);

$pembimbingx2	= $p2['Nama'];
$namap2_kecil 	= strtolower($pembimbingx2);
$pembimbing2	= ucwords($namap2_kecil);

//Penguji ---------------------------------------------
$pengujix1		= $x1['Nama'];
$penguji_kecilx1= strtolower($pengujix1);
$penguji1		= ucwords($penguji_kecilx1);

$pengujix2		= $x2['Nama'];
$penguji_kecilx2= strtolower($pengujix2);
$penguji2		= ucwords($penguji_kecilx2);

$pengujix3		= $x3['Nama'];
$penguji_kecilx3= strtolower($pengujix3);
$penguji3		= ucwords($penguji_kecilx3);

$nilai 			= ($r['NilaiPengujiPro1'] + $r['NilaiPengujiPro2'] + $r['NilaiPengujiPro3'])/3;
if ($nilai >= 85 AND $nilai <= 100){
	$GradeNilai = "A";
	$bobot = "4";
}
elseif ($nilai >= 80 AND $nilai <= 84.9){
	$GradeNilai = "A-";
	$bobot = "3.75";
}
elseif ($nilai >= 75 AND $nilai <= 79.9){
	$GradeNilai = "B+";
	$bobot = "3.25";
}
elseif ($nilai >= 70 AND $nilai <= 74.9){
	$GradeNilai = "B";
	$bobot = "3";
}
elseif ($nilai >= 65 AND $nilai <= 69.9){
	$GradeNilai = "B-";
	$bobot = "2.75";
}
elseif ($nilai >= 60 AND $nilai <= 64.9){
	$GradeNilai = "C+";
	$bobot = "2.25";
}
elseif ($nilai >= 55 AND $nilai <= 59.9){
	$GradeNilai = "C";
	$bobot = "2";
}
elseif ($nilai >= 50 AND $nilai <= 54.9){
	$GradeNilai = "C-";
	$bobot = "1.75";
}
elseif ($nilai >= 45 AND $nilai <= 49.9){
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
	<td align=left >$r[Judul]</td>
	<td align=left >1. $pembimbing1, $p1[Gelar]  <br> 2. $pembimbing2, $p2[Gelar]<br></td> 
	<td align='right' width='120px'>" .$dayList[$day].",".$r['TglUjianProposal']. " <br> " .$r['JamMulaiProSkripsi']. "-" .$r['JamSelesaiProSkripsi']. " <br> ".$d['Nama']. " <br></td> 
	<td align=left >1. $penguji1, $x1[Gelar]  <br> 2. $penguji2, $x2[Gelar]<br> 3. $penguji3, $x3[Gelar]<br></td>  
	
	<td align='right' width='120px'>" .substr($r['NilaiPengujiPro1'],0,2). " <br> " .substr($r['NilaiPengujiPro2'],0,2). " <br> " .substr($r['NilaiPengujiPro3'],0,2). " <br></td>
	        
</tr>";
}

echo"</table>

 ";
	
}
?>