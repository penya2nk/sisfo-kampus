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


if (empty($_SESSION['_Login']) && empty($_SESSION['_LevelID'])){
	header("Location: ../login.php");
}

else{



// $prodi    = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='$_GET[prodi]'"));
// $panitia  = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama FROM karyawan where Login='$_SESSION[id]'"));
// $Namax 	  = strtolower($panitia[Nama]);
// $Panitia  = ucwords($Namax);

$namaFile = "lapbkddosen_".$_GET[tahun]."_.xls";
/**/
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
<td colspan=9><b><u>Laporan Kerja Dosen Prodi $_GET[prodi] Tahun $_GET[tahun] </u></b></td>
</tr>

</table>     
<br>

<table  width='80%' border='1' align='center' cellspacing='0'>";
echo"<tr>
<th style='width:40px'>No</th>
<th style='width:80px'>NIDN/DosenID</th>
<th style='width:200px'>Nama Dosen</th>
<th style='text-align:center'>Mengajar (SKS)</th>
<th style='text-align:center'>Pembimbing KP</th>
<th style='text-align:center'>Pembimbing Skripsi </th>
			
</tr>";
$angkatan = substr($_GET[tahun],2,2);//20191
		$prodix = ".".$_GET['prodi'].".";
		$tampil = mysqli_query($koneksi, "SELECT * from dosen WHERE NA='N' AND Noreg NOT IN ('-')");//		AND keuangan_bayar.TahunID='".strfilter($_GET[tahun])."' 
		$no = 1;
		
		while($r=mysqli_fetch_array($tampil)){
        //$jmlmk		=mysqli_num_rows(mysqli_query("select * from krs where MhswID='$r[MhswID]'"));
		$tsks		=mysqli_fetch_array(mysqli_query("select sum(SKS)as tSKS from jadwal 
																	where DosenID='$r[Login]'
																	and TahunID='".strfilter($_GET[tahun])."'
																	and MKID NOT IN('1150','1154','1085','1081')
																	and ProdiID='$prodix'"));
		$jmlKP		= mysqli_num_rows(mysqli_query("select * from jadwal_kp 
															  where DosenID='$r[Login]'
														      and ProdiID='".strfilter($_GET[prodi])."' 
															  and TahunID='".strfilter($_GET[tahun])."'"));
		$jmlUtama	= mysqli_num_rows(mysqli_query("select * from jadwal_skripsi 
															  where PembimbingSkripsi1='$r[Login]'
														      and ProdiID='".strfilter($_GET[prodi])."' 
															  and TahunID='".strfilter($_GET[tahun])."'"));
		$jmlPendamping	= mysqli_num_rows(mysqli_query("select * from jadwal_skripsi 
															  where PembimbingSkripsi2='$r[Login]'
														      and ProdiID='".strfilter($_GET[prodi])."' 
															  and TahunID='".strfilter($_GET[tahun])."'"));
		$jmlBimbing = $jmlUtama + $jmlPendamping;													  														
		$Namax 	    = strtolower($r[Nama]);
		$Nama 		= ucwords($Namax);
		echo "<tr ><td align='center'>$no</td>
				  <td>$r[Login]</td>
				  <td>$Nama, $r[Gelar]</td>
				  <td align=center>$tsks[tSKS]</td>
				  <td align=center>$jmlKP</td>
				  <td align=center>$jmlBimbing</td>
				 
		</tr>";
		  $no++;
		  }
echo"</table>
<br>";

	
} //session login
?>	