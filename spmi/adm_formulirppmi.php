<?php 
if ($_GET['act']==''){ 
//cek_session_admin();
?>
<div class='card'>
<div class='card-header'>
<div class='col-md-12'>
<div class='box box-info'>
	  <h3 class="box-title"><b style='color:green;font-size:20px'>Manajemen Dokumen Manual Mutu</b> 					
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

		//$sq = mysqli_query($koneksi, "SELECT * from t_ppmikategori where UnitKerja='PPMI' ");
        //while($k=mysqli_fetch_array($sq)){
		//echo "<tr><td colspan=5 style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>$k[Kategori]</td>";
		$sql = mysqli_query($koneksi, "SELECT * from t_ppmidokumen Where KategoriID='Formulir'"); //where KategoriID='$k[KategoriID]'
		$no=1;
		while($r=mysqli_fetch_array($sql)){
		echo "<tr><td>$no</td>
				  <td>$r[nama_file]</td>
				  <td>$r[Keterangan]</td>";
				  echo "<td>";				 				
				  echo "<a style='margin-right:5px; width:106px' class='btn btn-info btn-xs' title='View' href='$r[Link]' target=_blank><span class='glyphicon glyphicon-download'></span> Lihat Dok </a>";				  
				  echo "
				  </td>
				</tr>";
				
		  $no++;
		  }
		 // }
	  ?>
		</tbody>
	  </table>
	</div><!-- /.box-body -->

	</div>
</div>
<?php
}
?>