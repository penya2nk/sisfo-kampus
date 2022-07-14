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

$filename="absensiuas_$_GET[JadwalID].pdf";
if (empty($_SESSION['_Login']) && empty($_SESSION['_LevelID'])){
	header("Location: ../login.php");
}
else{
$mboh     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM vw_mengajar where JadwalID='".strfilter($_GET[JadwalID])."'"));
$prodi    = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='".strfilter($_GET[prodi])."'"));
$hari 	  = mysqli_fetch_array(mysqli_query($koneksi, "SELECT HariID,Nama FROM hari where HariID='$mboh[HariID]'"));
$ruang 	  = mysqli_fetch_array(mysqli_query($koneksi, "SELECT RuangID,Nama FROM ruang where RuangID='$mboh[RuangID]'"));

$Matakul  		= $mboh[NamaMK];
$Matakul_kecil 	= strtolower($Matakul);
$matKecil 		= ucwords($Matakul_kecil);
$NamaDosenlower = strtolower($mboh[NamaDos]);
$NamaDosen 		= ucwords($NamaDosenlower);
/**/
$namaFile = "$mboh[NamaDos]_".$matKecil."_AbsensiUAS.xls";
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
<td colspan=6 align=center><b>DAFTAR HADIR UJIAN TENGAH SEMESTER</b></td>
</tr>
</table>     
<br>

<table border='0' width='800px'  align='center'>
<tr><td colspan=2>Tahun Akademik</td><td colspan=4>: $mboh[TahunID]</td> </tr>          
<tr><td colspan=2>Waktu / Ruang</td><td colspan=4>: $hari[Nama], ".tgl_indo($mboh[UTSTanggal]).", $mboh[JamMulai]-$mboh[JamSelesai] WIB / $ruang[Nama]</td> </tr>
<tr><td colspan=2>Program Studi</td><td colspan=4>: $prodi[Nama]</td></tr>             
<tr><td colspan=2>Matakuliah</td><td colspan=4>: $mboh[MKKode] - $matKecil ($mboh[SKS] SKS) - $mboh[NamaKelas]  - Semester $mboh[Sesi]</td></tr>
<tr><td colspan=2>Dosen Pengampu</td><td colspan=4>: $mboh[Login] - $NamaDosen, $mboh[Gelar]</td></tr>
</table>
<br>
<table  width='800px' border='1' align='center' cellspacing='0'>
<tr style='background-color:#E6E6E6'>
  <td height='30' align='center'> NO.</td>
  <td align='center'>NIM</td>
  <td style='width:85px'  align='left' >&nbsp;NAMA MAHASISWA</td>
  <td align='center'>TANDA TANGAN</td>
  <td  align='center'>KETERANGAN</td>
  </tr>
</tr>";
        
$sq = mysqli_query($koneksi, "SELECT * from vw_krs where JadwalID='".strfilter($_GET[JadwalID])."' order by MhswID Asc");
while($r=mysqli_fetch_array($sq)){
$no++;	       
//$huruf= $r[GradeNilai];
$NamaMhs 		= $r[NamaMhs];
$NamaMhs_kecil 	= strtolower($NamaMhs ); //strtoupper($kalimat);
$NamaMhs_AKecil	= ucwords($NamaMhs_kecil);
echo"<tr>
	<td height='30' align=center> $no</td>
	<td align=center>$r[MhswID]</td>
	<td>&nbsp;$NamaMhs_AKecil</td>
	<td align=right >&nbsp;</td>
	<td align=right >&nbsp;</td>
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
</br>
</br>
</br>
</br>
<table border=0>
<tr>
<td colspan=5><font style='font-size:8px'>Login by: $_SESSION[id] ".tgl_indo(date('Y-m-d'))." ".date('H:i:s') . " WIB - STMIK HTP Support System</font></td>
</tr>
</table>";

	
} //session login
?>	