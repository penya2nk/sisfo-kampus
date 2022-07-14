
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


<div class='card'>
<div class='card-header'>

<div class="box-header">
<h3 class="box-title"><b style=color:green;>Grafik PMB <b style=color:#FF8306;><?php echo" Seluruh Program Studi";?> dalam Beberapa Tahun</b>
<?php echo"<a href='?index.php&ndelox=penmaba/admpmbinfoall&tahun=".date('Y')."'> <font style=font-size:12px>[Selengkapnya]</font> </a>"?>
</b></h3>
<p></p>
</div>

<div class="box-body chat" id="chat-box">
<script src="plugins/highchart/highcharts.js"></script>
<script src="plugins/highchart/modules/data.js"></script>
<script src="plugins/highchart/modules/exporting.js"></script>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>


<div class='table-responsive'>
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
$sqdata = mysqli_query($koneksi,  "SELECT * FROM t_tahunnormal GROUP BY Tahun order by Tahun asc");	
while ($r = mysqli_fetch_array($sqdata)){
$IKM_A = mysqli_num_rows(mysqli_query($koneksi,  "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='IKM'
                            AND Left(PMBPeriodID,4)='$r[Tahun]'"));									  
$IKM_B = mysqli_num_rows(mysqli_query($koneksi,  "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='IKM B'
                            AND Left(PMBPeriodID,4)='$r[Tahun]'"));		

$PSIK = mysqli_num_rows(mysqli_query($koneksi,  "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='PSIK'
                            AND Left(PMBPeriodID,4)='$r[Tahun]'"));		
$PIKES = mysqli_num_rows(mysqli_query($koneksi,  "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='PIKES'
                           AND Left(PMBPeriodID,4)='$r[Tahun]'"));		
$MKES = mysqli_num_rows(mysqli_query($koneksi,  "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='MKES'
                            AND Left(PMBPeriodID,4)='$r[Tahun]'"));			
$NERS = mysqli_num_rows(mysqli_query($koneksi,  "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='NERS'
                            AND Left(PMBPeriodID,4)='$r[Tahun]'"));		
$PBD = mysqli_num_rows(mysqli_query($koneksi,  "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='PBD'
                            AND Left(PMBPeriodID,4)='$r[Tahun]'"));		
$BID = mysqli_num_rows(mysqli_query($koneksi,  "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='BID'
                           AND Left(PMBPeriodID,4)='$r[Tahun]'"));		
$SIBID = mysqli_num_rows(mysqli_query($koneksi,  "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='SIBID'
                            AND Left(PMBPeriodID,4)='$r[Tahun]'"));		
$SIBID = mysqli_num_rows(mysqli_query($koneksi,  "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='SIBID'
                            AND Left(PMBPeriodID,4)='$r[Tahun]'"));		
$SI = mysqli_num_rows(mysqli_query($koneksi,  "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='SI'
							AND Left(PMBPeriodID,4)='$r[Tahun]'"));		
$TI = mysqli_num_rows(mysqli_query($koneksi,  "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='TI'
							AND Left(PMBPeriodID,4)='$r[Tahun]'"));	  
$HKM = mysqli_num_rows(mysqli_query($koneksi,  "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='HKM'
							AND Left(PMBPeriodID,4)='$r[Tahun]'"));	
$KMN = mysqli_num_rows(mysqli_query($koneksi,  "SELECT PMBID,ProdiID,PMBPeriodID,RegUlang 
                            FROM pmb 
                            WHERE ProdiID='KMN'
							AND Left(PMBPeriodID,4)='$r[Tahun]'"));
							
$Total = $IKM_A + $IKM_B + $PSIK + $PIKES + $MKES + $NERS + $PBD + $SIBID;								  
			
    //balok grafik
	echo "<tr>
			<th>$r[Tahun]</th>
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
			<th>$Total</th>
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
<table id='example' class='table table-sm table-striped'>
<thead>
<tr style='background:purple;color:white;font-weight:bold'>
  <th style='width:10px;text-align:center'>No</th>              
  <th style='width:150px;text-align:center'>Tahun Penerimaan Mahasiswa Baru</th>
  <th style='width:80px;text-align:right'>Jumlah</th>
  <th style='width:150px;text-align:right'>Selengkapnya</th>              
</tr>
</thead>
<tbody>
<?php
$nomr=0;
$total=0;
$sqx = mysqli_query($koneksi,  "SELECT * FROM t_tahunnormal GROUP BY Tahun order by Tahun ASC");
while ($r = mysqli_fetch_array($sqx)){
$nomr++;
$jml = mysqli_fetch_array(mysqli_query($koneksi,  "SELECT count(PMBID) as Jumlah,ProdiID,PMBPeriodID,RegUlang 
									  FROM pmb 
									  WHERE  Left(PMBPeriodID,4)='$r[Tahun]'								  
									  Group By Left(PMBPeriodID,4)"));
	 	
$total += $jml['Jumlah'];
echo "<tr>
<td style='text-align:center'>$nomr</td>            
<td style='text-align:center'><a href='?index.php&ndelox=penmaba/admpmbinfoall&tahun=$r[Tahun]'>$r[Tahun]</a></td>
<td style='text-align:right'>$jml[Jumlah] </td>
<td style='text-align:right'><a href='?index.php&ndelox=penmaba/admpmbinfoall&tahun=$r[Tahun]'>Data PMB</a>  
								
</td>
</tr>";
}
?>
<tbody>
<tr style='background:purple;color:white;font-weight:bold'>
  <th style='width:10px'></th>               
  <th style='width:20px'>Total</th>
  <th style='width:110px;text-align:right'><?php echo"".number_format($total)." Orang";?></th>  
  <th style='width:10px;text-align:right'><a href='?index.php&ndelox=reports/pmbmonitor&act=grafikpmball&prodi=<?php echo"&tahun=".date('Y').""; ?>'>Grafik PMB All Prodi</a></th>              
</tr>
</table>
</div>
</div>
</div>

<div class='card'>
<div class='table-responsive'>
<table id='example' class='table table-sm table-striped'>
<tr>
<td  align="left">
<?php $pm = mysqli_fetch_array(mysqli_query($koneksi,  "SELECT * FROM t_pengumuman"));	 ?>       
</td>
</tr>

<tr>
<td  align="left">
<?php echo"<b style='color:green;'><marquee onmouseout='this.start()' onmouseover='this.stop()'>Informasi Mahasiswa Daftar Ulang Mahasiswa Baru 2019</marquee></b>"; ?> 

</td>
</tr>
</table>
</div>
</div>


