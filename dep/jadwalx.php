<div class="card">
<div class="card-header">


		<h3 class="box-title">
		<?php 
		if (isset($_GET['tahun'])){ 
			echo "<b style='color:green;font-size:20px'>Jadwal Kuliah &nbsp;&nbsp;</b> 
			<a class='btn btn-success btn-xs' href='index.php?ndelox=dep/labeluts&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><span class='glyphicon glyphicon-print'></span> Label UTS dan UAS</a> ";	 
		}else{ 
			echo "<b style='color:green;font-size:20px'>Jadwal Kuliah</b>";  
		} ?>
		</h3>


		<div class="form-group row">
				<label class="col-md-6 col-form-label text-md-right"><b style='color:purple'>JADWAL KULIAH</b></label>
				<div class="col-md-2">
				<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
					<input type="hidden" name='ndelox' value='dep/jadwalx'>
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
						echo "<option value='' >- Pilih Program Studi -</option>";
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
				
				<div class="col-md-1">				
				<a style='padding:4px;margin-left:0px' class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=dep/jadwalx&act=tambahjadwal&tahun=<?php echo $_GET['tahun']; ?>&prodi=<?php echo "$_GET[prodi]"; ?>&program=<?php echo 	"$_GET[program]"; ?>'>Add Jadwal</a>
				</div>
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
<table id="example" class="table table-sm table-bordered table-striped">
          <thead>
            <tr style=text-align:left;font-size:14px;color:white;font-weight:reguler;background-color:purple;>
              <th style='width:20px'>No</th>             
              <th style='width:95px'>Waktu</th>
              <th style='width:70px'>Ruang</th>
              <th>Kode MK</th>
              <th>Matakuliah</th>
              <th style='width:60px'>Kelas</th>
              <th>SKS</th>
              <th>Dosen</th>
              <th>JmlMhs</th> 
			  <th style='text-align:right'>Pres</th>
			  <th style='width:230px'>Aksi</th>
            </tr>
          </thead>
          <tbody>
        <?php
          if (isset($_GET['tahun'])){
			    $no = 0;
			  $prodix = ".".strfilter($_GET['prodi']).".";  
			  $hari = mysqli_query($koneksi, "SELECT * from hari WHERE Nama NOT IN ('Minggu','') order by HariID asc");
			  while ($h = mysqli_fetch_array($hari)){
			  echo"<tr style=text-align:left;font-size:14px;color:white;font-weight:reguler;background-color:#8c798c;>
				   <td colspan='11' height='4' ><b>&nbsp; $h[Nama]</b></td>
				   </tr>";  
                
					  $tampil = mysqli_query($koneksi, "SELECT jadwal.JadwalID,jadwal.TahunID,
					  jadwal.ProdiID,jadwal.ProgramID,
					  jadwal.NamaKelas,jadwal.SKS,
					  jadwal.MKID,jadwal.MKKode,
					  jadwal.DosenID,jadwal.RuangID,jadwal.HariID,
					  jadwal.JamMulai,jadwal.JamSelesai,
					  dosen.Nama as NamaDosen, dosen.Gelar,
					  mk.Nama as NamaMK,
					  ruang.Nama as NamaRuang,
					  hari.Nama as NamaHari
					  from jadwal,dosen,mk,hari,ruang			  
					  where dosen.Login=jadwal.DosenID
					  and mk.MKID=jadwal.MKID
					  and ruang.RuangID=jadwal.RuangID
					  and hari.HariID=jadwal.HariID
					  and jadwal.HariID='$h[HariID]'
					  and jadwal.TahunID='".strfilter($_GET['tahun'])."' 
					  and jadwal.ProdiID='$prodix'"); //".strfilter('$prodix')."
                      
		
          while($r=mysqli_fetch_array($tampil)){ 
		   $no++;
              $jmltmp=mysqli_num_rows(mysqli_query($koneksi, "select * from krstemp where JadwalID='$r[JadwalID]'"));
			  $jml=mysqli_num_rows(mysqli_query($koneksi, "select * from krs where JadwalID='$r[JadwalID]'"));
			  $jmlpres=mysqli_num_rows(mysqli_query($koneksi, "select * from presensi where JadwalID='$r[JadwalID]'"));
          echo "<tr><td>$no</td>
                  
                    <td>".substr($r['JamMulai'],0,5)." - ".substr($r['JamSelesai'],0,5)."</td>
                    <td style='text-align:center'>$r[RuangID]</td>
                    <td>$r[MKKode]</td>
                    <td><a href='?ndelox=dep/jadwalx&act=viewinfo&JadwalID=$r[JadwalID]&program=$r[ProgramID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&NamaMK=$r[NamaMK]&NamaDosen=$r[NamaDosen]&SKS=$r[SKS]&Sesi=$r[Sesi]'>$r[NamaMK]</a></td>
                    <td>$r[NamaKelas]</td>
                    <td>$r[SKS]</td>
                    <td>$r[NamaDosen], $r[Gelar] <a target='_BLANK'  href='print_report/print-absensidosen.php?JadwalID=$r[JadwalID]&NamaKelas=$r[NamaKelas]&tahun=$r[TahunID]&prodi=$r[ProdiID]'> [Abs]</a></td>
					<td align=right>
          <a target='_BLANK' href='print_report/print-absensitmp.php?JadwalID=$r[JadwalID]&NamaKelas=$r[NamaKelas]&tahun=$r[TahunID]&prodi=$r[ProdiID]'>$jml/$jmltmp </a>
          </td>
		  <td style='text-align:right'>$jmlpres</td>";
                            
		echo "<td style='width:250px !important'><center><a class='btn btn-success btn-sm' title='Edit Jadwal' href='index.php?ndelox=dep/jadwalx&act=edit&JadwalID=$r[JadwalID]&program=$r[ProgramID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'><i class='fa fa-edit'></i></a>
		<a target='_BLANK' class='btn btn-success btn-xs' href='print_report/print-absensi.php?JadwalID=$r[JadwalID]&NamaKelas=$r[NamaKelas]&tahun=$r[TahunID]&prodi=$r[ProdiID]'><i class='fa fa-print'></i> DHK</a>

		<a target='_BLANK' class='btn btn-success btn-xs' href='print_report/print-frmnilai.php?JadwalID=$r[JadwalID]&NamaKelas=$r[NamaKelas]&tahun=$r[TahunID]&prodi=$r[ProdiID]'><i class='fa fa-print'></i> Form</a>

		<a target='_BLANK' class='btn btn-success btn-xs' href='print_reportxls/frmnilaixls.php?JadwalID=$r[JadwalID]&NamaKelas=$r[NamaKelas]&tahun=$r[TahunID]&prodi=$r[ProdiID]'><i class='fa fa-print'></i> XLS</a>

		<a class='btn btn-danger btn-sm' title='Hapus Jadwal' href='index.php?ndelox=jadwalx&hapus=$r[JadwalID]&program=$r[ProgramID]&prodi=$r[ProdiID]&tahun=$r[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
		</center></td>";

echo "</tr>";
	 
	  }
	  }
}//hari
	  if (isset($_GET['hapus'])){
		mysqli_query($koneksi, "DELETE FROM jadwal_jangan_dihapus where JadwalID='".strfilter($_GET['hapus'])."'");
		echo "<script>document.location='index.php?ndelox=jadwalx&program=$_GET[program]&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
	  }
  ?>
	<tbody>
  </table>
  </table>
</div><!-- /.box-body -->
<?php 
	if ($_GET['prodi'] == '' OR $_GET['tahun'] == ''){
		echo "<center style='padding:60px; color:red'>Tentukan Tahun akademik dan Program Studi Terlebih dahulu...</center>";
	}
?>
</div>
</div>

<?php 
}elseif($_GET['act']=='tambahjadwal'){
    if (isset($_POST['tambah'])){	
	    $tglnow =date('Y-m-d H:i:s');	
	
	 			
		
		// if ($_POST[an]=='0' OR $_POST[ai]=='0' OR $_POST[av]=='0' OR $_POST[bc]=='0') {
		// 	echo "<script>document.location='index.php?ndelox=jadwalx&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&gagal';</script>";
		// 	exit;
		// 	}
		//and MKID='$_POST[ai]' and DosenID='$_POST[av]' 
		$jamMulai =substr($_POST['ao'],0,2);
		//echo"Data :$_POST[an] $_POST[bc] $jamMulai $_POST[tahun]";
		/*
		$cek = mysqli_num_rows(mysqli_query($koneksi, "select * from jadwalx 
											where HariID='$_POST[an]' 											
											and RuangID='$_POST[bc]' 
											and left(JamMulai,2)='$jamMulai'
											and TahunID='$_POST[tahun]'"));
											if ($cek>0){
												echo"Barangkali Jadwal Berbenturan dengan Jadwal yang sudah ada!";
												exit;
												}
		*/	
		$prodi=strfilter($_POST['prodi']);	
		$arrProgramID = $_POST['ProgramID'];
    	//$ProgramID = (empty($arrProgramID))? '' : '.'.implode('.', $arrProgramID).'.'; //oribro
		$ProgramID = (empty($arrProgramID))? '.REG A.' : '.'.implode('.', $arrProgramID).'.'; //if empty set REG A as default value	
		//echo"$ProgramID";
	    $m = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MKID,Nama,MKKode,SKS from mk where MKID='".strfilter($_POST['ai'])."'"));
		
        $query = mysqli_query("INSERT INTO jadwal(
							JadwalID,
							JadwalPar,
							JadwalSer,
							KodeID,
							TahunID,
							ProdiID,
							ProgramID,
							NamaKelas,
							MKID,
							JenisJadwalID,
							AdaResponsi,
							MKKode,
							Nama,
							HariID,
							JamMulai,
							JamSelesai,
							TglMulai,
							TglSelesai,
							SKSAsli,
							SKS,
							SKSHonor,
							DosenID,
							RencanaKehadiran,
							Kehadiran,
							KehadiranMin,
							JumlahMhsw,
							JumlahMhswKRS,
							Kapasitas,
							RuangID,
							JumlahKelasSerial,
							JumlahPraKRS,
							TugasMandiri,
							Tugas1,
							Tugas2,
							Tugas3,
							Tugas4,
							Tugas5,
							Presensi,
							UTS,
							UAS,
							Final,
							Responsi,
							UTSTanggal,
							UTSJamMulai,
							UTSJamSelesai,
							UTSRuangID,
							UASTanggal,
							UASJamMulai,
							UASJamSelesai,
							UASRuangID,
							Penilaian,
							Gagal,
							CatatanGagal,
							NilaiGagal,
							NoSurat,
							TglSurat,
							TglBuat,
							LoginBuat,
							TglEdit,
							LoginEdit,
							NA) 
					 VALUES('".strfilter($_POST['aa'])."',
							'".strfilter($_POST['ab'])."',
							'".strfilter($_POST['ac'])."',
							'HTP',
							'".strfilter($_GET['tahun'])."',
							'$prodi',
							'".strfilter($ProgramID)."',
							'".strfilter($_POST['ah'])."',
							'".strfilter($_POST['ai'])."',
							'K',
							'N',
							'$m[MKKode]',
							'$m[Nama]',
							'".strfilter($_POST['an'])."',
							'".strfilter($_POST['ao'])."',
							'".strfilter($_POST['ap'])."',
							'".strfilter($_POST['aq'])."',
							'".strfilter($_POST['ar'])."',
							'".strfilter($_POST['as'])."',
							'$m[SKS]',
							'".strfilter($_POST['au'])."',
							'".strfilter($_POST['av'])."',
							'".strfilter($_POST['aw'])."',
							'".strfilter($_POST['ax'])."',
							'".strfilter($_POST['ay'])."',
							'".strfilter($_POST['az'])."',
							'".strfilter($_POST['ba'])."',
							'50',
							'".strfilter($_POST['bc'])."',
							'".strfilter($_POST['bf'])."',
							'".strfilter($_POST['bg'])."',
							'".strfilter($_POST['bh'])."',
							'".strfilter($_POST['bi'])."',
							'".strfilter($_POST['bj'])."',
							'".strfilter($_POST['bk'])."',
							'".strfilter($_POST['bl'])."',
							'".strfilter($_POST['bm'])."',
							'".strfilter($_POST['bn'])."',
							'".strfilter($_POST['bo'])."',
							'".strfilter($_POST['bp'])."',
							'N',
							'".strfilter($_POST['br'])."',
							'".strfilter($_POST['bs'])."',
							'".strfilter($_POST['bt'])."',
							'".strfilter($_POST['bu'])."',
							'".strfilter($_POST['bv'])."',
							'".strfilter($_POST['bw'])."',
							'".strfilter($_POST['bx'])."',
							'".strfilter($_POST['by'])."',
							'".strfilter($_POST['bz'])."',
							'web',
							'N',
							'".strfilter($_POST['cc'])."',
							'".strfilter($_POST['cd'])."',
							'".strfilter($_POST['ce'])."',
							'".strfilter($_POST['cf'])."',
							'$tglnow',
							'".$_SESSION['_Login']."',
							'".strfilter($_POST['ci'])."',
							'".strfilter($_POST['cj'])."',
							'N')");
		
        if ($query){
            //echo"Berhasil";
			echo "<script>document.location='index.php?ndelox=dep/jadwalx&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&sukses';</script>";
        }else{
            echo "<script>document.location='index.php?ndelox=dep/jadwalx&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&gagal';</script>";
        }
		
    }

    //$ex = explode('.',$_GET[kelas]);
    //$tingkat = $ex[0];
$kurAktif = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM kurikulum where ProdiID='".strfilter($_GET['prodi'])."' and NA='N' order by KurikulumID Desc limit 1"));
    echo "
	            <div class='card'>
				<div class='card-header'>
				<div class='col-md-12'>
				<div class='box box-info'>
	
              
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Data Jadwal Kuliah</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <input type='hidden' name='tahun' value='$_GET[tahun]'>
				<input type='hidden' name='program' value='$_GET[program]'>
				<input type='hidden' name='prodi' value='$_GET[prodi]'>
				<div class='col-md-12'>
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
                    

					
					<tr><th style='width:260px' scope='row'>Berlaku Untuk Program</th>   <td>"; 
					$program = mysqli_query($koneksi, "SELECT * FROM program");
						while($a = mysqli_fetch_array($program)){	
					echo"<span style='display:block;'><input type=checkbox value='$a[ProgramID]' name=ProgramID[]> $a[Nama] </span>";
						}
					echo"</td></tr>	

					<tr><th scope='row'>Program Studi</th>   <td><select class='form-control' name='af'> 
						<option value='0' selected>- Pilih Program -</option>"; 
						$program = mysqli_query($koneksi, "SELECT * FROM prodi");
						while($a = mysqli_fetch_array($program)){
						  if ($_GET['prodi']==$a['ProdiID']){
							echo "<option value='$a[ProdiID]' selected>$a[Nama]</option>";
						  }else{
							echo "<option value='$a[ProdiID]'>$a[Nama]</option>";
						  }
						}
						echo "</select>
                    </td></tr>
					<tr><th scope='row'>Hari</th>   <td><select class='form-control chzn-select' name='an'> 
					<option value='0' selected></option>";                                               
						$hari = mysqli_query($koneksi, "SELECT * FROM hari");
						while($a = mysqli_fetch_array($hari)){
						  echo "<option value='$a[HariID]'>$a[Nama]</option>";
						}
						echo "</select>
					</td></tr>							 
					
					<tr><th scope='row'>Jam Mulai</th>  
					<td><input type='text' class='form-control' name='ao' style='width:100px' placeholder='hh:ii:ss' value='".date('H:i:s')."'></td>
					</tr>
                    
					<tr><th scope='row'>Jam Selesai</th>
					<td><input type='text' class='form-control' name='ap' style='width:100px' placeholder='hh:ii:ss' value='".date('H:i:s')."'>
					</td>	
					</tr>
										
                    <tr><th scope='row'>Mata Kuliah</th>   <td><select class='form-control select2' name='ai'> 
						<option value='$a[MKID]' selected>- Pilih Mata Kuliah -</option>"; 
						$mt = mysqli_query($koneksi, "SELECT MKID,MKKode,Nama,SKS FROM mk where ProdiID='$_GET[prodi]' AND NA='N' and KurikulumID='$kurAktif[KurikulumID]' order by  Nama ASC"); //mid(MKKode,0,3)					
						while($a = mysqli_fetch_array($mt)){
						  echo "<option value='$a[MKID]'>$a[Nama] - [ $a[MKKode] ]</option>";
						}
						echo "</select>
                    </td></tr>
					<tr><th scope='row'>Nama Kelas</th>  <td><input type='text' class='form-control' name='ah'></td></tr>
                     <tr><th scope='row'>Ruang Kuliah</th>   <td><select class='form-control select2' name='bc'> 
						<option value='$a[RuangID]' selected>- Pilih Ruangan -</option>"; 
						$ruangan = mysqli_query($koneksi, "SELECT * FROM ruang where KampusID='Kamp03'");
						while($a = mysqli_fetch_array($ruangan)){
						  echo "<option value='$a[RuangID]'>$a[Nama] - [ $a[RuangID] ]</option>";
						}
						echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Dosen Pengampu</th>   <td><select class='form-control select2' name='av'> 
						<option value='$a[Login]' selected>- Pilih Dosen -</option>"; 
						$dosen = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif order by  Nama ASC");
						while($a = mysqli_fetch_array($dosen)){
						  echo "<option value='$a[Login]'> $a[Nama] - [ $a[Login] ] </option>";
						}
						echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Rencana Tatap Muka</th>  <td><input type='text' style='width:100px' class='form-control' name='aw'></td></tr>
                    <tr><th scope='row'>Minimal Kehadiran Mahasiswa</th>  <td><input type='text' style='width:100px' class='form-control' name='ay'></td></tr>
                                        
                    <tr><th scope='row'>Aktif</th> <td><input type='radio' name='k' value='Ya' checked> Ya
                                                       <input type='radio' name='k' value='Tidak'> Tidak
                    </td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?ndelox=dep/jadwalx&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}elseif($_GET['act']=='edit'){
    if (isset($_POST['update'])){
		$mk = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MKID,Nama FROM mk where MKID='".strfilter($_POST['c'])."'"));
		$arrProgramID = $_POST['ProgramID'];    
        $ProgramID = (empty($arrProgramID))? '' : '.'.implode('.', $arrProgramID).'.'; //if empty set REG A as default value
        $query = mysqli_query($koneksi, "UPDATE jadwal SET 
							  ProgramID 		= '".strfilter($ProgramID)."',
							  MKID 				= '".strfilter($_POST['c'])."',
							  Nama 				= '$mk[Nama]',
							  NamaKelas 		= '".strfilter($_POST['NamaKelas'])."',
							  RuangID 			= '".strfilter($_POST['b'])."',  
							  HariID 			= '".strfilter($_POST['d'])."',													                                                 
							  DosenID 			= '".strfilter($_POST['e'])."',                                                    
							  RencanaKehadiran 	= '".strfilter($_POST['f'])."',
							  KehadiranMin 		=  '".strfilter($_POST['g'])."',
							  JamMulai 			= '".strfilter($_POST['h'])."',
							  JamSelesai 		= '".strfilter($_POST['i'])."'
							  where JadwalID	= '".strfilter($_POST['JadwalID'])."'");
        if ($query){
          echo "<script>document.location='index.php?ndelox=dep/jadwalx&tahun=$_POST[a]&program=$_POST[program]&prodi=$_POST[prodi]&JadwalID=$_POST[JadwalID]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=dep/jadwalx&tahun=$_POST[a]&program=$_POST[program]&prodi=$_POST[prodi]&JadwalID=$_POST[JadwalID]&gagal';</script>";
        }
    }
    
    $e = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jadwal where JadwalID='".strfilter($_GET['JadwalID'])."'"));
	$kurAktif = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM kurikulum where ProdiID='".strfilter($_GET['prodi'])."' and NA='N' order by KurikulumID Desc limit 1"));

    //$ex = explode('.',$e[kode_kelas]);
    //$tingkat = $ex[0];
    echo "            <div class='card'>
				<div class='card-header'>
				<div class='col-md-12'>
				<div class='box box-info'>          
              <div class='box-header with-border'>
              <h3 class='box-title'>Edit Data Jadwal Kuliah</h3>
              </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-sm table-bordered'>
                  <tbody>
                  <input type='hidden' name='JadwalID' value='$_GET[JadwalID]'>
				  <input type='hidden' name='prodi' value='$_GET[prodi]'>
				  <input type='hidden' name='program' value='$_GET[program]'>
				  <input type='hidden' name='tahun' value='$_GET[tahun]'>
                    <tr><th style='width:210px' scope='row'>Tahun Akademik</th>   <td><select class='form-control' name='a'> 
						<option value='0' selected>- Pilih Tahun Akademik -</option>"; 
						$tahun = mysqli_query($koneksi, "SELECT distinct(TahunID),NA,ProdiID FROM tahun order by TahunID desc");
						while($a = mysqli_fetch_array($tahun)){
						  if ($e['TahunID']==$a['TahunID']){
							echo "<option value='$a[TahunID]' selected>$a[TahunID]</option>";
						  }else{
							echo "<option value='$a[TahunID]'>$a[TahunID]</option>";
						  }
						}
						echo "</select>
                    </td></tr>
					<tr><th scope='row'>Nama Kelas</th>  <td><input type='text' class='form-control' name='NamaKelas' value='$e[NamaKelas]'></td></tr>
					<tr><th scope='row'>Berlaku Untuk Program</th>   <td>";	
						$_arrNilai = explode('.', $e['ProgramID']);				   
						$prog = mysqli_query($koneksi, "SELECT * FROM program");
						while($p = mysqli_fetch_array($prog)){
						    $_ck = (array_search($p['ProgramID'], $_arrNilai) === false)? '' : 'checked';
       						 echo "<span style='display:block;'><input type=checkbox value='$p[ProgramID]' name=ProgramID[] $_ck> $p[Nama] &nbsp; &nbsp; &nbsp; </span>";
						}
						echo "
					</td></tr>	 
					<tr><th scope='row'>Berlaku Untuk Prodi</th>   <td>";	
					$_arrNilaix = explode('.', $e['ProdiID']);				   
					$prod = mysqli_query($koneksi, "SELECT * FROM prodi");
					while($w = mysqli_fetch_array($prod)){
						$_ck = (array_search($w['ProdiID'], $_arrNilaix) === false)? '' : 'checked';
							echo "<span style='display:block;'><input type=checkbox value='$w[ProdiID]' name=ProdiID[] $_ck> $w[Nama] &nbsp; &nbsp; &nbsp; </span>";
					}
					echo "
				</td></tr>	 
                    <tr><th scope='row'>Hari</th>   <td><select class='form-control' name='d'> 
					
					<option value='0' selected></option>";					   
						$hari = mysqli_query($koneksi, "SELECT * FROM hari");
						while($a = mysqli_fetch_array($hari)){
						  if ($e['HariID']==$a['HariID']){
							echo "<option value='$a[HariID]' selected>$a[Nama]</option>";
						  }else{
							echo "<option value='$a[HariID]'>$a[Nama]</option>";
						  }
						}
						echo "</select>
					</td></tr>							 
					<tr><th scope='row'>Ruang Kuliah </th>   <td><select class='form-control select2' name='b'> 
						<option value='$a[RuangID]' selected>- Pilih Ruang -</option>"; 
						$ruang = mysqli_query($koneksi, "SELECT * FROM ruang");
						while($a = mysqli_fetch_array($ruang)){
						  if ($e['RuangID']==$a['RuangID']){
							echo "<option value='$a[RuangID]' selected>$a[Nama]</option>";
						  }else{
							echo "<option value='$a[RuangID]'>$a[Nama]</option>";
						  }
						}
						echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Mata Kuliahx</th>   <td><select class='form-control select2' name='c'> "; 
						//if ($_GET[prodi]=='TI'){
						//	$mk = mysqli_query($koneksi, "SELECT * FROM mk WHERE ProdiID='$_GET[prodi]' and NA='N' and KurikulumID='$kurAktif[KurikulumID]' order by Nama asc");
						//}
						//if ($_GET[prodi]=='SI'){
							$mk = mysqli_query($koneksi, "SELECT * FROM mk WHERE ProdiID='$_GET[prodi]' and NA='N' and KurikulumID='$kurAktif[KurikulumID]' order by Nama ASC"); //  WHERE ProdiID='$_GET[prodi]'  and KurikulumID='27' order by Nama asc where ProdiID='SI' not working
						//}
						while($a = mysqli_fetch_array($mk)){
						  if ($e['MKID']==$a['MKID']){
							echo "<option value='$a[MKID]' selected>$a[Nama] - [ $a[MKKode] ]</option>";
						  }else{
							echo "<option value='$a[MKID]'>$a[Nama] - [ $a[MKKode] ]</option>";
						  }
						}
						echo "</select>
                    </td></tr>
                   
                    <tr><th scope='row'>Dosen</th>   <td><select class='form-control select2' name='e'> 
						<option value='$a[Login]' selected>- Pilih Dosen -</option>"; 
						$dosen = mysqli_query($koneksi, "SELECT * FROM vw_dosenaktif"); //vw_dosenaktif order by Nama ASC
						while($a = mysqli_fetch_array($dosen)){
						  if ($e['DosenID']==$a['Login']){
							echo "<option value='$a[Login]' selected>$a[Nama] - [ $a[Login] ] </option>";
						  }else{
							echo "<option value='$a[Login]'>$a[Nama] - [ $a[Login] ]</option>";
						  }
						}
						echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Rencana Tatap Muka</th>  <td><input type='text' style='width:100px' class='form-control' name='f' value='$e[RencanaKehadiran]'></td></tr>
                    <tr><th scope='row'>Minimal Kehadiran Mhs</th>  <td><input type='text' style='width:100px' class='form-control' name='g' value='$e[KehadiranMin]'></td></tr>
                    <tr><th scope='row'>Jam Mulai</th>  <td><input type='text' style='width:100px' class='form-control' name='h' placeholder='hh:ii:ss' value='$e[JamMulai]'></td></tr>
                    <tr><th scope='row'>Jam Selesai</th><td><input type='text' style='width:100px' class='form-control' name='i' placeholder='hh:ii:ss' value='$e[JamSelesai]'></td></tr>
                   
                    <tr><th scope='row'>Aktif</th><td>";
						if ($e['NA']=='N'){
							echo "<input type='radio' name='k' value='Ya' checked> Ya
								   <input type='radio' name='k' value='Tidak'> Tidak";
						}else{
							echo "<input type='radio' name='k' value='Ya'> Ya
								   <input type='radio' name='k' value='Tidak' checked> Tidak";
						}
                  echo "</td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='index.php?ndelox=dep/jadwalx&tahun=$_GET[tahun]&prodi=$_GET[prodi]&program=$_GET[program]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}

elseif($_GET['act']=='viewinfo'){
$dt = mysqli_fetch_array(mysqli_query($koneksi, "select JadwalID,NamaMK,NamaDosen,Gelar,SKS,Sesi,NamaKelas,NamaHari,NamaRuang, 
										DATE_FORMAT(UASTanggal,'%d-%m-%Y') AS Tgl,TIME_FORMAT(JamMulai, '%H:%i') AS JMulai,
										TIME_FORMAT(JamSelesai, '%H:%i') AS JSelesai 
										FROM vw_jadwal where JadwalID='".strfilter($_GET['JadwalID'])."'"));
$NamaDosx 	= strtolower($dt['NamaDosen']); //strtoupper($kalimat);
$NamaDos	= ucwords($NamaDosx);
$NamaMKx 	= strtolower($dt['NamaMK']); //strtoupper($kalimat);
$NamaMK		= ucwords($NamaMKx);
echo"            <div class='card'>
				<div class='card-header'>
				<div class='col-md-12'>
				<div class='box box-info'>
<div class='table-responsive'>
<table id='example' class='table table-sm table-striped' >


<tr style='text-align:left;font-size:15px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;'>
  <th colspan=3>&nbsp;&nbsp;Matakuliah</th> 
  <th colspan=8 style='width:20px'>&nbsp;: $NamaMK ($_GET[SKS] SKS) - Semester $_GET[Sesi] - Kelas $dt[NamaKelas]</th>	  
</tr>
<tr style='text-align:left;font-size:15px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;'>
  <th colspan=3 >&nbsp;&nbsp;Dosen Pengampu</th> 
  <th colspan=8 style='width:20px'>&nbsp;: $NamaDos , $dt[Gelar]</th>	  
</tr>
<tr style='text-align:left;font-size:15px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;'>
  <th colspan=3>&nbsp;&nbsp;Waktu/Ruang</th> 
  <th colspan=8 style='width:20px'>&nbsp;: $dt[NamaHari], $dt[JMulai]-$dt[JSelesai] WIB / $dt[NamaRuang]</th>	  
</tr>

<tr style='text-align:left;font-size:15px;color:#FFFFFF;font-weight:reguler;'>
  <th colspan=11 height='10' style='width:20px'></th>	  
</tr>




<tr style='text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc;'>
  <th style='width:20px'>No</th> 
  <th style='width:20px;text-align:center;'>NIM</th> 
   <th style='width:150px'>Nama Mahasiswa</th>
   <th style='width:250px'>Alamat</th>
   <th style='width:100px'>Tempat Lahir</th>
   <th style='width:90px'>Tgl Lahir</th>
   <th style='width:30px'>Handphone</th>	   
   <th style='width:120px'>Nama Ibu</th>                          
   <th style='width:30px'>NIK</th> 
   <th style='width:120px'>Kelurahan</th>
   <th style='width:120px'>Kecamatan</th>  
</tr>
</thead>
<tbody>";

	$sq = mysqli_query($koneksi, "SELECT krs.*, jadwal.Kehadiran,
	jadwal.MKKode, jadwal.Nama AS NamaMK, jadwal.NamaKelas, jadwal.JenisJadwalID,jadwal.TahunID,
	mhsw.Nama as NamaMhs,mhsw.Handphone,mhsw.Alamat,mhsw.TempatLahir,mhsw.TanggalLahir,mhsw.Kelamin,mhsw.NamaIbu,mhsw.NIK,mhsw.IDKK,mhsw.Kelurahan
	FROM krs
	LEFT OUTER JOIN jadwal ON krs.JadwalID=jadwal.JadwalID			
	LEFT OUTER JOIN mhsw ON mhsw.MhswID=krs.MhswID
	WHERE krs.JadwalID='".strfilter($_GET['JadwalID'])."'
	AND jadwal.TahunID='".strfilter($_GET['tahun'])."'
	GROUP BY krs.JadwalID,krs.MhswID order by krs.MhswID asc"); 
	$no=0;
	while($r=mysqli_fetch_array($sq)){
	$n++;	
	
	$persen			= ($r['JML']/$r['Kehadiran'])* 100;
	$persentase		= number_format(($r['JML']/$r['Kehadiran'])* 100,0);				       			
	$NamaMhs 		= strtolower($r['NamaMhs']); //strtoupper($kalimat);
	$NamaMhsLower	= ucwords($NamaMhs);
	$Alamatx 	    = strtolower($r['Alamat']);
	$Alamat 		= ucwords($Alamatx);
	$TmpLahirx 	    = strtolower($r['TempatLahir']);
	$TmpLahir 		= ucwords($TmpLahirx);
	$NamaIbux 	    = strtolower($r['NamaIbu']);
	$NamaIbu 		= ucwords($NamaIbux);
	 mysqli_query("update krs set Presensi='$persen' where MhswID=$r[MhswID] and JadwalID='$r[JadwalID]'");
	echo"<tr>
	<td height='30' align=center> $n</td>
	<td align=center>$r[MhswID]</td>
	<td>$NamaMhsLower ($r[Kelamin])</td>
	<td>$Alamat</td>
	<td>$TmpLahir</td>
	<td>".tgl_indo($r['TanggalLahir'])."</td>
	<td align='left'>$r[Handphone]</td>
	<td align=left >$NamaIbu</td>
	<td align=left >$r[NIK]</td>
	<td align=left >$r[Kelurahan]</td>
	<td align=left >$r[Kecamatan]</td>	
	</tr>";
	$no++;
	}
  ?>
  <tbody>
  </table></div>
  </div><!-- /.box-body -->

</div>
</div>

<?php
}
?>