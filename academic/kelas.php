
<div class="card">
<div class="card-header">

<div class="form-group row">
	<label class="col-md-6 col-form-label text-md-right"><b style='color:purple'>FILTER KELAS</b></label>
		<div class="col-md-2">	  
		<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
		<input type="hidden" name='ndelox' value='academic/kelas'> 
			<select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
				<?php 
					echo "<option value=''>- Pilih Tahun Akademik -</option>";
					$tahun = mysqli_query($koneksi, "SELECT distinct(TahunID),NA FROM tahun order by TahunID Desc limit 8"); //and NA='N'
					while ($k = mysqli_fetch_array($tahun)){
					if ($_GET['tahun']==$k['TahunID']){
						echo "<option value='$k[TahunID]' selected>$k[TahunID]</option>";
					}else{
						echo "<option value='$k[TahunID]'>$k[TahunID]</option>";
					}
					}
				?>
			</select>
		</div>                


		<div class="col-md-2">							 
				<select name='prodi' class='form-control form-control-sm' onChange='this.form.submit()'>
					<?php 
						echo "<option value=''>- Pilih Program Studi -</option>";
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


		<div class="col-md-1">	
				<input type="submit"  class='btn btn-success btn-sm' value='Lihat'>
		
		</div>

        <div class="col-md-1">	

			</form>
            <?php 
	echo"<input class='btn btn-success btn-sm' type=button name='TambahKelas' value='Tambah Kelas' onClick=\"location='?ndelox=$_SESSION[ndelox]&act=editdata&md=1&tahun=$_GET[tahun]&prodi=$_GET[prodi]'\" />";
	?>
		</div>
	</div>


</div>  	
</div>  

<?php if ($_GET['act']==''){ ?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<tr >
	<td colspan=$cs class=ul1>
	
    </td>
</tr>
        <table id="example1" class="table table-sm table-striped">
          <thead>
            <tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:purple;>
              <th style='width:20px'>No</th>                     
              <th style='width:20px;text-align:center;'>Kode</th>
              <th style='width:120px'>Nama Kelas</th>	
              <th style='width:100px;text-align:left;'>ProgramID</th>
              <th style='width:100px;text-align:left;'>TahunID</th>
              <th style='width:100px;text-align:left;'>Kapasitas Sekarang</th>		 
              <th style='width:100px;text-align:left;'>Kapasitas Maksimum</th>		 
			  <th style='width:100px;text-align:center;'>Aksi</th>
            </tr>
          </thead>
          <tbody>
        <?php
		 
			$tampil = mysqli_query($koneksi, "SELECT * from kelas WHERE ProdiID='".strfilter($_GET['prodi'])."' and TahunID='".strfilter($_GET['tahun'])."'");                   
			$no = 1;
				while($r=mysqli_fetch_array($tampil)){  
				echo "<tr>
						<td>$no</td>               
						<td style='text-align:center;'>$r[KelasID]</td>   
                        <td>$r[Nama]</td>
						<td>$r[ProgramID]</td>   
						<td>$r[TahunID]</td>				
						<td>$r[KapasitasSekarang]</td>
						<td>$r[KapasitasMaksimum]</td>
						";                          
				echo "<td style='width:70px !important'><center><a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=academic/kelas&act=editdata&md=0&KelasID=$r[KelasID]&tahun=$r[TahunID]&prodi=$r[ProdiID]'><i class='fa fa-edit'></i></a>
				<a class='btn btn-danger btn-xs' title='Hapus ' href='index.php?ndelox=academic/kelas&hapus=$r[KelasID]&tahun=$r[TahunID]&prodi=$r[ProdiID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
				</center></td>";
				echo "</tr>";
				$no++;
			}

			if (isset($_GET['hapus'])){				
				mysqli_query($koneksi, "DELETE FROM kelas where KelasID='".strfilter($_GET['hapus'])."'");
				echo "<script>document.location='index.php?ndelox=academic/kelas&id=".strfilter($_GET['KelasID'])."&tahun=".strfilter($_GET['tahun'])."&prodi=".strfilter($_GET['prodi'])."&sukses';</script>";
			}
  ?>
	<tbody>
  </table>
</div>
</div>
</div>
<?php 
}

elseif($_GET['act']=='editdata'){
	if (isset($_POST['Simpan'])){			
		$md 		= $_POST['md']+0;
		$na   		= (empty($_POST['NA']))? 'N' : $_POST['NA']; //checbox
		if ($md == 0) {
			$query = mysqli_query($koneksi, "UPDATE kelas SET 
                        Nama 			    = '".strfilter($_POST['Nama'])."',
                        ProgramID 			= '".strfilter($_POST['ProgramID'])."',
                        ProdiID 		    = '".strfilter($_POST['ProdiID'])."',
                        KapasitasSekarang 	= '".strfilter($_POST['KapasitasSekarang'])."',
                        KapasitasMaksimum 	= '".strfilter($_POST['KapasitasMaksimum'])."',
                        LoginEdit		    = '$_SESSION[_Login]',
                        TanggalEdit		    = '".date('Y-m-d')."',
                        NA 				    = '$na'														 
                        WHERE KelasID	    = '".strfilter($_POST['KelasID'])."'");
			echo "<script>document.location='index.php?ndelox=academic/kelas&tahun=$_POST[TahunID]&prodi=$_POST[ProdiID]&sukses';</script>";
		}
		else {
			$KelasID 	= strfilter($_POST['KelasID']);
			$ada 		= AmbilFieldx('ruang', 'KelasID', $KelasID, '*');
			if (!empty($ada)) echo PesanError("Gagal Simpan", "Kode: <b>$KelasID</b> sudah ada dengan nama <b>$ada[Nama]</b>");
			else {
				mysqli_query($koneksi, "insert into kelas (
                        Nama, 
                        TahunID,
                        ProgramID, 
                        ProdiID, 
                        KapasitasMaksimum,
                        LoginBuat, 
                        TanggalBuat,
                        NA)
                values (
                        '".strfilter($_POST['Nama'])."', 
                        '".strfilter($_POST['TahunID'])."', 
                        '".strfilter($_POST['ProgramID'])."', 
                        '".strfilter($_POST['ProdiID'])."', 
                        '".strfilter($_POST['KapasitasMaksimum'])."', 
                        '".$_SESSION['_Login']."', 
                        '".date('Y-m-d')."', 			
                        '$na')");
		echo "<script>document.location='index.php?ndelox=academic/kelas&tahun=$_POST[TahunID]&prodi=$_POST[ProdiID]&sukses';</script>";
			}
	  }
	} //tutup submit simpan  

    $md = strfilter($_GET['md']);
	if ($md == 0) {
	  $w 		= mysqli_fetch_array(mysqli_query($koneksi, "select * from kelas where KelasID='".strfilter($_GET['KelasID'])."'"));
	  $jdl 		= "Edit Kelas";
	  $strid 	= "<input type=hidden name='KelasID' value='$w[KelasID]'><b>$w[KelasID]</b>";
	}
	else {
	  $w['NA'] 	= 'N';
	  $jdl 		= "Tambah Kelas";
	  $strid 	= "Auto";
	}

	$comboprog 	    = AmbilCombo2('program', "concat(ProgramID, ' - ', Nama)", 'ProgramID', $w['ProgramID'], '', 'ProgramID');
    $comboprod 	    = AmbilCombo2('prodi', "concat(ProdiID, ' - ', Nama)", 'ProdiID', $w['ProdiID'], '', 'ProdiID');
    $na 			= ($w['NA'] == 'Y')? 'checked' : '';
	echo "<div class='card'>
	<div class='card-header'>
	<div class='table-responsive'>
    <table id='example' class='table table-sm table-stripedx' style='width:50%'>
	<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>	
	<input type=hidden name='md' value='$md'>
    <input type=hidden name='ndelox' value='$_SESSION[ndelox]'>
	<tr ><th colspan=2 class=ttl>$jdl</th></tr>
	<tr><td>KelasID</td><td>$strid</td></tr>
    <tr><td>Tahun Akademik</td><td><input type=text class='form-control form-control-sm' name='TahunID' value='$w[TahunID]' size=40 maxlength=50></td></tr>
    <tr><td>Nama</td><td><input type=text class='form-control form-control-sm' name='Nama' value='$w[Nama]' size=40 maxlength=50></td></tr>
	<tr><td>Program</td><td><select class='form-control form-control-sm' name='ProgramID'>$comboprog</select></td></tr>
    <tr><td>Program Studi</td><td><select class='form-control form-control-sm' name='ProdiID'>$comboprod</select></td></tr>
	<tr><td>Nama</td><td><input type=text class='form-control form-control-sm' name='Nama' value='$w[Nama]' size=40 maxlength=50></td></tr>
  
	<tr><td>Kapasitas Maksimum Kelas</td><td><input class='form-control form-control-sm' type=text name='KapasitasMaksimum' value='$w[KapasitasMaksimum]' size=40 maxlength=50></td></tr>
	
	
	<tr><td>NA (tidak aktif)?</td><td><input  type=checkbox name='NA' value='Y' $na></td></tr>
	<tr>
		<td colspan=2 align=left>
	    <input class='btn btn-success btn-sm' type=submit name='Simpan' value='Simpan'>
		<input class='btn btn-primary btn-sm' type=reset name='Reset' value='Reset'>
		<input class='btn btn-danger btn-sm' type=button name='Batal' value='Batal' onClick=\"location='?ndelox=$_SESSION[ndelox]&tahun=$_GET[tahun]&prodi=$_GET[prodi]'\"></td></tr>
		</td>
	</tr>
	</form>
	</table>
	</div>
	</div>
	</div>";

}

?>