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


$namaFile = "$prodi[Nama]_".$_GET[tahun]."_Jadwal_Kuliah.xls";
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
<td colspan='2'>Jadwal Tahun</td> <td>: $_GET[tahun]</td>
</tr>

<tr>
<td colspan='2'>Program Studi </td> <td>: $prodi[Nama]</td>           
</tr>

</table>
<br>
<table border='1' align='center' cellspacing='0'>
          <thead>
            <tr>
              <th>No</th>             
              <th>Waktu</th>
              <th >Ruang</th>
               <th>Nama Dosen</th>
			  <th>Kode MK</th>
              <th>Matakuliah</th>
              <th style='width:80px'>Kelas</th>
              <th>SKS</th>
            </tr>
          </thead>
          <tbody>";
			  $hari = mysqli_query($koneksi, "SELECT * from hari WHERE Nama NOT IN ('Minggu','') order by HariID asc");
			  while ($h = mysqli_fetch_array($hari)){
			  echo"<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>
				 <td colspan='8' ><b>&nbsp; $h[Nama]</b></td>
				 </tr>";  

		      $tampil = mysqli_query($koneksi, "SELECT * from vw_jadwal where NamaHari='$h[Nama]' AND TahunID='".strfilter($_GET[tahun])."' and ProdiID='".strfilter($_GET[prodi])."'");
         
		  $no = 1;
          while($r=mysqli_fetch_array($tampil)){
			  $NamaDosenx 	= strtolower($r[NamaDosen]);
			  $NamaDosen 	= ucwords($NamaDosenx);
			  $NamaMKx 	    = strtolower($r[NamaMK]);
			  $NamaMK 		= ucwords($NamaMKx);
          echo "<tr><td>$no</td>
                  
                    <td>".substr($r[JamMulai],0,5)." - ".substr($r[JamSelesai],0,5)."</td>
                    <td align='center'>$r[NamaRuang]</td>
					<td>$NamaDosen, $r[Gelar]</td>
                    <td>$r[MKKode]</td>
                    <td>$NamaMK</td>
                    <td>$r[NamaKelas]</td>
                    <td>$r[SKS]</td>
		  ";
                            

echo "</tr>";
	  $no++;
	  }

}//hari

  ?>
	<tbody>
  </table>
<?php
 
}
?>