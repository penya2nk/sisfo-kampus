<?php

function GainVariabelx($str, $def='') {
  if (isset($_REQUEST[$str])) {
	$_SESSION[$str] = $_REQUEST[$str];
	return $_REQUEST[$str];
  }
  else {
    if (isset($_SESSION[$str])) return $_SESSION[$str];
    else {
	  $_SESSION[$str] = $def;
	  return $def;
    }
  }
}


function Info1($isi) {
  return "<p><table class=box cellspacing=1 cellpadding=4 width=100%>
  <tr><th class=ttl>$isi</th></tr>
  </table></p>";
}
function Info($jdl, $isi) {
  $Gbr = 'img/login.png';
  if (!file_exists($Gbr)) $Gbr = "../".$Gbr;
  Return "<p><center><table class=box cellspacing=1 cellpadding=4>
  <tr><th class=ttl colspan=2>$jdl</th></tr>
  <tr><td><img src='$Gbr'></td>
  <td>$isi</td></tr></table></center></p>";
}
function PesanError1($jdl, $isi) {
  Return "<p><table class=box cellspacing=1 cellpadding=4 align=center>
  <tr><th bgcolor='purple' colspan=2><font color=yellow>$jdl</th></tr>
  <tr><td><img src='img/tux001.jpg'></td>
  <td>$isi</td></tr></table></p>";
}
function PesanError($jdl='Error', $isi='') {
  $FileLogo = 'img/warn.png';
  if (!file_exists($FileLogo)) $FileLogo = "../".$FileLogo;

  Return "<p><table class=box cellspacing=1 cellpadding=4 align=center>
  <tr><td rowspan=2 width=10><img src='$FileLogo'></td>
  <td bgcolor=purple><font color=yellow><strong>$jdl</strong></font></td>
  <tr><td>$isi</td></tr>
  </table></p>";
}

function CheckFormScript($_str='') {
  $arr = explode(',', $_str);
  echo "<SCRIPT LANGUAGE=\"JavaScript1.2\">
  function CheckForm(form) {
    strs = \"\"; 
    ";
  for ($i=0; $i<sizeof($arr); $i++) {
    $nm = trim($arr[$i]);
    echo "
    if (form.$nm.value == \"\") {
      strs += \"$nm tidak boleh kosong\\n\"; }\n";
  }
  echo "if (strs != \"\") alert(strs);
  return strs == \"\";
  }
  </SCRIPT>";
}


function sqling($str) {
  $str = stripslashes($str);
return addslashes($str);
}

function AmbilFieldx($_tbl, $_key, $_value, $_results, $_group='', $_order='', $_limit= 'limit 1') {
  global $strCantQuery, $koneksi;
	$s = "select $_results from $_tbl where $_key='$_value' $_group $_order $_limit";
	$r = mysqli_query($koneksi, $s);
	if (mysqli_num_rows($r) == 0) return '';
	else {
	  return mysqli_fetch_array($r);
	}
}

function SuksesTersimpan($back, $tmr = 1500) {
  $_tmr = $tmr/1000;
  echo Info("Berhasil", 
    "Data berhasil disimpan.<br />
    Tampilan akan kembali ke semula dalam $_tmr detik.
    <hr size=1 color=silver />
    Atau klik: <a href='$back'>[ Kembali ]</a>");
  echo "<script type='text/javascript'>window.onload=setTimeout('window.location=\"$back\"', $tmr);</script>";
}

function AmbilComboTgl($dt, $nm='dt') {
  $arr = Explode('-', $dt);
  $_dy = AmbilComboNomor(1, 31, $arr[2]);
  $_mo = AmbilComboBulan($arr[1]);
  $_yr = AmbilComboNomor(1950, Date('Y')+2, $arr[0]);
  return "<select name='".$nm."_d'>$_dy</select>
    <select name='".$nm."_m'>$_mo</select>
    <select name='".$nm."_y'>$_yr</select>";
}

function AmbilComboNomor($_start, $_end, $_default=0, $interval=1, $pad=2) {
  $_tmp = "";
for ($i=$_start; $i <= $_end; $i+=$interval) {
  $stri = str_pad($i, $pad, '0', STR_PAD_LEFT);
  if ($i == $_default) $_tmp = "$_tmp <option selected>$stri</option>";
  else $_tmp = "$_tmp <option>$stri</option>";
}
return $_tmp;
}

function AmbilComboBulan($_default=1) {
  //global $arrBulan;
  $arrBulan = array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
    'Agustus', 'September', 'Oktober', 'November', 'Desember');
  $_tmp = "";
  $_max = count($arrBulan);
  for ($i=1; $i<$_max; $i++) {
    $stri = str_pad($i, 2, '0', STR_PAD_LEFT);
    if ($_default==$i) $_tmp = "$_tmp <option value='$stri' selected>". $arrBulan[$i] ."</option>";
    else $_tmp = "$_tmp <option value='$stri'>". $arrBulan[$i] ."</option>";
  }
  return $_tmp;
}

function AmbilPenggunaProdi($login, $def='') {
  $prodi = $_SESSION['_ProdiID'];
  $prodi = TRIM($prodi, ',');
  $p = explode(',', $prodi);
  $_p = array();
  foreach ($p as $prd) {
    $_p[] = "'".$prd."'";
  }
  if (empty($_p)) $_p[] = "'xqyalajdlflkajshdf'";
  $prodi = implode(',', $_p);
  $opt = AmbilCombo2('prodi', "concat(Nama, ' - ', ProdiID)", 'ProdiID', $def,
    "KodeID='".KodeID."' and ProdiID in ($prodi)", 'ProdiID');
  return $opt;
}


function AmbilCombo2($_table, $_field, $_order='', $_default='', $_where='', $_value='', $not=0, $blank=1) {
  global $strCantQuery, $koneksi;
	if (!empty($_order)) $str_order = " order by Nama ASC "; //$_order
	else $str_order = "";
	if ($not==0) $strnot = "NA='N'"; else $strnot = '';
	if (!empty($_where)) {
	  if (empty($strnot)) $_where = "$_where"; else $_where = "and $_where";
	}
	if (!empty($_value)) {
	  $_fieldvalue = ", $_value";
	  $fk = $_value;
	}
	else {
	  $_fieldvalue = '';
	  $fk = $_field;
	}
  $_tmp = ($blank == 1)? "<option value=''>++ Pilihan ++</option>" : '';
	$_sql = "select $_field $_fieldvalue from $_table where $strnot $_where $str_order";
	$_res = mysqli_query($koneksi, $_sql);
	//echo $_sql."</br>";
	//echo "====================================================================</br>";
  while ($w = mysqli_fetch_array($_res)) {
	  if (!empty($_value)) $_v = "value='" . $w[$_value]."'";
	  else $_v = '';
	  if ($_default == $w[$fk])
	    $_tmp = "$_tmp <option $_v selected>". $w[$_field]."</option>";
	  else
	    $_tmp = "$_tmp <option $_v>". $w[$_field]."</option>";    
  }
	return $_tmp;
}

function RandomStringScript() {
  //http://www.mediacollege.com/internet/javascript/number/random.html?randomfield=45654
  echo <<<ESD
  <script language="javascript" type="text/javascript">
  function randomString() {
	  var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	  var string_length = 8;
	  var randomstring = '';
	  for (var i=0; i<string_length; i++) {
		  var rnum = Math.floor(Math.random() * chars.length);
		  randomstring += chars.substring(rnum,rnum+1);
	  }
	  return randomstring;
  }
</script>
ESD;
}

function AmbilOneField($_tbl,$_key,$_value,$_result, $_order='', $_group='', $_limit= 'limit 1') {
  global $strCantQuery, $koneksi;
	$_sql = "select $_result from $_tbl where $_key='$_value' $_group $_order $_limit";
	$_res = mysqli_query($koneksi, $_sql);
	//echo $_sql;
	if (mysqli_num_rows($_res) == 0) return '';
	else {
	  $w = mysqli_fetch_array($_res);
	  return $w[$_result];
	}
}

function AmbilComboWaktu($dt, $nm='tm') {
  $arr = Explode(':', $dt);
  $_hr = AmbilComboNomor(0, 23, $arr[0]);
  $_mn = AmbilComboNomor(0, 59, $arr[1]);
  return "<select name='".$nm."_h'>$_hr</select>
    <select name='".$nm."_n'>$_mn</select>";
}

function BerhasilHapus($back, $tmr = 1500) {
  $_tmr = $tmr/1000;
  echo Info("Berhasil", 
    "Data berhasil dihapus.<br />
    Tampilan akan kembali ke semula dalam $_tmr detik.
    <hr size=1 color=silver />
    Atau klik: <a href='$back'>[ Kembali ]</a>");
  echo "<script type='text/javascript'>window.onload=setTimeout('window.location=\"$back\"', $tmr);</script>";
}

function FormatTanggal($tgl='', $sprt='/') {
  $tgl = substr($tgl, 0, 10);
  $arr = explode('-', $tgl);
  return (empty($arr))? '' : "$arr[2]$sprt$arr[1]$sprt$arr[0]";
}

function ComboProdiProgramx($ndelox='', $lungo='', $pref='', $token='') {
  global $arrID;
// Tampilkan hanya prodi yang berhak
  if (empty($_SESSION['_ProdiID'])) $_prodi = '-1';
  else {
    $_ProdiID = trim($_SESSION['_ProdiID'], ',');
    //echo $_ProdiID;
    $arrProdi = explode(',', $_ProdiID);
    $_prodi = '';
    for ($i = 0; $i<sizeof($arrProdi); $i++) $_prodi .= ",'".$arrProdi[$i]."'";
    $_prodi = trim($_prodi, ',');
    $_prodi = (empty($arrProdi))? '-1' : $_prodi; //implode(', ', $arrProdi);
  }
  $opt = AmbilCombo2("prodi", "concat(ProdiID, ' - ', Nama)", "ProdiID", $_SESSION['prodi'], "KodeID='".KodeID."' and ProdiID in ($_prodi)", 'ProdiID');
  $optprg = AmbilCombo2('program', "concat(ProgramID, ' - ', Nama)", 'ProgramID', $_SESSION['prid'], "KodeID='".KodeID."'", 'ProgramID');
  echo "<p>
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table class=box cellspacing=1 cellpadding=4 align=center>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$ndelox' />
  <input type=hidden name='lungo' value='$lungo' />
  <input type=hidden name='$pref' value='$token' />
  <input type=hidden name='page' value='0' />

  <tr><td class=inp>Program Studix</td>
      <td class=ul>
      <select name='prodi' onChange='this.form.submit()'>$opt</select></td>
      <td class=inp>Program</td>
      <td class=ul>
      <select name='prid' onChange='this.form.submit()'>$optprg</select></td>
      </tr>
  </form></table>
  </div>
</div>
</div>
  </p>";
}

function ViewKurikulum() {
  global $ndelox, $pref, $arrID;
  // Tampilkan hanya prodi yang berhak
  if (empty($_SESSION['_ProdiID'])) $_prodi = '-1';
  else {
    $_ProdiID = trim($_SESSION['_ProdiID'], ',');
    //echo $_ProdiID;
    $arrProdi = explode(',', $_ProdiID);
    $_prodi = '';
    foreach ($arrProdi as $val) $_prodi .= ",'$val'";
    $_prodi = trim($_prodi, ',');
    $_prodi = (empty($arrProdi))? '-1' : $_prodi; //implode(', ', $arrProdi);
  }
}  


function TabulasiSubMenu($ndelox, $arr=array(), $pref='', $tok='') {
  $sb = '';
  for ($i=0; $i<sizeof($arr); $i++) {
    $r = Explode('->', $arr[$i]);
    $cl = ($r[1] == $tok) ? "class=menuaktif" : "class=menuitem";
    $sb .= "<td $cl><a href=\"?ndelox=$ndelox&$pref=$r[1]\">$r[0]</a></td>";
  }
  echo "<p><table id='example' class='table table-sm table-striped' style='width:80%;background:white' align='center' border='1'>$sb</table></p>";
}
function TabulasiSubMenu2($ndelox, $arr=array(), $pref='', $tok='') {
  $sb = '';
  for ($i=0; $i<sizeof($arr); $i++) {
    $r = explode('->', $arr[$i]);
    //$cl = ($r[1] == $tok)? "class=menuaktif" : "class=menuitem";
    $sb .= "<li $cl><a href=\"?ndelox=$ndelox&$pref=$r[1]\">$r[0]</a></li>";
  }
  echo "<ul class=submenu>$sb</ul>";
}

function GetArrayTable($sql, $key, $label, $separator=', ', $diapit='') {
  // Digunakan untuk menerjemahkan array dalam string
  global $koneksi;
  $r = mysqli_query($koneksi, $sql);
  $ret = '';
  while ($w = mysqli_fetch_array($r)) {
    $ret .= $diapit.$w[$label] .$diapit. $separator;
  }
  return TRIM($ret, $separator);
}

function ComboOpsiProdi($ndelox='', $lungo='', $pref='', $token='') {
  global $oxID;
  if (empty($_SESSION['_ProdiID'])) $_prodi = '-1';
  else {
    $_ProdiID = trim($_SESSION['_ProdiID'], ',');
    //echo $_ProdiID;
    $arrProdi = explode(',', $_ProdiID);
    $_prodi = '';
    for ($i = 0; $i<sizeof($arrProdi); $i++) $_prodi .= ",'".$arrProdi[$i]."'";
    $_prodi = trim($_prodi, ',');
    $_prodi = (empty($arrProdi))? '-1' : $_prodi; //implode(', ', $arrProdi);
  }
  $opt = AmbilCombo2("prodi", "concat(Nama, ' - ', ProdiID)", "ProdiID", $_SESSION['prodi'], "KodeID='$oxID[Kode]' and ProdiID in ($_prodi)", 'ProdiID');
  echo "<p><table class=box cellspacing=1 cellpadding=4 align=center>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$ndelox'>
  <input type=hidden name='lungo' value='$lungo'>
  <input type=hidden name='$pref' value='$token'>
  <!--
  <tr><td class=ul colspan=2><b>$$oxID[Nama]</b></td></tr>
  -->
  <tr><td class=inp>Program Studi:</td>
      <td class=ul>
      <select style='height:25px' name='prodi' onChange='this.form.submit()'>$opt</select>
      </td></tr>
  </form></table></p>";
}

function ComboOpsiProdiProgram($ndelox='', $lungo='', $pref='', $token='') {
  global $oxID;
// Tampilkan hanya prodi yang berhak
  if (empty($_SESSION['_ProdiID'])) $_prodi = '-1';
  else {
    $_ProdiID = trim($_SESSION['_ProdiID'], ',');
    //echo $_ProdiID;
    $arrProdi = explode(',', $_ProdiID);
    $_prodi = '';
    for ($i = 0; $i<sizeof($arrProdi); $i++) $_prodi .= ",'".$arrProdi[$i]."'";
    $_prodi = trim($_prodi, ',');
    $_prodi = (empty($arrProdi))? '-1' : $_prodi; //implode(', ', $arrProdi);
  }
  $opt = AmbilCombo2("prodi", "concat(ProdiID, ' - ', Nama)", "ProdiID", $_SESSION['prodi'], "KodeID='".KodeID."' and ProdiID in ($_prodi)", 'ProdiID');
  $optprg = AmbilCombo2('program', "concat(ProgramID, ' - ', Nama)", 'ProgramID', $_SESSION['prid'], "KodeID='".KodeID."'", 'ProgramID');
  echo "<p><table class=box cellspacing=1 cellpadding=4 align=center>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$ndelox' />
  <input type=hidden name='lungo' value='$lungo' />
  <input type=hidden name='$pref' value='$token' />
  <input type=hidden name='page' value='0' />

  <tr><td class=inp>Program Studi</td>
      <td class=ul>
      <select name='prodi' onChange='this.form.submit()'>$opt</select></td>
      <td class=inp>Program</td>
      <td class=ul>
      <select name='prid' onChange='this.form.submit()'>$optprg</select></td>
      </tr>
  </form></table></p>";
}

function AmbilCekBox($table, $key, $Fields, $Label, $Nilai='', $Separator=',', $whr = '', $antar='<br />') {
  global $koneksi;
  $_whr = (empty($whr))? '' : "and $whr";
  $s = "select $key, $Fields
    from $table
    where NA='N' $_whr order by $key";
  $r = mysqli_query($koneksi, $s);
  $_arrNilai = explode($Separator, $Nilai);
  $str = '';
  while ($w = mysqli_fetch_array($r)) {
    $_ck = (array_search($w[$key], $_arrNilai) === false)? '' : 'checked';
    $str .= "<input type=checkbox name='".$key."[]' value='$w[$key]' $_ck> $w[$Label]$antar";
  }
  return $str;
}

function GetLastID() {
  global $koneksi;
  $sql = "select LAST_INSERT_ID() as ID";
  $res = mysqli_query($koneksi, $sql);
  return mysql_result($res, 0, 'ID');
}

function FileFotoMhsw($mhswid, $FotoMhsw='') {
  // Ambil gambar
  $FotoMhsw = (empty($FotoMhsw))? AmbilOneField('mhsw', 'MhswID', $mhswid, 'Foto') : $FotoMhsw;
  $def = "img/gambar.gif";
  if (!empty($FotoMhsw)) {
    $fn = $FotoMhsw;
    if (file_exists($fn)) $foto = $fn;
    else $foto = $def;
  }
  else $foto = $def;
  return $foto;
}




function donothing() {
  return '';
}


function TampilkanPMBSyarat($w, $sprt='<br />') {
  global $koneksi;
  $s = "select *
    from pmbsyarat
    where NA='N' and KodeID='$_SESSION[KodeID]'
      and INSTR(StatusAwalID, '.$w[StatusAwalID].') >0
      and INSTR(ProdiID, '.$w[ProdiID].') >0
    order by PMBSyaratID";
  $r = mysqli_query($koneksi, $s);
  $w['Syarat'] = TRIM($w['Syarat'], '.');
  $_arrNilai = explode('.', $w['Syarat']);
  $_a = array();
  while ($x = mysqli_fetch_array($r)) {
    $ck = (array_search($x['PMBSyaratID'], $_arrNilai) === false)? '' : 'checked';
    $_a[] = "<input type=checkbox name='PMBSyaratID[]' value='$x[PMBSyaratID]' $ck> $x[PMBSyaratID] - $x[Nama]";
  }
  $a = implode($sprt, $_a);
  return $a;
}

function tgl_indo($tgl){
  $tanggal = substr($tgl,8,2);
  $bulan = getBulan(substr($tgl,5,2));
  $tahun = substr($tgl,0,4);
  return $tanggal.' '.$bulan.' '.$tahun;		 
}	

function TampilkanTahunProdiProgram($ndelox='', $lungo='', $pref='', $token='', $JarakNPM=0) {
  global $arrID;
  if (empty($_SESSION['_ProdiID'])) $_prodi = '-1';
  else {
    $_ProdiID = trim($_SESSION['_ProdiID'], ',');
    //echo $_ProdiID;
    $arrProdi = explode(',', $_ProdiID);
    $_prodi = '';
    for ($i = 0; $i<sizeof($arrProdi); $i++) $_prodi .= ",'".$arrProdi[$i]."'";
    $_prodi = trim($_prodi, ',');
    $_prodi = (empty($arrProdi))? '-1' : $_prodi; //implode(', ', $arrProdi);
  }
  $optprd = AmbilCombo2("prodi", "concat(ProdiID, ' - ', Nama)", "ProdiID", $_SESSION['prodi'], "KodeID='$arrID[Kode]' and ProdiID in ($_prodi)", 'ProdiID');
  $optprg = AmbilCombo2('program', "concat(ProgramID, ' - ', Nama)", 'ProgramID', $_SESSION['prid'], "KodeID='$arrID[Kode]'", 'ProgramID');
  if ($JarakNPM == 1) {
    $_npm = "<tr><td class=inp1>Dari NPM</td>
      <td class=ul><input type=text name='DariNPM' value='$_SESSION[DariNPM]' size=20 maxlength=50>
      s/d <input type=text name='SampaiNPM' value='$_SESSION[SampaiNPM]' size=20 maxlength=50>
	  </td></tr>";
  }
  else $_npm = '';
	$Autosubmit = ($at == 1) ? " onChange='this.form.submit()'" : '';
  echo "<p class=noprint><table class=box cellspacing=1 cellpadding=4 align=center>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$ndelox'>
  <input type=hidden name='lungo' value='$lungo'>
  <input type=hidden name='$pref' value='$token'>
  <tr><td class=ul colspan=2><b>$arrID[Nama]</b></td></tr>
  <tr><td class=inp1>Tahun Akademik</td><td class=ul><input type=text name='tahun' value='$_SESSION[tahun]' size=10 maxlength=10>
    <input type=submit name='Tentukan' value='Tentukan'></td></tr>
  $_npm
  <tr><td class=inp1>Program</td><td class=ul>
    <select name='prid'>$optprg</select></td></tr>
  <tr><td class=inp1>Program Studi</td><td class=ul>
    <select name='prodi'>$optprd</select></td></tr>
  </form></table>";
}

function EditProfilPengguna($tbl, $lgn, $ndelox, $lungo, $lungoval) {
  $w = AmbilFieldx($tbl, "Login", $lgn, '*');
  echo "<p>
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:50%' align='left'>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$ndelox'>
  <input type=hidden name='$lungo' value='$lungoval'>
  <input type=hidden name='TabelUser' value='$tbl'>
  <input type=hidden name='LoginUser' value='$lgn'>
  
  <tr style='background:purple;color:white'><th class=ttl colspan=2>Perbaharui Profil</th></tr>
  <tr><td class=inp1>Nama</td>
    <td class=ul ><input type=text name='Nama' value='$w[Nama]' size=50 maxlength=50></td></tr>
  <tr><td class=inp1>Telepon</td>
    <td class=ul><input type=text name='Telephone' value='$w[Telephone]' size=50 maxlength=50></td></tr>
  <tr><td class=inp1>Handphone</td>
    <td class=ul><input type=text name='Handphone' value='$w[Handphone]' size=50 maxlength=50></td></tr>
  <tr><td class=inp1>E-mail</td>
    <td class=ul><input type=text name='Email' value='$w[Email]' size=50 maxlength=50></td></tr>
  <tr><td class=inp1>Alamat</td>
    <td class=ul><textarea name='Alamat' cols=40 rows=4>$w[Alamat]</textarea></td></tr>
  <tr><td class=inp1>Kota</td>
    <td class=ul><input type=text name='Kota' value='$w[Kota]' size=30 maxlength=50></td></tr>
  <tr><td class=inp1>Propinsi</td>
    <td class=ul><input type=text name='Propinsi' value='$w[Propinsi]' size=30 maxlength=50></td></tr>
  <tr><td class=inp1>Negara</td>
    <td class=ul><input type=text name='Negara' value='$w[Negara]' size=30 maxlength=30></td></tr>
  <tr><td colspan=2><input class='btn btn-success btn-sm' type=submit name='Simpan' value='Simpan'>
    <input class='btn btn-primary btn-sm' type=reset name='Reset' value='Reset'></td></tr>
  </table>
  </div>
</div>
</div></p>";
}

function SimpanProfilPengguna() {
  global $koneksi;
  $TabelUser = $_REQUEST['TabelUser'];
  $LoginUser = $_REQUEST['LoginUser'];
  $Nama = sqling($_REQUEST['Nama']);
  $Telephone = sqling($_REQUEST['Telephone']);
  $Handphone = sqling($_REQUEST['Handphone']);
  $Email = sqling($_REQUEST['Email']);
  $Alamat = sqling($_REQUEST['Alamat']);
  $Kota = sqling($_REQUEST['Kota']);
  $Propinsi = sqling($_REQUEST['Propinsi']);
  $Negara = sqling($_REQUEST['Negara']);
  // Simpan
  $s = "update $TabelUser set Nama='$Nama', Telephone='$Telephone', Handphone='$Handphone', Email='$Email',
    Alamat='$Alamat', Kota='$Kota', Propinsi='$Propinsi', Negara='$Negara'
    where Login='$LoginUser' ";
  $r = mysqli_query($koneksi, $s);
  echo Info("Berhasil", "Profile berhasil diperbaharui");
}

function NamaTahun($tahun, $prodi='') {
  $arr = array('1'=>'Ganjil', '2'=>'Genap', '1p'=>'Pendek Ganjil', '2p'=>'Pendek Genap');
  $_tahun = substr($tahun, 0, 4)+0;
  $_tahun1 = $_tahun + 1;
  $_smt = substr($tahun, 4, 4);
  $NamaSesi = (empty($prodi))? '' : AmbilOneField('prodi', 'ProdiID', $prodi, 'NamaSesi'). ' '; 
  return $NamaSesi .$arr[$_smt] . " $_tahun/$_tahun1";
}

function TampilkanPencarianMhsw($ndelox='academic_mhs', $lungo='CariMhsw', $btn=2) {
  global $arrID;
  $strbtn = "<input class='btn btn-success btn-sm' type=submit name='crmhsw' value='NIM'>&nbsp;";
  $strbtn .= ($btn >=2 )? "<input class='btn btn-primary btn-sm' type=submit name='crmhsw' value='Nama'>" : '';
  echo "<p>
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:50%' align='center'>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$ndelox'>
  <input type=hidden name='lungo' value='$lungo'>
  <tr><td class=ul colspan=2><b>$arrID[Nama]</b></td></tr>
  <tr><td class=inp1>Program Studi</td><td class=ul>$_SESSION[_ProdiID]</td></tr>
  <tr><td class=inp1>Cari Mahasiswa</td><td class=ul><input style='height:30px' type=text name='crmhswid' value='$_SESSION[crmhswid]' size=20 maxlength=50>
    $strbtn</td></tr>
  </form></table>
  </div>
</div>
</div>
  </p>";
}

function KonfirmasiTanggal($exec, $lungo) {
  $optmulai = AmbilComboTgl($_SESSION['TglMulai'], 'TglMulai');
  $optselesai = AmbilComboTgl($_SESSION['TglSelesai'], 'TglSelesai');
  echo <<<ESD
  <table align=center cellspacing=1 cellpadding=4 style="border: 1px solid gray">
  <form action='$exec' method=POST>
  <input type=hidden name='lungo' value='$lungo' />
  
  <tr><th colspan=2 style="border-bottom: 1px solid gray">Info Tanggal Laporan</th></tr>
  <tr><td nowrap valign=top>
      Tanggal Mulai:<br />
      $optmulai ~
      </td>
      <td nowrap valign=top>
      Tanggal Selesai:<br />
      $optselesai
      </td>
      </tr>
  
  <tr><td colspan=2 align=center>
      <input type=submit name='Cetak' value='Cetak Laporan' />
      <input type=button name='Close' value='Tutup' onClick="window.close()" />
      </td>
      </tr>
  </form>
  </table>
ESD;
}

function GetNextBPM() {
  global $_BPMDigit;
  $_BPMDigit = (empty($_BPMDigit))? 5 : $_BPMDigit;
  $thn = date('Y').'-';
  $panjang = str_pad('_', $_BPMDigit, '_');
  $bpmmx = AmbilOneField('bayarmhsw', "BayarMhswID like '".$thn.$panjang."' and KodeID", 
    KodeID, "max(BayarMhswID)");
  $bpmcnt = str_replace($thn, '', $bpmmx)+1;
  $bpmcnt = $thn.str_pad($bpmcnt, $_BPMDigit, '0', STR_PAD_LEFT);
  return $bpmcnt;
}

function GetNextNIMSementara($TahunNIM, $mhsw)
{ 
global $koneksi;
// Ambil Setup NIM Sementara
  $stp = AmbilOneField('prodi', 'ProdiID', $mhsw['ProdiID'], 'FormatNIMSementara');
  $YYYY = substr($TahunNIM, 0, 4);
  $YY = substr($TahunNIM, 2, 2);
  $tmp = $stp;
  $tmp = str_replace('~YY~', $YY, $tmp);
  $tmp = str_replace('~YYYY~', $YYYY, $tmp);
  // Ubah kode program
  $tmp = str_replace('~PRG~', $mhsw['ProgramID'], $tmp);
  // Ubah kode Status Awal Masuk Mhsw
  $tmp = str_replace('~STAWAL~', $mhsw['StatusAwalID'], $tmp);
  // untuk check
  $check = $tmp;
  $check = str_replace('~NMR3~', '___', $check);
  $check = str_replace('~NMR4~', '____', $check);
  $check = str_replace('~NMR5~', '_____', $check);
  // check dulu
  $s = "select max(MhswID) as LAST from mhsw where MhswID like '$check' ";
  $r = mysqli_query($koneksi, $s);
  $w = mysqli_fetch_array($r);
  
  if (empty($w['LAST'])) {
    $Last = $tmp;
    $Last = str_replace('~NMR3~', '001', $Last);
    $Last = str_replace('~NMR4~', '0001', $Last);
    $Last = str_replace('~NMR5~', '00001', $Last);
  }
  else {
    $_lst = $w['LAST'];
    $base = $tmp;
    $base = str_replace('~NMR3~', '', $base);
    $base = str_replace('~NMR4~', '', $base);
    $base = str_replace('~NMR5~', '', $base);
    $_lst = str_replace($base, '', $_lst) +1;
    // Format jumlah digit
    $Last = $tmp;
    $Last = str_replace('~NMR3~', str_pad($_lst, 3, '0', STR_PAD_LEFT), $Last);
    $Last = str_replace('~NMR4~', str_pad($_lst, 4, '0', STR_PAD_LEFT), $Last);
    $Last = str_replace('~NMR5~', str_pad($_lst, 5, '0', STR_PAD_LEFT), $Last);
  }
  return $Last;
}

function HitungBatasStudi($TahunID, $prodi) {
  $DefJumlahTahun = 3;
  $prd = AmbilFieldx('prodi', 'ProdiID', $w['ProdiID'], "*");
  $_thn = substr($TahunID, 0, 4);
  $_ses = substr($TahunID, 4, 1);
  $_jmlthn = ($prd['JumlahSesi'] == 0)? $DefJumlahTahun : floor($prd['BatasStudi']/$prd['JumlahSesi']);
  $_sisa = ($prd['JumlahSesi'] == 0)? 0 : $prd['BatasStudi'] % $prd['JumlahSesi'];
  $_BatasTahun = $_thn + $_jmlthn;
  $_BatasSemes = $_ses + $_sisa;
  $_BatasSemes = ($_BatasSemes > $prd['JumlahSesi'])? $_BatasSemes-$prd['JumlahSesi'] : $_BatasSemes;
  $BatasStudi = $_BatasTahun.$_BatasSemes;
  Return $BatasStudi;
}
function TambahBatasStudi($TahunID, $JmlSemester, $prodi) {
  $DefJumlahSesi = 2;
  $prd = AmbilFieldx('prodi', 'ProdiID', $w['ProdiID'], '*');
  $_thn = substr($TahunID, 0, 4);
  $_ses = substr($TahunID, 4, 1);
  $_jmlsesi = ($prd['JumlahSesi'] == 0)? $DefJumlahSesi : $prd['JumlahSesi'];
  $jmlsesi = $_ses + $JmlSemester;
  // hitung jumlah tahun
  $_jmlthn = floor($jmlsesi / $_jmlsesi);
  $_jmlthn = ($jmlsesi % $_jmlsesi == 0)? $_jmlthn-1 : $_jmlthn;
  $_thn += $_jmlthn;
  // hitung sesi
  $_akhir = $jmlsesi % $_jmlsesi;
  $_akhir = ($_akhir == 0)? $_jmlsesi : $_akhir;
  return "$_thn$_akhir";
}

function AmbilRadioProdi($prodis='', $nama='ProdiID') {
    global $koneksi;
  $s = "select * from prodi order by ProdiID";
  $r = mysqli_query($koneksi, $s);
  $rd = '';
  $nama .= "[]";
  while ($w = mysqli_fetch_array($r)) {
    $pos = strpos($prodis, $w['ProdiID']);
    $ck = ($pos === false)? '' : "checked";
    $rd .= "<input type=checkbox name='$nama' value='$w[ProdiID]' $ck>$w[ProdiID] - $w[Nama]<br>";
  }
  return $rd;
}

function AmbilRadio($_sql, $_name, $_disp, $_key, $_default='', $_pisah='<br>') {
  global $koneksi;
  $r = mysqli_query($koneksi, $_sql);
  $_ret = array();
  while ($w = mysqli_fetch_array($r)) {
    $ck = ($w[$_key] == $_default)? 'checked' : '';
    $_ret[] = "<input type=radio name='$_name' value='$w[$_key]' $ck> $w[$_disp]";
  }
  return implode($_pisah, $_ret);
}

function anti_injection($data){
  global $koneksi;
  $filter = mysqli_real_escape_string($koneksi, stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
  return $filter;
}

function getBulan($bln){
  switch ($bln){
      case 1: 
          return "Jan";
          break;
      case 2:
          return "Feb";
          break;
      case 3:
          return "Mar";
          break;
      case 4:
          return "Apr";
          break;
      case 5:
          return "Mei";
          break;
      case 6:
          return "Jun";
          break;
      case 7:
          return "Jul";
          break;
      case 8:
          return "Agu";
          break;
      case 9:
          return "Sep";
          break;
      case 10:
          return "Okt";
          break;
      case 11:
          return "Nov";
          break;
      case 12:
          return "Des";
          break;
  }
} 

function getBulanraport($bln){
  switch ($bln){
      case 1: 
          return "Januari";
          break;
      case 2:
          return "Februari";
          break;
      case 3:
          return "Maret";
          break;
      case 4:
          return "April";
          break;
      case 5:
          return "Mei";
          break;
      case 6:
          return "Juni";
          break;
      case 7:
          return "Juli";
          break;
      case 8:
          return "Agustus";
          break;
      case 9:
          return "September";
          break;
      case 10:
          return "Oktober";
          break;
      case 11:
          return "November";
          break;
      case 12:
          return "Desember";
          break;
  }
}

function combotgl($awal, $akhir, $var, $terpilih){
  echo "<select name=$var>";
  for ($i=$awal; $i<=$akhir; $i++){
    $lebar=strlen($i);
    switch($lebar){
      case 1:
      {
        $g="0".$i;
        break;     
      }
      case 2:
      {
        $g=$i;
        break;     
      }      
    }  
    if ($i==$terpilih)
      echo "<option value=$g selected>$g</option>";
    else
      echo "<option value=$g>$g</option>";
  }
  echo "</select> ";
}

function combobln($awal, $akhir, $var, $terpilih){
  echo "<select name=$var>";
  for ($bln=$awal; $bln<=$akhir; $bln++){
    $lebar=strlen($bln);
    switch($lebar){
      case 1:
      {
        $b="0".$bln;
        break;     
      }
      case 2:
      {
        $b=$bln;
        break;     
      }      
    }  
      if ($bln==$terpilih)
         echo "<option value=$b selected>$b</option>";
      else
        echo "<option value=$b>$b</option>";
  }
  echo "</select> ";
}

function combothn($awal, $akhir, $var, $terpilih){
  echo "<select name=$var>";
  for ($i=$awal; $i<=$akhir; $i++){
    if ($i==$terpilih)
      echo "<option value=$i selected>$i</option>";
    else
      echo "<option value=$i>$i</option>";
  }
  echo "</select> ";
}

function combonamabln($awal, $akhir, $var, $terpilih){
  $nama_bln=array(1=> "Januari", "Februari", "Maret", "April", "Mei", 
                      "Juni", "Juli", "Agustus", "September", 
                      "Oktober", "November", "Desember");
  echo "<select name=$var>";
  for ($bln=$awal; $bln<=$akhir; $bln++){
      if ($bln==$terpilih)
         echo "<option value=$bln selected>$nama_bln[$bln]</option>";
      else
        echo "<option value=$bln>$nama_bln[$bln]</option>";
  }
  echo "</select> ";
}

function GetNextNIM($TahunNIM, $mhsw) {
  global $koneksi;
  $stp = AmbilOneField('prodi', 'ProdiID', $mhsw['ProdiID'], 'FormatNIM');
  $YYYY = substr($TahunNIM, 0, 4);
  $YY = substr($TahunNIM, 2, 2);
  $tmp = $stp;
  $tmp = str_replace('~YY~', $YY, $tmp);
  $tmp = str_replace('~YYYY~', $YYYY, $tmp);
  // Ubah kode program
  $tmp = str_replace('~PRG~', $mhsw['ProgramID'], $tmp);
  // Ubah kode Status Awal Masuk Mhsw
  $tmp = str_replace('~STAWAL~', $mhsw['StatusAwalID'], $tmp);
  // untuk check
  $check = $tmp;
  $check = str_replace('~NMR3~', '___', $check);
  $check = str_replace('~NMR4~', '____', $check);
  $check = str_replace('~NMR5~', '_____', $check);
  // check dulu
  $s = "select max(MhswID) as LAST from mhsw where MhswID like '$check' ";
  $r = mysqli_query($koneksi, $s);
  $w = mysqli_fetch_array($r);
  
  if (empty($w['LAST'])) {
    $Last = $tmp;
    $Last = str_replace('~NMR3~', '001', $Last);
    $Last = str_replace('~NMR4~', '0001', $Last);
    $Last = str_replace('~NMR5~', '00001', $Last);
  }
  else {
    $_lst = $w['LAST'];
    $base = $tmp;
    $base = str_replace('~NMR3~', '', $base);
    $base = str_replace('~NMR4~', '', $base);
    $base = str_replace('~NMR5~', '', $base);
    $_lst = str_replace($base, '', $_lst) +1;
    // Format jumlah digit
    $Last = $tmp;
    $Last = str_replace('~NMR3~', str_pad($_lst, 3, '0', STR_PAD_LEFT), $Last);
    $Last = str_replace('~NMR4~', str_pad($_lst, 4, '0', STR_PAD_LEFT), $Last);
    $Last = str_replace('~NMR5~', str_pad($_lst, 5, '0', STR_PAD_LEFT), $Last);
  }
  return $Last;
}


function Terbilang($x)
{
$abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
if ($x < 12)
return " " . $abil[$x];
elseif ($x < 20)
return Terbilang($x - 10) . " belas";
elseif ($x < 100)
return Terbilang($x / 10) . " puluh" . Terbilang($x % 10);
elseif ($x < 200)
return " seratus" . Terbilang($x - 100);
elseif ($x < 1000)
return Terbilang($x / 100) . " ratus" . Terbilang($x % 100);
elseif ($x < 2000)
return " seribu" . Terbilang($x - 1000);
elseif ($x < 1000000)
return Terbilang($x / 1000) . " ribu" . Terbilang($x % 1000);
elseif ($x < 1000000000)
return Terbilang($x / 1000000) . " juta" . Terbilang($x % 1000000);
}

//rosihanari.et
function selisihHari($tglAwal, $tglAkhir)
{
// list tanggal merah selain hari minggu
//$tglLibur = Array("2013-01-04", "2013-01-05", "2013-01-17");
$tglLibur = Array("2019-12-07","2019-12-14","2019-12-21","2019-12-28","2019-12-22","2019-12-29","2020-01-04","2020-01-04","2019-01-19","2019-01-26","2019-01-02","2019-01-09");

// memecah string tanggal awal untuk mendapatkan
// tanggal, bulan, tahun
$pecah1 = explode("-", $tglAwal);
$date1 = $pecah1[2];
$month1 = $pecah1[1];
$year1 = $pecah1[0];

// memecah string tanggal akhir untuk mendapatkan
// tanggal, bulan, tahun
$pecah2 = explode("-", $tglAkhir);
$date2 = $pecah2[2];
$month2 = $pecah2[1];
$year2 =  $pecah2[0];

// mencari selisih hari dari tanggal awal dan akhir
$jd1 = GregorianToJD($month1, $date1, $year1);
$jd2 = GregorianToJD($month2, $date2, $year2);

$selisih = $jd2 - $jd1;

// proses menghitung tanggal merah dan hari minggu
// di antara tanggal awal dan akhir
for($i=1; $i<=$selisih; $i++){
  // menentukan tanggal pada hari ke-i dari tanggal awal
  $tanggal = mktime(0, 0, 0, $month1, $date1+$i, $year1);
  $tglstr = date("Y-m-d", $tanggal);

  // menghitung jumlah tanggal pada hari ke-i
  // yang masuk dalam daftar tanggal merah selain minggu
  if (in_array($tglstr, $tglLibur)){
     $libur1++;
  }

  // menghitung jumlah tanggal pada hari ke-i
  // yang merupakan hari minggu
  if ((date("N", $tanggal) == 7)){
     $libur2++;
  }
}

// menghitung selisih hari yang bukan tanggal merah dan hari minggu
return $selisih-$libur1-$libur2;
}

// leweh add
function strfilter($input)
    {
        $input=trim($input);
        $input=strip_tags($input);
        $input=nl2br($input);
        $input=addslashes($input);
        $input=stripslashes($input);
        $input=str_ireplace("'", "%", $input);
        $input=str_ireplace( "''", '%', $input );
        $input=str_ireplace( '""', '%', $input );
        $query = preg_replace( '|(?<!%)%s|', "'%s'", $input );
        $input=htmlentities($input, ENT_QUOTES);
        $input=ltrim($input);
        $input=rtrim($input);
        return $input;
    }	
//end leweh add 




function TitleApps($str='') {
  echo "<p align=center><b style='color:purple;font-size:20px'>$str</b></p>";
  //echo "<div class=Judul align=center><b>$str</b></div>";
}


?>
