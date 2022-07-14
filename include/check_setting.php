<?php

function ResetLogin() {
  global $_defmnux;
  $_SESSION['ndelox'] = $_defmnux;
  $_SESSION['mdlid'] = 0;
  $_SESSION['_Login'] = '';
  $_SESSION['_Nama'] = '';
  $_SESSION['_TabelUser'] = '';
  $_SESSION['_LevelID'] = 0;
  $_SESSION['_Session'] = '';
  $_SESSION['_Superuser'] = 'N';
  $_SESSION['_KodeID'] = '';
  $_SESSION['_ProdiID'] = '';
  
}

$ndelox = GetSetVar('ndelox', $_defmnux);
if (empty($ndelox)) {
  $ndelox = $_defmnux;
  $_SESSION['ndelox'] = $_defmnux;
}
if (empty($_SESSION['_Session'])) {
  $ndelox = $_defmnux;
  $_SESSION['ndelox'] = $_defmnux;
  $_SESSION['mdlid'] = 0;
}

?>