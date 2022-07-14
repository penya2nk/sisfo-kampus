<div class='card'>
<div class='card-header'>

<div class="form-group row">
<label class="col-md-3 col-form-label text-md-right"><b style='color:purple'>SEMESTER PENDEK</b></label>
<div class="col-md-2">
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='sp/datasp'>
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

<div class="col-md-2">
<select name='Periode' class='form-control form-control-sm' onChange='this.form.submit()'>
<?php 
	echo "<option value=''>- Pilih Periode -</option>";
	$prodi = mysqli_query($koneksi, "SELECT * from tahun_sp WHERE NA='N' order by Periode Asc");
	while ($k = mysqli_fetch_array($prodi)){
	   if ($_GET[Periode]==$k[Periode]){
		echo "<option value='$k[Periode]' selected>$k[Periode]</option>";
	  }else{
		echo "<option value='$k[Periode]'>$k[Periode]</option>";
	  }
	}
?>
</select>
</div>

<div class="col-md-1">
<input type="submit" class='btn btn-success btn-sm' value='Lihat'>
</form>
</div>

<div class="col-md-2">
<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=sp/datasp&act=tambahdata&tahun=<?php echo $_GET[tahun]; ?>&prodi=<?php echo "$_GET[prodi]"; ?>&Periode=<?php echo "$_GET[Periode]"; ?>'>Tambahkan Data</a>
</div>


</div>
</div>
</div>
</div>		


<?php 
echo" <div class='card'>
<div class='card-header'>
<div align='center'>
  <a class='btn btn-primary btn-sm' href='?ndelox=sp/datasp&tahun=$_GET[tahun]&prodi=$_GET[prodi]&Periode=$_GET[periode]'>Matakuliah</a>
  <a class='btn btn-danger btn-sm' href='?ndelox=sp/datasp&act=viewmhs&tahun=$_GET[tahun]&prodi=$_GET[prodi]&Periode=$_GET[periode]'>Mahasiswa</a>
  </div>
</div>
</div>";
?>	

<?php if ($_GET[act]==''){ ?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example" class="table table-sm table-bordered table-striped">
  <thead>
	<tr style='background:purple;color:white'>
	  <th style='width:10px'>No</th>
      <th style='width:20px'>MKID</th>	  
	  <th style='width:20px'>Kode</th>
	  <th style='width:400px'>Nama Matakuliah</th>
       <th style='width:20px'>SKS</th>	                                    	  
	</tr>
  </thead>
  <tbody>
<?php
if (isset($_GET[tahun])){			  
	$tampil = mysqli_query($koneksi, "SELECT distinct(MKID),MKKode,NamaMK,Sesi,SKS,TahunID,ProdiID,DosenID,Periode from vw_sp WHERE TahunID='".strfilter($_GET[tahun])."' 
						   AND ProdiID='".strfilter($_GET[prodi])."' AND Periode='".strfilter($_GET[Periode])."' order by NamaMK asc");			  
	$no = 1;
	while($r=mysqli_fetch_array($tampil)){
	$jmlMt=mysqli_num_rows(mysqli_query("select MhswID,TahunID from vw_sp where MKID='$r[MKID]' AND TahunID='".strfilter($_GET[tahun])."' AND Periode='".strfilter($_GET[Periode])."'"));
	$ds     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$r[DosenID]'"));
	echo"<tr>
		<td>$no</td>  
        <td>$r[MKID]</td>		
		<td>$r[MKKode]</td>
		<td><a href='?ndelox=sp/datasp&act=viewmhs&MKID=$r[MKID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&Periode=$r[Periode]'>$r[NamaMK]</a> ($jmlMt Orang) - <a href='?ndelox=sp/datasp&act=setdosen&MKID=$r[MKID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&DosenID=$r[DosenID]&Periode=$_GET[Periode]'> SET DOSEN</a> $ds[Nama], $ds[Gelar]</td>
		<td>$r[SKS]</td>
		</tr>";
		$no++;
	}
}
?>
<tbody>
</table></div>
</div>
</div>

<?php } elseif ($_GET[act]=='viewmhs'){ ?>

<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example" class="table table-sm table-bordered table-striped">
  <thead>
	<tr style='background:purple;color:white'>
	  <th style='width:10px'>No</th>              
	  <th style='width:20px'>NIM</th>
	  <th style='width:400px'>Nama Mahasiswa</th>	                                    
	  <th width='100px'>Prodi</th> 
	</tr>
  </thead>
  <tbody>
<?php
if (isset($_GET[tahun])){			  
	$tampil = mysqli_query($koneksi, "SELECT distinct(MhswID),NamaMhs,TahunID,ProdiID,Periode from vw_sp 
					WHERE TahunID='".strfilter($_GET[tahun])."' 
					AND ProdiID='".strfilter($_GET[prodi])."' AND Periode='".strfilter($_GET[Periode])."' order by NamaMhs asc");		  
	$no = 1;
	while($r=mysqli_fetch_array($tampil)){
  	$jmlMh=mysqli_num_rows(mysqli_query("select MKID,TahunID,Periode from vw_sp where MhswID='$r[MhswID]' AND TahunID='".strfilter($_GET[tahun])."' AND Periode='".strfilter($_GET[Periode])."'"));	
	echo"<tr>
	<td>$no</td>            
	<td>$r[MhswID]</td>
	<td><a href='?ndelox=sp/datasp&act=viewmk&MhswID=$r[MhswID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&Periode=$r[Periode]'>$r[NamaMhs]</a> ($jmlMh Matakuliah)</td>
	<td>$r[ProdiID]</td>
	</tr>";
	$no++;
	}
}	
?>
<tbody>
</table>
</div>
</div>
</div>
<?php 
}



//===============================================================================================================================
elseif($_GET[act]=='viewmhsx'){
//$dt = mysqli_fetch_array(mysqli_query("select MKID,Nama,SKS,Sesi from mk WHERE MKID='$_GET[MKID]'"));
//$ds     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$dt[DosenID] '"));
$dt   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM t_sp where MKID='".strfilter($_GET[MKID])."'"));
$mt     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MKID,MKKode,Nama,SKS,Sesi FROM mk where MKID='".strfilter($_GET[MKID])."'"));
$ds     = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$dt[DosenID] '"));
?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example" class="table table-sm table-bordered table-striped">
<tr>
<th scope='row' style='width:230px'>MATAKULIAH</th> 
<td scope='row' style='width:500px'><b> :<?php echo"&nbsp;$mt[Nama] ($mt[SKS] SKS) - Semester $mt[Sesi]";?></b></td>
<td scope='row' style='width:80px' align="right"><b><?php echo"<a href='print_report/absensi-sp.php?tahun=$_GET[tahun]&prodi=$_GET[prodi]&MKID=$_GET[MKID]&Periode=$_GET[Periode]' target=_BlANK>Cetak PDF</a>";?></b></td>
<td scope='row' style='width:100px' align="right"><b><?php echo"<a href='print_report/print-frmnilai-sp.php?tahun=$_GET[tahun]&prodi=$_GET[prodi]&MKID=$_GET[MKID]&Periode=$_GET[Periode]' target=_BlANK>Form Nilai PDF</a>";?></b></td>
<td scope='row' style='width:80px' align="right"><b><?php echo"<a href='print_reportxls/dataspmkxls.php?SpID=$r[SpID]&tahun=$_GET[tahun]&prodi=$_GET[prodi]&MKID=$_GET[MKID]&Periode=$_GET[Periode]'>Export XLS</a>";?></b></td>
</tr>
<tr>
<th scope='row' style='width:150px'><b>DOSEN PENGAMPU</b></th> 
<td scope='row' style='width:500px' colspan='4'><b> :<?php echo"&nbsp;$ds[Nama], $ds[Gelar]";?></b></td>

</td>
</tr>


<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example" class="table table-sm table-bordered table-striped">
<thead>
<tr>
  <th style='width:10px'>No</th>                       
  <th style='width:20px'>NIM</th>
  <th style='width:300px'>Mahasiswa</th>
  <th style='width:30px'>Prodi</th>                                    
  <th width='80px'>Action</th> 
</tr>
</thead>
<tbody>
<?php          
$tampil = mysqli_query($koneksi, "SELECT * from vw_sp WHERE MKID='".strfilter($_GET[MKID])."' and TahunID='".strfilter($_GET[tahun])."' AND Periode='".strfilter($_GET[Periode])."' order by MhswID asc");
$no = 1;
while($r=mysqli_fetch_array($tampil)){	
echo"<tr>
	<td>$no</td>
    <td>$r[MhswID]</td>
    <td>$r[NamaMhs] </td>
	<td>$r[ProdiID] </td>					    
<td style='width:70px !important'>
<a class='btn btn-danger btn-xs' title='Hapus Data' href='index.php?ndelox=sp/datasp&act=viewmhs&hapus=$r[SpID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&MKID=$r[MKID]&Periode=$r[Periode]' onclick=\"return confirm('Data akan dihapus?')\"><i class='fa fa-trash'></i></a>
</td>";
echo "</tr>";
$no++;
}
if (isset($_GET[hapus])){
    $data=mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_sp where SpID='".strfilter($_GET[hapus])."'"));
    mysqli_query("INSERT into t_sp_del (SpID,
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
										  VALUES('".strfilter($data[SpID])."',
												 '".date('Y-m-d H:i:s')."',
												 '".strfilter($data[TahunID])."',
												 '".strfilter($data[MhswID])."',
												 '".strfilter($data[MKID])."',
												 '".strfilter($data[SKS])."',
												 '".strfilter($data[DosenID])."',
												 '".strfilter($data[NilaiAkhir])."',
												 '".strfilter($data[BobotNilai])."',
												 '".strfilter($data[GradeNilai])."',														 
												 '".strfilter($data[Periode])."',
												 '$_SESSION[id]')");
	mysqli_query("DELETE FROM t_sp where SpID='".strfilter($_GET[hapus])."'");
	echo "<script>document.location='index.php?ndelox=sp/datasp&act=viewmhs&prodi=$_GET[prodi]&tahun=$_GET[tahun]&MKID=$_GET[MKID]&Periode=$_GET[Periode]';</script>";
}
?>
<tbody>
</table></div>
</div>
</div>
<?php	
}

//============================================================================================================================
elseif($_GET[act]=='viewmk'){
$dt = mysqli_fetch_array(mysqli_query("select MhswID,Nama,ProdiID from mhsw WHERE MhswID='".strfilter($_GET[MhswID])."' "));
?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example" class="table table-sm table-bordered table-striped">
    <tr>
    <th scope='row' style='width:150px'>Nama Mahasiswa</th> <td scope='row' style='width:500px'> :<?php echo"&nbsp;$dt[MhswID] - $dt[Nama] - ($dt[ProdiID])";?></td>
    </tr>
</table></div>
</div>
</div>


<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example" class="table table-sm table-bordered table-striped">
<thead>
<tr>
  <th style='width:10px'>No</th>                       
  <th style='width:20px'>Kode</th>
  <th style='width:300px'>Matakuliah</th> 
 <th style='width:30px'>SKS</th>   
  <th width='80px'>Action</th> 
</tr>
</thead>
<tbody>
<?php   
$tampil = mysqli_query($koneksi, "SELECT * from vw_sp WHERE MhswID='".strfilter($_GET[MhswID])."' and TahunID='".strfilter($_GET[tahun])."' AND Periode='".strfilter($_GET[Periode])."'");
$no = 1;
while($r=mysqli_fetch_array($tampil)){			  
echo"<tr><td>$no</td>
        <td>$r[MKKode]</td>
        <td>$r[NamaMK]</td>
		<td>$r[SKS]</td>		
	<td style='width:70px !important'>
	<a class='btn btn-danger btn-xs' title='Hapus Data' href='index.php?ndelox=sp/datasp&act=viewmk&hapus=$r[SpID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&MhswID=$r[MhswID]&Periode=$_GET[Periode]' onclick=\"return confirm('Data akan dihapus?')\"><i class='fa fa-trash'></i></a>
	</td></tr>";
$no++;
$tsks += $r[SKS];		  
}

if (isset($_GET[hapus])){
	$data=mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_sp where SpID='".strfilter($_GET[hapus])."'"));
    mysqli_query("INSERT into t_sp_del (SpID,
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
										  VALUES('".strfilter($data[SpID])."',
												 '".date('Y-m-d H:i:s')."',
												 '".strfilter($data[TahunID])."',
												 '".strfilter($data[MhswID])."',
												 '".strfilter($data[MKID])."',
												 '".strfilter($data[SKS])."',
												 '".strfilter($data[DosenID])."',
												 '".strfilter($data[NilaiAkhir])."',
												 '".strfilter($data[BobotNilai])."',
												 '".strfilter($data[GradeNilai])."',														 
												 '".strfilter($data[Periode])."',
												 '$_SESSION[id]')");
	mysqli_query("DELETE FROM t_sp where SpID='".strfilter($_GET[hapus])."'");
	echo "<script>document.location='index.php?ndelox=sp/datasp&act=viewmk&prodi=$_GET[prodi]&tahun=$_GET[tahun]&MhswID=$_GET[MhswID]&Periode=$_GET[Periode]';</script>";
}
?>
<tr ><td colspan='7'>Total SKS yang diambil: <?php echo"$tsks SKS"; ?></td></tr>		  
<tbody>
</table></div>
</div>
</div>
<?php	
}


elseif($_GET[act]=='setdosen'){
$mk = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MKID,MKKode,Nama,SKS,Sesi FROM mk WHERE MKID='".strfilter($_GET[MKID])."'"));
$sp = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM t_sp WHERE MKID='".strfilter($_GET[MKID])."'"));	
if (isset($_POST[tambahx])){	
	$sqx =mysqli_query("UPDATE t_sp set DosenID='".strfilter($_POST[DosenID])."',Waktu='".strfilter($_POST[Waktu])."',Ruang='".strfilter($_POST[Ruang])."' WHERE MKID='".strfilter($_POST[MKID])."'");
	if ($sqx){
		echo "<script>document.location='index.php?ndelox=sp/datasp&tahun=$_POST[tahun]&prodi=$_POST[prodi]&Periode=$_GET[Periode]&sukses';</script>";
	}else{
		echo "<script>document.location='index.php?ndelox=sp/datasp&tahun=$_POST[tahun]&prodi=$_POST[prodi]&Periode=$_GET[Periode]&gagal';</script>";
	}		
}
	
echo "
           
		<div class='box-header with-border'>
		  <h3 class='box-title'>Set Dosen</h3>
		</div>

	  <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
		<input type='hidden' name='tahun' value='$_GET[tahun]'>
		<input type='hidden' name='program' value='$_GET[program]'>
		<input type='hidden' name='prodi' value='$_GET[prodi]'>
		<input type='hidden' name='MKID' value='$_GET[MKID]'>
		<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
		  <table class='table table-condensed table-bordered'>
		  <tbody>
	        <tr><th scope='row' width='200px'>Dosen</th>  <td>
			<select name='DosenID' class='combobox form-control'>";
			echo "<option value=''> Pencarian Data Dosen </option>";
			$dosn = mysqli_query($koneksi, "SELECT Login,Nama FROM dosen order by Nama asc");
			while ($k = mysqli_fetch_array($dosn)){
			 if ($_GET[DosenID]==$k[Login]){
				echo "<option value='$k[Login]' selected>$k[Login] - $k[Nama] </option>";
			  }else{
				echo "<option value='$k[Login]'>$k[Login] - $k[Nama]</option>";
			  }
			}
			echo"</select> 
			</td></tr>
			<tr><th scope='row'>Matakuliah</th>  <td><input type='text' class='form-control' name='f' value='$mk[Nama]'></td></tr>
			<tr><th scope='row'>Waktu</th>  <td><input type='text' class='form-control' name='Waktu' value='$sp[Waktu]'></td></tr>
			<tr><th scope='row'>Ruang</th>  <td><input type='text' class='form-control' name='Ruang' value='$sp[Ruang]'></td></tr>
		  </tbody>
		  </table>
		  </div>
		  </div>
		  </div>
	<div class='box-footer'>
	<button type='submit' name='tambahx' class='btn btn-info'>Set Dosen</button>
	<a href='index.php?ndelox=sp/datasp&tahun=$_GET[tahun]&prodi=$_GET[prodi]&Periode=$_GET[Periode]'>
	<button type='button' class='btn btn-default pull-right'>Cancel</button></a>       
	</div>
	</form>
	</div>";

}

//============================================================================================================================
elseif($_GET[act]=='tambahdata'){
$kur = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM kurikulum where NA='N' AND ProdiID='".strfilter($_GET[prodi])."'"));	
if (isset($_POST[tambah])){	   
	$mk = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MKID,SKS FROM mk WHERE MKID='".strfilter($_POST[MKID])."'"));
	$cek =mysqli_num_rows(mysqli_query("select * from t_sp where MhswID='".strfilter($_POST[MhswID])."' AND MKID='".strfilter($_GET[MKID])."'"));
	if ($cek>0){
		echo "<script>document.location='index.php?ndelox=sp/datasp&SpID=".$_GET[SpID]."&MhswID=".$_POST[MhswID]."&gagal';</script>";
		exit;
}
/*$t =mysqli_fetch_array(mysqli_query("select sum(SKS) as totSKS from t_sp where MhswID='$_POST[MhswID]'"));
$vsks = $t[totSKS]+$_GET[SKS];
if ($vsks>10){
	echo "<script>document.location='index.php?ndelox=sp/datasp&SpID&lebih';</script>";
	exit;
}*/
	$sqx =mysqli_query("insert into t_sp(TahunID,
									Tanggal,
									MhswID,
									MKID,
									SKS,
									Periode,
									LoginBuat)
							 values('".strfilter($_GET[tahun])."',
									'".date('Y-m-d')."',
									'".strfilter($_POST[MhswID])."',
									'".strfilter($_POST[MKID])."',
									'$mk[SKS]',
									'".strfilter($_POST[Periode])."',
									'$_SESSION[id]')");
	if ($sqx){
		echo "<script>document.location='index.php?ndelox=sp/datasp&tahun=$_POST[tahun]&prodi=$_POST[prodi]&Periode=$_GET[Periode]&sukses';</script>";
	}else{
		echo "<script>document.location='index.php?ndelox=sp/datasp&tahun=$_POST[tahun]&prodi=$_POST[prodi]&Periode=$_GET[Periode]&gagal';</script>";
	}		
}
	
echo "


         
	  <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
		<input type='hidden' name='tahun' value='$_GET[tahun]'>
		<input type='hidden' name='program' value='$_GET[program]'>
		<input type='hidden' name='prodi' value='$_GET[prodi]'>
		<input type='hidden' name='Periode' value='$_GET[Periode]'>
		<div class='card'>
<div class='card-header'>
<div class='box-header with-border'>
<h3 class='box-title'>Tambah Data</h3>
</div>
<div class='table-responsive'>
		  <table class='table table-condensed table-bordered'>
		  <tbody>
	        <tr><th scope='row' width='200px'>Mahasiswa</th>  <td>
			<select name='MhswID' class='combobox form-control'>";
			echo "<option value=''> Pencarian Data Mahasiswa </option>";
			$mhs = mysqli_query($koneksi, "SELECT MhswID,Nama,ProgramID,ProdiID,Handphone FROM mhsw 
								WHERE ProdiID='".strfilter($_GET[prodi])."' and StatusMhswID='A' order by MhswID asc");
			while ($k = mysqli_fetch_array($mhs)){
			 if ($_GET[MhswID]==$k[MhswID]){
				echo "<option value='$k[MhswID]' selected>$k[MhswID] - $k[Nama] - $k[ProgramID] - $k[Handphone] </option>";
			  }else{
				echo "<option value='$k[MhswID]'>$k[MhswID] - $k[Nama] - $k[ProgramID] - $k[Handphone]</option>";
			  }
			}
			echo"</select> 
			</td></tr>
			 <tr><th scope='row' >Matakuliah</th>  <td>
			<select name='MKID' class='combobox form-control'>";
			echo "<option value=''> Pencarian Data Matakuliah </option>";
			$mk = mysqli_query($koneksi, "SELECT MKID,MKKode,Nama,SKS,Sesi,ProdiID,NA,KurikulumID FROM mk 
								WHERE ProdiID='".strfilter($_GET[prodi])."' AND NA='N' AND KurikulumID='$kur[KurikulumID]' order by Sesi asc");
			while ($k = mysqli_fetch_array($mk)){
			 if ($_GET[MKID]==$k[MKID]){
				echo "<option value='$k[MKID]' selected>$k[MKKode] - $k[Nama] ($k[SKS]) - SEMESTER $k[Sesi]</option>";
			  }else{
				echo "<option value='$k[MKID]'>$k[MKKode] - $k[Nama] ($k[SKS]) - SEMESTER $k[Sesi]</option>";
			  }
			}
			echo"</select> 
			</td></tr>	   
		  </tbody>
		  </table>
			<div class='box-footer'>
			<button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
			<a href='index.php?ndelox=sp/datasp&tahun=$_GET[tahun]&prodi=$_GET[prodi]&Periode=$_SESSION[Periode]'>
			<button type='button' class='btn btn-default pull-right'>Cancel</button></a>       
			</div>
		  </div>
		  </div>
		  </div>

	</form>
	</div>";
}
?>