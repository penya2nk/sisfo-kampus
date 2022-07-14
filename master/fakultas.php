<div class='card'>
<div class='card-header'>
<?php 
  if (isset($_GET['tahun'])){ 
     echo "<b style='color:green;font-size:20px'>Fakultas &nbsp;&nbsp;</b> ";
  }else{ 
     echo "<b style='color:green;font-size:20px'>Fakultas</b>";  
  } ?>
</h3>

</div>
</div>


<?php if ($_GET['act']==''){ ?>
	<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
        <table id="example" class="table table-sm table-striped">
          <thead>
            <tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>
              <th style='width:20px'>No</th>                     
              <th style='width:70px;text-align:center;'>Kode</th>
              <th style='width:200px'>Nama Prodi</th>
              <th style='width:600px'>Pejabat</th>
			  <th style='width:230px;text-align:center;'>Aksi</th>
            </tr>
          </thead>
          <tbody>
        <?php
			  $sqx = mysqli_query($koneksi, "SELECT * from fakultas order by Nama ASC");
			  while ($h = mysqli_fetch_array($sqx)){
			  echo"<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#89bedc;>
				   <td colspan='5' height='4' ><b>&nbsp; Fakultas: $h[Nama]</b></td>
				   </tr>";              
			  $tampil = mysqli_query($koneksi, "SELECT * from prodi WHERE FakultasID='$h[FakultasID]'");                   
		  $no = 1;
          while($r=mysqli_fetch_array($tampil)){  
		  echo "<tr>
				<td>$no</td>               
				<td>$r[ProdiID]</td>
				<td>$r[Nama]</td>   
				<td>$r[Pejabat]</td>
				<td style='width:70px !important'><center><a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=xxx&act=edit&JadwalID=$r[xx]&program=$r[ProgramID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'><i class='fa fa-edit'></i></a>
		  <a class='btn btn-danger btn-xs' title='Hapus ' href='index.php?ndelox=jadwalxx&hapus=$r[JadwalID]&program=$r[ProgramID]&prodi=$r[ProdiID]&tahun=$r[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
		  </center></td>";
		  echo "</tr>";
			$no++;
			}
			}
  ?>
	<tbody>
  </table>
</div>
</div>
</div>
<?php 
}

elseif($_GET['act']=='tambahdata'){
    echo "<div class='col-xs-12'>	
    <div class='box box-info'>
    <div class='box-header'>          
	<div class='box-header with-border'>
		<h3 class='box-title'><b>Tentukan PA untuk rentang NIM</b></h3>
	</div>
	<div class='box-body'>
	<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
	<input type='hidden' name='tahun' value='$_GET[tahun]'>
	<input type='hidden' name='program' value='$_GET[program]'>
	<input type='hidden' name='prodi' value='$_GET[prodi]'>
	<div class='col-md-12'>
	<table class='table table-condensed table-bordered'>
	<tbody>		
	<tr>
	<td>Dosen</td>
	<td scope='row'>
	<select name='DosenID' style='padding:4px'>";	
		echo "<option value=''>- Pilih Dosen -</option>";
		$dsn = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by Nama asc");
		while ($k = mysqli_fetch_array($dsn)){
		 if ($_POST['DosenID']==$k['Login']){
			echo "<option value='$k[Login]' selected>$k[Nama], $k[Gelar]</option>";
		  }else{
			echo "<option value='$k[Login]'>$k[Nama], $k[Gelar] </option>";
		  }
		}	
	echo"</select>
	</td>  
	</tr>

	<tr>
	<td>Mulai</td>
	<td scope='row'><input type='text' name='DariNIM' value='$_POST[DariNIM]'></td>  
	</tr>
	<tr>
	<td>Sampai </td>
	<td scope='row'><input type='text' name='SampaiNIM' value='$_POST[SampaiNIM]'></td>  
	</tr>
	</tbody>
	</table>
	</div>
	</div>
	<div class='box-footer'>
		<button type='submit' name='lihat' class='btn btn-info'>Simpan Pembimbing Akademik</button>
		<a href='index.php?ndelox=jadwal&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>	
		</div>
	</form>
	</div>";

	if (isset($_POST['lihat'])){	
		$DosenID = strfilter($_POST['DosenID']);
		$DariNIM = strfilter($_POST['DariNIM']);
		$SampaiNIM = strfilter($_POST['SampaiNIM']);
		$sql = mysqli_query($koneksi, "SELECT m.*, d.Nama as NamaDosen, d.Gelar FROM mhsw m
		left outer join dosen d on m.fakultasademik=d.Login
		WHERE '$DariNIM' <= m.MhswID AND m.MhswID <= '$SampaiNIM'");

		$dsn = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen 
		WHERE Login='".strfilter($_POST['DosenID'])."'"));
		
		echo"<div class='box box-info'>
		<div class='box-header'>          
		<div class='box-header with-border'>
			<h3 class='box-title'><b>Daftar PA Mahasiswa</b></h3>
		</div>
		<div class='box-body'>
		<table class='table table-condensed table-bordered'>
		<tr style='background:purple;color:white'>
		<th style='width:40px'>No</th>
		<th>NIM</th>
		<th>Nama</th>
		<th>Prodi</th>
		<th>Pembimbing Akademik</th>
		</tr>";
		$_npm='';
		while($w=mysqli_fetch_array($sql)){
		$no++;	
			$jml = mysqli_num_rows($sql);
			$_npm .= "&_NPM[]=$w[MhswID]";
			echo"<tr>
			<td>$no</td>
			<td>$w[MhswID]</td>
			<td>$w[Nama]</td>
			<td>$w[ProdiID]</td>
			<td>$w[NamaDosen], $w[Gelar]</td>
			<tr>";
		}
		echo"</table>
		<br><h6 class='box-title'>Daftar mahasiswa dalam rentang yang Anda tentukan.
		<br>Terdapat: <b>$jml</b> mahasiswa.
		<br>Apakah Anda akan mengubah Penasehat Akademik mereka menjadi: <b>$dsn[Nama] ($dsn[Login]) </b></h6>
		<br><br> <input type=button name='Ubah' value='Ubah PA'
      onClick=\"location='?ndelox=fakultas&act=RentangNIMSav1&&tahun=$_GET[tahun]&prodi=$_GET[prodi]&DariNIM=$DariNIM&SampaiNIM=$SampaiNIM&DosenID=$DosenID$_npm'\">
      <input type=button name='Batal' value='Batalkan' onClick=\"location='?ndelox=fakultas&tahun=$_GET[tahun]&prodi=$_GET[prodi]'\">
		</div>
		</div>";
	}

}
elseif($_GET[act]=='RentangNIMSav1'){
	$_NPM = array();
	$_NPM = $_GET['_NPM'];
	for ($i = 0; $i < sizeof($_NPM); $i++) {
	  $isi = $_NPM[$i];
	  $_NPM[$i] = "'$isi'";
	}
	$__npm = implode(',', $_NPM);
	$DosenID = $_GET['DosenID'];
	//echo"NIM: $__npm";
	$s = mysqli_query($koneksi, "UPDATE mhsw SET fakultasademik='$DosenID' WHERE MhswID IN ($__npm) ");
	echo "<script>document.location='index.php?ndelox=fakultas&tahun=$_GET[tahun]&prodi=$_GET[prodi]&sukses';</script>";
  
}


?>