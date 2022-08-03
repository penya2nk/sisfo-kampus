<?php
// error_reporting(0);
session_start();
include_once "../academic_sisfo1.php";
ViewHeaderApps("Formulir");

$gel = sqling($_REQUEST['gel']);
$id = sqling($_REQUEST['id']);
$md = $_REQUEST['md']+0;


$lungo = (empty($_REQUEST['lungo']))? 'Edit' : $_REQUEST['lungo'];

$lungo($md, $gel, $id);


function GetOptionsFromData($sourceArray, $chosen)
	{	
			$optresult = "";
			if($chosen == '' or empty($chosen))	
			{ 	$optresult .= "<option value='' selected></option>"; }
			else { $optresult .= "<option value=''></option>"; }
			for($i=0; $i < count($sourceArray); $i++)
			{	if($chosen == $sourceArray[$i])
				{	$optresult .= "<option value='$sourceArray[$i]' selected>$sourceArray[$i]</option>"; }
				else
				{ 	$optresult .= "<option value='$sourceArray[$i]'>$sourceArray[$i]</option>"; }
			}
			return $optresult;
	}
	
function GetPilihan2($w, $da2, $da3) {
  $a = '';
  for ($i = 1; $i <= 3; $i++) {
    if($i == 1) $opt = AmbilCombo2('prodi', "concat(ProdiID, ' - ', Nama)", 'ProdiID', $w['Pilihan'.$i], "KodeID='".KodeID."'", 'ProdiID', 0, 0);
	else $opt = AmbilCombo2('prodi', "concat(ProdiID, ' - ', Nama)", 'ProdiID', $w['Pilihan'.$i], "KodeID='".KodeID."'", 'ProdiID');
    $da = ''; 
	$da = ($i == 2)? $da2 : $da;
	$da = ($i == 3)? $da3 : $da;
	$a .= "<tr>
      <td class=ul1>Pilihan $i:</td>
      <td class=ul1 colspan=3>
      <select name='Pilihan$i' $da>$opt</select>
      </td>
      </tr>";
  }
  return $a;
}

function CekSudahProsesNIM($pmb) {
  if (!empty($pmb['MhswID'])) {
    die(PesanError('Error',
      "Anda sudah tidak dapat mengubah data ini karena Cama sudah diproses menjadi mahasiswa.<br />
      Nomer Induk Mahasiswa-nya adalah: <b>$pmb[MhswID]</b>.<br />
      Hubungi Sysadmin atau Bagian Administrasi Akademik untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      <input type=button name='Tutup' value='Tutup Jendela Ini' onClick=\"window.close()\" />"));
  }
}

function Edit($md, $gel, $id) {
	global $koneksi;
  $sisfo = AmbilFieldx('identitas', 'Kode', KodeID, '*');
  if ($md == 0) {
    $jdl = 'Edit Data PMB';
    $w = AmbilFieldx('pmb', 'PMBID', $id, '*');
    CekSudahProsesNIM($w);
    $JumlahPilihan = AmbilOneField('pmbformulir', "PMBFormulirID = '$w[PMBFormulirID]' and KodeID", KodeID, 'JumlahPilihan');
	if(empty($JumlahPilihan)) $JumlahPilihan = 3;
	if($JumlahPilihan == 1) { $da2 = "disabled"; $da3 = "disabled"; }
	else if($JumlahPilihan == 2) { $da2 = ''; $da3 = "disabled"; }
	else { $da2 = ''; $da3 = ''; }
	$_PMBID = "<input type=hidden id='id' name='id' value='$w[PMBID]'><b>$w[PMBID]</b>";
  }
  elseif ($md == 1) {
    $jdl = 'Masukkan Data PMB';
    $w = array();
    $w['StatusAwalID'] = 'B';
    $w['TanggalLahir'] = date('Y-m-d');
    $_PMBID = "<font color=red>Auto-generated</font><input type=hidden id='id' name='id' value='$w[PMBID]'><b>$w[PMBID]</b>";
	$da2 = ''; $da3 = '';
  }
  else die(PesanError('Error',
    "Mode edit <b>$md</b> tidak dikenali.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" />"));
  // Parameters
  $TanggalLahir = AmbilComboTgl($w['TanggalLahir'], 'TanggalLahir');
  $optfrm = AmbilCombo2('pmbformulir', "concat(Nama, ' (', JumlahPilihan, ' pilihan)')", 'Nama', $w['PMBFormulirID'], "KodeID='".KodeID."'", 'PMBFormulirID');
  $optstawal = AmbilCombo2('statusawal', "concat(StatusAwalID, ' - ', Nama)",
    'StatusAwalID', $w['StatusAwalID'], '', 'StatusAwalID');
  $optkel = AmbilCombo2('kelamin', "concat(Kelamin, ' - ', Nama)", 'Kelamin', $w['Kelamin'], '', 'Kelamin');
  $optagm = AmbilCombo2('agama', "concat(Agama, ' - ', Nama)", 'Agama', $w['Agama'], '', 'Agama');
  $optagamaayah = AmbilCombo2('agama', "concat(Agama, ' - ', Nama)", 'Agama', $w['AgamaAyah'], '', 'Agama');
  $optagamaibu = AmbilCombo2('agama', "concat(Agama, ' - ', Nama)", 'Agama', $w['AgamaIbu'], '', 'Agama');
  $optsipil = AmbilCombo2('statussipil', "concat(StatusSipil, ' - ', Nama)", 'StatusSipil', $w['StatusSipil'], '', 'StatusSipil');
//   $optpendidikan = AmbilCombo2('pendidikanterakhir', "PendidikanTerakhir", 'Urutan', $w['PendidikanTerakhir'], '', 'PendidikanTerakhir');
//  $optpendidikanayah = AmbilCombo2('jenjang', "concat(JenjangID, '. ', Nama)", 'JenjangID', $w['PendidikanAyah'], '', 'JenjangID');
  $optpendidikanayah = AmbilCombo2('pendidikanortu', "concat(Pendidikan, '. ', Nama)", 'Pendidikan', $w['PendidikanAyah'], '', 'Pendidikan');
  $optpendidikanibu = AmbilCombo2('pendidikanortu', "concat(Pendidikan, '. ', Nama)", 'Pendidikan', $w['PendidikanIbu'], '', 'Pendidikan');
  $opthidupayah = AmbilCombo2('hidup', 'Nama', 'Hidup', $w['HidupAyah'], '', 'Hidup');
  $opthidupibu  = AmbilCombo2('hidup', 'Nama', 'Hidup', $w['HidupIbu'], '', 'Hidup');
  $optprg = AmbilCombo2('program', "concat(ProgramID, ' - ', Nama)", 'ProgramID', $w['ProgramID'], "KodeID='".KodeID."'", 'ProgramID');
  $optwarganegara = AmbilCombo2('warganegara', "WargaNegara", 'WargaNegara', $w['WargaNegara'], '', 'WargaNegara');
  $Pilihan2 = GetPilihan2($w, $da2, $da3);
  $optpenghasilanayah = AmbilCombo2('penghasilanortu', "Nama", 'PenghasilanOrtuID', $w['PenghasilanAyah'], '', 'Nama');
  $optpenghasilanibu = AmbilCombo2('penghasilanortu', "Nama", 'PenghasilanOrtuID', $w['PenghasilanIbu'], '', 'Nama');
  $opttempattinggal = AmbilCombo2('tempattinggal', "Nama", 'TempatTinggalID', $w['TempatTinggal'], '', ''); 
  $optbiayastudi = AmbilCombo2('biayastudi', "Nama", 'BiayaStudiID', $w['BiayaStudi'], '', '');
  $arrPrestasiTambahan = explode('~', $w['PrestasiTambahan']);
  $NamaSekolah = AmbilOneField('asalsekolah', 'SekolahID', $w['AsalSekolah'], "concat(Nama, ', ', Kota)");
  
  $opttempatkuliah = AmbilCombo2('tempatkuliah', 'Nama', 'TempatKuliahID', $w['TempatKuliahID'], '', 'Nama', 0, 0);
  $s1 = "select PMBFormulirID, JumlahPilihan from pmbformulir where KodeID='".KodeID."' and NA='N'";
  $r1 = mysqli_query($koneksi, $s1);
  while($w1 = mysqli_fetch_array($r1))
  {	$hiddenformdata .= "<input type=hidden id='Form$w1[PMBFormulirID]' name='Form$w1[PMBFormulirID]' value='$w1[JumlahPilihan]' />";
  }
  // Tampilkan
  TitleApps($jdl);
  
  echo "<script>
	function carinomor(frm)
	{	
		temp = frm.AplikanID.value;
		
		if(temp!='')
		{
				id = document.getElementById('id').value;
				lnk = '../$_SESSION[ndelox].isi.cari.php?gel='+frmisi.gel.value+'&id='+id+'&n='+temp;
				win2 = window.open(lnk, '', 'width=700, left=500, top=150, height=600, scrollbars, status');
				win2.creator = self;
		}
		else
		{	alert('Masukkan nomor atau nama aplikan terlebih dahulu');
		}
	}
	
	function carisekolah(frm){
		lnk = 'carisekolah.php?SekolahID='+frm.AsalSekolah.value+'&Cari='+frm.NamaSekolah.value;
		win2 = window.open(lnk, '', 'width=400, left=600, top=150, height=300, scrollbars, status');
		win2.creator = frm;
	}
	function caript(frm){
		lnk = 'cariperguruantinggi.php?SekolahID='+frm.AsalSekolah.value+'&Cari='+frm.NamaSekolah.value;
		win2 = window.open(lnk, '', 'width=400, left=600, top=150, height=300, scrollbars, status');
		win2.creator = frm;
	}
	function tambahsekolah(frm) {
		lnk = 'penmabaasalsek.edit.php?md=1';
		win2 = window.open(lnk, '', 'width=400, left=600, top=150, height=300, scrollbars, status');
		win2.creator = self;
	}
	function tambahpt(frm) {
		lnk = 'penmabaasalpt.edit.php?md=1';
		win2 = window.open(lnk, '', 'width=400, left=600, top=150, height=300, scrollbars, status');
		win2.creator = self;
    }
  </script>
  ";
  
  CheckFormScript("Nama,TempatLahir,Kelamin,StatusAwalID, ProgramID, PMBFormulirID");
  JumlahPilihanScript();
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table class=bsc cellspacing=1 width=100%>
  <form name='frmisi' id='frmisi' action='../$_SESSION[ndelox].isi.php' method=POST onSubmit=\"return CheckForm(this)\">
  <input type=hidden name='gel' value='$gel' />
  <input type=hidden name='md' value='$md' />
  <input type=hidden name='lungo' value='Simpan' />
  $hiddenformdata
  
  <tr>
      <td class=ul1>No. Pendaftaran:</td>
      <td class=ul1>$_PMBID</td>
      <td class=ul1>Status Cama:</td>
      <td class=ul1><select name='StatusAwalID'>$optstawal</select></td>
  </tr>
  <tr>
	  <td class=ul1>No. Aplikan: </td>
	  <td class=ul1 colspan=3><input type=text id='aplikan' name='AplikanID' value='$w[AplikanID]'>
							  <input type=button name='CariAplikan' value='Cari Aplikan'
									onClick=\"carinomor(frmisi)\"/>
	  <br>
	  <font color=red>*) Dapat dicari melalui nomor aplikan atau nama aplikan.</font>
	  </td>
  </tr>
  
  <tr style='background:purple;color:white'><th class=ttl colspan=4>Pilihan Program Studi</th></tr>
  <tr>
      <td class=ul1>Formulir:</td>
      <td class=ul1 colspan=3>
        <select name='PMBFormulirID' onChange=\"UbahJumlahPilihan()\">$optfrm</select > <br />
        </td>
      </tr>
  <tr><td class=ul1>Program Pendidikan:</td>
      <td class=ul1 colspan=3>
      <select name='ProgramID'>$optprg</select>
      </td></tr>	  
  $Pilihan2
  <tr><td class=ul1>Pilihan Tempat Kuliah:</td>
	  <td class=ul1 colspan=3>
	  <select name='TempatKuliahID'>$opttempatkuliah</select>
	  </td></tr>
  
  <tr style='background:purple;color:white'><th class=ttl colspan=4>Data Pribadi Cama</th></tr>
  <tr><td class=ul1>Nama Lengkap:</td>
      <td class=ul1 colspan=3>
      <input type=text name='Nama' value='$w[Nama]' size=30 maxlength=50 />
      </td></tr>
  <tr><td class=ul1>Warga Negara:</td>
      <td class=ul1><select name='WargaNegara'>$optwarganegara</select></td>
      <td class=ul1>Kebangsaan:</td>
      <td class=ul1><input type=text name='Kebangsaan' value='$w[Kebangsaan]' size=20 maxlength=50 /></td>
      </tr>
  <tr><td class=ul1>Tanggal Lahir:</td>
      <td class=ul1>$TanggalLahir</td>
	  <td class=ul1>Tempat Lahir:</td>
      <td class=ul1><input type=text name='TempatLahir' value='$w[TempatLahir]' size=20 maxlength=50 /></td>
      </tr>
  <tr><td class=ul1>Jenis Kelamin:</td>
      <td class=ul1><select name='Kelamin'>$optkel</select></td>
      <td class=ul1>Golongan Darah:</td>
      <td class=ul1><input style='text-transform: uppercase' type=text name='GolonganDarah' value='$w[GolonganDarah]' size=5 maxlength=10/></td>
      </tr>
  <tr><td class=ul1>Agama:</td>
      <td class=ul1><select name='Agama'>$optagm</select></td>
      <td class=ul1>Status Perkawinan:</td>
      <td class=ul1><select name='StatusSipil'>$optsipil</select></td>
      </tr>
  <tr><td class=ul1>Tinggi Badan:</td>
      <td class=ul1><input type=text name='TinggiBadan' value='$w[TinggiBadan]' size=3 maxlength=5 /> cm</td>
      <td class=ul1>Berat Badan:</td>
      <td class=ul1><input type=text name='BeratBadan' value='$w[BeratBadan]' size=3 maxlength=5 /> kg</td>
      </tr>
  <tr><td>&nbsp;</td></tr>
  <tr><td class=ul1 colspan=4 align=center>
      <input type=submit name='Simpan' value='Simpan Cepat' />
      <input type=button name='Batal' value='Batal' onClick=\"window.close()\" />
      </td></tr>
  <tr><td>&nbsp;</td></tr>
  <tr style='background:purple;color:white'><th class=ttl colspan=4>Pendidikan Terakhir:</th></tr>
  <tr><td class=ul1>Pendidikan Terakhir:</td>
      <td class=ul1><select name='PendidikanTerakhir'>$optpendidikan</select></td>
	  <td class=ul1>Jurusan Sekolah :</td>
      <td class=ul1>
      <input type=text name='JurusanSekolah' value='$w[JurusanSekolah]' size=40 maxlength=50 />
      </td>
  </tr>
  <tr><td class=ul1>ID Sekolah :</td>
      <td class=ul1>
		<input type='text' name='AsalSekolah' value='$w[AsalSekolah]' size=20 maxlength=50 disabled>
		<input type='hidden' name='SavAsalSekolah' value='$w[AsalSekolah]' ></td>
	  </tr>
  <tr><td class=ul1>Nama Sekolah :</td>
	  <td class=ul1 colspan=3><input type=text name='NamaSekolah' value='$NamaSekolah' size=50 maxlength=255><br>
	    <font size=0.8>Tips</font><span>Masukkan <b>nama sekolah parsial</b> diikuti dengan <b>tanda koma</b> dan <b>nama kota</b> di mana sekolah terebut berada. <br \>
					  Contoh: 'Budi Utomo, Jakarta' ATAU 'Negeri 1, Surabaya'.<br \><br \>
					  <b>TIPS: Hindari penggunaan kata-kata umum</b>, seperti SMA, SMU, SLTA, SEKOLAH, SCHOOL, dll. <br \>
					  Gunakan kata-kata yang dapat mengidentifikasi sekolah yang dicari secara unik.</span></a>
		<br><a href='#self' onClick=\"javascript:frmisi.AsalSekolah.value='';frmisi.NamaSekolah.value='';\">Reset</a> &bull;
		<a href='javascript:carisekolah(frmisi)'>Cari Sekolah</a> &bull; 
		<a href='javascript:caript(frmisi)'>Cari Perguruan Tinggi</a> &bull; 
		<a href='javascript:tambahsekolah(frmisi)'>Tambah Sekolah</a> &bull;
		<a href='javascript:tambahpt(frmisi)'>Tambah Perguruan Tinggi</a>
		</td></tr>
  </tr>
  <tr><td class=ul1>Alamat Sekolah :</td>
	  <td class=ul1 colspan=3><input type=text name='AlamatSekolah' value='$w[AlamatSekolah]' size=50 maxlength=255></td>
	  </tr>
	
  <tr><td class=ul1>Tahun Lulus :</td>
      <td class=ul1>
      <input type=text name='TahunLulus' value='$w[TahunLulus]' size=5 maxlength=5 />
      </td>
	  <td class=ul1>Nilai UAN :</td>
      <td class=ul1>
      <input type=text name='NilaiSekolah' value='$w[NilaiSekolah]' size=5 maxlength=5 />
      </td>
      </tr>
  <tr><td class=ul1>Prestasi Lainnya:</td>
	  <td class=ul1 colspan=3><input type=text name='PrestasiTambahan1' value='$arrPrestasiTambahan[0]' size=80 maxlength=100 ></td>
  </tr>
  <tr><td></td>
	  <td class=ul1 colspan=3><input type=text name='PrestasiTambahan2' value='$arrPrestasiTambahan[1]' size=80 maxlength=100></td>
  </tr>
  <tr><td></td>
      <td class=ul1 colspan=3><input type=text name='PrestasiTambahan3' value='$arrPrestasiTambahan[2]' size=80 maxlength=100></br>
	  *) <i>Prestasi dapat berupa prestasi apa saja di bidang <b>seni</b>, <b>olahraga</b>, atau <b>akademik</b>. </i></br>
	  *) <i>Harap menuliskan <b>peringkat</b>, <b>nama lengkap kompetisi/event</b> dan <b>tahun</b> peraihan prestasi bila memungkinkan<i> 
	  </td>
  </tr>
  
  <tr style='background:purple;color:white'><th class=ttl colspan=4>Alamat</th></tr>
  <tr><td class=ul1>Tempat Tinggal:</td>
	  <td class=ul1 colspan=3><select name='TempatTinggal'>$opttempattinggal</select></td></tr>
  <tr><td class=ul1>Alamat lengkap:</td>
      <td class=ul1 colspan=3>
      <textarea name='Alamat' cols=70 rows=4>$w[Alamat]</textarea>
      </td></tr>
  <tr><td class=ul1>RT/RW:</td>
      <td class=ul1><input type=text name='RT' value='$w[RT]' size=10 maxlength=10 />
      / <input type=text name='RW' value='$w[RW]' size=10 maxlength=10 /></td>
      <td class=ul1>Kode Pos:</td>
      <td class=ul1><input type=text name='KodePos' value='$w[KodePos]' size=10 maxlength=10 /></td>
      </tr>
  <tr><td class=ul1>Kota/Kabupaten:</td>
      <td class=ul1><input type=text name='Kota' value='$w[Kota]' size=20 maxlength=50 /></td>
      <td class=ul1>Propinsi:</td>
      <td class=ul1><input type=text name='Propinsi' value='$w[Propinsi]' size=20 maxlength=50 /></td>
      </tr>
  <tr><td class=ul1>Telpon/ponsel:</td>
      <td class=ul1>
        <input type=text name='Telepon' value='$w[Telepon]' size=10 maxlength=50 /> /
        <input type=text name='Handphone' value='$w[Handphone]' size=10 maxlength=50 />
      </td>
      <td class=ul1>NIK:</td>
      <td class=ul1>
        <input type=text name='Email' value='$w[Email]' size=20 maxlength=50 />
      </td></tr>
  
  <tr style='background:purple;color:white'><th class=ttl colspan=4>Data Orangtua</th></tr>
  <tr><td class=ul1>Nama Ayah:</td>
      <td class=ul1><input type=text name='NamaAyah' value='$w[NamaAyah]' size=20 maxlength=50 /></td>
      <td class=ul1>Nama Ibu:</td>
      <td class=ul1><input type=text name='NamaIbu' value='$w[NamaIbu]' size=20 maxlength=50 /></td>
      </tr>
  <tr><td class=ul1>Agama Ayah:</td>
      <td class=ul1><select name='AgamaAyah' />$optagamaayah</td>
      <td class=ul1>Agama Ibu:</td>
      <td class=ul1><select name='AgamaIbu' />$optagamaibu</td>
      </tr>
  <tr><td class=ul1>Pendidikan Ayah:</td>
      <td class=ul1><select name='PendidikanAyah'>$optpendidikanayah</select></td>
      <td class=ul1>Pendidikan Ibu:</td>
      <td class=ul1><select name='PendidikanIbu'>$optpendidikanibu</select></td>
      </tr>
  <tr><td class=ul1>Pekerjaan Ayah:</td>
      <td class=ul1><input type=text name='PekerjaanAyah' value='$w[PekerjaanAyah]' size=20 maxlength=50 /></td>
      <td class=ul1>Pekerjaan Ibu:</td>
      <td class=ul1><input type=text name='PekerjaanIbu' value='$w[PekerjaanIbu]' size=20 maxlength=50 /></td>
      </tr>
  <tr><td class=ul1>Penghasilan/bulan:</td>
      <td class=ul1><select name='PenghasilanAyah'>$optpenghasilanayah</select></td>
      <td class=ul1>Penghasilan/bulan:</td>
      <td class=ul1><select name='PenghasilanIbu'>$optpenghasilanibu</select></td>
      </tr>
  <tr><td class=ul1>Status Kehidupan:</td>
      <td class=ul1><select name='HidupAyah'>$opthidupayah</select></td>
      <td class=ul1>Status kehidupan:</td>
      <td class=ul1><select name='HidupIbu'>$opthidupibu</select></td>
      </tr>
  <tr><td class=ul1>Biaya Studi:</td>
	  <td class=ul1 colspan=3><select name='BiayaStudi'>$optbiayastudi</select></td>
	  </tr>
  
  <tr style='background:purple;color:white'><th class=ttl colspan=4>Alamat Orangtua</th></tr>
  <tr><td class=ul1>Alamat lengkap:</td>
      <td class=ul1 colspan=3>
      <textarea name='AlamatOrtu' cols=70 rows=4>$w[AlamatOrtu]</textarea>
      </td></tr>
  <tr><td class=ul1>RT/RW:</td>
      <td class=ul1><input type=text name='RTOrtu' value='$w[RTOrtu]' size=10 maxlength=10 />
      / <input type=text name='RWOrtu' value='$w[RWOrtu]' size=10 maxlength=10 /></td>
      <td class=ul1>Kode Pos:</td>
      <td class=ul1><input type=text name='KodePosOrtu' value='$w[KodePosOrtu]' size=10 maxlength=10 /></td>
      </tr>
  <tr><td class=ul1>Kota:</td>
      <td class=ul1><input type=text name='KotaOrtu' value='$w[KotaOrtu]' size=20 maxlength=50 /></td>
      <td class=ul1>Propinsi:</td>
      <td class=ul1><input type=text name='PropinsiOrtu' value='$w[PropinsiOrtu]' size=20 maxlength=50 /></td>
      </tr>
  <tr><td class=ul1>Telpon/ponsel:</td>
      <td class=ul1>
        <input type=text name='TeleponOrtu' value='$w[TeleponOrtu]' size=10 maxlength=50 /> /
        <input type=text name='HandphoneOrtu' value='$w[HandphoneOrtu]' size=10 maxlength=50 />
      </td>
      <td class=ul1>E-mail:</td>
      <td class=ul1>
        <input type=text name='EmailOrtu' value='$w[EmailOrtu]' size=20 maxlength=50 />
      </td></tr>
  
  <tr style='background:purple;color:white'><th class=ttl colspan=4>Detail Pekerjaan (Jika sudah bekerja)</th></tr>
  <tr><td class=ul1>Nama Perusahaan:</td>
      <td class=ul1 colspan=3>
      <input type=text name='NamaPerusahaan' value='$w[NamaPerusahaan]' size=30/></td></tr>
  <tr><td class=ul1>Alamat Perusahaan:</td>
      <td class=ul1 colspan=3><input type=text name='AlamatPerusahaan' value='$w[AlamatPerusahaan]' size=50/></td>
      </tr>
  <tr><td class=ul1>No Telp. dan Fax</td>
      <td class=ul1><input type=text name='TeleponPerusahaan' value='$w[TeleponPerusahaan]' size=30/></td>
      </tr>
  <tr><td class=ul1>Jabatan Saat Ini:</td>
      <td class=ul1><input type=text name='JabatanPerusahaan' value='$w[JabatanPerusahaan]' size=20s></td>
      </tr>
  
  
  <tr><td class=ul1 colspan=4 align=center>
      <input type=submit name='Simpan' value='Simpan' />
      <input type=button name='Batal' value='Batal' onClick=\"window.close()\" />
      </td></tr>
  
  </form>
  </table></div>
  </div>
  </div>";
}

function Simpan($md, $gel, $id)
{
	global $koneksi;
  include_once "statusaplikan.lib.php";
  $AplikanID = $_REQUEST['AplikanID'];
  
	if($AplikanID == '' or empty($AplikanID))
	{	die(PesanError('Error',
			  "Nomer Aplikan ID kosong<br />
			  Anda harus memasukkan nomer AplikanID yang benar.<br />
			  Hubungi Sysadmin untuk informasi lebih lanjut.
			  <hr size=1 color=silver />
			  Opsi: <input type=button name='Kembali' value='Kembali'
				onClick=\"javascript:history.go(-1)\" />
				<input type=button name='Tutup' value='Tutup'
				onClick=\"window.close()\" />"));
	}
	else
	{ $sss = "select Nama from aplikan where AplikanID='$AplikanID'";
	  $rrr = mysqli_query($koneksi, $sss);
	  $nnn = mysqli_num_rows($rrr);
	  
	  if($nnn == 0)
	  {	die(PesanError('Error',
			  "Nomer Aplikan ID tidak diketemukan di dalam database<br />
			  Anda harus memasukkan nomer AplikanID yang benar.<br />
			  Hubungi Sysadmin untuk informasi lebih lanjut.
			  <hr size=1 color=silver />
			  Opsi: <input type=button name='Kembali' value='Kembali'
				onClick=\"javascript:history.go(-1)\" />
				<input type=button name='Tutup' value='Tutup'
				onClick=\"window.close()\" />"));
	  }
	  else if($nnn > 1)
	  {	die(PesanError('Error',
			  "Ditemukan Aplikan ID yang dobel. Harap dicek terlebih dahulu<br />
			  Hubungi Sysadmin untuk informasi lebih lanjut.
			  <hr size=1 color=silver />
			  Opsi: <input type=button name='Kembali' value='Kembali'
				onClick=\"javascript:history.go(-1)\" />
				<input type=button name='Tutup' value='Tutup'
				onClick=\"window.close()\" />"));
	  
	  }
	  else
	  {
  
	  $Nama = sqling($_REQUEST['Nama']);
	  $StatusAwalID = sqling($_REQUEST['StatusAwalID']);
	  $TempatLahir = sqling($_REQUEST['TempatLahir']);
	  $Kelamin = sqling($_REQUEST['Kelamin']);
	  $TanggalLahir = "$_REQUEST[TanggalLahir_y]-$_REQUEST[TanggalLahir_m]-$_REQUEST[TanggalLahir_d]";
	  $GolonganDarah = sqling($_REQUEST['GolonganDarah']);
	  $Agama = sqling($_REQUEST['Agama']);
	  $StatusSipil = sqling($_REQUEST['StatusSipil']);
	  $TinggiBadan = sqling($_REQUEST['TinggiBadan']);
	  $BeratBadan = sqling($_REQUEST['BeratBadan']);
	  $WargaNegara = sqling($_REQUEST['WargaNegara']);
	  $Kebangsaan = sqling($_REQUEST['Kebangsaan']);
	  $TempatTinggal = $_REQUEST['TempatTinggal'];
	  $Alamat = sqling($_REQUEST['Alamat']);
	  $RT = sqling($_REQUEST['RT']);
	  $RW = sqling($_REQUEST['RW']);
	  $KodePos = sqling($_REQUEST['KodePos']);
	  $Kota = sqling($_REQUEST['Kota']);
	  $Propinsi = sqling($_REQUEST['Propinsi']);
	  $Telepon = sqling($_REQUEST['Telepon']);
	  $Handphone = sqling($_REQUEST['Handphone']);
	  $Email = sqling($_REQUEST['Email']);
	  $JarakRumah = $_REQUEST['JarakRumah'];
	  $PendidikanTerakhir = sqling($_REQUEST['PendidikanTerakhir']);
	  $AsalSekolah = sqling($_REQUEST['SavAsalSekolah']);
	  $AlamatSekolah = $_REQUEST['AlamatSekolah'];
	  $JurusanSekolah = $_REQUEST['JurusanSekolah'];
	  $TahunLulus = sqling($_REQUEST['TahunLulus']);
	  $NilaiSekolah = sqling($_REQUEST['NilaiSekolah']);
	  $arrPT = array();
	  for($i = 1; $i <= 3; $i++)
	  {	$arrPT[] = str_replace('~', '-', sqling($_REQUEST['PrestasiTambahan'.$i]));
	  }
	  foreach($arrPT as $PT)
	  {	$PrestasiTambahan = implode('~', $arrPT);
	  }
	  $NamaAyah = sqling($_REQUEST['NamaAyah']);
	  $AgamaAyah = $_REQUEST['AgamaAyah'];
	  $PendidikanAyah = $_REQUEST['PendidikanAyah'];
	  $PekerjaanAyah = sqling($_REQUEST['PekerjaanAyah']);
	  $AlamatAyah = sqling($_REQUEST['AlamatAyah']);
	  $PenghasilanAyah = $_REQUEST['PenghasilanAyah']+0;
	  $HidupAyah = $_REQUEST['HidupAyah'];
	  
	  $NamaIbu = sqling($_REQUEST['NamaIbu']);
	  $AgamaIbu = $_REQUEST['AgamaIbu'];
	  $PendidikanIbu = $_REQUEST['PendidikanIbu'];
	  $PekerjaanIbu = sqling($_REQUEST['PekerjaanIbu']);
	  $AlamatIbu = sqling($_REQUEST['AlamatIbu']);
	  $PenghasilanIbu = $_REQUEST['PenghasilanIbu']+0;
	  $HidupIbu = $_REQUEST['HidupIbu'];
	  $BiayaStudi = $_REQUEST['BiayaStudi'];
	  
	  $AlamatOrtu = sqling($_REQUEST['AlamatOrtu']);
	  $RTOrtu = sqling($_REQUEST['RTOrtu']);
	  $RWOrtu = sqling($_REQUEST['RWOrtu']);
	  $KodePosOrtu = sqling($_REQUEST['KodePosOrtu']);
	  $KotaOrtu = sqling($_REQUEST['KotaOrtu']);
	  $PropinsiOrtu = sqling($_REQUEST['PropinsiOrtu']);
	  $TeleponOrtu = sqling($_REQUEST['TeleponOrtu']);
	  $HandphoneOrtu = sqling($_REQUEST['HandphoneOrtu']);
	  $EmailOrtu = sqling($_REQUEST['EmailOrtu']);
	  
	  $NamaPerusahaan = $_REQUEST['NamaPerusahaan'];
	  $AlamatPerusahaan = $_REQUEST['AlamatPerusahaan'];
	  $TeleponPerusahaan = $_REQUEST['TeleponPerusahaan'];
	  $JabatanPerusahaan = $_REQUEST['JabatanPerusahaan'];
	  
	  $PMBFormulirID = $_REQUEST['PMBFormulirID'];
	  $ProgramID = sqling($_REQUEST['ProgramID']);
//	  $ProdiID = sqling($_REQUEST['ProdiID']);
	  $TempatKuliahID = $_REQUEST['TempatKuliahID'];
	  
	  $frm = AmbilFieldx('pmbformulir', 'PMBFormulirID', $PMBFormulirID, '*');
	  $pil = array();
	  $vpil = array();
	  $epil = array();
	  for ($i = 1; $i <= $frm['JumlahPilihan']; $i++) {
		$pil[] = 'Pilihan'.$i;
		$vpil[] = "'".sqling($_REQUEST['Pilihan'.$i])."'";
		$epil[] = 'Pilihan'.$i."='".$_REQUEST['Pilihan'.$i]."'";
	  }
	  $_pil = implode(', ', $pil);
	  $_vpil = implode(', ', $vpil);
	  $_epil = implode(', ', $epil);
	  
	  TutupScript();
	  // simpan
	  if ($md == 0) {
		$s = "update pmb
		  set StatusAwalID = '$StatusAwalID',
			  Nama = '$Nama',
			  TempatLahir = '$TempatLahir', TanggalLahir = '$TanggalLahir',
			  Kelamin = '$Kelamin', GolonganDarah = '$GolonganDarah',
			  Agama = '$Agama', StatusSipil = '$StatusSipil',
			  TinggiBadan = '$TinggiBadan', BeratBadan = '$BeratBadan',
			  WargaNegara = '$WargaNegara', Kebangsaan = '$Kebangsaan',
			  TempatTinggal = '$TempatTinggal', Alamat = '$Alamat',
			  RT = '$RT', RW = '$RW', KodePos = '$KodePos',
			  Kota = '$Kota', Propinsi = '$Propinsi',
			  Telepon = '$Telepon', Handphone = '$Handphone', Email = '$Email',
			  PendidikanTerakhir = '$PendidikanTerakhir',
			  AsalSekolah = '$AsalSekolah', AlamatSekolah = '$AlamatSekolah', TahunLulus = '$TahunLulus', 
			  NilaiSekolah = '$NilaiSekolah', PrestasiTambahan = '$PrestasiTambahan',
			  NamaAyah = '$NamaAyah', AgamaAyah = '$AgamaAyah', PendidikanAyah = '$PendidikanAyah', 
			  PekerjaanAyah = '$PekerjaanAyah', HidupAyah = '$HidupAyah', PenghasilanAyah = '$PenghasilanAyah',
			  NamaIbu = '$NamaIbu', AgamaIbu = '$AgamaIbu', PendidikanIbu = '$PendidikanIbu', 
			  PekerjaanIbu = '$PekerjaanIbu', HidupIbu = '$HidupIbu', PenghasilanIbu = '$PenghasilanIbu',
			  BiayaStudi = '$BiayaStudi',
			  
			  AlamatOrtu = '$AlamatOrtu',
			  RTOrtu = '$RTOrtu', RWOrtu = '$RWOrtu', KodePosOrtu = '$KodePosOrtu',
			  KotaOrtu = '$KotaOrtu', PropinsiOrtu = '$PropinsiOrtu',
			  TeleponOrtu = '$TeleponOrtu', HandphoneOrtu = '$HandphoneOrtu', EmailOrtu = '$EmailOrtu',
			  PMBFormulirID = '$PMBFormulirID', ProgramID = '$ProgramID', TempatKuliahID='$TempatKuliahID',
			  
			  NamaPerusahaan = '$NamaPerusahaan', AlamatPerusahaan = '$AlamatPerusahaan',
			  TeleponPerusahaan = '$TeleponPerusahaan', JabatanPerusahaan = '$JabatanPerusahaan',
			  
			  $_epil,
			  LoginEdit = '$_SESSION[_Login]', TanggalEdit = now()
		  where PMBID = '$id' ";
		$r = mysqli_query($koneksi, $s);
		
		SetStatusAplikan('DFT', $AplikanID, $gel);
		
		echo "<script>ttutup()</script>";
	  }
	  elseif ($md == 1) {
		// Cek jika ID manual
		if (!empty($id)) {
		  $ada = AmbilOneField('pmb', "KodeID='".KodeID."' and PMBID", $id, 'PMBID');
		  if (!empty($ada)) {
			die(PesanError('Error',
			  "Nomer PMB sudah ada.<br />
			  Anda harus memasukkan nomer PMB yang lain.<br />
			  Atau kosongkan untuk mendapatkan nomer secara otomatis.<br />
			  Hubungi Sysadmin untuk informasi lebih lanjut.
			  <hr size=1 color=silver />
			  Opsi: <input type=button name='Kembali' value='Kembali'
				onClick=\"javascript:history.go(-1)\" />
				<input type=button name='Tutup' value='Tutup'
				onClick=\"window.close()\" />"));
		  }
		}
		// Jika menggunakan penomeran otomatis
		else {
		  $id = GetNextPMBIDFromGel($gel);
		}
		
		$FormulirID = AmbilOneField('aplikan', 'AplikanID', $AplikanID, 'PMBFormulirID');
		
		if(empty($FormulirID) or $FormulirID == '' or $FormulirID == NULL)
		{	echo Info("Gagal", "Aplikan belum membeli formulir.<br> Data tidak disimpan. <br>
								<input type=button name='Kembali' value='Kembali'
									onClick=\"javascript:history.go(-1)\" />");
		}
		else
		{
			// Baru kemudian disimpan
			$PMBFormJualID = AmbilOneField('aplikan', 'AplikanID', $AplikanID, 'PMBFormJualID');
			$ProdiID = $_REQUEST['Pilihan1'];
			$s = "insert into pmb
			  (PMBID, AplikanID, PMBPeriodID, KodeID, StatusAwalID, Nama, 
			  TempatLahir, TanggalLahir, Kelamin, GolonganDarah,
			  Agama, StatusSipil, TinggiBadan, BeratBadan,
			  WargaNegara, Kebangsaan,
			  TempatTinggal, Alamat, RT, RW, KodePos, Kota, Propinsi, 
			  Telepon, Handphone, Email,
			  PendidikanTerakhir, AsalSekolah, AlamatSekolah, 
			  TahunLulus, NilaiSekolah, PrestasiTambahan, 
			  NamaAyah, AgamaAyah, PendidikanAyah, PekerjaanAyah, HidupAyah, PenghasilanAyah,
			  NamaIbu, AgamaIbu, PendidikanIbu, PekerjaanIbu, HidupIbu, PenghasilanIbu, BiayaStudi, 
			  AlamatOrtu, RTOrtu, RWOrtu, KodePosOrtu, KotaOrtu, PropinsiOrtu,
			  TeleponOrtu, HandphoneOrtu, EmailOrtu,
			  NamaPerusahaan, AlamatPerusahaan, TeleponPerusahaan, JabatanPerusahaan,
			  PMBFormulirID, ProgramID, ProdiID, TempatKuliahID, $_pil,
			  
			  LoginBuat, TanggalBuat)
			  values
			  ('$id', '$AplikanID', '$gel', '".KodeID."', '$StatusAwalID', '$Nama', 
			  '$TempatLahir', '$TanggalLahir', '$Kelamin', '$GolonganDarah',
			  '$Agama', '$StatusSipil', '$TinggiBadan', '$BeratBadan',
			  '$WargaNegara', '$Kebangsaan',
			  '$TempatTinggal', '$Alamat', '$RT', '$RW', '$KodePos', '$Kota', '$Propinsi', 
			  '$Telepon', '$Handphone', '$Email',
			  '$PendidikanTerakhir', '$AsalSekolah', '$AlamatSekolah',
			  '$TahunLulus', '$NilaiSekolah', '$PrestasiTambahan', 
			  '$NamaAyah', '$AgamaAyah', '$PendidikanAyah', '$PekerjaanAyah', '$HidupAyah', '$PenghasilanAyah', 
			  '$NamaIbu', '$AgamaIbu', '$PendidikanIbu', '$PekerjaanIbu', '$HidupIbu', '$PenghasilanIbu', '$BiayaStudi',
			  '$AlamatOrtu', '$RTOrtu', '$RWOrtu', '$KodePosOrtu', '$KotaOrtu', '$PropinsiOrtu',
			  '$TeleponOrtu', '$HandphoneOrtu', '$EmailOrtu',
			  '$NamaPerusahaan', '$AlamatPerusahaan', '$TeleponPerusahaan', '$JabatanPerusahaan',
			  '$PMBFormulirID', '$ProgramID', '$ProdiID', '$TempatKuliahID', $_vpil,
			  
			  '$_SESSION[_Login]', now())";
			$r = mysqli_query($koneksi, $s);
			
			$s = "update aplikan set PMBID='$id' where AplikanID='$AplikanID'";
			$r = mysqli_query($koneksi, $s);
			
			SetStatusAplikan('DFT', $AplikanID, $gel);
			
			if($md == 1)
			  {	
				echo "<script>window.location='?ndelox=$_SESSION[ndelox]&lungo=PilihKursi&md=$md&gel=$gel&id=$id'</script>";
			  }
		  }
		}
		  else {
			die(PesanError('Error',
			  "Terjadi kesalahan mode edit.<br />
			  Mode <b>$md</b> tidak dikenali oleh sistem.
			  <hr size=1 color=silver />
			  <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" />"));
		  }
		}  
    }
}

function PilihKursi($md, $gel, $id)
{	
	global $koneksi;
	if($md == 1)
	{	$prodistring = ''; $arrProdi = array();
		$pmb = AmbilFieldx('pmb p left outer join pmbformulir pf on p.PMBFormulirID=pf.PMBFormulirID', "p.PMBID='$id' and p.KodeID", KodeID, "p.Pilihan1, p.Pilihan2, p.Pilihan3, p.PMBID, p.Nama, p.ProgramID, pf.USM, pf.Wawancara, pf.Nama as _NamaForm");
		for($i = 1; $i <= 3; $i++) $arrProdi[] = $pmb["Pilihan$i"];
		foreach ($arrProdi as $key => $value) {
		  if (is_null($value) || $value=="") unset($arrProdi[$key]);
		}
		$arrProdi = array_unique($arrProdi);
		
		foreach($arrProdi as $perprodi)
		{	$prodistring .= (empty($prodistring))? "$perprodi": " / $perprodi"; 
		}
		echo "<p><div class='card'>
		<div class='card-header'>
		<div class='table-responsive'><table class=box cellspacing=2 cellpadding=4 width=500 align=center>
				  <tr><td class=ul1 width=200>PMBID:</td>
					 <td class=ul><b>$pmb[PMBID]</b></td></tr>
				  <tr><td class=ul1>Nama:</td>
					  <td class=ul><b>$pmb[Nama]</b></td></tr>
				  <tr><td class=ul1>Program:</td>
					  <td class=ul><b>$pmb[ProgramID]</b></td></tr>
				  <tr><td class=ul1>Program Studi Pilihan:</td>
					  <td class=ul><b>$prodistring</b.</td></tr>
			  </table></div>
			  </div>
			  </div></p>";
		$n = 0;
		if($pmb['USM'] == 'Y')
		{
			$prodistring = ''; 
			foreach($arrProdi as $perprodi)
			{	$prodistring .= (empty($prodistring))? "(INSTR(concat('|', ProdiID, '|'), concat('|', '$perprodi', '|'))" :
														" OR INSTR(concat('|', ProdiID, '|'), concat('|', '$perprodi', '|'))";
			}
			$prodistring .= ')';
			$s = "select ProdiUSMID from prodiusm where KodeID='".KodeID."' and PMBPeriodID='$gel' and 
					$prodistring";
			$r = mysqli_query($koneksi, $s);
			while($w = mysqli_fetch_array($r))
			{	
				$n++;
				echo  "<Iframe name='frame$n' src='../$_SESSION[ndelox].frame.php?PMBID=$id&ProdiUSMID=$w[ProdiUSMID]&gel=$gel' align=center width=99% height=750 frameborder=0></Iframe>";
			}
		}
		else
		{	echo "<font size=2><b>Tidak ada USM yang dijadwalkan untuk Formulir $pmb[_NamaForm].</b></font>&nbsp;&nbsp;<input type=button name='Tutup' value='Tutup' onClick=\"window.close()\"";
		}
		
		if($pmb['Wawancara'] == 'Y')
		{	/*$prodistring = ''; 
			foreach($arrProdi as $perprodi)
			{	$prodistring .= (empty($prodistring))? "(INSTR(concat('|', ProdiID, '|'), concat('|', '$perprodi', '|'))" :
														" OR INSTR(concat('|', ProdiID, '|'), concat('|', '$perprodi', '|'))";
			}
			$prodistring .= ')';
			$s = "select WawancaraUSMID from wawancarausm where KodeID='".KodeID."' and PMBPeriodID='$gel' and 
					$prodistring";
			$r = mysqli_query($koneksi, $s);
			while($w = mysqli_fetch_array($r))
			{*/	
				$n++;
				echo  "<Iframe name='frame$n' src='../$_SESSION[ndelox].framewawancara.php?PMBID=$id&gel=$gel' align=center width=99% height=500 frameborder=0></Iframe>";
			//}
		}
		else
		{	echo "<font size=2><b>Tidak ada Wawancara yang dijadwalkan untuk Formulir $pmb[_NamaForm].</b></font>&nbsp;&nbsp;<input type=button name='Tutup' value='Tutup' onClick=\"window.close()\"";
		}
	}
}

function JumlahPilihanScript()
{	echo <<<SCR
	<SCRIPT>
		function UbahJumlahPilihan()
		{	id = frmisi.PMBFormulirID.value;
			var jmlpil = 0;
			
			if(id != '')
			{	jmlpil = document.getElementById('Form'+id).value;
			}
			else jmlpil = 3;
			
			if(jmlpil <= 1)
			{	frmisi.Pilihan2.value = '';
				frmisi.Pilihan2.disabled = true;
				frmisi.Pilihan3.value = '';
				frmisi.Pilihan3.disabled = true;
			}
			else if(jmlpil <=2)
			{	frmisi.Pilihan2.disabled = false;
				frmisi.Pilihan3.value = '';
				frmisi.Pilihan3.disabled = true;
			}
			else
			{	frmisi.Pilihan2.disabled = false;
				frmisi.Pilihan3.disabled = false;
			}
		}
	</SCRIPT>

SCR;
}

function GetNextPMBIDFromGel($gel) {
  $gelombang = AmbilFieldx('pmbperiod', "PMBPeriodID='$gel' and KodeID", KodeID, "FormatNoPMB, DigitNoPMB");
  // Buat nomer baru
  $nomer = str_pad('', $gelombang['DigitNoPMB'], '_', STR_PAD_LEFT);
  $nomer = $gelombang['FormatNoPMB'].$nomer;
  $akhir = AmbilOneField('pmb',
    "PMBID like '$nomer' and KodeID", KodeID, "max(PMBID)");
  $nmr = str_replace($gelombang['FormatNoPMB'], '', $akhir);
  $nmr++;
  $baru = str_pad($nmr, $gelombang['DigitNoPMB'], '0', STR_PAD_LEFT);
  $baru = $gelombang['FormatNoPMB'].$baru;
  return $baru;
}

function TutupScript() {
echo <<<SCR
<SCRIPT>
  function ttutup() {
    opener.location='../index.php?ndelox=$_SESSION[ndelox]&_pmbPage=0';
    self.close();
    return false;
  }
</SCRIPT>
SCR;
}
?>
