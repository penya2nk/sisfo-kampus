<?php
error_reporting(0);
echo <<<SCR
  <script src="$_SESSION[ndelox].script.js"></script>
SCR;

$TahunID = GainVariabelx('TahunID');
$ProdiID = GainVariabelx('ProdiID');
$ProgramID = GainVariabelx('ProgramID');
$MKID = GainVariabelx('MKID');

TitleApps("DAFTAR MAHASISWA YANG MEMENUHI SYARAT MATAKULIAH");
TampilkanHeader();
$lungo = (empty($_REQUEST['lungo']))? 'ListMahasiswaX' : $_REQUEST['lungo'];
$lungo();

function TampilkanHeader() {
  PraScript();
  $optprodi = AmbilPenggunaProdi($_SESSION['_Login'], $_SESSION['ProdiID']);
  $mk = AmbilFieldx('mk', 'MKID', $_SESSION['MKID'], "MKKode, Nama, SKS");
  CheckFormScript("TahunID,ProdiID,MKID");
  echo <<<ESD
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:70%' align='center'>
  
  <form name='frmPra' action='?' method=POST onSubmit="return CheckForm(this)">
  <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
  <input type=hidden name='lungo' value='ListMahasiswaX' />
  <tr>
      <td class=inp>Tahun Akademik</td>
      <td class=ul><input type=text name='TahunID' value='$_SESSION[TahunID]' size=5 maxlength=5 /></td>
      </tr>
  <tr>      
      <td class=inp>Program Studi:</td>
      <td class=ul><select name='ProdiID'>$optprodi</select></td>
      </tr>
  <tr>
      <td class=inp>Kode</td>
      <td >
        <input type=hidden name='MKID' value='$_SESSION[MKID]' />
        <input type=text name='MKKode' value='$mk[MKKode]' size=10 maxlength=15 readonly=true />
        </td>
        </tr>
  <tr>  
  <td class=inp>Nama MK</td>
  <td>
        <input type=text name='MKNama' value='$mk[Nama]' size=60 maxlength=50 onKeyUp="javascript:CariMK(frmPra.ProdiID.value, 'frmPra')" />
        <a href='#'
        onClick="javascript:CariMK(frmPra.ProdiID.value, 'frmPra')" />Cari...</a> |
      <a href='#' onClick="javascript:frmPra.MKID.value='';frmPra.MKKode.value='';frmPra.MKNama.value='';frmPra.SKS.value=0">Reset</a>
        </td>
   </tr>
   
   <tr>
   <td class=inp>SKS</td>
   <td>

        <input type=text name='SKS' value='$mk[SKS]' size=3 maxlength=3 readonly=true /> SKS
      <div style='text-align:right'>
     
      </div>
      </td>
      </tr>
  <tr>
      <td class=ul colspan=4>
      <input class='btn btn-success btn-sm' type=submit name='btnProses' value='Proses Daftar Mhsw' />
      </td></tr>
  </form>
  </table>
  </div>
</div>
</div>
  
  <div class='box0' id='carimk'></div>
ESD;
}
function ListMahasiswaX() {
  if (!empty($_SESSION['MKID'])) {
    ProfileMatakuliah($_SESSION['MKID']);
  }
}
function AmbilPrasyaratMK($mkid, &$arrPrasyarat) {
  global $koneksi;
  $s = "select mp.MKPraID, mp.NilaiID, mp.Bobot, mp.Nilai,
    mk.MKKode, mk.Nama, mk.SKS
    from mkpra mp
      left outer join mk on mk.MKID = mp.PraID
    where mp.MKID = '$mkid'
      and mp.NA = 'N'
    order by mk.MKKode";
  $r = mysqli_query($koneksi, $s);
  $a = array();
  while ($w = mysqli_fetch_array($r)) {
    $a[] = "<abbr title='$w[Nama] - $w[SKS]'>$w[MKKode]</abbr> ($w[Nilai])";
    $arrPrasyarat[] = $w['MKKode'].':'.$w['Nilai'].':'.$w['Bobot'];
  }
  if (!empty($a)) return implode(', ', $a);
  else return '&nbsp';
}
function Selesai() {
  echo <<<ESD
  <div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:70%' align='center'>
  <tr style='background:purple;color:white'><th class=ttl>Selesai Proses</th></tr>
  <tr><td class=ul align=center>
      Proses telah selesai.<br />
      Anda dapat mendownload file proses dengan mengklik tombol:<br />
      <input class='btn btn-success btn-sm' type=button name='btnDownloadFile' value='Download Mhsw Memenuhi Syarat'
        onClick="location='$_SESSION[ndelox].xl.php?fn=$_SESSION[_praFile]'" />
      <input class='btn btn-danger btn-sm' type=button name='btnDownloadFileGagal' value='Download Mhsw yang Tidak Memenuhi Syarat'
        onClick="location='$_SESSION[ndelox].xl.php?fn=$_SESSION[_praFile]_gagal'" />
      </td>
      </tr>
  </table>
  </div>
</div>
</div>
ESD;
}

function ProfileMatakuliah($mkid) {
  global $koneksi;
  $mk = AmbilFieldx("mk m
    left outer join kurikulum k on m.KurikulumID = k.KurikulumID",
    'm.MKID', $mkid, "m.*, k.Nama as KUR, k.KurikulumKode");
  $prasyarat = AmbilPrasyaratMK($mkid, $arrPrasyarat);
  $_prasyarat = implode(',', $arrPrasyarat);
  $_praFile = "tmp/prasyarat_mk";
  // Buat file berhasil proses
  $f = fopen($_praFile.".txt", 'w');
  fwrite($f, "Daftar Mahasiswa Yang Memenuhi Syarat\r\n");
  fwrite($f, "MK: $mk[MKKode]|Nama: $mk[Nama]|SKS: $mk[SKS]\r\n");
  fclose($f);
  // Buat file gagal proses
  $f = fopen($_praFile."_gagal.txt", 'w');
  fwrite($f, "Daftar Mahasiswa Yang Tidak Memenuhi Syarat\r\n");
  fwrite($f, "MK: $mk[MKKode]|Nama: $mk[Nama]|SKS: $mk[SKS]\r\n");
  fclose($f);
  // Tampilkan
  echo <<<ESD
  <div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:100%' align='center'>
  <tr><td class=inp>Kode MK:</td>
      <td class=ul>$mk[MKKode]  - $mk[SKS] SKS</td>
      <td class=inp>Matakuliah:</td>
      <td class=ul>$mk[Nama]</td>
      </tr>
  <tr><td class=inp>Sesi ke-</td>
      <td class=ul>$mk[Sesi]</td>
      <td class=inp>Kurikulum:</td>
      <td class=ul>$mk[KUR] <sup>$mk[KurikulumKode]</sup></td>
      </tr>
  <tr><td class=inp>SKS Min:</td>
      <td class=ul>$mk[SKSMin]</td>
      <td class=inp>IPK:</td>
      <td class=ul>$mk[IPKMin]</td>
      </tr>
  <tr>
      <td class=inp>MK Prasyarat:</td>
      <td class=ul colspan=3>$prasyarat</td>
      </tr>
  <tr><td bgcolor=red height=1 colspan=4></td></tr>
  <tr>
      <td class=inp>Proses:</td>
      <form name='frmProses'>
      <td class=ul colspan=3>
        <input type=text name='_nomer' size=4 />
        <input type=text name='_mhswid' size=10 />
        <input type=text name='_namamhsw' size=30 />
        <input type=text name='_prc' size=30 />
      </td>
      </form>
      </tr>
  </table>
  </div>
</div>
</div>
  <script>var iframeids=['frmPRC'];</script>
  <script src='putiframe.js' language='javascript' type='text/javascript'></script>
  <script>
  function Progresnya(nomer, mhswid, namamhsw, prc) {
    frmProses._nomer.value = nomer;
    frmProses._mhswid.value = mhswid;
    frmProses._namamhsw.value = namamhsw;
    frmProses._prc.value = prc;
  }
  function Selesai() {
    window.location = "../index.php?ndelox=$_SESSION[ndelox]&lungo=Selesai";
  }
  </script>
ESD;
  if ($_REQUEST['btnProses'] != '') {
    echo <<<ESD
    <iframe id="frmPRC" 
      src="$_SESSION[ndelox].proses.php?_praFile=$_praFile&_praPrc=0&_praCnt=0&_praMKID=$mkid&_praNama=$mk[Nama]&_praMKKode=$mk[MKKode]&_praSKS=$mk[SKS]&_praKur=$mk[KurikulumID]&_praSesi=$mk[Sesi]&_praSKSMin=$mk[SKSMin]&_praIPKMin=$mk[IPKMin]&_praPrasyarat=$_prasyarat"
      width=100% height=1 frameborder=0 align=center>
      Browser Anda tidak mendukung frame.
    </iframe>
ESD;
  }
}

function PraScript() {
  echo <<<SCR
  <script>
  function toggleBox(szDivID, iState) // 1 visible, 0 hidden
  {
    if(document.layers)	   //NN4+
    {
       document.layers[szDivID].visibility = iState ? "show" : "hide";
    }
    else if(document.getElementById)	  //gecko(NN6) + IE 5+
    {
        var obj = document.getElementById(szDivID);
        obj.style.visibility = iState ? "visible" : "hidden";
    }
    else if(document.all)	// IE 4
    {
        document.all[szDivID].style.visibility = iState ? "visible" : "hidden";
    }
  }

  function CariMK(ProdiID, frm) {
    if (eval(frm + ".MKNama.value != ''")) {
      eval(frm + ".MKNama.focus()");
      showMK(ProdiID, frm, eval(frm +".MKNama.value"), 'carimk');
      toggleBox('carimk', 1);
    }
  }

  </script>
SCR;
}
?>
<p>&nbsp;</p>
