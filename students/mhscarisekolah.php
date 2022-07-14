<?php if ($_GET[act]==''){ ?> 
  <div class="col-xs-12">  
    <div class="box">
      <div class="box-header">
        <h3 class="box-title"><b style=color:green;>Data Asal Sekolah <a href='index.php'>[ Kembali ]</a></b></h3>
     
      </div><!-- /.box-header -->
      <div class="box-body">
      <?php 
      ?>
        <form action='' method='GET'>
        <input type="hidden" name='ndelox' value='mhscarisekolah'>
        <div align="right">
        <table width="0%" style='text-align:right' >
        <tr>
      
        <td><input type="text" name="cari" class="form-control" style='width:200px; text-align:left; padding:10px' placeholder="Cari Sekolah" value="<?php echo $_GET['cari']?>"></td>
        <td> &nbsp;&nbsp;&nbsp;</td>
        <td> <input type='submit' value='Cari' class='pull-right btn btn-primary btn-sm'></td>
       
        </tr>
        </table>
        <div>
        <br>
        </form>    
       	<div class="table-responsive">
        <table id="examplex" class="table table-bordered table-striped">
          <thead>
            <tr style="background-color:purple;color:white">
              <th width="2%">No</th>
              <th width="8%">Kode Sekolah</th>
              <th width="18%">Nama Sekolah</th>                         
              <th width="15%">Kab/Kota</th>		
              <th>Alamat</th> 				
              
            </tr>
          </thead>
          <tbody>
        <?php 
          $cari = strfilter($_GET[cari]);
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
                    <td>$r[Alamat1]</td>";
                  echo "</tr>";
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