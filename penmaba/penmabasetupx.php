
<div class="card">
<div class="card-header">
	<div class="form-group row">
			<label class="col-md-10 col-form-label text-md-left"><b style='color:purple'>SETUP PMB</b></label>
			<div class="col-md-2">
				<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/penmabasetupx&act=tambah'>Tambahkan Data</a>
			</div>
			
	</div>
</div>
</div>

<?php if ($_GET['act']==''){ 

echo"<div class='card card-primary card-outline card-outline-tabs'>
						
              <div class='card-header p-0 border-bottom-0'>
                <ul class='nav nav-tabs' id='custom-tabs-three-tab' role='tablist'>
				
                  <li class='nav-item'>
                    <a class='nav-link active' id='custom-tabs-three-home-tab' data-toggle='pill' href='#custom-tabs-three-home' role='tab' aria-controls='custom-tabs-three-home' aria-selected='true'>PERIODE</a>
                  </li>
				  
                  <li class='nav-item'>
                    <a class='nav-link' id='custom-tabs-three-profile-tab' data-toggle='pill' href='#custom-tabs-three-profile' role='tab' aria-controls='custom-tabs-three-profile' aria-selected='false'>HARGA FORMULIR</a>
                  </li>
				  
                  <li class='nav-item'>
                    <a class='nav-link' id='custom-tabs-three-messages-tab' data-toggle='pill' href='#custom-tabs-three-messages' role='tab' aria-controls='custom-tabs-three-messages' aria-selected='false'>KOMPONEN USM</a>
                  </li>
				  
                  <li class='nav-item'>
                    <a class='nav-link' id='custom-tabs-three-settings-tab' data-toggle='pill' href='#custom-tabs-three-settings' role='tab' aria-controls='custom-tabs-three-settings' aria-selected='false'>PERSYARATAN</a>
                  </li>

                </ul>
              </div>
			  
              <div class='card-body'>
                <div class='tab-content' id='custom-tabs-three-tabContent'>
				
                  <div class='tab-pane fade show active' id='custom-tabs-three-home' role='tabpanel' aria-labelledby='custom-tabs-three-home-tab'>
                
					
							<table id='example' class='table table-sm table-striped'>
							<thead>
							<tr style='background:purple;color:white'>
							  <th style='width:10px'>No</th>              
							  <th style='width:20px'>Kode</th>
							  <th style='width:90px'>Nama</th>              
							  <th style='width:60px'>Pendaftaran</th>                                    
							  <th style='width:100px'>Ujian</th>
							  <th style='width:100px'>Pembayaran</th>
							  <th style='text-align:center'>Action</th> 
							</tr>
							</thead>
							<tbody>";
							
							  $tampil = mysqli_query($koneksi, "SELECT * from pmbperiod
									 order by PMBPeriodID Desc");  
								$no = 1;
								while($r=mysqli_fetch_array($tampil)){	
								if ($r['NA']=='N'){
									$c="style=color:green;font-weight:bold";
								}
								else{
									$c="style=color:black";
								}	 
								echo "<tr>
								<td $c>$no</td>            
								<td $c>$r[PMBPeriodID]</td>
								<td $c>$r[Nama]</td>	
								<td $c>".tgl_indo($r['TglMulai'])." s/d ".tgl_indo($r['TglSelesai'])."</td> 
								<td $c>".tgl_indo($r['UjianMulai'])." s/d ".tgl_indo($r['UjianSelesai'])."</td> 
								<td $c>".tgl_indo($r['BayarMulai'])." s/d ".tgl_indo($r['BayarSelesai'])."</td> 	
								<td style='width:70px !important'><center><a class='btn btn-success btn-xs' title='Edit Data' href='index.php?ndelox=penmaba/penmabasetupx&act=edit&PMBPeriodID=$r[PMBPeriodID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'><i class='fa fa-edit'></i></a>
								
								<a class='btn btn-danger btn-xs' title='Hapus Data' href='index.php?ndelox=penmaba/penmabasetupx&hapus=$r[PMBPeriodID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&MhswID=$r[MhswID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
								</center></td>";
								
								echo "</tr>";
								$no++;
								}

								if (isset($_GET['hapus'])){
								mysqli_query($koneksi, "DELETE FROM pmbperiod where PMBPeriodID='".strfilter($_GET['hapus'])."'");
								echo "<script>document.location='index.php?ndelox=penmaba/penmabasetupx&prodi=$_GET[prodi]&tahun=$_GET[tahun]&MhswID=$_GET[MhswID]';</script>";
								}
								
							
							echo"<tbody>
							</table>
							  
                  </div>
                  <div class='tab-pane fade' id='custom-tabs-three-profile' role='tabpanel' aria-labelledby='custom-tabs-three-profile-tab'>
                    
					<table id='example' class='table table-sm table-striped'>
					<thead>
					<tr style='background:purple;color:white'>
					  <th style='width:10px'>No</th>              
					  <th style='width:20px'>Formulir</th>
					  <th style='width:150px'>Harga</th>              
					  <th style='width:200px'>Jumlah Pilihan</th>                                    
					  <th style='text-align:center'>Action</th> 
					</tr>
					</thead>
					<tbody>";
					
					  $tampil = mysqli_query($koneksi, "SELECT * from pmbformulir"); //AND Status NOT IN ('DITOLAK','DITERIMA')   
						$no = 1;
						while($r=mysqli_fetch_array($tampil)){	
						if ($r['NA']=='N'){
							$c="style=color:green";
						}
						else{
							$c="style=color:black";
						}	 
						echo "<tr>
						<td>$no</td>            
						<td>$r[Nama]</td>
						<td>".number_format($r['Harga'])."</td>
						<td>$r[JumlahPilihan]</td>
							
						<td style='width:70px !important'><center><a class='btn btn-success btn-xs' title='Edit Data' href='index.php?ndelox=penmaba/penmabasetupx&act=editformulir&PMBFormulirID=$r[PMBFormulirID]'><i class='fa fa-edit'></i></a>	
						
						</center></td>";
						
						echo "</tr>";
						$no++;
						}
						
					
					echo"<tbody>
					</table>
					
                  </div>
                  <div class='tab-pane fade' id='custom-tabs-three-messages' role='tabpanel' aria-labelledby='custom-tabs-three-messages-tab'>
                    
						<table id='example' class='table table-sm table-striped'>
						<thead>
						<tr style='background:purple;color:white'>
						  <th style='width:10px'>No</th>              
						  <th style='width:20px'>Nama</th>                                  
						</tr>
						</thead>
						<tbody>";
						
						  $tampil = mysqli_query($koneksi, "SELECT * from pmbusm");  
							$no = 1;
							while($r=mysqli_fetch_array($tampil)){		 
							echo "<tr>
							<td>$no</td>            
							<td>$r[Nama]</td>
							</tr>";
							$no++;
							}	
						
						echo"<tbody>
						</table>
					
                  </div>
                  <div class='tab-pane fade' id='custom-tabs-three-settings' role='tabpanel' aria-labelledby='custom-tabs-three-settings-tab'>
                    
				
						<table id='example' class='table table-sm table-striped'>
						<thead>
						<tr style='background:purple;color:white'>
						  <th style='width:10px'>No</th>              
						  <th style='width:300px'>Nama</th>                                  
						</tr>
						</thead>
						<tbody>";
						
						  $tampil = mysqli_query($koneksi, "SELECT * from pmbsyarat");
							$no = 1;
							while($r=mysqli_fetch_array($tampil)){		 
							echo "<tr>
							<td>$no</td>            
							<td>$r[Nama]</td>
							</tr>";
							$no++;
							}	
						
						echo"<tbody>
						</table>
			
				  
                </div>
              </div>	  
            </div>";
			
			
			echo"<div class='card'>
				<div class='card-header'>
				<div class='table-responsive'>
					<div class='box-footer'>
					<button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
					<a href='index.php?ndelox=penmaba/penmabasetupx'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
					</div>
				</div>
				</div>
				</div>	
				</form>";

} //tutup elseif

elseif($_GET['act']=='tambah'){
 if (isset($_POST['kirimkan'])){		
              $query = mysqli_query($koneksi, "INSERT INTO pmbperiod(
							PMBPeriodID,
							KodeID,
							Nama,
							TglMulai,							
							TglSelesai,
							UjianMulai,
							UjianSelesai,
							BayarMulai,
							BayarSelesai,
							TelitiBayarProdi,
							LoginBuat,
							TanggalBuat) 
					VALUES('".strfilter($_POST['PMBPeriodID'])."',
							'SISFO',
							'".strfilter($_POST['Nama'])."',
							'".strfilter($_POST['TglMulai'])."',
							'".strfilter($_POST['TglSelesai'])."',
							'".strfilter($_POST['UjianMulai'])."',
							'".strfilter($_POST['UjianSelesai'])."',
							'".strfilter($_POST['BayarMulai'])."',
							'".strfilter($_POST['BayarSelesai'])."',
							'SI,TI',
							'$_SESSION[_Login]',
							'".date('Y-m-d')."')");
            echo "<script>document.location='index.php?ndelox=penmaba/penmabasetupx&tahun=$_GET[tahun]&prodi=$_GET[prodi]&sukses';</script>";							
    }//tutup kirimkan

echo "
<div class='box-body'> 
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>

<div class='card'>
<div class='card-header'>
<div class='box-header with-border'>
<h3 class='box-title'><b style=color:green;font-size=18px>Setup Pmb </b></h3>
</div>
<div class='table-responsive'>
<table class='table table-sm table-bordered'>
<tbody>
<tr><th  scope='row' colspan='2' style='background-color:purple;color:white'>Setup PMB</th></tr>
<tr><th width='290px' scope='row'>Tahun Akademik</th> <td><input type='text' class='form-control' name='PMBPeriodID' > </td></tr>
<tr><th scope='row'>Nama Tahun</th><td><input type='text' class='form-control' name='Nama' ></td></tr>				
<tr><th scope='row' colspan='2' style='background-color:purple;color:white'>Pendaftaran</th></tr>
<tr><th scope='row'>Tanggal Mulai</th><td><input type='text' id='datepicker' class='form-control tanggal' name='TglMulai' value='".date('Y-m-d')."'></td></tr>
<tr><th scope='row'>Tanggal Selesai</th><td><input type='text' id='datepicker2' class='form-control tanggal' name='TglSelesai' value='".date('Y-m-d')."'></td></tr>
<tr><th scope='row' colspan='2' style='background-color:purple;color:white'>Ujian Saringan Masuk</th></tr>
<tr><th scope='row'>Tanggal Mulai</th><td><input type='text' id='datepicker3' class='form-control tanggal' name='UjianMulai' value='".date('Y-m-d')."'></td></tr>
<tr><th scope='row'>Tanggal Selesai</th><td><input type='text' id='datepicker4' class='form-control tanggal' name='UjianSelesai' value='".date('Y-m-d')."'></td></tr>
<tr><th scope='row' colspan='2' style='background-color:purple;color:white'>Pembayaran dan Pendaftaran Ulang</th></tr>
<tr><th scope='row'>Tanggal Mulai</th><td><input type='text' id='datepicker5' class='form-control tanggal' name='BayarMulai' value='".date('Y-m-d')."'></td></tr>
<tr><th scope='row'>Tanggal Selesai</th><td><input type='text' id='datepicker6' class='form-control tanggal' name='BayarSelesai' value='".date('Y-m-d')."'></td></tr>
<tr><th scope='row'>Tetapkan pada Prodi</th><td><input type='text' class='form-control' name='ae' value='$tg[Nama]'></td></tr>
</tbody>
</table>
<div class='box-footer'>
<button type='submit' name='kirimkan' class='btn btn-info'>Tambahkan</button>
<a href='index.php?ndelox=penmaba/penmabasetupx'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>     
</div>
</div>
</div>


</form>
</div>";
}

elseif($_GET['act']=='edit'){
if (isset($_POST['update'])){
	$query = mysqli_query($koneksi, "UPDATE pmbperiod SET 
			  PMBPeriodID 	= '".strfilter($_POST['PMBPeriodID'])."',
			  Nama 			= '".strfilter($_POST['Nama'])."',
			  TglMulai 		= '".date('Y-m-d', strtotime($_POST['TglMulai']))."',						
			  TglSelesai 	= '".date('Y-m-d', strtotime($_POST['TglSelesai']))."',
			  UjianMulai 	= '".date('Y-m-d', strtotime($_POST['UjianMulai']))."',
			  UjianSelesai 	= '".date('Y-m-d', strtotime($_POST['UjianSelesai']))."',
			  BayarMulai 	= '".date('Y-m-d', strtotime($_POST['BayarMulai']))."',
			  BayarSelesai 	= '".date('Y-m-d', strtotime($_POST['BayarSelesai']))."',
			  NA			= '".strfilter($_POST['NA'])."',
			  LoginEdit 	= '$_SESSION[_Login]',
			  TanggalEdit 	= '".date('Y-m-d')."'
			  where PMBPeriodID='".strfilter($_POST['PMBPeriodID'])."'");
if ($query){
  echo "<script>document.location='index.php?ndelox=penmaba/penmabasetupx&tahun=$_POST[tahun]&prodi=$_POST[prodi]&IDX=$_POST[IDX]&sukses';</script>";
}else{
  echo "<script>document.location='index.php?ndelox=penmaba/penmabasetupx&tahun=$_POST[tahun]&prodi=$_POST[prodi]&IDX=$_POST[IDX]&gagal';</script>";
}
}
    
$e = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM pmbperiod where PMBPeriodID='".strfilter($_GET['PMBPeriodID'])."'"));
echo "
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>

<div class='card'>
<div class='card-header'>
<div class='box-header with-border'>
<h3 class='box-title'><b style=color:green;font-size=18px>Edit PMB </b></h3>
</div>
<div class='table-responsive'>
<table class='table table-sm table-bordered'>
<tbody>
<tr><th  scope='row' colspan='2' style='background-color:purple;color:white'>Setup PMB</th></tr>
<tr><th width='290px' scope='row'>Tahun Akademik</th> <td><input type='text' class='form-control' name='PMBPeriodID' value='$e[PMBPeriodID]'> </td></tr>
<tr><th scope='row'>Nama Tahun</th><td><input type='text' class='form-control' name='Nama' value='$e[Nama]'></td></tr>				
<tr><th scope='row' colspan='2' style='background-color:purple;color:white'>Pendaftaran</th></tr>
<tr><th scope='row'>Tanggal Mulai</th><td><input type='text' id='datepicker' class='form-control tanggal' name='TglMulai' value='".date('d-m-Y', strtotime($e['TglMulai']))."'></td></tr>
<tr><th scope='row'>Tanggal Selesai</th><td><input type='text' id='datepicker2' class='form-control tanggal' name='TglSelesai' value='".date('d-m-Y', strtotime($e['TglSelesai']))."'></td></tr>
<tr><th scope='row' colspan='2' style='background-color:purple;color:white'>Ujian Saringan Masuk</th></tr>
<tr><th scope='row'>Tanggal Mulai</th><td><input type='text' id='datepicker3' class='form-control tanggal' name='UjianMulai' value='".date('d-m-Y', strtotime($e['UjianMulai']))."'></td></tr>
<tr><th scope='row'>Tanggal Selesai</th><td><input type='text' id='datepicker4' class='form-control tanggal' name='UjianSelesai' value='".date('d-m-Y', strtotime($e['UjianSelesai']))."'></td></tr>
<tr><th scope='row' colspan='2' style='background-color:purple;color:white'>Pembayaran dan Pendaftaran Ulang</th></tr>
<tr><th scope='row'>Tanggal Mulai</th><td><input type='text' id='datepicker5' class='form-control tanggal' name='BayarMulai' value='".date('d-m-Y', strtotime($e['BayarMulai']))."'></td></tr>
<tr><th scope='row'>Tanggal Selesai</th><td><input type='text' id='datepicker6' class='form-control tanggal' name='BayarSelesai' value='".date('d-m-Y', strtotime($e['BayarSelesai']))."'></td></tr>
<tr><th scope='row'>Aktif</th>                <td>";
if ($e['NA']=='Y'){
	echo "<input type='radio' name='NA' value='N'> Ya
	<input type='radio' name='NA' value='Y' checked> Tidak";
}else{
	echo "<input type='radio' name='NA' value='N' checked> Ya 
	<input type='radio' name='NA' value='Y'> Tidak";
}
echo "</td></tr> 
</tbody>
</table>
<div class='box-footer'>
<button type='submit' name='update' class='btn btn-info'>Perbaharui</button>
<a href='index.php?ndelox=penmaba/penmabasetupx'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>    
</div>
</div>
</div>


</form>
</div>";
}


elseif($_GET['act']=='editformulir'){
if (isset($_POST['update'])){
	$query = mysqli_query($koneksi, "UPDATE pmbformulir SET 			 
			  Nama 				= '".strfilter($_POST['Nama'])."',
			  JumlahPilihan 	= '".strfilter($_POST['JumlahPilihan'])."',						
			  Harga 			= '".strfilter($_POST['Harga'])."'
			  where PMBFormulirID='".strfilter($_POST['PMBFormulirID'])."'");
if ($query){
  echo "<script>document.location='index.php?ndelox=penmaba/penmabasetupx&PMBFormulirID=$_POST[PMBFormulirID]&sukses';</script>";
}else{
  echo "<script>document.location='index.php?ndelox=penmaba/penmabasetupx&PMBFormulirID=$_POST[PMBFormulirID]&gagal';</script>";
}
}
    
$e = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM pmbformulir where PMBFormulirID='".strfilter($_GET['PMBFormulirID'])."'"));
echo "
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>

<div class='card'>
<div class='card-header'>
<div class='box-header with-border'>
<h3 class='box-title'><b style=color:green;font-size=18px>Edit Formulir </b></h3>
</div>
<div class='table-responsive'>
<table class='table table-condensed table-bordered'>
<tbody>
<input type=hidden name='PMBFormulirID' value='$e[PMBFormulirID]'>
<tr><th  scope='row' colspan='2' style='background-color:purple;color:white'>Ubah Formulir</th></tr>
<tr><th scope='row' width=200px>Nama Formulir</th><td><input type='text' class='form-control tanggal' name='Nama' value='$e[Nama]'></td></tr>				
<tr><th scope='row'>Jumlah Pilihan</th><td><input type='text' class='form-control tanggal' name='JumlahPilihan' value='$e[JumlahPilihan]'></td></tr>
<tr><th scope='row'>Harga</th><td><input type='text' class='form-control tanggal' name='Harga' value='$e[Harga]'></td></tr>
</tbody>
</table>
<div class='box-footer'>
<button type='submit' name='update' class='btn btn-info'>Perbaharui</button>
<a href='index.php?ndelox=penmaba/penmabasetupx'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>      
</div>
</div>
</div>


</form>
</div>";
}
?>