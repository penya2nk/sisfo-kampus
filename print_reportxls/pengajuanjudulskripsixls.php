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


$namaFile = "$prodi[Nama]_".$_GET[tahun]."_PengajuanJudulSkripsi.xls";
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
</tr>";	
	
$sq = mysqli_query($koneksi, "SELECT t_penelitian.*,mhsw.Nama,mhsw.ProdiID 
        FROM t_penelitian,mhsw 
        WHERE mhsw.MhswID=t_penelitian.MhswID 
        AND t_penelitian.TahunID='".strfilter($_GET[tahun])."' 
        AND mhsw.ProdiID='".strfilter($_GET[prodi])."' 
		AND t_penelitian.Status='DITERIMA'
        order by t_penelitian.MhswID asc");
while($r=mysqli_fetch_array($sq)){
$tanggal = $r[TglUjianProposal];
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

$p1     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$r[Pembimbing1]'"));
$p2     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$r[Pembimbing2]'"));



$namamhs_kecil 	= strtolower($r[Nama]);
$namamhs 		= ucwords($namamhs_kecil);

//Pembimbing ------------------------------------------
$pembimbingx1 	= $p1[Nama];
$namap1_kecil 	= strtolower($pembimbingx1);
$pembimbing1	= ucwords($namap1_kecil);

$namap2_kecil 	= strtolower($p2[Nama]);
$pembimbing2	= ucwords($namap2_kecil);

$no++;	       
	 
echo"<tr>
	<td  align=center> $no</td>
	<td align=center>$r[MhswID]</td>
	<td width='600px'>$namamhs</td>
	<td align=left >$r[Judul]</td>
	<td align=left >1. $pembimbing1, $p1[Gelar]  <br> 2. $pembimbing2, $p2[Gelar]<br></td> 
	
</tr>";
}

echo"</table>

 ";
	
}
?>