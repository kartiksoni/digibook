<?php $title = "Item Registration Report"; ?>
<?php include('include/usertypecheck.php');?>
<?php //include('include/permission.php'); ?>
<?php 
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';

  if($_POST){
    $companycode = (isset($_POST['company_code'])) ? $_POST['company_code'] : '';
    $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : '';
    $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';

    $searchdata = stockdetailPriceReport($from, $to, $companycode);
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Item Register Report</title>
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
                    <h4 class="card-title">Item Register Report</h4><hr class="alert-dark">
                    <form class="forms-sample" method="GET" action="item-registration-report-print.php" target="_blank" autocomplete="off">
                      <div class="form-group row">

                        <div class="col-12 col-md-2">
                            <label for="from">From <span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker" name="from" id="from" placeholder="DD/MM/YYYY" value="<?php echo (isset($_POST['from'])) ? $_POST['from'] : date('d/m/Y'); ?>" required>
                        </div>

                        <div class="col-12 col-md-2">
                            <label for="to">To <span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker" name="to" id="to" placeholder="DD/MM/YYYY" value="<?php echo (isset($_POST['to'])) ? $_POST['to'] : date('d/m/Y'); ?>" required>
                        </div>

                        <div class="col-12 col-md-3">
                          <label for="product_by">Product By <span class="text-danger">*</span></label>
                          <div class="row no-gutters">
                              <div class="col">
                                  <div class="form-radio">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input product_by" name="product_by" value="0" data-parsley-multiple="product_by" required data-parsley-errors-container="#error-product_by" checked>
                                      MRP
                                      <i class="input-helper"></i>
                                    </label>
                                  </div>
                              </div>
                              <div class="col">
                                  <div class="form-radio">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input product_by" name="product_by" value="1" data-parsley-multiple="product_by" required data-parsley-errors-container="#error-product_by">
                                      Item Name
                                      <i class="input-helper"></i>
                                    </label>
                                  </div>
                              </div>
                          </div>
                          <span id="error-product_by"></span>
                        </div>

                        <div class="col-12 col-md-2" id="mrp-div">
                            <label for="mrp">MRP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control onlynumber" name="mrp" id="mrp" placeholder="Enter MRP" required>
                        </div>

                        <div class="col-12 col-md-2">
                          <label for="item">Item Selection <span class="text-danger">*</span></label>
                          <select class="js-example-basic-single" name="item" id="item" style="width:100%" required data-parsley-errors-container="#error-item"> 
                            <option value="">Select Item</option>
                          </select>
                          <i class="fa fa-spin fa-refresh item-loader" style="position: absolute; top: 40px; right: 40px; display: none;"></i>
                          <span id="error-item"></span>
                        </div>

                      </div>

                      <div id="detail-div" style="display: none;">
                        <hr/>

                        <div class="form-group row">
                          <div class="col-md-3">
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input type" name="type" value="0" data-parsley-multiple="type" required>
                                    Item Registration In Details
                                    <i class="input-helper"></i>
                                  </label>
                                </div>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input type" name="type" value="1" data-parsley-multiple="type" required>
                                    Item Registration Sale Only
                                    <i class="input-helper"></i>
                                  </label>
                                </div>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input type" name="type" value="2" data-parsley-multiple="type" required>
                                    Item Registration Purchase Only
                                    <i class="input-helper"></i>
                                  </label>
                                </div>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input type" name="type" value="3" data-parsley-multiple="type" required>
                                    Item Registration Batch Wise
                                    <i class="input-helper"></i>
                                  </label>
                                </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group row" id="item_type_div" style="display: none;">
                          <div class="col-md-2">
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input item_type" name="item_type" value="0" data-parsley-multiple="item_type" required>
                                    All
                                    <i class="input-helper"></i>
                                  </label>
                                </div>
                            </div>
                          </div>
                          <div class="col-md-2">
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input item_type" name="item_type" value="1" data-parsley-multiple="item_type" required>
                                    Batch Wise
                                    <i class="input-helper"></i>
                                  </label>
                                </div>
                            </div>
                          </div>
                          <div class="col-md-1">
                              <i class="fa fa-spin fa-refresh batch-loader" style="position: absolute;top: 15px;display: none;"></i>
                            </div>
                          
                        </div>

                        <div class="row" id="item_detail_div" style="display: none;">
                          <div class="col-md-12">
                              <table class="table table-bordered table-striped table-itemdatail">
                                <thead>
                                  <tr>
                                    <th style="width: 70px !important;">Select</th>
                                    <th>Sr. No</th>
                                    <th>Item Name</th>
                                    <th>Batch No</th>
                                    <th>Expiry Date</th>
                                    <th>Current Stock</th>
                                  </tr>
                                </thead>
                                <tbody id="item_detail">
                                  
                                </tbody>
                              </table>
                              <span id="error-item_id"></span>
                          </div>
                        </div>
                      </div>

                      <hr/>

                      <div class="row">
                        <div class="col-md-12 text-center">
                          <button type="submit" name="search" class="btn btn-success">Go</button>
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
                                  <th>Opening Qty. Amount</th>
                                  <th>Purchase Qty. Amount</th>
                                  <th>Purchase Retu Qty. Amount</th>
                                  <th>Sale Qty. Amount</th>
                                  <th>Sale Retu Qty. Amount</th>
                                  <th>Total Qty</th>
                              </tr> 
                            </thead>
                            <tbody>

                                <?php if(isset($searchdata) && !empty($searchdata)){?>
                                  <?php 
                                    $totalOpeningStock = 0; $totalPurchaseQtyAmount = 0; $totalPurchaseReturnQtyAmount = 0; $totalSaleQtyAmount = 0; $totalSaleReturnQtyAmount = 0; $totalQtyAmount = 0;
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
                                          $openingStock = (isset($value['opening_stock']) && $value['opening_stock'] != '') ? $value['opening_stock'] : 0;
                                          $totalOpeningStock += $openingStock;
                                          $total += $openingStock;
                                          echo amount_format(number_format($openingStock, 2, '.', ''));
                                        ?>
                                      </td>
                                      <td class="text-right">
                                        <?php
                                          $purchaseQtyAmount = (isset($value['purchaseAmount']) && $value['purchaseAmount'] != '') ? $value['purchaseAmount'] : 0;
                                          $totalPurchaseQtyAmount += $purchaseQtyAmount;
                                          $total += $purchaseQtyAmount;
                                          echo amount_format(number_format($purchaseQtyAmount, 2, '.', ''));
                                        ?>
                                      </td class="text-right">
                                      <td class="text-right">
                                        <?php
                                          $purchaseReturnQtyAmount = (isset($value['purchaseReturnAmount']) && $value['purchaseReturnAmount'] != '') ? $value['purchaseReturnAmount'] : 0;
                                          $totalPurchaseReturnQtyAmount += $purchaseReturnQtyAmount;
                                          $total -= $purchaseReturnQtyAmount;
                                          echo amount_format(number_format($purchaseReturnQtyAmount, 2, '.', ''));
                                        ?>
                                      </td>
                                      <td class="text-right">
                                        <?php
                                          $saleQtyAmount = (isset($value['saleAmount']) && $value['saleAmount'] != '') ? $value['saleAmount'] : 0;
                                          $totalSaleQtyAmount += $saleQtyAmount;
                                          $total -= $saleQtyAmount;
                                          echo amount_format(number_format($saleQtyAmount, 2, '.', ''));
                                        ?>
                                      </td>
                                      <td class="text-right">
                                        <?php
                                          $saleReturnQtyAmount = (isset($value['saleReturnAmount']) && $value['saleReturnAmount'] != '') ? $value['saleReturnAmount'] : 0;
                                          $totalSaleReturnQtyAmount += $saleReturnQtyAmount;
                                          $total += $saleReturnQtyAmount;
                                          echo amount_format(number_format($saleReturnQtyAmount, 2, '.', ''));
                                        ?>
                                      </td>
                                      <td class="text-right">
                                        <?php
                                          echo (isset($total)) ? amount_format(number_format($total, 2, '.', '')) : 0;
                                          $totalQtyAmount += $total;
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
                                  <?php echo (isset($totalOpeningStock)) ? amount_format(number_format($totalOpeningStock, 2, '.', '')) : 0; ?>
                                </th>
                                <th class="text-right">
                                  <?php echo (isset($totalPurchaseQtyAmount)) ? amount_format(number_format($totalPurchaseQtyAmount, 2, '.', '')) : 0; ?>
                                </th>
                                <th class="text-right">
                                  <?php echo (isset($totalPurchaseReturnQtyAmount)) ? amount_format(number_format($totalPurchaseReturnQtyAmount, 2, '.', '')) : 0; ?>
                                </th>
                                <th class="text-right">
                                  <?php echo (isset($totalSaleQtyAmount)) ? amount_format(number_format($totalSaleQtyAmount, 2, '.', '')) : 0; ?>
                                </th>
                                <th class="text-right">
                                  <?php echo (isset($totalSaleReturnQtyAmount)) ? amount_format(number_format($totalSaleReturnQtyAmount, 2, '.', '')) : 0; ?>
                                </th>
                                <th class="text-right">
                                  <?php echo (isset($totalQtyAmount)) ? amount_format(number_format($totalQtyAmount, 2, '.', '')) : 0; ?>
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
  
  <div id="product-detail-tr" style="display: none;">
    <table>
      <tr>
        <td class="text-center">
          <input type="radio" name="item_id" class="item_id" value="##PRODUCTID##">
        </td>
        <td>##SRNO##</td>
        <td>##PRODUCTNAME##</td>
        <td>##BATCHNO##</td>
        <td>##EXPIRY##</td>
        <td>##CURRENTSTOCK##</td>
      </tr>
    </table>
  </div>
  

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
<script src="js/custom/item-registration-report.js"></script>
</body>


</html>
