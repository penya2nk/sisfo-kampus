<?php 
session_start();
error_reporting(0);
include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
require ("../punksi/html2pdf/html2pdf.class.php");
//include "../konfigurasi/fungsi_enkripsi2.php";


$filename="transkripnilai.pdf";
$tgl = tgl_indo(date('Y-m-d'));

if (empty($_SESSION['_Login']) && empty($_SESSION['_LevelID'])){
	header("Location: ../login.php");
}

else{
//$MhswIDx  = base64url_decode($_GET['MhswID']);
include "headerx-rpt.php"; $content = ob_get_clean();

$mhs       = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,ProgramID,MhswID,Nama FROM mhsw where MhswID='".strfilter($_GET[MhswID])."'"));
$ProgramID = $mhs[ProgramID];

$program   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProgramID,Nama FROM program where ProgramID='$ProgramID'"));
$prodi     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,FakultasID,Nama,Pejabat FROM prodi where ProdiID='".strfilter($_GET['prodi'])."'"));
$fakultas  = mysqli_fetch_array(mysqli_query($koneksi, "SELECT FakultasID,Nama,Pejabat FROM fakultas where ProdiID='$prodi[FakultasID]'"));

$content .= "

<table align='center'> 
<tr>
<td><b>TRANSKRIP NILAI SEMENTARA</b></td>
</tr>
</table>     
<br>
<br>


<table width='800' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td width='100'>Nama </td>       
<td width='222'> : $mhs[Nama]</td>           
</tr>

<tr>
<td width='100'>NIM</td> 
<td width='155'>: $mhs[MhswID]</td>
</tr>

<tr>
<td>Program/Prodi</td>            
<td > : $program[Nama] / $prodi[Nama]</td>             
<td></td> <td></td>
</tr>
</table>
<br>
<table  width='800' border='0.3' align='center' cellspacing='0'>
<tr style='background-color:#E6E6E6'>
<th width='30' height='30' align='center'> NO.</th>
<th width='80' align='center'>KODE</th>
<th width='300' align='center'>MATA KULIAH</th>
<th width='60' align='center'> SKS</th>
<th width='60' align='center'>HURUF</th>
<th width='60' align='center'>BOBOT</th>
</tr>";
/*$jenis = mysqli_query($koneksi, "SELECT * FROM jenismk where ProdiID='".strfilter($_GET[prodi])."'");  
while ($j = mysqli_fetch_array($jenis)){

$content .= "<tr style='background-color:#E6E6E6'>
<td colspan='6' height='20' ><b>&nbsp;  $j[Singkatan] ($j[Nama])</b></td>
</tr>";       */ 

//$sq = mysqli_query($koneksi, "SELECT * from vw_transkrip where MhswID='".strfilter($_GET[MhswID])."' AND JenisMK='$j[Nama]' ORDER BY MKKode ASC");
$sq = mysqli_query($koneksi, "SELECT
				  krs.KRSID      AS KRSID,
				  krs.KHSID      AS KHSID,
				  krs.TahunID    AS TahunID,
				  krs.MhswID     AS MhswID,
				  mhsw.Nama      AS NamaMhs,
				  mk.Nama        AS NamaMK,
				  krs.Tugas1     AS Tugas1,
				  krs.Tugas2     AS Tugas2,
				  krs.Tugas3     AS Tugas3,
				  krs.Tugas4     AS Tugas4,
				  krs.Tugas5     AS Tugas5,
				  krs.Presensi   AS Presensi,
				  krs.UTS        AS UTS,
				  krs.UAS        AS UAS,
				  krs.NilaiAkhir AS NilaiAkhir,
				  krs.GradeNilai AS GradeNilai,
				  krs.BobotNilai AS BobotNilai,
				  krs.SKS        AS SKS,
				  mk.MKID        AS MKID,
				  mk.MKKode      AS MKKode,
				  mk.Sesi        AS Sesi,
				  mhsw.ProdiID   AS ProdiID,
				  mhsw.ProgramID AS ProgramID
				  FROM mhsw,krs,mk
				  WHERE krs.MhswID=mhsw.MhswID
				  AND krs.MKID=mk.MKID 
				  AND mhsw.MhswID='".strfilter($_GET[MhswID])."'
				  AND krs.GradeNilai NOT IN ('-','TL','E','D')
				  ORDER BY mk.Sesi,mk.Nama ASC");
$no = 1;
while($r=mysqli_fetch_array($sq)){
$Matakul        = $r[NamaMK];
$Matakul_kecil 	= strtolower($Matakul);
$matKecil       = ucwords($Matakul_kecil);	       
$huruf          = $r[GradeNilai];
if ($huruf=='A'){
	$bobot=4;
}
elseif ($huruf=='A-'){
	$bobot=3.70;
}
elseif ($huruf=='B'){
	$bobot=3;
}
elseif ($huruf=='B+'){
	$bobot=3.30;
}
elseif ($huruf=='B-'){
	$bobot=2.70;
}
elseif ($huruf=='C'){
	$bobot=2;
}
elseif ($huruf=='C+'){
	$bobot=2.30;
}
elseif ($huruf=='C-'){
	$bobot=1.70;
}
elseif ($huruf=='D'){
	$bobot=1;
}
else{
    $bobot=0; 
}


/*if ($nilai >= 85 AND $nilai <= 100){
						$mutu = "A";
						$bobot = "4";
}
elseif ($nilai >= 80 AND $nilai <= 84.9){
	$mutu = "A-";
	$bobot = "3.75";
}
elseif ($nilai >= 75 AND $nilai <= 79.9){
	$mutu = "B+";
	$bobot = "3.25";
}
elseif ($nilai >= 70 AND $nilai <= 74.9){
	$mutu = "B";
	$bobot = "3";
}
elseif ($nilai >= 65 AND $nilai <= 69.9){
	$mutu = "B-";
	$bobot = "2.75";
}
elseif ($nilai >= 60 AND $nilai <= 64.9){
	$mutu = "C+";
	$bobot = "2.25";
}
elseif ($nilai >= 55 AND $nilai <= 59.9){
	$mutu = "C";
	$bobot = "2";
}
elseif ($nilai >= 50 AND $nilai <= 54.9){
	$mutu = "C-";
	$bobot = "1.75";
}
elseif ($nilai >= 45 AND $nilai <= 49.9){
	$mutu = "D";
	$bobot = "1";
}
elseif ($nilai < 45){
	$mutu = "E";
	$bobot = "0";
}*/

$total_sks 	 += $r['SKS'];
$total_bobot  = $r['SKS'] * $bobot;

$bobot 		 += $bobot;
$bobot_total += $total_bobot;

$ipk = number_format($bobot_total / $total_sks,2);
if ($ipk >= 3.00) {
	$YAD=24;
	}
if ($ipk < 3.00) {
	$YAD=21;
	}
if ($ipk <= 2.49) {
	$YAD=18;
	}
if ($ipk <= 1.99) {
	$YAD=15;
	}
if ($ipk <= 1.4) {
	$YAD=12;
	}
$content .= "  <tr>
	<td height='15' align=center> $no</td>
	<td align=center>$r[MKKode]</td>
	<td>&nbsp;$matKecil</td>
	<td align=center >$r[SKS]</td>
	<td align=center >$r[GradeNilai]</td>
	<td align=center >$total_bobot</td>              
</tr>";
$no++;
}
//} kategori
$content .= "</table>
<br>
<table border='0' width='800' align='center' cellspacing='0'>
  <tr>
  <td width='600' height='15'>Total SKS: $total_sks SKS</td>
  </tr>
  
  
  <tr>
  <td width='169'>Total Bobot: $bobot_total</td>
  </tr>
  
  <tr>
  <td width='147'>IPK: $ipk</td>
  </tr>
  
</table>

<br>



<table border=0 width='800' align='center'>
<tr>
<td width='423' align='center'>&nbsp;</td>
<td width='367' align='left'>Pekanbaru, $tgl  <br>Dekan $fakultas[Nama]</td>
</tr>

<tr>
  <td align='left'></td>
  <td align='left' >Universitas Hang Tuah Pekanbaru</td>
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
<td align='left'>$fakultas[Pejabat]</td>
</tr>
</table> ";


try
	{
		$html2pdf = new HTML2PDF('P','Letter','en', false, 'ISO-8859-15',array(10, 10, 10, 10)); //setting ukuran kertas dan margin pada dokumen anda
		// $html2pdf->setModeDebug();
		$html2pdf->setDefaultFont('Arial');
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output($filename);
	}
	catch(HTML2PDF_exception $e) { echo $e; }
	
} //session login
?>	