  
<?php 
if (empty($_GET['tahun'])){
   $tahun =date('Y');
}else{
    $tahun =$_GET['tahun'];
}

?>
<div class="card">
<div class="card-header">


<div class="form-group row">
<label class="col-md-8 col-form-label text-md-right"><b style='color:purple'>MONITOR PMB (BELUM REGISTRASI) 
| <a href="?ndelox=reports/monitorpmb">Monitor </a>
| <a href="?ndelox=reports/pmbangka">Evaluasi </a>|</b></label>
<div class="col-md-2">
<form action="" method="GET" accept-charset="utf-8" enctype="multipart/form-data">
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

<div class="col-md-1">
</div>
              
<script type="text/javascript" src="<?php //echo base_url(); ?>asset/admin/plugins/jQuery/jquery.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('#container').highcharts({
            data: {
                table: 'datatable'
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
<div id="container" style="min-width: 310px; height: 205px; margin: 0 auto"></div>
<table id="datatable" style='display:none'>
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
$sqdata = mysqli_query($koneksi, "SELECT * FROM t_gelombang_detail where left(PMBPeriodID,4)='$tahun' GROUP BY PMBPeriodID order by PMBPeriodID asc");	
while($r=mysqli_fetch_array($sqdata)){
$IKM_A = mysqli_num_rows(mysqli_query($koneksi, "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='IKM'
                            AND Left(PMBPeriodID,4)='$tahun'	
                            AND PMBPeriodID='$r[PMBPeriodID]'"));									  
$IKM_B = mysqli_num_rows(mysqli_query($koneksi, "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='IKM B'
                            AND Left(PMBPeriodID,4)='$tahun'	
                            AND PMBPeriodID='$r[PMBPeriodID]'"));

$PSIK = mysqli_num_rows(mysqli_query($koneksi, "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='PSIK'
                            AND Left(PMBPeriodID,4)='$tahun'	
                            AND PMBPeriodID='$r[PMBPeriodID]'"));	
$PIKES = mysqli_num_rows(mysqli_query($koneksi, "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='PIKES'
                            AND Left(PMBPeriodID,4)='$tahun'	
                            AND PMBPeriodID='$r[PMBPeriodID]'"));	
$MKES = mysqli_num_rows(mysqli_query($koneksi, "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='MKES'
                            AND Left(PMBPeriodID,4)='$tahun'	
                            AND PMBPeriodID='$r[PMBPeriodID]'"));	
$NERS = mysqli_num_rows(mysqli_query($koneksi, "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='NERS'
                            AND Left(PMBPeriodID,4)='$tahun'	
                            AND PMBPeriodID='$r[PMBPeriodID]'"));	 
$PBD = mysqli_num_rows(mysqli_query($koneksi, "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='PBD'
                            AND Left(PMBPeriodID,4)='$tahun'	
                            AND PMBPeriodID='$r[PMBPeriodID]'"));	
$BID = mysqli_num_rows(mysqli_query($koneksi, "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='BID'
                            AND Left(PMBPeriodID,4)='$tahun'	
                            AND PMBPeriodID='$r[PMBPeriodID]'")); 
$SIBID = mysqli_num_rows(mysqli_query($koneksi, "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='SIBID'
                            AND Left(PMBPeriodID,4)='$tahun'	
                            AND PMBPeriodID='$r[PMBPeriodID]'")); 

$SI = mysqli_num_rows(mysqli_query($koneksi, "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='SI'
                            AND Left(PMBPeriodID,4)='$tahun'	
                            AND PMBPeriodID='$r[PMBPeriodID]'")); 
$TI = mysqli_num_rows(mysqli_query($koneksi, "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='TI'
                            AND Left(PMBPeriodID,4)='$tahun'	
                            AND PMBPeriodID='$r[PMBPeriodID]'"));  
$HKM = mysqli_num_rows(mysqli_query($koneksi, "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='HKM'
                            AND Left(PMBPeriodID,4)='$tahun'	
                            AND PMBPeriodID='$r[PMBPeriodID]'"));  
$KMN = mysqli_num_rows(mysqli_query($koneksi, "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='KMN'
                            AND Left(PMBPeriodID,4)='$tahun'	
                            AND PMBPeriodID='$r[PMBPeriodID]'"));                                                                                                               							  
$Total = $IKM_A + $IKM_B + $PSIK + $PIKES + $MKES + $NERS + $PBD + $SIBID;
echo "<tr>
<th>$r[PMBPeriodID]</th>
<th>$IKM_A</th>
<th>$IKM_B</th>	
<th>$PSIK</th>
<th>$PIKES</th>
<th>$MKES</th>
<th>$NERS</th>
<th>$PBD</th>
<th>$BID</th>
<th>$SIBID</th>
<th>$SI</th>
<th>$TI</th>
<th>$HKM</th>
<th>$KMN</th>
<th>$Total</th>";
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
$nomr=0; $total=0;
$thn = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM pmbperiod where NA='N' Order by PMBPeriodID Desc limit 1"));
$sqx = mysqli_query($koneksi, "SELECT * FROM prodi GROUP BY Nama order by Nama ASC");
while($r=mysqli_fetch_array($sqx)){
$nomr++;
$jml = mysqli_num_rows(mysqli_query($koneksi, "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                        FROM pmb 
                        WHERE ProdiID='$r[ProdiID]'
                        AND Left(PMBPeriodID,4)='$tahun'"));
	 	
$total += $jml; 
echo "<tr>
<td>$nomr</td>            
<td><a href='?ndelox=penmaba/penmabamhs&tahun=$thn[PMBPeriodID]&prodi=$r[ProdiID]'>$r[Nama]</a></td>
<td style='text-align:right'>$jml </td>
<td style='text-align:left'><a href='?ndelox=reports/monitorpmb&tahun=$tahun&prodi=$r[ProdiID]'>Detail PMB</a> | Grafik PMB
</td>
</tr>";
}
?>
<tbody>
<tr style='background:purple;color:white;font-weight:bold'>
  <th style='width:10px'></th>           
  <th style='width:20px'>Total</th>
  <th style='width:110px;text-align:right'><?php echo"$total Orang";?></th>  
  <th style='width:10px;text-align:left'><a href="?ndelox=reports/pmbangka&tahun=$tahun&prodi=$r[ProdiID]">Rekap PMB All Prodi</a></th>              
</tr>

</tbody>
</table>



</div>
</div>
</div>