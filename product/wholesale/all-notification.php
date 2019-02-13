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
  
  <link rel="stylesheet" href="css/toggle/style.css">
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

              <!------------------------ ALL NOTIFICATION START ---------------------------------->
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">All Notification</h4><hr class="alert-dark">
                    <div class="col mt-3">
                      <div class="row">
                        <div class="col-12">
                          <div class="table-responsive">
                            <table class="table">
                              <thead>
                                <tr>
                                    <th width="10%">Sr No.</th>
                                    <th>Notification</th>
                                    <th>Notification Type</th>
                                    <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php 
                                    $notification = getAllNotification();
                                    if(isset($notification) && !empty($notification)){
                                        foreach($notification as $key => $value){
                                ?>
                                    <tr>
                                        <td><?php echo $key+1; ?></td>
                                        <td><strong><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></strong><?php echo (isset($value['subname']) && $value['subname'] != '') ? ' - '.$value['subname'] : ''; ?></td>
                                        <td><?php echo (isset($value['typename'])) ? $value['typename'] : ''; ?></td>
                                        <td>
                                            <a class="btn  btn-behance p-2" href="<?php echo (isset($value['url']) && $value['url'] != '') ? $value['url'] : 'javascript:void(0);'; ?>" title="Show Detail"><i class="fa fa-eye mr-0"></i></a>
                                        </td>
                                    </tr>
                                <?php 
                                        }
                                    }
                                ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!------------------------ ALL NOTIFICATION END ---------------------------------->
              
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
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  <script>
     $('.datatable').DataTable();
  </script>
  <!-- End custom js for this page-->
</body>


</html>