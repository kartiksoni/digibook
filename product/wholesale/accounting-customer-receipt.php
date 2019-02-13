<?php $title = "Customer Receipt"; ?>
<?php include('include/usertypecheck.php');
include('include/permission.php');

if(isset($_POST['add_receipt'])){
  
  $user_id = $_SESSION['auth']['id'];
  if(isset($_GET['id']) && $_GET['id'] != ''){
      $cash_receipt_no = $_POST['cash_receipt_no'];
  }else{
      $cash_receipt_no = getcustomerreceiptno();
  }
  
  $customer = $_POST['customer'];
  $payment_date = date('Y-m-d',strtotime(str_replace('/','-',$_POST['payment_date'])));
  $payment_mode = $_POST['payment_mode'];

  if(isset($_POST['payment_mode']) && $_POST['payment_mode'] == "cheque"){
    $cheque_no = $_POST['cheque_no'];
    $deposit_bank_cheque = $_POST['deposit_bank_cheque'];
    $cheque_date = date('Y-m-d',strtotime(str_replace('/','-',$_POST['cheque_date'])));
  }
  else{
    $cheque_no = "";
    $deposit_bank_cheque = "";
    $cheque_date = "";
  }
  
  if(isset($_POST['payment_mode']) && $_POST['payment_mode'] == "dd"){
    $dd_no = $_POST['dd_no'];
    $deposit_bank_dd = $_POST['deposit_bank_dd'];
    $dd_date = date('Y-m-d',strtotime(str_replace('/','-',$_POST['dd_date'])));
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

 if(isset($_GET['id']) && $_GET['id'] != ''){
    $insert = "UPDATE cash_receipt SET ";
  }else{
    $insert = "INSERT INTO cash_receipt SET owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', financial_id = '".$financial_id."',";
  }

  $insert .= "cash_receipt_no ='".$cash_receipt_no."',
                     customer ='".$customer."',
                     payment_date ='".$payment_date."',
                     payment_mode ='".$payment_mode."',
                     cheque_no ='".$cheque_no."',
                     deposit_bank_cheque ='".$deposit_bank_cheque."',
                     dd_no ='".$dd_no."',
                     deposit_bank_dd ='".$deposit_bank_dd."',
                     utr_number ='".$utr_number."',
                     deposit_bank_net_banking ='".$deposit_bank_net_banking."',
                     card_number = '".$card_number."',
                     deposit_bank_credit_debit_card = '".$deposit_bank_credit_debit_card."',
                     name_on_card = '".$name_on_card."',
                     reference = '".$reference."',
                     amount = '".$amount."',
                     remarks = '".$remarks."',
                     cheque_date = '".$cheque_date."',
                     dd_date = '".$dd_date."'";
                    /*if(isset($cheque_date) && $cheque_date != ''){
                      $insert .= ",cheque_date ='".$cheque_date."'";
                    }
                    if(isset($dd_date) && $dd_date != ''){
                      $insert .= ",dd_date ='".$dd_date."'";
                    } */

                     
                    if(isset($_GET['id']) && $_GET['id'] != ''){
                      $insert .= ",updated_at ='".date('Y-m-d H:i:s')."',updated_by = '".$user_id."' WHERE id='".$_GET['id']."'";
                    }else{
                      $insert .= ",creted_at ='".date('Y-m-d H:i:s')."',created_by = '".$user_id."'";
                    }
        
    $result = mysqli_query($conn,$insert);
    if($result){
      if(isset($_GET['id']) && $_GET['id'] != ''){
        $_SESSION['msg']['success'] = 'Customer Receipt Updated Successfully.';
      }else{
        $_SESSION['msg']['success'] = 'Customer Receipt Added Successfully.';
      }
      header('location:accounting-customer-receipt.php');exit; 
    }else{
      if(isset($_GET['id']) && $_GET['id'] != ''){
        $_SESSION['msg']['fail'] = 'Customer Receipt Updated Failed.';
      }else{
        $_SESSION['msg']['fail'] = 'Customer Receipt Added Failed.';
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
  <title>Customer Receipt</title>
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
                      /*function getcashrecipt(){
                        global $conn;
                        $voucher_no = '';
                        $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                        $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;
                        $cashQuery = "SELECT * FROM `cash_receipt` WHERE pharmacy_id = '".$p_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
                        $cashRes = mysqli_query($conn, $cashQuery);
                        if($cashRes){
                          $count = mysqli_num_rows($cashRes);
                          if($count !== '' && $count !== 0){
                            $row = mysqli_fetch_array($cashRes);
                            $voucherno = (isset($row['cash_receipt_no'])) ? $row['cash_receipt_no'] : '';

                            if($voucherno != ''){
                              $vouchernoarr = explode('-',$voucherno);
                              $voucherno = $vouchernoarr[1];
                              $voucherno = $voucherno + 1;
                              $voucherno = sprintf("%05d", $voucherno);
                              $voucher_no = 'CR-'.$voucherno;
                            }

                          }else{
                            $voucherno = sprintf("%05d", 1);
                            $voucher_no = 'CR-'.$voucherno;
                          }
                        }
                        return $voucher_no;
                      }*/

                      if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
                        $id = $_REQUEST['id'];
                        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                        $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;
                        $customerqry = "select * from cash_receipt WHERE id = '".$id."' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."'";
                        $customerrun = mysqli_query($conn, $customerqry);
                        $customerdata = mysqli_fetch_assoc($customerrun);
                      }
                    ?>
                    
                    <!-- First Row  -->
                    <form class="forms-sample" method="post" autocomplete="off">
                        <div class="form-group row">
                          <div class="col-12 col-md-3">
                              <label for="exampleInputName1">Cash Receipt No.<span class="text-danger">*</span></label>
                              <input readonly type="text" name="cash_receipt_no" value="<?php if(isset($_REQUEST['id'])) { echo $customerdata['cash_receipt_no']; }else{ echo getcustomerreceiptno(); }?>" class="form-control" id="exampleInputName1" placeholder="Cash Receipt No." required="">
                          </div>
                        
                  
                         <div class="col-12 col-md-9">
                              <label for="exampleInputName1" class="pull-right bg-success color-white p-2">Running Balance: <span id="running_balance">0</span></label>
                         </div>     
                        
                        </div>
                      
                        <div class="form-group row">
                        
                        
                         <!-- CHEQUE FIELDS -->
                        <div class="col-12 col-md-2">
                        <label for="exampleInputName1">Customer<span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" id="customer" name="customer" style="width:100%" required="" data-parsley-errors-container="#error-customer"> 
                            <option value="">Select</option>
                            <?php 
                              $p_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : NULL;
                              $customerQuery = "SELECT * FROM `ledger_master` WHERE group_id = '10' AND pharmacy_id = '".$p_id."' AND is_cash = 0 ORDER BY name"; 
                              $customerRes = mysqli_query($conn, $customerQuery);
                              while ($customerRow = mysqli_fetch_array($customerRes)) {
                            ?>
                            <option value="<?php echo $customerRow['id']; ?>" <?php echo (isset($customerdata['customer']) && $customerdata['customer'] == $customerRow['id']) ? 'selected' : '';?>><?php echo $customerRow['name']; ?></option>
                            <?php } ?>
                        </select>
                        <span id="error-customer"></span>
                        </div>
                        
                        <div class="col-12 col-md-2">
                        <label for="exampleInputName1">Payment Date<span class="text-danger">*</span></label>
                        <div id="" class="input-group date datepicker">
                            <input type="text" class="form-control border" name="payment_date" value="<?php if(isset($_REQUEST['id'])){echo date('d/m/Y', strtotime(str_replace('/', '-', $customerdata['payment_date']))); } else { echo date("d/m/Y"); }?>" required="" data-parsley-errors-container="#error-payment">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          <span id="error-payment"></span>
                        </div>
                        
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Payment Mode<span class="text-danger">*</span></label>
                          <select id="payment_mode" class="js-example-basic-single payment_mode" name="payment_mode" style="width:100%" required="" data-parsley-errors-container="#error-paymentmode"> 
                            <option value="">Select Any One </option>
                            <option value="cash" <?php if(isset($_REQUEST['id']) && $customerdata['payment_mode'] == "cash"){echo "selected";}?>>Cash</option>
                            <option value="cheque" <?php if(isset($_REQUEST['id']) && $customerdata['payment_mode'] == "cheque"){echo "selected";}?>>Cheque</option>
                            <option value="dd" <?php if(isset($_REQUEST['id']) && $customerdata['payment_mode'] == "dd"){echo "selected";}?>>DD</option>
                            <option value="net_banking" <?php if(isset($_REQUEST['id']) && $customerdata['payment_mode'] == "net_banking"){echo "selected";}?>>Net Banking</option>
                            <option value="credit_debit_card" <?php if(isset($_REQUEST['id']) && $customerdata['payment_mode'] == "credit_debit_card"){echo "selected";}?>>Credit/Debit Card</option>
                            <!-- <option value="on_account">On Account</option> -->
                            <option value="other" <?php if(isset($_REQUEST['id']) && $customerdata['payment_mode'] == "other"){echo "selected";}?>>Other</option>
                        </select>
                        <span id="error-paymentmode"></span>
                        </div>
                        
                        </div>
                        
                              <div class="form-group row div_cheque" <?php if(isset($_REQUEST['id']) && $customerdata['payment_mode'] == "cheque") { } else { ?> style="display: none;" <?php } ?>>
                          
                            <div class="col-12 col-md-2">
                              <label for="cheque_no">Cheque No<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="cheque_no" name="cheque_no" placeholder="Cheque No" value="<?php if(isset($_REQUEST['id'])){echo $customerdata['cheque_no']; }?>"required="">
                            </div>
                            <div class="col-12 col-md-2">
                              <label for="deposit_bank">Deposit Bank<span class="text-danger">*</span></label>
                              <select id="deposit_bank" name="deposit_bank_cheque" class="js-example-basic-single deposit_bank" style="width:100%" required="" data-parsley-errors-container="#error-cheque"> 
                                <option value="">Select Any One </option>
                                <?php
                                $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                                $bankQuery = "SELECT id, bank_name FROM pharmacy_bank_details WHERE pharmacy_id = '".$p_id."'"; 
                                $bankRes = mysqli_query($conn, $bankQuery);
                                while ($bankRow = mysqli_fetch_array($bankRes)) {
                                ?>
                                <option value="<?php echo $bankRow['id']; ?>" <?php echo (isset($customerdata['deposit_bank_cheque']) && $customerdata['deposit_bank_cheque'] == $bankRow['id']) ? 'selected' : '';?>><?php echo $bankRow['bank_name']; ?></option>
                                <?php } ?>
                              </select>
                              <span id="error-cheque"></span>
                            </div>
                            <div class="col-12 col-md-2">
                              <label for="cheque_date">Cheque Date<span class="text-danger">*</span></label>
                              <div id="" class="input-group date datepicker">
                                  <input type="text" class="form-control border" name="cheque_date" value="<?php if(isset($_REQUEST['id']) && $customerdata['payment_mode'] == "cheque") {echo date('d/m/Y', strtotime(str_replace('/', '-', $customerdata['cheque_date']))); } else { echo date("d/m/Y"); }?>" required="" data-parsley-errors-container="#error-chequedate">
                                  <span class="input-group-addon input-group-append border-left">
                                    <span class="mdi mdi-calendar input-group-text"></span>
                                  </span>
                                </div>
                                <span id="error-chequedate"></span>
                          </div>
                          
                        </div>

                        <div class="form-group row div_dd" <?php if(isset($_REQUEST['id']) && $customerdata['payment_mode'] == "dd") { } else {?>style="display: none;" <?php } ?>>
                          
                          <div class="col-12 col-md-2">
                              <label for="dd_no">DD No<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="dd_no" name="dd_no" placeholder="DD No" value="<?php if(isset($_REQUEST['id'])){echo $customerdata['dd_no']; }?>"required="">
                            </div>
                            <div class="col-12 col-md-2">
                              <label for="deposit_bank">Deposit Bank<span class="text-danger">*</span></label>
                              <select id="deposit_bank" name="deposit_bank_dd" class="js-example-basic-single deposit_bank" style="width:100%" required="" data-parsley-errors-container="#error-dd"> 
                               <option value="">Select Any One </option>
                                <?php
                                $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                                $bankQuery = "SELECT id, bank_name FROM pharmacy_bank_details WHERE pharmacy_id = '".$p_id."'"; 
                                $bankRes = mysqli_query($conn, $bankQuery);
                                while ($bankRow = mysqli_fetch_array($bankRes)) {
                                ?>
                                <option value="<?php echo $bankRow['id']; ?>" <?php echo (isset($customerdata['deposit_bank_dd']) && $customerdata['deposit_bank_dd'] == $bankRow['id']) ? 'selected' : '';?>><?php echo $bankRow['bank_name']; ?></option>
                                <?php } ?>
                            </select>
                            <span id="error-dd"></span>
                            </div>
                            <div class="col-12 col-md-2">
                              <label for="dd_date">DD Date<span class="text-danger">*</span></label>
                              <div id="" class="input-group date datepicker">
                                  <input type="text" class="form-control border" name="dd_date" value="<?php if(isset($_REQUEST['id']) && $customerdata['payment_mode'] == "dd"){ echo date('d/m/Y', strtotime(str_replace('/','-',$customerdata['dd_date']))); } else { echo date('d/m/Y'); }?>" required="" data-parsley-errors-container="#error-dddate">
                                  <span class="input-group-addon input-group-append border-left">
                                    <span class="mdi mdi-calendar input-group-text"></span>
                                  </span>
                                </div>
                                <span id="error-dddate"></span>
                          </div>
                          
                        </div>

                        <div class="form-group row div_net_banking" <?php if(isset($_REQUEST['id']) && $customerdata['payment_mode'] == "net_banking"){ } else { ?> style="display: none;" <?php } ?>>
                          <div class="col-12 col-md-2">
                              <label for="utr_number">UTR Number<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="utr_number" name="utr_number" placeholder="UTR Number" value="<?php if(isset($_REQUEST['id'])){echo $customerdata['utr_number'];}?>" required="">
                          </div>
                          
                          <div class="col-12 col-md-2">
                              <label for="deposit_bank">Deposit Bank<span class="text-danger">*</span></label>
                              <select id="deposit_bank" name="deposit_bank_net_banking" class="js-example-basic-single deposit_bank" style="width:100%" required="" data-parsley-errors-container="#error-netbanking"> 
                                <option value="">Select Any One </option>
                                <?php
                                $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                                $bankQuery = "SELECT id, bank_name FROM pharmacy_bank_details WHERE pharmacy_id = '".$p_id."'"; 
                                $bankRes = mysqli_query($conn, $bankQuery);
                                while ($bankRow = mysqli_fetch_array($bankRes)) {
                                ?>
                                <option value="<?php echo $bankRow['id']; ?>" <?php echo (isset($customerdata['deposit_bank_net_banking']) && $customerdata['deposit_bank_net_banking']) ? 'selected' : '';?>><?php echo $bankRow['bank_name']; ?></option>
                                <?php } ?>
                            </select>
                            <span id="error-netbanking"></span>
                          </div>
                        </div>

                        <div class="form-group row div_credit_debit_card" <?php if(isset($_REQUEST['id']) && $customerdata['payment_mode'] == "credit_debit_card") { } else {?>style="display: none;" <?php } ?>>
                          <div class="col-12 col-md-2">
                              <label for="card_number">Card Number<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="card_number" name="card_number" placeholder="Card Number" value="<?php if(isset($_REQUEST['id'])){echo $customerdata['card_number']; }?>"required="">
                          </div>
                          <div class="col-12 col-md-2">
                              <label for="deposit_bank">Deposit Bank<span class="text-danger">*</span></label>
                              <select id="deposit_bank" name="deposit_bank_credit_debit_card" class="js-example-basic-single deposit_bank" style="width:100%" required="" data-parsley-errors-container="#error-creditcard"> 
                               <option value="">Select Any One </option>
                                <?php
                                $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                                $bankQuery = "SELECT id, bank_name FROM pharmacy_bank_details WHERE pharmacy_id = '".$p_id."'"; 
                                $bankRes = mysqli_query($conn, $bankQuery);
                                while ($bankRow = mysqli_fetch_array($bankRes)) {
                                ?>
                                <option value="<?php echo $bankRow['id']; ?>" <?php echo (isset($customerdata['deposit_bank_credit_debit_card']) && $customerdata['deposit_bank_credit_debit_card'] == $bankRow['id']) ? 'selected' : '';?>><?php echo $bankRow['bank_name']; ?></option>
                                <?php } ?>
                              </select>
                              <span id="error-creditcard"></span>
                          </div>
                           <div class="col-12 col-md-2">
                              <label for="name_on_card">Name On Card<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="name_on_card" name="name_on_card" placeholder="Name On Card" value="<?php if(isset($_REQUEST['id'])){ echo $customerdata['name_on_card']; }?>"required="">
                          </div>
                        </div>

                        <div class="form-group row div_other" <?php if(isset($_REQUEST['id']) && $customerdata['payment_mode'] == "other") { } else {?>style="display: none;" <?php } ?>>
                          <div class="col-12 col-md-2">
                              <label for="reference">Reference<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="reference" name="reference" placeholder="Reference" value="<?php if(isset($_REQUEST['id'])){ echo $customerdata['reference']; }?>"required="">
                          </div>
                        </div>
                        
                       
                       
                       <div class="form-group row">
                       
                       <div class="col-12 col-md-2">
                        <label for="amount">Amount<span class="text-danger">*</span></label>
                        <input type="text" class="form-control onlynumber" id="amount" name="amount" placeholder="Amount" value="<?php if(isset($_REQUEST['id'])){ echo $customerdata['amount']; }?>"required="" autocomplete="off">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="remarks">Remarks</label>
                        <textarea  class="form-control" id="remarks" name="remarks" placeholder="Remarks" rows="3"><?php if(isset($_REQUEST['id'])){ echo $customerdata['remarks']; }?></textarea>
                        </div>
                        
                        
                        
                        <!--- CASH FIELD --->
                        
                        
                        <div class="col-12">  
                        <button type="submit" name="add_receipt" class="btn btn-success mt-30">Add Receipt</button>                        
                        <!--<a href="'#" class="btn btn-dark mt-30">Back</a>-->
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
                  $qry = "SELECT ledger_master.name AS name, ledger_master.id AS ledger_id, cash_receipt.id FROM ledger_master INNER JOIN cash_receipt ON ledger_master.id = cash_receipt.customer WHERE ledger_master.pharmacy_id = '".$p_id."' GROUP BY ledger_id ORDER BY cash_receipt.id DESC";
                  $run = mysqli_query($conn, $qry);
                ?>
                
                    <!-- TABLE STARTS -->
                    <div class="col mt-3">
                       <div class="row">
                            <div class="col-12">
                              <table id="order-listing1" class="table">
                                <thead>
                                  <tr>
                                      <th>Sr No.</th>
                                      <th>Customer</th>
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
                                    if($run){
                                      $count = 0;
                                      while($data = mysqli_fetch_assoc($run)){
                                        $count++;
                                        $running = countRunningBalance($data['ledger_id']);
                                  ?>  
                                  <tr>
                                    <td>
                                        <?php echo $count;?>
                                    </td>
                                    <td>
                                        <?php echo $data['name'];?>
                                    </td>
                                    <td class="text-right">
                                        <?php
                                            if($running['opening_balance'] >= 0){
                                                echo amount_format(number_format(abs($running['opening_balance']), 2, '.', '')) .' Dr';
                                            } else{
                                                echo amount_format(number_format(abs($running['opening_balance']), 2, '.', '')) .' Cr';
                                            }
                                        ?></td>
                                    <td class="text-right">
                                        <?php echo amount_format(number_format($running['total_bill'], 2, '.', '')); ?>
                                    </td>
                                    <td class="text-right">
                                        <?php 
                                            $total = $running['opening_balance'] + $running['total_bill']; 
                                            if($total >= 0){
                                                echo amount_format(number_format(abs($total), 2, '.', '')) .' Dr';
                                            } else {
                                                echo amount_format(number_format(abs($total), 2, '.', '')) .' Cr';
                                        }?>
                                    </td>
                                    <td class="text-right">
                                        <?php echo amount_format(number_format($running['total_receipt'], 2, '.', '')); ?>
                                    </td>
                                    <td class="text-right">
                                        <?php
                                            $pending =  $total - $running['total_receipt']; 
                                            if($pending >= 0){
                                                echo amount_format(number_format(abs($pending), 2, '.', '')) .' Dr';
                                            } else {
                                                echo amount_format(number_format(abs($pending), 2, '.', '')) .' Cr';
                                            }
                                        ?>
                                    </td>
                                    <td><a href="customer-ledger-print.php?id=<?php echo $data['ledger_id'];?>" target="_blank" class="btn  btn-behance p-2"><i class="fa fa-file mr-0"></i></a></td>
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
  
  
  <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
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
  <script src="js/custom/accounting-customer-receipt.js"></script>
  
  
  <!-- End custom js for this page-->
</body>


</html>
