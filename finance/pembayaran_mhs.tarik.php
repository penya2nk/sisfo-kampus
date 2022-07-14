<?php
error_reporting(0);
session_start();
include_once "../academic_sisfo1.php";
include_once "../$_SESSION[ndelox].lib.php";

ViewHeaderApps("Tarik Pembayaran");

//function ByrEdit(mhswid, khsid, md, bayarid)
$MhswID = sqling($_REQUEST['MhswID']);
$KHSID = $_REQUEST['KHSID']+0;
$md = $_REQUEST['md']+0;
$BayarID = sqling($_REQUEST['BayarID']);

$lungo = (empty($_REQUEST['lungo']))? 'Edit' : $_REQUEST['lungo'];
$lungo($MhswID, $KHSID, $md, $BayarID);

function Edit($MhswID, $KHSID, $md, $BayarID) {
  $mhsw = AmbilFieldx('mhsw', "KodeID='".KodeID."' and MhswID", $MhswID, '*');
  $khs = AmbilFieldx('khs', 'KHSID', $KHSID, '*');
  if ($md == 0) {
    $jdl = "Edit Pembayaran";
    $w = AmbilFieldx('bayarmhsw', 'BayarMhswID', $BayarID, '*');
  }
  elseif ($md == 1) {
    $jdl = "Tambah Penarikan";
    $w = array();
    $w['Tanggal'] = date('Y-m-d');
  }
  else die(PesanError('Error',
    "Mode edit <b>$md</b> tidak dikenali.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" />"));
  // Tampilkan
  BayarScript();
  CheckFormScript('RekeningID,Bank');
  $optrek = AmbilCombo2('rekening', "concat(RekeningID, ' - ', Nama)", 'RekeningID', $w['RekeningID'],
    "KodeID='".KodeID."'", 'RekeningID');
  $optTanggal = AmbilComboTgl($w['Tanggal'], 'Tanggal');
  TitleApps($jdl);
  echo "
  <table class=box cellspacing=1 width=100%>
  <form name='frmBayar' action='../$_SESSION[ndelox].tarik.php' method=POST onSubmit=\"return CheckForm(this)\">
  <input type=hidden name='lungo' value='Simpan' />
  <input type=hidden name='MhswID' value='$MhswID' />
  <input type=hidden name='KHSID' value='$KHSID' />
  <input type=hidden name='md' value='$md' />
  <input type=hidden name='BayarID' value='$BayarID' />
  
  <tr><td class=inp>Ditarik oleh:</td>
      <td class=ul1 colspan=3>
        <font size=+1>$mhsw[Nama]</font> <sup>(NIM: $MhswID)</sup>
      </td>
      </tr>
  <tr><td class=inp>Dari Rekening:</td>
      <td class=ul1><select name='RekeningID'>$optrek</select></td>
      <td class=inp>Tgl Setor:</td>
      <td class=ul1>$optTanggal</td>
      </tr>
  <tr><td class=inp>Ditarik Dari:</td>
      <td class=ul1><input type=text name='Bank' value='$w[Bank]' size=30 maxlength=50 /> <a href='#' onClick=\"javascript:SetCash()\">Set CASH</a><br />
        <sup>Isi dengan `CASH` bila dibayarkan dengan Tunai</sup>
      </td>
      <td class=inp>No. Bukti Tarik:</td>
      <td class=ul1><input type=text name='BuktiSetoran' value='$w[BuktiSetoran]'
        size=30 maxlength=50 /><br />
        <sup>Kosongkan jika pembayaran cash</sup>
      </td>
      </tr>
  <tr><td class=inp>Catatan:</td>
      <td class=ul1 colspan=3><input type=text name='Keterangan' value='$w[Keterangan]'
        size=90 maxlength=50 />
      </td>
      </tr>
  </table>";
  TampilkanDetailBiaya($MhswID, $mhsw, $khs, $md);
}
function TampilkanDetailBiaya($MhswID, $mhsw, $khs, $md) {
	global $koneksi;
  $s = "select bm.*, s.Nama as _saat,
      format(bm.Jumlah, 0) as JML,
      format(bm.Besar, 0) as BSR,
      (bm.Jumlah * bm.Besar) as SubTTL,
      ((bm.TrxID * bm.Jumlah * bm.Besar) - bm.Dibayar) as SISA,
	  bn.DipotongBeasiswa, b2.PerMataKuliah, b2.PerSKS, b2.PerLab
    from bipotmhsw bm
      left outer join bipot2 b2 on b2.BIPOT2ID = bm.BIPOT2ID
      left outer join saat s on b2.SaatID = s.SaatID
	  left outer join bipotnama bn on bn.BIPOTNamaID = bm.BIPOTNamaID
	where bm.MhswID = '$MhswID'
      and bm.PMBMhswID = 1
      and bm.TahunID = '$khs[TahunID]'
      and bm.KodeID = '".KodeID."'
      and bm.NA = 'N'
	  and (bm.Dibayar - (bm.TrxID * bm.Jumlah * bm.Besar) > 0)
    order by bm.TrxID, bm.BIPOTMhswID";
  $r = mysqli_query($koneksi, $s);
  $n = 0;
  $sisa = 0;
  $potongan = 0; $arrPotongan = array(); 
  echo "<table class=box cellspacing=1 width=100%>";
  echo "<tr><th class=ttl colspan=7>Detail Biaya</th></tr>";
  echo "<tr>
    <th class=ttl>#</th>
    <th class=ttl>Nama Biaya<hr size=1 color=silver />Saat Bayar</th>
    <th class=ttl width=100>Jumlah
      <hr size=1 color=silver />
      Besar Biaya</th>
    <th class=ttl width=100>Sub Total</th>
    <th class=ttl width=100>Sdh Dibayar</th>
    <th class=ttl width=100>Dibayarkan</th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
  	/*
    if($w['TrxID'] < 1)
	{

		if($w['PerMataKuliah'] == 'Y' or $w['PerSKS'] == 'Y' or $w['PerLab'] == 'Y')
		{	$arrPotongan[$w['TambahanNama']] += $w['Jumlah'] * $w['Besar'];
		}
		else $potongan += $w['Jumlah'] * $w['Besar'];
				  $ro = '';
		  $c = "class=ul";
		$SubTTL = number_format($w['SubTTL'], 2, ',', '.');
		$Dibayar = number_format($Dibayar, 2, ',', '.');  
	
	}
	else
	{
	*/	
		$n++;
		
		if($w['PerMataKuliah'] == 'Y' or $w['PerSKS'] == 'Y' or $w['PerLab'] == 'Y')
		{	$potonganpartial = $arrPotongan[$w['TambahanNama']];
		    if($potonganpartial > 0 and $w['DipotongBeasiswa'] == 'Y')
			{	if($potonganpartial >= $w['SISA'])
				{	$Dibayar = $w['SubTTL'];
					$selisih = 0;
					$sisa += $selisih;
					$arrPotongan[$w['TambahanNama']] -= $w['SISA'];
				}
				else
				{	$selisih = $w['SISA'] - $potonganpartial;
					$Dibayar = $w['Dibayar'] + $potonganpartial;
					$sisa += $selisih;
					$arrPotongan[$w['TambahanNama']] = 0;
				}
			}
			else
			{	$Dibayar = $w['Dibayar'];
				$selisih = $w['SISA'];
				$sisa += $selisih;
			}
		}
		else
		{
			if($potongan > 0 and $w['DipotongBeasiswa'] == 'Y')
			{	if($potongan >= $w['SISA'])
				{	$Dibayar = $w['SubTTL'];
					$selisih = 0;
					$sisa += $selisih;
					$potongan -= $w['SISA'];
				}
				else
				{	$selisih = $w['SISA'] - $potongan;
					$Dibayar = $w['Dibayar'] + $potongan;
					$sisa += $selisih;
					$potongan = 0;
				}
			}
			else
			{	$Dibayar = $w['Dibayar'];
				$selisih = $w['SISA'];
				$sisa += $selisih;
			}
		}
		  $ro = '';
		  $c = "class=ul";
		$SubTTL = number_format($w['SubTTL'], 2, ',', '.');
		$Dibayar = number_format($Dibayar, 2, ',', '.');  
		
	    echo "<tr>
		  <td class=inp>$n</td>
		  <td $c><b>$w[Nama]</b>
			<br />
			<sup>$w[TambahanNama]</sup>
			<div align=right><sub>$w[_saat]</sub></div></td>
		  <td $c><sup>$w[JML] &times;</sup><br />
			<div align=right>$w[BSR]</div>
			</td>
		  <td $c align=right><a href='#' onClick=\"javascript:SetBayar($n, $selisih); HitungUlang();\">$SubTTL &raquo;</a></td>
		  <td $c align=right>$Dibayar</td>
		  <td $c>
			<input type=hidden name='BYRID_$n' value='$w[BIPOTMhswID]' />
			<input type=text name='BYR_$n' value='0' size=10 maxlength=20 style='text-align:right' onChange='HitungUlang()' $ro />
			</td>
		  </tr>";
	  /*
	  }
	  */
  }
  $_sisa = number_format(-1*$sisa);
  echo "<input type=hidden name='CNT' value='$n' />";
  echo "<input type=hidden name='sisane' value='$sisa' />";
  echo "<tr><td bgcolor=silver colspan=10 height=1></td></tr>";
  echo "<tr>
    <td class=ul1 colspan=4 align=right>Total yang Dapat Ditarik:</td>
    <td class=ul1 align=right><font size=+1>$_sisa</font></td>
    <td class=ul1><input type=text name='TTLBYR' size=10 maxlength=50 readonly=TRUE style='text-align:right' /></td>
    </tr>";
  echo "<tr>
    <td class=ul1 colspan=10 align=center>
    <input type=submit  name='Simpan' value='Simpan' />
    <input type=button name='Batal' value='Batal' onClick=\"window.close()\" />
    </td></tr>";
  echo "</form>";
  echo "</table></p>";
  echo <<<ESD
  <script>
  function SetBayar(n, jml) {
    eval("frmBayar.BYR_" + n + ".value = '" + (jml*-1) +"';");
  }
  </script>
ESD;
  HitungUlang($n);
}
//($MhswID, $KHSID, $md, $BayarID);
function Simpan($MhswID, $KHSID, $md, $BayarID) {
	global $koneksi;
  $RekeningID = sqling($_REQUEST['RekeningID']);
  $Bank = sqling($_REQUEST['Bank']);
  $BuktiSetoran = sqling($_REQUEST['BuktiSetoran']);
  $Keterangan = sqling($_REQUEST['Keterangan']);
  $Tanggal = "$_REQUEST[Tanggal_y]-$_REQUEST[Tanggal_m]-$_REQUEST[Tanggal_d]";
  $CNT = $_REQUEST['CNT']+0;
  $sisane = -1*$_REQUEST['sisane']+0;
  $TTLBYR = $_REQUEST['TTLBYR']+0;
  // Cek jika tidak ada detailnya
  if ($sisane < $TTLBYR)
    die(PesanError('Error',
      "Penarikan tidak boleh lebih besar<br />
      daripada total yang dapat ditarik<br />
      Hubungi Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      Opsi: <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" />"));

  if ($CNT == 0)
    die(PesanError('Error',
      "Tidak ada detail biaya.<br />
      Data tidak bisa disimpan.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      Opsi: <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" />"));
  // Cek dulu jumlah yg dibayarkan
  $jml = 0;
  for ($i = 1; $i <= $CNT; $i++) {
    $jml += $_REQUEST['BYR_'.$i]+0;
  }
  if ($jml <= 0) {
    die(PesanError('Error',
      "Jumlah yang Anda bayarkan: <font size=+1>$jml</font>.<br />
      Tidak ada yang perlu dibayarkan.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      Opsi: <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" /"));
  }
  $mhsw = AmbilFieldx('mhsw', "KodeID='".KodeID."' and MhswID", $MhswID, '*');
  $khs = AmbilFieldx('khs', 'KHSID', $KHSID, '*');
  // Oke, mulai simpan datanya
  // 1. Ambil nomer terakhir yang ada
  $BayarMhswID = GetNextBPM();
  // 2. Buat header bukti pembayaran
  $s = "insert into bayarmhsw
    (BayarMhswID, TahunID, KodeID,
    RekeningID, MhswID, TrxID, PMBMhswID,
    Bank, BuktiSetoran, Tanggal,
    Jumlah, Keterangan,
    LoginBuat, TanggalBuat)
    values
    ('$BayarMhswID', '$khs[TahunID]', '".KodeID."',
    '$RekeningID', '$mhsw[MhswID]', -1, 1,
    '$Bank', '$BuktiSetoran', '$Tanggal',
    $jml, '$Keterangan',
    '$_SESSION[_LoginBuat]', now())";
  $r = mysqli_query($koneksi, $s);
  // 3. Simpan detailnya
  for ($i = 1; $i <= $CNT; $i++) {
    $_j = $_REQUEST['BYR_'.$i]+0;
    if ($_j > 0) { // Simpan
      $id = $_REQUEST['BYRID_'.$i]+0;
      $BIPOTNamaID = AmbilOneField('bipotmhsw', "BIPOTMhswID", $id, 'BIPOTNamaID');
      // Simpan dulu detail pembayarannya
      $s = "insert into bayarmhsw2
        (BayarMhswID, BIPOTMhswID, BIPOTNamaID, Jumlah,
        LoginBuat, TanggalBuat)
        values
        ('$BayarMhswID', $id, '$BIPOTNamaID', ".(-1*$_j).",
        '$_SESSION[_Login]', now())";
      $r = mysqli_query($koneksi, $s);
      // Update detail biayanya
      $s = "update bipotmhsw
        set Dibayar = Dibayar + ".(-1*($_j))."
        where BIPOTMhswID = $id";
      $r = mysqli_query($koneksi, $s);
    }
  }
  HitungUlangBIPOTMhsw($MhswID, $khs['TahunID']);
  TutupScript($MhswID);
}
function BayarScript() {
  echo <<<SCR
  <script>
  function SetCash() {
    frmBayar.Bank.value = 'CASH';
    frmBayar.BuktiSetoran.value = 'CASH';
  }
  </script>
SCR;
}
function HitungUlang($n) {
  echo <<<SCR
  <script>
  function HitungUlang() {
    var i = 0;
    var ttl = 0;
SCR;
  for ($i = 1; $i <= $n; $i++) {
    echo "
    ttl = ttl + Number(frmBayar.BYR_" . $i . ".value);\n";
  }
  echo <<<SCR
    frmBayar.TTLBYR.value = ttl;
  }
  </script>

SCR;
}
function TutupScript($MhswID) {
echo <<<SCR
<SCRIPT>
  function ttutup() {
    opener.location='../index.php?ndelox=$_SESSION[ndelox]&lungo=DetailMhsw&MhswID=$MhswID';
    self.close();
    return false;
  }
  ttutup();
</SCRIPT>
SCR;
}
?>
