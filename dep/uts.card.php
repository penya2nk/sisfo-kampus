<?php

include_once "header_pdf.php";

$TahunID = GainVariabelx('TahunID');
$ProdiID = GainVariabelx('ProdiID');
$MhswID = GainVariabelx('MhswID');
$Angkatan = GainVariabelx('Angkatan', date('Y'));

TitleApps("CETAK KARTU UTS");
$lungo = (empty($_REQUEST['lungo']))? 'TampilkanHeader' : $_REQUEST['lungo'];
$lungo();

function TampilkanHeader() {
  $optprodi = AmbilPenggunaProdi($_SESSION['_Login'], $_SESSION['ProdiID']);
  CheckFormScript('TahunID,ProdiID,Angkatan');
  echo <<<SELESAI
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedx' style='width:50%' align='center'>
  <form action='$_SESSION[ndelox].print.php' method=POST target=_blank onSubmit='return CheckForm(this)'>
  <input type=hidden name='lungo' value='CetakKHS' />
  <input type=hidden name='BypassMenu' value='1' />
  <tr>
      <td class=inp>Tahun Akademik</td>
      <td class=ul><input type=text id='TahunID' name='TahunID' value='$_SESSION[TahunID]' size=5 maxlength=5 /></td>
  </tr>
  <tr>            
      <td class=inp>Program Studi</td>
      <td class=ul><select id='ProdiID' name='ProdiID'>$optprodi</select></td>
  </tr>
  <tr>
	  <td class=inp>Angkatan Mahasiswa</td>
      <td class=ul><input type=text name='Angkatan' value='$_SESSION[Angkatan]' size=5 maxlength=5 /></td>
  </tr>
  <tr>      
      <td class=inp>Mahasiswa</td>
      <td class=ul colspan=2 nowrap>
      <input type=text name='MhswID' value='$_SESSION[MhswID]' size=20 maxlength=50 />
      *) Kosongkan jika ingin mencetak 1 angkatan
  </td>
  </tr>

  <tr>
      <td colspan=2 align=left>
      <input class='btn btn-success btn-sm' type=submit name='btnCetak' value='Print Kartu UTS' />
      <input class='btn btn-primary btn-sm' type=button name='Cetak' value='Print List Yang Tidak Bisa UTS' onClick="CetakDaftarTakBisaUjian()">
	  </td>
  </tr>
  </form>
  </table>
  </div>
</div>
</div>
  </p>
  <script>
	function CetakDaftarTakBisaUjian()
	{	
		var thn = document.getElementById('TahunID').value;
		var prd = document.getElementById('ProdiID').value;
		if (thn == '' || prd == ''){
			alert('Tahun Akademik dan Program Studi tidak boleh kosong');
		} else {
		  lnk = '$_SESSION[ndelox].tdkujian.php?TahunID='+thn+'&ProdiID='+prd;
		  win2 = window.open(lnk, '', 'width=600, height=400, scrollbars, status');
		  if (win2.opener == null) childWindow.opener = self;
		}
	}	
  </script>
SELESAI;
}
?>
