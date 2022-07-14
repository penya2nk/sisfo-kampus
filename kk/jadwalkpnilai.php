<div class="card">
<div class="card-header">
<div class="card-tools">  
<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=kk/jadwalkp&tahun=<?php echo $_GET['tahun']; ?>&prodi=<?php echo "$_GET[prodi]"; ?>'>Tambahkan Jadwal KP</a>
</div>

<div class="form-group row">
<label class="col-md-7 col-form-label text-md-left"><b style='color:purple'>Nilai Proposal Kerja Praktek</b></label>

<div class="col-md-2 ">
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='kk/jadwalkpnilai'>
<select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
    <?php 
        echo "<option value=''>- Pilih Tahun Akademik -</option>";
        $tahun = mysqli_query($koneksi, "SELECT distinct(TahunID) FROM tahun order by TahunID Desc"); //and NA='N'
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
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
<table id="example" class="table table-sm table-bordered table-striped">
        <table id="example" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th style='width:20px'>No</th>
              <th style='width:100px'>NIM</th>  
              <th style='width:100px'>Nama</th>
              <th style='width:100px'>Nilai Penguji1</th>  
              <th style='width:100px'>Nilai Penguji2</th> 
              <th style='width:100px'>Nilai Penguji3</th>                                        
            
            </tr>
          </thead>
          <tbody>
        <?php
          if (isset($_GET['tahun'])){
			    
				echo"		
				<input type='hidden' name='prodi' value='$_GET[prodi]'>
				<input type='hidden' name='tahun' value='$_GET[tahun]'>";
		  			
			  $hd = 0;
			  $no = 1;
			  $qrs = mysqli_query($koneksi, "SELECT * from vw_headerkp where TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."'  order by TglMulaiSidang desc");//and ProgramID like '%$_GET[program]%'
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
				           			  
			  $tampil = mysqli_query($koneksi, "SELECT * from vw_jadwalkp_anggota where KelompokID='$h[KelompokID]' and TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."' order by TglMulaiSidang desc");  //KelompokID='$h[KelompokID]' AND     / <u><b>[$r[KelompokID]]</b></u>             		 
			  while($r=mysqli_fetch_array($tampil)){			 			 
			  echo "<tr><td>$no</td>   
					<td>$r[MhswID]</td>  
					<td>$r[Nama] </td>                                                 									
					<input type='hidden' value='$r[MhswID]' name='MhswID".$no."'>					
					<td><input type='text' style='width:90px; text-align:center; padding:0px' maxlength='3' name='NilaiPengujiPro1".$no."' value='$r[NilaiPengujiPro1]'></td>
					<td><input type='text' style='width:90px; text-align:center; padding:0px' maxlength='3' name='NilaiPengujiPro2".$no."' value='$r[NilaiPengujiPro2]'></td>
					<td><input type='text' style='width:90px; text-align:center; padding:0px' maxlength='3' name='NilaiPengujiPro3".$no."' value='$r[NilaiPengujiPro3]'></td>								
					</tr>";
                    $no++;
              }
			  }
              ?>
              <tbody>
              </table>
              </div>
              <div class='box-footer'>
              <button type='submit' name='simpannilai' class='btn btn-info pull-right'>Perbaharui Nilai</button>                      
              </div>
              </form>
              </div><!-- /.box-body --> 
              </div>
            </div>
            
            <?php
            if (isset($_POST['simpannilai'])){ 			 	
				//$jml_data 			= count($_POST[MhswID]);
				$jml_data 			= mysqli_num_rows(mysqli_query($koneksi, "SELECT MhswID FROM vw_jadwalkp_anggota where ProdiID='".strfilter($_GET['prodi'])."' AND TahunID='".strfilter($_GET['tahun'])."'"));//prihan             
				for ($i=1; $i <= $jml_data; $i++){
					$tahun 				= strfilter($_POST['tahun']);	
					$MhswID 			= strfilter($_POST['MhswID'.$i]);
					$NilaiPengujiPro1 	= strfilter($_POST['NilaiPengujiPro1'.$i]);
					$NilaiPengujiPro2 	= strfilter($_POST['NilaiPengujiPro2'.$i]);
					$NilaiPengujiPro3 	= strfilter($_POST['NilaiPengujiPro3'.$i]);	
					$cek = mysqli_query($koneksi, "SELECT * FROM jadwal_kp_anggota where MhswID='$MhswID'"); //JadwalID='$_POST[JadwalID]' AND 
					$total = mysqli_num_rows($cek);
					if ($total >= 1){
						$query = mysqli_query($koneksi, "UPDATE jadwal_kp_anggota SET 
										  NilaiPengujiPro1	='$NilaiPengujiPro1',
										  NilaiPengujiPro2	='$NilaiPengujiPro2',
										  NilaiPengujiPro3	='$NilaiPengujiPro3'
										  WHERE MhswID		='$MhswID'");//  AND JadwalID='$_POST[JadwalID]'                     
					}
			  
				}
			
				if ($query){
					echo "<script>document.location='index.php?ndelox=kk/jadwalkpnilai&JadwalID=".strfilter($_POST['JadwalID'])."&program=".strfilter($_POST['program'])."&prodi=".strfilter($_POST['prodi'])."&tahun=".strfilter($_POST['tahun'])."&sukses';</script>";
				}else{
					echo "<script>document.location='index.php?ndelox=kk/jadwalkpnilai&JadwalID=".strfilter($_POST['JadwalID'])."&program=".strfilter($_POST['program'])."&prodi=".strfilter($_POST['prodi'])."&tahun=".strfilter($_POST['tahun'])."&gagal';</script>";
				}  
		  
			}
		 
	  }

}
?>