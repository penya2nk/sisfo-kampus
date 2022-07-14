<?php 
if ($_GET['act']==''){ 
//cek_session_admin();
?>
<div class="col-xs-12">  
  <div class="box">
	<div class="box-header">
	  <h3 class="box-title"><b style='color:green;font-size:20px'>Manajemen Dokumen Intruksi Kerja</b> 					

	</div><!-- /.box-header -->
	<div class="box-body">			   
	   <a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=admppmimanualmutu&act=tambah&tahun=<?php echo $_GET[tahun]; ?>&prodi=<?php echo "$_GET[prodi]";?>'>Tambah Dokumen</a>

	  <table id="example1" class="table table-bordered table-striped">
		<thead>
		  <tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:bold;background-color:#e62899;>
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
		$sql = mysqli_query($koneksi, "SELECT * from t_ppmidokumen "); //where KategoriID='$k[KategoriID]'
		$no=1;
		while($r=mysqli_fetch_array($sql)){
		$dc 		= $r['Link'];
		if ($dc=='-'){
		   $st="<b style=color:red>Dokumen Blm Tersedia</b>";
		}else{
		   $st="<b style=color:green>Dokumen Tersedia</b>";
		}
		
		echo "<tr><td>$no</td>
				  <td>$r[nama_file]</td>
				  <td>$st</td>";
				  echo "<td>";
				 
				  echo "<a style='margin-right:5px; width:106px' class='btn btn-info btn-xs' title='View' href='$r[Link]' target=_blank><span class='glyphicon glyphicon-download'></span> Lihat Doc </a>";				  
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