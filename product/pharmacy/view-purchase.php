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
          <?php include('include/flash.php'); ?>
          <div class="row">
            
     
            
            <!-- Product Master Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">View Purchase Profile</h4>
                  <hr class="alert-dark">
                  <br>
                  <div class="col mt-3">
                       <div class="row">
                            <div class="col-12">
                              <table id="order-listing1" class="table">
                                <thead>
                                  <tr>
                                      <th>Sr No</th>
                                      <th>Vouchar Date</th>
                                      <th>Voucher No</th>
                                      <th>Vendor Name</th>
                                      <th>Invoice Date</th>
                                      <th>Total</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts -->   
                                  <?php 
                                  $i = 1;
                                  $financialQry = "SELECT * FROM `purchase` ORDER BY id DESC";
                                  $financial = mysqli_query($conn,$financialQry);
                                  while($row = mysqli_fetch_assoc($financial)){
                                  ?>
                                  <tr>
                                      <td><?php echo $i; ?></td>
                                      <td><?php echo date("d-m-Y",strtotime($row['vouchar_date'])); ?></td>
                                      <td><?php echo $row['voucher_no']; ?></td>
                                      <?php 
                                      $vendorQry = "SELECT * FROM `ledger_master` WHERE id='".$row['vendor']."'";
                                      $vendor = mysqli_query($conn,$vendorQry);
                                      $row1 = mysqli_fetch_assoc($vendor)
                                      ?>
                                      <td><?php echo $row1['name']; ?></td>
                                      <td><?php echo date("d-m-Y",strtotime($row['invoice_date'])); ?></td>
                                      <td><?php echo $row['total_total']; ?></td>
                                      <td>
                                        <a class="btn  btn-behance p-2" href="purchase.php?id=<?php echo $row['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                      </td>
                                  </tr><!-- End Row --> 
                                  <?php 
                                  $i++;
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
  
 <script>
    $('#datepicker-popup1').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy'
    });
 </script>
 
 <script>
    $('#datepicker-popup2').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy'
    });
 </script>
 
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  <script>
  	 $('#order-listing2').DataTable();
  </script>
  
  <script>
  	 $('#order-listing1').DataTable();
  </script>
  
  <!-- End custom js for this page-->
  <?php include('include/usertypecheck.php'); ?>
</body>


</html>
