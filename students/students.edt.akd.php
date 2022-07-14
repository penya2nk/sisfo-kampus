<?php
$sub = (empty($_REQUEST['sub']))? 'frmAkademik' : $_REQUEST['sub'];
$sub();

function frmAkademik() {
  global $datamhsw;
  $optdsn = AmbilCombo2('dosen', "concat(Nama, ', ', Gelar)", 'Nama', $datamhsw['PenasehatAkademik'], 
    "Homebase='$datamhsw[ProdiID]'", 'Login');
  $optsta = AmbilCombo2('statusawal', "concat(StatusAwalID, ' - ', Nama)", 'Nama', $datamhsw['StatusAwalID'], '', 'StatusAwalID');
  $optsm = AmbilCombo2('statusmhsw', "concat(StatusMhswID, ' - ', Nama)", 'Nama', $datamhsw['StatusMhswID'], '', 'StatusMhswID');
  $optprg = AmbilCombo2('program', "concat(ProgramID, ' - ', Nama)", 'Nama', $datamhsw['ProgramID'], "KodeID='".KodeID."'", 'ProgramID');
  $syarat = TampilkanPMBSyarat($datamhsw);
  $arrLengkap = array('Y'=>'Lengkap', 'N'=>'Tidak Lengkap');
  $strLengkap = $arrLengkap[$datamhsw['SyaratLengkap']];
  echo "<p><table id='example' class='table table-sm table-striped' style='width:70%' align='center'>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='submodul' value='$_SESSION[submodul]' />
  <input type=hidden name='sub' value='AkademikSav' />
  <input type=hidden name='mhswid' value='$datamhsw[MhswID]' />
  <input type=hidden name='BypassMenu' value='1' />

  <tr style='background:purple;color:white'><td colspan=2 class=ul><b>DATA AKADEMIK</td></tr>
  <tr><td class=inp style='width:280px'>Program</td>
      <td class=ul><b>$datamhsw[PRG]</b> </td></tr>
  <tr><td class=inp>Program Studi</td>
      <td class=ul><b>$datamhsw[PRD]</b></td></tr>
  <tr><td class=inp>Tahun Masuk</td>
      <td class=ul>$datamhsw[TahunID]</td></tr>
  <tr><td class=inp>Status Awal</td>
      <td class=ul>$datamhsw[StatusAwalID]</td></tr>
  <tr><td class=inp>Status Mahasiswa</td>
      <td class=ul>$datamhsw[StatusMhswID]</td></tr>
  <tr><td class=inp>Penasehat Akademik</td>
      <td class=ul>$datamhsw[PenasehatAkademik]</td></tr>
  <tr><td class=inp>Batas Studi</td>
      <td class=ul><b>$datamhsw[BatasStudi]</b></td></tr>

  <tr style='background:purple;color:white'><td colspan=2 class=ul><b>KELENGKAPAN SYARAT</b></td></tr>
  <tr><td class=inp rowspan=2>Syarat-syarat</td><td class=ul><img src='img/$datamhsw[SyaratLengkap].png'> $strLengkap</td></tr>
  <tr><td class=ol>$syarat</td></tr>
  <tr>
   <td class=ul colspan=2 align=left>
	<input class='btn btn-success btn-sm' type=submit name='Simpan' value='Simpan'>
    <input class='btn btn-primary btn-sm' type=reset name='Reset' value='Reset'>
	</td>
   </tr>
  <tr></tr>
  </form>
  </table></p>";
}
function AkademikSav() {
	global $koneksi;
  // Cek Kelengkapan
  $_syarat = array();
  $_syarat = $_REQUEST['PMBSyaratID'];
  $syarat = (empty($_syarat))? '' : '.' . implode('.', $_syarat) .'.';
  // Cek Kelengkapan
  $mhsw = AmbilFieldx('mhsw', 'MhswID', $_REQUEST['mhswid'], 'StatusAwalID, ProdiID, Syarat, SyaratLengkap');
  $s = "select PMBSyaratID, Nama
    from pmbsyarat
    where NA='N' and KodeID='$_SESSION[KodeID]'
      and INSTR(StatusAwalID, '.$mhsw[StatusAwalID].') >0
      and INSTR(ProdiID, '.$mhsw[ProdiID].') >0
    order by PMBSyaratID";
  $r = mysqli_query($koneksi, $s);
  $lkp = True;
  if (!empty($_syarat)) {
    while ($w = mysqli_fetch_array($r)) {
      if (array_search($w['PMBSyaratID'], $_syarat) === false)
      $lkp = false;
    }
  } else $lkp = false;
  $Lengkap = ($lkp == true)? 'Y' : 'N';
  // Simpan
  $TahunID = sqling($_REQUEST['TahunID']);
  $s = "update mhsw 
    set PenasehatAkademik='$_REQUEST[PenasehatAkademik]', ProgramID='$_REQUEST[ProgramID]',
        TahunID = '$TahunID',
        StatusAwalID='$_REQUEST[StatusAwalID]', StatusMhswID='$_REQUEST[StatusMhswID]',
        Syarat='$syarat', SyaratLengkap='$Lengkap', BatasStudi='$_REQUEST[BatasStudi]'
    where MhswID='$_REQUEST[mhswid]' ";
  $r = mysqli_query($koneksi, $s);
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&submodul=$_SESSION[submodul]", 10);
}
?>
