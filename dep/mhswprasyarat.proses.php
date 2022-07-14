<?php
// error_reporting(0);
session_start();
include_once "../academic_sisfo1.php";

ViewHeaderApps("Aktivitas Dosen");

$TahunID = GainVariabelx('TahunID');
$ProdiID = GainVariabelx('ProdiID');
$MKID = GainVariabelx('MKID');
$_praMKID = GainVariabelx('_praMKID');
$_praMKKode = GainVariabelx('_praMKKode');
$_praSKS = GainVariabelx('_praSKS');
$_praKur = GainVariabelx('_praKur');
$_praSesi = GainVariabelx('_praSesi');
$_praSKSMin = GainVariabelx('_praSKSMin');
$_praIPKMin = GainVariabelx('_praIPKMin');
$_praPrasyarat = GainVariabelx('_praPrasyarat');
$_praFile = GainVariabelx('_praFile');

$lungo = (empty($_REQUEST['lungo']))? 'Proses' : $_REQUEST['lungo'];
$lungo();

function Proses() {
  global $koneksi;
  $_max = 100;
  $_praPrc = GainVariabelx('_praPrc');
  $_praCnt = GainVariabelx('_praCnt');
  $_dari = $_praPrc * $_max;
  
  $s = "select m.MhswID, m.Nama
    from mhsw m
    where m.KodeID = '".KodeID."'
      and m.ProdiID = '$_SESSION[ProdiID]'
      and m.StatusMhswID = 'A'
    order by m.MhswID
    limit $_dari, $_max";
  //die($s);
  $r = mysqli_query($koneksi, $s);
  
  $jml = mysqli_num_rows($r);
  if ($jml > 0) {
    while ($w = mysqli_fetch_array($r)) {
      $_SESSION['_praCnt']++;
      // Proses satu per satu
      $MhswID = $w['MhswID'];
      $Nama = $w['Nama'];
      $oke = true; $psn = '';
      // Apakah ada SKS Minimalnya?
      $oke = CheckSKSMin($MhswID, $psn);
      
      // Apakah ada IP Minimalnya?
      if ($oke) {
        $oke = CheckIPMin($MhswID, $psn);
      }

      // Apakah ada MK Prasyaratnya?
      if ($oke) {
        $oke = CheckPrasyarat($MhswID, $psn);
      }
      echo <<<ESD
      <script>
      self.parent.Progresnya($_SESSION[_praCnt], '$MhswID', '$Nama', '$psn');
      </script>
ESD;
      // Jika memenuhi syarat
      if ($oke == true) {
        $f = fopen("../".$_SESSION['_praFile'].".txt", 'a');
        fwrite($f, "$MhswID|$Nama|Oke\r\n");
        fclose($f);
      }
      else { // Jika tidak memenuhi syarat
        $f = fopen("../".$_SESSION['_praFile']."_gagal.txt", 'a');
        fwrite($f, "$MhswID|$Nama|$psn\r\n");
        fclose($f);
      }
    }
    
    // Nex Process
    $_SESSION['_praPrc']++;
    $time = 10;
    echo <<<ESD
    <script>
    <!--
    //window.setTimeout("location='$_SESSION[ndelox].proses.php?lungo=Proses&_praPrc=$_praPrc&_praCnt=$_SESSION[_praCnt]'", $time);
    window.setTimeout("location='../$_SESSION[ndelox].proses.php'", $time);
    //-->
    </script>
ESD;
  }
  else {
    echo "
    <script>
    self.parent.Selesai();
    </script>
    ";
  }
}
function CheckSKSMin($MhswID, &$psn) {
  $JmlSKS = AmbilOneField('krs', "MhswID='$MhswID' and KodeID", KodeID, "sum(SKS)")+0;
  if ($JmlSKS >= $_SESSION['_praSKSMin']) {
    return TRUE;
  }
  else {
    $psn .= "SKS ($JmlSKS) tidak mencukupi. ";
    return FALSE;
  }
}
function CheckIPMin($MhswID, &$psn) {
  $IPK = AmbilOneField('krs', "MhswID='$MhswID' and KodeID", KodeID,
    "sum(BobotNilai*SKS)/sum(SKS)")+0;
  if ($IPK >= $_SESSION['_praIPKMin']) {
    return TRUE;
  }
  else {
    $psn .= "IPK ($IPK) tidak mencukupi. ";
    return FALSE;
  }
}
function CheckPrasyarat($MhswID, &$psn) {
  $arr = explode(',', $_SESSION['_praPrasyarat']);
  $oke = true; $_p = '';
  foreach ($arr as $_pra) {
    $_pra = TRIM($_pra);
    $pra = explode(':', $_pra);
    $nilai = AmbilOneField('krs', "MhswID='$MhswID' and MKKode", $pra[0], "BobotNilai")+0;
    if ($nilai > $pra[2]) {
    }
    else {
      $oke = false;
      $_p .= "Prasyarat $pra[0] tidak terpenuhi. ";
    }
  }
  $psn = $_p;
  return $oke;
  //$arrPrasyarat[] = $w['MKKode'].':'.$w['Nilai'].':'.$w['Bobot'];
}
?>
