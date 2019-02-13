<?php $title = "Near Expiry Reminder"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php include('include/permission.php'); ?>

<?php
    $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
    $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
    $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
    $user_id = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : '';
    $date = date('Y-m-d H:i:s');
    
    if(isset($_POST['submit'])){
        $near_by = (isset($_POST['near_by'])) ? $_POST['near_by'] : '';
      
        $existQ = "SELECT id FROM general_settings WHERE pharmacy_id = '".$pharmacy_id."'";
        $existR = mysqli_query($conn, $existQ);
        if($existR && mysqli_num_rows($existR) > 0){
            $existRow = mysqli_fetch_assoc($existR);
            $updateQ = "UPDATE general_settings SET near_by = '".$near_by."', modified = '".$date."', modifiedby = '".$user_id."' WHERE id = '".$existRow['id']."'";
            $updateR = mysqli_query($conn, $updateQ);
            if($updateR){
                $_SESSION['msg']['success'] = "Near By Update Successfully.";
            }else{
                $_SESSION['msg']['fail'] = "Near By Update Fail!";
            }
        }else{
            $insertQ = "INSERT INTO general_settings SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', near_by = '".$near_by."', created = '".$date."', createdby = '".$user_id."'";
            $insertR = mysqli_query($conn, $insertQ);
            if($insertR){
                $_SESSION['msg']['success'] = "Near By Added Successfully.";
            }else{
                $_SESSION['msg']['fail'] = "Near By Added Fail!";
            }
        }
        header('location:near-by.php');exit;
    }
    
    $getSettingQ = "SELECT id,near_by FROM general_settings WHERE pharmacy_id = '".$pharmacy_id."'";
    $getSettingR = mysqli_query($conn, $getSettingQ);
    if($getSettingR && mysqli_num_rows($getSettingR) > 0){
        $editData = mysqli_fetch_assoc($getSettingR);
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Near Expiry Reminder</title>
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
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Near By</h4><hr class="alert-dark"><br>
                            <form  method="post">
                                <div class="form-group row">
                                    <div class="col-12 col-md-4">
                                        <label for="near_by">Near By Month<span class="text-danger">*</span></label>
                                        <select class="js-example-basic-single" name="near_by" style="width:100%" data-parsley-errors-container="#error-month" required>
                                            <option value="">Select Schedule Category</option>
                                            <option <?php echo (isset($editData['near_by']) && $editData['near_by'] == 1) ? 'selected' : ''; ?> value="1">1 Month</option>
                                            <option <?php echo (isset($editData['near_by']) && $editData['near_by'] == 2) ? 'selected' : ''; ?> value="2">2 Month</option>
                                            <option <?php echo (isset($editData['near_by']) && $editData['near_by'] == 3) ? 'selected' : ''; ?> value="3">3 Month</option>
                                            <option <?php echo (isset($editData['near_by']) && $editData['near_by'] == 4) ? 'selected' : ''; ?> value="4">4 Month</option>
                                            <option <?php echo (isset($editData['near_by']) && $editData['near_by'] == 5) ? 'selected' : ''; ?> value="5">5 Month</option>
                                            <option <?php echo (isset($editData['near_by']) && $editData['near_by'] == 6) ? 'selected' : ''; ?> value="6">6 Month</option>
                                            <option <?php echo (isset($editData['near_by']) && $editData['near_by'] == 7) ? 'selected' : ''; ?> value="7">7 Month</option>
                                            <option <?php echo (isset($editData['near_by']) && $editData['near_by'] == 8) ? 'selected' : ''; ?> value="8">8 Month</option>
                                            <option <?php echo (isset($editData['near_by']) && $editData['near_by'] == 9) ? 'selected' : ''; ?> value="9">9 Month</option>
                                            <option <?php echo (isset($editData['near_by']) && $editData['near_by'] == 10) ? 'selected' : ''; ?> value="10">10 Month</option>
                                            <option <?php echo (isset($editData['near_by']) && $editData['near_by'] == 11) ? 'selected' : ''; ?> value="11">11 Month</option>
                                            <option <?php echo (isset($editData['near_by']) && $editData['near_by'] == 12) ? 'selected' : ''; ?> value="12">12 Month</option>
                                        </select>
                                        <span id="error-month"></span>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 20px;">
                                    <div class="col-md-12">
                                        <a href="configuration.php" class="btn btn-light">Back</a>
                                        <button name="submit" type="submit" class="btn btn-success mr-2">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
          </div>
            <!-- content-wrapper ends -->
          
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
        <!-- partial:partials/_footer.php -->
        <?php include "include/footer.php" ?>
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
  </div>
  
  

  
  
  

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
  <script src="js/editorDemo.js"></script
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
 <script src="js/parsley.min.js"></script>
  <script type="text/javascript">
    $('form').parsley();
  </script>
  
  <!-- End custom js for this page-->
</body>


</html>
