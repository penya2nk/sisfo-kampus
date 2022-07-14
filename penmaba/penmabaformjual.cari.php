<?php

session_start();
include_once "../academic_sisfo1.php";
ViewHeaderApps("Cari Nama Parsial");

TitleApps("PMB Form Jual Mahasiswa - Gelombang $_REQUEST[gel]");

$temp_nama = $_REQUEST['n'];
$lungo = (empty($_REQUEST['lungo']))? "CariNama" : $_REQUEST['lungo'];
$lungo();

function CariNama()
{	
	global $koneksi;
	$gelombang = AmbilOneField('pmbperiod', "KodeID='".KodeID."' and NA", 'N', "PMBPeriodID");
	TitleApps("List Nama yang Cocok");
	if(empty($_REQUEST['PFJ_TGL_y']) and empty($_REQUEST['PFJ_TGL_m']) and empty($_REQUEST['PFJ_TGL_d']))
	{  	$checktanggal = '';	}
	else
	{	$temptanggal = "$_REQUEST[PFJ_TGL_y]-$_REQUEST[PFJ_TGL_m]-$_REQUEST[PFJ_TGL_d]"; 
		$checktanggal = "and TanggalLahir='$temptanggal'"; }
	$s = "select * from `aplikan` where Nama LIKE '%$_REQUEST[n]%' $checktanggal";
	//echo "Select: $s";
	$r = mysqli_query($koneksi, $s);
	$n = mysqli_num_rows($r);
	
	if(empty($_REQUEST['PFJ_TGL_y']))
	{	$temptahun = date('Y')-19;
		$opttanggal = AmbilComboTgl("$temptahun-".@$_REQUEST['PFJ_TGL_m']."-".@$_REQUEST['PFJ_TGL_d'], 'PFJ_TGL');	}
	else
	{	$opttanggal = AmbilComboTgl("$_REQUEST[PFJ_TGL_y]-$_REQUEST[PFJ_TGL_m]-$_REQUEST[PFJ_TGL_d]", 'PFJ_TGL'); 	}
	echo "<p><table class=box cellspacing=1 align=center>
			<form action='?' method=POST>
				<input type=hidden name='ndelox' value='$_SESSION[ndelox]'/>
				<input type=hidden name='lungo' value='CariNama' />
				<input type=hidden name='id' value='$_REQUEST[id]' />
				<input type=hidden name='gel' value='$_REQUEST[gel]' />
				<input type=hidden name='n' value='$_REQUEST[n]' />
				
				<tr>
				<td class=inp>Filter Tanggal Lahir: </td>
				<td class=ul1>$opttanggal</td>
				<td class=ul1><input type=submit name='CariTanggal' value='Filter Tanggal'></td>
				</tr>
			</form>
		</table></p>
		";
	
	echo "<p><table class=box cellspacing=1 align=center width=100%>
			<form action='?' method=POST>
				<input type=hidden name='ndelox' value='$_SESSION[ndelox]'/>
				<input type=hidden name='lungo' value='SelesaiCari' />
				<input type=hidden name='id' value='$_REQUEST[id]' />
				<input type=hidden name='gel' value='$_REQUEST[gel]' />
				";
	echo "<tr>
			<td class=ul1 colspan=8><sub>Nama Filter Aktif: $_REQUEST[n]</sub></td>
		  </tr>
		  <tr>
			<td class=ul1 colspan=8><sub>Tanggal Filter Aktif: ".@$_REQUEST['PFJ_TGL_y']."-".@$_REQUEST['PFJ_TGL_m']."-".@$_REQUEST['PFJ_TGL_d']."<sub></td>
		  </tr>";
	
	echo "<tr>
			<th class=ttl width=15>Pilih</th>
			<th class=ttl width=30>No. Aplikan</th>
			<th class=ttl width=150>Nama</th>
			<th class=ttl width=15>Kelamin</th>
			<th class=ttl width=80>Tempat<br> Lahir</th>
			<th class=ttl width=80>Tgl. Lahir</th>
			<th class=ttl width=150>Alamat</th>
			<th class=ttl width=20>Kota</th>
			<th class=ttl width=10>Kode<br>Pos</th>
			<th class=ttl width=20>Status</th>
		</tr>";
	while($w=mysqli_fetch_array($r))
	{	$FormulirID = AmbilOneField('pmbformjual', "PMBPeriodID='$gelombang' and AplikanID", $w['AplikanID'], 'PMBFormJualID');
		//if($w['PMBID']=='' or !isset($w['PMBID']) )
		if(empty($FormulirID) or $FormulirID == '')
		{	echo "<tr>
				<td class=ul1><input type=checkbox name='pilihan[]' value='".$w['AplikanID']."' 
					onChange='this.form.submit()' /></td>
				";
			$Status = "";
		}
		else
		{
			echo "<tr bgcolor='#D3D3D3'>
				<td class=ul1 align=center>&times</td>
				";
			$Status = "SUDAH MEMBELI";
		}
		echo "	<td class=ul1 align=center>".$w['AplikanID']."</td>
				<td class=ul1>$w[Nama]</td>
				<td class=ul1 align=center>$w[Kelamin]</td>
				<td class=ul1 align=center>$w[TempatLahir]</td>
				<td class=ul1 align=center>$w[TanggalLahir]</td>
				<td class=ul1>$w[Alamat]</td>
				<td class=ul1 align=center>$w[Kota]</td>
				<td class=ul1 align=center>$w[KodePos]</td>
				<td class=ul1 align=center><b>$Status</b></td>
			</tr>";
		
	}
	
	echo "<tr>
			<td class=ul1 colspan=8 align=center><input type=button name='Batal' value='Batal'
				onClick=\"window.close()\" /> 
			</td>
			</tr>";
	
	echo "</form>
		</table></p>";
}

function SelesaiCari()
{	echo "Data akan dimasukkan ke dalam formulir penjualan disimpan";

	$id = $_REQUEST['id'];
	$gel = $_REQUEST['gel'];
	$input = $_REQUEST['pilihan'];

	echo "<SCRIPT>
			lnk = '../$_SESSION[ndelox].jual.php?id=$id&gel=$gel&tn=$input[0]';
			//alert(lnk);
			opener.location=lnk;
			self.close();
		</SCRIPT>";
}
?>