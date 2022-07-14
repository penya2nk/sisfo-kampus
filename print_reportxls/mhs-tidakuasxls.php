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
echo"<table align='center'> 
<tr>
<td colspan=5><b>DAFTAR MAHASISWA TIDAK BISA MENGIKUTI UJIAN AKHIR SEMESTER</b></td>
</tr>
</table>     
<br>

<table border='0' width='800px'  align='center'>
<tr><td colspan=2>Tahun Akademik</td><td colspan=4>: $mboh[TahunID]</td> </tr>          
<tr><td colspan=2>Waktu / Ruang</td><td colspan=4>: $hari[Nama], $mboh[JamMulai]-$mboh[JamSelesai] WIB / $ruang[Nama]</td> </tr>
<tr><td colspan=2>Program Studi</td><td colspan=4>: $prodi[Nama]</td></tr>             
<tr><td colspan=2>Matakuliah</td><td colspan=4>: $mboh[MKKode] - $matKecil ($mboh[SKS] SKS) - $mboh[NamaKelas]  - Semester $mboh[Sesi]</td></tr>
<tr><td colspan=2>Dosen Pengampu</td><td colspan=4>: $mboh[Login] - $NamaDosen, $mboh[Gelar]</td></tr>
</table>
<br>
<table  width='800px' border='1' align='center' cellspacing='0'>
<tr >
  <td width='15'  align='center'> NO.</td>
  <td width='80px'  align='center'>NIM</td>
  <td width='150'  align='left' >&nbsp;NAMA MAHASISWA</td>
   <td height='40' width='10' align='center'>KEHADIRAN</td>
  <td height='40' width='20' align='center'>TANDA TANGAN</td>
  <td height='40' width='80px' align='center'>KETERANGAN</td>
  </tr>
</tr>";
        
$sq = mysqli_query($koneksi, "SELECT presensimhsw.*, jadwal.Kehadiran,
    jadwal.MKKode, jadwal.Nama AS NamaMK, jadwal.NamaKelas, jadwal.JenisJadwalID,jadwal.TahunID,
    SUM(presensimhsw.Nilai) AS JML,
	mhsw.Nama as NamaMhs
    FROM presensimhsw
    LEFT OUTER JOIN jadwal ON presensimhsw.JadwalID=jadwal.JadwalID			
	LEFT OUTER JOIN mhsw ON mhsw.MhswID=presensimhsw.MhswID
	WHERE presensimhsw.JadwalID='".strfilter($_GET[JadwalID])."'
	AND jadwal.TahunID='".strfilter($_GET[tahun])."'
    GROUP BY presensimhsw.JadwalID,presensimhsw.MhswID order by presensimhsw.MhswID asc");
while($r=mysqli_fetch_array($sq)){
$persentase= number_format(($r[JML]/$r[Kehadiran])* 100,2);
$no++;	       
//$huruf= $r[GradeNilai];
$NamaMhs 		= $r[NamaMhs];
$NamaMhs_kecil 	= strtolower($NamaMhs ); //strtoupper($kalimat);
$NamaMhs_AKecil	= ucwords($NamaMhs_kecil);
echo"<tr>
	<td height='30' align=center> $no</td>
	<td align=center>$r[MhswID]</td>
	<td>&nbsp;$NamaMhs_AKecil</td>
	<td align='center'>$persentase %</td>
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
<td width='230' align='left'>Pekanbaru, $tgl  <br>Ketua Program Studi</td>
</tr>

<tr>
  <td align='left'></td>
  <td align='left' >&nbsp;</td>
</tr>

<tr>
  <td align='left'></td>
  <td align='left' >&nbsp;</td>
</tr>



<tr>
<td align='left'></td>
<td align='left' >$prodi[Pejabat]</td>
</tr>
</table>
";

	
} //session login
?>	