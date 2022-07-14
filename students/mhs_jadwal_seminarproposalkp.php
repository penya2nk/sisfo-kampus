<div class="col-xs-12">
<div class='box box-info'>
<select name='program' class='combobox form-control' >
<?php 
	echo "<option value=''> Pencarian Data Mahasiswa </option>";
	$program = mysqli_query($koneksi, "SELECT MhswID,Nama,ProgramID,ProdiID,Handphone FROM mhsw where ProdiID='".strfilter($_GET[prodi])."' and StatusMhswID='A' order by MhswID asc");
	while ($k = mysqli_fetch_array($program)){
	 if ($_GET[MhswID]==$k[MhswID]){
		echo "<option value='$k[MhswID]' selected>$k[MhswID] - $k[Nama] - $k[ProgramID] - $k[Handphone] </option>";
	  }else{
		echo "<option value='$k[MhswID]'>$k[MhswID] - $k[Nama] - $k[ProgramID] - $k[Handphone]</option>";
	  }
	}
?>
</select> 
<div class="box-header">
     
<h3 class="box-title">
<?php 
if (isset($_GET[tahun])){ 
 $hari=mysqli_fetch_array(mysqli_query($koneksi, "SELECT CURDATE() AS sekarang, DATE_ADD(CURDATE(), INTERVAL 1 DAY) AS 'BESOK',DATE_ADD(CURDATE(), INTERVAL 2 DAY) AS 'LUSA',DATE_SUB(CURDATE(), INTERVAL 1 DAY) AS 'KEMAREN'"));
 echo "<b style='color:green;font-size:20px'>Jadwal Seminar Proposal Kerja Praktek</b> | <a  href='print_reportxls/nilaiprokpxls.php?tahun=$_GET[tahun]&prodi=$_GET[prodi]' target='_BLANK'>Exp XLS</a>";
 echo" | <a href='index.php?view=jadwalkp&act=kem&prodi=$_GET[prodi]&tahun=$_GET[tahun]&kemx=$hari[KEMAREN]'>Kemarin<a> ";	
 echo" | <a href='index.php?view=jadwalkp&act=tod&prodi=$_GET[prodi]&tahun=$_GET[tahun]&kemx=$hari[KEMAREN]'>Hari Ini<a> ";	
 echo" | <a href='index.php?view=jadwalkp&act=bes&prodi=$_GET[prodi]&tahun=$_GET[tahun]&besx=$hari[BESOK]'>Besok<a> ";
 echo" | <a href='index.php?view=jadwalkp&act=lus&prodi=$_GET[prodi]&tahun=$_GET[tahun]&lusx=$hari[LUSA]'>Lusa<a> |"; 
}else{ 
 echo "<b style='color:green;font-size:20px'>Jadwal Seminar Proposal Kerja Praktek</b> ".date('Y'); 
} ?>
</h3>

<?php 
 if ($_GET[prodi] == '' OR $_GET[tahun] == '' ){ ?>
     <?php echo"Upps Mas Ir!";?>
 <?php
 }else{
?>	     
	<a class='pull-right btn btn-primary btn-sm' href='index.php?view=jadwalkp&act=tambahjadwalkp&tahun=<?php echo $_GET[tahun]; ?>&prodi=<?php echo "$_GET[prodi]"; ?>'>Tambahkan Jadwal KP</a>   
    <a class='pull-right btn btn-primary btn-sm' href='index.php?view=jadwalkpnilai&tahun=<?php echo $_GET[tahun]; ?>&prodi=<?php echo "$_GET[prodi]"; ?>'>NILAI</a>
<?php } ?>
                  
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='view' value='jadwalkp'>
<select name='tahun' style='padding:4px'>
<?php 
	echo "<option value=''>- Pilih Tahun Akademik -</option>";
	$tahun = mysqli_query($koneksi, "SELECT distinct(TahunID) FROM tahun  order by TahunID Desc"); //and NA='N'
	while ($k = mysqli_fetch_array($tahun)){
	  if ($_GET[tahun]==$k[TahunID]){
		echo "<option value='$k[TahunID]' selected>$k[TahunID]</option>";
	  }else{
		echo "<option value='$k[TahunID]'>$k[TahunID]</option>";
	  }
	}
?>
</select> 
<select name='prodi' style='padding:4px'>
<?php 
echo "<option value=''>- Pilih Program Studi -</option>";
$prodi = mysqli_query($koneksi, "SELECT * from prodi where ProdiID='SI' or ProdiID='TI'");
while ($k = mysqli_fetch_array($prodi)){
   if ($_GET[prodi]==$k[ProdiID]){
	echo "<option value='$k[ProdiID]' selected>$k[Nama]</option>";
  }else{
	echo "<option value='$k[ProdiID]'>$k[Nama]</option>";
  }
}
?>
</select>
	<input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
  </form>
</div>
</div>
</div>

                
<div class='col-md-12'>
	<div class='box box-info'>
	<div class='box-body'>						
	<div class='col-md-12'>
<?php if ($_GET[act]==''){ ?>
	<div class="table-responsive">
<table id="example" class="table table-bordered table-striped">
<thead>
<tr>
  <th style='width:20px'>No</th>
  <th style='width:100px'>NIM</th>  
  <th style='width:400px'>Nama</th>             
  
  <th>Jadwal Seminar Proposal</th> 
  <th>Waktu</th>                                
  <th width='140px'>Action</th> 
</tr>
</thead>
<tbody>
<?php
if (isset($_GET[tahun])){
  $qrs = mysqli_query($koneksi, "SELECT * from vw_headerkp where TahunID='".strfilter($_GET[tahun])."' and ProdiID='".strfilter($_GET[prodi])."' order by Judul asc");//and ProgramID like '%$_GET[program]%' TglMulaiSidang
  while ($h = mysqli_fetch_array($qrs)){
   $p1 = mysqli_fetch_array(mysqli_query("select Login,Nama from dosen where Login='$h[Penguji1]'"));
   $p2 = mysqli_fetch_array(mysqli_query("select Login,Nama from dosen where Login='$h[Penguji2]'"));
   $p3 = mysqli_fetch_array(mysqli_query("select Login,Nama from dosen where Login='$h[Penguji3]'"));	  
	  $hd++;
  echo"<tr style='background-color:#DFF4FF'>
	 <td colspan='11' height='20'><b>&nbsp;  
	 <a class='btn btn-success btn-xs' title='Edit' href='index.php?view=jadwalkp&act=edit&JadwalID=$h[JadwalID]&program=$h[ProgramID]&prodi=$h[ProdiID]&tahun=$h[TahunID]'><span class='glyphicon glyphicon-edit'></span></a>
												 
	  | <a href='index.php?view=jadwalkp&act=tambahanggota&JadwalID=$h[JadwalID]&KelompokID=$h[KelompokID]&program=$h[ProgramID]&prodi=$h[ProdiID]&tahun=$h[TahunID]'>
	  $hd. KLP: $h[KelompokID] [ $h[Nama] ] -  $h[Judul]</a></b>
	  <br>
	  
	  <div align=right>
					  
	  <a title='Ujian' href='index.php?view=jadwalkp&act=ujianlah&JadwalID=$h[JadwalID]&program=$h[ProgramID]&prodi=$h[ProdiID]&tahun=$h[TahunID]&KampusID=Kamp03'>| Ujian </a>
	  
	  <a target='_BLANK' title='Cetak' href='print_report/print-baujianprokp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&prodi=$h[ProdiID]'>| Cetak Berita Acara</a>
	  
	  <a target='_BLANK' title='Cetak' href='print_report/print-frmnilaiprokp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&prodi=$h[ProdiID]'>| Cetak Form Nilai</a> 				  
	  <a target='_BLANK' title='Cetak' href='print_report/print-kwitansiprokp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&prodi=$h[ProdiID]'>| Cetak Kwitansi</a> |
	  <a title='Hapus' href='index.php?view=jadwalkp&del=$h[JadwalID]&program=$h[ProgramID]&prodi=$h[ProdiID]&tahun=$h[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"> Delete |  </a> Penguji= $p1[Nama],$p2[Nama],$p3[Nama]
	  
	  </div>
	 </td>
	 </tr>";  
	
	$tampil = mysqli_query($koneksi, "SELECT * from vw_jadwalkp where KelompokID='$h[KelompokID]' AND TahunID='".strfilter($_GET[tahun])."' and ProdiID='".strfilter($_GET[prodi])."' ");                      
	$no = 1;
	while($r=mysqli_fetch_array($tampil)){			    
	$TglMulaiSidang 	=tgl_indo($r[TglMulaiSidang]);
	$tanggal = $r[TglMulaiSidang];
	$i+=1;
	$sisa=$i%2;
	if ($sisa == 0)  {
	$warna = '#FF9900';
	}
	else {
	$warna = '#66CC00';
	} 
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
			<td $warna>$r[Nama] <b>[ <a href='index.php?view=smsmhs&JadwalID=$r[JadwalID]&program=$r[ProgramID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&telepon=$r[Handphone]'> $r[Handphone] </a> ]</b></td>              
		   
			<td $warna>$dayList[$day], $TglMulaiSidang</td>
			<td $warna>$r[JamMulai] - $r[JamSelesai]</td></tr>";
			  $no++;
			  }
			  }
	}//hari
		 
	  ?>
		<tbody>
	  </table></div>
	</div><!-- /.box-body -->
	</div>
</div>

<?php 
}