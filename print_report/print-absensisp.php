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

$filename="absensi_$_GET[JadwalID].pdf";
if (empty($_SESSION['_Login']) && empty($_SESSION['_LevelID'])){
	header("Location: ../login.php");
}

else{

include "headerx-rpt.php"; $content = ob_get_clean();

$prodi     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='".strfilter($_GET[prodi])."'"));
$mboh     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM vw_mengajarsp where JadwalID='".strfilter($_GET[JadwalID])."'"));

$Matakul = $mboh[NamaMK];
$Matakul_kecil 	= strtolower($Matakul);
$matKecil =ucwords($Matakul_kecil);


$content .= "

<table align='center'> 
<tr>
<td><b>ABSENSI KULIAH</b></td>
</tr>
</table>     
<br>

<table width='800' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td width='505'>$mboh[TahunID]</td>           
<td width='100'>$mboh[NamaHari]</td> 
</tr>

<tr>
<td>$prodi[Nama]</td>             
<td>$mboh[JamMulai]-$mboh[JamSelesai]</td> 
</tr>

<tr>
<td>$mboh[MKKode] - $matKecil - $mboh[NamaKelas] ($mboh[SKS] SKS) </td>             
<td>$mboh[NamaRuang]</td> 
</tr>


<tr>
<td>$mboh[Login] - $mboh[NamaDos]</td>             
<td>&nbsp;</td> 
</tr>

</table>
<br>
<table  width='800' border='0.3' align='center' cellspacing='0'>
<tr >
  <th width='30' rowspan='2' align='center'> NO.</th>
  <th width='80' rowspan='2' align='center'>NIM</th>
  <th width='300' rowspan='2' align='center'>NAMA MAHASISWA</th>
  <th height='30' colspan='4' align='center'>PERTEMUAN KE</th>
  </tr>
<tr >
<th width='60' height='30' align='center'>&nbsp;</th>
<th width='60' align='center'>&nbsp;</th>
<th width='60' align='center'>&nbsp;</th>
<th width='60' align='center'>&nbsp;</th>
</tr>";
        
$sq = mysqli_query($koneksi, "SELECT * from vw_krssp where JadwalID='".strfilter($_GET[JadwalID])."' order by MhswID Asc");
while($r=mysqli_fetch_array($sq)){
$no++;	       
$huruf			= $r[GradeNilai];
$NamaMhs 		= $r[NamaMhs];
$NamaMhs_kecil 	= strtolower($NamaMhs ); //strtoupper($kalimat);
$NamaMhs_AKecil	= ucwords($NamaMhs_kecil);
$content .= "<tr>
	<td height='15' align=center> $no</td>
	<td align=center>$r[MhswID]</td>
	<td>&nbsp;$NamaMhs_AKecil </td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>              
</tr>";
}
$content .= "
<tr>
	<td height='15' align=center></td>
	<td align=center></td>
	<td></td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>              
</tr>
<tr>
	<td height='15' align=center></td>
	<td align=center></td>
	<td></td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>              
</tr>
<tr>
	<td height='15' align=center></td>
	<td align=center></td>
	<td></td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>              
</tr>
<tr>
	<td height='15' align=center></td>
	<td align=center></td>
	<td></td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>              
</tr>
<tr>
	<td height='15' align=center></td>
	<td align=center></td>
	<td></td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>              
</tr>
<tr>
	<td height='15' colspan='3' align=right>Tanggal Perkuliahan &nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>              
</tr>
<tr>
	<td height='15' colspan='3' align=right>Paraf Dosen &nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>              
</tr>
</table>
<br>



<table border=0 width='800' align='center'>
<tr>
<td width='460' align='center'>&nbsp;</td>
<td width='367' align='left'>Pekanbaru, $tgl  <br>Program Studi Sistem Informasi<br> Ketua</td>

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
<td align='left'>$prodi[Pejabat]</td>
</tr>
</table>
<br><br>
<font style='font-size:8px'>Login by: $_SESSION[_Login]</font>";


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