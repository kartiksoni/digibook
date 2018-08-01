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
    
        
        
        <!-- partial:partials/_settings-panel.html -->
        
        <!--<div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="mdi mdi-settings"></i></div>
        <div id="theme-settings" class="settings-panel">
        <i class="settings-close mdi mdi-close"></i>
        <p class="settings-heading">SIDEBAR SKINS</p>
        <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Light</div>
        <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark</div>
        <p class="settings-heading mt-2">HEADER SKINS</p>
        <div class="color-tiles mx-0 px-4">
          <div class="tiles primary"></div>
          <div class="tiles success"></div>
          <div class="tiles warning"></div>
          <div class="tiles danger"></div>
          <div class="tiles pink"></div>
          <div class="tiles info"></div>
          <div class="tiles dark"></div>
          <div class="tiles default"></div>
        </div>
        </div>
        </div>-->
        
        
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
                            <button type="button" class="btn btn-warning active">Order</button>
                            <button type="button" class="btn btn-warning">List</button>
                            <button type="button" class="btn btn-warning">Missed Sales Order</button>
                            <button type="button" class="btn btn-warning btn-fw">Settings</button>
                        </div>  
                    </div> 
                    </div>
                    <hr>
                    
                    <!-- Sub Catagory Catagory -->
                    <div class="row">
                    <div class="col-12 bg-inverse-light" >
                        <div class="order-sub">
                            <button type="button" class="btn btn-grey-1">By Vendor</button>
                            <button type="button" class="btn btn-grey-1 active">By Min/Max Reorder</button>
                            <button type="button" class="btn btn-grey-1">By Min/Max Reorder</button>
                        </div>  
                    </div> 
                    </div>
                    <hr>

                    
                    <form class="forms-sample">
                    
                    <div class="col-md-12">
                    
                    	<div class="form-group row">
                    
                    <div class="col-12 col-md-2 col-sm-3">
                    <label>Select Vendor</label>
                        <select class="js-example-basic-single" style="width:100%"> 
                            <option value="Regular">Please select</option>
                            <option value="Unregistered">MRP</option>
                            <option value="Composition">Product Name </option>
                            <option value="Composition">Generic Name</option>
                        </select>
                    </div>
                    
                    <div class="col-12 col-md-2 col-lg-2">
                        <label >Product</label>
                        <select class="js-example-basic-single" style="width:100%"> 
                            <option value="Regular">Please select</option>
                            <option value="Unregistered">MRP</option>
                            <option value="Composition">Product Name </option>
                            <option value="Composition">Generic Name</option>
                        </select>
                    </div>
                    
                    <div class="col-12 col-md-2">
                        <label >Product</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Product">
                    </div>
                    
                    <div class="col-12 col-md-2">
                        <label >Purchase Price </label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="0.00">
                    </div>
                    
                    <div class="col-12 col-md-1">
                        <label >GST</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="0">
                    </div>
                    
                    <div class="col-12 col-md-2">
                        <label >Unit/Strip/Packing </label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="0">
                    </div>
                    
                    <div class="col-12 col-md-1">
                        <label >Qty</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="0">
                    </div>
                    
                   
                    
                    <div class="col-12 col-md-1">
                    <button type="submit" class="btn btn-success mt-30" style="margin-top:30px;">Add</button>
                    </div>
                    
                    </div> 
                    
                    	<div class="form-group row">
                    
                    <div class="col-6 col-md-3">
                    <label>Generic Name</label>
                    <p>Aloe vera gel and glycerin</p>
                      
                    </div>
                    
                     <div class="col-6 col-md-3">
                    <label>Manufacturer Name</label>
                    <p>ABBOTT LABO-R- (I) LTD.</p>
                      
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
                              <table id="order-listing1" class="table">
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
                                <tbody>
                                  <!-- Row Starts --> 	
                                  <tr>
                                      <td>1267</td>
                                      <td>501 SOAP 300gm</td>
                                      <td>94.40</td>
                                      <td>12</td>
                                      <td>4</td>
                                      <td>40</td>
                                      <td>
                                      	<a href="#" class="btn  btn-danger p-2">Remove</a>
                                        <a href="#" class="btn  btn-dark p-2">Edit</a>
                                      </td>
                                  </tr><!-- End Row --> 	
                                  
                                   <tr>
                                      <td>1267</td>
                                      <td>501 SOAP 300gm</td>
                                      <td>94.40</td>
                                      <td>12</td>
                                      <td>4</td>
                                      <td>40</td>
                                      <td>
                                      	<a href="#" class="btn  btn-danger p-2">Remove</a>
                                        <a href="#" class="btn  btn-dark p-2">Edit</a>
                                      </td>
                                  </tr>
                                  
                                   <tr>
                                      <td>1267</td>
                                      <td>501 SOAP 300gm</td>
                                      <td>94.40</td>
                                      <td>12</td>
                                      <td>4</td>
                                      <td>40</td>
                                      <td>
                                      	<a href="#" class="btn  btn-danger p-2">Remove</a>
                                        <a href="#" class="btn  btn-dark p-2">Edit</a>
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
