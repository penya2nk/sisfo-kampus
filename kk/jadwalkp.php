<div class="card">
<div class="card-header">
<div class="card-tools">
	
<?php 
  if (isset($_GET['tahun'])){     
	 $hari=mysqli_fetch_array(mysqli_query($koneksi, "SELECT CURDATE() AS SEKARANG, DATE_ADD(CURDATE(), INTERVAL 1 DAY) AS 'BESOK',DATE_ADD(CURDATE(), INTERVAL 2 DAY) AS 'LUSA',DATE_SUB(CURDATE(), INTERVAL 1 DAY) AS 'KEMAREN'"));
	 echo "<a  href='print_reportxls/nilaiprokpxls.php?tahun=$_GET[tahun]&prodi=$_GET[prodi]' target='_BLANK'>Exp XLS</a>"; 
	 echo" | <a href='index.php?ndelox=kk/jadwalkp&act=kem&prodi=$_GET[prodi]&tahun=$_GET[tahun]&kemx=$hari[KEMAREN]'>Kemarin<a> ";	
	 echo" | <a href='index.php?ndelox=kk/jadwalkp&act=tod&prodi=$_GET[prodi]&tahun=$_GET[tahun]&todx=$hari[SEKARANG]'>Hari Ini<a> ";	
	 echo" | <a href='index.php?ndelox=kk/jadwalkp&act=bes&prodi=$_GET[prodi]&tahun=$_GET[tahun]&besx=$hari[BESOK]'>Besok<a> ";
	 echo" | <a href='index.php?ndelox=kk/jadwalkp&act=lus&prodi=$_GET[prodi]&tahun=$_GET[tahun]&lusx=$hari[LUSA]'>Lusa<a> | &nbsp;&nbsp;&nbsp;";
  }else{ 
     echo ""; 
  } 
?>


<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=kk/jadwalkp&act=tambahjadwalkp&tahun=<?php echo $_GET['tahun']; ?>&prodi=<?php echo "$_GET[prodi]"; ?>&program=<?php echo 	"$_GET[program]"; ?>'>Tambahkan Data</a>
<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=kk/jadwalkpnilai&tahun=<?php echo $_GET['tahun']; ?>&prodi=<?php echo "$_GET[prodi]"; ?>&program=<?php echo 	"$_GET[program]"; ?>'>Input Nilai</a>
</div>


	<div class="form-group row">
		<label class="col-md-3 col-form-label text-md-left"><b style='color:purple'>SEMINAR PROPOSAL KP</b></label>

		<div class="col-md-2 ">
		<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
		<input type="hidden" name='ndelox' value='kk/jadwalkp'>
		<select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
		<?php 
			echo "<option value=''>- Pilih Tahun Akademik -</option>";
			$tahun = mysqli_query($koneksi, "SELECT distinct(TahunID) FROM tahun  order by TahunID Desc"); //and NA='N'
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

		<div class="col-md-3">
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
			<input type="submit" class='btn btn-success btn-sm' value='Lihat'>
		</form>
		</div>
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
?>
<div class="card">
<div class="card-header">
<div class="table-responsive">
<table id="example" class="table table-sm table-bordered table-striped">
  <thead>
	<tr>
	  <th style='width:20px'>No</th>
	  <th style='width:100px'>NIM</th>  
	  <th style='width:400px'>Nama  <?php 
	  if ($_SESSION['_Login']=='hery'){
		  echo"<a href='index.php?ndelox=kk/jadwalkp&act=pembimbingkp&tahun=$_GET[tahun]&prodi=$_GET[prodi]'>...</a>";
	  }
	  ?></th>         
	  <th>Jadwal Seminar Proposal</th> 
	  <th>Waktu</th>                                
	  <th width='240px'>Action</th> 
	</tr>
  </thead>
  <tbody>
<?php
  $qrs = mysqli_query($koneksi, "SELECT * from vw_headerkp where TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."' order by TglMulaiSidang,JamMulai asc");//and ProgramID like '%$_GET[program]%' TglMulaiSidang
  while ($h = mysqli_fetch_array($qrs)){
   
   $Judulx 	= strtolower($h['Judul']);
   $Judul	= ucwords($Judulx);
   $p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from dosen where Login='$h[Penguji1]'"));
   $Penguji1x 	= strtolower($p1['Nama']);
   $Penguji1	= ucwords($Penguji1x);
   
   $p2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from dosen where Login='$h[Penguji2]'"));
   $Penguji2x 	= strtolower($p2['Nama']);
   $Penguji2	= ucwords($Penguji2x);
   
   $p3 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from dosen where Login='$h[Penguji3]'"));	  
   $Penguji3x 	= strtolower($p3['Nama']);
   $Penguji3	= ucwords($Penguji3x);	
   
   $Ruangx = mysqli_fetch_array(mysqli_query($koneksi, "select RuangID,Nama from ruang where RuangID='$h[TempatUjian]'"));	  
   $Ruang 	= $Ruangx['Nama'];
  $hd++;
echo"<tr   style='background-color:#DFF4FF'>
	 <td colspan='11' height='20'><b>&nbsp;  
	 <a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=kk/jadwalkp&act=edit&JadwalID=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]'><i class='fa fa-edit'></i></a>
												 
	  | <a href='index.php?ndelox=kk/jadwalkp&act=tambahanggota&JadwalID=$h[JadwalID]&KelompokID=$h[KelompokID]&prodi=$h[ProdiID]&tahun=$h[TahunID]'>
	  $hd. KLP: $h[KelompokID] [ $h[Nama] ] -  $Judul</a></b>
	  <br>
	  
	  <div align=right>
					  
	  <a title='Ujian' href='index.php?ndelox=kk/jadwalkp&act=ujianlah&JadwalID=$h[JadwalID]&program=$h[ProgramID]&prodi=$h[ProdiID]&tahun=$h[TahunID]&KampusID=Kamp03'>| Ujian </a>
	  <a target='_BLANK' title='Cetak' href='print_report/print-srpenunjukankp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&prodi=$h[ProdiID]'>| Cetak Surat Pembimbing</a>
	  <a target='_BLANK' title='Cetak' href='print_report/print-baujianprokp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&prodi=$h[ProdiID]'>| Cetak Berita Acara</a>
	  
	  <a target='_BLANK' title='Cetak' href='print_report/print-frmnilaiprokp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&prodi=$h[ProdiID]'>| Cetak Form Nilai</a> 				  
	  <a target='_BLANK' title='Cetak' href='print_report/print-kwitansiprokp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&prodi=$h[ProdiID]'>| Cetak Kwitansi</a> |
	  <a title='Hapus' href='index.php?ndelox=kk/jadwalkp&del=$h[JadwalID]&program=$h[ProgramID]&prodi=$h[ProdiID]&tahun=$h[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"> Delete |  </a> Ruang: $Ruang, Penguji= $Penguji1, $Penguji2, $Penguji3
	  
	  </div>
	 </td>
	 </tr>";  
		
  $tampil = mysqli_query($koneksi, "SELECT * from vw_jadwalkp where KelompokID='$h[KelompokID]' AND TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."' ");                      
  $no = 1;
  while($r=mysqli_fetch_array($tampil)){			    
		if ($r['Ket']=='1'){
			$Ketr="<b style=color:green> Lulus</b>";				
			$c="style=color:green";
			}
		else if ($r['Ket']=='2'){
			$Ketr="<b style=color:red> Gagal</b>";
			$c="style=color:red";
		}else{
			$Ketr="<b style=color:purple> Belum Seminar</b>";
			$c="style=color:purple";
		}    
  $TglMulaiSidang 	=tgl_indo($r['TglMulaiSidang']);
  $tanggal = $r['TglMulaiSidang'];
  $day = date('D', strtotime($tanggal));
  $dayList = array(
	  'Sun' => 'Minggu',
	  'Mon' => 'Senin',
	  'Tue' => 'Selasa',
	  'Wed' => 'Rabu',
	  'Thu' => 'Kamis',
	  'Fri' => 'Jumat',
	  'Sat' => 'Sabtu'
  );
  
  
  $bul = date('m', strtotime($tanggal));
  $bulList = array(
	  '01' => 'Januari',
	  '02' => 'Februari',
	  '03' => 'Maret',
	  '04' => 'April',
	  '05' => 'Mei',
	  '06' => 'Juni',
	  '07' => 'Juli',
	  '08' => 'Agustus',
	  '09' => 'September',
	  '10' => 'Oktober',
	  '11' => 'Nopember',
	  '12' => 'Desember'
  );
  echo "<tr style=font-color: red><td>$no</td>   
			<td $warna>$r[MhswID]</td>  
			<td $c>$r[Nama] <b>[ <a href='index.php?ndelox=smsmhs&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&telepon=$r[Handphone]'> $r[Handphone] </a> ]</b></td>              		   
			<td $warna>$dayList[$day], $TglMulaiSidang</td>
			<td $warna>".substr($r['JamMulai'],0,5)."  - ".substr($r['JamSelesai'],0,5)."</td>";
					
echo "<td style='width:70px;font-weight:bold !important'>
<a href='index.php?ndelox=kk/jadwalkp&normal&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'> [ N ] </a> -
<a href='index.php?ndelox=kk/jadwalkp&lulus&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'> [ R ] </a> -
<a href='index.php?ndelox=kk/jadwalkp&gagalx&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'> [ X ] </a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=kk/jadwalkp&act=edit&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'><i class='fa fa-edit'></i></a>
<a class='btn btn-danger btn-xs' title='Hapus' href='index.php?ndelox=kk/jadwalkp&hapus=$r[MhswID]&program=$r[ProgramID]&prodi=$r[ProdiID]&tahun=$r[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
</td>
</tr>";
$no++;
}
}

if (isset($_GET['hapus'])){
mysqli_query($koneksi, "DELETE FROM jadwal_kp_anggota where MhswID='".strfilter($_GET['hapus'])."'");
echo "<script>document.location='index.php?ndelox=kk/jadwalkp&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
}
if (isset($_GET['del'])){
mysqli_query($koneksi, "DELETE FROM jadwal_kp where JadwalID='".strfilter($_GET['del'])."'");
mysqli_query($koneksi, "DELETE FROM jadwal_kp_anggota where JadwalID='".strfilter($_GET['del'])."'");
echo "<script>document.location='index.php?ndelox=kk/jadwalkp&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
}

if (isset($_GET['normal'])){
mysqli_query($koneksi, "UPDATE jadwal_kp Set Ket='0' where JadwalID='".strfilter($_GET['IDX'])."'");
echo "<script>document.location='index.php?ndelox=kk/jadwalkp&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
} 
if (isset($_GET['lulus'])){
mysqli_query($koneksi, "UPDATE jadwal_kp Set Ket='1' where JadwalID='".strfilter($_GET['IDX'])."'");
echo "<script>document.location='index.php?ndelox=kk/jadwalkp&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
} 
if (isset($_GET['gagalx'])){
mysqli_query($koneksi, "UPDATE jadwal_kp Set Ket='2' where JadwalID='".strfilter($_GET['IDX'])."'");
echo "<script>document.location='index.php?ndelox=kk/jadwalkp&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
}
?>
<tbody>
</table>
</div>
</div>
</div>
<?php 
}

//YESTERDDAY -----------------------------------------------------
elseif($_GET['act']=='kem'){ ?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example" class="table table-bordered table-striped">
<thead>
<tr>
  <th style='width:20px'>No</th>
  <th style='width:100px'>NIM</th>  
  <th style='width:400px'>Nama</th>               
  <th>Jadwal Seminar Proposal</th> 
  <th>Waktu</th>                                
  <th width='240px'>Action</th> 
</tr>
</thead>
<tbody>
<?php
  $qrs = mysqli_query($koneksi, "SELECT * from vw_headerkp where TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."' and TglMulaiSidang='".strfilter($_GET['kemx'])."' order by TglMulaiSidang, JamMulai asc");
  while ($h = mysqli_fetch_array($qrs)){			
  $Judulx 	= strtolower($h['Judul']);
   $Judul	= ucwords($Judulx);
   $p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from dosen where Login='$h[Penguji1]'"));
   $Penguji1x 	= strtolower($p1['Nama']);
   $Penguji1	= ucwords($Penguji1x);
   
   $p2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from dosen where Login='$h[Penguji2]'"));
   $Penguji2x 	= strtolower($p2['Nama']);
   $Penguji2	= ucwords($Penguji2x);
   
   $p3 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from dosen where Login='$h[Penguji3]'"));	  
   $Penguji3x 	= strtolower($p3['Nama']);
   $Penguji3	= ucwords($Penguji3x);	
   
   $Ruangx = mysqli_fetch_array(mysqli_query($koneksi, "select RuangID,Nama from ruang where RuangID='$h[TempatUjian]'"));	  
   $Ruang 	= $Ruangx['Nama'];   
  $hd++;
  echo"<tr style='background-color:#DFF4FF'>
	 <td colspan='11' height='20'><b>&nbsp;  
	 <a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=kk/jadwalkp&act=edit&JadwalID=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]'><i class='fa fa-edit'></i></a>												 
	  | <a href='index.php?ndelox=kk/jadwalkp&act=tambahanggota&JadwalID=$h[JadwalID]&KelompokID=$h[KelompokID]&prodi=$h[ProdiID]&tahun=$h[TahunID]'>
	  $hd. KLP: $h[KelompokID] [ $h[Nama] ] -  $Judul</a></b>
	  <br>
	  
	  <div align=right>					  
	  <a title='Ujian' href='index.php?ndelox=kk/jadwalkp&act=ujianlah&JadwalID=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]&KampusID=Kamp03'>| Ujian </a>	  
	  <a target='_BLANK' title='Cetak' href='print_report/print-baujianprokp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&prodi=$h[ProdiID]'>| Cetak Berita Acara</a>	  
	  <a target='_BLANK' title='Cetak' href='print_report/print-frmnilaiprokp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&prodi=$h[ProdiID]'>| Cetak Form Nilai</a> 				  
	  <a target='_BLANK' title='Cetak' href='print_report/print-kwitansiprokp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&prodi=$h[ProdiID]'>| Cetak Kwitansi</a> |
	  <a title='Hapus' href='index.php?ndelox=kk/jadwalkp&act=kem&del=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]&kemx=$_GET[kemx]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"> Delete |  </a> Ruang: $Ruang, Penguji= $Penguji1, $Penguji2, $Penguji3	  
	  </div>
	 </td>
	 </tr>";  
		
	$tampil = mysqli_query($koneksi, "SELECT * from vw_jadwalkp where KelompokID='$h[KelompokID]' AND TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."' and TglMulaiSidang='".strfilter($_GET['kemx'])."'");                      
	$no = 1;
	while($r=mysqli_fetch_array($tampil)){	
	if ($r['Ket']=='1'){
		$Ketr="<b style=color:green> Lulus</b>";				
		$c="style=color:green";
		}
	else if ($r['Ket']=='2'){
		$Ketr="<b style=color:red> Gagal</b>";
		$c="style=color:red";
	}else{
		$Ketr="<b style=color:purple> Belum Seminar</b>";
		$c="style=color:purple";
	}  
	$TglMulaiSidang 	= tgl_indo($r['TglMulaiSidang']);
	$tanggal 			= $r['TglMulaiSidang'];
	$day = date('D', strtotime($tanggal));
	$dayList = array(
	  'Sun' => 'Minggu',
	  'Mon' => 'Senin',
	  'Tue' => 'Selasa',
	  'Wed' => 'Rabu',
	  'Thu' => 'Kamis',
	  'Fri' => 'Jumat',
	  'Sat' => 'Sabtu'
	);

	$bul = date('m', strtotime($tanggal));
	$bulList = array(
	  '01' => 'Januari',
	  '02' => 'Februari',
	  '03' => 'Maret',
	  '04' => 'April',
	  '05' => 'Mei',
	  '06' => 'Juni',
	  '07' => 'Juli',
	  '08' => 'Agustus',
	  '09' => 'September',
	  '10' => 'Oktober',
	  '11' => 'Nopember',
	  '12' => 'Desember'
	);
	echo "<tr style=font-color: red><td>$no</td>   
			<td $warna>$r[MhswID]</td>  
			<td $c>$r[Nama] <b>[ <a href='index.php?ndelox=smsmhs&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&telepon=$r[Handphone]'> $r[Handphone] </a> ]</b></td>              		   
			<td $warna>$dayList[$day], $TglMulaiSidang</td>
			<td $warna>".substr($r['JamMulai'],0,5)."  - ".substr($r['JamSelesai'],0,5)."</td>";
					
	echo "<td style='width:70px;font-weight:bold !important'>
	<a href='index.php?ndelox=kk/jadwalkp&act=kem&normal&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&kemx=$_GET[kemx]'> [ N ] </a> -
	<a href='index.php?ndelox=kk/jadwalkp&act=kem&lulus&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&kemx=$_GET[kemx]'> [ R ] </a> -
	<a href='index.php?ndelox=kk/jadwalkp&act=kem&gagalx&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&kemx=$_GET[kemx]'> [ X ] </a>
&nbsp;&nbsp;&nbsp;&nbsp;
	<a class='btn btn-danger btn-xs' title='Hapus' href='index.php?ndelox=kk/jadwalkp&act=kem&hapus=$r[MhswID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&kemx=$_GET[kemx]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
	</center></td>
	</tr>";
	  $no++;
	  }
	}//hari
	if (isset($_GET['hapus'])){
		mysqli_query($koneksi, "DELETE FROM jadwal_kp_anggota where MhswID='".strfilter($_GET['hapus'])."'");
		echo "<script>document.location='index.php?ndelox=kk/jadwalkp&act=kem&prodi=$_GET[prodi]&tahun=$_GET[tahun]&kemx=$_GET[kemx]';</script>";
	}
	if (isset($_GET['del'])){
		 mysqli_query($koneksi, "DELETE FROM jadwal_kp where JadwalID='".strfilter($_GET['del'])."'");
		 mysqli_query($koneksi, "DELETE FROM jadwal_kp_anggota where JadwalID='".strfilter($_GET['del'])."'");
		echo "<script>document.location='index.php?ndelox=kk/jadwalkp&act=kem&prodi=$_GET[prodi]&tahun=$_GET[tahun]&kemx=$_GET[kemx]';</script>";
	}
	if (isset($_GET['normal'])){
		mysqli_query($koneksi, "UPDATE jadwal_kp Set Ket='0' where JadwalID='".strfilter($_GET['IDX'])."'");
		echo "<script>document.location='index.php?ndelox=kk/jadwalkp&act=kem&prodi=$_GET[prodi]&tahun=$_GET[tahun]&kemx=$_GET[kemx]';</script>";
	} 
	if (isset($_GET['lulus'])){
		mysqli_query($koneksi, "UPDATE jadwal_kp Set Ket='1' where JadwalID='".strfilter($_GET['IDX'])."'");
		echo "<script>document.location='index.php?ndelox=kk/jadwalkp&act=kem&prodi=$_GET[prodi]&tahun=$_GET[tahun]&kemx=$_GET[kemx]';</script>";
	} 
	if (isset($_GET['gagalx'])){
		mysqli_query($koneksi, "UPDATE jadwal_kp Set Ket='2' where JadwalID='".strfilter($_GET['IDX'])."'");
		echo "<script>document.location='index.php?ndelox=kk/jadwalkp&act=kem&prodi=$_GET[prodi]&tahun=$_GET[tahun]&kemx=$_GET[kemx]';</script>";
	}
?>
	<tbody>
  </table>
  </div>
</div>
</div>
<?php 
}

//NOW -----------------------------------------------------
elseif($_GET['act']=='tod'){ ?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example" class="table table-bordered table-striped">
<thead>
<tr>
<th style='width:20px'>No</th>
<th style='width:100px'>NIM</th>  
<th style='width:400px'>Nama</th>               
<th>Jadwal Seminar Proposal</th> 
<th>Waktu</th>                                
<th width='240px'>Action</th> 
</tr>
</thead>
<tbody>
<?php
  $qrs = mysqli_query($koneksi, "SELECT * from vw_headerkp where TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."' and TglMulaiSidang='".date('Y-m-d')."' order by TglMulaiSidang,JamMulai asc");
  while ($h = mysqli_fetch_array($qrs)){
			   $Judulx 	= strtolower($h['Judul']);
		       $Judul	= ucwords($Judulx);
			   $p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from dosen where Login='$h[Penguji1]'"));
		       $Penguji1x 	= strtolower($p1['Nama']);
		       $Penguji1	= ucwords($Penguji1x);
			   
			   $p2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from dosen where Login='$h[Penguji2]'"));
			   $Penguji2x 	= strtolower($p2['Nama']);
		       $Penguji2	= ucwords($Penguji2x);
			   
			   $p3 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from dosen where Login='$h[Penguji3]'"));	  
			   $Penguji3x 	= strtolower($p3['Nama']);
		       $Penguji3	= ucwords($Penguji3x);	

			   $Ruangx = mysqli_fetch_array(mysqli_query($koneksi, "select RuangID,Nama from ruang where RuangID='$h[TempatUjian]'"));	  
			   $Ruang 	= $Ruangx['Nama'];
	  $hd++;
  echo"<tr style='background-color:#DFF4FF'>
	 <td colspan='11' height='20'><b>&nbsp;  
	 <a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=kk/jadwalkp&act=edit&JadwalID=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]'><i class='fa fa-edit'></i></a>												 
	  | <a href='index.php?ndelox=kk/jadwalkp&act=tambahanggota&JadwalID=$h[JadwalID]&KelompokID=$h[KelompokID]&prodi=$h[ProdiID]&tahun=$h[TahunID]'>
	  $hd. KLP: $h[KelompokID] [ $h[Nama] ] -  $h[Judul]</a></b>
	  <br>
	  
	  <div align=right>					  
	  <a title='Ujian' href='index.php?ndelox=kk/jadwalkp&act=ujianlah&JadwalID=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]&KampusID=Kamp03'>| Ujian </a>	  
	  <a target='_BLANK' title='Cetak' href='print_report/print-baujianprokp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&prodi=$h[ProdiID]'>| Cetak Berita Acara</a>	  
	  <a target='_BLANK' title='Cetak' href='print_report/print-frmnilaiprokp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&prodi=$h[ProdiID]'>| Cetak Form Nilai</a> 				  
	  <a target='_BLANK' title='Cetak' href='print_report/print-kwitansiprokp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&prodi=$h[ProdiID]'>| Cetak Kwitansi</a> |
	  <a title='Hapus' href='index.php?ndelox=kk/jadwalkp&del=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"> Delete |  </a> Ruang: $Ruang, Penguji= $Penguji1, $Penguji2, $Penguji3	  
	  </div>
	 </td>
	 </tr>";  
		
	$tampil = mysqli_query($koneksi, "SELECT * from vw_jadwalkp where KelompokID='$h[KelompokID]' AND TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."' and TglMulaiSidang='".date('Y-m-d')."'");                      
	$no = 1;
	while($r=mysqli_fetch_array($tampil)){	
	if ($r[Ket]=='1'){
		$Ketr="<b style=color:green> Lulus</b>";				
		$c="style=color:green";
		}
	else if ($r[Ket]=='2'){
		$Ketr="<b style=color:red> Gagal</b>";
		$c="style=color:red";
	}else{
		$Ketr="<b style=color:purple> Belum Seminar</b>";
		$c="style=color:purple";
	}  	
	$TglMulaiSidang 	= tgl_indo($r['TglMulaiSidang']);
	$tanggal 			= $r['TglMulaiSidang'];
	$day = date('D', strtotime($tanggal));
	$dayList = array(
	  'Sun' => 'Minggu',
	  'Mon' => 'Senin',
	  'Tue' => 'Selasa',
	  'Wed' => 'Rabu',
	  'Thu' => 'Kamis',
	  'Fri' => 'Jumat',
	  'Sat' => 'Sabtu'
	);

	$bul = date('m', strtotime($tanggal));
	$bulList = array(
	  '01' => 'Januari',
	  '02' => 'Februari',
	  '03' => 'Maret',
	  '04' => 'April',
	  '05' => 'Mei',
	  '06' => 'Juni',
	  '07' => 'Juli',
	  '08' => 'Agustus',
	  '09' => 'September',
	  '10' => 'Oktober',
	  '11' => 'Nopember',
	  '12' => 'Desember'
	);
	echo "<tr style=font-color: red><td>$no</td>   
			<td $warna>$r[MhswID]</td>  
			<td $c>$r[Nama] <b>[ <a href='index.php?ndelox=smsmhs&JadwalID=$r[JadwalID]&program=$r[ProgramID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&telepon=$r[Handphone]'> $r[Handphone] </a> ]</b></td>              
		   
			<td $warna>$dayList[$day], $TglMulaiSidang</td>
			<td $warna>".substr($r['JamMulai'],0,5)."  - ".substr($r['JamSelesai'],0,5)."</td>";
					
	echo "<td style='width:70px;font-weight:bold !important'>
	<a href='index.php?ndelox=kk/jadwalkp&act=tod&normal&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'> [ N ] </a> -
	<a href='index.php?ndelox=kk/jadwalkp&act=tod&lulus&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'> [ R ] </a> -
	<a href='index.php?ndelox=kk/jadwalkp&act=tod&gagalx&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'> [ X ] </a>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<a class='btn btn-danger btn-xs' title='Hapus' href='index.php?ndelox=kk/jadwalkp&act=tod&hapus=$r[MhswID]&prodi=$r[ProdiID]&tahun=$r[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
	</center></td>";
	echo "</tr>";
			  $no++;
			  }
	}//hari
	if (isset($_GET['hapus'])){
		mysqli_query($koneksi, "DELETE FROM jadwal_kp_anggota where MhswID='".strfilter($_GET['hapus'])."'");
		echo "<script>document.location='index.php?ndelox=kk/jadwalkp&act=tod&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
	}
	if (isset($_GET['del'])){
		 mysqli_query($koneksi, "DELETE FROM jadwal_kp where JadwalID='".strfilter($_GET['del'])."'");
		 mysqli_query($koneksi, "DELETE FROM jadwal_kp_anggota where JadwalID='$_GET[del]'");
		echo "<script>document.location='index.php?ndelox=kk/jadwalkp&act=tod&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
	}
	if (isset($_GET['normal'])){
		mysqli_query($koneksi, "UPDATE jadwal_kp Set Ket='0' where JadwalID='".strfilter($_GET['IDX'])."'");
		echo "<script>document.location='index.php?ndelox=kk/jadwalkp&act=tod&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
	} 
	if (isset($_GET['lulus'])){
		mysqli_query($koneksi, "UPDATE jadwal_kp Set Ket='1' where JadwalID='".strfilter($_GET['IDX'])."'");
		echo "<script>document.location='index.php?ndelox=kk/jadwalkp&act=tod&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
	} 
	if (isset($_GET['gagalx'])){
		mysqli_query($koneksi, "UPDATE jadwal_kp Set Ket='2' where JadwalID='".strfilter($_GET['IDX'])."'");
		echo "<script>document.location='index.php?ndelox=kk/jadwalkp&act=tod&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
	}
?>
	<tbody>
  </table>
  </div>
</div>
</div>
<?php 
}


//BESOK -----------------------------------------------------
elseif($_GET['act']=='bes'){ ?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example" class="table table-bordered table-striped">
<thead>
<tr>
  <th style='width:20px'>No</th>
  <th style='width:100px'>NIM</th>  
  <th style='width:400px'>Nama</th>               
  <th>Jadwal Seminar Proposal</th> 
  <th>Waktu</th>                                
  <th width='240px'>Action</th> 
</tr>
</thead>
<tbody>
<?php
  $qrs = mysqli_query($koneksi, "SELECT * from vw_headerkp where TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."' and TglMulaiSidang='".strfilter($_GET['besx'])."' order by TglMulaiSidang,JamMulai asc");
  while ($h = mysqli_fetch_array($qrs)){
			   $Judulx 	= strtolower($h['Judul']);
		       $Judul	= ucwords($Judulx);
			   $p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from dosen where Login='$h[Penguji1]'"));
		       $Penguji1x 	= strtolower($p1['Nama']);
		       $Penguji1	= ucwords($Penguji1x);
			   
			   $p2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from dosen where Login='$h[Penguji2]'"));
			   $Penguji2x 	= strtolower($p2['Nama']);
		       $Penguji2	= ucwords($Penguji2x);
			   
			   $p3 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from dosen where Login='$h[Penguji3]'"));	  
			   $Penguji3x 	= strtolower($p3['Nama']);
		       $Penguji3	= ucwords($Penguji3x);	
			   
			   $Ruangx = mysqli_fetch_array(mysqli_query($koneksi, "select RuangID,Nama from ruang where RuangID='$h[TempatUjian]'"));	  
			   $Ruang 	= $Ruangx['Nama'];			   
	  $hd++;
  echo"<tr style='background-color:#DFF4FF'>
	 <td colspan='11' height='20'><b>&nbsp;  
	 <a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=kk/jadwalkp&act=edit&JadwalID=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]'><i class='fa fa-edit'></i></a>												 
	  | <a href='index.php?ndelox=kk/jadwalkp&act=tambahanggota&JadwalID=$h[JadwalID]&KelompokID=$h[KelompokID]&prodi=$h[ProdiID]&tahun=$h[TahunID]'>
	  $hd. KLP: $h[KelompokID] [ $h[Nama] ] -  $h[Judul]</a></b>
	  <br>
	  
	  <div align=right>					  
	  <a title='Ujian' href='index.php?ndelox=kk/jadwalkp&act=ujianlah&JadwalID=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]&KampusID=Kamp03'>| Ujian </a>	  
	  <a target='_BLANK' title='Cetak' href='print_report/print-baujianprokp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&prodi=$h[ProdiID]'>| Cetak Berita Acara</a>	  
	  <a target='_BLANK' title='Cetak' href='print_report/print-frmnilaiprokp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&prodi=$h[ProdiID]'>| Cetak Form Nilai</a> 				  
	  <a target='_BLANK' title='Cetak' href='print_report/print-kwitansiprokp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&prodi=$h[ProdiID]'>| Cetak Kwitansi</a> |
	  <a title='Hapus' href='index.php?ndelox=kk/jadwalkp&del=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]&besx=$_GET[besx]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"> Delete |  </a> Ruang: $Ruang, Penguji= $Penguji1, $Penguji2, $Penguji3	  
	  </div>
	 </td>
	 </tr>";  
		
	$tampil = mysqli_query($koneksi, "SELECT * from vw_jadwalkp where KelompokID='$h[KelompokID]' AND TahunID='".strfilter($_GET[tahun])."' and ProdiID='".strfilter($_GET[prodi])."' and TglMulaiSidang='".strfilter($_GET[besx])."'");                      
	$no = 1;
	while($r=mysqli_fetch_array($tampil)){	
	if ($r['Ket']=='1'){
		$Ketr="<b style=color:green> Lulus</b>";				
		$c="style=color:green";
		}
	else if ($r['Ket']=='2'){
		$Ketr="<b style=color:red> Gagal</b>";
		$c="style=color:red";
	}else{
		$Ketr="<b style=color:purple> Belum Seminar</b>";
		$c="style=color:purple";
	}  
	$TglMulaiSidang 	= tgl_indo($r['TglMulaiSidang']);
	$tanggal 			= $r['TglMulaiSidang'];
	$day = date('D', strtotime($tanggal));
	$dayList = array(
	  'Sun' => 'Minggu',
	  'Mon' => 'Senin',
	  'Tue' => 'Selasa',
	  'Wed' => 'Rabu',
	  'Thu' => 'Kamis',
	  'Fri' => 'Jumat',
	  'Sat' => 'Sabtu'
	);

	$bul = date('m', strtotime($tanggal));
	$bulList = array(
	  '01' => 'Januari',
	  '02' => 'Februari',
	  '03' => 'Maret',
	  '04' => 'April',
	  '05' => 'Mei',
	  '06' => 'Juni',
	  '07' => 'Juli',
	  '08' => 'Agustus',
	  '09' => 'September',
	  '10' => 'Oktober',
	  '11' => 'Nopember',
	  '12' => 'Desember'
	);
	echo "<tr style=font-color: red><td>$no</td>   
			<td $warna>$r[MhswID]</td>  
			<td $c>$r[Nama] <b>[ <a href='index.php?ndelox=smsmhs&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&telepon=$r[Handphone]'> $r[Handphone] </a> ]</b></td>              		   
			<td $warna>$dayList[$day], $TglMulaiSidang</td>
			<td $warna>".substr($r['JamMulai'],0,5)."  - ".substr($r['JamSelesai'],0,5)."</td>";					
	echo "<td style='width:70px;font-weight:bold !important'>
	<a href='index.php?ndelox=kk/jadwalkp&act=bes&normal&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&besx=$_GET[besx]'> [ N ] </a> -
	<a href='index.php?ndelox=kk/jadwalkp&act=bes&lulus&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&besx=$_GET[besx]'> [ R ] </a> -
	<a href='index.php?ndelox=kk/jadwalkp&act=bes&gagalx&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&besx=$_GET[besx]'> [ X ] </a>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<a class='btn btn-danger btn-xs' title='Hapus' href='index.php?ndelox=kk/jadwalkp&act=bes&hapus=$r[MhswID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&besx=$_GET[besx]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
	</center></td>";
	echo "</tr>";
			  $no++;
			  }
	}//hari
	if (isset($_GET['hapus'])){
		mysqli_query($koneksi, "DELETE FROM jadwal_kp_anggota where MhswID='".strfilter($_GET['hapus'])."'");
		echo "<script>document.location='index.php?ndelox=kk/jadwalkp&act=bes&prodi=$_GET[prodi]&tahun=$_GET[tahun]&besx=$_GET[besx]';</script>";
	}
	if (isset($_GET['del'])){
		 mysqli_query($koneksi, "DELETE FROM jadwal_kp where JadwalID='".strfilter($_GET['del'])."'");
		 mysqli_query($koneksi, "DELETE FROM jadwal_kp_anggota where JadwalID='".strfilter($_GET['del'])."'");
		echo "<script>document.location='index.php?ndelox=kk/jadwalkp&act=bes&prodi=$_GET[prodi]&tahun=$_GET[tahun]&besx=$_GET[besx]';</script>";
	}
	if (isset($_GET['normal'])){
		mysqli_query($koneksi, "UPDATE jadwal_kp Set Ket='0' where JadwalID='".strfilter($_GET['IDX'])."'");
		echo "<script>document.location='index.php?ndelox=kk/jadwalkp&act=bes&prodi=$_GET[prodi]&tahun=$_GET[tahun]&besx=$_GET[besx]';</script>";
	} 
	if (isset($_GET['lulus'])){
		mysqli_query($koneksi, "UPDATE jadwal_kp Set Ket='1' where JadwalID='".strfilter($_GET['IDX'])."'");
		echo "<script>document.location='index.php?ndelox=kk/jadwalkp&act=bes&prodi=$_GET[prodi]&tahun=$_GET[tahun]&besx=$_GET[besx]';</script>";
	} 
	if (isset($_GET['gagalx'])){
		mysqli_query($koneksi, "UPDATE jadwal_kp Set Ket='2' where JadwalID='".strfilter($_GET['IDX'])."'");
		echo "<script>document.location='index.php?ndelox=kk/jadwalkp&act=bes&prodi=$_GET[prodi]&tahun=$_GET[tahun]&besx=$_GET[besx]';</script>";
	}	
?>
	<tbody>
  </table>
  </div>
</div>
</div>
<?php 
}


//LUSA -----------------------------------------------------
elseif($_GET['act']=='lus'){ ?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example" class="table table-bordered table-striped">
<thead>
<tr>
  <th style='width:20px'>No</th>
  <th style='width:100px'>NIM</th>  
  <th style='width:400px'>Nama</th>               
  <th>Jadwal Seminar Proposal</th> 
  <th>Waktu</th>                                
   <th width='240px'>Action</th> 
</tr>
</thead>
<tbody>
<?php
  $qrs = mysqli_query($koneksi, "SELECT * from vw_headerkp where TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."' and TglMulaiSidang='".strfilter($_GET['lusx'])."' order by TglMulaiSidang,JamMulai asc");
  while ($h = mysqli_fetch_array($qrs)){
			   $Judulx 	= strtolower($h['Judul']);
		       $Judul	= ucwords($Judulx);
			   $p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from dosen where Login='$h[Penguji1]'"));
		       $Penguji1x 	= strtolower($p1['Nama']);
		       $Penguji1	= ucwords($Penguji1x);
			   
			   $p2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from dosen where Login='$h[Penguji2]'"));
			   $Penguji2x 	= strtolower($p2['Nama']);
		       $Penguji2	= ucwords($Penguji2x);
			   
			   $p3 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama from dosen where Login='$h[Penguji3]'"));	  
			   $Penguji3x 	= strtolower($p3['Nama']);
		       $Penguji3	= ucwords($Penguji3x);	 
			   
			   $Ruangx = mysqli_fetch_array(mysqli_query($koneksi, "select RuangID,Nama from ruang where RuangID='$h[TempatUjian]'"));	  
			   $Ruang 	= $Ruangx['Nama'];
	  $hd++;
  echo"<tr style='background-color:#DFF4FF'>
	 <td colspan='11' height='20'><b>&nbsp;  
	 <a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=kk/jadwalkp&act=edit&JadwalID=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]'><i class='fa fa-edit'></i></a>												 
	  | <a href='index.php?ndelox=kk/jadwalkp&act=tambahanggota&JadwalID=$h[JadwalID]&KelompokID=$h[KelompokID]&prodi=$h[ProdiID]&tahun=$h[TahunID]'>
	  $hd. KLP: $h[KelompokID] [ $h[Nama] ] -  $h[Judul]</a></b>
	  <br>
	  
	  <div align=right>					  
	  <a title='Ujian' href='index.php?ndelox=kk/jadwalkp&act=ujianlah&JadwalID=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]&KampusID=Kamp03'>| Ujian </a>	  
	  <a target='_BLANK' title='Cetak' href='print_report/print-baujianprokp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&prodi=$h[ProdiID]'>| Cetak Berita Acara</a>	  
	  <a target='_BLANK' title='Cetak' href='print_report/print-frmnilaiprokp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&prodi=$h[ProdiID]'>| Cetak Form Nilai</a> 				  
	  <a target='_BLANK' title='Cetak' href='print_report/print-kwitansiprokp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&prodi=$h[ProdiID]'>| Cetak Kwitansi</a> |
	  <a title='Hapus' href='index.php?ndelox=kk/jadwalkp&act=lus&del=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]&lusx=$_GET[lusx]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"> Delete |  </a> Ruang: $Ruang, Penguji= $Penguji1, $Penguji2, $Penguji3	  
	  </div>
	 </td>
	 </tr>";  
		
	$tampil = mysqli_query($koneksi, "SELECT * from vw_jadwalkp where KelompokID='$h[KelompokID]' AND TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."' and TglMulaiSidang='".strfilter($_GET['lusx'])."'");                      
	$no = 1;
	while($r=mysqli_fetch_array($tampil)){	
	if ($r['Ket']=='1'){
		$Ketr="<b style=color:green> Lulus</b>";				
		$c="style=color:green";
		}
	else if ($r['Ket']=='2'){
		$Ketr="<b style=color:red> Gagal</b>";
		$c="style=color:red";
	}else{
		$Ketr="<b style=color:purple> Belum Seminar</b>";
		$c="style=color:purple";
	}    
	$TglMulaiSidang 	= tgl_indo($r['TglMulaiSidang']);
	$tanggal 			= $r['TglMulaiSidang'];

	$day = date('D', strtotime($tanggal));
	$dayList = array(
	  'Sun' => 'Minggu',
	  'Mon' => 'Senin',
	  'Tue' => 'Selasa',
	  'Wed' => 'Rabu',
	  'Thu' => 'Kamis',
	  'Fri' => 'Jumat',
	  'Sat' => 'Sabtu'
	);

	$bul = date('m', strtotime($tanggal));
	$bulList = array(
	  '01' => 'Januari',
	  '02' => 'Februari',
	  '03' => 'Maret',
	  '04' => 'April',
	  '05' => 'Mei',
	  '06' => 'Juni',
	  '07' => 'Juli',
	  '08' => 'Agustus',
	  '09' => 'September',
	  '10' => 'Oktober',
	  '11' => 'Nopember',
	  '12' => 'Desember'
	);
	echo "<tr style=font-color: red><td>$no</td>   
			<td $warna>$r[MhswID]</td>  
			<td $c>$r[Nama] <b>[ <a href='index.php?ndelox=smsmhs&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&telepon=$r[Handphone]'> $r[Handphone] </a> ]</b></td>              		   
			<td $warna>$dayList[$day], $TglMulaiSidang</td>
			<td $warna>$r[JamMulai] - $r[JamSelesai]</td>";
					
	echo "<td style='width:70px;font-weight:bold !important'>
	<a href='index.php?ndelox=kk/jadwalkp&act=lus&normal&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&lusx=$_GET[lusx]'> [ N ] </a> -
	<a href='index.php?ndelox=kk/jadwalkp&act=lus&lulus&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&lusx=$_GET[lusx]'> [ R ] </a> -
	<a href='index.php?ndelox=kk/jadwalkp&act=lus&gagalx&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&lusx=$_GET[lusx]'> [ X ] </a>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=kk/jadwalkp&act=edit&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'><i class='fa fa-edit'></i></a>
	<a class='btn btn-danger btn-xs' title='Hapus' href='index.php?ndelox=kk/jadwalkp&act=lus&hapus=$r[MhswID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&lusx=$_GET[lusx]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
	</td>";
	echo "</tr>";
			  $no++;
			  }
	}//hari
	if (isset($_GET['hapus'])){
		mysqli_query($koneksi, "DELETE FROM jadwal_kp_anggota where MhswID='".strfilter($_GET['hapus'])."'");
		echo "<script>document.location='index.php?ndelox=kk/jadwalkp&act=lus&prodi=$_GET[prodi]&tahun=$_GET[tahun]&lusx=$_GET[lusx]';</script>";
	}
	if (isset($_GET['del'])){
		 mysqli_query($koneksi, "DELETE FROM jadwal_kp where JadwalID='".strfilter($_GET['del'])."'");
		 mysqli_query($koneksi, "DELETE FROM jadwal_kp_anggota where JadwalID='".strfilter($_GET['del'])."'");
		echo "<script>document.location='index.php?ndelox=kk/jadwalkp&act=lus&prodi=$_GET[prodi]&tahun=$_GET[tahun]&lusx=$_GET[lusx]';</script>";
	}
	if (isset($_GET['normal'])){
		mysqli_query($koneksi, "UPDATE jadwal_kp Set Ket='0' where JadwalID='".strfilter($_GET['IDX'])."'");
		echo "<script>document.location='index.php?ndelox=kk/jadwalkp&act=lus&prodi=$_GET[prodi]&tahun=$_GET[tahun]&lusx=$_GET[lusx]';</script>";
	} 
	if (isset($_GET['lulus'])){
		mysqli_query($koneksi, "UPDATE jadwal_kp Set Ket='1' where JadwalID='".strfilter($_GET['IDX'])."'");
		echo "<script>document.location='index.php?ndelox=kk/jadwalkp&act=lus&prodi=$_GET[prodi]&tahun=$_GET[tahun]&lusx=$_GET[lusx]';</script>";
	} 
	if (isset($_GET['gagalx'])){
		mysqli_query($koneksi, "UPDATE jadwal_kp Set Ket='2' where JadwalID='".strfilter($_GET['IDX'])."'");
		echo "<script>document.location='index.php?ndelox=kk/jadwalkp&act=lus&prodi=$_GET[prodi]&tahun=$_GET[tahun]&lusx=$_GET[lusx]';</script>";
	}	
?>
	<tbody>
  </table>
  </div>
</div>
</div>

<?php 
}

elseif($_GET['act']=='tambahjadwalkp'){
if ($_GET['prodi']=='SI'){
   $b='SI';
}else{
   $b='TI';
}

	//$w = mysqli_fetch_array(mysqli_query($koneksi, "SELECT KelompokID,ProdiID,TahunID FROM jadwal_kp WHERE ProdiID='".strfilter($_GET[prodi])."' ORDER BY JadwalID DESC LIMIT 1")); //AND TahunID='".strfilter($_GET[tahun])."'
	//$ex = explode('-', $w[KelompokID]);
	$w = mysqli_fetch_array(mysqli_query($koneksi, "SELECT JadwalID,KelompokID,ProdiID,TahunID FROM jadwal_kp ORDER BY JadwalID DESC LIMIT 1"));
	$ex = explode('-', $w['JadwalID']);
	 
	if (date('d')=='01'){ 
		$a = '01'; 
	}else{ 
		$a = $ex[0]+1; 
	}	 	
	$d = date('y');
	$KodeAuto = $a.'-'.$d.$b;

    if (isset($_POST['tambah'])){	
	    $tglnow =date('Y-m-d H:i:s');		   
		$cek = mysqli_num_rows(mysqli_query($koneksi, "select * from jadwal_kp 
											where KelompokID='".strfilter($_POST['KelompokID'])."' 											
											and TahunID='".strfilter($_POST['tahun'])."'"));
		if ($cek>0){
			echo "<script>document.location='index.php?ndelox=kk/jadwalkp&tahun=$_POST[tahun]&prodi=$_POST[prodi]&gagal';</script>";
			exit;
		}
	 
  				
	    $query = mysqli_query($koneksi, "INSERT INTO jadwal_kp(
							  JadwalID,
							  TahunID,
							  KelompokID,
							  DosenID,
							  Judul,
							  TempatPenelitian,							 
							  TglMulaiSidang,
							  ProgramID,
							  ProdiID,
							  TglBuat,
							  LoginBuat,
							  TglEdit,
							  LoginEdit,              
							  NA) 
					 VALUES('".strfilter($_POST['aa'])."',
							'".strfilter($_POST['tahun'])."',
							'".strfilter($_POST['KelompokID'])."',
							'".strfilter($_POST['DosenID'])."',
							'".strfilter($_POST['Judul'])."',
							'".strfilter($_POST['TempatPenelitian'])."',
							'".date('Y-m-d')."',							
							'".strfilter($_POST['program'])."',
							'".strfilter($_POST['prodi'])."',
							'".date('Y-m-d')."',
							'$_SESSION[_Login]',
							'".strfilter($_POST['am'])."',
						  '".strfilter($_POST['an'])."',                
							'N')");
		
        if ($query){
			echo "<script>document.location='index.php?ndelox=kk/jadwalkp&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&sukses';</script>";
        }else{
            echo "<script>document.location='index.php?ndelox=kk/jadwalkp&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&gagal';</script>";
        }
		
    }

    echo " <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <input type='hidden' name='tahun' value='$_GET[tahun]'>
				<input type='hidden' name='program' value='$_GET[program]'>
				<input type='hidden' name='prodi' value='$_GET[prodi]'>
				<div class='card'>
				<div class='card-header'>
						<div class='box-header with-border'>
						<h3 class='box-title'>Tambah Data</h3>
					</div>
				<div class='table-responsive'>
                  <table class='table table-sm table-bordered'>
                  <tbody>
                    <tr><th style='width:260px' scope='row'>Tahun Akademik</th>   <td><select class='form-control' name='ae'> 
						<option value='0' selected>- Pilih Tahun Akademik -</option>"; 
						$tahun = mysqli_query($koneksi, "SELECT distinct(TahunID),NA,ProdiID FROM tahun ");
						while($a = mysqli_fetch_array($tahun)){
						  if ($_GET['tahun']==$a['TahunID']){
							echo "<option value='$a[TahunID]' selected>$a[TahunID]</option>";
						  }else{
							echo "<option value='$a[TahunID]'>$a[TahunID]</option>";
						  }
						}
						echo "</select>
                    </td></tr>
                    
					
					
					<tr><th scope='row'>Nama Kelompok</th>           <td><input type='text' class='form-control' name='KelompokID' value='$KodeAuto' readonly></td></tr>
                     <tr><th scope='row'>Judul </th>           <td><textarea rows='2' class='form-control' name='Judul' ></textarea></td></tr>
					
					  <tr><th scope='row'>Dosen Pembimbing</th>   <td><select class='form-control select2' name='DosenID'> 
						<option value='0' selected>- Pilih Dosen -</option>"; 
						$dosen = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by  Nama ASC");
						while($a = mysqli_fetch_array($dosen)){
						  echo "<option value='$a[Login]'> $a[Nama] - [ $a[Login] ] </option>";
						}
						echo "</select>
                    </td></tr>
					
                  </tbody>
                  </table>
						<div class='box-footer'>
						<button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
						<a href='index.php?ndelox=kk/jadwalkp&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
						
						</div>
                </div>
              </div>
			  </div>
              </form>
";
}elseif($_GET['act']=='edit'){
    if (isset($_POST['update'])){							
		$query = mysqli_query($koneksi, "UPDATE jadwal_kp SET 							
							  DosenID 		= '".strfilter($_POST['DosenID'])."',							   
							  Judul 		= '".strfilter($_POST['Judul'])."',			                                                                                                  							
							  TglMulaiSidang = '".date('Y-m-d', strtotime($_POST['MulaiSidangKP']))."',
							  where JadwalID ='".strfilter($_POST['JadwalID'])."'"); //  TahunID 		= '$_POST[tahun]', KelompokID 	= '$_POST[KelompokID]',
        if ($query){
          echo "<script>document.location='index.php?ndelox=kk/jadwalkp&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&JadwalID=$_POST[JadwalID]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=kk/jadwalkp&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&JadwalID=$_POST[JadwalID]&gagal';</script>";
        }
    }
    
    $tg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jadwal_kp where JadwalID='".strfilter($_GET['JadwalID'])."'"));
    echo "
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
			  <div class='card'>
			  <div class='card-header'>
					<div class='box-header with-border'>
					<h3 class='box-title'>EDIT JADWAL KERJA PRAKTEK</h3>
					</div>
			  <div class='table-responsive'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                  <input type='hidden' name='JadwalID' value='$_GET[JadwalID]'>	
				  <input type='hidden' name='program' value='$_GET[program]'>	
				  <input type='hidden' name='prodi' value='$_GET[prodi]'>				 
				  <input type='hidden' name='tahun' value='$_GET[tahun]'>               
                  
				<tr><th scope='row' width='200px'>Judul Kerja Praktek</th><td><input type='text' class='form-control' name='Judul' value='$tg[Judul]'></td></tr> 
      
				   <tr><th scope='row'>Tanggal Sidang</th>  
					<td><input type='text' id='datepicker' class='form-control' name='MulaiSidangKP' style='width:100%' value='".date('d-m-Y', strtotime($tg['TglMulaiSidang']))."'></td>
				</tr> 
			     <tr><th scope='row'>Dosen Pembimbing</th>   <td><select class='form-control select2' name='DosenID'> 
						<option value='0' selected>- Pilih Dosen -</option>"; 
						$dosen = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by  Nama ASC");
						while($a = mysqli_fetch_array($dosen)){
						  if ($tg['DosenID']==$a['Login']){
						     echo "<option value='$a[Login]' selected> $a[Nama] - [ $a[Login] ] </option>";
						  }else{
						     echo "<option value='$a[Login]' > $a[Nama] - [ $a[Login] ] </option>";	  
						  }
						}
				echo "</select>
				</td></tr>
				
				
                </tbody>
                </table>
						<div class='box-footer'>
						<button type='submit' name='update' class='btn btn-info'>Update</button>
						<a href='index.php?ndelox=kk/jadwalkp&tahun=$_GET[tahun]&prodi=$_GET[prodi]&program=$_GET[program]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>						
					</div>
                </div>
              </div>
			  </div>
              </form>";
}elseif($_GET['act']=='tambahanggota'){
    if (isset($_POST['tambah'])){	
	    $tglnow =date('Y-m-d H:i:s');	
	    //echo"coba";
		$cek1 = mysqli_num_rows(mysqli_query($koneksi, "select * from mhsw where MhswID='".strfilter($_POST['MhswID'])."'"));
		if ($cek1==0){
			 echo "<a href='index.php?ndelox=kk/jadwalkp&act=tambahanggota&JadwalID=$_GET[JadwalID]&prodi=$_GET[prodi]&tahun=$_GET[tahun]&KelompokID=$_POST[KelompokID]'> Data tidak ketemu!</a>";		
			exit;
		}
		
		$cek2 = mysqli_num_rows(mysqli_query($koneksi, "select * from jadwal_kp_anggota where MhswID='".strfilter($_POST['MhswID'])."'"));
		if ($cek2>0){
			 echo "<script>document.location='index.php?ndelox=kk/jadwalkp&act=tambahanggota&JadwalID=$_GET[JadwalID]&prodi=$_GET[prodi]&tahun=$_GET[tahun]&KelompokID=$_POST[KelompokID]&gagal';</script>";
			exit;
		}
	  
	    $query = mysqli_query($koneksi, "INSERT INTO jadwal_kp_anggota(JadwalID,KelompokID,MhswID)VALUES('".strfilter($_POST['JadwalID'])."','".strfilter($_POST['KelompokID'])."','".strfilter($_POST['MhswID'])."')");		
        if ($query){
			echo "<script>document.location='index.php?ndelox=kk/jadwalkp&tahun=$_POST[tahun]&prodi=$_POST[prodi]&sukses';</script>";
        }else{
            echo "<script>document.location='index.php?ndelox=kk/jadwalkp&tahun=$_POST[tahun]&prodi=$_POST[prodi]&gagal';</script>";
        }		
    }
    $tg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jadwal_kp where JadwalID='".strfilter($_GET['JadwalID'])."'"));
	$x   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$tg[DosenID]'"));
    echo " 
               
            
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>              
				 <input type='hidden' name='JadwalID' value='$_GET[JadwalID]'>
				 <input type='hidden' name='tahun' value='$_GET[tahun]'>
			
				<input type='hidden' name='prodi' value='$_GET[prodi]'>
				<div class='card'>
				<div class='card-header'>
				<div class='box-header with-border'>
				<h3 class='box-title'>Tambah Data</h3>
			  </div>
				<div class='table-responsive'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>                  
					<tr><th scope='row'>Judul</th><td><b>$tg[Judul]</b></td></tr>
				  <tr><th scope='row'>Pembimbing</th><td><b>$x[Nama], $x[Gelar]</b></td></tr>
					<tr><th scope='row'>Nama Kelompok</th>           <td><input type='text' class='form-control' name='KelompokID' value='$_GET[KelompokID]' readonly=''></td></tr>
                    <tr><th scope='row'>NIM</th>           <td><input type='text' class='form-control' name='MhswID'></td></tr>										
                  </tbody>
                  </table>
				  <div class='box-footer'>
				  <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
				  <a href='index.php?ndelox=kk/jadwalkp&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>                  
				</div>
                </div>
              </div>
			  </div>

              </form>";
}elseif($_GET['act']=='ujianlah'){
if (isset($_POST['simpanujian'])){	       
$query = mysqli_query($koneksi, "UPDATE jadwal_kp SET 							
				  Penguji1 		= '".strfilter($_POST['Penguji1'])."',							   
				  Penguji2 		= '".strfilter($_POST['Penguji2'])."',			                                                                                                  
				  Penguji3 		= '".strfilter($_POST['Penguji3'])."',
				  TglMulaiSidang = '".date('Y-m-d', strtotime($_POST['MulaiSidangKP']))."',
				  JamMulai = '".strfilter($_POST['JamMulai'])."',
				  JamSelesai = '".strfilter($_POST['JamSelesai'])."',
				  TempatUjian	= '".strfilter($_POST['TempatUjian'])."'
				  where JadwalID='".strfilter($_POST['JadwalID'])."'"); //  TahunID 		= '$_POST[tahun]', KelompokID 	= '$_POST[KelompokID]',
if ($query){
echo "<script>document.location='index.php?ndelox=kk/jadwalkp&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&JadwalID=$_POST[JadwalID]&sukses';</script>";
}else{
echo "<script>document.location='index.php?ndelox=kk/jadwalkp&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&JadwalID=$_POST[JadwalID]&gagal';</script>";
}
}

$tg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jadwal_kp where JadwalID='".strfilter($_GET['JadwalID'])."'"));
$x   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$tg[DosenID]'"));


echo "
	

  <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>


		<div class='card'>
		<div class='card-header'>
		<div class='box-header with-border'>
		<h3 class='box-title'>PELAKSANAAN UJIAN KP</h3>
		</div>
		<div class='table-responsive'>
	  <table class='table table-condensed table-bordered'>
	  <tbody>
	  <input type='hidden' name='JadwalID' value='$_GET[JadwalID]'>	
	  <input type='hidden' name='program' value='$_GET[program]'>	
	  <input type='hidden' name='prodi' value='$_GET[prodi]'>				 
	  <input type='hidden' name='tahun' value='$_GET[tahun]'>
	 <tr><th scope='row'>Judul Kerja Praktek</th><td>$tg[Judul]</tr>
	  <tr><th scope='row'>Pembimbing</th><td><b>$x[Nama], $x[Gelar]</b></td></tr>
	  <tr><th scope='row'>Penguji 1</th>   <td><select class='form-control select2' name='Penguji1'> 
			<option value='0' selected>- Pilih Penguji 1 -</option>"; 
			$dosen = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by Nama asc");
			while($a = mysqli_fetch_array($dosen)){
			  if ($tg['Penguji1']==$a['Login']){
				 echo "<option value='$a[Login]' selected> $a[Nama] - [ $a[Login] ] </option>";
			  }else{
				 echo "<option value='$a[Login]' > $a[Nama] - [ $a[Login] ] </option>";	  
			  }
			}
	echo "</select>
	</td></tr>
	<tr><th scope='row'>Penguji 2</th>   <td><select class='form-control select2' name='Penguji2'> 
			<option value='0' selected>- Pilih Penguji 2 -</option>"; 
			$dosen = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by Nama asc");
			while($a = mysqli_fetch_array($dosen)){
			  if ($tg['Penguji2']==$a['Login']){
				 echo "<option value='$a[Login]' selected> $a[Nama] - [ $a[Login] ] </option>";
			  }else{
				 echo "<option value='$a[Login]' > $a[Nama] - [ $a[Login] ] </option>";	  
			  }
			}
	echo "</select>
	</td></tr>
	<tr><th scope='row'>Penguji 3</th>   <td><select class='form-control select2' name='Penguji3'> 
			<option value='0' selected>- Pilih Penguji 3 -</option>"; 
			$dosen = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by Nama asc");
			while($a = mysqli_fetch_array($dosen)){
			  if ($tg['Penguji3']==$a['Login']){
				 echo "<option value='$a[Login]' selected> $a[Nama] - [ $a[Login] ] </option>";
			  }else{
				 echo "<option value='$a[Login]' > $a[Nama] - [ $a[Login] ] </option>";	  
			  }
			}
	echo "</select>
	</td></tr>
	<tr><th scope='row'>Tempat Ujian</th>   <td><select class='form-control' name='TempatUjian'> 
			<option value='0' selected>- Pilih Ruang -</option>";  
			$rg = mysqli_query($koneksi, "SELECT * FROM ruang  order by Nama asc");
			while($g = mysqli_fetch_array($rg)){
			  if ($tg['TempatUjian']==$g['RuangID']){
				 echo "<option value='$g[RuangID]' selected> $g[Nama] - [ $g[RuangID] ] </option>";
			  }else{
				 echo "<option value='$g[RuangID]' > $g[Nama] - [ $g[RuangID] ] </option>";	  
			  }
			}
			echo "</select>
		</td></tr> 
	
	<tr><th scope='row'>Mulai Sidang</th>  
		<td><input type='text' class='form-control' name='MulaiSidangKP' style='width:100%' value='".date('d-m-Y', strtotime($tg['TglMulaiSidang']))."'></td>
	</tr> 				  
	 <tr><th scope='row'>Jam Mulai</th>  
		<td><input type='text' class='form-control' name='JamMulai' style='width:100px' placeholder='hh:ii:ss' value='$tg[JamMulai]'></td>
	</tr>
	<tr><th scope='row'>Jam Selesai</th>  
		<td><input type='text' class='form-control' name='JamSelesai' style='width:100px' placeholder='hh:ii:ss' value='$tg[JamSelesai]'></td>
	</tr>
	  </tbody>
	  </table>
			<div class='box-footer'>
			<button type='submit' name='simpanujian' class='btn btn-info'>Simpan</button>
			<a href='index.php?ndelox=kk/jadwalkp&tahun=$_GET[tahun]&prodi=$_GET[prodi]&program=$_GET[program]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>		
			</div>
	  </div>
	  </div>
	  </div>

  </form>";
}

//PEMBIMBING -----------------------------------------------------
elseif($_GET['act']=='pembimbingkp'){ ?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example1" class="table table-bordered table-striped">
  <thead>
	<tr>
	  <th style='width:20px'>No</th>
	  <th style='width:300px'>Nama Pembimbing </th> 
		<th style=text-align:center>Pembimbing</th>  
        <th style=text-align:center>Penguji 2</td>
		<th style=text-align:center>Penguji 3</td>
		<th style=text-align:center>Total Menguji</td>
	</tr>
  </thead>
  <tbody>
<?php	
    //nama dosen tidak muncul jika tidak memiliki pembimbing 1
	$tampil = mysqli_query($koneksi, "SELECT Login,Nama,Gelar,Homebase,NA from dosen where Homebase='".strfilter($_GET['prodi'])."' and NA='N' AND Login Not IN('1021087201','1023037506 ')");                  		
	while($r=mysqli_fetch_array($tampil)){	
        $PembimbingProKP =mysqli_fetch_array(mysqli_query($koneksi, "select Count(DosenID) as JmlPembimbingProKP,TahunID,ProdiID from jadwal_kp where DosenID='$r[Login]' and TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."'"));
		$PengujiProKP2 =mysqli_fetch_array(mysqli_query($koneksi, "select Count(Penguji2) as JmlPenguji2,TahunID,ProdiID from jadwal_kp where Penguji2='$r[Login]' and TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."'"));
		$PengujiProKP3 =mysqli_fetch_array(mysqli_query($koneksi, "select Count(Penguji2) as JmlPenguji3,TahunID,ProdiID from jadwal_kp where Penguji3='$r[Login]' and TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."'"));

	$no++;	
	$Pembimbing1x 	= strtolower($r['Nama']);
	$Pembimbing1	= ucwords($Pembimbing1x);
	echo "<tr>
	<td>$no</td>             
	<td>$r[Login] - $Pembimbing1, $r[Gelar]<br>				
	<td style=text-align:center;>$PembimbingProKP[JmlPembimbingProKP]</td>
	<td style=text-align:center;>$PengujiProKP2[JmlPenguji2]</td>
	<td style=text-align:center;>$PengujiProKP3[JmlPenguji3]</td>
	<td style=text-align:center;>$ttotal</td>
	</tr>";
	  }
	?>
<tbody>
</table>
</div>
</div>
</div>
<?php 
}