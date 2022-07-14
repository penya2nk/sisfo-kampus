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
//$angket = mysqli_fetch_array(mysqli_query($koneksi, "SELECT JadwalID,MKID FROM t_ppmiangket WHERE JadwalID='$_GET[JadwalID]' and MKID='$_GET[MKID]'"));
$jdw 	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT JadwalID,DosenID,MKID,TahunID,NamaKelas FROM jadwal WHERE JadwalID='".strfilter($_GET[JadwalID])."'"));
$dos 	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT Login,Nama,Gelar,Handphone FROM dosen WHERE Login='$jdw[DosenID]'"));
//$mhs 	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT MhswID,Nama,ProdiID,Handphone FROM mhsw WHERE MhswID='$jdw[MhswID]'"));
$mk 	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT MKID,MKKode,Nama,SKS,Sesi FROM mk WHERE MKID='$jdw[MKID]'"));
$prd 	= mysqli_fetch_array(mysqli_query($koneksi, "SELECT ProdiID,Nama FROM prodi WHERE ProdiID='$jdw[prodi]'"));

$namaFile = "$dos[Nama]_".$mk[Nama]."_".$jdw[TahunID]."_AngketPBM.xls";
/**/
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=$namaFile");
header("Content-Transfer-Encoding: binary ");

echo"<table align='center' width='800px'> 
<tr style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>
<td colspan=12 align=center><b>ANGKET PENILAIAN DOSEN</b></td>
</tr>
</table>     
<br>

<table border='0' width='800px'  align='center'>
<tr ><td colspan=2 width='20px'>Dosen Pengampu</td><td colspan=11>: $dos[Nama], $dos[Gelar]</td> </tr> 
<tr><td colspan=2>Matakuliah</td><td colspan=11>: $mk[Nama] ($mk[SKS] SKS) - Semester: $mk[Sesi]</td> </tr> 
<tr><td colspan=2>Kelas</td><td colspan=11>: $jdw[NamaKelas]</td> </tr>   
<tr><td colspan=2>Tahun Akademik</td><td colspan=11>: $jdw[TahunID]</td> </tr>  
<tr><td colspan=2></td><td colspan=11></td> </tr>       
</table>

<table  width='800px' border='1' align='center' cellspacing='0'>
<tr style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:reguler;background-color:#e62899;>
<td colspan='2'></td>
<td colspan='10' align='center'><b>Pertanyaan</b></td>
</tr>
<tr style='background-color:#E6E6E6'>
<td align='center' width='80px'>JadwalID</td>
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
        
$sql = mysqli_query($koneksi, "SELECT * from t_ppmiangket WHERE JadwalID='".strfilter($_GET[JadwalID])."' GROUP BY JadwalID,MhswID");
while($r=mysqli_fetch_array($sql)){
$p1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_ppmiangket WHERE PertanyaanID='1' AND JadwalID='$r[JadwalID]' and MhswID='$r[MhswID]'"));
$p2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_ppmiangket WHERE PertanyaanID='2' AND JadwalID='$r[JadwalID]' and MhswID='$r[MhswID]'"));
$p3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_ppmiangket WHERE PertanyaanID='3' AND JadwalID='$r[JadwalID]' and MhswID='$r[MhswID]'"));
$p4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_ppmiangket WHERE PertanyaanID='4' AND JadwalID='$r[JadwalID]' and MhswID='$r[MhswID]'"));
$p5 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_ppmiangket WHERE PertanyaanID='5' AND JadwalID='$r[JadwalID]' and MhswID='$r[MhswID]'"));
$p6 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_ppmiangket WHERE PertanyaanID='6' AND JadwalID='$r[JadwalID]' and MhswID='$r[MhswID]'"));
$p7 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_ppmiangket WHERE PertanyaanID='7' AND JadwalID='$r[JadwalID]' and MhswID='$r[MhswID]'"));
$p8 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_ppmiangket WHERE PertanyaanID='8' AND JadwalID='$r[JadwalID]' and MhswID='$r[MhswID]'"));
$p9 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_ppmiangket WHERE PertanyaanID='9' AND JadwalID='$r[JadwalID]' and MhswID='$r[MhswID]'"));
$p10 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * from t_ppmiangket WHERE PertanyaanID='10' AND JadwalID='$r[JadwalID]' and MhswID='$r[MhswID]'"));

$a1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml1 from t_ppmiangket WHERE Jawaban='1' AND PertanyaanID='1' AND JadwalID='$r[JadwalID]' "));
$a2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml2 from t_ppmiangket WHERE Jawaban='2' AND PertanyaanID='1' AND JadwalID='$r[JadwalID]' "));
$a3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml3 from t_ppmiangket WHERE Jawaban='3' AND PertanyaanID='1' AND JadwalID='$r[JadwalID]' "));
$a4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml4 from t_ppmiangket WHERE Jawaban='4' AND PertanyaanID='1' AND JadwalID='$r[JadwalID]' "));

$b1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml1 from t_ppmiangket WHERE Jawaban='1' AND PertanyaanID='2' AND JadwalID='$r[JadwalID]' "));
$b2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml2 from t_ppmiangket WHERE Jawaban='2' AND PertanyaanID='2' AND JadwalID='$r[JadwalID]' "));
$b3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml3 from t_ppmiangket WHERE Jawaban='3' AND PertanyaanID='2' AND JadwalID='$r[JadwalID]' "));
$b4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml4 from t_ppmiangket WHERE Jawaban='4' AND PertanyaanID='2' AND JadwalID='$r[JadwalID]' "));

$c1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml1 from t_ppmiangket WHERE Jawaban='1' AND PertanyaanID='3' AND JadwalID='$r[JadwalID]' "));
$c2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml2 from t_ppmiangket WHERE Jawaban='2' AND PertanyaanID='3' AND JadwalID='$r[JadwalID]' "));
$c3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml3 from t_ppmiangket WHERE Jawaban='3' AND PertanyaanID='3' AND JadwalID='$r[JadwalID]' "));
$c4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml4 from t_ppmiangket WHERE Jawaban='4' AND PertanyaanID='3' AND JadwalID='$r[JadwalID]' "));

$d1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml1 from t_ppmiangket WHERE Jawaban='1' AND PertanyaanID='4' AND JadwalID='$r[JadwalID]' "));
$d2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml2 from t_ppmiangket WHERE Jawaban='2' AND PertanyaanID='4' AND JadwalID='$r[JadwalID]' "));
$d3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml3 from t_ppmiangket WHERE Jawaban='3' AND PertanyaanID='4' AND JadwalID='$r[JadwalID]' "));
$d4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml4 from t_ppmiangket WHERE Jawaban='4' AND PertanyaanID='4' AND JadwalID='$r[JadwalID]' "));

$e1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml1 from t_ppmiangket WHERE Jawaban='1' AND PertanyaanID='5' AND JadwalID='$r[JadwalID]' "));
$e2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml2 from t_ppmiangket WHERE Jawaban='2' AND PertanyaanID='5' AND JadwalID='$r[JadwalID]' "));
$e3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml3 from t_ppmiangket WHERE Jawaban='3' AND PertanyaanID='5' AND JadwalID='$r[JadwalID]' "));
$e4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml4 from t_ppmiangket WHERE Jawaban='4' AND PertanyaanID='5' AND JadwalID='$r[JadwalID]' "));

$f1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml1 from t_ppmiangket WHERE Jawaban='1' AND PertanyaanID='6' AND JadwalID='$r[JadwalID]' "));
$f2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml2 from t_ppmiangket WHERE Jawaban='2' AND PertanyaanID='6' AND JadwalID='$r[JadwalID]' "));
$f3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml3 from t_ppmiangket WHERE Jawaban='3' AND PertanyaanID='6' AND JadwalID='$r[JadwalID]' "));
$f4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml4 from t_ppmiangket WHERE Jawaban='4' AND PertanyaanID='6' AND JadwalID='$r[JadwalID]' "));

$g1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml1 from t_ppmiangket WHERE Jawaban='1' AND PertanyaanID='7' AND JadwalID='$r[JadwalID]' "));
$g2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml2 from t_ppmiangket WHERE Jawaban='2' AND PertanyaanID='7' AND JadwalID='$r[JadwalID]' "));
$g3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml3 from t_ppmiangket WHERE Jawaban='3' AND PertanyaanID='7' AND JadwalID='$r[JadwalID]' "));
$g4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml4 from t_ppmiangket WHERE Jawaban='4' AND PertanyaanID='7' AND JadwalID='$r[JadwalID]' "));

$h1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml1 from t_ppmiangket WHERE Jawaban='1' AND PertanyaanID='8' AND JadwalID='$r[JadwalID]' "));
$h2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml2 from t_ppmiangket WHERE Jawaban='2' AND PertanyaanID='8' AND JadwalID='$r[JadwalID]' "));
$h3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml3 from t_ppmiangket WHERE Jawaban='3' AND PertanyaanID='8' AND JadwalID='$r[JadwalID]' "));
$h4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml4 from t_ppmiangket WHERE Jawaban='4' AND PertanyaanID='8' AND JadwalID='$r[JadwalID]' "));

$i1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml1 from t_ppmiangket WHERE Jawaban='1' AND PertanyaanID='9' AND JadwalID='$r[JadwalID]' "));
$i2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml2 from t_ppmiangket WHERE Jawaban='2' AND PertanyaanID='9' AND JadwalID='$r[JadwalID]' "));
$i3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml3 from t_ppmiangket WHERE Jawaban='3' AND PertanyaanID='9' AND JadwalID='$r[JadwalID]' "));
$i4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml4 from t_ppmiangket WHERE Jawaban='4' AND PertanyaanID='9' AND JadwalID='$r[JadwalID]' "));

$j1 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml1 from t_ppmiangket WHERE Jawaban='1' AND PertanyaanID='10' AND JadwalID='$r[JadwalID]' "));
$j2 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml2 from t_ppmiangket WHERE Jawaban='2' AND PertanyaanID='10' AND JadwalID='$r[JadwalID]' "));
$j3 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml3 from t_ppmiangket WHERE Jawaban='3' AND PertanyaanID='10' AND JadwalID='$r[JadwalID]' "));
$j4 =mysqli_fetch_array(mysqli_query($koneksi, "SELECT Count(Jawaban) as jml4 from t_ppmiangket WHERE Jawaban='4' AND PertanyaanID='10' AND JadwalID='$r[JadwalID]' "));

$no++;	       
echo"<tr>
<td align='center'>$r[JadwalID]</td>
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
</table>
<br>

<table width='800px' border='1' align='center' cellspacing='0'>
<tr>
<td align='center'>1</td>
<td align='center'>&nbsp;</td>
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
<tr>
<td align='center'>2</td>
<td align='center'>&nbsp;</td>
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
<tr>
<td align='center'>3</td>
<td align='center'>&nbsp;</td>
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
<tr>
<td align='center'>4</td>
<td align='center'>&nbsp;</td>
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
<tr style=text-align:left;font-size:16px;color:#FFFFFF;font-weight:bold;background-color:#e62899;><td align='right'>No</td><td align='left' colspan=11>Pertanyaan</td></tr>";
$q = mysqli_query($koneksi, "SELECT * from t_ppmipertanyaan order by PertanyaanID asc");
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