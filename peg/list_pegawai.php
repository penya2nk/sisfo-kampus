<?php if ($_GET['act']==''){ ?> 
<div class="card">
<div class="card-header">
	<h3 class="box-title"><b style=color:green;font-size:18px;>Informasi Pegawai</b></h3>

	<?php if($_SESSION['_LevelID']!='kepala'){ ?>
	<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=peg/list_pegawai&act=tambah'>Tambahkan Data</a>
	<?php } ?>
</div>
</div>

<div class="card">
<div class="card-header">
<div class="table-responsive">
<table id="example" class="table table-sm table-striped">
<thead>
	<tr style="background:purple;color:white">
	<th style='width:5px;text-align:center'>No</th>
	<th style='width:10px'>Noreg</th>
	<th style='width:150px'>Nama Pegawai</th>						
	<th style='width:150px'>Tempat dan Tgl Lahir</th>
	<th style='width:70px;text-align:center'>TMT</th>
	<th style='width:50px;text-align:center'>Handphone</th>
	<th style='width:200px'>Jabatan Saat Ini</th>
	<th style='width:150px'>Masa Kerja</th>                       
	<th style='width:50px;text-align:center'>Action</th>
	</tr>
</thead>
<tbody>
<?php 
$tampil = mysqli_query($koneksi, "SELECT * FROM t_simpegpegawai ORDER BY Urut asc");
$no = 1;
while($r=mysqli_fetch_array($tampil)){
$thnskrg		= date('Y-m-d');
$data 			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT datediff('$thnskrg', '$r[TMT]')as selisih"));
$tahun 			= floor($data['selisih']/365);
$bulan 			= floor(($data['selisih'] - ($tahun * 365))/30);
$hari  			= $data['selisih'] - $bulan * 30 - $tahun * 365; 
echo "<tr><td style='width:50px;text-align:center'>$no</td>
			<td >$r[Noreg]</td>
			<td>$r[Nama], $r[Gelar]</td>							
			<td>$r[TempatLahir], ".tgl_indo($r['TanggalLahir'])."</td>
			<td style='text-align:center'>".tgl_indo($r['TMT'])."</td>
			<td style='text-align:center'>$r[Handphone]</td>
			<td>$r[Jabatan]</td>
			<td>$tahun tahun $bulan bulan $hari hari</td>";
			if($_SESSION['_LevelID']!='kepala'){
	echo "<td><center>
			<a class='btn btn-success btn-xs' title='Edit Data' href='index.php?ndelox=peg/list_pegawai&act=edit&id=$r[IDPeg]'><i class='fa fa-edit'></i></a>
			<a class='btn btn-danger btn-xs' title='Delete Data' href='index.php?ndelox=peg/list_pegawai&hapus=$r[IDPeg]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-trash'></i></a>
			</center></td>";
			}
		echo "</tr>";
	$no++;
	}
	if (isset($_GET['hapus'])){
		mysqli_query($koneksi, "DELETE FROM t_simpegpegawai where IDPeg='".strfilter($_GET['hapus'])."'");
		echo "<script>document.location='index.php?ndelox=peg/list_pegawai';</script>";
	}

?>
</tbody>
</table>
</div>
</div>
</div>

<?php 
}elseif($_GET['act']=='edit'){
    if (isset($_POST['update'])){							
	  $rtrw 		= explode('/',$_POST['al']);
      $rt 			= $rtrw[0];
      $rw 			= $rtrw[1];
      $filename 	= basename($_FILES['ax']['name']);
	   if ($filename != ''){      
		    $ekstensi_diperbolehkan	= array('png','jpg');
			$filenamee 	= date("YmdHis").'-'.basename($_FILES['ax']['name']);
			$x 			= explode('.', $filenamee);
			$ekstensi 	= strtolower(end($x));
			$ukuran		= $_FILES['ax']['size'];
			$file_tmp = $_FILES['ax']['tmp_name'];	

			if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
				if($ukuran < 55000){	 //1044070		
				move_uploaded_file($file_tmp, 'pto_pgwe/'.$filenamee);
				$query = mysqli_query($koneksi, "UPDATE t_simpegpegawai SET                           
								Noreg 		= '".strfilter($_POST['Noreg'])."',
								NIDN 		= '".strfilter($_POST['NIDN'])."',
								Nama 		= '".strfilter($_POST['Nama'])."',
								TMT 		= '".strfilter($_POST['TMT'])."',
								Alamat 		= '".strfilter($_POST['Alamat'])."',
								JenisKelamin= '".strfilter($_POST['JenisKelamin'])."',
								TempatLahir	= '".strfilter($_POST['TempatLahir'])."',
								TanggalLahir= '".strfilter($_POST['TanggalLahir'])."',
								Agama 		= '".strfilter($_POST['Agama'])."',
								Gelar 		= '".strfilter($_POST['Gelar'])."',
								Keahlian 	= '".strfilter($_POST['Keahlian'])."',
                                Jabatan 	= '".strfilter($_POST['Jabatan'])."',
								Handphone 	= '".strfilter($_POST['Handphone'])."',
								Email 		= '".strfilter($_POST['Email'])."',
								FotoBro 	= '$filenamee' 
								where IDPeg='".strfilter($_POST['IDPeg'])."'");
					if($query){
						echo "<b style=color:green>File Berhasil diupload</b>";
					}else{
						echo "<b style=color:red>File Gagal diupload!</b>";
					}
				}else{
					echo "<b style=color:red>Ukuran File terlalu besar, Compres < 55000 Byte!</b>";
				}
			}else{
				echo "<b style=color:red>Ekstensi File yang diupload tidak diperbolehkan!</b>";
			}
	  }else{
           $query = mysqli_query($koneksi, "UPDATE t_simpegpegawai SET 
								Noreg 		= '".strfilter($_POST['Noreg'])."',
								NIDN 		= '".strfilter($_POST['NIDN'])."',
								Nama 		= '".strfilter($_POST['Nama'])."',
								TMT 		= '".strfilter($_POST['TMT'])."',
								Alamat 		= '".strfilter($_POST['Alamat'])."',
								JenisKelamin= '".strfilter($_POST['JenisKelamin'])."',
								TempatLahir	= '".strfilter($_POST['TempatLahir'])."',
								TanggalLahir= '".strfilter($_POST['TanggalLahir'])."',
								Agama 		= '".strfilter($_POST['Agama'])."',
								Gelar 		= '".strfilter($_POST['Gelar'])."',
								Keahlian 	= '".strfilter($_POST['Keahlian'])."',
                                Jabatan 	= '".strfilter($_POST['Jabatan'])."',
								Handphone 	= '".strfilter($_POST['Handphone'])."',
								Email 		= '".strfilter($_POST['Email'])."'								
								where IDPeg	= '".strfilter($_POST['IDPeg'])."'");
      }
        if ($query){
          echo "<script>document.location='index.php?ndelox=peg/list_pegawai&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=peg/list_pegawai&gagal';</script>";
        }						

    }
    $edit = mysqli_query($koneksi, "SELECT * FROM t_simpegpegawai where IDPeg='".strfilter($_GET['id'])."'");
    $s = mysqli_fetch_array($edit);
    if (trim($s['FotoBro'])=='-'){
		$foto = 'pto_pgwe/no-image.jpg';
	}else{
		$foto	= 'pto_pgwe/'.$s['FotoBro'];
	} 
?>

<div class='card'>
<div class='card-header'>
		<?php		
		//start kolom 1
		echo"<div class='row'>
				<div class='col-6'>";
								echo "
								<div class='card card-info'>
									<h3 class='card-title'><b style=color:purple;font-size='20px'>Edit Data</b></h3>
								</div>";
								
								echo"<div class='table-responsive'>
								<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
								<table class='table table-condensed table-bordered'>
								<tbody>
									<input type='hidden' name='IDPeg' value='$s[IDPeg]'>
									<tr><th width='200px' scope='row'>Noreg</th> <td><input type='text' class='form-control' name='Noreg' value='$s[Noreg]'> </td></tr>
									<tr><th  scope='row'>Nama Pegawai</th> <td><input type='text' class='form-control' name='Nama' value='$s[Nama]'> </td></tr>
									<tr><th  scope='row'>Alamat</th> <td><input type='text' class='form-control' name='Alamat' value='$s[Alamat]'> </td></tr>
									<tr><th  scope='row'>Tempat Lahir</th> <td><input type='text' class='form-control' name='TempatLahir' value='$s[TempatLahir]'> </td></tr>
									<tr><th  scope='row'>NIDN</th> <td><input type='text' class='form-control' name='NIDN' value='$s[NIDN]'> </td></tr>
									<tr><th  scope='row'>Gelar</th> <td><input type='text' class='form-control' name='Gelar' value='$s[Gelar]'> </td></tr>
									<tr><th  scope='row'>Foto</th> <td><img class='img-thumbnail' style='width:120px' src='$foto'></th></td></tr>
									<tr><th scope='row'>Ganti Foto</th>             <td><div style='position:relative;''>
										<a class='btn btn-primary' href='javascript:;'>
											<span class='glyphicon glyphicon-search'></span> Browse..."; ?>
											<input type='file' class='files' name='ax' onchange='$("#upload-file-info").html($(this).val());'>
										<?php echo "</a> <span style='width:155px' class='label label-info' id='upload-file-info'></span>
										</div>
									</td>
									</tr>  
									</tbody>
								</table>
								</div>";
								
				echo"</div>"; //close kolom 1	


				//start kolom 2
				echo "<div class='col-6'>";  
							echo"<table class='table table-condensed table-bordered'>
							<tbody>							
								<tr><th  scope='row'>Tanggal Lahir</th> <td><input type='text' class='form-control' id=datepicker2 name='TanggalLahir' value='$s[TanggalLahir]'> </td></tr>
								<tr><th  scope='row'>TMT</th> <td><input type='text' class='form-control' id=datepicker name='TMT' value='$s[TMT]'> </td></tr>
							
								<tr><th scope='row'>Agama</th>          
								<td><select class='form-control' name='Agama'> 
								<option value='0' selected>- Pilih Agama -</option>"; 
								$ag = mysqli_query($koneksi, "SELECT * FROM agama");
								while($a = mysqli_fetch_array($ag)){
								if ($a['Agama'] == $s['Agama']){
									echo "<option value='$a[Agama]' selected>$a[Nama]</option>";
								}else{
									echo "<option value='$a[Agama]' >$a[Nama]</option>";
								}
								}
								echo "</select></td></tr> 
								<tr><th scope='row'>Jenis Kelamin</th>                <td>";
									if ($s['sJenisKelamin']=='W'){
										echo "<input type='radio' name='JenisKelamin' value='P'> Pria
										<input type='radio' name='JenisKelamin' value='W' checked> Wanita";
									}else{
										echo "<input type='radio' name='JenisKelamin' value='P' checked> Pria 
										<input type='radio' name='JenisKelamin' value='W'> Wanita";
									}
									echo "</td></tr>  
								<tr><th scope='row'>Keahlian</th><td><input type='text' class='form-control' name='Keahlian' value='$s[Keahlian]'></td></tr>
								<tr><th scope='row'>Jabatan</th><td><input type='text' class='form-control' name='Jabatan' value='$s[Jabatan]'></td></tr>
								<tr><th scope='row'>Handphone</th><td><input type='text' class='form-control' name='Handphone' value='$s[Handphone]'></td></tr>
								<tr><th scope='row'>Email</th><td><input type='text' class='form-control' name='Email' value='$s[Email]'></td></tr>
								</tbody>
								</table>

						<div class='box-footer'>
						<button type='submit' name='update' class='btn btn-info'>Update</button>
						<a href='index.php?ndelox=peg/list_pegawai'><button type=button class='btn btn-default pull-right'>Cancel</button></a>                   
						</div>

						</form>";	
				echo"</div>";//tutup kolom 2
		echo"</div>"; //tutup row
		?>

</div>
</div>

<?php
}elseif($_GET['act']=='tambah'){
    if (isset($_POST['tambah'])){
        $query = mysqli_query($koneksi, "INSERT INTO t_simpegpegawai(
							  Noreg,
							  NIDN,
							  Nama,
							  Gelar,
							  TempatLahir,
							  TanggalLahir,
							  TMT,
							  Agama,
							  Keahlian,
							  Jabatan,
							  Handphone,
							  Email,
							  Alamat) 
					  VALUES('".strfilter($_POST['Noreg'])."',
							 '".strfilter($_POST['NIDN'])."',
							 '".strfilter($_POST['Nama'])."',
							 '".strfilter($_POST['Gelar'])."',
							 '".strfilter($_POST['TMT'])."',
							 '".strfilter($_POST['TempatLahir'])."',
							 '".strfilter($_POST['TanggalLahir'])."',
							 '".strfilter($_POST['Agama'])."',
							 '".strfilter($_POST['Keahlian'])."',
							 '".strfilter($_POST['Jabatan'])."',
							 '".strfilter($_POST['Handphone'])."',
							 '".strfilter($_POST['Email'])."',
							 '".strfilter($_POST['Alamat'])."'							 
							 )");
        if ($query){
          echo "<script>document.location='index.php?ndelox=peg/list_pegawai&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=peg/list_pegawai&gagal';</script>";
        } 
    }
?>


<div class='card'>
<div class='card-header'>
		<?php		
		//start kolom 1
		echo"<div class='row'>
				<div class='col-6'>";
					echo "
					<div class='card card-info'>
						<h3 class='card-title'><b style=color:purple;font-size='20px'>Tambah Data</b></h3>
					</div>";
								
					echo"<div class='table-responsive'>
					<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
						<table class='table table-condensed table-bordered'>
						<tbody>
							<tr><th width='200px' scope='row'>Noreg</th> <td><input type='text' class='form-control' name='Noreg' > </td></tr>
							<tr><th  scope='row'>Nama Pegawai</th> <td><input type='text' class='form-control' name='Nama' > </td></tr>
							<tr><th  scope='row'>NIDN</th> <td><input type='text' class='form-control' name='NIDN' > </td></tr>
							<tr><th  scope='row'>Gelar</th> <td><input type='text' class='form-control' name='Gelar' > </td></tr>
							<tr><th  scope='row'>Alamat</th> <td><input type='text' class='form-control' name='Alamat' > </td></tr>
							<tr><th  scope='row'>Tempat Lahir</th> <td><input type='text' class='form-control' name='TempatLahir' > </td></tr>
							<tr><th  scope='row'>Tanggal Lahir</th> <td><input type='text' class='form-control' id=datepicker2 name='TanggalLahir' > </td></tr>
						</tbody>
						</table>
						</div>";
										
				echo"</div>"; //close kolom 1	


				//start kolom 2
				echo "<div class='col-6'>  
							<div class='table-responsive'>
							<table class='table table-condensed table-bordered'>
							<tbody>
								<tr><th  scope='row'>TMT</th> <td><input type='text' class='form-control' id=datepicker name='TMT' > </td></tr>
								
								<tr><th scope='row'>Agama</th>          
								<td><select class='form-control' name='Agama'> 
								<option value='0' selected>- Pilih Agama -</option>"; 
								$status = mysqli_query($koneksi, "SELECT * FROM agama");
								while($a = mysqli_fetch_array($status)){
									echo "<option value='$a[Agama]' selected>$a[Nama]</option>";
								}
								echo "</select></td></tr> 
								<tr><th scope='row'>Jenis Kelamin</th>                <td>";						
										echo "<input type='radio' name='JenisKelamin' value='P'> Pria
										<input type='radio' name='JenisKelamin' value='W' checked> Wanita";						
									echo "</td></tr>  
								<tr><th scope='row'>Keahlian</th><td><input type='text' class='form-control' name='Keahlian' ></td></tr>
								<tr><th scope='row'>Jabatan</th><td><input type='text' class='form-control' name='Jabatan' ></td></tr>
								<tr><th scope='row'>Handphone</th><td><input type='text' class='form-control' name='Handphone' ></td></tr>
								<tr><th scope='row'>Email</th><td><input type='text' class='form-control' name='Email' ></td></tr>
							</tbody>
							</table>
							</div>

							<div class='box-footer'>
							<button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
							<a href='index.php?ndelox=peg/list_pegawai'><button type=button class='btn btn-default pull-right'>Cancel</button></a>                  
							</div>
				  			</form>";	

				echo"</div>";//tutup kolom 2
		echo"</div>"; //tutup row
		?>
  
</div>
</div>

<?php  
}
?>