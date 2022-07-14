<div class='card'>
<div class='card-header'>

<?php 
if (isset($_GET[MhswID])){ 
   $mahasiswa =mysqli_fetch_array(mysqli_query($koneksi, "select MhswID,Nama,ProdiID, ProgramID from mhsw where MhswID='".strfilter($_GET[MhswID])."'"));
?>   
   <h3 class='box-title'><b style=color:green;font-size:20px;>SURAT AKTIF KULIAH : <?php echo"$mahasiswa[Nama]";?></b></h3> 
  
<?php } ?>


<div class="form-group row">
<label class="col-md-7 col-form-label text-md-right"><b style='color:purple'>NIM</b></label>
<div class="col-md-2">
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='dep/srtaktif'>
<input type='text' class='form-control form-control-sm' name='MhswID' value='<?php echo"$_GET[MhswID]";?>'>
</div>                

<div class="col-md-1">
<input type="submit" class='btn btn-success btn-sm' value='Lihat'>
</div>                
</form>


<div class="col-md-2">
 <a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=dep/srtaktif&act=tambahsrtaktif&tahun=<?php echo"$_GET[tahun]";?>&MhswID=<?php echo"$_GET[MhswID]";?>&prodi=<?php echo"$mahasiswa[ProdiID]";?>'>Tambahkan Data Aktif Kuliah </a>
</div>                


</div>
</div>
</div>



<?php if ($_GET[act]==''){ 	

echo"<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>							  							
	<input type='hidden' name='TahunID' value='$_GET[tahun]'>
	<input type='hidden' name='MhswID' value='$_GET[MhswID]'>";						 
?>	
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example" class="table table-sm table-sm table-striped">
  <thead>
    <tr style='background:purple;color:white'>
      <th  width="10px">No</th>
      <th width="40px">Tahun</th>
      <th width="80px"> Tanggal</th>
      <th width="80px"> NIM</th>
       <th width="150px">Mahasiswa</th> 
      <th width="350px">Keterangan</th>
      <th width="200px">No Surat</th>
      <th width="30px">Prodi</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
<?php 
  if ($_GET['MhswID']==''){
  	  $tampil = mysqli_query($koneksi, "SELECT * FROM vw_aktifkul order by TanggalBuat desc limit 20");
  }else{
	  $tampil = mysqli_query($koneksi, "SELECT * FROM vw_aktifkul where MhswID='".strfilter($_GET[MhswID])."' order by TanggalBuat desc");
  }
  $no = 1;
  while($r=mysqli_fetch_array($tampil)){
  $tanggal = tgl_indo($r[tgl_posting]);
  echo "<tr>
  <td>$no</td>
  <td>$r[TahunID]</td>
  <td>".tgl_indo($r[TanggalBuat])."</td>
  <td>$r[MhswID]</td>
   <td>$r[Nama]</td>
  <td>$r[Keterangan]</td>
  <td>$r[TextSurat]</td>
  <td>$r[ProdiID]</td>                          
  <td width='100px'><center>
<a class='btn btn-info btn-xs' title='Cetak Surat' href='print_report/print-aktifkul.php?MhswID=$r[MhswID]&prodi=$r[ProdiID]' target='_blank'><i class='fa fa-print'></i></a>
  
  <a class='btn btn-success btn-xs' title='Edit Data' href='?ndelox=dep/srtaktif&act=editsrt&MhswID=$r[MhswID]&tahun=$r[TahunID]'><i class='fa fa-edit'></i></a>
  <a class='btn btn-danger btn-xs' title='Delete Data' href='?ndelox=dep/srtaktif&hapus=$r[MhswID]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-trash'></i></a>
  </center></td>
  </tr>";
	$no++;
	}
	if (isset($_GET[hapus])){
		mysqli_query($koneksi, "DELETE FROM aktifkul where MhswID='".strfilter($_GET[hapus])."'");
		echo "<script>document.location='index.php?ndelox=dep/srtaktif';</script>";
	}

  ?>
	</tbody>
  </table></div>
</div>
</di
<?php								  
echo "<div class='box-footer'>
                 
</div>";
echo "</form>";


}else if ($_GET[act]=='tambahsrtaktif'){ 
$tglnow = date('Y-m-d H:i:s');
$r = mysqli_fetch_array(mysqli_query($koneksi, "SELECT a.MhswID, a.Nama AS NamaMhs, a.ProdiID, a.ProgramID, b.Nama AS NamaProdi 
									FROM mhsw a LEFT JOIN prodi b ON a.ProdiID=b.ProdiID where a.MhswID='".strfilter($_GET[MhswID])."'"));                                       											   												
echo"<div class='card'>
<div class='card-header'>
<div class='table-responsive'>  
	<table class='table table-condensed table-sm table-bordered'>
	<tbody>              
	<tr>
	<th scope='row' style='width:200px'>NIM</th>
	<th>$r[MhswID]</th>
	<th>Program </th>
	<th>$r[ProgramID]</th>
	</tr>
							
	<tr>
	<th scope='row' >Nama Mahasiswa </th>
	<th>$r[NamaMhs]</td>
	<th scope='row' style='width:200px'>Program Studi</th> 
	<th>$r[NamaProdi]</th>
	</tr>        
	
	</tbody>
	</table>
	</div>
</div>
</div>
"; 

if (isset($_POST[tambah])){    
  $cek=mysqli_num_rows(mysqli_query($koneksi, "select MhswID from aktifkul where MhswIDx='".strfilter($_GET[MhswID])."' and TahunID='$_SESSION[tahun_akademik]'"));
  if($cek>0){		    
	 echo "<script language='javascript'>alert('Sorry Bos, Data sudah ada..');
		   window.location = 'index.php?ndelox=dep/srtaktif&MhswID=$_GET[MhswID]'</script>";
	 exit;
  } 
  
  if ($_POST[Semester]==''){
	  echo "<script language='javascript'>alert('Pilih Semester..');
		   window.location = 'index.php?ndelox=dep/srtaktif&MhswID=$_GET[MhswID]&act=tambahsrtaktif'</script>";
	 exit;
	  }
  
  $bln	=date('m');
  $tahun=date('Y');
  if($bln=='1'){$BlnRomawi="I";}elseif($bln=='2'){$BlnRomawi="II";} elseif($bln=='3'){$BlnRomawi="III";} elseif($bln=='4'){$BlnRomawi="IV";} elseif($bln=='5'){$BlnRomawi="V";}
  elseif($bln=='6'){$BlnRomawi="VI";} elseif($bln=='7'){$BlnRomawi="VII";} elseif($bln=='8'){$BlnRomawi="VIII";} elseif($bln=='9'){$BlnRomawi="IX";} elseif($bln=='10'){$BlnRomawi="X";}
  elseif($bln=='11'){$BlnRomawi="XI";}else{{$BlnRomawi="XII";}}
  	  
	  $query = mysqli_query($koneksi,"SELECT MAX(Nomor) AS last FROM t_suratadm where ProdiID='".strfilter($_POST[prodi])."' ORDER BY Nomor DESC LIMIT 1"); 
	  $data  = mysqli_fetch_array($query);
	  $lastNoTransaksi = $data['last'];
	  
	  $lastNoUrut = substr($lastNoTransaksi, 0, 3);
	  $nextNoUrut = $lastNoUrut + 1;  
	  $Nomor 	  = sprintf('%03s', $nextNoUrut);	 
	  
	  $prodx      = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM prodi where ProdiID='".strfilter($_POST[prodi])."'"));
	  $NoSurat    = $prodx[KodeProdi]."UHTP/".$BlnRomawi."/".$tahun."/".$Nomor;
	        
  	  $query = mysqli_query($koneksi, "INSERT INTO aktifkul
					   (TahunID,
						MhswID,								
						Keterangan,
						LoginBuat,
						TanggalBuat,
						LoginEdit,
						TanggalEdit,
						NA,
						Nomor,
						TextSurat,
						ProdiID,
						Semester) 						
				 VALUES('".strfilter($_POST[aa])."',
						'".strfilter($_POST[ab])."',
						'".strfilter($_POST[ac])."',
						'$_SESSION[id]',
						'$tglnow',
						'',
						'',
						'N',
						'$Nomor',
						'$NoSurat',
						'".strfilter($_POST[prodi])."',
						'".strfilter($_POST[Semester])."')");
   if ($query){
    echo "<script>document.location='index.php?ndelox=dep/srtaktif&MhswID=".$_POST[ab]."&sukses';</script>";
}else{
    echo "<script>document.location='index.php?ndelox=dep/srtaktif&MhswID=".$_POST[ab]."&gagal';</script>";
}

}

echo "


<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
<div class='card'>
<div class='card-header'>
<div class='box-header with-border'>
<h3 class='box-title'><b style=color:green>Tambah Data Aktif Kuliah</b></h3>
</div>
<div class='table-responsive'>
<table class='table table-condensed table-bordered'>
<tbody>
	
<input type='hidden' name='ab' value='$_GET[MhswID]'>
<input type='hidden' name='prodi' value='$_GET[prodi]'>
<tr>
<th width='190px' scope='row'>Tahun Akademik</th>
<td><input type='text' class='form-control form-control-sm' name='aa' value='$_SESSION[tahun_akademik]'></td>
</tr>

<th width='190px' scope='row'>Semester</th>
<td>

<select class='form-control form-control-sm' name='Semester'>
<option value='1'>Semester 1</option>
<option value='2'>Semester 2</option>
<option value='3'>Semester 3</option>
<option value='4'>Semester 4</option>
<option value='5'>Semester 5</option>
<option value='6'>Semester 6</option>
<option value='7'>Semester 7</option>
<option value='8'>Semester 8</option>
<option value='9'>Semester 9</option>
<option value='10'>Semester 10</option>

</select>
</td>
</tr>

<tr>
<th scope='row'>Untuk Keperluan</th>
<td><input type='text' class='form-control form-control-sm' name='ac'></td>
</tr>
						 
</tbody>
</table>
<div class='box-footer'>
<button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
<a href='index.php?ndelox=dep/srtaktif'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
</div>
</div>
</div>
</div>

 
</div>
</form>
</div>";
                  
 
} //tutup atas

else if ($_GET[act]=='editsrt'){ 
$tglnow = date('Y-m-d H:i:s');
$r = mysqli_fetch_array(mysqli_query($koneksi,"SELECT a.MhswID, a.Nama AS NamaMhs, a.ProdiID, a.ProgramID, b.Nama AS NamaProdi 
									FROM mhsw a LEFT JOIN prodi b ON a.ProdiID=b.ProdiID where a.MhswID='".strfilter($_GET[MhswID])."'"));                                       											   												
echo"<div class='card'>
<div class='card-header'>
<div class='table-responsive'>  						
	<table class='table table-sm table-bordered' style='width:50%' align='center'>
	<tbody>              
	<tr>
	<th scope='row' style='width:200px'>NIM</th>
	<th>$r[MhswID]</th>
	<th>Program </th>
	<th>$r[ProgramID]</th>
	</tr>
							
	<tr>
	<th scope='row' >Nama Mahasiswa </th>
	<th>$r[NamaMhs]</td>
	<th scope='row' style='width:200px'>Program Studi</th> 
	<th>$r[NamaProdi]</th>
	</tr>        
	
	</tbody>
	</table>
	</div>
</div>
</div>
"; 

if (isset($_POST[editx])){    
  	$query = mysqli_query($koneksi, "UPDATE aktifkul 
							SET Keterangan='".strfilter($_POST[Keterangan])."',
							Semester='".strfilter($_POST[Semester])."',
							TextSurat='".strfilter($_POST[TextSurat])."' 
							WHERE MhswID='".strfilter($_POST[MhswID])."'");
	if ($query){
		echo "<script>document.location='index.php?ndelox=dep/srtaktif&MhswID=".$_POST[MhswID]."&tahun=".$_POST[tahun]."&sukses';</script>";
	}else{
	    echo "<script>document.location='index.php?ndelox=dep/srtaktif&MhswID=".$_POST[MhswID]."&tahun=".$_POST[tahun]."&gagal';</script>";
	}
}

$e=mysqli_fetch_array(mysqli_query($koneksi,"select * from aktifkul 
								WHERE MhswID='".strfilter($_GET[MhswID])."' 
								AND TahunID='".strfilter($_GET[tahun])."'"));

echo "



<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
<div class='card'>
<div class='card-header'>
<div class='box-header with-border'>
<h3 class='box-title'><b style=color:green>Ubah Data Aktif Kuliah</b></h3>
</div>
<div class='table-responsive'>
<table class='table table-condensed table-bordered'>
<tbody>
	
<input type='hidden' name='MhswID' value='$_GET[MhswID]'>
<input type='hidden' name='prodi' value='$_GET[prodi]'>
<tr>
<th width='190px' scope='row'>Tahun Akademik</th>
<td><input type='text' class='form-control form-control-sm' name='tahun' value='$_GET[tahun]'></td>
</tr>

<tr>
<th width='190px' scope='row'>Nomor Surat</th>
<td><input type='text' class='form-control form-control-sm' name='TextSurat' value='$e[TextSurat]'></td>
</tr>

<th width='190px' scope='row'>Semester</th>
<td><input type='text' class='form-control form-control-sm' name='Semester' value='$e[Semester]'></td>
</tr>

<tr>
<th scope='row'>Untuk Keperluan</th>
<td><input type='text' class='form-control form-control-sm' name='Keterangan' value='$e[Keterangan]'></td>
</tr>
						 
</tbody>
</table>
<div class='box-footer'>
<button type='submit' name='editx' class='btn btn-info'>Perbaharui</button>
<a href='index.php?ndelox=dep/srtaktif'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
</div> 
</div>
</div>
</div>



</div>
</form>
</div>";
                  
 
} //tutup atas

?>