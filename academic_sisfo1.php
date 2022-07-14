<?php
	include_once "../pengembang.lib.php";
	include_once "../konfigurasi.mysql.php";
	include_once "../sambungandb.php";
	
function ViewHeaderApps($title='', $use_facebox=0) {
	include_once "../setting_awal.php";
	include_once "../check_setting.php";
	

	
  echo "<HTML xmlns=\"http://www.w3.org/1999/xhtml\">
  <HEAD><TITLE>$title</TITLE>
  <META content=\"SIAKAD TEAM\" name=\"author\">
  <META content=\"SIAKAD TEAM\" name=\"description\">
  <link rel=\"stylesheet\" type=\"text/css\" href=\"../themes/$themas/index.css\" />
  <link rel=\"stylesheet\" type=\"text/css\" href=\"../themes/$themas/drag.css\" />
  <script type=\"text/javascript\" language=\"javascript\" src=\"../include/js/drag.js\"></script>
  
  ";
  
  //<script src='../putiframe.js' language='javascript' type='text/javascript'></script>
  
  if ($use_facebox == 1) {
?>
  
  <script src="../fb/jquery.pack.js" type="text/javascript"></script>
  <link href="../fb/facebox.css" media="screen" rel="stylesheet" type="text/css" />
  <script src="../fb/facebox.js" type="text/javascript"></script>
  
  <script type="text/javascript">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox() 
    })
	
	function getDateTime(ob){
		var curDate = document.getElementById('alt'+ob).value;
		curDate = curDate.replace('-','/');
		curDate = curDate.replace('-','/');
		var period = Date.parse(curDate);
		
		return period;
	}

  </script>

<?php
  }
  echo "</HEAD>
  <BODY>";
}
?>

