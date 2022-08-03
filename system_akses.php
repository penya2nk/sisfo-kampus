<?php
function TampilkanMdlGrp() {
  $opt = AmbilCombo2('mdlgrp', "concat(Urutan, '. ', Nama)", 'Urutan', $_SESSION['mdlgrp'], '', 'MdlGrpID');
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' align='center'>
  
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='system_akses'>
  <input type=hidden name='token' value='ListModulx'>
  <tr>
  <td class=inp1 width='180'>Group Modul</td>
  <td class=ul><select name='mdlgrp' onChange=\"this.form.submit()\">$opt</select></td>
  </tr>
  </form></table>
  </div>
</div>
</div></p>";
}
function ViewModulMnu() {
  echo "<p align=center>
    <a class='btn btn-primary btn-sm' href=\"?ndelox=system_akses&token=ListModulx\">Daftar Modul</a> |
    <a class='btn btn-warning btn-sm' href=\"?ndelox=system_akses&token=ModulUbah&md=1\">Tambah Modul</a> |
    <a class='btn btn-danger btn-sm' href=\"?ndelox=system_akses&token=DftrGrp\">Daftar Group</a> |
    <a class='btn btn-info btn-sm' href=\"?ndelox=system_akses&token=GrpEdt&md=1\">Tambah Group</a>
    </p>";
}
function ListModulx() {
	  global $koneksi;
  TampilkanMdlGrp();

  $whr = '';
  $whr .= (empty($_SESSION['mdlgrp']))? '' : "and m.MdlGrpID='$_SESSION[mdlgrp]' ";
  $s = "select m.*, mg.Urutan
    from mdl m
    left outer join mdlgrp mg on m.MdlGrpID=mg.MdlGrpID
    where m.MdlID>0 $whr
    order by mg.Urutan, m.Nama";
  $r = mysqli_query($koneksi, $s) or die("Gagal: $s<br>".mysql_error());
  $n = 0;
  ViewModulMnu();
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example1' class='table table-sm table-striped' style='width:100%' align=center>
     <thead>
	<tr style='background:purple;color:white'><th class=ttl>#</th><th class=ttl>Module</td>
    <th class=ttl>Level</th>
    <th class=ttl>Script</th>
    <th class=ttl>Web</th>
    <th class=ttl>Group</th>
    <th class=ttl>NA</th>
    </tr></thead>
	<tbody>";
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    if ($w['NA']=='N'){
      $Stat="<i class='fa fa-eye'></i>";
    }else{
      $Stat="<i class='fa fa-eye-slash'></i>";
    }
    $c = ($w['NA'] == 'Y')? 'class=nac' : 'class=ul';
    echo "<tr>
      <td $c><a href=\"?ndelox=$_SESSION[ndelox]&token=ModulUbah&md=0&mid=$w[MdlID]\"><i class='fa fa-edit'></i></a>
      $n</td>
      <td $c>$w[Nama]</td>
      <td $c>$w[LevelID]</td>
      <td $c>$w[Script]</td>
      <td $c align=center width=5><img src='img/$w[Web].png'></td>
      <td $c>$w[MdlGrpID]</td>
      <td $c align=center width=5>
        <a href='?ndelox=$_SESSION[ndelox]&token=ModulStatx&mid=$w[MdlID]&BypassMenu=1'>$Stat</a>
        </td>
      </tr>";
  }
  echo "</tbody></table>
  </div>
</div>
</div></p>";
}
function ModulStatx() {
	global $koneksi;
  $mid = $_REQUEST['mid'];
  $m = AmbilOneField('mdl', 'MdlID', $mid, 'NA');
  $NA = ($m['NA'] == 'N')? 'Y' : 'N';
  $s = "update mdl set NA = '$NA' where MdlID = '$mid' ";
  $r = mysqli_query($koneksi, $s);
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=", 1);
}
function ModulUbah() {
  global $_Author, $_AuthorEmail;
  $md = $_REQUEST['md']+0;
  if ($md == 0) {
    $w = AmbilFieldx('mdl', 'MdlID', $_REQUEST['mid'], '*');
    $jdl = 'Edit Modul';
  }
  else {
    $w = array();
    $w['MdlID'] = '';
    $w['MdlGrpID'] = $_SESSION['mdlgrp'];
    $w['Nama'] = '';
    $w['Script'] = '';
    $w['LevelID'] = '.';
    $w['Web'] = 'Y';
    $w['Author'] = $_Author;
    $w['EmailAuthor'] = $_AuthorEmail;
    $w['Simbol'] = '';
    $w['Help'] = '';
    $w['NA'] = 'N';
    $w['Keterangan'] = '';
    $jdl = "Tambah Modul";
  }
  $optgrp = AmbilCombo2('mdlgrp', "concat(MdlGrpID, ' - ', Nama)", 'Nama', $w['MdlGrpID'], '', 'MdlGrpID');
  $na = ($w['NA'] == 'Y')? 'checked' : '';
  $web = ($w['Web'] == 'Y')? 'checked' : '';
  $snm = session_name(); $sid = session_id();
  $DftrLevel = GetDftrLevel($w['LevelID']);
  // Tampilkan form
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align=center> 
  <form action='?' name='data' method=POST>
  <input type=hidden name='md' value='$md'>
  <input type=hidden name='MdlID' value='$w[MdlID]'>
  <input type=hidden name='ndelox' value='system_akses'>
  <input type=hidden name='token' value='ModulSimpan'>
  <input type=hidden name='BypassMenu' value='1' />

  <tr><th colspan=3 class=ttl>$jdl</th></tr>
  <tr><td class=inp>Nama</td><td class=ul><input type=text name='Nama' value='$w[Nama]' size=40 maxlength=50></td>
    <td class=ul rowspan=12 valign=bottom>$DftrLevel</td></tr>
  <tr><td class=inp>Group</td><td class=ul><select name='MdlGrpID'>$optgrp</select></td></tr>
  <tr><td class=inp>Script</td><td class=ul><input type=text name='Script' value='$w[Script]' size=40 maxlength=50></td></tr>
  <tr><td class=inp>Level</td><td class=ul><input type=text name='LevelID' value='$w[LevelID]' size=40 maxlength=50></td></tr>
  <tr><td class=inp>Versi Web</td><td class=ul><input type=checkbox name='Web' value='Y' $web></td></tr>
  <tr><td class=inp>Author</td><td class=ul><input type=text name='Author' value='$w[Author]' size=40 maxlength=50></td></tr>
  <tr><td class=inp>Email</td><td class=ul><input type=text name='EmailAuthor' value='$w[EmailAuthor]' size=40 maxlength=50></td></tr>
  <tr><td class=inp>Simbol</td><td class=ul><input type=text name='Simbol' value='$w[Simbol]' size=40 maxlength=50></td></tr>
  <tr><td class=inp>Help</td><td class=ul><input type=text name='Help' value='$w[Help]' size=40 maxlength=50></td></tr>
  <tr><td class=inp>NA (tdk aktif)</td><td class=ul><input type=checkbox name='NA' value='Y' $na></td></tr>
  <tr><td class=inp>Keterangan</td><td class=ul><textarea name='Keterangan' cols=30 rows=3>$w[Keterangan]</textarea></td></tr>
  <tr><td colspan=2 align=center><input type=submit name='Simpan' value='Simpan'>
    <input type=reset name='Reset' value='Reset'>
    <input type=button name='Batal' value='Batal' onClick=\"location='?ndelox=system_akses'\"></td></tr>
  </form></table></div>
  </div>
  </div>
  </p>";
}
function ModulSimpan() {
	global $koneksi;
  $md = $_REQUEST['md'];
  $MdlID = $_REQUEST['MdlID'];
  $MdlGrpID = $_REQUEST['MdlGrpID'];
  $Nama = sqling($_REQUEST['Nama']);
  $Script = $_REQUEST['Script'];
  $_levelid = TRIM($_REQUEST['LevelID'], '.');
  if (empty($_levelid)) $LevelID = '';
  else {
    $arrLevelID = explode('.', $_levelid);
    sort($arrLevelID);
    $LevelID = '.'.implode('.', $arrLevelID).'.';
  }
  $Web = (!empty($_REQUEST['Web']))? $_REQUEST['Web'] : 'N';
  $Author = sqling($_REQUEST['Author']);
  $EmailAuthor = sqling($_REQUEST['EmailAuthor']);
  $Simbol = $_REQUEST['Simbol'];
  $Help = $_REQUEST['Help'];
  $NA = (!empty($_REQUEST['NA']))? $_REQUEST['NA'] : 'N';
  $Keterangan = sqling($_REQUEST['Keterangan']);
  // Simpan
  if ($md == 0) {
    $s = "update mdl set Nama='$Nama', MdlGrpID='$MdlGrpID', Script='$Script',
      LevelID='$LevelID', Web='$Web',
      Author='$Author', EmailAuthor='$EmailAuthor', Simbol='$Simbol',
      Help='$Help', NA='$NA', Keterangan='$Keterangan'
      where MdlID='$MdlID'";
  }
  else {
    $s = "insert into mdl (MdlGrpID, Nama, Script, LevelID, Web,
      Author, EmailAuthor, Simbol, Help, NA, Keterangan)
      values ('$MdlGrpID', '$Nama', '$Script', '$LevelID', '$Web',
      '$Author', '$EmailAuthor', '$Simbol', '$Help', '$NA', '$Keterangan')";
  }
  mysqli_query($koneksi, $s);
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=", 100);
}
function GetDftrLevel($lvl='') {
	global $koneksi;
  TulisScriptUbahLevel();
  $s = "select *
    from level
    order by LevelID";
  $r = mysqli_query($koneksi, $s);
  $a = '';
  while ($w = mysqli_fetch_array($r)) {
    $ck = (strpos($lvl, ".$w[LevelID].") === false)? '' : 'checked';
    $a .= "<input type=checkbox name='Level$w[LevelID]' value='$w[LevelID]' $ck onChange='javascript:UbahLevel(data.Level$w[LevelID])'> $w[LevelID] - $w[Nama]<br />";
  }
  return $a;
}
function TulisScriptUbahLevel() {
  echo <<<END
  <SCRIPT LANGUAGE="JavaScript1.2">
  function UbahLevel(nm){
    ck = "";
    if (nm.checked == true) {
      var nilai = data.LevelID.value;
      if (nilai.match(nm.value+".") != nm.value+".") data.LevelID.value += nm.value + ".";
    }
    else {
      var nilai = data.LevelID.value;
      data.LevelID.value = nilai.replace(nm.value+".", "");
    }
  }
  //-->
  </script>
END;
}
function DftrGrp() {
	global $koneksi;
  ViewModulMnu();
  $s = "select mg.*
    from mdlgrp mg
    order by mg.Urutan";
  $r = mysqli_query($koneksi, $s);
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>
  <tr style='background:purple;color:white'><th class=ttl>#</th>
  <th class=ttl>ID</th>
  <th class=ttl>Group</th>
  <th class=ttl>Nama</th>
  <th class=ttl>NA</th></tr>";
  while ($w = mysqli_fetch_array($r)) {
    $c = ($w['NA'] == 'Y')? 'class=nac' : 'class=ul';
    echo "<tr><td class=inp1>$w[Urutan]</td>
    <td $c><a href='?ndelox=system_akses&token=GrpEdt&md=0&grid=$w[MdlGrpID]'><i class='fa fa-edit'></i>
    $w[MdlGrpID]</a></td>
    <td $c>$w[Nama]</td>
    <td $c align=center><img src='img/book$w[NA].gif'></td>
    </tr>";
  }
  echo "</table>
  </div>
</div>
</div></p>";
}
function GrpEdt() {
  $md = $_REQUEST['md']+0;
  if ($md == 0) {
    $w = AmbilFieldx('mdlgrp', 'MdlGrpID', $_REQUEST['grid'], '*');
    $_grid = "<input type=hidden name='MdlGrpID' value='$w[MdlGrpID]'><b>$w[MdlGrpID]</b>";
    $jdl = "Edit Group";
  }
  else {
    $w = array();
    $w['MdlGrpID'] = '';
    $w['Nama'] = '';
    $w['Urutan'] = 0;
    $w['NA'] = 'N';
    $_grid = "<input type=text name='MdlGrpID' size=10 maxlength=10>";
    $jdl = "Tambah Group";
  }
  $_NA = ($w['NA'] == 'Y')? 'checked' : '';
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='system_akses'>
  <input type=hidden name='md' value='$md'>
  <input type=hidden name='token' value='GrpSav'>
  <input type=hidden name='BypassMenu' value='1' />
  
  <tr><th class=ttl colspan=2>$jdl</th></tr>
  <tr><td class=inp1>Group ID</td><td class=ul>$_grid</td></tr>
  <tr><td class=inp1>Nama Group</td><td class=ul><input type=text name='Nama' value='$w[Nama]' size=20 maxlength=50></td></tr>
  <tr><td class=inp1>Urutan</td><td class=ul><input type=text name='Urutan' value='$w[Urutan]' size=5 maxlength=5></td></tr>
  <tr><td class=inp1>Tidak Aktif?</td><td class=ul><input type=checkbox name='NA' value='Y' $_NA></td></tr>
  <tr><td colspan=2><input type=submit name='Simpan' value='Simpan'>
  <input type=reset name='Reset' value='Reset'>
  <input type=button name='Batal' value='Batal' onClick=\"location='?ndelox=system_akses&token=DftrGrp'\"></td></tr>
  </form></table>
  </div>
</div>
</div></p>";
}
function GrpSav() {
	global $koneksi;
  $md = $_REQUEST['md'];
  $MdlGrpID = $_REQUEST['MdlGrpID'];
  if (!empty($MdlGrpID)) {
    $NA = (empty($_REQUEST['NA']))? 'N' : $_REQUEST['NA'];
    $Nama = sqling($_REQUEST['Nama']);
    $Urutan = $_REQUEST['Urutan']+0;
    if ($md == 0) {
      $s = "update mdlgrp set Nama='$Nama', Urutan='$Urutan', NA='$NA' where MdlGrpID='$MdlGrpID' ";
      $r = mysqli_query($koneksi, $s);
      echo "<script>window.location='?ndelox=$_SESSION[ndelox]&lungo='</script>";
    }
    else {
      $ada = AmbilFieldx('mdlgrp', 'MdlGrpID', $MdlGrpID, '*');
      if (empty($ada)) {
        $s = "insert into mdlgrp (MdlGrpID, Nama, Urutan, NA)
          values ('$MdlGrpID', '$Nama', '$Urutan', '$NA')";
        $r = mysqli_query($koneksi, $s);
        echo "<script>window.location='?ndelox=$_SESSION[ndelox]&lungo='</script>";
      }
      else echo PesanError("Data Tidak Dapat Disimpan",
        "Group dengan ID <b>$MdlGrpID</b> telah ada. Gunakan ID lain.");
    }
  }
  DftrGrp();
}

$mdlgrp = GainVariabelx('mdlgrp');
$token = (empty($_REQUEST['token']))? 'ListModulx' : $_REQUEST['token'];

TitleApps("Modul $_ProductName");
$token();
?>
