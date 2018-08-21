<?php include('include/usertypecheck.php');
define('BASE_URL', 'http://localhost/digibook/product/api/api.php' );
if(isset($_GET['id'])){
  $id = $_GET['id'];
  $url= BASE_URL."?action=edit_user&id=".$id."";  
  $edit_data = file_get_contents($url);
  $edit = json_decode($edit_data,true);
}


if(isset($_POST['submit'])){
  $id = $_REQUEST['user_id'];
  $pharmacy_id = $_REQUEST['pharmacy_id'];
  $owner_id = $_SESSION['auth']['id'];
  $module = implode(",",$_POST['module']);
  $sub_module = implode(",",$_POST['sub_module']);

  $admin_rightsQry = "SELECT * FROM `admin_rights` WHERE owner_id='".$owner_id."'";
  $admin_rights = mysqli_query($conn,$admin_rightsQry);
  $admin_rights_data = mysqli_fetch_assoc($admin_rights);
  if(!empty($admin_rights_data)){
    echo "update";exit;
  }else{
    echo "insert";exit;
  }
}

if(isset($_POST['edit'])){
  $pharmacy_id = $_REQUEST['pharmacy_id'];
  $id = $_REQUEST['id'];
  $user_name = $_POST['user_name'];
  $email = $_POST['email'];
  $mobile = $_POST['mobile'];
  $password = md5($_POST['password']);
  $status = $_POST['status'];

  $url = BASE_URL."?action=edit_user_data&name=$user_name&user_name=$user_name&email=$email&mobile=$mobile&password=$password&status=$status&pharmacy_id=$pharmacy_id&id=$id";
  $data = file_get_contents($url);

  if($data){

    $_SESSION['msg']['success'] = "Admin Update successfully.";
    header('location:user.php?pharmacy_id='.$pharmacy_id.'');exit;

  }else{

    $_SESSION['msg']['success'] = "Admin Year Not Update.";
    header('location:user.php?pharmacy_id='.$pharmacy_id.'');exit;

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
          <?php include('include/flash.php'); ?>
          <span id="errormsg"></span>
          <div class="row">
            
         
            
            <!-- Service Master Form -->
            <form action="" method="POST">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <?php
                      $module_query = mysqli_query($conn,"SELECT * FROM `module`");
                      while($moduleInfo = mysqli_fetch_assoc($module_query)){
                      ?>
                      <div class="col-sm-6">
                        <div class="card">
                          <div class="card-header bg-success">
                              <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" id="module<?php echo $moduleInfo['id'];?>" value="<?php echo $moduleInfo['id']; ?>" class="form-check-input ModuleChange" name="module[]">
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
                            ?>
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" value="<?php echo $submoduleInfo['id']; ?>" data-moduleid="<?php echo $moduleInfo['id']; ?>" class="form-check-input SubModuleChange sub_module<?php echo $module_id; ?>" name="sub_module[]">
                                <?php echo $submoduleInfo['name']; ?>
                              </label>
                            </div>
                          <?php } ?>
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                  
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer row">
                <div class="col-md-12">
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
  
  <!-- change status js -->
  <script src="js/custom/admin_rights.js"></script>
  
  
  <!-- End custom js for this page-->
  
</body>


</html>
