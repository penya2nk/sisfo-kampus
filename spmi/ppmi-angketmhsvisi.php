<div class="col-xs-12">
<div class='box box-info'>
<div class="box-header">
<h3 class="box-title"><b style='color:green;font-size:20px'>Angket Pemahaman Mahasiswa Terhadap Visi, Misi dan Tujuan STMIK Hang Tuah Pekanbaru</b></h3>  
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='spmi/mhsangketvisi'>
<select name='tahun' style='padding:4px'>
<?php 
echo "<option value='$_SESSION[tahun_akademik]' selected>$_SESSION[tahun_akademik]</option>";

?>  
</select>
<select name='prodi' style='padding:4px'>
<?php 
if ($_SESSION['prodi']=='SI'){$prodi="Sistem Informasi";} else{$prodi="Teknik Informatika";}
echo "<option value='$_SESSION[prodi]' selected>$prodi</option>";

?>
</select>	
<input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
</form>
</div>
</div>
</div>

<?php if ($_GET['act']==''){ 	                                   											   												
echo"<div class='col-xs-12'>
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>							  							
<div class='box box-info'>
<div class='box-body'>						
<div class='col-md-12'>
<div class='table-responsive'>
<table class='table table-condensed table-bordered table-striped'>                                          
<thead>					 
<tr>                       				
<th colspan=2 style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>
Pengantar</th></tr>
<tr><td>Instrumen tes ini digunakan untuk mengukur tingkat 
pemahaman Saudara  terhadap visi, misi, dan tujuan STMIK HTP. Hasil tes tidak berdampak apapun pada Saudara saat ini atau di masa datang. Namun kesungguhan Saudara dalam mengerjakannya sangat diharapkan. Hal ini penting karena data hasil tes akan menjadi dasar dalam menentukan tindak lanjut yang harus dilakukan oleh STMIK HTP ke depan. Terima kasih.
</td></tr>

<tr><td colspan=2>
<a href='index.php?ndelox=spmi/spmimhsangketvisi&act=tambahdata&JadwalID=$r[JadwalID]&tahun=$r[TahunID]&MKID=$r[MKID]&DosenID=$r[DosenID]&prodi=$r[ProdiID]'> | Klik Link Pengisian Angket</a>
| <a href='index.php?ndelox=spmi/spmimhsangketvisi&act=viewangket&JadwalID=$r[JadwalID]&tahun=$r[TahunID]&MKID=$r[MKID]&DosenID=$r[DosenID]&prodi=$r[ProdiID]'> Lihat Angket</a>
</td></tr>

</tr>
</thead>
<tbody>
<tr bgcolor=$warna><td colspan='7'></td></tr></tbody></table></div>
<div class='box-footer'></div>
</form>
</div>
</div>
</div>";

//===================================================================================================================================
}else if ($_GET['act']=='tambahdata'){ 
$dos = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar,Handphone FROM dosen WHERE Login='".strfilter($_GET['DosenID'])."'"));
$mk = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MKID,MKKode,Nama,SKS,Sesi FROM mk WHERE MKID='".strfilter($_GET['MKID'])."'"));
$prd = mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama FROM prodi WHERE ProdiID='".strfilter($_GET['prodi'])."'"));
echo"
<div class='col-md-12'>
<div class='box box-info'>
<div class='box-header'>						
<div class='col-md-12'>			
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
/*	*/	
$no = 1;								
$tampil = mysqli_query($koneksi, "SELECT * from t_ppmipertanyaanvisi"); // 
while($r=mysqli_fetch_array($tampil)){   					         
echo "<tr bgcolor=$warna>
<td>$no</td>				
<td width=500px>$r[Pertanyaan]</td>
	
<input type='hidden' value='$r[PertanyaanID]' name='PertanyaanID$no'/>			
<td>
<input type='radio' value='A' name='Jawaban$no'/> A. $r[Pil1]<br>
<input type='radio' value='B' name='Jawaban$no'/> B. $r[Pil2]<br>
<input type='radio' value='C' name='Jawaban$no'/> C. $r[Pil3]<br>
<input type='radio' value='D' name='Jawaban$no'/> D. $r[Pil4]<br>
</td>

</tr>";
$no++;
} 

echo "</tbody>
</table>";
echo"<input type='hidden' name='JumData' value='$no-1'>";
echo "<div class='box-footer'>
<a href='index.php?ndelox=spmi/spmimhsangketvisi&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Back</button></a> 
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
			$sqlcek = mysqli_query($koneksi, "SELECT * FROM t_ppmiangketvisi WHERE MhswID='$_SESSION[_Login]' and JadwalID='$JadwalID' and PertanyaanID='$PertanyaanID'");
			if (mysqli_num_rows($sqlcek)>0){
				echo "<script language='javascript'>alert('Data : Pengisian angket untuk data perkuliahan tersebut sudah dilakukan!');
				window.location = 'index.php?ndelox=spmi/spmimhsangketvisi&JadwalID=$_POST[JadwalID]&tahun=$_POST[tahun]&prodi=$_POST[prodi]&gagal'</script>";
				exit;
			}
		$query = mysqli_query($koneksi, "INSERT INTO t_ppmiangketvisi 
						   (JadwalID,
						    MhswID,
							PertanyaanID,
							Jawaban,
							TahunID,
							ProdiID,
							TanggalBuat,
							RespondenKategori)
					 VALUES('$JadwalID',
							'$_SESSION[_Login]',
							'$PertanyaanID',
							'$Jawaban',
							'$tahun',
							'$prodi',
							'".date('Y-m-d')."',
							'mhs')");	 											
		}
	}		

	if ($query){
		echo "<script>document.location='index.php?ndelox=spmi/spmimhsangketvisi&act=viewangket&JadwalID=$_POST[JadwalID]&prodi=$_POST[prodi]&tahun=$_POST[tahun]&MhswID=$_SESSION[id]&sukses';</script>";                       
	}else{
		echo "<script>document.location='index.php?ndelox=spmi/spmimhsangketvisi&act=viewangket&JadwalID=$_POST[JadwalID]&prodi=$_POST[prodi]&tahun=$_POST[tahun]&MhswID=$_SESSION[id]&gagal';</script>";
	}

		
}
		  

} //tutup isset simpan

else if ($_GET['act']=='viewangket'){ 
$angket = mysqli_fetch_array(mysqli_query($koneksi, "SELECT JadwalID,MhswID FROM t_ppmiangketvisi WHERE JadwalID='".strfilter($_GET['JadwalID'])."' and MhswID='".strfilter($_GET['MhswID'])."'"));
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
$tampil = mysqli_query($koneksi, "SELECT * from t_ppmiangketvisi where JadwalID='".strfilter($_GET['JadwalID'])."' and MhswID='$_SESSION[_Login]' and TahunID='".strfilter($_GET['tahun'])."'"); 
while($r=mysqli_fetch_array($tampil)){   					         
$pertanyaan=mysqli_fetch_array(mysqli_query("select * from t_ppmipertanyaanvisi where PertanyaanID='$r[PertanyaanID]'"));
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
echo"<input type='hidden' name='JumData' value='$no-1'>";
echo "<div class='box-footer'>
<button type='submit' name='simpann2' class='btn btn-info pull-right'>Perbaharui Jawaban Angket</button>                      
</div>";
echo "</form>";
	
if (isset($_POST['simpann2'])){
    $JadwalID  	= strfilter($_POST['JadwalID']);             
	$tahun   	= strfilter($_POST['tahun']);
	$prodi   	= strfilter($_POST['prodi']);
	
$JumData    = strfilter($_POST['JumData']);	
for($i = 1; $i <= $JumData; $i++){
    $AngketID  		= strfilter($_POST['AngketID'.$i]);
	$PertanyaanID  	= strfilter($_POST['PertanyaanID'.$i]);
    $Jawaban  		= strfilter($_POST['Jawaban'.$i]);	
		if (!empty($PertanyaanID)){     
			$sqlcek = mysqli_query($koneksi, "SELECT * FROM t_ppmiangketvisi WHERE MhswID='$MhswID' and JadwalID='".strfilter($_POST['JadwalID'])."'");
			if (mysqli_num_rows($sqlcek)>0){
				echo "<script language='javascript'>alert('Data : $MKID sudah ada');
				window.location = 'index.php?ndelox=spmi/spmimhsangketvisi&JadwalID=$_POST[JadwalID]&tahun=$_POST[tahun]&prodi=$_POST[prodi]&gagal'</script>";
				exit;
			}
		$query = mysqli_query($koneksi, "UPDATE t_ppmiangketvisi set Jawaban='$Jawaban' WHERE AngketID='$AngketID'");	 											
		}
	}		

	if ($query){
		echo "<script>document.location='index.php?ndelox=spmi/spmimhsangketvisi&act=viewangket&JadwalID=$_POST[JadwalID]&prodi=$_POST[prodi]&tahun=$_POST[tahun]&MhswID=$_SESSION[id]&sukses';</script>";                       
	}else{
		echo "<script>document.location='index.php?ndelox=spmi/spmimhsangketvisi&act=viewangket&JadwalID=$_POST[JadwalID]&prodi=$_POST[prodi]&tahun=$_POST[tahun]&MhswID=$_SESSION[id]&gagal';</script>";
	}

		
} //tutup isset simpan

}
?>