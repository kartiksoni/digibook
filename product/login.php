<?php 
  include("include/config.php");
  if($_POST){
    $email = (isset($_POST['email'])) ? $_POST['email'] : '';
    $password = (isset($_POST['password'])) ? $_POST['password'] : '';
    $remember_me = (isset($_POST['remember_me'])) ? $_POST['remember_me'] : '';

    if($email != '' && $password != '')
    {
       if((isset($_REQUEST['ihis_login'])) && $_REQUEST['ihis_login'] == 'IHIS')
        {
          $query = "SELECT  * FROM users WHERE (email = '".$email."' OR username = '".$email."') AND password = '".$password."' AND status=1";
        }else{  
          $query = "SELECT  * FROM users WHERE (email = '".$email."' OR username = '".$email."') AND password = '".md5($password)."' AND status=1";
        }
        $result = mysqli_query($conn,$query);
        if($result){
            if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_assoc($result);
                
                // set pharmacy type as ihis or eclinic or general
                if(isset($row['is_ihis']) && $row['is_ihis'] == 1){
                    $row['pharmacy_user_type'] = 'ihis';
                }elseif(isset($row['is_ec']) && $row['is_ec'] == 1){
                    $row['pharmacy_user_type'] = 'eclinic';
                }else{
                    $row['pharmacy_user_type'] = 'general';
                }
                
                //set cookie if remember me is checked to never expire time = 2147483647
                if(!empty($remember_me)) {
                	setcookie ("digibook_username",$email,2147483647);
                	setcookie ("digibook_password",$password,2147483647);
                } else {
                    if(isset($_COOKIE["digibook_username"])) {
                            setcookie ("digibook_username","",time() - 3600);
                    }
                    if(isset($_COOKIE["digibook_password"])) {
                            setcookie ("digibook_password","",time() - 3600);
                     }
                }
                
                $_SESSION['auth'] = $row ;
                $_SESSION['msg']['success'] = "<b>Welcome!</b> Login successfully.";
                //header('Location:dashboard.php');exit;
                
                //update user online status
				if(isset($row['id']) && $row['id'] != ''){
					mysqli_query($conn, "UPDATE users SET is_online = 1 WHERE id = '".$row['id']."'");
				}
				
                if($_SESSION['auth']['type'] != '' && !empty($_SESSION['auth']['type'])){
                    if($_SESSION['auth']['type'] == "PHARMACY"){
                      echo'<script>window.location="pharmacy/index.php";</script>';
                    }
                    if($_SESSION['auth']['type'] == "PHARMACY_WHOLESALE"){
                      echo'<script>window.location="wholesale/index.php";</script>';
                    }

                    if($_SESSION['auth']['type'] == "IPD"){
                      echo'<script>window.location="ipd/index.php";</script>';
                    }

                    if($_SESSION['auth']['type'] == "FINANCE"){
                      echo'<script>window.location="finance/index.php";</script>';
                    }

                    if($_SESSION['auth']['type'] == "GENERAL"){
                      echo'<script>window.location="general/index.php";</script>';
                    }
                }else{
                  echo'<script>window.location="index-dashboard.php";</script>';
                }
                
            }else{
                $_SESSION['msg']['fail'] = "Invalid username or password";
            }
        }else{
            $_SESSION['msg']['fail'] = "Somthing want wrong!";
        }
    }else{
        $_SESSION['msg']['fail'] = "All fields are required!";
    }

  }
?>
<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from www.urbanui.com/pearl-admin/pages/samples/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 04 Jul 2018 08:41:24 GMT -->
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/iconfonts/puse-icons-feather/feather.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  
  <!--custom css for this page-->
  <style>.remember_me_label i{ position: inherit !important;}</style>
  <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth login-full-bg">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto">
            <?php include('include/flash.php'); ?>
            <div class="auth-form-dark text-left p-5">
              <h2 class="text-center">Login</h2>
              <h4 class="font-weight-light text-center">Hello! let's get started</h4>

              <form class="pt-5" method="post" action="" autocomplete="off">
                <div class="form-group">
                  <label for="email">Username</label>
                  <input type="text" class="form-control" name="email" id="exampleInputEmail1" placeholder="Username" autocomplete="off" value="<?php if(isset($_COOKIE["digibook_username"])) { echo $_COOKIE["digibook_username"]; } ?>">
                  <i class="mdi mdi-account"></i>
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" name="password" id="exampleInputPassword1" placeholder="Password" autocomplete="off" value="<?php if(isset($_COOKIE["digibook_password"])) { echo $_COOKIE["digibook_password"]; } ?>">
                  <i class="mdi mdi-eye"></i>
                </div>
                <div class="form-group">
                    <div class="form-check" style="margin-top:30px;">
                      <label class="form-check-label remember_me_label">
                        <input type="checkbox" name="remember_me" class="form-check-input remember_me" <?php if(isset($_COOKIE["digibook_username"])) { ?> checked <?php } ?>>
                        Remember Me
                      </label>
                    </div>
                </div>
                <div class="mt-5">
                  <button class="btn btn-block btn-warning btn-lg font-weight-medium" type="submit">Login</button>
                  <!-- <a class="btn btn-block btn-warning btn-lg font-weight-medium" href="index.html">Login</a> -->
                </div>
                <!--<div class="mt-3 text-center">
                  <a href="forgot-password.php" class="auth-link text-white">Forgot password ? click here</a>
                </div>-->
              </form>

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
</body>


<!-- Mirrored from www.urbanui.com/pearl-admin/pages/samples/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 04 Jul 2018 08:41:24 GMT -->
</html>
