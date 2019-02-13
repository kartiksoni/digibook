<?php $title = "Add Help Desk"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php include('include/permission.php'); ?>

<!DOCTYPE html>
<html lang="en">
<?php 

if(isset($_GET['id'])){
  $id = $_GET['id'];
  $editQry = "SELECT * FROM `help_desk` WHERE pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."'";
  $edit = mysqli_query($conn,$editQry);
  $edit = mysqli_fetch_assoc($edit);
  /*echo"<pre>";
  print_r($edit);exit;*/
}


if(isset($_POST['submit'])){
  /*echo"<pre>";
  print_r($_POST);exit;*/
  $count = count($_POST['product_name']);
  for ($i=0; $i < $count ; $i++) { 
      $product_name = "";
      if(isset($_POST["product_name"][$i])){
          $product_name = $_POST["product_name"][$i];
      }

      $product_id = "";
      if(isset($_POST["product_id"][$i])){
          $product_id = $_POST["product_id"][$i];
      }

      $company = "";
      if(isset($_POST["company"][$i])){
          $company = $_POST["company"][$i];
      }

      $company_id = "";
      if(isset($_POST["company_id"][$i])){
          $company_id = $_POST["company_id"][$i];
      }

      $vendor = "";
      if(isset($_POST["vendor"][$i])){
          $vendor = $_POST["vendor"][$i];
      }

      $vendor_id = "";
      if(isset($_POST["vendor_id"][$i])){
          $vendor_id = $_POST["vendor_id"][$i];
      }

      $c_no = "";
      if(isset($_POST["c_no"][$i])){
          $c_no = $_POST["c_no"][$i];
      }

      $email = "";
      if(isset($_POST["email"][$i])){
          $email = $_POST["email"][$i];
      }

      $city = "";
      if(isset($_POST["city"][$i])){
          $city = $_POST["city"][$i];
      }
      
      $insQry = "INSERT INTO `help_desk`(`pharmacy_id`,`product_name`, `product_id`, `company`, `company_id`, `vendor`, `vendor_id`, `contect_no`, `email`, `city`, `createdBy`, `created_at`) VALUES ('".$_SESSION['auth']['pharmacy_id']."','".$product_name."','".$product_id."','".$company."','".$company_id."','".$vendor."','".$vendor_id."','".$c_no."','".$email."','".$city."','".$_SESSION['auth']['id']."','".date('Y-m-d H:i:s')."')";
      $queryInsert = mysqli_query($conn,$insQry);
    
  }
  if($queryInsert){
    $_SESSION['msg']['success'] = "Data Add successfully.";
    header('location:view-help-desk.php');exit;
  }else{
    $_SESSION['msg']['fail'] = "Data Not Add.";
    header('location:view-help-desk.php');exit;
  }
}



if(isset($_POST['edit'])){
  $product_name = $_POST['product_name'][0];
  $product_id = $_POST['product_id'][0];
  $company = $_POST['company'][0];
  $company_id = $_POST['company_id'][0];
  $vendor = $_POST['vendor'][0];
  $vendor_id = $_POST['vendor_id'][0];
  $c_no = $_POST['c_no'][0];
  $email = $_POST['email'][0];
  $city = $_POST['city'][0];

  $updateQry = "UPDATE `help_desk` SET `product_name`='".$product_name."',`product_id`='".$product_id."',`company`='".$company."',`company_id`='".$company_id."',`vendor`='".$vendor."',`vendor_id`='".$vendor_id."',`contect_no`='".$c_no."',`email`='".$email."',`city`='".$city."',`updatedBy`='".$_SESSION['auth']['id']."',`updated_at`='".date('Y-m-d H:i:s')."' WHERE id='".$_GET['id']."'";
  $updateInsert = mysqli_query($conn,$updateQry);

  if($updateInsert){
    $_SESSION['msg']['success'] = "Data Update successfully.";
    header('location:view-help-desk.php');exit;
  }else{
    $_SESSION['msg']['fail'] = "Data  Not Update.";
    header('location:view-help-desk.php');exit;
  }
}
?>

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Help Desk</title>
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
  <link rel="stylesheet" href="css/parsley.css">
  <link rel="stylesheet" href="css/toggle/style.css">
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
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
            
       
            
            <!-- Financial Year Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Help Desk</h4>
                  <hr class="alert-dark">
                  <br>
                  <form id="commentForm" class="" method="post" autocomplete="off">
                  <div class="bank-details-section">
                    <div class="form-group row">
                        <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Product Name<span class="text-danger">*</span></label>
                          <input type="text" name="product_name[]" required class="form-control product_name" id="product_name" placeholder="Product Name" value="<?php echo (isset($edit['product_name'])) ? $edit['product_name'] : ''; ?>">
                          <input type="hidden" class="product_id" name="product_id[]" id="product_id" value="<?php echo (isset($edit['product_id'])) ? $edit['product_id'] : ''; ?>">
                        </div>
                        
                        
                        <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Comapany<span class="text-danger">*</span></label>
                          <input type="text" name="company[]" required class="form-control company" id="company" placeholder="Comapany" value="<?php echo (isset($edit['company'])) ? $edit['company'] : ''; ?>">
                          <input type="hidden" class="company_id" name="company_id[]" id="company_id" value="<?php echo (isset($edit['company_id'])) ? $edit['company_id'] : ''; ?>">
                        </div>
                        
                        <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Vendor<span class="text-danger">*</span></label>
                          <input type="text" name="vendor[]" required class="form-control vendor" id="vendor" placeholder="Vendor" value="<?php echo (isset($edit['vendor'])) ? $edit['vendor'] : ''; ?>">
                          <input type="hidden" class="vendor_id" name="vendor_id[]" id="vendor_id" value="<?php echo (isset($edit['vendor_id'])) ? $edit['vendor_id'] : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group row">  
                      <div class="col-12 col-md-4">
                      <label for="exampleInputName1">Contact No</label>
                      <input type="text" name="c_no[]" class="form-control onlynumber" id="exampleInputName1" placeholder="Contact No" value="<?php echo (isset($edit['contect_no'])) ? $edit['contect_no'] : ''; ?>">
                      </div>

                      <div class="col-12 col-md-4">
                      <label for="exampleInputName1">Email</label>
                      <input type="text" name="email[]" class="form-control" id="email" placeholder="Email" value="<?php echo (isset($edit['email'])) ? $edit['email'] : ''; ?>">
                      </div>

                      <div class="col-12 col-md-4">
                      <label for="exampleInputName1">City</label>
                      <input type="text" name="city[]" class="form-control" id="city" placeholder="City" value="<?php echo (isset($edit['city'])) ? $edit['city'] : ''; ?>">
                      </div>
                    </div>  
                   </div>
                   <?php 
                   if(!isset($_GET['id'])){
                   ?>
                    <div class="row">
                       <div class="col pt-1">
                        <a class="btn btn-primary btn-sm btn-addmore-bank" style="color:#fff;"><i class="fa fa-plus"></i> Add more</a>
                       </div>   
                    </div>
                  <?php } ?>
                       
                        
                      
                   
                      <br>
                      
                      <a href="view-help-desk.php" class="btn btn-light pull-left">Back</a>
                      <?php 
                      if(isset($_GET['id'])){
                        ?>
                      <button name="edit" type="submit" class="btn btn-success mr-2 pull-right">Edit</button>
                        <?php
                      }else{
                      ?>
                      <button name="submit" type="submit" class="btn btn-success mr-2 pull-right">Submit</button>
                      <?php } ?>
                      
                    
                  </form>
                </div>

              </div>
              <div id="bank_html" class="" style="display: none">
                <div class="content-add-bank">
                    <hr>   
                    <div class="row">
                      <div class="col-md-12">
                        <a class="btn btn-danger btn-sm pull-right btn-remove-bank" style="color:#fff;"><i class="fa fa-plus"></i> Remove</a>
                      </div>
                    </div>

                    <div class="form-group row">
                       <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Product Name<span class="text-danger">*</span></label>
                          <input type="text" name="product_name[]" required class="form-control product_name" id="product_name" placeholder="Product Name" value="<?php echo (isset($bankrow['product_name'])) ? $bankrow['product_name'] : ''; ?>">
                          <input type="hidden" class="product_id" name="product_id[]" id="product_id" value="">
                        </div>
                        
                        <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Comapany<span class="text-danger">*</span></label>
                          <input type="text" name="company[]" required class="form-control company" id="company" placeholder="Comapany" value="<?php echo (isset($bankrow['company'])) ? $bankrow['company'] : ''; ?>">
                          <input type="hidden" class="company_id" name="company_id[]" id="company_id" value="">
                        </div>
                        
                        <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Vendor<span class="text-danger">*</span></label>
                          <input type="text" name="vendor[]" required class="form-control vendor" id="vendor" placeholder="Vendor" value="<?php echo (isset($bankrow['vendor'])) ? $bankrow['vendor'] : ''; ?>">
                          <input type="hidden" class="vendor_id" name="vendor_id[]" id="vendor_id" value="">
                        </div>
                    </div>
                    <div class="form-group row">  
                      <div class="col-12 col-md-4">
                      <label for="exampleInputName1">Contact No</label>
                      <input type="text" name="c_no[]" class="form-control onlynumber" id="exampleInputName1" placeholder="Contact No" value="<?php echo (isset($bankrow['c_no'])) ? $bankrow['c_no'] : ''; ?>">
                      </div>

                      <div class="col-12 col-md-4">
                      <label for="exampleInputName1">Email</label>
                      <input type="text" name="email[]" class="form-control" id="email" placeholder="Email" value="<?php echo (isset($bankrow['email'])) ? $bankrow['email'] : ''; ?>">
                      </div>

                      <div class="col-12 col-md-4">
                      <label for="exampleInputName1">City</label>
                      <input type="text" name="city[]" class="form-control" id="city" placeholder="City" value="<?php echo (isset($bankrow['city'])) ? $bankrow['city'] : ''; ?>">
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
  
  <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>

<script src="js/parsley.min.js"></script>
    <script type="text/javascript">
      $('form').parsley();
    </script>
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>
  
  
 <script>
    $('#datepicker-popup1').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy'
    });
 </script>
 
 <script>
    $('#datepicker-popup2').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy'
    });
 </script>
 
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('#order-listing2').DataTable();
  </script>
  
  <script>
     $('#order-listing1').DataTable();
  </script>
  
  <!-- change status js -->
  <script src="js/custom/statusupdate.js"></script>
  <script src="js/custom/help-desk.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="js/custom/onlynumber.js"></script>
  
  <!-- End custom js for this page-->
</body>


</html>
