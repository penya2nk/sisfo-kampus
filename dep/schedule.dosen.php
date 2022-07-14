<?php
error_reporting(0);
session_start();

include_once "../academic_sisfo1.php";

ViewHeaderApps("Jadwal Kuliah", 1);
global $koneksi;
$id = GainVariabelx('id');
$jdwl = AmbilFieldx("jadwal j
  left outer join dosen d on d.Login = j.DosenID and d.KodeID='".KodeID."'
  left outer join program prg on prg.ProgramID = j.ProgramID
  left outer join prodi prd on prd.ProdiID = j.ProdiID
  left outer join jenisjadwal jj on jj.JenisJadwalID = j.JenisJadwalID
  left outer join hari h on h.HariID = j.HariID 
  LEFT OUTER JOIN kelas k ON k.KelasID = j.NamaKelas",
  "j.KodeID='".KodeID."' and j.JadwalID", $id,
  "j.JadwalID, j.MKKode, j.Nama, j.MKID, j.NamaKelas, j.JenisJadwalID,
  left(j.JamMulai, 5) as JM, left(j.JamSelesai, 5) as JS,
  h.Nama as HR, jj.Nama as JEN, j.SKS,
  j.RuangID,
  concat(d.Nama, ' <sup>', d.Gelar, '</sup>') as DSN,
  prg.Nama as _PRG, prd.Nama as _PRD, k.Nama AS namaKelas");
if (empty($jdwl))
  die(PesanError('Error',
    "Jadwal tidak ditemukan.<br />
    Mungkin jadwal sudah dihapus atau mungkin Anda tidak berhak mengakses modul ini.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    Opsi: <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" />"));

TitleApps("Edit Dosen &minus; Jadwal");
TampilkanHeaderDosenJadwal($jdwl);
$lungo = (empty($_REQUEST['lungo']))? 'DftrDosen' : $_REQUEST['lungo'];
$lungo($jdwl);

function TampilkanHeaderDosenJadwal($jdwl) {
  echo "<table id='example' class='table table-sm table-striped' style='width:100%' align='center'>
  <tr style='background:purple;color:white;text-align:left''><td colspan='2'><b>MATAKULIAH & WAKTU</b></td></tr>

  <tr><td  width=180>Matakuliah</td>
      <td ><b>$jdwl[Nama]  ($jdwl[SKS] SKS)</b></td>
	  </tr>
  <tr>  
      <td >Waktu</td>
      <td >$jdwl[HR], $jdwl[JM] - $jdwl[JS] WIB</td>
      </tr>
      <tr style='background:purple;color:white;text-align:left''><td colspan='2'><b>DOSEN UTAMA</b></td></tr>    
  <tr><td>Dosen Utama</td>
      <td >$jdwl[DSN]</td>
	  </tr>
  <tr>
  <tr style='background:purple;color:white;text-align:left''><td colspan='2'><b>RUANGAN DAN KELAS</b></td></tr>
  <td >Kelas / Ruang</td>
      <td >$jdwl[namaKelas] <sup>($jdwl[JEN])</sup> / $jdwl[RuangID]</td>
	  </tr>

  </table>";
}
function DftrDosen($jdwl) {
  global $koneksi;
  $s = "select jd.*, d.Nama, d.Gelar
    from jadwaldosen jd
      left outer join dosen d on jd.DosenID = d.Login
    where jd.JadwalID = '$jdwl[JadwalID]'
    order by d.Nama";
  $r = mysqli_query($koneksi, $s);
  $n = 0;
  
  FormDosenJadwal($jdwl);
  HapusDosenScript($jdwl);
  echo "<p><table class='table table-sm table-striped' style='width:100%' align='center'>";
  echo "<tr style='background:purple;color:white'>
    <th  width=20>No</th>
    <th  width=100>Kode</th>
    <th  style='text-align:left'>Nama Dosen</th>
    <th  width=100 style='text-align:left'>Jenis</th>
	 <th  style='text-align:left'>Aksi</th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    echo "<tr>
      <td class=inp>$n</td>
      <td class=ul1>$w[DosenID]</td>
      <td class=ul1>$w[Nama] <sup>($w[Gelar])</sup>
      <td class=ul1 align=left>$w[JenisDosenID]</td>
      <td class=ul1 width=10 align=center><a href='#' onClick='javascript:HapusDosen($w[JadwalDosenID])'><img src='../img/del.png' /></a></td>
      </tr>";
  }
  echo "</table></p>";
}
function FormDosenJadwal($jdwl) {
  JadwalDosenScript($jdwl);
  TutupScript();
  CheckFormScript('DosenID,JenisDosenID');
  $optjenisdosen = AmbilCombo2('jenisdosen', 'Nama', 'JenisDosenID', 'DSN', '', 'JenisDosenID');
  echo <<<SCR
    <table class='table table-sm table-striped' style='width:100%' align='center'>

    <form name='frmJadwal' action='../$_SESSION[ndelox].dosen.php' method=POST
      onSubmit="return CheckForm(this)">
    <input type=hidden name='lungo' value='SimpanDosen' />
    <input type=hidden name='id' value='$jdwl[JadwalID]' />
    
	<tr>	
    <td width=180>Nama Dosen </td>
	<td><input type=text name='Dosen' size=40 maxlength=15 
            onKeyUp="javascript:CariDosen('$_SESSION[_jdwlProdi]', 'frmJadwal')" />
          <a href='#'
            onClick="javascript:CariDosen('$jdwl[ProdiID]', 'frmJadwal')">Search</a> |
          <a href='#' onClick="javascript:frmJadwal.DosenID.value='';frmJadwal.Dosen.value=''">Clear</a>
    </td>
	</tr>
			  
    <tr>
	<td >Dosen</td>
    <td ><input type=text name='DosenID' size=40 maxlength=15 /></td>
	</tr>
	
  <tr>  
        <td >Jenis</td>
		<td>
          <select name='JenisDosenID'>$optjenisdosen</select>
          </td>
  </tr>
			  
  <tr>  
        <td >
          <input class='btn btn-primary' type=submit name='Simpan' value='Simpan' />
          <input type=button name='Kembali' value='Kembali' onClick="ttutup()" />
          </td>
        </tr>
    </form>
    </table>
    <div class='box0' id='caridosen'>...</div>
SCR;
}
function SimpanDosen($jdwl) {
  global $koneksi;
  $DosenID = sqling($_REQUEST['DosenID']);
  $JenisDosenID = sqling($_REQUEST['JenisDosenID']);
  // Cek dulu
  $ada = AmbilOneField('jadwaldosen', "JadwalID='$_SESSION[id]' and DosenID", $DosenID, 'JadwalDosenID');
  $ada1 = AmbilFieldx('jadwal', "JadwalID='$_SESSION[id]' and DosenID", $DosenID, '*');
  if(!empty($ada1)){
     echo PesanError("Error",
      "Dosen <b>$DosenID</b> telah didaftarkan di jadwal ini.<br />
      Anda tidak bisa mendaftarkannya dua kali.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      Opsi: <input type=button name='Tutup' value='Tutup'
        onClick=\"window.close()\" />");
  }
  elseif (!empty($ada)) {
     echo PesanError("Error",
      "Dosen <b>$DosenID</b> telah didaftarkan di jadwal ini.<br />
      Anda tidak bisa mendaftarkannya dua kali.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      Opsi: <input type=button name='Tutup' value='Tutup'
        onClick=\"window.close()\" />");
  }else{
    // Simpan
    $s = "insert into jadwaldosen
      (JadwalID, DosenID, JenisDosenID,
      TglBuat, LoginBuat)
      values
      ('$jdwl[JadwalID]', '$DosenID', '$JenisDosenID',
      now(), '$_SESSION[_Login]')";
    $r = mysqli_query($koneksi, $s);
    SuksesTersimpan("../$_SESSION[ndelox].dosen.php?id=$jdwl[JadwalID]", 1);  
  }
  
}
function HapusDosenScript($jdwl) {
  echo <<<SCR
  <script>
  function HapusDosen(jdid) {
    if (confirm("Benar Anda akan menghapus dosen ini?")) {
      window.location = "../$_SESSION[ndelox].dosen.php?lungo=HapusDosen&id=$jdwl[JadwalID]&jdid="+jdid;
    }
  }
  </script>
SCR;
}
function HapusDosen($jdwl) {
  global $koneksi;
  $jdid = $_REQUEST['jdid']+0;
  $s = "delete from jadwaldosen where JadwalDosenID = '$jdid' ";
  $r = mysqli_query($koneksi, $s);
  BerhasilHapus("../$_SESSION[ndelox].dosen.php?id=$jdwl[JadwalID]&lungo=", 1);
}
function TutupScript() {
echo <<<SCR
<SCRIPT>
  function ttutup() {
    opener.location='../index.php?ndelox=$_SESSION[ndelox]&lungo=';
    self.close();
    return false;
  }
</SCRIPT>
SCR;
}
function JadwalDosenScript($jdwl) {
  echo <<<SCR
  <script src="../$_SESSION[ndelox].edit.script.js"></script>
  <script>
  function toggleBox(szDivID, iState) // 1 visible, 0 hidden
  {
    if(document.layers)	   //NN4+
    {
       document.layers[szDivID].visibility = iState ? "show" : "hide";
    }
    else if(document.getElementById)	  //gecko(NN6) + IE 5+
    {
        var obj = document.getElementById(szDivID);
        obj.style.visibility = iState ? "visible" : "hidden";
    }
    else if(document.all)	// IE 4
    {
        document.all[szDivID].style.visibility = iState ? "visible" : "hidden";
    }
  }
  function CariDosen(ProdiID, frm) {
    if (eval(frm + ".Dosen.value != ''")) {
      showDosen(ProdiID, frm, eval(frm +".Dosen.value"), 'caridosen');
      toggleBox('caridosen', 1);
    }
  }
  </script>
SCR;
}
?>

</BODY>
</HEAD>
