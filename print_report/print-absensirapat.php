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
<td><b><u>DAFTAR HADIR $TemaX</u></b></td>
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
  <th width='100' align='center'>Noreg</th>
  <th width='200' align='center'>Nama</th>
  <th width='100' align='center'>Jabatan</th>
  <th width='100' align='center'>Tanda Tangan</th>
  </tr>";
$sql = mysqli_query($koneksi, "SELECT t_simpegpegawai.Nama,t_simpegpegawai.Jabatan,t_simpegpegawai.Gelar,t_simpegpegawai.Urut,
t_agendarapat.*, 
t_agendarapatpeserta.Noreg,t_agendarapatpeserta.IDX
FROM t_agendarapatpeserta, t_agendarapat,t_simpegpegawai
WHERE t_agendarapat.AgendaID=t_agendarapatpeserta.AgendaID
AND t_agendarapatpeserta.Noreg=t_simpegpegawai.Noreg
AND  t_agendarapat.AgendaID='".strfilter($_GET[AgendaID])."' order by t_simpegpegawai.Urut asc");
while($data=mysqli_fetch_array($sql)){
//$w=mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM t_simpegpegawai where Noreg='$data[Noreg]'  order by t_simpegpegawai.Urut desc"));
$no++;
$NamaDosX 		= strtolower($data[Nama]);
$NamaDosen 		= ucwords($NamaDosX);
$content .="<tr>
	<td align=center height='25'>$no</td>
	<td align=left>&nbsp; $data[Noreg]</td>
	<td >&nbsp; $NamaDosen, $data[Gelar]</td>
	<td >&nbsp; $data[Jabatan]</td>
	<td >&nbsp;</td>
  </tr>";
} 
$i=1;
while($i<=3){
$content .= "<tr><td align=center height='25'></td><td></td><td></td><td></td><td></td></tr>";
$i++;	
} 
$content .="</table>
<br>
<table border=0  align='center'>
<tr>
<td width='400' align='center'>&nbsp;</td><td width='200' align='left'>Pekanbaru, $tgl  <br>Universitas Teknokrat Indonesia<br>Ketua</td>
</tr>

<tr><td align='left'></td><td align='left'>&nbsp;</td></tr>
<tr><td align='left'></td><td align='left'>&nbsp;</td></tr>
<tr>
<td align='left'></td><td align='left'><u>Dr. Muhardi, S.Kom, M.Kom</u><br>Noreg. 1031230709130</td>
</tr>

</table>
<br>
<br>
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