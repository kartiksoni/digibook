<?php $title = "Quotation / Estimate / Proformo Invoice"; ?>
<?php include('include/usertypecheck.php');
include('include/permission.php');?>
<!-------------------------------------------- CODE FOR ADD AND UPDATE TAX BILLING START ----------------------------------------------->
<?php
  if(isset($_POST['submit'])){
    $tax_billing = [];
    $tax_billing['owner_id'] = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : NULL;
    $tax_billing['admin_id'] = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : NULL;
    $tax_billing['pharmacy_id'] = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
    $tax_billing['financial_id'] = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;
    $tax_billing['city_id'] = (isset($_POST['city_id'])) ? $_POST['city_id'] : NULL;
    $tax_billing['statecode'] = (isset($_POST['statecode'])) ? $_POST['statecode'] : NULL;
    $tax_billing['customer_id'] = (isset($_POST['customer_id'])) ? $_POST['customer_id'] : NULL;
    $tax_billing['invoice_date'] = (isset($_POST['invoice_date']) && $_POST['invoice_date'] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['invoice_date']))) : NULL;
    $tax_billing['c_addr_1'] = (isset($_POST['c_addr_1'])) ? $_POST['c_addr_1'] : '';
    $tax_billing['c_addr_2'] = (isset($_POST['c_addr_2'])) ? $_POST['c_addr_2'] : '';
    $tax_billing['c_addr_3'] = (isset($_POST['c_addr_3'])) ? $_POST['c_addr_3'] : '';
    $tax_billing['invoice_no'] = (isset($_POST['invoice_no'])) ? $_POST['invoice_no'] : '';
    $tax_billing['bill_type'] = (isset($_POST['bill_type'])) ? $_POST['bill_type'] : '';
    $tax_billing['alltotalamount'] = (isset($_POST['alltotalamount']) && $_POST['alltotalamount'] != '') ? $_POST['alltotalamount'] : 0;
    $tax_billing['couriercharge'] = (isset($_POST['couriercharge']) && $_POST['couriercharge'] != '') ? $_POST['couriercharge'] : 0;
    $tax_billing['couriercharge_val'] = (isset($_POST['couriercharge_val']) && $_POST['couriercharge_val'] != '') ? $_POST['couriercharge_val'] : 0;
    $tax_billing['totaltaxgst'] = (isset($_POST['totaltaxgst']) && $_POST['totaltaxgst'] != '') ? $_POST['totaltaxgst'] : 0;
    $tax_billing['totaligst'] = (isset($_POST['totaligst']) && $_POST['totaligst'] != '') ? $_POST['totaligst'] : 0;
    $tax_billing['totalcgst'] = (isset($_POST['totalcgst']) && $_POST['totalcgst'] != '') ? $_POST['totalcgst'] : 0;
    $tax_billing['totalsgst'] = (isset($_POST['totalsgst']) && $_POST['totalsgst'] != '') ? $_POST['totalsgst'] : 0;
    $tax_billing['discount_type'] = (isset($_POST['discount_type'])) ? $_POST['discount_type'] : '';
    $tax_billing['discount_per'] = (isset($_POST['discount_per']) && $_POST['discount_per'] != '') ? $_POST['discount_per'] : 0;
    $tax_billing['discount_rs'] = (isset($_POST['discount_rs']) && $_POST['discount_rs'] != '') ? $_POST['discount_rs'] : 0;
    $tax_billing['overalldiscount'] = (isset($_POST['overalldiscount']) && $_POST['overalldiscount'] != '') ? $_POST['overalldiscount'] : 0;
    $tax_billing['cr_db_type'] = (isset($_POST['cr_db_type']) && $_POST['cr_db_type'] != '') ? $_POST['cr_db_type'] : '';
    $tax_billing['cr_db_val'] = (isset($_POST['cr_db_val']) && $_POST['cr_db_val'] != '') ? $_POST['cr_db_val'] : 0;
    $tax_billing['purchase_amount'] = (isset($_POST['purchase_amount']) && $_POST['purchase_amount'] != '') ? $_POST['purchase_amount'] : 0;
    $tax_billing['roundoff_amount'] = (isset($_POST['roundoff_amount']) && $_POST['roundoff_amount'] != '') ? $_POST['roundoff_amount'] : 0;
    $tax_billing['final_amount'] = (isset($_POST['final_amount']) && $_POST['final_amount'] != '') ? $_POST['final_amount'] : 0;

    if(isset($_GET['id']) && $_GET['id']){
      $query = "UPDATE quotation SET ";
    }else{
      $query = "INSERT INTO quotation SET ";
    }

    foreach ($tax_billing as $key => $value) {
       $query .= " ".$key." = '".$value."', ";
    }

    if(isset($_GET['id']) && $_GET['id']){
        $query .= "modified = '".date('Y-m-d H:i:s')."', modifiedby = '".$_SESSION['auth']['id']."' ";
        $query .= "WHERE id = '".$_GET['id']."'";
    }else{
      $query .= "created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
    }
    $taxbillingRes = mysqli_query($conn, $query);
    if($taxbillingRes){
      $taxbillid = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : mysqli_insert_id($conn);
      if($taxbillid != ''){
        $deleteOldItemQ = "DELETE FROM quotation_details WHERE tax_bill_id = '".$taxbillid."'";
        mysqli_query($conn, $deleteOldItemQ);

        $count = (isset($_POST['product_id']) && !empty($_POST['product_id'])) ? count($_POST['product_id']) : 0;

        if($count != 0){
          for ($i=0; $i < $count; $i++) { 
            $tax_billing_details['tax_bill_id'] = $taxbillid;
            $tax_billing_details['product_id'] = (isset($_POST['product_id'][$i])) ? $_POST['product_id'][$i] : '';
            $tax_billing_details['mrp'] = (isset($_POST['mrp'][$i]) && $_POST['mrp'][$i] != '') ? $_POST['mrp'][$i] : 0;
            $tax_billing_details['mfg_co'] = (isset($_POST['mfg_co'][$i])) ? $_POST['mfg_co'][$i] : '';
            $tax_billing_details['batch'] = (isset($_POST['batch'][$i])) ? $_POST['batch'][$i] : '';
            $tax_billing_details['expiry'] = (isset($_POST['expiry'][$i]) && $_POST['expiry'][$i] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['expiry'][$i]))) : NULL;
            $tax_billing_details['qty'] = (isset($_POST['qty'][$i]) && $_POST['qty'][$i] != '') ? $_POST['qty'][$i] : 0;
            $tax_billing_details['qty_ratio'] = (isset($_POST['qty_ratio'][$i]) && $_POST['qty_ratio'][$i] != '') ? $_POST['qty_ratio'][$i] : 0;
            $tax_billing_details['freeqty'] = (isset($_POST['freeqty'][$i]) && $_POST['freeqty'][$i] != '') ? $_POST['freeqty'][$i] : 0;
            $tax_billing_details['rate'] = (isset($_POST['rate'][$i]) && $_POST['rate'][$i] != '') ? $_POST['rate'][$i] : 0;
            $tax_billing_details['discount'] = (isset($_POST['discount'][$i]) && $_POST['discount'][$i] != '') ? $_POST['discount'][$i] : 0;
            $tax_billing_details['amount'] = (isset($_POST['amount'][$i]) && $_POST['amount'][$i] != '') ? $_POST['amount'][$i] : 0;
            $tax_billing_details['gst'] = (isset($_POST['gst'][$i]) && $_POST['gst'][$i] != '') ? $_POST['gst'][$i] : 0;
            $tax_billing_details['igst'] = (isset($_POST['igst'][$i]) && $_POST['igst'][$i] != '') ? $_POST['igst'][$i] : 0;
            $tax_billing_details['cgst'] = (isset($_POST['cgst'][$i]) && $_POST['cgst'][$i] != '') ? $_POST['cgst'][$i] : 0;
            $tax_billing_details['sgst'] = (isset($_POST['sgst'][$i]) && $_POST['sgst'][$i] != '') ? $_POST['sgst'][$i] : 0;
            $tax_billing_details['totalamount'] = (isset($_POST['totalamount'][$i]) && $_POST['totalamount'][$i] != '') ? $_POST['totalamount'][$i] : 0;
            
            $item = "INSERT INTO quotation_details SET ";
            foreach ($tax_billing_details as $k => $v) {
                $item .= " ".$k." = '".$v."', ";
            }
            $item .= "created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
            mysqli_query($conn, $item);
          }
        }

      }

      if(isset($_GET['id']) && $_GET['id'] != ''){
        $_SESSION['msg']['success'] = "Quotation Updated Successfully.";
      }else{
        $_SESSION['msg']['success'] = "Quotation Added Successfully.";
      }
    header('Location: quotation.php');exit;
    }else{
      if(isset($_GET['id']) && $_GET['id'] != ''){
        $_SESSION['msg']['error'] = "Quotation Updated Fail!";
      }else{
        $_SESSION['msg']['error'] = "Quotation Added Fail!";
      }
    }

    echo $query;exit;

  }
?>
<!-------------------------------------------- CODE FOR ADD AND UPDATE TAX BILLING END ----------------------------------------------->

<!-------------------------------------------- CODE FOR GET NEW INVOICE NUMBER START ----------------------------------------------->
<?php 
  function getInvoiceNo(){
    global $conn;
    $invoice_no = '';
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
    $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;
    $getInvoiceNoQ = "SELECT invoice_no FROM quotation WHERE bill_type = 'Cash' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
    $getInvoiceNoR = mysqli_query($conn, $getInvoiceNoQ);
    if($getInvoiceNoR){
      $countInvoice = mysqli_num_rows($getInvoiceNoR);
      if($countInvoice !== '' && $countInvoice !== 0){
        $row = mysqli_fetch_array($getInvoiceNoR);
        $invoice_no = (isset($row['invoice_no'])) ? $row['invoice_no'] : '';

        $invoice_noarr = explode('-',$invoice_no);
        $invoice_no = $invoice_noarr[1];
        $invoice_no = $invoice_no + 1;
        $invoice_no = sprintf("%05d", $invoice_no);
        $invoice_no = 'C-'.$invoice_no;
      }else{
        $invoice_no = sprintf("%05d", 1);
        $invoice_no = 'C-'.$invoice_no;
      }
    }
    return $invoice_no;
  }
?>
<!-------------------------------------------- CODE FOR GET NEW INVOICE NUMBER END ----------------------------------------------->

<!-------------------------------------------- CODE FOR EDIT ID BY GET ALL DATA START ----------------------------------------------->
<?php 
    if(isset($_GET['id']) && $_GET['id'] != ''){
      $id = $_REQUEST['id'];
      $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
      $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;
      $editQ = "SELECT * FROM quotation WHERE id = '".$id."' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."'";
      $editR = mysqli_query($conn, $editQ);
      if($editR && mysqli_num_rows($editR) > 0){
        $editdata = mysqli_fetch_array($editR);
      }
    }
?>
<!-------------------------------------------- CODE FOR EDIT ID BY GET ALL DATA END ----------------------------------------------->

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
    <?php include "include/topbar.php"; ?>
    
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
           <!-- Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <!-- Main Catagory -->
                    <div class="row">
                      <div class="col-12">
                          <div class="purchase-top-btns">
                             <?php 
                            if(isset($user_sub_module) && in_array("Cash Management", $user_sub_module)){ 
                            ?>
                            <a href="accounting-cash-management.php" class="btn btn-dark active">Cash Management</a>
                            <?php } 
                            if(isset($user_sub_module) && in_array("Customer Receipt", $user_sub_module)){ 
                            ?>
                            <a href="accounting-customer-receipt.php" class="btn btn-dark btn-fw">Customer Receipt</a>
                            <?php } 
                            if(isset($user_sub_module) && in_array("Cheque", $user_sub_module)){ 
                            ?>
                            <a href="accounting-cheque.php" class="btn btn-dark  btn-fw">Cheque</a>
                            <?php } 
                            if(isset($user_sub_module) && in_array("Vendor Payment", $user_sub_module)){ 
                            ?>
                            <a href="accounting-vendor-payments.php" class="btn btn-dark  btn-fw">Vendor Payment</a>
                            <?php } 
                            if(isset($user_sub_module) && in_array("Financial Year Settings", $user_sub_module)){ 
                            ?>
                            <a href="financial-year.php" class="btn btn-dark  btn-fw">Financial Year Settings</a>
                            <?php } 
                            if(isset($user_sub_module) && in_array("Credit Note / Purchase Note", $user_sub_module)){ 
                            ?>
                            <a href="purchase-return.php" class="btn btn-dark  btn-fw">Credit Note / Purchase Note</a>
                            <?php } 
                            if(isset($user_sub_module) && in_array("Quotation / Estimate / Proformo Invoice", $user_sub_module)){ 
                            ?>
                            <a href="quotation.php" class="btn btn-dark  btn-fw">Quotation / Estimate / Proformo Invoice</a>
                            <?php } ?>
                            <a href="journal-vouchar.php" class="btn btn-dark  btn-fw">Journal Vouchar</a>
                          </div>   
                      </div> 
                    </div>
                </div>
              </div>
            </div>
            <!-- Form -->
            <form method="POST" autocomplete="off">
                <!--Chnages By Kartik-->
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <br>
                      <div class="form-group row">
                        
                        <div class="col-12 col-md-2">
                          <label for="customer_id">Select Customer <span class="text-danger">*</span></label>
                          <input class="form-control" autocomplete="off" type="text" value="<?php echo (isset($editdata['customer_name'])) ? $editdata['customer_name'] : '' ?>" name="customer_name" id="customer_name" required data-parsley-errors-container="#error-customer_id">
                          <small class="customererror text-danger"></small>
                          <span id="error-customer_id"></span>
                          <input type="hidden" name="statecode" id="statecode" value="<?php echo (isset($editdata['statecode'])) ? $editdata['statecode'] : ''; ?>">
                          <input type="hidden" name="cur_statecode" id="cur_statecode" value="24" >
                          <input type="hidden" name="customer_id" id="customer_id" value="<?php echo (isset($editdata['customer_id'])) ? $editdata['customer_id'] : '' //getcustomerID(); ?>" >
                        </div>

                        <div class="col-12 col-md-2">
                          <label for="customer_id">Customer ID</label>
                          <input type="text" readonly class="form-control" name="customer_u_id" id="customer_u_id" placeholder="Customer ID" value="<?php echo (isset($editdata['customer_u_id'])) ? $editdata['customer_u_id'] : '' //getcustomerID(); ?>">
                        </div>

                        <div class="col-12 col-md-2">
                          <label for="customer_mobile">Customer Mobile</label>
                          <input type="text" readonly class="form-control" name="customer_mobile" id="customer_mobile" placeholder="Customer Mobile" value="<?php echo (isset($editdata['customer_mobile'])) ? $editdata['customer_mobile'] : ''; ?>">
                        </div>

                        <div class="col-12 col-md-2">
                          <label for="doctor">Doctor</label>
                          <select class="js-example-basic-single" style="width:100%" name="doctor" id="doctor">
                            <option value="">Select Doctor</option>
                            <?php
                              $allDoctor = [];
                              $doctorQ = "SELECT id, name FROM doctor_profile WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 ORDER BY name";
                              $docrotR = mysqli_query($conn, $doctorQ);
                              if($docrotR && mysqli_num_rows($docrotR) > 0){
                                while ($doctorRow = mysqli_fetch_array($docrotR)) {
                                  $dtr['id'] = (isset($doctorRow['id'])) ? $doctorRow['id'] : '';
                                  $dtr['name'] = (isset($doctorRow['name'])) ? $doctorRow['name'] : '';
                                  $allDoctor[] = $dtr;
                                }
                              }
                            ?>
                            <?php 
                              if(isset($allDoctor) && !empty($allDoctor)){
                                foreach ($allDoctor as $key => $value) {
                            ?>
                              <option value="<?php echo $value['id'] ?>" <?php echo (isset($editdata['doctor']) && $editdata['doctor'] == $value['id']) ? 'selected' : ''; ?> ><?php echo $value['name']; ?></option>
                            <?php 
                                }
                              }
                            ?>
                          </select>
                        </div>
                        <?php
                        if(isset($editdata['doctor']) && $_GET['id']){
                            $doctorQ = "SELECT mobile FROM doctor_profile WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 AND id='".$editdata['doctor']."'";
                            $docrotR = mysqli_query($conn, $doctorQ);
                            $doctorRow = mysqli_fetch_array($docrotR);
                        }
                        ?>

                        <div class="col-12 col-md-2">
                          <label for="doctor_mobile">Doctor Mobile</label>
                          <input type="text" class="form-control" name="doctor_mobile" id="doctor_mobile" placeholder="Doctor Mobile" value="<?php echo (isset($doctorRow['mobile'])) ? $doctorRow['mobile'] : ''; ?>">
                        </div>

                        <div class="col-12 col-md-2">
                           <a href="javascript:void(0);" data-toggle="modal" data-target="#add_doctor_model" class="btn btn-primary btn-xs mt-30">Add Doctor</a>
                        </div>

                      </div>

                      <div class="form-group row">

                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Invoice Date <span class="text-danger">*</span></label>
                          <div class="input-group date datepicker">
                            <?php 
                              if(isset($_GET['id']) && $_GET['id'] != ''){
                                $invoicedate = (isset($editdata['invoice_date']) && $editdata['invoice_date'] != '') ? date('d/m/Y', strtotime($editdata['invoice_date'])) : '';
                              }else{
                                $invoicedate = date('d/m/Y');
                              }
                            ?>
                            <input type="text" class="form-control border" name="invoice_date" autocomplete="off" value="<?php echo (isset($invoicedate)) ? $invoicedate : ''; ?>" required data-parsley-errors-container="#error-date">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          <span id="error-date"></span>
                        </div>
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Invoice No <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" name="invoice_no" id="invoice_no" placeholder="Invoice No" value="<?php echo (isset($editdata['invoice_no'])) ? $editdata['invoice_no'] : getInvoiceNo(); ?>" required>
                        </div>
                        
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Bill Type</label>
                          <div class="row no-gutters">
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <?php 
                                      if(isset($editdata['bill_type'])){
                                        $cashchecked = ($editdata['bill_type'] == 'Cash') ? 'checked' : '';
                                      }else{
                                        $cashchecked = 'checked';
                                      }
                                    ?>
                                    <input type="radio" class="form-check-input bill_type" name="bill_type" value="Cash" <?php echo (isset($cashchecked)) ? $cashchecked : ''; ?> >
                                     CASH
                                  </label>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input bill_type" name="bill_type" value="Debit" <?php echo (isset($editdata['bill_type']) && $editdata['bill_type'] == 'Debit') ? 'checked' : ''; ?> >
                                    DEBIT
                                </label>
                                </div>
                            </div>
                          </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                          <a href="#" class="btn btn-primary btn-xs mt-30" data-toggle="modal" data-target="#add_customer_model" data-whatever="@mdo">Add Customer</a>
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
                                  <th>Sr No</th>
                                  <th>Product</th>
                                  <th>MRP</th>
                                  <th>MFG. Co.</th>
                                  <th>Batch</th>
                                  <th>Expiry</th>
                                  <th>Qty.</th>
                                  <th>Free Qty</th>
                                  <th>PTR</th>
                                  <th>Discount</th>
                                  <th>Rate</th>
                                  <!-- <th class="display-none">Amount</th> -->
                                  <th>GST</th>
                                  <th>Amount</th>
                                  <th>&nbsp;</th>
                              </tr>
                            </thead>
                            <tbody id="item-tbody">
                              <!-- Row Starts -->
                              <?php 
                                if(isset($_GET['id']) && $_GET['id'] != ''){
                                  $billing_item_Q = "SELECT tbd.*, pm.product_name FROM quotation_details tbd LEFT JOIN product_master pm ON tbd.product_id = pm.id WHERE tbd.tax_bill_id = '".$_GET['id']."'";
                                  $billing_item_R = mysqli_query($conn, $billing_item_Q);
                                }
                              ?>
                              <?php if(isset($billing_item_R) && $billing_item_R && mysqli_num_rows($billing_item_R) > 0){ ?>
                                <?php
                                  $itemcount = 1; 
                                  while($rowitem = mysqli_fetch_array($billing_item_R)) { 
                                ?>
                                  <tr>
                                    <td><?php echo $itemcount; ?></td>
                                    <td>
                                      <input type="text" name="product[]" class="form-control product" placeholder="Product" value="<?php echo (isset($rowitem['product_name'])) ? $rowitem['product_name'] : ''; ?>" required>
                                      <small class="producterror text-danger"></small>
                                      <input type="hidden" class="product_id" name="product_id[]" value="<?php echo (isset($rowitem['product_id'])) ? $rowitem['product_id'] : ''; ?>"></td>
                                    <td><input type="text" name="mrp[]" class="form-control mrp onlynumber" placeholder="MRP" value="<?php echo (isset($rowitem['mrp'])) ?$rowitem['mrp'] : ''; ?>"></td>
                                    <td><input type="text" name="mfg_co[]" class="form-control mfg" placeholder="MFG. Co." value="<?php echo($rowitem['mfg_co']) ? $rowitem['mfg_co'] : ''; ?>"></td>
                                    <td><input type="text" name="batch[]" class="form-control batch" placeholder="Batch" value="<?php echo (isset($rowitem['batch'])) ? $rowitem['batch'] : ''; ?>"></td>
                                    <td><input type="text" name="expiry[]" class="form-control expiry datepicker" placeholder="Expiry" value="<?php echo (isset($rowitem['expiry']) && $rowitem['expiry'] != '') ? date('d/m/Y',strtotime($rowitem['expiry'])) : ''; ?>"></td>
                                    <td>
                                      <input type="text" name="qty[]" class="form-control qty onlynumber" placeholder="Qty." value="<?php echo (isset($rowitem['qty'])) ? $rowitem['qty'] : ''; ?>">
                                      <input type="hidden" name="qty_ratio[]" class="qty_ratio" value="<?php echo (isset($rowitem['qty_ratio'])) ? $rowitem['qty_ratio'] : 0; ?>">
                                    </td>
                                    <td><input type="text" name="freeqty[]" class="form-control freeqty onlynumber" placeholder="Free Qty" value="<?php echo (isset($rowitem['freeqty'])) ? $rowitem['freeqty'] : ''; ?>"></td>
                                    <td><input type="text" name="rate[]" class="form-control rate onlynumber" placeholder="Rate" value="<?php echo (isset($rowitem['rate'])) ? $rowitem['rate'] : ''; ?>" required></td>
                                    <td><input type="text" name="discount[]" class="form-control discount onlynumber" placeholder="Discount(RS)" value="<?php echo (isset($rowitem['discount'])) ? $rowitem['discount'] : ''; ?>"></td>
                                    <td><input type="text" name="amount[]" class="form-control amount onlynumber" placeholder="Amount" value="<?php echo (isset($rowitem['amount'])) ? $rowitem['amount'] : ''; ?>" readonly></td>
                                    <td>
                                      <input type="text" name="gst[]" class="form-control gst onlynumber" placeholder="GST(%)" value="<?php echo (isset($rowitem['gst'])) ? $rowitem['gst'] : ''; ?>">
                                      <input type="hidden" name="igst[]" class="c_igst" value="<?php echo (isset($rowitem['igst'])) ? $rowitem['igst'] : 0; ?>">
                                      <input type="hidden" name="cgst[]" class="c_cgst" value="<?php echo (isset($rowitem['cgst'])) ? $rowitem['cgst'] : 0; ?>">
                                      <input type="hidden" name="sgst[]" class="c_sgst" value="<?php echo (isset($rowitem['sgst'])) ? $rowitem['sgst'] : 0; ?>">
                                    </td>
                                    <td><input type="text" name="totalamount[]" class="form-control totalamount onlynumber" placeholder="0.00" value="<?php echo (isset($rowitem['totalamount'])) ? $rowitem['totalamount'] : ''; ?>" readonly></td>
                                    <td>
                                      <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item"><i class="fa fa-plus mr-0 ml-0"></i></button>
                                    </td>
                                  </tr>
                                <?php $itemcount++; } ?>
                              <?php }else{ ?>
                                <tr>
                                  <td>1</td>
                                  <td>
                                    <input type="text" name="product[]" class="form-control product" placeholder="Product" required>
                                    <small class="producterror text-danger"></small>
                                    <input type="hidden" class="product_id" name="product_id[]"></td>
                                  <td><input type="text" name="mrp[]" class="form-control mrp onlynumber" placeholder="MRP"></td>
                                  <td><input type="text" name="mfg_co[]" class="form-control mfg" placeholder="MFG. Co."></td>
                                  <td><input type="text" name="batch[]" class="form-control batch" placeholder="Batch"></td>
                                  <td><input type="text" name="expiry[]" class="form-control expiry datepicker" placeholder="Expiry"></td>
                                  <td>
                                    <input type="text" name="qty[]" class="form-control qty onlynumber" placeholder="Qty.">
                                    <input type="hidden" name="qty_ratio[]" class="qty_ratio" value="0">
                                  </td>
                                  <td><input type="text" name="freeqty[]" class="form-control freeqty onlynumber" placeholder="Free Qty"></td>
                                  <td><input type="text" readonly name="ptr[]" class="form-control ptr" placeholder="PTR"></td>
                                  <td><input type="text" name="discount[]" class="form-control discount onlynumber" placeholder="Discount(RS)"></td>
                                  
                                  <td><input type="text" name="rate[]" class="form-control rate onlynumber" placeholder="Rate" required></td>
                                 <!--  <td class="display-none"><input type="text" name="amount[]" class="form-control amount onlynumber" placeholder="Amount" readonly></td> -->
                                  <td>
                                    <input type="text" name="gst[]" class="form-control gst onlynumber" placeholder="GST(%)">
                                    <input type="hidden" name="igst[]" class="c_igst" value="0">
                                    <input type="hidden" name="cgst[]" class="c_cgst" value="0">
                                    <input type="hidden" name="sgst[]" class="c_sgst" value="0">
                                  </td>
                                  <td><input type="text" name="totalamount[]" class="form-control totalamount onlynumber" placeholder="0.00" readonly></td>
                                  <td>
                                    <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item"><i class="fa fa-plus mr-0 ml-0"></i></button>
                                  </td>
                                </tr>
                            <?php } ?>
                              <!-- End Row -->  
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="col-12">
                      <div class="row">
                        <div class="offset-md-8 col-md-4">
                          <div class="form-group row">
                            <table class="table table-striped" width="100%">
                              <tbody>
                              
                                <tr>
                                  <td align="right" style="width:100px;">
                                    Total
                                  </td>
                                  <td align="right">
                                    <input type="text" name="alltotalamount" id="alltotalamount" class="form-control onlynumber" placeholder="Total Amount" value="<?php echo (isset($editdata['alltotalamount'])) ? $editdata['alltotalamount'] : ''; ?>" readonly>
                                  </td>
                                </tr>

                                <tr>
                                  <td align="right">
                                    <select class="form-control" id="couriercharge" name="couriercharge" style="width:160px;">
                                          <option value="">Freight/Courier Charge </option>
                                          <option value="5" <?php echo (isset($editdata['couriercharge']) && $editdata['couriercharge'] == 5) ? 'selected' : ''; ?> >5%</option>
                                          <option value="12" <?php echo (isset($editdata['couriercharge']) && $editdata['couriercharge'] == 12) ? 'selected' : ''; ?> >12%</option>
                                          <option value="18" <?php echo (isset($editdata['couriercharge']) && $editdata['couriercharge'] == 18) ? 'selected' : ''; ?> >18%</option>
                                      </select>
                                  </td>
                                  <td align="right">
                                    <input type="text" name="couriercharge_val" id="courier" class="form-control onlynumber" placeholder="Freight/Courier Charge" value="<?php echo (isset($editdata['couriercharge_val'])) ? $editdata['couriercharge_val'] : ''; ?>" readonly>
                                  </td>
                                </tr>

                                <tr>
                                  <td align="right">
                                    Total Tax (GST)
                                  </td>
                                  <td align="right">
                                    <input type="text" name="totaltaxgst" id="totaltaxgst" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editdata['totaltaxgst'])) ? $editdata['totaltaxgst'] : ''; ?>" readonly>
                                  </td>
                                </tr>
                                
                                
                                <tr>
                                  <td align="right">
                                    IGST
                                  </td>
                                  <td align="right">
                                    <input type="text" name="totaligst" id="totaligst" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editdata['totaligst'])) ? $editdata['totaligst'] : ''; ?>" readonly>
                                  </td>
                                </tr>
                                
                                <tr>
                                  <td align="right">
                                    CGST
                                  </td>
                                  <td align="right">
                                    <input type="text" name="totalcgst" id="totalcgst" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editdata['totalcgst'])) ? $editdata['totalcgst'] : ''; ?>" readonly>
                                  </td>
                                </tr>
                                
                                <tr>
                                  <td align="right">
                                    SGST
                                  </td>
                                  <td align="right">
                                    <input type="text" name="totalsgst" id="totalsgst" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editdata['totalsgst'])) ? $editdata['totalsgst'] : ''; ?>" readonly>
                                  </td>
                                </tr>
                                
                                <tr >
                                  <td align="right">
                                    Discount
                                  </td>
                                  <td align="right" width="158px">
                                      <div class="radio-inline">
                                           <div class="icheck" style="display:inline-block">
                                              <input tabindex="7" type="radio" class="discount_type" name="discount_type" value="per" <?php echo (isset($editdata['discount_type']) && $editdata['discount_type'] == 'per') ? 'checked' : ''; ?> >
                                              <label for="minimal-radio-1" class="mt-0" ></label>
                                            </div>
                                            <input type="text" class="form-control" name="discount_per" id="discount_per" placeholder="%" value="<?php echo (isset($editdata['discount_per']) && (isset($editdata['discount_type']) && $editdata['discount_type'] == 'per')) ? $editdata['discount_per'] : ''; ?>" style="display:inline-block;width:80px;">
                                      </div>
                                      
                                                  
                                      <div class="radio-inline ml-2">            
                                          <div class="icheck" style="display:inline-block">
                                              <input tabindex="8" type="radio" class="discount_type" name="discount_type" value="rs" <?php echo (isset($editdata['discount_type']) && $editdata['discount_type'] == 'rs') ? 'checked' : ''; ?> <?php echo (!isset($_GET['id'])) ? 'checked' : ''; ?>>
                                              <label for="minimal-radio-2" class="mt-0"></label>
                                          </div>
                                          <input type="text" class="form-control" name="discount_rs" id="discount_rs" placeholder="Rs" value="<?php echo (isset($editdata['discount_rs']) && (isset($editdata['discount_type']) && $editdata['discount_type'] == 'rs')) ? $editdata['discount_rs'] : ''; ?>" style="display:inline-block;width:80px;">
                                      </div>
                                        
                      
                                              
                                  </td>
                                </tr>
                                
                                
                                <tr>
                                  <td align="right">
                                   Overall Dis. Value
                                  </td>
                                  <td align="right">
                                    <input type="text" name="overalldiscount" id="overalldiscount" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editdata['overalldiscount'])) ? $editdata['overalldiscount'] : ''; ?>" readonly>
                                  </td>
                                </tr>
                                
                                <tr>
                                  <td align="right">
                                    <select class="form-control" name="cr_db_type" id="cr_db_type" style="width:160px;">
                                          <option value="credit" <?php echo (isset($editdata['cr_db_type']) && $editdata['cr_db_type'] == 'credit') ? 'checked' : ''; ?> >Credit Note</option>
                                          <option value="debit" <?php echo (isset($editdata['cr_db_type']) && $editdata['cr_db_type'] == 'debit') ? 'checked' : ''; ?> >Debit Note</option>
                                      </select>
                                  </td>
                                  <td align="right">
                                    <input type="text" name="cr_db_val" id="cr_db_val" class="form-control onlynumber" value="<?php echo (isset($editdata['cr_db_val'])) ? $editdata['cr_db_val'] : ''; ?>" placeholder="RS">
                                  </td>
                                </tr>
                                
                                <tr style="background:#ececec;">
                                  <td align="right">
                                    Purchase Ammount
                                  </td>
                                  <td align="right">
                                    <input type="text" name="purchase_amount" id="purchase_amount" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editdata['purchase_amount'])) ? $editdata['purchase_amount'] : ''; ?>" readonly>
                                  </td>
                                </tr>
                                
                                <tr style="background:#e0e0e0;">
                                  <td align="right">
                                    Round off
                                  </td>
                                  <td align="right">
                                    <input type="text" name="roundoff_amount" id="roundoff_amount" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editdata['roundoff_amount'])) ? $editdata['roundoff_amount'] : ''; ?>" readonly>
                                  </td>
                                </tr>
                                
                                <tr style="background:#0062ab;color:#fff;">
                                  <td align="right">
                                    <strong>NET VALUE (<i class="fa fa-rupee"></i>)</strong>
                                  </td>
                                  <td align="right">
                                   <strong><input type="text" name="final_amount" id="final_amount" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editdata['final_amount'])) ? $editdata['final_amount'] : ''; ?>" readonly></strong>
                                  </td>
                                </tr>
                                
                              </tbody>
                            </table>
                          </div>
                        </div>

                      </div>
                    </div>
                    <div class="col-12" style="padding: 0px;">
                      <div class="row">
                        <div class="col-md-12">
                          <a href="view-quotation.php" class="btn btn-light pull-left">Back</a>
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
        <?php include "popup/add-customer-model.php"?>
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  
<!-- -------------------------------------------HIDDEN TR START----------------------------------------------------- -->
  <div id="hiddenItemHtml" style="display: none;">
    <table>
      <tr>
        <td>##SRNO##</td>
        <td>
          <input type="text" name="product[]" class="form-control product" placeholder="Product" required>
          <small class="producterror text-danger"></small>
          <input type="hidden" class="product_id" name="product_id[]"></td>
        <td><input type="text" name="mrp[]" class="form-control mrp onlynumber" placeholder="MRP"></td>
        <td><input type="text" name="mfg_co[]" class="form-control mfg" placeholder="MFG. Co."></td>
        <td><input type="text" name="batch[]" class="form-control batch" placeholder="Batch"></td>
        <td><input type="text" name="expiry[]" class="form-control expiry datepicker" placeholder="Expiry"></td>
        <td>
          <input type="text" name="qty[]" class="form-control qty onlynumber" placeholder="Qty.">
          <input type="hidden" name="qty_ratio[]" class="qty_ratio" value="0">
        </td>
        <td><input type="text" name="freeqty[]" class="form-control freeqty onlynumber" placeholder="Free Qty"></td>
        <td><input type="text" readonly name="ptr[]" class="form-control ptr" placeholder="PTR"></td>
        <td><input type="text" name="discount[]" class="form-control discount onlynumber" placeholder="Discount(RS)"></td>
        <td><input type="text" name="rate[]" class="form-control rate onlynumber" placeholder="Rate" required></td>
        <!-- <td class="display-none"><input type="text" name="amount[]" class="form-control amount onlynumber" placeholder="Amount" readonly></td> -->
        <td>
          <input type="text" name="gst[]" class="form-control gst onlynumber" placeholder="GST(%)">
          <input type="hidden" name="igst[]" class="c_igst" value="0">
          <input type="hidden" name="cgst[]" class="c_cgst" value="0">
          <input type="hidden" name="sgst[]" class="c_sgst" value="0">
        </td>
        <td><input type="text" name="totalamount[]" class="form-control totalamount onlynumber" placeholder="0.00" readonly></td>
        <td>
          <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item"><i class="fa fa-plus mr-0 ml-0"></i></button>
          <button type="button" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-item" style=""><i class="fa fa-close mr-0 ml-0"></i></button>
        </td>
    </tr>
    </table>
  </div>
<!-- -------------------------------------------HIDDEN TR END----------------------------------------------------- -->
  
  
  

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


<script src="js/custom/quotation.js"></script>
<script src="js/custom/add-customer-popup.js"></script>
<script src="js/custom/onlynumber.js"></script>

<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>

 
 <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <!-- End custom js for this page-->
</body>


</html>
