<?php $title = "Rate Master"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php //include('include/permission.php'); ?>


<?php
  $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : NULL;
  $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : NULL;
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : NULL;
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : NULL;

    if(isset($_POST['submit'])){
        $name = (isset($_POST['name'])) ? $_POST['name'] : '';
        $rate = (isset($_POST['rate'])) ? $_POST['rate'] : '';
        $status = (isset($_POST['status']) && $_POST['status'] != '') ? $_POST['status'] : 0;
        $date = date('Y-m-d H:i:s');
        $userid = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : '';
        if($name != '' && $rate != ''){
        $successMsg = '';
        $failMsg = '';
            if(isset($_GET['id']) && $_GET['id'] != ''){
                $query = "UPDATE rate_master SET modified = '".$date."', modifiedby = '".$userid."', ";
                $successMsg = 'Rate update successfully';
                $failMsg = 'Rate update fail! Please try again.';
            }else{
                $query = "INSERT INTO rate_master SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', created = '".$date."', createdby = '".$userid."', ";
                $successMsg = 'Rate added Successfully';
                $failMsg = 'Rate added fail! Please try again.';
            }
            $query .= "name = '".$name."', rate = '".$rate."', status = '".$status."'";
            if(isset($_GET['id']) && $_GET['id'] != ''){
                $query .= " WHERE id = '".$_GET['id']."'";
            }
            $res = mysqli_query($conn, $query);
            if($res){
                $_SESSION['msg']['success'] = $successMsg;
            }else{
                $_SESSION['msg']['fail'] = $failMsg;
            }
        }else{
            $_SESSION['msg']['fail'] = 'Rate save fail! Some field is required.';
        }
        header('location:rate-master.php');exit;
    }
    if(isset($_GET['id']) && $_GET['id'] != ''){
        $editQuery = "SELECT * FROM rate_master WHERE id = '".$_GET['id']."' AND pharmacy_id = '".$pharmacy_id."'";
        $resEdit = mysqli_query($conn, $editQuery);
        if($resEdit && mysqli_num_rows($resEdit) > 0){
            $editdata = mysqli_fetch_assoc($resEdit);
        }else{
            $_SESSION['msg']['fail'] = 'Invalid Request! Please Try again.';
            header('location:rate-master.php');exit;
        }
    }
    
    function getAllRateRow(){
        global $conn;
        global $pharmacy_id;
        $data = [];
        
        $query = "SELECT id, name, rate, status FROM rate_master WHERE pharmacy_id = '".$pharmacy_id."' ORDER BY id DESC";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
                $data[] = $row;
            }
        }
        return $data;
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Rate Master</title>
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
                  <h4 class="card-title">Rate Master</h4>
                  <hr class="alert-dark">
                  <br>
                  <form class="forms-sample" method="POST" autocomplete="off">
                    <div class="form-group row">
                          <div class="col-12 col-md-4">
                              <label>Name <span class="text-danger">*</span></label>
                              <input type="text" name="name" value="<?php echo (isset($editdata['name'])) ? $editdata['name'] : ''; ?>" class="form-control" placeholder="Rate Name" required>
                          </div>
                          <div class="col-12 col-md-4">
                              <label>Rate(%) <span class="text-danger">*</span></label>
                              <input type="text" name="rate" maxlength="3" value="<?php echo (isset($editdata['rate'])) ? $editdata['rate'] : ''; ?>" class="form-control onlynumber" id="rate" placeholder="Rate(%)" required>
                          </div>
                          <div class="col-12 col-md-4">
                            <label for="status">Status</label>
                            <div class="row no-gutters">
                            
                                <div class="col">
                                    <div class="form-radio">
                                    <label class="form-check-label">
                                    <?php
                                    $active = '';
                                      if(isset($_GET['id']) && $_GET['id'] != ''){
                                        $active = (isset($editdata['status']) && $editdata['status'] == 1) ? 'checked' : '';
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
                                          $deactive = (isset($editdata['status']) && $editdata['status'] == 0) ? 'checked' : '';
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
                        <a href="rate-master.php" class="btn btn-light">Cancel</a>
                        <button name="submit" type="submit" name="submit" class="btn btn-success">Submit</button>
                      </div>
                    </div>
                  </form>
                  
                    <h4 class="card-title mt-30">View Rate Master</h4>
                    <hr class="alert-dark">
                    <div class="row">
                        <div class="col-12">
                          <table class="table datatable">
                            <thead>
                              <tr>
                                  <th class="text-center">Sr No.</th>
                                  <th>Name</th>
                                  <th>Rate(%)</th>
                                  <th>Status</th>
                                  <th class="text-center">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                                <?php $dataRow = getAllRateRow(); ?>
                                <?php if(isset($dataRow) && !empty($dataRow)){ ?>
                                    <?php foreach($dataRow as $key => $value){ ?>
                                        <tr>
                                            <td class="text-center"><?php echo $key+1; ?></td>
                                            <td><?php echo (isset($value['name']) && $value['name'] != '') ? ucwords(strtolower($value['name'])) : ''; ?></td>
                                            <td><?php echo (isset($value['rate'])) ? $value['rate'].'%' : ''; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-toggle changestatus <?php echo (isset($value['status']) && $value['status'] == 1) ? 'active' : ''; ?>" data-table="rate_master" data-id="<?php echo $value['id']; ?>" data-toggle="button" aria-pressed="<?php echo (isset($value['status']) && $value['status'] == 1) ? true : false; ?>" autocomplete="off">
                                                  <div class="handle"></div>
                                                </button>
                                            </td>
                                            <td>
                                                <a class="btn  btn-behance p-2" href="rate-master.php?id=<?php echo $value['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                          </table>
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
  
  
  <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
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
  <script src="js/custom/onlynumber.js"></script>
  <!-- End custom js for this page-->
    
</body>


</html>
