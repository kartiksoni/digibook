<?php $title = "Product Type Master"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php include('include/permission.php'); ?>
<?php 
if(isset($_GET['id'])){
  $id = $_GET['id'];
  $editQry = "SELECT * FROM `product_type` WHERE id='".$id."' ORDER BY id DESC LIMIT 1";
  $edit = mysqli_query($conn,$editQry);
  $edit = mysqli_fetch_assoc($edit);
  /*echo"<pre>";
  print_r($edit);exit;*/
}


if(isset($_POST['submit'])){

  $user_id = $_SESSION['auth']['id'];
  $financial_year_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : '';
  $product_type = $_POST['product_type'];
  $status = $_POST['status'];

  $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : NULL;
  $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : NULL;
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;

  $insQry = "INSERT INTO `product_type` (`owner_id`,`admin_id`,`pharmacy_id`,`user_id`, `finance_year_id`, `product_type`, `status`, `created_at`, `created_by`) VALUES ('".$owner_id."', '".$admin_id."', '".$pharmacy_id."', '".$user_id."', '".$financial_year_id."', '".$product_type."', '".$status."', '".date('Y-m-d H:i:s')."', '".$user_id."')";
  $queryInsert = mysqli_query($conn,$insQry);
  if($queryInsert){
    $_SESSION['msg']['success'] = "Product Type successfully.";
    header('location:product-type-master.php');exit;
  }else{
    $_SESSION['msg']['fail'] = "Product Type Not Add.";
    header('location:product-type-master.php');exit;
  }
}



if(isset($_POST['edit'])){

  $user_id = $_SESSION['auth']['id'];
  $financial_year_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : '';
  $product_type = $_POST['product_type'];
  $status = $_POST['status'];

  $updateQry = "UPDATE `product_type` SET `user_id`='".$user_id."',`finance_year_id`='".$financial_year_id."',`product_type`='".$product_type."',`status`='".$status."',`updated_at`='".date('Y-m-d H:i:s')."',`updated_by`='".$user_id."' WHERE id='".$_GET['id']."'";
  $updateInsert = mysqli_query($conn,$updateQry);

  if($updateInsert){
    $_SESSION['msg']['success'] = "Product Type Update successfully.";
    header('location:product-type-master.php');exit;
  }else{
    $_SESSION['msg']['fail'] = "Product Type Not Update.";
    header('location:product-type-master.php');exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Product Type</title>
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
                        <h4 class="card-title">Product Type</h4><hr class="alert-dark"><br>
                        <form  method="post" autocomplete="off">
                            <div class="form-group row">
                                <div class="col-12 col-md-4">
                                  <label for="product_type">Product Type<span class="text-danger">*</span></label>
                                  <input type="text" class="form-control" name="product_type" id="exampleInputName1" placeholder="Product Type" value="<?php echo (isset($edit['product_type'])) ? $edit['product_type'] : ''; ?>" required="">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="exampleInputName1">Status</label>
                                    <div class="row no-gutters">
                                        <div class="col">
                                            <div class="form-radio">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" <?php if(isset($_GET['id'])){if(isset($edit['status']) && $edit['status'] == "1"){echo "checked";}  }else{echo"checked";} ?>>
                                                    Active
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
                            <a href="configuration.php" class="btn btn-light">Cancel</a>
                            <?php if(isset($_GET['id'])){ ?>
                                <button name="edit" type="submit" class="btn btn-success mr-2">Update</button>
                            <?php }else{ ?>
                                <button name="submit" type="submit" class="btn btn-success mr-2">Submit</button>
                            <?php } ?>
                        </form>
                  
              
                        <h4 class="card-title mt-30">View Product Type</h4><hr class="alert-dark">
                        <div class="row">
                            <div class="col-12">
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Product Type</th>
                                            <th>status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Row Starts -->   
                                        <?php 
                                            $i = 1;
                                            $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
                                            $financialQry = "SELECT * FROM `product_type` WHERE pharmacy_id = '".$p_id."' ORDER BY id DESC";
                                            $financial = mysqli_query($conn,$financialQry);
                                            while($row = mysqli_fetch_assoc($financial)){
                                        ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $row['product_type']; ?></td>
                                                    <?php 
                                                        if($row['status'] == "1"){
                                                            $checked = "checked";
                                                        }else{
                                                            $checked = "";
                                                        }
                                                    ?>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-toggle changestatus <?php echo (isset($row['status']) && $row['status'] == 1) ? 'active' : ''; ?>" data-table="product_type" data-id="<?php echo $row['id']; ?>" data-toggle="button" aria-pressed="<?php echo (isset($row['status']) && $row['status'] == 1) ? true : false; ?>" autocomplete="off">
                                                            <div class="handle"></div>
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <a class="btn  btn-behance p-2" href="product-type-master.php?id=<?php echo $row['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                                    </td>
                                                </tr><!-- End Row --> 
                                        <?php  $i++; } ?>  
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

  <script src="js/parsley.min.js"></script>
    <script type="text/javascript">
      $('form').parsley();
    </script>
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>
 
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('.datatable').DataTable();
  </script>
  
  <!-- change status js -->
  <script src="js/custom/statusupdate.js"></script>
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <!-- End custom js for this page-->
</body>


</html>
