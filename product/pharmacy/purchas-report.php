<?php $title = "Customer Ledger"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>
<?php 
$pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';

if(isset($_POST['search'])){

  $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : ''; 
  $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';
  $gst = (isset($_POST['gst'])) ? $_POST['gst'] : '';
  $searchdata = getpurchasreport($from , $to, $gst);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Purchase Report</title>
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

            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Purchase Report</h4><hr class="alert-dark">
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
                        <label for="to">GST</label>
                        <select class="js-example-basic-single" required data-parsley-errors-container="#error-gstregistered"  style="width:100%" name="gst" id="vendor"> 
                          <option value="">Please select</option>
                            ?>
                          <option <?php echo(isset($_POST['gst']) && $_POST['gst'] == 'GST_Regular') ? 'selected' : ''; ?> value="GST_Regular">GST Registered</option>
                          <option value="GST_unregistered" <?php echo(isset($_POST['gst']) && $_POST['gst'] == 'GST_unregistered') ? 'selected' : ''; ?>>Unregistered</option>
                          <option value="All" <?php echo(isset($_POST['gst']) && $_POST['gst'] == 'All') ? 'selected' : ''; ?>>All</option>
                        </select>
                        <span id="error-gstregistered"></span>
                      </div>

                      <div class="col-12 col-md-2 col-sm-12">
                        <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                        <?php if(isset($searchdata)){ ?>
                          <a href="purchas-report-print.php?from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>&gst=<?php echo (isset($_POST['gst'])) ? $_POST['gst'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
                        <?php } ?>
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
                                <th>Date</th>
                                <th>Invoice No.</th>
                                <th>Party Name</th>
                                <th>GST NO.</th>
                                <th>Taxable Amount</th> 
                                <th>Tax Amount</th>
                                <th>Total Amount</th>
                              </tr> 
                            </thead>
                            <tbody>
                           <?php
                           $total = 0;
                           if(!empty($searchdata)){
                            foreach($searchdata as $key => $value){
                              ?>
                              <tr>
                                <td width="7%"><?php echo $key+1; ?></td>
                                <td width="8%"><?php echo (isset($value['invoice_date']) && $value['invoice_date'] != '') ? date('d/m/Y', strtotime($value['invoice_date'])) : '-'; ?></td>
                                <td><?php echo (isset($value['invoice_no'])) ? $value['invoice_no'] : '';  ?></td>
                                <td class="text-center"><?php echo (isset($value['name'])) ? $value['name'] : '';  ?></td>
                                <td class="text-center"><?php echo (isset($value['gstno'])) ? $value['gstno'] : '';  ?></td>
                                <td class="text-center"><?php echo (isset($value['taxable_amount'])) ? $value['taxable_amount'] : '';  ?></td>
                                <td class="text-center"><?php echo (isset($value['tax_amount'])) ? $value['tax_amount'] : '';  ?></td>
                                <td class="text-center"><?php echo (isset($value['total_amount'])) ? $value['total_amount'] : '';  ?></td>
                                <?php $total += $value['total_amount']; ?>
                              </tr>
                            <?php } } ?>
                          </tbody>
                          <tfoot>
                            <tr style="background-color: #EFEFEF;">
                              <th colspan="7" class="text-center"><strong>Total / Purchase Balance</strong></th>

                              <th class="text-center"><?php echo (isset($total)) ? amount_format(number_format($total, 2, '.', '')) : 0; ?></th>
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

 
 <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>

</body>


</html>
