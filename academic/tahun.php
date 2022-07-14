<?php
$prodi = GainVariabelx('prodi');
$prid  = GainVariabelx('prid');
$page  = GainVariabelx('page', 1);

TitleApps("Setup Tahun Akademik per Program Studi");
//TampilkanPilihanProdiProgram($ndelox='', $lungo='', $pref='', $token='') {
TampilkanPilihanProdiProgram($_SESSION['ndelox'], "");

$lungo = (empty($_REQUEST['lungo']))? 'ListTahun' : $_REQUEST['lungo'];
$lungo();

function ListTahun() {
  $_maxbaris = 10;
  $fmtTgl = '%d-%m-%Y';
  TahunEditScript();
  include_once "class/dwolister.class.php";
  
  $page = GainVariabelx('page', 1);
  $pagefmt = "<a href='?ndelox=$_SESSION[ndelox]&lungo=&page==PAGE='>=PAGE=</a>";
  $pageoff = "<b>=PAGE=</b>";

  $brs = "<hr size=1 color=silver />";
  $gantibrs = "<tr><td bgcolor=silver height=1 colspan=11></td></tr>";
  $lst = new dwolister;
  $lst->tables = "tahun t
    where t.KodeID = '".KodeID."'
      and t.ProdiID = '$_SESSION[prodi]'
      and t.ProgramID = '$_SESSION[prid]'
    order by t.TahunID desc";
  $lst->fields = "t.TahunID, t.Nama, t.ProgramID, t.ProdiID, 
    t.SP,t.NA,
    date_format(t.TglKuliahMulai, '$fmtTgl') as _KuliahMulai,
    date_format(t.TglKuliahSelesai, '$fmtTgl') as _KuliahSelesai,
    date_format(t.TglKRSMulai, '$fmtTgl') as _KRSMulai,
    date_format(t.TglKRSSelesai, '$fmtTgl') as _KRSSelesai,
	date_format(t.TglBayarMulai, '$fmtTgl') as _BayarMulai,
    date_format(t.TglBayarSelesai, '$fmtTgl') as _BayarSelesai,
    date_format(t.TglUTSMulai, '$fmtTgl') as _UTSMulai,
    date_format(t.TglUTSSelesai, '$fmtTgl') as _UTSSelesai,
    date_format(t.TglUASMulai, '$fmtTgl') as _UASMulai,
    date_format(t.TglUASSelesai, '$fmtTgl') as _UASSelesai,
    date_format(t.TglNilai, '$fmtTgl') as _TglNilai,
    t.Catatan, t.ProsesBuka
    ";
  $lst->page = $_SESSION['page']+0;
  $lst->maxrow = $_maxbaris;
  $lst->pages = $pagefmt;
  $lst->pageactive = $pageoff;
  $lst->headerfmt = "<p>
  <div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>
    <tr >
      <td class=ul1 colspan=9>
      <input type=button name='BuatTahun' value='Buat Tahun Baru' 
        onClick=\"javascript:TahunEdit('', '$_SESSION[prodi]', '$_SESSION[prid]', 1)\" />
      <input type=button name='Refresh' value='Refresh'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />
      </td>
    </tr>
    <tr style='background:purple;color:white'>
      <th class=ttl colspan=3 style='text-align:center'>Kode</th>
      <th class=ttl width='200'>Nama Tahun</th>
      <th class=ttl width='200' style='text-align:center'>Perkuliahan</th>
	    <th class=ttl width='200' style='text-align:center'>KRS</th>
      <th class=ttl width='200' style='text-align:center'>Pembayaran</th>
      <th class=ttl width='200' style='text-align:center'>UTS</th>
      <th class=ttl width='200' style='text-align:center'>UAS</th>
      <th class=ttl width='200' style='text-align:center'>Penilaian</th>
	    <th class=ttl>NA</th>
    </tr>";
  $lst->detailfmt = "<tr>
    <td class=ul1 width=5>
      <a href='#' onClick=\"javascript:TahunCetak('=TahunID=', '=ProdiID=', '=ProgramID=')\"><i class='fa fa-print'></i></a>
      </td>
	<td class=ul1 width=40 align=center><font color=green><b>=TahunID=</b><font></td>
    <td class=ul1 width=5>
      <a href='#' onClick=\"javascript:TahunEdit('=TahunID=', '=ProdiID=', '=ProgramID=', 0)\"><i class='fa fa-edit'></i></a>
      </td>
    <td class=ul1><b>=Nama=</b>=Catatan=</td>
    <td class=cna=NA= align=center width=80>=_KuliahMulai= s/d =_KuliahSelesai=</td>
    <td class=cna=NA= align=center width=80>=_KRSMulai= s/d =_KRSSelesai=</td>
	  <td class=cna=NA= align=center width=80>=_BayarMulai= s/d =_BayarSelesai=</td>
    <td class=cna=NA= align=center width=80>=_UTSMulai= s/d =_UTSSelesai=</td>
    <td class=cna=NA= align=center width=80>=_UASMulai= s/d =_UASSelesai=</td>
    <td class=cna=NA= align=center width=80>=_TglNilai=</td>
	  <td class=ul1 width=5><img src='img/book=NA=.gif' /></td>
    </tr>".$gantibrs;
  $lst->footerfmt = "</table>";
  $hal = $lst->TampilkanHalaman();
  $ttl = $lst->MaxRowCount;
  echo $lst->TampilkanData();
  echo "<p align=center>Hal: $hal <br />(Tot: $ttl)</p>";
}
/*
<br />
      <div align=right>Proses: <a href='#'><img src='img/gear.gif' width=16 /></a>
        (=ProsesBuka=&times;)
        </div>
*/
function TahunEditScript() {
  echo <<<SCR
  <script>
  function TahunCetak(TahunID, ProdiID, ProgramID) {
    lnk = "$_SESSION[ndelox].cetak.php?TahunID="+TahunID+"&ProdiID="+ProdiID+"&ProgramID="+ProgramID;
    win2 = window.open(lnk, "", "width=700, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function TahunEdit(TahunID, ProdiID, ProgramID, md) {
    lnk = "$_SESSION[ndelox].edit.php?TahunID="+TahunID+"&ProdiID="+ProdiID+"&ProgramID="+ProgramID+"&md="+md;
    win2 = window.open(lnk, "", "width=700, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
SCR;
}
?>
