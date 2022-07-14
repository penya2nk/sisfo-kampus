<?php
include_once "header_pdf.php";

$TahunID    = GainVariabelx('TahunID');
$ProdiID    = GainVariabelx('ProdiID');
$MhswID     = GainVariabelx('MhswID');
$Angkatan   = GainVariabelx('Angkatan', date('Y'));

TitleApps("PRINT KHS MAHASISWA");
$lungo = (empty($_REQUEST['lungo']))? 'ViewHeaderx' : $_REQUEST['lungo'];
$lungo();

function ViewHeaderx() {
  $optprodi = AmbilPenggunaProdi($_SESSION['_Login'], $_SESSION['ProdiID']);
  CheckFormScript('TahunID,ProdiID,Angkatan');
  $Sekarang = date('Y-m-d-H-i');
  echo <<<SELESAI
  <div class='card'>
<div class='card-header'>
<div class='table-responsive'>
  <table id='example' class='table table-sm table-stripedX' style='width:50%' align='center'>
  <form action='$_SESSION[ndelox].print.php' method=POST target=_blank onSubmit='return CheckForm(this)'>
  <input type=hidden name='lungo' value='CetakKHS' />
  <input type=hidden name='BypassMenu' value='1' />
  <input type=hidden name='Sekarang' value='$Sekarang' />
  <tr>
      <td class=inp>Tahun Akademik</td>
      <td class=ul><input type=text name='TahunID'placeholder='ex: 20221' value='$_SESSION[TahunID]' size=20 maxlength=5 /></td>
   </tr>
   <tr>   
      <td class=inp>Program Studi</td>
      <td class=ul><select name='ProdiID'>$optprodi</select></td>
      </tr>
  <tr>
  <td class=inp>Angkatan Mahasiswa</td>
      <td class=ul><input type=text name='Angkatan' placeholder='ex: 2022' value='$_SESSION[Angkatan]' size=20 maxlength=5 /></td>
     </tr>
     
  <tr>
    <td class=inp>Angkatan Mahasiswa</td>
    <td class=inp>  <input type=text name='MhswID' placeholder='ex: NIM' value='$_SESSION[MhswID]' size=20 maxlength=50 />
     <b style='color:purple'>*) Kosongkan jika ingin mencetak per angkatan</b>
    </td>
   </tr> 
        
  <tr>
     <td >AKSI</td>
      <td ><input class='btn btn-success btn-sm' type=submit name='Cetak' value='Print KHS' /></td>
   </tr> 

  </table>
  </div>
</div>
</div>


 </form>
  </p>
SELESAI;
}
?>
