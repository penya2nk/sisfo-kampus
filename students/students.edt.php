<?php
error_reporting(0);
function ViewHeadMahasiswa($w) {
  $foto = FileFotoMhsw($w['MhswID'], $w['Foto']);
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:50%' align='center'>
  <tr><td class=inp style='width:280px'>NIM</td>
     <td class=ul><b>: $w[MhswID]</b></td>
     <td class=box rowspan=6 style='padding: 2pt' align=center width=124>";
       if (trim($w['Foto'])==''){
          echo "<img class='img-thumbnail' style='width:155px' src='pto_stud/no-image.jpg'>";
        }else{
          echo "<img class='img-thumbnail' style='width:155px' src='pto_stud/$w[Foto]'>";
        }
        
	 echo"</td>
     </tr>

  <tr><td class=inp>Nama</td>
      <td class=ul><b>: $w[Nama]</b></td></tr>
  <tr><td class=inp>Program</td>
      <td class=ul>: $w[ProgramID] - <b>$w[PRG]</b></td></tr>
  <tr><td class=inp>Program Studi</td>
      <td class=ul>: $w[ProdiID] - <b>$w[PRD]</b></td></tr>
  <tr><td class=inp>AKSI</td>
      <td class=ul>
      
      <input class='btn btn-success btn-sm' type=button name='CetakMhsw' value='Cetak Data' onClick=\"CetakData('$w[MhswID]')\" />
	  </td></tr>
  </table>

  <script>
	function CetakData(id)
	{	lnk = \"$_SESSION[ndelox].cetak.php?MhswID=\"+id;
		  win2 = window.open(lnk, \"\", \"width=600, height=400, scrollbars, status\");
		  if (win2.opener == null) childWindow.opener = self;
	}
  </script>";
}

$mhswid = GainVariabelx('mhswid');
$mhswpg = GainVariabelx('mhswpg', 'pri');
$submodul = GainVariabelx('submodul', 'pri');

TitleApps("Data Mahasiswa");
$mhswid = $_SESSION['_Login'];
$w = AmbilFieldx('mhsw', "Login='$MhswID' and KodeID", KodeID,"*");
if (!empty($_SESSION['_Login'])) {
  $datamhsw = AmbilFieldx("mhsw m
    left outer join prodi prd on m.ProdiID=prd.ProdiID
    left outer join program prg on m.ProgramID=prg.ProgramID",
    'm.MhswID', $mhswid,
    "m.*, prd.Nama as PRD, prg.Nama as PRG");
  if (!empty($datamhsw)) {
    ViewHeadMahasiswa($datamhsw);
    ViewDetailMhs($_SESSION['ndelox'], $arrmhswpg, $submodul);
    include_once($_SESSION['ndelox'].'.'.$submodul.'.php');
    //TampilkanSubMenu($ndelox, $arrmhswpg, $pref, $token);
  }
  else echo PesanError("Kesalahan",
    "Terjadi kesalahan. Mahasiswa dengan NIM: <b>$mhswid</b> tidak ditemukan.");
}

function ViewDetailMhs($ndelox, $arr, $act) {
  echo "
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:55%' align='center'>
  <tr>
    <td $c align=center><a class='btn btn-success btn-sm' href='?ndelox=$ndelox&submodul=pri'>PERSONAL</a></td>
    <td $c align=center><a class='btn btn-primary btn-sm' href='?ndelox=$ndelox&submodul=almt'>ALAMAT</a></td>
    <td $c align=center><a class='btn btn-warning btn-sm' href='?ndelox=$ndelox&submodul=akd'>AKADEMIK</a></td>
    <td $c align=center><a class='btn btn-danger btn-sm' href='?ndelox=$ndelox&submodul=ortu'>ORANG TUA</a></td>
    <td $c align=center><a class='btn btn-info btn-sm' href='?ndelox=$ndelox&submodul=sek'>ASAL SEKOLAH</a></td>
    <td $c align=center><a class='btn btn-secondary btn-sm' href='?ndelox=$ndelox&submodul=pt'>ASAL PT</a></td>
    <td $c align=center><a class='btn btn-primary btn-sm' href='?ndelox=$ndelox&submodul=bank'>BANK</a></td>

  </table>
  </div>
</div>";
}
?>
