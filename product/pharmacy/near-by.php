<?php include('include/usertypecheck.php'); ?>
<!DOCTYPE html>
<html lang="en">
<?php 
  $editQry = "SELECT * FROM `general_settings`";
  $edit = mysqli_query($conn,$editQry);
  $edit = mysqli_fetch_assoc($edit);
  
  



if(isset($_POST['submit'])){
  $user_id = $_SESSION['auth']['id'];
  $near_by = $_POST['near_by'];

  $insQry = "INSERT INTO `general_settings` (`user_id`, `near_by`) VALUES ('".$user_id."', '".$near_by."')";
  $queryInsert = mysqli_query($conn,$insQry);
  if($queryInsert){
    $_SESSION['msg']['success'] = "Near By Added Successfully.";
    header('location:near-by.php');exit;
  }else{
    $_SESSION['msg']['fail'] = "Near By Added Failed.";
    header('location:near-by.php');exit;
  }
}



if(isset($_POST['edit'])){


  $user_id = $_SESSION['auth']['id'];
  $near_by = $_POST['near_by'];
  $editQry = "SELECT * FROM `general_settings`";
  $edit = mysqli_query($conn,$editQry);
  $edit = mysqli_fetch_assoc($edit);
  $updateQry = "UPDATE `general_settings` SET `user_id`='".$user_id."',`near_by`='".$near_by."' WHERE id='".$edit['id']."'";

  $updateInsert = mysqli_query($conn,$updateQry);

  if($updateInsert){
    $_SESSION['msg']['success'] = "Near By Updated Successfully.";
    header('location:near-by.php');exit;
  }else{
    $_SESSION['msg']['fail'] = "Near By Updated Failed.";
    header('location:near-by.php');exit;
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
                  <h4 class="card-title">Near By</h4>
                  <hr class="alert-dark">
                  <br>
                  <form id="commentForm" class="" method="post" action="">
                    
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-body">
                            <label for="exampleInputName1">Near By Month</label>
                            <select class="js-example-basic-single" name="near_by" style="width:100%">
                                <option value="">Select Schedule Category</option>
                                <option <?php if(isset($edit) && $edit['near_by'] == "1"){echo "selected";} ?> value="1">Month1</option>
                                <option <?php if(isset($edit) && $edit['near_by'] == "2"){echo "selected";} ?> value="2">Month2</option>
                                <option <?php if(isset($edit) && $edit['near_by'] == "3"){echo "selected";} ?> value="3">Month3</option>
                                <option <?php if(isset($edit) && $edit['near_by'] == "4"){echo "selected";} ?> value="4">Month4</option>
                                <option <?php if(isset($edit) && $edit['near_by'] == "5"){echo "selected";} ?> value="5">Month5</option>
                                <option <?php if(isset($edit) && $edit['near_by'] == "6"){echo "selected";} ?> value="6">Month6</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row" style="margin-top: 20px;">
                      <div class="col-lg-12">
                      <?php 
                      if(!empty($edit['near_by'])){
                        ?>
                      <button name="edit" type="submit" class="btn btn-success mr-2">Update</button>
                        <?php
                      }else{
                      ?>
                      <button name="submit" type="submit" class="btn btn-success mr-2">Submit</button>
                      <?php } ?>
                      <button class="btn btn-light">Cancel</button>
                      </div>
                    </div>
                  </form>
                

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

  <script type="text/javascript">
    $("#commentForm").validate({
      errorPlacement: function(label, element) {
        label.addClass('mt-2 text-danger');
        label.insertAfter(element);
      },
      highlight: function(element, errorClass) {
        $(element).parent().addClass('has-danger')
        $(element).addClass('form-control-danger')
      }
    });
  </script>
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>
  <script src="js/editorDemo.js"></script>
  
 
  
  <!-- End custom js for this page-->
</body>


</html>
