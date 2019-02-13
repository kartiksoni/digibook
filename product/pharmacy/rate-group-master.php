<?php $title = "Comapnay Code Master"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php include('include/permission.php'); ?>


<?php
  if(isset($_POST['submit'])){
    $name = (isset($_POST['name'])) ? $_POST['name'] : '';
    $status = (isset($_POST['status'])) ? $_POST['status'] : '';
    $date = date('Y-m-d H:i:s');

    $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : NULL;
    $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : NULL;
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
    $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;

    if(isset($_GET['id']) && $_GET['id'] != ''){
      $query = "UPDATE rate_group_master SET ";
      $concate = "modified = '".$date."', modifiedby = '".$_SESSION['auth']['id']."' WHERE id = '".$_GET['id']."'";
      $success = "Rate Updated Successfully.";
      $error = "Rate Updated Fail!";
    }else{
      $query = "INSERT rate_group_master SET owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."',financial_id='".$financial_id."', ";
      $concate = "created = '".$date."', createdby = '".$_SESSION['auth']['id']."'";
      $success = "Rate Added Successfully.";
      $error = "Rate Added Fail!";
    }
    $query .="name = '".$name."', status = '".$status."', ".$concate;
    $res = mysqli_query($conn, $query);
    if($res){
      $_SESSION['msg']['success'] = $success;
      header('location:rate-group-master.php');exit;
    }else{
      $_SESSION['msg']['fail'] = $error;
      header('location:rate-group-master.php');exit;
    }
  }
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $editQuery = "SELECT * FROM rate_group_master WHERE id = '".$_GET['id']."' AND pharmacy_id = '".$pharmacy_id."'";
    $resEdit = mysqli_query($conn, $editQuery);
    if($resEdit && mysqli_num_rows($resEdit) > 0){
      $data = mysqli_fetch_array($resEdit);
    }else{
      $_SESSION['msg']['fail'] = 'Invalid Request! Please Try again.';
      header('location:rate-group-master.php');exit;
    }
  }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Rate Group Master</title>
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
  <link rel="stylesheet" href="css/parsley.css">
  <link rel="stylesheet" href="css/toggle/style.css">
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
          <span id="errormsg"></span>
          <div class="row">
            
         
            
            <!-- Service Master Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Rate Group Master</h4>
                  <hr class="alert-dark">
                  <br>
                  <form class="forms-sample" method="POST" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Rate Name <span class="text-danger">*</span></label>
                          <input type="text" required name="name" value="<?php echo (isset($data['name'])) ? $data['name'] : ''; ?>" class="form-control" placeholder="Rate Name">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Status</label>
                            <div class="row no-gutters">
                            
                                <div class="col">
                                    <div class="form-radio">
                                    <label class="form-check-label">
                                    <?php
                                    $active = '';
                                      if(isset($_GET['id']) && $_GET['id'] != ''){
                                        $active = (isset($data['status']) && $data['status'] == 1) ? 'checked' : '';
                                      }else{
                                        $active = 'checked';
                                      }

                                    ?>
                                    <input type="radio" class="form-check-input" name="status" value="1" <?php echo $active; ?> >
                                    Active
                                    </label>
                                    </div>
                                </div>
                                
                                <div class="col">
                                    <div class="form-radio">
                                    <label class="form-check-label">
                                      <?php 
                                        $deactive = '';
                                        if(isset($_GET['id']) && $_GET['id'] != ''){
                                          $deactive = (isset($data['status']) && $data['status'] == 0) ? 'checked' : '';
                                        }
                                      ?>
                                    <input type="radio" class="form-check-input" name="status" value="0" <?php echo $deactive; ?> >
                                    Deactive
                                    </label>
                                    </div>
                                </div>
                            
                            </div>
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <a href="rate-group-master.php" class="btn btn-light">Cancel</a>
                        <button name="submit" type="submit" name="submit" class="btn btn-success">Submit</button>
                      </div>
                    </div>
                  </form>
                  <div class="col mt-30">
                    <h4 class="card-title">View Rate Group Master</h4>
                    <hr class="alert-dark">
                       <div class="row">
                            <div class="col-12">
                              <table class="table datatable">
                                <thead>
                                  <tr>
                                      <th>Sr No</th>
                                      <th>Rate Name</th>
                                      <th>Status</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts -->   
                                  <?php
                                    $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
                                    $getAllRateQuery = "SELECT * FROM `rate_group_master` WHERE pharmacy_id = '".$p_id."' ORDER BY id DESC";
                                    $getAllRateRes = mysqli_query($conn,$getAllRateQuery);
                                  ?>
                                  <?php if($getAllRateRes && mysqli_num_rows($getAllRateRes) > 0){ $i = 1; ?>
                                    <?php while ($row = mysqli_fetch_array($getAllRateRes)) { ?>
                                      <tr>
                                          <td><?php echo $i; ?></td>
                                          <td><?php echo $row['name']; ?></td>
                                          <?php 
                                          if($row['status'] == "1"){
                                            $checked = "checked";
                                          }else{
                                            $checked = "";
                                          }
                                          ?>
                                          <td>
                                            <button type="button" class="btn btn-sm btn-toggle changestatus <?php echo (isset($row['status']) && $row['status'] == 1) ? 'active' : ''; ?>" data-table="rate_group_master" data-id="<?php echo $row['id']; ?>" data-toggle="button" aria-pressed="<?php echo (isset($row['status']) && $row['status'] == 1) ? true : false; ?>" autocomplete="off">
                                              <div class="handle"></div>
                                            </button>
                                          </td>
                                          <td>
                                            <a class="btn  btn-behance p-2" href="rate-group-master.php?id=<?php echo $row['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                          </td>
                                      </tr>
                                      <?php $i++; } ?>
                                  <?php } ?>
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
  
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>

  <script src="js/parsley.min.js"></script>
    <script type="text/javascript">
      $('form').parsley();
    </script>
 
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('.datatable').DataTable();
  </script>
  
  <!-- change status js -->
  <script src="js/custom/statusupdate.js"></script>
  
   
 <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  
  <!-- End custom js for this page-->
  
</body>


</html>
