<?php
error_reporting(0);
session_start();
$Cari = $_REQUEST['Cari'];
if (empty($Cari)) {
  $_REQUEST['Pesan'] = "Tidak ada yang harus dicari.<hr size=1 />
    Masukkan Nama & Kota dari sekolah yg dicari dalam format: [<font color=maroon>NamaSekolah, KotaSekolah</font>]";
  include "pesan.html.php";
}
else {
include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";

echo "<HTML>
  <HEAD>
  <TITLE>Cari Sekolah</TITLE>";
echo "<link rel='stylesheet' type='text/css' href='../themes/$themas/index.css' />";
echo "</HEAD>
  <BODY>";
TampilkanKembalikanScript();
TitleApps("Daftar Sekolah");
TampilkanDaftarSekolah();

include_once "disconnectdb.php";
echo "</BODY>
</HTML>";
}

function TampilkanKembalikanScript() {
echo <<<END
  <script>
  <!--
  function kembalikan(SekolahID, Nama, Kota){
    creator.AsalSekolah.value = SekolahID;
    creator.NamaSekolah.value = Nama + ", " + Kota;
	creator.SavAsalSekolah.value = SekolahID;
	window.close();
  }
  -->
  </script>
END;
}

function RemoveBannedWords(&$arr)
{	$arrBannedWords = array('SMA', 'SMU', 'SMK', 'SAM', 'SCHOOL', 'SEKOLAH', 'SM', 'SFMA', 'SLTA', 'SMEA');
	
	foreach($arr as $key=>$element)
	{	if(in_array($element, $arrBannedWords))
		{	unset($arr[$key]);
		}
	}
} 

function TampilkanDaftarSekolah() {
  global $Cari, $koneksi;
  $Max = 50;
  
  $arrcr = explode(',', $Cari);
  $arrcrmixed = array(); 
  $arrcrnama = array(); 
  $arrwhrnamaangks = array(); 
  $arrwhrnama = array(); 
  $arrwhrkota = array(); 
  
  if(!empty($arrcr)){	
	if(count($arrcr) == 1)
	{	$arrcrmixed = explode(' ', TRIM($arrcr[0]));
		RemoveBannedWords($arrcrmixed);
    $cr=1;
		foreach($arrcrmixed as $cr)
		{	
      if((int)$cr+0 > 0) $arrwhrnamaangka[] = "Nama like ' ".TRIM($cr)." ' ";
			else
			{	$arrwhrnama[] = "Nama like '%".TRIM($cr)."%' ";
				$arrwhrkota[] = "Kota like '%".TRIM($cr)."%' ";
			}
		}
		$divider = 'or';
	}
	else
	{	$arrcrnama = explode(' ', TRIM($arrcr[0]));
		
		RemoveBannedWords($arrcrnama);
		foreach($arrcrnama as $cr) 
		{	if((int)$cr+0 > 0) $arrwhrnamaangka[] = "Nama like '% ".TRIM($cr)." %' ";
			else $arrwhrnama[] = "Nama like '%".TRIM($cr)."%' ";
		}
	    $arrwhrkota[] = "Kota like '%".TRIM($arrcr[1])."%' ";
		$divider = 'and';
	}
  }
  
  $whr = '('.((!empty($arrwhrnama))? implode(' or ', $arrwhrnama) : 1).') '.
		((!empty($arrwhrnamaangka))? $divider.' ('.implode(' or ', $arrwhrnamaangka) : ' and (1').') '.
		((!empty($arrwhrkota))? $divider.' ('.implode(' or ', $arrwhrkota) : ' and (1').')';
  
  // Hitung jumlah baris
  $Jml = AmbilOneField('asalsekolah', "$whr and NA", 'N', "count(SekolahID)");
  if ($Jml > $Max) {
    $_Jml = number_format($Jml);
    echo "<p><b>Catatan:</b> Jumlah Sekolah yang Anda cari mencapai: <b>$_Jml</b>, tetapi sistem membatasi
      jumlah sekolah yang ditampilkan dan hanya menampilkan: <b>$Max</b>.
      Gunakan Nama Sekolah dan Kota Sekolah dengan lebih spesifik untuk membatasi
      jumlah sekolah yang ditampilkan.</p>

      <p><b>Format Pencarian:</b> NamaSekolah, KotaSekolah</p>";
  }
  // Tampilkan
  $s = "select SekolahID, Nama, Kota, JenisSekolahID
    from asalsekolah
    where $whr and NA='N'
    order by Nama limit $Max";
  $r = mysqli_query($koneksi, $s);
  $n = 0;
  
  echo "<p><table class=box cellspacing=1 cellpadding=4 align=center>
    <form name='datasekolah' method=POST>
	<tr><th class=ttl>#</th>
    <th class=ttl>Kode Sekolah</th>
    <th class=ttl>Nama</th>
    <th class=ttl>Kota</th>
    <th class=ttl>Jenis</th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    echo "<tr><td class=ul>$n</td>
    <td class=ul><a href='javascript:kembalikan(\"$w[SekolahID]\", \"$w[Nama]\", \"$w[Kota]\")'>$w[SekolahID]</a></td>
    <td class=ul>$w[Nama]</td>
    <td class=ul>$w[Kota]</td>
    <td class=ul>$w[JenisSekolahID]</td>
    </tr>";
  }
  echo "
	<tr><td colspan=10 align=center><input type=button name='Tutup' value='Tutup' onClick=\"window.close()\"></td></tr>
	</form>
  </table></p>";
}
?>
