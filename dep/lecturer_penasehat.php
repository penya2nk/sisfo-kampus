<?php
error_reporting(0);
echo <<<SCR
  <script src="dep/dosen.penasehat.script.js"></script>
SCR;

$angk = GainVariabelx('angk');
$prodi = GainVariabelx('prodi');
$kelas = GainVariabelx('kelas');

TitleApps("SETTING DOSEN PENASEHAT");
  global $koneksi;
  $optprd = AmbilPenggunaProdi($_SESSION['_Login'], $_SESSION['prodi']);
  if (isset($prodi) and isset ($angk)){
  	$q = "select * from kelas where ProdiID = '".$prodi."' and TahunID = '".$angk."'";
	$m = mysqli_query($koneksi, $q);
		if (mysqli_num_rows($m) == 0) {
			$optkelas = "";
		} else {
		  $sel1 = ($kelas == 0)? 'selected' : '';
		  $sel2 = ($kelas == 'All')? 'selected' : '';
		  $optkelas ="<option value='0' $sel1>- Pilih Kelas -</option>
    <option value='All' $sel2>Semua</option>";
			while ($x = mysqli_fetch_array($m)){
				$sel = ($kelas == $x['KelasID'])? 'selected' : '';
				$optkelas .= "<option value='$x[KelasID]' $sel>$x[Nama]</option>";
			}
		}
	
	}
  
  echo "
  <div align='center'>
  <div class='card'>
    `<div class='card-header'>
      <form action='?' method=POST>
      <input type=hidden name='ndelox' value='$ndelox' />
      <input type=hidden name='lungo' value='$lungo' />
      Program Studi <select style='height:30px' name='prodi' onChange='this.form.submit()'>$optprd</select>
      <input style='height:30px' placeholder='Angkatan' type=text name='angk' value='$_SESSION[angk]' size=10 maxlength=20>
      <select style='height:30px' name='kelas' onChange='this.form.submit()'>
      $optkelas
      </select>
      <input class='btn btn-success btn-sm' type=submit name='Tampilkan' value='Lihat Data'>


      </form>
    </div>
    </div>`
</div>";

$lungo = (empty($_REQUEST['lungo']))? "ListMahasiswa" : $_REQUEST['lungo'];
$lungo();

function ListMahasiswa() {
	global $koneksi;
  if ($_SESSION['angk'] == '' || $_SESSION['prodi'] == '')
    echo Info("Masukkan Parameter",
      "Anda harus memasukkan Angkatan & Program Studi terlebih dulu.<br />
      Setelah itu Anda dapat mengeset Dosen Penasehatnya.
      <hr size=1 color=silver />
      Hubungi Sysadmin untuk informasi lebih lanjut.");
  else ViewListMahasiswa();
}
function ViewListMahasiswa() {
  global $koneksi;
  $kls =($_SESSION['kelas']=='All')? '' : "and m.KelasID = '$_SESSION[kelas]'";
  
  $s = "select m.MhswID, m.Nama, m.PenasehatAkademik,k.Nama as Kls,
      d.Nama as NamaDosen, d.Gelar as GelarDosen
    from mhsw m
      left outer join dosen d on m.PenasehatAkademik = d.Login and d.KodeID = '".KodeID."'
	  left outer join kelas k on m.KelasID = k.KelasID
    where m.KodeID = '".KodeID."'
      and m.ProdiID = '$_SESSION[prodi]'
      and m.TahunID = '$_SESSION[angk]'
	    
    order by m.MhswID";
  $r = mysqli_query($koneksi, $s); //$kls
  $n = 0;
  
  echo <<<ESD
  
<div class='card'>
<div class='card-header'>
<div class='table-responsive'>
    <table id='example' class='table table-sm table-striped' style='width:100%' align='center'>
    
    <form name='frmPA' action='?' method=POST>
    <input type=hidden name='ndelox' value='$_SESSION[ndelox]' />
    <input type=hidden name='lungo' value='SimpanDosenPenasehat' />
    <input type=hidden name='angk' value='$_SESSION[angk]' />
    <input type=hidden name='prodi' value='$_SESSION[prodi]' />
    <input type=hidden name='kelas' value='$_SESSION[kelas]' />    
    <tr>
        <td class=ul colspan=10>
          <input class='btn btn-success btn-sm' type=button name='btnRekapPA' value='Rek Penasehat Akademik'
            onClick="javascript:CetakRekapPA('$_SESSION[prodi]')" />
          <input class='btn btn-danger btn-sm' type=button name='btnDaftarPA' value='Daftar Penasehat Akademik'
            onClick="javascript:CetakDaftarPA('$_SESSION[prodi]')" />

        <input style='height:30px' type=text name='DosenID' value='$w[DosenID]' size=10 maxlength=50 />
        <input style='height:30px' type=text name='Dosen' value='$w[Dosen]' size=30 maxlength=50 onKeyUp="javascript:CariDosen('$_SESSION[prodi]', 'frmPA')" />
        &raquo;
        <a href='#'
          onClick="javascript:CariDosen('$_SESSION[prodi]', 'frmPA')" />Cari...</a> |
        <a href='#' onClick="javascript:frmPA.DosenID.value='';frmPA.Dosen.value=''">Reset</a>
        <input class='btn btn-primary btn-sm' type=submit name='btnSimpan' value='Set Penasehat Akademik' />
       
        </td>
    </tr>
    
    <tr style='background:purple;color:white'>
    <th class=ttl width=30>NO</th>
        <th class=ttl width=80>NIM</th>
        <th class=ttl width=200>Mahasiswa</th>
       
        <th class=ttl>Penasehat Akademik</th>
        <th class=ttl width=150>Kelas</th>
        <th class=ttl>Cek</th>
    </tr>
ESD;
  while ($w = mysqli_fetch_array($r)) {
    $n++;
    $dsn = (empty($w['PenasehatAkademik']))? "<abbr title='Belum diset'>&minus;</abbr>" : "$w[NamaDosen] <sup>$w[GelarDosen]</sup>";
    echo <<<ESD
    <tr>
        <td class=inp>$n</td>
        <td class=ul>$w[MhswID]</td>
        <td class=ul>$w[Nama]</td>
       
        <td class=ul width=200>$w[NamaDosen], $w[GelarDosen]</td>
        <td class=ul>$w[Kls]&nbsp;</td>
        <td class=ul width=5>
          <input type=checkbox name='MhswID_$n' value='$w[MhswID]' />
          </td>
        </tr>
ESD;
  }
  RandomStringScript();
  echo <<<ESD
    <input type=hidden name='JML' value='$n' />
    </form>
    <tr><td class=ul colspan=5 align=right>
        <input class='btn btn-success btn-sm' type=button name='btnCheckAll' value='Centang Semua' onClick="javascript:CentangSemua($n)" />
        </td></tr>
    </table>
    </div>
</div>
</div>
    
    <p>
    <div class='box0' id='caridosen'></div>
    
  <script>
  function toggleBox(szDivID, iState) // 1 visible, 0 hidden
  {
    if(document.layers)	   //NN4+
    {
       document.layers[szDivID].visibility = iState ? "show" : "hide";
    }
    else if(document.getElementById)	  //gecko(NN6) + IE 5+
    {
        var obj = document.getElementById(szDivID);
        obj.style.visibility = iState ? "visible" : "hidden";
    }
    else if(document.all)	// IE 4
    {
        document.all[szDivID].style.visibility = iState ? "visible" : "hidden";
    }
  }
  function CariDosen(ProdiID, frm) {
    if (eval(frm + ".Dosen.value != ''")) {
      eval(frm + ".Dosen.focus()");
      showDosen(ProdiID, frm, eval(frm +".Dosen.value"), 'caridosen');
      toggleBox('caridosen', 1);
    }
  }
  function CentangSemua(n) {
    for (i = 1; i <= n; i++) {
      eval("frmPA.MhswID_" + i + ".checked = true");
    }
  }
  function CetakRekapPA(prd) {
    _rnd = randomString();
    lnk = "$_SESSION[ndelox].rekap.php?ProdiID="+prd+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=700, height=500, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function CetakDaftarPA(prd) {
    lnk = "$_SESSION[ndelox].daftar.php?ProdiID="+prd+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=700, height=500, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
ESD;
}
function SimpanDosenPenasehat() {
	global $koneksi;
  $angk = sqling($_REQUEST['angk']);
  $prodi = sqling($_REQUEST['prodi']);
  $DosenID = sqling($_REQUEST['DosenID']);
  $JML = $_REQUEST['JML']+0;
  
  if ($JML <= 0) {
    echo PesanError("Error",
      "Tidak ada mahasiswa yang perlu diset.<br />
      Pilih Angkatan dan Program Studi yang tepat.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      <input type=button name='btnKembali' value='Kembali'
      onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />");
  }
  elseif (empty($DosenID)) {
    echo PesanError("Error",
      "Anda belum memilih dosen.<br />
      Pilih dosen yang akan dijadikan Penasehat Akademik terlebih dahulu.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      <input type=button name='btnKembali' value='Kembali'
      onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />");
  }
  else {
    $hitung = 0;
    for ($i = 1; $i <= $JML; $i++) {
      $MhswID = $_REQUEST['MhswID_'.$i];
      if ($MhswID != '') {
        $hitung++;
        $s = "update mhsw
          set PenasehatAkademik = '$DosenID'
          where KodeID = '".KodeID."'
            and MhswID = '$MhswID' ";
        $r = mysqli_query($koneksi, $s);
      }
    }
    if ($hitung == 0) {
      echo PesanError("Error",
      "Anda belum memilih seorang pun mahasiswa yg akan diset.<br />
      Pilih mahasiwa2 yang akan diset Penasehat Akademiknya terlebih dahulu.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      <input type=button name='btnKembali' value='Kembali'
      onClick=\"location='?ndelox=$_SESSION[ndelox]&lungo='\" />");
    }
    else SuksesTersimpan("?ndelox=$_SESSION[ndelox]&lungo=", 10);
  }
}
?>
