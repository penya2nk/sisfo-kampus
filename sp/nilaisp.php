<div class='card'>
<div class='card-header'>

<div class="form-group row">
<label class="col-md-4 col-form-label text-md-right"><b style='color:purple'>NILAI SEMESTER PENDEK</b></label>
<div class="col-md-2">
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='nilaisp'>
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

<div class="col-md-2">
<input type="submit"  class='btn btn-success btn-sm' value='Lihat'>
</form>
</div>

<div class="col-md-2">
<?php  
echo"<a href='?ndelox=nilaisp&act=nilaispbl&prodi=$_GET[prodi]&tahun=$_GET[tahun]'><b>[ ~~ Nilai SP Belum Lengkap ~~]</b></a>";
?>
</form>
</div>


</div>
</div>
</div>


	
<?php if ($_GET['act']==''){ 
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
<table id="example" class="table table-sm table-striped">
<thead>
<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:purple;>
  <th style='width:20px'>No</th>
  <th style='width:30px'>MKID</th>
  <th style='width:30px'>MKKode</th>
  <th style='width:150px'>Matakuliah</th>
  <th style='width:200px'>Dosen Pengampu</th>                                                     
</tr>
</thead>
<tbody>
<?php
if (isset($_GET['tahun'])){ 
	echo"<input type='hidden' name='prodi' value='$_GET[prodi]'>
	<input type='hidden' name='tahun' value='$_GET[tahun]'>";
  $tampil = mysqli_query($koneksi, "SELECT distinct(MKID),MKKOde,NamaMK,DosenID,TahunID,ProdiID from vw_sp WHERE TahunID='".strfilter($_GET['tahun'])."' 
						 and ProdiID='".strfilter($_GET['prodi'])."' ");      		 
  $no=1;
  while($r=mysqli_fetch_array($tampil)){	
  	$NamaMKx 	= strtolower($r['NamaMK']); 
	$NamaMK		= ucwords($NamaMKx);  
  //$dos = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar from dosen WHERE Login='$r[DosenID]'"));
  echo "<tr><td>$no</td>
		<td>$r[MKID]</td>
		<td>$r[MKKode]</td>
		<td>$NamaMK <a href='index.php?ndelox=nilaisp&act=inputnilai&prodi=$_GET[prodi]&tahun=$_GET[tahun]&MKID=$r[MKID]'>[ Input Nilai ]</a></td>  
		<td>$dos[Nama], $dos[Gelar]</td>                                                									
		<input type='hidden' value='$r[SpID]' name='SpID[$no]'>	
		<input type='hidden' value='$r[MKID]' name='MKID[$no]'>	
		<input type='hidden' value='$r[MhswID]' name='MhswID[$no]'>																			
		</tr>";
		$no++;
  }
  ?>
  <tbody>
  </table>
  
  <div class='box-footer'>
  <button type='submit' name='simpannilai' class='btn btn-info pull-right'>Perbaharui Nilai</button>                      
  </div>


<?php
}
}

else if ($_GET['act']=='nilaispbl'){ 

?>
  

<div class='card'>
<div class='card-header'>
<b style=color:green;font-size:18px;>Data SP Prodi <?php echo"$_GET[prodi]";?> Tahun <?php echo"$_GET[tahun]";?></b>
<div class='table-responsive'>
<table id="example" class="table table-sm table-striped">
<thead>
<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:purple;>
  <th style='width:20px'>No</th>
  <th style='width:30px'>MKID</th>
  <th style='width:30px'>MKKode</th>
  
  <th style='width:150px'>Matakuliah</th> 
  <th style='width:150px'>Mahasiswa</th>
  <th style='width:150px'>Dosen Pengampu</th>
  <th style='width:100px'>Angka</th>
  <th style='width:100px'>Huruf</th>  
  <th style='width:10px'>Periode</th>  
  <th style='width:10px'>Aksi</th>
</tr>
</thead>
<tbody>
<?php
  $tampil = mysqli_query($koneksi, "SELECT * from vw_sp WHERE TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."' ORDER BY NamaMK asc");      		 
  $no=1;
  while($r=mysqli_fetch_array($tampil)){			 			 
	$NamaMKx 	= strtolower($r['NamaMK']); 
	$NamaMK		= ucwords($NamaMKx);
	$NamaMhsx 	= strtolower($r['NamaMhs']); 
	$NamaMhs	= ucwords($NamaMhsx);
	$dos = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar from dosen WHERE Login='$r[DosenID]'"));
	$NamaDosx 	= strtolower($dos[Nama]); 
	$NamaDos		= ucwords($NamaDosx);
	if ($r['NilaiAkhir']=='0.00'){
	   $c="style=color:red";
	} else{
	   $c="style=color:black";
	}
	    $nilai = $r['NilaiAkhir'];
		if ($nilai >= 85 AND $nilai <= 100){
			$huruf = "A";
			$bobot = 4;
		}
		elseif ($nilai >= 80 AND $nilai <= 84.99){
			$huruf = "A-";
			$bobot = 3.70;
		}
		elseif ($nilai >= 75 AND $nilai <= 79.99){
			$huruf = "B+";
			$bobot = 3.30;
		}
		elseif ($nilai >= 70 AND $nilai <= 74.99){
			$huruf = "B";
			$bobot = 3;
		}
		elseif ($nilai >= 65 AND $nilai <= 69.99){
			$huruf = "B-";
			$bobot = 2.70;
		}
		elseif ($nilai >= 60 AND $nilai <= 64.99){
			$huruf = "C+";
			$bobot = 2.30;
		}
		elseif ($nilai >= 55 AND $nilai <= 59.99){
			$huruf = "C";
			$bobot = 2;
		}
		elseif ($nilai >= 50 AND $nilai <= 54.99){
			$huruf = "C-";
			$bobot = 1.70;
		}
		elseif ($nilai >= 40 AND $nilai <= 49.99){
			$huruf = "D";
			$bobot = 1;
		}
		elseif ($nilai < 40){
			$huruf = "E";
			$bobot = 0;
		}
  echo "<tr $c><td>$no</td>
		<td>$r[MKID]</td>
		<td>$r[MKKode]</td>
		<td>$NamaMK</td>  
		<td>$r[MhswID] - $NamaMhs</td>
		
		<td>$NamaDos, $dos[Gelar]</td> 
		<td>$r[NilaiAkhir]</td>     
		<td>$huruf <a href='?ndelox=nilaisp&act=editnilaispbl&SpID=$r[SpID]&MhswID=$r[MhswID]&MKID=$r[MKID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'>&nbsp;&nbsp;&nbsp;[ Edit ]</a> </td>  
		<td>$r[Periode]</td> 
		<td style='width:70px !important'>
		<a class='btn btn-danger btn-xs' title='Hapus Data' href='index.php?ndelox=nilaisp&act=nilaispbl&hapus=$r[SpID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&MhswID=$r[MhswID]&Periode=$_GET[Periode]' onclick=\"return confirm('Data akan dihapus?')\"><i class='fa fa-trash'></i></a>
		</td> 		
		</tr>";
		$no++;	
  }
  
  	if (isset($_GET['hapus'])){
	$data=mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_sp where SpID='".strfilter($_GET['hapus'])."'"));
    mysqli_query($koneksi, "INSERT into t_sp_del (SpID,
												  Tanggal,
												  TahunID,
												  MhswID,
												  MKID,
												  SKS,
												  DosenID,
												  NilaiAkhir,
												  BobotNilai,
												  GradeNilai,
												  Periode,
												  LoginBuat)
										  VALUES('".strfilter($data['SpID'])."',
												 '".date('Y-m-d H:i:s')."',
												 '".strfilter($data['TahunID'])."',
												 '".strfilter($data['MhswID'])."',
												 '".strfilter($data['MKID'])."',
												 '".strfilter($data['SKS'])."',
												 '".strfilter($data['DosenID'])."',
												 '".strfilter($data['NilaiAkhir'])."',
												 '".strfilter($data['BobotNilai'])."',
												 '".strfilter($data['GradeNilai'])."',														 
												 '".strfilter($data['Periode'])."',
												 '$_SESSION[id]')");
	mysqli_query($koneksi, "DELETE FROM t_sp where SpID='".strfilter($_GET['hapus'])."'");
	echo "<script>document.location='index.php?ndelox=nilaisp&act=nilaispbl&prodi=$_GET[prodi]&tahun=$_GET[tahun]&MhswID=$_GET[MhswID]&Periode=$_GET[Periode]';</script>";
	}	
  ?>
  <tbody>
  </table></div>
</div>
</div>
  <div class='box-footer'>
                    
  </div>

<?php
}

else if ($_GET['act']=='editnilaispbl'){ 								
	 if (isset($_POST['ubahnilai'])){	
	    $tglnow =date('Y-m-d H:i:s');	
		$nilai = strfilter($_POST['NilaiAkhir']);
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
		//mengambil MKID untuk disimpan  
	    $m = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MKID,Nama,MKKode,SKS from mk where MKID='".strfilter($_POST['MKID'])."'"));
        $query = mysqli_query($koneksi, "UPDATE t_sp SET
							 NilaiAkhir ='$nilai',
							 GradeNilai	='$huruf',
							 BobotNilai	='$bobot',
							 LoginEdit  ='$_SESSION[_Login]',
							 TanggalEdit='".date('Y-m-d H:i:s')."'
							 WHERE SpID='".strfilter($_POST['SpID'])."'");						 
        if ($query){
			echo "<script>document.location='index.php?ndelox=nilaisp&act=nilaispbl&tahun=$_POST[tahun]&MhswID=$_POST[MhswID]&prodi=$_POST[prodi]&sukses';</script>";
        }else{
            echo "<script>document.location='index.php?ndelox=nilaisp&act=nilaispbl&tahun=$_POST[tahun]&MhswID=$_POST[MhswID]&prodi=$_POST[prodi]&gagal';</script>";
        }
		
    }
	$x = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_sp where SpID='".strfilter($_GET['SpID'])."'"));
    $s = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MKID,Nama,MKKode,SKS from mk where MKID='".strfilter($_GET['MKID'])."'"));
	$n = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MhswID,Nama,ProdiID from mhsw where MhswID='".strfilter($_GET['MhswID'])."'"));
	$d = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar from dosen where Login='".strfilter($x['DosenID'])."'"));
    echo "



	</div>

	  <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
	   <input type='hidden' name='SpID' value='$_GET[SpID]'>
	   <input type='hidden' name='MKID' value='$_GET[MKID]'>
	   <input type='hidden' name='MhswID' value='$_GET[MhswID]'>
		<input type='hidden' name='tahun' value='$_GET[tahun]'>
		<input type='hidden' name='program' value='$_GET[program]'>
		<input type='hidden' name='prodi' value='$_GET[prodi]'>
		<div class='card'>
		<div class='card-header'>
		<h3 class='box-title'><b style=color:green>Update Nilai</b></h3>
		<div class='table-responsive'>
		  <table class='table table-sm table-bordered'>
		  <tbody>	
		    <tr><th scope='row' width='260px'>Nama Mahasiswa</th>  <td><input type='text' maxlength='3' class='form-control' name='xx' value='$n[Nama]' readonly></td></tr> 
            <tr><th scope='row'>Nama Matakuliah</th>  <td><input type='text' maxlength='3' class='form-control' name='xx' value='$s[Nama]' readonly></td></tr> 
			<tr><th scope='row'>Nama Dosen</th>  <td><input type='text' maxlength='3' class='form-control' name='xx' value='$d[Nama], $d[Gelar]' readonly></td></tr> 			
			<tr><th scope='row'>Nilai Angka</th>  <td><input type='text' maxlength='3' class='form-control' name='NilaiAkhir' value='$x[NilaiAkhir]'></td></tr>                    
		  </tbody>
		  </table>
			<div class='box-footer'>
			<button type='submit' name='ubahnilai' class='btn btn-info'>Perbaharui Nilai</button>
			<a href='index.php?ndelox=nilaisp&act=nilaispbl&tahun=$_GET[tahun]&prodi=$_GET[prodi]&MhswID=$_GET[MhswID]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>		
			</div> 
		  </div>
		</div>
	  </div>

	</form>
	</div>";
}  //tutup atas


elseif($_GET['act']=='inputnilai'){ 
	$dt = mysqli_fetch_array(mysqli_query($koneksi, "select * FROM vw_sp where MKID='".strfilter($_GET['MKID'])."'"));
	$dos = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar FROM dosen where Login='$dt[DosenID]'"));
	
	echo"<div class='col-xs-12'>  
	<div class='box'>
	<div class='box-header'>";
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
<table width='100%'>
<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#FF7213;><td colspan=5 height=10></td></tr>
<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#FF7213;>
<th scope='row' style='width:100px'>&nbsp;&nbsp;Matakuliah</th> <td scope='row' style='width:500px'><b> :<?php echo"&nbsp;$dt[NamaMK] ($dt[SKS] SKS)";?></b></td>
</tr>
<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#FF7213;>
<th scope='row' style='width:100px' >&nbsp;&nbsp;Dosen Pengampu</th> <td scope='row' style='width:500px'><b> :<?php echo"&nbsp;$dos[Nama], $dos[Gelar] ";?></b></td>
</tr>
<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#FF7213;>
<th scope='row' style='width:100px' >&nbsp;&nbsp;Waktu </th> <td scope='row' style='width:500px'><b> :<?php echo"&nbsp;$dt[Waktu] WIB ";?></b></td>
</tr>
<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#FF7213;>
<th scope='row' style='width:100px' >&nbsp;&nbsp;Ruang</th> <td scope='row' style='width:500px'><b> :<?php echo"&nbsp;$dt[Ruang]";?></b></td>
</tr>
<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#FF7213;><td colspan=5 height=10></td></tr>
</table></div>

<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example" class="table table-sm table-striped">
  <thead>
	<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#CCCC00;>
	  <th style='width:20px'>No</th>
	  <th style='width:20px'>NIM</th>  
	  <th style='width:150px'>Nama Mahasiswa</th>
	  <th style='width:60px'>Nilai Angka</th> 
      <th style='width:60px'>Nilai Huruf</th>	  
	</tr>
  </thead>
  <tbody>
<?php
  if (isset($_GET['tahun'])){ 
	  echo"<input type='hidden' name='prodi' value='$_GET[prodi]'>
	  <input type='hidden' name='tahun' value='$_GET[tahun]'>";
	  $tampil = mysqli_query($koneksi, "SELECT * from vw_sp WHERE TahunID='".strfilter($_GET['tahun'])."' 
							 and ProdiID='".strfilter($_GET['prodi'])."' 
							 AND MKID='".strfilter($_GET['MKID'])."' ORDER by MhswID ASC");      		 
	  $no=1;
	  while($r=mysqli_fetch_array($tampil)){			 			 
		$nilai = $r['NilaiAkhir'];
		if ($nilai >= 85 AND $nilai <= 100){
			$huruf = "A";
			$bobot = 4;
		}
		elseif ($nilai >= 80 AND $nilai <= 84.99){
			$huruf = "A-";
			$bobot = 3.70;
		}
		elseif ($nilai >= 75 AND $nilai <= 79.99){
			$huruf = "B+";
			$bobot = 3.30;
		}
		elseif ($nilai >= 70 AND $nilai <= 74.99){
			$huruf = "B";
			$bobot = 3;
		}
		elseif ($nilai >= 65 AND $nilai <= 69.99){
			$huruf = "B-";
			$bobot = 2.70;
		}
		elseif ($nilai >= 60 AND $nilai <= 64.99){
			$huruf = "C+";
			$bobot = 2.30;
		}
		elseif ($nilai >= 55 AND $nilai <= 59.99){
			$huruf = "C";
			$bobot = 2;
		}
		elseif ($nilai >= 50 AND $nilai <= 54.99){
			$huruf = "C-";
			$bobot = 1.70;
		}
		elseif ($nilai >= 40 AND $nilai <= 49.99){
			$huruf = "D";
			$bobot = 1;
		}
		elseif ($nilai < 40){
			$huruf = "E";
			$bobot = 0;
		}
	  $dos = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar from dosen WHERE Login='$r[DosenID]'"));
	  echo "<tr><td>$no</td>
			<td>$r[MhswID] </td>  
			<td>$r[NamaMhs] </td>                                                 									
			<input type='hidden' value='$r[SpID]' name='SpID".$no."'>	
			<input type='hidden' value='$r[MKID]' name='MKID".$no."'>	
			<input type='hidden' value='$r[MhswID]' name='MhswID".$no."'>						
			<td ><input type='text' style='width:90px; text-align:center; padding:0px' maxlength='3' name='NilaiAkhir".$no."' value='$r[NilaiAkhir]'></td>
			<input type='hidden' style='width:90px; text-align:center; padding:0px' maxlength='5'  name='BobotNilai".$no."' value='$bobot'>
			<input type='hidden' style='width:90px; text-align:center; padding:0px' maxlength='3'  name='GradeNilai".$no."' value='$huruf'>
			<td ><input type='text' style='width:90px; text-align:center; padding:0px' maxlength='3'  value='$huruf' readonly></td>			
			</tr>";
			$no++;
	  }
	  ?>
	  <tbody>
	  </table></div>
</div>
</div>

	  <div class='box-footer'>
	  <button type='submit' name='simpannilai' class='btn btn-info pull-right'>Perbaharui Nilai</button>                      
	  </div>
	  </form>
	 
	
	<?php
	 if (isset($_POST['simpannilai'])){ 			 	
		//$JmlData 				= count($_POST[SpID]); //substitute prihan
		$JmlData 				= mysqli_num_rows(mysqli_query($koneksi, "SELECT MhswID,TahunID FROM t_sp where TahunID='".strfilter($_GET['tahun'])."'"));//prihan 	   //ProdiID='".strfilter($_GET[prodi])."' AND 		
		for ($i=1; $i <= $JmlData; $i++){
		$MKID 					= strfilter($_POST['MKID'.$i]);
		$MhswID 				= strfilter($_POST['MhswID'.$i]);
		$NilaiAkhir 			= strfilter($_POST['NilaiAkhir'.$i]);
		$BobotNilai 			= strfilter($_POST['BobotNilai'.$i]);
		$GradeNilai 			= strfilter($_POST['GradeNilai'.$i]);
		$cek = mysqli_query($koneksi, "SELECT * FROM t_sp where MKID='$MKID' and MhswID='$MhswID'");
		$total = mysqli_num_rows($cek);
			if ($total >= 1){
				$query = mysqli_query($koneksi, "UPDATE t_sp SET 
								  NilaiAkhir='$NilaiAkhir',
								  BobotNilai='$BobotNilai',
								  GradeNilai='$GradeNilai'
								  WHERE MKID='$MKID' 
								  AND MhswID='$MhswID'");               
			}	  
	 }
	
	  if ($query){
		   echo "<script>document.location='index.php?ndelox=nilaisp&act=inputnilai&prodi=".$_POST['prodi']."&tahun=".$_POST['tahun']."&MKID=".$_GET['MKID']."&sukses';</script>";
	  }else{
		   echo "<script>document.location='index.php?ndelox=nilaisp&act=inputnilai&prodi=".$_POST['prodi']."&tahun=".$_POST['tahun']."&MKID=".$_GET['MKID']."&gagal';</script>";
	  }  	  
	}		 
}
}	

?>