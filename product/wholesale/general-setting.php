<?php include('include/usertypecheck.php');?>
<?php //include('include/permission.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks | General Setting</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/iconfonts/puse-icons-feather/feather.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
  <link rel="stylesheet" href="css/parsley.css">
  <!-- endinject -->
  
   <!-- plugin css for this page -->
  <link rel="stylesheet" href="vendors/icheck/skins/all.css">
  
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="vendors/iconfonts/font-awesome/css/font-awesome.min.css" />
  <link rel="stylesheet" href="vendors/iconfonts/simple-line-icon/css/simple-line-icons.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/toggle/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
  

  
</head>
<body>
  <div class="container-scroller">
  
    <!-- Topbar -->
        <?php include "include/topbar.php"; ?>
    
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
    
        
        
        <!-- partial:partials/_settings-panel.html -->
        
        <!--<div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="mdi mdi-settings"></i></div>
        <div id="theme-settings" class="settings-panel">
        <i class="settings-close mdi mdi-close"></i>
        <p class="settings-heading">SIDEBAR SKINS</p>
        <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Light</div>
        <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark</div>
        <p class="settings-heading mt-2">HEADER SKINS</p>
        <div class="color-tiles mx-0 px-4">
          <div class="tiles primary"></div>
          <div class="tiles success"></div>
          <div class="tiles warning"></div>
          <div class="tiles danger"></div>
          <div class="tiles pink"></div>
          <div class="tiles info"></div>
          <div class="tiles dark"></div>
          <div class="tiles default"></div>
        </div>
        </div>
        </div>-->
        
        
        <!-- Right Sidebar -->
        <?php include "include/sidebar-right.php"; ?>
        
       
       <!-- Left Navigation -->
        <?php include "include/sidebar-nav-left.php"; ?>
        
        
      
      
      <div class="main-panel">
      
        <div class="content-wrapper">
        <?php include('include/flash.php'); ?>
          <span id="errormsg"></span>
          <div class="row">
            <?php include "include/setting-header.php"; ?>
          
          
           <!-- Form -->
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
      <div class="card-body">
        <h4 class="card-title">General Setting</h4>
        <div class="tab-content tab-content-solid">
          <div class="tab-pane fade show active" id="home-6-1" role="tabpanel" aria-labelledby="tab-6-1">
            <div class="row">
              <div class="col-md-12">
                <h5 class="mb-3">Home Content</h5>
                Large businesses require a lot of IT infrastructure and a department to look after it.  Small businesses often can’t afford to have that sort of internal support in place, yet they need fully operational IT systems in order for the business to run properly. For businesses like these, external IT support can be a cost-effective yet vital resource.
              </div>
            </div>
          </div>
          <!--<div class="tab-pane fade" id="profile-6-2" role="tabpanel" aria-labelledby="tab-6-2">
            Even one experienced, trained IT professional can cost a lot of money. Paying a salary, tax, National Insurance, pension and any other benefits can make a big difference to your bottom line. For small businesses, there just isn’t enough IT requirement to justify employing someone to run the system full-time. Instead, put your money towards your business, and pay a lot less for an external IT professional to help you when you need it.
          </div>
          <div class="tab-pane fade" id="contact-6-3" role="tabpanel" aria-labelledby="tab-6-3">
            Using IT support has many benefits, but for small businesses, the most important thing is that external IT support allows you to concentrate on your business, whilst retaining confidence in your IT systems – and at a much lower cost than employing your own IT expert.
          </div>!-->
        </div>
      </div>
    </div>
    </div>
  </div>
</div>
        
        
        <!-- partial:partials/_footer.php -->
        <?php include "include/footer.php" ?>
        <!-- partial -->   
        
     
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  
  

  
  
  

  <!-- plugins:js -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="vendors/js/vendor.bundle.addons.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/misc.js"></script>
  <script src="js/settings.js"></script>
  <script src="js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="js/file-upload.js"></script>
  <script src="js/iCheck.js"></script>
  <script src="js/typeahead.js"></script>
  <script src="js/select2.js"></script>
  
  <!-- Custom js for this page-->
  <script src="js/formpickers.js"></script>
  <script src="js/form-addons.js"></script>
  <script src="js/x-editable.js"></script>
  <script src="js/dropify.js"></script>
  <script src="js/dropzone.js"></script>
  <script src="js/jquery-file-upload.js"></script>
  <script src="js/formpickers.js"></script>
  <script src="js/form-repeater.js"></script>
  <script src="js/custom/setting.js"></script>
  
  <!-- Custom js for this page Modal Box-->
  <script src="js/modal-demo.js"></script>

 <!-- script for custom validation -->
 <script src="js/custom/onlynumber.js"></script>
 <script src="js/parsley.min.js"></script>
 <script type="text/javascript"></script>
 
 
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script> 
  
  
  <!-- End custom js for this page-->
  <!-- change status js -->
  <script src="js/custom/statusupdate.js"></script>
</body>


</html>
