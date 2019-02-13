<?php include('include/usertypecheck.php'); ?>
<?php include('include/config-ihis.php'); ?>
<?php 
  $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
?>
<?php 
    addfinacialproductqty();

    update_leger_ob();
    //FOR SAVE MIN ORDER NOTIFICATION
    //addMinQtyNotification();
    
    sale_remider_update();
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
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
  
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
          <?php include('include/flash.php'); ?>
          <div class="row">
            <?php if($_SESSION['auth']['user_type'] != "owner"){ ?>
                <?php
                    $totalGeneralSale = 0;
                    $totalDebitGeneralSale = 0;
                    $totalCashGeneralSale = 0;
                    
                    $generalDebitSaleQ = "SELECT SUM(final_amount) as totalDebit FROM tax_billing WHERE is_ihis = 0 AND pharmacy_id='".$pharmacy_id."' AND financial_id = '".$financial_id."' AND DATE_FORMAT(created,'%Y-%m-%d') ='".date('Y-m-d')."' AND bill_type = 'Debit'";
                    $generalDebitSaleR = mysqli_query($conn,$generalDebitSaleQ);
                    if($generalDebitSaleR && mysqli_num_rows($generalDebitSaleR) > 0){
                        $generalDebitSaleRow = mysqli_fetch_assoc($generalDebitSaleR);
                        $totalDebitGeneralSale = (isset($generalDebitSaleRow['totalDebit']) && $generalDebitSaleRow['totalDebit'] != '') ? $generalDebitSaleRow['totalDebit'] : 0;
                    }
                    
                    $generalCashSaleQ = "SELECT SUM(final_amount) as totalCash FROM tax_billing WHERE is_ihis = 0 AND pharmacy_id='".$pharmacy_id."' AND financial_id = '".$financial_id."' AND DATE_FORMAT(created,'%Y-%m-%d') ='".date('Y-m-d')."' AND bill_type = 'Cash'";
                    $generalCashSaleR = mysqli_query($conn,$generalCashSaleQ);
                    if($generalCashSaleR && mysqli_num_rows($generalCashSaleR) > 0){
                        $generalCashSaleRow = mysqli_fetch_assoc($generalCashSaleR);
                        $totalCashGeneralSale = (isset($generalCashSaleRow['totalCash']) && $generalCashSaleRow['totalCash'] != '') ? $generalCashSaleRow['totalCash'] : 0;
                    }
                    $totalGeneralSale = $totalDebitGeneralSale+$totalCashGeneralSale;
                ?>
                <div class="col-md-2 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-0 text-primary"><a href="today-ledger.php?ledger=todayGeneralSale" target="_blank">Today Sale</a></h4><small>General</small>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-inline-block pt-3">
                                    <div class="d-flex">
                                        <h3 class="mb-0"><?php echo amount_format(number_format($totalGeneralSale, 2, '.', '')); ?></h3>
                                    </div>
                                    <div style="width:115px;">
                                        <small class="text-gray pull-left"><a href="today-ledger.php?ledger=todayDebitGeneralSale" target="_blank">Debit<a/></small><small class="text-gray pull-right"><?php echo amount_format(number_format($totalDebitGeneralSale, 2, '.', '')); ?></small>
                                        <br/>
                                        <small class="text-gray pull-left"><a href="today-ledger.php?ledger=todayCashGeneralSale" target="_blank">Cash</a></small><small class="text-gray pull-right"><?php echo amount_format(number_format($totalCashGeneralSale, 2, '.', '')); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    /*$totalIhisSale = 0;
                    $totalDebitIhisSale = 0;
                    $totalCashIhisSale = 0;
                    
                    $ihisDebitSaleQ = "SELECT SUM(final_amount) as totalDebit FROM tax_billing WHERE is_ihis = 1 AND pharmacy_id='".$pharmacy_id."' AND DATE_FORMAT(created,'%Y-%m-%d') ='".date('Y-m-d')."' AND bill_type = 'Debit'";
                    $ihisDebitSaleR = mysqli_query($conn,$ihisDebitSaleQ);
                    if($ihisDebitSaleR && mysqli_num_rows($ihisDebitSaleR) > 0){
                        $ihisDebitSaleRow = mysqli_fetch_assoc($ihisDebitSaleR);
                        $totalDebitIhisSale = (isset($ihisDebitSaleRow['totalDebit']) && $ihisDebitSaleRow['totalDebit'] != '') ? $ihisDebitSaleRow['totalDebit'] : 0;
                    }
                    
                    $ihisCashSaleQ = "SELECT SUM(final_amount) as totalCash FROM tax_billing WHERE is_ihis = 1 AND pharmacy_id='".$pharmacy_id."' AND DATE_FORMAT(created,'%Y-%m-%d') ='".date('Y-m-d')."' AND bill_type = 'Cash'";
                    $ihisCashSaleR = mysqli_query($conn,$ihisCashSaleQ);
                    if($ihisCashSaleR && mysqli_num_rows($ihisCashSaleR) > 0){
                        $ihisCashSaleRow = mysqli_fetch_assoc($ihisCashSaleR);
                        $totalCashIhisSale = (isset($ihisCashSaleRow['totalCash']) && $ihisCashSaleRow['totalCash'] != '') ? $ihisCashSaleRow['totalCash'] : 0;
                    }
                    $totalIhisSale = $totalDebitIhisSale+$totalCashIhisSale;*/
                ?>
                <!--<div class="col-md-2 grid-margin">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title mb-0 text-primary"><a href="today-ledger.php?ledger=todayIhisSale" target="_blank">Today Sale</a></h4><small>I-His</small>
                      <div class="d-flex justify-content-between align-items-center">
                        <div class="d-inline-block pt-3">
                          <div class="d-flex">
                            <h3 class="mb-0"><?php echo amount_format(number_format($totalIhisSale, 2, '.', '')); ?></h3>
                          </div>
                          <div style="width:115px;">
                            <small class="text-gray pull-left"><a href="today-ledger.php?ledger=todayDebitIhisSale" target="_blank">Debit</a></small><small class="text-gray pull-right"><?php echo amount_format(number_format($totalDebitIhisSale, 2, '.', '')); ?></small>
                            <br/>
                            <small class="text-gray pull-left"><a href="today-ledger.php?ledger=todayCashIhisSale" target="_blank">Cash</a></small><small class="text-gray pull-right"><?php echo amount_format(number_format($totalCashIhisSale, 2, '.', '')); ?></small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>-->

              <!------------------------ CUSTOMER NOTIFICATION START ---------------------------------->
              <?php
                $customerNotification = getCustomerPaymentNotification();
              ?>
              <?php if(isset($customerNotification) && !empty($customerNotification)){?>
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                        <h4 class="card-title">Customer Payment Notification</h4><hr class="alert-dark">
                        <div class="col mt-3">
                          <div class="row">
                            <div class="col-12">
                              <div class="table-responsive">
                                <table class="table datatable">
                                  <thead>
                                    <tr>
                                        <th class="text-center">Sr No.</th>
                                        <th class="text-center">Customer</th>
                                        <th class="text-center">Invoice Date</th>
                                        <th class="text-center">Invoice No.</th>
                                        <th class="text-right">Bill Amount</th>
                                        <th class="text-right">Pay Amount</th>
                                        <th class="text-right">Remaining Amount</th>
                                        <!--<th>Action</th>-->
                                    </tr>
                                  </thead>
                                  <tbody>
                                     
                                      <?php foreach ($customerNotification as $key => $value) { ?>
                                          <tr>
                                            <td class="text-center"><?php echo $key+1; ?></td>
                                            <td class="text-center"><?php echo (isset($value['customer']['name'])) ? $value['customer']['name'] : ''; ?></td>
                                            <td class="text-center"><?php echo (isset($value['invoice_date']) && $value['invoice_date'] != '') ? date('d/m/Y',strtotime($value['invoice_date'])) : ''; ?></td>
                                            <td class="text-center"><?php echo (isset($value['invoice_no'])) ? $value['invoice_no'] : ''; ?></td>
                                            <td class="text-right"><?php echo (isset($value['total_bill'])) ? amount_format(number_format($value['total_bill'], 2, '.', '')) : ''; ?></td>
                                            <td class="text-right"><?php echo (isset($value['total_payment'])) ? amount_format(number_format($value['total_payment'], 2, '.', '')) : ''; ?></td>
                                            <td class="text-right"><?php echo (isset($value['total_remaining'])) ? amount_format(number_format($value['total_remaining'], 2, '.', '')) : ''; ?></td>
                                            <!--<td>
                                                <a class="btn  btn-behance p-2" href="sales-tax-billing.php?id=<?php echo $value['id']; ?>" title="Show Sale Bill"><i class="fa fa-eye mr-0"></i></a>
                                            </td>-->
                                          </tr>
                                      <?php } ?>
                                    
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                 </div>
              <?php } ?>
            <!------------------------ CUSTOMER NOTIFICATION END ---------------------------------->

            <!------------------------ VENDOR NOTIFICATION START ---------------------------------->
            <?php
              $vendorNotification = getVendorPaymentNotification();
            ?>
            <?php if(isset($vendorNotification) && !empty($vendorNotification)){?>
                  <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                        <h4 class="card-title">Vendor Payment Notification</h4><hr class="alert-dark">
                        <div class="col mt-3">
                          <div class="row">
                            <div class="col-12">
                              <div class="table-responsive">
                                <table class="table datatable">
                                  <thead>
                                    <tr>
                                        <th class="text-center">Sr No.</th>
                                        <th class="text-center">Vendor</th>
                                        <th class="text-center">Voucher Date</th>
                                        <th class="text-center">Voucher No.</th>
                                        <th class="text-right">Bill Amount</th>
                                        <th class="text-right">Pay Amount</th>
                                        <th class="text-right">Remaining Amount</th>
                                        <!--<th>Action</th>-->
                                    </tr>
                                  </thead>
                                  <tbody>
                                    
                                      <?php foreach ($vendorNotification as $key => $value) { ?>
                                          <tr>
                                            <td class="text-center"><?php echo $key+1; ?></td>
                                            <td class="text-center"><?php echo (isset($value['vendor']['name'])) ? $value['vendor']['name'] : ''; ?></td>
                                            <td class="text-center"><?php echo (isset($value['vouchar_date']) && $value['vouchar_date'] != '') ? date('d/m/Y',strtotime($value['vouchar_date'])) : ''; ?></td>
                                            <td class="text-center"><?php echo (isset($value['voucher_no'])) ? $value['voucher_no'] : ''; ?></td>
                                            <td class="text-right"><?php echo (isset($value['total_bill'])) ? amount_format(number_format($value['total_bill'], 2, '.', '')) : ''; ?></td>
                                            <td class="text-right"><?php echo (isset($value['total_payment'])) ? amount_format(number_format($value['total_payment'], 2, '.', '')) : ''; ?></td>
                                            <td class="text-right"><?php echo (isset($value['total_remaining'])) ? amount_format(number_format($value['total_remaining'], 2, '.', '')) : ''; ?></td>
                                            <!--<td>
                                                <a class="btn  btn-behance p-2" href="purchase.php?id=<?php echo $value['id']; ?>" title="Show Purchase Bill"><i class="fa fa-eye mr-0"></i></a>
                                            </td>-->
                                          </tr>
                                      <?php } ?>
                                    
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
            <?php } ?>
            <!------------------------ VENDOR NOTIFICATION END ---------------------------------->

              <!------------------------ MIN ORDER PRODUCT START ---------------------------------->
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">suggested Order</h4><hr class="alert-dark">
                    <div class="col mt-3">
                      <div class="row">
                        <div class="col-12">
                          <div class="table-responsive">
                            <table class="table datatable">
                              <thead>
                                <tr>
                                    <th class="text-center">Sr No.</th>
                                    <th class="text-center">Product Name</th>
                                    <th class="text-center">Company Code</th>
                                    <th class="text-center">Min Qty</th>
                                    <th class="text-center">Current Stock</th>
                                    <th class="text-center">Suggested Order Qty</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                  $minproduct = getMinQtyProduct();
                                ?>
                                 <?php if(isset($minproduct) && !empty($minproduct)){ ?>
                                  <?php foreach ($minproduct as $key => $value) { ?>
                                      <tr>
                                        <td class="text-center"><?php echo $key+1; ?></td>
                                        <td class="text-center"><?php echo (isset($value['product_name'])) ? $value['product_name'] : 'Unknown Product'; ?></td>
                                        <td class="text-center"><?php echo (isset($value['company_code'])) ? $value['company_code'] : ''; ?></td>
                                        <td class="text-center"><?php echo (isset($value['min_qty'])) ? $value['min_qty'] : 0; ?></td>
                                        <td class="text-center"><?php echo (isset($value['currentstock'])) ? $value['currentstock'] : 0; ?></td>
                                        <td class="text-center"><?php echo (isset($value['suggested_qty'])) ? $value['suggested_qty'] : 0; ?></td>
                                      </tr>
                                  <?php } ?>
                                <?php } ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!------------------------ MIN ORDER PRODUCT END ---------------------------------->
              
              <!------------------------ Sale Remider START ---------------------------------->
              <?php
                $Sale_remider_data = Sale_remider_data();
              ?>
              <?php if(isset($Sale_remider_data) && !empty($Sale_remider_data)){ ?>
                  <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                        <h4 class="card-title">Sale Remider</h4><hr class="alert-dark">
                        <div class="col mt-3">
                          <div class="row">
                            <div class="col-12">
                              <div class="table-responsive">
                                <table class="table datatable">
                                  <thead>
                                    <tr>
                                        <th class="text-center">Sr No.</th>
                                        <th class="text-center">Invoice No</th>
                                        <th class="text-center">Invoice Date</th>
                                        <th class="text-center">Customer Name</th>
                                        <th class="text-right">Total Amount</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    
                                     
                                      <?php foreach ($Sale_remider_data as $key => $value) { 
                                        $sale_id = $value['id'];
                                        $sale_remiderQ = "SELECT pm.product_name,tbd.product_id FROM `tax_billing_details` tbd JOIN product_master pm ON pm.id = tbd.product_id WHERE tax_bill_id ='".$sale_id."'";
                                        $sale_remiderR = mysqli_query($conn,$sale_remiderQ);
                                    
                                        if($value['remider_email_sms_status'] == "0"){    
                                            $pharmacyQ = "SELECT * FROM `pharmacy_profile` WHERE id='".$_SESSION['auth']['pharmacy_id']."'";
                                            $pharmacyR = mysqli_query($conn,$pharmacyQ);
                                            $row_pharmacy = mysqli_fetch_assoc($pharmacyR);
                                            $address_arr[] = (isset($row_pharmacy['address1'])) ? $row_pharmacy['address1'] : '';
                                            $address_arr[] = (isset($row_pharmacy['address2'])) ? $row_pharmacy['address2'] : '';
                                            $address_arr[] = (isset($row_pharmacy['address3'])) ? $row_pharmacy['address3'] : '';
                                            $final_add = implode(",",$address_arr);
                                            
                                            $html = "<center>";
                                            $html .= "<h3>".$row_pharmacy['pharmacy_name']."</h3>";
                                            $html .= "<h4>".$final_add."</h4>";
                                            $html .= "<h3>Remider Order Summary</h3><table border='1' cellpadding='10' cellspacing='0'><thead><th>Sr. No</th><th>Product Name</th></thead><tbody>";
                                            $count = 1;
                                            while($row_sale = mysqli_fetch_assoc($sale_remiderR)){
                                              $html .= "<tr>";
                                              $html .= "<td>".$count."</td>";
                                              $html .= "<td>".$row_sale['product_name']."</td>";
                                              $html .= "<tr/>";
                                              $count ++;  
                                                
                                            }
                                            $html .="</tbody></table></center>";
                                            
                                            $r = smtpmail($value['email'], '', '', 'Remider Order Summary', $html, '', '');
                                            $update_productQ = "UPDATE `tax_billing` SET `remider_email_sms_status`='1' WHERE id='".$value['id']."'";
                                            $update_productR = mysqli_query($conn,$update_productQ);
                                        }
                                        
                                        
                                        
                                      ?>
                                          <tr>
                                            <td class="text-center"><?php echo $key+1; ?></td>
                                            <td class="text-center"><?php echo $value['invoice_no']; ?></td>
                                            <td class="text-center"><?php echo date('d/m/Y',strtotime($value['invoice_date'])); ?></td>
                                            <td class="text-center"><?php echo $value['name']; ?></td>
                                            <td class="text-right"><?php echo $value['final_amount']; ?></td>
                                          </tr>
                                      <?php } ?>
                                    
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              <?php } ?>
              <!------------------------ Sale Remider END ---------------------------------->
              
              <!------------------------ OVER CREDIT LIMIT START ---------------------------------->
              <?php 
                $creditLimitNotification = getCreditLimitNotification();
              ?>
              <?php if(isset($creditLimitNotification) && !empty($creditLimitNotification)){ ?>
                <div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Credit Limit Notification</h4><hr class="alert-dark">
                      <div class="col mt-3">
                        <div class="row">
                          <div class="col-12">
                            <div class="table-responsive">
                              <table class="table datatable">
                                <thead>
                                  <tr>
                                      <th class="text-center">Sr No.</th>
                                      <th class="text-center">Customer Name</th>
                                      <th class="text-right">Credit Limit</th>
                                      <th class="text-right">Current Credit Limit</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($creditLimitNotification as $key => $value) { ?>
                                        <tr>
                                          <td class="text-center"><?php echo $key+1; ?></td>
                                          <td class="text-center"><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></td>
                                          <td class="text-right"><?php echo (isset($value['crlimit']) && $value['crlimit'] != '') ? amount_format(number_format($value['crlimit'], 2, '.', '')) : 0; ?></td>
                                          <td class="text-right"><?php echo (isset($value['current_limit']) && $value['current_limit'] != '') ? amount_format(number_format($value['current_limit'], 2, '.', '')) : 0; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>
              <!------------------------ OVER CREDIT LIMIT END ---------------------------------->
              
              <!------------------------ VENDOR ORDER NOTIFICATION START ---------------------------------->
              <?php 
                $vendorOrderNotification = getVendorOrderNotification();
              ?>
              <?php if(isset($vendorOrderNotification) && !empty($vendorOrderNotification)){ ?>
                <div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Vendor Order Notification</h4><hr class="alert-dark">
                      <div class="col mt-3">
                        <div class="row">
                          <div class="col-12">
                            <div class="table-responsive">
                              <table class="table datatable">
                                <thead>
                                  <tr>
                                      <th class="text-center">Sr No.</th>
                                      <th class="text-center">Date</th>
                                      <th class="text-center">Vendor Name</th>
                                      <th class="text-center">Order Type</th>
                                      <th class="text-center">Total Order</th>
                                      <th class="text-center" width="15%">Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($vendorOrderNotification as $key => $value) { ?>
                                        <tr id="TR-V-<?php echo $value['id']; ?>">
                                          <td class="text-center" class="text-center"><?php echo $key+1; ?></td>
                                          <td class="text-center" class="text-center"><?php echo (isset($value['order_date']) ) ? $value['order_date'] : ''; ?></td>
                                          <td class="text-center" class="text-center"><?php echo (isset($value['vendor_name'])) ? $value['vendor_name'] : ''; ?></td>
                                          <td class="text-center" class="text-center"><?php echo (isset($value['type']) && $value['type'] == 1) ? 'Order By Vendor' : 'Order By Product'; ?></td>
                                          <td class="text-center" class="text-center"><?php echo (isset($value['totalorder'])) ? $value['totalorder'] : ''; ?></td>
                                          <td class="text-center" class="text-center">
                                              <div class="col-md-12">
                                                <small class="order-noti-error"></small>
                                              </div>
                                              <div class="col-md-12">
                                                <button class="btn btn-primary p-2 btn-resend-v-order" data-id = "<?php echo $value['id']; ?>">Resend</button>
                                                <button class="btn btn-danger p-2 btn-cancel-v-order" data-id = "<?php echo $value['id']; ?>">Cancel</button>
                                              </div>
                                          </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>
              <!------------------------ VENDOR ORDER NOTIFICATION END ---------------------------------->
              
              <!------------------------ CUSTOMER ORDER NOTIFICATION START ---------------------------------->
              <?php 
                $customerOrderNotification = getCustomerOrderNotification();
              ?>
              <?php if(isset($customerOrderNotification) && !empty($customerOrderNotification)){ ?>
                <div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Customer Order Notification</h4><hr class="alert-dark">
                      <div class="col mt-3">
                        <div class="row">
                          <div class="col-12">
                            <div class="table-responsive">
                              <table class="table datatable">
                                <thead>
                                  <tr>
                                      <th class="text-center">Sr No.</th>
                                      <th class="text-center">Date</th>
                                      <th class="text-center">Customer Name</th>
                                      <th class="text-center">Total Order</th>
                                      <th class="text-center" width="15%">Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($customerOrderNotification as $key => $value) { ?>
                                        <tr id="TR-C-<?php echo $value['id']; ?>">
                                          <td class="text-center"><?php echo $key+1; ?></td>
                                          <td class="text-center"><?php echo (isset($value['order_date']) ) ? $value['order_date'] : ''; ?></td>
                                          <td class="text-center"><?php echo (isset($value['customer_name'])) ? $value['customer_name'] : ''; ?></td>
                                          <td class="text-center"><?php echo (isset($value['totalorder'])) ? $value['totalorder'] : ''; ?></td>
                                          <td class="text-center">
                                              <div class="col-md-12">
                                                <small class="order-noti-error"></small>
                                              </div>
                                              <div class="col-md-12">
                                                <button class="btn btn-primary p-2 btn-resend-c-order" data-id = "<?php echo $value['id']; ?>">Resend</button>
                                                <button class="btn btn-danger p-2 btn-cancel-c-order" data-id = "<?php echo $value['id']; ?>">Cancel</button>
                                              </div>
                                          </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>
              <!------------------------ CUSTOMER ORDER NOTIFICATION END ---------------------------------->
              
              <!------------------------ IPD Prescription NOTIFICATION START ---------------------------------->
              <?php 
                //$IpdPrescriptionData = IpdPrescriptionData($today="1");
              ?>
              <?php //if(isset($IpdPrescriptionData) && !empty($IpdPrescriptionData)){ ?>
              <!--<div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Hospital Patient Prescription</h4><hr class="alert-dark">
                      <div class="col mt-3">
                        <div class="row">
                          <div class="col-12">
                            <div class="table-responsive">
                              <table class="table datatable">
                                <thead>
                                  <tr>
                                      <th class="text-center">Sr No.</th>
                                      <th class="text-center">Patient IPD ID</th>
                                      <th class="text-center">Doctor Name</th>
                                      <th class="text-center">Patient Name</th>
                                      <th class="text-center">Email</th>
                                      <th class="text-center">Mobile No</th>
                                      <th class="text-center">Type</th>
                                      <th class="text-center" width="15%">Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($IpdPrescriptionData as $key => $value) { ?>
                                        <tr title="<?php echo (isset($value['type'])) ? $value['type'] : ''; ?>">
                                          <td class="text-center"><?php echo $key+1; ?></td>
                                          <td class="text-center"><?php echo (isset($value['patient_opd_id']) ) ? $value['patient_opd_id'] : ''; ?></td>
                                          <td class="text-center"><?php echo (isset($value['doctor_name']) ) ? 'Dr.'.$value['doctor_name'] : ''; ?></td>
                                          <td class="text-center"><?php echo (isset($value['p_name']) ) ? $value['p_name'] : ''; ?></td>
                                          <td class="text-center"><?php echo (isset($value['email'])) ? $value['email'] : ''; ?></td>
                                          <td class="text-center"><?php echo (isset($value['mobile_no'])) ? $value['mobile_no'] : ''; ?></td>
                                          <td class="text-center"><?php echo (isset($value['type'])) ? $value['type'] : ''; ?></td>
                                          <td class="text-center">
                                              <a class="btn  btn-behance p-2" title="View Prescription" href="view-prescription.php?id=<?php echo $value['group_id']; ?>&type=<?php echo $value['type']; ?>&doctor_name=<?php echo $value['doctor_name']; ?>"><i class="fa fa-eye mr-0"></i></a>
                                              <?php if(isset($value['is_pharmacy_bill']) && $value['is_pharmacy_bill'] == 1){ ?>
                                                <a href="javascript:void(0);" class="btn  btn-success p-2" title="Bill Already Generated">Bill Generated</a>
                                                <?php
                                                $tax_idQ = "SELECT id FROM `tax_billing` WHERE prec_group='".$value['group_id']."' AND type='".$value['type']."' AND ihis_patient_id='".$value['ihis_patient_id']."'";
                                                $tax_idR = mysqli_query($conn, $tax_idQ);
                                                $tax_idrow = mysqli_fetch_array($tax_idR);
                                                ?>
                                                <a href="print-sales-tax-billing.php?id=<?php echo $tax_idrow['id']; ?>" class="btn btn-primary p-2" title="Print" target="_blank"><i class="fa fa-print mr-0"></i></a>
                                              <?php }else{ ?>
                                                <a class="btn  btn-success p-2" title="Generate Bill" target="_blank" href="sales-tax-billing.php?patient=<?php echo $value['patient_primary_id']; ?>&group=<?php echo $value['group_id']; ?>&doctor=<?php echo (isset($value['doctor_id'])) ? $value['doctor_id'] : ''; ?>&doctormobile=<?php echo (isset($value['doctor_mobile'])) ? $value['doctor_mobile'] : ''; ?>&type=<?php echo $value['type']; ?>&ihis_firm_id=<?php echo (isset($value['ihis_firm_id'])) ? $value['ihis_firm_id'] : ''; ?>&ihis_user_id=<?php echo (isset($value['ihis_user_id'])) ? $value['ihis_user_id'] : ''; ?>&ihis_ipd_id=<?php echo (isset($value['ihis_ipd_id'])) ? $value['ihis_ipd_id'] : ''; ?>&ihis_treatment_by=<?php echo (isset($value['ihis_treatment_by'])) ? $value['ihis_treatment_by'] : ''; ?>&ihis_patient_id=<?php echo (isset($value['ihis_patient_id'])) ? $value['ihis_patient_id'] : ''; ?>&ihis_followup_id=<?php echo (isset($value['ihis_followup_id'])) ? $value['ihis_followup_id'] : ''; ?>&register_type=<?php echo (isset($value['register_type'])) ? $value['register_type'] : ''; ?>&infertility_register_type=<?php echo (isset($value['infertility_register_type'])) ? $value['infertility_register_type'] : ''; ?>"><i class="fa fa-mail-forward mr-0"></i></a>
                                              <?php } ?>
                                          </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>-->
              <?php // } ?>
              <!------------------------ IPD Prescription NOTIFICATION END ---------------------------------->

            <?php } ?>
          </div>
        </div>
        <!-- content-wrapper ends -->
        
        <!-- partial:partials/_footer.php -->
        <?php include "include/footer.php" ?>
        <!-- partial -->
        
        <?php include "popup/set-reminder-model.php"; ?>
        
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
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('.datatable').DataTable();
  </script>
  <script src="js/custom/index.js"></script>
  <script src="js/custom/onlynumber.js"></script>
  <!-- End custom js for this page-->
</body>


</html>
