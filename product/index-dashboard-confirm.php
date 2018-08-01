<!DOCTYPE html>
<html lang="en">
<?php 
$name = $_GET['type'];
?>
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
        $_SESSION['auth']['type'] = strtoupper($type);
        $_SESSION['msg']['success'] = "Type update successfully.";

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
  }
}
?>

<!-- Mirrored from www.urbanui.com/pearl-admin/pages/samples/register-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 04 Jul 2018 08:41:24 GMT -->
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
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="vendors/iconfonts/font-awesome/css/font-awesome.min.css" />
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
                    
                    <div class="row">
                     
                    <?php 
                    if($name == "pharmacy"){
                    ?>  
                      <div class="col-md-4 col-12 mt-5">
                        <div class="dashboard-box-confirm">
                              <div class="p-3 m-1">
                                <div class="text-center pricing-card-head">
                                  <img src="images/dashboard-icon/pharmacy.png" alt="" class="img-md mb-2">
                                  <h4 class="mt-2">Pharmacy</h4>
                                  
                                </div>
                              </div>
                        </div>   
                      </div>
                      <div class="col-md-8 col-12  grid-margin stretch-card ">
                            <div class="p-3 m-1 text-left">
                              
                                <h4 class="mt-2 mb-3"><strong>Pharmacy</strong></h4>
                                <p class="text-muted">What you will get in this type</p>
                                <p><i class="fa fa-check"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                <p><i class="fa fa-check"></i> Integer ornare sem ac velit congue pellentesque.</p>
                                <p><i class="fa fa-check"></i> Mauris at ipsum non nulla sagittis scelerisque.</p>
                                <p><i class="fa fa-check"></i> Vestibulum egestas eros eu semper luctus.</p>
                                <p><i class="fa fa-check"></i> Nullam imperdiet purus vitae dictum fermentum.</p>
                                
                                
                								<p class="mt-4 text-danger">
                                  <div class="form-check form-check-flat">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input">
                                        I confirm that Once I select the version; i can not change the version in future.
                                      </label>
                                  </div>
                								</p>
                            </div>
                      </div>
                    <?php } ?>

                    <?php 
                    if($name == "IPD"){
                    ?>

                    <div class="col-md-4 col-12 mt-5">
                        <div class="dashboard-box-confirm">
                              <div class="p-3 m-1">
                                <div class="text-center pricing-card-head">
                                  <img src="images/dashboard-icon/ipd.png" alt="" class="img-md mb-2">
                                  <h4 class="mt-2">IPD</h4>
                                  
                                </div>
                              </div>
                        </div>   
                    </div>
                    <div class="col-md-8 col-12  grid-margin stretch-card ">
                            <div class="p-3 m-1 text-left">
                              
                                <h4 class="mt-2 mb-3"><strong>IPD</strong></h4>
                                <p class="text-muted">What you will get in this type</p>
                                <p><i class="fa fa-check"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                <p><i class="fa fa-check"></i> Integer ornare sem ac velit congue pellentesque.</p>
                                <p><i class="fa fa-check"></i> Mauris at ipsum non nulla sagittis scelerisque.</p>
                                <p><i class="fa fa-check"></i> Vestibulum egestas eros eu semper luctus.</p>
                                <p><i class="fa fa-check"></i> Nullam imperdiet purus vitae dictum fermentum.</p>
                                
                                
                                <p class="mt-4 text-danger">
                                  <div class="form-check form-check-flat">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input">
                                        I confirm that Once I select the version; i can not change the version in future.
                                      </label>
                                  </div>
                                </p>
                            </div>
                    </div>

                    <?php } ?>

                    <?php 
                    if($name == "finance"){
                    ?>
                    <div class="col-md-4 col-12 mt-5">
                        <div class="dashboard-box-confirm">
                              <div class="p-3 m-1">
                                <div class="text-center pricing-card-head">
                                  <img src="images/dashboard-icon/finance.png" alt="" class="img-md mb-2">
                                  <h4 class="mt-2">Finance</h4>
                                  
                                </div>
                              </div>
                        </div>   
                    </div>
                    <div class="col-md-8 col-12  grid-margin stretch-card ">
                            <div class="p-3 m-1 text-left">
                              
                                <h4 class="mt-2 mb-3"><strong>Finance</strong></h4>
                                <p class="text-muted">What you will get in this type</p>
                                <p><i class="fa fa-check"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                <p><i class="fa fa-check"></i> Integer ornare sem ac velit congue pellentesque.</p>
                                <p><i class="fa fa-check"></i> Mauris at ipsum non nulla sagittis scelerisque.</p>
                                <p><i class="fa fa-check"></i> Vestibulum egestas eros eu semper luctus.</p>
                                <p><i class="fa fa-check"></i> Nullam imperdiet purus vitae dictum fermentum.</p>
                                
                                
                                <p class="mt-4 text-danger">
                                  <div class="form-check form-check-flat">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input">
                                        I confirm that Once I select the version; i can not change the version in future.
                                      </label>
                                  </div>
                                </p>
                            </div>
                    </div>
                    <?php } ?>

                    <?php if($name == "general"){ ?>
                      <div class="col-md-4 col-12 mt-5">
                        <div class="dashboard-box-confirm">
                              <div class="p-3 m-1">
                                <div class="text-center pricing-card-head">
                                  <img src="images/dashboard-icon/general.png" alt="" class="img-md mb-2">
                                  <h4 class="mt-2">General</h4>
                                  
                                </div>
                              </div>
                        </div>   
                    </div>
                    <div class="col-md-8 col-12  grid-margin stretch-card ">
                            <div class="p-3 m-1 text-left">
                              
                                <h4 class="mt-2 mb-3"><strong>General</strong></h4>
                                <p class="text-muted">What you will get in this type</p>
                                <p><i class="fa fa-check"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                <p><i class="fa fa-check"></i> Integer ornare sem ac velit congue pellentesque.</p>
                                <p><i class="fa fa-check"></i> Mauris at ipsum non nulla sagittis scelerisque.</p>
                                <p><i class="fa fa-check"></i> Vestibulum egestas eros eu semper luctus.</p>
                                <p><i class="fa fa-check"></i> Nullam imperdiet purus vitae dictum fermentum.</p>
                                
                                
                                <p class="mt-4 text-danger">
                                  <div class="form-check form-check-flat">
                                      <label class="form-check-label">
                                        <input type="checkbox" name="checkme" class="form-check-input">
                                        I confirm that Once I select the version; i can not change the version in future.
                                      </label>
                                  </div>
                                </p>
                            </div>
                    </div>
                    <?php } ?>
                      
                      
                      
                      <div class="col-12 col-md-12">
                      <div class="row">
                      	<div class="col-md-6">
                        	<a href="index-dashboard.php" class="btn btn-dark btn-rounded btn-fw float-md-left float-xl-left float-sm-none">Back</a>
                        </div>
                        
                        <div class="col-md-6" id="button-confirm">
                        	<button disabled class="btn btn-success btn-rounded btn-fw float-md-right float-xl-right float-sm-none" id="check-confirm">Confirm</button>
                        </div>
                      </div>  
                      </div>
                      
                      
                    
                      
                    </div>
                    
                    
                    
                    
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
  <form id="form_submit" method="post">
    <input type="hidden" name="type" id="type" value="<?php echo $name; ?>">
  </form>
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
<script type="text/javascript">

  $(".form-check-input").change(function(){
      if($(this).prop('checked') == true){
          //checked
          //$("#check-confirm").show();
          $('#check-confirm').prop('disabled', false);
      }else{
        //not checked
        //$("#check-confirm").hide();
        $('#check-confirm').prop('disabled', true);
      }
  });

  $("#check-confirm").click(function(){
    $('#form_submit').trigger('submit');
  });
  

</script>
