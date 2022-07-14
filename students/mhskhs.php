<div class='card'>
<div class='card-header'>


<div class="form-group row">
<label class="col-md-7 col-form-label text-md-right"><b style='color:purple'>KARTU HASIL STUDI</b></label>
		<div class="col-md-2">
		<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
		<input type="hidden" name='ndelox' value='students/mhskhs'>

		<select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
		<?php 
		$tahun = mysqli_query($koneksi, "SELECT distinct(TahunID),NA,ProdiID FROM tahun order by TahunID Desc"); 
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

		<div class="col-md-1">
		<input type="submit"  class='btn btn-success btn-sm' value='Lihat'> 
		</div>

		<div class="col-md-2">
		<a class='pull-right btn btn-primary btn-sm' href='print_report/print-mhskhs2.php?MhswID=<?php echo"$_GET[MhswID]";?>&prodi=<?php echo"$m[ProdiID]";?>&tahun=<?php echo"$_GET[tahun]";?>' target='_BLANK'>Cetak KHS</a>

		</div>

</div>
</div>

</form>
                
<?php
	$ss  = mysqli_fetch_array(mysqli_query($koneksi, "SELECT KHSID,Sesi,TahunID,MhswID FROM khs where MhswID='$_SESSION[_Login]' 
												and TahunID='".strfilter($_GET['tahun'])."'"));
	
	$m = mysqli_fetch_array(mysqli_query($koneksi, "SELECT mhsw.MhswID, mhsw.Nama AS NamaMhs, 
	mhsw.ProdiID, mhsw.ProgramID, 
	prodi.Nama AS NamaProdi 
	FROM mhsw,prodi,program 
	WHERE mhsw.ProdiID=prodi.ProdiID
	AND mhsw.ProgramID=program.ProgramID
	AND mhsw.MhswID='$_SESSION[_Login]'")); 


if ($_GET['act']==''){ ?>
<?php																			
	echo"<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>							  			 	<div class='box box-info'>
	<div class='card'>
	<div class='card-header'>
	<div class='table-responsive'>
		<table class='table table-sm table-bordered'>            
		<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:purple;width:100%>
		<th scope='row' style='width:150px'>Nama</th>
		<th>$m[NamaMhs]</th>
		<th>NIM </th>
		<th>$m[MhswID]</th>
		</tr>
								
		<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:purple;width:100%>
		<th scope='row' >Program</th>
		<th>$m[ProgramID]/$prod</td>
		<th scope='row' style='width:150px'>Tahun/Semester</th> 
		<th>$_GET[tahun]/$ss[Sesi]</th>
		</tr>        
		
		</table>
		</div>
</div>
</div>
		
		<div class='card'>
		<div class='card-header'>
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
		
	$no = 1;									
	$tampil = mysqli_query($koneksi, "SELECT * FROM vw_krs where MhswID='$_SESSION[_Login]' AND TahunID='".strfilter($_GET['tahun'])."'");  								
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
		<td align=center>$huruf</td>
		<td align=center>$total_bobot</td>";
	$no++;
	
	}
	
	mysqli_query($koneksi,"UPDATE khs set IPS='$ips' WHERE MhswID='$_SESSION[_Login]' AND TahunID='".strfilter($_GET['tahun'])."'");
	
	echo"<tr bgcolor=$warna>
		<td colspan=3><b>IPS: $ips</b> &nbsp;&nbsp;&nbsp;&nbsp;<b>YAD: $YAD SKS</b> </td><td style=text-align:center><b>$tsks SKS</b> </td><td></td><td style=text-align:center><b>$tbobottotal</b></td>
	</tr>";	
	echo "</tbody>
	</table></div>
	</div>
	</div>";
									  
	echo "<div class='box-footer'>
						 
	</div>";
	echo "</form>
	</div>
	</div>
	</div>";
}
?>