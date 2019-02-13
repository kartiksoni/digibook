<?php $title="Sales Order History"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>


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
  <link rel="stylesheet" href="vendors/iconfonts/simple-line-icon/css/simple-line-icons.css">
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
          
           <!-- Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <!-- Main Catagory -->
                  <div class="row">
                    <div class="col-12">
                        <div class="purchase-top-btns">
                            <?php 
                                if(isset($user_sub_module) && in_array("Tax Billing", $user_sub_module)){ 
                             ?>
                            <a href="sales-tax-billing.php" class="btn btn-dark active">Sales</a>
                            <a href="view-sales-tax-billing.php" class="btn btn-dark active">View Sales Bill</a>
                            <?php }
                                if(isset($user_sub_module) && in_array("Sales Return", $user_sub_module)){
                            ?>
                            <a href="sales-return.php" class="btn btn-dark">Sales Return</a>
                            <?php }
                                if(isset($user_sub_module) && in_array("Sales Return List", $user_sub_module)){
                            ?>
                            <a href="#" class="btn btn-dark">Sales Return List</a>
                            <?php }
                                if(isset($user_sub_module) && in_array("Cancellation List", $user_sub_module)){
                            ?>
                            <a href="sales-cancellation-list.php" class="btn btn-dark">Cancellation List</a>
                            <?php } 
                                if(isset($user_sub_module) && in_array("Order", $user_sub_module)){
                              ?>
                                <a href="#" class="btn btn-dark dropdown-toggle" id="dropdownMenuButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Order</a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton4">
                                  <a class="dropdown-item" href="sales-order.php">Order/Estimate/Templates</a>
                                  <a class="dropdown-item" href="sales-order-history.php">History</a>
                                </div>
                                <?php } 
                                    if(isset($user_sub_module) && in_array("Sales History", $user_sub_module)){
                                  ?>
                            <a href="sales-history.php" class="btn btn-dark">History</a>
                            <?php } 
                                if(isset($user_sub_module) && in_array("Settings", $user_sub_module)){
                              ?>
                            <!--<a href="#" class="btn btn-dark">Settings</a>-->
                            <?php } ?>
                        </div>   
                    </div> 
                  </div>
                  <hr>
                  
                  <div class="row">
                    <div class="col-md-12">
                      <div class="sales-filter-btns-right display-3" style="display:inline-block">
                        <a href="sales-order.php" class="btn btn-primary-light-green btn-xs active">Order</a>
                        <a href="sales-order-estimate.php" class="btn btn-primary-light-green btn-xs">Estimate</a>
                        <a href="sales-order-templates.php" class="btn btn-primary-light-green btn-xs">Templates</a>
                      </div>  
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="col mt-3">
                       <div class="row">
                          <div class="col-12">
                              <table class="table datatable">
                                <thead>
                                  <tr>
                                      <th>Sr. No</th>
                                      <th>Order Date</th>
                                      <th>Customer Name</th>
                                      <th>Mobile</th>
                                      <th>Email</th>
                                      <th>Action</th>
                                  </tr> 
                                </thead>
                                <tbody>
                                <?php
                                  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
                                  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

                                  $query = "SELECT so.id, so.created, lgr.name, lgr.id as lg_id, lgr.mobile, lgr.email FROM sales_order so INNER JOIN ledger_master lgr ON so.customer_id = lgr.id WHERE so.pharmacy_id = '".$pharmacy_id."' AND so.financial_id = '".$financial_id."' ORDER BY so.id DESC";
                                  $res = mysqli_query($conn, $query);
                                  if($res && mysqli_num_rows($res)){
                                    $i = 1;
                                    while ($row = mysqli_fetch_array($res)) {
                                ?>
                                <tr>
                                  <td><?php echo $i; ?></td>
                                  <td><?php echo (isset($row['created']) && $row['created'] != '') ? date('d/m/Y',strtotime($row['created'])) : ''; ?></td>
                                  <td><?php echo (isset($row['name'])) ? $row['name'] : ''; ?></td>
                                  <td><?php echo (isset($row['mobile'])) ? $row['mobile'] : ''; ?></td>
                                  <td><?php echo (isset($row['email'])) ? $row['email'] : ''; ?></td>
                                  <td>
                                    <button type="button" class="btn btn-success btn-xs pt-2 pb-2"><i class="icon-pencil mr-0 ml-0"></i></button>
                                    <button type="button" class="btn btn-primary btn-xs pt-2 pb-2"><i class="icon-envelope mr-0 ml-0"></i></button>
                                    <a href="sales-order-print.php?id=<?php echo $row['lg_id']; ?>" class="btn btn-primary btn-xs pt-2 pb-2"><i class="icon-printer mr-0 ml-0"></i></a>
                                  </td>
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
                    <hr>
                  </div>
                </div>
              </div>
            
          </div>
        </div>
        <!-- content-wrapper ends -->
        
        <!-- partial:partials/_footer.php -->
        <?php include "include/footer.php" ?>
        <!-- partial -->
        
          
        <!-- Add New Product Model -->
        <?php include "include/addproductmodel.php" ?>
     
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
  
  <!-- Custom js for this page Modal Box-->
  <script src="js/modal-demo.js"></script>
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script>
  <script>
     $('.datatable').DataTable();
  </script>
  <!-- End custom js for this page-->
</body>


</html>
