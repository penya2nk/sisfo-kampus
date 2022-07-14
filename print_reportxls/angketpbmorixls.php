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

//$filename="angket_$_GET[SpID].pdf";
if (empty($_SESSION['_Login']) && empty($_SESSION['_LevelID'])){
	header("Location: ../login.php");
}
else{
$jdw 	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT JadwalID,DosenID,MKID,TahunID,NamaKelas FROM jadwal WHERE JadwalID='".strfilter($_GET[JadwalID])."'"));
$dos 	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar,Handphone FROM dosen WHERE Login='$jdw[DosenID]'"));
$mk 	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT MKID,MKKode,Nama,SKS,Sesi FROM mk WHERE MKID='$jdw[MKID]'"));
$prd 	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama FROM prodi WHERE ProdiID='$jdw[prodi]'"));

$namaFile = "$dos[Nama]_".$mk[Nama]."_".$jdw[TahunID]."_AngketPBM.xls";
/**/
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=$namaFile");
header("Content-Transfer-Encoding: binary ");

echo"<table align='center' width='800px'> 
<tr style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>
<td colspan=12 align=center><b>ANGKET PENILAIAN DOSEN</b></td>
</tr>
</table>     
<br>

<table border='0' width='800px'  align='center'>
<tr ><td colspan=2 width='20px'>Dosen Pengampu</td><td colspan=11>: $dos[Nama], $dos[Gelar]</td> </tr> 
<tr><td colspan=2>Matakuliah</td><td colspan=11>: $mk[Nama] ($mk[SKS] SKS) - Semester: $mk[Sesi]</td> </tr> 
<tr><td colspan=2>Kelas</td><td colspan=11>: $jdw[NamaKelas]</td> </tr>   
<tr><td colspan=2>Tahun Akademik</td><td colspan=11>: $jdw[TahunID]</td> </tr>  
<tr><td colspan=2></td><td colspan=11></td> </tr>       
</table>

<table  width='800px' border='1' align='center' cellspacing='0'>
<tr style='background-color:#E6E6E6'>
<td align='center' width='80px'>JadwalID</td>
<td align='center' width=80px>MhswID</td>
<td align='center' width=80px>PertanyaanID</td>
<td align='center' width=80px>KategoriID</td>
<td align='center' width=80px>Jawaban</td>
<td align='center' width=80px>TahunID</td>
<td align='center' width=80px>ProdiID</td>
<td align='center' width=80px>TanggalBuat</td>
<td align='center' width=80px>Keterangan</td>
<td align='center' width=80px>NA</td>

</tr>";
        
$sql = mysqli_query($koneksi, "SELECT * from krs WHERE JadwalID='".strfilter($_GET[JadwalID])."' order by MhswID asc");
while($r=mysqli_fetch_array($sql)){
$no++;	       
echo"<tr>
<td align='center'>$r[JadwalID]</td>
<td align='center'>$r[MhswID]</td>
<td align='center'>$r[PertanyaanID]</td>
<td align='center'>$p3[KategoriID]</td>
<td align='center'>$p4[Jawaban]</td>
<td align='center'>$p5[TahunID]</td>
<td align='center'>$p6[ProdiID]</td>
<td align='center'></td>
<td align='center'></td>
<td align='center'></td>

</tr>";
}
echo"</table>";

} //session login
?>	