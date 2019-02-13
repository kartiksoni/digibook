<?php $title = "Notification Master";?>
<?php include('include/usertypecheck.php');?>
<?php //include('include/permission.php');?>

<?php 
  
  $user_id = $_SESSION['auth']['id'];
  $editqry = "SELECT * FROM owner_notification WHERE created_by = '".$user_id."' LIMIT 1";
  $editrun = mysqli_query($conn, $editqry);
  $data = mysqli_fetch_assoc($editrun);

  if(isset($_POST['submit'])){
    $user_id = $_SESSION['auth']['id'];
    $month = $_POST['month'];
    $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : '';
    $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : '';
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
    $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : 'NULL';

    $addqry = "INSERT INTO `owner_notification`(`owner_id`, `admin_id`, `pharmacy_id`, `financial_id`, `owner_reminder`, `created_at`, `created_by`) VALUES ('".$owner_id."', '".$admin_id."', '".$pharmacy_id."', '".$financial_id."','".$month."', '".date('Y-m-d H:i:s')."', '".$user_id."')";
    $run = mysqli_query($conn, $addqry);

    if($run){
      $_SESSION['msg']['success'] = 'Notification Added Successfully.';
      header('location:owner-notification.php');exit;
    }else{
      $_SESSION['msg']['fail'] = 'Notification Added Fail.';
      header('location:owner_notification.php');exit;
    }
  }

  if(isset($_POST['edit'])){
    $user_id = $_SESSION['auth']['id'];
    $month = $_POST['month'];

    $updateqry = "UPDATE `owner_notification` SET `owner_reminder`= '".$month."', `updated_at`= '".date('Y-m-d H:i:s')."', `updated_by`= '".$user_id."' WHERE id = '".$data['id']."'";
    $updaterun = mysqli_query($conn, $updateqry);

    if($updaterun){
      $_SESSION['msg']['success'] = 'Notification Updated Successfully.';
      header('location:owner-notification.php');exit;
    }else{
      $_SESSION['msg']['fail'] = 'Notification Updated Fail.';
      header('location:owner-notification.php');exit;
    }
  }
?>

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Notification Master</title>
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
            
       
            
            <!-- Financial Year Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Notification Master</h4>
                  <hr class="alert-dark">
                  <br>
                  <form id="commentForm" class="" method="post" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-12 col-md-5">
                       <label for="exampleInputName1">Notification Reminder Month<span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" name="month" style="width:100%" required="" data-parsley-errors-container="#error-container"> 
                          <option value="">Select Month</option>
                          <?php 
                            for($m = 1; $m <= 12; $m++){
                            
                          ?>
                          <option value="<?php echo $m;?>"<?php if(isset($data['owner_reminder']) && $data['owner_reminder'] == $m){echo "selected";}?>><?php echo $m;?> </option>
                          <?php }  ?>
                        </select>
                        <span id="error-container"></span>        
                      </div>
                    </div>                      
                   
                      <br>
                      
                      <a href="" class="btn btn-light">Cancel</a>
                      <?php 
                      if(isset($data['owner_reminder'])){
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
  <script src="js/custom/onlynumber.js"></script>
  
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <!-- End custom js for this page-->
</body>


</html>
