<?php
error_reporting(0);
session_start();
include_once "../academic_sisfo1.php";

ViewHeaderApps("ABSENSI MAHASISWA", 1);

$pid = $_REQUEST['pid'];

TitleApps("ABSENSI MAHASISWA");
$lungo = (empty($_REQUEST['lungo']))? 'ListMahasiswa' : $_REQUEST['lungo'];
$lungo($pid);

function ListMahasiswa($pid) {
  $p = AmbilFieldx("presensi p
    left outer join jadwal j on p.JadwalID = j.JadwalID
    left outer join dosen d on d.Login = j.DosenID and d.KodeID='".KodeID."'
    left outer join hari h on h.HariID = date_format(p.Tanggal, '%w')
	left outer join jenisjadwal jj on jj.JenisJadwalID = j.JenisJadwalID",
    "p.PresensiID", $pid,
    "p.*, j.MKKode, j.Nama, h.Nama as _HR,
    concat(d.Nama, ' <sup>', d.Gelar, '</sup>') as DSN,
    date_format(p.Tanggal, '%d-%m-%Y') as _Tanggal,
    left(p.JamMulai, 5) as _JM, left(p.JamSelesai, 5) as _JS,
	jj.Nama as _NamaJenisJadwal, jj.Tambahan");
  ViewHeaderx($p);
  CekKRSMhsw($p);
  ViewAbsensiMahasiswa($p);
}
function ViewHeaderx($p) {
  $TagTambahan = ($p['Tambahan'] == 'Y')? "<b>( $p[_NamaJenisJadwal] )</b>" : "";
  echo "<table class=box cellspacing=1 width=100%>
  <tr style='background:purple;color:white'>
      <td class=ul>Matakuliah</td>
      <td class=ul>$p[Nama] $TagTambahan</td>
      <td class=ul>Dosen</td>
      <td class=ul>$p[DSN]</td>
      </tr>
  <tr style='background:purple;color:white'>
      <td class=ul>Pertemuan</td>
      <td class=ul>#$p[Pertemuan] - $p[_HR] $p[_Tanggal]
      </td><td class=ul>Jam</td>
      <td class=ul>$p[_JM] - $p[_JS]</td>
      </tr>
  </table>";
}
function CekKRSMhsw($p) {
  global $koneksi;
  $def = AmbilFieldx('jenispresensi', 'Def', 'Y', '*');
  $s = "select KRSID, MhswID, JadwalID
    from krs
    where JadwalID = '$p[JadwalID]'
    order by MhswID";
  $r = mysqli_query($koneksi, $s);
  while ($w = mysqli_fetch_array($r)) {
    $ada = AmbilFieldx('presensimhsw', "PresensiID = '$p[PresensiID]' and KRSID", $w['KRSID'], '*');
    if (empty($ada)) {
      $sp = "insert into presensimhsw
        (JadwalID, KRSID, PresensiID, 
        MhswID, JenisPresensiID, Nilai, NA)
        values
        ($p[JadwalID], $w[KRSID], $p[PresensiID],
        '$w[MhswID]', '$def[JenisPresensiID]', '$def[Nilai]', 'N')";
      $rp = mysqli_query($koneksi, $sp);
      // Hitung KRS
      $jml = AmbilOneField('presensimhsw', 'KRSID', $w['KRSID'], "sum(Nilai)")+0;
      $sk = "update krs
        set _Presensi = $jml
        where KRSID = $w[KRSID]";
      $rk = mysqli_query($koneksi, $sk);
    }
  }
}
function ViewAbsensiMahasiswa($p) {
  global $koneksi;
  $s = "select pm.*, mhsw.Nama
    from presensimhsw pm
      left outer join mhsw on mhsw.MhswID = pm.MhswID and mhsw.KodeID = '".KodeID."'
    where pm.PresensiID = '$p[PresensiID]'
    order by pm.MhswID";
  $r = mysqli_query($koneksi, $s);
  $def = AmbilFieldx('jenispresensi', 'Def', 'Y', '*');
  $opt0 = AmbilCombo2('jenispresensi', "Nama", 'JenisPresensiID', $def['JenisPresensiID'], '', 'JenisPresensiID');
  
  echo "<table class=box cellspacing=1 width=100%>";
  echo "<script>
  function ttutup() {
    opener.location='../index.php?ndelox=$_SESSION[ndelox]&lungo=Edit&JadwalID=$p[JadwalID]';
    self.close();
    return false;
  }
  </script>";
  echo "<tr>
    <form action='../$_SESSION[ndelox].mhswedit.php' method=POST>
    <input type=hidden name='lungo' value='SimpanSemua' />
    <input type=hidden name='pid' value='$p[PresensiID]' />
    
    <td class=ul colspan=5>Set semua ke:
    <select name='Stt'>$opt0</select>
    <input type=submit name='SetStt' value='Set Status' />
    <input type=button name='Refresh' value='Refresh' 
      onClick=\"location='../$_SESSION[ndelox].mhswedit.php?pid=$p[PresensiID]'\" />
    <input type=button name='Tutup' value='Tutup' onClick=\"ttutup()\" />
    </td>
    
    </form>
    </tr>";
  $n = 0;
  $arr = GetArrPre();
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    $optpre = GetOptPre($arr, $w['JenisPresensiID']);
    echo "
      <tr><td class=inp width=10>$n</td>
          <td class=ul1 width=94><b>$w[MhswID]</b></td>
          <td class=ul1 width=260>$w[Nama]</td>
          <td class=ul><select id='PresensiMhsw_$w[PresensiMhswID]'
            onChange='javascript:SetPresensiMhsw($w[PresensiMhswID])'>$optpre</select></td>
      </tr>";
  }
  echo <<<SCR
  </table>
  <script>
  function SetPresensiMhsw(id) {
    var status = document.getElementById("PresensiMhsw_"+id).value;
    lnk = "../$_SESSION[ndelox].mhswedit.save.php?id="+id+"&st="+status;
    win2 = window.open(lnk, "", "width=0, height=0, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
SCR;
}
function SimpanSemua($pid) {
  global $koneksi;
  $Stt = sqling($_REQUEST['Stt']);
  $Nilai = AmbilOneField('jenispresensi', 'JenisPresensiID', $Stt, 'Nilai');
  $s = "select *
    from presensimhsw
    where PresensiID = '$pid' ";
  $r = mysqli_query($koneksi, $s);
  while ($w = mysqli_fetch_array($r)) {
    // update
    $s0 = "update presensimhsw set JenisPresensiID = '$Stt', Nilai = '$Nilai'
      where PresensiMhswID = $w[PresensiMhswID]";
    $r0 = mysqli_query($koneksi, $s0);
    // Hitung & update ke KRS
    $jml = AmbilOneField('presensimhsw', 'KRSID', $w['KRSID'], "sum(Nilai)")+0;
    // Update KRS
    $s1 = "update krs
      set _Presensi = $jml
      where KRSID = $w[KRSID]";
    $r1 = mysqli_query($koneksi, $s1);
  }
  SuksesTersimpan("../$_SESSION[ndelox].mhswedit.php?pid=$pid", 1);
}
function GetOptPre($arr, $id) {
  $opt = '';
  foreach($arr as $a) {
    $_a = explode('~', $a);
    $sel = ($id == $_a[0])? 'selected' : '';
    $opt .= "<option value='$_a[0]' $sel>$_a[1]</option>";
  }
  return $opt;
}
function GetArrPre() {
  global $koneksi;
  $s = "select * from jenispresensi where NA='N' order by JenisPresensiID";
  $r = mysqli_query($koneksi, $s);
  $arr = array();
  $arr[] = '';
  while ($w = mysqli_fetch_array($r)) {
    $arr[] = "$w[JenisPresensiID]~$w[Nama]";
  }
  return $arr;
}
?>
