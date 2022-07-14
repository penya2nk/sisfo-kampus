<?php
function rupiah($angka){
  $rupiah=number_format($angka,2,',','.');
  return $rupiah;
}


# Fungsi untuk membuat kode automatis
function buatKode($tabel, $inisial){
	/*
	$struktur	= mysql_query("SELECT * FROM $tabel");
	$field		= mysql_field_name($struktur,0);
	$panjang	= mysql_field_len($struktur,0);

 	$qry	= mysql_query("SELECT MAX(".$field.") FROM ".$tabel);
 	$row	= mysql_fetch_array($qry); */
	
	$struktur	= $db->database_prepare("SELECT * FROM $tabel")->execute();
	$field		= mysql_field_name($struktur,0);
	$panjang	= mysql_field_len($struktur,0);

 	$qry	= $db->database_prepare("SELECT MAX(".$field.") FROM ".$tabel)->execute();
	$row = $db->database_fetch_array($qry);
 	if ($row[0]=="") {
 		$angka=0;
	}
 	else {
 		$angka		= substr($row[0], strlen($inisial));
 	}
	
 	$angka++;
 	$angka	=strval($angka); 
 	$tmp	="";
 	for($i=1; $i<=($panjang-strlen($inisial)-strlen($angka)); $i++) {
		$tmp=$tmp."0";	
	}
 	return $inisial.$tmp.$angka;
}
?> 