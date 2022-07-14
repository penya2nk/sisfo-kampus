<?php 
if (empty($_POST['tahun'])){
   $tahunx =date('Y');
}else{
    $tahunx =$_POST['tahun'];
}

?>
<div class="card">
<div class="card-header">
<div class="card-tools">

</div>

<div class="form-group row">
<label class="col-md-8 col-form-label text-md-right"><b style='color:purple'>MONITOR PMB (SUDAH REGISTRASI)</b></label>
<div class="col-md-2">
<form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data">
<?php 

echo "<select name='tahun' class='form-control form-control-sm' style='padding:4px' onChange='this.form.submit()'>";
        $recpx = mysqli_query($koneksi, "select * FROM t_tahunnormal  order by Tahun Desc limit 8"); 
        while ($k=mysqli_fetch_array($recpx)){  
            if ($tahunx==$k['Tahun']){
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

<div class="col-md-1">
</div>
              
<script type="text/javascript" src="asset/admin/plugins/jQuery/jquery.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('#containerx').highcharts({
            data: {
                table: 'datatablex'
            },
            chart: {
                type: 'line'
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
                    return '<b>' + this.series.name + '</b><br/>' +
                        'Ada ' + this.point.y + ' Orang';
                }
            }
        });
    });
</script>
</div>

<div class="card-body chat" id="chat-card">
<div id="containerx" style="min-width: 310px; height: 205px; margin: 0 auto"></div>
<table id="datatablex" style='display:none'>
<thead>
<tr>
<th></th>
<th>IKM A</th>
<th>IKM B</th>
<th>PSIK</th>
<th>PIKES</th>	
<th>MKES</th>	
<th>NERS</th>	
<th>PBD</th>	
<th>BID</th>	
<th>SIBID</th>	
<th>SI</th>	
<th>TI</th>	
<th>HKM</th>
<th>KMN</th>		
<th>Total</th>
</tr>
</thead>
<tbody>
<?php 
//group by harus sama dengan kriteria where kemudian ambil tahun format yy
$sqdatax = mysqli_query($koneksi, "select * FROM t_gelombang_detail where left(PMBPeriodID,4)='$tahunx' GROUP BY PMBPeriodID order by PMBPeriodID asc");	
while($rx=mysqli_fetch_array($sqdatax)){
    $IKM_Ax = mysqli_num_rows(mysqli_query($koneksi, "select PMBID,ProdiID,PMBPeriodID,RegUlang 
    FROM pmb 
    WHERE ProdiID='IKM'
    AND RegUlang='Y'
    AND Left(PMBPeriodID,4)='$tahunx'	
    AND PMBPeriodID='$rx[PMBPeriodID]'"));									  
$IKM_Bx = mysqli_num_rows(mysqli_query($koneksi, "select PMBID,ProdiID,PMBPeriodID,RegUlang 
    FROM pmb 
    WHERE ProdiID='IKM B'
    AND RegUlang='Y'
    AND Left(PMBPeriodID,4)='$tahunx'	
    AND PMBPeriodID='$rx[PMBPeriodID]'"));

$PSIKx = mysqli_num_rows(mysqli_query($koneksi, "select PMBID,ProdiID,PMBPeriodID,RegUlang 
    FROM pmb 
    WHERE ProdiID='PSIK'
    AND RegUlang='Y'
    AND Left(PMBPeriodID,4)='$tahunx'	
    AND PMBPeriodID='$rx[PMBPeriodID]'"));	
$PIKESx = mysqli_num_rows(mysqli_query($koneksi, "select PMBID,ProdiID,PMBPeriodID,RegUlang 
    FROM pmb 
    WHERE ProdiID='PIKES'
    AND RegUlang='Y'
    AND Left(PMBPeriodID,4)='$tahunx'	
    AND PMBPeriodID='$rx[PMBPeriodID]'"));	
$MKESx = mysqli_num_rows(mysqli_query($koneksi, "select PMBID,ProdiID,PMBPeriodID,RegUlang 
    FROM pmb 
    WHERE ProdiID='MKES'
    AND RegUlang='Y'
    AND Left(PMBPeriodID,4)='$tahunx'	
    AND PMBPeriodID='$rx[PMBPeriodID]'"));	
$NERSx = mysqli_num_rows(mysqli_query($koneksi, "select PMBID,ProdiID,PMBPeriodID,RegUlang 
    FROM pmb 
    WHERE ProdiID='NERS'
    AND RegUlang='Y'
    AND Left(PMBPeriodID,4)='$tahunx'	
    AND PMBPeriodID='$rx[PMBPeriodID]'"));	 
$PBDx = mysqli_num_rows(mysqli_query($koneksi, "select PMBID,ProdiID,PMBPeriodID,RegUlang 
    FROM pmb 
    WHERE ProdiID='PBD'
    AND RegUlang='Y'
    AND Left(PMBPeriodID,4)='$tahunx'	
    AND PMBPeriodID='$rx[PMBPeriodID]'"));
    
$BIDx = mysqli_num_rows(mysqli_query($koneksi, "select PMBID,ProdiID,PMBPeriodID,RegUlang 
    FROM pmb 
    WHERE ProdiID='BID'
    AND RegUlang='Y'	
    AND Left(PMBPeriodID,4)='$tahunx'	
    AND PMBPeriodID='$rx[PMBPeriodID]'"));  

$SIBIDx = mysqli_num_rows(mysqli_query($koneksi, "select PMBID,ProdiID,PMBPeriodID,RegUlang 
    FROM pmb 
    WHERE ProdiID='SIBID'
    AND RegUlang='Y'	
    AND Left(PMBPeriodID,4)='$tahunx'	
    AND PMBPeriodID='$rx[PMBPeriodID]'"));   
    
$SIx = mysqli_num_rows(mysqli_query($koneksi, "select PMBID,ProdiID,PMBPeriodID,RegUlang 
    FROM pmb 
    WHERE ProdiID='SI'
    AND RegUlang='Y'
    AND Left(PMBPeriodID,4)='$tahunx'	
    AND PMBPeriodID='$rx[PMBPeriodID]'"));	
$TIx = mysqli_num_rows(mysqli_query($koneksi, "select PMBID,ProdiID,PMBPeriodID,RegUlang 
    FROM pmb 
    WHERE ProdiID='TI'
    AND RegUlang='Y'
    AND Left(PMBPeriodID,4)='$tahunx'	
    AND PMBPeriodID='$rx[PMBPeriodID]'"));	 
$HKMx = mysqli_num_rows(mysqli_query($koneksi, "select PMBID,ProdiID,PMBPeriodID,RegUlang 
    FROM pmb 
    WHERE ProdiID='HKM'
    AND RegUlang='Y'
    AND Left(PMBPeriodID,4)='$tahunx'	
    AND PMBPeriodID='$rx[PMBPeriodID]'"));	
$KMNx = mysqli_num_rows(mysqli_query($koneksi, "select PMBID,ProdiID,PMBPeriodID,RegUlang 
    FROM pmb 
    WHERE ProdiID='KMN'
    AND RegUlang='Y'	
    AND Left(PMBPeriodID,4)='$tahunx'	
    AND PMBPeriodID='$rx[PMBPeriodID]'"));  
$Totalx = $IKM_Ax + $IKM_Bx + $PSIKx + $PIKESx + $MKESx + $NERSx + $PBDx + $BIDx + $SIBIDx+ $SIx + $TIx + $HKMx + $KMNx;
echo "<tr>
<th>$rx[PMBPeriodID]</th>
<th>$IKM_Ax</th>
<th>$IKM_Bx</th>	
<th>$PSIKx</th>
<th>$PIKESx</th>
<th>$MKESx</th>
<th>$NERSx</th>
<th>$PBDx</th>
<th>$BIDx</th>
<th>$SIBIDx</th>
<th>$SIx</th>
<th>$TIx</th>
<th>$HKMx</th>
<th>$KMNx</th>
<th>$Totalx</th>";
}
?>
</tbody>
</table>
<br>
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
$thn = mysqli_fetch_array(mysqli_query($koneksi, "select * FROM pmbperiod where NA='N' Order by PMBPeriodID Desc limit 1"));
$sqxx = mysqli_query($koneksi, "select * FROM prodi GROUP BY Nama order by Nama ASC");
while($rx=mysqli_fetch_array($sqxx)){
$nomrx++;
$jmlx = mysqli_num_rows(mysqli_query($koneksi, "select PMBID,ProdiID,PMBPeriodID,RegUlang 
                        FROM pmb 
                        WHERE ProdiID='$rx[ProdiID]'
                        AND RegUlang='Y'
                        AND Left(PMBPeriodID,4)='$tahunx'									 									  
                        ")); //Group By pmb.ProdiID
	 	
$totalx += $jmlx;
echo "<tr>
<td>$nomrx</td>            
<td><a href='?ndelox=penmaba/pmbmhs&tahun=$thn[PMBPeriodID]&prodi=$r[ProdiID]'>$rx[Nama]</a></td>
<td style='text-align:right'>$jmlx</td>
<td style='text-align:left'><a href='?ndelox=reports/monitorpmb&tahun=$tahun&prodi=$r[ProdiID]'>Detail PMB</a> | Grafik PMB
</td>
</tr>";
}
?>
<tbody>
<tr style='background:purple;color:white;font-weight:bold'>
  <th style='width:10px'></th>           
  <th style='width:20px'>Total</th>
  <th style='width:110px;text-align:right'><?php echo"$totalx Orang";?></th>  
  <th style='width:10px;text-align:left'><a href="?ndelox=reports/pmbangka&tahun=$tahun&prodi=$r[ProdiID]">Rekap PMB All Prodi</a></th>              
</tr>

</tbody>
</table>



</div>
</div>
</div>