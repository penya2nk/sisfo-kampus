<div class='card'>
<div class='card-header'>
<h3>ASAL PERGURUAN TINGGI</h3>
</div>
</div>

<?php if ($_GET['act']==''){ ?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<tr >
	<td colspan=$cs class=ul1>
    <?php 
	echo"<input class='btn btn-success btn-sm' type=button name='TambahPT' value='Tambah PT' onClick=\"location='?ndelox=$_SESSION[ndelox]&act=editdata&md=1'\" />";
	?>	
    </td>
</tr>
        <table id="example1" class="table table-sm table-striped">
          <thead>
            <tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>
              <th style='width:20px'>No</th>                     
              <th style='width:100px;text-align:center;'>Kode</th>
              <th style='width:120px'>Nama Perguruan Tinggi</th>
			  <th style='width:100px'>Jenis</th>
              <th style='width:100px'>Kota</th>
			  <th style='width:250px'>Website</th>
		
			  <th style='width:100px;text-align:center;'>Aksi</th>
            </tr>
          </thead>
          <tbody>
        <?php
           
			$tampil = mysqli_query($koneksi, "SELECT * from perguruantinggi order by Nama ASC");                   
			$no = 1;
				while($r=mysqli_fetch_array($tampil)){  
				echo "<tr>
						<td>$no</td>               
						<td>$r[PerguruanTinggiID]</td>
						<td>$r[Nama]</td>   
						<td>$r[JenisPerguruanTinggiID]</td>   			
						<td>$r[Kota]</td>
						<td>$r[Website]</td>
						";                          
				echo "<td style='width:70px !important'><center><a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=penmaba/asalpt&act=editdata&md=0&PerguruanTinggiID=$r[PerguruanTinggiID]&KampusID=$r[KampusID]'><i class='fa fa-edit'></i></a>
				<a class='btn btn-danger btn-xs' title='Hapus ' href='index.php?ndelox=penmaba/asalpt&hapus=$r[PerguruanTinggiID]&KampusID=$r[KampusID]&prodi=$r[ProdiID]&tahun=$r[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
				</center></td>";
				echo "</tr>";
				$no++;
			}

			if (isset($_GET['hapus'])){				
				mysqli_query($koneksi, "DELETE FROM perguruantinggi where PerguruanTinggiID='".strfilter($_GET['hapus'])."'");
				echo "<script>document.location='index.php?ndelox=penmaba/asalpt&id=".strfilter($_GET['PerguruanTinggiID'])."&KampusID=".strfilter($_GET['KampusID'])."&prodi=".strfilter($_GET['prodi'])."&sukses';</script>";
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
			$query = mysqli_query($koneksi, "UPDATE perguruantinggi SET 
							Nama 						= '".strfilter($_POST['Nama'])."',
							JenisPerguruanTinggiID 		= '".strfilter($_POST['JenisPerguruanTinggiID'])."',
							Alamat1 					= '".strfilter($_POST['Alamat1'])."',
							Alamat2 					= '".strfilter($_POST['Alamat2'])."',
							Kota 						= '".strfilter($_POST['Kota'])."',
							NA 							= '$na'														 
							WHERE PerguruanTinggiID		= '".strfilter($_POST['PerguruanTinggiID'])."'");
			echo "<script>document.location='index.php?ndelox=penmaba/asalpt&KampusID=$_POST[KampusID]&tahun=$_GET[tahun]&sukses';</script>";
		}
		else {
			$PerguruanTinggiID 	= strfilter($_POST['PerguruanTinggiID']);
			$ada 				= AmbilFieldx('perguruantinggi', 'PerguruanTinggiID', $PerguruanTinggiID, '*');
			if (!empty($ada)) echo PesanError("Gagal Simpan", "Kode: <b>$RuangID</b> sudah ada dengan nama <b>$ada[Nama]</b>");
			else {
				mysqli_query($koneksi, "insert into perguruantinggi (
					PerguruanTinggiID, 
					Nama, 
					JenisPerguruanTinggiID, 
					Alamat1, 
					Alamat2,
					Kota, 
					NA)
			values ('".strfilter($_POST['PerguruanTinggiID'])."', 
					'".strfilter($_POST['Nama'])."', 
					'".strfilter($_POST['JenisPerguruanTinggiID'])."', 
					'".strfilter($_POST['Alamat1'])."', 
					'".strfilter($_POST['Alamat2'])."', 
					'".strfilter($_POST['Kota'])."',  
					'$na')");
		echo "<script>document.location='index.php?ndelox=penmaba/asalpt&KampusID=$_POST[KampusID]&tahun=$_GET[tahun]&sukses';</script>";
			}
	  }
	} //tutup submit simpan  

    $md = strfilter($_GET['md']);
	if ($md == 0) {
	  //$w 		= AmbilFieldx('ruang', 'RuangID', $_GET['RuangID'], '*');
	  $w 		= mysqli_fetch_array(mysqli_query($koneksi, "select * from perguruantinggi where PerguruanTinggiID='".strfilter($_GET['PerguruanTinggiID'])."'"));
	  $jdl 		= "Edit Perguruan TInggi";
	  $strid 	= "<input type=hidden name='PerguruanTinggiID' value='$w[PerguruanTinggiID]'><b>$w[PerguruanTinggiID]</b>";
	}
	else {
	  $w['NA'] 	= 'N';
	  $jdl 		= "Tambah Perguruan Tinggi";
	  $strid 	= "<input class='form-control form-control-sm' type=text name='PerguruanTinggiID' size=20 maxlength=20>";
	}

	$combopt 		= AmbilCombo2('jenisperguruantinggi', "Nama", 'Nama', $w['JenisPerguruanTinggiID'], '', 'JenisPerguruanTinggiID');
	$cekboxprodi	= AmbilCekBox("prodi", "ProdiID","concat(ProdiID, ' - ', Nama) as NM", "NM", $w['ProdiID'], '.');
	$na 			= ($w['NA'] == 'Y')? 'checked' : '';
	echo "<div class='card'>
	<div class='card-header'>
	<div class='table-responsive'><table id='example' class='table table-sm table-stripedx' style='width:50%'>
	<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>	
	<input type=hidden name='md' value='$md'>
	<input type=hidden name='ndelox' value='$_SESSION[ndelox]'>
	<tr ><th colspan=2 class=ttl>$jdl</th></tr>
	<tr><td>RuangID</td><td>$strid</td></tr>
	<tr><td>Jenis Perguruan Tinggi</td><td><select class='form-control form-control-sm' name='JenisPerguruanTinggiID'>$combopt</select></td></tr>
	<tr><td>Nama</td><td><input type=text class='form-control form-control-sm' name='Nama' value='$w[Nama]' size=40 maxlength=50></td></tr>
	<tr><td>Alamat 1</td><td><input class='form-control form-control-sm' type=text name='Alamat1' value='$w[Alamat1]' size=40 maxlength=50></td></tr>
	<tr><td>Alamat 2</td><td><input class='form-control form-control-sm' type=text name='Alamat2' value='$w[Alamat2]' size=40 maxlength=50></td></tr>
	<tr><td>Kota</td><td><input class='form-control form-control-sm' type=text name='Kota' value='$w[Kota]' size=5 maxlength=5></td></tr>
	
	
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