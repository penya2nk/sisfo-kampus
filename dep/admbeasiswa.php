<div class='card'>
<div class='card-header'>
<h3 class="box-title">
<?php 
  if (isset($_GET['tahun'])){ 
     echo "<b style='color:green;font-size:20px'>Beasiswa &nbsp;&nbsp;</b>"; 
	 
  }else{ 
     echo "<b style='color:green;font-size:20px'>Beasiswa</b>";  
  } ?>
</h3>

<?php 
 if ($_GET['prodi'] == '' OR $_GET['tahun'] == '' ){ ?>
     <?php echo"Upps Mas Ir!";?>
 <?php
 }else{
?>	     
	<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=dep/admbeasiswa&act=tambahdata&tahun=<?php echo $_GET['tahun']; ?>&prodi=<?php echo "$_GET[prodi]"; ?>&program=<?php echo 	"$_GET[program]"; ?>'>Tambahkan Data</a>
<?php } ?>
                  
<div class="form-group row">
		<label class="col-md-7 col-form-label text-md-right"><b style='color:purple'>BEASISWA</b></label>
		<div class="col-md-2">
		<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
			<input type="hidden" name='ndelox' value='dep/admbeasiswa'>
			<select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
				<?php 
					echo "<option value=''>- Pilih Tahun Akademik -</option>";
					$tahun = mysqli_query($koneksi, "SELECT * FROM t_tahunnormal order by Tahun Desc"); //and NA='N'
					while ($k = mysqli_fetch_array($tahun)){
					if ($_GET['tahun']==$k['Tahun']){
						echo "<option value='$k[Tahun]' selected>$k[Tahun]</option>";
					}else{
						echo "<option value='$k[Tahun]'>$k[Tahun]</option>";
					}
					}
				?>
			</select>
			</div>

		<div class="col-md-2">
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
		<input type="submit" class='btn btn-success btn-sm' value='Lihat'>
		</form>
		</div>
</div>

</div>
</div>

<?php if ($_GET['act']==''){ ?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
        <table id="example1" class="table table-sm table-striped">
          <thead>
            <tr style='background:purple;color:white'>
              <th style='width:20px;text-align:center'>No</th>             
              <th style='width:70px;text-align:center'>NIM</th>
              <th style='width:200px'>Nama Mahasiswa</th>
               <th style='width:120px'>Handphone</th>
			
			    <th style='text-align:left'>Jenis Beasiswa</th>
			    <th style='text-align:right'>Jumlah</th>
			    <th style='text-align:center'>Prodi</th>
			  <th style='text-align:center'>TahunID</th>
			  <th style='width:70px;text-align:center'>Aksi</th>
            </tr>
          </thead>
          <tbody>
        <?php
            if ($_GET['tahun']!='' AND $_GET['prodi']!=''){
			 $tampil = mysqli_query($koneksi, "select mhsw.Nama, mhsw.ProdiID,mhsw.Handphone, beasiswamhsw.* 
			                        from mhsw, beasiswa, beasiswamhsw 
			                        where mhsw.MhswID= beasiswamhsw.MhswID
			                        and beasiswa.BeasiswaID= beasiswamhsw.BeasiswaID
			                        and left(beasiswamhsw.TahunID,4)='".strfilter($_GET['tahun'])."'
			                        and mhsw.ProdiID='".strfilter($_GET['prodi'])."'");
		 	}
		 	else if ($_GET['tahun']!='' AND $_GET['prodi']==''){
			 $tampil = mysqli_query($koneksi, "select mhsw.Nama, mhsw.ProdiID,mhsw.Handphone, beasiswamhsw.* 
			                        from mhsw, beasiswa, beasiswamhsw 
			                        where mhsw.MhswID= beasiswamhsw.MhswID
			                       and beasiswa.BeasiswaID= beasiswamhsw.BeasiswaID
			                        and left(beasiswamhsw.TahunID,4)='".strfilter($_GET['tahun'])."'");
		  	}
		  	else if ($_GET['tahun']=='' AND $_GET['prodi']!==''){
		     $tampil = mysqli_query($koneksi, "select mhsw.Nama, mhsw.ProdiID,mhsw.Handphone, beasiswamhsw.* 
		                             from mhsw, beasiswa, beasiswamhsw 
			                        where mhsw.MhswID= beasiswamhsw.MhswID
			                        and beasiswa.BeasiswaID= beasiswamhsw.BeasiswaID
			                        and mhsw.ProdiID='".strfilter($_GET['prodi'])."'");
		    }else{
		     $tampil = mysqli_query($koneksi, "select mhsw.Nama, mhsw.ProdiID,mhsw.Handphone, beasiswamhsw.* 
		                            from mhsw, beasiswa, beasiswamhsw 
			                        where mhsw.MhswID= beasiswamhsw.MhswID
			                        and beasiswa.BeasiswaID= beasiswa.BeasiswaID");  
		    }	                        
		  $no = 1;
          while($r=mysqli_fetch_array($tampil)){  
          $jnsb = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM beasiswa where BeasiswaID='$r[BeasiswaID]'"));
          $NamaBeax = strtolower($jnsb['Nama']); //strtoupper($kalimat);
          $NamaBea	= ucwords($NamaBeax);
          $NamaMhsx = strtolower($r['Nama']); //strtoupper($kalimat);
          $NamaMhs	= ucwords($NamaMhsx);
          echo "<tr><td style='text-align:center'>$no</td>
                    <td style='text-align:center'>$r[MhswID]</td>
                    <td>$NamaMhs</td>
                    <td>$r[Handphone]</td>
                     <td style='text-align:left'>$NamaBea ($r[BeasiswaID])</td>
                    <td style='text-align:right'>".number_format($r['Besar'])."</td>
                   
                     <td style='text-align:center'>$r[ProdiID]</td>
                    <td style='text-align:center'>$r[TahunID]</td>";
                   
		echo "<td style='width:70px !important'><center><a class='btn btn-success btn-xs' title='Edit Data' href='index.php?ndelox=dep/admbeasiswa&act=editdata&id=$r[BeasiswaMhswID]&program=$r[ProgramID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'><i class='fa fa-edit'></i></a>
		<a class='btn btn-danger btn-xs' title='Hapus Data' href='index.php?ndelox=dep/admbeasiswa&hapus=$r[BeasiswaMhswID]&program=$r[ProgramID]&prodi=$r[ProdiID]&tahun=$r[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
		</center></td>";

echo "</tr>";
	  $no++;
	  }

	  if (isset($_GET['hapus'])){
		mysqli_query($koneksi, "DELETE FROM beasiswamhsw where BeasiswaMhswID='".strfilter($_GET['hapus'])."'");
		echo "<script>document.location='index.php?ndelox=dep/admbeasiswa&program=$_GET[program]&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
	  }
  ?>
	<tbody>
  </table></div>
</div>
</div>

<?php 
}elseif($_GET['act']=='tambahdata'){
    if (isset($_POST['tambah'])){	
	    $tglnow =date('Y-m-d H:i:s');	
        $query = mysqli_query($koneksi, "INSERT INTO beasiswamhsw(
							TahunID,
							KodeID,
						    BeasiswaID,
							MhswID,
							Besar,
							TanggalBuat,
							LoginBuat,
							NA,
							Keterangan) 
					 VALUES('".strfilter($_POST['tahun'])."',
					 	    'SISFO',
							'".strfilter($_POST['BeasiswaID'])."',
							'".strfilter($_POST['MhswID'])."',
						    '".strfilter($_POST['Besar'])."',
							'$tglnow',
							'$_SESSION[_Login]',
							'N',
							'".strfilter($_POST['Keterangan'])."')");
		
        if ($query){
            //echo"Berhasil";
			echo "<script>document.location='index.php?ndelox=dep/admbeasiswa&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&sukses';</script>";
        }else{
            echo "<script>document.location='index.php?ndelox=dep/admbeasiswa&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&gagal';</script>";
        }
		
    }

    //$ex = explode('.',$_GET[kelas]);
    //$tingkat = $ex[0];

    echo "



              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
			  <div class='card'>
			  <div class='card-header'>
			  	<div class='box box-info'>
                  <h3 class='box-title'>Tambah Data Penerima Beasiswa</h3>
                </div>
			  <div class='table-responsive'>
                  <table class='table table-sm table-bordered'>
                  <tbody>
                    <tr><th style='width:260px' scope='row'>Tahun Akademik</th>   <td><select class='form-control' name='tahun'> 
						<option value='0' selected>- Pilih Tahun Akademik -</option>"; 
						$tahun = mysqli_query($koneksi, "SELECT * FROM t_tahunnormal order by Tahun DESC ");
						while($a = mysqli_fetch_array($tahun)){
						  if ($_GET['tahun']==$a['Tahun']){
							echo "<option value='$a[Tahun]' selected>$a[Tahun]</option>";
						  }else{
							echo "<option value='$a[Tahun]'>$a[Tahun]</option>";
						  }
						}
						echo "</select>
                    </td></tr>
                    
                    <tr><th style='width:260px' scope='row'>Program Studi</th>   <td><select class='form-control' name='prodi'> 
						<option value='0' selected>- Pilih Program Studi -</option>"; 
						$tahun = mysqli_query($koneksi, "SELECT distinct(ProdiID),NA,Nama FROM prodi ");
						while($a = mysqli_fetch_array($tahun)){
						  if ($_GET['prodi']==$a['ProdiID']){
							echo "<option value='$a[ProdiID]' selected>$a[Nama]</option>";
						  }else{
							echo "<option value='$a[ProdiID]'>$a[Nama]</option>";
						  }
						}
						echo "</select>
                    </td></tr>
                    

				
										
                    <tr><th scope='row'>Mahasiswa</th>   <td><select class='form-control select2' name='MhswID'> 
						<option value='$a[MhswID]' selected>- Cari Mahasiswa -</option>"; 
						if ($_GET['prodi']=='SI'){
						    $mt = mysqli_query($koneksi, "SELECT * FROM mhsw where ProdiID='$_GET[prodi]'"); // AND StatusMhswID='A'
						}else{
							$mt = mysqli_query($koneksi, "SELECT * FROM mhsw where ProdiID='$_GET[prodi]'"); // AND StatusMhswID='A'
						}
						while($a = mysqli_fetch_array($mt)){
						  echo "<option value='$a[MhswID]'>$a[MhswID] - $a[Nama]</option>";
						}
						echo "</select>
                    </td></tr>
                    
                    <tr><th style='width:260px' scope='row'>Jenis Beasiswa</th>   <td><select class='form-control' name='BeasiswaID' required> 
						<option value='' selected>- Pilih Jenis Beasiswa -</option>"; 
						$jb = mysqli_query($koneksi, "SELECT * FROM beasiswa ");
						while($a = mysqli_fetch_array($jb)){
							echo "<option value='$a[BeasiswaID]'>$a[Nama] - ($a[BeasiswaID])</option>";
						}
						echo "</select>
                    </td></tr>
				
  
                    <tr><th scope='row'>Jumlah Nominal</th>  <td><input type='text' style='width:100px' class='form-control' name='Besar'></td></tr>
                    <tr><th scope='row'>Keterangan</th>  <td><input type='text' style='width:100%' class='form-control' name='Keterangan'></td></tr>
                  </tbody>
                  </table>
				                <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?ndelox=dep/admbeasiswa&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
				  </div>
				  </div>
				  </div>

              </form>
     ";
}

elseif($_GET['act']=='editdata'){
    if (isset($_POST['update'])){
        $query = mysqli_query($koneksi, "UPDATE beasiswamhsw SET 
							  TahunID 		= '".strfilter($_POST['tahun'])."',
							  BeasiswaID 	= '".strfilter($_POST['BeasiswaID'])."',  
							  Besar 		= '".strfilter($_POST['Besar'])."',	
							  Keterangan 	= '".strfilter($_POST['Keterangan'])."',
							  LoginEdit 	= 	'$_SESSION[_Login]',                                                   
							  TanggalEdit 	= '".date('Y-m-d H:i:s')."'
							  where BeasiswaMhswID	= '".strfilter($_POST['id'])."'");
        if ($query){
          echo "<script>document.location='index.php?ndelox=dep/admbeasiswa&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&xx=$_POST[JadwalID]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=dep/admbeasiswa&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&xx=$_POST[JadwalID]&gagal';</script>";
        }
    }
    
    $e = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM beasiswamhsw where BeasiswaMhswID='".strfilter($_GET['id'])."'"));
    $mhsx = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mhsw where MhswID='$e[MhswID]'"));
    $prd = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM prodi where ProdiID='$mhsx[ProdiID]'"));
    echo "


              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
			  <div class='card'>
			  <div class='card-header'>
			  	<div class='box box-info'>
              <h3 class='box-title'>Edit Data Penerima Beasiswa</h3>
              </div>
			  <div class='table-responsive'>
                  <table class='table table-sm table-bordered'>
                  <tbody>
                  <input type='hidden' name='id' value='$_GET[id]'>
                   <input type='hidden' name='prodi' value='$prd[ProdiID]'>
				   <tr><th style='width:260px' scope='row'>Tahun Akademik</th>   <td><select class='form-control' name='tahun'> 
						<option value='0' selected>- Pilih Tahun Akademik -</option>"; 
						$tahun = mysqli_query($koneksi, "SELECT * FROM t_tahunnormal order by Tahun DESC");
						while($a = mysqli_fetch_array($tahun)){
						  if ($_GET['tahun']==$a['Tahun']){
							echo "<option value='$a[Tahun]' selected>$a[Tahun]</option>";
						  }else{
							echo "<option value='$a[Tahun]'>$a[Tahun]</option>";
						  }
						}
						echo "</select>
                    </td></tr>
                    
               
										
                    <tr><th style='width:260px' scope='row'>Nama Mahasiswa</th>   <td><select class='form-control select2' name='xxx'> 
						<option value='0' selected>- Mahasiswa -</option>"; 
						$jb = mysqli_query($koneksi, "SELECT * FROM mhsw where ProdiID='".strfilter($_GET['prodi'])."'");
						while($a = mysqli_fetch_array($jb)){
                        if ($e['MhswID']==$a['MhswID']){
							echo "<option value='$a[MhswID]' selected>$a[MhswID] - $a[Nama]</option>";
						  }else{
							echo "<option value='$a[MhswID]'>$a[MhswID] - $a[Nama]</option>";
						  }
						}
						echo "</select>
                    </td></tr>
                    
                    <tr><th style='width:260px' scope='row'>Jenis Beasiswa</th>   <td><select class='form-control' name='BeasiswaID'> 
						<option value='0' selected>- Pilih Jenis Beasiswa -</option>"; 
						$jb = mysqli_query($koneksi, "SELECT * FROM beasiswa order by Nama ASC");
						while($a = mysqli_fetch_array($jb)){
                        if ($e['BeasiswaID']==$a['BeasiswaID']){
							echo "<option value='$a[BeasiswaID]' selected>$a[Nama]  - ($a[BeasiswaID])</option>";
						  }else{
							echo "<option value='$a[BeasiswaID]'>$a[Nama]  - ($a[BeasiswaID])</option>";
						  }
						}
						echo "</select>
                    </td></tr>
				
  
                    <tr><th scope='row'>Jumlah Nominal</th>  <td><input type='text' style='width:100px' class='form-control' name='Besar' value='$e[Besar]'></td></tr>
                    <tr><th scope='row'>Keterangan</th>  <td><input type='text' style='width:100%' class='form-control' name='Keterangan' value='$e[Keterangan]'></td></tr>
                  </tbody>
                  </table>
				  <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='index.php?ndelox=dep/admbeasiswa&tahun=$_GET[tahun]&prodi=$prd[ProdiID]&program=$_GET[program]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
				  </div>
				  </div>
				  </div>
              
              </form>
            ";
}

?>