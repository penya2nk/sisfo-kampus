<?php
//error_reporting(0);
session_start();
include_once "../academic_sisfo1.php";
ViewHeaderApps("PROSES BIAYA DAN POTONGAN");

include_once "../finance/pembayaran_mhs.lib.php";

$lungo = (empty($_REQUEST['lungo']))? 'Prosesnya' : $_REQUEST['lungo'];
$lungo();

function Prosesnya() {
    global $koneksi;
  $max = $_SESSION['_bptMax']+0;
  $max = ($max == 0)? 10 : $max;
  $page = $_SESSION['_bptPage']+0;
  
  $mulai = $max * $page;
  
  $s = "select h.*, m.Nama as NamaMhsw
    from khs h
      left outer join mhsw m on m.MhswID = h.MhswID and m.KodeID = '".KodeID."'
    where h.KodeID = '".KodeID."'
      and h.TahunID = '$_SESSION[TahunID]'
    order by h.MhswID
    limit $mulai, $max";
  $r = mysqli_query($koneksi, $s);
  $jml = mysqli_num_rows($r);
  if ($jml > 0) {
    while ($w = mysqli_fetch_array($r)) {
      $_SESSION['_bptCounter']++;
      $jml = ProsesBiayaPotongan($w['MhswID'], $w['TahunID'])+0;
      $_jml = number_format($jml);
      echo "
      <script>
      parent.fnProgress($_SESSION[_bptCounter], '$w[MhswID]', '$w[NamaMhsw]', '$_jml');
      </script>";
    }
    $_SESSION['_bptPage']++;
    $tmr = 1;
    echo <<<ESD
    <script>
    window.onload=setTimeout("window.location='../$_SESSION[ndelox].go.php'", $tmr);
    </script>
ESD;
  }
  else {
    echo <<<ESD
    <script>
    parent.fnSelesai('$_SESSION[TahunID]', $_SESSION[_bptCounter]);
    </script>
ESD;
  }
}
function ProsesBiayaPotongan($MhswID, $TahunID) {
global $koneksi;
  $mhsw = AmbilFieldx('mhsw', "KodeID='".KodeID."' and MhswID", $MhswID, "*");
  $khs = AmbilFieldx('khs', "KodeID = '".KodeID."' and TahunID = '$TahunID' and MhswID", $MhswID, "*");
  // Ambil BIPOT-nya
  $s = "select * 
    from bipot2 
    where BIPOTID = '$mhsw[BIPOTID]'
      and Otomatis = 'Y'
      and NA = 'N'
    order by TrxID, Prioritas";
  $r = mysqli_query($koneksi, $s);
  while ($w = mysqli_fetch_array($r)) {
    $oke = true;
    // Apakah sesuai dengan status awalnya?
    $pos = strpos($w['StatusAwalID'], ".".$mhsw['StatusAwalID'].".");
    $oke = $oke && !($pos === false);

	// Apakah sesuai dengan status mahasiswanya?
    $pos = strpos($w['StatusMhswID'], ".".$khs['StatusMhswID'].".");
    $oke = $oke && !($pos === false);
	
    // Apakah grade-nya?
    if ($oke) {
      if ($w['GunakanGradeNilai'] == 'Y') {
        $pos = strpos($w['GradeNilai'], ".".$mhsw['GradeNilai'].".");
        $oke = $oke && !($pos === false);
      }
    }
	
	// Apakah Jumlah SKS Tahun ini mencukupi?
	if ($oke) {
	  if ($w['GunakanGradeIPK'] == 'Y') {
		if($khs['SKS'] < AmbilOneField('gradeipk', "IPKMin <= $mhsw[IPK] and $mhsw[IPK] <= IPKMax and KodeID", KodeID, 'SKSMin')) $oke = false;
		else $oke = true;
	  }
	}
	
	// Apakah Grade IPK-nya OK?
	if ($oke) {
      if ($w['GunakanGradeIPK'] == 'Y') {
        $pos = strpos($w['GradeIPK'], ".".AmbilOneField('gradeipk', "IPKMin <= $mhsw[IPK] and $mhsw[IPK] <= IPKMax and KodeID", KodeID, 'GradeIPK').".");
        $oke = $oke && !($pos === false);
      }
    }
    
	// Apakah dimulai pada sesi ini?
    if ($oke) {
      if ($w['MulaiSesi'] <= $khs['Sesi'] or $w['MulaiSesi'] == 0) $oke = true;
	  else $oke = false;
    }
	
    // Simpan data
    if ($oke) {
      // Cek, sudah ada atau belum? Kalau sudah, ambil ID-nya
      $ada = AmbilOneField('bipotmhsw',
        "KodeID='".KodeID."' and MhswID = '$mhsw[MhswID]'
        and PMBMhswID = 1
        and TahunID='$khs[TahunID]' and BIPOT2ID",
        $w['BIPOT2ID'], "BIPOTMhswID") +0;
      // Cek apakah memakai script atau tidak?
      if ($w['GunakanScript'] == 'Y') BipotGunakanScript($mhsw, $khs, $w, $ada, 1);
      // Jika tidak perlu pakai script
      else {
        // Jika tidak ada duplikasi, maka akan di-insert. Tapi jika sudah ada, maka dicuekin aja.
        if ($ada == 0) {
          // Simpan
          $Nama = AmbilOneField('bipotnama', 'BIPOTNamaID', $w['BIPOTNamaID'], 'Nama');
          $s1 = "insert into bipotmhsw
            (KodeID, COAID, PMBMhswID, MhswID, TahunID,
            BIPOT2ID, BIPOTNamaID, Nama, TrxID,
            Jumlah, Besar, Dibayar,
            Catatan, NA,
            LoginBuat, TanggalBuat)
            values
            ('".KodeID."', '$w[COAID]', 1, '$mhsw[MhswID]', '$khs[TahunID]',
            '$w[BIPOT2ID]', '$w[BIPOTNamaID]', '$Nama', '$w[TrxID]',
            1, '$w[Jumlah]', 0,
            'Auto', 'N',
            '$_SESSION[_Login]', now())";
          $r1 = mysqli_query($koneksi, $s1);
        }// end $ada=0
      } // end if $ada
    }   // end if $oke
  }     // end while
  $jml = HitungUlangBIPOTMhsw($MhswID, $TahunID);
  return $jml;
}
?>
