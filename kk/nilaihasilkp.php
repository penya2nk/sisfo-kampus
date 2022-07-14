<div class='card'>
<div class='card-header'>
<h3 class="box-title">
<?php 
  if (isset($_GET['tahun'])){ 
     echo "<b style='color:green;font-size:20px'>Nilai Seminar Hasil Kerja Praktek</b>"; 
  }else{ 
     echo "<b style='color:green;font-size:20px'>Nilai Seminar Hasil Kerja Praktek </b>".date('Y'); 
  } ?>
</h3>


<div class="form-group row">
<label class="col-md-5 col-form-label text-md-left"><b style='color:purple'>Proses Nilai Hasil KP</b></label>

<div class="col-md-2 ">
                  
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='ndelox' value='kk/nilaihasilkp'>
                    <select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
                        <?php 
                            echo "<option value=''>- Pilih Tahun Akademik -</option>";
                            $tahun = mysqli_query($koneksi,  "SELECT distinct(TahunID) FROM tahun order by TahunID Desc"); //and NA='N'
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
                 <!--  <select name='program' style='padding:4px'>
                        <?php 
                            echo "<option value=''>- Pilih Program -</option>";
                            $program = mysqli_query($koneksi,  "SELECT * FROM program");
                            while ($k = mysqli_fetch_array($program)){
                             if ($_GET['program']==$k['ProgramID']){
                                echo "<option value='$k[ProgramID]' selected>$k[ProgramID] - $k[Nama]</option>";
                              }else{
                                echo "<option value='$k[ProgramID]'>$k[ProgramID] - $k[Nama] </option>";
                              }
                            }
                        ?>
                    </select> -->
                    <select name='prodi' class='form-control form-control-sm' onChange='this.form.submit()'>
                        <?php 
                            echo "<option value=''>- Pilih Program Studi -</option>";
                            $prodi = mysqli_query($koneksi,  "SELECT * from prodi order by Nama ASC");
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
                  </form>
				  </div>
<div class="col-md-2">	
	<?php 
 if ($_GET['prodi'] == '' OR $_GET['tahun'] == '' ){ ?>
     <?php echo"Upps Mas Ir!";?>
 <?php
 }else{
?>	     
	<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=kk/hasilkp&tahun=<?php echo $_GET['tahun']; ?>&prodi=<?php echo "$_GET[prodi]"; ?>&program=<?php echo 	"$_GET[program]"; ?>'>JADWAL SEMINAR HASIL</a>
<?php } ?>

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
        <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
		<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
        <table id="example" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th style='width:20px'>No</th>
              <th style='width:100px'>NIM</th>  
              <th style='width:250px'>Nama</th>
              <th style='width:60px'>Nilai Penguji1</th>  
              <th style='width:60px'>Nilai Penguji2</th> 
              <th style='width:60px'>Nilai Penguji3</th>                                        
            
            </tr>
          </thead>
          <tbody>
        <?php
          if (isset($_GET['tahun'])){			    
			  echo"<input type='hidden' name='program' value='$_GET[program]'>
			  <input type='hidden' name='prodi' value='$_GET[prodi]'>
			  <input type='hidden' name='tahun' value='$_GET[tahun]'>";

			  $hd = 0;
			  $no = 1;
			  $qrs = mysqli_query($koneksi,  "SELECT * from vw_headerkp where TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."'  order by TglMulaiSidang desc");//and ProgramID like '%$_GET[program]%'
			  while ($h = mysqli_fetch_array($qrs)){
				   $p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from dosen where Login='$h[Penguji1]'"));
				   $p2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from dosen where Login='$h[Penguji2]'"));
				   $p3 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from dosen where Login='$h[Penguji3]'"));	 
				   
				   $p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from dosen where Login='$h[Penguji1]'"));
                    $Penguji1x 	= strtolower($p1['Nama']);
                    $Penguji1	= ucwords($Penguji1x);
                    
                    $p2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from dosen where Login='$h[Penguji2]'"));
                    $Penguji2x 	= strtolower($p2['Nama']);
                    $Penguji2	= ucwords($Penguji2x);
                    
                    $p3 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from dosen where Login='$h[Penguji3]'"));	  
                    $Penguji3x 	= strtolower($p3['Nama']);
                    $Penguji3	= ucwords($Penguji3x);
				   $hd++;
			  echo"<tr style='background-color:#DFF4FF'>
				 <td colspan='11' height='20'><b>&nbsp; $hd. KLP: $h[KelompokID] [ $h[Nama] ] - $h[Judul] <p align=right>(P1: $Penguji1, P2: $Penguji2, P3: $Penguji3)</p></b></td>
				 </tr>"; 
				           
			  
			  $tampil = mysqli_query($koneksi,  "SELECT * from vw_jadwalkp_anggota where KelompokID='$h[KelompokID]' and TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."' order by TglMulaiSidang desc");  //KelompokID='$h[KelompokID]' AND     / <u><b>[$r[KelompokID]]</b></u>             		 
			  while($r=mysqli_fetch_array($tampil)){			 			 
			  echo "<tr><td>$no</td>   
					<td>$r[MhswID]</td>  
					<td>$r[Nama] </td>                                                 									
					<input type='hidden' value='$r[MhswID]' name='MhswID".$no."'>					
					<td ><input type='text' style='width:90px; text-align:center; padding:0px' maxlength='3' name='NilaiPengujiSidang1".$no."' value='$r[NilaiPengujiSidang1]'></td>
					<td><input type='text' style='width:90px; text-align:center; padding:0px' maxlength='3' name='NilaiPengujiSidang2".$no."' value='$r[NilaiPengujiSidang2]'></td>
					<td><input type='text' style='width:90px; text-align:center; padding:0px' maxlength='3' name='NilaiPengujiSidang3".$no."' value='$r[NilaiPengujiSidang3]'></td>								
					</tr>";
                    $no++;
              }
			  }
              ?>
              <tbody>
              </table>
              <div class='box-footer'>
              <button type='submit' name='simpannilai' class='btn btn-info pull-right'>Perbaharui Nilai</button>                      
              </div>
              </form>
              </div><!-- /.box-body --> 
              </div>
            </div>
            
            <?php
             if (isset($_POST['simpannilai'])){ 			 	
				//$jml_data 				= count($_POST[MhswID]);
				$jml_data 				= mysqli_num_rows(mysqli_query($koneksi,  "SELECT MhswID FROM vw_jadwalkp_anggota where ProdiID='".strfilter($_GET['prodi'])."' AND TahunID='".strfilter($_GET['tahun'])."'"));//prihan 	                
				for ($i=1; $i <= $jml_data; $i++){
				$MhswID 				= strfilter($_POST['MhswID'.$i]);
			    $tahun 					= strfilter($_POST['tahun'.$i]);
				$NilaiPengujiSidang1 	= strfilter($_POST['NilaiPengujiSidang1'.$i]);
				$NilaiPengujiSidang2 	= strfilter($_POST['NilaiPengujiSidang2'.$i]);
				$NilaiPengujiSidang3 	= strfilter($_POST['NilaiPengujiSidang3'.$i]);
				$cek = mysqli_query($koneksi,  "SELECT * FROM jadwal_kp_anggota where MhswID='$MhswID'"); //JadwalID='$_POST[JadwalID]' AND 
				$total = mysqli_num_rows($cek);
					if ($total >= 1){
						$query = mysqli_query($koneksi, "UPDATE jadwal_kp_anggota SET 
										  NilaiPengujiSidang1	='$NilaiPengujiSidang1',
										  NilaiPengujiSidang2	='$NilaiPengujiSidang2',
										  NilaiPengujiSidang3	='$NilaiPengujiSidang3'
										  WHERE MhswID		 	='$MhswID'");   //AND JadwalID='$_POST[JadwalID]'                     
					}			  
				}			
			  if ($query){
				  echo "<script>document.location='index.php?ndelox=kk/nilaihasilkp&JadwalID=".$_POST['JadwalID']."&program=".$_POST['program']."&prodi=".$_POST['prodi']."&tahun=".$_POST['tahun']."&sukses';</script>";
			  }else{
				  echo "<script>document.location='index.php?ndelox=kk/nilaihasilkp&JadwalID=".$_POST['JadwalID']."&program=".$_POST['program']."&prodi=".$_POST['prodi']."&tahun=".$_POST['tahun']."gagal';</script>";
			  }  
			  
		    }
		 
	  }
}
?>