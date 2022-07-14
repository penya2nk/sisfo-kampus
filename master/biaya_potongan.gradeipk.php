<?php

function SetupGradeIPK() {
  global $ndelox, $tok;
  $ki = AmbilGradeIPK();
  // Tampilkan
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'><a href='?sub=GradeIPKEdt&md=1&ndelox=$ndelox&tok=$tok'>Tambahkan Grade IPK</a>";
  echo "<table id='example' class='table table-sm table-striped'>
  <tr><td width=600 valign=top>$ki</td>
  </table></div>
  </div>
  </div></p>";
  //echo CatatanGradeIPK();
}
function AmbilGradeIPK() {
  global $ndelox, $tok, $koneksi;
  $s = "select gi.*
    from gradeipk gi
    where gi.KodeID='".KodeID."'
    order by gi.IPKMin DESC, gi.IPKMax DESC";
  $r = mysqli_query($koneksi, $s);
  
  $n = 0;
  $a = "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'><table id='example' class='table table-sm table-striped'>
    <tr><td class=ul1 colspan=8><b>Grade IPK Beasiswa</b></td></tr>
    <tr>
	<th class=ttl colspan=2>No.</th>
    <th class=ttl>Grade</th>
    <th class=ttl>IPK Min</th>
    <th class=ttl>IPK Max</th>
	<th class=ttl>SKS Min</th>
    <th class=ttl>Keterangan</th>
    <th class=ttl>NA</th></tr>";
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    if ($w['NA']=='N'){
      $stat="<i style='color:purple' class='fa fa-eye'></i>";
    }else{
      $stat="<i style='color:red' class='fa fa-eye-slash'></i>";
    }
    $c = ($w['NA'] == 'Y')? 'class=nac' : 'class=ul';
    $a .= "<tr><td class=inp width=20>$n</td>
      <td $c width=10><a href='?ndelox=$ndelox&tok=$tok&sub=GradeIPKEdt&md=0&ipk=$w[GradeIPK]'><i class='fa fa-edit'></i></td>
      <td $c align=center>$w[GradeIPK]</td>
      <td $c align=center width=50>$w[IPKMin]</td>
      <td $c align=center width=50>$w[IPKMax]</td>
	  <td $c align=center width=50>$w[SKSMin]</td>
	  <td $c width=200>$w[Keterangan]</td>
      <td $c align=center width=10>$stat=</td>
      </tr>";
  }
  $a .= "</table></div>
  </div>
  </div></p>";
  
  return "$a";
}
function GradeIPKEdt() {
  global $ndelox, $tok;

  $md = $_REQUEST['md']+0;
  if ($md == 0) {
    $w = AmbilFieldx('gradeipk', 'GradeIPK', $_REQUEST['ipk'], '*');
    $Jdl = "Edit Grade IPK";
  }
  else {
    $w = array();
    $w['GradeIPK'] = '';
    $w['GradeIPK'] = '';
	$w['IPKMin'] = 0.00;
	$w['IPKMax'] = 0.00;
    $w['Keterangan'] = '';
    $w['NA'] = 'N';
    $Jdl = "Tambah Grade IPK";
  }
  $NA = ($w['NA'] == 'Y')? 'checked' : '';
  // Tampilkan
  CheckFormScript("GradeIPK,IPKMin,IPKMax");
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'><table id='example' class='table table-sm table-striped'>
  <form action='?' method=POST onSubmit=\"return CheckForm(this)\">
  <input type=hidden name='ndelox' value='$ndelox'>
  <input type=hidden name='tok' value='$tok'>
  <input type=hidden name='sub' value='GradeIPKSav'>
  <input type=hidden name='md' value='$md'>
  <input type=hidden name='ipk' value='$w[GradeIPK]'>
  <tr><th class=ttl colspan=2>$Jdl</th></tr>
  <tr><td class=inp>GradeIPK</td><td class=ul><input type=text name='GradeIPK' value='$w[GradeIPK]' size=4 maxlength=10></td></tr>
  <tr><td class=inp>IPK Min</td><td class=ul><input type=text name='IPKMin' value='$w[IPKMin]' size=3 maxlength=5> </td></tr>
  <tr><td class=inp>IPK Max</td><td class=ul><input type=text name='IPKMax' value='$w[IPKMax]' size=3 maxlength=5></td></tr>
  <tr><td class=inp>SKS Min</td><td class=ul><input type=text name='SKSMin' value='$w[SKSMin]' size=3 maxlength=5></td></tr>
  <tr><td class=inp>Catatan</td><td class=ul><textarea name='Keterangan' cols=30 rows=2>$w[Keterangan]</textarea></td></tr>
  <tr><td class=inp>Tidak Aktif?</td><td class=ul1><input type=checkbox name='NA' value='Y' $NA></td></tr>
  <tr><td colspan=2><input class='btn btn-success btn-sm' type=submit name='Simpan' value='Simpan'>
    <input class='btn btn-danger btn-sm' type=reset name='Reset' value='Reset'>
    <input class='btn btn-primary btn-sm' type=button name='Batal' value='Batal' onClick=\"location='?ndelox=$ndelox&tok=$tok&sub='\"></td></tr>
  </form></table></div>
  </div>
  </div></p>";
  //echo CatatanGradeIPK();
}
function GradeIPKSav() {
  global $koneksi;
  $md = $_REQUEST['md']+0;
  $GradeIPK = sqling($_REQUEST['GradeIPK']);
  $IPKMin = $_REQUEST['IPKMin']+0.00;
  $IPKMax = $_REQUEST['IPKMax']+0.00;
  $SKSMin = $_REQUEST['SKSMin']+0;
  $Keterangan = sqling($_REQUEST['Keterangan']);
  $NA = (empty($_REQUEST['NA']))? 'N' : $_REQUEST['NA'];
  // Simpan
  if ($md == 0) {
    $s = "update gradeipk set GradeIPK='$GradeIPK', 
      IPKMin='$IPKMin', IPKMax='$IPKMax', SKSMin='$SKSMin',
	  Keterangan='$Keterangan', NA='$NA',
      LoginEdit='$_SESSION[_Login]', TglEdit=now()
      where GradeIPK='$GradeIPK' ";
  }
  else {
    $s = "insert into gradeipk (GradeIPK, KodeID, 
	    IPKMin, IPKMax, SKSMin, Keterangan, NA, TglBuat, LoginBuat)
      values ('$GradeIPK', '".KodeID."', 
	    '$IPKMin', '$IPKMax', '$SKSMin', '$Keterangan', '$NA',
      now(), '$_SESSION[_Login]')";
  }
  $r = mysqli_query($koneksi, $s);
  
  SetupGradeIPK();
}

function CatatanGradeIPK() {
  return "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table class=box id='example' class='table table-sm table-striped'>
  <tr><td class=ul1 colspan=2>Catatan:</td></tr>
  <tr><td class=ul><b>Urutan</b>
    <td class=ul>Tampilan urutan dalam pencetakan BPM (Bukti Pembayaran Mahasiswa).<br />
    Jika ada 2 atau lebih biaya/potongan mahasiswa yang memiliki nomer urut sama,
    maka pada pencetakan BPM biaya/potongan tersebut akan dijumlahkan.</td></tr>
  </table></div>
  </div>
  </div></p>";
}
?>
