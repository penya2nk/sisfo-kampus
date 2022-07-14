<div class='card'>
<div class='card-header'>

     <div class="form-group row">
    	<label class="col-md-7 col-form-label text-md-right"><b style='color:purple'>SINKRONISASI DATA</b></label>
    	<div class="col-md-2">                
            <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
            <input type="hidden" name='ndelox' value='adm_sinkronisasi_mhs'>
            <select name='program' class='form-control form-control-sm' onChange='this.form.submit()'>
            <?php 
            	echo "<option value=''>- Pilih Program -</option>";
            	$program = mysqli_query($koneksi, "SELECT * FROM program");
            	while ($k = mysqli_fetch_array($program)){
            	 if ($_GET[program]==$k[ProgramID]){
            		echo "<option value='$k[ProgramID]' selected>$k[ProgramID] - $k[Nama]</option>";
            	  }else{
            		echo "<option value='$k[ProgramID]'>$k[ProgramID] - $k[Nama] </option>";
            	  }
            	}
            ?>
            </select>
        </div>                
            
            
        <div class="col-md-2">
            <select name='prodi' class='form-control form-control-sm' onChange='this.form.submit()'>
            <?php 
            	echo "<option value=''>- Pilih Program Studi -</option>";
            	$prodi = mysqli_query($koneksi, "SELECT * from prodi where ProdiID='SI' or ProdiID='TI'");
            	while ($k = mysqli_fetch_array($prodi)){
            	   if ($_GET[prodi]==$k[ProdiID]){
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
                </form>
        </div>
        
        
    </div>
</div>
</div>

<?php if ($_GET[act]==''){

  $tahun =mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mhsw_from WHERE ProgramID='".strfilter($_GET[program])."' AND ProdiID='".strfilter($_GET[prodi])."'"));
  if (empty($tahun['MhswID'])){
    echo"<p align='center'><b style='color:red;font-size:25px'>Tidak Dapat Diproses! </b></br>
	<b style='color:purple;font-size:20px'>Tidak dapat diproses karena tidak ditemukan data untuk Program=$_GET[program], Program Studi=$_GET[prodi]";
	echo"</b></p></br>";
  }else{	
  	echo "<p align='center'>Proses
    Benar Anda akan memproses IPK untuk tahun akademik:
    <b style='color:purple;font-size:30px'><center>$tahun[MhswID]</b></br>
    Proses mungkin memakan waktu yang lama.<hr>
	Pilihan: <input type=button name='Proses' value='Sinkronisasi Mahasiswa' onClick=\"location='?ndelox=adm_sinkronisasi_mhs&act=ProsesIPK1&tahun=$tahun[TahunID]&prodi=$_GET[prodi]&program=$_GET[program]'\">";
	echo"</p><br>";
  }

}

else if ($_GET[act]=='ProsesIPK1'){ 

	$tahun 		= strfilter($_GET['tahun']);
	$prodi 		= strfilter($_GET['prodi']);
	$program  	= strfilter($_GET['program']);

	$_npm = '';
	$s = "select *
	  from mhsw_from k
	  where
		k.ProdiID='$prodi'
		and k.ProgramID='$program'	
	  order by k.MhswID"; 

	$r = mysqli_query($koneksi, $s);
	$_SESSION['IPK'.$prodi] = 0;
	while ($w = mysqli_fetch_array($r)) {
	  $_pos = $_SESSION['IPK'.$prodi];
	  $_SESSION['IPK-MhswID'. $prodi . $_pos] = $w['MhswID'];
	  $_SESSION['IPK-KHSID' . $prodi . $_pos] = $w['KHSID'];
	  $_SESSION['IPK'.$prodi]++; 
	}
	 $max = $_SESSION['IPK'.$_GET[prodi]];
	 $_SESSION['IPK'.$prodi.'POS'] = 0;
	echo "<p align='center'>Akan diproses: <font size=+2>$max</font> mahasiswa.</p> 
	<p align='center'><IFRAME SRC='sinkronisasi_mhs_go.php?lungo=PRC2&tahun=$tahun&prodi=$prodi&program=$program' frameborder=0>
	</IFRAME></p>";
}

?>
