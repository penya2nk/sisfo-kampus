<?php
function ListCollege() {
  global $koneksi;
  global $Organisasix;
  $s = "select * 
    from kampus where KodeID='".KodeID."'
    order by KampusID";
  $r = mysqli_query($koneksi, $s);
  $cs = 4;
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'><table id='example' class='table table-sm table-striped' style='width:50%'>
    <tr ><td colspan=$cs class=ul1>
        <input class='btn btn-success btn-sm' type=button name='TambahKampus' value='Tambah Kampus'
          onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=EditCollege&md=1'\" />
        </td></tr>
    <tr style='background:purple;color:white'><th class=ttl colspan=2>Kode</th>
    <th class=ttl>Nama</th>
    <th class=ttl>Ruangan</th>
    <th class=ttl>NA</th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
    if ($w['NA']=='N'){
      $stat="<i style='color:green' class='fa fa-eye'></i>";
    }else{
      $stat="<i style='color:red' class='fa fa-eye-slash'></i>";
    }
    $c = ($w['NA'] == 'Y')? 'class=nac' : 'class=ul';
    echo "<tr>
    <td $c width=35 align=center>
      <input class='btn btn-warning btn-sm' type=button name='Edit' value='Edit'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=EditCollege&md=0&KampID=$w[KampusID]'\" />
      </td>
    <td $c width=100><a href='#' onClick=\"location='?ndelox=master/ruang&kampusid=$w[KampusID]'\">$w[KampusID]</a></td>
    <td $c>$w[Nama]</td>
    <td $c width=180><a href='#' onClick=\"location='?ndelox=master/ruang&kampusid=$w[KampusID]'\">[ Cek Ruangan ]</a></td>
    <td $c align=center width=10>$stat</td>
    </tr>";
  }
  echo "</table></div>
  </div>
  </div></p>";
}
function EditCollege() {
  global $_Identitas;
  $md = $_REQUEST['md']+0;
  if ($md == 0) {
    $w = AmbilFieldx('kampus', 'KampusID', $_REQUEST['KampID'], '*');
    $jdl = "Edit Kampus";
    $strid = "<input type=hidden name='KampusID' value='$w[KampusID]'><b>$w[KampusID]</b>";
  }
  else {
    $w = array();
    $w['KampusID'] = '';
    $w['Nama'] = '';
    $w['Alamat'] = '';
    $w['Kota'] = '';
    $w['KodeID'] = KodeID;
    $w['Telepon'] = '';
    $w['Fax'] = '';
    $w['NA'] = 'N';
    $jdl = "Tambah Kampus";
    $strid = "<input type=text name='KampusID' size=20 maxlength=20>";
  }
  $snm = session_name(); $sid = session_id();
  $na = ($w['NA'] == 'Y')? 'checked' : '';
  $c1 = 'class=inp'; $c2 = 'class=ul';
  CheckFormScript("KampusID,Nama,Alamat");
  // Tampilkan
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'><table id='example' class='table table-sm table-striped' style='width:50%'>
  <form action='?' method=POST onSubmit='return CheckForm(this)'>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]'>
  <input type=hidden name='lungo' value='CollegeSave'>
  <input type=hidden name='md' value='$md'>
  <tr ><th colspan=2 class=ttl>$jdl</th></tr>
  <tr><td >RuangID</td><td >$strid</td></tr>
  <tr><td >Nama</td><td ><input type=text name='Nama' value='$w[Nama]' size=40 maxlength=50></td></tr>
  <tr><td >Alamat</td><td ><textarea name='Alamat' cols=44 rows=3>$w[Alamat]</textarea></td></tr>
  <tr><td >Kota</td><td ><input type=text name='Kota' value='$w[Kota]' size=40 maxlength=50></td></tr>
  <tr><td >Telepon</td><td ><input type=text name='Telepon' value='$w[Telepon]' size=40 maxlength=50></td></tr>
  <tr><td >Fax</td><td ><input type=text name='Fax' value='$w[Fax]' size=40 maxlength=50></td></tr>
  <tr><td >NA (tidak aktif)?</td><td ><input type=checkbox name='NA' value='Y' $na></td></tr>
  <tr><td colspan=2 align=left>
    <input class='btn btn-success btn-sm' type=submit name='Simpan' value='Simpan'>
    <input class='btn btn-warning btn-sm' type=Reset name='Reset' value='Reset'>
    <input class='btn btn-info btn-sm' type=button name='Batal' value='Batal' onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=&$snm=$sid'\"></td></tr>
  
  </form></table></div>
  </div>
  </div></p>";
}
function CollegeSave() {
  global $koneksi;
  $md = $_REQUEST['md']+0;
  $KampusID = $_REQUEST['KampusID'];
  $Nama = sqling($_REQUEST['Nama']);
  $Alamat = sqling($_REQUEST['Alamat']);
  $Kota = sqling($_REQUEST['Kota']);
  $Telepon = sqling($_REQUEST['Telepon']);
  $Fax = sqling($_REQUEST['Fax']);
  $NA = (empty($_REQUEST['NA']))? 'N' : $_REQUEST['NA'];
  // simpan
  if ($md == 0) {
    $s = "update kampus set Nama='$Nama', KodeID='".KodeID."',
      Alamat='$Alamat', Kota='$Kota', Telepon='$Telepon', Fax='$Fax', NA='$NA'
      where KampusID='$KampusID' ";
    $r = mysqli_query($koneksi, $s);
  }
  else {
    $ada = AmbilFieldx('kampus', 'KampusID', $KampusID, '*');
    if (!empty($ada)) echo PesanError("Gagal Simpan",
      "Kampus dengan kode: <b>$KampusID</b> telah ada dengan nama <b>$ada[Nama]</b>.<br>
      Gunakan kode kampus lain.");
    else {
      $s = "insert into kampus (KampusID, Nama, KodeID,
        Alamat, Kota, Telepon, Fax, NA)
        values ('$KampusID', '$Nama', '".KodeID."',
        '$Alamat', '$Kota', '$Telepon', '$Fax', '$NA')";
      $r = mysqli_query($koneksi, $s);
    }
  }
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]", 100);
}

$lungo = (empty($_REQUEST['lungo']))? 'ListCollege' : $_REQUEST['lungo'];
$KodeID = GainVariabelx('KodeID', $_Identitas);

TitleApps("GEDUNG KAMPUS");
$lungo();
?>
