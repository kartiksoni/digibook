<?php $title = "Configuration"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php
if($_SESSION['auth']['user_type'] != "owner"){
    include('include/permission.php'); 
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Configuration</title>
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
    
        
        
        <!-- Right Sidebar -->
        <?php include "include/sidebar-right.php" ?>
        
       
       <!-- Left Navigation -->
        <?php include "include/sidebar-nav-left.php" ?>
        
        
      <div class="main-panel">
      
        <div class="content-wrapper">
          <div class="row">
            <?php 
            if($_SESSION['auth']['user_type'] == "owner"){ ?>
              <div class="col-md-3 grid-margin">
                <div class="card">
                  <a href="view-pharmacy-profile.php">
                    <div class="card-body">
                      <div class="d-flex flex-row align-items-top">
                        <i class="mdi mdi-plus-box text-facebook icon-md"></i>
                        <div class="ml-3 p-t-5">
                          <h5 class="text-facebook">Company Master</h5>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
              <div class="col-md-3 grid-margin">
                <div class="card">
                  <a href="user.php">
                    <div class="card-body">
                      <div class="d-flex flex-row align-items-top">
                        <i class="fa fa-user text-facebook icon-md"></i>
                        <div class="ml-3 p-t-5">
                          <h5 class="text-facebook">Add User</h5>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
              <!--<div class="col-md-4 grid-margin">
                <div class="card">
                  <a href="owner-notification.php">
                    <div class="card-body">
                      <div class="d-flex flex-row align-items-top">
                        <i class="mdi mdi-bell-ring text-facebook icon-md"></i>
                        <div class="ml-3 p-t-5">
                          <h5 class="text-facebook">Notification Reminder Months</h5>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>-->
            <?php } 
            
              if((isset($user_sub_module) && in_array("Ledger Master", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){
            ?>
              <div class="col-md-3 grid-margin">
                <div class="card">
                    <!--ledger-management.php-->
                  <a href="view-ledger-management.php">
                    <div class="card-body">
                      <div class="d-flex flex-row align-items-top">
                        <i class="mdi mdi-account-multiple text-facebook icon-md"></i>
                        <div class="ml-3 p-t-5">
                          <h5 class="text-facebook">Ledger Master</h5>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            <?php } 
              if((isset($user_sub_module) && in_array("Product Master", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){  
            ?>
               <!-- product-master.php-->
              <div class="col-md-3 grid-margin">
                <div class="card">
                  <a href="view-product-master.php">
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
            <?php } 
            
            //if(isset($user_sub_module) && in_array("Product Type Master", $user_sub_module)){
            ?>
            <div class="col-md-3 grid-margin">
                <div class="card">
                  <a href="view-service-master.php">
                    <div class="card-body">
                      <div class="d-flex flex-row align-items-top">
                        <i class="fa fa-server text-facebook icon-md"></i>
                        <div class="ml-3 p-t-5">
                          <h5 class="text-facebook">Service Master</h5>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>

              <!--<div class="col-md-3 grid-margin">
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
              </div>-->
            <?php // } 
            // if(isset($user_sub_module) && in_array("Product Category Master", $user_sub_module)){
            ?>

              <!--<div class="col-md-3 grid-margin">
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
              </div>-->
            <?php // }
            if((isset($user_sub_module) && in_array("Financial Year Master", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){
             ?>
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
            <?php } ?>
            
              <?php 
                //if($_SESSION['auth']['user_type'] != "owner"){
                if((isset($user_sub_module) && in_array("Bill Notes", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ 
              ?>
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
            <?php } 
                if((isset($user_sub_module) && in_array("Notification Master", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ 
            ?>

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
            <?php } 
                if((isset($user_sub_module) && in_array("Near Expiry Reminder", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ 
            ?>
            
              <div class="col-md-3 grid-margin">
                <a href="near-by.php" class="">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex flex-row align-items-top">
                        <i class="mdi mdi-blur-linear text-facebook icon-md"></i>
                        <div class="ml-3 p-t-5">
                          <h5 class="text-facebook">Near Expiry Reminder</h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
            <?php } 
                if((isset($user_sub_module) && in_array("Comapnay Code Master", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ 
            ?>
              <div class="col-md-3 grid-margin">
                <a href="company-master.php" class="">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex flex-row align-items-top">
                        <i class="mdi mdi-city text-facebook icon-md"></i>
                        <div class="ml-3 p-t-5">
                          <h5 class="text-facebook">Comapnay Code Master</h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
            <?php } 
                if((isset($user_sub_module) && in_array("Docter Profile", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ 
            ?>
              <!--<div class="col-md-3 grid-margin">
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
              </div> -->
            <?php } 
                if((isset($user_sub_module) && in_array("Trading Account", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){
            ?>
            
            <!--trading-account.php-->
            <!--<div class="col-md-3 grid-margin">
                <a href="view-trading-account.php" class="">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex flex-row align-items-top">
                        <i class="mdi mdi-bank text-facebook icon-md"></i>
                        <div class="ml-3 p-t-5">
                          <h5 class="text-facebook">Trading Account</h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
              </div> -->
              <?php } 
                if((isset($user_sub_module) && in_array("Transport Master", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){
            ?>
              <div class="col-md-3 grid-margin">
                <a href="transport-master.php" class="">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-row align-items-top">
                                <i class="fa fa-truck text-facebook icon-md"></i>
                                <div class="ml-3 p-t-5">
                                    <h5 class="text-facebook">Transport Master</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php } 
                if((isset($user_sub_module) && in_array("Expense Master", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){
            ?>
            <div class="col-md-3 grid-margin">
                <a href="expense-master.php" class="">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-row align-items-top">
                                <i class="fa fa-rupee text-facebook icon-md"></i>
                                <div class="ml-3 p-t-5">
                                    <h5 class="text-facebook">Expense Master</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php }  
                if((isset($user_sub_module) && in_array("Series Prefix", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){
            ?>
            <div class="col-md-3 grid-margin">
                <a href="series-prefix.php" class="">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-row align-items-top">
                                <i class="fa fa-sellsy text-facebook icon-md"></i>
                                <div class="ml-3 p-t-5">
                                    <h5 class="text-facebook">Series Prefix</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <!--<div class="col-md-3 grid-margin">
                <a href="receipt-pefix.php" class="">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-row align-items-top">
                                <i class="fa fa-registered text-facebook icon-md"></i>
                                <div class="ml-3 p-t-5">
                                    <h5 class="text-facebook">Receipt Pefix</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>-->
            <?php }   
                if((isset($user_sub_module) && in_array("GST Master", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){
            ?>
            <div class="col-md-3 grid-margin">
                <a href="gst-master.php" class="">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-row align-items-top">
                                <i class="fa fa-money text-facebook icon-md"></i>
                                <div class="ml-3 p-t-5">
                                    <h5 class="text-facebook">GST Master</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php } ?>
            <?php if((isset($user_sub_module) && in_array("Salesman", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                    <div class="col-md-3 grid-margin">
                        <a href="view-salesman.php" class="">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row align-items-top">
                                        <i class="fa fa fa-user-o text-facebook icon-md"></i>
                                        <div class="ml-3 p-t-5">
                                            <h5 class="text-facebook">Salesman</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php } ?>
                    
                    <!--<div class="col-md-3 grid-margin">
                        <a href="rate-group-master.php" class="">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row align-items-top">
                                        <i class="fa fa fa-list text-facebook icon-md"></i>
                                        <div class="ml-3 p-t-5">
                                            <h5 class="text-facebook">Rate Group Master</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>-->
                    
                    <div class="col-md-3 grid-margin">
                        <a href="rate-master.php" class="">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row align-items-top">
                                        <i class="fa fa fa-rupee text-facebook icon-md"></i>
                                        <div class="ml-3 p-t-5">
                                            <h5 class="text-facebook">Rate Master</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-md-3 grid-margin">
                        <a href="area-master.php" class="">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row align-items-top">
                                      <i class="fa fa-arrows text-facebook icon-md"></i>
                                        <div class="ml-3 p-t-5">
                                            <h5 class="text-facebook">Area Master</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-md-3 grid-margin">
                        <a href="unit-master.php" class="">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row align-items-top">
                                        <i class="fa fa-qrcode text-facebook icon-md"></i>
                                        <div class="ml-3 p-t-5">
                                            <h5 class="text-facebook">Unit Master</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php //} ?>
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
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
</body>


</html>
