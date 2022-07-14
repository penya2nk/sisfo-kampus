<div class="card">
<div class="card-header">

                  
<div class="form-group row">
<label class="col-md-5 col-form-label text-md-right"><b style='color:purple'>PENGAJUAN JUDUL SKRIPSI</b></label>
<?php 
echo "<b style='color:green;font-size:20px'></b> <a  href='print_reportxls/pengajuanjudulskripsixls.php?tahun=$_GET[tahun]&prodi=$_GET[prodi]' target='_BLANK'> Exp Excel</a>";
?>	
<div class="col-md-2">
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>	
<input type="hidden" name='ndelox' value='ta/pjudultaadm'>
<select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
<?php 
    echo "<option value=''>- Pilih Tahun Akademik -</option>";
    $tahun = mysqli_query($koneksi, "SELECT distinct(TahunID)FROM tahun order by TahunID Desc"); //and NA='N'
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
<input type="submit"  class='btn btn-success btn-sm' value='Lihat'>
</form>

</div>
<div class="col-md-1">
<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=ta/pjudultaadm&act=tambahdata&tahun=<?php echo $_GET['tahun']; ?>&prodi=<?php echo "$_GET[prodi]"; ?>&program=<?php echo 	"$_GET[program]"; ?>'>Tambahkan Data</a>
</div>


<?php 
echo"<h4>";
echo "<b style='color:green;font-size:20px'></b> <a  href='index.php?ndelox=ta/pjudultaadm&tahun=$_GET[tahun]&prodi=$_GET[prodi]'>| PENGAJUAN BARU </a> | ";
echo "<b style='color:green;font-size:20px'></b> <a  href='index.php?ndelox=ta/pjudultaadm&act=diterima&tahun=$_GET[tahun]&prodi=$_GET[prodi]'> DITERIMA </a> | ";
echo "<b style='color:green;font-size:20px'></b> <a  href='index.php?ndelox=ta/pjudultaadm&act=ditolak&tahun=$_GET[tahun]&prodi=$_GET[prodi]'> DITOLAK </a> | ";
echo"</h4>";
?>

</div>
</div>       
</div>             

<?php if ($_GET['act']==''){ 

?>
<div class="card">
<div class="card-header">

<table id="example1" class="table table-sm table-bordered table-striped">
<thead>
<tr style="background:purple;color:white">
  <th style='width:10px'>No</th>              
  <th style='width:20px'>NIM</th>
  <th style='width:150px'>Nama Mahasiswa</th>              
  <th style='width:200px'>Judul Penelitian</th>                                    
  <th style='width:60px'>Tempat</th>
  <th style='width:60px'>Status</th>
  <th width='100px'>Action</th> 
</tr>
</thead>
<tbody>
<?php
if (isset($_GET['tahun'])){
  $tampil = mysqli_query($koneksi, "SELECT t_penelitian.*,mhsw.Nama,mhsw.ProdiID 
        FROM t_penelitian,mhsw 
        WHERE mhsw.MhswID=t_penelitian.MhswID 
        AND t_penelitian.TahunID='".strfilter($_GET['tahun'])."' 
        AND mhsw.ProdiID='".strfilter($_GET['prodi'])."' AND Status='WAITING'
        order by mhsw.MhswID asc"); //AND Status NOT IN ('DITOLAK','DITERIMA')
   
	$no = 1;
	while($r=mysqli_fetch_array($tampil)){	
	if ($r['Status']=='DITERIMA'){
		$c="style=color:green";
	}
	else if ($r['Status']=='WAITING'){
		$c="style=color:black";
	}
	else{
		$c="style=color:red";
	}	 
	$judulx 	= strtolower($r['Judul']);
	$Judul		= ucwords($judulx);
	$TempatPenelitianx 		= strtolower($r['TempatPenelitian']);
	$TempatPenelitian		= ucwords($TempatPenelitianx);
	
	$p1 		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen WHERE Login='$r[Pembimbing1]'"));
	$dosenx 	= strtolower($p1['Nama']);
	$namados1	= ucwords($dosenx);
	$p2 		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen WHERE Login='$r[Pembimbing2]'"));  
	$doseny 	= strtolower($p2['Nama']);
	$namados2	= ucwords($doseny);
	echo "<tr>
	<td>$no</td>            
	<td>$r[MhswID]</td>
	<td>$r[Nama]<br>
	1. $namados1, $p1[Gelar]<br>
	2. $namados2, $p2[Gelar]
	</td>
	<td>$Judul <font style=color:red;font-size:10px;><i>($r[Komentar])</i></font> <br>
	<a href='index.php?ndelox=ta/pjudultaadm&accept&IDX=$r[IDPenelitian]&prodi=$r[ProdiID]&tahun=$r[TahunID]&MhswID=$r[MhswID]&Judul=$r[Judul]&P1=$r[Pembimbing1]&P2=$r[Pembimbing2]'> [TERIMA] </a> -
	<a href='index.php?ndelox=ta/pjudultaadm&reject&IDX=$r[IDPenelitian]&prodi=$r[ProdiID]&tahun=$r[TahunID]&MhswID=$r[MhswID]'> [TOLAK] </a> -
	<a href='?ndelox=ta/pjudultaadm&act=komentar&IDX=$r[IDPenelitian]&tahun=$r[TahunID]&prodi=$r[ProdiID]'>Komentar dan Pembimbing</a>
	</td>
	<td>$TempatPenelitian</td>
	<td $c><b>$r[Status]</b></td>  
	<td style='width:70px !important'><center><a class='btn btn-success btn-xs' title='Edit Data' href='index.php?ndelox=ta/pjudultaadm&act=edit&IDX=$r[IDPenelitian]&prodi=$r[ProdiID]&tahun=$r[TahunID]'><i class='fa fa-edit'></i></a>
	<a style='margin-right:5px; width:30px' class='btn btn-info btn-xs' title='Cek URL' href='$r[URLX]' target=_BLANK><i class='fa fa-download'></i></a>
	
	<a class='btn btn-danger btn-xs' title='Hapus Data' href='index.php?ndelox=ta/pjudultaadm&hapus=$r[IDPenelitian]&prodi=$r[ProdiID]&tahun=$r[TahunID]&MhswID=$r[MhswID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
	</center></td>";
	
	echo "</tr>";
	$no++;
	}
	}
	if (isset($_GET['hapus'])){
	mysqli_query($koneksi,"DELETE FROM t_penelitian where IDPenelitian='".strfilter($_GET['hapus'])."'");
	echo "<script>document.location='index.php?ndelox=ta/pjudultaadm&prodi=$_GET[prodi]&tahun=$_GET[tahun]&MhswID=$_GET[MhswID]';</script>";
	}
	if (isset($_GET['accept'])){
		$sqlcek1=mysqli_query($koneksi, "select IDPenelitian,Pembimbing1,Pembimbing2 from t_penelitian where IDPenelitian='".strfilter($_GET['IDX'])."'");
		$datap1=mysqli_fetch_array($sqlcek1);
		if ($datap1['Pembimbing1']=='-' OR $datap1['Pembimbing2']=='-'){
		    echo "<script language='javascript'>alert('Set Pembimbing 1 dan Pembimbing 2 terlebih dahulu!');
			window.location = 'index.php?ndelox=ta/pjudultaadm&prodi=$_GET[prodi]&tahun=$_GET[tahun]&MhswID=$_GET[MhswID]'</script>";
			exit; 
		}	
		mysqli_query($koneksi,"update t_penelitian set Status='DITERIMA',Komentar='Judul OK' where IDPenelitian='".strfilter($_GET['IDX'])."'");		
		$sqlcek2=mysqli_query($koneksi,"select MhswID,JadwalID,TahunID from jadwal_skripsi where MhswID='".strfilter($_GET['MhswID'])."' AND TahunID='".strfilter($_GET['tahun'])."'");
		$cek2=mysqli_num_rows($sqlcek2);
		if ($cek2>0){
		    echo "<script>document.location='index.php?ndelox=ta/pjudultaadm&prodi=$_GET[prodi]&tahun=$_GET[tahun]&MhswID=$_GET[MhswID]';</script>";
		}else{
			mysqli_query($koneksi, "INSERT into jadwal_skripsi(IDPenelitian,
															   TahunID,												   
															   MhswID,
															   Judul,
															   PembimbingPro1,
															   PembimbingPro2,
															   PengujiPro1,
															   PengujiPro2,
															   PembimbingSkripsi1,
															   PembimbingSkripsi2,
															   PengujiSkripsi1,
															   PengujiSkripsi2,
															   ProdiID)
													    values('".strfilter($_GET['IDX'])."',
															   '".strfilter($_GET['tahun'])."',
															   '".strfilter($_GET['MhswID'])."',
															   '".strfilter($_GET['Judul'])."',
															   '".strfilter($_GET['P1'])."',
															   '".strfilter($_GET['P2'])."',
															   '".strfilter($_GET['P1'])."',
															   '".strfilter($_GET['P2'])."',
															   '".strfilter($_GET['P1'])."',
															   '".strfilter($_GET['P2'])."',
															   '".strfilter($_GET['P1'])."',
															   '".strfilter($_GET['P2'])."',
															   '".strfilter($_GET['prodi'])."')");
		    echo "<script>document.location='index.php?ndelox=ta/pjudultaadm&prodi=$_GET[prodi]&tahun=$_GET[tahun]&MhswID=$_GET[MhswID]';</script>";
	    } 
	}
	if (isset($_GET[reject])){
	mysqli_query("update t_penelitian set Status='DITOLAK',Komentar='Judul belum memenuhi kriteria' where IDPenelitian='".strfilter($_GET[IDX])."'");
	echo "<script>document.location='index.php?ndelox=ta/pjudultaadm&prodi=$_GET[prodi]&tahun=$_GET[tahun]&MhswID=$_GET[MhswID]';</script>";
}
?>
<tbody>
</table>



<?php 
}

else if ($_GET['act']=='diterima'){ 
?>
<div class="card">
<div class="card-header">
<table id="example1" class="table table-sm table-bordered table-striped">
<thead>
<tr style="background:purple;color:white">
  <th style='width:10px'>No</th>              
  <th style='width:20px'>NIM</th>
  <th style='width:150px'>Nama Mahasiswa</th>              
  <th style='width:200px'>Judul Penelitian</th>                                    
  <th style='width:60px'>Tempat</th>
  <th style='width:60px'>Status</th>
  <th width='100px'>Action</th> 
</tr>
</thead>
<tbody>
<?php
if (isset($_GET['tahun'])){
  $tampil = mysqli_query($koneksi, "SELECT t_penelitian.*,mhsw.Nama,mhsw.ProdiID 
        FROM t_penelitian,mhsw 
        WHERE mhsw.MhswID=t_penelitian.MhswID 
        AND t_penelitian.TahunID='".strfilter($_GET['tahun'])."' 
        AND mhsw.ProdiID='".strfilter($_GET['prodi'])."' AND Status='DITERIMA'
         order by mhsw.MhswID asc"); //AND Status NOT IN ('DITOLAK','DITERIMA')
   
	$no = 1;
	while($r=mysqli_fetch_array($tampil)){	
	if ($r['Status']=='DITERIMA'){
		$c="style=color:green";
	}
	else if ($r['Status']=='WAITING'){
		$c="style=color:black";
	}
	else{
		$c="style=color:red";
	}	 
	$judulx 	= strtolower($r['Judul']);
	$Judul		= ucwords($judulx);
	$TempatPenelitianx 		= strtolower($r['TempatPenelitian']);
	$TempatPenelitian		= ucwords($TempatPenelitianx);
	
	$p1 		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen WHERE Login='$r[Pembimbing1]'"));
	$dosenx 	= strtolower($p1['Nama']);
	$namados1	= ucwords($dosenx);
	$p2 		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen WHERE Login='$r[Pembimbing2]'"));  
	$doseny 	= strtolower($p2['Nama']);
	$namados2	= ucwords($doseny);
	echo "<tr>
	<td>$no</td>            
	<td>$r[MhswID]</td>
	<td>$r[Nama]<br>
	1. $namados1, $p1[Gelar]<br>
	2. $namados2, $p2[Gelar]
	</td>
	<td>$Judul <font style=color:red;font-size:10px;><i>($r[Komentar])</i></font> <br>
	<a href='index.php?ndelox=ta/pjudultaadm&accept&IDX=$r[IDPenelitian]&prodi=$r[ProdiID]&tahun=$r[TahunID]&MhswID=$r[MhswID]&Judul=$r[Judul]&P1=$r[Pembimbing1]&P2=$r[Pembimbing2]'> [TERIMA] </a> -
	<a href='index.php?ndelox=ta/pjudultaadm&reject&IDX=$r[IDPenelitian]&prodi=$r[ProdiID]&tahun=$r[TahunID]&MhswID=$r[MhswID]'> [TOLAK] </a> -
	<a href='?ndelox=ta/pjudultaadm&act=komentar&IDX=$r[IDPenelitian]&tahun=$r[TahunID]&prodi=$r[ProdiID]'>Komentar dan Pembimbing</a>
	</td>
	<td>$TempatPenelitian</td>
	<td $c><b>$r[Status]</b></td>  
	<td style='width:70px !important'><center><a class='btn btn-success btn-xs' title='Edit Data' href='index.php?ndelox=ta/pjudultaadm&act=edit&IDX=$r[IDPenelitian]&prodi=$r[ProdiID]&tahun=$r[TahunID]'><i class='fa fa-edit'></i></a>
	<a style='margin-right:5px; width:30px' class='btn btn-info btn-xs' title='Cek URL' href='$r[URLX]' target=_BLANKs><i class='fa fa-download'></i></a>
	
	</td>";
	
	echo "</tr>";
	$no++;
	}
	}
	
	if (isset($_GET['hapus'])){
	mysqli_query($koneksi,"DELETE FROM t_penelitian where IDPenelitian='".strfilter($_GET['hapus'])."'");
	echo "<script>document.location='index.php?ndelox=ta/pjudultaadm&prodi=$_GET[prodi]&tahun=$_GET[tahun]&MhswID=$_GET[MhswID]';</script>";
	}
	if (isset($_GET['accept'])){
		$sqlcek1=mysqli_query($koneksi,"select IDPenelitian,Pembimbing1,Pembimbing2 from t_penelitian where IDPenelitian='".strfilter($_GET['IDX'])."'");
		$datap1=mysqli_fetch_array($sqlcek1);
		if ($datap1['Pembimbing1']=='-' OR $datap1['Pembimbing2']=='-'){
		    echo "<script language='javascript'>alert('Set Pembimbing 1 dan Pembimbing 2 terlebih dahulu!');
			window.location = 'index.php?ndelox=ta/pjudultaadm&prodi=$_GET[prodi]&tahun=$_GET[tahun]&MhswID=$_GET[MhswID]'</script>";
			exit; 
		}	
		mysqli_query($koneksi, "update t_penelitian set Status='DITERIMA',Komentar='Judul OK' where IDPenelitian='".strfilter($_GET['IDX'])."'");		
		$sqlcek2=mysqli_query($koneksi,"select MhswID,JadwalID,TahunID from jadwal_skripsi where MhswID='".strfilter($_GET['MhswID'])."' AND TahunID='".strfilter($_GET['tahun'])."'");
		$cek2=mysqli_num_rows($sqlcek2);
		if ($cek2>0){
		    echo "<script>document.location='index.php?ndelox=ta/pjudultaadm&prodi=$_GET[prodi]&tahun=$_GET[tahun]&MhswID=$_GET[MhswID]';</script>";
		}else{
			mysqli_query($koneksi, "INSERT into jadwal_skripsi(IDPenelitian,
															   TahunID,												   
															   MhswID,
															   Judul,
															   PembimbingPro1,
															   PembimbingPro2,
															   PengujiPro1,
															   PengujiPro2,
															   PembimbingSkripsi1,
															   PembimbingSkripsi2,
															   PengujiSkripsi1,
															   PengujiSkripsi2,
															   ProdiID)
													    values('".strfilter($_GET['IDX'])."',
															   '".strfilter($_GET['tahun'])."',
															   '".strfilter($_GET['MhswID'])."',
															   '".strfilter($_GET['Judul'])."',
															   '".strfilter($_GET['P1'])."',
															   '".strfilter($_GET['P2'])."',
															   '".strfilter($_GET['P1'])."',
															   '".strfilter($_GET['P2'])."',
															   '".strfilter($_GET['P1'])."',
															   '".strfilter($_GET['P2'])."',
															   '".strfilter($_GET['P1'])."',
															   '".strfilter($_GET['P2'])."',
															   '".strfilter($_GET['prodi'])."')");
		    echo "<script>document.location='index.php?ndelox=ta/pjudultaadm&prodi=$_GET[prodi]&tahun=$_GET[tahun]&MhswID=$_GET[MhswID]';</script>";
	    } 
	}
	if (isset($_GET['reject'])){
	mysqli_query($koneksi, "update t_penelitian set Status='DITOLAK',Komentar='Judul belum memenuhi kriteria' where IDPenelitian='".strfilter($_GET['IDX'])."'");
	echo "<script>document.location='index.php?ndelox=ta/pjudultaadm&prodi=$_GET[prodi]&tahun=$_GET[tahun]&MhswID=$_GET[MhswID]';</script>";
}
?>

<tbody>
</table>
<?php 
} 

else if ($_GET['act']=='ditolak'){ 
?>

<div class="card">
<div class="card-header">
<table id="example1" class="table table-sm table-bordered table-striped">
<thead>
<tr style="background:purple;color:white">
  <th style='width:10px'>No</th>              
  <th style='width:20px'>NIM</th>
  <th style='width:150px'>Nama Mahasiswa</th>              
  <th style='width:200px'>Judul Penelitian</th>                                    
  <th style='width:60px'>Tempat</th>
  <th style='width:60px'>Status</th>
  <th width='100px'>Action</th> 
</tr>
</thead>
<tbody>
<?php
if (isset($_GET['tahun'])){
  $tampil = mysqli_query($koneksi, "SELECT t_penelitian.*,mhsw.Nama,mhsw.ProdiID 
        FROM t_penelitian,mhsw 
        WHERE mhsw.MhswID=t_penelitian.MhswID 
        AND t_penelitian.TahunID='".strfilter($_GET['tahun'])."' 
        AND mhsw.ProdiID='".strfilter($_GET['prodi'])."' AND Status='DITOLAK'
         order by mhsw.MhswID asc"); //AND Status NOT IN ('DITOLAK','DITERIMA')
   
	$no = 1;
	while($r=mysqli_fetch_array($tampil)){	
	if ($r['Status']=='DITERIMA'){
		$c="style=color:green";
	}
	else if ($r['Status']=='WAITING'){
		$c="style=color:black";
	}
	else{
		$c="style=color:red";
	}	 
	$judulx 	= strtolower($r['Judul']);
	$Judul		= ucwords($judulx);
	$TempatPenelitianx 		= strtolower($r['TempatPenelitian']);
	$TempatPenelitian		= ucwords($TempatPenelitianx);
	
	$p1 		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen WHERE Login='$r[Pembimbing1]'"));
	$dosenx 	= strtolower($p1['Nama']);
	$namados1	= ucwords($dosenx);
	$p2 		= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen WHERE Login='$r[Pembimbing2]'"));  
	$doseny 	= strtolower($p2['Nama']);
	$namados2	= ucwords($doseny);
	echo "<tr>
	<td>$no</td>            
	<td>$r[MhswID]</td>
	<td>$r[Nama]<br>
	1. $namados1, $p1[Gelar]<br>
	2. $namados2, $p2[Gelar]
	</td>
	<td>$Judul <font style=color:red;font-size:10px;><i>($r[Komentar])</i></font> <br>
	<a href='index.php?ndelox=ta/pjudultaadm&accept&IDX=$r[IDPenelitian]&prodi=$r[ProdiID]&tahun=$r[TahunID]&MhswID=$r[MhswID]'> [TERIMA] </a> -
	<a href='index.php?ndelox=ta/pjudultaadm&reject&IDX=$r[IDPenelitian]&prodi=$r[ProdiID]&tahun=$r[TahunID]&MhswID=$r[MhswID]'> [TOLAK] </a> -
	<a href='?ndelox=ta/pjudultaadm&act=komentar&IDX=$r[IDPenelitian]&tahun=$r[TahunID]&prodi=$r[ProdiID]'> Komentar dan Pembimbing</a>
	</td>
	<td>$TempatPenelitian</td>
	<td $c><b>$r[Status]</b></td>  
	<td style='width:70px !important'><center><a class='btn btn-success btn-xs' title='Edit Data' href='index.php?ndelox=ta/pjudultaadm&act=edit&IDX=$r[IDPenelitian]&prodi=$r[ProdiID]&tahun=$r[TahunID]'><i class='fa fa-edit'></i></a>
	<a style='margin-right:5px; width:30px' class='btn btn-info btn-xs' title='Cek URL' href='$r[URLX]' target=_BLANK><i class='fa fa-download'></i></a>
	
	</td>";
	
	echo "</tr>";
	$no++;
	}
	}
	
?>
<tbody>
</table>

<?php
} //tutup elseif























//--------------------------------------------------------------------------------------------------------------
elseif($_GET['act']=='komentar'){
if (isset($_POST['koment'])){	
 $qr = mysqli_query("UPDATE t_penelitian SET Komentar = '".strfilter($_POST['Komentar'])."',
					Pembimbing1='".strfilter($_POST['Pembimbing1'])."',
					Pembimbing2='".strfilter($_POST['Pembimbing2'])."'
					WHERE IDPenelitian='".strfilter($_POST['IDX'])."'");	
if ($qr){
		echo "<script>document.location='index.php?ndelox=ta/pjudultaadm&tahun=$_POST[tahun]&prodi=$_POST[prodi]&sukses';</script>";
	}else{
		echo "<script>document.location='index.php?ndelox=ta/pjudultaadm&tahun=$_POST[tahun]&prodi=$_POST[prodi]&gagal';</script>";
	}
}
$e = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_penelitian where IDPenelitian='".strfilter($_GET['IDX'])."'"));
$m = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MhswID,Nama from mhsw where MhswID='$e[MhswID]'"));
echo "<div class='card'>
<div class='card-header'>
    <div class='box-header'>
		<div class='box-header with-border'>
		  <h3 class='box-title'>Komentar</h3>
		</div>
	  <div class='box-body'>
	  <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
	    <input type='hidden' name='IDX' value='$_GET[IDX]'>
		<input type='hidden' name='tahun' value='$_GET[tahun]'>
		<input type='hidden' name='prodi' value='$_GET[prodi]'>
		<div class='col-md-12'>
		  <table class='table table-condensed table-bordered'>
		  <tbody>
		  <tr><th  scope='row' width='200px'>Mahasiswa</th> <td><input type='text' class='form-control' name='x' value='$e[MhswID] - $m[Nama]'></td></tr>
		  <tr><th  scope='row' width='200px'>Judul Penelitian</th> <td><input type='text' class='form-control' name='Judul' value='$e[Judul]'></td></tr>
		  <tr><th scope='row'>Pembimbing 1</th>   <td><select class='form-control' name='Pembimbing1'> 
					<option value='-' selected>- Pilih Pembimbing 1 -</option>"; 
					$dosen = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by Nama asc");
					while($a = mysqli_fetch_array($dosen)){
					  if ($e['Pembimbing1']==$a['Login']){
						 echo "<option value='$a[Login]' selected> $a[Nama] - [ $a[Login] ] </option>";
					  }else{
						 echo "<option value='$a[Login]' > $a[Nama] - [ $a[Login] ] </option>";	  
					  }
					}
			echo "</select>
			</td></tr>
			<tr><th scope='row'>Pembimbing 2</th>   <td><select class='form-control' name='Pembimbing2'> 
					<option value='-' selected>- Pilih Pembimbing 2 -</option>"; 
					$dosen = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by Nama asc");
					while($a = mysqli_fetch_array($dosen)){
					  if ($e['Pembimbing2']==$a['Login']){
						 echo "<option value='$a[Login]' selected> $a[Nama] - [ $a[Login] ] </option>";
					  }else{
						 echo "<option value='$a[Login]' > $a[Nama] - [ $a[Login] ] </option>";	  
					  }
					}
				echo "</select>
				</td></tr>
		  
		  <tr ><th  scope='row' width='200px'>Berikan Komentar</th> <td><textarea  class='form-control' name='Komentar' rows=4>$e[Komentar]</textarea></td></tr>
		  </tbody>
		  </table>
		</div>
	  </div>
	  <div class='box-footer'>
			<button type='submit' name='koment' class='btn btn-info'>Tambahkan Komentar</button>
			<a href='index.php?ndelox=ta/pjudultaadm&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
			
		  </div>
	  </form>
	</div>";
}

//-----------------------------------------------------------------------------------------------------------------------------
elseif($_GET['act']=='tambahdata'){
 if (isset($_POST['kirimkan'])){		
  $query = mysqli_query("INSERT INTO t_penelitian(
				MhswID,
				Judul,
				TahunID,							
				TempatPenelitian,
				Abstrak,
				TglPengajuan,
				URLX,
				ProdiID) 
				VALUES('".strfilter($_POST['MhswID'])."',
				'".strfilter($_POST['Judul'])."',
				'".strfilter($_POST['TahunID'])."',
				'".strfilter($_POST['TempatPenelitian'])."',
				'".strfilter($_POST['Abstrak'])."',
				'".date('Y-m-d')."',
				'".strfilter($_POST['URLX'])."',
				'".strfilter($_POST['ProdiID'])."')");
		echo "<script>document.location='index.php?ndelox=ta/pjudultaadm&tahun=$_GET[tahun]&prodi=$_GET[prodi]&sukses';</script>";							
 }//tutup kirimkan
	
if ($_GET['prodi']=='SI'){$prod ="Sistem Informasi";}else{$prod ="Teknik Informatika"; }
echo "<div class='card'>
<div class='card-header'>

    <div class='box-header'>  	
	  <h3 class='box-title'>Pengajuan Judul Proposal Penelitian</h3>
	</div>

 <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>   
 <div class='row'>
	<div class='col-md-6'>
				<table class='table table-sm table-bordered'>
				<tbody>
					
				<tr><th scope='row'> Mahasiswa</th>    <td>
				<select name='MhswID' class='combobox form-control'>
				<option value=''> Pencarian Data Mahasiswa </option>";
				$program = mysqli_query($koneksi, "SELECT MhswID,Nama,ProgramID,ProdiID,Handphone FROM mhsw where ProdiID='".strfilter($_GET['prodi'])."' and StatusMhswID='A' order by MhswID asc");
				while ($k = mysqli_fetch_array($program)){
				if ($_GET['MhswID']==$k['MhswID']){
					echo "<option value='$k[MhswID]' selected>$k[MhswID] - $k[Nama] - $k[ProgramID] - $k[Handphone] </option>";
				}else{
					echo "<option value='$k[MhswID]'>$k[MhswID] - $k[Nama] - $k[ProgramID] - $k[Handphone]</option>";
				}
				}
				echo"</select></td>
				</tr>

				<tr>
				<th scope='row' style=width:200px>Judul Proposal Penelitian</th>    
				<td scope='row'><textarea class='form-control' rows='3' name='Judul'></textarea></td>
				</tr>
									
				<tr><th scope='row'>Tempat Penelitian</th>    <td><input type='text' class='form-control' name='TempatPenelitian'></td></tr>
				
				<tr><th scope='row'>URL</th>    <td><input type='text' class='form-control' name='URLX'></td></tr>
				<tr><th colspan='2'>	
					<div class='box-footer'>
					<button type='submit' name='kirimkan' class='btn btn-info'>Tambahkan</button>
					<a href='index.php?ndelox=ta/pjudultaadm&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
					<input type=reset name='Reset' value='&nbsp;Reset&nbsp;&nbsp;&nbsp;&nbsp;' class='btn btn-info'>      
					</div>
				</th></tr>
				</tbody>
				</table>
	</div>
	
	<div class='col-md-6'>
			<table class='table table-sm table-bordered'>
			<tbody>
			
			<tr>
			<th scope='row'>Program Studi</th><td><input type='text' class='form-control' name='ProdiID' value='$_GET[prodi]' readonly=''>
			</td>
			</tr>
			
			<tr>
			<th scope='row'>Tahun Akademik <i><b style='color:red;'>(ex. 20171)</b></i></th><td><input type='text' class='form-control' name='TahunID' value='$_SESSION[tahun_akademik]'>
			</td>
			</tr> 
			
			<tr>
			<th scope='row'>Deskripsi</td>    
			<td $c><textarea class='form-control' rows='6' name='Abstrak'></textarea></td>
			</tr>
			
			</tbody>
			</table>
		
  		</form>

	</div>";	  

echo"</div></div>";

}elseif($_GET['act']=='edit'){
    if (isset($_POST['update'])){		
        $query = mysqli_query("UPDATE t_penelitian SET 
							  Judul 		   = '".strfilter($_POST['Judul'])."',
							  TempatPenelitian = '".strfilter($_POST['TempatPenelitian'])."',
							  URLX			   = '".strfilter($_POST['URLX'])."'
							  where IDPenelitian='".strfilter($_POST['IDX'])."'");
if ($query){
  echo "<script>document.location='index.php?ndelox=ta/pjudultaadm&tahun=$_POST[tahun]&prodi=$_POST[prodi]&IDX=$_POST[IDX]&sukses';</script>";
}else{
  echo "<script>document.location='index.php?ndelox=ta/pjudultaadm&tahun=$_POST[tahun]&prodi=$_POST[prodi]&IDX=$_POST[IDX]&gagal';</script>";
}
}
    
    $e = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM t_penelitian where IDPenelitian='".strfilter($_GET['IDX'])."'"));
    echo "<div class='card'>
	<div class='card-header'>
             
		<div class='box-header with-border'>
		  <h3 class='box-title'>Edit Data </h3>
		</div>
	  <div class='box-body'>
	  <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
		<div class='col-md-12'>
		  <table class='table table-sm table-bordered'>
		  <tbody>
		  <input type='hidden' name='IDX' value='$_GET[IDX]'>
		  <input type='hidden' name='tahun' value='$_GET[tahun]'>
		  <input type='hidden' name='prodi' value='$_GET[prodi]'>
		  <tr><th  scope='row' width='200px'>Judul Penelitian</th> <td><input type='text' class='form-control' name='Judul' value='$e[Judul]'></td></tr>
		  <tr><th  scope='row'>Tempat</th> <td><input type='text' class='form-control' name='TempatPenelitian' value='$e[TempatPenelitian]'></td></tr>
		  <tr><th scope='row'>URL</th>    <td><input type='text' class='form-control' name='URLX' value='$e[URLX]'></td></tr>
		  </tbody>
		  </table>
		    <div class='box-footer'>
			<button type='submit' name='update' class='btn btn-info'>Update</button>
			<a href='index.php?ndelox=ta/pjudultaadm&tahun=$_GET[tahun]&prodi=$_GET[prodi]&program=$_GET[program]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
			
		  </div>
		</div>
	  </div>
	
	  </form>
	</div>";
}
?>