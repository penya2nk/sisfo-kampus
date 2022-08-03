<div class='card'>
<div class='card-header'>
<div class='col-md-12'>
<div class='box box-info'>
<h1 class="box-title"><b style='color:green;font-size:20px'>TRANSKRIP NILAI</a></b></h1>
<br>1. Hardcopy KHS yang valid adalah versi yang dicetak dan ditandatangani oleh Ka. Prodi 
<br>2. KHS yang anda cetak hanya sebagai referensi nilai yang diperoleh
<br>3. Jika terjadi perbedaaan antara di sistem dan versi cetak maka yang menjadi acuan adalah versi cetak yang sudah diterima dan ditandatangani ka. prodi
            
<?php
$m = mysqli_fetch_array(mysqli_query($koneksi, "SELECT mhsw.MhswID, mhsw.Nama AS NamaMhs, 
mhsw.ProdiID, mhsw.ProgramID, 
prodi.Nama AS NamaProdi 
FROM mhsw,prodi,program 
WHERE mhsw.ProdiID=prodi.ProdiID
AND mhsw.ProgramID=program.ProgramID
AND mhsw.MhswID='$_SESSION[_Login]'")); 

?>


</div>
</div>
</div>



<?php if ($_GET['act']==''){ 	 ?>                                       											   												
<div class='card'>
<div class='card-header'>
<div class='col-md-12'>
<div class='box box-info'>
<?php		
echo"<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>							  							 
	<input type='hidden' name='TahunID' value='$_GET[tahun]'>
	<input type='hidden' name='MhswID' value='$_GET[MhswID]'>
	<input type='hidden' name='prodi' value='$r[ProdiID]'>
		<div class='table-responsive'>
	<table class='table table-sm table-bordered'>
	<tbody>              
	<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#FF7213;width:100%>
	<th scope='row' style='width:200px'>NIM</th>
	<th>$m[MhswID]</th>
	<th>Program </th>
	<th>$m[ProgramID]</th>
	</tr>
							
	<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#FF7213;width:100%>
	<th scope='row' >Nama Mahasiswa </th>
	<th>$m[NamaMhs]</td>
	<th scope='row' style='width:200px'>Program Studi</th> 
	<th>$m[NamaProdi]</th>
	</tr>        
	
	</tbody>
	</table></div>

	<div class='table-responsive'>
	<table class='table table-sm table-bordered table-striped'>                      
	<thead>					 
	<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#ACAC00;width:100%;>
	<th style='width:50px'>No</th>                        
	<th style='width:100px'>Kode</th>
	<th>Matakuliah</th>
	<th>SKS</th>
	
	<th>Grade Nilai</th>
	
	</tr>
	</thead>
	<tbody>";
									
$no = 1;									
$tampil = mysqli_query($koneksi, "SELECT
				  krs.KRSID      AS KRSID,
				  krs.KHSID      AS KHSID,
				  krs.TahunID    AS TahunID,
				  krs.MhswID     AS MhswID,
				  mhsw.Nama      AS NamaMhs,
				  mk.Nama        AS NamaMK,
				  krs.Tugas1     AS Tugas1,
				  krs.Tugas2     AS Tugas2,
				  krs.Tugas3     AS Tugas3,
				  krs.Tugas4     AS Tugas4,
				  krs.Tugas5     AS Tugas5,
				  krs.Presensi   AS Presensi,
				  krs.UTS        AS UTS,
				  krs.UAS        AS UAS,
				  krs.NilaiAkhir AS NilaiAkhir,
				  krs.GradeNilai AS GradeNilai,
				  krs.BobotNilai AS BobotNilai,
				  krs.SKS        AS SKS,
				  mk.MKID        AS MKID,
				  mk.MKKode      AS MKKode,
				  mk.Sesi        AS Sesi,
				  mhsw.ProdiID   AS ProdiID,
				  mhsw.ProgramID AS ProgramID
				  FROM mhsw,krs,mk
				  WHERE krs.MhswID=mhsw.MhswID
				  AND krs.MKID=mk.MKID 
				  AND krs.MhswID='$_SESSION[_Login]'
				  AND krs.GradeNilai NOT IN ('-','TL','E')
				  order by krs.TahunID, krs.MKKode");  								

while($r=mysqli_fetch_array($tampil)){   	
    if ($_tahun != $r['TahunID']) {
      $_tahun = $r['TahunID'];
      echo "<tr style=text-align:left;font-size:14px;color:white;font-weight:reguler;background-color:purple;width:100%;>
        <td class=ul1 colspan=10>
          <font size=+1><b>$_tahun</b></font>
        </td></tr>";
      echo $hdr;
    
    }      
	echo "<tr >
	<td>$no</td>
	<td>$r[MKKode]</td>
	<td>$r[NamaMK]</td>
	<td>$r[SKS]</td>	
	<td>$r[GradeNilai]</td>
	
	</tr>";
$no++;
$nilai = $r['NilaiAkhir'];
if ($nilai >= 85 AND $nilai <= 100){
	$huruf = "A";
	$bobot = "4";
}
elseif ($nilai >= 80 AND $nilai <= 84.99){
	$huruf = "A-";
	$bobot = "3.70";
}
elseif ($nilai >= 75 AND $nilai <= 79.99){
	$huruf = "B+";
	$bobot = "3.30";
}
elseif ($nilai >= 70 AND $nilai <= 74.99){
	$huruf = "B";
	$bobot = "3";
}
elseif ($nilai >= 65 AND $nilai <= 69.99){
	$huruf = "B-";
	$bobot = "2.70";
}
elseif ($nilai >= 60 AND $nilai <= 64.99){
	$huruf = "C+";
	$bobot = "2.30";
}
elseif ($nilai >= 55 AND $nilai <= 59.99){
	$huruf = "C";
	$bobot = "2";
}
elseif ($nilai >= 50 AND $nilai <= 54.99){
	$huruf = "C-";
	$bobot = "1.70";
}
elseif ($nilai >= 40 AND $nilai <= 49.99){
	$huruf = "D";
	$bobot = "1";
}
elseif ($nilai < 40){
	$huruf = "E";
	$bobot = "0";
}

$total_sks 	  	= $r['SKS'];
$total_bobot  	= $r['SKS'] * $bobot;

$tsks 			+= $total_sks;
$tbobottotal 	+= $total_bobot;
$ips = number_format($tbobottotal / $tsks,2);

if ($ips >= 3.00) {
	$YAD=24;
	}
if ($ips < 3.00) {
	$YAD=21;
	}
if ($ips <= 2.49) {
	$YAD=18;
	}
if ($ips <= 1.99) {
	$YAD=15;
	}
if ($ips <= 1.4) {
	$YAD=12;
	}
}
echo"<tr bgcolor=$warna>
	<td colspan='2' align=left><b>Total SKS: </b></td><td  colspan='4'><b> $tsks SKS </b></td>
	</tr>
	<tr bgcolor=$warna>
		<td colspan='2' align=left><b>Total Bobot: </b></td><td colspan='4'><b> $tbobottotal </b></td>
	</tr>
	
	<tr bgcolor=$warna>
		<td colspan='2' align=left><b>IPK: </b></td><td colspan='4'><b> $ips </b></td>
	</tr>
";	
echo "</tbody>
</table></div>";
								  
echo "<div class='box-footer'>
                     
</div>";
echo "</form>
</div>
</div>
</div>";      


}
 //tutup atas
 
 
else if ($_GET['act']=='transkripsp'){ 	 ?>                                       											   												
<div class="col-xs-8">  
	  <div class="box">
		<div class="box-header">
		    <h1 class="box-title"><b style='color:green;font-size:20px'>TRANSKRIP NILAI SEMESTER PENDEK</b></h1>
<?php
echo"<p></p>";
echo"<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>							  							 
	<input type='hidden' name='TahunID' value='$_GET[tahun]'>
	<input type='hidden' name='MhswID' value='$_GET[MhswID]'>
	<input type='hidden' name='prodi' value='$r[ProdiID]'>
	<table class='table table-condensed table-bordered'>
	<tbody>              
	<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#FF7213;width:100%>
	<th scope='row' style='width:200px'>NIM</th>
	<th>$m[MhswID]</th>
	<th>Program </th>
	<th>$m[ProgramID]</th>
	</tr>
							
	<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#FF7213;width:100%>
	<th scope='row' >Nama Mahasiswa </th>
	<th>$m[NamaMhs]</td>
	<th scope='row' style='width:200px'>Program Studi</th> 
	<th>$m[NamaProdi]</th>
	</tr>        
	
	</tbody>
	</table>


	<table class='table table-condensed table-bordered table-striped'>                      
	<thead>					 
	<tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#ACAC00;width:100%;>
	<th style='width:50px'>No</th>                        
	<th style='width:100px'>Kode</th>
	<th>Matakuliah</th>
	<th>SKS</th>
	
	<th>Grade Nilai</th>
	
	</tr>
	</thead>
	<tbody>";
									
$no = 1;									
$tampil = mysqli_query($koneksi, "SELECT
				  t_sp.TahunID    AS TahunID,
				  t_sp.MhswID     AS MhswID,
				  mhsw.Nama      AS NamaMhs,
				  mk.Nama        AS NamaMK,
				  t_sp.NilaiAkhir AS NilaiAkhir,
				  t_sp.GradeNilai AS GradeNilai,
				  t_sp.BobotNilai AS BobotNilai,
				  t_sp.SKS        AS SKS,
				  mk.MKID        AS MKID,
				  mk.MKKode      AS MKKode,
				  mk.Sesi        AS Sesi,
				  mhsw.ProdiID   AS ProdiID,
				  mhsw.ProgramID AS ProgramID
				  FROM mhsw,t_sp,mk
				  WHERE t_sp.MhswID=mhsw.MhswID
				  AND t_sp.MKID=mk.MKID 
				  AND t_sp.MhswID='$_SESSION[_Login]'
				  
				  ORDER BY mk.Sesi,mk.Nama ASC"); //AND t_sp.GradeNilai NOT IN ('-','TL','E') 								

while($r=mysqli_fetch_array($tampil)){   					         
	echo "<tr bgcolor=$warna>
	<td>$no</td>

	<td>$r[MKKode]</td>
	<td>$r[NamaMK]</td>
	<td>$r[SKS]</td>	
	<td>$r[GradeNilai]</td>
	
	</tr>";
$no++;
$nilai = $r['NilaiAkhir'];
if ($nilai >= 85 AND $nilai <= 100){
	$huruf = "A";
	$bobot = "4";
}
elseif ($nilai >= 80 AND $nilai <= 84.99){
	$huruf = "A-";
	$bobot = "3.70";
}
elseif ($nilai >= 75 AND $nilai <= 79.99){
	$huruf = "B+";
	$bobot = "3.30";
}
elseif ($nilai >= 70 AND $nilai <= 74.99){
	$huruf = "B";
	$bobot = "3";
}
elseif ($nilai >= 65 AND $nilai <= 69.99){
	$huruf = "B-";
	$bobot = "2.70";
}
elseif ($nilai >= 60 AND $nilai <= 64.99){
	$huruf = "C+";
	$bobot = "2.30";
}
elseif ($nilai >= 55 AND $nilai <= 59.99){
	$huruf = "C";
	$bobot = "2";
}
elseif ($nilai >= 50 AND $nilai <= 54.99){
	$huruf = "C-";
	$bobot = "1.70";
}
elseif ($nilai >= 40 AND $nilai <= 49.99){
	$huruf = "D";
	$bobot = "1";
}
elseif ($nilai < 40){
	$huruf = "E";
	$bobot = "0";
}

$total_sks 	  	= $r['SKS'];
$total_bobot  	= $r['SKS'] * $bobot;

$tsks 			+= $total_sks;
$tbobottotal 	+= $total_bobot;
$ips = number_format($tbobottotal / $tsks,2);

if ($ips >= 3.00) {
	$YAD=24;
	}
if ($ips < 3.00) {
	$YAD=21;
	}
if ($ips <= 2.49) {
	$YAD=18;
	}
if ($ips <= 1.99) {
	$YAD=15;
	}
if ($ips <= 1.4) {
	$YAD=12;
	}
}
echo"<tr bgcolor=$warna>
	<td colspan='2' align=left><b>Total SKS: </b></td><td  colspan='4'><b> $tsks SKS </b></td>
	</tr>
	<tr bgcolor=$warna>
		<td colspan='2' align=left><b>Total Bobot: </b></td><td colspan='4'><b> $tbobottotal </b></td>
	</tr>
	

";	
echo "</tbody>
</table>";
								  
echo "<div class='box-footer'>
                     
</div>";
echo "</form>
</div>
</div>
</div>";      


} 
 
?>