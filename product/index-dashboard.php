<?php 
include('include/config.php');
if(!isset($_SESSION['auth']) && empty($_SESSION['auth'])){
        header('Location:index.php');
        exit;
}
if(!empty($_SESSION['auth']['type'])){
  header('Location:dashboard.php');
        exit;
}
if($_POST){
  $type = (isset($_POST['type'])) ? $_POST['type'] : '';
  $authid = $_SESSION['auth']['id'];
  if($type != '' && $authid != ''){
    $qry = "UPDATE `users` SET `type` = '".strtoupper($type)."' WHERE id = '".$authid."' ";
      $res = mysqli_query($conn, $qry);
      if($res){
        $_SESSION['auth']['type'] = $type;
        $_SESSION['msg']['success'] = "Type update successfully.";
        
        echo'<script>window.location="dashboard.php";</script>';
      }else{
        echo'<script>window.location="dashboard.php";</script>'; 
      }
  }
}
?>
<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from www.urbanui.com/pearl-admin/pages/samples/register-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 04 Jul 2018 08:41:24 GMT -->
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Pearl UI</title>
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
      <div class="content-wrapper d-flex align-items-center auth">
       <div class="col-lg-8 mx-auto">
       
      
        
        
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <div class="container text-center pt-5">
                  
                   <div class="text-center  d-flex align-items-center justify-content-center">
                    <a class="brand-logo" href="#"><img src="images/digi-logo.svg" alt="logo" style="width:250px;" class="mb-3"/></a>
                  </div>
                  <hr>
                    <h4 class="mb-3 mt-5"><strong>Start up your Bussiness today</strong></h4>
                    <p class="w-75 mx-auto mb-5">Choose a plan that suits you the best. we offer 30 Day free trial!!</p>
                    
                    <div class="row">
                     
                      
                      <div class="col-md-3 grid-margin stretch-card ">
                          <a href="index-dashboard-confirm.php?type=pharmacy" class="dashboard-box">
                            <div class="p-3 m-1">
                              <div class="text-center pricing-card-head">
                                <img src="images/dashboard-icon/pharmacy.png" alt="" class="img-md mb-2">
                                <h4 class="mt-2">Pharmacy</h4>
                              </div>
                            </div>
                          </a>  
                      </div>
                      
                      <div class="col-md-3 grid-margin stretch-card ">
                          <a href="index-dashboard-confirm.php?type=IPD" class="dashboard-box">
                            <div class="p-3 m-1">
                              <div class="text-center pricing-card-head">
                                <img src="images/dashboard-icon/ipd.png" alt="" class="img-md mb-2">
                                <h4 class="mt-2">IPD</h4>
                              </div>
                            </div>
                          </a>
                      </div>
                      
                      
                      <div class="col-md-3 grid-margin stretch-card ">
                         <a href="index-dashboard-confirm.php?type=finance" class="dashboard-box">
                            <div class="p-3 m-1">
                              <div class="text-center pricing-card-head">
                                <img src="images/dashboard-icon/finance.png" alt="" class="img-md mb-2">
                                <h4 class="mt-2">Finance</h4>
                              </div>
                            </div>
                          </a>
                      </div>
                      
                      <div class="col-md-3 grid-margin stretch-card ">
                         <a href="index-dashboard-confirm.php?type=general" class="dashboard-box">
                            <div class="p-3 m-1">
                              <div class="text-center pricing-card-head">
                                <img src="images/dashboard-icon/general.png" alt="" class="img-md mb-2">
                                <h4 class="mt-2">General</h4>
                              </div>
                            </div>
                          </a>
                      </div>
                      
                    </div>
                    <form id="form_submit" method="post">
                      <input type="hidden" name="type" id="type">
                    </form>

                    
                    <p class="w-75 mx-auto mb-5 text-danger"><strong>Note : </strong>One time selection, Please select carefully.</p>
                    
                    
                  </div>
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
</body>


<!-- Mirrored from www.urbanui.com/pearl-admin/pages/samples/register-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 04 Jul 2018 08:41:24 GMT -->
</html>


<div class="modal fade show" id="confirm-usertype" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-3" style="display: none; padding-right: 17px;">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header" style="padding: 15px 15px;border-bottom: 1px solid #A9A9A9;">
        <h5 class="modal-title" id="exampleModalLabel-3">Confirm</h5>
      </div>
      <div class="modal-body" style="padding: 15px 15px;">
          Are You sure want to confirm?
      </div>
      <div class="modal-footer text-center" style="padding: 15px 15px;display: block;">
        <button type="button" id="btn-confirmusertype" class="btn btn-success">Ok</button>
        <button type="button" id="btn-notconfirmusertype" class="btn btn-light" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

