<?php
function DftrUsr() {
  $optkd = AmbilCombo2('identitas', "concat(Kode, ' - ', Nama)", 'Kode', $_SESSION['KodeID'], '', 'Kode');
  $optlvl = AmbilCombo2('level', "concat(LevelID, '. ', Nama)", 'LevelID', $_SESSION['LevelID'], '', 'LevelID');
  $cs = 6;
  echo "<p><table class=box cellspacing=1 cellpadding=4 align=center>
    <form action='?' method=POST>
    <input type=hidden name='ndelox' value='systemuser_all'>
    <tr><td class=ul>Company</td><td class=ul><select name='KodeID' onChange='this.form.submit()'>$optkd</select></td></tr>
    <tr><td class=ul>Level</td><td class=ul><select name='LevelID' onChange='this.form.submit()'>$optlvl</select></td></tr>
    </form></table></p>";
  echo DaftarUsr();
}
function DaftarUsr() {
	global $koneksi;
  if (!empty($_SESSION['LevelID'])) {
    $TabelUser = AmbilOneField('level', 'LevelID', $_SESSION['LevelID'], 'TabelUser');
    $s = "select t.*
      from $TabelUser t
      where LevelID='$_SESSION[LevelID]' 
	  and KodeID='$_SESSION[KodeID]'
      order by t.Login";
    $r = mysqli_query($koneksi, $s);
    $n = 0;
    $a = "<p>
    <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
    <table id='example1' class='table table-sm table-striped' style='width:100%' align='center'>
        <thead>
	  <tr >
      <td class=ul colspan=5><a class='btn btn-primary btn-xs' href='?ndelox=systemuser_all&lungo=EditUserx&LevelID=$_SESSION[LevelID]&md=1'>Tambah User</a></td></tr>
      <tr style='background:purple;color:white'>
      <th class=ttl>#</th><th class=ttl>Kode</th>
      <th class=ttl>Nama</th>
      <th class=ttl>Aktif</th>
			<th class=ttl>DEL</th></tr>
			</thead>
	<tbody>";
    while ($w = mysqli_fetch_array($r)) {
     if ($w['NA']=='N'){
        $stat="<i style='color:green' class='fa fa-eye'></i>";
      }else{
        $stat="<i style='color:red' class='fa fa-eye-slash'></i>";
      }
      $n++;
      $c = ($w['NA'] == 'Y')? 'class=nac' : 'class=ul';
      $a .= "<tr><td $c>$n</td>
        <td $c><a href='?ndelox=systemuser_all&lungo=EditUserx&LevelID=$_SESSION[LevelID]&md=0&Lgn=$w[Login]'><i class='fa fa-edit'></i>
        $w[Login]</a></td>
        <td $c>$w[Nama]</td>
        <td $c align=left>$stat</td>
				<td $c align=left><a href='?ndelox=systemuser_all&lungo=dltUsrCek&LevelID=$_SESSION[LevelID]&Lgn=$w[Login]'><i class='fa fa-trash'></i></td>
        </tr>";
    }
    return $a ."</tbody></table></p>";
  }
}
function EditUserx() {
  $md = $_REQUEST['md'] +0;
  $TabelUser = AmbilFieldx('level', 'LevelID', $_REQUEST['LevelID'], 'Nama, TabelUser');
  if ($md == 0) {
    $w = AmbilFieldx($TabelUser['TabelUser'], 'Login', $_REQUEST['Lgn'], '*');
    $jdl = "Edit User: $TabelUser[Nama]";
    $strlogin = "<input type=hidden name='Login' value='$w[Login]'><b>$w[Login]</b>";
  }
  else {
    $w = array();
    $w['Login'] = '';
    $w['Nama'] = '';
    $w['LevelID'] = $_REQUEST['LevelID'];
    $w['Telephone'] = '';
    $w['Password'] = '';
    $w['Handphone'] = '';
    $w['Email'] = '';
    $w['Alamat'] = '';
    $w['Kota'] = '';
    $w['Propinsi'] = '';
    $w['Negara'] = '';
    $w['ProdiID'] = '';
    $w['Local'] = 'N';
    $w['Export'] = 'N';
    $w['NA'] = 'N';
    $jdl = "Tambah User: $TabelUser[Nama]";
    $strlogin = "<input type=text name='Login' value='' size=30 maxlength=20>";
  }
  $na = ($w['NA'] == 'Y')? 'checked' : '';
  $_local = ($w['Local'] == 'Y')? 'checked' : '';
  $_export = ($w['Export'] == 'Y')? 'checked' : '';
  $snm = session_name(); $sid = session_id();
  $c1 = 'class=inp'; $c2 = 'class=ul';
  // tampilkan
  echo "<p>
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:50%' align='left'>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='systemuser_all'>
  <input type=hidden name='lungo' value='UsrSav'>
  <input type=hidden name='md' value='$md'>
  <input type=hidden name='LevelID' value='$_REQUEST[LevelID]'>
  <input type=hidden name='OldPwd' value='$w[Password]'>
  <tr><th class=ttl colspan=2>$jdl</th></tr>
  <tr><td $c1>Login Code</td><td $c2>$strlogin</td></tr>
  <tr><td $c1>Complete Name</td><td $c2><input type=text name='Nama' value='$w[Nama]' size=40 maxlength=50></td></tr>
  <tr><td $c1>Password</td><td $c2><input type=password name='Password' value='$w[Password]' size=20 maxlength=10></td></tr>
  <tr><td $c1>Access (Marketing)</td>
      <td $c2><input type=checkbox name='Local' value='Y' $_local> Local,
              <input type=checkbox name='Export' value='Y' $_export> Export
      </td></tr>
  <tr><td $c1>Telephone</td><td $c2><input type=text name='Telephone' value='$w[Telephone]' size=40 maxlength=50></td></tr>
  <tr><td $c1>Cellphone</td><td $c2><input type=text name='Handphone' value='$w[Handphone]' size=40 maxlength=50></td></tr>
  <tr><td $c1>Email</td><td $c2><input type=text name='Email' value='$w[Email]' size=40 maxlength=50></td></tr>
  <tr><td $c1>Address</td><td $c2><textarea name='Alamat' cols=30 rows=4>$w[Alamat]</textarea></td></tr>
  <tr><td $c1>City</td><td $c2><input type=text name='Kota' value='$w[Kota]' size=40 maxlength=50></td></tr>
  <tr><td $c1>Province</td><td $c2><input type=text name='Propinsi' value='$w[Propinsi]' size=40 maxlength=50></td></tr>
  <tr><td $c1>Country</td><td $c2><input type=text name='Negara' value='$w[Negara]' size=40 maxlength=50></td></tr>
  <tr><td $c1>NA (tidak aktif)?</td><td $c2><input type=checkbox name='NA' value='Y' $na></td></tr>
  <tr><td colspan=2 align=center><input type=submit name='Simpan' value='Simpan'>
    <input type=reset name='Reset' value='Reset'>
    <input type=button name='Batal' value='Batal' onClick=\"location='?ndelox=systemuser_all'\"></td></tr>

  </form></table></p>";
}
function UsrSav() {
	global $koneksi;
  $md = $_REQUEST['md']+0;
  $TabelUser = AmbilFieldx('level', 'LevelID', $_REQUEST['LevelID'], 'Nama, TabelUser');
  $Login = $_REQUEST['Login'];
  $Nama = sqling($_REQUEST['Nama']);
  $Password = $_REQUEST['Password'];
  $OldPwd = $_REQUEST['OldPwd'];
  $Telephone = $_REQUEST['Telephone'];
  $Handphone = $_REQUEST['Handphone'];
  $Email = $_REQUEST['Email'];
  $Alamat = sqling($_REQUEST['Alamat']);
  $Kota = sqling($_REQUEST['Kota']);
  $Propinsi = sqling($_REQUEST['Propinsi']);
  $Negara = sqling($_REQUEST['Negara']);
  $Local = (empty($_REQUEST['Local']))? 'N' : $_REQUEST['Local'];
  $Export = (empty($_REQUEST['Export']))? 'N' : $_REQUEST['Export'];
  $NA = (empty($_REQUEST['NA']))? 'N' : $_REQUEST['NA'];
  if ($md == 0) {
    $pwd = ($OldPwd == $Password)? '' : ", `Password`=LEFT(PASSWORD('$Password'), 10)";
    $s = "update $TabelUser[TabelUser] set Nama='$Nama', Telephone='$Telephone', Handphone='$Handphone',
      Email='$Email', Alamat='$Alamat', Kota='$Kota', Propinsi='$Propinsi', Negara='$Negara', NA='$NA'
      $pwd
      where Login='$Login' ";
    $r = mysqli_query($koneksi, $s);
    if (!empty($pwd)) echo Info('Perubahan Password', 'Telah terjadi perubahan password.').'<br>';
  }
  else {
    $ada = AmbilFieldx($TabelUser['TabelUser'], 'Login', $Login, 'Login, Nama');
    if (empty($ada)) {
      $s = "insert into $TabelUser[TabelUser] (Login, KodeID, LevelID, Nama, `Password`, Telephone, Handphone, Email,
        Alamat, Kota, Propinsi, Negara, ProdiID, NA)
        values('$Login', '$_SESSION[KodeID]', '$_REQUEST[LevelID]', '$Nama', LEFT(PASSWORD('$Password'), 10), '$Telephone', '$Handphone', '$Email',
        '$Alamat', '$Kota', '$Propinsi', '$Negara', '$ProdiID', '$NA')";
      mysqli_query($koneksi, $s);
    }
    else echo PesanError('Gagal Simpan', "Kode Login: <b>$Login</b> telah dipakai oleh user: <b>$ada[Nama]</b>.<br>
      Gunakan Kode Login yang lain.").'<br>';
  }
  DftrUsr();
}

function dltUsrCek(){
	$Login = $_REQUEST['Lgn'];
	$Akses = AmbilOneField('level', 'LevelID', $_REQUEST['LevelID'], 'Nama');
	echo Info("Delete User", "Yakin Anda ingin menghapus user : <br />
									Login : <b>$Login</b><br />
									Akses : <b>$Akses</b><br /><br />
									<input type=button name='hapus' value='Delete User' onClick=\"location='?ndelox=systemuser_all&lungo=dltUsr&Lgn=$Login'\">");
}

function dltUsr(){
	global $koneksi;
	$s = "delete from karyawan where Login = '$_REQUEST[Lgn]'";
	$r = mysqli_query($koneksi, $s);
	DftrUsr();
}

$_arrUsr = array('karyawan', 'dosen', 'mahasiswa');
$KodeID = GainVariabelx('KodeID');
$lungo = (empty($_REQUEST['lungo']))? 'DftrUsr' : $_REQUEST['lungo'];
$LevelID = GainVariabelx('LevelID');

TitleApps('KONFIGURASI ADMINISTRATOR');
$lungo();
?>
