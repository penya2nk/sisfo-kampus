<div class='card'>
<div class='card-header'>

<h3 class="box-title">
<b style='color:green;font-size:20px'>DATA CUTI PEGAWAI </b>
</h3>
		<div class="form-group row">
			<label class="col-md-8 col-form-label text-md-right"><b style='color:purple'>DATA CUTI PEGAWAI</b></label>
			<div class="col-md-2">
			<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>

			<?php
					echo"
					<input type='hidden' name='ndelox' value='peg/admcutipeg'>
					<select class='form-control form-control-sm' name='Noreg' onChange='this.form.submit()'> 
					<option value='0' >- Pilih Nama Pegawai -</option>"; 		
					$pw = mysqli_query($koneksi, "SELECT * FROM t_simpegpegawai order by Nama Asc");//combobox
					while($d = mysqli_fetch_array($pw)){//combobox
					if ($_GET['Noreg']==$d['Noreg']){
						echo "<option value='$d[Noreg]' selected>$d[Nama] - $d[Gelar] - $d[Noreg] </option>";
					}else{
						echo "<option value='$d[Noreg]'>$d[Nama] - $d[Gelar] - $d[Noreg]</option>";	  
					}
					}
					echo "</select>"; ?>
			</div>

			</form>	

			<div class="col-md-2">
			<a class='pull-right btn btn-primary btn-sm'  href='index.php?ndelox=peg/admcutipeg&act=tambah&Noreg=<?php echo"$_GET[Noreg]";?>&tahun=<?php echo "$_GET[tahun]";?>'>Ajukan Cuti</a>
			</div>

		</div>

</div>
</div>


<?php if ($_GET['act']==''){ ?>            
  <div class="box">  
	<div class="box-body">
	<?php 
	  if (isset($_GET['sukses'])){
		  echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
			  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
			  <span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah Berhasil Di Proses,..
			  </div>";
	  }elseif(isset($_GET['gagal'])){
		  echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
			  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
			  <span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Data tidak Di Proses, terjadi kesalahan dengan data..
			  </div>";
	  }
	?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
	  <table id="example" class="table table-bordered table-striped">
		<thead>
		  <tr style='background:purple;color:white'>
			<th style='width:40px'>No</th>                       
			<th style='width:180px'>Pegawai</th>
			<th>Tgl Pengajuan</th> 			
			<th>Jenis Cuti</th> 
			<th style='width:180px'>Pengganti</th> 
			<th>Masa Cuti</th>
			<th>Selama</th>			
			<th style='width:80px'>Keberadaan</th>	
			<th>Status</th>	
			<th style='width:80px'>Action</th>                        
		  </tr>
		</thead>
		<tbody>
	  <?php 
		$tampil = mysqli_query($koneksi, "SELECT  
		t_simpegpegawai.Noreg,t_simpegpegawai.Nama,t_simpegpegawai.Gelar,  
		t_simpegcuti.*
		FROM t_simpegcuti,t_simpegpegawai 
		where t_simpegcuti.Noreg=t_simpegpegawai.Noreg order by TanggalInput Desc");
		$no = 1;
		while($r=mysqli_fetch_array($tampil)){		
		$tglm =tgl_indo($r['TanggalMulai']);
		$tgls =tgl_indo($r['TanggalSelesai']);

		$lamax			= selisihHari($r['TanggalMulai'], $r['TanggalSelesai']);
		$lamax2			= $lamax +1;
		
		if ($r['Status']=='1'){
			$stts="Waiting";
		}
		elseif ($r['Status']=='2'){
			$stts="Disetujui";
		}else{
			$stts="Ditolak";
		}

		$pgt = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Noreg,Nama,Gelar FROM t_simpegpegawai where Noreg='$r[Pengganti]'"));		
		echo "<tr><td>$no</td>              
				  <td>$r[Nama], $r[Gelar]</td>
				  <td>".tgl_indo($r['TanggalInput'])."</td>
				  <td>$r[JenisCuti] <a href='print_report/print-srcuti.php?IDCuti=$r[IDCuti]' target=_BLANK> <br>[ Cetak ] </a></td>
				  <td>$pgt[Nama], $pgt[Gelar]</td>
				  <td>".tgl_indo($r['TanggalMulai'])."  s/d <br> ".tgl_indo($r['TanggalSelesai'])."</td>							  
				  <td>$lamax2 hari</td>
				  <td>$r[Keberadaan]</td>
				  <td>$stts</td>				  
				  <td><center>                               
					<a class='btn btn-success btn-xs' title='Edit Data' href='?ndelox=peg/admcutipeg&act=edit&id=$r[IDCuti]&Noreg=$_GET[Noreg]&tahun=$_GET[tahun]'><i class='fa fa-edit'></i></a>
					<a class='btn btn-danger btn-xs' title='Delete Data' href='?ndelox=peg/admcutipeg&hapus=$r[IDCuti]&Noreg=$_GET[Noreg]&tahun=$_GET[tahun]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-trash'></i></a>
					
				  </center></td>";
				echo "</tr>";
				
				//<a style='margin-right:5px; width:30px' class='btn btn-info btn-xs' title='Download Bukti' href='download_peg.php?file=$r[file_upload]'><span class='glyphicon glyphicon-download'></span></a>
		  $no++;
		  }

		  if (isset($_GET['hapus'])){
			  mysqli_query($koneksi, "DELETE FROM t_simpegcuti where IDCuti='".strfilter($_GET['hapus'])."'");
			  echo "<script>document.location='index.php?ndelox=peg/admcutipeg&Noreg=$_GET[Noreg]&tahun=$_GET[tahun]';</script>";
		  }

	  ?>		
		</tbody>
	  </table>
	</div>
</div>
</div>



  <div class="box">               
	<div class="box-body">
	    <div class='table-responsive'>
	 <table>
	 <tr><td colspan='9' align="right">
	 <?php echo"<b>Total: $totkum</b>"; ?>
	 <?php echo"&nbsp;&nbsp;&nsub;&nsub;&nbsp;<a href' title='Validasi' href='index.php?ndelox=peg/admcutipeg&valid=$r[id_pengajaranitem]&tahun=$r[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Divalidasi?')\">Validasi</a>";?>
<?php
if (isset($_GET['valid'])){
mysqli_query($koneksi, "UPDATE t_rekapkum set xxx='$totkum' where Noreg='$_SESSION[_Login]'");
}
?>
	 </td>
	 </tr>
	 </table>
	</div>
	</div>
            
<?php 
}elseif($_GET['act']=='edit'){
    if (isset($_POST['update'])){
		
            $query = mysqli_query($koneksi, "UPDATE t_simpegcuti SET 
                                         JenisCuti 		= '".strfilter($_POST['JenisCuti'])."',
										 Alasan 		= '".strfilter($_POST['Alasan'])."',
										 Keberadaan 	= '".strfilter($_POST['Keberadaan'])."',
										 Pengganti 		= '".strfilter($_POST['Pengganti'])."',	
										 Mengetahui 	= '".strfilter($_POST['Mengetahui'])."',
										 TanggalMulai 	= '".strfilter($_POST['TanggalMulai'])."',
										 TanggalSelesai	= '".strfilter($_POST['TanggalSelesai'])."',
										 Keterangan		= '".strfilter($_POST['Keterangan'])."',
										 Status			= '".strfilter($_POST['Status'])."'										 
                                         where IDCuti	='".strfilter($_POST['id'])."'");
        echo "<script>document.location='index.php?ndelox=peg/admcutipeg&Noreg=$_GET[Noreg]&tahun=$_GET[tahun]&sukses';</script>";
          //}
      }//isset kirimkan
	  
    $s = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM t_simpegcuti where IDCuti='".strfilter($_GET['id'])."'"));
	$namax = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM t_simpegpegawai where Noreg='$s[Noreg]'"));
    echo "
               

              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
	<div class='row'>
			<div class='col-md-6'>	  
					<div class='card'>
					<div class='card-header'>
						<div class='box-header with-border'>
						<h3 class='box-title'><b style=color:green;font-size=18px>Edit Data Cuti</b></h3>
						</div>
					<div class='table-responsive'>
					<table class='table table-sm table-bordered'>
					<tbody>
						<input type='hidden' name='id' value='$s[IDCuti]'>
					<tr><th scope='row'>Nama Pegawai Cuti</th><td><input type='text'class='form-control' name='x' value='$namax[Nama], $namax[Gelar]'></td></tr>  
					<tr><th scope='row' width=200px>Jenis Cuti</th>   <td>
						<select class='form-control' name='JenisCuti'> 
							<option value='0'>- Pilih Jenis Cuti -</option>"; 
							$ak = mysqli_query($koneksi, "SELECT * FROM t_simpegjeniscuti");
							while($a = mysqli_fetch_array($ak)){
							if ($s['JenisCuti']==$a['Nama']){
								echo "<option value='$a[Nama]' selected>$a[Nama]</option>";
							}else{
								echo "<option value='$a[Nama]' > $a[Nama]</option>";	  
							}
							}
						echo "</select>
					</tr>
					<tr><th scope='row'>Alasan</th><td><input type='text'class='form-control' name='Alasan' value='$s[Alasan]'></td></tr>
					<tr><th scope='row'>Keberadaan</th><td><input type='text' class='form-control' name='Keberadaan' value='$s[Keberadaan]'></td></tr>
					<tr><th scope='row' width=200px>Pegawai Pengganti</th>   <td>
						<select class='combobox form-control' name='Pengganti'> 
							<option value='0'>- Pilih Nama Pegawai -</option>"; 
							$ak = mysqli_query($koneksi, "SELECT * FROM t_simpegpegawai");
							while($a = mysqli_fetch_array($ak)){
							if ($s['Pengganti']==$a['Noreg']){
								echo "<option value='$a[Noreg]' selected>$a[Nama], $a[Gelar]</option>";
							}else{
								echo "<option value='$a[Noreg]' > $a[Nama], $a[Gelar] </option>";	  
							}
							}
						echo "</select>
					</tr>
					
					<tr><th scope='row' width=200px>Mengetahui Atasan Langsung</th>   <td>
						<select class='combobox form-control' name='Mengetahui'> 
							<option value='0'>- Pilih Nama Pegawai -</option>"; 
							$ak = mysqli_query($koneksi, "SELECT * FROM t_simpegpegawai");
							while($a = mysqli_fetch_array($ak)){
							if ($s['Mengetahui']==$a['Noreg']){
								echo "<option value='$a[Noreg]' selected>$a[Nama], $a[Gelar]</option>";
							}else{
								echo "<option value='$a[Noreg]' > $a[Nama], $a[Gelar] </option>";	  
							}
							}
						echo "</select>
					</tr>
					<tr><th scope='row' width=200px>Status Pengajuan</th>   <td>
						<select class='form-control' name='Status'> 
							<option value='0'>- Pilih Status -</option>"; 
							$ak = mysqli_query($koneksi, "SELECT * FROM t_simpegstatuscuti");
							while($a = mysqli_fetch_array($ak)){
							if ($s['Status']==$a['IDStatus']){
								echo "<option value='$a[IDStatus]' selected>$a[Status]</option>";
							}else{
								echo "<option value='$a[IDStatus]' > $a[Status]</option>";	  
							}
							}
						echo "</select>
					</tr>
						</tbody>
					</table>
					</div>
					</div>
					</div>			
			</div>	

            <div class='col-md-6'>
						<div class='card'>
						<div class='card-header'>
						<div class='table-responsive'>
						<table class='table table-sm table-bordered'>
						<tbody>
																				
							<tr><th scope='row'>Tanggal Mulai</th>    <td><input type='text' class='form-control' name='TanggalMulai' id=datepicker value='$s[TanggalMulai]'></td></tr>
							<tr><th scope='row'>Tanggal Selesai</th>    <td><input type='text' class='form-control' name='TanggalSelesai' id=datepicker2 value='$s[TanggalSelesai]'></td></tr>
							<tr><th scope='row'>Keterangan</th><td><textarea rows='5' class='form-control' name='Keterangan'>$s[Keterangan] </textarea></td></tr>			  
							
						</tbody>
						</table>
						              <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='index.php?ndelox=peg/admcutipeg&Noreg=$_GET[Noreg]&tahun=$_GET[tahun]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
						</div>
						</div>
						</div>
			</div>
	</div>

              </form>";
}elseif($_GET['act']=='tambah'){
    	 if (isset($_POST['kirimkan'])){        
            mysqli_query($koneksi, "INSERT INTO t_simpegcuti(Noreg,
													JenisCuti,
													Alasan,
			  										Keberadaan,
													Pengganti,
													Mengetahui,
													TanggalMulai,
													TanggalSelesai,
													TanggalInput,
													Keterangan) 
											VALUES ('".strfilter($_POST['Noreg'])."',
													'".strfilter($_POST['JenisCuti'])."',
													'".strfilter($_POST['Alasan'])."',
													'".strfilter($_POST['Keberadaan'])."',
													'".strfilter($_POST['Pengganti'])."',
													'".strfilter($_POST['Mengetahui'])."',													
													'".strfilter($_POST['TanggalMulai'])."',
													'".strfilter($_POST['TanggalSelesai'])."',
													'".date('Y-m-d')."',
													'".strfilter($_POST['Keterangan'])."')");
            echo "<script>document.location='index.php?ndelox=peg/admcutipeg&Noreg=$_GET[Noreg]&tahun=$_GET[tahun]&sukses';</script>";
         // }
      }//isset kirimkan

	//Menggunakan ini (<form name='data' action='$act' method=POST onSubmit=\"return CheckForm(this);\">)
	//tidak bisa upload file solusinya ganti seperti form di bawah  
$pgw = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM t_simpegpegawai where Noreg='".strfilter($_GET['Noreg'])."'"));   
    echo "

          
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
			  <div class='row'>
							<div class='col-md-6'>
									<div class='card'>
									<div class='card-header'>
										<div class='box-header with-border'>
										<h3 class='box-title'><b style=color:green;font-size=18px>Tambah Data Cuti ($pgw[Nama], $pgw[Gelar])</b></h3>
										</div>
									<div class='table-responsive'>
									<table class='table table-sm table-bordered'>
									<tbody>
									<input type='hidden' name='Noreg' value='$_GET[Noreg]'>
									
									<tr><th scope='row' width=200px>Jenis Cuti</th>   <td>
										<select class='form-control' name='JenisCuti'> 
											<option value='0'>- Pilih Jenis Cuti -</option>"; 
											$ak = mysqli_query($koneksi, "SELECT * FROM t_simpegjeniscuti");
											while($a = mysqli_fetch_array($ak)){
											if ($s['JenisCuti']==$a['Nama']){
												echo "<option value='$a[Nama]' selected>$a[Nama]</option>";
											}else{
												echo "<option value='$a[Nama]' > $a[Nama]</option>";	  
											}
											}
										echo "</select>
									</tr>
									<tr><th scope='row'>Alasan</th><td><input type='text'class='form-control' name='Alasan'></td></tr>
									<tr><th scope='row'>Keberadaan Selama Cuti</th><td><input type='text' class='form-control' name='Keberadaan'></td></tr>
									<tr><th scope='row' width=200px>Pegawai Pengganti</th>   <td>
										<select class='combobox form-control' name='Pengganti'> 
											<option value='0'>- Pilih Nama Pegawai -</option>"; 
											$ak = mysqli_query($koneksi, "SELECT * FROM t_simpegpegawai");
											while($a = mysqli_fetch_array($ak)){
											if ($s['Pengganti']==$a['Noreg']){
												echo "<option value='$a[Noreg]' selected>$a[Nama], $a[Gelar]</option>";
											}else{
												echo "<option value='$a[Noreg]' > $a[Nama], $a[Gelar] </option>";	  
											}
											}
										echo "</select>
									</tr>
									<tr><th scope='row' width=200px>Mengetahui Atasan Langsung</th>   <td>
										<select class='combobox form-control' name='Mengetahui'> 
											<option value='0'>- Pilih Nama Pegawai -</option>"; 
											$ak = mysqli_query($koneksi, "SELECT * FROM t_simpegpegawai");
											while($a = mysqli_fetch_array($ak)){
											if ($s['Mengetahui']==$a['Noreg']){
												echo "<option value='$a[Noreg]' selected>$a[Nama], $a[Gelar]</option>";
											}else{
												echo "<option value='$a[Noreg]' > $a[Nama], $a[Gelar] </option>";	  
											}
											}
										echo "</select>
									</tr>
									</tbody>
									</table>
									</div>
									</div>
									</div>
							</div>					

							<div class='col-md-6'>
								<div class='card'>
								<div class='card-header'>
								<div class='table-responsive'>
								<table class='table table-sm table-bordered'>
								<tbody>
								<tr><th scope='row' >Tanggal Mulai Cuti</th><td><input type='text'   class='form-control' id=datepicker name='TanggalMulai'></td></tr>
									<tr><th scope='row' >Tanggal Berakhir Cuti</th><td><input type='text'   class='form-control' id=datepicker2 name='TanggalSelesai'></td></tr>				 
								<tr><th scope='row'>Keterangan</th><td><textarea rows='5' class='form-control' name='Keterangan'></textarea></td></tr>
								
								</tbody>
								</table>    
              <div class='box-footer'>
				<button type='submit' name='kirimkan' class='btn btn-info'>Kirimkan</button>
				<a href='index.php?ndelox=peg/admcutipeg&Noreg=$_GET[Noreg]&tahun=$_GET[tahun]'><button type=button class='btn btn-default pull-right'>Cancel</button></a>                   
              </div>								
								</div>
								</div>
								</div>
							</div>	
				</div>						

              </form>
            </div>";
}


?>