<?php
error_reporting(0);
$_utsProdi = GainVariabelx('_utsProdi');
$_utsProg  = GainVariabelx('_utsProg');
$_utsTahun = GainVariabelx('_utsTahun');

TitleApps("PENJADWALAN UTS");
TampilkanHeaderUTS();
RandomStringScript();

if (!empty($_utsTahun) && !empty($_utsProdi)) {
  $lungo = (empty($_REQUEST['lungo']))? 'ListUTS' : $_REQUEST['lungo'];
  $lungo();
}

function TampilkanHeaderUTS() {
  $optprodi = AmbilPenggunaProdi($_SESSION['_Login'], $_SESSION['_utsProdi']);
  $optprog  = AmbilCombo2('program', "concat(ProgramID, ' - ', Nama)", 'ProgramID', $_SESSION['_utsProg'], "KodeID='".KodeID."'", 'ProgramID');
  if (!empty($_SESSION['_utsTahun']) && !empty($_SESSION['_utsProdi'])) {
    JdwlEdtScript();
    $btn1 = " |
      <input class='btn-danger' type=button name='HapusSemua' value='Hapus Semua Jadwal UTS' 
        onClick=\"javascript:JadwalHpsSemua('$_SESSION[_utsTahun]', '$_SESSION[_utsProdi]','$_SESSION[_utsProg]')\" />
     "; 
  }
  echo <<<SCR
  <div class='card'>
<div class='card-header'>

  <form name='frmJadwalHeader' action='?' method=POST>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='lungo' value='' />
      <div align=center>
        TAHUN AKADEMIK  <input style='height:30px' type=text name='_utsTahun' value='$_SESSION[_utsTahun]' size=5 maxlength=5 />
        PROGRAM  <select style='height:30px'name='_utsProg'>$optprog</select>
        PROGRAM STUDI  <select style='height:30px' name='_utsProdi'>$optprodi</select>
        <input class='btn btn-success btn-sm'' type=submit name='btnKirim' value='Lihat Data' />
        <input class='btn btn-warning btn-sm' type=button name='btnReset' value='Reset' onClick="location='?ndelox=$_SESSION[ndelox]&_utsHari=&_utsKelas=&_utsMKKode=&_utsSemester='" />
        $btn1
        $btn2
      </div>  
  </form>

  </div>
</div>
</div>
SCR;
}
function ListUTS() {
global $koneksi;
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>";
  $hdr = "
  <tr style='background:purple;color:white'><th class=ttl width=50>#</th>
  <th class=ttl>Waktu</th>
      <th class=ttl>Kode</th>
      <th class=ttl>Matakuliah</th>
      <th class=ttl>Dosen</th>
	    <th class=ttl width=40>Kelas</th>
      <th class=ttl width=20>JmlMhs</th>
      <th class=ttl width=20>Edit</th>
	    <th class=ttl width=75>Ujian</th>
      <th class=ttl width=120>Jam</th>
	    <th class=ttl width=40 title='Pembagian Kursi'>Kursi</th>
      <th class=ttl width=20 title='Hapus Jadwal'>Del</th>
      </tr>";

  $whr_prg = (empty($_SESSION['_utsProg']))? '' : "and j.ProgramID = '$_SESSION[_utsProg]'";
  
  $s = "select j.JadwalID, j.ProdiID, j.ProgramID, j.HariID,
      j.RuangID, j.MKKode, j.Nama, j.NamaKelas, j.DosenID, j.SKS,
      concat(d.Nama, ' <sup>', d.Gelar, '</sup>') as DSN,
      LEFT(j.JamMulai, 5) as _JM, LEFT(j.JamSelesai, 5) as _JS,
      h.Nama as HR, mk.Sesi, j.Final, 
      j.JumlahMhsw, j.Kapasitas, 
      j.BiayaKhusus, j.Biaya, format(j.Biaya, 0) as _Biaya
    from jadwal j
      left outer join hari h on j.HariID = h.HariID
      left outer join dosen d on d.Login = j.DosenID and d.KodeID = '".KodeID."'
      left outer join mk mk on mk.MKID = j.MKID
	  left outer join jenisjadwal jj on jj.JenisJadwalID = j.JenisJadwalID
	where j.KodeID = '".KodeID."'
      and j.TahunID = '$_SESSION[_utsTahun]'
      and j.ProdiID = '$_SESSION[_utsProdi]'
      $whr_prg $whr_hr $whr_smt $whr_kls $whr_kd
      and j.NA = 'N'
	  and jj.Tambahan = 'N'
    order by j.UTSTanggal, j.UTSJamMulai, j.UTSJamSelesai, j.Nama";
  $r = mysqli_query($koneksi, $s); $n = 0;
  $HariID = -320;
  $kanan = "<img src='img/kanan.gif' />";
  echo $hdr;
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    if ($w['Final'] == 'Y') $c = "class=nac";
    else $c = "class=ul";
    
    // Ambil dosen2
    $dsn = AmbilDosen2($w['JadwalID']);
    
    // Tampilkan data
	$JumlahJadwalUTS = AmbilOneField('jadwaluts', "JadwalID='$w[JadwalID]' and KodeID", KodeID, "count(JadwalUTSID)");
	$rowspan = "rowspan=". (($JumlahJadwalUTS == 0)? 1 : $JumlahJadwalUTS);
	
    echo "<tr>
      <td class=inpx align=center width=20 >$n</font></br></td>
      <td><b>$w[HR]</b>, $w[_JM] - $w[_JS]</td>
      <td>$w[MKKode]</td>
      <td $c><b>$w[Nama]</b></td>
      <td>$w[DSN]$dsn</td>
      <td $c align=left $rowspan><b>$w[NamaKelas]</b></td>
		  <td align=right><b>$w[JumlahMhsw]</b></td>
      </td>";
	
	if($JumlahJadwalUTS == 0)
	{ if($w['Final'] == 'Y')
		echo "
		<td $c colspan=5 align=center><b>Blm ada jadwal</b></td>";
		else
		echo "
	  <td $c colspan=5 align=center><b>Blm ada jadwal</b> <a href='#' onClick=\"javascript:JdwlEdt(1, $w[JadwalID])\">>> Tambah <<</a></td>";
	}
	else
	{ 
	  $s1 = "select  ju.JadwalUTSID,
	            date_format(ju.Tanggal, '%d-%m-%y') as _UTSTanggal,
			    huts.Nama as _UTSHari, ju.JumlahMhsw as _JumlahMhswUTS,
			    LEFT(ju.JamMulai, 5) as _UTSJamMulai, LEFT(ju.JamSelesai, 5) as _UTSJamSelesai
				from jadwaluts ju left outer join hari huts on huts.HariID = date_format(ju.Tanggal, '%w')
				where ju.JadwalID='$w[JadwalID]' and ju.KodeID='".KodeID."'";
	  $r1 = mysqli_query($koneksi, $s1);
	  while($w1 = mysqli_fetch_array($r1))
	  {	  if ($w['Final'] == 'Y')
	      {  $edt = "<img src='img/lock.jpg' width=26 title='Sudah difinalisasi. Sudah tidak dapat diedit.' />";
			 $del = "&times;";
			 $editkursi = "<a href='#' onClick=\"alert('Penempatan kursi mahasiswa sudah tidak dapat dilakukan.')\"><img src='../img/kursi.png'></a>";
          }
		  else 
		  {	 $edt = "<a href='#' onClick=\"javascript:JdwlEdt(0, $w[JadwalID], $w1[JadwalUTSID])\" title='Edit jadwal'><i class='fa fa-edit'></i></a>";
			 $del = ($w1['JumlahMhsw'] > 0)? "<abbr title='Tidak dapat dihapus karena sudah ada Mhsw yang mendaftar'>&times;</abbr>" : "<a href='#' onClick=\"javascript:JadwalHps($w[JadwalID],$w1[JadwalUTSID])\" title='Hapus jadwal'><i class='fa fa-trash'></i></a>";
			 $editkursi = "<a href='#' onClick=\"EdtKursi('$w1[JadwalUTSID]')\"><img src='../img/kursi.png'></a>";
		  }
		  
		  echo "
		  <td $c align=center>$edt</td>
		  <td $c align=center>$w1[_UTSHari] - $w1[_UTSTanggal]</td>
		  <td $c align=center>$w1[_UTSJamMulai] - $w1[_UTSJamSelesai]</td>	  
		  <td $c align=center>$editkursi <b>$w1[_JumlahMhswUTS]</b></td>
		  <td class=ul1 align=center>$del</td>
		  </tr>";
	   }
	}
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
    $a[] = "&rsaquo; $w[Nama] <sup>($w[Gelar])</sup>";
  }
  $a = (!empty($a))? "<br />".implode("<br />", $a) : '';
  return $a;
}

function JadwalHps() {
	global $koneksi;
  $id = $_REQUEST['id'];
  $utsid = $_REQUEST['utsid'];
  $s = "update jadwal set JadwalUTSID = 0 where JadwalID = '$id' ";
  $r = mysqli_query($koneksi, $s);
  $s = "delete from jadwaluts where JadwalUTSID = '$utsid' ";
  $r = mysqli_query($koneksi, $s);
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=", 1);
}

function JadwalHpsSemua() {
	global $koneksi;
  $thn = sqling($_REQUEST['thn']);
  $prd = sqling($_REQUEST['prd']);
  $prg = sqling($_REQUEST['prg']);
  $whr_prg = (empty($prg))? '' : "and ProgramID = '$prg' ";
  // Hapus
  $s = "update jadwal set JadwalUTSID = 0 where TahunID = '$thn' and ProdiID = '$prd' $whr_prg";
  $r = mysqli_query($koneksi, $s);
  $s = "select JadwalID from jadwal where TahunID = '$thn' and ProdiID = '$prd' $whr_prg";
  $r = mysqli_query($koneksi, $s);
  while ($w = mysqli_fetch_array($r)){
  	  $s2 = "delete from jadwaluts where JadwalID = $w[JadwalID] ";
	  $r2 = mysqli_query($koneksi, $s2);
  }
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=", 1);
}

function JdwlEdtScript() {
  echo <<<SCR
  <script>
  function JdwlEdt(md, id, jutsid) {
    var _rnd = randomString();
    lnk = "$_SESSION[ndelox].edit.php?md="+md+"&id="+id+"&jutsid="+jutsid+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=600, height=400, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function JadwalHps(id,utsid) {
    if (confirm("Anda yakin akan menghapus jadwal ini?")) {
      var _rnd = randomString();
      window.location = "?ndelox=$_SESSION[ndelox]&BypassMenu=1&lungo=JadwalHps&id="+id+"&utsid="+utsid+"&_rnd="+_rnd;
    }
  }
  function JadwalHpsSemua(thn, prd, prg) {
    var psn = (prg == "")? "Anda juga akan menghapus semua jadwal dari semua program pendidikan." : "";
    if (confirm("Anda yakin akan menghapus semua jadwal ini? "+psn)) {
      var _rnd = randomString();
      window.location = "?ndelox=$_SESSION[ndelox]&BypassMenu=1&lungo=JadwalHpsSemua&thn=" + thn + "&prd=" + prd + "&prg=" + prg+"&_rnd="+_rnd;
    }
  }
  function EdtKursi(id) {
    var _rnd = randomString();
	lnk = "$_SESSION[ndelox].pilihkursi.php?id="+id+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=1000, height=600, scrollbars, status, resizable");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
SCR;
}
?>

</BODY>
</HEAD>
