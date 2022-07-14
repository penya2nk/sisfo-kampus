<?php

session_start();
include_once "../academic_sisfo1.php";

ViewHeaderApps("Cari Dosen");

$ProdiID = GainVariabelx('ProdiID');
$Nama = GainVariabelx('Nama');

echo "$ProdiID &raquo; $Nama";

?>


<p>
<a href='#' onClick="javascript:frmJadwal.DosenID.value='Test';frmJadwal.Dosen.value='Nama';jQuery.facebox.close()">Close</a>
</p>
