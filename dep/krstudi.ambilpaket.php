<?php
error_reporting(0);
session_start();
include_once "../academic_sisfo1.php";
include_once "../$_SESSION[ndelox].lib.php";

ViewHeaderApps("KRS Paket", 1);

$mhswid = GainVariabelx('mhswid');
$khsid = GainVariabelx('khsid');
$NamaKelas = GainVariabelx('NamaKelas');
$MKPaketID = GainVariabelx('MKPaketID');

TitleApps("Ambil KRS Paket");
$lungo = (empty($_REQUEST['lungo']))? 'DftrPaket' : $_REQUEST['lungo'];
$lungo($mhswid, $khsid);

function DftrPaket($mhswid, $khsid) {
  $mhsw = AmbilFieldx('mhsw', "KodeID='".KodeID."' and MhswID", $mhswid, '*');
  $khs = AmbilFieldx('khs', 'KHSID', $khsid, '*');
  $optpkt = AmbilCombo2('mkpaket', 'Nama', 'Nama', $_SESSION['MKPaketID'], "KodeID='".KodeID."' and ProdiID='$khs[ProdiID]'", 'MKPaketID');
  $optkelas = AmbilCombo2('kelas', 'Nama', 'Nama', $_SESSION['NamaKelas'], "TahunID='$khs[TahunID]' and KodeID='".KodeID."'", "KelasID");
  echo <<<ESD
  <table class=box cellspacing=1 width=100%>
  <form name='frm' action='../$_SESSION[ndelox].ambilpaket.php' method=POST>
  <input type=hidden name='mhswid' value='$mhswid' />
  <input type=hidden name='khsid' value='$khsid' />
  <input type=hidden name='lungo' value='' />
  
  <tr><td class=inp width=60>Tahun Akd:</td>
      <td class=ul width=100>$khs[TahunID]</td>
      <td class=inp width=60>NIM:</td>
      <td class=ul width=100>$khs[MhswID]</td>
      <td class=inp width=60>Mahasiswa:</td>
      <td class=ul>$mhsw[Nama] <img src='../img/$mhsw[Kelamin].png' /></td>
      </tr>
  <tr><td class=inp>Kelas:</td>
  	  <td class=ul1><select name='NamaKelas' value='$_SESSION[NamaKelas]'>$optkelas</select></td>
      <td class=inp>Paket:</td>
      <td class=ul colspan=3>
        <select name='MKPaketID'>$optpkt</select>
        <input type=submit name='AmbilJadwal' value='Cek Detail' />
        <input type=button name='Batal' value='Batal' onClick='window.close()' />
      </td>
      </tr>
  </form>
  </table>
ESD;
  if (!empty($_SESSION['MKPaketID'])) 
    TampilkanDetailPaket($mhsw, $khs, $_SESSION['MKPaketID']);
  else echo "<div class=wrn align=center>Tidak ada paket mata kuliah yang dipilih.<br> Pilihlah paket mata kuliah di atas terlebih dahulu.</div>";
}
function TampilkanDetailPaket($mhsw, $khs, $paketid) {
global $koneksi;
  $whr_namakelas = (empty($_SESSION['NamaKelas']))? "" : " and j.NamaKelas = '$_SESSION[NamaKelas]'";
  $s = "SELECT pi.MKPaketIsiID,
    mk.MKKode, mk.Nama, mk.SKS,
    j.JadwalID, LEFT(j.JamMulai, 5) AS JM, LEFT(j.JamSelesai, 5) AS JS,
    j.NamaKelas, h.Nama AS HR, j.AdaResponsi,
    d.Nama AS DSN, d.Gelar, 
	jj.Nama AS _NamaJenisJadwal, jj.Tambahan, kl.Nama AS NamaKelas
    FROM mkpaketisi pi
      LEFT OUTER JOIN mkpaket p 
	  ON pi.MKPaketID = p.MKPaketID
		LEFT OUTER JOIN mk 
		ON pi.MKID = mk.MKID
			LEFT OUTER JOIN jadwal j 
			ON j.MKID = pi.MKID $whr_namakelas AND j.TahunID='$khs[TahunID]' AND j.ProgramID='$khs[ProgramID]' AND j.JumlahMhsw < j.Kapasitas
				LEFT OUTER JOIN hari h 
				ON j.HariID = h.HariID
					LEFT OUTER JOIN dosen d 
					ON d.Login = j.DosenID AND d.KodeID = '".KodeID."'
						LEFT OUTER JOIN jenisjadwal jj 
						ON jj.JenisJadwalID=j.JenisJadwalID AND jj.Tambahan='Y' 
							LEFT OUTER JOIN kelas kl
							ON kl.KelasID = j.NamaKelas 
    WHERE pi.MKPaketID = $paketid
      AND p.ProdiID = '$khs[ProdiID]'
    ORDER by j.MKKode, j.HariID, j.JamMulai, j.JamSelesai";
  $r = mysqli_query($koneksi, $s); $n = 0;
  
  echo "<table class=box cellspacing=1 width=100%>";
  echo "<form name='frmisi' action='../$_SESSION[ndelox].ambilpaket.php' method=POST />
    <input type=hidden name='mhswid' value='$mhsw[MhswID]' />
    <input type=hidden name='khsid' value='$khs[KHSID]' />
    <input type=hidden name='lungo' value='Ambil' />";
  echo "<tr style='background:purple;color:white'>
    <th class=ttl colspan=2 width=40><input type=submit name='Ambil' value='Ambil' /></th>
    <th class=ttl width=100>Kode</th>
    <th class=ttl>Mata Kuliah</th>
    <th class=ttl width=50>Jadwal</th>
    <th class=ttl width=50>Hari</th>
    <th class=ttl width=70>Jam Kuliah</th>
    <th class=ttl width=50>Kelas</th>
    </tr>";
  $_sks = 0; $blong = 0; $j = '034rnpqb038h[q034bpae';
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    if (empty($w['JadwalID'])) {
      $c = "class=nac";
      $ck = '&times;';
      $blong++;
    }
    else {
	  if($j != $w['MKKode'])
	  {	$j = $w['MKKode'];
		$checked = 'checked';
	  }
	  else $checked = '';
      $c = "class=ul";
      $ck = "<input type=checkbox name='_JadwalID_$n' value='$w[JadwalID]' $checked>";
      $_sks += $w['SKS'];
    }
    
	if($w['Tambahan'] == 'Y')
	{
	}
	else
	{
	echo "<tr>
      <td class=inp width=20>$n</td>
      <td $c width=20 align=center>
        $ck
        </td>
      <td $c>
        $w[MKKode]
        <div align=right><sup>$w[SKS] sks</sup></div>
        </td>
      <td $c>
        $w[Nama]
        <div align=right><sup>$w[DSN], $w[Gelar]</sup></div>
        </td>
      <td $c><img src='../img/kanan.gif' /> <sup>#</sup>$w[JadwalID]</td>
      <td $c>$w[HR]&nbsp;</td>
      <td $c align=center><sup>$w[JM]</sup>~<sub>$w[JS]</sup></td>
      <td $c>$w[NamaKelas]&nbsp;</td>
      </tr>";
	  }
  }
  echo "<input type=hidden name='Jumlah' value='$n' />
    <tr>
    <td class=ul colspan=2><input type=submit name='Ambil' value='Ambil' /></td>
    <td class=ul1 align=right>Total: <font size=+1>$_sks</font> SKS</td>
    </tr>";
  echo "</form></table>";
  if ($blong > 0) {
    echo "<div class=wrn align=center>
      Anda matakuliah yang belum dijadwalkan sebanyak <font size=+1>$blong</font> MK.<br />
      Matakuliah tersebut harus dijadwalkan terlebih dahulu.<br />
      <input type=button name='Batal' value='Batal' onClick='window.close()' />
      </div>";
  }
}
function Ambil($mhswid, $khsid) {
	global $koneksi;
  $Jumlah = $_REQUEST['Jumlah'];
  if ($Jumlah > 0) {
    for ($i = 1; $i <= $Jumlah; $i++) {
      $JID = $_REQUEST['_JadwalID_'.$i]+0;
      if ($JID > 0) {
        // Cek dulu apakah sudah diambil oleh si Mhsw atau belum
        $sdh = AmbilOneField('krs', "KHSID='$khsid' and JadwalID", $JID, 'KRSID')+0;
        if ($sdh == 0) {
          $jdwl = AmbilFieldx('jadwal', 'JadwalID', $JID, '*');
          // Tambahkan di KRS mhsw
          $khs = AmbilFieldx('khs', 'KHSID', $khsid, '*');
          $s = "insert into krs
            (KodeID, KHSID, MhswID, TahunID,
            JadwalID, MKID, MKKode, Nama, SKS,
            HargaStandar, Harga,
            LoginBuat, TanggalBuat)
            values
            ('".KodeID."', '$khsid', '$mhswid', '$khs[TahunID]',
            $JID, '$jdwl[MKID]', '$jdwl[MKKode]', '$jdwl[Nama]', '$jdwl[SKS]',
            '$jdwl[HargaStandar]', '$jdwl[Harga]',
            '$_SESSION[_Login]', now())";
          $r = mysqli_query($koneksi, $s);
        } // end if empty
         HitungPeserta($jdwl['JadwalID']);
      } // end if ($jid)
    } // end for
    HitungUlangKRS($khsid);
    TutupScript($mhswid, $khsid);
  }
  else PesanError("Error",
    "Tidak ada matakuliah dalam paket.<br />
    Hubungi BAA untuk membuat paket mata kuliah terlebih dahulu.<br />
    Atau hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    <input type=button name='Tutup' value='Tutup' onClick='window.close()' />");
}

function TutupScript($mhswid, $khsid) {
echo <<<SCR
<SCRIPT>
  function ttutup() {
    opener.location='../index.php?ndelox=$_SESSION[ndelox]&lungo=&mhswid=$mhswid&khsid=$khsid';
    self.close();
    return false;
  }
  ttutup();
</SCRIPT>
SCR;
}
?>
