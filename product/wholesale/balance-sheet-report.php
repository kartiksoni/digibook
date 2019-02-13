<?php $title = "Balance Sheet Report"; ?>
<?php include('include/usertypecheck.php');?>
<?php //include('include/permission.php'); ?>
<?php
  $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : '';
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
?>
<?php 
    /*-----------------------GET FINANCIAL YEAR START-----------------------------*/
    /*$financialYearQ = "SELECT id, f_name, start_date, end_date FROM financial WHERE id = '".$financial_id."'";
    $financialYearR = mysqli_query($conn, $financialYearQ);
    if($financialYearR && mysqli_num_rows($financialYearR) > 0){
        $financialYearRow = mysqli_fetch_assoc($financialYearR);
    }*/
    /*-----------------------GET FINANCIAL YEAR END-----------------------------*/
    if($_POST){
        $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : ''; 
        $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';

        $data = balanceSheetReport($from, $to, 1);
        $profit_los = profit_loss($from, $to);
        $netprofit = (isset($profit_los['profit']['NetProfit']) && $profit_los['profit']['NetProfit'] != '') ? $profit_los['profit']['NetProfit'] : 0;
    }
    

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Balance Sheet Report</title>
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
                                <h4 class="card-title">Balance Sheet Report</h4><hr class="alert-dark">
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
                                        <div class="col-12 col-md-2">
                                            <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                                            <?php if(isset($data)){ ?>
                                              <a href="balance-sheet-report-print.php?from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                      </div>

                    <?php if(isset($data)){ ?>
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <!-- <div class="pull-right bg-success color-white p-1"> Financial Year : <?php //echo (isset($financialYearRow['f_name'])) ? $financialYearRow['f_name'] : 'Unknown'; ?></div> -->
                                    <!-- <h4 class="card-title">Balance Sheet Report</h4>
                                    <hr class="alert-dark"> -->

                                    <div class="row">
                                      <div class="col-12">
                                        <!--Report Table Start-->
                                        <table class="table table-bordered m-0">
                                            <tbody>
                                                <?php 
                                                    $totalLeft = 0;
                                                    $totalRight = 0;
                                                ?>
                                                <tr>
                                                    <td style="vertical-align: initial;">
                                                        <table class="table table-bordered m-0">
                                                            <thead>
                                                                <tr>
                                                                    <th>Liabilities</th>
                                                                    <th style="text-align:right;"><?php echo (isset($financialYearRow['end_date']) && $financialYearRow['end_date'] != '') ? 'As At '.date('d-M-Y',strtotime($financialYearRow['end_date'])) : ''; ?></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if(isset($data['left']) && !empty($data['left'])){ ?>
                                                                    <?php foreach ($data['left'] as $key => $value) { ?>
                                                                        <tr>
                                                                          <td colspan="2">
                                                                            <b><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></b>
                                                                            <b class="pull-right"><?php echo (isset($value['totalamount']) && $value['totalamount'] != '') ? amount_format(number_format($value['totalamount'], 2, '.', '')) : ''; ?></b>
                                                                          </td>                                
                                                                        </tr>
                                                                        <?php if(isset($value['data']) && !empty($value['data'])){ ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <ul class="list-group">
                                                                                        <?php foreach ($value['data'] as $kk => $vv) { ?>
                                                                                
                                                                                            <li class="list-group" style="margin-left: 20px;">
                                                                                                <?php echo (isset($vv['name'])) ? $vv['name'] : ''; ?>
                                                                                            </li>
                                                                                        <?php } ?>
                                                                                    </ul>
                                                                                </td>
                                                                                
                                                                                <td>
                                                                                    <ul class="list-group">
                                                                                        <?php foreach ($value['data'] as $k => $v) { ?>
                                                                                
                                                                                            <li class="list-group" style="text-align:right;margin-right:40%;<?php echo ($k == (count($value['data']) - 1)) ? 'border-bottom:1px solid;' : ''; ?>">
                                                                                                <?php
                                                                                                    $amountLeft = (isset($v['closing_balance']) && $v['closing_balance'] != '') ? $v['closing_balance'] : 0;
                                                                                                    echo amount_format(number_format($amountLeft, 2, '.', ''));
                                                                                                    $totalLeft += $amountLeft;
                                                                                                ?>
                                                                                            </li>
                                                                                        <?php } ?>
                                                                                    </ul>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                                <tr>
                                                                    <td colspan="2">
                                                                      <b>Profit &amp; Loss A/c</b>
                                                                    </td>                                
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <ul class="list-group">                                                        
                                                                                <li class="list-group" style="margin-left: 20px;">Opening Balance</li>                           
                                                                                <li class="list-group" style="margin-left: 20px;">Current Period</li>
                                                                                <li class="list-group" style="margin-left: 20px;">Less: Transferred</li>
                                                                        </ul>
                                                                    </td>
                                                                    <td>
                                                                       <ul class="list-group">                                                                      
                                                                            <li class="list-group" style="text-align:right;margin-right:40%;"></li>        
                                                                            <li class="list-group" style="text-align:right;margin-right:40%;"><?php echo (isset($netprofit)) ? amount_format(number_format($netprofit, 2, '.', '')) : 0; ?></li>
                                                                            <li class="list-group" style="text-align:right;margin-right:40%;border-bottom:1px solid;"><?php echo (isset($netprofit)) ? amount_format(number_format($netprofit, 2, '.', '')) : 0; ?></li>
                                                                        </ul>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>                  <!--Debit-->
                                                    <td style="vertical-align: initial;">
                                                        <table class="table table-bordered m-0">
                                                            <thead>
                                                                <tr>
                                                                    <th>Assets</th>
                                                                    <th style="text-align:right;"><?php echo (isset($financialYearRow['end_date']) && $financialYearRow['end_date'] != '') ? 'As At '.date('d-M-Y',strtotime($financialYearRow['end_date'])) : ''; ?></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if(isset($data['right']) && !empty($data['right'])){ ?>
                                                                    <?php foreach ($data['right'] as $key => $value) { ?>
                                                                        <tr>
                                                                          <td colspan="2">
                                                                            <b><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></b>
                                                                            <b class="pull-right"><?php echo (isset($value['totalamount']) && $value['totalamount'] != '') ? amount_format(number_format($value['totalamount'], 2, '.', '')) : ''; ?></b>
                                                                          </td>                                
                                                                        </tr>
                                                                        <?php if(isset($value['data']) && !empty($value['data'])){ ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <ul class="list-group">
                                                                                        <?php foreach ($value['data'] as $kk1 => $vv1) { ?>
                                                                                
                                                                                            <li class="list-group" style="margin-left: 20px;">
                                                                                                <?php echo (isset($vv1['name'])) ? $vv1['name'] : ''; ?>
                                                                                            </li>
                                                                                        <?php } ?>
                                                                                    </ul>
                                                                                </td>
                                                                                
                                                                                <td>
                                                                                    <ul class="list-group">
                                                                                        <?php foreach ($value['data'] as $k1 => $v1) { ?>
                                                                                
                                                                                            <li class="list-group" style="text-align:right;margin-right:40%;<?php echo ($k1 == (count($value['data']) - 1)) ? 'border-bottom:1px solid;' : ''; ?>">
                                                                                                <?php
                                                                                                    $amountRight = (isset($v1['closing_balance']) && $v1['closing_balance'] != '') ? $v1['closing_balance'] : 0;
                                                                                                    echo amount_format(number_format($amountRight, 2, '.', ''));
                                                                                                    $totalRight += $amountRight;
                                                                                                ?>
                                                                                            </li>
                                                                                        <?php } ?>
                                                                                    </ul>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <!--Total sum-->
                                                <tr>
                                                  <td>
                                                    <ul class="list-group">
                                                      <li class="list-group" style="text-align:right;">
                                                        <b><?php echo (isset($totalLeft)) ? 'Total : '.amount_format(number_format($totalLeft, 2, '.', '')) : 0; ?></b>
                                                      </li>
                                                    </ul>
                                                  </td>
                                                  <td>
                                                      <ul class="list-group">
                                                        <li class="list-group" style="text-align:right;">
                                                            <b><?php echo (isset($totalRight)) ? 'Total : '.amount_format(number_format($totalRight, 2, '.', '')) : 0; ?></b>
                                                        </li>
                                                      </ul>
                                                  </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <!--Report Table End-->
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
     $('.datatable').DataTable( {
        fixedHeader: {
            header: true,
            footer: true
        }
    } );
  </script>
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

<script src="js/custom/onlynumber.js"></script>
</body>


</html>
