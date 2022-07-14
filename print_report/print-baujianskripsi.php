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

$dt  		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM vw_jadwal_skripsi_ujian where JadwalID='".strfilter($_GET[JadwalID])."'"));
$JudulP 	= strtolower($dt[Judul]);
$Judul		= ucwords($JudulP);

$ProdiID   	= $dt[ProdiID];
if ($ProdiID=='SI'){ $prod="Sistem Informasi"; $kaprodi="Herianto, M.Kom";}else{ $prod="Teknik Informatika"; $kaprodi="Eka Sabna, M.Pd, M.Kom";}

$x   			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$dt[PembimbingSkripsi1]'"));
$namap 			= $x[Nama];
$nama_kecil 	= strtolower($namap);
$pembimbing1		= ucwords($nama_kecil);	

$y   			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$dt[PembimbingSkripsi2]'"));
$nama2 			= $y[Nama];
$nama_kecil2 	= strtolower($nama2);
$pembimbing2		= ucwords($nama_kecil2);	

$a   			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$dt[PengujiSkripsi1]'"));
$nama1 			= $a[Nama];
$nama_kecil1 	= strtolower($nama1);
$penguji1		= ucwords($nama_kecil1);	

$b   			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$dt[PengujiSkripsi2]'"));
$nama2 			= $b[Nama];
$nama_kecil2 	= strtolower($nama2);
$penguji2		= ucwords($nama_kecil2);	

$c   			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$dt[PengujiSkripsi3]'"));
$nama3 			= $c[Nama];
$nama_kecil3 	= strtolower($nama3);
$penguji3		= ucwords($nama_kecil3);	

$d     			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT RuangID,Nama FROM ruang where RuangID='$dt[TempatUjian]'"));

$tgl = substr($dt[TglUjianSkripsi],8,2); //2017-01-01
$bln = substr($dt[TglUjianSkripsi],5,2);
$thn = substr($dt[TglUjianSkripsi],0,4);

$tanggal = $dt[TglUjianSkripsi];
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


$bul = date('m', strtotime($tanggal));
$bulList = array(
	'01' => 'Januari',
	'02' => 'Februari',
	'03' => 'Maret',
	'04' => 'April',
	'05' => 'Mei',
	'06' => 'Juni',
	'07' => 'Juli',
	'08' => 'Agustus',
	'09' => 'September',
	'10' => 'Oktober',
	'11' => 'Nopember',
	'12' => 'Desember'
);

//echo "Tanggal {$tanggal} adalah hari : " . $dayList[$day];

/*
if ($bln=='01'){$bul ="Januari";}elseif ($bln=='02'){$bul ="Februari"; }elseif ($bln=='03'){$bul ="Maret"; }
elseif ($bln=='04'){$bul ="April";}elseif ($bln=='05'){$bul ="Mei"; }elseif ($bln=='06'){$bul ="Juni"; }
elseif ($bln=='07'){$bul ="Juli";}elseif ($bln=='08'){$bul ="Agustus"; }elseif ($bln=='09'){$bul ="September"; }
elseif ($bln=='10'){$bul ="Oktober";}elseif ($bln=='11'){$bul ="Nopember"; }else{$bul ="Desember"; }
*/


if ($dt[ProdiID]=='SI'){$progst ="Sistem Informasi";}else{$progst ="Teknik Informatika"; }
$content .= "

<table width='700' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td align='center'><b>BERITA ACARA UJIAN  SIDANG SKRIPSI DAN COMPREHENSIVE</b></td>
</tr>
<tr>
<td align='center'>Program Studi: $prod</td>
</tr>
</table>     
<br>
<br>

<table >
<tr >
  <td width='650' height=30>Pada hari ini,  $dayList[$day],  Tanggal $tgl Bulan $bulList[$bul] Tahun $thn Telah dilaksanakan Ujian  Sidang Skripsi dan Comprehensive Program Strata-1 ( S1 ),  
  Program Studi $progst Universitas Teknokrat Indonesia terhadap:
  </td>
</tr>
</table>
<table  >
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width='29'>1.</td>
    <td width='185'>Nama</td>
    <td width='10'>:</td>
    <td width='583'> $dt[Nama]</td>
  </tr>
  <tr>
    <td width='29'>&nbsp;</td>
    <td width='185'>NIM</td>
    <td width='10'>:</td>
    <td width='583'>$dt[MhswID]</td>
  </tr>
  <tr>
    <td width='29'>&nbsp;</td>
    <td width='185'>Program Studi</td>
    <td width='10'>:</td>
    <td width='583'> $prod</td>
  </tr>


  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

<table>  
<tr>
<td width='29'>2.</td>
<td width='185'>Judul Skripsi</td> 
<td width='10'>:</td>
<td width='400'> $Judul</td>           
</tr>
</table>

<table>
<tr>
<td width='29'></td>
<td width='185'></td> <td width='17'>&nbsp;</td>
<td width='583'></td>           
</tr>

<tr>
  <td>3.</td>
  <td>Dosen Pembimbing 1</td>
  <td width='10'>:</td>
  <td>$pembimbing1, $x[Gelar]</td>
</tr>
<tr>
  <td width='29'>&nbsp;</td>
<td width='185'>Dosen Pembimbing 2</td> 
<td width='10'>:</td><td width='583'> $pembimbing2, $y[Gelar]</td>           
</tr>

<tr>
  <td width='29'>4.</td>
<td width='185'>Waktu Ujian</td> 
<td width='10'>:</td><td width='583'>$dt[JamMulaiUjianSkripsi] s/d $dt[JamSelesaiUjianSkripsi] WIB</td>           
</tr>

<tr>
  <td width='29'>5.</td>
<td width='185'>Tempat Ujian</td> 
<td width='10'>:</td>
<td width='583'> Kampus Universitas Teknokrat Indonesia</td>           
</tr>

<tr>
  <td>6.</td>
  <td>Nilai</td>
  <td>:</td>
  <td>Angka = ..... &nbsp; Huruf =&nbsp; A &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;B&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; C&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; D&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; E</td>
</tr>
<tr>
  <td width='29'>7.</td>
<td width='185'>Keterangan</td> 
<td width='17'>:</td>
<td width='583'>1. Lulus &nbsp;&nbsp;&nbsp;&nbsp;2. Tidak Lulus</td>           
</tr>
</table>


  <br>
<br>
<table width='700' border='0' cellpadding='0' cellspacing='0' align='center'>
  <tr>
    <td width='152'>&nbsp;</td>   
  </tr>
  <tr>
    <td width='400' align=center><u>PANITIA UJIAN</u></td>   
  </tr>
  <tr>
    <td width='152'>&nbsp;</td>   
  </tr>
</table>
<br>
<table width='0' border='0.3' cellpadding='0' cellspacing='0' align='center'>
  <tr>
    <td width='80' height=30>&nbsp;Ketua</td>
  
    <td width='200'&nbsp;>&nbsp;$penguji3, $c[Gelar]</td>
    <td width='200'>&nbsp;1. </td>
	
  </tr>
   <tr>
    <td width='80' height=30>&nbsp;Anggota</td>
  
    <td width='200'>&nbsp;$penguji1, $x[Gelar]</td>
    <td width='200'>&nbsp;2. </td>
	
  </tr>
   <tr>
    <td width='80 height=30'>&nbsp;Anggota</td>
  
    <td width='200'>&nbsp;$penguji2, $b[Gelar]</td>
    <td width='200'>&nbsp;3.  </td>
	
  </tr>
</table>
<br>
<br>
<br>
<br>

<table border=0 width='700' align='center' cellpadding='0' cellspacing='0' >
<tr>
<td width='367' align='center'>Diketahui / disetujui oleh</td>
</tr>

<tr>
  <td align='center' >Ketua Universitas Teknokrat Indonesia</td>
</tr>

<tr>
  <td align='center' >&nbsp;</td>
</tr>

<tr>
  <td align='center' >&nbsp;</td>
</tr>
<tr>
  <td align='center' >&nbsp;</td>
</tr>

<tr>
<td align='center'>Hendry Fonda, M.Kom</td>
</tr>
<tr>
<td align='center'>NIDN. 1015027102</td>
</tr>
</table>

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
		$html2pdf = new HTML2PDF('P','Letter','en', false, 'ISO-8859-15',array(25, 10, 25, 10)); //setting ukuran kertas dan margin pada dokumen anda
		// $html2pdf->setModeDebug();
		$html2pdf->setDefaultFont('Arial');
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output($filename);
	}
	catch(HTML2PDF_exception $e) { echo $e; }
	
}
?>	