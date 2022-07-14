<?php 
if ($_GET['act']==''){ 
?>
<div class='card'>
<div class='card-header'>
<div class='col-md-12'>
<div class='box box-info'>
	  <h3 class="box-title"><b style='color:green;font-size:20px'>Manajemen Dokumen Kebijakan Mutu</b> 					

	</div><!-- /.box-header -->
	<div class="box-body">			   
	
	  <table id="example" class="table table-sm table-striped">
		<thead>
		  <tr style="background:purple;color:white">
			<th style='width:20px'>No</th>
			<th>Nama Dokumen</th>
			<th>Keterangan</th>			
			<th>Action</th>
		  </tr>
		</thead>
		<tbody>
	  <?php
		$sql = mysqli_query($koneksi, "SELECT * from t_ppmidokumen where KategoriID='Kebijakan'"); //where KategoriID='$k[KategoriID]'
		while($r=mysqli_fetch_array($sql)){
		$no++;
		echo "<tr><td>$no</td>
				  <td>$r[nama_file]</td>
				  <td>$r[Keterangan]</td>
				  <td><a style='margin-right:5px; width:106px' class='btn btn-info btn-xs' title='View' href='$r[Link]' target=_blank><span class='glyphicon glyphicon-download'></span> Lihat Dok </a>			  
				  </td>";		
		  $no++;
		  }
	  ?>
		</tbody>
	  </table>
	</div><!-- /.box-body -->

	</div>
</div>
<?php 
}

?>