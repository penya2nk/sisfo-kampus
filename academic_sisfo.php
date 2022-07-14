<?php

function ViewHeaderApps($title='') {
 	include_once "../konfigurasi.mysql.php"; 
	include_once "../sambungandb.php";
  include_once "../pengembang.lib.php";
	include_once "../setting_awal.php";

 
  echo "<HTML xmlns=\"http://www.w3.org/1999/xhtml\">
  <HEAD><TITLE>$title</TITLE>
  <META content=\"ACADEMIC SISFO\" name=\"author\">
  <META content=\"Academic Sisfo\" name=\"description\">
  <link href=\"themes/default/index.css\" rel=\"stylesheet\" type=\"text/css\">
  ";
}
?>
<script src='../putiframe.js' language='javascript' type='text/javascript'></script>
