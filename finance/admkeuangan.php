<div class='card'>
<div class='card-header'>


<h3 class="box-title">
<?php 
if (isset($_GET['MhswID'])){
 $pr = mysqli_fetch_array(mysqli_query($koneksi, "SELECT a.MhswID, a.Nama AS NamaMhs, a.ProdiID, a.ProgramID, b.Nama AS NamaProdi
	FROM mhsw a 
	LEFT JOIN prodi b ON a.ProdiID=b.ProdiID
	WHERE a.MhswID='".strfilter($_GET['MhswID'])."'"));   
     echo "<b style='color:green;font-size:20px'>BIAYA PENDIDIKAN</b>";
  }else{ 
     echo "<b style='color:green;font-size:20px'>BIAYA PENDIDIKAN</b>"; 
  } 
?>
</h3>

 <div class="form-group row">
		<label class="col-md-8 col-form-label text-md-right"><b style='color:purple'>BIAYA PENDIDIKAN</b></label>
		<div class="col-md-2">
			<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
			<input type="hidden" name='ndelox' value='finance/admkeuangan'>
			<input type='text' class='form-control form-control-sm form-control form-control-sm-sm'  placeholder="NIM" name='MhswID' value='<?php echo"$_GET[MhswID]";?>'>
		</div>
		<div class="col-md-1">
			<input type="submit" class='btn btn-success btn-sm' value='Lihat'>
			</form>
		</div>

		<div class="col-md-1">
		<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=finance/admkeuangan&act=tambah&MhswID=<?php echo"$_GET[MhswID]";?>&prodi=<?php echo"$m[ProdiID]";?>&tahun=<?php echo"$_GET[tahun]";?>'>PEMBAYARAN</a><?php  ?>

		</div>

</div>
</div>
</div>

<?php
echo"<div class='card'>
<div class='card-header'>
		<div class='table-responsive'>
		<table class='table table-sm table-bordered'>
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
		</table>
		</div>
	</div>										   
</div>"; 
?>

<?php
echo"<div class='card'>
<div class='card-header'>
		<table align=center class='table table-sm table-bordered' style='width:50%'>           
		<tr >
		<th style='width:200px'><a href='?ndelox=finance/admkeuangan&MhswID=$_GET[MhswID]'>BIAYA SPP</a></th>
		<th style='width:200px'><a href='?ndelox=finance/admkeuangan&act=biayalainnya&MhswID=$_GET[MhswID]'>BIAYA LAINNYA</a></th>
		<th style='width:200px'><a href='?ndelox=finance/admkeuangan&act=tunggakan&MhswID=$_GET[MhswID]'>TUNGGAKAN</a></th>
		</tr>									      			
		</table>
	</div>										   
</div>"; 
?>

<?php if ($_GET['act']==''){ ?> 
	<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example" class="table table-sm table-striped">
<thead>
  <tr style='background:purple;color:white'>
    <th style='width:40px;text-align:center'>No</th>	   
    <th style='width:80px;text-align:center'>TahunID</th>
    <th align=right style='width:140px;text-align:right'>Jumlah </th>
    <th style='width:100px;text-align:center'>Tanggal</th>    
    <th style='width:200px'>Keterangan</th>		
     <th style='width:100px'>No Bukti</th>
    <th style='width:70px;text-align:center'>Action</th>                      
  </tr>
</thead>
<tbody>
  <?php 
	$tampil = mysqli_query($koneksi, "SELECT keuangan_bayar.*,mhsw.Nama from mhsw,keuangan_bayar 
	WHERE keuangan_bayar.MhswID=mhsw.MhswID 
	AND keuangan_bayar.MhswID='".strfilter($_GET['MhswID'])."'
	AND keuangan_bayar.id_jenis IN ('','SPP') ORDER BY TahunID Desc");
	$no = 1;
	while($r=mysqli_fetch_array($tampil)){
	if ($r['Keterangan']<>'Lunas'){
		$c="style=color:red";
	}else{
		$c="style=color:green";
	}	
		$tot += $r['total_bayar'];
	echo "<tr><td style='text-align:center'>$no</td>			 
			  <td style='text-align:center'>$r[TahunID]</td>
			  <td align=right>Rp. ".number_format($r['total_bayar'],0)."</td>
			  <td style='text-align:center'>".tgl_indo($r['TanggalBayar'])."</td>			  
			  <td $c>$r[Keterangan]</td>
			  <td>$r[NoBukti]</td>";
			  if($_SESSION['level']!='kepala'){
		echo "<td><center>
		<a class='btn btn-primary btn-xs' title='Delete Data' href='index.php?ndelox=finance/admkeuangan&act=edit&id=$r[id_keu]&tahun=$_GET[tahun]&prodi=$pr[ProdiID]&MhswID=$_GET[MhswID]'><i class='fa fa-edit'></i></a>
				<a class='btn btn-primary btn-xs' title='Delete Data' href='index.php?ndelox=finance/admkeuangan&hapus=$r[id_keu]&tahun=$_GET[tahun]&prodi=$pr[ProdiID]&MhswID=$_GET[MhswID]' onclick=\"return confirm('Anda tidak memiliki akses di keuangan!')\"><i class='fa fa-trash'></i></a>
			  </center></td>";
			  }
			echo "</tr>";
	  $no++;
	  }
	  if (isset($_GET['hapus'])){
		  mysqli_fetch_array($koneksi, "DELETE FROM keuangan_bayar where id_keu='".strfilter($_GET['hapus'])."'");
		  echo "<script>document.location='index.php?ndelox=finance/admkeuangan&tahun=$_GET[tahun]&prodi=$_GET[prodi]&MhswID=$_GET[MhswID]&sukses';</script>";
	  }
  ?>
</tbody>
</table>
</div>
</div>
</div>

<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
 <table id="example" class="table table-sm table-striped">
	<thead>
   <tr style='background:purple;color:white'><td colspan='8'><b>Total Biaya SPP yang Sudah Dibayarkan  : Rp. <?php echo"".number_format($tot,0).""; echo" (<i>".terbilang($tot)." rupiah</i> )";?> </b> </td></tr>
  </tbody>
  </table>
  </div>
</div>
</div>


<?php } else if ($_GET['act']=='biayalainnya'){ ?> 

  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example" class="table table-sm table-striped">
<thead>
<tr style='background:purple;color:white'>
   <th style='width:40px;text-align:center'>No</th>	   
    <th align=right style='width:160px'>JenisByr</th>
    <th style='width:100px;text-align:center'>TahunID</th>
    <th align=right style='width:140px;text-align:right'>Jumlah </th>
    <th style='width:100px;text-align:center'>Tanggal</th>    
    <th style='width:200px'>Keterangan</th>		
     <th style='width:100px'>No Bukti</th>
    <th style='width:70px;text-align:center'>Action</th>                          
</tr>
</thead>
<tbody>
  <?php 
	$tampil = mysqli_query($koneksi, "SELECT keuangan_bayar.*,mhsw.Nama from mhsw,keuangan_bayar 
	WHERE keuangan_bayar.MhswID=mhsw.MhswID 
	AND keuangan_bayar.MhswID='".strfilter($_GET['MhswID'])."'
	AND keuangan_bayar.id_jenis NOT IN ('','SPP')");
	$no = 1;
	while($r=mysqli_fetch_array($tampil)){
	if ($r['Keterangan']<>'Lunas'){
		$c="style=color:red";
	}else{
		$c="style=color:green";
	}	
		$tot += $r[total_bayar];
	echo "<tr><td style='text-align:center'>$no</td>			 
			  <td>$r[id_jenis]</td>
			  <td style='text-align:center'>$r[TahunID]</td>
			  <td align=right>Rp. ".number_format($r['total_bayar'],0)."</td>
			  
			  <td style='text-align:center'>".tgl_indo($r['TanggalBayar'])."</td>
			  <td $c>$r[Keterangan]</td>
			  <td>$r[NoBukti]</td>";
			  if($_SESSION[level]!='kepala'){
		echo "<td><center>
		<a class='btn btn-primary btn-xs' title='Delete Data' href='index.php?ndelox=finance/admkeuangan&act=edit&id=$r[id_keu]&tahun=$_GET[tahun]&prodi=$pr[ProdiID]&MhswID=$_GET[MhswID]'><i class='fa fa-edit'></i></a>
			<a class='btn btn-primary btn-xs' title='Delete Data' href='index.php?ndelox=finance/admkeuangan&hapus=$r[id_keu]&tahun=$_GET[tahun]&prodi=$pr[ProdiID]&MhswID=$_GET[MhswID]' onclick=\"return confirm('Anda tidak memiliki akses di keuangan!')\"><i class='fa fa-trash'></i></a>
			  </center></td>";
			  }
			echo "</tr>";
	  $no++;
	  }
	  if (isset($_GET['hapus'])){
		  mysqli_fetch_array($koneksi, "DELETE FROM keuangan_bayar where id_keu='".strfilter($_GET['hapus'])."'");
		  echo "<script>document.location='index.php?ndelox=finance/admkeuangan&tahun=$_GET[tahun]&prodi=$_GET[prodi]&MhswID=$_GET[MhswID]&upss';</script>";
	  }
  ?>
	</tbody>
  </table>

  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
 <table id="example" class="table table-sm table-striped">
	<thead>
   <tr style='background:purple;color:white'><td colspan='8'><b>Total Biaya-Biaya Lainnya Yang Sudah Dibayarkan  : Rp. <?php echo"".number_format($tot,0).""; echo" (<i>".terbilang($tot)." rupiah</i> )";?> </b> </td></tr>
  </tbody>
  </table> 
  </div>
</div>
</div>

<?php } else if ($_GET['act']=='tunggakan'){ ?> 

  <div class='card'>
<div class='card-header'>
<div class='table-responsive'> 
<table id="example" class="table table-sm table-striped">
<thead>
<tr style='background:purple;color:white'>
   <th style='width:40px;text-align:center'>No</th>	   
    <th align=right style='width:120px'>Jenis Tunggakan</th>
    <th style='width:100px'>TahunID</th> 
    <th style='width:200px'>Keterangan</th>		    
    <th style='width:70px;text-align:center'>Action</th>                          
</tr>
</thead>
<tbody>
  <?php 
	$tampil = mysqli_query($koneksi, "SELECT * from khs 
	WHERE MhswID='".strfilter($_GET['MhswID'])."'
	AND StatusMhswID='P'");
	$no = 1;
	while($r=mysqli_fetch_array($tampil)){
	if ($r['Keterangan']<>'Lunas'){
		$c="style=color:red";
	}else{
		$c="style=color:green";
	}
	if ($r['StatusMhswID']=='P'){
		$st="Belum Lunas";
	}else{
		$st="Lunas";
	}		
		//$tot += $r[total_bayar];
	echo "<tr><td style='text-align:center'>$no</td>			 
			  <td>SPP</td>
			  <td>$r[TahunID]</td>			  			  			 
			  <td $c>$st</td>";
			  if($_SESSION['level']!='kepala'){
		echo "<td><center>
			<a class='btn btn-primary btn-xs' title='Delete Data' href='index.php?ndelox=finance/admkeuangan&hapus=$r[id_keuxx]&tahun=$_GET[tahun]&prodi=$pr[ProdiID]&MhswID=$_GET[MhswID]' onclick=\"return confirm('Anda tidak memiliki akses di keuangan!')\"><i class='fa fa-trash'></i></a>
			  </center></td>";
			  }
			echo "</tr>";
	  $no++;
	  }
	  if (isset($_GET['hapus'])){
		  mysqli_fetch_array($koneksi, "DELETE FROM keuangan_bayar where id_keu='".strfilter($_GET['hapus'])."'");
		  echo "<script>document.location='index.php?ndelox=finance/admkeuangan&tahun=$_GET[tahun]&prodi=$_GET[prodi]&MhswID=$_GET[MhswID]&upss';</script>";
	  }
  ?>
	</tbody>
  </table>
  </div>
</div>
</div>

 <table id="example" class="table table-sm table-striped">
	<thead>
   <!--<tr bgcolor='#CCFF33'><td colspan='8'><b>Total Tunggakan Yang Belum Dilunasi  : Rp. <?php echo"".number_format($totx,0).""; echo" (<i>".terbilang($tot)." rupiah</i> )";?> </b> </td></tr> -->
  </tbody>
  </table>  
  </div>
</div>
</div>




<?php 
//========================================================================================================================
}elseif($_GET['act']=='edit'){
    if (isset($_POST['update'])){
		$nominal 	= $_POST['total_bayar'];
		$angka1		= str_replace(".", "", $nominal);
        $query 		= mysqli_query($koneksi, "UPDATE keuangan_bayar SET 
									TahunID 		= '".strfilter($_POST['TahunID'])."',
									total_bayar 	= '$angka1',
									LoginEdit 		= '$_SESSION[_Login]',
									TanggalEdit 	= '".date('Y-m-d')."',
									TanggalBayar 	= '".strfilter($_POST['TanggalBayar'])."',
									keterangan 		= '".strfilter($_POST['keterangan'])."',
									NoBukti 		= '".strfilter($_POST['NoBukti'])."'									
									where id_keu	= '".strfilter($_POST['id'])."'");
        //if ($_POST[id_jenis]=='SPP'){
		//    mysqli_fetch_array("update khs set StatusMhswID='A' where MhswID='".strfilter($_POST[MhswID])."' AND TahunID='".strfilter($_POST[TahunID])."'");					
        //}
		
		if ($query){
          echo "<script>document.location='index.php?ndelox=finance/admkeuangan&tahun=$_GET[tahun]&prodi=$_GET[prodi]&MhswID=$_POST[MhswID]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=finance/admkeuangan&act=edit&tahun=$_GET[tahun]&prodi=$_GET[prodi]&MhswID=$_POST[MhswID]&gagal';</script>";
        } 
    }
    
$s =  mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM keuangan_bayar where id_keu='".strfilter($_GET['id'])."'"));
echo "



  <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
  <div class='card'>
  <div class='card-header'>
  <div class='box-header with-border'>
  <h3 class='box-title'>Edit Data Pembayaran</h3>
</div>
  <div class='table-responsive'>
	  <table class='table table-sm table-bordered'>
	  <tbody>
		<input type='hidden' name='id' value='$s[id_keu]'>
		<input type='hidden' name='MhswID' value='$_GET[MhswID]'>
		<tr><th scope='row'>Jenis admkeuangan</th> <td><input type='text' class='form-control form-control-sm' name='id_jenis' value='$s[id_jenis]' readonly=''></td></tr>
		<tr><th width='250px' scope='row'>Tahun</th> <td><input type='text' class='form-control form-control-sm' name='TahunID' value='$s[TahunID]'> </td></tr>					
		<tr><th  scope='row'>Tanggal</th> <td><input type='text' class='form-control form-control-sm' name='TanggalBayar' id='datepicker' value='$s[TanggalBayar]'> </td></tr>		
		<tr><th  scope='row'>Nominal</th> <td><input type='text' class='form-control form-control-sm' name='total_bayar' id=angka3 value='$s[total_bayar]'> </td></tr>
		<tr><th scope='row'>No Bukti</th> <td><input type='text' class='form-control form-control-sm' name='NoBukti' value='$s[NoBukti]'></td></tr>
		<tr><th  scope='row'>Keterangan admkeuangan</th>   <td><select class='form-control form-control-sm' name='keterangan'> 
			<option value='0' selected>- Pilih Keterangan -</option>"; 
			$ket = mysqli_query($koneksi, "SELECT * from keuangan_keterangan");
			while($a = mysqli_fetch_array($ket)){
			  if ($s['Keterangan']==$a['Keterangan']){
				echo "<option value='$a[Keterangan]' selected>$a[Keterangan]</option>";
			  }else{
				echo "<option value='$a[Keterangan]'>$a[Keterangan]</option>";
			  }
			}
			echo "</select>
		</td></tr>
	  </tbody>
	  </table>
	  <div class='box-footer'>
	  <button type='submit' name='update' class='btn btn-info'>Update</button>
	  <a href='index.php?ndelox=finance/admkeuangan&MhswID=$_GET[MhswID]'><button type=button class='btn btn-default pull-right'>Cancel</button></a>
	  
	</div>
	</div>
  </div>

  </form>
</div>";

}elseif($_GET['act']=='tambah'){
    if (isset($_POST['tambah'])){
		$cek =mysqli_num_rows(mysqli_fetch_array($koneksi, "select MhswID,TahunID,id_jenis,Keterangan from keuangan_bayar 
		where MhswID	='".strfilter($_POST['MhswID'])."' 
		and TahunID		='".strfilter($_POST['TahunID'])."'
		and id_jenis	='".strfilter($_POST['id_jenis'])."'
		and Keterangan	='Lunas'"));
		if($cek>0){
			 echo "<script>document.location='index.php?ndelox=finance/admkeuangan&tahun=$_POST[TahunID]&prodi=$_POST[prodi]&MhswID=$_POST[MhswID]&gagal';</script>";
			exit;
		}
		$prodi =mysqli_fetch_array(mysqli_fetch_array($koneksi, "select MhswID,ProdiID from mhsw where MhswID='".strfilter($_POST['MhswID'])."'")); 
        $nominal =$_POST['total_bayar'];
        $angka1= str_replace(".", "", $nominal);
		$query = mysqli_query($koneksi, "INSERT INTO keuangan_bayar
							(id_jenis,							
							TahunID,
							MhswID,
							ProdiID,
							total_bayar,
							TanggalBayar,
							TanggalBuat,
							keterangan,
							NoBukti,
							Login) 		
					VALUES('".strfilter($_POST['id_jenis'])."',
							'".strfilter($_POST['TahunID'])."',
							'".strfilter($_POST['MhswID'])."',
							'$prodi[ProdiID]',
							'$angka1',
							'".strfilter($_POST['TanggalBayar'])."',
							'".date('Y-m-d')."',
							'".strfilter($_POST['keterangan'])."',
							'".strfilter($_POST['NoBukti'])."',
							'$_SESSION[_Login]')");
		//if ($_POST[id_jenis]=='SPP'){
		//    mysqli_fetch_array("update khs set StatusMhswID='A' where MhswID='".strfilter($_POST[MhswID])."' AND TahunID='".strfilter($_POST[TahunID])."'");					
        //}
		if ($query){
          echo "<script>document.location='index.php?ndelox=finance/admkeuangan&tahun=$_POST[tahun]&prodi=$prodi[ProdiID]&MhswID=$_POST[MhswID]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=finance/admkeuangan&tahun=$_POST[tahun]&prodi=$prodi[ProdiID]&MhswID=$_POST[MhswID]&gagal';</script>";
        } 
    }

echo "



	  <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
<div class='card'>
<div class='card-header'>
<div class='box-header with-border'>
<h3 class='box-title'>Tambah Data admkeuangan</h3>
</div>
<div class='table-responsive'>
		  <table class='table table-sm table-bordered'>
		  <tbody>
		  <input type='hidden' name='prodi' value='$_GET[prodi]'>				  
		  <input type='hidden' name='MhswID' value='$_GET[MhswID]'>
		   <tr><th width='250px' scope='row'>Jenis admkeuangan</th> 
			<td><select class='form-control form-control-sm' name='id_jenis'> "; 
				$jn = mysqli_query($koneksi, "SELECT * FROM keuangan_jenis");
				while($a = mysqli_fetch_array($jn)){						
					 echo "<option value='$a[id_jenis]'> $a[id_jenis] </option>";						 
				}
			echo "</select></td></tr>					
			<tr><th scope='row'>Tahun Akademik</th>  <td><input type='text' class='form-control form-control-sm' name='TahunID' value='$_SESSION[tahun_akademik]' maxlength='5'></td></tr>					
			<tr><th scope='row'>Tanggal</th><td><input type='text' class='form-control form-control-sm' name='TanggalBayar' id='datepicker' value='".date('Y-m-d')."'></td></tr>                   				
			<tr><th scope='row'>Nominal</th><td><input type='text' class='form-control form-control-sm' name='total_bayar' id='angka3'></td></tr>
			<tr><th scope='row'>No Bukti</th><td><input type='text' class='form-control form-control-sm' name='NoBukti' value='-'></td></tr>
			<tr><th width='250px' scope='row'>Keterangan</th> 
			<td><select class='form-control form-control-sm' name='keterangan'>"; 
				$k = mysqli_query($koneksi, "SELECT * FROM keuangan_keterangan order by id_ket asc");
				while($f = mysqli_fetch_array($k)){						
					 echo "<option value='$f[Keterangan]'> $f[Keterangan] </option>";						 
				}
			echo "</select></td></tr>	
		  </tbody>
		  </table>
		  <div class='box-footer'>
		  <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
		  <a href='index.php?ndelox=finance/admkeuangan&MhswID=$_GET[MhswID]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>			
		  </div>
		</div>
	  </div>

	  </form>";
}
?>