<?php 
session_start();
error_reporting(0);
include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
require ("../punksi/html2pdf/html2pdf.class.php");
$filename="namafile.pdf";



if (empty($_SESSION['_Login']) && empty($_SESSION['_LevelID'])){
	header("Location: ../login.php");
}
else{

include "headerx-rpt.php"; $content = ob_get_clean();
$prodi = mysqli_fetch_array(mysqli_query($koneksi, "select ProdiID,Nama,Pejabat,Gelar from prodi where ProdiID='".strfilter($_GET['prodi'])."'"));
$content .= "<table width='100%' cellspacing='20'><tr>";
$no = 1; 
$query = mysqli_query($koneksi, "select * from vw_jadwal where TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."' and NamaHari='".strfilter($_GET[hari])."'");
while($dta = mysqli_fetch_array($query)){
  $ProdiID = $dta['ProdiID'];	
 
   $nama1 			= $dta['NamaMK'];
   $nama_kecil1 	= strtolower($nama1);
   $mk				= ucwords($nama_kecil1);
   
   $namad 			= $dta['NamaDosen'];
   $nama_dos1 		= strtolower($namad);
   $dosen			= ucwords($nama_dos1);
		
   $content .= "<td class='box' width='335'>

<table width='100%' style='width: 330px' cellspacing='0' border='0.1'>
   
<tr class='header' style='background-color:#E6E6E6'>
   <td width='60' align='center'>
      <img src='logo_uti.png' width='50'>
   </td>
   <td width='130' align='center' valign='middle' style='padding: 5px 30px;'>
   <b>UJIAN TENGAH SEMESTER (UTS) <br> TA $dta[TahunID] <br> <font style='font-size:8px'>Powered by: Univ Tekno Indo Support System </font></b>
   </td>
</tr>

<tr><td height='20' width='150'>&nbsp;Kode</td><td width='300'>&nbsp; $dta[MKKode]</td></tr>				
<tr><td height='20'>&nbsp;Matakuliah</td><td>&nbsp; $mk</td></tr>
<tr><td height='20'>&nbsp;Dosen Pengampu</td><td>&nbsp; $dosen, $dta[Gelar]</td></tr>
<tr><td height='20'>&nbsp;Semester</td><td>&nbsp; $dta[Sesi] - $prodi[Nama]</td></tr>
<tr><td height='20'>&nbsp;Kelas / Ruang</td><td>&nbsp; $dta[NamaKelas] / $dta[NamaRuang]</td></tr>
<tr><td height='20'>&nbsp;Waktu</td><td>&nbsp; $dta[NamaHari], ".substr($dta['JamMulai'],0,5)." WIB s/d ".substr($dta['JamSelesai'],0,5)." WIB</td></tr>
</table>
</td>";

  if($no%2==0) $content .= "</tr><tr>";
  $no++;

}
$content .= "</tr></table>";
try
	{
		$html2pdf = new HTML2PDF('L','Letter','en', false, 'ISO-8859-15',array(10, 10, 15, 10)); //setting ukuran kertas dan margin pada dokumen anda
		// $html2pdf->setModeDebug();
		$html2pdf->setDefaultFont('Arial');
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output($filename);
	}
	catch(HTML2PDF_exception $e) { echo $e; }
	
}
?>	