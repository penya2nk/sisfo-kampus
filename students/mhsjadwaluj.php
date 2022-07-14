<div class='card'>
<div class='card-header'>
<div class='col-md-12'>
<div class='box box-info'>
<?php 
if (isset($_GET[tahun])){ 
    $prodi=mysqli_fetch_array(mysqli_query("select MhswID,ProdiID,Nama from mhsw WHERE MhswID='$_SESSION[_Login]'"));
    echo "<b>Jadwal UTS dan UAS</b> ";  
} 
?>
</h3>
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='mhsjadwaluj'>
<select name='tahun' style='padding:4px' onChange='this.form.submit()'>
<?php 
	echo "<option value=''>- Pilih Tahun Akademik -</option>";
	$tahun = mysqli_query($koneksi, "SELECT distinct(TahunID) FROM tahun order by TahunID Desc"); //and NA='N'
	while ($k = mysqli_fetch_array($tahun)){
	  if ($_GET[tahun]==$k[TahunID]){
		echo "<option value='$k[TahunID]' selected>$k[TahunID]</option>";
	  }else{
		echo "<option value='$k[TahunID]'>$k[TahunID]</option>";
	  }
	}
?>
</select>
<input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
</form>
</div>
</div>
</div>

                
<?php if ($_GET[act]==''){ ?>
<div class="col-xs-12">  
<div class="box">
<div class="box-header">  
	<div class="table-responsive">
<table id="example" class="table table-bordered table-striped">
<thead>
<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#FF7213;>
  <th style='width:20px'>No</th> 
  <th style='width:85px'>Tgl UTS</th>
  <th style='width:85px'>Tgl UAS</th> 			  
  <th style='width:100px'>Waktu</th>
  <th style='width:80px'>Ruang</th>
 
  <th>Matakuliah</th>
  <th style='width:60px'>Kelas</th>
  <th>SKS</th>
  <th>Dosen</th>	  
</tr>
</thead>
<tbody>
<?php
if (isset($_GET[tahun])){
  $hari = mysqli_query($koneksi, "SELECT * from hari WHERE Nama NOT IN ('Minggu') order by HariID asc");
  while ($h = mysqli_fetch_array($hari)){
  echo"<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#CCCC00;>
	   <td colspan='12' height='20' ><b>&nbsp; $h[Nama]</b></td>
	   </tr>";   
   
  //$JadwalID=mysqli_query("select JadwalID,KRSID from krs WHERE MhswID='$_SESSION[_Login]'");
  //while($j=mysqli_fetch_array($JadwalID)){  
  $tampil = mysqli_query($koneksi, "SELECT JadwalID,NamaMK,NamaDosen,Gelar,SKS,Sesi,NamaKelas,NamaHari,ProgramID,ProdiID,TahunID,NamaKelas,NamaRuang, 
					DATE_FORMAT(UTSTanggal,'%d-%m-%Y') AS TglUTS,DATE_FORMAT(UASTanggal,'%d-%m-%Y') AS TglUAS,TIME_FORMAT(JamMulai, '%H:%i') AS JMulai,
					TIME_FORMAT(JamSelesai, '%H:%i') AS JSelesai 
					FROM vw_jadwal 
					WHERE TahunID='".strfilter($_GET[tahun])."' 
					AND ProdiID='$prodi[ProdiID]'
					AND NamaHari='$h[Nama]'
					
					"); //NOT IN ('A','') AND JadwalID='11049'
		  
$no = 1;
while($r=mysqli_fetch_array($tampil)){
  $jml=mysqli_num_rows(mysqli_query("select * from krs where JadwalID='$r[JadwalID]'"));
  $jmlpres=mysqli_num_rows(mysqli_query("select * from presensi where JadwalID='$r[JadwalID]'"));
echo "<tr >
<td>$no</td>
		<td>$r[TglUTS]</td>
		<td>$r[TglUAS]</td>
		<td>$r[NamaHarixxxxxxxxx]".$r[JMulai]." - ".$r[JSelesai]."</td>
		<td>$r[NamaRuang]</td>                   
		<td>$r[NamaMK]</td>
		<td>$r[NamaKelas]</td>
		<td>$r[SKS]</td>
		<td>$r[NamaDosen], $r[Gelar]</td>";

echo "</tr>";
	  $no++;
	  }
	  }
  //}//Jadwal
  }//hari
	 
  ?>
	<tbody>
  </table></div>
</div><!-- /.box-body -->
<?php 
if ($_GET[tahun] == ''){
	echo "<center style='padding:60px; color:red'>Tentukan Tahun akademik Terlebih dahulu...</center>";
}
?>
</div>
</div>

<?php 
}
?>