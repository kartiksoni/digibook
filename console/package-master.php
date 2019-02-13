<?php 
include("include/config.php");
if(isset($_GET['id'])){
  $id = $_GET['id'];
  $editQry = "SELECT * FROM `package_master` WHERE id='".$id."'";
  $edit = mysqli_query($conn,$editQry);
  $edit = mysqli_fetch_assoc($edit);
  
}


if(isset($_POST['submit'])){
  $user_id = $_SESSION['auth']['id'];
  $package_name = $_POST['package_name'];
  $duration = $_POST['duration'];
  $rate = $_POST['rate'];
  $status = $_POST['status'];
  $pharmacy = $_POST['pharmacy'];

  $insQry = "INSERT INTO `package_master` (`package_name`, `duration`, `rate`, `no_of_pharmacy`, `status`, `cretaed_at`, `created_by`) VALUES ('".$package_name."', '".$duration."', '".$rate."', '".$pharmacy.", '".$status."', '".date('Y-m-d H:i:s')."', '".$user_id."')";
  $queryInsert = mysqli_query($conn,$insQry);

  if($queryInsert){
    $_SESSION['msg']['success'] = "Package Added successfully.";
    header('location:package-master.php');exit;
  }else{
    $_SESSION['msg']['fail'] = "Package Added Failed.";
    header('location:package-master.php');exit;
  }
}

if(isset($_POST['edit'])){
  $user_id = $_SESSION['auth']['id'];
  $package_name = $_POST['package_name'];
  $duration = $_POST['duration'];
  $rate = $_POST['rate'];
  $status = $_POST['status'];
  $pharmacy = $_POST['pharmacy'];

  $updateQry = "UPDATE `package_master` SET `package_name`='".$package_name."',`duration`='".$duration."',`rate`='".$rate."', `no_of_pharmacy` = '".$pharmacy."', `status`='".$status."',`update_at`='".date('Y-m-d H:i:s')."',`updated_by`='".$user_id."' WHERE id='".$_GET['id']."'";
  $updateInsert = mysqli_query($conn,$updateQry);

  if($updateInsert){

    $_SESSION['msg']['success'] = "Package Updated Successfully.";
    header('location:package-master.php');exit;

  }else{

    $_SESSION['msg']['fail'] = "Package Updated Failed.";
    header('location:package-master.php');exit;

  }

}
?>
<!DOCTYPE html>
<html lang="en">

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
  <link rel="stylesheet" href="css/toggle/style.css">
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
  <link rel="stylesheet" href="css/parsley.css">
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
          <span id="errormsg"></span>
          <div class="row">
            
         
            
            <!-- Service Master Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Package Master</h4>
                  <hr class="alert-dark">
                  <br>
                  <form class="forms-sample" class="" method="post" action="">
                    
                  <div class="form-group row">
                    
                      <div class="col-12 col-md-3">
                        <label for="package_name">Package Name</label>
                      	<input type="text" required name="package_name" value="<?php echo (isset($edit['package_name'])) ? $edit['package_name'] : ''; ?>" class="form-control" id="package_name" placeholder="Package Name">
                      </div>
                     
                      <div class="col-12 col-md-3">
                        <label for="exampleInputName1">Duration</label>
                      	<select class="js-example-basic-single" name="duration" style="width:100%"> 
                            <option value="">Select Duration</option>
                            <?php 
                            for($i=1;$i<=12;$i++){
                            ?>
                            <option <?php if(isset($edit['duration']) && $edit['duration'] == $i){echo "selected";} ?> value="<?php echo $i; ?>"><?php echo $i; ?> Month</option>
                            <?php } ?>
                        </select>
                      </div>
                      
                      <div class="col-12 col-md-3">
                        <label for="rate">Rate</label>
                        <input type="text" required name="rate" value="<?php echo (isset($edit['rate'])) ? $edit['rate'] : ''; ?>" class="form-control" id="rate" placeholder="Rate">
                      </div>
                      
                      <div class="col-12 col-md-3">
                        <label for="rate">No of Pharmacy</label>
                        <select class="js-example-basic-single" name="pharmacy" style="width:100%">
                        <option value="">Select Pharmacy</option>
                        <?php
                        for($x = 1; $x <= 5; $x++){
                        ?> 
                        <option value="<?php echo $x; ?>" <?php if(isset($edit['no_of_pharmacy']) && $edit['no_of_pharmacy'] == $x){echo "selected";}?>><?php echo $x; ?></option>
                        <?php } ?>
                        </select>
                      </div>
                        
                  </div>
                  <div class="form-group row">
                       
                       <div class="col-12 col-md-4">
                                  <label for="exampleInputName1">Status</label>
                                  
                                  <div class="row no-gutters">
                                  
                                      <div class="col">
                                          <div class="form-radio">
                                          <label class="form-check-label">
                                          <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" <?php if(isset($_GET['id'])){if(isset($edit['status']) && $edit['status'] == "1"){echo "checked";}  }else{echo"checked";} ?>>
                                          active
                                          </label>
                                          </div>
                                      </div>
                                      
                                      <div class="col">
                                          <div class="form-radio">
                                          <label class="form-check-label">
                                          <input type="radio" <?php if(isset($edit['status']) && $edit['status'] == "0"){echo "checked";} ?> class="form-check-input" name="status" id="optionsRadios2" value="0">
                                          Deactive
                                          </label>
                                          </div>
                                      </div>
                                  
                                  </div>
                              </div>
                          
                      </div>
                    
                    <br>
                    <a href="" class="btn btn-light">Cancel</a>
                    <?php 
                      if(isset($_GET['id'])){
                        ?>
                      <button name="edit" type="submit" class="btn btn-success mr-2">Edit</button>
                        <?php
                      }else{
                      ?>
                      <button name="submit" type="submit" class="btn btn-success mr-2">Submit</button>
                      <?php } ?>
                    
                    
                  </form>
                  <div class="col mt-3">
                    <h4 class="card-title">Package</h4>
                    <hr class="alert-dark">
                       <div class="row">
                            <div class="col-12">
                              <table id="order-listing1" class="table">
                                <thead>
                                  <tr>
                                      <th>Sr No</th>
                                      <th>Package Name</th>
                                      <th>Duration</th>
                                      <th>Rate</th>
                                      <th>Status</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts -->   
                                  <?php 
                                  $i = 1;
                                  $serviceQry = "SELECT * FROM `package_master` ORDER BY id DESC";
                                  $service = mysqli_query($conn,$serviceQry);
                                  while($row = mysqli_fetch_assoc($service)){
                                  ?>
                                  <tr>
                                      <td><?php echo $i; ?></td>
                                      <td><?php echo $row['package_name']; ?></td>
                                      <?php 
                                      if(!empty($row['duration'])){
                                        $month = "month";
                                      }else{
                                        $month = "";
                                      }
                                      ?>
                                      <td><?php echo $row['duration']; ?><?php echo $month; ?></td>
                                      <td><?php echo $row['rate']; ?></td>
                                      <?php 
                                      if($row['status'] == "1"){
                                        $checked = "checked";
                                      }else{
                                        $checked = "";
                                      }
                                      ?>
                                      <td>
                                        <button type="button" class="btn btn-sm btn-toggle changestatus <?php echo (isset($row['status']) && $row['status'] == 1) ? 'active' : ''; ?>" data-table="package_master" data-id="<?php echo $row['id']; ?>" data-toggle="button" aria-pressed="<?php echo (isset($row['status']) && $row['status'] == 1) ? true : false; ?>" autocomplete="off">
                                          <div class="handle"></div>
                                        </button>
                                      </td>
                                      <td>
                                        <a href="package-master.php?id=<?php echo $row['id']; ?>" title="edit"><i class="fa fa-edit"></i></a>
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
  <!-- change status js -->
  <script src="js/custom/statusupdate.js"></script>
  
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>

  <script src="js/parsley.min.js"></script>
    <script type="text/javascript">
      $('form').parsley();
    </script>
  
 <script>
    $('#datepicker-popup1').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
    });
 </script>
 
 <script>
    $('#datepicker-popup2').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
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
</body>


</html>
