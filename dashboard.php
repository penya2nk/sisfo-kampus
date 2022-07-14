<?php 
// $session = \Config\Services::session();
// use App\Models\Dasbor_model;
// $m_dasbor = new Dasbor_model();
// global $koneksi;
?>
<div class="alert alert-info">
	<h4>Hai <em class="text-warning"><?php echo $_SESSION['_Nama'] ?></em></h4>
	<b style='font-size:30px'><?php echo $Organisasix; ?> - </b> 
  	<b style='font-size:30px;color:yellow'>A</b>
    <b style='font-size:25px'>cademic&nbsp;&nbsp;</b> 
    <b style='font-size:30px;color:yellow'>I</b>
    <b style='font-size:25px'>ntegrated&nbsp;&nbsp;</b>
    <b style='font-size:30px;color:yellow'>S</b>
    <b style='font-size:25px'>ystem </b>
</div>


<?php 
if ($_SESSION['_LevelID']=='1'){
?>

 <!-- Info boxes -->
<div class="row">
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-info elevation-1"><i class="fas fa-newspaper"></i></span>

      <div class="info-box-content">
        <span class="info-box-text"><a href="?ndelox=master/mhs">Mahasiswa</a></span>
        <span class="info-box-number">
        <?php $mhs = mysqli_fetch_array(mysqli_query($koneksi,  "SELECT count(*) as total FROM mhsw")); ?>
          <small><?php echo "".number_format($mhs['total']).""; ?> Orang</small>
        </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>

      <div class="info-box-content">
        <span class="info-box-text"><a href="?ndelox=master/dosenx">Dosen</a></span>
        <span class="info-box-number"> 
        <?php $dosen = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(*) as total FROM dosen")); ?>
          <small><?php echo $dosen['total']; ?> Orang</small>
        </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->

  <!-- fix for small devices only -->
  <div class="clearfix hidden-md-up"></div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>

      <div class="info-box-content">
        <span class="info-box-text"><a href="?ndelox=dep/jadwalx&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>">Jadwal Kuliah</a></span>
        <span class="info-box-number"> <?php //echo angka($m_dasbor->jadwal()) ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-tags"></i></span>

      <div class="info-box-content">
        <span class="info-box-text"><a href="?ndelox=dep/jadwaldsnx&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>">Jadwal Dosen</a></span>
        <span class="info-box-number"><?php //echo angka($m_dasbor->jadwal()) ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->

<div class="row">
<!-- /.col -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-download"></i></span>

      <div class="info-box-content">
        <span class="info-box-text"><a href="?ndelox=kk/pjudulkpadm&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>">Kerja Praktek</a></span>
        <span class="info-box-number"> <?php //echo angka($m_dasbor->kerjapraktek()) ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-info elevation-1"><i class="fas fa-images"></i></span>

      <div class="info-box-content">
        <span class="info-box-text"><a href="?ndelox=ta/jadwal_seminarproposalskripsi&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>">Skripsi</a></span>
        <span class="info-box-number">
        <?php //echo angka($m_dasbor->skripsi()) ?>
          <small>Penelitian</small>
        </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-newspaper"></i></span>

      <div class="info-box-content">
        <span class="info-box-text"><a href="?ndelox=yud/admyudisium">Yudisium</a></span>
        <span class="info-box-number"> <?php //echo angka($m_dasbor->yudisium()) ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->

  <!-- fix for small devices only -->
  <div class="clearfix hidden-md-up"></div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-success elevation-1"><i class="fas fa-tags"></i></span>

      <div class="info-box-content">
        <span class="info-box-text"><a href="<?php //echo base_url('admin/wisuda') ?>">Wisuda</a></span>
        <span class="info-box-number"> <?php //echo angka($m_dasbor->yudisium()) ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  
</div>
<!-- /.row -->


<?php
//start kolom 1
echo"<div class='row'>
    <div class='col-md-6'>"; //end
    include "grafikpmb.php";
    //close kolom 1
    echo"</div>";

    //start kolom 2
    echo "<div class='col-md-6'>"; 
    include "grafikpmbdaftarulang.php";
    //tutup kolom 2
    echo"</div>
</form>";
?>

<?php 
}
?>
