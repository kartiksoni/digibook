<?php $title = "Customer Ledger"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibook | GSTR 3B Report</title>
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
                  <h4 class="card-title">GSTR 3B Report</h4><hr class="alert-dark">
                  <form class="forms-sample" method="GET" autocomplete="off" action="report_gstr3b_details.php">

                    <div class="form-group row">
                        
                        <div class="col-12 col-md-2">
                        <label>Month</label>
                        <select class="form-control" name="month" id="month">
                          <option value="" data-start="<?php echo date('d/m/Y'); ?>" data-end="<?php echo date('d/m/Y'); ?>">Select Month</option>
                          <?php
                            $month = [1 => 'January',2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December']; 
                            foreach ($month as $key => $value) {
                          ?>
                            <?php
                              $first_day_this_month = date('01-'.sprintf("%02d", ($key)).'-Y');
                              $last_day_this_month = date("t-m-Y", strtotime($first_day_this_month));
                            ?>
                            <option data-start="<?php echo date('d/m/Y',strtotime($first_day_this_month)); ?>" data-end="<?php echo date('d/m/Y',strtotime($last_day_this_month)); ?>" value="<?php echo $key; ?>" <?php echo (isset($_POST['month']) && $_POST['month'] == $key) ? 'selected' : ''; ?> ><?php echo $value; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      
                        <div class="col-12 col-md-2">
                            <label for="from">From</label>
                            <input type="text" class="form-control datepicker" name="from" id="from" placeholder="DD/MM/YYYY" value="<?php echo (isset($_POST['from'])) ? $_POST['from'] : date('d/m/Y'); ?>" required>
                        </div>

                        <div class="col-12 col-md-2">
                            <label for="to">To</label>
                            <input type="text" class="form-control datepicker" name="to" id="to" placeholder="DD/MM/YYYY" value="<?php echo (isset($_POST['to'])) ? $_POST['to'] : date('d/m/Y'); ?>" required>
                        </div>
                   
                      <div class="col-12 col-md-5 col-sm-12">
                        <!--<button type="submit" name="submit" class="btn btn-success mt-30">View Report</button>-->
                        <button type="submit" class="btn btn-success mt-30 " id="submit">View Report</button>
                        </div>

                      </div>
                    </form>
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
    

    <!-- Custom js for this page Modal Box-->
    <script src="js/modal-demo.js"></script>

 <script>
    $('.datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy',
      autoclose : true
    });
    
     $('#month').on('change', function() {
        var startdate = $(this).find(':selected').attr('data-start');
        var enddate = $(this).find(':selected').attr('data-end');

        $('#from').attr('value', startdate);
        $('#to').attr('value', enddate);
        $(".datepicker").datepicker();
      });
 </script>
  <!-- script for custom validation -->
  <script src="js/parsley.min.js"></script>
  <script type="text/javascript">
    $('form').parsley();
  </script>
  <script src="js/custom/onlynumber.js"></script>
  <!-- <script src="js/custom/customer_ledger.js"></script> -->
  <!-- End custom js for this page-->


</body>


</html>
