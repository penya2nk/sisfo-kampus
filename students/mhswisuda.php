<div class='card'>
<div class='card-header'>

<h3 class="box-title"><b style=color:#e62899;font-size:25px><?php echo"$prd";?> PENGISIAN ANGKET 
| <a href="index.php?view=mhsangketvisi">Angket Visi Misi</a> 
| <a href="index.php?view=mhsangketpbm">Angket PBM</a> 
| <a href="index.php?view=mhsangketlayanan">Angket Layanan Akademik</a> |
</b> 
</h3>

</div>
</div>


    

<?php if ($_GET[act]==''){ ?>

<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example" class="table table-bordered table-striped">
<thead>
<tr>
  <th style='width:20px'>No<center>(1)</center></th>                           
  <th><div align='center'>NIM </div><center>(2)</center></th>            
  <th><div align='left'>Nama Mahasiswa<br><left>(3)</left></th>
  <th><div align='center'>IPK <br><center>(4)</center></th>
 <th><div align='left'>Predikat <br><left>(5)</left></th>
<th><div align='left'>Status <br><left>(6)</left></th> 
  <th width='450px'><div align='center'>Action <br><center>(7)</center></th> 
</tr>
</thead>
<tbody>
<?php
$sql = mysqli_query($koneksi, "SELECT * from t_yudisium where MhswID='$_SESSION[_Login]'
					order by t_yudisium.IPK desc");	
$no=1;
while ($r = mysqli_fetch_array($sql)){
$almt  = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MhswID,Alamat,Foto FROM mhsw WHERE MhswID='$r[MhswID]'"));
 

echo "<tr><td align='center'>$no</td>                  
        <td align='center'><a href='index.php?view=mhswisuda&act=profillulusan&MhswID=$r[MhswID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'>$r[MhswID]</a></td>
        <td align='left'>$r[Nama]</td>
        <td align='center'>$r[IPK]</td>
		<td align='left'>$r[Predikat]</td>
		<td align='left'>$r[Status]</td>";                            
echo "<td style='width:70px !important'><center>
<a class='btn btn-success btn-xs' title='Edit' href='index.php?view=mhswisuda&act=aplodpoto&prodi=$_GET[prodi]&tahun=$r[TahunID]&IDYudisium=$r[IDYudisium]'>
<span class='glyphicon glyphicon-edit'></span></a>

<a  class='btn btn-success btn-xs' href='index.php?view=mhswisuda&act=aplodpoto&IDYudisium=$r[IDYudisium]&prodi=$_GET[prodi]&tahun=$r[TahunID]'>
<span class='glyphicon glyphicon-th'></span> Upload Photo</a>

<a  class='btn btn-success btn-xs' href='index.php?view=mhswisuda&act=aplodfilebyr&IDYudisium=$r[IDYudisium]&prodi=$_GET[prodi]&tahun=$r[TahunID]'>
<span class='glyphicon glyphicon-th'></span> Upload Bukti Lunas Wisuda</a>

<a  class='btn btn-warning btn-xs' target='_BLANK' href='print_report/print-buktiwisuda.php?tahun=$r[TahunID]&MhswID=$_SESSION[_Login]'>
<span class='glyphicon glyphicon-book'></span> Cetak Bukti</a>


</center>
</td>
</tr>";
$no++;
}

if (isset($_GET[hapus])){
mysqli_query($koneksi, "DELETE FROM xx where IDYudisium='".strfilter($_GET[hapus])."'");
echo "<script>document.location='index.php?view=mhswisuda&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
}
;
?>
<tbody>
</table>
</div>
</div>
</div>


<?php 
}

elseif($_GET[act]=='aplodpoto'){
$j = mysqli_fetch_array(mysqli_query($koneksi, "select * from t_yudisium where IDYudisium='".strfilter($_GET[IDYudisium])."'"));
$m = mysqli_fetch_array(mysqli_query($koneksi, "select MhswID,Foto,Nama,ProdiID,ProgramID from mhsw where MhswID='$_SESSION[_Login]'"));
if (isset($_POST[ubahx])){										
	  $filename 	= basename($_FILES['ax']['name']);
	  if ($filename != ''){      
		    $ekstensi_diperbolehkan	= array('png','jpg');
			$nama 		= date("YmdHis").'-'.basename($_FILES['ax']['name']);
			$x 			= explode('.', $nama);
			$ekstensi 	= strtolower(end($x));
			$ukuran		= $_FILES['ax']['size'];
			$file_tmp = $_FILES['ax']['tmp_name'];	

			if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
				if($ukuran < 55000){	 //1044070		
					move_uploaded_file($file_tmp, 'pto_stud/'.$nama);
					$queryx =mysqli_query($koneksi, "UPDATE mhsw set Foto='$nama' WHERE MhswID='$_SESSION[_Login]'");
                    echo "<script>document.location='index.php?ndelox=students/mhswisuda&act=aplodpoto&act=aplodpoto&sukses';</script>";
				}else{
					echo "<b style=color:red>Ukuran File terlalu besar, compress menjadi < 55000 Byte !</b>";
				}
			}else{
				echo "<b style=color:red>Ekstensi File yang diupload tidak diperbolehkan!</b>";
			}
	  }else{
		    $queryx = mysqli_query($koneksi, "UPDATE mhsw SET                           
						   Nama     	='".strfilter($_POST[Nama])."'
						   where MhswID ='$_SESSION[_Login]'");
		 	echo "<script>document.location='index.php?ndelox=students/mhswisuda&act=aplodpoto&act=aplodpoto&sukses';</script>";			   
	  }
	  

}

echo "
  <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
    <input type='hidden' name='tahun' value='$_GET[tahun]'>
    <input type='hidden' name='prodi' value='$_GET[prodi]'>	
    <input type='hidden' name='IDYudisium' value='$_GET[IDYudisium]'>	
    <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id='example' class='table table-sm table-stripedx'>
      <tbody>
	  <tr><th scope='row' width='100px'>NIM</th> <td><input type='text' class='form-control form-control-sm' name='x' value='$m[MhswID]' readonly></td></tr>
        <tr><th scope='row' >Nama Mahasiswa</th> <td><input type='text' class='form-control form-control-sm' name='Nama' value='$m[Nama]' readonly></td></tr>          
        <tr><th scope='row'>IPK </th> <td><input type='text' class='form-control form-control-sm' name='IPK' value='$j[IPK]' readonly></td></tr>
        <tr><th scope='row'> </th> <td style='background-color:#E7EAEC' width='460px' >";
			if (trim($m[Foto])==''){
			  echo "<img class='img-thumbnail' style='width:100px' src='pto_stud/no-image.jpg'>";
			}else{
			  echo "<img class='img-thumbnail' style='width:100px' src='pto_stud/$m[Foto]'>";
			}
			echo "</td>
		</tr>		
         <tr><th scope='row'>Ganti Foto <b style=color:green>(Kompress < 55000 Byte) <br> (Jenis File JPG/PNG)</b></th> <td><div style='position:relative;''>
			  <a class='btn btn-primary' href='javascript:;'>
				<span class='glyphicon glyphicon-search'></span> Browse..."; ?>
				<input type='file' class='files' name='ax' onchange='$("#upload-file-info").html($(this).val());'>
			  <?php echo "</a> <span style='width:155px' class='label label-info' id='upload-file-info'></span>
			</div>
		</td>
		</tr>
        
      </tbody>
      </table>
	  <div class='box-footer'>
        <button type='submit' name='ubahx' class='btn btn-info btn-sm'>Perbaharui Data</button>
        <a href='index.php?ndelox=students/mhswisuda&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-primary btn-sm'>Cancel</button></a                    
      </div>
    </div>
  </div>
   </div>
  
  </form>";
}


elseif($_GET[act]=='aplodfilebyr'){
$j = mysqli_fetch_array(mysqli_query("select * from t_yudisium where IDYudisium='".strfilter($_GET[IDYudisium])."'"));
$m = mysqli_fetch_array(mysqli_query("select MhswID,Foto from mhsw where MhswID='$_SESSION[_Login]'"));
if (isset($_POST[ubahx])){										
	  $filename 	= basename($_FILES['ax']['name']);
	  if ($filename != ''){      
		    $ekstensi_diperbolehkan	= array('png','jpg');
			$nama 		= date("YmdHis").'-'.basename($_FILES['ax']['name']);
			$x 			= explode('.', $nama);
			$ekstensi 	= strtolower(end($x));
			$ukuran		= $_FILES['ax']['size'];
			$file_tmp = $_FILES['ax']['tmp_name'];	

			if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
				if($ukuran < 55000){	 //1044070		
					move_uploaded_file($file_tmp, 'pile_wisuda/'.$nama);
				    $queryx = mysqli_query("UPDATE t_yudisium SET                           
						   Nama   	= '".strfilter($_POST[Nama])."',
						   Status  	= 'Menunggu Verifikasi',
						   file_upload 	= '$nama' 
						   where MhswID='$_SESSION[_Login]'");
				    echo "<script>document.location='index.php?view=mhswisuda&tahun=$_POST[tahun]&prodi=$_POST[prodi]&sukses';</script>";
				}else{
					echo "<b style=color:red>Ukuran File terlalu besar, Compres < 300 KB!</b>";
				}
			}else{
				echo "<b style=color:red>Ekstensi File yang diupload tidak diperbolehkan!</b>";
			}
	  }else{
		  $queryx = mysqli_query($koneksi, "UPDATE t_yudisium SET                           
						   Nama     	='".strfilter($_POST[Nama])."',
						   Status  	= 'Menunggu Verifikasi'
						   where MhswID ='$_SESSION[_Login]'");
		  echo "<script>document.location='index.php?view=mhswisuda&tahun=$_POST[tahun]&prodi=$_POST[prodi]&sukses';</script>";				   
	  }
	  
	  
}

echo "
  <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
    <input type='hidden' name='tahun' value='$_GET[tahun]'>
    <input type='hidden' name='prodi' value='$_GET[prodi]'>	
    <input type='hidden' name='IDYudisium' value='$_GET[IDYudisium]'>	
    <div class='col-md-12'>
      <table class='table table-condensed table-bordered'>
      <tbody>
	  <tr><th scope='row' width='140px'>NIM</th> <td><input type='text' class='form-control form-control-sm' name='x' value='$j[MhswID]' readonly></td></tr>
        <tr><th scope='row' >Nama Mahasiswa</th> <td><input type='text' class='form-control form-control-sm' name='Nama' value='$j[Nama]' readonly></td></tr>          
       
        <tr><th scope='row'> </th> <td style='background-color:#E7EAEC' width='460px' >";
			if (trim($j[file_upload])==''){
			  echo "<img class='img-thumbnail' style='width:500px' src='pile_wisuda/no-image.jpg'>";
			}else{
			  echo "<img class='img-thumbnail' style='width:500px' src='pile_wisuda/$j[file_upload]'>";
			}
			echo "</td>
		</tr>		
         <tr><th scope='row'>Upload Bukti Lunas Wisuda <b style=color:green>(Kompress < 55000 byte) <br> (Jenis File JPG/PNG)</b></th>             <td><div style='position:relative;''>
			  <a class='btn btn-primary' href='javascript:;'>
				<span class='glyphicon glyphicon-search'></span> Browse..."; ?>
				<input type='file' class='files' name='ax' onchange='$("#upload-file-info").html($(this).val());'>
			  <?php echo "</a> <span style='width:155px' class='label label-info' id='upload-file-info'></span>
			</div>
		</td>
		</tr>
        
      </tbody>
      </table>
    </div>
  </div>
  <div class='box-footer'>
        <button type='submit' name='ubahx' class='btn btn-info'>Perbaharui Data</button>
        <a href='index.php?view=mhswisuda&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a                    
      </div>
  </form>
</div>";
}

elseif($_GET[act]=='profillulusan'){
$d 			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_yudisium where MhswID='$_SESSION[_Login]'"));
$c 			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from mhsw where MhswID='$d[MhswID]'"));
$kp  		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM vw_jadwalkp_seminarhasilanggota WHERE MhswID='$d[MhswID]'"));
$pn  		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM vw_jadwal_skripsi WHERE MhswID='$d[MhswID]'"));

$nilaikp 		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_yudisiumdetail where MhswID='".strfilter($_GET[MhswID])."' AND MKID='PBSI7403'"));
$nilaipn 		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_yudisiumdetail where MhswID='".strfilter($_GET[MhswID])."' AND MKID='PBSI8604'"));

$Namax 		= strtolower($c[Nama]);
$Nama 		= ucwords($Namax);

$NamaAyahx 		= strtolower($c[NamaAyah]);
$NamaAyah 		= ucwords($NamaAyahx);

$NamaIbux 		= strtolower($c[NamaIbu]);
$NamaIbu 		= ucwords($NamaIbux);

$TmpLahirx 		= strtolower($c[TempatLahir]);
$TmpLahir 		= ucwords($TmpLahirx);

$Judulx 	= strtolower($kp[Judul]);
$JudulKP		= ucwords($Judulx);
$Juduly 	= strtolower($pn[Judul]);
$JudulPN		= ucwords($Juduly);

$p1 = mysqli_fetch_array(mysqli_query("select Login,Nama,Gelar from dosen where Login='$pn[PembimbingSkripsi1]'"));
$Pembimbing1x 	= strtolower($p1[Nama]);
$Pembimbing1	= ucwords($Pembimbing1x);
	   
$p2 = mysqli_fetch_array(mysqli_query("select Login,Nama,Gelar from dosen where Login='$pn[PembimbingSkripsi2]'"));	
$Pembimbing2x 	= strtolower($p2[Nama]);
$Pembimbing2	= ucwords($Pembimbing2x);

$kelamin = mysqli_fetch_array(mysqli_query("select Kelamin,Nama from kelamin where Kelamin='$c[Kelamin]'"));

if (trim($c[Foto])=='-'){
  $foto = 'pto_stud/no-image.jpg';
}else{
  $foto	= 'pto_stud/'.$c[Foto];
} 

if (($d[IPK]>=2.76) && ($d[IPK]<=3.50)){
	$predikat="Sangat Memuaskan";
}else{
	$predikat="Dengan Pujian";
}

$tgnskrg		= date('Y-m-d');
$data 			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT datediff('$tgnskrg', '$c[TanggalLahir]')as selisih"));
$tahun 			= floor($data['selisih']/365);
$bulan 			= floor(($data['selisih'] - ($tahun * 365))/30);
$hari  			= $data['selisih'] - $bulan * 30 - $tahun * 365;

$maks = mysqli_fetch_array(mysqli_query("select MhswID,Pemuncak from t_yudisium WHERE MhswID='".strfilter($_GET[MhswID])."' AND Pemuncak='Y'"));

if ($maks[Pemuncak]=='Y'){
    $label="<tr>  
			<th scope='row'  width='120px'><b style=color:#FF8000;font-size:25px><img class='img-thumbnail' style='width:100px;' src='img/medals.png'></th>
			<th scope='row'  width='160px'><b style=color:#FF8000;font-size:25px>** CONGRATULATION **</th>			
			</tr>";	
}else{
	$label="<tr>  
			<th scope='row' colspan=2 width='120px'><b style=color:#FF8000;font-size:25px></th>			
			</tr>";
}

echo "<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
<div class='col-md-12'>
  <table class='table table-condensed table-bordered'>
  <tbody>
  <tr><th  scope='row' colspan='10' style='width:90px;background:purple;color:white'><b style=font-size:20px>SELAMAT KEPADA $d[Nama] [ $d[MhswID] ] </b></th></tr>			
  </tbody>
  </table>		
</div>

<div class='col-md-6'>
<table class='table table-condensed table-bordered'>
  <tbody>	
    <tr>
	<th rowspan=6><img class='img-thumbnail' style='width:160px' src='$foto'></th>
	<th scope='row' width='130px'><b style=color:#3c8dbc;font-size:20px>NIM</th>
	<td><b style=color:#3c8dbc;font-size:20px>: $d[MhswID]</b></td></tr>
	<tr><th scope='row'><b style=color:#3c8dbc;font-size:20px>Nama</b></th>
	<td><b style=color:#3c8dbc;font-size:20px>: $Nama </b>&nbsp;&nbsp;<i></td>
	</tr>                  
	
	<tr>
	<th scope='row'><b style=color:#3c8dbc;font-size:20px>TTL</th> 
	<td><b style=color:#3c8dbc;font-size:20px>: $TmpLahir, ".tgl_indo($c[TanggalLahir])."</td>
	</tr>
	
	<tr>
	<th scope='row'><b style=color:#3c8dbc;font-size:20px>Gender</th> 			  
	<td><b style=color:#3c8dbc;font-size:20px>: $kelamin[Nama]</td>
	</tr>
	
	<tr>
	<th scope='row'><b style=color:#3c8dbc;font-size:20px>Umur</th>           
	<td><b style=color:#3c8dbc;font-size:20px>: $tahun Tahun $bulan Bulan $hari Hari</td>
	</tr>	
			
  </tbody>
  </table>
</div>

<div class='col-md-5'>
  <table class='table table-condensed table-bordered'>
  <tbody>	
	
	<tr>  
	<th scope='row' width='120px'><b style=color:#3c8dbc;font-size:20px>IPK</th>			
	<th align=center><b style=color:#3c8dbc;font-size:20px;text-align:center>: $d[IPK]</b></th>
	</tr>
	
	<tr>  
	<th scope='row' width='120px'><b style=color:#3c8dbc;font-size:20px>Predikat</th>
	<th><b style=color:#3c8dbc;font-size:20px;text-align:center>: $predikat</b></th>
	</tr>	
	
	<tr>  
	<th scope='row' width='120px'><b style=color:#3c8dbc;font-size:20px>Nama Ayah</th>
	<th><b style=color:#3c8dbc;font-size:20px;text-align:center>: $NamaAyah</b></th>
	</tr>
	
	<tr>  
	<th scope='row' width='120px'><b style=color:#3c8dbc;font-size:20px>Nama Ibu</th>
	<th><b style=color:#3c8dbc;font-size:20px;text-align:center>: $NamaIbu</b></th>
	</tr>
	
	 <tr>   
	 <th colspan=2><b style=color:green> <a href='index.php?view=mhswisuda&act=aplodpoto&IDYudisium=$d[IDYudisium]&prodi=$_GET[prodi]&MhswID=$d[MhswID]'><i>(Upload Bukti Pembayaran Wisuda)</a></i></b></th>
	 </tr>";	
	echo"$label";
				
  echo"</tbody>
  </table>
</div>				
</div>
</div>";
	
echo "
<div class='box box-info'>
<div class='box-header with-border'>
<div class='box-body'>
  <div class='col-md-6'>
  <table class='table table-condensed table-bordered'>
	<tr><th  scope='row' colspan='2' style='width:90px;background:purple;color:white'><b style=font-size:20px>KERJA PRAKTEK</b></th></tr>
	<tr><td scope='row' width='140px'><font style=color:#0b3750;font-size:20px>Judul</td>   		<td><font style=color:#0b3750;font-size:20px> $JudulKP </td></tr>
	<tr><td scope='row'><font style=color:#0b3750;font-size:20px>Pembimbing</td>   	<td><font style=color:#0b3750;font-size:20px>: $Pembimbing1, $p1[Gelar] </td></tr>	
	<tr><td scope='row'><font style=color:#0b3750;font-size:20px>TMP Praktek</td>   	<td><font style=color:#0b3750;font-size:20px>: $kp[TempatPenelitian] </td></tr>
	<tr><td scope='row'><font style=color:#0b3750;font-size:20px>Grade Nilai</td>   	<td><font style=color:#0b3750;font-size:20px>: $nilaikp[GradeNilai] </td></tr>				
  </tbody>
  </table>	

</div><div class='col-md-6'>
  <table class='table table-condensed table-bordered'>
	<tr><td  scope='row' colspan='2' style='width:90px;background:purple;color:white'><b style=font-size:20px><b>SKRIPSI</b></th></tr>
	<tr><td scope='row' width='140px'><font style=color:#0b3750;font-size:20px>Judul</td><td><font style=color:#0b3750;font-size:20px> $JudulPN </td></tr>
	<tr><td scope='row'><font style=color:#0b3750;font-size:20px>Pembimbing 1</td>   	<td><font style=color:#0b3750;font-size:20px>: $Pembimbing1, $p1[Gelar] </td></tr>
	<tr><td scope='row'><font style=color:#0b3750;font-size:20px>Pembimbing 2</td>   	<td><font style=color:#0b3750;font-size:20px>: $Pembimbing2, $p2[Gelar] </td></tr>
	
	<tr><td scope='row'><font style=color:#0b3750;font-size:20px>Grade Nilai</td>   	<td><font style=color:#0b3750;font-size:20px>: $nilaipn[GradeNilai] </td></font></tr>			
  </tbody>
  </table>	
</div>		  		  


</div>";
}

?>


