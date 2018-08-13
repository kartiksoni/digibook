<?php include('include/usertypecheck.php');
?>
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
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
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
          
          
           <!-- Bank Management Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                
                  <!-- Main Catagory -->
                    <div class="row">
                    <div class="col-12">
                        <div class="purchase-top-btns">
                            <a href="purchase.php" class="btn btn-warning ">Purchase Bill</a>
                            <a href="purchase-return.php" class="btn btn-warning active">Purchase Return</a>
                            <a href="purchase-return-list.php" class="btn btn-warning">Purchase Return List</a>
                            <a href="#" class="btn btn-warning btn-fw">Cancel List</a>
                            <a href="purchase-history.php" class="btn btn-warning  btn-fw">History</a>
                            <a href="#" class="btn btn-warning btn-fw">Settings</a>
                        </div>   
                    </div> 
                    </div>
                    <hr>
                    
                
                
                 <br>
                  <form class="forms-sample">
                  
                  <div class="form-group row">
                  
                      <div class="col-12 col-md-2">
                       <label for="exampleInputName1">Debit Note Date</label>
                            <div id="datepicker-popup" class="input-group date datepicker">
                            <input type="text" class="form-control border" >
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                      </div>
                      
                      <div class="col-12 col-md-2">
                       <label for="exampleInputName1">Debit Note Number</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Number">
                      </div>
                      
                      <div class="col-12 col-md-3">
                      <label>Select Vendor</label>
                          <select class="js-example-basic-single" style="width:100%" name="vendor_id" id="vendor_id" data-parsley-errors-container="#error-vendor" required> 
                              <option value="">Please select</option>
                              <?php 
                                $getAllVendorQuery = "SELECT id, name FROM ledger_master WHERE status=1 AND group_id=14 order by name";
                                $getAllVendorRes = mysqli_query($conn, $getAllVendorQuery);
                              ?>
                              <?php if($getAllVendorRes && mysqli_num_rows($getAllVendorRes) > 0){ ?>
                                <?php while ($getAllVendorRow = mysqli_fetch_array($getAllVendorRes)) { ?>
                                  <option value="<?php echo $getAllVendorRow['id']; ?>"><?php echo $getAllVendorRow['name']; ?></option>
                                <?php } ?>
                              <?php } ?>
                          </select>
                      
                      </div>
                      
                      <div class="col-12 col-md-12 mt-30 mb-30">
                          <table id="order-listing1" class="table">
                                    <thead>
                                      <tr>
                                          <th>Sr No.</th>
                                          <th>Product</th>
                                          <th>MRP</th>
                                          <th>MFG. Co.</th>
                                          <th>Batch No</th>
                                          <th>Expiry</th>
                                          <th>Qty</th>
                                          <th>Free Qty</th>
                                          <th>Rate</th>
                                          <th>Discount</th>
                                          <th>Rate</th>
                                          <th>Amount</th>
                                          <th>&nbsp;</th>
                                      </tr>
                                    </thead>
                                    <tbody id="product-tbody">
                                      <!-- Row Starts --> 	
                                      <tr class="product-tr">
                                          <td>1</td>
                                          <td>
                                          	<input type="text" placeholder="Product" class="tags form-control" required="" name="product[]">
	                                          <input type="hidden" class="product-id" name="product_id[]">
                                            <small class="text-danger empty-message0"></small>
                                          </td>
                                            <td><input name="mrp[]" type="text" class="form-control" id="exampleInputName1" placeholder="MRP"></td>
                                            <td><input name="mfg_no[]" type="text" class="form-control" id="exampleInputName1" placeholder="MFG. Co."></td>
                                            <td><input name="batch_no[]" type="text" class="form-control" id="exampleInputName1" placeholder="Batch No."></td>
                                            <td><input name="expiry[]" type="text" class="form-control" id="exampleInputName1" placeholder="Expiry"></td>
                                            <td><input name="qty[]" type="text" class="form-control" id="exampleInputName1" placeholder="Qty."></td>
                                            <td><input name="free_qty[]" type="text" class="form-control" id="exampleInputName1" placeholder="Free Qty"></td>
                                            <td><input name="rate[]" type="text" class="form-control" id="exampleInputName1" placeholder="Rate"></td>
                                            <td><input name="discount[]" type="text" class="form-control" id="exampleInputName1" placeholder="Discount"></td>
                                            <td><input name="gst[]" type="text" class="form-control" id="exampleInputName1" placeholder="GST"></td>
                                            <td><input name="ammount[]" type="text" class="form-control" id="exampleInputName1" placeholder="Ammount"></td>
                                            <td><a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a><a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product remove_last" style="display: none;"><i class="fa fa-close mr-0 ml-0"></i></a></td>
                                      </tr><!-- End Row --> 	
                                      
                                   
                                      
                                     
                                    </tbody>
                                  </table>
                      </div>
                      
                      
                      <div class="col-12 col-md-6">
                      <label for="exampleInputName1">Remarks / Reason for Return </label>
                        <textarea class="form-control" id="exampleTextarea1" rows="3"></textarea>
                      </div>
                      
                     
                      
                      <div class="col-12 col-md-12">
                       <hr>
                      	<div class="row no-gutters">
                      
                          <div class="col-12 col-md-2">
                              <label for="exampleInputName1">Debit Note Settle in A/c.</label>
                            <div class="row no-gutters">
                                    
                                        <div class="col">
                                            <div class="form-radio">
                                            <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="Active" checked>
                                           Yes
                                            </label>
                                            </div>
                                        </div>
                                        
                                        <div class="col">
                                            <div class="form-radio">
                                            <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios2" value="Deactive">
                                            No
                                            </label>
                                            </div>
                                        </div>
                                    
                                    </div>
                          </div>
                      
                      <div class="col">
	                      <button type="submit" class="btn btn-success mt-30 pull-right">Save</button>
                      </div>
                      
                      </div>
                      </div>
                      
                     
                    
                  </div> 
                  
                 
                   
                   
                  </form>
                  <div id="html-copy" style="display: none;">
                    <table>
                      <tr class="product-tr">
                            <td>##SRPRODUCT##</td>
                            <td>
                              <input type="text" placeholder="Product" class="tags form-control" required="" name="product[]">
                              <input type="hidden" class="product-id" name="product_id[]">
                              <small class="text-danger empty-message##PRODUCTCOUNT##"></small>
                            </td>
                              <td><input name="mrp[]" type="text" class="form-control" id="exampleInputName1" placeholder="MRP"></td>
                              <td><input name="mfg_no[]" type="text" class="form-control" id="exampleInputName1" placeholder="MFG. Co."></td>
                              <td><input name="batch_no[]" type="text" class="form-control" id="exampleInputName1" placeholder="Batch No."></td>
                              <td><input name="expiry[]" type="text" class="form-control" id="exampleInputName1" placeholder="Expiry"></td>
                              <td><input name="qty[]" type="text" class="form-control" id="exampleInputName1" placeholder="Qty."></td>
                              <td><input name="free_qty[]" type="text" class="form-control" id="exampleInputName1" placeholder="Free Qty"></td>
                              <td><input name="rate[]" type="text" class="form-control" id="exampleInputName1" placeholder="Rate"></td>
                              <td><input name="discount[]" type="text" class="form-control" id="exampleInputName1" placeholder="Discount"></td>
                              <td><input name="gst[]" type="text" class="form-control" id="exampleInputName1" placeholder="GST"></td>
                              <td><input name="ammount[]" type="text" class="form-control" id="exampleInputName1" placeholder="Ammount"></td>
                              <td><a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a><a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product "><i class="fa fa-close mr-0 ml-0"></i></a></td>
                        </tr><!-- End Row -->   
                    </table>
                  </div>
                </div>
              </div>
            </div>
            
           
            
             <!-- Table ------------------------------------------------------------------------------------------------------>
            
              
            
            
            
            
      
            
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
  <script src="js/jquery-ui.js"></script>
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script>
  <script src="js/custom/purchase-return.js"></script>
  
  <!-- script for custom validation -->
<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>
  
  
  <!-- End custom js for this page-->
</body>


</html>
