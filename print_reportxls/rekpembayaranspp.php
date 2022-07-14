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

$program   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProgramID,Nama FROM program where ProgramID='$ProgramID'"));
$prodi     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='".strfilter($_GET[prodi])."'"));
/**/
$namaFile = "".date('d-m-Y')."-".$prodi[ProdiID]."-PembayaranSPP.xls";
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
<td colspan='2'>Program Studi </td> <td>: $_GET[tahun] - $prodi[Nama]</td>           
</tr>

</table>
<br>
<table  border='1' align='center' cellspacing='0'>
<tr style='background:purple;color:white'>
			<th style='width:40px'>No</th>
			<th style='width:80px'>NIM</th>
			<th style='width:200px'>Nama Mahasiswa</th>
			<th style='text-align:center' colspan='9'>Tahun Akademik</th>			
		  </tr>";	
		$angkatan = substr($_GET[tahun],2,2);//20191
		$tampil = mysqli_query($koneksi, "SELECT * from mhsw WHERE ProdiID='".strfilter($_GET[prodi])."' AND left(MhswID,2)='$angkatan' AND StatusMhswID IN ('A','P','C') order by MhswID asc");//
		$no = 1;
		while($r=mysqli_fetch_array($tampil)){
		$Namax 	    = strtolower($r[Nama]);
		$Nama 		= ucwords($Namax);
		echo "<tr><td>$no</td>
				  <td>$r[MhswID]</td>
				  <td>$Nama</td>";				  
				  $sqlb = mysqli_query("select * from keuangan_bayar where MhswID='$r[MhswID]' and id_jenis='SPP' and ProdiID='".strfilter($_GET[prodi])."' order by  TahunID asc");
				  while($row=mysqli_fetch_array($sqlb)){
				  echo"<td style='text-align:right'>$row[TahunID]<br>".number_format($row[total_bayar])."</td>";
				  }
				echo "</tr>";
		  $no++;
		  }
echo"</table><br>";
//echo"Total : ".number_format($total_bay,2,',','.')."";
	
}
?>