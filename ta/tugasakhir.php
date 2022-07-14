<div class="card">
<div class="card-header">
  <div class="card-tools">

  </div>

<?php if ($_GET['act']==''){ ?>
  <div class="form-group row">
<label class="col-md-7 col-form-label text-md-left"><b style='color:purple'>Referensi Judul Penelitian</b></label>

<div class="col-md-2 ">
<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
<input type="hidden" name='ndelox' value='ta/tugasakhir'> 
<select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
<?php 
    echo "<option value=''>- Pilih Tahun Akademik -</option>";
    $tahun = mysqli_query($koneksi, "SELECT distinct(TahunID),NA,ProdiID FROM tahun order by TahunID Desc limit 8"); //and NA='N'
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
</form>

</div><!-- /.box-header -->




<div class="table-responsive">
<table id="example" class="table table-sm table-bordered table-striped">
    <thead>
      <tr style="background:purple;color:white">
      <th  width="10px">No</th>					  					 
      <th width="40px"> NIM</th>
      <th width="80px">Mahasiswa</th> 
      <th  width="10px">ProgID</th>
      <th width="400px">Judul</th>
      <th width="30px">Tahun</th>
      <th width="30px">Prodi</th>
      <th width="30px">Aksi</th>
      </tr>
    </thead>
    <tbody>
  <?php
    if (isset($_GET['tahun'])){
if ($_GET['tahun']!='' AND $_GET['prodi']!=''){
$tampil = mysqli_query($koneksi, "SELECT * FROM vw_ta where ProdiID='".strfilter($_GET['prodi'])."' AND TahunID='".strfilter($_GET['tahun'])."' order by MhswID Desc");                    
}
else if ($_GET['tahun']!='' AND $_GET['prodi']==''){
$tampil = mysqli_query($koneksi, "SELECT * FROM vw_ta where TahunID='".strfilter($_GET['tahun'])."' order by MhswID Desc");                    
}
else if ($_GET['tahun']=='' AND $_GET['prodi']!==''){
$tampil = mysqli_query($koneksi, "SELECT * FROM vw_ta where ProdiID='".strfilter($_GET['prodi'])."' order by MhswID Desc");                    
}
else{
$tampil = mysqli_query($koneksi, "SELECT * FROM vw_ta  order by MhswID Desc");                
}				
$no = 1;
while($r=mysqli_fetch_array($tampil)){
$namax 		= strtolower($r['Nama']);
$Nama		= ucwords($namax);
$judulx 	= strtolower($r['Judul']);
$Judul		= ucwords($judulx);	

echo "<tr>
<td>$no</td>					  					 
<td>$r[MhswID]</td>
<td>$Nama</td>
<td>$r[ProgramID]</td>
<td>$Judul</td> 
<td>$r[TahunID]</td>
<td>$r[ProdiID]</td>	
<td><a class='btn btn-success btn-xs' title='Lihat Data' href='index.php?ndelox=home&act=xxx&kodejdwl=$r[b]&kd=$r[x]'><span class='glyphicon glyphicon-list'></span> OK</a></td>
</tr>";
$no++;
}
}	//get tahun top  
  ?>
    </tbody>
  </table></div>
</div><!-- /.box-body -->
</div>


<?php 
}elseif ($_GET['act']=='xxx'){
    $d = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM XXX"));
            echo "<div class='col-xs-12'>  
              <div class='box'>
                <div class='box-header'>
                  <h3 class='box-title'>XXX</h3>
                </div>
                <div class='box-body'>
                  <div class='col-md-12'>
                  <table class='table table-condensed table-hover'>
                      <tbody>
                        <input type='hidden' name='id' value='$d[x]'>
                       
                      </tbody>
                  </table>
                  </div>

                  <table class='table table-bordered table-striped'>
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>A</th>
                        <th>B </th>
                        <th>C</th>
                      </tr>
                    </thead>
                    <tbody>";
                     // $tampil = mysqli_query($koneksi, "SELECT * FROM rb_kompetensi_dasar where kode_pelajaran='$_GET[kd]' ORDER BY id_kompetensi_dasar ASC");
                    $no = 1;
                    while($r=mysqli_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[ranah]</td>
                              <td>$r[kd]</td>
                              <td>$r[kompetensi_dasar]</td>
                          </tr>";
                      $no++;
                      }
                    echo "<tbody>
                  </table>
                </div>
                </div>
            </div>";
} 
?>