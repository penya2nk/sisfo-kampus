<?php
error_reporting(0);
function DftrBipotNama() {
  global $ndelox, $tok;
  $ki = AmbilBipot(1);
  $ka = AmbilBipot(-1);
  // Tampilkan
  echo "<p align=center><a href='?sub=BipotNamaEdt&md=1&ndelox=$ndelox&tok=$tok'>Tambahkan Nama Biaya/Potongan</a>";
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>
  <tr><td width=50% valign=top>$ki</td>
  <td valign=top>$ka</td></tr>
  </table></div>
  </div>
  </div></p>";
  echo CatatanBipotNama();
}
function AmbilBipot($TrxID=1) {
  global $ndelox, $tok, $koneksi;
  $s = "select bn.*, format(bn.DefJumlah, 0) as DEFJML, format(bn.DefBesar, 0) as DEFBSR, r.Nama as REK
    from bipotnama bn
    left outer join rekening r on bn.RekeningID=r.RekeningID
    where bn.TrxID=$TrxID and bn.KodeID='$_SESSION[KodeID]'
    order by bn.TrxID, bn.Urutan, bn.Nama";
  $r = mysqli_query($koneksi, $s);
  $Jdl = AmbilOneField('trx', "TrxID", $TrxID, 'Nama');
  
  $n = 0;
  $a = "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>
    <tr><td class=ul1 colspan=8><b>$Jdl</b></td></tr>
    <tr style='background:purple;color:white'>
    <th class=ttl title='Nomer Urut dlm BPM' colspan=2>Urutan</th>
    <th class=ttl>Nama</th>
    <th class=ttl>Rekening</th>
    <th class=ttl>NA</th></tr>";
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    if ($w['NA']=='N'){
      $stat="<i style='color:purple' class='fa fa-eye'></i>";
    }else{
      $stat="<i style='color:red' class='fa fa-eye-slash'></i>";
    }
    $c = ($w['NA'] == 'Y')? 'class=nac' : 'class=ul';
    $a .= "<tr><td class=inp width=20>$w[Urutan]</td>
      <td $c width=10><a href='?ndelox=$ndelox&tok=$tok&sub=BipotNamaEdt&md=0&bnid=$w[BIPOTNamaID]'><i class='fa fa-edit'></a></td>
      <td $c>$w[Nama]</td>
      <td $c>$w[RekeningID]&nbsp;</td>
      <td $c align=center width=10>$stat</td>
      </tr>";
  }
  return "$a</table>
  </div>
  </div>
  </div></p>";
}
function BipotNamaEdt() {
  global $ndelox, $tok;

  $md = $_REQUEST['md']+0;
  if ($md == 0) {
    $w = AmbilFieldx('bipotnama', 'BIPOTNamaID', $_REQUEST['bnid'], '*');
    $Jdl = "Edit Nama Biaya dan Potongan";
  }
  else {
    $w = array();
    $w['BIPOTNamaID'] = 0;
    $w['Urutan'] = 0;
    $w['Nama'] = '';
    $w['RekeningID'] = '';
    $w['TrxID'] = 1;
    $w['Baris'] = 0;
	  $w['DipotongBeasiswa'] = 'N';
    $w['Catatan'] = '';
    $w['NA'] = 'N';
    $Jdl = "Tambah Nama Biaya & Potongan";
  }
  $NA = ($w['NA'] == 'Y')? 'checked' : '';
  $Detil = ($w['Detil'] == 'Y')? 'checked' : '';
  $Denda = ($w['KenaDenda'] == 'Y')? 'checked' : '';
  $DipotongBeasiswa = ($w['DipotongBeasiswa'] == 'Y')? 'checked' : '';
  $opttrx = AmbilCombo2('trx', "Concat(TrxID, '. ', Nama)", 'TrxID, Nama', $w['TrxID'], '', 'TrxID');
  $optrek = AmbilCombo2('rekening', "concat(RekeningID, ' - ', Nama)",
    'RekeningID', $w['RekeningID'], "KodeID='$_SESSION[KodeID]'", 'RekeningID');
  // Tampilkan
  CheckFormScript("Nama,TrxID");
  echo "<p>
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>
  <form action='?' method=POST onSubmit=\"return CheckForm(this)\">
  <input type=hidden name='ndelox' value='$ndelox'>
  <input type=hidden name='tok' value='$tok'>
  <input type=hidden name='sub' value='BipotNamaSav'>
  <input type=hidden name='md' value='$md'>
  <input type=hidden name='bnid' value='$w[BIPOTNamaID]'>
  <tr><th class=ttl colspan=2>$Jdl</th></tr>
  <tr><td class=inp>Urutan</td><td class=ul><input type=text name='Urutan' value='$w[Urutan]' size=3 maxlength=3> <font color=red>*)</font></td></tr>
  <tr><td class=inp>Nama</td><td class=ul><input type=text name='Nama' value='$w[Nama]' size=40 maxlength=50></td></tr>
  <tr><td class=inp>Masukkan ke rekening</td><td class=ul><select name='RekeningID'>$optrek</select></td></tr>
  <tr><td class=inp>Jenis Transaksi</td><td class=ul><select name='TrxID'>$opttrx</select></td></tr>
  <tr><td class=inp>Di baris</td>
    <td class=ul><input type=text name='Baris' value='$w[Baris]' size=3 maxlength=3> Pada baris ke berapa dalam cetakan.</td></tr>
  <tr><td class=inp>Dipotong Beasiswa</td>
    <td class=ul><input type=checkbox name='DipotongBeasiswa' value='Y' $DipotongBeasiswa> Apakah dapat dipotong beasiswa?</td></tr>
  <tr><td class=inp>Tidak aktif (NA)?</td><td class=ul><input type=checkbox name='NA' value='Y' $NA></td></tr>
  <tr><td class=inp>Catatan</td><td class=ul><textarea name='Catatan' cols=30 rows=2>$w[Catatan]</textarea></td></tr>
  <tr><td colspan=2><input class='btn btn-primary btn-sm' type=submit name='Simpan' value='Simpan'>
    <input class='btn btn-danger btn-sm' type=reset name='Reset' value='Reset'>
    <input class='btn btn-warning btn-sm' type=button name='Batal' value='Batal' onClick=\"location='?ndelox=$ndelox&tok=$tok&sub='\"></td></tr>
  </form></table>
  </div>
</div>
</div>
  </p>";
  echo CatatanBipotNama();
}
function BipotNamaSav() {
  global $koneksi;
  $md = $_REQUEST['md']+0;
  $Urutan = $_REQUEST['Urutan']+0;
  $Nama = sqling($_REQUEST['Nama']);
  $RekeningID = $_REQUEST['RekeningID'];
  $TrxID = $_REQUEST['TrxID']+0;
  $Baris = $_REQUEST['Baris']+0;
  $Detil = (empty($_REQUEST['Detil']))? 'N' : $_REQUEST['Detil'];
  $KenaDenda = (empty($_REQUEST['KenaDenda']))? 'N' : $_REQUEST['KenaDenda'];
  $DefJumlah = $_REQUEST['DefJumlah']+0;
  $DefBesar = $_REQUEST['DefBesar']+0;
  $Catatan = sqling($_REQUEST['Catatan']);
  $NA = (empty($_REQUEST['NA']))? 'N' : $_REQUEST['NA'];
  $DipotongBeasiswa = (empty($_REQUEST['DipotongBeasiswa']))? 'N' : $_REQUEST['DipotongBeasiswa'];
  // Simpan
  if ($md == 0) {
    $s = "update bipotnama set Urutan='$Urutan', Nama='$Nama', RekeningID='$RekeningID',
	    DefJumlah='$DefJumlah', DefBesar='$DefBesar',
      TrxID='$TrxID', Baris=$Baris,
      DipotongBeasiswa='$DipotongBeasiswa', 
      Catatan='$Catatan', NA='$NA',
      LoginEdit='$_SESSION[_Login]', TglEdit=now()
      where BipotNamaID='$_REQUEST[bnid]' ";
  }
  else {
    $s = "insert into bipotnama (Urutan, Nama, RekeningID, KodeID, 
	    Baris,
	    DipotongBeasiswa,
	    TrxID, Catatan, NA, TglBuat, LoginBuat)
      values ('$Urutan', '$Nama', '$RekeningID', '".KodeID."', 
	    $Baris,
	    '$DipotongBeasiswa',
	    '$TrxID', '$Catatan', '$NA',
      now(), '$_SESSION[_Login]')";
  }
  $r = mysqli_query($koneksi, $s);
  
  DftrBipotNama();
}

function CatatanBipotNama() {
  return "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>
  <tr><td class=ul1 colspan=2>Catatan:</td></tr>
  <tr><td class=ul><b>Urutan</b>
    <td class=ul>Tampilan urutan dalam pencetakan BPM (Bukti Pembayaran Mahasiswa).<br />
    Jika ada 2 atau lebih biaya/potongan mahasiswa yang memiliki nomer urut sama,
    maka pada pencetakan BPM biaya/potongan tersebut akan dijumlahkan.</td></tr>
  </table></div>
  </div>
  </div></p>";
}
?>
