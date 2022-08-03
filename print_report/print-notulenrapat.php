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

if (empty($_SESSION['_Login']) && empty($_SESSION['_LevelID'])){
	header("Location: ../login.php");
}
else{

include "headerx-rpt.php"; 	
$r     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM t_agendarapat where AgendaID='".strfilter($_GET[AgendaID])."'"));
$TemaX 		= strtoupper($r[TemaRapat]);
//$TemaRapat 		= ucwords($TemaX);
$tanggal = $r[Tanggal];
//$day = date('D', strtotime($tanggal));
$day = date('D', strtotime($tanggal));
$dayList = array(
	'Sun' => 'Minggu',
	'Mon' => 'Senin',
	'Tue' => 'Selasa',
	'Wed' => 'Rabu',
	'Thu' => 'Kamis',
	'Fri' => 'Jumat',
	'Sat' => 'Sabtu'
);
$content .= "

<table align='center'> 
<tr>
<td><b><u>NOTULEN RAPAT</u></b></td>
</tr>

</table>     
<br>
<br>
<table border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td width='100'>Hari/Tanggal</td><td>:</td>             
<td width='500'>$dayList[$day], ".tgl_indo($r[Tanggal])."</td> 
</tr>

<tr>
  <td width='100'>Waktu</td> <td>:</td>            
  <td width='500'>$r[JamMulai] s/d $r[JamSelesai] WIB</td> 
</tr>


<tr>
  <td width='100'>Ruangan</td> <td>:</td>            
  <td width='500'>$r[Tempat]</td> 
</tr>

<tr>
  <td width='100'>Agenda</td> <td>:</td>            
  <td width='500'>$r[TemaRapat]</td> 
</tr>

</table>
<br>
<table  width='416' border='0.3' align='center' cellspacing='0'>
<tr style='background-color:#E6E6E6'>
<th width='30' height='30' align='center'> No.</th>
<th width='600' align='left'>&nbsp;Catatan</th>
</tr>";
$i=1;
while($i<=25){
$content .= "<tr>
<th width='30' height='20' align='center'> $i.</th>
<th width='600' align='left'></th>
</tr>";
$i++;	
}

$content .= "</table>

<br>
<table border=0  align='center'>
<tr>
<td width='400' align='center'>&nbsp;</td><td width='200' align='left'>Pekanbaru, $tgl  <br>Universitas Teknokrat Indonesia<br>Ketua</td>
</tr>

<tr><td align='left'></td><td align='left'>&nbsp;</td></tr>
<tr><td align='left'></td><td align='left'>&nbsp;</td></tr>
<tr>
<td align='left'></td><td align='left'><u>Hendry Fonda, S.Kom, M.Kom</u><br>Noreg. 1031230808001</td>
</tr>

</table>
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