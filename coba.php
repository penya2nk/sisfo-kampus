<?php 


//Cara 1 ----------------------------------------------------------------------
// if (empty($_POST['lungo'])){
//   $lungo = ListKuliah;  
// }else{
//   $lungo = $_POST['lungo']; 
// } 
// $lungo();

//Cara 2 ----------------------------------------------------------------------
$lungo = (empty($_REQUEST['lungo']))? 'ListKuliah' : $_REQUEST['lungo'];
$lungo();

//Cara 3 ----------------------------------------------------------------------
// $lungo = (empty($_POST['lungo']))? 'ListKuliah' : $_POST['lungo'];
// $lungo();


function ListKuliah() {
    echo"Daftar Barang<br>";
    global $koneksi;
    echo"<a href='?ndelox=$_SESSION[ndelox]&lungo=BarangEdit&md=1'>Tambah Data</a>";
    echo"<table border='1' width='40%'>";
    $sql =mysqli_query($koneksi, "select * from t_barang order by BarangID DESC limit 50");
    while ($data=mysqli_fetch_array($sql)){
        $no++;
        echo"<tr>
        <td>$no</td>
        <td>$data[BarangID]</td>
        <td>$data[Nama]</td>
        <td>$data[Satuan]</td>
        <td>
         <a href='?ndelox=$_SESSION[ndelox]&lungo=BarangEdit&md=0&id=$data[BarangID]'>Edit</a>
         <td><a href='?ndelox=$_SESSION[ndelox]&lungo=BarangDel&id=$data[BarangID]'>Del</td>
        </td>
        </tr>";
    }
    echo"</table>";
}

function BarangEdit() {
  global $koneksi;    
  $md = $_GET['md'] +0;
  if ($md == 0) {
    //$data = AmbilFieldx('t_barang', 'BarangID', $_GET['id'], '*');
    $data   = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_barang where BarangID='".strfilter($_GET['id'])."'"));   
    $jdl    = 'Edit Data';
    $id     = "<input type=hidden name='BarangID' value='$data[BarangID]'><b>$data[BarangID]</b>";
  }
  else {
    $jdl    = 'Tambah Data';
    $id     = "<input type=text name='BarangID' size=20 maxlength=20>";
  }

  echo "
  <form action='?' method=POST onSubmit='return CheckForm(this)'>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]'>
  <input type=hidden name='lungo' value='BarangSimpan'>
  <input type=hidden name='md' value='$md'>
  <input type=hidden name='BypassMenu' value='1' />
  <table>
  <tr><th class=ttl colspan=2>$jdl</th></tr>
  
  <tr>
  <td class=inp>Barang ID</td>
  <td class=ul>$id</td>
  </tr>
  
  <tr>
      <td class=inp>Nama</td>
      <td class=ul><input type=text name='Nama' value='$data[Nama]' size=30 maxlength=50></td>
  </tr>
  
    <tr>
      <td class=inp>Satuan</td>
      <td class=ul><input type=text name='Satuan' value='$data[Satuan]' size=30 maxlength=50></td>
  </tr>
  
  <tr>
    <td colspan=2 align=center><input type=submit name='Simpan' value='Simpan'>
    <input type=reset name='Reset' value='Reset'>
    <input type=button name='Batal' value='Batal' onClick=\"location='?ndelox=$_SESSION[ndelox]'\"></td>
  </tr>
  
  </form>
  </table>";
}


function BarangSimpan() {
  global $koneksi;
  $md = $_POST['md'] +0;
  if ($md == 0) {
    $s = "update t_barang set Nama ='".strfilter($_POST['Nama'])."', 
          Satuan ='".strfilter($_POST['Satuan'])."'
          where BarangID ='".strfilter($_POST['BarangID'])."'";
    mysqli_query($koneksi, $s);
  }
  else {
    //$ada = AmbilFieldx('t_barang', 'BarangID', $_POST['BarangID'], '*');
    $ada = AmbilFieldx('t_barang', 'BarangID', $_POST['BarangID'], '*');
    if (empty($ada)) {
      $s = "insert into t_barang(BarangID, Nama, Satuan)
            values('".strfilter($_POST['BarangID'])."', '".strfilter($_POST['Nama'])."', '".strfilter($_POST['Satuan'])."')";
      mysqli_query($koneksi, $s);
    }
    else echo PesanError('Kesalahan',
      "Kode <b>$BarangID</b> sudah ada.");
  }
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]", 10);
}

function BarangDel() {
  global $koneksi;
  $s = "delete from t_barang where BarangID='".strfilter($_GET['id'])."' ";
  $r = mysqli_query($koneksi, $s);
  ListKuliah();
}


?>