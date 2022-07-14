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
$mboh     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM vw_mengajar where JadwalID='".strfilter($_GET[JadwalID])."'"));
$prodi    = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='".strfilter($_GET[prodi])."'"));
$hari 	  = mysqli_fetch_array(mysqli_query($koneksi, "SELECT HariID,Nama FROM hari where HariID='$mboh[HariID]'"));
$ruang 	  = mysqli_fetch_array(mysqli_query($koneksi, "SELECT RuangID,Nama FROM ruang where RuangID='$mboh[RuangID]'"));

$NamaMKx 	= strtolower($mboh[NamaMK]);
$NamaMK 		= ucwords($NamaMKx);

$NamaDosX 		= strtolower($mboh[NamaDos]);
$NamaDosen 		= ucwords($NamaDosX);

$content .= "

<table align='center'> 
<tr>
<td><b><u>ABSENSI MENGAJAR DOSEN TA. $mboh[TahunID]</u></b></td>
</tr>
<tr>
<td align=center><b>Program Studi: $prodi[Nama]</b></td>
</tr>
</table>     
<br>

<table border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td width='100'>Dosen</td><td>:</td>             
<td width='500'>$mboh[Login] - $NamaDosen, $mboh[Gelar]</td> 
</tr>

<tr>
  <td width='100'>Matakuliah</td> <td>:</td>            
  <td width='500'>$mboh[MKKode] - $NamaMK ($mboh[SKS] SKS) - Smt $mboh[Sesi]</td> 
</tr>


<tr>
  <td width='100'>Kelas</td> <td>:</td>            
  <td width='500'>$mboh[NamaKelas]</td> 
</tr>
</table>
<br>
<table  width='416' border='0.3' align='center' cellspacing='0'>
<tr style='background-color:#E6E6E6'>
  <th width='30' height='40' align='center'> No.</th>
  <th width='80' align='center'>Pertemuan</th>
  <th width='100' align='center'>Tanggal</th>
  <th width='100' align='center'>Tanda Tangan</th>
  <th width='100' align='center'>Staf Prodi</th>
  <th width='200' align='center'>Keterangan</th>
  </tr>

<tr>
	<td height='20' align=center>1</td>
	<td align=center>I</td>
	<td align=right>&nbsp;</td>
	<td align=right>&nbsp;</td>
	<td align=right>&nbsp;</td>
	<td align=right>&nbsp;</td>
  </tr>
  
<tr>
	<td height='20' align=center>2</td>
	<td align=center>II</td>
	<td align=right>&nbsp;</td>
	<td align=right>&nbsp;</td>
	<td align=right>&nbsp;</td>
	<td align=right>&nbsp;</td>
  </tr>  

<tr>
	<td height='20' align=center>3</td>
	<td align=center>III</td>
	<td align=right>&nbsp;</td>
	<td align=right>&nbsp;</td>
	<td align=right>&nbsp;</td>
	<td align=right>&nbsp;</td>
  </tr>
<tr>
	<td height='20' align=center>4</td>
	<td align=center>IV</td>
	<td align=right>&nbsp;</td>
	<td align=right>&nbsp;</td>
	<td align=right>&nbsp;</td>
	<td align=right>&nbsp;</td>
  </tr>
<tr>
  <td height='20' align=center>5</td>
  <td align=center>V</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
</tr>
<tr>
  <td height='20' align=center>6</td>
  <td align=center>VI</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
</tr>
<tr>
  <td height='20' align=center>7</td>
  <td align=center>VII</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
</tr>
<tr>
  <td height='20' align=center>8</td>
  <td align=center>VIII</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
</tr>
<tr>
  <td height='20' align=center>9</td>
  <td align=center>IX</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
</tr>
<tr>
  <td height='20' align=center>10</td>
  <td align=center>X</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
</tr>
<tr>
  <td height='20' align=center>11</td>
  <td align=center>XI</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
</tr>
<tr>
  <td height='20' align=center>12</td>
  <td align=center>XII</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
</tr>
<tr>
  <td height='20' align=center>13</td>
  <td align=center>XIII</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
</tr>
<tr>
  <td height='20' align=center>14</td>
  <td align=center>XIV</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
</tr>
<tr>
  <td height='20' align=center>15</td>
  <td align=center>XV</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
</tr>
<tr>
  <td height='20' align=center>16</td>
  <td align=center>XVI</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
</tr>
<tr>
  <td height='20' align=center>17</td>
  <td align=center>XVII</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
</tr>
<tr>
  <td height='20' align=center>18</td>
  <td align=center>XVIII</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
  <td align=right>&nbsp;</td>
</tr>
</table>
<br>



<table border=0 width='830' align='center'>
<tr>
<td width='500' align='center'>&nbsp;</td>
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
<br>
<br>
<br>
<br>
<br>
<table border=0>
<tr>
<td width='50'></td>
<td width='300'><font style='font-size:8px'>Login by: $_SESSION[_Login] ".tgl_indo(date('Y-m-d'))." ".date('H:i:s') . " WIB - Univ Tekno Indo Support System</font></td>
</tr>
</table>";

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