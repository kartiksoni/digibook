<?php include('include/usertypecheck.php'); ?>
<?php 
  $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

  /*----------------GET ALL GROUP - START----------------*/
  $allgroup = [];
  $groupQ = "SELECT id, name FROM `group` ORDER BY name";
  $groupR = mysqli_query($conn, $groupQ);
  if($groupR && mysqli_num_rows($groupR) > 0){
    while ($groupRow = mysqli_fetch_assoc($groupR)) {
      $allgroup[] = $groupRow;
    }
  }
  /*----------------GET ALL GROUP - END----------------*/

  /*-------------GET ALL BANK - START----------------*/
  $allBank = getBank();
  /*-------------GET ALL BANK - END----------------*/

  /*------------------ADD BANK TRANSACTION START-----------------------*/
  if(isset($_POST['add'])){
    $count = (isset($_POST['perticular']) && !empty($_POST['perticular'])) ? count($_POST['perticular']) : 0;
    if($count > 0){
      $receiptVoucherNo = getBankTransactionVoucherNo('receipt');
      $paymentVoucherNo = getBankTransactionVoucherNo('payment');
      $voucher_date = (isset($_POST['voucher_date']) && $_POST['voucher_date'] != '' && $_POST['voucher_date'] != '0000-00-00') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['voucher_date']))) : '';
      $bank_id = (isset($_POST['bank_id'])) ? $_POST['bank_id'] : '';
      $batch = mt_rand(100000,999999);
      for($i=0; $i < $count; $i++) {
        $type = (isset($_POST['type'][$i])) ? $_POST['type'][$i] : '';
        $perticular = (isset($_POST['perticular'][$i])) ? $_POST['perticular'][$i] : '';
        $credit = (isset($_POST['credit'][$i]) && $_POST['credit'][$i] != '') ? $_POST['credit'][$i] : 0;
        $debit = (isset($_POST['debit'][$i]) && $_POST['debit'][$i] != '') ? $_POST['debit'][$i] : 0;
        $payment_mode = (isset($_POST['payment_mode'][$i])) ? $_POST['payment_mode'][$i] : '';
        $payment_mode_date = (isset($_POST['payment_mode_date'][$i]) && $_POST['payment_mode_date'][$i] != '' && $_POST['payment_mode_date'][$i] != '0000-00-00') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['payment_mode_date'][$i]))) : '';
        $payment_mode_no = (isset($_POST['payment_mode_no'][$i])) ? $_POST['payment_mode_no'][$i] : '';
        $card_name = (isset($_POST['card_name'][$i])) ? $_POST['card_name'][$i] : '';
        $other_reference = (isset($_POST['other_reference'][$i])) ? $_POST['other_reference'][$i] : '';
        $reverse_charge = (isset($_POST['reverse_charge'][$i]) && $_POST['reverse_charge'][$i] != '') ? $_POST['reverse_charge'][$i] : 0;
        $gst = (isset($_POST['gst'][$i]) && $reverse_charge == 1) ? $_POST['gst'][$i] : 0;
        $payment_type = ($debit > 0) ? 'payment' : 'receipt';
        $amount = ($debit > 0) ? $debit : $credit;
        $voucher_no = ($payment_type == 'payment') ? $paymentVoucherNo : $receiptVoucherNo;

        $query = "INSERT INTO bank_transaction SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', payment_type = '".$payment_type."', voucher_no = '".$voucher_no."', voucher_date = '".$voucher_date."', bank_id = '".$bank_id."', type = '".$type."', perticular = '".$perticular."', amount = '".$amount."', payment_mode = '".$payment_mode."', payment_mode_date = '".$payment_mode_date."', payment_mode_no = '".$payment_mode_no."', card_name = '".$card_name."', other_reference = '".$other_reference."', reverse_charge = '".$reverse_charge."', gst = '".$gst."', batch = '".$batch."', created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
        $res = mysqli_query($conn, $query);
      }
      $_SESSION['msg']['success'] = "Bank Transaction Added Successfully.";
      header('Location: view-bank-transaction.php');exit;
    }else{
      $_SESSION['msg']['fail'] = "At least one transaction is required!";
      header('Location: bank-transaction.php');exit;
    }
  }
  /*------------------ADD BANK TRANSACTION START-----------------------*/

  /*------------------UPDATE BANK TRANSACTION START-----------------------*/
  if(isset($_POST['update'])){
    $voucher_date = (isset($_POST['voucher_date']) && $_POST['voucher_date'] != '' && $_POST['voucher_date'] != '0000-00-00') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['voucher_date']))) : '';
    $bank_id = (isset($_POST['bank_id'])) ? $_POST['bank_id'] : '';
    $type = (isset($_POST['type'])) ? $_POST['type'] : '';
    $perticular = (isset($_POST['perticular'])) ? $_POST['perticular'] : '';
    $credit = (isset($_POST['credit']) && $_POST['credit'] != '') ? $_POST['credit'] : 0;
    $debit = (isset($_POST['debit']) && $_POST['debit'] != '') ? $_POST['debit'] : 0;
    $payment_mode = (isset($_POST['payment_mode'])) ? $_POST['payment_mode'] : '';
    $payment_mode_date = (isset($_POST['payment_mode_date']) && $_POST['payment_mode_date'] != '' && $_POST['payment_mode_date'] != '0000-00-00') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['payment_mode_date']))) : '';
    $payment_mode_no = (isset($_POST['payment_mode_no'])) ? $_POST['payment_mode_no'] : '';
    $card_name = (isset($_POST['card_name'])) ? $_POST['card_name'] : '';
    $other_reference = (isset($_POST['other_reference'])) ? $_POST['other_reference'] : '';
    $reverse_charge = (isset($_POST['reverse_charge']) && $_POST['reverse_charge'] != '') ? $_POST['reverse_charge'] : 0;
    $gst = (isset($_POST['gst']) && $reverse_charge == 1) ? $_POST['gst'] : 0;
    $payment_type = ($debit > 0) ? 'payment' : 'receipt';
    $amount = ($debit > 0) ? $debit : $credit;

    $query = "UPDATE bank_transaction SET payment_type = '".$payment_type."', voucher_date = '".$voucher_date."', bank_id = '".$bank_id."', type = '".$type."', perticular = '".$perticular."', amount = '".$amount."', payment_mode = '".$payment_mode."', payment_mode_date = '".$payment_mode_date."', payment_mode_no = '".$payment_mode_no."', card_name = '".$card_name."', other_reference = '".$other_reference."', reverse_charge = '".$reverse_charge."', gst = '".$gst."', modified = '".date('Y-m-d H:i:s')."', modifiedby = '".$_SESSION['auth']['id']."' WHERE id = '".$_GET['id']."'";
    $res = mysqli_query($conn, $query);
    if($res){
      $_SESSION['msg']['success'] = "Bank Transaction Added Successfully.";
      header('Location: view-bank-transaction.php');exit;
    }else{
      $_SESSION['msg']['fail'] = "Bank Transaction Added Fail!";
      header('Location: bank-transaction.php');exit;
    }

  }
  /*------------------UPDATE BANK TRANSACTION START-----------------------*/

  /*---------------------GET EDIT DATA START---------------------*/
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $query = "SELECT * FROM bank_transaction WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND id = '".$_GET['id']."'";
    $res = mysqli_query($conn, $query);
    if($res && mysqli_num_rows($res) > 0){
      $editData = mysqli_fetch_assoc($res);
      $running_balance_data = countRunningBalance($editData['perticular'], '', '', 1);
      $running_balance = (isset($running_balance_data['running_balance']) && $running_balance_data['running_balance'] != '') ? amount_format(number_format($running_balance_data['running_balance'], 2, '.', '')) : 0;
      $running_balance = ($running_balance >= 0) ? $running_balance.' Dr' : $running_balance.' Cr';
      $editData['running_balance'] = $running_balance;
    }else{
      $_SESSION['msg']['fail'] = "Somthing Want Wrong!";
      header('Location: view-bank-payment.php');exit;
    }
  }
  /*---------------------GET EDIT DATA END---------------------*/

  /*------------------GET EDIT TIME PERTICULAR START----------------*/
  if(isset($editData['type']) && $editData['type'] != ''){
    $allPerticular = [];
    $query = "SELECT id, name FROM ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND group_id = '".$editData['type']."' ORDER BY name";
    $res = mysqli_query($conn, $query);
    if($res && mysqli_num_rows($res) > 0){
      while ($row = mysqli_fetch_assoc($res)) {
        $allPerticular[] = $row;
      }
    }
  }
  /*------------------GET EDIT TIME PERTICULAR END----------------*/

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks | Bank Payment</title>
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
                      <!-- <div class="col-md-2">
                        <label for="voucher_no">Voucher No. <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="voucher_no" name="voucher_no" placeholder="Voucher No." value="<?php //echo (isset($editData['voucher_no'])) ? $editData['voucher_no'] : getBankTransactionVoucherNo('payment'); ?>" required readonly>
                      </div> -->
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
                      <div class="col-md-2">
                        <label for="bank_id">Bank<span class="text-danger">*</span></label>
                        <select class="js-example-basic-single type" name="bank_id" style="width:100%" data-parsley-errors-container="#error-bank" required>
                          <option value="">Select Bank</option>
                          <?php if(isset($allBank) && !empty($allBank)){ ?>
                            <?php foreach ($allBank as $key => $value) { ?>
                              <option value="<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>" <?php echo (isset($editData['bank_id']) && $editData['bank_id'] == $value['id']) ? 'selected' : ''; ?> ><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                        <span id="error-bank"></span>
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
                            <div class="col-md-2">
                              <label for="type">Type<span class="text-danger">*</span></label>
                              <select class="js-example-basic-single type" name="type" style="width:100%" data-parsley-errors-container="#error-type" required>
                                <option value="">Select Type</option>
                                <?php if(isset($allgroup) && !empty($allgroup)){ ?>
                                  <?php foreach ($allgroup as $key => $value) { ?>
                                    <option value="<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>" <?php echo (isset($editData['type']) && $editData['type'] == $value['id']) ? 'selected' : ''; ?> ><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></option>
                                  <?php } ?>
                                <?php } ?>
                              </select>
                              <span id="error-type"></span>
                            </div>
                            <div class="col-md-2">
                              <label for="perticular">Perticular<span class="text-danger">*</span></label>
                              <select class="js-example-basic-single perticular" name="perticular" style="width:100%" data-parsley-errors-container="#error-perticular" required>
                                <option value="">Select Perticular</option>
                                <?php if(isset($allPerticular) && !empty($allPerticular)){ ?>
                                  <?php foreach ($allPerticular as $key => $value) { ?>
                                    <option value="<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>" <?php echo (isset($editData['perticular']) && $editData['perticular'] == $value['id']) ? 'selected' : ''; ?> ><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></option>
                                  <?php } ?>
                                <?php } ?>
                              </select>
                              <span id="error-perticular"></span>
                              <div class="badge badge-primary running_balance" style="position: absolute;right: 15px;"><?php echo (isset($editData['running_balance'])) ? $editData['running_balance'] : ''; ?></div>
                            </div>
                            <div class="col-md-2">
                              <label for="credit">Credit</label>
                              <input type="text" class="form-control credit onlynumber" name="credit" value="<?php echo ((isset($editData['payment_type']) && $editData['payment_type'] == 'receipt') && (isset($editData['amount']))) ? $editData['amount'] : ''; ?>" placeholder="Credit">
                            </div>
                            <div class="col-md-2">
                              <label for="debit">Debit</label>
                              <input type="text" class="form-control debit onlynumber" name="debit" value="<?php echo ((isset($editData['payment_type']) && $editData['payment_type'] == 'payment') && (isset($editData['amount']))) ? $editData['amount'] : ''; ?>" placeholder="Debit">
                            </div>
                            <div class="col-md-2">
                              <label for="payment_mode">Payment Mode <span class="text-danger">*</span></label>
                              <select class="form-control payment_mode" name="payment_mode" data-parsley-errors-container="#error-payment" required>
                                <option value="">Select Mode</option>
                                <option value="cheque" <?php echo (isset($editData['payment_mode']) && $editData['payment_mode'] == 'cheque') ? 'selected' : ''; ?> >Cheque</option>
                                <option value="dd" <?php echo (isset($editData['payment_mode']) && $editData['payment_mode'] == 'dd') ? 'selected' : ''; ?> >DD</option>
                                <option value="net_banking" <?php echo (isset($editData['payment_mode']) && $editData['payment_mode'] == 'net_banking') ? 'selected' : ''; ?> >Net Banking</option>
                                <option value="credit_debit_card" <?php echo (isset($editData['payment_mode']) && $editData['payment_mode'] == 'credit_debit_card') ? 'selected' : ''; ?> >Credit/Debit Card</option>
                                <option value="other" <?php echo (isset($editData['payment_mode']) && $editData['payment_mode'] == 'other') ? 'selected' : ''; ?> >Other</option>
                              </select>
                              <span id="error-payment"></span>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-md-2 payment_mode_date_div" <?php echo ((isset($editData['payment_mode'])) && ($editData['payment_mode'] == 'net_banking' || $editData['payment_mode'] == 'credit_debit_card' || $editData['payment_mode'] == 'other')) ? 'style="display:none;"' : ''; ?> >
                              <?php 
                                if(isset($editData['payment_mode']) && $editData['payment_mode'] == 'cheque'){
                                  $labledate = 'Check Date';
                                  $lableno = "Check No.";
                                }elseif(isset($editData['payment_mode']) && $editData['payment_mode'] == 'dd'){
                                  $labledate = 'DD Date';
                                  $lableno = "DD No.";
                                }elseif(isset($editData['payment_mode']) && $editData['payment_mode'] == 'net_banking'){
                                  $labledate = '';
                                  $lableno = "UTR No.";
                                }elseif(isset($editData['payment_mode']) && $editData['payment_mode'] == 'credit_debit_card'){
                                  $labledate = '';
                                  $lableno = "Card No.";
                                }elseif(isset($editData['payment_mode']) && $editData['payment_mode'] == 'other'){
                                  $labledate = '';
                                  $lableno = '';
                                }else{
                                  $labledate = '';
                                  $lableno = '';
                                }
                              ?>
                              <label for="payment_mode_date"><span class="payment_mode_date_lable"><?php echo (isset($labledate)) ? $labledate : ''; ?></span> <span class="text-danger">*</span></label>
                              <div class="input-group date datepicker">
                                <input type="text" class="form-control payment_mode_date" name="payment_mode_date" value="<?php echo (isset($editData['payment_mode_date']) && $editData['payment_mode_date'] != '' && $editData['payment_mode_date'] != '0000-00-00') ? date('d/m/Y',strtotime($editData['payment_mode_date'])) : ''; ?>" required>
                                <span class="input-group-addon input-group-append border-left">
                                  <span class="mdi mdi-calendar input-group-text"></span>
                                </span>
                              </div>
                            </div>

                            <div class="col-md-2 payment_mode_no_div" <?php echo (isset($editData['payment_mode']) && $editData['payment_mode'] == 'other') ? 'style="display: none;"' : ''; ?> >
                              <label for="payment_mode_no"><span class="payment_mode_no_lable"><?php echo (isset($lableno)) ? $lableno : ''; ?></span> <span class="text-danger">*</span></label>
                              <input type="text" name="payment_mode_no" value="<?php echo (isset($editData['payment_mode_no'])) ? $editData['payment_mode_no'] : ''; ?>" class="form-control payment_mode_no" required>
                            </div>

                            <div class="col-md-2 card_name_div" <?php echo (isset($editData['payment_mode']) && $editData['payment_mode'] != 'credit_debit_card') ? 'style="display: none;"' : ''; ?> >
                              <label>Card Name<span class="text-danger">*</span></label>
                              <input type="text" name="card_name" value="<?php echo (isset($editData['card_name'])) ? $editData['card_name'] : ''; ?>" class="form-control card_name" required>
                            </div>

                            <div class="col-md-2 other_reference_div" <?php echo (isset($editData['payment_mode']) && $editData['payment_mode'] != 'other') ? 'style="display: none;"' : ''; ?> >
                              <label>Other Reference<span class="text-danger">*</span></label>
                              <input type="text" name="other_reference" value="<?php echo (isset($editData['other_reference'])) ? $editData['other_reference'] : ''; ?>" class="form-control other_reference" required>
                            </div>
                            <div class="col-md-2">
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
                            <div class="col-md-2">
                              <div class="gst-div" <?php echo (isset($editData['reverse_charge']) && $editData['reverse_charge'] != 1) ? 'style="display:none;"' : ''; ?> >
                                <label for="gst">GST %</label>
                                <select name="gst" class="form-control">
                                  <option value="5" <?php echo (isset($editData['gst']) &&  $editData['gst'] == 5) ? 'selected' : ''; ?> >5%</option>
                                  <option value="12" <?php echo (isset($editData['gst']) &&  $editData['gst'] == 12) ? 'selected' : ''; ?> >12%</option>
                                  <option value="18" <?php echo (isset($editData['gst']) &&  $editData['gst'] == 18) ? 'selected' : ''; ?> >18%</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        <?php }else{ ?>
                          <div class="row form-group">
                            <div class="col-md-2">
                              <label for="type">Type<span class="text-danger">*</span></label>
                              <select class="js-example-basic-single type" name="type[]" style="width:100%" data-parsley-errors-container="#error-type" required>
                                <option value="">Select Type</option>
                                <?php if(isset($allgroup) && !empty($allgroup)){ ?>
                                  <?php foreach ($allgroup as $key => $value) { ?>
                                    <option value="<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>"><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></option>
                                  <?php } ?>
                                <?php } ?>
                              </select>
                              <span id="error-type"></span>
                            </div>
                            <div class="col-md-2">
                              <label for="perticular">Perticular<span class="text-danger">*</span></label>
                              <select class="js-example-basic-single perticular" name="perticular[]" style="width:100%" data-parsley-errors-container="#error-perticular" required>
                                <option value="">Select Perticular</option>
                              </select>
                              <span id="error-perticular"></span>
                              <div class="badge badge-primary running_balance" style="display: none;position: absolute;right: 15px;"></div>
                            </div>
                            <div class="col-md-2">
                              <label for="credit">Credit</label>
                              <input type="text" class="form-control credit onlynumber" name="credit[]" placeholder="Credit">
                            </div>
                            <div class="col-md-2">
                              <label for="debit">Debit</label>
                              <input type="text" class="form-control debit onlynumber" name="debit[]" placeholder="Debit">
                            </div>
                            <div class="col-md-2">
                              <label for="payment_mode">Payment Mode <span class="text-danger">*</span></label>
                              <select class="form-control payment_mode" name="payment_mode[]" data-parsley-errors-container="#error-payment" required>
                                <option value="">Select Mode</option>
                                <option value="cheque">Cheque</option>
                                <option value="dd">DD</option>
                                <option value="net_banking">Net Banking</option>
                                <option value="credit_debit_card">Credit/Debit Card</option>
                                <option value="other">Other</option>
                              </select>
                              <span id="error-payment"></span>
                            </div>
                            <div class="col-md-2 text-right" style="padding-top: 30px;">
                              <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item"><i class="fa fa-plus mr-0 ml-0"></i></button>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-md-2 payment_mode_date_div" style="display: none;">
                              <label for="payment_mode_date"><span class="payment_mode_date_lable">Check Date</span> <span class="text-danger">*</span></label>
                              <div class="input-group date datepicker">
                                <input type="text" class="form-control payment_mode_date" name="payment_mode_date[]" value="<?php echo date('d/m/Y'); ?>" required>
                                <span class="input-group-addon input-group-append border-left">
                                  <span class="mdi mdi-calendar input-group-text"></span>
                                </span>
                              </div>
                            </div>

                            <div class="col-md-2 payment_mode_no_div" style="display: none;">
                              <label for="payment_mode_no"><span class="payment_mode_no_lable">Check No.</span> <span class="text-danger">*</span></label>
                              <input type="text" name="payment_mode_no[]" class="form-control payment_mode_no" required>
                            </div>

                            <div class="col-md-2 card_name_div" style="display: none;">
                              <label>Card Name<span class="text-danger">*</span></label>
                              <input type="text" name="card_name[]" class="form-control card_name" required>
                            </div>

                            <div class="col-md-2 other_reference_div" style="display: none;">
                              <label>Other Reference<span class="text-danger">*</span></label>
                              <input type="text" name="other_reference[]" class="form-control other_reference" required>
                            </div>

                            <div class="col-md-2">
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
                            <div class="col-md-2">
                              <div class="gst-div" style="display: none;">
                                <label for="gst">GST %</label>
                                <select name="gst[]" class="form-control">
                                  <option value="5">5%</option>
                                  <option value="12">12%</option>
                                  <option value="18">18%</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                    <hr/>
                    <div id="detail-footer">
                      <a href="view-bank-transaction.php" class="btn btn-light">Back</a>
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
        <div class="col-md-2">
          <label for="type">Type<span class="text-danger">*</span></label>
          <select class="type" name="type[]" style="width:100%" data-parsley-errors-container="#error-type" required>
            <option value="">Select Type</option>
            <?php if(isset($allgroup) && !empty($allgroup)){ ?>
              <?php foreach ($allgroup as $key => $value) { ?>
                <option value="<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>"><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></option>
              <?php } ?>
            <?php } ?>
          </select>
          <span id="error-type"></span>
        </div>
        <div class="col-md-2">
          <label for="perticular">Perticular<span class="text-danger">*</span></label>
          <select class="perticular" name="perticular[]" style="width:100%" data-parsley-errors-container="#error-perticular" required>
            <option value="">Select Perticular</option>
          </select>
          <span id="error-perticular"></span>
          <div class="badge badge-primary running_balance" style="display: none;position: absolute;right: 15px;"></div>
        </div>
        <div class="col-md-2">
          <label for="credit">Credit</label>
          <input type="text" class="form-control credit onlynumber" name="credit[]" placeholder="Credit">
        </div>
        <div class="col-md-2">
          <label for="debit">Debit</label>
          <input type="text" class="form-control debit onlynumber" name="debit[]" placeholder="Debit">
        </div>
        <div class="col-md-2">
          <label for="payment_mode">Payment Mode <span class="text-danger">*</span></label>
          <select class="form-control payment_mode" name="payment_mode[]" data-parsley-errors-container="#error-perticular" required>
            <option value="">Select Mode</option>
            <option value="cheque">Cheque</option>
            <option value="dd">DD</option>
            <option value="net_banking">Net Banking</option>
            <option value="credit_debit_card">Credit/Debit Card</option>
            <option value="other">Other</option>
          </select>
          <span id="error-payment"></span>
        </div>
        <div class="col-md-2 text-right" style="padding-top: 30px;">
          <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item"><i class="fa fa-plus mr-0 ml-0"></i></button>
          <button type="button" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-item"><i class="fa fa-close mr-0 ml-0"></i></button>
        </div>
      </div>
      <div class="row form-group">
        <div class="col-md-2 payment_mode_date_div" style="display: none;">
          <label for="payment_mode_date"><span class="payment_mode_date_lable">Check Date</span> <span class="text-danger">*</span></label>
          <div class="input-group date datepicker">
            <input type="text" class="form-control payment_mode_date" name="payment_mode_date[]" value="<?php echo date('d/m/Y'); ?>" required>
            <span class="input-group-addon input-group-append border-left">
              <span class="mdi mdi-calendar input-group-text"></span>
            </span>
          </div>
        </div>

        <div class="col-md-2 payment_mode_no_div" style="display: none;">
          <label for="payment_mode_no"><span class="payment_mode_no_lable">Check No.</span> <span class="text-danger">*</span></label>
          <input type="text" name="payment_mode_no[]" class="form-control payment_mode_no" required>
        </div>

        <div class="col-md-2 card_name_div" style="display: none;">
          <label>Card Name<span class="text-danger">*</span></label>
          <input type="text" name="card_name[]" class="form-control card_name" required>
        </div>

        <div class="col-md-2 other_reference_div" style="display: none;">
          <label>Other Reference<span class="text-danger">*</span></label>
          <input type="text" name="other_reference[]" class="form-control other_reference" required>
        </div>

        <div class="col-md-2">
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
        <div class="col-md-2">
          <div class="gst-div" style="display: none;">
            <label for="gst">GST %</label>
            <select name="gst[]" class="form-control">
              <option value="5">5%</option>
              <option value="12">12%</option>
              <option value="18">18%</option>
            </select>
          </div>
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
    $.listen('parsley:field:validated', function(fieldInstance){
      if (fieldInstance.$element.is(":hidden")) {
          // hide the message wrapper
          fieldInstance._ui.$errorsWrapper.css('display', 'none');
          // set validation result to true
          fieldInstance.validationResult = true;
          return true;
      }
    });
  </script>
  <!-- page js -->
  <script src="js/custom/bank-transaction.js"></script>
  <script src="js/custom/onlynumber.js"></script>
  <!-- End custom js for this page-->
</body>
</html>
