<?php include('include/usertypecheck.php'); ?>
<?php 
    //FOR SAVE MIN ORDER NOTIFICATION
    addMinQtyNotification();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks</title>
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
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
  
  <link rel="stylesheet" href="css/toggle/style.css">
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
          <div class="row">
            <?php if($_SESSION['auth']['user_type'] != "owner"){ ?>

              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Customer Notification</h4><hr class="alert-dark">
                    <div class="col mt-3">
                      <div class="row">
                        <div class="col-12">
                          <div class="table-responsive">
                            <table class="table datatable">
                              <thead>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Customer</th>
                                    <th>Bill Date</th>
                                    <th>Invoice No.</th>
                                    <th>Bill Amount</th>
                                    <th>Cash Amount</th>
                                    <th>Remaining Amount</th>
                                    <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                  $customerNotification = getCustomerPaymentNotification();
                                ?>
                                 <?php if(isset($customerNotification) && !empty($customerNotification)){?>
                                  <?php foreach ($customerNotification as $key => $value) { ?>
                                      <tr>
                                        <td><?php echo $key+1; ?></td>
                                        <td><?php echo (isset($value['customer']['name'])) ? $value['customer']['name'] : ''; ?></td>
                                        <td><?php echo (isset($value['invoice_date']) && $value['invoice_date'] != '') ? date('d/m/Y',strtotime($value['invoice_date'])) : ''; ?></td>
                                        <td><?php echo (isset($value['invoice_no'])) ? $value['invoice_no'] : ''; ?></td>
                                        <td><?php echo (isset($value['total_bill'])) ? $value['total_bill'] : ''; ?></td>
                                        <td><?php echo (isset($value['total_payment'])) ? $value['total_payment'] : ''; ?></td>
                                        <td><?php echo (isset($value['total_remaining'])) ? $value['total_remaining'] : ''; ?></td>
                                        <td>
                                            <a class="btn  btn-behance p-2" href="sales-tax-billing.php?id=<?php echo $value['id']; ?>" title="Show Bill"><i class="fa fa-eye mr-0"></i></a>
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
                  </div>
                </div>
              </div>


              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Min Reorder</h4><hr class="alert-dark">
                    <div class="col mt-3">
                      <div class="row">
                        <div class="col-12">
                          <div class="table-responsive">
                            <table class="table datatable">
                              <thead>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Product Name</th>
                                    <th>Min Qty</th>
                                    <th>Current Stock</th>
                                    <th>Suggested Order Qty</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                  $minproduct = getAllProductWithCurrentStock();
                                ?>
                                 <?php if(isset($minproduct) && !empty($minproduct)){ $i = 1; ?>
                                  <?php foreach ($minproduct as $key => $value) { ?>
                                    <?php if($value['currentstock'] < $value['min_qty']){ ?>
                                      <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $value['product_name']; ?></td>
                                        <td><?php echo $value['min_qty']; ?></td>
                                        <td><?php echo $value['currentstock']; ?></td>
                                        <td><?php echo $value['min_qty']-$value['currentstock']; ?></td>
                                      </tr>
                                    <?php $i++; } ?>
                                  <?php } ?>
                                <?php } ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
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
  
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('.datatable').DataTable();
  </script>
  <!-- End custom js for this page-->
</body>


</html>
