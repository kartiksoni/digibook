<?php 
$title = "Journal Voucher Register";
include('include/usertypecheck.php');
include('include/permission.php');

  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';

  if(isset($_POST['search'])){
    $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : '';
    $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';
    $searchdata = JournalVoucherRegister($from, $to);
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Journal Voucher Register</title>
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
             <?php include "include/account_menus_header.php"; ?>
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Journal Voucher Register</h4><hr class="alert-dark">
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

                        <div class="col-12 col-md-8 col-sm-12">
                          <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                          <?php if(isset($searchdata)){ ?>
                            <a href="journal-voucher-register-print.php?from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
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
                          <table width="100%" cellspacing="10" cellpadding="10" border="1">
                            <thead>
                              <tr>
                                  <th colspan="2">Credit</th>
                                  <th colspan="2">Debit</th>
                              </tr>
                              <tr>
                                  <th width="15%" class="text-right pr-15">Amount</th>
                                  <th width="35%" class="pl-15">Narration</th>
                                  <th width="15%" class="text-right pr-15">Amount</th>
                                  <th width="35%" class="pl-15">Narration</th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr>
                                   <td colspan="2" style="vertical-align: top;">
                                       <table width="100%" cellspacing="10" cellpadding="10">
                                           <?php if(isset($searchdata['credit'])){ ?>
                                                <?php foreach($searchdata['credit'] as $key => $value){ ?>
                                                    <tr>
                                                       <td width="30%" class="text-right pr-15">
                                                           <?php echo (isset($value['amount']) && $value['amount'] != '') ? amount_format(number_format($value['amount'], 2, '.', '')) : 0.00; ?>
                                                       </td>
                                                       <td width="70%" class="pl-15">
                                                           <?php echo (isset($value['narration'])) ? $value['narration'] : ''; ?>
                                                       </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                       </table>
                                   </td>
                                   <td colspan="2" style="vertical-align: top;">
                                       <table width="100%" cellspacing="10" cellpadding="10">
                                           <?php if(isset($searchdata['debit'])){ ?>
                                                <?php foreach($searchdata['debit'] as $key => $value){ ?>
                                                    <tr>
                                                       <td width="30%" class="text-right pr-15">
                                                           <?php echo (isset($value['amount']) && $value['amount'] != '') ? amount_format(number_format($value['amount'], 2, '.', '')) : 0.00; ?>
                                                       </td>
                                                       <td width="70%" class="pl-15">
                                                           <?php echo (isset($value['narration'])) ? $value['narration'] : ''; ?>
                                                       </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                       </table>
                                   </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <?php 
                                        $totalCredit = (isset($searchdata['credit']) && !empty($searchdata['credit'])) ? array_sum(array_column($searchdata['credit'], 'amount')) : 0;
                                        $totalDebit = (isset($searchdata['debit']) && !empty($searchdata['debit'])) ? array_sum(array_column($searchdata['debit'], 'amount')) : 0;
                                    ?>
                                <tr>
                                    <th class="text-right pr-15"><?php echo (isset($totalCredit) && $totalCredit != '') ? amount_format(number_format($totalCredit, 2, '.', '')) : 0.00; ?></th>
                                    <th></th>
                                    <th style="border-right: 0px;" class="text-right pr-15"><?php echo (isset($totalDebit) && $totalDebit != '') ? amount_format(number_format($totalDebit, 2, '.', '')) : 0.00 ?></th>
                                    <th></th>
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

 
 <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <!-- End custom js for this page-->
</body>


</html>
