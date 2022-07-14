<?php

$sub = (empty($_REQUEST['sub']))? 'DftrPMBGrade' : $_REQUEST['sub'];
$sub();

function DftrPMBGrade() {
	global $koneksi;
  $s = "select * from pmbgrade where KodeID='".KodeID."'
    order by GradeNilai";
  $r = mysqli_query($koneksi, $s);
  echo "<p>
  <div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:50%' align='center'>
    <form action='?' method=POST>
	<input type=hidden name='md' value='1' />
    <input type=hidden name='lungo' value='pmbgrade' />
    <input type=hidden name='sub' value='Edit' />
	<tr><td colspan=5><input type=submit name='Tambah' value='Tambah Grade'></td>
	</tr>
	<tr style='background:purple;color:white'>
      <th class=ttl colspan=2>Grade</th>
	  <th class=ttl align=center>Nilai Min</th>
	  <th class=ttl align=center>Nilai Max</th>
      <th class=ttl>Lulus</th>
	  <th class=ttl>Keterangan</th>
      <th class=ttl>NA</th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
	$c = ($w['NA'] == 'Y')? 'class=nac' : 'class=ul';	
    echo "<tr>
      <td class=ul1 width=10>
        <a href='?ndelox=$_SESSION[ndelox]&sub=Edit&md=0&id=$w[GradeNilaiID]'><i class='fa fa-edit'></i></a>
        </td>
      <td $c width=20>$w[GradeNilai]</td>
	  <td $c align=center>$w[NilaiUjianMin]</td>
	  <td $c align=center>$w[NilaiUjianMax]</td>
      <td $c align=center width=10>
        <img src='img/$w[Lulus].png' />
      </td>	  
      <td $c>$w[Keterangan]&nbsp;</td>
      <td $c align=center width=10>
        <img src='img/book$w[NA].gif' />
      </td>
      </tr>";
  }
  echo "</table></div>
  </div>
  </div></p>";
}

function Edit() {
  $md = $_REQUEST['md']+0;
  $id = sqling($_REQUEST['id']);
  // Cek mode edit
  if ($md == 0) {
    $jdl = "Edit PMB Grade";
    $w = AmbilFieldx('pmbgrade', 'GradeNilaiID', $id, '*');
    $_id = "<input type=text name='id' value='$id' size=5 maxlength=5>";
  }
  elseif ($md == 1) {
    $jdl = "Tambah PMB Grade";
    $w = array();
	$w['NilaiUjianMin'] = 0.00;
	$w['NilaiUjianMax'] = 0.00;
    $w['NA'] = 'N';
    $_id = "<input type=text name='id' size=5 maxlength=5 />";
  }
  else die(PesanError('Error',
    "Mode edit <b>$md</b> tidak dikenali.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    <input type=button name='Kembali' value='Kembali' onClick=\"window.location='?ndelox=$_SESSION[ndelox]'\" />"));
  
  // Tampilkan formulir
  $na = ($w['NA'] == 'Y')? 'checked' : '';
  
  CheckFormScript('id,NilaiUjianMin,NilaiUjianMax');
  
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table class=box cellspacing=1 align=center>
  <form name='pmbgrade' action='?' method=POST onSubmit=\"return CheckForm(this)\">
  <input type=hidden name='md' value='$md' />
  <input type=hidden name='lungo' value='pmbgrade' />
  <input type=hidden name='idgrade' value='$id' />
  <input type=hidden name='sub' value='Simpan' />
  
  <tr><th class=ttl colspan=2>$jdl</th></tr>
  <tr><td class=inp>Grade</td>
      <td class=ul1><input type=text name='id' value='$w[GradeNilai]' size=2 maxlength=5 /></td>
      </tr>
  <tr><td class=inp>Nilai ComboProdiProgramx:</td>
      <td class=ul1><input type=text name='NilaiUjianMin' value='$w[NilaiUjianMin]' size=2 maxlength=5 /></td>
      </tr>
  <tr><td class=inp>Nilai Maximum:</td>
      <td class=ul1><input type=text name='NilaiUjianMax' value='$w[NilaiUjianMax]' size=2 maxlength=5 /></td>
      </tr>
  <tr><td class=inp wdith>Apakah Lulus?</td>
      <td class=ul1>
      <input type=checkbox name='Lulus' value='Y' $Lulus /> Centang jika Lulus.
      </td></tr>
  <tr><td class=inp>Catatan:</td>
      <td class=ul1><textarea name='Keterangan' cols=30 rows=3>$w[Keterangan]</textarea></td>
      </tr>
  <tr><td class=inp wdith>NA (tidak aktif)?</td>
      <td class=ul1>
      <input type=checkbox name='NA' value='Y' $na /> Centang jika tdk aktif.
      </td></tr>
  <tr><td class=ul1 colspan=2>
      <input type=submit name='Simpan' value='Simpan' onClick=\"return CekNilai()\"/>
      <input type=button name='Batal' value='Batal'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=pmbgrade&sub='\" />
      </td></tr>
  
  </form>
  </table></div>
  </div>
  </div></p>

  <script>
	function CekNilai()
	{	if(parseInt(pmbgrade.NilaiUjianMin.value) > parseInt(pmbgrade.NilaiUjianMax.value)) 
		{	alert('Nilai ComboProdiProgramx tidak boleh melebihi Nilai Maksimum'); }
		return parseInt(pmbgrade.NilaiUjianMin.value) <= parseInt(mbgrade.NilaiUjianMax.value);
	}
  </script>";
}

function Simpan()
{	
global $koneksi;
$md = $_REQUEST['md']+0;
	$id = $_REQUEST['id'];
	$idgrade = $_REQUEST['idgrade']+0;
	$GradeNilai = $_REQUEST['GradeNilai'];
	$NilaiUjianMin = $_REQUEST['NilaiUjianMin']+0;
	$NilaiUjianMax = $_REQUEST['NilaiUjianMax']+0;
	$Lulus = ($_REQUEST['Lulus'] == 'Y')? 'Y' : 'N';
	$Keterangan = sqling($_REQUEST['Keterangan']);
	$NA = ($_REQUEST['NA'] == 'Y')? 'Y' : 'N';
	
	if($md == 0)
	{	$s = "update `pmbgrade` set GradeNilai='$id', NilaiUjianMin='$NilaiUjianMin', NilaiUjianMax='$NilaiUjianMax', 
								Lulus='$Lulus', 
								Keterangan='$Keterangan', NA='$NA' where GradeNilaiID='$idgrade'";
		$r = mysqli_query($koneksi, $s);
	}
	else if($md == 1)
	{	$s = "insert `pmbgrade` set GradeNilai='$id', KodeID='".KodeID."', 
									NilaiUjianMin='$NilaiUjianMin', NilaiUjianMax='$NilaiUjianMax',
									Lulus='$Lulus',
									Keterangan='$Keterangan', NA='$NA'";
		$r = mysqli_query($koneksi, $s);
	}	
	SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=pmbgrade&sub", 10);
}
