<?php
session_start();
include_once "../academic_sisfo1.php";

ViewHeaderApps("Cari Dosen");
global $koneksi;
$ProdiID  = GainVariabelx('ProdiID');
$frm      = GainVariabelx('frm');
$div      = GainVariabelx('div');
$Nama     = GainVariabelx('Nama');

if (empty($Nama))
  die(PesanError('Error', 
    "Masukkan terlebih dahulu Nama Dosen sebagai kata kunci pencarian.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    <a href='#' onClick=\"javascript:toggleBox('$div', 0)\">Tutup</a>"));

$prd = AmbilOneField('prodi', "KodeID='".KodeID."' and ProdiID", $ProdiID, 'Nama');
TitleApps("Cari Dosen - $prd <sup>($ProdiID)</sup><br /><font size=-1><a href='#' onClick=\"toggleBox('$div', 0)\">(&times; Close &times;)</a></font>");
TampilkanDaftar();

function TampilkanDaftar() {
global $koneksi;
  $s = "select d.Login, d.Nama, d.Gelar, d.NA
    from dosen d
    where d.KodeID = '".KodeID."'
      and d.Nama like '%$_SESSION[Nama]%'
      and INSTR(d.ProdiID, '$_SESSION[ProdiID]') > 0
    order by d.Nama";
  $r = mysqli_query($koneksi, $s); $i = 0;
  
echo "<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:60%' align='center'>";
    echo "<tr style='background:purple;color:white'>
    <th class=ttl>#</th>
    <th class=ttl style='text-align:center'>Kode</th>
    <th class=ttl>Nama Dosen</th>
    <th class=ttl>Aktif?</th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
    $i++;
    if ($w['NA'] == 'Y') {
      $c = "class=nac";
      $d = "$w[Nama] <sup>$w[Gelar]</sup>";
    }
    else {
      $c = "class=ul";
      $d = "<a href=\"javascript:$_SESSION[frm].DosenID.value='$w[Login]';$_SESSION[frm].Dosen.value='$w[Nama]';toggleBox('$_SESSION[div]', 0)\">
        $w[Nama], </a>
        $w[Gelar]";
    }
    if ($w['NA']=='N'){
      $stat="<i style='color:green' class='fa fa-eye'></i>";
    }else{
      $stat="<i style='color:red' class='fa fa-eye-slash'></i>";
    }
    
    echo <<<SCR
      <tr>
      <td class=inp width=20>$i</td>
      <td $c width=160 align=center>$w[Login]</td>
      <td $c>$d</td>
      <td class=ul width=20 align=center>$stat</td>
      </tr>
SCR;
  }
  echo "</table>
  </div>
</div>
</div>
  ";
}

?>

</BODY>
</HTML>
