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
$prodi    = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='".strfilter($_GET[prodi])."'"));
$mboh     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM vw_sp where MKID='".strfilter($_GET[MKID])."'"));


$Dos     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,NIDN,Nama,Gelar FROM dosen where Login='$mboh[DosenID]'"));
$dosenx   = strtolower($Dos[Nama]);
$dosen    = ucwords($dosenx);


$Matakul 	= $mboh[NamaMK];
$Matakul_kecil 	= strtolower($Matakul);
$matKecil 	= ucwords($Matakul_kecil);


$content .= "
<table align='center'> 

<tr  align='center'>
<td><u><b>LEMBAR PENILAIAN SEMESTER PENDEK</b></u></td>
</tr>
<tr  align='center'>
<td><b>Program Studi : $prodi[Nama]</b></td>
</tr>
</table>     


<table width='800' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
  <td width='100'>&nbsp;</td>
  <td width='500'>&nbsp;</td>
  <td width='80'>&nbsp;</td>             
  <td width='200'>&nbsp;</td> 
</tr>

<tr>
  <td>Matakuliah</td>
<td>: $mboh[MKKode] - $matKecil</td>
<td>Dosen</td>             
<td>: $dosen, $Dos[Gelar]</td> 
</tr>


<tr>
  <td>Semester / Tahun</td>
<td>: $mboh[Sesi] / $_GET[tahun]</td>
<td>Kelas </td>             
<td>: $mboh[Ruang]</td> 
</tr>

</table>
<br>
<table  width='800' border='0.3' align='center' cellspacing='0'>
<tr style='background-color:#E7EAEC' >
  <th width='30' rowspan='3' align='center'> NO.</th>
  <th width='80' rowspan='3' align='center'>NIM</th>
  <th width='200' rowspan='3' align='center'>NAMA MAHASISWA</th>
  <th height='10' colspan='11' align='center'>NILAI</th>
  </tr>
<tr style='background-color:#E7EAEC'>
  <th height='10' colspan='6' align='center'>TUGAS</th>
  <th align='center'>&nbsp;</th>
  <th width='75' rowspan='2' align='center'>UTS</th>
  <th width='75' rowspan='2' align='center'>UAS</th>
  <th width='60' align='center'>&nbsp;</th>
  <th width='60' align='center'>&nbsp;</th>
  </tr>
<tr style='background-color:#E7EAEC'>
<th width='40' height='10' align='center'>1</th>
<th width='40' align='center'>2</th>
<th width='40' align='center'>3</th>
<th width='40' align='center'>4</th>
<th width='40' align='center'>5</th>
<th width='40' align='center'>R</th>
<th width='60' align='center'>HADIR</th>
<th colspan='2' align='center'>NILAI AKHIR</th>
</tr>

<tr style='background-color:#E7EAEC'>
  <td height='10' align=center>&nbsp;</td>
  <td align=center>&nbsp;</td>
  <td>&nbsp;</td>
  <td align=center >&nbsp;</td>
  <td align=center >&nbsp;</td>
  <td align=center >&nbsp;</td>
  <td align=center >&nbsp;</td>
  <td align=center >&nbsp;</td>
  <td align=center width='75'>25%</td>
  <td align=center width='75'>15%</td>
  <td align=center >Nilai (25%)</td>
  <td align=center >Nilai (35%)</td>
  <td align=center >Angka</td>
  <td align=center >Huruf</td>
</tr>
";
        
$sq = mysqli_query($koneksi, "SELECT * from vw_sp where MKID='".strfilter($_GET[MKID])."' and TahunID='".strfilter($_GET[tahun])."' and Periode='".strfilter($_GET[Periode])."' order by MhswID Asc");
while($r=mysqli_fetch_array($sq)){
$no++;	       
$huruf= $r[GradeNilai];
$NamaMhs 		= $r[NamaMhs];
$NamaMhs_kecil 	= strtolower($NamaMhs ); //strtoupper($kalimat);
$NamaMhs_AKecil	= ucwords($NamaMhs_kecil);
$content .= "

<tr>
	<td height='15' align=center> $no</td>
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
	</tr>
";
}
$content .= "
<tr>
	<td height='15' align=center></td>
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
  </tr>

</table>
<br>



<table border=0 width='800' align='center'>
<tr>
  <td colspan='3' rowspan='7' align='center'><table border='0.3' width='20' align='center' cellpadding='0' cellspacing='0'>
<tr>
  <td width='32' align='center'>No</td>
  <td width='172' align='center'>Range Nilai (0-100)</td>
  <td width='102' align='center'>Nilai Huruf (Markah)</td>
</tr>

<tr>
  <td align='center'>1</td>
  <td align='center'>85 - 100</td>
  <td align='center'>A</td>
</tr>
<tr>
  <td align='center'>2</td>
  <td align='center'>80 - 84.99</td>
  <td align='center'>A-</td>
</tr>
<tr>
  <td align='center'>3</td>
  <td align='center'>75 - 79.99</td>
  <td align='center'>B+</td>
</tr>
<tr>
  <td align='center'>4</td>
  <td align='center'>70 - 74.99</td>
  <td align='center'>B</td>
</tr>
<tr>
  <td align='center'>5</td>
  <td align='center'>65 - 69.00</td>
  <td align='center'>B-</td>
</tr>
<tr>
  <td align='center'>6</td>
  <td align='center'>60 - 64.99</td>
  <td align='center'>C+</td>
</tr>
<tr>
  <td align='center'>7</td>
  <td align='center'>55 - 59.99</td>
  <td align='center'>C</td>
</tr>
<tr>
  <td align='center'>8</td>
  <td align='center'>50 - 54.99</td>
  <td align='center'>C-</td>
</tr>
<tr>
  <td align='center'>9</td>
  <td align='center'>40 - 49.99</td>
  <td align='center'>D</td>
</tr>
<tr>
  <td align='center'>10</td>
  <td align='center'>0 - 39.99</td>
  <td align='center'>E</td>
</tr>

  </table></td>
  <td width='400' align='center'>&nbsp;</td>
  <td width='108' align='left'>Penilaian</td>
<td width='169' align='center'>&nbsp;</td>
<td width='158' align='left'>Pekanbaru, $tgl  <br>Dosen Pengampu</td>
</tr>

<tr>
  <td align='left'></td>
  <td align='left'>UTS</td>
  <td align='left'>: 25%</td>
  <td align='left' >&nbsp;</td>
</tr>

<tr>
  <td align='left'></td>
  <td align='left'>Tugas</td>
  <td align='left'> : 25%</td>
  <td align='left' >&nbsp;</td>
</tr>



<tr>
  <td align='left'></td>
  <td align='left'>UAS</td>
  <td align='left'>: 35%</td>
  <td align='left'>$dosen, $Dos[Gelar]</td>
</tr>

<tr>
  <td align='left'></td>
  <td align='left'>Kehadiran</td>
<td align='left'> : 15%</td>
<td align='left'></td>
</tr>
<tr>
  <td align='left'></td>
  <td colspan='2' align='left'>---------------------------------------------</td>
<td align='left'></td>
</tr>
<tr>
  <td align='left'></td>
  <td align='left'>Nilai Akhir</td>
<td align='left'>: 100%</td>
<td align='left'></td>
</tr>

</table>
<br>
<br>
<br>
<br>
<br>
<table border=0>
<tr>
<td width='10'></td>
<td width='300'><font style='font-size:8px'>Login by: $_SESSION[_Login] ".tgl_indo(date('Y-m-d'))." ".date('H:i:s') . " WIB - Univ Tekno Indo Support System</font></td>
</tr>
</table>";
try
	{
		$html2pdf = new HTML2PDF('L','Letter','en', false, 'ISO-8859-15',array(10, 10, 10, 10)); //setting ukuran kertas dan margin pada dokumen anda
		// $html2pdf->setModeDebug();
		$html2pdf->setDefaultFont('Arial');
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output($filename);
	}
	catch(HTML2PDF_exception $e) { echo $e; }
	
} //session login
?>	