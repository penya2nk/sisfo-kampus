<div class='card'>
<div class='card-header'>
<div class='col-md-12'>
<div class='box box-info'>
<h3 class="box-title"><b style='color:green;font-size:20px'>Jadwal Kuliah</b></h3>
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='mhsjadwalkuliah'>
<select name='tahun' style='padding:4px' onChange='this.form.submit()'>

<?php 
echo "<option value=''>- Pilih Tahun Akademik -</option>";
$tahun = mysqli_query($koneksi, "SELECT distinct(TahunID),NA,ProdiID FROM tahun where ProdiID='SI' order by TahunID Desc"); //and NA='N'
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

<div class='card'>
<div class='card-header'>
<div class='col-md-12'>
<div class='box box-info'>
		    	<div class="table-responsive">
<table id="example" class="table table-bordered table-striped">
  <thead>
	<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#FF7213;width:100%>
	  <th style='width:20px'>No</th>	  
	  <th style='width:100px'>Waktu</th>
	  <th style='width:80px'>Ruang</th>
	  <th>Kode MK</th>
	  <th>Matakuliah</th>
	  <th style='width:80px'>Kelas</th>
	  <th>SKS</th>
	  <th>Dosen</th>	                    	  
	</tr>
  </thead>
  <tbody>
<?php
if (isset($_GET[tahun]) ){
  $prodimhs =mysqli_fetch_array(mysqli_query("select MhswID,Nama,ProdiID from mhsw where MhswID='$_SESSION[_Login]'")); 	
  $hari = mysqli_query($koneksi, "SELECT * from hari WHERE Nama NOT IN ('Minggu','') order by HariID asc");
  while ($h = mysqli_fetch_array($hari)){
  echo"<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#ACAC00;width:100%;>
	 <td colspan='11' height='20' ><b>&nbsp; $h[Nama]</b></td>
	 </tr>";  		
     $tampil = mysqli_query("select * from vw_krsdetailok where MhswID='$_SESSION[_Login]' AND NamaHari='$h[Nama]'  AND TahunID='".strfilter($_GET[tahun])."'");		 //	AND MhswID='$_SESSION[_Login]'   
  $no = 1;
  while($r=mysqli_fetch_array($tampil)){
	  $tsks += $r[SKS];
	  //$jml=mysqli_num_rows(mysqli_query("select * from krsdetail where MhswID='$_SESSION[_Login]'"));
  echo "<tr><td>$no</td>		  
			<td>".substr($r[JamMulai],0,5)." - ".substr($r[JamSelesai],0,5)."</td>
			<td>$r[NamaRuang]</td>
			<td>$r[MKKode]</td>
			<td>$r[NamaMK]</td>
			<td>$r[NamaKelas]</td>
			<td>$r[SKS]</td>
			<td>$r[NamaDosen], $r[Gelar]</td>";
echo "</tr>";
		  $no++;
		  }
		  }
		  }//hari    NamaHari='$h[Nama]'  AND TahunID='$_GET[tahun]'                
		 
		  ?>
		 <tr >
		<td colspan=3><b>Total SKS</b> &nbsp;&nbsp;&nbsp;&nbsp;<b></b> </td><td style=text-align:center><b><?php echo"$tsks";?> SKS</b> </td><td></td><td style=text-align:center><b></b></td>
	</tr>
		<tbody>
	  </table></div>
	</div><!-- /.box-body -->	   
	</div>
</div>
