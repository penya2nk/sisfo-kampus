<div class='card'>
<div class='card-header'>
<div class="form-group row">
	<label class="col-md-5 col-form-label text-md-right"><b style='color:purple'>FILTER DATA</b></label>
		<div class="col-md-2">  
		<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
		<input type="hidden" name='ndelox' value='master/matkul'>
		<select name='prodi'  class='form-control form-control-sm form-control form-control-sm-sm' onChange='this.form.submit()'>
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


		<div class="col-md-2">
		<select name='kurikulum'  class='form-control form-control-sm form-control form-control-sm-sm' onChange='this.form.submit()'>
		<?php 
			echo "<option value=''>- Pilih Kurikulum -</option>";
			$kurikulum = mysqli_query($koneksi, "SELECT * FROM kurikulum where ProdiID='".strfilter($_GET['prodi'])."' and NA='N'");
			while ($k = mysqli_fetch_array($kurikulum)){
			if ($_GET['kurikulum']==$k['KurikulumID']){
				echo "<option value='$k[KurikulumID]' selected>$k[KurikulumID] - $k[ProdiID] - $k[Nama]</option>";
			}else{
				echo "<option value='$k[KurikulumID]'>$k[KurikulumID]- $k[ProdiID] - $k[Nama] </option>";
			}
			}
		?>
		</select>
		</div>                


		<div class="col-md-1">
		<input type="submit" class='btn btn-success btn-sm' value='Lihat'>
		</form>
		</div>                


		<div class="col-md-2">
		<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=master/matkul&act=tambahmatkul&prodi=<?php echo strfilter($_GET['prodi']); ?>&kurikulum=<?php echo strfilter($_GET['kurikulum']); ?>'>Tambahkan Data </a>
		</div>                
	</div>
	
</div>
</div>

<div class='card'>
<div class='card-header'>
	<div class='table-responsive'>
	<table class=bsc width=100% cellspacing=1 cellpadding=4 style='align:center;border-style: none'>
	<tr style='text-align:center;font-size:16px;font-weight:bold'>
	<td>
	<?php echo"
	| <a class='btn btn-success btn-xs' href='?ndelox=master/matkul&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'>MATAKULIAH</a> |
	<a class='btn btn-info btn-xs' href='?ndelox=master/matkul&act=mksetara&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'>MK SETARA</a> |
	<a class='btn btn-warning btn-xs' href='?ndelox=master/matkul&act=datakur&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'>KURIKULUM</a> |
	<a class='btn btn-primary btn-xs' href='?ndelox=master/matkul&act=konsentrasi&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'>KONSENTRASI</a> |
	<a class='btn btn-danger btn-xs' href='?ndelox=master/matkul&act=jenismk&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'>JENIS MATAKULIAH</a> |
	<a class='btn btn-info btn-xs' href='?ndelox=master/matkul&act=pilihanwajib&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'>PILIHAN WAJIB</a> |
	<a class='btn btn-warning btn-xs' href='?ndelox=master/matkul&act=jeniskur&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'>JENIS KURIKULUM</a> |
	<a class='btn btn-danger btn-xs' href='?ndelox=master/matkul&act=nilai&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'>PENILAIAN</a> |
	<a class='btn btn-success btn-xs'  href='?ndelox=master/matkul&act=maxsks&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'>MAX SKS</a> |
	<a class='btn btn-primary btn-xs' href='?ndelox=master/matkul&act=paketmk&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'>PAKET MATAKULIAH</a> |
	<a class='btn btn-danger btn-xs' href='?ndelox=master/matkul&act=predikat&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'>PREDIKAT</a> | ";
	?>
	</td>
	</tr>
	</table>
	</div>
</div>
</div>


<?php if ($_GET['act']==''){ ?>  
<?php
  $arrKurid =mysqli_fetch_array(mysqli_query($koneksi, "select * from kurikulum where KurikulumID='26'"));
  $mx =mysqli_fetch_array(mysqli_query($koneksi, "select max(Sesi) as tSesi from mk where KurikulumID='26'"));
  // Tampilkan
  $arrKurid['JmlSesi'] = ($arrKurid['JmlSesi'] == 0)? 1 : $arrKurid['JmlSesi'];
  $lebar = 100 / $arrKurid['JmlSesi'];
  $mxx = $mx['tSesi'];

  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
	<table class=bsc width=100% cellspacing=1 cellpadding=4>";
  for ($i=1; $i<=$mxx; $i++) {
    //$col++;
    if ($i % $arrKurid['JmlSesi'] == 1) echo "<tr>";
    echo "<td valign=top width=$lebar%>";
	$s = "select  mk.*, kons.KonsentrasiKode, kons.Nama as KONS
		from mk as mk	
		left outer join konsentrasi kons on mk.KonsentrasiID=kons.KonsentrasiID
		where mk.ProdiID='".strfilter($_GET['prodi'])."' 
		and mk.Sesi='$i'
		and mk.KurikulumID='".strfilter($_GET['kurikulum'])."' 
		order by kons.KonsentrasiKode, mk.MKKode";
	$r = mysqli_query($koneksi, $s);
	$nom = 0;
	$tot = 0;
	$kons = 0;
	echo "
	<table id='example' class='table table-sm table-striped' style='width:100%' align='center'>";
	echo "<tr><td colspan=6><b>$arrKurid[Sesi]: &nbsp;$i</b></td></tr>";
	echo "<tr style='background:purple;color:white;height:30px'>
	  <th class=ttl style='width:30px;text-align:center;height:20px'>NO</th>
	  <th class=ttl style='width:80px;text-align:center'>KODE</th>
	  <th class=ttl style='width:480px;text-align:left'>NAMA MATAKULIAH</th>
	 
	  <th class=ttl style='width:30px;text-align:center'>SKS</th>
	  <th class=ttl title='Prasyarat' style='width:50px;text-align:center'>AKSI</th>
	  <th class=ttl title='Prasyarat' style='width:70px;text-align:center'>PRA</th>
	  <th style='background:white'>&nbsp;</th></tr>";
	while ($w = mysqli_fetch_array($r)) {
		if ($kons != $w['KonsentrasiID']) {
			$kons = $w['KonsentrasiID'];
			echo "<tr style='background:#efe6eb;font-size:14px'><td colspan=6 class=inp1>$w[KonsentrasiKode] - <b>$w[KONS]</td></tr>";
		  }
		  $n++;
		  $tot += $w['SKS'];
		  $c = ($w['NA'] == 'Y')? 'class=nac' : 'class=ul';
		  $wjb = ($w['Wajib'] == 'Y')? "<font color=red title='Wajib'>*</font>" : '&nbsp;';
	  $nom++;
	  echo "<tr style='font-size:13px'>
	    <td $c style='text-align:center;height:25px'>$nom</td>
		<td $c style='text-align:center;'>$w[MKKode]</a></td>
		<td $c>$w[Nama] $wjb</td>
		<td $c align=center>$w[SKS]</td>
		<td $c align=center><a href='?ndelox=master/matkul&act=edit&MKID=$w[MKID]&prodi=$w[ProdiID]&kurikulum=$w[KurikulumID]'><i class='fa fa-edit'></i></a></td>
		<td $c align=center><a href=''><i class='fa fa-edit'></i></a></td>
		<td style='background:white'>&nbsp;</td>
		</tr>";
	}
	echo "<tr style='background:#eecadf'><td colspan=3 align=right><b>TOTAL :</b></td><td align=center><b>$tot</b></td><td colspan='2'></td></tr>";
	echo "</table>";
    echo "</td>";
    if ($i % $arrKurid['JmlSesi'] == 0) echo "</tr>";
  }
  $totAll = mysqli_fetch_array(mysqli_query($koneksi, "select sum(mk.SKS) as totAll from mk WHERE KurikulumID='".strfilter($_GET['kurikulum'])."'"));
  echo "</table>
  
  
 

  <table width='100%'>
  <tr>
  <td style='text-align:right'><b style='color:purple;font-size:18px;'>TOTAL KESELURUHAN : $totAll[totAll] SKS</b></td>
  <td>&nbsp;</td>
  </tr>
  </table>";



}elseif($_GET['act']=='edit'){
     if (isset($_POST['update1'])){
	   $tglu = date('Y-m-d H:i:s');
	   if ($_POST['ak']=='Y'){$wajib='Y';}else{$wajib='N';}
        $query = mysqli_query($koneksi, "UPDATE mk SET                           
							  KurikulumID	='".strfilter($_POST['kurikulum'])."',
							  NoUrut		='".strfilter($_POST['NoUrut'])."',
							  KonsentrasiID	='".strfilter($_POST['KonsentrasiID'])."',
							  MKKode		='".strfilter($_POST['MKKode'])."',
							  Nama			='".strfilter($_POST['Nama'])."',
							  Nama_en		='".strfilter($_POST['Nama_en'])."',
							  Singkatan		='".strfilter($_POST['Singkatan'])."',
							  Wajib			='$wajib',
							  JenisMKID		='".strfilter($_POST['JenisMKID'])."',
							  JenisPilihanID='".strfilter($_POST['JenisPilihanID'])."',
							  JenisKurikulumID	='".strfilter($_POST['JenisKurikulumID'])."',
							  Sesi			='".strfilter($_POST['Sesi'])."',
							  Deskripsi		='".strfilter($_POST['Deskripsi'])."',
							  AdaResponsi	='N',
							  SKS			='".strfilter($_POST['SKS'])."',
							  SKSTatapMuka	='".strfilter($_POST['SKSTatapMuka'])."',
							  SKSPraktikum	='".strfilter($_POST['SKSPraktikum'])."',
							  SKSPraktekLap	='".strfilter($_POST['SKSPraktekLap'])."',
							  SKSMin		='".strfilter($_POST['SKSMin'])."',
							  IPKMin		='".strfilter($_POST['IPKMin'])."',
							  Penanggungjawab='".strfilter($_POST['Penanggungjawab'])."',
							  Prasyarat		='".strfilter($_POST['Prasyarat'])."',
							  MKSetara		='".strfilter($_POST['MKSetara'])."',							 
							  TglEdit		='$tglu',
							  LoginEdit		='".strfilter($_SESSION['_Login'])."',
							  NA			='".strfilter($_POST['NA'])."'
							  WHERE MKID 	='".strfilter($_POST['MKID'])."'");     
        if ($query){
          echo "<script>document.location='index.php?ndelox=master/matkul&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST['kurikulum'])."&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=master/matkul&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST['kurikulum'])."&gagal';</script>";
        }
  }

$mt = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mk where MKID='".strfilter($_GET['MKID'])."'"));

echo"
<div class='card'>
<div class='card-header'>



<form action='' method='POST' enctype='multipart/form-data' class='form-horizontal'>
<input type='hidden' name='MKID' value='$_GET[MKID]'>
<input type='hidden' name='prodi' value='$_GET[prodi]'>
<input type='hidden' name='kurikulum' value='$_GET[kurikulum]'>

<div class='row'>
    <div class='col-md-6'>
		<div class='table-responsive'>
		<table class='table table-sm table-bordered'>
		<tbody>      
		<tr><th  scope='row' colspan='2' style='background-color:#E7EAEC'>$Organisasix</th></tr>                       
		<tr><th scope='row' width='280'>Kode Matakuliah</th> <td><input type='text' class='form-control form-control-sm' name='MKKode' value='$mt[MKKode]'></td></tr>
		<tr><th scope='row'>Nama Matakuliah</th> <td><input type='text' class='form-control form-control-sm' name='Nama' value='$mt[Nama]'></td></tr>   
		<tr><th scope='row'>Nama (Inggris) </th> <td><input type='text' class='form-control form-control-sm' name='Nama_en' value='$mt[Nama_en]'></td></tr>
		<tr><th scope='row'>Singkatan</th> <td><input type='text' class='form-control form-control-sm' name='Singkatan' value='$mt[Singkatan]'></td></tr> 
		<tr><th scope='row'>Jenis</th>          
		<td><select class='form-control form-control-sm' name='JenisMKID'> 
		<option value='0' selected>- Pilih Jenis Matakuliah -</option>"; 
		$jm = mysqli_query($koneksi, "SELECT * FROM jenismk where ProdiID='".strfilter($_GET['prodi'])."'");
		while($a = mysqli_fetch_array($jm)){
			if ($mt['JenisMKID']==$a['JenisMKID']){
				echo "<option value='$a[JenisMKID]' selected>$a[Singkatan] - $a[Nama] </option>";
			}else{	
				echo "<option value='$a[JenisMKID]'>$a[Singkatan] - $a[Nama]</option>";
			}
		}
		echo "</select></td></tr>  
		<tr><th scope='row'>Pilihan Wajib</th>          
		<td><select class='form-control form-control-sm' name='JenisPilihanID'> "; 
		$jn = mysqli_query($koneksi, "SELECT * FROM jenispilihan where ProdiID='".strfilter($_GET['prodi'])."'");
		while($a = mysqli_fetch_array($jn)){
		if ($mt['JenisPilihanID']==$a['JenisPilihanID']){
			echo "<option value='$a[JenisPilihanID]' selected>$a[Singkatan] - $a[Nama] </option>";
		}else{
			echo "<option value='$a[JenisPilihanID]'>$a[Singkatan] - $a[Nama] </option>";
			}		
		}
		echo "</select></td></tr> 
		<tr><th scope='row'>Pilihan Kurikulum</th>          
		<td><select class='form-control form-control-sm' name='JenisKurikulumID'> "; 
		$status = mysqli_query($koneksi, "SELECT * FROM jeniskurikulum where ProdiID='".strfilter($_GET['prodi'])."'");
		while($a = mysqli_fetch_array($status)){
		if ($mt['JenisKurikulumID']==$a['JenisKurikulumID']){
				echo "<option value='$a[JenisKurikulumID]' selected>$a[Singkatan] - $a[Nama] </option>";
			}else{	
				echo "<option value='$a[JenisKurikulumID]'>$a[Singkatan] - $a[Nama]</option>";
			}
		}
		echo "</select></td></tr> ";

		if ($mt['Wajib']=='Y'){
			$ch ="Checked";
			}else{
			$ch ="UnChecked";	
			}
		echo"<tr><th scope='row'>Matakuliah Wajib?</th> <td><input type='checkbox' name='ak' value='Y' $ch></td></tr>
		<tr><th scope='row'>Konsentrasi</th>          
		<td><select class='form-control form-control-sm' name='KonsentrasiID'> 
		<option value=''>Pilihan Konsentrasi</option>"; 
		$kn = mysqli_query($koneksi, "SELECT * FROM konsentrasi WHERE ProdiID='".strfilter($_GET['prodi'])."'");
		while($a = mysqli_fetch_array($kn)){
		if ($mt['KonsentrasiID']==$a['KonsentrasiID']){	
			echo "<option value='$a[KonsentrasiID]' selected>$a[Nama]</option>";
		}else{
			echo "<option value='$a[KonsentrasiID]'>$a[Nama]</option>";
			}
		}
		echo "</select></td></tr>
		<tr><th scope='row'>Sesi</th> <td><input type='text' class='form-control form-control-sm' name='Sesi' value='$mt[Sesi]'></td></tr>

		</tbody>
		</table>
		</div>
	</div>	


	<div class='col-md-6'>
		<div class='table-responsive'>
		<table class='table table-sm table-sm'>
		<tbody>      
		<tr><th  scope='row' colspan='2' style='background-color:#E7EAEC'>MORE..</th></tr>
		<tr><th scope='row'>SKS</th> <td><input type='text' class='form-control form-control-sm' name='SKS' value='$mt[SKS]'></td></tr>
		<tr><th scope='row'> - SKS Tatap Muka</th> <td><input type='text' class='form-control form-control-sm' name='SKSTatapMuka' value='$mt[SKSTatapMuka]'></td></tr>
		<tr><th scope='row'> - SKS Praktikum</th> <td><input type='text' class='form-control form-control-sm' name='SKSPraktikum' value='$mt[SKSPraktikum]'></td></tr>
		<tr><th scope='row'> - SKS Praktek Lapangan</th> <td><input type='text' class='form-control form-control-sm' name='SKSPraktekLap' value='$mt[SKSPraktekLap]'></td></tr>
		<tr><th scope='row'>SKS Minimal</th> <td><input type='text' class='form-control form-control-sm' name='SKSMin' value='$mt[SKSMin]'></td></tr>
		<tr><th scope='row'>IPK Minimal </th> <td><input type='text' class='form-control form-control-sm' name='IPKMin' value='$mt[IPKMin]'></td></tr>
		<tr><th scope='row'>Penanggung Jawab</th>          
		<td><select class='form-control form-control-sm' name='Penanggungjawab'> "; 
		$ds = mysqli_query($koneksi, "SELECT * FROM dosen");
		while($a = mysqli_fetch_array($ds)){
		if ($mt['Penanggungjawab']==$a['Login']){
			echo "<option value='$a[Login]' selected>$a[Nama] </option>";
		}else{
			echo "<option value='$a[Login]'>$a[Nama] </option>";
			}		
		}
		echo "</select></td></tr>
		<tr><th scope='row'>Keterangan </th> <td><input type='text' class='form-control form-control-sm' name='Deskripsi' value='$mt[Deskripsi]'></td></tr>
		<tr><th scope='row'>Aktif</th>                <td>";
			if ($mt['NA']=='Y'){
				echo "<input type='radio' name='NA' value='N'> Ya
				<input type='radio' name='NA' value='Y' checked> Tidak";
			}else{
				echo "<input type='radio' name='NA' value='N' checked> Ya 
				<input type='radio' name='NA' value='Y'> Tidak";
			}
		echo "</td>
		</tr> 
		</tbody>
		</table>
		</div>
	</div>	
</div>	

<div class='box-footer'>
<button type='submit' name='update1' class='btn btn-info'>Update</button>
<a href='index.php?ndelox=master/matkul&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
</div> 

</form>
";
  

}elseif($_GET['act']=='tambahmatkul'){
   if (isset($_POST['tambah'])){     
      $tglb = date('Y-m-d H:i:s');
	      if ($_POST['ak']=='Y'){$wajib='Y';}else{$wajib='N';}
          $query = mysqli_query($koneksi, "INSERT INTO mk
		  						 (KurikulumID,
								  NoUrut,
								  KodeID,
								  ProdiID,
								  KonsentrasiID,
								  MKKode,
								  Nama,
								  Nama_en,
								  Singkatan,
								  Wajib,
								  JenisMKID,
								  JenisPilihanID,
								  JenisKurikulumID,
								  Sesi,
								  Deskripsi,
								  AdaResponsi,
								  SKS,
								  SKSTatapMuka,
								  SKSPraktikum,
								  SKSPraktekLap,
								  SKSMin,
								  IPKMin,
								  Penanggungjawab,
								  Prasyarat,
								  MKSetara,
								  TglBuat,
								  LoginBuat,
								  NA)
						   VALUES('".strfilter($_POST['kurikulum'])."',
								  '".strfilter($_POST['NoUrut'])."',
								  'SISFO',
								  '".strfilter($_POST['prodi'])."',
								  '".strfilter($_POST['KonsentrasiID'])."',
								  '".strfilter($_POST['MKKode'])."',
								  '".strfilter($_POST['Nama'])."',
								  '".strfilter($_POST['Nama_en'])."',
								  '".strfilter($_POST['Singkatan'])."',
								  '$wajib',
								  '".strfilter($_POST['JenisMKID'])."',
								  '".strfilter($_POST['JenisPilihanID'])."',
								  '".strfilter($_POST['JenisKurikulumID'])."',
								  '".strfilter($_POST['Sesi'])."',
								  '".strfilter($_POST['Deskripsi'])."',
								  'N',
								  '".strfilter($_POST['SKS'])."',
								  '".strfilter($_POST['SKSTatapMuka'])."',
								  '".strfilter($_POST['SKSPraktikum'])."',
								  '".strfilter($_POST['SKSPraktekLap'])."',
								  '".strfilter($_POST['SKSMin'])."',
								  '".strfilter($_POST['IPKMin'])."',
								  '".strfilter($_POST['Penanggungjawab'])."',
								  '".strfilter($_POST['Prasyarat'])."',
								  '".strfilter($_POST['MKSetara'])."',
								  '$tglb',
								  '$_SESSION[_Login]',
								  'N')");
        if ($query){
          echo "<script>document.location='index.php?ndelox=master/matkul&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST['kurikulum'])."&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=master/matkul&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST['kurikulum'])."&gagal';</script>";
        }
  }
	  

echo"<div class='card'>
<div class='card-header'>
<form action='' method='POST' enctype='multipart/form-data' class='form-horizontal'>
<input type='hidden' name='prodi' value='$_GET[prodi]'>
<input type='hidden' name='kurikulum' value='$_GET[kurikulum]'>
  	<div class='row'>	
		<div class='col-md-6'>
			<div class='table-responsive'>
			<table class='table table-sm table-sm'>
			<tbody>      
			<tr><th  scope='row' colspan='2' style='background-color:#E7EAEC'>$Organisasix</th></tr>                       
			<tr><th scope='row'>Kode Matakuliah</th> <td><input type='text' class='form-control form-control-sm' name='MKKode' ></td></tr>
			<tr><th scope='row'>Nama Matakuliah</th> <td><input type='text' class='form-control form-control-sm' name='Nama' ></td></tr>   
			<tr><th scope='row'>Nama (Inggris) </th> <td><input type='text' class='form-control form-control-sm' name='Nama_en' ></td></tr>
			<tr><th scope='row'>Singkatan</th> <td><input type='text' class='form-control form-control-sm' name='Singkatan' ></td></tr> 
			<tr><th scope='row'>Jenis</th>          
			<td><select class='form-control form-control-sm' name='JenisMKID'> 
			<option value='0' selected>- Pilih Jenis Matakuliah -</option>"; 
			$status = mysqli_query($koneksi, "SELECT * FROM jenismk where ProdiID='".strfilter($_GET['prodi'])."'");
			while($a = mysqli_fetch_array($status)){
				echo "<option value='$a[JenisMKID]'>$a[Singkatan] - $a[Nama]</option>";
			}
			echo "</select></td></tr>  
			<tr><th scope='row'>Pilihan Wajib</th>          
			<td><select class='form-control form-control-sm' name='JenisPilihanID'> "; 
			$jn = mysqli_query($koneksi, "SELECT * FROM jenispilihan where ProdiID='".strfilter($_GET['prodi'])."'");
			while($a = mysqli_fetch_array($jn)){
				echo "<option value='$a[JenisPilihanID]'>$a[Singkatan] - $a[Nama] </option>";		
			}
			echo "</select></td></tr> 
			<tr><th scope='row'>Pilihan Kurikulum</th>          
			<td><select class='form-control form-control-sm' name='JenisKurikulumID'> "; 
			$status = mysqli_query($koneksi, "SELECT * FROM jeniskurikulum where ProdiID='".strfilter($_GET['prodi'])."'");
			while($a = mysqli_fetch_array($status)){
				echo "<option value='$a[JenisKurikulumID]'>$a[Singkatan] - $a[Nama]</option>";
			}
			echo "</select></td></tr> 



			<tr><th scope='row'>Matakuliah Wajib?</th> <td><input type='checkbox' name='ak' value='Y'></td></tr>
			<tr><th scope='row'>Konsentrasi</th>          
			<td><select class='form-control form-control-sm' name='KonsentrasiID'> "; 
			$status = mysqli_query($koneksi, "SELECT * FROM konsentrasi where ProdiID='".strfilter($_GET['prodi'])."' order by Nama asc");
			while($a = mysqli_fetch_array($status)){
				echo "<option value='$a[KonsentrasiID]'> $a[KonsentrasiKode] - $a[Nama]</option>";
			}
			echo "</select></td></tr>
			<tr><th scope='row'>Sesi</th> <td><input type='text' class='form-control form-control-sm' name='Sesi' ></td></tr>

			</tbody>
			</table>
			</div>
		</div>	

		<div class='col-md-6'>
			<div class='table-responsive'>
			<table class='table table-sm table-sm'>
			<tbody>      
			<tr><th  scope='row' colspan='2' style='background-color:#E7EAEC'>MORE..</th></tr>
			<tr><th scope='row'>SKS</th> <td><input type='text' class='form-control form-control-sm' name='SKS' ></td></tr>
			<tr><th scope='row'> - SKS Tatap Muka</th> <td><input type='text' class='form-control form-control-sm' name='SKSTatapMuka' ></td></tr>
			<tr><th scope='row'> - SKS Praktikum</th> <td><input type='text' class='form-control form-control-sm' name='SKSPraktikum' ></td></tr>
			<tr><th scope='row'> - SKS Praktek Lapangan</th> <td><input type='text' class='form-control form-control-sm' name='SKSPraktekLap' ></td></tr>
			<tr><th scope='row'>SKS Minimal</th> <td><input type='text' class='form-control form-control-sm' name='SKSMin' ></td></tr>
			<tr><th scope='row'>IPK Minimal </th> <td><input type='text' class='form-control form-control-sm' name='IPKMin' ></td></tr>
			<tr><th scope='row'>Penanggung Jawab</th>          
			<td><select class='form-control form-control-sm' name='Penanggungjawab'> "; 
			$ds = mysqli_query($koneksi, "SELECT * FROM dosen");
			while($a = mysqli_fetch_array($ds)){
				echo "<option value='$a[Login]'>$a[Nama]</option>";
			}
			echo "</select></td></tr>
			<tr><th scope='row'>Keterangan </th> <td><input type='text' class='form-control form-control-sm' name='Deskripsi' ></td></tr>
			<tr><th scope='row'>Aktif</th>                <td>";
				if ($mt['NA']=='Y'){
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
	</div>					  



	<div class='box-footer'>
	<button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
	<a href='index.php?ndelox=master/matkul&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
	</div>
</div>
</div>	
</form> ";
}

elseif($_GET['act']=='mksetara'){
echo"
<div class='card'>
<div class='card-header'>
<h3 class='card-title'><b style='color:purple'>Matakuliah Setara</b></h3>
<div class='table-responsive'>    
<table id='example' class='table table-sm table-striped'>
	<thead>
	<tr style='background:purple;color:white'>
	<th style='width:40px'>No</th>
	<th>KodeMK</th>
	<th>Nama</th>                              
	<th>SKS</th>
	<th>Setara</th>
	<th>Aksi</th>
	</tr>
	</thead>
	<tbody>";

	$no = 1;
	$sql =mysqli_query($koneksi, "select * from mk WHERE KurikulumID='".strfilter($_GET['kurikulum'])."' order by MKKode ASC");
	while($r=mysqli_fetch_array($sql)){  
		$MKSetara = TRIM($r['MKSetara'], '.');
		$MKSetara = str_replace('.', ', ', $MKSetara);	
	echo "<tr>
			<td>$no</td>                 
			<td>$r[MKKode]</td>
			<td>$r[Nama]</td>			
			<td>$r[SKS]</td>
			<td>$MKSetara</td>
			<td style='width:70px;text-align:left'><a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=master/matkul&act=editmksetara&kurikulum=$r[KurikulumID]&prodi=$r[ProdiID]'><i class='fa fa-edit'></i></a>
		
			</td>
		  </tr>";
	$no++;
	}
echo"<tbody>
</table>
</div>
</div>
</div>";
 }

 elseif($_GET['act']=='datakur'){
echo"
<div class='card'>
<div class='card-header'>
<h3 class='card-title'><b style='color:purple'>Data Kurikulum</b></h3>
<div class='table-responsive'>  
<table id='example' class='table table-sm table-striped'>
	<thead>
	<tr style='background:purple;color:white'>
	<th style='width:40px'>No</th>
	<th>Kode</th>
	<th>Nama</th>                              
	<th>Sesi</th>
	<th>Jml/Tahun</th>
	<th>NA</th>
	<th>Aksi</th>
	</tr>
	</thead>
	<tbody>";

	$no = 1;
	$sql =mysqli_query($koneksi, "select * from kurikulum WHERE ProdiID='".strfilter($_GET['prodi'])."' order by KurikulumKode DESC");
	while($r=mysqli_fetch_array($sql)){  
		if ($r['NA']=='N'){
			$aktif="Ya";
			$c="style=color:green;font-weight:bold";
		  } else{
			$aktif="Tidak";
			  $c="style=color:black";
		  }
	echo "<tr $c>
			<td>$no</td>                 
			<td>$r[KurikulumKode]</td>
			<td>$r[Nama]</td>			
			<td>$r[Sesi]</td>
			<td>$r[JmlSesi]</td>
			<td>$aktif</td>
			<td style='width:70px;text-align:left'><a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=master/matkul&act=editkur&kurikulum=$r[KurikulumID]&prodi=$r[ProdiID]'><i class='fa fa-edit'></i></a>
		
			</td>
			</tr>";
	$no++;
	}
echo"<tbody>
</table>
</div>
</div>
</div>";
}

elseif($_GET['act']=='editmksetara'){
	if (isset($_POST['update1'])){
	  $tglu = date('Y-m-d H:i:s');
	  if ($_POST['NA']=='Y'){$NA='N';}else{$NA='Y';}
	   $query = mysqli_query($koneksi, "UPDATE mkxxxxxxxx SET                           
							 KurikulumKode	='".strfilter($_POST['KurikulumKode'])."',
							 Nama			='".strfilter($_POST['Nama'])."',
							 JmlSesi		='".strfilter($_POST['JmlSesi'])."',
							 NA				='$NA'
							 WHERE KurikulumIDxx ='".strfilter($_POST['kurikulum'])."'");     
	   if ($query){
		 echo "<script>document.location='index.php?ndelox=master/matkul&act=datakur&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST['kurikulum'])."&sukses';</script>";
	   }else{
		 echo "<script>document.location='index.php?ndelox=master/matkul&&act=datakur&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST['kurikulum'])."&gagal';</script>";
	   }
 }

$mt = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM kurikulum where KurikulumID='".strfilter($_GET['kurikulum'])."'"));

echo"
<form action='' method='POST' enctype='multipart/form-data' class='form-horizontal'>
<input type='hidden' name='prodi' value='$_GET[prodi]'>
<input type='hidden' name='kurikulum' value='$_GET[kurikulum]'>

<div class='card'>
<div class='card-header'>
<h4><b style='color:purple'>MATAKULIAH SETARA</b></h4>
<div class='table-responsive'>  
<table class='table table-sm table-sm'>
<tbody>                         
<tr>
<th width='120px'>Tambah MK</th>
<th><select name='MKID'> 
<option value='0' selected>- Pilih Mata Kuliah -</option>"; 
$mk = mysqli_query($koneksi, "SELECT MKID,MKKode,Nama,SKS,NA FROM mk where ProdiID='".strfilter($_GET[prodi])."' AND NA='N' and KurikulumID='".strfilter($_GET[kurikulum])."' order by  Nama ASC");
while($a = mysqli_fetch_array($mk)){
	echo "<option value='$a[MKID]'>$a[Nama] - [ $a[MKKode] ]</option>";
  }
echo "</select></th>
<th>
<input type='submit' name='submit' value='Tambah'>
</th>
<th>
<a href='index.php?ndelox=master/matkul&act=mksetara&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'><button type='button'>Cancel</button></a>
</th>
</tr>
</table>
</form>
</div></div></div>";
}


elseif($_GET['act']=='editkur'){
	if (isset($_POST['update1'])){
	  $tglu = date('Y-m-d H:i:s');
	  if ($_POST['NA']=='Y'){$NA='N';}else{$NA='Y';}
	   $query = mysqli_query($koneksi, "UPDATE kurikulum SET                           
							 KurikulumKode	='".strfilter($_POST['KurikulumKode'])."',
							 Nama			='".strfilter($_POST['Nama'])."',
							 JmlSesi		='".strfilter($_POST['JmlSesi'])."',
							 LoginEdit		='".strfilter($_SESSION['_Login'])."',
							 NA				='$NA'
							 WHERE KurikulumID ='".strfilter($_POST['kurikulum'])."'");     
	   if ($query){
		 echo "<script>document.location='index.php?ndelox=master/matkul&act=datakur&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST[kurikulum])."&sukses';</script>";
	   }else{
		 echo "<script>document.location='index.php?ndelox=master/matkul&&act=datakur&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST[kurikulum])."&gagal';</script>";
	   }
 }

$mt = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM kurikulum where KurikulumID='".strfilter($_GET['kurikulum'])."'"));

echo"
<form action='' method='POST' enctype='multipart/form-data' class='form-horizontal'>
<input type='hidden' name='prodi' value='$_GET[prodi]'>
<input type='hidden' name='kurikulum' value='$_GET[kurikulum]'>


<div class='card'>
<div class='card-header'>
<h4><b style='color:purple'>EDIT KURIKULUM</b></h4>
<div class='table-responsive'>  
<table class='table table-sm table-sm'>
<tbody>                         
<tr><th scope='row'>Kode Kurikulum</th> <td><input type='text' class='form-control form-control-sm' name='KurikulumKode' value='$mt[KurikulumKode]'></td></tr>
<tr><th scope='row'>Nama Kurikulum</th> <td><input type='text' class='form-control form-control-sm' name='Nama' value='$mt[Nama]'></td></tr>   
<tr><th scope='row'>Jml Sesi </th> <td><input type='text' class='form-control form-control-sm' name='JmlSesi' value='$mt[JmlSesi]'></td></tr>";
if ($mt['NA']=='N'){
	$ch ="Checked";
}else{
	$ch ="UnChecked";	
}
echo"<tr><th scope='row'>Aktif?</th> <td><input type='checkbox' name='NA' value='Y' $ch></td></tr>
<tr><th colspan='2'>
<div class='box-footer'>
<button type='submit' name='update1' class='btn btn-info'>Update</button>
<a href='index.php?ndelox=master/matkul&act=datakur&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
</div> 
</th>
</tr>
</table>
</form>
</div></div></div>";
}

elseif($_GET['act']=='jenismk'){
echo"
<div class='card'>
<div class='card-header'>
<h3 class='card-title'><b style='color:purple'>Data Jenis Matakuliah</b></h3>
<div class='table-responsive'>  
<table id='example' class='table table-sm table-striped'>
	<thead>
	<tr style='background:purple;color:white'>
	<th style='width:40px'>No</th>
	<th>Jenis MK</th>
	<th>Nama</th>                              
	<th>Prodi</th>
	<th>Aktif</th>
	<th>Aksi</th>
	</tr>
	</thead>
	<tbody>";

	$no = 1;
	$sql =mysqli_query($koneksi, "select * from jenismk WHERE ProdiID='".strfilter($_GET['prodi'])."' order by JenisMKID DESC");
	while($r=mysqli_fetch_array($sql)){  
	if ($r['NA']=='N'){
		$aktif="Ya";
		$c="style=color:green;font-weight:bold";
		} else{
		$aktif="Tidak";
			$c="style=color:black";
		}
	echo "<tr>
			<td>$no</td>                 
			<td>$r[Singkatan]</td>
			<td>$r[Nama]</td>			
			<td>$r[ProdiID]</td>
			<td>$aktif</td>
			<td style='width:70px;text-align:left'><a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=master/matkul&act=editjenismk&JenisMKID=$r[JenisMKID]&prodi=$r[ProdiID]&kurikulum=$_GET[kurikulum]'><i class='fa fa-edit'></i></a>
		
			</td>
			</tr>";
	$no++;
	}
echo"<tbody>
</table>
</div>
</div>
</div>";
}

elseif($_GET['act']=='editjenismk'){
	if (isset($_POST['update1'])){
	  $tglu = date('Y-m-d H:i:s');
	  if ($_POST['NA']=='Y'){$NA='N';}else{$NA='Y';}
	   $query = mysqli_query($koneksi, "UPDATE jenismk SET                           
							 Singkatan	='".strfilter($_POST['Singkatan'])."',
							 Nama			='".strfilter($_POST['Nama'])."',
							 Urutan		='".strfilter($_POST['Urutan'])."',
							 NA				='$NA'
							 WHERE JenisMKID ='".strfilter($_POST['JenisMKID'])."'");     
	   if ($query){
		 echo "<script>document.location='index.php?ndelox=master/matkul&act=jenismk&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST['kurikulum'])."&sukses';</script>";
	   }else{
		 echo "<script>document.location='index.php?ndelox=master/matkul&&act=jenismk&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST['kurikulum'])."&gagal';</script>";
	   }
 }

$mt = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jenismk where JenisMKID='".strfilter($_GET['JenisMKID'])."'"));

echo"
<form action='' method='POST' enctype='multipart/form-data' class='form-horizontal'>
<input type='hidden' name='JenisMKID' value='$_GET[JenisMKID]'>
<input type='hidden' name='prodi' value='$_GET[prodi]'>
<input type='hidden' name='kurikulum' value='$_GET[kurikulum]'>


<div class='card'>
<div class='card-header'>
<h4><b style='color:purple'>EDIT JENIS MATAKULIAH</b></h4>
<div class='table-responsive'>  
<table class='table table-sm table-sm'>
<tbody>                         
<tr><th scope='row'>Singkatan</th> <td><input type='text' class='form-control form-control-sm' name='Singkatan' value='$mt[Singkatan]'></td></tr>
<tr><th scope='row'>Nama </th> <td><input type='text' class='form-control form-control-sm' name='Nama' value='$mt[Nama]'></td></tr>   
<tr><th scope='row'>Urutan </th> <td><input type='text' class='form-control form-control-sm' name='Urutan' value='$mt[Urutan]'></td></tr>";
if ($mt['NA']=='N'){
	$ch ="Checked";
}else{
	$ch ="UnChecked";	
}
echo"<tr><th scope='row'>Aktif?</th> <td><input type='checkbox' name='NA' value='Y' $ch></td></tr>
<tr><th colspan='2'>
<div class='box-footer'>
<button type='submit' name='update1' class='btn btn-info'>Update</button>
<a href='index.php?ndelox=master/matkul&act=jenismk&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
</div> 
</th>
</tr>
</table>
</form>
</div></div></div>";
}

elseif($_GET['act']=='pilihanwajib'){
echo"
<div class='card'>
<div class='card-header'>
<h3 class='card-title'><b style='color:purple'>Pilihan Wajib</b></h3>
<div class='table-responsive'>  
<table id='example' class='table table-sm table-striped'>
	<thead>
	<tr style='background:purple;color:white'>
	<th style='width:40px'>No</th>
	<th>Singkatan</th>
	<th>Nama</th>                              
	
	<th>Prodi</th>
	<th>Aktif</th>
	<th>Aksi</th>
	</tr>
	</thead>
	<tbody>";

	$no = 1;
	$sql =mysqli_query($koneksi, "select * from jenispilihan WHERE ProdiID='".strfilter($_GET['prodi'])."' order by JenisPilihanID DESC");
	while($r=mysqli_fetch_array($sql)){  
	echo "<tr>
			<td>$no</td>                 
			<td>$r[Singkatan]</td>
			<td>$r[Nama]</td>			
			<td>$r[ProdiID]</td>
			<td>$r[NA]</td>
			<td style='width:70px;text-align:left'><a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=master/matkul&act=editpilihanwajib&JenisPilihanID=$r[JenisPilihanID]&prodi=$r[ProdiID]&kurikulum=$_GET[kurikulum]'><i class='fa fa-edit'></i></a>
		
			</td>
			</tr>";
	$no++;
	}
echo"<tbody>
</table>
</div>
</div>
</div>";
}

elseif($_GET['act']=='editpilihanwajib'){
	if (isset($_POST['update1'])){
	  $tglu = date('Y-m-d H:i:s');
	  if ($_POST['NA']=='Y'){$NA='N';}else{$NA='Y';}
	   $query = mysqli_query($koneksi, "UPDATE jenispilihan SET                           
							 Singkatan	='".strfilter($_POST['Singkatan'])."',
							 Nama			='".strfilter($_POST['Nama'])."',
							 NA				='$NA'
							 WHERE JenisPilihanID ='".strfilter($_POST['JenisPilihanID'])."'");     
	   if ($query){
		 echo "<script>document.location='index.php?ndelox=master/matkul&act=pilihanwajib&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST['kurikulum'])."&sukses';</script>";
	   }else{
		 echo "<script>document.location='index.php?ndelox=master/matkul&&act=pilihanwajib&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST['kurikulum'])."&gagal';</script>";
	   }
 }

$mt = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jenispilihan where JenisPilihanID='".strfilter($_GET['JenisPilihanID'])."'"));

echo"
<form action='' method='POST' enctype='multipart/form-data' class='form-horizontal'>
<input type='hidden' name='JenisPilihanID' value='$_GET[JenisPilihanID]'>
<input type='hidden' name='prodi' value='$_GET[prodi]'>
<input type='hidden' name='kurikulum' value='$_GET[kurikulum]'>


<div class='card'>
<div class='card-header'>
<h4><b style='color:purple'>EDIT PILIHAN WAJIB</b></h4>
<div class='table-responsive'>  
<table class='table table-sm table-sm'>
<tbody>                         
<tr><th scope='row'>Singkatan</th> <td><input type='text' class='form-control form-control-sm' name='Singkatan' value='$mt[Singkatan]'></td></tr>
<tr><th scope='row'>Nama </th> <td><input type='text' class='form-control form-control-sm' name='Nama' value='$mt[Nama]'></td></tr>   ";
if ($mt['NA']=='N'){
	$ch ="Checked";
}else{
	$ch ="UnChecked";	
}
echo"<tr><th scope='row'>Aktif?</th> <td><input type='checkbox' name='NA' value='Y' $ch></td></tr>
<tr><th colspan='2'>
<div class='box-footer'>
<button type='submit' name='update1' class='btn btn-info'>Update</button>
<a href='index.php?ndelox=master/matkul&act=pilihanwajib&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
</div> 
</th>
</tr>
</table>
</form>
</div></div></div>";
}

elseif($_GET['act']=='jeniskur'){
echo"
<div class='card'>
<div class='card-header'>
<h3 class='card-title'><b style='color:purple'>Jenis Kurikulum</b></h3>
<div class='table-responsive'>  
<table id='example' class='table table-sm table-striped'>
	<thead>
	<tr style='background:purple;color:white'>
	<th style='width:40px'>No</th>
	<th>Singkatan</th>
	<th>Nama</th>                                                            
	<th>Prodi</th>
	<th>Aktif</th>
	<th>Aksi</th>
	</tr>
	</thead>
	<tbody>";
	$no = 1;
	$sql =mysqli_query($koneksi, "select * from jeniskurikulum WHERE ProdiID='".strfilter($_GET['prodi'])."' order by JenisKurikulumID DESC");
	while($r=mysqli_fetch_array($sql)){  
	echo "<tr>
			<td>$no</td>                 
			<td>$r[Singkatan]</td>
			<td>$r[Nama]</td>			
			<td>$r[ProdiID]</td>
			<td>$r[NA]</td>
			<td style='width:70px;text-align:left'><a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=master/matkul&act=editjeniskur&JenisKurikulumID=$r[JenisKurikulumID]&prodi=$r[ProdiID]&kurikulum=$_GET[kurikulum]'><i class='fa fa-edit'></i></a>
		
			</td>
			</tr>";
	$no++;
	}
echo"<tbody>
</table>
</div>
</div>
</div>";
}
	

elseif($_GET['act']=='editjeniskur'){
	if (isset($_POST['update1'])){
	  $tglu = date('Y-m-d H:i:s');
	  if ($_POST['NA']=='Y'){$NA='N';}else{$NA='Y';}
	   $query = mysqli_query($koneksi, "UPDATE jeniskurikulum SET                           
							 Singkatan		='".strfilter($_POST['Singkatan'])."',
							 Nama			='".strfilter($_POST['Nama'])."',
							 NA				='$NA'
							 WHERE JenisKurikulumID ='".strfilter($_POST['JenisKurikulumID'])."'");     
	   if ($query){
		 echo "<script>document.location='index.php?ndelox=master/matkul&act=jeniskur&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST['kurikulum'])."&sukses';</script>";
	   }else{
		 echo "<script>document.location='index.php?ndelox=master/matkul&&act=jeniskur&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST['kurikulum'])."&gagal';</script>";
	   }
 }

$mt = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jeniskurikulum where JenisKurikulumID='".strfilter($_GET['JenisKurikulumID'])."'"));

echo"
<form action='' method='POST' enctype='multipart/form-data' class='form-horizontal'>
<input type='hidden' name='JenisKurikulumID' value='$_GET[JenisKurikulumID]'>
<input type='hidden' name='prodi' value='$_GET[prodi]'>
<input type='hidden' name='kurikulum' value='$_GET[kurikulum]'>


<div class='card'>
<div class='card-header'>
<h4><b style='color:purple'>EDIT JENIS KURIKULUM</b></h4>
<div class='table-responsive'>  
<table class='table table-sm table-sm'>
<tbody>                         
<tr><th scope='row'>Singkatan</th> <td><input type='text' class='form-control form-control-sm' name='Singkatan' value='$mt[Singkatan]'></td></tr>
<tr><th scope='row'>Nama </th> <td><input type='text' class='form-control form-control-sm' name='Nama' value='$mt[Nama]'></td></tr>   ";
if ($mt['NA']=='N'){
	$ch ="Checked";
}else{
	$ch ="UnChecked";	
}
echo"<tr><th scope='row'>Aktif?</th> <td><input type='checkbox' name='NA' value='Y' $ch></td></tr>
<tr><th colspan='2'>
<div class='box-footer'>
<button type='submit' name='update1' class='btn btn-info'>Update</button>
<a href='index.php?ndelox=master/matkul&act=jeniskur&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
</div> 
</th>
</tr>
</table>
</form>
</div></div></div>";
}

elseif($_GET['act']=='nilai'){
echo"
<div class='card'>
<div class='card-header'>
<h3 class='card-title'><b style='color:purple'>Nilai</b></h3>
<div class='table-responsive'>  
<table id='example' class='table table-sm table-striped'>
	<thead>
	<tr style='background:purple;color:white'>
	<th style='width:40px'>No</th>
	<th style='text-align:center;width:60px'>Nilai</th>
	<th style='text-align:right;width:60px'>Bobot</th>                                                             
	<th style='text-align:center;width:60px'>Lulus</th>
	<th style='text-align:right;width:120px'>Batas Bawah</th>
	<th style='text-align:right;width:120px'>Batas Atas</th>
	<th style='text-align:center;width:100px'>Max SKS</th>
	<th style='text-align:center;width:150px'>Hitung dlm IPK</th>
	<th style='text-align:left;width:200px'>Deskripsi</th>
	<th style='text-align:center;width:30px'>&nbsp;</th>
	</tr>
	</thead>
	<tbody>";
	$no = 1;
	$sql =mysqli_query($koneksi, "select * from nilai WHERE ProdiID='".strfilter($_GET['prodi'])."' order by Bobot DESC");
	while($row=mysqli_fetch_array($sql)){  
	echo "<tr>
			<td>$no</td>                 
			<td style='text-align:center;'>$row[Nama]</td>
			<td style='text-align:right;'>$row[Bobot]</td>                                     
			<td style='text-align:center;'>$row[Lulus]</td>
			<td style='text-align:right;'>$row[NilaiMin]</td>
			<td style='text-align:right;'>$row[NilaiMax]</td>
			<td style='text-align:center;'>$row[MaxSKS]</td>
			<td style='text-align:center;'>$row[HitungIPK]</td>
			<td>$row[Deskripsi]</td>
			<td style='width:70px;text-align:left'><a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=master/matkul&act=editnilai&NilaiID=$row[NilaiID]&prodi=$row[ProdiID]&kurikulum=$_GET[kurikulum]'><i class='fa fa-edit'></i></a>
			
			</td>
			</tr>";
	$no++;
	}
echo"<tbody>
</table>
</div>
</div>
</div>";
}

elseif($_GET['act']=='editnilai'){
	if (isset($_POST['update1'])){
	  $tglu = date('Y-m-d H:i:s');
	  if ($_POST['NA']=='Y'){$NA='N';}else{$NA='Y';}
	  if ($_POST['HitungIPK']=='Y'){$HitungIPK='Y';}else{$HitungIPK='N';}
	  if ($_POST['Lulus']=='Y'){$Lulus='Y';}else{$Lulus='N';}
	   $query = mysqli_query($koneksi, "UPDATE nilai SET                           
							 Nama			='".strfilter($_POST['Nama'])."',
							 Bobot			='".strfilter($_POST['Bobot'])."',
							 Lulus			='$Lulus',
							 NilaiMin		='".strfilter($_POST['NilaiMin'])."',
							 NilaiMax		='".strfilter($_POST['NilaiMax'])."',
							 MaxSKS			='".strfilter($_POST['MaxSKS'])."',
							 Deskripsi		='".strfilter($_POST['Deskripsi'])."',
							 HitungIPK		='$HitungIPK',
							 LoginEdit		='".strfilter($_SESSION['_Login'])."',
							 NA				='$NA'
							 WHERE NilaiID  ='".strfilter($_POST['NilaiID'])."'");     
	   if ($query){
		 echo "<script>document.location='index.php?ndelox=master/matkul&act=nilai&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST['kurikulum'])."&sukses';</script>";
	   }else{
		 echo "<script>document.location='index.php?ndelox=master/matkul&&act=nilai&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST['kurikulum'])."&gagal';</script>";
	   }
 }

$mt = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai where NilaiID='".strfilter($_GET['NilaiID'])."'"));

echo"
<form action='' method='POST' enctype='multipart/form-data' class='form-horizontal'>
<input type='hidden' name='NilaiID' value='$_GET[NilaiID]'>
<input type='hidden' name='prodi' value='$_GET[prodi]'>
<input type='hidden' name='kurikulum' value='$_GET[kurikulum]'>


<div class='card'>
<div class='card-header'>
<h4><b style='color:purple'>EDIT NILAI</b></h4>
<div class='table-responsive'>  
<table class='table table-sm table-sm'>
<tbody>                         
<tr><th scope='row'>Nilai</th> <td><input type='text' class='form-control form-control-sm' name='Nama' value='$mt[Nama]'></td></tr>
<tr><th scope='row'>Bobot </th> <td><input type='text' class='form-control form-control-sm' name='Bobot' value='$mt[Bobot]'></td></tr>   
<tr><th scope='row'>Batas Bawah</th> <td><input type='text' class='form-control form-control-sm' name='NilaiMin' value='$mt[NilaiMin]'></td></tr>
<tr><th scope='row'>Batas Atas</th> <td><input type='text' class='form-control form-control-sm' name='NilaiMax' value='$mt[NilaiMax]'></td></tr>
<tr><th scope='row'>Max Pengambilan SKS</th> <td><input type='text' class='form-control form-control-sm' name='MaxSKS' value='$mt[MaxSKS]'></td></tr>
<tr><th scope='row'>Deskripsi</th> <td><input type='text' class='form-control form-control-sm' name='Deskripsi' value='$mt[Deskripsi]'></td></tr>";
if ($mt['NA']=='N'){
	$ch ="Checked";
}else{
	$ch ="UnChecked";	
}

if ($mt['HitungIPK']=='Y'){
	$chx ="Checked";
}else{
	$chx ="UnChecked";	
}

if ($mt['Lulus']=='Y'){
	$chxx ="Checked";
}else{
	$chxx ="UnChecked";	
}
echo"<tr><th scope='row'>Aktif?</th> <td><input type='checkbox' name='NA' value='Y' $ch></td></tr>
<tr><th scope='row'>Hitung IPK?</th> <td><input type='checkbox' name='HitungIPK' value='Y' $chx></td></tr>
<tr><th scope='row'>Lulus?</th> <td><input type='checkbox' name='Lulus' value='Y' $chxx></td></tr>
<tr><th colspan='2'>
<div class='box-footer'>
<button type='submit' name='update1' class='btn btn-info'>Update</button>
<a href='index.php?ndelox=master/matkul&act=nilai&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
</div> 
</th>
</tr>
</table>
</form>
</div></div></div>";
}

elseif($_GET['act']=='maxsks'){
echo"
<div class='card'>
<div class='card-header'>
<h3 class='card-title'><b style='color:purple'>Max SKS</b></h3>
<div class='table-responsive'>  
<table id='example' class='table table-sm table-striped'>
	<thead>
	<tr style='background:purple;color:white'>
	<th style='width:40px'>No</th>
	<th>Dari IPS</th>
	<th>Sampai IPS</th>                              
	<th>Max SKS</th>
	<th>Aksi</th>
	</tr>
	</thead>
	<tbody>";
	$no = 1;
	$sql =mysqli_query($koneksi, "select * from maxsks WHERE ProdiID='".strfilter($_GET['prodi'])."' order by SampaiIP DESC");
	while($row=mysqli_fetch_array($sql)){  
	echo "<tr>
			<td>$no</td>                 
			<td>$row[DariIP]</td>
			<td>$row[SampaiIP]</td>
			<td>$row[SKS]</td>
			<td style='width:70px;text-align:left'><a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=master/matkul&act=editmaxsks&MaxSKSID=$row[MaxSKSID]&prodi=$row[ProdiID]&kurikulum=$_GET[kurikulum]'><i class='fa fa-edit'></i></a>
			
			</td>
			</tr>";
	$no++;
	}
echo"<tbody>
</table>
</div>
</div>
</div>";
}

elseif($_GET['act']=='editmaxsks'){
	if (isset($_POST['update1'])){
	  $tglu = date('Y-m-d H:i:s');
	  if ($_POST['NA']=='Y'){$NA='N';}else{$NA='Y';}
	   $query = mysqli_query($koneksi, "UPDATE maxsks SET                           
							 DariIP		='".strfilter($_POST['DariIP'])."',
							 SampaiIP			='".strfilter($_POST['SampaiIP'])."',
							 SKS			='".strfilter($_POST['SKS'])."',
							 LoginEdit		='".strfilter($_SESSION['_Login'])."',
							 NA				='$NA'
							 WHERE MaxSKSID ='".strfilter($_POST['MaxSKSID'])."'");     
	   if ($query){
		 echo "<script>document.location='index.php?ndelox=master/matkul&act=maxsks&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST['kurikulum'])."&sukses';</script>";
	   }else{
		 echo "<script>document.location='index.php?ndelox=master/matkul&&act=maxsks&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST['kurikulum'])."&gagal';</script>";
	   }
 	}

$mt = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM maxsks where MaxSKSID='".strfilter($_GET['MaxSKSID'])."'"));

echo"
<form action='' method='POST' enctype='multipart/form-data' class='form-horizontal'>
<input type='hidden' name='MaxSKSID' value='$_GET[MaxSKSID]'>
<input type='hidden' name='prodi' value='$_GET[prodi]'>
<input type='hidden' name='kurikulum' value='$_GET[kurikulum]'>


<div class='card'>
<div class='card-header'>
<h4><b style='color:purple'>EDIT Max SKS</b></h4>
<div class='table-responsive'>  
<table class='table table-sm table-sm'>
<tbody>                         
<tr><th scope='row'>Dari IP</th> <td><input type='text' class='form-control form-control-sm' name='DariIP' value='$mt[DariIP]'></td></tr>
<tr><th scope='row'>Sampai IP </th> <td><input type='text' class='form-control form-control-sm' name='SampaiIP' value='$mt[SampaiIP]'></td></tr>
<tr><th scope='row'>Max SKS </th> <td><input type='text' class='form-control form-control-sm' name='SKS' value='$mt[SKS]'></td></tr>
";
if ($mt['NA']=='N'){
	$ch ="Checked";
}else{
	$ch ="UnChecked";	
}
echo"<tr><th scope='row'>Aktif?</th> <td><input type='checkbox' name='NA' value='Y' $ch></td></tr>
<tr><th colspan='2'>
<div class='box-footer'>
<button type='submit' name='update1' class='btn btn-info'>Update</button>
<a href='index.php?ndelox=master/matkul&act=maxsks&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
</div> 
</th>
</tr>
</table>
</form>
</div></div></div>";
}

elseif($_GET['act']=='paketmk'){
echo"
<div class='card'>
<div class='card-header'>
<h3 class='card-title'><b style='color:purple'>Paket Matakuliah</b></h3>
[ <a href='?ndelox=$ndelox&act=PktEdt&md=1&prodi=$_GET[prodi]'>Tambah Paket</a> ]
<div class='table-responsive'>  

<table id='example' class='table table-sm table-striped'>
	<thead>
	<tr style='background:purple;color:white'>
	<th style='width:40px'>No</th>
	<th>Nama Paket</th>
	<th>Kurikulum</th>                              
	<th>Deskripsi</th>
	<th style='text-align:center'>NA</th>
	<th style='text-align:right'>Jml MK</th>
	<th style='text-align:right'>SKS</th>
	<th style='text-align:left'>Aksi</th>
	</tr>
	</thead>
	<tbody>";
	$no = 1;
	$sql =mysqli_query($koneksi, "select mp.*, kr.Nama as NamaKur
    from mkpaket mp
    left outer join kurikulum kr on mp.KurikulumID=kr.KurikulumID
    where  mp.ProdiID='".strfilter($_GET['prodi'])."'
    order by mp.Nama"); //mp.KodeID='".KodeID."' and
	while($row=mysqli_fetch_array($sql)){  
		if ($row['NA']=='N'){
			$stat="<i style='color:green' class='fa fa-eye'></i>";
		  }else{
			$stat="<i style='color:red' class='fa fa-eye-slash'></i>";
		  }
		$jmlMK	= mysqli_num_rows(mysqli_query($koneksi, "SELECT * from mkpaketisi where MKPaketID='$row[MKPaketID]' and KurikulumID='$row[KurikulumID]' "));
		$tSKS	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(mk.SKS) as totSKS, mkpaketisi.* 
		FROM mkpaketisi,mk 
		WHERE mk.MKID=mkpaketisi.MKID
		AND mkpaketisi.MKPaketID='$row[MKPaketID]'
		AND mkpaketisi.KurikulumID='$row[KurikulumID]'"));
	echo "<tr>
			<td>$no</td>                 
			<td>$row[Nama]</td>
			<td>$row[NamaKur]</td>             
			<td>$row[Deskripsi]</td>
			<td style='text-align:center'>$stat</td>
			<td style='text-align:right'>$jmlMK</td>
			<td style='text-align:right'>$tSKS[totSKS]</td>
			<td style='width:70px;text-align:left'><a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=master/matkul&act=paketmkisi&MKPaketID=$row[MKPaketID]&prodi=$row[ProdiID]&kurikulum=$row[KurikulumID]'><i class='fa fa-edit'></i></a>
			</td>
		  </tr>";
	$no++;
	}
echo"<tbody>
</table>
</div>
</div>
</div>";
}


elseif($_GET['act']=='PktEdt'){
	if (isset($_POST['Simpan'])){			
		$md = $_REQUEST['md']+0;
		$Nama = sqling($_REQUEST['Nama']);
		$KurikulumID = $_REQUEST['KurikulumID'];
		$Deskripsi = sqling($_REQUEST['Deskripsi']);
		$NA = (empty($_REQUEST['NA']))? 'N' : $_REQUEST['NA'];
		$KodeID = $_REQUEST['KodeID'];
		$ProdiID = $_REQUEST['ProdiID'];
		$JumSksPaket = AmbilOneField('mkpaket', "ProdiID = '$ProdiID' and NA", 'N', "SUM(SKS)");
		if ($md == 0) {
			$query = mysqli_query($koneksi, "set Nama='$Nama', 
			KurikulumID	='$KurikulumID', 
			Deskripsi	='$Deskripsi', 
			NA			='$NA',
			LoginEdit 	= '$_SESSION[_Login]',
			TglEdit 	= now()
			where MKPaketID='$_REQUEST[MKPaketID]' ");
			echo "<script>document.location='index.php?ndelox=master/matkul&act=paketmk&prodi=$_POST[ProdiID]&kurikulum=$_GET[kurikulum]&sukses';</script>";
		}
		else {
			$RuangID 	= strfilter($_POST['RuangID']);
			$ada 		= AmbilFieldx('ruang', 'RuangID', $RuangID, '*');
			if (!empty($ada)) echo PesanError("Gagal Simpan", "Kode: <b>$RuangID</b> sudah ada dengan nama <b>$ada[Nama]</b>");
			else {
				mysqli_query($koneksi, "insert into mkpaket 
				(Nama, KodeID, ProdiID, KurikulumID,
				Deskripsi, NA, LoginBuat, TglBuat)
				values 
				('$Nama', '$KodeID', '$ProdiID', '$KurikulumID',
				'$Deskripsi', '$NA', '$_SESSION[_Login]', now())");
		echo "<script>document.location='index.php?ndelox=master/matkul&act=paketmk&prodi=$_POST[ProdiID]&kurikulum=$_GET[kurikulum]&sukses';</script>";
			}
	  }
	} //tutup submit simpan

$md = $_REQUEST['md']+0;
if ($md == 0) {
  $w = AmbilFieldx('mkpaket', 'MKPaketID', $_REQUEST['mpid'], '*');
  $jdl = "Edit Paket Matakuliah";
}
else {
  $w = array();
  $w['MKPaketID'] = 0;
  $w['KodeID'] = $_SESSION['KodeID'];
  $w['ProdiID'] = $_GET['prodi'];
  $w['KurikulumID'] = 0;
  $w['Nama'] = '';
  $w['Deskripsi'] = '';
  $w['NA'] = 'N';
  $jdl = "Tambah Paket Matakuliah";
}
$_na = ($w['NA'] == 'Y')? 'checked' : '';
$snm = session_name(); $sid = session_id();
$optkurid = AmbilCombo2("kurikulum", "concat(KurikulumKode, ' - ', Nama)",
  "KurikulumKode", $w['KurikulumID'], "KodeID='$w[KodeID]' and ProdiID='$w[ProdiID]'", "KurikulumID", 1);
$NamaProdi = AmbilOneField('prodi', 'ProdiID', $_GET['prodi'], 'Nama');
// Tampilkan
CheckFormScript("Nama,KurikulumID");
echo "<p><div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id='example' class='table table-sm table-stripedx' style='width:70%' align='center'>
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
<input type=hidden name='ndelox' value='$ndelox'>
<input type=hidden name='md' value='$md'>
<input type=hidden name='MKPaketID' value='$w[MKPaketID]'>
<input type=hidden name='BypassMenu' value='1' />

<input type=hidden name='KodeID' value='$w[KodeID]'>
<input type=hidden name='ProdiID' value='$w[ProdiID]'>

<tr><td colspan=2 class=ul><sup>$_SESSION[prodi]</sup> <font size=+1>$NamaProdi</font></td></tr>
<tr style='background:purple;color:white'><th class=ttl colspan=2>$jdl</th></tr>
<tr>
	<td class=inp>Nama Paket</td>
	<td class=ul><input type=text name='Nama' value='$w[Nama]' size=40 maxlength=50></td>
</tr>
<tr>
	<td class=inp>Kurikulum</td>
	<td class=ul><select name='KurikulumID'>$optkurid</select></td>
</tr>
<tr>
	<td class=inp>Deskripsi</td>
	<td class=ul><textarea name='Deskripsi' cols=35 rows=3>$w[Deskripsi]</textarea></td>
</tr>
<tr>
	<td class=inp>Tidak aktif?</td>
	<td class=ul><input type=checkbox name='NA' value='Y' $_na> Centang jika tidak aktif</td> 
</tr>
<tr>
	<td colspan=2 class=ul1 align=left>
	<input class='btn btn-success btn-sm' type=submit name='Simpan' value='Simpan'>
	<input class='btn btn-primary btn-sm' type=reset name='Reset' value='Reset'>
	<input class='btn btn-warning btn-sm' type=button name='Batal' value='Batal' onClick=\"location='?ndelox=$ndelox&$pref=$_SESSION[$pref]&$snm=$sid'\"></td>
</tr>
</form>
</table>
</div>
</div>
</div>";
}


elseif($_GET['act']=='paketmkisi'){
	global $koneksi;
	if (isset($_POST['Tambah'])){
		$MKPaketID 		= strfilter($_POST['MKPaketID']);
		$MKID 			= strfilter($_POST['MKID']);
		$KurikulumID 	= strfilter($_POST['KurikulumID']);
		$jmlsks 		= strfilter($_POST['jmlsks']);
		$makssks 		= strfilter($_POST['makssks']);

		$tambahjmlsks 	= AmbilOneField('mk', "MKID='$MKID' and NA", 'N', 'SKS');
		$totalsks 		= $jmlsks+$tambahjmlsks;
		if ($totalsks<=$makssks) {
			$query = mysqli_query($koneksi, "insert into mkpaketisi (MKPaketID, KurikulumID, MKID, NA)
					values('$MKPaketID', '$KurikulumID', '$MKID', 'N')");
					SuksesTersimpan("?ndelox=$_SESSION[ndelox]&mk=paketmk&act=paketmkisi&prodi=$_GET[prodi]&MKPaketID=$MKPaketID", 100);
		}
		else 
			echo PesanError("Tidak Disimpan", 
			"SKS Melebihi kuota SKS yang ada dalam prodi.<br>
			Hubungi Administrator untuk informasi lebih lanjut.<hr size=1 color=black>
			<a href='?ndelox=$_SESSION[ndelox]&mk=paketmk&act=paketmkisi&MKPaketID=$MKPaketID'>Kembali</a>");   
				if ($query){
					echo "<script>document.location='index.php?ndelox=master/matkul&act=paketmkisi&prodi=".strfilter($_POST['prodi'])."&KurikulumID=".strfilter($_POST['KurikulumID'])."&MKPaketID=".strfilter($_POST['MKPaketID'])."&sukses';</script>";
				}else{
					echo "<script>document.location='index.php?ndelox=master/matkul&act=paketmkisi&prodi=".strfilter($_POST['prodi'])."&KurikulumID=".strfilter($_POST['KurikulumID'])."&MKPaketID=".strfilter($_POST['MKPaketID'])."&&gagal';</script>";
				}
	}
  
	$MKPaketID = strfilter($_GET['MKPaketID']);
	$s = "select mpi.*, mk.MKKode, mk.Nama, mk.SKS
	  from mkpaketisi mpi
	  left outer join mk on mpi.MKID=mk.MKID
	  where mpi.MKPaketID='$MKPaketID'
	  order by mk.Sesi, mk.MKKode";
	$r = mysqli_query($koneksi, $s);
	$n = 0;
	$arrPaket = AmbilFieldx('mkpaket', "MKPaketID", $MKPaketID, 'Nama, KurikulumID, ProdiID');
	$NamaPaket = $arrPaket['Nama'];
	$NamaKurikulum = AmbilOneField('kurikulum', 'KurikulumID', $arrPaket['KurikulumID'], 'Nama');
	$NamaProdi = AmbilOneField('prodi', 'ProdiID', $arrPaket['ProdiID'], 'Nama');

	$s1 = "select Nama , MKKode, MKID, SKS from mk where NA='N' and KurikulumID='$arrPaket[KurikulumID]' and
		  NOT(MKID IN (select MKID from mkpaketisi where KodeID = '".KodeID."' and MKPaketID = '$MKPaketID' and NA='N'))
		  order by Nama ";		
	$q1 = mysqli_query($koneksi, $s1);
	while ($w1 = mysqli_fetch_array($q1)){
		  $optmkid .= "<option value='$w1[MKID]' >$w1[Nama] - $w1[MKKode] ( $w1[SKS] )</option>";
	}
  
	$kuraktif = AmbilOneField('kurikulum', "ProdiID = 99 and NA", 'N', "KurikulumID");
	$jmlsks = AmbilFieldx("mkpaketisi mpi left outer join mk mk on mpi.MKID=mk.MKID", 
		'mpi.MKPaketID', 
		$MKPaketID, 
		"count(*) as JMLMK, sum(mk.SKS) as JMLSKS");
	$makssks = AmbilOneField('prodi', "ProdiID = '$_GET[prodi]' and NA", 'N', "DefSKS");
	$optmkmpk = AmbilCombo2('mk', "concat(MKKode, ' - ', Nama, ' (', SKS, ')')", 'MKKode', '',
	  "KurikulumID='$kuraktif'", 'MKID');
  
	echo "
	<div class='card'>
	<div class='card-header'>
	<div class='table-responsive'>
	<table class='table table-sm table-stripedx' style='width:70%' align='center'>
	  <!-- Header -->
	  <tr><td class=inp colspan=2 width=220>Paket </td>
		  <td class=ul1 colspan=3><b>: $NamaPaket</b></td>
		  </tr>
	  <tr><td class=inp colspan=2>Program Studi </td>
		  <td class=ul1 colspan=3><b>: $NamaProdi</b></td>
		  </tr>
	  <tr><td class=inp colspan=2>Kurikulum </td>
		  <td class=ul1 colspan=3><b>: $NamaKurikulum</b></td>
		  </tr>
	  <tr><td class=inp colspan=2>Total SKS/Maks SKS </td>
		  <td class=ul1 colspan=3><b>: $jmlsks[JMLSKS]/$makssks</b></td>
		  </tr>
	  <tr><td class=inp colspan=2>Tambah MK </td>
	  <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
		  <input type=hidden name='MKPaketID' value='$MKPaketID'>
		  <input type=hidden name='prodi' value='$_GET[prodi]'>
		  <input type=hidden name='KurikulumID' value='$arrPaket[KurikulumID]'>
		  <input type=hidden name='jmlsks' value='$jmlsks[JMLSKS]' />
		  <input type=hidden name='makssks' value='$makssks' />
		  
		  <td class=ul1 colspan=3><select style='height:30px' name='MKID'>$optmkid</select>
		  <input class='btn btn-success btn-sm' type=submit name='Tambah' value='Tambah'>
		  <input class='btn btn-danger btn-sm' type=button name='Batal' value='Batal' onClick=\"location='?ndelox=$ndelox&act=paketmk&prodi=$_GET[prodi]&kurikulum=$_GET[KurikulumID]'\">
		  </td>
		  </form>
	  
	  <tr style='background:purple;color:white'>
	  <th class=ttl width=24>#</th>
		  <th class=ttl width=100>Kode MK</th>
		  <th class=ttl>Matakuliah</th>
		  <th class=ttl width=40>SKS</th>
		  <th class=ttl width=20 title='Hapus data'>Del</th>
	  </tr>";
	while ($w = mysqli_fetch_array($r)) {
	  $n++;
	  $c = ($w['NA'] == 'Y')? 'class=nac' : 'class=ul';
	  echo "<tr>
		<td $c>$n</td>
		<td $c>$w[MKKode]</td>
		<td $c>$w[Nama]</td>
		<td $c>$w[SKS]</td>
		<td class=ul>
		  <a href='?ndelox=master/matkul&act=IsiPktDel&MKPaketIsiID=$w[MKPaketIsiID]&MKPaketID=$MKPaketID&prodi=$_GET[prodi]&mkid=$w[MKID]'><i class='fa fa-trash'></i></a>
		  </td>
	  </tr>";
	}

	echo "</table>
	</div>
  </div>
  </div></p>";
}

elseif($_GET['act']=='IsiPktDel'){
	global $koneksi;
	$MKPaketID 		= strfilter($_GET['MKPaketID']);
	$mkid 			= strfilter($_GET['MKID']);
	$MKPaketIsiID 	= strfilter($_GET['MKPaketIsiID']);
	$s = "delete from mkpaketisi where MKPaketIsiID='$MKPaketIsiID' ";
	$r = mysqli_query($koneksi, $s);
	SuksesTersimpan("?ndelox=master/matkul&act=paketmkisi&MKPaketID=$MKPaketID&prodi=$_GET[prodi]", 100);
  }


elseif($_GET['act']=='predikat'){
echo"
<div class='card'>
<div class='card-header'>
<h3 class='card-title'><b style='color:purple'>Predikat</b></h3>
<div class='table-responsive'>  
<table id='example' class='table table-sm table-striped'>
	<thead>
	<tr style='background:purple;color:white'>
	<th style='width:5px'>No</th>
	<th style='text-align:right;width:100px'>IPK Min</th>
	<th style='text-align:right;width:100px'>IPK Max</th>                                   
	<th style='text-align:left;width:200px'>Predikat</th>
	<th style='text-align:left;width:300px'>Keterangan</th>
	<th>Aksi</th>
	</tr>
	</thead>
	<tbody>";
	$no = 1;
	$sql =mysqli_query($koneksi, "select * from predikat WHERE ProdiID='".strfilter($_GET['prodi'])."' order by IPKMax DESC");
	while($row=mysqli_fetch_array($sql)){  
	echo "<tr>
			<td>$no</td>                 
			<td style='text-align:right'>$row[IPKMin]</td>
			<td style='text-align:right'>$row[IPKMax]</td>
			<td>$row[Nama]</td>
			<td>$row[Keterangan]</td>
			<td style='width:70px;align:left'>
			<a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=master/matkul&act=editpredikat&PredikatID=$row[PredikatID]&prodi=$row[ProdiID]&kurikulum=$_GET[kurikulum]'><i class='fa fa-edit'></i></a>
			</td>
			</tr>";
	$no++;
	}
echo"<tbody>
</table>
</div>
</div>
</div>";
}
		
elseif($_GET['act']=='editpredikat'){
	if (isset($_POST['update1'])){
	  $tglu = date('Y-m-d H:i:s');
	  if ($_POST['NA']=='Y'){$NA='N';}else{$NA='Y';}
	   $query = mysqli_query($koneksi, "UPDATE predikat SET                           
							 Nama			='".strfilter($_POST['Nama'])."',
							 IPKMin			='".strfilter($_POST['IPKMin'])."',
							 IPKMax			='".strfilter($_POST['IPKMax'])."',
							  LoginEdit		='".strfilter($_SESSION['_Login'])."',
							 NA				='$NA'
							 WHERE PredikatID ='".strfilter($_POST['PredikatID'])."'");     
	   if ($query){
		 echo "<script>document.location='index.php?ndelox=master/matkul&act=predikat&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST['kurikulum'])."&sukses';</script>";
	   }else{
		 echo "<script>document.location='index.php?ndelox=master/matkul&&act=predikat&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST['kurikulum'])."&gagal';</script>";
	   }
 }

$mt = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM predikat where PredikatID='".strfilter($_GET['PredikatID'])."'"));

echo"
<form action='' method='POST' enctype='multipart/form-data' class='form-horizontal'>
<input type='hidden' name='PredikatID' value='$_GET[PredikatID]'>
<input type='hidden' name='prodi' value='$_GET[prodi]'>
<input type='hidden' name='kurikulum' value='$_GET[kurikulum]'>


<div class='card'>
<div class='card-header'>
<h4><b style='color:purple'>EDIT PREDIKAT</b></h4>
<div class='table-responsive'>  
<table class='table table-sm table-sm'>
<tbody>                         
<tr><th scope='row'>Nama </th> <td><input type='text' class='form-control form-control-sm' name='Nama' value='$mt[Nama]'></td></tr>
<tr><th scope='row'>IPK Min</th> <td><input type='text' class='form-control form-control-sm' name='IPKMin' value='$mt[IPKMin]'></td></tr>
<tr><th scope='row'>IPK Max </th> <td><input type='text' class='form-control form-control-sm' name='IPKMax' value='$mt[IPKMax]'></td></tr>   ";
if ($mt['NA']=='N'){
	$ch ="Checked";
}else{
	$ch ="UnChecked";	
}
echo"<tr><th scope='row'>Aktif?</th> <td><input type='checkbox' name='NA' value='Y' $ch></td></tr>
<tr><th colspan='2'>
<div class='box-footer'>
<button type='submit' name='update1' class='btn btn-info'>Update</button>
<a href='index.php?ndelox=master/matkul&act=predikat&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
</div> 
</th>
</tr>
</table>
</form>
</div></div></div>";
}

elseif($_GET['act']=='konsentrasi'){
echo"
<div class='card'>
<div class='card-header'>
<h3 class='card-title'><b style='color:purple'>Konsentrasi</b></h3>

<div class='table-responsive'>  
<table id='example' class='table table-sm table-striped'>
	<thead>
	<tr style='background:purple;color:white'>
	<th style='width:5px'>No</th>
	<th style='text-align:center;width:60px'>Kode</th>
	<th style='text-align:left;width:100px'>Nama</th>                                   
	<th style='text-align:left;width:300px'>Keterangan</th>
	<th style='text-align:left'>Aksi</th>
	</tr>
	</thead>
	<tbody>";
	$no = 1;
	$sql =mysqli_query($koneksi, "select * from konsentrasi WHERE ProdiID='".strfilter($_GET['prodi'])."' order by Nama ASC");
	while($row=mysqli_fetch_array($sql)){  
	echo "<tr>
			<td>$no</td>                 
			<td style='text-align:center'>$row[KonsentrasiKode]</td>
			<td>$row[Nama]</td>
			<td>$row[Keterangan]</td>
			<td style='width:70px;text-align:left'><a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=master/matkul&act=editkonsentrasi&KonsentrasiID=$row[KonsentrasiID]&prodi=$row[ProdiID]&kurikulum=$_GET[kurikulum]'><i class='fa fa-edit'></i></a>
			</td>
			</tr>";
	$no++;
	}
echo"<tbody>
</table>
</div>
</div>
</div>";
}

elseif($_GET['act']=='editkonsentrasi'){
	if (isset($_POST['update1'])){
	  $tglu = date('Y-m-d H:i:s');
	  if ($_POST['NA']=='Y'){$NA='N';}else{$NA='Y';}
	   $query = mysqli_query($koneksi, "UPDATE konsentrasi SET                           
							 Nama			    ='".strfilter($_POST['Nama'])."',
							 Keterangan			='".strfilter($_POST['Keterangan'])."',
							 KonsentrasiKode	='".strfilter($_POST['KonsentrasiKode'])."',
							 NA				    ='$NA'
							 WHERE KonsentrasiID   ='".strfilter($_POST['KonsentrasiID'])."'");     
	   if ($query){
		 echo "<script>document.location='index.php?ndelox=master/matkul&act=konsentrasi&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST['kurikulum'])."&sukses';</script>";
	   }else{
		 echo "<script>document.location='index.php?ndelox=master/matkul&&act=konsentrasi&prodi=".strfilter($_POST['prodi'])."&kurikulum=".strfilter($_POST['kurikulum'])."&gagal';</script>";
	   }
 }

$mt = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM konsentrasi where KonsentrasiID='".strfilter($_GET['KonsentrasiID'])."'"));


echo"
<form action='' method='POST' enctype='multipart/form-data' class='form-horizontal'>
<input type='hidden' name='KonsentrasiID' value='$_GET[KonsentrasiID]'>
<input type='hidden' name='prodi' value='$_GET[prodi]'>
<input type='hidden' name='kurikulum' value='$_GET[kurikulum]'>


<div class='card'>
<div class='card-header'>
<h4><b style='color:purple'>Edit Konsentrasi</b></h4>
<div class='table-responsive'>  
<table class='table table-sm table-sm'>
<tbody>                         
<tr><th scope='row' width='210px'>Kode </th> <td><input type='text' class='form-control form-control-sm' name='KonsentrasiKode' value='$mt[KonsentrasiKode]'></td></tr>
<tr><th scope='row'>Nama </th> <td><input type='text' class='form-control form-control-sm' name='Nama' value='$mt[Nama]'></td></tr>
<tr><th scope='row'>Keterangan </th> <td><textarea class='form-control form-control-sm' rows='25' name='Keterangan'> $mt[Keterangan]</textarea></td></tr>   ";
if ($mt['NA']=='N'){
	$ch ="Checked";
}else{
	$ch ="UnChecked";	
}
echo"<tr><th scope='row'>Aktif?</th> <td><input type='checkbox' name='NA' value='Y' $ch></td></tr>
<tr><th colspan='2'>
<div class='box-footer'>
<button type='submit' name='update1' class='btn btn-info'>Update</button>
<a href='index.php?ndelox=master/matkul&act=konsentrasi&prodi=$_GET[prodi]&kurikulum=$_GET[kurikulum]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
</div> 
</th>
</tr>
</table>
</form>
</div>
</div>
</div>";
}