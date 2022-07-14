<div class="card">
<div class="card-header">
<h3 class="box-title"><b style='color:green;font-size:20px'>KARTU HASIL STUDI</b></h3>              
<?php
$ss       = mysqli_fetch_array(mysqli_query($koneksi, "SELECT KHSID,Sesi,TahunID,MhswID FROM khs where MhswID='".strfilter($_GET['MhswID'])."' and TahunID='".strfilter($_GET['tahun'])."'"));

$m = mysqli_fetch_array(mysqli_query($koneksi, "SELECT mhsw.MhswID, mhsw.Nama AS NamaMhs, 
mhsw.ProdiID, mhsw.ProgramID, 
prodi.Nama AS NamaProdi 
FROM mhsw,prodi,program 
WHERE mhsw.ProdiID=prodi.ProdiID
AND mhsw.ProgramID=program.ProgramID
AND mhsw.MhswID='".strfilter($_GET['MhswID'])."'")); 


?>                  
<?php 
if (isset($_GET['MhswID'])){ ?>

<?php } 
?>

<div class="form-group row">
<label class="col-md-6 col-form-label text-md-right"><b style='color:purple'>CETAK KHS</b></label>
<div class="col-md-2">
  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='dep/khscetak2'>
<select name='tahun'class='form-control form-control-sm' onChange='this.form.submit()'>
<?php 
echo "<option value=''>Tahun Akademik</option>";
$tahun = mysqli_query($koneksi, "SELECT distinct(TahunID) FROM tahun order by TahunID Desc"); //NA='N' and
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
<input type='text'  class='form-control form-control-sm' name='MhswID' placeholder="NIM"  value='<?php echo"$_GET[MhswID]";?>'></td>
</div>      
                            
<div class="col-md-2">
	<input type="submit" class='btn btn-success btn-sm' value='Lihat'>
  </form>
  <a class='pull-right btn btn-primary btn-sm' href='?ndelox=dep/nilaitranskrip&MhswID=<?php echo"$_GET[MhswID]";?>&prodi=<?php echo"$m[ProdiID]";?>&tahun=<?php echo"$_GET[tahun]";?>' target='_BLANK'>Transkrip</a>
<a class='pull-right btn btn-primary btn-sm' href='print_report/print-khs2.php?MhswID=<?php echo"$_GET[MhswID]";?>&prodi=<?php echo"$m[ProdiID]";?>&tahun=<?php echo"$_GET[tahun]";?>' target='_BLANK'>Cetak KHS</a>

</div>

</div>
</div>

<?php if ($_GET['act']==''){ 	                                        											   																	              								
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

echo"<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>							  							 

	<input type='hidden' name='TahunID' value='$_GET[tahun]'>
	<input type='hidden' name='MhswID' value='$_GET[MhswID]'>
	<input type='hidden' name='prodi' value='$r[ProdiID]'>			
	<div class='card-body'>
	<div class='table-responsive'>
	<table id='example' class='table table-sm table-bordered table-striped'>
	<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:purple;width:100%>
	<th scope='row' colspan=5 height=10></th>	
	</tr>
	<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:purple;>
	<th scope='row' style='width:150px'>&nbsp;&nbsp;Nama</th><th>: $m[NamaMhs]</th>
	<th width='80px'></th>
	<th>NIM </th>
	<th>: $m[MhswID]</th>
	</tr>
							
	<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:purple;width:100%>
	<th scope='row' >&nbsp;&nbsp;Program</th>
	<th>: $m[ProgramID] - $prod</td>
	<th width='80px'></th>
	<th scope='row' style='width:150px'>Tahun/Semester</th> 
	<th>: $_GET[tahun]/$ss[Sesi]</th>
	</tr> 
	<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:purple;width:100%>
	<th scope='row' colspan=5  height=10></th>	
	</tr> 
	</table></div>
	
	<div class='table-responsive'>
<table id='example' class='table table-sm table-bordered table-striped'>                   
	<thead>					 
	<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:purple;width:100%;>
	<th width='50px'>No</th>                        
	<th width='100px'>KODE</th>
	<th width='700px'>MATAKULIAH</th>
	<th style=text-align:center>SKS</th>
	<th style=text-align:center>HURUF</th>
	<th style=text-align:center>BOBOT</th>
	</tr>
	</thead>
	<tbody>";
	/*echo"<th>Aksi</th>
	</tr>
	</thead>
	<tbody>";*/
									
$no = 1;									
$tampil = mysqli_query($koneksi, "SELECT
				  krs.KRSID      AS KRSID,
				  krs.KHSID      AS KHSID,
				  krs.TahunID    AS TahunID,
				  krs.MhswID     AS MhswID,
				  mhsw.Nama      AS NamaMhs,
				  mk.Nama        AS NamaMK,
				  krs.Tugas1     AS Tugas1,
				  krs.Tugas2     AS Tugas2,
				  krs.Tugas3     AS Tugas3,
				  krs.Tugas4     AS Tugas4,
				  krs.Tugas5     AS Tugas5,
				  krs.Presensi   AS Presensi,
				  krs.UTS        AS UTS,
				  krs.UAS        AS UAS,
				  krs.NilaiAkhir AS NilaiAkhir,
				  krs.GradeNilai AS GradeNilai,
				  krs.BobotNilai AS BobotNilai,
				  krs.SKS        AS SKS,
				  mk.MKID        AS MKID,
				  mk.MKKode      AS MKKode,
				  mk.Sesi        AS Sesi,
				  mhsw.ProdiID   AS ProdiID,
				  mhsw.ProgramID AS ProgramID
				  FROM mhsw,krs,mk
				  WHERE krs.MhswID=mhsw.MhswID
				  AND krs.MKID=mk.MKID 
				  AND mhsw.MhswID='".strfilter($_GET['MhswID'])."'
				  AND krs.TahunID='".strfilter($_GET['tahun'])."'
				  ORDER BY mk.Nama ASC");  								
while($r=mysqli_fetch_array($tampil)){   					         
$nilai = $r['NilaiAkhir'];
if ($nilai >= 85 AND $nilai <= 100){
						$huruf = "A";
						$bobot = "4";
}
elseif ($nilai >= 80 AND $nilai <= 84.99){
	$huruf = "A-";
	$bobot = "3.70";
}
elseif ($nilai >= 75 AND $nilai <= 79.99){
	$huruf = "B+";
	$bobot = "3.30";
}
elseif ($nilai >= 70 AND $nilai <= 74.99){
	$huruf = "B";
	$bobot = "3";
}
elseif ($nilai >= 65 AND $nilai <= 69.99){
	$huruf = "B-";
	$bobot = "2.70";
}
elseif ($nilai >= 60 AND $nilai <= 64.99){
	$huruf = "C+";
	$bobot = "2.30";
}
elseif ($nilai >= 55 AND $nilai <= 59.99){
	$huruf = "C";
	$bobot = "2";
}
elseif ($nilai >= 50 AND $nilai <= 54.99){
	$huruf = "C-";
	$bobot = "1.70";
}
elseif ($nilai >= 40 AND $nilai <= 49.99){
	$huruf = "D";
	$bobot = "1";
}
elseif ($nilai < 40){
	$huruf = "E";
	$bobot = "0";
}

//$huruf= $r[GradeNilai];
/*if ($huruf=='A'){
	$bobot=4;
}
elseif ($huruf=='B'){
	$bobot=3;
}
elseif ($huruf=='C'){
	$bobot=2;
}
elseif ($huruf=='D'){
	$bobot=1;
}

else{
    $bobot=0; 
}*/

$total_sks 	  	= $r['SKS'];
$total_bobot  	= $r['SKS'] * $bobot;

$tsks 			+= $total_sks;
$tbobottotal 	+= $total_bobot;
$ips = number_format($tbobottotal / $tsks,2);

if ($ips >= 3.00) {
	$YAD=24;
	}
if ($ips < 3.00) {
	$YAD=21;
	}
if ($ips <= 2.49) {
	$YAD=18;
	}
if ($ips <= 1.99) {
	$YAD=15;
	}
if ($ips <= 1.4) {
	$YAD=12;
	}

	echo "<tr bgcolor=$warna>
	<td>$no</td>
	<td>$r[MKKode]</td>
	<td>$r[NamaMK]</td> 
	<td align=center>$r[SKS]</td>
	<td align=center>$r[GradeNilai]</td>
	<td align=center>$r[BobotNilai]</td>";
	
	/*echo"<td>
	<center>                                
	<a class='btn btn-danger btn-xs' title='Delete' href='?ndelox=khscetak2&hapus=$r[KRSID]&JadwalID=".$_GET[JadwalID]."&tahun=".$_GET[tahun]."&MhswID=".$_GET[MhswID]."' 
	onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
	</center>
	</td>
	</tr>";*/
$no++;
//$tsks += $r[SKS];
}

if (isset($_GET['hapus'])){
	mysqli_query("DELETE FROM krs_matikanduluya where KRSID='".strfilter($_GET['hapus'])."'");
    echo "<script>document.location='index.php?ndelox=khscetak2&JadwalID=".strfilter($_GET['JadwalID'])."&tahun=".strfilter($_GET['tahun'])."&MhswID=".strfilter($_GET['MhswID'])."&sukses';</script>";
}

mysqli_query($koneksi,"UPDATE khs set IPS='$ips' WHERE MhswID='".strfilter($_GET['MhswID'])."' AND TahunID='".strfilter($_GET['tahun'])."'");

echo"<tr>
	<td colspan=3><b>IPS: $ips</b> &nbsp;&nbsp;&nbsp;&nbsp;<b>YAD: $YAD SKS</b> </td><td style=text-align:center><b>$tsks SKS</b> </td><td></td><td style=text-align:center><b>$tbobottotal</b></td>
</tr>";	
echo "</tbody>
</table></div>";
								  
echo "<div class='box-footer'>
                     
</div>";
echo "</form>
</div>
</div>
</div>";


}elseif($_GET['act']=='carimhs'){
?>
<div class="col-xs-12">
<div class='box box-info'>
<div class="box-header">

<h3 class="box-title">FILTER DATA </h3>
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
<select name='program' style='padding:4px'>
<?php 
	echo "<option value=''>- Pilih Program -</option>";
	$program = mysqli_query($koneksi, "SELECT * FROM program");
	while ($k = mysqli_fetch_array($program)){
	 if ($_POST['program']==$k['ProgramID']){
		echo "<option value='$k[ProgramID]' selected>$k[ProgramID] - $k[Nama]</option>";
	  }else{
		echo "<option value='$k[ProgramID]'>$k[ProgramID] - $k[Nama] </option>";
	  }
	}
?>
</select>
<select name='prodi' style='padding:4px'>
<?php 
	echo "<option value=''>- Pilih Program Studi -</option>";
	$prodi = mysqli_query($koneksi, "SELECT * from prodi where ProdiID='SI' or ProdiID='TI'");
	while ($k = mysqli_fetch_array($prodi)){
	   if ($_POST['prodi']==$k['ProdiID']){
		echo "<option value='$k[ProdiID]' selected>$k[Nama]</option>";
	  }else{
		echo "<option value='$k[ProdiID]'>$k[Nama]</option>";
	  }
	}
?>
</select>

<button type='submit' name='caribro' style='margin-top:-4px' class='btn btn-success btn-sm'>Go</button>
</form>
</div>
</div>
</div>

<?php
if (isset($_POST['caribro'])){
?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">DATA MAHASISWA </h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No</th>                       
						<th>MhswID</th>
                        <th>Nama</th>
                        <th>Program Studi</th>  
						<th>Status</th>  			                      
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                     if ($_POST['program']!='' AND $_POST['prodi'] != ''){
						$tampil = mysqli_query($koneksi, "SELECT * FROM view_mhs where ProgramID='".strfilter($_POST['program'])."' and ProdiID='".strfilter($_POST['prodi'])."'");
					 }
					 else if ($_POST['program']=='' AND $_POST['prodi'] != ''){
						$tampil = mysqli_query($koneksi, "SELECT * FROM view_mhs where ProdiID='".strfilter($_POST['prodi'])."'");						 
					} else if ($_POST['program']!='' AND $_POST['prodi'] == ''){
						$tampil = mysqli_query($koneksi, "SELECT * FROM view_mhs where ProgramID='".strfilter($_POST['program'])."'");
					}else{
					    $tampil = mysqli_query($koneksi, "SELECT * FROM view_mhsx");
					}
					
					$no = 1;
                    while($r=mysqli_fetch_array($tampil)){
                    $status = $r['StatusMhswID'];
					if ($status=='A'){
						$ket ="Aktif";
					}
					else if ($status=='C'){
						$ket ="Cuti";
					}
					else if ($status=='P'){
						$ket ="Pasif";
					}
					else if ($status=='K'){
						$ket ="Keluar";
					}
					else if ($status=='D'){
						$ket ="Drop-Out";
					}
					else if ($status=='L'){
						$ket ="Lulus";
					}
					else if ($status=='T'){
						$ket ="Tunggu Ujian";
					}
					else if ($status=='W'){
						$ket ="Tunggu Wisuda";
					}		
					else{
						$Ket="SkorSing";
					}
		echo "<tr>
		<td>$no</td>
		<td>$r[MhswID]</td>
		<td>$r[NamaMahasiswa]</td>
		<td>$r[NamaProdi]</td>
		<td>$ket</td>
		</tr>";
		$no++;
		}
		
		?>
		</tbody>
		</table>
		</div><!-- /.box-body -->
		</div><!-- /.box -->
		</div>
        <?php
	} //isset($_POST[caribro])	

}
 //tutup atas
?>