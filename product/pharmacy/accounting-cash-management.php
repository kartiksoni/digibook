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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("#showradio").click(function(){
        $(".show").show("fast");
    });

    $("#hideradio").click(function(){
        $(".show").hide("fast");
    });
});
</script>
</head>
<body>
  <div class="container-scroller">
  
    <!-- Topbar -->
        <?php include "include/topbar.php" ?>
    
    <?php
      function getvoucherno(){
        global $conn;
        $voucher_no = '';

        $voucherqry = "SELECT voucher_no FROM accounting_cash_management WHERE payment_type = 'active' ORDER BY id DESC LIMIT 1";
        $voucherrun = mysqli_query($conn, $voucherqry);
        if($voucherrun){
          $count = mysqli_num_rows($voucherrun);
          if($count !== '' && $count !== 0){
            $row = mysqli_fetch_assoc($voucherrun);
            $voucherno = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';

            if($voucherno != ''){
              $vouchernoarr = explode('-',$voucherno);
              $voucherno = $vouchernoarr[1];
              $voucherno = $voucherno + 1;
              $voucherno = sprintf("%05d", $voucherno);
              $voucher_no = 'CP-'.$voucherno;
            }
          }else{
            $voucherno = sprintf("%05d", 1);
            $voucher_no = 'CP-'.$voucherno;
          }
        }
        return $voucher_no;
      }
    
    ?>
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
          
          
           <!-- Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                
                  <!-- Main Catagory -->
                    <div class="row">
                    <div class="col-12">
                        <div class="purchase-top-btns">
                            <a href="accounting-cash-management.php" class="btn btn-dark active">Cash Management</a>
                            <a href="accounting-customer-receipt.php" class="btn btn-dark btn-fw">Customer Receipt</a>
                            <a href="accounting-cheque.php" class="btn btn-dark  btn-fw">Cheque</a>
                            <a href="#" class="btn btn-dark  btn-fw">Vendor Payment</a>
                            <a href="#" class="btn btn-dark  btn-fw">Financial Year Settings</a>
                            <a href="purchase-return.php" class="btn btn-dark  btn-fw">Credit Note / Purchase Note</a>
                            <a href="#" class="btn btn-dark  btn-fw">Quotation / Estimate / Proformo Invoice</a>
                            
                        </div>   
                    </div> 
                    </div>
                    <hr>
                    
                    <!-- First Row  -->
                    <form class="forms-sample">
                        <div class="form-group row">
                        
                        <div class="col-12 col-md-6">
                              <label for="exampleInputName1">Select Type</label>
                            <div class="row no-gutters">
                                    
                                        <div class="col">
                                            <div class="form-radio">
                                            <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="Active" checked>
                                           		Cash Payment
                                            </label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12 col-md-9">
                                            <div class="form-radio">
                                            <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios2" value="Deactive">
                                            Cash Receipt
                                            </label>
                                            </div>
                                        </div>
                                    </div>
                        </div>

                        <div class="col-12 col-md-3">
                              <label for="exampleInputName1">Reverse Change</label>
                            <div class="row no-gutters">
                                    
                                        <div class="col">
                                            <div class="form-radio">
                                            <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="reverseradio" id="hideradio" value="Deactive" checked>
                                           		No
                                            </label>
                                            </div>
                                        </div>
                                        
                                        <div class="col col-md-8">
                                            <div class="form-radio">
                                            <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="reverseradio" id="showradio" value="Active">
                                            Yes
                                            </label>
                                            </div>
                                        </div>

                                        <div class="show" style="display:none;">
                                        <div class="col">
                                            <div class="form-radio">
                                            <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="radio" id="" value="Active">
                                            5%
                                            </label>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-radio">
                                            <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="radio" id="" value="Active">
                                            12%
                                            </label>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-radio">
                                            <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="radio" id="" value="Active">
                                            18%
                                            </label>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                        </div>  
                        
                        
                         <div class="col-12">
                              <label for="exampleInputName1" class="pull-right bg-success color-white p-2">Running Balance: 123456</label>
                         </div>     
                        
                        </div>
                      
                        <div class="form-group row">
                        
                        <div class="col-12 col-md-2">
                        <label for="exampleInputName1">Voucher No.</label>
                        <?php
                              $voucher = getvoucherno();                          
                        ?>
                        <input type="text" class="form-control" id="exampleInputName1" value="<?php echo $voucher;
                        ?>" placeholder="Voucher No.">
                        </div>
                        
                        <div class="col-12 col-md-2">
                        <label for="exampleInputName1">Date</label>
                        <div id="datepicker-popup" class="input-group date datepicker">
                            <input type="text" class="form-control border" value="<?php echo date("d/m/y");?>">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
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
                
                    <!-- TABLE STARTS -->
                    <div class="col mt-3">
                    	 <div class="row">
                            <div class="col-12">
                              <table id="order-listing1" class="table">
                                <thead>
                                  <tr>
                                      <th>Credit/Debit</th>
                                      <th>Amount</th>
                                      <th>Type</th>
                                      <th>Perticulars</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts --> 	
                                  <tr>
                                      <td><input type="text" class="form-control" id="exampleInputName1" placeholder="Cr/Dr"></td>
                                      <td><input type="text" class="form-control" id="exampleInputName1" placeholder="Enter Amount"></td>
                                      <td><select class="js-example-basic-single" style="width:100%" id="group"> 
                                            <option value="">Please Select</option>
                                            <?php
                                            $dataqry = "SELECT * FROM `group`";
                                            $datarun = mysqli_query($conn, $dataqry);
                                            while($data = mysqli_fetch_assoc($datarun)){?>
                                            <option value="<?php echo $data['id'];?>"> <?php echo $data['name']; ?></option>
                                            <?php } ?>
                                            </select>
                                      </td>
                                      <td>
                                      <select class="js-example-basic-single" style="width:100%" id="ledger"> 
                                            <option value="">Please Select</option>
                                            <?php
                                            $sqlqry = "SELECT * FROM `ledger_master` WHERE group_id = '".$data['id']."'";
                                            $sqlqryrun = mysqli_query($conn, $sqlqry);
                                            while($sqldata = mysqli_fetch_assoc($sqlqryrun)){?>
                                            <option value="<?php echo $sqldata['id']; ?>"> <?php echo $sqldata['name']; ?></option>  
                                            <?php } ?>
                                        </select>
                                      </td>
                                      
                                  </tr><!-- End Row --> 	
                                   <tr>
                                   		
                                      <td>
                                          <label for="exampleInputName1">Remarks</label>
                                          <textarea  class="form-control" id="exampleInputName1" placeholder="Remarks" rows="3"></textarea>
                                      </td>
                                      <td colspan="3">&nbsp;</td>
                                   </tr>   
                               
                                  
                                 
                                </tbody>
                              </table>
                            
                            </div>
                            <hr>
                            
                            <div class="col-12">
                            	<a href="'#" class="btn btn-dark mt-30">Back</a>
                                <a href="'#" class="btn btn-success mt-30">Submit</a>
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
        
        
        <!-- Add Customer Model -->
        <div class="modal fade" id="exampleModal-4" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
              
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel">Add Customer</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <div class="modal-body">
                  <form>
                  
                    <div class="form-group row">
                  
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Vendor Name</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Vendor Name">   
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">DC Date</label>
                            <div id="datepicker-popup3" class="input-group date datepicker">
                            <input type="text" class="form-control border" >
                            <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                            </div>
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">DC No.</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="DC No.">
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Product</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Product">
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Batch</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Batch">
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Expiry Date</label>
	                        <div id="datepicker-popup4" class="input-group date datepicker">
                            <input type="text" class="form-control border" >
                            <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                            </div>

                          </div>
                          
                           <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Units/Strip</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Units/Strip">
                          </div>
                          
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">No. of Strips</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="No. of Strips">
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Free Strips</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Free Strips">
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">GST Total%</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">H</option>
                                    <option value="Unregistered">H1</option>
                                </select>
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Price/Strip</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Price/Strip">
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Selling/Strip</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Selling/Strip">
                          </div>
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">MRP/Strip</label>
                           <input type="text" class="form-control" id="exampleInputName1" placeholder="MRP/Strip">
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Discount%</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Discount%">
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">HSN Code</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="HSN Code">
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Rack No.</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Rack No.">
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Box No.</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Box No.">
                          </div>
                          
                          
                          
                          
                          
                        
                          
                         
                        
                      </div>
                    
                    
                  </form>
                </div>
                
                <div class="modal-footer">
                  <button type="button" class="btn btn-success">Send message</button>
                  <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
        </div>
        
        <!-- Alternates Model -->
        <div class="modal fade" id="exampleModal-5" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
              
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel">Product Alternate Selection</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <div class="modal-body">
                  <form>
                  
                    <div class="form-group row">
                  
                          <div class="col-12 col-md-6">
                            <label for="exampleInputName1">Product</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">Search</option>
                                    <option value="Unregistered">abc</option>
                                </select>
                          </div>
                          
                          <div class="col-12 col-md-6">
                            <label for="exampleInputName1">Generic Name</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">Search</option>
                                    <option value="Unregistered">H1</option>
                                </select>
                          </div>
                          
                          <div class="col-12 col-md-6">
                            <label for="exampleInputName1">Type</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Type">
                          </div>
                          
                          <div class="col-12 col-md-6">
                            <label for="exampleInputName1">Schedule</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Schedule">
                          </div>
                          
                          <div class="col-12 col-md-6">
                            <label for="exampleInputName1">Manufacturer</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Manufacturer">
                          </div>
         
                        
                      </div>
                    
                    
                  </form>
                </div>
                
                <div class="modal-footer">
                  <button type="button" class="btn btn-success">Add</button>
                  <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
        </div>
        
        <!-- Missed Order Model -->
        <div class="modal fade" id="exampleModal-6" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
              
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel">Missed Orders</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <div class="modal-body">
                  <form>
                  
                    <div class="form-group row">
                  
                          <div class="col-12 col-md-6">
                            <label for="exampleInputName1">Product</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">Search</option>
                                    <option value="Unregistered">abc</option>
                                </select>
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Qty.</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="00">
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Unit/MRP</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="00">
                          </div>
                          
                          <div class="col-12 col-md-3">
                           <button type="button" class="btn btn-success btn-xs mt-30">Add</button>
                          </div>
                          
                           <div class="col-12 col-md-3 mt-30">
                           <a href="#" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#exampleModal-7" data-whatever="">Add New Product</a>
                          </div>
                          
                         
                        
                      </div>
                    
                    
                  </form>
                  
                  <div class="row">
                  <div class="col-12">
                  	<table id="order-listing1" class="table">
                                <thead>
                                  <tr>
                                      <th>Product</th>
                                      <th>Qty.</th>
                                      <th>Unit/MRP</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts --> 	
                                  <tr>
                                      <td>O133</td>
                                      <td> 14/07/2018</td>
                                      <td>567 / O133</td>
                                      <td>
                                      	
                                        <a href="#" class="btn  btn-success p-2"><i class="icon-pencil mr-0"></i></a>
                                        <a href="#" class="btn  btn-danger p-2"><i class="icon-close mr-0"></i></a>
                                      </td>
                                  </tr><!-- End Row --> 	
                                  
                                
                                  
                                 
                                </tbody>
                              </table>            
                  </div>
                  </div>
                  
                  
                  
                  
                  
                  
                  
                </div>
                
                <div class="modal-footer">
                  <button type="button" class="btn btn-success">Add Order</button>
                  <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
        </div>
        
        
    	<!-- Missed order sub - Add new product Model -->
        <div class="modal fade" id="exampleModal-7" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
              
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel">New Product</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <div class="modal-body">
                        <form>
                           <div class="form-group">
                           
                            <div class="row">
                  
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Name of Product</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Name of Product">   
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Generic Name</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Generic Name">
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Manufacturer</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Manufacturer">
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Scheduled Category</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">select</option>
                                    <option value="Unregistered">H1</option>
                                </select>
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Type</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Type">
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Catagory</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Catagory">
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Sub Catagory</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">select</option>
                                    <option value="Unregistered">H1</option>
                                </select>
                          </div>
                          
                          </div>
                          
                          
                          <div class="row">
                          
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">HSN</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="HSN">
                          </div>
                          
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">GST Total%</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">5%</option>
                                    <option value="Unregistered">10%</option>
                                </select>
                          </div>
                          
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">CGST %</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="0.00">
                          </div>
                          
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">SGST %</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="0.00">
                          </div>
                          
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">IGST %</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="0.00">
                          </div>
                          
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">Units/Strip</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Units/Strip">
                          </div>
                          
                          </div>
                        
                      </div>
                        </form>
                    </div>
                
                <div class="modal-footer">
                  <button type="button" class="btn btn-success">Add</button>
                  <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
        </div>
        
        
        <!-- Save & Return Model -->
        <div class="modal fade" id="exampleModal-8" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
              
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel">Product Return</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <div class="modal-body">
                  <form>
                    <div class="form-group row">
                  
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">Product</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">Search</option>
                                    <option value="Unregistered">abc</option>
                                </select>
                          </div>
                          
                          
                          <div class="col-12 col-md-1">
                            <label for="exampleInputName1">Quantity</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="00">
                          </div>
                          
                          <div class="col-12 col-md-1">
                            <label for="exampleInputName1">Batch</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">19</option>
                                </select>
                          </div>
                          
                          <div class="col-12 col-md-1">
                            <label for="exampleInputName1">Disc%</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="00">
                          </div>
                          
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">Expiry</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="12/19">
                          </div>
                          
                          <div class="col-12 col-md-1">
                            <label for="exampleInputName1">GST%</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="00">
                          </div>
                          
                          <div class="col-12 col-md-1">
                            <label for="exampleInputName1">MRP</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="5">
                          </div>
                          
                          <div class="col-12 col-md-3">
                           <a href="#" class="btn btn-primary mt-30">Return</a>
                           <a href="#" class="btn btn-grey-1 mt-30">Clear</a>
                          </div>
                          
                          <div class="col-12 col-md-9 mt-1">
                          	<label for="exampleInputName1" class="color-green"><strong>Total Qty.:</strong> 15</label>&nbsp;
                            <label for="exampleInputName1" class="pull-right"><strong>Purchase Price:</strong> 1200</label>
                          </div>
                         
                        
                      </div>
                  </form>
                  
                  <div class="row">
                  <div class="col-12">
                  	<table id="order-listing1" class="table">
                                <thead>
                                  <tr align="left">
                                      <th>Sr No.</th>
                                      <th>Product</th>
                                      <th>Qty.</th>
                                      <th>Disc.%</th>
                                      <th>Ammount</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts --> 	
                                  <tr >
                                      <td>O133</td>
                                      <td>A O FORTE</td>
                                      <td>25</td>
                                      <td>5</td>
                                      <td>125</td>
                                  </tr><!-- End Row --> 	
                                  
                                
                                  
                                 
                                </tbody>
                              </table>            
                  </div>
                  </div>
                  
                  
                  
                  
                  
                  
                  
                </div>
                
                <div class="modal-footer">
                  <button type="button" class="btn btn-success">Return Items</button>
                  <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
        </div>
    
        
     
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

 
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script> 
  
  <script>
  	 $('#order-listing2').DataTable();
  </script>
  
  <script>
  	 $('#order-listing1').DataTable();
  </script>

<script>  
  $("#group").change(function(){
    var group_id = $(this).val();
    if(group_id !== ''){
        $.ajax({
          type: "POST",
          url: 'accountajax.php',
          data: {'group_id':group_id, 'action':'getgroup'},
          dataType: "json",
          success: function (data) {console.log(data);
              if(data.status == true){
                  $('#ledger').children('option:not(:first)').remove();
                  $.each(data.result, function (i, item) {
                    $('#ledger').append($('<option>', { 
                        value: item.id,
                        text : item.name 
                    }));
                });
              }else{
                  $('#ledger').children('option:not(:first)').remove();
              }
          },
          error: function () {
              $('#ledger').children('option:not(:first)').remove();
          }
          });
    }else{
        $('#ledger').children('option:not(:first)').remove();
    }
    $('#ledger').trigger("change");
});
</script>
  
  <!-- End custom js for this page-->
</body>


</html>
