<?php if ($_GET['act']==''){ ?> 
  <div class='card'>
<div class='card-header'>

                  <h3 class="box-title"><b style=color:green;>Data Asal Sekolah </b></h3>
               
                </div><!-- /.box-header -->
                <div class="box-body">
                <?php 
                ?>
                  <form action='' method='GET'>
                  <input type="hidden" name='ndelox' value='penmaba/asalsekolah'>
                  <div align="right">
                  <div class='table-responsive'>
                  <table width="0%" style='text-align:right' >
                  <tr>
                
                  <td><input type="text" name="cari" class="form-control" style='width:200px; text-align:left; padding:10px' placeholder="Cari Sekolah" value="<?php echo $_GET['cari']?>"></td>
                  <td> &nbsp;&nbsp;&nbsp;</td>
                  <td> <input type='submit' value='Cari' class='pull-right btn btn-primary btn-sm'></td>
                  <td> &nbsp;</td>
                  <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=tambah'>Tambahkan Data</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahmhs'>Tracking Asal Sekolah Mhs</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahsmk'>SMK</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahsma'>SMA</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahmaa'>MA</a></td>
                  </tr>
                  </table>
                  <div>
                  <br>
                  </form>    
                  <div class='card'>
                  <div class='card-header'>
                  <div class='table-responsive'>
                  <table id="examplex" class="table table-sm table-striped">
                    <thead>
                      <tr style="background-color:purple;color:white">
                        <th width="2%">No</th>
                        <th width="8%">Kode Sekolah</th>
                        <th width="18%">Nama Sekolah</th>                         
						            <th width="15%">Kab/Kota</th>		
                        <th>Alamat</th> 				
                        <th style='width:70px'>Action</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $cari = strfilter($_GET['cari']);
                    if (empty($cari)){
                        $tampil = mysqli_query($koneksi, "SELECT SekolahID,Nama,Kota,Alamat1 FROM asalsekolah WHERE Nama=''");
                    }else{
                        $tampil = mysqli_query($koneksi, "SELECT SekolahID,Nama,Kota,Alamat1 FROM asalsekolah WHERE Nama like '%".$cari."%' OR Kota like '%".$cari."%'");
                    }    
                    $no = 1;
                    while($r=mysqli_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[SekolahID]</td>
                              <td>$r[Nama]</td>
                              <td>$r[Kota]</td>
                              <td>$r[Alamat1]</td>
                             
                    		      <td><center>                               
                                <a class='btn btn-success btn-xs' title='Edit Data' href='?ndelox=penmaba/asalsekolah&act=edit&id=$r[SekolahID]'><i class='fa fa-edit'></i></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='?ndelox=penmaba/asalsekolah&hapus=$r[SekolahID]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-trash'></i></a>
                              </center></td>";
                            echo "</tr>";
                      $no++;
                      }

                      if (isset($_GET['hapus'])){
                          mysqli_query($koneksi, "DELETE FROM asalsekolah where SekolahID='".strfilter($_GET[hapus])."'");
                          echo "<script>document.location='index.php?ndelox=penmaba/asalsekolah';</script>";
                      }

                  ?>
                    </tbody>
                  </table>
                  </div>
                </div><!-- /.box-body -->
            </div>
<?php 
}

elseif($_GET['act']=='trackasalsekolahmhs'){ ?>
          <div class='card'>
<div class='card-header'>
                <div class="box-header">
                  <h3 class="box-title"><b style=color:green;>Rekap Asal Sekolah Mahasiswa</b></h3>
               
                </div><!-- /.box-header -->
                <div class="box-body">
                <?php 
                ?>
                  <form action='' method='GET'>
                  <input type="hidden" name='ndelox' value='penmaba/asalsekolah'>
                  <div align="right">
                  <table width="0%" style='text-align:right' >
                  <tr>
                
                  <td><input type="text" name="cari" class="form-control" style='width:200px; text-align:left; padding:10px' placeholder="Cari Sekolah" value="<?php echo $_GET['cari']?>"></td>
                  <td> &nbsp;&nbsp;&nbsp;</td>
                  <td> <input type='submit' value='Cari' class='pull-right btn btn-primary btn-sm'></td>
                  <td> &nbsp;</td>
                  <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=tambah'>Tambahkan Data</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahmhs'>Tracking Asal Sekolah Mhs</a></td>
                    <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahsmk'>SMK</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahsma'>SMA</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahmaa'>MA</a></td>
                  </tr>
                  </table>
                  <div>
                  <br>
                  </form>  
                  <div class='card'>
<div class='card-header'>  
                 <div class="table-responsive">
                  <table id="examplex" class="table table-sm table-striped">
                    <thead>
                      <tr style="background-color:purple;color:white">
                        <th width="2%">No</th>
                        <th width="8%">Kode</th>
                        <th width="60%">Asal Sekolah</th>
                        <th width="18%">Jumlah Siswa</th>                         
						<!--<th width="15%">Kab/Kota</th>		-->
                        <th style='width:70px;text-align:center'>Action</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                        $tampil = mysqli_query($koneksi, "SELECT * FROM view_asalsklmhs order by JumlahSiswa DESC");
                    $no = 1;
                    while($r=mysqli_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[AsalSekolah]</td>
                              <td><a href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahmhsdetail&idx=$r[AsalSekolah]'>$r[NamaSekolah]</a></td>
                              <td>$r[JumlahSiswa]</td>
                    		  <td><center>                               
                                <a class='btn btn-success btn-xs' title='Edit Data' href='?ndelox=master/asalsekolah&act=edit&id=$r[SekolahID]'><i class='fa fa-edit'></i></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='?ndelox=master/asalsekolah&hapus=$r[SekolahID]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-trash'></i></a>
                              </center></td>";
                            echo "</tr>";
                      $no++;
                      }

                      if (isset($_GET['hapus'])){
                          mysqli_query($koneksi, "DELETE FROM asalsekolahxx where SekolahID='".strfilter($_GET[hapus])."'");
                          echo "<script>document.location='index.php?ndelox=penmaba/asalsekolah';</script>";
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

elseif($_GET['act']=='trackasalsekolahmhsdetail'){ ?>
          <div class='card'>
<div class='card-header'>
                <div class="box-header">
                  <h3 class="box-title"><b style=color:green;>Data Asal Sekolah Mahasiswa</b></h3>
               
                </div><!-- /.box-header -->
                <div class="box-body">
                <?php 
                ?>
                  <form action='' method='GET'>
                  <input type="hidden" name='ndelox' value='penmaba/asalsekolah'>
                  <div align="right">
                  <table width="0%" style='text-align:right' >
                  <tr>
                
                  <td><input type="text" name="cari" class="form-control" style='width:200px; text-align:left; padding:10px' placeholder="Cari Sekolah" value="<?php echo $_GET['cari']?>"></td>
                  <td> &nbsp;&nbsp;&nbsp;</td>
                  <td> <input type='submit' value='Cari' class='pull-right btn btn-primary btn-sm'></td>
                  <td> &nbsp;</td>
                  <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=tambah'>Tambahkan Data</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahmhs'>Tracking Asal Sekolah Mhs</a></td>
                    <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahsmk'>SMK</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahsma'>SMA</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahmaa'>MA</a></td>
                  </tr>
                  </table>
                  <div>
                  <br>
                  </form>    
                 <div class="table-responsive">
                  <table id="examplex" class="table table-sm table-striped">
                    <thead>
                      <tr style="background-color:purple;color:white">
                        <th width="2%">No</th>
                        <th width="8%">NIM</th>
                        <th width="25%">Nama Mahasiswa</th>
                        <th width="30%">Asal Sekolah</th>                         
						<th width="15%">Kab/Kota</th>		
                        <th style='width:100px;text-align:center'>Action</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                        $tampil = mysqli_query($koneksi, "SELECT mhsw.MhswID, mhsw.Nama as NamaMhs, mhsw.AsalSekolah,
                                                          asalsekolah.Nama as NamaAsalSekolah, asalsekolah.Kota 
                                                          FROM mhsw, asalsekolah
                                                          WHERE mhsw.AsalSekolah=asalsekolah.SekolahID
                                                          AND mhsw.AsalSekolah='".strfilter($_GET['idx'])."'
                                                          ORDER BY mhsw.MhswID DESC");
                    $no = 1;
                    while($r=mysqli_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[MhswID]</td>
                              <td>$r[NamaMhs]</td>
                              <td>$r[NamaAsalSekolah]</td>
                              <td>$r[Kota]</td>
                    		  <td><center>                               
                                <a class='btn btn-success btn-xs' title='Edit Data' href='?ndelox=master/asalsekolah&act=edit&id=$r[SekolahID]'><i class='fa fa-edit'></i></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='?ndelox=master/asalsekolah&hapus=$r[SekolahID]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-trash'></i></a>
                              </center></td>";
                            echo "</tr>";
                      $no++;
                      }

                      if (isset($_GET['hapus'])){
                          mysqli_query($koneksi, "DELETE FROM asalsekolahxx where SekolahID='".strfilter($_GET['hapus'])."'");
                          echo "<script>document.location='index.php?ndelox=penmaba/asalsekolah';</script>";
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

elseif($_GET['act']=='trackasalsekolahsmk'){ ?>
         <div class='card'>
<div class='card-header'>
                <div class="box-header">
                  <h3 class="box-title"><b style=color:green;>Kategori Sekolah Menengah Kejuruan (SMK)</b></h3>
               
                </div><!-- /.box-header -->
                <div class="box-body">
                <?php 
                ?>
                  <form action='' method='GET'>
                  <input type="hidden" name='ndelox' value='penmaba/asalsekolah'>
                  <div align="right">
                  <table width="0%" style='text-align:right' >
                  <tr>
                
                  <td><input type="text" name="cari" class="form-control" style='width:200px; text-align:left; padding:10px' placeholder="Cari Sekolah" value="<?php echo $_GET['cari']?>"></td>
                  <td> &nbsp;&nbsp;&nbsp;</td>
                  <td> <input type='submit' value='Cari' class='pull-right btn btn-primary btn-sm'></td>
                  <td> &nbsp;</td>
                  <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=tambah'>Tambahkan Data</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahmhs'>Tracking Asal Sekolah Mhs</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahsmk'>SMK</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahsma'>SMA</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahmaa'>MA</a></td>
                  </tr>
                  </table>
                  <div>
                  <br>
                  </form>    
                 <div class="table-responsive">
                  <table id="examplex" class="table table-sm table-striped">
                    <thead>
                      <tr style="background-color:purple;color:white">
                        <th width="2%">No</th>
                        <th width="8%">Kode</th>
                        <th width="30%">Asal Sekolah</th>                         
						<th width="15%">Kab/Kota</th>		
                        <th style='width:100px;text-align:center'>Action</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                        $tampil = mysqli_query($koneksi, "SELECT * FROM `view_asalsklmhs` WHERE NamaSekolah LIKE '%SMK%'");
                    $no = 1;
                    while($r=mysqli_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[AsalSekolah]</td>
                              <td>$r[NamaSekolah]</td>
                              <td>$r[Kota]</td>
                    		  <td><center>                               
                                <a class='btn btn-success btn-xs' title='Edit Data' href='?ndelox=master/asalsekolah&act=edit&id=$r[SekolahID]'><i class='fa fa-edit'></i></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='?ndelox=master/asalsekolah&hapus=$r[SekolahID]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-trash'></i></a>
                              </center></td>";
                            echo "</tr>";
                      $no++;
                      }

                      if (isset($_GET[hapus])){
                          mysqli_query($koneksi, "DELETE FROM asalsekolahxx where SekolahID='".strfilter($_GET[hapus])."'");
                          echo "<script>document.location='index.php?ndelox=penmaba/asalsekolah';</script>";
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

elseif($_GET['act']=='trackasalsekolahsma'){ ?>
           <div class='card'>
<div class='card-header'>
                <div class="box-header">
                  <h3 class="box-title"><b style=color:green;>Kategori Sekolah Menengah Atas (SMA)</b></h3>
               
                </div><!-- /.box-header -->
                <div class="box-body">
                <?php 
                ?>
                  <form action='' method='GET'>
                  <input type="hidden" name='ndelox' value='penmaba/asalsekolah'>
                  <div align="right">
                  <table width="0%" style='text-align:right' >
                  <tr>
                
                  <td><input type="text" name="cari" class="form-control" style='width:200px; text-align:left; padding:10px' placeholder="Cari Sekolah" value="<?php echo $_GET['cari']?>"></td>
                  <td> &nbsp;&nbsp;&nbsp;</td>
                  <td> <input type='submit' value='Cari' class='pull-right btn btn-primary btn-sm'></td>
                  <td> &nbsp;</td>
                  <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=tambah'>Tambahkan Data</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahmhs'>Tracking Asal Sekolah Mhs</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahsmk'>SMK</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahsma'>SMA</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahmaa'>MA</a></td>
                  </tr>
                  </table>
                  <div>
                  <br>
                  </form>    
                 <div class="table-responsive">
                  <table id="examplex" class="table table-sm table-striped">
                    <thead>
                      <tr style="background-color:purple;color:white">
                        <th width="2%">No</th>
                        <th width="8%">Kode</th>
                        <th width="30%">Asal Sekolah</th>                         
						<th width="15%">Kab/Kota</th>		
                        <th style='width:100px;text-align:center'>Action</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                        $tampil = mysqli_query($koneksi, "SELECT * FROM `view_asalsklmhs` WHERE NamaSekolah LIKE '%SMA%'");
                    $no = 1;
                    while($r=mysqli_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[AsalSekolah]</td>
                              <td>$r[NamaSekolah]</td>
                              <td>$r[Kota]</td>
                    		  <td><center>                               
                                <a class='btn btn-success btn-xs' title='Edit Data' href='?ndelox=master/asalsekolah&act=edit&id=$r[SekolahID]'><i class='fa fa-edit'></i></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='?ndelox=master/asalsekolah&hapus=$r[SekolahID]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-trash'></i></a>
                              </center></td>";
                            echo "</tr>";
                      $no++;
                      }

                      if (isset($_GET['hapus'])){
                          mysqli_query($koneksi, "DELETE FROM asalsekolahxx where SekolahID='".strfilter($_GET['hapus'])."'");
                          echo "<script>document.location='index.php?ndelox=penmaba/asalsekolah';</script>";
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

elseif($_GET['act']=='trackasalsekolahmaa'){ ?>
          <div class='card'>
<div class='card-header'>
                <div class="box-header">
                  <h3 class="box-title"><b style=color:green;>Kategori Sekolah Madrasah Aliyah (MA)</b></h3>
               
                </div><!-- /.box-header -->
                <div class="box-body">
                <?php 
                ?>
                  <form action='' method='GET'>
                  <input type="hidden" name='ndelox' value='penmaba/asalsekolah'>
                  <div align="right">
                  <table width="0%" style='text-align:right' >
                  <tr>
                
                  <td><input type="text" name="cari" class="form-control" style='width:200px; text-align:left; padding:10px' placeholder="Cari Sekolah" value="<?php echo $_GET['cari']?>"></td>
                  <td> &nbsp;&nbsp;&nbsp;</td>
                  <td> <input type='submit' value='Cari' class='pull-right btn btn-primary btn-sm'></td>
                  <td> &nbsp;</td>
                  <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=tambah'>Tambahkan Data</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahmhs'>Tracking Asal Sekolah Mhs</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahsmk'>SMK</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahsma'>SMA</a></td>
                   <td><a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=penmaba/asalsekolah&act=trackasalsekolahmaa'>MA</a></td>
                  </tr>
                  </table>
                  <div>
                  <br>
                  </form>    
                 <div class="table-responsive">
                  <table id="examplex" class="table table-sm table-striped">
                    <thead>
                      <tr style="background-color:purple;color:white">
                        <th width="2%">No</th>
                        <th width="8%">Kode</th>
                        <th width="30%">Asal Sekolah</th>                         
						<th width="15%">Kab/Kota</th>		
                        <th style='width:100px;text-align:center'>Action</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                        $tampil = mysqli_query($koneksi, "SELECT * FROM `view_asalsklmhs` WHERE NamaSekolah LIKE 'MAN%' or 'MAS%'");
                    $no = 1;
                    while($r=mysqli_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[AsalSekolah]</td>
                              <td>$r[NamaSekolah]</td>
                              <td>$r[Kota]</td>
                    		  <td><center>                               
                                <a class='btn btn-success btn-xs' title='Edit Data' href='?ndelox=master/asalsekolah&act=edit&id=$r[SekolahID]'><i class='fa fa-edit'></i></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='?ndelox=master/asalsekolah&hapus=$r[SekolahID]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-trash'></i></a>
                              </center></td>";
                            echo "</tr>";
                      $no++;
                      }

                      if (isset($_GET['hapus'])){
                          mysqli_query($koneksi, "DELETE FROM asalsekolahxx where SekolahID='".strfilter($_GET['hapus'])."'");
                          echo "<script>document.location='index.php?ndelox=penmaba/asalsekolah';</script>";
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

elseif($_GET['act']=='detail'){
    $edit = mysqli_query($koneksi, "SELECT * FROM asalsekolah where SekolahID='".strfilter($_GET['id'])."'");
    $s = mysqli_fetch_array($edit);
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'><b style=color:green;> Detail Data Asal Sekolah </b></h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-sm table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='$s[SekolahID]'>
                    <tr><th width='140px' scope='row'>Sekolah ID</th> <td>$s[SekolahID]</td></tr>
                    <tr><th scope='row'>Nama Sekolah</th>       <td>$s[Nama]</td></tr>
                    <tr><th scope='row'>Alamat</th>    <td>$s[Alamat]</td></tr>
                    <tr><th scope='row'>Kota</th>    <td>$s[Kota]</td></tr>                
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <a href='index.php?ndelox=penmaba/asalsekolah'><button type='button' class='btn btn-default pull-right'>Kembali</button></a>
              </div>
              </form>
            </div>";
}elseif($_GET['act']=='edit'){
    if (isset($_POST['update'])){
        $query = mysqli_query($koneksi, "UPDATE asalsekolah SET 										 
                                          Nama 		  = '".strfilter($_POST['Nama'])."',
                                          Alamat1 	= '".strfilter($_POST['Alamat1'])."',
                                          Kota 		  = '".strfilter($_POST['Kota'])."',
                                          Telephone = '".strfilter($_POST['Telephone'])."',
                                          Website 	= '".strfilter($_POST['Website'])."',
                                          Email 		= '".strfilter($_POST['Email'])."'
                                          WHERE SekolahID='".strfilter($_POST['id'])."'");
        if ($query){
          echo "<script>document.location='index.php?ndelox=penmaba/asalsekolah&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=penmaba/asalsekolah&gagal';</script>";
        }
    }
    $edit = mysqli_query($koneksi, "SELECT * FROM asalsekolah where SekolahID='".strfilter($_GET['id'])."'");
    $s = mysqli_fetch_array($edit);
    echo "
               

              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
              <div class='card'>
<div class='card-header'>
<div class='box-header with-border'>
<h3 class='box-title'><b style=color:green;>Edit Data Asal Sekolah</b></h3>
</div>
                  <table class='table table-sm table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='$s[SekolahID]'>
                    <tr><th width='190px' scope='row'>Sekolah ID</th> <td><input type='text' class='form-control' name='SekolahID' value='$s[SekolahID]' readonly> </td></tr>
                    <tr><th scope='row'>Nama Sekolah</th>       <td><input type='text' class='form-control' name='Nama' value='$s[Nama]'></td></tr>
                    <tr><th scope='row'>Alamat</th>    <td><input type='text' class='form-control' name='Alamat1' value='$s[Alamat1]'></td></tr>
                    <tr><th scope='row'>Kota</th>    <td><input type='text' class='form-control' name='Kota' value='$s[Kota]'></td></tr>
					<tr><th scope='row'>Telephone</th>    <td><input type='text' class='form-control' name='Telephone' value='$s[Telephone]'></td></tr>
					<tr><th scope='row'>Website</th>    <td><input type='text' class='form-control' name='Website' value='$s[Website]'></td></tr>
					<tr><th scope='row'>Email</th>    <td><input type='text' class='form-control' name='Email' value='$s[Email]'></td></tr>
           
                  </tbody>
                  </table>
                  <button type='submit' name='update' class='btn btn-info'>Update</button>
                  <a href='index.php?ndelox=penmaba/asalsekolah'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                  
                </div>
              </div>
          
              </form>";
}elseif($_GET['act']=='tambah'){
    if (isset($_POST['tambah'])){
        $query = mysqli_query($koneksi, "INSERT INTO asalsekolah
							(SekolahID,
							Nama,
							Alamat1,
							Kota,
							JenisSekolahID,
							Telephone,
							Website,
							Email,
              Ket) 
							VALUES('".strfilter($_POST['SekolahID'])."',
							'".strfilter($_POST['Nama'])."',
							'".strfilter($_POST['Alamat1'])."',
							'".strfilter($_POST['Kota'])."',
							'UMUM',
							'".strfilter($_POST['Telephone'])."',
							'".strfilter($_POST['Website'])."',
							'".strfilter($_POST['Email'])."',
              'X'
							)");
        if ($query){
          echo "<script>document.location='index.php?ndelox=penmaba/asalsekolah&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=penmaba/asalsekolah&gagal';</script>";
        }
    }
    $data = mysqli_fetch_array(mysqli_query($koneksi, "SELECT SekolahID FROM asalsekolah WHERE Ket='X' ORDER BY SekolahID DESC limit 1"));
    $kode = $data['SekolahID'];
    $urutan = (int) substr($kode, 0, 8); //00000001
 
    // bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
    $urutan++; 
    $huruf = "";
    $kodeUrut = $huruf . sprintf("%08s", $urutan); //membuat string 8 karakter

    echo "
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>


              <div class='card'>
              <div class='card-header'>
              <div class='box-header with-border'>
              <h3 class='box-title'><b style=color:green;>Edit Data Asal Sekolah</b></h3>
              </div>
                  <table class='table table-sm table-bordered'>
                  <tbody>
                    <tr><th width='190px' scope='row'>Sekolah ID</th> <td><input type='text' class='form-control' name='SekolahID' value='$kodeUrut' readonly> </td></tr>
                    <tr><th scope='row'>Nama Sekolah</th>       <td><input type='text' class='form-control' name='Nama'></td></tr>
                    <tr><th scope='row'>Alamat</th>    <td><input type='text' class='form-control' name='Alamat1'></td></tr>
                    <tr><th scope='row'>Kota</th>    <td><input type='text' class='form-control' name='Kota'></td></tr>
                    <tr><th scope='row'>Telephone</th>    <td><input type='text' class='form-control' name='Telephone'></td></tr>
                    <tr><th scope='row'>Website</th>    <td><input type='text' class='form-control' name='Website'></td></tr>
                    <tr><th scope='row'>Email</th>    <td><input type='text' class='form-control' name='Email'></td></tr>
                   
                   
                  </tbody>
                  </table>
                  <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                  <a href='index.php?ndelox=penmaba/asalsekolah'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                  
                </div>
                </div>
              </div>
           

              </form>";
}
?>