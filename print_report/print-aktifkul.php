<?php 
session_start();
error_reporting(0);
include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";

if (empty($_SESSION['_Login']) && empty($_SESSION['_LevelID'])){
	header("Location: ../login.php");
}
else{
//$pdf->SetFont('Arial','B',50);

include "headerx-rpt2.php";

$dt        		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM vw_aktifkul where MhswID='".strfilter($_GET['MhswID'])."' and TahunID='$_SESSION[tahun_akademik]'"));
$ProgramID 		= $dt['ProgramID'];

$NamaMhsx 		= strtolower($dt['Nama']);
$NamaMhs		= ucwords($NamaMhsx);

$Alamatx 		= strtolower($dt['Alamat']);
$Alamat			= ucwords($Alamatx);

$TempatLahirx 	= strtolower($dt['TempatLahir']);
$TempatLahir	= ucwords($TempatLahirx);

$tgllahir  = tgl_indo($dt['TanggalLahir']);
$program   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProgramID,Nama FROM program where ProgramID='$ProgramID'"));
$prodi     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat FROM prodi where ProdiID='".strfilter($_GET['prodi'])."'"));

if ($_GET['prodi']=='SI'){
   $noreg="1031230515011";
}else{
   $noreg="1031231111004";
}


?>  
<!DOCTYPE html>
<html>
  <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <title></title>

  	<link rel="icon" type="image/png" href="asset/images/favicon1.png"/>
    <style>
    table {
        border-collapse: collapse;
    }
    thead > tr{
      background-color: #0070C0;
      color:#f1f1f1;
    }
    thead > tr > th{
      background-color: #0070C0;
      color:#fff;
      padding: 2px;
      border-color: #fff;
    }
    th, td {
      padding: 2px;
    }

    th {
        color: #222;
    }
    body{
      font-family:Calibri;
      font-size:17px;
      margin-top: 35px;
      margin-bottom: 35px;
      margin-right:35px;
      margin-left: 35px;
    }
    </style>
  </head>
  
  <body onload="window.print();">
<h4 align="center" style="margin:0px;font-size:20px;"><u><b>SURAT KETERANGAN AKTIF KULIAH</u></b></h4>
    <center>Nomor: <?php echo $dt['TextSurat'] ?></center>
    <br>
    <br>
    

 <table width="100%" border="0">
      <tr>
  <td >Saya yang bertanda tangan di bawah ini:</td>
</tr>
</table>
<table width='700' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
  <td width='152' >Nama</td>
<td width='5' >:</td>       
<td width='580' > <?php echo  $prodi['Pejabat'] ?></td>           
</tr>

<tr style=font-size:15px;font-family:Arial;>
  <td >Jabatan</td>
<td >:</td>            
<td > Ka. Prodi <?php echo $prodi['Nama'] ?></td>             
</tr>
</table>
<br>
<table align='justify'>
  <tr>
    <td width='630' align='justify' >Dengan ini menerangkan bahwa: </td>
  </tr>
</table>
<table >
  <tr >
    <td width='152' >Nama Mahasiswa</td>
    <td width='5' >:</td>
    <td width='580' > <?php echo $NamaMhs ?></td>
  </tr>
  
  <tr>
    <td width='152' >NIM</td>
    <td width='5' >:</td>
    <td width='580' > <?php echo $dt['MhswID'] ?> </td>
  </tr>
  
  <tr>
    <td>Program Studi</td>
    <td> :</td>
    <td><?php echo $prodi['Nama'] ?></td>
  </tr>
  
   <tr>
    <td >Tempat/Tanggal lahir</td>
    <td >:</td>
    <td > <?php echo $TempatLahir ?> /  <?php echo $tgllahir ?></td>
  </tr>
  
   <tr>
    <td >Alamat</td>
    <td >:</td>
    <td > <?php echo $Alamat ?> </td>
  </tr> 
</table>
<br>

<table align='justify'>
  <tr>
    <td width='630' align='justify' >Adalah <u>benar</u> mahasiswa Universitas Hang Tuah Pekanbaru Program Studi <?php echo $prodi['Nama'] ?> dan aktif  kuliah di Semester <?php echo $dt['Semester'] ?> 
    TA <?php echo   $_SESSION['tahun_akademik'] ?>

</td>
</tr>

<tr>
<td width='630' align='justify'>&nbsp;
</td>
  </tr>  
  
    <tr>
    <td width='630' align='justify' >Demikianlah Surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
</td>
  </tr>
</table>
<br>
<br>

<table >
<tr>
<td width='330' >&nbsp;</td>
<td width='120' >Dikeluarkan di</td>
<td width='200' >: Pekanbaru</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>Pada Tanggal</td>
<td>: <?php echo "".tgl_indo(date('Y-m-d')).""  ?></td>
</tr>

<tr>
  <td align='left'></td>
  <td colspan='3'>----------------------------------------------</td>
</tr>

<tr>
  <td align='left'></td>
  <td colspan='3' align='left' >Ketua Prodi <?php echo $prodi['Nama'] ?></td>
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
  <td colspan='3' align='left' >&nbsp;</td>
</tr>

<tr>
<td align='left'></td>
<td colspan='3' align='left'><?php  echo $prodi[Pejabat]  ?></td>
</tr>

<tr>
<td align='left'></td>
<td colspan='3'>----------------------------------------------------</td>
</tr>

<tr>
<td align='left'></td>
<td colspan='3' align='left'>No Reg: <?php echo $noreg ?></td>
</tr>
</table>


<?php 
}
?>
	