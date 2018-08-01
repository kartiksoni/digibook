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
            
            <hr>
            
            <!-- Vendor Bank Managment Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Vendor Bank Management </h4>
                 <hr class="alert-dark">
                 <br>
                
                  <form class="forms-sample">
                  
                  <div class="form-group row">
                      <div class="col-12 col-md-3">
                        <label for="exampleInputName1">Bank Name</label>
                      	<input type="text" class="form-control" id="exampleInputName1" placeholder="Bank Name">
                      </div>
                      <div class="col-12 col-md-3">
                        <label for="exampleInputName1">Bank A/c. No</label>
                      <input type="text" class="form-control" id="exampleInputName1" placeholder="Bank A/c. No">
                      </div>
                      
                      <div class="col-12 col-md-3">
                        <label for="exampleInputName1">Branch Name</label>
                      <input type="text" class="form-control" id="exampleInputName1" placeholder="Branch Name">
                      </div>
                      
                      <div class="col-12 col-md-3">
                        <label for="exampleInputName1">Branch Name</label>
                      <input type="text" class="form-control" id="exampleInputName1" placeholder="Branch Name">
                      </div>
                      
                    </div>
                    
                    
                    <br>
                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                    
                  </form>
                </div>
              </div>
            </div>
            
            
            
            
            <!-- Customer Managment Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Customer Managment </h4>
                  <hr class="alert-dark">
                  <br>
                  <form class="forms-sample">
                  
                  <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Customer Type </label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                <option value="Regular">Reseller</option>
                                <option value="Unregistered">End User</option>
                            </select>
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">ID Number</label>
                      <input type="text" class="form-control" id="exampleInputName1" placeholder="ID Number">
                      </div>
                      
                       <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Customer Name</label>
                      	<input type="text" class="form-control" id="exampleInputName1" placeholder="Customer Name">
                      </div>
                      
                     
                    </div>
                    
                    <div class="form-group row">
                     
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Short Name</label>
                      <input type="text" class="form-control" id="exampleInputName1" placeholder="Short Name">
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">DOB</label>
                        <input class="form-control" data-inputmask="'alias': 'date'" />
                      </div>
                      
                       <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Gender</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                <option value="Regular">Male</option>
                                <option value="Unregistered">Female</option>
                            </select>
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
                        <input class="form-control" data-inputmask="'alias': 'email'" />
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
                        <label for="exampleInputName1">Aadhar Card No</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Aadhar Card No">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">D.L. No.1</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="D.L. No.1">
                        </div>
                        
                         <div class="col-12 col-md-4">
                        <label for="exampleInputName1">D.L. No.1</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="D.L. No.1">
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
                    
                    <div class="form-group row">
                    
                    
                            
                            <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Reseller Display Price </label>
                            <div class="row no-gutters">
                                    <div class="col-12 col-md-6">
                                    	<input type="text" class="form-control" id="exampleInputName1" placeholder="Local">
                                    </div>
                                    <div class="col-12 col-md-6">
                                    	<input type="text" class="form-control" id="exampleInputName1" placeholder="Out">
                                    </div>
                                </div>
                            </div>
                            
                           
                    </div>
                    
                    <br>
                    
                    
                    
                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                    
                  </form>
                </div>
              </div>
            </div>
            
            
            <!-- Product Master Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Product Master</h4>
                  <hr class="alert-dark">
                  <br>
                  <form class="forms-sample">
                  
                  <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Product Name</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Product Name">
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Generic Name </label>
                      <input type="text" class="form-control" id="exampleInputName1" placeholder="Generic Name ">
                      </div>
                      
                       <div class="col-12 col-md-4">
                        <label for="exampleInputName1">MFG. Company</label>
                      	<input type="text" class="form-control" id="exampleInputName1" placeholder="MFG. Company">
                      </div>
                      
                     
                    </div>
                    
                    <div class="form-group row">
                     
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Schedule Category</label>
                      	<select class="js-example-basic-single" style="width:100%"> 
                                <option value="Regular">cat</option>
                                <option value="Unregistered">cat2</option>
                            </select>
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Product  Type</label>
                        <select class="js-example-basic-single" style="width:100%"> 
                                <option value="Regular">cat</option>
                                <option value="Unregistered">cat2</option>
                            </select>
                      </div>
                      
                       <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Product Category</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                <option value="Regular">Alaska</option>
                                <option value="Unregistered">Alaska</option>
                            </select>
                        </div>
                        
                    </div>
                    
                   
                    
                    
                    <div class="form-group row">
                        
                        <div class="col-12 col-md-4">
                        	<label for="exampleInputName1">Sub Category</label>
                        	<select class="js-example-basic-single" style="width:100%"> 
                                <option value="Regular">Sub Cat</option>
                                <option value="Unregistered">Sub Cat</option>
                            </select>
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">HSN Code</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="HSN Code">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Batch No</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Batch No">
                        </div>
                        
                       <!--  <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Email</label>
                        <input class="form-control" data-inputmask="'alias': 'email'" />
                        </div>-->
                        
                    </div>
                    
                    <div class="form-group row">
                    
                       
                        
                        <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Expiry Date</label>
                            <div id="datepicker-popup" class="input-group date datepicker">
                            <input type="text" class="form-control border" >
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Opening Qty</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Opening Qty">
                        </div>
                        
                         <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Opening Qty in Godown</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Opening Qty in Godown">
                        </div>
                    
                    </div>
                    
                    <div class="form-group row">
                       
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Give a New MRP</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Give a New MRP">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Ex. Duty</label>
                        <div class="input-group">
                          <div class="input-group-prepend bg-dark">
                            <span class="input-group-text bg-transparent"><i class="mdi mdi-percent text-white"></i></span>
                          </div>
                          <input type="text" class="form-control" placeholder="Ex. Duty" aria-label="Ex. Duty">
                        </div>
                        </div>
                        
                         <div class="col-12 col-md-4">
                        <label for="exampleInputName1">IGST</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="IGST">
                        </div>
                        
                    
                    </div>
                    
                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">CGST</label>
                      	<input type="text" class="form-control" id="exampleInputName1" placeholder="CGST">
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">SGST</label>
                      <input type="text" class="form-control" id="exampleInputName1" placeholder="SGST">
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Inward Rate</label>
                      <input type="text" class="form-control" id="exampleInputName1" placeholder="INWARD Rate">
                      </div>
                      
                    </div>
                    
                    
                    <div class="form-group row">
                      
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Distributor Rate</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Distributor Rate">
                          </div>
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Sales Rate</label>
                              <div class="row no-gutters">
                                  <div class="col-12 col-md-6">
                                 <input type="text" class="form-control" id="exampleInputName1" placeholder="Local">
                                    </div>
                                   <div class="col-12 col-md-6">
                                    <input type="text" class="form-control" id="exampleInputName1" placeholder="Out">
                                   </div>
                                </div>
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Rack No</label>
                          <input type="text" class="form-control" id="exampleInputName1" placeholder="Rack No">
                          </div>
                      
                     
                    </div>
                    
                    <div class="form-group row">
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Self No</label>
                    <input type="text" class="form-control" id="exampleInputName1" placeholder="Self No">
                    </div>
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Box No</label>
                    <input type="text" class="form-control" id="exampleInputName1" placeholder="Box No">
                    </div>
                    
                    <div class="col-12 col-md-4">
	                    <label for="exampleInputName1">Company Code</label>
                    	<input type="text" class="form-control" id="exampleInputName1" placeholder="Company Code">
                    </div>
                    </div>
                    
                    <div class="form-group row">
                            
                            <div class="col-12 col-md-4">
                                <label for="exampleInputName1">Opening Stock Rs</label>
                                <input type="text" class="form-control" id="exampleInputName1" placeholder="Opening Stock Rs">
                            </div>
                            
                            <div class="col-12 col-md-4">
                                <label for="exampleInputName1">Unit / Strip / Pack</label>
                                <input type="text" class="form-control" id="exampleInputName1" placeholder="Opening Stock Rs">
                            </div>
                            
                            <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Min Qty.</label>
                      			<input type="text" class="form-control" id="exampleInputName1" placeholder="Min Qty.">
                            </div>

                    </div>
                    
                    <div class="form-group row">
                    
                    
                            
                            <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Max Qty.</label>
                      			<input type="text" class="form-control" id="exampleInputName1" placeholder="Max Qty.">
                            </div>
                            
                            
                    </div>
                    
                    <br>
                    
                    
                    
                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                    
                  </form>
                </div>
              </div>
            </div>
            
            
            <!-- Service Master Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Service Master</h4>
                  <hr class="alert-dark">
                  <br>
                  <form class="forms-sample">
                  
                  <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Service Name</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Service Name">
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">SAC Code</label>
                      <input type="text" class="form-control" id="exampleInputName1" placeholder="SAC Code">
                      </div>
                      
                     
                      
                     
                    </div>
                    
                    <div class="form-group row">
                    
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">CGST</label>
                      	<input type="text" class="form-control" id="exampleInputName1" placeholder="CGST">
                      </div>
                     
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">SGST</label>
                      	<input type="text" class="form-control" id="exampleInputName1" placeholder="SGST">
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">IGST</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="IGST">
                      </div>
                        
                    </div>
                    
                    <br>
                    
                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                    
                  </form>
                </div>
              </div>
            </div>
            
            <!-- Financial Year Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Financial Year</h4>
                  <hr class="alert-dark">
                  <br>
                  <form class="forms-sample">
                  
                  <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Financial year</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Financial year">
                      </div>
                  </div>    
                      
                      
                      <div class="form-group row">
                      
                      <div class="col-12 col-md-4">
                                
                                <div class="row no-gutters">
                                
                                    <div class="col-12 col-md-6">
                                        <label for="exampleInputName1">Start Date</label>
                                             <div id="datepicker-popup1" class="input-group date datepicker">
                                            <input type="text" class="form-control">
                                            <span class="input-group-addon input-group-append border-left">
                                              <span class="mdi mdi-calendar input-group-text"></span>
                                            </span>
                                          </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                         <label for="exampleInputName1">End Date</label>
                                        <div id="datepicker-popup2" class="input-group date datepicker">
                                        <input type="text" class="form-control">
                                        <span class="input-group-addon input-group-append border-left">
                                          <span class="mdi mdi-calendar input-group-text"></span>
                                        </span>
                                      </div>
                                    </div>
                                
                                </div>
                            </div>
                  
                     
                      </div>
                    
                    <div class="form-group row">
                     
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
                        
                    </div>
                    
                 
                    <br>
                    
                    
                    
                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                    
                  </form>
                </div>
              </div>
            </div>
            
            
             <!-- Pharmacy Profile Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Pharmacy Profile</h4>
                  <hr class="alert-dark">
                  <br>
                  <form class="forms-sample">
                  <h5 class="card-title">Pharmacy Details</h5>
                  <hr>
                  
                    <div class="form-group row">
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Select Firm</label>
                    <select class="js-example-basic-single" style="width:100%"> 
                    <option value="Regular">Regular</option>
                    <option value="Unregistered">Unregistered</option>
                    <option value="Composition">Composition</option>
                    </select>
                    </div>
                    
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Pharmacy Name</label>
                    <input type="text" class="form-control" id="exampleInputName1" placeholder="Pharmacy Name">
                    </div>
                    
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Contact Person Name</label>
                    <input type="text" class="form-control" id="exampleInputName1" placeholder="Contact Person Name">
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
                    <label for="exampleInputName1">Country</label>
                    <select class="js-example-basic-single" style="width:100%"> 
                    <option value="Regular">Regular</option>
                    <option value="Unregistered">Unregistered</option>
                    <option value="Composition">Composition</option>
                    </select>
                    </div>
                    
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">State</label>
                    <select class="js-example-basic-single" style="width:100%"> 
                    <option value="Regular">Regular</option>
                    <option value="Unregistered">Unregistered</option>
                    <option value="Composition">Composition</option>
                    </select>
                    </div>
                    
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">District</label>
                    <input type="text" class="form-control" id="exampleInputName1" placeholder="District">
                    </div>
                    
                    </div>
                    
                    <div class="form-group row">
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">City</label>
                    <input type="text" class="form-control" id="exampleInputName1" placeholder="City">
                    </div>
                    
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Pincode</label>
                    <input type="text" class="form-control" id="exampleInputName1" placeholder="Pincode">
                    </div>
                    
                    </div>
                    
                  <br>  
                  <h5 class="card-title">Contact Details</h5>
                  <hr>
                  
                    <div class="form-group row">
                    
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Telephone No</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Telephone No">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Mobile No</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Mobile No">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Email</label>
                        <input class="form-control" data-inputmask="'alias': 'email'" />
                        </div>
                    
                    </div>
                    
                    
                    
                  <br>  
                  <h5 class="card-title">Financial Details</h5>
                  <hr>
                  
                    <div class="form-group row">
                    
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">PAN No</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="PAN No">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">GST No</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="GST No">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">DL No</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="DL No">
                        </div>
                    
                    </div>
                    
                    <div class="form-group row">
                    
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">DL No</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="DL No">
                        </div>
                        
                         <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Drug Lic. Ex. Date</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Drug Lic. Ex. Date">
                        </div>
                        
                       
                    
                    </div>
                    
                    <div class="form-group row">
                    
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Shop Act License</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Shop Act License">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Exp. Date</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Exp. Date">
                        </div>
                    
                    </div>
                    
                    
                    
                  <br>
                    
                  <h5 class="card-title">Pharmacist Details </h5>
                  
                  <div class="pharmacist-detail-section">
                  <hr>
                  
                    <div class="form-group row">
                    
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Pharmacist Name</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Pharmacist Name">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Pharmacist Reg. No</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Pharmacist Reg. No">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Pharmacist Reg. No Exp. Date</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Pharmacist Reg. No Exp. Date">
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
                        <label for="exampleInputName1">Contact No</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Contact No">
                        </div>
                        
                         <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Photo</label>
                        <input type="file" name="img[]" class="file-upload-default">
                      	<div class="input-group col-xs-12">
                        <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                        <span class="input-group-append">
                          <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                        </span>
                      </div>
                        </div>
                    
                    </div>
                    
                    <div class="form-group row">
                    
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Aadhar Card No</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Aadhar Card No">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Exp. Date</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="PAN Card No">
                        </div>
                    
                    </div> 
                    
                 </div>   
                 
                 <div class="row">
                 <div class="col pt-1">
                 	<a class="btn btn-primary btn-sm" style="color:#fff;"><i class="fa fa-plus"></i> Add more</a>
                 </div>   
                 </div>
                      
                    
                    
                  
                    
                    <br>
                    
                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                    
                  </form>
                </div>
              </div>
            </div>
            
            <!-- Bank Management Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Bank Management</h4>
                  <hr class="alert-dark">
                  <br>
                  <form class="forms-sample">
                  
                  <div class="form-group row">
                  
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Bank Name</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Enter Name">
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Bank Address</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Enter Address">
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Bank A/C No</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Bank A/C No">
                      </div>
                    
                  </div> 
                  
                  
                   <div class="form-group row">
                   
                   	<div class="col-12 col-md-4">
                        <label for="exampleInputName1">Bank IFSC Code</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Enter IFSC Code">
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Opening Balance</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Enter Opening Balance">
                      </div>
                      
                   </div>
                  
                 
                    <br>
                    
                    
                    
                    <button type="submit" class="btn btn-success mr-2">Save Bank Details</button>
                    <button class="btn btn-light">Cancel</button>
                    
                  </form>
                </div>
              </div>
            </div>
            
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
                            
                            
                           <!-- <div class="dropdown" style="display:inline-block">
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
                            <option value="Unregistered">MRP</option>
                            <option value="Composition">Product Name </option>
                            <option value="Composition">Generic Name</option>
                        </select>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <label >Enter Generic</label>
                        <div id="bloodhound">
                        <input class="typeahead" type="text" placeholder="States of USA">
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-12">
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
                    <div class="col mt-4">
                        <button type="button" class="btn btn-outline-success btn-sm">All(438)</button>
                        <button type="button" class="btn btn-outline-success btn-sm">Available</button> 	
                        <button type="button" class="btn btn-outline-success btn-sm">Expiry(125)</button> 	
                        <button type="button" class="btn btn-outline-success btn-sm">Zero Stock(42)</button> 	
                        <button type="button" class="btn btn-outline-success btn-sm">Over Stock(00)</button>
                        <button type="button" class="btn btn-outline-success btn-sm">Non Moving Stock</button> 	
                        <button type="button" class="btn btn-outline-success btn-sm">Reorder</button> 	 	
                        
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
                        <button type="submit" class="btn btn-success mt-2">Search</button>
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
                        <button type="submit" class="btn btn-success mt-2">Search</button>
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
                              <table id="order-listing1" class="table">
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
                    
                    <hr>
                     <!-- OVER STOCK TABLE STARTS -->
                    <div class="col mt-3">
                    <h4 class="card-title">Over stock Table</h4>
                    <hr class="alert-dark">
                    	 <div class="row">
                            <div class="col-12">
                              <table id="order-listing2" class="table">
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
