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
                    <form class="forms-sample" id="byproduct_temp_form" method="POST">
                      <div class="form-group row">
                        <div class="col-12 col-md-2">
                            <label>Product Name <span class="text-danger">*</span></label>
                            <input class="form-control" name="search" id="search" type="text" placeholder="Enter product name" required>
                            <input type="hidden" name="product_id" id="product_id">
                            <input type="hidden" name="sgst" id="sgst">
                            <input type="hidden" name="cgst" id="cgst">
                            <input type="hidden" name="igst" id="igst">
                            <small class="empty-message text-danger"></small>
                        </div>
                        <div class="col-12 col-md-2 col-sm-3">
                          <label>Select Vendor <span class="text-danger">*</span></label>
                          <select class="" style="width:100%" name="vendor_id" id="vendor_id" data-parsley-errors-container="#error-vendor" required>
                              <option value="">Please select</option>
                          </select>
                          <div id="error-vendor"></div>
                          <input type="hidden" id="vendor_name" name="vendor_name">
                          <input type="hidden" id="statecode" name="statecode">
                        </div>
                        <div class="col-12 col-md-2">
                            <label>Email</label>
                            <input type="text" class="form-control" name="email" id="email" placeholder="Enter email" data-parsley-type="email" readonly>
                        </div>
                        <div class="col-12 col-md-2">
                            <label>Mobile</label>
                            <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Enter mobile" data-parsley-type="number" maxlength="10" readonly>
                        </div>
                        <div class="col-12 col-md-2">
                            <label>Generic Name</label>
                            <input type="text" class="form-control" name="generic_name" id="generic_name" placeholder="Enter Generic Name" readonly>
                        </div>
                        <div class="col-12 col-md-2">
                            <label>MFG. Company</label>
                            <input type="text" class="form-control" name="mfg_co" id="mfg_co" placeholder="Enter MFG. Company" readonly>
                        </div>
                        <input type="hidden" name="editid" class="editid" id="editid">
                      </div>
                      <div class="form-group row">
                        <div class="col-12 col-md-2">
                            <label>Purchase Price</label>
                            <input type="text" class="form-control onlynumber" name="purchase_price" id="purchase_price" placeholder="Enter Purchase Price" value="0">
                        </div>
                        <div class="col-12 col-md-2">
                            <label>GST</label>
                            <input type="text" class="form-control onlynumber" name="gst" id="gst" placeholder="Enter GST %" value="0">
                        </div>
                        <div class="col-12 col-md-2">
                            <label>Unit/Strip/Packing</label>
                            <input type="text" class="form-control onlynumber" name="unit" id="unit" placeholder="Enter Unit/Strip/Packing" value="0">
                        </div>
                        <div class="col-12 col-md-2">
                            <label>Qty</label>
                            <input type="text" class="form-control onlynumber" name="qty" id="qty" placeholder="Enter Quantity" value="1" data-parsley-min="1">
                        </div>
                        <div class="col-12 col-md-2">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#purchase-addproductmodel" style="margin-top:30px;"><i class="fa fa-plus"></i> Add New Product</button>
                        </div>
                        <div class="col-12 col-md-2">
                            <button type="submit" class="btn btn-success" id="btn-addtmp" style="margin-top:30px;" disabled>Add</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <!-- Temporary data table ------------------------------------------------------------------------------------------------>
              <div class="col-md-12 grid-margin stretch-card display-none" id="tmpdata-div">
                <div class="card">
                  <div class="card-body">
                    <div class="col mt-3">
                       <div class="row">
                          <div class="col-12">
                            <form id="add-byproduct-form" method="POST">
                              <table class="table">
                                <thead>
                                  <tr>
                                      <th>Product Name</th>
                                      <th>Purchase Price</th>
                                      <th>GST</th>
                                      <th>Unit</th>
                                      <th>Qty</th>
                                      <th>Vendor Name</th>
                                      <th>Email</th>
                                      <th>Mobile</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody id="tbody-tmp">
                                 
                                </tbody>
                              </table>
                              <button type="submit" class="btn btn-success mt-30 pull-right btn-savebyproduct" style="margin-top:30px;">Save</button>
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
                                      <th>Product Name</th>
                                      <th>Purchase Price</th>
                                      <th>GST</th>
                                      <th>Unit/Strip/Packing</th>
                                      <th>QTY</th>
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

          <!-- Add new Product Model -->
          <?php include("include/addproductmodel.php");?>


        </div>
        <!-- main-panel ends -->
      </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  
  <!-- Hidden temp tr html start -->
  <div id="tr-html" class="display-none">
    <table>
      <tr id="##DATAID##">
        <td>
          ##PRODUCTNAME##
          <input type="hidden" name="product_id[]" class="product_id" value="##PRODUCTID##">
          <input type="hidden" name="product_name[]" class="product_name" value="##PRODUCTNAME##">
        </td>
        <td>
          ##PURCHASEPRICE##
          <input type="hidden" name="purchase_price[]" class="purchase_price" value="##PURCHASEPRICE##">
        </td>
        <td>
          ##GST##
          <input type="hidden" name="gst[]" class="gst" value="##GST##">
          <input type="hidden" name="cgst[]" class="cgst" value="##CGST##">
          <input type="hidden" name="sgst[]" class="sgst" value="##SGST##">
          <input type="hidden" name="igst[]" class="igst" value="##IGST##">
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
          ##VENDORNAME##
          <input type="hidden" name="vendor_name[]" class="vendor_name" value="##VENDORNAME##">
          <input type="hidden" name="vendor_id[]" class="vendor_id" value="##VENDORID##">
          <input type="hidden" name="statecode[]" class="statecode" value="##STATECODE##">
        </td>
        <td>
          ##EMAIL##
          <input type="hidden" name="email[]" class="email" value="##EMAIL##">
        </td>
        <td>
          ##MOBILE##
          <input type="hidden" name="mobile[]" class="mobile" value="##MOBILE##">
        </td>
        <td>
          <input type="hidden" name="generic[]" class="generic" value="##GENERIC##">
          <input type="hidden" name="mfg[]" class="mfg" value="##MFG##">
          <button type="button" class="btn  btn-danger p-2 edit-temp"><i class="icon-pencil mr-0"></i></button>
          <button type="button" class="btn  btn-primary p-2 delete-temp"><i class="icon-trash mr-0"></i></button>
        </td>
      </tr>
    </table>
  </div>
  <!-- Hidden temp tr html end -->

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
  $('#vendor_id').select2({
      tags: true
    });
</script>
<script src="js/jquery-ui.js"></script>
<script src="js/custom/order_by_product.js"></script>
<script src="js/custom/onlynumber.js"></script>
<!-- End custom js for this page-->
</body>


</html>
