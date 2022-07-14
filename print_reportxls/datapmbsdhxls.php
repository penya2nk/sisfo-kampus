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

$prodi    = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='$_GET[prodi]'"));
$panitia  = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama FROM karyawan where Login='$_SESSION[id]'"));
$Namax 	  = strtolower($panitia[Nama]);
$Panitia  = ucwords($Namax);

$namaFile = $_GET[prodi]."_PMBSudahRegistrasi_".$_GET[tahun]."_.xls";
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
<td colspan='8'><b><u>Data PMB Registrasi Ulang Prodi $_GET[prodi] - Tahun: $_GET[tahun]</u></b></td>
</tr>
</table>     
<br>

<table  width='80%' border='1' align='center' cellspacing='0'>";
$sqx = mysqli_query($koneksi, "SELECT * from t_gelombang order by PMBPeriodID asc"); //	AND pmb.ProgramID IN ('REG A')				
while($w=mysqli_fetch_array($sqx)){
echo"<tr style='text-align:left;font-size:18px;color:#FFFFFF;font-weight:reguler;background-color:#FF7213;'>
<th colspan='8'  height='20' align='left'>&nbsp;$w[PMBPeriodID] - $w[Nama]</th>
</tr>";

echo"<tr style='background-color:#E6E6E6' >
  <th width='30' height='20' align='center'> No.</th>
  <th width='80' align='center'>No. PMB</th>
  <th width='250' align='left'>&nbsp;Nama Peserta</th>
  <th width='450' align='left'>Tempat & TglLahir</th>
  <th width='50' align='left'>Program</th>
  <th width='50' align='center'>Prodi</th>
  <th width='50' align='left'>&nbsp;Handphone</th>
  <th width='50' align='left'>&nbsp;NIM</th>
  </tr>";
  $tampil = mysqli_query($koneksi, "SELECT
						pmb.PMBID,pmb.Nama,pmb.PMBPeriodID,pmb.PMBFormulirID,pmb.ProdiID,pmb.ProgramID,Handphone,NIM,TempatLahir,TanggalLahir
						FROM pmb
						WHERE LEFT(pmb.PMBPeriodID,4)='$_GET[tahun]' 
						AND ProdiID='$_GET[prodi]'
						AND RegUlang='Y'
						AND PMBPeriodID='$w[PMBPeriodID]'
						order by pmb.PMBID asc"); //	AND pmb.ProgramID IN ('REG A')				
  //$no=0;
  while($r=mysqli_fetch_array($tampil)){
  $prodi=mysqli_fetch_array(mysqli_query("select ProdiID,Nama from prodi WHERE ProdiID='$r[ProdiID]'"));	
  $no++;
  $Namax 	= strtolower($r[Nama]);
  $Nama 	= ucwords($Namax);
  $TempatLahirx = strtolower($r[TempatLahir]);
  $TempatLahir 	= ucwords($TempatLahirx);
  echo"<tr>
	<td height='20' align=center>$no</td>
	<td align=center>$r[PMBID]</td>
	<td align=left>&nbsp;$Nama</td>
	<td>$TempatLahir, ".tgl_indo($r[TanggalLahir])."</td>
	<td align=left>&nbsp;$r[ProgramID]</td>
	<td align=center>&nbsp;$r[ProdiID]</td>
	<td align=left>&nbsp;$r[Handphone]</td>
	<td align=left>&nbsp;$r[NIM]</td>
    </tr>";  
}  
}
echo"</table>";

	
} //session login
?>	