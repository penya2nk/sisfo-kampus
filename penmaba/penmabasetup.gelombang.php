<?php
// $lungo = (empty($_REQUEST['lungo']))? 'DftrPejabat' : $_REQUEST['lungo'];
$sub = (empty($_REQUEST['sub']))? 'ListGelombang' : $_REQUEST['sub'];
$sub();
// $lungo();

function GelEdtScript() {
  echo <<<SCR
  <script>
  function GelEdt(MD, ID, BCK) {
    lnk = "$_SESSION[ndelox].gelombang.edit.php?md="+MD+"&id="+ID+"&bck="+BCK;
    win2 = window.open(lnk, "", "width=400, height=400, left=500, top=150, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
SCR;
}

function ListGelombang() {
	global $koneksi;
  GelEdtScript();
  $_gelombangpage = GainVariabelx('_gelombangpage', 0);
  $s = "SELECT p.PMBPeriodID, p.Nama,
    p.NA,
    date_format(p.TglMulai, '%d/%m/%y') as _TglMulai,
    date_format(p.TglSelesai, '%d/%m/%y') as _TglSelesai,
    date_format(p.UjianMulai, '%d/%m/%y') as _UjianMulai,
    date_format(p.UjianSelesai, '%d/%m/%y') as _UjianSelesai,
    date_format(p.BayarMulai, '%d/%m/%y') as _BayarMulai,
    date_format(p.BayarSelesai, '%d/%m/%y') as _BayarSelesai,
    date_format(p.WawancaraMulai, '%d/%m/%y') as _WawancaraMulai,
    date_format(p.WawancaraSelesai, '%d/%m/%y') as _WawancaraSelesai
    FROM pmbperiod p 
    WHERE p.KodeID = '".KodeID."' 
    order by p.PMBPeriodID desc";
  $r = mysqli_query($koneksi, $s);
  $n = 0;

  echo "<p>
  <div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>";
  echo"<tr>
  <td class=ul1 colspan=8>
    <input class='btn btn-success btn-sm' type=button name='Tambah' value='Tambah Gelombang'
      onClick=\"javascript:GelEdt(1, '', '$_SESSION[ndelox]')\" />
    <input class='btn btn-primary btn-sm' type=button name='Refresh' value='Refresh'
      onClick=\"window.location='?ndelox=$_SESSION[ndelox]'\" />
    Penting: Hanya ada 1 gelombang yang aktif.
  </td>
  </tr>";

  echo "<tr style='background:purple;color:white'>
  <th class=ttl colspan=2>#</th>
  <th class=ttl>Gelombang</th>
  <th class=ttl>Nama Gelombang</th>
  <th class=ttl>Periode</th>
  <th class=ttl>Ujian</th>
  <th class=ttl>Bayar</th>
  <th class=ttl>NA</th>
    </tr>";
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    if ($w['NA']=='N'){
      $stat="<i style='color:green' class='fa fa-eye'></i>";
      $c="style=color:green;font-weight:bold";
    }else{
      $stat="<i style='color:red' class='fa fa-eye-slash'></i>";
      $c="style=color:black;";
    }
    
    $fn = "ttd/$w[KodeJabatan].ttd.gif";
    $img_ttd = (file_exists($fn))? "<img src='$fn' width=80 height=80 />" : '&times;';
    echo "<tr $c>
      <td class=inp width=10>$w[Urutan]</td>
      <td class=cna$w[NA] width=10>
        <a href='#' onClick=\"javascript:GelEdt(0, $w[PMBPeriodID], '$_SESSION[ndelox]')\" /><i class='fa fa-edit'></i></a>       
        </td>
      <td class=cna$w[NA] width=120>$w[PMBPeriodID]&nbsp;</td>
      <td class=cna$w[NA] width=300>$w[Nama]&nbsp;</td>
      <td class=cna$w[NA]>$w[_TglMulai] - $w[_TglSelesai]</td>
      <td class=cna$w[NA] width=220>$w[_UjianMulai] - $w[_UjianSelesai]</td>
      <td class=cna$w[NA] width=220>$w[_BayarMulai] - $w[_BayarSelesai]</td>
      <td class=cna$w[NA] width=120 align=center'>$stat</a></td>
      </tr>";
  }
  echo "</table>
  </div>
</div>
</div>
  </p>";
}

?>
