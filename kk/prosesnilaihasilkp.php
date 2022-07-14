<div class="card">
<div class="card-header">
<div class="card-tools">
<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=kk/hasilkp&tahun=<?php echo $_GET['tahun']; ?>&prodi=<?php echo "$_GET[prodi]"; ?>&program=<?php echo 	"$_GET[program]"; ?>'>Input Nilai</a>
</div>    

<div class="form-group row">
<label class="col-md-7 col-form-label text-md-left"><b style='color:purple'>Proses Nilai Hasil KP</b></label>

<div class="col-md-2 ">
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='kk/prosesnilaihasilkp'>
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
	$prodi = mysqli_query($koneksi, "SELECT * from prodi where ProdiID='SI' or ProdiID='TI'");
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
	$sopo=mysqli_fetch_array(mysqli_query($koneksi, "select MhswID,Nama from mhsw where MhswID='".strfilter($_GET['MhswID'])."'"));
    echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data $_GET[MhswID]-$sopo[Nama] telah Berhasil Di Proses ..
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
<table id="example" class="table table-sm table-striped">
  <thead>
    <tr>
      <th style='width:20px'>No</th>
      <th style='width:80px'>NIM</th>  
      <th style='width:250px'>Nama</th>
      <th style='width:60px'>Nilai Penguji1</th>  
      <th style='width:60px'>Nilai Penguji2</th> 
      <th style='width:60px'>Nilai Penguji3</th>                                        
      <th style='width:60px'> Angka</th> 
      <th style='width:60px'>Huruf</th>
      <th style='width:60px'>Proses</th> 
    </tr>
  </thead>
  <tbody>
<?php
if (isset($_GET['tahun'])){
	if ($_GET['prodi']=='SI'){
		$MKID='1150'; //1150 1018
		}else{
	    $MKID='1208'; //1150
	}		    
echo"
<input type='hidden' name='prodi' value='$_GET[prodi]'>
<input type='hidden' name='tahun' value='$_GET[tahun]'>";

$no = 1;
$tampil = mysqli_query($koneksi, "SELECT * from vw_jadwalkp_seminarhasilanggota 
						where TahunID='".strfilter($_GET['tahun'])."' 
						AND ProdiID='".strfilter($_GET['prodi'])."' 					
						order by MhswID asc");  //KelompokID='$h[KelompokID]'    AND NA NOT IN('Y','')       		 
while($r=mysqli_fetch_array($tampil)){
if ($r['NA']=='Y'){
	$c="style=color:#666666";
}else{
	$c="style=color:#FF6702";
}	
	$nilai = number_format(($r['NilaiPengujiSidang1'] + $r['NilaiPengujiSidang2'] + $r['NilaiPengujiSidang3'])/3,0);
	if ($nilai >= 85 AND $nilai <= 100){
						$huruf = "A";
						$bobot = "4";
	}
	elseif ($nilai >= 80 AND $nilai <= 84.99){
		$huruf = "A-";
		$bobot = "3.70";
	}
	elseif ($nilai >= 75 AND $nilai <= 79.99){
		$huruf = "B+";
		$bobot = "3.30";
	}
	elseif ($nilai >= 70 AND $nilai <= 74.99){
		$huruf = "B";
		$bobot = "3";
	}
	elseif ($nilai >= 65 AND $nilai <= 69.99){
		$huruf = "B-";
		$bobot = "2.70";
	}
	elseif ($nilai >= 60 AND $nilai <= 64.99){
		$huruf = "C+";
		$bobot = "2.30";
	}
	elseif ($nilai >= 55 AND $nilai <= 59.99){
		$huruf = "C";
		$bobot = "2";
	}
	elseif ($nilai >= 50 AND $nilai <= 54.99){
		$huruf = "C-";
		$bobot = "1.70";
	}
	elseif ($nilai >= 40 AND $nilai <= 49.99){
		$huruf = "D";
		$bobot = "1";
	}
	elseif ($nilai < 40){
		$huruf = "E";
		$bobot = "0";
	}	 
	echo "<tr $c>
	<td $c>$no</td>   
	<td $c>$r[MhswID]</td>  
	<td $c><a href='?ndelox=kk/prosesnilaihasilkp&valid&MID=$r[MID]&tahun=$_GET[tahun]&prodi=$_GET[prodi]&MhswID=$r[MhswID]&MKID=$MKID&nilai=$nilai&huruf=$huruf&bobot=$bobot'>$r[Nama] </a></td>                                                 														
	<td $c>$r[NilaiPengujiSidang1]</td>
	<td $c>$r[NilaiPengujiSidang2]</td>
	<td $c>$r[NilaiPengujiSidang3]</td>
	<td $c>$nilai</td>
	<td $c>$huruf</td>
	<td $c><a href='?ndelox=kk/prosesnilaihasilkp&valid&MID=$r[MID]&tahun=$_GET[tahun]&prodi=$_GET[prodi]&MhswID=$r[MhswID]&MKID=$MKID&nilai=$nilai&huruf=$huruf&bobot=$bobot'>Validasi</a></td>								
	</tr>";
	$no++;
	}
}//tutup header
?>
<tbody>
</table>
</div>
<div class='box-footer'>                     
</div>
</form>
</div><!-- /.box-body --> 
</div>
</div>            
<?php   

if (isset($_GET['valid'])){
	
   mysqli_query($koneksi, "UPDATE jadwal_kp_anggota set NA='Y' WHERE MID='".strfilter($_GET['MID'])."'");
   if ($_GET['nilai'] >= 85 AND $_GET['nilai'] <= 100){
		$huruf = "A";
		$bobot = "4";
	}
	elseif ($_GET['nilai'] >= 80 AND $_GET['nilai'] <= 84.99){
		$huruf = "A-";
		$bobot = "3.70";
	}
	elseif ($_GET['nilai'] >= 75 AND $_GET['nilai'] <= 79.99){
		$huruf = "B+";
		$bobot = "3.30";
	}
	elseif ($_GET['nilai'] >= 70 AND $_GET['nilai'] <= 74.99){
		$huruf = "B";
		$bobot = "3";
	}
	elseif ($_GET['nilai'] >= 65 AND $_GET['nilai'] <= 69.99){
		$huruf = "B-";
		$bobot = "2.70";
	}
	elseif ($_GET['nilai'] >= 60 AND $_GET['nilai'] <= 64.99){
		$huruf = "C+";
		$bobot = "2.30";
	}
	elseif ($_GET['nilai'] >= 55 AND $_GET['nilai'] <= 59.99){
		$huruf = "C";
		$bobot = "2";
	}
	elseif ($_GET['nilai'] >= 50 AND $_GET['nilai'] <= 54.99){
		$huruf = "C-";
		$bobot = "1.70";
	}
	elseif ($_GET['nilai'] >= 40 AND $_GET['nilai'] <= 49.99){
		$huruf = "D";
		$bobot = "1";
	}
	elseif ($_GET['nilai'] < 40){
		$huruf = "E";
		$bobot = "0";
	}		
	
// echo "<script language='javascript'>alert('Pengisian $huruf ');
// 		window.location = 'index.php?ndelox=kk/prosesnilaihasilkp&prodi=".$_GET[prodi]."&tahun=".$_GET[tahun]."&MhswID=$_GET[MhswID]'</script>";
   		mysqli_query($koneksi, "UPDATE krs set NilaiAkhir='".strfilter($_GET['nilai'])."',GradeNilai='$huruf',BobotNilai='$bobot' 
   					WHERE MhswID='".strfilter($_GET['MhswID'])."' AND MKID='".strfilter($_GET['MKID'])."' AND TahunID='".strfilter($_GET['tahun'])."'");
					echo "<script>document.location='index.php?ndelox=kk/prosesnilaihasilkp&prodi=".$_GET['prodi']."&tahun=".$_GET['tahun']."&MhswID=$_GET[MhswID]&sukses';</script>";				
}

        
}
?>