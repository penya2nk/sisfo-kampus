<?php if ($_GET[act]==''){ 
$prodi= mysqli_fetch_array(mysqli_query("select MhswID,ProdiID from mhsw WHERE MhswID='$_SESSION[_Login]'"));
$nmprodi= mysqli_fetch_array(mysqli_query("select ProdiID,Nama from prodi WHERE ProdiID='$prodi[ProdiID]'"));
?> 
<div class='card'>
<div class='card-header'>
<div class='col-md-12'>
<div class='box box-info'>
                  <h3 class="box-title"><b style=color:#FF8306;>Kalender Akademik <?php echo"$nmprodi[Nama]";?> Periode <?php echo" $_SESSION[tahun_akademik]";?></b></h3>
                 
                </div><!-- /.box-header -->
                <div class="box-body">
                    	<div class="table-responsive">
                  <table id="example" class="table table-bordered table-striped">
                    <thead>
                      <tr style=text-align:left;font-size:14px;color:#FFFFFF;font-weight:reguler;background-color:#ACAC00;width:100%;>
                        <th style='width:2px'>No</th>
                        <th style='width:200px'>Nama Kegiatan</th>
                        <th style='width:80px'>Tgl Mulai</th>
                        <th style='width:80px'>Tgl Selesai</th>                                                
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
				    //where TahunID='$_SESSION[tahun_akademik]'
					
                    $tampil = mysqli_query($koneksi, "SELECT * from tahun_kegiatan  WHERE ProdiID='$prodi[ProdiID]' and TahunID='$_SESSION[tahun_akademik]' order by TglMulai asc");
                    $no = 1;
                    while($r=mysqli_fetch_array($tampil)){
                    $tglnow =date('Y-m-d');
					if ($r[TglMulai]<=$tglnow && $r[TglSelesai]<=$tglnow){
						$c="style=color:red";
					}else{
						$c="style=color:green";
					}
					echo "<tr><td $c>$no</td>
                              <td $c>$r[Nama]</td>
                              <td $c>".tgl_indo($r[TglMulai])."</td>
                              <td $c>".tgl_indo($r[TglSelesai])."</td>
							  </tr>";
                      $no++;
                      }
                  ?>
                    </tbody>
                  </table></div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
<?php 
}
?>