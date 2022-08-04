<?php
session_start();
include_once "../academic_sisfo1.php";

ViewHeaderApps("Presensi Mahasiswa", 1);

global $koneksi;

$id = $_REQUEST['id']+0;
$st = $_REQUEST['st'];
$nilai = AmbilOneField('jenispresensi', 'JenisPresensiID', $st, 'Nilai')+0;

$pm = AmbilFieldx('presensimhsw', 'PresensiMhswID', $id, '*');
// Update presensinya
$s = "update presensimhsw set JenisPresensiID = '$st', Nilai = $nilai
  where PresensiMhswID = '$id' ";
$r = mysqli_query($koneksi, $s);
// Hitung & update ke KRS
$jml = AmbilOneField('presensimhsw', 'KRSID', $pm['KRSID'], "sum(Nilai)")+0;
// Update KRS
$s = "update krs
  set _Presensi = $jml
  where KRSID = $pm[KRSID]";
$r = mysqli_query($koneksi, $s);
?>
<script>window.close()</script>
