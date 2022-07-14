<?php


$Cari = $_REQUEST['Cari'];
if (empty($Cari)) {
  $_REQUEST['Pesan'] = "Tidak ada yang harus dicari.<hr size=1 />
    Masukkan Nama & Kota dari sekolah yg dicari dalam format: [<font color=maroon>NamaSekolah, KotaSekolah</font>]";
  echo $_REQUEST['Pesan'];
}
else {
	include "../konfigurasi.mysql.php";
	include_once "../sambungandb.php";
	include_once "../pengembang.lib.php";
	include_once "../setting_awal.php";
	include_once "../check_setting.php";

echo "
<HTML>
  <HEAD>
  <TITLE>Cari Sekolah</TITLE>
  <link rel='stylesheet' type='text/css' href='../themes/".$themas."/index.css' />
  </HEAD>
  <BODY>
";
TampilkanKembalikanScript();
TitleApps("Daftar Sekolah");
TampilkanDaftarSekolah();

}

function TampilkanKembalikanScript() {
echo <<<END
  <script>
  <!--
  function kembalikan(SekolahID, Nama, Kota){
    creator.data.AsalSekolah.value = SekolahID;
    creator.data.NamaSekolah.value = Nama + ", " + Kota;
    window.close();
  }
  -->
  </script>
END;
}
function TampilkanDaftarSekolah() {
  global $Cari, $koneksi;
  $Max = 50;

  $arrcr = explode(',', $Cari);
  $arrwhr = array();
  if (!empty($arrcr[0])) $arrwhr[] = "Nama like '%".TRIM($arrcr[0])."%' ";
  if (!empty($arrcr[1])) $arrwhr[] = "Kota like '%".TRIM($arrcr[1])."%' ";
  $whr = implode(' and ', $arrwhr);
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
  echo "<p><table class=box cellspacing=1 cellpadding=4>
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
  echo "</table></p>";
}
?>

</BODY>
</HTML>
