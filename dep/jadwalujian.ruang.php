<?php
$_jdwlProdi = GainVariabelx('_jdwlProdi');
$_jdwlProg  = GainVariabelx('_jdwlProg');
$_jdwlTahun = GainVariabelx('_jdwlTahun');
$_jdwlHari  = GainVariabelx('_jdwlHari');
$_jdwlUjian = GainVariabelx('_jdwlUjian');

$arrUjian = array(1=>'UTS', 2=>'UAS');
$_SESSION['_jdwlU'] = $arrUjian[$_jdwlUjian];

// *** Main ***
TitleApps("Jadwal $_SESSION[_jdwlU] &minus; $_SESSION[_jdwlTahun]");
$lungo = (empty($_REQUEST['lungo']))? "fnJadwalUjian" : $_REQUEST['lungo'];
$lungo();


// *** Functions ***
function fnJadwalUjian() {
  echo <<<ESD
  <iframe name='frmJDWL' src='$_SESSION[ndelox].jdwl.php' width=500 height=500 frameborder=0 align=center>
  </iframe>
  <iframe name='frmUJIAN' src='$_SESSION[ndelox].ruang.php' width=500 height=500 frameborder=0 align=center>
  </iframe>
  
  <script>
  function RefreshAll() {
    window.location="../index.php?ndelox=$_SESSION[ndelox]";
  }
  </script>
ESD;
}

?>
