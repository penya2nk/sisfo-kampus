<div class='card'>
<div class='card-header'>
        <div class="form-group row">
        <label class="col-md-7 col-form-label text-md-right"><b style='color:purple'>ANGKA LULUSAN </b><a href='?ndelox=reports/angka.lulusan&act=rekaplulusantahun&tahun=<?php echo $_GET['tahun']; ?>&prodi=<?php echo "$_GET[prodi]"; ?>&program=<?php echo 	"$_GET[program]"; ?>'>[ Data Wisuda Per Tahun ]</a> </b></label>
        <div class="col-md-2">
        <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
        <input type="hidden" name='ndelox' value='reports/angka.lulusan'>
        <?php 
        echo"<select class='form-control form-control-sm' name='prodi'  onChange='this.form.submit()'>";
        echo "<option value=''>- Pilih Program Studi -</option>";
        echo "<option value='All' selected>Seluruh Program Studi</option>";
        $prodi = mysqli_query($koneksi, "SELECT * from prodi order by Nama ASC");
        while ($k = mysqli_fetch_array($prodi)){
        if ($_GET['prodi']==$k['ProdiID']){
          echo "<option value='$k[ProdiID]' selected>$k[Nama]</option>";
        }else{
          echo "<option value='$k[ProdiID]'>$k[Nama]</option>";
          }
        }
        ?>
        </select>
        </div>                


        <div class="col-md-1">	
        <input type="submit"  class='btn btn-success btn-sm' value='Lihat'>
        </div>                


        <div class="col-md-1">	
        </form>
        <a class='pull-right btn btn-primary btn-sm' href='index.php?ndelox=reports/angka.lulusan&act=tambahdata&tahun=<?php echo $_GET['tahun']; ?>&prodi=<?php echo "$_GET[prodi]"; ?>&program=<?php echo 	"$_GET[program]"; ?>'>Tambahkan Data</a>
        </div>
      </div>    
</div>
</div>


    
<?php if ($_GET['act']==''){ ?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example" class="table table-sm table-striped">
<thead>
<tr>
  <th style='width:20px' >No<center>(1)</center></th>                           
  <th><div align='center'>Tahun </div><center>(2)</center></th>   
  <th><div align='left'>Program Studi </div><left>(3)</left></th>
  <th><div align='center'>Periode<br><center>(4)</center></th>              
  <th><div align='center'>Reguler <br><center>(5)</center></th>
  <th><div align='center'>Transfer <br><center>(6)</center></th>
  <th><div align='center'>Pindahan <br><center>(7)</center></th> 
  <th><div align='center'>Pria <br><center>(9)</center></th>
  <th><div align='center'>Wanita <br><center>(9)</center></th>
   <th><div align='center'>Total <br><center>(8+9) <br><center></center></th>                                
  <th width='190px'><div align='center'>Action <br><center>(11)</center></th> 
</tr>
 
</thead>
<tbody>

<?php
if ($_GET['prodi']=='SI'){    
    $sqp = mysqli_query($koneksi, "SELECT * FROM lulusan WHERE ProdiID='".strfilter($_GET['prodi'])."' order by TahunID desc");
}
else if ($_GET['prodi']=='TI'){      
    $sqp = mysqli_query($koneksi, "SELECT * FROM lulusan WHERE ProdiID='".strfilter($_GET['prodi'])."' order by TahunID desc");
}    
else if ($_GET['prodi']=='All'){
    $sqp = mysqli_query($koneksi, "SELECT * FROM lulusan  order by ProdiID,TahunID desc");
    
} 
   
	while($r=mysqli_fetch_array($sqp)){
	$no++;    
	     $prod = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM prodi  where ProdiID='$r[ProdiID]'"));
	if ($r['ProdiID']=='SI'){
	    $c="style=color:purple";
	}else{
	    $c="style=color:green";
	}	 

	$regAll +=  $r['Reguler'];
	$traAll +=  $r['Transfer'];
	$pinAll +=  $r['Pindahan'];
	$grandTot = $r['Pria'] + $r['Wanita'];
	//$grandTot2 +=  $grandTot;
	//$grandTot2 =  $regAll + $traAll + $grandTot;
	$grandTot2 =  $grandTot2 + $grandTot;
	echo "<tr $c><td align='center'>$no</td>                  
		<td align='center'><a href='index.php?ndelox=admyudisium&prodi=$r[ProdiID]&tahun=$r[TahunID]&LulusanID=$r[LulusanID]'>$r[TahunID]</a></td>
		<td align='left'> $prod[Nama]</td>
		<td align='center'>$r[Periode]</td>
		<td align='center'>$r[Reguler]</td>
		<td align='center'>$r[Transfer]</td>
		<td align='center'>$r[Pindahan]</td>
		<td align='center'>$r[Pria]</td>
		<td align='center'>$r[Wanita]</td>					
		<td align='center'>$grandTot</td>";                            

echo "<td style='width:70px !important'><center>
<a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=reports/angka.lulusan&act=edit&prodi=$r[ProdiID]&tahun=$r[TahunID]&LulusanID=$r[LulusanID]'>
<i class='fa fa-edit'></i></a>

<a  class='btn btn-success btn-xs' href='index.php?ndelox=admyudisium&LulusanID=$r[LulusanID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'>
<span class='glyphicon glyphicon-th'></span> View</a>

<a  class='btn btn-warning btn-xs' target='_BLANK' href='print_reportxls/xxx.php?tahun=$r[TahunID]'>
<span class='glyphicon glyphicon-book'></span> Export</a>

<a class='btn btn-danger btn-xs' title='Hapus' href='index.php?ndelox=reports/angka.lulusan&hapus=$r[LulusanID]&prodi=$r[ProdiID]&tahun=$r[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
</center>
</td>";

echo "</tr>";
$no++;
}

if (isset($_GET['hapus'])){
mysqli_query($koneksi, "DELETE FROM lulusan where LulusanID='".strfilter($_GET['hapus'])."'");
echo "<script>document.location='index.php?ndelox=reports/angka.lulusan&program=$_GET[program]&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
}
;
?>
<tbody>
</table>
</div>
</div>
</div>


<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example" class="table table-sm table-striped">
<thead>
<tr><td  width=300px><b>Total Reguler</b></td><td width=10px>:</td><td style='text-align:right;width:30px;'>  <?php echo"<b>$regAll</b>";?> </td><td>Mahasiswa</td></tr>
<tr><td ><b>Total Transfer</b>	</td><td>:</td><td style='text-align:right;width:30px;'>  <?php echo"<b>$traAll</b>";?> </td><td>Mahasiswa</td></tr>
<tr><td ><b>Total Pindahan</b>	</td><td>:</td><td style='text-align:right;width:30px;'>  <?php echo"<b><b>$pinAll</b>";?> </td><td>Mahasiswa</td></tr>
<tr style=background-color:#FFEB99;><td ><b>Total Keseluruhan Lulusan</b></td><td >:</td><td style='text-align:right;width:30px;'>  <?php echo"<b>$grandTot2</b>";?> </td><td>Mahasiswa</td></tr>
<tr><td colspan='4'><a href='?ndelox=reports/angka.lulusan&act=graflulusan&prodi=<?php echo"$_GET[prodi]";?>'>Grafik Lulusan</td></tr>
</table>
</div>
</div>
</div>

<?php 
}
else if ($_GET['act']=='rekaplulusantahun'){ ?>
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<table id="example" class="table table-sm table-striped">
<thead>
<tr>
  <th style='width:20px' >No<center>(1)</center></th>                           
  <th><div align='center'>Tahun </div><center>(2)</center></th>            
  <th><div align='center'>Program Studi<br><center>(3)</center></th>              
  <th><div align='center'>Reguler <br><center>(4)</center></th>
  <th><div align='center'>Transfer <br><center>(5)</center></th>
  <th><div align='center'>Pindahan <br><center>(6)</center></th> 
  <th><div align='center'>Pria <br><center>(7)</center></th>
  <th><div align='center'>Wanita <br><center>(8)</center></th>
   <th><div align='center'>Total <br><center>(7+8) <br><center></center></th>                                
  <th width='190px'><div align='center'>Action <br><center>(10)</center></th> 
</tr>
 
</thead>
<tbody>

<?php
$grafik = mysqli_query($koneksi, "SELECT * FROM t_tahunnormal GROUP BY Tahun order by Tahun DESC");	
$no=1;
while ($r = mysqli_fetch_array($grafik)){
if ($_GET['prodi']=='SI'){
	$prodi="Sistem Informasi";	
	$reg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Reguler) as JumReg,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE ProdiID='".strfilter($_GET['prodi'])."' 
								  AND LEFT(TahunID,4)='$r[Tahun]' "));
	$tra = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Transfer) as JumTra,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE ProdiID='".strfilter($_GET['prodi'])."' 
								  AND LEFT(TahunID,4)='$r[Tahun]' "));
	$pin = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Pindahan) as JumPin,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE ProdiID='".strfilter($_GET['prodi'])."' 
								  AND LEFT(TahunID,4)='$r[Tahun]' "));
	$pria = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Pria) as JumPria,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE ProdiID='".strfilter($_GET['prodi'])."' 
								  AND LEFT(TahunID,4)='$r[Tahun]' "));	
	$wanita = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Wanita) as JumWanita,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE ProdiID='".strfilter($_GET['prodi'])."' 
								  AND LEFT(TahunID,4)='$r[Tahun]' "));
	$grandTot = $pria['JumPria']+$wanita['JumWanita'];							  								  							  				    
	//Keseluruhan ------------------------------------------------------------------------------------
	$regAll = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Reguler) as JumRegAll,ProdiID,TahunID 
									  FROM lulusan WHERE ProdiID='".strfilter($_GET['prodi'])."'"));
	$traAll = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Transfer) as JumTraAll,ProdiID,TahunID 
									  FROM lulusan WHERE ProdiID='".strfilter($_GET['prodi'])."'"));
	$pinAll = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Pindahan) as JumPinAll,ProdiID,TahunID 
									  FROM lulusan WHERE ProdiID='".strfilter($_GET['prodi'])."'"));
									  
	$grandTotAll = $regAll['JumRegAll']+$traAll['JumTraAll']+$pinAll['JumPinAll'];
	}
else if ($_GET['prodi']=='TI'){
	$prodi="Teknik Informatika";
	$reg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Reguler) as JumReg,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE ProdiID='".strfilter($_GET['prodi'])."' 
								  AND LEFT(TahunID,4)='$r[Tahun]' "));
	$tra = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Transfer) as JumTra,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE ProdiID='".strfilter($_GET['prodi'])."' 
								  AND LEFT(TahunID,4)='$r[Tahun]' "));
	$pin = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Pindahan) as JumPin,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE ProdiID='".strfilter($_GET['prodi'])."' 
								  AND LEFT(TahunID,4)='$r[Tahun]' "));
	$pria = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Pria) as JumPria,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE ProdiID='".strfilter($_GET['prodi'])."' 
								  AND LEFT(TahunID,4)='$r[Tahun]' "));	
	$wanita = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Wanita) as JumWanita,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE ProdiID='".strfilter($_GET['prodi'])."' 
								  AND LEFT(TahunID,4)='$r[Tahun]' "));
	$grandTot = $pria['JumPria']+$wanita['JumWanita'];							  								  							  				    
	//Keseluruhan ------------------------------------------------------------------------------------
	$regAll = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Reguler) as JumRegAll,ProdiID,TahunID 
									  FROM lulusan WHERE ProdiID='".strfilter($_GET['prodi'])."'"));
	$traAll = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Transfer) as JumTraAll,ProdiID,TahunID 
									  FROM lulusan WHERE ProdiID='".strfilter($_GET['prodi'])."'"));
	$pinAll = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Pindahan) as JumPinAll,ProdiID,TahunID 
									  FROM lulusan WHERE ProdiID='".strfilter($_GET['prodi'])."'"));	
	$grandTotAll = $regAll['JumRegAll']+$traAll['JumTraAll']+$pinAll['JumPinAll'];
	
}//tutup TI
else if ($_GET['prodi']=='All'){
	$prodi="Semua Program Studi";
	$reg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Reguler) as JumReg,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE LEFT(TahunID,4)='$r[Tahun]' "));
	$tra = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Transfer) as JumTra,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE LEFT(TahunID,4)='$r[Tahun]' "));
	$pin = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Pindahan) as JumPin,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE LEFT(TahunID,4)='$r[Tahun]' "));
	$pria = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Pria) as JumPria,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE LEFT(TahunID,4)='$r[Tahun]' "));	
	$wanita = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Wanita) as JumWanita,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE LEFT(TahunID,4)='$r[Tahun]' "));
	$grandTot = $pria['JumPria']+$wanita['JumWanita'];							  								  							  				    
	//Keseluruhan ------------------------------------------------------------------------------------
	$regAll = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Reguler) as JumRegAll,ProdiID,TahunID 
									  FROM lulusan"));
	$traAll = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Transfer) as JumTraAll,ProdiID,TahunID 
									  FROM lulusan"));
	$pinAll = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Pindahan) as JumPinAll,ProdiID,TahunID 
									  FROM lulusan"));	
	$grandTotAll = $regAll['JumRegAll']+$traAll['JumTraAll']+$pinAll['JumPinAll'];
	
} //Tutup All
echo "

<tr><td align='center'>$no</td>                  
        <td align='center'><a href='index.php?ndelox=reports/angka.lulusan&act=detail&prodi=$_GET[prodi]&tahun=$r[Tahun]'>$r[Tahun]</a></td>
        <td align='center'>$prodi</td>
        <td align='center'>$reg[JumReg]</td>
        <td align='center'>$tra[JumTra]</td>
        <td align='center'>$pin[JumPin]</td>
        <td align='center'>$pria[JumPria]</td>
        <td align='center'>$wanita[JumWanita]</td>					
        <td align='center'>$grandTot</td>";                            

echo "<td style='width:70px !important'><center>
<a class='btn btn-success btn-xs' title='Edit' href='index.php?ndelox=reports/angka.lulusan&act=edit&prodi=$r[ProdiID]&tahun=$r[TahunID]&LulusanID=$r[LulusanID]'>
<i class='fa fa-edit'></i></a>

<a  class='btn btn-success btn-xs' href='index.php?ndelox=reports/angka.lulusan&act=xx&LulusanID=$r[LulusanID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'>
<span class='glyphicon glyphicon-th'></span> View</a>

<a  class='btn btn-warning btn-xs' target='_BLANK' href='print_reportxls/xxx.php?tahun=$r[TahunID]'>
<span class='glyphicon glyphicon-book'></span> Export</a>

<a class='btn btn-danger btn-xs' title='Hapus' href='index.php?ndelox=reports/angka.lulusan&hapus=$r[LulusanID]&prodi=$r[ProdiID]&tahun=$r[TahunID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-trash'></i></a>
</center>
</td>";

echo "</tr>

";
$no++;
}

if (isset($_GET['hapus'])){
mysqli_query($koneksi, "DELETE FROM lulusan where LulusanID='".strfilter($_GET['hapus'])."'");
echo "<script>document.location='index.php?ndelox=reports/angka.lulusan&program=$_GET[program]&prodi=$_GET[prodi]&tahun=$_GET[tahun]';</script>";
}
;


?>
<tbody>
</table>
<table id="example" class="table table-bordered table-striped">
<thead>
<tr><td  width=300px><b>Total Reguler</b></td><td width=10px>:</td><td style='text-align:right;width:30px;'>  <?php echo"<b>$regAll[JumRegAll]</b>";?> </td><td>Mahasiswa</td></tr>
<tr><td ><b>Total Transfer</b>	</td><td>:</td><td style='text-align:right;width:30px;'>  <?php echo"<b>$traAll[JumTraAll]</b>";?> </td><td>Mahasiswa</td></tr>
<tr><td ><b>Total Pindahan</b>	</td><td>:</td><td style='text-align:right;width:30px;'>  <?php echo"<b><b>$pinAll[JumPinAll]</b>";?> </td><td>Mahasiswa</td></tr>
<tr style=background-color:#FFEB99;><td ><b>Total Keseluruhan Lulusanx</b></td><td >:</td><td style='text-align:right;width:30px;'>  <?php echo"<b>$grandTotAll</b>";?> </td><td>Mahasiswa</td></tr>
<tr><td colspan=4><a href='?ndelox=reports/angka.lulusan&act=graflulusan&prodi=<?php echo"$_GET[prodi]";?>'>Grafik Lulusan</td></tr>
</table>

</div>
</div>
</div>

<?php 
}


elseif($_GET['act']=='tambahdata'){
if (isset($_POST['simpanx'])){	
$tglnow =date('Y-m-d H:i:s');	
$cek = mysqli_num_rows(mysqli_query($koneksi, "select * from lulusan where TahunID='$_POST[tahun]' and ProdiID='$_POST[prodi]'"));
  if ($cek>0){
      echo "<script>document.location='index.php?ndelox=reports/angka.lulusan&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&gagal';</script>";
      exit;
  }
                
$query = mysqli_query($koneksi, "INSERT INTO lulusan(
                  Periode,
				  TahunID,
                  ProdiID,
                  Reguler,
                  Pindahan,
                  Transfer,
                  Pria,
                  Wanita,
                  TglBuat,
                  LoginBuat) 
         VALUES('".strfilter($_POST['Periode'])."',
			    '".strfilter($_POST['tahun'])."',
                '".strfilter($_POST['prodi'])."',
                '".strfilter($_POST['Reguler'])."',
                '".strfilter($_POST['Pindahan'])."',
                '".strfilter($_POST['Transfer'])."',							
                '".strfilter($_POST['Pria'])."',
                '".strfilter($_POST['Wanita'])."',						
                '".date('Y-m-d H:i:s')."',
                '".$_SESSION['_Login']."')");

if ($query){
echo "<script>document.location='index.php?ndelox=reports/angka.lulusan&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&sukses';</script>";
}else{
echo "<script>document.location='index.php?ndelox=reports/angka.lulusan&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&gagal';</script>";
}

}

echo "
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
    
    <input type='hidden' name='program' value='$_GET[program]'>
    <input type='hidden' name='prodi' value='$_GET[prodi]'>				
    <div class='card'>
    <div class='card-header'>
    <div class='table-responsive'>
      <table class='table table-condensed table-bordered'>
      <tbody>
        <tr><th scope='row' width='200px'>Tahun Akademik</th> <td><input type='text' class='form-control' name='tahun' ></td></tr>          
        <tr><th scope='row'>Program Studi </th> <td><input type='text' class='form-control' name='x' value='$_GET[prodi]' readonly=''></td></tr>         										
        <tr><th scope='row'>Periode </th> <td><input type='text' class='form-control' name='Periode' ></td></tr> 
		<tr><th scope='row'>Reguler </th> <td><input type='text' class='form-control' name='Reguler' ></td></tr>
        <tr><th scope='row'>Pindahan </th> <td><input type='text' class='form-control' name='Pindahan' ></td></tr>
        <tr><th scope='row'>Tranfer </th> <td><input type='text' class='form-control' name='Transfer' ></td></tr>
        <tr><th scope='row'>Pria </th> <td><input type='text' class='form-control' name='Pria' ></td></tr>
        <tr><th scope='row'>Wanita</th> <td><input type='text' class='form-control' name='Wanita' ></td></tr>
        
      </tbody>
      </table>
      <div class='box-footer'>
        <button type='submit' name='simpanx' class='btn btn-info'>Simpan</button>
        <a href='index.php?ndelox=reports/angka.lulusan&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a                    
      </div>
    </div>
  </div>
  
  </form>
</div>";
}

elseif($_GET['act']=='edit'){

$j = mysqli_fetch_array(mysqli_query($koneksi, "select * from lulusan where LulusanID='".strfilter($_GET['LulusanID'])."'"));

if (isset($_POST['ubahx'])){		  					 	   	     				
$query = mysqli_query($koneksi, "UPDATE lulusan SET
                  TahunID 	='".strfilter($_POST['tahun'])."',
				  Periode 	='".strfilter($_POST['Periode'])."',
                  ProdiID 	='".strfilter($_POST['prodi'])."',
                  Reguler 	='".strfilter($_POST['Reguler'])."',
                  Pindahan 	='".strfilter($_POST['Pindahan'])."',
                  Transfer 	='".strfilter($_POST['Transfer'])."',
                  Pria 		='".strfilter($_POST['Pria'])."',
                  Wanita 	='".strfilter($_POST['Wanita'])."',
                  LoginEdit	='".$_SESSION['_Login']."',
                  TglEdit	='".date('Y-m-d H:i:s')."'
                  WHERE LulusanID ='".strfilter($_POST['LulusanID'])."' ");
        

if ($query){
echo "<script>document.location='index.php?ndelox=reports/angka.lulusan&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&sukses';</script>";
}else{
echo "<script>document.location='index.php?ndelox=reports/angka.lulusan&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&gagal';</script>";
}

}

echo "
  <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
    <input type='hidden' name='LulusanID' value='$_GET[LulusanID]'>
    <input type='hidden' name='tahun' value='$_GET[tahun]'>
    <input type='hidden' name='program' value='$_GET[program]'>
    <input type='hidden' name='prodi' value='$_GET[prodi]'>				
    <div class='card'>
    <div class='card-header'>
    <div class='table-responsive'>
      <table class='table table-condensed table-bordered'>
      <tbody>
        <tr><th scope='row' width='200px'>Tahun Akademik</th> <td><input type='text' class='form-control' name='x' value='$_GET[tahun]' readonly=''></td></tr>          
        <tr><th scope='row'>Program Studi </th> <td><input type='text' class='form-control' name='x' value='$_GET[prodi]' readonly=''></td></tr>
        <tr><th scope='row'>Periode </th> <td><input type='text' class='form-control' name='Periode' value='$j[Periode]' ></td></tr> 		
        <tr><th scope='row'>Reguler </th> <td><input type='text' class='form-control' name='Reguler' value='$j[Reguler]'></td></tr>
        <tr><th scope='row'>Pindahan </th> <td><input type='text' class='form-control' name='Pindahan' value='$j[Pindahan]'></td></tr>
        <tr><th scope='row'>Tranfer </th> <td><input type='text' class='form-control' name='Transfer' value='$j[Transfer]'></td></tr>
        <tr><th scope='row'>Pria </th> <td><input type='text' class='form-control' name='Pria' value='$j[Pria]'></td></tr>
        <tr><th scope='row'>Wanita</th> <td><input type='text' class='form-control' name='Wanita' value='$j[Wanita]'></td></tr>
        
      </tbody>
      </table>
      <div class='box-footer'>
      <button type='submit' name='ubahx' class='btn btn-info'>Perbaharui Data</button>
      <a href='index.php?ndelox=reports/angka.lulusan&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a                    
    </div>
    </div>
  </form>
</div>";
}


elseif($_GET['act']=='tambahdataangkatanlls'){
if (isset($_POST['simpanz'])){	
$tglnow =date('Y-m-d H:i:s');	
$cek = mysqli_num_rows(mysqli_query($koneksi, "select * from lulusan where TahunID='$_POST[tahun]' and ProdiID='$_POST[prodi]'"));
  if ($cek>0){
      echo "<script>document.location='index.php?ndelox=reports/angka.lulusan&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&gagal';</script>";
      exit;
  }
                
$query = mysqli_query($koneksi, "INSERT INTO lulusan(
                  TahunID,
                  ProdiID,
                  Reguler,
                  Pindahan,
                  Transfer,
                  Pria,
                  Wanita,
                  TglBuat,
                  LoginBuat) 
         VALUES('".strfilter($_POST['tahun'])."',
                '".strfilter($_POST['prodi'])."',
                '".strfilter($_POST['Reguler'])."',
                '".strfilter($_POST['Pindahan'])."',
                '".strfilter($_POST['Transfer'])."',							
                '".strfilter($_POST['Pria'])."',
                '".strfilter($_POST['Wanita'])."',						
                '".date('Y-m-d H:i:s')."',
                '".$_SESSION['_Login']."')");

if ($query){
echo "<script>document.location='index.php?ndelox=reports/angka.lulusan&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&sukses';</script>";
}else{
echo "<script>document.location='index.php?ndelox=reports/angka.lulusan&tahun=$_POST[tahun]&program=$_POST[program]&prodi=$_POST[prodi]&gagal';</script>";
}

}

echo "
<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
    
    <input type='hidden' name='program' value='$_GET[program]'>
    <input type='hidden' name='prodi' value='$_GET[prodi]'>				
    <div class='card'>
    <div class='card-header'>
    <div class='table-responsive'>
      <table class='table table-condensed table-bordered'>
      <tbody>
        <tr><th scope='row' width='200px'>Tahun Akademik</th> <td><input type='text' class='form-control' name='tahun' ></td></tr>          
        <tr><th scope='row'>Program Studi </th> <td><input type='text' class='form-control' name='x' value='$_GET[prodi]' readonly=''></td></tr>         										
        <tr><th scope='row'>Reguler </th> <td><input type='text' class='form-control' name='Reguler' ></td></tr>
        <tr><th scope='row'>Pindahan </th> <td><input type='text' class='form-control' name='Pindahan' ></td></tr>
        <tr><th scope='row'>Tranfer </th> <td><input type='text' class='form-control' name='Transfer' ></td></tr>
        <tr><th scope='row'>Pria </th> <td><input type='text' class='form-control' name='Pria' ></td></tr>
        <tr><th scope='row'>Wanita</th> <td><input type='text' class='form-control' name='Wanita' ></td></tr>
        
      </tbody>
      </table>
      <div class='box-footer'>
      <button type='submit' name='simpanz' class='btn btn-info'>Simpan</button>
      <a href='index.php?ndelox=reports/angka.lulusan&tahun=$_GET[tahun]&prodi=$_GET[prodi]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a                    
    </div>
    </div>
  </form>
</div>";
}

elseif($_GET['act']=='detail'){
?>	

<div class='card'>
    <div class='card-header'>
    <div class='table-responsive'>
<table id="example1" class="table table-bordered table-striped">
<thead>
<tr>
  <th style='width:20px'>No</th>                           
  <th>NIM</th>   
  <th style='width:200px'>Nama Mahasiswa x</th> 
  <th style='width:20px'>Gender</th> 
  <th style='width:400px'>Judul TA</th>  
  <th>Prodi</th>
                          
  <th>Handphone</th>                                 
  <th width='240px'>Action</th> 
</tr>
</thead>
<tbody>
<?php		
$tampil = mysqli_query($koneksi, "SELECT  
mhsw.MhswID,mhsw.Nama as NamaMahasiswa,mhsw.Alamat,mhsw.ProdiID,mhsw.Kelamin,mhsw.Handphone,
ta.TAID,
ta.Judul,		 
ta.TahunID
from mhsw,ta
where mhsw.MhswID=ta.MhswID
and left(ta.TahunID,4)='".strfilter($_GET['tahun'])."'		
and mhsw.ProdiID='".strfilter($_GET['prodi'])."'
order by NamaMahasiswa asc");                      

$no = 1;
while($r=mysqli_fetch_array($tampil)){	
$kel =$r['Kelamin'];
if ($kel=='P'){
  $Kelamin="Pria";
}else{
  $Kelamin="Wanita";
}		 	
echo "<tr><td>$no</td>                  
<td><a href='index.php?ndelox=reports/angka.lulusan&prodi=$r[ProdiID]&tahun=$r[TahunID]'>$r[MhswID]</a></td>
<td>$r[NamaMahasiswa]</td>
<td>$Kelamin</td>
<td>$r[Judul] </td>
<td>$r[ProdiID] </td>

<td>$r[Handphone]</td>";                            
echo "<td style='width:70px !important'><center><a class='btn btn-success btn-xs' title='Edit Jadwal' href='index.php?ndelox=reports/angka.lulusan&act=edit&TAID=$r[TAID]&program=$r[ProgramID]&prodi=$r[ProdiID]&tahun=$r[TahunID]'><span class='glyphicon glyphicon-edit'></span></a>

<a class='btn btn-warning btn-xs' href='index.php?ndelox=reports/angka.lulusan&prodi=$r[ProdiID]&tahun=$r[TahunID]'><span class='glyphicon glyphicon-book'></span> Rekap</a>


<a class='btn btn-danger btn-xs' title='Hapus' href='index.php?ndelox=reports/angka.lulusan&act=detail&id=$r[TAID]&program=$r[ProgramID]&prodi=$r[ProdiID]&tahun=$r[TahunID]&DosenID=$r[DosenID]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus x?')\"><span class='glyphicon glyphicon-remove'></span></a>
</center></td>";

echo "</tr>
";
          $no++;
          }
     if (isset($_GET['id'])){
            mysqli_query($koneksi, "DELETE FROM ta where TAID='".strfilter($_GET['id'])."'");
            echo "<script>document.location='index.php?ndelox=reports/angka.lulusan&act=detail&program=$_GET[program]&prodi=$_GET[prodi]&tahun=$_GET[tahun]&DosenID=$_GET[DosenID]';</script>";
          }
      ?>
     
        <tbody>
      </table>
    </div>
    </div>
</div>

<?php  
}
else if($_GET['act']=='graflulusan'){
$prodi=mysqli_fetch_array(mysqli_query($koneksi, "select ProdiID,Nama from prodi where ProdiID='".strfilter($_GET['prodi'])."'"));
?>
<script type="text/javascript" src="plugins/jQuery/jquery.min.js"></script>
<script type="text/javascript">
$(function () {
$('#container').highcharts({
data: {
    table: 'datatable'
},
chart: {
    type: 'column'
},
title: {
    text: ''
},
yAxis: {
    allowDecimals: false,
    title: {
        text: ''
    }
},
tooltip: {
    formatter: function () {
        return '<b> ' + this.series.name + '</b><br/>' +
            ' ' + this.point.y + ' Orang';
    }
}
});
});
</script>


<div class="box-body chat" id="chat-box">
<script src="plugins/highchart/highcharts.js"></script>
<script src="plugins/highchart/modules/data.js"></script>
<script src="plugins/highchart/modules/exporting.js"></script>
<h3 class="box-title"><b style=color:#FF8306;>Grafik Lulusan Program Studi <?php echo"$prd";?></b></h3>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<div class='card'>
    <div class='card-header'>
    <div class='table-responsive'>
    
<table id="datatable" style='display:none'>
<thead>
<tr>
<th></th>
<th>Reguler</th>
<th>Tranfer</th>
<th>Pindahan</th>
 <th>Total</th> 
</tr>
</thead>
<tbody>
<?php 
//group by harus sama dengan kriteria where kemudia ambil tahun format yyyy
$grafik = mysqli_query($koneksi, "SELECT * FROM t_tahunnormal GROUP BY Tahun order by Tahun ASC");//,TahunID ORDER BY TahunID desc limit 6
while ($r = mysqli_fetch_array($grafik)){
if ($_GET['prodi']=='SI'){
	$prodi="Sistem Informasi";	
	$reg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Reguler) as JumReg,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE ProdiID='".strfilter($_GET['prodi'])."' 
								  AND LEFT(TahunID,4)='$r[Tahun]' "));
	$tra = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Transfer) as JumTra,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE ProdiID='".strfilter($_GET['prodi'])."' 
								  AND LEFT(TahunID,4)='$r[Tahun]' "));
	$pin = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Pindahan) as JumPin,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE ProdiID='".strfilter($_GET['prodi'])."' 
								  AND LEFT(TahunID,4)='$r[Tahun]' "));
	
	//Keseluruhan ------------------------------------------------------------------------------------
	$regAll = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Reguler) as JumRegAll,ProdiID,TahunID 
									  FROM lulusan WHERE ProdiID='".strfilter($_GET['prodi'])."'"));
	$traAll = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Transfer) as JumTraAll,ProdiID,TahunID 
									  FROM lulusan WHERE ProdiID='".strfilter($_GET['prodi'])."'"));
	$pinAll = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Pindahan) as JumPinAll,ProdiID,TahunID 
									  FROM lulusan WHERE ProdiID='".strfilter($_GET['prodi'])."'"));	
	$grandTotAll = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Reguler+Transfer+Pindahan) as JumAll,ProdiID,TahunID 
									  FROM lulusan WHERE ProdiID='".strfilter($_GET['prodi'])."' AND LEFT(TahunID,4)='$r[Tahun]' "));								  							  							  
	}
else if ($_GET['prodi']=='TI'){
	$prodi="Teknik Informatika";
	$reg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Reguler) as JumReg,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE ProdiID='".strfilter($_GET['prodi'])."' 
								  AND LEFT(TahunID,4)='$r[Tahun]' "));
	$tra = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Transfer) as JumTra,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE ProdiID='".strfilter($_GET['prodi'])."' 
								  AND LEFT(TahunID,4)='$r[Tahun]' "));
	$pin = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Pindahan) as JumPin,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE ProdiID='".strfilter($_GET['prodi'])."' 
								  AND LEFT(TahunID,4)='$r[Tahun]' "));
							  								  							  				    
	//Keseluruhan ------------------------------------------------------------------------------------
	$regAll = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Reguler) as JumRegAll,ProdiID,TahunID 
									  FROM lulusan WHERE ProdiID='".strfilter($_GET['prodi'])."'"));
	$traAll = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Transfer) as JumTraAll,ProdiID,TahunID 
									  FROM lulusan WHERE ProdiID='".strfilter($_GET['prodi'])."'"));
	$pinAll = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Pindahan) as JumPinAll,ProdiID,TahunID 
									  FROM lulusan WHERE ProdiID='".strfilter($_GET['prodi'])."'"));	
	$grandTotAll = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Reguler+Transfer+Pindahan) as JumAll,ProdiID,TahunID 
									  FROM lulusan WHERE ProdiID='".strfilter($_GET['prodi'])."' AND LEFT(TahunID,4)='$r[Tahun]' "));
	
}//tutup TI
else if ($_GET['prodi']=='All'){
	$prodi="Semua Program Studi";
	$reg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Reguler) as JumReg,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE LEFT(TahunID,4)='$r[Tahun]' "));
	$tra = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Transfer) as JumTra,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE LEFT(TahunID,4)='$r[Tahun]' "));
	$pin = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Pindahan) as JumPin,ProdiID,TahunID 
								  FROM lulusan 
								  WHERE LEFT(TahunID,4)='$r[Tahun]' "));
	
							  								  							  				    
	//Keseluruhan ------------------------------------------------------------------------------------
	$regAll = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Reguler) as JumRegAll,ProdiID,TahunID 
									  FROM lulusan"));
	$traAll = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Transfer) as JumTraAll,ProdiID,TahunID 
									  FROM lulusan"));
	$pinAll = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Pindahan) as JumPinAll,ProdiID,TahunID 
									  FROM lulusan"));	
	
	$grandTotAll = mysqli_fetch_array(mysqli_query($koneksi, "SELECT sum(Reguler+Transfer+Pindahan) as JumAll,ProdiID,TahunID 
									  FROM lulusan WHERE LEFT(TahunID,4)='$r[Tahun]' "));
} //Tutup All
echo "<tr>
    <th>$r[Tahun]</th>
    <td>$reg[JumReg]</td>
    <td>$tra[JumTra]</td>
    <td>$pin[JumPin]</td>
    <td>$grandTotAll[JumAll]</td>
  </tr>";
}
?>
</tbody>
</table>
</div>
</div>
</div>

<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
<?php $pm = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM t_pengumuman"));	       
echo"<b style='color:green;'><marquee>$pm[Pengumuman]</marquee></b>";  
?>
</div>
</div>
</div>
<?php
}
?>