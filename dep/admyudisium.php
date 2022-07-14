<div class="card">
<div class="card-header">
<div class="card-tools">
 [ <a href='yudisium' target=_blank>Preview Yudisium </a> ]
<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=dep/admyudisium&act=tambahdata&tahun=<?php echo $_GET['tahun']; ?>&prodi=<?php echo "$_GET[prodi]"; ?>&program=<?php echo 	"$_GET[program]"; ?>'>Add Data</a>
</div>  

<div class="form-group row">
<label class="col-md-6 col-form-label text-md-left"><b style='color:purple'>YUDISIUM</b></label>

<div class="col-md-2 "> 
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='dep/admyudisium'>
<select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
<?php 
	echo "<option value=''>- Pilih Tahun Akademik -</option>";
	$tahun = mysqli_query($koneksi, "SELECT distinct(TahunID) FROM tahun order by TahunID Desc limit 20"); //and NA='N'
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
echo "<option value='All'>Seluruh Program Studi</option>";
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
<input type="submit" class='btn btn-success btn-sm' value='Lihat'>
</form>
</div>
</div>
</div>
</div>

    
<?php if ($_GET['act']==''){ ?>
<?php 
if (isset($_GET['sukses'])){
echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah Berhasil Di Proses,..
    </div>";
}elseif(isset($_GET['gagal'])){
echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Data tidak Di Proses, terjadi kesalahan dengan data..
    </div>";
} //class='btn btn-warning btn-xs' class='glyphicon glyphicon-th
?>
<div class="card">
<div class="card-header">
<div class='table-responsive'>
<table id="example" class="table table-sm table-bordered table-striped">
<thead>
<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:purple;>
  <th style='width:20px'>No<center>(1)</center></th>                           
  <th><div align='center'>NIM </div><center>(2)</center></th>            
  <th><div align='left'>Nama Mahasiswa<br><left>(3)</left></th>
  <th><div align='left'>JL<br><left>(4)</left></th>
    <th><div align='left'>Program<br><left>(5)</left></th>
  <th><div align='center'>IPK <br><center>(6)</center></th>
 <th><div align='left'>Predikat <br><left>(7)</left></th>  
  <th width='210px'><div align='center'>Action <br><center>(7)</center></th> 
</tr>
</thead>
<tbody>
<?php
$sql = mysqli_query($koneksi, "SELECT mhsw.Nama,mhsw.ProgramID,
					t_yudisium.IDYudisium,
					t_yudisium.MhswID,
					t_yudisium.IPK,
					t_yudisium.Predikat,
					t_yudisium.Pemuncak,
					t_yudisium.ProdiID,
					t_yudisium.TahunID,
					t_yudisium.Status,
					t_yudisium.file_upload,
					t_yudisium.TglYudisium 
					FROM t_yudisium,mhsw 
					WHERE t_yudisium.MhswID=mhsw.MhswID 
					AND t_yudisium.ProdiID='".strfilter($_GET['prodi'])."' 
					AND t_yudisium.TahunID='".strfilter($_GET['tahun'])."'
					order by t_yudisium.IPK DESC");	
$no=1;
while ($r = mysqli_fetch_array($sql)){
$almt  = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MhswID,Alamat,NamaAyah,NamaIbu,Foto,Kelamin FROM mhsw WHERE MhswID='$r[MhswID]'"));
$NamaMhsx 	= strtoupper($r['Nama']); //strtoupper($kalimat);
$NamaMhs	= ucwords($NamaMhsx);
$NamaAyahx 	= strtolower($r['NamaAyah']); //strtoupper($kalimat);
$NamaAyah	= ucwords($NamaAyahx);
$NamaIbux 	= strtolower($r['NamaIbu']); //strtoupper($kalimat);
$NamaIbu	= ucwords($NamaIbux);
$reg  = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumReg 
											  FROM t_yudisium 
											  WHERE SUBSTR(MhswID,5,1)='1'
											  and TahunID='".strfilter($_GET['tahun'])."'
											  and ProdiID='".strfilter($_GET['prodi'])."'")); //1 reguler	
$tran = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumTran
											  FROM t_yudisium 
											  WHERE SUBSTR(MhswID,5,1)='2'
											  and TahunID='".strfilter($_GET['tahun'])."'
											  and ProdiID='".strfilter($_GET['prodi'])."'")); //2 transfer
$pin = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumPin
											  FROM t_yudisium 
											  WHERE SUBSTR(MhswID,5,1)='3'
											  and TahunID='".strfilter($_GET['tahun'])."'
											  and ProdiID='".strfilter($_GET['prodi'])."'")); //2 transfer												  
$grandTot = $reg['JumReg']+$tran['JumTran']+$pin['JumPin'];

echo "<tr >
		<td align='center'>$no</td>                  
        <td align='center'><a href='index.php?ndelox=dep/admyudisium&act=profillulusan&MhswID=$r[MhswID]&prodi=$_GET[prodi]&tahun=$r[TahunID]'>$r[MhswID]</a></td>
        <td align='left'><a href='index.php?ndelox=dep/admyudisium&act=profillulusan2&MhswID=$r[MhswID]&prodi=$_GET[prodi]&tahun=$r[TahunID]'>$NamaMhsx</a></td>
        <td align='left'>($almt[Kelamin])</td>
        <td align='left'>$r[ProgramID]</td>
        <td align='center'>$r[IPK]</td>
		<td align='left'>$r[Predikat]</td>";                            
echo "<td style='width:70px !important'><center>
<a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=dep/admyudisium&act=edit&prodi=$_GET[prodi]&tahun=$r[TahunID]&IDYudisium=$r[IDYudisium]'>
<i class='fa fa-edit'></i></a>

<a  class='btn btn-success btn-xs' href='index.php?ndelox=dep/admyudisium&act=viewnilai&IDYudisium=$r[IDYudisium]&prodi=$_GET[prodi]&tahun=$r[TahunID]&MhswID=$r[MhswID]' target='_BLANK'>
<span class='glyphicon glyphicon-th'></span> Transkrip</a>

<a  class='btn btn-warning btn-xs' target='_BLANK' href='print_reportxls/dtwisuda.php?tahun=$r[TahunID]'>
<span class='glyphicon glyphicon-book'></span> Export</a>

<a class='btn btn-danger btn-xs' title='Hapus' href='index.php?ndelox=dep/admyudisium&hapus=$r[IDYudisium]&prodi=$r[prodi]&tahun=$r[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
</center>
</td>
</tr>";
$no++;
}

if (isset($_GET['hapus'])){
mysqli_query($koneksi, "DELETE FROM xx where IDYudisium='".strfilter($_GET['hapus'])."'");
echo "<script>document.location='index.php?ndelox=dep/admyudisium&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
}
;
?>
<tbody>
</table>
</div>


<table id="example" class="table table-sm table-bordered table-striped">
<thead>
<tr><td  width=300px><b>Total Reguler</b></td><td width=10px>:</td><td style='text-align:right;width:30px;'>  <?php echo"<b>$reg[JumReg]</b>";?> </td><td>Mahasiswa</td></tr>
<tr><td ><b>Total Transfer</b>	</td><td>:</td><td style='text-align:right;width:30px;'>  <?php echo"<b>$tran[JumTran]</b>";?> </td><td>Mahasiswa</td></tr>
<tr><td ><b>Total Pindahan</b>	</td><td>:</td><td style='text-align:right;width:30px;'>  <?php echo"<b><b>$pin[JumPin]</b>";?> </td><td>Mahasiswa</td></tr>
<tr style=background-color:#FFEB99;><td ><b>Total Keseluruhan Lulusan</b></td><td >:</td><td style='text-align:right;width:30px;'>  <?php echo"<b>$grandTot</b>";?> </td><td>Mahasiswa</td></tr>
<tr><td colspan="4">&nbsp;</td></tr>
<tr><td colspan="4"><a href='yudisium' target=_blank>Preview Yudisium </a></td></tr>
<tr><td colspan="4"><a href='?ndelox=AngkaPenerimaan&act=grafpenerimaan&tahun=<?php echo"$_GET[tahun]";?>&prodi=<?php echo"$_GET[prodi]";?>'>Grafik Penerimaan Mahasiswa Baru</td></tr>
<tr><td colspan="4"><a href='?ndelox=AngkaLulusan&act=graflulusan&tahun=<?php echo"$_GET[tahun]";?>&prodi=<?php echo"$_GET[prodi]";?>'>Grafik Lulusan</td></tr>
<tr><td colspan="4"><a href='?ndelox=dep/admyudisium&act=predikat&tahun=<?php echo"$_GET[tahun]";?>&prodi=<?php echo"$_GET[prodi]";?>'>Grafik Predikat Lulusan</td></tr>
</table></div>     
</div><!-- /.box-body -->
<?php 
if ($_GET['prodi'] == '' ){
	echo "<center style='padding:60px; color:red'>Tentukan Tahun akademik, Program Studi Terlebih dahulu...</center>";
}
?>
</div>
</div>

<?php 
}elseif($_GET['act']=='tambahdata'){
if (isset($_POST['simpanx'])){	
	$tglnow =date('Y-m-d H:i:s');	
	$cek = mysqli_num_rows(mysqli_query($koneksi, "select * from lulusan where TahunID='".strfilter($_POST['tahun'])."' and ProdiID='".strfilter($_POST['prodi'])."'"));
	if ($cek>0){
		echo "<script>document.location='index.php?ndelox=dep/admyudisium&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&gagal';</script>";
		exit;
	}
					
	$query = mysqli_query($koneksi, "INSERT INTO t_yudisium(
					MhswID,
					Nama,
					IPK) 
			VALUES('".strfilter($_POST['MhswID'])."',
					'".strfilter($_POST['Nama'])."',
					'".strfilter($_POST['IPK'])."')");

	if ($query){
	echo "<script>document.location='index.php?ndelox=dep/admyudisium&tahun=$_POST[tahun]&prodi=$_POST[prodi]&sukses';</script>";
	}else{
	echo "<script>document.location='index.php?ndelox=dep/admyudisium&tahun=$_POST[tahun]&prodi=$_POST[prodi]&gagal';</script>";
	}

}

echo "

  <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>    
    <input type='hidden' name='program' value='$_GET[program]'>
    <input type='hidden' name='prodi' value='$_GET[prodi]'>				
	<div class='card'>
	<div class='card-header'>
      <table class='table table-condensed table-bordered'>
      <tbody>        
        <tr><th scope='row'>MhswID </th> <td><input type='text' class='form-control' name='MhswID' ></td></tr>
        <tr><th scope='row'>Nama </th> <td><input type='text' class='form-control' name='Nama' ></td></tr>
        <tr><th scope='row'>IPK</th> <td><input type='text' class='form-control' name='IPK' ></td></tr>        
      </tbody>
      </table>
    </div>
  </div>
  <div class='box-footer'>
        <button type='submit' name='simpanx' class='btn btn-info'>Simpan</button>
        <a href='index.php?ndelox=dep/admyudisium&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a                    
      </div>
  </form>
</div>";
}

elseif($_GET['act']=='edit'){
$j = mysqli_fetch_array(mysqli_query("select * from t_yudisium where IDYudisium='".strfilter($_GET['IDYudisium'])."'"));
$m = mysqli_fetch_array(mysqli_query("select MhswID,Foto from mhsw where MhswID='$j[MhswID]'"));
if (isset($_POST['ubahx'])){									
	$query = mysqli_query("UPDATE t_yudisium SET
	Nama 		='".strfilter($_POST['Nama'])."',
	Predikat	='".strfilter($_POST['Predikat'])."',
	IPK 		='".strfilter($_POST['IPK'])."'
	WHERE IDYudisium ='".strfilter($_POST['IDYudisium'])."' "); 


	  $filename 	= basename($_FILES['ax']['name']);
	   if ($filename != ''){      
		    $ekstensi_diperbolehkan	= array('png','jpg');
			$filenamee 	= date("YmdHis").'-'.basename($_FILES['ax']['name']);
			$x 			= explode('.', $filenamee);
			$ekstensi 	= strtolower(end($x));
			$ukuran		= $_FILES['ax']['size'];
			$file_tmp = $_FILES['ax']['tmp_name'];	

			if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
				if($ukuran < 55000){	 //1044070		
					move_uploaded_file($file_tmp, 'pto_stud/'.$filenamee);	
					$queryx = mysqli_query($koneksi, "UPDATE mhsw SET                           
						   Nama   	= '".strfilter($_POST['Nama'])."',
						   Foto 	= '$filenamee' 
						   where MhswID='".strfilter($_POST['MhswID'])."'");
				    echo "<script>document.location='index.php?ndelox=dep/admyudisium&tahun=$_POST[tahun]&prodi=$_POST[prodi]&sukses';</script>";		   
				}else{
					echo "<b style=color:red>Ukuran File terlalu besar, compress menjadi < 55000 Byte !</b>";
				}
			}else{
				echo "<b style=color:red>Ekstensi File yang diupload tidak diperbolehkan!</b>";
			}
	  }else{
		  $queryx = mysqli_query($koneksi, "UPDATE mhsw SET                           
						   Nama     	='".strfilter($_POST['Nama'])."'
						   where MhswID ='".strfilter($_POST['MhswID'])."'");
		  echo "<script>document.location='index.php?ndelox=dep/admyudisium&tahun=$_POST[tahun]&prodi=$_POST[prodi]&sukses';</script>";				   
	  }
}

echo " 
  <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
    <input type='hidden' name='MhswID' value='$j[MhswID]'>
    <input type='hidden' name='tahun' value='$_GET[tahun]'>
    <input type='hidden' name='prodi' value='$_GET[prodi]'>	
    <input type='hidden' name='IDYudisium' value='$_GET[IDYudisium]'>	
	<div class='card'>
	<div class='card-header'>
      <table class='table table-condensed table-bordered'>
      <tbody>
	  <tr><th scope='row' width='140px'>NIM</th> <td><input type='text' class='form-control' name='x' value='$j[MhswID]'></td></tr>
        <tr><th scope='row' >Nama Mahasiswa</th> <td><input type='text' class='form-control' name='Nama' value='$j[Nama]' ></td></tr>          
        <tr><th scope='row'>IPK </th> <td><input type='text' class='form-control' name='IPK' value='$j[IPK]' ></td></tr>
        <tr><th scope='row'>Predikat </th> <td><input type='text' class='form-control' name='Predikat' value='$j[Predikat]' ></td></tr>
        <tr><th scope='row'> </th> <td style='background-color:#E7EAEC' width='460px' >";
			if (trim($m['Foto'])==''){
			  echo "<img class='img-thumbnail' style='width:155px' src='pto_stud/no-image.jpg'>";
			}else{
			  echo "<img class='img-thumbnail' style='width:155px' src='pto_stud/$m[Foto]'>";
			}
			echo "</td>
		</tr>		
         <tr><th scope='row'>Ganti Foto</th>             <td><div style='position:relative;''>
			  <a class='btn btn-primary' href='javascript:;'>
				<span class='glyphicon glyphicon-search'></span> Browse..."; ?>
				<input type='file' class='files' name='ax' onchange='$("#upload-file-info").html($(this).val());'>
			  <?php echo "</a> <span style='width:155px' class='label label-info' id='upload-file-info'></span>
			</div>
		</td>
		</tr>
        
      </tbody>
      </table>
    </div>
  </div>
  <div class='box-footer'>
        <button type='submit' name='ubahx' class='btn btn-info'>Perbaharui Data</button>
        <a href='index.php?ndelox=dep/admyudisium&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a                    
      </div>
  </form>
</div>";
}

elseif($_GET['act']=='editnilai'){
$j = mysqli_fetch_array(mysqli_query("select * from t_yudisiumdetail where IDX='".strfilter($_GET['IDX'])."'"));
$m = mysqli_fetch_array(mysqli_query("select MhswID,Nama,Foto from mhsw where MhswID='$j[MhswID]'"));
if (isset($_POST['ubahf'])){									
	$query = mysqli_query($koneksi, "UPDATE t_yudisiumdetail SET
	GradeNilai 		='".strfilter($_POST['GradeNilai'])."'
	WHERE IDX 		='".strfilter($_POST['IDX'])."' "); 
	 
	if ($query){
	echo "<script>document.location='index.php?ndelox=dep/admyudisium&act=viewnilai&tahun=$_POST[tahun]&prodi=$_POST[prodi]&MhswID=$_POST[MhswID]&sukses';</script>";
	}else{
	echo "<script>document.location='index.php?ndelox=dep/admyudisium&act=viewnilai&tahun=$_POST[tahun]&prodi=$_POST[prodi]&MhswID=$_POST[MhswID]&gagal';</script>";
	}
}

echo " 
  <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
    <input type='hidden' name='MhswID' value='$j[MhswID]'>
    <input type='hidden' name='tahun' value='$_GET[tahun]'>
    <input type='hidden' name='prodi' value='$_GET[prodi]'>	
    <input type='hidden' name='IDX' value='$_GET[IDX]'>	
	<div class='card'>
	<div class='card-header'>
      <table class='table table-condensed table-bordered'>
      <tbody>
	  <tr><th scope='row' width='220px'>NIM</th> <td><input type='text' class='form-control' name='x' value='$j[MhswID]' readonly></td></tr>
      <tr><th scope='row' >Nama Mahasiswa</th> <td><input type='text' class='form-control' name='Nama' value='$m[Nama]' readonly></td></tr> 
	  <tr><th scope='row' >MKID</th> <td><input type='text' class='form-control' name='MKID' value='$j[MKID]' readonly></td></tr> 
	  <tr><th scope='row' >Nama Matakuliah</th> <td><input type='text' class='form-control' name='NamaMK' value='$j[NamaMK]' readonly></td></tr> 
	  <tr><th scope='row' >SKS</th> <td><input type='text' class='form-control' name='SKS' value='$j[SKS]' readonly></td></tr> 
      <tr><th scope='row' >Grade Nilai</th> <td><input type='text' class='form-control' name='GradeNilai' value='$j[GradeNilai]' ></td></tr> 	  
      </tbody>
      </table>
    </div>
  </div>
  <div class='box-footer'>
        <button type='submit' name='ubahf' class='btn btn-info'>Perbaharui Nilai</button>
        <a href='index.php?ndelox=dep/admyudisium&act=viewnilai&tahun=$_GET[tahun]&prodi=$_GET[prodi]&MhswID=$_GET[MhswID]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a                    
      </div>
  </form>
</div>";
}

elseif($_GET['act']=='viewnilai'){ 
	$dt = mysqli_fetch_array(mysqli_query($koneksi, "select * FROM t_yudisium where MhswID='".strfilter($_GET['MhswID'])."'"));
    $j = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MhswID,Judul,TahunID,ProdiID,PembimbingSkripsi1,PembimbingSkripsi2 FROM jadwal_skripsi where MhswID='".strfilter($_GET['MhswID'])."' "));
   
	$Judulx 	= strtolower($j['Judul']);
	$Judul		= ucwords($Judulx);

	$p1 = mysqli_fetch_array(mysqli_query("select Login,Nama,Gelar from dosen where Login='$j[PembimbingSkripsi1]'"));
	$Pembimbing1x 	= strtolower($p1['Nama']);
	$Pembimbing1	= ucwords($Pembimbing1x);
		   
	$p2 = mysqli_fetch_array(mysqli_query("select Login,Nama,Gelar from dosen where Login='$j[PembimbingSkripsi2]'"));	
	$Pembimbing2x 	= strtolower($p2['Nama']);
	$Pembimbing2	= ucwords($Pembimbing2x);
	
	if (isset($_GET['sukses'])){
		echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
			<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
			<span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah Berhasil Di Proses,..
			</div>";
	}elseif(isset($_GET['gagal'])){
		echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
			<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
			<span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Data tidak Di Proses, terjadi kesalahan dengan data..
			</div>";
	}
?>	


<div class='card'>
<div class='card-header'> 

<table width='100%'>
<tr style='text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#FF8737;'>
<td scope='row' colspan='2' style='width:900px' height='10px'></td>
</tr>
<tr style='text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#FF8737;'>
<th scope='row' style='width:150px'>&nbsp;&nbsp;NIM</th> <td scope='row' style='width:900px'><b> :<?php echo"&nbsp;$dt[MhswID]";?></b></td>
</tr>
<tr style='text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#FF8737;'>
<th scope='row' style='width:150px'>&nbsp;&nbsp;Nama Mahasiswa</th> <td scope='row' style='width:500px'><b> :<?php echo"&nbsp;$dt[Nama]";?></b></td>
</tr>
<tr style='text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#FF8737;'>
<th scope='row' style='width:150px'>&nbsp;&nbsp;Judul Penelitian</th> <td scope='row' style='width:500px'><b> :<?php echo"&nbsp;$Judul";?></b></td>
</tr>
<tr style='text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#FF8737;'>
<th scope='row' style='width:150px'>&nbsp;&nbsp;Pembimbing 1</th> <td scope='row' style='width:500px'><b> :<?php echo"&nbsp;$Pembimbing1, $p1[Gelar]";?></b></td>
</tr>
<tr style='text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#FF8737;'>
<th scope='row' style='width:150px'>&nbsp;&nbsp;Pembimbing 2</th> <td scope='row' style='width:500px'><b> :<?php echo"&nbsp;$Pembimbing2, $p2[Gelar]";?></b></td>
</tr>
<tr style='text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#FF8737;'>
<td scope='row' colspan='2' style='width:900px' height='10px'></td>
</tr>
</table>

<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
<table id="example" class="table table-bordered table-striped">
  <thead>
	<tr style='background:purple;color:white'>
	  <th style='width:10px'>No</th>
	  <th style='width:20px'>Kode</th>  
	  <th style='width:250px'>Nama Matakuliah</th>
	  <th style='width:60px'>SKS</th> 
      <th style='width:60px'>Nilai Huruf</th>
	  <th>Aksi</th>	  
	</tr>
  </thead>
  <tbody>
	<?php
	  $tampil = mysqli_query($koneksi, "SELECT * from t_yudisiumdetail WHERE MhswID='".strfilter($_GET['MhswID'])."'");      		 
	  $no=1;
	  while($r=mysqli_fetch_array($tampil)){
	   $NamaMKx 	= strtoupper($r['NamaMK']);
       $NamaMK	    = ucwords($NamaMKx);
	  $huruf = $r['GradeNilai'];
		if ($huruf=="A"){								
			$bobot = 4;
		}
		elseif ($huruf=="A-"){			
			$bobot = 3.70;
		}
		elseif ($huruf=="B+"){			
			$bobot = 3.30;
		}
		elseif ($huruf=="B"){
			$bobot = 3;
		}
		elseif ($huruf=="B-"){			
			$bobot = 2.70;
		}
		elseif ($huruf=="C+"){			
			$bobot = 2.30;
		}
		elseif ($huruf=="C"){			
			$bobot = 2;
		}
		elseif ($huruf=="C-"){			
			$bobot = 1.70;
		}
		elseif ($huruf=="D"){			
			$bobot = "1";
		}
		elseif ($huruf=="E"){			
			$bobot = 0;
		}
	  echo "<tr><td>$no</td>
			<td>$r[MKID] </td>  
			<td>$NamaMKx </td>                                                 														
			<td>$r[SKS] </td>
			<td>$r[GradeNilai] </td>
			<td style='width:70px !important'><left>
			<a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=dep/admyudisium&act=editnilai&prodi=$_GET[prodi]&tahun=$_GET[tahun]&IDX=$r[IDX]&MhswID=$_GET[MhswID]'>
			<i class='fa fa-edit'></i></a>			
			</left>
			</td>			
			</tr>";
			$no++;
			$total_sks 	  	= $r['SKS'];
			$total_bobot  	= $r['SKS'] * $bobot;

			$tsks 			+= $total_sks;
			$tbobottotal 	+= $total_bobot;
			$ipk = number_format($tbobottotal / $tsks,2);	
	  }
	  echo"
	  <tr bgcolor=$warna><td colspan='2' align=left>&nbsp;</td></tr>
	  <tr bgcolor=$warna>
		<td colspan='2' align=left><b>Total SKS </b></td><td  colspan='4'><b> $tsks SKS </b></td>
		</tr>
		<tr bgcolor=$warna>
			<td colspan='2' align=left><b>Total Bobot </b></td><td colspan='4'><b> $tbobottotal </b></td>
		</tr>
		
		<tr bgcolor=$warna>
			<td colspan='2' align=left><b>IPK </b></td><td colspan='4'><b> $dt[IPK] </b></td>
		</tr>";	
	  ?>
	  
	  <tbody>
	  </table>
	 
	  </form>
	  <!-- /.box-body --> 
	
	
<?php	 
}

elseif($_GET['act']=='profillulusan'){
$d 			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT mhsw.Nama,
					t_yudisium.IDYudisium,
					t_yudisium.MhswID,
					t_yudisium.IPK,
					t_yudisium.Predikat,
					t_yudisium.Pemuncak,
					t_yudisium.ProdiID,
					t_yudisium.TahunID,
					t_yudisium.Status,
					t_yudisium.file_upload,
					t_yudisium.TglYudisium 
					FROM t_yudisium,mhsw 
					WHERE t_yudisium.MhswID=mhsw.MhswID 
					AND t_yudisium.MhswID='".strfilter($_GET['MhswID'])."'
					order by t_yudisium.MhswID asc"));
$c 			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from mhsw where MhswID='$d[MhswID]'"));
$NamaAyahx 	= strtolower($c['NamaAyah']); //strtoupper($kalimat);
$NamaAyah	= ucwords($NamaAyahx);
$NamaIbux 	= strtolower($c['NamaIbu']); //strtoupper($kalimat);
$NamaIbu	= ucwords($NamaIbux);
$kp  		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM vw_jadwalkp_seminarhasilanggota WHERE MhswID='$d[MhswID]'"));
$pn  		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM vw_jadwal_skripsi WHERE MhswID='$d[MhswID]'"));

$sqlpembimbingkpx 	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from vw_jadwalkp_seminarhasilanggota where MhswID='".strfilter($_GET['MhswID'])."'"));
$sqldosenjadwal 	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from jadwal_kp where JadwalID='$sqlpembimbingkpx[JadwalID]'"));
$pmkp1              = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$sqldosenjadwal[DosenID]'"));
$Pembimbingkp1x 	= strtolower($pmkp1['Nama']);
$Pembimbingkp1	    = ucwords($Pembimbingkp1x);


$nilaikp 		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_yudisiumdetail where MhswID='".strfilter($_GET['MhswID'])."' AND MKID='PBSI7403'"));
$nilaipn 		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_yudisiumdetail where MhswID='".strfilter($_GET['MhswID'])."' AND MKID='PBSI8604'"));

$NamaMhs 	= strtoupper($d['Nama']);
$Namax 		= strtolower($d['Nama']);
$Nama 		= ucwords($Namax);

$TmpLahirx 		= strtolower($c['TempatLahir']);
$TmpLahir 		= ucwords($TmpLahirx);

$Judulx 	= strtolower($kp['Judul']);
$JudulKP		= ucwords($Judulx);
$Juduly 	= strtolower($pn['Judul']);
$JudulPN		= ucwords($Juduly);



$p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$pn[PembimbingSkripsi1]'"));
$Pembimbing1x 	= strtolower($p1['Nama']);
$Pembimbing1	= ucwords($Pembimbing1x);
	   
$p2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$pn[PembimbingSkripsi2]'"));	
$Pembimbing2x 	= strtolower($p2['Nama']);
$Pembimbing2	= ucwords($Pembimbing2x);

$kelamin = mysqli_fetch_array(mysqli_query($koneksi, "select Kelamin,Nama from kelamin where Kelamin='$c[Kelamin]'"));

if (trim($c['Foto'])==''){
  $foto = 'pto_stud/no-image.jpg';
}else{
  $foto	= 'pto_stud/'.$c['Foto'];
} 

if (($d['IPK']>=2.76) && ($d['IPK']<=3.50)){
	$predikat="Sangat Memuaskan";
}else{
	$predikat="Dengan Pujian";
}

$tgnskrg		= date('Y-m-d');
$data 			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT datediff('$tgnskrg', '$c[TanggalLahir]')as selisih"));
$tahun 			= floor($data['selisih']/365);
$bulan 			= floor(($data['selisih'] - ($tahun * 365))/30);
$hari  			= $data['selisih'] - $bulan * 30 - $tahun * 365;

$maks = mysqli_fetch_array(mysqli_query($koneksi, "select MhswID,Pemuncak from t_yudisium WHERE MhswID='$_GET[MhswID]' AND Pemuncak='Y'"));

if ($maks['Pemuncak']=='Y'){
    $label="<tr>  
			<th scope='row'  width='120px'><b style=color:#FF8000;font-size:25px><img class='img-thumbnail' style='width:100px;' src='img/medals.png'></th>
			<th scope='row'  width='160px'><b style=color:#FF8000;font-size:25px>** CONGRATULATION **</th>			
			</tr>";	
}else{
	$label="<tr>  
			<th scope='row' colspan=2 width='120px'><b style=color:#FF8000;font-size:25px></th>			
			</tr>";
}

echo "<div class='card'>
<div class='card-header'>
               
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>  
  <table class='table table-condensed table-bordered'>
  <tbody>
  <tr style=text-align:left;font-size:20px;color:#FFFFFF;font-weight:bold;background-color:purple;>
  <th  scope='row' colspan='10'>SELAMAT KEPADA $NamaMhs [ $c[ProgramID] - $d[MhswID] ]</th></tr>			
  </tbody>
  </table>	
 

		<div class='row'>
			<div class='col-md-6'>

				<table class='table table-condensed table-bordered'>
				<tbody>	
					<tr>
					<td rowspan=5><img class='img-thumbnail' style='width:160px;height:205px' src='$foto'></td>
					<td scope='row' width='130px''><b style=color:#3c8dbc;font-size:20px>NIM</td>
					<td><b style=color:#3c8dbc;font-size:20px>: $d[MhswID] </b> [ <a href='index.php?ndelox=mhs&act=detailmhs&id=$_GET[MhswID]&program=$c[ProgramID]&prodi=$c[ProdiID]' target='_BLANK'> more </a>]</td></tr>
					<tr><th scope='row'><b style=color:#3c8dbc;font-size:20px>Nama</b></td>
					<td><b style=color:#3c8dbc;font-size:20px>: $Nama </b>&nbsp;&nbsp;<i></td>
					</tr>                  
					
					<tr>
					<th scope='row'><b style=color:#3c8dbc;font-size:20px>TTL</th> 
					<td><b style=color:#3c8dbc;font-size:20px>: $TmpLahir, ".tgl_indo($c['TanggalLahir'])."</td>
					</tr>
					
					<tr>
					<td scope='row'><b style=color:#3c8dbc;font-size:20px>Jns Kelamin</td> 			  
					<td><b style=color:#3c8dbc;font-size:20px>: $kelamin[Nama]</td>
					</tr>
					
					<tr>
					<td scope='row'><b style=color:#3c8dbc;font-size:20px>Umur</td>           
					<td><b style=color:#3c8dbc;font-size:20px>: $tahun Tahun $bulan Bulan $hari Hari</td>
					</tr>	
							
				</tbody>
				</table>
			</div>

			<div class='col-md-5'>
					<table class='table table-condensed table-bordered'>
					<tbody>	
						<tr>  
						<th scope='row' width='220px'><b style=color:#3c8dbc;font-size:20px>Nama Ayah</th>			
						<th align=center><b style=color:#3c8dbc;font-size:20px;text-align:center>: $NamaAyah</b></th>
						</tr>
							<tr>  
						<th scope='row' ><b style=color:#3c8dbc;font-size:20px>Nama Ibu</th>			
						<th align=center><b style=color:#3c8dbc;font-size:20px;text-align:center>: $NamaIbu</b></th>
						</tr>
						<tr>  
						<th scope='row' ><b style=color:#3c8dbc;font-size:20px>IPK</th>			
						<th align=center><b style=color:#3c8dbc;font-size:20px;text-align:center>: $d[IPK]</b></th>
						</tr>
						
						<tr>  
						<th scope='row' ><b style=color:#3c8dbc;font-size:20px>Predikat</th>
						<th><b style=color:#3c8dbc;font-size:20px;text-align:center>: $d[Predikat]</b></th>
						</tr>	
						
						<tr>   
						<th><b style=color:green> <a href='index.php?ndelox=dep/admyudisium&act=viewnilai&IDYudisium=$d[IDYudisium]&prodi=$_GET[prodi]&MhswID=$d[MhswID]&tahun=$_GET[tahun]' target=_BLANK><i>(Lihat Transkrip)</a></i></b></th>
						</tr>";	
						echo"$label";
									
					echo"</tbody>
					</table>
			</div>				
		</div>

</div>
</div>";
	



echo "<div class='card'>
<div class='card-header'>
		<div class='row'>
				<div class='col-md-6'>
				<table class='table table-condensed table-bordered'>
					<tr style=text-align:left;font-size:18px;color:#FFFFFF;font-weight:reguler;background-color:purple;>
					<td  scope='row' colspan='2' ><b>KERJA PRAKTEK <a href='index.php?ndelox=hasilkp&prodi=$_GET[prodi]&tahun=$_GET[tahun]'>[ndelox]</a></b></td></tr>
					<tr><td scope='row' width='140px'><font style=color:#0b3750;font-size:18px>Judul</td>   		<td><font style=color:#0b3750;font-size:18px> $JudulKP </td></tr>
					<tr><td scope='row'><font style=color:#0b3750;font-size:18px>Pembimbing</td>   	<td><font style=color:#0b3750;font-size:18px>: $Pembimbingkp1, $pmkp1[Gelar] </td></tr>	
					<tr><td scope='row'><font style=color:#0b3750;font-size:18px>TMP Praktek</td>   	<td><font style=color:#0b3750;font-size:18px>: $kp[TempatPenelitian] </td></tr>
									
				</tbody>
				</table>	
				</div>

				<div class='col-md-6'>
				<table class='table table-condensed table-bordered'>
					<tr style=text-align:left;font-size:18px;color:#FFFFFF;font-weight:reguler;background-color:purple;>
					<td  scope='row' colspan='2'><b>SKRIPSI <a href='index.php?ndelox=jadwalskripsi&prodi=SI&tahun=20192'> [ndelox]</a></b></td></tr>
					<tr><td scope='row' width='140px'><font style=color:#0b3750;font-size:18px>Judul</td><td><font style=color:#0b3750;font-size:18px> $JudulPN </td></tr>
					<tr><td scope='row'><font style=color:#0b3750;font-size:18px>Pembimbing 1</td>   	<td><font style=color:#0b3750;font-size:18px>: $Pembimbing1, $p1[Gelar] </td></tr>
					<tr><td scope='row'><font style=color:#0b3750;font-size:18px>Pembimbing 2</td>   	<td><font style=color:#0b3750;font-size:18px>: $Pembimbing2, $p2[Gelar] </td></tr>
				</tbody>
				</table>	
				</div>		  

		</div>
</div>
</div>";


echo"<div class='col-md-12'>
<div class='card'>
<div class='card-header'>
		<table class='table table-condensed table-bordered'>	
		<tr style=text-align:center;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:purple;>
		<th  scope='row' colspan='40'>
		<a href='index.php?ndelox=dep/admyudisium&prodi=$_GET[prodi]&tahun=$_GET[tahun]'>INDEXS</a> <b>[ $_GET[IDYudisium] ]</th></tr><tr>";
		$idx =mysqli_query($koneksi, "select * from t_yudisium WHERE ProdiID='".strfilter($_GET['prodi'])."' and TahunID='".strfilter($_GET['tahun'])."' order by IPK DESC limit 0,20");//
		while($x=mysqli_fetch_array($idx)){
		echo"<td style='text-align:center;font-size:13px'><a href='index.php?ndelox=dep/admyudisium&act=profillulusan&MhswID=$x[MhswID]&IDYudisium=$x[IDYudisium]&prodi=$_GET[prodi]&tahun=$_GET[tahun]'>".substr($x['Nama'],0,5)."</a></td>";
		}		
		echo"</tr>
		<tr>";
		$idx =mysqli_query($koneksi, "select * from t_yudisium WHERE ProdiID='".strfilter($_GET['prodi'])."' and TahunID='".strfilter($_GET['tahun'])."' order by IPK DESC limit 20,20");
		while($x=mysqli_fetch_array($idx)){
		echo"<td style='text-align:center;font-size:13px'><a href='index.php?ndelox=dep/admyudisium&act=profillulusan&MhswID=$x[MhswID]&IDYudisium=$x[IDYudisium]&prodi=$_GET[prodi]&tahun=$_GET[tahun]'>".substr($x['Nama'],0,5)."</a></td>";
		}		
		echo"</tr>
		<tr>";
		$idx =mysqli_query($koneksi, "select * from t_yudisium WHERE ProdiID='".strfilter($_GET['prodi'])."' and TahunID='".strfilter($_GET['tahun'])."' order by IPK DESC limit 40,25");
		while($x=mysqli_fetch_array($idx)){
		echo"<td style='text-align:center;font-size:13px'><a href='index.php?ndelox=dep/admyudisium&act=profillulusan&MhswID=$x[MhswID]&IDYudisium=$x[IDYudisium]&prodi=$_GET[prodi]&tahun=$_GET[tahun]'>".substr($x['Nama'],0,5)."</a></td>";
		}		
		echo"</tr>
		</table>	
	
		
</div>
</div>";
}



elseif($_GET['act']=='profillulusan2'){
$d 			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT mhsw.Nama,
					t_yudisium.IDYudisium,
					t_yudisium.MhswID,
					t_yudisium.IPK,
					t_yudisium.Predikat,
					t_yudisium.Pemuncak,
					t_yudisium.ProdiID,
					t_yudisium.TahunID,
					t_yudisium.Status,
					t_yudisium.file_upload,
					t_yudisium.TglYudisium 
					FROM t_yudisium,mhsw 
					WHERE t_yudisium.MhswID=mhsw.MhswID 
					AND t_yudisium.MhswID='".strfilter($_GET['MhswID'])."'
					order by t_yudisium.IPK DESC"));
$c 			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from mhsw where MhswID='$d[MhswID]'"));
$NamaAyahx 	= strtolower($c['NamaAyah']); //strtoupper($kalimat);
$NamaAyah	= ucwords($NamaAyahx);
$NamaIbux 	= strtolower($c['NamaIbu']); //strtoupper($kalimat);
$NamaIbu	= ucwords($NamaIbux);
$Alamatx 	= strtolower($c['Alamat']); //strtoupper($kalimat);
$Alamat	= ucwords($Alamatx);

$kp  		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM vw_jadwalkp_seminarhasilanggota WHERE MhswID='$d[MhswID]'"));
$pn  		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM vw_jadwal_skripsi WHERE MhswID='$d[MhswID]'"));

$nilaikp 		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_yudisiumdetail where MhswID='".strfilter($_GET['MhswID'])."' AND MKID='PBSI7403'"));
$nilaipn 		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_yudisiumdetail where MhswID='".strfilter($_GET['MhswID'])."' AND MKID='PBSI8604'"));

if ($_GET['prodi']=='SI'){
   $prd="SISTEM INFORMASI";
}else{
   $prd="TEKNIK INFORMATIKA";
}
$Namax 		= strtoupper($d['Nama']);
$Nama 		= ucwords($Namax);

$TmpLahirx 		= strtolower($c['TempatLahir']);
$TmpLahir 		= ucwords($TmpLahirx);

$Judulx 	= strtolower($kp['Judul']);
$JudulKP		= ucwords($Judulx);
$Juduly 	= strtolower($pn['Judul']);
$JudulPN		= ucwords($Juduly);

$p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$pn[PembimbingSkripsi1]'"));
$Pembimbing1x 	= strtolower($p1['Nama']);
$Pembimbing1	= ucwords($Pembimbing1x);
	   
$p2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$pn[PembimbingSkripsi2]'"));	
$Pembimbing2x 	= strtolower($p2['Nama']);
$Pembimbing2	= ucwords($Pembimbing2x);

$kelamin = mysqli_fetch_array(mysqli_query($koneksi, "select Kelamin,Nama from kelamin where Kelamin='$c[Kelamin]'"));

if (trim($c['Foto'])=='-'){
  $foto = 'pto_stud/no-image.jpg';
}else{
  $foto	= 'pto_stud/'.$c['Foto'];
} 

if (($d['IPK']>=2.76) && ($d['IPK']<=3.50)){
	$predikat="Sangat Memuaskan";
}else{
	$predikat="Dengan Pujian";
}

$tgnskrg		= date('Y-m-d');
$data 			= mysqli_fetch_array(mysqli_query($koneksi, "SELECT datediff('$tgnskrg', '$c[TanggalLahir]')as selisih"));
$tahun 			= floor($data['selisih']/365);
$bulan 			= floor(($data['selisih'] - ($tahun * 365))/30);
$hari  			= $data['selisih'] - $bulan * 30 - $tahun * 365;

$maks = mysqli_fetch_array(mysqli_query($koneksi, "select MhswID,Pemuncak from t_yudisium WHERE MhswID='$_GET[MhswID]' AND Pemuncak='Y'"));

if ($maks['Pemuncak']=='Y'){
    $label="<tr>  
			<th scope='row'  width='120px'><b style=color:#FF8000;font-size:25px><img class='img-thumbnail' style='width:100px;' src='img/medals.png'></th>
			<th scope='row'  width='160px'><b style=color:#FF8000;font-size:25px>** CONGRATULATION **</th>			
			</tr>";	
}else{
	$label="<tr>  
			<th scope='row' colspan=2 width='120px'><b style=color:#FF8000;font-size:25px></th>			
			</tr>";
}

echo "   <div class='card'>
<div class='card-header'>
               
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>  
  <table class='table table-condensed table-bordered'>
  <tbody>
  <tr style=text-align:left;font-size:30px;color:#FFFFFF;font-weight:bold;background-color:purple;>
  <th  scope='row' colspan='10' style='text-align:center'>~~~~~ STRATA I  $prd ~~~~~</th></tr>			
  </tbody>
  </table>		
</div>

<div class='box box-info'>
<div class='box-body'>

  <table class='table table-condensed table-bordered' style=text-align:center>
  <tbody>	
  	<tr>  		
	<th style='text-align:center'><b style=color:#3c8dbc;font-size:25px;text-align:center> <img class='img-thumbnail' style='width:160px;height:200px' src='$foto'></b></th>
	</tr>
	<tr style='text-align:center'>  		
	<th style='text-align:center'><b style=color:#3c8dbc;font-size:40px;text-align:center> $Namax</b></th>
	</tr>
	<tr>  		
	<th style='text-align:center'><b style=color:#FF7213;font-size:30px;text-align:center> ~ IPK: $d[IPK] ~</b></th>
	</tr>
	<tr>  		
	<th style='text-align:center'><b style=color:#3c8dbc;font-size:35px;text-align:center>$TmpLahir, ".tgl_indo($c['TanggalLahir'])."</b></th>
	</tr>
	<tr>  		
	<th style='text-align:center'><b style=color:#3c8dbc;font-size:18px;text-align:center>$Alamat</b></th>
	</tr>
	<tr>  		
	<th style='text-align:center'><b style=color:#3c8dbc;font-size:18px;text-align:center> Orang Tua : $NamaAyah dan $NamaIbu</b></th>
	</tr>
";		
  echo"</tbody>
  </table>
			

<table class='table table-condensed table-bordered'>	
<tr style=text-align:center;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:purple;>
<th  scope='row' colspan='40' style='text-align:center'>
~~<a href='index.php?ndelox=dep/admyudisium&prodi=$_GET[prodi]&tahun=$_GET[tahun]'> INDEXS</a> <b> [ NO URUT: $_GET[IDYudisium] ] ~~</th></tr><tr>";
$idx =mysqli_query($koneksi, "select * from t_yudisium WHERE ProdiID='".strfilter($_GET['prodi'])."' and TahunID='".strfilter($_GET['tahun'])."' order by IPK DESC limit 1,25");//limit 1,13
while($x=mysqli_fetch_array($idx)){
echo"<td><a href='index.php?ndelox=dep/admyudisium&act=profillulusan2&MhswID=$x[MhswID]&IDYudisium=$x[IDYudisium]&prodi=$_GET[prodi]&tahun=$_GET[tahun]'>".substr($x['Nama'],0,4)."</a></td>";
}		
echo"</tr>
<tr>";
$idx =mysqli_query($koneksi, "select * from t_yudisium WHERE ProdiID='".strfilter($_GET['prodi'])."' and TahunID='".strfilter($_GET['tahun'])."' order by IPK DESC limit 26,25");//jika lebih dari 25 colom
while($x=mysqli_fetch_array($idx)){
echo"<td><a href='index.php?ndelox=dep/admyudisium&act=profillulusan2&MhswID=$x[MhswID]&IDYudisium=$x[IDYudisium]&prodi=$_GET[prodi]&tahun=$_GET[tahun]'>".substr($x['Nama'],0,4)."</a></td>";
}		
echo"</tr>
<tr>";
$idx =mysqli_query($koneksi, "select * from t_yudisium WHERE ProdiID='".strfilter($_GET['prodi'])."' and TahunID='".strfilter($_GET[tahun])."' order by IPK DESC limit 50,25");//jika lebih dari 25 colom
while($x=mysqli_fetch_array($idx)){
echo"<td><a href='index.php?ndelox=dep/admyudisium&act=profillulusan2&MhswID=$x[MhswID]&IDYudisium=$x[IDYudisium]&prodi=$_GET[prodi]&tahun=$_GET[tahun]'>".substr($x['Nama'],0,4)."</a></td>";
}		
echo"</tr>
</table>	
</div>";
}	

else if($_GET['act']=='predikat'){?>
<script type="text/javascript" src="plugins/jQuery/jquery.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('#container').highcharts({
            data: {
                table: 'datatable'
            },
            chart: {
                type: 'pie'
            },
            title: {
                text: ''
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: ''
                }
            },
            tooltip: {
                formatter: function () {
                    return '<b> ' + this.series.name + '</b><br/>' +
                        ' ' + this.point.y + ' %';
                }
            }
        });
    });
</script>

<div class='col-xs-12'>
<div class='box box-info'>
<div class='box-header'>
    <h3 class="box-title"><b style=color:#FF8000;font-size:25px>Grafik Predikat Lulusan</b></h3>
        <div class="box-tools pull-right">
           <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
        </div>
        </div>

<div class="box-body chat" id="chat-box">
    <script src="plugins/highchart/highcharts.js"></script>
    <script src="plugins/highchart/modules/data.js"></script>
    <script src="plugins/highchart/modules/exporting.js"></script>
    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<table id="datatable" style='display:none'>
    <thead>
        <tr>
            <th></th>
            <th>Predikat Lulusan</th>          
        </tr>
    </thead>
    <tbody>
    <?php 
        $grafik = mysqli_query($koneksi, "SELECT * FROM t_predikat  GROUP BY Predikat ORDER BY PredikatID ASC LIMIT 7");
        while ($r = mysqli_fetch_array($grafik)){
            $ale = $r['Predikat'];
            $predik = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM t_yudisium where Predikat='$r[Predikat]' and ProdiID='".strfilter($_GET['prodi'])."' and TahunID='".strfilter($_GET['tahun'])."'"));
            $totMhs = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM t_yudisium where ProdiID='".strfilter($_GET['prodi'])."' and TahunID='".strfilter($_GET['tahun'])."'"));
            //$totMhs=12;
			$persen			= number_format(($predik/$totMhs)* 100,0);			
			echo "<tr>
                    <th>$ale</th>
                    <td>$persen</td>
                  </tr>";
        }
    ?>
    </tbody>
</table>
</div>
</div><!-- /.chat -->
</div><!-- /.box (chat box) -->

<?php
}
?>

