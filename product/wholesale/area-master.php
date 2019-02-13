<?php $title = "GST Master"; ?>
<?php include('include/usertypecheck.php'); ?>


<?php 
  $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
?>

<?php 

  if(isset($_POST['submit'])){
    $area_name = (isset($_POST['area_name'])) ? $_POST['area_name'] : '';
    $status = (isset($_POST['status']) && $_POST['status'] != '') ? $_POST['status'] : 1;
    
    $uid = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : '';
    $date = date('Y-m-d H:i:s');
   
    if(isset($_GET['id']) && $_GET['id'] != ''){
        $query = "UPDATE area_master SET ";
    }else{
        $query = "INSERT INTO area_master SET ";
    }
    $query .= "pharmacy_id = '".$pharmacy_id."', area_name = '".$area_name."' , status = '".$status."', ";
    
    if(isset($_GET['id']) && $_GET['id'] != ''){
        $query .= "updated_at = '".$date."', updated_by = '".$uid."' WHERE id = '".$_GET['id']."'";
    }else{
       $query .= "created_at = '".$date."', created_by = '".$uid."'"; 
    }
    $res = mysqli_query($conn, $query);
    if($res){
        if(isset($_GET['id']) && $_GET['id'] != ''){
            $_SESSION['msg']['success'] = 'Area Update successfully.';
        }else{
            $_SESSION['msg']['success'] = 'Area Add successfully.';
        }
    }else{
        if(isset($_GET['id']) && $_GET['id'] != ''){
            $_SESSION['msg']['fail'] = 'Area Update Fail!';
        }else{
            $_SESSION['msg']['fail'] = 'Area Add Fail!';
        }
    }
    header('location:area-master.php');exit;
  }
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $editQuery = "SELECT * FROM area_master WHERE id = '".$_GET['id']."' AND pharmacy_id = '".$pharmacy_id."'";
    $editRes = mysqli_query($conn, $editQuery);
    if($editRes && mysqli_num_rows($editRes) > 0){
      $data = mysqli_fetch_assoc($editRes);
    }else{
        $_SESSION['msg']['fail'] = 'Invalid Request! Try Again.';
        header('location:transport-master.php');exit;
    }
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Area Master</title>
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
                  <h4 class="card-title">Area Master</h4>
                  <hr class="alert-dark">
                  <br>
                  <form class="forms-sample" method="POST" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-12 col-md-3">
                          <label for="name">Area Name <span class="text-danger">*</span></label>
                          <input type="text" required name="area_name" value="<?php echo (isset($data['area_name'])) ? $data['area_name'] : ''; ?>" class="form-control" placeholder="Area Name">
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
                        <a href="area-master.php" class="btn btn-light">Cancel</a>
                        <button name="submit" type="submit" name="submit" class="btn btn-success">Submit</button>
                      </div>
                    </div>
                  </form>
                  
                    <h4 class="card-title mt-30">View Area Master</h4>
                    <hr class="alert-dark">
                    <div class="row">
                        <div class="col-12">
                          <table class="table datatable">
                            <thead>
                              <tr>
                                  <th>Sr No</th>
                                  <th>Area Name</th>
                                  <th>Status</th>
                                  <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <!-- Row Starts -->   
                              <?php
                                $getAllTransportQ = "SELECT * FROM `area_master` WHERE pharmacy_id = '".$pharmacy_id."' ORDER BY id DESC";
                                $getAllTransportR = mysqli_query($conn,$getAllTransportQ);
                              ?>
                              <?php if($getAllTransportR && mysqli_num_rows($getAllTransportR) > 0){ $i = 1; ?>
                                <?php while ($row = mysqli_fetch_array($getAllTransportR)) { ?>
                                  <tr>
                                      <td><?php echo $i; ?></td>
                                      <td><?php echo $row['area_name']; ?></td>
                                      <?php 
                                      if($row['status'] == "1"){
                                        $checked = "checked";
                                      }else{
                                        $checked = "";
                                      }
                                      ?>
                                      <td>
                                        <button type="button" class="btn btn-sm btn-toggle changestatus <?php echo (isset($row['status']) && $row['status'] == 1) ? 'active' : ''; ?>" data-table="area_master" data-id="<?php echo $row['id']; ?>" data-toggle="button" aria-pressed="<?php echo (isset($row['status']) && $row['status'] == 1) ? true : false; ?>" autocomplete="off">
                                          <div class="handle"></div>
                                        </button>
                                      </td>
                                      <td>
                                        <a class="btn  btn-behance p-2" href="area-master.php?id=<?php echo $row['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
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
  
  
  <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <script>
     $('.datatable').DataTable();
  </script>
  
  <!-- change status js -->
  <script src="js/custom/statusupdate.js"></script>
  <script src="js/custom/gst-master.js"></script>
  <script src="js/custom/onlynumber.js"></script>
  
  <!-- End custom js for this page-->
  
</body>


</html>