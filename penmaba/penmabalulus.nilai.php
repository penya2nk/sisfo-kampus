<?php
error_reporting(0);
session_start();
include_once "../academic_sisfo1.php";

ViewHeaderApps("NILAI UJIAN SELEKSI MASUK");

$PMBID = sqling($_REQUEST['PMBID']);
$pmb = AmbilFieldx('pmb', "KodeID='".KodeID."' and PMBID", $PMBID, '*');

if (empty($pmb)) {
  die(PesanError('Error',
    "Data PMB dengan nomer: <b>$PMBID</b> tidak ditemukan.
    <hr size=1 color=silver />
    <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" />"));
}

// Cek apakah sudah diproses menjadi mahasiswa atau belum
if (!empty($pmb['MhswID']))
  die(PesanError('Error',
    "<img src='../img/lock.jpg' align=right />
    Anda sudah tidak dapat mengubah data ini karena Cama sudah diproses menjadi mahasiswa.<br />
    Nomer registrasi mahasiswa (NIM): <b>$pmb[MhswID]</b>.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" />"));

TitleApps("NILAI UJIAN SELEKSI MASUK");
$lungo = (empty($_REQUEST['lungo']))? 'Edit' : $_REQUEST['lungo'];
$lungo($PMBID, $pmb);

function TampilkanHeader($pmb) {
  $STA = AmbilOneField('statusawal', "StatusAwalID", $pmb['StatusAwalID'], 'Nama');
  $FRM = AmbilOneField('pmbformulir', "KodeID='".KodeID."' and PMBFormulirID", $pmb['PMBFormulirID'], 'Nama');
  $p1 = AmbilOneField('prodi', "KodeID='".KodeID."' and ProdiID", $pmb['Pilihan1'], 'Nama');
  $p2 = AmbilOneField('prodi', "KodeID='".KodeID."' and ProdiID", $pmb['Pilihan2'], 'Nama');
  $p3 = AmbilOneField('prodi', "KodeID='".KodeID."' and ProdiID", $pmb['Pilihan3'], 'Nama');
  echo "<table class=bsc cellspacing=1 width=100%>
  <tr><td class=ul1 width=160>No. PMB:</td>
      <td class=ul1>$pmb[PMBID]&nbsp;</td>
      <td class=ul1>Nama Cama:</td>
      <td class=ul1>$pmb[Nama]&nbsp;</td>
      </tr>
  <tr><td class=ul1>Status Masuk:</td>
      <td class=ul1>$STA&nbsp;</td>
      <td class=ul1>Formulir:</td>
      <td class=ul1>$FRM&nbsp;</td>
      </tr>
  <tr><td class=ul1>Pilihan 1:</td>
      <td class=ul1 colspan=3>$pmb[Pilihan1] - $p1</td>
      </tr>
  <tr><td class=ul1>Pilihan 2:</td>
      <td class=ul1 colspan=3>$pmb[Pilihan2] - $p2</td>
      </tr>
  <tr><td class=ul1>Pilihan 3:</td>
      <td class=ul1 colspan=3>$pmb[Pilihan3] - $p3</td>
      </tr>
  </table>";
}

function Edit($PMBID, $pmb) {
  TampilkanHeader($pmb);
  
  $optprodi = AmbilPilihanFinal($pmb);
  $getnilaisekolah = (empty($pmb['NilaiSekolah']))? 'N/A' : $pmb['NilaiSekolah'];
  $gel = AmbilOneField('pmbperiod', "KodeID='".KodeID."' and NA", 'N', "PMBPeriodID");
  $getnilaiujian = (empty($pmb['NilaiUjianTotal']))? AmbilOneField('ruangusm', "PMBID='$pmb[PMBID]' and PMBPeriodID='$gel' and KodeID", KodeID, "sum(NilaiUSM)")+0: $pmb['NilaiUjianTotal']+0;
  $xx = AmbilFieldx('ruangusm', "PMBID='$pmb[PMBID]' and PMBPeriodID='$gel' and KodeID", KodeID, "sum(NilaiUSM) as N, count(ProdiUSMID) as J");
  $rata = ($xx['J']>0)? number_format($xx['N']/$xx['J'],2) : 0;  
  $getnilaiujianrata = (empty($pmb['NilaiUjian']))? $rata : $pmb['NilaiUjian']+0;  
  //$getgrade = AmbilOneField('pmbgrade', "NilaiUjianMin <= $getnilaiujianrata and $getnilaiujianrata <= NilaiUjianMax and KodeID", KodeID, 'GradeNilai');
  $getgrade = AmbilOneField('pmbgrade', "NilaiUjianMin <= 80 and   NilaiUjianMax <= 80 and KodeID", KodeID, 'GradeNilai');  
  $optgrd = AmbilCombo2('pmbgrade', "concat(GradeNilai, ' (', if (Keterangan is NULL, '', Keterangan), ')')", 'GradeNilai', $getgrade, "KodeID='".KodeID."'", 'GradeNilai');
  $arrPT = explode('~', $pmb['PrestasiTambahan']);
  foreach($arrPT as $Prestasi) 
  {	if(!empty($Prestasi)) $PrestasiTambahan .= (empty($PrestasiTambahan))? $Prestasi : "<br>".$Prestasi;
  }
  $ck = ($pmb['LulusUjian'] == 'Y')? 'checked' : '';
  echo '
  		<script>
			function cekThisForm(){
				var errmsg = "";
				var cek = document.getElementById("LulusUjian").checked;
				var nilai = document.getElementById("NilaiUjian").value;
				if (cek == true){
					if (nilai == 0){
						errmsg += "Nilai ujian masih bernilai 0 \\n";
					}
				}
				if (errmsg != ""){
					alert (errmsg);
					return false;
				} else {
					return true;
				}
			}
			
		</script>
  		';
  echo "<table class=bsc cellspacing=1 width=100%>
  
  <form action='../$_SESSION[ndelox].nilai.php' method=POST onsubmit='return cekThisForm()'>
  <input type=hidden name='PMBID' value='$PMBID' />
  <input type=hidden name='lungo' value='Simpan' />
  ";
  
  $pmbformulir = AmbilFieldx('pmbformulir', "KodeID='".KodeID."' and PMBFormulirID", $pmb['PMBFormulirID'], 'USM, Wawancara');
  // Bila PMB Formulir memiliki komponen ujian, ambil kolom dan isi detail USM nya
  if($pmbformulir['USM'] == 'Y')
  {
	  $DetailUSM = AmbilDetailUSM($pmb);
	  echo "
	  <tr style='background:purple;color:white'><th class=ttl colspan=2>Detail Penilaian</th></tr>
	  $DetailUSM
	  <tr style='background:purple;color:white'><th class=ttl colspan=2>Penilaian Akhir</th></tr>";
  }
  
  if($pmbformulir['Wawancara'] == 'Y')
  {	$HasilWawancara = AmbilOneField('wawancara w', "w.Tanggal = (select max(w2.Tanggal) from wawancara w2 where w2.PMBID='$PMBID' group by w2.PMBID) and w.PMBID='$PMBID' and w.KodeID", KodeID, "HasilWawancara");
	$_HasilWawancara = (!empty($HasilWawancara))? "<td class=ul1><input type=text name='HasilWawancara' value='$HasilWawancara' disabled></td>" : "<td><b>Belum Wawancara</b></td>";
	echo "<tr><td class=ul1>Hasil Wawancara:</td>
				$_HasilWawancara
				</tr>";
  }
  
  echo "
  <tr><td class=ul1 width=160>Nilai Sekolah Terakhir:</td>
	  <td class=ul1><input type=text name='NilaiSekolah' value='$getnilaisekolah' size=4 maxlength=4 style='text-align:right' disabled></td></tr>
  <tr><td class=ul1>Prestasi Tambahan:</td>
	  <td class=ul1>$PrestasiTambahan</td>
	  </tr>";
	  
  echo "
  <tr><td class=ul1>Catatan Lainnya:</td>
	  <td class=ul1><textarea name='Catatan' cols=30 row=2>$pmb[Catatan]</textarea></td>
	  </tr>
  <tr><td class=ul1 width=100>Pilihan Final:</td>
      <td class=ul1><select name='ProdiID'>$optprodi</select></td>
      </tr>
  <tr><td class=ul1>Lulus?</td>
      <td class=ul1><input type=checkbox id='LulusUjian' name='LulusUjian' value='Y' $ck />
        Beri tanda centang jika lulus
      </td></tr>
  <tr><td class=ul1>Grade:</td>
      <td class=ul1><input type=text name='GradeNilai' value='$getgrade' size=5 readonly></td>	  
      </tr>
  <tr><td class=ul1 colspan=2 align=center>
      <input type=submit name='Simpan' value='Simpan' />
      <input type=button name='Batal' value='Batal' onClick=\"window.close()\" />
      </td></tr>
  </form>
  </table>";
}

function AmbilPilihanFinal($w) {
  $a = '';
  for ($i = 1; $i <= 3; $i++) {
    if (!empty($w['Pilihan'.$i])) {
      $_p = AmbilOneField('prodi', "KodeID='".KodeID."' and ProdiID", $w['Pilihan'.$i], 'Nama');
      $sel = ($w['Pilihan'.$i] == $w['ProdiID'])? 'selected' : '';
      $a .= "<option value='".$w['Pilihan'.$i]."' $sel>".$w['Pilihan'.$i].' - '.$_p."</option>";
    }
  }
  return $a;
}
function Simpan($PMBID, $pmb) {
	global $koneksi;
  $ProdiID = sqling($_REQUEST['ProdiID']);
  $Catatan = sqling($_REQUEST['Catatan']);
  $NilaiUjianTotal = $_REQUEST['NilaiUjianTotal'];

  $rt = "rat_".$_REQUEST['ProdiID'];
  $rat = $_REQUEST[$rt];

  $LulusUjian = (empty($_REQUEST['LulusUjian']))? 'N' : sqling($_REQUEST['LulusUjian']);
  // echo "#$rat1~$rat2~$rat3#";
  //
  $GradeNilai = AmbilOneField('pmbgrade', "NilaiUjianMin <= 80 and 80 <= NilaiUjianMax and KodeID", KodeID, 'GradeNilai');
  //$GradeNilai = AmbilOneField('pmbgrade', "NilaiUjianMin <= $rat and $rat <= NilaiUjianMax and KodeID", KodeID, 'GradeNilai');
  //jika lulusnya di centang
  if($LulusUjian=='Y'){
    
      $grd = $GradeNilai;
      $NilaiUjian = $rat;
     
  }
  //jika tidak
  else{
     $grd = $GradeNilai;
     $NilaiUjian = $rat;  
  }
  //echo "#$rat1~$rat2~$rat3~$grd~$NilaiUjian#";
  //exit;
  // Simpan
  $s = "update pmb
    set ProdiID = '$ProdiID',
		Catatan = '$Catatan',
        LulusUjian = '$LulusUjian',
        NilaiUjian = '$NilaiUjian',
        NilaiUjianTotal = '$NilaiUjianTotal',
		    GradeNilai = '$grd',
        LoginEdit = '$_SESSION[_Login]',
        TanggalEdit = now()
    where KodeID = '".KodeID."' and PMBID = '$PMBID' ";
  $r = mysqli_query($koneksi, $s);
  TutupScript();
  
  include_once "statusaplikan.lib.php";
  SetStatusAplikan('LLS', AmbilOneField('pmb', "PMBID='$PMBID' and KodeID", KodeID, 'AplikanID'), AmbilOneField('pmbperiod', "KodeID='".KodeID."' and NA", 'N', "PMBPeriodID"));
}

function AmbilDetailUSM($pmb) {
global $koneksi;
$a = ''; $n =0; $tot = 0; $x = 0;
for($i=1;$i<=3;$i++){
	$pil = "Pilihan".$i;
	if (!empty($pmb[$pil])){
			
			$s = "select pu.ProdiID, ru.NilaiUSM, pu.PMBUSMID, pu2.Nama, ru.Kehadiran
					from ruangusm ru 
						left outer join prodiusm pu on ru.ProdiUSMID=pu.ProdiUSMID 
						left outer join pmbusm pu2 on pu.PMBUSMID=pu2.PMBUSMID and pu2.KodeID='".KodeID."'
					where ru.KodeID='".KodeID."' 
						and pu.NA='N' 
						and PMBID='$pmb[PMBID]' 
						and (concat('|',pu.ProdiID,'|') LIKE '%|$pmb[$pil]|%')
						and ru.PMBPeriodID='$pmb[PMBPeriodID]' order by pu.ProdiID, PMBUSMID";
			$r = mysqli_query($koneksi, $s);
			$prodi = 'skdashkjd';
			

			$NM = AmbilOneField('prodi', "KodeID='".KodeID."' and ProdiID", $pmb[$pil], 'Nama');
			$a .= "<tr>
				  <td class=wrn colspan=2>$NM</td>
				  </tr>";

			while ($w = mysqli_fetch_array($r)) {
				$ro = 'readonly=true';
				$x++;
				$tot += $w['NilaiUSM'];
				$n++;

				$a .= "<tr>
				  <td class=ul1>$w[Nama]</td>
				  <td class=ul1>
					<input type=text name='USM_$n' value='$w[NilaiUSM]' size=7 $ro>
					</td>
				  </tr>";
			}   
			 $rat =($tot>0)? number_format($tot/$x,2) : number_format(0,2);
			 $a .= "<tr>
				  <td class=wrn>Rata-rata</td>
				  <td class=ul1><input type=text name='rat_$pmb[$pil]' value='$rat' $ro 
				  size=7 ></td>
				  </tr>
				";
			$tot=0;
			$x = 0;			
	}			 
}
	return $a;
}

function AmbilTotalNilaiUSM($w) {
	global $koneksi;
  $s = "select ru.NilaiUSM from ruangusm ru left outer join prodiusm pu on ru.ProdiUSMID=pu.ProdiUSMID 
			where ru.KodeID='".KodeID."' and pu.NA='N' and pu.ProdiID='$w[Pilihan1]' and ru.PMBPeriodID='$w[PMBPeriodID]' order by PMBUSMID";
  $r = mysqli_query($koneksi, $s);
  
  $total = 0;
  while ($w = mysqli_fetch_array($r)) {
    $nilai = $w['NilaiUSM'];
    
	$total += $nilai;
  }
  return $total;
}

function HitungUlangUSM($n) {
  echo <<<SCR
  <script>
  function HitungUlangUSM() {
    var i = 0;
    var ttl = 0;
SCR;
  for ($i = 1; $i<= $n; $i++) {
    echo "ttl = ttl + Number(datalulus.USM_" . $i . ".value);\n";
  }
  echo <<<SCR
    datalulus.NilaiUjian.value = ttl;
  }
  </script>

SCR;
}

function TutupScript() {
echo <<<SCR
<SCRIPT>
  function ttutup() {
    opener.location='../index.php?ndelox=$_SESSION[ndelox]';
    self.close();
    return false;
  }
  ttutup();
</SCRIPT>
SCR;
}
?>
