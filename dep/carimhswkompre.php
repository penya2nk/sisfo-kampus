<?php
session_start();
include_once "../academic_sisfo1.php";

ViewHeaderApps("Cari Mahasiswa");

$ProdiID = GainVariabelx('ProdiID');
$frm = GainVariabelx('frm');
$div = GainVariabelx('div');
$NamaMhsw = GainVariabelx('NamaMhsw');

if (empty($NamaMhsw))
  die(PesanError('Error', 
    "Masukkan terlebih dahulu Nama Mahasiswa sebagai kata kunci pencarian.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    Opsi: <a href='#' onClick=\"javascript:toggleBox('$div', 0)\">Tutup</a>"));


$prd = AmbilOneField('prodi', "KodeID='".KodeID."' and ProdiID", $ProdiID, 'Nama');

TitleApps("Cari Mahasiswa - $prd <sup>($ProdiID)</sup><br /><font size=-1><a href='#' onClick=\"toggleBox('$div', 0)\">(&times; Close &times;)</a></font>");
TampilkanDaftar();

function TampilkanDaftar() {
	global $koneksi;
  /*$s = "select DISTINCT(m.MhswID), m.Nama as NamaMhsw, m.TahunID, m.NA
    from mhsw m
		left outer join (krs k left outer join mk mk on mk.MKID=k.MKID and mk.Komprehensif='Y') on k.MhswID=m.MhswID and k.KodeID='".KodeID."'
    where m.KodeID = '".KodeID."'
      and m.NA = 'N'
      and m.Nama like '%$_SESSION[NamaMhsw]%'
	  and k.KRSID is not null
	order by m.Nama";*/
  $filter_prodi = (empty($_SESSION['ProdiID']))? "" : "and m.ProdiID='$_SESSION[ProdiID]'";
	
  $s = "select DISTINCT(m.MhswID), m.Nama as NamaMhsw, m.TahunID, m.NA
			from krs k 
				left outer join mk mk on mk.MKID=k.MKID
				left outer join mhsw m on m.MhswID=k.MhswID
			where m.KodeID = '".KodeID."'
				and m.NA = 'N'
				and m.Nama like '%$_SESSION[NamaMhsw]%'
				$filter_prodi
				and mk.Komprehensif='Y'";
  $r = mysqli_query($koneksi, $s); $i = 0;
  
  echo "<table class=bsc cellspacing=1 width=100%>";
  echo "<tr>
    <th class=ttl>#</th>
    <th class=ttl>NIM</th>
    <th class=ttl>Nama Mahasiswa</th>
    <th class=ttl>NA</th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
    $i++;
    if ($w['NA'] == 'Y') {
      $c = "class=nac";
      $d = "$w[Nama] <sup>$w[Gelar]</sup>";
    }
    else {
      $c = "class=ul";
      $d = "<a href=\"javascript:$_SESSION[frm].MhswID.value='$w[MhswID]';$_SESSION[frm].NamaMhsw.value='$w[NamaMhsw]';toggleBox('$_SESSION[div]', 0)\">
        &raquo;
        $w[NamaMhsw]</a>
        <sup>$w[Gelar]</sup>";
    }
    echo <<<SCR
      <tr>
      <td class=inp width=20>$i</td>
      <td $c width=100 align=center>$w[MhswID]</td>
      <td $c>$d</td>
      <td class=ul width=20 align=center><img src='../img/book$w[NA].gif' /></td>
      </tr>
SCR;
  }
  echo "</table>";
}

?>


</BODY>
</HTML>
