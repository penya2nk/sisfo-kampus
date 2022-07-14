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
	   <a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=spmi/spmiadmppmimanualmutu&act=tambah&tahun=<?php echo $_GET['tahun']; ?>&prodi=<?php echo "$_GET[prodi]";?>'>Tambah Dokumen</a>
<div class='table-responsive'>
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
				 
				  echo "<a style='margin-right:5px; width:106px' class='btn btn-info btn-xs' title='View' href='$r[Link]' target=_blank><i class='fa fa-download'></i></span> Lihat Doc </a>";				  
				  echo "<a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=spmi/spmiadmppmimanualmutu&act=edit&prodi=$r[ProdiID]&id=$_GET[id]&tahun=$r[TahunID]&edit=$r[DokID]'><i class='fa fa-edit'></i></a>
							<a class='btn btn-danger btn-xs' title='Delete' href='index.php?ndelox=spmi/spmiadmppmimanualmutu&prodi=$r[ProdiID]&tahun=$r[TahunID]&hapus=$r[DokID]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-trash'></i></a>
				  </td>
				</tr>";
				
		  $no++;
		  }
		 // }
	  ?>
		</tbody>
	  </table></div>
	</div><!-- /.box-body -->
	<?php 
		if (isset($_GET['hapus'])){
			mysqli_query($koneksi, "DELETE FROM t_ppmidokumen where DokID='".strfilter($_GET['hapus'])."'");
			echo "<script>document.location='index.php?ndelox=spmi/spmiadmppmimanualmutu&prodi=".$_GET['prodi']."&id=".$_GET['id']."&tahun=".$_GET['tahun']."'</script>";
		  }
	?>
	</div>
</div>
<?php 
}

elseif($_GET['act']=='tambah'){
cek_session_admppmi();
if (isset($_POST['tambah'])){
        mysqli_query($koneksi, "INSERT INTO t_ppmidokumen(KategoriID,nama_file,TanggalBuat,Revisi,Keterangan) 
			  VALUES ('".strfilter($_POST['KategoriID'])."',
					  '".strfilter($_POST['nama_file'])."',
					  '".date('Y-m-d')."',
					  '".strfilter($_POST['Revisi'])."',
					  '".strfilter($_POST['Keterangan'])."')");
        echo "<script>document.location='index.php?ndelox=spmi/spmiadmppmimanualmutu&prodi=".$_POST['prodi']."&id=".$_GET['id']."&tahun=".$_POST['tahun']."';</script>";
      }

echo "<div class='col-md-12'>
		  <div class='box box-info'>
			<div class='box-header with-border'>
			  <h3 class='box-title'><b style='color:green;font-size:20px'>Tambah Dokumen</b></h3>
			</div>
		  <div class='box-body'>
		  <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
			<div class='col-md-12'>
			  <table class='table table-condensed table-bordered'>
			  <tbody>
				<input type=hidden name='tahun' value='$_GET[tahun]'>
				<input type=hidden name='prodi' value='$_GET[prodi]'>
				<tr><th width='220px' scope='row' >Nama Dokumen</th>        <td><input type='text' class='form-control' name='nama_file'></td></tr>
				<tr><th scope='row'>Kategori Dokumen</th>   <td><select class='form-control chzn-select' name='KategoriID'> 
					<option value='0' selected></option>";                                               
						$kat = mysqli_query($koneksi, "SELECT * FROM t_ppmikategori where UnitKerja='PPMI'");
						while($a = mysqli_fetch_array($kat)){
						  echo "<option value='$a[KategoriID]'>$a[Kategori]</option>";
						}
						echo "</select>
					</td></tr>		
				<tr><th scope='row'>Tanggal Revisi</th>      <td><input type='text' class='form-control' id=datepicker2 value='".date("Y-m-d")."' name='Revisi'></td></tr>
				<tr><th scope='row'>Keterangan</th>       <td><input type='text' class='form-control' name='Keterangan'></td></tr>
				
			  </tbody>
			  </table>
			</div>
			
		  </div>
		  <div class='box-footer'>
				<button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
				<a href='index.php?ndelox=spmi/spmiadmppmimanualmutu&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
				
			  </div>
		  </form>
		</div>";
}

elseif($_GET['act']=='edit'){
cek_session_admppmi();
if (isset($_POST['update'])){
        mysqli_query($koneksi, "UPDATE t_ppmidokumen SET 
				   nama_file      = '".strfilter($_POST['nama_file'])."',
				   Revisi         = '".strfilter($_POST['Revisi'])."',
				   Keterangan     = '".strfilter($_POST['Keterangan'])."' 
				   where DokID	  = '".strfilter($_GET['edit'])."'");
        echo "<script>document.location='index.php?ndelox=spmi/spmiadmppmimanualmutu&prodi=".$_POST['prodi']."&id=".$_GET['id']."&tahun=".$_POST['tahun']."';</script>";

      }


$edit = mysqli_query($koneksi, "SELECT * FROM t_ppmidokumen where DokID='".strfilter($_GET['edit'])."'");
    $s = mysqli_fetch_array($edit);
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'><b style='color:green;font-size:20px'>Edit Dokumen</b></h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                   <input type=hidden name='tahun' value='$_GET[tahun]'>
					<input type=hidden name='prodi' value='$_GET[prodi]'>
                    <tr><th scope='row'>Nama File</th>        <td><input type='text' class='form-control' name='nama_file' value='$s[nama_file]'></td></tr>
                    <tr><th style='width:210px' scope='row'>Kategori Dokumen</th>   <td><select class='form-control' name='KategoriID'> 
						<option value='0' selected>- Pilih Kategori Dokumen -</option>"; 
						$kat = mysqli_query($koneksi, "SELECT * from t_ppmikategori where UnitKerja='PPMI'");
						while($a = mysqli_fetch_array($kat)){
						  if ($s['KategoriID']==$a['KategoriID']){
							echo "<option value='$a[KategoriID]' selected>$a[Kategori]</option>";
						  }else{
							echo "<option value='$a[KategoriID]'>$a[Kategori]</option>";
						  }
						}
						echo "</select>
                    </td></tr>
					<tr><th scope='row'>Tanggal Revisi</th>      <td><input type='text' class='form-control' id=datepicker2 value='$s[Revisi]' name='Revisi'></td></tr>
                    <tr><th scope='row'>Keterangan</th>       <td><input type='text' class='form-control' name='Keterangan' value='$s[Keterangan]'></td></tr>
                    
                  </tbody>
                  </table>
                </div>
                
              </div>
              <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='index.php?ndelox=spmi/spmiadmppmimanualmutu&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type=button class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}

?>