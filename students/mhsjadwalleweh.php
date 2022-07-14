<head>
<!-- <link rel="stylesheet" href="themes/default/index.css"> -->
</head>

<section class="content">
    <div class="row">
        <div class="col-12">       
          <div class="card">
            <div class="card-body">
<?php
TitleApps("Jadwal Kuliah Mahasiswa");
global $koneksi;
// $sub = GainVariabelx('sub', 'KHS'); //perlu
$_SESSION['_TahunID'] = GainVariabelx("_TahunID");
if (empty($_SESSION['_TahunID'])) {
  $_SESSION['_TahunID'] = AmbilOneField('khs', "MhswID", $_SESSION['_Login'], "TahunID");
}
$__JJadwal = GainVariabelx('__JJadwal', 1);

$mhsw = AmbilFieldx("mhsw m
  left outer join statusmhsw sm on m.StatusMhswID=sm.StatusMhswID
  left outer join program prg on m.ProgramID=prg.ProgramID
  left outer join prodi prd on m.ProdiID=prd.ProdiID
  left outer join agama agm on m.Agama=agm.Agama
  left outer join kelamin kel on m.Kelamin=kel.Kelamin
  left outer join statussipil kwn on m.StatusSipil=kwn.StatusSipil", 
  "MhswID", $_SESSION['_Login'], 
  "m.*, sm.Nama as STA, 
  prg.Nama as PRG, prd.Nama as PRD, agm.Nama as AGM, kel.Nama as KEL,
  kwn.Nama as KWN");



  $thna = AmbilOneField("tahun", "ProgramID='$mhsw[ProgramID]' and ProdiID='$mhsw[ProdiID]' and NA", 'N', 'TahunID');
  
  $JJadwal = array(0=>"Jadwal Prodi~fakultas", 1=>"Jadwal KRS~KRS");
  $a = '';
  for ($i=0; $i<sizeof($JJadwal); $i++) {
    $sel = ($i == $_SESSION['__JJadwal'])? 'selected' : '';
    $v = explode('~', $JJadwal[$i]);
    $_v = $v[0];
    $a .= "<option value='$i' $sel>$_v</option>\r\n";
	}
	echo "<p align=center><table id='example1' class='table table-sm table-striped' >
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='students/mhsjadwalleweh'>
  <input type=hidden name='sub' value='$sub'>
  <tr><td class=inp>Pilih Jenis Jadwal</td>
      <td class=ul><select name='__JJadwal' onChange='javascript:this.form.submit()'>$a</select></td>
      </tr>
  </form></table></p>";
  echo "<p>Berikut adalah jadwal Tahun Akademik: <b>$thna</b>. Silakan hubungi Tata Usaha untuk informasi lebih lanjut.</p>";
  // Tampilkan jadwal
  $s = "select j.JadwalID, j.MKKode, j.Nama, j.NamaKelas, j.SKS, j.SKSAsli, 
    j.HariID, j.DosenID, j.RuangID, j.JamMulai, j.JamSelesai, 
    h.Nama as HR, jj.Nama as JJ,
    concat(d.Nama, ', ', d.Gelar) as DSN
    from jadwal j
      left outer join hari h on j.HariID=h.HariID
      left outer join jenisjadwal jj on j.JenisJadwalID=jj.JenisJadwalID
      left outer join dosen d on j.DosenID=d.Login
    where j.TahunID='$thna'
      and INSTR(j.ProgramID, '.$mhsw[ProgramID].')>0
      and INSTR(j.ProdiID, '.$mhsw[ProdiID].')>0
    order by j.HariID";
		
	$s0 = "select k.*, j.*, jj.Nama as JJ, d.Nama as DSN,
    sk.Nama as SK, sk.Ikut, sk.Hitung, k.SKS as SKSnya,
    time_format(j.JamMulai, '%H:%i') as JM,
    time_format(j.JamSelesai, '%H:%i') as JS
    from krs k
      left outer join jadwal j on k.JadwalID=j.JadwalID
      left outer join jenisjadwal jj on j.JenisJadwalID=jj.JenisJadwalID
      left outer join statuskrs sk on k.StatusKRSID=sk.StatusKRSID
			left outer join dosen d on j.DosenID=d.Login
    where k.MhswID = '$mhsw[MhswID]'
			and k.TahunID = '$thna'
      and k.NA='N'
    order by j.HariID, j.JamMulai, j.MKKode ";
	//echo $_SESSION['__JJadwal'];
	if ($_SESSION['__JJadwal'] == 1)  $s1 = $s0;
	else $s1 = $s;
  $r = mysqli_query($koneksi, $s1);
  $hdr = "<tr style='background:purple;color:white'><th class=ttl>#</th>
      <th class=ttl>Jam</th>
      <th class=ttl>Ruang</th>
      <th class=ttl>Kode</th>
      <th class=ttl>Matakuliah</th>
      <th class=ttl>Kelas</th>
      <th class=ttl>Kuliah</th>
      <th class=ttl>SKS</th>
      <th class=ttl>Dosen Pengampu</th>
      </tr>";
  echo "<p><table id='example1' class='table table-sm table-striped'>";
  $hr = -1; $n = 0;
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    if ($hr != $w['HariID']) {
      $hr = $w['HariID'];
      echo "<tr><td class=ttl colspan=10>$w[HR]</td></tr>";
      echo $hdr;
    }
    $jm = substr($w['JamMulai'], 0, 5);
    $js = substr($w['JamSelesai'], 0, 5);
    echo "<tr>
    <td class=ul>$n</td>
    <td class=ul>$jm - $js</td>
    <td class=ul>$w[RuangID]</td>
    <td class=ul>$w[MKKode]</td>
    <td class=ul>$w[Nama]</td>
    <td class=ul>$w[NamaKelas]</td>
    <td class=ul>$w[JJ]</td>
    <td class=ul>$w[SKS] ($w[SKSAsli])</td>
    <td class=ul>$w[DSN]</td>
    </tr>";
  }
  echo "</table></p>";
?>
</div>
          </div>
      </div>
    </div>
</section> 