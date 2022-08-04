<div class="card">
<div class="card-header">
	<div class="card-tools">
           
	<?php
	$m = mysqli_fetch_array(mysqli_query($koneksi, "SELECT mhsw.MhswID, mhsw.Nama AS NamaMhs, 
	mhsw.ProdiID, mhsw.ProgramID, 
	prodi.Nama AS NamaProdi 
	FROM mhsw,prodi,program 
	WHERE mhsw.ProdiID=prodi.ProdiID
	AND mhsw.ProgramID=program.ProgramID
	AND mhsw.MhswID='".strfilter($_GET['MhswID'])."'")); 

	?>
	</div>

	<div class="form-group row">
		<label class="col-md-7 col-form-label text-md-left"><b style='color:purple'>TRANSKRIP NILAI</b></label>

				<div class="col-md-2 "> 
				<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
				<input type="hidden" name='ndelox' value='dep/nilaitranskrip'>
				<input type='text'  class='form-control form-control-sm' placeholder="NIM" name='MhswID' value='<?php echo"$_GET[MhswID]";?>'></td>
				</div>

				<div class="col-md-1">
				<input type="submit" class='btn btn-success btn-sm' value='Lihat'>
				</div>

				<div class="col-md-2">
				<?php 
				if ($_GET['MhswID']!=''){
				echo"<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=dep/nilaitranskrip&act=historykrsmhs&MhswID=$_GET[MhswID]&prodi=$m[ProdiID]&program=$_GET[program]'>Cek History Nilai</a>";
				}
				?>
				</div>
	</div>
	</form>


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
	<input type='hidden' name='prodi' value='$r[ProdiID]'>";
	
     
	
if (isset($_GET['MhswID'])){ 
	echo"	<div class='card'>
	<div class='card-header'>
	<div class='table-responsive'>
	<table width='100%' border='0.10'>	
	<tr style=text-align:left;font-size:14px;color:white;font-weight:reguler;background-color:purple;width:100%>
	<th scope='row' colspan=5 height=10></th>	
	</tr>
	<tr style=text-align:left;font-size:14px;color:white;font-weight:reguler;background-color:purple;>
	<th scope='row' style='width:150px'>&nbsp;&nbsp;NIM</th><th width='250px'>: $m[MhswID]</th>
	<th width='20px'></th><th width='150px'>Program </th><th>:  $m[ProgramID] </th>
	</tr>
							
	<tr style=text-align:left;font-size:14px;color:white;font-weight:reguler;background-color:purple;width:100%>
	<th scope='row' >&nbsp;&nbsp;Nama Mahasiswa</th>
	<th>: $m[NamaMhs]</td>
	<th ></th>
	<th scope='row' >Program Studi</th> 
	<th>: $m[NamaProdi]</th>
	</tr> 
	<tr style=text-align:left;font-size:14px;color:white;font-weight:reguler;background-color:purple;width:100%>
	<th scope='row' colspan=5  height=10></th>	
	</tr>
	</table></div>
		<div class='table-responsive'>
		<table class='table table-sm table-bordered table-striped'> 
        <tr>
		<td>
		
		<a class='pull-right btn btn-primary btn-sm' href=print_reportxls/transkripxls.php?MhswID=$_GET[MhswID]&prodi=$m[ProdiID] 
		target='_BLANK'>Export Excel Lulus</a>&nbsp;


		
		<a class='pull-right btn btn-primary btn-sm' href='print_reportxls/transkriplengkapxls.php?MhswID=$_GET[MhswID]&prodi=$m[ProdiID]' 
		target='_BLANK'>Export Excel Lengkap</a>&nbsp;

		<a class='pull-right btn btn-primary btn-sm' href='print_report/print-transkriplengkap.php?MhswID=$_GET[MhswID]&prodi=$m[ProdiID]' 
		target='_BLANK'>Cetak Trankrip Lengkap</a>&nbsp;

		<a class='pull-right btn btn-primary btn-sm' href='print_report/print-transkrip.php?MhswID=$_GET[MhswID]&prodi=$m[ProdiID]' 
		target='_BLANK'>Cetak Transkrip Lulus</a>&nbsp;

		<a class='pull-right btn btn-primary btn-sm' href=index.php?ndelox=dep/nilaitranskrip&act=transkripnilailulus&MhswID=$_GET[MhswID]>Lihat Transkrip Lulus </a>&nbsp;
		
        </td></tr>
		</table></div>";
} 

	echo"	<div class='table-responsive'><table class='table table-sm table-bordered table-striped'>                      
	<thead>	";
	$hdr = "
	<tr style=text-align:left;font-size:14px;color:white;font-weight:reguler;background-color:purple;width:100%;>
	<th style='width:50px;text-align:center'>No</th>                        	
	<th style='width:100px;text-align:center'>Kode</th>
	<th style='width:400px;text-align:left'>Matakuliah</th>
	<th style='width:90px;text-align:center'>SKS</th>
	<th style='width:90px;text-align:center'>Semester</th>
	<th style='width:90px;text-align:center'>Grade Nilai</th>
	<th style='width:90px;text-align:center'>TahunAK</th>
	<th style='width:90px;text-align:center'>Aksi</th>
	</tr>
	</thead>
	<tbody>";
									

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
				  order by krs.TahunID, krs.MKKode"); 

$_tahun = 'alksdjfasdf-asdf';
  $n = 0;
while($r=mysqli_fetch_array($tampil)){  
    
 if ($_tahun != $r['TahunID']) {
      $_tahun = $r['TahunID'];
      echo "<tr >
        <td class=ul1 colspan=10>
          <font size=+1><b>$_tahun</b></font>
        </td></tr>";
      echo $hdr;
    
    }  
    
    
    if ($r['GradeNilai']=='E'){			
    	$c="color:red;font-weight:bold";
    }
    elseif ($r['GradeNilai']=='D'){			
    	$c="color:red;font-weight:bold";
    }
    elseif ($r['GradeNilai']=='TL'){			
    	$c="color:red;font-weight:bold";
    }
    elseif ($r['GradeNilai']=='-'){			
    	$c="color:red;font-weight:bold";
    }
    elseif ($r['GradeNilai']==''){			
    	$c="color:red;font-weight:bold";
    }
    else{
    	$c="color:black;font-weight:reguler";
    }				         
	echo "<tr bgcolor=$warna>
	<td style='text-align:center;$c'>$no</td>
	<td style='text-align:center;$c'>$r[MKKode] $jml</td>
	<td $c>$r[NamaMK]</td>	
	<td style='width:90px;text-align:center;$c'>$r[SKS]</td>
	<td style='width:90px;text-align:center;$c'>$r[Sesi]</td>
	<td style='width:90px;text-align:center;$c'>$r[GradeNilai]</td>
	<td style='width:90px;text-align:center;$c'>$r[TahunID]</td>
	<td>
	<center>   
	
	<a class='btn btn-success btn-xs' title='Edit Nilai' href='index.php?ndelox=dep/nilaitranskrip&act=editnilai&KRSID=$r[KRSID]&MhswID=$r[MhswID]&MKID=$r[MKID]&GLama=$r[GradeNilai]&prodi=$r[ProdiID]&tahun=$r[TahunID]'><i class='fa fa-edit'></i></a>
	                              
	<a class='btn btn-danger btn-xs' title='Delete' href='?ndelox=dep/nilaitranskrip&hapus=$r[KRSID]&JadwalID=".$_GET['JadwalID']."&tahun=".$_GET['tahun']."&MhswID=".$_GET['MhswID']."' 
	onclick=\"return confirm('Apa anda yakin akan menghapus Data ($r[MKKode]) $r[NamaMK] - Nilai $r[GradeNilai]?')\"><i class='fa fa-trash'></i></a>
	</center>
	</td>
	</tr>
	
	";
	/*cadangan
	<a class='btn btn-danger btn-xs' title='Delete' href='?ndelox=dep/nilaitranskrip&hapus=$r[KRSID]&JadwalID=".$_GET[JadwalID]."&tahun=".$_GET[tahun]."&MhswID=".$_GET[MhswID]."' 
	onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-trash'></i></a> */
$no++;

$huruf= $r['GradeNilai'];
if ($huruf=='A'){
	$bobot=4;
}
elseif ($huruf=='A-'){
	$bobot=3.70;
}
elseif ($huruf=='B'){
	$bobot=3;
}
elseif ($huruf=='B+'){
	$bobot=3.30;
}
elseif ($huruf=='B-'){
	$bobot=2.70;
}
elseif ($huruf=='C'){
	$bobot=2;
}
elseif ($huruf=='C+'){
	$bobot=2.30;
}
elseif ($huruf=='C-'){
	$bobot=1.70;
}
elseif ($huruf=='D'){
	$bobot=1;
}
else{
    $bobot=0; 
}
$tsks 		 += $r['SKS'];
$total_bobot  = $r['SKS'] * $bobot;

$bobot 		 += $bobot;
$bobot_total += $total_bobot;

$ipk = number_format($bobot_total / $tsks,2);
}

if (isset($_GET['hapus'])){
	$data=mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from krs where KRSID='".strfilter($_GET['hapus'])."'"));
    mysqli_query("INSERT into krs_del (KRSID,
												  KHSID,
												  MhswID,
												  TahunID,
												  JadwalID,
												  MKID,
												  MKKode,
												  SKS,
												  Tugas1,
												  Tugas2,
												  Tugas3,
												  Tugas4,
												  Tugas5,
												  Presensi,
												  _Presensi,
												  UTS,
												  UAS,								 
												  NilaiAkhir,
												  GradeNilai,
												  BobotNilai,																						  
												  LoginBuat,
												  TanggalBuat)
										  VALUES('".strfilter($data['KRSID'])."',
										  		 '".strfilter($data['KHSID'])."',
												 '".strfilter($data['MhswID'])."',
												 '".strfilter($data['TahunID'])."',  
												 '".strfilter($data['JadwalID'])."',												
												 '".strfilter($data['MKID'])."',
												 '".strfilter($data['MKKode'])."',
												 '".strfilter($data['SKS'])."',
												 '".strfilter($data['Tugas1'])."',
												 '".strfilter($data['Tugas2'])."',
												 '".strfilter($data['Tugas3'])."',
												 '".strfilter($data['Tugas4'])."',
												 '".strfilter($data['Tugas5'])."',
												 '".strfilter($data['Presensi'])."',
												 '".strfilter($data['_Presensi'])."',
												 '".strfilter($data['UTS'])."',
												 '".strfilter($data['UAS'])."',
												 '".strfilter($data['NilaiAkhir'])."',
												 '".strfilter($data['GradeNilai'])."',
												 '".strfilter($data['BobotNilai'])."',
												 '$_SESSION[id]', 
												 '".date('Y-m-d H:i:s')."')");
	mysqli_query($koneksi, "DELETE FROM krs where KRSID='".strfilter($_GET['hapus'])."'");//_offdulubro
    echo "<script>document.location='index.php?ndelox=dep/nilaitranskrip&JadwalID=".$_GET['JadwalID']."&tahun=".$_GET['tahun']."&MhswID=".$_GET['MhswID']."&sukses';</script>";
}
echo"</tbody>
</table></div>


<div class='card'>
	<div class='card-header'>
	<div class='table-responsive'>
	<table>	
<tr bgcolor=$warna>
	<td width='200' colspan='2' align=left><b>Total SKS </b></td><td  colspan='6'><b> : $tsks SKS </b></td>
	</tr>
	<tr bgcolor=$warna>
		<td colspan='2' align=left><b>Total Bobot </b></td><td colspan='6'><b> : $bobot_total </b></td>
	</tr>
	
	<tr bgcolor=$warna>
		<td colspan='2' align=left><b>IPK </b></td><td colspan='6'><b> : $ipk </b></td>
	</tr>
";	
echo "</tbody>
</table></div>";
								  
echo "<div class='box-footer'>
                     
</div>";
echo "</form>
</div>
</div>
</div>";      
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
	<div class='box-header with-border'>
	  <h3 class='box-title'><b style=color:green>History KRS</b></h3>
	</div>

		
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
<div class='col-md-8'>
<div class='box box-info'>
	<div class='box-header with-border'>
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

						  							
	<table class='table table-sm table-bordered table-striped'>                      
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


else if ($_GET['act']=='editnilai'){ 
 //header
 $r = mysqli_fetch_array(mysqli_query($koneksi, "SELECT a.MhswID, a.Nama AS NamaMhs, a.ProdiID, a.ProgramID, b.Nama AS NamaProdi 
									FROM mhsw a LEFT JOIN prodi b ON a.ProdiID=b.ProdiID where a.MhswID='".strfilter($_GET['MhswID'])."'"));
 //ambil MKID tampil untuk edit
 $s = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MKID,MKKode,NilaiAkhir,GradeNilai,BobotNilai from krs where KRSID='".strfilter($_GET['KRSID'])."'"));	
 									
	 if (isset($_POST['ubahnilai'])){	
	    $uas = strfilter($_POST['NilaiAkhir']);
		$nilai = strfilter($_POST['NilaiAkhir']);
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
		//mengambil MKID untuk disimpan  
	    $m = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MKID,Nama,MKKode,SKS from mk where MKID='".strfilter($_POST['MKID'])."'"));
        $query = mysqli_query($koneksi, "UPDATE krs SET
							 UAS 		='$uas',
							 NilaiAkhir ='$nilai',
							 GradeNilai	='$huruf',
							 BobotNilai	='$bobot',
							 LoginEdit  ='$_SESSION[_Login]',
							 TanggalEdit='".date('Y-m-d H:i:s')."'
							 WHERE KRSID='".strfilter($_POST['KRSID'])."'");
        mysqli_query($koneksi, "INSERT into koreksinilai (Tanggal,TahunID,KRSID,MhswID,MKID,GradeLama,GradeNilai,LoginBuat,TglBuat)
								values('".date('Y-m-d')."',
									   '".strfilter($_POST['tahun'])."',
									   '".strfilter($_POST['KRSID'])."',
									   '".strfilter($_POST['MhswID'])."',
									   '".strfilter($_POST['MKID'])."',
									   '".strfilter($_GET['GLama'])."',
									   '$huruf',
									   '$_SESSION[_Login]',
									   '".date('Y-m-d H:i:s')."')");							 
        if ($query){
			echo "<script>document.location='index.php?ndelox=dep/nilaitranskrip&tahun=$_POST[tahun]&MhswID=$_POST[MhswID]&prodi=$_POST[prodi]&sukses';</script>";
        }else{
            echo "<script>document.location='index.php?ndelox=dep/nilaitranskrip&tahun=$_POST[tahun]&MhswID=$_POST[MhswID]&prodi=$_POST[prodi]&gagal';</script>";
        }
		
    }


echo"<div class='card'>
<div class='card-header'>					
<div class='table-responsive'>	
	<table class='table table-sm table-bordered'>
	<tbody>              
	<tr>
	<th scope='row' style='width:200px'>NIM</th>
	<th style='width:300px'>$r[MhswID]</th>
	<th>Program </th>
	<th>$r[ProgramID]</th>
	</tr>
							
	<tr>
	<th scope='row' >Nama Mahasiswa </th>
	<th>$r[NamaMhs]</td>
	<th scope='row' style='width:200px'>Program Studi</th> 
	<th>$r[NamaProdi]</th>
	</tr>        
	
	</tbody>
	</table>
	</div>
	</div>										   
	</div>";  
						              								
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
    echo " <div class='card'>
	<div class='card-header'>					
	<div class='table-responsive'>	        
                <div class='box-header with-border'>
                  <h3 class='box-title'><b style=color:green>Edit Nilai Kuliah</b></h3>
                </div>
          
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
			  <input type='hidden' name='KRSID' value='$_GET[KRSID]'>
			  <input type='hidden' name='MKID' value='$_GET[MKID]'>
			   <input type='hidden' name='MhswID' value='$_GET[MhswID]'>
                <input type='hidden' name='tahun' value='$_GET[tahun]'>
				<input type='hidden' name='program' value='$_GET[program]'>
				<input type='hidden' name='prodi' value='$_GET[prodi]'>
                  <table class='table table-sm table-bordered'>
                  <tbody>			
                    <tr><th scope='row'>Mata Kuliah</th>   <td><select class='form-control' name='MKID'> 
						<option value='0' selected>- Pilih Mata Kuliah -</option>"; 
						
						if ($_GET['prodi']=='SI'){
						    $mk = mysqli_query($koneksi, "SELECT MKID,MKKode,Nama,SKS FROM mk where ProdiID='".strfilter($_GET['prodi'])."' AND NA='N' and KurikulumID='24' order by  Nama ASC"); //mid(MKKode,0,3)
						}
						if ($_GET['prodi']=='TI'){						
							$mk = mysqli_query($koneksi, "SELECT MKID,MKKode,Nama,SKS FROM mk where ProdiID='".strfilter($_GET['prodi'])."' AND NA='N' and KurikulumID='25' order by  Nama ASC");
						}
						//$mk = mysqli_query($koneksi, "SELECT * FROM mk where ProdiID='$_GET[prodi]' AND KurikulumID='24' order by Nama asc"); // where ProdiID='SI' not working
						while($a = mysqli_fetch_array($mk)){
						  if ($_GET['MKID']==$a['MKID']){
							echo "<option value='$a[MKID]' selected>$a[Nama] - [ $a[MKKode] ]</option>";
						  }else{
							echo "<option value='$a[MKID]'>$a[Nama] - [ $a[MKKode] ]</option>";
						  }
						}
						echo "</select>
                    </td></tr>
					<tr><th scope='row'>Nilai Angka</th>  <td><input type='text' maxlength='3'  class='form-control' name='NilaiAkhir' value='$s[NilaiAkhir]'></td></tr>                     
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='ubahnilai' class='btn btn-info'>Perbaharui Nilai</button>
                    <a href='index.php?ndelox=dep/nilaitranskrip&tahun=$_GET[tahun]&prodi=$_GET[prodi]&MhswID=$_GET[MhswID]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>
			";
}
 //tutup atas

 else if ($_GET['act']=='transkripnilailulus'){     
				
																
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
  <input type='hidden' name='prodi' value='$r[ProdiID]'>";
  
   
  
if (isset($_GET['MhswID'])){ 
  echo"
  <div class='card'>
  <div class='card-header'> 
  <div class='table-responsive'>
  <table width='100%' border='0.10'>	
  <tr style=text-align:left;font-size:14px;color:white;font-weight:reguler;background-color:purple;width:100%>
  <th scope='row' colspan=5 height=10></th>	
  </tr>
  <tr style=text-align:left;font-size:14px;color:white;font-weight:reguler;background-color:purple;>
  <th scope='row' style='width:150px'>&nbsp;&nbsp;NIM</th><th width='250px'>: $m[MhswID]</th>
  <th width='20px'></th><th width='150px'>Program </th><th>:  $m[ProgramID] </th>
  </tr>
						  
  <tr style=text-align:left;font-size:14px;color:white;font-weight:reguler;background-color:purple;width:100%>
  <th scope='row' >&nbsp;&nbsp;Nama Mahasiswa</th>
  <th>: $m[NamaMhs]</td>
  <th ></th>
  <th scope='row' >Program Studi</th> 
  <th>: $m[NamaProdi]</th>
  </tr> 
  <tr style=text-align:left;font-size:14px;color:white;font-weight:reguler;background-color:purple;width:100%>
  <th scope='row' colspan=5  height=10></th>	
  </tr>
  </table>
  <div class='table-responsive'>
	  <table class='table table-sm table-bordered table-striped'> 
	  <tr>
	  <td>

	 
	  <a class='pull-right btn btn-primary btn-sm' href=print_reportxls/transkripxls.php?MhswID=$_GET[MhswID]&prodi=$m[ProdiID] 
	  target='_BLANK'>Export Excel Lulus</a>&nbsp;

	
	  
	  <a class='pull-right btn btn-primary btn-sm' href='print_reportxls/transkriplengkapxls.php?MhswID=$_GET[MhswID]&prodi=$m[ProdiID]' 
	  target='_BLANK'>Export Excel Lengkap</a>&nbsp;

	  <a class='pull-right btn btn-primary btn-sm' href='print_report/print-transkriplengkap.php?MhswID=$_GET[MhswID]&prodi=$m[ProdiID]' 
	  target='_BLANK'>Cetak Trankrip Lengkap</a>&nbsp;

  <a class='pull-right btn btn-primary btn-sm' href='print_report/print-transkrip.php?MhswID=$_GET[MhswID]&prodi=$m[ProdiID]' 
	  target='_BLANK'>Cetak Transkrip Lulus</a>&nbsp;
	  
	  <a class='pull-right btn btn-primary btn-sm' href=index.php?ndelox=dep/nilaitranskrip&act=transkripnilailulus&MhswID=$_GET[MhswID]>Lihat Transkrip Lulus </a>&nbsp;
	  
	  </td></tr>
	  </table>";
} 

  echo"<table class='table table-sm table-bordered table-striped'>                      
  <thead>					 
  <tr style=text-align:left;font-size:14px;color:white;font-weight:reguler;background-color:purple;width:100%;>
  <th style='width:50px;text-align:center'>No</th>                        	
  <th style='width:100px;text-align:center'>Kode</th>
  <th style='width:400px;text-align:left'>Matakuliah</th>
  <th style='width:90px;text-align:center'>SKS</th>
  <th style='width:90px;text-align:center'>Semester</th>
  <th style='width:90px;text-align:center'>Grade Nilai</th>
  <th style='width:90px;text-align:center'>TahunAK</th>
  <th style='width:90px;text-align:center'>Aksi</th>
  </tr>
  </thead>
  <tbody>";
								  

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
				AND krs.GradeNilai NOT IN ('-','TL','E','D')
				ORDER BY mk.Sesi,mk.Nama ASC ");  								
while($r=mysqli_fetch_array($tampil)){  
if ($r['GradeNilai']=='E'){			
	$c="color:red;font-weight:bold";
}
elseif ($r['GradeNilai']=='D'){			
	$c="color:red;font-weight:bold";
}
elseif ($r['GradeNilai']=='TL'){			
	$c="color:red;font-weight:bold";
}
elseif ($r['GradeNilai']=='-'){			
	$c="color:red;font-weight:bold";
}
elseif ($r['GradeNilai']==''){			
	$c="color:red;font-weight:bold";
}
else{
	$c="color:black;font-weight:reguler";
}
				         
  echo "<tr>
  <td style='text-align:center;$c;'>$no</td>
  <td style='text-align:center;$c;'>$r[MKKode] $jml</td>
  <td style='text-align:left;$c;'>$r[NamaMK]</td>	
  <td style='width:90px;text-align:center;$c;'>$r[SKS]</td>
  <td style='width:90px;text-align:center;$c;'>$r[Sesi]</td>
  <td style='width:90px;text-align:center;$c;'>$r[GradeNilai]</td>
  <td style='width:90px;text-align:center;$c;'>$r[TahunID]</td>
  <td>
  <center>   
  
  <a class='btn btn-success btn-xs' title='Edit Nilai' href='index.php?ndelox=dep/nilaitranskrip&act=editnilai&KRSID=$r[KRSID]&MhswID=$r[MhswID]&MKID=$r[MKID]&GLama=$r[GradeNilai]&prodi=$r[ProdiID]&tahun=$r[TahunID]'><i class='fa fa-edit'></i></a>
								
  <a class='btn btn-danger btn-xs' title='Delete' href='?ndelox=dep/nilaitranskrip&hapus=$r[KRSID]&JadwalID=".$_GET['JadwalID']."&tahun=".$_GET['tahun']."&MhswID=".$_GET['MhswID']."' 
  onclick=\"return confirm('Apa anda yakin akan menghapus Data ($r[MKKode]) $r[NamaMK] - Nilai $r[GradeNilai]?')\"><i class='fa fa-trash'></i></a>
  </center>
  </td>
  </tr>";
  /*cadangan
  <a class='btn btn-danger btn-xs' title='Delete' href='?ndelox=dep/nilaitranskrip&hapus=$r[KRSID]&JadwalID=".$_GET[JadwalID]."&tahun=".$_GET[tahun]."&MhswID=".$_GET[MhswID]."' 
  onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-trash'></i></a> */
$no++;

$huruf= $r['GradeNilai'];
if ($huruf=='A'){
  $bobot=4;
}
elseif ($huruf=='A-'){
  $bobot=3.70;
}
elseif ($huruf=='B'){
  $bobot=3;
}
elseif ($huruf=='B+'){
  $bobot=3.30;
}
elseif ($huruf=='B-'){
  $bobot=2.70;
}
elseif ($huruf=='C'){
  $bobot=2;
}
elseif ($huruf=='C+'){
  $bobot=2.30;
}
elseif ($huruf=='C-'){
  $bobot=1.70;
}
elseif ($huruf=='D'){
  $bobot=1;
}
else{
  $bobot=0; 
}
$tsks 		 += $r['SKS'];
$total_bobot  = $r['SKS'] * $bobot;

$bobot 		 += $bobot;
$bobot_total += $total_bobot;

$ipk = number_format($bobot_total / $tsks,2);
}

if (isset($_GET['hapus'])){
  $data=mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from krs where KRSID='".strfilter($_GET['hapus'])."'"));
  mysqli_query("INSERT into krs_del (KRSID,
												KHSID,
												MhswID,
												TahunID,
												JadwalID,
												MKID,
												MKKode,
												SKS,
												Tugas1,
												Tugas2,
												Tugas3,
												Tugas4,
												Tugas5,
												Presensi,
												_Presensi,
												UTS,
												UAS,								 
												NilaiAkhir,
												GradeNilai,
												BobotNilai,																						  
												LoginBuat,
												TanggalBuat)
										VALUES('".strfilter($data['KRSID'])."',
												 '".strfilter($data['KHSID'])."',
											   '".strfilter($data['MhswID'])."',
											   '".strfilter($data['TahunID'])."',  
											   '".strfilter($data['JadwalID'])."',												
											   '".strfilter($data['MKID'])."',
											   '".strfilter($data['MKKode'])."',
											   '".strfilter($data['SKS'])."',
											   '".strfilter($data['Tugas1'])."',
											   '".strfilter($data['Tugas2'])."',
											   '".strfilter($data['Tugas3'])."',
											   '".strfilter($data['Tugas4'])."',
											   '".strfilter($data['Tugas5'])."',
											   '".strfilter($data['Presensi'])."',
											   '".strfilter($data['_Presensi'])."',
											   '".strfilter($data['UTS'])."',
											   '".strfilter($data['UAS'])."',
											   '".strfilter($data['NilaiAkhir'])."',
											   '".strfilter($data['GradeNilai'])."',
											   '".strfilter($data['BobotNilai'])."',
											   '$_SESSION[_Login]', 
											   '".date('Y-m-d H:i:s')."')");
  mysqli_query("DELETE FROM krs where KRSID='".strfilter($_GET['hapus'])."'");//_offdulubro
  echo "<script>document.location='index.php?ndelox=dep/nilaitranskrip&JadwalID=".$_GET['JadwalID']."&tahun=".$_GET['tahun']."&MhswID=".$_GET['MhswID']."&sukses';</script>";
}
echo"
</tbody>
</table></div>


<div class='card'>
	<div class='card-header'>
	<div class='table-responsive'>
	<table>	
<tr bgcolor=$warna>
  <td colspan='2' align=left><b>Total SKS </b></td><td  colspan='6'><b> : $tsks SKS </b></td>
  </tr>
  <tr bgcolor=$warna>
	  <td colspan='2' align=left><b>Total Bobot </b></td><td colspan='6'><b> : $bobot_total </b></td>
  </tr>
  
  <tr bgcolor=$warna>
	  <td colspan='2' align=left><b>IPK </b></td><td colspan='6'><b> : $ipk </b></td>
  </tr>
";	
echo "</tbody>
</table>";
								
echo "<div class='box-footer'>
				   
</div>";
echo "</form>
</div>
</div>
</div>";      
	}
?>