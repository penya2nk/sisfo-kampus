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


//$namaFile = "".date('d-m-Y')."-".$_GET[tahun]."-".$prodi[ProdiID]."-PembayaranKuliah.xls";
$namaFile = "".date('d-m-Y')."-".$prodi[ProdiID]."-PembayaranKuliah.xls";
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
<td colspan='2'>Program Studi </td> <td>: $prodi[Nama]</td>           
</tr>

</table>
<br>
<table  border='1' align='center' cellspacing='0'>
<tr>
<th width='5' height='30' align='center'> NO.</th>
<th width='80' align='center'>JENIS PEMBAYARAN</th>
<th width='800' align='center'>JUMLAH NOMINAL</th>
<th width='100' align='center'>Tahun</th>
<th width='800' align='center'>KETERANGAN</th>
</tr>";	
$qrs = mysqli_query($koneksi, "SELECT MhswID,Nama,ProdiID,ProgramID,Alamat from mhsw Where ProdiID='".strfilter($_GET[prodi])."' AND StatusMhswID='A' order by MhswID desc");//and ProgramID like '%$_GET[program]%'
while ($h = mysqli_fetch_array($qrs)){
$hd++;
echo"<tr style='background-color:#DAF896'>
<td colspan='5' ><b>&nbsp;$hd. $h[MhswID] - $h[Nama] ($h[ProgramID]) </b></td>
</tr>"; 	
	$sq = mysqli_query($koneksi, "SELECT id_jenis,MhswID,total_bayar,ProdiID,TahunID from view_keu 
	WHERE MhswID='$h[MhswID]' 
	AND ProdiID='$_GET[prodi]' 
	AND StatusMhswID='A' order by TahunID Desc");
	$no = 1;
	while($r=mysqli_fetch_array($sq)){
echo"<tr>
	<td  align=center> $no</td>
	<td align=left>$r[id_jenis]</td>
	<td align=right >".number_format($r[total_bayar],2,',','.')."</td>  
	<td align=center>$r[TahunID]</td> 
  <td align=center>$r[keterangan]</td>
       
</tr>";
$no++;
$total_bayar = $r[total_bayar];
$total_bay += $total_bayar;
}
}
echo"</table><br>";
echo"Total : ".number_format($total_bay,2,',','.')."";
	
}
?>