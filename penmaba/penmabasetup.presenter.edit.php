<?php
error_reporting(0);
session_start();
include_once "../academic_sisfo1.php";

ViewHeaderApps("USM");
echo $_Themes;

$md = $_REQUEST['md']+0;
$id = sqling($_REQUEST['id']);
$bck = sqling($_REQUEST['bck']);

$lungo = (empty($_REQUEST['lungo']))? 'Edit' : $_REQUEST['lungo'];
$lungo($md, $id, $bck);

function Edit($md, $id, $bck) {
  if ($md == 0) {
    $jdl = "Edit Presenter";
    $w = AmbilFieldx('presenter', "KodeID='".KodeID."' and PresenterID", $id, "*");
	$ro = "readonly=true";
  }
  elseif ($md == 1) {
    $jdl = "Tambah Presenter";
    $w = array();
    $w['NA'] = 'N';
	$ro = '';
  }
  else die(PesanError('Error', "Mode edit tidak dikenali."));
  
  TitleApps($jdl);
  // Parameters
  $na = ($w['NA'] == 'Y')? 'checked' : '';
  CheckFormScript("id,Nama");
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table class=bsc cellspacing=1 align=center width=100%>
  <form action='../$_SESSION[ndelox].presenter.edit.php' method=POST onSubmit=\"return CheckForm(this)\">
  <input type=hidden name='lungo' value='Simpan' />
  <input type=hidden name='idlama' value='$w[PresenterID]' />
  <input type=hidden name='md' value='$md' />
  <input type=hidden name='bck' value='$bck' />
  
  <tr><td class=inp>Kode:</td>
      <td class=ul1><input type=text name='id' value='$w[PresenterID]' size=5></td>
      </tr>
  <tr><td class=inp>Nama:</td>
      <td class=ul1><input type=text name='Nama' value='$w[Nama]' size=40>
      </td></tr>
  <tr><td class=inp>NA (tidak aktif)?</td>
      <td class=ul1>
      <input type=checkbox name='NA' value='Y' $na /> *) Beri centang jika tidak aktif
      </td>
      </tr>
  <tr><td class=ul1 colspan=2 align=center>
      <input type=submit name='Simpan' value='Simpan' />
      <input type=button name='Batal' value='Batal'
        onClick=\"window.close()\" />
      </td>
      </tr>
  </form>
  </table></div>
  </div>
  </div>
  </p>";
}

function Simpan($md, $id, $bck) {
	global $koneksi;
  TutupScript();
  $idlama = $_REQUEST['idlama'];
  $Nama = sqling($_REQUEST['Nama']);
  $NA = (empty($_REQUEST['NA']))? 'N' : 'Y';
  
  if($id != $idlama)
  {	$ada = AmbilFieldx('presenter', "KodeID='".KodeID."' and PresenterID", $id, '*');
    if (!empty($ada))
      die(PesanError('Error', "<br />Presenter dengan kode <b>$id</b> sudah ada.<br />
        Gunakan kode yang lain.
        <hr size=1 color=silver />
        <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" />"));
  }	
  
  if ($md == 0) {
    $s = "update presenter
      set Nama = '$Nama',
          PresenterID = '$id',
          NA = '$NA',
          LoginEdit = '$_SESSION[_Login]',
          TanggalEdit = now()
      where KodeID = '".KodeID."' and PresenterID = '$idlama' ";
    $r = mysqli_query($koneksi, $s);
    echo "<script>ttutup('$_SESSION[ndelox]');</script>";
  }
  elseif ($md == 1) {
  
  // Cek ID-nya dulu
    $s = "insert into presenter
      (PresenterID, KodeID, Nama, LoginBuat, TanggalBuat, NA)
      values
      ('$id', '".KodeID."', '$Nama', '$_SESSION[_Login]', now(), '$NA')";
    $r = mysqli_query($koneksi, $s);
    echo "<script>ttutup('$_SESSION[ndelox]');</script>";
  }
  else die(PesanError('Error', "Mode edit tidak ditemukan."));
}

function TutupScript() {
echo <<<SCR
<SCRIPT>
  function ttutup(bck) {
    opener.location='../index.php?ndelox='+bck;
    self.close();
    return false;
  }
</SCRIPT>
SCR;
}

?>
