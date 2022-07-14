<div class='card'>
<div class='card-header'>

<h3 class="box-title">
<?php 
  if (isset($_GET[tahun])){ 
     echo "<b style='color:green;font-size:20px'>Merdeka Belajar Kampus Merdeka (MBKM) &nbsp;&nbsp; </b>"; 
	 
  }else{ 
     echo "<b style='color:green;font-size:20px'>Merdeka Belajar Kampus Merdeka (MBKM)</b>";  
  } ?>
</h3>


 <div class="form-group row">
<label class="col-md-5 col-form-label text-md-right"><b style='color:purple'>MBKM</b></label>
<div class="col-md-2">         
  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
	<input type="hidden" name='ndelox' value='dep/mbkm'>
	<select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
		<?php 
			echo "<option value=''>- Pilih Tahun Akademik -</option>";
			$tahun = mysqli_query($koneksi, "SELECT distinct(TahunID) FROM tahun order by TahunID Desc"); //and NA='N'
			while ($k = mysqli_fetch_array($tahun)){
			  if ($_GET[tahun]==$k[TahunID]){
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
		echo "<option value=''>- Pilih Program Studi -</option>";
		$prodi = mysqli_query($koneksi, "SELECT * from prodi order by Nama ASC");
		while ($k = mysqli_fetch_array($prodi)){
		   if ($_GET[prodi]==$k[ProdiID]){
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
</div> 


<div class="col-md-2">
<?php 
 if ($_GET[prodi] == '' OR $_GET[tahun] == '' ){ ?>
     <?php echo"Upps Mas Ir!";?>
 <?php
 }else{
?>	     
	<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=dep/mbkm&act=tambahdata&tahun=<?php echo $_GET[tahun]; ?>&prodi=<?php echo "$_GET[prodi]"; ?>&program=<?php echo 	"$_GET[program]"; ?>'>Tambahkan Data</a>
<?php 
} 
?>
</div> 


</form>
</div>
</div>
</div>

<?php if ($_GET[act]==''){ ?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
        <table id="example1" class="table table-sm table-striped">
          <thead>
            <tr style=text-align:left;font-size:14px;color:white;font-weight:reguler;background-color:purple;>
              <th style='width:20px;text-align:center'>No</th>             
              <th style='width:70px;text-align:center'>NIM</th>
              <th style='width:150px'>Nama Mahasiswa</th>
               <th style='width:80px'>Handphone</th>
			    <th style='text-align:left'>Program MBKM</th>
			    <th style='text-align:left'>Instansi <br>Penyelenggara</th>
			    <th style='text-align:center'>Tempat <br>Pelaksanaan</th>
			  <th style='text-align:center'>Waktu <br>Pelaksanaan</th>
			  <th style='text-align:center'>Konversi</th>
			  <th style='width:70px;text-align:center'>Aksi</th>
            </tr>
          </thead>
          <tbody>
        <?php
            if ($_GET[tahun]!='' AND $_GET[prodi]!=''){
			 $tampil = mysqli_query($koneksi, "select mhsw.Nama, mhsw.ProdiID,mhsw.Handphone, t_mbkm.* 
			                        from mhsw, t_mbkm 
			                        where mhsw.MhswID= t_mbkm.MhswID
			                        and t_mbkm.TahunID='".strfilter($_GET[tahun])."'
			                        and mhsw.ProdiID='".strfilter($_GET[prodi])."'");
		 	}
		 	else if ($_GET[tahun]!='' AND $_GET[prodi]==''){
			 $tampil = mysqli_query($koneksi, "select mhsw.Nama, mhsw.ProdiID,mhsw.Handphone, t_mbkm.* 
			                        from mhsw, t_mbkm 
			                        where mhsw.MhswID= t_mbkm.MhswID
			                        and t_mbkm.TahunID='".strfilter($_GET[tahun])."'");
		  	}
		  	else if ($_GET[tahun]=='' AND $_GET[prodi]!==''){
		     $tampil = mysqli_query($koneksi, "select mhsw.Nama, mhsw.ProdiID,mhsw.Handphone, t_mbkm.* 
		                             from mhsw, t_mbkm 
			                        where mhsw.MhswID= t_mbkm.MhswID
			                        and mhsw.ProdiID='".strfilter($_GET[prodi])."'");
		    }else{
		     $tampil = mysqli_query($koneksi, "select mhsw.Nama, mhsw.ProdiID,mhsw.Handphone, t_mbkm.* 
		                            from mhsw, t_mbkm 
			                        where mhsw.MhswID= t_mbkm.MhswID");  
		    }	                        
		  $no = 1;
          while($r=mysqli_fetch_array($tampil)){  
          $jnsb = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM t_program_mbkm where ProgramMBKMID='$r[ProgramMBKMID]'"));
          $NamaProgramx = strtolower($jnsb[Nama]); //strtoupper($kalimat);
          $NamaProgram	= ucwords($NamaProgramx);
          $NamaMhsx = strtolower($r[Nama]); //strtoupper($kalimat);
          $NamaMhs	= ucwords($NamaMhsx);
          echo "<tr><td style='text-align:center'>$no</td>
                    <td style='text-align:center'>$r[MhswID]</td>
                    <td>$NamaMhs</td>
                    <td>$r[Handphone]</td>
                    <td style='text-align:left'>$NamaProgram</td>
                    <td style='text-align:left'>$r[InstansiPenyelenggara]</td>
                    <td style='text-align:left'>$r[TempatPelaksanaan]</td>
                    <td style='text-align:center'>$r[WaktuPelaksanaan]</td>
                    <td style='text-align:center'><a href=''>Konversi Matakuliah</a></td>";
		echo "<td style='width:70px !important'><center><a class='btn btn-success btn-xs' title='Edit Data' href='index.php?ndelox=dep/mbkm&act=editdata&id=$r[MBKMID]&program=$r[ProgramID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'><i class='fa fa-edit'></i></a>
		<a class='btn btn-danger btn-xs' title='Hapus Data' href='index.php?ndelox=dep/mbkm&hapus=$r[MBKMID]&program=$r[ProgramID]&prodi=$r[ProdiID]&tahun=$r[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
		</center></td>";

echo "</tr>";
	  $no++;
	  }

	  if (isset($_GET[hapus])){
		mysqli_query($koneksi, "DELETE FROM t_mbkm where MBKMID='".strfilter($_GET[hapus])."'");
		echo "<script>document.location='index.php?ndelox=dep/mbkm&program=$_GET[program]&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
	  }
  ?>
	<tbody>
  </table></div>
</div>
</div>

<?php 
}elseif($_GET[act]=='tambahdata'){
    if (isset($_POST[tambah])){	
	    $tglnow =date('Y-m-d H:i:s');	
        $query = mysqli_query($koneksi, "INSERT INTO t_mbkm(
							TahunID,
							KodeID,
						    ProgramMBKMID,
							MhswID,
							InstansiPenyelenggara,
							TempatPelaksanaan,
							WaktuPelaksanaan,
							TanggalBuat,
							LoginBuat,
							NA,
							Keterangan) 
					 VALUES('".strfilter($_POST[tahun])."',
					 	    'SISFO',
							'".strfilter($_POST[ProgramMBKMID])."',
							'".strfilter($_POST[MhswID])."',
							'".strfilter($_POST[InstansiPenyelenggara])."',
							'".strfilter($_POST[TempatPelaksanaan])."',
						    '".strfilter($_POST[WaktuPelaksanaan])."',
							'$tglnow',
							'$_SESSION[id]',
							'N',
							'".strfilter($_POST[Keterangan])."')");
		
        if ($query){
            //echo"Berhasil";
			echo "<script>document.location='index.php?ndelox=dep/mbkm&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&sukses';</script>";
        }else{
            echo "<script>document.location='index.php?ndelox=dep/mbkm&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&gagal';</script>";
        }
		
    }

    //$ex = explode('.',$_GET[kelas]);
    //$tingkat = $ex[0];

    echo "


              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
			  <div class='card'>
			  <div class='card-header'>
			  
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Data Program MBKM</h3>
                </div>
			  <div class='table-responsive'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th style='width:260px' scope='row'>Tahun Akademik</th>   <td><select class='form-control' name='tahun'> 
						<option value='0' selected>- Pilih Tahun Akademik -</option>"; 
						$tahun = mysqli_query($koneksi, "SELECT distinct(TahunID) FROM tahun order by TahunID Desc ");
						while($a = mysqli_fetch_array($tahun)){
						  if ($_GET[tahun]==$a[TahunID]){
							echo "<option value='$a[TahunID]' selected>$a[TahunID]</option>";
						  }else{
							echo "<option value='$a[TahunID]'>$a[TahunID]</option>";
						  }
						}
						echo "</select>
                    </td></tr>
                    
                    <tr><th style='width:260px' scope='row'>Program Studi</th>   <td><select class='form-control' name='prodi'> 
						<option value='0' selected>- Pilih Program Studi -</option>"; 
						$tahun = mysqli_query($koneksi, "SELECT distinct(ProdiID),NA,Nama FROM prodi ");
						while($a = mysqli_fetch_array($tahun)){
						  if ($_GET[prodi]==$a[ProdiID]){
							echo "<option value='$a[ProdiID]' selected>$a[Nama]</option>";
						  }else{
							echo "<option value='$a[ProdiID]'>$a[Nama]</option>";
						  }
						}
						echo "</select>
                    </td></tr>
                    

				
										
                    <tr><th scope='row'>Mahasiswa</th>   <td><select class='form-control select2' name='MhswID'> 
						<option value='$a[MhswID]' selected>- Cari Mahasiswa -</option>"; 
						if ($_GET[prodi]=='SI'){
						    $mt = mysqli_query($koneksi, "SELECT * FROM mhsw where ProdiID='$_GET[prodi]'"); // AND StatusMhswID='A'
						}else{
							$mt = mysqli_query($koneksi, "SELECT * FROM mhsw where ProdiID='$_GET[prodi]'"); // AND StatusMhswID='A'
						}
						while($a = mysqli_fetch_array($mt)){
						  echo "<option value='$a[MhswID]'>$a[MhswID] - $a[Nama]</option>";
						}
						echo "</select>
                    </td></tr>
                    
                    <tr><th style='width:260px' scope='row'>Program MBKM</th>   <td><select class='form-control' name='ProgramMBKMID' required> 
						<option value='' selected>- Pilih Program MBKM -</option>"; 
						$jb = mysqli_query($koneksi, "SELECT * FROM t_program_mbkm ");
						while($a = mysqli_fetch_array($jb)){
							echo "<option value='$a[ProgramMBKMID]'>$a[Nama] - ($a[ProgramMBKMID])</option>";
						}
						echo "</select>
                    </td></tr>
				
  
                    <tr><th scope='row'>Instansi Penyelenggara</th>  <td><input type='text'  class='form-control' name='InstansiPenyelenggara'></td></tr>
                     <tr><th scope='row'>Tempat Pelaksanaan</th>  <td><input type='text'  class='form-control' name='TempatPelaksanaan'></td></tr>
                      <tr><th scope='row'>Waktu Pelaksanaan</th>  <td><input type='text'  class='form-control' name='WaktuPelaksanaan'></td></tr>
                    <tr><th scope='row'>Keterangan</th>  <td><input type='text' style='width:100%' class='form-control' name='Keterangan'></td></tr>
                  </tbody>
                  </table>
				  <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?ndelox=dep/mbkm&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
				  </div>
				  </div>
				  </div>
              
              </form>
            </div>";
}

elseif($_GET[act]=='editdata'){
    if (isset($_POST[update])){
        $query = mysqli_query($koneksi, "UPDATE t_mbkm SET 
							  TahunID 		= '".strfilter($_POST[tahun])."',
							  ProgramMBKMID 	= '".strfilter($_POST[ProgramMBKMID])."',  
							  InstansiPenyelenggara 		= '".strfilter($_POST[InstansiPenyelenggara])."',
							  TempatPelaksanaan 		= '".strfilter($_POST[TempatPelaksanaan])."',
							  WaktuPelaksanaan 		= '".strfilter($_POST[WaktuPelaksanaan])."',
							  Keterangan 	= '".strfilter($_POST[Keterangan])."',
							  LoginEdit 	= 	'$_SESSION[id]',                                                   
							  TanggalEdit 	= '".date('Y-m-d H:i:s')."'
							  WHERE MBKMID	= '".strfilter($_POST[id])."'");
        if ($query){
          echo "<script>document.location='index.php?ndelox=dep/mbkm&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&xx=$_POST[JadwalID]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=dep/mbkm&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&xx=$_POST[JadwalID]&gagal';</script>";
        }
    }
    
    $e = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM t_mbkm where MBKMID='".strfilter($_GET[id])."'"));
    $mhsx = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mhsw where MhswID='$e[MhswID]'"));
    $prd = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM prodi where ProdiID='$mhsx[ProdiID]'"));
    echo "         


              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
			  <div class='card'>
			  <div class='card-header'>
			                <div class='box-header with-border'>
              <h3 class='box-title'>Edit Data Program MBKM</h3>
              </div>
			  <div class='table-responsive'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                  <input type='hidden' name='id' value='$_GET[id]'>
                   <input type='hidden' name='prodi' value='$prd[ProdiID]'>
				   <tr><th style='width:260px' scope='row'>Tahun Akademik</th>   <td><select class='form-control' name='tahun'> 
						<option value='0' selected>- Pilih Tahun Akademik -</option>"; 
						$tahun = mysqli_query($koneksi, "SELECT distinct(TahunID) FROM tahun order by TahunID Desc");
						while($a = mysqli_fetch_array($tahun)){
						  if ($_GET[tahun]==$a[TahunID]){
							echo "<option value='$a[TahunID]' selected>$a[TahunID]</option>";
						  }else{
							echo "<option value='$a[TahunID]'>$a[TahunID]</option>";
						  }
						}
						echo "</select>
                    </td></tr>
                    
               
										
                    <tr><th style='width:260px' scope='row'>Mahasiswa</th>   <td><select class='form-control select2' name='xxx'> 
						<option value='0' selected>- Mahasiswa -</option>"; 
						$jb = mysqli_query($koneksi, "SELECT * FROM mhsw where ProdiID='".strfilter($_GET['prodi'])."'");
						while($a = mysqli_fetch_array($jb)){
                        if ($e[MhswID]==$a[MhswID]){
							echo "<option value='$a[MhswID]' selected>$a[MhswID] - $a[Nama]</option>";
						  }else{
							echo "<option value='$a[MhswID]'>$a[MhswID] - $a[Nama]</option>";
						  }
						}
						echo "</select>
                    </td></tr>
                    
                    <tr><th style='width:260px' scope='row'>Jenis Program MBKM</th>   <td><select class='form-control' name='ProgramMBKMID'> 
						<option value='0' selected>- Pilih Jenis Program MBKM -</option>"; 
						$jb = mysqli_query($koneksi, "SELECT * FROM t_program_mbkm ");
						while($a = mysqli_fetch_array($jb)){
                        if ($e[ProgramMBKMID]==$a[ProgramMBKMID]){
							echo "<option value='$a[ProgramMBKMID]' selected>$a[Nama]  - ($a[ProgramMBKMID])</option>";
						  }else{
							echo "<option value='$a[ProgramMBKMID]'>$a[Nama]  - ($a[ProgramMBKMID])</option>";
						  }
						}
						echo "</select>
                    </td></tr>
				
                    <tr><th scope='row'>Instansi Penyelenggara</th>  <td><input type='text' style='width:100%' class='form-control' name='InstansiPenyelenggara' value='$e[InstansiPenyelenggara]'></td></tr>
                    <tr><th scope='row'>Tempat Pelaksanaan</th>  <td><input type='text' style='width:100%' class='form-control' name='TempatPelaksanaan' value='$e[TempatPelaksanaan]'></td></tr>
                    <tr><th scope='row'>Waktu Pelaksanaan</th>  <td><input type='text' style='width:100%' class='form-control' name='WaktuPelaksanaan' value='$e[WaktuPelaksanaan]'></td></tr>
                    <tr><th scope='row'>Keterangan</th>  <td><input type='text' style='width:100%' class='form-control' name='Keterangan' value='$e[Keterangan]'></td></tr>
                  </tbody>
                  </table>
				   <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='index.php?ndelox=dep/mbkm&tahun=$_GET[tahun]&prodi=$prd[ProdiID]&program=$_GET[program]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
				  </div>
				  </div>
				  </div>
             
              </form>
            </div>";
}

?>