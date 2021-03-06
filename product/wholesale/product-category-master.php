<?php $title = "Product Category Master"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php include('include/permission.php'); ?>
<!DOCTYPE html>
<html lang="en">
<?php 
if(isset($_GET['id'])){
  $id = $_GET['id'];
  $editQry = "SELECT * FROM `product_category` WHERE id='".$id."' ORDER BY id DESC LIMIT 1";
  $edit = mysqli_query($conn,$editQry);
  $edit = mysqli_fetch_assoc($edit);
}


if(isset($_POST['submit'])){
  
  $user_id = $_SESSION['auth']['id'];
  $financial_year_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : '';
  $product_category = $_POST['product_category'];
  $status = $_POST['status'];

  $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : NULL;
  $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : NULL;
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;

  $insQry = "INSERT INTO `product_category` (`owner_id`,`admin_id`,`pharmacy_id`,`user_id`, `finance_year_id`, `product_cat`, `status`, `created_at`, `created_by`) VALUES ('".$owner_id."', '".$admin_id."', '".$pharmacy_id."', '".$user_id."', '".$financial_year_id."', '".$product_category."', '".$status."', '".date('Y-m-d H:i:s')."', '".$user_id."')";
  
  $queryInsert = mysqli_query($conn,$insQry);

  if($queryInsert){
    $_SESSION['msg']['success'] = "Product Category successfully.";
    header('location:product-category-master.php');exit;
  }else{
    $_SESSION['msg']['fail'] = "Product Type Not Add.";
    header('location:product-category-master.php');exit;
  }
}



if(isset($_POST['edit'])){

  $user_id = $_SESSION['auth']['id'];
  $financial_year_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : '';;
  $product_category = $_POST['product_category'];
  $status = $_POST['status'];

  $updateQry = "UPDATE `product_category` SET `user_id`='".$user_id."',`finance_year_id`='".$financial_year_id."',`product_cat`='".$product_category."',`status`='".$status."',`updated_at`='".date('Y-m-d H:i:s')."',`updated_by`='".$user_id."' WHERE id='".$_GET['id']."'";
  $updateInsert = mysqli_query($conn,$updateQry);

  if($updateInsert){
    $_SESSION['msg']['success'] = "Product Category Update successfully.";
    header('location:product-category-master.php');exit;
  }else{
    $_SESSION['msg']['fail'] = "Product Category Not Update.";
    header('location:product-category-master.php');exit;
  }
}
?>

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Product Category</title>
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
                    <h4 class="card-title">Product Category</h4><hr class="alert-dark"><br>
                    <form id="commentForm" class="" method="post" autocomplete="off">
                        <div class="form-group row">
                            <div class="col-12 col-md-4">
                                <label for="product_category">Product Category <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="product_category" id="product_category" placeholder="Product Category" value="<?php echo (isset($edit['product_cat'])) ? $edit['product_cat'] : ''; ?>" required="">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="status">Status</label>
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
                        <a href="product-category-master.php" class="btn btn-light">Cancel</a>
                        <?php if(isset($_GET['id'])){ ?>
                            <button name="edit" type="submit" class="btn btn-success mr-2">Update</button>
                        <?php }else{ ?>
                            <button name="submit" type="submit" class="btn btn-success mr-2">Submit</button>
                        <?php } ?>
                    </form>
                
                    <h4 class="card-title mt-30">View Product Category</h4>
                    <hr class="alert-dark">
                    <div class="row">
                        <div class="col-12">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Product Category</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $i = 1;
                                        $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
                                        $financialQry = "SELECT * FROM `product_category` WHERE pharmacy_id = '".$p_id."' ORDER BY id DESC";
                                        $financial = mysqli_query($conn,$financialQry);
                                        while($row = mysqli_fetch_assoc($financial)){
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $row['product_cat']; ?></td>
                                        <?php 
                                            if($row['status'] == "1"){
                                                $checked = "checked";
                                            }else{
                                                $checked = "";
                                            }
                                        ?>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-toggle changestatus <?php echo (isset($row['status']) && $row['status'] == 1) ? 'active' : ''; ?>" data-table="product_category" data-id="<?php echo $row['id']; ?>" data-toggle="button" aria-pressed="<?php echo (isset($row['status']) && $row['status'] == 1) ? true : false; ?>" autocomplete="off">
                                                <div class="handle"></div>
                                            </button>
                                        </td>
                                        <td>
                                            <a class="btn btn-behance p-2" href="product-category-master.php?id=<?php echo $row['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                        </td>
                                    </tr>
                                    <?php $i++; } ?>  
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
  
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <!-- change status js -->
  <script src="js/custom/statusupdate.js"></script>
  
  <!-- End custom js for this page-->
  
</body>


</html>
