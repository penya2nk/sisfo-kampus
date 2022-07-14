  
<?php 
if (empty($_POST['tahun'])){
   $tahun =date('Y');
}else{
    $tahun =$_POST['tahun'];
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
            $recp = mysqli_query($koneksi, "SELECT * FROM t_tahunnormal  order by Tahun Desc limit 8"); 
            while ($k=mysqli_fetch_array($recp)){  
                if ($tahun==$k['Tahun']){
                    echo "<option value='$k[Tahun]' selected>$k[Tahun]</option>";
                }else{
                    echo "<option value='$k[Tahun]'>$k[Tahun]</option>";
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
$thn = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM pmbperiod where NA='N' Order by PMBPeriodID Desc limit 1"));
//group by harus sama dengan kriteria where kemudian ambil tahun format yy
$sqdata = mysqli_query($koneksi, "SELECT * FROM t_gelombang_detail WHERE left(PMBPeriodID,4)='".$tahun."' GROUP BY PMBPeriodID order by PMBPeriodID asc");	
while ($r = mysqli_fetch_array($sqdata)){
$IKM_A = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
									  FROM pmb 
									  WHERE Left(PMBPeriodID,4)='".$tahun."'
									  and ProdiID='IKM'
									  and PMBPeriodID='$r[PMBPeriodID]'
									  Group By pmb.ProdiID"));
									  
$IKM_B = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
									  FROM pmb 
									  WHERE Left(PMBPeriodID,4)='$tahun'
									  and ProdiID='IKM B'
									  and PMBPeriodID='$r[PMBPeriodID]'
									  Group By pmb.ProdiID"));


$PSIK = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
									  FROM pmb 
									  WHERE Left(PMBPeriodID,4)='$tahun'
									  and ProdiID='PSIK'
									  and PMBPeriodID='$r[PMBPeriodID]'
									  Group By pmb.ProdiID"));	
$PIKES = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
									  FROM pmb 
									  WHERE Left(PMBPeriodID,4)='$tahun'
									  and ProdiID='PIKES'
									  and PMBPeriodID='$r[PMBPeriodID]'
									  Group By pmb.ProdiID"));
$MKES = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
                                        FROM pmb 
                                        WHERE Left(PMBPeriodID,4)='$tahun'
                                        and ProdiID='MKES'
                                        and PMBPeriodID='$r[PMBPeriodID]'
                                        Group By pmb.ProdiID"));  
$NERS = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
                                        FROM pmb 
                                        WHERE Left(PMBPeriodID,4)='$tahun'
                                        and ProdiID='NERS'
                                        and PMBPeriodID='$r[PMBPeriodID]'
                                        Group By pmb.ProdiID")); 
$PBD = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
                                        FROM pmb 
                                        WHERE Left(PMBPeriodID,4)='$tahun'
                                        and ProdiID='PBD'
                                        and PMBPeriodID='$r[PMBPeriodID]'
                                        Group By pmb.ProdiID"));
$BID = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
                                        FROM pmb 
                                        WHERE Left(PMBPeriodID,4)='$tahun'
                                        and ProdiID='BID'
                                        and PMBPeriodID='$r[PMBPeriodID]'
                                        Group By pmb.ProdiID"));      
$SIBID = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
                                        FROM pmb 
                                        WHERE Left(PMBPeriodID,4)='$tahun'
                                        and ProdiID='SIBID'
                                        and PMBPeriodID='$r[PMBPeriodID]'
                                        Group By pmb.ProdiID")); 
$SI = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
                                        FROM pmb 
                                        WHERE Left(PMBPeriodID,4)='$tahun'
                                        and ProdiID='SI'
                                        and PMBPeriodID='$r[PMBPeriodID]'
                                        Group By pmb.ProdiID"));   
$TI = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
                                        FROM pmb 
                                        WHERE Left(PMBPeriodID,4)='$tahun'
                                        and ProdiID='TI'
                                        and PMBPeriodID='$r[PMBPeriodID]'
                                        Group By pmb.ProdiID"));
$HKM = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
                                        FROM pmb 
                                        WHERE Left(PMBPeriodID,4)='$tahun'
                                        and ProdiID='HKM'
                                        and PMBPeriodID='$r[PMBPeriodID]'
                                        Group By pmb.ProdiID"));  
$KMN = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID 
                                        FROM pmb 
                                        WHERE Left(PMBPeriodID,4)='$tahun'
                                        and ProdiID='KMN'
                                        and PMBPeriodID='$r[PMBPeriodID]'
                                        Group By pmb.ProdiID"));                          									  
$Total = $IKM_A['Jumlah'] + $IKM_B['Jumlah'] + $PSIK['Jumlah'] + $PIKES['Jumlah']+ $MKES['Jumlah']+ $NERS['Jumlah']+ $PBD['Jumlah']+ $BID['Jumlah'] + $SIBID['Jumlah']+ $SI['Jumlah']+ $TI['Jumlah']+ $HKM['Jumlah']+ $KMN['Jumlah'];									  
$grandtot           = $grandtot + $Total;
$grandIKM_A         = $grandIKM_A + $IKM_A['Jumlah'];
$grandIKM_B         = $grandIKM_B + $IKM_B['Jumlah'];
$grandPSIK          = $grandPSIK + $PSIK['Jumlah'];
$grandPIKES         = $grandPIKES + $PIKES['Jumlah'];
$grandMKES          = $grandMKES + $MKES['Jumlah'];
$grandNERS          = $grandNERS + $NERS['Jumlah'];
$grandPBD           = $grandPBD + $PBD['Jumlah'];
$grandBID           = $grandBID + $BID['Jumlah'];
$grandSIBID         = $grandSIBID + $SIBID['Jumlah'];
$grandSI            = $grandSI + $SI['Jumlah'];
$grandTI            = $grandTI + $TI['Jumlah'];
$grandHKM           = $grandHKM + $HKM['Jumlah'];
$grandKMN           = $grandKMN + $KMN['Jumlah'];
		
    //balok grafik
	echo "<tr>
	<th style='text-align:center'>$r[PMBPeriodID]</th>
    <th style='text-align:right'>".number_format($IKM_A['Jumlah'],0)."</th>
    <th style='text-align:right'>".number_format($IKM_B['Jumlah'],0)."</th>	
	<th style='text-align:right'>".number_format($PSIK['Jumlah'],0)."</th>
	<th style='text-align:right'>".number_format($PIKES['Jumlah'],0)."</th>
    <th style='text-align:right'>".number_format($MKES['Jumlah'],0)."</th>	
	<th style='text-align:right'>".number_format($NERS['Jumlah'],0)."</th>
	<th style='text-align:right'>".number_format($PBD['Jumlah'],0)."</th>
    <th style='text-align:right'>".number_format($BID['Jumlah'],0)."</th>
    <th style='text-align:right'>".number_format($SIBID['Jumlah'],0)."</th>
    <th style='text-align:right'>".number_format($SI['Jumlah'],0)."</th>	
	<th style='text-align:right'>".number_format($TI['Jumlah'],0)."</th>
	<th style='text-align:right'>".number_format($HKM['Jumlah'],0)."</th>
    <th style='text-align:right'>".number_format($KMN['Jumlah'],0)."</th>
	<th style='text-align:right'>".number_format($Total,0)."</th>
	</tr>";
	}
	echo"<tr style='background:#e3abe2;color:white;font-weight:bold'>
	<td></td>
	<td style='text-align:right'><b>".number_format($grandIKM_A,0)."</b></td>
	<td style='text-align:right'><b>".number_format($grandIKM_B,0)."</b></td>
	<td style='text-align:right'><b>".number_format($grandPSIK,0)."</b></td>
	<td style='text-align:right'><b>".number_format($grandPIKES,0)."</b></td>
    <td style='text-align:right'><b>".number_format($grandMKES,0)."</b></td>
    <td style='text-align:right'><b>".number_format($grandNERS,0)."</b></td>
    <td style='text-align:right'><b>".number_format($grandPBD,0)."</b></td>
    <td style='text-align:right'><b>".number_format($grandBID,0)."</b></td>
    <td style='text-align:right'><b>".number_format($grandSIBID,0)."</b></td>

    <td style='text-align:right'><b>".number_format($grandSI,0)."</b></td>
    <td style='text-align:right'><b>".number_format($grandTI,0)."</b></td>
    <td style='text-align:right'><b>".number_format($grandHKM,0)."</b></td>
    <td style='text-align:right'><b>".number_format($grandKMN,0)."</b></td>
	<td style='text-align:right'><b>".number_format($grandtot,0)."</b></td>
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
$nomr=0; $total=0;
$sqx = mysqli_query($koneksi, "SELECT * FROM prodi GROUP BY Nama order by Nama ASC");
while($r=mysqli_fetch_array($sqx)){
$nomr++;
$jml = mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID  
                        FROM pmb 
                        WHERE ProdiID='$r[ProdiID]'
                        AND Left(PMBPeriodID,4)='$tahun'"));
	 	
$total += $jml['Jumlah'];
echo "<tr>
<td>$nomr</td>            
<td><a href='/admin/pmb/pmbinfoall/$tahun'>$r[Nama]</a></td>
<td style='text-align:right'>$jml[Jumlah] </td>
<td style='text-align:left'><a href='admin/pmb/pmbinfoall/$tahun'>Detail PMB</a> | Grafik PMB
</td>
</tr>";
}
?>
<tbody>
<tr style='background:purple;color:white;font-weight:bold'>
  <th style='width:10px'></th>           
  <th style='width:20px'>Total</th>
  <th style='width:110px;text-align:right'><?php echo"$total Orang";?></th>  
  <th style='width:10px;text-align:left'><a href="/admin/laporanakademik/pmbrekap">Rekap PMB All Prodi</a></th>              
</tr>

</tbody>
</table>
</div>

</div>
</div>




