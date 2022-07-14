<?php
error_reporting(0);
$MhswID = GainVariabelx('MhswID');
$mhsw = AmbilFieldx('mhsw', "MhswID = '$MhswID' and KodeID", KodeID, "*");
$tgllhr= date('d-m-Y', strtotime($mhsw['TanggalLahir']));

TitleApps("TRANSKRIP NILAI MAHASISWA");
TampilkanAmbilMhswID($MhswID, $mhsw);

if ($MhswID == '') {
  echo Info("INPUTKAN NIM",
    "Masukkan NIM dari Mahasiswa pindahan.<br />
    Hubungi Administrator untuk informasi lebih lanjut.");
}
// Cek apakah mahasiswanya ketemu atau tidak
elseif (empty($mhsw)) {
  echo PesanError("Error",
    "Mahasiswa dengan NIM: <b>$MhswID</b> tidak ditemukan.<br />
    <hr size=1 color=silver />
    Hubungi Administrator untuk informasi lebih lanjut.");
}
/*
elseif ($mhsw['Keluar'] == 'Y') {
  echo PesanError("Error",
    "Mahasiswa dengan NIM: <b>$MhswID</b> telah keluar/lulus.<br />
    Anda sudah tidak dapat mengubah konversi.
    <hr size=1 color=silver />
    Hubungi Sysadmin untuk informasi lebih lanjut.");
} */
else {
  // Cek apakah punya hak akses terhadap mhsw dari prodi ini?
  if (strpos($_SESSION['_ProdiID'], $mhsw['ProdiID']) === false) {
    echo PesanError("Error",
      "Anda tidak memiliki akses.<br />
      Mahasiswa: <b>$MhswID</b>, Prodi: <b>$mhsw[ProdiID]</b>.<br />
      Hubungi Administrator untuk informasi lebih lanjut.");
  }
  // hak akses oke
  else {
      $lungo = (empty($_REQUEST['lungo']))? 'ListMK' : $_REQUEST['lungo'];
      $lungo($MhswID, $mhsw);
  }
}

function TampilkanAmbilMhswID($MhswID, $mhsw) {
  $stawal = AmbilOneField('statusawal', 'StatusAwalID', $mhsw['StatusAwalID'], 'Nama');
  $status = AmbilOneField('statusmhsw', 'StatusMhswID', $mhsw['StatusMhswID'], 'Nama');
  if (empty($mhsw['PenasehatAkademik'])) {
    $pa = '<sup>Belum diset</sup>';
  }
  else {
    $dosenpa = AmbilFieldx('dosen', "Login='$mhsw[PenasehatAkademik]' and KodeID", KodeID, "Nama, Gelar");
    $pa = "$dosenpa[Nama], $dosenpa[Gelar]";
  } 
    
  echo <<<ESD
 
  <form name='frmMhsw' action='?' method=POST>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='lungo' value='' />
   <div class='card'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:50%' align='center'>
  <tr>
      <td align='center'>
        <input style='height:30px' type=text name='MhswID' value='$MhswID' placeholder='NIM' size=15 maxlength=15 />
        <input  class='btn btn-success btn-sm' type=submit name='btnCari' value='Lihat' />
         <input  class='btn btn-primary btn-sm' type=button name='btnSKSLulus' value='Print SKS Lulus'
        onClick="fnCetakSKSLulus('$MhswID')" />
	    <input  class='btn btn-danger btn-sm' type=button name='btnSKSTidakLulus' value='Print SKS Tidak Lulus'
        onClick="fnCetakSKSTidakLulus('$MhswID')" />
        </td>
    </tr>
    </table>
</div>
</div>
 
   <div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:60%' align='center'>
  
  <tr> 
      <th  width=180>Nama Mahasiswa</th>
      <th width=200>: $mhsw[Nama]</th>
      <th>Status </th>
      <th>: $status <sup>$stawal</sup></th>
   </tr>
   
  <tr>
      <th width=180>NIM</th>
      <th>: $mhsw[MhswID]</th>
      <th>Pembimbing Akademik</th>
      <th>: $pa</th>
  </tr>
  
    <tr>
      <th width=180>Program/Prodi</th>
      <th>: $mhsw[ProgramID] - $mhsw[ProdiID]</th>
      <th>Tempat & Tgl Lahir</th>
      <th>:  $mhsw[TempatLahir], $mhsw[TanggalLahir]</th>
  </tr>

  </form>
  </table>
   </div>
</div>
</div>

  <script>
	  function fnCetakSKSLulus(MhswID)
	  {	var _rnd = randomString();
        lnk = "$_SESSION[ndelox].skslulus.php?MhswID="+MhswID+"&_rnd="+_rnd;
        win2 = window.open(lnk, "", "width=700, height=500, scrollbars");
        if (win2.opener == null) childWindow.opener = self;
	  }
	  function fnCetakSKSTidakLulus(MhswID)
      {	var _rnd = randomString();
        lnk = "$_SESSION[ndelox].skstidaklulus.php?MhswID="+MhswID+"&_rnd="+_rnd;
        win2 = window.open(lnk, "", "width=700, height=500, scrollbars");
        if (win2.opener == null) childWindow.opener = self;
	  }
  </script>
ESD;
  RandomStringScript();
}
function ListMK($MhswID, $mhsw) {
    global $koneksi;
  $s = "select k.*
    from krs k
      left outer join khs h on h.KHSID = k.KHSID and h.KodeID = '".KodeID."'
    where k.MhswID = '$MhswID'
    order by k.TahunID, k.MKKode";
  $r = mysqli_query($koneksi, $s); $_tahun = 'alksdjfasdf-asdf';
  echo <<<ESD
     <div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:70%' align='center'>
ESD;
  $hdr = "<tr style='background:purple;color:white'>
    <th class=ttl width=20>No</th>
    <th class=ttl width=80>Kode</th>
    <th class=ttl>Matakuliah</th>
    <th class=ttl width=30>SKS</th>
    <th class=ttl width=30>Nilai</th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
    if ($_tahun != $w['TahunID']) {
      $_tahun = $w['TahunID'];
      echo "<tr>
        <td class=ul1 colspan=10>
          <font size=+1><b>$_tahun</b></font>
        </td></tr>";
      echo $hdr;
      $n = 0;
    }
    $n++;
    
	$GradeNilai = ($w['Final'] == 'Y')? $w['GradeNilai'] : '*'; 
	
    echo <<<ESD
    <tr>
      <td class=inp>$n</td>
      <td class=ul>$w[MKKode]</td>
      <td class=ul>$w[Nama]</td>
      <td class=ul align=right>$w[SKS]</td>
      <td class=ul align=center>$GradeNilai</td>
    </tr>
ESD;
  }
  echo <<<ESD
  </form>
  </table>
     </div>
</div>
</div>
ESD;
}

?>
