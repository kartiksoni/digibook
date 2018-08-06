<?php include('include/usertypecheck.php'); ?>
<!DOCTYPE html>
<html lang="en">
<?php 

  $id = $_GET['id'];
  $editQry = "SELECT * FROM `notification_master`ORDER BY id DESC LIMIT 1";
  $edit = mysqli_query($conn,$editQry);
  $edit = mysqli_fetch_assoc($edit);
 


if(isset($_POST['submit'])){
  $user_id = $_SESSION['auth']['id'];
  $customer_reminder = $_POST['customer_reminder'];
  $vender_reminder = $_POST['vender_reminder'];

  $insQry = "INSERT INTO `notification_master` (`customer_reminder`, `vender_reminder`, `created_at`, `cretaed_by`) VALUES ('".$customer_reminder."', '".$vender_reminder."', '".date('Y-m-d H:i:s')."', '".$user_id."')";
  $queryInsert = mysqli_query($conn,$insQry);
  if($queryInsert){
    $_SESSION['msg']['success'] = "Notification Master Added Successfully.";
    header('location:notification-master.php');exit;
  }else{
    $_SESSION['msg']['fail'] = "Notification Master Not Added.";
    header('location:notification-master.php');exit;
  }
}



if(isset($_POST['edit'])){
  $user_id = $_SESSION['auth']['id'];
  $customer_reminder = $_POST['customer_reminder'];
  $vender_reminder = $_POST['vender_reminder'];
  $editQry = "SELECT * FROM `notification_master`ORDER BY id DESC LIMIT 1";
  $edit = mysqli_query($conn,$editQry);
  $edit = mysqli_fetch_assoc($edit);

  $updateQry = "UPDATE `notification_master` SET `customer_reminder`='".$customer_reminder."',`vender_reminder`='".$vender_reminder."',`updated_at`='".date('Y-m-d H:i:s')."',`updated_by`='".$user_id."' WHERE id='".$edit['id']."'";
  
  $updateInsert = mysqli_query($conn,$updateQry);

  if($updateInsert){
    $_SESSION['msg']['success'] = "Notification Master Updated Successfully.";
    header('location:notification-master.php');exit;
  }else{
    $_SESSION['msg']['fail'] = "Notification Master Updated Not Updated.";
    header('location:notification-master.php');exit;
  }
}
?>

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
          <?php include('include/flash.php'); ?>
          <div class="row">
            
       
            
            <!-- Financial Year Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Notification Master</h4>
                  <hr class="alert-dark">
                  <br>
                  <form id="commentForm" class="" method="post" action="">
                    <div class="form-group row">
                        <div class="col-12 col-md-4">
                          <label for="customer_reminder">Customer Reminder Days</label>
                          <input data-parsley-type="number" type="text" class="form-control" name="customer_reminder" id="customer_reminder" placeholder="Customer Reminder Days" value="<?php echo $edit['customer_reminder']; ?>" required="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 col-md-4">
                          <label for="vender_reminder">Vender Reminder Days</label>
                          <input data-parsley-type="number" type="text" class="form-control" name="vender_reminder" id="vender_reminder" placeholder="Vender Reminder Days" value="<?php echo $edit['vender_reminder']; ?>" required="">
                        </div>
                    </div>
                      
                   
                      <br>
                      
                      <a href="" class="btn btn-light">Cancel</a>
                      <?php 
                      if(isset($edit['customer_reminder'])){
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
  
 <script>
    $('#datepicker-popup1').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy'
    });
 </script>
 
 <script>
    $('#datepicker-popup2').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy'
    });
 </script>
 
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('#order-listing2').DataTable();
  </script>
  
  <script>
     $('#order-listing1').DataTable();
  </script>
  
  <!-- End custom js for this page-->
  <?php include('include/usertypecheck.php'); ?>
</body>


</html>
