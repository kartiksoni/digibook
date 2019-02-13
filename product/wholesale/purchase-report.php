<?php $title = "Round Of Sales Ledger"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>
<?php 
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Purchase Report</title>
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
                    <h4 class="card-title">Purchse Report</h4><hr class="alert-dark">
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

                        <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Select Type</label>
                          <div class="row no-gutters">
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" <?php if(isset($_POST['bill_type']) && $_POST['bill_type'] =="avg_price"){echo "checked";} ?> checked class="form-check-input bill_type" name="bill_type" value="avg_price">
                                     Avg Price
                                  </label>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" <?php if(isset($_POST['bill_type']) && $_POST['bill_type'] =="original_price"){echo "checked";} ?> class="form-check-input bill_type" name="bill_type" value="original_price">
                                    Original Price
                                </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" <?php if(isset($_POST['bill_type']) && $_POST['bill_type'] =="last_price"){echo "checked";} ?> class="form-check-input bill_type" name="bill_type" value="last_price">
                                    Last Price
                                </label>
                                </div>
                            </div>
                          </div>
                        </div>

                       

                        <div class="col-12 col-md-4 col-sm-12">
                          <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                          <?php if(isset($_POST['search'])){ ?>
                            <a href="purchase-report-print.php?from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>&bill_type=<?php echo (isset($_POST['bill_type'])) ? $_POST['bill_type'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
                          <?php } ?>
                        </div>


                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <?php if(isset($_POST['search'])){
                $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : ''; 
                $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';
                $bill_type = (isset($_POST['bill_type']) && $_POST['bill_type'] != '') ? $_POST['bill_type'] : '';
                $purchse_data = purchase_report($from,$to,$bill_type);
                /*$iteam_code_id = $_POST['iteam_code_id'];
                $iteam_codeQry = "SELECT * FROM `product_master` WHERE id='".$iteam_code_id."'";
                $iteam_codeR = mysqli_query($conn,$iteam_codeQry);*/
                //$purchase_data = mysqli_fetch_assoc($purchase);
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
                                  <th width="8%">Iteam Code</th>
                                  <th class="text-center">Iteam Name</th>
                                  <th class="text-center">MFG. Company</th> 
                                  <th class="text-center">Generic Name</th>
                                  <th width="8%">Opening Qty</th>
                                  <?php 
                                  if(isset($bill_type) && $bill_type != '' && $bill_type == "avg_price"){
                                  ?>
                                  <th class="text-center" width="12%">Avg Price</th>
                                  <?php } ?>
                                  <?php 
                                  if(isset($bill_type) && $bill_type != '' && $bill_type == "original_price"){
                                  ?>
                                  <th class="text-center" width="12%">Original Price</th>
                                  <?php } ?>
                                  <?php 
                                  if(isset($bill_type) && $bill_type != '' && $bill_type == "last_price"){
                                  ?>
                                  <th class="text-center" width="12%">Last Price</th>
                                  <?php } ?>

                              </tr> 
                            </thead>
                            <tbody>
                              <?php 
                              $i = 1;
                              foreach ($purchse_data as $key => $value) {
                              ?>
                                <tr>
                                  <td><?php echo $key+1; ?></td>
                                  <td><?php echo $value['product_code']; ?></td>
                                  <td class="text-center"><?php echo $value['product_name']; ?></td>
                                  <td class="text-center"><?php echo $value['mfg_company']; ?></td>
                                  <td class="text-center"><?php echo $value['generic_name']; ?></td>
                                  <td class="text-right"><?php echo amount_format(number_format($value['opening_qty'], 2, '.', '')); ?></td>
                                  <?php 
                                  if(isset($bill_type) && $bill_type != '' && $bill_type == "avg_price"){
                                  ?>
                                  <td class="text-right"><?php echo amount_format(number_format($value['A_v'], 2, '.', '')); ?></td>
                                </tr>
                                <?php } ?>
                                  <?php 
                                  if(isset($bill_type) && $bill_type != '' && $bill_type == "original_price"){
                                  ?>
                                  <td class="text-right"><?php echo amount_format(number_format($value['rate'], 2, '.', '')); ?></td>
                                  <?php } ?>
                                  <?php 
                                  if(isset($bill_type) && $bill_type != '' && $bill_type == "last_price"){
                                  ?>
                                  <td class="text-right"><?php echo amount_format(number_format($value['rate'], 2, '.', '')); ?></td>
                                  <?php } ?>
                              <?php $i++; } ?>
                            </tbody>
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
<!--<script src="js/custom/customer_ledger.js"></script>-->
  <!-- End custom js for this page-->
 
</body>


</html>
