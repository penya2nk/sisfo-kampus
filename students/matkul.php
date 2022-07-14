<?php
$MhswID = $_SESSION['_Login'];
$w = AmbilFieldx('mhsw', "Login='$MhswID' and KodeID", KodeID,"*");	
//$MhswID = GainVariabelx('MhswID');
$mhsw = AmbilFieldx('mhsw', "MhswID = '$MhswID' and KodeID", KodeID, "*");

// *** Main ***
TitleApps("History Matakuliah Mahasiswa");
TampilkanAmbilMhswID($MhswID, $mhsw);

if ($MhswID == '') {
  echo Info("Masukkan Parameter",
    "Masukkan NIM/NPM dari Mahasiswa pindahan.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.");
}
// Cek apakah mahasiswanya ketemu atau tidak
elseif (empty($mhsw)) {
  echo PesanError("Error",
    "Mahasiswa dengan NIM/NPM: <b>$MhswID</b> tidak ditemukan.<br />
    Masukkan NIM/NPM yang sebenarnya.
    <hr size=1 color=silver />
    Hubungi Sysadmin untuk informasi lebih lanjut.");
}
/*
elseif ($mhsw['Keluar'] == 'Y') {
  echo PesanError("Error",
    "Mahasiswa dengan NIM/NPM: <b>$MhswID</b> telah keluar/lulus.<br />
    Anda sudah tidak dapat mengubah konversi.
    <hr size=1 color=silver />
    Hubungi Sysadmin untuk informasi lebih lanjut.");
} */
else {
  // Cek apakah punya hak akses terhadap mhsw dari prodi ini?
  if (strpos($_SESSION['_ProdiID'], $mhsw['ProdiID']) === false) {
    echo PesanError("Error",
      "Anda tidak memiliki hak akses terhadap mahasiswa ini.<br />
      Mahasiswa: <b>$MhswID</b>, Prodi: <b>$mhsw[ProdiID]</b>.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut.");
  }
  // hak akses oke
  else {
      $lungo = (empty($_REQUEST['lungo']))? 'DftrMK' : $_REQUEST['lungo'];
      $lungo($MhswID, $mhsw);
  }
}

// *** Functions ***
function TampilkanAmbilMhswID($MhswID, $mhsw) {
  $stawal = AmbilOneField('statusawal', 'StatusAwalID', $mhsw['StatusAwalID'], 'Nama');
  $status = AmbilOneField('statusmhsw', 'StatusMhswID', $mhsw['StatusMhswID'], 'Nama');
  if (empty($mhsw['PenasehatAkademik'])) {
    $pa = '<sup>Belum diset</sup>';
  }
  else {
    $dosenpa = AmbilFieldx('dosen', "Login='$mhsw[PenasehatAkademik]' and KodeID", KodeID, "Nama, Gelar");
    $pa = "$dosenpa[Nama] <sup>$dosenpa[Gelar]</sup>";
  } 
    
  echo <<<ESD
  <table id='example' class='table table-sm table-striped' style='width:50%'>
  <form name='frmMhsw' action='?' method=POST>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='lungo' value='' />
  
  <tr><td class=wrn width=2 rowspan=4></td>
      <td class=inp width=80>NIM/NPM:</td>
      <td class=ul width=200><b>$MhswID</b></td>
      <td class=inp width=80>Mahasiswa:</td>
      <td class=ul>$mhsw[Nama]&nbsp;</td>
      </tr>
  <tr><td class=inp>Status Mhsw:</td>
      <td class=ul>$status <sup>$stawal</sup></td>
      <td class=inp>Dosen PA:</td>
      <td class=ul>$pa</td>
  </form>
  </table>
ESD;
}
function DftrMK($MhswID, $mhsw) {	
  $s = "select k.*
    from krs k
      left outer join khs h on h.KHSID = k.KHSID and h.KodeID = '".KodeID."'
    where k.MhswID = '$MhswID'
    order by k.TahunID, k.MKKode";
  $r = mysqli_query($s); $_tahun = 'alksdjfasdf-asdf';
  echo <<<ESD
  <table id='example' class='table table-sm table-striped' style='width:50%'>
ESD;
  $hdr = "<tr style='background:purple;color:white'><th class=ttl width=20>Nmr</th>
    <th class=ttl width=80>Kode</th>
    <th class=ttl>Matakuliah</th>
    <th class=ttl width=30>SKS</th>
    <th class=ttl width=30>Nilai</th>
    <th class=ttl width=30></th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
    if ($_tahun != $w['TahunID']) {
      $_tahun = $w['TahunID'];
      echo "<tr>
        <td class=ul1 colspan=10>
          <font size=+1>Tahun Akademik :$_tahun</font>
          
        </td></tr>";
      echo $hdr;
      $n = 0;
    }
    $n++;
    if ($w['Setara'] == 'Y') {
      $btnEdit = "<input type=button name='btnEdit' value='»'
        onClick=\"fnEditKonversi(0, '$w[MhswID]', '$w[TahunID]', $w[KRSID])\" />";
    }
    else {
      $btnEdit = "<abbr title='Bukan Konversi'></abbr>";
    }
    echo <<<ESD
    <tr>
      <td class=inp>$n</td>
      <td class=ul>$w[MKKode]</td>
      <td class=ul>$w[Nama]</td>
      <td class=ul align=right>$w[SKS]</td>
      <td class=ul align=center>$w[GradeNilai]</td>
      <td class=ul align=center>
        $btnEdit
        </td>
    </tr>
ESD;
  }
  RandomStringScript();
  echo <<<ESD
  </form>
  </table>
  
  <script>
  function fnEditKonversi(md, mhsw, thn, id) {
      var _rnd = randomString();
      lnk = "$_SESSION[ndelox].edit.php?mhsw="+mhsw+"&md="+md+"&id="+id+"&thn="+thn+"&_rnd="+_rnd;
      win2 = window.open(lnk, "", "width=700, height=500, scrollbars, status");
      if (win2.opener == null) childWindow.opener = self;
  }
  </script>
ESD;
}
?>
