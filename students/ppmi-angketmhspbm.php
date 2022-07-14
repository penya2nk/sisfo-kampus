<div class='card'>
<div class='card-header'>

<div class="box-header">
<h3 class="box-title"><b style='color:green;font-size:20px'>Angket Penilaian Dosen</b></h3>  
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='students/ppmi-angketmhspbm'>
<select name='tahun' style='padding:4px'>
<?php 
echo "<option value='$_SESSION[tahun_akademik]' selected>$_SESSION[tahun_akademik]</option>";
?>  
</select>
<select name='prodi' style='padding:4px'>
<?php 
if ($_SESSION[prodi]=='SI'){$prodi="Sistem Informasi";} else{$prodi="Teknik Informatika";}
echo "<option value='$_SESSION[prodi]' selected>$prodi</option>";
?>
</select>	
<input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
</form>
</div>
</div>
</div>

<?php if ($_GET[act]==''){ 	                                   											   												
echo"
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>							  							
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table class='table table-condensed table-bordered table-striped'>                                          
<thead>	
<tr>                       				
<th colspan=6 style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>
Petunjuk Umum</th></tr>
<tr><td>a.	</td><td colspan=5>Kajian ini dilakukan dengan tujuan untuk mengukur tingkat kepuasan mahasiswa terhadap dosen pengampu matakuliah di STMIK HANG TUAH PEKANBARU</td></tr>
<tr><td>b.	</td><td colspan=5>	Saudara mendapatkan kepercayaan terpilih sebagai responden, di mohon untuk mengisi deluruh instrumen ini sesuai dengan pengalaman, pengetahuan, presepsi, dan keadaan yang sebenarnya</td></tr>
<tr><td>c.	</td><td colspan=5>	Partisipasi saudara untuk mengisi instrumen ini secara objektif sangat besar artinta bagi STMIK HTP guna mendapatkan masukan yang akurat dalam rangka perbaikan dan peningkatan pelayanan akademik kedepan</td></tr>
<tr><td>d.	</td><td colspan=5>	Jawaban saudara di jamin kerahasiaaan dan tidak memiliki dampak negatif dalam bentuk apapun</td></tr>
<tr><td>f.	</td><td colspan=5>	Pilihlah salah satu dari alternatif yang disediakan dengan cara klik option yang disediakan  dari link di bawah Aksi</td></tr>				 
<tr>
<th style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>No</th>                        				
<th style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>Dosen</th>
<th style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>Nama Matakuliah</th>
<th style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>Nama Kelas</th>
<th style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>SKS</th>
<th style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>Aksi</th>
</tr>
</thead>
<tbody>";
						
$tampil = mysqli_query($koneksi, "select * from krs where MhswID='$_SESSION[_Login]' AND TahunID='$_SESSION[tahun_akademik]'");								
while($r=mysqli_fetch_array($tampil)){ 
$jdwl   =mysqli_fetch_array(mysqli_query($koneksi, "select * from jadwal where JadwalID='$r[JadwalID]' AND TahunID='$_SESSION[tahun_akademik]'"));
$dosen 	=mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$jdwl[DosenID]'"));
$mk 	=mysqli_fetch_array(mysqli_query($koneksi, "select MKID,MKKode,Nama,SKS,Sesi,ProdiID from mk where MKID='$jdwl[MKID]'"));		
$jjwb   =mysqli_fetch_array(mysqli_query($koneksi, "select Count(PertanyaanID) as Jml from t_ppmiangket where JadwalID='$r[JadwalID]' AND TahunID='$_SESSION[tahun_akademik]' AND MhswID='$_SESSION[id]'"));			         
if ($jjwb[Jml]>0){
	$c="style=color:red";
}else{
	$c="style=color:green";
}

$no++;
echo "<tr $c>
<td >$no</td>
<td >$dosen[Nama], $dosen[Gelar] </td>
<td>$mk[Nama]  </td>
<td>$jdwl[NamaKelas]</td>
<td align=center>$mk[SKS]</td>
<td>";
if ($jjwb[Jml]>0){
	echo"| <a href='index.php?ndelox=students/ppmi-angketmhspbm&act=viewangket&JadwalID=$jdwl[JadwalID]&tahun=$jdwl[TahunID]&MKID=$jdwl[MKID]&DosenID=$jdwl[DosenID]&prodi=$mk[ProdiID]'> Lihat Angket </a> <b style=color:green>($jjwb[Jml])</b> |</a>";
}else{		
	echo"<a href='index.php?ndelox=students/ppmi-angketmhspbm&act=tambahdata&JadwalID=$jdwl[JadwalID]&tahun=$jdwl[TahunID]&MKID=$jdwl[MKID]&DosenID=$jdwl[DosenID]&prodi=$mk[ProdiID]'> | Isi Angket</a>";		
}
echo"</td>
</tr>";
 }

if (isset($_GET['hapus'])){
	mysqli_query($koneksi, "DELETE FROM jadwal_uppengujixx where JadwalID='".strfilter($_GET['hapus'])."'");
    echo "<script>document.location='index.php?ndelox=students/ppmi-angketmhspbm&JadwalID=$_GET[JadwalID]&tahun=$_GET[tahun]&prodi=$_GET[prodi]&sukses';</script>";
}
echo"<tr bgcolor=$warna><td colspan='7'></td></tr>";	
echo "</tbody></table></div></div>";
echo "<div class='box-footer'></div>";
echo "</form>
</div>
</div>
</div>";

//===================================================================================================================================
}else if ($_GET[act]=='tambahdata'){ 
$dos = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar,Handphone FROM dosen WHERE Login='".strfilter($_GET[DosenID])."'"));
$mk = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MKID,MKKode,Nama,SKS,Sesi FROM mk WHERE MKID='".strfilter($_GET[MKID])."'"));
$prd = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama FROM prodi WHERE ProdiID='".strfilter($_GET[prodi])."'"));
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
</table>
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
$tampil = mysqli_query($koneksi, "SELECT * from t_ppmipertanyaan");
while($r=mysqli_fetch_array($tampil)){   					         
echo "<tr bgcolor=$warna>
<td>$no</td>				
<td width=500px>$r[Pertanyaan]</td>
	
<input type='hidden' value='$r[PertanyaanID]' name='PertanyaanID".$no."'/>			
<td>
<input type='radio' value='1' name='Jawaban".$no."' required/> 1. $r[Pil1]<br>
<input type='radio' value='2' name='Jawaban".$no."' required/> 2. $r[Pil2]<br>
<input type='radio' value='3' name='Jawaban".$no."' required/> 3. $r[Pil3]<br>
<input type='radio' value='4' name='Jawaban".$no."' required/> 4. $r[Pil4]<br>
</td>

</tr>";
$no++;
} 



echo "</tbody>
</table></div>";
//echo"<input type='hidden' name='JumData' value='$no-1'>";
echo "<div class='box-footer'>

<a href='index.php?ndelox=students/ppmi-angketmhspbm&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Back</button></a>                    
<button type='submit' name='simpann' class='btn btn-info pull-right'>Isi Angket</button>  
</div>";
echo "</form>";
	
if (isset($_POST[simpann])){
    $JadwalID  	= strfilter($_POST['JadwalID']);             
	$tahun   	= strfilter($_POST['tahun']);
	$prodi   	= strfilter($_POST['prodi']);
	//$JumData    = $_POST['JumData'];
	$JumData 	= mysqli_num_rows(mysqli_query($koneksi, "SELECT * from t_ppmipertanyaan"));
	for($i = 1; $i <= $JumData; $i++){
	$PertanyaanID  	= strfilter($_POST['PertanyaanID'.$i]);
    $Jawaban  		= strfilter($_POST['Jawaban'.$i]);	
		if (!empty($PertanyaanID)){     
			$sqlcek = mysqli_query($koneksi, "SELECT * FROM t_ppmiangket WHERE MhswID='$_SESSION[_Login]' and JadwalID='$JadwalID' and PertanyaanID='$PertanyaanID'");
			if (mysqli_num_rows($sqlcek)>0){
				echo "<script language='javascript'>alert('Data : Pengisian angket untuk data perkuliahan tersebut sudah dilakukan!');
				window.location = 'index.php?ndelox=students/ppmi-angketmhspbm&JadwalID=$_POST[JadwalID]&tahun=$_POST[tahun]&prodi=$_POST[prodi]&gagal'</script>";
				exit;
			}
		$query = mysqli_query($koneksi, "INSERT INTO t_ppmiangket 
						   (JadwalID,
						    MhswID,
							PertanyaanID,
							Jawaban,
							TahunID,
							ProdiID,
							TanggalBuat,
							Keterangan)
					 VALUES('$JadwalID',
							'$_SESSION[_Login]',
							'$PertanyaanID',
							'$Jawaban',
							'$tahun',
							'$prodi',
							'".date('Y-m-d')."',
							'".strfilter($_POST['Keterangan'])."')");	 
		mysqli_query($koneksi, "update krs SET QS='Y' WHERE JadwalID='$JadwalID' and MhswID='$_SESSION[_Login]'");																
		}
	}		

	if ($query){
		echo "<script>document.location='index.php?ndelox=students/ppmi-angketmhspbm&act=viewangket&JadwalID=$_POST[JadwalID]&prodi=$_POST[prodi]&tahun=$_POST[tahun]&MhswID=$_SESSION[id]&sukses';</script>";                       
	}else{
		echo "<script>document.location='index.php?ndelox=students/ppmi-angketmhspbm&act=viewangket&JadwalID=$_POST[JadwalID]&prodi=$_POST[prodi]&tahun=$_POST[tahun]&MhswID=$_SESSION[id]&gagal';</script>";
	}

		
}
		  

} //tutup isset simpan

else if ($_GET[act]=='viewangket'){ 
$angket = mysqli_fetch_array(mysqli_query($koneksi, "SELECT JadwalID,MhswID FROM t_ppmiangket WHERE JadwalID='".strfilter($_GET['JadwalID'])."' and MhswID='$_SESSION[_Login]'"));
$jdw = mysqli_fetch_array(mysqli_query($koneksi, "SELECT JadwalID,DosenID,MKID FROM jadwal WHERE JadwalID='$angket[JadwalID]'"));
$dos = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar,Handphone FROM dosen WHERE Login='$jdw[DosenID]'"));
$mk = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MKID,MKKode,Nama,SKS,Sesi FROM mk WHERE MKID='$jdw[MKID]'"));
$prd = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama FROM prodi WHERE ProdiID='".strfilter($_GET['prodi'])."'"));

echo"
<div class='col-md-12'>
<div class='box box-info'>
<div class='box-header'>						
<div class='col-md-12'>			
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
</table>
</div>
</div>										   
</div>";

echo"<div class='box box-info'>
<div class='box-header'>						
<div class='col-md-12'>	
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
<input type='hidden' name='JadwalID' value='$_GET[JadwalID]'>
<input type='hidden' name='prodi' value='$_GET[prodi]'>	
<input type='hidden' name='tahun' value='$_GET[tahun]'>
<input type='hidden' name='JadwalID' value='$_GET[JadwalID]'>							 
<table class='table table-condensed table-bordered table-striped'>                      
<thead>					 
  <tr>
	<th>No</th>                        				
	<th>Pertanyaan</th>
	<th colspan=5 style=text-align:left>Pilihan Jawaban</th>
  </tr>
</thead>
<tbody>";
$no = 1;								
$tampil = mysqli_query($koneksi, "SELECT * from t_ppmiangket where JadwalID='".strfilter($_GET['JadwalID'])."' and MhswID='$_SESSION[_Login]' and TahunID='".strfilter($_GET['tahun'])."'"); 
while($r=mysqli_fetch_array($tampil)){   					         
$pertanyaan=mysqli_fetch_array(mysqli_query($koneksi, "select * from t_ppmipertanyaan where PertanyaanID='$r[PertanyaanID]'"));
echo "<tr bgcolor=$warna>
<td>$no</td>				
<td>$pertanyaan[Pertanyaan]</td>
<input type='hidden' value='$r[AngketID]' name='AngketID$no'/>	
<input type='hidden' value='$r[JadwalID]' name='JadwalID$no'/>
<input type='hidden' value='$r[TahunID]' name='tahun$no'/>
<input type='hidden' value='$r[ProdiID]' name='prodi$no'/>
<input type='hidden' value='$r[PertanyaanID]' name='PertanyaanID$no'/>			
<td><input type='text' size=1 maxlength=1 value='$r[Jawaban]' name='Jawaban$no'/> <br></td>

</tr>";
$no++;
} 

echo "</tbody>
</table>";
//echo"<input type='hidden' name='JumData' value='$no-1'>";
echo "<div class='box-footer'>
<button type='submit' name='simpann2' class='btn btn-info pull-right'>Perbaharui Jawaban Angket</button>  
<a href='index.php?ndelox=students/ppmi-angketmhspbm&JadwalID=$_POST[JadwalID]&prodi=$_POST[prodi]&tahun=$_POST[tahun]&MhswID=$_SESSION[id]&sukses''><button type='button' class='btn btn-default pull-right'>Kembali Ke Soal</button></a>                    
</div>";
echo "</form>";
	
if (isset($_POST['simpann2'])){
    $JadwalID  	= strfilter($_POST['JadwalID']);             
	$tahun   	= strfilter($_POST['tahun']);
	$prodi   	= strfilter($_POST['prodi']);
	//$JumData    = $_POST['JumData'];

$JumData 	= mysqli_num_rows(mysqli_query($koneksi, "SELECT * from t_ppmipertanyaan"));	
for($i = 1; $i <= $JumData; $i++){
    $AngketID  		= strfilter($_POST['AngketID'.$i]);
	$PertanyaanID  	= strfilter($_POST['PertanyaanID'.$i]);
    $Jawaban  		= strfilter($_POST['Jawaban'.$i]);	
		if (!empty($PertanyaanID)){     
			$sqlcek = mysqli_query($koneksi, "SELECT * FROM t_ppmiangket WHERE MhswID='$_SESSION[_Login]' and JadwalID='$_POST[JadwalID]'");
			if (mysqli_num_rows($sqlcek)>0){
				echo "<script language='javascript'>alert('Data : $MKID sudah ada');
				window.location = 'index.php?ndelox=students/ppmi-angketmhspbm&JadwalID=$_POST[JadwalID]&tahun=$_POST[tahun]&prodi=$_POST[prodi]&gagal'</script>";
				exit;
			}
		//off kan	
		$query = mysqli_query($koneksi, "UPDATE t_ppmiangket_jgn_xxxxxxxx set Jawaban='$Jawaban' WHERE AngketID='$AngketID'");
		 mysqli_query($koneksi, "update krs SET QS='Y' WHERE JadwalID='$JadwalID' and MhswID='$_SESSION[_Login]'"); //JadwalID='$JadwalID' and
		}
	}		

	if ($query){
		echo "<script>document.location='index.php?ndelox=students/ppmi-angketmhspbm&act=viewangket&JadwalID=$_POST[JadwalID]&prodi=$_POST[prodi]&tahun=$_POST[tahun]&MhswID=$_SESSION[id]&sukses';</script>";                       
	}else{
		echo "<script>document.location='index.php?ndelox=students/ppmi-angketmhspbm&act=viewangket&JadwalID=$_POST[JadwalID]&prodi=$_POST[prodi]&tahun=$_POST[tahun]&MhswID=$_SESSION[id]&gagal';</script>";
	}

		
} //tutup isset simpan

}
?>