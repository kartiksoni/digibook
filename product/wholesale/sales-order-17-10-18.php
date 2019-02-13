<?php $title="Sales Order"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>
<?php $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';?>
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

  <link rel="stylesheet" href="css/parsley.css">
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
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
                            <a href="#" class="btn btn-dark">Settings</a>
                            <?php } ?>
                        </div>   
                    </div> 
                  </div>
                  <hr>
                  
                  <div class="row">
                    <div class="col-md-8">
                      <div class="sales-filter-btns-right display-3" style="display:inline-block">
                        <a href="sales-order.php" class="btn btn-primary-light-green btn-xs active">Order</a>
                        <a href="sales-order-estimate.php" class="btn btn-primary-light-green btn-xs">Estimate</a>
                        <a href="sales-order-templates.php" class="btn btn-primary-light-green btn-xs">Templates</a>
                      </div>  
                    </div>
                    <div class="col-md-2">
                      <div class="display-3">
                        <a href="#" class="btn btn-primary btn-xs mt-2 pull-right" data-toggle="modal" data-target="#purchase-addproductmodel" data-whatever="">Add New Product</a>
                      </div>       
                    </div>
                    <div class="col-md-2">
                        <div class="display-3">
                          <a href="#" class="btn btn-primary btn-xs mt-2 pull-right" data-toggle="modal" data-target="#add_customer_model" data-whatever="@mdo">Add Customer</a>
                    </div>
                  </div>
                  </div>
                  <br/>

                  <form id="sales-order-form" autocomplete="off">
                    <div class="form-group row">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-12 col-md-2">
                            <label>Customer Name <span class="text-danger">*</span></label>
                            <select class="js-example-basic-single" name="customer_id" id="customer_id" style="width:100%" data-parsley-errors-container="#error-customer" required>
                              <option value="">--Select Customer--</option>
                              <?php 
                                $customerQ = "SELECT id, name FROM ledger_master WHERE group_id = 10 AND status = 1 AND pharmacy_id = '".$pharmacy_id."' ORDER BY name";
                                $customerR = mysqli_query($conn, $customerQ);
                                if($customerR && mysqli_num_rows($customerR) > 0){
                                  while ($customerRow = mysqli_fetch_array($customerR)) {
                              ?>
                                    <option value="<?php echo $customerRow['id']; ?>"><?php echo $customerRow['name']; ?></option>
                              <?php
                                  }
                                }
                              ?>
                            </select>
                            <span id="error-customer"></span>
                          </div>
                        
                          <div class="col-12 col-md-2">
                            <label for="product">Product <span class="text-danger">*</span></label>
                            <input type="text" class="form-control product" name="product" id="product" placeholder="Product" required>
                            <input type="hidden" name="product_id" id="product_id">
                            <small class="text-danger product-error"></small>
                          </div>
                      
                          <div class="col-12 col-md-1">
                            <label for="qty">Qty. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control onlynumber" name="qty" id="qty" placeholder="Qty." data-parsley-min="1" required>
                          </div>
                      
                          <div class="col-12 col-md-1">
                            <label for="discount">Disc.%</label>
                            <input type="text" class="form-control onlynumber" name="discount" id="discount" placeholder="Disc.%">
                          </div>
                      
                          <div class="col-12 col-md-2">
                            <label for="mrp">MRP</label>
                            <input type="text" class="form-control onlynumber" name="mrp" id="mrp" placeholder="MRP">
                          </div>
                          <div class="col-12 col-md-4">
                            <input type="hidden" name="editid" id="editid">
                            <input type="hidden" name="id" id="id">
                            <button type="submit" class="btn btn-success mt-30" id="btn-add-tmp" disabled>Add</button>
                            <button type="button" class="btn btn-dark mt-30 reset">Reset</button>
                          </div>
                        </div> 
                      </div>
                    </div> 
                  </form>

                </div>
              </div>
            </div>

            <div class="col-md-12 grid-margin stretch-card display-none" id="tmpdata-div">
                <div class="card">
                  <div class="card-body">
                    <div class="col mt-3">
                       <div class="row">
                          <div class="col-12">
                            <form id="sales-order-final-form" method="POST">
                              <table class="table">
                                <thead>
                                  <tr>
                                      <th>Customer Name</th>
                                      <th>Product Name</th>
                                      <th>Qty</th>
                                      <th>Discount</th>
                                      <th>MRP</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody id="tbody-tmp">
                                  
                                </tbody>
                              </table>
                              <button type="submit" class="btn btn-success mt-30 pull-right btn-saveproduct" style="margin-top:30px;">Save</button>
                            </form>
                          </div>
                        </div>
                    </div>
                    <hr>
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
                                      <th>Customer</th>
                                      <th>Product</th>
                                      <th>Qty</th>
                                      <th>Discount</th>
                                      <th>MRP</th>
                                      <th>Action</th>
                                  </tr> 
                                </thead>
                                <tbody>
                                 
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
        
         <!-- Add customer Model -->
          <?php include "popup/add-customer-model.php"?>
     
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  
  

  <div id="addproduct-tr-html" class="display-none">
    <table>
      <tr id="##DATAID##">
        <td>
          ##CUSTOMERNAME##
          <input type="hidden" class="customer_id" name="customer_id[]" value="##CUSTOMERID##">
          <input type="hidden" class="customer_name" name="customer_name[]" value="##CUSTOMERNAME##">
        </td>
        <td>
          ##PRODUCTNAME##
          <input type="hidden" class="product_id" name="product_id[]" value="##PRODUCTID##">
          <input type="hidden" class="product_name" name="product_name[]" value="##PRODUCTNAME##">
        </td>
        <td>
          ##QTY##
          <input type="hidden" class="qty" name="qty[]" value="##QTY##">
        </td>
        <td>
          ##DISCOUNT##
          <input type="hidden" class="discount" name="discount[]" value="##DISCOUNT##">
        </td>
        <td>
          ##MRP##
          <input type="hidden" class="mrp" name="mrp[]" value="##MRP##">
        </td>
        <td>
          <button class="btn  btn-danger p-2 edit-temp"><i class="icon-pencil mr-0"></i></button>
          <button class="btn  btn-primary p-2 delete-temp"><i class="icon-trash mr-0"></i></button>
        </td>
      </tr>
    </table>
  </div>
  
  

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

  <script src="js/parsley.min.js"></script>
  <script type="text/javascript">
    $('form').parsley();
  </script>
  <script src="js/custom/onlynumber.js"></script>
  <script src="js/custom/sales_order.js"></script>
  <script src="js/custom/add-customer-popup.js"></script>
  <script src="js/jquery-ui.js"></script>
  
  
  <!-- End custom js for this page-->
</body>


</html>
