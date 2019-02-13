<?php $title = "SMS Config"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php //include('include/permission.php'); ?>


<?php 

  if(isset($_POST['submit'])){
    $pharmacy = (isset($_POST['pharmacy'])) ? $_POST['pharmacy'] : '';
    $sender_id = (isset($_POST['sender_id'])) ? $_POST['sender_id'] : '';
    $server_url = (isset($_POST['server_url'])) ? $_POST['server_url'] : '';
    $auth_key = (isset($_POST['auth_key'])) ? $_POST['auth_key'] : '';
    $status = (isset($_POST['status']) && $_POST['status'] != '') ? $_POST['status'] : 1;
    $uid = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : '';
    $date = date('Y-m-d H:i:s');
    $editid = (isset($_GET['id'])) ? $_GET['id'] : '';
    $exist = isExistSMSConfig($pharmacy, $editid);
    if($exist){
        if(isset($_GET['id']) && $_GET['id'] != ''){
            $query = "UPDATE sms_config SET ";
        }else{
            $query = "INSERT INTO sms_config SET ";
        }
        $query .= "pharmacy = '".$pharmacy."', sender_id = '".$sender_id."', server_url = '".$server_url."', auth_key = '".$auth_key."', status = '".$status."', ";
        
        if(isset($_GET['id']) && $_GET['id'] != ''){
            $query .= "modified = '".$date."', modifiedby = '".$uid."' WHERE id = '".$_GET['id']."'";
        }else{
            $query .= "created = '".$date."', createdby = '".$uid."'";
        }
        
        $res = mysqli_query($conn, $query);
        if($res){
            if(isset($_GET['id']) && $_GET['id'] != ''){
                $_SESSION['msg']['success'] = 'Config Update successfully.';
            }else{
                $_SESSION['msg']['success'] = 'Config Add successfully.';
            }
        }else{
            if(isset($_GET['id']) && $_GET['id'] != ''){
                $_SESSION['msg']['fail'] = 'Config Update Fail!';
            }else{
                $_SESSION['msg']['fail'] = 'Config Add Fail!';
            }
        }
    }else{
        $_SESSION['msg']['fail'] = 'This Pharmacy have already added config! Check it.';
    }
    header('location:sms-config.php');exit;
  }
  
  function isExistSMSConfig($pharmacy = null, $id = null){
      global $conn;
      
      $query = "SELECT id FROM sms_config WHERE pharmacy = '".$pharmacy."' AND createdby = '".$_SESSION['auth']['id']."' ";
      if(isset($id) && $id != ''){
          $query .= "AND id != '".$id."'";
      }
      $res = mysqli_query($conn, $query);
      if($res && mysqli_num_rows($res) > 0){
          return false;
      }else{
          return true;
      }
  }
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $editQuery = "SELECT * FROM sms_config WHERE id = '".$_GET['id']."' AND createdby = '".$_SESSION['auth']['id']."'";
    $editRes = mysqli_query($conn, $editQuery);
    if($editRes && mysqli_num_rows($editRes) > 0){
      $data = mysqli_fetch_assoc($editRes);
    }else{
        $_SESSION['msg']['fail'] = 'Invalid Request! Try Again.';
        header('location:sms-config.php');exit;
    }
  }
  
  if(isset($_GET['delete']) && $_GET['delete'] != ''){
      $query = "DELETE FROM sms_config WHERE id = '".$_GET['delete']."' AND createdby = '".$_SESSION['auth']['id']."'";
      $res = mysqli_query($conn, $query);
      if($res){
          $_SESSION['msg']['success'] = 'Record delete successfully.';
      }else{
          $_SESSION['msg']['fail'] = 'Record delete fail!.';
      }
     header('location:sms-config.php');exit;
  }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Comapnay Code Master</title>
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
                    <h4 class="card-title">SMS Config</h4>
                    <hr class="alert-dark">
                    <br>
                    <form class="forms-sample" method="POST" autocomplete="off">
                        <div class="form-group row">
                            <div class="col-12 col-md-4">
                              <label for="pharmacy">Select Pharmacy <span class="text-danger">*</span></label>
                              <select class="js-example-basic-single" style="width:100%" name="pharmacy" required data-parsley-errors-container="#error-pharmacy">
                                <?php 
                                  $allPharmacy = [];
                                  $getPharmacyQ = "SELECT id, pharmacy_name FROM pharmacy_profile WHERE created_by = '".$_SESSION['auth']['id']."' ORDER BY pharmacy_name";
                                  $getPharmacyR = mysqli_query($conn, $getPharmacyQ);
                                  if($getPharmacyR && mysqli_num_rows($getPharmacyR) > 0){
                                    while($getPharmacyRow = mysqli_fetch_assoc($getPharmacyR)){
                                        $allPharmacy[] = $getPharmacyRow;
                                    }
                                  }
                                ?>
                                <option value="">Select Pharmacy</option>
                                <?php if(isset($allPharmacy) && !empty($allPharmacy)){ ?>
                                    <?php foreach ($allPharmacy as $key => $value) { ?>
                                      <option value="<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>" <?php echo (isset($data['pharmacy']) && $data['pharmacy'] == $value['id']) ? 'selected' : ''; ?> ><?php echo (isset($value['pharmacy_name'])) ? $value['pharmacy_name'] : ''; ?></option>
                                    <?php } ?>
                                <?php } ?>
                              </select>
                              <span id="error-pharmacy"></span>
                            </div>
                            
                            <div class="col-12 col-md-4">
                              <label for="sender_id">Sender Id <span class="text-danger">*</span></label>
                              <input type="text" name="sender_id" class="form-control" placeholder="Sender Id" value="<?php echo (isset($data['sender_id'])) ? $data['sender_id'] : ''; ?>" required>
                            </div>
                            
                            <div class="col-12 col-md-4">
                              <label for="server_url">Server Url IP <span class="text-danger">*</span></label>
                              <input type="text" name="server_url" class="form-control" placeholder="Server Url IP" value="<?php echo (isset($data['server_url'])) ? $data['server_url'] : ''; ?>" required>
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <div class="col-12 col-md-4">
                              <label for="auth_key">AUTH Key <span class="text-danger">*</span></label>
                              <input type="text" name="auth_key" class="form-control" placeholder="AUTH Key" value="<?php echo (isset($data['auth_key'])) ? $data['auth_key'] : ''; ?>" required>
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
                            <a href="sms-config.php" class="btn btn-light">Cancel</a>
                            <button name="submit" type="submit" name="submit" class="btn btn-success">Submit</button>
                          </div>
                        </div>
                    </form>
                    
                    <h4 class="card-title" style="margin-top:50px;">View SMS Config</h4>
                    <hr class="alert-dark">
                    <div class="row">
                        <div class="col-12">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Pharmacy Name</th>
                                        <th>Sender ID</th>
                                        <th>Server URL IP</th>
                                        <th>Auth Key</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts -->   
                                  <?php
                                    $getAllConfigQ = "SELECT conf.*, pp.pharmacy_name  FROM `sms_config` conf LEFT JOIN pharmacy_profile pp ON conf.pharmacy = pp.id WHERE conf.createdby = '".$_SESSION['auth']['id']."' ORDER BY conf.id DESC";
                                    $getAllConfigR = mysqli_query($conn,$getAllConfigQ);
                                  ?>
                                  <?php if($getAllConfigR && mysqli_num_rows($getAllConfigR) > 0){ $i = 1; ?>
                                    <?php while ($row = mysqli_fetch_array($getAllConfigR)) { ?>
                                      <tr>
                                          <td><?php echo $i; ?></td>
                                          <td><?php echo (isset($row['pharmacy_name'])) ? $row['pharmacy_name'] : ''; ?></td>
                                          <td><?php echo (isset($row['sender_id'])) ? $row['sender_id'] : ''; ?></td>
                                          <td><?php echo (isset($row['server_url'])) ? $row['server_url'] : ''; ?></td>
                                          <td><?php echo (isset($row['auth_key'])) ? $row['auth_key'] : ''; ?></td>
                                          <td>
                                            <button type="button" class="btn btn-sm btn-toggle changestatus <?php echo (isset($row['status']) && $row['status'] == 1) ? 'active' : ''; ?>" data-table="sms_config" data-id="<?php echo $row['id']; ?>" data-toggle="button" aria-pressed="<?php echo (isset($row['status']) && $row['status'] == 1) ? true : false; ?>" autocomplete="off">
                                              <div class="handle"></div>
                                            </button>
                                          </td>
                                          <td>
                                            <a class="btn  btn-behance p-2" href="sms-config.php?id=<?php echo $row['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                            <a class="btn  btn-danger p-2" href="sms-config.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure want to delete?')" title="Delete"><i class="fa fa-trash-o mr-0"></i></a>
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
  
  <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>

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
  
  
  <!-- End custom js for this page-->
  
</body>


</html>
