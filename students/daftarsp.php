<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<div class='box box-info'>
<h3 class="box-title"><b style='color:green;font-size:20px'>SEMESTER PENDEK</b></h3>    
</div>          
<?php 
echo"<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=students/daftarsp&act=viewdata'>Lihat Data SP</a>";
echo"&nbsp;<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=students/daftarsp'>Tambah Data SP</a>"; 
$r = mysqli_fetch_array(mysqli_query($koneksi, "SELECT mhsw.MhswID, mhsw.Nama AS NamaMhs, mhsw.ProdiID, mhsw.ProgramID, prodi.Nama AS NamaProdi 
								FROM mhsw LEFT JOIN prodi ON mhsw.ProdiID=prodi.ProdiID where mhsw.MhswID='$_SESSION[_Login]'"));
$kur = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM kurikulum where NA='N' AND ProdiID='$r[ProdiID]'"));

echo"				
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
	<table class='table table-sm table-bordered'>
	<tbody>              
	<tr>
	<th scope='row' style='width:200px'>NIM</th>
	<th>$_SESSION[_Login]</th>
	<th>Program </th>
	<th>$r[ProgramID]</th>
	</tr>
							
	<tr>
	<th scope='row' >Nama Mahasiswa </th>
	<th>$r[NamaMhs]</td>
	<th scope='row' style='width:200px'>Program Studi</th> 
	<th>$r[NamaProdi]</th>
	</tr>        	
	</tbody>
	</table>
	</div>
</div>
</div>";  
?>
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
elseif(isset($_GET['lebih'])){
	echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
	<span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Data tidak Di Proses, jumlah total sks sudah melebihi 10 SKS..
	</div>";
}
echo"							  				 
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
	<table class='table table-sm table-bordered table-striped'>                      
	<thead>					 
	<tr>
	<th>No</th>                        	
	<th>Kode</th>
	<th>Matakuliah</th>
	<th>SKS</th>
	<th>Semester</th>
	<th>Aksi</th>
	</tr>
	</thead>
	<tbody>";
								
$no = 1;									
$sqx = mysqli_query($koneksi, "SELECT * FROM mk where ProdiID='$r[ProdiID]' 
					   AND KurikulumID='$kur[KurikulumID]' 
				       AND NA='N' 
					   AND MKID NOT IN (SELECT MKID from t_sp WHERE MhswID='$_SESSION[_Login]' AND TahunID='$_SESSION[tahun_akademik]') ORDER BY Sesi ASC"); 
while($r=mysqli_fetch_array($sqx)){   					         
	echo "<tr bgcolor=$warna>
	<td>$no</td>
	<td>$r[MKKode]</td>
	<td>$r[Nama]</td> 
	<td>$r[SKS]</td>
	<td>$r[Sesi]</td>
	<td>
	<a  class='btn btn-success btn-xs' href='index.php?ndelox=students/daftarsp&act=ambilsp&SpID=$r[SpID]&MKID=$r[MKID]&SKS=$r[SKS]' onclick=\"return confirm('Benar mengambil Matakuliah $r[Nama] di semester pendek?')\"><span class='glyphicon glyphicon-th'></span> Ambil</a>
	</td>
	</tr>";
$no++;
$tsks += $r['SKS'];
}

echo"<tr bgcolor=$warna><td colspan='7'>Total SKS : $tsks SKS </td></tr>";	
echo "</tbody></table>
</div>
</div>
</div>";								  
echo "<div class='box-footer'>
                    
</div>";
echo "
</div>
</div>
</div>";


}else if ($_GET['act']=='ambilsp'){ 
$cek =mysqli_num_rows(mysqli_query($koneksi, "select * from t_sp where MhswID='$_SESSION[_Login]' AND MKID='".strfilter($_GET['MKID'])."'"));
if ($cek>0){
	echo "<script>document.location='index.php?ndelox=students/daftarsp&SpID=".$_GET['SpID']."&MhswID=".$_GET['MhswID']."&gagal';</script>";
	exit;
}
$t =mysqli_fetch_array(mysqli_query($koneksi, "select sum(SKS) as totSKS from t_sp where MhswID='$_SESSION[_Login]' and TahunID='$_SESSION[tahun_akademik]'"));
$vsks = $t['totSKS']+$_GET['SKS'];
if ($vsks>10){
	echo "<script>document.location='index.php?ndelox=students/daftarsp&SpID&lebih';</script>";
	exit;
}
$sqx =mysqli_query($koneksi, "insert into t_sp(TahunID,
									Tanggal,
									MhswID,
									MKID,
									SKS,
									Periode)
				values('$_SESSION[tahun_akademik]',
						'".date('Y-m-d')."',
						'$_SESSION[_Login]',
						'".strfilter($_GET['MKID'])."',
						'".strfilter($_GET['SKS'])."',
						'$_SESSION[Periode]')");
	if ($sqx){
		echo "<script>document.location='index.php?ndelox=students/daftarsp&sukses';</script>";                       
	}else{
		echo "<script>document.location='index.php?ndelox=students/daftarsp&gagal';</script>";
	}
}

else if ($_GET['act']=='viewdata'){ 
$r = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM t_sp WHERE MhswID='$_SESSION[_Login]' AND TahunID='$_SESSION[tahun_akademik]'"));                                      
echo"							 
	 <div class='box box-info'>								
	 <div class='box-body'>";

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

 echo"<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>

 <div class='card'>
 <div class='card-header'>
 <div class='table-responsive'>						 
	  <table class='table table-sm table-bordered table-striped'>                      
	  <thead>					 
		  <tr>
			<th>No</th>                        					
			<th>Kode</th>
			<th>Matakuliah</th>
			<th>SKS</th>
			<th>Semester</th>
									
		  </tr>
		  </thead>
		  <tbody>";														
			$sq = mysqli_query($koneksi, "SELECT  
			mk.MKKode,mk.Nama,mk.Sesi,
			t_sp.SpID,t_sp.TahunID,t_sp.SKS,t_sp.MhswID
			FROM t_sp,mk 
			WHERE mk.MKID=t_sp.MKID
			AND t_sp.MhswID='$_SESSION[_Login]'");																			
			$no=1;					
			while($r=mysqli_fetch_array($sq)){   					         
			echo "<tr bgcolor=$warna>
					<td>$no</td>
					<td>$r[MKKode]</td>
					<td>$r[Nama]</td>
					<td>$r[SKS]</td>
					<td>$r[Sesi]</td>
					
</tr>";
	$no++;
	$tsks += $r['SKS'];
	}
if (isset($_GET['hapus'])){
	mysqli_query($koneksi, "DELETE FROM t_sp WHERE SpID='".strfilter($_GET['hapus'])."'");
	echo "<script>document.location='index.php?ndelox=students/daftarsp&act=viewdata';</script>";
}					  
echo"<tr bgcolor=$warna><td colspan='7'>Total: $tsks SKS </td></tr>";		 
echo "</tbody></table>";
echo"</div></div>";
echo "<div class='box-footer'></div>";
echo "</form></div";
?>			

<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table>
<tr><td colspan='9' align="left">
<?php echo"<b style='color:red;'>Perhatian!</b> <br>Jumlah SKS SP tidak boleh melebihi 10 SKS Setiap Semester"; ?>
</td>
</tr>
</table>	
</div>
</div>
</div>
<?php                 		
} //tutup atas
?>