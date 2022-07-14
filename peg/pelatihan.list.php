<?php
function ListPelatihan() {
  global $koneksi;
  $opt = AmbilCombo2('t_simpegpegawai', "concat(Noreg, ' - ', Nama)", 'Noreg', $_SESSION['noreg'], '', 'Noreg');
  $colspan = 9;
  echo "
  <div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example1' class='table table-sm table-striped'>
    <tr><form action='?' method=POST>
    <input type=hidden name='ndelox' value='peg/pelatihan.list'>
    <td colspan=$colspan class=ul>Nama Pegawai : <select name='noreg' onChange='this.form.submit()'>$opt</select></td>
    </form></tr>";
  echo "<tr><td class=ul colspan=$colspan><a href='?ndelox=peg/pelatihan.list&lungo=PelatihanEdt&md=1'>Tambah Pelatihan</a> |
    <a href='peg/pelatihan.list.cetakxls.php?tahun=2022' target=_BLANK>Cetak</a></td></tr>";
  echo "<tr><th class=ttl>#</th>
    <th class=ttl width=140px>Noreg</th>
    <th class=ttl width=200px>Nama</th>
    <th class=ttl width=350px>Nama Pelatihan</th>
    <th class=ttl width=150px>Pelaksana</th>
    <th class=ttl>Tanggal</th>
    <th class=ttl width=150px>Narasumber</th>
    <th class=ttl>Jenis Kegiatan</th>
    <th class=ttl>Download</th>
    </tr>";
  $s = "select
    t_simpegpelatihan.*,
    dosen.Nama, dosen.Gelar
    from t_simpegpelatihan,dosen
    where t_simpegpelatihan.Noreg=dosen.Noreg
    and t_simpegpelatihan.Noreg='$_SESSION[noreg]'
    order by t_simpegpelatihan.TanggalMulai Desc";
  $r = mysqli_query($koneksi, $s); $n = 0;
  while ($w = mysqli_fetch_array($r)) {
    $Namax 		= strtolower($w['Nama']);
	  $Nama	    = ucwords($Namax);
    $n++;
    $c = ($w['NA'] == 'Y')? 'class=nac' : 'class=ul';
    $ket = str_replace(chr(13), ', ', $w['Keterangan']);
    echo "<tr><td $c>$n</td>
      <td $c><a href='?ndelox=peg/pelatihan.list&lungo=PelatihanEdt&md=0&idpel=$w[IDPel]'><i class='fas fa-edit'></i>
      $w[Noreg]</a></td>
      <td $c>$Nama, $w[Gelar]</td>
      <td $c>$w[Judul]</td>
      <td $c>$w[Pelaksana]</td>
      <td $c>".FormatTanggal($w['TanggalMulai'])."&nbsp;</td>
      <td $c>$w[NaraSumber]</td>
      <td $c>$w[JenisKegiatan]</td>
      <td $c>&nbsp;</td>
      </tr>";
  }
  echo "</table>
  </div>
  </div>
  </div>
  ";
}
function PelatihanEdt() {
  global $datapelatihan;
  $md = $_REQUEST['md'] +0;
  if ($md == 0) {
    $w = AmbilFieldx('t_simpegpelatihan', 'IDPel', $_REQUEST['idpel'], '*');
    $jdl = "Edit Pelatihan";
    $strid = "<input type=hidden name='IDPel' value='$w[IDPel]'><b>$w[IDPel]</b>";
  }
  else {
    $w = array();
    $w['IDPel'] = '';
    $w['Judul'] = '';
    $w['NaraSumber'] = '';
    $w['Pelaksana'] = '';
    $w['Tempat'] = '';
    $w['MaksudTujuan'] = '';
    $w['JenisKegiatan'] = '';
    $w['Keterangan'] = '';
    $w['Noreg'] = $_SESSION['noreg'];

    $jdl = "Tambah Pelatihan";
    $strid = "Auto";
  }
  $_ruangkuliah = ($w['RuangKuliah'] == 'Y')? 'checked' : '';
  $_na = ($w['NA'] == 'Y')? 'checked' : '';
  $_usm = ($w['UntukUSM'] == 'Y')? 'checked' : '';
  $_optkaryawan = AmbilCombo2('t_simpegpegawai', "concat(Noreg, ' - ', Nama)", 'Noreg', $w['Noreg'], '', 'Noreg');
  $_optkegiatan = AmbilCombo2('t_simpegjeniskegiatan', "concat(IDKegiatan, ' - ', JenisKegiatan)", 'JenisKegiatan', $w['JenisKegiatan'], '', 'JenisKegiatan');
  $TanggalMulai = AmbilComboTgl($w['TanggalMulai'], 'TanggalMulai');
  $optprodi= AmbilCekBox("prodi", "ProdiID",
    "concat(ProdiID, ' - ', Nama) as NM", "NM", $w['ProdiID'], '.');
  CheckFormScript("RuangID,Nama,KampusID,Lantai");

  // Tampilkan
  $c1 = 'class=inp1'; $c2 = 'class=ul';
  $snm = session_name(); $sid = session_id();
  echo "
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table class=box cellspacing=1 cellpadding=4 align=center width=100%>
  <form action='?' method=POST onSubmit=\"return CheckForm(this)\">
  <input type=hidden name='ndelox' value='peg/pelatihan.list'>
  <input type=hidden name='lungo' value='PelatihanSimpan'>
  <input type=hidden name='md' value='$md'>
  <tr><th class=ttl colspan=2>$jdl</th></tr>
  <tr><td $c1 width='280px'>ID Pelatihan</td><td $c2>$strid</td></tr>
  <tr><td $c1 width='280px'>Karyawan / Dosen</td><td $c2><select name='Noreg'>$_optkaryawan</select></td></tr>
  <tr><td $c1>Nama Pelatihan</td><td $c2><textarea name='Judul' cols=100 rows=4>$w[Judul]</textarea></td></tr>
  <tr><td $c1>Maksud dan Tujuan</td><td $c2><input type=text name='MaksudTujuan' value='$w[MaksudTujuan]' size=100 maxlength=50></td></tr>
  <tr><td $c1>Narasumber</td><td $c2><input type=text name='NaraSumber' value='$w[NaraSumber]' size=100 maxlength=50></td></tr>
  <tr><td $c1>Pelaksana</td><td $c2><input type=text name='Pelaksana' value='$w[Pelaksana]' size=100 maxlength=50></td></tr>
  <tr><td $c1>Tempat Pelaksanaan</td><td $c2><input type=text name='Tempat' value='$w[Tempat]' size=100 maxlength=50></td></tr>
  <tr><td $c1>Jenis Kegiatan</td><td $c2><select name='JenisKegiatan'>$_optkegiatan</select></td></tr>
  
  <tr><td $c1>Tanggal Mulai</td><td $c2><input type=text id='datepicker' name='TanggalMulai' value='$w[TanggalMulai]' size=100 maxlength=50></td></tr>

  <tr><td $c1>Tanggal Selesai</td><td $c2><input type=text id='datepicker2' name='TanggalSelesai' value='$w[TanggalSelesai]' size=100 maxlength=50></td></tr>
  <tr><td $c1>Tahun Akademik</td><td $c2><input type=text name='TahunID' value='$w[TahunID]' size=100 maxlength=50></td></tr>
  <tr><td $c1>Keterangan</td><td $c2><textarea name='Keterangan' cols=100 rows=4>$w[Keterangan]</textarea></td></tr>
  <tr><td $c1>NA (tidak aktif)?</td><td $c2><input type=checkbox name='NA' value='Y' $_na></td></tr>
  <tr><td colspan=2><input type=submit name='Simpan' value='Simpan'>
    <input type=reset name='Reset' value='Reset'>
    <input type=button name='Batal' value='Batal' onClick=\"location='?ndelox=peg/pelatihan.list&$snm=$sid'\"></td></tr>
  </form></table>";
  /*<tr><td $c1>Untuk Prodi</td><td $c2>$optprodi</td></tr>
  <tr><td $c1>Untuk kuliah?</td><td $c2><input type=checkbox name='RuangKuliah' value='Y' $_ruangkuliah></td></tr>
  <tr><td $c1>Untuk Ujian Saringan Masuk (USM)?</td><td $c2><input type=checkbox name='UntukUSM' value='Y' $_usm></td></tr>*/
}

function PelatihanSimpan() {
  global $koneksi;
  $md = $_REQUEST['md']+0;
  $IDPel = $_REQUEST['IDPel'];
  $Noreg = $_REQUEST['Noreg'];
  $Judul = sqling($_REQUEST['Judul']);
  $MaksudTujuan = sqling($_REQUEST['MaksudTujuan']);
  $NaraSumber = sqling($_REQUEST['NaraSumber']);
  $JenisKegiatan = $_REQUEST['JenisKegiatan'];
  $TahunID = $_REQUEST['TahunID'];
  $Pelaksana = sqling($_REQUEST['Pelaksana']);
  $Tempat = sqling($_REQUEST['Tempat']);
  $TanggalMulai = $_REQUEST['TanggalMulai'];
  
  $TanggalSelesai = $_REQUEST['TanggalSelesai'];
  $Keterangan = sqling($_REQUEST['Keterangan']);

  // $RuangKuliah = (empty($_REQUEST['RuangKuliah']))? 'N' : $_REQUEST['RuangKuliah'];
  // $Kapasitas = $_REQUEST['Kapasitas']+0;
  // $KapasitasUjian = $_REQUEST['KapasitasUjian']+0;
  // $KolomUjian = $_REQUEST['KolomUjian']+0;
  // $UntukUSM = (empty($_REQUEST['UntukUSM']))? 'N' : $_REQUEST['UntukUSM'];
  // $Keterangan = sqling($_REQUEST['Keterangan']);
  // $NA = (empty($_REQUEST['NA']))? 'N' : $_REQUEST['NA'];
  // $prodi = $_REQUEST['ProdiID'];
  // $ProdiID = (empty($prodi))? '' : '.'.implode('.', $prodi).'.';
  if ($md == 0) {
    $s = "update t_simpegpelatihan 
          set Noreg='$Noreg', 
          Judul='$Judul',
          MaksudTujuan='$MaksudTujuan', 
          NaraSumber='$NaraSumber',
          JenisKegiatan='$JenisKegiatan', 
          TahunID='$TahunID', 
          Pelaksana='$Pelaksana', 
          Tempat='$Tempat',
          TanggalMulai='$TanggalMulai', 
          TanggalSelesai='$TanggalSelesai',
          Keterangan='$Keterangan' 
          WHERE IDPel='$IDPel' ";
    $r = mysqli_query($koneksi, $s);
  }
  else {
    $ada = AmbilFieldx('t_simpegpelatihan', 'IDPel', $_REQUEST['IDPel'], '*');
    if (empty($ada)) {
      $s = "insert into t_simpegpelatihan(Noreg, Judul, MaksudTujuan, NaraSumber, JenisKegiatan, TahunID,
        Pelaksana, Tempat, TanggalMulai, TanggalSelesai, Keterangan)
        values('$Noreg', '$Judul', '$MaksudTujuan', '$NaraSumber', '$JenisKegiatan', '$TahunID',
        '$Pelaksana', '$Tempat', '$TanggalMulai', '$TanggalSelesai', '$Keterangan')";
      $r = mysqli_query($koneksi, $s);
    }
    else echo ErrorMsg('Terjadi Kesalahan',
      "Kode ruang telah digunakan: <b>$ada[IDPel] - $ada[Judul]</b> di gedung: $ada[Noreg].<br>
      Gunakan kode ruang lain.");
  }
  ListPelatihan();
}

$kampusid = GainVariabelx('noreg'); //huruf kecil bro
$lungo = (empty($_REQUEST['lungo']))? 'ListPelatihan' : $_REQUEST['lungo'];

TitleApps("Daftar Pelatihan");
$lungo();
?>
</div>
</div>
</div>

