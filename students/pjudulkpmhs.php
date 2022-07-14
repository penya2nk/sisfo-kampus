<div class='card'>
<div class='card-header'>

<div class='box box-info'>
<h3 class="box-title"><b style='color:green;font-size:20px'>Pengajuan Judul Kerja Praktek </b></h3>
<a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=students/pjudulkpmhs&act=ajukanjudul&tahun=<?php echo $_SESSION[tahun_akademik]; ?>'>Ajukan Judul KP</a>       
</div>

</div>
</div>

                
<?php if ($_GET[act]==''){ ?>         
	  <?php 
        if (isset($_GET['sukses'])){
            echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah Berhasil Di Proses,..
                </div>";
        }elseif(isset($_GET['gagal'])){
            echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Data tidak Di Proses..
                </div>";
        }
      ?>
	  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
        <table id="example" class="table table-sm table-striped">
          <thead>
            <tr style='background:purple;color:white'>
              <th style='width:20px'>No</th>
              <th style='width:100px'>NIM</th>  
              <th style='width:400px'>Nama</th>                           
              <th>Program Studi</th>                                
              <th width='140px'>Action</th> 
            </tr>
          </thead>
          <tbody>
        <?php
			  $qrs = mysqli_query($koneksi, "SELECT * from jadwal_kp where MhswID='$_SESSION[_Login]'");//and ProgramID like '%$_GET[program]%'
			  while ($h = mysqli_fetch_array($qrs)){
			  $mhs = mysqli_fetch_array(mysqli_query($koneksi, "select MhswID,Nama,ProdiID from mhsw where MhswID='$h[MhswID]'"));
			  $pmb = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$h[DosenID]'"));			   
				if ($h[Status]=='DITERIMA'){
				$c="style=color:green;font-size:20px";
				}
				else if ($h[Status]=='WAITING'){
					$c="style=color:color:#FF8306;font-size:20px";
				}
				else{
					$c="style=color:red;font-size:20px";
				}
				$hd++;
			  echo"<tr style='background-color:#DFF4FF'>
				 <td colspan='11' height='20'><b>&nbsp;  
				 <a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=students/pjudulkpmhs&act=edit&JadwalID=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]'><i class='fa fa-edit'></i></a>
				  
				   
				  | <a href='index.php?ndelox=students/pjudulkpmhs&act=tambahanggota&JadwalID=$h[JadwalID]&KelompokID=$h[KelompokID]&prodi=$h[ProdiID]&tahun=$h[TahunID]'>
				  $hd. KLP: $h[KelompokID] - $h[Judul] <i>( Ketua: $mhs[MhswID] - $mhs[Nama] )</i> [ Add Member ]</a> 
				  
				  <br> <div align='right'>
				  
				  <b $c>Status : $h[Status]</b> |
				  <a href='$h[URLX]' target=_BLANK>Cek URL</a> |
				  <a href='index.php?ndelox=students/pjudulkpmhs&act=viewkomentar&JadwalID=$h[JadwalID]&prodi=$h[ProdiID]&tahun=$h[TahunID]'> Komentar Reviewer </a> |
				  <a target='_BLANK' href='print_report/print-srpengajuanjudulkp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&MhswID=$h[MhswID]'> Cetak Pengajuan Judul </a> |
				  <a target='_BLANK' href='print_report/print-srpenunjukankp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&MhswID=$h[MhswID]'> Cetak Surat Pembimbing </a> |
				  <a target='_BLANK' href='print_report/print-srperusahaankp.php?JadwalID=$h[JadwalID]&tahun=$h[TahunID]&MhswID=$h[MhswID]'> Cetak Surat Perusahaan </a> |
				  Pembimbing: $pmb[Nama], $pmb[Gelar];
				  </div></b>
                  
				 </td>
				 </tr>";  
                
          $tampil = mysqli_query($koneksi, "SELECT jadwal_kp_anggota.*,mhsw.Nama,mhsw.ProdiID,mhsw.Handphone 
		  FROM jadwal_kp_anggota,mhsw where 
		  mhsw.MhswID=jadwal_kp_anggota.MhswID
		  AND jadwal_kp_anggota.JadwalID='$h[JadwalID]'");                      
		  $no = 1;
          while($r=mysqli_fetch_array($tampil)){	
		   $i+=1;
			 $sisa=$i%2;
			 if ($sisa == 0)  {
				$warna = '#FF9900';
			 }else {
				$warna = '#66CC00';
			 } 
		  echo "<tr style=font-color: red><td>$no</td>   
		  		<td $warna>$r[MhswID]</td>  
		  	    <td $warna>$r[Nama] <b>[ <a href='index.php?ndelox=smsmhs&JadwalID=$r[JadwalID]&program=$r[ProgramID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&telepon=$r[Handphone]'> $r[Handphone] </a> ]</b></td>                   
                <td $warna>$r[ProdiID]</td>";                           
		  echo "<td style='width:70px !important'><center>
          <a class='btn btn-danger btn-xs' title='Hapus' href='index.php?ndelox=students/pjudulkpmhs&hapus=$r[MhswID]&prodi=$r[ProdiID]&tahun=$r[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
	      </center></td>";

echo "</tr>";
		  $no++;
		  }

}//hari
		  if (isset($_GET[hapus])){
			mysqli_query($koneksi, "DELETE FROM jadwal_kp_anggota where MhswID='".strfilter($_GET[hapus])."'");
			echo "<script>document.location='index.php?ndelox=students/pjudulkpmhs&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
		  }
		  if (isset($_GET[del])){
			 mysqli_query($koneksi, "DELETE FROM jadwal_kp where JadwalID='".strfilter($_GET[del])."'");
			 mysqli_query($koneksi, "DELETE FROM jadwal_kp_anggota where JadwalID='".strfilter($_GET[del])."''");
			echo "<script>document.location='index.php?ndelox=students/pjudulkpmhs&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
		  }
	  ?>
		<tbody>
	  </table>
	</div>
</div>
</div>
</div>

<?php 
}elseif($_GET[act]=='ajukanjudul'){
    $dsks = mysqli_fetch_array(mysqli_query($koneksi, "SELECT SUM(krs.SKS) AS tSKS,
			mhsw.ProgramID,
			krs.MKID
			FROM krs,mhsw 
			WHERE mhsw.MhswID=krs.MhswID
			AND krs.MhswID='$_SESSION[_Login]' 
			AND krs.GradeNilai NOT IN ('-','TL','E','D','C-','') ")); //AND krs.GradeNilai NOT IN ('-','TL','E','D','C-','')
	if ($dsks[tSKS]<110 AND $dsks[ProgramID]=='REG A'){
	    echo "<script language='javascript'>alert('Belum memenuhi syarat untuk mengajukan Kerja Praktek, Total SKS yang baru ditempuh $dsks[tSKS] SKS!, sedangkan syarat minimalnya adalah 110 SKS');
			 window.location = 'index.php?ndelox=students/pjudulkpmhs&tahun=".$_GET[tahun]."&prodi=".$_GET[prodi]."&gagal'</script>";
		exit;
	}		
    
    if ($_SESSION[prodi]=='SI'){	
    $sqq = mysqli_query($koneksi, "SELECT krs.MKID,krs.MhswID,krs.GradeNilai
			FROM krs 
			WHERE krs.MhswID='$_SESSION[_Login]' 
			AND krs.MKID='1008'
			AND krs.GradeNilai IN ('-','TL','E','D','C-','')"); 
	}
	if ($_SESSION[prodi]=='TI'){	
    $sqq = mysqli_query($koneksi, "SELECT krs.MKID,krs.MhswID,krs.GradeNilai
			FROM krs 
			WHERE krs.MhswID='$_SESSION[_Login]' 
			AND krs.MKID='1071'
			AND krs.GradeNilai IN ('-','TL','E','D','C-','')"); 
	}
	
	$dt = mysqli_fetch_array($sqq);
	$cekmetopel=mysqli_num_rows($sqq);		
	if ($cekmetopel>0){
	    echo "<script language='javascript'>alert('Belum memenuhi syarat untuk mengajukan Kerja Praktek, Nilai Metode Penelitian Anda [ $dt[GradeNilai] ] dan belum dinyatakan lulus!');
			 window.location = 'index.php?ndelox=students/pjudulkpmhs&tahun=".$_GET[tahun]."&prodi=".$_GET[prodi]."&gagal'</script>";
		exit;
	}		

	$prodi = mysqli_fetch_array(mysqli_query($koneksi, "select MhswID,Nama,ProdiID from mhsw WHERE MhswID='$_SESSION[_Login]'"));
	if ($prodi[ProdiID]=='SI'){$b='SI';}else{$b='TI';}
	
	//$sqNo = mysqli_fetch_array(mysqli_query($koneksi, "SELECT KelompokID,ProdiID FROM jadwal_kp WHERE ProdiID='$prodi[ProdiID]' ORDER BY JadwalID DESC LIMIT 1"));
	//$ex = explode('-', $sqNo[KelompokID]);
	$w = mysqli_fetch_array(mysqli_query($koneksi, "SELECT JadwalID,KelompokID,ProdiID,TahunID FROM jadwal_kp ORDER BY JadwalID DESC LIMIT 1"));
	$ex = explode('-', $w[JadwalID]); 
	
	if (date('d')=='01'){ $a = '01'; }
	else{ $a = $ex[0]+1; }	 	
	//$c = array('','I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII');
	$d = date('y');
	//$KodeAuto = $a.'/'.$b.'/'.$c[date('n')].'/'.$d;
	$KodeAuto = $a.'-'.$d.$b; //$a ditarok belakang tidak jalan
	if (isset($_POST[tambah])){	
	    $tglnow =date('Y-m-d H:i:s');		   
		/*$cek = mysqli_num_rows(mysqli_query($koneksi, "select * from jadwal_kp 
											WHERE MhswID='$_SESSION[_Login]'
											AND TahunID='$_POST[tahun]'"));
		if ($cek>0){
			echo "<script>document.location='index.php?ndelox=pjudulkpmhs&tahun=$_POST[tahun]&prodi=$_POST[prodi]&gagal';</script>";
			exit;
		}*/
 				
	    $query = mysqli_query($koneksi, "INSERT INTO jadwal_kp(
							  TahunID,
							  KelompokID,
							  MhswID,
							  DosenID,
							  Judul,
							  TempatPenelitian,							 
							  ProdiID,
							  TglBuat,
							  LoginBuat,            
							  NA,
							  Deskripsi,
							  Kota,
							  Ke,
							  URLX) 
					 VALUES(
							'".strfilter($_POST[tahun])."',
							'".strfilter($_POST[KelompokID])."',
							'$_SESSION[_Login]',
							'".strfilter($_POST[DosenID])."',
							'".strfilter($_POST[Judul])."',
							'".strfilter($_POST[TempatPenelitian])."',					
							'".strfilter($prodi[ProdiID])."',
							'".date('Y-m-d')."',
							'$_SESSION[_Login]',              
							'N',
							'".strfilter($_POST[Deskripsi])."',
							'".strfilter($_POST[Kota])."',
							'".strfilter($_POST[Ke])."',
							'".strfilter($_POST[URLX])."')");
		
        if ($query){
			echo "<script>document.location='index.php?ndelox=students/pjudulkpmhs&tahun=$_POST[tahun]&prodi=$_POST[prodi]&sukses';</script>";
        }else{
            echo "<script>document.location='index.php?ndelox=students/pjudulkpmhs&tahun=$_POST[tahun]&prodi=$_POST[prodi]&gagal';</script>";
        }
		
    }

    echo "<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <input type='hidden' name='tahun' value='$_GET[tahun]'>
				<input type='hidden' name='prodi' value='$_GET[prodi]'>
				<div class='card'>
				<div class='card-header'>
				<div class='table-responsive'>
                  <table class='table table-sm table-bordered'>
                  <tbody>
                   	<tr style='background:purple;color:white;'><th scope='row' colspan=2>Kelompok/Tim Kerja Praktek</th></tr>
					<tr><th scope='row' width='200'>Nama Kelompok</th>           <td><input type='text' class='form-control form-control-sm' name='KelompokID' value='$KodeAuto' readonly></td></tr>
                    <tr><th scope='row'>Judul Kerja Praktek</th>           <td><textarea rows='2' class='form-control form-control-sm' name='Judul' ></textarea></td></tr>
					<tr><th scope='row'>Tempat Penelitian</th>           <td><input type='text' class='form-control form-control-sm' name='TempatPenelitian'></td></tr>
					<tr><th scope='row'>URL</th>           <td><input type='text' class='form-control form-control-sm' name='URLX'></td></tr>
					<tr>
					<th scope='row'>Deskripsi</td>    
					<td $c><textarea class='form-control form-control-sm' rows='6' name='Deskripsi'></textarea></td>
					</tr>
					
					<tr style='background:purple;color:white;'><th scope='row' colspan=2>Setting Penerbitan Surat Pengantar Ke Perusahaan</th></tr>
                    <tr><th scope='row'>Tujuan Surat / Kota </th>           <td><input type='text' class='form-control form-control-sm' name='Kota'></td></tr>
					<tr><th scope='row'>Kepada</th> <td><input type='text' class='form-control form-control-sm' name='Ke'></td></tr>
					
					
	
			 
                  </tbody>
                  </table>
				  <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?ndelox=students/pjudulkpmhs&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>              
                  </div>
				  </div>
                </div>
              </div>

				  
				  <div class='card'>
				  <div class='card-header'>
				  <div class='table-responsive'>
                 <table>
                 <tr>
				 <td colspan='20' align='left'>
				 <b style='color:red;'><marquee>Kerja Praktek merupakan matakuliah yang dilakukan berkelompok yang terdiri dari maksimal 3 orang dan minimal 2 orang, Pengajuan Judul KP hanya satu judul dan dilakukan oleh Ketua Kelompok</marquee></b>
                 </td>
                 </tr>
                 </table>
						 
             	</div>
               </div>
             </div> 
				  
              </form>
            </div>";
}elseif($_GET[act]=='edit'){
    if (isset($_POST[update])){			
		$query = mysqli_query($koneksi, "UPDATE jadwal_kp SET 							
							 Judul 				= '".strfilter($_POST[Judul])."',
							 TempatPenelitian   = '".strfilter($_POST[TempatPenelitian])."',
							 Deskripsi   		= '".strfilter($_POST[Deskripsi])."',
							 Kota   			= '".strfilter($_POST[Kota])."',
							 Ke   				= '".strfilter($_POST[Ke])."',
							 URLX				= '".strfilter($_POST[URLX])."'
							 where JadwalID		= '".strfilter($_POST[JadwalID])."'");
        if ($query){
          echo "<script>document.location='index.php?ndelox=students/pjudulkpmhs&tahun=$_POST[tahun]&prodi=$_POST[prodi]&JadwalID=$_POST[JadwalID]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=students/pjudulkpmhs&tahun=$_POST[tahun]&prodi=$_POST[prodi]&JadwalID=$_POST[JadwalID]&gagal';</script>";
        }
    }
    
    $tg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jadwal_kp where JadwalID='".strfilter($_GET[JadwalID])."'"));
    echo "
             
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
			  <div class='card'>
			  <div class='card-header'>
			  <div class='box-header with-border'>
			  <h3 class='box-title'><b style='background:purple;color:white;'>Edit Pengajuan Judul Kerja Praktek</b></h3>
			</div>
			  <div class='table-responsive'>
                  <table class='table table-sm table-bordered'>
                  <tbody>
                  <input type='hidden' name='JadwalID' value='$_GET[JadwalID]'>	
				  <input type='hidden' name='prodi' value='$_GET[prodi]'>				 
				  <input type='hidden' name='tahun' value='$_GET[tahun]'>               
                <tr style=background:#FFCC00><th scope='row' colspan=2>Kelompok/Tim Kerja Praktek</th></tr>  
				<tr><th scope='row' width='200px'>Judul Kerja Praktek</th><td><input type='text' class='form-control form-control-sm' name='Judul' value='$tg[Judul]'></td></tr> 
				<tr><th scope='row'>Tempat Penelitian</th><td><input type='text' class='form-control form-control-sm' name='TempatPenelitian' value='$tg[TempatPenelitian]'></td></tr> 
				<tr><th scope='row'>URL</th><td><input type='text' class='form-control form-control-sm' name='URLX' value='$tg[URLX]'></td></tr> 
			    <tr>
				<th scope='row'>Deskripsi</td>    
				<td $c><textarea class='form-control form-control-sm' rows='6' name='Deskripsi'>$tg[Deskripsi]</textarea></td>
				</tr>
				<tr style=background:#FFCC00><th scope='row' colspan=2>Setting Penerbitan Surat Pengantar Ke Perusahaan</th></tr>
                <tr><th scope='row'>Tujuan Surat / Kota </th> <td><input type='text' class='form-control form-control-sm' name='Kota' value='$tg[Kota]'></td></tr>
			    <tr><th scope='row'>Kepada</th> <td><input type='text' class='form-control form-control-sm' name='Ke' value='$tg[Ke]'></td></tr>
                </tbody>
                </table>
				              <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='index.php?ndelox=students/pjudulkpmhs&tahun=$_GET[tahun]&prodi=$_GET[prodi]&program=$_GET[program]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
				</div>
                </div>
              </div>

              </form>";
}elseif($_GET[act]=='tambahanggota'){

    $dsks = mysqli_fetch_array(mysqli_query($koneksi, "SELECT SUM(krs.SKS) AS tSKS,
			mhsw.ProgramID,
			krs.MKID
			FROM krs,mhsw 
			WHERE mhsw.MhswID=krs.MhswID
			AND krs.MhswID='".strfilter($_POST[MhswID])."' 
			AND krs.GradeNilai NOT IN ('-','TL','E','D','C-','') ")); //AND krs.GradeNilai NOT IN ('-','TL','E','D','C-','')
	if ($dsks[tSKS]<110 AND $dsks[ProgramID]=='REG A'){
	    echo "<script language='javascript'>alert('Belum memenuhi syarat untuk mengajukan Kerja Praktek, Total SKS yang baru ditempuh $dsks[tSKS] SKS!, sedangkan syarat minimalnya adalah 110 SKS');
			 window.location = 'index.php?ndelox=students/pjudulkpmhs&tahun=".$_GET[tahun]."&prodi=".$_GET[prodi]."&sukses'</script>";
		exit;
	}		
    
    if ($_SESSION[prodi]=='SI'){	
    $sqq = mysqli_query($koneksi, "SELECT krs.MKID,krs.MhswID,krs.GradeNilai
			FROM krs 
			WHERE krs.MhswID='".strfilter($_POST[MhswID])."' 
			AND krs.MKID='1008'
			AND krs.GradeNilai IN ('-','TL','E','D','C-','')"); 
	}
	if ($_SESSION[prodi]=='TI'){	
    $sqq = mysqli_query($koneksi, "SELECT krs.MKID,krs.MhswID,krs.GradeNilai
			FROM krs 
			WHERE krs.MhswID='".strfilter($_POST[MhswID])."' 
			AND krs.MKID='1071'
			AND krs.GradeNilai IN ('-','TL','E','D','C-','')"); 
	}
	
	$dt = mysqli_fetch_array($sqq);
	$cekmetopel=mysqli_num_rows($sqq);		
	if ($cekmetopel>0){
	    echo "<script language='javascript'>alert('Belum memenuhi syarat untuk mengajukan Kerja Praktek, Nilai Metode Penelitian Anda [ $dt[GradeNilai] ] dan belum dinyatakan lulus!');
			 window.location = 'index.php?ndelox=students/pjudulkpmhs&tahun=".$_GET[tahun]."&prodi=".$_GET[prodi]."&sukses'</script>";
		exit;
	}		


    if (isset($_POST[tambah])){	
	    $tglnow =date('Y-m-d H:i:s');	
	    //echo"coba";
		$cek1 = mysqli_num_rows(mysqli_query($koneksi, "select * from mhsw where MhswID='".strfilter($_POST[MhswID])."'"));
		if ($cek1==0){
			 echo "<a href='index.php?ndelox=students/pjudulkpmhs&act=tambahanggota&JadwalID=$_GET[JadwalID]&prodi=$_GET[prodi]&tahun=$_GET[tahun]&KelompokID=$_POST[KelompokID]'> Data tidak ketemu!</a>";		
			exit;
		}
		
		$cek2 = mysqli_num_rows(mysqli_query($koneksi, "select * from jadwal_kp_anggota where MhswID='".strfilter($_POST[MhswID])."'"));
		if ($cek2>0){
			 echo "<script>document.location='index.php?ndelox=students/pjudulkpmhs&act=tambahanggota&JadwalID=$_GET[JadwalID]&prodi=$_GET[prodi]&tahun=$_GET[tahun]&KelompokID=$_POST[KelompokID]&gagal';</script>";
			exit;
		}
	  
	    $query = mysqli_query($koneksi, "INSERT INTO jadwal_kp_anggota(JadwalID,KelompokID,MhswID)
										VALUES('".strfilter($_POST[JadwalID])."','".strfilter($_POST[KelompokID])."','".strfilter($_POST[MhswID])."')");		
        if ($query){
			echo "<script>document.location='index.php?ndelox=students/pjudulkpmhs&tahun=$_POST[tahun]&prodi=$_POST[prodi]&sukses';</script>";
        }else{
            echo "<script>document.location='index.php?ndelox=students/pjudulkpmhs&tahun=$_POST[tahun]&prodi=$_POST[prodi]&gagal';</script>";
        }		
    }
    $tg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jadwal_kp where JadwalID='".strfilter($_GET[JadwalID])."'"));
	$x   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen where Login='$tg[DosenID]'"));
    echo "

              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>              
				 <input type='hidden' name='JadwalID' value='$_GET[JadwalID]'>
				 <input type='hidden' name='tahun' value='$_GET[tahun]'>			
				<input type='hidden' name='prodi' value='$_GET[prodi]'>
				<div class='card'>
				<div class='card-header'>
				<div class='box box-info'>
                  <h3 class='box-title'>Tambah Data</h3>
                </div>

				<div class='table-responsive'>
                  <table class='table table-sm table-bordered'>
                  <tbody>                  
					<tr><th scope='row' width='260'>Judul</th><td><b>$tg[Judul]</b></td></tr>
				  <tr><th scope='row'>Pembimbing</th><td><b>$x[Nama], $x[Gelar]</b></td></tr>
					<tr><th scope='row'>Nama Kelompok</th>           <td><input type='text' class='form-control form-control-sm' name='KelompokID' value='$_GET[KelompokID]' readonly=''></td></tr>
                    <tr><th scope='row'>NIM</th>           <td><input type='text' class='form-control form-control-sm' name='MhswID'></td></tr>										
                  </tbody>
                  </table>
				                <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?ndelox=students/pjudulkpmhs&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
				  </div>
                </div>
              </div>

              </form>
			  </div>
			  </div>
			  </div>";
}
elseif($_GET[act]=='viewkomentar'){
    $dj = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jadwal_kp where JadwalID='".strfilter($_GET[JadwalID])."'"));
	$mhs = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MhswID,Nama FROM mhsw where MhswID='$dj[MhswID]'"));
    echo "
              <div class='card'>
<div class='card-header'>
<div class='col-md-12'>
<div class='box box-info'>
                  <h3 class='box-title'>Komentar Reviewer</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                	<div class='table-responsive'>
                  <table class='table table-sm table-bordered'>
                  <tbody>
                    <tr><th  scope='row' width='200px'>NIM</th> <td>$dj[MhswID]</td></tr> 
					<tr><th  scope='row' width='200px'>Nama Mahasiswa</th> <td>$mhs[Nama]</td></tr> 
					<tr><th  scope='row' width='200px'>Judul Penelitian</th> <td>$dj[Judul]</td></tr>
					<tr><th  scope='row' >Tempat Penelitian</th> <td>$dj[TempatPenelitian]</td></tr>
					<tr><th width='140px' scope='row'>Komentar Reviewer</th> <td><font style=color:green><b>$dj[Komentar]</b></font></td></tr>
					<tr><th width='140px' scope='row'>Status</th> <td><font style=color:red><h1><b>$dj[Status]</b></h1></font></td></tr>
					<tr><th width='140px' scope='row'>Berkas</th> <td><b><a href='xx.php?file=$dj[FilePenelitian]'>Download Soft File</a></b></td></tr>					
                  </tbody>
                  </table></div>
                </div>
              </div>
              <div class='box-footer'>
                <a href='index.php?ndelox=students/pjudulkpmhs'><button type='button' class='btn btn-default pull-right'>Kembali</button></a>
              </div>
              </form>
            </div>";
}

elseif($_GET[act]=='jadwalprokpmhs'){ 

$r = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from vw_jadwalkp where MhswID='$_SESSION[_Login]'"));			   
	$p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[DosenID]'"));
	$PembimbingPro1x   = strtolower($p1[Nama]);
	$PembimbingPro1	  = ucwords($PembimbingPro1x);

	
	$DTPengujiPro1   = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[Penguji1]'"));
	$PengujiPro1x   = strtolower($DTPengujiPro1[Nama]);
	$PengujiPro1	= ucwords($PengujiPro1x);
	
	$DTPengujiPro2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[Penguji2]'"));
	$PengujiPro2x   = strtolower($DTPengujiPro2[Nama]);
	$PengujiPro2	  = ucwords($PengujiPro2x);
	
	$DTPengujiPro3 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[Penguji3]'"));		    
	$PengujiPro3x   = strtolower($DTPengujiPro3[Nama]);
	$PengujiPro3	  = ucwords($PengujiPro3x);
	
	$Judulx   = strtolower($r[Judul]);
	$Judul	  = ucwords($Judulx);		
	$ruang    = mysqli_fetch_array(mysqli_query($koneksi, "select RuangID,Nama from ruang where RuangID='$r[TempatUjian]'"));		  
	$tanggal  = $r[TglMulaiSidang];
	$tglx     = tgl_indo($r[TglMulaiSidang]);
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


echo"<div class='card'>
<div class='card-header'>
<div class='col-md-12'>
<div class='box box-info'>
<h3 class='box-title'><b style='color:green;font-size:20px'>Jadwal Seminar Proposal Kerja Praktek</b></h3>
</div>

<table id='example' class='table table-bordered table-striped'>
<tr style='text-align:left;font-size:15px;color:#FFFFFF;font-weight:bold;background-color:#3c8dbc;'>
<td colspan=2>Jadwal Seminar Proposal</td>
</tr>

<tr>
<th scope='row' width='220px'>Judul</th><td>$Judul</td>
</tr> 

<tr>
<th scope='row'>Pembimbing  </th>
<td>$PembimbingPro1, $p1[Gelar] <br>
</td>
</tr>	


<tr>
<th scope='row'>Penguji  </th>
<td>1. $PengujiPro1, $DTPengujiPro1[Gelar] <br>
    2. $PengujiPro2, $DTPengujiPro2[Gelar] <br>
	3. $PengujiPro3, $DTPengujiPro3[Gelar] <br>
</td>
</tr>

<tr>
<th scope='row'>Waktu  dan Tanggal</th>
<td>$dayList[$day], $tglx<br>
						".substr($r[JamMulai],0,5)."- ".substr($r[JamSelesai],0,5)."  WIB<br>
						$ruang[Nama]
</td>
</tr>";


//SEMINAR HASIL KERJA PRAKTEK ===================================================================================================================================================
$r = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from vw_jadwalkp_seminarhasil where MhswID='$_SESSION[_Login]'"));
$p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[DosenID]'"));
		   $Pembimbing1x 	= strtolower($p1[Nama]);
		   $Pembimbing1		= ucwords($Pembimbing1x);
		   
			
		   $penguji1Bro		= mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiSeminarHasil1]'"));
		   $Penguji1x 		= strtolower($penguji1Bro[Nama]);
		   $Penguji1		= ucwords($Penguji1x);
		   
		   $penguji2Bro = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiSeminarHasil2]'"));
		   $Penguji2x 		= strtolower($penguji2Bro[Nama]);
		   $Penguji2		= ucwords($Penguji2x);
		   
		   $penguji3Bro = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiSeminarHasil3]'"));
		   $Penguji3x 		= strtolower($penguji3Bro[Nama]);
		   $Penguji3		= ucwords($Penguji3x);
		
		   $ruang  = mysqli_fetch_array(mysqli_query($koneksi, "select RuangID,Nama from ruang where RuangID='$r[TempatUjian]'"));
			 
			$tanggal = $r[TglSeminarHasil];
			$tglUjian =tgl_indo($r[TglSeminarHasil]);
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
echo"<tr style='text-align:left;font-size:15px;color:#FFFFFF;font-weight:bold;background-color:#3c8dbc;'>
<td colspan=2>Jadwal Seminar Hasil Kerja Praktek</td>
</tr>

<tr>
<th scope='row'>Judul</th><td>$Judul</td>
</tr> 

<tr>
<th scope='row'>Pembimbing  </th>
<td>$Pembimbing1, $p1[Gelar]
  
</td>
</tr>	


<tr>
<th scope='row'>Penguji  </th>
<td>1. $Penguji1, $penguji1Bro[Gelar] <br>
    2. $Penguji2, $penguji2Bro[Gelar] <br>
	3. $Penguji3, $penguji3Bro[Gelar] <br>
</td>
</tr>

<tr>
<th scope='row'>Waktu  dan Tanggal</th>
<td>$dayList[$day], $tglUjian<br>
						".substr($r[JamMulaiSeminarHasil],0,5)."- ".substr($r[JamSelesaiSeminarHasil],0,5)." WIB<br>
						$ruang[Nama]
</td>
</tr>
</table>
</div>	   
</div>
</div>";

}
?>