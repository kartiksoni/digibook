<?php $title = "Customer Ledger"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>
<?php 
 $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';

if(isset($_POST['search'])){

  $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : ''; 
  $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';
  $transport = (isset($_POST['transport'])) ? $_POST['transport'] : '';
  $searchdata = GetTranportPurchase($from, $to, $transport); 

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Transport Report</title>
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
                  <h4 class="card-title">Transport Report</h4><hr class="alert-dark">
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

                      <div class="col-12 col-md-2">
                        <label for="to">Transport Name</label>
                        <select class="js-example-basic-single" data-parsley-errors-container="#error-transport" required="" style="width:100%" name="transport" id="vendor"> 
                          <option value="">Select Transport</option>
                          <?php 
                          $getTransportQ = "SELECT id, name, t_code FROM transport_master WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 ORDER BY name";

                          $result = mysqli_query($conn,$getTransportQ);
                          while ($TransportR = mysqli_fetch_array($result)) {
                            ?>
                            <option <?php if(isset($_POST['transport'])){if($_POST['transport'] == $TransportR['id']){echo "selected";} }?> value="<?php echo $TransportR['id']; ?>"><?php echo $TransportR['name']; ?></option>
                            <?php
                          }

                          ?>
                        </select>
                        <span id="error-transport"></span>
                      </div>

                      <div class="col-12 col-md-5 col-sm-12">
                        <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                        <?php if(isset($searchdata)){ ?>
                          <a href="transport-report-print.php?from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>&transport=<?php echo (isset($_POST['transport'])) ? $_POST['transport'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
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
                                <th>Vouchar Date</th>
                                <th>Vouchar Number</th> 
                                <th>Vendor</th> 
                                <th>Purchase Type</th>
                                <th>Amount</th>
                                <th>Action</th>
                              </tr> 
                            </thead>
                            <tbody>
                              <?php $total_amount = 0; ?>

                              <?php if(isset($searchdata) && !empty($searchdata)){ ?>
                                <?php foreach ($searchdata as $key => $value) { 
                                ?>
                                  <tr>
                                    <td><?php echo $key+1; ?></td>
                                    <td><?php echo (isset($value['vouchar_date']) && $value['vouchar_date'] != '') ? date('d/m/Y', strtotime($value['vouchar_date'])) : ''; ?></td>

                                    <td><?php echo (isset($value['voucher_no'])) ? $value['voucher_no'] : '';  ?></td>
                                    <td><?php
                                    $vendor = "SELECT name from ledger_master where id ='".$value['vendor']."'";
                                    $vendorR = mysqli_query($conn,$vendor);
                                    $vendorL = mysqli_fetch_assoc($vendorR);

                                    echo $vendorL['name'];
                                    ?>
                                  </td>
                                  <td><?php echo (isset($value['purchase_type'])) ? $value['purchase_type'] : '';  ?></td>
                                  <td class="text-right">
                                    <?php
                                    if(isset($value['total_total']) && $value['total_total'] != ''){
                                      echo  number_format($value['total_total'], 2, '.', '');
                                      $total_amount += $value['total_total'];
                                    }
                                    ?>
                                  </td>


                                  <td>
                                    <a class="btn btn-behance p-2" href="purchase.php?id=<?php echo $value['id'] ?>" title="edit" target="_blank"><i class="fa fa-pencil mr-0"></i></a>
                                  </td>
                                </tr>
                              <?php } ?>
                            <?php } ?>
                          </tbody>
                          <tfoot>
                            <tr style="background-color: #EFEFEF;">
                              <th class="text-center"></th>
                              <th class="text-center"></th>
                              <th class="text-center"></th>
                              <th class="text-center"></th>
                              <th class="text-center"></th>
                              <th class="text-right">
                                <?php
                                echo number_format($total_amount, 2, '.', '');
                                ?>
                              </th>
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
</body>
</html>
