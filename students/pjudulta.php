<?php if ($_GET[act]==''){ ?> 
	<div class='card'>
<div class='card-header'>
<div class='box box-info'>
                  <h3 class="box-title"><b style='color:green;font-size:20px'>Pengajuan Judul Skripsi 
                  <a href='index.php?ndelox=students/pjudulta&act=jadwalskripsipromhs'>[Jadwal Seminar Proposal]</a>
                  </b></br></h3>
				  <?php 
				
				  ?>
                  <?php if($_SESSION[level]!='pimpinan'){ ?>
                  <a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=students/pjudulta&act=tambah'>Tambahkan Data</a>
                  <?php } ?>
                </div><!-- /.box-header -->
                <div class="box-body">
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
</div>
</div>
</div>


<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
          <table id="example" class="table table-sm table-striped">
            <thead>
              <tr style='background:purple;color:white'>
                <th style='width:10px'>No</th>                        
                <th style='width:250px'>Judul Penelitian</th>                                              
                <th style='width:100px'>Tempat</th>
                <th style='width:10px'>Status</th>
				<th style='width:150px'>Pembimbing</th>
                <th style='width:40px'>Referensi</th>                     
                <th style='width:100px'>Action</th>                        
              </tr>
            </thead>
            <tbody>
          <?php 
            $h =mysqli_fetch_array(mysqli_query($koneksi, "select MhswID,Nama,ProdiID FROM mhsw WHERE MhswID='$_SESSION[_Login]'"));
            $tampil = mysqli_query($koneksi, "SELECT * from t_penelitian where MhswID='$_SESSION[_Login]'");
            $no = 1;
            while($r=mysqli_fetch_array($tampil)){	
            if ($r[Status]=='DITERIMA'){
				$c="style=color:green";
			}
			else if ($r[Status]=='WAITING'){
				$c="style=color:black";
			}
			else{
				$c="style=color:red";
			}
			$p1 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen WHERE Login='$r[Pembimbing1]'"));
			$dosenx 	= strtolower($p1[Nama]);
			$namados1	= ucwords($dosenx);
			$p2 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen WHERE Login='$r[Pembimbing2]'"));  
			$doseny 	= strtolower($p2[Nama]);
			$namados2	= ucwords($doseny);
            echo "<tr><td>$no</td>                             
                   <td>$r[Judul] <a href='?ndelox=students/pjudulta&act=komentar&id=$r[IDPenelitian]'><br>[Komentar Reviewer]</a></td>                             
                      <td>$r[TempatPenelitian] <br><a href='?ndelox=students/pjudulta&act=editalamat&IDPenelitian=$r[IDPenelitian]'>[ Set Penerima Surat ] <br> </a> 
					  <a target='_BLANK' href='print_report/print-mhssrpengantar.php?IDPenelitian=$r[IDPenelitian]'>[ Cetak Surat Pengantar ]</a></td>
                      <td $c><b>$r[Status]</b></td>
					   <td>					   
						<a target='_BLANK'  href='print_report/print-mhssrpenunjukan.php?IDPenelitian=$r[IDPenelitian]'>1. $namados1, $p1[Gelar]</a><br>
						<a target='_BLANK'  href='print_report/print-mhssrpenunjukan2.php?IDPenelitian=$r[IDPenelitian]'>2. $namados2, $p2[Gelar]</a></td>
                      <td>Ref 1: <br>Ref 2: <br></td>
                      <td> 
                      <a target='_BLANK' class='btn btn-success btn-sm' href='print_report/print-srpengajuanjudul.php?IDX=$r[IDPenelitian]&MhswID=$_SESSION[_Login]&tahun=$r[TahunID]&prodi=$h[ProdiID]'><i class='fa fa-print'></i></a>";
                        
             if($r[NA]=='N'){
				 
             echo"
			 <a class='btn btn-success btn-sm' title='Edit Data' href='?ndelox=students/pjudulta&act=edit&id=".$r[IDPenelitian]."'><i class='fa fa-edit'></i></a>
			 <a class='btn btn-danger btn-sm' title='Delete Data' href='?ndelox=students/pjudulta&hapus=".$r[IDPenelitian]."' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-trash'></i></a>";
              }        
                echo"<a style='margin-right:5px; width:30px' class='btn btn-info btn-sm' title='Lihat URL' href='$r[URLX]' target=_BLANK><i class='fa fa-download'></i></a>
                      </center>
                      </td>";
                    echo "</tr>";
              $no++;
              }

              if (isset($_GET[hapus])){
                  mysqli_query($koneksi, "DELETE FROM t_penelitian where IDPenelitian='".strfilter($_GET[hapus])."'");
                  echo "<script>document.location='index.php?ndelox=students/pjudulta';</script>";
              }

          ?>
                    </tbody>
                  </table>
				</div>
</div>
</div>


			<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
                 <table>
                 <tr><td colspan='9' align="left">
				 <?php echo"<b style='color:red;'>Perhatian!:</b> <br>1. Jumlah pengajuan judul skripsi minimal 3 (tiga), disertai referensi jurnal terkait untuk setiap judul <br>2. Soft file proposal disatukan dengan referensi jurnal terkait, di upload dengan format pdf max 1MB <br>3. Setelah selesai mohon untuk validasi link berikut</b><br><br>"; ?>
                 <?php   echo"<b style='color:red;'>Ini contoh2 judul yg saya rekomendasikan, tidak harus dimulai dengan aplikasi atau sistem informasi</b>...<br>

1. Integrasi Sistem Informasi Akademik STMIK Pontianak Dengan Metode Togaf Architechture Development Method<br>

2. Penerapan Metode Depth First Search pada Pencarian Rute Bus Kota Berbasis Web Mobile di Solo<br>

3. Pengarsipan File Administrasi Pada Biro Akademik Dengan Model Webbase System Aplication (Studi Kasus Di Stt Ibnu Sina Batam)<br>

4. Pengujian Kepuasan Sistem Informasi Menggunakan End-User Computing Satisfaction Studi Kasus: Sistem Informasi Akademik Uin Syarif Hidayatullah Jakarta<br>

5. Penerapan Algoritma Cosine Similarity dan Pembobotan TF-IDF pada Sistem Klasifikasi Dokumen Skripsi<br>

6. Model Sistem Informasi Promosi Dan Management Event Berbasis Web<br>

7. Implementasi Sistem Informasi Eksekutif Berbasis Web di Fakultas Teknik Universitas Pasundan Bandung<br>

8. Penerapan Qrcode Sebagai Media Pelayanan Untuk Absensi Pada Website Berbasis Php Native<br>

9. Simulasi Penjadwalan Bus Trans Mebidang Menggunakan Metode Repetitive<br>

10. Implementasi XP Programming Terhadap Sistem Informasi Pelayanan Publik Administrasi Desa Berbasis Web<br>
Dikirim dari ponsel cerdas Samsung Galaxy saya.<br>
11. Smart Parking System di .... (Studi Kasus:...)<br>
12. Sistem Filtering Berita Hoax berbasis Browser Extentions<br>
13. Security Alarm Rumahan Menggunakan Perangkat Rasberry<br>
<br><br>";

echo"&nsub;&nsub;&nbsp;<a href' title='Validasi' href='index.php?ndelox=students/pjudulta&valid=$r[id_pengajaranitem]&tahun=$r[TahunID]' onclick=\"return confirm('Yakin Pengajuan judul divalidasi?, setelah divalidasi data tidak bisa dilakukan perubahan kembali!')\">Validasi</a>";

if (isset($_GET[valid])){
   mysqli_query($koneksi, "UPDATE t_penelitian set NA='Y' where MhswID='$_SESSION[_Login]'");
}
?>
 </td>
 </tr>
 </table></div>
		 
</div>
</div>
</div>
			 
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
 <table>
 <tr><td colspan='20' align="left">
 <?php echo"<b style='color:red;'><marquee>Judul lebih diutamakan menggunakan metode tertentu misalnya metode electre, metode ahp, metode saw, analisis perbandingan dengan metode trand moment, least squre, Metode Single Item Lot Sizing Kapasitas Dinamis, 
 Metode Hierchical Model View Control (HMVC),  Metode Just In Time (JIT), Metode Profile Matching, Metode Webqual 4.0  dan lain-lain, 
 sedangkan judul mengenai sistem informasi atau aplikasi disarankan memiliki topik kasus yang berbeda dari referensi judul-judul penelitian yang dilakukan oleh mahasiswa yang telah lalu</marquee></b>"; ?> 
 </td>
 </tr>
 </table>
		 
</div>
</div>
</div>
			 
<?php 
}
elseif($_GET[act]=='komentar'){
    $dj = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM t_penelitian where IDPenelitian='".strfilter($_GET[id])."'"));
    echo "


              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
			  <div class='card'>
			  <div class='card-header'>
			  <div class='box box-info'>
                  <h3 class='box-title'><b style=color:green>Komentar Reviewer</b></h3>
                </div>
			  <div class='table-responsive'>
                  <table class='table table-sm table-sm'>
                  <tbody>
                    <tr><th  scope='row' width='200px'>NIM</th> <td>$_SESSION[_Login]</td></tr> 
					<tr><th  scope='row' width='200px'>Nama Mahasiswa</th> <td>$_SESSION[namalengkap]</td></tr> 
					<tr><th  scope='row' width='200px'>Judul Penelitian</th> <td>$dj[Judul]</td></tr>
					<tr><th  scope='row' >Tempat Penelitian</th> <td>$dj[TempatPenelitian]</td></tr>
					<tr><th width='140px' scope='row'>Komentar Reviewer</th> <td><font style=color:green><b>$dj[Komentar]</b></font></td></tr>
					<tr><th width='140px' scope='row'>Status</th> <td><font style=color:red><h1><b>$dj[Status]</b></h1></font></td></tr>
					<tr><th width='140px' scope='row'>Berkas</th> <td><b><a href='download_data.php?file=$dj[FilePenelitian]'>Download Soft File</a></b></td></tr>					
                  </tbody>
                  </table>
				  </div>
				  </div>
				  </div>
              <div class='box-footer'>
                <a href='index.php?ndelox=students/pjudulta'><button type='button' class='btn btn-default pull-right'>Kembali</button></a>
              </div>
              </form>
         ";
}
elseif($_GET[act]=='edit'){
    if (isset($_POST[update])){ 
		  $Judul 			=strfilter($_POST['Judul']);
		  $TahunID 			=strfilter($_POST['TahunID']);
		  $TempatPenelitian =strfilter($_POST['TempatPenelitian']);
		  $Abstrak			=strfilter($_POST['Abstrak']);
          $URLX				=strfilter($_POST['URLX']);
		  $query = mysqli_query($koneksi, "UPDATE t_penelitian SET 
										 Judul 				= '$Judul',
                                         TahunID 			= '$TahunID',
										 TempatPenelitian 	= '$TempatPenelitian',
										 Abstrak			= '$Abstrak',										
										 URLX				= '$URLX'
                                         WHERE IDPenelitian = '".strfilter($_POST[id])."' 
										 AND MhswID ='$_SESSION[_Login]'"); //FilePenelitian		= '$filenamee'
          echo "<script>document.location='index.php?ndelox=students/pjudulta&sukses';</script>";											 
    }//tutup kirimkan
	
$edit = mysqli_query($koneksi, "SELECT * FROM t_penelitian where IDPenelitian='".strfilter($_GET[id])."' AND MhswID ='$_SESSION[_Login]'");
$s = mysqli_fetch_array($edit);
echo "


<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
<div class='card'>
<div class='card-header'>
<div class='box box-info'>
<h3 class='box-title'><b style=color:green>Edit Data Proposal Penelitian</b></h3>
</div>
<div class='table-responsive'>
<table class='table table-sm table-sm'>
<tbody>
<input type='hidden' name='id' value='$s[IDPenelitian]'>
<tr><th width='280px' scope='row'>Judul Proposal Penelitian</th> <td><input type='text' class='form-control form-control-sm' name='Judul' value='$s[Judul]'> </td></tr>
<tr><th scope='row'>Tahun Akademik</th>       <td><input type='text' class='form-control form-control-sm' name='TahunID' value='$s[TahunID]'></td></tr>
<tr><th scope='row'>Tempat Penelitian</th>    <td><input type='text' class='form-control form-control-sm' name='TempatPenelitian' value='$s[TempatPenelitian]'></td></tr>


<tr>
<th scope='row'>Deskripsi</td>    
<td $c><textarea class='form-control form-control-sm' rows='6' name='Abstrak'>$s[Abstrak]</textarea></td>
</tr>

<tr><th scope='row'>URL </th>    <td><input type='text' class='form-control form-control-sm' name='URLX' value='$s[URLX]'></td></tr>                                             
</tbody>
</table>
<div class='box-footer'>
<button type='submit' name='update' class='btn btn-info'>Update</button>
<a href='index.php?ndelox=students/pjudulta'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>

</div>
</div>
</div>
</div>

</form>
";
}elseif($_GET[act]=='tambah'){

    $dsks = mysqli_fetch_array(mysqli_query($koneksi, "SELECT SUM(krs.SKS) AS tSKS,
			mhsw.ProgramID,
			krs.MKID
			FROM krs,mhsw 
			WHERE mhsw.MhswID=krs.MhswID
			AND krs.MhswID='$_SESSION[_Login]' 
			AND krs.GradeNilai NOT IN ('-','TL','E','D','') ")); //agar nilai di dalam range tidak dihitung tSKS lulus
	if ($dsks[tSKS]<130 AND $dsks[ProgramID]=='REG A'){
	    echo "<script language='javascript'>alert('Belum memenuhi syarat untuk mengajukan Skripsi, Total SKS yang baru ditempuh $dsks[tSKS] SKS!, sedangkan syarat minimalnya adalah 130 SKS');
			 window.location = 'index.php?ndelox=students/pjudulta&tahun=".$_GET[tahun]."&prodi=".$_GET[prodi]."&gagal'</script>";
		exit;
	}		
    
    if ($_SESSION[prodi]=='SI'){	
    $sqq = mysqli_query($koneksi, "SELECT krs.MKID,krs.MhswID,krs.GradeNilai
			FROM krs 
			WHERE krs.MhswID='$_SESSION[_Login]' 
			AND krs.MKID='1018'
			AND krs.GradeNilai IN ('-','TL','E','D','')"); 
	}
	if ($_SESSION[prodi]=='TI'){
	    //1081
    $sqq = mysqli_query($koneksi, "SELECT krs.MKID,krs.MhswID,krs.GradeNilai
			FROM krs 
			WHERE krs.MhswID='$_SESSION[_Login]' 
			AND krs.MKID='1202'
			AND krs.GradeNilai IN ('-','TL','E','D','')"); 
	}
	//1202
	
	$dt = mysqli_fetch_array($sqq);
	$cekkp=mysqli_num_rows($sqq);		
	if ($cekkp>0){
	    echo "<script language='javascript'>alert('Belum memenuhi syarat untuk mengajukan Kerja Praktek, Nilai Metode Penelitian Anda [ $dt[GradeNilai] ] dan belum dinyatakan lulus!');
			 window.location = 'index.php?ndelox=students/pjudulta&tahun=".$_GET[tahun]."&prodi=".$_GET[prodi]."&gagal'</script>";
		exit;
	}		



	if (isset($_POST[kirimkan])){		
		  $Judul 			=strfilter($_POST['Judul']);
		  $TahunID 			=strfilter($_POST['TahunID']);
		  $TempatPenelitian =strfilter($_POST['TempatPenelitian']);
		  $Abstrak			=strfilter($_POST['Abstrak']);
          $URLX				=strfilter($_POST['URLX']);
		  $query = mysqli_query($koneksi, "INSERT INTO t_penelitian(
						MhswID,
						Judul,
						TahunID,
						TempatPenelitian,
						Abstrak,
						TglPengajuan,
						ProdiID,
						URLX) 
				 VALUES('$_SESSION[_Login]',
						'$Judul',
						'$TahunID',
						'$TempatPenelitian',
						'$Abstrak',
						'".date('Y-m-d')."',
						'$_SESSION[prodi]',
						'$URLX')");
		if ($query){
		    echo "<script>document.location='index.php?ndelox=students/pjudulta&sukses';</script>";
		}else{
			 echo "<script>document.location='index.php?ndelox=students/pjudulta&gagal';</script>";
		}	
	}//tutup kirimkan

if ($_SESSION[prodi]=='SI'){$prod ="Sistem Informasi";}else{$prod ="Teknik Informatika"; }
echo "
<div class='box box-info'>
	  <h3 class='box-title'><b style=color:green>Pengajuan Judul Skripsi</b></h3>
	</div>
 
 <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
 <div class='row'>
 		<div class='col-md-6'>
			<div class='card'>
			<div class='card-header'>
			<div class='table-responsive'>
				<table class='table table-sm table-sm'>
				<tbody>
				
				<tr><th scope='row' >NIM</th>    <td><input type='text' class='form-control form-control-sm' name='x' value='$_SESSION[_Login]' readonly></td></tr>
				<tr><th scope='row'>Nama Mahasiswa</th>    <td><input type='text' class='form-control form-control-sm' name='x' value='$_SESSION[_Nama]' readonly></td></tr>
				
				<tr>
				<th scope='row' style=width:200px>Judul Proposal Penelitian</th>    
				<td scope='row'><textarea class='form-control form-control-sm' rows='3' name='Judul'></textarea></td>
				</tr>
									
				<tr><th scope='row'>Tempat Penelitian</th>    <td><input type='text' class='form-control form-control-sm' name='TempatPenelitian'></td></tr>
				<tr><th scope='row'>URL</th>    <td><input type='text' class='form-control form-control-sm' name='URLX'></td></tr>				
				</tbody>
				</table>
					<div class='box-footer'>
					<button type='submit' name='kirimkan' class='btn btn-info'>Tambahkan</button>
					<a href='index.php?ndelox=students/pjudulta'><button type='button' class='btn btn-default pull-right'>Cancel</button></a> 
					</div>
				</div>
				</div>
				</div>
		</div>		
 		<div class='col-md-6'>				
				<div class='card'>
				<div class='card-header'>
				<div class='table-responsive'>
				<table class='table table-sm table-sm'>
				<tbody>
				
				<tr>
				<th scope='row'>Program Studi <i><b style='color:red;'></b></i></th><td><input type='text' class='form-control form-control-sm' name='x' value='$prod' readonly>
				</td>
				</tr>
				
				<tr>
				<th scope='row'>Tahun Akademik <i><b style='color:red;'>(ex. 20171)</b></i></th><td><input type='text' class='form-control form-control-sm' name='TahunID' value='$_SESSION[tahun_akademik]'>
				</td>
				</tr> 
				
				<tr>
				<th scope='row'>Deskripsi</td>    
				<td $c><textarea class='form-control form-control-sm' rows='6' name='Abstrak'></textarea></td>
				</tr>
				
				</tbody>
				</table>
	
				</div>
				</div>
				</div>
		</div>		
    </div>
	

  </form>
";
}

elseif($_GET[act]=='editalamat'){
      if (isset($_POST[simpaneditl])){				       
		$query = mysqli_query($koneksi, "UPDATE t_penelitian SET 							
							  Kota 					= '".strfilter($_POST[Kota])."',	
							  Ke 					= '".strfilter($_POST[Ke])."'							                                                                                                   							
							  where IDPenelitian	= '".strfilter($_POST[IDPenelitian])."'"); //  TahunID 		= '$_POST[tahun]', KelompokID 	= '$_POST[KelompokID]',
        if ($query){
          echo "<script>document.location='index.php?ndelox=students/pjudulta&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=students/pjudulta&sukses';</script>";
        }
    }
    
    $tg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM t_penelitian where IDPenelitian='".strfilter($_GET[IDPenelitian])."'"));	
    $mhs  = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MhswID,Nama,ProdiID FROM mhsw where MhswID='$tg[MhswID]'"));
	
    echo "
<div class='box box-info'>
                  <h3 class='box-title'><b style=color:green>SET PENERIMA SURAT</b></h3>
                </div>

              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
			  <div class='card'>
			  <div class='card-header'>
			  <div class='table-responsive'>
                  <table class='table table-sm table-sm'>
                  <tbody>
                  <input type='hidden' name='IDPenelitian' value='$_GET[IDPenelitian]'>	
				 
                 
				  <tr><th scope='row' width='270px'>Mahasiswa</th><td><input type='text' class='form-control form-control-sm' name='NIM' value='$mhs[MhswID] - $mhs[Nama]' readonly></td></tr>
				  <tr><th scope='row' >Judul Skripsi</th><td><input type='text' class='form-control form-control-sm' name='Judul' value='$tg[Judul]' readonly></td></tr> 
				  <tr><th scope='row''>Tempat Penelitian</th><td><input type='text' class='form-control form-control-sm' name='TempatPenelitian' value='$tg[TempatPenelitian]' readonly></td></tr> 
				  <tr><th scope='row' >Kab/Kota</th><td><input type='text' class='form-control form-control-sm' name='Kota' value='$tg[Kota]'></td></tr> 
				  <tr><th scope='row' >To (<i>Untuk Surat Pengantar</i>)</th><td><input type='text' class='form-control form-control-sm' name='Ke' value='$tg[Ke]'></td></tr> 
				  
                 
				
                  </tbody>
                  </table>
				  </div>
				  </div>
				  </div>
              <div class='box-footer'>
                    <button type='submit' name='simpaneditl' class='btn btn-info'>Simpan</button>
                    <a href='index.php?ndelox=students/pjudulta&sukses'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            ";
}

elseif($_GET[act]=='jadwalskripsipromhs'){ 

$r = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from vw_jadwal_skripsi_ujian where MhswID='$_SESSION[_Login]' ORDER BY JadwalID DESC limit 1"));			   
	$p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PembimbingPro1]'"));
	$PembimbingPro1x   = strtolower($p1[Nama]);
	$PembimbingPro1	  = ucwords($PembimbingPro1x);
	$p2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PembimbingPro2]'"));	
	$PembimbingPro2x   = strtolower($p2[Nama]);
	$PembimbingPro2	  = ucwords($PembimbingPro2x);
	
	
	$DTPengujiPro1   = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiPro1]'"));
	$PengujiPro1x   = strtolower($DTPengujiPro1[Nama]);
	$PengujiPro1	= ucwords($PengujiPro1x);
	
	$DTPengujiPro2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiPro2]'"));
	$PengujiPro2x   = strtolower($DTPengujiPro2[Nama]);
	$PengujiPro2	  = ucwords($PengujiPro2x);
	
	$DTPengujiPro3 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiPro3]'"));		    $PengujiPro3x   = strtolower($DTPengujiPro3[Nama]);
	$PengujiPro3	  = ucwords($PengujiPro3x);
	
	$Judulx   = strtolower($r[Judul]);
	$Judul	  = ucwords($Judulx);		
	$ruang    = mysqli_fetch_array(mysqli_query($koneksi, "select RuangID,Nama from ruang where RuangID='$r[TempatUjian]'"));		  
	$tanggal  = $r[TglUjianProposal];
	$tglx     = tgl_indo($r[TglUjianProposal]);
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


echo"


<div class='card'>
<div class='card-header'>
<div class='box box-info'>
<h3 class='box-title'><b style='color:green;font-size:20px'>Seminar Skripsi</b></h3>
</div>
<div class='table-responsive'>
<table id='example' class='table table-sm table-striped'>
<tr style='text-align:left;font-size:15px;color:#FFFFFF;font-weight:bold;background-color:#3c8dbc;'>
<td colspan=2 >Jadwal Seminar Proposal</td>
</tr>

<tr>
<th scope='row' width='220px'>Judul</th><td>$Judul</td>
</tr> 

<tr>
<th scope='row'>Pembimbing  </th>
<td>1. $PembimbingPro1, $p1[Gelar] <br>
    2. $PembimbingPro2, $p2[Gelar] <br>
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
						".substr($r[JamMulaiProSkripsi],0,5)." - ".substr($r[JamSelesaiProSkripsi],0,5)." WIB <br>
						$ruang[Nama]
</td>
</tr>";
$r = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from vw_jadwal_skripsi_ujian where MhswID='$_SESSION[_Login]' ORDER BY JadwalID DESC limit 1"));
$p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PembimbingSkripsi1]'"));
		   $Pembimbing1x 	= strtolower($p1[Nama]);
		   $Pembimbing1		= ucwords($Pembimbing1x);
		   
		   $p2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PembimbingSkripsi2]'"));	
		   $Pembimbing2x 	= strtolower($p2[Nama]);
		   $Pembimbing2		= ucwords($Pembimbing2x);
			
			
		   $penguji1 		= mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiSkripsi1]'"));
		   $Penguji1x 		= strtolower($penguji1[Nama]);
		   $Penguji1		= ucwords($Penguji1x);
		   
		   $penguji2 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiSkripsi2]'"));
		   $Penguji2x 		= strtolower($penguji2[Nama]);
		   $Penguji2		= ucwords($Penguji2x);
		   
		   $penguji3 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$r[PengujiSkripsi3]'"));
		   $Penguji3x 		= strtolower($penguji3[Nama]);
		   $Penguji3		= ucwords($Penguji3x);
		
		   $ruang  = mysqli_fetch_array(mysqli_query($koneksi, "select RuangID,Nama from ruang where RuangID='$r[TempatUjian]'"));
			 
			$tanggal = $r[TglUjianSkripsi];
			$tglUjian =tgl_indo($r[TglUjianSkripsi]);
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
<td colspan=2>Jadwal Seminar Hasil</td>
</tr>

<tr>
<th scope='row'>Judul</th><td>$Judul</td>
</tr> 

<tr>
<th scope='row'>Pembimbing  </th>
<td>1. $Pembimbing1, $p1[Gelar] <br>
    2. $Pembimbing2, $p2[Gelar] <br>
</td>
</tr>	


<tr>
<th scope='row'>Penguji  </th>
<td>1. $Penguji1, $p1[Gelar] <br>
    2. $Penguji2, $p2[Gelar] <br>
	3. $Penguji3, $p3[Gelar] <br>
</td>
</tr>

<tr>
<th scope='row'>Waktu  dan Tanggal</th>
<td>$dayList[$day], $tglUjian<br>
						".substr($r[JamMulaiUjianSkripsi],0,5)."- ".substr($r[JamSelesaiUjianSkripsi],0,5)." WIB<br>
						$ruang[Nama]
</td>
</tr>
</table>
		<div class='box-footer'>
		<a href='index.php?ndelox=students/pjudulta'><button type='button' class='btn btn-default pull-right'>Cancel</button></a> 
		</div>
</div>
</div>
</div>";

}

elseif($_GET[act]=='jadwalujianprogmhs'){ 
	echo"

	<div class='card'>
<div class='card-header'>
<div class='box box-info'>
<h3 class='box-title'><b style='color:green;font-size:20px'>Seminar Skripsi</b></h3>
</div>
<div class='table-responsive'>

	<table id='example' class='table table-sm table-striped'>
	<tr style='text-align:left;font-size:15px;color:#FFFFFF;font-weight:bold;background-color:#3c8dbc;'>
	<td colspan=2 >Jadwal & Penguji Ujian Program</td>
	</tr>";
	$jdwl = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from jadwal_uptahun where ProdiID='SI'"));
	$tanggal  = $jdwl[TglUjian];
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
	
	echo"<tr>
	<th scope='row' width='220px'>Hari / Tanggal</th><th>$dayList[$day], ".tgl_indo($jdwl[TglUjian])." </th>
	</tr>
	<tr>
	<th scope='row' width='220px'>Waktu</th><th> ".substr($jdwl[JamMulai],0,5)." - ".substr($jdwl[JamSelesai],0,5)." WIB</th></tr>
	</tr>
	<tr>
	<th scope='row' width='220px'>Ruang</th><th>$jdwl[Ruang]</th></tr>
	</tr>"; 
	$sqz = mysqli_query($koneksi, "SELECT * from jadwal_upmhs where MhswID='$_SESSION[_Login]'");			   
		while($r=mysqli_fetch_array($sqz)){
			$sq = mysqli_query($koneksi, "SELECT * from jadwal_uppenguji where PengujiID='$r[PengujiID]'");
			while($w=mysqli_fetch_array($sq)){
			    $x++;
				echo"$DosenID <br>";
				$p1 = mysqli_fetch_array(mysqli_query($koneksi, "select Login,Nama,Gelar from dosen where Login='$w[DosenID]'"));
				$Pengujix   = strtolower($p1[Nama]);
				$Penguji	  = ucwords($Pengujix);
			}	
	echo"<tr>
	<th>Penguji $x</th>
	<th>$Penguji, $p1[Gelar]</th>
	</tr>";	
	}
	
	echo"</table>
	</div>
	</div>
	</div>";
	
	}

