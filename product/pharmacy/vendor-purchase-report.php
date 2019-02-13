<?php $title = "Stock Detail Quantity"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>
<?php 
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';

  if(isset($_POST['search'])){
      $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : '';
      $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';
      $vendor = (isset($_POST['vendor'])) ? $_POST['vendor'] : '';
      $view = (isset($_POST['view'])) ? $_POST['view'] : '';
      $type = (isset($_POST['type'])) ? $_POST['type'] : '';
      $company = (isset($_POST['company'])) ? $_POST['company'] : '';
      $product = (isset($_POST['product'])) ? $_POST['product'] : '';
      $mrp = (isset($_POST['mrp'])) ? $_POST['mrp'] : '';

      $searchdata = vendorPurchaseReport($from, $to, $vendor, $view, $type, $company, $product, $mrp);
  }

  $allCompany = [];
  $cpnyQ = "SELECT id, name FROM company_master WHERE pharmacy_id = '".$pharmacy_id."' ORDER BY name";
  $cpnyR = mysqli_query($conn, $cpnyQ);
  if($cpnyR && mysqli_num_rows($cpnyR) > 0){
    while ($cpnyRow = mysqli_fetch_assoc($cpnyR)) {
      $allCompany[] = $cpnyRow;
    }
  }
  
  //pr($allCompany);exit;

  $allProduct = [];
  $productQ = "SELECT id, product_name as name FROM product_master WHERE pharmacy_id = '".$pharmacy_id."'";
  if(isset($_POST['mrp']) && $_POST['mrp'] != ''){
    $productQ .= " AND mrp = '".$_POST['mrp']."'";
  }
  $productQ .= " GROUP BY product_name ORDER BY product_name";
  $productR = mysqli_query($conn, $productQ);
  if($productR && mysqli_num_rows($productR) > 0){
    while ($productRow = mysqli_fetch_assoc($productR)) {
      $allProduct[] = $productRow;
    }
  }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Vendor Purchase Report</title>
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
                    <h4 class="card-title">Vendor Purchase Report</h4><hr class="alert-dark">
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
                          <label for="city">Vendor City <span class="text-danger">*</span></label>
                          <select class="form-control" name="city" id="city" style="width:100%" data-parsley-errors-container="#error-v-city"  required>
                            <?php 
                                if(isset($_POST['city']) && $_POST['city'] != ''){
                                    $getCustomerCityQ = "SELECT id, name FROM own_cities WHERE id = '".$_POST['city']."'";
                                    $getCustomerCityR = mysqli_query($conn, $getCustomerCityQ);
                                    if($getCustomerCityR && mysqli_num_rows($getCustomerCityR) > 0){
                                        $getCustomerCityRow = mysqli_fetch_assoc($getCustomerCityR);
                                        echo "<option value='".$getCustomerCityRow['id']."' selected>".$getCustomerCityRow['name']."</option>";
                                    }
                                }
                            ?>
                          </select>
                          <span id="error-v-city"></span>
                        </div>

                        <div class="col-12 col-md-2">
                          <label for="vendor">Vendor Name <span class="text-danger">*</span></label>
                          <select class="js-example-basic-single form-control" name="vendor" id="vendor" style="width:100%" data-parsley-errors-container="#error-v-name" required>
                            <option value=""> Select Vendor</option>
                            <?php 
                                if(isset($_POST['city']) && $_POST['city'] != ''){
                                    $getVendorQ = "SELECT id, name FROM ledger_master WHERE city = '".$_POST['city']."' AND pharmacy_id = '".$pharmacy_id."' AND group_id = 14";
                                    $getVendorR = mysqli_query($conn, $getVendorQ);
                                    if($getVendorR && mysqli_num_rows($getVendorR) > 0){
                                      while ($getVendorRow = mysqli_fetch_assoc($getVendorR)) {
                            ?>
                                        <option value="<?php echo (isset($getVendorRow['id'])) ? $getVendorRow['id'] : ''; ?>" <?php echo (isset($_POST['vendor']) && $_POST['vendor'] == $getVendorRow['id']) ? 'selected' : ''; ?> ><?php echo (isset($getVendorRow['name'])) ? $getVendorRow['name'] : ''; ?></option>
                            <?php
                                      }
                                    }
                                }
                            ?>
                          </select>
                          <span id="error-v-name"></span>
                        </div>

                        <div class="col-12 col-md-3">
                          <?php 
                            $detail = 'checked';
                            $summary = '';
                            if(isset($_POST['view']) && $_POST['view'] == 'detail'){
                              $detail = 'checked';
                            }elseif(isset($_POST['view']) && $_POST['view'] == 'summary'){
                              $summary = 'checked';
                            }
                          ?>
                          <label for="view">Select Any One</label>
                          <div class="row no-gutters">
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input bill_type" name="view" value="detail" data-parsley-multiple="view" <?php echo (isset($detail)) ? $detail : ''; ?> >
                                     Detail
                                  <i class="input-helper"></i></label>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input bill_type" name="view" value="summary" data-parsley-multiple="view" <?php echo (isset($summary)) ? $summary : ''; ?> >
                                    Summary
                                <i class="input-helper"></i></label>
                                </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">

                        <div class="col-12 col-md-6">
                          <?php
                            $all_type = 'checked';
                            $company_wise_type = '';
                            $single_product_type = '';
                            if(isset($_POST['type']) && $_POST['type'] == 'all'){
                              $all_type = 'checked';
                              $company_wise_type = '';
                              $single_product_type = '';
                            }elseif(isset($_POST['type']) && $_POST['type'] == 'company_wise'){
                              $all_type = '';
                              $company_wise_type = 'checked';
                              $single_product_type = '';
                            }elseif(isset($_POST['type']) && $_POST['type'] == 'single_product'){
                              $all_type = '';
                              $company_wise_type = '';
                              $single_product_type = 'checked';
                            }
                          ?>
                          <label for="type"></label>
                          <div class="row no-gutters">

                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input type" name="type" value="all" data-parsley-multiple="type" <?php echo (isset($all_type)) ? $all_type : ''; ?>>
                                    All
                                    <i class="input-helper"></i>
                                  </label>
                                </div>
                            </div>
                              
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input type" name="type" value="company_wise" data-parsley-multiple="type" <?php echo (isset($company_wise_type)) ? $company_wise_type : ''; ?>>
                                    Company Wise
                                    <i class="input-helper"></i>
                                  </label>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input type" name="type" value="single_product" data-parsley-multiple="type" <?php echo (isset($single_product_type)) ? $single_product_type : ''; ?>>
                                    Single Product
                                    <i class="input-helper"></i>
                                  </label>
                                </div>
                            </div>

                          </div>
                        </div>

                        <div class="col-12 col-md-2" id="company_div" style="display:<?php echo (isset($_POST['type']) && $_POST['type'] == 'company_wise') ? 'unset;' : 'none;'; ?>">
                          <label for="company">Company Name <span class="text-danger">*</span></label>
                          <select class="js-example-basic-single form-control" name="company" id="company" style="width:100%" data-parsley-errors-container="#error-company" required>
                            <option value=""> Select Company</option>
                            <?php if(isset($allCompany) && !empty($allCompany)){ ?>
                              <?php foreach ($allCompany as $key => $value) { ?>
                                <option value="<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>" <?php echo ((isset($_POST['type']) && $_POST['type'] == 'company_wise') && (isset($_POST['company']) && $_POST['company'] == $value['id'])) ? 'selected' : ''; ?> ><?php echo (isset($value['name'])) ? $value['name'] : 'Unknown Company'; ?></option>
                              <?php } ?>
                            <?php } ?>
                          </select>
                          <span id="error-company"></span>
                        </div>

                        <div class="col-12 col-md-2" id="product_div" style="display:<?php echo (isset($_POST['type']) && $_POST['type'] == 'single_product') ? 'unset;' : 'none;'; ?>">
                          <label for="product">Product Name <span class="text-danger">*</span></label>
                          <select class="js-example-basic-single form-control" name="product" id="product" style="width:100%" data-parsley-errors-container="#error-product" required>
                            <option value=""> Select Product</option>
                            <?php if(isset($allProduct) && !empty($allProduct)){ ?>
                              <?php foreach ($allProduct as $key => $value) { ?>
                                <option value="<?php echo (isset($value['name'])) ? $value['name'] : ''; ?>" <?php echo ((isset($_POST['type']) && $_POST['type'] == 'single_product') && (isset($_POST['product']) && $_POST['product'] == $value['name'])) ? 'selected' : ''; ?> ><?php echo (isset($value['name'])) ? $value['name'] : 'Unknown Company'; ?></option>
                              <?php } ?>
                            <?php } ?>
                          </select>
                          <span id="error-product"></span>
                        </div>

                        <div class="col-12 col-md-2" id="mrp_div" style="display:<?php echo (isset($_POST['type']) && $_POST['type'] == 'single_product') ? 'unset;' : 'none;'; ?>">
                            <label for="mrp">MRP</label>
                            <input type="text" class="form-control onlynumber" name="mrp" placeholder="MRP" id="mrp" value="<?php echo ((isset($_POST['type']) && $_POST['type'] == 'single_product') && (isset($_POST['mrp']))) ? $_POST['mrp'] : ''; ?>">
                        </div>

                        <div class="col-12 col-md-2">
                          <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                          <?php if(isset($searchdata) && !empty($searchdata)){ ?>
                            <a href="vendor-purchase-report-print.php?from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>&vendor=<?php echo (isset($_POST['vendor'])) ? $_POST['vendor'] : 0; ?>&view=<?php echo (isset($_POST['view'])) ? $_POST['view'] : ''; ?>&type=<?php echo (isset($_POST['type'])) ? $_POST['type'] : ''; ?>&company=<?php echo (isset($_POST['company'])) ? $_POST['company'] : ''; ?>&product=<?php echo (isset($_POST['product'])) ? $_POST['product'] : ''; ?>&mrp=<?php echo (isset($_POST['mrp'])) ? $_POST['mrp'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
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
                                  <?php if(isset($_POST['view']) && $_POST['view'] == 'detail'){ ?>
                                    <th>Voucher Date</th>
                                    <th>Voucher No</th>
                                  <?php } ?>
                                  <th><?php echo ((isset($view) && $view == 'summary') && (isset($type) && $type == 'company_wise')) ? 'Company' : 'Item'; ?> Name</th>
                                  <th class="text-right">Qty.</th>
                                  <th class="text-right">Amount</th>
                              </tr> 
                            </thead>
                            <tbody>
                              <?php $total_amount = 0; $total_qty = 0; ?>
                              <?php if(!empty($searchdata)){ ?>
                                <?php foreach ($searchdata as $key => $value) { ?>
                                  <tr>
                                    <td><?php echo $key+1; ?></td>
                                    <?php if(isset($_POST['view']) && $_POST['view'] == 'detail'){ ?>
                                      <td><?php echo (isset($value['vouchar_date']) && $value['vouchar_date'] != '' && $value['vouchar_date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['vouchar_date'])) : ''; ?></td>
                                      <td><?php echo (isset($value['voucher_no'])) ? $value['voucher_no'] : ''; ?></td>
                                    <?php } ?>
                                    <td><?php echo (isset($value['product_name'])) ? $value['product_name'] : ''; ?></td>
                                    <td class="text-right">
                                      <?php
                                        $qty = (isset($value['qty']) && $value['qty'] != '') ? $value['qty'] : '';
                                        $total_qty += $qty;
                                        echo $qty;
                                      ?>
                                    </td>
                                    <td class="text-right">
                                      <?php
                                        $amount = (isset($value['amount']) && $value['amount'] != '') ? $value['amount'] : '';
                                        $total_amount += $amount;
                                        echo amount_format(number_format($amount, 2, '.', ''));
                                      ?>
                                    </td>
                                  </tr>
                                <?php } ?>
                              <?php } ?>
                            </tbody>
                            <tfoot>
                              <tr style="background-color: #EFEFEF;">
                                <th colspan="<?php echo (isset($_POST['view']) && $_POST['view'] == 'detail') ? 4 : 2; ?>" class="text-center"><strong>Total</strong></th>
                                <th class="text-right">
                                  <?php echo (isset($total_qty)) ? $total_qty : 0; ?>
                                </th>
                                <th class="text-right">
                                  <?php echo (isset($total_amount)) ? amount_format(number_format($total_amount, 2, '.', '')) : 0; ?>
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
<script src="js/custom/vendor-purchase-report.js"></script>

 <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
</body>


</html>
