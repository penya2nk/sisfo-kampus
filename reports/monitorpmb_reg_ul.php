  
<?php 
if (empty($_POST['tahun'])){
   $tahunx =date('Y');
}else{
    $tahunx =$_POST['tahun'];
}

?>
<div class="card">
<div class="card-header">


    <div class="form-group row">
            <label class="col-md-8 col-form-label text-md-right"><b style='color:purple'>MONITOR PMB (BELUM REGISTRASI) 
            | <a href="?ndelox=reports/monitorpmb">Monitor </a>
            | <a href="?ndelox=reports/pmbangka">Evaluasi </a>|</b></label>
            <div class="col-md-2">
            <form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data">
            <?php 
            //echo csrf_field();                        
            //$this->db   = \Config\Database::connect();
            echo "<select name='tahun' class='form-control form-control-sm' style='padding:4px' onChange='this.form.submit()'>";
                    $recpx = mysqli_query($koneksi, "SELECT * FROM t_tahunnormal  order by Tahun Desc limit 8"); 
                    while ($kx=mysqli_fetch_array($recpx)){  
                        if ($tahunx==$kx['Tahun']){
                            echo "<option value='$kx[Tahun]' selected>$kx[Tahun]</option>";
                        }else{
                            echo "<option value='$kx[Tahun]'>$kx[Tahun]</option>";
                        }
                    }
                    echo"</select>";
                    ?>                          
            </div>
                                
            <div class="col-md-1">
            <?php
                echo"<input class='pull-right btn btn-primary btn-sm' type='submit' value='Lihat'>";
                echo"</form>";
            ?>
            </div>
    </div>

<br>
<div class='table-responsive'>
<table id="example" class="table table-sm table-striped">
<thead>
<tr style='background:purple;color:white;font-weight:bold'>
<th style='text-align:center'>GEL</th>
    <th style='text-align:right'>IKM A</th>
    <th style='text-align:right'>IKM B</th>
	<th style='text-align:right'>PSIK</th>
    <th style='text-align:right'>RMIK</th>	
    <th style='text-align:right'>MKES</th>	
    <th style='text-align:right'>NERS</th>	
    <th style='text-align:right'>PBD</th>
    <th style='text-align:right'>BID</th>	
    <th style='text-align:right'>SIBID</th>	
    <th style='text-align:right'>&nbsp;SI&nbsp;</th>	
    <th style='text-align:right'>&nbsp;TI&nbsp;</th>	
    <th style='text-align:right'>HKM</th>	
    <th style='text-align:right'>KMN</th>	
	<th style='text-align:right;'>Total</th>             
</tr>
</thead>
<tbody>
<?php 
$thnx = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM pmbperiod where NA='N' Order by PMBPeriodID Desc limit 1"));
//group by harus sama dengan kriteria where kemudian ambil tahun format yy
$sqdatax = mysqli_query($koneksi, "SELECT * FROM t_gelombang_detail WHERE left(PMBPeriodID,4)='".$tahunx."' GROUP BY PMBPeriodID order by PMBPeriodID asc");	
while ($rx = mysqli_fetch_array($sqdatax)){
$IKM_Ax = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
									  FROM pmb 
									  WHERE Left(PMBPeriodID,4)='".$tahunx."'
									  and ProdiID='IKM'
                                      and RegUlang='Y'
									  and PMBPeriodID='$rx[PMBPeriodID]'
									  Group By pmb.ProdiID"));
									  
$IKM_Bx = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
									  FROM pmb 
									  WHERE Left(PMBPeriodID,4)='$tahunx'
									  and ProdiID='IKM B'
                                      and RegUlang='Y'
									  and PMBPeriodID='$rx[PMBPeriodID]'
									  Group By pmb.ProdiID"));


$PSIKx = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
									  FROM pmb 
									  WHERE Left(PMBPeriodID,4)='$tahunx'
									  and ProdiID='PSIK'
                                      and RegUlang='Y'
									  and PMBPeriodID='$rx[PMBPeriodID]'
									  Group By pmb.ProdiID"));	
$PIKES = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
									  FROM pmb 
									  WHERE Left(PMBPeriodID,4)='$tahunx'
									  and ProdiID='PIKES'
                                      and RegUlang='Y'
									  and PMBPeriodID='$rx[PMBPeriodID]'
									  Group By pmb.ProdiID"));
$MKESx = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
                                        FROM pmb 
                                        WHERE Left(PMBPeriodID,4)='$tahunx'
                                        and ProdiID='MKES'
                                        and RegUlang='Y'
                                        and PMBPeriodID='$rx[PMBPeriodID]'
                                        Group By pmb.ProdiID"));  
$NERSx = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
                                        FROM pmb 
                                        WHERE Left(PMBPeriodID,4)='$tahunx'
                                        and ProdiID='NERS'
                                        and RegUlang='Y'
                                        and PMBPeriodID='$rx[PMBPeriodID]'
                                        Group By pmb.ProdiID")); 
$PBDx = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
                                        FROM pmb 
                                        WHERE Left(PMBPeriodID,4)='$tahunx'
                                        and ProdiID='PBD'
                                        and RegUlang='Y'
                                        and PMBPeriodID='$rx[PMBPeriodID]'
                                        Group By pmb.ProdiID"));
$BID = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
                                        FROM pmb 
                                        WHERE Left(PMBPeriodID,4)='$tahunx'
                                        and ProdiID='BID'
                                        and RegUlang='Y'
                                        and PMBPeriodID='$r[PMBPeriodID]'
                                        Group By pmb.ProdiID"));      
$SIBIDx = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
                                        FROM pmb 
                                        WHERE Left(PMBPeriodID,4)='$tahunx'
                                        and ProdiID='SIBID'
                                        and RegUlang='Y'
                                        and PMBPeriodID='$rx[PMBPeriodID]'
                                        Group By pmb.ProdiID")); 
$SIx = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
                                        FROM pmb 
                                        WHERE Left(PMBPeriodID,4)='$tahunx'
                                        and ProdiID='SI'
                                        and RegUlang='Y'
                                        and PMBPeriodID='$rx[PMBPeriodID]'
                                        Group By pmb.ProdiID"));   
$TIx = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
                                        FROM pmb 
                                        WHERE Left(PMBPeriodID,4)='$tahunx'
                                        and ProdiID='TI'
                                        and RegUlang='Y'
                                        and PMBPeriodID='$rx[PMBPeriodID]'
                                        Group By pmb.ProdiID"));
$HKMx = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
                                        FROM pmb 
                                        WHERE Left(PMBPeriodID,4)='$tahunx'
                                        and ProdiID='HKM'
                                        and RegUlang='Y'
                                        and PMBPeriodID='$rx[PMBPeriodID]'
                                        Group By pmb.ProdiID"));  
$KMNx = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
                                        FROM pmb 
                                        WHERE Left(PMBPeriodID,4)='$tahunx'
                                        and ProdiID='KMN'
                                        and RegUlang='Y'
                                        and PMBPeriodID='$rx[PMBPeriodID]'
                                        Group By pmb.ProdiID"));                          									  
$Totalx = $IKM_Ax['Jumlah'] + $IKM_Bx['Jumlah'] + $PSIKx['Jumlah'] + $PIKESx['Jumlah']+ $MKESx['Jumlah']+ $NERSx['Jumlah']+ $PBDx['Jumlah']+ $BIDx['Jumlah'] + $SIBIDx['Jumlah']+ $SIx['Jumlah']+ $TIx['Jumlah']+ $HKMx['Jumlah']+ $KMNx['Jumlah'];									  
$grandtotx           = $grandtotx + $Totalx;
$grandIKM_Ax        = $grandIKM_Ax + $IKM_Ax['Jumlah'];
$grandIKM_Bx        = $grandIKM_Bx + $IKM_Bx['Jumlah'];
$grandPSIKx          = $grandPSIx + $PSIKx['Jumlah'];
$grandPIKESx         = $grandPIKESx + $PIKESx['Jumlah'];
$grandMKESx          = $grandMKESx + $MKESx['Jumlah'];
$grandNERSx          = $grandNERSx + $NERSx['Jumlah'];
$grandPBDx           = $grandPBDx + $PBDx['Jumlah'];
$grandBIDx           = $grandBIDx + $BIDx['Jumlah'];
$grandSIBIDx         = $grandSIBIDx + $SIBIDx['Jumlah'];
$grandSIx            = $grandSIx + $SIx['Jumlah'];
$grandTIx            = $grandTIx + $TIx['Jumlah'];
$grandHKMx           = $grandHKMx + $HKMx['Jumlah'];
$grandKMNx           = $grandKMNx + $KMNx['Jumlah'];
		
    //balok grafik
	echo "<tr>
	<th style='text-align:center'>$rx[PMBPeriodID]</th>
    <th style='text-align:right'>".number_format($IKM_Ax['Jumlah'],0)."</th>
    <th style='text-align:right'>".number_format($IKM_Bx['Jumlah'],0)."</th>	
	<th style='text-align:right'>".number_format($PSIKx['Jumlah'],0)."</th>
	<th style='text-align:right'>".number_format($PIKESx['Jumlah'],0)."</th>
    <th style='text-align:right'>".number_format($MKESx['Jumlah'],0)."</th>	
	<th style='text-align:right'>".number_format($NERSx['Jumlah'],0)."</th>
	<th style='text-align:right'>".number_format($PBDx['Jumlah'],0)."</th>
    <th style='text-align:right'>".number_format($BIDx['Jumlah'],0)."</th>
    <th style='text-align:right'>".number_format($SIBIDx['Jumlah'],0)."</th>
    <th style='text-align:right'>".number_format($SIx['Jumlah'],0)."</th>	
	<th style='text-align:right'>".number_format($TIx['Jumlah'],0)."</th>
	<th style='text-align:right'>".number_format($HKMx['Jumlah'],0)."</th>
    <th style='text-align:right'>".number_format($KMNx['Jumlah'],0)."</th>
	<th style='text-align:right'>".number_format($Totalx,0)."</th>
	</tr>";
	}
	echo"<tr style='background:#e3abe2;color:white;font-weight:bold'>
	<td></td>
	<td style='text-align:right'><b>".number_format($grandIKM_Ax,0)."</b></td>
	<td style='text-align:right'><b>".number_format($grandIKM_Bx,0)."</b></td>
	<td style='text-align:right'><b>".number_format($grandPSIKx,0)."</b></td>
	<td style='text-align:right'><b>".number_format($grandPIKESx,0)."</b></td>
    <td style='text-align:right'><b>".number_format($grandMKESx,0)."</b></td>
    <td style='text-align:right'><b>".number_format($grandNERSx,0)."</b></td>
    <td style='text-align:right'><b>".number_format($grandPBDx,0)."</b></td>
    <td style='text-align:right'><b>".number_format($grandBIDx,0)."</b></td>
    <td style='text-align:right'><b>".number_format($grandSIBIDx,0)."</b></td>

    <td style='text-align:right'><b>".number_format($grandSIx,0)."</b></td>
    <td style='text-align:right'><b>".number_format($grandTIx,0)."</b></td>
    <td style='text-align:right'><b>".number_format($grandHKMx,0)."</b></td>
    <td style='text-align:right'><b>".number_format($grandKMNx,0)."</b></td>
	<td style='text-align:right'><b>".number_format($grandtotx,0)."</b></td>
	</tr>";
	?>
<tbody>
</tbody>
</table>
</div>

<br>   
<div class='table-responsive'>
<table id="example" class="table table-sm table-striped">
<thead>
<tr style='background:purple;color:white;font-weight:bold'>
  <th style='width:10px'>No</th>              
  <th style='width:150px'>Program Studi</th>
  <th style='width:80px;text-align:right'>Jumlah</th>
  <th style='width:150px;text-align:left'>Info</th>              
</tr>
</thead>
<tbody>
<?php
$nomrx=0; $totalx=0;
$sqxx = mysqli_query($koneksi, "SELECT * FROM prodi GROUP BY Nama order by Nama ASC");
while($rx=mysqli_fetch_array($sqxx)){
$nomrx++;
$jmlx = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID  
                        FROM pmb 
                        WHERE ProdiID='$rx[ProdiID]'
                        and RegUlang='Y'
                        AND Left(PMBPeriodID,4)='$tahunx'"));
	 	
$totalx += $jmlx['Jumlah'];
echo "<tr>
<td>$nomrx</td>            
<td><a href='/admin/pmb/pmbinfoall/$tahunx'>$rx[Nama]</a></td>
<td style='text-align:right'>$jmlx[Jumlah] </td>
<td style='text-align:left'><a href='admin/pmb/pmbinfoall/$tahunx'>Detail PMB</a> | Grafik PMB
</td>
</tr>";
}
?>
<tbody>
<tr style='background:purple;color:white;font-weight:bold'>
  <th style='width:10px'></th>           
  <th style='width:20px'>Total</th>
  <th style='width:110px;text-align:right'><?php echo"$totalx Orang";?></th>  
  <th style='width:10px;text-align:left'><a href="/admin/laporanakademik/pmbrekap">Rekap PMB All Prodi</a></th>              
</tr>

</tbody>
</table>
</div>


</div>
</div>
