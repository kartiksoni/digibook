<?php $title="Sales Order Template"; ?>
<?php include('include/usertypecheck.php');?>
<?php //include('include/permission.php'); ?>
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
  <title>DigiBooks | Sale Template</title>
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
            <?php include "include/sale_header.php"; ?>
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    
                  <div class="row">
                    <div class="col-md-10">
                      <div class="sales-filter-btns-right display-3" style="display:inline-block">
                          <a href="sales-order.php" class="btn btn-primary-light-green btn-xs ">Order</a>
                          <a href="sales-order-estimate.php" class="btn btn-primary-light-green btn-xs">Estimate</a>
                          <a href="sales-order-templates.php" class="btn btn-primary-light-green btn-xs active">Templates</a>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="display-3">
                          <a href="#" class="btn btn-primary btn-xs mt-2 pull-right" data-toggle="modal" data-target="#purchase-addproductmodel" data-whatever="">Add New Product</a>
                      </div>       
                    </div> 
                  </div>
                  <hr>
                  <br>
                  
                  <form class="forms-sample" id="tmp-add-order-form" autocomplete="nope">
                    <div class="form-group row">
                      <div class="col-md-12">
                        <div class="row">
                        
                        <div class="col-12 col-md-2">
                            <label>Customer <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="customer_name" id="customer_name" placeholder="Customer" required autocomplete="nope">
                            <input type="hidden" name="customer_id" id="customer_id">
                            <small class="text-danger" id="customer-error"></small>
                          </div>    

                          <div class="col-12 col-md-2">
                            <label>Template Selection <span class="text-danger">*</span></label>
                            <select class="js-example-basic-single" name="template_id" style="width:100%" id="template_id" data-parsley-errors-container="#temp-dp-error" required> 
                                <option value="">Please select</option>
                                <?php 
                                  $getTempQ = "SELECT id, name FROM sales_template WHERE status = 1 AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY name";
                                  $getTempR = mysqli_query($conn, $getTempQ);
                                  if($getTempR && mysqli_num_rows($getTempR) > 0){
                                    while ($tempRow = mysqli_fetch_array($getTempR)) {
                                ?>
                                  <option value="<?php echo $tempRow['id']; ?>"><?php echo $tempRow['name']; ?></option>
                                <?php
                                    } 
                                  }
                                ?>
                            </select>
                            <span id="temp-dp-error"></span>
                          </div>

                          <div class="col-12 col-md-2">
                            <label>Product <span class="text-danger">*</span></label>
                            <input type="text" class="form-control product" name="product" id="product" placeholder="Product" required>
                            <input type="hidden" name="product_id" id="product_id">
                            <small class="text-danger" id="product-error"></small>
                          </div>

                          <div class="col-12 col-md-1">
                           <label>Qty. <span class="text-danger">*</span></label>
                           <input type="text" name="qty" class="form-control onlynumber" id="qty" placeholder="Qty." data-parsley-min="1" required>
                          </div>

                          <div class="col-12 col-md-4">
                            <input type="hidden" name="editid" id="editid">
                            <input type="hidden" name="id" id="id">

                            <button type="submit" class="btn btn-success mt-30" id="btn-add-tmp" disabled>Add</button>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-success mt-30" data-toggle="modal" data-target="#add-template-model">Create New Template</button>
                          </div>

                        </div>
                      </div>
                    
                    </div> 
                  </form>
                    
                </div>
              </div>
            </div>
            <!-- End Form -->
            <!-- display-none -->
            <div class="col-md-12 grid-margin stretch-card display-none" id="tmpdata-div">
                <div class="card">
                  <div class="card-body">
                    <div class="col mt-3">
                       <div class="row">
                          <div class="col-12">
                            <form id="order-final-form" method="POST">
                              <table class="table">
                                <thead>
                                  <tr>
                                      <th>Customer</th>
                                      <th>Template</th>
                                      <th>Product Name</th>
                                      <th>Qty</th>
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

            <!-- Table ------------------------------------------------------------------------------------------------------>
            
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                <div class="card-body">
                  <!-- TABLE STARTS -->
                  <div class="col mt-3">
                    <div class="row">
                      <div class="col-12">
                        <table class="table order-table">
                          <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Customer</th>
                                <th>Template</th>
                                <th>Product</th>
                                <th>Batch No.</th>
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
                  <!-- TABLE END -->
                </div>
                </div>
            </div>
            
           <!-- Template History View Table ------------------------------------------------------------------------------------------------------>
            
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4>Template History View</h4>
                    <!-- TABLE STARTS -->
                    <div class="col mt-3">
                      <div class="row">
                        <div class="col-12">
                          <table class="table template-table">
                            <thead>
                              <tr>
                                  <th>Sr No.</th>
                                  <th>Template No.</th>
                                  <th>Template Date</th>
                                  <th>Template Name</th>
                                  <th>Status</th>
                              </tr>
                            </thead>
                            <tbody>
                              
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

        <!-- Add New Product Model -->
        <?php include "include/addproductmodel.php" ?>

        <!-- Add New Product Model -->
        <?php include "popup/add-template-model.php" ?>
     
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
          <input type="hidden" class="customer_name" name="customer_name[]" value="##CUSTOMERNAME##">
          <input type="hidden" class="customer_id" name="customer_id[]" value="##CUSTOMERID##">
        </td>
        <td>
          ##TEMPLATENAME##
          <input type="hidden" class="template_name" name="template_name[]" value="##TEMPLATENAME##">
          <input type="hidden" class="template_id" name="template_id[]" value="##TEMPLATEID##">
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
          <button type="button" class="btn btn-danger p-2 edit-temp"><i class="icon-pencil mr-0"></i></button>
          <button type="button" class="btn btn-primary p-2 delete-temp"><i class="icon-trash mr-0"></i></button>
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
  
  <script>
     $('.datatable').DataTable();
  </script>


  <script src="js/parsley.min.js"></script>
  <script type="text/javascript">
    $('form').parsley();
  </script>
  <script src="js/custom/onlynumber.js"></script>
  <script src="js/custom/sales_order_templates.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/custom/statusupdate.js"></script>
  
  
  <!-- End custom js for this page-->
</body>


</html>
