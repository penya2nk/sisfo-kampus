<div class='card'>
<div class='card-header'>
<h3 class="box-title"><b style='color:green;font-size:20px'> &nbsp;&nbsp;Transkrip Nilai Sementara Semester Pendek</b></h3>              
<?php
$m = mysqli_fetch_array(mysqli_query($koneksi, "SELECT a.MhswID, a.Nama AS NamaMhs, a.ProdiID, a.ProgramID, b.Nama AS NamaProdi 
									FROM mhsw a LEFT JOIN prodi b ON a.ProdiID=b.ProdiID where a.MhswID='".strfilter($_GET['MhswID'])."'")); 

?>

<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='sp/nilaitranskripsp'>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example1" class="table table-sm table-striped"> 
<tr>
<th scope='row' style='width:40px'>NIM</th> 
<td scope='row' style='width:160px'><input type='text'  class='form-control form-control-sm' name='MhswID' style='width:150px' value='<?php echo"$_GET[MhswID]";?>'></td>
<td>
<input type="submit" class='btn btn-success btn-sm' value='Lihat'>
</td>
<td scope='row' style='width:110px'>
<a class='pull-right btn btn-primary btn-sm'  href='index.php?ndelox=sp/nilaitranskripsp&act=carimhs&tahun=<?php echo $_GET[tahun]; ?>&prodi=<?php echo "$_GET[prodi]"; ?>&program=<?php echo 	"$_GET[program]"; ?>'>Cari Mahasiswa</a>
</td>
</tr>
</table>
</form>

</div>
</div>
</div>



<?php if ($_GET['act']==''){ 	                                        											   												
echo"<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>							  							 
	<input type='hidden' name='TahunID' value='$_GET[tahun]'>
	<input type='hidden' name='MhswID' value='$_GET[MhswID]'>
	<input type='hidden' name='prodi' value='$r[ProdiID]'>";
				 
	if (isset($_GET['MhswID'])){ 
	echo"<div class='card'>
	<div class='card-header'>
	<div class='table-responsive'>
	<table class='table table-sm table-bordered'>
	<tbody>              
	<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#FF7213;>
	<th scope='row' style='width:200px'>NIM</th>
	<th>$m[MhswID]</th>
	<th>Program </th>
	<th>$m[ProgramID]</th>
	</tr>
							
	<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#FF7213;>
	<th scope='row' >Nama Mahasiswa </th>
	<th>$m[NamaMhs]</td>
	<th scope='row' style='width:200px'>Program Studi</th> 
	<th>$m[NamaProdi]</th>
	</tr>        
	
	</tbody>
	</table></div>
	</div>
	</div>";
	
	?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example1" class="table table-sm table-striped"> 
        <tr >
        <td>
        <a class='pull-right btn btn-primary btn-sm' href='print_report/print-transkripsp.php?MhswID=<?php echo"$_GET[MhswID]";?>&prodi=<?php echo"$m[ProdiID]";?>' 
        target='_BLANK'>Cetak Transkrip</a>
		<a class='pull-right btn btn-primary btn-sm' href='print_reportxls/transkripxlssp.php?MhswID=<?php echo"$_GET[MhswID]";?>&prodi=<?php echo"$m[ProdiID]";?>' 
        target='_BLANK'>Export Ke Excel</a>
        </td></tr>
		</table></div>
</div>
</div>
	<?php } ?>

	<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example1" class="table table-sm table-striped">                     
	<thead>					 
	<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#CCCC00;>
	<th>No</th>                        
	
	<th>Kode</th>
	<th>Matakuliah</th>
	<th>SKS</th>
	<th>Grade Nilai</th>
	
	</tr>
	</thead>
	<tbody>
<?php									
$no = 1;									
$tampil = mysqli_query($koneksi, "SELECT * FROM vw_sp where MhswID='".strfilter($_GET['MhswID'])."' Group By MKKode order by NamaMK asc ");  								

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

	echo "<tr bgcolor=$warna>
	<td>$no</td>

	<td>$r[MKKode]</td>
	<td>$r[NamaMK]</td>
	<td>$r[SKS]</td>
	<td>$r[GradeNilai]</td>
	
	</tr>";
$no++;
$tsks += $r[SKS];
}

if (isset($_GET['hapus'])){
	mysqli_query($koneksi, "DELETE FROM xxx where KRSID='".strfilter($_GET['hapus'])."'");
    echo "<script>document.location='index.php?ndelox=sp/nilaitranskripsp&JadwalID=".$_GET['JadwalID']."&tahun=".$_GET['tahun']."&MhswID=".$_GET['MhswID']."&sukses';</script>";
}
echo"<tr bgcolor=$warna>
	<td colspan='7'>Total SKS yang diambil: $tsks SKS </td>
</tr>";	
echo "</tbody>
</table></div>
</div>
</div></div>";
								  
echo "<div class='box-footer'></div>";
echo "</form>";
}
 //tutup atas
?>