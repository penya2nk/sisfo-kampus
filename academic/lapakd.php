<?php
error_reporting(0);
$TahunID = GainVariabelx('TahunID');
$ProdiID = GainVariabelx('ProdiID');

TitleApps("Laporan Akademik");
TampilkanHeaderLaporanAkademik();
$lungo = (empty($_REQUEST['lungo']))? 'DftrLapAkd' : $_REQUEST['lungo'];
$lungo();

function TampilkanHeaderLaporanAkademik() {
  $optprd = AmbilPenggunaProdi($_SESSION['_Login'], $_SESSION['ProdiID']);
  echo <<<ESD
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:50%' align='center'>
  <form action='?' method=POST>
  <tr ><td class=wrn width=1></td>
      <td class=inp width=100>Tahun Akd:</td>
      <td class=ul width=120 nowrap>
        <input type=text name='TahunID' value='$_SESSION[TahunID]' size=5 maxlength=6 />
        <input class='btn btn-success btn-sm' type=submit name='btnSet' value='Set' />
        </td>
      <td class=inp width=60>Prodi:</td>
      <td class=ul><select name='ProdiID' onChange='this.form.submit()'>$optprd</select></td>
  
  </form>
  </table>
  </div>
</div>
</div>
ESD;
}
function DftrLapAkd() {
  $arrLap = array(
    'Rekapitulasi Jumlah Mahasiswa per Angkatan~statusmhsw0',
    '&raquo; Laporan Mahasiswa Aktif~statusmhsw~&sta=A',
    '&raquo; Laporan Mahasiswa Cuti~statusmhsw~&sta=C',
    '&raquo; Laporan Mahasiswa Drop Out~statusmhsw~&sta=D',
	'&raquo; Laporan Mahasiswa Keluar~statusmhsw~&sta=K',
	'&raquo; Laporan Mahasiswa Pasif~statusmhsw~&sta=P',
	'Daftar Mahasiswa Yang Sudah KRS~krsmhsw',
    'Daftar Mahasiswa Yang Belum KRS~krsmhsw0',
	'&raquo; Daftar Mahasiswa Berdasarkan Agama~agamamhsw', 
	'&raquo; Daftar Mahasiswa Berdasarkan Asal Sekolah~asalsekmhsw',
	'&raquo; Daftar Mahasiswa Berdasarkan Dosen PA~dosenpamhsw',
	'&raquo; Daftar Mahasiswa Berdasarkan Prodi~prodimhsw',
	'&raquo; Daftar Mahasiswa Berdasarkan Angkatan~angkmhsw',
	'&raquo; Laporan Statistik Kelas~statistikkelas',
	'&raquo; Laporan Nilai Semester dan Distribusi Matakuliah~statistikkelas2'
  );
  $i = 0;
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:50%' align='center'>";
  foreach ($arrLap as $arr) {
    $i++;
    $a = explode('~', $arr);
    $_a = "<a href='#$i' onClick=\"Prints('".$a[1]."', '".$a[2]."')\">";
    echo "<tr >
      <td class=inp width=10><a name='$i'></a>$i</td>
      <td class=ul1>$_a $a[0]</a></td>
      <td class=ul1 align=center width=10>$_a<i class='fa fa-print'></i></a></td>
      </tr>";
  }
  echo "</table></p>";
  RandomStringScript();
  echo <<<SCR
  <script>
  function Prints(mdl, param) {
    var rnd = randomString();
    lnk = "$_SESSION[ndelox]."+mdl+".php?TahunID=$_SESSION[TahunID]&ProdiID=$_SESSION[ProdiID]"+param+"&_rnd="+rnd;
    //window.location = lnk;
    win2 = window.open(lnk, "", "width=800, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
SCR;
}

?>
