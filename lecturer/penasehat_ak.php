<?php

$DosenID = $_SESSION['_Login'];
$dsn = AmbilFieldx('dosen', "Login='$DosenID' and KodeID", KodeID, "*");

TitleApps("Penasehat Akademik: $dsn[Nama] <sup>$dsn[Gelar]</sup>");
if (empty($dsn))
  die(PesanError("Error",
    "Anda tidak berhak mengakses menu ini.<br />
    Modul ini khusus untuk dosen.
    <hr size=1 color=silver />
    Hubungi Administrator untuk informasi lebih lanjut."));

$lungo = (empty($_REQUEST['lungo']))? 'ListMahasiswa' : $_REQUEST['lungo'];
$lungo($dsn);

function ListMahasiswa($dsn) {
	global $koneksi;
  $s = "select m.MhswID, m.Nama as NamaMhsw, m.TahunID,
      m.ProdiID
    from mhsw m
    where m.KodeID = '".KodeID."'
      and m.PenasehatAkademik = '$dsn[Login]'
    order by m.TahunID, m.MhswID";
  $r = mysqli_query($koneksi, $s); $n = 0;
  
  echo <<<ESD
  <p>
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:70%' align='center'>
  <tr style='background:purple;color:white'><td class=ul colspan=5>
      <input type=button name='btnCetakDaftar' value='Cetak Daftar Mahasiswa'
        onClick="javascript:fnCetakDaftar('$dsn[Login]')" />
      </td></tr>
  <tr><th class=ttl>No</th>
      <th class=ttl>NIM</th>
      <th class=ttl>Nama Mahasiswa</th>
      <th class=ttl>Prodi</th>
      </tr>
ESD;
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    echo "<tr>
      <td class=inp width=30>$n</td>
      <td class=ul1 width=100>$w[MhswID]</td>
      <td class=ul1>$w[NamaMhsw]</td>
      <td calss=ul1 width=100>$w[ProdiID]</td>
      </tr>";
  }
  echo "</table>
  </div>
</div>
</div></p>";
  RandomStringScript();
  echo <<<ESD
    <script>
    <!--
    function fnCetakDaftar(dsn) {
      var _rnd = randomString();
      lnk = "$_SESSION[ndelox].daftar.php?DosenID="+dsn+"&_rnd="+_rnd;
      win2 = window.open(lnk, "", "width=800, height=600, scrollbars, status");
      if (win2.opener == null) childWindow.opener = self;
    }
    //-->
    </script>
ESD;
}
?>
