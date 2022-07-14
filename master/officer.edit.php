<?php
error_reporting(0);
session_start();
include_once "../academic_sisfo1.php";

ViewHeaderApps("Pejabat Perguruan Tinggi", 1);

$md = $_REQUEST['md']+0;
$id = $_REQUEST['id']+0;

$lungo = (empty($_REQUEST['lungo']))? 'EditData' : $_REQUEST['lungo'];
$lungo($md, $id);

function EditData($md, $id) {
    global $koneksi;
  if ($md == 0) {
    $jdl = "Edit Data Pejabat";
    //$w = AmbilFieldx('pejabat', 'PejabatID', $id, '*');
    $w = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from pejabat where PejabatID='".strfilter($id)."'"));
	$ro = "readonly=true";
  }
  elseif ($md == 1) {
    $jdl = "Tambah Pejabat";
    $w = array();
    $w['NA'] = 'N';
    $w['Urutan'] = AmbilOneField('pejabat', "KodeID", KodeID, "max(Urutan)")+1;
	$ro = "";
  }
  else die(PesanError('Error',
    "Terjadi kesalahan mode edit.<br />
    Mode edit $md tidak dikenali.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    <input type=button name='Tutup' value='Tutup'
    onClick=\"window.close()\" />"));
  $NA = ($w['NA'] == 'Y')? 'checked' : '';
  // tampilkan form
  CheckFormScript("KodeJabatan,Urutan,Jabatan");
  TitleApps($jdl);
  echo "
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table class=bsc cellspacing=1 width=100%>
  <form action='?ndelox=$_SESSION[ndelox]' method=POST onSubmit=\"return CheckForm(this)\">
  <input type=hidden name='lungo' value='Simpan' />
  <input type=hidden name='md' value='$md' />
  <input type=hidden name='id' value='$id' />
  
  <tr><td class=inp>Urutan:</td>
      <td class=ul><input type=text name='Urutan' value='$w[Urutan]' size=3 maxlength=4 $ro/></td>
      </tr>
  <tr><td class=inp>Kode Jabatan:</td>
      <td class=ul><input type=text name='KodeJabatan' value='$w[KodeJabatan]'
        size=20 maxlength=50 $ro/><br />
        (*) Digunakan oleh sistem utk laporan<sup>2</sup>
      </td></tr>
  <tr><td class=inp>Nama Pejabat:</td>
      <td class=ul><input type=text name='Nama' value='$w[Nama]'
        size=30 maxlength=50 />
      </td>
      </tr>
  <tr><td class=inp>NIP:</td>
      <td class=ul><input type=text name='NIP' value='$w[NIP]'
        size=30 maxlength=50 />
      </td></tr>
  <tr><td class=inp>Jabatan:</td>
      <td class=ul><input type=text name='Jabatan' value='$w[Jabatan]'
        size=30 maxlength=75 />
      </td>
      </tr>
  <tr><td class=inp>NA (tidak aktif)?</td>
      <td class=ul><input type=checkbox name='NA' value='Y' $NA />
      </td></tr>
  <tr><td class=ul1 colspan=2 align=center>
      <input type=submit name='Simpan' value='Simpan' />
      <input type=button name='Batal' value='Batal'
        onClick=\"window.close()\" />
      </td></tr>
  
  </form>
  </table>";
}
function Simpan($md, $id) {
	global $koneksi;
  $Urutan = $_REQUEST['Urutan']+0;
  $KodeJabatan = sqling($_REQUEST['KodeJabatan']);
  $Nama = sqling($_REQUEST['Nama']);
  $NIP = sqling($_REQUEST['NIP']);
  $Jabatan = sqling($_REQUEST['Jabatan']);
  $NA = (empty($_REQUEST['NA']))? 'N' : $_REQUEST['NA'];
  // Simpan
  if ($md == 0) {
    $s = "update pejabat
      set Urutan = '$Urutan',
          KodeJabatan = '$KodeJabatan',
          Nama = '$Nama',
          NIP = '$NIP',
          Jabatan = '$Jabatan',
          NA = '$NA',
          TglEdit = now(),
          LoginEdit = '$_SESSION[_Login]'
      where PejabatID = $id ";
    $r = mysqli_query($koneksi, $s);
    TutupScript();
  }
  elseif ($md == 1) {
    $s = "insert into pejabat
      (KodeID, Urutan, KodeJabatan, Nama, NIP, Jabatan,
      NA, TglBuat, LoginBuat)
      values
      ('".KodeID."', '$Urutan', '$KodeJabatan', '$Nama', '$NIP', '$Jabatan',
      '$NA', now(), '$_SESSION[_Login]')";
    $r = mysqli_query($koneksi, $s);
    TutupScript();
  }
  else die(PesanError('Error',
    "Mode edit tidak dikenali.<br />
    <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" />"));
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
