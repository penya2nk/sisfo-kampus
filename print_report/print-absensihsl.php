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

$Matakul  = $mboh[NamaMK];
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
<td>".substr($mboh[JamMulai],0,5)." - ".substr($mboh[JamSelesai],0,5)." WIB</td> 
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
	mhsw.Nama as NamaMhs,
	presensi.Pertemuan,presensi.PresensiID,
	presensimhsw.JenisPresensiID,
	jenispresensi.Nilai
	FROM krs
	LEFT OUTER JOIN jadwal ON krs.JadwalID=jadwal.JadwalID	
	LEFT OUTER JOIN presensi ON jadwal.JadwalID=presensi.JadwalID
	LEFT OUTER JOIN presensimhsw ON presensi.PresensiID=presensimhsw.PresensiID	
	LEFT OUTER JOIN jenispresensi ON jenispresensi.JenisPresensiID=presensimhsw.JenisPresensiID			
	LEFT OUTER JOIN mhsw ON mhsw.MhswID=krs.MhswID
	WHERE krs.JadwalID='".strfilter($_GET[JadwalID])."'
	AND jadwal.TahunID='".strfilter($_GET[tahun])."'
	GROUP BY krs.JadwalID,krs.MhswID order by krs.MhswID asc");
//sq = mysqli_query($koneksi, "SELECT * from presensimhsw where JadwalID='".strfilter($_GET[JadwalID])."'");
while($r=mysqli_fetch_array($sq)){
$no++;	       

$NamaMhsx 	= strtolower($r[NamaMhs]); 
$NamaMhs	= ucwords($NamaMhsx);
$content .= "<tr>
<td align=center>$no</td>
	<td align=center>$r[MhswID]</td>
	<td align=left>$NamaMhs</td>";
    $p1 = mysqli_query($koneksi, "SELECT * from presensimhsw where MhswID='$r[MhswID]' and JadwalID='".strfilter($_GET[JadwalID])."'");
    while($w=mysqli_fetch_array($p1)){ 
		$nm = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from jenispresensi where JenisPresensiID='$w[JenisPresensiID]'"));
		$content .= "<td style='text-align:center'>$w[JenisPresensiID]-$nm[Nilai]</td>";
	}

	$content .= "</tr>";
}
$content .="
<tr>
	<td height='20' align=center></td>
	<td align=center></td>
	<td align=right>Tanggal&nbsp;</td>";
    $p1 = mysqli_query($koneksi, "SELECT * from presensi where  JadwalID='".strfilter($_GET[JadwalID])."'");
    while($w=mysqli_fetch_array($p1)){ 
		//$nm = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from jenispresensi where JenisPresensiID='$w[JenisPresensiID]'"));
		$content .= "<td style='text-align:center'><b style='font-size:9px'>".date('d-m-y',strtotime($w['Tanggal']))."</b></td>";
	}

	$content .= "</tr>";

$content .="<tr>
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
</tr>";
$content .= "</table><br>";
$content .="<table>
<tr>
	<td>Keterangan</td>           
</tr>
<tr>
	<td>H</td><td> : Hadir (1)</td>           
</tr>
<tr>
	<td>S</td><td> : Sakit (1)</td>           
</tr>
<tr>
	<td>I</td><td> : Ijin (1)</td>           
</tr>
<tr>
	<td>M</td><td> : Mangkir (0)</td>           
</tr>
</table>


<br>
<table border=0 width='830' align='center'>
<tr>
<td width='700' align='center'>&nbsp;</td>
<td width='530' align='left'>Pekanbaru, $tgl  <br>Dosen Pengampu</td>
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
<td align='left'>$mboh[NamaDos], $mboh[Gelar]</td>
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

try
	{
		$html2pdf = new HTML2PDF('L','Letter','en', false, 'ISO-8859-15',array(10, 30, 10, 30)); //setting ukuran kertas dan margin pada dokumen anda
		// $html2pdf->setModeDebug();
		$html2pdf->setDefaultFont('Arial');
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output($filename);
	}
	catch(HTML2PDF_exception $e) { echo $e; }
	
} //session login
?>	