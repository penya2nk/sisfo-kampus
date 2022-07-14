<?php
error_reporting(0);
session_start();
include_once "../academic_sisfo1.php";
ViewHeaderApps("Proses BIPOT Mhsw");

$Angkatan = GainVariabelx('Angkatan');
$ProdiID = GainVariabelx('ProdiID');

$lungo = (empty($_REQUEST['lungo']))? 'Proses' : $_REQUEST['lungo'];
$lungo();

function Proses() {
	global $koneksi;
  $Jumlah = $_SESSION['_bipotJumlah'];
  $Progress = $_SESSION['_bipotProgress'];
  $BIPOTID = $_SESSION['_bipotBIPOTID'];
  
  if ($Jumlah >= $Progress) {
    $MhswID = $_SESSION['_bipotMhswID_'.$Progress];
    $TahunID = $_SESSION['_bipotTahunID_'.$Progress];
    $ProdiID = $_SESSION['_bipotProdiID_'.$Progress];
    $ProgramID = $_SESSION['_bipotProgramID_'.$Progress];
  
    $s = "update mhsw
      set BIPOTID = '$BIPOTID'
      where KodeID = '".KodeID."'
        and MhswID = '$MhswID'
      limit 1";
    $r = mysqli_query($koneksi, $s);
    $_SESSION['_bipotProgress']++;
    $_p = ($Jumlah > 0)? $Progress/$Jumlah*100 : 0;
    $_s = ($Jumlah > 0)? 100 - ($Progress/$Jumlah*100) : 0;
    // Tampilkan
    TitleApps("Proses Set BIPOT Mhsw");
    echo <<<ESD
    <p align=center>
    <font size=+1>$Progress</font> <sup>&#8594; $Jumlah</sup><br />
    <sup>$MhswID</sup><br />
    <img src='../img/B1.jpg' height=20 width=1 /><img src='../img/B2.jpg' height=20 width=$_p /><img src='../img/B3.jpg' height=20 width=$_s/><img src='../img/B1.jpg' height=20 width=1 />
    </p>
ESD;
    // Next Process
    $tmr = 1;
    echo <<<SCR
    <script>
    window.onload=setTimeout("window.location='../$_SESSION[ndelox].go.php'", $tmr);
    </script>
SCR;
  }
  else {
    TitleApps("Proses Selesai");
    echo <<<SCR
    <p align=center>
    Proses set BIPOT Mahasiswa telah selesai.<br />
    Jumlah data mahasiswa yang telah diset: <font size=+1>$Jumlah</font>.
    </p>
    <hr size=1 color=silver />
    <p align=center>
    <input type=button name='Tutup' value='Tutup'
      onClick="window.close()" />
    </p>
SCR;
  }
}

?>
