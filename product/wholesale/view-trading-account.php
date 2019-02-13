<?php $title = "Trading Account"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php include('include/permission.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>View Trading Account</title>
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
          <span id="errormsg"></span>
          <div class="row">
            
       
            
            <!-- Financial Year Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    <!-- INVENTORY TABLE STARTS -->
                    <div class="col mt-3">
                    <a href="trading-account.php" class="btn btn-success p-2 pull-right" title="Add Trading Account"><i class="mdi mdi-plus-circle-outline"></i>Add Trading Account</a>
                    <h4 class="card-title">View Trading Account</h4>
                    <hr class="alert-dark">
                       <div class="row">
                            <div class="col-12">
                              <table id="order-listing1" class="table datatable">
                                <thead>
                                  <tr>
                                      <th>Sr No</th>
                                      <th>Account Type</th>
                                      <th>Financial Yea</th>
                                      <th>Opening Balance</th>
                                      <th>Closing Balance</th>
                                      <th>Status</th>
                                      <th>Action</th>
                                      
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts --> 
                                  <?php 
                                  $i=1;
                              $financial = "SELECT * FROM `trading_account` ORDER BY `id` DESC";
                                  $financialdata = mysqli_query($conn,$financial);
                                  while($row = mysqli_fetch_assoc($financialdata)){

                                      $financial = "SELECT f_name FROM `financial` where id = '".$row['financial_year']."'";
                                      $datefinancial = mysqli_query($conn,$financial);
                                    $financtialyear = mysqli_fetch_assoc($datefinancial);
                                  ?>  
                                  <tr>
                                      <td><?php echo $i; ?></td>
                                      <td><?php echo $row['account_type']; ?></td>
                                      <td><?php echo $financtialyear['f_name']; ?></td>
                                      <td><?php echo $row['opening_balance']; ?></td>
                                      <td><?php echo $row['closing_balance']; ?></td>
                                      <td>
                                     <button type="button" class="btn btn-sm btn-toggle changestatus <?php echo (isset($row['status']) && $row['status'] == 1) ? 'active' : ''; ?>" data-table="trading_account" data-id="<?php echo $row['id']; ?>" data-toggle="button" aria-pressed="<?php echo (isset($row['status']) && $row['status'] == 1) ? true : false; ?>" autocomplete="off">
                                        <div class="handle"></div>
                                      </button>
                                      </td>
                                      <td><a class="btn  btn-behance p-2" href="trading-account.php?id=<?php echo $row['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                      <a class="btn btn-behance p-2" href="trading-account-report.php?yearid=<?php echo $row['financial_year']; ?>" title="edit"><i class="fa fa-file mr-0"></i></a>
                                          </td>
                                      
                                  </tr><!-- End Row -->   
                                <?php $i++; } ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                    </div>
                <hr>
                   <br>
                   <a href="configuration.php" class="btn btn-light">Back</a>
                </div>
                
                </div>

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

  <script src="js/parsley.min.js"></script>
    <script type="text/javascript">
      $('form').parsley();
    </script>
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>
 
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('.datatable').DataTable();
  </script>
  
  <!-- change status js -->
  <script src="js/custom/statusupdate.js"></script>
  
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <!-- End custom js for this page-->
</body>


</html>















