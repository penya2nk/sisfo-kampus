
<div class='card'>
<div class='card-header'>
<h3 class="box-title"><b style='color:green;font-size:20px'>KARTU HASIL STUDI SEMESTER PENDEK</b></h3>              
<?php
$m = mysqli_fetch_array(mysqli_query($koneksi, "SELECT a.MhswID, a.Nama AS NamaMhs, a.ProdiID, a.ProgramID, b.Nama AS NamaProdi 
									FROM mhsw a LEFT JOIN prodi b ON a.ProdiID=b.ProdiID where a.MhswID='".strfilter($_GET['MhswID'])."'")); 
?>                  


<div class="form-group row">
		<label class="col-md-5 col-form-label text-md-right"><b style='color:purple'>NILAI SEMESTER PENDEK</b></label>
		<div class="col-md-2">
		<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
		<input type="hidden" name='ndelox' value='sp/khscetaksp'>
		<select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
		<?php 
		$tahun = mysqli_query($koneksi, "SELECT distinct(TahunID) FROM tahun order by TahunID Desc"); //NA='N' and
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
		<input type='text'  class='form-control form-control-sm' name='MhswID' placeholder="NIM" value='<?php echo"$_GET[MhswID]";?>'></td>
		</div>

		<div class="col-md-1">

		<input type="submit"  class='btn btn-success btn-sm' value='Lihat'>
		</div>
		</form>


		<div class="col-md-1">
		<a class='pull-right btn btn-primary btn-sm' href='print_report/print-khssp.php?MhswID=<?php echo"$_GET[MhswID]";?>&prodi=<?php echo"$m[ProdiID]";?>&tahun=<?php echo"$_GET[tahun]";?>' target='_BLANK'>Cetak KHS SP</a><?php  ?>
		</div>

		</div>
</div>
</div>

<?php if ($_GET['act']==''){ 	                                        											   												
echo" 
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
	<table class='table table-sm table-bordered'>
	<tbody>              
	<tr>
	<th scope='row' style='width:200px'>NIM</th>
	<th>$m[MhswID]</th>
	<th>Program </th>
	<th>$m[ProgramID]</th>
	</tr>
							
	<tr>
	<th scope='row' >Nama Mahasiswa </th>
	<th>$m[NamaMhs]</td>
	<th scope='row' style='width:200px'>Program Studi</th> 
	<th>$m[NamaProdi]</th>
	</tr>        
	
	</tbody>
	</table>
	</div>
	</div>										   
	</div>";  
						              								
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

echo"
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>							  							 

	<input type='hidden' name='TahunID' value='$_GET[tahun]'>
	<input type='hidden' name='MhswID' value='$_GET[MhswID]'>
	<input type='hidden' name='prodi' value='$r[ProdiID]'>
				 

	<div class='card'>
	<div class='card-header'>
	<div class='table-responsive'>
	<table class='table table-sm table-bordered table-striped'>                      
	<thead>					 
	<tr style='background:purple;color:white'>
	<th>No</th>                        
	<th>Kode</th>
	<th>Matakuliah</th>
	<th>SKS</th>
	<th>Huruf</th>
	<th>Bobot</th>
	
	</tr>
	</thead>
	<tbody>";
									
$no = 1;									
$tampil = mysqli_query($koneksi, "SELECT * FROM vw_sp where MhswID='".strfilter($_GET['MhswID'])."' and TahunID='".strfilter($_GET['tahun'])."'");  								
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

$total_sks 	  	= $r['SKS'];
$total_bobot  	= $r['SKS'] * $bobot;

$tsks 			+= $total_sks;
$tbobottotal 	+= $total_bobot;
$ips = number_format($tbobottotal / $tsks,2);

	echo "<tr bgcolor=$warna>
	<td>$no</td>
	<td>$r[MKKode]</td>
	<td>$r[NamaMK]</td> 
	<td>$r[SKS]</td>
	<td>$huruf</td>
	<td>$total_bobot</td>
	</tr>";
$no++;
}

if (isset($_GET['hapus'])){
	mysqli_query($koneksi, "DELETE FROM krsxxx where KRSID='".strfilter($_GET['hapus'])."'");
    echo "<script>document.location='index.php?ndelox=sp/khscetaksp&JadwalID=".$_GET['JadwalID']."&tahun=".$_GET['tahun']."&MhswID=".$_GET['MhswID']."&sukses';</script>";
}
echo"
<tr bgcolor=$warna>
	<td colspan='6'>&nbsp;</td>
    </tr>
    <tr bgcolor=$warna>
	<td colspan='2'><b>Total SKS </b></td><td colspan='4'>:<b> $tsks SKS </b></td>
    </tr>
<tr bgcolor=$warna>
	<td colspan='2'><b>Total Bobot </b></td><td colspan='4'>:<b> $tbobottotal </b></td>
    </tr>
	<tr bgcolor=$warna>
	<td colspan='2'><b>IPS </b></td><td colspan='4'>:<b> $ips </b></td>
    </tr>
";	
echo "</tbody>
</table></div>
</div>
</div>";
								  
echo "<div class='box-footer'>
                     
</div>";
echo "</form>
";

}
 //tutup atas
?>