<?php $title = "Notification Master";?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php');?>
<?php
    $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
    $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
    $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
    $user_id = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : '';
    $date = date('Y-m-d H:i:s');
    
    if(isset($_POST['submit'])){
        $customer_reminder = (isset($_POST['customer_reminder']) && $_POST['customer_reminder'] != '') ? $_POST['customer_reminder'] : 0;
        $vender_reminder = (isset($_POST['vender_reminder']) && $_POST['vender_reminder'] != '') ? $_POST['vender_reminder'] : 0;
      
        $existQ = "SELECT id FROM notification_master WHERE pharmacy_id = '".$pharmacy_id."'";
        $existR = mysqli_query($conn, $existQ);
        if($existR && mysqli_num_rows($existR) > 0){
            $existRow = mysqli_fetch_assoc($existR);
            $updateQ = "UPDATE notification_master SET customer_reminder = '".$customer_reminder."', vender_reminder = '".$vender_reminder."', modified = '".$date."', modifiedby = '".$user_id."' WHERE id = '".$existRow['id']."'";
            $updateR = mysqli_query($conn, $updateQ);
            if($updateR){
                $_SESSION['msg']['success'] = "Notification Reminder Update Successfully.";
            }else{
                $_SESSION['msg']['fail'] = "Notification Reminder Update Fail!";
            }
        }else{
            $insertQ = "INSERT INTO notification_master SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', customer_reminder = '".$customer_reminder."', vender_reminder = '".$vender_reminder."', created = '".$date."', createdby = '".$user_id."'";
            $insertR = mysqli_query($conn, $insertQ);
            if($insertR){
                $_SESSION['msg']['success'] = "Notification Reminder Added Successfully.";
            }else{
                $_SESSION['msg']['fail'] = "Notification Reminder Added Fail!";
            }
        }
        header('location:notification-master.php');exit;
    }
    $getReminderQ = "SELECT id,customer_reminder,vender_reminder FROM notification_master WHERE pharmacy_id = '".$pharmacy_id."'";
    $getReminderR = mysqli_query($conn, $getReminderQ);
    if($getReminderR && mysqli_num_rows($getReminderR) > 0){
        $editData = mysqli_fetch_assoc($getReminderR);
    }
?>

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Notification Master</title>
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
                        <h4 class="card-title">Notification Master</h4><hr class="alert-dark"><br>
                        <form  method="post" autocomplete="off">
                            <div class="form-group row">
                                <div class="col-12 col-md-4">
                                  <label for="customer_reminder">Customer Reminder Days <span class="text-danger">*</span></label>
                                  <input data-parsley-type="number" type="text" class="form-control onlynumber" name="customer_reminder" id="customer_reminder" placeholder="Customer Reminder Days" value="<?php echo (isset($editData['customer_reminder'])) ? $editData['customer_reminder'] : ''; ?>" required="">
                                </div>
                                <div class="col-12 col-md-4">
                                  <label for="vender_reminder">Vender Reminder Days <span class="text-danger">*</span></label>
                                  <input data-parsley-type="number" type="text" class="form-control onlynumber" name="vender_reminder" id="vender_reminder" placeholder="Vender Reminder Days" value="<?php echo isset($editData['vender_reminder']) ? $editData['vender_reminder'] : ''; ?>" required="">
                                </div>
                            </div>
                            <a href="configuration.php" class="btn btn-light">Back</a>
                            <button name="submit" type="submit" class="btn btn-success mr-2">Submit</button>
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
