<?php
error_reporting(0);

session_start();
include_once "../academic_sisfo1.php";
echo"<script type='text/javascript' src='jscolor.js'></script>";

$md = $_REQUEST['md'];
$id = $_REQUEST['id'];

ViewHeaderApps("Jenis Libur");

$lungo = (empty($_REQUEST['lungo']))? 'Edit' : $_REQUEST['lungo'];
$lungo($md, $id);

function Edit($md, $id)
{	if($md == 0)
	{	$jdl = "Edit Jenis Hari Libur";	
		$w = AmbilFieldx('jenislibur', "JenisLiburID='$id' and KodeID", KodeID, '*');
		$kodejenislibur = $w['JenisLiburID']."<input type=hidden name='JenisLiburID' value='$id'>";
	}
	else if($md == 1)
	{	$jdl = "Tambah Jenis Hari Libur";
		$w = array();
		$w['Warna'] = '#';
		$kodejenislibur = "<input type=text name='JenisLiburID' value='' size=7 maxlength=10>";
	}
	else
	{	die(PesanError("Error", "Mode penyimpanan tidak diketahui"));
	}
	

	echo "<table class=bsc cellspacing=1 width=100%>
			<form name='datajenislibur' id='datajenislibur' action='?' method=POST>
			<input type=hidden name='lungo' value='SavData'>
			<input type=hidden name='md' value='$md'>
			<input type=hidden name='id' value='$id'>
			<tr><td class=inp>Kode Jenis Libur</td>
			  <td class=ul1>$kodejenislibur</td>
			  </tr>
			<tr><td class=inp>Nama Jenis Libur:</td>
			  <td class=ul1><input type=text name='Nama' value='$w[Nama]'>
			<tr><td class=inp>Warna Penanda:</td>
			  <td class=ul1>
			  				<input name='Warna' class='color' value='$w[Warna]'>
							</td></tr>
			<tr><td class=inp>Keterangan:</td>
			  <td class=ul1><textarea name='Keterangan' cols=30 row=2>$w[Keterangan]</textarea></td>
			  </tr>
			<tr><td colspan=2 align=center><input type=submit name='Simpan' value='Simpan Hari Libur'>
										   <input type=button name='Batal' value='Batal' onClick=\"window.close()\"></td>
			  </tr>
			 </form>
		  </table>
		  <script>
			function CheckWarnaSyntax(frm)
			{	var thetext = frm.Warna.value;
				
				if(thetext.substring(0, 1) != '#') 
				{	frm.Warna.value = '#'+frm.Warna.value; }
				else { }
				
				var thetext2 = frm.Warna.value;
				var allowedChar = 'ABCDEF1234567890';
				
				if(thetext2 == '#') 
				{	alert('Masukkan isi dari Warna Penanda'); }
				else if(thetext2.length < 7)
				{	alert('Masukkan tanda # diikuti 6 digit angka atau huruf kapital yang diijinkan'); 
					frm.Warna.value = '#'; 
				}
				else 
				{	var noforbidden = true;
					for(var i=1; i < thetext2.length; i++)
					{	if(allowedChar.indexOf(thetext2.charAt(i)) == -1)
						{	noforbidden = false;
							break;
						}
					}
					if(noforbidden == false)
					{	alert('Masukkan hanya angka atau huruf kapital yang diijinkan'); 
						frm.Warna.value = '#';
					}
					else 
					{	frm.ColorBox.setAttribute('style', 'background-color:'+thetext2); }
				}
			}
		  </script>
		 ";	
}

function SavData($md, $id)
{	
	global $koneksi;
	$JenisLiburID = $_REQUEST['JenisLiburID'];
	$Nama = $_REQUEST['Nama'];
	$Keterangan = $_REQUEST['Keterangan'];
	$Warna = $_REQUEST['Warna'];
	$AdaKuliah = (!empty($_REQUEST['AdaKuliah']))? 'Y' : 'N';
	
	if($md == 0)
	{	$s = "update jenislibur 
				set Nama = '$Nama',
					Keterangan = '$Keterangan',
					Warna = '$Warna',
					TanggalEdit= now(),
					LoginEdit = '$_SESSION[_Login]'
				where JenisLiburID='$JenisLiburID'
				";
		$r = mysqli_query($koneksi, $s);
	}
	else if($md == 1) 
	{	$ada = AmbilOneField('jenislibur', "JenisLiburID='$JenisLiburID' and KodeID", KodeID, 'JenisLiburID');
		if($ada)
		{	die(PesanError("Duplikat", "Terdapat Kode Jenis Libur yang sama. Harap memasukkan kode yang berbeda<br>
                                  <input type=button name='Tutup' value='Tutup' onClick=\"window.close();\">")); 
		}
		else
		{
			$s = "insert into jenislibur 
					set JenisLiburID = '$JenisLiburID',
						Nama = '$Nama',
						KodeID='".KodeID."',
						Keterangan = '$Keterangan',
						Warna = '$Warna',
						TanggalBuat= now(),
						LoginBuat = '$_SESSION[_Login]'";
			$r = mysqli_query($koneksi, $s);
		}
	}
	TutupScript();
}

function TutupScript() {
echo <<<SCR
<SCRIPT>
  function ttutup() {
    opener.location='../index.php?ndelox=$_SESSION[ndelox]';
    self.close();
    return false;
  }
  ttutup();
</SCRIPT>
SCR;
}
?>
