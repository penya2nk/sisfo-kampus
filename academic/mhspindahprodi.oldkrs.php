<?php
session_start();
include_once "../academic_sisfo1.php";
ViewHeaderApps("Mhsw Pindahan");

// *** Parameters ***
$MhswID = GainVariabelx('MhswID');
$mhsw = AmbilFieldx('mhsw', "MhswID='$MhswID' and KodeID", KodeID, '*');
if (empty($mhsw))
  die(PesanError('Error',
  "Data mahasiswa lama tidak ditemukan.<br />
  Hubungi Sysadmin untuk informasi lebih lanjut"));

// *** Main ***
$lungo = (empty($_REQUEST['lungo']))? 'DftrKRSLama' : $_REQUEST['lungo'];
$lungo($MhswID, $mhsw);

// *** Functions ***
function DftrKRSLama($MhswID, $mhsw) {
	global $koneksi;
  $s = "select k.*
    from krs k
    where k.MhswID = '$mhsw[PMBID]'
    order by k.TahunID";
  $r = mysqli_query($koneksi, $s);
  
  RandomStringScript();
  echo "<table class=bsc cellspacing=1 width=100%>
    <tr>
        <th class=ttl width=20><abbr title='Konversikan'>Konv.</abbr></th>
        <th class=ttl width=20>#</th>
        <th class=ttl width=80>Kode &rsaquo; SKS</th>
        <th class=ttl>Matakuliah</th>
        </tr>";
  $thn = 'alksdjflasdjfhasd';
  while ($w = mysqli_fetch_array($r)) {
    if ($thn != $w['TahunID']) {
      $thn = $w['TahunID'];
      echo "<tr>
        <td class=ul1 colspan=5><font size=+1>$thn</td>
        </tr>";
    }
    $n++;
    if ($w['StatusKRSID'] == 'K') {
      $c = "class=nac";
      $konv = "<abbr title='Sudah dikonversikan'>&times;</a>";
    }
    else {
      $c = "class=ul1";
      $konv = "<input type=button name='btnKonversi' value='<' onClick=\"javascript:Konversikan($w[KRSID], '$MhswID')\" />";
    }
    echo "<tr>
      <td class=ul align=center>$konv</td>
      <td class=inp>$n</td>
      <td $c>$w[MKKode]<sup>$w[SKS]</sup></td>
      <td $c>$w[Nama]</td>
      </tr>";
  }
  echo <<<ESD
  </table>
  
  <script>
  function Konversikan(krsid, mhswid) {
    _rnd = randomString();
    lnk = "../$_SESSION[ndelox].konversikan.php?KRSID="+krsid+"&MhswID="+mhswid+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=700, height=500, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function RefreshParent() {
    parent.location="../index.php?ndelox=$_SESSION[ndelox]&lungo=fnKonversi&MhswID=$MhswID";
  }
  </script>
  
ESD;
}

?>

</BODY>
</HTML>
