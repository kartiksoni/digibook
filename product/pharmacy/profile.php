<?php include('include/usertypecheck.php');
include('include/config-ihis.php');
$user_id = $_SESSION['auth']['id'];

if(isset($_SESSION['auth']['id'])){
  $url= BASE_URL."?action=edit_user&id=".$user_id."";
  $edit_data = file_get_contents($url);
  $edit = json_decode($edit_data,true);

}

function isValidMd5($md5 ='')
{
    return preg_match('/^[a-f0-9]{32}$/', $md5);
}

if(isset($_POST['update'])){
    $upload_path = "../user_profile";
    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path."/".$_FILES['profile_image']['name'])) {
        $fields  = array();
        $url = BASE_URL."?action=profile";
        $fields = array(
            "id" => $_SESSION['auth']['id'],
            "user_name" => $_POST['user_name'],
            "email" => $_POST['email'],
            "mobile" => $_POST['mobile'],
            "name" => $_FILES['profile_image']['name']
            );
        $fields_string = ''; 
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
        
        //UPDATE PASSWORD IN IHIS DB
          /*if($edit['ihis_user_id']!=0 && $edit['user_type']=="owner")
          {
              if(!isValidMd5($_POST['password'])){
                  $password = md5($_POST['password']);
              }else{
                  $password = $_POST['password'];
              }
              $up_qry = "UPDATE users SET password='".$password."' WHERE id='".$edit['ihis_user_id']."' "; 
              mysqli_query($ihis_conn,$up_qry);
          }*/        
          //END OF IHIS
        
        $url = BASE_URL."?action=profile&$fields_string";

        $data = file_get_contents($url);
       
        if($data){
         $_SESSION['auth']['profile_pic'] = $_FILES['profile_image']['name'];   //set image in session -Shreya 5/1/2019
         
            $_SESSION['msg']['success'] = "Profile Update successfully.";
            header('location:profile.php');exit;
        
        }else{
        
            $_SESSION['msg']['fail'] = "Profile  Not Update.";
            header('location:profile.php');exit;
        
        }
    }else{
        if(empty($_FILES['profile_image']['name'])){
            $fields  = array();
            $url = BASE_URL."?action=profile";
            $fields = array(
                "id" => $_SESSION['auth']['id'],
                "user_name" => $_POST['user_name'],
                "email" => $_POST['email'],
                "mobile" => $_POST['mobile'],
                "name" => $_FILES['profile_image']['name']
                );
            $fields_string = ''; 
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string, '&');
            
            //UPDATE PASSWORD IN IHIS DB
              /*if($edit['ihis_user_id']!=0 && $edit['user_type']=="owner")
              {
                  if(!isValidMd5($_POST['password'])){
                      $password = md5($_POST['password']);
                  }else{
                      $password = $_POST['password'];
                  }
                  $up_qry = "UPDATE users SET password='".$password."' WHERE id='".$edit['ihis_user_id']."' "; 
                  mysqli_query($ihis_conn,$up_qry);
              }*/        
              //END OF IHIS
            
            $url = BASE_URL."?action=profile&$fields_string";
    
            $data = file_get_contents($url);
            
            if($data){
                $_SESSION['msg']['success'] = "Profile Update successfully.";
                header('location:profile.php');exit;
            }else{
       
                $_SESSION['msg']['fail'] = "Profile1 Not Update.";
                header('location:profile.php');exit;
            }
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
  <title>DigiBooks | Profile</title>
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
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    <a href="javascript:void(0);" class="btn btn-success p-2 pull-right btn-changepassword-modal" title="Change Password"><i class="mdi mdi-lock-outline"></i>Change Password</a>
                    <h4 class="card-title">Profile</h4><hr class="alert-dark">
                    
                  <form class="forms-sample" class="" method="post" action="" enctype="multipart/form-data">
                  
                  <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="user_name">User Name<span class="text-danger">*</span></label>
                        <input type="text" required name="user_name" value="<?php echo (isset($edit['username'])) ? $edit['username'] : ''; ?>" class="form-control" id="user_name" placeholder="User Name" readonly>
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="email">Email<span class="text-danger">*</span></label>
                      <input type="text"  name="email" value="<?php echo (isset($edit['email'])) ? $edit['email'] : ''; ?>" class="form-control" id="email" placeholder="Email" required>
                      </div> 

                      <div class="col-12 col-md-4">
                        <label for="email">Mobile<span class="text-danger">*</span></label>
                      <input type="text"  name="mobile" value="<?php echo (isset($edit['mobile'])) ? $edit['mobile'] : ''; ?>" class="form-control" id="mobile" placeholder="Mobile" required>
                      </div> 
                  </div>
                    
                  <!--<div class="form-group row">
                    
                      <div class="col-12 col-md-4">
                        <label for="password">Password<span class="text-danger">*</span></label>
                        <input type="password"  name="password" value="<?php //echo (isset($edit['password'])) ? $edit['password'] : ''; ?>" class="form-control" id="password" placeholder="Password" required>
                      </div>
                        
                  </div>-->
                  <?php $url_img = "http://digibooks.cloud/product/user_profile/".$edit['profile_pic']; ?>
                    <div class="form-group row">
                        <div class="col-md-8">
                            <label for="article_image">Profile Image <span class="text-danger">*</span></label>
                            <input type="file" name="profile_image" id="profile_image" class="dropify"
                                data-allowed-file-extensions="jpg png jpeg"  data-default-file="<?php echo $url_img; ?>"  data-show-remove="false";
                                >
                        </div>
                    </div>
                    <br>
                    <a href="index.php" class="btn btn-light">Back</a>
                      <button name="update" type="submit" class="btn btn-success mr-2">Update</button>
                    
                    
                  </form>
                </div>
              </div>
            </div>
            
          
            
       
            
          </div>
        </div>
        <!-- content-wrapper ends -->
        
        <!-- partial:partials/_footer.php -->
        <?php include "include/footer.php" ?>
        <!--change password modal-->
        <?php include "popup/change-password-model.php" ?>
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
  
 
 
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('.datatable').DataTable();
     $('.datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
    });
  </script>
  <!-- change status js -->
  <script src="js/custom/statusupdate.js"></script>
  <script src="js/custom/profile.js"></script>
   
 <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  
  <!-- End custom js for this page-->
  
</body>


</html>
