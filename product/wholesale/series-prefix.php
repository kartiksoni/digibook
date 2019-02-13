<?php $title = "Series Prefix"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php include('include/permission.php'); ?>

<?php 
  $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
?>

<?php
  if(isset($_POST['submit'])){
    $sale_cash = (isset($_POST['sale_cash'])) ? $_POST['sale_cash'] : '';
    $sale_debit = (isset($_POST['sale_debit'])) ? $_POST['sale_debit'] : '';
    $credit_note = (isset($_POST['credit_note'])) ? $_POST['credit_note'] : '';
    $service_cash = (isset($_POST['service_cash'])) ? $_POST['service_cash'] : '';
    $service_debit = (isset($_POST['service_debit'])) ? $_POST['service_debit'] : '';
    $bos_cash = (isset($_POST['bos_cash'])) ? $_POST['bos_cash'] : '';
    $bos_debit = (isset($_POST['bos_debit'])) ? $_POST['bos_debit'] : '';
    $challan_cash = (isset($_POST['challan_cash'])) ? $_POST['challan_cash'] : '';
    $challan_debit = (isset($_POST['challan_debit'])) ? $_POST['challan_debit'] : '';
    $purchase_cash = (isset($_POST['purchase_cash'])) ? $_POST['purchase_cash'] : '';
    $purchase_debit = (isset($_POST['purchase_debit'])) ? $_POST['purchase_debit'] : '';
    $debit_note = (isset($_POST['debit_note'])) ? $_POST['debit_note'] : '';
    $cash_management_cash = (isset($_POST['cash_management_cash'])) ? $_POST['cash_management_cash'] : '';
    $cash_management_receipt = (isset($_POST['cash_management_receipt'])) ? $_POST['cash_management_receipt'] : '';
    $vendor_payments = (isset($_POST['vendor_payments'])) ? $_POST['vendor_payments'] : '';
    $customer_receipts = (isset($_POST['customer_receipts'])) ? $_POST['customer_receipts'] : '';
    $accounting_bank = (isset($_POST['accounting_bank_payment'])) ? $_POST['accounting_bank_payment'] : '';
    $accounting_bank_receipt = (isset($_POST['accounting_bank_receipt'])) ? $_POST['accounting_bank_receipt'] : '';
    $payment_tax = (isset($_POST['payment_tax'])) ? $_POST['payment_tax'] : '';
    $payment_taxfree = (isset($_POST['payment_taxfree'])) ? $_POST['payment_taxfree'] : '';
    $courier_payment = (isset($_POST['courier_payment'])) ? $_POST['courier_payment'] : '';
    $quotation_cash = (isset($_POST['quotation_cash'])) ? $_POST['quotation_cash'] : '';
    $quotation_debit = (isset($_POST['quotation_debit'])) ? $_POST['quotation_debit'] : '';
    $uid = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : '';
    $date = date('Y-m-d H:i:s');
   
    $existQ = "SELECT id FROM series_prefix WHERE pharmacy_id = '".$pharmacy_id."'";
    $existR = mysqli_query($conn, $existQ);
    if($existR && mysqli_num_rows($existR) > 0){
      $existRow = mysqli_fetch_assoc($existR);
      $query = "UPDATE series_prefix SET sale_cash = '".$sale_cash."', sale_debit = '".$sale_debit."', credit_note = '".$credit_note."',service_cash = '".$service_cash."', service_debit = '".$service_debit."', bos_cash = '".$bos_cash."', bos_debit = '".$bos_debit."', challan_cash = '".$challan_cash."', challan_debit = '".$challan_debit."', purchase_cash = '".$purchase_cash."', purchase_debit = '".$purchase_debit."', debit_note = '".$debit_note."', cash_management_cash = '".$cash_management_cash."', cash_management_receipt = '".$cash_management_receipt."', vendor_payments = '".$vendor_payments."', customer_receipts = '".$customer_receipts."', accounting_bank_cheque = '".$accounting_bank."', accounting_bank_cheque_receipt = '".$accounting_bank_receipt."', payment_tax = '".$payment_tax."', payment_tax_free = '".$payment_taxfree."', courier_payment = '".$courier_payment."', quotation_cash = '".$quotation_cash."', quotation_debit = '".$quotation_debit."', modified = '".$date."', modifiedby = '".$uid."'";
    }else{
      $query = "INSERT INTO series_prefix SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', sale_cash = '".$sale_cash."', sale_debit = '".$sale_debit."', credit_note = '".$credit_note."', service_cash = '".$service_cash."', service_debit = '".$service_debit."', bos_cash = '".$bos_cash."', bos_debit = '".$bos_debit."', challan_cash = '".$challan_cash."', challan_debit = '".$challan_debit."', purchase_cash = '".$purchase_cash."', purchase_debit = '".$purchase_debit."', debit_note = '".$debit_note."', cash_management_cash = '".$cash_management_cash."', cash_management_receipt = '".$cash_management_receipt."', vendor_payments = '".$vendor_payments."', customer_receipts = '".$customer_receipts."', accounting_bank_cheque = '".$accounting_bank."', accounting_bank_cheque_receipt = '".$accounting_bank_receipt."', payment_tax = '".$payment_tax."', payment_tax_free = '".$payment_taxfree."', courier_payment = '".$courier_payment."', quotation_cash = '".$quotation_cash."', quotation_debit = '".$quotation_debit."', created = '".$date."', createdby = '".$uid."'";
    }
    $res = mysqli_query($conn, $query);
    if($res){
      $_SESSION['msg']['success'] = 'Prefix save successfully.';
    }else{
      $_SESSION['msg']['fail'] = 'Prefix save fail! Try again.';
    }
    header('location:series-prefix.php');exit;
  }

  $getDataQ = "SELECT * FROM series_prefix WHERE pharmacy_id = '".$pharmacy_id."'";
  $getDataR = mysqli_query($conn, $getDataQ);
  if($getDataR && mysqli_num_rows($getDataR) > 0){
    $data = mysqli_fetch_assoc($getDataR);
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Series Prefix</title>
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
  <link rel="stylesheet" href="css/parsley.css">
  <link rel="stylesheet" href="css/toggle/style.css">
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
          <span id="errormsg"></span>
          <div class="row">
            
         
            
            <!-- Service Master Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Series Prefix</h4><hr class="alert-dark">

                  <form class="forms-sample" method="POST" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-12 col-md-3">
                          <label for="sale_cash">Sale Bill Prefix(Cash) </label>
                          <input type="text"  name="sale_cash" value="<?php echo (isset($data['sale_cash'])) ? $data['sale_cash'] : ''; ?>" class="form-control" placeholder="Sale Cash Prefix">
                        </div>
                        <div class="col-12 col-md-3">
                          <label for="sale_debit">Sale Bill Prefix(Debit) </label>
                          <input type="text"  name="sale_debit" value="<?php echo (isset($data['sale_debit'])) ? $data['sale_debit'] : ''; ?>" class="form-control" placeholder="Sale Debit Prefix">
                        </div>
                        <div class="col-12 col-md-3">
                          <label for="sale_debit">Credit Note Prefix </label>
                          <input type="text"  name="credit_note" value="<?php echo (isset($data['credit_note'])) ? $data['credit_note'] : ''; ?>" class="form-control" placeholder="Credit Note Prefix">
                        </div>
                    </div>
                    <hr class="alert-dark">
                    <div class="form-group row">
                        <div class="col-12 col-md-3">
                          <label for="service_cash">Service Bill Prefix(Cash) </label>
                          <input type="text"  name="service_cash" value="<?php echo (isset($data['service_cash'])) ? $data['service_cash'] : ''; ?>" class="form-control" placeholder="Service Cash Prefix">
                        </div>
                        <div class="col-12 col-md-3">
                          <label for="service_debit">Service Bill Prefix(Debit) </label>
                          <input type="text"  name="service_debit" value="<?php echo (isset($data['service_debit'])) ? $data['service_debit'] : ''; ?>" class="form-control" placeholder="Service Debit Prefix">
                        </div>
                    </div>
                    
                    <hr class="alert-dark">
                    <div class="form-group row">
                        <div class="col-12 col-md-3">
                          <label for="bos_cash">BOS Prefix(Cash) </label>
                          <input type="text"  name="bos_cash" value="<?php echo (isset($data['bos_cash'])) ? $data['bos_cash'] : ''; ?>" class="form-control" placeholder="BOS Cash Prefix">
                        </div>
                        <div class="col-12 col-md-3">
                          <label for="bos_debit">BOS Prefix(Debit) </label>
                          <input type="text"  name="bos_debit" value="<?php echo (isset($data['bos_debit'])) ? $data['bos_debit'] : ''; ?>" class="form-control" placeholder="BOS Debit Prefix">
                        </div>
                    </div>
                    
                    <hr class="alert-dark">
                    <div class="form-group row">
                        <div class="col-12 col-md-3">
                          <label for="bos_cash">Delivery Challan(Cash) </label>
                          <input type="text"  name="challan_cash" value="<?php echo (isset($data['challan_cash'])) ? $data['challan_cash'] : ''; ?>" class="form-control" placeholder="Delivery Challan Cash Prefix">
                        </div>
                        <div class="col-12 col-md-3">
                          <label for="bos_debit">Delivery Challan(Debit) </label>
                          <input type="text"  name="challan_debit" value="<?php echo (isset($data['challan_debit'])) ? $data['challan_debit'] : ''; ?>" class="form-control" placeholder="Delivery Challan Debit Prefix">
                        </div>
                    </div>
                    
                    <hr class="alert-dark">
                    <div class="form-group row">
                        <div class="col-12 col-md-3">
                          <label for="bos_cash">Purchase Prefix(Cash) </label>
                          <input type="text"  name="purchase_cash" value="<?php echo (isset($data['purchase_cash'])) ? $data['purchase_cash'] : ''; ?>" class="form-control" placeholder="Purchase Cash Prefix">
                        </div>
                        <div class="col-12 col-md-3">
                          <label for="bos_debit">Purchase Prefix(Debit) </label>
                          <input type="text"  name="purchase_debit" value="<?php echo (isset($data['purchase_debit'])) ? $data['purchase_debit'] : ''; ?>" class="form-control" placeholder="Purchase Debit Prefix">
                        </div>

                        <div class="col-12 col-md-3">
                          <label for="bos_debit">Debit Note Prefix</label>
                          <input type="text"  name="debit_note" value="<?php echo (isset($data['debit_note'])) ? $data['debit_note'] : ''; ?>" class="form-control" placeholder="Debit Note Prefix">
                        </div>
                    </div>

                    <hr class="alert-dark">
                    <div class="form-group row">
                        <div class="col-12 col-md-3">
                          <label for="bos_cash">Cash Management Prefix(Cash Payment) </label>
                          <input type="text"  name="cash_management_cash" value="<?php echo (isset($data['cash_management_cash'])) ? $data['cash_management_cash'] : ''; ?>" class="form-control" placeholder="Cash Management Cash Payment Prefix">
                        </div>
                        <div class="col-12 col-md-3">
                          <label for="bos_debit">Cash Management Prefix(Cash Receipt) </label>
                          <input type="text"  name="cash_management_receipt" value="<?php echo (isset($data['cash_management_receipt'])) ? $data['cash_management_receipt'] : ''; ?>" class="form-control" placeholder="Cash Management Cash Receipt Prefix">
                        </div>
                    </div>

                    <hr class="alert-dark">
                    <div class="form-group row">
                      <div class="col-12 col-md-3">
                          <label for="bos_debit">Vendor Payments Prefix</label>
                          <input type="text"  name="vendor_payments" value="<?php echo (isset($data['vendor_payments'])) ? $data['vendor_payments'] : ''; ?>" class="form-control" placeholder="Vendor Payments Prefix">
                        </div>
                    </div>

                    <hr class="alert-dark">
                    <div class="form-group row">
                      <div class="col-12 col-md-3">
                          <label for="bos_debit">Customer Receipts Prefix</label>
                          <input type="text"  name="customer_receipts" value="<?php echo (isset($data['customer_receipts'])) ? $data['customer_receipts'] : ''; ?>" class="form-control" placeholder="Customer Receipts Prefix">
                        </div>
                    </div>

                    <hr class="alert-dark">
                    <div class="form-group row">
                      <div class="col-12 col-md-3">
                          <label for="bos_debit">Accounting Cheque Bank Prefix(Cash Payment)</label>
                          <input type="text"  name="accounting_bank_payment" value="<?php echo (isset($data['accounting_bank_cheque'])) ? $data['accounting_bank_cheque'] : ''; ?>" class="form-control" placeholder="Accounting Cheque Bank Prefix Cash Payment">
                        </div>

                        <div class="col-12 col-md-3">
                          <label for="bos_debit">Accounting Cheque Bank Prefix(Cash Receipt)</label>
                          <input type="text"  name="accounting_bank_receipt" value="<?php echo (isset($data['accounting_bank_cheque_receipt'])) ? $data['accounting_bank_cheque_receipt'] : ''; ?>" class="form-control" placeholder="Accounting Cheque Bank Prefix Cash Receipt">
                        </div>
                    </div>

                    <hr class="alert-dark">
                    <div class="form-group row">
                      <div class="col-12 col-md-3">
                          <label for="bos_debit">Payment Prefix(Tax)</label>
                          <input type="text"  name="payment_tax" value="<?php echo (isset($data['payment_tax'])) ? $data['payment_tax'] : ''; ?>" class="form-control" placeholder="Payment Tax Prefix">
                        </div>

                        <div class="col-12 col-md-3">
                          <label for="bos_debit">Payment Prefix(Tax Free)</label>
                          <input type="text"  name="payment_taxfree" value="<?php echo (isset($data['payment_tax_free'])) ? $data['payment_tax_free'] : ''; ?>" class="form-control" placeholder="Payment Tax free Prefix">
                        </div>
                    </div>

                    <hr class="alert-dark">
                    <div class="form-group row">
                      <div class="col-12 col-md-3">
                          <label for="bos_debit">Courier Payment Prefix</label>
                          <input type="text"  name="courier_payment" value="<?php echo (isset($data['courier_payment'])) ? $data['courier_payment'] : ''; ?>" class="form-control" placeholder="Courier Payment Prefix">
                        </div>
                    </div>

                    <hr class="alert-dark">
                    <div class="form-group row">
                      <div class="col-12 col-md-3">
                          <label for="bos_debit">Quotation Prefix(Cash)</label>
                          <input type="text"  name="quotation_cash" value="<?php echo (isset($data['quotation_cash'])) ? $data['quotation_cash'] : ''; ?>" class="form-control" placeholder="Quotation Cash Prefix">
                        </div>

                        <div class="col-12 col-md-3">
                          <label for="bos_debit">Quotation Prefix(Debit)</label>
                          <input type="text"  name="quotation_debit" value="<?php echo (isset($data['quotation_debit'])) ? $data['quotation_debit'] : ''; ?>" class="form-control" placeholder="Quotation Debit Prefix">
                        </div>
                    </div>
                    
                    <br/>
                    <div class="row">
                      <div class="col-md-12">
                        <a href="configuration.php" class="btn btn-light">Cancel</a>
                        <button name="submit" type="submit" name="submit" class="btn btn-success">Submit</button>
                      </div>
                    </div>
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

  <script src="js/parsley.min.js"></script>
    <script type="text/javascript">
      $('form').parsley();
    </script>
 
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script>
  
  
  <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <script>
     $('.datatable').DataTable();
  </script>
  
  <!-- change status js -->
  <script src="js/custom/onlynumber.js"></script>
  
  <!-- End custom js for this page-->
  
</body>


</html>
