<div class='card'>
<div class='card-header'>

<div class="box-header">
<h3 class="box-title"><b style='color:green;font-size:20px'>Angket Kepuasan Mahasiswa Terhadap Sarana & Prasarana serta Pelayanan Akademik</b></h3>  
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='students/ppmi-angketmhslayanan'>
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
<th colspan=2 style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>
Petunjuk Umum</th></tr>
<tr><td>a.	</td><td>Kajian ini dilakukan dengan tujuan untuk mengukur tingkat kepuasan mahasiswa terhadap pelayanan akademik di STMIK HANG TUAH PEKANBARU</td></tr>
<tr><td>b.	</td><td>	Saudara mendapatkan kepercayaan terpilih sebagai responden, di mohon untuk mengisi deluruh instrumen ini sesuai dengan pengalaman, pengetahuan, presepsi, dan keadaan yang sebenarnya</td></tr>
<tr><td>c.	</td><td>	Partisipasi saudara untuk mengisi instrumen ini secara objektif sangat besar artinta bagi STMIK HTP guna mendapatkan masukan yang akurat dalam rangka perbaikan dan peningkatan pelayanan akademik kedepan</td></tr>
<tr><td>d.	</td><td>	Jawaban saudara di jamin kerahasiaaan dan tidak memiliki dampak negatif dalam bentuk apapun</td></tr>
<tr><td>e.	</td><td>	Instrumen ini terdiri dari seperangkat pertanyaan atau pernyataan untuk mengukur :</td></tr>
<tr><td></td><td>(1)	Kemampuan<br> (2) Sikap<br> (3) Assurance<br> (4) Empathy<br> (5) Information system<br></td></tr>
<tr><td>f.	</td><td>	Pilihlah salah satu dari alternatif yang disediakan dengan cara klik option yang disediakan  </td></tr>
<tr><td>g.	</td><td>	Ada lima alternatif jawaban yang dapat saudara pilih, yaitu :
<tr><td></td><td>
0= Apabila kondisinya sangat kurang<br>
1= Apabila kondisinya kurang<br>
2= Apabila kondisinya cukup<br>
3= Apabila kondisinya baik<br>
4= Apabila kondisinya sangat baik<br>
</td></tr>
<tr><td colspan=2>
<a href='index.php?ndelox=students/ppmi-angketmhslayanan&act=tambahdata&JadwalID=$r[JadwalID]&tahun=$_SESSION[tahun_akademik]&MKID=$r[MKID]&DosenID=$r[DosenID]&prodi=$r[ProdiID]'> | Klik Link Pengisian Angket</a>
| <a href='index.php?ndelox=students/ppmi-angketmhslayanan&act=viewangket&JadwalID=$r[JadwalID]&tahun=$_SESSION[tahun_akademik]&MKID=$r[MKID]&DosenID=$r[DosenID]&prodi=$r[ProdiID]'> Lihat Angket</a>
</td></tr>

</tr>
</thead>
<tbody>";

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
<th width='200px' style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>Tgl Efektif</th>
<th style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>".tgl_indo(date('Y-m-d'))."</th>
</tr>

<tr>	
<th width='200px' style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>No Form</th>
<th style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>-</th>   
</tr>	

<tr>	
<th width='200px' style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>No. Ref</th>
<th style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>-</th>   
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
<input type='hidden' name='tahun' value='$_SESSION[tahun_akademik]'>
<input type='hidden' name='JadwalID' value='$_GET[JadwalID]'>	
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table class='table table-condensed table-bordered table-striped'>                      
<thead>					 
  <tr>
	<th>No</th>                        				
	<th>Pertanyaan</th>
	<th colspan=5 style=text-align:center>Pilihan Jawaban</th>
  </tr>
</thead>
<tbody>";
/*	*/	
$no = 1;								
$tampil = mysqli_query($koneksi, "SELECT * from t_ppmipertanyaanlayanan"); // 
while($r=mysqli_fetch_array($tampil)){   					         
echo "<tr bgcolor=$warna>
<td width=15px>$no</td>				
<td width=700px><b>[ $r[KategoriID] ]</b> - $r[Pertanyaan]</td>
	
<input type='hidden' value='$r[PertanyaanID]' name='PertanyaanID$no'/>			
<td><input type='radio' value='0' name='Jawaban$no' required/> 0</td>
<td><input type='radio' value='1' name='Jawaban$no' required/> 1</td>
<td><input type='radio' value='2' name='Jawaban$no' required/> 2</td>
<td><input type='radio' value='3' name='Jawaban$no' required/> 3</td>
<td><input type='radio' value='4' name='Jawaban$no' required/> 4</td>
</tr>";
$no++;
} //<input type='checkbox' value='$r[id_tanya]' name='id_tanya$no'/>
echo"<tr bgcolor=$warna>
<td colspan=2 align=right><b>Komentar/Saran</b></td>				
<td colspan=5><textarea name='Keterangan' cols=100 rows=5 required></textarea></td>
</tr>";
echo "</tbody>
</table></div>";
//echo"<input type='hidden' name='JumData' value='$no-1'>";
echo "<div class='box-footer'>
<a href='index.php?ndelox=students/ppmi-angketmhslayanan&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Back</button></a> 
<button type='submit' name='simpann' class='btn btn-info pull-right'>Isi Angket</button>                      
</div>";
echo "</form>";
	
if (isset($_POST[simpann])){
    $JadwalID  	= strfilter($_POST['JadwalID']);             
	$tahun   	= strfilter($_POST['tahun']);
	$prodi   	= strfilter($_POST['prodi']);
	
	//$JumData    = $_POST['JumData'];
$JumData 	= mysqli_num_rows(mysqli_query($koneksi, "SELECT * from t_ppmipertanyaanlayanan"));	
for($i = 1; $i <= $JumData; $i++){
	$PertanyaanID  	= $_POST['PertanyaanID'.$i];
    $Jawaban  		= $_POST['Jawaban'.$i];	
		if (!empty($PertanyaanID)){     
			$sqlcek = mysqli_query($koneksi, "SELECT * FROM t_ppmiangketlayanan WHERE MhswID='$_SESSION[_Login]' and PertanyaanID='$PertanyaanID' and TahunID='$tahun'"); //and JadwalID='$JadwalID'
			if (mysqli_num_rows($sqlcek)>0){
				echo "<script language='javascript'>alert('Data : Pengisian angket sudah dilakukan!');
				window.location = 'index.php?ndelox=students/ppmi-angketmhslayanan&JadwalID=$_POST[JadwalID]&tahun=$_POST[tahun]&prodi=$_POST[prodi]&gagal'</script>";
				exit;
			}
		$query = mysqli_query($koneksi, "INSERT INTO t_ppmiangketlayanan 
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
		}
	}		

	if ($query){
		echo "<script>document.location='index.php?ndelox=students/ppmi-angketmhslayanan&act=viewangket&JadwalID=$_POST[JadwalID]&prodi=$_POST[prodi]&tahun=$_POST[tahun]&MhswID=$_SESSION[id]&sukses';</script>";                       
	}else{
		echo "<script>document.location='index.php?ndelox=students/ppmi-angketmhslayanan&act=viewangket&JadwalID=$_POST[JadwalID]&prodi=$_POST[prodi]&tahun=$_POST[tahun]&MhswID=$_SESSION[id]&gagal';</script>";
	}

		
}
		  

} //tutup isset simpan

else if ($_GET[act]=='viewangket'){ 
$angket = mysqli_fetch_array(mysqli_query($koneksi, "SELECT JadwalID,MhswID FROM t_ppmiangketlayanan WHERE MhswID='$_SESSION[_Login]' and TahunID='$_SESSION[tahun_akademik]'")); //JadwalID='".strfilter($_GET[JadwalID])."' and 
$jdw = mysqli_fetch_array(mysqli_query($koneksi, "SELECT JadwalID,DosenID,MKID FROM jadwal WHERE JadwalID='$angket[JadwalID]'"));
$dos = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar,Handphone FROM dosen WHERE Login='$jdw[DosenID]'"));
$mk = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MKID,MKKode,Nama,SKS,Sesi FROM mk WHERE MKID='$jdw[MKID]'"));
$prd = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama FROM prodi WHERE ProdiID='".strfilter($_GET['prodi'])."'"));

echo"
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table class='table table-condensed table-bordered'>
<tbody>              
<tr>
<th width='200px' style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>Tgl. Efektif</th>
<th style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>".tgl_indo(date('Y-m-d'))."</th>
</tr>

<tr>	
<th width='200px' style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>No Form</th>
<th style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>-</th>   
</tr>	

<tr>	
<th width='200px' style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>No. Ref</th>
<th style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>-</th>   
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
$no = 1;								
$tampil = mysqli_query($koneksi, "SELECT * from t_ppmiangketlayanan where MhswID='$_SESSION[_Login]' and TahunID='$_SESSION[tahun_akademik]'"); //JadwalID='".strfilter($_GET[JadwalID])."' and TahunID='".strfilter($_GET[tahun])."'
while($r=mysqli_fetch_array($tampil)){   					         
$pertanyaan=mysqli_fetch_array(mysqli_query($koneksi, "select * from t_ppmipertanyaanlayanan where PertanyaanID='$r[PertanyaanID]'"));
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
} //<input type='checkbox' value='$r[id_tanya]' name='id_tanya$no'/>

echo "</tbody>
</table>";
//echo"<input type='hidden' name='JumData' value='$no-1'>";
echo "<div class='box-footer'>
<button type='submit' name='simpann2' class='btn btn-info pull-right'>Perbaharui Jawaban Angket</button>                      
</div>";
echo "</form>";
	
if (isset($_POST['simpann2'])){
    $JadwalID  	= strfilter($_POST['JadwalID']);             
	$tahun   	= strfilter($_POST['tahun']);
	$prodi   	= strfilter($_POST['prodi']);
	//$JumData    = strfilter($_POST['JumData']);
$JumData 	= mysqli_num_rows(mysqli_query($koneksi, "SELECT * from t_ppmipertanyaanlayanan"));	
for($i = 1; $i <= $JumData; $i++){
    $AngketID  		= strfilter($_POST['AngketID'.$i]);
	$PertanyaanID  	= strfilter($_POST['PertanyaanID'.$i]);
    $Jawaban  		= strfilter($_POST['Jawaban'.$i]);	
		if (!empty($PertanyaanID)){     
			$sqlcek = mysqli_query($koneksi, "SELECT * FROM t_ppmiangketlayanan WHERE MhswID='$_SESSION[_Login]' and TahunID='$_SESSION[tahun_akademik]'");
			if (mysqli_num_rows($sqlcek)>0){
				echo "<script language='javascript'>alert('Data : $MKID sudah ada');
				window.location = 'index.php?ndelox=students/ppmi-angketmhslayanan&JadwalID=$_POST[JadwalID]&tahun=$_POST[tahun]&prodi=$_POST[prodi]&gagal'</script>";
				exit;
			}
		$query = mysqli_query($koneksi, "UPDATE t_ppmiangketlayanan set Jawaban='$Jawaban' WHERE AngketID='$AngketID'");	 											
		}
	}		

	if ($query){
		echo "<script>document.location='index.php?ndelox=students/ppmi-angketmhslayanan&act=viewangket&JadwalID=$_POST[JadwalID]&prodi=$_POST[prodi]&tahun=$_POST[tahun]&MhswID=$_SESSION[id]&sukses';</script>";                       
	}else{
		echo "<script>document.location='index.php?ndelox=students/ppmi-angketmhslayanan&act=viewangket&JadwalID=$_POST[JadwalID]&prodi=$_POST[prodi]&tahun=$_POST[tahun]&MhswID=$_SESSION[id]&gagal';</script>";
	}

		
} //tutup isset simpan

}
?>