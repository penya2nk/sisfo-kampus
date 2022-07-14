<?php
$sub = (empty($_REQUEST['sub']))? 'frmBank' : $_REQUEST['sub'];
$sub();

function frmBank() {
  global $datamhsw, $ndelox, $pref;
  $ad = ($datamhsw['Autodebet'] == 'Y')? 'checked' : '';
  echo "<p><table id='example' class='table table-sm table-striped' style='width:70%' align='center'>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$ndelox'>
  <input type=hidden name='mhswid' value='$datamhsw[MhswID]'>
  <input type=hidden name='submodul' value='$_SESSION[submodul]' />
  <input type=hidden name='sub' value='BankSav' />
  <input type=hidden name='BypassMenu' value='1' />

  <tr style='background:purple;color:white'><td class=ul colspan=2><b>AUTO</b></td></tr>
  <tr><td class=ul colspan=2>Autodebet adalah fasilitas keuangan mahasiswa yang meng-enable
    pen-debetan secara otomatis keuangan mahasiswa sehingga mahasiswa tidak perlu
    membayar semua biaya kuliah secara manual ke bank.</td></tr>
  <tr><td class=inp>Autodebet</td><td class=ul><input type=checkbox name='Autodebet' value='Y' $ad></td></tr>
  <tr><td class=inp>Nama Bank</td><td class=ul><input type=text name='NamaBank' value='$datamhsw[NamaBank]' size=40 maxlength=50></td></tr>
  <tr><td class=inp>Nomer Rekening</td><td class=ul><input type=text name='NomerRekening' value='$datamhsw[NomerRekening]' size=40 maxlength=50></td></tr>
  <tr>
   <td class=ul colspan=2 align=left>
	<input class='btn btn-success btn-sm' type=submit name='Simpan' value='Simpan'>
    <input class='btn btn-primary btn-sm' type=reset name='Reset' value='Reset'>
	</td>
   </tr>
  </form></table></p>";
}

function BankSav() {
	global $koneksi;
  $Autodebet = (empty($_REQUEST['Autodebet']))? 'N' : $_REQUEST['Autodebet'];
  $NamaBank = sqling($_REQUEST['NamaBank']);
  $NomerRekening = sqling($_REQUEST['NomerRekening']);
  $s = "update mhsw set Autodebet='$Autodebet',
    NamaBank='$NamaBank', NomerRekening='$NomerRekening'
    where MhswID='$_REQUEST[mhswid]' ";
  $r = mysqli_query($koneksi, $s);
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&submodul=$_SESSION[submodul]", 10);
}

?>
