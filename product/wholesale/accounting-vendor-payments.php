<?php $title = "Vendor Payment"; ?>
<?php include('include/usertypecheck.php');
include('include/permission.php'); 

if(isset($_POST['add_receipt'])){
  
  $user_id = $_SESSION['auth']['id'];
  //$vendor_receipt_no = $_POST['vendor_receipt_no'];
  if(isset($_GET['id']) && $_GET['id'] !=''){
    $vendor_receipt_no = $_POST['vendor_receipt_no'];  
  }else{
    $vendor_receipt_no = getvendorreceiptno();  
  }
  
  $customer = $_POST['vendor'];
  $payment_date = date('Y-m-d',strtotime(str_replace('/','-',$_POST['payment_date'])));
  $payment_mode = $_POST['payment_mode'];
  
  if(isset($_POST['payment_mode']) && $_POST['payment_mode'] == "cheque"){
    $deposit_bank_cheque = $_POST['deposit_bank_cheque'];
    $cheque_no = $_POST['cheque_no'];
    $cheque_date = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['cheque_date'])));
  }
  else{
    $deposit_bank_cheque = "";
    $cheque_no = "";
    $cheque_date = "";
  }

  if(isset($_POST['payment_mode']) && $_POST['payment_mode'] == "dd"){
  $dd_no = $_POST['dd_no'];
  $deposit_bank_dd = $_POST['deposit_bank_dd'];
  $dd_date = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['dd_date'])));
  }
  else{
  $dd_no = "";
  $deposit_bank_dd = "";
  $dd_date = "";
  }

  if(isset($_POST['payment_mode']) && $_POST['payment_mode'] == "net_banking"){
  $utr_number = $_POST['utr_number'];
  $deposit_bank_net_banking = $_POST['deposit_bank_net_banking'];
  }
  else{
  $utr_number = "";
  $deposit_bank_net_banking = "";
  }

  if(isset($_POST['payment_mode']) && $_POST['payment_mode'] == "credit_debit_card"){
  $card_number = $_POST['card_number'];
  $deposit_bank_credit_debit_card = $_POST['deposit_bank_credit_debit_card'];
  $name_on_card = $_POST['name_on_card'];
  }
  else{
  $card_number = "";
  $deposit_bank_credit_debit_card = "";
  $name_on_card = "";
  }

  if(isset($_POST['payment_mode']) && $_POST['payment_mode'] == "other"){
  $reference = $_POST['reference'];
  }
  else{
  $reference = "";
  }
  $amount = $_POST['amount'];
  $remarks = $_POST['remarks'];
  $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : NULL;
  $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : NULL;
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
  $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;

  if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
    $editid = $_REQUEST['id'];

    $vendoreditqry = "UPDATE `accounting_vendor_payment` SET `vendor_receipt_no`= '".$vendor_receipt_no."', `vendor` = '".$customer."',`payment_date`= '".$payment_date."', `payment_mode`= '".$payment_mode."', `deposit_bank_cheque`= '".$deposit_bank_cheque."',`cheque_no`= '".$cheque_no."', `cheque_date`= '".$cheque_date."', `dd_no`= '".$dd_no."', `dd_date`= '".$dd_date."',`deposit_bank_dd`= '".$deposit_bank_dd."', `utr_number`= '".$utr_number."', `deposit_bank_net_banking`= '".$deposit_bank_net_banking."', `card_number`= '".$card_number."', `deposit_bank_credit_debit_card`= '".$deposit_bank_credit_debit_card."', `name_on_card`= '".$name_on_card ."', `reference`= '".$reference."', `amount`= '".$amount."', `remarks`= '".$remarks."', `updated_at`= '".date('Y-m-d H:i:s')."', `updated_by`= '".$user_id."' WHERE id = '".$editid."'";

    $vendoreditrun = mysqli_query($conn, $vendoreditqry);

    if($vendoreditrun){
      $_SESSION['msg']['success'] = 'Vendor Receipt Updated Successfully.';
      header('location:accounting-vendor-payments.php');exit;
    }
    else{
      $_SESSION['msg']['fail'] = 'Vendor Receipt Updated Fail';
      header('location:accounting-vendor-payments.php');exit;
    }
  }
  else{
  $vendoraddqry = "INSERT INTO `accounting_vendor_payment`(`owner_id`, `admin_id`, `pharmacy_id`, `financial_id`, `vendor_receipt_no`, `vendor`, `payment_date`, `payment_mode`, `deposit_bank_cheque`, `cheque_no`, `cheque_date`, `dd_no`, `dd_date`, `deposit_bank_dd`, `utr_number`, `deposit_bank_net_banking`, `card_number`, `deposit_bank_credit_debit_card`, `name_on_card`, `reference`, `amount`, `remarks`, `creted_at`, `created_by`) VALUES ('".$owner_id."', '".$admin_id."', '".$pharmacy_id."', '".$financial_id."', '".$vendor_receipt_no."', '".$customer."', '".$payment_date."', '".$payment_mode."', '".$deposit_bank_cheque."', '".$cheque_no."', '".$cheque_date."', '".$dd_no."', '".$dd_date."', '".$deposit_bank_dd."', '".$utr_number."', '".$deposit_bank_net_banking."', '".$card_number."', '".$deposit_bank_credit_debit_card."', '".$name_on_card."','".$reference."', '".$amount."', '".$remarks."', '".date('Y-m-d H:i:s')."', '".$user_id."')";

  $vendoraddrun = mysqli_query($conn, $vendoraddqry);

  if($vendoraddrun){
    $_SESSION['msg']['success'] = 'Vendor Receipt Added Successfully.';
    header('location:accounting-vendor-payments.php');exit;
  }
  else{
    $_SESSION['msg']['fail'] = 'Vendor Receipt Added Fail';
    header('location:accounting-vendor-payments.php');exit;
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
        <!-- Right Sidebar -->
        <?php include "include/sidebar-right.php" ?>
        
       
       <!-- Left Navigation -->
        <?php include "include/sidebar-nav-left.php" ?>
        
        
      
      
      <div class="main-panel">

      <div class="content-wrapper">
      <span id="errormsg"></span>
      <form method="post" action="" autocomplete="off">
        
          <div class="row">
          
          
           <!-- Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                
                  <!-- Main Catagory -->
                    <div class="row">
                    <div class="col-12">
                        <div class="purchase-top-btns">
                            <?php if((isset($user_sub_module) && in_array("Cash Management", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <a href="accounting-cash-management.php" class="btn btn-dark active">Cash Management</a>
                            <?php } if((isset($user_sub_module) && in_array("Customer Receipt", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <a href="accounting-customer-receipt.php" class="btn btn-dark btn-fw">Customer Receipt</a>
                            <?php } if((isset($user_sub_module) && in_array("Cheque", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <a href="accounting-cheque.php" class="btn btn-dark  btn-fw">Cheque</a>
                            <?php } if((isset($user_sub_module) && in_array("Vendor Payment", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <a href="accounting-vendor-payments.php" class="btn btn-dark  btn-fw">Vendor Payment</a>
                            <?php } if((isset($user_sub_module) && in_array("Financial Year Settings", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <a href="financial-year.php" class="btn btn-dark  btn-fw">Financial Year Settings</a>
                            <?php } if((isset($user_sub_module) && in_array("Credit Note / Purchase Note", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <a href="purchase-return.php" class="btn btn-dark  btn-fw">Credit Note / Purchase Note</a>
                            <?php } 
                            /// Changes By Kartik in this module reseller Only ///
                            //if(isset($user_sub_module) && in_array("Quotation / Estimate / Proformo Invoice", $user_sub_module)){ 
                            ?>
                            <!--<a href="quotation.php" class="btn btn-dark  btn-fw">Quotation / Estimate / Proformo Invoice</a>-->
                            <?php //} 
                            if((isset($user_sub_module) && in_array("Journal Vouchar", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){
                            ?>
                            <a href="journal-vouchar.php" class="btn btn-dark  btn-fw">Journal Vouchar</a>
                            <?php } ?>
                        </div>   
                    </div> 
                    </div>
                    <hr>
                    <?php
                      if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
                        $id = $_REQUEST['id'];
                        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                        $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;
                        $vendordataqry = "select * from accounting_vendor_payment where id = '".$id."' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."'";
                        $vendordatarun = mysqli_query($conn, $vendordataqry);
                        $vendordata = mysqli_fetch_assoc($vendordatarun);
                      }
                    ?>
                    
                    <!-- First Row  -->
                    
                        <div class="form-group row">
                          <div class="col-12 col-md-3">
                              <label for="exampleInputName1">Vendor Receipt No.<span class="text-danger">*</span></label>
                              <input type="text" name="vendor_receipt_no" value="<?php if(isset($_REQUEST['id'])){echo $vendordata['vendor_receipt_no']; } else { echo getvendorreceiptno(); } ?>" class="form-control" id="exampleInputName1" placeholder="Vendor Receipt No." required="">
                          </div>
                        
                  
                         <div class="col-12 col-md-9">
                              <label for="exampleInputName1" class="pull-right bg-success color-white p-2">Running Balance: <span id="running_balance">0</span></label>
                         </div>     
                        
                        </div>
                      
                        <div class="form-group row">
                        
                        
                         <!-- CHEQUE FIELDS -->
                        <div class="col-12 col-md-2">
                        <label for="exampleInputName1">Vendor<span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" name="vendor" id="vendor_id" style="width:100%" required="" data-parsley-errors-container="#error-vendor"> 
                            <option value="">Select</option>
                            <?php 
                              $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                              $customerQuery = "SELECT id, name FROM `ledger_master` WHERE group_id = 14 AND pharmacy_id = '".$p_id."' ORDER BY name";
                              $customerRes = mysqli_query($conn, $customerQuery);
                              while ($customerRow = mysqli_fetch_array($customerRes)) {
                            ?>
                            <option value="<?php echo $customerRow['id']; ?>" <?php echo (isset($vendordata['vendor']) && $vendordata['vendor'] == $customerRow['id']) ? 'selected' : ''; ?>><?php echo $customerRow['name']; ?></option>
                            <?php } ?>
                        </select>
                        <span id="error-vendor"></span>
                        </div>
                        
                        <div class="col-12 col-md-2">
                        <label for="exampleInputName1">Payment Date<span class="text-danger">*</span></label>
                        <div id="" class="input-group date datepicker">
                            <input type="text" class="form-control border" name="payment_date" value="<?php if(isset($_REQUEST['id'])){echo date('d/m/Y', strtotime(str_replace('/','-', $vendordata['payment_date']))); } else { echo date("d/m/Y"); }?>" required="" data-parsley-errors-container="#error-date">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          <span id="error-date"></span>
                        </div>
                        
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Payment Mode<span class="text-danger">*</span></label>
                          <select id="payment_mode" class="js-example-basic-single payment_mode" name="payment_mode" style="width:100%" required="" data-parsley-errors-container="#error-payment"> 
                            <option value="">Select Any One </option>
                            <option value="cash" <?php if(isset($_REQUEST['id']) && $vendordata['payment_mode'] == "cash"){echo "selected";}?>>Cash </option>

                            <option value="cheque" <?php if(isset($_REQUEST['id']) && $vendordata['payment_mode'] == "cheque"){echo "selected";}?>>Cheque </option>

                            <option value="dd" <?php if(isset($_REQUEST['id']) && $vendordata['payment_mode'] == "dd"){echo "selected";}?>>DD </option>

                            <option value="net_banking" <?php if(isset($_REQUEST['id']) && $vendordata['payment_mode'] == "net_banking"){echo "selected";}?>>Net Banking </option>

                            <option value="credit_debit_card" <?php if(isset($_REQUEST['id']) && $vendordata['payment_mode'] == "credit_debit_card"){echo "selected";}?>>Credit/Debit Card </option>
                            <!-- <option value="on_account">On Account</option> -->
                            <option value="other" <?php if(isset($_REQUEST['id']) && $vendordata['payment_mode'] == "other"){echo "selected";}?>>Other</option>
                        </select>
                        <span id="error-payment"></span>
                        </div>
                        
                        </div>
                        
                              <div class="form-group row div_cheque" <?php if(isset($_REQUEST['id']) && $vendordata['payment_mode'] == "cheque"){ } else{ ?> style="display: none;" <?php } ?>>
                          
                            <div class="col-12 col-md-2">
                              <label for="cheque_no">Cheque No<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="cheque_no" name="cheque_no" placeholder="Cheque No" 
                              value="<?php if(isset($_REQUEST['id']) && $vendordata['cheque_no'] != ''){echo $vendordata['cheque_no'];}?>" required="">
                            </div>
                            <div class="col-12 col-md-2">
                              <label for="deposit_bank">Deposit Bank<span class="text-danger">*</span></label>
                              <select id="deposit_bank" name="deposit_bank_cheque" class="js-example-basic-single deposit_bank" style="width:100%" required="" data-parsley-errors-container="#error-bankcheque"> 
                                <option value="">Select Any One </option>
                                <?php
                                $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                                $bankQuery = "SELECT id, bank_name FROM pharmacy_bank_details WHERE pharmacy_id = '".$p_id."'"; 
                                $bankRes = mysqli_query($conn, $bankQuery);
                                while ($bankRow = mysqli_fetch_array($bankRes)) {
                                ?>
                                <option value="<?php echo $bankRow['id']; ?>" <?php echo (isset($vendordata['deposit_bank_cheque']) && $vendordata['deposit_bank_cheque'] == $bankRow['id']) ? 'selected' : '';?>><?php echo $bankRow['bank_name']; ?></option>
                                <?php } ?>
                              </select>
                              <span id="error-bankcheque"></span>
                            </div>
                            <div class="col-12 col-md-2">
                              <label for="cheque_date">Cheque Date<span class="text-danger">*</span></label>
                              <div id="" class="input-group date datepicker">
                                  <input type="text" class="form-control border" name="cheque_date" value="<?php if(isset($_REQUEST['id']) && $vendordata['payment_mode'] == "cheque") { echo date('d/m/Y', strtotime(str_replace('/', '-', $vendordata['cheque_date']))); } else { echo date("d/m/Y"); } ?>" required="" data-parsley-errors-container="#error-bankchequedate">
                                  <span class="input-group-addon input-group-append border-left">
                                    <span class="mdi mdi-calendar input-group-text"></span>
                                  </span>
                                </div>
                                <span id="error-bankchequedate"></span>
                          </div>
                          
                        </div>

                        <div class="form-group row div_dd" <?php if(isset($_REQUEST['id']) && $vendordata['payment_mode'] == "dd"){ } else{ ?> style="display: none;" <?php } ?>>
                          
                          <div class="col-12 col-md-2">
                              <label for="dd_no">DD No<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="dd_no" name="dd_no" placeholder="DD No" value="<?php if(isset($_REQUEST['id']) && $vendordata['dd_no'] != ''){echo $vendordata['dd_no'];}?>" required="">
                            </div>
                            <div class="col-12 col-md-2">
                              <label for="deposit_bank">Deposit Bank<span class="text-danger">*</span></label>
                              <select id="deposit_bank" name="deposit_bank_dd" class="js-example-basic-single deposit_bank" style="width:100%" required="" data-parsley-errors-container="#error-bankdd"> 
                               <option value="">Select Any One </option>
                                <?php
                                $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                                $bankQuery = "SELECT id, bank_name FROM pharmacy_bank_details WHERE pharmacy_id = '".$p_id."'"; 
                                $bankRes = mysqli_query($conn, $bankQuery);
                                while ($bankRow = mysqli_fetch_array($bankRes)) {
                                ?>
                                <option value="<?php echo $bankRow['id']; ?>" <?php echo (isset($vendordata['deposit_bank_dd']) && $vendordata['deposit_bank_dd'] == $bankRow['id']) ? 'selected' : '';?> > <?php echo $bankRow['bank_name']; ?></option>
                                <?php } ?>
                            </select>
                            <span id="error-bankdd"></span>
                            </div>
                            <div class="col-12 col-md-2">
                              <label for="dd_date">DD Date<span class="text-danger">*</span></label>
                              <div id="" class="input-group date datepicker">
                                  <input type="text" class="form-control border" name="dd_date" value="<?php if(isset($_REQUEST['id']) && $vendordata['payment_mode'] == "dd"){echo date('d/m/Y', strtotime(str_replace('/', '-', $vendordata['dd_date']))); } else { echo date("d/m/Y"); } ?>" required="" data-parsley-errors-container="#error-bankdddate">
                                  <span class="input-group-addon input-group-append border-left">
                                    <span class="mdi mdi-calendar input-group-text"></span>
                                  </span>
                                </div>
                                <span id="error-bankdddate"></span>
                          </div>
                          
                        </div>

                        <div class="form-group row div_net_banking" <?php if(isset($_REQUEST['id']) && $vendordata['payment_mode'] == "net_banking"){ } else{ ?> style="display: none;" <?php } ?>>
                          <div class="col-12 col-md-2">
                              <label for="utr_number">UTR Number<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="utr_number" name="utr_number" placeholder="UTR Number" required="" value="<?php if(isset($_REQUEST['id'])){echo $vendordata['utr_number']; } ?>">
                          </div>
                          
                          <div class="col-12 col-md-2">
                              <label for="deposit_bank">Deposit Bank<span class="text-danger">*</span></label>
                              <select id="deposit_bank" name="deposit_bank_net_banking" class="js-example-basic-single deposit_bank" style="width:100%" required="" data-parsley-errors-container="#error-banknetbanking"> 
                                <option value="">Select Any One </option>
                                <?php
                                $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                                $bankQuery = "SELECT id, bank_name FROM pharmacy_bank_details WHERE pharmacy_id = '".$p_id."'"; 
                                $bankRes = mysqli_query($conn, $bankQuery);
                                while ($bankRow = mysqli_fetch_array($bankRes)) {
                                ?>
                                <option value="<?php echo $bankRow['id']; ?>" <?php echo (isset($vendordata['deposit_bank_net_banking']) && $vendordata['deposit_bank_net_banking'] == $bankRow['id']) ? 'selected' : '';?>><?php echo $bankRow['bank_name']; ?></option>
                                <?php } ?>
                            </select>
                            <span id="error-banknetbanking"></span>
                          </div>
                        </div>

                        <div class="form-group row div_credit_debit_card" <?php if(isset($_REQUEST['id']) && $vendordata['payment_mode'] == "credit_debit_card"){ } else{ ?> style="display: none;" <?php } ?>>
                          <div class="col-12 col-md-2">
                              <label for="card_number">Card Number<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="card_number" name="card_number" placeholder="Card Number" required="" value="<?php if(isset($_REQUEST['id'])){echo $vendordata['card_number']; } ?>">
                          </div>
                          <div class="col-12 col-md-2">
                              <label for="deposit_bank">Deposit Bank<span class="text-danger">*</span></label>
                              <select id="deposit_bank" name="deposit_bank_credit_debit_card" class="js-example-basic-single deposit_bank" style="width:100%" required="" data-parsley-errors-container="#error-bankcard"> 
                               <option value="">Select Any One </option>
                                <?php
                                $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                                $bankQuery = "SELECT id, bank_name FROM pharmacy_bank_details WHERE pharmacy_id = '".$p_id."'"; 
                                $bankRes = mysqli_query($conn, $bankQuery);
                                while ($bankRow = mysqli_fetch_array($bankRes)) {
                                ?>
                                <option value="<?php echo $bankRow['id']; ?>" <?php echo (isset($vendordata[' deposit_bank_credit_debit_card']) && $vendordata['  deposit_bank_credit_debit_card'] == $bankRow['id']) ? 'selected' : '';?>><?php echo $bankRow['bank_name']; ?></option>
                                <?php } ?>
                              </select>
                              <span id="error-bankcard"></span>
                          </div>
                           <div class="col-12 col-md-2">
                              <label for="name_on_card">Name On Card<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="name_on_card" name="name_on_card" placeholder="Name On Card" required="" value="<?php if(isset($_REQUEST['id'])){echo $vendordata['name_on_card']; }?>">
                          </div>
                        </div>

                        <div class="form-group row div_other" style="display: none;">
                          <div class="col-12 col-md-2">
                              <label for="reference">Reference<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="reference" name="reference" placeholder="Reference" value="<?php if(isset($_REQUEST['id'])){echo $vendordata['reference']; }?>" required="">
                          </div>
                        </div>
                        
                       
                       
                       <div class="form-group row">
                       
                       <div class="col-12 col-md-2">
                        <label for="amount">Amount<span class="text-danger">*</span></label>
                        <input type="text" class="form-control onlynumber" id="amount" name="amount" placeholder="Amount" required="" autocomplete="off" data-parsley-type="number" value="<?php if(isset($_REQUEST['id'])){echo $vendordata['amount']; } ?>">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="remarks">Remarks</label>
                        <textarea  class="form-control" id="remarks" name="remarks" placeholder="Remarks" rows="3"><?php if(isset($_REQUEST['id'])){echo $vendordata['remarks']; } ?></textarea>
                        </div>
                        
                        
                        
                        
                        <div class="col-12">  
                        <button type="submit" name="add_receipt" class="btn btn-success mt-30">Add Receipt</button>                        
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

                    <?php 
                        $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                        $dataqry = "SELECT ledger_master.name AS name, ledger_master.id AS ledger_id, accounting_vendor_payment.vendor, accounting_vendor_payment.id FROM ledger_master INNER JOIN accounting_vendor_payment ON ledger_master.id = accounting_vendor_payment.vendor WHERE ledger_master.pharmacy_id = '".$p_id."' GROUP BY ledger_master.id ORDER BY accounting_vendor_payment.id DESC";
                        $datarun = mysqli_query($conn, $dataqry);
                    ?>                
                    <!-- TABLE STARTS -->
                    <div class="col mt-3">
                       <div class="row">
                            <div class="col-12">
                              <table id="order-listing1" class="table">
                                <thead>
                                  <tr>
                                      <th>Sr No.</th>
                                      <th>Vendor</th>
                                      <th>Opening Balance</th>
                                      <th>Bill Ammount</th>
                                      <th>Total Ammount</th>
                                      <th>Ammount Received</th>
                                      <th>Pending Ammount</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts --> 
                                  <?php
                                    if($datarun){
                                        $count = 0;
                                        while($data = mysqli_fetch_assoc($datarun)){
                                        $count++;    
                                        $running = countRunningBalance($data['ledger_id']);
                                  ?>  
                                    <tr>
                                        <td><?php echo $count;?></td>
                                        <td><?php echo $data['name']; ?></td>
                                        <td class="text-right">
                                            <?php 
                                                if($running['opening_balance'] >=0){
                                                    echo amount_format(number_format(abs($running['opening_balance']), 2, '.', '')) .' Dr';
                                                }else{
                                                    echo amount_format(number_format(abs($running['opening_balance']), 2, '.', '')) .' Cr';
                                                } 
                                            ?>
                                        </td>
                                        <td class="text-right">
                                          <?php echo amount_format(number_format($running['total_bill'], 2, '.', '')); ?>
                                        </td>
                                        <td class="text-right">
                                            <?php 
                                                $total = $running['opening_balance'] + $running['total_bill']; 
                                                if($total >= 0){
                                                    echo amount_format(number_format(abs($total), 2, '.', '')).' Dr';
                                                } else {
                                                    echo amount_format(number_format(abs($total), 2, '.', '')).' Cr';
                                                }
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <?php echo amount_format(number_format($running['total_payment'], 2, '.', '')); ?>
                                        </td>
                                        <td class="text-right">
                                            <?php 
                                                $pending = $total - $running['total_payment']; 
                                                if($pending >= 0){
                                                    echo amount_format(number_format(abs($pending), 2, '.', '')).' Dr';
                                                } else {
                                                    echo amount_format(number_format(abs($pending), 2, '.', '')).' Cr';
                                                } 
                                            ?>
                                        </td>
                                        <td><a href="vendor-ledger-print.php?id=<?php echo $data['ledger_id'];?>" target="_blank" class="btn  btn-behance p-2"><i class="fa fa-file mr-0"></i></a></td>
                                      
                                    </tr><!-- End Row --> 
                                  
                                    <?php } }?>
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
  <script src="js/custom/onlynumber.js"></script>
  
  <!-- Custom js for this page Modal Box-->
  <script src="js/modal-demo.js"></script>
  
  
  <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  
  <!-- Datepicker Initialise-->
 
 <script>
    $('.datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy',
      autoclose: true
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
  <script src="js/jquery-ui.js"></script>
  <script src="js/parsley.min.js"></script>
  <script type="text/javascript">
     $('form').parsley({
    excluded:':hidden'
    });

  </script>
  <script src="js/custom/accounting-vendor-payments.js"></script>
  
  
  <!-- End custom js for this page-->
</body>


</html>
