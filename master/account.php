<?php

function AccountList() {
  global $koneksi;
  
  $s = "select *
    from rekening
    where KodeID='SISFO' ";
  $r = mysqli_query($koneksi, $s);
  $nomer = 0;
  $link = "<tr><td class=ul colspan=5>
    <input class='btn btn-success btn-xs' type=button name='TambahRek' value='Tambah Rekening'
      onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=AccountEdit&md=1'\" />
    <input class='btn btn-danger btn-xs' type=button name='Refresh' value='Refresh Data'
      onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />
    </td></tr>";
  echo "<p><div class='card'>
  <div class='card-header'>
        <div class='table-responsive'>
        <table id='example' class='table table-sm table-striped'>
    $link
    <tr style='background:purple;color:white'> <th class=ttl>No.</th>
    <th class=ttl>No. Rekening</th>
    <th class=ttl>Nama</th>
    <th class=ttl>Bank</th>
	<th class=ttl>Cabang</th>
    <th class=ttl>NA</th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
    $nomer++;
    if ($w['NA']=='N'){
      $stat="<i style='color:green' class='fa fa-eye'></i>";
    }else{
      $stat="<i style='color:red' class='fa fa-eye-slash'></i>";
    }
    $c = ($w['NA'] == 'Y')? 'class=nac' : 'class=ul';
    echo "<tr><td class=inp width=30>$nomer</td>
      <td $c nowrap>
        <input type=button name='Edit' value='Edit'
          onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=AccountEdit&md=0&rekid=$w[RekeningID]'\" />
        $w[RekeningID]</a></td>
      <td $c nowrap>$w[Nama]</td>
      <td $c nowrap>$w[Bank]</td>
	  <td $c nowrap>$w[Cabang]</td>
      <td $c align=center width=20>$stat</td>
      </tr>";
  }
  echo "</table>
  </div>
</div>
</div></p>";
}
function AccountEdit() {
  global $koneksi;
  $md = $_REQUEST['md']+0;
  if ($md == 0) {
    $rekid = $_REQUEST['rekid'];
    $w = AmbilFieldx('rekening', 'RekeningID', $rekid, '*');
    $jdl = "Edit Rekening";
    $norek = "<input type=hidden name='RekeningID' value='$w[RekeningID]'><b>$w[RekeningID]</b>";
  }
  else {
    $w = array();
    $w['RekeningID'] = '';
    $w['Nama'] = '';
    $w['Bank'] = '';
    $w['NA'] = 'N';
    $jdl = "Tambah Rekening";
    $norek = "<input type=text name='RekeningID' value='$w[RekeningID]' size=50 maxlength=50>";
  }
  $na = ($w['NA'] == 'Y')? 'checked' : '';
  CheckFormScript("RekeningID,Nama,Bank");
  // Tampilkan
  echo "<p>
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:50%'>
  <form action='?' method=POST name='rekening' onSubmit='return CheckForm(this)' />
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='lungo' value='AccountSave' />
  <input type=hidden name='md' value='$md' />
  <input type=hidden name='BypassMenu' value='1' />
  
  <tr><th class=ttl colspan=2>$jdl</th></tr>
  <tr><td class=inp nowrap>Nomer Rekening:</td>
      <td class=ul1>$norek</td></tr>
  <tr><td class=inp>Nama:</td>
      <td class=ul1><input type=text name='Nama' value='$w[Nama]' size=50 maxlength=50></td></tr>
  <tr><td class=inp>Nama Bank:</td>
      <td class=ul><input type=text name='Bank' value='$w[Bank]' size=50 maxlength=50></td></tr>
  <tr><td class=inp>Lokasi Cabang:</td>
      <td class=ul><input type=text name='Cabang' value='$w[Cabang]' size=50 maxlength=100></td></tr>
  <tr><td class=inp>Tidak aktif?</td>
      <td class=ul><input type=checkbox name='NA' value='Y' $na></td></tr>
  <tr><td class=ul colspan=2 align=center>
      <input type=submit name='Simpan' value='Simpan' />
      <input type=reset name='Reset' value='Reset' />
      <input type=button name='Batal' value='Batal' onClick=\"location='?ndelox=$_SESSION[ndelox]'\" />
      </td></tr>
  </form>
  </table>
  </div>
</div>
</div></p>";
}
function AccountSave() {
  global $koneksi;
  $md = $_REQUEST['md']+0;
  $RekeningID = $_REQUEST['RekeningID'];
  $Nama = sqling($_REQUEST['Nama']);
  $Bank = sqling($_REQUEST['Bank']);
  $Cabang = sqling($_REQUEST['Cabang']);
  $NA = empty($_REQUEST['NA'])? 'N' : $_REQUEST['NA'];
  if ($md == 0) {
    $s = "update rekening set Nama='$Nama', Bank='$Bank', Cabang='$Cabang', NA='$NA'
      where RekeningID='$RekeningID' ";
    $r = mysqli_query($koneksi, $s);
  }
  else {
    $ada = AmbilFieldx('rekening', 'RekeningID', $RekeningID, '*');
    if (empty($ada)) {
      $s = "insert into rekening (RekeningID, KodeID, Nama, Bank, Cabang, NA)
        values('$RekeningID', '$_SESSION[KodeID]', '$Nama', '$Bank', '$Cabang', '$NA')";
      $r = mysqli_query($koneksi, $s);
    }
    else echo PesanError('Rekening Tidak Dapat Disimpan',
      "<p>Nomer rekening sudah ada. Berikut adalah data rekening tersebut:</p>
      <p><table class=box cellspacing=1 cellpadding=4>
      <tr><td class=inp1>Nomer Rekening</td><td class=ul>$ada[RekeningID]</td></tr>
      <tr><td class=inp1>Kode Institusi</td><td class=ul>$ada[KodeID]</td></tr>
      <tr><td class=inp1>Nama Pemilik</td><td class=ul>$ada[Nama]</td></tr>
      <tr><td class=inp1>Nama Bank</td><td class=ul>$ada[Bank]</td></tr>
      <tr><td class=inp1>Tidak aktif?</td><td class=ul><img src='img/book$ada[NA].gif'></td></tr>
      </table></p>");
  }
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]", 10);
}

$lungo = (empty($_REQUEST['lungo']))? 'AccountList' : $_REQUEST['lungo'];

TitleApps("REKENING $arrID[Nama]");
$lungo();
?>
