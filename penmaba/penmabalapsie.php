<!--Fungsi Load Javascript fusioncharts-->
<script type="text/javascript" src="JS/jquery-1.4.js"></script>
<script type="text/javascript" src="JS/jquery.fusioncharts.js"></script>
<!--GRAFIK JUMLAH SISWA PER KELAS-->

<?php

function DftrLapPMB() {
  global $_arrpmblap;
  $n=0;
  echo "<table class=box cellspacing=1 cellpadding=4>
    <tr><th class=ttl>#</th><th class=ttl>Jenis Laporan</th></tr>";
  for ($i=0; $i<sizeof($_arrpmblap); $i++) {
    $n++;
    $lap = explode('->', $_arrpmblap[$i]);
    echo "<tr><td class=inp1>$n</td>
    <td class=ul><a href='?ndelox=pmblapsie&lungo=$lap[1]'>$lap[0]</a>
    </td></tr>";
  }
  echo "</table>";
}
// Laporan Penjualan Formulir PMB
function JualForm() {
  global $arrID, $_HeaderPrn, $_EjectPrn, $_pmbaktif, $_lf, $arrHari;
  $s = "select count(pfj.PMBFormJualID) as TOT, pf.Nama, pf.JumlahPilihan
    from pmbformjual pfj
    left outer join pmbformulir pf on pfj.PMBFormulirID=pf.PMBFormulirID
    where pfj.PMBPeriodID='$_pmbaktif' and pfj.KodeID='$arrID[Kode]'
    group by pfj.PMBFormulirID";
  $r = mysqli_query($koneksi, $s);
  
  $fn = "tmp/$_SESSION[_Login].dwoprn";
  $f = fopen($fn, 'w');
  $hr = date('w');
  $tgl = $arrHari[$hr].', '.date("d/m/Y");
  
  $hdr = $_HeaderPrn;
  $hdr.= $_lf.$_lf;
  $hdr.= str_pad($arrID['Nama'], 79, ' ', STR_PAD_BOTH).$_lf;
  $hdr.= str_pad("Rekapitulasi Penjualan Formulir PMB - $_pmbaktif", 79, ' ', STR_PAD_BOTH).$_lf;
  $hdr.= str_pad("Sampai dengan tanggal: ".$tgl, 79, ' ', STR_PAD_BOTH).$_lf;
  $hdr.= str_pad('', 79, '=').$_lf;
  //$hdr.= str_pad('', 100, '=').$_lf;
  $hdr.= '  #  ' . str_pad('Jenis Formulir', 30). 'Jml.Pilihan | Hari ini | Total | Tdk Kembali '. $_lf;
  $hdr.= str_pad('', 79, '-').$_lf;
  fwrite($f, $hdr);
  $n = 0;
  $tot = 0; $toti = 0; $toth = 0;
  
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    $hrini = AmbilOneField('pmbformjual', "OK='Y' and PMBFormulirID='$w[PMBFormulirID]' and PMBPeriodID='$_pmbaktif' and Tanggal", date("Y-m-d"), "count(*)")+0;
    $hilng = AmbilOneField('pmbformjual', "OK='N' and PMBFormulirID='$w[PMBFormulirID]' and PMBPeriodID='$_pmbaktif' and Tanggal", date("Y-m-d"), "count(*)")+0;
    fwrite($f, str_pad($n, 3, ' ', STR_PAD_LEFT).'. ');
    fwrite($f, str_pad($w['Nama'], 36));
    fwrite($f, str_pad($w['JumlahPilihan'], 5, ' ', STR_PAD_LEFT).' | ');
    fwrite($f, str_pad($hrini, 8, ' ', STR_PAD_LEFT).' | ');
    fwrite($f, str_pad($w['TOT'], 5, ' ', STR_PAD_LEFT). ' | ');
    fwrite($f, str_pad($hilng, 5, ' ', STR_PAD_LEFT));
    fwrite($f, $_lf);
    $tot += $w['TOT'];
    $toti += $hrini;
    $toth += $hilng;
  }
  fwrite($f, str_pad('', 79, '-').$_lf);
  //fwrite($f, str_pad('', 100, '-').$_lf);
  fwrite($f, str_pad('Total : ', 50, ' ', STR_PAD_LEFT));
  fwrite($f, str_pad($toti, 7, ' ', STR_PAD_LEFT).' | ');
  fwrite($f, str_pad($tot, 5, ' ', STR_PAD_LEFT).' | ');
  fwrite($f, str_pad($toth, 5, ' ', STR_PAD_LEFT));
  
  fclose($f);
  TampilkanFileDWOPRN($fn, 'pmblapsie');
  ?>

  
  
  
<div class="tengah_judul">
   	Grafik Penjualan Formulir<hr align="left" size="1" color="#cccccc">
</div>

<table id="TableFormulir" border="0" align="center" cellpadding="10">
    <tr bgcolor="#FF9900" style='display:none;'> <th>Prodi</th> <th>Jumlah CAMA</th></tr>
    <?php
    $query_formulir = $koneksi, $koneksi, "SELECT * FROM pmbformulir";
    $result_formulir = mysqli_query($query_formulir);
    $count_formulir = mysqli_num_rows($result_formulir);

    while ($data = mysqli_fetch_array($result_formulir)) {
        $nama_prodi = $data['Nama'];
		$formulir_id = $data['PMBFormulirID'];
		//QUERY MENGHITUNG JUMLAH CAMA SESUAI DENGAN KODE FORMULIR ID
		$query_cama = $koneksi, $koneksi, "SELECT COUNT(*) AS jumlah_cama FROM pmbformjual  WHERE PMBFormulirID='$formulir_id' and PMBPeriodID='$_pmbaktif'";
        $result_cama = mysqli_query($query_cama);
        $data_cama = mysqli_fetch_array($result_cama);

        echo "<tr bgcolor='#D5F35B' style='display:none;'>
              <td>Prodi $data[Nama]</td>
              <td align='center'>$data_cama[jumlah_cama]</td>
              </tr>";
    }
    ?>

</table>
<!--LOAD HTML KE JQUERY FUSION CHART BERDASARKAN ID TABLE-->
<script type="text/javascript">
    $('#TableFormulir').convertToFusionCharts({
        swfPath: "Charts/",
        type: "MSColumn3D",
        data: "#TableFormulir",
        dataFormat: "HTMLTable"
		
    });
</script>


<?php
}

// Laporan Peserta Test
function PesertaTest() {
  global $arrID, $_HeaderPrn, $_EjectPrn, $_pmbaktif, $_lf, $arrHari;
  $s = "select prd.ProdiID, LEFT(prd.Nama, 25) as PRD, prd.FakultasID, f.Nama as FAK
    from prodi prd
    left outer join fakultas f on prd.FakultasID=f.FakultasID
    where prd.KodeID='$arrID[Kode]'
    order by prd.FakultasID, prd.ProdiID";
  $r = mysqli_query($koneksi, $s);
  
  $fn = "tmp/$_SESSION[_Login].dwoprn";
  $f = fopen($fn, 'w');
  $hr = date('w');
  $tgl = $arrHari[$hr].', '.date("d/m/Y");
  
  // Buat header dulu
  $hdr = $_HeaderPrn.$_lf.$_lf;
  $hdr.= str_pad($arrID['Nama'], 79, ' ', STR_PAD_BOTH).$_lf;
  $hdr.= str_pad("Rekapitulasi Peserta Test Per Program Studi - $_pmbaktif", 79, ' ', STR_PAD_BOTH).$_lf;
  $hdr.= str_pad("Sampai dengan tanggal: ".$tgl, 79, ' ', STR_PAD_BOTH).$_lf;
  $hdr.= str_pad('', 100, '=').$_lf;

  // Ambil Status Awal
  $_ssa = "select StatusAwalID, LEFT(Nama, 10) as NM
    from statusawal
    order by StatusAwalID";
  $_rsa = mysqli_query($_ssa);
  $arrSA = array(); $arrSANM = array();
  while ($_wsa = mysqli_fetch_array($_rsa)) {
    $arrSA[] = $_wsa['StatusAwalID'];
    $arrSANM[] = $_wsa['NM'];
  }
  
  $hdr.= $mrg1. str_pad("Program Studi", 35, ' ');
  $hdr.= '  Total';
  for ($i=0; $i<mysqli_num_rows($_rsa); $i++) {
    $hdr .= str_pad($arrSANM[$i], 10, ' ', STR_PAD_LEFT);
  }
  $hdr.= $_lf;
  //$hdr.= str_pad('', 79, '-').$_lf;
  $hdr.= str_pad('', 100, '-').$_lf;
  fwrite($f, $hdr);
  
  $mrg1 = ''; $mrg2 = '   ';
  $fak = '';
  $tot = 0;
  while ($w = mysqli_fetch_array($r)) {
    if ($fak != $w['FakultasID']) {
      $fak = $w['FakultasID'];
      fwrite($f, $mrg1. chr(27).'G'. $w['FAK']. chr(27).'H'.$_lf);
    }
    $_tot = AmbilOneField('pmb', "KodeID='$arrID[Kode]' and PMBPeriodID='$_pmbaktif' and ProdiID", $w['ProdiID'], "count(*)");
    $tot += $_tot;
    
    
    fwrite($f, $mrg2. str_pad($w['PRD'], 30, ' '));
    fwrite($f, str_pad($_tot, 7, ' ', STR_PAD_LEFT));
    // Ambil detail
    for ($i=0; $i<sizeof($arrSA); $i++) {
      $_det = AmbilOneField('pmb', "KodeID='$arrID[Kode]' and PMBPeriodID='$_pmbaktif' and ProdiID='$w[ProdiID]' and StatusAwalID",
        $arrSA[$i], "count(*)");
      fwrite($f, str_pad($_det, 10, ' ', STR_PAD_LEFT));
    }
    fwrite($f, $_lf);
  }
  // fwrite($f, str_pad('', 79, '-').$_lf);
  fwrite($f, str_pad('', 100, '-').$_lf);
  fwrite($f, str_pad('Total Peserta Test      : ', 34, ' ', STR_PAD_LEFT));
  fwrite($f, str_pad($tot, 6, ' ', STR_PAD_LEFT));
  
  fclose($f);
  TampilkanFileDWOPRN($fn, 'pmblapsie');
  
    ?>

  
  
  
<div class="tengah_judul">
   	Grafik Peserta test<hr align="left" size="1" color="#cccccc">
</div>

<table id="TablePesertaTes" border="0" align="center" cellpadding="10">
    <tr bgcolor="#FF9900" style='display:none;'> <th>Prodi</th> <th>Jumlah CAMA</th></tr>
    <?php
    $query_prodi = $koneksi, $koneksi, "SELECT * FROM prodi";
    $result_prodi = mysqli_query($query_prodi);
    $count_prodi = mysqli_num_rows($result_prodi);

    while ($data = mysqli_fetch_array($result_prodi)) {
        $kode_prodi = $data['Nama'];
		$prodi_id = $data['ProdiID'];
		//QUERY MENGHITUNG JUMLAH CAMA SESUAI DENGAN KODE PRODI
		$query_peserta = $koneksi, $koneksi, "SELECT COUNT(*) AS jumlah_peserta FROM pmb WHERE ProdiID='$prodi_id' and PMBPeriodID='$_pmbaktif'";
        $result_peserta = mysqli_query($query_peserta);
        $data_peserta = mysqli_fetch_array($result_peserta);

        echo "<tr bgcolor='#D5F35B' style='display:none;'>
              <td>Prodi $data[Nama]</td>
              <td align='center'>$data_peserta[jumlah_peserta]</td>
              </tr>";
    }
    ?>

</table>
<!--LOAD HTML KE JQUERY FUSION CHART BERDASARKAN ID TABLE-->
<script type="text/javascript">
    $('#TablePesertaTes').convertToFusionCharts({
        swfPath: "Charts/",
        type: "MSColumn3D",
        data: "#TablePesertaTes",
        dataFormat: "HTMLTable"
		
    });
</script>


<?php
}
function LulusUSM() {
  global $arrID, $_HeaderPrn, $_EjectPrn, $_pmbaktif, $_lf, $arrHari;
  $s = "select p.PMBID, p.Nama
    from pmb p
      left outer join statusawal sa on p.StatusAwalID=sa.StatusAwalID
    where p.LulusUjian='Y' and p.PMBPeriodID='$_pmbaktif' and sa.TanpaTest='N'
    order by p.PMBID";
  $r = mysqli_query($koneksi, $s);

  // Tulis ke file
  $_maxbaris = 40;
  $nmf = "tmp/$_SESSION[_Login].dwoprn";
  $f = fopen($nmf, "w");
  fwrite($f, $_HeaderPrn);
  // $div = str_pad("=", 79, '=', STR_PAD_BOTH).$_lf;
  $div = str_pad("=", 100, '=', STR_PAD_BOTH).$_lf;
  $hdr = $_lf.$_lf;
  $hdr .= str_pad($arrID['Nama'], 79, ' ', STR_PAD_BOTH).$_lf;
  $hdr .= str_pad("Daftar Peserta Ujian Saringan Masuk yang Lulus", 79, ' ', STR_PAD_BOTH).$_lf;
  $hdr .= str_pad("Penerimaan Gelombang: $_pmbaktif", 79, ' ', STR_PAD_BOTH).$_lf.$div;
  // Tuliskan header
  fwrite($f, $hdr);
  // Tuliskan isinya
  $nmr = 0; $brs = 0;
  while ($w = mysqli_fetch_array($r)) {
    if ($brs >= $_maxbaris) {
      fwrite($f, chr(12));
      fwrite($f, $hdr);
      $brs = 0;
    }
    $n++; $brs++;
    fwrite($f, str_pad($n, 4, ' ', STR_PAD_LEFT).'. ');
    fwrite($f, str_pad($w['PMBID'], 15, ' '));
    fwrite($f, str_pad($w['Nama'], 50, ' '));
    fwrite($f, $_lf);
  }
  fwrite($f, $div);
  fwrite($f, chr(12));
  fclose($f);
  TampilkanFileDWOPRN($nmf, 'pmblapsie');
  

}


// *** Parameters ***
$prodi = GainVariabelx('prodi');
$_pmbaktif = GainVariabelx('_pmbaktif');
if (empty($_pmbaktif)) {
  $_pmbaktif = AmbilOneField('pmbperiod', 'NA', 'N', 'PMBPeriodID');
  $_SESSION['_pmbaktif'] = $_pmbaktif;
}
$pmbfid = GainVariabelx('pmbfid');
$_arrpmblap = array(
  "Penjualan Formulir->JualForm",
  "Peserta Test->PesertaTest",
  "Daftar Lulus USM->LulusUSM"
  );
$lungo = (empty($_REQUEST['lungo']))? 'DftrLapPMB' : $_REQUEST['lungo'];

// *** Main ***
TitleApps("Laporan PMB Gelombang - $_pmbaktif");
TampilkanPeriodePMBLaporan('pmblapsie');
$lungo();


?>

