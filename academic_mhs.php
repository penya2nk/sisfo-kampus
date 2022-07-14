<?php
function FindMahasiswa() {
  if (!empty($_SESSION['crmhswid']) && !empty($_SESSION['crmhsw'])) FindMahasiwa1();
}
function FindMahasiwa1() {
	global $koneksi;
  $arrkey = array('NIM'=>'MhswID', 'Nama'=>'Nama');
  $_key = $_SESSION['crmhsw'];
  $s = "select m.MhswID, m.Nama, m.ProdiID,m.TempatLahir,m.TanggalLahir,m.Handphone,m.ProgramID,
    sm.Nama as STT, sm.Nilai, sm.Keluar, sm.Def
    from mhsw m
    left outer join statusmhsw sm on m.StatusMhswID=sm.StatusMhswID
    where m.$arrkey[$_key] like '%$_SESSION[crmhswid]%'
    order by $arrkey[$_key] ";
  $r = mysqli_query($koneksi, $s);
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>
    <tr style='background:purple;color:white'>
	<th class=ttl style='text-align:center'>NIM</th>
    <th class=ttl>Nama Mahasiswa</th>
    <th class=ttl>Tempat & Tgl Lahir</th>
    <th class=ttl>Program</th>
    <th class=ttl>Program Studi</th>
    <th class=ttl>Handphone</th>
    <th class=ttl>Status</th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
    if (strpos(",".$_SESSION['_ProdiID'], ",$w[ProdiID],") === false) {
      //$c = 'class=nac';
	  $c = "class=ul";
     // $lnk = $w['MhswID'];
	  $lnk = "<a href='?ndelox=academic_mhs&mhswid=$w[MhswID]&lungo=EditAkademikMahasiswa'><i class='fa fa-edit'></i> $w[MhswID]</a>";
    }
    else {
      $c = "class=ul";
      $lnk = "<a href='?ndelox=academic_mhs&mhswid=$w[MhswID]&lungo=EditAkademikMahasiswa'><i class='fa fa-edit'></i> $w[MhswID]</a>";
    }
    if ($w['Keluar'] == 'Y') {
      $k = 'class=wrn';
      $lnk = $w['MhswID'];
    }
    else {
      $k = $c;
    }
    echo "<tr>
    <td $k align=center>$lnk</td>
    <td $k>$w[Nama]</td>
    <td $k>$w[TempatLahir], ".date('d-m-Y', strtotime($w['TanggalLahir']))."</td>
    <td $k>$w[ProgramID]</td>
    <td $k>$w[ProdiID]</td>
    <td $k>$w[Handphone]</td>
    <td $k>$w[STT]</td>
    </tr>";
  }
  echo "</table>
  
</div>
</div>
</div></p>";
}
function HeaderMhsw($w) {
  // ambil tahun aktif
  $TahunAktif = AmbilOneField('tahun',
    "KodeID='$_SESSION[KodeID]' and NA='N' and ProgramID='$w[ProgramID]' and ProdiID",
    $w['ProdiID'], "max(TahunID)");
  $prg = AmbilOneField('program', 'ProgramID', $w['ProgramID'], 'Nama');
  $prd = AmbilOneField('prodi', 'ProdiID', $w['ProdiID'], 'Nama');
  // tampilkan data mshw
  echo "<p>
  <div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example1' class='table table-sm table-striped1' style='width:50%' align='center'>
  <tr><td class=inp width=220>NIM</td><td class=ul><b>: $w[MhswID]</td></tr>
  <tr><td class=inp>Nama</td><td class=ul><b>: $w[Nama]</td></tr>
  <tr><td class=inp>Tempat & Tgl Lahir</td><td class=ul><b>: $w[TempatLahir], ".date('d-m-Y', strtotime($w['TanggalLahir']))."</td></tr>
  <tr><td class=inp>Program</td><td class=ul><b>: $w[ProgramID]- $prg</td></tr>
  <tr><td class=inp>Program Studi</td><td class=ul><b>: $w[ProdiID]- $prd</td></tr>
  <tr><td class=inp>Tahun Akademik Aktif</td><td class=ul><b>: $TahunAktif</td></tr>
  </table>
  </div>
</div>
</div></p>";
  return $TahunAktif;
}
function EditAkademikMahasiswa() {
	global $koneksi;
  $w = AmbilFieldx('mhsw', 'MhswID', $_SESSION['mhswid'], '*');
  // Tampilkan Data Mhsw
  $TahunAktif = HeaderMhsw($w);
  // Tampilkan tambah sesi
  $NextSesi = AmbilOneField("khs", "MhswID", $w['MhswID'], "max(Sesi)")+1;
  $DefStatus = AmbilOneField('statusmhsw', 'Def', 'Y', 'StatusMhswID');
  $optstt = AmbilCombo2('statusmhsw', "concat(StatusMhswID, ' - ', Nama)", 'StatusMhswID', $DefStatus, '', 'StatusMhswID');
  $_modTambahTahunAkd = "<p>
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id='example' class='table table-sm table-striped' style='width:100%' align='center'>
  <tr><td class=ul colspan=7><b>Tambahkan Sesi</b></td></tr>
  
  <form action='?' method=GET>
  <input type=hidden name='ndelox' value='academic_mhs'>
  <input type=hidden name='lungo' value='MhswAkdAdd'>
  <input type=hidden name='mhswid' value='$w[MhswID]'>
  <tr><td class=inp1>Tahun Akd. :</td>
    <td class=ul><input type=text name='TahunID' value='$TahunAktif' size=8 maxlength=10></td>
    <td class=inp1>Sesi :</td>
    <td class=ul><input type=text name='Sesi' value='$NextSesi' size=5 maxlength=5></td>
    <td class=inp1>Status :</td>
    <td class=ul><select name='StatusMhswID'>$optstt</select></td>
    <td class=ul><input type=submit name='Tambahkan' value='Tambahkan'></td>
  </tr>
  </form></table>
  </div>
</div>
</div></p>";
  DaftarSesiMhsw($w, $TahunAktif);
}
function DaftarSesiMhsw($m, $TahunAktif) {
	global $koneksi;
  $s = "select k.*, sm.Nama as STT, sm.Nilai,
    format(k.IP, 2) as IP,
    format(k.Biaya, 0) as BIA,
    format(k.Potongan, 0) as POT,
    format(k.Bayar, 0) as BYR,
    format(k.Tarik, 0) as TRK
    from khs k
    left outer join statusmhsw sm on k.StatusMhswID=sm.StatusMhswID
    where k.MhswID='$m[MhswID]'
    order by k.TahunID";
  $r = mysqli_query($koneksi, $s);
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>
  <tr style='background:purple;color:white'><th class=ttl colspan=2>Sesi</th>
  <th class=ttl>Tahun</th>
  <th class=ttl>Status</th>
  <th class=ttl>Ubah Status</th>
  <th class=ttl style='text-align:center'>Max SKS</th>
  <th class=ttl title='Cetak Kartu Studi Semester'>KSS</th>
  <th class=ttl style='text-align:left'>KRS</th>
  <th class=ttl style='text-align:right'>IP</th>
  <th class=ttl style='text-align:right'>Biaya</th>
  <th class=ttl style='text-align:right'>Potongan</th>
  <th class=ttl style='text-align:right'>Bayar</th>
  <th class=ttl style='text-align:right'>Tarikan</th>
  <th class=ttl style='text-align:center'>Hitung Ulang</th>
  </tr>";
  while ($w = mysqli_fetch_array($r)) {
    $c = ($w['Nilai'] <= 0)? 'class=nac' : 'class=ul';
    $_where = (strpos(".1.20.50.40.", '.'.$_SESSION['_LevelID'].'.') === false)? "Nilai=1" : '';
    $optstt = AmbilCombo2('statusmhsw', "concat(StatusMhswID, ' - ', Nama)", 'StatusMhswID',
      $w['StatusMhswID'], $_where, 'StatusMhswID');
    if ($TahunAktif == $w['TahunID']) {
      $edit = "<form action='?' method=POST>
      <input type=hidden name='ndelox' value='academic_mhs'>
      <input type=hidden name='lungo' value='UbahStatus'>
      <input type=hidden name='crmhswid' value='$m[MhswID]'>
      <input type=hidden name='khsid' value='$w[KHSID]'>
      <td $c>$w[STT]</td>
      <td $c><select name='StatusMhswID'>$optstt</select></td>
      <td $c><input type=text name='MaxSKS' value='$w[MaxSKS]' size=2 maxlength=2>
      <input style='margin-top:-5px' class='btn btn-primary btn-xs' type=submit name='Simpan' value='Simpan'></td>
      </form>";
      $krs = "<a href='?ndelox=dep/krstudi&mhswid=$w[MhswID]&tahun=$w[TahunID]'><i class='fa fa-plus-circle'></i></a>";
      $ctk1 = "<a href='?ndelox=kss&lungo=cekkss&tahun=$w[TahunID]&mhswid=$w[MhswID]&khsid=$w[KHSID]'>
      <i class='fa fa-print'></i></a>";
    }
    else {
      $stt = AmbilOneField('statusmhsw', 'StatusMhswID', $w['StatusMhswID'], 'Nama');
      $edit = "<td $c>$stt</td><td $c align=center>&times;</td><td $c>$w[MaxSKS]</td>";
      $krs = "&nbsp;";
      $ctk1 = "&times;";
    }
    echo "<tr><td class=ul><a href='?ndelox=academic_mhs&lungo=MhswSesiDel&mhswid=$w[MhswID]&khsid=$w[KHSID]'><i class='fa fa-trash'></i></td>
    <td class=ul><b>$w[Sesi]</b></td>
    <td $c>$w[TahunID]</td>
    $edit
    <td class=ul align=center>$ctk1</td>
    <td $c align=center>$krs</td>
    <td $c align=right>$w[IP]</td>
    <td $c align=right>$w[BIA]</td>
    <td $c align=right>$w[POT]</td>
    <td $c align=right>$w[BYR]</td>
    <td $c align=right>$w[TRK]</td>
    <td class=ul align=center><a href='?ndelox=academic_mhs&lungo=EditAkademikMahasiswa&mhswid=$w[MhswID]&khsid=$w[KHSID]&slnt=mhswkeu.lib&slntx=HitungBiayaBayarMhsw'><img src='img/Y.png'></a></td>
    </tr>";
  }
  echo "</table>
  </div>
</div>
</div></p>";
}
function UbahStatus() {
	global $koneksi;
  $khsid = $_REQUEST['khsid'];
  $StatusMhswID = $_REQUEST['StatusMhswID'];
  $MaxSKS = $_REQUEST['MaxSKS']+0;
  if (!empty($StatusMhswID)) {
    $s = "update khs set StatusMhswID='$StatusMhswID', MaxSKS=$MaxSKS where KHSID='$khsid' ";
    $r = mysqli_query($koneksi, $s);
  }
  EditAkademikMahasiswa();
}
function MhswAkdAdd() {
	global $koneksi;
  $ada = AmbilFieldx('khs', "TahunID='$_REQUEST[TahunID]' and MhswID", $_REQUEST['mhswid'], '*');
  if (empty($ada)) {
    // Cek apakah statusnya kini menjadi "T" (Tunggu ujian)?
    if ($_REQUEST['StatusMhswID'] == 'T') {
      $tunggu = AmbilFieldx('khs', "StatusMhswID='T' and MhswID", $_REQUEST['mhswid'], '*');
      if (empty($tunggu)) {
        $w = AmbilFieldx('mhsw', 'MhswID', $_REQUEST['mhswid'], '*');
        $s = "insert into khs (TahunID, KodeID, ProgramID, ProdiID, 
          MhswID, Sesi, StatusMhswID,
          TanggalBuat, LoginBuat)
          values ('$_REQUEST[TahunID]', '$_SESSION[KodeID]', '$w[ProgramID]', '$w[ProdiID]',
          '$w[MhswID]', '$_REQUEST[Sesi]', '$_REQUEST[StatusMhswID]',
          now(), '$_SESSION[_LoginID]')";
        $r = mysqli_query($koneksi, $s);
        echo "<script>window.location='?ndelox=academic_mhs&lungo=EditAkademikMahasiswa';</script>";
      }
      else echo PesanError("Gagal Disimpan",
        "Mahasiswa telah berstatus <b>Tunggu Ujian (T)</b> pada tahun $_REQUEST[TahunID].<br />
        Sistem hanya memperbolehkan 1x status Tunggu Ujian.
        <hr size=1 color=silver>
        Pilihan: <a href='?ndelox=academic_mhs&lungo=EditAkademikMahasiswa'>Kembali</a>");
    }
  }
  else {
    echo PesanError("Gagal Disimpan",
      "Mahasiswa telah mengikuti tahun ajaran <b>$_REQUEST[TahunID]</b>.
      <hr size=1>
      Pilihan: <a href='?ndelox=academic_mhs&lungo=EditAkademikMahasiswa'>Kembali</a>");
  }
  
}
function MhswSesiDel() {
  $w = AmbilFieldx('mhsw', 'MhswID', $_REQUEST['mhswid'], '*');
  $khs = AmbilFieldx('khs', 'KHSID', $_REQUEST['khsid'], '*');
  if ($khs['Biaya'] + $khs['Bayar'] + $khs['Potongan'] + $khs['Tarik'] > 0) {
    echo PesanError("Sesi Tidak Dapat Dihapus",
    "Anda tidak dapat menghapus Sesi <b>$khs[TahunID]</b> dari mahasiswa <b>$w[Nama]</b> ($w[MhswID]) karena telah ada
    transaksi keuangan.
    <hr size=1>
    Pilihan: <a href='?ndelox=academic_mhs&mhswid=$w[MhswID]&lungo=EditAkademikMahasiswa'>Batal</a>");
  }
  else echo Info("KONFIRMASI",
    "
	<div class='card'>
	<div class='card-header'>
	<div class='table-responsive'>
	Apakah Anda yakin akan menghapus Sesi mahasiswa ini?<br /><br />
    <table id='example' class='table table-sm table-stripedx' style='width:100%' align='center'>
    <tr><td class=ul>NIM </td><td class=ul><b>: $w[MhswID]</b></td></tr>
    <tr><td class=ul>Nama Mahasiswa</td><td class=ul><b>: $w[Nama]</b></td></tr>
    <tr><td class=ul>Sesi </td><td class=ul><b>: $khs[Sesi]</b></td></tr>
    <tr><td class=ul>Tahun Akademik </td><td class=ul><b>: $khs[TahunID]</b></td></tr>
    </table>
	<a href='?ndelox=academic_mhs&lungo=MhswSesiDel1&mhswid=$w[MhswID]&khsid=$_REQUEST[khsid]'>Delete</a> |
    <a href='?ndelox=academic_mhs&mhswid=$w[MhswID]&lungo=EditAkademikMahasiswa'>Batal</a>
	    </div>
	</div>
	</div>");

   
}
function MhswSesiDel1() {
	global $koneksi;
  $s = "delete from khs where KHSID='$_REQUEST[khsid]' ";
  $r = mysqli_query($koneksi, $s);
  EditAkademikMahasiswa();
}

$crmhsw = GainVariabelx('crmhsw', 'NPM');
$crmhswid = GainVariabelx('crmhswid');
$lungo = (empty($_REQUEST['lungo']))? 'FindMahasiswa' : $_REQUEST['lungo'];
$mhswid = GainVariabelx('mhswid');

TitleApps("DATA AKADEMIK MAHASISWA");
TampilkanPencarianMhsw('academic_mhs', 'FindMahasiswa');
$lungo();
?>
