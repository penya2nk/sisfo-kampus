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
$sekrewisuda  = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM karyawan where Jabatan='SekreWisuda' and LevelID='7'"));
$mhs       = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,ProgramID,MhswID,Nama FROM mhsw where MhswID='".strfilter($_GET[MhswID])."'"));
$ProgramID = $mhs[ProgramID];
$ProdiID   = $mhs[ProdiID];

$program   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProgramID,Nama FROM program where ProgramID='$ProgramID'"));
$prodi     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='$ProdiID'"));

$content .= "<table width='800' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td width='8%' rowspan='4'><img width='80' width='80' src='logo_uti.png'></td>       
<td width='84%' align='center' ><strong>SEKOLAH TINGGI MANAJEMEN INFORMATIKA DAN KOMPUTER</strong></td>           
<td width='8%' rowspan='4'>&nbsp;</td> 
</tr>

<tr>
<td align='center' ><font style='font-size:25px'><strong>(STMIK) HANG TUAH PEKANBARU</strong></font></td>
</tr>

<tr>
<td align='center' >Jl. ZA. Pagar Alam No.9 -11, Labuhan Ratu, Kec. Kedaton, Kota Bandar Lampung, Lampung 35132</td>
</tr>

<tr>
<td align='center' >Email: stmikhtp@yahoo.co.id, Website: http://www.stmikhtp.ac.id</td>
</tr>
<tr>

<td colspan='3'><hr></td>            
</tr>
</table><br>

<table align='center'> 
<tr>
<td><b>PELAPORAN WISUDA UNIVERSITAS TEKNOKRAT INDONESIA</b></td>
</tr>
</table>     
<br>
<br>


<table width='800' border='0' cellpadding='0' cellspacing='0' align='center'>

<tr>
<td>Tahun </td> <td>: $_GET[tahun]</td>
</tr>
</table>
<br>
<table  width='800' border='0.3' align='center' cellspacing='0'>
<tr >
<th width='30' height='30' align='center'> NO.</th>
<th width='80' align='center'>&nbsp;ProdiID</th>
<th width='200' align='left'>&nbsp;Nama Program Studi</th>
<th width='80' align='center'>&nbsp;Jumlah Calon<br>Wisudawan</th>
<th width='80' align='center'>&nbsp;Lunas <br>/ Berkas Lengkap</th>
<th width='80' align='center'>&nbsp;Blm Lunas <br>/ Berkas Blm Lengkap</th>
<th width='80' align='center'>&nbsp;Persentase <br> Sdh Lunas</th>
</tr>";
        
$sq = mysqli_query($koneksi, "SELECT * from prodi order by Nama asc");
while($r=mysqli_fetch_array($sq)){
$all = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(*) as jmlAll,ProdiID,TahunID 
								  FROM t_yudisium 
								  WHERE  ProdiID='$r[ProdiID]' 
								  AND TahunID='".strfilter($_GET[tahun])."'"));	
$lunas = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(*) as jmlLunas,ProdiID,TahunID 
								  FROM t_yudisium 
								  WHERE  ProdiID='$r[ProdiID]' 
								  AND Status='Lunas'
								  AND TahunID='".strfilter($_GET[tahun])."'"));	
$blmLunas = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(*) as jmlBlmLunas,ProdiID,TahunID 
								  FROM t_yudisium 
								  WHERE  ProdiID='$r[ProdiID]' 
								  AND Status<>'Lunas'
								  AND TahunID='".strfilter($_GET[tahun])."'")); 
							//Lunas
$tot = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(*) as jml,ProdiID,TahunID 
								  FROM t_yudisium 
								  WHERE  ProdiID='$r[ProdiID]' 
								  AND Status='Lunas'
								  AND TahunID='".strfilter($_GET[tahun])."'"));
$grandtot	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(*) as jGrandTot,ProdiID,TahunID 
								  FROM t_yudisium
								  WHERE TahunID='".strfilter($_GET[tahun])."'"));
								  
$tuntas = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(*) as jmlTuntas,TahunID 
										 FROM t_yudisium WHERE Status='Lunas'
										 AND TahunID='".strfilter($_GET[tahun])."'"));
$btuntas = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(*) as jmlBTuntas,TahunID 
										 FROM t_yudisium WHERE Status<>'Lunas'
										 AND TahunID='".strfilter($_GET[tahun])."'"));

$tuntasPersen   = number_format(($tuntas[jmlTuntas]/$grandtot[jGrandTot])* 100,0);			
								  
$persen			= ($tot[jml]/$all[jmlAll])* 100;
$persentase		= number_format(($tot[jml]/$all[jmlAll])* 100,0);		
$no++;	 

$content .= "  <tr>
	<td height='15' align=center> $no</td>
	<td align=center>$r[ProdiID]</td>	
	<td>&nbsp; $r[Nama]</td>
	<td align=center>$all[jmlAll]</td>
	<td align=center>$lunas[jmlLunas]</td>
	<td align=center>$blmLunas[jmlBlmLunas]</td>
    <td align=center>&nbsp; $persentase %</td>	
</tr>";
}

$content .= "  <tr>
	<td height='15' align=left colspan='3'> Total Calon Wisudawan</td>
	<td align=center>$grandtot[jGrandTot]</td>	
	<td align=center>&nbsp;</td>
	<td align=center>&nbsp;</td>
	<td align=center>&nbsp;</td>
	</tr>
    <tr>
	<td height='15' align=left colspan='3'> Jumlah Sudah Melunasi Biaya Wisuda dan Berkas Lengkap</td>
	<td align=center>$tuntas[jmlTuntas]</td>	
	<td align=center>&nbsp;</td>
	<td align=center>&nbsp;</td>
	<td align=center>&nbsp;</td>
	</tr>
  <tr>
	<td height='15' align=left colspan='3'> Jumlah Belum Melunasi Biaya Wisuda dan Berkas Belum Lengkap</td>
	<td align=center>$btuntas[jmlBTuntas]</td>	
	<td align=center>&nbsp;</td>
	<td align=center>&nbsp;</td>
	<td align=center>&nbsp;</td>
	</tr>
  <tr>
	<td height='15' align=left colspan='3'> Persentase Kesiapan Pelunasan dan Berkas SI dan TI</td>
	<td align=center>$tuntasPersen %</td>	
	<td align=center>&nbsp;</td>
	<td align=center>&nbsp;</td>
	<td align=center>&nbsp;</td>
	</tr>	
</table>
<br>

<br>


<table border=0 width='800' align='center'>
<tr>
<td width='423' align='center'>&nbsp;</td>
<td width='367' align='left'>Pekanbaru, $tgl  <br>Sekretaris Wisuda Univ Tekno Indo</td>
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
  <td align='left' >&nbsp;</td>
</tr>

<tr>
<td align='left'></td>
<td align='left'>$sekrewisuda[Nama]</td>
</tr>
</table> ";

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