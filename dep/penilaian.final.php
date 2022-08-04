<?php
error_reporting(0);
session_start();
include_once "../academic_sisfo1.php";

ViewHeaderApps("FINALISASI PENILAIAN", 1);

$id = $_REQUEST['id'];
$jdwl = AmbilFieldx('jadwal', 'JadwalID', $id, '*');
if ($jdwl['Final'] == 'Y')
  die(PesanError('Error',
    "Matakuliah sudah difinalisasi."));

TitleApps("FINALISASI PENILAIAN");
$lungo = (empty($_REQUEST['lungo']))? "KonfirmasiFinalisasi" : $_REQUEST['lungo'];
$lungo($jdwl);

// *** Functions ***
function KonfirmasiFinalisasi($jdwl) {
  echo Info("Info Finalisasi",
    "<p>Benar Anda akan memfinalisasi mata kuliah ini?<br />
    Setelah difinalisasi, mata kuliah sudah tidak dapat diubah nilainya.</p>
    
    <p>Cek sekali lagi. Lakukan <b>[Hitung Nilai]</b> untuk menghitung semua nilai mahasiswa.
    Baru setelah itu mata kuliah dapat difinalisasi.</p>

    <hr size=1 color=silver />
    Opsi: <input type=button name='Finalisasi' value='Finalisasi'
      onClick=\"location='../$_SESSION[ndelox].final.php?id=$jdwl[JadwalID]&lungo=Finalisasi'\" />
      <input type=button name='Batal' value='Batalkan' onClick=\"window.close()\" />");
}
function Finalisasi($jdwl) {
    global $koneksi;
  $id = $_REQUEST['id'];
  // finalisasi jadwal
  $s = "update jadwal 
    set Final = 'Y', Gagal = 'N',
        TglEdit = now(), LoginEdit = '$_SESSION[_Login]'
    where JadwalID = $id";
  $r = mysqli_query($koneksi, $s);
  // finalisasi krs
  $s = "update krs
    set Final = 'Y',
        TanggalEdit = now(), LoginEdit = '$_SESSION[_Login]'
    where JadwalID = $id";
  $r = mysqli_query($koneksi, $s);
  
  // finalisasi jadwal uts
  $s = "update jadwaluts set Final = 'Y'
	where JadwalID = $id";
  $r = mysqli_query($koneksi, $s);
  $s = "update jadwaluas set Final = 'Y'
	where JadwalID = $id";
  $r = mysqli_query($koneksi, $s);
  
  // finalisasi jadwal responsi/lab/tutorial tambahan
  $s = "select JadwalID from jadwal where JadwalRefID = '$id' and KodeID='".KodeID."'";
  $r = mysqli_query($koneksi, $s);
  while($w = mysqli_fetch_array($r))
  {	$s1 = "update jadwal set Final = 'Y', Gagal = 'N',
			TglEdit=now(), LoginEdit = '$_SESSION[_Login]'
			where JadwalID='$w[JadwalID]'";
	$r1 = mysqli_query($koneki, $s1);
	
	$s1 = "update krs
    set Final = 'Y',
        TanggalEdit = now(), LoginEdit = '$_SESSION[_Login]'
    where JadwalID = '$w[JadwalID]'";
	$r1 = mysqli_query($koneksi, $s1);
  }
  
  // Kembali
  TutupScript($id);
}
function TutupScript($id) {
echo <<<SCR
<SCRIPT>
  function ttutup() {
    opener.location='../index.php?ndelox=$_SESSION[ndelox]&lungo=Nilai2&_nilaiJadwalID=$id';
    self.close();
    return false;
  }
  ttutup();
</SCRIPT>
SCR;
}
?>
