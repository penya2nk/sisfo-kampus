
<section class="content">
    <div class="row">
        <div class="col-12">       
          <div class="card">
            <div class="card-body">
<?php


// *** Functions ***
function TampilkanHeaderJadwalDosen() {
  $optdsn = AmbilCombo2('dosen', "concat(Login, ' - ', Nama, ', ', Gelar)",
    "Nama", $_SESSION['dosen'], '', 'Login');
  echo "<p><table id='example1' class='table table-sm table-striped'>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='dosen/dosen.jadwal'>
  <tr><td class=wrn>$_SESSION[KodeID]</td>
    <td class=inp1>Tahun Akd.</td><td class=ul><input type=text name='tahun' value='$_SESSION[tahun]' size=10 maxlength=10></td>
    <td class=inp1>Dosen</td><td class=ul><select name='dosen'>$optdsn</select></td>
    <td class=ul><input type=submit name='Kirim' value='Kirim'></td></tr>
  </form></table></p>";
}

function TampilkanJadwalDosen() {
  $s = "select j.*, h.Nama as HR
    from jadwal j
      left outer join hari h on j.HariID=h.HariID
    where j.DosenID='$_SESSION[dosen]'
    and j.TahunID='$_SESSION[tahun]'
    order by j.HariID, j.JamMulai, j.MKKode";
  $r = mysqli_query($koneksi, $s); //Ganti $_SESSION['_Login'] tidak boleh lihat dosen lain
  // Tampilkan
  $nomer = 0; $hari = -1; $totsks = 0;
  $hdrjdwl = "<tr style='background:purple;color:white'>
    <th class=ttl>No</th>
    <th class=ttl>Jam</th>
    <th class=ttl>Ruang</th>
    <th class=ttl>Kode MK</th>
    <th class=ttl>Matakuliah</th>
    <th class=ttl>Kelas</th>
    <th class=ttl>SKS</th>
    <th class=ttl>Prodi</th>
    <th class=ttl>Dosen</th>
    <th class=ttl title='Presensi'>Prs</th>
    <th class=ttl>Link</th>
    </tr>";
  echo "<p><table id='example1' class='table table-sm table-striped'>";
  while ($w = mysqli_fetch_array($r)) {
    if ($hari != $w['HariID']) {
      $hari = $w['HariID'];
      echo "<tr><td class=ul colspan=12><b>$w[HR]</b></td></tr>";
      echo $hdrjdwl;
    }
    $nomer++;
    $totsks += $w['SKS'];
    // Array dosen
    $arrdosen = explode('.', TRIM($w['DosenID'], '.'));
    $strdosen = implode(',', $arrdosen);
    $_dosen = (empty($strdosen))? '' : GetArrayTable("select Nama from dosen where Login in ($strdosen) order by Nama",
      "Login", "Nama", '<br />');
    // Array prodi
    $arrprodi = explode('.', TRIM($w['ProdiID'], '.'));
    $strprodi = implode(',', $arrprodi);
    $_prodi = (empty($strprodi))? '' : GetArrayTable("select Nama from prodi where ProdiID='($strprodi)' order by ProdiID",
      "ProdiID", "Nama", '<br />');

    echo "<tr><td class=inp1>$nomer</td>
      <td class=ul>$w[JamMulai]-$w[JamSelesai]</td>
      <td class=ul>$w[RuangID]</td>
      <td class=ul>$w[MKKode]</td>
      <td class=ul>$w[Nama]</td>
      <td class=ul>$w[NamaKelas]&nbsp;</td>
      <td class=ul>$w[SKS] ($w[SKSAsli])</td>
      <td class=ul>$_prodi</td>
      <td class=ul>$_dosen</td>
      <td class=ul align=right>$w[Kehadiran]</td>
      <td class=ul><a href='?ndelox=dosen.nilai&tahun=$_SESSION[tahun]&jadwalid=$w[JadwalID]&dosen=$_SESSION[dosen]'>Nilai</a></td>
      </tr>";
  }
  echo "<tr><td colspan=6 align=right>Total SKS :</td><td class=cnnY align=right><b>$totsks</b></td></tr>
    </table></p>";
}

// *** Parameters ***
$tahun = GainVariabelx('tahun');
$dosen = GainVariabelx('dosen');

// *** Main ***
TitleApps("Jadwal Mengajar Dosen");
TampilkanHeaderJadwalDosen();
if (!empty($dosen) && !empty($tahun)) TampilkanJadwalDosen();
?>
</div>
          </div>
      </div>
    </div>
</section> 