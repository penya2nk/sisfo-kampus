<div class='card'>
<div class='card-header'>

<div class="box-header">
<h3 class="box-title"><b style='color:green;font-size:20px'>Angket Evaluasi Pembelajaran Jarak Jauh (PJJ) STMIK HTP</b></h3>  
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='spmi/ppmi-angketadmpjj'>
<select name='tahun' style='padding:4px' onChange='this.form.submit()'>
<?php 
echo "<option value=''>- Pilih Tahun Akademik -</option>";
$tahun = mysqli_query($koneksi, "SELECT distinct(TahunID), ProdiID,NA FROM tahun where ProdiID='SI' order by TahunID Desc"); //NA='N' and
while ($k = mysqli_fetch_array($tahun)){
	if ($_GET['tahun']==$k['TahunID']){
		echo "<option value='$k[TahunID]' selected>$k[TahunID]</option>";
	}else{
		echo "<option value='$k[TahunID]'>$k[TahunID]</option>";
	}
}
?>  
</select>
<select name='prodi' style='padding:4px' onChange='this.form.submit()'>
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
<input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
</form>
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
<th style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>No</th>                        				
<th style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>Kategori Responden</th>
<th style=text-align:center;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>Aksi</th>
</tr>
</thead>
<tbody>";
						
$tampil = mysqli_query($koneksi, "SELECT * from t_ppmiangketpjj Group by RespondenKategori"); 									
while($r=mysqli_fetch_array($tampil)){ 
$responden = mysqli_num_rows(mysqli_query($koneksi, "SELECT * from t_ppmiangketpjj Group By MhswID"));   					         
$no++;
echo "<tr bgcolor=$warna>
<td>$no</td>
<td>$r[RespondenKategori] </td>

<td>
<center>                                

<a href='index.php?ndelox=spmi/ppmi-angketadmpjj&act=viewangket&tahun=$_GET[tahun]&prodi=$_GET[prodi]&JadwalID=$r[JadwalID]'> View Data ($responden Orang responden)</a>
| <a href='print_reportxls/angketpjjxls.php?tahun=$r[TahunID]&prodi=$_GET[prodi]&JadwalID=$r[JadwalID]&DosenID=$r[DosenID]&MKID=$r[MKID]' target=_BLANK> Exp XLS</a>
</center>
</td>
</tr>";
 }

if (isset($_GET['hapus'])){
	mysqli_query("DELETE FROM jadwal_uppengujixx where JadwalID='".strfilter($_GET['hapus'])."'");
    echo "<script>document.location='index.php?ndelox=spmi/ppmi-angketadmpjj&JadwalID=$_GET[JadwalID]&tahun=$_GET[tahun]&prodi=$_GET[prodi]&sukses';</script>";
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
$dos = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar,Handphone FROM dosen WHERE Login='".strfilter($_GET['DosenID'])."'"));
$mk = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MKID,MKKode,Nama,SKS,Sesi FROM mk WHERE MKID='".strfilter($_GET['MKID'])."'"));
$prd = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama FROM prodi WHERE ProdiID='".strfilter($_GET['prodi'])."'"));
echo"
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table class='table table-condensed table-bordered'>
<tbody>              
<tr>
<th width='200px' style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>Dosen Pengampu</th>
<th style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>$dos[Nama], $dos[Gelar]</th>
</tr>

<tr>	
<th width='200px' style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>Matakuliah</th>
<th style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>$mk[Nama] ( $mk[SKS] SKS ) - Semester $mk[Sesi]</th>   
</tr>	

<tr>	
<th width='200px' style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>Program Studi</th>
<th style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>$prd[Nama]</th>   
</tr>

<tr>	
<th width='200px' style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>Tahun Akademik</th>
<th style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>$_GET[tahun]</th>   
</tr>											
</tbody>
</table></div>
</div>
</div>										   
</div>";

echo"
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
<input type='hidden' name='JadwalID' value='$_GET[JadwalID]'>
<input type='hidden' name='prodi' value='$_GET[prodi]'>	
<input type='hidden' name='tahun' value='$_GET[tahun]'>
<input type='hidden' name='JadwalID' value='$_GET[JadwalID]'>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table class='table table-condensed table-bordered table-striped'>                      
<thead>					 
  <tr>
	<th>No</th>                        				
	<th>Pertanyaan</th>
	<th colspan=5 style=text-align:left>Pilihan Jawaban</th>
  </tr>
</thead>
<tbody>";
/*	*/	
$no = 1;								
$tampil = mysqli_query($koneksi, "SELECT * from t_ppmipertanyaan"); // 
while($r=mysqli_fetch_array($tampil)){   					         
echo "<tr bgcolor=$warna>
<td>$no</td>				
<td width=500px>$r[Pertanyaan]</td>
	
<input type='hidden' value='$r[PertanyaanID]' name='PertanyaanID$no'/>			
<td>
<input type='radio' value='1' name='Jawaban$no'/> 1. $r[Pil1]<br>
<input type='radio' value='2' name='Jawaban$no'/> 2. $r[Pil2]<br>
<input type='radio' value='3' name='Jawaban$no'/> 3. $r[Pil3]<br>
<input type='radio' value='4' name='Jawaban$no'/> 4. $r[Pil4]<br>
</td>

</tr>";
$no++;
} //<input type='checkbox' value='$r[id_tanya]' name='id_tanya$no'/>

echo "</tbody>
</table></div>";
echo"<input type='hidden' name='JumData' value='$no-1'>";
echo "<div class='box-footer'>
<button type='submit' name='simpann' class='btn btn-info pull-right'>Isi Angket</button>                      
</div>";
echo "</form>";
	
if (isset($_POST['simpann'])){
    $JadwalID  	= strfilter($_POST['JadwalID']);             
	$tahun   	= strfilter($_POST['tahun']);
	$prodi   	= strfilter($_POST['prodi']);
	$JumData    = strfilter($_POST['JumData']);
	
for($i = 1; $i <= $JumData; $i++){
	$PertanyaanID  	= strfilter($_POST['PertanyaanID'.$i]);
    $Jawaban  		= strfilter($_POST['Jawaban'.$i]);	
		if (!empty($PertanyaanID)){     
			$sqlcek = mysqli_query($koneksi, "SELECT * FROM t_ppmiangket WHERE MhswID='$_SESSION[id]' and JadwalID='$JadwalID' and PertanyaanID='$PertanyaanID'");
			if (mysqli_num_rows($sqlcek)>0){
				echo "<script language='javascript'>alert('Data : Pengisian angket untuk data perkuliahan tersebut sudah dilakukan!');
				window.location = 'index.php?ndelox=spmi/ppmi-angketadmpjj&JadwalID=$_POST[JadwalID]&tahun=$_POST[tahun]&prodi=$_POST[prodi]&gagal'</script>";
				exit;
			}
		$query = mysqli_query("INSERT INTO t_ppmiangket 
						   (JadwalID,
						    MhswID,
							PertanyaanID,
							Jawaban,
							TahunID,
							ProdiID)
					 VALUES('$JadwalID',
							'$_SESSION[_Login]',
							'$PertanyaanID',
							'$Jawaban',
							'$tahun',
							'$prodi')");	 											
		}
	}		

	if ($query){
		echo "<script>document.location='index.php?ndelox=spmi/ppmi-angketadmpjj&act=viewangket&JadwalID=$_POST[JadwalID]&prodi=$_POST[prodi]&tahun=$_POST[tahun]&MhswID=$_SESSION[id]&sukses';</script>";                       
	}else{
		echo "<script>document.location='index.php?ndelox=spmi/ppmi-angketadmpjj&act=viewangket&JadwalID=$_POST[JadwalID]&prodi=$_POST[prodi]&tahun=$_POST[tahun]&MhswID=$_SESSION[id]&gagal';</script>";
	}

		
}
		  

} //tutup isset simpan

else if ($_GET['act']=='viewangket'){ 
$jdw = mysqli_fetch_array(mysqli_query($koneksi, "SELECT JadwalID,DosenID,MKID,NamaKelas FROM jadwal WHERE JadwalID='".strfilter($_GET['JadwalID'])."'"));
$dos = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar,Handphone FROM dosen WHERE Login='$jdw[DosenID]'"));
$mk = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MKID,MKKode,Nama,SKS,Sesi FROM mk WHERE MKID='$jdw[MKID]'"));
$prd = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama FROM prodi WHERE ProdiID='".strfilter($_GET['prodi'])."'"));

echo"<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table class='table table-condensed table-bordered'>
<tbody>              
<tr>
<th width='200px' style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>No Dokumen</th>
<th style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>$dos[Nama]</th>
</tr>


<tr>	
<th width='200px' style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>Tahun Akademik</th>
<th style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>$_GET[tahun]</th>   
</tr>											
</tbody>
</table></div>
</div>
</div>										   
</div>";

echo"
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>						 
<table class='table table-condensed table-bordered table-striped'>                      
<thead>					 
  <tr>
	<th>No</th>                        				
	<th>MhswID</th>
	<th>Nama Mahasiswa</th>
  </tr>
</thead>
<tbody>";
					
$sq = mysqli_query($koneksi, "SELECT distinct(MhswID) from t_ppmiangketpjj order by MhswID ASC"); // where TahunID='".strfilter($_GET[tahun])."'
while($r=mysqli_fetch_array($sq)){  
$mhs 	=mysqli_fetch_array(mysqli_query($koneksi, "SELECT MhswID,Nama from mhsw where MhswID='$r[MhswID]' ORDER By MhswID asc"));
$no++;				         
echo "<tr bgcolor=$warna>
<td>$no</td>				
<td>$r[MhswID]</td>
<td>$mhs[Nama]</td>
</tr>";
}
echo "</tbody>
</table></div>
<div class='box-footer'>
</div>";
}
?>