<!-- Left navbar links -->
<ul class="navbar-nav">
    <li class="nav-item">
    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item">
    <a class="nav-link" href=""> Home</a>
    </li>
</ul>


<!-- Right navbar links -->
<ul class="navbar-nav ml-auto">
    <!-- Messages Dropdown Menu -->
   
    <li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-comments"></i>
      
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <?php 
            echo "<a class='dropdown-item' href=''>
                    <div class='media'>
                        <img src='asset/foto_user/blank.png'  class='img-size-50 mr-3 img-circle' alt='User Image'>
                        <div class='media-body'>
                            <h3 class='dropdown-item-title'>
                             
                                <span class='float-right text-sm text-color'><i class='fas fa-star'></i></span> 
                            </h3>
                            <p class='text-sm'>Profil</p>
                            <p class='text-sm text-muted'><i class='far fa-clock mr-1'></i> </p>
                        </div>
                    </div>
                    </a>
                    <div class='dropdown-divider'></div>";
   
        ?>
        <a href="" class="dropdown-item dropdown-footer">See All Messages</a>
    </div>
    </li>

    <!-- Notifications Dropdown Menu -->
    <?php 
   // $idx = encrypt_url($this->session->Login);
    ?>
    <li class="nav-item">
    <a class="nav-link" href="?ndelox=login_go&lungo=login_out" title="Log Out"><i class="nav-icon fas fa-unlock"></i></a></li>
    <li class="nav-item">
    <a class="nav-link" data-widget="control-sidebar" data-slide="true" target='_BLANK' href=""><i
        class="fas fa-cog"></i></a>
    </li>
</ul>
