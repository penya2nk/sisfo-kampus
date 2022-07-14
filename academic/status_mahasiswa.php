<?php
$_statusmhswNama = GainVariabelx('_statusmhswID');
$_statusmhswNama = GainVariabelx('_statusmhswNama');
$_statusmhswProdi = GainVariabelx('_statusmhswProdi');
$_statusmhswPrg = GainVariabelx('_statusmhswPrg');
$_statusmhswNomer = GainVariabelx('_statusmhswNomer');
$_statusmhswPage = GainVariabelx('_statusmhswPage');
$_statusmhswUrut = GainVariabelx('_statusmhswUrut', 1);
$arrUrutMhsw = array('NIM~psm.MhswID asc, m.Nama', 'NIM (balik)~psm.MhswID desc, m.Nama', 'Nama~m.Nama');
RandomStringScript();

TitleApps("MANAJEMEN STATUS MAHASISWA");
  $lungo = (empty($_REQUEST['lungo']))? 'StatusMhsw' : $_REQUEST['lungo'];
  $lungo();

function AmbilUrutanMhswID() {
  global $arrUrutMhsw;
  $a = ''; $i = 0;
  foreach ($arrUrutMhsw as $u) {
    $_u = explode('~', $u);
    $sel = ($i == $_SESSION['_statusmhswUrut'])? 'selected' : '';
    $a .= "<option value='$i' $sel>". $_u[0] ."</option>";
    $i++;
  }
  return $a;
}

function ViewHeaderX() {
  $optprg = AmbilCombo2('program', "concat(ProgramID, ' - ', Nama)", 'ProgramID', $_SESSION['_statusmhswPrg'], "KodeID='".KodeID."'", 'ProgramID');
  $optprodi = AmbilCombo2('prodi', "concat(ProdiID, ' - ', Nama)", 'ProdiID', $_SESSION['_statusmhswProdi'], "KodeID='".KodeID."'", 'ProdiID');
  $optstatusmhsw = AmbilCombo2('statusmhsw', "concat(StatusMhswID, ' - ', Nama)", 'StatusMhswID', $_SESSION['_statusmhswID'], "", 'StatusMhswID');

  echo "<div class='card'>
  <div class='card-header'>
  <div align='center'>

  <form action='?' method=POST>
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='lungo' value='' />
  <input type=hidden name='_statusmhswPage' value='0' />
        <input type=text style='height:30px' placeholder='Nama Mahasiswa' name='_statusmhswNama' value='$_SESSION[_statusmhswNama]' size=14 maxlength=30 />
        <input type=text style='height:30px' placeholder='NIM' name='_statusmhswNomer' value='$_SESSION[_statusmhswNomer]' size=14 maxlength=30 />&nbsp;&nbsp;
       
        PRODI <select style='height:30px' name='_statusmhswProdi'>$optprodi</select>
        PROGRAM <select style='height:30px' name='_statusmhswPrg'>$optprg</select>
        STATUS <select style='height:30px' name='_statusmhswID'>$optstatusmhsw</select>


      <input class='btn btn-success btn-sm' type=submit name='Submit' value='Lihat Data' />
      <input class='btn btn-warning btn-sm' type=button name='Reset' value='Reset'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=&_statusmhswPage=0&_statusmhswNama=&_statusmhswNomer='\" />
    <input class='btn btn-primary btn-sm' type=button name='IsiFrm' value='Formulir Perubahan' 
		onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=EditStatusMahasiswa&md=1'\" />

  </form>
    </div>
</div>
</div>";
}

function StatusMhsw() {
  ViewHeaderX();
  
  global $arrUrutMhsw, $koneksi;
  $_maxbaris = 10;

  // Urutan
  

  
  $_urut = $arrUrutMhsw[$_SESSION['_statusmhswUrut']];
  $__urut = explode('~', $_urut);
  $urut = "order by ".$__urut[1];
  // Filter formulir
  $whr = array();
  if (!empty($_SESSION['_statusmhswNomer'])) $whr[] = "psm.MhswID like '%$_SESSION[_statusmhswNomer]%'";
  if (!empty($_SESSION['_statusmhswProdi']))   $whr[] = "m.ProdiID = '$_SESSION[_statusmhswProdi]' ";
  if (!empty($_SESSION['_statusmhswPrg']))   $whr[] = "m.ProgramID = '$_SESSION[_statusmhswPrg]' ";
  if (!empty($_SESSION['_statusmhswNama']))  $whr[] = "m.Nama like '%$_SESSION[_statusmhswNama]%'";
  if (!empty($_SESSION['_statusmhswID']))  $whr[] = "psm.StatusMhswID = '$_SESSION[_statusmhswID]'";
  
  $_whr = implode(' and ', $whr);
  $_whr = (empty($_whr))? '' : 'and '.$_whr;
  
  
  $s = "select DISTINCT(psm.ProsesStatusMhswID), psm.MhswID, psm.Tanggal, m.Nama, m.Kelamin, m.ProgramID, m.ProdiID, psm.StatusMhswLama, psm.StatusMhswID, psm.NA,
					_prg.Nama as _PRG, 
					_stm.Nama as NamaStatusLama, _stm2.Nama as NamaStatusBaru
					from prosesstatusmhsw psm
	left outer join mhsw m on psm.MhswID = m.MhswID
    left outer join prodi _prd on m.ProdiID = _prd.ProdiID
	left outer join program _prg on m.ProgramID = _prg.ProgramID
    left outer join statusmhsw _stm on psm.StatusMhswLama = _stm.StatusMhswID
	left outer join statusmhsw _stm2 on psm.StatusMhswID = _stm2.StatusMhswID
    where psm.KodeID = '".KodeID."' 
    $_whr";
	$r = mysqli_query($koneksi, $s); 

  echo"<div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
   <table id='example1' class='table table-sm table-bordered table-striped'>
    <thead>
    <tr style='background:purple;color:white'>
    <th class=ttl>#</th>
	 <th class=ttl>Aksi</th>
    <th class=ttl style='text-align:center'>Tanggal</th>
    <th class=ttl style='text-align:center'>NIM</th>
    <th class=ttl>Nama</th>
	 <th class=ttl style='text-align:center'>JKelamin</th>
    <th class=ttl style='text-align:center'>Prodi Program</th>
    <th class=ttl style='text-align:center'>Status Lama</th>
	  <th class=ttl style='text-align:center'>Status Baru</th>
	 <th class=ttl style='text-align:center'>Print</th>
    </tr>
	</thead>
	<tbody>";
	 $no=0;
	while ($w = mysqli_fetch_array($r)) {
		$no++;
	
  echo"<tr>
    <td class=inp width=10>$no</td>
     <td class=inp width=10><a href='#' onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=EditStatusMahasiswa&md=0&id=$w[ProsesStatusMhswID]'\" />
    <i class='fa fa-edit'></i></a></td>
    <td class=cna=NA= width=90 align=center>".date('d-m-Y', strtotime($w['Tanggal']))."</td>
    <td class=ul1 width=80 style='text-align:center'>$w[MhswID]</td>
    <td class=cna=NA= width=320>$w[Nama]</td>
    <td class=cna=NA= width=10 align=center>$w[Kelamin]</td>
    <td class=cna=NA= width=120 align=center>$w[ProdiID] - $w[_PRG]</td>
	<td class=cna=NA= width=100 align=center>$w[NamaStatusLama]</td>
  <td class=cna=NA= width=100 align=center>$w[NamaStatusBaru]</td>
	<td class=cna=NA= width=15 align=center>
		<a href='#' onClick=\"PrintSurat($w[ProsesStatusMhswID])\"><i class='fa fa-print'></i></a>
	</td>
    </tr>";
	}
	echo"</tbody>
	</table>";
	
  echo"</div>
</div>
</div>

    <script>
		function PrintSurat(id)
		{	lnk = '$_SESSION[ndelox].keterangan.php?_psmid='+id;
			win2 = window.open(lnk, '', 'width=600, height=500, scrollbars, status');
			if (win2.opener == null) childWindow.opener = self;
		}
	</script>";
}

function EditStatusMahasiswa()
{ 	
global $koneksi;
  $md = $_REQUEST['md'] +0;
  // Jika Edit
  if ($md == 0) {
    $w = AmbilFieldx('prosesstatusmhsw', "ProsesStatusMhswID", $_REQUEST['id'], '*');
    $jdl = "Edit Proses Status Mhsw";
	$opttgl = AmbilComboTgl($w['Tanggal'], 'Tanggal');
	$NIM = $w['MhswID'];
	$Nama = AmbilOneField('mhsw', 'MhswID', $NIM, 'Nama');
	$HiddenNama = $Nama;
	$optstatusmhsw = "<input type=hidden name='StatusMhswID' value='$w[StatusMhswID]'>".$w['StatusMhswID'].' - '.AmbilOneField('statusmhsw', 'StatusMhswID', $w['StatusMhswID'], 'Nama');
	$tahunakademik = "<input type=hidden name='TahunID' value='$w[TahunID]'>".$w['TahunID'];
  }
  // Jika tambah
  else {
    $w = array();
	$MhswID = (empty($_REQUEST['MhswID']))? ((empty($_SESSION['_statusmhswNomer']))? "" : $_SESSION['_statusmhswNomer']) : $_REQUEST['MhswID'] ;
	$w['TahunID'] = '';
	$cari = AmbilOneField('mhsw', 'MhswID', $MhswID, 'MhswID');
	$w['StatusMhswLama'] = (empty($cari))? "- Tidak ada" : AmbilOneField('mhsw', "MhswID='$MhswID' and KodeID", KodeID, 'StatusMhswID'); 
    $opttgl = AmbilComboTgl(date('Y-m-d'), 'Tanggal');
	$jdl = "Formulir Perubahan Status Mahasiswa";
	$NIM = "<input type=text name='MhswID' value='$MhswID'>
	<input class='btn btn-success btn-sm' type=button name=Cari' value='Lihat NIM' onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo=EditStatusMahasiswa&md=1&MhswID='+frmstatusmhsw.MhswID.value\">";
	$Nama = (empty($cari))? "- Tidak ditemukan -" : AmbilOneField('mhsw', 'MhswID', $MhswID, 'Nama');
	$HiddenNama = (empty($cari))? "" : AmbilOneField('mhsw', 'MhswID', $MhswID, 'Nama');
	$optstatusmhsw = "<select name='StatusMhswID'>".AmbilCombo2('statusmhsw', "concat(StatusMhswID, ' - ', Nama)", 'StatusMhswID', $_SESSION['_statusmhswID'], "", 'StatusMhswID')."</select>";
	$tahunakademik = "<input type=text name='TahunID' value='$w[TahunID]'>";
  }

  
  
  $NamaStatusLama = AmbilOneField('statusmhsw', 'StatusMhswID', $w['StatusMhswLama'], 'Nama');
  CheckFormScript('MhswID,Nama,StatusMhswID,Perihal,Pejabat,Jabatan');
  echo "
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:50%' align='center'>
  <form name='frmstatusmhsw' action='?' method=POST onSubmit='return CheckForm(this)'>
  <input type=hidden name='md' value='$md' />
  <input type=hidden name='id' value='$_REQUEST[id]' />
  <input type=hidden name='lungo' value='SimpanData' />
  <tr><td class=inp>NIM</td>
      <td class=ul>$NIM</td>
      </tr>
  <tr class=inp>
    <td class=inp>Nama</td>
	  <td><input type=hidden name='Nama' value='$HiddenNama'><b>$Nama</b></td>
  <tr>

  </tr>
  <tr style='background:purple;color:white'><th class=ttl colspan=4>Status Lama</th></tr>
  <tr><td class=inp>Status Lama</td>
      <td class=ul><input type=hidden name='StatusMhswLama' value='$w[StatusMhswLama]'>$w[StatusMhswLama] - $NamaStatusLama</td>
  </tr>
  <tr style='background:purple;color:white'><th class=ttl colspan=4>Ubah Menjadi</td>
  <tr><td class=inp>Status Baru</td>
      <td class=ul colspan=3>
        $optstatusmhsw
      </td></tr>
  <tr>
	  <td class=inp>Tahun Akademik<br></td>
      <td class=ul>$tahunakademik</td>
      </tr>
  <tr><td class=inp>No. SK</td>
      <td class=ul colspan=3>
      <input type=text name='SK' value='$w[SK]' size=40 maxlength=50 />
      </td></tr>
  <tr><td class=inp>Perihal</td>
      <td class=ul colspan=3>
      <input type=text name='Perihal' value='$w[Perihal]' size=40 maxlength=50 />
      </td></tr>
  <tr><td class=inp>Tgl. SK</td>
      <td class=ul colspan=3>
      $opttgl
      </td></tr>
  <tr><td class=inp>Pejabat</td>
      <td class=ul colspan=3>
      <input type=text name='Pejabat' value='$w[Pejabat]' size=40 maxlength=50 />
      </td></tr>
  <tr><td class=inp>Jabatan</td>
      <td class=ul colspan=3>
      <input type=text name='Jabatan' value='$w[Jabatan]' size=40 maxlength=50 />
      </td></tr>
  <tr><td class=inp>Keterangan</td>
      <td class=ul colspan=3>
      <textarea name='Keterangan' cols=30 rows=2>$w[Keterangan]</textarea>
      </td></tr>
  <tr><td class=ul colspan=4 align=center>
      <input class='btn btn-success btn-sm' type=submit name='SimpanData' value='Simpan Data' />
      <input class='btn btn-danger btn-sm' type=button name='Batal' value='Batal'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />
      </td></tr>
  </form>
  </table>
  </div>
</div>
</div>";
}
function SimpanData() {
	global $koneksi;
  $md = $_REQUEST['md']+0;
  $id = $_REQUEST['id']+0;
  $MhswID = $_REQUEST['MhswID'];
  $TahunID = $_REQUEST['TahunID'];
  $StatusMhswLama = $_REQUEST['StatusMhswLama'];
  $StatusMhswID = $_REQUEST['StatusMhswID'];
  $SK = sqling($_REQUEST['SK']);
  $Perihal = sqling($_REQUEST['Perihal']);
  $Tanggal = "$_REQUEST[Tanggal_y]-$_REQUEST[Tanggal_m]-$_REQUEST[Tanggal_d]";
  $Pejabat = sqling($_REQUEST['Pejabat']);
  $Jabatan = sqling($_REQUEST['Jabatan']);
  $Keterangan = sqling($_REQUEST['Keterangan']);
  
  // Checking
  if ($StatusMhswLama == $StatusMhswID)
    {die(PesanError('Gagal',
      "Anda tidak boleh mengisikan status yg sama dengan yg lama.<br />
      Gunakan formulir ini hanya untuk perubahan status
      <hr size=1 color=silver />
      Opsi: <input type=button name='Batal' value='Batal'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />"));
	}
//untuk mhsw yg sudah lulus tidak bisa urus permohonan ganti status
if ($StatusMhswLama == L)
    {die(PesanError('Gagal',
      "Anda tidak boleh melakukan perubahan status karena Mahasiswa sudah dinyatakan LULUS
      <hr size=1 color=silver />
      Opsi: <input type=button name='Batal' value='Batal'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />"));
	}
if ($StatusMhswLama == K)
    {die(PesanError('Gagal',
      "Anda tidak boleh melakukan perubahan status karena Mahasiswa sudah dinyatakan KELUAR
      <hr size=1 color=silver />
      Opsi: <input type=button name='Batal' value='Batal'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />"));
	}
  
  $ada = AmbilOneField('mhsw', 'MhswID', $MhswID, 'MhswID');
  if (empty($ada))
    die(PesanError('Gagal',
      "Nomor NIM $MhswID tidak terdaftar di sistem.
      <hr size=1 color=silver />
      Opsi: <input type=button name='Batal' value='Batal'
        onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />"));
  
  if($md == 0)
  {	  
	  $s = "update prosesstatusmhsw
			set TahunID='$TahunID', SK='$SK', Perihal='$Perihal'.
			StatusMhswID = '$StatusMhswID', Pejabat='$Pejabat', Jabatan='$Jabatan',
			Keterangan = '$Keterangan', 
			LoginEdit='$_SESSION[_Login]', TanggalEdit=now()
			where ProsesStatusMhswID = '$id'";
	  $r = mysqli_query($koneksi, $s);
  }
  else if($md == 1)
  {
	  $s = "insert into prosesstatusmhsw
		(Tanggal, KodeID, MhswID, TahunID, SK, Perihal,
		StatusMhswLama, StatusMhswID,
		Pejabat, Jabatan, Keterangan,
		LoginBuat, TglBuat, NA)
		values
		('$Tanggal', '".KodeID."', '$MhswID', '$TahunID', '$SK', '$Perihal',
		'$StatusMhswLama', '$StatusMhswID',
		'$Pejabat', '$Jabatan', '$Keterangan',
		'$_SESSION[_Login]', now(), 'N')";
	  $r = mysqli_query($koneksi, $s);
  }
  else
  die(PesanError('Error',
      "Mode edit tidak ditemukan.
      <hr size=1 color=silver />
      Opsi: <input type=button name='Kembali' value='Kembali' onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />"));
  
  $s = "update mhsw
    set StatusMhswID = '$StatusMhswID'
    where MhswID = '$MhswID' ";
  $r = mysqli_query($koneksi, $s);
  
  $khsada = AmbilOneField('khs', "MhswID='$MhswID' and TahunID='$TahunID' and KodeID", KodeID, 'KHSID');
  if(!empty($khsada))
  {	$s = "update khs set StatusMhswID='$StatusMhswID' where KHSID='$khsada'";
	$r = mysqli_query($koneksi, $s);
  }
  else
  {	$mhsw = AmbilFieldx('mhsw', "KodeID='".KodeID."' and MhswID", $MhswID,
      "Nama, ProgramID, ProdiID, BIPOTID, StatusMhswID");
    // Ambil semester terakhir mhsw
    $_sesiakhir = AmbilOneField('khs', "KodeID='".KodeID."' and MhswID", $MhswID,
      "max(Sesi)")+0;
    if ($_sesiakhir > 0) {
      $_khs = AmbilFieldx('khs', "KodeID='".KodeID."' and MhswID='$MhswID' and Sesi", 
        $_sesiakhir, '*');
      $Sesi = $_khs['Sesi']+1;
      $MaxSKS = AmbilOneField('maxsks', "KodeID='".KodeID."' 
        and DariIP <= $_khs[IPS] and $_khs[IPS] <= SampaiIP
        and ProdiID", $mhsw['ProdiID'], 'SKS')+0;
    }
    else {
      $Sesi = 1;
      $MaxSKS = AmbilOneField('prodi', "KodeID='".KodeID."' and ProdiID",
        $mhsw['ProdiID'], 'DefSKS');
    }
  
    // SimpanData
    $s = "insert into khs
      (TahunID, KodeID, ProgramID, ProdiID, 
      MhswID, StatusMhswID,
      Sesi, IP, MaxSKS,
      LoginBuat, TanggalBuat, NA)
      values
      ('$TahunID', '".KodeID."', '$mhsw[ProgramID]', '$mhsw[ProdiID]',
      '$MhswID', '$StatusMhswID',
      '$Sesi', 0, $MaxSKS,
      '$_SESSION[_Login]', now(), 'N')";
    $r = mysqli_query($koneksi, $s);
  }
  
  StatusMhsw();
}

?>
