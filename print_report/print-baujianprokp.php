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

$dt  = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM vw_jadwalkp where JadwalID='".strfilter($_GET[JadwalID])."'"));

$x   			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$dt[DosenID]'"));
$namap 			= $x[Nama];
$nama_kecil 	= strtolower($namap);
$pembimbing		= ucwords($nama_kecil);	

$a   			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$dt[Penguji1]'"));
$nama1 			= $a[Nama];
$nama_kecil1 	= strtolower($nama1);
$penguji1		= ucwords($nama_kecil1);	

$b   			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$dt[Penguji2]'"));
$nama2 			= $b[Nama];
$nama_kecil2 	= strtolower($nama2);
$penguji2		= ucwords($nama_kecil2);	

$c   			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$dt[Penguji3]'"));
$nama3 			= $c[Nama];
$nama_kecil3 	= strtolower($nama3);
$penguji3		= ucwords($nama_kecil3);	

$d     			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT RuangID,Nama FROM ruang where RuangID='$dt[TempatUjian]'"));

$tgl = substr($dt[TglMulaiSidang],8,2); //2017-01-01
$bln = substr($dt[TglMulaiSidang],5,2);
$thn = substr($dt[TglMulaiSidang],0,4);

$tanggal = $dt[TglMulaiSidang];
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
<td align='center'><b>BERITA ACARA PROPOSAL KERJA PRAKTEK </b></td>
</tr>
<tr>
<td align='center'>&nbsp;</td>
</tr>
</table>     
<br>
<br>

<table >
<tr >
  <td width='650' height=25>Pada hari ini,  $dayList[$day],  Tanggal $tgl Bulan $bulList[$bul] Tahun $thn Telah dilaksanakan Ujian Proposal Kerja Praktek Program Strata-1 ( S1 ),  
  Program Studi $progst Universitas Teknokrat Indonesia terhadap:
  </td>
</tr>
</table>
<br>
<table width='427' border='0.3' align='center' cellspacing='0'>
  <tr style='background-color:#E6E6E6'>
    <td width='25' align=center height=25>No</td>
    <td width='230' align=left>&nbsp; Nama</td>
    <td width='100' align=center>NIM </td>
    <td width='140' align=center>Program Studi</td>
    <td width='100' align=center>Jenjang</td>
  </tr>";
	
	$sqo  = mysqli_query($koneksi, "SELECT * FROM vw_jadwalkp_anggota where JadwalID='".strfilter($_GET[JadwalID])."'");
	while($data=mysqli_fetch_array($sqo)){	
	$nom++;
	$nama 		= $data[Nama];
	$namax 		= strtolower($nama);
	$nama_kecil = ucwords($namax);	
	$prodi 		= $data[ProdiID];
	if ($prodi=='SI'){$prod ="Sistem Informasi";}else{$prod ="Teknik Informatika"; }
$content .= "  
   <tr>
    <td width='0' align=center height=20>$nom</td>
    <td width='0'>&nbsp; $nama_kecil</td>
    <td width='0' align=center>$data[MhswID]</td>
    <td width='0' align=center>$prod</td>
    <td width='0' align=center>S1 (Strata 1)</td>	
  </tr>";
}
$content .= "</table>
<br>
<table width='700' border='0' cellpadding='0' cellspacing='0' align='center'>

<tr>
<td width='152'>1. Judul</td> <td width='5'>:</td><td width='505'> $dt[Judul]</td>           
</tr>

<tr>
<td width='152'></td> <td width='5'>&nbsp;</td><td width='450'></td>           
</tr>

<tr>
<td width='152'>2. Dosen Pembimbing</td> <td width='5'>:</td><td width='450'> $pembimbing, $x[Gelar]</td>           
</tr>

<tr>
<td width='152'>3. Waktu Ujian</td> <td width='5'>:</td><td width='450'> $dt[JamMulai] s/d $dt[JamSelesai]</td>           
</tr>

<tr>
<td width='152'>4. Tempat Ujian</td> <td width='5'>:</td><td width='450'> $d[Nama]</td>           
</tr>

<tr>
<td width='152'>5. Nilai</td> <td width='5'>:</td><td width='450'></td>           
</tr>
</table>


  <br>
<table width='427' border='0.3' align='center' cellspacing='0'>
  <tr style='background-color:#E6E6E6'>
    <td width='25' align=center height=25>No</td>
    <td width='230' align=left>&nbsp; Nama</td>
    <td width='100' align=center>NIM </td>
    <td width='60' align=center>Penguji I<br>(1)</td>
    <td width='60' align=center>Penguji II<br>(2)</td>
	 <td width='60' align=center>Penguji III<br>(3)</td>
	 <td width='60' align=center>Nilai<br>(1+2+3)/3</td>
  </tr>";
	
	
	$sqo  = mysqli_query($koneksi, "SELECT * FROM vw_jadwalkp_anggota where JadwalID='".strfilter($_GET[JadwalID])."'");
	while($data=mysqli_fetch_array($sqo)){	
	$nob++;
	$nama 		= $data[Nama];
	$namax 		= strtolower($nama);
	$nama_kecil = ucwords($namax);	
	$prodi 		= $data[ProdiID];
	if ($prodi=='SI'){$prod ="Sistem Informasi";}else{$prod ="Teknik Informatika"; }
$content .= "  
   <tr>
    <td width='0' align=center height=20>$nob</td>
    <td width='0'>&nbsp; $nama_kecil</td>
    <td width='0' align=center>$data[MhswID]</td>
    <td width='0' align=center>...</td>
    <td width='0' align=center>...</td>
	<td width='0' align=center>...</td>
	<td width='0' align=center>...</td>	
  </tr>";
}
$content .= "</table>
<br>
  
<table>  
  <tr>
    <td width=152>6. Keterangan</td>
    <td>:</td>
    <td > 1. Lulus &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2. Tidak Lulus</td>
  </tr>
</table>
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
<table width='0' border='0' cellpadding='0' cellspacing='0' align='left'>
  <tr>
    <td width='152' height=25>Ketua</td>
    <td width='10'>:</td>
    <td width='200'>$penguji1, $x[Gelar]</td>
    <td width='0'>1. --------------------------------------</td>
	
  </tr>
   <tr>
    <td width='100' height=25>Anggota</td>
    <td width='10'>:</td>
    <td width='200'>$penguji2, $b[Gelar]</td>
    <td width='0'>2. --------------------------------------</td>
	
  </tr>
   <tr>
    <td width='100 height=25'>Anggota</td>
    <td width='10'>:</td>
    <td width='200'>$penguji3, $c[Gelar]</td>
    <td width='0'>3. -------------------------------------- </td>
	
  </tr>
</table>
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
<table border=0>
<tr>
<td width='0'></td>
<td width='300'><font style='font-size:8px'>Login by: $_SESSION[_Login] ".tgl_indo(date('Y-m-d'))." ".date('H:i:s') . " WIB - Univ Tekno Indo Support System</font></td>
</tr>
</table>";


try
	{
		$html2pdf = new HTML2PDF('P','Letter','en', false, 'ISO-8859-15',array(25, 10, 25, 5)); //setting ukuran kertas dan margin pada dokumen anda
		// $html2pdf->setModeDebug();
		$html2pdf->setDefaultFont('Arial');
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output($filename);
	}
	catch(HTML2PDF_exception $e) { echo $e; }
	
}
?>	