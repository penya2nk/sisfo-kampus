<?php
  session_start();
include "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";

 
echo <<<END
  <HTML>
  <HEAD>
  <TITLE>Cari Program Studi Lengkap</TITLE>
  <link href="../themes/$themas/index.css" rel="stylesheet" type="text/css">
  </HEAD>
  <BODY>
END;

$Cari = $_REQUEST['Cari'];

if (empty($Cari)) {
  include_once "../dwo.lib.php";
  echo (PesanError('Error', 
    "Tidak ada yang harus dicari.<hr size=1 />
    Masukkan sedikit huruf dari Program Studi yg akan dicari"));
}
else {

TampilkanKembalikanScript();
TampilkanJudul("Daftar Program Studi");
TampilkanDaftarProgramStudi();

include_once "../disconnectdb.php";
echo "</BODY>
</HTML>";
}

function TampilkanKembalikanScript() {
echo <<<END
  <script>
  <!--
  function kembalikan(ProdiID, Nama){
    creator.data.ProdiDiktiID.value = ProdiID;
    creator.data.NamaProdi.value = Nama;
    window.close();
  }
  -->
  </script>
END;
}
function TampilkanDaftarProgramStudi() {
  global $Cari, $koneksi;
  $Max = 50;

  $arrcr = explode(',', $Cari);
  $arrwhr = array();
  if (!empty($arrcr[0])) $arrwhr[] = "(Nama like '%".TRIM($arrcr[0])."%') ";
  $whr = implode(' and ', $arrwhr);
  // Hitung jumlah baris
  $Jml = AmbilOneField('perguruantinggi', "$whr and NA", 'N', "count(PerguruanTinggiID)");
  if ($Jml > $Max) {
    $_Jml = number_format($Jml);
    echo "<p><b>Catatan:</b> Jumlah program studi yang Anda cari mencapai: <b>$_Jml</b>, tetapi sistem membatasi
      jumlah program studi yang ditampilkan dan hanya menampilkan: <b>$Max</b>.
      Gunakan Nama program studi dengan lebih spesifik untuk membatasi
      jumlah program studi yang ditampilkan.</p>

      <p><b>Format Pencarian:</b> NamaProgramStudi</p>";
  }
  // Tampilkan
  $s = "select ProdiDiktiID, Nama
    from prodidikti
    where $whr and NA='N'
    order by Nama limit $Max";
  $r = mysqli_query($koneksi, $s);
  $n = 0;
  echo "<p><table class=box cellspacing=1 cellpadding=4 width=100%>
    <tr><th class=ttl>#</th>
    <th class=ttl>Kode Prodi</th>
    <th class=ttl>Nama</th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    echo "<tr><td class=ul>$n</td>
    <td class=ul><a href='javascript:kembalikan(\"$w[ProdiDiktiID]\", \"$w[Nama]\")'>$w[ProdiDiktiID]</a></td>
    <td class=ul>$w[Nama]&nbsp;</td>
    </tr>";
  }
  echo "</table></p>";
}
?>
