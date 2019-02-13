<?php $title = "Investment Report"; ?>
<?php include('include/usertypecheck.php');?>
<?php //include('include/permission.php'); ?>
<?php 
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';

  if(isset($_POST['search'])){

    $id = (isset($_POST['ladger'])) ? $_POST['ladger'] : '';
    $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : ''; 
    $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';
    
    $searchdata = investmentReport($id, $from, $to, 0);
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Investment Report</title>
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
  <style type="text/css">
    .perticular{
      border-top: 1px solid;
      border-bottom: 1px solid;
        padding: 2px;
        font-weight: bold;
    }
    .text-bold{
      font-weight: bold;
    }
  </style>
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
             
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                        <h4 class="card-title">Investment Report</h4><hr class="alert-dark">
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
                              <label>Perticular</label>
                              <select class="js-example-basic-single" style="width:100%" name="ladger" id="ladger_list" data-parsley-errors-container="#error-ladger">  
                                  <option value="">All Perticular</option>
                                  <?php 
                                    $queryCapitalAcc = "SELECT name,id FROM ledger_master where pharmacy_id = '".$pharmacy_id."' AND group_id = 20 ORDER BY name";
                                    $resultCapitalAcc = mysqli_query($conn,$queryCapitalAcc);
                                    if($resultCapitalAcc && mysqli_num_rows($resultCapitalAcc) > 0){
                                      while ($rowCapitalAcc = mysqli_fetch_array($resultCapitalAcc)) {
                                  ?>
                                    <option value="<?php echo $rowCapitalAcc['id']; ?>" <?php echo (isset($_POST['ladger']) && $_POST['ladger'] == $rowCapitalAcc['id']) ? 'selected' : ''; ?>><?php echo $rowCapitalAcc['name']; ?></option>
                                  <?php
                                      }
                                    }
                                  ?>
                              </select>
                              <span id="error-ladger"></span>
                            </div>
    
                            <div class="col-12 col-md-2">
                                <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                                <?php if(isset($searchdata)){ ?>
                                  <a href="investment-report-print.php?id=<?php echo (isset($_POST['ladger'])) ? $_POST['ladger'] : ''; ?>&from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
                                <?php } ?>
                               
                              </div>
    
                              <!-- <label class="pull-right bg-success color-white p-2 <?php //echo (isset($searchdata)) ? ' display-block' : ' display-none'; ?>" style="margin-top: 30px;" id="running_balance"> Running Balance : <?php //echo (isset($searchdata['running_balance'])) ? amount_format(number_format(abs($searchdata['running_balance']), 2, '.', '')) : 0; 
                              //echo (isset($searchdata['running_balance']) && $searchdata['running_balance'] >= 0) ? ' Dr' : ' Cr';
                               ?></label> -->
                          </div>
                        </form>
                      </div>
                    </div>
                </div>
                <?php if(isset($searchdata)){ ?>
                  <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-12">
                            <table class="table table-bordered">
                              <thead>
                                <tr>
                                  <th>Date</th>
                                  <th>Particulars</th>
                                  <th>Vch Type</th>
                                  <th>Vch No.</th>
                                  <th colspan="3">Inwards</th>
                                  <th colspan="3">Outwards</th>
                                  <th colspan="3">Closing</th>
                                </tr>
                                <tr>
                                  <th colspan="4"></th>
                                  <th>Qty</th>
                                  <th>Rate</th>
                                  <th>value</th>
                                  <th>Qty</th>
                                  <th>Rate</th>
                                  <th>value</th>
                                  <th>Qty</th>
                                  <th>Rate</th>
                                  <th>value</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php if(!empty($searchdata)){ ?>
                                  <?php foreach ($searchdata as $key => $value) { ?>
                                      <tr>
                                        <td></td>
                                        <td>
                                            <span class="perticular">
                                              <?php echo (isset($value['name']) && $value['name'] != '') ? ucwords(strtolower($value['name'])) : 'Unknown Ledger'; ?>
                                            </span>
                                        </td>
                                        <td colspan="2"></td>
                                        <td colspan="3"></td>
                                        <td colspan="3"></td>
                                        <td colspan="3"></td>
                                      </tr>
                                      <?php $totalInwardQty = 0; $totalInwardRate = 0; $totalInwardValue = 0; $totalOutwardQty = 0; $totalOutwardRate = 0; $totalOutwardValue = 0; $qtyRunning = 0; $rateRunning = 0; $valueRunning = 0; ?>
                                      <?php if(isset($value['detail']) && !empty($value['detail'])){ ?>
                                        <?php foreach ($value['detail'] as $k1 => $v1) { ?>
                                            <?php if(isset($v1) && !empty($v1)){ ?>
                                              <?php foreach ($v1 as $k2 => $v2) { ?>
                                                <tr>
                                                  <td>
                                                    <?php
                                                        if($k2 == 0){
                                                          echo (isset($k1)) ? $k1 : '';
                                                        }
                                                    ?>
                                                  </td>
                                                  <td class="text-bold"><?php echo (isset($v2['perticular'])) ? $v2['perticular'] : 'Unknown Perticular'; ?></td>
                                                  <td class="text-bold"><?php echo (isset($v2['type'])) ? $v2['type'] : ''; ?></td>
                                                  <td><?php echo (isset($v2['voucherno'])) ? $v2['voucherno'] : ''; ?></td>

                                                  <td>
                                                    <?php
                                                      if(isset($v2['inward']['qty']) && $v2['inward']['qty'] != '' && $v2['inward']['qty'] != 0){
                                                        echo amount_format(number_format($v2['inward']['qty'], 2, '.', ''));
                                                        $totalInwardQty += $v2['inward']['qty'];
                                                        $qtyRunning += $v2['inward']['qty'];
                                                      }
                                                    ?>
                                                  </td>
                                                  <td>
                                                    <?php
                                                      if(isset($v2['inward']['rate']) && $v2['inward']['rate'] != '' && $v2['inward']['rate'] != 0){
                                                        echo amount_format(number_format($v2['inward']['rate'], 2, '.', ''));
                                                        $totalInwardRate += $v2['inward']['rate'];
                                                        $rateRunning += $v2['inward']['rate'];
                                                      }
                                                    ?>
                                                  </td>
                                                  <td class="text-bold">
                                                    <?php
                                                      if(isset($v2['inward']['value']) && $v2['inward']['value'] != '' && $v2['inward']['value'] != 0){
                                                        echo amount_format(number_format($v2['inward']['value'], 2, '.', ''));
                                                        $totalInwardValue += $v2['inward']['value'];
                                                        $valueRunning += $v2['inward']['value'];
                                                      }
                                                    ?>
                                                  </td>

                                                  <td>
                                                    <?php
                                                      if(isset($v2['outward']['qty']) && $v2['outward']['qty'] != '' && $v2['outward']['qty'] != 0){
                                                        echo amount_format(number_format($v2['outward']['qty'], 2, '.', ''));
                                                        $totalOutwardQty += $v2['outward']['qty'];
                                                        $qtyRunning -= $v2['outward']['qty'];
                                                      }
                                                    ?>
                                                  </td>
                                                  <td>
                                                    <?php
                                                      if(isset($v2['outward']['rate']) && $v2['outward']['rate'] != '' && $v2['outward']['rate'] != 0){
                                                        echo amount_format(number_format($v2['outward']['rate'], 2, '.', ''));
                                                        $totalOutwardRate += $v2['outward']['rate'];
                                                        $rateRunning -= $v2['outward']['rate'];
                                                      }
                                                    ?>
                                                  </td>
                                                  <td class="text-bold">
                                                    <?php
                                                      if(isset($v2['outward']['value']) && $v2['outward']['value'] != '' && $v2['outward']['value'] != 0){
                                                        echo amount_format(number_format($v2['outward']['value'], 2, '.', ''));
                                                        $totalOutwardValue += $v2['outward']['value'];
                                                        $valueRunning -= $v2['outward']['value'];
                                                      }
                                                    ?>
                                                  </td>

                                                  <td>
                                                    <?php echo (isset($qtyRunning) && $qtyRunning != '' && $qtyRunning != 0) ? amount_format(number_format($qtyRunning, 2, '.', '')) : ''; ?>
                                                  </td>
                                                  <td>
                                                    <?php echo (isset($rateRunning) && $rateRunning != '' && $rateRunning != 0) ? amount_format(number_format($rateRunning, 2, '.', '')) : ''; ?>
                                                  </td>
                                                  <td class="text-bold">
                                                    <?php echo (isset($valueRunning) && $valueRunning != '' && $valueRunning != 0) ? amount_format(number_format($valueRunning, 2, '.', '')) : ''; ?>
                                                  </td>
                                                </tr>
                                              <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                      <?php } ?>
                                      <tr>
                                        <td colspan="4" class="text-center text-bold">Totals as per 'Default' valuation :</td>

                                        <td>
                                          <?php echo (isset($totalInwardQty) && $totalInwardQty != '' && $totalInwardQty != 0) ? amount_format(number_format($totalInwardQty, 2, '.', '')) : ''; ?>
                                        </td>
                                        <td>
                                          <?php echo (isset($totalInwardRate) && $totalInwardRate != '' && $totalInwardRate != 0) ? amount_format(number_format($totalInwardRate, 2, '.', '')) : ''; ?>
                                        </td>
                                        <td class="text-bold">
                                          <?php echo (isset($totalInwardValue) && $totalInwardValue != '' && $totalInwardValue != 0) ? amount_format(number_format($totalInwardValue, 2, '.', '')) : ''; ?>
                                        </td>

                                        <td>
                                          <?php echo (isset($totalOutwardQty) && $totalOutwardQty != '' && $totalOutwardQty != 0) ? amount_format(number_format($totalOutwardQty, 2, '.', '')) : ''; ?>
                                        </td>
                                        <td>
                                          <?php echo (isset($totalOutwardRate) && $totalOutwardRate != '' && $totalOutwardRate != 0) ? amount_format(number_format($totalOutwardRate, 2, '.', '')) : ''; ?>
                                        </td>
                                        <td class="text-bold">
                                          <?php echo (isset($totalOutwardValue) && $totalOutwardValue != '' && $totalOutwardValue != 0) ? amount_format(number_format($totalOutwardValue, 2, '.', '')) : ''; ?>
                                        </td>
                                        
                                        <td>
                                          <?php echo (isset($qtyRunning) && $qtyRunning != '' && $qtyRunning != 0) ? amount_format(number_format($qtyRunning, 2, '.', '')) : ''; ?>
                                        </td>
                                        <td>
                                          <?php echo (isset($rateRunning) && $rateRunning != '' && $rateRunning != 0) ? amount_format(number_format($rateRunning, 2, '.', '')) : ''; ?>
                                        </td>
                                        <td class="text-bold">
                                          <?php echo (isset($valueRunning) && $valueRunning != '' && $valueRunning != 0) ? amount_format(number_format($valueRunning, 2, '.', '')) : ''; ?>
                                        </td>
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
<script src="js/custom/capital-account-report.js"></script>

 
 <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  <!-- End custom js for this page-->


</script>
 
</body>


</html>
