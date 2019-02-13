<?php $title = "Cancel List"; ?>
<?php include('include/usertypecheck.php'); 
include('include/permission.php'); ?>

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
          <span id="errormsg"></span>
          <div class="row">
            
     
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <!-- Main Catagory -->
                    <div class="row">
                      <div class="col-12">
                          <div class="purchase-top-btns">
                          <?php if((isset($user_sub_module) && in_array("Purchase Bill", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="purchase.php" class="btn btn-dark active">Purchase Bill</a>
                          <?php } if((isset($user_sub_module) && in_array("Purchase Return", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="purchase-return.php" class="btn btn-dark">Purchase Return</a>
                          <?php } if((isset($user_sub_module) && in_array("Purchase Return List", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="purchase-return-list.php" class="btn btn-dark">Purchase Return List</a>
                          <?php } if((isset($user_sub_module) && in_array("Cancel List", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="purchase-cancel-list.php" class="btn btn-dark btn-fw">Cancel List</a>
                          <?php } if((isset($user_sub_module) && in_array("History", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="purchase-history.php" class="btn btn-dark btn-fw">History</a>
                          <?php }  if((isset($user_sub_module) && in_array("Settings", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <!--<a href="#" class="btn btn-dark btn-fw">Settings</a>-->
                          <?php } ?>
                        </div>      
                      </div> 
                    </div>
                </div>
              </div>
            </div>

            <!-- Product Master Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Purchase Cancel List</h4>
                  <hr class="alert-dark">
                  <br>
                  <div class="col mt-3">
                       <div class="row">
                            <div class="col-12">
                              <div class="table-responsive">
                                <table class="table datatable">
                                  <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Invoice Date</th>
                                        <th>Name</th>
                                        <th>Invoice No</th>
                                        <th>Bill Type</th>
                                        <th>Amount</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                      $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
                                      $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

                                      $qry = "SELECT pr.id, pr.invoice_date, pr.invoice_no, pr.purchase_type, pr.total_total, lg.name as vendorname FROM purchase pr INNER JOIN ledger_master lg ON pr.vendor = lg.id WHERE pr.pharmacy_id = '".$pharmacy_id."' AND pr.financial_id = '".$financial_id."' AND pr.cancel = 0 ORDER BY pr.id DESC";
                                      $res = mysqli_query($conn, $qry);
                                      if($res){
                                        $i = 1;
                                        while ($row = mysqli_fetch_array($res)) {
                                    ?>
                                      <tr>
                                          <td><?php echo $i; ?></td>
                                          <td><?php echo (isset($row['invoice_date']) && $row['invoice_date'] != '') ? date('d/m/Y',strtotime($row['invoice_date'])) : ''; ?></td>
                                          <td><?php echo (isset($row['vendorname']) && $row['vendorname'] != '') ? ucwords(strtolower($row['vendorname'])) : ''; ?></td>
                                          <td><?php echo (isset($row['invoice_no'])) ? $row['invoice_no'] : ''; ?></td>
                                          <td><?php echo (isset($row['purchase_type'])) ? $row['purchase_type'] : ''; ?></td>
                                          <td class="text-right"><?php echo (isset($row['total_total']) && $row['total_total'] != '') ? amount_format(number_format($row['total_total'], 2, '.', '')) : ''; ?></td>
                                      </tr>
                                    <?php
                                      $i++;
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
  
  <script>
     $('.datatable').DataTable();
  </script>
  
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <!-- End custom js for this page-->
</body>


</html>
