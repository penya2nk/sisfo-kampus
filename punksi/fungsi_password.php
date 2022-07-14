<?php
function password(){
	$gpass=NULL;
	$n = 20; // jumlah karakter yang akan di bentuk.
	$chr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvqxyz0123456789@";
	for($i=0;$i<$n;$i++){
		$rIdx = rand(1,strlen($chr));
		$gpass .=substr($chr,$rIdx,1);
	}
	return $gpass;
};
?> 