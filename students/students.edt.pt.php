<?php
$sub = (empty($_REQUEST['sub']))? 'frmPT' : $_REQUEST['sub'];
$sub();

// *** Functions ***
function CariPTScript() {
  echo <<<EOF
  <SCRIPT LANGUAGE="JavaScript1.2">
  <!--
  function caript(frm){
    lnk = "cari/cariperguruantinggi.php?PerguruanTinggiID="+frm.AsalPT.value+"&Cari="+frm.NamaPT.value;
    win2 = window.open(lnk, "", "width=600, height=600, scrollbars, status");
    win2.creator = self;
  }
  -->
  </script>
EOF;
}
function frmPT() {
  global $datamhsw, $ndelox, $pref;
  CariPTScript();
  $NamaPT = AmbilOneField('perguruantinggi', 'PerguruanTinggiID', $datamhsw['AsalPT'], "concat(Nama, ', ', Kota)");
  $lulus = ($datamhsw['LulusAsalPT'] == 'Y')? 'checked' : '';
  $TglLulusAsalPT = AmbilComboTgl($datamhsw['TglLulusAsalPT'], 'TL');
  //$optjur = AmbilCombo2('jurusansekolah', "concat(JurusanSekolahID, ' - ', Nama, ' - ', NamaJurusan)", 'JurusanSekolahID', $datamhsw['JurusanSekolah'], '', 'JurusanSekolahID');
  // Edit: Ilham
  // Line: 44, 55
  echo "<p><table id='example' class='table table-sm table-striped' style='width:70%' align='center'>
  <form action='?' name='data' method=POST>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='mhswid' value='$datamhsw[MhswID]' />
  <input type=hidden name='submodul' value='$_SESSION[submodul]' />
  <input type=hidden name='sub' value='PTSav' />
  <input type=hidden name='BypassMenu' value='1' />

  <tr style='background:purple;color:white'><td colspan=2 class=ul><b>ASAL PERGURUAN TINGGI</td></tr>

  <tr>
  <td class=inp>Kode</td>
  <td class=ul><input type=text name='AsalPT' value='$datamhsw[AsalPT]' size=10 maxlength=50></td>
  </tr>
  
  <tr>
  <td class=inp>Perguruan Tinggi</td>
  <td class=ul ><input type=text name='NamaPT' value='$NamaPT' size=50 maxlength=50> <a href='javascript:caript(data)'>Cari</a></td>
  </tr>
  
  <tr>
  <td class=inp style='width:280px'>Jurusan</td>
  <td class=ul><input type=text name='ProdiAsalPT' value='$datamhsw[ProdiAsalPT]' ></select></td>
  </tr>
  
  <tr>
  <td class=inp>Lulus?</td>
  <td class=ul><input type=checkbox name='LulusAsalPT' value='Y' $lulus></td>
  </tr>

  <tr>
  <td class=inp>Lulus tahun</td>
  <td class=ul> $TglLulusAsalPT</td>
  </tr>
  
  <tr><td class=inp>Nilai IPK</td><td class=ul><input type=text name='IPKAsalPT' value='$datamhsw[IPKAsalPT]' size=5 maxlength=5></td></tr>
  <tr>
   <td class=ul colspan=2 align=left>
	<input class='btn btn-success btn-sm' type=submit name='Simpan' value='Simpan'>
    <input class='btn btn-primary btn-sm' type=reset name='Reset' value='Reset'>
	</td>
   </tr>


  </form></table>
  
  </p>";
}
function PTSav() {
	global $koneksi;
  $AsalPT = $_REQUEST['AsalPT'];
  $ProdiAsalPT = $_REQUEST['ProdiAsalPT']; // Edit: Ilham
  $LulusAsalPT = (empty($_REQUEST['LulusAsalPT']))? 'N' : $_REQUEST['LulusAsalPT'];
  $TglLulusAsalPT = "$_REQUEST[TL_y]-$_REQUEST[TL_m]-$_REQUEST[TL_d]";
  echo $TglLulusAsalPT;
  $IPKAsalPT = $_REQUEST['IPKAsalPT'];
  $s = "update mhsw set AsalPT='$AsalPT', ProdiAsalPT='$ProdiAsalPT', LulusAsalPT='$LulusAsalPT', 
    TglLulusAsalPT='$TglLulusAsalPT', IPKAsalPT='$IPKAsalPT'
    where MhswID='$_REQUEST[mhswid]' ";
  $r = mysqli_query($koneksi, $s);
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&submodul=$_SESSION[submodul]", 100);
}
?>
