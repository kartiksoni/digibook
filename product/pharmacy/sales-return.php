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
        if(isset($_GET['id']) && $_GET['id'] != ''){
          $return['credit_note_no'] = (isset($_POST['credit_note_no'])) ? $_POST['credit_note_no'] : '';
        }else{
          $return['credit_note_no'] = getCreditNoteNo();
        }
        $return['credit_note_date'] = (isset($_POST['credit_note_date']) && $_POST['credit_note_date'] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['credit_note_date']))) : '';
        $return['customer_id'] = (isset($_POST['customer_id'])) ? $_POST['customer_id'] : '';
        $return['city_id'] = (isset($_POST['city_id'])) ? $_POST['city_id'] : '';
        $return['remarks'] = (isset($_POST['remarks'])) ? $_POST['remarks'] : '';
        $return['finalamount'] = (isset($_POST['finalamount']) && $_POST['finalamount'] != '') ? $_POST['finalamount'] : 0;
    
        if(isset($_GET['id']) && $_GET['id']){
          $query = "UPDATE sale_return SET ";
        }else{
          $query = "INSERT INTO sale_return SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."',";
        }
    
        foreach ($return as $key => $value) {
           $query .= " ".$key." = '".$value."', ";
        }
    
        if(isset($_GET['id']) && $_GET['id']){
            $query .= "modified = '".date('Y-m-d H:i:s')."', modifiedby = '".$_SESSION['auth']['id']."' ";
            $query .= "WHERE id = '".$_GET['id']."'";
        }else{
          $query .= " created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
        }
        $res = mysqli_query($conn, $query);
        if($res){
          $returnid = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : mysqli_insert_id($conn);
          if($returnid != ''){
              $deleteOldItemQ = "DELETE FROM sale_return_details WHERE sale_return_id = '".$returnid."'";
              mysqli_query($conn, $deleteOldItemQ);
    
              $count = (isset($_POST['product_id']) && !empty($_POST['product_id'])) ? count($_POST['product_id']) : 0;
              if($count > 0){
                for ($i=0; $i < $count; $i++) { 
                  $return_details['sale_return_id'] = $returnid;
                  $return_details['tax_bill_id'] = (isset($_POST['tax_bill_id'][$i])) ? $_POST['tax_bill_id'][$i] : '';
                  $return_details['product_id'] = (isset($_POST['product_id'][$i])) ? $_POST['product_id'][$i] : '';
                  $return_details['mfg_co'] = (isset($_POST['mfg_co'][$i])) ? $_POST['mfg_co'][$i] : '';
                  $return_details['batch'] = (isset($_POST['batch'][$i])) ? $_POST['batch'][$i] : '';
                  $return_details['expiry'] = (isset($_POST['expiry'][$i]) && $_POST['expiry'][$i] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['expiry'][$i]))) : '';
                  $return_details['mrp'] = (isset($_POST['mrp'][$i]) && $_POST['mrp'][$i] != '') ? $_POST['mrp'][$i] : 0;
                  $return_details['qty'] = (isset($_POST['qty'][$i]) && $_POST['qty'][$i] != '') ? $_POST['qty'][$i] : 0;
                  $return_details['rate'] = (isset($_POST['rate'][$i]) && $_POST['rate'][$i] != '') ? $_POST['rate'][$i] : 0;
                  $return_details['qty_ratio'] = (isset($_POST['qty_ratio'][$i]) && $_POST['qty_ratio'][$i] != '') ? $_POST['qty_ratio'][$i] : 0;
                  $return_details['discount'] = (isset($_POST['discount'][$i]) && $_POST['discount'][$i] != '') ? $_POST['discount'][$i] : 0;
                  $return_details['gst'] = (isset($_POST['gst'][$i]) && $_POST['gst'][$i] != '') ? $_POST['gst'][$i] : 0;
                  $return_details['igst'] = (isset($_POST['igst'][$i]) && $_POST['igst'][$i] != '') ? $_POST['igst'][$i] : 0;
                  $return_details['cgst'] = (isset($_POST['cgst'][$i]) && $_POST['cgst'][$i] != '') ? $_POST['cgst'][$i] : 0;
                  $return_details['sgst'] = (isset($_POST['sgst'][$i]) && $_POST['sgst'][$i] != '') ? $_POST['sgst'][$i] : 0;
                  $return_details['gst_tax'] = (isset($_POST['gst_tax'][$i]) && $_POST['gst_tax'][$i] != '') ? $_POST['gst_tax'][$i] : 0;
                  $return_details['amount'] = (isset($_POST['amount'][$i]) && $_POST['amount'][$i] != '') ? $_POST['amount'][$i] : 0;
                  
                  $item = "INSERT INTO sale_return_details SET ";
                  foreach ($return_details as $k => $v) {
                      $item .= " ".$k." = '".$v."', ";
                  }
                  $item .= "created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
                  mysqli_query($conn, $item);
                }
              }
          }
          if(isset($_GET['id']) && $_GET['id'] != ''){
            $_SESSION['msg']['success'] = "Sale Return Updated Successfully.";
          }else{
            $_SESSION['msg']['success'] = "Sale Return Added Successfully.";
          }
          header('Location: view-sales-return.php');exit;
        }else{
          if(isset($_GET['id']) && $_GET['id'] != ''){
            $_SESSION['msg']['fail'] = "Sale Return Updated Fail!";
          }else{
            $_SESSION['msg']['fail'] = "Sale Return Added Fail!";
          }
        }
    }

    if(isset($_GET['id']) && $_GET['id'] != ''){
        $editQ = "SELECT * FROM sale_return WHERE id = '".$_GET['id']."' AND pharmacy_id = '".$pharmacy_id."'";
        $editR = mysqli_query($conn, $editQ);
        if($editR && mysqli_num_rows($editR) > 0){
          $editData = mysqli_fetch_assoc($editR);
    
          $detailQ = "SELECT srd.*, pm.product_name FROM sale_return_details srd LEFT JOIN product_master pm ON srd.product_id = pm.id WHERE srd.sale_return_id = '".$_GET['id']."'";
          $detailR = mysqli_query($conn, $detailQ);
          if($detailR && mysqli_num_rows($detailR) > 0){
            $detail = [];
            while ($detailRow = mysqli_fetch_assoc($detailR)) {
              $detail[] = $detailRow;
            }
            $editData['detail'] = $detail;
          }
        }
    }

  
  
    /*---------------------------SET SALE RETURN DATA START----------------------------*/
    if(isset($_GET['bill']) && $_GET['bill'] != ''){
        $saleQ = "SELECT tb.id, lg.id customer_id, lg.city as city_id FROM tax_billing tb LEFT JOIN ledger_master lg ON tb.customer_id = lg.id WHERE tb.id = '".$_GET['bill']."' AND tb.pharmacy_id = '".$pharmacy_id."'";
        $saleR = mysqli_query($conn, $saleQ);
        if($saleR && mysqli_num_rows($saleR) > 0){
            $saleRow = mysqli_fetch_assoc($saleR);
            $editData['customer_id'] = (isset($saleRow['customer_id'])) ? $saleRow['customer_id'] : '';
            $editData['city_id'] = (isset($saleRow['city_id'])) ? $saleRow['city_id'] : '';
            $editData['finalamount'] = 0;
            
            $detailQ = "SELECT tbd.id, tbd.product_id, pm.product_name, pm.mrp, pm.mfg_company, pm.batch_no, pm.ex_date, tbd.qty, tbd.qty_ratio, tbd.rate, tbd.discount, tbd.gst, tbd.igst, tbd.cgst, tbd.sgst, tbd.gst_tax, tbd.totalamount FROM tax_billing_details tbd LEFT JOIN product_master pm ON tbd.product_id = pm.id WHERE tbd.tax_bill_id = '".$_GET['bill']."'";
            $detailR = mysqli_query($conn, $detailQ);
            if($detailR && mysqli_num_rows($detailR) > 0){
                $detail = [];
                while($detailRow = mysqli_fetch_assoc($detailR)){
                    $tmp['tax_bill_id'] = $_GET['bill'];
                    $tmp['product_id'] = (isset($detailRow['product_id'])) ? $detailRow['product_id'] : '';
                    $tmp['product_name'] = (isset($detailRow['product_name'])) ? $detailRow['product_name'] : '';
                    $tmp['mfg_co'] = (isset($detailRow['mfg_company'])) ? $detailRow['mfg_company'] : '';
                    $tmp['batch'] = (isset($detailRow['batch_no'])) ? $detailRow['batch_no'] : '';
                    $tmp['expiry'] = (isset($detailRow['ex_date'])) ? $detailRow['ex_date'] : '';
                    $tmp['mrp'] = (isset($detailRow['mrp'])) ? $detailRow['mrp'] : '';
                    $tmp['qty'] = (isset($detailRow['qty']) && $detailRow['qty'] != '') ? $detailRow['qty'] : 0;
                    $tmp['qty_ratio'] = (isset($detailRow['qty_ratio']) && $detailRow['qty_ratio'] != '') ? $detailRow['qty_ratio'] : 0;
                    $tmp['rate'] = (isset($detailRow['rate']) && $detailRow['rate'] != '') ? $detailRow['rate'] : 0;
                    $tmp['discount'] = (isset($detailRow['discount']) && $detailRow['discount'] != '') ? $detailRow['discount'] : 0;
                    $tmp['gst'] = (isset($detailRow['gst']) && $detailRow['gst'] != '') ? $detailRow['gst'] : 0;
                    $tmp['igst'] = (isset($detailRow['igst']) && $detailRow['igst'] != '') ? $detailRow['igst'] : 0;
                    $tmp['cgst'] = (isset($detailRow['cgst']) && $detailRow['cgst'] != '') ? $detailRow['cgst'] : 0;
                    $tmp['sgst'] = (isset($detailRow['sgst']) && $detailRow['sgst'] != '') ? $detailRow['sgst'] : 0;
                    $tmp['gst_tax'] = (isset($detailRow['gst_tax']) && $detailRow['gst_tax'] != '') ? $detailRow['gst_tax'] : 0;
                    $tmp['amount'] = (isset($detailRow['totalamount']) && $detailRow['totalamount'] != '') ? $detailRow['totalamount'] : 0;
                    $editData['finalamount'] += $tmp['amount'];
                    $detail[] = $tmp;
                }
                $editData['detail'] = $detail;
            }
        }else{
            $_SESSION['msg']['fail'] = "Invalid Request!";
        }
    }
    /*---------------------------SET SALE RETURN DATA END----------------------------*/
    
    if(isset($editData['city_id']) && $editData['city_id'] != ''){
        $getCustomerQ = "SELECT id, name FROM ledger_master WHERE city = '".$editData['city_id']."' AND pharmacy_id = '".$pharmacy_id."'";
        $getCustomerR = mysqli_query($conn, $getCustomerQ);
        if($getCustomerR && mysqli_num_rows($getCustomerR) > 0){
          $customer = [];
          while ($getCustomerRow = mysqli_fetch_assoc($getCustomerR)) {
            $customer[] = $getCustomerRow;
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
  <title>DigiBooks | <?php echo (isset($_GET['id']) && $_GET['id'] != '') ? 'Edit' : 'Add'; ?> Sales Return</title>
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
            <?php include "include/sale_header.php"; ?>
            <!-- Form -->
            <form method="POST" autocomplete="off">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                      <div class="form-group row">

                        <div class="col-12 col-md-2">
                          <label for="credit_note_no">Credit Note No <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" name="credit_note_no" autocomplete="off" value="<?php echo (isset($editData['credit_note_no'])) ? $editData['credit_note_no'] : getCreditNoteNo(); ?>" required>
                        </div>

                        <div class="col-12 col-md-2">
                          <label for="credit_note_date">Credit Note Date <span class="text-danger">*</span></label>
                          <div class="input-group <?php if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'view') { } else { ?> date datepicker <?php } ?>">
                            <input type="text" class="form-control border" name="credit_note_date" autocomplete="off" value="<?php echo (isset($editData['credit_note_date']) && $editData['credit_note_date'] != '' && $editData['credit_note_date'] != '0000-00-00') ? date('d/m/Y',strtotime($editData['credit_note_date'])) : date('d/m/Y'); ?>" required data-parsley-errors-container="#error-date">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          <span id="error-date"></span>
                        </div>

                        <div class="col-12 col-md-2">
                          <label>Select City <span class="text-danger">*</span></label>
                          <select class="js-example-basic-single" style="width:100%" name="city_id" id="city_id" required data-parsley-errors-container="#error-city_id">
                            <option value="">Select city</option>
                            <?php 
                                $getCustomerCityQ = "SELECT ct.id, ct.name FROM ledger_master lgr INNER JOIN own_cities ct ON lgr.city = ct.id WHERE lgr.group_id = 10 AND lgr.pharmacy_id = '".$pharmacy_id."' GROUP BY lgr.city ORDER BY ct.name";
                                $getCustomerCityR = mysqli_query($conn, $getCustomerCityQ);
                                if($getCustomerCityR && mysqli_num_rows($getCustomerCityR) > 0){
                                  while ($cityRow = mysqli_fetch_array($getCustomerCityR)) {
                              ?>
                                <option value="<?php echo (isset($cityRow['id'])) ? $cityRow['id'] : ''; ?>" <?php echo (isset($editData['city_id']) && $editData['city_id'] == $cityRow['id']) ? 'selected' : ''; ?> ><?php echo (isset($cityRow['name'])) ? $cityRow['name'] : ''; ?></option>
                            <?php } } ?>
                          </select>
                          <span id="error-city_id"></span>
                        </div>
                        
                        <div class="col-12 col-md-2">
                          <label for="customer_id">Select Customer <span class="text-danger">*</span></label>
                          <select class="js-example-basic-single" style="width:100%" name="customer_id" id="customer_id" required data-parsley-errors-container="#error-customer_id"> 
                            <option value="">Select Customer</option>
                            <?php if(!empty($customer)){ ?>
                                <?php foreach ($customer as $key => $value) { ?>
                                  <option value="<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>" <?php echo (isset($editData['customer_id']) && $editData['customer_id'] == $value['id']) ? 'selected' : ''; ?> ><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></option>
                                <?php } ?>
                            <?php } ?>
                          </select>
                          <span id="error-customer_id"></span>
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
                      <div class="row form-group">
                        <div class="col-12">
                          <table class="table">
                            <thead>
                              <tr>
                                  <th width="5%">Sr No</th>
                                  <th width="15%">Product</th>
                                  <th>MFG. Co.</th>
                                  <th>Batch</th>
                                  <th width="9%">Expiry</th>
                                  <th>MRP</th>
                                  <th>Qty.</th>
                                  <th>Discount</th>
                                  <th>GST %</th>
                                  <th>GST</th>
                                  <th>Amount</th>
                                  <th>&nbsp;</th>
                              </tr>
                            </thead>
                            <tbody id="item-tbody">
                              <?php if(isset($editData['detail']) && !empty($editData['detail'])){ ?>
                                <?php foreach ($editData['detail'] as $key => $value) { ?>
                                   <tr>
                                  <td><?php echo ($key+1); ?></td>
                                  <td>
                                    <input type="text" name="product[]" value="<?php echo (isset($value['product_name'])) ? $value['product_name'] : ''; ?>" class="form-control product" placeholder="Product" required>
                                    <small class="product_error text-danger"></small>
                                    <input type="hidden" class="product_id" name="product_id[]" value="<?php echo (isset($value['product_id'])) ? $value['product_id'] : ''; ?>">
                                    <input type="hidden" class="tax_bill_id" name="tax_bill_id[]" value="<?php echo (isset($value['tax_bill_id'])) ? $value['tax_bill_id'] : ''; ?>">
                                  </td>
                                  <td><input type="text" name="mfg_co[]" class="form-control mfg_co" value="<?php echo (isset($value['mfg_co'])) ? $value['mfg_co'] : ''; ?>" placeholder="MFG. Co."></td>
                                  <td><input type="text" name="batch[]" class="form-control batch" value="<?php echo (isset($value['batch'])) ? $value['batch'] : ''; ?>" placeholder="Batch"></td>
                                  <td><input type="text" name="expiry[]" class="form-control expiry datepicker" value="<?php echo (isset($value['expiry']) && $value['expiry'] != '' && $value['expiry'] != '0000-00-00') ? date('d/m/Y',strtotime($value['expiry'])) : ''; ?>" placeholder="Expiry"></td>
                                  <td><input type="text" name="mrp[]" class="form-control mrp onlynumber" value="<?php echo (isset($value['mrp'])) ? $value['mrp'] : ''; ?>" placeholder="MRP" readonly></td>
                                  <td>
                                    <input type="text" name="qty[]" class="form-control qty onlynumber" value="<?php echo (isset($value['qty'])) ? $value['qty'] : ''; ?>" placeholder="Qty." required>
                                    <input type="hidden" name="qty_ratio[]" class="qty_ratio" value="<?php echo (isset($value['qty_ratio'])) ? $value['qty_ratio'] : ''; ?>">
                                    <input type="hidden" name="rate[]" class="rate" value="<?php echo (isset($value['rate'])) ? $value['rate'] : ''; ?>">
                                  </td>
                                  <td><input type="text" name="discount[]" class="form-control discount onlynumber" value="<?php echo (isset($value['discount'])) ? $value['discount'] : ''; ?>" placeholder="Discount(RS)"></td>
                                  <td>
                                    <input type="text" name="gst[]" class="form-control gst onlynumber" value="<?php echo (isset($value['gst'])) ? $value['gst'] : ''; ?>"  placeholder="GST(%)">
                                    <input type="hidden" name="igst[]" class="igst" value="<?php echo (isset($value['igst'])) ? $value['igst'] : 0; ?>">
                                    <input type="hidden" name="cgst[]" class="cgst" value="<?php echo (isset($value['cgst'])) ? $value['cgst'] : 0; ?>">
                                    <input type="hidden" name="sgst[]" class="sgst" value="<?php echo (isset($value['sgst'])) ? $value['sgst'] : 0; ?>">
                                  </td>
                                  <td>
                                    <input type="text" name="gst_tax[]" class="form-control gst_tax onlynumber" placeholder="GST" value="<?php echo (isset($value['gst_tax'])) ? $value['gst_tax'] : 0; ?>" readonly>
                                  </td>
                                  <td><input type="text" name="amount[]" class="form-control amount onlynumber" value="<?php echo (isset($value['amount'])) ? $value['amount'] : 0; ?>" placeholder="0.00" readonly></td>
                                  <td>
                                    <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item"><i class="fa fa-plus mr-0 ml-0"></i></button>
                                    <?php if($key != 0){ ?>
                                      <button type="button" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-item" style=""><i class="fa fa-close mr-0 ml-0"></i></button>
                                    <?php } ?>
                                  </td>
                                </tr>
                                <?php } ?>
                              <?php }else{ ?>
                                <tr>
                                  <td>1</td>
                                  <td>
                                    <input type="text" name="product[]" class="form-control product" placeholder="Product" required>
                                    <small class="product_error text-danger"></small>
                                    <input type="hidden" class="product_id" name="product_id[]">
                                    <input type="hidden" class="tax_bill_id" name="tax_bill_id[]">
                                  </td>
                                  <td><input type="text" name="mfg_co[]" class="form-control mfg_co" placeholder="MFG. Co."></td>
                                  <td><input type="text" name="batch[]" class="form-control batch" placeholder="Batch"></td>
                                  <td><input type="text" name="expiry[]" class="form-control expiry datepicker" placeholder="Expiry"></td>
                                  <td><input type="text" name="mrp[]" class="form-control mrp onlynumber" placeholder="MRP" readonly></td>
                                  <td>
                                    <input type="text" name="qty[]" class="form-control qty onlynumber" placeholder="Qty." required>
                                    <input type="hidden" name="qty_ratio[]" class="qty_ratio" value="0">
                                    <input type="hidden" name="rate[]" class="rate" value="0">
                                  </td>
                                  <td><input type="text" name="discount[]" class="form-control discount onlynumber" placeholder="Discount(%)"></td>
                                  <td>
                                    <input type="text" name="gst[]" class="form-control gst onlynumber" placeholder="GST(%)" readonly>
                                    <input type="hidden" name="igst[]" class="igst" value="0">
                                    <input type="hidden" name="cgst[]" class="cgst" value="0">
                                    <input type="hidden" name="sgst[]" class="sgst" value="0">
                                  </td>
                                  <td><input type="text" name="gst_tax[]" class="form-control gst_tax onlynumber" placeholder="GST" readonly></td>
                                  <td><input type="text" name="amount[]" class="form-control amount onlynumber" placeholder="0.00" readonly required></td>
                                  <td>
                                    <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item"><i class="fa fa-plus mr-0 ml-0"></i></button>
                                  </td>
                                </tr>
                              <?php } ?> 
                            </tbody>
                          </table>
                        </div>

                        <div class="col-md-6">
                          <div class="col-12 col-md-12">
                            <label>Remarks / Reason for Return </label>
                            <textarea class="form-control" name="remarks" id="remarks" rows="3"><?php echo (isset($editData['remarks'])) ? $editData['remarks'] : ''; ?></textarea>
                          </div>
                        </div>
                        <div class="col-md-6" style="padding-top: 30px;">
                          <table class="table table-striped" width="100%">
                            <tbody>
                              <tr>
                                <td width="70%" align="right">Total Amount</td>
                                <td width="30%">
                                  <input type="text" name="finalamount" id="finalamount" class="form-control text-right onlynumber" value="<?php echo (isset($editData['finalamount'])) ? number_format($editData['finalamount'], 2, '.', '') : ''; ?>" placeholder="Total Amount" readonly="">
                                </td>
                              </tr>
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
                          <button type="submit" name="submit" id="btn-update" class="btn btn-success mr-2 pull-right"><?php echo (isset($_GET['id'])) ? 'Update' : 'Save'; ?></button>
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
        <?php  include "popup/show-invoice-no-model.php"; ?>
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
          <input type="hidden" class="product_id" name="product_id[]">
          <input type="hidden" class="tax_bill_id" name="tax_bill_id[]">
        </td>
        <td><input type="text" name="mfg_co[]" class="form-control mfg_co" placeholder="MFG. Co."></td>
        <td><input type="text" name="batch[]" class="form-control batch" placeholder="Batch"></td>
        <td><input type="text" name="expiry[]" class="form-control expiry datepicker" placeholder="Expiry"></td>
        <td><input type="text" name="mrp[]" class="form-control mrp onlynumber" placeholder="MRP" readonly></td>
        <td>
          <input type="text" name="qty[]" class="form-control qty onlynumber" placeholder="Qty." required>
          <input type="hidden" name="qty_ratio[]" class="qty_ratio" value="0">
          <input type="hidden" name="rate[]" class="rate" value="0">
        </td>
        <td><input type="text" name="discount[]" class="form-control discount onlynumber" placeholder="Discount(%)"></td>
        <td>
          <input type="text" name="gst[]" class="form-control gst onlynumber" placeholder="GST(%)" readonly>
          <input type="hidden" name="igst[]" class="igst" value="0">
          <input type="hidden" name="cgst[]" class="cgst" value="0">
          <input type="hidden" name="sgst[]" class="sgst" value="0">
        </td>
        <td><input type="text" name="gst_tax[]" class="form-control gst_tax onlynumber" placeholder="GST" readonly></td>
        <td><input type="text" name="amount[]" class="form-control amount onlynumber" placeholder="0.00" readonly required></td>
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

 
  <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>

<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>
  
  <?php 
  if(isset($_GET['type']) && $_GET['type'] == "view"){
      
      ?>
      <script>
          $('input[type="text"]').prop('readonly', true);
          $('textarea').prop('readonly', true);
          $('.datepicker').prop('disabled', 'disabled');
          $('select').prop('disabled', 'disabled');
          $('#btn-update').hide();
          $('.btn-add-more-item').hide();
      </script>
      <?php 
  }
  ?>
 
</body>
</html>
