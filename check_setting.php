<?php
function Reset_Log_In() {
  $_SESSION['mdlid'] = 0;
  $_SESSION['_Login'] = '';
  $_SESSION['_Nama'] = '';
  $_SESSION['_TabelUser'] = '';
  $_SESSION['_LevelID'] = 0;
  $_SESSION['_Session'] = '';
  $_SESSION['_Superuser'] = 'N';
  $_SESSION['_KodeID'] = '';
  $_SESSION['_ProdiID'] = '';
  $_SESSION['KodeID'] = KodeID;
}

$_KodeID = GainVariabelx('KodeID', KodeID);
if ($_KodeID != KodeID) {
  Reset_Log_In();
  $_KodeID = KodeID;
  $_SESSION['KodeID'] = KodeID;
  die("<h1>Error</h1> Hubungi Administrator!.<hr size=1 color=purple />Opsi: <a href='index.php?KodeID=$_SESSION[KodeID]'>Kembali</a>");
}

$ndelox = GainVariabelx('ndelox', $_defndelox);
if (empty($ndelox)) {
  $ndelox = $_defndelox;
  $_SESSION['ndelox'] = $_defndelox;
}

if (empty($_SESSION['_Session']) && empty($ndelox)) {
  $ndelox = $_defndelox;
  $_SESSION['ndelox'] = $_defndelox;
  $_SESSION['mdlid'] = 0;
}



?>
