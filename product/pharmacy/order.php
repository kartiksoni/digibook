<?php include('include/usertypecheck.php');?>
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

  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
  <link rel="stylesheet" href="css/parsley.css">
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
            <span id="errormsg"></span>
            <div class="row">
             <!-- Inventory Form ------------------------------------------------------------------------------------------------------>
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <!-- Main Catagory -->
                    <div class="row">
                      <div class="col-12">
                          <div class="enventory">
                              <a href="order.php" class="btn btn-dark btn-fw active">Order</a>
                              <a href="order-list-tab.php" class="btn btn-dark btn-fw ">List</a>
                              <a href="#" class="btn btn-dark btn-fw ">Missed Sales Order</a>
                              <a href="#" class="btn btn-dark btn-fw ">Settings</a>
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
                              <a href="order-by-min-qty.php" class="btn btn-rounded btn-xs btn-grey-1 <?php echo (basename($_SERVER['PHP_SELF']) == 'order-by-min-qty.php') ? 'active' : ''; ?>">By Max Reorder</a>
                              <a href="order-by-product.php" class="btn btn-rounded btn-xs btn-grey-1 <?php echo (basename($_SERVER['PHP_SELF']) == 'order-by-product.php') ? 'active' : ''; ?>">By Product</a>
                          </div>   
                      </div> 
                    </div>
                    <hr>
                    <form class="forms-sample" id="add_byvendor_temp" method="POST">
                      <div class="form-group row">
                        <div class="col-12 col-md-2 col-sm-3">
                          <label>Select Vendor</label>
                          <select class="js-example-basic-single" style="width:100%" name="vendor_id" id="vendor_id" data-parsley-errors-container="#error-vendor" required> 
                              <option value="">Please select</option>
                              <?php 
                                $getAllVendorQuery = "SELECT id, name FROM ledger_master WHERE status=1 AND group_id=14 order by name";
                                $getAllVendorRes = mysqli_query($conn, $getAllVendorQuery);
                              ?>
                              <?php if($getAllVendorRes && mysqli_num_rows($getAllVendorRes) > 0){ ?>
                                <?php while ($getAllVendorRow = mysqli_fetch_array($getAllVendorRes)) { ?>
                                  <option value="<?php echo $getAllVendorRow['id']; ?>"><?php echo $getAllVendorRow['name']; ?></option>
                                <?php } ?>
                              <?php } ?>
                          </select>
                          <div id="error-vendor"></div>
                          <input type="hidden" id="statecode" name="statecode">
                          <input type="hidden" id="vendor_name" name="vendor_name">
                        </div>
                        <div class="col-12 col-md-2 col-lg-2">
                            <label >Select anyone</label>
                            <select class="js-example-basic-single" style="width:100%" id="selectsearch"> 
                                <option value="product">Product Name </option>
                                <option value="mrp">MRP</option>
                                <option value="generic">Generic Name</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <label id="search-lable">Product Name</label>
                            <input class="form-control" name="search" id="search" type="text" placeholder="Start typing.." required>
                            <input type="hidden" name="product_id" id="product_id">
                            <input type="hidden" name="product_name" id="product_name">
                            <small class="empty-message text-danger"></small>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-12 col-md-2">
                            <label >Purchase Price </label>
                            <input type="text" class="form-control onlynumber" name="purchase_price" id="purchase_price" placeholder="0.00" value="0" data-parsley-type="number">
                        </div>
                        <div class="col-12 col-md-1">
                            <label >GST</label>
                            <input type="text" class="form-control onlynumber" name="gst" id="gst" placeholder="0" value="0" data-parsley-type="number">
                        </div>
                        <div class="col-12 col-md-2">
                            <label >Unit/Strip/Packing </label>
                            <input type="text" class="form-control onlynumber" name="unit" id="unit" placeholder="0" value="0" data-parsley-type="number">
                        </div>
                        <div class="col-12 col-md-1">
                            <label >Qty</label>
                            <input type="text" class="form-control onlynumber" id="qty" name="qty" placeholder="0" value="1" data-parsley-type="number" data-parsley-min="1" required>
                        </div>
                        <div class="col-12 col-md-1">
                          <button type="submit" class="btn btn-success mt-30" id="btn-addtop" style="margin-top:30px;" disabled>Add</button>
                        </div>
                      </div> 
                      <div class="form-group row">
                        <div class="col-6 col-md-3">
                          <label>Generic Name</label>
                          <p id="generic-name"></p>
                          <input type="hidden" name="generic_name" id="generic-name-input">
                        </div>
                        <div class="col-6 col-md-3">
                          <label>Manufacturer Name</label>
                          <p id="menufacturer-name"></p>
                          <input type="hidden" name="menufacturer_name" id="menufacturer-name-input">
                          <input type="hidden" name="editid" id="editid">
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
               <!-- Table ------------------------------------------------------------------------------------------------------>
              <div class="col-md-12 grid-margin stretch-card" id="tmpdata-div" style="display: none;">
                <div class="card">
                  <div class="card-body">
                    <div class="col mt-3">
                       <div class="row">
                          <div class="col-12">
                            <form id="add-byvendor-form" method="POST">
                              <table class="table">
                                <thead>
                                  <tr>
                                      <th>Vendor Name</th>
                                      <th>Product</th>
                                      <th>Purchase Price</th>
                                      <th>GST</th>
                                      <th>Unit / Strip / Packing</th>
                                      <th>Qty</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody id="tbody-tmp">
                                 
                                </tbody>
                              </table>
                              <button type="submit" class="btn btn-success mt-30 pull-right btn-savebyvendor" style="margin-top:30px;">Save</button>
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
                                      <th>Vendor Name</th>
                                      <th>Product</th>
                                      <th>Purchase Price</th>
                                      <th>GST</th>
                                      <th>Unit / Strip / Packing</th>
                                      <th>Qty</th>
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
        </div>
        <!-- main-panel ends -->
      </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  
  
 <!-- HIDDEN TR HTML -->
  <div id="addproduct-tr-html" style="display: none;">
    <table>
      <tr id="##DATAID##">
        <td>
          ##VENDORNAME##
          <input type="hidden" name="vendor_id[]" class="vendor_id" value="##VENDORID##">
          <input type="hidden" name="vendor_name[]" class="vendor_name" value="##VENDORNAME##">
          <input type="hidden" name="state_code[]" class="state_code" value="##STATECODE##">
        </td>
        <td>
          ##PRODUCTNAME##
          <input type="hidden" name="product_id[]" class="product_id" value="##PRODUCTID##">
          <input type="hidden" name="product_name[]" class="product_name" value="##PRODUCTNAME##">
        </td>
        <td>
          ##MRP##
          <input type="hidden" name="purchase_price[]" class="purchase_price" value="##MRP##">
        </td>
        <td>
          ##GST##
          <input type="hidden" name="gst[]" class="gst" value="##GST##">
        </td>
        <td>
          ##UNIT##
          <input type="hidden" name="unit[]" class="unit" value="##UNIT##">
        </td>
        <td>
          ##QTY##
          <input type="hidden" name="qty[]" class="qty" value="##QTY##">
        </td>
        <td>
          <input type="hidden" name="generic_name[]" class="generic_name" value="##GENERICNAME##">
          <input type="hidden" name="menufacturer_name[]" class="menufacturer_name" value="##MANUFACTURERNAME##">

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
  
  
  <!-- Datepicker Initialise-->
 <script>
    $('.datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
    });
 </script>
 
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script> 

  <!-- script for custom validation -->
<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>
<script src="js/jquery-ui.js"></script>
<script src="js/custom/order_by_vendor.js"></script>
<script src="js/custom/onlynumber.js"></script>
  <!-- End custom js for this page-->
</body>


</html>
