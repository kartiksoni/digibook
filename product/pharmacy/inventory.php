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
  <link rel="stylesheet" href="vendors/iconfonts/simple-line-icon/css/simple-line-icons.css">
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
                    <h4 class="card-title">Inventory</h4>
                    <hr class="alert-dark">
                    <br>
                
                    <div class="row">
                    
                      <div class="col-12 col-md-10 col-sm-12">
                          <div class="enventory">
                              <a href="#" class="btn btn-dark active">Inventory</a>
                              <a href="#" class="btn btn-dark">Update Inventory </a>
                              <a href="#" class="btn btn-dark">Inventory Setting </a>
                              <a href="#" class="btn btn-dark">Product Master </a>
                              <a href="#" class="btn btn-dark">Self Consumption </a>
                          </div>          
                      </div> 
                      
                      <div class="col-12 col-md-2">
                        <button type="button" class="btn btn-grey-1 btn-rounded pull-right"><strong>*HSN Code Reference</strong></button>
                      </div>
                    
                    </div>

                    <hr>
                    
                    <form class="forms-sample">
                      <div class="row">
                      
                        <div class="col-md-5">
                            <div class="form-group row">
                            
                              <div class="col-12 col-md-5">
                                <label>Select anyone</label>
                                  <select class="js-example-basic-single" style="width:100%"> 
                                      <option value="product">Product Name </option>
                                      <option value="mrp">MRP</option>
                                      <option value="generic">Generic Name</option>
                                  </select>
                              </div>
                              
                              <div class="col-12 col-md-4">
                                  <label >Enter Generic</label>
                                  <div id="bloodhound">
                                  <input class="form-control" type="text" placeholder="1235" disabled>
                                  </div>
                              </div>
                              
                              <div class="col-12 col-md-2">
                                <button type="submit" class="btn btn-success mt-30">Search</button>
                              </div>
                            
                            </div> 
                        </div>
                        
                        <div class="col-md-7">
                          <div class="row">
                            <div class="col-12">  
                              <label class="">Search by alphabet</label>
                            </div>
                            <div class="col-12">  
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">A</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">B</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">C</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">D</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">E</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">F</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">G</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">H</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">I</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">J</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">K</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">L</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">M</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">N</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">O</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">P</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">Q</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">R</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">S</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">T</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">U</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">V</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">W</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">X</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">Y</a>
                              <a href="#" class="btn btn-primary filter-alphabet btn-xs">Z</a>
                            </div>
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
                    <div class="col mt-4">
                        <a href="#" class="btn btn-outline-success btn-sm">All(438)</a>
                        <a href="#" class="btn btn-outline-success btn-sm">Available</a>  
                        <a href="#" class="btn btn-outline-success btn-sm">Expiry(125)</a>  
                        <a href="#" class="btn btn-outline-success btn-sm">Zero Stock(42)</a>   
                        <a href="#" class="btn btn-outline-success btn-sm">Over Stock(00)</a>
                        <a href="#" class="btn btn-outline-success btn-sm">Non Moving Stock</a>   
                        <a href="#" class="btn btn-outline-success btn-sm">Reorder</a>
                    </div>
                    <hr>
                    
                    <!-- NON Moving Filter Extra Section -->
                      <!-- Show hide on non-moving filter btn -->
                    <div class="col">
                        <div class="form-group row">
                          <div class="col-12 col-md-4">
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
                          <div class="col-6 col-md-4 mt-4">
                            <a href="#" class="btn btn-success mt-1">Search</a>
                          </div>
                        </div> 
                    </div><!-- End NON Moving Filter Extra Section -->
                    <hr>
                    
                    <!-- OVER STOCK Filter Extra Section -->
                      <!-- Show hide on OVER STOCK  filter btn -->
                    <div class="col">
                        <div class="form-group row">
                          <div class="col-12 col-md-4">
                            <label>Sales Percentage wise</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                <option value="Regular">Please select</option>
                                <option value="">60</option>
                                <option value="">75</option>
                                <option value="">90</option>
                            </select>
                          </div>
                          <div class="col-6 col-md-4 mt-4">
                            <a href="#" class="btn btn-success mt-1">Search</a>
                          </div>
                        </div> 
                    </div><!-- End OVER STOCK  Filter Extra Section -->
                    <hr>
                    
                    
                    
                    <!-- INVENTORY TABLE STARTS -->
                    <div class="col mt-3">
                      <h4 class="card-title">Common Table</h4>
                      <hr class="alert-dark">
                        <div class="row">
                          <div class="col-12">
                            <table class="table datatable">
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
                                      5
                                    </td>
                                    <td>
                                      3
                                    </td>
                                    <td>
                                      6
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
                                      5
                                    </td>
                                    <td>
                                      3
                                    </td>
                                    <td>
                                      6
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
                                      5
                                    </td>
                                    <td>
                                      3
                                    </td>
                                    <td>
                                      6
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
                                      5
                                    </td>
                                    <td>
                                      3
                                    </td>
                                    <td>
                                      6
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
                                      5
                                    </td>
                                    <td>
                                      3
                                    </td>
                                    <td>
                                      6
                                    </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                    
                    <hr>
                     <!-- OVER STOCK TABLE STARTS -->
                    <div class="col mt-3">
                    <h4 class="card-title">Over stock Table</h4>
                    <hr class="alert-dark">
                    <div class="row">
                      <div class="col-12">
                        <table class="table datatable">
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
                                <th>Min Qty.</th>
                                <th>Reorder</th>
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
                                  5
                                </td>
                                <td>
                                  3
                                </td>
                                <td>
                                  6
                                </td>
                                <td>2</td>
                                <td>2</td>
                            </tr><!-- End Row --> 
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
  
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('.datatable').DataTable();
  </script>
  
  <!-- End custom js for this page-->
</body>


</html>
