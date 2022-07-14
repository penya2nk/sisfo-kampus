<?php if ($_GET['act']==''){ ?>
	<div class="card">
<div class="card-header">

<div class="form-group row">
	<label class="col-md-6 col-form-label text-md-right"><b style='color:purple'><b style=color:green;font-size=18px>Peserta Test <?php echo"Prodi $_GET[prodi] - Gelombang $_GET[tahun]";?></b></b></label>
	<div class="col-md-2">	 

                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                   <input type="hidden" name='ndelox' value='penmaba/penmabamhs'> 
					<select name='tahun' class='form-control form-control-sm' onChange='this.form.submit()'>
                        <?php 
                            echo "<option value=''>- Pilih Periode PMB -</option>";
                            $tahun = mysqli_query($koneksi, "SELECT distinct(PMBPeriodID),NA FROM pmbperiod order by PMBPeriodID Desc limit 8"); //and NA='N'
                            while ($k = mysqli_fetch_array($tahun)){
                              if ($_GET['tahun']==$k['PMBPeriodID']){
                                echo "<option value='$k[PMBPeriodID]' selected>$k[PMBPeriodID]</option>";
                              }else{
                                echo "<option value='$k[PMBPeriodID]'>$k[PMBPeriodID]</option>";
                              }
                            }
                        ?>
                    </select>
					</div>                


			<div class="col-md-2">           
                    <select name='prodi' class='form-control form-control-sm' onChange='this.form.submit()'>
                        <?php 
                            echo "<option value=''>- Pilih Program Studi -</option>";
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
		<input type="submit" class='btn btn-success btn-sm' value='Lihat'>
		</form>
		</div>
</div>
                
</div>  	
</div>  

<div class="card">
<div class="card-header">
                    	<div class="table-responsive">
                  <table id="example1" class="table table-sm table-striped">
                    <thead>
                     <tr style="background:purple;color:white">
					  <th  width="10px">No</th>					  					 
					  <th width="40px"> PMBID</th>					  
					  <th width="150px">Nama</th> 
					  <th width="150px">Tempat & TglLahir</th> 
					  <th width="30px">Program</th>
					  <th width="30px">Prodi</th>
					  <th width="30px">Status</th>
					  <th width="30px">Lulus?</th>
					  <th width="30px">Registered?</th>
					  <th width="30px">NIM</th>
					  <th width="30px">Aksi</th>
					</tr>
                    </thead>
                    <tbody>
                  <?php
                    if (isset($_GET['tahun'])){
						if ($_GET['tahun']!='' AND $_GET['prodi']!=''){
						  $tampil = mysqli_query($koneksi, "SELECT * FROM pmb where ProdiID='".strfilter($_GET['prodi'])."' AND PMBPeriodID='".strfilter($_GET['tahun'])."' order by PMBID Desc");                    
						}
						else if ($_GET['tahun']!='' AND $_GET['prodi']==''){
						  $tampil = mysqli_query($koneksi, "SELECT * FROM pmb where PMBPeriodID='".strfilter($_GET['tahun'])."' order by PMBID Desc");                    
						}
						else if ($_GET['tahun']=='' AND $_GET['prodi']!==''){
						  $tampil = mysqli_query($koneksi, "SELECT * FROM pmb where ProdiID='".strfilter($_GET['prodi'])."' order by PMBID Desc");                    
						}
						else{
						  $tampil = mysqli_query($koneksi, "SELECT * FROM vw_taxxxxxxxxx  order by PMBID Desc");                
						}				
						$no = 1;
						while($r=mysqli_fetch_array($tampil)){
						$status = mysqli_fetch_array(mysqli_query($koneksi, "select StatusAwalID,Nama from statusawal where StatusAwalID='$r[StatusAwalID]'"));	
						$namax 	= strtolower($r['Nama']);
						$Nama	= ucwords($namax);
						$TempatLahirx 	= strtolower($r['TempatLahir']);
						$TempatLahir	= ucwords($TempatLahirx);
					    if ($r['RegUlang']=='Y'){
					        $RegStat="Aktif";
							$c="style=color:green";
						}else{
						    $RegStat="Belum";
						    $c="style=color:black";
						}
					   
						  echo "<tr $c>
						  <td>$no</td>					  					 
						  <td>$r[PMBID]</td>						  
						  <td>$Nama</td>
						  <td>$TempatLahir, ".tgl_indo($r['TanggalLahir'])."</td>
						  <td>$r[ProgramID]</td> 
						  <td>$r[ProdiID]</td>
						  <td>$status[Nama]</td>	
						  <td>$r[LulusUjian] - $r[NilaiUjian]</td>
						  <td>$RegStat</td>	
						  <td>$r[NIM]</td>
						  <td><a class='btn btn-success btn-xs' title='Lihat Data' href='index.php?ndelox=penmaba/penmabaformdaftar&act=ubahformdaftar&tahun=$r[PMBPeriodID]&formulir=&PMBID=$r[PMBID]'><span class='glyphicon glyphicon-list'></span> Lihat Data</a></td>
						  </tr>";
						  $no++;
						  }
					}	//get tahun top  
                  ?>
                    </tbody>
                  </table></div>
                </div><!-- /.box-body -->
                </div>
            </div>

<?php 
}
?>