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
                    <h4 class="card-title">Inventory</h4>
                    <hr class="alert-dark">
                    <br>
                
                    <div class="row">
                    
                    <div class="col-12 col-md-10 col-sm-12">
                        <div class="enventory">
                            <button type="button" class="btn btn-warning">Inventory</button>
                            <button type="button" class="btn btn-warning">Update Inventory </button>
                            <button type="button" class="btn btn-warning">Inventory Setting </button>
                            <button type="button" class="btn btn-warning btn-fw">Product Master </button>
                            <button type="button" class="btn btn-warning btn-fw">Self Consumption </button>
                            <button type="button" class="btn btn-warning btn-fw active">Non Moving </button>
                            <button type="button" class="btn btn-warning btn-fw">Reorder </button>
                            <button type="button" class="btn btn-warning btn-fw">Over Stock</button>
                            
                            <!--<div class="dropdown" style="display:inline-block">
                                <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Barcode
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton4">
                                <a class="dropdown-item" href="#">Barcode Profile</a>
                                <a class="dropdown-item" href="#">Barcode PRN Design</a>
                                <a class="dropdown-item" href="#">Barcode Generation &amp; Printing</a>
                                </div>
                            </div>-->
                            
                        </div>          
                    </div> 
                    
                    <div class="col-12 col-md-2">
                    <button type="button" class="btn btn-secondary pull-right"><strong>*HSN Code Reference</strong></button>
                    </div>
                    
                    </div>
                    <hr>
                    
                    <form class="forms-sample">
                    
                    <div class="col-md-4">
                    <div class="form-group row">
                    
                    <div class="col-12 col-md-6">
                    <label>Select anyone</label>
                        <select class="js-example-basic-single" style="width:100%"> 
                            <option value="Regular">Please select</option>
                            <option value="">1</option>
                            <option value="">2</option>
                            <option value="">3</option>
                            <option value="">4</option>
                            <option value="">5</option>
                            <option value="">6</option>
                            <option value="">7</option>
                            <option value="">8</option>
                            <option value="">9</option>
                            <option value="">10</option>
                            <option value="">11</option>
                            <option value="">12</option>
                        </select>
                    </div>
                    
                   
                    
                    <div class="col-6 col-md-6 mt-4">
                    <button type="submit" class="btn btn-success mt-2">Search</button>
                    </div>
                    
                    </div> 
                    </div>
                    
                    <div class="col-md-8">
                    <div class="row">
                    <div class="col-12">	
                    <label class="">Search by alphabet</label>
                    </div>
                    <div class="col-12">	
                    <button type="button" class="btn btn-primary btn-xs">A</button>
                    <button type="button" class="btn btn-primary btn-xs">B</button>
                    <button type="button" class="btn btn-primary btn-xs">C</button>
                    <button type="button" class="btn btn-primary btn-xs">D</button>
                    <button type="button" class="btn btn-primary btn-xs">E</button>
                    <button type="button" class="btn btn-primary btn-xs">F</button>
                    <button type="button" class="btn btn-primary btn-xs">G</button>
                    <button type="button" class="btn btn-primary btn-xs">H</button>
                    <button type="button" class="btn btn-primary btn-xs">I</button>
                    <button type="button" class="btn btn-primary btn-xs">J</button>
                    <button type="button" class="btn btn-primary btn-xs">K</button>
                    <button type="button" class="btn btn-primary btn-xs">L</button>
                    <button type="button" class="btn btn-primary btn-xs">M</button>
                    <button type="button" class="btn btn-primary btn-xs">N</button>
                    <button type="button" class="btn btn-primary btn-xs">O</button>
                    <button type="button" class="btn btn-primary btn-xs">P</button>
                    <button type="button" class="btn btn-primary btn-xs">Q</button>
                    <button type="button" class="btn btn-primary btn-xs">R</button>
                    <button type="button" class="btn btn-primary btn-xs">S</button>
                    <button type="button" class="btn btn-primary btn-xs">T</button>
                    <button type="button" class="btn btn-primary btn-xs">U</button>
                    <button type="button" class="btn btn-primary btn-xs">V</button>
                    <button type="button" class="btn btn-primary btn-xs">W</button>
                    <button type="button" class="btn btn-primary btn-xs">X</button>
                    <button type="button" class="btn btn-primary btn-xs">Y</button>
                    <button type="button" class="btn btn-primary btn-xs">Z</button>
                    </div>
                    </div>
                    </div>
                    
                    </form>
                
                </div>
                </div>
              
                  
            </div>
            
            
            <div class="col-md-12 grid-margin stretch-card">
              	<div class="card">
                <div class="card-body">
                
                	<!-- TABLE Filters btn -->
                   
                    
                    <!-- TABLE STARTS -->
                    <div class="col mt-3">
                    	 <div class="row">
                            <div class="col-12">
                              <table id="order-listing" class="table">
                                <thead>
                                  <tr>
                                      <th>Sr No</th>
                                      <th>Product</th>
                                      <th>MRP</th>
                                      <th>MFG. Co.</th>
                                      <th>Batch</th>
                                      <th>Expiry</th>
                                      <th>Qty.</th>
                                      <th>Rack No.</th>
                                      <th>Self No.</th>
                                      <th>Box No.</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts --> 	
                                  <tr>
                                      <td>1267</td>
                                      <td>501 SOAP 300gm</td>
                                      <td>94.40</td>
                                      <td>&nbsp;</td>
                                      <td>456</td>
                                      <td>1/21</td>
                                      <td>2</td>
                                      <td>
                                        <input type="text" class="form-control">
                                      </td>
                                      <td>
                                        <input type="text" class="form-control">
                                      </td>
                                      <td>
                                        <input type="text" class="form-control">
                                      </td>
                                  </tr><!-- End Row --> 	
                                  
                                   <tr>
                                      <td>1267</td>
                                      <td>501 SOAP 300gm</td>
                                      <td>94.40</td>
                                      <td>&nbsp;</td>
                                      <td>456</td>
                                      <td>1/21</td>
                                      <td>2</td>
                                      <td>
                                        <input type="text" class="form-control">
                                      </td>
                                      <td>
                                        <input type="text" class="form-control">
                                      </td>
                                      <td>
                                        <input type="text" class="form-control">
                                      </td>
                                  </tr>
                                  
                                   <tr>
                                      <td>1267</td>
                                      <td>501 SOAP 300gm</td>
                                      <td>94.40</td>
                                      <td>&nbsp;</td>
                                      <td>456</td>
                                      <td>1/21</td>
                                      <td>2</td>
                                      <td>
                                        <input type="text" class="form-control">
                                      </td>
                                      <td>
                                        <input type="text" class="form-control">
                                      </td>
                                      <td>
                                        <input type="text" class="form-control">
                                      </td>
                                  </tr>
                                  
                                   <tr>
                                      <td>1267</td>
                                      <td>501 SOAP 300gm</td>
                                      <td>94.40</td>
                                      <td>&nbsp;</td>
                                      <td>456</td>
                                      <td>1/21</td>
                                      <td>2</td>
                                      <td>
                                        <input type="text" class="form-control">
                                      </td>
                                      <td>
                                        <input type="text" class="form-control">
                                      </td>
                                      <td>
                                        <input type="text" class="form-control">
                                      </td>
                                  </tr>
                                  
                                   <tr>
                                      <td>1267</td>
                                      <td>501 SOAP 300gm</td>
                                      <td>94.40</td>
                                      <td>&nbsp;</td>
                                      <td>456</td>
                                      <td>1/21</td>
                                      <td>2</td>
                                      <td>
                                        <input type="text" class="form-control">
                                      </td>
                                      <td>
                                        <input type="text" class="form-control">
                                      </td>
                                      <td>
                                        <input type="text" class="form-control">
                                      </td>
                                  </tr>
                                  
                                 
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
  
  
  <!-- End custom js for this page-->
  <?php include('include/usertypecheck.php'); ?>
</body>


</html>
