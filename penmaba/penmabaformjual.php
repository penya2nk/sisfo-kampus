<?php
$gelombang = AmbilOneField('pmbperiod', "KodeID='".KodeID."' and NA", 'N', "PMBPeriodID");

TitleApps("PENJUALAN FORMULIR PMB - $gelombang");
if (empty($gelombang)) {
  echo PesanError("Error",
    "Tidak ada gelombang PMB yang aktif.<br />
    Hubungi Kepala PMB untuk mengaktifkan gelombang.");
}
else {
  $lungo = (empty($_REQUEST['lungo']))? 'ListPenjualan' : $_REQUEST['lungo'];
  $lungo($gelombang);
}

function ListPenjualan($g) {
  global $koneksi;
	$tot = 0;
  JualFormulirScript();
  $s = "select f.PMBFormulirID, f.Nama, f.JumlahPilihan, format(f.Harga, 0) as HRG,
    (select count(PMBFormJualID) 
      from pmbformjual 
      where KodeID='".KodeID."' 
        and PMBFormulirID=f.PMBFormulirID
        and PMBPeriodID='".$g."') as JML
    from pmbformulir f
    where f.KodeID = '".KodeID."' and f.NA = 'N'
    order by f.Nama";
  $r = mysqli_query($koneksi, $s); $n = 0;
  
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'><table id='example' class='table table-sm table-striped' style='width:70%' align='left'>";
  echo "<tr>
    <td class=ul1 colspan=7>
    <input class='btn btn-success btn-sm' type=button name='Refresh' value='Refresh'
      onClick=\"location='?ndelox=$_SESSION[ndelox]'\" />
    <!--
    <input type=button name='CetakUlangKwitansi' value='Cetak Ulang Kwitansi'
      onClick=\"\" />
    -->
    </td>
    </tr>";
  echo "<tr style='background:purple;color:white'>
    <th class=ttl>#</th>
    <th class=ttl>Formulir</th>
    <th class=ttl>Jml Pilihan</th>
    <th class=ttl style='text-align:right'>Harga</th>
    <th class=ttl style='text-align:right'>Jml Terjual</th>
    <th class=ttl style='text-align:center'>Jual</th>
	<th class=ttl>Print</th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    $tot += $w['JML'];
    echo "<tr>
      <td class=inp width=20>$n</td>
      <td class=ul>$w[Nama]</td>
      <td class=ul width=100 align=center>$w[JumlahPilihan]</td>
      <td class=ul width=100 align=right>$w[HRG]</td>
      <td class=ul width=100 align=right>&nbsp;$w[JML]</td>
      <td class=ul width=90 align=center>
        <a href='#' onClick=\"javascript:JualFormulir('$g', $w[PMBFormulirID])\"><i style='font-size:23px' class='fa fa-plus-square'></i></a>
        </td>
	  <td class=ul width=20 align=center><a href='#' onClick=\"CetakFormulir('$g', '$w[PMBFormulirID]')\"><i style='font-size:20px' class='fa fa-print'></i></a></td>
      </tr>";
  }
  $_tot = number_format($tot);
  echo "<tr>
    <td class=ul1 colspan=4 align=right>Total Terjual:</td>
    <td class=ul1 align=right><font size=+1>$_tot</font></td>
    </tr>";
  echo "</table></div>
  </div>
  </div></p>";
}

function JualFormulirScript() {
  //width=700,height=600,left=250,top=200,toolbar=0,status=0
  echo <<<SCR
  <script>
  function JualFormulir(gel, id) {
    lnk = "$_SESSION[ndelox].jual.php?id="+id+"&gel="+gel;
    win2 = window.open(lnk, "", "width=350, left=580, height=300, top=100, scrollbars, status, resizable");
    if (win2.opener == null) childWindow.opener = self;
  }
  function CetakFormulir(gel, id) {
	lnk = "$_SESSION[ndelox].cetak.php?id="+id+"&gel="+gel;
    win2 = window.open(lnk, "", "width=1000, height=600, scrollbars, status, resizable");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
SCR;
}
?>
