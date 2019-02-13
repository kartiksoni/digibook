<?php
include('include/config.php');

//check for reset token
//$reset_token = $_GET['reset_token'];
if(isset($_GET['reset_token'])){
  $reset_token = $_GET['reset_token'];
}
else{
  $reset_token = 0;
} 
//$reset_token = $_GET['reset_token'];

if( isset($_POST['submit']) ){

  //$reset_token = $_GET['reset_token'];
  $token = $_POST['reset_token'];

    if(!empty($token) && $token != NULL){
        $password = $_POST['password'];
        $encrypt_password = md5($password);
        //check token is valid

        $check_qry = "SELECT id FROM users WHERE password_reset = '1' AND reset_token = '".$token."'";
        $check_run = mysqli_query($conn, $check_qry);
        $check_data = mysqli_fetch_assoc($check_run);
       
        if($check_data){

                $edit_password = "UPDATE users SET password = '".$encrypt_password."',  password_reset = '0', reset_token = NULL WHERE reset_token = '".$token."'";
                $edit_password_run = mysqli_query($conn, $edit_password);
    
                if($edit_password_run){
    
                    $_SESSION['msg']['success'] = "Password Changed Successfully";
                    header('Location: changepassword.php');
                    exit;
                }else{
                    $_SESSION['msg']['fail'] = "Something went wrong. Try again later";
                }
    
        }
        else{
            $_SESSION['msg']['fail'] = "Invalid Reset Token";
        }

    }
    else{
      //$_SESSION['msg']['fail'] = "Invalid Reset Token";
        die("Invalid Request");
    }
   
   
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Change Password</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/iconfonts/puse-icons-feather/feather.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
  <!-- endinject -->
  
  <!-- parsely validation css  -->
  <link rel="stylesheet" href="css/parsley.css">
  <!-- End plugin css for this page -->
  
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->

  <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth login-full-bg">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto">
          <?php include('include/flash.php'); ?>
          <span id="errormsg"></span>
            <div class="row">
              <div class="col-lg-12">
                <div class="auth-form-dark text-left p-5">
                  <h2>Change Password</h2>
                  <form method="post" class="pt-5" action="">
                    <div class="form-group">
                      <label for="password">Password</label>
                      <input type="password" name="password" class="form-control" id="password" placeholder="Password" required="">
                    </div>
                    <div class="form-group">
                      <label for="confirm_password">Confirm Password</label>
                      <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm password" data-parsley-equalto='#password' required="">
                    </div>
                    <input type="hidden" name="reset_token" value="<?php echo $reset_token;?>">
                    <div class="mt-5">
                      <button type="submit" name="submit" class="btn btn-block btn-primary btn-lg font-weight-medium">Submit</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
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

  <script>
    /*$(document).ready(function(){
      $("#confirm_password").change(function(){
        var confirm = $(this).val();
        var password = $('#password').val();
        if(confirm != password){
          //alert("password Not Matched");
          $("#password").formError("Password Not Matched");
        }
      })
    }); */
  </script>  
    <!-- parsely validation js -->
    <script src="js/parsley.js"></script>
    <script src="js/form-validation.js"></script>
</body>


<!-- Mirrored from www.urbanui.com/pearl-admin/pages/samples/register-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 04 Jul 2018 08:41:24 GMT -->
</html>
