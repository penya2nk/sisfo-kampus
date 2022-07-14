<?php if ($_GET[act]==''){ ?>
<div class="card">
<div class="card-header">
    <h3 class="box-title"><?php if (isset($_GET[tahun])){ echo "<b style='color:green;font-size:20px'>Penelitian Mahasiswa Sebelumnya</b>"; }else{ echo "<b style='color:green;font-size:20px'>Penelitian Mahasiswa Pada Tahun </b>".date('Y'); } ?></h3>
    <div class="form-group row">
    <label class="col-md-6 col-form-label text-md-right"><b style='color:purple'>JADWAL DOSEN</b></label>
    <div class="col-md-2">
    
    <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
      <input type="hidden" name='ndelox' value='students/mhstugasakhir'> 
      <select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
          <?php 
              echo "<option value=''>- Pilih Tahun Akademik -</option>";
              $tahun = mysqli_query($koneksi, "SELECT distinct(TahunID),NA,ProdiID FROM tahun order by TahunID Desc limit 8"); //and NA='N'
              while ($k = mysqli_fetch_array($tahun)){
                if ($_GET[tahun]==$k[TahunID]){
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
                  if ($_GET[prodi]==$k[ProdiID]){
                  echo "<option value='$k[ProdiID]' selected>$k[Nama]</option>";
                }else{
                  echo "<option value='$k[ProdiID]'>$k[Nama]</option>";
                }
              }
          ?>
      </select>
      </div>

    <div class="col-md-2">
      <input type="submit" class='btn btn-success btn-sm' value='Lihat'>
    </div>
    </form>
    </div>
    </div>
</div>

<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
          <table id="example1" class="table table-sm table-striped">
            <thead>
            <tr style='background:purple;color:white'>
					  <th  width="10px">No</th>					  					 
					  <th width="40px"> NIM</th>
					   <th width="80px">Mahasiswa</th> 
					  <th width="400px">Judul</th>
					  <th width="30px">Tahun</th>
					  <th width="30px">Prodi</th>
					  <th width="30px">Aksi</th>
					</tr>
                    </thead>
                    <tbody>
                  <?php
                    if (isset($_GET[tahun])){
						if ($_GET[tahun]!='' AND $_GET[prodi]!=''){
						  $tampil = mysqli_query($koneksi, "SELECT * FROM vw_ta where ProdiID='".strfilter($_GET[prodi])."' AND TahunID='".strfilter($_GET[tahun])."' order by MhswID Desc");                    
						}
						else if ($_GET[tahun]!='' AND $_GET[prodi]==''){
						  $tampil = mysqli_query($koneksi, "SELECT * FROM vw_ta where TahunID='".strfilter($_GET[tahun])."' order by MhswID Desc");                    
						}
						else if ($_GET[tahun]=='' AND $_GET[prodi]!==''){
						  $tampil = mysqli_query($koneksi, "SELECT * FROM vw_ta where ProdiID='".strfilter($_GET[prodi])."' order by MhswID Desc");                    
						}
						else{
						  $tampil = mysqli_query($koneksi, "SELECT * FROM vw_ta  order by MhswID Desc");                
						}				
						$no = 1;
						while($r=mysqli_fetch_array($tampil)){
						$namax 		= strtolower($r[Nama]);
						$Nama		= ucwords($namax);
						$judulx 	= strtolower($r[Judul]);
						$Judul		= ucwords($judulx);	
					   
					    $tanggal = tgl_indo($r[tgl_posting]);
						  echo "<tr>
						  <td>$no</td>					  					 
						  <td>$r[MhswID]</td>
						  <td>$Nama</td>
						  <td>$Judul</td> 
						  <td>$r[TahunID]</td>
						   <td>$r[ProdiID]</td>	
						  <td><a class='btn btn-success btn-xs' title='Lihat Data' href='index.php?ndelox=students/mhstugasakhir&act=detail&IDX=$r[TAID]&tahun=$r[TahunID]&prodi=$r[ProdiID]'><span class='glyphicon glyphicon-list'></span> More</a></td>
						  </tr>";
						  $no++;
						  }
					}	//get tahun top  
                  ?>
                    </tbody>
                  </table></div>              
                </div>
            </div>

<?php 
}
elseif($_GET[act]=='detail'){
$d = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM ta where TAID='".strfilter($_GET[IDX])."'")); 
$Judulx = strtolower($d[Judul]);
$Judul	= ucwords($Judulx);
$p = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MhswID,Nama,ProdiID FROM mhsw where MhswID='$d[MhswID]'"));    	

$pm = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MhswID,TempatPenelitian, PembimbingPro1,PembimbingPro2,TglUjianProposal,TglUjianSkripsi FROM jadwal_skripsi WHERE MhswID='$d[MhswID]'")); 

$dosen1 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen WHERE Login='$pm[PembimbingPro1]'")); 
$Pembimbing1x = strtolower($dosen1[Nama]);
$Pembimbing1	= ucwords($Pembimbing1x);

$dosen2 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar FROM dosen WHERE Login='$pm[PembimbingPro2]'")); 
$Pembimbing2x = strtolower($dosen2[Nama]);
$Pembimbing2	= ucwords($Pembimbing2x);

echo "<div class='col-md-12'>
<div class='box box-info'>
<div class='box-header with-border'>
  <h3 class='box-title'><b style=color:green;font-size=18px>Informasi Selengkapnya</b></h3>
</div>
<div class='box-body'>
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
<div class='col-md-6'>
  <table class='table table-condensed table-bordered'>
  <tbody>			
  <tr><th style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc; colspan=2><b>PENELITIAN MAHASISWA</b></th></tr>
  <tr><th scope='row' style='width:140px;'>Nama Mahasiswa</th><td>$d[MhswID] - $p[Nama]</td></tr>				 
  <tr><th scope='row'>Judul Penelitian</th><td>$Judul</td></tr>
  <tr><th scope='row'>Tempat Penelitian</th><td>$pm[TempatPenelitian]</td></tr>				 
  <tr><th scope='row'>Tahun Akademik</th><td>$d[TahunID]</td></tr> 				  
  </tbody>
  </table>  
</div>

<div class='col-md-6'>
  <table class='table table-condensed table-bordered'>
  <tbody>	
  <tr><th style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#3c8dbc; colspan=2><b>PEMBIMBING DAN JADWAL</b></th></tr>
   
  <tr><th scope='row' style='width:190px;'>Pembimbing 1</th><td>$Pembimbing1, $dosen1[Gelar]</td></tr>				 
  <tr><th scope='row'>Pembimbing 2</th><td>$Pembimbing2, $dosen2[Gelar]</td></tr>
  <tr><th scope='row'>Tanggal Proposal</th><td>".tgl_indo($pm[TglUjianProposal])."</td></tr>
   <tr><th scope='row'>Tanggal Seminar Hasil</th><td>".tgl_indo($pm[TglUjianSkripsi])."</td></tr>
  </tbody>
  </table>   		
</div>
</div>
<div class='box-footer'>

<a href='index.php?ndelox=students/mhstugasakhir&prodi=$_GET[prodi]&tahun=$_GET[tahun]'><button type=button class='btn btn-default pull-right'>Cancel</button></a>                   
</div>
</form>
</div>";
}
?>