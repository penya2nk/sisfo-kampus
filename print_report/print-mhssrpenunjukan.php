<?php 
session_start();
// error_reporting(0);
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
$tgl 	   = tgl_indo(date('Y-m-d'));

$dt  			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM t_penelitian where IDPenelitian='".strfilter($_GET['IDPenelitian'])."'"));

$judulx 		= $dt['Judul'];
$judul_kecil 	= strtolower($judulx);
$Judul			= ucwords($judul_kecil);

$dos   			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar,NIDN FROM dosen where Login='$dt[Pembimbing1]'"));
//$namarien 		= $dos[Nama];
$nama_kecild 	= strtolower($dos['Nama']);
$pembimbing1	= ucwords($nama_kecild);	

$mhs   			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,MhswID,Nama,ProdiID,ProgramID FROM mhsw where MhswID='$dt[MhswID]'"));
$nama_kecimhs 	= strtolower($mhs['Nama']);
$NamaMhs		= ucwords($nama_kecimhs);

//08/Prodi-TI/STMIK-HTP/III/2016/
$ProdiID   	= $dt['ProdiID'];
if ($ProdiID=='SI'){ 
	$prod	="Sistem Informasi"; 
	$kaprodi="Herianto, S.Kom, M.Kom"; 
	$KD		="07"; 
	$KDPROD	="Prodi-SI"; 
	$nidn	="1008068202";
	}
else{ 
	$prod="Teknik Informatika"; 
	$kaprodi="Yuda Irawan, S.Kom, M.TI"; 
	$KD		="08"; 
	$KDPROD	="Prodi-TI";
	$nidn	="1016079101";
}

$bln	=date('m');
$tahun	=date('Y');
if($bln=='1'){$BlnRomawi="I";}elseif($bln=='2'){$BlnRomawi="II";} elseif($bln=='3'){$BlnRomawi="III";} elseif($bln=='4'){$BlnRomawi="IV";} elseif($bln=='5'){$BlnRomawi="V";}
elseif($bln=='6'){$BlnRomawi="VI";} elseif($bln=='7'){$BlnRomawi="VII";} elseif($bln=='8'){$BlnRomawi="VIII";} elseif($bln=='9'){$BlnRomawi="IX";} elseif($bln=='10'){$BlnRomawi="X";}
elseif($bln=='11'){$BlnRomawi="XI";}else{{$BlnRomawi="XII";}}

$content .= "


<table width='800'  >
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width='78'>Nomor</td>
    <td width='10'>:</td>
    <td width='254'>$KD/$KDPROD/STMIK-HTP/$BlnRomawi/$tahun/  </td>
    <td width='105'>&nbsp;</td>
    <td width='329'>Pekanbaru, $tgl</td>
  </tr>
  <tr>
    <td width='78'>Lampiran</td>
    <td width='10'>:</td>
    <td width='254'>-</td>
    <td width='105'>&nbsp;</td>
    <td width='329'>&nbsp;</td>
  </tr>
  <tr>
    <td width='78'>Perihal</td>
    <td width='10'>:</td>
    <td width='254'>Permohonan Pembimbingan Skripsi</td>
    <td width='105'>&nbsp;</td>
    <td width='329'>&nbsp;</td>
  </tr>


  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br>
<table width='800'  >
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan='3'>Kepada</td>
  </tr>
  <tr>
    <td width='81'>&nbsp;</td>
    <td width='10'>&nbsp;</td>
    <td colspan='3'>Yth. Bapak/Ibu $pembimbing1, $dos[Gelar] </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan='3'>Dosen Pembimbing I  (Pembimbing Utama) Skripsi</td>
  </tr>
  <tr>
    <td width='81'>&nbsp;</td>
    <td width='10'>&nbsp;</td>
    <td colspan='3'>Program Studi $prod</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan='3'>&nbsp;</td>
  </tr>
  <tr>
    <td width='81'>&nbsp;</td>
    <td width='10'>&nbsp;</td>
    <td colspan='3'>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan='3'>Dengan Hormat,</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan='3'>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan='3'>Bersama  dengan ini kami mohon Bapak/Ibu untuk memberikan Bimbingan Skripsi </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan='3'>kepada Mahasiswa  di bawah ini :</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan='3'>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width='140'>Nama</td>
    <td width='13'>:</td>
    <td width='491'>$NamaMhs</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>NIM</td>
    <td>:</td>
    <td>$dt[MhswID]</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Program Studi</td>
    <td>:</td>
    <td>$prod</td>
  </tr>

  <tr>
    <td  vlign='top'>&nbsp;</td>
    <td  vlign='top'>&nbsp;</td>
    <td width='400' vlign='top' align=justify colspan=3><textarea cols=56 rows=3>Judul Penelitian: $Judul</textarea></td>
  </tr>
  
   <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan='3'><p>Demikian atas perhatian serta kesediaan  Bapak/Ibu kami ucapkan terimakasih.</p></td>
  </tr>
</table>
<br>
<br>
<br>


<table border=0 width='734' align='left' cellpadding='0' cellspacing='0' >
<tr>
  <td width='209' align='center'>&nbsp;</td>
  <td width='525' align='center'>Program Studi $prod <br> Ketua</td>
</tr>

<tr>
  <td align='center' >&nbsp;</td>
  <td align='center' >&nbsp;</td>
  </tr>

<tr>
  <td align='center' >&nbsp;</td>
  <td align='center' >&nbsp;dto</td>
  </tr>

<tr>
  <td align='center' >&nbsp;</td>
  <td align='center' >&nbsp;</td>
</tr>
  
<tr>
  <td align='center' >&nbsp;</td>
  <td align='center' ><u>$kaprodi</u></td>
  </tr>  
  




<tr>
  <td align='center'>&nbsp;</td>
  <td align='center'>NIDN. $nidn</td>
</tr>
<tr>
  <td align='center'>&nbsp;</td>
  <td align='center'>&nbsp;</td>
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