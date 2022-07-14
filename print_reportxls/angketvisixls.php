<?php 
session_start();
error_reporting(0);
include_once "../pengembang.lib.php";
include_once "../konfigurasi.mysql.php";
include_once "../sambungandb.php";
include_once "../setting_awal.php";
include_once "../check_setting.php";
require ("../punksi/html2pdf/html2pdf.class.php");
$filename='namafile.pdf';
$tgl= tgl_indo(date('Y-m-d'));

//$filename="angket_$_GET[SpID].pdf";
if (empty($_SESSION['_Login']) && empty($_SESSION['_LevelID'])){
	header("Location: ../login.php");
}
else{
$jdw 	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT JadwalID,DosenID,MKID,TahunID,NamaKelas FROM jadwal WHERE JadwalID='".strfilter($_GET[JadwalID])."'"));
$dos 	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar,Handphone FROM dosen WHERE Login='$jdw[DosenID]'"));
$mk 	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT MKID,MKKode,Nama,SKS,Sesi FROM mk WHERE MKID='$jdw[MKID]'"));
$prd 	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama FROM prodi WHERE ProdiID='$jdw[prodi]'"));

$namaFile = "$dos[Nama]_".$mk[Nama]."_".$jdw[TahunID]."_AngketVisiMisiTujuan.xls";
/*
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=$namaFile");
header("Content-Transfer-Encoding: binary ");
*/
echo"<table align='center' width='800px'> 
<tr style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>
<td colspan=12 align=center><b>ANGKET PEMAHAMAN VISI DAN MISI SERTA TUJUAN STMIK HTP</b></td>
</tr>
</table>     
<br>

<table border='0' width='800px'  align='center'>
<tr ><td colspan=2 width='20px'>Kategori Responden</td><td colspan=11>: Mahasiswa</td> </tr>  
<tr><td colspan=2>Tahun Akademik</td><td colspan=11>: $jdw[TahunID]</td> </tr>       
</table>

<table  width='800px' border='1' align='center' cellspacing='0'>
<tr style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>
<td colspan='2'></td>
<td colspan='10' align='center'><b>Pertanyaan</b></td>
</tr>
<tr style='background-color:#E6E6E6'>
<td align='center' width=80px>Responden</td>
<td align='center' width=80px>1</td>
<td align='center' width=80px>2</td>
<td align='center' width=80px>3</td>
<td align='center' width=80px>4</td>
<td align='center' width=80px>5</td>
<td align='center' width=80px>6</td>
<td align='center' width=80px>7</td>
<td align='center' width=80px>8</td>
<td align='center' width=80px>9</td>
<td align='center' width=80px>10</td>
</tr>";
        
$sql = mysqli_query($koneksi, "SELECT * from t_ppmiangketvisi where RespondenKategori='mhs' GROUP BY MhswID");
while($r=mysqli_fetch_array($sql)){
$p1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_ppmiangketvisi WHERE PertanyaanID='1' AND MhswID='$r[MhswID]'"));
$p2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_ppmiangketvisi WHERE PertanyaanID='2' AND MhswID='$r[MhswID]'"));
$p3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_ppmiangketvisi WHERE PertanyaanID='3' AND  MhswID='$r[MhswID]'"));
$p4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_ppmiangketvisi WHERE PertanyaanID='4' AND MhswID='$r[MhswID]'"));
$p5 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_ppmiangketvisi WHERE PertanyaanID='5' AND MhswID='$r[MhswID]'"));
$p6 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_ppmiangketvisi WHERE PertanyaanID='6' AND MhswID='$r[MhswID]'"));
$p7 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_ppmiangketvisi WHERE PertanyaanID='7' AND MhswID='$r[MhswID]'"));
$p8 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_ppmiangketvisi WHERE PertanyaanID='8' AND MhswID='$r[MhswID]'"));
$p9 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_ppmiangketvisi WHERE PertanyaanID='9' AND MhswID='$r[MhswID]'"));
$p10 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_ppmiangketvisi WHERE PertanyaanID='10' AND MhswID='$r[MhswID]'"));

$a1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml1 from t_ppmiangketvisi WHERE Jawaban='A' AND PertanyaanID='1'  "));
$a2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml2 from t_ppmiangketvisi WHERE Jawaban='B' AND PertanyaanID='1' "));
$a3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml3 from t_ppmiangketvisi WHERE Jawaban='C' AND PertanyaanID='1' "));
$a4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml4 from t_ppmiangketvisi WHERE Jawaban='D' AND PertanyaanID='1' "));

$b1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml1 from t_ppmiangketvisi WHERE Jawaban='A' AND PertanyaanID='2' "));
$b2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml2 from t_ppmiangketvisi WHERE Jawaban='B' AND PertanyaanID='2' "));
$b3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml3 from t_ppmiangketvisi WHERE Jawaban='C' AND PertanyaanID='2' "));
$b4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml4 from t_ppmiangketvisi WHERE Jawaban='D' AND PertanyaanID='2' "));

$c1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml1 from t_ppmiangketvisi WHERE Jawaban='A' AND PertanyaanID='3'"));
$c2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml2 from t_ppmiangketvisi WHERE Jawaban='B' AND PertanyaanID='3'"));
$c3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml3 from t_ppmiangketvisi WHERE Jawaban='C' AND PertanyaanID='3'"));
$c4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml4 from t_ppmiangketvisi WHERE Jawaban='D' AND PertanyaanID='3'"));

$d1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml1 from t_ppmiangketvisi WHERE Jawaban='A' AND PertanyaanID='4'"));
$d2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml2 from t_ppmiangketvisi WHERE Jawaban='B' AND PertanyaanID='4'"));
$d3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml3 from t_ppmiangketvisi WHERE Jawaban='C' AND PertanyaanID='4'"));
$d4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml4 from t_ppmiangketvisi WHERE Jawaban='D' AND PertanyaanID='4'"));

$e1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml1 from t_ppmiangketvisi WHERE Jawaban='A' AND PertanyaanID='5'"));
$e2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml2 from t_ppmiangketvisi WHERE Jawaban='B' AND PertanyaanID='5'"));
$e3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml3 from t_ppmiangketvisi WHERE Jawaban='C' AND PertanyaanID='5'"));
$e4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml4 from t_ppmiangketvisi WHERE Jawaban='D' AND PertanyaanID='5'"));

$f1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml1 from t_ppmiangketvisi WHERE Jawaban='A' AND PertanyaanID='6'"));
$f2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml2 from t_ppmiangketvisi WHERE Jawaban='B' AND PertanyaanID='6'"));
$f3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml3 from t_ppmiangketvisi WHERE Jawaban='C' AND PertanyaanID='6'"));
$f4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml4 from t_ppmiangketvisi WHERE Jawaban='D' AND PertanyaanID='6'"));

$g1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml1 from t_ppmiangketvisi WHERE Jawaban='A' AND PertanyaanID='7'"));
$g2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml2 from t_ppmiangketvisi WHERE Jawaban='B' AND PertanyaanID='7'"));
$g3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml3 from t_ppmiangketvisi WHERE Jawaban='C' AND PertanyaanID='7'"));
$g4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml4 from t_ppmiangketvisi WHERE Jawaban='D' AND PertanyaanID='7'"));

$h1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml1 from t_ppmiangketvisi WHERE Jawaban='A' AND PertanyaanID='8'"));
$h2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml2 from t_ppmiangketvisi WHERE Jawaban='B' AND PertanyaanID='8'"));
$h3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml3 from t_ppmiangketvisi WHERE Jawaban='C' AND PertanyaanID='8'"));
$h4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml4 from t_ppmiangketvisi WHERE Jawaban='D' AND PertanyaanID='8'"));

$i1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml1 from t_ppmiangketvisi WHERE Jawaban='A' AND PertanyaanID='9'"));
$i2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml2 from t_ppmiangketvisi WHERE Jawaban='B' AND PertanyaanID='9'"));
$i3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml3 from t_ppmiangketvisi WHERE Jawaban='C' AND PertanyaanID='9'"));
$i4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml4 from t_ppmiangketvisi WHERE Jawaban='D' AND PertanyaanID='9'"));

$j1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml1 from t_ppmiangketvisi WHERE Jawaban='A' AND PertanyaanID='10'"));
$j2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml2 from t_ppmiangketvisi WHERE Jawaban='B' AND PertanyaanID='10'"));
$j3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml3 from t_ppmiangketvisi WHERE Jawaban='C' AND PertanyaanID='10'"));
$j4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml4 from t_ppmiangketvisi WHERE Jawaban='D' AND PertanyaanID='10'"));

$no++;	       
echo"<tr>
<td align='center'>$no</td>
<td align='center'>$p1[Jawaban]</td>
<td align='center'>$p2[Jawaban]</td>
<td align='center'>$p3[Jawaban]</td>
<td align='center'>$p4[Jawaban]</td>
<td align='center'>$p5[Jawaban]</td>
<td align='center'>$p6[Jawaban]</td>
<td align='center'>$p7[Jawaban]</td>
<td align='center'>$p8[Jawaban]</td>
<td align='center'>$p9[Jawaban]</td>
<td align='center'>$p10[Jawaban]</td>
</tr>";
}
echo"

<tr style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#f6aede;>
<td align='center'>A</td>
<td align='center'>$a1[jml1]</td>
<td align='center'>$b1[jml1]</td>
<td align='center'>$c1[jml1]</td>
<td align='center'>$d1[jml1]</td>
<td align='center'>$e1[jml1]</td>
<td align='center'>$f1[jml1]</td>
<td align='center'>$g1[jml1]</td>
<td align='center'>$h1[jml1]</td>
<td align='center'>$i1[jml1]</td>
<td align='center'>$j1[jml1]</td>

</tr>
<tr style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#f6aede;>
<td align='center'>B</td>
<td align='center'>$a2[jml2]</td>
<td align='center'>$b2[jml2]</td>
<td align='center'>$c2[jml2]</td>
<td align='center'>$d2[jml2]</td>
<td align='center'>$e2[jml2]</td>
<td align='center'>$f2[jml2]</td>
<td align='center'>$g2[jml2]</td>
<td align='center'>$h2[jml2]</td>
<td align='center'>$i2[jml2]</td>
<td align='center'>$j2[jml2]</td>
</tr>
<tr style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#f6aede;>
<td align='center'>C</td>
<td align='center'>$a3[jml3]</td>
<td align='center'>$b3[jml3]</td>
<td align='center'>$c3[jml3]</td>
<td align='center'>$d3[jml3]</td>
<td align='center'>$e3[jml3]</td>
<td align='center'>$f3[jml3]</td>
<td align='center'>$g3[jml3]</td>
<td align='center'>$h3[jml3]</td>
<td align='center'>$i3[jml3]</td>
<td align='center'>$j3[jml3]</td>
</tr>
<tr style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#f6aede;>
<td align='center'>D</td>
<td align='center'>$a4[jml4]</td>
<td align='center'>$b4[jml4]</td>
<td align='center'>$c4[jml4]</td>
<td align='center'>$d4[jml4]</td>
<td align='center'>$e4[jml4]</td>
<td align='center'>$f4[jml4]</td>
<td align='center'>$g4[jml4]</td>
<td align='center'>$h4[jml4]</td>
<td align='center'>$i4[jml4]</td>
<td align='center'>$j4[jml4]</td>
</tr>
</table>
<br>
<table width='800px' border='0' align='center' cellspacing='0'>
<tr style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:bold;background-color:#f6aede;><td align='right'>No</td><td align='left' colspan=11>Pertanyaan</td></tr>";
$q = mysqli_query($koneksi, "SELECT * from t_ppmipertanyaanvisi order by PertanyaanID asc");
while($p=mysqli_fetch_array($q)){
$nom++;
echo"<tr ><td >$nom</td><td colspan=11>$p[Pertanyaan]</td></tr>";
}
echo"</table>

<br>


<table border=0 width='800px' align='center'>
<tr>
<td width='300' align='center'>&nbsp;</td>
<td width='230' align='left'>&nbsp;</td>
<td width='230' align='left'>&nbsp;</td>
<td colspan='9' align='right'><b>Pekanbaru, $tgl <br>Ketua Program Studi</b></td>
</tr>

<tr>
  <td align='left'></td>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  <td width='230' align='left' >&nbsp;</td>
  <td width='230' align='left' >&nbsp;</td>
  <td width='230' align='left' >&nbsp;</td>
</tr>

<tr>
  <td align='left'></td>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
  <td align='left' >&nbsp;</td>
</tr>

<tr>
<td align='left'></td>
<td align='left' >&nbsp;</td>
<td align='left' >&nbsp;</td>
<td colspan='10' align='left' >$prodi[Pejabat]</td>
</tr>
</table>
<font style='font-size:8px'>Login by: $_SESSION[id]</font>
";

	
} //session login
?>	