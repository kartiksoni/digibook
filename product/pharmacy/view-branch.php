<?php $title = "Branch"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php include('include/permission.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks | View Branch</title>
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
          <div class="row">


<!-- Table ------------------------------------------------------------------------------------------------------>
<?php 
    
    $usertype = (isset($_SESSION['auth']['user_type'])) ? $_SESSION['auth']['user_type'] : '';
    $authid = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : '';
    $ownerid = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
    
    $id = ($usertype == 'owner') ? $authid : $ownerid;
    $pharmacyqry = "SELECT * FROM `pharmacy_profile` WHERE `created_by` = '".$id."' ORDER BY id DESC";
    $pharmacyrun = mysqli_query($conn, $pharmacyqry);
?>
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Branch List</h4>
        <hr class="alert-dark">
        <br>
        <div class="col mt-3">
        <div class="row">
          <div class="col-12">
            <div class="table-responsive">
              <table id="order-listing" class="table datatable">
              <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Pharmacy Name</th>
                    <th>Contact Person</th>
                    <th>Address</th>
                    <th>Mobile No</th>
                    <th>Email</th>
                </tr>
              </thead>
              <tbody>

              <!-- Row Starts --> 

              <?php
              if($pharmacyrun){
                  $count = 0;
                  while($data = mysqli_fetch_assoc($pharmacyrun)){
                      $count++;
              ?>      
              <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $data['pharmacy_name']; ?></td>
                    <td><?php echo $data['contact_person_name']; ?></td>
                    <td><?php echo $data['address1']; ?></td>
                    <td><?php echo $data['mobile_no']; ?></td>
                    <td><?php echo $data['email']; ?></td> 
              </tr><!-- End Row -->
              <?php } } ?>
            </tbody>
            </table>
            </div>
            </div>        
          </div>
        </div>
        </div>
      </div>              
    </div>                
</div> 
</div>
                       




<!-- partial:partials/_footer.php -->
<?php include "include/footer.php"?>
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

<!-- Custom js for this page-->
<script src="js/modal-demo.js"></script>


  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>

  
</body>
</html>
