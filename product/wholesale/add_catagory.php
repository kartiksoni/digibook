<?php include('include/usertypecheck.php');
//define('BASE_URL', 'http://localhost/digibook/product/api/api.php' );

 $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : ''; 

if(isset($_GET['id'])){
  $id = $_GET['id'];
  $getQry = "SELECT * FROM `user_rights` WHERE id='".$id."'";
  $get = mysqli_query($conn,$getQry);
  $get = mysqli_fetch_assoc($get);
  
  $get_module = explode(",",$get['module']);
  $get_sub_module = explode(",",$get['sub_module']);

}


if(isset($_POST['submit'])){  
  $admin_id = $_SESSION['auth']['id'];
  $catagory_name = $_POST['catagory_name'];
  $module = implode(",",$_POST['module']);
  $sub_module = implode(",",$_POST['sub_module']);

  

  $user_rightsQry = "SELECT * FROM `user_rights` WHERE admin_id='".$admin_id."'";
  $user_rights = mysqli_query($conn,$user_rightsQry);
  $user_rights_data = mysqli_fetch_assoc($user_rights);
  if(!empty($user_rights_data)){
    $UpdateQty = "UPDATE `user_rights` SET `catagory_name`='".$catagory_name."',`module`='".$module."',`sub_module`='".$sub_module."',`pharmacy_id`='".$pharmacy_id."',`update_at`='".date('Y-m-d H:i:s')."',`updated_by`='".$admin_id."' WHERE admin_id='".$admin_id."'";
    $result = mysqli_query($conn,$UpdateQty);
    if($result){
      $_SESSION['msg']['success'] = 'User Rights Updated Successfully.';
      header('location:view-user-rights.php');exit;
    }else{
      $_SESSION['msg']['fail'] = 'User Rights Updated Failed.';
      header('location:view-user-rights.php');exit;
    }
  }else{
    $inQty = "INSERT INTO `user_rights`(`admin_id`,`pharmacy_id`,`catagory_name`, `module`,`sub_module`,`cretaed_at`, `created_by`) VALUES ('".$admin_id."','".$pharmacy_id."','".$catagory_name."','".$module."','".$sub_module."','".date('Y-m-d H:i:s')."','".$admin_id."')";
    $result = mysqli_query($conn,$inQty);
    if($result){
      $_SESSION['msg']['success'] = 'User Rights Added Successfully.';
      header('location:view-user-rights.php');exit;
    }else{
      $_SESSION['msg']['fail'] = 'User Rights Added Failed.';
      header('location:view-user-rights.php');exit;
    }
  }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks</title>
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
            
         
            
            <!-- Service Master Form -->
            <form action="" method="POST">
              
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="row" style="margin-bottom:  15px;">
                      <div class="col-12 col-md-12">
                        <label for="catagory_name">Catagory Name</label>
                        <input type="text" name="catagory_name" required  class="form-control" id="catagory_name" value="<?php echo (isset($get['catagory_name'])) ? $get['catagory_name'] : ''; ?>" placeholder="Catagory Name">
                      </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" id="module" class="form-check-input check_all">
                                Check All
                              </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                      <?php
                      $module_query = mysqli_query($conn,"SELECT * FROM `module`");
                      while($moduleInfo = mysqli_fetch_assoc($module_query)){
                        if(in_array($moduleInfo['id'], $module_data_array)){
                      ?>
                      <div class="col-sm-6">
                        <div class="card">
                          <div class="card-header bg-success">
                              <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" <?php if(isset($get_module) && in_array($moduleInfo['id'], $get_module)){echo "checked";} ?> id="module<?php echo $moduleInfo['id'];?>" value="<?php echo $moduleInfo['id']; ?>" class="form-check-input ModuleChange" name="module[]">
                                <?php echo $moduleInfo['name']; ?>
                              </label>
                              </div>
                          </div>
                          <div class="card-body">
                            <?php 
                              $module_id = $moduleInfo['id'];
                              $sub_module_query = "SELECT * FROM `sub_module` WHERE `module_id`='".$module_id."' ";
                              $sub_module_query = mysqli_query($conn,$sub_module_query);
                              while($submoduleInfo = mysqli_fetch_assoc($sub_module_query)){
                                if(in_array($submoduleInfo['id'], $sub_module_data_array)){
                            ?>
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" <?php if(isset($get_sub_module) && in_array($submoduleInfo['id'], $get_sub_module)){echo "checked";} ?> value="<?php echo $submoduleInfo['id']; ?>" data-moduleid="<?php echo $moduleInfo['id']; ?>" class="form-check-input SubModuleChange sub_module<?php echo $module_id; ?>" name="sub_module[]">
                                <?php echo $submoduleInfo['name']; ?>
                              </label>
                            </div>
                          <?php } ?>
                          <?php } ?>
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                    <?php } ?>
                  
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer row">
                <div class="col-md-12">
                  <a href="view-user-rights.php">
                  <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Back</button>
                  <button type="submit" name="submit" class="btn btn-success pull-right" id="btn-addpoi">Save</button>
                </div>
              </div>
            </form>
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
  
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>

  <script src="js/parsley.min.js"></script>
    <script type="text/javascript">
      $('form').parsley();
    </script>
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  <!-- change status js -->
  <script src="js/custom/admin_rights.js"></script>
  
  
  <!-- End custom js for this page-->
  
</body>


</html>
