<?php
session_start();
include_once "../sisfokampus1.php";

ViewHeaderApps("Edit Status Mhsw");

$KHSID = GainVariabelx('KHSID');
$khs = AmbilFieldx('khs', 'KHSID', $KHSID, '*');
if (empty($khs))
  die(PesanError('Error',
    "Data semester tidak ditemukan.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    <input type=button name='Tutup' value='Tutup'
      onClick='window.close()' />"));

TitleApps("Ubah Status Mhsw");
$lungo = (empty($_REQUEST['lungo']))? 'frmStatus' : $_REQUEST['lungo'];
$lungo();

function frmStatus() {
  $sta = AmbilOneField('khs', 'KHSID', $_SESSION['KHSID'], 'StatusMhswID');
  $optsta = AmbilCombo2('statusmhsw', "concat(StatusMhswID, ' - ', Nama)",
    'StatusMhswID', $sta, '', 'StatusMhswID');
  echo <<<ESD
  <form action='../$_SESSION[ndelox].statusmhsw.php' method=POST>
  <input type=hidden name='KHSID' value='$_SESSION[KHSID]' />
  <input type=hidden name='lungo' value='Simpan' />
  
  <p align=center>
  Status Mahasiswa:<br />
  <select name='StatusMhswID'>$optsta</select>
  </p>
  <hr size=1 color=silver />
  <p align=center>
  <input type=submit name='Simpan' value='Simpan' />
  <input type=button name='Batal' value='Batal'
    onClick='window.close()' />
  </p>
  </form>
  
ESD;
}
function Simpan() {
	global $koneksi;
  $KHSID = $_REQUEST['KHSID'];
  $StatusMhswID = sqling($_REQUEST['StatusMhswID']);
  // Simpan
  $s = "update khs set StatusMhswID = '$StatusMhswID'
    where KHSID = '$KHSID' ";
  $r = mysqli_query($koneksi, $s);
  // Tutup
  TutupScript();
}
function TutupScript() {
echo <<<SCR
<SCRIPT>
  function ttutup() {
    opener.location='../index.php?ndelox=$_SESSION[ndelox]&lungo=';
    self.close();
    return false;
  }
  ttutup();
</SCRIPT>
SCR;
}
?>
