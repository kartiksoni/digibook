<?php $title = "Customer Ledger"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibook | GSTR 1 Report</title>
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
                  <h4 class="card-title">GSTR 1 Report</h4><hr class="alert-dark">
                  <form class="forms-sample " method="POST" autocomplete="off" action="">

                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label>Year<span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" style="width:100%" name="year" id="year_id" data-parsley-errors-container="#error-year" required> 
                          <option value="">Select Year</option>
                          <?php 
                          $financial ="SELECT start_date FROM `financial` ORDER BY `start_date` ASC LIMIT 1";
                          $financialR = mysqli_query($conn , $financial);
                          $financialL = mysqli_fetch_assoc($financialR);
                          $year = date("Y",strtotime(str_replace("-","/",$financialL['start_date'])));

                          for($i = $year; $i < $year+5; $i++){
                            echo "<option value='".$i."'>" . $i . "</option>";
                          }
                          ?>
                        </select>
                        <div id="error-year"></div>
                      </div>
                    </div>


                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label>Month<span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" style="width:100%" name="month" id="month_id" data-parsley-errors-container="#error-month" required>
                          <option value="">Select Month</option>
                          <?php
                          $monthArray = range(1, 12);
                          foreach ($monthArray as $month) {
                            $monthPadding = str_pad($month, 2, "0", STR_PAD_LEFT);
                            $fdate = date("F", strtotime("2015-$monthPadding-01"));
                            echo '<option value="'.$fdate.'">'.$fdate.'</option>';
                          }
                          ?>
                        </select>
                        <div id="error-month"></div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-12 col-md-5 col-sm-12">
                        <button type="button" class="btn btn-success mt-30 " id="submit">View Report</button>
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
  <script type="text/javascript">
    $( "#submit" ).click(function( event ) {
      var month = $('#month_id').val();
       var year = $('#year_id').val();
    if(month == ""){
      return false;
    }else  
    if(year == ""){
      return false;
    }else{
      var link = "report_gstr1_details.php?year=" + year + "&month=" + month ; 
      $(location).attr('href',link);
    }
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
