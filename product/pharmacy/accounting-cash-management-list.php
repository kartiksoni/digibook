<?php include('include/usertypecheck.php'); ?>
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


<!-- Table ------------------------------------------------------------------------------------------------------>
<?php 

    $accountqry = "select * from accounting_cash_management";
    $accountrun = mysqli_query($conn, $accountqry);
?>
<div class="col mt-3">
      <h4 class="card-title">Accounting Cash Management List</h4>
      <hr class="alert-dark">
        <div class="row">
          <div class="col-12">
            <div class="table-responsive">
              <table id="order-listing" class="table datatable">
              <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Payment Type</th>
                    <th>Voucher No</th>
                    <th>Voucher Date</th>
                    <th>Amount</th>
                    <th>Credit_Debit</th>
                    <th>Action</th>
                </tr>
              </thead>
              <tbody>

              <!-- Row Starts --> 

              <?php
              if($accountrun){
                  $count = 0;
                  while($accountdata = mysqli_fetch_assoc($accountrun)){
                      $count++;
              ?>  
              <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $accountdata['payment_type']; ?></td>
                    <td><?php echo $accountdata['voucher_no']; ?></td>
                    <td><?php echo $accountdata['voucher_date']; ?></td>
                    <td><?php echo $accountdata['amount']; ?></td>
                    <td><?php echo $accountdata['credit_debit']; ?></td>
                    <td> 
                    <a class="btn  btn-behance p-2" href="accounting-cash-management.php?id=<?php echo $accountdata['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
              </tr><!-- End Row -->
              <?php } } ?>
            </tbody>
            </table>
            </div>
            </div>        
          </div>
        </div>
</div> 
                    <hr>    
                       




<!-- partial:partials/_footer.php -->
<?php include "include/footer.php"?>
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



<!-- Custom js for this page Datatables-->
<script src="js/data-table.js"></script>
<script>
  	 $('.datatable').DataTable();
</script>

<script>
  	 $('#order-listing').DataTable();
</script>

<script>
  	 $('#order-listing2').DataTable();
  </script>
  
</body>
</html>
