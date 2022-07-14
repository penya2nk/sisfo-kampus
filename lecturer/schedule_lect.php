<?php
error_reporting(0);
$TahunID  = GainVariabelx('TahunID');
$DosenID  = $_SESSION['_Login'];
$dsn      = AmbilFieldx('dosen', "Login='$DosenID' and KodeID", KodeID, "*");

TitleApps("JADWAL MENGAJAR $dsn[Nama], $dsn[Gelar]");
if (empty($dsn))
  die(PesanError("Error",
    "Anda tidak berhak mengakses menu ini.<br />
    Modul ini khusus untuk dosen.
    <hr size=1 color=silver />
    Hubungi Sysadmin untuk informasi lebih lanjut."));

$lungo = (empty($_REQUEST['lungo']))? 'JadwalDosen' : $_REQUEST['lungo'];
$lungo($TahunID, $dsn);

function JadwalDosen($TahunID, $dsn) {
	global $koneksi;
  ViewComboThn($TahunID, $dsn['Login']);
  
  $s = "select j.*,
      left(j.JamMulai, 5) as _JM, left(j.JamSelesai, 5) as _JS,
      p.Nama as NamaProdi, k.Nama AS namaKelas
    from jadwal j
	  LEFT OUTER JOIN kelas k ON k.KelasID = j.NamaKelas
      left outer join prodi p on p.ProdiID = j.ProdiID and p.KodeID = '".KodeID."'
    where j.TahunID = '$TahunID'
      and j.KodeID = '".KodeID."'
      and j.DosenID = '$dsn[Login]'
    order by j.HariID, j.JamMulai, j.JamSelesai";
  $r = mysqli_query($koneksi, $s);
  
  $n = 0; $ttl = 0; $hr = -25;
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>";
  while ($w = mysqli_fetch_array($r)) {
    if ($hr != $w['HariID']) {
      $hr = $w['HariID'];
      $Hari = AmbilOneField('hari', 'HariID', $hr, 'Nama');
      ViewHeaderLect($Hari);
    }
    $n++;
    $ttl += $w['SKS'];
    echo <<<ESD
    <tr>
        <td class=inp>$n</td>
        <td class=ul>$w[_JM] - $w[_JS]</td>
        <td class=ul>$w[MKKode]</td>
        <td class=ul>$w[Nama]</td>
        <td class=ul align=right>$w[SKS]</td>
        <td class=ul>$w[namaKelas]</td>
        <td class=ul>$w[RuangID]</td>
        <td class=ul>$w[ProgramID]</td>
        <td class=ul>$w[ProdiID]</td>
ESD;
  }
  RandomStringScript();
  echo <<<ESD
    <tr><td class=ul1 colspan=4 align=right>Total SKS:</td>
        <td class=ul1 align=right><font size=+1>$ttl</font></td>
        <td class=ul1 colspan=4></td>
        </tr>
    </table>
    
    <script>
    <!--
    function PrintSchedule(thn, dsn) {
      var _rnd = randomString();
      lnk = "$_SESSION[ndelox].print.php?TahunID="+thn+"&DosenID="+dsn+"&_rnd="+_rnd;
      win2 = window.open(lnk, "", "width=800, height=600, scrollbars, status");
      if (win2.opener == null) childWindow.opener = self;
    }
    //-->
    </script>
ESD;
}
function ViewHeaderLect($Hari) {
  echo <<<ESD
  <tr><td class=ul1 colspan=9><font size=+1>$Hari</font></td></tr>
  <tr style='background:purple;color:white'><th class=ttl width=20>No.</th>
      <th class=ttl width=100>Jam Kuliah</th>
      <th class=ttl width=80>Kode</th>
      <th class=ttl width=300>Matakuliah</th>
      <th class=ttl width=20>SKS</th>
      <th class=ttl width=60>Kelas</th>
      <th class=ttl width=60>Ruang</th>
      <th class=ttl width=250>Program</th>
       <th class=ttl width=80>Prodi</th>
ESD;
}
function ViewComboThn($TahunID, $DosenID) {
  $btnCetak = ($TahunID == '')? '' : "<input  class='btn btn-success btn-sm' type=button name='Cetak' value='Cetak Jadwal' onClick=\"PrintSchedule('$TahunID', '$DosenID')\" />";
  echo <<<ESD
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:50%' align='center'>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='lungo' value='' />
  <tr><td class=wrn width=1></td>
      <td class=inp>Tahun Akademik:</td>
      <td class=ul>
        <input style='height:30px' type=text name='TahunID' value='$_SESSION[TahunID]' size=6 maxlength=5 />
        <input class='btn btn-primary btn-sm' type=submit name='ST' value='Set Tahun' />
        $btnCetak
      </td></tr>
  </table>
  </div>
</div>
</div>
ESD;
}
?>