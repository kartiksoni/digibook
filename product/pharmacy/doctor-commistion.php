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
  <title>Digibooks | Doctor Commistion</title>
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
            <?php include('include/flash.php'); ?>
            <span id="errormsg"></span>

            <div class="row">
             
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Doctor Commistion Report</h4><hr class="alert-dark">
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

                        <div class="col-12 col-md-3">
                          <label>Select Doctor<span class="text-danger">*</span></label>
                          <select class="js-example-basic-single" style="width:100%" name="doctor" id="customer" data-parsley-errors-container="#error-customer" required> 
                              <option value="">Please select</option>
                              <?php 
                                $getAllCustomerQ = "SELECT id, name, personal_title FROM doctor_profile WHERE status=1 AND pharmacy_id = '".$pharmacy_id."' AND commission != 0 ORDER BY name";
                                $getAllCustomerR = mysqli_query($conn, $getAllCustomerQ);
                              ?>
                              <?php if($getAllCustomerR && mysqli_num_rows($getAllCustomerR) > 0){ ?>
                                <?php while ($getAllCustomerRow = mysqli_fetch_array($getAllCustomerR)) { ?>
                                  <option value="<?php echo $getAllCustomerRow['id']; ?>" <?php echo (isset($_POST['doctor']) && $_POST['doctor'] == $getAllCustomerRow['id']) ? 'selected' : ''; ?> ><?php if($getAllCustomerRow['personal_title'] != ''){ echo $getAllCustomerRow['personal_title'].". ".$getAllCustomerRow['name']; } else{ echo $getAllCustomerRow['name']; } ?></option>
                                <?php } ?>
                              <?php } ?>
                          </select>
                          <span id="error-customer"></span>
                        </div>
                    
                        
                        <div class="col-12 col-md-5 col-sm-12">
                          <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                          <?php if(isset($_POST['search'])){ ?>
                            <a href="doctor-commistion-print.php?from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>&doctor=<?php echo (isset($_POST['doctor'])) ? $_POST['doctor'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
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
                $doctor = $_POST['doctor'];
                $doctorqry = "SELECT tb.id as tb_id, tb.invoice_date, doctor_profile.commission, SUM((tbd.totalamount)-((tbd.totalamount*tbd.gst)/(tbd.gst+100))) as taxable_amount FROM `tax_billing` tb INNER JOIN tax_billing_details tbd ON tb.id = tbd.tax_bill_id INNER JOIN doctor_profile ON doctor_profile.id = tb.doctor WHERE tb.doctor = '".$doctor."' AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."' AND tb.pharmacy_id = '".$pharmacy_id."' GROUP BY tbd.tax_bill_id";
                $doctorrun = mysqli_query($conn, $doctorqry);
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
                                  <th class="text-center">Sr. No</th>
                                  <th class="text-center">Bill Date</th>
                                  <th class="text-center">Bill Amount</th>
                                  <th class="text-center">Commission Amount</th> 
                              </tr> 
                            </thead>
                            <tbody>
                              <?php 
                              $total_amount = 0;
                              $i = 1;
                              while($doctor_data = mysqli_fetch_assoc($doctorrun)){
                              ?>
                                <tr>
                                <td class="text-center"><?php echo $i; ?></td>
                                <td class="text-center"><?php echo date('d-m-Y', strtotime(str_replace("/", "-", $doctor_data['invoice_date']))); ?></td>
                                <td class="text-center"><?php echo amount_format(number_format(abs($doctor_data['taxable_amount']), 2, '.', '')); ?></td>
                                <?php $commission = $doctor_data['taxable_amount'] * $doctor_data['commission'] / 100 ; ?>
                                <td class="text-center">
                                  <?php
                                    if(isset($commission)){
                                      echo number_format($commission, 2, '.', '');
                                      $total_amount += $commission;
                                    }
                                    ?>
                                </td>
                                </tr>
                              <?php $i++; } ?>
                            </tbody>
                            <tfoot>
                            <tr style="background-color: #EFEFEF;">
                               <th colspan="3" class="text-center">Total</th>
                              <th class="text-center">
                                <?php
                                echo number_format($total_amount, 2, '.', '');
                                ?>
                              </th>
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
<script src="js/custom/item-code.js"></script>
  <!-- End custom js for this page-->
 
</body>


</html>
