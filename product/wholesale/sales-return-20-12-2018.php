<?php $title="Sales Return"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>

<?php 
  $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
?>

<?php
  if(isset($_POST['submit'])){
    $count = (isset($_POST['product_id']) && !empty($_POST['product_id'])) ? count($_POST['product_id']) : 0;
    if($count > 0){
      for ($i=0; $i < $count; $i++) {
        $data['financial_id'] = (isset($financial_id) && $financial_id != '') ? $financial_id : NULL;
        $data['owner_id'] = (isset($owner_id) && $owner_id != '') ? $owner_id : NULL;
        $data['admin_id'] = (isset($admin_id) && $admin_id != '') ? $admin_id : NULL;
        $data['pharmacy_id'] = (isset($pharmacy_id) && $pharmacy_id != '') ? $pharmacy_id : NULL;
        $data['customer_id'] = (isset($_POST['customer_id'])) ? $_POST['customer_id'] : NULL;
        $data['product_id'] = (isset($_POST['product_id'][$i])) ? $_POST['product_id'][$i] : NULL;
        $data['qty'] = (isset($_POST['qty'][$i]) && $_POST['qty'][$i] != '') ? $_POST['qty'][$i] : 0;
        $data['rate'] = (isset($_POST['rate'][$i]) && $_POST['rate'][$i] != '') ? $_POST['rate'][$i] : 0;
        $data['discount'] = (isset($_POST['discount'][$i]) && $_POST['discount'][$i] != '') ? $_POST['discount'][$i] : 0;
        $data['gst'] = (isset($_POST['gst'][$i]) && $_POST['gst'][$i] != '') ? $_POST['gst'][$i] : 0;
        $data['igst'] = (isset($_POST['igst'][$i]) && $_POST['igst'][$i] != '') ? $_POST['igst'][$i] : 0;
        $data['cgst'] = (isset($_POST['cgst'][$i]) && $_POST['cgst'][$i] != '') ? $_POST['cgst'][$i] : 0;
        $data['sgst'] = (isset($_POST['sgst'][$i]) && $_POST['sgst'][$i] != '') ? $_POST['sgst'][$i] : 0;
        $data['amount'] = (isset($_POST['amount'][$i]) && $_POST['amount'][$i] != '') ? $_POST['amount'][$i] : 0;
        $data['return_date'] = (isset($_POST['return_date'][$i]) && $_POST['return_date'][$i] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['return_date'][$i]))) : NULL;
        $itemReturn = "INSERT INTO sale_return SET ";
        foreach ($data as $ks => $vs) {
            $itemReturn .= " ".$ks." = '".$vs."', ";
        }
        $itemReturn .= "created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
        mysqli_query($conn, $itemReturn);
      }
      $_SESSION['msg']['success'] = "Product return Added successfully.";
      if(isset($_GET['bill']) && $_GET['bill'] != ''){
        header('Location: sales-history.php');exit;
      }else{
        header('Location: sales-return.php');exit;
      }
    }else{
      $_SESSION['msg']['error'] = "Please add at least one item at a time";
    }
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Sales Return</title>
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
    <?php include "include/topbar.php"; ?>
    
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">

      <!-- Right Sidebar -->
      <?php include "include/sidebar-right.php" ?>
        
       
      <!-- Left Navigation -->
      <?php include "include/sidebar-nav-left.php" ?>
      
      
      <div class="main-panel">
      
        <div class="content-wrapper">
          <?php include('include/flash.php'); ?>
          <span id="errormsg"></span>
          <div class="row">
           <!-- Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <!-- Main Catagory -->
                    <div class="row">
                      <div class="col-12">
                          <div class="purchase-top-btns">
                              <?php 
                                if((isset($user_sub_module) && in_array("Tax Billing", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ 
                              ?>
                              <a href="sales-tax-billing.php" class="btn btn-dark active">Sales</a>
                              <a href="view-sales-tax-billing.php" class="btn btn-dark active">View Sales Bill</a>
                              <?php }
                                if((isset($user_sub_module) && in_array("Sales Return", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){
                              ?>
                              <a href="sales-return.php" class="btn btn-dark">Sales Return</a>
                              <?php }
                                if((isset($user_sub_module) && in_array("Sales Return List", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){
                              ?>
                              <a href="view-sales-return.php" class="btn btn-dark">Sales Return List</a>
                              <?php }
                                if((isset($user_sub_module) && in_array("Cancellation List", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){
                              ?>
                              <a href="sales-cancellation-list.php" class="btn btn-dark">Cancellation List</a>
                              <?php } 
                                if((isset($user_sub_module) && in_array("Order", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){
                              ?>
                                  <a href="#" class="btn btn-dark dropdown-toggle" id="dropdownMenuButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Order</a>
                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton4">
                                    <a class="dropdown-item" href="sales-order.php">Order/Estimate/Templates</a>
                                    <a class="dropdown-item" href="#">History</a>
                                  </div>
                              <?php } 
                                if((isset($user_sub_module) && in_array("Sales History", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){
                              ?>
                              <a href="sales-history.php" class="btn btn-dark">History</a>
                              <?php } 
                                //if(isset($user_sub_module) && in_array("Settings", $user_sub_module)){
                              ?>
                              <!--<a href="#" class="btn btn-dark">Settings</a>-->
                              <?php // } ?>
                          </div>   
                      </div> 
                    </div>
                </div>
              </div>
            </div>
            <!-- Form -->
            <form method="POST" autocomplete="off">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <br>
                      <?php 
                        if(isset($_GET['bill']) && $_GET['bill'] != ''){
                          $findbillQ = "SELECT lgr.id, lgr.name, lgr.city FROM tax_billing tb INNER JOIN ledger_master lgr ON tb.customer_id = lgr.id WHERE tb.id = '".$_GET['bill']."' AND tb.pharmacy_id = '".$pharmacy_id."'";
                          $findbillR = mysqli_query($conn, $findbillQ);
                          if($findbillR && mysqli_num_rows($findbillR) > 0){
                            $findbillRow = mysqli_fetch_array($findbillR);
                            $bill_city = (isset($findbillRow['city'])) ? $findbillRow['city'] : '';
                            $bill_customer['id'] = (isset($findbillRow['id'])) ? $findbillRow['id'] : '';
                            $bill_customer['name'] = (isset($findbillRow['name'])) ? $findbillRow['name'] : '';
                          }
                        }
                      ?>
                      <div class="form-group row">
                        <div class="col-12 col-md-2">
                          <label>Select City <span class="text-danger">*</span></label>
                          <select class="js-example-basic-single" style="width:100%" name="city_id" id="city_id" required data-parsley-errors-container="#error-city_id">
                            <?php if(!isset($bill_city)){ ?>
                              <option value="">Select City</option>
                            <?php } ?>
                              <?php 
                                $getCustomerCityQ = "SELECT ct.id, ct.name FROM ledger_master lgr INNER JOIN own_cities ct ON lgr.city = ct.id ";
                                $getCustomerCityQ .= "WHERE lgr.group_id = 10 AND lgr.pharmacy_id = '".$pharmacy_id."' ";
                                if(isset($bill_city) && $bill_city != ''){
                                  $getCustomerCityQ .= "AND lgr.city = '".$bill_city."' ";
                                }
                                $getCustomerCityQ .= "GROUP BY lgr.city ORDER BY ct.name";

                                $getCustomerCityR = mysqli_query($conn, $getCustomerCityQ);
                                if($getCustomerCityR && mysqli_num_rows($getCustomerCityR) > 0){
                                  while ($cityRow = mysqli_fetch_array($getCustomerCityR)) {
                              ?>
                                <option value="<?php echo (isset($cityRow['id'])) ? $cityRow['id'] : ''; ?>"><?php echo (isset($cityRow['name'])) ? $cityRow['name'] : ''; ?></option>
                            <?php } } ?>
                          </select>
                          <span id="error-city_id"></span>
                        </div>
                        
                        <div class="col-12 col-md-2">
                          <label for="customer_id">Select Customer <span class="text-danger">*</span></label>
                          <select class="js-example-basic-single" style="width:100%" name="customer_id" id="customer_id" required data-parsley-errors-container="#error-customer_id"> 
                            <option value="">Select Customer</option>
                            <?php if(isset($bill_customer) && !empty($bill_customer)){ ?>
                              <option value="<?php echo (isset($bill_customer['id'])) ? $bill_customer['id'] : ''; ?>" selected><?php echo (isset($bill_customer['name'])) ? $bill_customer['name'] : ''; ?></option>
                            <?php } ?>
                          </select>
                          <span id="error-customer_id"></span>
                        </div>
                        
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Return Date <span class="text-danger">*</span></label>
                          <div class="input-group date datepicker">
                            <?php $invoicedate = date('d/m/Y'); ?>
                            <input type="text" class="form-control border" name="return_date[]" autocomplete="off" value="<?php echo (isset($invoicedate)) ? $invoicedate : ''; ?>" required data-parsley-errors-container="#error-date">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          <span id="error-date"></span>
                        </div>
                        
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Sale Invoice No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control billno" name="billno" autocomplete="off"  required readonly>
                        </div>
                        
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Sale Invoice Date <span class="text-danger">*</span></label>
                          <div class="input-group date datepicker">
                            <input type="text" class="form-control billdate" name="invoice_date" autocomplete="off"  required  readonly>
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                         
                        </div>
                        
                      </div>
                  </div>
                </div>
              </div>
              <!-- Table -------------->
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="col mt-3">
                      <div class="row">
                        <div class="col-12">
                          <table class="table">
                            <thead>
                              <tr>
                                  <th width="5%">Sr No</th>
                                  <th>Product</th>
                                  <th>MFG. Co.</th>
                                  <th>Batch</th>
                                  <th width="9%">Expiry</th>
                                  <th width="7%">Qty.</th>
                                  <th>Rate</th>
                                  <th>Discount</th>
                                  <th width="5%">GST</th>
                                  <th>Total Amount</th>
                                  <th width="8%">&nbsp;</th>
                              </tr>
                            </thead>
                            <tbody id="item-tbody">
                                <tr>
                                  <td>1</td>
                                  <td>
                                    <input type="text" name="product[]" class="form-control product" placeholder="Product" required>
                                    <small class="product_error text-danger"></small>
                                    <input type="hidden" class="product_id" name="product_id[]"></td>
                                  <td><input type="text" name="mfg_co[]" class="form-control mfg" placeholder="MFG. Co."></td>
                                  <td><input type="text" name="batch[]" class="form-control batch" placeholder="Batch"></td>
                                  <td><input type="text" name="expiry[]" class="form-control expiry datepicker" placeholder="Expiry"></td>
                                  <td><input type="text" name="qty[]" class="form-control qty onlynumber" placeholder="Qty." required></td>
                                  <td><input type="text" name="rate[]" class="form-control rate onlynumber" placeholder="Rate" required></td>
                                  <td><input type="text" name="discount[]" class="form-control discount onlynumber" placeholder="Discount(RS)"></td>
                                  <td>
                                    <input type="text" name="gst[]" class="form-control gst onlynumber" placeholder="GST(%)">
                                    <input type="hidden" name="igst[]" class="igst" value="0">
                                    <input type="hidden" name="cgst[]" class="cgst" value="0">
                                    <input type="hidden" name="sgst[]" class="sgst" value="0">
                                  </td>
                                  <td><input type="text" name="amount[]" class="form-control amount onlynumber" placeholder="0.00" readonly></td>
                                  <td>
                                    <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item"><i class="fa fa-plus mr-0 ml-0"></i></button>
                                  </td>
                                </tr>
                              <!-- End Row -->  
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <hr>

                    <div class="col-12" style="padding: 0px;">
                      <div class="row">
                        <div class="col-md-12">
                          <a href="view-sales-return.php" class="btn btn-light pull-left">Back</a>
                          <button type="submit" name="submit" class="btn btn-success mr-2 pull-right"><?php echo (isset($_GET['id'])) ? 'Update' : 'Save'; ?></button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <!-- content-wrapper ends -->
        
        <!-- partial:partials/_footer.php -->
        <?php include "include/footer.php"?>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <div id="hidden-return-tr" style="display: none;">
    <table>
      <tr>
        <td>##SRNO##</td>
        <td>
          <input type="text" name="product[]" class="form-control product" placeholder="Product" required>
          <small class="product_error text-danger"></small>
          <input type="hidden" class="product_id" name="product_id[]"></td>
        <td><input type="text" name="mfg_co[]" class="form-control mfg" placeholder="MFG. Co."></td>
        <td><input type="text" name="batch[]" class="form-control batch" placeholder="Batch"></td>
        <td><input type="text" name="expiry[]" class="form-control expiry datepicker" placeholder="Expiry"></td>
        <td><input type="text" name="qty[]" class="form-control qty onlynumber" placeholder="Qty." required></td>
        <td><input type="text" name="rate[]" class="form-control rate onlynumber" placeholder="Rate" required></td>
        <td><input type="text" name="discount[]" class="form-control discount onlynumber" placeholder="Discount(RS)"></td>
        <td>
          <input type="text" name="gst[]" class="form-control gst onlynumber" placeholder="GST(%)">
          <input type="hidden" name="igst[]" class="igst" value="0">
          <input type="hidden" name="cgst[]" class="cgst" value="0">
          <input type="hidden" name="sgst[]" class="sgst" value="0">
        </td>
        <td><input type="text" name="amount[]" class="form-control amount onlynumber" placeholder="0.00" readonly></td>
        <td>
          <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item"><i class="fa fa-plus mr-0 ml-0"></i></button>
          <button type="button" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-item" style=""><i class="fa fa-close mr-0 ml-0"></i></button>
        </td>
      </tr>
    </table>
  </div>

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
    $(document).on('focus',".datepicker", function(){ //bind to all instances of class "date". 
      $(this).datepicker({
        enableOnReadonly: true,
        todayHighlight: true,
        format: 'dd/mm/yyyy',
        autoclose : true
      });
      $(this).datepicker("refresh");
  });
 </script>
<script src="js/jquery-ui.js"></script>


<script src="js/custom/sales-return.js"></script>
<script src="js/custom/onlynumber.js"></script>

<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>
  
  <!-- End custom js for this page-->
</body>


</html>
