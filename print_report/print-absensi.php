<?php 
session_start();
error_reporting(0);
include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
require_once ("../punksi/html2pdf/vendor/autoload.php");
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
$tgl= tgl_indo(date('Y-m-d'));

$filename="absensi_$_GET[JadwalID].pdf";
if (empty($_SESSION['_Login']) && empty($_SESSION['_LevelID'])){
	header("Location: ../login.php");
}

else{


include "headerx-rpt.php"; $content = ob_get_clean();
$mboh     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM vw_mengajar where JadwalID='".strfilter($_GET['JadwalID'])."'"));

$prodi    = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='".strfilter($_GET['prodi'])."'"));
$hari 	  = mysqli_fetch_array(mysqli_query($koneksi, "SELECT HariID,Nama FROM hari where HariID='$mboh[HariID]'"));
$ruang 	  = mysqli_fetch_array(mysqli_query($koneksi, "SELECT RuangID,Nama FROM ruang where RuangID='$mboh[RuangID]'"));

$Matakul  = $mboh['NamaMK'];
$Matakul_kecil 	= strtolower($Matakul);
$matKecil =ucwords($Matakul_kecil);

$content .= "
<table align='center'> 
<tr>
<td><b>ABSENSI KULIAH</b></td>
</tr>
</table>     
<br>

<table width='1408' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td width='750'>$mboh[TahunID]</td>           
<td width='100'>$hari[Nama]</td> 
</tr>

<tr>
<td>$prodi[Nama]</td>             
<td>".substr($mboh['JamMulai'],0,5)." - ".substr($mboh['JamSelesai'],0,5)." WIB</td> 
</tr>

<tr>
<td>$mboh[MKKode] - $matKecil ($mboh[SKS] SKS) - $mboh[NamaKelas]  - Semester $mboh[Sesi]</td>             
<td>$ruang[Nama]</td> 
</tr>


<tr>
<td>$mboh[Login] - $mboh[NamaDos], $mboh[Gelar]</td>             
<td>&nbsp;</td> 
</tr>

</table>
<br>
<table  width='1408' border='0.3' align='center' cellspacing='0'>
<tr >
  <th width='30' rowspan='2' align='center'> NO.</th>
  <th width='80' rowspan='2' align='center'>NIM</th>
  <th width='200' rowspan='2' align='center'>NAMA MAHASISWA</th>
  <th height='20' colspan='16' align='center'>PERTEMUAN KE</th>
  </tr>
<tr >
  <th width='40' align='center'>1</th>
  <th width='40' align='center'>2</th>
<th width='40' height='20' align='center'>3</th>
<th width='40' align='center'>4</th>
<th width='40' align='center'>5</th>
<th width='40' align='center'>6</th>
<th width='40' align='center'>7</th>
<th width='40' align='center'>8</th>
<th width='40' align='center'>9</th>
<th width='40' align='center'>10</th>
<th width='40' align='center'>11</th>
<th width='40' align='center'>12</th>
<th width='40' align='center'>13</th>
<th width='40' align='center'>14</th>
<th width='40' align='center'>15</th>
<th width='40' align='center'>16</th>
</tr>";
        
$sq = mysqli_query($koneksi, "SELECT krs.*, jadwal.Kehadiran,
	jadwal.MKKode, jadwal.Nama AS NamaMK, jadwal.NamaKelas, jadwal.JenisJadwalID,jadwal.TahunID,
	mhsw.Nama as NamaMhs
	FROM krs
	LEFT OUTER JOIN jadwal ON krs.JadwalID=jadwal.JadwalID			
	LEFT OUTER JOIN mhsw ON mhsw.MhswID=krs.MhswID
	WHERE krs.JadwalID='".strfilter($_GET['JadwalID'])."'
	AND jadwal.TahunID='".strfilter($_GET['tahun'])."'
	GROUP BY krs.JadwalID,krs.MhswID order by krs.MhswID asc");
while($r=mysqli_fetch_array($sq)){
$no++;	       
$huruf= $r['GradeNilai'];
$NamaMhs 		= $r['NamaMhs'];
$NamaMhs_kecil 	= strtolower($NamaMhs ); //strtoupper($kalimat);
$NamaMhs_AKecil	= ucwords($NamaMhs_kecil);
$content .= "<tr>
	<td height='20' align=center> $no</td>
	<td align=center>$r[MhswID]</td>
	<td>&nbsp;$NamaMhs_AKecil </td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
    </tr>";
}

for ($i=0; $i <= 10; $i++){
$content .= "
<tr>
	<td height='20' align=center></td>
	<td align=center></td>
	<td></td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>              
</tr>";
}
$content .="
<tr>
	<td height='20' align=center></td>
	<td align=center></td>
	<td align=right>Tanggal&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>              
</tr>
<tr>
	<td height='20' align=center></td>
	<td align=center></td>
	<td align=right>Paraf Dosen&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>              
</tr>
</table>
<br>



<table border=0 width='830' align='center'>
<tr>
<td width='700' align='center'>&nbsp;</td>
<td width='530' align='left'>Pekanbaru, $tgl  <br>Ketua Program Studi</td>
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
<br>
<br>
<br>
<br>
<table border=0>
<tr>
<td width='0'></td>
<td width='300'><font style='font-size:8px'>Login by: $_SESSION[_Login] ".tgl_indo(date('Y-m-d'))." ".date('H:i:s') . " WIB - Univ Tekno Indo Support System</font></td>
</tr>
</table>";

try {
	ob_start();
	$html2pdf = new Html2Pdf('P','Legal','fr', true, 'UTF-8', array(15, 15, 15, 15), false); 
	$html2pdf->writeHTML($content);
	$html2pdf->output();
  } catch (Html2PdfException $e) {
	$html2pdf->clean();
  
	$formatter = new ExceptionFormatter($e);
	echo $formatter->getHtmlMessage();
  }
  
  }	
  ?>	