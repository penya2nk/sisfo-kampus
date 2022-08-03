<!-- Brand Logo -->
<a href="" class="brand-link navbar-light">
      <img src="asset/logo/logo_stmik.png" alt="Admin Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light" style='color:#000'><b style='color:#ff5113;font-family:Tahoma'>UTI ADMIN </b></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <?php 

       
          echo "<div class='image'>
                  <img src='asset/foto_user/blank.png' class='img-circle elevation-2' alt=''>
                </div>
                <div class='info'>
                  <a href='#' class='d-block'><b>$_SESSION[_Nama]<br> ( $_SESSION[_Login] )</b><br> <span class='badge badge-success'>Online</span></a>
                </div>";
        ?>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat menu-open nav-legacy nav-compact" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-header">MAIN MENU</li>
          <li class="nav-item"><a class="nav-link" href="?ndelox=dashboard&tahun=<?php echo"".date('Y')."";?>"><i class="nav-icon fas fa-tachometer-alt"></i> <p>Dashboard <span class="right badge badge-danger">Welcome</span></p></a></li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>Menu Utama <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
            <li class='nav-item'><a class='nav-link' href="?ndelox=master/identity&mdlid=10&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Master Identitas</p></a></li>
            <li class='nav-item'><a class='nav-link' href="?ndelox=master/officer&mdlid=90&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Master Pejabat</p></a></li>
          </ul>
          </li>
        

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Master <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
                <li class='nav-item'><a class='nav-link' href="?ndelox=master/dosenx&mdlid=23&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Master Dosen</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=master/mhs&mdlid=28&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Master Mahasiswa</p></a></li>

                <li class='nav-item'><a class='nav-link' href="?ndelox=master/matkul&mdlid=20&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Master Matakuliah</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=master/ruang&mdlid=12&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Master Ruangan</p></a></li>            
                <li class='nav-item'><a class='nav-link' href="?ndelox=master/fakultas&mdlid=9&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Master Fakultas</p></a></li>            
                <li class='nav-item'><a class='nav-link' href="?ndelox=master/programs&mdlid=8&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Master Program</p></a></li>            
                <li class='nav-item'><a class='nav-link' href="?ndelox=master/identity&mdlid=8&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Identitas Institusi</p></a></li>            
                <li class='nav-item'><a class='nav-link' href="?ndelox=master/officer&mdlid=90&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Pejabat</p></a></li>            
                <li class='nav-item'><a class='nav-link' href="?ndelox=master/account&mdlid=39&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Rekening Institusi</p></a></li>            
                <li class='nav-item'><a class='nav-link' href="?ndelox=master/college&mdlid=11&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Kampus</p></a></li>            
                <li class='nav-item'><a class='nav-link' href="?ndelox=master/biaya_potongan&mdlid=26&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Master Biaya & Potongan</p></a></li> 
                <li class='nav-item'><a class='nav-link' href="?ndelox=master/tahunakademik&mdlid=26&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Master Tahun Akademik</p></a></li>            

              </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>PMB <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
                <li class='nav-item'><a class='nav-link' href="?ndelox=penmaba/pendaftar&mdlid=210&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Formulir Pendaftar</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=penmaba/penmabaformjual&mdlid=15&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Penjualan Formulir</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=penmaba/penmabaform&mdlid=2&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Formulir PMB</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=penmaba/penmabaformdaftar&mdlid=222&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Formulir Daftar</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=penmaba/penmabamhs&mdlid=222&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Peserta Test</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=penmaba/admpmbreg&mdlid=222&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Reg PMB</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=penmaba/admpmbinfoall&mdlid=238&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Info PMB Per Tahun</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=penmaba/asalsekolah&mdlid=215&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Asal Sekolah</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=penmaba/asalpt&mdlid=222&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Asal PT</p></a></li>
               
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Ka PMB <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
                <li class='nav-item'><a class='nav-link' href="?ndelox=penmaba/penmabasetupx&mdlid=1&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Setup PMB</p></a></li>
				        <li class='nav-item'><a class='nav-link' href="?ndelox=penmaba/penmabalulus&mdlid=172&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Penentuan Kelulusan</p></a></li>
             
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Biro Akademik <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
                <li class='nav-item'><a class='nav-link' href="?ndelox=academic/mahasiswa_baru&mdlid=19&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Mahasiswa Baru</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=academic/kelas&mdlid=217&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Kelas</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=academic/mhspindahprodi&mdlid=191&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Mhs Pindah Prodi</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=academic/generate.ipk&mdlid=56&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Proses Hitung IPK</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=academic/khstudi&mdlid=53&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Cetak Kartu Hasil Studi</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/transkrip_nilai_mhs&mdlid=197&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Transkrip Nilai</p></a></li>

            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Ka Biro Akademik <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
                <li class='nav-item'><a class='nav-link' href="?ndelox=academic/sorting_nim&mdlid=204&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Proses NIMSementara->NIM</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=academic/status_mahasiswa&mdlid=205&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Proses Status Mhs</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=master/koreksi_penilaian&mdlid=77&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Koreksi Nilai</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=academic/lapakd&mdlid=180&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Laporan Akademik</p></a></li>

            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Keuangan <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
                <li class='nav-item'><a class='nav-link' href="?ndelox=finance/proses_biaya_potongan&mdlid=192&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Proses Biaya Potongan</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=finance/pembayaran_mhs&mdlid=40&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Regis Ulang & Pembayaran</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=finance/biaya_potongan_mhs_angkatan&mdlid=177&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Biaya & Pot. Per Angkatan</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=academic_mhs&mdlid=232&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Keuangan Mahasiswa</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=finance/lapkeu&mdlid=181&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Laporan Keuangan</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=finance/admkeuangan&mdlid=181&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Pembayaran SPP</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=finance/admlapsppangkatan&mdlid=181&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>SPP Per Angkatan</p></a></li>
               
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Jurusan <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
            <li class='nav-item'><a class='nav-link' href="?ndelox=dep/schedule&mdlid=22&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Jadwal Kuliah</p></a></li>
            <li class='nav-item'><a class='nav-link' href="?ndelox=dep/jadwalx&mdlid=22&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Jadwal Kuliah Simple</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/krstudi&mdlid=42&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Kartu Rencana Studi</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/absensi&mdlid=63&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Absensi</p></a></li>
                
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/penilaian&mdlid=72&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Nilai Dosen</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=academic/khstudi&mdlid=53&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Kartu Hasil Studi</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/nilaitranskrip&mdlid=197&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Transkrip Nilai</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/lecturer_penasehat&mdlid=188&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Set Dosen Penasehat</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/uts&mdlid=209&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Penjadwalan UTS</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/uas&mdlid=184&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Penjadwalan UAS</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/komprehensif&mdlid=226&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Komprehensif</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/praktek&mdlid=207&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Manajemen Praktek Kerja</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/ta&mdlid=59&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Manajemen TA/Skripsi</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/mhswprasyarat&mdlid=176&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Mhs Yg Memenuhi Prasarat</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/konversipindahan&mdlid=190&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Konversi Mhs Pindahan</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=master/kalender&mdlid=212&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Kalender</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/uts.card&mdlid=223&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Cetak Kartu UTS</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/uas.card&mdlid=224&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Cetak Kartu UAS</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/srtaktif&mdlid=223&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Surat Aktif Kuliah</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/srtluluskuliah&mdlid=224&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Surat Keterangan Lulus</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/admbeasiswa&mdlid=223&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Beasiswa</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/mbkm&mdlid=224&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>MBKM</p></a></li>

              </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Dosen <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
            <li class='nav-item'><a class='nav-link' href="?ndelox=dep/jadwaldsnx&mdlid=38&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Jadwal Dosen</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/penilaian&mdlid=182&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Nilai Kuliah</p></a></li>

                <li class='nav-item'><a class='nav-link' href="?ndelox=lecturer/penasehat_ak&mdlid=189&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Penasehat Akademik</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=lecturer/lecturer_pass&mdlid=220&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Ubah Password</p></a></li>
               
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Mahasiswa <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
            <li class='nav-item'><a class='nav-link' href="?ndelox=dep/cekkehadiran&mdlid=153&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Cek Kehadiran</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/krs&mdlid=42&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>KRS</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=students/students_nilai&mdlid=43&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Nilai Semester</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=students/students.edt&mdlid=229&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Edit Profil</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=students/studpass&mdlid=179&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Ubah Password</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/khscetak2&mdlid=239&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>KHS & Transkrip</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/ceksesikhs&mdlid=157&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Cek Sesi KHS</p></a></li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Semester Pendek <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
            <li class='nav-item'><a class='nav-link' href="?ndelox=sp/datasp&mdlid=153&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Pengajuan SP</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=sp/nilaisp&mdlid=42&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Nilai SP</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=sp/khscetaksp&mdlid=43&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Cetak Kartu Hasil Studi</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=sp/nilaitranskripsp&mdlid=229&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Cetak Transkrip Nilai</p></a></li>
                
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Kerja Praktek <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
            <li class='nav-item'><a class='nav-link' href="?ndelox=kk/pjudulkpadm&mdlid=153&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Pengajuan Judul KP</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=kk/jadwalkp&mdlid=42&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Seminar Proposal KP</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=kk/hasilkp&mdlid=43&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Seminar Hasil KP</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=kk/prosesnilaihasilkp&mdlid=229&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Proses Nilai Hasil KP</p></a></li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Skripsi <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
            <li class='nav-item'><a class='nav-link' href="?ndelox=ta/pjudultaadm&mdlid=153&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Pengajuan Judul Skripsi</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=ta/jadwal_seminarproposalskripsi&mdlid=42&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Seminar Proposal Skripsi</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=ta/jadwalskripsi&mdlid=43&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Seminar Hasil Skripsi</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=ta/prosesnilaihasilskripsi&mdlid=229&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Proses Nilai Skripsi</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=ta/tugasakhir&mdlid=179&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Referensi Judul</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=ta/ujianprogram&mdlid=233&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Ujian Program</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/admyudisium&mdlid=157&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Yudisium</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=ta/sincronisasita&mdlid=157&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Sinkronisasi Skripsi</p></a></li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Dokumen SPMI <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
            <li class='nav-item'><a class='nav-link' href="?ndelox=spmi/adm_kebijakanmutuppmi&mdlid=153&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Kebijakan SPMI</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=spmi/adm_manualppmi&mdlid=42&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Manual SPMI</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=spmi/adm_formulirppmi&mdlid=43&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Standar SPMI</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=spmi/adm_formulirppmi&mdlid=229&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Formulir SPMI</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=spmi/adm_doksop_ppmi&mdlid=179&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>SOP</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=spmi/adm_doksnpt_ppmi&mdlid=233&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Dokumen SNPT</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=spmi/ppmi-dokinstitusiak&mdlid=157&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Dokumen Penting</p></a></li>
                
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Kepegawaian <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
                <li class='nav-item'><a class='nav-link' href="?ndelox=peg/list_pegawai&mdlid=155&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>4"><i class='far fa-circle nav-icon text-success'></i> <p>List Pegawai</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=peg/pelatihan.list&mdlid=235&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Pelatihan/Workshop</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=peg/admcutipeg&mdlid=234&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Pengajuan Cuti</p></a></li>
            
            </ul>
          </li>          
          
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Activa <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
                <li class='nav-item'><a class='nav-link' href="?ndelox=asset&mdlid=236&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Daftar Asset</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=kelompok.asset&mdlid=235&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Kelompok Asset</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=lokasi.asset&mdlid=234&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Lokasi Asset</p></a></li>
            
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Informasi <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
                <li class='nav-item'><a class='nav-link' href="?ndelox=inq.kalendar&mdlid=80&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Kalender Akademik</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=inq.jadwal&mdlid=81&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Jadwal Kuliah</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=angka.mahasiswa&mdlid=121&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Angka Mahasiswa</p></a></li>           
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Dokumen <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
                <li class='nav-item'><a class='nav-link' href="?ndelox=datadonlot&mdlid=80&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Download Dokumen</p></a></li>             
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Laporan <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
                <li class='nav-item'><a class='nav-link' href="?ndelox=reports/inq.kalendar&mdlid=80&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Laporan Akademik</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=reports/pmbangka&mdlid=81&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>PMB Dalam Angka</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=reports/monitorpmb&mdlid=121&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Monitor PMB</p></a></li>           
                <li class='nav-item'><a class='nav-link' href="?ndelox=reports/monitorpmb&mdlid=121&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Monitor PMB Rekap</p></a></li>           
                <li class='nav-item'><a class='nav-link' href="?ndelox=reports/angka.mahasiswa&mdlid=121&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Monitor PMB Rekap Prodi</p></a></li>           
                <li class='nav-item'><a class='nav-link' href="?ndelox=reports/angka.mahasiswa&mdlid=121&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Mahasiswa Dalam Angka</p></a></li>           
                <li class='nav-item'><a class='nav-link' href="?ndelox=reports/angka.mhsaktif&mdlid=121&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Mahasiswa Aktif Dalam Angka</p></a></li>           
                <li class='nav-item'><a class='nav-link' href="?ndelox=reports/angka.lulusan&mdlid=121&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Lulusan Dalam Angka</p></a></li>           
                <li class='nav-item'><a class='nav-link' href="?ndelox=reports/angka.mahasiswa&mdlid=121&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Rekapitulasi Lulusan</p></a></li>           
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/admlapbkddosen&mdlid=121&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Laporan BKD</p></a></li>                      
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>System <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
            <li class='nav-item'><a class='nav-link' href="?ndelox=yuser_setting&mdlid=4&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Change Password</p></a></li>
            <li class='nav-item'><a class='nav-link' href="?ndelox=systemlevel_usr&mdlid=5&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Setting User</p></a></li>
			        <li class='nav-item'><a class='nav-link' href="?ndelox=systemuser_all&mdlid=5&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Setting All Usr</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=system_akses&mdlid=4&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Admin Level</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=system_akses&mdlid=3&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Setting Akses</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=petunjuk&mdlid=123&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Alur Diagram System</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=petunjuk2&mdlid=227&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>IT ADMIN</p></a></li>
                
               
            </ul>
          </li>


          <li class="nav-item"><a class="nav-link" href="?ndelox=login_go&lungo=login_out"><i class="nav-icon fas fa-sign-out-alt"></i> <p>Logout</p></a></li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->