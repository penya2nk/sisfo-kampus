<?php if ($_GET['act']==''){ ?>
<div class="card">
<div class="card-header">

<div class="form-group row">
	<label class="col-md-7 col-form-label text-md-right"><b style='color:purple'>REG PMB</b></label>
		<div class="col-md-2">	  
		<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
		<input type="hidden" name='ndelox' value='penmaba/admpmbreg'> 
			<select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
				<?php 
					echo "<option value=''>- Pilih Periode PMB -</option>";
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
				<input type="submit" class='btn btn-success btn-sm' value='Lihat'>
			</form>
		</div>
	</div>


</div>  	
</div>  

<div class="card">
<div class="card-header">
	    <div class="table-responsive">
	  <table id="example" class="table table-sm table-striped">
		<thead>
		 <tr style="background:purple;color:white">
		  <th  width="10px">No</th>					  					 
		  <th width="100px"> PMBID</th>		  
		  <th width="200px">Nama</th>
		  <th width="80px">Handphone</th>		  
		  <th width="40px">Program</th>
		  <th width="30px">Prodi</th>
		  <th width="30px">Status</th>
		  <th width="30px">Lulus?</th>
		  <th width="30px">Registered?</th>
		  <th width="40px">NIM</th>
		  <th width="200px">Aksi</th>
		</tr>
		</thead>
		<tbody>
	  <?php
		if (isset($_GET['tahun'])){
			if ($_GET['tahun']!='' AND $_GET['prodi']!=''){
			  $tampil = mysqli_query($koneksi, "SELECT * FROM pmb where ProdiID='".strfilter($_GET['prodi'])."' AND PMBPeriodID='".strfilter($_GET['tahun'])."' order by PMBID Desc");                    
			}
			else if ($_GET['tahun']!='' AND $_GET['prodi']==''){
			  $tampil = mysqli_query($koneksi, "SELECT * FROM pmb where PMBPeriodID='".strfilter($_GET['tahun'])."' order by PMBID Desc");                    
			}
			else if ($_GET['tahun']=='' AND $_GET['prodi']!==''){
			  $tampil = mysqli_query($koneksi, "SELECT * FROM pmb where ProdiID='".strfilter($_GET['prodi'])."' order by PMBID Desc");                    
			}
			else{
			  $tampil = mysqli_query($koneksi, "SELECT * FROM vw_taxxxxxxxxx  order by PMBID Desc");                
			}				
			$no = 1;
			while($r=mysqli_fetch_array($tampil)){
			$status = mysqli_fetch_array(mysqli_query($koneksi, "SELECT StatusAwalID,Nama from statusawal where StatusAwalID='$r[StatusAwalID]'"));	
			$namax 		= strtolower($r['Nama']);
			$Nama		= ucwords($namax);
		    if ($r['RegUlang']=='Y'){
		        $RegStat="Aktif";
				$c="style=color:green";
			}else{
		        $RegStat="Belum";
				$c="style=color:black";
			}
		
			  echo "<tr $c>
			  <td>$no</td>					  					 
			  <td><a href='?ndelox=pmb/admpmbreg&act=setnimawal&PMBID=$r[PMBID]&PMBPeriodID=$r[PMBPeriodID]&program=$r[ProgramID]&prodi=$r[ProdiID]&tahun=$r[PMBPeriodID]'>$r[PMBID]</a></td>
			  <td>$Nama</td>			  
			  <td>$r[Handphone]</td>
			  <td>$r[ProgramID]</td> 
			  <td>$r[ProdiID]</td>
			  <td>$status[Nama]</td>
			  <td>$r[LulusUjian] - $r[NilaiUjian]</td>
			  <td>$RegStat</td>	
			  <td>$r[NIM]</td>
			  <td>
			  <a class='btn btn-success btn-xs' title='View' href='index.php?ndelox=pmb/admpmbreg&act=viewdetail&tahun=$r[PMBPeriodID]&formulir=&PMBID=$r[PMBID]'><span class='glyphicon glyphicon-list'></span> View</a>
			  <a class='btn btn-success btn-xs' title='Generate NIM' href='?ndelox=admpmbreg&act=gawenim&PMBID=$r[PMBID]&PMBPeriodID=$r[PMBPeriodID]&program=$r[ProgramID]&prodi=$r[ProdiID]' onclick=\"return confirm('Yakin Data $r[PMBID]-$r[Nama] Akan digenerate NIM ?')\"><span class='glyphicon glyphicon-list'></span> Generate NIM</a>
			  <a class='btn btn-success btn-xs' title='Generate NIM' href='?ndelox=admpmbreg&act=orasido&PMBID=$r[PMBID]&PMBPeriodID=$r[PMBPeriodID]&program=$r[ProgramID]&prodi=$r[ProdiID]' onclick=\"return confirm('Yakin Generate NIM untuk data $r[PMBID]-$r[Nama] Dibatalkan?, data Mahasiswa juga akan terhapus!')\"><span class='glyphicon glyphicon-list'></span> Batal</a>
			  </td>
			  </tr>";
			  $no++;
			  }
		}	//get tahun top  
	  ?>
		</tbody>
	  </table>
	  </div>
	</div><!-- /.box-body -->
	</div>
</div>

<?php 
}

elseif($_GET['act']=='viewdetail'){
$e =mysqli_fetch_array(mysqli_query($koneksi,  "SELECT * from pmb where PMBID='".strfilter($_GET['PMBID'])."'"));
echo "
<div class='card'>
<div class='card-header'>

	<div class='box box-info'>
	<h3 class='box-title'><b style=color:green;font-size=18px>Detail Informasi PMB</b></h3>
	</div>
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
<tr><th  scope='row' colspan='2' style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;>Info Formulir PMB</th></tr>
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
<tr><th scope='row'>Asal Sekolah</th>          
<td><select class='combobox form-control' name='AsalSekolah'> 
<option value='0' selected>- Pilih Asal Sekolah -</option>"; 
$ag = mysqli_query($koneksi, "SELECT * FROM asalsekolah  
				   WHERE Kota like '%Pekanbaru%'
				   OR Kota like '%Dumai%'
				   OR Kota like '%Bengkalis%'
				   OR Kota like '%Bangkinang%'
				   OR Kota like '%Kerinci%'
				   OR Kota like '%Rupat%'
				   OR Kota like '%Bengkalis%'
				   OR Kota like '%Bagan%'
				   OR Kota like '%Pasir%'
				   OR Kota like '%Duri%'
				   OR Kota like '%Taluk%'
				   OR Kota like '%Padang%'
				   OR Kota like '%Bukit%'
				   OR Kota like '%pakning%'
				   OR Kota like '%Rengat%'
				   OR Kota like '%medan%'
				   OR Kota like '%siantar%'
				   OR Kota like '%palembang%'
				   OR Kota like '%jambi%'
				   OR Kota like '%batam%'
				    OR Kota like '%siak%'
				   OR Kota like '%pinang%'
				   
				   ");
while($a = mysqli_fetch_array($ag)){
  if ($a['SekolahID'] == $e['AsalSekolah']){
	echo "<option value='$a[SekolahID]' selected>$a[SekolahID] - $a[Nama]</option>";
  }else{
	echo "<option value='$a[SekolahID]'>$a[SekolahID] - $a[Nama]</option>";
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
<a href='index.php?ndelox=pmb/admpmbreg&tahun=$_GET[tahun]&formulir=$_GET[formulir]&program=$_GET[program]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>    
</div>
</div>
</div>
</div>


</form>
</div>";
}

elseif($_GET['act']=='gawenim'){
$th				=substr(date('Y'),2,4); //2019
$BatasStudix	=date('Y')+3;
$BatasStudi		=$BatasStudix."2";
//periode PMB Aktif
if($_GET['prodi']=='SI'){
  $ProdiKode="07"; 
}else{
  $ProdiKode="08";
}

if($_GET['program']=='REG A'){
  $ProgramKode="1"; 
}
elseif($_GET['program']=='TRANS'){
  $ProgramKode="2"; 
}
elseif($_GET['program']=='NREG B'){
  $ProgramKode="2"; 
}
else{
  $ProgramKode="3";
}

$p 			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM pmbperiod Where NA='N'"));
$pmbaktif 	= $p['PMBPeriodID'];

$thakademikx	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM tahun Where NA='N'"));
$thakademik 	= $thakademikx['TahunID'];

$cek = mysqli_num_rows(mysqli_query($koneksi, "SELECT NIM, ProdiID,ProgramID,PMBID from pmb WHERE PMBID='".strfilter($_GET['PMBID'])."'
AND NIM NOT IN ('','NULL') "));

//select max ganti dengan Desc Limit 1
$data 	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT NIM as maxID FROM pmb 
										WHERE ProgramID='".strfilter($_GET['program'])."' 
										AND ProdiID='".strfilter($_GET['prodi'])."' 
										AND LEFT(PMBPeriodID,4)='2019' ORDER BY NIM DESC LIMIT 1")); 
$idMax 	= $data['maxID'];
$NoUrut = (int) substr($idMax, 6, 3);
$NoUrut++; 
$NewID 	= $th . $ProdiKode . $ProgramKode .sprintf('%03s', $NoUrut);

if ($cek>0){
   echo "<script>document.location='index.php?ndelox=pmb/admpmbreg&program=$_GET[program]&prodi=$_GET[prodi]&tahun=$_GET[PMBPeriodID]';</script>";
}else{
   mysqli_query($koneksi, "UPDATE pmb SET NIM='$NewID',RegUlang='Y',LulusUjian='Y',NilaiUjian='80' where PMBID='".strfilter($_GET['PMBID'])."'");
   
   //generate nim
   $sqlcek2=mysqli_query($koneksi,  "SELECT MhswID from mhsw where MhswID='".strfilter($_GET['$NewID'])."'");
		$cek2=mysqli_num_rows($sqlcek2);
		if ($cek2>0){
		    echo "<script>document.location='index.php?ndelox=pmb/admpmbreg&program=$_GET[program]&prodi=$_GET[prodi]&tahun=$_GET[PMBPeriodID]';</script>";
		}else{
			$pmb= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from pmb WHERE PMBID='".strfilter($_GET['PMBID'])."'"));
			mysqli_query("INSERT into mhsw(MhswID,
													 Login,
													 Password,
													 PMBID,
													 PMBFormJualID,
													 ProgramID,
													 ProdiID,
													 Nama,
													 StatusAwalID,
													 StatusMhswID,
													 Kelamin,
													 Alamat,
													 TahunID,
													 TempatLahir,
													 TanggalLahir,
													 NamaAyah,
													 NamaIbu,
													 NIK,
													 Kecamatan,
													 Kelurahan,
													 PasswordBro,
													 Agama,
													 Handphone,
													 Kota,
													 BIPOTID,
													 AlamatAsal,
													 KotaAsal,
													 TanggalBuat,
													 LoginBuat,
													 BatasStudi,
													 AsalSekolah,
													 JenisSekolahID,
													 AlamatSekolah,
													 KotaSekolah,
													 JurusanSekolah,
													 TahunLulus,
													 RT,
													 RW,
													 Email,
													 RTAsal,
													 RWAsal,
													 WargaNegara,
													 Kebangsaan,
													 HandphoneOrtu,
													 NoIjazah,
													 TglIjazah,
													 Propinsi,
													 Negara,
													 AgamaAyah,
													 PendidikanAyah,
													 PekerjaanAyah,
													 HidupAyah,
													 AgamaIbu,
													 PendidikanIbu,
													 PekerjaanIbu,
													 HidupIbu,
													 AlamatOrtu)
											values('$pmb[NIM]',
												   '$pmb[NIM]',
												   '*6BB4837EB',
												   '$pmb[PMBID]',
												   '$pmb[PMBFormJualID]',
												   '$pmb[ProgramID]',
												   '$pmb[ProdiID]',
												   '$pmb[Nama]',
												   '$pmb[StatusAwalID]',
												   'A',
												   '$pmb[Kelamin]',
												   '$pmb[Alamat]',
												   '$thakademik',
												   '$pmb[TempatLahir]',
												   '$pmb[TanggalLahir]',
												   '$pmb[NamaAyah]',
												   '$pmb[NamaIbu]',
												   '$pmb[NIK]',
												   '$pmb[Kecamatan]',
												   '$pmb[Kelurahan]',
												   '9970f16668b0ce09b694293b5164ae2b211fb9a23e9026bb4d0d1aef370f192120dd5f5a8e78c06d57fa036de0975c09b528ea7dc49262aee10c3247e62964fa',
												   '$pmb[Agama]',
												   '$pmb[Handphone]',
												   '$pmb[Kota]',
												   '$pmb[BIPOTID]',
												   '$pmb[AlamatAsal]',
												   '$pmb[KotaAsal]',
												   '".date('Y-m-d H:i:s')."',
												   '$_SESSION[id]',
												   '$BatasStudi',
												   '$pmb[AsalSekolah]',
												   '$pmb[JenisSekolahID]',
												   '$pmb[AlamatSekolah]',
												   '$pmb[KotaSekolah]',
												   '$pmb[JurusanSekolah]',
												   '$pmb[TahunLulus]',
												   '$pmb[RT]',
												   '$pmb[RW]',
												   '$pmb[Email]',
												   '$pmb[RTAsal]',
												   '$pmb[RWAsal]',
												   '$pmb[WargaNegara]',
												   '$pmb[Kebangsaan]',
												   '$pmb[HandphoneOrtu]',
												   '$pmb[NoIjazah]',
												   '$pmb[TglIjazah]',
												   '$pmb[Propinsi]',
												   '$pmb[Negara]',
												   '$pmb[AgamaAyah]',
												   '$pmb[PendidikanAyah]',
												   '$pmb[PekerjaanAyah]',
												   '$pmb[HidupAyah]',
												   '$pmb[AgamaIbu]',
												   '$pmb[PendidikanIbu]',
												   '$pmb[PekerjaanIbu]',
												   '$pmb[HidupIbu]',
												   '$pmb[AlamatOrtu]')");
		    echo "<script>document.location='index.php?ndelox=pmb/admpmbreg&program=$_GET[program]&prodi=$_GET[prodi]&tahun=$_GET[PMBPeriodID]';</script>";
	    } 
	echo "<script>document.location='index.php?ndelox=pmb/admpmbreg&program=$_GET[program]&prodi=$_GET[prodi]&tahun=$_GET[PMBPeriodID]';</script>";
}


}

elseif($_GET['act']=='orasido'){  
   $pmb= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from pmb WHERE PMBID='".strfilter($_GET['PMBID'])."'"));   
   mysqli_query($koneksi, "DELETE FROM mhsw WHERE MhswID='$pmb[NIM]'");
   mysqli_query($koneksi, "UPDATE pmb SET NIM='',RegUlang='N',LulusUjian='N',NilaiUjian='0' where PMBID='".strfilter($_GET['PMBID'])."'");
   echo "<script>document.location='index.php?ndelox=pmb/admpmbreg&prodi=$_GET[prodi]&tahun=$_GET[PMBPeriodID]';</script>";
}

elseif($_GET['act']=='setnimawal'){  
    if (isset($_POST['update'])){
        $query = mysqli_query($koneksi, "UPDATE pmb SET                                                                    
                                         NIM = '".strfilter($_POST['NIM'])."',
										 RegUlang='Y',
										 LulusUjian='Y',
										 NilaiUjian='80',
										 LoginEdit='$_SESSION[_Login]',
										 TanggalEdit='".date('Y-m-d H:i:s')."'
										 WHERE PMBID='".strfilter($_POST['PMBID'])."'");
		
        $pmb= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from pmb WHERE PMBID='".strfilter($_GET['PMBID'])."'"));
		mysqli_query("INSERT into mhsw(MhswID,
													 Login,
													 Password,
													 PMBID,
													 PMBFormJualID,
													 ProgramID,
													 ProdiID,
													 Nama,
													 StatusAwalID,
													 StatusMhswID,
													 Kelamin,
													 Alamat,
													 TahunID,
													 TempatLahir,
													 TanggalLahir,
													 NamaAyah,
													 NamaIbu,
													 NIK,
													 Kecamatan,
													 Kelurahan,
													 PasswordBro,
													 Agama,
													 Handphone,
													 Kota,
													 BIPOTID,
													 AlamatAsal,
													 KotaAsal,
													 TanggalBuat,
													 LoginBuat,
													 BatasStudi,
													 AsalSekolah,
													 JenisSekolahID,
													 AlamatSekolah,
													 KotaSekolah,
													 JurusanSekolah,
													 TahunLulus,
													 RT,
													 RW,
													 Email,
													 RTAsal,
													 RWAsal,
													 WargaNegara,
													 Kebangsaan,
													 HandphoneOrtu,
													 NoIjazah,
													 TglIjazah,
													 Propinsi,
													 Negara,
													 AgamaAyah,
													 PendidikanAyah,
													 PekerjaanAyah,
													 HidupAyah,
													 AgamaIbu,
													 PendidikanIbu,
													 PekerjaanIbu,
													 HidupIbu,
													 AlamatOrtu)
											values('$pmb[NIM]',
												   '$pmb[NIM]',
												   '*6BB4837EB',
												   '$pmb[PMBID]',
												   '$pmb[PMBFormJualID]',
												   '$pmb[ProgramID]',
												   '$pmb[ProdiID]',
												   '$pmb[Nama]',
												   '$pmb[StatusAwalID]',
												   'A',
												   '$pmb[Kelamin]',
												   '$pmb[Alamat]',
												   '$thakademik',
												   '$pmb[TempatLahir]',
												   '$pmb[TanggalLahir]',
												   '$pmb[NamaAyah]',
												   '$pmb[NamaIbu]',
												   '$pmb[NIK]',
												   '$pmb[Kecamatan]',
												   '$pmb[Kelurahan]',
												   '9970f16668b0ce09b694293b5164ae2b211fb9a23e9026bb4d0d1aef370f192120dd5f5a8e78c06d57fa036de0975c09b528ea7dc49262aee10c3247e62964fa',
												   '$pmb[Agama]',
												   '$pmb[Handphone]',
												   '$pmb[Kota]',
												   '$pmb[BIPOTID]',
												   '$pmb[AlamatAsal]',
												   '$pmb[KotaAsal]',
												   '".date('Y-m-d H:i:s')."',
												   '$_SESSION[_Login]',
												   '$BatasStudi',
												   '$pmb[AsalSekolah]',
												   '$pmb[JenisSekolahID]',
												   '$pmb[AlamatSekolah]',
												   '$pmb[KotaSekolah]',
												   '$pmb[JurusanSekolah]',
												   '$pmb[TahunLulus]',
												   '$pmb[RT]',
												   '$pmb[RW]',
												   '$pmb[Email]',
												   '$pmb[RTAsal]',
												   '$pmb[RWAsal]',
												   '$pmb[WargaNegara]',
												   '$pmb[Kebangsaan]',
												   '$pmb[HandphoneOrtu]',
												   '$pmb[NoIjazah]',
												   '$pmb[TglIjazah]',
												   '$pmb[Propinsi]',
												   '$pmb[Negara]',
												   '$pmb[AgamaAyah]',
												   '$pmb[PendidikanAyah]',
												   '$pmb[PekerjaanAyah]',
												   '$pmb[HidupAyah]',
												   '$pmb[AgamaIbu]',
												   '$pmb[PendidikanIbu]',
												   '$pmb[PekerjaanIbu]',
												   '$pmb[HidupIbu]',
												   '$pmb[AlamatOrtu]')");
		
		echo "<script>document.location='index.php?ndelox=pmb/admpmbreg&prodi=$_GET[prodi]&tahun=$_GET[PMBPeriodID]';</script>";
    }
    $s = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM pmb where PMBID='".strfilter($_GET['PMBID'])."'"));
    
	$nimterakhir = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MhswID,ProgramID,ProdiID FROM mhsw 
				   where ProgramID='$s[ProgramID]'
				   and ProdiID='$s[ProdiID]'
				   order by MhswID Desc limit 1"));
	$program = $nimterakhir['ProgramID'];
	$jkel = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM kelamin where Kelamin='".$s['Kelamin']."'"));
	echo "<div class='card'>
			<div class='card-header'>
                <div class='box-header with-border'>
                  <h3 class='box-title'><b style=color:green>SET AWAL NIM </b></h3>
                </div>
              <div>
              
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
			  <div class='card'>
			  <div class='card-header'>
			  <div class='table-responsive'>
                  <table class='table table-sm table-bordered'>
                  <tbody>
				     <input type='hidden' name='PMBID' value='$s[PMBID]'>
					 <tr><th width='200px' scope='row'>PMBID</th> <td><input type='text' class='form-control form-control-sm' name='XXX' value='$s[PMBID]' readonly> </td></tr>
					 <tr><th width='200px' scope='row'>Nama</th> <td><input type='text' class='form-control form-control-sm' name='xx' value='$s[Nama]' readonly> </td></tr> 
					 <tr><th width='200px' scope='row'>Jenis Kelamin</th> <td><input type='text' class='form-control form-control-sm' name='xx' value='$jkel[Nama]' readonly> </td></tr> 
					 <tr><th width='200px' scope='row'>Tempat & Tgl Lahir</th> <td><input type='text' class='form-control form-control-sm' name='xx' value='$s[TempatLahir], ".tgl_indo($s[TanggalLahir])."' readonly> </td></tr> 
					 <tr><th width='200px' scope='row'>Alamat</th> <td><input type='text' class='form-control form-control-sm' name='xx' value='$s[Alamat]' readonly> </td></tr> 
					 <tr><th width='200px' scope='row'>ProgramID</th> <td><input type='text' class='form-control form-control-sm' name='xx' value='$s[ProgramID]' readonly> </td></tr> 
					 <tr><th width='200px' scope='row'>ProdiID</th> <td><input type='text' class='form-control form-control-sm' name='Namxa' value='$s[ProdiID]' readonly> </td></tr>
                     <tr><th width='200px' scope='row'>Handphone</th> <td><input type='text' class='form-control form-control-sm' name='xx' value='$s[Handphone]' readonly> </td></tr>					 
                     <tr><th width='200px' scope='row'>NIM Terakhir</th> <td>$nimterakhir[MhswID] <a href='?ndelox=mhs&program=$program&prodi=$nimterakhir[ProdiID]' target=_BLANK>(Klik di sini untuk memastikan di master mahasiswa!)</a></td></tr>
					 <tr><th width='200px' scope='row'>NIM</th> <td><input type='text' class='form-control form-control-sm' name='NIM' value='$s[NIM]'> </td></tr> 					 					 
                  </tbody>
                  </table>
				  </div>
				  </div>
				  </div>
              <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>SET</button>
                    <a href='index.php?ndelox=pmb/admpmbreg&prodi=$_GET[prodi]&tahun=$_GET[tahun]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>                  
                  </div>
              </form>
            </div>";
}
?>