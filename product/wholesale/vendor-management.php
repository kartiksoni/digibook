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
            
            <!-- Vendor Managment Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Vendor Management </h4>
                 <hr class="alert-dark">
                 <br>
                  <form class="forms-sample">
                  
                  <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Company Name</label>
                      	<input type="text" class="form-control" id="exampleInputName1" placeholder="Company Name">
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Contact Person Name</label>
                      <input type="text" class="form-control" id="exampleInputName1" placeholder="Contact Person Name">
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Designation</label>
                      <input type="text" class="form-control" id="exampleInputName1" placeholder="Designation">
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Mobile No</label>
                      	<input type="text" class="form-control" id="exampleInputName1" placeholder="Mobile No">
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Phone No </label>
                      <input type="text" class="form-control" id="exampleInputName1" placeholder="Phone No ">
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Email</label>
                      <input type="email" class="form-control" id="exampleInputName1" placeholder="Email">
                      </div>
                    </div>
                    
                    
                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Fax no</label>
                      	<input type="text" class="form-control" id="exampleInputName1" placeholder="Fax no">
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Pan No</label>
                      <input type="text" class="form-control" id="exampleInputName1" placeholder="Pan No">
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">GST No</label>
                      <input type="text" class="form-control" id="exampleInputName1" placeholder="GST No">
                      </div>
                    </div>
                    
                    
                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">DL No 1</label>
                      	<input type="text" class="form-control" id="exampleInputName1" placeholder="Mobile No">
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">DL No 2</label>
                      <input type="text" class="form-control" id="exampleInputName1" placeholder="Phone No ">
                      </div>
                      
                     
                    </div>
                    
                     <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Address</label>
                      	<input type="text" class="form-control" id="exampleInputName1" placeholder="Address line1">
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">&nbsp;</label>
                      <input type="text" class="form-control" id="exampleInputName1" placeholder="Address line2">
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">&nbsp;</label>
                      <input type="text" class="form-control" id="exampleInputName1" placeholder="Address line3">
                      </div>
                      
                      </div>
                      
                      <div class="form-group row">
                      
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">City</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="City">
                          </div>
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">State</label>
                              <div class="row no-gutters">
                                  <div class="col-4 col-md-4 ">
                                  <select class="js-example-basic-single" style="width:100%">
                                      <option value="AL">+16</option>
                                      <option value="WY">+59</option>
                                      <option value="AL">+16</option>
                                      <option value="WY">+59</option>
                                    </select>
                                    </div>
                                   <div class="col-8 col-md-8 ">
                                     <select class="js-example-basic-single" style="width:100%">
                                      <option value="AL">Alabama</option>
                                      <option value="WY">Wyoming</option>
                                      <option value="AM">America</option>
                                      <option value="CA">Canada</option>
                                      <option value="RU">Russia</option>
                                    </select>
                                   </div>
                                </div>
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Pincode</label>
                          <input type="text" class="form-control" id="exampleInputName1" placeholder="Pincode">
                          </div>
                      
                     
                    </div>
                    
                    
                    <div class="form-group row">
                            <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Address</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Address line1">
                            </div>
                            <div class="col-12 col-md-4">
                            <label for="exampleInputName1">&nbsp;</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Address line2">
                            </div>
                            
                            <div class="col-12 col-md-4">
                            <label for="exampleInputName1">&nbsp;</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Address line3">
                            </div>
                    </div>
                      
                      
                      
                    
                      <div class="form-group row">
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">District</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="District">
                          </div>
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Country</label>
                          <input type="text" class="form-control" id="exampleInputName1" placeholder="Country">
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Opening Balance</label>
                      			<select class="js-example-basic-single" style="width:100%">
                                  <option value="AL">DB</option>
                                  <option value="WY">CR</option>
                                </select>
                            </div>
                    </div>
                    
                    <div class="form-group row">
                            
                            <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Cr Days</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Cr Days">
                            </div>
                            
                            <div class="col-12 col-md-4">
                                <label for="exampleInputName1">Status</label>
                                
                                <div class="row no-gutters">
                                
                                    <div class="col">
                                        <div class="form-radio">
                                        <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="Active" checked>
                                        Active
                                        </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col">
                                        <div class="form-radio">
                                        <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios2" value="Deactive">
                                        Deactive
                                        </label>
                                        </div>
                                    </div>
                                
                                </div>
                            </div>
                            
                            
                            
                            <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Vender Type</label>
                      			<select class="js-example-basic-single" style="width:100%"> 
                                  <option value="Regular">Regular</option>
                                  <option value="Unregistered">Unregistered</option>
                                  <option value="Composition">Composition</option>
                                </select>
                            </div>

                    </div>
                    
                    <br>
                    
                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                    
                  </form>
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
  
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>
  
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
 
  <!-- Custom js for this page-->
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
