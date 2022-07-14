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
	include "headerx-rpt.php";
include "headerx-rpt.php"; $content = ob_get_clean();
$data   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM t_sp where MKID='".strfilter($_GET[MKID])."'"));
$mt     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MKID,MKKode,Nama,SKS,Sesi FROM mk where MKID='".strfilter($_GET[MKID])."'"));
$ds     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$data[DosenID] '"));

$Matakul_kecil 	= strtolower($mt[Nama]);
$matKecil 		= ucwords($Matakul_kecil);

$NamaDosen 	= strtolower($ds[Nama]);
$NamaDosenx = ucwords($NamaDosen);

if ($_GET[prodi]=='SI'){
	$pr  	="Sistem Informasi";
	$kaprod ="Herianto, S.Kom, M.Kom";
}else{
	$pr  	="Teknik Informatika";
	$kaprod ="Yuda Irawan, S.Kom, M.TI";
}


$content .= "

<table align='center'> 
<tr>
<td><b>ABSENSI PERKULIAHAN SEMESTER PENDEK</b></td>
</tr>
</table>     
<br>

<table width='1408' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td width='120'>Matakuliah</td>
<td width='0'>:  $mt[MKKode] - $matKecil ($mt[SKS] SKS) -  Semester $mt[Sesi]</td>
<td width='60'>&nbsp;</td>
<td width='170'>Waktu / Ruang </td>
<td width='150'>: $data[Waktu] / $data[Ruang]</td> 
</tr>

<tr>
<td>Dosen</td>
<td>: $ds[Login] - $ds[Nama], $ds[Gelar]</td>
<td>&nbsp;</td>
<td>Tahun Akademik</td>
<td>: $_GET[tahun]</td> 
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
        
$sq = mysqli_query($koneksi, "SELECT * from vw_sp where MKID='".strfilter($_GET[MKID])."' and TahunID='".strfilter($_GET[tahun])."' and Periode='".strfilter($_GET[Periode])."' order by MhswID Asc");
while($r=mysqli_fetch_array($sq)){
$no++;	       
$huruf= $r[GradeNilai];
$NamaMhs 		= $r[NamaMhs];
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

for ($i=0; $i <= 2; $i++){
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
<td align='left'>$kaprod</td>
</tr>
</table>

<br>
<br>
<br>

<br>
<br>
<table border=0>
<tr>
<td width='30'></td>
<td width='300'><font style='font-size:8px'>Login by: $_SESSION[_Login] ".tgl_indo(date('Y-m-d'))." ".date('H:i:s') . " WIB - Univ Tekno Indo Support System</font></td>
</tr>
</table>";

try
	{
		$html2pdf = new HTML2PDF('L','Letter','en', false, 'ISO-8859-15',array(10, 5, 10, 5)); //setting ukuran kertas dan margin pada dokumen anda
		// $html2pdf->setModeDebug();
		$html2pdf->setDefaultFont('Arial');
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output($filename);
	}
	catch(HTML2PDF_exception $e) { echo $e; }
	
} //session login
?>	