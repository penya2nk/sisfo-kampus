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
if ($prodi[ProdiID]=='SI'){$nidn='1008068202';}else{$nidn='1010057101';}
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
<td colspan=14><b>SEKOLAH TINGGI MANAJEMAN INFORMATIKA DAN KOMPUTER (STMIK)</b></td>
</tr>
<tr  align='center'>
<td colspan=14><b><font style='font-size:25px'>HANG TUAH PEKANBARU</font></b></td>
</tr>


<tr  align='center'>
<td colspan=14></td>
</tr>
<tr  align='center'>
<td colspan=14><b>LEMBAR PENILAIAN</b></td>
</tr>
</table>     

<table width='800' border='0' cellpadding='0' cellspacing='0' align='center'>

<tr>
<td colspan=2>Dosen</td>       <td colspan=7>: $dosKecil, $mboh[Gelar]</td> <td colspan=2>Program Studi</td>	<td colspan=3>: $prodi[Nama]</td> 
</tr>

<tr>
<td colspan=2 >Matakuliah</td> <td colspan=7>: $matKecil ($mboh[SKS] SKS)</td><td colspan=2>Tahun Akademik</td> <td colspan=3>: $mboh[TahunID]</td>
</tr>

<tr>
<td colspan=2>Kelas </td>      <td colspan=7 >: $mboh[NamaKelas]</td><td colspan=2>Semester</td>		<td colspan=3>: $mboh[Sesi]</td> 
</tr>
</table>




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
<th width='60' align='center'>PRES</th>
<th colspan='2' align='center'>NILAI AKHIR</th>
</tr>

<tr>
  <td height='30' align=center>&nbsp;</td>
  <td align=center>&nbsp;</td>
  <td>&nbsp;</td>
  <td align=center >&nbsp;</td>
  <td align=center >&nbsp;</td>
  <td align=center >&nbsp;</td>
  <td align=center >&nbsp;</td>
  <td align=center >&nbsp;</td>
  <td align=center >25%</td>
  <td align=center >15%</td>
  <td align=center >25%</td>
  <td align=center >35%</td>
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
	<td align=center >$r[Tugas1]</td>
	<td align=center >$r[Tugas2]</td>
	<td align=center >$r[Tugas3]</td>
	<td align=center >$r[Tugas4]</td>
	<td align=center >$r[Tugas5]</td>
	<td align=center >$r[x]</td>
	<td align=center >$r[Presensi]</td>
	<td align=center >$r[UTS]</td>
	<td align=center >$r[UAS]</td>
	<td align=center >$r[NilaiAkhir]</td>
	<td align=center >$r[GradeNilai]</td>
  </tr>
";
}
echo"

</table>
<br>



<table border=0 width='800' align='center'>
<tr>
  <td colspan='3' rowspan='7' align='center'><table border='1' width='209' align='center' cellpadding='0' cellspacing='0'>
<tr>
  <td width='27' align='center'><b>No</b></td>
  <td width='96' align='center'><b>Range Nilai<br> (0-100)</b></td>
  <td width='78' align='center'><b>Nilai Huruf<br> (Markah)</b></td>
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
  <td width='10' align='center'>&nbsp;</td>
  <td width='253' rowspan='71' align='center'><table width='200' border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td colspan='4' align='left'><b>Penilaian</b></td>
  </tr>
  <tr>
    <td width='971' align='left' colspan=4>Tugas</td>
    <td width='103' align='left'>: 25%</td>
  </tr>
  <tr>
    <td align='left' colspan=4>UTS</td>
    <td align='left'> : 25%:</td>
  </tr>
  <tr>
    <td align='left' colspan=4>UAS</td>
    <td align='left'>: 35%</td>
  </tr>
  <tr>
    <td align='left' colspan=4>Kehadiran</td>
    <td align='left'> : 15%</td>
  </tr>
  <tr>
    <td colspan='5' align='left'>---------------------------------------------</td>
  </tr>
  <tr>
    <td align='left' colspan=4>Nilai Akhir</td>
    <td align='left'>: 100%</td>
  </tr>
</table></td>
  <td width='18' align='center'>&nbsp;</td>
  <td width='251' align='left' colspan='4'><b>Pekanbaru, $tgl  <br>Program Studi $prodi[Nama] <br>Dosen Pengampu</b></td>
</tr>



<tr>
  <td align='left' ></td>
  <td align='left'></td>
  <td align='left' colspan=4><b><u>$dosKecil, $mboh[Gelar]</u><br>NIDN: $mboh[NIDN]</b></td>
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
<tr>
  <td align='left'></td>
  <td align='left'></td>
  <td align='left'></td>
</tr>

</table>


 ";
	
}
?>