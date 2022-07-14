<div class="card">
<div class="card-header">

	<div class="form-group row">
		<label class="col-md-7 col-form-label text-md-right"><b style='color:purple'>LAPORAN BEBAN KERJA DOSEN <a  href='print_reportxls/lapbkddosenxls.php?tahun=$_GET[tahun]&prodi=$_GET[prodi]' target='_BLANK'>Export Ke Excel</a></b></label>
		<div class="col-md-2">			 
			<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
			<input type="hidden" name='ndelox' value='dep/admlapbkddosen'>
			<select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
			<?php 
				echo "<option value=''>- Pilih Angkatan -</option>";
				$tahun = mysqli_query($koneksi, "SELECT * from tahun order by TahunID Desc"); //and NA='N'
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
					<input type="submit" class='btn btn-success btn-sm' value='Lihat'>
					</form>
		</div>				
	
	</div>

</div>
</div>


<?php
if ($_GET['act']==''){ 
$prodi=mysqli_fetch_array(mysqli_query($koneksi, "select ProdiID,Nama from prodi where ProdiID='".strfilter($_GET['prodi'])."'"));
?> 
            <div class="card">
				<div class="card-header">
				<div class="col-md-12">
				<div class='box box-info'>
	  <h3 class="box-title"><b style='color:green;font-size:20px'>Laporan Beban Kerja Dosen Prodi <?php echo"<b style=color:#FF8306;>$prodi[Nama]</b>";?> Tahun <?php echo"<b style=color:#FF8306;>$_GET[tahun]</b>";?></b></h3>	  
	</div><!-- /.box-header -->
	<div class="box-body">
	    <div class='table-responsive'>
	  <table id="example" class="table table-sm table-striped">
		<thead>
		  <tr style='background:purple;color:white'>
			<th style='width:40px;vertical-align:middle'>No</th>
			<th style='width:80px;vertical-align:middle'>NIDN/DosenID</th>
			<th style='width:300px;vertical-align:middle'>Nama Dosen</th>
			<th style='text-align:center;width:40px;vertical-align:middle'>Mengajar (SKS)</th>
			<th style='text-align:center;width:40px'>Membimbing <br>KP</th>
			<th style='text-align:center;width:40px'>Membimbing <br>Skripsi</th>	
			<th style='text-align:center;width:40px'>Menguji <br>Skripsi </th>
			<th style='text-align:center;width:40px'>Menguji <br>KP </th>
		  </tr>

		</thead>
		<tbody>
	  <?php 
		$angkatan = strfilter(substr($_GET['tahun'],2,2));//20191
		$prodix = ".".$_GET['prodi']."."; //versi baru tidak tanda titik 
		$prodix = $_GET['prodi'];
		$tampil = mysqli_query($koneksi, "SELECT * from dosen WHERE NA='N'");//  AND Noreg NOT IN ('-')		AND keuangan_bayar.TahunID='".strfilter($_GET[tahun])."' 
		$no = 1;
		
		while($r=mysqli_fetch_array($tampil)){
        //$jmlmk		=mysqli_num_rows(mysqli_query("select * from krs where MhswID='$r[MhswID]'"));
		$tsks		= mysqli_fetch_array(mysqli_query($koneksi, "select sum(SKS)as tSKS from jadwal 
																	where DosenID='$r[Login]'
																	and TahunID='".strfilter($_GET['tahun'])."'
																	and MKID NOT IN('1150','1154','1085','1081','1208','1209')
																	and ProdiID='".strfilter($prodix)."'"));
		$jmlKP		= mysqli_num_rows(mysqli_query($koneksi, "select * from jadwal_kp 
															  where DosenID='$r[Login]'
														      and ProdiID='".strfilter($_GET['prodi'])."' 
															  and TahunID='".strfilter($_GET['tahun'])."'"));
		
		$jmlMengujiKP1	= mysqli_num_rows(mysqli_query($koneksi, "select * from jadwal_kp 
															  where Penguji1='$r[Login]'
														      and ProdiID='".strfilter($_GET['prodi'])."' 
															  and TahunID='".strfilter($_GET['tahun'])."'"));
															  
		$jmlMengujiKP2	= mysqli_num_rows(mysqli_query($koneksi, "select * from jadwal_kp 
													  where Penguji2='$r[Login]'
												      and ProdiID='".strfilter($_GET['prodi'])."' 
													  and TahunID='".strfilter($_GET['tahun'])."'"));	
													  
		$jmlMengujiKP3	= mysqli_num_rows(mysqli_query($koneksi, "select * from jadwal_kp 
													  where Penguji3='$r[Login]'
												      and ProdiID='".strfilter($_GET['prodi'])."' 
													  and TahunID='".strfilter($_GET['tahun'])."'"));
		$jmlMengujiKPx = $jmlMengujiKP2 + $jmlMengujiKP3;
		//------------------------------------------------------------------------------------------
													  
															  
															  
		$jmlUtama	= mysqli_num_rows(mysqli_query($koneksi, "select * from jadwal_skripsi 
															  where PembimbingSkripsi1='$r[Login]'
														      and ProdiID='".strfilter($_GET['prodi'])."' 
															  and TahunID='".strfilter($_GET['tahun'])."'"));
		$jmlPendamping	= mysqli_num_rows(mysqli_query($koneksi, "select * from jadwal_skripsi 
															  where PembimbingSkripsi2='$r[Login]'
														      and ProdiID='".strfilter($_GET['prodi'])."' 
															  and TahunID='".strfilter($_GET['tahun'])."'"));
		$jmlBimbing = $jmlUtama + $jmlPendamping;													  											
		$jmlMenguji1	= mysqli_num_rows(mysqli_query($koneksi, "select * from jadwal_skripsi 
															  where PengujiPro1='$r[Login]'
														      and ProdiID='".strfilter($_GET['prodi'])."' 
															  and TahunID='".strfilter($_GET['tahun'])."'"));
															  
		$jmlMenguji2	= mysqli_num_rows(mysqli_query($koneksi, "select * from jadwal_skripsi 
													  where PengujiPro2='$r[Login]'
												      and ProdiID='".strfilter($_GET['prodi'])."' 
													  and TahunID='".strfilter($_GET['tahun'])."'"));	
													  
		$jmlMenguji3	= mysqli_num_rows(mysqli_query($koneksi, "select * from jadwal_skripsi 
													  where PengujiPro3='$r[Login]'
												      and ProdiID='".strfilter($_GET['prodi'])."' 
													  and TahunID='".strfilter($_GET['tahun'])."'"));											  
		
		$jmlMengujix = $jmlMenguji1 + $jmlMenguji2 + $jmlMenguji3;													  
															  
		$Namax 	    = strtolower($r['Nama']);
		$Nama 		= ucwords($Namax);
		echo "<tr >
		          <td>$no</td>
				  <td><a href='?ndelox=jadwaldsn&tahun=$_GET[tahun]&DosenID=$r[Login]&prodi=$_GET[prodi]' target=_BLANK>$r[Login]</a></td>
				  <td>$Nama, $r[Gelar]</td>
				  <td align=center>".number_format($tsks['tSKS'])."</td>
				  <td align=center>$jmlKP</td>
				  <td align=center>$jmlBimbing</td>
				  <td align=center>$jmlMenguji3</td>
				  <td align=center>$jmlMengujiKPx</td>
				  
		</tr>";
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


?>

