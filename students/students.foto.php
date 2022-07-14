<?php
$mhswbck = GainVariabelx('mhswbck');

function TampilkanHeader($w) {
  $foto = FileFotoMhsw($w, $w['Foto']);
  echo "<p>
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:50%' align='center'>

  <tr><td class=inp width=180>NIM</td>
      <td class=ul><b>$w[MhswID]</td>
      <td rowspan=7 class=box width=124 style='padding: 2pt' align=center valign=middle>
      <img src='$foto' height=120 /></td>
      </tr>

  <tr><td class=inp>Nama</td>
      <td class=ul><b>$w[Nama]</td></tr>
  <tr><td class=inp>Program</td>
      <td class=ul><b>$w[ProgramID]</td></tr>
  <tr><td class=inp>Program Studi</td>
      <td class=ul><b>$w[ProdiID]</td></tr>
  <tr><td class=inp>File Foto</td>
      <td class=ul>$w[Foto]</td></tr>
  <tr><td class=inp>Pilihan</td>
      <td class=ul>
        <input class='btn btn-success btn-sm' type=button name='Kembali' value='Kembali ke Data Mahasiswa'
          onClick=\"location='?ndelox=students/students.edt&mhswid=$w[MhswID]'\" />
      </td></tr>
  </table>
  </div>
</div>
</div></p>";
}
function TampilkanUploadFoto($w) {
  $MaxFileSize = 100000;
  echo "<p>
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:50%' align='center'>
  <form action='index.php' enctype='multipart/form-data' method=POST>
  <input type=hidden name='MAX_FILE_SIZE' value='$MaxFileSize' />
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]'>
  <input type=hidden name='lungo' value='aplodFoto'>
  <input type=hidden name='mhswid' value='$w[MhswID]'>
  <tr><td class=inp width=100>File Foto</td>
    <td class=ul><input type=file name='foto' size=35></td></tr>
  <tr><td class=ul colspan=2 align=left>
    <input class='btn btn-success btn-sm' type=submit name='Upload' value='Upload File Foto'></td></tr>
  </form></table>
  </div>
</div>
</div>
  </p>";
}
function aplodFoto() {
	global $koneksi;
  $MhswID = $_REQUEST['mhswid'];
  $upf = $_FILES['foto']['tmp_name'];
  $arrNama = explode('.', $_FILES['foto']['name']);
  $tipe = $_FILES['foto']['type'];
  $arrtipe = explode('/', $tipe);
  $extensi = $arrtipe[1];
  $dest = "foto/" . $MhswID . '.' . $extensi;
  //echo $dest;
  if (move_uploaded_file($upf, $dest)) {
    $s = "update mhsw set Foto='$dest' where MhswID='$MhswID' ";
    $r = mysqli_query($koneksi, $s);
  }
  else echo PesanError("Gagal Upload Foto",
    "Tidak dapat meng-upload file foto.<br />
    Periksa file yg di-upload, karena besar file dibatasi cuma: <b>$_REQUEST[MAX_FILE_SIZE]</b> byte.");
  //print_r($_FILES);
}

$lungo = (empty($_REQUEST['lungo']))? 'donothing' : $_REQUEST['lungo'];
$lungo();
$w = AmbilFieldx('mhsw', 'MhswID', $_REQUEST['mhswid'], '*');

TampilkanHeader($w);
TampilkanUploadFoto($w);
?>
