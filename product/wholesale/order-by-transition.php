<?php include('include/usertypecheck.php');
$owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
$pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
 $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
?>

<?php

//  if(isset($_POST['submit'])){
//   echo "<pre>";
//   print_r($_POST);
  
// }
  

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
  <link rel="stylesheet" href="css/parsley.css">

  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
  <style type="text/css">
     .ui-autocomplete { z-index:2147483647 !important; }
  </style>
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
                    	    <?php if((isset($user_sub_module) && in_array("Order", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                      		    <a href="order.php" class="btn btn-dark btn-fw active">Order</a>
                    	    <?php } if((isset($user_sub_module) && in_array("List", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                      		    <a href="order-list-tab.php" class="btn btn-dark btn-fw ">List</a>
                		    <?php } if((isset($user_sub_module) && in_array("Missed Sales Order", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                      		    <a href="missed-sales-order.php" class="btn btn-dark btn-fw ">Missed Sales Order</a>
                		    <?php } ?>
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
                  
                  <form class="forms-sample" autocomplete="off" id="search" >
                    <div class="col-md-12">
                      <div class="form-group row">
                        <div class="col-12 col-md-8 col-sm-12">
                            <label for="exampleInputName1">Select Type  </label>
                            <div class="row no-gutters">
                              <div class="col">
                                  <div class="form-radio">
                                    <label class="form-check-label">
                                      <input type="radio" required class="form-check-input" name="type" id="company" value="1"
                                       onclick="myFunctionCompany()">
                                      Company wise  
                                    </label>
                                  </div>
                              </div>

                              <div class="col">
                                  <div class="form-radio">
                                    <label class="form-check-label">
                                      <input type="radio" required class="form-check-input" name="type" id="allcompany" value="2" onclick="AllCompany()">
                                      All Company wise
                                    </label>
                                  </div>
                              </div>
                              <div class="col">
                                  <div class="form-radio">
                                    <label class="form-check-label">
                                      <input type="radio" required class="form-check-input" name="type" id="selectedcompany" value="3" onclick="mySelectedCompany();">
                                      Selected Company wise
                                    </label>
                                  </div>
                              </div>
                              <div class="col">
                                  <div class="form-radio">
                                    <label class="form-check-label">
                                      <input type="radio" required class="form-check-input" name="type" id="allproduct" value="4" onclick="AllProduct();">
                                      All Products
                                    </label>
                                  </div>
                              </div>
                            </div>
                        </div>
                      </div>

                       <div class="form-group row" >
                        <div class="col-12 col-md-2" id= "trans_com">
                          <label for="exampleInputName1">Select Company</label>
                          <input type="text" required class="form-control" placeholder="Company" id = "company_list" name = "company_name" autocomplete="nope" data-parsley-errors-container="#error-cumpany_id" autocomplete="off">
                           <small class="customererror text-danger"></small>
                          <div id="error-cumpany_id"></div>
                        </div>
                        <input type="hidden" name="company_id" id= "company_id">
                        <div class="col-12 col-md-2" id= "trans_all">
                          <label for="exampleInputName1">Select Company wise</label>
                          <select class="js-example-basic-single" style="width:100%" name="selectedcompany[]" id="companyall" multiple="multiple" required data-parsley-errors-container="#error-com">
                            <option value="">Select All company</option>
                            <?php
                              $allCompany = [];
                            $dmfgQ = "SELECT id , mfg_company FROM `product_master` WHERE pharmacy_id = '".$pharmacy_id."' GROUP BY mfg_company ORDER BY mfg_company";
                              $mfgR = mysqli_query($conn, $dmfgQ);
                              if($mfgR && mysqli_num_rows($mfgR) > 0){
                                while ($mfgRow = mysqli_fetch_array($mfgR)) {
                                  $dtr['id'] = (isset($mfgRow['id'])) ? $mfgRow['id'] : '';
                                  $dtr['company'] = (isset($mfgRow['mfg_company'])) ? $mfgRow['mfg_company'] : '';
                                  $allCompany[] = $dtr;
                                }
                              }
                            ?>
                            <?php 
                              if(isset($allCompany) && !empty($allCompany)){
                                foreach ($allCompany as $key => $value) {
                            ?>
                              <option value="<?php echo $value['company'] ?>" ><?php echo $value['company']; ?></option>
                            <?php 
                                }
                              }
                            ?>
                          </select>
                           <div id="error-com"></div>
                        </div>
                        
                        
                      </div>
<?php $date = date('d/m/Y'); ?>
                      <div class="form-group row">
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">From Date</label>
                          <div class="input-group date datepicker">
                            <input type="text" class="form-control border" placeholder="dd/mm/yyyy" name = "from" required data-parsley-errors-container="#error-from" value="<?php echo $date; ?>"> 
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          <div id="error-from"></div>
                        </div>
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName2">To Date</label>
                          <div class="input-group date datepicker1">
                            <input type="text" class="form-control border" placeholder="dd/mm/yyyy" name = "to" required data-parsley-errors-container="#error-to" value="<?php echo $date; ?>">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          <div id="error-to"></div>
                        </div>
                        <div class="col-12 col-md-2">
                            <label >Stock per. Of Sales</label>
                            <input type="text" class="form-control onlynumber" placeholder="0.00" name = "stock" required> 
                        </div>
                        <div class="col-12 col-md-2">
                          <button type = "submit" class="btn btn-success mt-30" style="margin-top:30px;">Search</button>
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
                          <table class="table datatable" id="location">
                            <thead>
                              <tr>
                                  <th>Product Name</th>
                                  <th>Manufacturer Name</th>
                                  <th>Batch No</th>
                                  <th>GST</th>
                                  <th>Unit/Stip</th>
                                  <th>Ratio</th>
                                  <th>Order Qty</th>
                                  <th>Current Stock</th>
                                 
                                 
                              </tr>
                            </thead>
                            <tbody>
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
  <script src="js/jquery-ui.js"></script>
   <script src="js/custom/order-by-transition.js"></script>
  <!-- Datepicker Initialise-->
 <script>
    $('.datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      autoclose: true,
      format: 'dd/mm/yyyy'
    });
 </script>
 <script>
    $('.datepicker1').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      autoclose: true,
      format: 'dd/mm/yyyy'
    });
 </script>
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script> 
  <script src="js/parsley.min.js"></script>
  <script type="text/javascript">
  $('form').parsley();
</script>
  <script src="js/custom/onlynumber.js"></script>

  
  <!-- End custom js for this page-->
</body>


</html>
                                 <!-- <tr>
                                    <select class="js-example-basic-single" style="width:100%"> 
                                        <option value="Regular">H</option>
                                        <option value="Unregistered">H1</option>
                                    </select>
                              </tr> -->