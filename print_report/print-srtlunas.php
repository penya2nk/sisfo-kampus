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
//$pdf->SetFont('Arial','B',50);
$content   		= ob_get_clean();
$mhs        	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mhsw where MhswID='".strfilter($_GET[IDX])."'"));
$keu        	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM keuangan_bayar where MhswID='".strfilter($_GET[MhswID])."'"));
$ProgramID 		= $dt[ProgramID];

$NamaMhsx 		= strtolower($mhs[Nama]); //strtoupper($kalimat);
$NamaMhs		= ucwords($NamaMhsx);

$Alamatx 		= strtolower($mhs[Alamat]); //strtoupper($kalimat);
$Alamat			= ucwords($Alamatx);

$TempatLahirx 	= strtolower($mhs[TempatLahir]); //strtoupper($kalimat);
$TempatLahir	= ucwords($TempatLahirx);

$tgllahir  = tgl_indo($mhs[TanggalLahir]);
$program   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProgramID,Nama FROM program where ProgramID='$ProgramID'"));
$prodi     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='".strfilter($_GET[prodi])."'"));

$bln	=date('m');
$tahun=date('Y');
if($bln=='1'){$BlnRomawi="I";}elseif($bln=='2'){$BlnRomawi="II";} elseif($bln=='3'){$BlnRomawi="III";} elseif($bln=='4'){$BlnRomawi="IV";} elseif($bln=='5'){$BlnRomawi="V";}
elseif($bln=='6'){$BlnRomawi="VI";} elseif($bln=='7'){$BlnRomawi="VII";} elseif($bln=='8'){$BlnRomawi="VIII";} elseif($bln=='9'){$BlnRomawi="IX";} elseif($bln=='10'){$BlnRomawi="X";}
elseif($bln=='11'){$BlnRomawi="XI";}else{{$BlnRomawi="XII";}}
$NoSurat      = "04/STMIK-HTP/$BlnRomawi/$tahun";	



if ($_GET[prodi]=='SI'){
   $noreg="1031230515011";
}else{
   $noreg="1031231111004";
}


$content .= "

<table width='700' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr style=font-size:18px;>
<td align='center '><b ><u>SURAT KETERANGAN </u></b></td>
</tr>
<tr style=font-size:15px;font-family:Arial;>
<td align='center' >No: $NoSurat/</td>
</tr>
</table>     
<br>
<br>

<table width='700' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr style=font-size:15px;font-family:Arial;>
  <td width='700' >Yang bertanda tangan di bawah ini kepala bagian keuangan dan kepegawaian Universitas Teknokrat Indonesia menerangkan bahwa:</td>
</tr>
</table>
<br>
<table width='700' border='0' cellpadding='0' cellspacing='0' align='center'>

<tr style=font-size:15px;font-family:Arial;>
  <td width='152' >Nama</td>
<td width='5' >:</td>       
<td width='580' > $NamaMhs</td>           
</tr>

<tr style=font-size:15px;font-family:Arial;>
  <td >NIM</td>
<td >:</td>            
<td >  $mhs[MhswID]</td>             
</tr>

<tr style=font-size:15px;font-family:Arial;>
  <td >Program Studi</td>
<td >:</td>            
<td >$prodi[Nama]</td>             
</tr>

<tr>
  <td></td>
<td></td>            
<td> $nbsp</td>             
</tr>
</table>
<br>

<table width='700' border='0' cellpadding='0' cellspacing='0' align='justify'>
  <tr style=font-size:15px;font-family:Arial;>
    <td width='630' align='justify' >Telah melakukan pembayaran kewajiban sebagai mahasiswa program studi $prodi[Nama] dengan rincian sebagai berikut: </td>
  </tr>
</table>
<br>
<table width='700' border='0.1' cellpadding='0' cellspacing='0' align='center'>
  <tr style=font-size:15px;font-family:Arial;background-color:#E7EAEC'>
    <td width='40' align='center' height='20'><b>No</b></td>
    <td width='180' ><b>Uraian</b></td>
    <td width='80' align='center'><b> Semester </b></td>
    <td width='100' align='center'> <b>Tanggal </b></td>
    <td width='100' align='right'> <b>Jumlah </b></td>
  </tr>";
  $sqls   = mysqli_query($koneksi, "SELECT * FROM keuangan_bayar where MhswID='".strfilter($_GET[IDX])."' order by TahunID,id_jenis ASC");
  while($dtkeu=mysqli_fetch_array($sqls)){
    $tot += $dtkeu[total_bayar];
    $nom++;
    $content .= "<tr style=font-size:15px;font-family:Arial;>
    <td align='center'>$nom</td>
    <td > $dtkeu[id_jenis] </td>
    <td align='center'> $dtkeu[TahunID] </td>
    <td align='center'> ".date('d-m-Y', strtotime($dtkeu[TanggalBayar]))." </td>
    <td align='right'>".number_format($dtkeu[total_bayar])."</td>
    </tr>";
  }
  $content .= "<tr style=font-size:15px;font-family:Arial;>
  <td colspan='4' align='center'><b>Total</b></td><td align='right' ><b>".number_format($tot)."</b></td></tr>";
  $content .= "</table>

<table width='700' border='0' cellpadding='0' cellspacing='0' align='justify'>
<tr style=font-size:15px;font-family:Arial;>
<td width='630' align='justify'>&nbsp;
</td>
  </tr>  
  
    <tr style=font-size:15px;font-family:Arial;>
    <td width='630' align='justify' >Untuk Mahasiswa tersebut diatas telah melunasi dan melaksanakan  kewajiban : LUNAS.
    Demikian Keterangan ini dibuat, Untuk dipergunakan sebagaimana mestinya.
</td>
  </tr>
</table>
<br>
<br>
<br>

<table border=0 width='700' align='center' cellpadding='0' cellspacing='0' >
<tr style=font-size:15px;font-family:Arial;>
<td width='330' >&nbsp;</td>
<td width='120' >Dikeluarkan di</td>
<td width='200' >: Pekanbaru</td>
</tr>
<tr style=font-size:15px;font-family:Arial;text-align:left;>
<td>&nbsp;</td>
<td>Pada Tanggal</td>
<td>: ".tgl_indo(date('Y-m-d'))."</td>
</tr>

<tr style=font-size:15px;font-family:Arial;text-align:left;>
  <td align='left'></td>
  <td colspan='3'>----------------------------------------------</td>
</tr>

<tr>
  <td align='left'></td>
  <td colspan='3' align='left' style=font-size:15px;font-family:Arial;>Ka Bag Keuangan dan Kepegawaian</td>
</tr>

<tr>
  <td align='left'></td>
  <td colspan='3' align='left' >&nbsp;</td>
</tr>
<tr>
  <td align='left'></td>
  <td colspan='3' align='left' >&nbsp;</td>
</tr>
<tr>
  <td align='left'></td>
  <td colspan='3' align='left' >&nbsp;</td>
</tr>
<tr>
<td align='left'></td>
<td colspan='3' align='left' style=font-size:15px;font-family:Arial;>Zupri Hendra Hartomi, M.Kom</td>
</tr>

<tr>
<td align='left'></td>
<td colspan='3'>----------------------------------------------------</td>
</tr>

<tr>
<td align='left'></td>
<td colspan='3' align='left' style=font-size:15px;font-family:Arial;>No Reg: 1031230210172</td>
</tr>
</table>";


try
	{
		$html2pdf = new HTML2PDF('P','Legal','en', false, 'ISO-8859-15',array(22, 10,15, 10)); //setting ukuran kertas dan margin pada dokumen anda
		// $html2pdf->setModeDebug();
		$html2pdf->setDefaultFont('Arial');
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output($filename);
	}
	catch(HTML2PDF_exception $e) { echo $e; }
	
}
?>	