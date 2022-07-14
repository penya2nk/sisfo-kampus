<div class="card">
<div class="card-header">
<div class="card-tools">
	
<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=kk/pjudulkpadm&act=ajukanjudul&tahun=<?php echo $_GET['tahun']; ?>&prodi=<?php echo "$_GET[prodi]"; ?>'>PENGAJUAN JUDUL </a>
<?php 
  if (isset($_GET['tahun'])){ 
	 echo "<b style='color:green;font-size:20px'></b> | 
	 <a href='index.php?ndelox=kk/pjudulkpadm&prodi=$_GET[prodi]&tahun=$_GET[tahun]'>PENGAJUAN BARU</a> |
	 <a href='index.php?ndelox=kk/pjudulkpadm&act=ditolak&prodi=$_GET[prodi]&tahun=$_GET[tahun]'>DITOLAK</a> |
	 
	 <a  href='print_reportxls/pengajuanjudulkpxls.php?tahun=$_GET[tahun]&prodi=$_GET[prodi]' target='_BLANK'>Export Ke Excel</a>"; 
  }else{ 
     echo ""; 
  } ?>
</div>

<div class="form-group row">
<label class="col-md-3 col-form-label text-md-left"><b style='color:purple'>PENGAJUAN JUDUL KERJA PRAKTEK</b></label>

<div class="col-md-2 ">
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='kk/pjudulkpadm'>
<select name='tahun' class='form-control form-control-sm form-control form-control-sm-sm' onChange='this.form.submit()'>
	<?php 
		echo "<option value=''>- Pilih Tahun Akademik -</option>";
		$tahun = mysqli_query($koneksi, "SELECT distinct(TahunID),NA,ProdiID FROM tahun order by TahunID Desc"); //and NA='N'
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

<div class="col-md-3">
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
<input type="submit" class='btn btn-success btn-sm' value='Lihat'>
</form>
</div>
	</div>

</div>
</div>

                
<?php if ($_GET['act']==''){ ?>       
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
<div class="card">
<div class="card-header">
<div class="table-responsive">
        <table id="example" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th style='width:20px'>No</th>
              <th style='width:100px'>NIM</th>  
              <th style='width:400px'>Namax</th>             
              <th>Program Studi</th> 
              <th>Tgl Pengajuan</th>                                
              <th width='140px'>Action</th> 
            </tr>
          </thead>
          <tbody>
        <?php
          if (isset($_GET['tahun'])){
			  $qrs = mysqli_query($koneksi, "SELECT * from jadwal_kp where TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."' and Status NOT IN ('DITOLAK') order by Judul asc"); //TglBuat desc
			  while ($h = mysqli_fetch_array($qrs)){
				 $judulx 	= strtolower($h['Judul']);
				 $Judul		= ucwords($judulx);
			     $mhs = mysqli_fetch_array(mysqli_query($koneksi, "select MhswID,Nama from mhsw where MhswID='$h[MhswID]'"));				 
				 $NamaMhsx 	= strtolower($mhs['Nama']);
				 $NamaMhs	= ucwords($NamaMhsx);
			     $p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$h[DosenID]'"));		      
			     $NamaDosx 	= strtoupper($p1['Nama']);
    				if ($h['Status']=='DITERIMA'){
    				$c="style=color:green;font-size:16px";
    				}
    				else if ($h['Status']=='WAITING'){
    					$c="style=color:#FF8306;font-size:16px";
    				}
    				else{
    					$c="style=color:red;font-size:16px";
    				}	

				$hd++;
			  echo"<tr style='background-color:#DFF4FF'>
				 <td colspan='11' height='20'><b>&nbsp;  
				 <a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=kk/pjudulkpadm&act=edit&JadwalID=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]'><i class='fa fa-edit'></i></a>
				 							 				 
				  | <a href='index.php?ndelox=kk/pjudulkpadm&act=tambahanggota&JadwalID=$h[JadwalID]&KelompokID=$h[KelompokID]&prodi=$h[ProdiID]&tahun=$h[TahunID]'>
				  $hd. KLP: $h[KelompokID] [ $NamaDosx ] -  $Judul</a> ($h[MhswID] - $NamaMhs) </b> <b $c>( $h[Status] )</b>
				  <br>
				  
				  <div align=right>
 <a href='$h[URLX]' target=_BLANK>| URL</a>
				  <a title='Komentar' href='index.php?ndelox=kk/pjudulkpadm&act=komentar&JadwalID=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]&KampusID=Kamp03'>| Komentar </a>	
<a title='Terima' href='index.php?ndelox=kk/pjudulkpadm&terima=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]&KampusID=Kamp03'>| Terima </a>	

<a title='Tolak' href='index.php?ndelox=kk/pjudulkpadm&tolak=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]&KampusID=Kamp03'>| Tolak </a>
				 				  
				  <a title='SetPembimbing' href='index.php?ndelox=kk/pjudulkpadm&act=setpembimbing&JadwalID=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]&KampusID=Kamp03'>| Set Pembimbing </a>
				  
				  <a target='_BLANK' title='Cetak' href='print_report/print-srpengajuanjudulkp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&prodi=$h[ProdiID]'>| Cetak Pengajuan</a>
				  
				 |
				 <a target='_BLANK' href='print_report/print-srperusahaankp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&MhswID=$h[MhswID]'>[ Cetak Surat Perusahaan ]</a>
				 (Pembimbing: $p1[Nama], $p1[Gelar]) 
				  <a title='Hapus' href='index.php?ndelox=kk/pjudulkpadm&del=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"> | Delete |  </a> 
				  
				  </div>
				 </td>
				 </tr>";  
                
          $tampil = mysqli_query($koneksi, "SELECT 
				jadwal_kp.TahunID,jadwal_kp.TahunID,jadwal_kp.DosenID,jadwal_kp.Judul,jadwal_kp.TempatPenelitian,
				jadwal_kp_anggota.MhswID,
				mhsw.Nama,mhsw.ProdiID,mhsw.Handphone 
				FROM jadwal_kp,jadwal_kp_anggota,mhsw 
				WHERE mhsw.MhswID=jadwal_kp_anggota.MhswID
				AND jadwal_kp.JadwalID=jadwal_kp_anggota.JadwalID
				AND jadwal_kp.JadwalID='$h[JadwalID]'  
				AND jadwal_kp.KelompokID='$h[KelompokID]' 
				AND jadwal_kp.TahunID='".strfilter($_GET['tahun'])."' 
				AND jadwal_kp.ProdiID='".strfilter($_GET['prodi'])."' 
				ORDER BY jadwal_kp.TglBuat DESC");
		  // AND TahunID='$_GET[tahun]' and ProdiID='$_GET[prodi]' and ProgramID like '%$_GET[program]%'                       
		  $no = 1;
          while($r=mysqli_fetch_array($tampil)){			 
         
		  echo "<tr><td>$no</td>   
		  		    <td>$r[MhswID]</td>  
		  			<td>$r[Nama] <b>[ <a href='index.php?ndelox=smsmhs&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&telepon=$r[Handphone]'> $r[Handphone] </a> ]</b></td>              
                   
                    <td>$h[ProdiID]</td>
					<td>$h[TglBuat]</td>";
                            
echo "<td style='width:70px !important'><center>
<a class='btn btn-danger btn-xs' title='Hapus' href='index.php?ndelox=kk/pjudulkpadm&hapus=$r[MhswID]&prodi=$r[ProdiID]&tahun=$r[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
	  </center></td>";

echo "</tr>";
                      $no++;
                      }
					  }
		  }//hari
                      if (isset($_GET['hapus'])){
                        mysqli_query($koneksi, "DELETE FROM jadwal_kp_anggota where MhswID='".strfilter($_GET['hapus'])."'");
                        echo "<script>document.location='index.php?ndelox=kk/pjudulkpadm&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
                      }
					  if (isset($_GET['del'])){
                         mysqli_query($koneksi, "DELETE FROM jadwal_kp where JadwalID='".strfilter($_GET['del'])."'");
						 mysqli_query($koneksi, "DELETE FROM jadwal_kp_anggota where JadwalID='".strfilter($_GET['del'])."'");
                        echo "<script>document.location='index.php?ndelox=kk/pjudulkpadm&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
                      }
					  if (isset($_GET['terima'])){
                         mysqli_query($koneksi, "UPDATE jadwal_kp SET Status='DITERIMA',Komentar='Judul OK' where JadwalID='".strfilter($_GET['terima'])."'");
                        echo "<script>document.location='index.php?ndelox=kk/pjudulkpadm&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
                      }	
					  if (isset($_GET['tolak'])){
                         mysqli_query($koneksi, "UPDATE jadwal_kp SET Status='DITOLAK',Komentar='Judul Belum Memenuhi Kriteria' where JadwalID='".strfilter($_GET['tolak'])."'");
                        echo "<script>document.location='index.php?ndelox=kk/pjudulkpadm&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
                      }					  
                  ?>
                    <tbody>
                  </table>
                  </div>
                </div><!-- /.box-body -->
                <?php 
                    if ($_GET['prodi'] == '' OR $_GET['tahun'] == ''){
                        echo "<center style='padding:60px; color:red'>Tentukan Tahun akademik, Program Studi Terlebih dahulu...</center>";
                    }
                ?>
                </div>
            </div>

<?php 
}

if ($_GET['act']=='ditolak'){ ?>      
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
<div class="card-body">
<div class="table-responsive">
	  <table id="example" class="table table-bordered table-striped">
		<thead>
		  <tr>
			<th style='width:20px'>No</th>
			<th style='width:100px'>NIM</th>  
			<th style='width:400px'>Nama</th>             
			<th>Program Studi</th> 
			<th>Tgl Pengajuan</th>                                
			<th width='140px'>Action</th> 
		  </tr>
		</thead>
		<tbody>
	  <?php
		if (isset($_GET['tahun'])){
			$qrs = mysqli_query($koneksi, "SELECT * from jadwal_kp where TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."' and Status='DITOLAK' order by Judul asc"); //TglBuat desc
			while ($h = mysqli_fetch_array($qrs)){
			   $judulx 	= strtolower($h['Judul']);
			   $Judul		= ucwords($judulx);
			   $mhs = mysqli_fetch_array(mysqli_query($koneksi, "select MhswID,Nama from mhsw where MhswID='$h[MhswID]'"));				 
			   $NamaMhsx 	= strtolower($mhs['Nama']);
			   $NamaMhs	= ucwords($NamaMhsx);
			   $p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$h[DosenID]'"));		      
			   $NamaDosx 	= strtoupper($p1['Nama']);
				  if ($h['Status']=='DITERIMA'){
				  $c="style=color:green;font-size:16px";
				  }
				  else if ($h['Status']=='WAITING'){
					  $c="style=color:#FF8306;font-size:16px";
				  }
				  else{
					  $c="style=color:red;font-size:16px";
				  }	

			  $hd++;
			echo"<tr style='background-color:#DFF4FF'>
			   <td colspan='11' height='20'><b>&nbsp;  
			   <a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=kk/pjudulkpadm&act=edit&JadwalID=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]'><i class='fa fa-edit'></i></a>
															 
				| <a href='index.php?ndelox=kk/pjudulkpadm&act=tambahanggota&JadwalID=$h[JadwalID]&KelompokID=$h[KelompokID]&prodi=$h[ProdiID]&tahun=$h[TahunID]'>
				$hd. KLP: $h[KelompokID] [ $NamaDosx ] -  $Judul</a> ($h[MhswID] - $NamaMhs) </b> <b $c>( $h[Status] )</b>
				<br>
				
				<div align=right>
<a href='$h[URLX]' target=_BLANK>| URL</a>
				<a title='Komentar' href='index.php?ndelox=kk/pjudulkpadm&act=komentar&JadwalID=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]&KampusID=Kamp03'>| Komentar </a>	
<a title='Terima' href='index.php?ndelox=kk/pjudulkpadm&terima=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]&KampusID=Kamp03'>| Terima </a>	

<a title='Tolak' href='index.php?ndelox=kk/pjudulkpadm&tolak=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]&KampusID=Kamp03'>| Tolak </a>
								 
				<a title='SetPembimbing' href='index.php?ndelox=kk/pjudulkpadm&act=setpembimbing&JadwalID=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]&KampusID=Kamp03'>| Set Pembimbing </a>
				
				<a target='_BLANK' title='Cetak' href='print_report/print-srpengajuanjudulkp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&prodi=$h[ProdiID]'>| Cetak Pengajuan</a>
				
			   |
			   <a target='_BLANK' href='print_report/print-srperusahaankp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&MhswID=$h[MhswID]'>[ Cetak Surat Perusahaan ]</a>
			   (Pembimbing: $p1[Nama], $p1[Gelar]) 
				<a title='Hapus' href='index.php?ndelox=kk/pjudulkpadm&del=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"> | Delete |  </a> 
				
				</div>
			   </td>
			   </tr>";  
			  
		$tampil = mysqli_query($koneksi, "SELECT 
			  jadwal_kp.TahunID,jadwal_kp.TahunID,jadwal_kp.DosenID,jadwal_kp.Judul,jadwal_kp.TempatPenelitian,
			  jadwal_kp_anggota.MhswID,
			  mhsw.Nama,mhsw.ProdiID,mhsw.Handphone 
			  FROM jadwal_kp,jadwal_kp_anggota,mhsw 
			  WHERE mhsw.MhswID=jadwal_kp_anggota.MhswID
			  AND jadwal_kp.JadwalID=jadwal_kp_anggota.JadwalID
			  AND jadwal_kp.JadwalID='$h[JadwalID]'  
			  AND jadwal_kp.KelompokID='$h[KelompokID]' 
			  AND jadwal_kp.TahunID='".strfilter($_GET['tahun'])."' 
			  AND jadwal_kp.ProdiID='".strfilter($_GET['prodi'])."' 
			  ORDER BY jadwal_kp.TglBuat DESC");
		// AND TahunID='$_GET[tahun]' and ProdiID='$_GET[prodi]' and ProgramID like '%$_GET[program]%'                       
		$no = 1;
		while($r=mysqli_fetch_array($tampil)){			 
	   
		echo "<tr><td>$no</td>   
					<td>$r[MhswID]</td>  
					<td>$r[Nama] <b>[ <a href='index.php?ndelox=smsmhs&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&telepon=$r[Handphone]'> $r[Handphone] </a> ]</b></td>              
				 
				  <td>$h[ProdiID]</td>
				  <td>$h[TglBuat]</td>";
						  
echo "<td style='width:70px !important'><center>
<a class='btn btn-danger btn-xs' title='Hapus' href='index.php?ndelox=kk/pjudulkpadm&hapus=$r[MhswID]&prodi=$r[ProdiID]&tahun=$r[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
	</center></td>";

echo "</tr>";
					$no++;
					}
					}
		}//hari
					if (isset($_GET['hapus'])){
					  mysqli_query($koneksi, "DELETE FROM jadwal_kp_anggota where MhswID='".strfilter($_GET['hapus'])."'");
					  echo "<script>document.location='index.php?ndelox=kk/pjudulkpadm&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
					}
					if (isset($_GET['del'])){
					   mysqli_query($koneksi, "DELETE FROM jadwal_kp where JadwalID='".strfilter($_GET['del'])."'");
					   mysqli_query($koneksi, "DELETE FROM jadwal_kp_anggota where JadwalID='".strfilter($_GET['del'])."'");
					  echo "<script>document.location='index.php?ndelox=kk/pjudulkpadm&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
					}
					if (isset($_GET['terima'])){
					   mysqli_query($koneksi, "UPDATE jadwal_kp SET Status='DITERIMA',Komentar='Judul OK' where JadwalID='".strfilter($_GET['terima'])."'");
					  echo "<script>document.location='index.php?ndelox=kk/pjudulkpadm&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
					}	
					if (isset($_GET['tolak'])){
					   mysqli_query($koneksi, "UPDATE jadwal_kp SET Status='DITOLAK',Komentar='Judul Belum Memenuhi Kriteria' where JadwalID='".strfilter($_GET['tolak'])."'");
					  echo "<script>document.location='index.php?ndelox=kk/pjudulkpadm&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
					}					  
				?>
				  <tbody>
				</table>
				</div>
			  </div><!-- /.box-body -->
			  <?php 
				  if ($_GET['prodi'] == '' OR $_GET['tahun'] == ''){
					  echo "<center style='padding:60px; color:red'>Tentukan Tahun akademik, Program Studi Terlebih dahulu...</center>";
				  }
			  ?>
			  </div>
		  </div>

<?php 
}


elseif($_GET['act']=='edit'){
    if (isset($_POST['update'])){			
		$query = mysqli_query($koneksi, "UPDATE jadwal_kp SET 							
							  DosenID 			= '".strfilter($_POST['DosenID'])."',							   
							  Judul 			= '".strfilter($_POST['Judul'])."',
							  TempatPenelitian 	= '".strfilter($_POST['TempatPenelitian'])."',
							  Kota 				= '".strfilter($_POST['Kota'])."',
							  Ke 				= '".strfilter($_POST['Ke'])."',
							  TglEdit 			= '".date('Y-m-d')."',
							  LoginEdit 		= '".$_SESSION['_Login']."' 
							  where JadwalID	= '".strfilter($_POST['JadwalID'])."'"); 
        if ($query){
          echo "<script>document.location='index.php?ndelox=kk/pjudulkpadm&tahun=$_POST[tahun]&prodi=$_POST[prodi]&JadwalID=$_POST[JadwalID]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=kk/pjudulkpadm&tahun=$_POST[tahun]&prodi=$_POST[prodi]&JadwalID=$_POST[JadwalID]&gagal';</script>";
        }
    }
    
    $tg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jadwal_kp where JadwalID='".strfilter($_GET['JadwalID'])."'"));
    $mhs= mysqli_fetch_array(mysqli_query($koneksi, "select MhswID,Nama,ProdiID,ProgramID from mhsw where MhswID='$tg[MhswID]'"));
	echo "

              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
			  <div class='card'>
			  <div class='card-header'>
			  <div class='table-responsive'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                  <input type='hidden' name='JadwalID' value='$_GET[JadwalID]'>		
				  <input type='hidden' name='prodi' value='$_GET[prodi]'>				 
				  <input type='hidden' name='tahun' value='$_GET[tahun]'>               
                 <h3 class='box-title'><b style=color:#FF8306>Edit Pengajuan Judul Kerja Praktek</b></h3> 
				 <tr style=background:#FFCC00><th scope='row' colspan=2>Kelompok/Tim Kerja Praktek</th></tr>  
				 <tr><th scope='row' width='200px'>Ketua Kelompok</th><td><input type='text' class='form-control form-control-sm' name='MhswID' value='$tg[MhswID] - $mhs[Nama]'></td></tr> 
				 <tr><th scope='row' width='200px'>Judul Kerja Praktek</th><td><input type='text' class='form-control form-control-sm' name='Judul' value='$tg[Judul]'></td></tr> 
                 <tr><th scope='row'>Tempat Penelitian</th><td><input type='text' class='form-control form-control-sm' name='TempatPenelitian' value='$tg[TempatPenelitian]'></td></tr>
			     <tr><th scope='row'>Dosen Pembimbing</th>   <td><select class='form-control form-control-sm select2' name='DosenID'> 
						<option value='0' selected>- Pilih Dosen -</option>"; 
						$dosen = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by  Nama ASC");
						while($a = mysqli_fetch_array($dosen)){
						  if ($tg['DosenID']==$a['Login']){
						     echo "<option value='$a[Login]' selected> $a[Nama] - [ $a[Login] ] </option>";
						  }else{
						     echo "<option value='$a[Login]' > $a[Nama] - [ $a[Login] ] </option>";	  
						  }
						}
				echo "</select>
				</td></tr>
				
				<tr style=background:#FFCC00><th scope='row' colspan=2>Setting Penerbitan Surat Pengantar Ke Perusahaan</th></tr>
                <tr><th scope='row'>Tujuan Surat / Kota </th> <td><input type='text' class='form-control form-control-sm' name='Kota' value='$tg[Kota]'></td></tr>
			    <tr><th scope='row'>Kepada</th> <td><input type='text' class='form-control form-control-sm' name='Ke' value='$tg[Ke]'></td></tr>
                </tbody>
                </table>
				<div class='box-footer'>
				<button type='submit' name='update' class='btn btn-info'>Update</button>
				<a href='index.php?ndelox=kk/pjudulkpadm&tahun=$_GET[tahun]&prodi=$_GET[prodi]&program=$_GET[program]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>                 
				</div>
				</div>
				</div>
				</div>

              </form>";
}elseif($_GET['act']=='tambahanggota'){
    if (isset($_POST['tambah'])){	
	    $tglnow =date('Y-m-d H:i:s');	
	    
		$cek1 = mysqli_num_rows(mysqli_query($koneksi, "select * from mhsw where MhswID='".strfilter($_POST['MhswID'])."'"));
		if ($cek1==0){
			 echo "<a href='index.php?ndelox=kk/pjudulkpadm&act=tambahanggota&JadwalID=$_GET[JadwalID]&prodi=$_GET[prodi]&tahun=$_GET[tahun]&KelompokID=$_POST[KelompokID]'> Data tidak ketemu!</a>";		
			exit;
		}
// 		/*
// 		and TahunID='".strfilter($_POST[tahun])."' 
// 		*/
		
		
		$cek2 = mysqli_num_rows(mysqli_query($koneksi, "select * from jadwal_kp_anggota where MhswID='".strfilter($_POST['MhswID'])."' "));
		if ($cek2>0){
			 echo "<script>document.location='index.php?ndelox=kk/pjudulkpadm&act=tambahanggota&JadwalID=$_GET[JadwalID]&program=$_GET[program]&prodi=$_GET[prodi]&tahun=$_GET[tahun]&KelompokID=$_POST[KelompokID]&gagal';</script>";
			exit;
		}
	  
	    $query = mysqli_query($koneksi, "INSERT INTO jadwal_kp_anggota(JadwalID,KelompokID,MhswID)VALUES('".strfilter($_POST['JadwalID'])."','".strfilter($_POST['KelompokID'])."','".strfilter($_POST['MhswID'])."')");		
        if ($query){
			echo "<script>document.location='index.php?ndelox=kk/pjudulkpadm&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&sukses';</script>";
        }else{
            echo "<script>document.location='index.php?ndelox=kk/pjudulkpadm&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&gagal';</script>";
        }		
    }
    $tg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jadwal_kp where JadwalID='".strfilter($_GET['JadwalID'])."'"));
	$x   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$tg[DosenID]'"));
	$mhsx   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MhswID,Nama FROM mhsw where MhswID='$tg[MhswID]'"));
    echo "
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>              
				 <input type='hidden' name='JadwalID' value='$_GET[JadwalID]'>
				 <input type='hidden' name='tahun' value='$_GET[tahun]'>				
				<input type='hidden' name='prodi' value='$_GET[prodi]'>
				<div class='card'>
				<div class='card-header'>
				                <div class='box-header with-border'>
                  <h3 class='box-title'><b style='color:green;font-size:20px'>Tambah Data Anggota Kelompok</b></h3>
                </div>
				<div class='table-responsive'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th scope='row' width='220px'>Mahasiswa Pengusul</th><td><b>$tg[MhswID] - $mhsx[Nama]</b></td></tr> 
					<tr><th scope='row'>Judul</th><td><b>$tg[Judul]</b></td></tr>
				  <tr><th scope='row'>Pembimbing</th><td><b>$x[Nama], $x[Gelar]</b></td></tr>
					<tr><th scope='row'>Nama Kelompok</th>           <td><input type='text' class='form-control form-control-sm' name='KelompokID' value='$_GET[KelompokID]' readonly=''></td></tr>
                    <tr><th scope='row'>NIM</th>           <td><input type='text' class='form-control form-control-sm' name='MhswID'></td></tr>										
                  </tbody>
                  </table>
				                <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?ndelox=kk/pjudulkpadm&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
                </div>
              </div>

              </form>
            </div>";
}

elseif($_GET['act']=='setpembimbing'){
    if (isset($_POST['simpanpembimbing'])){		       
		$query = mysqli_query($koneksi, "UPDATE jadwal_kp SET 							
							  DosenID 		= '".strfilter($_POST['DosenID'])."',							   
							  LoginEdit	= '".$_SESSION[id]."'
							  where JadwalID='".strfilter($_POST['JadwalID'])."'");
        if ($query){
          echo "<script>document.location='index.php?ndelox=kk/pjudulkpadm&tahun=$_POST[tahun]&prodi=$_POST[prodi]&JadwalID=$_POST[JadwalID]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=kk/pjudulkpadm&tahun=$_POST[tahun]&prodi=$_POST[prodi]&JadwalID=$_POST[JadwalID]&gagal';</script>";
        }
    }
    
    $tg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jadwal_kp where JadwalID='".strfilter($_GET['JadwalID'])."'"));
	$x   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$tg[DosenID]'"));

	
    echo "
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
			  <div class='card'>
			  <div class='card-header'>
			  	<div class='box-header with-border'>
			  		<h3 class='box-title'>Penetapan Pembimbing</h3>
				</div>
			  <div class='table-responsive'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                  <input type='hidden' name='JadwalID' value='$_GET[JadwalID]'>		
				  <input type='hidden' name='prodi' value='$_GET[prodi]'>				 
				  <input type='hidden' name='tahun' value='$_GET[tahun]'>
                 <tr><th scope='row'>Judul Kerja Praktek</th><td>$tg[Judul]</tr>
                  <tr><th scope='row'>Pembimbing</th>   <td><select class='form-control form-control-sm select2' name='DosenID'> 
						<option value='0' selected>- Pilih Pembimbing -</option>"; 
						$dosen = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by Nama asc");
						while($a = mysqli_fetch_array($dosen)){
						  if ($tg['DosenID']==$a['Login']){
						     echo "<option value='$a[Login]' selected> $a[Nama] - [ $a[Login] ] </option>";
						  }else{
						     echo "<option value='$a[Login]' > $a[Nama] - [ $a[Login] ] </option>";	  
						  }
						}
				echo "</select>
				</td></tr>
				
                  </tbody>
                  </table>
						<div class='box-footer'>
						<button type='submit' name='simpanpembimbing' class='btn btn-info'>Simpan</button>
						<a href='index.php?ndelox=kk/pjudulkpadm&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
						
						</div>
                </div>
              </div>
			  </div>

              </form>";
}

elseif($_GET['act']=='ajukanjudul'){

	if ($_GET['prodi']=='SI'){$b='SI';}else{$b='TI';}

	//$sqNo = mysqli_fetch_array(mysqli_query($koneksi, "SELECT KelompokID,ProdiID FROM jadwal_kp WHERE ProdiID='".strfilter($_GET[prodi])."' ORDER BY JadwalID DESC LIMIT 1"));
	//$ex = explode('-', $sqNo[KelompokID]);
	$w = mysqli_fetch_array(mysqli_query($koneksi, "SELECT JadwalID,KelompokID,ProdiID,TahunID FROM jadwal_kp ORDER BY JadwalID DESC LIMIT 1"));
	$ex = explode('-', $w['JadwalID']); 
	 
	if (date('d')=='01'){ $a = '01'; }
	else{ $a = $ex[0]+1; }	 	
	//$c = array('','I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII');
	$d = date('y');
	//$KodeAuto = $a.'/'.$b.'/'.$c[date('n')].'/'.$d;
	$KodeAuto = $a.'-'.$d.$b; //$a ditarok belakang tidak jalan
	if (isset($_POST['tambah'])){	
	    $tglnow =date('Y-m-d H:i:s');		   
// 		$cek = mysqli_num_rows(mysqli_query($koneksi, "select * from jadwal_kp 
// 											WHERE MhswID='".strfilter($_POST[MhswID])."'
// 											AND TahunID='".strfilter($_POST[tahun])."'"));
// 		if ($cek>0){
// 			echo "<script>document.location='index.php?ndelox=pjudulkpmhs&tahun=$_POST[tahun]&prodi=$_POST[prodi]&gagal';</script>";
// 			exit;
// 		}
 				
	    $query = mysqli_query($koneksi, "INSERT INTO jadwal_kp(
							  TahunID,
							  KelompokID,
							  MhswID,
							  DosenID,
							  Judul,
							  TempatPenelitian,							 
							  TglMulaiSidang,
							  ProgramID,
							  ProdiID,
							  TglBuat,
							  LoginBuat,
							  TglEdit,
							  LoginEdit,              
							  NA,
							  Deskripsi,
							  Kota,
							  Ke) 
					 VALUES(
							'".strfilter($_POST['tahun'])."',
							'".strfilter($_POST['KelompokID'])."',
							'".strfilter($_POST['MhswID'])."',
							'".strfilter($_POST['DosenID'])."',
							'".strfilter($_POST['Judul'])."',
							'".strfilter($_POST['TempatPenelitian'])."',
							'".date('Y-m-d')."',							
							'".strfilter($_POST['program'])."',
							'".strfilter($_POST['prodi'])."',
							'".date('Y-m-d')."',
							'$_SESSION[_Login]',
							'".strfilter($_POST['am'])."',
						    '".strfilter($_POST['an'])."',                
							'N',
							'".strfilter($_POST['Deskripsi'])."',
							'".strfilter($_POST['Kota'])."',
							'".strfilter($_POST['Ke'])."')");
		
        if ($query){
			echo "<script>document.location='index.php?ndelox=kk/pjudulkpadm&tahun=$_POST[tahun]&prodi=$_POST[prodi]&sukses';</script>";
        }else{
            echo "<script>document.location='index.php?ndelox=kk/pjudulkpadm&tahun=$_POST[tahun]&prodi=$_POST[prodi]&gagal';</script>";
        }
		
    }

    echo "
	<div class='card'>
			  <div class='card-header'>
			  	<div class='box-header with-border'>
				  <h3 class='box-title'><b style=color:#FF8306>Pengajuan Judul Kerja Praktek</b></h3>
				</div>
			  <div class='table-responsive'>


             
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <input type='hidden' name='tahun' value='$_GET[tahun]'>
				<input type='hidden' name='prodi' value='$_GET[prodi]'>

                  <table class='table table-condensed table-bordered'>
                  <tbody>
                   	<tr style=background:#FFCC00><th scope='row' colspan=2>Kelompok/Tim Kerja Praktek</th></tr>
					<tr><th scope='row' width='200'>Nama Kelompok</th>           <td><input type='text' class='form-control form-control-sm' name='KelompokID' value='$KodeAuto' readonly></td></tr>
					<tr><th scope='row' >Ketua Kelompok</th>           
					<td>
					<select name='MhswID' class='combobox form-control form-control-sm' >
					<option value=''> Pencarian Data Mahasiswa </option>";
						$program = mysqli_query($koneksi, "SELECT MhswID,Nama,ProgramID,ProdiID,Handphone FROM mhsw where ProdiID='".strfilter($_GET['prodi'])."' and StatusMhswID='A' order by MhswID asc");
						while ($k = mysqli_fetch_array($program)){
						 if ($_GET['MhswID']==$k['MhswID']){
							echo "<option value='$k[MhswID]' selected>$k[MhswID] - $k[Nama] - $k[ProgramID] - $k[Handphone] </option>";
						  }else{
							echo "<option value='$k[MhswID]'>$k[MhswID] - $k[Nama] - $k[ProgramID] - $k[Handphone]</option>";
						  }
						}
					
					echo"</select> 
					</td></tr>
                    <tr><th scope='row'>Judul Kerja Praktek</th>           <td><textarea rows='2' class='form-control form-control-sm' name='Judul' ></textarea></td></tr>
					<tr><th scope='row'>Tempat Penelitian</th>           <td><input type='text' class='form-control form-control-sm' name='TempatPenelitian'></td></tr>
					
					<tr>
					<th scope='row'>Deskripsi</td>    
					<td $c><textarea class='form-control form-control-sm' rows='6' name='Deskripsi'></textarea></td>
					</tr>
					 <tr style=background:#FFCC00><th scope='row' colspan=2>Setting Penerbitan Surat Pengantar Ke Perusahaan</th></tr>
                    <tr><th scope='row'>Tujuan Surat / Kota </th>           <td><input type='text' class='form-control form-control-sm' name='Kota'></td></tr>
					<tr><th scope='row'>Kepada</th> <td><input type='text' class='form-control form-control-sm' name='Ke'></td></tr>
					
                  </tbody>
                  </table>
              
              
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?ndelox=kk/pjudulkpadm&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                
				  
				       
                <div class='box-body'>
                 <table>
                 <tr><td colspan='20' align='left'>
				 <b style='color:red;'><marquee>Kerja Praktek merupakan matakuliah yang dilakukan berkelompok yang terdiri dari maksimal 3 orang dan minimal 2 orang, Pengajuan Judul KP hanya satu judul dan dilakukan oleh Ketua Kelompok</marquee></b>
                 </td>
                 </tr>
                 </table>
						 
             	</div>
               </div>
             </div> 
				  
              </form>
            </div>";
}

elseif($_GET['act']=='komentar'){
if (isset($_POST['koment'])){	
    $qr = mysqli_query($koneksi, "UPDATE jadwal_kp SET Komentar = '".strfilter($_POST['Komentar'])."'
					WHERE JadwalID='".strfilter($_POST['JadwalID'])."'");	
if ($qr){
		echo "<script>document.location='index.php?ndelox=kk/pjudulkpadm&tahun=$_POST[tahun]&prodi=$_POST[prodi]&sukses';</script>";
	}else{
		echo "<script>document.location='index.php?ndelox=kk/pjudulkpadm&tahun=$_POST[tahun]&prodi=$_POST[prodi]&gagal';</script>";
	}
}
$e = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from jadwal_kp where JadwalID='".strfilter($_GET['JadwalID'])."'"));
$m = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MhswID,Nama from mhsw where MhswID='$e[MhswID]'"));
echo "<div class='col-xs-12'>
    <div class='box box-info'>
    <div class='box-header'>
		<div class='box-header with-border'>
		  <h3 class='box-title'>Komentar</h3>
		</div>
	  <div class='box-body'>
	  <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
	    <input type='hidden' name='JadwalID' value='$_GET[JadwalID]'>
		<input type='hidden' name='tahun' value='$_GET[tahun]'>
		<input type='hidden' name='prodi' value='$_GET[prodi]'>
		<div class='col-md-12'>
		  <table class='table table-condensed table-bordered'>
		  <tbody>
		  <tr><th  scope='row' width='200px'>Mahasiswa</th> <td><input type='text' class='form-control form-control-sm' name='x' value='$e[MhswID] - $m[Nama]' readonly></td></tr>
		  <tr><th  scope='row' width='200px'>Judul Kerja Praktek</th> <td><input type='text' class='form-control form-control-sm' name='Judul' value='$e[Judul]' readonly></td></tr>
		  
		  <tr ><th  scope='row' width='200px'>Berikan Komentar</th> <td><textarea  class='form-control form-control-sm' name='Komentar' rows=4>$e[Komentar]</textarea></td></tr>
		  </tbody>
		  </table>
		</div>
	  </div>
	  <div class='box-footer'>
			<button type='submit' name='koment' class='btn btn-info'>Tambahkan Komentar</button>
			<a href='index.php?ndelox=kk/pjudulkpadm&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
			
		  </div>
	  </form>
	</div>";
}
?>