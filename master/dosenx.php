<div class='card'>
<div class='card-header'>
<div class="form-group row">
	<label class="col-md-8 col-form-label text-md-right"><b style='color:purple'>FILTER DATA</b></label>
	<div class="col-md-2">                 
	<form style='margin-right:7px; margin-top:0px' class='pull-right' action='' method='GET'>
	<input type="hidden" name='ndelox' value='master/dosenx'>

	<select name='prodi' class='form-control form-control-sm' onChange='this.form.submit()'>
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

	<div class="col-md-1">
		<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=master/dosenx&act=tambahdosen&prodi=<?php echo strfilter($_GET['prodi']); ?>&program=<?php echo strfilter($_GET['program']); ?>'>Add Data</a>
	</div>


</div>
</div>
</div>

<?php 
global $act;
if ($_GET['act']==''){ ?> 
	<div class='card'>
				<div class='card-header'>
				 <div class="box-header">
                  <h3 class="box-title"><b style='color:green;font-size:20px'>Master Dosen </b></h3>
                </div><!-- /.box-header -->
				<div class='table-responsive'>
                  <table id="example1" class="table table-sm table-striped">
                    <thead>
                      <tr style='background:purple;color:white'>
                        <th width='10px'>No</th>                       
						<th width='20px'>Login</th>
                        <th width='20px'>NIDN</th>
                        <th width='250px'>Nama</th> 
						<th width='200px'>Tempat & Tgl Lahir</th>						
                        <th width='30px'>Homebase</th>
                        <th width='30px'>Handphone</th>                          	                        		
                        <th width='40px'>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                     if ($_GET['prodi'] != ''){
						$tampil = mysqli_query($koneksi, "SELECT * FROM dosen where Homebase='".strfilter($_GET['prodi'])."' order by Noreg desc");//  and NA='N'
					 }					
					else{
					    $tampil = mysqli_query($koneksi, "SELECT * FROM dosen order by Noreg desc"); //where NA='N'
					 }
					
					$no = 1;
                    while($r=mysqli_fetch_array($tampil)){
                    $Namax 				= strtolower($r['Nama']);
					$Nama				= ucwords($Namax);
					$TempatLahirx 	    = strtolower($r['TempatLahir']);
					$TempatLahir 		= ucwords($TempatLahirx);
					if ($r['NA']=='N'){
						$stat="<i style='color:green' class='fa fa-eye'></i>";
					  }else{
						$stat="<i style='color:red' class='fa fa-eye-slash'></i>";
					  }
            
            		echo "<tr>
            		<td>$no</td>
            		<td>$r[Login]</td>
            		<td>$r[NIDN]</td>
            		<td>$Nama, $r[Gelar]</td>
            		<td>$TempatLahir, ".($r['TanggalLahir'])."</td>
            		<td>$r[Homebase]</td>
            		<td>$r[Handphone]</td>";		
            		echo "<td>
					$stat
            		<a class='btn btn-info btn-xs' title='Lihat Detail' href='?ndelox=master/dosenx&act=detaildosen&id=$r[Login]'><i class='fa fa-edit'></i></a>
            		<a class='btn btn-danger btn-xs' title='Delete Data' href='?ndelox=master/dosenx&hapus=$r[Login]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-trash'></i></a>
            		</td>
            		</tr>";
            		$no++;
		}
		if (isset($_GET['hapus'])){
		mysqli_query($koneksi, "DELETE FROM dosenx where Login='".strfilter($_GET['hapus'])."'");
		echo "<script>document.location='index.php?ndelox=master/dosenx';</script>";
		}
		?>
		</tbody>
		</table>
		</div>
		</div>
		</div>



<?php 
}

elseif($_GET['act']=='tambahdosen'){
  

  
   if (isset($_POST['tambah'])){
          $ProdiID = $_REQUEST['ProdiID'];
         $_ProdiID = (empty($ProdiID))? '' : '.'.implode('.', $ProdiID).'.';
  
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
					move_uploaded_file($file_tmp, 'pto_lect/'.$filenamee);			
					$query = mysqli_query($koneksi, "INSERT INTO dosen 
		  						(Login,
								KodeID,
								NIDN,					
								Nama,
								TempatLahir,
								TanggalLahir,
								LevelID,							
								PASSWORD,
								Handphone,
								ProdiID,
								Gelar,								
								LoginBuat,
								TanggalBuat,
								FotoBro) 
		  					VALUES
								('".strfilter($_POST['aa'])."',
								'SISFO',
								'".strfilter($_POST['NIDN'])."',
								'".strfilter($_POST['Nama'])."',
								'".strfilter($_POST['TempatLahir'])."',
								'".strfilter($_POST['TanggalLahir'])."',
								'100',
								'3434344dfdfdf21324343',
								'".strfilter($_POST['Handphone'])."',
								'$_ProdiID',
								'".strfilter($_POST['Gelar'])."',								
								'".$_SESSION['_Login']."',
								'".date('Y-m-d')."',
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
          $query = mysqli_query($koneksi, "INSERT INTO dosen
		  					(Login,
								KodeID,
								NIDN,					
								Nama,
								TempatLahir,
								TanggalLahir,
								LevelID,							
								PASSWORD,
								Handphone,
								ProdiID,
								Gelar,								
								LoginBuat,
								TanggalBuat) 
		  					VALUES
							    ('".strfilter($_POST['aa'])."',
								'SISFO',
								'".strfilter($_POST['NIDN'])."',
								'".strfilter($_POST['Nama'])."',
								'".strfilter($_POST['TempatLahir'])."',
								'".date('Y-m-d', strtotime($_POST['TanggalLahir']))."',
								'100',
								'3434344dfdfdf21324343',
								'".strfilter($_POST['Handphone'])."',
								'$_ProdiID',
								'".strfilter($_POST['Gelar'])."',								
								'".$_SESSION['_Login']."',
								'".date('Y-m-d')."')");
      }
        if ($query){
          echo "<script>document.location='index.php?ndelox=master/dosenx&act=detaildosen&id=".strfilter($_POST['aa'])."&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=master/dosenx&act=detaildosen&id=".strfilter($_POST['aa'])."&gagal';</script>";
        }
  }

 $_ProdiID = AmbilRadioProdi($w['ProdiID'], 'ProdiID');
echo"<div class='card'>
<div class='table-responsive'><div class='box-header with-border'>
<h3 class='box-title'><b style=color:green;font-size=12px>&nbsp;Informasi Dosen</b></h3>
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
                    <a class='nav-link' id='custom-tabs-three-settings-tab' data-toggle='pill' href='#custom-tabs-three-settings' role='tab' aria-controls='custom-tabs-three-settings' aria-selected='false'>Jabatan</a>
                  </li>

                </ul>
              </div>
			  
              <div class='card-body'>
                <div class='tab-content' id='custom-tabs-three-tabContent'>
				
                  <div class='tab-pane fade show active' id='custom-tabs-three-home' role='tabpanel' aria-labelledby='custom-tabs-three-home-tab'>
                
				  <form action='' method='POST' enctype='multipart/form-data' class='form-horizontal'>
				  <div class='row'>
				  <div class='col-md-6'>
								<table class='table table-sm table-bordered'>
								<tbody>      
								<tr><th  scope='row' colspan='2' style='background:purple;color:white'>DATA PRIBADI</th></tr> 
								<tr><th scope='row' width='280'>Login/NIP <b style='color:red'>*</b></th> <td><input type='text' class='form-control form-control-sm' name='aa' required></td></tr>                      
								<tr><th scope='row'>NIDN </th> <td><input type='text' class='form-control form-control-sm' name='NIDN' ></td></tr>
								<tr><th scope='row'>Nama <b style='color:red'>*</b></th> <td><input type='text' class='form-control form-control-sm' name='Nama' required></td></tr> 
								<tr><th scope='row'>Gelar <b style='color:red'>*</b></th> <td><input type='text' class='form-control form-control-sm' name='Gelar' required></td></tr>   
								<tr><th scope='row'>Tempat Lahir <b style='color:red'>*</b></th> <td><input type='text' class='form-control form-control-sm' name='TempatLahir' required></td></tr>
								<tr><th scope='row'>Tanggal Lahir <b style='color:red'>*</b></th> <td><input type='text' class='form-control form-control-sm tanggal' name='TanggalLahir' value'".date('d-m-Y', strtotime($mw['TanggalLahir']))."'></td></tr> 
								<tr><th scope='row'>Jenis Kelamin <b style='color:red'>*</b></th>          
								<td><select class='form-control form-control-sm' name='Kelamin'> 
								<option value='0' selected>- Pilih Jenis Kelamin -</option>"; 
								$status = mysqli_query($koneksi, "SELECT * FROM kelamin");
								while($a = mysqli_fetch_array($status)){
									echo "<option value='$a[Kelamin]' selected>$a[Nama]</option>";
								}
								echo "</select></td></tr>  
								</tbody>
								</table>
								<b style='color:red'>*</b> wajib di isi!
						</div>
						<div class='col-md-6'>									
								<table class='table table-sm table-bordered'>
								<tr><th  scope='row' colspan='2' style='background:purple;color:white'>&nbsp;</th></tr> 			
								<tr><th scope='row'>Handphone <b style='color:red'>*</b></th> 		<td><input type='text' class='form-control form-control-sm' name='Handphone' ></td></tr>  
								<tr><th scope='row'>Email <b style='color:red'>*</b></th> 			<td><input type='text' class='form-control form-control-sm' name='Email' ></td></tr>  
					
								<tr><th scope='row'>Upload Foto (Ukuran < 55000 Byte)</th>             <td><div style='position:relative;''>
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
							
							  
                  </div>

                  <div class='tab-pane fade' id='custom-tabs-three-profile' role='tabpanel' aria-labelledby='custom-tabs-three-profile-tab'>                   					
						<table class='table table-sm table-bordered'>
						<tbody>
						<tr><th  scope='row' colspan='2' style='background:purple;color:white'>ALAMAT TETAP</th></tr> 
						<tr><th  scope='row' width='280'>No KTP <b style='color:red'>*</b></th> <td><input type='text' class='form-control form-control-sm' name='aj' ></td></tr>
						<tr><th  scope='row'>Alamat</th> <td><input type='text' class='form-control form-control-sm' name='ao' ></td></tr>
						<tr><th scope='row'>Kota </th> <td><input type='text' class='form-control form-control-sm' name='ap' ></td></tr>                              
						</tbody>
						</table>												
                  </div>

                  <div class='tab-pane fade' id='custom-tabs-three-messages' role='tabpanel' aria-labelledby='custom-tabs-three-messages-tab'>
                    
						<table class='table table-sm table-bordered'>
						<tbody>
						
						<tr><th  scope='row' colspan='2' style='background:purple;color:white'>DATA AKADEMIK</th></tr> 
						<tr><th  scope='row' width='280'>Mulai Bekerja	</th> <td><input type='text' class='form-control form-control-sm' name='bt' ></td></tr>
						<tr><th scope='row'>Status Dosen</th>
						<td><select class='form-control form-control-sm' name='bi'> 
						<option value='0' selected>- Pilih Status -</option>"; 
						$ds = mysqli_query($koneksi, "SELECT * FROM statusdosenx");
						while($a = mysqli_fetch_array($ds)){
							if ($a['StatusDosenID'] == $mw['StatusDosenID']){
								echo "<option value='$a[StatusDosenID]' selected>$a[Nama]</option>";
							}else{
								echo "<option value='$a[StatusDosenID]'>$a[Nama]</option>";
							}
						}
						echo "</select></td></tr>
						
						<tr><th scope='row'>Status Kerja</th>
						<td><select class='form-control form-control-sm' name='bj'> 
						<option value='0' selected>- Pilih Status Kerja -</option>"; 
						$prog = mysqli_query($koneksi, "SELECT * FROM statuskerja");
						while($a = mysqli_fetch_array($prog)){
							if ($a['StatusKerjaID'] == $mw['StatusKerjaID']){
								echo "<option value='$a[StatusKerjaID]' selected>$a[Nama]</option>";
							}else{
								echo "<option value='$a[StatusKerjaID]'>$a[Nama]</option>";
							}
						}
						echo "</select></td></tr>
						
						<tr><th scope='row'>Prodi Homebase</th>
						<td><select class='form-control form-control-sm' name='au'> 
						<option value='0' selected>- Pilih Prodi -</option>"; 
						$prod = mysqli_query($koneksi, "SELECT * FROM prodi");
						while($a = mysqli_fetch_array($prod)){
							if ($a['ProdiID'] == $mw['ProdiID']){
								echo "<option value='$a[ProdiID]' selected>$a[Nama]</option>";
							}else{
								echo "<option value='$a[ProdiID]'>$a[Nama]</option>";
							}
						}
						
						echo "</select></td></tr>
						
						<tr><th scope='row'>Mengajar di Prodi</th>          
						<td>"; 
						echo "
						$_ProdiID
						<input type='checkbox' name='PINDAHAN' checked> PINDAHAN - Pindahan</br>
								<input type='checkbox' name='REGA' checked> REG A - Reguler Jalur A</br>
								<input type='checkbox' name='TRANS' checked> TRANS - Transfer D3 ke S1 </br>
								<input type='checkbox' name='NREGB' > NREG B - Non Reguler Jalur B </br>						
						</td></tr>
						<tr><th  scope='row'>Kode Instansi Induk	</th> <td><input type='text' class='form-control form-control-sm' name='bh' ></td></tr>
						<tr><th  scope='row'>Lulus Perg. Tinggi</th> <td><input type='text' class='form-control form-control-sm' name='az' ></td></tr>
						<tr><th scope='row'>Jenjang Pendidikan</th>          
						<td><select class='form-control form-control-sm' name='ax'> 
						<option value='0' selected>- Pilih Jenjang Pendidikan -</option>"; 
						$jn = mysqli_query($koneksi, "SELECT * FROM jenjang");
						while($a = mysqli_fetch_array($jn)){
							if ($a['JenjangID'] == $st['JenjangID']){
							echo "<option value='$a[JenjangID]' selected>$a[Nama]</option>";
							}else{
							echo "<option value='$a[JenjangID]'>$a[Nama]</option>";
							}
						}
						echo "</select></td></tr>
						
						<tr><th  scope='row'>Keilmuan</th> <td><input type='text' class='form-control form-control-sm' name='ay' ></td></tr>
												
						</tbody>
						</table>
					
                  </div>
                  <div class='tab-pane fade' id='custom-tabs-three-settings' role='tabpanel' aria-labelledby='custom-tabs-three-settings-tab'>
                    
				
				  <table class='table table-condensed table-bordered'>
				  <tbody>
				  <tr><th  scope='row' colspan='2' style='background:purple;color:white'>DATA JABATAN</th></tr>
				  <tr><th scope='row' width='280'>Jabatan Akademik</th>          
				  <td><select class='form-control form-control-sm' name='bf'> 
				  <option value='0' selected>- Pilih Jabatan -</option>"; 
				  $ag = mysqli_query($koneksi, "SELECT * FROM jabatan");
				  while($a = mysqli_fetch_array($ag)){
					if ($a['JabatanID'] == $mw['JabatanID']){
					  echo "<option value='$a[JabatanID]' selected>$a[JabatanID] - $a[Nama]</option>";
					}else{
					  echo "<option value='$a[JabatanID]'>$a[JabatanID] - $a[Nama]</option>";
					}
				  }
				  echo "</select></td></tr>  
				  <tr><th scope='row'>Jabatan Dikti</th>          
				  <td><select class='form-control form-control-sm' name='bg'> 
				  <option value='0' selected>- Pilih Jabatan Dikti -</option>"; 
				  $ag = mysqli_query($koneksi, "SELECT * FROM jabatandikti");
				  while($a = mysqli_fetch_array($ag)){
					if ($a['JabatanDiktiID'] == $mw['JabatanDiktiID']){
					  echo "<option value='$a[JabatanDiktiID]' selected>$a[JabatanDiktiID] - $a[Nama]</option>";
					}else{
					  echo "<option value='$a[JabatanDiktiID]'>$a[JabatanDiktiID] - $a[Nama]</option>";
					}
				  }
				  echo "</select></td></tr>  
				  <tr><th scope='row'>Golongan</th>          
				  <td><select class='form-control form-control-sm' name='bc'> 
				  <option value='0' selected>- Pilih Golongan -</option>"; 
				  $kerja = mysqli_query($koneksi, "SELECT * FROM golongan");
				  while($a = mysqli_fetch_array($kerja)){
					if ($a['GolonganID'] == $mw['GolonganID']){
					  echo "<option value='$a[GolonganID]' selected>$a[GolonganID] - $a[Nama]</option>";
					}else{
					  echo "<option value='$a[GolonganID]'>$a[GolonganID] - $a[Nama]</option>";
					}
				  }
				  echo "</select></td></tr> 
				  <tr><th scope='row'>Tunjangan Ikatan</th>          
				  <td><select class='form-control form-control-sm' name='be'> 
				  <option value='0' selected>- Pilih Tunjangan Ikatan -</option>"; 
				  $ik = mysqli_query($koneksi, "SELECT * FROM ikatan");
				  while($a = mysqli_fetch_array($ik)){
					if ($a['IkatanID'] == $mw['IkatanID']){
					  echo "<option value='$a[IkatanID]' selected>$a[IkatanID] - $a[Nama]</option>";
					}else{
					  echo "<option value='$a[IkatanID]'>$a[IkatanID] - $a[Nama]</option>";
					}
				  }
				  echo "</select></td></tr>  
				  
				  </tbody>
				  </table>

                  </div>
				
				  
			
				  
                </div>
              </div>	  
            </div>";
			
			
			echo"<div class='card'>
				<div class='card-header'>
				<div class='table-responsive'>
					<div class='box-footer'>
					<button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
					<a href='index.php?ndelox=master/dosenx'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
					</div>
				</div>
				</div>
				</div>	
				</form>";


}elseif($_GET['act']=='editdosen'){
  if (isset($_POST['update1'])){
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
					move_uploaded_file($file_tmp, 'pto_lect/'.$filenamee);	
					$query = mysqli_query("UPDATE dosenx SET                           
                           Nama   	= '".strfilter($_POST['an'])."',
                           FotoBro 	= '$filenamee' 
						   where Login='".strfilter($_POST['id'])."'");
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
          $query = mysqli_query("UPDATE dosenx SET                           
                           Nama     = '".strfilter($_POST['an'])."'
						   where Login='".strfilter($_POST['id'])."'");
      }
        if ($query){
          echo "<script>document.location='index.php?ndelox=master/dosenx&act=editdosen&id=".$_POST['id']."&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=master/dosenx&act=editdosen&id=".$_POST['id']."&gagal';</script>";
        }
  }
    
    $detail = mysqli_query($koneksi, "SELECT * FROM dosen where Login='".strfilter($_GET['id'])."'");
    $s = mysqli_fetch_array($detail);
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Data Dosen</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-7'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='$s[Login]'>
                    <tr><th style='background:purple;color:white' width='160px' rowspan='25'>";
                        if (trim($s['FotoBro'])==''){
                          echo "<img class='img-thumbnail' style='width:155px' src='pto_lect/no-image.jpg'>";
                        }else{
                          echo "<img class='img-thumbnail' style='width:155px' src='pto_lect/$s[FotoBro]'>";
                        }
                        echo "</th>
                    </tr>
                    <input type='hidden' name='id' value='$s[Login]'>
                    <tr><th width='120px' scope='row'>Login</th>      <td><input type='text' class='form-control form-control-sm' value='$s[Login]' name='aa'></td></tr>
					 <tr><th width='120px' scope='row'>Nama Dosen</th>      <td><input type='text' class='form-control form-control-sm' value='$s[Nama]' name='an'></td></tr>
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
                </div> 
                <div style='clear:both'></div>
                        <div class='box-footer'>
                          <button type='submit' name='update1' class='btn btn-info'>Update</button>
                          <a href='index.php?ndelox=master/dosenx'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                        </div> 
              </div>
            </form>
            </div>";

}elseif($_GET['act']=='detaildosen'){    
	  
if (isset($_POST['ubahdata'])){
      
      $_ProdiID = array();
      $_ProdiID = $_REQUEST['ProdiID'];
      $ProdiID = implode('.', $_ProdiID);
      $ProdiID = ".$ProdiID.";
      
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
					move_uploaded_file($file_tmp, 'pto_lect/'.$filenamee);
					$query = mysqli_query($koneksi, "UPDATE dosen SET				
					KodeID			='SISFO',
					NIDN			='".strfilter($_POST['ac'])."',
					HomebaseInduk	='".strfilter($_POST['ad'])."',
					NIPPNS			='".strfilter($_POST['ae'])."',
					Nama			='".strfilter($_POST['af'])."',
					TempatLahir		='".strfilter($_POST['ag'])."',
					TanggalLahir	='".date('Y-m-d', strtotime($_POST['ah']))."',
					KTP				='".strfilter($_POST['aj'])."',
					Telephone		='".strfilter($_POST['ak'])."',
					PASSWORD		='".strfilter($_POST['al'])."',
					Handphone		='".strfilter($_POST['am'])."',
					Email			='".strfilter($_POST['an'])."',
					Alamat			='".strfilter($_POST['ao'])."',
					Kota			='".strfilter($_POST['ap'])."',
					KodePos			='".strfilter($_POST['aq'])."',
					Propinsi		='".strfilter($_POST['ar'])."',
					Negara			='".strfilter($_POST['as'])."',
					NA				='".strfilter($_POST['at'])."',
					Homebase		='".strfilter($_POST['au'])."',
					ProdiID			='$ProdiID',
					Gelar			='".strfilter($_POST['aw'])."',
					JenjangID		='".strfilter($_POST['ax'])."',
					Keilmuan		='".strfilter($_POST['ay'])."',
					LulusanPT		='".strfilter($_POST['az'])."',
					AgamaID			='".strfilter($_POST['ba'])."',
					KelaminID		='".strfilter($_POST['bb'])."',
					GolonganID		='".strfilter($_POST['bc'])."',
					KategoriID		='".strfilter($_POST['bd'])."',
					IkatanID		='".strfilter($_POST['be'])."',
					JabatanID		='".strfilter($_POST['bf'])."',
					JabatanDiktiID	='".strfilter($_POST['bg'])."',
					InstitusiInduk	='".strfilter($_POST['bh'])."',
					StatusDosenID	='".strfilter($_POST['bi'])."',
					StatusKerjaID	='".strfilter($_POST['bj'])."',
					TglBekerja		='".strfilter($_POST['bk'])."',
					NamaBank		='".strfilter($_POST['bl'])."',
					NamaAkun		='".strfilter($_POST['bm'])."',
					NomerAkun		='".strfilter($_POST['bn'])."',
					LoginEdit		='".$_SESSION['_Login']."',
					TanggalEdit		='".date('Y-m-d')."',
					Noreg			='".strfilter($_POST['Noreg'])."',
					FotoBro			='$filenamee' 
					WHERE Login		='".strfilter($_POST['id'])."'");		
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
				$query = mysqli_query($koneksi, "UPDATE dosen SET				
					KodeID			='SISFO',
					NIDN			='".strfilter($_POST['ac'])."',
					HomebaseInduk	='".strfilter($_POST['ad'])."',
					NIPPNS			='".strfilter($_POST['ae'])."',
					Nama			='".strfilter($_POST['af'])."',
					TempatLahir		='".strfilter($_POST['ag'])."',
					TanggalLahir	='".date('Y-m-d', strtotime($_POST['ah']))."',
					KTP				='".strfilter($_POST['aj'])."',
					Telephone		='".strfilter($_POST['ak'])."',
					PASSWORD		='".strfilter($_POST['al'])."',
					Handphone		='".strfilter($_POST['am'])."',
					Email			='".strfilter($_POST['an'])."',
					Alamat			='".strfilter($_POST['ao'])."',
					Kota			='".strfilter($_POST['ap'])."',
					KodePos			='".strfilter($_POST['aq'])."',
					Propinsi		='".strfilter($_POST['ar'])."',
					Negara			='".strfilter($_POST['as'])."',
					NA				='".strfilter($_POST['at'])."',
					Homebase		='".strfilter($_POST['au'])."',
					ProdiID			='$ProdiID',
					Gelar			='".strfilter($_POST['aw'])."',
					JenjangID		='".strfilter($_POST['ax'])."',
					Keilmuan		='".strfilter($_POST['ay'])."',
					LulusanPT		='".strfilter($_POST['az'])."',
					AgamaID			='".strfilter($_POST['ba'])."',
					KelaminID		='".strfilter($_POST['bb'])."',
					GolonganID		='".strfilter($_POST['bc'])."',
					KategoriID		='".strfilter($_POST['bd'])."',
					IkatanID		='".strfilter($_POST['be'])."',
					JabatanID		='".strfilter($_POST['bf'])."',
					JabatanDiktiID	='".strfilter($_POST['bg'])."',
					InstitusiInduk	='".strfilter($_POST['bh'])."',
					StatusDosenID	='".strfilter($_POST['bi'])."',
					StatusKerjaID	='".strfilter($_POST['bj'])."',
					TglBekerja		='".date('Y-m-d', strtotime($_POST['bk']))."',
					NamaBank		='".strfilter($_POST['bl'])."',
					NamaAkun		='".strfilter($_POST['bm'])."',
					NomerAkun		='".strfilter($_POST['bn'])."',
					LoginEdit		='".$_SESSION['_Login']."',
					Noreg			='".strfilter($_POST['Noreg'])."',
					TanggalEdit		='".date('Y-m-d')."'
					WHERE Login		='".strfilter($_POST['id'])."'");
			echo "<script>document.location='index.php?ndelox=master/dosenx&act=detaildosen&id=".strfilter($_POST['id'])."&sukses';</script>";
			  }
      }
	  
 $mw = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM dosen where Login='".strfilter($_GET['id'])."'")); 	  
 $_ProdiID = AmbilRadioProdi($mw['ProdiID'], 'ProdiID');
 
 echo"<div class='card'>
 <div class='table-responsive'><div class='box-header with-border'>
 <h3 class='box-title'><b style=color:green;font-size=12px>&nbsp;Informasi Dosen:</b> <b style=color:purple;font-size=12px>&nbsp;$mw[Nama], $mw[Gelar]</b></h3>
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
					 <a class='nav-link' id='custom-tabs-three-settings-tab' data-toggle='pill' href='#custom-tabs-three-settings' role='tab' aria-controls='custom-tabs-three-settings' aria-selected='false'>Jabatan</a>
				   </li>
				   
					<li class='nav-item'>
					 <a class='nav-link' id='custom-tabs-three-pengajaran-tab' data-toggle='pill' href='#custom-tabs-three-pengajaran' role='tab' aria-controls='custom-tabs-three-pengajaran' aria-selected='false'>Pengajaran</a>
				   </li>
				   
				   <li class='nav-item'>
					 <a class='nav-link' id='custom-tabs-three-asalpt-tab' data-toggle='pill' href='#custom-tabs-three-penelitian' role='tab' aria-controls='custom-tabs-three-penelitian' aria-selected='false'>Penelitian</a>
				   </li>
				   
				   <li class='nav-item'>
					 <a class='nav-link' id='custom-tabs-three-bank-tab' data-toggle='pill' href='#custom-tabs-three-pendidikan' role='tab' aria-controls='custom-tabs-three-pendidikan' aria-selected='false'>Pendidikan</a>
				   </li>
 
				   <li class='nav-item'>
				   <a class='nav-link' id='custom-tabs-three-pekerjaan-tab' data-toggle='pill' href='#custom-tabs-three-pekerjaan' role='tab' aria-controls='custom-tabs-three-pekerjaan' aria-selected='false'>Nama Bank</a>
				 </li>
				   
				 </ul>
			   </div>
			   
			   <div class='card-body'>
				 <div class='tab-content' id='custom-tabs-three-tabContent'>
				 
				   <div class='tab-pane fade show active' id='custom-tabs-three-home' role='tabpanel' aria-labelledby='custom-tabs-three-home-tab'>
				 
							<form action='' method='POST' enctype='multipart/form-data' class='form-horizontal'>
							
							<input type='hidden' class='form-control form-control-sm' name='id' value='$_GET[id]'>
							<div class='row'>
									<div class='col-md-6'>
											<table class='table table-sm table-bordered'>
											<tbody>      
											<tr><th  scope='row' colspan='2' style='background:purple;color:white'>DATA PRIBADI</th></tr> 
											<tr><th scope='row' width='280'>Login/NIP</th> <td><input type='text' class='form-control form-control-sm' name='aa' value='$mw[Login]'></td></tr>                      
											<tr><th scope='row'>NIDN</th> <td><input type='text' class='form-control form-control-sm' name='ac' value='$mw[NIDN]'></td></tr>
											<tr><th scope='row'>Nama</th> <td><input type='text' class='form-control form-control-sm' name='af' value='$mw[Nama]'></td></tr>
											<tr><th scope='row'>Gelar</th> <td><input type='text' class='form-control form-control-sm' name='aw' value='$mw[Gelar]'></td></tr>      
											<tr><th scope='row'>Tempat Lahir</th> <td><input type='text' class='form-control form-control-sm' name='ag' value='$mw[TempatLahir]'></td></tr>
											<tr><th scope='row'>Tanggal Lahir</th> <td><input type='text' class='form-control form-control-sm tanggal' name='ah' value='".date('d-m-Y', strtotime($mw['TanggalLahir']))."'></td></tr> 
											<tr><th scope='row'>Jenis Kelamin</th>          
											<td><select class='form-control form-control-sm' name='bb'> 
											<option value='0' selected>- Pilih Jenis Kelamin -</option>"; 
											$kl = mysqli_query($koneksi, "SELECT * FROM kelamin");
											while($a = mysqli_fetch_array($kl)){
											if ($a['Kelamin'] == $mw['KelaminID']){
												echo "<option value='$a[Kelamin]' selected>$a[Nama]</option>";
												}else{
												echo "<option value='$a[Kelamin]' >$a[Nama]</option>";
											}
											}
											echo "</select></td></tr>  
											
											<tr><th scope='row'>Agama</th>          
											<td><select class='form-control form-control-sm' name='ba'> 
											<option value='0' selected>- Pilih Agama -</option>"; 
											$ag = mysqli_query($koneksi, "SELECT * FROM agama");
											while($a = mysqli_fetch_array($ag)){
											if ($a['Agama'] == $mw['AgamaID']){
												echo "<option value='$a[Agama]' selected>$a[Nama]</option>";
												}else{
												echo "<option value='$a[Agama]' >$a[Nama]</option>";
											}
											}
											echo "</select></td></tr> 
											<tr><th scope='row'>Telp</th> 			<td><input type='text' class='form-control form-control-sm' name='ak' value='$mw[Telephone]'></td></tr>  
											<tr><th scope='row'>Handphone</th> 		<td><input type='text' class='form-control form-control-sm' name='am' value='$mw[Handphone]'></td></tr>
											</tbody>
											</table>
										</div>

										<div class='col-md-6'>
										<table class='table table-sm table-bordered'>
											<tbody>
											<tr><th  scope='row' colspan='2' style='background:purple;color:white'>&nbsp;</th></tr>   
											<tr><th scope='row'>Email</th> 			<td><input type='text' class='form-control form-control-sm' name='an' value='$mw[Email]'></td></tr>  
										
											<tr><th scope='row'>Aktif</th>                <td>";
												if ($mw['NA']=='Y'){
													echo "<input type='radio' name='at' value='N'> Ya
													<input type='radio' name='at' value='Y' checked> Tidak";
												}else{
													echo "<input type='radio' name='at' value='N' checked> Ya 
													<input type='radio' name='at' value='Y'> Tidak";
												}
											echo "</td></tr>  
											
											<tr><th scope='row'>Foto</th><th style='background:purple;color:white' >";
												if (trim($mw['FotoBro'])=='-'){
													echo "<img class='img-thumbnail' style='width:155px' src='pto_lect/no-image.jpg'>";
												}else{
													echo "<img class='img-thumbnail' style='width:155px' src='pto_lect/$mw[FotoBro]'>";
												}
												echo "</th>
											</tr>
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
								</div>
								</div>			
							 
							   
				   </div>
				   <div class='tab-pane fade' id='custom-tabs-three-profile' role='tabpanel' aria-labelledby='custom-tabs-three-profile-tab'>
					 
					 
							<table class='table table-sm table-bordered'>
							<tbody>
							<tr><th  scope='row' colspan='2' style='background:purple;color:white'>ALAMAT TETAP</th></tr> 
							<tr><th  scope='row' width='280'>No KTP</th> <td><input type='text' class='form-control form-control-sm' name='aj' value='$mw[KTP]'></td></tr>
							<tr><th scope='row'>No Telepon</th> <td><input type='text' class='form-control form-control-sm' name='xx' value='$mw[Telephone]'></td></tr>
							<tr><th scope='row'>No HP</th> <td><input type='text' class='form-control form-control-sm' name='xx' value='$mw[Handphone]'></td></tr>
							<tr><th scope='row'>E-mail</th> <td><input type='text' class='form-control form-control-sm' name='xx' value='$mw[Email]'></td></tr>                              
							<tr><th  scope='row'>Alamat</th> <td><input type='text' class='form-control form-control-sm' name='ao' value='$mw[Alamat]'></td></tr>
							<tr><th scope='row'>Kota </th> <td><input type='text' class='form-control form-control-sm' name='ap' value='$mw[Kota]'></td></tr>                              
							<tr><th  scope='row'>Kode Pos	</th> <td><input type='text' class='form-control form-control-sm' name='aq' value='$mw[KodePos]'></td></tr>
							<tr><th  scope='row'>Propinsi	</th> <td><input type='text' class='form-control form-control-sm' name='ar'value='$mw[Propinsi]' ></td></tr>
							<tr><th  scope='row'>Negara	</th> <td><input type='text' class='form-control form-control-sm' name='as'value='$mw[Negara]' ></td></tr>
							</tbody>
							</table>
					 
				   </div>
				   <div class='tab-pane fade' id='custom-tabs-three-messages' role='tabpanel' aria-labelledby='custom-tabs-three-messages-tab'>
					 
				   <table class='table table-condensed table-bordered'>
				   <tbody>
				   
				   <tr><th  scope='row' colspan='2' style='background:purple;color:white'>DATA AKADEMIK</th></tr> 
				   <tr><th  scope='row' width='280'>Mulai Bekerja	</th> <td><input type='text' class='form-control form-control-sm tanggal' name='bk' value='".date('d-m-Y', strtotime($mw['TglBekerja']))."'></td></tr>
				   <tr><th scope='row'>Status Dosen</th>
				   <td><select class='form-control form-control-sm' name='bi'> 
				   <option value='0' selected>- Pilih Status -</option>"; 
				   $ds = mysqli_query($koneksi, "SELECT * FROM statusdosen");
				   while($a = mysqli_fetch_array($ds)){
					 if ($a['StatusDosenID'] == $mw['StatusDosenID']){
						 echo "<option value='$a[StatusDosenID]' selected>$a[Nama]</option>";
					 }else{
						 echo "<option value='$a[StatusDosenID]'>$a[Nama]</option>";
					 }
				   }
				   echo "</select></td></tr>
				   
				   <tr><th scope='row'>Status Kerja</th>
				   <td><select class='form-control form-control-sm' name='bj'> 
				   <option value='0' selected>- Pilih Status Kerja -</option>"; 
				   $prog = mysqli_query($koneksi, "SELECT * FROM statuskerja");
				   while($a = mysqli_fetch_array($prog)){
					 if ($a['StatusKerjaID'] == $mw['StatusKerjaID']){
						 echo "<option value='$a[StatusKerjaID]' selected>$a[Nama]</option>";
					 }else{
						 echo "<option value='$a[StatusKerjaID]'>$a[Nama]</option>";
					 }
				   }
				   echo "</select></td></tr>
				   
				   <tr><th scope='row'>Prodi Homebase</th>
				   <td><select class='form-control form-control-sm' name='au'> 
				   <option value='0' selected>- Pilih Prodi -</option>"; 
				   $prod = mysqli_query($koneksi, "SELECT * FROM prodi");
				   while($a = mysqli_fetch_array($prod)){
					 if ($a['ProdiID'] == $mw['Homebase']){
						 echo "<option value='$a[ProdiID]' selected>$a[Nama]</option>";
					 }else{
						 echo "<option value='$a[ProdiID]'>$a[Nama]</option>";
					 }
				   }
				   echo "</select></td></tr>
				   
				   <tr><th scope='row'>Mengajar di Prodi</th><td>$_ProdiID</td></tr>
				   <tr><th  scope='row'>Kode Instansi Induk	</th> <td><input type='text' class='form-control form-control-sm' name='bh' value='$mw[InstitusiInduk]'></td></tr>
				   <tr><th  scope='row'>Lulus Perg. Tinggi</th> <td><input type='text' class='form-control form-control-sm' name='az' value='$mw[LulusanPT]'></td></tr>
				   <tr><th scope='row'>Jenjang Pendidikan</th>          
				   <td><select class='form-control form-control-sm' name='ax'> 
				   <option value='0' selected>- Pilih Jenjang Pendidikan -</option>"; 
				   $jn = mysqli_query($koneksi, "SELECT * FROM jenjang");
				   while($a = mysqli_fetch_array($jn)){
					 if ($a['JenjangID'] == $mw['JenjangID']){
					   echo "<option value='$a[JenjangID]' selected>$a[Nama]</option>";
					 }else{
					   echo "<option value='$a[JenjangID]'>$a[Nama]</option>";
					 }
				   }
				   echo "</select></td></tr>
				   
				   <tr><th  scope='row'>Keilmuan</th> <td><input type='text' class='form-control form-control-sm' name='ay' value='$mw[Keilmuan]'></td></tr>
										   
				   </tbody>
				   </table>
					 
				   </div>
				   <div class='tab-pane fade' id='custom-tabs-three-settings' role='tabpanel' aria-labelledby='custom-tabs-three-settings-tab'>
					 
				 
				   <table class='table table-condensed table-bordered'>
				   <tbody>
				   <tr><th  scope='row' colspan='2' style='background:purple;color:white'>DATA JABATAN</th></tr>
				   <tr><th scope='row' width='280'>Jabatan Akademik</th>          
				   <td><select class='form-control form-control-sm' name='bf'> 
				   <option value='0' selected>- Pilih Jabatan -</option>"; 
				   $ag = mysqli_query($koneksi, "SELECT * FROM jabatan");
				   while($a = mysqli_fetch_array($ag)){
					 if ($a['JabatanID'] == $mw['JabatanID']){
					   echo "<option value='$a[JabatanID]' selected>$a[JabatanID] - $a[Nama]</option>";
					 }else{
					   echo "<option value='$a[JabatanID]'>$a[JabatanID] - $a[Nama]</option>";
					 }
				   }
				   echo "</select></td></tr>  
				   <tr><th scope='row'>Jabatan Dikti</th>          
				   <td><select class='form-control form-control-sm' name='bg'> 
				   <option value='0' selected>- Pilih Jabatan Dikti -</option>"; 
				   $ag = mysqli_query($koneksi, "SELECT * FROM jabatandikti");
				   while($a = mysqli_fetch_array($ag)){
					 if ($a['JabatanDiktiID'] == $mw['JabatanDiktiID']){
					   echo "<option value='$a[JabatanDiktiID]' selected>$a[JabatanDiktiID] - $a[Nama]</option>";
					 }else{
					   echo "<option value='$a[JabatanDiktiID]'>$a[JabatanDiktiID] - $a[Nama]</option>";
					 }
				   }
				   echo "</select></td></tr>  
				   <tr><th scope='row'>Golongan</th>          
				   <td><select class='form-control form-control-sm' name='bc'> 
				   <option value='0' selected>- Pilih Golongan -</option>"; 
				   $kerja = mysqli_query($koneksi, "SELECT * FROM golongan");
				   while($a = mysqli_fetch_array($kerja)){
					 if ($a['GolonganID'] == $mw['GolonganID']){
					   echo "<option value='$a[GolonganID]' selected>$a[GolonganID] - $a[Nama]</option>";
					 }else{
					   echo "<option value='$a[GolonganID]'>$a[GolonganID] - $a[Nama]</option>";
					 }
				   }
				   echo "</select></td></tr> 
				   <tr><th scope='row'>Tunjangan Ikatan</th>          
				   <td><select class='form-control form-control-sm' name='be'> 
				   <option value='0' selected>- Pilih Tunjangan Ikatan -</option>"; 
				   $ik = mysqli_query($koneksi, "SELECT * FROM ikatan");
				   while($a = mysqli_fetch_array($ik)){
					 if ($a['IkatanID'] == $mw['IkatanID']){
					   echo "<option value='$a[IkatanID]' selected>$a[IkatanID] - $a[Nama]</option>";
					 }else{
					   echo "<option value='$a[IkatanID]'>$a[IkatanID] - $a[Nama]</option>";
					 }
				   }
				   echo "</select></td></tr>  
				   <tr><th  scope='row'>Noreg</th> <td><input type='text' class='form-control form-control-sm' name='Noreg' value='$mw[Noreg]'></td></tr>
				   </tbody>
				   </table>
 
				   </div>
				   
				   <div class='tab-pane fade' id='custom-tabs-three-pengajaran' role='tabpanel' aria-labelledby='custom-tabs-three-pengajaran-tab'>
					 
				   <table id='example' class='table table-bordered table-sm' id='dataTable' width='100%' cellspacing='0'>
				   <thead>
				   <tr class='bg-info'>
					   <th width='3%'>No</th>
					   <th width='5%'>Tahun</th>
					   <th width='5%'>Kode</th>
					   <th width='35%'>Matakuliah</th>
					   <th width='3%' align='center'>SKS</th>
					   <th width='8%'>Kelas</th>
					   <th width='5%'>Hari</th>
					   <th width='10%'>Waktu</th>  
				   </tr>
				   </thead>
				   <tbody>";
				   $ss = mysqli_query($koneksi, "select jadwal.*, dosen.Nama as NamaDosen, mk.Nama as NamaMK, hari.Nama as NamaHari 
								     from jadwal, dosen, mk, hari 
    								 where jadwal.DosenID=dosen.Login 
    								 and jadwal.MKID=mk.MKID
    								 and jadwal.HariID=hari.HariID 
    								 and jadwal.DosenID='".strfilter($_GET['id'])."'");
					$no=1;
					while($rows=mysqli_fetch_array($ss)){	
						$no++;		 
				   echo"<tr>
					   <td>$no</td>
					   <td>$rows[TahunID]</td>
					   <td>$rows[MKKode]</td>
					   <td>$rows[NamaMK]</td>
					   <td align='center'>$rows[SKS]</td>
					   <td>$rows[NamaKelas]</td>
					   <td>$rows[NamaHari]</td>
					   <td> ".substr($rows['JamMulai'],0,5)." - ".substr($rows['JamSelesai'],0,5)."</td>
					 </tr>";
					}
					
				   
				   echo"</tbody>
				   </table>
					 
				   </div>
				   
 
					<div class='tab-pane fade' id='custom-tabs-three-penelitian' role='tabpanel' aria-labelledby='custom-tabs-three-penelitian-tab'>
					 
						   
					 
				   </div>
				   
				   
				   <div class='tab-pane fade' id='custom-tabs-three-pendidikan' role='tabpanel' aria-labelledby='custom-tabs-three-pendidikan-tab'>
					 
				 
				   </div>
				   
				   
				   <div class='tab-pane fade' id='custom-tabs-three-pekerjaan' role='tabpanel' aria-labelledby='custom-tabs-three-pekerjaan-tab'>
					
				   <table class='table table-condensed table-bordered'>
					<tbody>
					<tr><th  scope='row' colspan='2' style='background:purple;color:white'>DATA BANK</th></tr>

					<tr><th  scope='row'>Nama Bank</th> <td><input type='text' class='form-control form-control-sm' name='bl' value='$mw[NamaBank]'></td></tr>
					<tr><th  scope='row'>Nama Akun</th> <td><input type='text' class='form-control form-control-sm' name='bm' value='$mw[NamaAkun]'></td></tr>
					<tr><th  scope='row'>Nomer Akun</th> <td><input type='text' class='form-control form-control-sm' name='bn' value='$mw[NomerAkun]'></td></tr>
					</tbody>
					</table>
					
				   </div>
				   
			 
				   
				 </div>
			   </div>	  
			 </div>";
			 
			 
			 echo"<div class='card'>
				 <div class='card-header'>
				 <div class='table-responsive'>
					 <div class='box-footer'>
					 <button type='submit' name='ubahdata' class='btn btn-info'>Perbaharui Data</button>
					 <a href='index.php?ndelox=master/dosenx'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
					 </div>
				 </div>
				 </div>
				 </div>	
				 </form>";
}
?>