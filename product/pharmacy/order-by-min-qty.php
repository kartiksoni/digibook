<?php include('include/usertypecheck.php'); ?>
<?php
    if(isset($_GET['notification']) && $_GET['notification'] != ''){
        $updateFlagQ = "UPDATE product_master SET minqty_noti_flag = 0 WHERE id = '".$_GET['notification']."'";
        $updateFlagR = mysqli_query($conn, $updateFlagQ);
    }
    $allproduct = getMinQtyProduct();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks | Order By Min Reorder</title>
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
          <!-- content-wrapper start -->
          <div class="content-wrapper">
            <div class="row">
              <!-- Inventory Form ------------------------------------------------------------------------------------------------------>
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <!-- Main Catagory -->
                    <div class="row">
                      <div class="col-12">
                        <div class="enventory">
                            <?php 
                            if(isset($user_sub_module) && in_array("Order", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ 
                            ?>
                              <a href="order.php" class="btn btn-dark btn-fw active">Order</a>
                            <?php } 
                            if(isset($user_sub_module) && in_array("List", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ 
                            ?>
                              <a href="order-list-tab.php" class="btn btn-dark btn-fw ">List</a>
                            <?php } 
                            if(isset($user_sub_module) && in_array("Missed Sales Order", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ 
                            ?>
                              <a href="missed-sales-order.php" class="btn btn-dark btn-fw ">Missed Sales Order</a>
                            <?php } 
                            //if(isset($user_sub_module) && in_array("Settings", $user_sub_module)){ 
                            ?>
                              <!--<a href="#" class="btn btn-dark btn-fw ">Settings</a>-->
                            <?php //} ?>
                          </div>   
                      </div> 
                    </div>
                    <hr>
                      
                    <!-- Sub Catagory Catagory -->
                    <div class="row">
                      <div class="col-12 bg-inverse-light" >
                        <div class="order-sub">
                            <a href="order.php" class="btn btn-grey-1 btn-rounded btn-xs <?php echo (basename($_SERVER['PHP_SELF']) == 'order.php') ? 'active' : ''; ?>">By Vendor</a>
                            <a href="order-by-transition.php" class="btn btn-rounded btn-xs btn-grey-1 <?php echo (basename($_SERVER['PHP_SELF']) == 'order-by-transition.php') ? 'active' : ''; ?>">By Transition</a>
                            <a href="order-by-min-qty.php" class="btn btn-rounded btn-xs btn-grey-1 <?php echo (basename($_SERVER['PHP_SELF']) == 'order-by-min-qty.php') ? 'active' : ''; ?>">By Min Reorder</a>
                            <a href="order-by-product.php" class="btn btn-rounded btn-xs btn-grey-1 <?php echo (basename($_SERVER['PHP_SELF']) == 'order-by-product.php') ? 'active' : ''; ?>">By Product</a>
                        </div>  
                      </div> 
                    </div>
                    <hr>
                    <!-- TABLE STARTS -->
                    <div class="col mt-3">
                    	<div class="row">
                        <div class="col-12">
                          <table class="table datatable">
                            <thead>
                              <tr>
                                  <th>Sr No.</th>
                                  <th>Product Name</th>
                                  <th>Company Code</th>
                                  <th>Min Qty</th>
                                  <th>Current Stock</th>
                                  <th>Suggested Order Qty</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php $i=1; if(isset($allproduct) && !empty($allproduct)){ ?>
                                <?php foreach ($allproduct as $key => $value) { ?>
                                    <tr>
                                      <td><?php echo $key+1; ?></td>
                                      <td><?php echo (isset($value['product_name'])) ? $value['product_name'] : ''; ?></td>
                                      <td><?php echo (isset($value['company_code'])) ? $value['company_code'] : ''; ?></td>
                                      <td><?php echo (isset($value['min_qty'])) ? $value['min_qty'] : ''; ?></td>
                                      <td><?php echo (isset($value['currentstock'])) ? $value['currentstock'] : ''; ?></td>
                                      <td><?php echo (isset($value['suggested_qty'])) ? $value['suggested_qty'] : ''; ?></td>
                                    </tr>
                                <?php } ?>
                              <?php } ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Table ------------------------------------------------------------------------------------------------------>
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
  
  <!-- Custom js for this page Modal Box-->
  <script src="js/modal-demo.js"></script>
 
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script> 
  
  <script>
  	 $('.datatable').DataTable();
  </script>
</body>


</html>
