<?php 
session_start();
error_reporting(0);
include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

if (empty($_SESSION['_Login']) && empty($_SESSION['_LevelID'])){
	header("Location: ../login.php");
}
else{

include "headerx-rpt.php"; 
$tgl 	   	= tgl_indo(date('Y-m-d'));
$pl  		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Noreg,Judul,Pelaksana,Tempat,TanggalMulai,TanggalSelesai,NoSurat,MaksudTujuan FROM t_simpegpelatihan 
											WHERE IDPel='".strfilter($_GET[IDPel])."'"));
$dt  		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Noreg,Nama,Gelar,Jabatan FROM t_simpegpegawai WHERE Noreg='$pl[Noreg]'"));

$namax 		= strtolower($dt[Nama]);
$namaK		= ucwords($namax);	

$bln	=date('m');
$tahun	=date('Y');
if($bln=='1'){$BlnRomawi="I";}elseif($bln=='2'){$BlnRomawi="II";} elseif($bln=='3'){$BlnRomawi="III";} elseif($bln=='4'){$BlnRomawi="IV";} elseif($bln=='5'){$BlnRomawi="V";}
elseif($bln=='6'){$BlnRomawi="VI";} elseif($bln=='7'){$BlnRomawi="VII";} elseif($bln=='8'){$BlnRomawi="VIII";} elseif($bln=='9'){$BlnRomawi="IX";} elseif($bln=='10'){$BlnRomawi="X";}
elseif($bln=='11'){$BlnRomawi="XI";}else{{$BlnRomawi="XII";}}

//$day = date('D', strtotime($tanggal));
$tanggalm 	= $pl[TanggalMulai];
$tanggals 	= $pl[TanggalSelesai];
$day 		= date('D', strtotime($tanggalm));
$day2 		= date('D', strtotime($tanggals));

$dayList = array(
	'Sun' => 'Minggu',
	'Mon' => 'Senin',
	'Tue' => 'Selasa',
	'Wed' => 'Rabu',
	'Thu' => 'Kamis',
	'Fri' => 'Jumat',
	'Sat' => 'Sabtu'
);

if ($tanggalm==$tanggals){
    $tglKegiatan=$dayList[$day].", ". tgl_indo($pl[TanggalMulai]);
}else{
    $tglKegiatan=$dayList[$day].", ". tgl_indo($pl[TanggalMulai]) ." s/d ". $dayList[$day2].", ".tgl_indo($pl[TanggalSelesai]);
}


$content .= "


<table  border='0' cellpadding='0' cellspacing='0' align='center'>
  <tr>
    <td  align='center'><u><b>SURAT PERINTAH TUGAS</b></u><br>Nomor: 05/STMIK-HTP/$BlnRomawi/$tahun/$pl[NoSurat]</td>
  </tr>
</table>
<br>
<br>
<table >  
  <tr>
    <td colspan='5'>&nbsp;</td>
  </tr>
  <tr>
    <td colspan='5'>Yang bertanda tangan dibawah ini Ketua STMIK  Hang Tuah Pekanbaru dengan ini memberi </td>
  </tr>
  <tr>
    <td colspan='5'>tugas kepada :</td>
  </tr>
  <tr>
    <td colspan='5'>&nbsp;</td>
  </tr>
  <tr>
    <td width='18'>&nbsp;</td>
    <td width='18'>1.</td>
    <td width='150'>Nama</td>
    <td width='7'>:</td>
    <td width='500'>$namaK, $dt[Gelar]</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>No Reg</td>
    <td>:</td>
    <td>$dt[Noreg]</td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Jabatan</td>
    <td>:</td>
    <td>$dt[Jabatan]</td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>2.</td>
    <td>Maksud dan Tujuan</td>
    <td>:</td>
    <td><textarea cols=45 rows=2 >$pl[MaksudTujuan]</textarea></td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>3.</td>
    <td>Hari/Tanggal</td>
    <td>:</td>
    <td>$tglKegiatan </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>4.</td>
    <td>Tempat</td>
    <td>:</td>
    <td>$pl[Tempat]</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
  </tr>
  <tr>
    <td colspan='5'>Demikian surat tugas ini kami buat, agar dapat  dilaksanakan sebagaimana mestinya.</td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<table  border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
  <td width='370' align='left'>&nbsp;</td>
  <td width='90' align='left'>Dikeluarkan di</td>
  <td width='10' align='left'>:</td>
  <td width='140' align='left'>PEKANBARU</td>
</tr>

<tr>
  <td align='left' >&nbsp;</td>
  <td align='left' ><u>Pada Tanggal</u></td>
  <td align='left' >:</td>
  <td align='left' ><u>".tgl_indo(date('Y-m-d'))."</u></td>
  </tr>

<tr>
  <td align='left' >&nbsp;</td>
  <td colspan='3' align='left' >Universitas Teknokrat Indonesia</td>
  </tr>

<tr>
  <td align='left' >&nbsp;</td>
  <td colspan='3' align='left' >Ketua</td>
  </tr>
  
<tr>
  <td align='left' >&nbsp;</td>
  <td align='left' ></td>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
</tr>
<tr>
  <td align='left' >&nbsp;</td>
  <td align='left' ></td>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  
  </tr>  
  
<tr>
  <td align='left' >&nbsp;</td>
  <td align='left' ></td>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  </tr>
<tr>
  <td align='left' ></td>
  <td colspan='3' align='left' ><u>Hendry Fonda, S.Kom, M.Kom</u></td>
</tr>
<tr>
  <td align='left' ></td>
  <td colspan='3' align='left' >No Reg: 1031230808001</td>
</tr>
</table>
<br>
<br>
<br>

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

<br>
<br>
<br>
<br>
<br>
<br>";

$content .="<table border=0>
<tr>
<td width='0'></td>
<td width='300'><font style='font-size:8px;'>Login by: $_SESSION[_Login] ".tgl_indo(date('Y-m-d'))." ".date('H:i:s') . " WIB - Univ Tekno Indo Support System</font></td>
</tr>
</table>";
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