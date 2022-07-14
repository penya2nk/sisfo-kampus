<?php
function TampilkanCariTahunMhsw($ndelox='') {
  global $arrID;
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table class=box cellspacing=1 cellpadding=4 align=center >
  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='dep/ceksesikhs'>
  <input type=hidden name='lungo' value='TampilkanData'>
  <tr><td class=ul colspan=2><font size=+1>$arrID[Nama]</font></td></tr>
 
  <tr>
  <td class=ul><input style='height:30px' placeholder='NIM' type=text name='MhswID' value='$_SESSION[MhswID]' size=15 maxlength=20>
  <td class=ul colspan=2><input class='btn btn-success btn-sm' type=submit name='Tampilkan' value='Lihat Data'></td></tr>
  </form></table>
  </div>
</div>
</div></p>";
}

function TampilkanData($mhsw) {
  global $koneksi;
  $s = "SELECT khs.*,mhsw.Nama from mhsw,khs 
	WHERE khs.MhswID=mhsw.MhswID 
	AND khs.MhswID='$_SESSION[MhswID]'
  ORDER BY khs.TahunID ASC";
  $r = mysqli_query($koneksi, $s); $n = 0;
  
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:70%' align='center'> 
  <tr style=text-align:center>
  <td class=ul colspan=7><b style='color:purple;font-size:20px'>NAMA: $mhsw[Nama] ( $mhsw[PRG] - $mhsw[PRD] )</b></td>
  </tr> 
  <tr>
  <td class=ul colspan=7><b style='color:green;font-size:20px'>SESI KHS </b></td>
  </tr> 

  <tr style='background:purple;color:white'><th class=ttl height=40 style=text-align:center>No</th>
  <th class=ttl>TahunID</th>
    <th class=ttl style=text-align:left>ProgramID</th>
    <th class=ttl style=text-align:center>Sesi</th>
    <th class=ttl style=text-align:center>Max SKS</th>
    <th class=ttl style=text-align:center>IPS</th>
    <th class=ttl style=text-align:center>Jumlah MK</th>
    <th class=ttl style=text-align:center>Aksi</th>
    </tr>";
  $n = 0;	
  while ($w = mysqli_fetch_array($r)) {
    if ($w[Keterangan]<>'Lunas'){
      $c="style=color:red";
    }else{
      $c="style=color:green";
    }	
	$tot += $w[total_bayar];
    $n++;
    echo "<tr><td class=inp width=10 style=text-align:center>$n</td>
    <td class=ul width=40>$w[TahunID]</td>
    <td class=ul width=10 style=text-align:left>$w[ProgramID]</td>
    <td width=40 align=center>$w[Sesi]</td>
    <td class=ul align=center width=40 $c>$w[MaxSKS]</td>
    <td class=ul align=center width=40>$w[IPS]</td>
    <td class=ul align=center width=40>$w[JumlahMK]</td>
    <td class=ul align=center width=40>
    <a href='?ndelox=dep/ceksesikhs&lungo=SesiKHSEdit&LevelID=$_SESSION[LevelID]&md=0&KHSID=$w[KHSID]'><i class='fas fa-edit'></i></a>
    <a href='?ndelox=dep/ceksesikhs&lungo=dltDataCek&LevelID=$_SESSION[LevelID]&KHSID=$w[KHSID]'><i class='fas fa-trash-alt'></i>
    </td>
    </tr>";
  }
  echo "</table>
  </div>
</div>
</div>
  <br>

  </p>";
}


function SesiKHSEdit() {
  $mhswxx =mysqli_fetch_array(mysqli_query($koneksi, "select MhswID,Nama,ProdiID,ProgramID from mhsw where MhswID='$_SESSION[MhswID]'"));
  $prog =mysqli_fetch_array(mysqli_query($koneksi, "select ProgramID,Nama from program where ProgramID='$mhswxx[ProgramID]'"));
  $prod =mysqli_fetch_array(mysqli_query($koneksi, "select ProdiID,Nama from prodi where ProdiID='$mhswxx[ProdiID]'"));
  $tahunak =mysqli_fetch_array(mysqli_query($koneksi, "select TahunID,Nama from tahun where NA='N'"));
  
  global $datapelatihan;
  $md = $_REQUEST['md'] +0;
  if ($md == 0) {
    $w = AmbilFieldx('khs', 'KHSID', $_REQUEST['KHSID'], '*');
    $jdl = "Edit Sesi KHS";
    $strid = "<input type=hidden name='KHSID' value='$w[KHSID]'><b>$w[KHSID]</b>";
  }
  else {
    $w = array();
    $w['KHSID'] = '';
    $w['TahunID'] = '';
    $w['Sesi'] = '';
    $w['MaxSKS'] = '';
    $w['MhswID'] = $_SESSION['MhswID'];

    $jdl = "Tambah Data";
    $strid = "Auto";
  }

  $c1 = 'class=inp1'; $c2 = 'class=ul';
  $snm = session_name(); $sid = session_id();
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:50%' align='center'>
  <form action='?' method=POST onSubmit=\"return CheckForm(this)\">
  <input type=hidden name='ndelox' value='dep/ceksesikhs'>
  <input type=hidden name='lungo' value='CekSesiKHSSave'>
  <input type=hidden name='md' value='$md'>
  <tr style='background:purple;color:white'><th class=ttl colspan=2>$jdl</th></tr>
  <tr><td $c1 width='280px'>Mahasiswa</td><td $c2><b>$mhswxx[MhswID] - $mhswxx[Nama]</b></td></tr>
  <tr><td $c1 >KHSID</td><td $c2>$strid</td></tr>
  <input type='hidden' name='MhswID' value='$_SESSION[MhswID]'>

  <tr><td $c1>Tahun Akademik</td><td $c2><input type=text name='TahunID' value='$w[TahunID]' size=50 maxlength=50 readonly></td></tr>
  <tr><td $c1>ProgramID</td><td><input type='text'  name='ProgramID' value='$w[ProgramID]' size=50></td></tr>                   				
	<tr><td $c1>Sesi</td><td><input type='text'  name='Sesi' value='$w[Sesi]' id='angka3' size=50></td></tr>
  <tr><td $c1>Max SKS</td><td><input type='text'  name='MaxSKS' value='$w[MaxSKS]' value='-' size=50></td></tr>
  <tr><td $c1>IPS</td><td><input type='text'  name='IPS' value='$w[IPS]' size=50 readonly></td></tr>
  <tr><td colspan=2><input class='btn btn-success btn-sm' type=submit name='Simpan' value='Simpan'>
    <input class='btn btn-danger btn-sm' type=reset name='Reset' value='Reset'>
    <input class='btn btn-warning btn-sm' type=button name='Batal' value='Batal' onClick=\"location='?ndelox=dep/ceksesikhs&lungo=TampilkanData&$snm=$sid'\"></td></tr>
  </form></table>
  </div>
</div>
</div></p>";
}

function CekSesiKHSSave() {
 global $koneksi;
  $md = $_REQUEST['md']+0;
  $KHSID     = $_REQUEST['KHSID'];
  $MhswID    = sqling($_REQUEST['MhswID']);
  $ProgramID = sqling($_REQUEST['ProgramID']);
  $Sesi      = sqling($_REQUEST['Sesi']);
  $MaxSKS    = sqling($_REQUEST['MaxSKS']);
  $IPS       = sqling($_REQUEST['IPS']);

  if ($md == 0) {
    $s = "update khs 
          set ProgramID ='$ProgramID', 
          Sesi          ='$Sesi',
          MaxSKS        ='$MaxSKS',
          TanggalEdit   ='".date('Y-m-d')."',
          LoginEdit     ='$_SESSION[_Login]'
          WHERE KHSID   ='$KHSID'";
    $r = mysqli_query($koneksi, $s);
  }
  else {
    $ada = AmbilFieldx('xx', 'KHSID', $_REQUEST['KHSID'], '*');
    if (empty($ada)) {
      $s = "insert into xx (
            id_jenis,							
            TahunID,
            MhswID,
            ProdiID,
            total_bayar,
            TanggalBayar,
            TanggalBuat,
            keterangan,
            NoBukti,
            Login)
   values('$id_jenis', 
          '$TahunID', 
          '$MhswID', 
          '$ProdiID', 
          '$total_bayar', 
          '$TanggalBayar',
          '".date('Y-m-d')."', 
          '$Keterangan', 
          '$NoBukti', 
          '$_SESSION[_Login]')";
      $r = mysqli_query($koneksi, $s);
    }
    if ($id_jenis=='SPP'){
      mysqli_query($koneksi, "update khsxx set StatusMhswID='A' where MhswID='$MhswID' AND TahunID='$TahunID'");					
      }
    else echo PesanError('Terjadi Kesalahan',
      "Kode telah digunakan: <b>$ada[KHSID] - $ada[Judul]</b> : $ada[KHSID].<br>
      Gunakan kode lain.");
  }
  TampilkanData();
}

function dltDataCek(){
	$KHSID = $_REQUEST['KHSID'];
	//$Akses = AmbilOneField('level', 'LevelID', $_REQUEST['LevelID'], 'Nama');
	echo Info("Delete Data", "Yakin Anda ingin menghapus Data : <br />
									ID : <b>$KHSID</b><br />								
									<input type=button name='hapus' value='Delete Data' onClick=\"location='?ndelox=dep/ceksesikhs&lungo=dltData&KHSID=$KHSID'\">");
}

function dltData(){
  global $koneksi;
	$s = "delete from khs where KHSID = '$_REQUEST[KHSID]'";
	$r = mysqli_query($koneksi, $s);
	TampilkanData();
}

function dltDataCek2(){
	$KHSID = $_REQUEST['KHSID'];
	echo Info("Delete Data", "Yakin Anda ingin menghapus Data : <br />
									ID : <b>$KHSID</b><br />								
									<input type=button name='hapus' value='Delete Data' onClick=\"location='?ndelox=dep/ceksesikhs&lungo=dltData&KHSID=$KHSID'\">");
}
function dltData2(){
  global $koneksi;
	$s = "delete from khs where KHSID = '$_REQUEST[KHSID]'";
	$r = mysqli_query($koneksi, $s);
	TampilkanData();
}

$tahun = GainVariabelx('tahun');
$MhswID = GainVariabelx('MhswID');
$lungo = (empty($_REQUEST['lungo']))? '' : $_REQUEST['lungo'];

TitleApps("KOREKSI SESI KHS");
TampilkanCariTahunMhsw('ceksesikhs');
if (!empty($MhswID)) {
  $mhsw = AmbilFieldx("mhsw m
    left outer join program prg on m.ProgramID=prg.ProgramID
    left outer join prodi prd on m.ProdiID=prd.ProdiID", 
    "m.MhswID", $_SESSION['MhswID'], 
    "m.*, prg.Nama as PRG, prd.Nama as PRD");
  if (empty($mhsw))
    echo PesanError("Mhsw Tidak Ditemukan",
      "Mahasiswa dengan NPM: <font size=+1>$_SESSION[MhswID]</font> tidak ditemukan");
  else if (!empty($lungo)) {
    //echo"Nama: $mhsw[Nama]";
    //include_once "mhsw.hdr.php";
    //TampilkanHeaderBesar($mhsw, "transkrip.mhs", "TampilkanData", 0);
    $lungo($mhsw);
  }
}
?>
</div>
          </div>
      </div>
    </div>
</section>  