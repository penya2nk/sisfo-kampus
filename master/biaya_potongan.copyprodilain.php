<?php
session_start();
include_once "../academic_sisfo1.php";

ViewHeaderApps("Edit BIPOT");

$bipotid = GainVariabelx('bipotid');
$ProdiID = GainVariabelx('ProdiID');

TitleApps("Salin Dari Prodi Lain");
$lungo = (empty($_REQUEST['lungo']))? 'Salin' : $_REQUEST['lungo'];
$lungo($bipotid);

function Salin($bipotid) {
  $bpt = AmbilFieldx('bipot', 'BIPOTID', $bipotid, '*');
  $optprd = AmbilCombo2('prodi', "concat(ProdiID, ' - ', Nama)",
    'ProdiID', $_SESSION['ProdiID'], "KodeID='".KodeID."'", 'ProdiID');
  if (!empty($_SESSION['ProdiID'])) {
    $optbpt = AmbilCombo2('bipot', "concat(Tahun, ' - ', Nama)",
      "Tahun Desc", '', "KodeID='".KodeID."' and ProdiID = '$_SESSION[ProdiID]'",
      'BIPOTID');
  }
  else $optbpt = "<option value=''>{ Tidak ada BIPOT }</option>";
  CheckFormScript("_bipotid");
  echo <<<ESD
  <table class=bsc cellspacing=1 width=100%>
  <form name='frm' action='../$_SESSION[ndelox].copyprodilain.php' method=POST
    onSubmit="return CheckForm(this)">
  <input type=hidden name='lungo' value='Simpan' />
  <input type=hidden name='bipotid' value='$bipotid' />
  
  <tr><td class=ul colspan=2 align=center>
      Detail bipot tujuan akan dihapus terlebih dahulu sebelum penyalinan
      detail bipot dari Prodi lain.
      </td></tr>
  <tr><td class=inp>BIPOT Tujuan:</td>
      <td class=ul><sup>$bpt[Tahun]</sup> &raquo; $bpt[Nama]</td>
      </tr>
  <tr><th class=ttl colspan=2>Ambil Dari Prodi:</td></tr>
  <tr><td class=inp>Prodi:</td>
      <td class=ul>
      <select name='ProdiID' onChange="javascript:window.location='../$_SESSION[ndelox].copyprodilain.php?ProdiID='+frm.ProdiID.value">$optprd</select>
      </td></tr>
  <tr><td class=inp>BIPOT Prodi:</td>
      <td class=ul>
      <select name='_bipotid'>$optbpt</select>
      </td></tr>
  <tr><td class=ul1 colspan=2 align=center>
      <input type=submit name='Simpan' value='Simpan' />
      <input type=button name='Batal' value='Batal' onClick='window.close()' />
      </td></tr>
  </form>
  </table>
ESD;
}
function Simpan($bipotid) {
    global $koneksi;
  $ProdiID = sqling($_REQUEST['ProdiID']);
  $CopyID = $_REQUEST['_bipotid']+0;
  if ($bipotid == $CopyID)
    die(PesanError('Error',
      "Anda tidak boleh menyalin BIPOT dari sumber yang sama.<br />
      Pilih BIPOT dari Prodi lain.
      <hr size=1 color=silver />
      Opsi: <input type=button name='Kembali' value='Kembali'
        onClick=\"location='../$_SESSION[ndelox].copyprodilain.php'\" />
        <input type=button name='Batal' value='Batal'
        onClick='window.close()' />"));
  // Kosongkan bipot2 dari tujuan
  $s = "delete from bipot2 where BIPOTID='$bipotid' ";
  $r = mysqli_query($koneksi, $s);
  // Ambil data dari bipot2
  $s1 = "select * from bipot2 where BIPOTID='$CopyID' ";
  $r1 = mysqli_query($koneksi, $s1);
  while ($w1 = mysqli_fetch_array($r1)) {
    $s2 = "insert into bipot2(BIPOTID, BIPOTNamaID, TambahanNama,
      TrxID, Prioritas, Jumlah, KaliSesi, MulaiSesi, PerMataKuliah, PerSKS, PerLab, Remedial, PraktekKerja,
      Otomatis, SaatID, 
      StatusMhswID, StatusPotonganID, StatusAwalID,
      GunakanGradeNilai, GradeNilai,
	  GunakanGradeIPK, GradeIPK,
      GunakanScript, NamaScript, NA,
      LoginBuat, TglBuat)
      values ('$bipotid', '$w1[BIPOTNamaID]', '$w1[TambahanNama]',
      '$w1[TrxID]', '$w1[Prioritas]', '$w1[Jumlah]', '$w1[KaliSesi]', '$w1[MulaiSesi]', '$w1[PerMataKuliah]', '$w1[PerSKS]', '$w1[PerLab]','$w1[Remedial]', '$w1[PraktekKerja]',
      '$w1[Otomatis]', '$w1[SaatID]',
      '$w1[StatusMhswID]', '$w1[StatusPotonganID]', '$w1[StatusAwalID]',
      '$w1[GunakanGradeNilai]', '$w1[GradeNilai]',
	  '$w1[GunakanGradeIPK]', '$w1[GradeIPK]',
      '$w1[GunakanScript]', '$w1[NamaScript]', '$w1[NA]',
      '$_SESSION[_Login]', now())";
    $r2 = mysqli_query($koneksi, $s2);
  }
  TutupScript();
}
function TutupScript() {
echo <<<SCR
<SCRIPT>
  function ttutup() {
    opener.location='../index.php?ndelox=$_SESSION[ndelox]&tok=bipotmhsw';
    self.close();
    return false;
  }
  ttutup();
</SCRIPT>
SCR;
}
?>
