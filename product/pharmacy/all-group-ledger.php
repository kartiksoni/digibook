<?php $title = "All Ledger"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>
<?php 
$pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
$financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

if(isset($_POST['search'])){
  $id = (isset($_POST['ladger'])) ? $_POST['ladger'] : '';
  $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : ''; 
  $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';

  $searchdata = allLedgerdetaills($id, $from, $to);
 
}

   // get all Ledger
$allLedger = [];

$ledgerQ = "SELECT id, name FROM ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND group_id != 29 ORDER BY name";
$ledgerR = mysqli_query($conn, $ledgerQ);
if($ledgerR && mysqli_num_rows($ledgerR) > 0){
  while ($ledgerRow = mysqli_fetch_assoc($ledgerR)) {
    $allLedger[] = $ledgerRow;
  }
}

// get financial detail
  $financialQ = "SELECT id, start_date, end_date FROM financial WHERE id = '".$financial_id."'";
  $financialR = mysqli_query($conn, $financialQ);
  if($financialR && mysqli_num_rows($financialR)){
    $financialRow = mysqli_fetch_assoc($financialR);
    $defaultfrom = (isset($financialRow['start_date']) && $financialRow['start_date'] != '' && $financialRow['start_date'] != '0000-00-00') ? date('d/m/Y',strtotime($financialRow['start_date'])) : '';
    $defaultto = (isset($financialRow['end_date']) && $financialRow['end_date'] != '' && $financialRow['end_date'] != '0000-00-00') ? date('d/m/Y',strtotime($financialRow['end_date'])) : '';
  }else{
    $defaultfrom = '';
    $defaultto = '';
  }



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | All Group Ledger</title>
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
            <?php include "include/account_menus_header.php"; ?>
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">All Group Ledger</h4><hr class="alert-dark">
                  <form class="forms-sample" method="POST" autocomplete="off">
                    <div class="form-group row">

                      <div class="col-12 col-md-2">
                        <label for="from">From</label>
                        <input type="text" class="form-control datepicker" name="from" id="from" placeholder="DD/MM/YYYY" value="<?php echo (isset($_POST['from'])) ? $_POST['from'] : date('d/m/Y'); ?>" required>
                      </div>

                      <div class="col-12 col-md-2">
                        <label for="to">To</label>
                        <input type="text" class="form-control datepicker" name="to" id="to" placeholder="DD/MM/YYYY" value="<?php echo (isset($_POST['to'])) ? $_POST['to'] : date('d/m/Y'); ?>" required>
                      </div>

                      <div class="col-12 col-md-3">
                        <label>Perticular<span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" style="width:100%" name="ladger" id="ladger_list" data-parsley-errors-container="#error-ladger" required >  
                          <option value="">Select Perticular</option>
                          <?php if(!empty($allLedger)){ ?>
                            <?php foreach ($allLedger as $key => $value) { ?>
                              <option value="<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>" <?php echo (isset($_POST['ladger']) && $_POST['ladger'] == $value['id']) ? 'selected' : ''; ?> ><?php echo (isset($value['name']) && $value['name'] != '') ? ucwords(strtolower($value['name'])) : 'Unknown Perticular'; ?></option>
                            <?php } ?>
                          <?php } ?>

                        </select>
                        <span id="error-ladger"></span>
                      </div>

                      <div class="col-12 col-md-2">
                        <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                        <?php if(isset($searchdata)){ ?>
                          <a href="all-group-ledger-print.php?ladger=<?php echo (isset($_POST['ladger'])) ? $_POST['ladger'] : ''; ?>&from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
                        <?php } ?>
                      </div>

                      
                      <!--<label class="pull-right bg-success color-white p-2 ?php //echo (isset($searchdata)) ? ' display-block' : ' display-none'; ?>" style="margin-top: 30px;" id="running_balance"> Running Balance : ?php echo //(isset($searchdata['running_balance'])) ? amount_format(number_format(abs($searchdata['running_balance']), 2, '.', '')) : 0; -->
                        <!--//echo (isset($searchdata['running_balance']) && $searchdata['running_balance'] >= 0) ? ' Dr' : ' Cr';-->
                        <!-- //?></label>-->

                        
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <?php if(isset($searchdata)){ 

                ?>
                <div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-12">

                          <?php if(isset($searchdata['acc_flag']) && $searchdata['acc_flag'] != '' && $searchdata['acc_flag'] == 1){
                           ?>
                           <table class="table table-bordered table-striped datatable">
                            <thead>
                              <tr>
                                <th width="7%">Sr. No</th>
                                <th width="8%">Invoice Date</th>
                                <th>Invoice No.</th>
                                <th>Narration</th>
                                <th class="text-center" width="12%">Debit</th>
                                <th class="text-center" width="12%">Credit</th>
                                <th class="text-center" width="12%">Balance</th>

                              </tr> 
                            </thead>
                            <tbody>

                              <?php if(isset($searchdata['data']) && !empty($searchdata['data'])){?>
                                <?php $running_balance = 0;$total_debit = 0;$total_credit = 0; ?>
                                <?php foreach($searchdata['data'] as $key => $value){ ?>

                                  <tr>
                                    <td><?php echo ($key+1); ?></td>
                                    <td><?php echo (isset($value['date']) && $value['date'] != '' && $value['date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['date'])) : ''; ?></td>
                                    <td><?php echo (isset($value['no'])) ? $value['no'] : ''; ?></td>
                                    <td><?php echo (isset($value['remarks'])) ? $value['remarks'] : ''; ?></td>
                                    <td class="text-right">
                                     <?php
                                     if(isset($value['debit']) && $value['debit'] != ''){
                                      echo amount_format(number_format($value['debit'], 2, '.', ''));
                                      $total_debit += $value['debit'];
                                      $running_balance -= $value['debit'];
                                    }
                                    ?>
                                  </td>
                                  <td class="text-right">
                                    <?php
                                    if(isset($value['credit']) && $value['credit'] != ''){
                                      echo amount_format(number_format($value['credit'], 2, '.', ''));
                                      $total_credit += $value['credit'];
                                      $running_balance += $value['credit'];
                                    }
                                    ?>
                                  </td>

                                  <td class="text-right">
                                    <?php
                                    echo amount_format(number_format($running_balance, 2, '.', ''));
                                    ?>
                                  </td>
                                </tr>
                              <?php } ?>
                            <?php } ?>
                          </tbody>
                          <tfoot>
                            <tr style="background-color: #EFEFEF;">
                              <th colspan="4" class="text-center"><strong>Total</strong></th>
                              <th class="text-right">
                                <?php echo (isset($total_debit) && $total_debit != '') ? amount_format(number_format($total_debit, 2, '.', '')) : 0; ?>
                              </th>
                              <th class="text-right">
                                <?php echo (isset($total_credit) && $total_credit != '') ? amount_format(number_format($total_credit, 2, '.', '')) : 0; ?>
                              </th>
                              <th class="text-right">
                                <?php echo (isset($running_balance) && $running_balance != '') ? amount_format(number_format($running_balance, 2, '.', '')) : 0; ?>
                              </th>

                            </tr>
                          </tfoot>
                        </table>
                      <?php }else if(isset($searchdata['acc_flag']) && $searchdata['acc_flag'] != '' && $searchdata['acc_flag'] == 3){?>

                        <table class="table table-bordered table-striped datatable">
                          <thead>
                            <tr>
                              <th width="7%" class="text-center">Sr. No</th>
                              <th>Voucher Date</th>
                              <th>Voucher No.</th>
                              <th>Narration</th>
                              <th class="text-center" width="12%">Debit</th>
                              <th class="text-center" width="12%">Credit</th>
                              <th class="text-center" width="12%">Balance</th>
                            </tr> 
                          </thead>
                          <tbody>

                            <?php if(isset($searchdata['data']) && !empty($searchdata['data'])){?>
                              <?php $running_balance = 0;$total_debit = 0;$total_credit = 0; ?>
                              <?php foreach($searchdata['data'] as $key => $value){ ?>

                                <tr>
                                  <td><?php echo ($key+1); ?></td>
                                  <td><?php echo (isset($value['date']) && $value['date'] != '' && $value['date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['date'])) : ''; ?></td>
                                  <td><?php echo (isset($value['no'])) ? $value['no'] : ''; ?></td>
                                  <td><?php echo (isset($value['date']) && $value['date'] != '' && $value['date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['date'])) : ''; ?> - <?php echo (isset($value['no'])) ? $value['no'] : ''; ?></td>
                                  <td class="text-right">
                                   <?php
                                   if(isset($value['debit']) && $value['debit'] != ''){
                                    echo amount_format(number_format($value['debit'], 2, '.', ''));
                                    $total_debit += $value['debit'];
                                    $running_balance += $value['debit'];
                                  }
                                  ?>
                                </td>
                                <td class="text-right">
                                  <?php
                                  if(isset($value['credit']) && $value['credit'] != ''){
                                    echo amount_format(number_format($value['credit'], 2, '.', ''));
                                    $total_credit += $value['credit'];
                                    $running_balance -= $value['credit'];
                                  }
                                  ?>
                                </td>

                                <td class="text-right">
                                  <?php
                                  echo amount_format(number_format($running_balance, 2, '.', ''));
                                  ?>
                                </td>
                              </tr>
                            <?php } ?>
                          <?php } ?>
                        </tbody>
                        <tfoot>
                          <tr style="background-color: #EFEFEF;">
                            <th colspan="4" class="text-center"><strong>Total</strong></th>
                            <th class="text-right">
                              <?php echo (isset($total_debit) && $total_debit != '') ? amount_format(number_format($total_debit, 2, '.', '')) : 0; ?>
                            </th>
                            <th class="text-right">
                              <?php echo (isset($total_credit) && $total_credit != '') ? amount_format(number_format($total_credit, 2, '.', '')) : 0; ?>
                            </th>
                            <th class="text-right">
                              <?php echo (isset($running_balance) && $running_balance != '') ? amount_format(number_format($running_balance, 2, '.', '')) : 0; ?>
                            </th>

                          </tr>
                        </tfoot>
                      </table>
                    <?php }else if(isset($searchdata['acc_flag']) && $searchdata['acc_flag'] != '' && $searchdata['acc_flag'] == 5){?>
                     <table class="table table-bordered table-striped datatable">
                      <thead>
                        <tr>
                          <th width="7%" class="text-center">Sr. No</th>
                          <th>Invoice Date</th>
                          <th>Invoice No.</th>

                          <th class="text-center" width="12%">Debit</th>
                          <th class="text-center" width="12%">Credit</th>
                          <th class="text-center" width="12%">Balance</th>
                        </tr> 
                      </thead>
                      <tbody>

                        <?php if(isset($searchdata['data']) && !empty($searchdata['data'])){?>
                          <?php $running_balance = 0;$total_debit = 0;$total_credit = 0; ?>
                          <?php foreach($searchdata['data'] as $key => $value){ ?>

                            <tr>
                              <td><?php echo ($key+1); ?></td>
                              <td><?php echo (isset($value['date']) && $value['date'] != '' && $value['date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['date'])) : ''; ?></td>
                              <td><?php echo (isset($value['no'])) ? $value['no'] : ''; ?></td>

                              <td class="text-right">
                               <?php
                               if(isset($value['debit']) && $value['debit'] != ''){
                                echo amount_format(number_format($value['debit'], 2, '.', ''));
                                $total_debit += $value['debit'];
                                $running_balance -= $value['debit'];
                              }
                              ?>
                            </td>
                            <td class="text-right">
                              <?php
                              if(isset($value['credit']) && $value['credit'] != ''){
                                echo amount_format(number_format($value['credit'], 2, '.', ''));
                                $total_credit += $value['credit'];
                                $running_balance += $value['credit'];
                              }
                              ?>
                            </td>

                            <td class="text-right">
                              <?php
                              echo amount_format(number_format($running_balance, 2, '.', ''));
                              ?>
                            </td>
                          </tr>
                        <?php } ?>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr style="background-color: #EFEFEF;">
                        <th colspan="3" class="text-center"><strong>Total</strong></th>
                        <th class="text-right">
                          <?php echo (isset($total_debit) && $total_debit != '') ? amount_format(number_format($total_debit, 2, '.', '')) : 0; ?>
                        </th>
                        <th class="text-right">
                          <?php echo (isset($total_credit) && $total_credit != '') ? amount_format(number_format($total_credit, 2, '.', '')) : 0; ?>
                        </th>
                        <th class="text-right">
                          <?php echo (isset($running_balance) && $running_balance != '') ? amount_format(number_format($running_balance, 2, '.', '')) : 0; ?>
                        </th>

                      </tr>
                    </tfoot>
                  </table>
                 <?php }else if(isset($searchdata['acc_flag']) && $searchdata['acc_flag'] != '' && $searchdata['acc_flag'] == 8){?>
                  <table class="table table-bordered table-striped datatable">
                    <thead>
                      <tr>
                        <th width="7%" class="text-center">Sr. No</th>
                        <th>Narration</th>
                        <th class="text-center" width="12%">Balance</th>
                      </tr> 
                    </thead>
                    <tbody>

                      <?php if(isset($searchdata['data']) && !empty($searchdata['data'])){?>
                        <?php foreach($searchdata['data'] as $key => $value){ ?>
                          <tr>
                            <td><?php echo ($key+1); ?></td>
                            <td>Opening Stock</td>
                          <td class="text-right">
                            <?php
                             echo amount_format(number_format($value['amount'], 2, '.', ''));
                            ?>
                          </td>
                        </tr>
                      <?php } ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr style="background-color: #EFEFEF;">
                      <th colspan="2" class="text-center"><strong>Total</strong></th>
                      <th class="text-right">
                        <?php
                             echo amount_format(number_format($value['amount'], 2, '.', '')); ?>
                      </th>
                    </tr>
                  </tfoot>
                </table>
             
              <?php }else if(isset($searchdata['acc_flag']) && $searchdata['acc_flag'] != '' && $searchdata['acc_flag'] == 9){?>
                  <table class="table table-bordered table-striped datatable">
                    <thead>
                      <tr>
                        <th width="7%" class="text-center">Sr. No</th>
                        <th>Narration</th>
                        <th class="text-center" width="12%">Balance</th>
                      </tr> 
                    </thead>
                    <tbody>

                      <?php if(isset($searchdata['data']) && !empty($searchdata['data'])){?>
                        <?php foreach($searchdata['data'] as $key => $value){ ?>
                          <tr>
                            <td><?php echo ($key+1); ?></td>
                            <td>Closing Stock</td>
                          <td class="text-right">
                            <?php
                             echo amount_format(number_format($value['amount'], 2, '.', ''));
                            ?>
                          </td>
                        </tr>
                      <?php } ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr style="background-color: #EFEFEF;">
                      <th colspan="2" class="text-center"><strong>Total</strong></th>
                      <th class="text-right">
                        <?php
                             echo amount_format(number_format($value['amount'], 2, '.', '')); ?>
                      </th>
                    </tr>
                  </tfoot>
                </table>
                <?php }else if(isset($searchdata['acc_flag']) && $searchdata['acc_flag'] != '' && $searchdata['acc_flag'] == 10){?>
                  <table class="table table-bordered table-striped datatable">
                    <thead>
                      <tr>
                        <th width="7%" class="text-center">Sr. No</th>
                        <th>Invoice Date</th>
                        <th>Invoice No.</th>
                        <th class="text-center" width="12%">Debit</th>
                        <th class="text-center" width="12%">Credit</th>
                        <th class="text-center" width="12%">Balance</th>
                      </tr> 
                    </thead>
                    <tbody>

                      <?php if(isset($searchdata['data']) && !empty($searchdata['data'])){?>
                        <?php $running_balance = 0;$total_debit = 0;$total_credit = 0; ?>
                        <?php foreach($searchdata['data'] as $key => $value){ ?>

                          <tr>
                            <td><?php echo ($key+1); ?></td>
                            <td><?php echo (isset($value['date']) && $value['date'] != '' && $value['date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['date'])) : ''; ?></td>
                            <td><?php echo (isset($value['no'])) ? $value['no'] : ''; ?></td>

                            <td class="text-right">
                             <?php
                             if(isset($value['debit']) && $value['debit'] != ''){
                              echo amount_format(number_format($value['debit'], 2, '.', ''));
                              $total_debit += $value['debit'];
                              $running_balance += $value['debit'];
                            }
                            ?>
                          </td>
                          <td class="text-right">
                            <?php
                            if(isset($value['credit']) && $value['credit'] != ''){
                              echo amount_format(number_format($value['credit'], 2, '.', ''));
                              $total_credit += $value['credit'];
                              $running_balance -= $value['credit'];
                            }
                            ?>
                          </td>

                          <td class="text-right">
                            <?php
                            echo amount_format(number_format($running_balance, 2, '.', ''));
                            ?>
                          </td>
                        </tr>
                      <?php } ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr style="background-color: #EFEFEF;">
                      <th colspan="3" class="text-center"><strong>Total</strong></th>
                      <th class="text-right">
                        <?php echo (isset($total_debit) && $total_debit != '') ? amount_format(number_format($total_debit, 2, '.', '')) : 0; ?>
                      </th>
                      <th class="text-right">
                        <?php echo (isset($total_credit) && $total_credit != '') ? amount_format(number_format($total_credit, 2, '.', '')) : 0; ?>
                      </th>
                      <th class="text-right">
                        <?php echo (isset($running_balance) && $running_balance != '') ? amount_format(number_format($running_balance, 2, '.', '')) : 0; ?>
                      </th>

                    </tr>
                  </tfoot>
                </table>
                <?php }else if(isset($searchdata['acc_flag']) && $searchdata['acc_flag'] != '' && $searchdata['acc_flag'] == 11){?>
                  <table class="table table-bordered table-striped datatable">
                    <thead>
                      <tr>
                        <th width="7%" class="text-center">Sr. No</th>
                        <th>Invoice Date</th>
                        <th>Invoice No.</th>
                        <th class="text-center" width="12%">Debit</th>
                        <th class="text-center" width="12%">Credit</th>
                        <th class="text-center" width="12%">Balance</th>
                      </tr> 
                    </thead>
                    <tbody>

                      <?php if(isset($searchdata['data']) && !empty($searchdata['data'])){?>
                        <?php $running_balance = 0;$total_debit = 0;$total_credit = 0; ?>
                        <?php foreach($searchdata['data'] as $key => $value){ ?>

                          <tr>
                            <td><?php echo ($key+1); ?></td>
                            <td><?php echo (isset($value['date']) && $value['date'] != '' && $value['date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['date'])) : ''; ?></td>
                            <td><?php echo (isset($value['no'])) ? $value['no'] : ''; ?></td>

                            <td class="text-right">
                             <?php
                             if(isset($value['debit']) && $value['debit'] != ''){
                              echo amount_format(number_format($value['debit'], 2, '.', ''));
                              $total_debit += $value['debit'];
                              $running_balance += $value['debit'];
                            }
                            ?>
                          </td>
                          <td class="text-right">
                            <?php
                            if(isset($value['credit']) && $value['credit'] != ''){
                              echo amount_format(number_format($value['credit'], 2, '.', ''));
                              $total_credit += $value['credit'];
                              $running_balance -= $value['credit'];
                            }
                            ?>
                          </td>

                          <td class="text-right">
                            <?php
                            echo amount_format(number_format($running_balance, 2, '.', ''));
                            ?>
                          </td>
                        </tr>
                      <?php } ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr style="background-color: #EFEFEF;">
                      <th colspan="3" class="text-center"><strong>Total</strong></th>
                      <th class="text-right">
                        <?php echo (isset($total_debit) && $total_debit != '') ? amount_format(number_format($total_debit, 2, '.', '')) : 0; ?>
                      </th>
                      <th class="text-right">
                        <?php echo (isset($total_credit) && $total_credit != '') ? amount_format(number_format($total_credit, 2, '.', '')) : 0; ?>
                      </th>
                      <th class="text-right">
                        <?php echo (isset($running_balance) && $running_balance != '') ? amount_format(number_format($running_balance, 2, '.', '')) : 0; ?>
                      </th>

                    </tr>
                  </tfoot>
                </table>
                
                <?php }else if(isset($searchdata['acc_flag']) && $searchdata['acc_flag'] != '' && $searchdata['acc_flag'] == 12){?>
                  <table class="table table-bordered table-striped datatable">
                    <thead>
                      <tr>
                        <th width="7%" class="text-center">Sr. No</th>
                        <th>Invoice Date</th>
                        <th>Invoice No.</th>
                        <th class="text-center" width="12%">Debit</th>
                        <th class="text-center" width="12%">Credit</th>
                        <th class="text-center" width="12%">Balance</th>
                      </tr> 
                    </thead>
                    <tbody>

                      <?php if(isset($searchdata['data']) && !empty($searchdata['data'])){?>
                        <?php $running_balance = 0;$total_debit = 0;$total_credit = 0; $count = 1; ?>
                        <?php foreach($searchdata['data'] as $key => $value){ 
                            
                             ?>

                          <tr>
                            <td><?php echo $count ; ?></td>
                            <td><?php echo (isset($value['date']) && $value['date'] != '' && $value['date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['date'])) : ''; ?></td>
                            <td><?php echo (isset($value['no'])) ? $value['no'] : ''; ?></td>

                            <td class="text-right">
                             <?php
                             if(isset($value['debit']) && $value['debit'] != ''){
                              echo amount_format(number_format($value['debit'], 2, '.', ''));
                              $total_debit += $value['debit'];
                              $running_balance += $value['debit'];
                            }
                            ?>
                          </td>
                          <td class="text-right">
                            <?php
                            if(isset($value['credit']) && $value['credit'] != ''){
                              echo amount_format(number_format($value['credit'], 2, '.', ''));
                              $total_credit += $value['credit'];
                              $running_balance -= $value['credit'];
                            }
                            ?>
                          </td>

                          <td class="text-right">
                            <?php
                            echo amount_format(number_format($running_balance, 2, '.', ''));
                            ?>
                          </td>
                        </tr>

                      <?php $count ++; }?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr style="background-color: #EFEFEF;">
                      <th colspan="3" class="text-center"><strong>Total</strong></th>
                      <th class="text-right">
                        <?php echo (isset($total_debit) && $total_debit != '') ? amount_format(number_format($total_debit, 2, '.', '')) : 0; ?>
                      </th>
                      <th class="text-right">
                        <?php echo (isset($total_credit) && $total_credit != '') ? amount_format(number_format($total_credit, 2, '.', '')) : 0; ?>
                      </th>
                      <th class="text-right">
                        <?php echo (isset($running_balance) && $running_balance != '') ? amount_format(number_format($running_balance, 2, '.', '')) : 0; ?>
                      </th>

                    </tr>
                  </tfoot>
                </table>
                
              <?php }else if(isset($searchdata['acc_flag']) && $searchdata['acc_flag'] != '' && $searchdata['acc_flag'] == 7 || $searchdata['acc_flag'] == 6 || $searchdata['acc_flag'] == 2 || $searchdata['acc_flag'] == 4){?> 

                <table class="table table-bordered table-striped datatable">
                  <thead>
                    <tr>
                      <th width="7%" class="text-center">Sr. No</th>
                      <th>Invoice Date</th>
                      <th>Invoice No.</th>
                      <th>Perticular</th>
                      <th>Mobile</th>
                      <th>City</th>
                      <th>Type</th>
                      <th class="text-right">Amount</th>
                      
                    </tr> 
                  </thead>
                  <tbody>
                    <?php if(isset($searchdata['data']) && !empty($searchdata['data'])){ $total_amount = 0;?>
                      <?php foreach($searchdata['data'] as $key => $value){ ?>
                        <tr>
                          <td><?php echo ($key+1); ?></td>
                          <td><?php echo (isset($value['date']) && $value['date'] != '' && $value['date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['date'])) : ''; ?></td>
                          <td><?php echo (isset($value['no'])) ? $value['no'] : ''; ?></td>
                          <td><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></td>
                          <td><?php echo (isset($value['mobile'])) ? $value['mobile'] : ''; ?></td>
                          <td><?php echo (isset($value['city'])) ? $value['city'] : ''; ?></td>
                          <td><?php echo (isset($value['bill_type'])) ? $value['bill_type'] : ''; ?></td>
                          <td class="text-right">
                            <?php
                            $amount = (isset($value['amount']) && $value['amount'] != '') ? $value['amount'] : 0;
                            $total_amount += $amount;
                            echo amount_format(number_format($amount, 2, '.', ''));
                            ?>
                          </td>
                          <!-- <td class="text-center">
                            <a class="btn btn-behance p-2" href="<?php echo (isset($value['url']) && $value['url'] != '') ? $value['url'] : 'javascript:void(0);'; ?>" title="edit" target="_blank"><i class="fa fa-pencil mr-0"></i></a> 
                          </td> -->
                        </tr>
                      <?php } ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr style="background-color: #EFEFEF;">
                      <th colspan="7" class="text-center"><strong>Total</strong></th>
                      <th class="text-right">
                        <?php echo (isset($total_amount) && $total_amount != '') ? amount_format(number_format($total_amount, 2, '.', '')) : 0; ?>
                      </th>
                      <!-- <th></th> -->
                    </tr>
                  </tfoot>
                </table>
              <?php }else{ ?>

                <table class="table table-bordered table-striped datatable">
                  <thead>
                    <tr>
                      <th width="7%" class="text-center">Sr. No</th>
                      <th width="8%" class="text-center">Date</th>
                      <th class="text-center">Narration</th>
                      <th class="text-center" width="12%">Debit</th>
                      <th class="text-center" width="12%">Credit</th>
                      <th class="text-center" width="12%">Balance</th>
                      <!--<th width="10%" class="text-center">Action</th>-->
                    </tr> 
                  </thead>
                  <tbody>
                    <?php $running_balance = 0;$total_debit = 0;$total_credit = 0; ?>
                    <tr>
                      <td></td>
                      <td></td>
                      <td>Opening Balance</td>
                      <td></td>
                      <td></td>
                      <td class="text-right">
                        <?php
                        $opening_balance = (isset($searchdata['opening_balance']) && $searchdata['opening_balance'] != '') ? $searchdata['opening_balance'] : 0;
                        echo amount_format(number_format(abs($opening_balance), 2, '.', ''));
                        echo ($opening_balance > 0) ? ' Dr' : ' Cr';
                        $running_balance += $opening_balance;
                        ?> 
                      </td>
                      <!--<td></td>-->
                    </tr>

                    <?php if(isset($searchdata['data']) && !empty($searchdata['data'])){ ?>
                      <?php foreach ($searchdata['data'] as $key => $value) { ?>
                        <tr>
                          <td class="text-center"><?php echo $key+1; ?></td>
                          <td><?php echo (isset($value['date']) && $value['date'] != '') ? date('d/m/Y', strtotime($value['date'])) : ''; ?></td>
                          <td><?php echo (isset($value['narration'])) ? $value['narration'] : ''; ?></td>
                          <td class="text-right">
                            <?php
                            if(isset($value['debit']) && $value['debit'] != ''){
                              echo amount_format(number_format($value['debit'], 2, '.', ''));
                              $total_debit += $value['debit'];
                              $running_balance += $value['debit'];
                            }
                            ?>
                          </td>
                          <td class="text-right">
                            <?php
                            if(isset($value['credit']) && $value['credit'] != ''){
                              echo amount_format(number_format($value['credit'], 2, '.', ''));
                              $total_credit += $value['credit'];
                              $running_balance -= $value['credit'];
                            }
                            ?>
                          </td>
                          <td class="text-right">
                            <?php
                            echo amount_format(number_format(abs($running_balance), 2, '.', ''));
                            echo ($running_balance > 0) ? ' Dr' : ' Cr';
                            ?>
                          </td>
                          <!-- <td class="text-center">-->
                           <!--<a class="btn btn-behance p-2" href="<?php echo (isset($value['url']) && $value['url'] != '') ? $value['url'] : 'javascript:void(0);'; ?>" title="edit" target="_blank"><i class="fa fa-pencil mr-0"></i></a> -->
                           <!-- </td>-->
                         </tr>
                       <?php } ?>
                     <?php } ?>
                   </tbody>
                   <tfoot>
                    <tr style="background-color: #EFEFEF;">
                      <th colspan="3" class="text-center"><strong>Total / Closing Balance</strong></th>
                      <th class="text-right">
                        <?php echo (isset($total_debit) && $total_debit != '') ? amount_format(number_format($total_debit, 2, '.', '')) : 0; ?>
                      </th>
                      <th class="text-right">
                        <?php echo (isset($total_credit) && $total_credit != '') ? amount_format(number_format($total_credit, 2, '.', '')) : 0; ?>
                      </th>
                      <th class="text-right">
                        <?php 
                        echo (isset($running_balance) && $running_balance != '') ? amount_format(number_format(abs($running_balance), 2, '.', '')) : 0;
                        echo (isset($running_balance) && $running_balance > 0) ? ' Dr' : ' Cr';
                        ?>
                      </th>
                      <!--<th></th>-->
                    </tr>
                  </tfoot>
                </table>
              <?php } ?>  
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


<!-- Datepicker Initialise-->
<script>
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
 $('.datatable').DataTable( {
  fixedHeader: {
    header: true,
    footer: true
  }
} );
</script>
<!-- script for custom validation -->
<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>
<script src="js/custom/onlynumber.js"></script>
<script src="js/custom/all-group-ladger.js"></script>
 
 <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
<!-- End custom js for this page-->


</script>

</body>


</html>
