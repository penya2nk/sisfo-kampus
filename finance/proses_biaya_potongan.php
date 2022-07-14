<?php
//error_reporting(0);
$TahunID = GainVariabelx('TahunID');

TitleApps("PROSES BIAYA DAN POTONGAN");
$lungo = (empty($_REQUEST['lungo']))? "Konf" : $_REQUEST['lungo'];
$lungo();

function Konf() {
  CheckFormScript('TahunID');
  echo Info("Proses Biaya dan Potongan Massal",
    "Anda akan memproses Biaya & Potongan Mahasiswa secara massal.<br />
    Tentukan dulu tahun akademik yang akan diproses.<br />
    Pastikan Mahasiswa Sudah Terdaftar di Tahun Akademik Sebelum Diproses.
    <hr size=1 color=silver />
    <form name='frmKonf' action='?' method=POST onSubmit='return CheckForm(this)'>
    <input type=hidden name='lungo' value='FormProses' />
    TAHUN AKADEMIK <input style='height:30px' type=text name='TahunID' value='$_SESSION[TahunID]'
      size=6 maxlength=6 />
    <input class='btn btn-success btn-sm' type=submit name='btnProses' value='Mulai Proses' />
    <hr size=1 color=silver />
    Hubungi Administrator untuk informasi lebih lanjut.
    </form>");
}

function FormProses() {
  $_SESSION['_bptMax'] = 2;
  $_SESSION['_bptPage'] = 0;
  $_SESSION['_bptCounter'] = 0;
  
  echo <<<ESD
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:50%' align='center'>
  <form name='frmProses' >
  
  <tr style='background:purple;color:white'><th class=ttl colspan=4>PROSES PERHITUNGAN BIAYA DAN POTONGAN</th></tr>
  <tr><td class=inp>Proses:</td>
      <td class=ul><input type=text name='_bptCounter' value='' size=10 maxlength=50 /></td>
      </tr>
    <tr><td class=inp>NIM</td>
      <td class=ul colspan=3>
        <input type=text name='_bptMhswID' value='' size=20 maxlength=50 />
      </td>
      </tr>	  
  <tr><td class=inp>Nama Mhsw:</td>
      <td class=ul colspan=3>
        <input type=text name='_bptNamaMhsw' value='' size=50 maxlength=50 />
        </td>
      </tr>
  <tr><td class=inp>BIPOT:</td>
      <td class=ul colspan=3>
        <input type=text name='_bptJumlah' value='0' size=20 maxlength=50 />
      </td>
      </tr>
	    <tr><td class=inp>AKSI</td>
      <td class=ul colspan=3>
         <input class='btn btn-danger btn-sm' type=button name='btnBatal' value='Batalkan Proses'  onClick="location='?ndelox=$_SESSION[ndelox]'" />
      </td>
      </tr>
  </form>
  </table>
  </div>
</div>
</div>

  
  <script>
  function fnProgress(cnt, mhswid, nama, jml) {
    frmProses._bptCounter.value = cnt;
    frmProses._bptMhswID.value = mhswid;
    frmProses._bptNamaMhsw.value = nama;
    frmProses._bptJumlah.value = jml;
  }
  function fnSelesai(thn, cnt) {
    alert("Proses BIPOT mahasiswa tahun " + thn + " telah selesai. Berhasil diproses: " + cnt);
    window.location="../index.php?ndelox=$_SESSION[ndelox]";
  }
  </script>
  
  <iframe name='frmProsesDetail' src="$_SESSION[ndelox].go.php?lungo=&_bptMax=$_SESSION[_bptMax]&_bptPage=0&_bptCounter=0" frameborder=0 width=600 height=200 align=center>
  Tidak mendukung frame.
  </iframe>
ESD;
}
?>
