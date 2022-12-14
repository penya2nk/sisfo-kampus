<?php
$arrBulan = array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
  'Agustus', 'September', 'Oktober', 'November', 'Desember');
$_PMBMinimalFields = "Nama,ProgramID,Pilihan1,TempatLahir,Agama,Alamat,Telepon";

function ResetPMB() {
  $w = array();
  $w['PMBID'] = '';
  $w['PMBRef'] = '';
  $w['PSSBID'] = '';
  $w['PMBPeriodID'] = AmbilOneField('pmbperiod', 'NA', 'N', 'PMBPeriodID');
  $w['Nama'] = '';
  $w['KodeID'] = $_SESSION['KodeID'];
  $w['ProdiID'] = '';
  $w['ProgramID'] = $_SESSION['pmbfid'];
  $w['PMBFormJualID'] = '';
  $w['BuktiSetoran'] = '';
  $w['StatusAwalID'] = 'B';
  $w['Kelamin'] = 'P';
  $w['WargaNegara'] = 'WNI';
  $w['Kebangsaan'] = '';
  $w['TempatLahir'] = '';
  $w['TanggalLahir'] = date('Y-m-d');
  $w['Agama'] = '';
  $w['StatusSipil'] = '';
  $w['Alamat'] = '';
  $w['Kota'] = '';
  $w['RT'] = '';
  $w['RW'] = '';
  $w['KodePos'] = '';
  $w['Propinsi'] = '';
  $w['Negara'] = '';
  $w['Telepon'] = '';
  $w['Handphone'] = '';
  $w['Email'] = '';
  $w['AlamatAsal'] = '';
  $w['KotaAsal'] = '';
  $w['RTAsal'] = '';
  $w['RWAsal'] = '';
  $w['KodePosAsal'] = '';
  $w['PropinsiAsal'] = '';
  $w['NegaraAsal'] = '';
  $w['TeleponAsal'] = '';
  $w['NamaAyah'] = '';
  $w['AgamaAyah'] = '';
  $w['PendidikanAyah'] = '';
  $w['PekerjaanAyah'] = '';
  $w['HidupAyah'] = '';
  $w['NamaIbu'] = '';
  $w['AgamaIbu'] = '';
  $w['PendidikanIbu'] = '';
  $w['PekerjaanIbu'] = '';
  $w['HidupIbu'] = '';
  $w['AlamatOrtu'] = '';
  $w['KotaOrtu'] = '';
  $w['RTOrtu'] = '';
  $w['RWOrtu'] = '';
  $w['KodePosOrtu'] = '';
  $w['PropinsiOrtu'] = '';
  $w['NegaraOrtu'] = '';
  $w['TeleponOrtu'] = '';
  $w['HandphoneOrtu'] = '';
  $w['EmailOrtu'] = '';
  $w['AsalSekolah'] = '';
  $w['JenisSekolahID'] = '';
  $w['AlamatSekolah'] = '';
  $w['KotaSekolah'] = '';
  $w['JurusanSekolah'] = '';
  $w['NilaiSekolah'] = '';
  $w['TahunLulus'] = date('Y');
  $w['AsalPT'] = '';
  $w['ProdiAsalPT'] = '';
  $w['LulusAsalPT'] = 'Y';
  $w['TglLulusAsalPT'] = date('Y-m-d');
  $w['Pilihan1'] = '';
  $w['Pilihan2'] = '';
  $w['Pilihan3'] = '';
  $w['Harga'] = 0;
  $w['BIPOTID'] = 0;
  return $w;
}
function GetAsalSekolah1($def='') {
  $fname = "pmb.daftarsekolah.txt";
  $f = fopen($fname, "r");
  $isi = fread($f, filesize($fname));
  fclose($f);
  $_arrisi = explode("\n\r", $isi);
  $a = '';
  for ($i=0; $i<sizeof($_arrisi); $i++) {
    list($kode, $nama, $kota) = explode('->', $_arrisi[$i]);
    $slc = ($kode == $def)? 'selected' : '';
    $a .= "<option value='$kode' $slc>$nama - $kota</option>";
  }
  return $a;
}
function GetAsalSekolah($def='') {
  $fname = "pmb.daftarsekolah.txt";
  $f = fopen($fname, "r");
  $a = '';
  while (!feof($f)) {
    $str = fgets($f);
    list($kode, $nama, $kota) = explode('->', $str);
    $slc = ($kode == $def)? 'selected' : '';
    $a .= "<option value='$kode' $slc>$nama - $kota</option>";
  }
  fclose($f);
  return $a;
}
function CariSekolahScript() {
  echo <<<EOF
  <SCRIPT LANGUAGE="JavaScript1.2">
  <!--
  function carisekolah(frm){
    lnk = "carisekolah.php?SekolahID="+frm.AsalSekolah.value+"&Cari="+frm.NamaSekolah.value;
    win2 = window.open(lnk, "", "width=600, height=600, scrollbars, status");
    win2.creator = self;
  }
  function caript(frm){
    lnk = "cariperguruantinggi.php?PerguruanTinggiID="+frm.AsalPT.value+"&Cari="+frm.NamaPT.value;
    win2 = window.open(lnk, "", "width=600, height=600, scrollbars, status");
    win2.creator = self;
  }
  -->
  </script>
EOF;
}
// OBSOLETE
function CariSekolahScript_x() {
  echo <<<END
  <script>
  <!--
  function carisekolah(frm) {
    alert(frm.NamaSekolah.value);
  }
  -->
  </script>
END;
}
function PMBEdt($ndelox='pmbform.edt', $pmbgos='PMBSav') {
  Global $arrID, $_PMBMinimalFields, $_PMBAdminJalurKhusus;
  CheckFormScript($_PMBMinimalFields);
  CariSekolahScript();
  $md = $_REQUEST['md']+0;
  if ($md == 0) {
    $w = AmbilFieldx('pmbweb', 'PMBID', $_REQUEST['pmbid'], '*');
    $jdl = "Edit Formulir PMB";
    $_pmbid = "<input type=hidden name='PMBID' value='$w[PMBID]'><b>$w[PMBID]</b>";
  }
  else {
    //if (empty($_SESSION['pmbfid'])) die(PesanError("Kesalahan Formulir",
    //  "Pilih salah satu jenis formulir terlebih dahulu."));
    //else {
      $w = ResetPMB();
      $w['PMBFormulirID'] = $_SESSION['pmbfid'];
      $jdl = "Tambah Formulir PMB";
      $_pmbid = "<input type=hidden name='PMBID' value=''><font color=red>[AutoNumber]</font>";
    //}
  }
  $NamaSekolah = AmbilOneField('asalsekolah', 'SekolahID', $w['AsalSekolah'], "concat(Nama, ', ', Kota)");
  $NamaSekolah = ($NamaSekolah == ', ') ? '' : $NamaSekolah;
  // setup formulir
  $jenisfrm = AmbilFieldx('pmbformulir', 'PMBFormulirID', $w['PMBFormulirID'], '*');
  $w['Harga'] = $jenisfrm['Harga'];
  $_pil = GetOpsiPilihanProdi($w, $jenisfrm);
  $optkelamin = AmbilRadio("select * from kelamin where NA='N'", 'Kelamin', 'Nama', 'Kelamin', $w['Kelamin']);
  // Jika jalur khusus!
  $JalurKhusus = (strpos($_PMBAdminJalurKhusus, ".$_SESSION[_LevelID].") === false)? " and JalurKhusus='N'" : " and JalurKhusus='Y'";
  $optstatusawal = AmbilRadio("select * from statusawal where NA='N' $JalurKhusus", "StatusAwalID", "Nama", "StatusAwalID", $w['StatusAwalID']);
  $optwarga = AmbilRadio("select * from warganegara where NA='N'", 'WargaNegara', 'Nama', 'WargaNegara', $w['WargaNegara']);
  $_TanggalLahir = AmbilComboTgl($w['TanggalLahir'], 'TanggalLahir');
  $_Agama = AmbilCombo2('agama', "concat(Agama, ' - ', Nama)", '', $w['Agama'], '', 'Agama');
  $_AgamaAyah = AmbilCombo2('agama', "concat(Agama, ' - ', Nama)", '', $w['AgamaAyah'], '', 'Agama');
  $_AgamaIbu = AmbilCombo2('agama', "concat(Agama, ' - ', Nama)", '', $w['AgamaIbu'], '', 'Agama');
  $_StatusSipil = AmbilCombo2('statussipil', "concat(StatusSipil, ' - ', Nama)", '', $w['StatusSipil'], '', 'StatusSipil');

  $_PendidikanAyah = AmbilCombo2('pendidikanortu', "concat(Pendidikan, ' - ', Nama)", 'Pendidikan', $w['PendidikanAyah'], '', 'Pendidikan');
  $_PekerjaanAyah = AmbilCombo2('pekerjaanortu', "concat(Pekerjaan, ' - ', Nama)", 'Pekerjaan', $w['PekerjaanAyah'], '', 'Pekerjaan');
  $_HidupAyah = AmbilCombo2('hidup', "concat(Hidup, ' - ', Nama)", 'Hidup', $w['HidupAyah'], '', 'Hidup');

  $_PendidikanIbu = AmbilCombo2('pendidikanortu', "concat(Pendidikan, ' - ', Nama)", 'Pendidikan', $w['PendidikanIbu'], '', 'Pendidikan');
  $_PekerjaanIbu = AmbilCombo2('pekerjaanortu', "concat(Pekerjaan, ' - ', Nama)", 'Pekerjaan', $w['PekerjaanIbu'], '', 'Pekerjaan');
  $_HidupIbu = AmbilCombo2('hidup', "concat(Hidup, ' - ', Nama)", 'Hidup', $w['HidupIbu'], '', 'Hidup');
  //$_AsalSekolah = GetAsalSekolah($w['AsalSekolah']);
  $_JurusanSekolah = AmbilCombo2('jurusansekolah', "concat(Nama, ' - ', NamaJurusan)", 'Nama', $w['JurusanSekolah'], '', 'JurusanSekolahID');
  $_Program = AmbilCombo2('program', "concat(ProgramID, ' - ', Nama)", 'Nama', $_REQUEST['prgid'], "KodeID='HTP'", 'ProgramID');
  $optprodiasalpt = AmbilCombo2('asalprodi',
    "concat(ProdiID, ' - ', Nama)", 'ProdiID', $w['ProdiAsalPT'], '', 'ProdiID');
  $_LulusAsalPT = ($w['LulusAsalPT'] == 'Y')? 'checked' : '';
  $NamaPT = AmbilOneField('perguruantinggi', "PerguruanTinggiID", $w['AsalPT'], "concat(Nama,', ', Kota)");
  $TL = AmbilComboTgl($w['TglLulusAsalPT'], 'TL');

  $c = 'class=ul';
  $button = "<tr><td colspan=3><input type=submit name='Simpan' value='Simpan'>
    <input type=reset name='Reset' value='Reset'>
    <input type=button name='Batal' value='Batal' onClick=\"location='?ndelox=pmbform'\">
    </td></tr>";
  // Tampilkan formulir
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table class=box cellspacing=1 cellpadding=4>
  <form name='data' action='$act' method=POST onSubmit=\"return CheckForm(this);\">
  <input type=hidden name='md' value='$md'>
  <input type=hidden name='ndelox' value='$ndelox'>
  <input type=hidden name='lungo' value='$pmbgos'>
  <input type=hidden name='JumlahPilihan' value='$jenisfrm[JumlahPilihan]'>
  <input type=hidden name='Harga' value='$jenisfrm[Harga]'>
  <input type=hidden name='PMBFormulirID' value='$_SESSION[pmbfid]'>
  <input type=hidden name='pmbaktif' value='$_SESSION[pmbaktif]'>

  <tr><td class=ul colspan=3><strong>$arrID[Nama]</strong></td></tr>
  <tr><th class=ttl colspan=3>$jdl</th></tr>

  <tr><td $c>No. PMB</td><td $c>:</td><td $c>$_pmbid</td></tr>
  <tr><td $c>Nama</td><td $c>:</td><td $c><input type=text name='Nama' value='$w[Nama]' size=50 maxlength='50'></td></tr>
  <tr><td $c>Status Awal Masuk</td><td $c>:</td><td $c>$optstatusawal</td></tr>
  <tr><td $c>Jenis Kelamin</td><td $c>:</td><td $c>$optkelamin</td></tr>
  <tr><td $c>Tempat Lahir</td><td $c>:</td><td $c><input type=text name='TempatLahir' value='$w[TempatLahir]' size=50 maxlength=50></td></tr>
  <tr><td $c>Tanggal Lahir</td><td $c>:</td><td $c>$_TanggalLahir</td></tr>

  <tr><td class=ttl colspan=3><b>Pilihan Program Studi</td></tr>
  <tr><td $c>Program</td><td $c>:</td><td $c><select name='ProgramID'>$_Program</select></td></tr>
  $_pil

  $button

  <tr><td class=ttl colspan=4><b>Data Pribadi (Sesuai KTP)</td></tr>
  <tr><td $c>Warga Negara</td><td $c>:</td><td $c>$optwarga <br>
    Jika WNA, sebutkan: <input type=text name='Kebangsaan' value='$w[Kebangsaan]' size=20 maxlength=50></td></tr>
  <tr><td $c>Agama</td><td $c>:</td><td $c><select name='Agama'>$_Agama</select></td></tr>
  <tr><td $c>Status Sipil</td><td $c>:</td><td $c><select name='StatusSipil'>$_StatusSipil</select></td></tr>

  <tr><td $c>Alamat Tinggal</td><td $c>:</td><td $c><textarea name='Alamat' cols=40 rows=2>$w[Alamat]</textarea></td></tr>
  <tr><td $c>Kota</td><td $c>:</td><td $c><input type=text name='Kota' value='$w[Kota]' size=40 maxlength=50></td></tr>
  <tr><td $c>RT/RW</td><td $c>:</td><td $c><input type=text name='RT' value='$w[RT]' size=10 maxlength=5> /
    <input type=text name='RW' value='$w[RW]' size=10 maxlength=5></td></tr>

  <tr><td $c>Kode Pos</td><td $c>:</td><td $c><input type=text name='KodePos' value='$w[KodePos]' size=30 maxlength=50></td></tr>
  <tr><td $c>Propinsi</td><td $c>:</td><td $c><input type=text name='Propinsi' value='$w[Propinsi]' size=30 maxlength=50></td></tr>
  <tr><td $c>Negara</td><td $c>:</td><td $c><input type=text name='Negara' value='$w[Negara]' size=30 maxlength=50></td></tr>

  <tr><td $c>Telepon</td><td $c>:</td><td $c><input type=text name='Telepon' value='$w[Telepon]' size=40 maxlength=50></td></tr>
  <tr><td $c>Telp. Bergerak</td><td $c>:</td><td $c><input type=text name='Handphone' value='$w[Handphone]' size=40 maxlength=50></td></tr>
  <tr><td $c>Email</td><td $c>:</td><td $c><input type=text name='Email' value='$w[Email]' size=40 maxlength=100></td></tr>

  $button

  <tr><td class=ttl colspan=3><b>Asal Sekolah</td></tr>
  <tr><td $c>Kode Sekolah</td><td $c>:</td><td $c><input type='text' readonly='true' name='AsalSekolah' value='$w[AsalSekolah]' size=20 maxlength=50></td></tr>
  <tr><td $c>Nama Sekolah</td><td $c>:</td><td $c><input type=text name='NamaSekolah' value='$NamaSekolah' size=50 maxlength=255>
    <a href='javascript:carisekolah(data)'>Cari</a></td></tr>
  <tr><td $c>Jurusan</td><td $c>:</td><td $c><select name='JurusanSekolah'>$_JurusanSekolah</select></td></tr>
  <tr><td $c>Tahun Lulus</td><td $c>:</td><td $c><input type=text name='TahunLulus' value='$w[TahunLulus]' size=10 maxlength=5></td></tr>
  <tr><td $c>Nilai Kelulusan</td><td $c>:</td><td $c><input type=text name='NilaiSekolah' value='$w[NilaiSekolah]' size=5 maxlength=10></td></tr>
  
  <tr><td class=ttl colspan=3><b>Asal Perguruan Tinggi</td></tr>
  <tr><td $c colspan=3>Diisi bila mahasiswa merupakan mahasiswa
  pindahan atau mahasiswa pendaftar Program S2 (Pasca Sarjana).</td></tr>
  <tr><td $c>Asal Perguruan Tinggi</td><td $c>:</td>
    <td $c><input type=text readonly= 'true' name='AsalPT' value='$w[AsalPT]' size=20></td></tr>
  <tr><td $c>Nama Perguruan Tinggi</td><td $c>:</td>
    <td $c><input type=text name='NamaPT' value='$NamaPT' size=50 maxlength=255>
    <a href='javascript:caript(data)'>Cari PT</a></td></tr>
  <tr><td $c>Dari Program Studi</td><td $c>:</td>
    <td $c><select name='ProdiAsalPT'>$optprodiasalpt</select></td></tr>
  <tr><td $c>Telah Lulus dr PT ini?</td><td $c>:</td>
    <td $c><input type=checkbox name='LulusAsalPT' value='Y' $_LulusAsalPT>
    Jika ya, maka lulus tanggal: $TL</td></tr>

  $button
  
  <tr><td class=ttl colspan=3><b>Alamat Tinggal di Jakarta</td></tr>
  <tr><td $c>Alamat</td><td $c>:</td><td $c><textarea name='AlamatAsal' cols=40 rows=2>$w[AlamatAsal]</textarea></td></tr>
  <tr><td $c>Kota</td><td $c>:</td><td $c><input type=text name='KotaAsal' value='$w[KotaAsal]' size=30 maxlength=50></td></tr>
  <tr><td $c>RT/RW</td><td $c>:</td><td $c><input type=text name='RTAsal' value='$w[RTAsal]' size=10 maxlength=5> /
    <input type=text name='RWAsal' value='$w[RWAsal]' size=10 maxlength=5></td></tr>
  <tr><td $c>Telepon</td><td $c>:</td><td $c><input type=text name='TeleponAsal' value='$w[TeleponAsal]' size=40 maxlength=50></td></tr>

  <tr><td $c>Kode Pos</td><td $c>:</td><td $c><input type=text name='KodePosAsal' value='$w[KodePosAsal]' size=30 maxlength=50></td></tr>
  <tr><td $c>Propinsi</td><td $c>:</td><td $c><input type=text name='PropinsiAsal' value='$w[PropinsiAsal]' size=30 maxlength=50></td></tr>
  <tr><td $c>Negara</td><td $c>:</td><td $c><input type=text name='NegaraAsal' value='$w[NegaraAsal]' size=30 maxlength=50></td></tr>

  $button

  <tr><td class=ttl colspan=3><b>Data Orang Tua</td></tr>
  <tr><td $c>Nama Ayah</td><td $c>:</td><td $c><input type=text name='NamaAyah' value='$w[NamaAyah]' size=40 maxlength=50></td></tr>
  <tr><td $c>Agama Ayah</td><td $c>:</td><td $c><select name='AgamaAyah'>$_AgamaAyah</select></td></tr>
  <tr><td $c>Pendidikan Ayah</td><td $c>:</td><td $c><select name='PendidikanAyah'>$_PendidikanAyah</select></td></tr>
  <tr><td $c>Pekerjaan Ayah</td><td $c>:</td><td $c><select name='PekerjaanAyah'>$_PekerjaanAyah</select></td></tr>
  <tr><td $c>Status Ayah</td><td $c>:</td><td $c><select name='HidupAyah'>$_HidupAyah</select></td></tr>

  <tr><td $c>Nama Ibu</td><td $c>:</td><td $c><input type=text name='NamaIbu' value='$w[NamaIbu]' size=40 maxlength=50></td></tr>
  <tr><td $c>Agama Ibu</td><td $c>:</td><td $c><select name='AgamaIbu'>$_AgamaIbu</select></td></tr>
  <tr><td $c>Pendidikan Ibu</td><td $c>:</td><td $c><select name='PendidikanIbu'>$_PendidikanIbu</select></td></tr>
  <tr><td $c>Pekerjaan Ibu</td><td $c>:</td><td $c><select name='PekerjaanIbu'>$_PekerjaanIbu</select></td></tr>
  <tr><td $c>Status Ibu</td><td $c>:</td><td $c><select name='HidupIbu'>$_HidupIbu</select></td></tr>

  <tr><td class=ttl colspan=3><b>Alamat Orang Tua</td></tr>
  <tr><td $c>Alamat Orang Tua</td><td $c>:</td><td $c><textarea name='AlamatOrtu' cols=40 rows=2>$w[AlamatOrtu]</textarea></td></tr>
  <tr><td $c>Kota</td><td $c>:</td><td $c><input type=text name='KotaOrtu' value='$w[KotaOrtu]' size=40 maxlength=50></td></tr>
  <tr><td $c>RT/RW</td><td $c>:</td><td $c><input type=text name='RTOrtu' value='$w[RTOrtu]' size=10 maxlength=5> /
    <input type=text name='RWOrtu' value='$w[RWOrtu]' size=10 maxlength=5></td></tr>

  <tr><td $c>Kode Pos</td><td $c>:</td><td $c><input type=text name='KodePosOrtu' value='$w[KodePosOrtu]' size=30 maxlength=50></td></tr>
  <tr><td $c>Propinsi</td><td $c>:</td><td $c><input type=text name='PropinsiOrtu' value='$w[PropinsiOrtu]' size=30 maxlength=50></td></tr>
  <tr><td $c>Negara</td><td $c>:</td><td $c><input type=text name='NegaraOrtu' value='$w[NegaraOrtu]' size=30 maxlength=50></td></tr>

  <tr><td $c>Telepon</td><td $c>:</td><td $c><input type=text name='TeleponOrtu' value='$w[TeleponOrtu]' size=40 maxlength=50></td></tr>
  <tr><td $c>Telp. Bergerak</td><td $c>:</td><td $c><input type=text name='HandphoneOrtu' value='$w[HandphoneOrtu]' size=40 maxlength=50></td></tr>
  <tr><td $c>Email</td><td $c>:</td><td $c><input type=text name='EmailOrtu' value='$w[EmailOrtu]' size=40 maxlength=100></td></tr>

  $button
  </form></table>
  </div>
</div>
</div><br>";
}
//  <tr><td $c>Alamat</td><td $c>:</td><td $c><textarea name='AlamatSekolah' cols=40 rows=3>$w[AlamatSekolah]</textarea></td></tr>
//  <tr><td $c>Kota</td><td $c>:</td><td $c><input type=text name='KotaSekolah' value='$w[KotaSekolah]' size=30 maxlength=50></td></tr>

function GetOpsiPilihanProdi($w, $jf) {
  $c = 'class=ul';
  $a = '';
  for ($i = 1; $i<=$jf['JumlahPilihan']; $i++) {
    $whr = '';
    // Hanya Prodi
    if (!empty($jf["HanyaProdi$i"])) {
      $h = explode('.', trim($jf["HanyaProdi$i"], '.'));
      $whr = "ProdiID in (" .implode(', ', $h).")";
    }
    else {
      // Kecuali Prodi
      if (!empty($jf["KecualiProdi$i"])) {
        $k = explode('.', trim($jf["KecualiProdi$i"], '.'));
        $whr = "not (ProdiID in (" . implode(', ', $k) ."))";
      }
    }
    $opt = AmbilCombo2("prodi", "concat(ProdiID, ' - ', Nama)", "ProdiID", $w["Pilihan".$i], $whr, 'ProdiID');
    $a .= "<tr><td $c>Pilihan $i</td>
      <td $c>:</td><td $c><select name='Pilihan$i'>$opt</select></td></tr>";
  }
  return $a;
}
function PMBSav($lungo='') {
  global $_PMBMaxPilihan;
  $md = $_REQUEST['md']+0;
  $PMBRef = sqling($_REQUEST['PMBRef']);
  $Nama = sqling($_REQUEST['Nama']);
  $PMBFormJualID = $_REQUEST['PMBFormJualID'];
  $PSSBID = $_REQUEST['PSSBID'];
  $StatusAwalID = $_REQUEST['StatusAwalID'];
  $Kelamin = $_REQUEST['Kelamin'];
  $WargaNegara = $_REQUEST['WargaNegara'];
  $ProgramID = (empty($_REQUEST['ProgramID']))? AmbilOneField('program', 'Def', 'Y', 'ProgramID') : $_REQUEST['ProgramID'];
  $Kebangsaan = sqling($_REQUEST['Kebangsaan']);
  $TempatLahir = sqling($_REQUEST['TempatLahir']);
  $TanggalLahir = "$_REQUEST[TanggalLahir_y]-$_REQUEST[TanggalLahir_m]-$_REQUEST[TanggalLahir_d]";
  $Agama = $_REQUEST['Agama'];
  $StatusSipil = $_REQUEST['StatusSipil'];
  $Alamat = sqling($_REQUEST['Alamat']);
  $Kota = sqling($_REQUEST['Kota']);
  $KodePos = sqling($_REQUEST['KodePos']);
  $Propinsi = sqling($_REQUEST['Propinsi']);
  $Negara = sqling($_REQUEST['Negara']);
  $RT = sqling($_REQUEST['RT']);
  $RW = sqling($_REQUEST['RW']);
  $Telepon = sqling($_REQUEST['Telepon']);
  $Handphone = sqling($_REQUEST['Handphone']);
  $Email = sqling($_REQUEST['Email']);

  $AlamatAsal = sqling($_REQUEST['AlamatAsal']);
  $KotaAsal = sqling($_REQUEST['KotaAsal']);
  $KodePosAsal = sqling($_REQUEST['KodePosAsal']);
  $PropinsiAsal = sqling($_REQUEST['PropinsiAsal']);
  $NegaraAsal = sqling($_REQUEST['NegaraAsal']);
  $RTAsal = sqling($_REQUEST['RTAsal']);
  $RWAsal = sqling($_REQUEST['RWAsal']);
  $TeleponAsal = sqling($_REQUEST['TeleponAsal']);

  $NamaAyah = sqling($_REQUEST['NamaAyah']);
  $AgamaAyah = $_REQUEST['AgamaAyah'];
  $PendidikanAyah = $_REQUEST['PendidikanAyah'];
  $PekerjaanAyah = $_REQUEST['PekerjaanAyah'];
  $HidupAyah = $_REQUEST['HidupAyah'];

  $NamaIbu = sqling($_REQUEST['NamaIbu']);
  $AgamaIbu = $_REQUEST['AgamaIbu'];
  $PendidikanIbu = $_REQUEST['PendidikanIbu'];
  $PekerjaanIbu = $_REQUEST['PekerjaanIbu'];
  $HidupIbu = $_REQUEST['HidupIbu'];

  $AlamatOrtu = sqling($_REQUEST['AlamatOrtu']);
  $KotaOrtu = sqling($_REQUEST['KotaOrtu']);
  $KodePosOrtu = sqling($_REQUEST['KodePosOrtu']);
  $PropinsiOrtu = sqling($_REQUEST['PropinsiOrtu']);
  $NegaraOrtu = sqling($_REQUEST['NegaraOrtu']);

  $RTOrtu = sqling($_REQUEST['RTOrtu']);
  $RWOrtu = sqling($_REQUEST['RWOrtu']);
  $TeleponOrtu = sqling($_REQUEST['TeleponOrtu']);
  $HandphoneOrtu = sqling($_REQUEST['HandphoneOrtu']);
  $EmailOrtu = sqling($_REQUEST['EmailOrtu']);

  $AsalSekolah = sqling($_REQUEST['AsalSekolah']);
  $AlamatSekolah = sqling($_REQUEST['AlamatSekolah']);
  $KotaSekolah = sqling($_REQUEST['KotaSekolah']);
  $JurusanSekolah = sqling($_REQUEST['JurusanSekolah']);
  $TahunLulus = $_REQUEST['TahunLulus'];
  $NilaiSekolah = sqling($_REQUEST['NilaiSekolah']);
  
  $AsalPT = $_REQUEST['AsalPT'];
  $ProdiAsalPT = $_REQUEST['ProdiAsalPT'];
  $LulusAsalPT = (empty($_REQUEST['LulusAsalPT']))? 'N' : $_REQUEST['LulusAsalPT'];
  $TglLulusAsalPT = "$_REQUEST[TL_y]-$_REQUEST[TL_m]-$_REQUEST[TL_d]";
  $Harga = $_REQUEST['Harga']+0;
  $JenisSekolahID = AmbilOneField('asalsekolah', "SekolahID", $AsalSekolah, "JenisSekolahID");

  // Status Awal apakah perlu beli formulir?
  //$BeliFormulir = AmbilOneField('statusawal', 'StatusAwalID', $StatusAwalID, 'BeliFormulir');
  // Cek formulir pendaftaran
  //if ($BeliFormulir == 'Y') {
  //  $ada = AmbilOneField('pmbformjual', "PMBFormJualID", $PMBFormJualID, "PMBFormJualID");
  //  if (empty($ada)) $md = 2;
  //}

  // Simpan
  if ($md == 0) {
    // Update PMBFormulir dulu
    if ($BeliFormulir == 'Y') {
      $s0 = "update pmbformjual set OK='Y' where PMBFormJualID='$PMBFormJualID' ";
      $r0 = mysqli_query($s0);
    }
    $PMBID = $_REQUEST['PMBID'];
    $_p = '';
    for ($i=1; $i<=$_PMBMaxPilihan; $i++) {
      $_n = "Pilihan$i";
      $nilai = ($i <= $_REQUEST['JumlahPilihan'])? $_REQUEST[$_n] : '';
      $_p .= ", $_n='$nilai'";
    }
    $s = "update pmb set PMBRef='$PMBRef', Nama='$Nama', PMBFormJualID='$PMBFormJualID', PSSBID='$PSSBID',
      StatusAwalID='$StatusAwalID', Kelamin='$Kelamin', WargaNegara='$WargaNegara', Harga='$Harga',
      ProgramID='$ProgramID',
      Kebangsaan='$Kebangsaan', TempatLahir='$TempatLahir', TanggalLahir='$TanggalLahir',
      Agama='$Agama', StatusSipil='$StatusSipil',
      Alamat='$Alamat', Kota='$Kota',
      KodePos='$KodePos', Propinsi='$Propinsi', Negara='Negara',
      RT='$RT', RW='$RW', Telepon='$Telepon', Handphone='$Handphone', Email='$Email',
      AlamatAsal='$AlamatAsal', KotaAsal='$KotaAsal',
      KodePosAsal='$KodePosAsal', PropinsiAsal='$PropinsiAsal', NegaraAsal='$NegaraAsal',
      RTAsal='$RTAsal', RWAsal='$RWAsal', TeleponAsal='$TeleponAsal',
      NamaAyah='$NamaAyah', AgamaAyah='$AgamaAyah', PendidikanAyah='$PendidikanAyah', PekerjaanAyah='$PekerjaanAyah', HidupAyah='$HidupAyah',
      NamaIbu='$NamaIbu', AgamaIbu='$AgamaIbu', PendidikanIbu='$PendidikanIbu', PekerjaanIbu='$PekerjaanIbu', HidupIbu='$HidupIbu',
      AlamatOrtu='$AlamatOrtu', KotaOrtu='$KotaOrtu',
      KodePosOrtu='$KodePosOrtu', PropinsiOrtu='$PropinsiOrtu', NegaraOrtu='$NegaraOrtu',
      RTOrtu='$RTOrtu', RWOrtu='$RWOrtu',
      TeleponOrtu='$TeleponOrtu', HandphoneOrtu='$HandphoneOrtu', EmailOrtu='$EmailOrtu',
      AsalSekolah='$AsalSekolah', AlamatSekolah='$AlamatSekolah', KotaSekolah='$KotaSekolah',
      JenisSekolahID='$JenisSekolahID',
      JurusanSekolah='$JurusanSekolah', NilaiSekolah='$NilaiSekolah', TahunLulus='$TahunLulus',
      AsalPT='$AsalPT', ProdiAsalPT='$ProdiAsalPT', LulusAsalPT='$LulusAsalPT', TglLulusAsalPT='$TglLulusAsalPT',
      LoginEdit='$_SESSION[_Login]', TanggalEdit=now(), ProdiID='$_REQUEST[Pilihan1]' $_p
      where PMBID='$PMBID' ";
    $r = mysqli_query($koneksi, $s);
  }
  elseif ($md == 1) {
    // Update PMBFormulir dulu
    //if ($BeliFormulir == 'Y') {
      //$s0 = "update pmbformjual set OK='Y' where PMBFormJualID='$PMBFormJualID' ";
      //$r0 = mysqli_query($s0);
    //}
    // Baru simpan data
    $_p = ''; $_pn = '';
    for ($i=1; $i<=$_PMBMaxPilihan; $i++) {
      $_n = "Pilihan$i";
      $_p .= ", $_n";
      $_pn .= ($i <= $_REQUEST['JumlahPilihan'])? ", '$_REQUEST[$_n]'" : ", ''";
    }
    //$BIPOTID = AmbilOneField('bipot', "Def='Y' and ProgramID='$ProgramID' and ProdiID", $_REQUEST['Pilihan1'], 'BIPOTID');
    $PMBID = GetNextPMBID($_REQUEST['Pilihan1']);
    $s = "insert into pmbweb (PMBID, PMBRef, PMBPeriodID, Nama, PMBFormJualID,
      PSSBID, StatusAwalID, Harga, Kelamin,
      KodeID, ProgramID, BIPOTID,
      PMBFormulirID, WargaNegara, Kebangsaan,
      TempatLahir, TanggalLahir,
      Agama, StatusSipil,
      Alamat, Kota,
      KodePos, Propinsi, Negara,
      RT, RW, Telepon, Handphone, Email,
      AlamatAsal, KotaAsal,
      KodePosAsal, PropinsiAsal, NegaraAsal,
      RTAsal, RWAsal, TeleponAsal,
      NamaAyah, AgamaAyah, PendidikanAyah, PekerjaanAyah, HidupAyah,
      NamaIbu, AgamaIbu, PendidikanIbu, PekerjaanIbu, HidupIbu,
      AlamatOrtu, KotaOrtu,
      KodePosOrtu, PropinsiOrtu, NegaraOrtu,
      RTOrtu, RWOrtu, TeleponOrtu, HandphoneOrtu, EmailOrtu,
      AsalSekolah, JenisSekolahID, AlamatSekolah, KotaSekolah,
      NilaiSekolah, JurusanSekolah, TahunLulus, 
      AsalPT, ProdiAsalPT, LulusAsalPT, TglLulusAsalPT,
      LoginBuat, TanggalBuat, ProdiID $_p)

      values ('$PMBID', '$PMBRef', '$_SESSION[pmbaktif]', '$Nama', '$PMBFormJualID',
      '$PSSBID', '$StatusAwalID', '$Harga', '$Kelamin',
      '$_SESSION[KodeID]', '$ProgramID', '$BIPOTID',
      '$_REQUEST[PMBFormulirID]', '$WargaNegara', '$Kebangsaan',
      '$TempatLahir', '$TanggalLahir',
      '$Agama', '$StatusSipil',
      '$Alamat', '$Kota',
      '$KodePos', '$Propinsi', '$Negara',
      '$RT', '$RW', '$Telepon', '$Handphone', '$Email',
      '$AlamatAsal', '$KotaAsal',
      '$KodePosAsal', '$PropinsiAsal', '$NegaraAsal',
      '$RTAsal', '$RWAsal', '$TeleponAsal',
      '$NamaAyah', '$AgamaAyah', '$PendidikanAyah', '$PekerjaanAyah', '$HidupAyah',
      '$NamaIbu', '$AgamaIbu', '$PendidikanIbu', '$PekerjaanIbu', '$HidupIbu',
      '$AlamatOrtu', '$KotaOrtu',
      '$KodePosOrtu', '$PropinsiOrtu', '$NegaraOrtu',
      '$RTOrtu', '$RWOrtu', '$TeleponOrtu', '$HandphoneOrtu', '$Email',
      '$AsalSekolah', '$JenisSekolahID', '$AlamatSekolah', '$KotaSekolah',
      '$NilaiSekolah', '$JurusanSekolah', '$TahunLulus', 
      '$AsalPT', '$ProdiAsalPT', '$LulusAsalPT', '$TglLulusAsalPT',
      '$_SESSION[_Login]', now(), '$_REQUEST[Pilihan1]' $_pn)";
    $r = mysqli_query($koneksi, $s);
    $bayar = AmbilOneField('pmbformulir','PMBFormulirID',$_REQUEST['PMBFormulirID'],'Harga');
    $bayar = number_format($bayar);
    $prod = AmbilOneField("prodi",'ProdiID',$_REQUEST['Pilihan1'],'Nama');
    $lambat = AmbilOneField('pmbperiod','PMBPeriodID',$_SESSION['pmbaktif'],'BayarSelesai');
    $lambat = FormatTanggal($lambat,'-');
    echo Info("Info","<p>Anda telah terdaftar di HANG TUAH PEKANBARU pada Program Studi <b>$prod</b><br>Nomor registrasi Anda adalah : <b>$PMBID</b> <font color=Red>(harap dicatat untuk konfirmasi)</font></p>Silakan melakukan 
           pembayaran registrasi senilai <b>Rp. $bayar</b> paling lambat tanggal : <b>$lambat</b><br><br>
           Informasi lebih lanjut hubungi:<br>Bagian Admisi<br>	Jl.Mustafa Sari No.5 Tangkerang Selatan<br>Pekanbaru<br>Telepon : +62 (761) 33815<br>Fax : +62 (761) 863646F");
  }
  else {
    $strStatusAwalID = AmbilOneField('statusawal', 'StatusAwalID', $StatusAwalID, 'Nama');
    echo Info("Formulir Pendaftaran Tidak Ditemukan",
      "Tidak ditemukan pembelian formulir dengan nomer: <b>$PMBFormJualID</b>.<br />
      Calon Mahasiswa dengan status: $StatusAwalID - <b>$strStatusAwalID</b> harus membeli formulir pendaftaran terlebih dahulu.<br><a href=pmb.web.php>Kembali</a>");
  }
}
?>
