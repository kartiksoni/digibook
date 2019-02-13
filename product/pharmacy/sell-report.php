<?php $title = "Daily Sales Reports"; ?>
<?php include('include/usertypecheck.php');?>
<?php //include('include/permission.php'); ?>
<?php 
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Sales Reports</title>
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
                    <h4 class="card-title">Sales Reports</h4><hr class="alert-dark">
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
                          <label>Customer Type</label><span class="text-danger">*</span>
                          <select class="js-example-basic-single" style="width:100%" name="customer_type" required="" data-parsley-errors-container="#error-customer"> 
                              <option value="">Please select</option>
                              <option value="GST_Regular" <?php echo (isset($_POST['customer_type']) && $_POST['customer_type'] == 'GST_Regular') ? 'selected' : ''; ?>>GST Registered</option>
                              <option value="GST_unregistered" <?php echo (isset($_POST['customer_type']) && $_POST['customer_type'] == 'GST_unregistered') ? 'selected' : '';?>>Unregistered</option>
                              <option value="all" <?php echo (isset($_POST['customer_type']) && $_POST['customer_type'] == 'all') ? 'selected' : ''; ?>>All</option>
                          </select>
                          <span id="error-customer"></span>
                        </div>

                        <div class="col-12 col-md-2 col-sm-12">
                          <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                          <?php if(isset($_POST['search'])){ ?>
                            <a href="sell-report-print.php?from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>&customer_type=<?php echo (isset($_POST['customer_type'])) ? $_POST['customer_type'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
                          <?php } ?>
                        </div>

                      </div>
                    </form>
                  </div>
                </div>
              </div>

          <?php    
            if(isset($_POST['search'])){

            $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : '';
            $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';
            $customer_type = (isset($_POST['customer_type']) && $_POST['customer_type'] != '') ? $_POST['customer_type'] : '';

            $reportqry = "SELECT tb.invoice_date, tb.invoice_no, lm.name, lm.gstno, tbd.gst_tax, SUM(tbd.totalamount) as total_amount, SUM((tbd.totalamount)-(tbd.gst_tax)) as taxable_amount FROM tax_billing AS tb INNER JOIN ledger_master AS lm ON lm.id = tb.customer_id INNER JOIN tax_billing_details tbd ON tb.id = tbd.tax_bill_id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."' AND lm.group_id = 10";

            if(isset($customer_type) && $customer_type == 'GST_Regular'){
              $reportqry .= " AND lm.customer_type = '".$customer_type."'";
            }

            if(isset($customer_type) && $customer_type == 'GST_unregistered'){
              $reportqry .= " AND (lm.customer_type = '".$customer_type."' OR lm.customer_type IS NULL)";
            }
            
            $reportqry .= " GROUP BY tbd.tax_bill_id ORDER BY tb.invoice_date";

            $reportrun = mysqli_query($conn, $reportqry);   
          ?>      
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
                                  <th class="text-center">Invoice Number</th> 
                                  <th class="text-center">Party Name</th>
                                  <th class="text-center">Gst No.</th>
                                  <th class="text-center">Taxable Amount</th>
                                  <th class="text-center">Tax Amount</th>
                                  <th class="text-center">Total Amount</th>
                              </tr> 
                            </thead>
                            <tbody>
                                  <?php 
                                  $count = 1;
                                  $total = 0;
                                  while($data = mysqli_fetch_assoc($reportrun)){ 
                                     ?>
                                    <tr>
                                      <td><?php echo $count; ?></td>
                                      <td><?php echo (isset($data['invoice_date']) && $data['invoice_date'] != '') ? date('d/m/Y', strtotime($data['invoice_date'])) : ''; ?></td>
                          
                                      <td class="text-center"><?php echo (isset($data['invoice_no'])) ? $data['invoice_no'] : '';  ?></td>
                                      <td class="text-center"><?php echo (isset($data['name'])) ? $data['name'] : ''; ?></td>
                                      <td class="text-center"><?php echo (isset($data['gstno'])) ? $data['gstno'] : ''; ?></td>
                                      <td class="text-center"><?php echo (isset($data['taxable_amount'])) ? amount_format(number_format($data['taxable_amount'], 2, '.', '')): ''; ?></td>
                                      <td class="text-center"><?php echo (isset($data['gst_tax'])) ? amount_format(number_format($data['gst_tax'], 2, '.', '')): ''; ?></td>
                                      <td class="text-right"><?php echo (isset($data['total_amount'])) ? amount_format(number_format($data['total_amount'], 2, '.', '')) : ''; ?></td>
                                      <?php $total += $data['total_amount']; ?>
                                    </tr>
                                  <?php $count++; } ?>
                                <?php //} ?>
                            </tbody>
                            <tfoot>
                              <tr style="background-color: #EFEFEF;">
                                <th colspan="7" class="text-center"><strong>Total / Closing Balance</strong></th>
                             
                                <th class="text-right"><?php echo (isset($total)) ? amount_format(number_format($total, 2, '.', '')) : 0; ?></th>
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
<script src="js/custom/item-code.js"></script>
<!--<script src="js/custom/customer_ledger.js"></script>-->
</body>


</html>
