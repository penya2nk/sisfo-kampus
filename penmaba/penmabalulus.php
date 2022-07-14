<?php

$gels = AmbilFieldx('pmbperiod', "KodeID='".KodeID."' and NA", 'N', "*");
$gelombang = $gels['PMBPeriodID'];

$_pmbNama = GainVariabelx('_pmbNama');
$_pmbFrmID = GainVariabelx('_pmbFrmID');
$_pmbPrg = GainVariabelx('_pmbPrg');
$_pmbNomer = GainVariabelx('_pmbNomer');
$_pmbPage = GainVariabelx('_pmbPage');
$_pmbUrut = GainVariabelx('_pmbUrut', 0);
$arrUrut = array('Nomer PMB~p.PMBID asc, p.Nama', 'Nomer PMB (balik)~p.PMBID desc, p.Nama', 'Nama~p.Nama', 'Nilai Ujian Tertinggi~p.NilaiUjian DESC');

TitleApps("PROSES KELULUSAN PMB - $gels[Nama]");
if (empty($gelombang)) {
  echo PesanError("Error",
    "Tidak ada gelombang PMB yang aktif.<br />
    Aktifkan salah satu gelombang terlebih dahulu.<br />
    Untuk mengaktifkan: <a href='?ndelox=pmbsetup'>Modul PMB Setup</a>");
}
else {
  $lungo = (empty($_REQUEST['lungo']))? 'DftrPMB' : $_REQUEST['lungo'];
  $lungo($gels, $gelombang);
}

function ViewHeaderx($gels, $gel) {
  //IsiFormulirScript($gel);
  //CetakKartuScript();
  EditNilaiScript();
  $optfrm = AmbilCombo2('pmbformulir', 'Nama', 'Nama', $_SESSION['_pmbFrmID'],
    "KodeID='".KodeID."'", 'PMBFormulirID');
  $optprg = AmbilCombo2('program', "concat(ProgramID, ' - ', Nama)", 'ProgramID', $_SESSION['_pmbPrg'], "KodeID='".KodeID."'", 'ProgramID');
  $opturut = GetUrutanPMB();
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <div align='center'>

  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='lungo' value='' />
  <input type=hidden name='_pmbPage' value='0' />
<input style='height:30px' placeholder='Nama' type=text name='_pmbNama' value='$_SESSION[_pmbNama]' size=12 maxlength=30 />&nbsp;
<input type=text style='height:30px' placeholder='No Formulir' name='_pmbNomer' value='$_SESSION[_pmbNomer]' size=12 maxlength=30 />&nbsp;
FORMULIR <select style='height:30px' name='_pmbFrmID'>$optfrm</select>
PROGRAM <select style='height:30px' name='_pmbPrg'>$optprg</select>
        <input  class='btn btn-success btn-sm' type=submit name='Submit' value='Lihat Data' />
      <input  class='btn btn-primary btn-sm' type=button name='ExportXL' value='Exp XLS' 
        onClick=\"window.location='$_SESSION[ndelox].XL.php?gel=$gel'\" />
      <!--
	  <input  class='btn btn-warning btn-sm' type=button name='ImportXL' value='Upload Data XLS' />
      -->
	  <input  class='btn btn-info btn-sm' type=button name='CetakPengumuman' value='Print Info'
        onClick=\"location='$_SESSION[ndelox].pengumuman.php?PMBPeriodID=$gel'\" />
      <input  class='btn btn-danger btn-sm' type=button name='CetakSurat' value='Print Surat Lulus'
        onClick=\"CetakSemuaSuratKelulusan()\" />
    
  </form>
 </div>
  </div>
</div>
</div>";
}

function GetUrutanPMB() {
  global $arrUrut;
  $a = ''; $i = 0;
  foreach ($arrUrut as $u) {
    $_u = explode('~', $u);
    $sel = ($i == $_SESSION['_pmbUrut'])? 'selected' : '';
    $a .= "<option value='$i' $sel>". $_u[0] ."</option>";
    $i++;
  }
  return $a;
}

function DftrPMB($gels, $gel) {
	global $koneksi;
  ViewHeaderx($gels, $gel);
  

  $hr = "<hr size=1 color=silver />";
  $rs = "rowspan=3";
  $rsm = "rowspan=2";
  // Urutan
  $_urut = $arrUrut[$_SESSION['_pmbUrut']];
  $__urut = explode('~', $_urut);
  $urut = "order by ".$__urut[1];
  // Filter formulir
  $whr = array();
  if (!empty($_SESSION['_pmbFrmID'])) $whr[] = "p.PMBFormulirID='$_SESSION[_pmbFrmID]'";
  if (!empty($_SESSION['_pmbPrg']))   $whr[] = "p.ProgramID = '$_SESSION[_pmbPrg]' ";
  if (!empty($_SESSION['_pmbNama']))  $whr[] = "p.Nama like '%$_SESSION[_pmbNama]%'";
  if (!empty($_SESSION['_pmbNomer'])) $whr[] = "p.PMBID like '%$_SESSION[_pmbNomer]%'";
  
  $_whr = implode(' and ', $whr);
  $_whr = (empty($_whr))? '' : 'and '.$_whr;
  


  $s = "select p.PMBID, p.Nama, p.Kelamin, p.ProdiID, p.Pilihan1, p.Pilihan2, p.Pilihan3, p.LulusUjian, p.GradeNilai, p.NilaiUjian,
    p.DetailNilai, p.NilaiUjian, p.GradeNilai, p.LulusUjian as LU, p.NA, p.NilaiSekolah, 
    p.AsalSekolah, _prg.Nama as PRG, w.WawancaraID, p.PrestasiTambahan,
    if(a.Nama like '_%', a.Nama, 
		if(pt.Nama like '_%', pt.Nama, p.AsalSekolah)) as _NamaSekolah,
	concat('&bull; ', replace(p.PrestasiTambahan, '~', '<br />&bull; ')) as PT,
    f.Nama as FRM, _p1.Nama as P1, _p2.Nama as P2, _p3.Nama as P3,
    _sta.Nama as STA from pmb p
    left outer join pmbformulir f on p.PMBFormulirID = f.PMBFormulirID
    left outer join program _prg on p.ProgramID = _prg.ProgramID
    left outer join prodi _p1 on p.Pilihan1 = _p1.ProdiID
    left outer join prodi _p2 on p.Pilihan2 = _p2.ProdiID
    left outer join prodi _p3 on p.Pilihan3 = _p3.ProdiID
    left outer join statusawal _sta on p.StatusAwalID = _sta.StatusAwalID
    left outer join wawancara w on p.PMBID = w.PMBID and w.Tanggal = (select max(Tanggal) from wawancara where PMBID=p.PMBID group by PMBID)
	left outer join asalsekolah a on p.AsalSekolah = a.SekolahID
	left outer join perguruantinggi pt on p.AsalSekolah = pt.PerguruanTinggiID
	where p.KodeID = '".KodeID."' 
  and p.PMBPeriodID='$gel'
$_whr"; //$urut

echo"
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id='example1' class='table table-sm table-striped' style='width:100%' align='center'> 
    <thead>
    <tr style='background:purple;color:white'>
    <th class=ttl>#</th>
    <th class=ttl>PMB #</th>
    <th class=ttl>Nama Asal Sekolah</th>
    <th class=ttl>Formulir Program</th>   
    <th class=ttl>Prestasi Tambahan</th>
    <th class=ttl>Lulus Ujian?</th>
    <th class=ttl>GradeNilai</th>
    <th>Nilai & Kelulusan</th>
    </tr>
    </thead>
    <tbody>";
    $r = mysqli_query($koneksi, $s);
    $no=0;
    while($w = mysqli_fetch_array($r)){
      if ($w['LulusUjian']=='Y'){
        $c="style=color:green";
      }else{
        $c="style=color:black";
      }
      $no++;		
echo "<tr $c>
    <td class=inp width=40 >$no</td>
    <td class=inp width=80 ><b>$w[PMBID]</b></td>
    <td class=inp width=350 >$w[Nama]</td>
    <td class=inp width=180 >$w[FRM]</td>
    <td class=inp width=280 >$w[PrestasiTambahan]</td>
    <td class=ul1 width=184 align=left><b>$w[LulusUjian] - $w[NilaiUjian]</b></td>
    <td class=ul1 width=184 align=left><b>$w[GradeNilai]</b></td>
    <td align='left' width=184> 
    <a href='#' onClick=\"javascript:EditNilai('$w[PMBID]')\" />Nilai & Kelulusan USM</a>
    <a href='#' onClick=\"javascript:CetakSuratKelulusan('$w[PMBID]', '$w[WawancaraID]')\" />$w[PRINT]</a>
    </td>
    </tr>";
	}
echo"</tbody>
</table>

  </div>
</div>
</div>";

}

function EditNilaiScript() {
  echo <<<SCR
  <script>
  function EditNilai(PMBID) {
    lnk = "$_SESSION[ndelox].nilai.php?PMBID="+PMBID;
    win2 = window.open(lnk, "", "width=500, left=400,top=200, height=350, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function CetakSuratKelulusan(PMBID, WID) {
	lnk = "$_SESSION[ndelox].suratlulus.php?PMBID="+PMBID+"&WID="+WID;
    win2 = window.open(lnk, "", "width=500, left=400, top=200, height=350, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function CetakSemuaSuratKelulusan() {
	lnk = "$_SESSION[ndelox].suratlulus.php?PMBID="+0+"&WID="+0;
    win2 = window.open(lnk, "", "width=500, left=400, top=200, height=350, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
SCR;
}
?>
