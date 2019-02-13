<?php $title = "Financial Year Master"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php include('include/permission.php'); ?>

<!DOCTYPE html>
<html lang="en">
<?php 
if(isset($_GET['id'])){
  $id = $_GET['id'];
  $editQry = "SELECT * FROM `financial` WHERE id='".$id."' ORDER BY id DESC LIMIT 1";
  $edit = mysqli_query($conn,$editQry);
  $edit = mysqli_fetch_assoc($edit);
  /*echo"<pre>";
  print_r($edit);exit;*/
}


if(isset($_POST['submit'])){
  $user_id = $_SESSION['auth']['id'];
  $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : '';
  $financial_year = $_POST['financial_year'];
  $start_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST['start_date'])));
  $end_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST['end_date'])));
  $action = $_POST['action'];

  $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : NULL;
  $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : NULL;
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;

  $insQry = "INSERT INTO `financial` (`owner_id`,`admin_id`,`pharmacy_id`,`financial_id`,`user_id`, `f_name`, `start_date`, `end_date`, `status`, `created_at`, `created_by`) VALUES ('".$owner_id."', '".$admin_id."', '".$pharmacy_id."','".$financial_id."', '".$user_id."', '".$financial_year."', '".$start_date."', '".$end_date."', '".$action."', '".date('Y-m-d H:i:s')."', '".$user_id."')";
  $queryInsert = mysqli_query($conn,$insQry);
  if($queryInsert){
    $_SESSION['msg']['success'] = "Financial Year successfully.";
    header('location:financial-year.php');exit;
  }else{
    $_SESSION['msg']['fail'] = "Financial Year Not Add.";
    header('location:financial-year.php');exit;
  }
}



if(isset($_POST['edit'])){
 /* echo"<pre>";
  print_r($_POST);exit;*/
  $user_id = $_SESSION['auth']['id'];
  $financial_year = $_POST['financial_year'];
  $start_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST['start_date'])));
  $end_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST['end_date'])));
  $action = $_POST['action'];
  $updateQry = "UPDATE `financial` SET `user_id`='".$user_id."',`f_name`='".$financial_year."',`start_date`='".$start_date."',`end_date`='".$end_date."',`status`='".$action."',`updated_at`='".date('Y-m-d H:i:s')."',`updated_by`='".$user_id."' WHERE id='".$_GET['id']."'";
  $updateInsert = mysqli_query($conn,$updateQry);

  if($updateInsert){
    $_SESSION['msg']['success'] = "Financial Year Update successfully.";
    header('location:financial-year.php');exit;
  }else{
    $_SESSION['msg']['fail'] = "Financial Year Not Update.";
    header('location:financial-year.php');exit;
  }
}
?>

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Financial Year</title>
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
            <!-- Financial Year Form -->
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Financial Year</h4>
                        <hr class="alert-dark">
                        <br>
                        <form id="commentForm" class="" method="post" autocomplete="off">
                            <div class="form-group row">
                                <div class="col-12 col-md-3">
                                  <label for="financial_year">Financial year <span class="text-danger">*</span></label>
                                  <input type="text" class="form-control" name="financial_year" id="financial_year" placeholder="Financial year" value="<?php echo (isset($edit['f_name'])) ? $edit['f_name'] : ''; ?>" required="">
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                    <div id="datepicker-popup1" class="input-group date datepicker">
                                        <?php 
                                            if(isset($_GET['id']) && $_GET['id'] != ''){
                                                $startdate_val = (isset($edit['start_date']) && $edit['start_date'] != '') ? date('d/m/Y',strtotime($edit['start_date'])) : '';
                                            }else{
                                                $startdate_val = date('d/m/Y');
                                            }
                                        ?>
                                        <input type="text" value="<?php echo (isset($startdate_val)) ? $startdate_val : ''; ?>" name="start_date" class="form-control" data-parsley-errors-container="#error-startdate" required>
                                        <span class="input-group-addon input-group-append border-left">
                                            <span class="mdi mdi-calendar input-group-text"></span>
                                        </span>
                                    </div>
                                    <span id="error-startdate"></span>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="end_date">End Date <span class="text-danger">*</span></label>
                                    <div id="datepicker-popup2" class="input-group date datepicker">
                                        <?php
                                            if(isset($_GET['id']) && $_GET['id'] != ''){
                                                $enddate_val = (isset($edit['end_date']) && $edit['end_date'] != '') ? date('d/m/Y',strtotime($edit['end_date'])) : '';
                                            }else{
                                                $enddate_val = date('d/m/Y');
                                            }
                                        ?>
                                        <input type="text" value="<?php echo (isset($enddate_val)) ? $enddate_val : ''; ?>" data-parsley-errors-container="#error-enddate" required name="end_date" class="form-control">
                                        <span class="input-group-addon input-group-append border-left">
                                            <span class="mdi mdi-calendar input-group-text"></span>
                                        </span>
                                    </div>
                                    <span id="error-enddate"></span>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="exampleInputName1">Status</label>
                                    <div class="row no-gutters">
                                        <div class="col">
                                            <div class="form-radio">
                                                <label class="form-check-label">
                                                    <input type="radio" <?php if(isset($edit['status']) && $edit['status'] == "1"){echo "checked";}else{echo"checked";} ?> class="form-check-input" name="action" id="optionsRadios1" value="1">
                                                    Active
                                                </label>
                                            </div>
                                        </div>
                                              
                                        <div class="col">
                                            <div class="form-radio">
                                                <label class="form-check-label">
                                                    <input type="radio" <?php if(isset($edit['status']) && $edit['status'] == "0"){echo "checked";} ?> class="form-check-input" name="action" id="optionsRadios2" value="0">
                                                    Deactive
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          
                            <a href="financial-year.php" class="btn btn-light">Cancel</a>
                            <?php if(isset($_GET['id'])){ ?>
                                <button name="edit" type="submit" class="btn btn-success mr-2">Update</button>
                            <?php }else{ ?>
                                <button name="submit" type="submit" class="btn btn-success mr-2">Submit</button>
                            <?php } ?>
                        </form>
                      
                        
                        <h4 class="card-title mt-30">View Financial Year</h4><hr class="alert-dark">
                        <div class="row">
                                <div class="col-12">
                                    <table id="order-listing1" class="table">
                                        <thead>
                                            <tr>
                                              <th>Sr No</th>
                                              <th>Financial year</th>
                                              <th>Start Date</th>
                                              <th>End Date</th>
                                              <th>Status</th>
                                              <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          <!-- Row Starts -->   
                                          <?php 
                                          $i = 1;
                                          $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
                                          $financialQry = "SELECT * FROM `financial` WHERE pharmacy_id = '".$p_id."' ORDER BY id DESC";
                                          $financial = mysqli_query($conn,$financialQry);
                                          while($row = mysqli_fetch_assoc($financial)){
                                          ?>
                                          <tr>
                                              <td><?php echo $i; ?></td>
                                              <td><?php echo $row['f_name']; ?></td>
                                              <td><?php echo date("d-m-Y",strtotime($row['start_date'])); ?></td>
                                              <td><?php echo date("d-m-Y",strtotime($row['end_date'])); ?></td>
                                              <?php 
                                              if($row['status'] == "1"){
                                                $checked = "checked";
                                              }else{
                                                $checked = "";
                                              }
                                              ?>
                                              <td>
                                                <button type="button" class="btn btn-sm btn-toggle changestatus <?php echo (isset($row['status']) && $row['status'] == 1) ? 'active' : ''; ?>" data-table="financial" data-id="<?php echo $row['id']; ?>" data-toggle="button" aria-pressed="<?php echo (isset($row['status']) && $row['status'] == 1) ? true : false; ?>" autocomplete="off">
                                                  <div class="handle"></div>
                                                </button>
                                              </td>
                                              <td>
                                                <a class="btn btn-behance p-2" href="financial-year.php?id=<?php echo $row['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
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
  
  
  <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>

<script src="js/parsley.min.js"></script>
    <script type="text/javascript">
      $('form').parsley();
    </script>
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>
  
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
  
  <!-- change status js -->
  <script src="js/custom/statusupdate.js"></script>
  
  <!-- End custom js for this page-->
</body>


</html>
