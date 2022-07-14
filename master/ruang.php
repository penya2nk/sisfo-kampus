<div class='card'>
<div class='card-header'>
<h3>Informasi Ruangan</h3>
</div>
</div>

<?php if ($_GET['act']==''){ ?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<tr >
	<td colspan=$cs class=ul1>
    <?php 
	echo"<input class='btn btn-success btn-sm' type=button name='TambahRuangan' value='Tambah Ruangan' onClick=\"location='?ndelox=$_SESSION[ndelox]&act=editdata&md=1'\" />";
	?>	
    </td>
</tr>
        <table id="example1" class="table table-sm table-striped">
          <thead>
            <tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>
              <th style='width:20px'>No</th>                     
              <th style='width:100px;text-align:center;'>Kode</th>
              <th style='width:220px'>Nama Ruangan</th>
			  <th style='width:100px'>Kapasitas</th>
              <th style='width:100px'>Lantai</th>
			  <th style='width:450px'>Digunakan Untuk</th>
			  <th style='width:100px'>Kampus/Gedung</th>
			 
			  <th style='width:100px;text-align:center;'>Aksi</th>
            </tr>
          </thead>
          <tbody>
        <?php
			$sqx = mysqli_query($koneksi, "SELECT * from kampus order by Nama ASC");
			while ($h = mysqli_fetch_array($sqx)){
			echo"<tr style='background:purple;color:white'>
				<td colspan='8' height='4' ><b>&nbsp; Kampus / Gedung: $h[Nama]</b></td>
				</tr>";              
			$tampil = mysqli_query($koneksi, "SELECT * from ruang WHERE KampusID='$h[KampusID]'");                   
			$no = 1;
				while($r=mysqli_fetch_array($tampil)){  
				echo "<tr>
						<td>$no</td>               
						<td>$r[RuangID]</td>
						<td>$r[Nama]</td>   
						<td>$r[Kapasitas]</td>   
						<td>$r[Lantai]</td>				
						<td>$r[ProdiID]</td>
						<td>$r[KampusID]</td>
						";                          
				echo "<td style='width:70px !important'><center><a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=master/ruang&act=editdata&md=0&RuangID=$r[RuangID]&KampusID=$r[KampusID]'><i class='fa fa-edit'></i></a>
				<a class='btn btn-danger btn-xs' title='Hapus ' href='index.php?ndelox=master/ruang&hapus=$r[RuangID]&KampusID=$r[KampusID]&prodi=$r[ProdiID]&tahun=$r[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
				</center></td>";
				echo "</tr>";
				$no++;
				}
			}

			if (isset($_GET['hapus'])){				
				mysqli_query($koneksi, "DELETE FROM ruang where RuangID='".strfilter($_GET['hapus'])."'");
				echo "<script>document.location='index.php?ndelox=master/ruang&id=".strfilter($_GET['RuangID'])."&KampusID=".strfilter($_GET['KampusID'])."&prodi=".strfilter($_GET['prodi'])."&sukses';</script>";
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
		$prodi 		= $_POST['ProdiID'];
		$ProdiID 	= (empty($prodi))? '' : '.'.implode('.', $prodi).'.'; //checkbox
		$UntukUSM   = (empty($_POST['UntukUSM']))? 'N' : $_POST['UntukUSM']; //checbox
		$na   		= (empty($_POST['NA']))? 'N' : $_POST['NA']; //checbox
		if ($md == 0) {
			$query = mysqli_query($koneksi, "UPDATE ruang SET 
			Nama 			= '".strfilter($_POST['Nama'])."',
			Lantai 			= '".strfilter($_POST['Lantai'])."',
			Kapasitas 		= '".strfilter($_POST['Kapasitas'])."',
			KapasitasUjian 	= '".strfilter($_POST['KapasitasUjian'])."',
			KampusID 		= '".strfilter($_POST['KampusID'])."',
			UntukUSM		= '$UntukUSM',
			ProdiID 		= '$ProdiID',
			NA 				= '$na'														 
			WHERE RuangID	= '".strfilter($_POST['RuangID'])."'");
			echo "<script>document.location='index.php?ndelox=master/ruang&KampusID=$_POST[KampusID]&tahun=$_GET[tahun]&sukses';</script>";
		}
		else {
			$RuangID 	= strfilter($_POST['RuangID']);
			$ada 		= AmbilFieldx('ruang', 'RuangID', $RuangID, '*');
			if (!empty($ada)) echo PesanError("Gagal Simpan", "Kode: <b>$RuangID</b> sudah ada dengan nama <b>$ada[Nama]</b>");
			else {
				mysqli_query($koneksi, "insert into ruang (
					RuangID, 
					Nama, 
					KampusID, 
					KodeID, 
					Lantai,
					ProdiID, 
					Kapasitas, 
					KapasitasUjian, 
					UntukUSM, 
					NA)
			values ('".strfilter($_POST['RuangID'])."', 
					'".strfilter($_POST['Nama'])."', 
					'".strfilter($_POST['KampusID'])."', 
					'".KodeID."',
					'".strfilter($_POST['Lantai'])."', 
					'$ProdiID', 
					'".strfilter($_POST['Kapasitas'])."', 
					'".strfilter($_POST['KapasitasUjian'])."',  
					'$UntukUSM',  
					'$na')");
		echo "<script>document.location='index.php?ndelox=master/ruang&KampusID=$_POST[KampusID]&tahun=$_GET[tahun]&sukses';</script>";
			}
	  }
	} //tutup submit simpan  

    $md = strfilter($_GET['md']);
	if ($md == 0) {
	  //$w 		= AmbilFieldx('ruang', 'RuangID', $_GET['RuangID'], '*');
	  $w 		= mysqli_fetch_array(mysqli_query($koneksi, "select * from ruang where RuangID='".strfilter($_GET['RuangID'])."'"));
	  $jdl 		= "Edit Ruangan";
	  $strid 	= "<input type=hidden name='RuangID' value='$w[RuangID]'><b>$w[RuangID]</b>";
	}
	else {
	  $w['NA'] 	= 'N';
	  $jdl 		= "Tambah Ruangan";
	  $strid 	= "<input class='form-control form-control-sm' type=text name='RuangID' size=20 maxlength=20>";
	}

	$combokampus 	= AmbilCombo2('kampus', "concat(KampusID, ' - ', Nama)", 'KampusID', $w['KampusID'], '', 'KampusID');
	$cekboxprodi	= AmbilCekBox("prodi", "ProdiID","concat(ProdiID, ' - ', Nama) as NM", "NM", $w['ProdiID'], '.');
	$RuangKuliah 	= ($w['RuangKuliah'] == 'Y')? 'checked' : '';
	$na 			= ($w['NA'] == 'Y')? 'checked' : '';
	$usm 			= ($w['UntukUSM'] == 'Y')? 'checked' : '';
	echo "<div class='card'>
	<div class='card-header'>
	<div class='table-responsive'>
	<table id='example' class='table table-sm table-stripedx' style='width:50%'>
	<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>	
	<input type=hidden name='md' value='$md'>
	<input type=hidden name='ndelox' value='$_SESSION[ndelox]'>
	<tr ><th colspan=2 class=ttl>$jdl</th></tr>
	<tr><td>RuangID</td><td>$strid</td></tr>
	<tr><td>Kampus</td><td><select class='form-control form-control-sm' name='KampusID'>$combokampus</select></td></tr>
	<tr><td>Nama</td><td><input type=text class='form-control form-control-sm' name='Nama' value='$w[Nama]' size=40 maxlength=50></td></tr>
	<tr><td>Lantai</td><td><input class='form-control form-control-sm' type=text name='Lantai' value='$w[Lantai]' size=40 maxlength=50></td></tr>
	<tr><td>Kapasitas</td><td><input class='form-control form-control-sm' type=text name='Kapasitas' value='$w[Kapasitas]' size=40 maxlength=50></td></tr>
	<tr><td>Kapasitas Ujian</td><td><input class='form-control form-control-sm' type=text name='KapasitasUjian' value='$w[KapasitasUjian]' size=5 maxlength=5></td></tr>
	
	<tr><td>Untuk Ujian Saringan Masuk (USM)?</td><td><input  type=checkbox name='UntukUSM' value='Y' $usm></td></tr>
	<tr><td>Digunakan oleh prodi</td><td>$cekboxprodi</td></tr>
	<tr><td>Keterangan</td><td><textarea class='form-control form-control-sm' name='Keterangan' cols=44 rows=3>$w[Keterangan]</textarea></td></tr>
	<tr><td>NA (tidak aktif)?</td><td><input  type=checkbox name='NA' value='Y' $na></td></tr>
	<tr>
		<td colspan=2 align=left>
	    <input class='btn btn-success btn-sm' type=submit name='Simpan' value='Simpan'>
		<input class='btn btn-primary btn-sm' type=reset name='Reset' value='Reset'>
		<input class='btn btn-danger btn-sm' type=button name='Batal' value='Batal' onClick=\"location='?ndelox=$_SESSION[ndelox]&$snm=$sid'\"></td></tr>
		</td>
	</tr>
	</form>
	</table>
	</div>
	</div>
	</div>";

}

?>