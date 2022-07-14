<?php
// error_reporting(0);
if ($_SESSION['_LevelID'] == 1) {
  $_DosenID = GainVariabelx('_DosenID');
}
elseif ($_SESSION['_LevelID'] == 100) {
  $_DosenID = $_SESSION['_Login'];
}
else die(PesanError('Error',
  "Anda tidak berhak menjalankan modul ini."));

TitleApps("PERUBAHAN PASSWORD DOSEN");
$lungo = (empty($_REQUEST['lungo']))? 'frmPwd' : $_REQUEST['lungo'];
$lungo($_DosenID);

function frmPwd($_DosenID) {
  if ($_SESSION['_LevelID'] == 1) {
    $ro = '';
  }
  else {
    $ro = "readonly=true";
  }
  $dsn = AmbilFieldx('dosen', "KodeID='".KodeID."' and Login", $_DosenID,
    "Login, Nama, ProdiID, `Password`");
  echo <<<ESD
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:50%' align='left'>
  <form name='frmPwd' action='?' method=POST onSubmit="return CheckPassword(frmPwd)">
  <input type=hidden name='lungo' value='SimpanPwd' />

  <tr style='background:purple;color:white'><td colspan='2'><b>Gunakan kombinasi angka, huruf, simbol dan panjang lebih dari 4 karakter</b></td></tr>  
<tr> 
<td class=inp width=120>Kode Login:</td>
<td class=ul width=120><input type=text name='_DosenID' value='$_DosenID' size=20 maxlength=50 $ro /></td>
</tr>

<tr>      
<td class=inp width=120>Nama Dosen</td>
<td class=ul><b>$dsn[Nama]</b>&nbsp;</td> 
</tr>

 <tr>
 <td class=inp valign=top>Password Baru</td>
 <td class=ul valign=top><input type=password name='PWD1' size=20 maxlength=10 /></td>
 </tr>

<tr> 
<td class=inp valign=top>Password Baru</td>
<td class=ul valign=top><input type=password name='PWD2' size=20 maxlength=10 /><br />*) tuliskan password baru sekali lagi</td>
</tr>

<tr>
<td class=ul colspan=2 align=left><input class='btn btn-danger btn-sm' type=submit name='Simpan' value='Simpan Password Baru' /></td>
</tr>
  
  </form>
  </table>
  </div>
</div>
</div>
  
  <script>
  function CheckPassword(frm) {
    var pesan = "";
    if (frm.PWD1.value == '' || frm.PWD2.value == '')
      pesan += "Password tidak boleh kosong. \\n";
    if (frm.PWD1.value.length < 4)
      pesan += "Password harus lebih dari 4 karakter. \\n";
    if (frm.PWD1.value != frm.PWD2.value)
      pesan += "Ketikkan kembali password baru. \\n";
    if (pesan != "") alert(pesan);
    return pesan == "";
  }
  </script>
ESD;
}
function SimpanPwd($_DosenID) {
  global $koneksi;
  $_DosenID = sqling($_POST['_DosenID']);
  $PWD1 = sqling($_POST['PWD1']);
  $PWD2 = sqling($_POST['PWD2']);
  //leweh ----------------------------------------
  $b				= md5($PWD1);
  $PasBaruEnkrip	= hash("sha512",$b);
  //end leweh  -----------------------------------
  $s = "update dosen
        set `PasswordBro` ='$PasBaruEnkrip',
        LevelID = '100' 
        where KodeID = '".KodeID."'
        and Login = '$_DosenID' ";
  $r = mysqli_query($koneksi, $s); //`Password`=LEFT(PASSWORD('$PWD1'), 10),
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=&_DosenID=$_DosenID", 1); 
}
?>
