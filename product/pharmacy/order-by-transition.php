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
                  
                  <form class="forms-sample">
                    <div class="col-md-12">
                      <div class="form-group row">
                        <div class="col-12 col-md-8 col-sm-12">
                            <label for="exampleInputName1">Select Type  </label>
                            <div class="row no-gutters">
                              <div class="col">
                                  <div class="form-radio">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="Active" checked>
                                      Company wise  
                                    </label>
                                  </div>
                              </div>
                              <div class="col">
                                  <div class="form-radio">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios2" value="Deactive">
                                      All Company wise
                                    </label>
                                  </div>
                              </div>
                              <div class="col">
                                  <div class="form-radio">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="Active" checked>
                                      Selected Company wise
                                    </label>
                                  </div>
                              </div>
                              <div class="col">
                                  <div class="form-radio">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios2" value="Deactive">
                                      All Products
                                    </label>
                                  </div>
                              </div>
                            </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">From Date</label>
                          <div class="input-group date datepicker">
                            <input type="text" class="form-control border" placeholder="dd/mm/yyyy">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                        </div>
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">To Date</label>
                          <div class="input-group date datepicker">
                            <input type="text" class="form-control border" placeholder="dd/mm/yyyy">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                        </div>
                        <div class="col-12 col-md-2">
                            <label >Stock per. Of Sales</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="0.00">
                        </div>
                        <div class="col-12 col-md-2">
                          <button type="submit" class="btn btn-success mt-30" style="margin-top:30px;">Search</button>
                        </div>
                      </div>
                    </div>
                  </form>
                
                </div>
              </div>
            </div>
            
            <!-- Table ------------------------------------------------------------------------------------------------------>
            
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <!-- TABLE Filters btn -->
                    <!-- TABLE STARTS -->
                    <div class="col mt-3">
                      <div class="row">
                        <div class="col-12">
                          <table class="table datatable">
                            <thead>
                              <tr>
                                  <th>Product Name</th>
                                  <th>Manufacturer Name</th>
                                  <th>Purchase Price</th>
                                  <th>Last Purchase Price</th>
                                  <th>GST</th>
                                  <th>Unit/Stip</th>
                                  <th>No. of Strips</th>
                                  <th>Order Qty</th>
                                  <th>Current Stock</th>
                                  <th>Vendor</th>
                                 
                              </tr>
                            </thead>
                            <tbody>
                              <!-- Row Starts -->   
                              <tr>
                                  <td>501 SOAP 300gm</td>
                                  <td>Chennai Pharma (HO)</td>
                                  <td>2.00</td>
                                  <td>56.00</td>
                                  <td>12</td>
                                  <td>12</td>
                                  <td>5</td>
                                  <td>5</td>
                                  <td>189</td>
                                  <td>
                                    <select class="js-example-basic-single" style="width:100%"> 
                                        <option value="Regular">H</option>
                                        <option value="Unregistered">H1</option>
                                    </select>
                                  </td>
                                 
                              </tr><!-- End Row -->   
                              
                               <tr>
                                  <td>501 SOAP 300gm</td>
                                  <td>Chennai Pharma (HO)</td>
                                  <td>2.00</td>
                                  <td>56.00</td>
                                  <td>12</td>
                                  <td>12</td>
                                  <td>5</td>
                                  <td>5</td>
                                  <td>189</td>
                                  <td>
                                    <select class="js-example-basic-single" style="width:100%"> 
                                        <option value="Regular">H</option>
                                        <option value="Unregistered">H1</option>
                                    </select>
                                  </td>
                                 
                              </tr>
                              
                               <tr>
                                  <td>501 SOAP 300gm</td>
                                  <td>Chennai Pharma (HO)</td>
                                  <td>2.00</td>
                                  <td>56.00</td>
                                  <td>12</td>
                                  <td>12</td>
                                  <td>5</td>
                                  <td>5</td>
                                  <td>189</td>
                                  <td>
                                    <select class="js-example-basic-single" style="width:100%"> 
                                        <option value="Regular">H</option>
                                        <option value="Unregistered">H1</option>
                                    </select>
                                  </td>
                                 
                              </tr>
                              
                               <tr>
                                  <td>501 SOAP 300gm</td>
                                  <td>Chennai Pharma (HO)</td>
                                  <td>2.00</td>
                                  <td>56.00</td>
                                  <td>12</td>
                                  <td>12</td>
                                  <td>5</td>
                                  <td>5</td>
                                  <td>189</td>
                                  <td>
                                    <select class="js-example-basic-single" style="width:100%"> 
                                        <option value="Regular">H</option>
                                        <option value="Unregistered">H1</option>
                                    </select>
                                  </td>
                                 
                              </tr>
                              
                             
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
      autoclose: true
    });
 </script>
 
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('.datatable').DataTable();
  </script>
  
  <!-- End custom js for this page-->
</body>


</html>
