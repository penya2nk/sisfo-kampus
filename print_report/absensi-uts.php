<?php 
session_start();
error_reporting(0);
include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
require ("../punksi/html2pdf/html2pdf.class.php");
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
/*
$namaFile = "$mboh[NamaDos]_".$matKecil."_AbsensiUAS.xls";
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=$namaFile");
header("Content-Transfer-Encoding: binary ");
*/
$content .= "

<table align='center'> 
<tr>
<td align=center><b><u>DAFTAR HADIR UJIAN TENGAH SEMESTER TAHUN $_GET[tahun]</u></b></td>
</tr>
<tr>
<td align=center><b>Program Studi: $prodi[Nama]</b></td>
</tr>
</table>     
<br>

<table border='0'  align='center'>        
<tr><td >Matakuliah / Semester</td><td>: $mboh[MKKode] - $matKecil ($mboh[SKS] SKS) / $mboh[Sesi]</td></tr>
<tr><td >Dosen Pengampu</td><td>: $mboh[Login] - $NamaDosen, $mboh[Gelar]</td></tr>
<tr><td >Waktu / Ruang</td><td>: $hari[Nama], ".tgl_indo($mboh[UTSTanggal]).", ".substr($mboh[JamMulai],0,5)." - ".substr($mboh[JamSelesai],0,5)." WIB / $ruang[Nama]</td> </tr>
<tr><td >Kelas</td><td>: $mboh[NamaKelas]</td></tr>
</table>
<br>
<table border='0.3' align='center' cellspacing='0'>
<tr style='background-color:#E6E6E6'>
  <td height='30' align='center'> NO.</td>
  <td width='80' align='center'>&nbsp;NIM</td>
  <td width='250' align='left' >&nbsp;NAMA MAHASISWA</td>
  <td width='100' align='center'>TANDA TANGAN</td>
  <td  width='150' align='center'>KETERANGAN</td>
</tr>";
        
$sq = mysqli_query($koneksi, "SELECT * from vw_krs where JadwalID='".strfilter($_GET[JadwalID])."' order by MhswID Asc");
while($r=mysqli_fetch_array($sq)){
$no++;	       
//$huruf= $r[GradeNilai];
$NamaMhs 		= $r[NamaMhs];
$NamaMhs_kecil 	= strtolower($NamaMhs ); //strtoupper($kalimat);
$NamaMhs_AKecil	= ucwords($NamaMhs_kecil);
$content.="<tr>
	<td height='15' align=center> $no</td>
	<td align=center>$r[MhswID]</td>
	<td>&nbsp;$NamaMhs_AKecil</td>
	<td align=right >&nbsp;</td>
	<td align=right >&nbsp;</td>
    </tr>";
}

$content.="
</table>
<br>
<table border=0 width='800px' align='center'>
<tr>
<td width='100' align='center'>&nbsp;</td>
<td width='130' align='left'>&nbsp;</td>
<td width='130' align='left'>&nbsp;</td>
<td colspan='3' align='left'>Pekanbaru, $tgl <br>
  Ketua Program Studi</td>
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
<br>
<br>
<br>
<br>
<table border=0>
<tr>
<td colspan=5><font style='font-size:8px'>Login by: $_SESSION[_Login] ".tgl_indo(date('Y-m-d'))." ".date('H:i:s') . " WIB - Univ Tekno Indo Support System</font></td>
</tr>
</table>";

	
try
	{
		$html2pdf = new HTML2PDF('P','Letter','en', false, 'ISO-8859-15',array(10, 5, 10, 5)); //setting ukuran kertas dan margin pada dokumen anda
		// $html2pdf->setModeDebug();
		$html2pdf->setDefaultFont('Arial');
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output($filename);
	}
	catch(HTML2PDF_exception $e) { echo $e; }
	
} //session login
?>	