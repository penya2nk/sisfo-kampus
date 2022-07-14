<div class="card">
<div class="card-header">
<div class="card-tools">

<!-- <select name='program' class='combobox form-control' >
<?php 
	echo "<option value=''> Pencarian Data Mahasiswa </option>";
	$program = mysqli_query($koneksi, "SELECT MhswID,Nama,ProgramID,ProdiID,Handphone FROM mhsw where ProdiID='".strfilter($_GET['prodi'])."' and StatusMhswID='A' order by MhswID asc");
	while ($k = mysqli_fetch_array($program)){
	 if ($_GET['MhswID']==$k['MhswID']){
		echo "<option value='$k[MhswID]' selected>$k[MhswID] - $k[Nama] - $k[ProgramID] - $k[Handphone] </option>";
	  }else{
		echo "<option value='$k[MhswID]'>$k[MhswID] - $k[Nama] - $k[ProgramID] - $k[Handphone]</option>";
	  }
	}
?>
</select>  -->


<?php 
  if (isset($_GET['tahun'])){     
	 $hari=mysqli_fetch_array(mysqli_query($koneksi, "SELECT CURDATE() AS SEKARANG, DATE_ADD(CURDATE(), INTERVAL 1 DAY) AS 'BESOK',DATE_ADD(CURDATE(), INTERVAL 2 DAY) AS 'LUSA',DATE_SUB(CURDATE(), INTERVAL 1 DAY) AS 'KEMAREN'"));
	 echo "<a  href='print_reportxls/jdwskripsiproxls.php?tahun=$_GET[tahun]&prodi=$_GET[prodi]' target='_BLANK'>Exp XLS</a>"; 
	 echo" | <a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=kem&prodi=$_GET[prodi]&tahun=$_GET[tahun]&kemx=$hari[KEMAREN]'>Kemarin<a> ";	
	 echo" | <a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=tod&prodi=$_GET[prodi]&tahun=$_GET[tahun]&todx=$hari[SEKARANG]'>Hari Ini<a> ";	
	 echo" | <a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=bes&prodi=$_GET[prodi]&tahun=$_GET[tahun]&besx=$hari[BESOK]'>Besok<a> ";
	 echo" | <a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=lus&prodi=$_GET[prodi]&tahun=$_GET[tahun]&lusx=$hari[LUSA]'>Lusa<a> | &nbsp;&nbsp;&nbsp;";
  }else{ 
     echo ""; 
  } 
?>


<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=tambahjadwal_seminarproposalskripsi&tahun=<?php echo $_GET['tahun']; ?>&prodi=<?php echo "$_GET[prodi]"; ?>&program=<?php echo 	"$_GET[program]"; ?>'>Tambahkan Data</a>
<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=inputnilaiproposal&tahun=<?php echo $_GET['tahun']; ?>&prodi=<?php echo "$_GET[prodi]"; ?>&program=<?php echo 	"$_GET[program]"; ?>'>Input Nilai</a>
</div>


<div class="form-group row">
		<label class="col-md-3 col-form-label text-md-left"><b style='color:purple'>SEMINAR PROPOSAL SKRIPSI</b></label>

		<div class="col-md-2 ">
		<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
		<input type="hidden" name='ndelox' value='ta/jadwal_seminarproposalskripsi'>
		<select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>>
		<?php 
			echo "<option value=''>- Pilih Tahun Akademik -</option>";
			$tahun = mysqli_query($koneksi, "SELECT distinct(TahunID) FROM tahun order by TahunID Desc"); //and NA='N'
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
		<select name='prodi' class='form-control form-control-sm' onChange='this.form.submit()'>>
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
									
		<div class="col-md-1">
		<?php
			echo"<input class='pull-right btn btn-primary btn-sm' type='submit' value='Lihat'>";
			echo"</form>";
		?>                      
		</div>                    
		</div>
	</div>
</div><!-- /.card-header -->



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
<table id="example1" class="table table-sm table-striped">
  <thead>
	<tr style='background:purple;color:white'>
	  <th style='width:20px'>No</th>
	  <th style='width:100px'>NIM</th>  
	  <th style='width:200px'>Nama / Pembimbing <?php 
	  if ($_SESSION['_Login']=='hery'){
		  echo"<a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=pembimbing&tahun=$_GET[tahun]&prodi=$_GET[prodi]'>...</a>";
	  }
	  ?>
	  <th style='width:500px'>Judul Proposal</th> 
	  <th style='width:200px'>Penguji</th>                                          
	  <th width='200px'>Waktu dan Tempat</th>             
	  <th style='width:250px;text-align:center'>Aksi</th> 
	</tr>
  </thead>
  <tbody>
	<?php		
	  $tampil = mysqli_query($koneksi, "SELECT jadwal_skripsi.*, 
	  mhsw.Nama, mhsw.ProgramID, mhsw.Handphone
	  from jadwal_skripsi, mhsw
	  where jadwal_skripsi.MhswID=mhsw.MhswID
	  and jadwal_skripsi.TahunID='".strfilter($_GET['tahun'])."' 
	  and jadwal_skripsi.ProdiID='".strfilter($_GET['prodi'])."' 
	  order by jadwal_skripsi.TglUjianProposal, jadwal_skripsi.JamMulaiProSkripsi asc");//and ProgramID like '%$_GET[program                    		
	  $no = 1;
	  while($r=mysqli_fetch_array($tampil)){			   
		if ($r['Ket']=='1'){
			$Ketr="<b style=color:green> Lulus</b>";				
			$c="style=color:green";
		}
		else if ($r['Ket']=='2'){
			$Ketr="<b style=color:red> Gagal</b>";
			$c="style=color:red";
		}
		else{
			$Ketr="<b style=color:purple> Belum Seminar</b>";
			$c="style=color:purple";
		}
		
	   $p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PembimbingPro1]'"));
	   $Pembimbing1x 	 = strtolower($p1['Nama']);
	   $Pembimbing1	 = ucwords($Pembimbing1x);
	   
	   $p2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PembimbingPro2]'"));	
	   $Pembimbing2x 	 = strtolower($p2['Nama']);
	   $Pembimbing2	 = ucwords($Pembimbing2x);

	
	   $Pji1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiPro1]'"));
	   $Penguji1x 	 = strtolower($Pji1['Nama']);
	   $Penguji1	 = ucwords($Penguji1x);
	   
	   $Pji2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiPro2]'"));
	   $Penguji2x 	 = strtolower($Pji2['Nama']);
	   $Penguji2	 = ucwords($Penguji2x);
	   
	   $Pji3 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiPro3]'"));		   
	   $Penguji3x 	 = strtolower($Pji3['Nama']);
	   $Penguji3	 = ucwords($Penguji3x);
	   
	   $Judulx 	 = strtolower($r['Judul']);
	   $Judul	 = ucwords($Judulx);
	   
	   $ruang    = mysqli_fetch_array(mysqli_query($koneksi, "select RuangID,Nama from ruang where RuangID='$r[TempatUjian]'"));		  
	   $tanggal  = $r['TglUjianProposal'];
	   $tglx     = tgl_indo($r['TglUjianProposal']);
	   $day      = date('D', strtotime($tanggal));
	   $dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
				  
	echo "<tr><td>$no</td>   
	<td>
	<a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=edit&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'>$r[MhswID]</a> <br>
	[ <a href='index.php?ndelox=smsmhs&act=kirimsms&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&telepon=$r[Handphone]&judul=$r[Judul]'> $r[Handphone] </a>]
	<br>
	<a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&normal&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'> [ N ] </a> -
	<a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&lulus&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'> [ R ] </a> -
	<a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&gagalx&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'> [ X ] </a>
	
	</td>  
	<td><b $c>$r[Nama]</b><br>
		<a target='_BLANK'  href='print_report/print-srpenunjukan.php?JadwalID=$r[JadwalID]'>1. $Pembimbing1, $p1[Gelar]</a><br>
		<a target='_BLANK'  href='print_report/print-srpenunjukan2.php?JadwalID=$r[JadwalID]'>2. $Pembimbing2, $p2[Gelar]</a>		
	</td>              
	<td>$Judul <br>
	<a target='_BLANK'  href='print_report/print-srpengantar.php?JadwalID=$r[JadwalID]'>[ Surat Pengantar ]</a> - 
	<a target='_BLANK'  href='print_report/print-kwitansiproskripsi.php?JadwalID=$r[JadwalID]'>[ Print Kwitansi ]</a></td>
	<td>1. $Penguji1, $Pji1[Gelar]<br>
		2. $Penguji2, $Pji2[Gelar]<br> 
		3. $Penguji3, $Pji3[Gelar]
	</td>
	<td>$dayList[$day], $tglx<br>
		".substr($r['JamMulaiProSkripsi'],0,5)." - ".substr($r['JamSelesaiProSkripsi'],0,5)."  <br>
		$ruang[Nama] 
	</td>
";                           
	echo "
	<td style='width:70px !important'><center>
	<a class='btn btn-success btn-sm' title='Edit' href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=ujianskripsi&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'><i class='fa fa-edit'></i></a>
	<a target='_BLANK' class='btn btn-success btn-xs' href='print_report/print-baujianproskripsi.php?JadwalID=$r[JadwalID]&tahun=$r[TahunID]&prodi=$r[ProdiID]'>BA</a>
	<a target='_BLANK' class='btn btn-success btn-xs' href='print_report/print-frmnilaiproskripsi.php?JadwalID=$r[JadwalID]&tahun=$r[TahunID]&prodi=$r[ProdiID]'><i class='fa fa-print'></i> NILAI</a>	  
	<a class='btn btn-danger btn-sm' title='Hapus' href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&hapus=$r[MhswID]&prodi=$r[ProdiID]&tahun=$r[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
	<br>$Ketr
	</center>	  
	</td>
	</tr>";
	$no++;
	}
	  if (isset($_GET['hapus'])){
		mysqli_query($koneksi, "DELETE FROM jadwal_skripsi where MhswID='".strfilter($_GET['hapus'])."'");
		echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
	  }
	  if (isset($_GET['del'])){
		mysqli_query($koneksi, "DELETE FROM jadwal_skripsi where JadwalID='".strfilter($_GET['del'])."'");
		mysqli_query($koneksi, "DELETE FROM jadwal_skripsi where JadwalID='".strfilter($_GET['del'])."'");
		echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
	  }
	  if (isset($_GET['gagalx'])){
		mysqli_query($koneksi, "UPDATE jadwal_skripsi Set Ket='2' where JadwalID='".strfilter($_GET['IDX'])."'");
		echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
	  }
	  if (isset($_GET['normal'])){
		mysqli_query($koneksi, "UPDATE jadwal_skripsi Set Ket='0' where JadwalID='".strfilter($_GET['IDX'])."'");
		echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
	  } 
	  if (isset($_GET['lulus'])){
		mysqli_query($koneksi, "UPDATE jadwal_skripsi Set Ket='1' where JadwalID='".strfilter($_GET['IDX'])."'");
		echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
	  } 
?>
<tbody>
</table>
</div>
</div><!-- /.box-body -->
</div>
</div>

<?php 
//YESTERDAY -----------------------------------------------------
}elseif($_GET['act']=='kem'){ ?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example1" class="table table-bordered table-striped">
<thead>
<tr style='background:purple;color:white'>
  <th style='width:20px'>No</th>
  <th style='width:100px'>NIM</th>  
  <th style='width:200px'>Nama / Pembimbing</th> 
  <th style='width:500px'>Judul Skripsi</th> 
  <th style='width:200px'>Penguji</th>                                          
  <th width='200px'>Waktu dan Tempat</th> 
  <th width='260px'>Aksi</th> 
</tr>
</thead>
<tbody>
<?php		
$tampil = mysqli_query($koneksi, "SELECT * from vw_jadwal_skripsi_ujian where TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."' and TglUjianProposal='".strfilter($_GET['kemx'])."' order by TglUjianProposal,JamMulaiProSkripsi asc");//and ProgramID like '%$_GET[program                    		
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
	$Judulx 		= strtolower($r['Judul']);
	$Judul		= ucwords($Judulx);

	$p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PembimbingPro1]'"));
	$Pembimbing1x 	= strtolower($p1['Nama']);
	$Pembimbing1		= ucwords($Pembimbing1x);

	$p2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PembimbingPro2]'"));	
	$Pembimbing2x 	= strtolower($p2['Nama']);
	$Pembimbing2		= ucwords($Pembimbing2x);


	$penguji1 		= mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiPro1]'"));
	$Penguji1x 		= strtolower($penguji1['Nama']);
	$Penguji1		= ucwords($Penguji1x);

	$penguji2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiPro2]'"));
	$Penguji2x 		= strtolower($penguji2['Nama']);
	$Penguji2		= ucwords($Penguji2x);

	$penguji3 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiPro3]'"));
	$Penguji3x 		= strtolower($penguji3['Nama']);
	$Penguji3		= ucwords($Penguji3x);

	$ruang  = mysqli_fetch_array(mysqli_query($koneksi, "select RuangID,Nama from ruang where RuangID='$r[TempatUjian]'"));
	 
	$tanggal = $r['TglUjianProposal'];
	$tglUjian =tgl_indo($r['TglUjianProposal']);
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
		  
		echo "<tr><td>$no</td>   
		<td>
		<a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=edit&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'>$r[MhswID]</a> <br>
		<a href='index.php?ndelox=smsmhs&act=kirimsms&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&telepon=$r[Handphone]&judul=$r[Judul]'> [ $r[Handphone] ]
		</a>
		<br>
		<a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=kem&normal&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&kemx=$_GET[kemx]'> [ N ] </a> -
	    <a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=kem&lulus&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&kemx=$_GET[kemx]'> [ R ] </a> -
	    <a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=kem&gagalx&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&kemx=$_GET[kemx]'> [ X ] </a>
		</td>  
		<td $c><b>$r[Nama]</b><br>
			<a target='_BLANK'  href='print_report/print-srpenunjukan.php?JadwalID=$r[JadwalID]'> 1. $Pembimbing1, $p1[Gelar]</a><br>
			<a target='_BLANK'  href='print_report/print-srpenunjukan2.php?JadwalID=$r[JadwalID]'> 2. $Pembimbing2, $p2[Gelar]</a>
		</td>              
		<td>$Judul<br>
		<a target='_BLANK'  href='print_report/print-srpengantar.php?JadwalID=$r[JadwalID]'>[ Surat Pengantar ]</a> -
		<a target='_BLANK'  href='print_report/print-kwitansiproskripsi.php?JadwalID=$r[JadwalID]'>[ Print Kwitansi ]</a></td>
		<td>1. $Penguji1, $penguji1[Gelar]<br>
			2. $Penguji2, $penguji2[Gelar]<br> 
			3. $Penguji3, $penguji3[Gelar]
		</td>
		<td>$dayList[$day], $tglUjian<br>
			".substr($r['JamMulaiProSkripsi'],0,5)." - ".substr($r['JamSelesaiProSkripsi'],0,5)."  <br>
			$ruang[Nama]
		</td>";
				
	echo "<td style='width:70px !important'><center>
	<a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=ujianskripsi&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'><i class='fa fa-edit'></i></a>
	<a target='_BLANK' class='btn btn-success btn-xs' href='print_report/print-baujianproskripsi.php?JadwalID=$r[JadwalID]&tahun=$r[TahunID]&prodi=$r[ProdiID]'><span class='glyphicon glyphicon-print'></span>BA</a>
	<a target='_BLANK' class='btn btn-success btn-xs' href='print_report/print-frmnilaiproskripsi.php?JadwalID=$r[JadwalID]&tahun=$r[TahunID]&prodi=$r[ProdiID]'><span class='glyphicon glyphicon-print'></span> NILAI</a> 
	<a class='btn btn-danger btn-xs' title='Hapus' href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=kem&hapus=$r[MhswID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&kemx=$_GET[kemx]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
	<br>$Ketr
	</center>	  
	</td></tr>";
	$no++;
	}	  
	if (isset($_GET['hapus'])){
		mysqli_query($koneksi, "DELETE FROM jadwal_skripsi where MhswID='".strfilter($_GET['hapus'])."'");
		echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=kem&prodi=$_GET[prodi]&tahun=$_GET[tahun]&kemx=$_GET[kemx]';</script>";
	  }
	if (isset($_GET['del'])){
		mysqli_query($koneksi, "DELETE FROM jadwal_skripsi where JadwalID='".strfilter($_GET['del'])."'");
		mysqli_query($koneksi, "DELETE FROM jadwal_skripsi where JadwalID='".strfilter($_GET['del'])."'");
		echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=kem&prodi=$_GET[prodi]&tahun=$_GET[tahun]&kemx=$_GET[kemx]';</script>";
	  }
	if (isset($_GET['normal'])){
		   mysqli_query($koneksi, "UPDATE jadwal_skripsi Set Ket='0' where JadwalID='".strfilter($_GET['IDX'])."'");
		   echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=kem&prodi=$_GET[prodi]&tahun=$_GET[tahun]&kemx=$_GET[kemx]';</script>";
	}
	if (isset($_GET['lulus'])){
		   mysqli_query("UPDATE jadwal_skripsi Set Ket='1' where JadwalID='".strfilter($_GET['IDX'])."'");
		   echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=kem&prodi=$_GET[prodi]&tahun=$_GET[tahun]&kemx=$_GET[kemx]';</script>";
	}
	if (isset($_GET['gagalx'])){
		   mysqli_query($koneksi, "UPDATE jadwal_skripsi Set Ket='2' where JadwalID='".strfilter($_GET['IDX'])."'");
		   echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=kem&prodi=$_GET[prodi]&tahun=$_GET[tahun]&kemx=$_GET[kemx]';</script>";
	}
?>
<tbody>
</table>
</div>
</div><!-- /.box-body -->
</div>
</div>
<?php 
}

//NOW -----------------------------------------------------
elseif($_GET['act']=='tod'){ ?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example1" class="table table-bordered table-striped">
<thead>
<tr style='background:purple;color:white'>
<th style='width:20px'>No</th>
<th style='width:100px'>NIM</th>  
<th style='width:200px'>Nama / Pembimbing</th> 
<th style='width:500px'>Judul Skripsi</th> 
<th style='width:200px'>Penguji</th>                                          
<th width='200px'>Waktu dan Tempat</th> 	
<th width='260px'>Aksi</th> 
</tr>
</thead>
<tbody>
<?php	
  $tampil = mysqli_query($koneksi, "SELECT * from vw_jadwal_skripsi_ujian where TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."' and TglUjianProposal='".date('Y-m-d')."' order by TglUjianProposal,JamMulaiProSkripsi asc");//and ProgramID like '%$_GET[program                    		
  while($r=mysqli_fetch_array($tampil)){	
  $no++;  
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
   $Judulx 		= strtolower($r['Judul']);
   $Judul		= ucwords($Judulx);

   $p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PembimbingPro1]'"));
   $Pembimbing1x 	= strtolower($p1['Nama']);
   $Pembimbing1		= ucwords($Pembimbing1x);
   
   $p2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PembimbingPro2]'"));	
   $Pembimbing2x 	= strtolower($p2['Nama']);
   $Pembimbing2		= ucwords($Pembimbing2x);
	
	
   $penguji1 		= mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiPro1]'"));
   $Penguji1x 		= strtolower($penguji1['Nama']);
   $Penguji1		= ucwords($Penguji1x);
   
   $penguji2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiPro2]'"));
   $Penguji2x 		= strtolower($penguji2['Nama']);
   $Penguji2		= ucwords($Penguji2x);
   
   $penguji3 = mysqli_fetch_array(mysqli_query("select Login,Nama,Gelar from dosen where Login='$r[PengujiPro3]'"));
   $Penguji3x 		= strtolower($penguji3['Nama']);
   $Penguji3		= ucwords($Penguji3x);

   $ruang  = mysqli_fetch_array(mysqli_query($koneksi, "select RuangID,Nama from ruang where RuangID='$r[TempatUjian]'"));
	 
	$tanggal = $r['TglUjianProposal'];
	$tglUjian =tgl_indo($r['TglUjianProposal']);
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
			  
	echo "<tr><td>$no</td>   
	<td>
	<a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=edit&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'>$r[MhswID]</a> <br>
	<a href='index.php?ndelox=smsmhs&act=kirimsms&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&telepon=$r[Handphone]&judul=$r[Judul]'> [ $r[Handphone] ]
	</a>
	<br>
	<a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=tod&normal&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'> [ N ] </a> -
	<a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=tod&lulus&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'> [ R ] </a> -
	<a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=tod&gagalx&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'> [ X ] </a>
	
	</td>  
	<td $c><b>$r[Nama]</b><br>
		<a target='_BLANK'  href='print_report/print-srpenunjukan.php?JadwalID=$r[JadwalID]'> 1. $Pembimbing1, $p1[Gelar]</a><br>
		<a target='_BLANK'  href='print_report/print-srpenunjukan2.php?JadwalID=$r[JadwalID]'> 2. $Pembimbing2, $p2[Gelar]</a>
	</td>              
	<td>$Judul<br>
	<a target='_BLANK'  href='print_report/print-srpengantar.php?JadwalID=$r[JadwalID]'>[ Surat Pengantar ]</a> -
	<a target='_BLANK'  href='print_report/print-kwitansiproskripsi.php?JadwalID=$r[JadwalID]'>[ Print Kwitansi ]</a></td>
	<td>1. $Penguji1, $penguji1[Gelar]<br>
		2. $Penguji2, $penguji2[Gelar]<br> 
		3. $Penguji3, $penguji3[Gelar]
	</td>
	<td>$dayList[$day], $tglUjian<br>
		".substr($r['JamMulaiProSkripsi'],0,5)." - ".substr($r['JamSelesaiProSkripsi'],0,5)."  <br>
		$ruang[Nama]
	</td>";					
	echo "
	<td style='width:70px !important'><center>
	<a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=ujianskripsi&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'><i class='fa fa-edit'></i></a>
	<a target='_BLANK' class='btn btn-success btn-xs' href='print_report/print-baujianproskripsi.php?JadwalID=$r[JadwalID]&tahun=$r[TahunID]&prodi=$r[ProdiID]'><span class='glyphicon glyphicon-print'></span>BA</a>
	<a target='_BLANK' class='btn btn-success btn-xs' href='print_report/print-frmnilaiproskripsi.php?JadwalID=$r[JadwalID]&tahun=$r[TahunID]&prodi=$r[ProdiID]'><span class='glyphicon glyphicon-print'></span> NILAI</a>	  
	<a class='btn btn-danger btn-xs' title='Hapus' href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=tod&hapus=$r[MhswID]&prodi=$r[ProdiID]&tahun=$r[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
	<br>$Ketr
	</center>	  
	</td>";
	echo "</tr>";
	  $no++;
	  }
	   if (isset($_GET['normal'])){
		   mysqli_query($koneksi, "UPDATE jadwal_skripsi Set Ket='0' where JadwalID='".strfilter($_GET['IDX'])."'");
		   echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=tod&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
	   }
	   if (isset($_GET['lulus'])){
		   mysqli_query($koneksi, "UPDATE jadwal_skripsi Set Ket='1' where JadwalID='".strfilter($_GET['IDX'])."'");
		   echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=tod&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
	   }
	   if (isset($_GET['gagalx'])){
		   mysqli_query($koneksi, "UPDATE jadwal_skripsi Set Ket='2' where JadwalID='".strfilter($_GET['IDX'])."'");
		   echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=tod&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
	   }

	  if (isset($_GET['hapus'])){
		mysqli_query($koneksi, "DELETE FROM jadwal_skripsi where MhswID='".strfilter($_GET['hapus'])."'");
		echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=tod&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
	  }
	  if (isset($_GET['del'])){
		 mysqli_query($koneksi, "DELETE FROM jadwal_skripsi where JadwalID='".strfilter($_GET['del'])."'");
		 mysqli_query($koneksi, "DELETE FROM jadwal_skripsi where JadwalID='".strfilter($_GET['del'])."'");
		echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=tod&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
	  }
	  
  ?>
<tbody>
</table>
</div>
</div><!-- /.box-body -->
</div>
</div>
<?php 
}


//BESOK -----------------------------------------------------
elseif($_GET['act']=='bes'){ ?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example1" class="table table-bordered table-striped">
<thead>
<tr style='background:purple;color:white'>
<th style='width:20px'>No</th>
<th style='width:100px'>NIM</th>  
<th style='width:200px'>Nama / Pembimbing</th> 
<th style='width:500px'>Judul Skripsi</th> 
<th style='width:200px'>Penguji</th>                                          
<th width='200px'>Waktu dan Tempat</th> 
<th width='260px'>Aksi</th> 
</tr>
</thead>
<tbody>
<?php
$tampil = mysqli_query($koneksi, "SELECT * from vw_jadwal_skripsi_ujian where TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."' and TglUjianProposal='".strfilter($_GET[besx])."' order by TglUjianProposal,JamMulaiProSkripsi asc");                    		
$no = 1;
while($r=mysqli_fetch_array($tampil)){			   
$tglnow =date('Y-m-d');
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
$Judulx 		= strtolower($r['Judul']);
$Judul		= ucwords($Judulx);

$p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PembimbingPro1]'"));
$Pembimbing1x 	= strtolower($p1['Nama']);
$Pembimbing1		= ucwords($Pembimbing1x);

$p2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PembimbingPro2]'"));	
$Pembimbing2x 	= strtolower($p2['Nama']);
$Pembimbing2		= ucwords($Pembimbing2x);


$penguji1 		= mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiPro1]'"));
$Penguji1x 		= strtolower($penguji1['Nama']);
$Penguji1		= ucwords($Penguji1x);

$penguji2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiPro2]'"));
$Penguji2x 		= strtolower($penguji2['Nama']);
$Penguji2		= ucwords($Penguji2x);

$penguji3 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiPro3]'"));
$Penguji3x 		= strtolower($penguji3['Nama']);
$Penguji3		= ucwords($Penguji3x);

$ruang  = mysqli_fetch_array(mysqli_query($koneksi, "select RuangID,Nama from ruang where RuangID='$r[TempatUjian]'"));
 
$tanggal = $r['TglUjianProposal'];
$tglUjian =tgl_indo($r['TglUjianProposal']);
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
		  
echo "<tr><td>$no</td>   
		<td>
		<a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=edit&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'>$r[MhswID]</a> <br>
		<a href='index.php?ndelox=smsmhs&act=kirimsms&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&telepon=$r[Handphone]&judul=$r[Judul]'> [ $r[Handphone] ]
		</a>
		<br>
		<a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=bes&normal&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&besx=$_GET[besx]'> [ N ] </a> -
		<a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=bes&lulus&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&besx=$_GET[besx]'> [ R ] </a> -
		<a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=bes&gagalx&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&besx=$_GET[besx]'> [ X ] </a>
		
		</td>  
		<td $c><b>$r[Nama]</b><br>
			<a target='_BLANK'  href='print_report/print-srpenunjukan.php?JadwalID=$r[JadwalID]'> 1. $Pembimbing1, $p1[Gelar]</a><br>
			<a target='_BLANK'  href='print_report/print-srpenunjukan2.php?JadwalID=$r[JadwalID]'> 2. $Pembimbing2, $p2[Gelar]</a>
		</td>              
		<td>$Judul<br>
		<a target='_BLANK'  href='print_report/print-srpengantar.php?JadwalID=$r[JadwalID]'>[ Surat Pengantar ]</a> -
		<a target='_BLANK'  href='print_report/print-kwitansiproskripsi.php?JadwalID=$r[JadwalID]'>[ Print Kwitansi ]</a></td>
		<td>1. $Penguji1, $penguji1[Gelar]<br>
			2. $Penguji2, $penguji2[Gelar]<br> 
			3. $Penguji3, $penguji3[Gelar]
		</td>
		<td>$dayList[$day], $tglUjian<br>
			".substr($r['JamMulaiProSkripsi'],0,5)." - ".substr($r['JamSelesaiProSkripsi'],0,5)."  <br>
			$ruang[Nama]
		</td>";
				
echo"<td style='width:70px !important'><center>
<a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=ujianskripsi&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'><i class='fa fa-edit'></i></a>
<a target='_BLANK' class='btn btn-success btn-xs' href='print_report/print-baujianproskripsi.php?JadwalID=$r[JadwalID]&tahun=$r[TahunID]&prodi=$r[ProdiID]'><span class='glyphicon glyphicon-print'></span>BA</a>
<a target='_BLANK' class='btn btn-success btn-xs' href='print_report/print-frmnilaiproskripsi.php?JadwalID=$r[JadwalID]&tahun=$r[TahunID]&prodi=$r[ProdiID]'><span class='glyphicon glyphicon-print'></span> NILAI</a>		  
<a class='btn btn-danger btn-xs' title='Hapus' href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=bes&hapus=$r[MhswID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&besx=$_GET[besx]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
<br>$Ketr
</center>	  
</td>";
echo "</tr>";
$no++;
}

	if (isset($_GET['hapus'])){
		mysqli_query($koneksi, "DELETE FROM jadwal_skripsi where MhswID='".strfilter($_GET['hapus'])."'");
		echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=bes&prodi=$_GET[prodi]&tahun=$_GET[tahun]&besx=$_GET[besx]';</script>";
	  }
	if (isset($_GET['del'])){
		mysqli_query($koneksi, "DELETE FROM jadwal_skripsi where JadwalID='".strfilter($_GET['del'])."'");
		mysqli_query($koneksi, "DELETE FROM jadwal_skripsi where JadwalID='".strfilter($_GET['del'])."'");
		echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=bes&prodi=$_GET[prodi]&tahun=$_GET[tahun]&besx=$_GET[besx]';</script>";
	  }
	if (isset($_GET['normal'])){
		   mysqli_query($koneksi, "UPDATE jadwal_skripsi Set Ket='0' where JadwalID='".strfilter($_GET['IDX'])."'");
		   echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=bes&prodi=$_GET[prodi]&tahun=$_GET[tahun]&besx=$_GET[besx]';</script>";
	}
	if (isset($_GET['lulus'])){
		   mysqli_query($koneksi, "UPDATE jadwal_skripsi Set Ket='1' where JadwalID='".strfilter($_GET['IDX'])."'");
		   echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=bes&prodi=$_GET[prodi]&tahun=$_GET[tahun]&besx=$_GET[besx]';</script>";
	}
	if (isset($_GET['gagalx'])){
		   mysqli_query($koneksi, "UPDATE jadwal_skripsi Set Ket='2' where JadwalID='".strfilter($_GET['IDX'])."'");
		   echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=bes&prodi=$_GET[prodi]&tahun=$_GET[tahun]&besx=$_GET[besx]';</script>";
	}
?>
<tbody>
</table>
</div>
</div><!-- /.box-body -->
</div>
</div>
<?php 
}


//LUSA -----------------------------------------------------
elseif($_GET['act']=='lus'){ ?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example1" class="table table-bordered table-striped">
<thead>
<tr style='background:purple;color:white'>
<th style='width:20px'>No</th>
<th style='width:100px'>NIM</th>  
<th style='width:200px'>Nama / Pembimbing</th> 
<th style='width:500px'>Judul Skripsi</th> 
<th style='width:200px'>Penguji</th>                                          
<th width='200px'>Waktu dan Tempat</th> 
<th width='260px'>Aksi</th> 
</tr>
</thead>
<tbody>
<?php
$tampil = mysqli_query($koneksi, "SELECT * from vw_jadwal_skripsi_ujian where TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."' and TglUjianProposal='".strfilter($_GET['lusx'])."' order by TglUjianProposal,JamMulaiProSkripsi asc");//and ProgramID like '%$_GET[program                    		
$no = 1;
while($r=mysqli_fetch_array($tampil)){			   
	$tglnow =date('Y-m-d');
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
	$Judulx 		= strtolower($r['Judul']);
	$Judul		= ucwords($Judulx);

	$p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PembimbingPro1]'"));
	$Pembimbing1x 	= strtolower($p1['Nama']);
	$Pembimbing1		= ucwords($Pembimbing1x);

	$p2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PembimbingPro2]'"));	
	$Pembimbing2x 	= strtolower($p2['Nama']);
	$Pembimbing2		= ucwords($Pembimbing2x);


	$penguji1 		= mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiPro1]'"));
	$Penguji1x 		= strtolower($penguji1['Nama']);
	$Penguji1		= ucwords($Penguji1x);

	$penguji2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiPro2]'"));
	$Penguji2x 		= strtolower($penguji2['Nama']);
	$Penguji2		= ucwords($Penguji2x);

	$penguji3 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiPro3]'"));
	$Penguji3x 		= strtolower($penguji3['Nama']);
	$Penguji3		= ucwords($Penguji3x);

	$ruang  = mysqli_fetch_array(mysqli_query($koneksi, "select RuangID,Nama from ruang where RuangID='$r[TempatUjian]'"));

	$tanggal = $r['TglUjianProposal'];
	$tglUjian =tgl_indo($r['TglUjianProposal']);
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

	echo "<tr><td>$no</td>   
	<td>
	<a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=edit&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'>$r[MhswID]</a> <br>
	<a href='index.php?ndelox=smsmhs&act=kirimsms&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&telepon=$r[Handphone]&judul=$r[Judul]'> [ $r[Handphone] ]
	</a>
	<br>
	<a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=lus&normal&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&lusx=$_GET[lusx]'> [ N ] </a> -
	<a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=lus&lulus&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&lusx=$_GET[lusx]'> [ R ] </a> -
	<a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=lus&gagalx&IDX=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&lusx=$_GET[lusx]'> [ X ] </a>
	</td>  
	<td $c><b>$r[Nama]</b><br>
	<a target='_BLANK'  href='print_report/print-srpenunjukan.php?JadwalID=$r[JadwalID]'> 1. $Pembimbing1, $p1[Gelar]</a><br>
	<a target='_BLANK'  href='print_report/print-srpenunjukan2.php?JadwalID=$r[JadwalID]'> 2. $Pembimbing2, $p2[Gelar]</a>
	</td>              
	<td>$Judul<br>
	<a target='_BLANK'  href='print_report/print-srpengantar.php?JadwalID=$r[JadwalID]'>[ Surat Pengantar ]</a> -
	<a target='_BLANK'  href='print_report/print-kwitansiproskripsi.php?JadwalID=$r[JadwalID]'>[ Print Kwitansi ]</a></td>
	<td>1. $Penguji1, $penguji1[Gelar]<br>
	2. $Penguji2, $penguji2[Gelar]<br> 
	3. $Penguji3, $penguji3[Gelar]
	</td>
	<td>$dayList[$day], $tglUjian<br>
	".substr($r['JamMulaiProSkripsi'],0,5)." - ".substr($r['JamSelesaiProSkripsi'],0,5)."  <br>
	$ruang[Nama]
	</td>";
		
	echo "<td style='width:70px !important'><center>
	<a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=ujianskripsi&JadwalID=$r[JadwalID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'><i class='fa fa-edit'></i></a>
	<a target='_BLANK' class='btn btn-success btn-xs' href='print_report/print-baujianproskripsi.php?JadwalID=$r[JadwalID]&tahun=$r[TahunID]&prodi=$r[ProdiID]'><span class='glyphicon glyphicon-print'></span>BA</a>
	<a target='_BLANK' class='btn btn-success btn-xs' href='print_report/print-frmnilaiproskripsi.php?JadwalID=$r[JadwalID]&tahun=$r[TahunID]&prodi=$r[ProdiID]'><span class='glyphicon glyphicon-print'></span> NILAI</a>
	<a class='btn btn-danger btn-xs' title='Hapus' href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=lus&hapus=$r[MhswID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&lusx=$_GET[lusx]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
	<br>$Ketr
	</center>	  
	</td>
	</tr>";
	$no++;
	}

	if (isset($_GET['hapus'])){
		mysqli_query($koneksi, "DELETE FROM jadwal_skripsi where MhswID='".strfilter($_GET['hapus'])."'");
		echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=lus&prodi=$_GET[prodi]&tahun=$_GET[tahun]&lusx=$_GET[lusx]';</script>";
	}
	if (isset($_GET['del'])){
		mysqli_query($koneksi, "DELETE FROM jadwal_skripsi where JadwalID='".strfilter($_GET['del'])."'");
		mysqli_query($koneksi, "DELETE FROM jadwal_skripsi where JadwalID='".strfilter($_GET['del'])."'");
		echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=lus&prodi=$_GET[prodi]&tahun=$_GET[tahun]&lusx=$_GET[lusx]';</script>";
	}
	if (isset($_GET['normal'])){
		   mysqli_query($koneksi, "UPDATE jadwal_skripsi Set Ket='0' where JadwalID='".strfilter($_GET['IDX'])."'");
		   echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=lus&prodi=$_GET[prodi]&tahun=$_GET[tahun]&lusx=$_GET[lusx]';</script>";
	}
	if (isset($_GET['lulus'])){
		   mysqli_query($koneksi, "UPDATE jadwal_skripsi Set Ket='1' where JadwalID='".strfilter($_GET['IDX'])."'");
		   echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=lus&prodi=$_GET[prodi]&tahun=$_GET[tahun]&lusx=$_GET[lusx]';</script>";
	}
	if (isset($_GET['gagalx'])){
		   mysqli_query($koneksi, "UPDATE jadwal_skripsi Set Ket='2' where JadwalID='".strfilter($_GET['IDX'])."'");
		   echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=lus&prodi=$_GET[prodi]&tahun=$_GET[tahun]&lusx=$_GET[lusx]';</script>";
	}
?>
<tbody>
</table>
</div>
</div>
</div>
<?php 
}



elseif($_GET['act']=='tambahjadwal_seminarproposalskripsi'){
?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>					
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='jadwal_seminarproposalskripsi'>
</form>				
<?php	
if (isset($_POST['tambah'])){	
	  $tglnow =date('Y-m-d H:i:s');	
	  $cek = mysqli_num_rows(mysqli_query($koneksi, "select * from jadwal_skripsi 
	  where MhswID='$_POST[MhswID]' 											
	  and TahunID='$_POST[tahun]'"));
	  if ($cek>0){
		  echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&tahun=$_POST[tahun]&prodi=$_POST[prodi]&gagal';</script>";
		  exit;
	  }
   					
	  $query = mysqli_query($koneksi, "INSERT INTO jadwal_skripsi(
						  TahunID,
						  MhswID,
						  Judul,
						  PembimbingPro1,
						  PembimbingPro2,
						  TempatPenelitian,
						  TglBuat,
						  LoginBuat,
						  ProdiID,
						  TglUjianProposal,
						  NA) 
				 VALUES('".strfilter($_POST['tahun'])."',
						'".strfilter($_POST['MhswID'])."',
						'".strfilter($_POST['Judul'])."',
						'".strfilter($_POST['PembimbingPro1'])."',
						'".strfilter($_POST['PembimbingPro2'])."',							
						'".strfilter($_POST['TempatPenelitian'])."',						
						'".date('Y-m-d')."',
						'$_SESSION[_Login]',														
						'".strfilter($_POST['prodi'])."',
						  '".date('Y-m-d')."',	
						'N')");	
	if ($query){
		echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&tahun=$_POST[tahun]&prodi=$_POST[prodi]&sukses';</script>";
	}else{
		echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&tahun=$_POST[tahun]&prodi=$_POST[prodi]&gagal';</script>";
	}
	
}

  echo "<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
		<input type='hidden' name='tahun' value='$_GET[tahun]'>
		<input type='hidden' name='prodi' value='$_GET[prodi]'>
		<input type='hidden' name='MhswID' value='$_POST[MhswID]'>
		<div class='card'>
		<div class='card-header'>
		<div class='table-responsive'>
		<table class='table table-sm table-bordered'>
		<tbody>
            
        <tr><th style='width:260px' scope='row'>Mahasiswa</th>   <td>
        <select class='combobox form-control select2' name='MhswID' > 
		<option value='$a[MhswID]' selected>- Cari Data Mahasiswa -</option>"; 
		$mhs = mysqli_query($koneksi, "SELECT MhswID,NamaMahasiswa,ProdiID,ProgramID,Handphone FROM view_mhs where ProdiID='".strfilter($_GET['prodi'])."'");
		while($a = mysqli_fetch_array($mhs)){
		  if ($_GET['MhswID']==$a['MhswID']){
			echo "<option value='$a[MhswID]' selected>$a[MhswID] - $a[NamaMahasiswa] - $a[ProdiID] - $a[ProgramID] - $a[Handphone]</option>";
		  }else{
			echo "<option value='$a[MhswID]'>$a[MhswID] - $a[NamaMahasiswa] - $a[ProdiID] - $a[ProgramID] - $a[Handphone] </option>";
		  }
		}
		echo "</select>
        </td></tr>
            
        <tr><th style='width:260px' scope='row'>Tahun Akademik</th>   <td><select class='form-control' name='tahun'> 
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
			 <tr><th scope='row'>Judul </th> <td><input type='text' class='form-control' name='Judul' value='$j[Judul]'></td></tr>
			 <tr><th scope='row' width='200px'>Tempat Penelitian</th><td><input type='text' class='form-control' name='TempatPenelitian' value='$j[TempatPenelitian]'></td></tr> 					
			  <tr><th scope='row'>Dosen Pembimbing 1</th>   <td><select class='combobox form-control select2' name='PembimbingPro1'> 
				<option value='$a[Login]' selected>- Pilih Pembimbing 1 -</option>"; 
				$dosen = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by Nama asc");
				while($a = mysqli_fetch_array($dosen)){
				  if ($j['PembimbingPro1']==$a['Login']){
					 echo "<option value='$a[Login]' selected> $a[Nama] - [ $a[Login] ] </option>";
				  }else{
					 echo "<option value='$a[Login]' > $a[Nama] - [ $a[Login] ] </option>";	  
				  }
				}
		echo "</select>
		</td></tr>
					
		<tr><th scope='row'>Dosen Pembimbing 2</th>   <td><select class='combobox form-control select2' name='PembimbingPro2'> 
				<option value='$a[Login]' selected>- Pilih Pembimbing 2 -</option>"; 
				$dosen = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by Nama asc");
				while($a = mysqli_fetch_array($dosen)){
				  if ($j['PembimbingPro2']==$a['Login']){
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
	  <button type='submit' name='tambah' class='btn btn-info'>Simpan</button>
	  <a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
	  
	</div>
	</div>
  </div>

  </form>
</div>";
			
			
			
			
			
			
			
			
}elseif($_GET['act']=='edit'){
      if (isset($_POST['simpanedit'])){		       
		$query = mysqli_query($koneksi, "UPDATE jadwal_skripsi SET 							
							  Judul 			= '".strfilter($_POST['Judul'])."',
							  TempatPenelitian 	= '".strfilter($_POST['TempatPenelitian'])."',	
							  Kota 				= '".strfilter($_POST['Kota'])."',	
							  Ke 				= '".strfilter($_POST['Ke'])."',		
							  PembimbingPro1 	= '".strfilter($_POST['PembimbingPro1'])."',							   
							  PembimbingPro2 	= '".strfilter($_POST['PembimbingPro2'])."',
							  PembimbingSkripsi1= '".strfilter($_POST['PembimbingPro1'])."',							   
							  PembimbingSkripsi2= '".strfilter($_POST['PembimbingPro2'])."',
							  LoginEdit 		= '".$_SESSION['_Login']."',			
							  TglEdit 			= '".date('Y-m-d')."'	                                                                                                 							
							  where JadwalID	= '".strfilter($_POST['JadwalID'])."'");
							  
		$jd = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jadwal_skripsi where JadwalID='".strfilter($_POST['JadwalID'])."'"));		
		mysqli_query($koneksi, "UPDATE t_penelitian SET 							
							  Judul 				= '".strfilter($_POST['Judul'])."',
							  TempatPenelitian = '".strfilter($_POST['TempatPenelitian'])."'
							  where IDPenelitian	= '$jd[IDPenelitian]'
							  and MhswID 			= '$jd[MhswID]'");					  
        if ($query){
          echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&JadwalID=$_POST[JadwalID]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&JadwalID=$_POST[JadwalID]&gagal';</script>";
        }
    }
    
    $tg  = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jadwal_skripsi where JadwalID='".strfilter($_GET['JadwalID'])."'"));
	$x   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$tg[PembimbingPro1]'"));
	$y   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$tg[PembimbingPro2]'"));
    $mhs = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MhswID,Nama,ProdiID FROM mhsw where MhswID='$tg[MhswID]'"));
	
    echo "	
             
               

              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
			  <div class='card'>
			  <div class='card-header'>
			  <div class='box-header with-border'>
			  <h3 class='box-title'>EDIT DATA</h3>
			</div>
			  <div class='table-responsive'>
                  <table class='table table-sm table-bordered'>
                  <tbody>
                  <input type='hidden' name='JadwalID' value='$_GET[JadwalID]'>	
				  <input type='hidden' name='program' value='$_GET[program]'>	
				  <input type='hidden' name='prodi' value='$_GET[prodi]'>				 
				  <input type='hidden' name='tahun' value='$_GET[tahun]'>
                 
				  <tr><th scope='row' width='200px'>Mahasiswa</th><td><input type='text' class='form-control' name='NIM' value='$mhs[MhswID] - $mhs[Nama]'></td></tr>
				  <tr><th scope='row' width='200px'>Judul Skripsi</th><td><input type='text' class='form-control' name='Judul' value='$tg[Judul]'></td></tr> 
				  <tr><th scope='row' width='200px'>Tempat Penelitian</th><td><input type='text' class='form-control' name='TempatPenelitian' value='$tg[TempatPenelitian]'></td></tr> 
				  <tr><th scope='row' width='200px'>Kab/Kota</th><td><input type='text' class='form-control' name='Kota' value='$tg[Kota]'></td></tr> 
				  <tr><th scope='row' width='200px'>To (<i>Untuk Surat Pengantar</i>)</th><td><input type='text' class='form-control' name='Ke' value='$tg[Ke]'></td></tr> 
				  <tr><th  scope='row' colspan='2' style='background-color:#E7EAEC'>PEMBIMBING PROPOSAL / SKRIPSI</th></tr>
                  <tr><th scope='row'>Pembimbing 1</th>   <td><select class='form-control' name='PembimbingPro1'> 
						<option value='0' selected>- Pilih Penguji 1 -</option>"; 
						$dosen = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by Nama asc");
						while($a = mysqli_fetch_array($dosen)){
						  if ($tg['PembimbingPro1']==$a['Login']){
						     echo "<option value='$a[Login]' selected> $a[Nama] - [ $a[Login] ] </option>";
						  }else{
						     echo "<option value='$a[Login]' > $a[Nama] - [ $a[Login] ] </option>";	  
						  }
						}
				echo "</select>
				</td></tr>
				<tr><th scope='row'>Pembimbing 2</th>   <td><select class='form-control' name='PembimbingPro2'> 
						<option value='0' selected>- Pilih Penguji 2 -</option>"; 
						$dosen = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by Nama asc");
						while($a = mysqli_fetch_array($dosen)){
						  if ($tg['PembimbingPro2']==$a['Login']){
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
				  <button type='submit' name='simpanedit' class='btn btn-info'>Simpan</button>
				  <a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&tahun=$_GET[tahun]&prodi=$_GET[prodi]&program=$_GET[program]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
				  
				</div>
                </div>
              </div>

              </form>
            </div>";


}elseif($_GET['act']=='ujianproposal'){
      if (isset($_POST['simpanujianproposal'])){
		$TglUjianProposal		=$_POST['TglUjianProposal'];			       
		$query = mysqli_query($koneksi, "UPDATE jadwal_skripsi SET 							
							  PengujiPro1 			= '".strfilter($_POST['PengujiPro1'])."',							   
							  PengujiPro2 			= '".strfilter($_POST['PengujiPro2'])."',			                                                                                                  
							  PengujiPro3 			= '".strfilter($_POST['PengujiPro3'])."',
							  TglUjianProposal 		= '$TglUjianProposal',
							  JamMulaiProSkripsi 	= '".strfilter($_POST['JamMulaiProSkripsi'])."',
							  JamSelesaiProSkripsi 	= '".strfilter($_POST['JamSelesaiProSkripsi'])."',
							  TempatUjian			= '".strfilter($_POST['TempatUjian'])."'
							  where JadwalID		= '".strfilter($_POST['JadwalID'])."'"); //  TahunID 		= '$_POST[tahun]', KelompokID 	= '$_POST[KelompokID]',
        if ($query){
          echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&JadwalID=$_POST[JadwalID]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&JadwalID=$_POST[JadwalID]&gagal';</script>";
        }
    }
    
    $tg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jadwal_skripsi where JadwalID='".strfilter($_GET['JadwalID'])."'"));
	$x   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$tg[PembimbingPro1]'"));
	$y   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$tg[PembimbingPro2]'"));

	
    echo "

              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
              <div class='card'>
				<div class='card-header'>
						<div class='box-header with-border'>
						<h3 class='box-title'>UJIAN PROPOSAL PROPOSAL</h3>
						</div>
				<div class='table-responsive'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                  <input type='hidden' name='JadwalID' value='$_GET[JadwalID]'>	
				  <input type='hidden' name='program' value='$_GET[program]'>	
				  <input type='hidden' name='prodi' value='$_GET[prodi]'>				 
				  <input type='hidden' name='tahun' value='$_GET[tahun]'>
                  <tr><th scope='row'>Judul </th><td> <b>$tg[Judul] </b></tr>
				 
				  <tr><th scope='row'>Pembimbing 1</th><td><b>$x[Nama], $x[Gelar]</b></td></tr>
				   <tr><th scope='row'>Pembimbing 2</th><td><b>$y[Nama], $y[Gelar]</b></td></tr>
                  <tr><th scope='row'>Penguji 1</th>   <td><select class='form-control' name='PengujiPro1'> 
						<option value='0' selected>- Pilih Penguji 1 -</option>"; 
						$dosen = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by Nama asc");
						while($a = mysqli_fetch_array($dosen)){
						  if ($tg['PengujiPro1']==$a['Login']){
						     echo "<option value='$a[Login]' selected> $a[Nama] - [ $a[Login] ] </option>";
						  }else{
						     echo "<option value='$a[Login]' > $a[Nama] - [ $a[Login] ] </option>";	  
						  }
						}
				echo "</select>
				</td></tr>
				<tr><th scope='row'>Penguji 2</th>   <td><select class='form-control' name='PengujiPro2'> 
						<option value='0' selected>- Pilih Penguji 2 -</option>"; 
						$dosen = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by Nama asc");
						while($a = mysqli_fetch_array($dosen)){
						  if ($tg['PengujiPro2']==$a['Login']){
						     echo "<option value='$a[Login]' selected> $a[Nama] - [ $a[Login] ] </option>";
						  }else{
						     echo "<option value='$a[Login]' > $a[Nama] - [ $a[Login] ] </option>";	  
						  }
						}
				echo "</select>
				</td></tr>
				<tr><th scope='row'>Penguji 3</th>   <td><select class='form-control' name='PengujiPro3'> 
						<option value='0' selected>- Pilih Penguji 3 -</option>"; 
						$dosen = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by Nama asc");
						while($a = mysqli_fetch_array($dosen)){
						  if ($tg['PengujiPro3']==$a['Login']){
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
				
					
			  <tr><th scope='row'>Sidang Proposal</th>  
					<td><input type='text' id='datepicker' class='form-control' data-date-format='yyyy-mm-dd' name='TglUjianProposal' style='width:100px' ></td>
				</tr>  
								  
				 <tr><th scope='row'>Jam Mulai</th>  
					<td><input type='text' class='form-control' name='JamMulaiProSkripsi' style='width:100px' placeholder='hh:ii:ss' value='$tg[JamMulaiProSkripsi]'></td>
				</tr>
				<tr><th scope='row'>Jam Selesai</th>  
					<td><input type='text' class='form-control' name='JamSelesaiProSkripsi' style='width:100px' placeholder='hh:ii:ss' value='$tg[JamSelesaiProSkripsi]'></td>
				</tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='simpanujianproposal' class='btn btn-info'>Simpan</button>
                    <a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&tahun=$_GET[tahun]&prodi=$_GET[prodi]&program=$_GET[program]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";


}elseif($_GET['act']=='ujianskripsi'){
    if (isset($_POST['simpanujianskripsi'])){	       
	$query = mysqli_query($koneksi, "UPDATE jadwal_skripsi SET 							
						  PengujiPro1 			= '".strfilter($_POST['PengujiPro1'])."',							   
						  PengujiPro2 			= '".strfilter($_POST['PengujiPro2'])."',		                                                                                                 
						  PengujiPro3 			= '".strfilter($_POST['PengujiPro3'])."',							
						  TglUjianProposal 		= '".date('Y-m-d', strtotime($_POST['TglUjianProposal']))."',
						  JamMulaiProSkripsi 	= '".strfilter($_POST['JamMulaiProSkripsi'])."',
						  JamSelesaiProSkripsi 	= '".strfilter($_POST['JamSelesaiProSkripsi'])."',
						  TempatUjian			= '".strfilter($_POST['TempatUjian'])."'
						  where JadwalID		='".strfilter($_POST['JadwalID'])."'"); 
	if ($query){
	  echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&JadwalID=$_POST[JadwalID]&sukses';</script>";
	}else{
	  echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&JadwalID=$_POST[JadwalID]&gagal';</script>";
	}
    }
    
    $tg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jadwal_skripsi where JadwalID='".strfilter($_GET['JadwalID'])."'"));
	$mhs = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MhswID,Nama,ProdiID FROM mhsw where MhswID='$tg[MhswID]'"));
	$x   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$tg[PembimbingPro1]'"));
	$y   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$tg[PembimbingPro2]'"));

	
    echo "	
             


              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
			  <div class='card'>
					<div class='card-header'>
					<div class='box-header with-border'>
					<b style='color:purple'>Ujian Proposal Skripsi</b>
					</div>
					<div class='table-responsive'>
                  <table class='table table-sm table-bordered'>
                  <tbody>
                  <input type='hidden' name='JadwalID' value='$_GET[JadwalID]'>	
				  <input type='hidden' name='program' value='$_GET[program]'>	
				  <input type='hidden' name='prodi' value='$_GET[prodi]'>				 
				  <input type='hidden' name='tahun' value='$_GET[tahun]'>
                  <tr><th scope='row' width='200px'>Mahasiswa</th><td><b>$tg[MhswID] - $mhs[Nama]</b></td></tr>
				  <tr><th scope='row'>Judul </th><td> <b>$tg[Judul] </b></tr>
				 
				  <tr><th scope='row'>Pembimbing 1</th><td><b>$x[Nama], $x[Gelar]</b></td></tr>
				   <tr><th scope='row'>Pembimbing 2</th><td><b>$y[Nama], $y[Gelar]</b></td></tr>
                  <tr><th scope='row'>Penguji 1</th>   <td><select class='form-control select2' name='PengujiPro1'> 
						<option value='0' selected>- Pilih Penguji 1 -</option>"; 
						$dosen = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by Nama asc");
						while($a = mysqli_fetch_array($dosen)){
						  if ($tg['PengujiPro1']==$a['Login']){
						     echo "<option value='$a[Login]' selected> $a[Nama] - [ $a[Login] ] </option>";
						  }else{
						     echo "<option value='$a[Login]' > $a[Nama] - [ $a[Login] ] </option>";	  
						  }
						}
				echo "</select>
				</td></tr>
				<tr><th scope='row'>Penguji 2</th>   <td><select class='form-control select2' name='PengujiPro2'> 
						<option value='0' selected>- Pilih Penguji 2 -</option>"; 
						$dosen = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by Nama asc");
						while($a = mysqli_fetch_array($dosen)){
						  if ($tg['PengujiPro2']==$a['Login']){
						     echo "<option value='$a[Login]' selected> $a[Nama] - [ $a[Login] ] </option>";
						  }else{
						     echo "<option value='$a[Login]' > $a[Nama] - [ $a[Login] ] </option>";	  
						  }
						}
				echo "</select>
				</td></tr>
				<tr><th scope='row'>Penguji 3</th>   <td><select class='form-control select2' name='PengujiPro3'> 
						<option value='0' selected>- Pilih Penguji 3 -</option>"; 
						$dosen = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by Nama asc");
						while($a = mysqli_fetch_array($dosen)){
						  if ($tg['PengujiPro3']==$a['Login']){
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
				
					
			 
				<tr><th scope='row'>Sidang Proposal Skripsi</th>  
					<td><input type='text' class='form-control tanggal' name='TglUjianProposal'  value='".date('d-m-Y', strtotime($tg['TglUjianProposal']))."'></td>
				</tr> 				  
				 <tr><th scope='row'>Jam Mulai</th>  
					<td><input type='text' class='form-control' name='JamMulaiProSkripsi' style='width:100px' placeholder='hh:ii:ss' value='$tg[JamMulaiProSkripsi]'></td>
				</tr>
				<tr><th scope='row'>Jam Selesai</th>  
					<td><input type='text' class='form-control' name='JamSelesaiProSkripsi' style='width:100px' placeholder='hh:ii:ss' value='$tg[JamSelesaiProSkripsi]'></td>
				</tr>
                  </tbody>
                  </table>
				  <div class='box-footer'>
				  <button type='submit' name='simpanujianskripsi' class='btn btn-info'>Simpan</button>
				  <a href='index.php?ndelox=ta/jadwal_seminarproposalskripsi&tahun=$_GET[tahun]&prodi=$_GET[prodi]&program=$_GET[program]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>		  
				</div>
                </div>
              </div>

              </form>
            </div>";

}


//PEMBIMBING -----------------------------------------------------
elseif($_GET['act']=='pembimbing'){ ?>

<div class='card'>
<div class='card-header'>
<div class='table-responsive'> 	
<table id="example1" class="table table-bordered table-striped">
  <thead>
	<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#c9dd0a;width:100%;>
	  <th style='width:20px'>No</th>
	  <th style='width:300px'>Nama Pembimbing </th> 
		<th style=text-align:center;>Pembimbing 1</th>  
        <th style=text-align:center;>Pembimbing 2</td>
		<th style=text-align:center;>Pembimbing 1 + 2</td>
		<th style=text-align:center;>Penguji 3</td>
		<th style=text-align:center;>Total Menguji + Membimbing</td>
	</tr>
  </thead>
  <tbody>
<?php	
	$tampil = mysqli_query($koneksi, "SELECT Login,Nama,Gelar,Homebase,NA from dosen where Homebase='".strfilter($_GET['prodi'])."' and NA='N' AND Login Not IN('1021087201','1023037506 ')");                  		
	while($r=mysqli_fetch_array($tampil)){	
        $PembimbingProSkripsi1 =mysqli_fetch_array(mysqli_query("select Count(PembimbingPro1) as JmlPembimbingPro1,TahunID,ProdiID from jadwal_skripsi where PembimbingPro1='$r[Login]' and TahunID='".strfilter($_GET[tahun])."' and ProdiID='".strfilter($_GET[prodi])."'"));
		$PembimbingProSkripsi2 =mysqli_fetch_array(mysqli_query("select Count(PembimbingPro2) as JmlPembimbingPro2,TahunID,ProdiID from jadwal_skripsi where PembimbingPro2='$r[Login]' and TahunID='".strfilter($_GET[tahun])."' and ProdiID='".strfilter($_GET[prodi])."'"));
		$PengujiProSkripsi3 =mysqli_fetch_array(mysqli_query("select Count(PembimbingPro2) as JmlPengujiPro3,TahunID,ProdiID from jadwal_skripsi where PengujiPro3='$r[Login]' and TahunID='".strfilter($_GET[tahun])."' and ProdiID='".strfilter($_GET[prodi])."'"));
        $ttotalmembimbing = $PembimbingProSkripsi1['JmlPembimbingPro1'] + $PembimbingProSkripsi2['JmlPembimbingPro2'];
	$no++;	
	$Pembimbing1x 	= strtolower($r['Nama']);
	$Pembimbing1	= ucwords($Pembimbing1x);
	echo "<tr>
	<td>$no</td>             
	<td>$r[Login] - $Pembimbing1, $r[Gelar]<br>				
	<td style=text-align:center;>$PembimbingProSkripsi1[JmlPembimbingPro1]</td>
	<td style=text-align:center;>$PembimbingProSkripsi2[JmlPembimbingPro2]</td>
	<td style=text-align:center;font-size:14px;font-weight:reguler;>$ttotalmembimbing</td>
	<td style=text-align:center;>$PengujiProSkripsi3[JmlPengujiPro3]</td>
	<td style=text-align:center;>$ttotal</td>
	</tr>";
	  }
	?>
<tbody>
</table>
</div>
</div>
<?php 
}


elseif($_GET['act']=='inputnilaiproposal'){	  	 	  

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
 	
        <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
        <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
		<table id="example" class="table table-bordered table-striped">
          <thead>
            <tr style='text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:purple;width:100%'>
              <th style='width:20px'>No</th>
              <th style='width:70px'>NIM</th>  
              <th style='width:150px'>Nama</th>
               <th style='width:100px'>Judul Proposal</th>
              <th style='width:100px'>Nilai Penguji1</th>  
              <th style='width:100px'>Nilai Penguji2</th> 
              <th style='width:100px'>Nilai Penguji3</th>                                        
            
            </tr>
          </thead>
          <tbody>
        <?php
          if (isset($_GET['tahun'])){			    
				echo"
				<input type='hidden' name='prodi' value='$_GET[prodi]'>
				<input type='hidden' name='tahun' value='$_GET[tahun]'>";			
			    $no = 1;
			    $tampil = mysqli_query($koneksi, "SELECT * from vw_jadwal_skripsi_ujian where TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."' order by TglUjianProposal,JamMulaiProSkripsi asc");                  		 
			    while($r=mysqli_fetch_array($tampil)){			 			 
			    $p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PembimbingPro1]'"));
		        $p2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PembimbingPro2]'"));
		        $pj1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiPro1]'"));
		        $pj2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiPro2]'"));
		        $pj3 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiPro3]'"));
			    $NamaMhsx 	= strtolower($r['Nama']);
				$NamaMhs    = ucwords($NamaMhsx);
				$Judulx 	= strtolower($r['Judul']);
				$Judul 		= ucwords($Judulx);
				$NamaP1x 	= strtolower($p1['Nama']);
				$NamaP1 	= ucwords($NamaP1x);
				$NamaP2x 	= strtolower($p2['Nama']);
				$NamaP2 	= ucwords($NamaP2x);
				
				$NamaP1x 	= strtolower($pj1['Nama']);
				$NamaP1 	= ucwords($NamaP1x);
				$NamaP2x 	= strtolower($pj2['Nama']);
				$NamaP2 	= ucwords($NamaP2x);
				$NamaP3x 	= strtolower($pj3['Nama']);
				$NamaP3 	= ucwords($NamaP3x);
				echo "<tr><td>$no</td>   
					<td>$r[MhswID]</td>  
					<td><b>$NamaMhs</b><br>
						1. $NamaP1, $p1[Gelar]<br>
						2. $NamaP2, $p2[Gelar]
					</td>              
                    <td>$Judul</td>					                                              									
					<input type='hidden' value='$r[MhswID]' name='MhswID".$no."'>					
					<td>$NamaP1<br><input type='text'  style='width:90px; text-align:center; padding:0px' maxlength='3' name='NilaiPengujiPro1".$no."' value='$r[NilaiPengujiPro1]'></td>
					<td>$NamaP2<br><input type='text'  style='width:90px; text-align:center; padding:0px' maxlength='3' name='NilaiPengujiPro2".$no."' value='$r[NilaiPengujiPro2]'></td>
					<td>$NamaP3<br><input type='text'  style='width:90px; text-align:center; padding:0px' maxlength='3' name='NilaiPengujiPro3".$no."' value='$r[NilaiPengujiPro3]'></td>								
					</tr>";
                    $no++;
              }
              ?>
              <tbody>
              </table>
              <div class='box-footer'>
              <button type='submit' name='simpannilai' class='btn btn-info pull-right'>Perbaharui Nilai</button>                      
              </div>
              </form>
              </div><!-- /.box-body --> 
              </div>
            </div>
            
            <?php
            if (isset($_POST['simpannilai'])){ 			 	
				//$jml_data 			= count($_POST[MhswID]); 
				$jml_data 			= mysqli_num_rows(mysqli_query($koneksi, "SELECT MhswID,ProdiID,TahunID FROM jadwal_skripsi where ProdiID='".strfilter($_GET['prodi'])."' AND TahunID='".strfilter($_GET['tahun'])."'"));//prihan			
				for ($i=1; $i <= $jml_data; $i++){
			    $tahun 				= strfilter($_POST['tahun']);	
				$MhswID 			= strfilter($_POST['MhswID'.$i]);
				$NilaiPengujiPro1 	= strfilter($_POST['NilaiPengujiPro1'.$i]);
				$NilaiPengujiPro2 	= strfilter($_POST['NilaiPengujiPro2'.$i]);
				$NilaiPengujiPro3 	= strfilter($_POST['NilaiPengujiPro3'.$i]);								
				//$cek = mysqli_query($koneksi, "SELECT * FROM jadwal_skripsi_ujian where MhswID='".$MhswID[$i]."'"); //JadwalID='$_POST[JadwalID]' AND 
				//$total = mysqli_num_rows($cek);
				//if ($total >= 1){
				    $query = mysqli_query($koneksi, "UPDATE jadwal_skripsi SET 
									  NilaiPengujiPro1	='$NilaiPengujiPro1',
									  NilaiPengujiPro2	='$NilaiPengujiPro2',
									  NilaiPengujiPro3	='$NilaiPengujiPro3'
									  WHERE MhswID		='$MhswID' 
									  AND TahunID		='$tahun'");                  
			   // }			  
			}
			
		  if ($query){
		     echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=inputnilaiproposal&JadwalID=".$_POST['JadwalID']."&program=".$_POST['program']."&prodi=".$_POST['prodi']."&tahun=".$_POST['tahun']."&sukses';</script>";
		  }else{
		     echo "<script>document.location='index.php?ndelox=ta/jadwal_seminarproposalskripsi&act=inputnilaiproposal&JadwalID=".$_POST['JadwalID']."&program=".$_POST['program']."&prodi=".$_POST['prodi']."&tahun=".$_POST['tahun']."gagal';</script>";
		  }  
			  
		}
	}
 }


?>