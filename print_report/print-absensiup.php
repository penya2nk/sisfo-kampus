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
$tgl= tgl_indo(date('Y-m-d'));
if (empty($_SESSION['_Login']) && empty($_SESSION['_LevelID'])){
	header("Location: ../login.php");
}
else{

include "headerx-rpt.php";
$uj     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jadwal_uptahun where UjianID='".strfilter($_GET['UjianID'])."'"));
$prodi    = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='".strfilter($_GET['prodi'])."'"));

$tanggal 	= $uj['TglUjian'];
$day 		= date('D', strtotime($tanggal));
$dayList 	= array(
	'Sun' => 'Minggu',
	'Mon' => 'Senin',
	'Tue' => 'Selasa',
	'Wed' => 'Rabu',
	'Thu' => 'Kamis',
	'Fri' => 'Jumat',
	'Sat' => 'Sabtu'
);

$Matakul  = $mboh['NamaMK'];
$Matakul_kecil 	= strtolower($Matakul);
$matKecil =ucwords($Matakul_kecil);


$content .= "

<table align='center'> 
<tr>
<td align=center><b><u>DAFTAR HADIR UJIAN PROGRAM TAHUN $_GET[tahun]</u></b></td>
</tr>
<tr>
<td align=center><b>Program Studi: $prodi[Nama]</b></td>
</tr>
</table>     
<br>

<table border='0' cellpadding='0' cellspacing='0' align='center'>
<tr><td>Hari / Tanggal</td><td>:</td><td>$dayList[$day],  ".tgl_indo($uj['TglUjian'])."</td> </tr>
<tr><td width='200'>Waktu</td><td width='10'>:</td><td width='200'>".substr($uj['JamMulai'],0,5)." s/d ".substr($uj['JamSelesai'],0,5)." WIB</td> </tr>
<tr><td width='200'>Tempat</td><td width='10'>:</td><td width='200'>$uj[Ruang]</td> </tr>
</table>

<br>
<table  width='1408' border='0.3' align='center' cellspacing='0'>
<tr style='background-color:#E6E6E6'>
  <th width='30'  align='center'> NO.</th>
  <th width='80'  align='center'>NIM</th>
  <th width='300'  align='center'>NAMA MAHASISWA</th>
  <th height='20' width='80' align='center'>TANDA<br>TANGAN</th>
  <th width='150' height='20'  align='center'>KETERANGAN</th>
  </tr>
";
        
$sq = mysqli_query($koneksi, "SELECT mhsw.Nama,jadwal_skripsi.* 
				FROM mhsw,jadwal_skripsi
				WHERE mhsw.MhswID=jadwal_skripsi.MhswID 
				AND jadwal_skripsi.TahunID='".strfilter($_GET['tahun'])."' 
				AND jadwal_skripsi.ProdiID='".strfilter($_GET['prodi'])."'
				order by jadwal_skripsi.MhswID asc");
while($r=mysqli_fetch_array($sq)){
$no++;	       
$NamaMhsx 	= strtolower($r['Nama']); //strtoupper($kalimat);
$NamaMhs	= ucwords($NamaMhsx);
$content .= "<tr>
	<td height='20' align=center> $no</td>
	<td align=center>$r[MhswID]</td>
	<td>&nbsp;$NamaMhs</td>
	<td align=center >&nbsp;</td>
	<td align=center >&nbsp;</td>


    </tr>";
}

for ($i=0; $i <= 0; $i++){
$content .= "
<tr >
  <th width='30'  align='center'></th>
  <th width='80'  align='center'></th>
  <th width='300'  align='center'></th>
  <th height='20'  align='center'></th>
  <th height='20'  align='center'></th>
  </tr>";
}
$content .="

</table>
<br>



<table border=0 width='830' align='center'>
<tr>
<td width='500' align='center'>&nbsp;</td>
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
<td align='left'>$prodi[Pejabat]</td>
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

try {
    ob_start();
    $html2pdf = new Html2Pdf('P','A4','fr', true, 'UTF-8', array(15, 15, 15, 15), false); 
    $html2pdf->writeHTML($content);
    $html2pdf->output();
} catch (Html2PdfException $e) {
    $html2pdf->clean();

    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}

}	
?>	