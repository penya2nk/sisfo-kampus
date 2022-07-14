<?php

$sub = (empty($_REQUEST['sub']))? 'DftrPresenter' : $_REQUEST['sub'];
$sub();

function DftrPresenterScript() {
  echo <<<SCR
  <script>
	function PresenterEdt(MD, ID, BCK) {
    lnk = "$_SESSION[ndelox].presenter.edit.php?md="+MD+"&id="+ID+"&bck="+BCK;
    win2 = window.open(lnk, "", "width=500, height=300, left=500, top=100, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
SCR;
}

function DftrPresenter() {
	global $koneksi;
  DftrPresenterScript();
  $gelombang = AmbilOneField('pmbperiod', "KodeID='".KodeID."' and NA", 'N', 'PMBPeriodID');
  $n = 0;
  echo "<p><div class='card'>
  <div class='card-header'>
  <div class='table-responsive'>
  <table id='example' class='table table-sm table-striped' style='width:50%' align='center'>
			<form name='datatech' action='?ndelox=$_SESSION[ndelox]' method=POST>
				<input type=hidden name='gel' value='$gelombang'>";
  echo "<tr >
		<td class=ul1 colspan=6>
      <input type=button name='TambahPresenter' value='Tambah Presenter' onClick=\"PresenterEdt(1, '', '$_SESSION[ndelox]')\"/>
      <input type=button name='Refresh' value='Refresh'
        onClick=\"window.location='?ndelox=$_SESSION[ndelox]'\" />
    </td>
    </tr>";
  echo "<tr style='background:purple;color:white'>
    <th class=ttl>#</th>
    <th class=ttl>Kode</th>
    <th class=ttl>Nama</th>
    <th class=ttl>NA</th>
    </tr>";
  
	$s="select * from presenter where KodeID='".KodeID."'";
	$r=mysqli_query($koneksi, $s);
	while($w=mysqli_fetch_array($r))
	{
		$n++;
		$c = ($w['NA'] == 'Y')? 'class=nac' : 'class=ul';
		$edit= "<a href='#' onClick=\"javascript:PresenterEdt(0, '$w[PresenterID]', '$_SESSION[ndelox]')\"><i class='fa fa-edit'></i></a>";
		echo "<tr>
		  <td class=inp width=50>$n $edit</td>
		  <td $c width=60>$w[PresenterID]</td>
		  <td $c>$w[Nama]</td>
		  <td class=ul1 align=center width=10><img src='img/book$w[NA].gif' /></td>
		  <td class=ul1 width=10>
			</td>
		  </tr>";
	}

  echo "</form></table>
  </div>
  </div>
  </div>";
}
