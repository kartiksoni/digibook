<?php $title = "Cash Ledger"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>
<?php 
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';

  if(isset($_POST['search'])){
    $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : '';
    $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';
    $searchdata = cashLedger($from, $to);
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Cash Ledger</title>
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
                    <h4 class="card-title">Cash Book</h4><hr class="alert-dark">
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

                        <div class="col-12 col-md-8 col-sm-12">
                          <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                          <?php if(isset($searchdata)){ ?>
                            <a href="cash-ledger-print.php?from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
                          <?php } ?>

                          <label class="pull-right bg-success color-white p-2 <?php echo (isset($searchdata)) ? ' display-block' : ' display-none'; ?>" style="margin-top: 30px;" id="running_balance"> Running Balance : <?php echo (isset($searchdata['running_balance'])) ? amount_format(number_format(abs($searchdata['running_balance']), 2, '.', '')) : 0; echo (isset($searchdata['running_balance']) && $searchdata['running_balance'] >= 0) ? ' Dr' : ' Cr'; ?></label>
                        </div>

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
                          <table class="table table-bordered table-striped datatable">
                            <thead>
                              <tr>
                                  <th width="7%">Sr. No</th>
                                  <th width="8%">Date</th>
                                  <th width="10%">Type</th>
                                  <th>Narration</th>
                                  <th width="12%">Receipt</th>
                                  <th width="12%">Payment</th>
                                  <th width="12%">Running Balance</th>
                                  <th width="5%" class="text-center">Action</th>
                              </tr> 
                            </thead>
                            <tbody>
                              <?php $running_balance = 0;$total_receipt = 0;$total_payment = 0; ?>
                               <tr>
                                  <td>1</td>
                                  <td></td>
                                  <td></td>
                                  <td class="text-center">Opening Balance</td>
                                  <td></td>
                                  <td></td>
                                  <td class="text-right">
                                    <?php 
                                      echo (isset($searchdata['opening_balance']) && $searchdata['opening_balance'] != '') ? amount_format(number_format(abs($searchdata['opening_balance']), 2, '.', '')) : 0;
                                      $opening_balance = (isset($searchdata['opening_balance']) && $searchdata['opening_balance'] != '') ? $searchdata['opening_balance'] : 0;
                                      echo ($opening_balance >= 0) ? ' Dr' : ' Cr';
                                      $running_balance += $opening_balance;
                                    ?>
                                  </td>
                                  <td></td>
                                </tr>

                                <?php if(isset($searchdata['data']) && !empty($searchdata['data'])){ ?>
                                  <?php foreach ($searchdata['data'] as $key => $value) { ?>
                                    <tr>
                                      <td><?php echo $key+2; ?></td>
                                      <td><?php echo (isset($value['date']) && $value['date'] != '') ? date('d/m/Y', strtotime($value['date'])) : ''; ?></td>
                                      <td><?php echo (isset($value['type_name'])) ? $value['type_name'] : ''; ?></td>
                                      <td><?php echo (isset($value['narration'])) ? $value['narration'] : ''; ?></td>
                                      <td class="text-right">
                                        <?php
                                            if(isset($value['receipt']) && $value['receipt'] != ''){
                                                echo amount_format(number_format($value['receipt'], 2, '.', ''));
                                                $total_receipt += $value['receipt'];
                                                $running_balance += $value['receipt'];
                                            }
                                        ?>
                                      </td>
                                      <td class="text-right">
                                        <?php
                                            if(isset($value['payment']) && $value['payment'] != ''){
                                                echo amount_format(number_format($value['payment'], 2, '.', ''));
                                                $total_payment += $value['payment'];
                                                $running_balance -= $value['payment'];
                                            }
                                        ?>
                                      </td>
                                      <td class="text-right">
                                        <?php
                                          echo (isset($running_balance)) ? amount_format(number_format(abs($running_balance), 2, '.', '')) : 0;
                                          echo ($running_balance >= 0) ? ' Dr' : ' Cr';
                                        ?>
                                      </td>
                                      <td class="text-center">
                                        <a class="btn btn-behance p-2" href="<?php echo (isset($value['url']) && $value['url'] != '') ? $value['url'] : 'javascript:void(0);'; ?>" title="edit" target="_blank"><i class="fa fa-pencil mr-0"></i></a>
                                      </td>
                                    </tr>
                                  <?php } ?>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                              <tr style="background-color: #EFEFEF;">
                                <th colspan="4" class="text-right"><strong>Total / Closing Balance</strong></th>
                                <th class="text-right"><?php echo (isset($total_receipt)) ? amount_format(number_format($total_receipt, 2, '.', '')) : 0; ?></th>
                                <th class="text-right"><?php echo (isset($total_payment)) ? amount_format(number_format($total_payment, 2, '.', '')) : 0; ?></th>
                                <th class="text-right">
                                  <?php
                                    echo (isset($running_balance)) ? amount_format(number_format(abs($running_balance), 2, '.', '')) : 0;
                                    echo (isset($running_balance) && $running_balance >= 0) ? ' Dr' : ' Cr';
                                  ?>
                                </th>
                                <th></th>
                              </tr>
                            </tfoot>
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
<script src="js/custom/cash_ledger.js"></script>

 
 <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <!-- End custom js for this page-->
</body>


</html>
