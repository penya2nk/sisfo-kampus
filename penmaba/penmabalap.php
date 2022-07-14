<?php
$_PMBPeriodID = GainVariabelx('_PMBPeriodID');
if (empty($_SESSION['_PMBPeriodID'])) {
  $_PMBPeriodID = AmbilOneField('pmbperiod', 'NA', 'N', 'PMBPeriodID');
  $_SESSION['_PMBPeriodID'] = $_PMBPeriodID;
}

// *** Main ***
TitleApps("Laporan-laporan PMB");
$lungo = (empty($_REQUEST['lungo']))? 'DftrLaporan' : $_REQUEST['lungo'];
$lungo();

// *** Functions ***
function DftrLaporan() {
  $maxperiode = 5;
  $_SESSION['maxperiode'] = $maxperiode;
  $arrLap = array(
    'Laporan Penjualan Formulir~jualformulir',
    'Laporan Calon Mahasiswa Per Asal Kota~asalkota',
    'Laporan Calon Mahasiswa Per Asal Sekolah~asalsekolah',
    'Laporan Calon Mahasiswa Berdasar Nilai Sekolah~nilaiasalsekolah',
	'Laporan Calon Mahasiswa Berdasar Program Pendidikan~program',
	'Laporan Data & Fakta PMB~faktapmb',
	'Laporan Ratio Presenter~ratiopresenter',
	'Data Registrasi Sort By Presenter~registrasi',
    "Rekap Jumlah Pendaftar per Periode (max: $maxperiode periode)~rekapperperiode"
	
  );
  
  // Tampilkan
  LauncherScript();
  $i = 0;
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:50%' align='center'>";
  echo "<tr style='background:purple;color:white'>
    <form action='?' method=POST>
    <input type=hidden name='lungo' value='' />
    <td class=ul1 colspan=3>
      Gelombang PMB: <input type=text name='_PMBPeriodID' value='$_SESSION[_PMBPeriodID]' size=10 maxlength=10 />
      <input type=submit name='Submit' value='Submit' />
    </td>
    </form>
    </tr>";
  foreach ($arrLap as $arr) {
    $i++;
    $a = explode('~', $arr);
    $_a = "<a href='#' onClick=\"Prints('".$a[1]."')\">";
    echo "<tr>
      <td class=inp width=10>$i</td>
      <td class=ul1>$_a $a[0]</a></td>
      <td class=ul1 align=center width=10>$_a<i class='fa fa-print'></i></a></td>
      </tr>";
  }
  echo "</table>
  </div>
</div>
</div>
  </p>";
}
function LauncherScript() {
  echo <<<SCR
  <script>
  function Prints(mdl) {
    lnk = "$_SESSION[ndelox]."+mdl+".php?gel=$_SESSION[_PMBPeriodID]";
    //window.location = lnk;
    win2 = window.open(lnk, "", "width=900, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
SCR;
}
?>
