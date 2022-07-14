
<?php 
if (isset($_POST[ubahdata])){			
	$s = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,PasswordBro FROM mhsw where Login='$_SESSION[_Login]'"));
	$p 			= md5($_POST[pLama]);
	$pswLama	= hash("sha512",$p);
	if ($s[PasswordBro]==$pswLama){
		if ($_POST[pasbaru1]==$_POST[pasbaru2]){			
			$b				= md5($_POST[pasbaru1]);
			$PasBaruEnkrip	= hash("sha512",$b);
			$query  		= mysqli_query("UPDATE mhsw SET PasswordBro='$PasBaruEnkrip', LoginEdit ='$_SESSION[_Login]', TanggalEdit ='".date('Y-m-d H:i:s')."' where Login='$_SESSION[_Login]'");
	        echo "Perubahan Password Berhasil!";
		}else{
		    echo"Info Password Baru tidak sama!";
		}
	}else{
		echo"Anda salah memasukkan Password Lama!";
	}	
}
	
echo "<div class='col-md-6'>
<div class='card'>
<div class='card-header'>
<div class='col-md-12'>
<div class='box box-info'>
<h3 class='box-title'><b style=color:green;font-size=18px>Change Password</b></h3>
</div>
<div class='box-body'>
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
<div class='col-md-12'>
<table class='table table-condensed table-bordered'>
<tbody>
<tr><th scope='row'>Masukkan Password Lama</th><td><input type='password' placeholder='Password' class='form-control' name='pLama'></td></tr>
<tr><th scope='row'>Masukkan Passwod Baru</th><td><input type='password' placeholder='Password Baru' class='form-control' name='pasbaru1'></td></tr>
<tr><th scope='row'>Masukkan Kembali Password Baru</th><td><input type='password' placeholder='Info Password Baru' class='form-control' name='pasbaru2'></td></tr>
</tbody>
</table>
</div>


<div class='box-footer'>
<button type='submit' name='ubahdata' class='btn btn-info'>Change Password</button>
<a href='index.php'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
</div>
</form>";

?>