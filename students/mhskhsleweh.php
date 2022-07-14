<head>
<link rel="stylesheet" href="themes/default/index.css">
</head>


<section class="content">
    <div class="row">
        <div class="col-12">       
          <div class="card">
            <div class="card-body">
<?php
function TampilkanPilihanTahunMhsw($mhsw, $ndelox='', $sub='') {
  $optthn = AmbilCombo2("khs", "TahunID", "TahunID", $_SESSION['_TahunID'], "MhswID='$mhsw[MhswID]'", "TahunID");
  echo "<p><table class=bsc align=center>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='mhsw/mhskhsleweh'>
  <input type=hidden name='sub' value='$sub'>
  <tr><td class=inp>Pilih Tahun Akademikx</td>
      <td class=ul><select name='_TahunID' onChange='javascript:this.form.submit()'>$optthn</select></td>
      </tr>
  </form></table></p>";
}

function KHS($mhsw) {
  global $koneksi;
  TampilkanPilihanTahunMhsw($mhsw, 'mhskhsleweh', 'KHS');
  // Data
  $s = "select k.JadwalID, k.KRSID, k.SKS, k.BobotNilai, k.GradeNilai, k.MKKode, 
    mk.Nama as Nama
    from krs k
      left outer join mk mk on k.MKID=mk.MKID
      left outer join jadwal j on j.JadwalID = k.JadwalID
    where k.MhswID='$mhsw[MhswID]'
      and k.TahunID='$_SESSION[_TahunID]'
      and (j.JenisJadwalID <> 'R' 
      or j.JenisJadwalID is NULL)
    order by k.MKKode";
  $r = mysqli_query($koneksi, $s);
  $khs = AmbilFieldx('khs', "MhswID='$mhsw[MhswID]' and TahunID", $_SESSION['_TahunID'], '*');
  $bal = $khs['Biaya'] - $khs['Bayar'] - $khs['Potongan'] + $khs['Tarik'];
  $_bal = number_format($bal);
  echo "<p align=center>
  <table class=box cellspacing=1 colspan=4 width=100%>
  <tr height=40><th class=ttl cellspacing=2>#</th>
      <th class=ttl cellspacing=2 >Kode</th>
      <th class=ttl>Matakuliah</th>
      <th class=ttl>SKS</th>
      <th class=ttl>Grade</th>
      <th class=ttl>Bobot</th>
      </tr>";
  $n = 0;
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    if ($bal > 0) {
      $w['GradeNilai'] = "<font color=red>*</font>";
      $w['BobotNilai'] = "<font color=red>*</font>";
      $ket = "<p align=center><table class=box><tr><td class=inp><font color=red>*</font></td>
              <td class=inp>Anda masih memiliki utang sebesar : Rp.$_bal</td><tr></table></p>";
    }
    echo "<tr>
      <td class=ul>$n</td>
      <td class=ul>$w[MKKode]</td>
      <td class=ul>$w[Nama]</td>
      <td class=ul align=right>$w[SKS]</td>               
      <td class=ul align=center>$w[GradeNilai]</td>
      <td class=ul align=right>$w[BobotNilai]</td>
      </tr>";
  }
  echo "</table></p>";
  echo $ket;
  // Tampilkan rekap KHS
  echo "<p align=center><table class=box align=center>
  <tr><td class=inp>Total SKS</td>
    <td class=ul align=right>$khs[TotalSKS] &nbsp;</td>
    </tr>
  <tr><td class=inp>Jumlah Matakuliah</td>
    <td class=ul align=right>$khs[JumlahMK] &nbsp;</td>
    </tr>
  <tr>
    <td class=inp>IPS</td>
    <td class=ul align=right>$khs[IPS] &nbsp;</td>
    </tr>
  </table></p>";
}

// *** Parameters ***
//agar nilai history tersimpan
//$lungo = (empty($_REQUEST['lungo']))? 'KHS' : $_REQUEST['lungo'];

$sub = GainVariabelx('sub', 'KHS'); //perlu
$_SESSION['_TahunID'] = GainVariabelx("_TahunID");
if (empty($_SESSION['_TahunID'])) {
  $_SESSION['_TahunID'] = AmbilOneField('khs', "MhswID", $_SESSION['_Login'], "TahunID");
  //echo"Tahun $_SESSION[_TahunID]";
}
$__JJadwal = GainVariabelx('__JJadwal', 1);

// *** Main ***
TitleApps("Informasi KHS Semester");
//echo"Tahun $_SESSION[_TahunID]";
//echo" $_SESSION[_Login]";

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
if (!empty($mhsw)) $sub($mhsw);
//$lungo();
?>
</div>
          </div>
      </div>
    </div>
</section> 