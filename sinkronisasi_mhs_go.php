<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
  session_start();
  include_once "pengembang.lib.php";
  include_once "sambungandb.php";
  include_once "setting_awal.php";
  include_once "check_setting.php";

  // function PRC2() {
  echo "<body bgcolor=#EEFFFF>";
  $prodi = strfilter($_GET['prodi']);
  $program  = strfilter($_GET['program']);
  
  $pos = $_SESSION['IPK'.$prodi.'POS'];
  $max = $_SESSION['IPK'.$prodi];
  $mhswid = $_SESSION['IPK-MhswID'.$prodi.$pos];
  $khsid = $_SESSION['IPK-KHSID'.$prodi.$pos];
  

  echo"Data $pos";
  // proses
  if (!empty($mhswid)) {
    echo "<p><b>$pos/$max</b><br />
      &raquo; $khsid &raquo; <font size=+2>$mhswid</font></p>";     
        $s = "select *
          from mhsw_from k
          where k.MhswID='$mhswid' order by MhswID ASC";
        $r = mysqli_query($koneksi, $s);
        $n = 0; $mk = '';
        while ($w = mysqli_fetch_array($r)) {
          //execute when the data not exist otherwise jump to next
          if ($mk != $w['MhswID']) {
            $mk = $w['MhswID'];       
            $s = "insert into mhsw
            (MhswID,
                Login,
                Password,
                PMBID,
                PMBFormJualID,
                ProgramID,
                ProdiID,
                Nama,
                StatusAwalID,
                StatusMhswID,
                Kelamin,
                Alamat,
                TahunID,
                TempatLahir,
                TanggalLahir,
                NamaAyah,
                NamaIbu,
                NIK,
                Kecamatan,
                Kelurahan,
                PasswordBro,
                Agama,
                Handphone,
                Kota,
                BIPOTID,
                AlamatAsal,
                KotaAsal,
                TanggalBuat,
                LoginBuat,
                BatasStudi,
                AsalSekolah,
                JenisSekolahID,
                AlamatSekolah,
                KotaSekolah,
                JurusanSekolah,
                TahunLulus,
                RT,
                RW,
                Email,
                RTAsal,
                RWAsal,
                WargaNegara,
                Kebangsaan,
                HandphoneOrtu,
                NoIjazah,
                TglIjazah,
                Propinsi,
                Negara,
                AgamaAyah,
                PendidikanAyah,
                PekerjaanAyah,
                HidupAyah,
                AgamaIbu,
                PendidikanIbu,
                PekerjaanIbu,
                HidupIbu,
                AlamatOrtu)
            values ('$w[MhswID]',
                '$w[MhswID]',
                '*6BB4837EB',
                '$w[PMBID]',
                '$w[PMBFormJualID]',
                '$w[ProgramID]',
                '$w[ProdiID]',
                '$w[Nama]',
                '$w[StatusAwalID]',
                'A',
                '$w[Kelamin]',
                '$w[Alamat]',
                '$thakademik',
                '$w[TempatLahir]',
                '$w[TanggalLahir]',
                '$w[NamaAyah]',
                '$w[NamaIbu]',
                '$w[NIK]',
                '$w[Kecamatan]',
                '$w[Kelurahan]',
                '9970f16668b0ce09b694293b5164ae2b211fb9a23e9026bb4d0d1aef370f192120dd5f5a8e78c06d57fa036de0975c09b528ea7dc49262aee10c3247e62964fa',
                '$w[Agama]',
                '$w[Handphone]',
                '$w[Kota]',
                '$w[BIPOTID]',
                '$w[AlamatAsal]',
                '$w[KotaAsal]',
                '".date('Y-m-d H:i:s')."',
                '$_SESSION[id]',
                '$BatasStudi',
                '$w[AsalSekolah]',
                '$w[JenisSekolahID]',
                '$w[AlamatSekolah]',
                '$w[KotaSekolah]',
                '$w[JurusanSekolah]',
                '$w[TahunLulus]',
                '$w[RT]',
                '$w[RW]',
                '$w[Email]',
                '$w[RTAsal]',
                '$w[RWAsal]',
                '$w[WargaNegara]',
                '$w[Kebangsaan]',
                '$w[HandphoneOrtu]',
                '$w[NoIjazah]',
                '$w[TglIjazah]',
                '$w[Propinsi]',
                '$w[Negara]',
                '$w[AgamaAyah]',
                '$w[PendidikanAyah]',
                '$w[PekerjaanAyah]',
                '$w[HidupAyah]',
                '$w[AgamaIbu]',
                '$w[PendidikanIbu]',
                '$w[PekerjaanIbu]',
                '$w[HidupIbu]',
                '$w[AlamatOrtu]')";
             $r = mysqli_query($koneksi, $s);
          }
        }
    
  }
  // refresh page
  if ($_SESSION['IPK'.$prodi.'POS'] < $_SESSION['IPK'.$prodi]) {
    echo "<script type='text/javascript'>window.onload=setTimeout('window.location.reload()', 2);</script>";
  }
  else {
    echo "<p>Proses Data sudah <font size=+2>SELESAI</font></p>";
  }
  $_SESSION['IPK'.$prodi.'POS']++;


?>
