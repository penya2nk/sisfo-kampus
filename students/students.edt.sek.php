<?php
$sub = (empty($_REQUEST['sub']))? 'frmSekolah' : $_REQUEST['sub'];
$sub();

function CariSekolahScript() {
  echo <<<EOF
  <SCRIPT LANGUAGE="JavaScript1.2">
  <!--
  function carisekolah(frm){
    lnk = "cari/carisekolah.php?SekolahID="+frm.AsalSekolah.value+"&Cari="+frm.NamaSekolah.value;
    win2 = window.open(lnk, "", "width=600, height=600, scrollbars, status");
    win2.creator = self;
  }
  -->
  </script>
EOF;
}
function frmSekolah() {
  global $datamhsw;
  CariSekolahScript();
  $NamaSekolah = AmbilOneField('asalsekolah', 'SekolahID', $datamhsw['AsalSekolah'], "concat(Nama, ', ', Kota)");
  $optjur = AmbilCombo2('jurusansekolah', "concat(JurusanSekolahID, ' - ', Nama, ' - ', NamaJurusan)", 'JurusanSekolahID', $datamhsw['JurusanSekolah'], '', 'JurusanSekolahID');
  echo "<p><table id='example' class='table table-sm table-striped' style='width:70%' align='center'>
  <form action='?' name='data' method=POST>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='mhswid' value='$datamhsw[MhswID]' />
  <input type=hidden name='submodul' value='$_SESSION[submodul]' />
  <input type=hidden name='sub' value='SekolahSav' />
  <input type=hidden name='BypassMenu' value='1' />

  <tr style='background:purple;color:white'>
  <td colspan=2 class=ul><b>SEKOLAH MENENGAH ATAS</td>
  </tr>

  <tr>
  <td class=inp style='width:280px'>Kode Sekolah</td>
  <td class=ul ><input type=text name='AsalSekolah' value='$datamhsw[AsalSekolah]' size=10 maxlength=50></td>
  </tr>

  <tr>
  <td class=inp>Nama Sekolah</td>
  <td class=ul ><input type=text name='NamaSekolah' value='$NamaSekolah' size=40 maxlength=50> <a href='javascript:carisekolah(data)'>Cari</a></td>
  </tr>
  
  <tr><td class=inp>Jenis Sekolah</td><td class=ul><b>$datamhsw[JenisSekolahID]</b></td></tr>
  <tr><td class=inp>Jurusan</td><td class=ul><select name='JurusanSekolah'>$optjur</select></td></tr>
  <tr><td class=inp>Tahun Lulus</td><td class=ul><input type=text name='TahunLulus' value='$datamhsw[TahunLulus]' size=10 maxlength=5></td></tr>
  <tr><td class=inp>Nilai Sekolah</td><td class=ul><input type=text name='NilaiSekolah' value='$datamhsw[NilaiSekolah]' size=5 maxlength=5></td></tr>
  <tr>
   <td class=ul colspan=2 align=left>
	<input class='btn btn-success btn-sm' type=submit name='Simpan' value='Simpan'>
    <input class='btn btn-primary btn-sm' type=reset name='Reset' value='Reset'>
	</td>
   </tr>
  </form></table></p>";
}
function SekolahSav() {
	global $koneksi;
  $AsalSekolah = $_REQUEST['AsalSekolah'];
  $JurusanSekolah = $_REQUEST['JurusanSekolah'];
  $TahunLulus = $_REQUEST['TahunLulus'];
  $NilaiSekolah = $_REQUEST['NilaiSekolah'];
  $JenisSekolahID = AmbilOneField('asalsekolah', 'SekolahID', $AsalSekolah, 'JenisSekolahID');

  $s = "update mhsw set AsalSekolah='$AsalSekolah', JenisSekolahID='$JenisSekolahID',
    JurusanSekolah='$JurusanSekolah',
    TahunLulus='$TahunLulus', NilaiSekolah='$NilaiSekolah'
    where MhswID='$_REQUEST[mhswid]' ";
  $r = mysqli_query($koneksi, $s);
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&submodul=$_SESSION[submodul]", 100);
}

?>
