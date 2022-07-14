<div class="card">
<div class="card-header">
<h3 class="box-title"><b style=color:green;font-size=18px>Formulir PMB</b>  <a href='index.php?ndelox=pmb/pmbjualform&PMBFormJualID=$_GET[PMBFormJualID]&tahun=$_GET[tahun]&formulir=$_GET[formulir]'> [Back Penjualan Formulir] </a> </h3>


<div class="form-group row">
		<label class="col-md-5 col-form-label text-md-right"><b style='color:purple'>Formulir PMB</b></label>
		<div class="col-md-2">
		<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
		<input type="hidden" name='ndelox' value='penmaba/penmabaformdaftar'> 
		<select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
		<?php 
		echo "<option value=''>- Pilih Gelombang -</option>";
		$tahun = mysqli_query($koneksi, "SELECT distinct(PMBPeriodID),NA FROM pmbperiod order by PMBPeriodID Desc limit 8"); //and NA='N'
		while ($k = mysqli_fetch_array($tahun)){
		if ($_GET['tahun']==$k['PMBPeriodID']){
			echo "<option value='$k[PMBPeriodID]' selected>$k[PMBPeriodID]</option>";
		}else{
			echo "<option value='$k[PMBPeriodID]'>$k[PMBPeriodID]</option>";
		}
		}
		?>
		</select>
		</div>


		<div class="col-md-2">          
		<select name='formulir' class='form-control form-control-sm' onChange='this.form.submit()'>
		<?php 
		echo "<option value=''>- Pilih Jenis Formulir -</option>";
		$fr = mysqli_query($koneksi, "SELECT * from pmbformulir");
		while ($f = mysqli_fetch_array($fr)){
		if ($_GET['formulir']==$f['PMBFormulirID']){
			echo "<option value='$f[PMBFormulirID]' selected>$f[Nama] - $f[Harga]</option>";
		}else{
			echo "<option value='$f[PMBFormulirID]'>$f[Nama]  - $f[Harga]</option>";
		}
		}
		?>
		</select>
		</div>



		<div class="col-md-2">
		<select name='program' class='form-control form-control-sm' onChange='this.form.submit()'>
		<?php 
		echo "<option value=''>- Pilih Program -</option>";
		$fr = mysqli_query($koneksi, "SELECT * from program");
		while ($f = mysqli_fetch_array($fr)){
		if ($_GET['program']==$f['ProgramID']){
			echo "<option value='$f[ProgramID]' selected>$f[Nama]</option>";
		}else{
			echo "<option value='$f[ProgramID]'>$f[Nama]</option>";
		}
		}
		?>
		</select>
		</div>



		<div class="col-md-1">
		<input type="submit" class='btn btn-success btn-sm' value='Lihat'>
		</form>
		</div>
	</div>
</div>
</div>

<?php if ($_GET['act']==''){ 
echo "
<div class='card'>
<div class='card-header'>		
	<div class='table-responsive'>
<table id='example1' class='table table-sm table-striped'>
<thead>
<tr style='background:purple;color:white'>
<th  width='10'>No</th>					  					 
<th width='60'>No PMB</th>
<th width='70'>No Ujian</th> 
<th width='200'>Nama </th>
<th width='100'>Program</th>
<th width='70'>Pilihan 1</th>
<th width='70'>Pilihan 2</th>
<th width='30'>Status</th>
<th width='30'>Asal</th>
<th width='30'>Harga</th>
<th width='30'>Syarat</th>
<th width='30'>Kartu</th>
<th width='40'>Aksi</th>
</tr>
</thead>
<tbody>";

if ($_GET['formulir']==''){
	$squery = mysqli_query($koneksi, "SELECT
	pmbformulir.Nama AS NamaFormulir,pmbformulir.JumlahPilihan,pmbformulir.Harga,pmb.Syarat,
	pmb.PMBFormulirID,pmb.PMBID,pmb.PMBRef,pmb.Nama,pmb.ProgramID,pmb.Pilihan1,pmb.Pilihan2,pmb.Pilihan3,pmb.StatusAwalID,pmb.Syarat
	FROM pmbformulir,pmb
	WHERE pmbformulir.PMBFormulirID=pmb.PMBFormulirID
	AND pmb.PMBPeriodID='".strfilter($_GET['tahun'])."'
	order by pmb.PMBID desc"); 
}

else if ($_GET['formulir']!=''){
    $squery = mysqli_query($koneksi, "SELECT
	pmbformulir.Nama AS NamaFormulir,pmbformulir.JumlahPilihan,pmbformulir.Harga,pmb.Syarat,
	pmb.PMBFormulirID,pmb.PMBID,pmb.PMBRef,pmb.Nama,pmb.ProgramID,pmb.Pilihan1,pmb.Pilihan2,pmb.Pilihan3,pmb.StatusAwalID,pmb.Syarat
	FROM pmbformulir,pmb
	WHERE pmbformulir.PMBFormulirID=pmb.PMBFormulirID
	AND pmb.PMBPeriodID='".strfilter($_GET['tahun'])."'
	AND pmb.PMBFormulirID='".strfilter($_GET['formulir'])."'
	order by pmb.PMBID desc"); 

} 
$no = 1;
while($r=mysqli_fetch_array($squery)){	
$Namax 	= strtolower($r['Nama']);
$Nama	= ucwords($Namax);	 
$stat   = mysqli_fetch_array(mysqli_query($koneksi, "select StatusAwalID,Nama from statusawal where StatusAwalID='$r[StatusAwalID]'"));
echo "<tr>
	  <td>$no</td>					  					 
	  <td>$r[PMBID]</td>	  
	  <td>$r[PMBRef]</td> 	  
	  <td>$Nama</td>
	  <td>$r[ProgramID]</td>	
	  <td>$r[Pilihan1]</td>
	  <td>$r[Pilihan2]</td>
	  <td>$stat[Nama]</td>
	  <td>$r[StatusAsalID]</td>
      <td>$r[Harga]</td>
      <td>$r[Syarat]</td>
	  <td><a target='_BLANK'  href='print_report/print-pmbcard.php?PMBID=$r[PMBID]'>Print</a></td>
	  <td>
<a class='btn btn-success btn-xs' title='Edit Data' href='index.php?ndelox=penmaba/penmabaformdaftar&act=ubahformdaftar&tahun=$_GET[tahun]&formulir=$r[PMBFormulirID]&PMBID=$r[PMBID]&program=$_GET[program]'><i class='fa fa-edit'></i></a>

</td>
	  </tr>";
$no++; //<a class='btn btn-danger btn-xs' title='Delete Data' href='index.php?ndelox=pmbformdaftar&tahun=$_GET[tahun]&formulir=$_GET[formulir]&hapus=$r[PMBID]&program=$_GET[program]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
}	
//if (isset($_GET[hapus])){
//  mysqli_query("DELETE FROM pmb where PMBID='$_GET[hapus]'");
//  echo "<script>document.location='index.php?ndelox=pmbformdaftar&tahun=$_GET[tahun]&formulir=$_GET[formulir]&program=$_GET[program]';</script>";
//}
?>
<tbody>
</table></div>
</div>
</div>
</div>
</div>
<?php
} //tutup elseif

elseif($_GET['act']=='tambahform'){

if (isset($_POST['kirimkan'])){
   $cek 	= mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM pmbformjual where PMBFormJualID='".strfilter($_POST['PMBFormJualID'])."'"));
      
   if ($cek<=0){
	   exit("<script>window.alert('No Kwitansi tidak terdaftar!');
 	   window.location='index.php?ndelox=pmbformdaftar&tahun=$_POST[PMBPeriodID]&formulir=$_POST[PMBFormulirID]&program=$_POST[program]';</script>");
   }
   $cek2 	= mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM pmb where PMBFormJualID='".strfilter($_POST['PMBFormJualID'])."'"));
   if ($cek2>0){
	   exit("<script>window.alert('No Kwitansi sudah terdaftar!');
 	   window.location='index.php?ndelox=pmbformdaftar&tahun=$_POST[PMBPeriodID]&formulir=$_POST[PMBFormulirID]&program=$_POST[program]';</script>");
   }
   else{
   $hargaxx		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT PMBFormulirID,harga FROM pmbformulir WHERE PMBFormulirID='".strfilter($_GET['formulir'])."'"));		
   $harga		=$hargaxx['harga'];
   $query = mysqli_query("INSERT INTO pmb(
				PMBID,
				PMBRef,
				PMBFormulirID,
				PMBPeriodID,
				PMBFormJualID,
				PSSBID,
				BuktiSetoran,
				NIM,
				KodeID,
				BIPOTID,
				Nama,
				StatusAwalID,
				StatusMundur,
				MhswPindahanID,
				ProgramID,
				ProdiID,
				Kelamin,
				WargaNegara,
				Kebangsaan,
				TempatLahir,
				TanggalLahir,
				Agama,
				StatusSipil,
				Alamat,
				Kota,
				RT,
				RW,
				KodePos,
				Propinsi,
				Negara,
				Telepon,
				Handphone,
				Email,
				AlamatAsal,
				KotaAsal,
				RTAsal,
				RWAsal,
				KodePosAsal,
				PropinsiAsal,
				NegaraAsal,
				TeleponAsal,
				NamaAyah,
				AgamaAyah,
				PendidikanAyah,
				PekerjaanAyah,
				HidupAyah,
				NamaIbu,
				AgamaIbu,
				PendidikanIbu,
				PekerjaanIbu,
				HidupIbu,
				AlamatOrtu,
				KotaOrtu,
				RTOrtu,
				RWOrtu,
				KodePosOrtu,
				PropinsiOrtu,
				NegaraOrtu,
				TeleponOrtu,
				HandphoneOrtu,
				EmailOrtu,
				AsalSekolah,
				JenisSekolahID,
				AlamatSekolah,
				KotaSekolah,
				JurusanSekolah,
				NilaiSekolah,
				TahunLulus,
				AsalPT,
				ProdiAsalPT,
				LulusAsalPT,
				TglLulusAsalPT,
				Pilihan1,
				Pilihan2,
				Pilihan3,
				Harga,
				SudahBayar,
				NA,
				TanggalUjian,
				LulusUjian,
				RuangID,
				NomerUjian,
				NilaiUjian,
				DetailNilai,
				GradeNilai,
				Catatan,
				NomerSurat,
				Syarat,
				SyaratLengkap,
				BuktiSetoranMhsw,
				TanggalSetoranMhsw,
				Dispensasi,
				DispensasiID,
				JudulDispensasi,
				CatatanDispensasi,
				LoginBuat,
				TanggalBuat,
				NIK,
				IDKK,
				Kelurahan,
				Kecamatan) 
		 VALUES('".strfilter($_POST['PMBID'])."',
				'".strfilter($_POST['PMBID'])."',
				'".strfilter($_POST['PMBFormulirID'])."',
				'".strfilter($_POST['PMBPeriodID'])."',
				'".strfilter($_POST['PMBFormJualID'])."',
				'".strfilter($_POST['PSSBID'])."',
				'".strfilter($_POST['BuktiSetoran'])."',
				'".strfilter($_POST['NIM'])."',
				'SISFO',
				'".strfilter($_POST['BIPOTID'])."',
				'".strfilter($_POST['Nama'])."',
				'".strfilter($_POST['StatusAwalID'])."',
				'N',
				'".strfilter($_POST['MhswPindahanID'])."',
				'".strfilter($_POST['ProgramID'])."',
				'".strfilter($_POST['ProdiID'])."',
				'".strfilter($_POST['Kelamin'])."',
				'".strfilter($_POST['WargaNegara'])."',
				'".strfilter($_POST['Kebangsaan'])."',
				'".strfilter($_POST['TempatLahir'])."',
				'".strfilter($_POST['TanggalLahir'])."',
				'".strfilter($_POST['Agama'])."',
				'".strfilter($_POST['StatusSipil'])."',
				'".strfilter($_POST['Alamat'])."',
				'".strfilter($_POST['Kota'])."',
				'".strfilter($_POST['RT'])."',
				'".strfilter($_POST['RW'])."',
				'".strfilter($_POST['KodePos'])."',
				'".strfilter($_POST['Propinsi'])."',
				'".strfilter($_POST['Negara'])."',
				'".strfilter($_POST['Telepon'])."',
				'".strfilter($_POST['Handphone'])."',
				'".strfilter($_POST['Email'])."',
				'".strfilter($_POST['AlamatAsal'])."',
				'".strfilter($_POST['KotaAsal'])."',
				'".strfilter($_POST['RTAsal'])."',
				'".strfilter($_POST['RWAsal'])."',
				'".strfilter($_POST['KodePosAsal'])."',
				'".strfilter($_POST['PropinsiAsal'])."',
				'".strfilter($_POST['NegaraAsal'])."',
				'".strfilter($_POST['TeleponAsal'])."',
				'".strfilter($_POST['NamaAyah'])."',
				'".strfilter($_POST['AgamaAyah'])."',
				'".strfilter($_POST['PendidikanAyah'])."',
				'".strfilter($_POST['PekerjaanAyah'])."',
				'".strfilter($_POST['HidupAyah'])."',
				'".strfilter($_POST['NamaIbu'])."',
				'".strfilter($_POST['AgamaIbu'])."',
				'".strfilter($_POST['PendidikanIbu'])."',
				'".strfilter($_POST['PekerjaanIbu'])."',
				'".strfilter($_POST['HidupIbu'])."',
				'".strfilter($_POST['AlamatOrtu'])."',
				'".strfilter($_POST['KotaOrtu'])."',
				'".strfilter($_POST['RTOrtu'])."',
				'".strfilter($_POST['RWOrtu'])."',
				'".strfilter($_POST['KodePosOrtu'])."',
				'".strfilter($_POST['PropinsiOrtu'])."',
				'".strfilter($_POST['NegaraOrtu'])."',
				'".strfilter($_POST['TeleponOrtu'])."',
				'".strfilter($_POST['HandphoneOrtu'])."',
				'".strfilter($_POST['EmailOrtu'])."',
				'".strfilter($_POST['AsalSekolah'])."',
				'".strfilter($_POST['JenisSekolahID'])."',
				'".strfilter($_POST['AlamatSekolah'])."',
				'".strfilter($_POST['KotaSekolah'])."',
				'".strfilter($_POST['JurusanSekolah'])."',
				'".strfilter($_POST['NilaiSekolah'])."',
				'".strfilter($_POST['TahunLulus'])."',
				'".strfilter($_POST['AsalPT'])."',
				'".strfilter($_POST['ProdiAsalPT'])."',
				'".strfilter($_POST['LulusAsalPT'])."',
				'".strfilter($_POST['TglLulusAsalPT'])."',
				'".strfilter($_POST['Pilihan1'])."',
				'".strfilter($_POST['Pilihan2'])."',
				'".strfilter($_POST['Pilihan3'])."',
				'$harga',
				'N',
				'N',
				'".strfilter($_POST['TanggalUjian'])."',
				'N',
				'".strfilter($_POST['RuangID'])."',
				'".strfilter($_POST['NomerUjian'])."',
				'".strfilter($_POST['NilaiUjian'])."',
				'".strfilter($_POST['DetailNilai'])."',
				'".strfilter($_POST['GradeNilai'])."',
				'".strfilter($_POST['Catatan'])."',
				'".strfilter($_POST['NomerSurat'])."',
				'".strfilter($_POST['Syarat'])."',
				'N',
				'".strfilter($_POST['BuktiSetoranMhsw'])."',
				'".strfilter($_POST['TanggalSetoranMhsw'])."',
				'N',
				'".strfilter($_POST['DispensasiID'])."',
				'".strfilter($_POST['JudulDispensasi'])."',
				'".strfilter($_POST['CatatanDispensasi'])."',
				'$_SESSION[_Login]',
				'".date('Y-m-d')."',
				'".strfilter($_POST['NIK'])."',
				'".strfilter($_POST['IDKK'])."',
				'".strfilter($_POST['Kelurahan'])."',
				'".strfilter($_POST['Kecamatan'])."')");
echo "<script>document.location='index.php?ndelox=penmaba/penmabaformdaftar&tahun=$_POST[PMBPeriodID]&formulir=$_POST[PMBFormulirID]&program=$_POST[program]&sukses';</script>";						
	}//tutup kirimkan
} //else cek kwitansi

//periode PMB Aktif
$p 			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM pmbperiod Where NA='N'"));
$pmbaktif 	= $p['PMBPeriodID'];

//select max ganti dengan Desc Limit 1
$data 	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT PMBID as maxID FROM pmb where PMBPeriodID='".strfilter($_GET['tahun'])."' ORDER BY PMBID DESC LIMIT 1")); 
$idMax 	= $data['maxID'];
$NoUrut = (int) substr($idMax, 5, 4);
$NoUrut++; 
$NewID 	= $pmbaktif .sprintf('%04s', $NoUrut);

$kw 		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM pmbformjual WHERE PMBFormJualID='".strfilter($_GET['PMBFormJualID'])."'"));
$frm 		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM pmbformulir WHERE PMBFormulirID='".strfilter($_GET['formulir'])."'"));

$ProgramID 	= $frm['ProgramID'];
$programs 	= $frm['Nama'];
$prodd    	= $frm['Keterangan']; //Keterangan berisi SI atau TI modifikasi logika

$prodist 	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama FROM prodi WHERE ProdiID='$frm[Keterangan]'"));

/*
$cek = $this->db->query($koneksi, "SELECT no_cuti FROM cuti ORDER BY id_cuti DESC LIMIT 1")->result_array();
$ex = explode('/', $cek['no_cuti']);
if (date('tahun')=='001'){ $urut = '001'; }
else{ $urut = $ex[00]+1; }
$cuti = 'CUTI';
$rssp = 'RSSP';
$bulan = array('','01','02','03','04','05','06','07','08','09','10','11','12');
$tahun = date('Y');
$no_cuti = $urut.'/'.$cuti.'/'.$rssp.'/'.$bulan[date('n')].'/'.$tahun;
*/
echo "<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>

<div class='card'>
<div class='card-header'>
<div class='row'>
	<div class='col-md-6'>
						<div class='table-responsive'>
						<table class='table table-sm table-bordered'>
						<tbody>
						<input type='hidden' name='program' value='$_GET[program]'>
						<input type='hidden' name='ProdiID' value='$prodd'>
						<input type='hidden' name='PMBPeriodID' value='$_GET[tahun]'>
						<input type='hidden' name='PMBFormulirID' value='$_GET[formulir]'>
						<tr><th  scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>Tambah Formulir PMB </th></tr>
						<tr><th width='280px' scope='row'>No. PMB</th> <td><input type='text' class='form-control form-control-sm' name='PMBID' value='$NewID' readonly> </td></tr>
						<tr><th  scope='row'>Program Studi (Program) </th> <td><input type='text' class='form-control form-control-sm' name='xx' value='$prodist[Nama] ($programs)' readonly> </td></tr>
						<tr><th scope='row'>No Ujian</th><td><input type='text' class='form-control form-control-sm' name='PMBRef' value='$NewID'></td></tr>

						<tr><th scope='row'>Kwitansi</th><td><input type='text' class='form-control form-control-sm' name='PMBFormJualID' value='$kw[PMBFormJualID]'></td></tr>
						<tr><th scope='row'>Nama</th><td><input type='text' class='form-control form-control-sm' name='Nama' value='$kw[Nama]'></td></tr>	
						 
						<tr><th scope='row'>Status Awal</th>          
						<td><select class='form-control form-control-sm' name='StatusAwalID'> 
						<option value='B' selected>Baru</option>"; 
						$st = mysqli_query($koneksi, "SELECT * FROM statusawal");
						while($a = mysqli_fetch_array($st)){
							echo "<option value='$a[StatusAwalID]'>$a[Nama]</option>";
						}
						echo "</select></td></tr>	
						<tr><th scope='row'>Jenis Kelamin</th>          
						<td><select class='form-control form-control-sm' name='Kelamin'> 
						<option value='0' selected>- Pilih Jenis Kelamin -</option>"; 
						$status = mysqli_query($koneksi, "SELECT * FROM kelamin");
						while($a = mysqli_fetch_array($status)){
							echo "<option value='$a[Kelamin]' selected>$a[Nama]</option>";
						}
						echo "</select></td></tr>	
						<tr><th scope='row'>NIK</th><td><input type='text' class='form-control form-control-sm' name='NIK' required></td></tr>
						<tr><th scope='row'>IDKK</th><td><input type='text' class='form-control form-control-sm' name='IDKK' ></td></tr>
						<tr><th scope='row'>Tempat Lahir</th><td><input type='text' class='form-control form-control-sm' name='TempatLahir' required></td></tr>	
						<tr><th scope='row'>Tanggal Lahir</th><td><input type='text' id='datepicker' class='form-control form-control-sm' name='TanggalLahir' value='".date('Y-m-d')."'></td></tr>
								
						<tr><th scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>Pilihan Program Studi</th></tr>

						<tr><th scope='row'>Program</th><td><input type='text' class='form-control form-control-sm' name='ProgramID' value='$ProgramID' readonly></td></tr>
						<tr><th scope='row'>Pilihan 1</th><td><input type='text' class='form-control form-control-sm' name='Pilihan1' value='$prodd' readonly></td></tr>

						<tr><th scope='row'>Pilihan 2</th>
						<td><select class='form-control form-control-sm' name='Pilihan2'> 
						<option value='0' selected>- Pilih Prodi -</option>"; 
						$prd2 = mysqli_query($koneksi, "SELECT * FROM prodi order by Nama asc");
						while($a = mysqli_fetch_array($prd2)){
							 echo "<option value='$a[ProdiID]'>$a[Nama]</option>";
						}
						echo "</select></td>
						</tr>
						</tbody>
					</table>
					</div>
	</div>				

	<div class='col-md-6'>
						<div class='table-responsive'>
						<table class='table table-sm table-bordered'>
						<tbody>
						<tr><th scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>Data Pribadi (Sesuai KTP)</th></tr>
						<tr><th scope='row' width='280px'>Warga Negara</th>          
						<td><select class='form-control form-control-sm' name='WargaNegara'>"; 
							  $w = mysqli_query($koneksi, "SELECT * FROM warganegara order by Nama asc");
							  while($a = mysqli_fetch_array($w)){		
								 echo "<option value='$a[WargaNegara]' selected>$a[Nama]</option>";		
							  }
							  echo "</select></td></tr> 
						<tr><th scope='row'>Kebangsaan</th><td>Jika WNA, Sebutkan<input type='text' class='form-control form-control-sm' name='Kebangsaan' ></td></tr>	
						<tr><th scope='row'>Agama</th>          
						<td><select class='form-control form-control-sm' name='Agama'> 
						"; 
						$ag = mysqli_query($koneksi, "SELECT * FROM agama order by urut asc");
						while($a = mysqli_fetch_array($ag)){
							echo "<option value='$a[Agama]'>$a[Agama] - $a[Nama]</option>";
						  }
						  echo "</select></td></tr>  
						<tr><th scope='row'>Status Sipil</th>          
						<td><select class='form-control form-control-sm' name='StatusSipil'> 
						"; 
						$ag = mysqli_query($koneksi, "SELECT * FROM statussipil order by Nama asc");
						while($a = mysqli_fetch_array($ag)){
							echo "<option value='$a[StatusSipil]'>$a[StatusSipil] - $a[Nama]</option>";
						}
						echo "</select></td></tr> 

						<tr><th scope='row'>Alamat Tinggal</th><td><input type='text' class='form-control form-control-sm' name='Alamat' ></td></tr>	
						<tr><th scope='row'>Kota /Kabupaten</th><td><input type='text' class='form-control form-control-sm' name='Kota' ></td></tr>
						<tr><th scope='row'>RT</th><td><input type='text' class='form-control form-control-sm' name='RT' ></td></tr>
						<tr><th scope='row'>RW</th><td><input type='text' class='form-control form-control-sm' name='RW' ></td></tr>
						<tr><th scope='row'>Kelurahan</th><td><input type='text' class='form-control form-control-sm' name='Kelurahan' required></td></tr>
						<tr><th scope='row'>Kecamatan</th><td><input type='text' class='form-control form-control-sm' name='Kecamatan' required></td></tr>
						<tr><th scope='row'>Kode POS</th><td><input type='text' class='form-control form-control-sm' name='KodePOS' ></td></tr>
						<tr><th scope='row'>Propinsi</th><td><input type='text' class='form-control form-control-sm' name='Propinsi' ></td></tr>
						<tr><th scope='row'>Negara</th><td><input type='text' class='form-control form-control-sm' name='Negara' ></td></tr>
						<tr><th scope='row'>Telepon</th><td><input type='text' class='form-control form-control-sm' name='Telepon' ></td></tr>
						<tr><th scope='row'>Telp. Bergerak / HP</th><td><input type='text' class='form-control form-control-sm' name='Handphone' required></td></tr>
						<tr><th scope='row'>Email</th><td><input type='text' class='form-control form-control-sm' name='Email' ></td></tr>
						</tbody>
					</table>
					</div>
	</div>
</div>	


<div class='row'>
	<div class='col-md-6'>
			<div class='table-responsive'>
			<table class='table table-sm table-bordered'>
			<tbody>
			<tr><th scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>Asal Sekolah</th></tr>

			<tr><th scope='row' width='280px'>Asal Sekolah <a href='?ndelox=asalsekolah&act=tambah'>[ Tambah Sekolah ]</a></th>          
			<td><select class='combobox form-control' name='AsalSekolah'> 
			<option value='0' selected>- Pilih Asal Sekolah -</option>"; 

			$ag=mysqli_query($koneksi, "SELECT * FROM asalsekolah");
			while($a = mysqli_fetch_array($ag)){
				echo "<option value='$a[SekolahID]'>$a[Kota] - $a[Nama] - $a[SekolahID]</option>";
			}
			echo "</select></td></tr> 

			<tr><th scope='row'>Jurusan Sekolah</th>          
			<td><select class='combobox form-control' name='JurusanSekolah'> 
			<option value='0' selected>- Pilih Jurusan -</option>"; 
			$ag = mysqli_query($koneksi, "SELECT * FROM jurusansekolah order by JurusanSekolahID");
			while($a = mysqli_fetch_array($ag)){
				echo "<option value='$a[JurusanSekolahID]'>$a[JurusanSekolahID] - $a[NamaJurusan]</option>";
			}
			echo "</select></td></tr>

			<tr><th scope='row'>Tahun Lulus</th><td><input type='text' class='form-control form-control-sm' name='TahunLulus' ></td></tr>
			<tr><th scope='row'>Nilai Kelulusan</th><td><input type='text' class='form-control form-control-sm' name='NilaiSekolah' ></td></tr>


			<tr><th scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>Asal Perguruan Tinggi</th></tr>
			<tr><th scope='row'>Nama Perguruan Tinggi</th><td><input type='text' class='form-control form-control-sm' name='AsalPT' ></td></tr>
			<tr><th scope='row'>Dari Program Studi</th><td><input type='text' class='form-control form-control-sm' name='ProdiAsalPT' </td></tr>
			<tr><th scope='row'>Telah Lulus dr PT ini?</th><td>Jika ya, maka lulus tanggal<input type='text' class='form-control form-control-sm' name='TglLulusAsalPT' ></td></tr>

			<tr><th scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>Alamat Tinggal Pekanbaru</th></tr>
			<tr><th scope='row'>Alamat</th><td><input type='text' class='form-control form-control-sm' name='ae'></td></tr>
			<tr><th scope='row'>Kota</th><td><input type='text' class='form-control form-control-sm' name='ae'></td></tr>
			<tr><th scope='row'>RT/RW</th><td><input type='text' class='form-control form-control-sm' name='ae'></td></tr>
			<tr><th scope='row'>Telepon</th><td><input type='text' class='form-control form-control-sm' name='ae'></td></tr>
			<tr><th scope='row'>Kode POS</th><td><input type='text' class='form-control form-control-sm' name='ae'></td></tr>
			<tr><th scope='row'>Propinsi</th><td><input type='text' class='form-control form-control-sm' name='ae'></td></tr>
			<tr><th scope='row'>Negara</th><td><input type='text' class='form-control form-control-sm' name='ae'></td></tr>
			</tbody>
			</table>
					<div class='box-footer'>
					<button type='submit' name='kirimkan' class='btn btn-info'>Tambahkan</button>
					<a href='index.php?ndelox=pmb/pmbformdaftar&tahun=$_GET[tahun]&formulir=$_GET[formulir]&program=$_GET[program]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>     
					</div>
			</div>
	</div>

	<div class='col-md-6'>
				<div class='table-responsive'>
				<table class='table table-sm table-bordered'>
				<tbody>			
				<tr><th scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>Data Orang Tua</th></tr>
				<tr><th scope='row' width='280px'>Nama Ayah</th><td><input type='text' class='form-control form-control-sm' name='NamaAyah' value='$tg[Nama]'></td></tr>
				<tr><th scope='row'>Agama Ayah</th>          
				<td><select class='form-control form-control-sm' name='AgamaAyah'> 
				<option value='0' selected>- Pilih agama -</option>"; 
				$ag = mysqli_query($koneksi, "SELECT * FROM agama");
				while($a = mysqli_fetch_array($ag)){
				  if ($a['Agama'] == $mw['AgamaAyah']){
					echo "<option value='$a[Agama]' selected>$a[Agama] - $a[Nama]</option>";
				  }else{
					echo "<option value='$a[Agama]'>$a[Agama] - $a[Nama]</option>";
				  }
				}
				echo "</select></td></tr>  
				<tr><th scope='row'>Pendidikan Ayah</th>          
				<td><select class='form-control form-control-sm' name='PendidikanAyah'> 
				<option value='0' selected>- Pilih Pendidikan Ayah -</option>"; 
				$kerja = mysqli_query($koneksi, "SELECT * FROM pendidikanortu");
				while($a = mysqli_fetch_array($kerja)){
				  if ($a['Pendidikan'] == $mw['PendidikanAyah']){
					echo "<option value='$a[Pendidikan]' selected>$a[Pendidikan] - $a[Nama]</option>";
				  }else{
					echo "<option value='$a[Pendidikan]'>$a[Pendidikan] - $a[Nama]</option>";
				  }
				}
				echo "</select></td></tr> 
				<tr><th scope='row'>Pekerjaan Ayah</th>          
				<td><select class='form-control form-control-sm' name='PekerjaanAyah'> 
				<option value='0' selected>- Pilih Pekerjaan Ayah -</option>"; 
				$kerja = mysqli_query($koneksi, "SELECT * FROM pekerjaanortu");
				while($a = mysqli_fetch_array($kerja)){
				  if ($a['Pekerjaan'] == $mw['PekerjaanAyah']){
					echo "<option value='$a[Pekerjaan]' selected>$a[Pekerjaan] - $a[Nama]</option>";
				  }else{
					echo "<option value='$a[Pekerjaan]'>$a[Pekerjaan] - $a[Nama]</option>";
				  }
				}
				echo "</select></td></tr> 
				<tr><th scope='row'>Status Ayah</th>          
				<td><select class='form-control form-control-sm' name='HidupAyah'> 
				"; 
				$hd = mysqli_query($koneksi, "SELECT * FROM hidup");
				while($a = mysqli_fetch_array($hd)){
				  if ($a['Hidup'] == $mw['HidupAyah']){
					echo "<option value='$a[Hidup]' selected>$a[Hidup] - $a[Nama]</option>";
				  }else{
					echo "<option value='$a[Hidup]'>$a[Hidup] - $a[Nama]</option>";
				  }
				}
				echo "</select></td></tr> 

				<tr><th  scope='row'>Nama Ibu</th> <td><input type='text' class='form-control form-control-sm' name='NamaIbu' required></td></tr>
				<tr><th scope='row'>Agama Ibu</th>          
				<td><select class='form-control form-control-sm' name='AgamaIbu'> 
				<option value='0' selected>- Pilih agama -</option>"; 
				$ag = mysqli_query($koneksi, "SELECT * FROM agama");
				while($a = mysqli_fetch_array($ag)){
				  if ($a['Agama'] == $mw['AgamaIbu']){
					echo "<option value='$a[Agama]' selected>$a[Agama] - $a[Nama]</option>";
				  }else{
					echo "<option value='$a[Agama]'>$a[Agama] - $a[Nama]</option>";
				  }
				}
				echo "</select></td></tr> 
				<tr><th scope='row'>Pendidikan Ibu</th>          
				<td><select class='form-control form-control-sm' name='PendidikanIbu'> 
				<option value='0' selected>- Pilih Pendidikan Ibu -</option>"; 
				$kerja = mysqli_query($koneksi, "SELECT * FROM pendidikanortu");
				while($a = mysqli_fetch_array($kerja)){
				  if ($a['Pendidikan'] == $mw['PendidikanIbu']){
					echo "<option value='$a[Pendidikan]' selected>$a[Pendidikan] - $a[Nama]</option>";
				  }else{
					echo "<option value='$a[Pendidikan]'>$a[Pendidikan] - $a[Nama]</option>";
				  }
				}
				echo "</select></td></tr> 
				<tr><th scope='row'>Pekerjaan Ibu</th>          
				<td><select class='form-control form-control-sm' name='PekerjaanIbu'> 
				<option value='0' selected>- Pilih Pekerjaan Ibu -</option>"; 
				$kerja = mysqli_query($koneksi, "SELECT * FROM pekerjaanortu");
				while($a = mysqli_fetch_array($kerja)){
				  if ($a['Pekerjaan'] == $mw['PekerjaanIbu']){
					echo "<option value='$a[Pekerjaan]' selected>$a[Pekerjaan] - $a[Nama]</option>";
				  }else{
					echo "<option value='$a[Pekerjaan]'>$a[Pekerjaan] - $a[Nama]</option>";
				  }
				}
				echo "</select></td></tr>  
				<tr><th scope='row'>Status Ibu</th>          
				<td><select class='form-control form-control-sm' name='HidupIbu'> 
				"; 
				$hd = mysqli_query($koneksi, "SELECT * FROM hidup");
				while($a = mysqli_fetch_array($hd)){
				  if ($a['Hidup'] == $mw['HidupIbu']){
					echo "<option value='$a[Hidup]' selected>$a[Hidup] - $a[Nama]</option>";
				  }else{
					echo "<option value='$a[Hidup]'>$a[Hidup] - $a[Nama]</option>";
				  }
				}
				echo "</select></td></tr> 
				<tr><th scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>Alamat Orang Tua</th></tr>
				<tr><th scope='row' width='280px'>Alamat Orang Tua</th><td><input type='text' class='form-control form-control-sm' name='AlamatOrtu' ></td></tr>
				<tr><th scope='row'>Kota</th><td><input type='text' class='form-control form-control-sm' name='KotaOrtu' ></td></tr>
				<tr><th scope='row'>RT</th><td><input type='text' class='form-control form-control-sm' name='RTOrtu' ></td></tr>
				<tr><th scope='row'>RW</th><td><input type='text' class='form-control form-control-sm' name='RWOrtu' ></td></tr>
				<tr><th scope='row'>Kode POS</th><td><input type='text' class='form-control form-control-sm' name='KodePosOrtu' ></td></tr>
				<tr><th scope='row'>Propinsi</th><td><input type='text' class='form-control form-control-sm' name='PropinsiOrtu' ></td></tr>
				<tr><th scope='row'>Negara</th><td><input type='text' class='form-control form-control-sm' name='NegaraOrtu' ></td></tr>
				<tr><th scope='row'>Telepon</th><td><input type='text' class='form-control form-control-sm' name='TeleponOrtu' ></td></tr>
				<tr><th scope='row'>Telepon Bergerak</th><td><input type='text' class='form-control form-control-sm' name='HandphoneOrtu'></td></tr>
				<tr><th scope='row'>Email</th><td><input type='text' class='form-control form-control-sm' name='EmailOrtu' ></td></tr>
				</tbody>
				</table>
				</div>
</div>
</div>
</form>";
}


elseif($_GET['act']=='ubahformdaftar'){
if (isset($_POST['ubahdaftar'])){		
  $query = mysqli_query($koneksi, "UPDATE pmb set
						PMBID			='".strfilter($_POST['PMBID'])."',
						PMBRef			='".strfilter($_POST['PMBID'])."',
						PMBFormulirID	='".strfilter($_POST['PMBFormulirID'])."',
						PMBPeriodID		='".strfilter($_POST['PMBPeriodID'])."',
						PMBFormJualID	='".strfilter($_POST['PMBFormJualID'])."',
						BuktiSetoran	='".strfilter($_POST['BuktiSetoran'])."',
						KodeID			='SISFO',
						Nama			='".strfilter($_POST['Nama'])."',
						StatusAwalID	='".strfilter($_POST['StatusAwalID'])."',
						StatusMundur	='".strfilter($_POST['StatusMundur'])."',
						MhswPindahanID	='".strfilter($_POST['MhswPindahanID'])."',
						ProgramID		='".strfilter($_POST['ProgramID'])."',
						ProdiID			='".strfilter($_POST['Pilihan1'])."',
						Kelamin			='".strfilter($_POST['Kelamin'])."',
						WargaNegara		='".strfilter($_POST['WargaNegara'])."',
						Kebangsaan		='".strfilter($_POST['Kebangsaan'])."',
						TempatLahir		='".strfilter($_POST['TempatLahir'])."',
						TanggalLahir	='".strfilter($_POST['TanggalLahir'])."',
						Agama			='".strfilter($_POST['Agama'])."',
						StatusSipil		='".strfilter($_POST['StatusSipil'])."',
						Alamat			='".strfilter($_POST['Alamat'])."',
						Kota			='".strfilter($_POST['Kota'])."',
						RT				='".strfilter($_POST['RT'])."',
						RW				='".strfilter($_POST['RW'])."',
						KodePos			='".strfilter($_POST['KodePos'])."',
						Propinsi		='".strfilter($_POST['Propinsi'])."',
						Negara			='".strfilter($_POST['Negara'])."',
						Telepon			='".strfilter($_POST['Telepon'])."',
						Handphone		='".strfilter($_POST['Handphone'])."',
						Email			='".strfilter($_POST['Email'])."',
						AlamatAsal		='".strfilter($_POST['AlamatAsal'])."',
						KotaAsal		='".strfilter($_POST['KotaAsal'])."',
						RTAsal			='".strfilter($_POST['RTAsal'])."',
						RWAsal			='".strfilter($_POST['RWAsal'])."',
						KodePosAsal		='".strfilter($_POST['KodePosAsal'])."',
						PropinsiAsal	='".strfilter($_POST['PropinsiAsal'])."',
						NegaraAsal		='".strfilter($_POST['NegaraAsal'])."',
						TeleponAsal		='".strfilter($_POST['TeleponAsal'])."',
						NamaAyah		='".strfilter($_POST['NamaAyah'])."',
						AgamaAyah		='".strfilter($_POST['AgamaAyah'])."',
						PendidikanAyah	='".strfilter($_POST['PendidikanAyah'])."',
						PekerjaanAyah	='".strfilter($_POST['PekerjaanAyah'])."',
						HidupAyah		='".strfilter($_POST['HidupAyah'])."',
						NamaIbu			='".strfilter($_POST['NamaIbu'])."',
						AgamaIbu		='".strfilter($_POST['AgamaIbu'])."',
						PendidikanIbu	='".strfilter($_POST['PendidikanIbu'])."',
						PekerjaanIbu	='".strfilter($_POST['PekerjaanIbu'])."',
						HidupIbu		='".strfilter($_POST['HidupIbu'])."',
						AlamatOrtu		='".strfilter($_POST['AlamatOrtu'])."',
						KotaOrtu		='".strfilter($_POST['KotaOrtu'])."',
						RTOrtu			='".strfilter($_POST['RTOrtu'])."',
						RWOrtu			='".strfilter($_POST['RWOrtu'])."',
						KodePosOrtu		='".strfilter($_POST['KodePosOrtu'])."',
						PropinsiOrtu	='".strfilter($_POST['PropinsiOrtu'])."',
						NegaraOrtu		='".strfilter($_POST['NegaraOrtu'])."',
						TeleponOrtu		='".strfilter($_POST['TeleponOrtu'])."',
						HandphoneOrtu	='".strfilter($_POST['HandphoneOrtu'])."',
						EmailOrtu		='".strfilter($_POST['EmailOrtu'])."',
						AsalSekolah		='".strfilter($_POST['AsalSekolah'])."',
						JenisSekolahID	='".strfilter($_POST['JenisSekolahID'])."',
						AlamatSekolah	='".strfilter($_POST['AlamatSekolah'])."',
						KotaSekolah		='".strfilter($_POST['KotaSekolah'])."',
						JurusanSekolah	='".strfilter($_POST['JurusanSekolah'])."',
						NilaiSekolah	='".strfilter($_POST['NilaiSekolah'])."',
						TahunLulus		='".strfilter($_POST['TahunLulus'])."',
						AsalPT			='".strfilter($_POST['AsalPT'])."',
						ProdiAsalPT		='".strfilter($_POST['ProdiAsalPT'])."',
						LulusAsalPT		='".strfilter($_POST['LulusAsalPT'])."',
						TglLulusAsalPT	='".strfilter($_POST['TglLulusAsalPT'])."',
						Pilihan1		='".strfilter($_POST['Pilihan1'])."',
						Pilihan2		='".strfilter($_POST['Pilihan2'])."',
						Pilihan3		='".strfilter($_POST['Pilihan3'])."',						
						NomerUjian		='".strfilter($_POST['NomerUjian'])."',						
						Dispensasi		='".strfilter($_POST['Dispensasi'])."',
						DispensasiID	='".strfilter($_POST['DispensasiID'])."',
						JudulDispensasi	='".strfilter($_POST['JudulDispensasi'])."',
						CatatanDispensasi	='".strfilter($_POST['CatatanDispensasi'])."',
						TanggalBuat		='".date('Y-m-d')."',
						LoginEdit		='$_SESSION[_Login]',
						TanggalEdit		='".date('Y-m-d')."',
						NIK				='".strfilter($_POST['NIK'])."',
						IDKK			='".strfilter($_POST['IDKK'])."',
						Kelurahan		='".strfilter($_POST['Kelurahan'])."',
						Kecamatan		='".strfilter($_POST['Kecamatan'])."'
						WHERE PMBID 	='".strfilter($_POST['PMBID'])."'");
echo "<script>document.location='index.php?ndelox=penmaba/penmabaformdaftar&tahun=$_POST[PMBPeriodID]&formulir=$_POST[PMBFormulirID]&program=$_POST[program]&sukses';</script>";							
}//tutup kirimkan

$e =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from pmb where PMBID='".strfilter($_GET['PMBID'])."'"));
echo "
<div class='card'>
<div class='card-header'>
<h3 class='box-title'><b style=color:green;font-size=18px>Ubah Data Formulir PMB</b></h3>
</div>
</div>
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>

<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table class='table table-sm table-bordered'>
<tbody>
<input type='hidden' name='program' value='$_GET[program]'>
<input type='hidden' name='PMBPeriodID' value='$_GET[tahun]'>
<input type='hidden' name='PMBFormulirID' value='$_GET[formulir]'>
<input type='hidden' name='PMBID' value='$_GET[PMBID]'>
<tr><th  scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>Ubah Formulir PMB</th></tr>
<tr><th width='290px' scope='row'>No. PMB</th> <td><input type='text' class='form-control form-control-sm' name='PMBID' value='$e[PMBID]' readonly> </td></tr>
<tr><th scope='row'>No Ujian</th><td><input type='text' class='form-control form-control-sm' name='PMBRef' value='$e[PMBRef]'></td></tr>	
<tr><th scope='row'>Nama</th><td><input type='text' class='form-control form-control-sm' name='Nama' value='$e[Nama]'></td></tr>	
<tr><th scope='row'>No Kwitansi</th><td><input type='text' class='form-control form-control-sm' name='PMBFormJualID' value='$e[PMBRef]'></td></tr>	
<tr><th scope='row'>Status Awal</th>          
<td><select class='form-control form-control-sm' name='StatusAwalID'> 
<option value='0' selected>- Pilih Status Awal -</option>"; 
$st = mysqli_query($koneksi, "SELECT * FROM statusawal");
while($x = mysqli_fetch_array($st)){
   if ($x['StatusAwalID'] == $e['StatusAwalID']){
	   echo "<option value='$x[StatusAwalID]' selected>$x[Nama]</option>";
	}else{
	   echo "<option value='$x[StatusAwalID]'>$x[Nama]</option>";
  }
}
echo "</select></td></tr>	
<tr><th scope='row'>Jenis Kelamin</th>          
<td><select class='form-control form-control-sm' name='Kelamin'> 
<option value='0' selected>- Pilih Jenis Kelamin -</option>"; 
$kel = mysqli_query($koneksi, "SELECT * FROM kelamin");
while($a = mysqli_fetch_array($kel)){
  if ($a['Kelamin'] == $e['Kelamin']){	
	echo "<option value='$a[Kelamin]' selected>$a[Nama]</option>";
  }else{
	echo "<option value='$a[Kelamin]'>$a[Nama]</option>";
  }
}
echo "</select></td></tr>	
<tr><th scope='row'>NIK</th><td><input type='text' class='form-control form-control-sm' name='NIK' value='$e[NIK]'></td></tr>
<tr><th scope='row'>IDKK</th><td><input type='text' class='form-control form-control-sm' name='IDKK' value='$e[IDKK]'></td></tr>
<tr><th scope='row'>Tempat Lahir</th><td><input type='text' class='form-control form-control-sm' name='TempatLahir' value='$e[TempatLahir]' ></td></tr>	
<tr><th scope='row'>Tanggal Lahir</th><td><input type='text' id='datepicker' class='form-control form-control-sm' name='TanggalLahir' value='$e[TanggalLahir]'></td></tr>
		
<tr><th scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>Pilihan Program Studi</th></tr>
<tr><th scope='row'>Program</th>
<td><select class='form-control form-control-sm' name='ProgramID'> 
<option value='0' selected>- Pilih Program -</option>"; 
$prog = mysqli_query($koneksi, "SELECT * FROM program");
while($a = mysqli_fetch_array($prog)){
  if ($a['ProgramID'] == $e['ProgramID']){
	  echo "<option value='$a[ProgramID]' selected>$a[Nama]</option>";
  }else{
	  echo "<option value='$a[ProgramID]'>$a[Nama]</option>";
  }
}
echo "</select></td></tr>
<tr><th scope='row'>Pilihan 1</th>          
<td><select class='form-control form-control-sm' name='Pilihan1'> 
<option value='0' selected>- Pilih -</option>"; 
$prod = mysqli_query($koneksi, "SELECT * FROM prodi");
while($a = mysqli_fetch_array($prod)){
if ($a['ProdiID'] == $e['Pilihan1']){
	echo "<option value='$a[ProdiID]' selected>$a[Nama]</option>";
}else{
	  echo "<option value='$a[ProdiID]'>$a[Nama]</option>";
  }
}
echo "</select></td></tr>

<tr><th scope='row'>Pilihan 2</th>          
<td><select class='form-control form-control-sm' name='Pilihan2'> 
<option value='0' selected>- Pilih -</option>"; 
$prod = mysqli_query($koneksi, "SELECT * FROM prodi");
while($a = mysqli_fetch_array($prod)){
if ($a['ProdiID'] == $e['Pilihan2']){
	echo "<option value='$a[ProdiID]' selected>$a[Nama]</option>";
}else{
	  echo "<option value='$a[ProdiID]'>$a[Nama]</option>";
  }
}
echo "</select></td></tr>

<tr><th scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>Data Pribadi (Sesuai KTP)</th></tr>


<tr><th scope='row'>Warga Negara</th>          
<td><select class='form-control form-control-sm' name='WargaNegara'>"; 
$w = mysqli_query($koneksi, "SELECT * FROM warganegara");
while($a = mysqli_fetch_array($w)){		
if ($a['WargaNegara']==$e['WargaNegara']){
 echo "<option value='$a[WargaNegara]' selected>$a[Nama]</option>";		
}else{
  echo "<option value='$a[WargaNegara]'>$a[Nama]</option>";
  }
}
echo "</select></td></tr> 
<tr><th scope='row'>Kebangsaan</th><td>Jika WNA, Sebutkan<input type='text' class='form-control form-control-sm' name='Kebangsaan' value='$e[Kebangsaan]'></td></tr>	
<tr><th scope='row'>Agama</th>          
<td><select class='form-control form-control-sm' name='Agama'> 
<option value='0' selected>- Pilih agama -</option>"; 
$ag = mysqli_query($koneksi, "SELECT * FROM agama");
while($a = mysqli_fetch_array($ag)){
if ($a['Agama'] == $e['Agama']){
	echo "<option value='$a[Agama]' selected>$a[Agama] - $a[Nama]</option>";
}else{
	  echo "<option value='$a[Agama]'>$a[Nama]</option>";
} 
}
  echo "</select></td></tr>  
<tr><th scope='row'>Status Sipil</th>          
<td><select class='form-control form-control-sm' name='StatusSipil'> 
<option value='0' selected>- Pilih Status Sipil -</option>"; 
$ag = mysqli_query($koneksi, "SELECT * FROM statussipil");
while($a = mysqli_fetch_array($ag)){
if ($a['StatusSipil'] == $e['StatusSipil']){
	echo "<option value='$a[StatusSipil]' selected>$a[StatusSipil] - $a[Nama]</option>";
}else{
	  echo "<option value='$a[StatusSipil]'>$a[StatusSipil] - $a[Nama]</option>";
} 
}
echo "</select></td></tr> 

<tr><th scope='row'>Alamat Tinggal</th><td><input type='text' class='form-control form-control-sm' name='Alamat' value='$e[Alamat]'></td></tr>	
<tr><th scope='row'>Kota /Kabupaten</th><td><input type='text' class='form-control form-control-sm' name='Kota' value='$e[Kota]'></td></tr>
<tr><th scope='row'>RT</th><td><input type='text' class='form-control form-control-sm' name='RT' value='$e[RT]'></td></tr>
<tr><th scope='row'>RW</th><td><input type='text' class='form-control form-control-sm' name='RW' value='$e[RW]'></td></tr>
<tr><th scope='row'>Kelurahan</th><td><input type='text' class='form-control form-control-sm' name='Kelurahan' value='$e[Kelurahan]'></td></tr>
<tr><th scope='row'>Kecamatan</th><td><input type='text' class='form-control form-control-sm' name='Kecamatan' value='$e[Kecamatan]'></td></tr>
<tr><th scope='row'>Kode POS</th><td><input type='text' class='form-control form-control-sm' name='KodePOS' value='$e[KodePOS]'></td></tr>
<tr><th scope='row'>Propinsi</th><td><input type='text' class='form-control form-control-sm' name='Propinsi' value='$e[Propinsi]'></td></tr>
<tr><th scope='row'>Negara</th><td><input type='text' class='form-control form-control-sm' name='Negara' value='$e[Negara]'></td></tr>
<tr><th scope='row'>Telepon</th><td><input type='text' class='form-control form-control-sm' name='Telepon' value='$e[Telepon]'></td></tr>
<tr><th scope='row'>Telp. Bergerak / HP </th><td><input type='text' class='form-control form-control-sm' name='Handphone' value='$e[Handphone]'></td></tr>
<tr><th scope='row'>Email</th><td><input type='text' class='form-control form-control-sm' name='Email' value='$e[Email]'></td></tr>


<tr><th scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>Asal Sekolah</th></tr>
<tr><th scope='row'>Asal Sekolah <a href='?ndelox=asalsekolah&act=tambah'>[ Tambah Sekolah ]</a></th>          
<td><select class='form-control form-control-sm' name='AsalSekolah'> 
<option value='0' selected>- Pilih Asal Sekolah -</option>"; 
$ag = mysqli_query($koneksi, "SELECT * FROM asalsekolah");
while($a = mysqli_fetch_array($ag)){
  if ($a['SekolahID'] == $e['AsalSekolah']){
	echo "<option value='$a[SekolahID]' selected>$a[Kota] - $a[Nama] - $a[SekolahID]</option>";
  }else{
	echo "<option value='$a[SekolahID]'>$a[Kota] - $a[Nama] - $a[SekolahID]</option>";
  }
}
echo "</select></td></tr> 

<tr><th scope='row'>Jurusan Sekolah</th>          
<td><select class='combobox form-control' name='JurusanSekolah'> 
<option value='0' selected>- Pilih Jurusan -</option>"; 
$ag = mysqli_query($koneksi, "SELECT * FROM jurusansekolah order by JurusanSekolahID");
while($a = mysqli_fetch_array($ag)){
 if ($a['JurusanSekolahID'] == $e['JurusanSekolah']){
	echo "<option value='$a[JurusanSekolahID]' selected>$a[JurusanSekolahID] - $a[NamaJurusan]</option>";
  }else{	
	echo "<option value='$a[JurusanSekolahID]'>$a[JurusanSekolahID] - $a[NamaJurusan]</option>";
}
}
echo "</select></td></tr>

<tr><th scope='row'>Tahun Lulus</th><td><input type='text' class='form-control form-control-sm' name='TahunLulus' value='$e[TahunLulus]'></td></tr>
<tr><th scope='row'>Nilai Kelulusan</th><td><input type='text' class='form-control form-control-sm' name='NilaiSekolah' value='$e[NilaiSekolah]'></td></tr>


<tr><th scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>Asal Perguruan Tinggi</th></tr>
<tr><th scope='row'>Nama Perguruan Tinggi</th><td><input type='text' class='form-control form-control-sm' name='AsalPT' value='$e[AsalPT]'></td></tr>
<tr><th scope='row'>Dari Program Studi</th><td><input type='text' class='form-control form-control-sm' name='ProdiAsalPT' value='$e[ProdiAsalPT]'></td></tr>
<tr><th scope='row'>Telah Lulus dr PT ini?</th><td>Jika ya, maka lulus tanggal<input type='text' class='form-control form-control-sm' name='TglLulusAsalPT' value='$e[TglLulusAsalPT]'></td></tr>

<tr><th scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>Alamat Tinggal Pekanbaru</th></tr>
<tr><th scope='row'>Alamat</th><td><input type='text' class='form-control form-control-sm' name='ae'></td></tr>
<tr><th scope='row'>Kota</th><td><input type='text' class='form-control form-control-sm' name='ae'></td></tr>
<tr><th scope='row'>RT/RW</th><td><input type='text' class='form-control form-control-sm' name='ae'></td></tr>
<tr><th scope='row'>Telepon</th><td><input type='text' class='form-control form-control-sm' name='ae'></td></tr>
<tr><th scope='row'>Kode POS</th><td><input type='text' class='form-control form-control-sm' name='ae'></td></tr>
<tr><th scope='row'>Propinsi</th><td><input type='text' class='form-control form-control-sm' name='ae'></td></tr>
<tr><th scope='row'>Negara</th><td><input type='text' class='form-control form-control-sm' name='ae'></td></tr>

<tr><th scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>Data Orang Tua</th></tr>
<tr><th scope='row'>Nama Ayah</th><td><input type='text' class='form-control form-control-sm' name='NamaAyah' value='$e[NamaAyah]'></td></tr>
<tr><th scope='row'>Agama Ayah</th>          
<td><select class='form-control form-control-sm' name='AgamaAyah'> 
<option value='0' selected>- Pilih agama -</option>"; 
$ag = mysqli_query($koneksi, "SELECT * FROM agama");
while($a = mysqli_fetch_array($ag)){
  if ($a['Agama'] == $e['AgamaAyah']){
	echo "<option value='$a[Agama]' selected>$a[Agama] - $a[Nama]</option>";
  }else{
	echo "<option value='$a[Agama]'>$a[Agama] - $a[Nama]</option>";
  }
}
echo "</select></td></tr>  
<tr><th scope='row'>Pendidikan Ayah</th>          
<td><select class='form-control form-control-sm' name='PendidikanAyah'> 
<option value='0' selected>- Pilih Pendidikan Ayah -</option>"; 
$kerja = mysqli_query($koneksi, "SELECT * FROM pendidikanortu");
while($a = mysqli_fetch_array($kerja)){
  if ($a['Pendidikan'] == $e['PendidikanAyah']){
	echo "<option value='$a[Pendidikan]' selected>$a[Pendidikan] - $a[Nama]</option>";
  }else{
	echo "<option value='$a[Pendidikan]'>$a[Pendidikan] - $a[Nama]</option>";
  }
}
echo "</select></td></tr> 
<tr><th scope='row'>Pekerjaan Ayah</th>          
<td><select class='form-control form-control-sm' name='PekerjaanAyah'> 
<option value='0' selected>- Pilih Pekerjaan Ayah -</option>"; 
$kerja = mysqli_query($koneksi, "SELECT * FROM pekerjaanortu");
while($a = mysqli_fetch_array($kerja)){
  if ($a['Pekerjaan'] == $e['PekerjaanAyah']){
	echo "<option value='$a[Pekerjaan]' selected>$a[Pekerjaan] - $a[Nama]</option>";
  }else{
	echo "<option value='$a[Pekerjaan]'>$a[Pekerjaan] - $a[Nama]</option>";
  }
}
echo "</select></td></tr> 
<tr><th scope='row'>Status Ayah</th>          
<td><select class='form-control form-control-sm' name='HidupAyah'> 
"; 
$hd = mysqli_query($koneksi, "SELECT * FROM hidup");
while($a = mysqli_fetch_array($hd)){
  if ($a['Hidup'] == $e['HidupAyah']){
	echo "<option value='$a[Hidup]' selected>$a[Hidup] - $a[Nama]</option>";
  }else{
	echo "<option value='$a[Hidup]'>$a[Hidup] - $a[Nama]</option>";
  }
}
echo "</select></td></tr> 

<tr><th  scope='row'>Nama Ibu</th> <td><input type='text' class='form-control form-control-sm' name='NamaIbu' value='$e[NamaIbu]'></td></tr>
<tr><th scope='row'>Agama Ibu</th>          
<td><select class='form-control form-control-sm' name='AgamaIbu'> 
<option value='0' selected>- Pilih agama -</option>"; 
$ag = mysqli_query($koneksi, "SELECT * FROM agama");
while($a = mysqli_fetch_array($ag)){
  if ($a['Agama'] == $e['AgamaIbu']){
	echo "<option value='$a[Agama]' selected>$a[Agama] - $a[Nama]</option>";
  }else{
	echo "<option value='$a[Agama]'>$a[Agama] - $a[Nama]</option>";
  }
}
echo "</select></td></tr> 
<tr><th scope='row'>Pendidikan Ibu</th>          
<td><select class='form-control form-control-sm' name='PendidikanIbu'> 
<option value='0' selected>- Pilih Pendidikan Ibu -</option>"; 
$kerja = mysqli_query($koneksi, "SELECT * FROM pendidikanortu");
while($a = mysqli_fetch_array($kerja)){
  if ($a['Pendidikan'] == $e['PendidikanIbu']){
	echo "<option value='$a[Pendidikan]' selected>$a[Pendidikan] - $a[Nama]</option>";
  }else{
	echo "<option value='$a[Pendidikan]'>$a[Pendidikan] - $a[Nama]</option>";
  }
}
echo "</select></td></tr> 
<tr><th scope='row'>Pekerjaan Ibu</th>          
<td><select class='form-control form-control-sm' name='PekerjaanIbu'> 
<option value='0' selected>- Pilih Pekerjaan Ibu -</option>"; 
$kerja = mysqli_query($koneksi, "SELECT * FROM pekerjaanortu");
while($a = mysqli_fetch_array($kerja)){
  if ($a['Pekerjaan'] == $e['PekerjaanIbu']){
	echo "<option value='$a[Pekerjaan]' selected>$a[Pekerjaan] - $a[Nama]</option>";
  }else{
	echo "<option value='$a[Pekerjaan]'>$a[Pekerjaan] - $a[Nama]</option>";
  }
}
echo "</select></td></tr>  
<tr><th scope='row'>Status Ibu</th>          
<td><select class='form-control form-control-sm' name='HidupIbu'> 
"; 
$hd = mysqli_query($koneksi, "SELECT * FROM hidup");
while($a = mysqli_fetch_array($hd)){
  if ($a['Hidup'] == $e['HidupIbu']){
	echo "<option value='$a[Hidup]' selected>$a[Hidup] - $a[Nama]</option>";
  }else{
	echo "<option value='$a[Hidup]'>$a[Hidup] - $a[Nama]</option>";
  }
}
echo "</select></td></tr> 
<tr><th scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>Alamat Orang Tua</th></tr>
<tr><th scope='row'>Alamat Orang Tua</th><td><input type='text' class='form-control form-control-sm' name='AlamatOrtu' value='$e[AlamatOrtu]'></td></tr>
<tr><th scope='row'>Kota</th><td><input type='text' class='form-control form-control-sm' name='KotaOrtu' value='$e[KotaOrtu]'></td></tr>
<tr><th scope='row'>RT</th><td><input type='text' class='form-control form-control-sm' name='RTOrtu' value='$e[RTOrtu]'></td></tr>
<tr><th scope='row'>RW</th><td><input type='text' class='form-control form-control-sm' name='RWOrtu' value='$e[RWOrtu]'></td></tr>
<tr><th scope='row'>Kode POS</th><td><input type='text' class='form-control form-control-sm' name='KodePosOrtu' value='$e[KodePosOrtu]'></td></tr>
<tr><th scope='row'>Propinsi</th><td><input type='text' class='form-control form-control-sm' name='PropinsiOrtu' value='$e[PropinsiOrtu]'></td></tr>
<tr><th scope='row'>Negara</th><td><input type='text' class='form-control form-control-sm' name='NegaraOrtu' value='$e[NegaraOrtu]'></td></tr>
<tr><th scope='row'>Telepon</th><td><input type='text' class='form-control form-control-sm' name='TeleponOrtu' value='$e[TeleponOrtu]'></td></tr>
<tr><th scope='row'>Telepon Bergerak / HP</th><td><input type='text' class='form-control form-control-sm' name='HandphoneOrtu' value='$e[HandphoneOrtu]'></td></tr>
<tr><th scope='row'>Email</th><td><input type='text' class='form-control form-control-sm' name='EmailOrtu' value='$e[EmailOrtu]'></td></tr>
</tbody>
</table>
<div class='box-footer'>
<button class='btn btn-success btn-sm' type='submit' name='ubahdaftar' class='btn btn-info'>Perbaharui Data</button>
<a href='index.php?ndelox=pmb/pmbformdaftar&tahun=$_GET[tahun]&formulir=$_GET[formulir]&program=$_GET[program]'>
<button type='button' class='btn btn-default btn-sm pull-right'>Cancel</button></a>     
</form>
</div>
</div>
</div>
</div>

";
}

?>