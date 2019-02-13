<?php $title="Sales History"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks | History</title>
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
          <div class="row">
            <?php include "include/sale_header.php"; ?>
            
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                <h4 class="card-title">View Sales History</h4><hr class="alert-dark"><br>
                  <div class="col">
                      <div class="row">
                        <div class="col-12">
                          <table class="table datepicker">
                            <thead>
                              <tr>
                                  <th>Sr No</th>
                                  <th>Date</th>
                                  <th>Customer</th>
                                  <th>Mobile No.</th>
                                  <th>Doctor</th>
                                  <th>Taxable amount</th>
                                  <th>Tax Amount</th>
                                  <th>Inv.total amount</th>
                                  <th style="width: 17%;">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                                $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
                                $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

                                $query = "SELECT tb.id, tb.invoice_date, FORMAT(tb.final_amount, 2) as amount, FORMAT(tb.alltotalamount, 2) as taxable_amount,FORMAT(tb.totaligst+tb.totalcgst+tb.totalsgst, 2) as tax_amount,FORMAT(tb.final_amount, 2) as invoice_total, tb.cancel,lgr.name as customer_name, lgr.mobile as customer_mobile, dp.name as doctor FROM tax_billing tb INNER JOIN ledger_master lgr ON tb.customer_id = lgr.id LEFT JOIN doctor_profile dp ON tb.doctor = dp.id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND tb.financial_id = '".$financial_id."' ORDER BY tb.id DESC";
                                $res = mysqli_query($conn, $query);
                                if($res && mysqli_num_rows($res) > 0){
                                  $count = 1;
                                  while ($row = mysqli_fetch_array($res)){
                              ?>
                                  <tr>
                                      <td><?php echo $count; ?></td>
                                      <td><?php echo (isset($row['invoice_date']) && $row['invoice_date'] != '') ? date('d/m/Y',strtotime($row['invoice_date'])) : ''; ?></td>
                                      <td><?php echo (isset($row['customer_name'])) ? $row['customer_name'] : ''; ?></td>
                                      <td><?php echo (isset($row['customer_mobile'])) ? $row['customer_mobile'] : ''; ?></td>
                                      <td><?php echo (isset($row['doctor'])) ? $row['doctor'] : ''; ?></td>
                                      <td class="text-right"><?php echo (isset($row['taxable_amount'])) ? $row['taxable_amount'] : ''; ?></td>
                                      <td class="text-right"><?php echo (isset($row['tax_amount'])) ? $row['tax_amount'] : ''; ?></td>
                                      <td class="text-right"><?php echo (isset($row['invoice_total'])) ? $row['invoice_total'] : ''; ?></td>
                                      <td class="action">
                                        <?php if(isset($row['cancel']) && $row['cancel'] == 1){ ?>
                                          <a class="btn  btn-behance p-2" href="sales-tax-billing.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-xs pt-2 pb-2"><i class="fa fa-pencil mr-0"></i></a>
                                          <a href="sales-return.php?bill=<?php echo $row['id']; ?>" class="btn btn-primary btn-xs pt-2 pb-2">Return</a>
                                          <button type="button" data-id="<?php echo $row['id']; ?>" class="btn btn-danger btn-xs pt-2 pb-2 btn-cancel-bill">Cancel</button>
                                        <?php }else{ ?>
                                          <a href="javascript:void(0);" class="btn btn-danger btn-xs pt-2 pb-2">Cancelled Bill</a>
                                        <?php } ?>
                                      </td>
                                  </tr>
                              <?php
                                    $count++;
                                  }
                                }
                              ?>
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
  <script src="js/parsley.min.js"></script>
  
  <!-- Custom js for this page Modal Box-->
  <script src="js/modal-demo.js"></script>

 <script src="js/custom/sales-history.js"></script>
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('.datepicker').DataTable();
  </script>
   
 <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <!-- End custom js for this page-->
</body>


</html>
