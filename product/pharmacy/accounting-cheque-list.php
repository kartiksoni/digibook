<?php include('include/usertypecheck.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks | View Cheque</title>
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
            <div class="row">
                <?php include "include/transaction_header.php"; ?>
                <?php 
                    $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                    $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;
                    $listqry = "SELECT ac.*, lm.name FROM accounting_cheque ac INNER JOIN ledger_master lm ON ac.bank_name = lm.id WHERE ac.pharmacy_id = '".$p_id."' AND ac.financial_id = '".$financial_id."' ORDER BY id DESC";
                                   
                  
                    $listrun = mysqli_query($conn, $listqry);
                   
                ?>
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                        <!--<a href="accounting-cheque.php" class="btn btn-success p-2 pull-right" title="Add Cheque"><i class="mdi mdi-plus-circle-outline"></i>Add Cheque</a>-->
                        <h4 class="card-title">Accounting Cheque List</h4>
                        <hr class="alert-dark">
                        <br>
                        <div class="col">
                        <div class="row">
                          <div class="col-12">
                            <div class="table-responsive">
                              <table id="order-listing" class="table datatable">
                              <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Voucher No</th>
                                    <th>Bank Name</th>
                                    <th>Voucher Type</th>
                                    <th>Credit/Debit</th>
                                    <th>Cheque No</th>
                                    <th>Cheque Date</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>
                
                              <!-- Row Starts --> 
                
                              <?php
                                
                                if($listrun){
                                    $count = 0;
                                  while($listdata = mysqli_fetch_assoc($listrun)){
                                    $count++;
                              ?>  
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $listdata['voucher_no']; ?></td>
                                    <td><?php echo $listdata['bank_name']; ?></td>
                                    <td><?php echo $listdata['voucher_type']; ?></td>
                                    <td><?php echo $listdata['credit_debit']; ?></td>
                                    <td><?php echo $listdata['cheque_no']; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime(str_replace('/', '-', $listdata['cheque_date']))); ?></td>
                                    <td class="text-right"><?php echo (isset($listdata['amount']) && $listdata['amount'] != '') ? amount_format(number_format($listdata['amount'], 2, '.', '')) : ''; ?></td>
                                    <td> 
                                    <a class="btn  btn-behance p-2" href="accounting-cheque.php?id=<?php echo $listdata['id']; ?>"title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                </tr><!-- End Row -->
                                <?php } }?>
                            </tbody>
                            </table>
                            </div>
                            </div>        
                          </div>
                        </div>
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


  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>

<!-- Custom js for this page Datatables-->
<script src="js/data-table.js"></script>
<script>
  	 $('.datatable').DataTable();
</script>
  
</body>
</html>
