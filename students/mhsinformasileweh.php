<section class="content">
    <div class="row">
        <div class="col-12">       
          <div class="card">
            <div class="card-body">
<?php
TitleApps("Profil Mahasiswa");
$mhsw = AmbilFieldx("mhsw m
  left outer join statusmhsw sm on m.StatusMhswID=sm.StatusMhswID
  left outer join program prg on m.ProgramID=prg.ProgramID
  left outer join prodi prd on m.ProdiID=prd.ProdiID
  left outer join agama agm on m.Agama=agm.Agama
  left outer join kelamin kel on m.Kelamin=kel.Kelamin
  left outer join statussipil kwn on m.StatusSipil=kwn.StatusSipil", 
  "MhswID", $_SESSION['_Login'], 
  "m.*, sm.Nama as STA, 
  prg.Nama as PRG, prd.Nama as PRD, agm.Nama as AGM, kel.Nama as KEL,
  kwn.Nama as KWN");

    $tglLahir = FormatTanggal($mhsw['TanggalLahir']);
    $foto = $mhsw['Foto'];
    $foto = (empty($foto))? "img/20190316143146-blank.png" : $foto;
    $PA = AmbilOneField('dosen', "Login", $mhsw['PenasehatAkademik'], "concat(Nama, ', ', Gelar)");
    echo "<p align=center>
    <table class=box cellspacing=1 cellpadding=4 width=100%>
    <tr><td class=ttl colspan=4>Data Pribadi</td></tr>
    <tr><td class=inp4>Nomer Pokok Mhsw</td>
        <td class=ul>$mhsw[MhswID]</td>
        <td class=inp4>Program</td>
        <td class=ul>$mhsw[ProgramID] - $mhsw[PRG]</td>
        <td class=box rowspan=20 valign=top><img src='$foto' vspace=10 hspace=10 height=150 width=120></td>
        </tr>
    <tr><td class=inp4>Nama Mhsw</td>
        <td class=ul>$mhsw[Nama]</td>
        <td class=inp4>Program Studi</td>
        <td class=ul>$mhsw[ProdiID] - $mhsw[PRD]</td>
        </tr>
    <tr><td class=inp4>Angkatan</td>
        <td class=ul>$mhsw[TahunID]</td>
        <td class=inp4>Status Mhsw</td>
        <td class=ul>$mhsw[STA] &nbsp;</td>
        </tr>
    <tr><td class=inp4>Batas Studi</td>
        <td class=ul>$mhsw[BatasStudi] &nbsp;</td>
        <td class=inp4>Pembimbing Akademik</td>
        <td class=ul>$PA &nbsp;</td>
        </tr>
    <tr><td class=inp4>Jenis Kelamin</td>
        <td class=ul>$mhsw[KEL] &nbsp;</td>
        <td class=inp4>Agama</td>
        <td class=ul>$mhsw[AGM] &nbsp;</td>
        </tr>
    <tr><td class=inp4>Tempat, Tgl Lahir</td>
        <td class=ul>$mhsw[TempatLahir], $tglLahir</td>
        <td class=inp4>Status Perkawinan</td>
        <td class=ul>$mhsw[KWN] &nbsp;</td>
        </tr>
    <tr><td class=ttl colspan=4>Alamat</td></tr>
    <tr><td class=inp4>E-mail</td>
        <td class=ul colspan=3>$mhsw[Email] &nbsp;</td>
        </tr>
    <tr><td class=inp4># Telepon</td>
        <td class=ul>$mhsw[Telephone] &nbsp;</td>
        <td class=inp4># Handphone</td>
        <td class=ul>$mhsw[Handphone] &nbsp;</td>
        </tr>
    <tr><td class=inp4>Alamat</td>
        <td class=ul colspan=3>$mhsw[Alamat] &nbsp;</td>
        </tr>
    <tr><td class=inp4>RT/RW</td>
        <td class=ul>$mhsw[RT]/$mhsw[RW]</td>
        <td class=inp4>Kode Pos</td>
        <td class=ul>$mhsw[KodePos] &nbsp;</td>
        </tr>
    <tr><td class=inp4>Kota</td>
        <td class=ul>$mhsw[Kota] &nbsp;</td>
        <td class=inp4>Propinsi</td>
        <td class=ul>$mhsw[Propinsi] &nbsp;</td>
        </tr>
    <tr><td class=inp4>Negara</td>
        <td class=ul colspan=3>$mhsw[Negara] &nbsp;</td>
        </tr>
    
    <tr><td class=ttl colspan=4>Orang Tua</td></tr>
    <tr><td class=inp4>Nama Ayah</td>
        <td class=ul>$mhsw[NamaAyah] &nbsp;</td>
        <td class=inp4>Nama Ibu</td>
        <td class=ul4>$mhsw[NamaIbu] &nbsp;</td>
        </tr>
    <tr><td class=inp4># Telepon</td>
        <td class=ul>$mhsw[TeleponOrtu] &nbsp;</td>
        <td class=inp4># Handphone</td>
        <td class=ul>$mhsw[HandphoneOrtu] &nbsp;</td>
        </tr>
    </table></p>";
?>
</div>
          </div>
      </div>
    </div>
</section> 