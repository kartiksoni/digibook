<?php $title = "Product Type Master"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php include('include/permission.php'); ?>
<?php 
if(isset($_GET['id'])){
  $id = $_GET['id'];
  $editQry = "SELECT * FROM `trading_account` WHERE id='".$id."' ORDER BY id DESC LIMIT 1";
  $edit = mysqli_query($conn,$editQry);
  $edit = mysqli_fetch_assoc($edit);
  /*echo"<pre>";
  print_r($edit);exit;*/
}


if(isset($_POST['submit'])){

  $user_id = $_SESSION['auth']['id'];
  $account_type = $_POST['account_type'];
  $financial_year = $_POST['financial_year'];
  $opening_balance = $_POST['opening_balance'];
  $closing_balance = $_POST['closing_balance'];  
  $status = $_POST['status'];

  $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : NULL;
  $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : NULL;
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
  $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;

  $insQry = "INSERT INTO `trading_account` (`admin_id`,`owner_id`,`pharmacy_id`,`financial_id`,`account_type`,`financial_year`, `opening_balance`, `closing_balance`, `status`, `created_at`, `created_by`) VALUES ('".$admin_id."','".$owner_id."','".$pharmacy_id."','".$financial_id."', '".$account_type."', '".$financial_year."', '".$opening_balance."','".$closing_balance."', '".$status."', '".date('Y-m-d H:i:s')."', '".$user_id."')"; 

  $queryInsert = mysqli_query($conn,$insQry);

    if($queryInsert){
      $_SESSION['msg']['success'] = "Trading account added successfully.";
      header('location:trading-account.php');exit;
    }else{
      $_SESSION['msg']['fail'] = "Trading account added Failed.";
      header('location:trading-account.php');exit;
    }
}



if(isset($_POST['edit'])){
 
  $user_id = $_SESSION['auth']['id'];
  $account_type = $_POST['account_type'];
  $financial_year = $_POST['financial_year'];
  $opening_balance = $_POST['opening_balance'];
  $closing_balance = $_POST['closing_balance'];  
  $status = $_POST['status'];
  
  $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : NULL;
  $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : NULL;
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
  $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;

  $updateQry = "UPDATE `trading_account` SET `admin_id`='".$admin_id."',`owner_id`='".$owner_id."',`pharmacy_id`='".$pharmacy_id."',`financial_id`='".$financial_id."',`account_type`='".$account_type."',`financial_year`='".$financial_year."',`opening_balance`='".$opening_balance."',`closing_balance`='".$closing_balance."',`status`='".$status."',`updated_at`='".date('Y-m-d H:i:s')."',`updated_by`='".$user_id."' WHERE id='".$_GET['id']."'"; 
  $updateInsert = mysqli_query($conn,$updateQry);

  if($updateInsert){
    $_SESSION['msg']['success'] = "Trading account Updated successfully.";
    header('location:trading-account.php');exit;
  }else{
    $_SESSION['msg']['fail'] = "Trading account Not Updated.";
    header('location:trading-account.php');exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trading Account</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/iconfonts/puse-icons-feather/feather.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
  <link rel="stylesheet" href="css/parsley.css">
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
            <!-- Financial Year Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Trading Account</h4>
                  <hr class="alert-dark">
                  <br>
                  <form id="commentForm" class="" method="post" autocomplete="off">

                        <div class="form-group row">
                            <div class="col-12 col-md-3">
                              <label for="exampleInputName1">Account Type<span class="text-danger">*</span></label>
                              <select name="account_type"  class="js-example-basic-single" id="ac_type" style="width:100%" required=""  data-parsley-errors-container="#error-type">
                                    <option value="">Select</option>
                                    <option <?php if(isset($edit['account_type']) && $edit['account_type'] == 'FIFO'){echo "selected";}?> value="FIFO" >FIFO</option>
                                    <option <?php if(isset($edit['account_type']) && $edit['account_type'] == 'LIFO'){echo "selected";}?> value="LIFO" >LIFO</option>
                                    <option <?php if(isset($edit['account_type']) && $edit['account_type'] == 'NONE'){echo "selected";}?> value="NONE" >NONE</option>
                              </select>
                              <div id = "error-type"></div>
                            </div>
                            <div class="col-12 col-md-3">
                              <label for="exampleInputName1">Financial Year<span class="text-danger">*</span></label>
                              <select name="financial_year"  class="js-example-basic-single" id="financial_year" style="width:100%" required="" data-parsley-errors-container="#error-fy">
                                    <option value="">Select</option>
                              <?php  
                                
                                $financial = "SELECT * FROM `financial` where status = 1 AND pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."'";
                                $datefinancial = mysqli_query($conn,$financial);
                                 while ($rowofvender = mysqli_fetch_array($datefinancial)) {
                                    ?>
                                    <option <?php if(isset($edit['financial_year']) && $edit['financial_year'] == $rowofvender['id']){echo "selected";} ?> value="<?php echo $rowofvender['id']; ?>"><?php echo $rowofvender['f_name']; ?></option>
                                    <?php
                                  }
                                ?> 
                              </select>
                              <div id="error-fy"></div>
                            </div>
                            <div class="col-12 col-md-3">
                              <label for="opening_balance">Opening Balance<span class="text-danger">*</span></label>
                              <input type="text" required class="form-control onlynumber" name="opening_balance" id="opening_balance" data-parsley-type="number" placeholder="Product Type" value="<?php echo (isset($edit['opening_balance'])) ? $edit['opening_balance'] : '0'; ?>" required="">
                            </div>
                            <div class="col-12 col-md-3">
                              <label for="closing_balance">Closing Balance<span class="text-danger">*</span></label>
                              <input type="text" required class="form-control onlynumber" name="closing_balance" id="closing_balance" data-parsley-type="number"  placeholder="Product Type" value="<?php echo (isset($edit['closing_balance'])) ? $edit['closing_balance'] : '0'; ?>" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 col-md-4">
                                <label for="exampleInputName1">Status</label>
                                <div class="row no-gutters">
                                    <div class="col">
                                        <div class="form-radio">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" <?php if(isset($_GET['id'])){if(isset($edit['status']) && $edit['status'] == "1"){echo "checked";}  }else{echo"checked";} ?>>
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                  
                                    <div class="col">
                                        <div class="form-radio">
                                            <label class="form-check-label">
                                                <input type="radio" <?php if(isset($edit['status']) && $edit['status'] == "0"){echo "checked";} ?> class="form-check-input" name="status" id="optionsRadios2" value="0">
                                                Deactive
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      
                   
                      <br>
                      
                      <a href="view-trading-account.php" class="btn btn-light">Back</a>
                      <?php 
                      if(isset($_GET['id'])){
                        ?>
                      <button name="edit" type="submit" class="btn btn-success mr-2">Edit</button>
                        <?php
                      }else{
                      ?>
                      <button name="submit" type="submit" class="btn btn-success mr-2">Submit</button>
                      <?php } ?>
                      
                    
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

  <script src="js/parsley.min.js"></script>
    <script type="text/javascript">
      $('form').parsley();
    </script>
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>
 
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('.datatable').DataTable();
  </script>
  
  <!-- change status js -->
  <script src="js/custom/statusupdate.js"></script>
   <script src="js/custom/onlynumber.js"></script>
   
   
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <!-- End custom js for this page-->
</body>


</html>
