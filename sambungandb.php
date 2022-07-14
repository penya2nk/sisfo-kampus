<?php
include 'config/model.php';
// local ----------------------------------------------------------------------
require_once('fungsi_validasi.php');
$db['host'] = "localhost"; 
$db['user'] = "root"; 
$db['pass'] = ""; 
$db['name'] = "db_uti_sr"; 
$koneksi = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name']);

?>
