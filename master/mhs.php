
         
       
        
          
        
       

<div class='card'>
<div class='card-header'>
<div class="form-group row">
	<label class="col-md-6 col-form-label text-md-right"><b style='color:purple'>FILTER DATA</b></label>
	<div class="col-md-2">                 
	<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
	<input type="hidden" name='ndelox' value='master/mhs'>
	<select name='program' class='form-control form-control-sm form-control form-control-sm-sm' onChange='this.form.submit()'>
	<?php 
		echo "<option value=''>- Pilih Program -</option>";
		$program = mysqli_query($koneksi, "SELECT * FROM program");
		while ($k = mysqli_fetch_array($program)){
		 if ($_GET['program']==$k['ProgramID']){
			echo "<option value='$k[ProgramID]' selected>$k[ProgramID] - $k[Nama]</option>";
		  }else{
			echo "<option value='$k[ProgramID]'>$k[ProgramID] - $k[Nama] </option>";
		  }
		}
	?>
	</select>
	</div>                


	<div class="col-md-2">
	<select name='prodi' class='form-control form-control-sm form-control form-control-sm-sm' onChange='this.form.submit()'>
	<?php 
		echo "<option value=''>- Pilih Program Studi -</option>";
		$prodi = mysqli_query($koneksi, "SELECT * from prodi order by Nama ASC");
		while ($k = mysqli_fetch_array($prodi)){
		   if ($_GET['prodi']==$k['ProdiID']){
			echo "<option value='$k[ProdiID]' selected>$k[Nama]</option>";
		  }else{
			echo "<option value='$k[ProdiID]'>$k[Nama]</option>";
		  }
		}
	?>
	</select>
	</div>                


	<div class="col-md-1">
	<input type="submit"  class='btn btn-success btn-sm' value='Lihat'>
	</div>                
	</form>


	<div class="col-md-1"><?php 
	if (!empty($_GET['program']) && !empty($_GET['prodi'])) { ?>
			<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=master/mhs&act=tambahmhs&prodi=<?php echo strfilter($_GET['prodi']); ?>&program=<?php echo strfilter($_GET['program']); ?>'>Add Data</a>

	<?php } ?>
	
	</div>


</div>
</div>
</div>

<?php if ($_GET['act']==''){ ?> 
				<div class='card'>
				<div class='card-header'>
				 <div class="box-header">
                  <h3 class="box-title"><b style='color:green;font-size:20px'>Master Mahasiswa </b></h3>
                </div><!-- /.box-header -->
				<div class='table-responsive'>
                  <table id="example1" class="table table-sm  table-striped">
                    <thead>
                      <tr style='background:purple;color:white'>
                        <th width='5px'>No</th>                       
						<th width='10px'>MhswID</th>
                        <th width='150px'>Nama</th>
						<th width='150px'>Tempat & Tgl Lahir</th>
						<th width='150px'>Program Studi</th> 
                        <th width='10px'>Handphone</th>
                         
						<th width='10px'>Status</th>  			
                        <th width='70px'>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    //  if (strfilter($_GET['program']) !='' AND strfilter($_GET['prodi']) != ''){
					// 	$tampil = mysqli_query($koneksi, "SELECT * FROM view_mhs where ProgramID='".strfilter($_GET['program'])."' and ProdiID='".strfilter($_GET['prodi'])."' order by MhswID Desc");
					//  }
					//  else if (strfilter($_GET['program']) =='' AND strfilter($_GET['prodi']) != ''){
					// 	$tampil = mysqli_query($koneksi, "SELECT * FROM view_mhs where ProdiID='".strfilter($_GET['prodi'])."' order by MhswID Desc limit 10");						 
					// } else if (($_GET['program']) !='' AND strfilter($_GET['prodi']) == ''){
					// 	$tampil = mysqli_query($koneksi, "SELECT * FROM view_mhs where ProgramID='".strfilter($_GET['program'])."' order by MhswID Desc limit 10");
					// }
					//else{
					   $tampil = mysqli_query($koneksi, "SELECT * FROM view_mhs order by MhswID Desc limit 300");// where StatusMhswID='A'
					//}
					
					$no = 1;
                    while($r=mysqli_fetch_array($tampil)){
                    $NamaMhsx 	= strtolower($r['NamaMahasiswa']);
					$NamaMhs	= ucwords($NamaMhsx);
					$TempatLahirx 	    = strtolower($r['TempatLahir']);
					$TempatLahir 		= ucwords($TempatLahirx);
                    $status 	= $r['StatusMhswID'];
					if ($status=='A'){
						$ket ="Aktif";
					}
					else if ($status=='C'){
						$ket ="Cuti";
					}
					else if ($status=='P'){
						$ket ="Pasif";
					}
					else if ($status=='K'){
						$ket ="Keluar";
					}
					else if ($status=='D'){
						$ket ="Drop-Out";
					}
					else if ($status=='L'){
						$ket ="Lulus";
					}
					else if ($status=='T'){
						$ket ="Tunggu Ujian";
					}
					else if ($status=='W'){
						$ket ="Tunggu Wisuda";
					}		
					else{
						$Ket="SkorSing";
					}
		echo "<tr>
		<td>$no</td>
		<td><a href='?ndelox=master/mhs&act=detailmhs&id=$r[MhswID]&program=$r[ProgramID]&prodi=$r[ProdiID]'>$r[MhswID]</a></td>
		<td>$NamaMhs </td>
        <td>$TempatLahir, ".tgl_indo($r['TanggalLahir'])."</td>
		<td>$r[ProgramID] - $r[NamaProdi]</td>
		<td>$r[Handphone] </td>
		<td>$ket</td>";
		
		echo "<td>
		
		<a class='btn btn-info btn-xs' title='Lihat Detail' href='?ndelox=master/mhs&act=detailmhs&id=$r[MhswID]&program=$r[ProgramID]&prodi=$r[ProdiID]'><i class='fa fa-edit'></i></a>
		<a class='btn btn-success btn-xs' title='Edit Data' href='?ndelox=master/mhs&act=editmhs&id=$r[MhswID]&program=$r[ProgramID]&prodi=$r[ProdiID]'><i class='fa fa-image''></i></a>
		<a class='btn btn-danger btn-xs' title='Delete Data' href='?ndelox=master/mhs&hapus=$r[MhswID]&program=$r[ProgramID]&prodi=$r[ProdiID]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-trash'></i></a>
		</td>
		</tr>";
		$no++;
		}
		if (isset($_GET['hapus'])){
		    $data=mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from mhsw where MhswID='".strfilter($_GET['hapus'])."'"));
		    mysqli_query("INSERT into del_mhs (MhswID,
												  Nama,
												  Alamat,
												  Handphone,
												  ProgramID,
												  ProdiID,
												  BatasStudi,
												  LoginBuat,
												  TanggalBuat)
										  VALUES('".strfilter($data['MhswID'])."',
										  		 '".strfilter($data['Nama'])."',
												 '".strfilter($data['Alamat'])."',
												 '".strfilter($data['Handphone'])."',  
												 '".strfilter($data['ProgramID'])."',
												 '".strfilter($data['ProdiID'])."',
												 '".strfilter($data['BatasStudi'])."',
												 '$_SESSION[_Login]', 
												 '".date('Y-m-d H:i:s')."')");
		mysqli_query("DELETE FROM mhsw where MhswID='".strfilter($_GET['hapus'])."'");
		echo "<script>document.location='index.php?ndelox=master/mhs&id=".strfilter($_GET['aa'])."&program=".strfilter($_GET['program'])."&prodi=".strfilter($_GET['prodi'])."&sukses';</script>";
		}
		?>
		</tbody>
		</table>
		</div>
		</div>
		</div>


<?php 
}elseif($_GET['act']=='tambahmhs'){
   if (isset($_POST['tambah'])){     	
     
      $rtrw = explode('/',$_POST['al']);
      $rt = $rtrw[0];
      $rw = $rtrw[1];     
      $data = strfilter(md5($_POST['aa']));
      $passs= hash("sha512",$data);
	  
      $tglb = date('Y-m-d H:i:s');
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
					move_uploaded_file($file_tmp, 'pto_stud/'.$filenamee);			
					$query = mysqli_query("INSERT INTO mhsw
		  						(MhswID,
									Login,
									LevelID,
									PASSWORD,								
									TahunID,
									KodeID,								
									Nama,
									StatusAwalID,
									StatusMhswID,
									ProgramID,
									ProdiID,
									Kelamin,								
									TempatLahir,
									TanggalLahir,							
									Alamat,
									Handphone,							
									LoginBuat,
									TanggalBuat,
									Foto) 
		  						VALUES
									('".strfilter($_POST['MhswID'])."',
									'".strfilter($_POST['MhswID'])."',
									'120',
									'*6BB4837EB',
									'$_SESSION[tahun_akademik]',
									'HTP',
									'".strfilter($_POST['Nama'])."',
									'".strfilter($_POST['StatusAwalID'])."',
									'".strfilter($_POST['StatusMhswID'])."',
									'".strfilter($_POST['ProgramID'])."',
									'".strfilter($_POST['ProdiID'])."',
									'".strfilter($_POST['Kelamin'])."',								
									'".strfilter($_POST['TempatLahir'])."',
									'".date('Y-m-d', strtotime($_POST('TanggalLahir')))."',								
									'".strfilter($_POST['Alamat'])."',
									'".strfilter($_POST['Handphone'])."',							
									'$_SESSION[_Login]',
									'".date('Y-m-d H:i:s')."',
									'$filenamee')");
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
          $query = mysqli_query("INSERT INTO mhsw
		  						(MhswID,
								Login,
								LevelID,
								PASSWORD,								
								TahunID,
								KodeID,								
								Nama,
								StatusAwalID,
								StatusMhswID,
								ProgramID,
								ProdiID,
								Kelamin,								
								TempatLahir,
								TanggalLahir,							
								Alamat,
								Handphone,							
								LoginBuat,
								TanggalBuat) 
		  						VALUES
								('".strfilter($_POST['MhswID'])."',
								'".strfilter($_POST['MhswID'])."',
								'120',
								'*6BB4837EB',
								'$_SESSION[tahun_akademik]',
								'HTP',
								'".strfilter($_POST['Nama'])."',
								'".strfilter($_POST['StatusAwalID'])."',
								'".strfilter($_POST['StatusMhswID'])."',
								'".strfilter($_POST['ProgramID'])."',
								'".strfilter($_POST['ProdiID'])."',
								'".strfilter($_POST['Kelamin'])."',								
								'".strfilter($_POST['TempatLahir'])."',
								'".strfilter($_POST['TanggalLahir'])."',								
								'".strfilter($_POST['Alamat'])."',
								'".strfilter($_POST['Handphone'])."',							
								'$_SESSION[_Login]',
								'".date('Y-m-d H:i:s')."')");
      }
        if ($query){
          echo "<script>document.location='index.php?ndelox=master/mhs&act=detailmhs&id=".$_POST['MhswID']."&program=".$_POST['ProgramID']."&prodi=".$_POST['ProdiID']."&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=master/mhs&act=detailmhs&id=".$_POST['MhswID']."&program=".$_POST['ProgramID']."&prodi=".$_POST['ProdiID']."&gagal';</script>";
        }
  }
	

echo"<div class='card'>

<div class='table-responsive'>
<div class='box-header with-border'>
<h3 class='box-title'><b style=color:green;font-size=12px>&nbsp;Tambah Data</b></h3>
</div>

</div>
</div>";	
echo "<div class='card card-primary card-outline card-outline-tabs'>
						
              <div class='card-header p-0 border-bottom-0'>
                <ul class='nav nav-tabs' id='custom-tabs-three-tab' role='tablist'>
				
                  <li class='nav-item'>
                    <a class='nav-link active' id='custom-tabs-three-home-tab' data-toggle='pill' href='#custom-tabs-three-home' role='tab' aria-controls='custom-tabs-three-home' aria-selected='true'>Pribadi</a>
                  </li>
				  
                  <li class='nav-item'>
                    <a class='nav-link' id='custom-tabs-three-profile-tab' data-toggle='pill' href='#custom-tabs-three-profile' role='tab' aria-controls='custom-tabs-three-profile' aria-selected='false'>Alamat Tetap</a>
                  </li>
				  
                  <li class='nav-item'>
                    <a class='nav-link' id='custom-tabs-three-messages-tab' data-toggle='pill' href='#custom-tabs-three-messages' role='tab' aria-controls='custom-tabs-three-messages' aria-selected='false'>Akademik</a>
                  </li>
				  
                  <li class='nav-item'>
                    <a class='nav-link' id='custom-tabs-three-settings-tab' data-toggle='pill' href='#custom-tabs-three-settings' role='tab' aria-controls='custom-tabs-three-settings' aria-selected='false'>Orang Tua</a>
                  </li>
				  
				   <li class='nav-item'>
                    <a class='nav-link' id='custom-tabs-three-asalsek-tab' data-toggle='pill' href='#custom-tabs-three-asalsek' role='tab' aria-controls='custom-tabs-three-asalsek' aria-selected='false'>Asal Sekolah</a>
                  </li>
				  
				  <li class='nav-item'>
                    <a class='nav-link' id='custom-tabs-three-asalpt-tab' data-toggle='pill' href='#custom-tabs-three-asalpt' role='tab' aria-controls='custom-tabs-three-asalpt' aria-selected='false'>Asal PT</a>
                  </li>
				  
				  <li class='nav-item'>
                    <a class='nav-link' id='custom-tabs-three-bank-tab' data-toggle='pill' href='#custom-tabs-three-bank' role='tab' aria-controls='custom-tabs-three-bank' aria-selected='false'>Bank</a>
                  </li>
				  
                </ul>
              </div>
			  
              <div class='card-body'>
                <div class='tab-content' id='custom-tabs-three-tabContent'>
				
                  <div class='tab-pane fade show active' id='custom-tabs-three-home' role='tabpanel' aria-labelledby='custom-tabs-three-home-tab'>
                
						<form  method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
					
												<table class='table table-sm table-bordered'>
												<tbody>                            
												<tr><th scope='row' width='260'>NIM <b style='color:red'>*</b></th> <td><input type='text' class='form-control form-control-sm' name='MhswID' required></td></tr>   
												<tr><th scope='row' >Nama <b style='color:red'>*</b></th> <td><input type='text' class='form-control form-control-sm' name='Nama' value='$mw[Nama]' required></td></tr>   
												<tr><th scope='row'>Tempat Lahir <b style='color:red'>*</b></th> <td><input type='text' class='form-control form-control-sm' name='TempatLahir' value='$mw[TempatLahir]' required></td></tr>
												<tr><th scope='row'>Tanggal Lahir <b style='color:red'>*</b></th> <td><input type='text' class='form-control form-control-sm tanggal' name='TanggalLahir' value='".date('d-m-Y', strtotime($mw['TanggalLahir']))."' required></td></tr> 
												<tr><th scope='row'>Jenis Kelamin <b style='color:red'>*</b></th>          
												<td><select class='form-control form-control-sm' name='Kelamin' required> 
													<option value='' selected>- Pilih Jenis Kelamin -</option>"; 
													$jk = mysqli_query($koneksi, "SELECT * FROM kelamin");
													while($a = mysqli_fetch_array($jk)){
														if ($a['Kelamin'] == $mw['Kelamin']){
														echo "<option value='$a[Kelamin]' selected>$a[Nama]</option>";
														}else{
														echo "<option value='$a[Kelamin]'>$a[Nama]</option>";
														}
													}
													echo "</select></td></tr> 
												
												<tr><th scope='row'>Alamat <b style='color:red'>*</b></th> <td><input type='text' class='form-control form-control-sm' name='Alamat' value='$mw[Alamat]' required></td></tr>
																	
																	
												<tr><th scope='row'>Foto</th>           <td><div style='position:relative;''>
												<a class='btn btn-primary' href='javascript:;'>
												<span class='glyphicon glyphicon-search'></span> Browse..."; ?>
												<input type='file' class='files' name='ax' onchange='$("#upload-file-info").html($(this).val());'>
												<?php echo "</a> <span style='width:155px' class='label label-info' id='upload-file-info'></span>
												</div>
												</td></tr> 
												</tbody>
												</table>
												<b style='color:red'>*</b> wajib di isi!
												
									</div>
									<div class='tab-pane fade' id='custom-tabs-three-profile' role='tabpanel' aria-labelledby='custom-tabs-three-profile-tab'>
										
										
												<table class='table table-sm table-bordered'>
												<tbody>
												<tr><th  scope='row' width='260'>Alamat Asal <b style='color:red'>*</b></th> <td><input type='text' class='form-control form-control-sm' name='bm' value='$mw[AlamatAsal]'></td></tr>
												<tr><th  scope='row' >NIK <b style='color:red'>*</b></th> <td><input type='text' class='form-control form-control-sm' name='NIK' value='$mw[NIK]' required></td></tr>
												<tr><th  scope='row'>ID KK <b style='color:red'>*</b></th> <td><input type='text' class='form-control form-control-sm' name='IDKK' value='$mw[IDKK]'></td></tr>
												<tr><th  scope='row'>Kelurahan <b style='color:red'>*</b></th> <td><input type='text' class='form-control form-control-sm' name='Kelurahan' value='$mw[Kelurahan]' required></td></tr>
												<tr><th  scope='row'>Kecamatan <b style='color:red'>*</b></th> <td><input type='text' class='form-control form-control-sm' name='Kecamatan' value='$mw[Kecamatan]' required></td></tr>
												</tbody>
												</table>
										
										
									</div>
									<div class='tab-pane fade' id='custom-tabs-three-messages' role='tabpanel' aria-labelledby='custom-tabs-three-messages-tab'>
										
												<table class='table table-sm table-bordered'>
												<tbody>
												<tr><th scope='row' width='260'>Program <b style='color:red'>*</b></th>
												<td><select class='form-control form-control-sm' name='ProgramID' required> 
												<option value='0' selected>- Pilih Program -</option>"; 
												$prog = mysqli_query($koneksi, "SELECT * FROM program");
												while($a = mysqli_fetch_array($prog)){
												if ($a['ProgramID'] == $_GET['program']){
													echo "<option value='$a[ProgramID]' selected>$a[Nama]</option>";
												}else{
													echo "<option value='$a[ProgramID]'>$a[Nama]</option>";
												}
												}
												echo "</select></td></tr>

												<tr><th scope='row'>Program Studi <b style='color:red'>*</b></th>          
												<td><select class='form-control form-control-sm' name='ProdiID' required> 
												<option value='0' selected>- Pilih Program -</option>"; 
												$prod = mysqli_query($koneksi, "SELECT * FROM prodi");
												while($a = mysqli_fetch_array($prod)){
												if ($a[ProdiID] == $_GET['prodi']){
													echo "<option value='$a[ProdiID]' selected>$a[Nama]</option>";
												}else{
													echo "<option value='$a[ProdiID]'>$a[Nama]</option>";
												}
												}
												echo "</select></td></tr>
												<tr><th scope='row'>Status Awal <b style='color:red'>*</b></th>          
												<td><select class='form-control form-control-sm' name='StatusAwalID' required> 
												<option value='0' selected>- Pilih Status Awal -</option>"; 
												$sw = mysqli_query($koneksi, "SELECT * FROM statusawal");
												while($a = mysqli_fetch_array($sw)){
												if ($a['StatusAwalID'] == $mw['StatusAwalID']){
													echo "<option value='$a[StatusAwalID]' selected>$a[Nama]</option>";
												}else{
													echo "<option value='$a[StatusAwalID]'>$a[Nama]</option>";
												}
												}
												echo "</select></td></tr>

												<tr><th scope='row'>Status Mahasiswa <b style='color:red'>*</b></th>          
												<td><select class='form-control form-control-sm' name='StatusMhswID' required> 
												<option value='0' selected>- Pilih Status Mahasiswa -</option>"; 
												$status = mysqli_query($koneksi, "SELECT * FROM statusmhsw");
												while($a = mysqli_fetch_array($status)){
												if ($a['StatusMhswID'] == $mw['StatusMhswID']){
													echo "<option value='$a[StatusMhswID]' selected>$a[Nama]</option>";
												}else{
													echo "<option value='$a[StatusMhswID]'>$a[Nama]</option>";
												}
												}
												echo "</select></td></tr>

												<tr><th scope='row'>Penasehat Akademik </th>          
												<td><select class='form-control form-control-sm' name='PenasehatAkademik'> 
												<option value='0' selected>- Pilih Penasehat Akademik -</option>"; 
												$sql = mysqli_query($koneksi, "SELECT Login,Nama FROM dosen");
												while($a = mysqli_fetch_array($sql)){
												if ($a['Login'] == $mw['PenasehatAkademik']){
													echo "<option value='$a[Login]' selected>$a[Nama]</option>";
												}else{
													echo "<option value='$a[Login]'>$a[Nama]</option>";
												}
												}
												echo "</select></td></tr>  
												<tr><th  scope='row'>Batas Studi <b style='color:red'>*</b></th> <td><input type='text' class='form-control form-control-sm' name='BatasStudi' value='$mw[BatasStudi]' required></td></tr>                           
												</tbody>
												</table>
					
					
					
                  </div>
                  <div class='tab-pane fade' id='custom-tabs-three-settings' role='tabpanel' aria-labelledby='custom-tabs-three-settings-tab'>
                    
					
					<table class='table table-sm table-bordered'>
					<tbody>
					<tr><th  scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>DATA AYAH</th></tr>
					<tr><th  scope='row' width='260'>Nama Ayah <b style='color:red'>*</b></th> <td><input type='text' class='form-control form-control-sm' name='NamaAyah' value='$mw[NamaAyah]'></td></tr>
				
					<tr><th  scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>DATA IBU</th></tr>
					<tr><th  scope='row'>Nama Ibu <b style='color:red'>*</b></th> <td><input type='text' class='form-control form-control-sm' name='cb' value='$mw[NamaIbu]'></td></tr>
					
					</tbody>
					</table>
					
                  </div>
				  
				   <div class='tab-pane fade' id='custom-tabs-three-asalsek' role='tabpanel' aria-labelledby='custom-tabs-three-asalsek-tab'>
                    
					<table class='table table-condensed table-bordered'>
					<tbody>
				
					<tr><th  scope='row' width='260'>Jurusan</th> <td><input type='text' class='form-control form-control-sm' name='JurusanSekolah' value='$mw[JurusanSekolah]'></td></tr>
					<tr><th  scope='row'>Tahun Lulus</th> <td><input type='text' class='form-control form-control-sm' name='TahunLulus' value='$mw[TahunLulus]'></td></tr>
					</tbody>
					</table>
					
					
                  </div>
				  
				  
				  <div class='tab-pane fade' id='custom-tabs-three-asalpt' role='tabpanel' aria-labelledby='custom-tabs-three-asalpt-tab'>
                    
					<table class='table table-condensed table-bordered'>
					<tbody>
					<tr><th  scope='row' width='260px'>Asal Perguruan Tinggi</th> <td><input type='text' class='form-control form-control-sm' name='AsalPT' value='$mw[AsalPT]'></td></tr>
				
					</tbody>
					</table>
					
                  </div>
				  
				  
				  <div class='tab-pane fade' id='custom-tabs-three-bank' role='tabpanel' aria-labelledby='custom-tabs-three-bank-tab'>
                   
				   	<table class='table table-condensed table-bordered'>
					<tbody>
					<tr><th  scope='row' width='260px'>Norek</th> <td><input type='text' class='form-control form-control-sm' name='NomerRekening' value='$mw[NomerRekening]'></td></tr>
					<tr><th  scope='row'>Nama Bank</th> <td><input type='text' class='form-control form-control-sm' name='NamaBank' value='$mw[NamaBank]'></td></tr>
					
					</tbody>
					</table>
				   
				   
                  </div>
				  
			
				  
                </div>
              </div>
              <!-- /.card -->		  
            </div>";
			
			
			echo"<div class='card'>
				<div class='card-header'>
				<div class='table-responsive'>
					<div class='box-footer'>
					<button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
					<a href='index.php?ndelox=master/mhs&id=".$_GET['id']."&program=".$_GET['program']."&prodi=".$_GET['prodi']."'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
					</div>
				</div>
				</div>
				</div>	
				</form>";
//Akhir Tab Asal Perguruan Tinggi ------------------------------------------------------------------------------------------------------------------------	

}elseif($_GET['act']=='editmhs'){
  if (isset($_POST['update1'])){
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
					move_uploaded_file($file_tmp, 'pto_stud/'.$filenamee);
					$query = mysqli_query($koneksi, "UPDATE mhsw SET                           
                           Nama   	= '".strfilter($_POST['an'])."',
                           Foto 	= '$filenamee' 
						   where MhswID='".strfilter($_POST['id'])."'");
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
          $query = mysqli_query($koneksi, "UPDATE mhsw SET                           
                           Nama     = '".strfilter($_POST['an'])."'
						   where MhswID='".strfilter($_POST['id'])."'");
      }
        if ($query){
          echo "<script>document.location='index.php?ndelox=master/mhs&act=editmhs&id=".$_POST['id']."&program=".$_POST['program']."&prodi=".$_POST['prodi']."&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=master/mhs&act=editmhs&id=".$_POST['id']."&program=".$_POST['program']."&prodi=".$_POST['prodi']."&gagal';</script>";
        }
  }

    $detail = mysqli_query($koneksi, "SELECT * FROM mhsw where MhswID='".strfilter($_GET['id'])."'");
    $s = mysqli_fetch_array($detail);
    echo "
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
               <div class='card'>
				<div class='card-header'>
				 <div class='box-header with-border'>
                  <h3 class='box-title'>Change Foto</h3>
                </div>
				<div class='table-responsive'>
                  <table class='table table-sm'>
                  <tbody>
                    <input type='hidden' name='id' value='$s[MhswID]'>
					<input type='hidden' name='program' value='$_GET[program]'>
					<input type='hidden' name='prodi' value='$_GET[prodi]'>
                    <tr><th style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc; width='120px' rowspan='25'>";
                        if (trim($s['Foto'])==''){
                          echo "<img class='img-thumbnail' style='width:120px' src='pto_stud/no-image.jpg'>";
                        }else{
                          echo "<img class='img-thumbnail' style='width:120px' src='pto_stud/$s[Foto]'>";
                        }
                        echo "</th>
                    </tr>
                    <tr><th width='280px' scope='row'>MhswID</th>      <td><input type='text' class='form-control form-control-sm' value='$s[MhswID]' name='aa' readonly></td></tr>
					 <tr><th scope='row'>Nama Mahasiswa</th>      <td><input type='text' class='form-control form-control-sm' value='$s[Nama]' name='an'></td></tr>
                    <tr><th scope='row'>Ganti Foto (Ukuran < 55000 Byte)</th>             <td><div style='position:relative;''>
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
                          <button type='submit' name='update1' class='btn btn-info'>Update</button>
                          <a href='index.php?ndelox=master/mhs&id=".$_GET['id']."&program=".$_GET['program']."&prodi=".$_GET['prodi']."'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                        </div> 
                </div> 
               </div>                  
              </div>
            </form>";

}elseif($_GET['act']=='detailmhs'){      
      if (isset($_POST['ubahdata'])){
          /*
		  //$cek = mysqlimysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM cuti where MhswID='$_POST[MhswID]' and '$_SESSION[tahun_akademik]'"));
          //if ($cek >= 1){
          //   echo "<script>window.alert('Maaf, Data Tersebut sudah ada.');
                                  window.location=('index.php?ndelox=master/mhs&act=detailmhs&id=".$_POST[MhswID]."')</script>";
          }else{
          */
		  //$query = mysqli_query("INSERT INTO cuti(TahunID,MhswID) VALUES('$_SESSION[tahun_akademik]','$_POST[MhswID]')");
	  $queryx = mysqli_query($koneksi, "UPDATE mhsw SET                           
				PMBID			='".strfilter($_POST['ae'])."',
				PMBFormJualID	='".strfilter($_POST['af'])."',
				PSSBID			='".strfilter($_POST['ag'])."',
				NIRM			='".strfilter($_POST['ah'])."',
				BuktiSetoran	='".strfilter($_POST['ai'])."',
				TahunID			='".strfilter($_POST['aj'])."',
				KodeID			='SISFO',
				BIPOTID			='".strfilter($_POST['al'])."',
				Autodebet		='".strfilter($_POST['am'])."',
				Nama			='".strfilter($_POST['an'])."',
				StatusAwalID	='".strfilter($_POST['ap'])."',
				StatusMhswID	='".strfilter($_POST['aq'])."',
				ProgramID		='".strfilter($_POST['ar'])."',
				ProdiID			='".strfilter($_POST['as'])."',
				PenasehatAkademik	='".strfilter($_POST['at'])."',
				Kelamin			='".strfilter($_POST['au'])."',
				WargaNegara		='".strfilter($_POST['av'])."',
				Kebangsaan		='".strfilter($_POST['aw'])."',
				TempatLahir		='".strfilter($_POST['ax'])."',
				TanggalLahir	='".date('Y-m-d', strtotime($_POST['ay']))."',
				Agama			='".strfilter($_POST['az'])."',
				StatusSipil		='".strfilter($_POST['ba'])."',
				Alamat			='".strfilter($_POST['bb'])."',
				Kota			='".strfilter($_POST['bc'])."',
				RT				='".strfilter($_POST['bd'])."',
				RW				='".strfilter($_POST['be'])."',
				KodePos			='".strfilter($_POST['bf'])."',
				Propinsi		='".strfilter($_POST['bg'])."',
				Negara			='".strfilter($_POST['bh'])."',
				Telepon			='".strfilter($_POST['bi'])."',
				Telephone		='".strfilter($_POST['bj'])."',
				Handphone		='".strfilter($_POST['bk'])."',
				Email			='".strfilter($_POST['bl'])."',
				AlamatAsal		='".strfilter($_POST['bm'])."',
				KotaAsal		='".strfilter($_POST['bn'])."',
				RTAsal			='".strfilter($_POST['bo'])."',
				RWAsal			='".strfilter($_POST['bp'])."',
				KodePosAsal		='".strfilter($_POST['bq'])."',
				PropinsiAsal	='".strfilter($_POST['br'])."',
				NegaraAsal		='".strfilter($_POST['bs'])."',
				TeleponAsal		='".strfilter($_POST['bt'])."',
				AnakKe			='".strfilter($_POST['bu'])."',
				JumlahSaudara	='".strfilter($_POST['bv'])."',
				NamaAyah		='".strfilter($_POST['bw'])."',
				AgamaAyah		='".strfilter($_POST['bx'])."',
				PendidikanAyah	='".strfilter($_POST['by'])."',
				PekerjaanAyah	='".strfilter($_POST['bz'])."',
				HidupAyah		='".strfilter($_POST['ca'])."',
				NamaIbu			='".strfilter($_POST['cb'])."',
				AgamaIbu		='".strfilter($_POST['cc'])."',
				PendidikanIbu	='".strfilter($_POST['cd'])."',
				PekerjaanIbu	='".strfilter($_POST['ce'])."',
				HidupIbu		='".strfilter($_POST['cf'])."',
				AlamatOrtu		='".strfilter($_POST['cg'])."',
				KotaOrtu		='".strfilter($_POST['ch'])."',
				RTOrtu			='".strfilter($_POST['ci'])."',
				RWOrtu			='".strfilter($_POST['cj'])."',
				KodePosOrtu		='".strfilter($_POST['ck'])."',
				PropinsiOrtu	='".strfilter($_POST['cl'])."',
				NegaraOrtu		='".strfilter($_POST['cm'])."',
				TeleponOrtu		='".strfilter($_POST['cn'])."',
				HandphoneOrtu	='".strfilter($_POST['co'])."',
				EmailOrtu		='".strfilter($_POST['cp'])."',
				AsalSekolah		='".strfilter($_POST['AsalSekolah'])."',
				AsalSekolah1	='".strfilter($_POST['cr'])."',
				JenisSekolahID	='".strfilter($_POST['JenisSekolahID'])."',
				AlamatSekolah	='".strfilter($_POST['AlamatSekolah'])."',
				KotaSekolah		='".strfilter($_POST['cu'])."',
				JurusanSekolah	='".strfilter($_POST['cv'])."',
				NilaiSekolah	='".strfilter($_POST['cw'])."',
				TahunLulus		='".strfilter($_POST['cx'])."',
				IjazahSekolah	='".strfilter($_POST['cy'])."',
				AsalPT			='".strfilter($_POST['cz'])."',
				MhswIDAsalPT	='".strfilter($_POST['da'])."',
				ProdiAsalPT		='".strfilter($_POST['db'])."',
				LulusAsalPT		='".strfilter($_POST['LulusAsalPT'])."',
				TglLulusAsalPT	='".strfilter($_POST['dd'])."',
				IPKAsalPT		='".strfilter($_POST['de'])."',
				Pilihan1		='".strfilter($_POST['df'])."',
				Pilihan2		='".strfilter($_POST['dg'])."',
				Pilihan3		='".strfilter($_POST['dh'])."',
				BatasStudi	='".strfilter($_POST['di'])."',
			
				NA			='N',
				TanggalUjian	='".strfilter($_POST['dm'])."',
				LulusUjian	='".strfilter($_POST['LulusUjian'])."',
				RuangID		='".strfilter($_POST['do'])."',
				NomerUjian	='".strfilter($_POST['dp'])."',
				NilaiUjian	='".strfilter($_POST['dq'])."',
				GradeNilai	='".strfilter($_POST['dr'])."',
				TanggalLulus	='".strfilter($_POST['ds'])."',
				Syarat		='".strfilter($_POST['dt'])."',
				SyaratLengkap	='".strfilter($_POST['du'])."',
				BuktiSetoranMhsw	='".strfilter($_POST['dv'])."',
				TanggalSetoranMhsw='".strfilter($_POST['dw'])."',
			
				Dispensasi		='".strfilter($_POST['dz'])."',
				DispensasiID		='".strfilter($_POST['ea'])."',
				JudulDispensasi	='".strfilter($_POST['eb'])."',
				CatatanDispensasi	='".strfilter($_POST['ec'])."',
				NamaBank		='".strfilter($_POST['NamaBank'])."',
				NomerRekening	='".strfilter($_POST['NomerRekening'])."',
				IPK			='".strfilter($_POST['ef'])."',
				TotalSKS		='".strfilter($_POST['eg'])."',
				TotalSKSPindah='".strfilter($_POST['eh'])."',
				WisudaID		='".strfilter($_POST['ei'])."',
				TAID			='".strfilter($_POST['ej'])."',
				Predikat		='".strfilter($_POST['ek'])."',
				SKPenyetaraan	='".strfilter($_POST['el'])."',
				TglSKPenyetaraan	='".strfilter($_POST['em'])."',
				SKMasuk		='".strfilter($_POST['en'])."',
				TglSKMasuk	='".strfilter($_POST['eo'])."',
				SKKeluar		='".strfilter($_POST['ep'])."',
				TglSKKeluar		='".strfilter($_POST['eq'])."',
				TahunKeluar		='".strfilter($_POST['er'])."',
				CatatanKeluar	='".strfilter($_POST['es'])."',
				NoIdentitas		='".strfilter($_POST['et'])."',
				NoFakultas		='".strfilter($_POST['eu'])."',
				NoProdi			='".strfilter($_POST['ev'])."',
				NoIjazah		='".strfilter($_POST['ew'])."',
				TglIjazah		='".strfilter($_POST['ex'])."',
				LoginEdit		='".$_SESSION['_Login']."',
				TanggalEdit 	='".date('Y-m-d H:i:s')."',
				NIK 			='".strfilter($_POST['NIK'])."',
				IDKK 			='".strfilter($_POST['IDKK'])."',
				Kelurahan 		='".strfilter($_POST['Kelurahan'])."',
				Kecamatan 		='".strfilter($_POST['Kecamatan'])."'
				where MhswID	='".strfilter($_POST['MhswID'])."'");
			 if ($queryx){
				echo "<script>document.location='index.php?ndelox=master/mhs&act=detailmhs&id=".strfilter($_POST['MhswID'])."&program=".strfilter($_POST['program'])."&prodi=".strfilter($_POST['prodi'])."&sukses';</script>";
			 }else{
				echo "<script>document.location='index.php?ndelox=master/mhs&act=detailmhs&id=".strfilter($_POST['MhswID'])."&program=".strfilter($_POST['program'])."&prodi=".strfilter($_POST['prodi'])."&gagal';</script>";
			 }
        // }
      } //&act=detailmhs&id=".$_POST[MhswID]."
	  
$mw = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mhsw where MhswID='".strfilter($_GET['id'])."'"));	
$thnskrg		= date('Y-m-d');
$data 			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT datediff('$thnskrg', '$mw[TanggalLahir]')as selisih"));
$tahun 			= floor($data['selisih']/365);
$bulan 			= floor(($data['selisih'] - ($tahun * 365))/30);
$hari  			= $data['selisih'] - $bulan * 30 - $tahun * 365;   	  
echo "<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<h4><b style=color:green>INFORMASI MAHASISWA</b></h4>
<table class='table table-sm table-bordered'>
<tbody>              
<tr>
<th scope='row' style='width:200px'>NIM</th>
<th>$mw[MhswID]</th>
<th>Program </th>
<th>$mw[ProgramID]</th>
</tr>
						
<tr>
<th scope='row' >Nama Mahasiswa </th>
<th>$mw[Nama] &nbsp;&nbsp;&nbsp;( <b style=color:purple>Umur: $tahun Tahun $bulan Bulan $hari Hari </b>)</td>
<th scope='row' style='width:200px'>Program Studi</th> 
<th>$mw[ProdiID]</th>
</tr>        	
</tbody>
</table>
</div>
</div>
</div>



 <div class='card card-primary card-outline card-outline-tabs'>
						
              <div class='card-header p-0 border-bottom-0'>
                <ul class='nav nav-tabs' id='custom-tabs-three-tab' role='tablist'>
				
                  <li class='nav-item'>
                    <a class='nav-link active' id='custom-tabs-three-home-tab' data-toggle='pill' href='#custom-tabs-three-home' role='tab' aria-controls='custom-tabs-three-home' aria-selected='true'>Pribadi</a>
                  </li>
				  
                  <li class='nav-item'>
                    <a class='nav-link' id='custom-tabs-three-profile-tab' data-toggle='pill' href='#custom-tabs-three-profile' role='tab' aria-controls='custom-tabs-three-profile' aria-selected='false'>Alamat Tetap</a>
                  </li>
				  
                  <li class='nav-item'>
                    <a class='nav-link' id='custom-tabs-three-messages-tab' data-toggle='pill' href='#custom-tabs-three-messages' role='tab' aria-controls='custom-tabs-three-messages' aria-selected='false'>Akademik</a>
                  </li>
				  
                  <li class='nav-item'>
                    <a class='nav-link' id='custom-tabs-three-settings-tab' data-toggle='pill' href='#custom-tabs-three-settings' role='tab' aria-controls='custom-tabs-three-settings' aria-selected='false'>Orang Tua</a>
                  </li>
				  
				   <li class='nav-item'>
                    <a class='nav-link' id='custom-tabs-three-asalsek-tab' data-toggle='pill' href='#custom-tabs-three-asalsek' role='tab' aria-controls='custom-tabs-three-asalsek' aria-selected='false'>Asal Sekolah</a>
                  </li>
				  
				  <li class='nav-item'>
                    <a class='nav-link' id='custom-tabs-three-asalpt-tab' data-toggle='pill' href='#custom-tabs-three-asalpt' role='tab' aria-controls='custom-tabs-three-asalpt' aria-selected='false'>Asal PT</a>
                  </li>
				  
				  <li class='nav-item'>
                    <a class='nav-link' id='custom-tabs-three-bank-tab' data-toggle='pill' href='#custom-tabs-three-bank' role='tab' aria-controls='custom-tabs-three-bank' aria-selected='false'>Bank</a>
                  </li>
				  
                </ul>
              </div>
			  
              <div class='card-body'>
                <div class='tab-content' id='custom-tabs-three-tabContent'>
				
                  <div class='tab-pane fade show active' id='custom-tabs-three-home' role='tabpanel' aria-labelledby='custom-tabs-three-home-tab'>
                
						<form  method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
						<div class='row'>
							<div class='col-md-6'>
										<table class='table table-sm table-bordered'>
										<tbody>   
										<input type='hidden' class='form-control form-control-sm' name='MhswID' value='$_GET[id]'>  
										<input type='hidden' class='form-control form-control-sm' name='program' value='$_GET[program]'>  
										<input type='hidden' class='form-control form-control-sm' name='prodi' value='$_GET[prodi]'>                            
										<tr><th scope='row' width='260'>Nama</th> <td><input type='text' class='form-control form-control-sm' name='an' value='$mw[Nama]'></td></tr>   
										<tr><th scope='row'>Tempat Lahir</th> <td><input type='text' class='form-control form-control-sm' name='ax' value='$mw[TempatLahir]'></td></tr>
										<tr><th scope='row'>Tanggal Lahir</th> <td><input type='text' class='form-control form-control-sm tanggal' name='ay' value='".date('d-m-Y', strtotime($mw['TanggalLahir']))."'></td></tr> 
										<tr><th scope='row'>Jenis Kelamin</th>          
										<td><select class='form-control form-control-sm' name='au'> 
											<option value='0' selected>- Pilih Jenis Kelamin -</option>"; 
											$jk = mysqli_query($koneksi, "SELECT * FROM kelamin");
											while($a = mysqli_fetch_array($jk)){
												if ($a['Kelamin'] == $mw['Kelamin']){
												echo "<option value='$a[Kelamin]' selected>$a[Nama]</option>";
												}else{
												echo "<option value='$a[Kelamin]'>$a[Nama]</option>";
												}
											}
											echo "</select></td></tr> 
										<tr><th scope='row'>Warga Negara</th>          
										<td><select class='form-control form-control-sm' name='av'>"; 
											$w = mysqli_query($koneksi, "SELECT * FROM warganegara");
											while($a = mysqli_fetch_array($w)){
												if ($a['WargaNegara'] == $mw['WargaNegara']){
												echo "<option value='$a[WargaNegara]' selected>$a[Nama]</option>";
												}else{
												echo "<option value='$a[WargaNegara]'>$a[Nama]</option>";
												}
											}
											echo "</select></td></tr> 
										<tr><th scope='row'>Agama</th>          
										<td><select class='form-control form-control-sm' name='az'> 
										<option value='0' selected>- Pilih agama -</option>"; 
										$ag = mysqli_query($koneksi, "SELECT * FROM agama");
										while($a = mysqli_fetch_array($ag)){
										if ($a['Agama'] == $mw['Agama']){
											echo "<option value='$a[Agama]' selected>$a[Agama] - $a[Nama]</option>";
										}else{
											echo "<option value='$a[Agama]'>$a[Agama] - $a[Nama]</option>";
										}
										}
										echo "</select></td></tr>
										<tr><th scope='row'>Status Sipil</th>          
										<td><select class='form-control form-control-sm' name='ba'> 
										<option value='0' selected>- Pilih Status Sipil -</option>"; 
										$sp = mysqli_query($koneksi, "SELECT * FROM statussipil");
										while($a = mysqli_fetch_array($sp)){
										if ($a['StatusSipil'] == $mw['StatusSipil']){
											echo "<option value='$a[StatusSipil]' selected>$a[StatusSipil] - $a[Nama]</option>";
										}else{
											echo "<option value='$a[StatusSipil]'>$a[StatusSipil] - $a[Nama]</option>";
										}
										} 
										echo "</select></td></tr>
										<tr><th scope='row'>Alamat</th> <td><input type='text' class='form-control form-control-sm' name='bb' value='$mw[Alamat]'></td></tr>
										</tbody>
										</table>
									</div>
										<div class='col-md-6'>	
										<table class='table table-sm table-bordered'>
										<tbody>   								
										<tr><th scope='row'>RT</th> <td><input type='text' class='form-control form-control-sm' name='bd' value='$mw[RT]'></td></tr>
										<tr><th scope='row'>RW</th> <td><input type='text' class='form-control form-control-sm' name='be' value='$mw[RW]'></td></tr>   
										<tr><th scope='row'>Kota</th> <td><input type='text' class='form-control form-control-sm' name='bc' value='$mw[Kota]'></td></tr>   
										<tr><th scope='row'>Propinsi</th> <td><input type='text' class='form-control form-control-sm' name='bg' value='$mw[Propinsi]'></td></tr>   
										<tr><th scope='row'>Negara</th> <td><input type='text' class='form-control form-control-sm' name='bh' value='$mw[Negara]'></td></tr>   
										<tr><th scope='row'>Telp</th> <td><input type='text' class='form-control form-control-sm' name='bi' value='$mw[Telepon]'></td></tr>  
										<tr><th scope='row'>Handphone</th> <td><input type='text' class='form-control form-control-sm' name='bk' value='$mw[Handphone]'></td></tr>  
										<tr><th scope='row'>Email</th> <td><input type='text' class='form-control form-control-sm' name='bl' value='$mw[Email]'></td></tr>                           
										</tbody>
										</table>
								</div>
								</div>		
							  
                  </div>
                  <div class='tab-pane fade' id='custom-tabs-three-profile' role='tabpanel' aria-labelledby='custom-tabs-three-profile-tab'>
                    
					
							<table class='table table-sm table-bordered'>
							<tbody>
							<tr><th  scope='row' width='260'>Alamat Asal</th> <td><input type='text' class='form-control form-control-sm' name='bm' value='$mw[AlamatAsal]'></td></tr>
							<tr><th scope='row'>RT</th> <td><input type='text' class='form-control form-control-sm' name='bo' value='$mw[RT]'></td></tr>
							<tr><th scope='row'>RW</th> <td><input type='text' class='form-control form-control-sm' name='bp' value='$mw[RW]'></td></tr>
							<tr><th scope='row'>Kota</th> <td><input type='text' class='form-control form-control-sm' name='bn' value='$mw[Kota]'></td></tr>                              
							<tr><th  scope='row'>Propinsi</th> <td><input type='text' class='form-control form-control-sm' name='br' value='$mw[Propinsi]'></td></tr>
							<tr><th scope='row'>Negara </th> <td><input type='text' class='form-control form-control-sm' name='bs' value='$mw[Negara]'></td></tr>                              
							<tr><th  scope='row'>Telepon</th> <td><input type='text' class='form-control form-control-sm' name='bt' value='$mw[Telepon]'></td></tr>
							<tr><th  scope='row'>NIK</th> <td><input type='text' class='form-control form-control-sm' name='NIK' value='$mw[NIK]'></td></tr>
							<tr><th  scope='row'>ID KK</th> <td><input type='text' class='form-control form-control-sm' name='IDKK' value='$mw[IDKK]'></td></tr>
							<tr><th  scope='row'>Kelurahan</th> <td><input type='text' class='form-control form-control-sm' name='Kelurahan' value='$mw[Kelurahan]'></td></tr>
							<tr><th  scope='row'>Kecamatan</th> <td><input type='text' class='form-control form-control-sm' name='Kecamatan' value='$mw[Kecamatan]'></td></tr>
							</tbody>
							</table>
					
					
                  </div>
                  <div class='tab-pane fade' id='custom-tabs-three-messages' role='tabpanel' aria-labelledby='custom-tabs-three-messages-tab'>
                    
							<table class='table table-sm table-bordered'>
							<tbody>
							<tr><th scope='row' width='260'>Program</th>
							<td><select class='form-control form-control-sm' name='ar'> 
							<option value='0' selected>- Pilih Program -</option>"; 
							$prog = mysqli_query($koneksi, "SELECT * FROM program");
							while($a = mysqli_fetch_array($prog)){
							  if ($a['ProgramID'] == $mw['ProgramID']){
								  echo "<option value='$a[ProgramID]' selected>$a[Nama]</option>";
							  }else{
								  echo "<option value='$a[ProgramID]'>$a[Nama]</option>";
							  }
							}
							echo "</select></td></tr>

							<tr><th scope='row'>Program Studi</th>          
							<td><select class='form-control form-control-sm' name='as'> 
							<option value='0' selected>- Pilih Program -</option>"; 
							$prod = mysqli_query($koneksi, "SELECT * FROM prodi");
							while($a = mysqli_fetch_array($prod)){
							  if ($a['ProdiID'] == $mw['ProdiID']){
								echo "<option value='$a[ProdiID]' selected>$a[Nama]</option>";
							  }else{
								echo "<option value='$a[ProdiID]'>$a[Nama]</option>";
							  }
							}
							echo "</select></td></tr>
							<tr><th scope='row'>Status Awal</th>          
							<td><select class='form-control form-control-sm' name='ap'> 
							<option value='0' selected>- Pilih Status Awal -</option>"; 
							$sw = mysqli_query($koneksi, "SELECT * FROM statusawal");
							while($a = mysqli_fetch_array($sw)){
							  if ($a['StatusAwalID'] == $mw['StatusAwalID']){
								echo "<option value='$a[StatusAwalID]' selected>$a[Nama]</option>";
							  }else{
								echo "<option value='$a[StatusAwalID]'>$a[Nama]</option>";
							  }
							}
							echo "</select></td></tr>

							<tr><th scope='row'>Status Mahasiswa</th>          
							<td><select class='form-control form-control-sm' name='aq'> 
							<option value='0' selected>- Pilih Status Mahasiswa -</option>"; 
							$status = mysqli_query($koneksi, "SELECT * FROM statusmhsw");
							while($a = mysqli_fetch_array($status)){
							  if ($a['StatusMhswID'] == $mw['StatusMhswID']){
								echo "<option value='$a[StatusMhswID]' selected>$a[Nama]</option>";
							  }else{
								echo "<option value='$a[StatusMhswID]'>$a[Nama]</option>";
							  }
							}
							echo "</select></td></tr>

							<tr><th scope='row'>Penasehat Akademik</th>          
							<td><select class='form-control form-control-sm' name='at'> 
							<option value='0' selected>- Pilih Penasehat Akademik -</option>"; 
							$sql = mysqli_query($koneksi, "SELECT Login,Nama FROM dosen");
							while($a = mysqli_fetch_array($sql)){
							  if ($a['Login'] == $mw['PenasehatAkademik']){
								echo "<option value='$a[Login]' selected>$a[Nama]</option>";
							  }else{
								echo "<option value='$a[Login]'>$a[Nama]</option>";
							  }
							}
							echo "</select></td></tr>  
							<tr><th  scope='row'>Batas Studi</th> <td><input type='text' class='form-control form-control-sm' name='di' value='$mw[BatasStudi]'></td></tr>                           
							</tbody>
							</table>
					
					
					
                  </div>
                  <div class='tab-pane fade' id='custom-tabs-three-settings' role='tabpanel' aria-labelledby='custom-tabs-three-settings-tab'>
                    
					
					<table class='table table-sm table-bordered'>
					<tbody>
					<tr><th  scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>DATA AYAH</th></tr>
					<tr><th  scope='row' width='260'>Nama Ayah</th> <td><input type='text' class='form-control form-control-sm' name='bw' value='$mw[NamaAyah]'></td></tr>
					<tr><th scope='row'>Agama Ayah</th>          
					<td><select class='form-control form-control-sm' name='bx'> 
					<option value='0' selected>- Pilih agama -</option>"; 
					$ag = mysqli_query($koneksi, "SELECT * FROM agama");
					while($a = mysqli_fetch_array($ag)){
					  if ($a['Agama'] == $mw['AgamaAyah']){
						echo "<option value='$a[Agama]' selected>$a[Agama] - $a[Nama]</option>";
					  }else{
						echo "<option value='$a[Agama]'>$a[Agama] - $a[Nama]</option>";
					  }
					}
					echo "</select></td></tr>  
					<tr><th scope='row'>Hidup</th>          
					<td><select class='form-control form-control-sm' name='ca'> 
					"; 
					$hd = mysqli_query($koneksi, "SELECT * FROM hidup");
					while($a = mysqli_fetch_array($hd)){
					  if ($a['Hidup'] == $mw['HidupAyah']){
						echo "<option value='$a[Hidup]' selected>$a[Hidup] - $a[Nama]</option>";
					  }else{
						echo "<option value='$a[Hidup]'>$a[Hidup] - $a[Nama]</option>";
					  }
					}
					echo "</select></td></tr> 
					<tr><th scope='row'>Pendidikan Ayah</th>          
					<td><select class='form-control form-control-sm' name='by'> 
					<option value='0' selected>- Pilih Pendidikan Ayah -</option>"; 
					$kerja = mysqli_query($koneksi, "SELECT * FROM pendidikanortu");
					while($a = mysqli_fetch_array($kerja)){
					  if ($a['Pendidikan'] == $mw['PendidikanAyah']){
						echo "<option value='$a[Pendidikan]' selected>$a[Pendidikan] - $a[Nama]</option>";
					  }else{
						echo "<option value='$a[Pendidikan]'>$a[Pendidikan] - $a[Nama]</option>";
					  }
					}
					echo "</select></td></tr> 
					<tr><th scope='row'>Pekerjaan Ayah</th>          
					<td><select class='form-control form-control-sm' name='bz'> 
					<option value='0' selected>- Pilih Pekerjaan Ayah -</option>"; 
					$kerja = mysqli_query($koneksi, "SELECT * FROM pekerjaanortu");
					while($a = mysqli_fetch_array($kerja)){
					  if ($a['Pekerjaan'] == $mw['PekerjaanAyah']){
						echo "<option value='$a[Pekerjaan]' selected>$a[Pekerjaan] - $a[Nama]</option>";
					  }else{
						echo "<option value='$a[Pekerjaan]'>$a[Pekerjaan] - $a[Nama]</option>";
					  }
					}
					echo "</select></td></tr>  
					<tr><th scope='row'>Hidup</th>          
					<td><select class='form-control form-control-sm' name='ca'> 
					"; 
					$hd = mysqli_query($koneksi, "SELECT * FROM hidup");
					while($a = mysqli_fetch_array($hd)){
					  if ($a['Hidup'] == $mw['HidupAyah']){
						echo "<option value='$a[Hidup]' selected>$a[Hidup] - $a[Nama]</option>";
					  }else{
						echo "<option value='$a[Hidup]'>$a[Hidup] - $a[Nama]</option>";
					  }
					}
					echo "</select></td></tr> 
					<tr><th  scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>DATA IBU</th></tr>
					<tr><th  scope='row'>Nama Ibu</th> <td><input type='text' class='form-control form-control-sm' name='cb' value='$mw[NamaIbu]'></td></tr>
					<tr><th scope='row'>Agama Ibu</th>          
					<td><select class='form-control form-control-sm' name='cc'> 
					<option value='0' selected>- Pilih agama -</option>"; 
					$ag = mysqli_query($koneksi, "SELECT * FROM agama");
					while($a = mysqli_fetch_array($ag)){
					  if ($a['Agama'] == $mw['AgamaIbu']){
						echo "<option value='$a[Agama]' selected>$a[Agama] - $a[Nama]</option>";
					  }else{
						echo "<option value='$a[Agama]'>$a[Agama] - $a[Nama]</option>";
					  }
					}
					echo "</select></td></tr> 
					<tr><th scope='row'>Pendidikan Ibu</th>          
					<td><select class='form-control form-control-sm' name='cd'> 
					<option value='0' selected>- Pilih Pendidikan Ibu -</option>"; 
					$kerja = mysqli_query($koneksi, "SELECT * FROM pendidikanortu");
					while($a = mysqli_fetch_array($kerja)){
					  if ($a['Pendidikan'] == $mw['PendidikanIbu']){
						echo "<option value='$a[Pendidikan]' selected>$a[Pendidikan] - $a[Nama]</option>";
					  }else{
						echo "<option value='$a[Pendidikan]'>$a[Pendidikan] - $a[Nama]</option>";
					  }
					}
					echo "</select></td></tr> 
					<tr><th scope='row'>Pekerjaan Ibu</th>          
					<td><select class='form-control form-control-sm' name='ce'> 
					<option value='0' selected>- Pilih Pekerjaan Ibu -</option>"; 
					$kerja = mysqli_query($koneksi, "SELECT * FROM pekerjaanortu");
					while($a = mysqli_fetch_array($kerja)){
					  if ($a['Pekerjaan'] == $mw['PekerjaanIbu']){
						echo "<option value='$a[Pekerjaan]' selected>$a[Pekerjaan] - $a[Nama]</option>";
					  }else{
						echo "<option value='$a[Pekerjaan]'>$a[Pekerjaan] - $a[Nama]</option>";
					  }
					}
					echo "</select></td></tr>  
					<tr><th scope='row'>Hidup</th>          
					<td><select class='form-control form-control-sm' name='cf'> 
					"; 
					$hd = mysqli_query($koneksi, "SELECT * FROM hidup");
					while($a = mysqli_fetch_array($hd)){
					  if ($a['Hidup'] == $mw['HidupIbu']){
						echo "<option value='$a[Hidup]' selected>$a[Hidup] - $a[Nama]</option>";
					  }else{
						echo "<option value='$a[Hidup]'>$a[Hidup] - $a[Nama]</option>";
					  }
					}
					echo "</select></td></tr> 
					
					<tr><th  scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>ALAMAT ORANG TUA</th></tr>
					<tr><th  scope='row'>Alamat Orang Tua</th> <td><input type='text' class='form-control form-control-sm' name='cg' value='$mw[AlamatOrtu]'></td></tr>
					<tr><th  scope='row'>RT</th> <td><input type='text' class='form-control form-control-sm' name='ci' value='$mw[RTOrtu]'></td></tr>
					<tr><th  scope='row'>RW</th> <td><input type='text' class='form-control form-control-sm' name='cj' value='$mw[RWOrtu]'></td></tr>
					<tr><th  scope='row'>Kota</th> <td><input type='text' class='form-control form-control-sm' name='ch' value='$mw[KotaOrtu]'></td></tr>
					<tr><th  scope='row'>Propinsi</th> <td><input type='text' class='form-control form-control-sm' name='cl' value='$mw[PropinsiOrtu]'></td></tr>
					<tr><th  scope='row'>Negara</th> <td><input type='text' class='form-control form-control-sm' name='cm' value='$mw[NegaraOrtu]'></td></tr>
					<tr><th  scope='row'>Telepon</th> <td><input type='text' class='form-control form-control-sm' name='cn' value='$mw[TeleponOrtu]'></td></tr>
					<tr><th  scope='row'>Handphone</th> <td><input type='text' class='form-control form-control-sm' name='co' value='$mw[HandphoneOrtu]'></td></tr>
					<tr><th  scope='row'>Email</th> <td><input type='text' class='form-control form-control-sm' name='cp' value='$mw[EmailOrtu]'></td></tr>
					</tbody>
					</table>
					
                  </div>
				  
				   <div class='tab-pane fade' id='custom-tabs-three-asalsek' role='tabpanel' aria-labelledby='custom-tabs-three-asalsek-tab'>
                    
					<table class='table table-condensed table-bordered'>
					<tbody>
					<tr><th scope='row' width='260px'>Asal Sekolah <a href='?ndelox=asalsekolah' target='_BLANK'>[ Cari Sekolah ]</a></th>          
					<td><select class='form-control form-control-sm select2' name='AsalSekolah'> 
					<option value='' selected>- Pilih Asal Sekolah -</option>"; 
					$ag = mysqli_query($koneksi, "SELECT * FROM asalsekolah order by Kota ASC");
					while($a = mysqli_fetch_array($ag)){
					  if ($a['SekolahID'] == $mw['AsalSekolah']){
						echo "<option value='$a[SekolahID]' selected>$a[Kota] - $a[Nama] - $a[SekolahID]</option>";
					  }else{
						echo "<option value='$a[SekolahID]'>$a[Kota] - $a[Nama] - $a[SekolahID]</option>";
					  }
					}
					echo "</select></td></tr> 
					<tr><th  scope='row'>Alamat Sekolah</th> <td><input type='text' class='form-control form-control-sm' name='AlamatSekolah' value='$mw[AlamatSekolah]'></td></tr>

					<tr><th  scope='row'>Jenis Sekolah</th>       
					<td><select class='form-control form-control-sm' name='JenisSekolahID'> 
					<option value='' selected>- Pilih Jenis Sekolah -</option>"; 
					$sk = mysqli_query($koneksi, "SELECT * FROM jenissekolah order by TemplateSuratPMB ASC");
					while($a = mysqli_fetch_array($sk)){
					  if ($a['JenisSekolahID'] == $mw['JenisSekolahID']){
						echo "<option value='$a[JenisSekolahID]' selected>$a[Nama]</option>";
					  }else{
						echo "<option value='$a[JenisSekolahID]'>$a[Nama]</option>";
					  }
					}
					echo "</select> 
					</td></tr>
					<tr><th  scope='row'>Jurusan</th> <td><input type='text' class='form-control form-control-sm' name='cv' value='$mw[JurusanSekolah]'></td></tr>
					<tr><th  scope='row'>Tahun Lulus</th> <td><input type='text' class='form-control form-control-sm' name='cx' value='$mw[TahunLulus]'></td></tr>
					</tbody>
					</table>
					
					
                  </div>
				  
				  
				  <div class='tab-pane fade' id='custom-tabs-three-asalpt' role='tabpanel' aria-labelledby='custom-tabs-three-asalpt-tab'>
                    
					<table class='table table-condensed table-bordered'>
					<input type='hidden' class='form-control form-control-sm' name='MhswID' value='$mw[MhswID]'>
					<tbody>
					<tr><th  scope='row' width='260px'>Asal Perguruan Tinggi</th> <td><input type='text' class='form-control form-control-sm' name='cz' value='$mw[AsalPT]'></td></tr>
					<tr><th  scope='row'>Jurusan</th> <td><input type='text' class='form-control form-control-sm' name='db' value='$mw[ProdiAsalPT]'></td></tr>
					<tr><th  scope='row'>Lulus ASAL PT (Y/N)</th> <td><input type='text' class='form-control form-control-sm' name='LulusAsalPT' value='$mw[LulusAsalPT]'></td></tr>
					<tr><th  scope='row'>Nilai IPK</th> <td><input type='text' class='form-control form-control-sm' name='de' value='$mw[IPKAsalPT]'></td></tr>
					</tbody>
					</table>
					
                  </div>
				  
				  
				  <div class='tab-pane fade' id='custom-tabs-three-bank' role='tabpanel' aria-labelledby='custom-tabs-three-bank-tab'>
                   
				   	<table class='table table-condensed table-bordered'>
					<input type='hidden' class='form-control form-control-sm' name='xx' value='$mw[MhswID]'>
					<tbody>
					<tr><th  scope='row' width='260px'>Norek</th> <td><input type='text' class='form-control form-control-sm' name='NomerRekening' value='$mw[NomerRekening]'></td></tr>
					<tr><th  scope='row'>Nama Bank</th> <td><input type='text' class='form-control form-control-sm' name='NamaBank' value='$mw[NamaBank]'></td></tr>
					
					</tbody>
					</table>
				   
				   
                  </div>
				  
			
				  
                </div>
              </div>
              <!-- /.card -->		  
            </div>

			
			
		<div class='card'>
		<div class='card-header'>
		<div class='table-responsive'>			
					
		<div class='box-footer'>
		<button type='submit' name='ubahdata' class='btn btn-info'>Perbaharui Data</button>
		<a href='index.php?ndelox=master/mhs&id=".$_GET['id']."&program=".$_GET['program']."&prodi=".$_GET['prodi']."'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
		</div>

</div>
</div>
</div>


</form>";
}
?>