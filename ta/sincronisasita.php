<div class="card">
<div class="card-header">
<div class="card-tools">

<?php 
  if (isset($_GET['tahun'])){ 
     echo "<b style='color:green;font-size:20px'>SINKRONISASI TUGAS AKHIR</b> <a  href='print_reportxls/lapbkddosenxls.php?tahun=$_GET[tahun]&prodi=$_GET[prodi]' target='_BLANK'>Export Ke Excel</a>";
  }else{ 
     echo "<b style='color:green;font-size:20px'>SINKRONISASI TUGAS AKHIR</b>"; 
  } ?>
</h3>
</div>

<div class="form-group row">
<label class="col-md-6 col-form-label text-md-left"><b style='color:purple'>Sincronisasi</b></label>

<div class="col-md-2 "> 
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='ta/sincronisasita'>
<select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
<?php 
	echo "<option value=''>- Pilih Tahun -</option>";
	$tahun = mysqli_query($koneksi, "SELECT distinct(TahunID) from tahun order by TahunID Desc"); //and NA='N'
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
<input type="submit" class='btn btn-success btn-sm' value='Lihat'>
</form>
</div>
</div>

</div>
</div>
<?php


if ($_GET['act']==''){ 
$prodi = mysqli_fetch_array(mysqli_query($koneksi, "select ProdiID,Nama from prodi where ProdiID='".strfilter($_GET['prodi'])."'"));
?> 
<div class="card">
	<div class="card-header">
	<div class="col-md-12">
	<div class='box box-info'>
	  <h3 class="box-title"><b style='color:green;font-size:20px'>Research Sincronize <?php echo"<b style=color:#FF8306;>$prodi[Nama]</b>";?> Tahun <?php echo"<b style=color:#FF8306;>$_GET[tahun]</b>";?></b></h3>	  
	</div>
	<div class="box-body">
	<?php
	if (isset($_POST['proses'])){
		if ($_GET['tahun']!=''){
			$qr=mysqli_query($koneksi, "SELECT * from jadwal_skripsi 
				where Ket2='1' 
				and TahunID='".strfilter($_GET['tahun'])."' 
				and ProdiID='".strfilter($_GET['prodi'])."'
				order by TahunID DESC");
		}else{
			$qr=mysqli_query($koneksi, "SELECT * from jadwal_skripsi 
				where Ket2='1' 
				and ProdiID='".strfilter($_GET['prodi'])."'
				order by TahunID DESC");
		}				
	while($data=mysqli_fetch_array($qr)) {
	$nom++;	
		//$cek = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM ta where MhswID='$data[MhswID]'"));
		/* if ($cek >= 1){
		   echo "<script language='javascript'>alert('Data sudah ada!');
			     window.location = 'inputloopta.php'</script>";
		   exit;
		} */
		//$datax=mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from ta where where MhswID='$data[MhswID]'"));
		if (!empty($data['MhswID'])){
			mysqli_fetch_array(mysqli_query($koneksi, "update mhsw set StatusMhswID='L' where MhswID='$data[MhswID]'"));
			$sdh =mysqli_fetch_array(mysqli_query($koneksi, "select * from ta where MhswID='$data[MhswID]'"));
			//Lakukan pengecekan lakukan insert pada tabel ta untuk data yang belum ada saja 
			if (empty($sdh)) {
				$qsimpan=mysqli_query($koneksi, "INSERT INTO ta
						   (TahunID,
							MhswID,
							Judul,
							ProdiID)
					 VALUES('".strfilter($data['TahunID'])."',							
							'".strfilter($data['MhswID'])."',
							'".strfilter($data['Judul'])."',
							'".strfilter($data['ProdiID'])."')");
				$mh = mysqli_fetch_array(mysqli_query($koneksi, "select MhswID,Nama from mhsw where MhswID='$data[MhswID]'"));			
				echo "<p>$nom <font color=green>$data[MhswID] - <b style=color:green>$mh[Nama]</b> di synchronize.. </font></p>";
					
			}else{
				$mh = mysqli_fetch_array(mysqli_query($koneksi, "select MhswID,Nama from mhsw where MhswID='$data[MhswID]'"));
				echo "<p>$nom <font color=gray>$data[MhswID] - <b style=color:green>$mh[Nama]</b> Sudah pernah synchronize..</font></p>";
			}				
		}							
	}
	} //tutup btRekap
	?>
		
	<form action='' method='POST'>
	<div class='table-responsive'>
	  <table id="example" class="table table-sm table-bordered table-striped">
	    <tr><td colspan='6'><input type='submit' name='proses' class='btn btn-info pull-right' value='Sincronize'></td></tr>
		<thead>
		  <tr style='background:purple;color:white'>
			<td>No</td>
			<td>NIM</td>
			<td>Nama Mahasiswa</td>
			<td>Judul</td>
			<td>Prodi</td>
			<td>Tahun</td>			
		  </tr>
		</thead>
		<tbody>
	  <?php 
	    if (!empty($_GET['tahun'])){
			$sql=mysqli_query($koneksi, "SELECT * from jadwal_skripsi 				
				where Ket2='1' 
				and TahunID='".strfilter($_GET['tahun'])."' 
				and ProdiID='".strfilter($_GET['prodi'])."'
				order by TahunID DESC");
		}else{
			$sql=mysqli_query($koneksi, "SELECT * from jadwal_skripsi 
				where Ket2='1' 
				and ProdiID='".strfilter($_GET['prodi'])."'
				order by TahunID DESC");
		}		
		while($data=mysqli_fetch_array($sql)){
		    	$nm=mysqli_fetch_array(mysqli_query($koneksi, "SELECT MhswID,Nama from mhsw where MhswID='$data[MhswID]'"));
				$no++;
		  		$Judulx = strtolower($data['Judul']);
          		$Judul	= ucwords($Judulx);
           		$Nama 	= strtoupper($nm['Nama']);        
		  echo "<tr>
				<td>$no</td>
				<td>$data[MhswID]</td>
				<td>$Nama</td>
				<td>$Judul</td>
				<td>$data[ProdiID]</td>
				<td>$data[TahunID]</td>
				</tr>";
		  }
	  ?>
	</tbody>
	</table>
	</div>
	  <div class='box-footer'>
      <input type='submit' name='proses' class='btn btn-info pull-right' value='Sincronize'>                      
      </div>
	  </form>
	</div>
  </div>
</div>
<?php
}
?>

