<?php $title = "Stock Detail Quantity"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>
<?php 
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';

  if($_POST){
    $companycode = (isset($_POST['company_code'])) ? $_POST['company_code'] : '';
    $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : '';
    $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';

    $searchdata = stockdetailReport($from, $to, $companycode);
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Stock Detail Quantity</title>
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
                    <h4 class="card-title">Stock Detail Quantity Reports</h4><hr class="alert-dark">
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
                          <label for="company">Company</label>
                          <select class="js-example-basic-single" name="company" id="company" style="width:100%" required=""> 
                            <option value="all" <?php echo (isset($_POST['company']) && $_POST['company'] == 'all') ? 'selected' : ''; ?>>All</option>
                            <option value="company_wise" <?php echo (isset($_POST['company']) && $_POST['company'] == 'company_wise') ? 'selected' : ''; ?>>Company Wise</option>
                          </select>
                        </div>

                        <div class="col-12 col-md-2 <?php echo (isset($_POST['company']) && $_POST['company'] == 'company_wise') ? 'display-block' : 'display-none'; ?>" id="company-code-div">
                          <label for="company_code">Company Code</label>
                          <select class="js-example-basic-single" name="company_code" id="company_code" style="width:100%" data-parsley-errors-container="#error-companycode" required=""> 
                            <option value="">Select Code</option>
                            <?php
                              $companyCodeQ = "SELECT id, name, code FROM `company_master` WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1";
                              $companyCodeR = mysqli_query($conn, $companyCodeQ);
                              if($companyCodeR && mysqli_num_rows($companyCodeR) > 0){
                                while ($companyCodeRow = mysqli_fetch_assoc($companyCodeR)) {
                            ?>
                              <option value="<?php echo (isset($companyCodeRow['id'])) ? $companyCodeRow['id'] : ''; ?>" <?php echo (isset($_POST['company_code']) && $_POST['company_code'] == $companyCodeRow['id']) ? 'selected' : ''; ?>><?php echo (isset($companyCodeRow['code'])) ? $companyCodeRow['code'] : ''; ?></option>
                            <?php
                                }
                              }
                            ?>
                          </select>
                          <span id="error-companycode"></span>
                        </div>

                        <div class="col-12 col-md-2">
                          <label for="betch_wise">Batch Wise</label>
                          <select class="js-example-basic-single" name="betch_wise" id="betch_wise" style="width:100%" required=""> 
                            <option value="1" <?php echo (isset($_POST['betch_wise']) && $_POST['betch_wise'] == 1) ? 'selected' : ''; ?> >Yes</option>
                            <option value="0" <?php echo (isset($_POST['betch_wise']) && $_POST['betch_wise'] == 0) ? 'selected' : ''; ?>>No</option>
                          </select>
                        </div>

                        <div class="col-12 col-md-2">
                          <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                          <?php if(isset($searchdata) && !empty($searchdata)){ ?>
                            <a href="stock-detail-qty-report-print.php?from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>&batch=<?php echo (isset($_POST['betch_wise'])) ? $_POST['betch_wise'] : 0; ?>&company=<?php echo (isset($_POST['company_code'])) ? $_POST['company_code'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
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
                                  <th>Product Name</th>
                                  <?php if(isset($_POST['betch_wise']) && $_POST['betch_wise'] == 1){ ?>
                                    <th>Batch No</th>
                                  <?php } ?>
                                  <th>Unit</th>
                                  <th>Opening Qty</th>
                                  <th>Purchase Qty</th>
                                  <th>Purchase Retu Qty</th>
                                  <th>Sale Qty</th>
                                  <th>Sale Retu Qty</th>
                                  <th>Total Qty</th>
                              </tr> 
                            </thead>
                            <tbody>

                                <?php if(isset($searchdata) && !empty($searchdata)){?>
                                  <?php 
                                    $totalOpeningQty = 0; $totalPurchaseQty = 0; $totalPurchaseReturnQty = 0; $totalSaleQty = 0; $totalSaleReturnQty = 0; $totalQty = 0;
                                  ?>
                                  <?php foreach ($searchdata as $key => $value) { $total = 0;?>
                                    <tr>
                                      <td><?php echo $key+1; ?></td>
                                      <td><?php echo (isset($value['product_name'])) ? $value['product_name'] : ''; ?></td>
                                      <?php if(isset($_POST['betch_wise']) && $_POST['betch_wise'] == 1){ ?>
                                        <td><?php echo (isset($value['batch_no'])) ? $value['batch_no'] : '';  ?></td>
                                      <?php } ?>
                                      <td><?php echo (isset($value['unit'])) ? $value['unit'] : ''; ?></td>
                                      <td class="text-right">
                                        <?php
                                          $openingQty = (isset($value['opening_qty']) && $value['opening_qty'] != '') ? $value['opening_qty'] : 0;
                                          $totalOpeningQty += $openingQty;
                                          $total += $openingQty;
                                          echo amount_format(number_format($openingQty, 2, '.', ''));
                                        ?>
                                      </td>
                                      <td class="text-right">
                                        <?php
                                          $purchaseQty = (isset($value['purchaseqty']) && $value['purchaseqty'] != '') ? $value['purchaseqty'] : 0;
                                          $totalPurchaseQty += $purchaseQty;
                                          $total += $purchaseQty;
                                          echo amount_format(number_format($purchaseQty, 2, '.', ''));
                                        ?>
                                      </td class="text-right">
                                      <td class="text-right">
                                        <?php
                                          $purchaseReturnQty = (isset($value['purchasereturn']) && $value['purchasereturn'] != '') ? $value['purchasereturn'] : 0;
                                          $totalPurchaseReturnQty += $purchaseReturnQty;
                                          $total -= $purchaseReturnQty;
                                          echo amount_format(number_format($purchaseReturnQty, 2, '.', ''));
                                        ?>
                                      </td>
                                      <td class="text-right">
                                        <?php
                                          $saleQty = (isset($value['saleqty']) && $value['saleqty'] != '') ? $value['saleqty'] : 0;
                                          $totalSaleQty += $saleQty;
                                          $total -= $saleQty;
                                          echo amount_format(number_format($saleQty, 2, '.', ''));
                                        ?>
                                      </td>
                                      <td class="text-right">
                                        <?php
                                          $saleReturnQty = (isset($value['salereturn']) && $value['salereturn'] != '') ? $value['salereturn'] : 0;
                                          $totalSaleReturnQty += $saleReturnQty;
                                          $total += $saleReturnQty;
                                          echo amount_format(number_format($saleReturnQty, 2, '.', ''));
                                        ?>
                                      </td>
                                      <td class="text-right">
                                        <?php
                                          echo (isset($total)) ? amount_format(number_format($total, 2, '.', '')) : 0;
                                          $totalQty += $total;
                                        ?>
                                      </td>
                                    </tr>
                                  <?php } ?>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                              <tr style="background-color: #EFEFEF;">
                                <th colspan="<?php echo (isset($_POST['betch_wise']) && $_POST['betch_wise'] == 1) ? 4 : 3; ?>" class="text-center"><strong>Total</strong></th>
                                <th class="text-right">
                                  <?php echo (isset($totalOpeningQty)) ? amount_format(number_format($totalOpeningQty, 2, '.', '')) : 0; ?>
                                </th>
                                <th class="text-right">
                                  <?php echo (isset($totalPurchaseQty)) ? amount_format(number_format($totalPurchaseQty, 2, '.', '')) : 0; ?>
                                </th>
                                <th class="text-right">
                                  <?php echo (isset($totalPurchaseReturnQty)) ? amount_format(number_format($totalPurchaseReturnQty, 2, '.', '')) : 0; ?>
                                </th>
                                <th class="text-right">
                                  <?php echo (isset($totalSaleQty)) ? amount_format(number_format($totalSaleQty, 2, '.', '')) : 0; ?>
                                </th>
                                <th class="text-right">
                                  <?php echo (isset($totalSaleReturnQty)) ? amount_format(number_format($totalSaleReturnQty, 2, '.', '')) : 0; ?>
                                </th>
                                <th class="text-right">
                                  <?php echo (isset($totalQty)) ? amount_format(number_format($totalQty, 2, '.', '')) : 0; ?>
                                </th>
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
  
  <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
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
<script src="js/custom/stock-detail-qty-report.js"></script>
</body>


</html>
