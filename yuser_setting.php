<?php

function ViewFormYuser() {
  EditUserPassword($_SESSION['_TabelUser'], $_SESSION['_Login'], 'yuser_setting', 'lungo', 'SimpanPassbro');
  if ($_SESSION['_LevelID'] != 120)
  EditProfilPengguna($_SESSION['_TabelUser'], $_SESSION['_Login'], 'yuser_setting', 'lungo', 'SimpanPref');
}
function SimpanPref() {
  SimpanProfilPengguna();
  ViewFormYuser();
}
function SimpanPassbro() {
  SimpanUserPengguna();
  ViewFormYuser();
}
function EditUserPassword($tbl, $lgn, $ndelox, $lungo, $gosval) {
  // JavaScript
  /*
      var pjg = frm.PWD1.value.length;
    if (pjg != 6) alert('Panjang password harus 6 karakter');
    var hsl = false;
    hsl = pjg == 6;
    if (hsl) {
      hsl = frm.PWD1.value == frm.PWD2.value;
      if (!hsl) alert('Password dan Info Password tidak sama.');
    }
    return hsl;
  */
  echo "
  <SCRIPT LANGUAGE=\"JavaScript1.2\">
  <!--
  function CheckPWD(form) {
    strs = \"\";
    if (form.PWD1.value == \"\") strs += \"Password tidak boleh KOSONG.\\n\";
    if (form.PWD1.value != form.PWD2.value) strs += \"Password harus sama dengan Password Info.\\n\";
    var pjg = form.PWD1.value.length;
    if (pjg < 6) strs += \"Minimal Panjang password harus 6 karakter.\\n\";
    if (strs != \"\") alert(strs);
    return strs == \"\";
  }
  -->
  </SCRIPT>\n";

  // Tuliskan formulir
  echo "<p><div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id='example' class='table table-sm table-striped' style='width:50%' align='left'>
  <form action='?' method=POST onSubmit=\"return CheckPWD(this);\">
  <input type=hidden name='ndelox' value='$ndelox'>
  <input type=hidden name='$lungo' value='$gosval'>
  <input type=hidden name='TabelUser' value='$tbl'>
  <input type=hidden name='LoginUser' value='$lgn'>
   <tr style='background:purple;color:white'><td colspan='2'><b>Gunakan kombinasi angka, huruf, simbol dan panjang lebih dari 4 karakter</b></td></tr>
  <tr><th class=ttl colspan=2>Edit Password</th></tr>
  <tr><td class=inp1 width='200'>Password Baru</td><td class=ul><input type=password name='PWD1' size=20 maxlength=20></td></tr>
  <tr><td class=inp1>Konfirm Password Baru</td><td class=ul><input type=password name='PWD2' size=20 maxlength=20></td></tr>
  <tr><td colspan=2><input class='btn btn-success btn-sm' type=submit name='Submit' value='Simpan'>
    <input class='btn btn-primary btn-sm' type=reset value='Reset'></td></tr>
  </form></table>
  </div>
</div>
</div>
</p>";
}
function SimpanUserPengguna() {
	global $koneksi;
  $b				      = md5($_POST['PWD1']);
  $PasBaruEnkrip	= hash("sha512",$b);

  $tbl = $_REQUEST['TabelUser'];
  $lgn = $_REQUEST['LoginUser'];
  $PWD = $_REQUEST['PWD1'];
  //$s = "update $tbl set `Password`=LEFT(PASSWORD('$PWD'), 10) where Login='$lgn' ";
  $s = "update $tbl set `PasswordBro`='$PasBaruEnkrip' where Login='$lgn' ";
  $r = mysqli_query($koneksi, $s);
  echo "<script>alert('Password telah berhasil diperbaharui.');</script>";
}

$lungo = (empty($_REQUEST['lungo']))? 'ViewFormYuser' : $_REQUEST['lungo'];

TitleApps("MANAJEMEN USER PASSWORD");
$lungo();
?>
