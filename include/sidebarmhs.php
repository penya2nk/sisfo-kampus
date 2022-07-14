<!-- Brand Logo -->
<a href="/home" class="brand-link navbar-light">
      <img src="asset/logo/logo_stmik.png" alt="Admin Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light" style='color:#000'><b style='color:#ff5113;font-family:Tahoma'>PANEL MAHASISWA </b></span>
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
                  <a href='#' class='d-block'><b>$_SESSION[_Nama]<br> ( $_SESSION[_Login] ) </b><br><span class='badge badge-success'>Online</span></a>
                </div>";
        ?>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat menu-open nav-legacy nav-compact" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-header">MAIN MENU</li>
          <li class="nav-item"><a class="nav-link" href="?ndelox=dashboard&lungo=Sukses"><i class="nav-icon fas fa-tachometer-alt"></i> <p>Dashboard <span class="right badge badge-danger">Welcome</span></p></a></li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>Setting <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
                <li class='nav-item'><a class='nav-link' href="?ndelox=students/students.edt&mdlid=229&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Edit Profil</p></a></li>
				<li class='nav-item'><a class='nav-link' href="?ndelox=students/mhswisuda&act=aplodpoto&mdlid=229&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Ganti Foto</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=students/studpass&mdlid=179&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Ubah Password</p></a></li>
          </ul>
          </li>
        
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>KRS & Nilai <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
                <li class='nav-item'><a class='nav-link' href="?ndelox=dep/krstudi&mdlid=42&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>KRS</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=students/students_nilai&mdlid=43&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Nilai Semester</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=students/mhskhs&mdlid=233&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Cetak KHS</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=students/mhs-transkrip_nilai&mdlid=233&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Transkrip Nilai</p></a></li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Semester Pendek <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
                <li class='nav-item'><a class='nav-link' href="?ndelox=students/daftarsp&mdlid=146&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Pengajuan SP</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=students/nilaisp&mdlid=146&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Transkrip Nilai SP</p></a></li>
              
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Penelitian <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
                <li class='nav-item'><a class='nav-link' href="?ndelox=students/pjudulkpmhs&mdlid=146&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Pengajuan Judul KP</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=students/pjudulta&mdlid=146&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Pengajuan Judul Skripsi</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=students/mhstugasakhir&mdlid=146&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Rerefensi Judul</p></a></li>

            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Dokumen <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
                <li class='nav-item'><a class='nav-link' href="?ndelox=students/datadonlot&mdlid=146&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Download Berkas</p></a></li>
               
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Angket/Kuesioner <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
                <li class='nav-item'><a class='nav-link' href="?ndelox=students/ppmi-angketmhsvisi&mdlid=146&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Angket VMTS</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=students/ppmi-angketmhspbm&mdlid=146&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Angket PBM</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=students/ppmi-angketmhspjj&mdlid=146&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Angket PJJ</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=students/ppmi-angketmhslayanan&mdlid=179&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Angket Layanan Akademik</p></a></li>
               
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Informasi <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
                <li class='nav-item'><a class='nav-link' href="?ndelox=students/mhsjadwalleweh&mdlid=146&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Jadwal Kuliah</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=students/mhskhsleweh&mdlid=146&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Kartu Hasil Studi</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=students/mhshistoryleweh&mdlid=146&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>History Transkrip</p></a></li>
          
            </ul>
          </li>



          <li class="nav-item"><a class="nav-link" href="?ndelox=login_go&lungo=login_out"><i class="nav-icon fas fa-sign-out-alt"></i> <p>Logout</p></a></li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->