<?php $title="Tax Billing"; 
 include('include/usertypecheck.php');
 include('include/permission.php');
 //include('include/config-ihis.php');
 
  $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
?>
<!-------------------------------------------- CODE FOR ADD AND UPDATE TAX BILLING START ----------------------------------------------->
<?php

  if(isset($_POST['save'])){
    
    $tax_billing['invoice_date'] = (isset($_POST['invoice_date']) && $_POST['invoice_date'] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['invoice_date']))) : NULL;
    if(isset($_GET['id']) && $_GET['id'] != '' ){
        $tax_billing['invoice_no'] = (isset($_POST['invoice_no'])) ? $_POST['invoice_no'] : '';
    }else{
        $tax_billing['invoice_no'] = sale_of_service((isset($_POST['bill_type'])) ? $_POST['bill_type'] : '');
    }
    $tax_billing['city_id'] = (isset($_POST['customer_city'])) ? $_POST['customer_city'] : NULL;
    $tax_billing['statecode'] = (isset($_POST['statecode'])) ? $_POST['statecode'] : NULL;
    
    // $tax_billing['customer_id'] = findCustomer($_POST);
    $tax_billing['customer_name'] = (isset($_POST['customer_name'])) ? $_POST['customer_name'] : '';
    $tax_billing['customer_id'] = (isset($_POST['customer_id'])) ? $_POST['customer_id'] : '';
    $tax_billing['is_general_sale'] = (isset($_POST['customer_id']) && $_POST['customer_id'] != '' && $_POST['customer_id'] != 0) ? 0 : 1;
    
    $tax_billing['bill_type'] = (isset($_POST['bill_type'])) ? $_POST['bill_type'] : '';
    $tax_billing['alltotalamount'] = (isset($_POST['alltotalamount']) && $_POST['alltotalamount'] != '') ? $_POST['alltotalamount'] : 0;
    // $tax_billing['total_return_amount'] = (isset($_POST['total_return_amount']) && $_POST['total_return_amount'] != '') ? $_POST['total_return_amount'] : 0;
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
    // $tax_billing['cr_db_type'] = (isset($_POST['cr_db_type']) && $_POST['cr_db_type'] != '') ? $_POST['cr_db_type'] : '';
    // $tax_billing['cr_db_val'] = (isset($_POST['cr_db_val']) && $_POST['cr_db_val'] != '') ? $_POST['cr_db_val'] : 0;
    $tax_billing['purchase_amount'] = (isset($_POST['purchase_amount']) && $_POST['purchase_amount'] != '') ? $_POST['purchase_amount'] : 0;
    $tax_billing['roundoff_amount'] = (isset($_POST['roundoff_amount']) && $_POST['roundoff_amount'] != '') ? $_POST['roundoff_amount'] : 0;
    $tax_billing['final_amount'] = (isset($_POST['final_amount']) && $_POST['final_amount'] != '') ? $_POST['final_amount'] : 0;
    $tax_billing['remarks'] = (isset($_POST['remarks'])) ? $_POST['remarks'] : '';
    

    if(isset($_GET['id']) && $_GET['id']){
      $query = "UPDATE sale_of_service SET ";
    }else{
      $query = "INSERT INTO sale_of_service SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."',";
    }

    foreach ($tax_billing as $key => $value) {
       $query .= " ".$key." = '".$value."', ";
    }

    if(isset($_GET['id']) && $_GET['id']){
        $query .= "modified = '".date('Y-m-d H:i:s')."', modifiedby = '".$_SESSION['auth']['id']."' ";
        $query .= "WHERE id = '".$_GET['id']."'";
    }else{
       $query .= "cancel = 1, created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'"; 
    }
     $taxbillingRes = mysqli_query($conn, $query);
    if($taxbillingRes){
      $taxbillid = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : mysqli_insert_id($conn);
     
      if($taxbillid != ''){
          if((isset($_GET['patient']) && $_GET['patient'] != '') && (isset($_GET['group']) && $_GET['group'] != '') && (isset($_GET['type']) && $_GET['type'] != '')){
              //updateIhisPrecriptionBillFlag($_GET['patient'], $_GET['group'], $_GET['type']);
          }
          
       $deleteOldItemQ = "DELETE FROM sale_of_service_details WHERE service_bill_id = '".$taxbillid."'";
        mysqli_query($conn, $deleteOldItemQ);

        /*-----------------------------------SAVE TAX BILLING ITEMS START----------------------------------------------*/
        $count = (isset($_POST['service_id']) && !empty($_POST['service_id'])) ? count($_POST['service_id']) : 0;
        if($count != 0){
          for ($i=0; $i < $count; $i++) { 
              
            $tax_billing_details['service_bill_id'] = $taxbillid;
            $tax_billing_details['service_id'] = (isset($_POST['service_id'][$i])) ? $_POST['service_id'][$i] : '';
            $tax_billing_details['sac_code'] = (isset($_POST['sac_code'][$i]) && $_POST['sac_code'][$i] != '') ? $_POST['sac_code'][$i] : 0;
            $tax_billing_details['rate'] = (isset($_POST['rate'][$i]) && $_POST['rate'][$i] != '') ? $_POST['rate'][$i] : 0;
            $tax_billing_details['totalamount'] = (isset($_POST['totalamount'][$i]) && $_POST['totalamount'][$i] != '') ? $_POST['totalamount'][$i] : 0;
            $tax_billing_details['gst'] = (isset($_POST['gst'][$i]) && $_POST['gst'][$i] != '') ? $_POST['gst'][$i] : 0;
            $tax_billing_details['igst'] = (isset($_POST['igst'][$i]) && $_POST['igst'][$i] != '') ? $_POST['igst'][$i] : 0;
            $tax_billing_details['cgst'] = (isset($_POST['cgst'][$i]) && $_POST['cgst'][$i] != '') ? $_POST['cgst'][$i] : 0;
            $tax_billing_details['sgst'] = (isset($_POST['sgst'][$i]) && $_POST['sgst'][$i] != '') ? $_POST['sgst'][$i] : 0;
          
            $item = "INSERT INTO sale_of_service_details SET ";
            foreach ($tax_billing_details as $k => $v) {
                $item .= " ".$k." = '".$v."', ";
            }
         $item .= "created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
             mysqli_query($conn, $item);
          }
        }
        /*-----------------------------------SAVE TAX BILLING ITEMS END----------------------------------------------*/
      }

      if(isset($_GET['id']) && $_GET['id'] != ''){
        $_SESSION['msg']['success'] = "Sale Of Service Bill Updated Successfully.";
      }else{
        $_SESSION['msg']['success'] = "Sale Of Service Bill Added Successfully.";
      }
      
      if(isset($_POST['save'])){
          header('Location: view_sale_of_service.php');exit;
      } else{
          header('Location: sale_of_service.php');exit;
      }
      
    }else{
      if(isset($_GET['id']) && $_GET['id'] != ''){
        $_SESSION['msg']['fail'] = "Sale Of Service Bill Updated Fail!";
      }else{
        $_SESSION['msg']['fail'] = "Sale Of Service Bill Added Fail!";
      }
    }
  }
?>
<!-------------------------------------------- CODE FOR ADD AND UPDATE TAX BILLING END ----------------------------------------------->


<!-------------------------------------------- CODE FOR EDIT ID BY GET ALL DATA START ----------------------------------------------->
<?php 
    if(isset($_GET['id']) && $_GET['id'] != ''){
      $editQ = "SELECT tb.*, lg.customer_id as customer_u_id,lg.name as l_customer_name,lg.mobile as customer_mobile, lg.email as customer_email, lg.city as customer_city  FROM sale_of_service tb LEFT JOIN ledger_master lg ON tb.customer_id = lg.id WHERE tb.id = '".$_GET['id']."' AND tb.pharmacy_id = '".$pharmacy_id."' AND tb.financial_id = '".$financial_id."'";
      $editR = mysqli_query($conn, $editQ);
      if($editR && mysqli_num_rows($editR) > 0){
        $tmpeditdata = mysqli_fetch_assoc($editR);
        if(isset($tmpeditdata['is_general_sale']) && $tmpeditdata['is_general_sale'] == 1){
          $tmpeditdata['l_customer_name'] = $tmpeditdata['customer_name'];
          $tmpeditdata['customer_city'] = $tmpeditdata['city_id'];
        }
        $editdata = $tmpeditdata;
        $billing_item_Q = "SELECT sosd.*, sm.name FROM sale_of_service_details sosd LEFT JOIN service_master sm ON sosd.service_id = sm.id WHERE sosd.service_bill_id = '".$editdata['id']."'";
        $billing_item_R = mysqli_query($conn, $billing_item_Q);
        if($billing_item_R && mysqli_num_rows($billing_item_R) > 0){
            while($billing_item_Row = mysqli_fetch_assoc($billing_item_R)){
                //$productData = getAllProductWithCurrentStock('','',0,[$billing_item_Row['product_id']]);
                //$billing_item_Row['current_qty'] = (isset($productData[0]['currentstock']) && $productData[0]['currentstock'] != '') ? $productData[0]['currentstock'] : 0;
                $editdata['detail'][] = $billing_item_Row;
            }
        }
      }
    }
?>
<!-------------------------------------------- CODE FOR EDIT ID BY GET ALL DATA END ----------------------------------------------->

<!--CODE FOR GET PHARMACY DETAIL START-->
<?php
    $getPharmcyQ = "SELECT id, firm_name FROM pharmacy_profile WHERE id = '".$pharmacy_id."' LIMIT 1";
    $getPharmcyR = mysqli_query($conn, $getPharmcyQ);
    if($getPharmcyR && mysqli_num_rows($getPharmcyR) > 0){
        $getPharmcyRow = mysqli_fetch_assoc($getPharmcyR);
        $pharmacy_firm = (isset($getPharmcyRow['firm_name'])) ? $getPharmcyRow['firm_name'] : '';
    }
?>
<!--CODE FOR GET PHARMACY DETAIL END-->

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Sale Of Service - DigiBooks</title>
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
                   
                      <div class="form-group row">

                        <div class="col-12 col-md-2">
                          <label for="invoice_date">Bill Date <span class="text-danger">*</span></label>
                          <div class="input-group date <?php if(!isset($_GET['id'])){echo"datepicker";} ?>">
                            <?php 
                              if(isset($_GET['id']) && $_GET['id'] != ''){
                                $invoicedate = (isset($editdata['invoice_date']) && $editdata['invoice_date'] != '') ? date('d/m/Y', strtotime($editdata['invoice_date'])) : '';
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
                          <input type="text" class="form-control" <?php if(isset($_GET['id'])){echo"readonly";} ?> name="invoice_no" id="invoice_no" placeholder="Invoice No" value="<?php echo (isset($editdata['invoice_no'])) ? $editdata['invoice_no'] : sale_of_service('Debit'); ?>" required>
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
                                <option data-code = "<?php echo (isset($getCityRow['statecode'])) ? $getCityRow['statecode'] : ''; ?>" value="<?php echo $getCityRow['id']; ?>" <?php echo (isset($editdata['customer_city']) && $editdata['customer_city'] == $getCityRow['id']) ? 'selected' : ''; ?> ><?php echo $getCityRow['name']; ?></option>
                            <?php 
                                    }
                                }
                            ?>
                          </select>
                        </div>
                        
                        <div class="col-12 col-md-2 customer-name-div">
                          <label for="customer_id">Customer Name <span class="text-danger">*</span></label>
                          <input class="form-control" data-name="name" autocomplete="nope" type="text" value="<?php echo (isset($editdata['l_customer_name'])) ? $editdata['l_customer_name'] : '' ?>" name="customer_name" id="customer_name" required data-parsley-errors-container="#error-customer_id">
                          <small class="customererror text-danger"></small>
                          <span id="error-customer_id"></span>
                          <input type="hidden" name="statecode" id="statecode" value="<?php echo (isset($editdata['statecode'])) ? $editdata['statecode'] : ''; ?>">
                          <input type="hidden" name="cur_statecode" id="cur_statecode" value="<?php echo (isset($_SESSION['state_code'])) ? $_SESSION['state_code'] : ''; ?>" >
                          <input type="hidden" name="customer_id" id="customer_id" value="<?php echo (isset($editdata['customer_id'])) ? $editdata['customer_id'] : ''; ?>" >
                          <input type="hidden" name="salesman_id" id="salesman_id" value="<?php echo (isset($editdata['salesman_id'])) ? $editdata['salesman_id'] : ''; ?>" >
                          <input type="hidden" name="rate_id" id="rate_id" value="<?php echo (isset($editdata['rate_id'])) ? $editdata['rate_id'] : ''; ?>" >
                           
                          <input type="hidden" name="limit_total" id="limit_total" value="<?php echo (isset($_GET['id'])) ? CheckCr($editdata['customer_id']) : '0'; ?>">
                          <input type="hidden" name="limit_status" id="limit_status" value="0">
                          <input type="hidden" name="popup_status" id="popup_status" value="0">
                           <i class="fa fa-spin fa-refresh" id="customer_loader" style="position: absolute; top: 40px; right: 40px; display: none;"></i>
                        </div>

                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Bill Type</label>
                          <div class="row no-gutters">
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input bill_type" name="bill_type" value="Cash" <?php echo (isset($editdata['bill_type']) && $editdata['bill_type'] == 'Cash') ? 'checked' : ''; ?> >
                                     CASH
                                  </label>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                     <?php 
                                        if(isset($editdata['bill_type'])){
                                            $cashchecked = ($editdata['bill_type'] == 'Debit') ? 'checked' : '';
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
                          <table class="table">
                            <thead>
                              <tr>
                                  <th width="5%">Sr No</th>
                                  <th width="20%">Service</th>
                                  <th>SAC Code</th>
                                  <th>Rate</th>
                                  <th>Amount</th>
                                  <th>&nbsp;</th>
                              </tr>
                            </thead>
                            <tbody id="item-tbody">
                              <!-- Row Starts -->
                              <?php if(isset($editdata['detail']) && !empty($editdata['detail'])){ ?>
                                <?php foreach($editdata['detail'] as $key => $value){?>
                                  <tr data-id="<?php echo $key+1; ?>" id="tr-<?php echo $key+1; ?>">
                                    <td><?php echo $key+1; ?></td>
                                    <td>
                                      <input type="text" name="service[]" class="form-control service" placeholder="Service" value="<?php echo (isset($value['name'])) ? $value['name'] : ''; ?>" required>
                                      <small class="producterror text-danger"></small>
                                      <input type="hidden" class="service_id" name="service_id[]" value="<?php echo (isset($value['service_id'])) ? $value['service_id'] : ''; ?>">
                                    </td>
                                    <td><input type="text" name="sac_code[]" class="form-control sac_code onlynumber" placeholder="SAC Code" value="<?php echo (isset($value['sac_code'])) ? $value['sac_code'] : ''; ?>" readonly></td>
                                   
                                    <td>
                                      <input type="text" name="rate[]" class="form-control rate onlynumber" placeholder="Rate" value="<?php echo (isset($value['rate'])) ? $value['rate'] : ''; ?>" required readonly>
                                        <?php if(isset($pharmacy_firm) && $pharmacy_firm != 'Composition'){ ?>
                                          <input type="hidden" name="gst[]" class="gst" value="<?php echo (isset($value['gst']) && $value['gst'] != '') ? $value['gst'] : 0; ?>">
                                          <input type="hidden" name="igst[]" class="c_igst" value="<?php echo (isset($value['igst']) && $value['igst'] != '') ? $value['igst'] : 0; ?>">
                                          <input type="hidden" name="cgst[]" class="c_cgst" value="<?php echo (isset($value['cgst']) && $value['cgst'] != '') ? $value['cgst'] : 0; ?>">
                                          <input type="hidden" name="sgst[]" class="c_sgst" value="<?php echo (isset($value['sgst']) && $value['sgst'] != '') ? $value['sgst'] : 0; ?>">
                                        <?php } ?>
                                    </td>
                                   
                                    <td>
                                      <input type="text" name="totalamount[]" class="form-control totalamount onlynumber" placeholder="0.00" value="<?php echo (isset($value['totalamount']) && $value['totalamount'] != '') ? $value['totalamount'] : 0; ?>" readonly>
                                    </td>
                                    <td>
                                      <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item"><i class="fa fa-plus mr-0 ml-0"></i></button>
                                    </td>
                                  </tr>
                                <?php } ?>
                              <?php }else{ ?>
                                <tr data-id="1" id="tr-1">
                                  <td>1</td>
                                  <td>
                                    <input type="text" name="service[]" class="form-control service" placeholder="Service" required>
                                    <small class="producterror text-danger"></small>
                                    <input type="hidden" class="service_id" name="service_id[]"></td>
                                  <td><input type="text" name="sac_code[]" class="form-control sac_code onlynumber" placeholder="SAC Code" readonly></td>
                                  
                                  
                                  <td>
                                    <input type="text" name="rate[]" class="form-control rate onlynumber" placeholder="Rate" required readonly>
                                    <?php if(isset($pharmacy_firm) && $pharmacy_firm != 'Composition'){ ?>
                                        <input type="hidden" name="gst[]" class="gst" value="0">
                                        <input type="hidden" name="igst[]" class="c_igst" value="0">
                                        <input type="hidden" name="cgst[]" class="c_cgst" value="0">
                                        <input type="hidden" name="sgst[]" class="c_sgst" value="0">
                                    <?php } ?>
                                  </td>
                                 
                                  <td><input type="text" name="totalamount[]" class="form-control totalamount onlynumber" placeholder="0.00" readonly ></td>
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
                      <div class="row form-group">
                        <div class="col-md-8">
                            <label for="remarks">Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control"><?php echo (isset($editdata['remarks'])) ? $editdata['remarks'] : ''; ?></textarea>
                        </div>
                          
                        <div class="col-md-4">
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
                                            <input type="text" class="form-control onlynumber" name="discount_per" id="discount_per" placeholder="%" value="<?php echo (isset($editdata['discount_per']) && (isset($editdata['discount_type']) && $editdata['discount_type'] == 'per')) ? $editdata['discount_per'] : ''; ?>" style="display:inline-block;width:80px;">
                                      </div>
                                      
                                                  
                                      <div class="radio-inline ml-2">            
                                          <div class="icheck" style="display:inline-block">
                                              <input tabindex="8" type="radio" class="discount_type" name="discount_type" value="rs" <?php echo (isset($editdata['discount_type']) && $editdata['discount_type'] == 'rs') ? 'checked' : ''; ?> <?php echo (!isset($_GET['id'])) ? 'checked' : ''; ?>>
                                              <label for="minimal-radio-2" class="mt-0"></label>
                                          </div>
                                          <input type="text" class="form-control onlynumber" name="discount_rs" id="discount_rs" placeholder="Rs" value="<?php echo (isset($editdata['discount_rs']) && (isset($editdata['discount_type']) && $editdata['discount_type'] == 'rs')) ? $editdata['discount_rs'] : ''; ?>" style="display:inline-block;width:80px;">
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
                                <?php if(isset($pharmacy_firm) && $pharmacy_firm != 'Composition'){ ?>
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
                                <?php } ?>
                                
                                <tr style="background:#ececec;">
                                  <td align="right">
                                    Total Amount
                                  </td>
                                  <td align="right">
                                    <input type="text" name="purchase_amount" id="purchase_amount" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editdata['purchase_amount'])) ? number_format($editdata['purchase_amount'], 2, '.', '') : ''; ?>" readonly>
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
                          <a href="view_sale_of_service.php" class="btn btn-light pull-left">Cancel</a>
                          <button type="submit" name="save" class="btn btn-success mr-2 pull-right">Save</button>
                          <!--<button type="submit" name="saveAndNext" class="btn btn-success mr-2 pull-right">Save & Next</button>-->
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
          <input type="text" name="service[]" class="form-control service" placeholder="Service" required>
          <small class="producterror text-danger"></small>
          <input type="hidden" class="service_id" name="service_id[]"></td>
        <td><input type="text" name="sac_code[]" class="form-control sac_code onlynumber" placeholder="SAC Code"></td>
       
        <td>
          <input type="text" name="rate[]" class="form-control rate onlynumber" placeholder="Rate" required>
            <?php if(isset($pharmacy_firm) && $pharmacy_firm != 'Composition'){ ?>
              <input type="hidden" name="gst[]" class="gst" value="0">
              <input type="hidden" name="igst[]" class="c_igst" value="0">
              <input type="hidden" name="cgst[]" class="c_cgst" value="0">
              <input type="hidden" name="sgst[]" class="c_sgst" value="0">
            <?php } ?>
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


<script src="js/custom/sale_of_service.js"></script>
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
