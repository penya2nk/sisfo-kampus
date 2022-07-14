
<head>
<link rel="stylesheet" type="text/css" href="themes/<?=$themas;?>/index.css" />

</head>

<?php
error_reporting(0);
$_jdwlProdi     = GainVariabelx('_jdwlProdi');
$_jdwlProg      = GainVariabelx('_jdwlProg');
$_jdwlTahun     = GainVariabelx('_jdwlTahun');
$_jdwlHari      = GainVariabelx('_jdwlHari');
$_jdwlKelas     = GainVariabelx('_jdwlKelas');
$_jdwlSemester  = GainVariabelx('_jdwlSemester');
$_jdwlMKKode    = GainVariabelx('_jdwlMKKode');

TitleApps("JADWAL KULIAH");
ViewHeaderJdwlx();
RandomStringScript();

if (!empty($_jdwlTahun) && !empty($_jdwlProdi)) {
  $lungo = (empty($_REQUEST['lungo']))? 'ListKuliah' : $_REQUEST['lungo'];
  $lungo();
}

function ViewHeaderJdwlx() {
  global $koneksi;
  $s = "select DISTINCT(TahunID) from tahun where KodeID='".KodeID."' order by TahunID DESC";
  $r = mysqli_query($koneksi, $s);
  $opttahun = "<option value=''></option>";
  while($w = mysqli_fetch_array($r)) {  
	  $ck = ($w['TahunID'] == $_SESSION['_jdwlTahun'])? "selected" : '';
      $opttahun .=  "<option value='$w[TahunID]' $ck>$w[TahunID]</option>";
  }

  $optprodi = AmbilPenggunaProdi($_SESSION['_Login'], $_SESSION['_jdwlProdi']);  
  $optprog  = AmbilCombo2('program', "concat(Nama, ' - ', ProgramID)", 'ProgramID', $_SESSION['_jdwlProg'], "KodeID='".KodeID."'", 'ProgramID');
  $opthr    = AmbilCombo2('hari', 'Nama', 'HariID', $_SESSION['_jdwlHari'], '', 'HariID');
  $optkelas = AmbilCombo2('kelas', 'Nama', 'Nama', $_SESSION['_jdwlKelas'], "ProdiID='$_SESSION[_jdwlProdi]' AND TahunID='$_SESSION[_jdwlTahun]' and KodeID='".KodeID."'", "KelasID");
  if (!empty($_SESSION['_jdwlTahun']) && !empty($_SESSION['_jdwlProdi'])) {
    JdwlEdtScript();
    $btn1 = "
      <input class='btn btn-danger btn-sm' type=button name='TambahJdwl' value='Tambah Jadwal' 
        onClick=\"javascript:JdwlEdt(1, 0)\"/>";
      //<input class='btn-secondary' type=button name='HapusSemua' value='Hapus Semua' 
      //onClick=\"javascript:JadwalHpsAll('$_SESSION[_jdwlTahun]', '$_SESSION[_jdwlProdi]','$_SESSION[_jdwlProg]')\" />
     
	 //<input class='btn-secondary' type=button name='btnJadwalUjian' value='Jadwal UAS'
     //onClick=\"location='?ndelox=$_SESSION[ndelox]ujian&_jdwlProdi=$_SESSION[_jdwlProdi]&_jdwlProg=$_SESSION[_jdwlProg]&_jdwlTahun=$_SESSION[_jdwlTahun]&_jdwlUjian=2'\" />
     

    $btn2 = "
      <input class='btn btn-success btn-sm' type=button name='Cetak' value='Print Jadwal' onClick=\"javascript:CetakJadwal()\" />
      <input class='btn btn-warning btn-sm' type=button name='CetakFormKRS' value='Print FRS' onClick=\"javascript:CetakFormulirKRS()\" />
	    <input class='btn btn-primary btn-sm' type=button name='CetakJdwlDosen' value='Print Jadwal Dosen' onClick=\"javascript:CetakJadwalDosen()\" />
      <input class='btn btn-info btn-sm' type=button name='CetakJdwlRuang' value='Print Jadwal Per Ruang' onClick=\"javascript:CetakJadwalRuang()\" />
	  </br>";
  }
  echo <<<SCR
  <div class='card'>
  <div class='card-header'>
  <div align='center'>
  <form name='frmJadwalHeader' action='?' method=POST>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='lungo' value='' />
      TAHUN AK <select style='width:75px;height:30px;' name='_jdwlTahun' onChange='this.form.submit()' />$opttahun</select>
      PRODI <select style='height:30px;' name='_jdwlProdi' onChange='this.form.submit()'>$optprodi</select>    
      PROGRAM <select style='height:30px;' name='_jdwlProg'>$optprog</select> 
      HARI <select style='height:30px;' ame='_jdwlHari'>$opthr</select></td>
      KELAS <select style='height:30px;' name='_jdwlKelas' value='$_SESSION[_jdwlKelas]'>$optkelas</select></td>
      <input style='height:30px' placeholder='MATAKULIAH' type=text name='_jdwlMKKode' value='$_SESSION[_jdwlMKKode]' size=10 maxlength=50 /></td>
      <input style='height:30px' placeholder='SMT' type=text name='_jdwlSemester' value='$_SESSION[_jdwlSemester]' size=2 maxlength=10 /><br>
      </div>  
</div>
</div>

<div class='card'>
<div class='card-header'>
<div align='center'>
  <input class='btn btn-primary btn-sm' type=submit name='btnKirim' value='Lihat Data' />
  <input class='btn btn-secondary btn-sm' type=button name='btnReset' value='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reset&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
    onClick="location='?ndelox=$_SESSION[ndelox]&_jdwlHari=&_jdwlKelas=&_jdwlMKKode=&_jdwlSemester='" />
  $btn1
  $btn2
  </div>
</div>
</div>

</form>


SCR;
}
function ListKuliah() {
  global $koneksi;
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>";
  $hdr = "<tr style='background:purple;color:white'>
      <th style='text-align:center;width:2px'>#</th>   
      <th width=20 style='text-align:center'>Ruang</th>
      <th width=80 style='text-align:center'>Jam</th>
      <th style='text-align:center;width:25px'>Kode </th>
      <th width=240>Matakuliah <sup style='color:yellow'>(Semester)</sup></th>
      <th width=90 style='text-align:center'>Prog - Kelas</th>
      <th width=10 style='text-align:center'>SKS</th>
      <th width=250>Dosen</th>
	  <th width=10 style='text-align:center'>JmlMhs</th>
      <th width=20 style='text-align:center'>Print</th>
      <th width=50 title='Hapus Jadwal'>Aksi</th>
      </tr>";

  $whr_prg = (empty($_SESSION['_jdwlProg']))? '' : "and j.ProgramID = '$_SESSION[_jdwlProg]'";
  $whr_hr  = ($_SESSION['_jdwlHari'] == '')? '' : "and j.HariID = '$_SESSION[_jdwlHari]'";
  $whr_smt = (empty($_SESSION['_jdwlSemester']))? '' : "and mk.Sesi = '$_SESSION[_jdwlSemester]' ";
  $whr_kls = ($_SESSION['_jdwlKelas'] == '')? '' : "and j.NamaKelas like '$_SESSION[_jdwlKelas]%' ";
  $whr_kd  = ($_SESSION['_jdwlMKKode'] == '')? '' : "and j.MKKode like '$_SESSION[_jdwlMKKode]%' ";
  
  $s = "select j.JadwalID, j.JadwalRefID, j.ProdiID, j.ProgramID, j.HariID, j.AdaResponsi,
      j.RuangID, j.MKKode, j.Nama, j.NamaKelas, j.DosenID, j.SKS, j.JenisJadwalID, 
      d.Nama as DSN, d.Gelar,
      LEFT(j.JamMulai, 5) as _JM, LEFT(j.JamSelesai, 5) as _JS,
      h.Nama as HR, mk.Sesi, j.Final,
      j.JumlahMhsw, j.Kapasitas,
      j.BiayaKhusus, j.Biaya, format(j.Biaya, 0) as _Biaya,
	  k.Nama as _NamaKelas
    from jadwal j
      left outer join hari h on j.HariID = h.HariID
      left outer join dosen d on d.Login = j.DosenID and d.KodeID = '".KodeID."'
      left outer join mk mk on mk.MKID = j.MKID
	  left outer join kelas k on k.KelasID = j.NamaKelas
    where j.KodeID = '".KodeID."'
      and j.TahunID = '$_SESSION[_jdwlTahun]'
      and j.ProdiID = '$_SESSION[_jdwlProdi]'
      $whr_prg $whr_hr $whr_smt $whr_kls $whr_kd
      and j.NA = 'N'
    order by j.HariID, j.RuangID, j.JamMulai, j.JamSelesai";
  $r = mysqli_query($koneksi, $s); $n = 0;
  $HariID = -320;
  $kanan = "<img src='img/kanan.gif' />";
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    if ($HariID != $w['HariID']) {
      $HariID = $w['HariID'];
      echo "<tr>
        <td class=ul1 colspan=15><font size=+1>$w[HR]</font> </td>
        </tr>";
      echo $hdr;
    }
    if ($w['Final'] == 'Y') {
      $edt = "<img src='img/lock.jpg' width=26 title='Sudah difinalisasi. Sudah tidak dapat diedit.' />";
      $del = "&times;";
      $c = "class=nac";
      $pindah = '&nbsp;';
      $dosen = '&nbsp;';
	  $print = '&nbsp;';
	  $LabTag = '';
    }
	else if($w['JenisJadwalID'] != 'K')
	{ $edt = "<a href='#' onClick=\"javascript:JdwlLabEdt(0, '$w[JadwalRefID]', '$w[JadwalID]')\" title='Edit jadwal'><img src='img/jadwal.png' width=20 /></a>";
      $del = "&times;";
      $c = "class=cnaY";
      $pindah = "<a href='#' onClick=\"javascript:PindahLabKelas($w[JadwalID])\" title='Pindahkan peserta kuliah ke Jadwal Lain'>&#8904;</a>";
      $dosen = '&nbsp;';
	  $print = '&nbsp;';
	  $LabTag = "<b>( ".AmbilOneField('jenisjadwal', "JenisJadwalID", $w['JenisJadwalID'], 'Nama')." )</b>";
	}
    else {
      $edt = "<a href='#' onClick=\"javascript:JdwlEdt(0, $w[JadwalID])\" title='Edit jadwal'><i class='fa fa-edit'></i></a>";
      // Jika sudah ada mahasiswa yang mendaftar, maka jadwal tidak boleh dihapus
      $del = ($w['JumlahMhsw'] > 0)? "<abbr title='Tidak dapat dihapus karena sudah ada Mhsw yang mendaftar'>&times;</abbr>" : "<a href='#' onClick=\"javascript:JadwalHps($w[JadwalID])\" title='Hapus jadwal'><i class='fa fa-trash'></i></a>";
      $c = "class=ul";
      $pindah = "<a href='#' onClick=\"javascript:PindahKelas($w[JadwalID])\" title='Pindahkan peserta kuliah ke Jadwal Lain'>&#8904;</a>";
      //$dosen = "<a href='#' onClick=\"javascript:JdwlDsnEdt($w[JadwalID])\"><i class='fa fa-edit'></i></a>";
      $dosen = "<a href='#' onClick=\"javascript:JdwlDsnEdt($w[JadwalID])\"><i class='fa fa-edit'></i></a>";
      $print = "<a href='#' onClick=\"javascript:CetakDPNA($w[JadwalID])\"><i class='fa fa-print'></i></i></a>
      <a href='#' onClick=\"javascript:CetakKursiUAS($w[JadwalID])\"><i class='nav-icon fas fa-print'></i></a>";
	  $LabTag = '';
	}
    // Ambil dosen2
    $dsn = AmbilDosen2($w['JadwalID']);
    $HRG = ($w['BiayaKhusus'] == 'Y')? "<div align=right><sup>Biaya: Rp. <b>$w[_Biaya]</b></sup></div>" : '';
	if($w['AdaResponsi'] == 'Y')
	{	$FieldResponsi = AmbilResponsi($w['JadwalID']);
		$FieldResponsi .= "<br><a href='#' onClick=\"JdwlLabEdt(1, '$w[JadwalID]', '0')\"><font size=0.8m>Tambah Jadwal Ekstra(Lab, Responsi, dll.)</font></a>";
	}
	else $FieldResponsi = '';
  $NamaMKx 	= strtolower($w['Nama']);
  $NamaMK		= ucwords($NamaMKx);

  $NamaDosx 	= strtolower($w['DSN']);
  $NamaDos		= ucwords($NamaDosx);
  
  $Gelar 	= $w['Gelar'];
  
  $ProgramIDx 	= strtolower($w['ProgramID']);
  $ProgramID		= ucwords($ProgramIDx);

	echo "<tr>
        <td style='text-align:center'>$n</td>
        <td $c style='text-align:center'>$w[RuangID]</td>
        <td $c style='text-align:center'>$w[_JM] - $w[_JS]</td>
        <td $c style='text-align:center;'>$w[MKKode]</sup></td>
        <td $c>$NamaMK <sup style='color:blue'>($w[Sesi]) $LabTag $FieldResponsi $HRG</td>
        <td $c align=center>$ProgramID - $w[_NamaKelas]</td>	
        <td $c align=center>$w[SKS]</td>		
        <td $c>$dosen $NamaDos, <font style='color:purple'>$Gelar</font> $dsn </td>
		<td $c align=center>$w[JumlahMhsw]/$w[Kapasitas]</td>
        <td $c align=center valign=bottom nowrap>$print</td>
        <td class=ul1 align=center valign=bottom>$edt $del
        </tr>";
  }
  echo "</table>
  </div>
</div>
</div></p>";
}

function AmbilDosen2($id) {
  global $koneksi;
  $s = "select d.Nama, d.Gelar, jd.JenisDosenID
    from jadwaldosen jd
      left outer join dosen d on d.Login = jd.DosenID and d.KodeID = '".KodeID."'
    where jd.JadwalID = '$id'
    order by d.Nama";
  $r = mysqli_query($koneksi, $s);
  //die("<pre>$s</pre>");
  $a = array();;
  while ($w = mysqli_fetch_array($r)) {
    $a[] = "&rsaquo; $w[Nama], <font style='color:purple'>$w[Gelar]</font>";
  }
  $a = (!empty($a))? "<br />".implode("<br />", $a) : '';
  return $a;
}
function AmbilResponsi($id) {
  global $koneksi;
   $s = "select jr.JadwalID, jr.JadwalRefID, h.Nama as _NamaHari, LEFT(jr.JamMulai, 5) as _JM, LEFT(jr.JamSelesai, 5) as _JS, 
			jr.RuangID, r.Nama as _NamaRuang, jr.JenisJadwalID, jj.Nama as _NamaJenisJadwal
    from jadwal jr
      left outer join ruang r on jr.RuangID = r.RuangID and r.KodeID = '".KodeID."'
	  left outer join hari h on h.HariID = jr.HariID
	  left outer join jenisjadwal jj on jj.JenisJadwalID=jr.JenisJadwalID
	where jr.JadwalRefID = '$id'
    order by jj.JenisJadwalID, jr.HariID, jr.JamMulai, jr.JamSelesai";
  $r = mysqli_query($koneksi, $s);
  //die("<pre>$s</pre>");
  $a = array();;
  $n = 0; $jj = 'K';
  while ($w = mysqli_fetch_array($r)) {
    if($jj != $w['JenisJadwalID'])
	{	$n = 0;
		$jj = $w['JenisJadwalID'];
	}
	$n++;
	$a[] = "&rsaquo; <b>$w[_NamaJenisJadwal] #$n</b> $w[_NamaHari], $w[_JM] - $w[_JS], $w[_NamaRuang]($w[RuangID]) <a href='#' onClick=\"JdwlLabEdt(0, '$w[JadwalRefID]', '$w[JadwalID]')\"><i class='fa fa-edit'></i></a>";
  }
  $a = (!empty($a))? "<br />".implode("<br />", $a) : '';
  return $a;
}

function JadwalHps() {
	global $koneksi;
  $id = $_REQUEST['id'];
  $s = "delete from jadwal where JadwalID = '$id' ";
  $r = mysqli_query($koneksi, $s);
  $s = "delete from jadwal where JadwalRefID = '$id' ";
  $r = mysqli_query($koneksi, $s);
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=", 1);
}

function JadwalHpsAll() {
	global $koneksi;
  $thn = sqling($_REQUEST['thn']);
  $prd = sqling($_REQUEST['prd']);
  $prg = sqling($_REQUEST['prg']);
  $whr_prg = (empty($prg))? '' : "and ProgramID = '$prg' ";
  // Hapus Jadwal2 UTS terlebih dahulu
  $s = "select JadwalID from jadwal where TahunID='$thn' and ProdiID='$prd' $whr_prg";
  $r = mysqli_query($koneksi, $s);
  while($w = mysqli_fetch_array($r))
  {	$s1 = "delete from jadwaluts where JadwalID='$w[JadwalID]' and KodeID='".KodeID."'";
	$r1 = mysqli_query($koneksi, $s1);
	$s1 = "delete from jadwaluas where JadwalID='$w[JadwalID]' and KodeID='".KodeID."'";
	$r1 = mysqli_query($koneksi, $s1);
  }
  
  $s = "delete from jadwal where TahunID = '$thn' and ProdiID = '$prd' $whr_prg";
  $r = mysqli_query($koneksi, $s);
  
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=", 1);
}

function JdwlEdtScript() {
  //width=450,height=250,left=450,top=200,toolbar=0,status=0
  //width=450, height=430, scrollbars, status
  echo <<<SCR
  <script>
  function JdwlEdt(md, id) {
    var _rnd = randomString();
    lnk = "$_SESSION[ndelox].edit.php?md="+md+"&id="+id+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=550,height=600,left=500,top=150,toolbar=0,status=0");
    if (win2.opener == null) childWindow.opener = self;
  }
  function JdwlLabEdt(md, id, resid) {
    var _rnd = randomString();
    lnk = "$_SESSION[ndelox].editlab.php?md="+md+"&id="+id+"&resid="+resid+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=550, height=600, left=500,top=150, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function JdwlDsnEdt(id) {
    var _rnd = randomString();
    lnk = "$_SESSION[ndelox].dosen.php?id="+id+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=550,height=500,left=500,top=150,toolbar=0,status=0");
    if (win2.opener == null) childWindow.opener = self;
  }
  function JadwalHps(id) {
    if (confirm("Anda yakin akan menghapus jadwal ini?")) {
      var _rnd = randomString();
      window.location = "?ndelox=$_SESSION[ndelox]&BypassMenu=1&lungo=JadwalHps&id="+id+"&_rnd="+_rnd;
    }
  }
  function JadwalHpsAll(thn, prd, prg) {
    var psn = (prg == "")? "Anda juga akan menghapus semua jadwal dari semua program pendidikan." : "";
    if (confirm("Anda yakin akan menghapus semua jadwal ini? "+psn)) {
      var _rnd = randomString();
      window.location = "?ndelox=$_SESSION[ndelox]&BypassMenu=1&lungo=JadwalHpsAll&thn=" + thn + "&prd=" + prd + "&prg=" + prg+"&_rnd="+_rnd;
    }
  }
  function CetakJadwal() {
    if (frmJadwalHeader._jdwlProg.value == '') {
      alert("Tentukan dahulu Program Pendidikan yang akan dicetak jadwalnya.");
    }
    else {
      var _rnd = randomString();
      lnk = "$_SESSION[ndelox].pdf.php?TahunID=$_SESSION[_jdwlTahun]&ProdiID=$_SESSION[_jdwlProdi]&ProgramID=$_SESSION[_jdwlProg]&_rnd="+_rnd;
      win2 = window.open(lnk, "", "width=700, height=500, scrollbars, status");
      if (win2.opener == null) childWindow.opener = self;
    }
  }
  function CetakFormulirKRS() {
    if (frmJadwalHeader._jdwlProg.value == '') {
      alert("Tentukan dahulu Program Pendidikan yang akan dicetak formulir KRS-nya.");
    }
    else {
      var _rnd = randomString();
      lnk = "$_SESSION[ndelox].formkrs.php?TahunID=$_SESSION[_jdwlTahun]&ProdiID=$_SESSION[_jdwlProdi]&ProgramID=$_SESSION[_jdwlProg]&_rnd="+_rnd;
      win2 = window.open(lnk, "", "width=700, height=500, scrollbars, status");
      if (win2.opener == null) childWindow.opener = self;
    }
  }
  function CetakJadwalDosen() {
      var _rnd = randomString();
      lnk = "$_SESSION[ndelox].dosen.pdf.php?TahunID=$_SESSION[_jdwlTahun]&ProdiID=$_SESSION[_jdwlProdi]&_rnd="+_rnd;
      win2 = window.open(lnk, "", "width=700, height=500, scrollbars, status");
      if (win2.opener == null) childWindow.opener = self;
  }
  function CetakJadwalRuang() {
      var _rnd = randomString();
      lnk = "$_SESSION[ndelox].ruang.pdf.php?TahunID=$_SESSION[_jdwlTahun]&ProdiID=$_SESSION[_jdwlProdi]+_rnd="+_rnd;
      win2 = window.open(lnk, "", "width=700, height=500, scrollbars, status");
      if (win2.opener == null) childWindow.opener = self;
  }
  function CetakDPNA(id) {
    var _rnd = randomString();
    lnk = "$_SESSION[ndelox].dpna.php?id="+id+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=700, height=500, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function CetakKursiUTS(id) {
    var _rnd = randomString();
    lnk = "$_SESSION[ndelox].kursiuts.php?id="+id+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=700, height=500, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function CetakKursiLab(id) {
    var _rnd = randomString();
    lnk = "$_SESSION[ndelox].kursilab.php?id="+id+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=700, height=500, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function CetakKursiUAS(id) {
    var _rnd = randomString();
    lnk = "$_SESSION[ndelox].kursiuas.php?id="+id+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=700, height=500, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function PindahKelas(JadwalID) {
    var _rnd = randomString();
    lnk = "$_SESSION[ndelox].pindah.php?JadwalID="+JadwalID+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=800, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
SCR;
}
?>

</BODY>
</HEAD>
