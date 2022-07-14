<?php 
session_start();
error_reporting(0);
include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
require ("../punksi/html2pdf/html2pdf.class.php");
$filename='namafile.pdf';
$tgl = tgl_indo(date('Y-m-d'));

if (empty($_SESSION['_Login']) && empty($_SESSION['_LevelID'])){
	header("Location: ../login.php");
}

else{
$prodi     		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='".strfilter($_GET[prodi])."'"));
$mboh     		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM vw_mengajar where JadwalID='".strfilter($_GET[JadwalID])."'"));

$dos 			= $mboh[NamaDos];
$dos_kecil 		= strtolower($dos);
$dosKecil 		= ucwords($dos_kecil);

$Matakul 		= $mboh[NamaMK];
$Matakul_kecil 	= strtolower($Matakul);
$matKecil 		= ucwords($Matakul_kecil);


$namaFile = "$mboh[NamaDos]_".$matKecil."_FormNilai.xls";
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=$namaFile");
header("Content-Transfer-Encoding: binary ");


echo"<table align='center'> 
<tr  align='center'>
<td colspan=14><b>PROGRAM STUDI : $prodi[Nama]</b></td>
</tr>
<tr  align='center'>
<td colspan=14><b>LEMBAR PENILAIAN</b></td>
</tr>
</table>     
<br>

<table width='800' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td colspan=2>Dosen</td>					<td>: $dosKecil</td> 
</tr>
<tr>
<td colspan=2>Matakuliah</td> 	<td>: $matKecil</td>
</tr>

<tr>
<td colspan=2>Kelas </td>					<td>: $mboh[NamaKelas]</td> 
</tr>

<tr>
<td colspan=2>Semester</td>		<td>:</td>
</tr>
</table>
<br>

<table  width='800' border='1' align='center' cellspacing='0'>
<tr >
  <th width='30' rowspan='3' align='center'> NO.</th>
  <th width='80' rowspan='3' align='center'>NIM</th>
  <th width='150' rowspan='3' align='center'>NAMA MAHASISWA</th>
  <th height='30' colspan='11' align='center'>NILAI</th>
  </tr>
<tr >
  <th height='30' colspan='6' align='center'>TUGAS</th>
  <th align='center'>&nbsp;</th>
  <th width='60' rowspan='2' align='center'>UTS</th>
  <th width='60' rowspan='2' align='center'>UAS</th>
  <th width='60' align='center'>&nbsp;</th>
  <th width='60' align='center'>&nbsp;</th>
  </tr>
<tr >
<th width='60' height='30' align='center'>1</th>
<th width='60' align='center'>2</th>
<th width='60' align='center'>3</th>
<th width='60' align='center'>4</th>
<th width='60' align='center'>5</th>
<th width='60' align='center'>R</th>
<th width='60' align='center'>KEHADIRAN</th>
<th colspan='2' align='center'>NILAI AKHIR</th>
</tr>

<tr>
  <td height='15' align=center>&nbsp;</td>
  <td align=center>&nbsp;</td>
  <td>&nbsp;</td>
  <td align=center >&nbsp;</td>
  <td align=center >&nbsp;</td>
  <td align=center >&nbsp;</td>
  <td align=center >&nbsp;</td>
  <td align=center >&nbsp;</td>
  <td align=center >25%</td>
  <td align=center >15%</td>
  <td align=center >Nilai (25%)</td>
  <td align=center >Nilai (35%)</td>
  <td align=center >ANGKA</td>
  <td align=center >HURUF</td>
</tr>
";
        
$sq = mysqli_query($koneksi, "SELECT * from vw_krs where JadwalID='".strfilter($_GET[JadwalID])."' order by MhswID Asc");
while($r=mysqli_fetch_array($sq)){
	$no++;	       
	$huruf			= $r[GradeNilai];
	$NamaMhs 		= $r[NamaMhs];
	$NamaMhs_kecil 	= strtolower($NamaMhs ); //strtoupper($kalimat);
	$NamaMhs_AKecil	= ucwords($NamaMhs_kecil);
echo"

<tr>
	<td height='25' align=center> $no</td>
	<td align=center >$r[MhswID]</td>
	<td >&nbsp;$NamaMhs_AKecil </td>
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
echo"
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
  <td colspan='3' rowspan='7' align='center'><table border='1' width='209' align='center' cellpadding='0' cellspacing='0'>
<tr>
  <td width='27' align='center'>No</td>
  <td width='96' align='center'>Range Nilai<br> (0-100)</td>
  <td width='78' align='center'>Nilai Huruf<br> (Markah)</td>
  </tr>

<tr>
  <td align='center'>1</td>
  <td align='center'>80 - 100</td>
  <td align='center'>A</td>
  </tr>

<tr>
  <td align='center'>2</td>
  <td align='center'>68 - 79</td>
  <td align='center'>B</td>
  </tr>



<tr>
  <td align='center'>3</td>
  <td align='center'>56 - 67</td>
  <td align='center'>C</td>
  </tr>

<tr>
  <td align='center'>4</td>
  <td align='center'>45 - 55</td>
  <td align='center'>D</td>
  </tr>
<tr>
  <td align='center'>5</td>
  <td align='center'>0 - 44</td>
  <td align='center'>E</td>
  </tr>
<tr>
  <td align='center'>6</td>
  <td align='center'></td>
  <td align='center'>TL</td>
  </tr>

</table></td>
  <td width='10' align='center'>&nbsp;</td>
  <td width='253' rowspan='71' align='center'><table width='200' border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td colspan='4' align='center'>Penilaian</td>
  </tr>
  <tr>
    <td width='971' align='left'>UTS</td>
    <td width='103' align='left'>: 25%</td>
  </tr>
  <tr>
    <td align='left'>Tugas</td>
    <td align='left'> : 25%:</td>
  </tr>
  <tr>
    <td align='left'>UAS</td>
    <td align='left'>: 35%</td>
  </tr>
  <tr>
    <td align='left'>Kehadiran</td>
    <td align='left'> : 15%</td>
  </tr>
  <tr>
    <td colspan='2' align='left'>---------------------------------------------</td>
  </tr>
  <tr>
    <td align='left'>Nilai Akhir</td>
    <td align='left'>: 100%</td>
  </tr>
</table></td>
  <td width='18' align='center'>&nbsp;</td>
  <td width='251' align='left' colspan='4'>Pekanbaru, $tgl  <br>Ketua Program Studi</td>
</tr>

<tr>
  <td align='left'></td>
  <td align='left'></td>
  <td align='left' >&nbsp;</td>
</tr>

<tr>
  <td align='left'></td>
  <td align='left'></td>
  <td align='left' >&nbsp;</td>
</tr>



<tr>
  <td align='left'></td>
  <td align='left'></td>
  <td align='left'>$dosKecil</td>
</tr>

<tr>
  <td align='left'></td>
  <td align='left'></td>
  <td align='left'></td>
</tr>
<tr>
  <td align='left'></td>
  <td align='left'></td>
  <td align='left'></td>
</tr>
<tr>
  <td align='left'></td>
  <td align='left'></td>
  <td align='left'></td>
</tr>

</table>
 ";
	
}
?>