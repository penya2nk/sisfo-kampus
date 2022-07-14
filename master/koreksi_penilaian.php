<?php
$MhswID = GainVariabelx('MhswID');
$mhsw = AmbilFieldx("mhsw m
      left outer join dosen d on m.PenasehatAkademik = d.Login and d.KodeID='".KodeID."'
      left outer join prodi prd on prd.ProdiID = m.ProdiID and prd.KodeID='".KodeID."'
      left outer join program prg on prg.ProgramID = m.ProgramID and prg.KodeID='".KodeID."'
      ",
      "m.KodeID='".KodeID."' and m.MhswID", $MhswID,
      "m.*, prd.Nama as _PRD, prg.Nama as _PRG,
      d.Nama as DSN, d.Gelar");

TitleApps("KOREKSI PENILAIAN");
TampilkanHeaderMhsw($MhswID, $mhsw);
$lungo = (empty($_REQUEST['lungo']))? "EditNilaiMhsw" : $_REQUEST['lungo'];
if (!empty($mhsw)) $lungo($MhswID, $mhsw);


function TampilkanHeaderMhsw($MhswID, $w) {
  echo <<<ESD
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:50%' align='center'>
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='lungo' value='' />
  <tr><th class=inp width=120>NIM</th>
      <th class=ul width=240>
        <input type=text name='MhswID' value='$_SESSION[MhswID]' size=12 maxlength=20 />
        <input style='margin-top:-5px' class='btn btn-success btn-xs' type=submit name='Ambil' value='Lihat Data' />
        </td>
      <th class=inp>Program Studi</th>
      <th class=ul>: $w[_PRD] - $w[ProdiID]</th>
      </tr>
  <tr>
   <th class=inp width=130>Mahasiswa</th>
      <th class=ul><b>: $w[Nama]</b></th>
 
      <th class=inp>Program</th>
      <th class=ul>: $w[_PRG] - $w[ProgramID]</th>
      </tr>
  <tr>
    <th class=inp>Masa Studi</th>
      <th class=ul>: $w[TahunID] &#8594; $w[BatasStudi]</td>
  <th class=inp>Dosen PA</th>
      <th class=ul>: $w[DSN] $w[Gelar]</th>
    
      </tr>
  </form>
  </table>
  </div>
</div>
</div>
ESD;
}

function EditNilaiMhsw($MhswID, $mhsw) {
	global $koneksi;
  $s = "select k.*,
      @KOR := (select count(kn.KoreksiNilaiID)
      from koreksinilai kn
      where kn.KRSID = k.KRSID),
      if (@KOR = 0, '&nbsp;', concat(@KOR, '&times;')) as JML
    from krs k
    where k.KodeID = '".KodeID."'
      and k.MhswID = '$MhswID'
	  
	  and k.NA='N'
    order by k.TahunID, k.MKKode";
  $r = mysqli_query($koneksi, $s);
  $n = 0; $_thn = 'laskdfj'; $sks = 0;
  $hdr = "<tr style='background:purple;color:white'>
    <th class=ttl width=30>#</th>
    <th class=ttl width=80>Kode</th>
    <th class=ttl>Matakuliah</th>
    <th class=ttl width=20>SKS</th>
    <th class=ttl width=20>Nilai</th>
    <th class=ttl width=30>Koreksi</th>
    </tr>";
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table  id='example' class='table table-sm table-striped' style='width:100%' align='center'>";
  while ($w = mysqli_fetch_array($r)) {
    if ($_thn != $w['TahunID']) {
      $_thn = $w['TahunID'];
      echo "<tr>
        <td class=ul1 colspan=10>Tahun Akademik: <font size=+1><b>$w[TahunID]</b></font></td>
        </tr>";
      echo $hdr;
      $n = 0;
    }
    $n++;
    // Detail
      $c = 'class=ul';
      $sks += $w['SKS'];
    if ($w['BobotNilai'] == 0) {
      $Nilai = '&times;';
    }
    else {
      $Nilai = "$w[GradeNilai] ";
    }
    echo <<<ESD
    <tr><td class=inp>$n</td>
        <td $c>$w[MKKode]</td>
        <td $c>$w[Nama]</td>
        <td $c align=right>$w[SKS]</td>
        <td $c align=center>$Nilai</td>
        <td $c >
          $w[JML]&nbsp;
          <a href='#' onClick="javascript:Edit($w[KRSID])"><i class='fa fa-edit'></i></a>
          </td>
        </tr>
ESD;
  }
  echo <<<ESD
  <tr>
    <td class=ul colspan=3 align=right>Total SKS:</td>
    <td class=ul align=right><font size=+1>$sks</font></td>
    </tr>
  </table>
  </div>
</div>
</div>
  
  <script>
  <!--
  function Edit(krsid) {
    lnk = "$_SESSION[ndelox].edit.php?KRSID="+krsid;
    win2 = window.open(lnk, "", "width=500, height=500, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  //-->
  </script>
ESD;
}

?>
