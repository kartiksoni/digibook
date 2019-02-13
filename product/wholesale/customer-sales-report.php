<?php $title = "Daily Sales Reports"; ?>
<?php include('include/usertypecheck.php');?>
<?php //include('include/permission.php'); ?>
<?php 
$pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
$financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Customer Sales Reports</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/iconfonts/puse-icons-feather/feather.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
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
           <?php include('include/stock_header.php'); ?>
           <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Customer Sales Reports</h4><hr class="alert-dark">
                <form class="forms-sample" method="POST">
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
                      <label for="customer_city">Customer City<span class="text-danger">*</span></label>
                      <select class="form-control" style="width:100%" name="customer_city" id="customer_city" required data-parsley-errors-container="#error-customer-city">
                        <option value="">Select City</option>

                        <?php 
                        $getcity = "SELECT ct.id, ct.name, st.state_code_gst as statecode FROM ledger_master lg INNER JOIN own_cities ct ON lg.city = ct.id LEFT JOIN own_states st ON ct.state_id = st.id WHERE lg.group_id = 10 AND lg.pharmacy_id = '".$pharmacy_id."' GROUP BY lg.city ORDER BY ct.name";
                        $cityrun = mysqli_query($conn, $getcity);
                        if($cityrun && mysqli_num_rows($cityrun) > 0){
                          while($cityrow = mysqli_fetch_assoc($cityrun)){
                            ?>
                            <option value="<?php echo $cityrow['id']; ?>" <?php echo (isset($_POST['customer_city']) && $_POST['customer_city'] == $cityrow['id']) ? 'selected' : ''; ?>><?php echo $cityrow['name']; ?></option>
                          <?php } } ?>  
                        </select>
                        <span id="error-customer-city"></span>
                      </div>


                      <div class="col-12 col-md-2">
                        <label>Customer Name<span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" style="width:100%" name="customer_name" id="customer_name" required data-parsley-errors-container="#error-customer-name"> 
                          <option value="">Please select</option>
                          <?php
                          $query = 'SELECT id, name FROM ledger_master WHERE city = '.$_POST['customer_city'].' AND status=1 AND group_id = 10 AND pharmacy_id = '.$pharmacy_id.' order by name';
                          $customerrun = mysqli_query($conn, $query);
                          if($customerrun && mysqli_num_rows($customerrun) > 0){
                            while($customerdata = mysqli_fetch_assoc($customerrun)){
                              ?>

                              <option value="<?php echo $customerdata['id']; ?>" <?php echo (isset($_POST['customer_name']) && $_POST['customer_name'] == $customerdata['id']) ? 'selected' : ''; ?>><?php echo $customerdata['name']; ?></option>
                            <?php } } ?>
                          </select>
                          <span id="error-customer-name"></span>
                        </div>
                        <?php 
                          $detail = 'checked';
                          $summary = '';
                          if(isset($_POST['view']) && $_POST['view'] == 'detail'){
                            $detail = 'checked';
                          }elseif(isset($_POST['view']) && $_POST['view'] == 'summary'){
                            $summary = 'checked';
                          }
                        ?>
                        <div class="col-12 col-md-2">
                          <label for="view">Select Any One</label>
                          <div class="row no-gutters">
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input summary" name="view" value="detail" data-parsley-multiple="view" <?php echo (isset($detail)) ? $detail : ''; ?> >
                                     Detail
                                  <i class="input-helper"></i></label>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input summary" name="view" value="summary" data-parsley-multiple="view" <?php echo (isset($summary)) ? $summary : ''; ?> >
                                    Summary
                                <i class="input-helper"></i></label>
                                </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <hr/>


                      <div class="form-group row">
                        <div class="col-12 col-md-6">
                          <label for="product_by"></label>
                          <div class="row no-gutters">

                            <div class="col">
                              <div class="form-radio">
                                <label class="form-check-label">
                                  <input type="radio" class="form-check-input product_by" name="radio" value="all" checked <?php if(isset($_POST['radio']) && $_POST['radio'] == 'all'){ echo "checked"; } ?>data-parsley-multiple="product_by">
                                  All
                                  <i class="input-helper"></i>
                                </label>
                              </div>
                            </div>

                            <div class="col">
                              <div class="form-radio">
                                <label class="form-check-label">
                                  <input type="radio" class="form-check-input product_by" name="radio" value="company_wise" <?php if(isset($_POST['radio']) && $_POST['radio'] == 'company_wise'){echo "checked"; }?> data-parsley-multiple="product_by">
                                  Company Wise
                                  <i class="input-helper"></i>
                                </label>
                              </div>
                            </div>

                            <div class="col">
                              <div class="form-radio">
                                <label class="form-check-label">
                                  <input type="radio" class="form-check-input product_by" name="radio" value="single_product" <?php if(isset($_POST['radio']) && $_POST['radio'] == 'single_product'){ echo "checked"; }?> id="single_product" data-parsley-multiple="product_by">
                                  Single Product
                                  <i class="input-helper"></i>
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-12 col-md-2" <?php if(isset($_POST['radio']) && $_POST['radio'] == 'company_wise'){}else{ ?>style="display: none;" <?php } ?> id="company_name">
                          <label>Company Name</label><span class="text-danger">*</span>
                          <select class="js-example-basic-single company_name" style="width:100%" name="company_name" id="customer_name"data-parsley-errors-container="#error-customer" required> 
                            <option value="">Please select</option>
                            <?php
                            $qry = "SELECT id, name FROM company_master WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1";
                            $run = mysqli_query($conn, $qry);

                            while($companydata = mysqli_fetch_assoc($run)){ ?>

                              <option value="<?php echo $companydata['id']; ?>" <?php echo (isset($_POST['company_name']) && $_POST['company_name'] == $companydata['id']) ? 'selected' : '';?>><?php echo $companydata['name']; ?></option>
                            <?php } ?>
                          </select>
                          <span id="error-customer"></span>
                        </div>

                        <div class="col-12 col-md-2" <?php if(isset($_POST['radio']) && $_POST['radio'] == 'single_product'){} else{ ?>style="display: none;" <?php } ?> id="product_name">
                          <label for="from">Product Name</label>
                          <select input type="text" class="js-example-basic-single product-name" style="width:100%" name="product_name" placeholder="Product Name" data-parsley-errors-container="#product-error" required>
                            <option value="">Select Product</option> 
                            <?php
                            $selectproduct = " Select product_name,id from product_master WHERE pharmacy_id = '$pharmacy_id'";
                            if(isset($_POST['mrp']) && $_POST['mrp'] != ''){
                                $selectproduct .= " AND mrp = '".$_POST['mrp']."'";
                            }
                            $selectproduct .= " GROUP BY product_name ORDER BY product_name";
                            $product = mysqli_query($conn,$selectproduct);
                            if($pro=mysqli_num_rows($product)){
                              while($row=mysqli_fetch_assoc($product)){
                                ?>
                              <option value="<?php echo (isset($row['product_name'])) ? $row['product_name'] : ''; ?>"<?php echo (isset($_POST['product_name']) && $_POST['product_name'] == $row['product_name']) ? 'selected' : '';?>><?php echo $row['product_name']; ?></option>
                              <?php } } ?>
                            </select>
                          <i class="fa fa-spin fa-refresh product_name" style="position: absolute; top: 40px; right: 40px; display: none;"></i>
                            <span class="text-danger product-error" id="product-error"></span>
                          </div>


                          <div class="col-12 col-md-2" <?php if(isset($_POST['radio']) && $_POST['radio'] == 'single_product'){} else{ ?>style="display: none;" <?php } ?> id="mrp">
                            <label for="to">Mrp</label>
                            <input type="text" class="form-control mrp" name="mrp" placeholder="Mrp" value="<?php if(isset($_POST['mrp']) && $_POST['mrp'] != ''){ echo $_POST['mrp']; }?>">
                          </div>

                          <div class="col-12 col-md-2 col-sm-12">
                            <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                            <?php if(isset($_POST['search'])){ ?>
                              <a href="customer-sales-report-print.php?from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&view=<?php echo (isset($_POST['view'])) ? $_POST['view'] : '';?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>&search_radio=<?php echo (isset($_POST['radio'])) ? $_POST['radio'] : ''; ?>&company_id=<?php echo (isset($_POST['company_name'])) ? $_POST['company_name'] : '';?>&product_name=<?php echo (isset($_POST['product_name'])) ? $_POST['product_name'] : '';?>&customer_name=<?php echo (isset($_POST['customer_name'])) ? $_POST['customer_name'] : '';?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
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
                  $customer_city = (isset($_POST['customer_city']) && $_POST['customer_city'] != '') ? $_POST['customer_city'] : '';
                  $customer_name = (isset($_POST['customer_name']) && $_POST['customer_name'] != '') ? $_POST['customer_name'] : '';
                  $search_radio = (isset($_POST['radio']) && $_POST['radio'] != '') ? $_POST['radio'] : '';
                  $company_name = (isset($_POST['company_name']) && $_POST['company_name'] != '') ? $_POST['company_name'] : '';
                  $product_name = (isset($_POST['product_name']) && $_POST['product_name'] != '') ? $_POST['product_name'] : '';
                  $view = (isset($_POST['view']) && $_POST['view'] != '') ? $_POST['view'] : '';
                  

                  if ($view == 'detail') {
                      $reportqry = "SELECT tb.invoice_no, tb.customer_id, tb.invoice_date, pm.product_name, tbd.qty, tbd.tax_bill_id, tbd.totalamount FROM tax_billing AS tb INNER JOIN tax_billing_details AS tbd ON tb.id = tbd.tax_bill_id INNER JOIN product_master AS pm ON pm.id = tbd.product_id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND tb.financial_id = '".$financial_id."' AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."' AND tb.customer_id = '".$customer_name."'";

                      if(isset($search_radio) && $search_radio == 'company_wise'){
                        $reportqry .= " AND pm.company_code = '".$company_name."'";
                      }

                      if(isset($search_radio) && $search_radio == 'single_product'){
                        $reportqry .= " AND pm.product_name = '".$product_name."'";
                      }

                      $reportqry .= " ORDER BY tb.invoice_date";
                  }elseif($view == 'summary'){
                      $reportqry = "SELECT  pm.product_name, SUM(tbd.qty) AS qty, SUM(tbd.totalamount) AS totalamount FROM tax_billing AS tb INNER JOIN tax_billing_details AS tbd ON tb.id = tbd.tax_bill_id INNER JOIN product_master AS pm ON pm.id = tbd.product_id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND tb.financial_id = '".$financial_id."' AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."' AND tb.customer_id = '".$customer_name."'";

                      if(isset($search_radio) && $search_radio == 'company_wise'){
                        $reportqry .= " AND pm.company_code = '".$company_name."'";
                      }

                      if(isset($search_radio) && $search_radio == 'single_product'){
                        $reportqry .= " AND pm.product_name = '".$product_name."'";
                      }

                      $reportqry .= " GROUP BY pm.product_name";
                  }
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
                                  <?php if(isset($_POST['view']) && $_POST['view'] == 'detail'){ ?>
                                  <th class="text-center">Bill No</th>
                                  <th class="text-center">Bill Date</th> 
                                  <?php } ?>
                                  <th class="text-center">Item Name</th>
                                  <th class="text-center">Qty</th>
                                  <th class="text-center">Amount</th>
                                </tr> 
                              </thead>
                              <tbody>
                                <?php 
                                if($reportrun){
                                  $count = 1;
                                  $total = 0;
                                  while($data = mysqli_fetch_assoc($reportrun)){ 
                                   ?>
                                   <tr>
                                    <td><?php echo $count; ?></td>
                                    <?php if(isset($_POST['view']) && $_POST['view'] == 'detail'){ ?>
                                    <td class="text-center"><?php echo (isset($data['invoice_no'])) ? $data['invoice_no'] : '';  ?></td>
                                    <td class="text-center"><?php echo (isset($data['invoice_date']) && $data['invoice_date'] != '') ? date('d/m/Y', strtotime($data['invoice_date'])) : ''; ?></td>
                                    <?php } ?>
                                    <td class="text-center"><?php echo (isset($data['product_name'])) ? $data['product_name'] : ''; ?></td>
                                    <td class="text-center"><?php echo (isset($data['qty'])) ? $data['qty'] : ''; ?></td>
                                    <td class="text-center"><?php echo (isset($data['totalamount'])) ? amount_format(number_format($data['totalamount'], 2, '.', '')) : ''; ?></td>
                                    <?php $total += $data['totalamount']; ?>
                                  </tr>
                                  <?php $count++; } ?>
                                <?php } ?>
                              </tbody>
                              <tfoot>
                                <tr style="background-color: #EFEFEF;">
                                  <th colspan="<?php echo (isset($_POST['view']) && $_POST['view'] == 'detail') ? 5 : 3; ?>" class="text-center"><strong>Total / Closing Balance</strong></th>

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
      <script src="js/jquery-ui.js"></script>
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
      $('form').parsley({
        excluded: ':hidden'
      });
    </script>
    <script src="js/custom/onlynumber.js"></script>
    <script src="js/custom/customer-sales-report.js"></script>

  </body>


  </html>
