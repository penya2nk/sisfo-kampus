<?php
error_reporting(0);
session_start();
include_once "../academic_sisfo1.php";

ViewHeaderApps("Jadwal Kuliah", 1);

echo <<<SCR
  <script src="../$_SESSION[ndelox].edit.script.js"></script>
SCR;

$_uasTahun = GainVariabelx('_uasTahun');
$_uasProdi = GainVariabelx('_uasProdi');
$_uasProg  = GainVariabelx('_uasProg');

$md = $_REQUEST['md']+0;
$id = $_REQUEST['id']+0;
$juasid = $_REQUEST['juasid']+0;

$lungo = (empty($_REQUEST['lungo']))? 'Edit' : $_REQUEST['lungo'];
$lungo($md, $id, $juasid);

function Edit($md, $id, $juasid) {
	global $koneksi;
  $w = AmbilFieldx('jadwal', 'JadwalID', $id, '*');
  $w['Dosen'] = AmbilOneField('dosen', "KodeID='".KodeID."' and Login", $w['DosenID'], 'Nama');
  $w['_JM'] = substr($w['JamMulai'], 0, 5);
  $w['_JS'] = substr($w['JamSelesai'], 0, 5);
  $prodi = AmbilFieldx('prodi', "ProdiID='$_SESSION[_uasProdi]' and KodeID", KodeID, "*");
  $tahun = AmbilFieldx('tahun', "TahunID='$_SESSION[_uasTahun]' and ProdiID='$_SESSION[_uasProdi]' and ProgramID='$_SESSION[_uasProg]' and KodeID", KodeID, "*");
  
  if ($md == 0) {
	$jdl = "Edit Jadwal UAS";
	$jadwaluas = AmbilFieldx('jadwaluas', 'JadwalUASID', $juasid, '*');
	$w['UASTanggal'] = $jadwaluas['Tanggal'];
	$w['UASJamMulai'] = substr($jadwaluas['JamMulai'], 0, 5);
	$w['UASJamSelesai'] = substr($jadwaluas['JamSelesai'], 0, 5);
	$w['UASDosenID'] = $jadwaluas['DosenID'];
	$w['UASDosen'] = AmbilOneField('dosen', "Login='$jadwaluas[DosenID]' and KodeID", KodeID, 'Nama');
	$w['UASRuangID'] = $jadwaluas['RuangID'];
	$w['UASKapasitas'] = $jadwaluas['Kapasitas'];
	$w['UASKolomUjian'] = $jadwaluas['KolomUjian'];
	$w['UASBarisUjian'] = ceil($jadwaluas['Kapasitas'] / $jadwaluas['KolomUjian']);
  }
  elseif ($md == 1) {
	$jdl = "Tambah Jadwal UAS";
	$w['UASTanggal'] = $tahun['TglUASMulai'];
	$w['UASJamMulai'] = '09:00';
	$w['UASJamSelesai'] = '09:50';
  }
  else {
	die(PesanError("Error", "Mode tidak dikenali")); 
  }
  // Parameters
  JdwlUASScript();

echo '
<link type="text/css" href="../datepicker2/datePicker.css" rel="stylesheet" />	
<script type="text/javascript" src="../datepicker2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../datepicker2/date-id.js"></script>
<!--[if IE]>
<script type="text/javascript" src="../datepicker2/jquery.bgiframe.js"></script>
<![endif]-->
<script type="text/javascript" src="../datepicker2/jquery.datePicker.js"></script>
';

$s2 = "select date_format(TglUASMulai, '%d')+0 as _fromday, date_format(TglUASMulai, '%m')+0 as _frommonth, date_format(TglUASMulai, '%Y')+0 as _fromyear,
	date_format(TglUASSelesai, '%d')+0 as _today, date_format(TglUASSelesai, '%m')+0 as _tomonth, date_format(TglUASSelesai, '%Y')+0 as _toyear
	from tahun where NA = 'N' and TahunID = '$_SESSION[_uasTahun]' and ProdiID = '$_SESSION[_uasProdi]' and ProgramID = '$_SESSION[_uasProg]'";
$q2 = mysqli_query($koneksi, $s2);
$w2 = (mysqli_fetch_array($q2));

$start = $w2[_fromyear].",".($w2[_frommonth]).",".$w2[_fromday];
$end = $w2[_toyear].','.($w2[_tomonth]).','.$w2[_today];


echo "
<script>
function setDatePicker(selector,rangeSelector,stat){
	var dt = $('#alt'+selector).val().replace('-',',');
	dt = dt.replace('-',',');
	
	$('#'+selector).datePicker({startDate:'01/01/1990'});
	$('#'+selector).datePicker().val(new Date(dt).asString()).trigger('change');
	$('#'+selector).dpSetPosition($.dpConst.POS_TOP, $.dpConst.POS_RIGHT);
	
	if (rangeSelector != ''){
		var dts = $('#'+rangeSelector).val().replace('-',',');
		dts = dts.replace('-',',');
		
		if (dts) {
			dts = new Date(dts);
			
			if (stat == 'end'){
				$('#'+selector).dpSetEndDate(dts.addDays(-1).asString());
			} else if (stat == 'start'){
				$('#'+selector).dpSetStartDate(dts.addDays(1).asString());
			}
			
		}
		
		// bind to event
		$('#'+rangeSelector).bind(
			'dpClosed',
			function(e, selectedDates)
			{
				var year = selectedDates[0].getFullYear();
				var mon = selectedDates[0].getMonth()+1;
				var day = selectedDates[0].getDate();
				var realvalue = year+'-'+mon+'-'+day;
				$('#alt'+rangeSelector).val(realvalue);
				
				var d = selectedDates[0];
				if (d) {
					d = new Date(d);
					if (stat == 'end'){
						$('#'+selector).dpSetEndDate(d.addDays(-1).asString());
					} else if (stat == 'start'){
						$('#'+selector).dpSetStartDate(d.addDays(1).asString());
					}
				}
			}
		)
	}	
}

$(function()
{
	Date.format = 'dd mmmm yyyy';
	setDatePicker('UASTanggal','UASTanggal','');
		var dts = '".$start."';
		if (dts) {
			dts = new Date(dts);
				$('#UASTanggal').dpSetStartDate(dts.addDays(0).asString());
			}
		var dts = '".$end."';
		if (dts) {
			dts = new Date(dts);
				$('#UASTanggal').dpSetEndDate(dts.addDays(0).asString());
			}
});
</script>";

function GetDateOption3($value,$name){
	$a = "<input type=hidden name=".$name." id=alt".$name." value=".$value." /><input type=text id=".$name." value=".$value." readonly=true />";
	return $a;
}

  //$opttgluas = GetDateOption3($w['UASTanggal'], 'UASTanggal');
  $opttgluas = AmbilComboTgl($w['UASTanggal'], 'UASTanggal');
  $optJamMulai = AmbilComboWaktu($w['UASJamMulai'], 'UASJamMulai');
  $optJamSelesai = AmbilComboWaktu($w['UASJamSelesai'], 'UASJamSelesai');
  $NamaHari = AmbilOneField('hari', 'HariID', $w['HariID'], 'Nama');
  // Tampilkan
  CheckFormScript("UASJamMulai,UASJamSelesai,UASRuangID");

echo '
  <script>
  	function cekJdwl(){
	
		var fromHour = document.forms[0].JamMulai_h.value;
		var fromMinutes = document.forms[0].JamMulai_n.value;

		var toHour = document.forms[0].JamSelesai_h.value;
		var toMinutes = document.forms[0].JamSelesai_n.value;
		
		var d4 = new Date();
		d4.setHours(fromHour);
		d4.setMinutes(fromMinutes);
		
		var fromJam = d4.getTime();
		
		var d5 = new Date();
		d5.setHours(toHour);
		d5.setMinutes(toMinutes);
		
		var toJam = d5.getTime();
		
		
		var errmsg = "";
		
		if (fromJam >= toJam){
			errmsg += "Jam ujian mulai harus lebih awal dari jam kuliah selesai\\n"
		}
		if (errmsg != ""){
			alert (errmsg);
			return false;
		}
	}
  </script>';
  
  

  TitleApps($jdl);
  echo <<<END
  <table class=bsc cellspacing=1 width=100%>
  <form name='frmJadwalUAS' action='../$_SESSION[ndelox].edit.php' method=POST onSubmit="return CheckForm(this)">
  <input type=hidden name='lungo' value='Simpan' />
  <input type=hidden name='md' value='$md' />
  <input type=hidden name='TahunID' value='$_SESSION[_uasTahun]' />
  <input type=hidden name='ProdiID' value='$_SESSION[_uasProdi]' />
  <input type=hidden name='ProgramID' value='$_SESSION[_uasProg]' />
  <input type=hidden name='id' value='$id' />
  <input type=hidden name='juasid' value='$juasid' />
  
  
  <tr><td class=inp>Program Studi:</td>
      <td class=ul1><b>$prodi[Nama]</b> <sup>($_SESSION[_uasProdi])</sup></td>
      <td class=inp>Program:</td>
      <td class=ul1>$w[ProgramID]</td>
      </tr>
  <tr><td class=inp>Tanggal Mulai Kuliah:</td>
      <td class=ul1>$w[KuliahTanggal] <sup>$NamaHari</sup></td>
	  <td class=inp>Jam Kuliah:</td>
      <td class=ul1>
        $w[_JM] &#8594; $w[_JS]
        </td>
      </tr>
  <tr><td class=inp>Ruang:</td>
      <td class=ul1>$w[RuangID]</td>
      <td class=inp>Kapasitas:</td>
      <td class=ul1>$w[Kapasitas]<sub>orang</sub></td>
      </tr>

  <tr><td class=inp>Matakuliah:</td>
      <td class=ul1 colspan=3 nowrap>$w[Nama] <sup>$w[MKKode]</sup></td>
      </tr>
  <tr><td class=inp>Dosen Pengajar:</td>
      <td class=ul1>$w[Dosen] <sup>$w[DosenID]</sup></td>
      <td class=inp>Kelas:</td>
      <td class=ul1>$w[NamaKelas]</td>
      </tr>
  <tr><td colspan=4><hr color=silver size=3></td></tr>
  <tr><td class=inp>Tanggal UAS:</td>
      <td class=ul1 nowrap>$opttgluas</td>
      <td class=inp>Jam UAS:</td>
      <td class=ul1 nowrap>
        $optJamMulai &#8594;
        $optJamSelesai
      </td></tr>
  <tr><td class=inp>Ruang UAS:</td>
      <td class=ul1>
        <input type=text name='UASRuangID' value='$w[UASRuangID]' size=10 maxlength=50 
          onKeyUp="javascript:CariRuang('$_SESSION[_uasProdi]', 'frmJadwalUAS')" />
        &raquo;
      <a href='#'
        onClick="javascript:CariRuang('$_SESSION[_uasProdi]', 'frmJadwalUAS')" />Cari...</a> |
      <a href='#' onClick="javascript:frmJadwalUAS.UASRuangID.value=''">Reset</a>
        </td>
      <td class=inp>Kapasitas:</td>
      <td class=ul1>
        <input type=text name='UASKapasitas' value='$w[UASKapasitas]' size=4 maxlength=5 />
        <sub>orang</sub>
        </td>
      </tr>
  <tr><td class=inp>Kolom Ujian:</td>
	  <td class=ul1><input type=text name='UASKolomUjian' value='$w[UASKolomUjian]' onChange="HitungBaris('frmJadwalUAS')" size=1 maxlength=2 />
	  <td class=inp>Baris Ujian:</td>
	  <td class=ul1><input type=text name='UASBarisUjian' value='$w[UASBarisUjian]' size=1 maxlength=2 disabled />
  </tr>
  
  <tr><td class=inp>Dosen Pengawas:</td>
      <td class=ul1 colspan=3 nowrap>
      <input type=text name='UASDosenID' value='$w[UASDosenID]' size=10 maxlength=50 />
      <input type=text name='UASDosen' value='$w[UASDosen]' size=30 maxlength=50 onKeyUp="javascript:CariDosen('$_SESSION[_uasProdi]', 'frmJadwalUAS')" />
      <div style='text-align:right'>
      &raquo;
      <a href='#'
        onClick="javascript:CariDosen('$_SESSION[_uasProdi]', 'frmJadwalUAS')" />Cari...</a> |
      <a href='#' onClick="javascript:frmJadwalUAS.UASDosenID.value='';frmJadwalUAS.UASDosen.value=''">Reset</a>
      </div>
      </td>
      </tr>
  
  <tr><td class=ul1 colspan=4 align=center>
      <input type=submit name='Simpan' value='Simpan' onclick='return cekJdwl()' />
      <input type=button name='Batal' value='Batal' onClick="window.close()" />
      </td></tr>
  </form>
  </table>
  
  <div class='box0' id='cariruang'></div>
  <div class='box0' id='caridosen'></div>
END;
}

function Simpan($md, $id, $juasid) {
global $koneksi;
  $w['UASTanggal'] = "$_REQUEST[UASTanggal_y]-$_REQUEST[UASTanggal_m]-$_REQUEST[UASTanggal_d]";
  $w['UASJamMulai'] = "$_REQUEST[UASJamMulai_h]:$_REQUEST[UASJamMulai_n]";
  $w['UASJamSelesai'] = "$_REQUEST[UASJamSelesai_h]:$_REQUEST[UASJamSelesai_n]";
  $w['UASRuangID'] = $_REQUEST['UASRuangID'];
  $w['UASKapasitas'] = $_REQUEST['UASKapasitas'];
  $w['UASKolomUjian'] = $_REQUEST['UASKolomUjian'];
  $w['UASDosenID'] = $_REQUEST['UASDosenID'];
  $w['JadwalID'] = $id;
  $w['TahunID'] = $_REQUEST['TahunID'];
  $w['ProdiID'] = $_REQUEST['ProdiID'];
  $w['ProgramID'] = $_REQUEST['ProgramID'];

  // *** Cek semuanya dulu ***
  $cekada = AmbilFieldx('tahun', "NA='N' and TahunID='$w[TahunID]' 
            and ProdiID = '$w[ProdiID]' 
            and left(TglUASMulai,10)<= '$w[UASTanggal]' and 
            left(TglUASSelesai,10)>= '$w[UASTanggal]' and ProgramID","$w[ProgramID]",
            "*");
  $cek = AmbilFieldx('tahun', "NA='N' and TahunID='$w[TahunID]' 
            and ProdiID = '$w[ProdiID]' and ProgramID","$w[ProgramID]",
            "date_format(TglUASMulai,'%d %M %Y') as Mulai, date_format(TglUASSelesai,'%d %M %Y') as Selesai");
  if(empty($cekada)){
    die(PesanError('Kesalahan Tanggal', 
      "Tanggal yang anda setting tidak sesuai dengan Tanggal penjadwalan UAS,<br/>
       yaitu dari Tanggal : <b>$cek[Mulai]</b> sampai dengan <b>$cek[Selesai]</b>.
      <hr size=1 color=silver />
      <p align=center>
      <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" />
      </p>"));
  }
  $oke = '';
  if (!empty($w['UASRuangID'])) $oke .= CekRuang($w, $juasid);
  //$oke .= CekTanggal($w, $juasid);
  
  // Ambil data MK
  $mk = AmbilFieldx('mk', "MKID", $w['MKID'], "Nama,MKKode,KurikulumID,SKS,Sesi");
  // Jika semuanya baik2 saja
  if (empty($oke)) {
    // Jika mode=edit
    if ($md == 0) {
      $s = "update jadwaluas
        set RuangID = '$w[UASRuangID]',
			DosenID = '$w[UASDosenID]',
            Kapasitas = '$w[UASKapasitas]',
            Tanggal = '$w[UASTanggal]',
            JamMulai = '$w[UASJamMulai]', JamSelesai = '$w[UASJamSelesai]',
			KolomUjian = '$w[UASKolomUjian]',
			TanggalEdit = now(),
            LoginEdit = '$_SESSION[_Login]'
        where JadwalUASID = '$juasid' ";
      $r = mysqli_query($s);
      
	  DaftarkanMhswKeRuangUAS($w, $juasid);
	  
	  TutupScript();
    }
    elseif ($md == 1) {
      $s = "insert into jadwaluas
        (KodeID, TahunID, JadwalID, DosenID, 
        Tanggal, JamMulai, JamSelesai,
		Kapasitas, RuangID, KolomUjian, TanggalBuat, LoginBuat)
        values
        ('".KodeID."', '$w[TahunID]', '$w[JadwalID]', '$w[UASDosenID]',
        '$w[UASTanggal]', '$w[UASJamMulai]', '$w[UASJamSelesai]',
		'$w[UASKapasitas]', '$w[UASRuangID]', '$w[UASKolomUjian]', now(), '$_SESSION[_Login]')";
      $r = mysqli_query($koneksi, $s);
	  
	  $JadwalUASID = mysql_insert_id();
	  
	  DaftarkanMhswKeRuangUAS($w, $JadwalUASID);
	  
      TutupScript();
    }
  }
  // Jika ada yg salah
  else {
	die(PesanError('Ada Kesalahan', 
      "Berikut adalah pesan kesalahannya: 
      <ol>$oke</ol>
      <hr size=1 color=silver />
      <p align=center>
      <input type=button name='Kembali' value='Kembali' onClick=\"javascript:Kembali()\" />
	  <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" />
      </p>"));
  }
}
function CekRuang($w, $JadwalUASID) {
  global $koneksi;
  $s = "select j.JadwalUASID, j.JamMulai, j.JamSelesai, j.DosenID, 
    d.Nama as NamaDosen, j.JadwalID, j2.Nama, j2.MKKode, j2.ProdiID, j2.ProgramID, 
	p.Nama as _PRG, pr.Nama as _PRD, date_format(j.Tanggal, '%d-%m-%y') as _Tanggal 
    from jadwaluas j
      left outer join dosen d on d.Login = j.DosenID and d.KodeID = '".KodeID."'
      left outer join jadwal j2 on j.JadwalID = j2.JadwalID and j2.KodeID = '".KodeID."'
	  left outer join program p on p.ProgramID = j2.ProgramID and p.KodeID = '".KodeID."'
      left outer join prodi pr on pr.ProdiID = j2.ProdiID and pr.KodeID = '".KodeID."'
    where j.TahunID = '$w[TahunID]'
      and j.RuangID = '$w[UASRuangID]'
      and j.Tanggal = '$w[UASTanggal]'
      and (('$w[UASJamMulai]:00' <= j.JamMulai and j.JamMulai <= '$w[UASJamSelesai]:59')
      or  ('$w[UASJamMulai]:00' <= j.JamSelesai and j.JamSelesai <= '$w[UASJamSelesai]:59'))
      and j.NA = 'N'
	  and j.KodeID='".KodeID."'
      and j.JadwalUASID <> '$JadwalUASID' ";
  //die("<pre>$s</pre>");
  $r = mysqli_query($koneksi, $s);
  $a = '';
  while ($w = mysqli_fetch_array($r)) {
    $a .= "<li>
      <b>Jadwal UAS bentrok dengan</b>:<br />
      <table class=bsc width=400>
      <tr><td class=inp width=100>Matakuliah:</td>
          <td class=ul1>$w[Nama] <sup>($w[MKKode])</td>
          </tr>
	  <tr><td class=inp>Tanggal:</td>
          <td class=ul1>$w[_Tanggal]</td>
          </tr>
      <tr><td class=inp>Jam:</td>
          <td class=ul1>$w[JamMulai] &minus; $w[JamSelesai]</td>
          </tr>
      <tr><td class=inp>Dosen:</td>
          <td class=ul1>$w[NamaDosen]</td>
          </tr>
      <tr><td class=inp>Program Studi:</td>
          <td class=ul1>$w[_PRD] <sup>($w[ProdiID])</sup></td>
          </tr>
      <tr><td class=inp>Prg Pendidikan:</td>
          <td class=ul1>$w[_PRG] <sup>($w[ProgramID])</sup></td>
          </tr>
      </table>
      </li>";
  }
  return $a;
}

function CekTanggal($w, $juasid)
{ $uas = AmbilFieldx('tahun', "TahunID='$w[TahunID]' and ProgramID='$w[ProgramID]' and ProdiID='$w[ProdiID]' and KodeID", KodeID, "LEFT(TglUASMulai, 10) as _TglUASMulai, LEFT(TglUASSelesai, 10) as _TglUASSelesai");

  $a = '';
  if( $uas['_TglUASMulai'] > $w['UASTanggal'] or $w['UASTanggal'] > $uas['_TglUASSelesai'])
  {
    $a .= "<li>
      <b>Tanggal UAS ".$w[UASTanggal]." berada di luar Tanggal UAS yang direncanakan</b>:<br />
		 Rentang waktu yang disediakan: ".$uas['_TglUASMulai']." s/d ".$uas['_TglUASSelesai']."<br />
      ";
  }
  return $a;
}
function DaftarkanMhswKeRuangUAS($w, $JadwalUASID)
{	
global $koneksi;
	if($JadwalUASID+0 != 0)
    {   
		$s2 = "select MhswID from krs where JadwalID='$w[JadwalID]' and KodeID='".KodeID."'";
		$r2 = mysqli_query($koneksi, $s2);
		$n2 = _num_rows($r2);
		echo "JUMLAH: $n2<br>";
		while($w2 = mysqli_fetch_array($r2))
		{   //Cek apakan mahasiswa sudah terdaftar
			$ada = AmbilOneField('uasmhsw', "MhswID='$w2[MhswID]' and JadwalUASID='$JadwalUASID' and KodeID", KodeID, "UASMhswID");
			if(empty($ada))
			{	$s1 = "insert into uasmhsw
				(KodeID, MhswID, JadwalUASID, TahunID, RuangID, TanggalBuat, LoginBuat)
				values
				('".KodeID."', '$w2[MhswID]', '$JadwalUASID', '$w[TahunID]', '$w[UASRuangID]', now(), '$_SESSION[_Login]')";
				$r1 = mysqli_query($koneksi, $s1);
			}
		}
	}
	else
	die(PesanError("Error", "Tidak ditemukan Jadwal UAS yang dimaksud. Harap menghubungi yang berwenang."));
}

function GetDateOption2($dt, $nm='dt',$loc='') {
  $arr = Explode('-', $dt);
  $_dy = AmbilComboNomor(1, 31, $arr[2]);
  $_mo = GetMonthOption($arr[1]);
  $_yr = AmbilComboNomor(1930, Date('Y')+2, $arr[0]);
  return "<select name='".$nm."_d' onChange=\"$loc\">$_dy</select>
    <select name='".$nm."_m' onChange=\"$loc\">$_mo</select>
    <select name='".$nm."_y' onChange=\"$loc\">$_yr</select>";
}
function TutupScript() {
echo <<<SCR
<SCRIPT>
  function ttutup() {
    opener.location='../index.php?ndelox=$_SESSION[ndelox]&lungo=';
    self.close();
    return false;
  }
  ttutup();
</SCRIPT>
SCR;
}
function JdwlUASScript() {
  echo <<<SCR
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
  function CariRuang(ProdiID, frm) {
    if (eval(frm + ".UASRuangID.value != ''")) {
      eval(frm + ".UASRuangID.focus()");
      showRuang(ProdiID, frm, eval(frm +".UASRuangID.value"), 'cariruang');
      toggleBox('cariruang', 1);
    }
  }
  function CariDosen(ProdiID, frm) {
    if (eval(frm + ".UASDosen.value != ''")) {
      eval(frm + ".UASDosen.focus()");
      showDosen(ProdiID, frm, eval(frm +".UASDosen.value"), 'caridosen');
      toggleBox('caridosen', 1);
    }
  }
  function HitungBaris(frm)
  {  var kapasitas, kolom;
	 if(eval(frm + ".UASKapasitas.value == ''")) kapasitas = 0;
	 else kapasitas = parseInt(eval(frm + ".UASKapasitas.value"));
	 if(eval(frm + ".UASKolomUjian.value != ''") && eval(frm + ".UASKolomUjian.value != 0")) 
	 {	kolom = parseInt(eval(frm + ".UASKolomUjian.value"));
		if(kolom != 0)
		{	baris = Math.ceil(kapasitas/kolom);
			eval(frm + ".UASBarisUjian.value = " + baris);
		}
	 }	 
	 else 
	 {	eval(frm + ".UASKolomUjian.value = 1");
		eval(frm + ".UASBarisUjian.value = " + kapasitas);
	 } 
  }
  </script>
SCR;
}
?>

</BODY>
</HTML>
