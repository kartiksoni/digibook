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
                    
                    
                    <!-- TABLE STARTS -->
                    <div class="col mt-3">
                    	 <div class="row">
                            <div class="col-12">
                              <table id="order-listing1" class="table">
                                <thead>
                                  <tr>
                                      <th>Product Name</th>
                                      <th>Min Qty</th>
                                      <th>Current Stock</th>
                                      <th>Suggested Order Qty</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts --> 	
                                  <tr>
                                      <td>501 SOAP 300gm</td>
                                      <td>5</td>
                                      <td>125</td>
                                      <td>12</td>
                                  </tr><!-- End Row --> 
                                  
                                  <!-- Row Starts --> 	
                                  <tr>
                                      <td>501 SOAP 300gm</td>
                                      <td>5</td>
                                      <td>125</td>
                                      <td>12</td>
                                  </tr><!-- End Row --> 
                                  
                                  <!-- Row Starts --> 	
                                  <tr>
                                      <td>501 SOAP 300gm</td>
                                      <td>5</td>
                                      <td>125</td>
                                      <td>12</td>
                                  </tr><!-- End Row --> 
                                  
                                  <!-- Row Starts --> 	
                                  <tr>
                                      <td>501 SOAP 300gm</td>
                                      <td>5</td>
                                      <td>125</td>
                                      <td>12</td>
                                  </tr><!-- End Row --> 
                                  
                                  <!-- Row Starts --> 	
                                  <tr>
                                      <td>501 SOAP 300gm</td>
                                      <td>5</td>
                                      <td>125</td>
                                      <td>12</td>
                                  </tr><!-- End Row --> 
                                  
                                  <!-- Row Starts --> 	
                                  <tr>
                                      <td>501 SOAP 300gm</td>
                                      <td>5</td>
                                      <td>125</td>
                                      <td>12</td>
                                  </tr><!-- End Row --> 	
                                 
                                 
                                </tbody>
                              </table>
                            </div>
                          </div>
                    </div>
                    
              
                
                </div>
                </div>
                
             
                  
            </div>
          
      
            
           
            
             <!-- Table ------------------------------------------------------------------------------------------------------>
            
            <div class="col-md-12 grid-margin stretch-card">
              	<div class="card">
                <div class="card-body">
                
                	<!-- TABLE Filters btn -->
                    
                    
                   
                    
                  
                    
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
    $('#datepicker-popup1').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
    });
 </script>
 
 <script>
    $('#datepicker-popup2').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
    });
 </script>
 
 <script>
    $('#datepicker-popup3').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
    });
 </script>
 
 <script>
    $('#datepicker-popup4').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
    });
 </script>
 
  <!-- Custom js for this page Datatables-->
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
