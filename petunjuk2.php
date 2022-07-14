<?php

$arrPanduan = array(
  "Panduan Login User~login.pdf",
  "Panduan Simbol Penting~simbol.pdf",

  );

TitleApps("DAFTAR PANDUAN");
TampilkanDaftarPanduan();

function TampilkanDaftarPanduan() {
  global $arrPanduan;
  echo "<ol>";
  for ($i = 0; $i < sizeof($arrPanduan); $i++) {
    $a = Explode('~', $arrPanduan[$i]);
    echo "<li><a href='desain/" . $a[1] . "' target=_blank>" .
      $a[0] .
      "</li>";
  }
  echo "</ol>";
}
?>
