<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
  session_start();
  include_once "pengembang.lib.php";
  include_once "sambungandb.php";
  include_once "setting_awal.php";
  include_once "check_setting.php";

  $mdlid    = GainVariabelx('mdlid');
  $loadTime = date('m d, Y H:i:s');

    //leweh add
    $_snm = session_name(); 
    $_sid = session_id();
    $_arr = array();
    $strLevel = ".$_LevelID.";
    //echo"saa$_snm $_sid";
    //end leweh add
  
  function cekSession(){
    global $koneksi;
    $s = "select * from session where sessionId = '".$_SESSION['_Session']."' and user = '".$_SESSION['_Login']."'";
    $q = mysqli_query($koneksi, $s);
    $w = mysqli_fetch_array($q);
        if (mysqli_num_rows($q) == 0){
          $s2 = "insert into session (sessionId,user,address,sessionTime) values ('".$_SESSION['_Session']."', '".$_SESSION['_Login']."', '".$_SERVER['REMOTE_ADDR']."', '".time()."')";
          $q2 = mysqli_query($koneksi, $s2);
        } else {
          $s2 = "update session set sessionTime = '".time()."' where sessionId = '".$w['sessionId']."'";
          $q2 = mysqli_query($koneksi, $s2);	
        }
  }

  //leweh add
  if (!empty($_SESSION['_Session'])) {     
           
      // $NamaLevel = AmbilOneField('level', 'LevelID', $_SESSION['_LevelID'], 'Nama');
      $NamaLevel = mysqli_fetch_array(mysqli_query($koneksi, "select * from level where LevelID='$_SESSION[_LevelID]'"));
      if (!empty($_SESSION['mdlid'])) {
        //$_strMDLID = AmbilOneField('mdl', "MdlID", $_SESSION['mdlid'], "concat(MdlGrpID, ' &raquo; ', Nama)");
        $_strMDLID = mysqli_fetch_array(mysqli_query($koneksi, "select * from mdl where MdlID='$_SESSION[mdlid]'"));
      }
      $_SESSION['username'] = $_SESSION['_Login'];
      $tombolChat = "<div id='onlineUser' onClick='javascript:openUser()'></div>";
      cekSession();       		 
 
  } else {
  echo '<script>
    $("#userbox").css("display","none");
  </script>';
}

//end leweh add
 ?>


  <HTML xmlns="http://www.w3.org/1999/xhtml">
  <head><TITLE><?php echo $Organisasix; ?></TITLE>
  <!-- leweh add -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="author" content="Administrator">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="asset/admin_crew/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="asset/admin_crew/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <!-- <link rel="stylesheet" href="asset/admin_crew/dist/css/adminlte.min.css"> -->
  <link rel="stylesheet" href="asset/admin_crew/dist/css/style.css">
  <link rel="stylesheet" href="asset/summernote/summernote-bs4.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="asset/admin_crew/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style type="text/css">.checkbox-scroll { border:1px solid #ccc; width:100%; height: 114px; padding-left:8px; overflow-y: scroll; }</style>
  <!--<script src="asset/ckeditor/ckeditor.js"></script>-->
  <link rel="stylesheet" href="asset/admin_crew/plugins/daterangepicker/daterangepicker-bs3.css">
  <script type="text/javascript" src="asset/admin_crew/plugins/daterangepicker/moment.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.4.0.min.js"></script>

  <link rel="stylesheet" href="asset/admin_crew/plugins/daterangepicker/daterangepicker.css">
  <!-- end leweh add -->

  <!-- leweh add combo -->
    <link rel="stylesheet" href="asset/admin_crew/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="asset/admin_crew/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

  <!-- leweh add it make combo search tidy bro Theme style -->
  <link rel="stylesheet" href="asset/admin_crew/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<!-- end hery add  -->


  <!-- leweh add all -->
  <link rel="stylesheet" href="assets/admin/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="assets/admin/plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- sweetalert -->
  <script src="assets/sweetalert/js/sweetalert.min.js"></script>
  <!-- angular -->
  <script src="assets/angular/angular.min.js"></script>  
  <link rel="stylesheet" type="text/css" href="assets/sweetalert/css/sweetalert.css">
  <!-- jQuery -->
  <!-- <script src="assets/admin/plugins/jquery/jquery.min.js"></script> -->
  <script src="assets/jquery-ui/external/jquery/jquery.js"></script>
  <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
  <!-- JQUERY CHAINED -->
  <script src="assets/js/jquery.chained.min.js" type="text/javascript"></script> 
  <!-- jQuery UI 1.11.4 -->
  <script src="assets/admin/plugins/jquery-ui/jquery-ui.min.js"></script>
  <link href="assets/admin/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
  <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
  
  <!-- Viewer js -->
  <script src="assets/viewerjs/pdf.js"></script>
  <!-- TIMEPICKER -->
  <script src="assets/timepicker/timepicker.min.js"></script>
  <link href="assets/timepicker/timepicker.min.css" rel="stylesheet"/>
  <script src="assets/tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
  <!--  -->
</head>


  <!-- leweh add -->
  <!-- Navbar -->

  <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <div class="wrapper">
  
  
  <?php if (!empty($_SESSION['_Login'])){ ?>
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <?php include "include/navbar.php"; ?>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar elevation-4 sidebar-dark-success">
    <?php 
    //include "include/sidebar.php"; 
    
    if (!empty($_SESSION['_LevelID'])){
      if ($_SESSION['_LevelID']=='1'){
        include"include/sidebar.php";
      }
      else if ($_SESSION['_LevelID']=='120'){
        include"include/sidebarmhs.php";
      }
      else if ($_SESSION['_LevelID']=='100'){
        include"include/sidebardos.php";
      }
      else if ($_SESSION['_LevelID']=='141'){
        include"include/sidebarppmi.php";
      }
      else if ($_SESSION['_LevelID']=='70'){
        include"include/sidebarkeu.php";
      }
      else{
        include"include/sidebarx.php";
      }
    }  
    
    ?>
    
  </aside>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><b style='color:purple;font-family:Tahoma'>
           
            &nbsp;SISFO -</b> <b style='color:#ff5113;font-family:Tahoma'><?php echo $Organisasix; ?>
         
          </b></h1>
          </div>
          <div class="col-sm-6">
          <ol class='breadcrumb float-sm-right'>
				<li class='breadcrumb-item'>Hi, 

        
            <b style='color:purple'> <?php echo $_SESSION['_Nama']; ?></b><span> (<?php echo $NamaLevel['Nama']; ?>)</b></li>
          </ol>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
  <!-- end leweh add -->


  <!-- Main content -->
    <section class="content">
      <div class="container-fluid">     
            <?php
            if (!empty($_SESSION['_Session'])) {
                //fungsikan untuk akses menu side bar
                //if (empty($_REQUEST['BypassMenu'])) include "menu_sistem.php";
              }
            
              if (file_exists($_SESSION['ndelox'].'.php')) {
                echo "";
                // check one by one per level
                $x_allow = "select * from mdl where Script='$_SESSION[ndelox]'";
                $w_allow = mysqli_query($koneksi, $x_allow); $ktmu = -1;
                if (mysqli_num_rows($w_allow) > 0) {
                  while ($data_allow = mysqli_fetch_array($w_allow)) {
                    $posx = strpos($data_allow['LevelID'], ".$_SESSION[_LevelID].");
                    if ($posx === false) {}
                    else $ktmu = 1;
                  }
                  if ($ktmu <= 0) {
                    echo PesanError("Anda Tidak Diperkenankan Login",
                      "Pastikan Anda Memilki Wewenang Untuk Mengakses Data.<br />
                      Hubungi Sistem Administrator untuk Memiliki User Account.
                      <hr size=1>
                      <a class='btn btn-danger btn-xl' href='?ndelox=&slnt=login_go&slntx=login_out'>Logout</a>");
                  }
                  else include_once $_SESSION['ndelox'].'.php';
                } else include_once $_SESSION['ndelox'].'.php';
                include_once "disconnectdb.php";
              }
              else echo PesanError('Error', "Modul tidak ketemu. Hubungi Administrator Sistem!<hr size=1 color=silver>
              Pilihan: <a href='?ndelox=&KodeID=$_SESSION[KodeID]'>Kembali</a>");
              echo "</div>";
            ?>

      </div>
    </section>
  </div>

  <!-- end leweh add -->

  <!-- leweh add -->
<!-- Main Footer -->
<!-- Main Footer -->

<?php 
if (!empty($_SESSION['_Login'])){
?>
<footer class="main-footer text-sm">
    <strong>Developed by <?php echo $Organisasix; ?> &copy; 2021-<?php echo date('Y'); ?> <a target='_BLANK' href="https://teknokrat.ac.id/"><?php //echo $iden['Nama']; ?></a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
    <a style='color:#cecece !important' href="/upgrade"><b>Version</b> 3.0.0</a>
    </div>
  </footer>
</div>
<?php 
}
?>
<!-- ./wrapper -->
<script src="asset/admin_crew/plugins/daterangepicker/daterangepicker.js"></script>
<script type="text/javascript">
$('#rangepicker').daterangepicker();
</script>
<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->

<!-- Bootstrap -->
<script src="asset/admin_crew/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="asset/admin_crew/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- DataTables -->
<script src="asset/admin_crew/plugins/datatables/jquery.dataTables.js"></script>
<script src="asset/admin_crew/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- AdminLTE App -->
<script src="asset/admin_crew/dist/js/adminlte.js"></script>
<!-- OPTIONAL SCRIPTS -->
<script src="asset/admin_crew/dist/js/demo.js"></script>
<!-- PAGE PLUGINS -->
<script src="asset/summernote/summernote-bs4.min.js"></script>
<script>
  $(function () {
    // Summernote
    $('#editor1').summernote()
  })
</script>

<!-- jQuery Mapael -->
<script src="asset/admin_crew/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="asset/admin_crew/plugins/raphael/raphael.min.js"></script>
<script src="asset/admin_crew/plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="asset/admin_crew/plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="asset/admin_crew/plugins/chart.js/Chart.min.js"></script>
<!-- PAGE SCRIPTS -->

<script src="asset/admin_crew/dist/js/jquery.nestable.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<!-- leweh add -->
<!-- Select2 -->
<script src="asset/admin_crew/plugins/select2/js/select2.full.min.js"></script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  })
</script>

<!-- leweh add -->
<script>
tinymce.init({
  selector: '.simple',
  menubar: false,
  plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table paste code help wordcount'
  ],
  toolbar: 'undo redo | formatselect | ' +
  'bold italic backcolor | alignleft aligncenter ' +
  'alignright alignjustify | bullist numlist outdent indent | ' +
  'removeformat | help',
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});
</script>

<?php 
$sek  = date('Y');
$awal = $sek-100;
?>
<script>
  $( ".datepicker" ).datepicker({
    inline: true,
    changeYear: true,
    changeMonth: true,
    dateFormat: "dd-mm-yy",
    yearRange: "<?php echo $awal ?>:<?php $tahundepan = date('Y')+2; echo $tahundepan; ?>"
  });

  $( ".tanggal" ).datepicker({
    inline: true,
    changeYear: true,
    changeMonth: true,
    dateFormat: "dd-mm-yy",
    yearRange: "<?php echo $awal ?>:<?php $tahundepan = date('Y')+2; echo $tahundepan; ?>"
  });

  $( ".tanggalan" ).datepicker({
    inline: true,
    changeYear: true,
    changeMonth: true,
    dateFormat: "dd-mm-yy",
    yearRange: "<?php echo $awal ?>:<?php $tahundepan = date('Y')+2; echo $tahundepan; ?>"
  });

</script>
<script>
@if ($message = Session::get('sukses'))
// Notifikasi
swal ( "Berhasil" ,  "<?php echo $message ?>" ,  "success" )
@endif

@if ($message = Session::get('warning'))
// Notifikasi
swal ( "Oops.." ,  "<?php echo $message ?>" ,  "warning" )
@endif

// Popup Delete
$(document).on("click", ".delete-link", function(e){
  e.preventDefault();
  url = $(this).attr("href");
  swal({
    title:"Yakin akan menghapus data ini?",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: 'btn btn-danger',
    cancelButtonClass: 'btn btn-success',
    buttonsStyling: false,
    confirmButtonText: "Delete",
    cancelButtonText: "Cancel",
    closeOnConfirm: false,
    showLoaderOnConfirm: true,
  },
  function(isConfirm){
    if(isConfirm){
      $.ajax({
        url: url,
        success: function(resp){
          window.location.href = url;
        }
      });
    }
    return false;
  });
});
// Popup disable
$(document).on("click", ".disable-link", function(e){
  e.preventDefault();
  url = $(this).attr("href");
  swal({
    title:"Yakin akan menonaktifkan data ini?",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: 'btn btn-danger',
    cancelButtonClass: 'btn btn-success',
    buttonsStyling: false,
    confirmButtonText: "Non Aktifkan",
    cancelButtonText: "Cancel",
    closeOnConfirm: false,
    showLoaderOnConfirm: true,
  },
  function(isConfirm){
    if(isConfirm){
      $.ajax({
        url: url,
        success: function(resp){
          window.location.href = url;
        }
      });
    }
    return false;
  });
});

// Popup approval
$(document).on("click", ".approval-link", function(e){
  e.preventDefault();
  url = $(this).attr("href");
  swal({
    title:"Anda yakin ingin menyetujui data ini?",
    type: "info",
    showCancelButton: true,
    confirmButtonClass: 'btn btn-danger',
    cancelButtonClass: 'btn btn-success',
    buttonsStyling: false,
    confirmButtonText: "Ya, Setujui",
    cancelButtonText: "Cancel",
    closeOnConfirm: false,
    showLoaderOnConfirm: true,
  },
  function(isConfirm){
    if(isConfirm){
      $.ajax({
        url: url,
        success: function(resp){
          window.location.href = url;
        }
      });
    }
    return false;
  });
});
</script>
</div>
</div>
<!-- /.card-body -->
</div>
<!-- /.card -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>

<!-- Bootstrap 4 -->
<script src="assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="assets/admin/plugins/select2/js/select2.full.min.js"></script>
<!-- DataTables -->
<script src="assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="assets/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- Sparkline -->
<script src="assets/admin/plugins/sparklines/sparkline.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="assets/admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="assets/admin/plugins/summernote/summernote-bs4.min.js"></script>
<!-- tinymce -->
  <script src="assets/ckeditor/ckeditor.js" type="text/javascript"></script>
<!-- overlayScrollbars -->
<script src="assets/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<!-- pace-progress -->
<script src="assets/admin/plugins/pace-progress/pace.min.js"></script>
<script src="assets/admin/dist/js/adminlte.js"></script>
<script>
    CKEDITOR.replace('editorku', {
      height: 60,
      // Define the toolbar groups as it is a more accessible solution.
      toolbarGroups: [{
          "name": "basicstyles",
          "groups": ["basicstyles"]
        },
        {
          "name": "links",
          "groups": ["links"]
        },
        {
          "name": "paragraph",
          "groups": ["list", "blocks"]
        },
        {
          "name": "document",
          "groups": ["mode"]
        },
        
      ],
      // Remove the redundant buttons from toolbar groups defined above.
      removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar'
    });
    // Tes
    
// Replace the <textarea id="editor1"> with a CKEditor 4
// instance, using default configuration.
CKEDITOR.replace( 'kontenku',
      {
        filebrowserBrowseUrl : '{{ asset("assets/ckeditor/filemanager/dialog.php?type=2&editor=ckeditor&fldr=") }}',
        filebrowserUploadUrl : '{{ asset("assets/ckeditor/filemanager/dialog.php?type=2&editor=ckeditor&fldr=") }}',
        filebrowserImageBrowseUrl : '{{ asset("assets/ckeditor/filemanager/dialog.php?type=1&editor=ckeditor&fldr==") }}'
  } 
);
</script>
<!-- Page Script -->
<script>
  $(function () {
     //Initialize Select2 Elements
    //Initialize Select2 Elements
    $('.select2').select2({
      theme: 'bootstrap4'
    })
    
    $('.mselect2').select2({
      dropdownParent: $('.Tambah')
    });
   
    $('.checkbox-toggle').click(function () {
      var clicks = $(this).data('clicks')
      if (clicks) {
        //Uncheck all checkboxes
        $('.mailbox-messages input[type=\'checkbox\']').prop('checked', false)
        $('.checkbox-toggle .far.fa-check-square').removeClass('fa-check-square').addClass('fa-square')
      } else {
        //Check all checkboxes
        $('.mailbox-messages input[type=\'checkbox\']').prop('checked', true)
        $('.checkbox-toggle .far.fa-square').removeClass('fa-square').addClass('fa-check-square')
      }
      $(this).data('clicks', !clicks)
    })

    //Handle starring for glyphicon and font awesome
    $('.mailbox-star').click(function (e) {
      e.preventDefault()
      //detect type
      var $this = $(this).find('a > i')
      var glyph = $this.hasClass('glyphicon')
      var fa    = $this.hasClass('fa')

      //Switch states
      if (glyph) {
        $this.toggleClass('glyphicon-star')
        $this.toggleClass('glyphicon-star-empty')
      }

      if (fa) {
        $this.toggleClass('fa-star')
        $this.toggleClass('fa-star-o')
      }
    })
  })
</script>
<!-- AdminLTE for demo purposes -->
<script src="assets/admin/dist/js/demo.js"></script>
<!-- page script -->
<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
    });
  });

  </script>
<!-- end leweh add -->

<?php 
$sek  = date('Y');
$awal = $sek-100;
?>
<script>
  $( ".datepicker" ).datepicker({
    inline: true,
    changeYear: true,
    changeMonth: true,
    dateFormat: "dd-mm-yy",
    yearRange: "<?php echo $awal ?>:<?php $tahundepan = date('Y')+2; echo $tahundepan; ?>"
  });

  $( ".tanggal" ).datepicker({
    inline: true,
    changeYear: true,
    changeMonth: true,
    dateFormat: "dd-mm-yy",
    yearRange: "<?php echo $awal ?>:<?php $tahundepan = date('Y')+2; echo $tahundepan; ?>"
  });

  $( ".tanggalan" ).datepicker({
    inline: true,
    changeYear: true,
    changeMonth: true,
    dateFormat: "dd-mm-yy",
    yearRange: "<?php echo $awal ?>:<?php $tahundepan = date('Y')+2; echo $tahundepan; ?>"
  });

</script>
<!-- end leweh add -->

<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
    });
  });
</script>
  <!-- end leweh add -->
  <script>
  /** add active class and stay opened when selected */
var url = window.location;

// for sidebar menu entirely but not cover treeview
$('ul.nav-sidebar a').filter(function() {
    return this.href == url;
}).addClass('active');

// for treeview
$('ul.nav-treeview a').filter(function() {
    return this.href == url;
}).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open') .prev('a').addClass('active');
</script>

<script>
tinymce.init({
  selector: '.simple',
  menubar: false,
  plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table paste code help wordcount'
  ],
  toolbar: 'undo redo | formatselect | ' +
  'bold italic backcolor | alignleft aligncenter ' +
  'alignright alignjustify | bullist numlist outdent indent | ' +
  'removeformat | help',
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});
</script>

<?php 
$sek  = date('Y');
$awal = $sek-100;
?>
<script>
  $( ".datepicker" ).datepicker({
    inline: true,
    changeYear: true,
    changeMonth: true,
    dateFormat: "dd-mm-yy",
    yearRange: "<?php echo $awal ?>:<?php $tahundepan = date('Y')+2; echo $tahundepan; ?>"
  });

  $( ".tanggal" ).datepicker({
    inline: true,
    changeYear: true,
    changeMonth: true,
    dateFormat: "dd-mm-yy",
    yearRange: "<?php echo $awal ?>:<?php $tahundepan = date('Y')+2; echo $tahundepan; ?>"
  });

  $( ".tanggalan" ).datepicker({
    inline: true,
    changeYear: true,
    changeMonth: true,
    dateFormat: "dd-mm-yy",
    yearRange: "<?php echo $awal ?>:<?php $tahundepan = date('Y')+2; echo $tahundepan; ?>"
  });

</script>
<script>
@if ($message = Session::get('sukses'))
// Notifikasi
swal ( "Berhasil" ,  "<?php echo $message ?>" ,  "success" )
@endif

@if ($message = Session::get('warning'))
// Notifikasi
swal ( "Oops.." ,  "<?php echo $message ?>" ,  "warning" )
@endif

// Popup Delete
$(document).on("click", ".delete-link", function(e){
  e.preventDefault();
  url = $(this).attr("href");
  swal({
    title:"Yakin akan menghapus data ini?",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: 'btn btn-danger',
    cancelButtonClass: 'btn btn-success',
    buttonsStyling: false,
    confirmButtonText: "Delete",
    cancelButtonText: "Cancel",
    closeOnConfirm: false,
    showLoaderOnConfirm: true,
  },
  function(isConfirm){
    if(isConfirm){
      $.ajax({
        url: url,
        success: function(resp){
          window.location.href = url;
        }
      });
    }
    return false;
  });
});
// Popup disable
$(document).on("click", ".disable-link", function(e){
  e.preventDefault();
  url = $(this).attr("href");
  swal({
    title:"Yakin akan menonaktifkan data ini?",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: 'btn btn-danger',
    cancelButtonClass: 'btn btn-success',
    buttonsStyling: false,
    confirmButtonText: "Non Aktifkan",
    cancelButtonText: "Cancel",
    closeOnConfirm: false,
    showLoaderOnConfirm: true,
  },
  function(isConfirm){
    if(isConfirm){
      $.ajax({
        url: url,
        success: function(resp){
          window.location.href = url;
        }
      });
    }
    return false;
  });
});

// Popup approval
$(document).on("click", ".approval-link", function(e){
  e.preventDefault();
  url = $(this).attr("href");
  swal({
    title:"Anda yakin ingin menyetujui data ini?",
    type: "info",
    showCancelButton: true,
    confirmButtonClass: 'btn btn-danger',
    cancelButtonClass: 'btn btn-success',
    buttonsStyling: false,
    confirmButtonText: "Ya, Setujui",
    cancelButtonText: "Cancel",
    closeOnConfirm: false,
    showLoaderOnConfirm: true,
  },
  function(isConfirm){
    if(isConfirm){
      $.ajax({
        url: url,
        success: function(resp){
          window.location.href = url;
        }
      });
    }
    return false;
  });
});
</script>
</div>
</div>
<!-- /.card-body -->
</div>
<!-- /.card -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>

<!-- Bootstrap 4 -->
<script src="assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="assets/admin/plugins/select2/js/select2.full.min.js"></script>
<!-- DataTables -->
<script src="assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="assets/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- Sparkline -->
<script src="assets/admin/plugins/sparklines/sparkline.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="assets/admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="assets/admin/plugins/summernote/summernote-bs4.min.js"></script>
<!-- tinymce -->
  <script src="assets/ckeditor/ckeditor.js" type="text/javascript"></script>
<!-- overlayScrollbars -->
<script src="assets/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<!-- pace-progress -->
<script src="assets/admin/plugins/pace-progress/pace.min.js"></script>
<script src="assets/admin/dist/js/adminlte.js"></script>
<script>
    CKEDITOR.replace('editorku', {
      height: 60,
      // Define the toolbar groups as it is a more accessible solution.
      toolbarGroups: [{
          "name": "basicstyles",
          "groups": ["basicstyles"]
        },
        {
          "name": "links",
          "groups": ["links"]
        },
        {
          "name": "paragraph",
          "groups": ["list", "blocks"]
        },
        {
          "name": "document",
          "groups": ["mode"]
        },
        
      ],
      // Remove the redundant buttons from toolbar groups defined above.
      removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar'
    });
    // Tes
    
// Replace the <textarea id="editor1"> with a CKEditor 4
// instance, using default configuration.
CKEDITOR.replace( 'kontenku',
      {
        filebrowserBrowseUrl : '{{ asset("assets/ckeditor/filemanager/dialog.php?type=2&editor=ckeditor&fldr=") }}',
        filebrowserUploadUrl : '{{ asset("assets/ckeditor/filemanager/dialog.php?type=2&editor=ckeditor&fldr=") }}',
        filebrowserImageBrowseUrl : '{{ asset("assets/ckeditor/filemanager/dialog.php?type=1&editor=ckeditor&fldr==") }}'
  } 
);
</script>
<!-- Page Script -->
<script>
  $(function () {
     //Initialize Select2 Elements
    //Initialize Select2 Elements
    $('.select2').select2({
      theme: 'bootstrap4'
    })
    
    $('.mselect2').select2({
      dropdownParent: $('.Tambah')
    });
   
    $('.checkbox-toggle').click(function () {
      var clicks = $(this).data('clicks')
      if (clicks) {
        //Uncheck all checkboxes
        $('.mailbox-messages input[type=\'checkbox\']').prop('checked', false)
        $('.checkbox-toggle .far.fa-check-square').removeClass('fa-check-square').addClass('fa-square')
      } else {
        //Check all checkboxes
        $('.mailbox-messages input[type=\'checkbox\']').prop('checked', true)
        $('.checkbox-toggle .far.fa-square').removeClass('fa-square').addClass('fa-check-square')
      }
      $(this).data('clicks', !clicks)
    })

    //Handle starring for glyphicon and font awesome
    $('.mailbox-star').click(function (e) {
      e.preventDefault()
      //detect type
      var $this = $(this).find('a > i')
      var glyph = $this.hasClass('glyphicon')
      var fa    = $this.hasClass('fa')

      //Switch states
      if (glyph) {
        $this.toggleClass('glyphicon-star')
        $this.toggleClass('glyphicon-star-empty')
      }

      if (fa) {
        $this.toggleClass('fa-star')
        $this.toggleClass('fa-star-o')
      }
    })
  })
</script>
<!-- AdminLTE for demo purposes -->
<script src="assets/admin/dist/js/demo.js"></script>
<!-- page script -->
<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
    });
  });

  </script>
</BODY>

</HTML>
