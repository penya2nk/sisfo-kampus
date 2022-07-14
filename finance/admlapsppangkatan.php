<div class="card">
<div class="card-header">
<div class="card-tools">

<?php 
  if (isset($_GET['tahun'])){ 
     echo "<b style='color:green;font-size:20px'>BIAYA SPP</b> <a  href='print_reportxls/rekpembayaranspp.php?tahun=$_GET[tahun]&prodi=$_GET[prodi]' target='_BLANK'>Export Ke Excel</a>";
  }else{ 
     echo "<b style='color:green;font-size:20px'>BIAYA SPP</b>"; 
  } ?>
</h3>
</div>

<div class="form-group row">
<label class="col-md-6 col-form-label text-md-left"><b style='color:purple'>BIAYA SPP PER ANGKATAN</b></label>

<div class="col-md-2 "> 
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='finance/admlapsppangkatan'>
<select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
<?php 
	echo "<option value=''>- Pilih Angkatan -</option>";
	$tahun = mysqli_query($koneksi, "SELECT * from t_tahunnormal order by Tahun Desc"); //and NA='N'
	while ($k = mysqli_fetch_array($tahun)){
	  if ($_GET['tahun']==$k['Tahun']){
		echo "<option value='$k[Tahun]' selected>$k[Tahun]</option>";
	  }else{
		echo "<option value='$k[Tahun]'>$k[Tahun]</option>";
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

<div class="col-md-2">
<input type="submit"  class='btn btn-success btn-sm' value='Lihat'>
</form>
</div>
</div>

</div>
</div>


<?php
if ($_GET['act']==''){ 
$prodi=mysqli_fetch_array(mysqli_fetch_array($koneksi, "select ProdiID,Nama from prodi where ProdiID='".strfilter($_GET['prodi'])."'"));
?> 
<div class='card'>
<div class='card-header'>
<div class='col-md-12'>
<div class='box box-info'>
	  <h3 class="box-title"><b style='color:green;font-size:20px'>Rincian Pembayaran SPP Prodi <?php echo"<b style=color:#FF8306;>$prodi[Nama]</b>";?> Angkatan <?php echo"<b style=color:#FF8306;>$_GET[tahun]</b>";?></b></h3>	  
	</div><!-- /.box-header -->
	<div class="box-body">
	    <div class='table-responsive'>
	  <table id="example" class="table table-sm table-striped">
		<thead>
		  <tr style='background:purple;color:white'>
			<th style='width:40px'>No</th>
			<th style='width:80px'>NIM</th>
			<th style='width:200px'>Nama Mahasiswa</th>
			<th style='text-align:center' colspan='9'>Tahun Akademik</th>			
		  </tr>
		</thead>
		<tbody>
	  <?php 
	    $angkatan = substr($_GET['tahun'],2,2);//20191
		$tampil = mysqli_query($koneksi, "SELECT * from mhsw WHERE ProdiID='".strfilter($_GET['prodi'])."' AND left(MhswID,2)='$angkatan' AND StatusMhswID IN ('A','P','C') order by MhswID asc");//		AND keuangan_bayar.TahunID='".strfilter($_GET[tahun])."' 
		$no = 1;
		while($r=mysqli_fetch_array($tampil)){
        $jmlmk		=mysqli_num_rows(mysqli_fetch_array($koneksi, "select * from krs where MhswID='$r[MhswID]'"));
		$Namax 	    = strtolower($r['Nama']);
		$Nama 		= ucwords($Namax);
		echo "<tr ><td>$no</td>
				  <td>$r[MhswID]</td>
				  <td>$Nama</b><br><a href='?ndelox=finance/admlapsppangkatan&act=historykrsmhs&MhswID=$r[MhswID]&prodi=$_GET[prodi]&tahun=$_GET[tahun]'>Cek History KRS ($jmlmk MK)</a></td>";				  
				  $sqlb = mysqli_fetch_array($koneksi, "select keuangan_bayar.id_jenis,keuangan_bayar.TahunID,keuangan_bayar.MhswID,
												  keuangan_bayar.total_bayar,
												  mhsw.ProdiID
												  from keuangan_bayar,mhsw
												  WHERE keuangan_bayar.MhswID=mhsw.MhswID
												  AND keuangan_bayar.MhswID='$r[MhswID]' 
												  and keuangan_bayar.id_jenis='SPP' 
												  AND mhsw.ProdiID='".strfilter($_GET['prodi'])."' 
												  order by keuangan_bayar.TahunID asc");
				  //$i=0;
				  while($row=mysqli_fetch_array($sqlb)){				  		
						if ($row['total_bayar'] <1880000){
							$c="style=color:red";
						} else{
						    $c="style=color:black";
						}
				  echo"<td style='text-align:right'><a href='?ndelox=finance/admlapsppangkatan&act=historysppmhs&MhswID=$r[MhswID]&tahun=$_GET[tahun]&prodi=$_GET[prodi]'>$row[TahunID]</a><br><b $c>".number_format($row['total_bayar'])."</b></td>";
				  }
				echo "</tr>";
		  $no++;
		  }
	  ?>
		</tbody>
	  </table></div>
	</div><!-- /.box-body -->
  </div><!-- /.box -->
</div>
<?php
}

else if ($_GET['act']=='historykrsmhs'){     
$pr = mysqli_fetch_array(mysqli_query($koneksi, "SELECT mhsw.MhswID, mhsw.Nama AS NamaMhs, 
mhsw.ProdiID, mhsw.ProgramID, 
prodi.Nama AS NamaProdi 
FROM mhsw,prodi,program 
WHERE mhsw.ProdiID=prodi.ProdiID
AND mhsw.ProgramID=program.ProgramID
AND mhsw.MhswID='".strfilter($_GET['MhswID'])."'")); 
if ($m['ProdiID']=='SI'){$prod="Sistem Informasi";}else{$prod="Teknik Informatika";}
                                    											   												
echo"
<div class='card'>
<div class='card-header'>
<div class='col-md-12'>
<div class='box box-info'>
	  <h3 class='box-title'><b style=color:green>History KRS</b></h3>
	</div>
<div class='box-body'>	
		
<table class='table table-sm table-bordered'>
<tbody>              
<tr>
<th scope='row' style='width:200px'>NIM</th>
<th>$pr[MhswID]</th>
<th>Program </th>
<th>$pr[ProgramID]</th>
</tr>									
<tr>
<th scope='row' >Nama Mahasiswa </th>
<th>$pr[NamaMhs]</td>
<th scope='row' style='width:200px'>Program Studi</th> 
<th>$pr[NamaProdi]</th>
</tr>        			
</tbody>
</table>
<br>";
                           											   												
echo"

						  							
	<table class='table table-sm table-sm table-striped'>                      
	<thead>					 
	<tr style='background:purple;color:white'>
	<th>No</th>                        
	<th>Kode</th>
	<th>Matakuliah</th>
	
	<th style=text-align:center>SKS</th>
	<th>TahunID</th>
	<th>GradeNilai</th>	
	</tr>
	</thead>
	<tbody>";
$thk = mysqli_query($koneksi, "SELECT TahunID from tahun where ProdiID='".strfilter($_GET['prodi'])."' AND ProgramID='REG A' order by TahunID desc limit 10");	
while($w=mysqli_fetch_array($thk)){
echo"<tr style='background:purple;color:white'>
<th colspan='6'>TahunID : $w[TahunID]</th>
</tr>";
	$no = 1;							
	$tampil = mysqli_query($koneksi, "SELECT  
	KRSID,
	MKID,MKKode,NamaMK,SKS,TahunID,ProdiID,GradeNilai
	FROM vw_transkripdetailok 
	WHERE MhswID='".strfilter($_GET['MhswID'])."' 
	AND TahunID='$w[TahunID]'
	Order by TahunID Desc "); //AND TahunID='".strfilter($_GET[tahun])."' 									
while($r=mysqli_fetch_array($tampil)){   					         
	echo "<tr bgcolor=$warna>
	<td>$no</td>
	<td>$r[MKKode]</td>
	<td>$r[NamaMK]</td>
	<td style=text-align:center>$r[SKS]</td>
	<td>$r[TahunID]</td>
	<td>$r[GradeNilai]</td>   	
	</tr>";
	$no++;
	$tsks += $r['SKS'];
    
 }
   $skssmt = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(SKS) AS SKSSem FROM krs WHERE TahunID='$w[TahunID]' and MhswID='".strfilter($_GET['MhswID'])."'"));
   $rec = mysqli_fetch_array(mysqli_query($koneksi, "SELECT krs.KRSID,krs.MhswID,mhsw.Nama,mhsw.ProdiID,mhsw.ProgramID,mhsw.NA,
													krs.TahunID,krs.GradeNilai,
													(SUM(krs.BobotNilai * mk.sks))/(SUM(mk.sks)) AS vIPK 
													FROM krs,mhsw,mk
													WHERE krs.MhswID=mhsw.MhswID
													AND krs.MKID=mk.MKID
													AND krs.TahunID='$w[TahunID]'
													AND mhsw.ProdiID='".strfilter($_GET['prodi'])."'
													AND krs.MhswID='".strfilter($_GET['MhswID'])."'")); //AND krs.GradeNilai NOT IN ('-')
   echo"<tr bgcolor=$warna><td colspan='2'></td><td style=text-align:right><b >SKS yang diambil  </b></td><td  style=text-align:center><b>$skssmt[SKSSem] SKS <b></td><td colspan='2'><b>IPS: ".number_format($rec['vIPK'],2)."</b></td></tr>";	
}//th akademik
   $skstot = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(SKS) AS SKSTot FROM krs WHERE MhswID='".strfilter($_GET['MhswID'])."'"));
   echo"<tr bgcolor=$warna><td colspan='6'><b ></td></tr>";
   echo"<tr bgcolor=$warna><td colspan='6'><b >Total SKS yang diambil: $skstot[SKSTot] SKS<b></td></tr>";	
echo "</tbody></table>";
echo "<div class='box-footer'></div>";
echo "</form>
</div></div>
</div>";
}


else if ($_GET['act']=='historysppmhs'){     
$pr = mysqli_fetch_array(mysqli_query($koneksi, "SELECT mhsw.MhswID, mhsw.Nama AS NamaMhs, 
mhsw.ProdiID, mhsw.ProgramID, 
prodi.Nama AS NamaProdi 
FROM mhsw,prodi,program 
WHERE mhsw.ProdiID=prodi.ProdiID
AND mhsw.ProgramID=program.ProgramID
AND mhsw.MhswID='".strfilter($_GET['MhswID'])."'")); 
if ($m['ProdiID']=='SI'){$prod="Sistem Informasi";}else{$prod="Teknik Informatika";}
                                    											   												
echo"
<div class='card'>
<div class='card-header'>
<div class='col-md-12'>
<div class='box box-info'>
	  <h3 class='box-title'><b style=color:green>Rincian Pembayaran SPP</b></h3>
	</div>
<div class='box-body'>	
		
<table class='table table-sm table-bordered'>
<tbody>              
<tr>
<th scope='row' style='width:200px'>NIM</th>
<th>$pr[MhswID]</th>
<th>Program </th>
<th>$pr[ProgramID]</th>
</tr>									
<tr>
<th scope='row' >Nama Mahasiswa </th>
<th>$pr[NamaMhs]</td>
<th scope='row' style='width:200px'>Program Studi</th> 
<th>$pr[NamaProdi]</th>
</tr>        			
</tbody>
</table>
<br>";
                           											   												
echo"<table class='table table-sm table-bordered table-striped'>                      
	<thead>					 
	<tr style='background:purple;color:white'>
	<th>No</th>                        
	<th>TahunID</th>
	<th>Jumlah Pembayaran</th>
	
	<th style='text-align:center'>Tanggal Keterangan</th>
	<th>No Bukti</th>
	<th></th>
	</tr>
	</thead>
	<tbody>";
							
	$tampil = mysqli_query($koneksi, "SELECT keuangan_bayar.*,mhsw.Nama from mhsw,keuangan_bayar 
	WHERE keuangan_bayar.MhswID=mhsw.MhswID 
	AND keuangan_bayar.MhswID='".strfilter($_GET['MhswID'])."'
	AND keuangan_bayar.id_jenis='SPP' order by keuangan_bayar.TahunID desc");
	while($r=mysqli_fetch_array($tampil)){
	$no++;
	if ($r['Keterangan']<>'Lunas'){
		$c="style=color:red";
	}else{
		$c="style=color:green";
	}	
		$tot += $r['total_bayar'];
	echo "<tr><td>$no</td>			 
			  <td>$r[TahunID]</td>
			  <td align=right>Rp. ".number_format($r['total_bayar'],0)."</td>
			  
			  <td style='text-align:center'>".tgl_indo($r['TanggalBayar'])."</td>
			  <td $c>$r[Keterangan]</td>
			  <td>$r[NoBukti]</td></tr>";
    }
echo "</tbody></table>";
echo "<div class='box-footer'></div>";
echo "</form>
</div></div>
</div>";
}
?>

