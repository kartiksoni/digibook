<?php $title = "Bill Notes"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php');?>

<?php
    $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
    $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
    $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
    
    if(isset($_POST['submit'])){
        $user_id = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : '';
        $date = date('Y-m-d H:i:s');
        $pefix_cash = (isset($_POST['pefix_cash'])) ? $_POST['pefix_cash'] : '';
        $pefix_debit = (isset($_POST['pefix_debit'])) ? $_POST['pefix_debit'] : '';
        $credit_note = (isset($_POST['credit_note'])) ? $_POST['credit_note'] : '';
        $challan_cash = (isset($_POST['challan_cash'])) ? $_POST['challan_cash'] : '';
        $challan_debit = (isset($_POST['challan_debit'])) ? $_POST['challan_debit'] : '';
        $purchase_cash = (isset($_POST['purchase_cash'])) ? $_POST['purchase_cash'] : '';
        $purchase_debit = (isset($_POST['purchase_debit'])) ? $_POST['purchase_debit'] : '';
        $debit_note = (isset($_POST['debit_note'])) ? $_POST['debit_note'] : '';
        $cash_management_cash = (isset($_POST['cash_management_cash'])) ? $_POST['cash_management_cash'] : '';
        $cash_management_receipt = (isset($_POST['cash_management_receipt'])) ? $_POST['cash_management_receipt'] : '';
        $vendor_payments = (isset($_POST['vendor_payments'])) ? $_POST['vendor_payments'] : '';
        $customer_receipts = (isset($_POST['customer_receipts'])) ? $_POST['customer_receipts'] : '';
        $accounting_bank_payment = (isset($_POST['accounting_bank_payment'])) ? $_POST['accounting_bank_payment'] : '';
        $accounting_bank_receipt = (isset($_POST['accounting_bank_receipt'])) ? $_POST['accounting_bank_receipt'] : '';
        $payment_tax = (isset($_POST['payment_tax'])) ? $_POST['payment_tax'] : '';
        $payment_taxfree = (isset($_POST['payment_taxfree'])) ? $_POST['payment_taxfree'] : '';
        $courier_payment = (isset($_POST['courier_payment'])) ? $_POST['courier_payment'] : '';
        
        $existQ = "SELECT id FROM sale_pefix WHERE pharmacy_id = '".$pharmacy_id."'";
        $existR = mysqli_query($conn, $existQ);
        if($existR && mysqli_num_rows($existR) > 0){
            $existRow = mysqli_fetch_assoc($existR);
            $updateQ = "UPDATE sale_pefix SET pefix_name_cash = '".$pefix_cash."', pefix_name_debit = '".$pefix_debit."', sale_credit_note = '".$credit_note."', challan_cash = '".$challan_cash."', challan_debit = '".$challan_debit."', purchase_cash = '".$purchase_cash."', purchase_debit = '".$purchase_debit."', purchase_debit_note = '".$debit_note."', cash_management_cash = '".$cash_management_cash."', cash_management_receipt = '".$cash_management_receipt."', vendor_payments = '".$vendor_payments."', customer_receipts = '".$customer_receipts."', accounting_bank_cheque = '".$accounting_bank_payment."', accounting_bank_cheque_receipt = '".$accounting_bank_receipt."', payment_tax = '".$payment_tax."', payment_tax_free = '".$payment_taxfree."', courier_payment = '".$courier_payment."'";
            $updateR = mysqli_query($conn, $updateQ);
            if($updateR){
                $_SESSION['msg']['success'] = "Prifix Update Successfully.";
            }else{
                $_SESSION['msg']['fail'] = "Prifix Update Fail!";
            }
        }else{
            $insertQ = "INSERT INTO sale_pefix SET pharmacy_id = '".$pharmacy_id."', pefix_name_cash = '".$pefix_cash."', pefix_name_debit = '".$pefix_debit."', sale_credit_note = '".$credit_note."', challan_cash = '".$challan_cash."', challan_debit = '".$challan_debit."', purchase_cash = '".$purchase_cash."', purchase_debit = '".$purchase_debit."', purchase_debit_note = '".$debit_note."', cash_management_cash = '".$cash_management_cash."', cash_management_receipt = '".$cash_management_receipt."', vendor_payments = '".$vendor_payments."', customer_receipts = '".$customer_receipts."', accounting_bank_cheque = '".$accounting_bank_payment."', accounting_bank_cheque_receipt = '".$accounting_bank_receipt."', payment_tax = '".$payment_tax."', payment_tax_free = '".$payment_taxfree."', courier_payment = '".$courier_payment."'";
            $insertR = mysqli_query($conn, $insertQ);
            if($insertR){
                $_SESSION['msg']['success'] = "Prifix Added Successfully.";
            }else{
                $_SESSION['msg']['fail'] = "Prifix Added Fail!";
            }
        }
        header('location:sale-pefix.php');exit;
    }
    
    $getPrifixQ = "SELECT * FROM sale_pefix WHERE pharmacy_id = '".$pharmacy_id."'";
    $getPrifixR = mysqli_query($conn, $getPrifixQ);
    if($getPrifixR && mysqli_num_rows($getPrifixR) > 0){
        $editData = mysqli_fetch_assoc($getPrifixR);
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
  <link rel="stylesheet" href="css/parsley.css">
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
        
        
        <!-- Right Sidebar -->
        <?php include "include/sidebar-right.php" ?>
        
       
       <!-- Left Navigation -->
        <?php include "include/sidebar-nav-left.php" ?>
        
        
      
      
      <div class="main-panel">
      
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Series Prefix</h4><hr class="alert-dark"><br>
                  <form method="post">
                    <div class="row form-group">
                        <div class="col-12 col-md-4">
                            <lable>Sale Prefix(Cash)</lable>
                            <input type="text" class="form-control border" name="pefix_cash" value="<?php echo (isset($editData['pefix_name_cash'])) ? $editData['pefix_name_cash'] : ''; ?>" placeholder="Sale Cash Prefix">
                        </div>
                        <div class="col-12 col-md-4">
                            <lable>Sale Prefix(Debit)</lable>
                            <input type="text" class="form-control border" name="pefix_debit" value="<?php echo (isset($editData['pefix_name_debit'])) ? $editData['pefix_name_debit'] : ''; ?>" placeholder="Sale Debit Prefix">
                        </div>
                        
                        <div class="col-12 col-md-4">
                            <lable>Credit Note Prefix</lable>
                            <input type="text" class="form-control border" name="credit_note" value="<?php echo (isset($editData['sale_credit_note'])) ? $editData['sale_credit_note'] : ''; ?>" placeholder="Credit Note Prefix">
                        </div>
                    </div>
                    
                    <!--<hr class="alert-dark">
                    <div class="form-group row">
                        <div class="col-12 col-md-4">
                          <label for="bos_cash">Delivery Challan(Cash) </label>
                          <input type="text"  name="challan_cash" value="<?php echo (isset($editData['challan_cash'])) ? $editData['challan_cash'] : ''; ?>" class="form-control" placeholder="Delivery Challan Cash Prefix">
                        </div>
                        <div class="col-12 col-md-4">
                          <label for="bos_debit">Delivery Challan(Debit) </label>
                          <input type="text"  name="challan_debit" value="<?php echo (isset($editData['challan_debit'])) ? $editData['challan_debit'] : ''; ?>" class="form-control" placeholder="Delivery Challan Debit Prefix">
                        </div>
                    </div>-->
                    
                    <hr class="alert-dark">
                    <div class="form-group row">
                        <div class="col-12 col-md-4">
                          <label for="bos_cash">Purchase Prefix(Cash) </label>
                          <input type="text"  name="purchase_cash" value="<?php echo (isset($editData['purchase_cash'])) ? $editData['purchase_cash'] : ''; ?>" class="form-control" placeholder="Purchase Cash Prefix">
                        </div>
                        <div class="col-12 col-md-4">
                          <label for="bos_debit">Purchase Prefix(Debit) </label>
                          <input type="text"  name="purchase_debit" value="<?php echo (isset($editData['purchase_debit'])) ? $editData['purchase_debit'] : ''; ?>" class="form-control" placeholder="Purchase Debit Prefix">
                        </div>

                        <div class="col-12 col-md-4">
                          <label for="bos_debit">Debit Note Prefix</label>
                          <input type="text"  name="debit_note" value="<?php echo (isset($editData['purchase_debit_note'])) ? $editData['purchase_debit_note'] : ''; ?>" class="form-control" placeholder="Debit Note Prefix">
                        </div>
                    </div>

                    <hr class="alert-dark">
                    <div class="form-group row">
                        <div class="col-12 col-md-4">
                          <label for="bos_cash">Cash Management Prefix(Cash Payment) </label>
                          <input type="text"  name="cash_management_cash" value="<?php echo (isset($editData['cash_management_cash'])) ? $editData['cash_management_cash'] : ''; ?>" class="form-control" placeholder="Cash Management Cash Payment Prefix">
                        </div>
                        <div class="col-12 col-md-4">
                          <label for="bos_debit">Cash Management Prefix(Cash Receipt) </label>
                          <input type="text"  name="cash_management_receipt" value="<?php echo (isset($editData['cash_management_receipt'])) ? $editData['cash_management_receipt'] : ''; ?>" class="form-control" placeholder="Cash Management Cash Receipt Prefix">
                        </div>
                    </div>

                    <hr class="alert-dark">
                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                          <label for="bos_debit">Vendor Payments Prefix</label>
                          <input type="text"  name="vendor_payments" value="<?php echo (isset($editData['vendor_payments'])) ? $editData['vendor_payments'] : ''; ?>" class="form-control" placeholder="Vendor Payments Prefix">
                        </div>
                    </div>

                    <hr class="alert-dark">
                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                          <label for="bos_debit">Customer Receipts Prefix</label>
                          <input type="text"  name="customer_receipts" value="<?php echo (isset($editData['customer_receipts'])) ? $editData['customer_receipts'] : ''; ?>" class="form-control" placeholder="Customer Receipts Prefix">
                        </div>
                    </div>

                    <hr class="alert-dark">
                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                          <label for="bos_debit">Accounting Cheque Bank Prefix(Cash Payment)</label>
                          <input type="text"  name="accounting_bank_payment" value="<?php echo (isset($editData['accounting_bank_cheque'])) ? $editData['accounting_bank_cheque'] : ''; ?>" class="form-control" placeholder="Accounting Cheque Bank Prefix Cash Payment">
                        </div>

                        <div class="col-12 col-md-4">
                          <label for="bos_debit">Accounting Cheque Bank Prefix(Cash Receipt)</label>
                          <input type="text"  name="accounting_bank_receipt" value="<?php echo (isset($editData['accounting_bank_cheque_receipt'])) ? $editData['accounting_bank_cheque_receipt'] : ''; ?>" class="form-control" placeholder="Accounting Cheque Bank Prefix Cash Receipt">
                        </div>
                    </div>

                    <hr class="alert-dark">
                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                          <label for="bos_debit">Payment Prefix(Tax)</label>
                          <input type="text"  name="payment_tax" value="<?php echo (isset($editData['payment_tax'])) ? $editData['payment_tax'] : ''; ?>" class="form-control" placeholder="Payment Tax Prefix">
                        </div>

                        <div class="col-12 col-md-4">
                          <label for="bos_debit">Payment Prefix(Tax Free)</label>
                          <input type="text"  name="payment_taxfree" value="<?php echo (isset($editData['payment_tax_free'])) ? $editData['payment_tax_free'] : ''; ?>" class="form-control" placeholder="Payment Tax free Prefix">
                        </div>
                    </div>

                    <hr class="alert-dark">
                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                          <label for="bos_debit">Courier Payment Prefix</label>
                          <input type="text"  name="courier_payment" value="<?php echo (isset($editData['courier_payment'])) ? $editData['courier_payment'] : ''; ?>" class="form-control" placeholder="Courier Payment Prefix">
                        </div>
                    </div>
                    
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-12">
                          <a href="configuration.php" class="btn btn-light">Back</a>
                        <button name="submit" type="submit" class="btn btn-success mr-2">Submit</button>
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

  <script type="text/javascript">
    $("#commentForm").validate({
      errorPlacement: function(label, element) {
        label.addClass('mt-2 text-danger');
        label.insertAfter(element);
      },
      highlight: function(element, errorClass) {
        $(element).parent().addClass('has-danger')
        $(element).addClass('form-control-danger')
      }
    });
  </script>
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>
  <script src="js/editorDemo.js"></script>
  
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <!-- End custom js for this page-->
</body>


</html>
