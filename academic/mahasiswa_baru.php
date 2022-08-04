<?php
// error_reporting(0);
include_once "$_SESSION[ndelox].lib.php";

$gels = AmbilFieldx('pmbperiod', "KodeID='".KodeID."' and NA", 'N', "PMBPeriodID, Nama");
$gelombang = $gels['PMBPeriodID'];

$_pmbNama = GainVariabelx('_pmbNama');
$_pmbFrmID = GainVariabelx('_pmbFrmID');
$_pmbPrg = GainVariabelx('_pmbPrg');
$_pmbNomer = GainVariabelx('_pmbNomer');
$_pmbPage = GainVariabelx('_pmbPage');
$_pmbUrut = GainVariabelx('_pmbUrut', 0);

$arrUrut = array('Nomer PMB~p.PMBID asc, p.Nama', 'Nomer PMB (balik)~p.PMBID desc, p.Nama', 'Nama~p.Nama');

TitleApps("PROSES MAHASISWA BARU : $gels[Nama]");
if (empty($gelombang)) {
  echo PesanError("Error",
    "Tidak ada gelombang PMB yang aktif.<br />
    Hubungi Kepala PMB untuk mengaktifkan gelombang.");
}
else {
  $lungo = (empty($_REQUEST['lungo']))? 'DftrCama' : $_REQUEST['lungo'];
  $lungo($gels, $gelombang);
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

function TampilkanHeader($gels, $gel) {
  $optfrm = AmbilCombo2('pmbformulir', 'Nama', 'Nama', $_SESSION['_pmbFrmID'],
    "KodeID='".KodeID."'", 'PMBFormulirID');
  $optprg = AmbilCombo2('program', "concat(ProgramID, ' - ', Nama)", 'ProgramID', $_SESSION['_pmbPrg'], "KodeID='".KodeID."'", 'ProgramID');
  $opturut = GetUrutanPMB();
  echo "<div class='card'>
  <div class='card-header'>
  <div align='center'>

  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='lungo' value='' />
  <input type=hidden name='_pmbPage' value='0' />
  FORMULIR <select style='height:30px' name='_pmbFrmID'>$optfrm</select>
      <input style='height:30px' type=text placeholder='NAMA' name='_pmbNama' value='$_SESSION[_pmbNama]' size=15 maxlength=30 />
      
      <input style='height:30px' placeholder='NO FORM PMB' type=text name='_pmbNomer' value='$_SESSION[_pmbNomer]' size=15 maxlength=30 /></td>     
      PROGRAM <select style='height:30px' name='_pmbPrg'>$optprg</select>
      <input  class='btn btn-success btn-sm' type=submit name='Submit' value='Lihat Data' />
      <input  class='btn btn-primary btn-sm' type=button name='Reset' value='Reset'
       onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=&_pmbPage=0&_pmbNama=&_pmbNomer='\" />

  </form>
</div>
</div>
</div>";

}

function DftrCama($gels, $gel) {
  TampilkanHeader($gels, $gel);
  
  global $_maxbaris, $arrUrut, $koneksi;

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
  $NIMSementara = AmbilOneField('prodi', 'ProdiID', $pmb['ProdiID'], 'GunakanNIMSementara');
  $pagefmt = "<a href='?ndelox=$_SESSION[ndelox]&lungo=&_pmbPage==PAGE='>=PAGE=</a>";
  $pageoff = "<b>=PAGE=</b>";

  $brs = "<hr size=1 color=silver />";
  $gantibrs = "<tr ><td bgcolor=silver height=1 colspan=11></td></tr>";

  $sqx = "select p.PMBID, p.MhswID, p.Nama, p.Kelamin, 
    p.ProdiID, p.Pilihan1, p.Pilihan2, p.Pilihan3,
    f.Nama as FRM, p.LulusUjian, m.NIMSementara,
    _p.Nama as Prodi,
    _sta.Nama as STA, _prg.Nama as PRG,
    format(p.TotalBiaya, 0) as _TotalBiaya,
    format(p.TotalBayar, 0) as _TotalBayar,
    if (p.LulusUjian = 'Y', 
      if (p.MhswID is NULL or p.MhswID = '',
      concat('<a href=\'?ndelox=$_SESSION[ndelox]&lungo=MhswBaruEdt&PMBID=', p.PMBID, '\'>
      <img src=\'img/bayar.png\' width=25 title=\'Pembayaran dan pemrosesan Cama\' /></a>'),
      '<img src=\'img/cama.png\' width=25 title=\'Sudah diproses menjadi mahasiswa\' />'), '&times') as EDT,
	  if (p.LulusUjian = 'Y',
	  concat('<a href=\'#\' onClick=\"javascript:CetakKartu(\'', p.PMBID, '\',\'', m.NIMSementara, '\')\" /><img src=\'img/print.png\' /></a>'),
	  '&nbsp;') as _ktm from pmb p 
	left outer join pmbformulir f on p.PMBFormulirID = f.PMBFormulirID
	left outer join prodi _p on p.ProdiID = _p.ProdiID
	left outer join program _prg on p.ProgramID = _prg.ProgramID
	left outer join statusawal _sta on p.StatusAwalID = _sta.StatusAwalID
	left outer join mhsw m on m.MhswID = p.MhswID  
	where p.KodeID = '".KodeID."' 
  and p.PMBPeriodID='$gel'
	$_whr";
	$r = mysqli_query($koneksi, $sqx);

echo"<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  
  <table id='example1' class='table table-sm table-bordered table-striped'>
    <thead>
	<tr style='background:purple;color:white'>
    <th class=ttl>#</th>
    <th class=ttl style='text-align:center'>PMB</th>
    <th class=ttl >Nama</th>
    <th class=ttl style='text-align:left'>Status Mhs</th>
    <th class=ttl style='text-align:center'>Lulus Ujian?</th>
    <th class=ttl>Formulir Program</th>
    <th class=ttl>Prodi</th>
    <th class=ttl style='text-align:center'>NIM</th>
    <th class=ttl style='text-align:right'>BIPOT
      <a href='#' onClick=\"alert('Jumlah Biaya yg harus dibayarkan oleh Cama pada tahap 1 pembayaran')\"><img src='img/info.gif' /></a>
      Bayar</th>
  
    <th class=ttl style='text-align:center'>Aksi</th>
    </tr>
	</thead>
	<tbody>";
	  while ($w = mysqli_fetch_array($r)) {
      if ($w['LulusUjian']=='Y'){
        $c="style=color:green";
        $ket ="Lulus";
      }else{
        $c="style=color:black";
        $ket ="On Progress";
      }
    $n++;
	echo"<tr $c>
    <td class=inp width=20 align=center>$n</td>
   
    <td class=ul  align=center>$w[PMBID]</td>
    <td width=350>$w[Nama]</td>
    <td align=left>$w[STA]</td>
    <td align=center>$ket</td>
    <td>$w[FRM] - $w[PRG]&nbsp;</td>
    <td>$w[ProdiID] $w[Prodi]</td>
    <td class=ul  align=center>$w[MhswID]</td>
    <td align=right>$w[_TotalBiaya] / $w[_TotalBayar]</td>

	  <td width=90 align=center>$w[EDT]</td>

    </tr>";
	  }
		echo"</tbody>
	</table>
  </div>
</div>
</div>
	<script>
		function CetakKartu(pmbid,ob)
		{	
			if (ob == 'Y'){
				alert ('Mahasiswa ini masih memiliki NIM Sementara. \\n Lakukan Konversi NIM terlebih dahulu');
			} else {
				lnk = \"$_SESSION[ndelox].ktm.php?pmbid=\"+pmbid;
				  win2 = window.open(lnk, \"\", \"width=600, height=400, scrollbars, resizable, status\");
				  if (win2.opener == null) childWindow.opener = self;
			 }
		}
	</script>";
}

function MhswBaruEdt($gels, $gel) {
  $PMBID = $_REQUEST['PMBID'];
  $pmb = AmbilFieldx("pmb 
    left outer join prodi prd on pmb.ProdiID = prd.ProdiID
    left outer join program prg on pmb.ProgramID = prg.ProgramID
    left outer join statusawal sta on pmb.StatusAwalID = sta.StatusAwalID 
    left outer join asalsekolah a on pmb.AsalSekolah = a.SekolahID
	left outer join perguruantinggi pt on pmb.AsalSekolah = pt.PerguruanTinggiID",
	"pmb.KodeID='".KodeID."' and pmb.PMBID", 
    $PMBID,
    "pmb.*, prd.Nama as PROD, prg.Nama as PROG,
	if(a.Nama like '_%', a.Nama, 
		if(pt.Nama like '_%', pt.Nama, pmb.AsalSekolah)) as _NamaSekolah,
    sta.Nama as STAWAL"
    );
  if (empty($pmb))
    echo PesanError('Error',
      "Calon Mahasiswa dengan nomer PMB: <b>$PMBID</b> tidak ditemukan.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      Opsi: <input type=button name='Kembali' value='Kembali'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />");
  else MhswBaruEdt1($gels, $gel, $pmb);
}
function MhswBaruEdt1($gels, $gel, $pmb) {
  TampilkanHeaderMhswBaruEdt($gels, $gel, $pmb);
  if (empty($pmb['MhswID'])) {
    if (empty($pmb['BIPOTID'])) AmbilBIPOTID($pmb);
    TampilkanDataBIPOT($gels, $gel, $pmb);
    TampilkanDataBayar($gels, $gel, $pmb);
  }
  else echo Info('Telah Diproses',
    "Calon Mahasiswa ini telah diproses menjadi mahasiswa.<br />
    Silakan hubungi BAA untuk informasi lebih lanjut.<br />
    Atau hubungi Sysadmin untuk keterangan lebih lanjut.
    <hr size=1 color=silver />
    <input type=button name='Kembali' value='Kembali'
      onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />");
}
function AmbilBIPOTID($pmb) {
  global $koneksi;
  $bipot = AmbilFieldx('bipot', "KodeID='".KodeID."' and NA='N' and `Def`='Y' 
    and ProgramID='$pmb[ProgramID]' and ProdiID",
    $pmb['ProdiID'], '*');
  $bipot['BIPOTID'] += 0;

  if ($bipot['BIPOTID'] == 0)
    die(PesanError('Error',
      "Belum ada master BIPOT (biaya & potongan) untuk Program: <b>$pmb[ProgramID]</b> dan
      Program-Studi: <b>$pmb[ProdiID]</b>.<br />
      Coba hubungi Kepala BAU/BAA untuk setup master BIPOT.<br />
      Atau hubungi Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      <input type=button name='Kembali' value='Kembali'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />"));
  else {
    $s = "update pmb set BIPOTID = '$bipot[BIPOTID]' where KodeID='".KodeID."' and PMBID='$pmb[PMBID]' ";
    $r = mysqli_query($koneksi, $s);
    echo Info('Update Data',
      "Updating Data...<br />
      Please wait a second.");
    echo "<script>window.location='?ndelox=$_SESSION[ndelox]&lungo=MhswBaruEdt&PMBID=$pmb[PMBID]';</script>";
  }
}
function BuatSummaryBIPOT($pmb) {
  $TotalBiaya = number_format($pmb['TotalBiaya']);
  $TotalBayar = number_format($pmb['TotalBayar']);
  $Sisa = $pmb['TotalBiaya'] - $pmb['TotalBayar'];
  $_Sisa = number_format($Sisa);
  $color = ($Sisa > 0)? "color=red" : '';
  return "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>
  <tr style='background:purple;color:white'>
  <td class=inp width=30% style='text-align:right'>Total Biaya</td>
      <td class=inp width=30% style='text-align:right'>Total Bayar</td>
      <td class=inp style='text-align:right'>Kekurangan</td>
      </tr>
  <tr><td align=right>$TotalBiaya</td>
      <td align=right>$TotalBayar</td>
      <td align=right><font size=+1 $color>$_Sisa</font></td>
  </table>
  </div>
</div>
</div>";
}
function TampilkanHeaderMhswBaruEdt($gels, $gel, $pmb) {
  // Jika belum menjadi Mhsw, maka lakukan perhitungan bipot & pembayaran
  if (empty($pmb['MhswID'])) {
    $MhswID = '&nbsp;';
    // Cek data pembayaran Calon Siswa
    $TotalBiaya = AmbilOneField("bipotmhsw bm
      left outer join bipot2 b2 on bm.BIPOT2ID = b2.BIPOT2ID",
      "bm.PMBMhswID = 0 and bm.KodeID = '".KodeID."' and b2.SaatID = 1
      and bm.TahunID = '$pmb[PMBPeriodID]' and bm.PMBID", $pmb['PMBID'],
      "sum(bm.TrxID * bm.Jumlah * bm.Besar)")+0;
    $TotalBayar = AmbilOneField('bayarmhsw',
      "PMBMhswID = 0 and KodeID = '".KodeID."'
      and TahunID = '$pmb[PMBPeriodID]' and PMBID", $pmb['PMBID'],
      "sum(Jumlah)")+0;
//    if (($TotalBayar > 0) && ($TotalBiaya - $TotalBayar <= 0)) {
      if (($TotalBayar > 0) && ($TotalBiaya - $TotalBayar >= 0)) {	
      KonfirmasiProsesNIMScript();
      $TombolProses = "&raquo; <input class='btn-danger' type=button name='Proses' value='Proses NIM' 
        onClick=\"javascript:KonfirmasiProsesNIM('$pmb[PMBID]')\" /> &laquo; <br />";
    }
    else {
      $TombolProses = '&nbsp;';
    }
    $Tombol2 = "<input class='btn-danger' type=button name='Proses' value='Proses BIPOT'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&BypassMenu=1&lungo=ProsesBIPOT&PMBID=$pmb[PMBID]'\" />
      <input class='btn-primary' type=button name='HapusSemua' value='Hapus Semua BIPOT' 
        onClick=\"javascript:BIPOTDELALLCONF('$pmb[PMBID]')\" />
      <input class='btn-danger' type=button name='TambahBipot' value='Tambah Bipot'
        onClick=\"javascript:BIPOTEdit('$pmb[PMBID]', 1, 0)\" />
      <input class='btn-warning' type=button name='TambahBayar' value='Tambah Pembayaran'
        onClick=\"javascript:ByrEdit('$pmb[PMBID]', 1, 0, 0)\" />
	  <input class='btn-success' type=button name='Tagihan' value='Print Tagihan' onClick=\"PrintTagihan('$pmb[PMBID]')\">";
  }
  else {
    $MhswID = "&raquo; <b>$pmb[MhswID]</b>";
    $Tombol2 = '';
  }
  $summary = BuatSummaryBIPOT($pmb);
  $arrPT = explode('~', $pmb['PrestasiTambahan']);
  foreach($arrPT as $Prestasi) 
  {	if(!empty($Prestasi)) $PrestasiTambahan .= (empty($PrestasiTambahan))? $Prestasi : "<br>".$Prestasi;
  }
  $GradePMB = AmbilOneField('pmbgrade', "NilaiUjianMin <= $pmb[NilaiUjian] and $pmb[NilaiUjian] <= NilaiUjianMax and KodeID", KodeID, 'GradeNilai');
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  
  <table class=box cellspacing=1 align=center width=800>
  <tr><th class=ttl colspan=4>Data Calon Mahasiswa</th></tr>
  <tr><td class=inp>Nomer PMB:</td>
      <td class=ul1><b>$pmb[PMBID]</b> $MhswID</td>
      <td class=inp>Nama Cama:</td>
      <td class=ul1><b>$pmb[Nama]</b>&nbsp;</td>
      </tr>
  <tr><td class=inp>Program:</td>
      <td class=ul1>$pmb[PROG] <sup>($pmb[ProgramID])</sup></td>
      <td class=inp>Status:</td>
      <td class=ul1>$pmb[STAWAL] <sup>($pmb[StatusAwalID])</sup></td>
      </tr>
  <tr><td class=inp>Program Studi:</td>
      <td class=ul1>$pmb[PROD]&nbsp; <sup>($pmb[ProdiID])</sup></td>
      <td class=inp>Asal Sekolah:</td>
      <td class=ul1>$pmb[_NamaSekolah]&nbsp;</td>
      </tr>
  <tr><td class=inp>GradeNilai:</td>
      <td class=ul1>$pmb[NilaiUjian]&nbsp; <sup>(Grade PMB: $GradePMB)</sup></td>
      <td class=inp>Prestasi:</td>
      <td class=ul1>$PrestasiTambahan&nbsp;</td>
      </tr>	  
  <tr><td class=ul1 colspan=4>
      $summary
      </td></tr>
  <tr><td class=ul1 colspan=4 align=center>
      $TombolProses
      <input class='btn-success' type=button name='Kembali' value='Kembali'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />
      <input class='btn-primary' type=button name='Refresh' value='Refresh'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=MhswBaruEdt&PMBID=$pmb[PMBID]'\" />
      $Tombol2
      </td></tr>
  </table>
  </div>
</div>
</div>";
}
function HapusBIPOTScript() {
  echo <<<SCR
  <script>
  function BIPOTDELCONF(id, pmbid) {
    if (confirm("Benar Anda akan menghapus BIPOT ini?")) {
      window.location="?ndelox=$_SESSION[ndelox]&lungo=HapusBIPOT&BypassMenu=1&_BIPOTMhswID="+id+"&PMBID="+pmbid;
    }
  }
  function BIPOTDELALLCONF(pmbid) {
    if (confirm("Benar Anda akan menghapus semua biaya di bawah ini? Biaya yang sudah terbayar tidak akan dihapus.")) {
      window.location="?ndelox=$_SESSION[ndelox]&lungo=HapusSemuaBIPOT&BypassMenu=1&PMBID="+pmbid;
    }
  }
  function BIPOTEdit(pmbid, md, id) {
    lnk = "$_SESSION[ndelox].bipotedit.php?pmbid="+pmbid+"&md="+md+"&id="+id;
    win2 = window.open(lnk, "", "width=400, left=450, top=200, height=200, scrollbars, resizable, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function ByrEdit(pmbid, md, bayar, bipotmhsw) {
    lnk = "$_SESSION[ndelox].bayar.php?pmbid="+pmbid+"&md="+md+"&bayar="+bayar+"&bipotmhsw="+bipotmhsw;
    win2 = window.open(lnk, "", "width=600, left=350, top=100, height=500, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function PrintTagihan(pmbid) {
    lnk = "$_SESSION[ndelox].tagihan.php?pmbid="+pmbid;
    win2 = window.open(lnk, "", "width=800, height=600, scrollbars, resizable, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function KonfirmasiHapusBayar(byrid, pmbid) {
    if (confirm("Anda benar akan menghapus data pembayaran ini? Mungkin daftar BIPOT di atas menjadi tidak balance lagi.")) {
      window.location="?ndelox=$_SESSION[ndelox]&lungo=HapusBayar&byrid="+byrid+"&PMBID="+pmbid;
    }
  }
  </script>
SCR;
}
function HapusBIPOT($gels, $gel) {
  global $koneksi;
  $_BIPOTMhswID = $_REQUEST['_BIPOTMhswID']+0;
  $PMBID = sqling($_REQUEST['PMBID']);
  $s = "delete from bipotmhsw where BIPOTMhswID = '$_BIPOTMhswID' ";
  $r = mysqli_query($koneksi, $s);
  HitungUlangBIPOTPMB($PMBID);
  echo "<script>window.location='?ndelox=$_SESSION[ndelox]&lungo=MhswBaruEdt&PMBID=$PMBID'</script>";
}
function HapusSemuaBIPOT($gels, $gel) {
  global $koneksi;
  $PMBID = sqling($_REQUEST['PMBID']);
  
  //$pmb = AmbilFieldx('pmb', "KodeID='".KodeID."' and PMBID", $PMBID, '*');
  $s = "delete from bipotmhsw
        where PMBMhswID = 0
          and PMBID = '$PMBID'
          and Dibayar = 0
          and TahunID = '$gel'
          and KodeID = '".KodeID."' ";
  $r = mysqli_query($koneksi, $s);
  
  HitungUlangBIPOTPMB($PMBID);
  echo "<script>window.location='?ndelox=$_SESSION[ndelox]&lungo=MhswBaruEdt&PMBID=$PMBID'</script>";
}
function HapusBayar($gels, $gel) {
  global $koneksi;
  $PMBID = sqling($_REQUEST['PMBID']);
  $byrid = sqling($_REQUEST['byrid']);
  // Hapus header
  $s = "delete from bayarmhsw
    where BayarMhswID = '$byrid' ";
  $r = mysqli_query($koneksi, $s);
  // Hapus detail
  $s1 = "delete from bayarmhsw2
    where BayarMhswID = '$byrid' ";
  $r1 = mysqli_query($koneksi, $s1);
  HitungUlangBIPOTPMB($PMBID);
  echo "<script>window.location='?ndelox=$_SESSION[ndelox]&lungo=MhswBaruEdt&PMBID=$PMBID'</script>";
}
function TampilkanDataBIPOT($gels, $gel, $pmb) {
  global $koneksi;
  HapusBIPOTScript();
  $s = "select bm.*, s.Nama as _saat,
    format(bm.Jumlah, 0) as JML,
    format(bm.TrxID*bm.Besar, 0) as BSR,
    format(bm.Dibayar, 0) as BYR
    from bipotmhsw bm
      left outer join bipot2 b2 on bm.BIPOT2ID = b2.BIPOT2ID
      left outer join saat s on b2.SaatID = s.SaatID
    where bm.PMBMhswID = 0
      and bm.KodeID = '".KodeID."'
      and bm.PMBID = '$pmb[PMBID]'
    order by b2.Prioritas, bm.TrxID, bm.BIPOTMhswID";
  $r = mysqli_query($koneksi, $s); $n = 0;
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'";
  echo "<tr>
    <th class=ttl colspan=10>Daftar Biaya & Potongan (BIPOT)</th>
    </tr>";
  echo "<tr style='background:purple;color:white'>
    <th class=ttl colspan=2>#</th>
    <th class=ttl>Keterangan</th>
    <th class=ttl style='text-align:right'>Jumlah &times; Besar</th>
    <th class=ttl style='text-align:right'>Total</th>
    <th class=ttl style='text-align:right'>Dibayar</th>
    <th class=ttl style='text-align:center'>Catatan</th>
    <th class=ttl>&times;</th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    $sub = $w['Jumlah'] * $w['Besar'] * $w['TrxID'];
    $_sub = number_format($sub);
    $ttl += $sub;
    $byr += $w['Dibayar'];
    if ($_SESSION['_LevelID'] == 1) {
      //if($w['Dibayar']>0){
      $del = ($w['Dibayar']>0)? '&nbsp;':"<a href='#' onClick=\"BIPOTDELCONF($w[BIPOTMhswID], '$pmb[PMBID]')\"><i class='fa fa-trash'></i></a>";
    }
	$TambahanNama = (empty($w['TambahanNama']))? "" : "($w[TambahanNama])";
    echo "<tr>
      <td class=inp width=15>$n</td>
      <td class=ul width=10>
        <a href='#' onClick=\"javascript:BIPOTEdit('$pmb[PMBID]', 0, $w[BIPOTMhswID])\"><i class='fa fa-edit'></i></a>
        </td>
      <td class=ul>$w[Nama] $TambahanNama  <b>[ $w[_saat] ]</b></td>
      <td class=ul norwap align=right> $w[JML] &times;  $w[BSR]</td>
      <td class=ul align=right nowrap>$_sub</td>
      <td class=ul align=right nowrap >
        $w[BYR] <!--
        <a href='#' onClick=\"javascript:ByrEdit('$pmb[PMBID]', 1, 0, $w[PMBMhswID])\"><i class='fa fa-edit'></i></a>
        -->
        </td>
      <td class=ul1 align=center>$w[Catatan]&nbsp;</td>
      <td class=ul1 align=center width=10>
        $del
        </td>
      </tr>";
  }
  $TTL = number_format($ttl);
  $BYR = number_format($byr);
  $SS = number_format($ttl - $byr);
  echo "<tr><td bgcolor=silver colspan=10 height=1></td></tr>";
  echo "<tr>
    <td class=ul1 colspan=4 align=right><b>Total:</td>
    <td class=ul1 align=right><b>$TTL</b></td>
    <td class=ul1 align=right><b>$BYR</b></td>
    <td class=ul1 colspan=2>Sisa: <font size=+1>$SS</font></td>
    </tr>";
  echo "</table>
  </div>
</div>
</div>";
  
}
function ProsesBIPOT($gels, $gel) {
  global $koneksi;
  $PMBID = sqling($_REQUEST['PMBID']);
  $pmb = AmbilFieldx('pmb', "KodeID='".KodeID."' and PMBID", $PMBID, '*');
  if (empty($pmb))
    die(PesanError('Error',
      "Data Cama dengan nomer PMB: <b>$PMBID</b> tidak ditemukan.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      <input type=button name='Kembali' value='Kembali'
        onClick=\"location='$_SESSION[ndelox]&lungo='\" />"));
  // Ambil BIPOT-nya
  $s = "select * 
    from bipot2 
    where BIPOTID = '$pmb[BIPOTID]'
      and Otomatis = 'Y'
	  and PerMataKuliah = 'N'
	  and PerSKS = 'N'
	  and PerLab = 'N'
      and NA = 'N'
    order by TrxID, Prioritas";
  $r = mysqli_query($koneksi, $s);
  while ($w = mysqli_fetch_array($r)) {
    $oke = true;
    
	
	// Apakah sesuai dengan status awalnya?
    $pos = strpos($w['StatusAwalID'], ".".$pmb['StatusAwalID'].".");
    $oke = $oke && !($pos === false);

	// Apakah sesuai dengan status mahasiswanya?
    $pos = strpos($w['StatusMhswID'], ".A.");
    $oke = $oke && !($pos === false);
	
    // Apakah grade-nya?
    if ($oke) {
      if ($w['GunakanGradeNilai'] == 'Y') {
        $pos = strpos($w['GradeNilai'], ".".$pmb['GradeNilai'].".");
        $oke = $oke && !($pos === false);
      }
    }
	if ($oke) {
      if ($w['GunakanGradeIPK'] == 'Y') $oke=false; 
    }
    
	// Apakah dimulai pada sesi 1?
    if ($oke) {
      if ($w['MulaiSesi'] <= 1) $oke = true;
	  else $oke = false;
    }
	
    // Simpan data
    if ($oke) {
      // Cek, sudah ada atau belum? Kalau sudah, ambil ID-nya
      $ada = AmbilOneField('bipotmhsw',
        "KodeID='".KodeID."' and PMBID = '$pmb[PMBID]'
        and TahunID='$pmb[PMBPeriodID]' and BIPOT2ID",
        $w['BIPOT2ID'], "BIPOTMhswID"); // +0
      // Cek apakah memakai script atau tidak?
      if ($w['GunakanScript'] == 'Y') BipotGunakanScript($pmb, '', $w, $ada, 0);
      // Jika tidak perlu pakai script
      else {
        // Jika tidak ada duplikasi, maka akan di-insert. Tapi jika sudah ada, maka dicuekin aja.
        if ($ada == 0) { 
          // Simpan
		  $Nama = AmbilOneField('bipotnama', 'BIPOTNamaID', $w['BIPOTNamaID'], 'Nama');
          // Cek Jumlah jika memiliki beasiswa
		  /*if(AmbilOneField('bipotnama', 'BIPOTNamaID', $w['BIPOTNamaID'], 'DipotongBeasiswa') == 'Y')
		  { $Jumlah = (1 - ($pmb['Diskon']/100))*$w['Jumlah'];
		  }
		  else
		  { $Jumlah = $w['Jumlah'];
		  }*/
		  $s1 = "insert into bipotmhsw
            (KodeID, COAID, PMBMhswID, PMBID, TahunID,
            BIPOT2ID, BIPOTNamaID, Nama, TrxID,
            Jumlah, Besar, Dibayar,
            Catatan, NA,
            LoginBuat, TanggalBuat)
            values
            ('".KodeID."', '$w[COAID]', 0, '$pmb[PMBID]', '$pmb[PMBPeriodID]',
            '$w[BIPOT2ID]', '$w[BIPOTNamaID]', '$Nama', '$w[TrxID]',
            1, '$w[Jumlah]', 0,
            'Auto', 'N',
            '$_SESSION[_Login]', now())";
          $r1 = mysqli_query($koneksi, $s1);
        }// end $ada=0
      } // end if $ada
    }   // end if $oke
  }     // end while
  HitungUlangBIPOTPMB($PMBID);
  SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=MhswBaruEdt&PMBID=$pmb[PMBID]", 100);
}

function TampilkanDataBayar($gels, $gel, $pmb) {
  global $koneksi;
  echo "<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>";
  echo "<tr>
    <th class=ttl colspan=8>Daftar Pembayaran</th>
    </tr>";
  echo "<tr style='background:purple;color:white'>
    <th class=ttl width=15>#</th>
    <th class=ttl width=120>Tanggal</th>
    <th class=ttl width=150>No. Bukti</th>
    <th class=ttl width=180 style='text-align:right'>Besar Pembayaran</th>
    <th class=ttl>Catatan</th>
    <th class=ttl width=10>&times;</th>
    </tr>";

  $s = "select bm.BayarMhswID, bm.Keterangan, bm.Jumlah,
      date_format(bm.Tanggal, '%d-%m-%Y') as TGL,
      format(bm.Jumlah, 0) as JML
    from bayarmhsw bm
    where bm.KodeID = '".KodeID."'
      and bm.PMBMhswID = 0
      and bm.PMBID = '$pmb[PMBID]'
    order by bm.Tanggal";
  $r = mysqli_query($koneksi, $s);
  $n = 0;
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    $del = ($_SESSION['_LevelID'] == 1)? "<a href='#' onClick=\"javascript:KonfirmasiHapusBayar('$w[BayarMhswID]', '$pmb[PMBID]')\"><img src='img/del.png' /></a>" : '&nbsp;';
    echo "<tr>
      <td class=inp>$n</td>
      <td class=ul1>$w[TGL]</td>
      <td class=ul1>$w[BayarMhswID]</td>
      <td class=ul1 align=right>$w[JML]</td>
      <td class=ul1>$w[Keterangan]&nbsp;</td>
      <td class=ul1 align=center width=10>&nbsp;</td>
      </tr>";
  }
  echo "</table>
  </div>
</div>
</div>";
}
function KonfirmasiProsesNIMScript() {
  echo <<<SCR
  <script>
  function KonfirmasiProsesNIM_xx(pmbid) {
    if (confirm("Cama telah melunasi biaya yg harus dibayarkan di awal tahun. Anda yakin akan memproses NIM utk Cama ini sekarang?")) {
      window.location = "?ndelox=$_SESSION[ndelox]&lungo=ProsesNIM&BypassMenu=1&PMBID="+pmbid;
    }
  }
  function KonfirmasiProsesNIM(pmbid) {
    lnk = "$_SESSION[ndelox].prosesnim.php?pmbid="+pmbid;
    win2 = window.open(lnk, "", "width=600, height=500, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
SCR;
}
?>
