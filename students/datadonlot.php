<?php 
if ($_GET['act']==''){ 

?>	
<div class='card'>
<div class='card-header'>
<div class='col-md-12'>
<div class='box box-info'>
	  <h3 class="box-title">
		  <b style='color:green;font-size:20px'>Download</b> 
	  </h3>
	 
	<div class="box-body">	
		<div class="table-responsive">
	  <table id="example" class="table table-sm table-striped">
		<thead>
		  <tr style="background:purple;color:white">
			<th style='width:20px'>No</th>
			<th>Berkas</th>
			<th>Tanggal Upload</th>
		    <th>Keterangan</th>
			<th>Action</th>
		  </tr>
		</thead>
		<tbody>
	  <?php
		$tampil = mysqli_query($koneksi, "SELECT * from t_unduhfile  ORDER BY UnduhID DESC");	//where ProdiID='$_SESSION[prodi]'	
		$no = 1;
		while($r=mysqli_fetch_array($tampil)){
		//$total = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM rb_elearning where kodejdwl='$r[kodejdwl]'"));
		echo "<tr><td>$no</td>
				  <td>$r[nama_file]</td>
				  <td>".tgl_indo($r['TanggalBuat'])."</td>
				  <td>$r[Keterangan]</td>";
				if ($_SESSION['_LevelID']=='1'){
				  echo "<td>";
				  echo "<a style='margin-right:5px; width:106px' class='btn btn-info btn-xs' title='Download' href='$r[URL]' target=_BLANK><i class='fa fa-download'></i> Download </a>";   
				  //echo "<a style='margin-right:5px; width:106px' class='btn btn-info btn-xs' title='Download' href='sedotmhs.php?file=$r[file_upload]'><span class='glyphicon glyphicon-download'></span> Download </a>";                                 
				  echo "<a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=datadonlot&act=edit&prodi=$r[ProdiID]&id=$_GET[id]&tahun=$r[TahunID]&edit=$r[UnduhID]'><i class='fa fa-edit'></i></a>
							<a class='btn btn-danger btn-xs' title='Delete' href='index.php?ndelox=datadonlot&prodi=$r[ProdiID]&tahun=$r[TahunID]&hapus=$r[UnduhID]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-trash'></i></a>
				  </td>
				</tr>";
				}elseif ($_SESSION['_LevelID']=='120'){
				  $sekarangwaktu = date("YmdHis");
				  $bataswaktu1 = str_replace('-','',$r['TanggalSelesai']);
				  $bataswaktu2 = str_replace(':','',$bataswaktu1);
				  $bataswaktu3 = str_replace(' ','',$bataswaktu2);
				  
				  if ($sekarangwaktu < $bataswaktu3 OR $bataswaktu3 == '00000000000000'){
					  echo "<td><a class='btn btn-info btn-xs' title='Download' href='$r[URL]' target=_BLANK><i class='fa fa-download'></i> Download</a></td></tr>";
					  //echo "<td><a class='btn btn-info btn-xs' title='Download' href='sedotmhs.php?file=$r[file_upload]'><span class='glyphicon glyphicon-download'></span> Download</a></td></tr>";
				  }else{
					  echo "<td><a style='width:167px' class='btn btn-danger btn-xs' title='Waktu Habis' href=''><span class='glyphicon glyphicon-remove'></span> Waktu Habis</a></td></tr>";
				  }
			  }		
				echo "</tr>";
		  $no++;
		  }
	  ?>
		</tbody>
	  </table></div>
	</div><!-- /.box-body -->	
	</div>
</div>
<?php 	
}
?>