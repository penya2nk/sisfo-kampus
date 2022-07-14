<?php
TitleApps("MASTER PEJABAT");
$lungo = (empty($_REQUEST['lungo']))? 'OfficerList' : $_REQUEST['lungo'];
$lungo();

function OfficerList() {
	global $koneksi;
  PejabatScript();
  $s = "Select p.*
    from pejabat p
    where p.KodeID = '".KodeID."'
    order by p.Urutan";
  $r = mysqli_query($koneksi, $s);
  $n = 0;

  echo "
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>";
  echo "<tr >
    <td class=ul1 colspan=7>
    <input class='btn btn-success btn-sm' type=button name='Refresh' value='Refresh' onClick=\"location='?ndelox=$_SESSION[ndelox]'\" />
    <input class='btn btn-primary btn-sm' type=button name='Tambah' value='Tambah Pejabat'
      onClick=\"javascript:PjbtEdit(1, 0)\" />
    </td>
    </tr>";
  echo "<tr style='background:purple;color:white'>
    <th class=ttl width=20 colspan=2>#</th>
    <th class=ttl width=120>Kode</th>
    <th class=ttl width=200>Jabatan</th>
    <th class=ttl>Nama Pejabat</th>
    <th class=ttl width=80>NIP</th>
    <th class=ttl width=80>TTD</th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    $fn = "ttd/$w[KodeJabatan].ttd.gif";
    $img_ttd = (file_exists($fn))? "<img src='$fn' width=80 height=80 />" : '&times;';
    echo "<tr>
      <td class=inp width=10>$w[Urutan]</td>
      <td class=cna$w[NA] width=10>
        <a href='#' onClick=\"javascript:PjbtEdit(0, $w[PejabatID])\" /><i class='fa fa-edit'></i></a>
        </td>
      <td class=cna$w[NA] width=120>$w[KodeJabatan]&nbsp;</td>
      <td class=cna$w[NA] width=300>$w[Jabatan]&nbsp;</td>
      <td class=cna$w[NA]>$w[Nama]&nbsp;</td>
      <td class=cna$w[NA] width=80>$w[NIP]&nbsp;</td>
      <td class=cna$w[NA] width=120 align=center>$img_ttd<a href='?ndelox=$_SESSION[ndelox]&lungo=GantiTTD&PID=$w[PejabatID]'>Ganti TTD</a></td>
      </tr>";
  }
  echo "</table>
  </div>
</div>
</div>";
}
function GantiTTD() {
	global $koneksi;
  $MaxFileSize = 500000;
  $PID = $_REQUEST['PID'];
  //$w = AmbilFieldx('pejabat', 'PejabatID', $PID, '*');
  $w = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from pejabat where PejabatID='".strfilter($PID)."'"));
  echo <<<ESD
  <p>
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:50%' align='left'>
  <form action='?' enctype='multipart/form-data' method=POST>
  <input type=hidden name='MAX_FILE_SIZE' value='$MaxFileSize' />
  <input type=hidden name='lungo' value='SimpanTandaTangan' />
  <input type=hidden name='PID' value='$PID' />
  <input type=hidden name='BypassMenu' value='1' />
  <input type=hidden name='KodeJabatan' value='$w[KodeJabatan]' />
  
  <tr><td class=inp>Nama:</td>
      <td class=ul>$w[Nama]</td></tr>
  <tr><td class=inp>Jabatan:</td>
      <td class=ul>$w[Jabatan]</td></tr>
  
  <tr><td class=inp width=100>File Foto</td>
    <td class=ul><input type=file name='foto' size=35></td></tr>
  <tr style='background:purple;color:white'><td class=ul colspan=2 align=center>
      File gambar tanda tangan yang bisa diupload hanya yang berformat <b>gif</b>.<br />
      Ukuran gambar maximal: <b>80&times;80px</b>
      </td></tr>
  <tr ><td class=ul colspan=2 align=left>
    <input class='btn btn-success btn-sm' type=submit name='btnUpload' value='Upload File Foto' />
    <input  class='btn btn-primary btn-sm' type=button name='btnBatal' value='Batal' onClick="location='?ndelox=$_SESSION[ndelox]&lungo='" />
    </td></tr>
  </form></table>
  </div>
</div>
</div>
  </p>
ESD;
}
function SimpanTandaTangan() {
	global $koneksi;
  $PID = $_REQUEST['PID'];
  $KodeJabatan = sqling($_REQUEST['KodeJabatan']);
  
  $upf = $_FILES['foto']['tmp_name'];
  $arrNama = explode('.', $_FILES['foto']['name']);
  $tipe = $_FILES['foto']['type'];
  $arrtipe = explode('/', $tipe);
  $extensi = $arrtipe[1];
  if ($extensi != 'gif')
    die(PesanError("Error",
      "File tanda tangan yang bisa diupload hanya yang berformat gif.<br />
      Hubunti Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      <input type=button name='btnKembali' value='Kembali' onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />"));
  $dest = "ttd/" . $KodeJabatan . '.ttd.gif';
  //echo $dest;
  if (move_uploaded_file($upf, $dest)) {
    $_rand = rand();
    SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=&_rnd=".$_rand, 1);
  }
  else echo PesanError("Gagal Upload Foto",
    "Tidak dapat meng-upload file foto.<br />
    Periksa file yg di-upload, karena besar file dibatasi cuma: <b>$_REQUEST[MAX_FILE_SIZE]</b> byte.");
  //print_r($_FILES);
}
function PejabatScript() {
  //width=400, align='center' height=400, scrollbars, status
  //width=550,height=170,left=450,top=200,toolbar=0,status=0
  echo <<<SCR
  <script>
  function PjbtEdit(md, id) {
    lnk = "$_SESSION[ndelox].edit.php?md="+md+"&id="+id;
    win2 = window.open(lnk, "", "width=450,height=250,left=450,top=200,toolbar=0,status=0");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
SCR;
}
?>
