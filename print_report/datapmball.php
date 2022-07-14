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

$content  = ob_get_clean();
$prodi    = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='$_GET[prodi]'"));
$panitia  = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama FROM karyawan where Login='$_SESSION[_Login]'"));
$Namax 	  = strtolower($panitia['Nama']);
$Panitia  = ucwords($Namax);

$content .= "

<table align='center'> 
<tr>
<td><b><u>Data PMB Tahun: $_GET[tahun]</u></b></td>
</tr>

</table>     
<br>

<table border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td width='100'></td><td></td><td width='500'></td> 
</tr>
</table>
<br>
<table  width='416' border='0.3' align='center' cellspacing='0'>";
$sqx = mysqli_query($koneksi, "SELECT * from t_gelombang order by PMBPeriodID asc"); //	AND pmb.ProgramID IN ('REG A')				
while($w=mysqli_fetch_array($sqx)){
$content .="<tr style='background-color:#E6E6E6'>
<th colspan='7'  height='20' align='left'>&nbsp;$w[PMBPeriodID] - $w[Nama]</th>
</tr>";

$content .="<tr style='background-color:#E6E6E6'>
  <th width='30' height='15' align='center'> No.</th>
  <th width='80' align='center'>No. PMB</th>
  <th width='250' align='left'>&nbsp;Nama Peserta</th>
  <th width='50' align='left'>Program</th>
  <th width='50' align='center'>Prodi</th>
  <th width='50' align='left'>&nbsp;Handphone</th>
  <th width='50' align='left'>&nbsp;NIM</th>
  </tr>";
  $tampil = mysqli_query($koneksi, "SELECT
						pmb.PMBID,pmb.Nama,pmb.PMBPeriodID,pmb.PMBFormulirID,pmb.ProdiID,pmb.ProgramID,Handphone,NIM
						FROM pmb
						WHERE LEFT(pmb.PMBPeriodID,4)='$_GET[tahun]' 
						AND pmb.PMBPeriodID='$w[PMBPeriodID]'
						order by pmb.PMBID asc"); //	AND pmb.ProgramID IN ('REG A')				
  //$no=0;
  while($r=mysqli_fetch_array($tampil)){
  $prodi=mysqli_fetch_array(mysqli_query("select ProdiID,Nama from prodi WHERE ProdiID='$r[ProdiID]'"));	
  $no++;
  $Namax 	= strtolower($r['Nama']);
  $Nama 	= ucwords($Namax);
  $content .="<tr>
	<td height='15' align=center>$no</td>
	<td align=center>$r[PMBID]</td>
	<td align=left>&nbsp;$Nama</td>
	<td align=left>&nbsp;$r[ProgramID]</td>
	<td align=center>&nbsp;$r[ProdiID]</td>
	<td align=left>&nbsp;$r[Handphone]</td>
	<td align=left>&nbsp;$r[NIM]</td>
    </tr>";  
}  
}
$content .="</table>
<br>



<table border=0 width='830' align='center'>
<tr>
<td width='500' align='center'>&nbsp;</td>
<td width='530' align='left'>Pekanbaru, $tgl  <br>Panitia PMB</td>
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
<td align='left'>$Panitia</td>
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
<td width='10'></td>
<td width='350'><font style='font-size:8px'>Login by: $_SESSION[_Login] ".tgl_indo(date('Y-m-d'))." ".date('H:i:s') . " WIB - Univ Tekno Indo CBT System</font></td>
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