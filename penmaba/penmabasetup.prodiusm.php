<?php
$_usmProdiID = GainVariabelx('_usmProdiID');

$sub = (empty($_REQUEST['sub']))? 'DftrProdi' : $_REQUEST['sub'];
$sub();

function DftrProdi() {
  // container
  $f = GetProdi();
  $u = GetUSMProdi();
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:70%' align='center'>
    <tr><td class=ul valign=top width=350>
        $f
        </td>
        <td class=ul1 valign=top>
        $u
        </td>
        </tr>
    </table></div>
    </div>
    </div>
    </p>";
}
function GetProdi() {
	global $koneksi;
  $s = "select p.ProdiID, p.FakultasID, p.Nama, p.JenjangID,
    f.Nama as FAK
    from prodi p
      left outer join fakultas f on p.FakultasID = f.FakultasID
    where p.KodeID = '".KodeID."' and p.NA = 'N'
    order by p.FakultasID, p.ProdiID";
  $r = mysqli_query($koneksi, $s);
  
  $a = "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'><table class=box cellspacing=1 width=100%>
    <tr style='background:purple;color:white'>
    <th class=ttl colspan=4>Daftar Prodi</th>
    </tr>";
  $f = 'laskdjfairujfa;ldnf;asdf';
  while ($w = mysqli_fetch_array($r)) {
    if ($f != $w['FakultasID']) {
      $f = $w['FakultasID'];
      $a .= "<tr>
        <td class=ul1 colspan=4>
        $w[FakultasID] <b>$w[FAK]</b>
        </td></tr>";
    }
    if ($w['ProdiID'] == $_SESSION['_usmProdiID']) {
      $ki = "&raquo;";
      $ka = "&laquo;";
      $c = "class=inp1";
    }
    else {
      $ki = '&nbsp;';
      $ka = '&nbsp;';
      $c = "class=ul1";
    }
    $a .= "<tr>
      <td $c width=10>$ki</td>
      <td class=inp width=40>$w[ProdiID]</td>
      <td $c><a href='?ndelox=$_SESSION[ndelox]&lungo=prodiusm&sub=&_usmProdiID=$w[ProdiID]'>$w[Nama]</a></td>
      <td $c width=10>$ka</td>
      </tr>";
  }
  $a .= "</table></div>
  </div>
  </div>";
  return $a;
}

function GetUSMProdi() {
	global $koneksi;
  EditUSMScript();
  $gel = AmbilOneField("pmbperiod","NA='N' and KodeID",KodeID,"PMBPeriodID");
  
  $s = "select u.*, usm.Nama as _USM,
    date_format(u.TanggalUjian, '%d-%m-%Y') as _TanggalUjian,
	LEFT(u.JamMulai, 5) as _JM,
	LEFT(u.JamSelesai, 5) as _JS
    from prodiusm u
      left outer join pmbusm usm on u.PMBUSMID = usm.PMBUSMID
    where u.KodeID = '".KodeID."' and u.PMBPeriodID = '$gel'
      and INSTR(concat('|', u.ProdiID, '|'), concat('|', '$_SESSION[_usmProdiID]', '|')) != 0
    order by u.PMBPeriodID desc, u.Urutan asc";
  $r = mysqli_query($koneksi, $s);
  
  $a = "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'><table class=box cellspacing=1 width=100%>
    <tr style='background:purple;color:white'><td class=ul1 width=30><input type=button name='Tambah' value='+'
          onClick=\"javascript:EditUSM(1,'$_SESSION[_usmProdiID]', 0)\" /></td>
        <th class=ttl colspan=6>
        Daftar USM yg harus ditempuh Cama
        </th>
    </tr>
    <tr><th class=ttl>#</th>
		<th class=ttl width=80>Gelombang</th>
        <th class=ttl>Ujian</th>
        <th class=ttl width=80>Tgl Ujian</th>
		<th class=ttl width=40>Jam Ujian</th>
        <th class=ttl width=50>Ruang</th>
        <th class=ttl width=10>&nbsp;</th>
        </tr>";
  while ($w = mysqli_fetch_array($r)) {
    $a .= "<tr>
      <td class=ul1 align=center>
        <a href='#' onClick=\"javascript:EditUSM(0, '$_SESSION[_usmProdiID]', $w[ProdiUSMID])\" /><i class='fa fa-edit'></i></a> $w[Urutan]
        </td>
	  <td class=ul1 align=center>$w[PMBPeriodID]</td>
      <td class=ul1>$w[_USM]</td>
      <td class=ul1 align=center>$w[_TanggalUjian]</td>
      <td class=ul1 align=center>$w[_JM] - $w[_JS]</td>
	  <td class=ul1 align=right>$w[RuangID]</td>
      <td class=ul1 align=center>
        <a href='#' onClick=\"javascript:DelUSM($w[ProdiUSMID])\"><img src='img/del.png' /></a>
        </td>
      </tr>";
  }
  $a .= "</table></div>
  </div>
  </div>";
  return $a;
}
function DelUSM() {
	global $koneksi;
  $id = $_REQUEST['id'];
  $s = "delete from prodiusm where ProdiUSMID = '$id' ";
  $r = mysqli_query($koneksi, $s);
  echo "<script>window.location='index.php?ndelox=$_SESSION[ndelox]&lungo=prodiusm'</script>";
}
function EditUSMScript() {
  echo <<<SCR
  <script>
  function EditUSM(MD,PRD,ID) {
    lnk = "$_SESSION[ndelox].prodiusm.edit.php?md="+MD+"&prd="+PRD+"&id="+ID;
    win2 = window.open(lnk, "", "width=400, height=400, left=500, top=100, scrollbars, status, resizable");
    if (win2.opener == null) childWindow.opener = self;
  }
  function DelUSM(ID) {
    if (confirm('Benar Anda akan menghapus data ini?')) {
      window.location = 'index.php?ndelox=$_SESSION[ndelox]&lungo=prodiusm&sub=DelUSM&BypassMenu=1&id='+ID;
    }
  }
  </script>
SCR;
}

?>
