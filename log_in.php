
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="">
  <title>Login</title>
  <link rel="shortcut icon" href="">
  <!-- Custom fonts for this template-->
  <link href="admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="admin/css/sb-admin-2.css" rel="stylesheet">
  <!-- Bootstrap core JavaScript-->
  <script src="admin/vendor/jquery/jquery.min.js"></script>
  <!-- sweetalert -->
  <script src="sweetalert/js/sweetalert.min.js"></script>
  <link rel="stylesheet" type="text/css" href="sweetalert/css/sweetalert.css">
  <style type="text/css" media="screen">
    .bg-login-image, .bg-register-image, .bg-password-image {
      background: url("img/banner_login.png");
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
    }

    body {
    margin-top:90px;
    background-image: url('img/bg2.jpg');
    background-size: cover;
     background-attachment: fixed;
}
  </style>
</head>



<?php 
function LoginView() {
  echo"<div class='card'>
  <div class='card-header'>
  <div align='center'>
  <h2><b style='color:purple;font-family:tahoma'>SIAKAD</b><br><b style='color:#154c79;font-family:tahoma'> UNIVERSITAS</b> <b style='color:orange;font-family:tahoma'>TEKNOKRAT <b style='color:blue;font-family:tahoma'>INDONESIA<b></h2
  </div>
  </div>
  </div>";

  global $koneksi;
  $kolom = 3;
  $sqx =  "select * from level where Tampak='Y'";
  $rows = mysqli_query($koneksi, $sqx) or die("Failed: "+mysql_error());
  echo " 
  <div class='card'>
  <div class='card-headerx'>
  <div class='table-responsive'>
  <bodyx>
  <table id='examplex' class='table table-sm table-stripedx' style='width:50%' align='center'>";
  echo "<tr style='height:150;'>";
  $knt = 0; 
  while ($wx = mysqli_fetch_array($rows)) {
    if ($knt >= $kolom) {
      echo "</tr>
      <tr style='height:150'>";
      $knt = 0;
    }
      $knt++;
      $iconx = (empty($wx['Simbol']))? 'img/login.png' : $wx['Simbol'];
      echo "<td style='vertical-align:middle;text-align:center'>
            <a href='?ndelox=log_in&logn=form_lg&lev_id=$wx[LevelID]&nama_level=$wx[Nama]' title='Log sebagai $wx[Nama]'>
            <img src='$iconx' border=0 width='80'><br>
            <b>".strtoupper($wx['Nama'])."</a></b></td>";
  }
      echo "</tr></table>
      </div>
  </div>
</div>";

echo"<div class='card'>
<div class='card-header'>
<div align='center'>
<p>Universitas Teknokrat Indonesia<br>
<a href='https://teknokrat.ac.id'>www.teknokrat.ac.id</a></p>
</div>
</div>
</div>
</body>";
}

function form_lg(){
  Reset_Log_In();
  global $oxID;
  $Lbl_Log      = ($_GET['lev_id'] == 120)? "NIM" : "";
  $MsgMB        = ($_GET['lev_id'] == 33)? "Password default adalah tanggal lahir anda dengan format TTTT-BB-HH.<br>Contoh: Masukkan '1999-12-31' untuk tanggal lahir 31 Desember 1999" : "";
  $v_organisasi = KodeID;
  $NamaOrg      = AmbilOneField('identitas', 'Kode', KodeID, 'Nama');
  //$NamaOrg      = mysqli_fetch_array(mysqli_query($koneksi, "select Nama from identitas where Kode='KodeID'"));
?>

<body class="bg-gradient-primary">
  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center" style="margin-top: 5%;">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-md-12 text-center">
                
              </div>
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4"><b style='color:orange'>UNIVERSITAS</b> <b style='color:purple'>TEKNOKRAT </b><b style='color:blue'>INDONESIA</b></h1>
                    <hr>
                    <p>LOGIN <?php echo "".strtoupper($_GET['nama_level'])."";?></p>
                    <hr>
                  </div>
                  <form action="?" method="POST" accept-charset="utf-8">
                    <?php 
                    echo"<input type=hidden name='lev_id' value='$_GET[lev_id]' />
                    <input type=hidden name='ndelox' value='login_go' />
                    <input type=hidden name='lungo' value='periksax' />
                    <input type=hidden name='nama_level' value='$_GET[nama_level]' />
                    <input type=hidden name='v_organisasi' value='$v_organisasi' />
                    <input type=hidden name='KodeID' value='".KodeID."' />
                    <input type=hidden name='BypassMenu' value='1' />";
                    ?>
                
                  <input type="hidden" name="pengalihan" value="">
                    <div class="form-group">
                      <input type="text" name="Login" maxlength=20 required class="form-control form-control-user" placeholder="Username...">
                      <small class="text-danger"></small>
                    </div>
                    <div class="form-group">
                      <input type="password" name="Password" maxlength=20 required class="form-control form-control-user" placeholder="Password">
                      <small class="text-danger"></small>
                      <?php   echo "$MsgMB" ?>
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Ingat saya</label>
                      </div>
                    </div>
                    <button class="btn btn-primary btn-block btn-user" type="submit" name="submit">
                      <i class="fa fa-lock"></i> Login
                    </button>
                                     
                  </form>                  <hr>
                  <div class="text-center">
                    <a class="small" href="">Lupa Password?</a> | 
                    <a class="small" href="?ndelox=">Home</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>




<?php
echo Info("", $frm_content);
}

$logn = (!empty($_GET['logn']))? $_GET['logn'] : 'LoginView';
$logn();

?>

<script src="admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="admin/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="admin/js/sb-admin-2.min.js') }}"></script>
</body>
</html>
