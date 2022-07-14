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
$tgl= tgl_indo(date('Y-m-d'));

$filename="datasp_$_GET[SpID].pdf";
if (empty($_SESSION['_Login']) && empty($_SESSION['_LevelID'])){
	header("Location: ../login.php");
}
else{
/**/
$namaFile = "$mboh[NamaDos]_".$matKecil."_PesertaSP.xls";
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=$namaFile");
header("Content-Transfer-Encoding: binary ");

$w = mysqli_fetch_array(mysqli_query("select MKID,MKKode,Nama,SKS,Sesi from mk where MKID='".strfilter($_GET[MKID])."'"));
echo"<table align='center'> 
<tr>
<td colspan=3 align=center><b>DAFTAR PESERTA SEMESTER PENDEK</b></td>
</tr>
</table>     
<br>

<table align='center'> 
<tr>
<td colspan=2>Kode</td> <td colspan=3>: $w[MKKode]</td>
</tr>
<tr>
<td colspan=2>Matakuliah</td> <td colspan=3>: $w[Nama] - $w[SKS]</td>
</tr>
<tr>
<td colspan=2>Tahun Akademik</td> <td colspan=3>: $_GET[tahun]</td>
</tr>
</table> 

<br>
<table  width='800px' border='1' align='center' cellspacing='0'>
<tr style='background-color:#E6E6E6'>
  <td height='30' align='center'>NO.</td>
  <td style='width:15px' align='center'>NIM</td>
  <td align='left' style='width:700px'>MAHASISWA</td>
</tr>";
        
$sq = mysqli_query($koneksi, "SELECT * from vw_sp 
where TahunID='".strfilter($_GET[tahun])."'
and MKID='".strfilter($_GET[MKID])."' 
and ProdiID='".strfilter($_GET[prodi])."'
and Periode='".strfilter($_GET[Periode])."' order by NamaMK asc");
while($r=mysqli_fetch_array($sq)){
	$no++;	       
	$NamaMhs  = strtolower($r[NamaMhs]); //strtoupper($kalimat);
	$NamaMhs2 = ucwords($NamaMhs);
echo"<tr>
	<td height='30' align=center> $no</td>	
	<td align=center>$r[MhswID]</td>
	<td>&nbsp;$NamaMhs2</td>
    </tr>";
}
echo"</table>";
} //session login
?>	