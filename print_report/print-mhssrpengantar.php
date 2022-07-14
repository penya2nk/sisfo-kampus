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
$tgl 	   = tgl_indo(date('Y-m-d'));

$dt  			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM t_penelitian where IDPenelitian='".strfilter($_GET[IDPenelitian])."'"));

$judulx 		= $dt[Judul];
$judul_kecil 	= strtolower($judulx);
$Judul			= ucwords($judul_kecil);	

$dos   			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar,NIDN FROM dosen where Login='$dt[PembimbingSkripsi1]'"));
$namarien 		= $dos[Nama];
$nama_kecild 	= strtolower($namarien);
$pembimbing1	= ucwords($nama_kecild);

$mhs   			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,MhswID,Nama,ProdiID,ProgramID,Handphone FROM mhsw where MhswID='$dt[MhswID]'"));
$nama_kecimhs 	= strtolower($mhs[Nama]);
$NamaMhs		= ucwords($nama_kecimhs);	

//08/Prodi-TI/STMIK-HTP/III/2016/
$ProdiID   	= $dt[ProdiID];
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

// cadangan =$KD/$KDPROD/STMIK-HTP/$BlnRomawi/$tahun/
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
    <td width='10'>Nomor</td>
    <td width='10'>:</td>
    <td width='254'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/STMIK-HTP/$BlnRomawi/$tahun </td>
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
    <td width='254'>Permohonan Penelitian</td>
    <td width='105'>&nbsp;</td>
    <td width='329'>&nbsp;</td>
  </tr>
</table>
<br>
<table width='800'  >
  <tr>
    <td width='79'>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan='3'>Kepada Yth, </td>
  </tr>
  <tr>
    <td width='79'>&nbsp;</td>
    <td width='10'>&nbsp;</td>
    <td colspan='3'>$dt[Ke]</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan='3'>di </td>
  </tr>
  <tr>
    <td width='79'>&nbsp;</td>
    <td width='10'>&nbsp;</td>
    <td colspan='3'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$dt[Kota]</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan='3'>&nbsp;</td>
  </tr>
  <tr>
    <td width='79'>&nbsp;</td>
    <td width='10'>&nbsp;</td>
    <td colspan='3'>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan='3'>Dengan hormat,</td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan='3'><p>Ketua  Universitas Teknokrat Indonesia, dengan ini memberikan surat pengantar kepada </p></td>
  </tr>
  <tr>
    <td width='79'>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan='3'>Mahasiswa</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan='3'>&nbsp;</td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td width='140'>Nama</td>
    <td width='13' >:</td>
    <td width='491' >$NamaMhs</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>NIM</td>
    <td>:</td>
    <td>$mhs[MhswID]</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Program Studi</td>
    <td>:</td>
    <td>$prod</td>
  </tr>
     <tr style=font-size:15px;font-family:Arial;>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>No. Handphone</td>
    <td>:</td>
    <td>$dt[Handphone]</td>
  </tr>
    <tr>
    <td  vlign='top'>&nbsp;</td>
    <td  vlign='top'>&nbsp;</td>
    <td vlign='top' align=justify colspan=3><textarea cols=58 rows=3>Judul Penelitian: $Judul</textarea></td>
  </tr>
 
   <tr>
     <td  vlign='top'>&nbsp;</td>
     <td  vlign='top'>&nbsp;</td>
     <td  vlign='top'>&nbsp;</td>
     <td  vlign='top'>&nbsp;</td>
     <td >&nbsp;</td>
   </tr>
   <tr>
     <td  vlign='top'>&nbsp;</td>
     <td  vlign='top'>&nbsp;</td>
     <td colspan='3' vlign='top'>Untuk melakukan Penelitian di Instansi/Perusahaan yang Bapak/Ibu pimpin. Sehubung-<br>an dengan hal tersebut, kami  mohon kiranya Bapak/Ibu dapat memberikan izin dan <br>bantuannya kepada yang  bersangkutan.</td>
   </tr>
   <tr>
     <td  vlign='top'>&nbsp;</td>
     <td  vlign='top'>&nbsp;</td>
     <td  vlign='top'>&nbsp;</td>
     <td  vlign='top'>&nbsp;</td>
     <td >&nbsp;</td>
   </tr>
   <tr>
    <td  vlign='top'>&nbsp;</td>
    <td  vlign='top'>&nbsp;</td>
    <td colspan='3'  vlign='top'>Demikianlah atas perhatian dan kerjasamanya kami ucapkan terimakasih.</td>
  </tr>
  <tr>
    <td  vlign='top'>&nbsp;</td>
    <td  vlign='top'>&nbsp;</td>
    <td colspan='3'  vlign='top'></td>
  </tr>
  </table>
<table>
<br>
<br>
</table>
<table border=0 width='800' align='left' cellpadding='0' cellspacing='0' >
<tr>
  <td align='center'>&nbsp;</td>
  <td align='center'>&nbsp;</td>
</tr>

<tr>
  <td width='300' align='center'>&nbsp;</td>
  <td width='300' align='center'>Universitas Teknokrat Indonesia</td>
</tr> 
<tr>
  <td width='300' align='center'>&nbsp;</td>
  <td width='300' align='center'>Ketua</td>
</tr>
<tr>
  <td align='center' >&nbsp;</td>
  <td align='center' >&nbsp;</td>
  </tr>

<tr>
  <td align='center' >&nbsp;</td>
  <td align='center' >&nbsp;</td>
</tr>
  
<tr>
  <td align='center' >&nbsp;</td>
  <td align='center' >&nbsp;</td>
  </tr>  
 
<tr>
  <td align='center' >&nbsp;</td>
  <td align='center' ><u>Hendry Fonda, S.Kom, M.Kom</u></td>
  </tr>



<tr>
  <td align='center'>&nbsp;</td>
  <td align='center'>NIDN. 1015027102</td>
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
<table border=0>
<tr>
<td width='2'></td>
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