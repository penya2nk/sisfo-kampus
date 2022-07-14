<?php
error_reporting(0);
session_start();
include_once "../academic_sisfo1.php";

ViewHeaderApps("Edit BIPOT Mhsw");

$MhswID = GainVariabelx('MhswID');

TitleApps("Edit BIPOT Mhsw");
$lungo = (empty($_REQUEST['lungo']))? "Edit" : $_REQUEST['lungo'];
$lungo();

function Edit() {
  $mhsw = AmbilFieldx('mhsw', "KodeID='".KodeID."' and MhswID", $_SESSION['MhswID'], '*');
  $optbpt = AmbilCombo2('bipot', 'Tahun', 'Tahun Desc', $mhsw['BIPOTID'],
    "KodeID='".KodeID."' and ProgramID='$mhsw[ProgramID]' and ProdiID='$mhsw[ProdiID]'",
    'BIPOTID');
  echo <<<ESD
  <table class=bsc width=100%>
  <form action='../$_SESSION[ndelox].bipotmhsw.php' method=POST>
  <input type=hidden name='lungo' value='Simpan' />
  <input type=hidden name='MhswID' value='$_SESSION[MhswID]' />
  
  <tr><td class=inp>NIM:</td>
      <td class=ul1><b>$mhsw[MhswID]</b></td>
      </tr>
  <tr><td class=inp>Mahasiswa:</td>
      <td class=ul1><b>$mhsw[Nama]</b></td>
      </tr>
  <tr><td class=inp>Angkatan:</td>
      <td class=ul1><b>$mhsw[TahunID]</b></td>
      </tr>
  <tr><td class=inp>Bipot:</td>
      <td class=ul1>
      <select name='BIPOTID'>$optbpt</select>
      </td></tr>
  <tr><td class=ul1 colspan=2 align=center>
      <input type=submit name='Simpan' value='Simpan' />
      <input type=button name='Batal' value='Batal'
        onClick="window.close()" />
      </td></tr>
  
  </form>
  </table>
ESD;
}
function Simpan() {
	global $koneksi;
  $MhswID = sqling($_REQUEST['MhswID']);
  $BIPOTID = $_REQUEST['BIPOTID'];
  // Simpan
  $s = "update mhsw
    set BIPOTID = '$BIPOTID',
        LoginEdit = '$_SESSION[_Login]',
        TanggalEdit = now()
    where KodeID = '".KodeID."' and MhswID = '$MhswID' ";
  $r = mysqli_query($koneksi, $s);
  TutupScript($MhswID);
}
function TutupScript($MhswID) {
echo <<<SCR
<SCRIPT>
  function ttutup() {
    opener.location='../index.php?ndelox=$_SESSION[ndelox]&MhswID=$MhswID';
    self.close();
    return false;
  }
  ttutup();
</SCRIPT>
SCR;
}
?>
