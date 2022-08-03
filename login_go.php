<?php
$lungo = (empty($_REQUEST['lungo']))? 'periksax' : $_REQUEST['lungo'];
$lungo();

function gagal_login() {
	 ?>
  <div align="center">
	 <img src="img/iconggl.jpg" alt="Login Gagal" width="100">
	 <h1>Login Gagal</h1>
	 "Login dan Password Anda Tidak Terdaftar.<br>
     Silahkan Hubungi Administrasi System!<hr size=1 color=black>
	  <a class='btn btn-success' href='?ndelox='>Login</a>
    <a class='btn btn-danger'href='?ndelox='>Back</a>
  </div>
	 <?php
}

function Sukses() {
  global $AppNamex, $Versionx, $oxID, $koneksi;
}

function periksax() {
  global $oxID, $koneksi; //$koneksi harus deklarasi leweh     
  $passlain	= anti_injection($_POST['Password']);
  $data    	= md5(anti_injection($_POST['Password']));
  $pass    	= hash("sha512",$data);
  $LvlID    = strfilter($_POST['lev_id']);
      
  //$_tbl = mysqli_fetch_array(mysqli_query($koneksi, "select * from level where LevelID='$_REQUEST[lev_id]'"));      
  $table_x = AmbilOneField('level', 'LevelID', $LvlID , 'TabelUser');
  $Organisasix = $_POST['v_organisasi'];

  //echo"dd '".anti_injection($_POST['Login'])."' '".anti_injection($_POST['Password'])."'";
  $sqlqx = "SELECT * from $table_x
          WHERE Login = '".anti_injection($_POST['Login'])."'
          and LevelID = '".anti_injection($_POST['lev_id'])."' 
          and KodeID  = '".KodeID."' 
          and NA = 'N' 
          "; //and PasswordBro ='$pass' //and Password=LEFT(PASSWORD('".anti_injection($_POST['Password'])."'),10) limit 1"; // //and Password=LEFT(PASSWORD('".anti_injection($_POST['Password'])."'),10) limit 1
  $rows = mysqli_query($koneksi, $sqlqx);
  $datax = mysqli_fetch_array($rows);
  if (empty($datax)) {
    gagal_login();
  }
  else {
    //echo"Yes";
      $tahunak 	= mysqli_query($koneksi, "SELECT * FROM tahun order by TahunID DESC limit 1");
      $d 		    = mysqli_fetch_array($tahunak);
      $_SESSION['tahun_akademik'] = $d['TahunID'];
      $_SESSION['aksess']    	    = $datax['HeryAkses'];
    
      $sid = session_id();
      $_SESSION['_Login']     = $_POST['Login'];
      $_SESSION['_Nama']      = $datax['Nama'];
      $_SESSION['_TabelUser'] = $table_x;
      $_SESSION['_LevelID']   = $_POST['lev_id'];
      $_SESSION['_Session']   = $sid;
      $_SESSION['_Superuser'] = $datax['Superuser'];
      $_SESSION['_ProdiID']   = $datax['ProdiID'];
      $_SESSION['KodeID']     = $Organisasix;
      $_SESSION['_KodeID']    = $Organisasix;
      $_SESSION['ndelox']     = 'login';
      $_REQUEST['logn ']      = 'Sukses';
    echo "<script>window.location='?ndelox=dashboard&lungo=Sukses';</script>";
  }
}
function login_out() {
  Reset_Log_In();
  echo "<script>window.location='?ndelox=';</script>";
}
?>
