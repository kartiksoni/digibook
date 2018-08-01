<?php include('include/usertypecheck.php'); ?>
<!DOCTYPE html>
<html lang="en">
<?php 
  $editQry = "SELECT * FROM `bill_note`";
  $edit = mysqli_query($conn,$editQry);
  $edit = mysqli_fetch_assoc($edit);
  
  



if(isset($_POST['submit'])){
  $user_id = $_SESSION['auth']['id'];
  $financial_year = $_POST['financial_year'];
  $bill_note = $_POST['bill_note'];

  $insQry = "INSERT INTO `bill_note` (`user_id`, `finance_year_id`, `bill_note`, `created_at`, `created_by`) VALUES ('".$user_id."', '".$financial_year."', '".$bill_note."', '".date('Y-m-d H:i:s')."', '".$user_id."')";
  $queryInsert = mysqli_query($conn,$insQry);
  if($queryInsert){
    $_SESSION['msg']['success'] = "Bill Note Added Successfully.";
    header('location:bill-note.php');exit;
  }else{
    $_SESSION['msg']['fail'] = "Bill Note Added Failed.";
    header('location:bill-note.php');exit;
  }
}



if(isset($_POST['edit'])){


  $user_id = $_SESSION['auth']['id'];
  $financial_year = $_POST['financial_year'];
  $editQry = "SELECT * FROM `bill_note`";
  $edit = mysqli_query($conn,$editQry);
  $edit = mysqli_fetch_assoc($edit);
  $bill_note = $_POST['bill_note'];
  $updateQry = "UPDATE `bill_note` SET `user_id`='".$user_id."',`finance_year_id`='".$financial_year."',`bill_note`='".$bill_note."',`updated_at`='".date('Y-m-d H:i:s')."',`updated_by`='".$user_id."' WHERE id='".$edit['id']."'";

  $updateInsert = mysqli_query($conn,$updateQry);

  if($updateInsert){
    $_SESSION['msg']['success'] = "Bill Note Updated Successfully.";
    header('location:bill-note.php');exit;
  }else{
    $_SESSION['msg']['fail'] = "Bill Note Updated Failed.";
    header('location:bill-note.php');exit;
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
                  <h4 class="card-title">Bill Note</h4>
                  <hr class="alert-dark">
                  <br>
                  <form id="commentForm" class="" method="post" action="">
                    
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-body">
                            <h4 class="card-title">Bill Note</h4>
                            <textarea id="simpleMde" name="bill_note"><?php echo $edit['bill_note']; ?>
                            </textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row" style="margin-top: 20px;">
                      <div class="col-lg-12">
                      <?php 
                      if(!empty($edit['bill_note'])){
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
