<div class='card'>
<div class='card-header'>
		<h3 class="box-title"><b style='color:green;font-size:20px'>Setting Tahun Akademik</b></h3>              				

		<div class="form-group row">
				<label class="col-md-5 col-form-label text-md-right"><b style='color:purple'>FILTER DATA</b></label>
				<div class="col-md-2">  
					<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
					<input type="hidden" name='ndelox' value='master/tahunakademik'>
					<select name='program' class='form-control form-control-sm' onChange='this.form.submit()'>
					<?php 
					echo "<option value=''>- Pilih Program -</option>";
					$program = mysqli_query($koneksi, "SELECT * FROM program");
					while ($k = mysqli_fetch_array($program)){
						if ($_GET['program']==$k['ProgramID']){
						echo "<option value='$k[ProgramID]' selected>$k[ProgramID] - $k[Nama]</option>";
						}else{
						echo "<option value='$k[ProgramID]'>$k[ProgramID] - $k[Nama] </option>";
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
						echo "<option value='$k[ProdiID]' selected>$k[ProdiID] - $k[Nama]</option>";
						}else{
						echo "<option value='$k[ProdiID]'>$k[ProdiID] - $k[Nama]</option>";
						}
					}
					?>
					</select>
				</div>                

				<div class="col-md-1">
					<input type="submit" class='btn btn-success btn-sm' value='Lihat'>
				</div>     

				<div class="col-md-2">
					<?php 
					if (isset($_GET['program']) AND isset($_GET['prodi'])){ ?>
					<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=master/tahunakademik&act=tambahtahun&program=<?php echo"$_GET[program]";?>&prodi=<?php echo"$_GET[prodi]";?>'>Tambahkan Data</a>
					<?php 
					}
					?>
				</div> 
				</form>
		</div>
</div>
</div>




<?php if ($_GET['act']==''){ ?> 
	<?php 
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
	<div class='card'>
	<div class='card-header'>
	<div class='table-responsive'>
		<table id="example" class="table table-sm table-striped">
		<thead>
			<tr style='background:purple;color:white'>
			<th style='width:5px'>No</th>
			<th style='width:100px'>Tahun</th>
			
			<th>KRS</th>
			<th>KRS Online</th>
			<th>Ubah KRS</th>
			<th>Masa Bayar</th>
			<th>Masa Kuliah</th>
			<th>Masa UTS</th>
			<th>Masa UAS</th>
			<th>Penilaian</th>
			<th>Akhir Masa KSS</th>
			<th>Aktif</th>
			<th style='width:100px'>Action</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		if ($_GET['program']!='' AND $_GET['prodi']!=''){						
			$tampil = mysqli_query($koneksi, "SELECT * FROM tahun where ProgramID='".strfilter($_GET['program'])."' and ProdiID='".strfilter($_GET['prodi'])."' ORDER BY TahunID DESC");
		}else if ($_GET['program']!='' AND $_GET['prodi']==''){
			$tampil = mysqli_query($koneksi, "SELECT * FROM tahun where ProgramID='".strfilter($_GET['program'])."' ORDER BY TahunID DESC");
		}else if ($_GET['program']=='' AND $_GET['prodi']!=''){
			$tampil = mysqli_query($koneksi, "SELECT * FROM tahun where ProdiID='".strfilter($_GET['prodi'])."' ORDER BY TahunID DESC");
		}else{
			$tampil = mysqli_query($koneksi, "SELECT * FROM tahun ORDER BY TahunID DESC");
		}
		$no = 1;
		while($r=mysqli_fetch_array($tampil)){
			if ($r['NA']=='N'){
				$aktif="Ya";
				$c="style=color:green;font-weight:bold";
				} else{
				$aktif="Tidak";
					$c="style=color:black";
				}
		echo "<tr $c>
				<td>$no</td>
				<td>$r[TahunID]</td>
				<td>".date('d-M-Y',strtotime($r['TglKRSMulai']))."<br>".date('d-M-Y',strtotime($r['TglKRSSelesai']))."</td>
				<td>".date('d-M-Y',strtotime($r['TglKRSOnlineMulai']))."<br>".date('d-M-Y',strtotime($r['TglKRSOnlineSelesai']))."</td>
				<td>".date('d-M-Y',strtotime($r['TglUbahKRSMulai']))."<br>".date('d-M-Y',strtotime($r['TglUbahKRSSelesai']))."</td>
				<td>".date('d-M-Y',strtotime($r['TglBayarMulai']))."<br>".date('d-M-Y',strtotime($r['TglBayarSelesai']))."</td>
				<td>".date('d-M-Y',strtotime($r['TglKuliahMulai']))."<br>".date('d-M-Y',strtotime($r['TglKuliahSelesai']))."</td>
				<td>".date('d-M-Y',strtotime($r['TglUTSMulai']))."<br>".date('d-M-Y',strtotime($r['TglUTSSelesai']))."</td>
				<td>".date('d-M-Y',strtotime($r['TglUASMulai']))."<br>".date('d-M-Y',strtotime($r['TglUASSelesai']))."</td>
				<td>".date('d-M-Y',strtotime($r['TglNilai']))."</td>
				<td>".date('d-M-Y',strtotime($r['TglAkhirKSS']))."</td>
				<td>$aktif</td>";
				
		echo "<td><center>
		<a class='btn btn-success btn-xs' title='Edit Data' href='index.php?ndelox=master/tahunakademik&act=edit&tahun=$r[TahunID]&prodi=$r[ProdiID]&program=$r[ProgramID]'><i style='font-size:15px' class='fa fa-edit'></i></a>
		<a class='btn btn-danger btn-xs' title='Delete Data' href='index.php?ndelox=master/tahunakademik&hapus=$r[TahunID]&prodi=$r[ProdiID]&program=$r[ProgramID]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i style='font-size:15px' class='fa fa-trash'></i></a>
		</center></td>";

		echo "</tr>";
			$no++;
			}
			if (isset($_GET['hapus'])){
				mysqli_query($koneksi, "DELETE FROM tahun where TahunID='".strfilter($_GET['hapus'])."'");
				echo "<script>document.location='index.php?ndelox=master/tahunakademik&program=$_GET[program]&prodi=$_GET[prodi]';</script>";
			}

		?>
		</tbody>
		</table>
		</div>
	</div><!-- /.box-body -->
	</div><!-- /.box -->
</div>
<?php 
}


elseif($_GET['act']=='ThnPrcHERYYYYYYYYYYYYYYYYYYYYYY'){	
$tahun = $_GET['tahun'];
$prodi = $_GET['prodi'];
$program  = $_GET['program'];
// hitung jumlah proses
$sj = "select m.MhswID
from mhsw m
left outer join statusmhsw sm on m.StatusMhswID=sm.StatusMhswID
where m.ProdiID='$prodi' and m.ProgramID='$program' and sm.Keluar='N'
and m.KodeID='SISFO' ";
$rj = mysqli_query($koneksi, $sj);
$jml = mysqli_num_rows($rj);
$n = 0;
while ($w = mysqli_fetch_array($rj)) {
$_SESSION['THN'.$prodi.$n] = $w['MhswID'];
$n++;
}
$_SESSION['THN'.$prodi] = $n;
$_SESSION['THN'.$prodi.'POS'] = 0;
echo "<p align=center><b style='color:purple;font-size:20px'>Sistem akan memproses $jml data</b></p>
<p align=center><IFRAME src='tahun.prc.php?lungo=PRC&tahun=$tahun&prodi=$prodi&program=$program' frameborder=0>
</IFRAME></p>";
}


elseif($_GET['act']=='edit'){	
$tg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM tahun where TahunID='".strfilter($_GET['tahun'])."' and ProdiID='".strfilter($_GET['prodi'])."' and ProgramID='".strfilter($_GET['program'])."'"));

if (isset($_POST['update'])){
$thn = substr($_POST['tahun'],0,4);
mysqli_query($koneksi, "update tahun set NA='Y' WHERE left(TahunID,4) NOT IN ('$thn')");
$query = mysqli_query($koneksi, "UPDATE tahun SET 									
							Nama = '".strfilter($_POST['Nama'])."',
							TglKRSMulai			= '".date('Y-m-d', strtotime($_POST['TglKRSMulai']))."',
							TglKRSSelesai 		= '".date('Y-m-d', strtotime($_POST['TglKRSSelesai']))."',
							TglKRSOnlineMulai 	= '".date('Y-m-d', strtotime($_POST['TglKRSOnlineMulai']))."',
							TglKRSOnlineSelesai = '".date('Y-m-d', strtotime($_POST['TglKRSOnlineSelesai']))."',
							TglBayarMulai 		= '".date('Y-m-d', strtotime($_POST['TglKRSOnlineSelesai']))."',
							TglBayarSelesai 	= '".date('Y-m-d', strtotime($_POST['TglBayarSelesai']))."',
							TglKuliahMulai 		= '".date('Y-m-d', strtotime($_POST['TglKuliahMulai']))."',
							TglKuliahSelesai 	= '".date('Y-m-d', strtotime($_POST['TglKuliahSelesai']))."',
							TglUTSMulai 		= '".date('Y-m-d', strtotime($_POST['TglUTSMulai']))."',
							TglUTSSelesai 		= '".date('Y-m-d', strtotime($_POST['TglUTSSelesai']))."',
							TglUASMulai 		= '".date('Y-m-d', strtotime($_POST['TglUASMulai']))."',
							TglUASSelesai 		= '".date('Y-m-d', strtotime($_POST['TglUASSelesai']))."',
							TglNilai 			= '".date('Y-m-d', strtotime($_POST['TglNilai']))."',
							TglEdit 			= '".date('Y-m-d')."',
							LoginEdit 			= '$_SESSION[_Login]',
							Catatan 			= '".strfilter($_POST['bk'])."',
							NA 				    = '".strfilter($_POST['bl'])."' 
							WHERE TahunID		= '".strfilter($_POST['tahun'])."'
							AND ProgramID		= '".strfilter($_POST['program'])."'
							AND ProdiID		= '".strfilter($_POST['prodi'])."'");
if ($query){
echo "<script>document.location='index.php?ndelox=master/tahunakademik&program=$_POST[program]&prodi=$_POST[prodi]&sukses$thn';</script>";
}else{
echo "<script>document.location='index.php?ndelox=master/tahunakademik&program=$_POST[program]&prodi=$_POST[prodi]&gagal';</script>";
}
}

echo "
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
<div class='card'>
<div class='card-header'>
	<div class='box-header with-border'>
	<h3 class='box-title'>Edit Data Tahun Akademik</h3>
</div>
<div class='table-responsive'>
	<table class='table table-condensed table-sm'>
	<tbody>
	<tr><th  scope='row' colspan='2' style='background:purple;color:white'>DATA KALENDER AKADEMIK</th></tr>
	
	<tr><th width='290px' scope='row'>Tahun Akademik</th> <td><input type='text' class='form-control form-control-sm' name='tahun' value='$tg[TahunID]'> </td></tr>
	<tr><th scope='row'>Nama Tahun</th><td><input type='text' class='form-control form-control-sm' name='Nama' value='$tg[Nama]'></td></tr>
	
	<tr><th  scope='row' colspan='2' style='background:purple;color:white'>PROGRAM DAN PROGRAM STUDI</th></tr>
	
	<tr><th width='290px' scope='row'>Program</th> <td><select class='form-control form-control-sm' name='program'> 
			<option value='0' selected>- Pilih Program -</option>"; 
			$program = mysqli_query($koneksi, "SELECT * FROM program");
			while($a = mysqli_fetch_array($program)){
				if ($_GET['program']==$a['ProgramID']){
				echo "<option value='$a[ProgramID]' selected>$a[ProgramID] - $a[Nama]</option>";
				}else{
				echo "<option value='$a[ProgramID]'>$a[ProgramID] -  $a[Nama]</option>";
				}
			}
			echo "</select></td></tr>
	<tr><th scope='row'>Program Studi</th><td><select class='form-control form-control-sm' name='prodi'> 
			<option value='0' selected>- Pilih Program 	Studi -</option>"; 
			$prod = mysqli_query($koneksi, "SELECT * FROM prodi");
			while($p = mysqli_fetch_array($prod)){
				if ($_GET['prodi']==$p['ProdiID']){
				echo "<option value='$p[ProdiID]' selected>$p[ProdiID] - $p[Nama]</option>";
				}else{
				echo "<option value='$p[ProdiID]'>$p[ProdiID] - $p[Nama]</option>";
				}
			}
			echo "</select></td></tr>
	
	<tr><th  scope='row' colspan='2' style='background:purple;color:white'>KRS</th></tr>
	<tr><th scope='row' >Mulai KRS</th><td><input type='text' class='form-control form-control-sm tanggal' name='TglKRSMulai' value='".date('d-m-Y', strtotime($tg['TglKRSMulai']))."'></td></tr>
	<tr><th scope='row' >Selesai KRS</th><td><input type='text' class='form-control form-control-sm tanggal' name='TglKRSSelesai' value='".date('d-m-Y', strtotime($tg['TglKRSSelesai']))."'></td></tr>

	<tr><th  scope='row' colspan='2' style='background:purple;color:white'>KRS Online (Untuk Mahasiswa)</th></tr>
	<tr><th scope='row' >Mulai KRS Online</th><td><input type='text' class='form-control form-control-sm tanggal' name='TglKRSOnlineMulai' value='".date('d-m-Y', strtotime($tg['TglKRSOnlineMulai']))."'> </td></tr>
		<tr><th scope='row' >Selesai KRS Online</th><td>
		<input type='text' class='form-control form-control-sm tanggal' name='TglKRSOnlineSelesai' value='".date('d-m-Y', strtotime($tg['TglKRSOnlineSelesai']))."'></td></tr>
	
	<tr><th  scope='row' colspan='2' style='background:purple;color:white'>MASA PEMBAYARAN</th></tr>
	<tr><th scope='row' >Mulai Pembayaran</th><td>
	<input type='text' class='form-control form-control-sm tanggal' name='TglBayarMulai' value='".date('d-m-Y', strtotime($tg['TglBayarMulai']))."'>";    
		
	
	echo "</td></tr>
		<tr><th scope='row' >Selesai Pembayaran</th><td>
		
		<input type='text' class='form-control form-control-sm tanggal' name='TglBayarSelesai' value='".date('d-m-Y', strtotime($tg['TglBayarSelesai']))."'>";    
	
	echo "</td></tr>
	
	<tr><th  scope='row' colspan='2' style='background:purple;color:white'>TGL KULIAH</th></tr>
	<tr><th scope='row' >Mulai Kuliah</th><td>
	<input type='text' class='form-control form-control-sm tanggal' name='TglKuliahMulai' value='".date('d-m-Y', strtotime($tg['TglKuliahMulai']))."'>";    
	
	echo "</td></tr>
		<tr><th scope='row' >Selesai Kuliah</th><td>
		<input type='text' class='form-control form-control-sm tanggal' name='TglKuliahSelesai' value='".date('d-m-Y', strtotime($tg['TglKuliahSelesai']))."'>";    

	
	echo "</td></tr>
	
	<tr><th scope='row' >Akhir Nilai</th><td>
	<input type='text' class='form-control form-control-sm tanggal' name='TglNilai' value='".date('d-m-Y', strtotime($tg['TglNilai']))."'>";    

	
	echo "</td></tr>
	
	<tr><th scope='row'>Keterangan</th>           <td><textarea rows='4' class='form-control' name='bk'>$tg[Catatan]</textarea></td></tr>
	<tr><th scope='row'>Aktif?</th><td>";
		if ($tg['NA']=='N'){
			echo "<input type='radio' name='bl' value='N' checked> Ya
					<input type='radio' name='bl' value='Y'> Tidak";
		}else{
			echo "<input type='radio' name='bl' value='N'> Ya 
					<input type='radio' name='bl' value='Y' checked> Tidak";
		}
	echo "</td></tr>
	
	</tbody>
	</table>
			<div class='box-footer'>
	<button type='submit' name='update' class='btn btn-info'>Update</button>
	<a href='index.php?ndelox=master/tahunakademik&program=$_GET[program]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>				
	</div>
</div>
</div>
</div>
</form>
";
}elseif($_GET['act']=='tambahtahun'){
if (isset($_POST['tambah'])){	
$query = mysqli_query($koneksi, "INSERT INTO tahun
					(TahunID,
					KodeID,
					ProdiID,
					ProgramID,
					Nama,
					TglKRSMulai,
					TglKRSSelesai,
					TglKRSOnlineMulai,
					TglKRSOnlineSelesai,
					TglUbahKRSMulai,
					TglUbahKRSSelesai,
					TglCetakKSS1,
					TglCetakKSS2,
					TglCuti,
					TglMundur,
					TglBayarMulai,
					TglBayarSelesai,
					TglAutodebetSelesai,
					TglAutodebetSelesai2,
					TglKembaliUangKuliah,
					TglKuliahMulai,
					TglKuliahSelesai,
					TglUTSMulai,
					TglUTSSelesai,
					TglUASMulai,
					TglUASSelesai,
					TglNilai,
					TglAkhirKSS,
					HanyaAngkatan,
					ProsesBuka,
					ProsesIPK,
					ProsesTutup,
					TglBuat,
					LoginBuat,
					Catatan,
					NA) 
				VALUES('".strfilter($_POST['TahunID'])."',
						'SISFO',
						'".strfilter($_POST['prodi'])."',
						'".strfilter($_POST['program'])."',
						'".strfilter($_POST['Nama'])."',
						'".date('Y-m-d', strtotime($_POST['TglKRSMulai']))."',
						'".date('Y-m-d', strtotime($_POST['TglKRSSelesai']))."',
						'".date('Y-m-d', strtotime($_POST['TglKRSOnlineMulai']))."',
						'".date('Y-m-d', strtotime($_POST['TglKRSOnlineSelesai']))."',
						'".date('Y-m-d', strtotime($_POST['TglUbahKRSMulai']))."',
						'".date('Y-m-d', strtotime($_POST['TglUbahKRSSelesai']))."',
						'".date('Y-m-d', strtotime($_POST['TglCetakKSS1']))."',
						'".date('Y-m-d', strtotime($_POST['TglCetakKSS2']))."',
						'".date('Y-m-d', strtotime($_POST['TglCuti']))."',
						'".date('Y-m-d', strtotime($_POST['TglMundur']))."',
						'".date('Y-m-d', strtotime($_POST['TglBayarMulai']))."',
						'".date('Y-m-d', strtotime($_POST['TglBayarSelesai']))."',
						'".date('Y-m-d', strtotime($_POST['TglAutoDebetSelesai']))."',
						'".date('Y-m-d', strtotime($_POST['TglAutoDebetSelesai2']))."',
						'".date('Y-m-d', strtotime($_POST['TglKembaliUangKuliah']))."',
						'".date('Y-m-d', strtotime($_POST['TglKuliahMulai']))."',
						'".date('Y-m-d', strtotime($_POST['TglKuliahSelesai']))."',
						'".date('Y-m-d', strtotime($_POST['TglUTSMulai']))."',
						'".date('Y-m-d', strtotime($_POST['TglUTSSelesai']))."',
						'".date('Y-m-d', strtotime($_POST['TglUASMulai']))."',
						'".date('Y-m-d', strtotime($_POST['TglUASSelesai']))."',
						'".date('Y-m-d', strtotime($_POST['TglNilai']))."',
						'".date('Y-m-d', strtotime($_POST['TglAkhirKSS']))."',
						'".strfilter($_POST['HanyaAngkatan'])."',
						'".strfilter($_POST['ProsesBuka'])."',
						'".strfilter($_POST['ProsesIPK'])."',
						'".strfilter($_POST['ProsesTutup'])."',
						'".date('Y-m-d H:i:s')."',
						'$_SESSION[_Login]',
						'".strfilter($_POST['Catatan'])."',
						'".strfilter($_POST['NA'])."')");
if ($query){
echo "<script>document.location='index.php?ndelox=master/tahunakademik&program=$_POST[program]&prodi=$_POST[prodi]&sukses';</script>";
}else{
echo "<script>document.location='index.php?ndelox=master/tahunakademik&program=$_POST[program]&prodi=$_POST[prodi]&gagal';</script>";
}
}

echo "
	<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
		<input type='hidden' name='program' value='$_GET[program]'>
		<input type='hidden' name='prodi' value='$_GET[prodi]'>
		<div class='card'>
		<div class='card-header'>
		<div class='box-header with-border'>
		<h3 class='box-title'>Tambah Data Tahun Akademik</h3>
	</div>
		<div class='table-responsive'>
		<table class='table table-condensed table-sm'>
		<tbody>
		<tr><th  scope='row' colspan='2' style='background:purple;color:white'>DATA KALENDER AKADEMIK</th></tr>
		<tr><th width='290px' scope='row'>Tahun Akademik</th> <td><input type='text' class='form-control form-control-sm' name='TahunID'> </td></tr>
		<tr><th scope='row'>Nama Tahun</th>           <td><input type='text' class='form-control form-control-sm' name='ae'></td></tr>
		<tr><th scope='row'>Tanggal</th>           <td>
		<input type='hidden' class='form-control form-control-sm tanggal' name='x' value='".date('d-m-Y', strtotime($tg['ss']))."'>
		</td></tr>
			<tr><th  scope='row' colspan='2' style='background:purple;color:white'>KRS</th></tr>
			<tr><th scope='row'>Mulai KRS</th><td>
			<input type='text' class='form-control form-control-sm tanggal' name='TglKRSMulai' value='".date('d-m-Y')."'>";        
						
			
		echo "</td></tr>
			<tr><th  scope='row'>Selesai KRS </th><td><input type='text' class='form-control form-control-sm tanggal' name='TglKRSSelesai' value='".date('d-m-Y')."'>";  
			
		echo "</td></tr>
		<tr><th  scope='row' colspan='2' style='background:purple;color:white'>KRS Online (Untuk Mahasiswa)</th></tr>
			<tr><th scope='row'>Mulai KRS Online</th><td>
			<input type='text' class='form-control form-control-sm tanggal' name='TglKRSOnlineMulai' value='".date('d-m-Y')."'>";       
			
		echo "</td></tr>
		<tr><th  scope='row'>Selesai KRS Online</th><td>
		<input type='text' class='form-control form-control-sm tanggal' name='TglKRSOnlineSelesai' value='".date('d-m-Y')."'>";  
			
		echo "</td></tr>
		<tr><th  scope='row' colspan='2' style='background:purple;color:white'>MASA PEMBAYARAN</th></tr>
			<tr><th scope='row'>Mulai Pembayaran</th><td>
			<input type='text' class='form-control form-control-sm tanggal' name='TglBayarMulai' value='".date('d-m-Y')."'>";         
			
		echo "</td></tr>
		<tr><th  scope='row'>Selesai Pembayaran</th><td>
		<input type='text' class='form-control form-control-sm tanggal' name='TglBayarSelesai' value='".date('d-m-Y')."'>";  
			
		echo "</td></tr>
			<tr><th  scope='row' colspan='2' style='background:purple;color:white'>TANGGAL KULIAH</th></tr>
		<tr><th scope='row'>Mulai Kuliah</th><td>
		<input type='text' class='form-control form-control-sm tanggal' name='TglKuliahMulai' value='".date('d-m-Y')."'>";      						
				echo "</td>
						</tr>
						<tr><th  scope='row'>Selesai Kuliah</th><td>
						<input type='text' class='form-control form-control-sm tanggal' name='TglKuliahSelesai' value='".date('d-m-Y')."'>";  
			
		echo "</td></tr>
		
		<tr><th  scope='row' colspan='2' style='background:purple;color:white'>TGL UJIAN TENGAH SEMESTER</th></tr>
		<tr><th scope='row'>Mulai UTS</th><td>
		<input type='text' class='form-control form-control-sm tanggal' name='TglUTSMulai' value='".date('d-m-Y')."'>";        
			
		echo "</td>
		
		</tr>
		<tr><th  scope='row'>Selesai UTS</th><td>
		<input type='text' class='form-control form-control-sm tanggal' name='TglUTSSelesai' value='".date('d-m-Y')."'>";  
			
		echo "</td></tr>
		
		<tr><th  scope='row' colspan='2' style='background:purple;color:white'>TGL UJIAN AKHIR SEMESTER</th></tr>
		<tr><th scope='row'>Mulai UAS</th><td>
		<input type='text' class='form-control form-control-sm tanggal' name='TglUASMulai' value='".date('d-m-Y')."'>";       
			
		echo "</td></tr>
		
		<tr><th  scope='row'>Selesai UAS</th><td>
		<input type='text' class='form-control form-control-sm tanggal' name='TglUASSelesai' value='".date('d-m-Y')."'>";  				
		echo "</td></tr>
		
		<tr><th  scope='row'>Akhir Penilaian</th><td>
		<input type='text' class='form-control form-control-sm tanggal' name='TglNilai' value='".date('d-m-Y')."'>";  					
		echo "</td></tr>
		
		<tr><th scope='row'>Tidak Aktif?</th> <td><input type='radio' name='bl' value='Y'> Y
										<input type='radio' name='bl' value='N' checked> N
		</td></tr>
<tr><th scope='row'>Deskripsi</th>           <td><textarea  class='ckeditor form-control'  name='Catatan' style='height:260px' required>$s[Catatan]</textarea></td></tr>          
		
		</tbody>
		</table>
		<div class='box-footer'>
		<button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>                 
		<a href='index.php?ndelox=master/tahunakademik&program=$_GET[program]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
		</div>
	</div>
	</div>

	</form>
</div>";
}
?>