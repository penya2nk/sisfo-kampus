<?php
function ListProgram() {
  global $koneksi;
  $s = "select * 
    from program 
    where KodeID = '".KodeID."'
    order by ProgramID";
  $r = mysqli_query($koneksi, $s);
  $n = 0;
  $cs = 5;
	
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:50%'>
    <form action='?' method=POST>
    <input type=hidden name='KodeID' value='".KodeID."' />
    <input type=hidden name='ndelox' value='$_SESSION[ndelox]'>
    <tr><td colspan=$cs class=ul>
        <input class='btn btn-success btn-sm' type=button name='TambahProgram' value='Tambah Program'
          onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=ProgEdt&md=1'\" />
        </td></tr>
    <tr style='background:purple;color:white'><th class=ttl colspan=2>#</th>
        <th class=ttl>Kode</th>
        <th class=ttl>Nama</th><th class=ttl>NA</th></tr>";
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    if ($w['NA']=='N'){
      $stat="<i style='color:green' class='fa fa-eye'></i>";
    }else{
      $stat="<i style='color:red' class='fa fa-eye-slash'></i>";
    }
    $c = ($w['NA'] == 'Y')? 'class=nac' : 'class=ul';
    echo "<tr>
      <td class=inp width=20>$n</td>
      <td class=ul1 width=20 align=center><a href='?ndelox=$_SESSION[ndelox]&lungo=ProgEdt&md=0&ProgramID=$w[ProgramID]'><i class='fa fa-edit'></a></td>
      <td $c width=100>$w[ProgramID]</td>
      <td $c>$w[Nama]</td>
      <td $c align=center width=20>$stat</td>
      </tr>";
  }
  echo "</table>
  </div>
</div>
</div>
  </p>";
}
function ProgEdt() {
  $md        = strfilter($_GET['md']) +0;
  $ProgramID = strfilter($_GET['ProgramID']);
  if ($md == 0) {
      $w     = AmbilFieldx('program', 'ProgramID', $ProgramID, '*');
      $jdl   = 'Edit Program';
      $_pid  = "<input type=hidden name='ProgramID' value='$w[ProgramID]'><b>$w[ProgramID]</b>";
  }
  else {
      $w = array();
      $w['ProgramID'] = '';
      $w['Nama']      = '';
      $w['NA']        = 'N';
      $jdl = 'Tambah Program';
      $_pid = "<input type=text name='ProgramID' size=20 maxlength=20>";
  }
  $na = ($w['NA'] == 'Y')? 'checked' : '';
  $snm = session_name(); $sid = session_id();
  CheckFormScript("ProgramID,Nama");
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:50%'>
  <form action='?' method=POST onSubmit='return CheckForm(this)'>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]'>
  <input type=hidden name='lungo' value='ProgSav'>
  <input type=hidden name='md' value='$md'>
  <input type=hidden name='BypassMenu' value='1' />
  
  <tr><th class=ttl colspan=2>$jdl</th></tr>
  <tr><td class=inp>Program ID</td>
      <td class=ul>$_pid</td></tr>
  <tr><td class=inp>Nama</td>
      <td class=ul><input type=text name='Nama' value='$w[Nama]' size=30 maxlength=50></td></tr>
  <tr><td class=inp>Tidak aktif?</td>
      <td class=ul><input type=checkbox name='NA' value='Y' $na></td></tr>
  
  <tr><td colspan=2 align=left>
    <input class='btn btn-success btn-sm' type=submit name='Simpan' value='Simpan'>
    <input class='btn btn-primary btn-sm' type=reset name='Reset' value='Reset'>
    <input class='btn btn-warning btn-sm' type=button name='Batal' value='Batal' onClick=\"location='?ndelox=$_SESSION[ndelox]'\"></td></tr>
  </form>
  </table>
  </div>
  </div>
  </div>";
}
function ProgSav() {
  global $koneksi;
  $md = $_REQUEST['md'] +0;
  $ProgramID = $_REQUEST['ProgramID'];
  $Nama = sqling($_REQUEST['Nama']);
  $NA = (empty($_REQUEST['NA']))? 'N' : $_REQUEST['NA'];
  if ($md == 0) {
    $s = "update program set Nama='$Nama', NA='$NA' where ProgramID='$ProgramID' and KodeID = '".KodeID."' ";
    mysqli_query($koneksi, $s);
  }
  else {
    $ada = AmbilFieldx('program', 'ProgramID', $ProgramID, '*');
    if (empty($ada)) {
      $s = "insert into program(ProgramID, Nama, KodeID, NA)
        values('$ProgramID', '$Nama', '".KodeID."', '$NA')";
      mysqli_query($koneksi, $s);
    }
    else echo PesanError('Kesalahan',
      "Kode program: <b>$ProgramID</b> telah dipakai oleh Program: <b>$ada[Nama]</b>.<br>
      Gunakan Kode Program lain.");
  }
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]", 10);
}

$lungo = (empty($_REQUEST['lungo']))? 'ListProgram' : $_REQUEST['lungo'];

TitleApps("PROGRAM PENDIDIKAN");
$lungo();
?>
