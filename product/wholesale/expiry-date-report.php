<?php $title = "Item Registration Report"; ?>
<?php include('include/usertypecheck.php');?>
<?php //include('include/permission.php'); ?>
<?php 
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';

  if(isset($_POST['search'])){
    $expiry_date = (isset($_POST['expiry_date']) && $_POST['expiry_date'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['expiry_date']))) : '';
    $company_by = (isset($_POST['company_by'])) ? $_POST['company_by'] : '';
    
    $searchdata = getexpirydatereport($expiry_date , $company_by);
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Expiry Date Report</title>
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
            <?php include('include/stock_header.php');?>
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Expiry Date Report</h4><hr class="alert-dark">
                    <form form class="forms-sample" method="POST" autocomplete="off">
                      <div class="form-group row">

                        <div class="col-12 col-md-2">
                            <label for="expiry_date">Expiry Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker" name="expiry_date" id="expiry_date" placeholder="DD/MM/YYYY" value="<?php echo (isset($_POST['expiry_date'])) ? $_POST['expiry_date'] : date('d/m/Y'); ?>" required>
                        </div>

                        <div class="col-12 col-md-3">
                          <label for="company_by">Company By <span class="text-danger">*</span></label>
                          <div class="row no-gutters">
                              <div class="col">
                                  <div class="form-radio">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input company_by" name="company_by" value="1" checked <?php echo (isset($_POST['company_by']) && $_POST['company_by'] == '1') ? 'checked' : ''; ?> data-parsley-multiple="company_by" required data-parsley-errors-container="#error-company_by">
                                      All
                                      <i class="input-helper"></i>
                                    </label>
                                  </div>
                              </div>
                              <div class="col">
                                  <div class="form-radio">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input company_by" name="company_by" value="0" <?php echo (isset($_POST['company_by']) && $_POST['company_by'] == '0') ? 'checked' : ''; ?> data-parsley-multiple="company_by" required data-parsley-errors-container="#error-company_by">
                                      Company
                                      <i class="input-helper"></i>
                                    </label>
                                  </div>
                              </div>
                          </div>
                          <span id="error-company_by"></span>
                        </div>

                        <div class="col-12 col-md-2 companyd" <?php if(isset($_POST['company_by']) && $_POST['company_by'] == '0') { } else{ ?> style="display: none;" <?php } ?>>
                          <label for="item">Company<span class="text-danger">*</span></label>
                          <select class="js-example-basic-single" name="company" id="company" style="width:100%" required data-parsley-errors-container="#error-item"> 
                            <option value="">Select Company</option>
                            <?php
                                $query = "SELECT * FROM `company_master` WHERE pharmacy_id = '".$pharmacy_id."'";
                                $result = mysqli_query($conn,$query);
                                while($allcompany = mysqli_fetch_array($result)){
                            ?>
                            <option <?php echo (isset($_POST['company']) && $_POST['company'] ==$allcompany['id']) ? 'selected': ''; ?> value="<?php echo $allcompany['id']; ?>" ><?php echo $allcompany['name']; ?></option>
                          <?php } ?>
                          </select>
                          <span id="error-item"></span>
                        </div>

                        <div class="col-12 col-md-2">
                        <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                        <?php if(isset($searchdata)){ ?>
                          <a href="expiry-date-report-print.php?expiry_date=<?php echo (isset($_POST['expiry_date'])) ? $_POST['expiry_date'] : ''; ?>&company_by=<?php echo (isset($_POST['company_by'])) ? $_POST['company_by'] : ''; ?>&company=<?php echo (isset($_POST['company'])) ? $_POST['company'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
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
                                <th>Sr.No</th>
                                <th>Product Name</th>
                                <?php if(isset($_POST['company_by']) && $_POST['company_by'] == '0'){ ?>
                                  <th>Company Name</th>
                                <?php } ?>
                                <th>Ex.Date</th>
                                <th>Batch No</th>
                                <th>Stock</th>
                              </tr> 
                            </thead>
                            <tbody>
                              <?php
                                if(!empty($searchdata)){
                                  foreach($searchdata as $key => $value){
                                ?>
                              <tr>
                                <td><?php echo $key+1; ?></td>
                                <td><?php echo (isset($value['product_name'])) ? $value['product_name'] : '-';  ?></td>
                                <?php if(isset($_POST['company_by']) && $_POST['company_by'] == '0'){ ?>
                                  <td><?php echo (isset($value['name'])) ? $value['name'] : '-';  ?></td>
                                <?php } ?>
                                <td><?php echo (isset($value['ex_date']) && $value['ex_date'] != '' && $value['ex_date'] != '0000-00-00') ? date('d/m/Y', strtotime($value['ex_date'])) : '-'; ?></td>
                                <td><?php echo (isset($value['batch_no'])) ? $value['batch_no'] : '-';  ?></td>
                                <td><?php echo (isset($value['stock'])) ? $value['stock'] : '-';  ?></td>
                              </tr>
                              <?php } } ?>
                                
                            </tbody>
                            <tfoot>
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
  <script src="js/data-table.js"></script> 
  <script>
     $('.datatable').DataTable( {
        fixedHeader: {
            header: true,
            footer: true
        }
    } );
  </script>
  <script>
    $('.datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy',
      autoclose : true
    });
 </script>
  <!-- script for custom validation -->
<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
  $.listen('parsley:field:validated', function(fieldInstance){
        if (fieldInstance.$element.is(":hidden")) {
            // hide the message wrapper
            fieldInstance._ui.$errorsWrapper.css('display', 'none');
            // set validation result to true
            fieldInstance.validationResult = true;
            return true;
        }
    });
</script>
<script src="js/custom/onlynumber.js"></script>
<script src="js/custom/expiry-date-report.js"></script>
</body>


</html>
