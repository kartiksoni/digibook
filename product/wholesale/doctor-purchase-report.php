<?php $title = "Stock Detail Price"; ?>
<?php include('include/usertypecheck.php');?>
<?php //include('include/permission.php'); ?>
<?php 
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';

  if($_POST){
    $doctor = (isset($_POST['doctor'])) ? $_POST['doctor'] : '';
    $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : '';
    $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';

    $searchdata = doctorPurchaseReport($from, $to, $doctor);
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Doctor Purchase Report</title>
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
            <?php include('include/flash.php'); ?>
            <span id="errormsg"></span>

            <div class="row">
             
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Doctor - Purchase Report</h4><hr class="alert-dark">
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

                        <div class="col-12 col-md-2">
                          <label for="doctor">Doctor <span class="text-danger">*</span></label>
                          <select class="js-example-basic-single" name="doctor" id="doctor" style="width:100%" data-parsley-errors-container="#error-doctor" required> 
                            <option value="">Select Doctor</option>
                            <?php if(isset($purchaseDoctor) && !empty($purchaseDoctor)){ ?>
                                <?php foreach ($purchaseDoctor as $key => $value) { ?>
                                  <option value="<?php echo $value['id']; ?>" <?php echo (isset($_POST['doctor']) && $_POST['doctor'] == $value['id']) ? 'selected' : ''; ?> ><?php echo $value['name']; ?></option>
                                <?php } ?>
                            <?php } ?>
                          </select>
                          <span id="error-doctor"></span>
                          <input type="hidden" name="doctor_name" id="doctor_name" value="<?php echo (isset($_POST['doctor_name'])) ? $_POST['doctor_name'] : ''; ?>">
                        </div>
                        

                        <div class="col-12 col-md-6">
                          <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                          <?php if(isset($searchdata) && !empty($searchdata)){ ?>
                            <a href="doctor-purchase-report-print.php?from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>&doctor=<?php echo (isset($_POST['doctor'])) ? $_POST['doctor'] : 0; ?>&doctorname=<?php echo (isset($_POST['doctor_name'])) ? $_POST['doctor_name'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
                          <?php } ?>
                          <label class="pull-right bg-success color-white p-2  <?php echo (isset($searchdata['running_balance'])) ? 'display-block' : 'display-none'; ?>" id="running_balance">
                              <?php 
                                if(isset($searchdata['running_balance'])){
                                    echo ($searchdata['running_balance'] != '') ? 'Running Balance : '.amount_format(number_format(abs($searchdata['running_balance']), 2, '.', '')) : 'Running Balance : 0';
                                    echo ($searchdata['running_balance'] >= 0) ? ' Dr' : ' Cr';
                                }
                              ?>
                          </label>
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
                                  <th>Sr. No</th>
                                  <th>Invoice Date</th>
                                  <th>Invoice No.</th>
                                  <th>Vendor Name</th>
                                  <th>Debit</th>
                                  <th>Cash</th>
                                  <th>Running Balance</th>
                                  <th class="text-center">Action</th>
                              </tr> 
                            </thead>
                            <tbody>
                                <?php $runningBalance = 0; $debit = 0; $cash = 0; ?>
                                <?php if(isset($searchdata['detail']) && !empty($searchdata['detail'])){?>
                                  <?php foreach ($searchdata['detail'] as $key => $value) { ?>
                                    <tr>
                                      <td><?php echo $key+1; ?></td>
                                      <td><?php echo (isset($value['invoice_date']) && $value['invoice_date'] != '') ? date('d/m/Y',strtotime($value['invoice_date'])) : ''; ?></td>
                                      <td><?php echo (isset($value['invoice_no'])) ? $value['invoice_no'] : ''; ?></td>
                                      <td><?php echo (isset($value['vendor'])) ? $value['vendor'] : ''; ?></td>
                                      <td class="text-right">
                                        <?php
                                          if((isset($value['purchase_type']) && $value['purchase_type'] == 'Debit') && (isset($value['amount']) && $value['amount'] != '')){
                                              echo amount_format(number_format($value['amount'], 2, '.', ''));
                                              $debit += $value['amount'];
                                              $runningBalance += $value['amount'];
                                          }
                                        ?>
                                      </td>
                                      <td class="text-right">
                                        <?php
                                          if((isset($value['purchase_type']) && $value['purchase_type'] == 'Cash') && (isset($value['amount']) && $value['amount'] != '')){
                                              echo amount_format(number_format($value['amount'], 2, '.', ''));
                                              $cash += $value['amount'];
                                              $runningBalance -= $value['amount'];
                                          }
                                        ?>
                                      </td>
                                      <td class="text-right">
                                        <?php
                                            echo (isset($runningBalance)) ? amount_format(number_format(abs($runningBalance), 2, '.', '')) : 0;
                                            echo ($runningBalance >= 0) ? ' Dr' : ' Cr';
                                        ?>
                                      </td>
                                      <td class="text-center">
                                          <a class="btn  btn-behance p-2" target="_blank" href="purchase.php?id=<?php echo $value['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                      </td>
                                    </tr>
                                  <?php } ?>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                              <tr style="background-color: #EFEFEF;">
                                <th colspan="4" class="text-center"><strong>Total</strong></th>
                                <th class="text-right">
                                  <?php echo (isset($debit)) ? amount_format(number_format($debit, 2, '.', '')) : 0; ?>
                                </th>
                                <th class="text-right">
                                  <?php echo (isset($cash)) ? amount_format(number_format($cash, 2, '.', '')) : 0; ?>
                                </th>
                                <th class="text-right">
                                    <?php 
                                        echo (isset($runningBalance)) ? amount_format(number_format(abs($runningBalance), 2, '.', '')) : 0;
                                        echo ($runningBalance >= 0) ? ' Dr' : ' Cr';
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
<script src="js/custom/doctor-purchase-report.js"></script>
</body>


</html>
