<?php
error_reporting(0);
if ($_SESSION['_LevelID'] == 1) {
  $_MhswID = GainVariabelx('_MhswID');
}
elseif ($_SESSION['_LevelID'] == 120) {
  $_MhswID = $_SESSION['_Login'];
}
else die(PesanError('Error',
  "Anda tidak berhak menjalankan modul ini."));

TitleApps("PERUBAHAN PASSWORD MAHASISWA");
$lungo = (empty($_REQUEST['lungo']))? 'frmPwd' : $_REQUEST['lungo'];
$lungo($_MhswID);

function frmPwd($_MhswID) {
  if ($_SESSION['_LevelID'] == 1) {
    $_NIM = "<input type=text name='_MhswID' value='$_MhswID' size=20 maxlength=50 />"; 
  }
  else {
    $_NIM = "<input type=hidden name='_MhswID' value='$_MhswID' /><b>$_MhswID</b>";
  }
  $mhsw = AmbilFieldx('mhsw', "KodeID='".KodeID."' and MhswID", $_MhswID,
    "MhswID, Nama, ProdiID, `Password`");
  echo <<<ESD
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:80%' align='left'>
  <form name='frmPwd' action='?' method=POST onSubmit="return CheckPassword(frmPwd)">
  <input type=hidden name='lungo' value='SimpanSandi' />
  <tr style='background:purple;color:white'>
  <td colspan='2'><b>Gunakan kombinasi angka, huruf, simbol dan panjang lebih dari 4 karakter</b></td>
  </tr>
  
  <tr>
  <td class=inp style='width:20px'>NIM</td>
  <td class=ul width=80>$_NIM</td>
  </tr>
  
  <tr>
  <td class=inp >Nama Mahasiswa</td>
  <td class=ul><b>$mhsw[Nama]</b>&nbsp;</td>
  </tr>
  
  <tr>
  <td class=inp valign=top>Password Baru</td>
  <td class=ul valign=top><input type=password name='PWD1' size=20 maxlength=10 /></td>
  </tr>
  
  <tr>
      <td class=inp valign=top>Password Baru</td><td class=ul valign=top><input type=password name='PWD2' size=20 maxlength=10 />
      *) Inputkan kembali password baru
      </td>
  </tr>

  <tr>
  <td class=inp valign=top colspan='2'>
  <input class='btn btn-danger btn-sm' type=submit name='Simpan' value='Simpan Password Baru' />
  </td>
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
      pesan += "Ketikkan password baru 2 kali dengan benar. \\n";
    if (pesan != "") alert(pesan);
    return pesan == "";
  }
  </script>
ESD;
}
function SimpanSandi($_MhswID) {
  global $koneksi;
  $_MhswID = sqling($_REQUEST['_MhswID']);
  $PWD1 = sqling($_REQUEST['PWD1']);
  $PWD2 = sqling($_REQUEST['PWD2']);
  
  //leweh ----------------------------------------
  $b			  = md5($PWD1);
  $PasBaruEnkrip  = hash("sha512",$b);
  //end leweh  -----------------------------------
  $s = "update mhsw 
    set `PasswordBro`= '$PasBaruEnkrip' 
    where KodeID  = '".KodeID."'
    and MhswID    = '$_MhswID' ";
  $r = mysqli_query($koneksi, $s); //LEFT(PASSWORD('$PWD1'), 10) 
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=&_MhswID=$_MhswID", 1000); 
}
?>
