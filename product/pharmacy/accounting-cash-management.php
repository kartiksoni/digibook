<?php include('include/usertypecheck.php');

if (isset($_POST['submit'])) {

  $user_id = $_SESSION['auth']['id'];
  if (isset($_POST['state_gst_code']) && $_POST['state_gst_code'] == 24) {
    $sgst = $_POST['gst'];
    $sgst /= 2;
  } else {
    $igst = $_POST['gst'];
    $sgst = 0;
  }

  $paymenttype = $_POST['paymenttype'];
  $reverse = $_POST['reversechange'];

  if (isset($_POST['gst'])) {

    $reverse_change_gst = $_POST['gst'];
  } else {
    $reverse_change_gst = 0;
  }
  $voucherno = $_POST['voucherno'];
  $voucherdate = $_POST['date'];
  $crdr = $_POST['credit_debit'];
  $amount = $_POST['amount'];
  $groupid = $_POST['group'];
  $perticular = $_POST['Perticulars'];
  $state_gst_code = $_POST['state_gst_code'];
  $remark = $_POST['remark'];

   //Data Update Query
  if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
    $editid = $_REQUEST['id'];

    $editqry = "UPDATE `accounting_cash_management` SET `payment_type`= '" . $paymenttype . "', `reverse_change`= '" . $reverse . "',`sgst`= '" . $sgst . "', `cgst`= '" . $sgst . "', `igst`= '" . $igst . "', `reverse_change_gst`= '" . $reverse_change_gst . "', `voucher_no`= '" . $voucherno . "', `voucher_date`= '" . $voucherdate . "', `credit_debit`= '" . $crdr . "', `amount`= '" . $amount . "', `group_id`= '" . $groupid . "', `perticular`= '" . $perticular . "', `state_gst_code`= '" . $state_gst_code . "', `remark`= '" . $remark . "', `modified`= '" . date('Y-m-d H:i:s') . "', `modifiedby`= '" . $user_id . "' WHERE id = '" . $editid . "'";

    $editrun = mysqli_query($conn, $editqry);

    if ($editrun) {

      $_SESSION['msg']['success'] = 'Data Updated Successfully.';
      header('location:accounting-cash-management.php');
      exit;
    } else {

      $_SESSION['msg']['fail'] = 'Updated fail.';
    }
  }
   
   //Data Insert Query
  else {
    $addaccountqry = "INSERT INTO `accounting_cash_management`(`payment_type`, `reverse_change`, `sgst`, `cgst`, `igst`, `reverse_change_gst`, `voucher_no`, `voucher_date`, `credit_debit`, `amount`, `group_id`, `perticular`, `state_gst_code`, `remark`, `created`, `createdby`) VALUES ('" . $paymenttype . "', '" . $reverse . "', '" . $sgst . "', '" . $sgst . "', '" . $igst . "', '" . $reverse_change_gst . "', '" . $voucherno . "', '" . $voucherdate . "', '" . $crdr . "', '" . $amount . "', '" . $groupid . "', '" . $perticular . "', '" . $state_gst_code . "', '" . $remark . "', '" . date('Y-m-d H:i:s') . "', '" . $user_id . "')";

    $addaccountrun = mysqli_query($conn, $addaccountqry);

    if ($addaccountrun) {

      $_SESSION['msg']['success'] = 'Data Added Successfully.';
    } else {

      $_SESSION['msg']['fail'] = 'Added Fail';
    }
  }
}
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
  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" />
  <link rel="stylesheet" href="css/parsley.css">
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
          <?php include('include/flash.php'); ?>
          <span id="errormsg"></span>
        <form  method="post" action="">
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
                            <a href="accounting-vendor-payments.php" class="btn btn-dark  btn-fw">Vendor Payment</a>
                            <a href="#" class="btn btn-dark  btn-fw">Financial Year Settings</a>
                            <a href="purchase-return.php" class="btn btn-dark  btn-fw">Credit Note / Purchase Note</a>
                            <a href="#" class="btn btn-dark  btn-fw">Quotation / Estimate / Proformo Invoice</a>
                        </div>   
                      </div> 
                    </div>
                    <hr>
                    
                    <!-- First Row  -->
                 

                    <?php
                    if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
                      $id = $_REQUEST['id'];
                      $accountdataqry = "select * from accounting_cash_management where id = '" . $id . "'";
                      $accountdatarun = mysqli_query($conn, $accountdataqry);
                      $accountdatarecord = mysqli_fetch_assoc($accountdatarun);
                    }
                    ?>      
                      <div class="form-group row">
                        <?php
                        include('function.php');
                        $runningbalance = runningbalance();
                        ?>
                        <div class="col-12">
                              <label for="exampleInputName1" class="pull-right bg-success color-white p-2">Running Balance: (<?php echo $runningbalance; ?>)</label>
                        </div>     

                        <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Select Type</label>
                            <div class="row no-gutters">
                                <div class="col-12 col-md-6">
                                    <div class="form-radio">
                                      <label class="form-check-label">
                                      <input type="radio" class="form-check-input cash" name="paymenttype" id="optionsRadios1" value="cash_payment" checked <?php if (isset($_REQUEST['id']) && $accountdatarecord['payment_type'] == "cash_payment") {echo "checked";} ?>>
                                   	  Cash Payment
                                      </label>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                  <div class="form-radio">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input cash" name="paymenttype" id="optionsRadios2" value="cash_receipt" <?php if (isset($_REQUEST['id']) && $accountdatarecord['payment_type'] == "cash_receipt") {echo "checked";} ?>>
                                      Cash Receipt
                                    </label>
                                  </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Reverse Change</label>
                          <div class="row no-gutters">
                                  
                            <div class="col-12 col-md-6">
                              <div class="form-radio">
                                <label class="form-check-label">
                                  <input type="radio" class="form-check-input reversechange" name="reversechange" value="no" checked 
                                  <?php if (isset($_REQUEST['id']) && $accountdatarecord['reverse_change'] == "no"){echo "checked";} ?>>
                               		No
                                </label>
                              </div>
                            </div>
                            
                            <div class="col-12 col-md-6">
                              <div class="form-radio">
                                <label class="form-check-label">
                                  <input type="radio" class="form-check-input reversechange" name="reversechange" value="yes" <?php if (isset($_REQUEST['id']) && $accountdatarecord['reverse_change'] == "yes") {echo "checked";} ?>>
                                  Yes
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-12 col-md-4" id="reversechangeper" <?php if (isset($_REQUEST['id']) && $accountdatarecord            ['reverse_change'] == "yes") {} else { ?> style="display: none;"<?php } ?>>
                          <label for="exampleInputName1">GST%</label>
                            <div class="row no-gutters">
                              <div class="col-12 col-md-4">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="gst" id="" value="5" <?php if (isset($_REQUEST['id']) && $accountdatarecord['reverse_change_gst'] == "5") {echo "checked";} ?>> 
                                    5%
                                  </label>
                                </div>
                              </div>

                              <div class="col-12 col-md-4">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="gst" id="" value="12" <?php if (isset($_REQUEST['id']) && $accountdatarecord['reverse_change_gst'] == "12") {echo "checked";} ?>>
                                    12%
                                  </label>
                                </div>
                              </div>

                              <div class="col-12 col-md-4">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="gst" id="" value="18" <?php if (isset($_REQUEST['id']) && $accountdatarecord['reverse_change_gst'] == "18") {echo "checked";} ?>>
                                    18%
                                  </label>
                                </div>
                              </div>
                            </div>
                        </div>                    
                      </div>
                      
                      <div class="form-group row">
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Voucher No.</label>
                            <?php
                            $voucher = getcashpaymentno();
                            ?>
                            <input type="text" class="form-control" id="voucherno" class="voucherno" name="voucherno"  value="<?php if (isset($_REQUEST['id'])) {echo $accountdatarecord['voucher_no'];} else {echo $voucher;} 
                            ?>"placeholder="Voucher No." required>
                        </div>
                        
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Date</label>
                            <div class="input-group date datepicker">
                              <input type="text" class="form-control border" name="date" value="<?php if (isset($_REQUEST['id'])) {echo $accountdatarecord['voucher_date'];} else { echo date("d/m/Y");} ?>" required="">
                              <span class="input-group-addon input-group-append border-left">
                                <span class="mdi mdi-calendar input-group-text"></span>
                              </span>
                            </div>
                        </div>
                      </div>      
                </div>
              </div>
            </div>
            
            
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
                                <td>
                                  <select class="js-example-basic-single" name="credit_debit" style="width:100%"                        id="exampleInputName1" required="">
                                   <option value="credit" <?php if (isset($_REQUEST['id']) && $accountdatarecord['credit_debit'] == "credit") {echo "selected";} ?>>Credit </option>
                                            
                                   <option value="debit" <?php if (isset($_REQUEST['id']) && $accountdatarecord['credit_debit'] == "debit") {echo "selected";} ?>>Debit </option> 
                                  </select>
                                </td>

                                <td>
                                  <input type="text" class="form-control" name="amount" id="exampleInputName1" placeholder="Enter Amount" data-parsley-type="number" required="" value="<?php if (isset($_REQUEST['id'])) {echo $accountdatarecord['amount'];}?>">
                                </td>

                                <td>
                                  <select class="js-example-basic-single" name="group" style="width:100%" id="group" required=""> 
                                    <option value="">Please Select</option>
                                      <?php
                                        $dataqry = "SELECT * FROM `group`";
                                        $datarun = mysqli_query($conn, $dataqry);
                                        while ($data = mysqli_fetch_assoc($datarun)) { ?>
                                            
                                    <option value="<?php echo $data['id']; ?>" <?php echo (isset($accountdatarecord['group_id']) && $accountdatarecord['group_id'] == $data['id']) ? 'selected' : ''; ?>> <?php echo $data['name']; ?></option>
                                      <?php  } ?>
                                  </select>
                                </td>
                                    
                                <td>
                                  <select class="js-example-basic-single state" name="Perticulars" style="width:100%" id="ledger" required=""> 
                                    <option value="">Please Select</option>
                                      <?php
                                      $sqlqry = "SELECT * FROM `ledger_master` WHERE group_id = '".$accountdatarecord['group_id']."'";
                                      $sqlqryrun = mysqli_query($conn, $sqlqry);
                                      while ($sqldata = mysqli_fetch_assoc($sqlqryrun)) { ?>
                                    <option value="<?php echo $sqldata['id']; ?>" <?php echo (isset($accountdatarecord['perticular']) 
                                      && $accountdatarecord['perticular'] == $sqldata['id']) ? 'selected' : '';?>> <?php echo $sqldata['name']; ?> 
                                    </option>  
                                      <?php } ?>
                                  </select>
                                </td>
                                      
                              </tr><!-- End Row --> 	
                                <tr>
                                 <td>
                                    <label for="exampleInputName1">Remarks</label>
                                      <textarea  class="form-control" name="remark" id="exampleInputName1" placeholder="Remarks"  rows="3"><?php if (isset($_REQUEST['id'])) {echo $accountdatarecord['remark'];} ?></textarea> 
                                  </td>
                                    <td colspan="3">&nbsp;</td>
                                </tr>   
                            </tbody>
                        </table>
                      </div>
                    </div>    <hr>
                          
                      
                  <div class="col-12">
                    <a href="accounting-cash-management-list.php" name="back" class="btn btn-dark pull-left">Back</a>
                      <input type="hidden" name="state_gst_code" id="state_gst_code" value="<?php if (isset($_REQUEST['id'])) {echo $accountdatarecord['state_gst_code'];} ?>">
                      <button type="submit" name="submit" class="btn btn-success mr-2 pull-right">Submit</button>
                  </div>
          </div>         
        </form>
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
    $('.datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy',
      autoclose : true
    });
 </script>
 
  <script src="js/jquery-ui.js"></script>
  <script src="js/custom/accounting-cash-management.js"></script>
  
  <!-- script for custom validation -->
  <script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>
  
  <!-- End custom js for this page-->
</body>
</html>
