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
                            <a href="order.php" class="btn btn-dark">Order</a>
                            <a href="order-list-tab.php" class="btn btn-dark  active">List</a>
                            <a href="#" class="btn btn-dark">Missed Sales Order</a>
                            <a href="#" class="btn btn-dark btn-fw">Settings</a>
                        </div>  
                    </div> 
                    </div>
                    <hr>
                    
                    <!-- Sub Catagory Catagory -->
                    <div class="row">
                    <div class="col-12 bg-inverse-light" >
                        <p>Order Search</p>
                    </div> 
                    </div>

                    
                    <form class="forms-sample">
                    
                    
                    <div class="form-group row">
                    
                    <div class="col-12 col-md-3">
                    <div class="row no-gutters">
                        <div  class="col-md-10">
                            <label class="col-12 row">Select Vendor</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                            <option value="Regular">Please select</option>
                            <option value="Unregistered">MRP</option>
                            <option value="Composition">Product Name </option>
                            <option value="Composition">Generic Name</option>
                            </select>
                       </div>     
                    </div>    
                    </div>
                    
                    
                    <div class="col-12 col-md-3">
                    <div class="row no-gutters">
                        <div  class="col-md-10">
                            <label class="col-12 row">Mobile</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Mobile">
                       </div>     
                    </div>    
                    </div>
                    
                    
                    <div class="col-12 col-md-3">
                    <div class="row no-gutters">
                        <div  class="col-md-10">
                            <label class="col-12 row">Order No.</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Order No.">
                       </div>     
                       </div>    
                    </div>
                    
                    
                    <div class="col-12 col-md-3">
                    <div class="row no-gutters">
                        <div  class="col-md-10">
                            <label class="col-12 row">Email ID</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Email ID">
                       </div>     
                    </div>    
                    </div>
                    
                    
                    </div>
                    
                     <div class="form-group row">
                    
                    <div class="col-12 col-md-3">
                    <div class="row no-gutters">
                    	<div  class="col-md-12">
                        <label class="col-12 row">From Date</label>
                       <div id="datepicker-popup" class="input-group date datepicker">
                        <input type="text" class="form-control">
                        <span class="input-group-addon input-group-append border-left">
                          <span class="mdi mdi-calendar input-group-text"></span>
                        </span>
                      </div>
                    	</div>
                    </div>    
                    </div>
                    
                    
                      <div class="col-12 col-md-3">
                    <div class="row no-gutters">
                    	<div  class="col-md-12">
                        <label class="col-12 row">To Date</label>
                       <div id="datepicker-popup" class="input-group date datepicker">
                        <input type="text" class="form-control">
                        <span class="input-group-addon input-group-append border-left">
                          <span class="mdi mdi-calendar input-group-text"></span>
                        </span>
                      </div>
                    	</div>
                    </div>    
                    </div>
                    
                    <div class="col-12 col-md-3">
	                    <button type="submit" class="btn btn-success mt-30" style="margin-top:30px;">Search</button>
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
                    <?php 
                        $sqlqry = "SELECT product_master.id as orderno, byvendor.created as orderdate, product_master.product_name as productname, ledger_master.name as vendorname, ledger_master.mobile as mobile, ledger_master.email as email from((byvendor INNER JOIN product_master ON byvendor.product_id = product_master.id) INNER JOIN ledger_master ON byvendor.vendor_id = ledger_master.id) WHERE byvendor.status = '0'";
                        $sqlqryrun = mysqli_query($conn, $sqlqry); ?>

                    <div class="col mt-3">
                    	 <div class="row">
                            <div class="col-12">
                              <table id="order-listing1" class="table">
                                <thead>
                                  <tr>
                                      <th>Order No</th>
                                      <th>Order Date</th>
                                      <th>Product Name</th>
                                      <th>Vendor Name</th>
                                      <th>Mobile</th>
                                      <th>Email</th>
                                      <th>&nbsp;</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <?php
                                while($sqldata = mysqli_fetch_assoc($sqlqryrun))
                                {
                                ?>        
                                <tbody>
                                  <!-- Row Starts --> 	
                                  <tr>
                                      <td><?php echo $sqldata['orderno'];?></td>
                                      <td><?php echo date('d/m/Y',strtotime($sqldata['orderdate']));?></td>
                                      <td><?php echo $sqldata['productname'];?></td>
                                      <td><?php echo $sqldata['vendorname']?></td>
                                      <td><?php echo $sqldata['mobile'];?></td>
                                      <td><?php echo $sqldata['email']?></td>
                                      <td>
                                      	<a href="#" class="btn btn-warning p-2" title="Email">
                                        	<i class="fa fa-envelope mr-0"></i>
                                        </a>
                                      </td>
                                      <td>
                                      	<a href="#" class="btn btn-primary p-2" title="Print">
                                        	<i class="fa fa-print mr-0"></i>
                                        </a>
                                        <a href="#" class="btn btn-primary p-2" title="CSV">
                                        	<i class="fa fa-file mr-0"></i>
                                        </a>
                                      </td>
                                  </tr><!-- End Row --> 	
                                 
                                 
                                </tbody>
                                <?php
                                }
                                ?>
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
</body>


</html>
