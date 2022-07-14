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

$dt  			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jadwal_kp where JadwalID='".strfilter($_GET[JadwalID])."'"));
$namamhs 		= $dt[Nama];
$nama_kecilmhs 	= strtolower($namamhs);
$namamahasiswa	= ucwords($nama_kecilmhs);	

$judulx 		= $dt[Judul];
$judul_kecil 	= strtolower($judulx);
$Judul			= ucwords($judul_kecil);

$dos   			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar,NIDN FROM dosen where Login='$dt[DosenID]'"));
$namarien 		= $dos[Nama];
$nama_kecild 	= strtolower($namarien);
$pembimbing1	= ucwords($nama_kecild);	

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
    <td width='254'>Permohonan Pembimbingan Kerja Praktek</td>
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
    <td colspan='3'>Dosen Pembimbing Kerja Praktek</td>
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
    <td colspan='3'>Bersama  dengan ini kami mohon Bapak/Ibu untuk memberikan Bimbingan Kerja Praktek </td>
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
    <td width='140'>Kelompok</td>
    <td width='13'>:</td>
    <td width='491'>$dt[KelompokID]</td>
  </tr>";
  	$mhs = mysqli_query($koneksi, "SELECT
			jadwal_kp_anggota.JadwalID,jadwal_kp_anggota.MhswID,jadwal_kp_anggota.KelompokID,
			mhsw.Nama FROM mhsw,jadwal_kp_anggota 
			WHERE mhsw.MhswID=jadwal_kp_anggota.MhswID 
			AND jadwal_kp_anggota.JadwalID='".strfilter($_GET[JadwalID])."'");	
	while($m=mysqli_fetch_array($mhs)){
	$a++;
	$Namag 		= strtolower($m[Nama]);
	$NamaMhs	= ucwords($Namag);
	$content .=" <tr class='batas2' align='left'>
	 <td>&nbsp;</td>
    <td>&nbsp;</td>
	<td align=right></td>
	<td >:</td><td >$a. $m[MhswID] - $NamaMhs</td>
	</tr>";
	}
  $content .="
  
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
  <td align='center' >dto</td>
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

<table border=0>
<tr>
<td width='30'></td>
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