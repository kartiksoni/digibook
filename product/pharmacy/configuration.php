<?php include('include/usertypecheck.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks</title>
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
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
  <style type="text/css">
    a{text-decoration: none !important}
    .p-t-5{padding-top: 5px;}
  </style>
</head>
<body>
  <div class="container-scroller">
  
    <!-- Topbar -->
        <?php include "include/topbar.php" ?>
    
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
        <?php include "include/sidebar-right.php" ?>
        
       
       <!-- Left Navigation -->
        <?php include "include/sidebar-nav-left.php" ?>
        
        
      <div class="main-panel">
      
        <div class="content-wrapper">
          <?php include('include/flash.php'); ?>
          <div class="row">
              <div class="col-md-3 grid-margin">
                <div class="card">
                  <a href="ledger-management.php">
                    <div class="card-body">
                      <div class="d-flex flex-row align-items-top">
                        <i class="mdi mdi-account-multiple text-facebook icon-md"></i>
                        <div class="ml-3 p-t-5">
                          <h5 class="text-facebook">Ledger Management</h5>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>

              <div class="col-md-3 grid-margin">
                <div class="card">
                  <a href="product-master.php">
                    <div class="card-body">
                      <div class="d-flex flex-row align-items-top">
                        <i class="mdi mdi-file-powerpoint-box text-facebook icon-md"></i>
                        <div class="ml-3 p-t-5">
                          <h5 class="text-facebook">Product Master</h5>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>

              <div class="col-md-3 grid-margin">
                <div class="card">
                  <a href="product-type-master.php">
                    <div class="card-body">
                      <div class="d-flex flex-row align-items-top">
                        <i class="mdi mdi-format-list-bulleted-type text-facebook icon-md"></i>
                        <div class="ml-3 p-t-5">
                          <h5 class="text-facebook">Product Type Master</h5>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>

              <div class="col-md-3 grid-margin">
                <div class="card">
                  <a href="product-category-master.php">
                    <div class="card-body">
                      <div class="d-flex flex-row align-items-top">
                        <i class="mdi mdi-arrange-bring-forward text-facebook icon-md"></i>
                        <div class="ml-3 p-t-5">
                          <h5 class="text-facebook">Product Category Master</h5>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
          </div>
          <div class="row">
              <!-- <div class="col-md-3 grid-margin">
                <div class="card">
                  <a href="service-master.php">
                    <div class="card-body">
                      <div class="d-flex flex-row align-items-top">
                        <i class="mdi mdi-settings text-facebook icon-md"></i>
                        <div class="ml-3 p-t-5">
                          <h5 class="text-facebook">Service Master</h5>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              </div> -->

              <div class="col-md-3 grid-margin">
                <div class="card">
                  <a href="financial-year.php">
                    <div class="card-body">
                      <div class="d-flex flex-row align-items-top">
                        <i class="mdi mdi-chart-line text-facebook icon-md"></i>
                        <div class="ml-3 p-t-5">
                          <h5 class="text-facebook">Financial Year Master</h5>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>

              <div class="col-md-3 grid-margin">
                <div class="card">
                  <a href="pharmacy-profile.php">
                    <div class="card-body">
                      <div class="d-flex flex-row align-items-top">
                        <i class="mdi mdi-plus-box text-facebook icon-md"></i>
                        <div class="ml-3 p-t-5">
                          <h5 class="text-facebook">Pharmarcy Profile</h5>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>

              <div class="col-md-3 grid-margin">
              <a href="bill-note.php" class="">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex flex-row align-items-top">
                      <i class="mdi mdi-note-text text-facebook icon-md"></i>
                      <div class="ml-3 p-t-5">
                        <h5 class="text-facebook">Bill Notes</h5>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div>

              <div class="col-md-3 grid-margin">
                <div class="card">
                  <a href="notification-master.php">
                    <div class="card-body">
                      <div class="d-flex flex-row align-items-top">
                        <i class="mdi mdi-bell-ring text-facebook icon-md"></i>
                        <div class="ml-3 p-t-5">
                          <h5 class="text-facebook">Notification Master</h5>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
          </div>
          <div class="row">
            
            <div class="col-md-3 grid-margin">
              <a href="near-by.php" class="">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex flex-row align-items-top">
                      <i class="mdi mdi-blur-linear text-facebook icon-md"></i>
                      <div class="ml-3 p-t-5">
                        <h5 class="text-facebook">Near Expiry</h5>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-md-3 grid-margin">
              <a href="company-master.php" class="">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex flex-row align-items-top">
                      <i class="mdi mdi-city text-facebook icon-md"></i>
                      <div class="ml-3 p-t-5">
                        <h5 class="text-facebook">Company Master</h5>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-md-3 grid-margin">
              <a href="doctor-profile.php" class="">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex flex-row align-items-top">
                      <i class="mdi mdi-stethoscope text-facebook icon-md"></i>
                      <div class="ml-3 p-t-5">
                        <h5 class="text-facebook">Docter Profile</h5>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div> 
          </div>
        </div>
        <!-- content-wrapper ends -->
        
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
  
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>
</body>


</html>
