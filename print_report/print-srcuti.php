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

$content   	= ob_get_clean();
$tgl 	   	= tgl_indo(date('Y-m-d'));
$pl  		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM t_simpegcuti 
											WHERE IDCuti='".strfilter($_GET[IDCuti])."'"));
$dt  		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Noreg,Nama,Gelar,Jabatan FROM t_simpegpegawai WHERE Noreg='$pl[Noreg]'"));
$namax 		= strtolower($dt[Nama]);
$namaK		= ucwords($namax);	

$pg  		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Noreg,Nama,Gelar,Jabatan FROM t_simpegpegawai WHERE Noreg='$pl[Pengganti]'"));
$namay 		= strtolower($pg[Nama]);
$namaP		= ucwords($namay);

$mg  		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Noreg,Nama,Gelar,Jabatan FROM t_simpegpegawai WHERE Noreg='$pl[Mengetahui]'"));
$namaz 		= strtolower($mg[Nama]);
$namaM		= ucwords($namaz);

//$s 			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT datediff('$pl[TanggalSelesai]','$pl[TanggalMulai]')as lama FROM t_simpegcuti 
//											WHERE IDCuti='$_GET[IDCuti]'"));
//$lamax 		= $s[lama] +1;

$lamax			= selisihHari($pl[TanggalMulai], $pl[TanggalSelesai]);
$lamax2			= $lamax +1;
$content .= "
<table  border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
  <td width='300' align='left'>Pekanbaru, ".tgl_indo(date('Y-m-d'))."</td>
  <td width='100' align='left'>&nbsp;</td>
  <td width='90' align='left'>&nbsp;</td>
  </tr>

<tr>
  <td align='left' ><u>PERMOHONAN IZIN CUTI</u></td>
  <td align='left' >&nbsp;</td>
  <td align='left' ></td>
</tr>
<tr>
  <td height='23' align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  <td align='left' ></td>
  </tr>
<tr>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  <td align='left' ><p>Kepada Yth :</p></td>
  </tr>  
  
<tr>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  <td align='left' >Bapak  Ketua STMIK Hang Tuah </td>
  </tr>
<tr>
  <td align='left' >&nbsp;</td>
  <td align='left' ></td>
  <td align='left' >Pekanbaru</td>
</tr>
<tr>
  <td align='left' ></td>
  <td align='left' ></td>
  <td align='left' >di</td>
</tr>
<tr>
  <td align='left' ></td>
  <td align='left' ></td>
  <td align='left' >Pekanbaru</td>
</tr>
</table>
<br>
<br>
<table >  
  <tr>
    <td colspan='5'>Dengan Hormat,</td>
  </tr>
  <tr>
    <td colspan='5'>Yang bertanda tangan dibawah ini :</td>
  </tr>
  <tr>
    <td colspan='5'>tugas kepada :</td>
  </tr>
  <tr>
    <td colspan='5'>&nbsp;</td>
  </tr>
  <tr>
    <td width='10'>&nbsp;</td>    
    <td width='40'>Nama</td>
    <td width='5'>:</td>
    <td width='200'>$namaK, $dt[Gelar]</td>
  </tr>
  <tr>    
    <td>&nbsp;</td>
    <td>No Reg</td>
    <td>:</td>
    <td>$dt[Noreg]</td>
  </tr>

  <tr>
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
    <td colspan='5'>Dengan ini mengajukan permohonan cuti tahunan untuk tahun 2018 selama $lamax2 hari, terhitung</td>
  </tr>
  <tr>
    <td colspan='5'>mulai tanggal ".tgl_indo($pl[TanggalMulai])." s/d ".tgl_indo($pl[TanggalSelesai])."</td>
  </tr>
  </table>
  
  <table>
  <tr>
    <td>Selama menjalankan cuti alamat saya di </td>
    <td width='0'>:</td>
    <td width='0'>$pl[Keberadaan]</td>
  </tr>
  <tr>
    <td >Sebagai  pengganti saya usulkan</td>
    <td>:</td>
    <td>$namaP, $pg[Gelar]</td>
  </tr>
  </table>
  
  <table>
  <tr>
    <td >Demikian  permohonan cuti ini saya buat untuk dapat dipertimbangkan sebagaimana mestinya.</td>
  </tr>
</table>

<br>
<br>
<br>
<br>
<table  border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
  <td width='200' align='left'>Karyawan  Pengganti,</td>
  <td width='200' align='left'>&nbsp;</td>
  <td width='90' align='left'>Hormat Saya</td>
  </tr>

<tr>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  <td align='left' ></td>
</tr>
<tr>
  <td height='23' align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  <td align='left' ></td>
  </tr>
<tr>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  <td align='left' ></td>
  </tr>  
  
<tr>
  <td align='left' ><u>$namaP, $pg[Gelar]</u></td>
  <td align='left' >&nbsp;</td>
  <td align='left' ><u>$namaK, $dt[Gelar]</u></td>
  </tr>
<tr>
  <td align='left' >No.Reg  : $pg[Noreg]</td>
  <td align='left' ></td>
  <td align='left' >No Reg: $dt[Noreg]</td>
</tr>
<tr>
  <td align='left' ></td>
  <td align='left' ></td>
  <td align='left' >&nbsp;</td>
</tr>
</table>
<br>
<table  border='1' cellpadding='0' cellspacing='0' align='center'>
  <tr>
    <td align='left' >Catatan Pejabat Kepegawaian</td>
    <td width='300' align='left'>Catatan Pertimbangan Atasan langsung</td>
  </tr>
  <tr>
    <td width='300' rowspan='2' align='left' ><p>Cuti yang telah diambil dalam tahun yang <br>besangkutan :</p>
      <table width='99%' border='0' cellspacing='0' cellpadding='0'>
        <tr>
          <td width='70%'>1. <u>Cuti  tahunan</u></td>
          <td width='16' align='left' >:</td>
          <td width='27%'>$lamax2 Hari</td>
        </tr>
        <tr>
          <td>2. Cuti besar</td>
          <td align='left' >:</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align='left' >3. Cuti  sakit</td>
          <td align='left' >:</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align='left' >4. Cuti bersalin</td>
          <td align='left' >:</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align='left'>5. Cuti karena alasan penting</td>
          <td align='left'>:</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align='left'>6. Keterangan lain-lain</td>
          <td align='left'> :</td>
          <td>&nbsp;</td>
        </tr>
      </table>
    <p>&nbsp;</p></td>
    <td align='left' ><table width='99%' border='0' cellspacing='0' cellpadding='0'>
      <tr>
        <td width='70%'>Mengetahui Atasan Langsung</td>
        </tr>
 
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><u>$namaM, $mg[Gelar]</u><br>$mg[Noreg]</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align='left' ><p>Keputusan pejabat yang berwenang<br>memberi cuti:</p>
    <p><br><u>Hendry Fonda, S.Kom, M.Kom</u><br>No.Reg : 1031230808001 </p></td>
  </tr>
</table>
<br>
<br>
<br>";

$content .="<table border=0>
<tr>
<td width='0'></td>
<td width='300'><font style='font-size:8px;'>Login by: $_SESSION[_Login] ".tgl_indo(date('Y-m-d'))." ".date('H:i:s') . " WIB - Univ Tekno Indo Support System</font></td>
</tr>
</table>";
try
{
	$html2pdf = new HTML2PDF('P','Letter','en', false, 'ISO-8859-15',array(25, 25, 25, 10));
	// $html2pdf->setModeDebug();
	$html2pdf->setDefaultFont('Arial');
	$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
	$html2pdf->Output($filename);
}
catch(HTML2PDF_exception $e) { echo $e; }
	
}
?>	