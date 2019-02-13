<?php $title="Sales Return List"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php include('include/permission.php'); ?>
<?php 
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
?>
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
                              <?php 
                                if((isset($user_sub_module) && in_array("Tax Billing", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ 
                              ?>
                              <a href="sales-tax-billing.php" class="btn btn-dark active">Sales</a>
                              <a href="view-sales-tax-billing.php" class="btn btn-dark active">View Sales Bill</a>
                              <?php }
                                if((isset($user_sub_module) && in_array("Sales Return", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){
                              ?>
                              <a href="sales-return.php" class="btn btn-dark">Sales Return</a>
                              <?php }
                                if((isset($user_sub_module) && in_array("Sales Return List", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){
                              ?>
                              <a href="view-sales-return.php" class="btn btn-dark">Sales Return List</a>
                              <?php }
                                if((isset($user_sub_module) && in_array("Cancellation List", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){
                              ?>
                              <a href="sales-cancellation-list.php" class="btn btn-dark">Cancellation List</a>
                              <?php } 
                                if((isset($user_sub_module) && in_array("Order", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){
                              ?>
                                  <a href="#" class="btn btn-dark dropdown-toggle" id="dropdownMenuButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Order</a>
                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton4">
                                    <a class="dropdown-item" href="sales-order.php">Order/Estimate/Templates</a>
                                    <a class="dropdown-item" href="sales-order-history.php">History</a>
                                  </div>
                              <?php } 
                                if((isset($user_sub_module) && in_array("Sales History", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){
                              ?>
                              <a href="sales-history.php" class="btn btn-dark">History</a>
                              <?php } 
                                //if(isset($user_sub_module) && in_array("Settings", $user_sub_module)){
                              ?>
                              <!--<a href="#" class="btn btn-dark">Settings</a>-->
                              <?php //} ?>
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
                  <h4 class="card-title">Sales Return List</h4>
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
                                        <th>Credit Note Date</th>
                                        <th>Credit Note No.</th>
                                        <th>Customer Name</th>
                                        <th>City</th>
                                        <th>Taxable Amount</th>
                                        <th>Tax Amount</th>
                                        <th>Final Amount</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                      $qry = "SELECT sr.id, sr.credit_note_no, sr.credit_note_date, sr.finalamount,sr.taxable_amount,(sr.totaligst + sr.totalcgst + sr.totalsgst) as tax_amount, lg.name as customer_name, ct.name as city_name FROM sale_return sr LEFT JOIN ledger_master lg ON sr.customer_id = lg.id LEFT JOIN own_cities ct ON lg.city = ct.id WHERE sr.pharmacy_id = '".$pharmacy_id."' AND sr.financial_id = '".$financial_id."' ORDER BY sr.id DESC";
                                      
                                      $res = mysqli_query($conn, $qry);
                                      if($res && mysqli_num_rows($res) > 0){
                                        $i = 1;
                                        while ($row = mysqli_fetch_array($res)) {
                                    ?>
                                      <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo (isset($row['credit_note_date']) && $row['credit_note_date'] != '' && $row['credit_note_date'] != '0000-00-00') ? date('d/m/Y',strtotime($row['credit_note_date'])) : ''; ?></td>
                                            <td><?php echo (isset($row['credit_note_no'])) ? $row['credit_note_no'] : ''; ?></td>
                                            <td><?php echo (isset($row['customer_name'])) ? $row['customer_name'] : ''; ?></td>
                                            <td><?php echo (isset($row['city_name'])) ? $row['city_name'] : ''; ?></td>
                                            <td class="text-right"><?php echo (isset($row['taxable_amount']) && $row['taxable_amount'] != '') ? amount_format(number_format($row['taxable_amount'], 2, '.', '')) : ''; ?></td>
                                            <td class="text-right"><?php echo (isset($row['tax_amount']) && $row['tax_amount'] != '') ? amount_format(number_format($row['tax_amount'], 2, '.', '')) : ''; ?></td>
                                            <td class="text-right"><?php echo (isset($row['finalamount']) && $row['finalamount'] != '') ? amount_format(number_format($row['finalamount'], 2, '.', '')) : ''; ?></td>
                                            <td class="text-center">
                                              <a class="btn btn-behance p-2" href="sales-return.php?id=<?php echo $row['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                            </td>
                                      </tr>
                                    <?php $i++;
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
  
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <script>
     $('.datatable').DataTable();
  </script>
  <!-- End custom js for this page-->
</body>


</html>
