<?php $title="Bill Of Supply"; 
 include('include/usertypecheck.php');
 //include('include/permission.php');
 
  $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
?>

<?php

    if(isset($_POST['save']) || isset($_POST['saveAndNext'])){
        
        $bill_of_supply['customer_id'] = (isset($_POST['customer_id'])) ? $_POST['customer_id'] : '';
        $bill_of_supply['customer_name'] = (isset($_POST['customer_name'])) ? $_POST['customer_name'] : '';
        $bill_of_supply['statecode'] = (isset($_POST['statecode'])) ? $_POST['statecode'] : '';
        $bill_of_supply['city_id'] = (isset($_POST['customer_city'])) ? $_POST['customer_city'] : '';
        $bill_of_supply['invoice_date'] = (isset($_POST['invoice_date']) && $_POST['invoice_date'] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['invoice_date']))) : '';
        $bill_of_supply['bill_type'] = (isset($_POST['bill_type'])) ? $_POST['bill_type'] : '';
        if(isset($_GET['id']) && $_GET['id'] != ''){
            $bill_of_supply['invoice_no'] = (isset($_POST['invoice_no'])) ? $_POST['invoice_no'] : '';
        }else{
            $bill_of_supply['invoice_no'] = getInvoiceNoForBillOfSupply($bill_of_supply['bill_type']);
        }
        $bill_of_supply['discount_type'] = (isset($_POST['discount_type'])) ? $_POST['discount_type'] : '';
        $bill_of_supply['discount_amount'] = (isset($_POST['discount_amount']) && $_POST['discount_amount'] != '') ? $_POST['discount_amount'] : 0;
        $bill_of_supply['overalldiscount'] = (isset($_POST['overalldiscount']) && $_POST['overalldiscount'] != '') ? $_POST['overalldiscount'] : 0;
        $bill_of_supply['total_amount'] = (isset($_POST['total_amount']) && $_POST['total_amount'] != '') ? $_POST['total_amount'] : 0;
        $bill_of_supply['roundoff_amount'] = (isset($_POST['roundoff_amount']) && $_POST['roundoff_amount'] != '') ? $_POST['roundoff_amount'] : 0;
        $bill_of_supply['final_amount'] = (isset($_POST['final_amount']) && $_POST['final_amount'] != '') ? $_POST['final_amount'] : 0;
        $bill_of_supply['remarks'] = (isset($_POST['remarks'])) ? $_POST['remarks'] : '';
        $bill_of_supply['is_general'] = (isset($_POST['customer_id']) && $_POST['customer_id'] != '') ? 0 : 1;
        
        if(isset($_GET['id']) && $_GET['id']){
            $query = "UPDATE bill_of_supply SET";
            $successMsg = "Bill Update Successfully.";
            $failMsg = "Bill Update Fail! Try Again.";
        }else{
            $query = "INSERT INTO bill_of_supply SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."',";
            $successMsg = "Bill Add Successfully.";
            $failMsg = "Bill Add Fail! Try Again.";
        }
        foreach ($bill_of_supply as $key => $value) {
           $query .= " ".$key." = '".$value."', ";
        }
        if(isset($_GET['id']) && $_GET['id']){
            $query .= "modified = '".date('Y-m-d H:i:s')."', modifiedby = '".$_SESSION['auth']['id']."' ";
            $query .= "WHERE id = '".$_GET['id']."'";
        }else{
          $query .= "created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
        }
        $res = mysqli_query($conn, $query);
        if($res){
            $bos_id = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : mysqli_insert_id($conn);
            if($bos_id != ''){
             
                //  ADD BOS PRODUCT DETAIL
                $deleteOldProduct = "DELETE FROM bos_product_detail WHERE bos_id = '".$bos_id."'";
                mysqli_query($conn, $deleteOldProduct);
                
                $countProduct = (isset($_POST['product_id']) && !empty($_POST['product_id'])) ? count($_POST['product_id']) : 0;
                if($countProduct != 0){
                    for ($i=0; $i < $countProduct; $i++) {
                        $bos_product['bos_id'] = $bos_id;
                        $bos_product['product_id'] = (isset($_POST['product_id'][$i])) ? $_POST['product_id'][$i] : '';
                        $bos_product['mrp'] = (isset($_POST['mrp'][$i]) && $_POST['mrp'][$i] != '') ? $_POST['mrp'][$i] : 0;
                        $bos_product['mfg_co'] = (isset($_POST['mfg_co'][$i])) ? $_POST['mfg_co'][$i] : '';
                        $bos_product['batch'] = (isset($_POST['batch'][$i])) ? $_POST['batch'][$i] : '';
                        $bos_product['qty'] = (isset($_POST['qty'][$i]) && $_POST['qty'][$i] != '') ? $_POST['qty'][$i] : 0;
                        $bos_product['qty_ratio'] = (isset($_POST['qty_ratio'][$i]) && $_POST['qty_ratio'][$i] != '') ? $_POST['qty_ratio'][$i] : 0;
                        $bos_product['freeqty'] = (isset($_POST['freeqty'][$i]) && $_POST['freeqty'][$i] != '') ? $_POST['freeqty'][$i] : 0;
                        $bos_product['rate'] = (isset($_POST['rate'][$i]) && $_POST['rate'][$i] != '') ? $_POST['rate'][$i] : 0;
                        $bos_product['discount'] = (isset($_POST['discount'][$i]) && $_POST['discount'][$i] != '') ? $_POST['discount'][$i] : 0;
                        $bos_product['ptr'] = (isset($_POST['ptr'][$i]) && $_POST['ptr'][$i] != '') ? $_POST['ptr'][$i] : 0;
                        $bos_product['totalamount'] = (isset($_POST['totalamount'][$i]) && $_POST['totalamount'][$i] != '') ? $_POST['totalamount'][$i] : 0;
                        
                        $bos_product_q = "INSERT INTO bos_product_detail SET ";
                        foreach ($bos_product as $k => $v) {
                            $bos_product_q .= " ".$k." = '".$v."', ";
                        }
                        $bos_product_q .= "created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
                        mysqli_query($conn, $bos_product_q);
                    }
                }
                
                //  ADD BOS SERVICE DETAIL
                /*$deleteOldService = "DELETE FROM bos_service_detail WHERE bos_id = '".$bos_id."'";
                mysqli_query($conn, $deleteOldService);
                
                $countService = (isset($_POST['s_service_id']) && !empty($_POST['s_service_id'])) ? count($_POST['s_service_id']) : 0;
                if($countService != 0){
                    for ($i=0; $i < $countService; $i++) {
                        $bos_service['bos_id'] = $bos_id;
                        $bos_service['service_id'] = (isset($_POST['s_service_id'][$i])) ? $_POST['s_service_id'][$i] : '';
                        $bos_service['hsn_code'] = (isset($_POST['s_hsn_code'][$i])) ? $_POST['s_hsn_code'][$i] : '';
                        $bos_service['amount'] = (isset($_POST['s_amount'][$i]) && $_POST['s_amount'][$i] != '') ? $_POST['s_amount'][$i] : 0;
                        $bos_service['discount'] = (isset($_POST['s_less_discount'][$i]) && $_POST['s_less_discount'][$i] != '') ? $_POST['s_less_discount'][$i] : 0;
                        $bos_service['total_amount'] = (isset($_POST['s_total_amount'][$i]) && $_POST['s_total_amount'][$i] != '') ? $_POST['s_total_amount'][$i] : 0;
                        
                        $bos_service_q = "INSERT INTO bos_service_detail SET ";
                        foreach ($bos_service as $k => $v) {
                            $bos_service_q .= " ".$k." = '".$v."', ";
                        }
                        $bos_service_q .= "created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
                        mysqli_query($conn, $bos_service_q);
                    }
                }*/
            }
            $_SESSION['msg']['success'] = $successMsg;
        }else{
            $_SESSION['msg']['fail'] = $failMsg;
        }
        
        if(isset($_POST['save'])){
              header('Location: view-bill-of-supply.php');exit;
        }elseif(isset($_POST['saveAndNext'])){
              header('Location: bill-of-supply.php');exit;
        }else{
              header('Location: bill-of-supply.php');exit;
        }
    }

?>
<?php 
    // CODE FOR GET DATA FOR EDIT RECORD
    if(isset($_GET['id']) && $_GET['id'] != ''){
        $editData = [];
            $query = "SELECT bos.*, lg.name as lgr_customer_name, lg.city as lgr_customer_city, lg.salesman_id as lgr_salesman_id, lg.rate_id as lgr_rate_id FROM bill_of_supply bos LEFT JOIN ledger_master lg ON bos.customer_id = lg.id WHERE bos.id = '".$_GET['id']."' AND bos.pharmacy_id = '".$pharmacy_id."'";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
                $tmpeditData = mysqli_fetch_assoc($res);
                $tmpeditData['lgr_customer_name'] = (isset($tmpeditData['is_general']) && $tmpeditData['is_general'] == 1) ? $tmpeditData['customer_name'] : $tmpeditData['lgr_customer_name'];
                $tmpeditData['lgr_customer_city'] = (isset($tmpeditData['is_general']) && $tmpeditData['is_general'] == 1) ? $tmpeditData['city_id'] : $tmpeditData['lgr_customer_city'];
                $editData = $tmpeditData;
                
                $bos_product_query = "SELECT bpd.*, pm.product_name FROM bos_product_detail bpd LEFT JOIN product_master pm ON bpd.product_id = pm.id WHERE bpd.bos_id = '".$editData['id']."' ORDER BY bpd.id";
                $bos_product_res = mysqli_query($conn, $bos_product_query);
                if($bos_product_res && mysqli_num_rows($bos_product_res) > 0){
                    while($bos_product_row = mysqli_fetch_assoc($bos_product_res)){
                        $editData['product'][] = $bos_product_row;
                    }   
                }
                
                /*$bos_service_query = "SELECT bsd.*, sm.product_name as service_name FROM bos_service_detail bsd LEFT JOIN service_master_data sm ON bsd.service_id = sm.id  WHERE bsd.bos_id = '".$editData['id']."' ORDER BY bsd.id";
                $bos_service_res = mysqli_query($conn, $bos_service_query);
                if($bos_service_res && mysqli_num_rows($bos_service_res) > 0){
                    while($bos_service_row = mysqli_fetch_assoc($bos_service_res)){
                        $editData['service'][] = $bos_service_row;
                    }   
                }*/
            }else{
                $_SESSION['msg']['fail'] = 'Invalid Request! Try Again.';
                header('Location: bill-of-supply.php');exit;
            }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Bill Of Supply - DigiBooks</title>
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
  <link rel="stylesheet" href="css/messagebox.css">
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
                            <?php if((isset($user_sub_module) && in_array("Tax Billing", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <a href="sales-tax-billing.php" class="btn btn-dark active">Sales</a>
                            <?php } if((isset($user_sub_module) && in_array("View Sales Bill", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <a href="view-sales-tax-billing.php" class="btn btn-dark active">View Sales Bill</a>
                            <?php } if((isset($user_sub_module) && in_array("Sales Return", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <a href="sales-return.php" class="btn btn-dark">Sales Return</a>
                            <?php } if((isset($user_sub_module) && in_array("Sales Return List", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                              <a href="view-sales-return.php" class="btn btn-dark">Sales Return List</a>
                            <?php } if((isset($user_sub_module) && in_array("Cancellation List", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                              <a href="sales-cancellation-list.php" class="btn btn-dark">Cancellation List</a>
                            <?php } if((isset($user_sub_module) && in_array("Order", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                              <a href="#" class="btn btn-dark dropdown-toggle" id="dropdownMenuButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Order</a>
                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton4">
                                    <a class="dropdown-item" href="sales-order.php">Order/Estimate/Templates</a>
                                    <a class="dropdown-item" href="sales-order-history.php">History</a>
                                  </div>
                            <?php } if((isset($user_sub_module) && in_array("Sales History", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                              <a href="sales-history.php" class="btn btn-dark">History</a>
                            <?php } ?>
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
                      <div class="form-group row">

                        <div class="col-12 col-md-2">
                          <label for="invoice_date">Bill Date <span class="text-danger">*</span></label>
                          <div class="input-group date <?php if(!isset($_GET['id'])){echo"datepicker";} ?>">
                            <?php 
                              if(isset($_GET['id']) && $_GET['id'] != ''){
                                $invoicedate = (isset($editData['invoice_date']) && $editData['invoice_date'] != '') ? date('d/m/Y', strtotime($editData['invoice_date'])) : '';
                              }else{
                                $invoicedate = date('d/m/Y');
                              }
                            ?>
                            <input type="text" class="form-control border" name="invoice_date" autocomplete="off" <?php if(isset($_GET['id'])){echo"readonly";} ?> value="<?php echo (isset($invoicedate)) ? $invoicedate : ''; ?>" required data-parsley-errors-container="#error-date">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          <span id="error-date"></span>
                        </div>

                        <div class="col-12 col-md-2">
                          <label for="invoice_no">Bill No <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" <?php if(isset($_GET['id'])){echo"readonly";} ?> name="invoice_no" id="invoice_no" placeholder="Invoice No" value="<?php echo (isset($editData['invoice_no'])) ? $editData['invoice_no'] : getInvoiceNoForBillOfSupply('Debit'); ?>" required>
                        </div>

                        <div class="col-12 col-md-2">
                          <label for="customer_city">Customer City</label>
                          <select class="js-example-basic-single" style="width:100%" name="customer_city" id="customer_city">
                            <option value="">Select City</option>
                            <?php 
                                $getCityQ = "SELECT ct.id, ct.name, ct.state_id, st.name as statename, st.state_code_gst as statecode FROM own_cities ct LEFT JOIN own_states st ON ct.state_id = st.id WHERE ct.state_id = 12 ORDER BY ct.name";
                                $getCityR = mysqli_query($conn, $getCityQ);
                                if($getCityR && mysqli_num_rows($getCityR) > 0){
                                    while($getCityRow = mysqli_fetch_assoc($getCityR)){
                            ?>
                                <option data-code = "<?php echo (isset($getCityRow['statecode'])) ? $getCityRow['statecode'] : ''; ?>" value="<?php echo $getCityRow['id']; ?>" <?php echo (isset($editData['lgr_customer_city']) && $editData['lgr_customer_city'] == $getCityRow['id']) ? 'selected' : ''; ?> ><?php echo $getCityRow['name']; ?></option>
                            <?php 
                                    }
                                }
                            ?>
                          </select>
                        </div>
                        
                        <div class="col-12 col-md-2 customer-name-div">
                          <label for="customer_id">Customer Name <span class="text-danger">*</span></label>
                          <input class="form-control" data-name="name" autocomplete="nope" type="text" value="<?php echo (isset($editData['lgr_customer_name'])) ? $editData['lgr_customer_name'] : '' ?>" name="customer_name" id="customer_name" required data-parsley-errors-container="#error-customer_id">
                          <small class="customererror text-danger"></small>
                          
                          <input type="hidden" name="statecode" id="statecode" value="<?php echo (isset($editData['statecode'])) ? $editData['statecode'] : ''; ?>">
                          <input type="hidden" name="cur_statecode" id="cur_statecode" value="<?php echo (isset($_SESSION['state_code'])) ? $_SESSION['state_code'] : ''; ?>" >
                          <input type="hidden" name="customer_id" id="customer_id" value="<?php echo (isset($editData['customer_id'])) ? $editData['customer_id'] : ''; ?>" >
                          <input type="hidden" name="salesman_id" id="salesman_id" value="<?php echo (isset($editData['lgr_salesman_id'])) ? $editData['lgr_salesman_id'] : ''; ?>" >
                          <input type="hidden" name="rate_id" id="rate_id" value="<?php echo (isset($editData['lgr_rate_id'])) ? $editData['lgr_rate_id'] : ''; ?>" >
                           <i class="fa fa-spin fa-refresh" id="customer_loader" style="position: absolute; top: 40px; right: 40px; display: none;"></i>
                        </div>

                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Bill Type</label>
                          <div class="row no-gutters">
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input bill_type" name="bill_type" value="Cash" <?php echo (isset($editData['bill_type']) && $editData['bill_type'] == 'Cash') ? 'checked' : ''; ?> >
                                     CASH
                                  </label>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                     <?php 
                                        if(isset($editData['bill_type'])){
                                            $cashchecked = ($editData['bill_type'] == 'Debit') ? 'checked' : '';
                                        }else{
                                            $cashchecked = 'checked';
                                        }
                                    ?>
                                    <input type="radio" class="form-check-input bill_type" name="bill_type" value="Debit" <?php echo (isset($cashchecked)) ? $cashchecked : ''; ?> >
                                    DEBIT
                                </label>
                                </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-12 col-md-2">
                          <a href="javascript:void(0);" class="btn btn-primary btn-xs mt-30" data-toggle="modal" data-target="#add_customer_model" data-whatever="@mdo">Add Customer</a>
                        </div>
                        
                      </div>
                  </div>
                </div>
              </div>
              <!-- Table -------------->
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="col">
                      <div class="row">
                        <div class="col-12">
                            <label class="pull-right sale-rate-lable display-none"></label>
                        </div>
                        <div class="col-12">
                         <h4>Product Type</h4>
                          <table class="table">
                            <thead>
                              <tr>
                                  <th width="5%">Sr No</th>
                                  <th width="20%">Product</th>
                                  <th>MRP</th>
                                  <th>MFG. Co.</th>
                                  <th>Batch</th>
                                  <th>Qty.</th>
                                  <th>Free Qty</th>
                                  <th>Rate</th>
                                  <th>Amount</th>
                                  <th width="8%">&nbsp;</th>
                              </tr>
                            </thead>
                            <tbody id="item-tbody">
                                <?php if(isset($editData['product']) && !empty($editData['product'])){ ?>
                                    <?php foreach($editData['product'] as $key => $value){ ?>
                                        <tr data-id="<?php echo $key+1; ?>" id="tr-<?php echo $key+1; ?>">
                                            <td><?php echo $key+1; ?></td>
                                            <td>
                                                <input type="text" name="product[]" class="form-control product" placeholder="Product" value="<?php echo (isset($value['product_name'])) ? $value['product_name'] : ''; ?>" required>
                                                <small class="producterror text-danger"></small>
                                                <input type="hidden" class="product_id" name="product_id[]" value="<?php echo (isset($value['product_id'])) ? $value['product_id'] : ''; ?>"></td>
                                            <td><input type="text" name="mrp[]" class="form-control mrp onlynumber" placeholder="MRP" value="<?php echo (isset($value['mrp'])) ? $value['mrp'] : ''; ?>"></td>
                                          <td><input type="text" name="mfg_co[]" class="form-control mfg" placeholder="MFG. Co." value="<?php echo (isset($value['mfg_co'])) ? $value['mfg_co'] : ''; ?>"></td>
                                          <td>
                                            <input type="text" name="batch[]" class="form-control batch" placeholder="Batch" value="<?php echo (isset($value['batch'])) ? $value['batch'] : ''; ?>">
                                          </td>
                                          <td>
                                            <input type="text" name="qty[]" class="form-control qty onlynumber" placeholder="Qty." value="<?php echo (isset($value['qty'])) ? $value['qty'] : ''; ?>" required>
                                            <input type="hidden" name="qty_ratio[]" class="qty_ratio" value="<?php echo (isset($value['qty_ratio'])) ? $value['qty_ratio'] : ''; ?>">
                                            <input type="hidden" name="current_qty[]" class="current_qty">
                                            <small class="qty_error text-danger"></small>
                                          </td>
                                          <td>
                                            <input type="text" name="freeqty[]" class="form-control freeqty onlynumber" placeholder="Free Qty" value="<?php echo (isset($value['freeqty'])) ? $value['freeqty'] : ''; ?>">
                                            <input type="hidden" name="ptr[]" class="form-control ptr" placeholder="PTR" value="<?php echo (isset($value['ptr'])) ? $value['ptr'] : ''; ?>">
                                            <input type="hidden" name="discount[]" class="form-control discount" placeholder="Discount(RS)" value="<?php echo (isset($value['discount'])) ? $value['discount'] : ''; ?>">
                                          </td>
                                          
                                          <td>
                                            <input type="text" name="rate[]" class="form-control rate onlynumber" placeholder="Rate" value="<?php echo (isset($value['rate'])) ? $value['rate'] : ''; ?>" required>
                                          </td>
                                         
                                          <td><input type="text" name="totalamount[]" class="form-control totalamount onlynumber" placeholder="0.00" value="<?php echo (isset($value['totalamount'])) ? $value['totalamount'] : ''; ?>" readonly required></td>
                                          <td>
                                            <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item"><i class="fa fa-plus mr-0 ml-0"></i></button>
                                            <?php if($key != 0){ ?>
                                                <button type="button" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-item" style=""><i class="fa fa-close mr-0 ml-0"></i></button>
                                            <?php } ?>
                                          </td>
                                        </tr>
                                    <?php } ?>
                                <?php }else{ ?>
                                    <tr data-id="1" id="tr-1">
                                      <td>1</td>
                                      <td>
                                        <input type="text" name="product[]" class="form-control product" placeholder="Product" required>
                                        <small class="producterror text-danger"></small>
                                        <input type="hidden" class="product_id" name="product_id[]"></td>
                                      <td><input type="text" name="mrp[]" class="form-control mrp onlynumber" placeholder="MRP"></td>
                                      <td><input type="text" name="mfg_co[]" class="form-control mfg" placeholder="MFG. Co."></td>
                                      <td>
                                        <input type="text" name="batch[]" class="form-control batch" placeholder="Batch">
                                      </td>
                                      <td>
                                        <input type="text" name="qty[]" class="form-control qty onlynumber" placeholder="Qty." required>
                                        <input type="hidden" name="qty_ratio[]" class="qty_ratio" value="0">
                                        <input type="hidden" name="current_qty[]" class="current_qty">
                                        <small class="qty_error text-danger"></small>
                                      </td>
                                      <td>
                                        <input type="text" name="freeqty[]" class="form-control freeqty onlynumber" placeholder="Free Qty">
                                        <input type="hidden" name="ptr[]" class="form-control ptr" placeholder="PTR">
                                        <input type="hidden" name="discount[]" class="form-control discount" placeholder="Discount(RS)">
                                      </td>
                                      
                                      <td>
                                        <input type="text" name="rate[]" class="form-control rate onlynumber" placeholder="Rate" required>
                                      </td>
                                     
                                      <td><input type="text" name="totalamount[]" class="form-control totalamount onlynumber" placeholder="0.00" readonly required></td>
                                      <td>
                                        <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item"><i class="fa fa-plus mr-0 ml-0"></i></button>
                                      </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      
                    </div>
                    <hr>
                    <div class="col-12">
                      <div class="row form-group">
                        <div class="col-md-8">
                            <label for="remarks">Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control"><?php echo (isset($editData['remarks'])) ? $editData['remarks'] : ''; ?></textarea>
                        </div>
                          
                        <div class="col-md-4">
                          <div class="form-group row">
                            <table class="table table-striped" width="100%">
                              <tbody>
                                <tr >
                                  <td align="right">
                                    <select name="discount_type" id="discount_type" class="form-control" style="width:65%;">
                                        <option value="RS" <?php echo (isset($editData['discount_type']) && $editData['discount_type'] == 'RS') ? 'selected' : ''; ?> >Discount In Rs</option>
                                        <option value="PER" <?php echo (isset($editData['discount_type']) && $editData['discount_type'] == 'PER') ? 'selected' : ''; ?> >Discount In Per(%)</option>
                                    </select>
                                  </td>
                                  <td align="right" width="158px">
                                      <input type="text" name="discount_amount" id="discount_amount" class="form-control onlynumber" value="<?php echo (isset($editData['discount_amount'])) ? $editData['discount_amount'] : ''; ?>"> 
                                  </td>
                                </tr>
                                
                                
                                <tr>
                                  <td align="right">
                                   Overall Dis. Value
                                  </td>
                                  <td align="right">
                                    <input type="text" name="overalldiscount" id="overalldiscount" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editData['overalldiscount'])) ? $editData['overalldiscount'] : ''; ?>" readonly>
                                  </td>
                                </tr>
                                <tr style="background:#ececec;">
                                  <td align="right">
                                    Total Amount
                                  </td>
                                  <td align="right">
                                    <input type="text" name="total_amount" id="total_amount" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editData['total_amount'])) ? number_format($editData['total_amount'], 2, '.', '') : ''; ?>" readonly>
                                  </td>
                                </tr>
                                
                                <tr style="background:#e0e0e0;">
                                  <td align="right">
                                    Round off
                                  </td>
                                  <td align="right">
                                    <input type="text" name="roundoff_amount" id="roundoff_amount" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editData['roundoff_amount'])) ? $editData['roundoff_amount'] : ''; ?>" readonly>
                                  </td>
                                </tr>
                                
                                <tr style="background:#0062ab;color:#fff;">
                                  <td align="right">
                                    <strong>NET VALUE (<i class="fa fa-rupee"></i>)</strong>
                                  </td>
                                  <td align="right">
                                   <strong><input type="text" name="final_amount" id="final_amount" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editData['final_amount'])) ? $editData['final_amount'] : ''; ?>" readonly></strong>
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
                          <a href="view-bill-of-supply.php" class="btn btn-light pull-left">Cancel</a>
                          <button type="submit" name="save" class="btn btn-success mr-2 pull-right">Save</button>
                          <button type="submit" name="saveAndNext" class="btn btn-success mr-2 pull-right">Save & Next</button>
                          <!--<button type="submit" name="saveAndPrint" class="btn btn-success mr-2 pull-right">Save & Print</button>-->
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
        
        <?php include "popup/limit-show-model.php"?>
        <!-- last bill Model -->
        <?php include('popup/customer-last-bill-model.php'); ?>
        <!-- PTR RATE Model -->
        <?php include('popup/ptr-discount-model.php'); ?>
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  
<!-- -------------------------------------------HIDDEN TR START----------------------------------------------------- -->
  <div id="hiddenItemHtml" style="display: none;">
    <table>
      <tr data-id="##SRNO##" id="tr-##SRNO##">
        <td>##SRNO##</td>
        <td>
          <input type="text" name="product[]" class="form-control product" placeholder="Product" required>
          <small class="producterror text-danger"></small>
          <input type="hidden" class="product_id" name="product_id[]"></td>
        <td><input type="text" name="mrp[]" class="form-control mrp onlynumber" placeholder="MRP"></td>
        <td><input type="text" name="mfg_co[]" class="form-control mfg" placeholder="MFG. Co."></td>
        <td>
          <input type="text" name="batch[]" class="form-control batch" placeholder="Batch">
          <input type="hidden" name="expiry[]" class="expiry" value="">
        </td>
        <td>
          <input type="text" name="qty[]" class="form-control qty onlynumber" placeholder="Qty." required>
          <input type="hidden" name="qty_ratio[]" class="qty_ratio" value="0">
          <input type="hidden" name="current_qty[]" class="current_qty">
          <small class="qty_error text-danger"></small>
        </td>
        <td>
          <input type="text" name="freeqty[]" class="form-control freeqty onlynumber" placeholder="Free Qty">
          <input type="hidden" name="ptr[]" class="form-control ptr" placeholder="PTR">
          <input type="hidden" name="discount[]" class="form-control discount" placeholder="Discount(RS)">
        </td>
        
        <td>
          <input type="text" name="rate[]" class="form-control rate onlynumber" placeholder="Rate" required>
        </td>
       
        <td><input type="text" name="totalamount[]" class="form-control totalamount onlynumber" placeholder="0.00" readonly required></td>
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
  <script src="js/messagebox.js"></script>
  <script src="js/form-addons.js"></script>
  <script src="js/x-editable.js"></script>
  <script src="js/dropify.js"></script>
  <script src="js/dropzone.js"></script>
  <script src="js/jquery-file-upload.js"></script>
  <script src="js/formpickers.js"></script>
  <script src="js/form-repeater.js"></script>
  
  
  <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
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


<script src="js/custom/bill-of-supply.js"></script>
<script src="js/custom/add-customer-popup.js"></script>
<script src="js/custom/onlynumber.js"></script>
<!-- <script src="js/custom/onlyalphabet.js"></script> -->

<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>
  
  <!-- End custom js for this page-->
</body>


</html>
