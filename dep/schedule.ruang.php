<?php
session_start();
include_once "../academic_sisfo1.php";

ViewHeaderApps("Jadwal Ruang", 1);

$_jdwlRuang = GainVariabelx('_jdwlRuang');
$_jdwlTgl_d = GainVariabelx('_jdwlTgl_d', date('d'));
$_jdwlTgl_m = GainVariabelx('_jdwlTgl_m', date('m'));
$_jdwlTgl_y = GainVariabelx('_jdwlTgl_y', date('Y'));
$_SESSION['_jdwlTgl'] = "$_jdwlTgl_y-$_jdwlTgl_m-$_jdwlTgl_d";

$_jdwlProdi = GainVariabelx('_jdwlProdi');
$_jdwlProg  = GainVariabelx('_jdwlProg');
$_jdwlTahun = GainVariabelx('_jdwlTahun');
$_jdwlHari  = GainVariabelx('_jdwlHari');
$_jdwlUjian = GainVariabelx('_jdwlUjian', 2);

$lungo = (empty($_REQUEST['lungo']))? 'DftrRuang' : $_REQUEST['lungo'];
$lungo();

function DftrRuang() {
  TampilkanHeaderRuang();
  
  $s = "select j.*,
      left(j.Nama, 50) as _Nama,
      left(j.$_SESSION[_jdwlU]JamMulai, 5) as _JM,
      left(j.$_SESSION[_jdwlU]JamSelesai, 5) as _JS,
      d.Nama as NamaDosen, d.Gelar
    from jadwal j
      left outer join dosen d on d.Login = j.DosenID and d.KodeID = '".KodeID."'
    where j.KodeID = '".KodeID."'
      and j.TahunID = '$_SESSION[_jdwlTahun]'
      and j.$_SESSION[_jdwlU]RuangID = '$_SESSION[_jdwlRuang]'
      and j.$_SESSION[_jdwlU]Tanggal = '$_SESSION[_jdwlTgl]'
    order by j.$_SESSION[_jdwlU]JamMulai";
  $r = _query($s); $n = 0;
  
  echo <<<ESD
  <table class=box cellspacing=1 width=100%>
  <tr><th class=ttl width=10>#</th>
      <th class=ttl width=70>Jam</th>
      <th class=ttl>Matakuliah $_SESSION[_jdwlU]</th>
      <th class=ttl>Kelas</th>
      <th class=ttl>Prodi</th>
      <th class=ttl><abbr title="Hapus dari jadwal ujian">Del</abbr></th>
      </tr>
  <script>
  function HapusUjian(jid) {
    if (confirm("Anda akan menghapus matakuliah ini dari jadwal ujian?")) {
      window.location="../$_SESSION[ndelox].ruang.php?lungo=fnHapusUjian&jid="+jid;
    }
  }
  </script>
ESD;

  while ($w = _fetch_array($r)) {
    $n++;
    echo <<<ESD
    <tr><td class=inp width=10>$n</td>
        <td class=ul width=70 nowrap align=center>
          <sup>$w[_JM]</sup>~<sub>$w[_JS]</sub>
          </td>
        <td class=ul>
          <sup>$w[MKKode] &minus; $w[SKS] sks</sup><br />
          $w[_Nama]
          <div align=right>
            <sub>$w[NamaDosen], $w[Gelar]</sub>
          </div>
          </td>
        <td class=ul align=center>
          $w[NamaKelas]
          <br />
          $w[JumlahMhsw] <sub>mhs</sub>
          </td>
        <td class=ul align=center>
          <sup>$w[ProgramID]</sup><br />
          $w[ProdiID]
          </td>
        <td class=ul width=10 align=center>
          <a href='#' onClick="javascript:HapusUjian($w[JadwalID])"><img src='../img/del.png' /></a>
          </td>
        </tr>
ESD;
  }
  echo "</table>";
}
function TampilkanHeaderRuang() {
  $optruang = AmbilCombo2('ruang', "RuangID", 'RuangID', $_SESSION['_jdwlRuang'],
    "KodeID='".KodeID."' and RuangKuliah='Y'", 'RuangID');
  $tglujian = AmbilComboTgl($_SESSION['_jdwlTgl'], '_jdwlTgl');
  echo <<<ESD
  <table class=box cellspacing=1 width=100%>
  <form name='frmRUANG1' action='../$_SESSION[ndelox].ruang.php' method=POST>
  
  <tr><td class=wrn width=2></td>
      <td class=inp width=40>Ruang:</td>
      <td class=ul><select name='_jdwlRuang' onChange="this.form.submit()">$optruang</select></td>
      <td class=inp width=40>Tanggal:</td>
      <td class=ul nowrap>
        $tglujian
        <input type=submit name='btnKirim' value='Kirim' />
        </td>
      </tr>
  </form>
  </table>
ESD;
}
function fnHapusUjian() {
  $jid = $_REQUEST['jid'];
  $s = "update jadwal
    set $_SESSION[_jdwlU]RuangID = ''
    where JadwalID = '$jid' ";
  $r = _query($s);
  //SuksesTersimpan("../$_SESSION[ndelox].ruang.php", 10);
  echo <<<ESD
  <script>
  parent.RefreshAll();
  </script>
ESD;
}
?>
