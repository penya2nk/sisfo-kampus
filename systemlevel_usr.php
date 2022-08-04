<?php
$_arrUsr = array(100, 120);
$KodeID = GainVariabelx('KodeID');
$LevelID = GainVariabelx('LevelID', 1);

TitleApps('ADMINISTRASI PENGGUNA');
$lungo = (empty($_REQUEST['lungo']))? 'DftrUsr' : $_REQUEST['lungo'];
$lungo();


function DftrUsr() {
  global $_arrUsr, $koneksi;
  $_arr = implode(',', $_arrUsr);
   if (!empty($_SESSION['_LevelID'])) {
    $optlvl = AmbilCombo2('level', "concat(LevelID, '. ', Nama)", 'LevelID', $_SESSION['LevelID'], 
      "Tampak = 'Y' and Accounting = 'N' and not (LevelID in ($_arr))", 'LevelID');
    $a = "<p>
    <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
      <table id='example1' class='table table-sm table-striped' style='width:100%' align='center'>
       <thead>
	  <form action='?' method=POST>
      <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
     
      <tr><td class=ul1 colspan=10>
          Level: <select  name='LevelID' onChange='this.form.submit()'>$optlvl</select>
          <a class='btn btn-success btn-xs' href='?ndelox=$_SESSION[ndelox]&lungo=EditUser&LevelID=$_SESSION[LevelID]&md=1'> Tambah User</a>
          <a class='btn btn-info  btn-xs' href='?ndelox=$_SESSION[ndelox]&lungo='>Refresh</a>
          </td>
          </form></tr>

      <tr style='background:purple;color:white'><th class=ttl>#</th>
          <th class=ttl>Kode</th>
          <th class=ttl>Nama</th>
          <th class=ttl>Program Studi</th>
          <th class=ttl>NA</th>
          <th class=ttl>Hapus</th>
      </tr></thead>
	<tbody>";
    $TabelUser = AmbilOneField('level', 'LevelID', $_SESSION['LevelID'], 'TabelUser');
    $s = "select t.Login, t.Nama, t.ProdiID, t.NA
      from $TabelUser t
      where LevelID='$_SESSION[LevelID]' and KodeID='$_SESSION[KodeID]'
      order by t.Login";
    $r = mysqli_query($koneksi, $s);
    $n = 0;
    
    while ($w = mysqli_fetch_array($r)) {
      $n++;
      if ($w['NA']=='N'){
        $Stat="<i style='color:green' class='fa fa-eye'></i>";
      }else{
        $Stat="<i style='color:red' class='fa fa-eye-slash'></i>";
      }
      $c = ($w['NA'] == 'Y')? 'class=nac' : 'class=ul';
      $a .= "<tr>
        <td class=inp width=20>$n</td>
        <td $c width='80'><a href='?ndelox=$_SESSION[ndelox]&lungo=EditUser&LevelID=$_SESSION[LevelID]&md=0&Lgn=$w[Login]'><i class='fa fa-edit'></i>
        $w[Login]</a></td>
        <td $c width='150'>$w[Nama]</td>
        <td $c width='350'>$w[ProdiID]&nbsp;</td>
        <td $c align=left width=5>$Stat</td>
	<td $c align=left width=5><a href='?ndelox=$_SESSION[ndelox]&lungo=dltUsrCek&LevelID=$_SESSION[LevelID]&Lgn=$w[Login]'><i class='fa fa-trash'></i></td>
        </tr>";
    }
    echo $a ."</tbody></table>
    </div>
</div>
</div></p>";
  }
}

function EditUser() {
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
    $w['NA'] = 'N';
    $jdl = "Tambah User: $TabelUser[Nama]";
    $strlogin = "<input type=text name='Login' value='' size=30 maxlength=20>";
  }
  $na = ($w['NA'] == 'Y')? 'checked' : '';
  $snm = session_name(); $sid = session_id();
  $cb_prodi = AmbilCekBox('prodi', 'ProdiID', "concat(ProdiID, ' - ', Nama) as PRD", 'PRD', $w['ProdiID'], ',');
  $c1 = 'class=inp'; $c2 = 'class=ul';
  // tampilkan
  CheckFormScript('Login,Nama');
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:100%' align='center'>
  <form action='?' method=POST onSubmit='return CheckForm(this)'>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='BypassMenu' value='1' />
  <input type=hidden name='lungo' value='UsrSav' />
  <input type=hidden name='md' value='$md' />
  <input type=hidden name='LevelID' value='$_REQUEST[LevelID]' />
  <input type=hidden name='OldPwd' value='$w[Password]' />
  
   <tr style='background:purple;color:white'><td colspan='3'><b>Gunakan kombinasi angka, huruf, simbol dan panjang lebih dari 4 karakter</b></td></tr>
  <tr><th class=ttl colspan=3>$jdl</th></tr>
  <tr><td $c1>Kode Login</td>
      <td $c2>$strlogin</td>
      <th class=ttl>Hak Akses Prodi:</th>
      </tr>
  <tr><td $c1>Nama User</td>
      <td $c2><input type=text name='Nama' value='$w[Nama]' size=40 maxlength=50></td>
      <td $c2 rowspan=12 valign=top>$cb_prodi</td>
      </tr>
  <tr><td $c1>Password</td><td $c2><input type=password name='Password' value='$w[Password]' size=20 maxlength=10></td></tr>
  <tr><td $c1>Telepon</td><td $c2><input type=text name='Telephone' value='$w[Telephone]' size=40 maxlength=50></td></tr>
  <tr><td $c1>Handphone</td><td $c2><input type=text name='Handphone' value='$w[Handphone]' size=40 maxlength=50></td></tr>
  <tr><td $c1>Email</td><td $c2><input type=text name='Email' value='$w[Email]' size=40 maxlength=50></td></tr>
  <tr><td $c1>Alamat</td><td $c2><textarea name='Alamat' cols=30 rows=4>$w[Alamat]</textarea></td></tr>
  <tr><td $c1>Kota</td><td $c2><input type=text name='Kota' value='$w[Kota]' size=40 maxlength=50></td></tr>
  <tr><td $c1>Propinsi</td><td $c2><input type=text name='Propinsi' value='$w[Propinsi]' size=40 maxlength=50></td></tr>
  <tr><td $c1>Negara</td><td $c2><input type=text name='Negara' value='$w[Negara]' size=40 maxlength=50></td></tr>
  <tr><td $c1>NA (tidak aktif)?</td><td $c2><input type=checkbox name='NA' value='Y' $na></td></tr>
  <tr><td $c2 colspan=2 align=center>
      <input class='btn btn-success' type=submit name='Simpan' value='Simpan'>
      <input class='btn btn-primary' type=reset name='Reset' value='Reset'>
      <input class='btn btn-danger' type=button name='Batal' value='Batal' onClick=\"location='?ndelox=systemlevel_usr'\">
      </td></tr>

  </form></table>
  </div>
</div>
</div></p>";
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
  $arrProdiID = array();
  $arrProdiID = $_REQUEST['ProdiID'];
  $ProdiID = (empty($arrProdiID))? '' : implode(',', $arrProdiID);
  // leweh
  $b				= md5($_POST['Password']);
  $PasBaruEnkrip	= hash("sha512",$b);
  // end leweh
  $NA = (empty($_REQUEST['NA']))? 'N' : $_REQUEST['NA'];
  if ($md == 0) {
    //$pwd = ($OldPwd == $Password)? '' : ", Password=PASSWORD('$Password')";
	$pwd = ($OldPwd == $Password)? '' : ", PasswordBro='$PasBaruEnkrip'";
    $s = "update $TabelUser[TabelUser] set Nama='$Nama', Telephone='$Telephone', Handphone='$Handphone',
      Email='$Email', Alamat='$Alamat', Kota='$Kota', Propinsi='$Propinsi', Negara='$Negara', NA='$NA',
      ProdiID='$ProdiID' $pwd
      where Login='$Login' ";
    $r = mysqli_query($koneksi, $s);
    if (!empty($pwd)) echo Info('Perubahan Password', 'Telah terjadi perubahan password.').'<br>';
    SuksesTersimpan("?ndelox=$_SESSION[ndelox]", 1);
  }
  else {
    $ada = AmbilFieldx($TabelUser['TabelUser'], 'Login', $Login, 'Login, Nama');
    if (empty($ada)) {
      $s = "insert into $TabelUser[TabelUser] (Login, KodeID, LevelID, Nama, Password, Telephone, Handphone, Email, 
        Alamat, Kota, Propinsi, Negara, ProdiID, NA)
        values('$Login', '$_SESSION[KodeID]', '$_REQUEST[LevelID]', '$Nama', PASSWORD('$Password'), '$Telephone', '$Handphone', '$Email',
        '$Alamat', '$Kota', '$Propinsi', '$Negara', '$ProdiID', '$NA')";
      $r = mysqli_query($koneksi, $s);
      SuksesTersimpan("?ndelox=$_SESSION[ndelox]", 1);
    }
    else echo PesanError('Gagal Simpan', "Kode Login: <b>$Login</b> telah dipakai oleh user: <b>$ada[Nama]</b>.<br>
      Gunakan Kode Login yang lain.").'<br>';
  }
}

function dltUsrCek(){
	$Login = $_REQUEST['Lgn'];
	$Akses = AmbilOneField('level', 'LevelID', $_REQUEST['LevelID'], 'Nama');
	echo Info("Delete User", "Yakin Anda ingin menghapus user : <br />
									Login : <b>$Login</b><br />
									Akses : <b>$Akses</b><br /><br />
									<input type=button name='hapus' value='Delete User' onClick=\"location='?ndelox=systemlevel_usr&lungo=dltUsr&Lgn=$Login'\">");
}

function dltUsr(){
	global $koneksi;
	$s = "delete from karyawan where Login = '$_REQUEST[Lgn]'";
	$r = mysqli_query($koneksi, $s);
	DftrUsr();
}


?>
