<?php
// Untuk menghitung jumlah peserta kuliah
function HitungPeserta($jdwlid) {
	global $koneksi;
  $jml = AmbilOneField('krs', "StatusKRSID='A' and JadwalID", $jdwlid, "count(KRSID)")+0;
  // Simpan
  $s = "update jadwal set JumlahMhsw = '$jml' where JadwalID = '$jdwlid' ";
  $r = mysqli_query($koneksi, $s);
}

// Menghitung ulang jumlah SKS dan total SKS yang diambil oleh Mhs
function HitungUlangKRS($khsid) {
	global $koneksi;
  $khs = AmbilFieldx('khs', 'KHSID', $khsid, '*');
  $sks = AmbilOneField('krs',
    "TahunID = '$khs[TahunID]' and NA = 'N' and MhswID", $khs['MhswID'],
    "sum(SKS)")+0;
  $totalsks = AmbilOneField('khs', "Sesi <= $khs[Sesi] and MhswID", $khs['MhswID'], "sum(SKS)")+0;
  $s = "update khs
    set SKS = '$sks',
		TotalSKS = '$totalsks'
    where KHSID = '$khsid' ";
  $r = mysqli_query($koneksi, $s);
  
  
  
}


?>
