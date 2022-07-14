<div class="card">
<div class="card-header">

<h3 class="box-title">
<?php 
  if (isset($_GET['tahun'])){ 
     echo "<b style='color:green;font-size:20px'>Jadwal Mengajar Dosen &nbsp;&nbsp;</b> 
	 <a class='btn btn-success btn-xs' href='print_reportxls/jdwkuliahxls.php?tahun=$_GET[tahun]&prodi=$_GET[prodi]'><i class='fa fa-print'></i> Jadwal Kuliah</a> ";
  }else{ 
     echo "<b style='color:green;font-size:20px'>Jadwal Mengajar Dosen</b>";  
  } ?>
</h3>

<div class="form-group row">
<label class="col-md-3 col-form-label text-md-right"><b style='color:purple'>JADWAL DOSEN</b></label>
<div class="col-md-2">
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='dep/jadwaldsnx'>


<select name='DosenID' class='form-control form-control-sm'>
	<?php 
		echo "<option value=''>- Pilih Dosen -</option>";
		$dsn = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by Nama asc");
		while ($k = mysqli_fetch_array($dsn)){
		 if ($_GET['DosenID']==$k['Login']){
			echo "<option value='$k[Login]' selected>$k[Nama], $k[Gelar]</option>";
		  }else{
			echo "<option value='$k[Login]'>$k[Nama], $k[Gelar] </option>";
		  }
		}
	?>
</select>
</div>

<div class="col-md-2">
	<select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
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

<div class="col-md-2">
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
	  <div class="card">
<div class="card-header">
      <div class="table-responsive">
        <table id="example" class="table table-sm table-striped">
          <thead>
            <tr style='text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;'>
              <th style='width:20px'>No</th>             
              <th style='width:100px'>Waktu</th>
              <th style='width:80px'>Ruang</th>
              <th>Kode MK</th>
              <th>Matakuliah</th>
              <th style='width:80px'>Kelas</th>
              <th>SKS</th>
              <th>Dosen</th>
               <th>JmlMhs</th> 
			   <th style='text-align:right'>Pres</th>
              <th width='240px'>Action</th> 
            </tr>
          </thead>
          <tbody>
        <?php
          if (isset($_GET['tahun'])){
			$no = 0;
			  $hari = mysqli_query($koneksi, "SELECT * from hari WHERE Nama NOT IN ('Minggu','') order by HariID asc");
			  while ($h = mysqli_fetch_array($hari)){
				$no++;
			  echo"<tr style='text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#8c798c;'>
				 <td colspan='11' height='4' ><b>&nbsp; $h[Nama]</b></td>
				 </tr>";  
          if ($_GET['prodi']==''){      
              $tampil = mysqli_query($koneksi, "SELECT * from vw_jadwal where NamaHari='$h[Nama]' AND TahunID='".strfilter($_GET['tahun'])."' and Login='".strfilter($_GET['DosenID'])."' AND MKKode NOT IN ('PBSI7403','PBSI8605') ");
          }else{
		      $tampil = mysqli_query($koneksi, "SELECT * from vw_jadwal where NamaHari='$h[Nama]' AND TahunID='".strfilter($_GET['tahun'])."' and Login='".strfilter($_GET['DosenID'])."' AND MKKode NOT IN ('PBSI7403','PBSI8605') and ProdiID='".strfilter($_GET['prodi'])."'");
		  }            
		 
          while($r=mysqli_fetch_array($tampil)){
			 
			  $jml=mysql_num_rows(mysqli_query($koneksi, "select * from krs where JadwalID='$r[JadwalID]'"));
			  $jmlpres=mysql_num_rows(mysqli_query($koneksi, "select * from presensi where JadwalID='$r[JadwalID]'"));
          echo "<tr><td>$no</td>
                  
                    <td>".substr($r['JamMulai'],0,5)." - ".substr($r['JamSelesai'],0,5)."</td>
                    <td>$r[RuangID]</td>
                    <td>$r[MKKode]</td>
                    <td><a href='?ndelox=dep/jadwalx&act=viewinfo&JadwalID=$r[JadwalID]&program=$r[ProgramID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&NamaMK=$r[NamaMK]&NamaDosen=$r[NamaDosen]&SKS=$r[SKS]&Sesi=$r[Sesi]' target='_BLANK'>$r[NamaMK]</a></td>
                    <td>$r[NamaKelas]</td>
                    <td>$r[SKS]</td>
                    <td>$r[NamaDosen] <a target='_BLANK'  href='print_report/print-absensidosen.php?JadwalID=$r[JadwalID]&NamaKelas=$r[NamaKelas]&tahun=$r[TahunID]&prodi=$r[ProdiID]'> Abs</a></td>
					<td align=right>
          <a target='_BLANK' href='print_report/print-absensitmp.php?JadwalID=$r[JadwalID]&NamaKelas=$r[NamaKelas]&tahun=$r[TahunID]&prodi=$r[ProdiID]'>$jml </a>
          </td>
		  <td style='text-align:right'>$jmlpres</td>
		  ";
                            
echo "<td style='width:300px !important'><center><a class='btn btn-success btn-sm' title='Edit Jadwal' href='index.php?ndelox=dep/jadwalx&act=edit&JadwalID=$r[JadwalID]&program=$r[ProgramID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'><i class='fa fa-edit'></i></a>
<a target='_BLANK' class='btn btn-success btn-sm' href='print_report/print-absensi.php?JadwalID=$r[JadwalID]&NamaKelas=$r[NamaKelas]&tahun=$r[TahunID]&prodi=$r[ProdiID]'><i class='fa fa-print'></i> DHK</a>

<a target='_BLANK' class='btn btn-success btn-sm' href='print_report/print-frmnilai.php?JadwalID=$r[JadwalID]&NamaKelas=$r[NamaKelas]&tahun=$r[TahunID]&prodi=$r[ProdiID]'><i class='fa fa-print'></i> Form</a>

<a target='_BLANK' class='btn btn-success btn-sm' href='print_reportxls/frmnilaixls.php?JadwalID=$r[JadwalID]&NamaKelas=$r[NamaKelas]&tahun=$r[TahunID]&prodi=$r[ProdiID]'><i class='fa fa-print'></i> XLS</a>


	  </center></td>";

echo "</tr>";
/*<a class='btn btn-danger btn-xs' title='Hapus Jadwal' href='index.php?ndelox=jadwal&hapus=$r[JadwalID]&program=$r[ProgramID]&prodi=$r[ProdiID]&tahun=$r[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a> */


	
	  }
	  }
}//hari
	  if (isset($_GET['hapus'])){
		mysqli_query($koneksi, "DELETE FROM jadwal where JadwalID='".strfilter($_GET['hapus'])."'");
		echo "<script>document.location='index.php?ndelox=jadwal&program=$_GET[program]&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
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

?>