<?php

$arrMenu = array(
  "Manual 1~SOP9.pdf",
  "Manual 2~SOP3.pdf",
  "Manual 3~SOP1.pdf",
  );
  
function ViewMenu() {
  global $arrMenu;
  echo"<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>
  <tr style='background:purple;color:white'>
  <td>NO</td>
  <td>NAMA PANDUAN</td>
  </tr>";
  for ($i = 1; $i < sizeof($arrMenu); $i++) {
    $a = Explode('~', $arrMenu[$i]);
    echo "<tr>
    <td>$i</td>
    <td><a href='manual/" . $a[1] . "' target=_blank>" .$a[0] . "</a>"."</td>";
  }
  echo"</tr>
  </table>
  </div>
</div>
</div>";
}

TitleApps("MANUAL PENGGUNAAN SYSTEM");
ViewMenu();

?>
