<div class="card">
<div class="card-header">
<h3 class="box-title">
<?php 
	echo "<b style='color:green;font-size:20px'>CEK KEHADIRAN</b>";  
	if (isset($_GET['tahun']) AND isset($_GET['MhswID'])){
		$pr = mysqli_fetch_array(mysqli_query($koneksi, "SELECT a.MhswID, a.Nama AS NamaMhs, a.ProdiID, a.ProgramID, 
		b.Nama AS NamaProdi,
		kh.KHSID,kh.TahunID 
		FROM mhsw a 
		LEFT JOIN prodi b ON a.ProdiID=b.ProdiID
		LEFT JOIN khs kh ON kh.MhswID=a.MhswID 
		WHERE kh.MhswID='".strfilter($_GET['MhswID'])."'"));
	}
	?>
</h3>
                  
	<div class="form-group row">
		<label class="col-md-6 col-form-label text-md-right"><b style='color:purple'>CEK KEHADIRAN</b></label>
		<div class="col-md-2">
		<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
		<input type="hidden" name='ndelox' value='dep/cekkehadiran'>
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
		<input type='text' class='form-control form-control-sm' name='MhswID' value='<?php echo $_GET['MhswID'] ?>'>
		</div>

		<div class="col-md-1">
		<input type="submit"  class='btn btn-success btn-sm' value='Lihat'>
		</form>
		</div>
	</div>
	
</div>
</div>


<?php
echo"<div class='card'>
<div class='card-header'>					
			
			<div class='table-responsive'>
			<table id='example' class='table table-sm table-striped'>
			<tbody>              
			<tr>
			<th scope='row' style='width:200px'>NIM</th>
			<th>$pr[MhswID]</th>
			<th>Program </th>
			<th>$pr[ProgramID]</th>
			</tr>									
			<tr>
			<th scope='row' >Nama Mahasiswa </th>
			<th>$pr[NamaMhs]</td>
			<th scope='row' style='width:200px'>Program Studi</th> 
			<th>$pr[NamaProdi]</th>
			</tr>        			
			</tbody>
			</table></div>
			</div>

		</div>										   
	</div>";  
?>
<?php if ($_GET['act']==''){ ?>
<div class='card'>
<div class='card-header'>
<div class="table-responsive">
<table id="example" class="table table-sm table-striped">
	<thead>
	<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>
	<th width="2%" style='text-align:center'>No</th>
        <th width="5%" style='text-align:center'>Kode</th>
        <th width="30%">Matakuliah</th>
        <th width="10%">Kelas</th>
		<th width="5%" style='text-align:center'>Jumlah Hadir</th>
        <th width="5%" style='text-align:center'>Hadir</th>  			  
	</tr>
	</thead>
	<tbody>
	<?php
		$sqlx = mysqli_query($koneksi, "SELECT presensimhsw.*, 
		jadwal.Kehadiran,jadwal.MKKode,jadwal.Nama AS NamaMK,
		jadwal.NamaKelas,jadwal.JenisJadwalID,jadwal.TahunID,
		SUM(presensimhsw.Nilai) as JML
		FROM jadwal,presensimhsw
		WHERE jadwal.JadwalID=presensimhsw.JadwalID	
		AND jadwal.TahunID='".strfilter($_GET['tahun'])."' 
		AND presensimhsw.MhswID='".strfilter($_GET['MhswID'])."'
		GROUP BY presensimhsw.JadwalID ");			
		$no = 1;
		while($r=mysqli_fetch_array($sqlx)){  
		$persen		  = ($r['JML'] / $r['Kehadiran']) * 100;
		$persentase	  = number_format(($r['JML'] / $r['Kehadiran'])* 100,0);		
		echo "<tr>
			<td style='text-align:center'>$no</td>            
			<td style='text-align:center'>$r[MKKode]</td>
			<td>$r[NamaMK]</td>
			<td>$r[NamaKelas]</td>
			<td style='text-align:center'>$r[JML] X</td>
			<td style='text-align:center'>$persentase %</td>
		</tr>";
	$no++;
	}  
?>
<tbody>
</table>
</div>
</div>
</div>
</div>

<!-- col 8 -->
</div> 
<?php 
}

?>
</div>
</div>