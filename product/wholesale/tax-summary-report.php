<?php $title = "Daily Sales Reports"; ?>
<?php include('include/usertypecheck.php');?>
<?php //include('include/permission.php'); ?>
<?php 
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';

  if(isset($_POST['search'])){
     
    $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : ''; 
    $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';
    //$tax = tax_summary($from,$to);
    $searchdata = getTaxSummaryReport($from, $to, 0);
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Tax Summary Report</title>
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
                    <h4 class="card-title">Tax Summary Report</h4><hr class="alert-dark">
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

                       

                        <div class="col-12 col-md-5 col-sm-12">
                          <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                          <?php if(isset($searchdata)){ ?>
                            <a href="tax-summary-report-print.php?from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
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
                                   <th width="7%">Sr. No</th>
                                  <th width="8%">Invoice Date</th>
                                  <th class="text-center">Invoice Number</th> 
                                  <th class="text-center">Party Name</th>
                                  <th class="text-center">Bill Type</th>
                                  <th class="text-center">Sale/Purchase</th>
                                  <th class="text-center">CGST</th>
                                  <th class="text-center">SGST</th>
                                  <th class="text-center">IGST</th>
                                  <th class="text-center">Amount</th>
                                  <th class="text-center">Action</th>
                              </tr> 
                            </thead>
                            <tbody>

                                <?php if(isset($searchdata) && !empty($searchdata)){ ?>
                                  <?php $totaligstS = 0; $totaligstP = 0; $totalcgstS = 0; $totalcgstP = 0; $totalsgstS = 0; $totalsgstP = 0; $totalamountS = 0; $totalamountP = 0; ?>
                                  <?php foreach ($searchdata as $key => $value) { ?>
                                    <tr>
                                      <td><?php echo $key+1; ?></td>
                                      <td><?php echo (isset($value['invoice_date']) && $value['invoice_date'] != '' && $value['invoice_date'] != '0000-00-00') ? date('d/m/Y', strtotime($value['invoice_date'])) : ''; ?></td>
                                      <td class="text-center"><?php echo (isset($value['invoice_no'])) ? $value['invoice_no'] : '';  ?></td>
                                      <td class="text-center"><?php echo (isset($value['perticular'])) ? $value['perticular'] : ''; ?></td>
                                      <td class="text-center"><?php echo (isset($value['bill_type'])) ? $value['bill_type'] : ''; ?></td>
                                      <td class="text-center"><?php echo (isset($value['type'])) ? $value['type'] : ''; ?></td>
                                      <td class="text-right">
                                        <?php
                                          if(isset($value['cgst']) && $value['cgst'] != ''){
                                            echo amount_format(number_format($value['cgst'], 2, '.', ''));
                                            if(isset($value['type']) && $value['type'] == 'Purchase'){
                                              $totalcgstP += $value['cgst'];
                                            }else{
                                              $totalcgstS += $value['cgst'];
                                            }
                                          }
                                        ?>
                                      </td>
                                      <td class="text-right">
                                        <?php
                                          if(isset($value['sgst']) && $value['sgst'] != ''){
                                            echo amount_format(number_format($value['sgst'], 2, '.', ''));
                                            if(isset($value['type']) && $value['type'] == 'Purchase'){
                                              $totalsgstP += $value['sgst'];
                                            }else{
                                              $totalsgstS += $value['sgst'];
                                            }
                                          }
                                        ?>
                                      </td>
                                      <td class="text-right">
                                        <?php
                                          if(isset($value['igst']) && $value['igst'] != ''){
                                            echo amount_format(number_format($value['igst'], 2, '.', ''));
                                            if(isset($value['type']) && $value['type'] == 'Purchase'){
                                              $totaligstP += $value['igst'];
                                            }else{
                                              $totaligstS += $value['igst'];
                                            }
                                          }
                                        ?>
                                      </td>
                                      <td class="text-right">
                                        <?php
                                          if(isset($value['amount']) && $value['amount'] != ''){
                                            echo amount_format(number_format($value['amount'], 2, '.', ''));
                                            if(isset($value['type']) && $value['type'] == 'Purchase'){
                                              $totalamountP += $value['amount'];
                                            }else{
                                              $totalamountS += $value['amount'];
                                            }
                                          }
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
                                <th colspan="6" class="text-center"><strong>Total / Closing Balance</strong></th>
                                <th class="text-right">
                                  <?php echo (isset($totalcgstS) && isset($totalcgstP)) ? amount_format(number_format(($totalcgstS-$totalcgstP), 2, '.', '')) : 0; ?>
                                </th>
                                <th class="text-right">
                                  <?php echo (isset($totalsgstS) && isset($totalsgstP)) ? amount_format(number_format(($totalsgstS-$totalsgstP), 2, '.', '')) : 0; ?>
                                </th>
                                <th class="text-right">
                                  <?php echo (isset($totaligstS) && isset($totaligstP)) ? amount_format(number_format(($totaligstS-$totaligstP), 2, '.', '')) : 0; ?>
                                </th>
                                <th class="text-right">
                                  <?php echo (isset($totalamountS) && isset($totalamountP)) ? amount_format(number_format(($totalamountS-$totalamountP), 2, '.', '')) : 0; ?>
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
  <!-- End custom js for this page-->
</body>


</html>
