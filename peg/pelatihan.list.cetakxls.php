<?php 
session_start();
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
include "konfigurasi.mysql.php";
include "sambungandb.php";
include "pengembang.lib.php";
include "setting_awal.php";

if (empty($_SESSION['_Login']) && empty($_SESSION['_LevelID'])){
	header("Location: ../login.php");
}

else{
$namaFile = "$mhs[Nama]_".strfilter($_GET['MhswID'])."_PelatihanList.xls";
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
<td colspan='2'>NIM</td> <td>: $mhs[MhswID]</td>
</tr>

<tr>
<td colspan='2'>Nama </td> <td>: $mhs[Nama]</td>           
</tr>

<tr>
<td colspan='2'>Program/Prodi </td> <td>: $program[Nama] / $prodi[Nama]</td>             
</tr>
</table>
<br>
<table  border='1' align='center' cellspacing='0'>
<tr style='background-color:#E6E6E6'>
<th width='5' height='30' align='center'> No.</th>
<th class=ttl width=140px>Noreg</th>
    <th class=ttl width=200px>Nama</th>
    <th class=ttl width=350px>Nama Pelatihan</th>
    <th class=ttl width=150px>Pelaksana</th>
    <th class=ttl>Tanggal Mulai</th>
	<th class=ttl>Tanggal Selesai</th>
    <th class=ttl width=150px>Narasumber</th>
    <th class=ttl>Jenis Kegiatan</th>
	<th class=ttl>Tempat</th>
	<th class=ttl>Tahun</th>
</tr>";	
/*
$jenis = mysql_query( "SELECT * FROM jenismk where ProdiID='".strfilter($_GET[prodi])."'");  
while ($j = mysql_fetch_array($jenis)){
echo "<tr style='background-color:#E6E6E6'>
<td colspan='6' height='20' ><b>$j[Singkatan] ($j[Nama])</b></td>
</tr>"; 	
*/
//$sq = mysql_query( "SELECT * FROM vw_transkrip WHERE MhswID='".strfilter($_GET[MhswID])."' AND JenisMK='$j[Nama]' ORDER BY MKKode,substr(MKKode,0,4) ASC");
$sq = mysql_query($koneksi,  "select
    t_simpegpelatihan.*,
    dosen.Nama, dosen.Gelar
    from t_simpegpelatihan,dosen
    where t_simpegpelatihan.Noreg=dosen.Noreg
    order by t_simpegpelatihan.TanggalMulai Desc");
while($w=mysql_fetch_array($sq)){
$no++;
$Namax 		= strtolower($w['Nama']);
$Nama	    = ucwords($Namax);	      
echo "<tr><td $c>$no</td>
      <td $c>$w[Noreg]</td>
      <td $c>$Nama, $w[Gelar]</td>
      <td $c>$w[Judul]</td>
      <td $c>$w[Pelaksana]</td>
      <td $c>".FormatTanggal($w['TanggalMulai'])."&nbsp;</td>
	  <td $c>".FormatTanggal($w['TanggalSelesai'])."&nbsp;</td>
      <td $c>$w[NaraSumber]</td>
      <td $c>$w[JenisKegiatan]</td>
      <td $c>$w[Tempat]</td>
      <td $c>$w[TahunID]</td> 
      </tr>";
}
//} kategori
echo"</table>";
	
}

?>