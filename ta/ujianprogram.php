<div class="card">
<div class="card-header">
<div class="card-tools">
<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=ta/jadwalskripsi&tahun=<?php echo $_GET['tahun']; ?>&prodi=<?php echo "$_GET[prodi]"; ?>&program=<?php echo 	"$_GET[program]"; ?>'>JADWAL SEMINAR HASIL</a>
</div>  

<div class="form-group row">
<label class="col-md-7 col-form-label text-md-left"><b style='color:purple'>Setting Penguji Ujian Program</b></label>

<div class="col-md-2 "> 
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='ta/ujianprogram'>
<select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
<?php 
echo "<option value=''>- Pilih Tahun Akademik -</option>";
$tahun = mysqli_query($koneksi, "SELECT distinct(TahunID), ProdiID,NA FROM tahun order by TahunID DESC"); //NA='N' and
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
$prodi = mysqli_query($koneksi, "SELECT * from prodi where ProdiID='SI' or ProdiID='TI'");
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
</form>
</div>
</div>
</div>
</div>

<?php if ($_GET['act']==''){ 	                                   											   												
echo"
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>							  							
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table class='table table-condensed table-bordered table-striped'>                                          
<thead>					 
<tr>
<th>No</th>                        				
<th>NIDN</th>
<th>Nama Dosen</th>
<th>Handphone</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>";
						
$tampil = mysqli_query($koneksi, "SELECT
			dosen.Nama,dosen.Gelar,dosen.Handphone,
			jadwal_uppenguji.PengujiID,jadwal_uppenguji.UjianID,jadwal_uppenguji.DosenID,
			jadwal_uptahun.TahunID,
			jadwal_uptahun.ProdiID,jadwal_uptahun.TglUjian,jadwal_uptahun.JamMulai,
			jadwal_uptahun.JamSelesai
			FROM dosen,jadwal_uppenguji,jadwal_uptahun
			WHERE dosen.Login=jadwal_uppenguji.DosenID
			AND jadwal_uptahun.UjianID=jadwal_uppenguji.UjianID
			AND jadwal_uptahun.ProdiID='".strfilter($_GET['prodi'])."'
			AND jadwal_uptahun.TahunID='".strfilter($_GET['tahun'])."'
			"); 									
while($r=mysqli_fetch_array($tampil)){   					         
$jml=mysqli_num_rows(mysqli_query("select * from jadwal_upmhs where PengujiID='$r[PengujiID]'"));
$no++;
echo "<tr bgcolor=$warna>
<td>$no</td>
<td>$r[DosenID] </td>
<td>$r[Nama], $r[Gelar] 
&nbsp;&nbsp;&nbsp;&nbsp;| <a href='index.php?ndelox=ta/ujianprogram&act=tambahdata&tahun=$_GET[tahun]&prodi=$_GET[prodi]&PengujiID=$r[PengujiID]'> Add Peserta</a>
| <a href='print_report/print-frmnilaiup.php?tahun=$_GET[tahun]&prodi=$_GET[prodi]&PengujiID=$r[PengujiID]&UjianID=$r[UjianID]' target=_BLANK> Cetak Form Nilai</a>
| <a href='print_report/print-absensiup.php?tahun=$_GET[tahun]&prodi=$_GET[prodi]&PengujiID=$r[PengujiID]&UjianID=$r[UjianID]' target=_BLANK> Cetak Daftar Hadir</a>
| <a href='index.php?ndelox=ta/ujianprogram&act=viewpenguji&tahun=$_GET[tahun]&prodi=$_GET[prodi]&PengujiID=$r[PengujiID]'> View Peserta ($jml Orang)</a> |
</td> 
<td>$r[Handphone]</td>
<td>
<center>                                
<a class='btn btn-danger btn-xs' title='Delete' href='?ndelox=ta/ujianprogram&hapus=$r[PengujiID]&UjianID=$r[UjianID]&tahun=$_GET[tahun]&prodi=$_GET[prodi]' 
onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-trash'></i></a>
</center>
</td>
</tr>";
 }

if (isset($_GET['hapus'])){
	mysqli_query($koneksi, "DELETE FROM jadwal_uppenguji where PengujiID='".strfilter($_GET['hapus'])."'");
    echo "<script>document.location='index.php?ndelox=ta/ujianprogram&PengujiID=$_GET[PengujiID]&tahun=$_GET[tahun]&prodi=$_GET[prodi]&sukses';</script>";
}
echo"<tr bgcolor=$warna><td colspan='7'></td></tr>";	
echo "</tbody></table></div>";
echo "<div class='box-footer'></div>";
echo "</form>
</div>
</div>
</div>";

//===================================================================================================================================
}else if ($_GET[act]=='tambahdata'){ 
$penguji = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jadwal_uppenguji WHERE PengujiID='".strfilter($_GET['PengujiID'])."'"));
$dos = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar,Handphone FROM dosen WHERE Login='$penguji[DosenID]'"));
echo"
<div class='col-md-8'>
<div class='box box-info'>
<div class='box-header'>						
<div class='col-md-8'>			
<table class='table table-condensed table-bordered'>
<tbody>              
<tr>
	<th width='200px'>Dosen Penguji</th><th>$dos[Nama], $dos[Gelar]</th>
</tr>
<tr>	
	<th width='200px'>Handphone</th><th>$dos[Handphone]</th>   
</tr>												
</tbody>
</table>
</div>
</div>										   
</div>";

echo"

<div class='box box-info'>
<div class='box-header'>						
<div class='col-md-9'>	
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>

<input type='hidden' name='prodi' value='$_GET[prodi]'>	
<input type='hidden' name='tahun' value='$_GET[tahun]'>
<input type='hidden' name='PengujiID' value='$_GET[PengujiID]'>							 
<table class='table table-condensed table-bordered table-striped'>                      
<thead>					 
  <tr>
	<th style='width:30'>No</th>                        				
	<th style='width:30'>NIM</th>
	<th style='width:400'>Nama Mahasiswa</th>
	<th style='width:100'>Pilih</th>
  </tr>
</thead>
<tbody>";
/*	*/	
$no = 1;								
$tampil = mysqli_query($koneksi, "SELECT mhsw.Nama,jadwal_skripsi.* 
				FROM mhsw,jadwal_skripsi
				WHERE mhsw.MhswID=jadwal_skripsi.MhswID 
				AND jadwal_skripsi.TahunID='".strfilter($_GET['tahun'])."' 
				AND jadwal_skripsi.ProdiID='".strfilter($_GET['prodi'])."'
				order by mhsw.Nama asc"); // 
while($r=mysqli_fetch_array($tampil)){   					         
$jml=mysqli_num_rows(mysqli_query("select * from jadwal_upmhs where MhswID='$r[MhswID]'"));
$p1 =mysqli_fetch_array(mysqli_query("select Login,Nama,Gelar from dosen where Login='$r[PembimbingSkripsi1]'"));
$p2 =mysqli_fetch_array(mysqli_query("select Login,Nama,Gelar from dosen where Login='$r[PembimbingSkripsi2]'"));
echo "<tr bgcolor=$warna>
<td>$no</td>				
<td>$r[MhswID]</td> 
<td>$r[Nama] ($jml) - <b style=color:green;font-size:12px;font-style:italic;>(1. $p1[Nama], $p1[Gelar] &nbsp; 2. $p2[Nama], $p1[Gelar])</b></td>
<td>				
<input type='checkbox' value='$r[MhswID]' name='MhswID$no'/>
</td>
</tr>";
$no++;
}

echo "</tbody>
</table>";
echo"<input type='hidden' name='JumData' value='$no-1'>";
echo "<div class='box-footer'>
<button type='submit' name='simpann' class='btn btn-info pull-right'>Simpan Data</button>                      
</div>";
echo "</form>";
	
if (isset($_POST['simpann'])){             
	$TahunID   = $_POST['TahunID'];
	$JumData   = $_POST['JumData'];
	
for($i = 1; $i <= $JumData; $i++){
	$MhswID  	= $_POST['MhswID'.$i];   
		if (!empty($MhswID)){     
			$sqlcek = mysqli_query($koneksi, "SELECT * FROM jadwal_upmhs WHERE MhswID='$MhswID' and PengujiID='$_POST[PengujiID]'");
			if (mysqli_num_rows($sqlcek)>0){
				echo "<script language='javascript'>alert('Data : $MKID sudah ada');
				window.location = 'index.php?ndelox=ta/ujianprogram&PengujiID=$_POST[PengujiID]&tahun=$_POST[tahun]&prodi=$_POST[prodi]&gagal'</script>";
				exit;
			}
		$query = mysqli_query("INSERT INTO jadwal_upmhs 
							(PengujiID,
							MhswID,
							LoginBuat,
							TglBuat)
					 VALUES('".strfilter($_POST['PengujiID'])."',					
							'$MhswID',
							'".$_SESSION['_Login']."',
							'".date('Y-m-d')."')");	 											
		}
	}		

	if ($query){
		echo "<script>document.location='index.php?ndelox=ta/ujianprogram&act=viewpenguji&PengujiID=$_POST[PengujiID]&prodi=$_POST[prodi]&tahun=$_POST[tahun]&sukses';</script>";                       
	}else{
		echo "<script>document.location='index.php?ndelox=ta/ujianprogram&act=viewpenguji&PengujiID=$_POST[PengujiID]&prodi=$_POST[prodi]&tahun=$_POST[tahun]&gagal';</script>";
	}

		
}
		  

} //tutup isset simpan

else if ($_GET['act']=='viewpenguji'){ 
$penguji = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jadwal_uppenguji WHERE PengujiID='".strfilter($_GET['PengujiID'])."'"));
$dos = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar,Handphone FROM dosen WHERE Login='$penguji[DosenID]'"));
echo"
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>		
<table class='table table-condensed table-bordered'>
<tbody>              
<tr>
	<th width='200px'>Dosen Penguji</th><th>$dos[Nama], $dos[Gelar]</th>
</tr>
<tr>	
	<th width='200px'>Handphone</th><th>$dos[Handphone]</th>   
</tr>												
</tbody>
</table>
</div>
</div>										   
</div>";	                                   											   												
echo"<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>							  							
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>                     
<table class='table table-condensed table-bordered table-striped'>                      
<thead>					 
  <tr>
	<th>No</th>                        				
	<th>NIM</th>
	<th>Nama Mahasiswa</th>
	<th>Aksi</th>
  </tr>
</thead>
<tbody>";
	
$no = 1;					
$tampil = mysqli_query($koneksi, "SELECT
mhsw.Nama,
jadwal_upmhs.*
from mhsw,jadwal_upmhs
WHERE mhsw.MhswID=jadwal_upmhs.MhswID
AND jadwal_upmhs.PengujiID='".strfilter($_GET['PengujiID'])."'"); 									
while($r=mysqli_fetch_array($tampil)){   					         
echo "<tr bgcolor=$warna>
<td>$no</td>
<td>$r[MhswID] </td>
<td>$r[Nama]</td> 

<td>
<center>                                
<a class='btn btn-danger btn-xs' title='Delete' href='?ndelox=ta/ujianprogram&act=viewpenguji&hapus=$r[IDX]&PengujiID=$r[PengujiID]&tahun=$_GET[tahun]&prodi=$_GET[prodi]' 
onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-trash'></i></a>
</center>
</td>
</tr>";
$no++;
 }

if (isset($_GET['hapus'])){
	mysqli_query($koneksi, "DELETE FROM jadwal_upmhs where IDX='".strfilter($_GET['hapus'])."'");
    echo "<script>document.location='index.php?ndelox=ta/ujianprogram&act=viewpenguji&PengujiID=$_GET[PengujiID]&tahun=$_GET[tahun]&prodi=$_GET[prodi]&sukses';</script>";
}
echo"<tr bgcolor=$warna><td colspan='7'></td></tr>";	
echo "</tbody></table>";
echo "<div class='box-footer'></div>";
echo "</form>
</div>
</div>
</div>";
}

?>