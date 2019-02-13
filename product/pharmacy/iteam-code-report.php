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
  <title>Digibooks | Item Code Report</title>
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
                    <h4 class="card-title">Item Code Report</h4><hr class="alert-dark">
                    <form class="forms-sample" method="POST" autocomplete="off">
                      <div class="form-group row">

                        <!-- <div class="col-12 col-md-2">
                            <label for="from">From</label>
                            <input type="text" class="form-control datepicker" name="from" id="from" placeholder="DD/MM/YYYY" value="<?php echo (isset($_POST['from'])) ? $_POST['from'] : date('d/m/Y'); ?>" required>
                        </div> -->

                        <!-- <div class="col-12 col-md-2">
                            <label for="to">To</label>
                            <input type="text" class="form-control datepicker" name="to" id="to" placeholder="DD/MM/YYYY" value="<?php echo (isset($_POST['to'])) ? $_POST['to'] : date('d/m/Y'); ?>" required>
                        </div> -->
                        <div class="col-12 col-md-3">
                          <label for="iteam_code">Iteam Code</label>
                          <select name="iteam_code_id" id="iteam_code_id" class="js-example-basic-single" style="width:100%">
                                <option value="">Select Iteam Code</option>
                                <?php 
                                $query = "SELECT id,CONCAT(company_code,' - ',product_name) as product_name1,company_code,product_name FROM `product_master` WHERE pharmacy_id='".$pharmacy_id."' GROUP BY product_name,company_code ORDER BY `id` ASC";
                                $result = mysqli_query($conn,$query);
                                while($row = mysqli_fetch_assoc($result)){
                                  ?>
                                  <option <?php echo (isset($_POST['iteam_code_id']) && $_POST['iteam_code_id'] == $row['id']) ? 'selected' : '';  ?> value="<?php echo $row['id']; ?>" data-code="<?php echo $row['company_code']; ?>" data-name="<?php echo $row['product_name'] ?>"><?php echo $row['product_name1']; ?></option>
                                  <?php
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" name="code-company" id="code-company" />
                        <input type="hidden" name="name-product" id="name-product" />

                       

                        <div class="col-12 col-md-5 col-sm-12">
                          <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                        </div>

                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <?php if(isset($_POST['search'])){ 
                $iteam_code_id = $_POST['iteam_code_id'];
                $code_company = $_POST['code-company'];
                $name_product = $_POST['name-product'];
                $iteam_codeQry = "SELECT * FROM `product_master` WHERE product_name = '".$name_product."' AND pharmacy_id ='".$pharmacy_id."' AND company_code='".$code_company."'";
                $iteam_codeR = mysqli_query($conn,$iteam_codeQry);
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
                                  <th width="7%">Sr. No</th>
                                  <th width="8%">Iteam Code</th>
                                  <th class="text-center">Iteam Name</th>
                                  <th class="text-center">MFG. Company</th> 
                                  <th class="text-center">Generic Name</th>
                                  <th class="text-center">Batch No.</th>
                                  <th width="8%">Opening Qty</th>
                                  <th class="text-center" width="12%">MRP</th>
                                  <th class="text-center">Current Stock</th>
                              </tr> 
                            </thead>
                            <tbody>
                              <?php 
                              $i = 1;
                              while($iteam_data = mysqli_fetch_assoc($iteam_codeR)){
                              ?>
                                <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $iteam_data['product_code']; ?></td>
                                <td class="text-center"><?php echo $iteam_data['product_name']; ?></td>
                                <td class="text-center"><?php echo $iteam_data['mfg_company']; ?></td>
                                <td class="text-center"><?php echo $iteam_data['generic_name']; ?></td>
                                <td class="text-center"><?php echo $iteam_data['batch_no']; ?></td>
                                <?php 
                                if(empty($iteam_data['opening_qty'])){
                                    $iteam_data['opening_qty'] = "0";
                                }
                                ?>
                                <td class="text-right"><?php echo (isset($iteam_data['opening_qty'])) ? amount_format(number_format($iteam_data['opening_qty'], 2, '.', '')) : ''; ?></td>
                                <td class="text-right"><?php echo (isset($iteam_data['mrp'])) ? amount_format(number_format($iteam_data['mrp'], 2, '.', '')) : ''; ?></td>
                                <?php 
                                $a[] = $iteam_data['id'];
                                $geAllProduct = getAllProductWithCurrentStock('','',0,$a);
                                ?>
                                <td class="text-right"><?php echo amount_format(number_format($geAllProduct[0]['currentstock'], 2, '.', ''));  ?></td>
                                </tr>
                              <?php $i++; } ?>
                            </tbody>
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
