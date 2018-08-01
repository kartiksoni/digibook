<!DOCTYPE html>
<html lang="en">
<?php 
  include("include/config.php");
  if($_POST){
    $email = (isset($_POST['email'])) ? $_POST['email'] : '';
    $password = (isset($_POST['password'])) ? $_POST['password'] : '';

    if($email != '' && $password != ''){
        $query = " SELECT  * FROM users WHERE email = '".$email."' AND password = '".md5($password)."' AND status=1";
        $result = mysqli_query($conn,$query);
        if($result){
            if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_array($result);
                $_SESSION['auth'] = $row ;
                $_SESSION['msg']['success'] = "<b>Welcome!</b> Login successfully.";
                //header('Location:dashboard.php');exit;
                if($_SESSION['auth']['type'] != '' && !empty($_SESSION['auth']['type'])){
                    if($_SESSION['auth']['type'] == "PHARMACY"){
                      echo'<script>window.location="pharmacy/index.php";</script>';
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
              <h2>Login</h2>
              <h4 class="font-weight-light">Hello! let's get started</h4>

              <form class="pt-5" method="post" action="" autocomplete="off">
                <div class="form-group">
                  <label for="exampleInputEmail1">Username</label>
                  <input type="email" class="form-control" name="email" id="exampleInputEmail1" placeholder="Username" autocomplete="off">
                  <i class="mdi mdi-account"></i>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Password</label>
                  <input type="password" class="form-control" name="password" id="exampleInputPassword1" placeholder="Password" autocomplete="off">
                  <i class="mdi mdi-eye"></i>
                </div>
                <div class="mt-5">
                  <button class="btn btn-block btn-warning btn-lg font-weight-medium" type="submit">Login</button>
                  <!-- <a class="btn btn-block btn-warning btn-lg font-weight-medium" href="index.html">Login</a> -->
                </div>
                <div class="mt-3 text-center">
                  <a href="#" class="auth-link text-white">Forgot password?</a>
                </div>
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
