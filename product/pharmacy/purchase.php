<?php $title = "Purchase Bill"; ?>
<?php include('include/usertypecheck.php');
include('include/permission.php');

    $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
    $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
    $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

    /*------------GET ALL TRANSPORT NAME START-------------*/
    $allTransport = [];
    $getTransportQ = "SELECT id, name, t_code FROM transport_master WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 ORDER BY name";
    $getTransportR = mysqli_query($conn, $getTransportQ);
    if($getTransportR && mysqli_num_rows($getTransportR) > 0){
        while($getTransportRow = mysqli_fetch_assoc($getTransportR)){
            $allTransport[] = $getTransportRow;
        }
    }
    /*------------GET ALL TRANSPORT NAME START-------------*/

    if(isset($_GET['id']) && $_GET['id'] != ''){
      $purchaseQ = "SELECT * FROM `purchase` WHERE id='".$_GET['id']."' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."'";
      $purchaseR = mysqli_query($conn,$purchaseQ);
      if($purchaseR && mysqli_num_rows($purchaseR) > 0){
        $editData = mysqli_fetch_assoc($purchaseR);

        $purchaseDetailQ = "SELECT pd.*, pm.product_name FROM purchase_details pd LEFT JOIN product_master pm ON pd.product_id = pm.id WHERE pd.purchase_id = '".$_GET['id']."'";
        $purchaseDetailR = mysqli_query($conn, $purchaseDetailQ);
        if($purchaseDetailR && mysqli_num_rows($purchaseDetailR) > 0){
          while ($purchaseDetailRow = mysqli_fetch_assoc($purchaseDetailR)) {
            $editData['detail'][] = $purchaseDetailRow;
          }
        }
      }else{
        $_SESSION['msg']['fail'] = "Invalid Request!!";
        header('Location: purchase.php');exit;
      }
    }
?>

<?php
  if(isset($_POST['save']) || isset($_POST['saveAndNext'])){
    $purchase['vouchar_date'] = (isset($_POST['vouchar_date']) && $_POST['vouchar_date'] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['vouchar_date']))) : '';
    if(isset($_GET['id']) && $_GET['id'] != ''){
      $purchase['voucher_no'] = (isset($_POST['voucher_no'])) ? $_POST['voucher_no'] : '';
    }else{
      $purchase['voucher_no'] = getpurchaseinvoiceno((isset($_POST['purchase_type'])) ? $_POST['purchase_type'] : '');
    }
    $purchase['city'] = (isset($_POST['city'])) ? $_POST['city'] : '';
    $purchase['vendor'] = (isset($_POST['vendor'])) ? $_POST['vendor'] : '';
    $purchase['doctor'] = (isset($_POST['doctor'])) ? $_POST['doctor'] : '';
    $purchase['statecode'] = (isset($_POST['statecode'])) ? $_POST['statecode'] : '';
    $purchase['invoice_date'] = (isset($_POST['invoice_date']) && $_POST['invoice_date'] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['invoice_date']))) : '';
    $purchase['invoice_no'] = (isset($_POST['invoice_no'])) ? $_POST['invoice_no'] : '';
    $purchase['lr_no'] = (isset($_POST['lr_no'])) ? $_POST['lr_no'] : '';
    $purchase['lr_date'] = (isset($_POST['lr_date']) && $_POST['lr_date'] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['lr_date']))) : '';
    $purchase['transporter_name'] = (isset($_POST['transporter_name'])) ? $_POST['transporter_name'] : '';
    $purchase['purchase_type'] = (isset($_POST['purchase_type'])) ? $_POST['purchase_type'] : '';
    $purchase['total_amount'] = (isset($_POST['total_amount']) && $_POST['total_amount'] != '') ? $_POST['total_amount'] : 0;
    $purchase['courier'] = (isset($_POST['courier']) && $_POST['courier'] != '') ? $_POST['courier'] : 0;
    $purchase['total_courier'] = (isset($_POST['total_courier']) && $_POST['total_courier'] != '') ? $_POST['total_courier'] : 0;
    $purchase['total_tax'] = (isset($_POST['total_tax']) && $_POST['total_tax'] != '') ? $_POST['total_tax'] : 0;
    $purchase['total_igst'] = (isset($_POST['total_igst']) && $_POST['total_igst'] != '') ? $_POST['total_igst'] : 0;
    $purchase['total_cgst'] = (isset($_POST['total_cgst']) && $_POST['total_cgst'] != '') ? $_POST['total_cgst'] : 0;
    $purchase['total_sgst'] = (isset($_POST['total_sgst']) && $_POST['total_sgst'] != '') ? $_POST['total_sgst'] : 0;
    $purchase['per_discount'] = (isset($_POST['per_discount']) && $_POST['per_discount'] != '') ? $_POST['per_discount'] : 0;
    $purchase['rs_discount'] = (isset($_POST['rs_discount']) && $_POST['rs_discount'] != '') ? $_POST['rs_discount'] : 0;
    $purchase['overall_value'] = (isset($_POST['overall_value']) && $_POST['overall_value'] != '') ? $_POST['overall_value'] : 0;
    $purchase['note_details'] = (isset($_POST['note_details']) && $_POST['note_details'] != '') ? $_POST['note_details'] : 0;
    $purchase['note_details'] = (isset($_POST['note_details']) && $_POST['note_details'] != '') ? $_POST['note_details'] : '';
    $purchase['note_value'] = (isset($_POST['note_value']) && $_POST['note_value'] != '') ? $_POST['note_value'] : 0;
    $purchase['purchase_amount'] = (isset($_POST['purchase_amount']) && $_POST['purchase_amount'] != '') ? $_POST['purchase_amount'] : 0;
    $purchase['round_off'] = (isset($_POST['round_off']) && $_POST['round_off'] != '') ? $_POST['round_off'] : 0;
    $purchase['total_total'] = (isset($_POST['total_total']) && $_POST['total_total'] != '') ? $_POST['total_total'] : 0;

    if(isset($_GET['id']) && $_GET['id']){
      $query = "UPDATE purchase SET ";
    }else{
      $query = "INSERT INTO purchase SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."',";
    }

    foreach ($purchase as $key => $value) {
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
      $purchaseid = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : mysqli_insert_id($conn);
      if($purchaseid != ''){
        $deleteOldItemQ = "DELETE FROM purchase_details WHERE purchase_id = '".$purchaseid."'";
        mysqli_query($conn, $deleteOldItemQ);

        $count = (isset($_POST['product_id']) && !empty($_POST['product_id'])) ? count($_POST['product_id']) : 0;
        if($count > 0){
          for ($i=0; $i < $count; $i++) {
            $purchaseDetail['purchase_id'] = $purchaseid;
            $purchaseDetail['product_id'] = (isset($_POST['product_id'][$i])) ? $_POST['product_id'][$i] : '';
            $purchaseDetail['mrp'] = (isset($_POST['mrp'][$i]) && $_POST['mrp'][$i] != '') ? $_POST['mrp'][$i] : 0;
            $purchaseDetail['mfg_co'] = (isset($_POST['mfg_co'][$i]) && $_POST['mfg_co'][$i] != '') ? $_POST['mfg_co'][$i] : 0;
            $purchaseDetail['batch'] = (isset($_POST['batch'][$i]) && $_POST['batch'][$i] != '') ? $_POST['batch'][$i] : 0;
            $purchaseDetail['expiry'] = (isset($_POST['expiry'][$i]) && $_POST['expiry'][$i] != '' && $_POST['expiry'][$i] != '-' && $_POST['expiry'][$i] != '0000-00-00') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['expiry'][$i]))) : '';
            $purchaseDetail['qty'] = (isset($_POST['qty'][$i]) && $_POST['qty'][$i] != '') ? $_POST['qty'][$i] : 0;
            $purchaseDetail['qty_ratio'] = (isset($_POST['qty_ratio'][$i]) && $_POST['qty_ratio'][$i] != '') ? $_POST['qty_ratio'][$i] : 0;
            $purchaseDetail['free_qty'] = (isset($_POST['free_qty'][$i]) && $_POST['free_qty'][$i] != '') ? $_POST['free_qty'][$i] : 0;
            $purchaseDetail['rate'] = (isset($_POST['rate'][$i]) && $_POST['rate'][$i] != '') ? $_POST['rate'][$i] : 0;
            $purchaseDetail['discount'] = (isset($_POST['discount'][$i]) && $_POST['discount'][$i] != '') ? $_POST['discount'][$i] : 0;
            $purchaseDetail['f_rate'] = (isset($_POST['f_rate'][$i]) && $_POST['f_rate'][$i] != '') ? $_POST['f_rate'][$i] : 0;
            $purchaseDetail['ammout'] = (isset($_POST['ammout'][$i]) && $_POST['ammout'][$i] != '') ? $_POST['ammout'][$i] : 0;
            $purchaseDetail['f_igst'] = (isset($_POST['f_igst'][$i]) && $_POST['f_igst'][$i] != '') ? $_POST['f_igst'][$i] : 0;
            $purchaseDetail['f_cgst'] = (isset($_POST['f_cgst'][$i]) && $_POST['f_cgst'][$i] != '') ? $_POST['f_cgst'][$i] : 0;
            $purchaseDetail['f_sgst'] = (isset($_POST['f_sgst'][$i]) && $_POST['f_sgst'][$i] != '') ? $_POST['f_sgst'][$i] : 0;
            $created = (isset($_POST['created'][$i]) && $_POST['created'][$i] != '' && $_POST['created'][$i] != '0000-00-00 00:00:00') ? date('Y-m-d H:i:s',strtotime($_POST['created'][$i])) : date('Y-m-d H:i:s');

            $duplicateProduct = duplicateProduct($purchaseDetail['product_id'], $purchaseDetail['mrp'], $purchaseDetail['batch'], $purchaseDetail['expiry']);
            if((isset($duplicateProduct['status']) && $duplicateProduct['status'] == 1) && (isset($duplicateProduct['id']) && $duplicateProduct['id'] != '')){
              $purchaseDetail['product_id'] = $duplicateProduct['id'];
            }

            $item = "INSERT INTO purchase_details SET ";
            foreach ($purchaseDetail as $k => $v) {
                $item .= " ".$k." = '".$v."', ";
            }
            $item .= "created = '".$created."', createdby = '".$_SESSION['auth']['id']."'";
            mysqli_query($conn, $item);
          }
        }
      }

      if(isset($_GET['id']) && $_GET['id'] != ''){
        $_SESSION['msg']['success'] = "Purchase Bill Updated Successfully.";
      }else{
        $_SESSION['msg']['success'] = "Purchase Bill Added Successfully.";
      }

      if(isset($_POST['save'])){
          header('Location: view-purchase.php');exit;
      }elseif(isset($_POST['saveAndNext'])){
          header('Location: purchase.php');exit;
      }else{
          header('Location: view-purchase.php');exit;
      }
    }else{
      if(isset($_GET['id']) && $_GET['id'] != ''){
        $_SESSION['msg']['fail'] = "Purchase Bill Updated Fail!";
      }else{
        $_SESSION['msg']['fail'] = "Purchase Bill Added Fail!";
      }
    }
  }

  function duplicateProduct($id = null, $mrp = 0, $batch = '', $expiry = ''){
    global $conn;
    global $pharmacy_id;
    $data['id'] = '';
    $data['status'] = 0;

    $getProductQ = "SELECT id, mrp, batch_no, ex_date FROM product_master WHERE id = '".$id."'";
    $getProductR = mysqli_query($conn, $getProductQ);
    if($getProductR && mysqli_num_rows($getProductR) > 0){
      $getProductRow = mysqli_fetch_assoc($getProductR);
      $product_mrp = (isset($getProductRow['mrp']) && $getProductRow['mrp'] != '') ? $getProductRow['mrp'] : 0;
      $product_batch = (isset($getProductRow['batch_no'])) ? $getProductRow['batch_no'] : '';
      $product_expiry = (isset($getProductRow['ex_date']) && $getProductRow['ex_date'] != '' && $getProductRow['ex_date'] != '0000-00-00') ? $getProductRow['ex_date'] : '';

      if(($product_mrp == 0 && $mrp != 0) || ($product_batch == '' && $batch != '') || ($product_expiry == '' && $expiry != '')){
        // update field
        $updateQ = "UPDATE product_master SET mrp = '".$mrp."', batch_no = '".$batch."', ex_date = '".$expiry."' WHERE id = '".$id."'";
        $updateR = mysqli_query($conn, $updateQ);
      }elseif($product_mrp != $mrp || $product_batch != $batch || $product_expiry != $expiry){
        $duplicateQ = "INSERT INTO product_master (`owner_id`, `admin_id`, `pharmacy_id`, `user_id`, `finance_year_id`, `product_code`, `product_name`, `generic_name`, `mfg_company`, `schedule_cat`, `product_type`, `product_cat`, `sub_cat`, `hsn_code`, `batch_no`, `ex_date`, `opening_qty`, `opening_qty_godown`, `give_mrp`, `mrp`, `serial_no`, `gst_id`, `igst`, `cgst`, `sgst`, `inward_rate`, `sale_rate_local`, `sale_rate_out`, `rack_no`, `self_no`, `box_no`, `company_code`, `opening_stock`, `unit`, `min_qty`, `max_qty`, `discount`, `discount_per`, `ratio`, `minqty_flag`, `minqty_noti_flag`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`) SELECT `owner_id`, `admin_id`, `pharmacy_id`, `user_id`, `finance_year_id`, `product_code`, `product_name`, `generic_name`, `mfg_company`, `schedule_cat`, `product_type`, `product_cat`, `sub_cat`, `hsn_code`, '".$batch."' as `batch_no`, '".$expiry."' as `ex_date`, `opening_qty`, `opening_qty_godown`, `give_mrp`, '".$mrp."' as `mrp`, `serial_no`, `gst_id`, `igst`, `cgst`, `sgst`, `inward_rate`, `sale_rate_local`, `sale_rate_out`, `rack_no`, `self_no`, `box_no`, `company_code`, `opening_stock`, `unit`, `min_qty`, `max_qty`, `discount`, `discount_per`, `ratio`, `minqty_flag`, `minqty_noti_flag`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by` FROM product_master WHERE id='".$id."'";
        $duplicateR = mysqli_query($conn, $duplicateQ);
        if($duplicateR){
          $data['id'] = mysqli_insert_id($conn);
          $data['status'] = 1;
        }
      }
    }
    return $data;
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks | <?php echo (isset($_GET['id']) && $_GET['id'] != '') ? 'Edit' : 'Add'; ?> Purchase Bill</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/iconfonts/puse-icons-feather/feather.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
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
          <form action="" method="POST" autocomplete="off">
            <div class="row">
                <?php include "include/purchase_header.php"; ?>
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                      <div class="form-group row">
                    
                        <div class="col-12 col-md-2">
                          <label for="vouchar_date">Voucher Date<span class="text-danger">*</span></label>
                              <div class="input-group date datepicker">
                              <input type="text" name="vouchar_date" data-parsley-errors-container="#error-vochar-date" class="form-control border" value="<?php echo (isset($editData['vouchar_date']) && $editData['vouchar_date'] != '' && $editData['vouchar_date'] != '0000-00-00') ? date('d/m/Y',strtotime($editData['vouchar_date'])) : date('d/m/Y'); ?>" required>
                              <span class="input-group-addon input-group-append border-left">
                                <span class="mdi mdi-calendar input-group-text"></span>
                              </span>
                            </div>
                            <span id="error-vochar-date"></span>
                        </div>
                        
                        <div class="col-12 col-md-2">
                          <label for="voucher_no">Voucher No<span class="text-danger">*</span></label>
                          <input type="text" required="" <?php if(isset($_GET['id'])){echo"readonly";} ?> class="form-control" name="voucher_no" id="voucher_no" value="<?php echo (isset($editData['voucher_no'])) ? $editData['voucher_no'] : getpurchaseinvoiceno('Debit'); ?>" placeholder="Voucher No">
                        </div>
                        
                        <div class="col-12 col-md-2">
                          <label for="city">Select City<span class="text-danger">*</span></label>
                          <select class="js-example-basic-single" style="width:100%" data-parsley-errors-container="#error-city" required="" name="city" id="city"> 
                                  <option value="">Select City</option>
                                  <?php
                                    $getAllCityQuery = "SELECT ct.id, ct.name FROM ledger_master lg INNER JOIN own_cities ct ON lg.city = ct.id WHERE lg.group_id = 14 AND lg.pharmacy_id = '".$pharmacy_id."' GROUP BY lg.city ORDER BY ct.name";
                                    $getAllCityRes = mysqli_query($conn, $getAllCityQuery);
                                    if($getAllCityRes && mysqli_num_rows($getAllCityRes) > 0){
                                      while ($rowofcity = mysqli_fetch_array($getAllCityRes)) {
                                          
                                  ?>
                                    <option value="<?php echo $rowofcity['id']; ?>" <?php echo (isset($editData['city']) && $editData['city'] == $rowofcity['id']) ? 'selected' : ''; ?> > <?php echo $rowofcity['name']; ?> </option>
                                  <?php
                                      }
                                    }
                                  ?>
                              </select>
                              <span id="error-city"></span>
                        </div>
                        
                        <div class="col-12 col-md-2">
                          <label for="vendor">Select Vendor<span class="text-danger">*</span></label>
                          <select class="js-example-basic-single" data-parsley-errors-container="#error-vendor" required="" style="width:100%" name="vendor" id="vendor"> 
                              <option value="">Select Vendor</option>
                              <?php 
                                if(isset($editData['city']) && $editData['city'] != ''){
                                  $cityQ = 'SELECT id, name FROM ledger_master WHERE city = '.$editData['city'].' AND pharmacy_id = '.$pharmacy_id.' AND status=1 AND group_id=14 order by name';
                                  $cityR = mysqli_query($conn,$cityQ);
                                  if($cityR && mysqli_num_rows($cityR) > 0){
                                    while ($cityRow = mysqli_fetch_array($cityR)) {
                              ?>
                                  <option value="<?php echo (isset($cityRow['id'])) ? $cityRow['id'] : ''; ?>" <?php echo (isset($editData['vendor']) && $editData['vendor'] == $editData['vendor']) ? 'selected' : ''; ?> ><?php echo (isset($cityRow['name'])) ? $cityRow['name'] : ''; ?></option>
                              <?php
                                    }
                                  }
                                }
                              ?>
                          </select>
                          <span id="error-vendor"></span>
                          <i class="fa fa-spin fa-refresh vendor-loader display-none" style="position: absolute;top: 40px;right: 40px;"></i>
                          <input type="hidden" value="<?php echo (isset($editData['statecode'])) ? $editData['statecode'] : ''; ?>" name="statecode" id="statecode">
                        </div>
                        
                        <!--<div class="col-12 col-md-2">
                            <label for="doctor">Select Doctor</label>
                            <select class="js-example-basic-single" style="width:100%" name="doctor" id="doctor"> 
                                <option value="">Select Doctor</option>
                                <?php //if(isset($purchaseDoctor) && !empty($purchaseDoctor)){ ?>
                                    <?php //foreach($purchaseDoctor as $key => $value){ ?>
                                        <option value="<?php //echo $value['id']; ?>" <?php //echo (isset($purchase_data['doctor']) && $purchase_data['doctor'] == $value['id']) ? 'selected' : ''; ?> ><?php //echo $value['name']; ?></option>
                                    <?php //} ?>
                                <?php //} ?>
                            </select>
                        </div>-->
                      
                        <div class="col-12 col-md-2">
                            <button type="button" class="btn btn-primary mt-30" data-toggle="modal" data-target="#purchase-addvendormodel" data-whatever="@mdo"><i class="fa fa-plus"></i> Add New Vendor</button>
                        </div>
                        <div class="col-12 col-md-2">
                            <button type="button" class="btn btn-primary pull-right mt-30" data-toggle="modal" data-target="#purchase-addproductmodel" data-whatever="@mdo"><i class="fa fa-plus"></i> Add New Product </button>
                        </div>
                      </div> 
                    
                    
                      <div class="form-group row">
                          <div class="col-12 col-md-2">
                            <label for="invoice_date">Invoice Date<span class="text-danger">*</span></label>
                            <div class="input-group date datepicker ">
                              <input type="text" name="invoice_date" class="form-control border" value="<?php echo (isset($editData['invoice_date']) && $editData['invoice_date'] != '' && $editData['invoice_date'] != '0000-00-00') ? date('d/m/Y',strtotime($editData['invoice_date'])) : date('d/m/Y'); ?>" required>
                              <span class="input-group-addon input-group-append border-left">
                                <span class="mdi mdi-calendar input-group-text"></span>
                              </span>
                            </div>
                          </div>
                          <div class="col-12 col-md-2">
                            <label for="invoice_no">Invoice No.<span class="text-danger">*</span></label>
                            <input type="text" name="invoice_no"  class="form-control"  value="<?php echo (isset($editData['invoice_no'])) ? $editData['invoice_no'] : ''; ?>" placeholder="Invoice No" required>
                          </div>
                          <div class="col-12 col-md-2">
                            <label for="lr_no">LR No</label>
                            <input type="text" name="lr_no" value="<?php echo (isset($editData['lr_no'])) ? $editData['lr_no'] : ''; ?>" class="form-control" placeholder="LR No">
                          </div>
                          <div class="col-12 col-md-2">
                            <label for="lr_date">LR Date</label>
                            <div class="input-group date datepicker">
                              <input type="text" name="lr_date" class="form-control border" value="<?php echo (isset($editData['lr_date']) && $editData['lr_date'] != '' && $editData['lr_date'] != '0000-00-00') ? date('d/m/Y',strtotime($editData['lr_date'])) : ''; ?>" placeholder="dd/mm/yyyy">
                              <span class="input-group-addon input-group-append border-left">
                                <span class="mdi mdi-calendar input-group-text"></span>
                              </span>
                            </div>
                          </div>
                          <div class="col-12 col-md-2">
                              <label for="transporter_name">Transporter Name</label>
                              <select class="js-example-basic-single" style="width:100%" name="transporter_name" id="transporter_name"> 
                                  <option value="">Select Transport</option>
                                  <?php if(isset($allTransport) && !empty($allTransport)){ ?>
                                      <?php foreach($allTransport as $key => $value){ ?>
                                          <option value="<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>" <?php echo (isset($editData['transporter_name']) && $editData['transporter_name'] == $value['id']) ? 'selected' : ''; ?> ><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></option>
                                      <?php } ?>
                                  <?php } ?>
                              </select>
                          </div>
                          <div class="col-12 col-md-2">
                              <button type="button" class="btn btn-primary mt-30" data-toggle="modal" data-target="#add-transport-model" data-whatever="@mdo" style="padding-right:7px;"><i class="fa fa-plus"></i> Add New Transport</button>
                         </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Purchase Type  </label>
                          <div class="row no-gutters">
                            <div class="col">
                                <div class="form-radio">
                                <label class="form-check-label">
                                <input type="radio" class="form-check-input purchase_type" name="purchase_type" value="Cash" <?php echo (isset($editData['purchase_type']) && $editData['purchase_type'] == 'Cash') ? 'checked' : ''; ?> >
                               Cash
                                </label>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-radio">
                                <label class="form-check-label">
                                <input type="radio" class="form-check-input purchase_type" name="purchase_type" value="Debit" <?php echo (isset($editData['purchase_type']) && $editData['purchase_type'] == 'Debit') ? 'checked' : ''; ?>  <?php echo (!isset($_GET['id'])) ? 'checked' : ''; ?> >
                                Debit
                                </label>
                                </div>
                            </div>
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
                      <div class="col">
                         <div class="row">
                             <div class="col-12">
                                <label class="pull-right purchase-rate-lable" style="display: none;color: #000000; font-size: 12px; padding: 2px; background-color: #DCDCDC; border: 1px solid #A9A9A9;"></label>
                            </div>
                            <div class="col-12">
                                <div class="add_show" style="display: none;">
                                  <a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product pull-right add_show"><i class="fa fa-plus mr-0 ml-0"></i></a>
                                </div>
                              <table id="order-listing1" class="table">
                                <thead>
                                  <tr>
                                      <th>Sr No</th>
                                      <th width="15%">Product</th>
                                      <th>MRP</th>
                                      <th>MFG. Co.</th>
                                      <th>Batch</th>
                                      <th>Expiry</th>
                                      <th>Qty.</th>
                                      <th>Free Qty</th>
                                      <th>Rate</th>
                                      <th>Discount</th>
                                      <th>Rate</th>
                                      <th>Amount</th>
                                      <th>&nbsp;</th>
                                  </tr>
                                </thead>
                                <tbody id="product-tbody">
                                  <?php if(isset($editData['detail']) && !empty($editData['detail'])){ ?>
                                      <?php foreach ($editData['detail'] as $key => $value) { ?>
                                        <tr class="product-tr">
                                          <td>1</td>
                                          <td>
                                            <input type="text" placeholder="Product" class="tags form-control" name="product[]" value="<?php echo (isset($value['product_name'])) ? $value['product_name'] : ''; ?>" required>
                                            <input type="hidden" class="product-id" name="product_id[]" value="<?php echo (isset($value['product_id'])) ? $value['product_id'] : ''; ?>">
                                            <small class="text-danger empty-message"></small>
                                          </td>
                                          <td>
                                            <input type="text" name="mrp[]" value="<?php echo (isset($value['mrp'])) ? $value['mrp'] : ''; ?>" class="form-control mrp onlynumber" placeholder="MRP" autocomplete="off">
                                          </td>
                                          <td>
                                            <input type="text" name="mfg_co[]" value="<?php echo (isset($value['mfg_co'])) ? $value['mfg_co'] : ''; ?>" class="form-control mrp onlynumber" class="form-control mfg_co" placeholder="MFG. Co." autocomplete="off">
                                          </td>
                                          <td>
                                            <input type="text" name="batch[]" value="<?php echo (isset($value['batch'])) ? $value['batch'] : ''; ?>" class="form-control batch" placeholder="Batch" autocomplete="off">
                                          </td>
                                          <td>
                                            <input type="text" name="expiry[]" value="<?php echo (isset($value['expiry']) && $value['expiry'] != '' && $value['expiry'] != '0000-00-00') ? date('d/m/Y',strtotime($value['expiry'])) : ''; ?>" class="form-control datepicker-ex expiry" style="width: 80px;" placeholder="Expiry" autocomplete="off">
                                            <small class="text-danger expired"></small>
                                          </td>
                                          <td>
                                            <input type="text" name="qty[]" value="<?php echo (isset($value['qty'])) ? $value['qty'] : ''; ?>" class="form-control qty onlynumber" placeholder="Qty." required>
                                            <input type="hidden" class="qty-value" name="qty_ratio[]" autocomplete="off">
                                          </td>
                                          <td>
                                            <input type="text" name="free_qty[]" value="<?php echo (isset($value['free_qty'])) ? $value['free_qty'] : ''; ?>" class="form-control free_qty onlynumber" placeholder="Free Qty" autocomplete="off">
                                          </td>
                                          <td>
                                            <input type="text" name="rate[]" value="<?php echo (isset($value['rate'])) ? $value['rate'] : ''; ?>" class="form-control rate onlynumber" placeholder="Rate" autocomplete="off">
                                          </td>
                                          <td>
                                            <input type="text" name="discount[]" value="<?php echo (isset($value['discount'])) ? $value['discount'] : ''; ?>" class="form-control discount onlynumber" placeholder="Discount" autocomplete="off">
                                          </td>
                                          <td>
                                            <input type="text" name="f_rate[]" value="<?php echo (isset($value['f_rate'])) ? $value['f_rate'] : ''; ?>" class="form-control f_rate onlynumber" placeholder="Rate" autocomplete="off" required>
                                          </td>
                                          <td>
                                            <input type="text" name="ammout[]" value="<?php echo (isset($value['ammout'])) ? $value['ammout'] : ''; ?>" class="form-control ammout onlynumber" placeholder="Ammount" autocomplete="off">
                                            <input type="hidden" name="f_igst[]" value="<?php echo (isset($value['f_igst'])) ? $value['f_igst'] : ''; ?>" class="f_igst">
                                            <input type="hidden" name="f_cgst[]" value="<?php echo (isset($value['f_cgst'])) ? $value['f_cgst'] : ''; ?>" class="f_cgst">
                                            <input type="hidden" name="f_sgst[]" value="<?php echo (isset($value['f_sgst'])) ? $value['f_sgst'] : ''; ?>" class="f_sgst">
                                            
                                            <input type="hidden" name="created[]" value="<?php echo (isset($value['created']) && $value['created'] != '' && $value['created'] != '0000-00-00 00:00:00') ? $value['created'] : ''; ?>">
                                          </td>
                                          <td>
                                            <a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a>
                                            <a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product remove_last" style="display: none;"><i class="fa fa-close mr-0 ml-0"></i></a>
                                          </td>
                                        </tr>
                                      <?php } ?>
                                  <?php }else{ ?>
                                      <tr class="product-tr">
                                        <td>1</td>
                                        <td>
                                          <input type="text" placeholder="Product" class="tags form-control" required="" name="product[]">
                                          <input type="hidden" class="product-id" name="product_id[]">
                                          <small class="text-danger empty-message"></small>
                                        </td>
                                        <td>
                                          <input type="text" name="mrp[]" class="form-control mrp onlynumber" placeholder="MRP" autocomplete="off">
                                        </td>
                                        <td>
                                          <input type="text" name="mfg_co[]" class="form-control mfg_co" placeholder="MFG. Co." autocomplete="off">
                                        </td>
                                        <td>
                                          <input type="text" name="batch[]" class="form-control batch" placeholder="Batch" autocomplete="off">
                                        </td>
                                        <td>
                                          <input type="text" name="expiry[]" class="form-control datepicker-ex expiry" style="width: 80px;" placeholder="Expiry" autocomplete="off">
                                          <small class="text-danger expired"></small>
                                        </td>
                                        <td>
                                          <input type="text" name="qty[]" class="form-control qty onlynumber" placeholder="Qty." required>
                                          <input type="hidden" class="qty-value" name="qty_ratio[]" autocomplete="off">
                                        </td>
                                        <td>
                                          <input type="text" name="free_qty[]" class="form-control free_qty onlynumber" placeholder="Free Qty" autocomplete="off">
                                        </td>
                                        <td>
                                          <input type="text" name="rate[]" class="form-control rate onlynumber" placeholder="Rate" autocomplete="off">
                                        </td>
                                        <td>
                                          <input type="text" name="discount[]" class="form-control discount onlynumber" placeholder="Discount" autocomplete="off">
                                        </td>
                                        <td>
                                          <input type="text" name=f_rate[] class="form-control f_rate onlynumber" placeholder="Rate" autocomplete="off" required>
                                        </td>
                                        <td>
                                          <input type="text" name=ammout[] class="form-control ammout onlynumber" placeholder="Ammount" autocomplete="off">
                                          <input type="hidden" name="f_igst[]" class="f_igst">
                                          <input type="hidden" name="f_cgst[]" class="f_cgst">
                                          <input type="hidden" name="f_sgst[]" class="f_sgst">
                                        </td>
                                        <td>
                                          <a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a>
                                          <a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product remove_last" style="display: none;"><i class="fa fa-close mr-0 ml-0"></i></a>
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
                        <div class="row">
                            <div class="col-md-4 offset-8">
                                <div class="form-group row">
                                  <table class="table table-striped">
                                    <tbody>
                                      <tr>
                                        <td align="right" style="width:100px;">
                                          Total
                                        </td>
                                        <td align="right"><input class="form-control" type="text" name="total_amount" id="total_amount" value="<?php echo (isset($editData['total_amount'])) ? $editData['total_amount'] : ''; ?>" readonly="">
                                         
                                        </td>
                                      </tr>
                                      <tr>
                                        <td align="right">
                                         
                                         <div class="input-group">
                                            <input type="text" name="per_discount" class="form-control priceOnly onlynumber" id="per_discount" placeholder="%" value="<?php echo (isset($editData['per_discount'])) ? $editData['per_discount'] : ''; ?>" style="display:inline-block;width:80px;">
                                            <div class="input-group-append">
                                              <span class="input-group-text"><i class="fa fa-percent"></i></span>
                                            </div>
                                          </div>
                                        </td>
                                        <td align="right">
                                            <div class="input-group">
                                            <input type="text" name="rs_discount" class="form-control priceOnly onlynumber" id="rs_discount" placeholder="Rs." value="<?php echo (isset($editData['rs_discount'])) ? $editData['rs_discount'] : ''; ?>" style="display:inline-block;width:80px;">
                                            <div class="input-group-append">
                                              <span class="input-group-text"><i class="fa fa-rupee"></i></span>
                                            </div>
                                          </div>    
                                        </td>
                                        
                                      </tr>
                                      <tr>
                                        <td align="right">
                                          <select class="form-control" name="courier" id="courier_charge" style="width:250px;">
                                                <option value="">Freight/Courier Charge </option>
                                                <?php if(isset($purchaseCourierCharge) && !empty($purchaseCourierCharge)){ ?>
                                                  <?php foreach($purchaseCourierCharge as $key => $value){ ?>
                                                      <option value="<?php echo $value; ?>" <?php echo (isset($editData['courier']) && $editData['courier'] == $value) ? 'selected' : ''; ?> ><?php echo $value; ?> %</option>
                                                  <?php } ?> 
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td align="right"> 
                                          <input type="text" name="total_courier" class="form-control" value="<?php echo (isset($editData['total_courier'])) ? $editData['total_courier'] : ''; ?>" id="total_courier" <?php echo (isset($editData['courier']) && $editData['courier'] != '') ? '' : 'disabled'; ?>>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td align="right">
                                         Taxable Value
                                        </td>
                                        <td align="right"><input type="text" readonly="" name="overall_value" class="form-control" value="<?php echo (isset($editData['overall_value'])) ? $editData['overall_value'] : ''; ?>"  id="overall_value"></td>
                                      </tr>
                                      <tr style="display: none;">
                                        <td align="right">
                                          Total Tax (GST)
                                        </td>
                                        <td align="right">
                                          <input type="text" class="form-control" readonly="" name="total_tax" value="<?php echo (isset($editData['total_tax'])) ? $editData['total_tax'] : ''; ?>" id="total_tax">
                                          <input type="hidden" value="<?php echo (isset($editData['hidden_total_tax'])) ? $editData['hidden_total_tax'] : ''; ?>" id="hidden-total_tax" name="hidden_total_tax">
                                        </td>
                                      </tr>
                                      
                                      <tr id="igst" <?php if(isset($editData['total_igst']) && $editData['total_igst'] != '' && $editData['total_igst'] != 0){ } else{ ?> style="display: none;" <?php } ?>>
                                        <td align="right">
                                          IGST
                                        </td>
                                        <td align="right">
                                          <input type="text" value="<?php echo (isset($editData['total_igst'])) ? $editData['total_igst'] : ''; ?>" class="form-control" readonly="" name="total_igst" id="total_igst">
                                          <input type="hidden" value="<?php echo (isset($editData['hidden_total_igst'])) ? $editData['hidden_total_igst'] : ''; ?>" id="hidden_total_igst" name="hidden_total_igst">
                                        </td>
                                      </tr>
                                      <tr id="cgst" <?php if(isset($editData['total_cgst']) && $editData['total_cgst'] != '' && $editData['total_cgst'] != 0){ }else{ ?> style="display: none;" <?php } ?>>
                                        <td align="right">
                                          CGST
                                        </td>
                                        <td align="right">
                                          <input type="text" class="form-control" value="<?php echo (isset($editData['total_cgst'])) ? $editData['total_cgst'] : ''; ?>" readonly="" name="total_cgst" id="total_cgst">
                                          <input type="hidden" value="<?php echo (isset($editData['hidden_total_cgst'])) ? $editData['hidden_total_cgst'] : ''; ?>" id="hidden_total_cgst" name="hidden_total_cgst">
                                        </td>
                                      </tr>
                                      
                                      <tr id="sgst" <?php if(isset($editData['total_sgst']) && $editData['total_sgst'] != '' && $editData['total_sgst'] != 0){ } else{ ?>style="display: none;" <?php } ?>>
                                        <td align="right">
                                          SGST
                                        </td>
                                        <td align="right">
                                          <input type="text" class="form-control" value="<?php echo (isset($editData['total_sgst'])) ? $editData['total_sgst'] : ''; ?>" readonly="" name="total_sgst" id="total_sgst">
                                          <input type="hidden" value="<?php echo (isset($editData['hidden_total_sgst'])) ? $editData['hidden_total_sgst'] : ''; ?>" id="hidden_total_sgst" name="hidden_total_sgst">
                                        </td>
                                      </tr>
                                      <input type="hidden" id="hidden_total">
                                      
                                      
                                       <tr>
                                        <td align="right">
                                          <select class="form-control note_details " name="note_details" id="note_details" style="width:250px;">
                                                <option <?php if(isset($editData) && $editData['note_details'] == "credit_note"){echo "selected";} ?> value="credit_note">Credit Note</option>
                                                <option <?php if(isset($editData) && $editData['note_details'] == "debit_note"){echo "selected";} ?> value="debit_note">Debit Note</option>
                                            </select>
                                        </td>
                                        <td align="right">
                                          <input type="text" name="note_value" class="form-control note_details onlynumber priceOnly" value="<?php echo (isset($editData['note_value'])) ? $editData['note_value'] : ''; ?>" id="note_value">
                                        </td>
                                      </tr>
                                      <tr style="background:#ececec;">
                                        <td align="right">
                                          Total Amount
                                        </td>
                                        <td align="right">
                                          <input type="text" value="<?php echo (isset($editData['purchase_amount'])) ? $editData['purchase_amount'] : ''; ?>" class="form-control" readonly="" name="purchase_amount" id="purchase_amount">
                                        </td>
                                      </tr>
                                      
                                      <tr style="background:#e0e0e0;">
                                        <td align="right">
                                          Round off
                                        </td>
                                        <td align="right">
                                          <input type="text" value="<?php echo (isset($editData['round_off'])) ? $editData['round_off'] : ''; ?>" class="form-control" readonly="" name="round_off" id="round_off">
                                        </td>
                                      </tr>
                                      
                                      <tr style="background:#0062ab;color:#fff;">
                                        <td align="right">
                                          <strong>NET VALUE</strong>
                                        </td>
                                        <td align="right">
                                         <input type="text" class="form-control" readonly="" value="<?php echo (isset($editData['total_total'])) ? $editData['total_total'] : ''; ?>" name="total_total" id="total_total">
                                        </td>
                                      </tr>
                                      
                                    </tbody>
                                  </table>
                                </div>
                            </div>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <a href="view-purchase.php" class="btn btn-light pull-left">Back</a>
                        <button type="submit" name="save" class="btn btn-success mr-2 pull-right">Save</button>
                        <button type="submit" name="saveAndNext" class="btn btn-success mr-2 pull-right">Save & Next</button>
                        <!--<button type="submit" name="saveAndPrint" class="btn btn-success mr-2 pull-right">Save & Print</button>-->
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>

        
        <!-- partial:partials/_footer.php -->
        <?php include "include/footer.php" ?>
        <!-- partial -->
        
        <input type="hidden" name="cur_statecode" id="cur_statecode" value="<?php echo (isset($_SESSION['state_code'])) ? $_SESSION['state_code'] : ''; ?>" >
        
        <!-- Add new vendor Model -->
        <?php include('include/addvendormodel.php')?>
        
         <!-- Add new Product Model -->
        <?php include('include/addproductmodel.php');?>
        
        <!-- get last bill Model -->
        <?php include('popup/vendor-last-bill-model.php'); ?>
        
        <!-- Add transport Model -->
        <?php include('popup/add-transport-model.php'); ?>

        <!-- Add Company model -->
        <?php //include "include/addcompanymodel.php"?>

        <!-- Add GST Model -->
        <?php //include "include/addgstmodel.php"?>

        <!--  Add Unit Model -->
        <?php //include "include/addunitmodel.php"?>
        
         <!-- add area Model -->
        <?php include "include/addarea-model.php"?>

        <!-- PURCHASE ORDER ITEM POPUP -->
        <div class="modal fade" id="poi-model" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true" style="display: none;">
          <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Select Purchase Order Item</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                  <div class="modal-body">
                    <span id="poi-error"></span>
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th><input type="checkbox" id="poi-checkbox-all"></th>
                          <th>Sr. No</th>
                          <th>Date</th>
                          <th>Order No</th>
                          <th>Product Name</th>
                          <th>Batch No.</th>
                          <th>Expiry</th>
                          <th>Generic Name</th>
                          <th>Manufacturer Name</th>
                          <th>Purchase Price</th>
                          <th>GST(%)</th>
                          <th>Unit/Strip/Packing</th>
                          <th>Quentity</th>
                        </tr>
                      </thead>
                      <tbody id="poi-body">
                        
                      </tbody>
                    </table>
                  </div>
                  <div class="modal-footer row">
                    <div class="col-md-12">
                      <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancel</button>
                      <button type="button" class="btn btn-success pull-right" id="btn-addpoi">Add</button>
                    </div>
                  </div>
              </div>
          </div>
        </div>         
                        
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  
  
  <div id="html-copy" style="display: none;">
    <table>
      <tr class="product-tr">
        <td>##SRNO##</td>
        <td>
          <input type="text" name="product[]" placeholder="Product" required="" class="tags form-control">
          <input type="hidden" class="product-id" name="product_id[]">
          <small class="text-danger empty-message"></small>
        </td>
        <td>
          <input type="text" name="mrp[]" class="form-control mrp onlynumber" id="mrp" placeholder="MRP" autocomplete="off">
        </td>
        <td>
          <input type="text" name="mfg_co[]" class="form-control mfg_co" id="mfg_co" placeholder="MFG. Co." autocomplete="off">
        </td>
        <td>
          <input type="text" name="batch[]" class="form-control batch" id="batch" placeholder="Batch" autocomplete="off">
        </td>
        <td>
          <input type="text" name="expiry[]" class="form-control datepicker-ex expiry" style="width: 80px;" id="expiry" placeholder="Expiry" autocomplete="off">
          <small class="text-danger expired"></small>
        </td>
        <td>
          <input type="text" name="qty[]" class="form-control qty onlynumber" id="qty" placeholder="Qty." autocomplete="off" required>
          <input type="hidden" class="qty-value" name="qty_ratio[]">
        </td>
        <td>
          <input type="text" name="free_qty[]" class="form-control free_qty onlynumber" id="free_qty" placeholder="Free Qty" autocomplete="off">
        </td>
        <td>
          <input type="text" name="rate[]" class="form-control rate onlynumber" id="rate" placeholder="Rate" autocomplete="off">
        </td>
        <td>
          <input type="text" name="discount[]" class="form-control discount onlynumber" id="discount" placeholder="Discount" autocomplete="off">
        </td>
        <td>
          <input type="text" name=f_rate[] class="form-control f_rate onlynumber" id="f_rate" placeholder="Rate" autocomplete="off" required>
        </td>
        <td>
          <input type="text" name=ammout[] class="form-control ammout onlynumber" id="ammout" placeholder="Ammount" autocomplete="off">
          <input type="hidden" name="f_igst[]" class="f_igst">
          <input type="hidden" name="f_cgst[]" class="f_cgst">
          <input type="hidden" name="f_sgst[]" class="f_sgst">
          <input type="hidden" name="poi_id[]" class="f_poi_id">
        </td>
        <td><a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a><a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product"><i class="fa fa-close mr-0 ml-0"></i></a></td>
      </tr>
    </table>
  </div>
  
  <!-- Hidden HTML for poi items start -->
  <div id="poi-tr-html" style="display: none;">
      <table>
        <tr>
          <td><input type="checkbox" name="item" class="poi-checkbox"></td>
          <td>##SRNO##</td>
          <td class="poi-date">##DATE##</td>
          <td class="poi-order">##ORDER##</td>
          <td class="poi-pname">##PRODUCTNAME##</td>
          <td class="poi-batch">##BATCH##</td>
          <td class="poi-expiry">##EXPIRY##</td>
          <td class="poi-gname">##GENERIC##</td>
          <td class="poi-mfg">##MFG##</td>
          <td class="poi-pprice">##PURCHASEPRICE##</td>
          <td class="poi-gst">##GST##</td>
          <td class="poi-unit">##UNIT##</td>
          <td class="poi-qty">##QTY##</td>
          <input type="hidden" class="poi-pid" value="##PRODUCTID##">
          <input type="hidden" class="poi-id" value="##POIID##">
        </tr>
      </table>
  </div>
  <!-- Hidden HTML for poi items end -->

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
  <script src="js/custom/onlyalphabet.js"></script>
  
  <!-- Custom js for this page Modal Box-->
  <script src="js/modal-demo.js"></script>
  
  <script type="text/javascript">
    $(".product-select2").select2();
  </script>
  <!-- Datepicker Initialise-->
 <script>
    $('.datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy',
      autoclose : true
    });
    
 </script>
 <script type="text/javascript">
  $(document).on('focus',".datepicker-ex", function(){ //bind to all instances of class "date". 
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
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script>
  <script src="js/custom/purchase.js"></script>
  <script src="js/custom/product-gst-change.js"></script>
  
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <!-- script for custom validation -->
<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>

  
  
  <!-- End custom js for this page-->
</body>


</html>
