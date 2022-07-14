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
$mboh     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM vw_mengajar where JadwalID='".strfilter($_GET[JadwalID])."'"));
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

echo"<table align='center'> 
<tr>
<td colspan=5 align=center><b>DAFTAR PESERTA SEMESTER PENDEK</b></td>
</tr>
</table>     
<br>

<table border='0' width='800px'  align='center'>
<tr><td colspan=2>Tahun Akademik</td><td colspan=4>: $_GET[tahun]</td> </tr>          
</table>
<br>
<table  width='800px' border='1' align='center' cellspacing='0'>
<tr style='background-color:#E6E6E6'>
  <td height='30' align='center'> NO.</td>
  <td align='center'>KODE</td>
  <td style='width:85px'  align='left' >MATAKULIAH</td>
  <td style='width:15px' align='center'>NIM</td>
  <td align='left'>MAHASISWA</td>
  </tr>
</tr>";
        
$sq = mysqli_query($koneksi, "SELECT * from vw_sp where TahunID='".strfilter($_GET[tahun])."' and ProdiID='".strfilter($_GET[prodi])."' and Periode='".strfilter($_GET[Periode])."' order by NamaMK asc");
while($r=mysqli_fetch_array($sq)){
$no++;	       

$NamaMhs 	= strtolower($r[NamaMhs]); //strtoupper($kalimat);
$NamaMhs2	= ucwords($NamaMhs);
echo"<tr>
	<td height='30' align=center> $no</td>
	<td align='center'>$r[MKKode]</td>
	<td align=left >$r[NamaMK]</td>
	<td align=center>$r[MhswID]</td>
	<td>&nbsp;$NamaMhs2</td>
    </tr>";
}

echo"

</table>
<br>



<table border=0 width='800px' align='center'>
<tr>
<td width='300' align='center'>&nbsp;</td>
<td width='230' align='left'>&nbsp;</td>
<td width='230' align='left'>&nbsp;</td>
<td colspan='3' align='left'>Pekanbaru, $tgl <br>
  Ketua Program Studi</td>
</tr>

<tr>
  <td align='left'></td>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  <td width='230' align='left' >&nbsp;</td>
  <td width='230' align='left' >&nbsp;</td>
  <td width='230' align='left' >&nbsp;</td>
</tr>

<tr>
  <td align='left'></td>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
</tr>



<tr>
<td align='left'></td>
<td align='left' >&nbsp;</td>
<td align='left' >&nbsp;</td>
<td colspan='3' align='left' >$prodi[Pejabat]</td>
</tr>
</table>
<font style='font-size:8px'>Login by: $_SESSION[id]</font>
";

	
} //session login
?>	