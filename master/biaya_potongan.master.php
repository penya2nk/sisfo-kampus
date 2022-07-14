<?php

function DftrBipotMaster() {
  $ka = (empty($_REQUEST['sub1']))? DftrBipotIsi() : $_REQUEST['sub1']();
  $ki = AmbilDaftarBipotMaster();
  
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'><table id='example' class='table table-sm table-striped'>
  <td valign=top width=400 class=kolkir>$ki</td>
  <td valign=top class=kolkan>$ka</td>
  </table></div>
  </div>
  </div></p>";
}

function AmbilDaftarBipotMaster() {
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
  $a = "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'><table class=box cellspacing=1 cellpadding=4 width=100%>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$ndelox'>
  <input type=hidden name='tok' value='$tok'>
  <input type=hidden name='bipot' value='0'>
  <tr><td class=inp>Program</td><td class=ul1><select name='prid'>$optprid</select></td></tr>
  <tr><td class=inp>Program Studi</td><td class=ul1><select name='prodi'>$optprodi</select></td></tr>
  <tr><td colspan=2><input class='btn btn-success btn-sm' type=submit name='Jalankan' value='Jalankan'></td></tr>
  </form></table></div>
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
  <div class='table-responsive'><table id='example' class='table table-sm table-striped'>
  <tr><td colspan=6 class=ul1><a href='?ndelox=$ndelox&tok=$tok&sub1=BipotMasterEdt&md=1'>Tambah Master Biaya & Potongan</a></td></tr>
  <tr><th></th>
    <th class=ttl>Tahun</th><th class=ttl>Nama Master</th>
    <th class=ttl title='Default'>Def</th>
    <th class=ttl title='Tidak aktif'>NA</th>
    <th></th>
  </tr>";
  while ($w = mysqli_fetch_array($r)) {
    if ($w['NA']=='N'){
      $stat="<i style='color:purple' class='fa fa-eye'></i>";
    }else{
      $stat="<i style='color:red' class='fa fa-eye-slash'></i>";
    }
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
        <a href='?ndelox=$ndelox&tok=$tok&sub1=BipotMasterEdt&md=0&bipotid=$w[BIPOTID]'  title='Edit Master'><i class='fa fa-edit'>
      $w[Tahun]</a></td>
      <td $c><a href='?ndelox=$ndelox&tok=$tok&sub=&bipotid=$w[BIPOTID]' title='Lihat Detail'>
        $w[Nama]</a></td>
      <td $d align=center width=5><img src='img/$w[Def].png'></td>
      <td $c align=center width=5>$stat</td>
      <td $c width=2>$_ka</td>
      </tr>";
  }
  return "$a</table></div>
  </div>
  </div></p>";
}
function BipotMasterEdt() {
  global $arrID, $ndelox, $tok, $koneksi;
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
  <div class='table-responsive'><table id='example' class='table table-sm table-striped'>
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
  <tr><td colspan=2><input class='btn btn-success btn-sm' type=submit name='Simpan' value='Simpan'>
    <input class='btn btn-primary btn-sm' type=reset name='Reset' value='Reset'>
    <input class='btn btn-danger btn-sm' type=button name='Batal' value='Batal' onClick=\"location='?ndelox=$ndelox&tok=$tok&$snm=$sid'\"></td></tr>
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
  <div class='table-responsive'><table id='example' class='table table-sm table-striped'>
    <tr style='background:purple;color:white'><td class=ul colspan=10><b>$JDL</b></td></tr>
    <tr><th class=ttl>#</th>
    <th class=ttl>Nama</th>
    <th class=ttl style='text-align:right'>Jumlah</th>
    <th class=ttl title='Otomatis' style='text-align:center'>Oto/Scr?</th>
    <th class=ttl style='text-align:center'>Mulai/Brp &times;</th>
    <th class=ttl>Stt Awal Mhs</th>
    <th class=ttl>Grade USM</th>
	  <th class=ttl>GradeIPK</th>
    <th class=ttl style='text-align:center'>NA</th>
    </tr>";
}
function DftrBipotIsi1() {
  global $ndelox, $tok, $koneksi;
  $arrbenar = AmbilFieldx('bipot', 'BIPOTID', $_SESSION['bipotid'], "ProgramID, ProdiID");
  if (($arrbenar['ProgramID'] == $_SESSION['prid']) and ($arrbenar['ProdiID'] == $_SESSION['prodi'])) {
    $s = "select b2.*, bn.Nama, format(b2.Jumlah, 0) as JML,
      t.Nama as NMTRX, s.Nama as SAAT
      from bipot2 b2
        left outer join bipotnama bn on b2.BIPOTNamaID=bn.BIPOTNamaID
        left outer join saat s on b2.SaatID=s.SaatID
        left outer join trx t on b2.TrxID=t.TrxID
      where b2.BIPOTID='$_SESSION[bipotid]' and KodeID='$_SESSION[KodeID]'
      order by b2.TrxID, b2.Prioritas, b2.GradeNilai, b2.GradeIPK";
    $r = mysqli_query($koneksi, $s);
    $ftr = "</table></div>
    </div>
    </div></p>";
    $TrxID = -100;
    $cnt = 0;
    $a = BuatMenuBipotIsi();
    while ($w = mysqli_fetch_array($r)) {
      if ($w['NA']=='N'){
        $stat="<i style='color:purple' class='fa fa-eye'></i>";
      }else{
        $stat="<i style='color:red' class='fa fa-eye-slash'></i>";
      }

      if ($w['Otomatis']=='Y'){
        $oto="<i style='color:green' class='fa fa-check-circle'></i>";
      }else{
        $oto="<i style='color:red' class='fa fa-minus-circle'></i>";
      }

      if ($w['GunakanGradeNilai']=='N'){
        $gunakan_grade_nilai="<i style='color:red' class='fa fa-minus-circle'></i>";
      }else{
        $$gunakan_grade_nilai="<i style='color:red' class='fa fa-minus-circle'></i>";
      }
      
      if ($w['GunakanGradeIPK']=='N'){
        $gunakan_grade_ipk="<i style='color:red' class='fa fa-minus-circle'></i>";
      }else{
        $$gunakan_grade_ipk="<i style='color:red' class='fa fa-minus-circle'></i>";
      }
      

      // Buat header & footer
      if ($TrxID != $w['TrxID']) {
        $TrxID = $w['TrxID'];
        if ($cnt > 0) $a .= $ftr;
        $a .= HdrBipotIsi($w['NMTRX'], $TrxID);
      }
      
      // menggunakan script?
      $scr = ($w['GunakanScript']=='Y')? "<img src='img/gear.gif' width=20 title='$w[NamaScript]'>" : "&nbsp;";
      // Tampilkan data
      $cnt++;
      $c = ($w['NA'] == 'Y')? 'class=nac' : 'class=ul';
      $KaliSesi = (empty($w['KaliSesi']) or $w['KaliSesi']==0)? '&#8734;' : $w['KaliSesi'];
	  $TambahanNama = (empty($w['TambahanNama']))? '' : "($w[TambahanNama])";
	  $PerMataKuliah = ($w['PerMataKuliah'] == 'Y')? "<img src='img/permatakuliah.bmp'" : '';
	  $PerSKS = ($w['PerSKS'] == 'Y')? "<img src='img/persks.bmp'" : ''; 
	  $PerLab = ($w['PerLab'] == 'Y')? "<img src='img/perlab.bmp'" : '';
	  $Remedial = ($w['Remedial'] == 'Y') ? "<sup>REMEDIAL</sup>" : '';
	  $PraktekKerja = ($w['PraktekKerja'] == 'Y')? "<sup>PRAKTEK KERJA</sup>": '';
	  $a .= "<tr>
      <td $c nowrap align=center>
        <a href='?ndelox=$ndelox&tok=$tok&sub1=BipotIsiEdt&md=0&bipot2=$w[BIPOT2ID]&trxid=$w[TrxID]&trxnama=$w[NMTRX]'>
        $w[Prioritas] <i class='fa fa-edit'></i></a>
      </td>
      <td $c>$w[Nama] $TambahanNama $PerMataKuliah$PerLab$Remedial$PraktekKerja$PerSKS</td>
      <td $c align=right>$w[JML] / $w[SAAT]</td>
      <td $c align=center>$oto $scr</td>
      <td $c style='text-align:center'> $w[MulaiSesi] / $KaliSesi &times</td>
      <td $c>$w[StatusAwalID]  $w[StatusMhswID]</td>
      <td $c align=center>$gunakan_grade_nilai $w[GradeNilai]&nbsp;</td>
	    <td $c align=center>$gunakan_grade_ipk $w[GradeIPK]&nbsp;</td>
      <td $c align=center>$stat</td>
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
  while ($w = mysqli_fetch_array($r)) {
    $arr[] = "<a href='?ndelox=$ndelox&tok=$tok&sub1=BipotIsiEdt&md=1&trxid=$w[TrxID]&trxnama=$w[Nama]'>Tambah $w[Nama]</a>";
  }
  $a .= implode(' &#9889; ', $arr);
  $a .= " &#9889; <a href='?ndelox=$ndelox&tok=$tok&sub1=BipotCopy'>Salin Dari Tahun Lain</a>
    &#9889;
    <a href='#' onClick=\"javascript:CopyProdiLain($_SESSION[bipotid])\">Salin Dari Prodi Lain</a>
    &#9889;
    <a href='master/bipot.cetak.php?bipotid=$_SESSION[bipotid]' target=_blank>Cetak</a>";
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
    $w['BIPOTID'] = $_SESSION['bipotid'];
    $w['BIPOTNamaID'] = 0;
	$w['TambahanNama'] = '';
    $w['Prioritas'] = 0;
    $w['TrxID'] = $_REQUEST['trxid'];
    $w['Jumlah'] = 0;
    $w['KaliSesi'] = 0;
	$w['MulaiSesi'] = 0;
    $w['Otomatis'] = 'Y';
	$w['PerMataKuliah'] = 'N';
	$w['PerSKS'] = 'N';
	$w['PerLab'] = 'N';
	$w['Remedial'] = 'N';
	$w['PraktekKerja'] = 'N';
    $w['SaatID'] = 1;
    $w['StatusMhswID'] = '.A.';
    $w['StatusPotonganID'] = '';
    $w['StatusAwalID'] = '.B.';
    $w['GunakanGradeNilai'] = 'N';
	$w['GunakanGradeIPK'] = 'N';
    $w['GradeNilai'] = '';
	$w['GradeIPK'] = '';
    $w['GunakanScript'] = 'N';
    $w['NamaScript'] = '';
    $w['NA'] = 'N';
    $jdl = "Tambah $_REQUEST[trxnama]";
  }
  // setup
  $NA = ($w['NA'] == 'Y')? 'checked' : '';
  $OTO = ($w['Otomatis'] == 'Y')? 'checked' : '';
  $PMK = ($w['PerMataKuliah'] == 'Y')? 'checked' : '';
  $PSKS = ($w['PerSKS'] == 'Y')? 'checked' : '';
  $PLab = ($w['PerLab'] == 'Y')? 'checked' : '';
  $PRem = ($w['Remedial'] == 'Y')? 'checked' : '';
  $PPK = ($w['PraktekKerja'] == 'Y')? 'checked' : '';
  $GunakanGradeNilai = ($w['GunakanGradeNilai'] == 'Y')? 'checked' : '';
  $grdnilai = AmbilCekBox('pmbgrade', "GradeNilai", "concat(GradeNilai, ' ( ', Keterangan, ' ) ') as PMBGRD", "PMBGRD", $w['GradeNilai'], '.');
  $GunakanGradeIPK = ($w['GunakanGradeIPK'] == 'Y')? 'checked' : '';
  $grdipk = AmbilCekBox('gradeipk', "GradeIPK", "concat(GradeIPK, ' ( ', Keterangan, ' ) ') as IPKGRD", "IPKGRD", $w['GradeIPK'], '.');
  $GunakanScript = ($w['GunakanScript'] == 'Y')? 'checked' : '';
  $optnama = AmbilCombo2('bipotnama', 'Nama', 'Nama', $w['BIPOTNamaID'], "TrxID=$_REQUEST[trxid]", 'BIPOTNamaID');
  $optsaat = AmbilCombo2('saat', "concat(SaatID, '. ', Nama)", 'SaatID', $w['SaatID'], '', 'SaatID');
  //AmbilCekBox($table, $key, $Fields, $Label, $Nilai='', $Separator=',') {
  $stamhsw = AmbilCekBox('statusmhsw', "StatusMhswID",
    "concat(StatusMhswID, ' - ', Nama) as STA", 'STA', $w['StatusMhswID'], '.');
  $staawal = AmbilCekBox('statusawal', 'StatusAwalID',
    "concat(StatusAwalID, ' - ', Nama) as STA", 'STA', $w['StatusAwalID'], '.');

  // Tuliskan
  CheckFormScript("BIPOTNamaID,Jumlah,SaatID");
  return "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'><table id='example' class='table table-sm table-striped'>
  <form name='bipotform' action='?' method=POST onSubmit=\"return CheckForm(this)\">
  <input type=hidden name='ndelox' value='$ndelox'>
  <input type=hidden name='tok' value='$tok'>
  <input type=hidden name='sub1' value='BipotIsiSav'>
  <input type=hidden name='md' value='$md'>
  <input type=hidden name='trxid' value='$_REQUEST[trxid]'>
  <input type=hidden name='bipot2' value='$w[BIPOT2ID]'>

  <tr><th class=ttl colspan=2><b>$jdl</th></tr>
  <tr><td class=inp>Prioritas Pembayaran</td>
    <td class=ul><input type=text name='Prioritas' value='$w[Prioritas]' size=4 maxlength=3></td></tr>
  <tr><td class=inp>Nama $_REQUEST[trxnama]</td><td class=ul><select name='BIPOTNamaID'>$optnama</select></td></tr>
  <tr><td class=inp>Tambahan Nama</td><td class=ul><input type=text name='TambahanNama' value='$w[TambahanNama]' size=30 maxlength=100></td></tr>
  <tr><td class=inp>Jumlah Rp.</td><td class=ul><input type=text name='Jumlah' value='$w[Jumlah]' size=20 maxlength=15></td></tr>
  <tr><td class=inp>Berapa kali sesi?</td><td class=ul><input type=text name='KaliSesi' value='$w[KaliSesi]' size=5 maxlength=5> Isikan 0 jika tidak ditentukan.</td></tr>
  <tr><td class=inp>Mulai sesi keberapa?</td><td class=ul><input type=text name='MulaiSesi' value='$w[MulaiSesi]' size=5 maxlength=5> Isikan 0 jika tidak ditentukan.</td></tr>
  <tr><td class=inp>Dikenakan otomatis?</td><td class=ul><input type=checkbox name='Otomatis' value='Y' $OTO></td></tr>
  <tr><td class=inp>Dikenakan per mata kuliah?</td><td class=ul><input type=checkbox id='PerMataKuliah' name='PerMataKuliah' value='Y' $PMK ></td></tr>
  <tr><td class=inp>Dikenakan per Lab/Responsi?</td><td class=ul><input type=checkbox id='PerLab' name='PerLab' value='Y' $PLab></td></tr>
  <tr><td class=inp>Dikenakan per Remedial?<td class=ul1><input type=checkbox id='Remedial' name='Remedial' value='Y' $PRem></td></tr>
  <tr><td class=inp>Dikenakan per Praktek Kerja?</td><td class=ul><input type=checkbox id='PraktekKerja' name='PraktekKerja' value='Y' $PPK ></td></tr>
  <tr><td class=inp>Dikenakan per SKS?</td><td class=ul><input type=checkbox id='PerSKS' name='PerSKS' value='Y' $PSKS><font color=red>*) Bila dicentang, Biaya Per di atas akan dikenakan per SKS mata kuliahnya</td></tr>
  <tr><td class=inp>Dikenakan saat</td><td class=ul><select name='SaatID'>$optsaat</select></td></tr>
  <tr><td class=inp>Status Awal</td><td class=ul>$staawal</td></tr>
  <tr><td class=inp>Status Mahasiswa</td><td class=ul>$stamhsw</td></tr>
  <tr><td class=inp rowspan=2>Grade USM</td>
    <td class=ul><input type=checkbox name='GunakanGradeNilai' value='Y' $GunakanGradeNilai> Cek Grade Nilai USM?</td></tr>
    <tr><td class=ul>$grdnilai</td></tr>
  <tr><td class=inp rowspan=2>Grade IPK</td>
    <td class=ul><input type=checkbox name='GunakanGradeIPK' value='Y' $GunakanGradeIPK> Cek Grade IPK? <font color=red>*) Tidak berlaku untuk biaya masuk mahasiswa baru</font></td></tr>
    <tr><td class=ul>$grdipk</td></tr>
  <tr><td class=inp>Tidak aktif (NA)?</td><td class=ul><input type=checkbox name='NA' value='Y' $NA></td></tr>

  <tr><td class=inp>Gunakan Script External?</td>
    <td class=ul><input type=checkbox name='GunakanScript' value='Y' $GunakanScript><br />
    Nama Script: <input type=text name='NamaScript' value='$w[NamaScript]' size=30 maxlength=200></td></tr>
  <tr><td colspan=2><input class='btn btn-success btn-sm' type=submit name='Simpan' value='Simpan'>
    <input class='btn btn-primary btn-sm' type=reset name='Reset' value='Reset'>
    <input class='btn btn-danger btn-sm' type=button name='Batal' value='Batal' onClick=\"location='?ndelox=$ndelox&tok=$tok'\"></td></tr>
  </form></table></div>
  </div>
  </div></p>
  ";
}
function BipotIsiSav() {
  global $koneksi;
  $md = $_REQUEST['md']+0;
  $trxid = $_REQUEST['trxid'];
  $Prioritas = $_REQUEST['Prioritas']+0;
  $BIPOTNamaID = $_REQUEST['BIPOTNamaID'];
  $TambahanNama = $_REQUEST['TambahanNama'];
  $Jumlah = $_REQUEST['Jumlah']+0;
  $MulaiSesi = $_REQUEST['MulaiSesi']+0;
  $KaliSesi = $_REQUEST['KaliSesi']+0;
  $Otomatis = (empty($_REQUEST['Otomatis']))? 'N' : $_REQUEST['Otomatis'];
  $PerMataKuliah = (empty($_REQUEST['PerMataKuliah']))? 'N' : $_REQUEST['PerMataKuliah'];
  $PerSKS = (empty($_REQUEST['PerSKS']))? 'N' : $_REQUEST['PerSKS'];
  $PerLab = (empty($_REQUEST['PerLab']))? 'N' : $_REQUEST['PerLab'];
  $Remedial = (empty($_REQUEST['Remedial']))? 'N' : $_REQUEST['Remedial'];
  $PraktekKerja = (empty($_REQUEST['PraktekKerja']))? 'N' : $_REQUEST['PraktekKerja'];
  $SaatID = $_REQUEST['SaatID'];
  // Ambil Status Awal
  $_staawal = array();
  $_staawal = $_REQUEST['StatusAwalID'];
  $StatusAwalID = (empty($_staawal))? '' : '.'. implode('.', $_staawal) .'.';
  // Ambil Status Mhsw
  $_stamhsw = array();
  $_stamhsw = $_REQUEST['StatusMhswID'];
  $StatusMhswID = (empty($_stamhsw))? '' : '.'. implode('.', $_stamhsw) .'.';
  
  $NA = (empty($_REQUEST['NA']))? 'N' : $_REQUEST['NA'];
  $GunakanScript = (empty($_REQUEST['GunakanScript']))? 'N' : $_REQUEST['GunakanScript'];
  $NamaScript = ($GunakanScript == 'Y')? sqling($_REQUEST['NamaScript']) : '';
  $GunakanGradeNilai = (empty($_REQUEST['GunakanGradeNilai']))? 'N' : $_REQUEST['GunakanGradeNilai'];
  // Grade Nilai
  if ($GunakanGradeNilai == 'Y') {
    $_grdnilai = array();
    $_grdnilai = $_REQUEST['GradeNilai'];
    $GradeNilai = (empty($_grdnilai))? '' : '.'. implode('.', $_grdnilai) .'.';
  }
  else $GradeNilai = '';
  $GunakanGradeIPK = (empty($_REQUEST['GunakanGradeIPK']))? 'N' : $_REQUEST['GunakanGradeIPK'];
  // Grade IPK
  if ($GunakanGradeIPK == 'Y') {
    $_grdIPK = array();
    $_grdIPK = $_REQUEST['GradeIPK'];
    $GradeIPK = (empty($_grdIPK))? '' : '.'. implode('.', $_grdIPK) .'.';
  }
  else $GradeIPK = '';
  
  // Simpan
  //$adakah = AmbilOneField('bipot2', 'Bipot')
  if ($md == 0) {
    $s = "update bipot2 set Prioritas='$Prioritas',
      BIPOTNamaID='$BIPOTNamaID', TambahanNama='$TambahanNama', Jumlah='$Jumlah',
      KaliSesi='$KaliSesi', MulaiSesi='$MulaiSesi', Otomatis='$Otomatis', 
	  PerMataKuliah='$PerMataKuliah', PerSKS='$PerSKS', PerLab='$PerLab', Remedial='$Remedial', PraktekKerja='$PraktekKerja', SaatID='$SaatID',
      StatusAwalID='$StatusAwalID', StatusMhswID='$StatusMhswID',
      GunakanScript='$GunakanScript', NamaScript='$NamaScript',
      GunakanGradeNilai='$GunakanGradeNilai', GradeNilai='$GradeNilai',
      GunakanGradeIPK='$GunakanGradeIPK', GradeIPK='$GradeIPK',
	  NA='$NA'
      where BIPOT2ID='$_REQUEST[bipot2]' ";
  }
  else {
    $s = "insert into bipot2
      (BIPOTID, Prioritas, BIPOTNamaID, TambahanNama, Jumlah, KaliSesi, MulaiSesi, Otomatis, PerMataKuliah, PerSKS, PerLab, Remedial, PraktekKerja, SaatID,
      StatusAwalID, StatusMhswID, NA, TrxID,
      GunakanGradeNilai, GradeNilai,
	  GunakanGradeIPK, GradeIPK,
      GunakanScript, NamaScript)
      values('$_SESSION[bipotid]', '$Prioritas',
      '$BIPOTNamaID', '$TambahanNama', '$Jumlah', '$KaliSesi', '$MulaiSesi', '$Otomatis', '$PerMataKuliah', '$PerSKS', '$PerLab', '$Remedial', '$PraktekKerja', '$SaatID',
      '$StatusAwalID', '$StatusMhswID', '$NA', '$trxid',
      '$GunakanGradeNilai', '$GradeNilai',
      '$GunakanGradeIPK', '$GradeIPK',
	  '$GunakanScript', '$NamaScript')";
  }
  //echo $s;
  $r = mysqli_query($koneksi, $s);
  return DftrBipotIsi();
}
function BipotCopy() {
  global $ndelox, $tok, $koneksi;
  $bipotid = $_SESSION['bipotid'];
  $bipot = AmbilOneField('bipot', 'BIPOTID', $bipotid, "concat(Tahun, ' - ', Nama)");
  // Ambil Daftar
  $s = "select b.BIPOTID, b.Tahun, b.Nama
    from bipot b
    where b.KodeID='$_SESSION[KodeID]'
      and b.ProgramID='$_SESSION[prid]'
      and b.ProdiID='$_SESSION[prodi]'
      and b.BIPOTID<>$bipotid
      and b.NA='N'
    order by b.Nama";
  $r = mysqli_query($koneksi, $s);
  $opt = "<option value=''> </option>";
  while ($w = mysqli_fetch_array($r)) {
    $opt .= "<option value='$w[BIPOTID]'>$w[Tahun] - $w[Nama]</option>";
  }
  CopyProdiLainScript();
  $a = "<p>
  <table id='example' class='table table-sm table-striped'>
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
    <input class='btn btn-danger btn-sm' type=submit name='Copy' value='Delete & Copy'>
    <input class='btn btn-primary btn-sm' type=button name='Batal' value='Batal' onClick=\"location='?ndelox=$ndelox&tok=$tok'\">
    |
    <input class='btn btn-info btn-sm' type=button name='btnCopyProdiLain' value='Copy Dari Prodi Lain'
      onClick=\"javascript:CopyProdiLain($bipotid)\" />
    </td></tr>
  </form>
  </table></p>";
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
    $s2 = "insert into bipot2(BIPOTID, BIPOTNamaID, TambahanNama, 
      TrxID, Prioritas, Jumlah, KaliSesi, MulaiSesi, 
      Otomatis, PerMataKuliah, PerSKS, PerLab, Remedial, PraktekKerja, SaatID, 
      StatusMhswID, StatusPotonganID, StatusAwalID,
      GunakanGradeNilai, GradeNilai,
	  GunakanGradeIPK, GradeIPK,
      GunakanScript, NamaScript,
      LoginBuat, TglBuat)
      values ('$bipotid', '$w1[BIPOTNamaID]', '$TambahanNama', 
      '$w1[TrxID]', '$w1[Prioritas]', '$w1[Jumlah]', '$w1[KaliSesi]', '$w1[MulaiSesi]', 
      '$w1[Otomatis]', '$w1[PerMataKuliah]', '$w1[PerSKS]', '$w1[PerLab]', '$w1[Remedial]', '$w1[PraktekKerja]', '$w1[SaatID]',
      '$w1[StatusMhswID]', '$w1[StatusPotonganID]', '$w1[StatusAwalID]',
      '$w1[GunakanGradeNilai]', '$w1[GradeNilai]',
	  '$w1[GunakanGradeIPK]', '$w1[GradeIPK]',
      '$w1[GunakanScript]', '$w1[NamaScript]',
      '$_SESSION[_Login]', now())";
    $r2 = mysqli_query($koneksi, $s2);
  }
  return DftrBipotIsi();
}
?>
