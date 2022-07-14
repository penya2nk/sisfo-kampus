<?php 
if ($_GET['act']==''){ 
?>
<div class='card'>
<div class='card-header'>
<div class='col-md-12'>
<div class='box box-info'>
	      
	      
	      <b style='color:green;font-size:20px'>Manajemen Dokumen SOP</b> 		&nbsp; <a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=admdoksopppmi&act=carisop&tahun=<?php echo $_GET['tahun']; ?>&prodi=<?php echo "$_GET[prodi]";?>'>Cari SOP</a>
	   
	   		
	</div><!-- /.box-header -->
	<div class="box-body">			   
	   
	   
	   
	  <table id="example" class="table table-sm table-striped">
		<thead>
		    <tr style="background:purple;color:white">
			<th style='width:20px'>No</th>
			<th style='width:450px'>Nama Dokumen SOP</th>
			<th>Keterangan</th>			
			<th>Action</th>
		  </tr>
		</thead>
		<tbody>
	  <?php
		$sq = mysqli_query($koneksi, "SELECT * from t_ppmikategorisop ");
        while($k=mysqli_fetch_array($sq)){		
		echo "<tr><td colspan=5 style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:bold;background-color:#e62899;>$k[Nama]</td>";
		$sql = mysqli_query($koneksi, "SELECT * from t_ppmisop where KategoriSOPID='$k[KategoriSOPID]'");
		$no=1;
		while($r=mysqli_fetch_array($sql)){
		$sopx 		= strtolower($r['nama_file']);
		$sop		= ucwords($sopx);
		$dc 		= $r['Link'];
		if ($dc=='-'){
		   $st="<b style=color:red>Dokumen Blm Tersedia</b>";
		}else{
		   $st="<b style=color:green>Dokumen Tersedia</b>";
		}
		echo "<tr><td>$no</td>
				  <td>$sop</td>
				  <td>$st</td>";
				  echo "<td>";				 
				  
echo "<a style='margin-right:5px; width:106px' class='btn btn-info btn-xs' title='View' href='$r[Link]' target=_blank><span class='glyphicon glyphicon-download'></span> Lihat Dok </a>				  
</td>
</tr>";
				
		  $no++;
		  }
		  }
	  ?>
		</tbody>
	  </table>
	</div><!-- /.box-body -->

	</div>
</div>
<?php 
}

elseif($_GET['act']=='carisop'){
?>
<div class="col-xs-12">  
<div class="col-xs-12">  
  <div class="box">
	<div class="box-header">
	  <h3 class="box-title"><b style='color:green;font-size:20px'>Manajemen Dokumen SOP &nbsp;<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=admppmisop&act=carisop&tahun=<?php echo $_GET[tahun]; ?>&prodi=<?php echo "$_GET[prodi]";?>'>Cari SOP</a>
	   
	   <a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=admdoksopppmi&tahun=<?php echo $_GET[tahun]; ?>&prodi=<?php echo "$_GET[prodi]";?>'>Back </a></b> 					
	</div><!-- /.box-header -->
	<div class="box-body">
	    
   
<table id="example1" class="table table-bordered table-striped">
		<thead>
		  <tr>
			<th style='width:20px'>No</th>
			<th style='width:450px'>Nama Dokumen SOP</th>
			<th>Keterangan</th>			
			<th>Action</th>
		  </tr>
		</thead>
		<tbody>
	  <?php

		$sql = mysqli_query($koneksi, "SELECT * from t_ppmisop order by KategoriSOPID asc");
		$no=1;
		while($r=mysqli_fetch_array($sql)){
		$sopx 		= strtolower($r['nama_file']);
		$sop		= ucwords($sopx);
		$dc 		= $r['Link'];
		if ($dc=='-'){
		   $st="<b style=color:red>Dokumen Blm Tersedia</b>";
		}else{
		   $st="<b style=color:green>Dokumen Tersedia</b>";
		}
		echo "<tr><td>$no</td>
				  <td>$sop</td>
				  <td>$st</td>";
				  echo "<td>
<a style='margin-right:5px; width:106px' class='btn btn-info btn-xs' title='View' href='$r[Link]' target=_blank><span class='glyphicon glyphicon-download'></span> Lihat Dok </a>				  

</td>
</tr>";

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