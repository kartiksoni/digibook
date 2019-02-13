<?php include('include/usertypecheck.php'); ?>
<?php 
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
  $data = [];
  $query = "SELECT bt.id, bt.amount, bt.voucher_no, bt.voucher_date, bt.payment_mode, lg.name as perticular_name FROM bank_transaction bt LEFT JOIN ledger_master lg ON bt.perticular = lg.id WHERE bt.pharmacy_id = '".$pharmacy_id."' AND bt.financial_id = '".$financial_id."' AND payment_type = 'receipt' ORDER BY bt.id DESC";
  $res = mysqli_query($conn, $query);
  if($res && mysqli_num_rows($res) > 0){
    while ($row = mysqli_fetch_assoc($res)) {
      $data[] = $row;
    }
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks | View Bank Receipt</title>
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
          <span id="errormsg"></span>
          <div class="row">
              <?php include "include/transaction_header.php"; ?>
            
            <!-- Product Master Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <a href="bank-receipt.php" class="btn btn-success p-2 pull-right" title="Add Bank Receipt"><i class="mdi mdi-plus-circle-outline"></i>Add Bank Receipt</a>
                  <h4 class="card-title">View Bank Receipt</h4>
                  <hr class="alert-dark">
                  <br>
                  <div class="col">
                       <div class="row">
                            <div class="col-12">
                              <div class="table-responsive">
                                <table class="table datatable">
                                  <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Voucher Date</th>
                                        <th>Voucher No.</th>
                                        <th>Perticular</th>
                                        <th>Payment Mode</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php if(isset($data) && !empty($data)){ ?>
                                      <?php foreach ($data as $key => $value) { ?>
                                        <tr>
                                          <td><?php echo $key+1; ?></td>
                                          <td><?php echo (isset($value['voucher_date']) && $value['voucher_date'] != '' && $value['voucher_date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['voucher_date'])) : ''; ?></td>
                                          <td><?php echo (isset($value['voucher_no'])) ? $value['voucher_no'] : ''; ?></td>
                                          <td><?php echo (isset($value['perticular_name'])) ? $value['perticular_name'] : ''; ?></td>
                                          <td>
                                            <?php 
                                              $payment_mode = (isset($value['payment_mode'])) ? $value['payment_mode'] : '';
                                              if($payment_mode == 'cheque'){
                                                echo 'Cheque';
                                              }elseif($payment_mode == 'dd'){
                                                echo 'DD';
                                              }elseif($payment_mode == 'net_banking'){
                                                echo 'Net Banking';
                                              }elseif($payment_mode == 'credit_debit_card'){
                                                echo 'Credit/Debit Card';
                                              }elseif($payment_mode == 'other'){
                                                echo 'Other';
                                              }
                                            ?>
                                          </td>
                                          <td><?php echo (isset($value['amount'])) ? amount_format(number_format($value['amount'], 2, '.', '')) : ''; ?></td>
                                          <td>
                                            <a class="btn btn-behance p-2" href="bank-receipt.php?id=<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                            <a href="javascript:void(0);" class="btn btn-danger p-2 delete" title="Delete" data-id="<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>" data-action="deleteBankTransaction"><i class="fa fa-trash-o mr-0"></i></a>
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
  
   
  <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <script>
     $('.datatable').DataTable();
  </script>
  <script src="js/custom/delete.js"></script>
  <!-- End custom js for this page-->
</body>


</html>
