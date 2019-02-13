<?php $title = "GSTR1 Report"; ?>
<?php include('include/usertypecheck.php');?>
<?php //include('include/permission.php'); ?>

<?php
    if(isset($_POST['search'])){
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
        $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : ''; 
        $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';
        $searchData = [];
        
        // getB2B
        $b2b = getGSTR1B2BReport($from, $to, 0);
        $tmp['desc'] = "4. Taxable outward supplies made to registered persons (including UIN-holders)";
        $tmp['taxable_value'] = (isset($b2b['total_taxable_value']) && $b2b['total_taxable_value'] != '') ? $b2b['total_taxable_value'] : 0;
        $tmp['integrate_tax'] = (isset($b2b['total_igst']) && $b2b['total_igst'] != '') ? $b2b['total_igst'] : 0;
        $tmp['central_tax'] = (isset($b2b['total_cgst']) && $b2b['total_cgst'] != '') ? $b2b['total_cgst'] : 0;
        $tmp['state_tax'] = (isset($b2b['total_sgst']) && $b2b['total_sgst'] != '') ? $b2b['total_sgst'] : 0;
        $searchData[] = $tmp;
        unset($tmp);
        
        $b2cl = getGSTR1B2CLReport($from, $to, 0);
        $tmp['desc'] = "5. Taxable outward inter-State supplies to un-registered persons where the invoice value is more than Rs 2.5 lakh";
        $tmp['taxable_value'] = (isset($b2cl['total_taxable_value']) && $b2cl['total_taxable_value'] != '') ? $b2cl['total_taxable_value'] : 0;
        $tmp['integrate_tax'] = (isset($b2cl['total_igst']) && $b2cl['total_igst'] != '') ? $b2cl['total_igst'] : 0;
        $tmp['central_tax'] = (isset($b2cl['total_cgst']) && $b2cl['total_cgst'] != '') ? $b2cl['total_cgst'] : 0;
        $tmp['state_tax'] = (isset($b2cl['total_sgst']) && $b2cl['total_sgst'] != '') ? $b2cl['total_sgst'] : 0;
        $searchData[] = $tmp;
        unset($tmp);
        
        $exportQ = "SELECT SUM(tb.alltotalamount) as taxable_value, SUM(tb.totaligst) as total_igst, SUM(tb.totalcgst) as total_cgst, SUM(tb.totalsgst) as total_sgst FROM tax_billing tb INNER JOIN ledger_master lg ON tb.customer_id = lg.id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND lg.customer_type = 'Deemed' AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
        $exportR = mysqli_query($conn, $exportQ);
        if($exportR && mysqli_num_rows($exportR) > 0){
            $exportRow = mysqli_fetch_assoc($exportR);
        }
        
        $tmp['desc'] = "6. Zero rated supplies and Deemed Exports";
        $tmp['taxable_value'] = (isset($exportRow['taxable_value']) && $exportRow['taxable_value'] != '') ? $exportRow['taxable_value'] : 0;
        $tmp['integrate_tax'] = (isset($exportRow['total_igst']) && $exportRow['total_igst'] != '') ? $exportRow['total_igst'] : 0;
        $tmp['central_tax'] = (isset($exportRow['total_cgst']) && $exportRow['total_cgst'] != '') ? $exportRow['total_cgst'] : 0;
        $tmp['state_tax'] = (isset($exportRow['total_sgst']) && $exportRow['total_sgst'] != '') ? $exportRow['total_sgst'] : 0;
        $searchData[] = $tmp;
        unset($tmp);
        
        $b2cs = getGSTR1B2CSReport($from, $to, 0);
        $tmp['desc'] = "7. Taxable supplies to unregistered persons";
        $tmp['taxable_value'] = (isset($b2cs['total_taxable_value']) && $b2cs['total_taxable_value'] != '') ? $b2cs['total_taxable_value'] : 0;
        $tmp['integrate_tax'] = (isset($b2cs['total_igst']) && $b2cs['total_igst'] != '') ? $b2cs['total_igst'] : 0;
        $tmp['central_tax'] = (isset($b2cs['total_cgst']) && $b2cs['total_cgst'] != '') ? $b2cs['total_cgst'] : 0;
        $tmp['state_tax'] = (isset($b2cs['total_sgst']) && $b2cs['total_sgst'] != '') ? $b2cs['total_sgst'] : 0;
        $searchData[] = $tmp;
        unset($tmp);
        
        $nilratedQ = "SELECT SUM(tbd.totalamount) as taxable_value, SUM(tbd.totalamount*tbd.igst/100) as total_igst, SUM(tbd.totalamount*tbd.cgst/100) as total_cgst, SUM(tbd.totalamount*tbd.sgst/100) as total_sgst  FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id INNER JOIN product_master pm ON tbd.product_id = pm.id WHERE pm.gst_id = '3' AND tb.pharmacy_id = '".$pharmacy_id."' AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
        $nilratedR = mysqli_query($conn, $nilratedQ);
        if($nilratedR && mysqli_num_rows($nilratedR) > 0){
            $nilratedRow = mysqli_fetch_assoc($nilratedR);
        }
        
        $tmp['desc'] = "8. Nil rated exempted and non GST outward supplies";
        $tmp['taxable_value'] = (isset($nilratedRow['taxable_value']) && $nilratedRow['taxable_value'] != '') ? $nilratedRow['taxable_value'] : 0;
        $tmp['integrate_tax'] = (isset($nilratedRow['total_igst']) && $nilratedRow['total_igst'] != '') ? $nilratedRow['total_igst'] : 0;
        $tmp['central_tax'] = (isset($nilratedRow['total_cgst']) && $nilratedRow['total_cgst'] != '') ? $nilratedRow['total_cgst'] : 0;
        $tmp['state_tax'] = (isset($nilratedRow['total_sgst']) && $nilratedRow['total_sgst'] != '') ? $nilratedRow['total_sgst'] : 0;
        $searchData[] = $tmp;
        unset($tmp);
        
        $hsn = getGSTR1HSNReport($from, $to, 0);
        $tmp['desc'] = "12. HSN-wise summary of outward supplies";
        $tmp['taxable_value'] = (isset($hsn['total_taxable']) && $hsn['total_taxable'] != '') ? $hsn['total_taxable'] : 0;
        $tmp['integrate_tax'] = (isset($hsn['total_igst']) && $hsn['total_igst'] != '') ? $hsn['total_igst'] : 0;
        $tmp['central_tax'] = (isset($hsn['total_cgst']) && $hsn['total_cgst'] != '') ? $hsn['total_cgst'] : 0;
        $tmp['state_tax'] = (isset($hsn['total_sgst']) && $hsn['total_sgst'] != '') ? $hsn['total_sgst'] : 0;
        $searchData[] = $tmp;
        unset($tmp);
        
        $cdnr = getGSTR1CDNRReport($from, $to, 0);
        $tmp['desc'] = "Details of Credit/Debit Notes and Refund voucher";
        $tmp['taxable_value'] = (isset($cdnr['total_taxable_value']) && $cdnr['total_taxable_value'] != '') ? $cdnr['total_taxable_value'] : 0;
        $tmp['integrate_tax'] = (isset($cdnr['total_igst']) && $cdnr['total_igst'] != '') ? $cdnr['total_igst'] : 0;
        $tmp['central_tax'] = (isset($cdnr['total_cgst']) && $cdnr['total_cgst'] != '') ? $cdnr['total_cgst'] : 0;
        $tmp['state_tax'] = (isset($cdnr['total_sgst']) && $cdnr['total_sgst'] != '') ? $cdnr['total_sgst'] : 0;
        $searchData[] = $tmp;
        unset($tmp);
        
        $cdnur = getGSTR1CDNURReport($from, $to, 0);
        $tmp['desc'] = "Details of Credit/Debit Notes and Refund voucher (Unregistered)";
        $tmp['taxable_value'] = (isset($cdnur['total_taxable_value']) && $cdnur['total_taxable_value'] != '') ? $cdnur['total_taxable_value'] : 0;
        $tmp['integrate_tax'] = (isset($cdnur['total_igst']) && $cdnur['total_igst'] != '') ? $cdnur['total_igst'] : 0;
        $tmp['central_tax'] = (isset($cdnur['total_cgst']) && $cdnur['total_cgst'] != '') ? $cdnur['total_cgst'] : 0;
        $tmp['state_tax'] = (isset($cdnur['total_sgst']) && $cdnur['total_sgst'] != '') ? $cdnur['total_sgst'] : 0;
        $searchData[] = $tmp;
        unset($tmp);
        
        $tmp['desc'] = "Consolidated Statement of Advances Received";
        $tmp['taxable_value'] = 0;
        $tmp['integrate_tax'] = 0;
        $tmp['central_tax'] = 0;
        $tmp['state_tax'] = 0;
        $searchData[] = $tmp;
        unset($tmp);
        
        $tmp['desc'] = "Tax already paid (on advance receipt) on invoices Issued in the current period";
        $tmp['taxable_value'] = 0;
        $tmp['integrate_tax'] = 0;
        $tmp['central_tax'] = 0;
        $tmp['state_tax'] = 0;
        $searchData[] = $tmp;
        unset($tmp);
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibook | GSTR 1 Report</title>
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
                  <h4 class="card-title">GSTR 1 Report</h4><hr class="alert-dark">
                  <form class="forms-sample" method="POST" autocomplete="off">
                    <div class="form-group row">
                      <div class="col-12 col-md-2">
                        <label>Month</label>
                        <select class="form-control" name="month" id="month">
                          <option value="" data-start="<?php echo date('d/m/Y'); ?>" data-end="<?php echo date('d/m/Y'); ?>">Select Month</option>
                          <?php
                            $month = [1 => 'January',2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December']; 
                            foreach ($month as $key => $value) {
                          ?>
                            <?php
                              $first_day_this_month = date('01-'.sprintf("%02d", ($key)).'-Y');
                              $last_day_this_month = date("t-m-Y", strtotime($first_day_this_month));
                            ?>
                            <option data-start="<?php echo date('d/m/Y',strtotime($first_day_this_month)); ?>" data-end="<?php echo date('d/m/Y',strtotime($last_day_this_month)); ?>" value="<?php echo $key; ?>" <?php echo (isset($_POST['month']) && $_POST['month'] == $key) ? 'selected' : ''; ?> ><?php echo $value; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-12 col-md-2">
                          <label for="from">From</label>
                          <input type="text" class="form-control datepicker" name="from" id="from" placeholder="DD/MM/YYYY" value="<?php echo (isset($_POST['from'])) ? $_POST['from'] : date('d/m/Y'); ?>" required>
                      </div>
                      <div class="col-12 col-md-2">
                          <label for="to">To</label>
                          <input type="text" class="form-control datepicker" name="to" id="to" placeholder="DD/MM/YYYY" value="<?php echo (isset($_POST['to'])) ? $_POST['to'] : date('d/m/Y'); ?>" required>
                      </div>
                      <div class="col-12 col-md-4">
                        <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                        
                        <?php if(isset($searchData) && !empty($searchData)){ ?>
                            <a href="gstr1-report-print.php?from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>" class="btn btn-success mt-30">Export To All</a>
                        <?php } ?>
                       
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
            <?php if(isset($searchData)){ ?>
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                      <div class="card">
                        <div class="card-body">
                          <table class="table table-bordered m-50">
                            <thead>
                              <tr class="primary">
                                <th width="45%"><b>DESCRIPTION</b></th>
                                <th><b>TOTAL TAXABLE VALUE</b></th>
                                <th><b>TOTAL INTEGRATED TAX</b></th>
                                <th><b>TOTAL CENTRAL TAX</b></th>
                                <th><b>TOTAL STATE TAX</b></th>
                                <th><b>TOTAL VALUE</b></th>
                              </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($searchData)){ ?>
                                    <?php foreach($searchData as $key => $value){ ?>
                                        <tr>
                                            <td><?php echo(isset($value['desc'])) ? $value['desc'] : ''; ?></td>
                                            <td class="text-right"><?php echo(isset($value['taxable_value']) && $value['taxable_value'] != '') ? amount_format(number_format($value['taxable_value'], 2, '.', '')) : 0; ?></td>
                                            <td class="text-right"><?php echo(isset($value['integrate_tax']) && $value['integrate_tax'] != '') ? amount_format(number_format($value['integrate_tax'], 2, '.', '')) : 0; ?></td>
                                            <td class="text-right"><?php echo(isset($value['central_tax']) && $value['central_tax'] != '') ? amount_format(number_format($value['central_tax'], 2, '.', '')) : 0; ?></td>
                                            <td class="text-right"><?php echo(isset($value['state_tax']) && $value['state_tax'] != '') ? amount_format(number_format($value['state_tax'], 2, '.', '')) : 0; ?></td>
                                            <td class="text-right">
                                                <?php 
                                                    $taxable_value = (isset($value['taxable_value']) && $value['taxable_value'] != '') ? $value['taxable_value'] : 0;
                                                    $integrate_tax = (isset($value['integrate_tax']) && $value['integrate_tax'] != '') ? $value['integrate_tax'] : 0;
                                                    $central_tax = (isset($value['central_tax']) && $value['central_tax'] != '') ? $value['central_tax'] : 0;
                                                    $state_tax = (isset($value['state_tax']) && $value['state_tax'] != '') ? $value['state_tax'] : 0;
                                                    $total = ($taxable_value+$integrate_tax+$central_tax+$state_tax);
                                                    echo amount_format(number_format($total, 2, '.', ''));
                                                ?>
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
            <?php } ?>
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
      $('#month').on('change', function() {
        var startdate = $(this).find(':selected').attr('data-start');
        var enddate = $(this).find(':selected').attr('data-end');

        $('#from').attr('value', startdate);
        $('#to').attr('value', enddate);
        $(".datepicker").datepicker();
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
  <!-- <script src="js/custom/customer_ledger.js"></script> -->
  <!-- End custom js for this page-->


</body>


</html>
