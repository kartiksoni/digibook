<?php $title = "Credit Note / Debit Note"; ?>
<?php include('include/usertypecheck.php');
// include('include/permission.php');

  $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

  if(isset($_POST['submit'])){

      if(isset($_GET['id']) && $_GET['id'] != ''){
        $return['debit_note_no'] = (isset($_POST['debit_note_no'])) ? $_POST['debit_note_no'] : '';
      }else{
        $return['debit_note_no'] = getDebitNoteNo();
      }
      $return['debit_note_date'] = (isset($_POST['debit_note_date']) && $_POST['debit_note_date'] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['debit_note_date']))) : '';
      $return['vendor_id'] = (isset($_POST['vendor_id'])) ? $_POST['vendor_id'] : '';
      $return['remarks'] = (isset($_POST['remarks'])) ? $_POST['remarks'] : '';
      $return['debit_note_settle'] = (isset($_POST['debit_note_settle'])) ? $_POST['debit_note_settle'] : '';

      
      $return['totalamount'] = (isset($_POST['totalamount']) && $_POST['totalamount'] != '') ? $_POST['totalamount'] : 0;
      $return['igst'] = (isset($_POST['totaligst']) && $_POST['totaligst'] != '') ? $_POST['totaligst'] : 0;
      $return['cgst'] = (isset($_POST['totalcgst']) && $_POST['totalcgst'] != '') ? $_POST['totalcgst'] : 0;
      $return['sgst'] = (isset($_POST['totalsgst']) && $_POST['totalsgst'] != '') ? $_POST['totalsgst'] : 0;
      $return['finalamount'] = (isset($_POST['finalamount']) && $_POST['finalamount'] != '') ? $_POST['finalamount'] : 0;
  
      if(isset($_GET['id']) && $_GET['id']){
        $query = "UPDATE purchase_return SET ";
      }else{
        $query = "INSERT INTO purchase_return SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."',";
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
            $deleteOldItemQ = "DELETE FROM purchase_return_detail WHERE pr_id = '".$returnid."'";
            mysqli_query($conn, $deleteOldItemQ);
  
            $count = (isset($_POST['product_id']) && !empty($_POST['product_id'])) ? count($_POST['product_id']) : 0;
            if($count > 0){
              for ($i=0; $i < $count; $i++) { 
                $return_details['pr_id'] = $returnid;
                $return_details['purchase_id'] = (isset($_POST['purchase_id'][$i])) ? $_POST['purchase_id'][$i] : '';
                $return_details['product_id'] = (isset($_POST['product_id'][$i])) ? $_POST['product_id'][$i] : '';
                $return_details['mrp'] = (isset($_POST['mrp'][$i]) && $_POST['mrp'][$i] != '') ? $_POST['mrp'][$i] : 0;
                $return_details['mfg_co'] = (isset($_POST['mfg_co'][$i])) ? $_POST['mfg_co'][$i] : '';
                $return_details['batchno'] = (isset($_POST['batch_no'][$i])) ? $_POST['batch_no'][$i] : '';
                $return_details['expiry'] = (isset($_POST['expiry'][$i]) && $_POST['expiry'][$i] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['expiry'][$i]))) : '';
                $return_details['qty'] = (isset($_POST['qty'][$i]) && $_POST['qty'][$i] != '') ? $_POST['qty'][$i] : 0;
                $return_details['free_qty'] = (isset($_POST['free_qty'][$i]) && $_POST['free_qty'][$i] != '') ? $_POST['free_qty'][$i] : 0;
                $return_details['rate'] = (isset($_POST['rate'][$i]) && $_POST['rate'][$i] != '') ? $_POST['rate'][$i] : 0;
                $return_details['discount'] = (isset($_POST['discount'][$i]) && $_POST['discount'][$i] != '') ? $_POST['discount'][$i] : 0;
                $return_details['final_rate'] = (isset($_POST['f_rate'][$i]) && $_POST['f_rate'][$i] != '') ? $_POST['f_rate'][$i] : 0;
                $return_details['igst'] = (isset($_POST['igst'][$i]) && $_POST['igst'][$i] != '') ? $_POST['igst'][$i] : 0;
                $return_details['cgst'] = (isset($_POST['cgst'][$i]) && $_POST['cgst'][$i] != '') ? $_POST['cgst'][$i] : 0;
                $return_details['sgst'] = (isset($_POST['sgst'][$i]) && $_POST['sgst'][$i] != '') ? $_POST['sgst'][$i] : 0;
                $return_details['amount'] = (isset($_POST['ammout'][$i]) && $_POST['ammout'][$i] != '') ? $_POST['ammout'][$i] : 0;
                
                $item = "INSERT INTO purchase_return_detail SET ";
                foreach ($return_details as $k => $v) {
                    $item .= " ".$k." = '".$v."', ";
                }
                $item .= "created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
                mysqli_query($conn, $item);
              }
            }
        }
        if(isset($_GET['id']) && $_GET['id'] != ''){
          $_SESSION['msg']['success'] = "Purchase Return Updated Successfully.";
        }else{
          $_SESSION['msg']['success'] = "Purchase Return Added Successfully.";
        }
      }else{
        if(isset($_GET['id']) && $_GET['id'] != ''){
          $_SESSION['msg']['fail'] = "Purchase Return Updated Fail!";
        }else{
          $_SESSION['msg']['fail'] = "Purchase Return Added Fail!";
        }
      }
      header('Location: purchase-return.php');exit;
  }

  if(isset($_GET['id']) && $_GET['id'] != ''){
    $editQ = "SELECT pr.*, st.state_code_gst as statecode FROM purchase_return pr LEFT JOIN ledger_master lg ON pr.vendor_id = lg.id LEFT JOIN own_states st ON lg.state = st.id WHERE pr.pharmacy_id = '".$pharmacy_id."' AND pr.id = '".$_GET['id']."'";
    $editR = mysqli_query($conn, $editQ);
    if($editR && mysqli_num_rows($editR) > 0){
      $editData = mysqli_fetch_assoc($editR);
      
      $editItemQ = "SELECT prd.*, pm.product_name FROM purchase_return_detail prd LEFT JOIN product_master pm ON prd.product_id = pm.id WHERE prd.pr_id = '".$_GET['id']."'";
      $editItemR = mysqli_query($conn, $editItemQ);
      if($editItemR && mysqli_num_rows($editItemR) > 0){
        while($editItemRow = mysqli_fetch_assoc($editItemR)){
          $editData['detail'][] = $editItemRow;
        }
      }
    }else{
      $_SESSION['msg']['fail'] = "Sorry!! Invalid Request!";
      header('Location: purchase-return.php');exit;
    }
  }

  if(isset($_GET['bill']) && $_GET['bill'] != ''){
    $purchaseQ = "SELECT p.vendor as vendor_id, st.state_code_gst as statecode FROM purchase p LEFT JOIN ledger_master lg ON p.vendor = lg.id LEFT JOIN own_states st ON lg.state = st.id WHERE p.pharmacy_id = '".$pharmacy_id."' AND p.id = '".$_GET['bill']."'";
    $purchaseR = mysqli_query($conn, $purchaseQ);
    if($purchaseR && mysqli_num_rows($purchaseR) > 0){
      $editData = mysqli_fetch_assoc($purchaseR);
      $editData['totalamount'] = 0;
      $editData['igst'] = 0;
      $editData['cgst'] = 0;
      $editData['sgst'] = 0;
      $editData['finalamount'] = 0;

      $purchaseDetailQ = "SELECT pd.purchase_id, pd.product_id, pm.product_name, pd.mrp, pd.mfg_co, pd.batch as batchno, pd.expiry, pd.qty, pd.free_qty, pd.rate, pd.discount, pd.f_rate as final_rate, pd.f_igst, pd.f_cgst, pd.f_sgst FROM purchase_details pd LEFT JOIN product_master pm ON pd.product_id = pm.id WHERE pd.purchase_id = '".$_GET['bill']."'";
      $purchaseDetailR = mysqli_query($conn, $purchaseDetailQ);
      if($purchaseDetailR && mysqli_num_rows($purchaseDetailR) > 0){
        while ($purchaseDetailRow = mysqli_fetch_assoc($purchaseDetailR)) {
          if($_SESSION['state_code'] == $editData['statecode']){
            $purchaseDetailRow['igst'] = 0;
            $purchaseDetailRow['cgst'] = (isset($purchaseDetailRow['f_cgst']) && $purchaseDetailRow['f_cgst'] != '') ? $purchaseDetailRow['f_cgst'] : 0;
            $purchaseDetailRow['sgst'] = (isset($purchaseDetailRow['f_sgst']) && $purchaseDetailRow['f_sgst'] != '') ? $purchaseDetailRow['f_sgst'] : 0;
          }else{
            $purchaseDetailRow['igst'] = (isset($purchaseDetailRow['f_igst']) && $purchaseDetailRow['f_igst'] != '') ? $purchaseDetailRow['f_igst'] : 0;
            $purchaseDetailRow['cgst'] = 0;
            $purchaseDetailRow['sgst'] = 0;
          }
          $purchaseDetailRow['amount'] = ($purchaseDetailRow['qty']*$purchaseDetailRow['final_rate']);
          $editData['totalamount'] += $purchaseDetailRow['amount'];

          $finalamount = (($purchaseDetailRow['qty']+$purchaseDetailRow['free_qty'])*($purchaseDetailRow['final_rate']));
          $editData['igst'] += ($finalamount*$purchaseDetailRow['igst']/100);
          $editData['cgst'] += ($finalamount*$purchaseDetailRow['cgst']/100);
          $editData['sgst'] += ($finalamount*$purchaseDetailRow['sgst']/100);

          $editData['detail'][] = $purchaseDetailRow;
        }
      }
      $editData['finalamount'] = ($editData['totalamount']+$editData['igst']+$editData['cgst']+$editData['sgst']);
    }else{
      $_SESSION['msg']['fail'] = "Sorry!! Invalid Request!";
      header('Location: purchase-return.php');exit;
    }
  }

  $allvendor = [];
  $getVendorQ = "SELECT lg.id, lg.name, st.state_code_gst as state_code FROM purchase p INNER JOIN ledger_master lg ON p.vendor = lg.id LEFT JOIN own_states st ON lg.state = st.id WHERE p.pharmacy_id = '".$pharmacy_id."' GROUP BY p.vendor ORDER BY lg.name";
  $getVendorR = mysqli_query($conn, $getVendorQ);
  if($getVendorR && mysqli_num_rows($getVendorR)){
    while ($getVendorRow = mysqli_fetch_assoc($getVendorR)) {
      $allvendor[] = $getVendorRow;
    }
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Purchase Return</title>
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
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                   <div class="row">
                      <div class="col-12">
                        <div class="purchase-top-btns">
                          <?php if((isset($user_sub_module) && in_array("Purchase Bill", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="purchase.php" class="btn btn-dark active">Purchase Bill</a>
                          <?php } if((isset($user_sub_module) && in_array("Purchase Return", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="purchase-return.php" class="btn btn-dark">Purchase Return</a>
                          <?php } if((isset($user_sub_module) && in_array("Purchase Return List", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="purchase-return-list.php" class="btn btn-dark">Purchase Return List</a>
                          <?php } if((isset($user_sub_module) && in_array("Cancel List", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="purchase-cancel-list.php" class="btn btn-dark btn-fw">Cancel List</a>
                          <?php } if((isset($user_sub_module) && in_array("History", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="purchase-history.php" class="btn btn-dark btn-fw">History</a>
                          <?php }  if((isset($user_sub_module) && in_array("Settings", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <!--<a href="#" class="btn btn-dark btn-fw">Settings</a>-->
                          <?php } ?>
                        </div>   
                      </div> 
                    </div>
                    <hr><br>
                    <form class="forms-sample" method="post" autocomplete="off">
                      <div class="form-group row">
                        <div class="col-12 col-md-2">
                          <label for="debit_note_no">Debit Note Number <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" value="<?php echo (isset($editData['debit_note_no'])) ? $editData['debit_note_no'] : getDebitNoteNo(); ?>" name="debit_note_no" id="debit_note_no" placeholder="Number" required>
                        </div>
                        <div class="col-12 col-md-2">
                          <label for="debit_date">Debit Note Date <span class="text-danger">*</span></label>
                            <div id="" class="input-group date datepicker">
                              <input type="text" class="form-control border debit_note_date" value="<?php echo (isset($editData['debit_note_date']) && $editData['debit_note_date'] != '' && $editData['debit_note_date'] != '0000-00-00') ? date('d/m/Y',strtotime($editData['debit_note_date'])) : date('d/m/Y'); ?>" name="debit_note_date" data-parsley-errors-container="#error-dbnote" required>
                              <span class="input-group-addon input-group-append border-left">
                                <span class="mdi mdi-calendar input-group-text"></span>
                              </span>
                            </div>
                            <span id="error-dbnote"></span>
                        </div>
                        <div class="col-12 col-md-3">
                          <label>Select Vendor <span class="text-danger">*</span></label>
                            <select class="js-example-basic-single vendor_id" style="width:100%" name="vendor_id" id="vendor_id" data-parsley-errors-container="#error-vendor" required> 
                              <option value="">Please select</option>
                              <?php if(!empty($allvendor)){ ?>
                                  <?php foreach ($allvendor as $key => $value) { ?>
                                      <option data-state="<?php echo (isset($value['state_code'])) ? $value['state_code'] : ''; ?>" value="<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>" <?php echo (isset($editData['vendor_id']) && $editData['vendor_id'] == $value['id']) ? 'selected' : ''; ?> ><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></option>
                                  <?php } ?>
                              <?php } ?>
                            </select>
                            <input type="hidden" name="statecode" value="<?php echo (isset($editData['statecode'])) ? $editData['statecode'] : ''; ?>" id="statecode">
                            <input type="hidden" name="current_state_code" value="<?php echo (isset($_SESSION['state_code'])) ? $_SESSION['state_code'] : ''; ?>" id="current_state_code">
                            <span id="error-vendor"></span>
                        </div>
                        <div class="col-12 col-md-12 mt-30 mb-30">
                            <table id="order-listing1" class="table">
                              <thead>
                                <tr>
                                    <th width="5%">Sr No.</th>
                                    <th width="15%">Product</th>
                                    <th>MRP</th>
                                    <th>MFG. Co.</th>
                                    <th>Batch No</th>
                                    <th>Expiry</th>
                                    <th>Qty</th>
                                    <th>Free Qty</th>
                                    <th>Rate</th>
                                    <th>Discount</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                    <th width="8%">&nbsp;</th>
                                </tr>
                              </thead>
                              <tbody id="product-tbody">
                                <?php if(isset($editData['detail']) && !empty($editData['detail'])){ ?>
                                  <?php foreach ($editData['detail'] as $key => $value) { ?>
                                      <tr class="product-tr">
                                        <td><?php echo $key+1; ?></td>
                                        <td>
                                          <input type="text" placeholder="Product" class="product form-control" name="product[]" value="<?php echo (isset($value['product_name'])) ? $value['product_name'] : ''; ?>" required>
                                          <input type="hidden" class="product_id" name="product_id[]" value="<?php echo (isset($value['product_id'])) ? $value['product_id'] : ''; ?>">
                                          <input type="hidden" class="purchase_id" name="purchase_id[]" value="<?php echo (isset($value['purchase_id'])) ? $value['purchase_id'] : ''; ?>">
                                          <small class="text-danger empty-message"></small>
                                        </td>
                                        <td><input name="mrp[]" type="text" class="form-control mrp onlynumber" placeholder="MRP" value="<?php echo (isset($value['mrp'])) ? $value['mrp'] : ''; ?>"></td>
                                        <td><input name="mfg_co[]" type="text" class="form-control mfg_co" placeholder="MFG. Co." value="<?php echo (isset($value['mfg_co'])) ? $value['mfg_co'] : ''; ?>"></td>
                                        <td><input name="batch_no[]" type="text" class="form-control batch_no" placeholder="Batch No." value="<?php echo (isset($value['batchno'])) ? $value['batchno'] : ''; ?>"></td>
                                        <td>
                                            <input name="expiry[]" type="text" class="form-control expiry datepicker" placeholder="Expiry" value="<?php echo (isset($value['expiry']) && $value['expiry'] != '' && $value['expiry'] != '0000-00-00') ? date('d/m/Y',strtotime($value['expiry'])) : ''; ?>">
                                            <small class="text-danger expired"></small>
                                        </td>
                                        <td><input name="qty[]" type="text" class="form-control onlynumber qty" placeholder="Qty." value="<?php echo (isset($value['qty'])) ? $value['qty'] : ''; ?>" required></td>
                                        <td><input name="free_qty[]" type="text" class="form-control onlynumber free_qty" placeholder="Free Qty" value="<?php echo (isset($value['free_qty'])) ? $value['free_qty'] : ''; ?>"></td>
                                        <td><input name="rate[]" type="text" class="form-control onlynumber rate" placeholder="Rate" value="<?php echo (isset($value['rate'])) ? $value['rate'] : ''; ?>"></td>
                                        <td><input name="discount[]" type="text" class="form-control onlynumber discount" placeholder="Discount" value="<?php echo (isset($value['discount'])) ? $value['discount'] : ''; ?>"></td>
                                        <td>
                                          <input type="text" name=f_rate[] class="form-control f_rate onlynumber priceOnly" placeholder="Rate" value="<?php echo (isset($value['final_rate'])) ? $value['final_rate'] : ''; ?>" autocomplete="off" readonly required>
                                        </td>
                                        <td>
                                          <input type="hidden" name="igst[]" class="f_igst" value="<?php echo (isset($value['igst'])) ? $value['igst'] : ''; ?>">
                                          <input type="hidden" name="cgst[]" class="f_cgst" value="<?php echo (isset($value['cgst'])) ? $value['cgst'] : ''; ?>">
                                          <input type="hidden" name="sgst[]" class="f_sgst" value="<?php echo (isset($value['sgst'])) ? $value['sgst'] : ''; ?>">
                                          <input name="ammout[]" type="text" class="form-control onlynumber ammout" placeholder="Ammount" value="<?php echo (isset($value['amount'])) ? $value['amount'] : ''; ?>" readonly>
                                        </td>
                                        <td>
                                          <a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a>
                                          <?php if($key != 0){ ?>
                                            <a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product "><i class="fa fa-close mr-0 ml-0"></i></a>
                                          <?php } ?>
                                        </td>
                                      </tr>
                                  <?php } ?>
                                <?php }else{ ?>
                                  <tr class="product-tr">
                                    <td>1</td>
                                    <td>
                                      <input type="text" placeholder="Product" class="product form-control" name="product[]" required>
                                      <input type="hidden" class="product_id" name="product_id[]">
                                      <input type="hidden" class="purchase_id" name="purchase_id[]">
                                      <small class="text-danger empty-message"></small>
                                    </td>
                                    <td><input name="mrp[]" type="text" class="form-control mrp onlynumber" placeholder="MRP"></td>
                                    <td><input name="mfg_co[]" type="text" class="form-control mfg_co" placeholder="MFG. Co."></td>
                                    <td><input name="batch_no[]" type="text" class="form-control batch_no" placeholder="Batch No."></td>
                                    <td>
                                        <input name="expiry[]" type="text" class="form-control expiry datepicker" placeholder="Expiry">
                                        <small class="text-danger expired"></small>
                                    </td>
                                    <td><input name="qty[]" type="text" class="form-control onlynumber qty" placeholder="Qty." required></td>
                                    <td><input name="free_qty[]" type="text" class="form-control onlynumber free_qty" placeholder="Free Qty"></td>
                                    <td><input name="rate[]" type="text" class="form-control onlynumber rate" placeholder="Rate"></td>
                                    <td><input name="discount[]" type="text" class="form-control onlynumber discount" placeholder="Discount"></td>
                                    <td>
                                      <input type="text" name=f_rate[] class="form-control f_rate onlynumber priceOnly" placeholder="Rate" autocomplete="off" readonly required>
                                    </td>
                                    <td>
                                      <input type="hidden" name="igst[]" class="f_igst">
                                      <input type="hidden" name="cgst[]" class="f_cgst">
                                      <input type="hidden" name="sgst[]" class="f_sgst">
                                      <input name="ammout[]" type="text" class="form-control onlynumber ammout" placeholder="Ammount" readonly>
                                    </td>
                                    <td>
                                      <a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a>
                                    </td>
                                  </tr>
                                <?php } ?>
                              </tbody>
                            </table>
                        </div>
                      
                        <div class="col-md-6">
                          <div class="col-12 col-md-12">
                            <label for="remarks">Remarks / Reason for Return </label>
                            <textarea class="form-control" name="remarks" id="remarks" rows="3"><?php echo (isset($editData['remarks'])) ? $editData['remarks'] : ''; ?>
                            </textarea>
                          </div>
                          <div class="col-12 col-md-12">
                            <hr>
                            <div class="row no-gutters">
                              <div class="col-12 col-md-12">
                                  <label for="exampleInputName1">Debit Note Settle in A/c.</label>
                                  <div class="row no-gutters">
                                    <div class="col">
                                        <div class="form-radio">
                                        <label class="form-check-label">
                                        <input type="radio" class="form-check-input debit_note_settle" name="debit_note_settle" id="optionsRadios1" value="1" <?php echo (isset($editData['debit_note_settle']) && $editData['debit_note_settle'] == 1) ? 'checked' : ''; ?> >
                                       ON HOLD
                                        </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col">
                                      <div class="form-radio">
                                      <label class="form-check-label">
                                      <input type="radio" class="form-check-input debit_note_settle" name="debit_note_settle" id="optionsRadios2" value="0" <?php echo (isset($editData['debit_note_settle']) && $editData['debit_note_settle'] == 0) ? 'checked' : ''; ?> <?php echo (!isset($_GET['id'])) ? 'checked' : ''; ?> >
                                      EFFECT IN PARTY LEDGER
                                      </label>
                                      </div>
                                    </div>
                                  </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <table class="table table-striped" width="100%">
                            <tbody>
                              <tr>
                                <td width="70%" align="right">Total Amount</td>
                                <td width="30%">
                                  <input type="text" name="totalamount" id="totalamount" class="form-control text-right onlynumber" placeholder="Total Amount" value="<?php echo (isset($editData['totalamount'])) ? $editData['totalamount'] : ''; ?>" readonly="">
                                </td>
                              </tr>
                              <tr>
                                <td width="70%" align="right">IGST</td>
                                <td width="30%">
                                  <input type="text" name="totaligst" id="totaligst" class="form-control text-right onlynumber" placeholder="Total IGST" value="<?php echo (isset($editData['igst'])) ? $editData['igst'] : ''; ?>" readonly="">
                                </td>
                              </tr>
                              <tr>
                                <td width="70%" align="right">CGST</td>
                                <td width="30%">
                                  <input type="text" name="totalcgst" id="totalcgst" class="form-control text-right onlynumber" value="<?php echo (isset($editData['cgst'])) ? $editData['cgst'] : ''; ?>" placeholder="Total CGST" readonly="">
                                </td>
                              </tr>
                              <tr>
                                <td width="70%" align="right">SGST</td>
                                <td width="30%">
                                  <input type="text" name="totalsgst" id="totalsgst" class="form-control text-right onlynumber" value="<?php echo (isset($editData['sgst'])) ? $editData['sgst'] : ''; ?>" placeholder="Total SGST" readonly="">
                                </td>
                              </tr>
                              <tr>
                                <td width="70%" align="right">Final Amount</td>
                                <td width="30%">
                                  <input type="text" name="finalamount" id="finalamount" class="form-control text-right onlynumber" value="<?php echo (isset($editData['finalamount'])) ? $editData['finalamount'] : ''; ?>" placeholder="Final Amount" readonly="">
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>

                        <div class="col-md-12">
                            <button type="submit" name="submit" class="btn btn-success mt-30 pull-right">Save</button>
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
          <?php include "include/footer.php"; ?>
          <?php include "popup/show-invoice-no-model.php"; ?>

          <!-- partial -->
        </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  
  
<!-- HIDDEN TR START -->
<div id="html-copy" style="display: none;">
  <table>
    <tr class="product-tr">
        <td>##SRNO##</td>
        <td>
          <input type="text" placeholder="Product" class="product form-control" name="product[]" required>
          <input type="hidden" class="product_id" name="product_id[]">
          <input type="hidden" class="purchase_id" name="purchase_id[]">
          <small class="text-danger empty-message"></small>
        </td>
        <td><input name="mrp[]" type="text" class="form-control mrp onlynumber" placeholder="MRP"></td>
        <td><input name="mfg_co[]" type="text" class="form-control mfg_co" placeholder="MFG. Co."></td>
        <td><input name="batch_no[]" type="text" class="form-control batch_no" placeholder="Batch No."></td>
        <td>
            <input name="expiry[]" type="text" class="form-control expiry datepicker" placeholder="Expiry">
            <small class="text-danger expired"></small>
        </td>
        <td><input name="qty[]" type="text" class="form-control onlynumber qty" placeholder="Qty." required></td>
        <td><input name="free_qty[]" type="text" class="form-control onlynumber free_qty" placeholder="Free Qty"></td>
        <td><input name="rate[]" type="text" class="form-control onlynumber rate" placeholder="Rate"></td>
        <td><input name="discount[]" type="text" class="form-control onlynumber discount" placeholder="Discount"></td>
        <td>
          <input type="text" name=f_rate[] class="form-control f_rate onlynumber priceOnly" placeholder="Rate" autocomplete="off" readonly required>
        </td>
        <td>
          <input type="hidden" name="igst[]" class="f_igst">
          <input type="hidden" name="cgst[]" class="f_cgst">
          <input type="hidden" name="sgst[]" class="f_sgst">
          <input name="ammout[]" type="text" class="form-control onlynumber ammout" placeholder="Ammount" readonly>
        </td>
        <td>
          <a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a>
          <a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product "><i class="fa fa-close mr-0 ml-0"></i></a>
        </td>
    </tr>
  </table>
</div>
<!-- HIDDEN TR END -->
  
  
  

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
  
  
  <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <!-- Custom js for this page Modal Box-->
  <script src="js/modal-demo.js"></script>
 
 <script type="text/javascript">
   $('.datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy',
      autoclose : true
    });
 </script>
 
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('.datatable').DataTable();
  </script>
  <script src="js/jquery-ui.js"></script>
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script>
  <script src="js/custom/purchase-return.js"></script>
  
  <!-- script for custom validation -->
<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>
<script src="js/custom/onlynumber.js"></script>
  
  
  <!-- End custom js for this page-->
</body>


</html>
