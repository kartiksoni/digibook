<?php $title="View Jurnal Voucher"; ?>
<?php include('include/usertypecheck.php');?>
<?php //include('include/permission.php'); ?>
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
          <span id="errormsg"></span>
          <div class="row">
            <?php include "include/transaction_header.php"; ?>
            <!-- Product Master Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    <a href="journal-vouchar.php" class="btn btn-success p-2 pull-right" title="Add Voucher"><i class="mdi mdi-plus-circle-outline"></i>Add Voucher</a>
                    <h4 class="card-title">View Journal Vouchar</h4>
                    <hr class="alert-dark">

                  <!-- <h4 class="card-title">View Journal Vouchar</h4>
                  <hr class="alert-dark">
                  <br> -->
                  <div class="col mt-3">
                       <div class="row">
                            <div class="col-12">
                              <div class="table-responsive">
                                <table class="table datatable">
                                  <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Voucher Date</th>
                                        <th>Voucher No</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                      $data = [];
                                      $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
                                      $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;
                                      $qry = "SELECT jv.id, jv.voucher_date, jv.voucher_no, jv.remarks, SUM(jvd.debit) as total_debit, SUM(jvd.credit) as total_credit, (SUM(jvd.debit) + SUM(jvd.credit)) as total_amount FROM journal_vouchar jv LEFT JOIN journal_vouchar_details jvd ON jv.id = jvd.voucher_id WHERE jv.pharmacy_id = '".$pharmacy_id."' AND jv.financial_id = '".$financial_id."' GROUP BY jvd.voucher_id ORDER BY jv.id DESC";
                                      $res = mysqli_query($conn, $qry);
                                      if($res && mysqli_num_rows($res) > 0){
                                        while ($row = mysqli_fetch_assoc($res)) {
                                          $data[] = $row;
                                        }
                                      }
                                      
                                    ?>
                                    <?php if(isset($data) && !empty($data)){ ?>
                                      <?php foreach ($data as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $key+1;; ?></td>
                                            <td>
                                              <?php echo (isset($value['voucher_date']) && $value['voucher_date'] != '') ? date('d/m/Y',strtotime($value['voucher_date'])) : ''; ?>
                                            </td>
                                            <td>
                                              <?php echo (isset($value['voucher_no'])) ? $value['voucher_no'] : ''; ?>
                                            </td>
                                            <td class="text-right">
                                              <?php echo (isset($value['total_debit'])) ? amount_format(number_format($value['total_debit'], 2, '.', '')) : ''; ?>
                                            </td>
                                            <td class="text-right">
                                              <?php echo (isset($value['total_credit'])) ? amount_format(number_format($value['total_credit'], 2, '.', '')) : ''; ?>
                                            </td>
                                            <td>
                                              <a class="btn btn-behance p-2" href="journal-vouchar.php?id=<?php echo $value['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                              <!--<a class="btn btn-danger p-2" href="view-journal-vouchar.php?id=<?php //echo $value['id']; ?>" title="edit" onclick="return confirm('Are you sure want to delete this record?')"><i class="fa fa-trash-o mr-0"></i></a>-->
                                                <a href="javascript:void(0);" class="btn btn-danger p-2 delete" title="Delete" data-id="<?php echo $value['id']; ?>" data-action="deleteJournalVoucher"><i class="fa fa-trash-o mr-0"></i></a>
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
  
  <script>
     $('.datatable').DataTable();
  </script>
  <!-- End custom js for this page-->
  
  <!-- change status js -->
  <script src="js/custom/statusupdate.js"></script>
  <script src="js/custom/delete.js"></script>
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
</body>


</html>
