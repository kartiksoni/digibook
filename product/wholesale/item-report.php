<?php $title = "Item Report"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>
<?php 
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';

  $allProduct = [];
  $productQ = "SELECT product_name as name FROM product_master WHERE pharmacy_id = '".$pharmacy_id."' GROUP BY product_name ORDER BY product_name";
  $productR = mysqli_query($conn, $productQ);
  if($productR && mysqli_num_rows($productR) > 0){
    while ($productRow = mysqli_fetch_assoc($productR)) {
      $allProduct[] = $productRow;
    }
  }

  if((isset($_POST['type']) && $_POST['type'] == 'batch_wise') && (isset($_POST['item']) && $_POST['item'] != '')){
    $allBatch = [];
    $batchQ = "SELECT id, batch_no, product_name FROM product_master WHERE product_name = '".$_POST['item']."' AND pharmacy_id = '".$pharmacy_id."' AND batch_no != '' AND batch_no != '-'  GROUP BY batch_no ORDER BY batch_no";
    $batchR = mysqli_query($conn, $batchQ);
    if($batchR && mysqli_num_rows($batchR) > 0){
      while ($batchRow = mysqli_fetch_assoc($batchR)) {
        $allBatch[] = $batchRow;
      }
    }
  }


  if(isset($_POST['search'])){
    $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : '';
    $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';
    $item = (isset($_POST['item'])) ? $_POST['item'] : '';//product name
    $type = (isset($_POST['type'])) ? $_POST['type'] : '';//all or batch wise
    $batch = (isset($_POST['batch']) && $type == 'batch_wise') ? $_POST['batch'] : '';//batch no
    $view = (isset($_POST['view'])) ? $_POST['view'] : '';//detail or summary

    $searchdata = itemReport($from, $to, $item, $type, $batch, $view, 1);
  }
  
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Item Report</title>
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

            <div class="row">
             <?php include('include/stock_header.php');?>
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Item Report</h4><hr class="alert-dark">
                    <form class="forms-sample" method="POST" autocomplete="off">
                      <div class="form-group row">

                        <div class="col-12 col-md-2">
                            <label for="from">From <span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker" name="from" id="from" placeholder="DD/MM/YYYY" value="<?php echo (isset($_POST['from'])) ? $_POST['from'] : date('d/m/Y'); ?>" required>
                        </div>

                        <div class="col-12 col-md-2">
                            <label for="to">To <span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker" name="to" id="to" placeholder="DD/MM/YYYY" value="<?php echo (isset($_POST['to'])) ? $_POST['to'] : date('d/m/Y'); ?>" required>
                        </div>

                        <div class="col-12 col-md-3">
                          <label for="company">Item <span class="text-danger">*</span></label>
                          <select class="js-example-basic-single" name="item" id="item" style="width:100%" data-parsley-errors-container="#error-item" required> 
                            <option value="">Select Item</option>
                            <?php if(isset($allProduct) && !empty($allProduct)){ ?>
                              <?php foreach ($allProduct as $key => $value) { ?>
                                <option value="<?php echo (isset($value['name'])) ? $value['name'] : ''; ?>" <?php echo (isset($_POST['item']) && $_POST['item'] == $value['name']) ? 'selected' : ''; ?> ><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></option>
                              <?php } ?>
                            <?php } ?>
                          </select>
                          <span id="error-item"></span>
                        </div>

                        <div class="col-12 col-md-3">
                          <?php 
                            $all = 'checked';
                            $batch_wise = '';
                            if(isset($_POST['type']) && $_POST['type'] == 'all'){
                              $all = 'checked';
                            }elseif(isset($_POST['type']) && $_POST['type'] == 'batch_wise'){
                              $batch_wise = 'checked';
                            }
                          ?>
                          <label for="view">Select Type</label>
                          <div class="row no-gutters">
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input bill_type" name="type" value="all" data-parsley-multiple="type" <?php echo (isset($all)) ? $all : ''; ?> >
                                     All
                                  <i class="input-helper"></i></label>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input bill_type" name="type" value="batch_wise" data-parsley-multiple="type" <?php echo (isset($batch_wise)) ? $batch_wise : ''; ?> >
                                    Batch Wise
                                <i class="input-helper"></i></label>
                                </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-12 col-md-2" id="batch_div" style="<?php echo (isset($_POST['type']) && $_POST['type'] == 'batch_wise') ? 'display:inline;' : 'display:none;'; ?>">
                          <label for="batch">Batch <span class="text-danger">*</span></label>
                          <select class="js-example-basic-single" name="batch" id="batch" style="width:100%" data-parsley-errors-container="#error-batch" required> 
                            <option value="">Select Batch</option>
                            <?php if(isset($allBatch) && !empty($allBatch)){ ?>
                              <?php foreach ($allBatch as $key => $value) { ?>
                                <option value="<?php echo (isset($value['batch_no'])) ? $value['batch_no'] : ''; ?>" <?php echo ((isset($_POST['type']) && $_POST['type'] == 'batch_wise') && (isset($_POST['batch']) && $_POST['batch'] == $value['batch_no'])) ? 'selected' : ''; ?> ><?php echo (isset($value['batch_no'])) ? $value['batch_no'] : ''; ?></option>
                              <?php } ?>
                            <?php } ?>
                          </select>
                          <span id="error-batch"></span>
                        </div>

                      </div>
                      <div class="form-group row">
                        <div class="col-12 col-md-2">
                          <?php 
                            $detail = 'checked';
                            $summary = '';
                            if(isset($_POST['view']) && $_POST['view'] == 'detail'){
                              $detail = 'checked';
                            }elseif(isset($_POST['view']) && $_POST['view'] == 'summary'){
                              $summary = 'checked';
                            }
                          ?>
                          <label for="view">Select Any One</label>
                          <div class="row no-gutters">
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input bill_type" name="view" value="detail" data-parsley-multiple="view" <?php echo (isset($detail)) ? $detail : ''; ?> >
                                     Detail
                                  <i class="input-helper"></i></label>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input bill_type" name="view" value="summary" data-parsley-multiple="view" <?php echo (isset($summary)) ? $summary : ''; ?> >
                                    Summary
                                <i class="input-helper"></i></label>
                                </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-md-2">
                          <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                          <?php if(isset($searchdata) && !empty($searchdata)){ ?>
                            <a href="item-report-print.php?from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>&item=<?php echo (isset($_POST['item'])) ? $_POST['item'] : ''; ?>&type=<?php echo (isset($_POST['type'])) ? $_POST['type'] : ''; ?>&batch=<?php echo (isset($_POST['batch'])) ? $_POST['batch'] : ''; ?>&view=<?php echo (isset($_POST['view'])) ? $_POST['view'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
                          <?php } ?>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>

              <?php if((isset($searchdata)) && (isset($_POST['view']) && $_POST['view'] == 'detail')){ ?>
                <div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-12">
                          <table width="100%" cellspacing="5" cellpadding="5" border="1">
                            <?php if(!empty($searchdata)){ $i = 1; ?>
                                <?php foreach ($searchdata as $key => $value) { ?>
                                  <?php if((isset($value['detail']['credit']) && !empty($value['detail']['credit'])) || (isset($value['detail']['debit']) && !empty($value['detail']['debit']))){ ?>
                                      <?php $credit_amount = 0; $credit_qty = 0; $debit_amount = 0; $debit_qty = 0; ?>
                                      <thead>
                                        <?php if($i != 1){ ?>
                                          <tr style="height: 30px;">
                                            <th colspan="6"></th>
                                          </tr>
                                        <?php } ?>
                                        <tr>
                                          <th colspan="3"><?php echo (isset($value['product_name'])) ? $value['product_name'] : ''; ?></th>
                                          <th colspan="3">Batch No. : <?php echo (isset($value['batch_no'])) ? $value['batch_no'] : ''; ?></th>
                                        </tr>
                                        <tr>
                                          <th colspan="6" style="text-align: center;">Item Ledger Dated <?php echo (isset($_POST['from']) && $_POST['from'] != '') ? date('d/m/Y',strtotime($_POST['from'])) : ''; ?> to <?php echo (isset($_POST['to']) && $_POST['to'] != '') ? date('d/m/Y',strtotime($_POST['to'])) : ''; ?></th>
                                        </tr>
                                        <tr>
                                          <th colspan="3">Credit</th>
                                          <th colspan="3">Debit</th>
                                        </tr>
                                        <tr>
                                          <th width="15%" class="text-right pr-15">Amount</th>
                                          <th width="10%" class="text-right pr-15">Sale Qty.</th>
                                          <th width="25%" class="pl-15">Account Head/Description</th>
                                          <th width="15%" class="text-right pr-15">Amount</th>
                                          <th width="10%" class="text-right pr-15">Purchase Qty.</th>
                                          <th width="25%" class="pl-15">Account Head/Description</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                          <td colspan="3" style="vertical-align: top;">
                                            <table width="100%" cellspacing="5" cellpadding="5">
                                              <tbody>
                                                <?php if(isset($value['detail']['credit']) && !empty($value['detail']['credit'])){ ?>
                                                  <?php foreach ($value['detail']['credit'] as $key1 => $value1) { ?>
                                                    <tr>
                                                      <td width="30%" class="text-right pr-15">
                                                        <?php 
                                                          $amount1 = (isset($value1['amount']) && $value1['amount'] != '') ? $value1['amount'] : '';
                                                          $credit_amount += $amount1;
                                                          echo amount_format(number_format($amount1, 2, '.', ''));
                                                        ?>
                                                      </td>
                                                      <td width="20%" class="text-right pr-15">
                                                        <?php 
                                                          $qty1 = (isset($value1['qty']) && $value1['qty'] != '') ? $value1['qty'] : '';
                                                          $credit_qty += $qty1;
                                                          echo amount_format(number_format($qty1, 2, '.', ''));
                                                        ?>
                                                      </td>
                                                      <td width="50%" class="pl-15"><?php echo (isset($value1['narration'])) ? $value1['narration'] : ''; ?></td>
                                                    </tr>
                                                  <?php } ?>
                                                <?php } ?>
                                              </tbody>
                                            </table>
                                          </td>
                                          <td colspan="3" style="vertical-align: top;">
                                            <table width="100%" cellspacing="5" cellpadding="5">
                                              <tbody>
                                                <?php if(isset($value['detail']['debit']) && !empty($value['detail']['debit'])){ ?>
                                                  <?php foreach ($value['detail']['debit'] as $key2 => $value2) { ?>
                                                    <tr>
                                                      <td width="30%" class="text-right pr-15">
                                                        <?php 
                                                          $amount2 = (isset($value2['amount']) && $value2['amount'] != '') ? $value2['amount'] : '';
                                                          $debit_amount += $amount2;
                                                          echo amount_format(number_format($amount2, 2, '.', ''));
                                                        ?>
                                                      </td>
                                                      <td width="20%" class="text-right pr-15">
                                                        <?php 
                                                          $qty2 = (isset($value2['qty']) && $value2['qty'] != '') ? $value2['qty'] : '';
                                                          $debit_qty += $qty2;
                                                          echo amount_format(number_format($qty2, 2, '.', ''));
                                                        ?>
                                                      </td>
                                                      <td width="50%" class="pl-15"><?php echo (isset($value2['narration'])) ? $value2['narration'] : ''; ?></td>
                                                    </tr>
                                                  <?php } ?>
                                                <?php } ?>
                                              </tbody>
                                            </table>
                                          </td>
                                       </tr>
                                      </tbody>
                                      <tbody>
                                        <?php 
                                          $credit_amount = (isset($credit_amount) && $credit_amount != '') ? $credit_amount : 0;
                                          $debit_amount = (isset($debit_amount) && $debit_amount != '') ? $debit_amount : 0;
                                          $credit_qty = (isset($credit_qty) && $credit_qty != '') ? $credit_qty : 0;
                                          $debit_qty = (isset($debit_qty) && $debit_qty != '') ? $debit_qty : 0;

                                          $cash_on_hand_amount = ($credit_amount-$debit_amount);
                                          $cash_on_hand_qty = ($credit_qty-$debit_qty);

                                        ?>
                                        <tr>
                                            <th class="text-right pr-15">
                                              <?php echo (isset($credit_amount) && $credit_amount != '') ? amount_format(number_format($credit_amount, 2, '.', '')) : 0; ?>
                                            </th>
                                            <th class="text-right pr-15">
                                              <?php echo (isset($credit_qty) && $credit_qty != '') ? amount_format(number_format($credit_qty, 2, '.', '')) : 0; ?>
                                            </th>
                                            <th></th>
                                            <th style="border-right: 0px;" class="text-right pr-15">
                                              <?php echo (isset($debit_amount) && $debit_amount != '') ? amount_format(number_format($debit_amount, 2, '.', '')) : 0; ?>
                                            </th>
                                            <th class="text-right pr-15">
                                              <?php echo (isset($debit_qty) && $debit_qty != '') ? amount_format(number_format($debit_qty, 2, '.', '')) : 0; ?>
                                            </th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th class="text-right pr-15">
                                              <?php 
                                                  if($cash_on_hand_amount < 0){
                                                      echo amount_format(number_format(abs($cash_on_hand_amount), 2, '.', ''));
                                                      $credit_amount += abs($cash_on_hand_amount);
                                                  }
                                              ?>
                                            </th>
                                            <th class="text-right pr-15">
                                              <?php 
                                                  if($cash_on_hand_qty < 0){
                                                      echo amount_format(number_format(abs($cash_on_hand_qty), 2, '.', ''));
                                                      $credit_qty += abs($cash_on_hand_qty);
                                                  }
                                              ?>
                                            </th>
                                            <th>
                                              <?php 
                                                  if($cash_on_hand_amount < 0 || $cash_on_hand_qty < 0){
                                                      echo 'Cash On Hand Db.';
                                                  }
                                              ?>
                                            </th>

                                            <th class="text-right pr-15">
                                              <?php 
                                                  if($cash_on_hand_amount >= 0){
                                                      echo amount_format(number_format(abs($cash_on_hand_amount), 2, '.', ''));
                                                      $debit_amount += abs($cash_on_hand_amount);
                                                  }
                                              ?>
                                            </th>
                                            <th class="text-right pr-15">
                                              <?php 
                                                  if($cash_on_hand_qty >= 0){
                                                      echo amount_format(number_format(abs($cash_on_hand_qty), 2, '.', ''));
                                                      $debit_qty += abs($cash_on_hand_qty);
                                                  }
                                              ?>
                                            </th>
                                            <th>
                                              <?php 
                                                  if($cash_on_hand_amount >= 0 || $cash_on_hand_qty >= 0){
                                                      echo 'Cash On Hand Cr.';
                                                  }
                                              ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="text-right pr-15"><?php echo amount_format(number_format(abs($credit_amount), 2, '.', '')); ?></th>
                                            <th class="text-right pr-15"><?php echo amount_format(number_format(abs($credit_qty), 2, '.', '')); ?></th>
                                            <th></th>
                                            <th class="text-right pr-15"><?php echo amount_format(number_format(abs($debit_amount), 2, '.', '')); ?></th>
                                            <th class="text-right pr-15"><?php echo amount_format(number_format(abs($debit_qty), 2, '.', '')); ?></th>
                                            <th></th>
                                        </tr>
                                      </tbody>
                                  <?php $i++; } ?>
                                <?php } ?>
                            <?php }else{ ?>
                              <h5>No Record Found!</h5>
                            <?php } ?>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php }elseif((isset($searchdata)) && (isset($_POST['view']) && $_POST['view'] == 'summary')){ ?>
                <div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-12">
                          <table width="100%" cellspacing="5" cellpadding="5" border="1">
                            <thead>
                              <tr>
                                <th rowspan="3" width="20%" class="text-center">Item Name</th>
                                <th rowspan="3" width="10%" class="text-center">Batch No.</th>
                                <th colspan="4" width="35%" class="text-center">Credit</th>
                                <th colspan="4" width="35%" class="text-center">Debit</th>
                              </tr>
                              <tr>
                                <th rowspan="2" class="text-right">Amount</th>
                                <th rowspan="2" class="text-right">Qty</th>
                                <th colspan="2" class="text-center">Cash On Hand Db.</th>
                                <th rowspan="2" class="text-right">Amount</th>
                                <th rowspan="2" class="text-right">Qty</th>
                                <th colspan="2" class="text-center">Cash On Hand Cr.</th>
                              </tr>
                              <tr>
                                <th class="text-right">Amount</th>
                                <th class="text-right">Qty</th>
                                <th class="text-right">Amount</th>
                                <th class="text-right">Qty</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php if (!empty($searchdata)) { ?>
                                <?php $c_amount = 0; $c_qty = 0; $c_c_amount = 0; $c_c_qty = 0; $d_amount = 0; $d_qty = 0; $d_c_amount = 0; $d_c_qty = 0; ?>
                                <?php foreach ($searchdata as $key => $value) { ?>
                                  <tr>
                                    <td><?php echo (isset($value['product_name'])) ? $value['product_name'] : ''; ?></td>
                                    <td><?php echo (isset($value['batch_no'])) ? $value['batch_no'] : ''; ?></td>

                                    <td class="text-right">
                                      <?php
                                        $cr_amount =  (isset($value['credit']['amount']) && $value['credit']['amount'] != '') ? $value['credit']['amount'] : 0;
                                        $c_amount += $cr_amount;
                                        echo amount_format(number_format($cr_amount, 2, '.', '')); 
                                      ?>
                                    </td>
                                    <td class="text-right">
                                      <?php
                                        $cr_qty = (isset($value['credit']['qty']) && $value['credit']['qty'] != '') ? $value['credit']['qty'] : 0;
                                        $c_qty += $cr_qty;
                                        echo amount_format(number_format($cr_qty, 2, '.', '')); 
                                      ?>
                                    </td>
                                    <td class="text-right">
                                      <?php
                                        if(isset($value['credit']['cash_on_hand_amount']) && $value['credit']['cash_on_hand_amount'] != ''){
                                          $c_c_amount += abs($value['credit']['cash_on_hand_amount']);
                                          echo amount_format(number_format(abs($value['credit']['cash_on_hand_amount']), 2, '.', ''));
                                        }
                                      ?>
                                    </td>
                                    <td class="text-right">
                                      <?php
                                        if(isset($value['credit']['cash_on_hand_qty']) && $value['credit']['cash_on_hand_qty'] != ''){
                                          $c_c_qty += abs($value['credit']['cash_on_hand_qty']);
                                          echo amount_format(number_format(abs($value['credit']['cash_on_hand_qty']), 2, '.', ''));
                                        }
                                      ?>
                                    </td>

                                    <td class="text-right">
                                      <?php
                                        $db_amount = (isset($value['debit']['amount']) && $value['debit']['amount'] != '') ? $value['debit']['amount'] : 0;
                                        $d_amount += $db_amount;
                                        echo  amount_format(number_format($db_amount, 2, '.', '')); 
                                      ?>
                                    </td>
                                    <td class="text-right">
                                      <?php
                                        $db_qty = (isset($value['debit']['qty']) && $value['debit']['qty'] != '') ? $value['debit']['qty'] : 0;
                                        $d_qty += $db_qty;
                                        echo amount_format(number_format($db_qty, 2, '.', '')); 
                                      ?>
                                    </td>
                                    <td class="text-right">
                                      <?php
                                        if(isset($value['debit']['cash_on_hand_amount']) && $value['debit']['cash_on_hand_amount'] != ''){
                                          $d_c_amount += abs($value['debit']['cash_on_hand_amount']);
                                          echo amount_format(number_format(abs($value['debit']['cash_on_hand_amount']), 2, '.', ''));
                                        }
                                      ?>
                                    </td>
                                    <td class="text-right">
                                      <?php
                                        if(isset($value['debit']['cash_on_hand_qty']) && $value['debit']['cash_on_hand_qty'] != ''){
                                          $d_c_qty += abs($value['debit']['cash_on_hand_qty']);
                                          echo amount_format(number_format(abs($value['debit']['cash_on_hand_qty']), 2, '.', ''));
                                        }
                                      ?>
                                    </td>
                                  </tr>
                                <?php } ?>
                                <tr style="font-weight: bold;">
                                  <td colspan="2" class="text-center">Total</td>

                                  <td class="text-right"><?php echo (isset($c_amount) && $c_amount != '') ? amount_format(number_format($c_amount, 2, '.', '')) : 0; ?></td>
                                  <td class="text-right"><?php echo (isset($c_qty) && $c_qty != '') ? amount_format(number_format($c_qty, 2, '.', '')) : 0; ?></td>
                                  <td class="text-right"><?php echo (isset($c_c_amount) && $c_c_amount != '') ? amount_format(number_format($c_c_amount, 2, '.', '')) : 0; ?></td>
                                  <td class="text-right"><?php echo (isset($c_c_qty) && $c_c_qty != '') ? amount_format(number_format($c_c_qty, 2, '.', '')) : 0; ?></td>

                                  <td class="text-right"><?php echo (isset($d_amount) && $d_amount != '') ? amount_format(number_format($d_amount, 2, '.', '')) : 0; ?></td>
                                  <td class="text-right"><?php echo (isset($d_qty) && $d_qty != '') ? amount_format(number_format($d_qty, 2, '.', '')) : 0; ?></td>
                                  <td class="text-right"><?php echo (isset($d_c_amount) && $d_c_amount != '') ? amount_format(number_format($d_c_amount, 2, '.', '')) : 0; ?></td>
                                  <td class="text-right"><?php echo (isset($d_c_qty) && $d_c_qty != '') ? amount_format(number_format($d_c_qty, 2, '.', '')) : 0; ?></td>
                                </tr>
                              <?php }else{ ?>
                                <tr>
                                  <td colspan="10">No Record Found!</td>
                                </tr>
                              <?php } ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>
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
  <script src="js/data-table.js"></script> 
  <script>
    $('.datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy',
      autoclose : true
    });
 </script>
  <!-- script for custom validation -->
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
<script src="js/custom/onlynumber.js"></script>
<script src="js/custom/item-report.js"></script>

 <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
</body>


</html>
