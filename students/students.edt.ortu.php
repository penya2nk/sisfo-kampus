<?php
$sub = (empty($_REQUEST['sub']))? 'frmOrtu' : $_REQUEST['sub'];
$sub();

function frmOrtu() {
  global $datamhsw;
  // Data ayah
  $AgamaAyah = AmbilCombo2("agama", "concat(Agama, ' - ', Nama)", 'Agama', $datamhsw['AgamaAyah'], '', 'Agama');
  $PendidikanAyah = AmbilCombo2('pendidikanortu', "concat(Pendidikan, ' - ', Nama)", 'Pendidikan', $datamhsw['PendidikanAyah'], '', 'Pendidikan');
  $PekerjaanAyah = AmbilCombo2('pekerjaanortu', "concat(Pekerjaan, ' - ', Nama)", 'Pekerjaan', $datamhsw['PekerjaanAyah'], '', 'Pekerjaan');
  $HidupAyah = AmbilCombo2('hidup', "concat(Hidup, ' - ', Nama)", 'Hidup', $datamhsw['HidupAyah'], '', 'Hidup');

  // Data ayah
  $AgamaIbu = AmbilCombo2("agama", "concat(Agama, ' - ', Nama)", 'Agama', $datamhsw['AgamaIbu'], '', 'Agama');
  $PendidikanIbu = AmbilCombo2('pendidikanortu', "concat(Pendidikan, ' - ', Nama)", 'Pendidikan', $datamhsw['PendidikanIbu'], '', 'Pendidikan');
  $PekerjaanIbu = AmbilCombo2('pekerjaanortu', "concat(Pekerjaan, ' - ', Nama)", 'Pekerjaan', $datamhsw['PekerjaanIbu'], '', 'Pekerjaan');
  $HidupIbu = AmbilCombo2('hidup', "concat(Hidup, ' - ', Nama)", 'Hidup', $datamhsw['HidupIbu'], '', 'Hidup');

  echo "<p><table id='example' class='table table-sm table-striped' style='width:70%' align='center'>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='submodul' value='$_SESSION[submodul]' />
  <input type=hidden name='sub' value='OrtuSav' />
  <input type=hidden name='mhswid' value='$datamhsw[MhswID]'>
  <input type=hidden name='BypassMenu' value='1' />

  <tr style='background:purple;color:white'><td colspan=2 class=ul><b>Data Ayah</b></td></tr>
  <tr><td class=inp style='width:280px'>Nama</td><td class=ul><input type=text name='NamaAyah' value='$datamhsw[NamaAyah]' size=40 maxlength=50></td></tr>
  <tr><td class=inp>Agama</td><td class=ul><select name='AgamaAyah'>$AgamaAyah</select></td></tr>
  <tr><td class=inp>Pendidikan</td><td class=ul><select name='PendidikanAyah'>$PendidikanAyah</select></td></tr>
  <tr><td class=inp>Pekerjaan</td><td class=ul><select name='PekerjaanAyah'>$PekerjaanAyah</select></td></tr>
  <tr><td class=inp>Hidup</td><td class=ul><select name='HidupAyah'>$HidupAyah</select></td></tr>

  <tr style='background:purple;color:white'><td colspan=2 class=ul><b>Data Ibu</b></td></tr>
  <tr><td class=inp>Nama</td><td class=ul><input type=text name='NamaIbu' value='$datamhsw[NamaIbu]' size=40 maxlength=50></td></tr>
  <tr><td class=inp>Agama</td><td class=ul><select name='AgamaIbu'>$AgamaIbu</select></td></tr>
  <tr><td class=inp>Pendidikan</td><td class=ul><select name='PendidikanIbu'>$PendidikanIbu</select></td></tr>
  <tr><td class=inp>Pekerjaan</td><td class=ul><select name='PekerjaanIbu'>$PekerjaanIbu</select></td></tr>
  <tr><td class=inp>Hidup</td><td class=ul><select name='HidupIbu'>$HidupIbu</select></td></tr>

  <tr style='background:purple;color:white'><td colspan=2 class=ul><b>ALAMAT ORANG TUA</b></td></tr>
  <tr><td class=inp>Alamat</td><td class=ul><input type=text name='AlamatOrtu' value='$datamhsw[AlamatOrtu]' size=50 maxlength=200></td></tr>
  <tr><td class=inp>RT</td><td class=ul><input type=text name='RTOrtu' value='$datamhsw[RTOrtu]' size=10 maxlength=5>
    RW <input type=text name='RWOrtu' value='$datamhsw[RWOrtu]' size=10 maxlength=5></td></tr>
  <tr><td class=inp>Kota</td><td class=ul><input type=text name='KotaOrtu' value='$datamhsw[KotaOrtu]' size=20 maxlength=50>
    Kode Pos <input type=text name='KodePosOrtu' value='$datamhsw[KodePosOrtu]' size=20 maxlength=50></td></tr>
  <tr><td class=inp>Propinsi</td><td class=ul><input type=text name='PropinsiOrtu' value='$datamhsw[PropinsiOrtu]' size=30 maxlength=50></td></tr>
  <tr><td class=inp>Negara</td><td class=ul><input type=text name='NegaraOrtu' value='$datamhsw[NegaraOrtu]' size=30 maxlength=50></td></tr>

  <tr style='background:purple;color:white'><td colspan=2 class=ul><b>KONTAK</b></td></tr>
  <tr><td class=inp>Telepon</td><td class=ul><input type=text name='TeleponOrtu' value='$datamhsw[TeleponOrtu]' size=30 maxlength=50></td></tr>
  <tr><td class=inp>Handphone</td><td class=ul><input type=text name='HandphoneOrtu' value='$datamhsw[HandphoneOrtu]' size=30 maxlength=50></td></tr>
  <tr><td class=inp>Email</td><td class=ul><input type=text name='EmailOrtu' value='$datamhsw[EmailOrtu]' size=30 maxlength=50></td></tr>

  <tr>
   <td class=ul colspan=2 align=left>
	<input class='btn btn-success btn-sm' type=submit name='Simpan' value='Simpan'>
    <input class='btn btn-primary btn-sm' type=reset name='Reset' value='Reset'>
	</td>
   </tr>
  </table></p>";
}
function OrtuSav() {
	global $koneksi;
  $NamaAyah = sqling($_REQUEST['NamaAyah']);
  $NamaIbu = sqling($_REQUEST['NamaIbu']);
  $AlamatOrtu = sqling($_REQUEST['AlamatOrtu']);
  $RTOrtu = sqling($_REQUEST['RTOrtu']);
  $RWOrtu = sqling($_REQUEST['RWOrtu']);
  $KotaOrtu = sqling($_REQUEST['KotaOrtu']);
  $PropinsiOrtu = sqling($_REQUEST['PropinsiOrtu']);
  $NegaraOrtu = sqling($_REQUEST['NegaraOrtu']);
  $TeleponOrtu = sqling($_REQUEST['TeleponOrtu']);
  $HandphoneOrtu = sqling($_REQUEST['HandphoneOrtu']);
  $EmailOrtu = sqling($_REQUEST['EmailOrtu']);
  // Simpan
  $s = "update mhsw set NamaAyah='$NamaAyah', AgamaAyah='$_REQUEST[AgamaAyah]',
    PendidikanAyah='$_REQUEST[PendidikanAyah]',
    PekerjaanAyah='$_REQUEST[PekerjaanAyah]',
    HidupAyah='$_REQUEST[HidupAyah]',
    NamaIbu='$_REQUEST[NamaIbu]',
    AgamaIbu='$_REQUEST[AgamaIbu]',
    PendidikanIbu='$_REQUEST[PendidikanIbu]',
    PekerjaanIbu='$_REQUEST[PekerjaanIbu]',
    HidupIbu='$_REQUEST[HidupIbu]',
    AlamatOrtu='$_REQUEST[AlamatOrtu]',
    RTOrtu='$_REQUEST[RTOrtu]',
    RWOrtu='$_REQUEST[RWOrtu]',
    KotaOrtu='$_REQUEST[KotaOrtu]',
    KodePosOrtu='$_REQUEST[KodePosOrtu]',
    PropinsiOrtu='$_REQUEST[PropinsiOrtu]',
    NegaraOrtu='$_REQUEST[NegaraOrtu]',
    TeleponOrtu='$_REQUEST[TeleponOrtu]',
    HandphoneOrtu='$_REQUEST[HandphoneOrtu]',
    EmailOrtu='$_REQUEST[EmailOrtu]'
    where MhswID='$_REQUEST[mhswid]' ";
  $r = mysqli_query($koneksi, $s);
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&submodul=$_SESSION[submodul]", 10);
}

?>
