
<div class='card'>
<div class='card-header'>
	<div class="form-group row">
	<label class="col-md-8 col-form-label text-md-right"><b style='color:purple'>Angka Mahasiswa Aktif Prodi</b></label>
	<div class="col-md-2">               
	<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
	<input type="hidden" name='ndelox' value='reports/angka.mhsaktif'>                                   
	<select name='prodi' class='form-control form-control-sm' onChange='this.form.submit()'>
	<?php 
	echo "<option value='' >- Pilih Program Studi -</option>";
	echo "<option value='All' selected>Seluruh Program Studi</option>";
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
               
					

<?php if ($_GET['act']==''){ ?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id='example' class='table table-sm table-striped'>
<thead>
<tr style='background:purple;color:white'>
  <th style='width:10px;text-align:center' >No <br>(1)</th>                           
  <th style='width:200px;text-align:center'>Tahun <br>(2)</th>                        
  <th style='width:200px;text-align:center'>Reguler <br>(4)</th>
  <th style='width:200px;text-align:center'>Transfer <br>(5)</th>
  <th style='width:200px;text-align:center'>Pindahan <br>(6)</th> 
  <th style='width:200px;text-align:center'>Total <br>(4+5+6) <br></th>                                
  <th style='width:200px;text-align:center'>Action <br>(10)</center></th> 
</tr> 
</thead>
<tbody>
          
<?php
if (isset($_GET['prodi'])){			     
		$tahun = mysqli_query($koneksi, "SELECT * FROM t_tahunakademik GROUP BY TahunID order by TahunID DESC");
		while ($r = mysqli_fetch_array($tahun)){
		$nom++;
		if ($_GET['prodi']=='IKM')	{	
			//sub berdasarkan tahun -------------------------------------------------------------------------
			$ikm = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
											  FROM krs 
											  WHERE SUBSTR(MhswID,3,2)='01'
											  AND TahunID='$r[TahunID]'											  
											  AND SUBSTR(MhswID,5,1)='1'
											  GROUP BY MhswID")); //1 reguler
			$tra = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
											  FROM krs 
											  WHERE SUBSTR(MhswID,3,2)='01'
											  AND TahunID='$r[TahunID]' 								
											  AND SUBSTR(MhswID,5,1)='2' 
											  GROUP BY MhswID")); //2 transfer
			$tra2 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
											  FROM krs 
											  WHERE SUBSTR(MhswID,3,2)='01'
											  AND TahunID='$r[TahunID]' 								
											  AND SUBSTR(MhswID,5,1)='5' 
											  GROUP BY MhswID")); //5 transfer kode lama	
			//tranfer nim lama + nim baru
			$tranfer = $tra + $tra2 ;								  							  
			$pin = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
											  FROM krs 
											  WHERE SUBSTR(MhswID,3,2)='07'
											  AND TahunID='$r[TahunID]'											  
											  AND SUBSTR(MhswID,5,1)='3'
											  GROUP BY MhswID")); //3 pindahan									  
			$subtot = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
											  FROM krs 
											  WHERE SUBSTR(MhswID,3,2)='07'
											  AND TahunID='$r[TahunID]'											  
											  GROUP BY MhswID")); //
																			  
			
			} //tutup prodi SI
		else if($_GET['prodi']=='PSIK'){
		    $reg = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
											  FROM krs 
											  WHERE SUBSTR(MhswID,3,2)='03'
											  AND TahunID='$r[TahunID]'											  
											  AND SUBSTR(MhswID,5,1)='1'
											  GROUP BY MhswID")); //1 reguler
			$tra = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
											  FROM krs 
											  WHERE SUBSTR(MhswID,3,2)='08'
											  AND TahunID='$r[TahunID]' 								
											  AND SUBSTR(MhswID,5,1)='2' 
											  GROUP BY MhswID")); //2 transfer
			$tra2 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
											  FROM krs 
											  WHERE SUBSTR(MhswID,3,2)='08'
											  AND TahunID='$r[TahunID]' 								
											  AND SUBSTR(MhswID,5,1)='5' 
											  GROUP BY MhswID")); //5 transfer kode lama	
			//tranfer nim lama + nim baru
			$tranfer = $tra + $tra2 ;								  							  
			$pin = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
											  FROM krs 
											  WHERE SUBSTR(MhswID,3,2)='08'
											  AND TahunID='$r[TahunID]'											  
											  AND SUBSTR(MhswID,5,1)='3'
											  GROUP BY MhswID")); //3 pindahan									  
			$subtot = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
											  FROM krs 
											  WHERE SUBSTR(MhswID,3,2)='08'
											  AND TahunID='$r[TahunID]'											  
											  GROUP BY MhswID")); //
											
										  
		
		} //tutup prodi TI
	else if($_GET['prodi']=='All'){
			$reg = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
											  FROM krs 
											  WHERE TahunID='$r[TahunID]'											  
											  AND SUBSTR(MhswID,5,1)='1'
											  GROUP BY MhswID")); //1 reguler
			$tra = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
											  FROM krs 
											  WHERE TahunID='$r[TahunID]' 								
											  AND SUBSTR(MhswID,5,1)='2' 
											  GROUP BY MhswID")); //2 transfer
			$tra2 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
											  FROM krs 
											  WHERE TahunID='$r[TahunID]' 								
											  AND SUBSTR(MhswID,5,1)='5' 
											  GROUP BY MhswID")); //5 transfer kode lama	
			//tranfer nim lama + nim baru
			$tranfer = $tra + $tra2 ;								  							  
			$pin = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
											  FROM krs 
											  WHERE TahunID='$r[TahunID]'											  
											  AND SUBSTR(MhswID,5,1)='3'
											  GROUP BY MhswID")); //3 pindahan									  
			$subtot = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
											  FROM krs 
											  WHERE TahunID='$r[TahunID]'											  
											  GROUP BY MhswID")); //
											
		
		} //tutup prodi Semua Prodi

	
		echo "<tr>
		<td align=center>$nom</td>
		<td align=center>$r[TahunID]</td>
		<td align=center>$ikm</td>
		<td align=center>$tranfer</td>
		<td align=center>$pin</td>
		<td align=center>$subtot</td>";
echo "<td style='width:70px !important'><center>

<a  class='btn btn-success btn-xs' href='index.php?ndelox=AngkaPenerimaan&act=detail&LulusanID=$r[LulusanID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&DosenID=$r[DosenID]'><span class='glyphicon glyphicon-th'></span> View</a>

<a  class='btn btn-warning btn-xs' target='_BLANK' href='print_reportxls/x.php?tahun=$r[TahunID]'><span class='glyphicon glyphicon-book'></span> Export</a>
</center>
</td></tr>";
		

}//tutup while
	echo"</table>
	</div>
</div>
</div>";	
	
}
?>
<div class='table-responsive'>
<table id="example" class="table table-bordered table-striped">
<thead>
<tr><td ><a href='?ndelox=angkamhsaktif&act=grafmhsaktif&prodi=<?php echo"$_GET[prodi]";?>&prd=<?php echo"$prd";?>'>Grafik Penerimaan Mahasiswa Baru</td></tr>
</table>
<tbody>
</table></div>


</div><!-- /.box-body -->
</div>
</div>
<?php 
if ($_GET['prodi'] == '' ){
    echo "<center style='padding:60px; color:red'>Tentukan Program Studi Terlebih dahulu...</center>";
}


}
   

else if($_GET['act']=='grafmhsaktif'){
$prodi=mysqli_fetch_array(mysqli_query($koneksi, "select ProdiID,Nama from prodi where ProdiID='$_GET[prodi]'"));
?>
<script type="text/javascript" src="plugins/jQuery/jquery.min.js"></script>
<script type="text/javascript">
$(function () {
	$('#container').highcharts({
		data: {
			table: 'datatable'
		},
		chart: {
			type: 'column'
		},
		title: {
			text: ''
		},
		yAxis: {
			allowDecimals: false,
			title: {
				text: ''
			}
		},
		tooltip: {
			formatter: function () {
				return '<b> ' + this.series.name + '</b><br/>' +
					' ' + this.point.y + ' Orang';
			}
		}
	});
});
</script>

<div class='col-xs-12'>
    <div class='box box-info'>
<div class="box-header">
<i class="fa fa-th-list"></i>
<h3 class="box-title"><b style=color:green;>Grafik Mahasiswa Aktif </b><b style=color:#FF8306;><?php echo"$prd";?></b></h3>
<p></p>
<div class="box-tools pull-right">
<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
</div>
</div>

<div class="box-body chat" id="chat-box">
<script src="plugins/highchart/highcharts.js"></script>
<script src="plugins/highchart/modules/data.js"></script>
<script src="plugins/highchart/modules/exporting.js"></script>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<table id="datatable" style='display:none'>
<thead>
<tr>
    <th></th>
    <th>Reguler</th>
    <th>Transfer</th>
    <th>Pindahan</th>
    <th>Total</th> 
</tr>
</thead>
<tbody>
<?php 
//group by harus sama dengan kriteria where kemudian ambil tahun format yy
$grafik = mysqli_query($koneksi, "SELECT * FROM t_tahunakademik  GROUP BY TahunID");	
while($r = mysqli_fetch_array($grafik)){
if ($_GET['prodi']=='SI'){	
	$reg = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
									  FROM krs 
									  WHERE SUBSTR(MhswID,3,2)='07'
									  AND TahunID='$r[TahunID]'											  
									  AND SUBSTR(MhswID,5,1)='1'
									  GROUP BY TahunID")); //1 reguler
	$tra = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
									  FROM krs 
									  WHERE SUBSTR(MhswID,3,2)='07'
									  AND TahunID='$r[TahunID]' 								
									  AND SUBSTR(MhswID,5,1)='2' 
									  GROUP BY MhswID")); //2 transfer
	$tra2 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
									  FROM krs 
									  WHERE SUBSTR(MhswID,3,2)='07'
									  AND TahunID='$r[TahunID]' 								
									  AND SUBSTR(MhswID,5,1)='5' 
									  GROUP BY MhswID")); //5 transfer kode lama	
	//tranfer nim lama + nim baru
	$tranfer = $tra + $tra2 ;								  							  
	$pin = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
									  FROM krs 
									  WHERE SUBSTR(MhswID,3,2)='07'
									  AND TahunID='$r[TahunID]'											  
									  AND SUBSTR(MhswID,5,1)='3'
									  GROUP BY MhswID")); //3 pindahan									  
	$subtot = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
									  FROM krs 
									  WHERE SUBSTR(MhswID,3,2)='07'
									  AND TahunID='$r[TahunID]'											  
									  GROUP BY MhswID")); //																  
	} //tutup prodi SI
else if ($_GET['prodi']=='TI'){
		$reg = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
									  FROM krs 
									  WHERE SUBSTR(MhswID,3,2)='08'
									  AND TahunID='$r[TahunID]'											  
									  AND SUBSTR(MhswID,5,1)='1'
									  GROUP BY MhswID")); //1 reguler
	$tra = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
									  FROM krs 
									  WHERE SUBSTR(MhswID,3,2)='08'
									  AND TahunID='$r[TahunID]' 								
									  AND SUBSTR(MhswID,5,1)='2' 
									  GROUP BY MhswID")); //2 transfer
	$tra2 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
									  FROM krs 
									  WHERE SUBSTR(MhswID,3,2)='08'
									  AND TahunID='$r[TahunID]' 								
									  AND SUBSTR(MhswID,5,1)='5' 
									  GROUP BY MhswID")); //5 transfer kode lama	
	//tranfer nim lama + nim baru
	$tranfer = $tra + $tra2 ;								  							  
	$pin = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
									  FROM krs 
									  WHERE SUBSTR(MhswID,3,2)='08'
									  AND TahunID='$r[TahunID]'											  
									  AND SUBSTR(MhswID,5,1)='3'
									  GROUP BY MhswID")); //3 pindahan									  
	$subtot = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
									  FROM krs 
									  WHERE SUBSTR(MhswID,3,2)='08'
									  AND TahunID='$r[TahunID]'											  
									  GROUP BY MhswID")); //																  
	} //tutup prodi SI
else if ($_GET['prodi']=='All'){
	$reg = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
									  FROM krs 
									  WHERE TahunID='$r[TahunID]'											  
									  AND SUBSTR(MhswID,5,1)='1'
									  GROUP BY MhswID")); //1 reguler
	$tra = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
									  FROM krs 
									  WHERE TahunID='$r[TahunID]' 								
									  AND SUBSTR(MhswID,5,1)='2' 
									  GROUP BY MhswID")); //2 transfer
	$tra2 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
									  FROM krs 
									  WHERE TahunID='$r[TahunID]' 								
									  AND SUBSTR(MhswID,5,1)='5' 
									  GROUP BY MhswID")); //5 transfer kode lama	
	//tranfer nim lama + nim baru
	$tranfer = $tra + $tra2 ;								  							  
	$pin = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
									  FROM krs 
									  WHERE TahunID='$r[TahunID]'											  
									  AND SUBSTR(MhswID,5,1)='3'
									  GROUP BY MhswID")); //3 pindahan									  
	$subtot = mysqli_num_rows(mysqli_query($koneksi, "SELECT * 
									  FROM krs 
									  WHERE TahunID='$r[TahunID]'											  
									  GROUP BY MhswID")); //
} //Tutup All
				
	echo "<tr>
	<th>$r[TahunID]</th>
	<td>$reg</td>
	<td>$tranfer</td>
	<td>$pin</td>
	<td>$tot</td>
	</tr>";
}
	?>
</tbody>
</table>
</div>
<?php 
$pm = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM t_pengumuman"));  
echo"<b style='color:green;'><marquee onmouseout='this.start()' onmouseover='this.stop()'>$pm[Pengumuman]</marquee></b>"; 
}
?>
</div>

</div>
