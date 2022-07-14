
<div class='card'>
<div class='card-header'>
<h3 class="box-title"><b style='color:green;font-size:20px'>Cetak Label UTS dan UAS</b></h3>  


<div class="form-group row">
<label class="col-md-6 col-form-label text-md-right"><b style='color:purple'>FILTER</b></label>
<div class="col-md-2">
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='dep/labeluts'>
<select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
<?php 
echo "<option value=''>- Pilih Tahun Akademik -</option>";
$tahun = mysqli_query($koneksi, "SELECT distinct(TahunID), ProdiID,NA FROM tahun  order by TahunID Desc"); //NA='N' and
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
<input type="submit" class='btn btn-success btn-sm' value='Lihat'>
</div>

</form>
</div>
</div>
</div>

<?php if ($_GET[act]==''){ 	                                   											   												
echo"            <div class='card'>
				<div class='card-header'>
				<div class='col-md-12'>
				<div class='box box-info'>
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>							  							
<div class='box box-info'>
<div class='box-body'>						
<div class='col-md-12'>
	<div class='table-responsive'>
<table class='table table-condensed table-sm table-striped'>                                          
<thead>					 
<tr>
<th>No</th>                        				
<th>Nama Hari</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>";
						
$tampil = mysqli_query($koneksi, "SELECT * from hari where HariID NOT IN ('0') order by HariID asc"); 									
while($r=mysqli_fetch_array($tampil)){   					         
$no++;
echo "<tr bgcolor=$warna>
<td>$no</td>
<td>$r[Nama] </td>
<td>
<a target='_BLANK' href='print_report/labl_uts.php?JadwalID=$r[JadwalID]&NamaKelas=$r[NamaKelas]&tahun=$_GET[tahun]&prodi=$_GET[prodi]&hari=$r[Nama]'> Cetak Label UTS</a>
| <a target='_BLANK' href='print_report/labl_uas.php?JadwalID=$r[JadwalID]&NamaKelas=$r[NamaKelas]&tahun=$_GET[tahun]&prodi=$_GET[prodi]&hari=$r[Nama]'> Cetak Label UAS</a>
</td> 

</tr>";
 }
echo"<tr bgcolor=$warna><td colspan='7'></td></tr>";	
echo "</tbody></table></div>";
echo "<div class='box-footer'></div>";
echo "</form>
</div>
</div>
</div>";
}
?>