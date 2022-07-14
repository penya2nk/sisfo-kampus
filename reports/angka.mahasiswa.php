<section class="content">
    <div class="row">
        <div class="col-12">       
          <div class="card">
            <div class="card-body">
<?php

function DftrAngkaMahasiswa() {
  global $koneksi;
  $opt = AmbilCombo2('prodi', "concat(ProdiID, ' - ', Nama)", 'ProdiID', $_SESSION['ProdiID'], '', 'ProdiID');
  $colspan = 16;
  echo "<p><table id='example1' class='table table-sm table-striped' width='80%'>
    <tr><form action='?' method=POST>
    <input type=hidden name='ndelox' value='reports/angka.mahasiswa'>
    <td colspan=$colspan class=ul>Program Studi : <select name='ProdiID' onChange='this.form.submit()'>$opt</select></td>
    </form></tr>";
  echo "<tr><td class=ul colspan=$colspan><a href='?ndelox=reports/angka.mahasiswa&lungo=GrafikMhs&md=1'>Grafik Pertumbuhan Mahasiswa</a> |
    </td></tr>";
  echo "<tr style='background:purple;color:white'>
    <th class=ttl width=20px height=28px>#</th>
    <th class=ttl width=140px>Tahun</th>
    <th class=ttl width=60px style=text-align:right>IKM </th>
    <th class=ttl width=60px style=text-align:right>IKM B</th>
    <th class=ttl width=60px style=text-align:right>RMIK</th>
    <th class=ttl width=60px style=text-align:right>PSIK</th>
    <th class=ttl width=60px style=text-align:right>PBD</th>
    <th class=ttl width=60px style=text-align:right>BID</th>
    <th class=ttl width=60px style=text-align:right>SIBID</th>
    <th class=ttl width=60px style=text-align:right>MKES</th>
    <th class=ttl width=60px style=text-align:right>NERS</th>
    <th class=ttl width=60px style=text-align:right>SI</th>
    <th class=ttl width=60px style=text-align:right>TI</th>
    <th class=ttl width=60px style=text-align:right>HKM</th>
    <th class=ttl width=60px style=text-align:right>KMN</th>
    <th class=ttl width=60px style=text-align:right>All</th>
    </tr>";
    $tahun = mysqli_query($koneksi, "SELECT * FROM t_tahunx GROUP BY Tahun order by Tahun ASC");
		while ($r = mysqli_fetch_array($tahun)){
    $no++;  
    $regIKM =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumRegIKM,ProdiID,TahunID FROM mhsw 
          WHERE ProdiID='IKM' AND LEFT(MhswID,2)='$r[Tahun]'")); 
    $totIKM += $regIKM['JumRegIKM']; 
    
    $regIKM_B =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumRegIKM_B,ProdiID,TahunID FROM mhsw 
    WHERE ProdiID='IKM B' AND LEFT(MhswID,2)='$r[Tahun]'")); 
    $totIKM_B += $regIKM_B['JumRegIKM_B']; 

    $regPIKES =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumRegPIKES,ProdiID,TahunID FROM mhsw 
          WHERE ProdiID='PIKES' AND LEFT(MhswID,2)='$r[Tahun]'"));     
    $totPIKES += $regPIKES['JumRegPIKES'];    
    

    $regPSIK =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumRegPSIK,ProdiID,TahunID FROM mhsw 
          WHERE ProdiID='PSIK' AND LEFT(MhswID,2)='$r[Tahun]'"));     
    $totPSIK += $regPSIK['JumRegPSIK'];  

    $regPBD =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumRegPBD,ProdiID,TahunID FROM mhsw 
          WHERE ProdiID='PBD' AND LEFT(MhswID,2)='$r[Tahun]'"));     
    $totPBD += $regPBD['JumRegPBD'];  

    $regBID =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumRegBID,ProdiID,TahunID FROM mhsw 
    WHERE ProdiID='BID' AND LEFT(MhswID,2)='$r[Tahun]'"));     
    $totBID += $regBID['JumRegBID']; 

    $regSIBID =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumRegSIBID,ProdiID,TahunID FROM mhsw 
    WHERE ProdiID='SIBID' AND LEFT(MhswID,2)='$r[Tahun]'"));     
    $totSIBID += $regSIBID['JumRegSIBID']; 

    $regGIGI =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumRegGIGI,ProdiID,TahunID FROM mhsw 
    WHERE ProdiID='GIGI' AND LEFT(MhswID,2)='$r[Tahun]'"));     
    $totGIGI += $regGIGI['JumRegGIGI']; 

    $regMKES =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumRegMKES,ProdiID,TahunID FROM mhsw 
    WHERE ProdiID='MKES' AND LEFT(MhswID,2)='$r[Tahun]'"));     
    $totMKES += $regMKES['JumRegMKES']; 

    $regNERS =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumRegNERS,ProdiID,TahunID FROM mhsw 
    WHERE ProdiID='NERS' AND LEFT(MhswID,2)='$r[Tahun]'"));     
    $totNERS += $regNERS['JumRegNERS']; 

    $regSI =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumRegSI,ProdiID,TahunID FROM mhsw 
    WHERE ProdiID='SI' AND LEFT(MhswID,2)='$r[Tahun]'"));     
    $totSI += $regSI['JumRegSI']; 

    $regTI =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumRegTI,ProdiID,TahunID FROM mhsw 
    WHERE ProdiID='TI' AND LEFT(MhswID,2)='$r[Tahun]'"));     
    $totTI += $regTI['JumRegTI']; 

    $regHKM =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumRegHKM,ProdiID,TahunID FROM mhsw 
    WHERE ProdiID='HKM' AND LEFT(MhswID,2)='$r[Tahun]'"));     
    $totHKM += $regHKM['JumRegHKM']; 

    $regKMN =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumRegKMN,ProdiID,TahunID FROM mhsw 
    WHERE ProdiID='KMN' AND LEFT(MhswID,2)='$r[Tahun]'"));     
    $totKMN += $regKMN['JumRegKMN']; 
    
    $All =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumAll,ProdiID,TahunID FROM mhsw WHERE LEFT(MhswID,2)='$r[Tahun]'"));
    $totAll += $All['JumAll'];
    
      if ($r['Tahun']=='10'){
        $tahunx="2010";
      }
      elseif ($r['Tahun']=='11'){
        $tahunx="2011";
      }
      elseif ($r['Tahun']=='12'){
        $tahunx="2012";
      }
      elseif ($r['Tahun']=='13'){
        $tahunx="2013";
      }
      elseif ($r['Tahun']=='14'){
        $tahunx="2014";
      }
      elseif ($r['Tahun']=='15'){
        $tahunx="2015";
      }
      elseif ($r['Tahun']=='16'){
        $tahunx="2016";
      }
      elseif ($r['Tahun']=='17'){
        $tahunx="2017";
      }
      elseif ($r['Tahun']=='18'){
        $tahunx="2018";
      }
      elseif ($r['Tahun']=='19'){
        $tahunx="2019";
      }
      elseif ($r['Tahun']=='20'){
        $tahunx="2020";
      } 
      elseif ($r['Tahun']=='21'){
        $tahunx="2021";
      }   
        
      elseif ($r['Tahun']=='22'){
        $tahunx="2022";
      }   
        
        echo "<tr>
              <td $c>$no</td>
              <td $c>$tahunx</td>
              <td $c style=text-align:right>$regIKM[JumRegIKM]</td>
              <td $c style=text-align:right>$regIKM_B[JumRegIKM_B]</td>
              <td $c style=text-align:right>$regPIKES[JumRegPIKES]</td>
              <td $c style=text-align:right>$regPSIK[JumRegPSIK]</td>
              <td $c style=text-align:right>$regPBD[JumRegPBD]</td>
              <td $c style=text-align:right>$regBID[JumRegBID]</td>
              <td $c style=text-align:right>$regSIBID[JumRegSIBID]</td>
              <td $c style=text-align:right>$regMKES[JumRegMKES]</td>
              <td $c style=text-align:right>$regNERS[JumRegNERS]</td>
              <td $c style=text-align:right>$regSI[JumRegSI]</td>
              <td $c style=text-align:right>$regTI[JumRegTI]</td>
              <td $c style=text-align:right>$regHKM[JumRegHKM]</td>
              <td $c style=text-align:right>$regKMN[JumRegKMN]</td>                 
              <td $c style=text-align:right>$All[JumAll]</td>
              </tr>";        
    }
    echo"<tr>
    <td colspan=2><b>Total</b></td>
    <td style=text-align:right><b>".number_format($totIKM)."</b></td>
    <td style=text-align:right><b>".number_format($totIKM_B)."</b></td>
    <td style=text-align:right><b>".number_format($totPIKES)."</b></td>
    <td style=text-align:right><b>".number_format($totPSIK)."</b></td>
    <td style=text-align:right><b>".number_format($totPBD)."</b></td>
    <td style=text-align:right><b>".number_format($totBID)."</b></td>
    <td style=text-align:right><b>".number_format($totSIBID)."</b></td>
    <td style=text-align:right><b>".number_format($totMKES)."</b></td>
    <td style=text-align:right><b>".number_format($totNERS)."</b></td>
    <td style=text-align:right><b>".number_format($totSI)."</b></td>
    <td style=text-align:right><b>".number_format($totTI)."</b></td>
    <td style=text-align:right><b>".number_format($totHKM)."</b></td>
    <td style=text-align:right><b>".number_format($totKMN)."</b></td>
    <td style=text-align:right><b>".number_format($totAll)."</b></td>
    </tr>";
  echo "</table></p>";
}

function GrafikMhs() {
?>  

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
                    return '<b>' + this.series.name + '</b><br/>' +
                        'Ada ' + this.point.y + ' Orang';
                }
            }
        });
    });
</script>

    <div class="card-header">
        <h3 class="card-title"><b style=color:green;>Grafik Pertumbuhan Mahasiswa </b><b style=color:#FF8306;><?php echo"Seluruh Program Studi";?></b></h3>
    </div>

<div class="card-body chat" id="chat-card">
<div id="container" style="min-width: 310px; height: 205px; margin: 0 auto"></div>
<table id="datatable" style='display:none'>
<tr>
    <th></th>
    <th>IKM A</th>
    <th>IKM B</th>
    <th>PSIK</th>
    <th>RMIK</th>	
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
    <?php 
    global $koneksi;
    $tahun = mysqli_query($koneksi, "SELECT * FROM t_tahunx GROUP BY Tahun order by Tahun ASC");
		while ($r = mysqli_fetch_array($tahun)){
    $no++;  
          $IKM =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumIKM,ProdiID,TahunID FROM mhsw 
                WHERE ProdiID='IKM' AND LEFT(MhswID,2)='$r[Tahun]'")); 
          $IKM_B =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumIKM_B,ProdiID,TahunID FROM mhsw 
                WHERE ProdiID='IKM_B' AND LEFT(MhswID,2)='$r[Tahun]'"));
          $PSIK =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumPSIK,ProdiID,TahunID FROM mhsw 
                WHERE ProdiID='PSIK' AND LEFT(MhswID,2)='$r[Tahun]'"));  
          $PIKES =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumPIKES,ProdiID,TahunID FROM mhsw 
                WHERE ProdiID='PIKES' AND LEFT(MhswID,2)='$r[Tahun]'")); 
          $MKES =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumMKES,ProdiID,TahunID FROM mhsw 
                WHERE ProdiID='MKES' AND LEFT(MhswID,2)='$r[Tahun]'")); 
          $NERS =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumNERS,ProdiID,TahunID FROM mhsw 
                WHERE ProdiID='NERS' AND LEFT(MhswID,2)='$r[Tahun]'")); 
                
          $PBD =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumNERS,ProdiID,TahunID FROM mhsw 
                WHERE ProdiID='PBD' AND LEFT(MhswID,2)='$r[Tahun]'"));     
          $BID =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumBID,ProdiID,TahunID FROM mhsw 
                WHERE ProdiID='BID' AND LEFT(MhswID,2)='$r[Tahun]'"));     
           
          $SIBID =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumSIBID,ProdiID,TahunID FROM mhsw 
                WHERE ProdiID='SIBID' AND LEFT(MhswID,2)='$r[Tahun]'"));     

          $SI =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumSI,ProdiID,TahunID FROM mhsw 
                WHERE ProdiID='SI' AND LEFT(MhswID,2)='$r[Tahun]'"));     

          $TI =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumTI,ProdiID,TahunID FROM mhsw 
                WHERE ProdiID='TI' AND LEFT(MhswID,2)='$r[Tahun]'")); 

          $HKM =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumHKM,ProdiID,TahunID FROM mhsw 
                WHERE ProdiID='HKM' AND LEFT(MhswID,2)='$r[Tahun]'"));  
                
          $KMN =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumKMN,ProdiID,TahunID FROM mhsw 
                WHERE ProdiID='KMN' AND LEFT(MhswID,2)='$r[Tahun]'"));                  
                
          $All =mysqli_fetch_array(mysqli_query($koneksi, "SELECT count(MhswID) as JumAll,ProdiID,TahunID FROM mhsw 
                WHERE LEFT(MhswID,2)='$r[Tahun]'"));  

          if ($r['Tahun']=='10'){
            $tahunx="2010";
          }
          if ($r['Tahun']=='11'){
            $tahunx="2011";
          }
          elseif ($r['Tahun']=='12'){
            $tahunx="2012";
          }
          elseif ($r['Tahun']=='13'){
            $tahunx="2013";
          }
          elseif ($r['Tahun']=='14'){
            $tahunx="2014";
          }
          elseif ($r['Tahun']=='15'){
            $tahunx="2015";
          }
          elseif ($r['Tahun']=='16'){
            $tahunx="2016";
          }
          elseif ($r['Tahun']=='17'){
            $tahunx="2017";
          }
          elseif ($r['Tahun']=='18'){
          $tahunx="2018";
          }
          elseif ($r['Tahun']=='19'){
          $tahunx="2019";
          }
          elseif ($r['Tahun']=='20'){
            $tahunx="2020";
          }  
          elseif ($r['Tahun']=='21'){
            $tahunx="2021";
          }  
          elseif ($r['Tahun']=='22'){
            $tahunx="2022";
          }  
        echo "<tr>           
              <td $c>$tahunx</td>
              <td $c>$IKM[JumIKM]</td>
              <td $c>$IKM_B[JumIKM_B]</td>
              <td $c>$PSIK[JumPSIK]</td>
              <td $c>$PIKES[JumPIKES]</td>
              <td $c>$MKES[JumMKES]</td>
              <td $c>$NERS[JumNERS]</td>
              <td $c>$PBD[JumPBD]</td>
              <td $c>$BID[JumBID]</td>
              <td $c>$SIBID[JumSIBID]</td>
              <td $c>$SI[JumSI]</td>
              <td $c>$TI[JumTI]</td>
              <td $c>$HKM[JumHKM]</td>
              <td $c>$KMN[JumKMN]</td>
              <td $c>$All[JumAll]</td>
              </tr>";        
    }
    ?>
</tbody>
</table>

<div class="card-header">

<table width=100%>
<tr>
<td>
<?php //$pm = mysqli_fetch_array(mysqli_query($koneksi, $koneksi, "SELECT * FROM t_pengumuman"));	 
$pengumuman = "Universitas Hang Tuah Pekanbaru";
?>       
</td>
</tr>

<tr>
<td>
<?php echo"<b style='color:green;'><marquee onmouseout='this.start()' onmouseover='this.stop()'>$pengumuman</marquee></b>"; ?> 

</td>
</tr>
</table>
</div>
   
    <h3 class="card-title"><a href='?ndelox=angka.mahasiswa'>Tampilkan Data Mahasiswa Dalam Format Tabel </a></h3>
   
</div>

  <?php
}



$kampusid = GainVariabelx('ProdiID'); //huruf kecil bro
$lungo = (empty($_REQUEST['lungo']))? 'DftrAngkaMahasiswa' : $_REQUEST['lungo'];

TitleApps("Mahasiswa Dalam Angka");
$lungo();
?>
</div>
          </div>
      </div>
    </div>
</section> 
