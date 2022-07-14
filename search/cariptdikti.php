<?php

$Cari = $_REQUEST['Cari'];
if (empty($Cari)) {
  $_REQUEST['Pesan'] = "Tidak ada yang harus dicari.<hr size=1 />
    Masukkan Nama & Kota dari perguruan tinggi yg dicari dalam format: [<font color=maroon>NamaPT/SingkatanPT, KotaPT</font>]";
  echo $_REQUEST['Pesan'];
  //include "pesan.html.php";
}
else {
  session_start();
include "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";

echo "<HTML>
  <HEAD>
  <TITLE>Cari Perguruan Tinggi</TITLE>
  <link rel='stylesheet' type='text/css' href='../themes/$themas/index.css' />
  </HEAD>
  <BODY>";
TampilkanKembalikanScript();
TitleApps("Daftar Perguruan Tinggi <div align=right><a href='#' onClick=\"javascript:window.close()\"><img src='../img/kali.png' /></a></div>");
TampilkanDaftarPerguruanTinggi();

include_once "../disconnectdb.php";
echo "</BODY>
</HTML>";
}

function TampilkanKembalikanScript() {
echo <<<END
  <script>
  <!--
  function kembalikan(PerguruanTinggiID, Nama, Kota){
    creator.data.KodeHukum.value = PerguruanTinggiID;
    creator.data.Nama.value = Nama;
    creator.data.Kota.value = Kota;
    window.close();
  }
  -->
  </script>
END;
}
function TampilkanDaftarPerguruanTinggi() {
  global $Cari, $koneksi;
  $Max = 50;

  $arrcr = explode(',', $Cari);
  $arrwhr = array();
  if (!empty($arrcr[0])) $arrwhr[] = "((Nama like '%".TRIM($arrcr[0])."%') or (SingkatanNama like '%".TRIM($arrcr[0])."%')) ";
  if (!empty($arrcr[1])) $arrwhr[] = "Kota like '%".TRIM($arrcr[1])."%' ";
  $whr = implode(' and ', $arrwhr);
  // Hitung jumlah baris
  $Jml = AmbilOneField('perguruantinggi', "$whr and NA", 'N', "count(PerguruanTinggiID)");
  if ($Jml > $Max) {
    $_Jml = number_format($Jml);
    echo "<p><b>Catatan:</b> Jumlah perguruan tinggi yang Anda cari mencapai: <b>$_Jml</b>, tetapi sistem membatasi
      jumlah perguruan tinggi yang ditampilkan dan hanya menampilkan: <b>$Max</b>.
      Gunakan Nama perguruan tinggi dan Kota Sekolah dengan lebih spesifik untuk membatasi
      jumlah perguruan tinggi yang ditampilkan.</p>

      <p><b>Format Pencarian:</b> NamaPerguruanTinggi/Singkatan, KotaSekolah</p>";
  }
  // Tampilkan
  $s = "select PerguruanTinggiID, SingkatanNama, Nama, Kota
    from perguruantinggi
    where $whr and NA='N'
    order by Nama limit $Max";
  $r = mysqli_query($koneksi, $s);
  $n = 0;

  echo "<table class=box cellspacing=1 cellpadding=4 width=100%>
    <tr><th class=ttl width=20>#</th>
    <th class=ttl>Kode Sekolah</th>
    <th class=ttl>Singkatan</th>
    <th class=ttl>Nama</th>
    <th class=ttl>Kota</th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    echo "<tr><td class=ul>$n</td>
    <td class=ul><a href='javascript:kembalikan(\"$w[PerguruanTinggiID]\", \"$w[Nama]\", \"$w[Kota]\")'>$w[PerguruanTinggiID]</a></td>
    <td class=ul>$w[SingkatanNama]&nbsp;</td>
    <td class=ul>$w[Nama]&nbsp;</td>
    <td class=ul>$w[Kota]&nbsp;</td>
    </tr>";
  }
  echo "</table></p>";
}
?>
