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
$dt        		= mysqli_fetch_array(mysqli_query($koneksi, "select t_suratadm.*,mhsw.Nama,mhsw.Alamat,mhsw.TempatLahir,mhsw.TanggalLahir 
                        FROM t_suratadm,mhsw 
                        where t_suratadm.MhswID=mhsw.MhswID
                        and t_suratadm.MhswID='".strfilter($_GET['MhswID'])."'")); //and t_suratadm.TahunID='$_SESSION[tahun_akademik]'
$ProgramID 		= $dt['ProgramID'];

$NamaMhsx 		= strtolower($dt['Nama']); //strtoupper($kalimat);
$NamaMhs		= ucwords($NamaMhsx);

$Alamatx 		= strtolower($dt['Alamat']); //strtoupper($kalimat);
$Alamat			= ucwords($Alamatx);

$TempatLahirx 	= strtolower($dt['TempatLahir']); //strtoupper($kalimat);
$TempatLahir	= ucwords($TempatLahirx);

$tgllahir  = tgl_indo($dt['TanggalLahir']);
$program   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProgramID,Nama FROM program where ProgramID='$ProgramID'"));
$prodi     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama,Pejabat,Noreg FROM prodi where ProdiID='".strfilter($_GET['prodi'])."'"));

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
      /* font-size:17px; */
      margin-top: 35px;
      margin-bottom: 35px;
      margin-right:60px;
      margin-left: 60px;
    }
    </style>
  </head>
  
  <body onload="window.print();">
 

    <?php 
    include "headerx-rpt.php";
    ?> 
    <h4 align="center" style="margin:0px;font-size:20px;"><u><b>SURAT KETERANGAN LULUS </u></b></h4>
    <center>Nomor: <?php echo $dt['TextSurat'] ?></center>
    <br>
    <br>

      <br>
      <table width="100%" border="0">
      <tr>
      <td width="130" colspan="4" style="text-align:justify">Dengan hormat, <br>Ketua Program Studi <?php echo $prodi['Nama'] ?> Universitas Teknokrat Indonesia, dengan ini menerangkan bahwa:</td>
      </tr>
      <tr>
      <td width="35">&nbsp;</td>
      <td width="200">NIM </td>
      <td width="1">:</td>
      <td><?php echo $dt['MhswID'] ?></td>
      </tr>

      <tr>
      <td >&nbsp;</td>
      <td>Nama Mahasiswa </td>
      <td>:</td>
      <td><?php echo $dt['Nama'] ?></td>
      </tr>

      <tr>
      <td >&nbsp;</td>
      <td>Tempat dan Tanggal Lahir </td>
      <td>:</td>
      <td><?php echo $TempatLahir; ?>, <?php echo $tgllahir ?></td>
      </tr>

      <tr>
      <td >&nbsp;</td>
      <td valign="top">Alamat </td>
      <td valign="top">:</td>
      <td><?php echo $dt['Alamat']; ?></td>
      </tr>

<?php 
$tgl = date('d-M-Y', strtotime($dt['Tanggal']));
?>
      <tr>
      <td width="150" colspan="4" style="text-align:justify">Berdasarkan Keputusan Panitia Ujian Sidang Sarjana Program Studi <?php echo $prodi['Nama'] ?>,
      pada tanggal <?php echo $tgl ?> 
      dinyatakan <b>Lulus Ujian Sidang Sarjana</b>, dan berhak menyandang gelar <b>Sarjana Komputer (S.Kom)</b>.  
      
      <br>
      <br>
      Keterangan ini diberikan sehubungan ijazah yang bersangkutan sedang dalam proses penyelesaian.
      <br>
      <br>
      Demikianlah surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
      </td>
      </tr>


    </table>
    <br> 
    <br> 
    <br> 

    <div style="float:right;">
      <center>
      Ditetapkan di: Pekanbaru <br>
			Pada Tanggal : <?php echo date('d-m-Y') ?> <br>
      --------------------------------------<br>
      Ketua Prodi <?php echo $prodi['Nama'] ?>
      <br>
      <br>
      <br>      
      <?php echo $prodi['Pejabat'] ?><br>
      --------------------------------------<br>
      No Reg:  <?php echo $prodi['Noreg'] ?><br>
    
      </center>
    </div>

    <div style="float:right;">
      <center>
     
      </center>
    </div>  
<br>
<br>

  </body>
</html>

<?php 
}
?>