<?php include('include/usertypecheck.php'); ?>
<?php 
if($_SESSION['auth']['user_type'] != "owner"){
    header('Location:index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks | View Pharmacy Profile</title>
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
            
            <?php
            $financialQry = "SELECT * FROM `pharmacy_profile` WHERE created_by='".$_SESSION['auth']['id']."' ORDER BY id DESC";
            $financial = mysqli_query($conn,$financialQry);
            $count = mysqli_num_rows($financial);
            $ihis_flag = $_SESSION['auth']['is_ihis'];
            
            ?>
            
            <!-- Product Master Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                <?php 
                if(($ihis_flag == 1 && $count < 2) ||$ihis_flag == 0){
                ?>
                  <div class="text-right mb-2">
                    <a href="pharmacy-profile.php" class="btn btn-success pull-right">
                      <i class="mdi mdi-plus-circle-outline"></i>Add New Pharmacy</a>
                  </div>
                 <?php 
                }
                 ?>
                  <h4 class="card-title">View Pharmacy Profile</h4>
                  
                  <hr class="alert-dark">
                  
                  <br>
                  <div class="col mt-3">
                       <div class="row">
                            <div class="col-12">
                              <table id="order-listing1" class="table">
                                <thead>
                                  <tr>
                                      <th>Sr No</th>
                                      <th>Firm Name</th>
                                      <th>Pharmacy Name</th>
                                      <th>Contact Person Name</th>
                                      <th>Mobile No</th>
                                      <th>Email</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts -->   
                                  <?php 
                                  $i = 1;
                                  while($row = mysqli_fetch_assoc($financial)){
                                  ?>
                                  <tr>
                                      <td><?php echo $i; ?></td>
                                      <td><?php echo $row['firm_name']; ?></td>
                                      <td><?php echo $row['pharmacy_name']; ?></td>
                                      <td><?php echo $row['contact_person_name']; ?></td>
                                      <td><?php echo $row['mobile_no']; ?></td>
                                      <td><?php echo $row['email']; ?></td>
                                      <td>
                                        <a class="btn  btn-behance p-2" href="pharmacy-profile.php?id=<?php echo $row['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                       <!-- <a class="btn  btn-success p-2" href="user.php?pharmacy_id=<?php echo $row['id']; ?>" title="Create User"><i class="fa fa-user-plus mr-0"></i></a>-->
                                      </td>
                                  </tr><!-- End Row --> 
                                  <?php 
                                  $i++;
                                  }
                                  ?>  
                                </tbody>
                              </table>
                            </div>
                          </div>
                    </div>
                    <hr>
                   <br>
                   <a href="configuration.php" class="btn btn-light">Back</a>
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
  
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
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
</body>


</html>
