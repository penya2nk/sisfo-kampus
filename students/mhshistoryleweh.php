<head>
<link rel="stylesheet" href="themes/default/index.css">
</head>

<section class="content">
    <div class="row">
        <div class="col-12">       
          <div class="card">
            <div class="card-body">
<?php

TitleApps("History Nilai");
global $koneksi;
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

  $urt = GainVariabelx('__urt', 'Sesi');
  if (empty($urt)) {
    $urt = 'Sesi';
    $_SESSION['__urt'] = $urt;
  }
  $arr = array("Sesi", "Matakuliah");
  $opturt = "";
  for ($i = 0; $i < sizeof($arr); $i++) {
    $nm = $arr[$i];
    $sel = ($urt == $nm)? 'selected' : '';
    $opturt .= "<option name='$nm' $sel>$nm</option>";
  }
  echo "<p align=center><form action='?' method=POST>
    <input type=hidden name='ndelox' value='students/mhshistoryleweh'>
    <input type=hidden name='sub' value='HIST'>
    <center> Urutkan daftar berdasarkan: <select name='__urt' onChange=\"this.form.submit()\">$opturt</select>
    </center></form></p>";
  
  // data
  if ($urt == 'Sesi') {
    $_urt = "k.TahunID, k.MKKode";
  } else {
    $_urt = "k.MKKode, k.TahunID";
  }
  $hdr = "<tr><td class=ttl>#</th>
    <th class=ttl>Kode</th>
    <th class=ttl>Matakuliah</th>
    <th class=ttl>SKS</th>
    <th class=ttl>Grade</th>
    <th class=ttl>Bobot</th>
    <th class=ttl>Tahun</th>
    </tr>";
  $s = "select k.*, mk.Nama
    from krs k
      left outer join mk mk on k.MKID=mk.MKID
      left outer join jadwal j on j.JadwalID = k.JadwalID
    where k.MhswID='$mhsw[MhswID]'
        and j.JenisJadwalID = 'K'
    order by $_urt";
  $r = mysqli_query($koneksi, $s); $n = 0; $ss = '';
  echo "</p align=center><center><table class=box cellspacing=1 width=100%>";
  if ($urt != 'Sesi') echo $hdr;
  while ($w = mysqli_fetch_array($r)) {
    $khs = AmbilFieldx('khs', "MhswID='$mhsw[MhswID]' and TahunID", $w['TahunID'], '*');
    $bal = $khs['Biaya'] - $khs['Bayar'] - $khs['Potongan'] + $khs['Tarik'];
    $_bal = number_format($bal);
    if ($bal > 0) {
      $w['GradeNilai'] = "<font color=red>*</font>";
      $w['BobotNilai'] = "<font color=red>*</font>";
      $ket = "<p align=center><table class=box ><tr><td class=inp><font color=red>*</font></td>
              <td class=inp>Anda masih memiliki utang sebesar : Rp.$_bal</td><tr></table></p>";  
    }
    if ($urt == 'Sesi') {
      if ($ss != $w['TahunID']) {
        $ss = $w['TahunID'];
        echo "<tr><td class=ttl colspan=10>Semester: $w[TahunID]</td></tr>";
        echo $hdr;
      }
    }
    else {
    }
    $n++;
    echo "<tr>
    <td class=ul>$n</td>
    <td class=nac>$w[MKKode]</td>
    <td class=nac>$w[Nama]</td>
    <td class=ul align=right>$w[SKS]</td>
    <td class=ul align=center>$w[GradeNilai]</td>
    <td class=ul align=right>$w[BobotNilai]</td>
    <td class=ul align=right>$w[TahunID]</td>
    </tr>";
  }
  echo "</center></table></p>";
  echo $ket;

?>
</div>
          </div>
      </div>
    </div>
</section> 