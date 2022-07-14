<?php
error_reporting(0);
session_start();
include_once "../academic_sisfo1.php";

ViewHeaderApps("Cari Ruang");

$ProdiID = GainVariabelx('ProdiID');
$frm = GainVariabelx('frm');
$div = GainVariabelx('div');
$RuangID = GainVariabelx('RuangID');

if (empty($RuangID))
  die(PesanError('Error', 
    "Masukkan terlebih dahulu Kode Ruang sebagai kata kunci pencarian.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    Opsi: <a href='#' onClick=\"javascript:toggleBox('$div', 0)\">Tutup</a>"));


$prd = AmbilOneField('prodi', "KodeID='".KodeID."' and ProdiID", $ProdiID, 'Nama');

TitleApps("Cari Ruang - $prd <sup>($ProdiID)</sup><br /><font size=-1><a href='#' onClick=\"toggleBox('$div', 0)\">(&times; Close &times;)</a></font>");
ViewListRuang();

function ViewListRuang() {
  global $koneksi;
  $s = "select r.RuangID, r.Nama, r.Kapasitas, r.KampusID
    from ruang r
    where r.KodeID = '".KodeID."'
      and r.RuangID like '%$_SESSION[RuangID]%'
      and r.NA = 'N'
      and INSTR(r.ProdiID, '.$_SESSION[ProdiID].') > 0
    order by r.KampusID, r.RuangID";
  $r = mysqli_query($koneksi, $s); $i = 0;
  
  echo "<table class=bsc cellspacing=1 width=100%>";
  echo "<tr style='background:purple;color:white'>
    <th class=ttl>#</th>
    <th class=ttl>Kampus</th>
    <th class=ttl>Kode</th>
    <th class=ttl>Nama Ruang</th>
    <th class=ttl width=60>Kapasitas</th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
    $i++;
    echo <<<SCR
      <tr>
      <td class=inp width=20>$i</td>
      <td class=ul1 width=100>$w[KampusID]</td>
      <td class=ul1 width=100>$w[RuangID]</td>
      <td class=ul1>
        <a href="javascript:$_SESSION[frm].RuangID.value='$w[RuangID]';$_SESSION[frm].Kapasitas.value='$w[Kapasitas]';toggleBox('$_SESSION[div]', 0)">$w[Nama]</a>
      </td>
      <td class=ul1 align=right>$w[Kapasitas]</td>
      </tr>
SCR;
  }
  echo "</table>";
}

?>

</BODY>
</HTML>
