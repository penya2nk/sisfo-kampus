<?php

function DftrBipotMaster() {
  $ka = (empty($_REQUEST['sub1']))? DftrBipotIsi() : $_REQUEST['sub1']();
  $ki = AmbilDaftarBipotMaster();
  
  echo "<p>
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table class=bsc cellspacing=1 cellpadding=0 width=100%>
  <td valign=top width=400 class=kolkir>$ki</td>
  <td valign=top class=kolkan>$ka</td>
  </table></div>
  </div>
  </div></p>";
}
function AmbilDaftarBipotMaster() {
  global $koneksi;
  $filter = AmbilFilterBipotMaster();
  $daftar = '';
  if (!empty($_SESSION['prodi']) && !empty($_SESSION['prid']))
    $daftar = DftrBipot();
  
  return $filter.$daftar;
}
function AmbilFilterBipotMaster() {
  global $arrID, $ndelox, $tok;
  $optprid = AmbilCombo2('program', "concat(ProgramID, ' - ', Nama)", 'ProgramID', $_SESSION['prid'], '', 'ProgramID');
  $optprodi = AmbilCombo2('prodi', "concat(ProdiID, ' - ', Nama)", 'ProdiID', $_SESSION['prodi'], '', 'ProdiID');
  $a = "<p>
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table class=box cellspacing=1 cellpadding=4 width=100%>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$ndelox'>
  <input type=hidden name='tok' value='$tok'>
  <input type=hidden name='bipot' value='0'>
  <tr><td class=inp>Program</td><td class=ul1><select name='prid'>$optprid</select></td></tr>
  <tr><td class=inp>Program Studi</td><td class=ul1><select name='prodi'>$optprodi</select></td></tr>
  <tr><td colspan=2><input type=submit name='Jalankan' value='Jalankan'></td></tr>
  </form></table>
  </div>
</div>
</div>";
  return $a;
}
function DftrBipot() {
  global $ndelox, $tok, $arrID, $koneksi;
  $s = "select *
    from bipot
    where KodeID='$_SESSION[KodeID]' and ProdiID='$_SESSION[prodi]' and ProgramID='$_SESSION[prid]'
    order by Tahun desc";
  $r = mysqli_query($koneksi, $s);
  
  $a = "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'><table class=box cellspacing=1 cellpadding=4 width=100%>
  <tr><td colspan=6 class=ul1><a href='?ndelox=$ndelox&tok=$tok&sub1=BipotMasterEdt&md=1'>Tambah Master Biaya & Potongan</a></td></tr>
  <tr><th></th>
    <th class=ttl>Tahun</th><th class=ttl>Nama Master</th>
    <th class=ttl title='Default'>Def</th>
    <th class=ttl title='Tidak aktif'>NA</th>
    <th></th>
  </tr>";
  while ($w = mysqli_fetch_array($r)) {
    $c = ($w['NA'] == 'Y')? 'class=nac' : 'class=ul1';
    $d = ($w['Def'] == 'Y')? 'class=ul1' : 'class=nac';
    if ($w['BIPOTID'] == $_SESSION['bipotid']) {
      $_ki = "&#9658;";
      $_ka = "&#9668;";
    }
    else {
      $_ki = '&nbsp;';
      $_ka = '&nbsp;';
    }
    
    $a .= "<tr>
      <td $c width=2>$_ki</td>
      <td $c align=center>
        <a href='?ndelox=$ndelox&tok=$tok&sub1=BipotMasterEdt&md=0&bipotid=$w[BIPOTID]'  title='Edit Master'><i class='fa fa-edit'></i>
        <br />
      $w[Tahun]</a></td>
      <td $c><a href='?ndelox=$ndelox&tok=$tok&sub=&bipotid=$w[BIPOTID]' title='Lihat Detail'>
        $w[Nama]</a></td>
      <td $d align=center width=5><img src='img/$w[Def].gif'></td>
      <td $c align=center width=5><img src='img/book$w[NA].gif'></td>
      <td $c width=2>$_ka</td>
      </tr>";
  }
  return "$a</table></div>
  </div>
  </div></p>";
}
function BipotMasterEdt() {
  global $arrID, $ndelox, $tok;
  $md = $_REQUEST['md']+0;
  if ($md == 0) {
    $w = AmbilFieldx('bipot', "BIPOTID", $_REQUEST['bipotid'], '*');
    $jdl = "Edit Master Biaya & Potongan";
  }
  else {
    $w = array();
    $w['BIPOTID'] = 0;
    $w['Tahun'] = '';
    $w['Nama'] = '';
    $w['Catatan'] = '';
    $w['Def'] = 'N';
    $w['NA'] = 'N';
    $w['SP'] = 'N';
    $jdl = "Tambah Master Biaya & Potongan";
  }
  $NA = ($w['NA'] == 'Y')? 'checked' : '';
  $Def = ($w['Def'] == 'Y')? 'checked' : '';
  $SP = ($w['SP'] == 'Y') ? 'checked' : '';
  $snm = session_name(); $sid = session_id();
  CheckFormScript("Tahun,Nama");
  $a = "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'><table class=box cellspacing=1 cellpadding=4>
  <form action='?' method=POST onSubmit=\"return CheckForm(this)\">
  <input type=hidden name='ndelox' value='$ndelox'>
  <input type=hidden name='tok' value='$tok'>
  <input type=hidden name='sub1' value='BipotMasterSav'>
  <input type=hidden name='md' value='$md'>
  <input type=hidden name='bipotid' value='$w[BIPOTID]'>
  
  <tr><th class=ul colspan=2><b>$jdl</b></td></tr>
  <tr><td class=inp>Kode Tahun</td><td class=ul><input type=text name='Tahun' value='$w[Tahun]' size=10 maxlength=10></td></tr>
  <tr><td class=inp>Nama Master</td><td class=ul><input type=text name='Nama' value='$w[Nama]' size=40 maxlength=50></td></tr>
  <tr><td class=inp>Catatan</td><td class=ul><textarea name='Catatan' cols=30 rows=3>$w[Catatan]</textarea></td></tr>
  <tr><td class=inp>Default?</td><td class=ul><input type=checkbox name='Def' value='Y' $Def></td></tr>
  <tr><td class=inp>Semester Pendek?</td><td class=ul><input type=checkbox name='SP' value='Y' $SP></td></tr>
  <tr><td class=inp>Tidak Aktif (NA)?</td><td class=ul><input type=checkbox name='NA' value='Y' $NA></td></tr>
  <tr><td colspan=2><input type=submit name='Simpan' value='Simpan'>
    <input type=reset name='Reset' value='Reset'>
    <input type=button name='Batal' value='Batal' onClick=\"location='?ndelox=$ndelox&tok=$tok&$snm=$sid'\"></td></tr>
  </form></table></div>
  </div>
  </div></p>";
  return $a;
}
function BipotMasterSav() {
  global $koneksi;
  $md = $_REQUEST['md']+0;
  $Tahun = sqling($_REQUEST['Tahun']);
  $Nama = sqling($_REQUEST['Nama']);
  $Catatan = sqling($_REQUEST['Catatan']);
  $Def = (empty($_REQUEST['Def']))? 'N' : $_REQUEST['Def'];
  $NA = (empty($_REQUEST['NA']))? 'N' : $_REQUEST['NA'];
  $SP = (empty($_REQUEST['SP']))? 'N' : $_REQUEST['SP'];
  // Simpan
  if ($md == 0) {
    $BIPOTID = $_REQUEST['bipotid'];
    $s = "update bipot set Nama='$Nama', Tahun='$Tahun', Catatan='$Catatan', 
      Def='$Def', NA='$NA', SP='$SP',
      LoginEdit='$_SESSION[_Login]', TglEdit=now()
      where BIPOTID='$BIPOTID' ";
    $r = mysqli_query($koneksi, $s);
  }
  else {
    $s = "insert into bipot (Tahun, Nama, KodeID, ProgramID, ProdiID, Catatan, 
      Def, NA, SP,
      TglBuat, LoginBuat)
      values('$Tahun', '$Nama', '$_SESSION[KodeID]', '$_SESSION[prid]',
      '$_SESSION[prodi]', '$Catatan', 
      '$Def', '$NA', '$SP',
      now(), '$_SESSION[_Login]')";
    $r = mysqli_query($koneksi, $s);
    // Ambil Last_Insert_ID
    $s_last = "select LAST_INSERT_ID() as ID";
    $r_last = mysqli_query($koneksi, $s_last);
    $w_last = mysqli_fetch_array($r_last);
    $BIPOTID = $w_last['ID'];
  }
  
  // Apakah diset menjadi default?
  if ($Def == 'Y') {
    $sd = "update bipot set Def='N' 
      where ProgramID='$_SESSION[prid]' and ProdiID='$_SESSION[prodi]'
      and BIPOTID<>$BIPOTID";
    //echo $sd;
    $rd = mysqli_query($koneksi, $sd);
  }
  return DftrBipotIsi();
}
function DftrBipotIsi() {
  global $ndelox, $tok;
  if (!empty($_SESSION['prid']) && !empty($_SESSION['prodi']))
    $a = DftrBipotIsi1();
  else $a = '';
  return $a;
}
function HdrBipotIsi($JDL='', $TrxID) {
  global $ndelox, $tok;
  if ($_SESSION['_LevelID'] == 1) {
    $del = "<th class=ttl>Del</th>";
  }
  return "<p><div class='card'>
<div class='card-header'>
<div class='table-responsive'><table class=box cellspacing=1 cellpadding=4 width=100%>
    <tr><td class=ul colspan=10><b>$JDL</b></td></tr>
    <tr><th class=ttl>#</th>
    <th class=ttl>Nama</th>
    <th class=ttl>Jumlah</th>
	<th class=ttl>Persen</th>
    <th class=ttl>Stt<br />Awal
      <hr size=1 color=silver />
      Mhsw
      </th>
    <th class=ttl>Min<br />IPK</th>
	<th class=ttl>Max<br />IPK</th>
    <th class=ttl>NA</th>
    </tr>";
}
function DftrBipotIsi1() {
  global $ndelox, $tok, $koneksi;
  $arrbenar = AmbilFieldx('beasiswa', 'BeasiswaID', $_SESSION['beasiswaid'], "ProgramID, ProdiID");
  if (($arrbenar['ProgramID'] == $_SESSION['prid']) and ($arrbenar['ProdiID'] == $_SESSION['prodi'])) {
    $s = "select b.*
			from beasiswa b
			where BIPOTID = $_SESSION[bipotid] and KodeID='".KodeID."'
			order by b.IPKMin DESC, b.IPKMax DESC";
    $r = mysqli_query($koneksi, $s);
    $ftr = "</table></p>";
    $TrxID = -100;
    $cnt = 0;
    $a = BuatMenuBipotIsi();
    while ($w = mysqli_fetch_array($r)) {
      
      $a .= HdrBipotIsi('Beasiswa', $TrxID);
      
      // menggunakan script?
      // Tampilkan data
      $cnt++;
      $c = ($w['NA'] == 'Y')? 'class=nac' : 'class=ul';
      $a .= "<tr>
      <td $c nowrap align=center>
        <a href='?ndelox=$ndelox&tok=$tok&sub1=BIPOTIsiEdt&md=0'>
        $cnt
        <br />
        <i class='fa fa-edit'></i>
        </a></td>
      <td $c>$w[Nama]</td>
      <td $c align=right>
        $w[Jumlah] 
        <hr size=1 color=silver />
        <div align=left></div>
        </td>
	  <td $c align=right>
        $w[Persen] 
        <hr size=1 color=silver />
        <div align=left></div>
        </td>
      <td $c>$w[StatusAwalID]
        <hr size=1 color=silver />
        $w[StatusMhswID]</td>
      <td $c align=center>
        $w[IPKMin]&nbsp;</td>
	  <td $c align=center>
        $w[IPKMax]&nbsp;</td>
      <td $c align=center><img src='img/book$w[NA].gif'></td>
      </tr>";
      $a .= "<tr><td bgcolor=silver colspan=10 height=1></td></tr>";
    }
    $a .= "</table></div>
    </div>
    </div></p>";
  }
  else $a = '';
  return $a;
}
function CopyProdiLainScript() {
  echo <<<ESD
  <script>
  function CopyProdiLain(bipotid) {
    lnk = "$_SESSION[ndelox].copyprodilain.php?bipotid="+bipotid;
    win2 = window.open(lnk, "", "width=400, height=300, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
ESD;
}
function BuatMenuBipotIsi() {
  CopyProdiLainScript();
  global $ndelox, $tok, $koneksi;
  $s = "select * from trx order by TrxID";
  $r = mysqli_query($koneksi, $s);
  $a = "<p>";
  $arr = array();
  $a .= "<a href='?ndelox=$ndelox&tok=$tok&sub1=BipotCopy'>Salin Dari Tahun Lain</a>
    &#9889;
    <a href='#' onClick=\"javascript:CopyProdiLain($_SESSION[bipotid])\">Salin Dari Prodi Lain</a>
    &#9889;
    <a href='cetak/bipot.cetak.php?lungo=DetailBIPOT&bipotid=$_SESSION[bipotid]' target=_blank>Cetak</a>";
  return $a."</p>";
}
function BipotIsiEdt() {
  global $ndelox, $tok;
  $fakultas = substr($_SESSION['prodi'], 0, 1);

  $md = $_REQUEST['md'] +0;
  // Jika Edit
  if ($md == 0) {
    $bipot2 = $_REQUEST['bipot2'];
    $w = AmbilFieldx('bipot2', "BIPOT2ID", $bipot2, '*');
    $jdl = "Edit $_REQUEST[trxnama]";
  }
  // Jika tambah
  else {
    $w = array();
    $w['BeasiswaID'] = $_SESSION['beasiswaid'];
    $w['BIPOTNamaID'] = 0;
    $w['IPKMin'] = 0.00;
	$w['IPKMax'] = 0.00;
	$w['Keterangan'] = '';
	$w['NA'] = 'N';
    $jdl = "Tambah $_REQUEST[trxnama]";
  }
  // setup
  $NA = ($w['NA'] == 'Y')? 'checked' : '';
  $optnama = AmbilCombo2('bipotnama', 'Nama', 'Nama', $w['BIPOTNamaID'], "TrxID=$_REQUEST[trxid]", 'BIPOTNamaID');
  
  // Tuliskan
  CheckFormScript("BIPOTNamaID,Jumlah,SaatID");
  return "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'><table class=box cellspacing=1 cellpadding=4 width=100%>
  <form action='?' method=POST onSubmit=\"return CheckForm(this)\">
  <input type=hidden name='ndelox' value='$ndelox'>
  <input type=hidden name='tok' value='$tok'>
  <input type=hidden name='sub1' value='BipotIsiSav'>
  <input type=hidden name='md' value='$md'>
  <input type=hidden name='trxid' value='$_REQUEST[trxid]'>
  <input type=hidden name='bipot2' value='$w[BIPOT2ID]'>

  <tr><th class=ttl colspan=2><b>$jdl</th></tr>
  <tr><td class=inp>Nama $_REQUEST[trxnama]</td><td class=ul><select name='BIPOTNamaID'>$optnama</select></td></tr>
  <tr><td class=inp>Jumlah Rp.</td><td class=ul><input type=text name='Jumlah' value='$w[Jumlah]' size=20 maxlength=15></td></tr>
  <tr><td class=inp>Persen</td><td class=ul><input type=text name='Persen' value='$w[Persen]' size=5 maxlength=5> Isikan 0 jika tidak ditentukan.</td></tr>  
  <tr><td class=inp>Status Mahasiswa</td><td class=ul>$stamhsw</td></tr>
  <tr><td class=inp>Tidak aktif (NA)?</td><td class=ul><input type=checkbox name='NA' value='Y' $NA></td></tr>

  <tr><td colspan=2><input type=submit name='Simpan' value='Simpan'>
    <input type=reset name='Reset' value='Reset'>
    <input type=button name='Batal' value='Batal' onClick=\"location='?ndelox=$ndelox&tok=$tok'\"></td></tr>
  </form></table></div>
  </div>
  </div></p>";
}
function BipotIsiSav() {
  global $koneksi;
  $md = $_REQUEST['md']+0;
  $BIPOTNamaID = $_REQUEST['BIPOTNamaID'];
  $Jumlah = $_REQUEST['Jumlah']+0;
  $Persen = $_REQUEST['Persen']+0;
  // Ambil Status Mhsw
  $_stamhsw = array();
  $_stamhsw = $_REQUEST['StatusMhswID'];
  $StatusMhswID = (empty($_stamhsw))? '' : '.'. implode('.', $_stamhsw) .'.';
  
  $NA = (empty($_REQUEST['NA']))? 'N' : $_REQUEST['NA'];
  
  // Simpan
  //$adakah = AmbilOneField('bipot2', 'Bipot')
  if ($md == 0) {
    $s = "update beasiswa set 
      BIPOTNamaID='$BIPOTNamaID', Jumlah='$Jumlah',
      Persen='$Persen', StatusMhswID='$StatusMhswID',
      NA='$NA'
      where BeasiswaID='$_REQUEST[beasiswaid]' ";
  }
  else {
    $s = "insert into beasiswa
      (BeasiswaID, 
	  BIPOTNamaID, Jumlah, Persen, 
      StatusMhswID, NA)
      values('$_SESSION[beasiswaid]',
      '$BIPOTNamaID', '$Jumlah', '$Persen', 
	  '$StatusMhswID', '$NA')";
  }
  //echo $s;
  $r = mysqli_query($koneksi, $s);
  return DftrBipotIsi();
}
function BipotCopy() {
  global $ndelox, $tok, $koneksi;
  $bipotid = $_SESSION['beasiswaid'];
  $bipot = AmbilOneField('beasiswa', 'BeasiswaID', $beasiswaid, "concat(Tahun, ' - ', Nama)");
  // Ambil Daftar
  $s = "select b.BeasiswaID, b.Tahun, b.Nama
    from beasiswa b
    where b.KodeID='$_SESSION[KodeID]'
      and b.ProgramID='$_SESSION[prid]'
      and b.ProdiID='$_SESSION[prodi]'
      and b.BeasiswaID<>$beasiswaid
      and b.NA='N'
    order by b.Nama";
  $r = mysqli_query($koneksi, $s);
  $opt = "<option value=''> </option>";
  while ($w = mysqli_fetch_array($r)) {
    $opt .= "<option value='$w[BIPOTID]'>$w[Tahun] - $w[Nama]</option>";
  }
  CopyProdiLainScript();
  $a = "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'><table class=box cellspacing=1 cellpadding=4>
  <form action='?' method=POST name='data'>
  <input type=hidden name='ndelox' value='$ndelox'>
  <input type=hidden name='tok' value='$tok'>
  <input type=hidden name='sub1' value='BipotCopySav'>
  <input type=hidden name='bipotid' value='$bipotid'>
  <tr><td class=ul colspan=2>Anda akan menyalin dari Master BIPOT:</td></tr>
  <tr><td class=inp>Dari Master :</td><td class=ul><select name='CopyID'>$opt</select></td></tr>
  <tr><td class=inp>Ke Master :</td><td class=ul><b>$bipot</b></td></tr>
  <tr><td class=ul colspan=2>Proses penyalinan ini akan melakukan:
  <ol>
    <li>Menghapus semua biaya & potongan dari master biaya & potongan ini.</li>
    <li>Menyalin semua biaya & potongan dari master biaya & potongan lain.</li>
  </ol>
  </td></tr>
  <tr><td class=ul colspan=2>
    <input type=submit name='Copy' value='Delete & Copy'>
    <input type=button name='Batal' value='Batal' onClick=\"location='?ndelox=$ndelox&tok=$tok'\">
    |
    <input type=button name='btnCopyProdiLain' value='Copy Dari Prodi Lain'
      onClick=\"javascript:CopyProdiLain($bipotid)\" />
    </td></tr>
  </form>
  </table></div>
  </div>
  </div></p>";
  return $a;
}
function BipotCopySav() {
  global $koneksi;
  $bipotid = $_REQUEST['bipotid'];
  $CopyID = $_REQUEST['CopyID'];
  // Kosongkan bipot2 dari tujuan
  $s = "delete from bipot2 where BIPOTID='$bipotid' ";
  $r = mysqli_query($koneksi, $s);
  // Ambil data dari bipot2
  $s1 = "select * from bipot2 where BIPOTID='$CopyID' ";
  $r1 = mysqli_query($koneksi, $s1);
  while ($w1 = mysqli_fetch_array($r1)) {
    $s2 = "insert into bipot2(BIPOTID, BIPOTNamaID,
      TrxID, Prioritas, Jumlah, KaliSesi, MulaiSesi, 
      Otomatis, SaatID, 
      StatusMhswID, StatusPotonganID, StatusAwalID,
      GunakanGradeNilai, GradeNilai,
      GunakanScript, NamaScript,
      LoginBuat, TglBuat)
      values ('$bipotid', '$w1[BIPOTNamaID]',
      '$w1[TrxID]', '$w1[Prioritas]', '$w1[Jumlah]', '$w1[KaliSesi]', '$w1[MulaiSesi]', 
      '$w1[Otomatis]', '$w1[SaatID]',
      '$w1[StatusMhswID]', '$w1[StatusPotonganID]', '$w1[StatusAwalID]',
      '$w1[GunakanGradeNilai]', '$w1[GradeNilai]',
      '$w1[GunakanScript]', '$w1[NamaScript]',
      '$_SESSION[_Login]', now())";
    $r2 = mysqli_query($koneksi, $s2);
  }
  return DftrBipotIsi();
}
?>
