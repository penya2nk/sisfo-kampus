<?php if ($_GET['act']==''){
    if (isset($_POST['update'])){
        $query = mysqli_query($koneksi, "UPDATE identitas SET 
										 Nama   = '".strfilter($_POST['a'])."',
                                         KodeHukum = '".strfilter($_POST['b'])."',
                                         TglMulai = '".strfilter($_POST['c'])."',
                                         Alamat1 = '".strfilter($_POST['d'])."',
                                         KodePos = '".strfilter($_POST['e'])."',
                                         Telepon = '".strfilter($_POST['f'])."',
                                         NoAkta = '".strfilter($_POST['g'])."',
                                         TglAkta = '".strfilter($_POST['h'])."',
                                         NoSah = '".strfilter($_POST['i'])."',
                                         TglSah = '".strfilter($_POST['j'])."',
                                         website = '".strfilter($_POST['k'])."',
                                         email = '".strfilter($_POST['l'])."' 
										 where Kode='".strfilter($_POST['id'])."'");
        if ($query){
          echo "<script>document.location='index.php?ndelox=master/identity&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?ndelox=master/identity&gagal';</script>";
        }
    }
    $edit = mysqli_query($koneksi, "SELECT * FROM identitas ORDER BY Kode DESC LIMIT 1");
    $s = mysqli_fetch_array($edit);
   
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
              echo "<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
              <div class='card'>
                <div class='card-header'>
                <div class='box-header with-border'>
                <h3 class='box-title'><b style='color:green;font-size:20px'>Data Identitas Institusi</b></h3>
              </div>
                <div class='table-responsive'>
                  <table class='table table-sm table-borderedx'>
                  <tbody>
                    <input type='hidden' name='id' value='$s[Kode]'>
                    <tr><th width='220px' scope='row'>Nama Institusi</th>   <td><input type='text' class='form-control form-control-sm' name='a' value='$s[Nama]'></td></tr>
                    <tr><th scope='row'>Kode Hukum</th>                         <td><input type='text' class='form-control form-control-sm' name='b' value='$s[KodeHukum]'></td></tr>
                    <tr><th scope='row'>Tgl Mulai</th>                          <td><input type='text' class='form-control form-control-sm' name='c' value='$s[TglMulai]'></td></tr>
                    <tr><th scope='row'>Alamat Institusi</th>               <td><input type='text' class='form-control form-control-sm' name='d' value='$s[Alamat1]'></td></tr>
                    <tr><th scope='row'>Kode Pos</th>                     <td><input type='text' class='form-control form-control-sm' name='e' value='$s[KodePos]'></td></tr>
                    <tr><th scope='row'>No Telpon</th>                    <td><input type='text' class='form-control form-control-sm' name='f' value='$s[Telepon]'></td></tr>
                    <tr><th scope='row'>No Akta</th>                    <td><input type='text' class='form-control form-control-sm' name='g' value='$s[NoAkta]'></td></tr>
                    <tr><th scope='row'>Tgl Akta</th>                    <td><input type='text' class='form-control form-control-sm' name='h' value='$s[TglAkta]'></td></tr>
                    <tr><th scope='row'>No Sah</th>             <td><input type='text' class='form-control form-control-sm' name='i' value='$s[NoSah]'></td></tr>
                    <tr><th scope='row'>TglSah</th>                     <td><input type='text' class='form-control form-control-sm' name='j' value='$s[TglSah]'></td></tr>
                    <tr><th scope='row'>Website</th>                      <td><input type='text' class='form-control form-control-sm' name='k' value='$s[Website]'></td></tr>
                    <tr><th scope='row'>Email</th>                        <td><input type='text' class='form-control form-control-sm' name='l' value='$s[Email]'></td></tr>
                  </tbody>
                  </table>
                  <div class='box-footer'>
                  <button type='submit' name='update' class='btn btn-info'>Update</button>
                  <a href='index.php'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                  
                </div>
                </div>
              </div>
            
              </form>
            </div>";
}
?>