<!-- Brand Logo -->
<a href="/home" class="brand-link navbar-light">
      <img src="asset/logo/logo_stmik.png" alt="Admin Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light" style='color:#000'><b style='color:#ff5113;font-family:Tahoma'>PANEL PPMI </b></span>
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
          <li class="nav-item"><a class="nav-link" href=""><i class="nav-icon fas fa-tachometer-alt"></i> <p>Dashboard<span class="right badge badge-danger">Welcome</span></p></a></li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>User Profil<i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
            <li class='nav-item'><a class='nav-link' href="?ndelox=master/identitas&mdlid=10&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Update Biodata</p></a></li>
           
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
            <p>Angket/Kuesioner <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
            <li class='nav-item'><a class='nav-link' href="?ndelox=spmi/ppmi-angketadmvisi&mdlid=153&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Visi Misi</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=spmi/ppmi-angket&mdlid=42&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>PBM</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=spmi/ppmi-angketadmpjj&mdlid=43&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>PJJ</p></a></li>
                <li class='nav-item'><a class='nav-link' href="?ndelox=spmi/ppmi-angketadmlayanan&mdlid=229&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Pelayanan Akademik</p></a></li>

            
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Password <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
                <li class='nav-item'><a class='nav-link' href="?ndelox=lecturer/lecturer_pass&mdlid=179&prodi=<?php echo"$_SESSION[aksess]";?>&tahun=<?php echo"$_SESSION[tahun_akademik]";?>&<?php echo"$_snm";?>=<?php echo"$_sid";?>"><i class='far fa-circle nav-icon text-success'></i> <p>Ubah Password</p></a></li>              
            </ul>
          </li>



          <li class="nav-item"><a class="nav-link" href="?ndelox=login_go&lungo=login_out"><i class="nav-icon fas fa-sign-out-alt"></i> <p>Logout</p></a></li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->