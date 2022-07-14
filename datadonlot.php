<?php 
if ($_GET['act']==''){ 
//cek_session_admin();
?>
<div class="card">
<div class="card-header">

	  <?php 
	  if (isset($_GET['prodi']) AND isset($_GET['tahun'])){ 
		  echo "<b style='color:green;font-size:20px'>Manajemen Data Download</b>"; 					
		  }
	  
	  else{ 
		  echo "<b style='color:green;font-size:20px'>Manajemen Data Download </b>".date('Y'); } 
	  ?></h3>
	  	<div class="form-group row">
					<label class="col-md-6 col-form-label text-md-right"><b style='color:purple'>TAHUN</b></label>
					<div class="col-md-2">	  
					<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
					<input type="hidden" name='ndelox' value='datadonlot'>
					<select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
						<?php 
							echo "<option value=''>- Pilih Tahun Akademik -</option>";
							$tahun = mysqli_query($koneksi, "SELECT distinct(TahunID) FROM tahun order by TahunID Desc");
							while ($k = mysqli_fetch_array($tahun)){
							if ($_GET['tahun']==$k['TahunID']){
								echo "<option value='$k[TahunID]' selected>$k[TahunID]</option>";
							}else{
								echo "<option value='$k[TahunID]'>$k[TahunID]</option>";
							}
							}
						?>
					</select>
					</div>                


					<div class="col-md-2">	
					<select name='prodi' class='form-control form-control-sm' onChange='this.form.submit()'>
						<?php 
							echo "<option value=''>- Pilih Prodi -</option>";
							$prodi = mysqli_query($koneksi, "SELECT * FROM prodi");
							while ($k = mysqli_fetch_array($prodi)){
							if ($_GET['prodi']==$k['ProdiID']){
								echo "<option value='$k[ProdiID]' selected>$k[ProdiID] - $k[Nama]</option>";
							}else{
								echo "<option value='$k[ProdiID]'>$k[ProdiID] - $k[Nama]</option>";
							}
							}
						?>
					</select>
					</div>                

					<div class="col-md-1">	
					<input type="submit" class='btn btn-success btn-sm' value='Lihat'>
					</form>
					</div>

					<div class="col-md-1">	
					<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=datadonlot&act=tambah&tahun=<?php echo $_GET['tahun']; ?>&prodi=<?php echo "$_GET[prodi]";?>'>Tambah Berkas</a>				
					</div>
		</div>
</div>
</div>

	<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
	  <table id="example" class="table table-sm table-striped">
		<thead>
		  <tr style="background:purple;color:white">
			<th style='width:20px'>No</th>
			<th>Berkas</th>
			<th>Program Studi</th>
			<th>Tahun</th>
			<th>Tanggal Upload</th>
		   
			<th>Action</th>
		  </tr>
		</thead>
		<tbody>
	  <?php
		// if (isset($_GET['prodi']) AND isset($_GET['tahun'])){
		    $tampil = mysqli_query($koneksi, "SELECT * from t_unduhfile where ProdiID='".strfilter($_GET['prodi'])."' ORDER BY UnduhID DESC"); //AND TahunID='".strfilter($_GET[tahun])."'		
		// }
		$no = 1;
		while($r=mysqli_fetch_array($tampil)){
	    $prodi = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama FROM prodi where ProdiID='$r[ProdiID]'"));
		//$total = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM rb_elearning where kodejdwl='$r[kodejdwl]'"));
		echo "<tr><td>$no</td>
				  <td>$r[nama_file]</td>
				  <td>$prodi[Nama]</td>
				  <td>$r[TahunID]</td>
				  <td>".tgl_indo($r['TanggalBuat'])."</td>
				  ";
				if ($_SESSION['_LevelID']=='1'){
				  echo "<td>";
				 
				  //echo "<a style='margin-right:5px; width:106px' class='btn btn-info btn-xs' title='Download' href='sedot_berkas.php?file=$r[file_upload]'><span class='glyphicon glyphicon-download'></span> Download </a>";                                 
				  echo "<a class='btn btn-info btn-xs' title='Download Bahan' href='$r[URL]' target=_BLANK><span class='glyphicon glyphicon-download'></span> Download </a>&nbsp";
				  echo "<a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=datadonlot&act=edit&prodi=$r[ProdiID]&id=$_GET[id]&tahun=$r[TahunID]&edit=$r[UnduhID]'><i class='fa fa-edit'></i></a>
							<a class='btn btn-danger btn-xs' title='Delete' href='index.php?ndelox=datadonlot&prodi=$r[ProdiID]&tahun=$r[TahunID]&hapus=$r[UnduhID]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-trash'></i></a>
				  </td>
				</tr>";
				}elseif ($_SESSION['_LevelID']=='120'){
				  $sekarangwaktu = date("YmdHis");
				  $bataswaktu1 = str_replace('-','',$r['TanggalSelesai']);
				  $bataswaktu2 = str_replace(':','',$bataswaktu1);
				  $bataswaktu3 = str_replace(' ','',$bataswaktu2);
				  
				  if ($sekarangwaktu < $bataswaktu3 OR $bataswaktu3 == '00000000000000'){
					  //echo "<td><a class='btn btn-info btn-xs' title='Download Bahan' href='download_berkas.php?file=$r[file_upload]'><span class='glyphicon glyphicon-download'></span> Download</a></td></tr>";
					  echo "<td><a class='btn btn-info btn-xs' title='Download Bahan' href='$r[URL]' target=_BLANK><span class='glyphicon glyphicon-download'></span> Download</a></td></tr>";
				  }else{
					  echo "<td><a style='width:167px' class='btn btn-danger btn-xs' title='Waktu Habis' href=''><i class='fa fa-trash'></i> Waktu Habis</a></td></tr>";
				  }
			  }		
				echo "</tr>";
		  $no++;
		  }
	  ?>
		</tbody>
	  </table></div>
	</div><!-- /.box-body -->
	<?php 
		if ($_GET['prodi'] == '' AND $_GET['tahun'] == ''){
			echo "<center style='padding:60px; color:red'>Silahkan Memilih Tahun akademik dan Prodi Terlebih dahulu...</center>";
		}
		if (isset($_GET['hapus'])){
			mysqli_query($koneksi, "DELETE FROM t_unduhfile where UnduhID='".strfilter($_GET['hapus'])."'");
			echo "<script>document.location='index.php?ndelox=datadonlot&prodi=".$_GET['prodi']."&id=".$_GET['id']."&tahun=".$_GET['tahun']."'</script>";
		  }
	?>
</div>
</div>
</div>
<?php 
}

elseif($_GET['act']=='tambah'){
//cek_session_admin();
if (isset($_POST['tambah'])){
	  $filename 	= basename($_FILES['ax']['name']);
	  if ($filename != ''){      
		    $ekstensi_diperbolehkan	= array('png','jpg','pdf');
			$filenamee 	= date("YmdHis").'-'.basename($_FILES['ax']['name']);
			$x 			= explode('.', $filenamee);
			$ekstensi 	= strtolower(end($x));
			$ukuran		= $_FILES['ax']['size'];
			$file_tmp = $_FILES['ax']['tmp_name'];	

			if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
				if($ukuran < 55000){	 //1044070		
					move_uploaded_file($file_tmp, 'pile_berkas/'.$filenamee);
				    mysqli_query($koneksi, "INSERT INTO t_unduhfile(TahunID,ProdiID,nama_file,file_upload,TanggalBuat,TanggalMulai,TanggalSelesai) 
						VALUES ('".strfilter($_POST['tahun'])."',
								'".strfilter($_POST['prodi'])."',
								'".strfilter($_POST['nama_file'])."',
								'$filenamee',
								'".date('Y-m-d')."',
								'".strfilter($_POST['TanggalMulai'])."',
								'".strfilter($_POST['TanggalSelesai'])."')");
                    echo "<script>document.location='index.php?ndelox=datadonlot&prodi=".$_POST['prodi']."&id=".$_GET['id']."&tahun=".$_POST['tahun']."';</script>";
				}else{
					echo "<b style=color:red>Ukuran File terlalu besar, compress menjadi < 300 KB !</b>";
				}
			}else{
				echo "<b style=color:red>Ekstensi File yang diupload tidak diperbolehkan!</b>";
			}
	  }else{
        mysqli_query($koneksi, "INSERT INTO t_unduhfile(TahunID,ProdiID,nama_file,TanggalBuat,TanggalMulai,TanggalSelesai,URL) 
						VALUES ('".strfilter($_POST['tahun'])."',
								'".strfilter($_POST['prodi'])."',
								'".strfilter($_POST['nama_file'])."',
								'".date('Y-m-d')."',
								'".strfilter($_POST['TanggalMulai'])."',
								'".strfilter($_POST['TanggalSelesai'])."',
								'".strfilter($_POST['URL'])."')");
        echo "<script>document.location='index.php?ndelox=datadonlot&prodi=".$_POST['prodi']."&id=".$_GET[id]."&tahun=".$_POST['tahun']."';</script>";
      }
  }

echo "


		  <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
		  <div class='card'>
		  <div class='card-header'>
		  <div class='box box-info'>
			  <h3 class='box-title'><b style='color:green;font-size:20px'>Tambah Berkas</b></h3>
			</div>
		  <div class='table-responsive'>
			  <table class='table table-sm table-bordered'>
			  <tbody>
				<input type=hidden name='tahun' value='$_GET[tahun]'>
				<input type=hidden name='prodi' value='$_GET[prodi]'>
				
				<tr><th width='120px' scope='row' >Nama File</th>        <td><input type='text' class='form-control form-control-sm' name='nama_file'></td></tr>
				<tr><th scope='row'>File</th>             <td><div style='position:relative;''>
																	  <a class='btn btn-primary' href='javascript:;'>
																		<i class='fa fa-search'></i> Cari File ..."; ?>
																		<input type='file' class='pile_berkas' name='ax' onchange='$("#upload-file-info").html($(this).val());'>
																	  <?php echo "</a> <span style='width:155px' class='label label-info' id='upload-file-info'></span>
																	</div>
				</td></tr>
				<tr><th scope='row'>Waktu Mulai</th>      <td><input type='text' class='form-control form-control-sm' value='".date("Y-m-d H:i:s")."' name='TanggalMulai'></td></tr>
				<tr><th scope='row'>Waktu Selesai</th>    <td><input type='text' class='form-control form-control-sm' value='".date("Y-m-d H:i:s")."' name='TanggalSelesai'></td></tr>
				<tr><th scope='row'>URL</th>       <td><input type='text' class='form-control form-control-sm' name='URL'></td></tr>
				<tr><th scope='row'>Keterangan</th>       <td><input type='text' class='form-control form-control-sm' name='Keterangan'></td></tr>
				
			  </tbody>
			  </table>
			  		  <div class='box-footer'>
				<button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
				<a href='index.php?ndelox=datadonlot&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
				
			  </div>
			  </div>
			  </div>
			  </div>

		  </form>
		</div>";
}



elseif($_GET['act']=='edit'){
//cek_session_admin();
if (isset($_POST['update'])){
  $filename 	= basename($_FILES['ax']['name']);
	  if ($filename != ''){      
		    $ekstensi_diperbolehkan	= array('png','jpg','pdf');
			$filenamee 	= date("YmdHis").'-'.basename($_FILES['ax']['name']);
			$x 			= explode('.', $filenamee);
			$ekstensi 	= strtolower(end($x));
			$ukuran		= $_FILES['ax']['size'];
			$file_tmp = $_FILES['ax']['tmp_name'];	

			if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
				if($ukuran < 55000){	 //1044070		
					move_uploaded_file($file_tmp, 'pile_berkas/'.$filenamee);
					mysqli_query($koneksi, "UPDATE t_unduhfile SET ProdiID= '".strfilter($_POST['prodi'])."',
                                               TahunID          = '".strfilter($_POST['tahun'])."',
                                               nama_file        = '".strfilter($_POST['nama_file'])."',
                                               file_upload      = '$filenamee',
                                               TanggalMulai     = '".strfilter($_POST['TanggalMulai'])."',
                                               TanggalSelesai   = '".strfilter($_POST['TanggalSelesai'])."',
											   URL  		    = '".strfilter($_POST['URL'])."',
											   TanggalBuat     = '".date('Y-m-d')."',
                                               Keterangan       = '".strfilter($_POST['Keterangan'])."' 
											   where UnduhID	= '".strfilter($_GET['edit'])."'");
					echo "<script>document.location='index.php?ndelox=datadonlot&prodi=".$_POST['prodi']."&id=".$_GET['id']."&tahun=".$_POST['tahun']."';</script>";
				}else{
					echo "<b style=color:red>Ukuran File terlalu besar, compress menjadi < 300 KB !</b>";
				}
			}else{
				echo "<b style=color:red>Ekstensi File yang diupload tidak diperbolehkan!</b>";
			}
	  }else{
        mysqli_query($koneksi, "UPDATE t_unduhfile SET ProdiID = '".strfilter($_POST['prodi'])."',
                                               TahunID         = '".strfilter($_POST['tahun'])."',
                                               nama_file       = '".strfilter($_POST['nama_file'])."',
                                               TanggalMulai    = '".strfilter($_POST['TanggalMulai'])."',
                                               TanggalSelesai  = '".strfilter($_POST['TanggalSelesai'])."',
											   URL  		   = '".strfilter($_POST['URL'])."',
											   TanggalBuat     = '".date('Y-m-d')."',
                                               Keterangan      = '".strfilter($_POST['Keterangan'])."' 
											   where UnduhID   = '".strfilter($_GET['edit'])."'");
        echo "<script>document.location='index.php?ndelox=datadonlot&prodi=".$_POST['prodi']."&id=".$_GET['id']."&tahun=".$_POST['tahun']."';</script>";

      }
  }

$edit = mysqli_query($koneksi, "SELECT * FROM t_unduhfile where UnduhID='".strfilter($_GET['edit'])."'");
    $s = mysqli_fetch_array($edit);
    echo "


              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
			  <div class='card'>
			  <div class='card-header'>
			  <div class='box box-info'>
                  <h3 class='box-title'><b style='color:green;font-size:20px'>Edit Berkas</b></h3>
                </div>
			  <div class='table-responsive'>
                  <table class='table table-sm table-bordered'>
                  <tbody>
					<input type=hidden name='prodi' value='$_GET[prodi]'>
					<tr><th scope='row'>Tahun Akademik</th>       <td><input type='text' class='form-control form-control-sm' name='tahun' value='$s[TahunID]'></td></tr>
                    <tr><th scope='row'>Nama File</th>        <td><input type='text' class='form-control form-control-sm' name='nama_file' value='$s[nama_file]'></td></tr>
                    <tr><th scope='row'>Ganti File</th>             <td><div style='position:relative;''>
                                                                          <a class='btn btn-primary' href='javascript:;'>
                                                                            <i class='fa fa-search'></i> <b>Ganti File :</b> $s[file_upload]"; ?>
                                                                            <input type='file' class='pile_berkas' name='ax' onchange='$("#upload-file-info").html($(this).val());'>
                                                                          <?php echo "</a> <span style='width:155px' class='label label-info' id='upload-file-info'></span>
                                                                        </div>
                    </td></tr>
                    <tr><th scope='row'>Waktu Mulai</th>      <td><input type='text' class='form-control form-control-sm' value='$s[TanggalMulai]' name='TanggalMulai'></td></tr>
                    <tr><th scope='row'>Waktu Selesai</th>    <td><input type='text' class='form-control form-control-sm' value='$s[TanggalSelesai]' name='TanggalSelesai'></td></tr>
                    <tr><th scope='row'>URL</th>       <td><input type='text' class='form-control form-control-sm' name='URL' value='$s[URL]'></td></tr>
					<tr><th scope='row'>Keterangan</th>       <td><input type='text' class='form-control form-control-sm' name='Keterangan' value='$s[Keterangan]'></td></tr>
                    
                  </tbody>
                  </table>
				                <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='index.php?ndelox=datadonlot&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type=button class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
				  </div>
				  </div>
				  </div>

              </form>
            </div>";
}

?>