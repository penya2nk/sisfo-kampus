<div class='card'>
<div class='card-header'>

<div class="form-group row">
<label class="col-md-7 col-form-label text-md-right"><b style='color:purple'>Penjualan Formulir</b></label>
<div class="col-md-2">	
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='pmb/pmbjualform'> 
<select name='tahun' class='form-control form-control-sm form-control form-control-sm-sm' onChange='this.form.submit()'>
<?php 
echo "<option value=''>- Pilih Periode PMB -</option>";
$tahun = mysqli_query($koneksi, "select distinct(PMBPeriodID),NA FROM pmbperiod order by PMBPeriodID Desc limit 8"); //and NA='N'
while ($k = mysqli_fetch_array($tahun)){
  if ($_GET[tahun]==$k[PMBPeriodID]){
	echo "<option value='$k[PMBPeriodID]' selected>$k[PMBPeriodID]</option>";
  }else{
	echo "<option value='$k[PMBPeriodID]'>$k[PMBPeriodID]</option>";
  }
}
?>
</select>
</div>                


<div class="col-md-2">	               
<select name='formulir' class='form-control form-control-sm form-control form-control-sm-sm' onChange='this.form.submit()'>
<?php 
echo "<option value=''>- Pilih Jenis Formulir -</option>";
$fr = mysqli_query($koneksi, "select * from pmbformulir");
while ($f = mysqli_fetch_array($fr)){
   if ($_GET[formulir]==$f[PMBFormulirID]){
	echo "<option value='$f[PMBFormulirID]' selected>$f[Nama] - $f[Harga]</option>";
  }else{
	echo "<option value='$f[PMBFormulirID]'>$f[Nama]  - $f[Harga]</option>";
  }
}
?>
</select>
</div>                


<div class="col-md-1">	
<input type="submit"  class='btn btn-success btn-sm' value='Lihat'>
</form>
</div><!-- /.box-header -->
</div>
</div>
</div>


<?php if ($_GET[act]==''){ 
	
echo "<div class='card card-primary card-outline card-outline-tabs'>
						
              <div class='card-header p-0 border-bottom-0'>
                <ul class='nav nav-tabs' id='custom-tabs-three-tab' role='tablist'>
				
                  <li class='nav-item'>
                    <a class='nav-link active' id='custom-tabs-three-home-tab' data-toggle='pill' href='#custom-tabs-three-home' role='tab' aria-controls='custom-tabs-three-home' aria-selected='true'>Data Form</a>
                  </li>
				  
                  <li class='nav-item'>
                    <a class='nav-link' id='custom-tabs-three-profile-tab' data-toggle='pill' href='#custom-tabs-three-profile' role='tab' aria-controls='custom-tabs-three-profile' aria-selected='false'>Jumlah Formulir Terjual</a>
                  </li>
				  
                  <li class='nav-item'>
                    <a class='nav-link' id='custom-tabs-three-messages-tab' data-toggle='pill' href='#custom-tabs-three-messages' role='tab' aria-controls='custom-tabs-three-messages' aria-selected='false'>Jual Formulir</a>
                  </li>

				  <li class='nav-item'>
				  <a class='nav-link' id='custom-tabs-three-kwitansi-tab' data-toggle='pill' href='#custom-tabs-three-kwitansi' role='tab' aria-controls='custom-tabs-three-kwitansi' aria-selected='false'>Edit Kwitansi</a>
				</li>
				  
                
                </ul>
              </div>
			  
              <div class='card-body'>
                <div class='tab-content' id='custom-tabs-three-tabContent'>
				
                  <div class='tab-pane fade show active' id='custom-tabs-three-home' role='tabpanel' aria-labelledby='custom-tabs-three-home-tab'>
				  			<div class='table-responsive'>
							<table id='example1' class='table table-sm table-striped'>
							<thead>
							<tr style='background:purple;color:white'>
							<th  width='10'>No</th>					  					 
							<th width='2'>Periode</th>
							<th width='30'>Tanggal</th> 
							<th width='80'>No Kwitansi</th>
							<th width='100'>No Bukti Setoran</th>
							<th width='200'>Pembeli</th>
							<th width='100'>Formulir</th>
							<th width='30'>Jml Pilihan</th>
							<th width='30'>Harga</th>
							</tr>
							</thead>
							<tbody>";
							

							$thnMuhai = date('Y');
							$squery = mysqli_query($koneksi, "select
							pmbformulir.Nama AS NamaFormulir,pmbformulir.Harga,pmbformulir.JumlahPilihan,
							pmbformjual.*
							FROM pmbformulir,pmbformjual
							WHERE pmbformulir.PMBFormulirID=pmbformjual.PMBFormulirID
							AND left(pmbformjual.PMBPeriodID,4)='$thnMuhai'
							order by pmbformjual.PMBFormJualID desc"); 
							$no = 1;
							while($r=mysqli_fetch_array($squery)){	
							$Namax 	= strtolower($r[Nama]);
							$Nama	= ucwords($Namax);	 
							echo"<tr>
								  <td>$no</td>					  					 
								  <td>$r[PMBPeriodID]</td>
								  <td>".tgl_indo($r[TanggalBuat])."</td>
								  <td>$r[PMBFormJualID]<a href='index.php?ndelox=pmb/pmbjualform&act=editkwitansi&PMBFormJualID=$r[PMBFormJualID]&tahun=$r[PMBPeriodID]&formulir=$r[PMBFormulirID]'> [Edit] </a></td> 
								  <td>$r[BuktiSetoran]</td>
								  <td>$Nama <a href='index.php?ndelox=pmb/pmbformdaftar&act=tambahform&PMBFormJualID=$r[PMBFormJualID]&tahun=$r[PMBPeriodID]&formulir=$r[PMBFormulirID]'>[Inputkan Formulir]</a></td>	
								  <td>$r[NamaFormulir]</td>
								  <td>$r[JumlahPilihan]</td>
								  <td>".number_format($r[Harga])."</td>
								  </tr>";
							$no++;
							}	
							
							echo"<tbody>
							</table>
							</div>
							
							  
                  </div>
                  <div class='tab-pane fade' id='custom-tabs-three-profile' role='tabpanel' aria-labelledby='custom-tabs-three-profile-tab'>
                  
								<caption><b>Jumlah Formulir Terjual Secara Keseluruhan di Tahun  ".substr($_GET[tahun],0,4)."</b></caption>
								<div class='table-responsive'>
								<table id='example' class='table table-sm table-striped'>
								<thead>
								<tr style='background:purple;color:white'>
								  <th style='width:10px'>No</th>              
								  <th style='width:20px'>Jenis Formulir</th>
								  <th style='width:200px;text-align:right'>Jumlah</th>              
								</tr>
								</thead>
								<tbody>";
								

								if ($_GET[formulir]){
									$sq = mysqli_query($koneksi, "select COUNT(pmbformjual.PMBFormJualID) AS JumForm, pmbformulir.Nama AS JenisFormulir
									FROM pmbformjual, pmbformulir 
									WHERE pmbformjual.PMBFormulirID=pmbformulir.PMBFormulirID
									AND left(pmbformjual.PMBPeriodID,4)='".strfilter(substr($_GET[tahun],0,4))."'
									AND pmbformjual.PMBFormulirID='".strfilter($_GET[formulir])."'
									GROUP BY pmbformjual.PMBFormulirID");  
								}
								else{
									$sq = mysqli_query($koneksi, "select COUNT(pmbformjual.PMBFormJualID) AS JumForm, pmbformulir.Nama AS JenisFormulir
									FROM pmbformjual, pmbformulir 
									WHERE pmbformjual.PMBFormulirID=pmbformulir.PMBFormulirID
									AND left(pmbformjual.PMBPeriodID,4)='".strfilter(substr($_GET[tahun],0,4))."'
									GROUP BY pmbformjual.PMBFormulirID");  
								}
								while($r=mysqli_fetch_array($sq)){	 	
								$no++;
								$total += $r['JumForm'];
								echo "<tr>
								<td $c>$no</td>            
								<td $c >$r[JenisFormulir]</td>
								<td $c style='text-align:right'>$r[JumForm] </td>
								</tr>";
								}
								
								echo"<tbody>
								<tr>
								  <th style='width:10px'></th>              
								  <th style='width:20px'>Total</th>
								  <th style='width:200px;text-align:right'>$total Orang</th>              
								</tr>
								</table>
								</div>


                  </div>
                  <div class='tab-pane fade' id='custom-tabs-three-messages' role='tabpanel' aria-labelledby='custom-tabs-three-messages-tab'>";

							if (isset($_POST[kirimkan])){	 
								if (empty($_POST[PMBFormulirID])){
									exit("<script>window.alert('Pilih Periode dan Jenis Formulir Terlebih Dahulu!');
									window.location='index.php?ndelox=pmb/pmbjualform&tahun=$_GET[tahun]&formulir=$_GET[formulir]';</script>");
								}else{	  
								$query = mysqli_query($koneksi, "INSERT INTO pmbformjual(
											PMBFormJualID,
											PMBFormulirID,
											KodeID,
											Tanggal,
											PMBPeriodID,							
											BuktiSetoran,
											Nama,				
											LoginBuat,
											TanggalBuat,
											Keterangan,
											Jumlah,
											OK) 
									VALUES('".strfilter($_POST[PMBFormJualID])."',
											'".strfilter($_POST[PMBFormulirID])."',
											'SISFO',
											'".strfilter($_POST[Tanggal])."',
											'".strfilter($_POST[PMBPeriodID])."',
											'".strfilter($_POST[BuktiSetoran])."',
											'".strfilter($_POST[Nama])."',
											'$_SESSION[_Login]',
											'".date('Y-m-d')."',
											'".strfilter($_POST[Keterangan])."',
											'".strfilter($_POST[Harga])."',
											'Y')");
									echo "<script>document.location='index.php?ndelox=pmb/pmbjualform&tahun=$_POST[PMBPeriodID]&formulir=$_POST[PMBFormulirID]&sukses';</script>";							
								}
							} 
							$frm 			= mysqli_fetch_array(mysqli_query($koneksi, "select * FROM pmbformulir  WHERE PMBFormulirID='".strfilter($_GET[formulir])."'"));
							$PeriodAktif 	= mysqli_fetch_array(mysqli_query($koneksi, "select * FROM pmbperiod where NA='N'"));
							//$pmbaktif 		= substr($PeriodAktif[PMBPeriodID],0,4); //digit pmbperiod 4

							//select max ganti dengan Desc Limit 1 
							// $data 	= mysqli_fetch_array(mysqli_query($koneksi, "select PMBFormJualID as maxID FROM pmbformjual 
							// 										ORDER BY PMBFormJualID DESC LIMIT 1")); 
							// $idMax 	= $data['maxID'];
							// $NoUrut = (int) substr($idMax, 5, 4);
							// $NoUrut++; 
							// $NewID 	= substr($pmbaktif,0,4) .sprintf('%04s', $NoUrut);


							$pmbaktif 		= substr($PeriodAktif[PMBPeriodID],0,5); //digit pmbperiod 4
							//select max ganti dengan Desc Limit 1 
							$data 	= mysqli_fetch_array(mysqli_query($koneksi, "select PMBFormJualID as maxID FROM pmbformjual 
																	ORDER BY PMBFormJualID DESC LIMIT 1")); 
							$idMax 	= $data['maxID'];
							$NoUrut = (int) substr($idMax, 5, 4);
							$NoUrut++; 
							//%04s = format 4 digit terakhir Nomor Urut
							$NewID 	= substr($pmbaktif,0,5) .sprintf('%04s', $NoUrut);
							echo "

							<div class='table-responsive'>
							<table class='table table-condensed table-sm'>
							<tbody>
							<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
							<input type=hidden name='PMBPeriodID' value='$PeriodAktif[PMBPeriodID]'>
							<input type=hidden name='PMBFormulirID' value='$_GET[formulir]'>
							<tr ><th  scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>Penjualan Formulir</th></tr>
							<tr><th width='290px' scope='row'>PMBFormJualID</th> <td><input type='text' class='form-control form-control-sm' name='PMBFormJualID' value='$NewID' readonly> </td></tr>
							<tr><th width='200px' scope='row'>Jenis Formulir</th> <td><input type='text' class='form-control form-control-sm' required name='x' value='$frm[Nama]' readonly> </td></tr>
							<tr><th  scope='row'>Tanggal</th> <td><input type='text' class='form-control form-control-sm' name='Tanggal' value='".date('Y-m-d')."' readonly> </td></tr>
										
							<tr><th scope='row'>Jumlah Pilihan</th><td><input type='text' class='form-control form-control-sm' name='JumlahPilihan' value='$frm[JumlahPilihan]' readonly></td></tr>
							<tr><th scope='row'>Harga</th><td><input type='text' class='form-control form-control-sm' name='Harga' value='$frm[Harga]' readonly></td></tr>
							<tr><th scope='row'>No Bukti Setoran</th><td><input type='text' class='form-control form-control-sm' name='BuktiSetoran' ></td></tr>
							<tr><th scope='row'>Pembeli</th><td><input type='text' class='form-control form-control-sm' name='Nama' ></td></tr>
							<tr><th scope='row'>Keterangan</th><td><input type='text' class='form-control form-control-sm' name='Keterangan'></td></tr>
							</tbody>
							</table>
							</div>
							
							<div class='box-footer'>
							<button type='submit' name='kirimkan' class='btn btn-info'>Jual Formulir</button>
							<a href='index.php?ndelox=pmb/pmbjualform'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
								
							</div>
							</form>
					
                  </div>


				  <div class='tab-pane fade' id='custom-tabs-three-kwitansi' role='tabpanel' aria-labelledby='custom-tabs-three-kwitansi-tab'>";
                
								if (isset($_POST[cari])){		
									echo"Caiee";						
								}//tutup kirimkan
								echo "
								<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
								<table class='table table-condensed table-sm'>
								<tbody>
								<tr><th scope='row' width=200px>Nomor Kwitansi</th><td><input type='text' class='form-control form-control-sm' name='ae' value='$tg[Nama]'></td></tr>
								</tbody>
								</table>
								
							
								<div class='box-footer'>
								<button type='submit' name='cari' class='btn btn-info'>Cari</button>
								</div>
								</form>
								
                
				  </div>
			
				  
                </div>
              </div>	  
            </div>";
			

} //tutup elseif

//-----------------------------------------------------------------------------------------------------------------------------
elseif($_GET[act]=='tambah'){
if (isset($_POST[kirimkan])){		
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
		VALUES('".strfilter($_POST[PMBPeriodID])."',
				'SISFO',
				'".strfilter($_POST[Nama])."',
				'".strfilter($_POST[TglMulai])."',
				'".strfilter($_POST[TglSelesai])."',
				'".strfilter($_POST[UjianMulai])."',
				'".strfilter($_POST[UjianSelesai])."',
				'".strfilter($_POST[BayarMulai])."',
				'".strfilter($_POST[BayarSelesai])."',
				'SI,TI',
				'$_SESSION[id]',
				'".date('Y-m-d')."')");
echo "<script>document.location='index.php?ndelox=pmb/pmbjualform&tahun=$_GET[tahun]&formulir=$_GET[formulir]&sukses';</script>";							
}//tutup kirimkan

echo "<div class='col-xs-12'>
<div class='box box-info'>			
<div class='box-header with-border'>
<h3 class='box-title'><b style=color:green;font-size=18px>Setup Pmb </b></h3>
</div>

<div class='box-body'> 
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>

<div class='col-md-12'>
<table class='table table-condensed table-sm'>
<tbody>
<tr><th  scope='row' colspan='2' style='background-color:#FFCC00'>Setup PMB</th></tr>
<tr><th width='290px' scope='row'>Tahun Akademik</th> <td><input type='text' class='form-control form-control-sm' name='PMBPeriodID' > </td></tr>
<tr><th scope='row'>Nama Tahun</th><td><input type='text' class='form-control form-control-sm' name='Nama' ></td></tr>				
<tr><th scope='row' colspan='2' style='background-color:#FFCC00'>Pendaftaran</th></tr>
<tr><th scope='row'>Tanggal Mulai</th><td><input type='text' id='datepicker' class='form-control form-control-sm' name='TglMulai' value='".date('Y-m-d')."'></td></tr>
<tr><th scope='row'>Tanggal Selesai</th><td><input type='text' id='datepicker2' class='form-control form-control-sm' name='TglSelesai' value='".date('Y-m-d')."'></td></tr>
<tr><th scope='row' colspan='2' style='background-color:#FFCC00'>Ujian Saringan Masuk</th></tr>
<tr><th scope='row'>Tanggal Mulai</th><td><input type='text' id='datepicker3' class='form-control form-control-sm' name='UjianMulai' value='".date('Y-m-d')."'></td></tr>
<tr><th scope='row'>Tanggal Selesai</th><td><input type='text' id='datepicker4' class='form-control form-control-sm' name='UjianSelesai' value='".date('Y-m-d')."'></td></tr>
<tr><th scope='row' colspan='2' style='background-color:#FFCC00'>Pembayaran dan Pendaftaran Ulang</th></tr>
<tr><th scope='row'>Tanggal Mulai</th><td><input type='text' id='datepicker5' class='form-control form-control-sm' name='BayarMulai' value='".date('Y-m-d')."'></td></tr>
<tr><th scope='row'>Tanggal Selesai</th><td><input type='text' id='datepicker6' class='form-control form-control-sm' name='BayarSelesai' value='".date('Y-m-d')."'></td></tr>
<tr><th scope='row'>Tetapkan pada Prodi</th><td><input type='text' class='form-control form-control-sm' name='ae' value='$tg[Nama]'></td></tr>
</tbody>
</table>
</div>
</div>

<div class='box-footer'>
<button type='submit' name='kirimkan' class='btn btn-info'>Tambahkan</button>
<a href='index.php?ndelox=pmb/pmbjualform'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
<input type=reset name='Reset' value='&nbsp;Reset&nbsp;&nbsp;&nbsp;&nbsp;' class='btn btn-info'>      
</div>
</form>
</div>";
}

elseif($_GET[act]=='edit'){
if (isset($_POST[update])){
	$query = mysqli_query($koneksi, "UPDATE pmbperiod SET 
			  PMBPeriodID 	= '".strfilter($_POST[PMBPeriodID])."',
			  Nama 			= '".strfilter($_POST[Nama])."',
			  TglMulai 		= '".strfilter($_POST[TglMulai])."',						
			  TglSelesai 	= '".strfilter($_POST[TglSelesai])."',
			  UjianMulai 	= '".strfilter($_POST[UjianMulai])."',
			  UjianSelesai 	= '".strfilter($_POST[UjianSelesai])."',
			  BayarMulai 	= '".strfilter($_POST[BayarMulai])."',
			  BayarSelesai 	= '".strfilter($_POST[BayarSelesai])."',
			  NA			= '".strfilter($_POST[NA])."',
			  LoginEdit 	= '$_SESSION[id]',
			  TanggalEdit 	= '".date('Y-m-d')."'
			  where PMBPeriodID='".strfilter($_POST[PMBPeriodID])."'");
if ($query){
  echo "<script>document.location='index.php?ndelox=pmb/pmbjualform&tahun=$_POST[tahun]&formulir=$_GET[formulir]&IDX=$_POST[IDX]&sukses';</script>";
}else{
  echo "<script>document.location='index.php?ndelox=pmb/pmbjualform&tahun=$_POST[tahun]&formulir=$_GET[formulir]&IDX=$_POST[IDX]&gagal';</script>";
}
}
    
$e = mysqli_fetch_array(mysqli_query($koneksi, "select * FROM pmbperiod where PMBPeriodID='".strfilter($_GET[PMBPeriodID])."'"));
echo "<div class='col-xs-12'>
<div class='box box-info'>			
<div class='box-header with-border'>
<h3 class='box-title'><b style=color:green;font-size=18px>Edit PMB </b></h3>
</div>

<div class='box-body'> 
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>

<div class='col-md-12'>
<table class='table table-condensed table-sm'>
<tbody>
<tr><th  scope='row' colspan='2' style='background-color:#FFCC00'>Setup PMB</th></tr>
<tr><th width='290px' scope='row'>Tahun Akademik</th> <td><input type='text' class='form-control form-control-sm' name='PMBPeriodID' value='$e[PMBPeriodID]'> </td></tr>
<tr><th scope='row'>Nama Tahun</th><td><input type='text' class='form-control form-control-sm' name='Nama' value='$e[Nama]'></td></tr>				
<tr><th scope='row' colspan='2' style='background-color:#FFCC00'>Pendaftaran</th></tr>
<tr><th scope='row'>Tanggal Mulai</th><td><input type='text' id='datepicker' class='form-control form-control-sm' name='TglMulai' value='$e[TglMulai]'></td></tr>
<tr><th scope='row'>Tanggal Selesai</th><td><input type='text' id='datepicker2' class='form-control form-control-sm' name='TglSelesai' value='$e[TglSelesai]'></td></tr>
<tr><th scope='row' colspan='2' style='background-color:#FFCC00'>Ujian Saringan Masuk</th></tr>
<tr><th scope='row'>Tanggal Mulai</th><td><input type='text' id='datepicker3' class='form-control form-control-sm' name='UjianMulai' value='$e[UjianMulai]'></td></tr>
<tr><th scope='row'>Tanggal Selesai</th><td><input type='text' id='datepicker4' class='form-control form-control-sm' name='UjianSelesai' value='$e[UjianSelesai]'></td></tr>
<tr><th scope='row' colspan='2' style='background-color:#FFCC00'>Pembayaran dan Pendaftaran Ulang</th></tr>
<tr><th scope='row'>Tanggal Mulai</th><td><input type='text' id='datepicker5' class='form-control form-control-sm' name='BayarMulai' value='$e[BayarMulai]'></td></tr>
<tr><th scope='row'>Tanggal Selesai</th><td><input type='text' id='datepicker6' class='form-control form-control-sm' name='BayarSelesai' value='$e[BayarSelesai]'></td></tr>
<tr><th scope='row'>Aktif</th>                <td>";
if ($e[NA]=='Y'){
	echo "<input type='radio' name='NA' value='N'> Ya
	<input type='radio' name='NA' value='Y' checked> Tidak";
}else{
	echo "<input type='radio' name='NA' value='N' checked> Ya 
	<input type='radio' name='NA' value='Y'> Tidak";
}
echo "</td></tr> 
</tbody>
</table>
</div>
</div>

<div class='box-footer'>
<button type='submit' name='update' class='btn btn-info'>Perbaharui</button>
<a href='index.php?ndelox=pmb/pmbjualform'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
<input type=reset name='Reset' value='&nbsp;Reset&nbsp;&nbsp;&nbsp;&nbsp;' class='btn btn-info'>      
</div>
</form>
</div>";
}


elseif($_GET[act]=='editkwitansi'){
if (isset($_POST[updatekw])){
	$query = mysqli_query($koneksi, "UPDATE pmbformjual SET 
			  PMBPeriodID 	= '".strfilter($_POST[PMBPeriodID])."',
			  BuktiSetoran 	= '".strfilter($_POST[BuktiSetoran])."',
			  Nama 			= '".strfilter($_POST[Nama])."',						
			  Keterangan 	= '".strfilter($_POST[Keterangan])."',
			  PMBFormulirID = '".strfilter($_POST[formulir])."',
			  LoginEdit 	= '$_SESSION[_Login]',
			  TanggalEdit 	= '".date('Y-m-d')."'
			  where PMBFormJualID='".strfilter($_POST[PMBFormJualID])."'");
if ($query){
  echo "<script>document.location='index.php?ndelox=pmb/pmbjualform&tahun=$_POST[tahun]&formulir=$_GET[formulir]&IDX=$_POST[IDX]&sukses';</script>";
}else{
  echo "<script>document.location='index.php?ndelox=pmb/pmbjualform&tahun=$_POST[tahun]&formulir=$_GET[formulir]&IDX=$_POST[IDX]&gagal';</script>";
}
}
    
$kw = mysqli_fetch_array(mysqli_query($koneksi, "select * FROM pmbformjual where PMBFormJualID='".strfilter($_GET[PMBFormJualID])."'"));
$j  = mysqli_fetch_array(mysqli_query($koneksi, "select * FROM pmbformulir where PMBFormulirID='$kw[PMBFormulirID]'"));
echo "
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>

<div class='card'>
<div class='card-header'>
<div class='box-header with-border'>
<h3 class='box-title'><b style=color:green;font-size=18px>Edit Kwitansi </b></h3>
</div>
<div class='table-responsive'>
<table class='table table-condensed table-sm'>
<tbody>
<input type='hidden' name='PMBFormJualID' value='$kw[PMBFormJualID]'>
<tr><th  scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>Edit Kwitansi</th></tr>
<tr><th width='290px' scope='row'>Nomor Kwitansi</th> <td><b>$kw[PMBFormJualID]</b> </td></tr>
<tr><th scope='row'>Periode Gelombang</th> <td><input type='text' class='form-control form-control-sm' name='PMBPeriodID' value='$kw[PMBPeriodID]'> </td></tr>
<tr><th scope='row'>Bukti Setoran Bank</th><td><input type='text'  class='form-control form-control-sm' name='BuktiSetoran' value='$kw[BuktiSetoran]'></td></tr>
<tr><th scope='row'>Nama Pembeli</th><td><input type='text'  class='form-control form-control-sm' name='Nama' value='$kw[Nama]'></td></tr>
<tr><th  scope='row'>Jenis Formulir</th> <td><b>$j[Nama] </b></td></tr>
<tr><th  scope='row'>Harga </th> <td><b>$j[Harga] </b></td></tr>
<tr><th  scope='row'>Jumlah Pilihan</th> <td><b>$j[JumlahPilihan] </b></td></tr>
<tr><th scope='row'>Ganti Formulir</th><td>
<select name='formulir' class='form-control form-control-sm'>
<option value=''>- Pilih Jenis Formulir -</option>";

$fr = mysqli_query($koneksi, "select * from pmbformulir");
while ($f = mysqli_fetch_array($fr)){
   if ($kw[PMBFormulirID]==$f[PMBFormulirID]){
	echo "<option value='$f[PMBFormulirID]' selected>$f[Nama] - $f[Harga]</option>";
  }else{
	echo "<option value='$f[PMBFormulirID]'>$f[Nama]  - $f[Harga]</option>";
  }
}
echo"</select>
</td></tr>
<tr><th scope='row'>Keterangan</th><td><input type='text'  class='form-control form-control-sm' name='Keterangan' value='$kw[Keterangan]'></td></tr>

</tbody>
</table>
	
	<div class='box-footer'>
	<button type='submit' name='updatekw' class='btn btn-info'>Perbaharui</button>
	<a href='index.php?ndelox=pmb/pmbjualform'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
	<input type=reset name='Reset' value='&nbsp;Reset&nbsp;&nbsp;&nbsp;&nbsp;' class='btn btn-info'>      
	</div>
</div>
</div>
</div>
</form>
";
}
?>