<?php $title = "Bill Notes"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php');?>

<?php
    $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
    $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
    $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
    
    if(isset($_POST['submit'])){
        $user_id = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : '';
        $date = date('Y-m-d H:i:s');
        $bill_note = (isset($_POST['bill_note'])) ? $_POST['bill_note'] : '';
        
        $existQ = "SELECT id FROM bill_note WHERE pharmacy_id = '".$pharmacy_id."'";
        $existR = mysqli_query($conn, $existQ);
        if($existR && mysqli_num_rows($existR) > 0){
            $existRow = mysqli_fetch_assoc($existR);
            $updateQ = "UPDATE bill_note SET bill_note = '".$bill_note."', modified = '".$date."', modifiedby = '".$user_id."' WHERE id = '".$existRow['id']."'";
            $updateR = mysqli_query($conn, $updateQ);
            if($updateR){
                $_SESSION['msg']['success'] = "Bill Note Update Successfully.";
            }else{
                $_SESSION['msg']['fail'] = "Bill Note Update Fail!";
            }
        }else{
            $insertQ = "INSERT INTO bill_note SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', bill_note = '".$bill_note."', created = '".$date."', createdby = '".$user_id."'";
            $insertR = mysqli_query($conn, $insertQ);
            if($insertR){
                $_SESSION['msg']['success'] = "Bill Note Added Successfully.";
            }else{
                $_SESSION['msg']['fail'] = "Bill Note Added Fail!";
            }
        }
        header('location:bill-note.php');exit;
    }
    
    $getBillNoteQ = "SELECT id, bill_note FROM bill_note WHERE pharmacy_id = '".$pharmacy_id."'";
    $getBillNoteR = mysqli_query($conn, $getBillNoteQ);
    if($getBillNoteR && mysqli_num_rows($getBillNoteR) > 0){
        $getBillNoteRow = mysqli_fetch_assoc($getBillNoteR);
        $billnoteData = isset($getBillNoteRow['bill_note']) ? $getBillNoteRow['bill_note'] : '';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Bill Note</title>
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
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Bill Note</h4><hr class="alert-dark"><br>
                  <form method="post" autocomplete="off">
                    <div class="form-group row">
                      <div class="col-12 col-md-12">
                        <!--<label for="bill_note">Bill Note</label>-->
                        <textarea id="simpleMde" name="bill_note"><?php echo (isset($billnoteData)) ? $billnoteData : ''; ?></textarea>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-12 col-md-12">
                        <a href="configuration.php" class="btn btn-light">Back</a>
                          <button name="submit" type="submit" class="btn btn-success mr-2">Submit</button>
                      </div>
                    </div>
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
  
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <!-- End custom js for this page-->
</body>


</html>
