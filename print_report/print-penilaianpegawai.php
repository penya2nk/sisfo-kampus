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

$p1 			= mysqli_fetch_array(mysqli_query("select * from t_simpegpenilaian where IDPenilaian='".strfilter($_GET[IDPenilaian])."'"));	
$pg 			= mysqli_fetch_array(mysqli_query("select * from t_simpegpegawai where Noreg='$p1[Noreg]'"));	
$Namaa 			= strtolower($pg[Nama]);
$Pegawai		= ucwords($Namaa);
$un 			= mysqli_fetch_array(mysqli_query("select * from t_unitkerja where UnitKerja='$pg[UnitKerja]'"));	

$pg2 			= mysqli_fetch_array(mysqli_query("select * from t_simpegpegawai where Noreg='$p1[PejabatPenilai]'"));	
$Namaa2 		= strtolower($pg2[Nama]);
$Pegawai2		= ucwords($Namaa2);
$un2 			= mysqli_fetch_array(mysqli_query("select * from t_unitkerja where UnitKerja='$pg2[UnitKerja]'"));	

$pg3 			= mysqli_fetch_array(mysqli_query("select * from t_simpegpegawai where Noreg='$p1[AtasanPejabatPenilai]'"));	
$Namaa3 		= strtolower($pg3[Nama]);
$Pegawai3		= ucwords($Namaa3);
$un3 			= mysqli_fetch_array(mysqli_query("select * from t_unitkerja where UnitKerja='$pg3[UnitKerja]'"));	

$angka = $p1[Kesetiaan] + $p1[PrestasiKerja] + $p1[TanggungJawab] + $p1[Ketaatan] + $p1[Kejujuran] + $p1[Kerjasama] + $p1[Prakarsa] +  $p1[Kepemimpinan];
$rata = $angka / 8;

if ($p1[Kesetiaan]>=91){
   $prediketKesetiaan="Sangat Baik";
}
elseif ($p1[Kesetiaan]>=76){
   $prediketKesetiaan="Baik";
}
elseif ($p1[Kesetiaan]>=61){
   $prediketKesetiaan="Cukup";
}
elseif ($p1[Kesetiaan]>=51){
   $prediketKesetiaan="Kurang";
}
elseif ($p1[Kesetiaan]<=50){
   $prediketKesetiaan="Buruk";
}
else{
   $prediketKesetiaan="Cukup";
}

if ($p1[PrestasiKerja]>=91){
   $prediketPrestasiKerja="Sangat Baik";
}
elseif ($p1[PrestasiKerja]>=76){
   $prediketPrestasiKerja="Baik";
}
elseif ($p1[PrestasiKerja]>=61){
   $prediketPrestasiKerja="Cukup";
}
elseif ($p1[PrestasiKerja]>=51){
   $prediketPrestasiKerja="Kurang";
}
elseif ($p1[PrestasiKerja]<=50){
   $prediketPrestasiKerja="Buruk";
}
else{
   $prediketPrestasiKerja="Cukup";
}


if ($p1[TanggungJawab]>=91){
   $prediketTanggungJawab="Sangat Baik";
}
elseif ($p1[TanggungJawab]>=76){
   $prediketTanggungJawab="Baik";
}
elseif ($p1[TanggungJawab]>=61){
   $prediketTanggungJawab="Cukup";
}
elseif ($p1[TanggungJawab]>=51){
   $prediketTanggungJawab="Kurang";
}
elseif ($p1[TanggungJawab]<=50){
   $prediketTanggungJawab="Buruk";
}
else{
   $prediketTanggungJawab="Cukup";
}

if ($p1[Ketaatan]>=91){
   $prediketKetaatan="Sangat Baik";
}
elseif ($p1[Ketaatan]>=76){
   $prediketKetaatan="Baik";
}
elseif ($p1[Ketaatan]>=61){
   $prediketTanggungJawab="Cukup";
}
elseif ($p1[Ketaatan]>=51){
   $prediketKetaatan="Kurang";
}
elseif ($p1[Ketaatan]<=50){
   $prediketKetaatan="Buruk";
}
else{
   $prediketKetaatan="Cukup";
}

if ($p1[Kejujuran]>=91){
   $prediketKejujuran="Sangat Baik";
}
elseif ($p1[Kejujuran]>=76){
   $prediketKejujuran="Baik";
}
elseif ($p1[Kejujuran]>=61){
   $prediketKejujuran="Cukup";
}
elseif ($p1[Kejujuran]>=51){
   $prediketKejujuran="Kurang";
}
elseif ($p1[Kejujuran]<=50){
   $prediketKejujuran="Buruk";
}
else{
   $prediketKejujuran="Cukup";
}


if ($p1[Kerjasama]>=91){
   $prediketKerjasama="Sangat Baik";
}
elseif ($p1[Kerjasama]>=76){
   $prediketKerjasama="Baik";
}
elseif ($p1[Kerjasama]>=61){
   $prediketKerjasama="Cukup";
}
elseif ($p1[Kerjasama]>=51){
   $prediketKerjasama="Kurang";
}
elseif ($p1[Kerjasama]<=50){
   $prediketKerjasama="Buruk";
}
else{
   $prediketKejujuran="Cukup";
}

if ($p1[Prakarsa]>=91){
   $prediketPrakarsa="Sangat Baik";
}
elseif ($p1[Prakarsa]>=76){
   $prediketPrakarsa="Baik";
}
elseif ($p1[Prakarsa]>=61){
   $prediketPrakarsa="Cukup";
}
elseif ($p1[Prakarsa]>=51){
   $prediketPrakarsa="Kurang";
}
elseif ($p1[Prakarsa]<=50){
   $prediketPrakarsa="Buruk";
}
else{
   $prediketPrakarsa="Cukup";
}


if ($p1[Kepemimpinan]>=91){
   $prediketKepemimpinan="Sangat Baik";
}
elseif ($p1[Kepemimpinan]>=76){
   $prediketKepemimpinan="Baik";
}
elseif ($p1[Kepemimpinan]>=61){
   $prediketKepemimpinan="Cukup";
}
elseif ($p1[Kepemimpinan]>=51){
   $prediketKepemimpinan="Kurang";
}
elseif ($p1[Kepemimpinan]<=50){
   $prediketKepemimpinan="Buruk";
}
else{
   $prediketKepemimpinan="Cukup";
}


if ($angka>=91){
   $prediket="Sangat Baik";
}
elseif ($angka>=76){
   $prediket="Baik";
}
elseif ($angka>=61){
   $prediket="Cukup";
}
elseif ($angka>=51){
   $prediket="Kurang";
}
elseif ($angka<=50){
   $prediket="Buruk";
}
else{
   $prediket="Cukup";
}

$content .= "
<table  border='0' align='center' cellpadding='0' cellspacing='0' class='keliling'>
<tr class='batas2' ><td style=text-align:left;width:700px><b style=font-size:10px>Format DP-3</b></td></tr>
<tr class='batas2' ><td width='8%' align=center><img width='50' width='50' src='logo_uti.png'></td></tr>
<tr class='batas2' ><td style=text-align:center;width:700px><b>R A H A S I A</b></td></tr>
<tr class='batas2' ><td style=text-align:center><b>DAFTAR PENILAIAN PELAKSANAAN PEKERJAAN</b></td></tr>
<tr class='batas2' style=text-align:center><td ><b>PEGAWAI TETAP YAYASAN</b></td></tr>
</table>
<br>

<table  border='0' align='center' cellpadding='0' cellspacing='0' class='keliling'>
<tr class='batas2' >
  <td colspan='3' align='left' width=350>UNIVERSITAS TEKNOKRAT INDONESIA</td>
  <td align='right' width=350>JANGKA WAKTU PENILAIAN</td>
</tr>
<tr class='batas2' align='left'>
  <td>&nbsp;</td>
  <td colspan='2'>&nbsp;</td>
  <td align='right' width=350>BULAN: ".tgl_indo($p1[Mulai])." s/d ".tgl_indo($p1[Akhir])."</td>
  </tr>
</table>
<br>
<table  border='0.3' align='center' cellpadding='0' cellspacing='0' class='keliling'>  
<tr class='batas2' align='left'>
  <td rowspan='10' width=30 align=center>&nbsp;1. </td>
<td colspan='3'><b>YANG DINILAI</b></td>
</tr>
<tr class='batas2' align='left'>
  <td width=15 align=center>a.</td>
<td width=200 > Nama</td>
<td width=250>&nbsp;: $Pegawai, $pg[Gelar]</td>
</tr>

<tr class='batas2' align='left'>
  <td align=center>b.</td>
<td >NIDN/Noreg</td>
<td >&nbsp;: $pg[NIDN]/$pg[Noreg]</td>
</tr>

<tr class='batas2' align='left'>
  <td align=center>c.</td>
<td >Pangkat, Golongan Ruang</td>
<td >&nbsp;: $pg[GolRuang]</td>
</tr>

<tr class='batas2' align='left'>
  <td align=center>d.</td>
<td>Jabatan</td>
<td>&nbsp;</td>
</tr>

<tr class='batas2' align='left'>
  <td>&nbsp;</td>
<td>1. Struktural</td>
<td>&nbsp;: $pg[Jabatan]</td>
</tr>

<tr class='batas2' align='left'>
  <td>&nbsp;</td>
<td>2. Akademik</td>
<td>&nbsp;: $pg[JabAkademik]</td>
</tr>

<tr class='batas2' align='left'>
  <td align=center>e. </td>
<td>Unit Kerja</td>
<td>&nbsp;</td>
</tr>

<tr class='batas2' align='left'>
  <td>&nbsp;</td>
<td>1. Institusi</td>
<td>&nbsp;: Universitas Teknokrat Indonesia</td>
</tr>
<tr class='batas2' align='left'>
  <td >&nbsp;</td>
  <td >2. Program Studi</td>
  <td >&nbsp;: $un[Nama]</td>
</tr>

<tr class='batas2' align='left'>
  <td rowspan='10' align=center>&nbsp;2. </td>
  <td colspan='2'><b>PEJABAT PENILAI</b></td>
  <td>&nbsp;</td>
</tr>
<tr class='batas2' align='left'>
  <td align=center>a.</td>
<td> Nama</td>
<td>&nbsp;: $Pegawai2, $pg2[Gelar]</td>
</tr>

<tr class='batas2' align='left'>
  <td align=center>b.</td>
<td >NIDN/Noreg</td>
<td >&nbsp;: $pg2[NIDN]/$pg2[Noreg]</td>
</tr>

<tr class='batas2' align='left'>
  <td align=center>c.</td>
<td >Pangkat, Golongan Ruang</td>
<td >&nbsp;: $pg2[GolRuang]</td>
</tr>

<tr class='batas2' align='left'>
  <td align=center>d.</td>
<td>Jabatan</td>
<td>&nbsp;</td>
</tr>

<tr class='batas2' align='left'>
  <td>&nbsp;</td>
<td>1. Struktural</td>
<td>&nbsp;: $pg2[Jabatan]</td>
</tr>

<tr class='batas2' align='left'>
  <td>&nbsp;</td>
<td>2. Akademik</td>
<td>&nbsp;: $pg2[JabAkademik]</td>
</tr>

<tr class='batas2' align='left'>
  <td align=center>e. </td>
<td>Unit Kerja</td>
<td>&nbsp;</td>
</tr>

<tr class='batas2' align='left'>
  <td>&nbsp;</td>
<td>1. Institusi</td>
<td>&nbsp;: Universitas Teknokrat Indonesia</td>
</tr>
<tr class='batas2' align='left'>
  <td >&nbsp;</td>
  <td >2. Program Studi</td>
  <td >&nbsp;: $un2[Nama]</td>
</tr>

<tr class='batas2' align='left'>
  <td rowspan='10' align=center>3. </td>
  <td colspan='2'><b>ATASAN PEJABAT PENILAI</b></td>
  <td>&nbsp;</td>
</tr>
<tr class='batas2' align='left'>
  <td align=center>a.</td>
<td> Nama</td>
<td>&nbsp;: $Pegawai3, $pg3[Gelar]</td>
</tr>

<tr class='batas2' align='left'>
  <td align=center>b.</td>
<td >NIDN/Noreg</td>
<td >&nbsp;: $pg3[NIDN]/$pg3[Noreg]</td>
</tr>

<tr class='batas2' align='left'>
  <td align=center>c.</td>
<td >Pangkat, Golongan Ruang</td>
<td >&nbsp;: $pg3[GolRuang]</td>
</tr>

<tr class='batas2' align='left'>
  <td align=center>d.</td>
<td>Jabatan</td>
<td>&nbsp;</td>
</tr>

<tr class='batas2' align='left'>
  <td>&nbsp;</td>
<td>1. Struktural</td>
<td>&nbsp;: $pg3[Jabatan]</td>
</tr>

<tr class='batas2' align='left'>
  <td>&nbsp;</td>
<td>2. Akademik</td>
<td>&nbsp;: $pg3[JabAkademik]</td>
</tr>

<tr class='batas2' align='left'>
  <td align=center>e. </td>
<td>Unit Kerja</td>
<td>&nbsp;</td>
</tr>

<tr class='batas2' align='left'>
  <td>&nbsp;</td>
<td>1. Institusi</td>
<td>&nbsp;: Universitas Teknokrat Indonesia</td>
</tr>
<tr class='batas2' align='left'>
  <td >&nbsp;</td>
  <td >2. Program Studi</td>
  <td >&nbsp;: $un3[Nama]</td>
</tr>

<tr class='batas2' align='left'>
  <td align=center>4.</td>
  <td colspan='2'><b>PENILAIAN</b></td>
  <td>&nbsp;</td>
</tr>
<tr class='batas2' align='left'>
  <td>&nbsp;</td>
<td colspan='3'>
<br>
  <table width='99%' border='0.1' align='center' cellpadding='0' cellspacing='0' class='keliling'>  
    <tr>
      <td colspan='2' align=center><b>UNSUR YANG DINILAI</b></td>
      <td colspan='3' align=center>&nbsp;</td>
      </tr>
    <tr>
      <td align=center>&nbsp;</td>
      <td align='left' class='batas2'>&nbsp;</td>
      <td align='center'><b>ANGKA</b></td>
      <td width='130' align='center'><b>SEBUTAN</b></td>
      <td width='100' align='center'><b>KETERANGAN</b></td>
    </tr>
    <tr>
      <td   width='30' align=center>a.</td>
      <td  width='230' align='left' class='batas2'>Kesetiaan</td>
      <td width='120' align='center'>&nbsp;$p1[Kesetiaan]</td>
      <td align='center'>&nbsp;$prediketKesetiaan</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td  class='batas2' align=center>b. </td>
      <td  class='batas2' >Prestasi Kerja</td>
      <td width='120' align='center'>&nbsp;$p1[PrestasiKerja]</td>
      <td align='center'>&nbsp;$prediket</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td  class='batas2' align=center>c. </td>
      <td  class='batas2' >Tanggung Jawab</td>
      <td width='120' align='center'>&nbsp;$p1[TanggungJawab]</td>
      <td align='center'>&nbsp;$prediketTanggungJawab</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td  class='batas2' align=center>d. </td>
      <td  class='batas2'>Ketaatan</td>
      <td align='center'> &nbsp;$p1[Ketaatan]</td>
      <td align='center'>&nbsp;$prediketKetaatan</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align=center>e.</td>
      <td>Kejujuran</td>
      <td align='center'>&nbsp;$p1[Kejujuran]</td>
      <td align='center'>&nbsp;$prediketKejujuran</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align=center>f.</td>
      <td>Kerjasama</td>
      <td align='center'>&nbsp;$p1[Kerjasama]</td>
      <td align='center'>&nbsp;$prediketKerjasama</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align=center>g.</td>
      <td>Prakarsa</td>
      <td align='center'>&nbsp;$p1[Prakarsa]</td>
      <td align='center'>&nbsp;$prediketPrakarsa</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align=center>h.</td>
      <td>Kepemimpinan</td>
      <td align='center'>&nbsp;$p1[Kepemimpinan]</td>
      <td align='center'>&nbsp;$prediketKepemimpinan</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align=center>i.</td>
      <td>JUMLAH</td>
      <td align=center>$angka</td>
      <td align='center'>&nbsp;$prediket</td>
      <td align='center'>&nbsp;</td>
    </tr>
    <tr>
      <td align=center>j.</td>
      <td>NILAI RATA-RATA</td>
      <td align=center>$rata</td>
      <td align='center'>&nbsp;$prediket</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <br>
  </td>

  </tr>
</table>

<table  border='0' align='center' cellpadding='0' cellspacing='0' class='keliling'>
<tr class='batas2' ><td style=text-align:center>&nbsp;</td></tr>
<tr class='batas2' ><td style=text-align:center;width:700px><b>R A H A S I A</b></td></tr>
</table>
";        

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
