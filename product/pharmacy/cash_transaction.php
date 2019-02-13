<?php include('include/usertypecheck.php'); ?>
<?php 
  $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

  $allgroup = [];
  $groupQ = "SELECT id, name FROM `group` ORDER BY name";
  $groupR = mysqli_query($conn, $groupQ);
  if($groupR && mysqli_num_rows($groupR) > 0){
    while ($groupRow = mysqli_fetch_assoc($groupR)) {
      $allgroup[] = $groupRow;
    }
  }

  if(isset($_POST['add'])){
    $count = (isset($_POST['perticular']) && !empty($_POST['perticular'])) ? count($_POST['perticular']) : 0;
    if($count > 0){
      $voucher_no = getCashReceiptVoucherNo();
      $voucher_date = (isset($_POST['voucher_date']) && $_POST['voucher_date'] != '' && $_POST['voucher_date'] != '0000-00-00') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['voucher_date']))) : '';
      $batch = mt_rand(100000,999999);
      for($i=0; $i < $count; $i++) {
        $type = (isset($_POST['type'][$i])) ? $_POST['type'][$i] : '';
        $perticular = (isset($_POST['perticular'][$i])) ? $_POST['perticular'][$i] : '';
        $amount = (isset($_POST['amount'][$i]) && $_POST['amount'][$i] != '') ? $_POST['amount'][$i] : 0;
        $remarks = (isset($_POST['remarks'][$i])) ? $_POST['remarks'][$i] : '';
        $reverse_charge = (isset($_POST['reverse_charge'][$i]) && $_POST['reverse_charge'][$i] != '') ? $_POST['reverse_charge'][$i] : 0;
        $gst = (isset($_POST['gst'][$i]) && $reverse_charge == 1) ? $_POST['gst'][$i] : 0;

        
        $query = "INSERT INTO cash_receipt SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', voucher_no = '".$voucher_no."', voucher_date = '".$voucher_date."', type = '".$type."', perticular = '".$perticular."', amount = '".$amount."', remarks = '".$remarks."', reverse_charge = '".$reverse_charge."', gst = '".$gst."', batch = '".$batch."', created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
        $res = mysqli_query($conn, $query);
      }
      $_SESSION['msg']['success'] = "Cash Receipt Added Successfully.";
      header('Location: view-cash-receipt.php');exit;
    }else{
      $_SESSION['msg']['fail'] = "At least one transaction is required!";
      header('Location: cash-receipt.php');exit;
    }
  }

  if(isset($_POST['update'])){
    $voucher_date = (isset($_POST['voucher_date']) && $_POST['voucher_date'] != '' && $_POST['voucher_date'] != '0000-00-00') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['voucher_date']))) : '';
    $type = (isset($_POST['type'])) ? $_POST['type'] : '';
    $perticular = (isset($_POST['perticular'])) ? $_POST['perticular'] : '';
    $amount = (isset($_POST['amount']) && $_POST['amount'] != '') ? $_POST['amount'] : 0;
    $remarks = (isset($_POST['remarks'])) ? $_POST['remarks'] : '';
    $reverse_charge = (isset($_POST['reverse_charge']) && $_POST['reverse_charge'] != '') ? $_POST['reverse_charge'] : 0;
    $gst = (isset($_POST['gst']) && $reverse_charge == 1) ? $_POST['gst'] : 0;

    $query = "UPDATE cash_receipt SET voucher_date = '".$voucher_date."', type = '".$type."', perticular = '".$perticular."', amount = '".$amount."', remarks = '".$remarks."', reverse_charge = '".$reverse_charge."', gst = '".$gst."', modified = '".date('Y-m-d H:i:s')."', modifiedby = '".$_SESSION['auth']['id']."' WHERE id = '".$_GET['id']."'";
    $res = mysqli_query($conn, $query);
    if($res){
      $_SESSION['msg']['success'] = "Cash Receipt Update Successfully.";
      header('Location: view-cash-receipt.php');exit;
    }else{
      $_SESSION['msg']['fail'] = "Cash Receipt Update Fail! Try Again.";
      header('Location: cash-receipt.php');exit;
    }
  }

  if(isset($_GET['id']) && $_GET['id'] != ''){
    $query = "SELECT * FROM cash_receipt WHERE id = '".$_GET['id']."' AND pharmacy_id = '".$pharmacy_id."'";
    $res = mysqli_query($conn, $query);
    if($res && mysqli_num_rows($res) > 0){
      $editData = mysqli_fetch_assoc($res);
      if(isset($editData['perticular']) && $editData['perticular'] != ''){
        $running_balance_data = countRunningBalance($editData['perticular'], '', '', 1);
        $running_balance = (isset($running_balance_data['running_balance']) && $running_balance_data['running_balance'] != '') ? amount_format(number_format($running_balance_data['running_balance'], 2, '.', '')) : 0;
        $running_balance = ($running_balance >= 0) ? $running_balance.' Dr' : $running_balance.' Cr';
        $editData['running_balance'] = $running_balance;
      }
    }else{
      $_SESSION['msg']['fail'] = "Somthing Want Wrong!";
      header('Location: view-cash-receipt.php');exit;
    }
  }

  if(isset($editData['type']) && $editData['type'] != ''){
    $alltype = [];
    $query = "SELECT id, name FROM ledger_master WHERE group_id = '".$editData['type']."' AND pharmacy_id = '".$pharmacy_id."'";
    $res = mysqli_query($conn, $query);
    if($res && mysqli_num_rows($res) > 0){
      while ($row = mysqli_fetch_assoc($res)) {
        $alltype[] = $row;
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks | Cash Payment</title>
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
  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" />
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
          <!------------------ FORM START ----------------->
          <form method="POST" autocomplete="off">
            <div class="row">
              <?php include "include/transaction_header.php"; ?>

              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="row form-group">
                      <div class="col-md-2">
                        <label for="voucher_no">Voucher No. <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="voucher_no" name="voucher_no" placeholder="Voucher No." value="<?php echo (isset($editData['voucher_no'])) ? $editData['voucher_no'] : getCashReceiptVoucherNo(); ?>" required readonly>
                      </div>
                      <div class="col-md-2">
                        <label for="voucher_date">Voucher Date <span class="text-danger">*</span></label>
                        <div class="input-group date datepicker">
                          <input type="text" class="form-control" name="voucher_date" value="<?php echo (isset($editData['voucher_date']) && $editData['voucher_date'] != '' && $editData['voucher_date'] != '0000-00-00') ? date('d/m/Y',strtotime($editData['voucher_date'])) : date('d/m/Y'); ?>" data-parsley-errors-container="#error-voucher-date" required>
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        <span id="error-voucher-date"></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div id="detail-body">
                      <div class="detail-row">
                        <?php if(isset($editData) && !empty($editData)){ ?>
                          <div class="row form-group">
                            <div class="col-md-3">
                              <label for="type">Type<span class="text-danger">*</span></label>
                              <select class="js-example-basic-single type" name="type" style="width:100%" required>
                                <option value="">Select Type</option>
                                <?php if(isset($allgroup) && !empty($allgroup)){ ?>
                                  <?php foreach ($allgroup as $key => $value) { ?>
                                    <option value="<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>" <?php echo (isset($editData['type']) && $editData['type'] == $value['id']) ? 'selected' : ''; ?>><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></option>
                                  <?php } ?>
                                <?php } ?>
                              </select>
                            </div>
                            <div class="col-md-3">
                              <label for="perticular">Perticular<span class="text-danger">*</span></label>
                              <select class="js-example-basic-single perticular" name="perticular" style="width:100%" required>
                                <option value="">Select Perticular</option>
                                <?php if(isset($alltype) && !empty($alltype)){ ?>
                                  <?php foreach ($alltype as $key => $value) { ?>
                                    <option value="<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>" <?php echo (isset($editData['perticular']) && $editData['perticular'] == $value['id']) ? 'selected' : ''; ?> ><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></option>
                                  <?php } ?>
                                <?php } ?>
                              </select>
                              <div class="badge badge-primary running_balance" style="position: absolute;right: 15px;"><?php echo (isset($editData['running_balance'])) ? $editData['running_balance'] : ''; ?></div>
                            </div>
                            <div class="col-md-3">
                              <label for="debit">Debit <span class="text-danger">*</span></label>
                              <input type="text" class="form-control debit onlynumber" name="debit[]" placeholder="Debit" required>
                            </div>
                            <div class="col-md-3">
                              <label for="credit">Credit<span class="text-danger">*</span></label>
                             <input type="text" class="form-control credit onlynumber" name="credit[]" placeholder="Credit" required>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-md-3">
                              <label for="narration">Narration<span class="text-danger">*</span></label>
                             <input type="text" class="form-control narration onlynumber" name="narration[]" placeholder="Narration" required>
                            </div>
                            
                            <div class="col-md-3">
                              <label>Reverse Charge</label>
                              <div class="row no-gutters">
                                      
                                <div class="col-12 col-md-6">
                                  <div class="form-radio">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input reverse_charge" name="reverse_charge" value="0" data-parsley-multiple="reversechange" <?php echo (isset($editData['reverse_charge']) && $editData['reverse_charge'] == 0) ? 'checked' : ''; ?> >
                                      No
                                    <i class="input-helper"></i></label>
                                  </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                  <div class="form-radio">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input reverse_charge" name="reverse_charge" value="1" data-parsley-multiple="reverse_charge" <?php echo (isset($editData['reverse_charge']) && $editData['reverse_charge'] == 1) ? 'checked' : ''; ?> >
                                      Yes
                                    <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="gst-div" <?php echo (isset($editData['reverse_charge']) && $editData['reverse_charge'] == 1) ? '' : 'style="display:none;"'; ?>>
                                <label for="gst">GST %</label>
                                <select name="gst" class="form-control">
                                  <option value="5" <?php echo (isset($editData['gst']) && $editData['gst'] == 5) ? 'selected' : ''; ?> >5%</option>
                                  <option value="12" <?php echo (isset($editData['gst']) && $editData['gst'] == 12) ? 'selected' : ''; ?> >12%</option>
                                  <option value="18" <?php echo (isset($editData['gst']) && $editData['gst'] == 18) ? 'selected' : ''; ?> >18%</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        <?php }else{ ?>
                          <div class="row form-group">
                            <div class="col-md-3">
                              <label for="type">Type<span class="text-danger">*</span></label>
                              <select class="js-example-basic-single type" name="type[]" style="width:100%" required>
                                <option value="">Select Type</option>
                                <?php if(isset($allgroup) && !empty($allgroup)){ ?>
                                  <?php foreach ($allgroup as $key => $value) { ?>
                                    <option value="<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>"><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></option>
                                  <?php } ?>
                                <?php } ?>
                              </select>
                            </div>
                            <div class="col-md-3">
                              <label for="perticular">Perticular<span class="text-danger">*</span></label>
                              <select class="js-example-basic-single perticular" name="perticular[]" style="width:100%" required>
                                <option value="">Select Perticular</option>
                              </select>
                              <div class="badge badge-primary running_balance" style="display: none;position: absolute;right: 15px;"></div>
                            </div>
                            <div class="col-md-3">
                              <label for="debit">Debit <span class="text-danger">*</span></label>
                              <input type="text" class="form-control debit onlynumber" name="debit[]" placeholder="Debit" required>
                            </div>
                            <div class="col-md-3">
                              <label for="credit">Credit<span class="text-danger">*</span></label>
                             <input type="text" class="form-control credit onlynumber" name="credit[]" placeholder="Credit" required>
                            </div>
                          </div>
                          <div class="row form-group">
                             <div class="col-md-3">
                              <label for="narration">Narration<span class="text-danger">*</span></label>
                             <input type="text" class="form-control narration onlynumber" name="narration[]" placeholder="Narration" required>
                            </div>
                            
                            
                            <div class="col-md-3">
                              <label>Reverse Charge</label>
                              <div class="row no-gutters">
                                      
                                <div class="col-12 col-md-6">
                                  <div class="form-radio">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input reverse_charge" name="reverse_charge[0]" value="0" data-parsley-multiple="reversechange" checked>
                                      No
                                    <i class="input-helper"></i></label>
                                  </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                  <div class="form-radio">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input reverse_charge" name="reverse_charge[0]" value="1" data-parsley-multiple="reverse_charge">
                                      Yes
                                    <i class="input-helper"></i></label>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="gst-div" style="display: none;">
                                <label for="gst">GST %</label>
                                <select name="gst[]" class="form-control">
                                  <option value="5">5%</option>
                                  <option value="12">12%</option>
                                  <option value="18">18%</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-3 text-right" style="padding-top: 25px;">
                              <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item"><i class="fa fa-plus mr-0 ml-0"></i></button>
                            </div>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                    <hr/>
                    <div id="detail-footer">
                      <a href="view-cash-receipt.php" class="btn btn-light">Back</a>
                      <button type="submit" class="btn btn-success pull-right" name="<?php echo (isset($_GET['id']) && $_GET['id'] != '') ? 'update' : 'add' ?>"><?php echo (isset($_GET['id']) && $_GET['id'] != '') ? 'Update' : 'Save' ?></button>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </form>
          <!------------------ FORM END ----------------->
        </div>
        <?php include "include/footer.php" ?>
      </div>
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <div id="hidden-detail-row" style="display: none;">
    <div class="detail-row">
      <hr/>
      <div class="row form-group">
        <div class="col-md-3">
          <label for="type">Type<span class="text-danger">*</span></label>
          <select class="type" name="type[]" style="width:100%" required>
            <option>Select Type</option>
            <?php if(isset($allgroup) && !empty($allgroup)){ ?>
              <?php foreach ($allgroup as $key => $value) { ?>
                <option value="<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>"><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></option>
              <?php } ?>
            <?php } ?>
          </select>
        </div>
        <div class="col-md-3">
          <label for="perticular">Perticular<span class="text-danger">*</span></label>
          <select class="perticular" name="perticular[]" style="width:100%" required>
            <option value="">Select Perticular</option>
          </select>
          <div class="badge badge-primary running_balance" style="display: none;position: absolute;right: 15px;"></div>
        </div>
        <div class="col-md-3">
          <label for="debit">Debit <span class="text-danger">*</span></label>
          <input type="text" class="form-control debit onlynumber" name="debit[]" placeholder="Debit" required>
        </div>
        <div class="col-md-3">
          <label for="credit">Credit<span class="text-danger">*</span></label>
         <input type="text" class="form-control credit onlynumber" name="credit[]" placeholder="Credit" required>
        </div>
      </div>
      <div class="row form-group">
          
           <div class="col-md-3">
            <label for="narration">Narration<span class="text-danger">*</span></label>
             <input type="text" class="form-control narration onlynumber" name="narration[]" placeholder="Narration" required>
            </div>
                            
        <div class="col-md-3">
          <label>Reverse Charge</label>
          <div class="row no-gutters">
                  
            <div class="col-12 col-md-6">
              <div class="form-radio">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input reverse_charge" name="reverse_charge[##KEY##]" value="0" data-parsley-multiple="reversechange" checked>
                  No
                <i class="input-helper"></i></label>
              </div>
            </div>
            
            <div class="col-12 col-md-6">
              <div class="form-radio">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input reverse_charge" name="reverse_charge[##KEY##]" value="1" data-parsley-multiple="reverse_charge">
                  Yes
                <i class="input-helper"></i></label>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="gst-div" style="display: none;">
            <label for="gst">GST %</label>
            <select name="gst[]" class="form-control">
              <option value="5">5%</option>
              <option value="12">12%</option>
              <option value="18">18%</option>
            </select>
          </div>
        </div>
        <div class="col-md-3 text-right" style="padding-top: 25px;">
          <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item"><i class="fa fa-plus mr-0 ml-0"></i></button>
          <button type="button" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-item" style=""><i class="fa fa-close mr-0 ml-0"></i></button>
        </div>
      </div>
    </div>
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
  
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script>
  
  <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <script>
     $('.datatable').DataTable();
     $('.datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy',
      autoclose : true
    });
  </script>
  <!-- script for custom validation -->
  <script src="js/jquery-ui.js"></script>
  <script src="js/parsley.min.js"></script>
  <script type="text/javascript">
    $('form').parsley();
  </script>
  <!-- page js -->
  <script src="js/custom/cash-payment.js"></script>
  <script src="js/custom/onlynumber.js"></script>
  <!-- End custom js for this page-->
</body>
</html>
