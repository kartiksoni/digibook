<?php $title = "Self Consumption"; ?>
<?php include('include/usertypecheck.php');
include('include/permission.php');

$owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
$admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
$pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
$financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

if(isset($_GET['id'])){
  $id = $_GET['id'];
    $editQry = "SELECT * FROM `self_consumption` WHERE id='".$id."' AND pharmacy_id='".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
    $edit = mysqli_query($conn,$editQry);
    $edit = mysqli_fetch_assoc($edit);

}
 
if(isset($_POST['submit'])){
  $user_id = $_SESSION['auth']['id'];
  $count = count($_POST['product_name']);
  for($i=0;$i<$count;$i++){

    $product_id = "";
        if(isset($_POST["product_id"][$i])){
            $product_id = $_POST["product_id"][$i];
        }

        $purchase_id = "";
        if(isset($_POST["purchase_id"][$i])){
            $purchase_id = $_POST["purchase_id"][$i];
        }

        $batch = "";
        if(isset($_POST["batch"][$i])){
            $batch = $_POST["batch"][$i];
        }

        $qty = "";
        if(isset($_POST["qty"][$i])){
            $qty = $_POST["qty"][$i];
        }

        $expiry = "";
        if(isset($_POST["expiry"][$i])){
            $expiry = date('Y-m-d',strtotime(str_replace('/','-',$_POST["expiry"][$i])));
        }

        $gst = "";
        if(isset($_POST["gst"][$i])){
            $gst = $_POST["gst"][$i];
        }

        $units_strip = "";
        if(isset($_POST["units_strip"][$i])){
            $units_strip = $_POST["units_strip"][$i];
        }

        $price_strip = "";
        if(isset($_POST["price_strip"][$i])){
            $price_strip = $_POST["price_strip"][$i];
        }

        $consumption = "";
        if(isset($_POST["consumption"][$i])){
            $consumption = $_POST["consumption"][$i];
        }

        $note = "";
        if(isset($_POST["note"][$i])){
            $note = $_POST["note"][$i];
        }

        $ins_product = "INSERT INTO `self_consumption`(`owner_id`,`admin_id`,`pharmacy_id`,`financial_id`,`product_id`, `purchase_id`, `batch`, `qty`, `expiry`, `gst`,`units_strip`, `price_strip`, `consumption`, `note`, `createdat`, `createdby`) VALUES ('".$owner_id."','".$admin_id."','".$pharmacy_id."','".$financial_id."','".$product_id."','".$purchase_id."',  '".$batch."', '".$qty."', '".$expiry."', '".$gst."','".$units_strip."', '".$price_strip."', '".$consumption."', '".$note."', '".date('Y-m-d H:i:s')."', '".$user_id."')";
        $in = mysqli_query($conn,$ins_product);

  }

  if($in){
    $_SESSION['msg']['success'] = 'Self Consumption Added Successfully.';
    header('location:inventory-self-consumption.php');exit;
  }else{
    $_SESSION['msg']['fail'] = 'Self Consumption Added Failed.';
    header('location:inventory-self-consumption.php');exit; 
  }
  /*$_SESSION['msg']['success'] = 'Self Consumption Added Successfully.';
  $_SESSION['msg']['fail'] = 'Self Consumption Added Failed.';
  header('location:purchase.php');exit; */
}
?>

<?php 
if(isset($_POST['edit'])){
  $user_id = $_SESSION['auth']['id'];
  $product_id = $_POST["product_id"][0];
  $purchase_id = $_POST["purchase_id"][0];
  $batch = $_POST["batch"][0];
  $qty = $_POST["qty"][0];
  $expiry = date('Y-m-d',strtotime(str_replace('/','-',$_POST["expiry"][0])));
  $gst = $_POST["gst"][0];
  $units_strip = $_POST["units_strip"][0];
  $price_strip = $_POST["price_strip"][0];
  $consumption = $_POST["consumption"][0];
  $note = $_POST["note"][0];
  $updateQry = "UPDATE `self_consumption` SET `product_id`='".$product_id."',`batch`='".$batch."',`qty`='".$qty."',`expiry`='".$expiry."',`gst`='".$gst."',`units_strip`='".$units_strip."',`price_strip`='".$price_strip."',`consumption`='".$consumption."',`note`='".$note."',`updatedat`='".date('Y-m-d H:i:s')."',`updateby`='".$user_id."' WHERE id='".$_GET['id']."'";
    $updateInsert = mysqli_query($conn,$updateQry);
    if($updateInsert){
      $_SESSION['msg']['success'] = 'Self Consumption Updated Successfully.';
    header('location:inventory-self-consumption.php');exit;
    }else{
      $_SESSION['msg']['fail'] = 'Self Consumption Updated Failed.';
    header('location:inventory-self-consumption.php');exit; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Self Consumption</title>
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
  <link rel="stylesheet" href="vendors/iconfonts/simple-line-icon/css/simple-line-icons.css">
  <link rel="stylesheet" href="css/parsley.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/toggle/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
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
            
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Self Consumption</h4>
                      <hr class="alert-dark">
                      <br>
                  <div class="row">
                    
                      <div class="col-12 col-md-10 col-sm-12">
                          <div class="enventory">
                            <a href="inventory.php?reset=all" class="btn btn-dark active">Inventory</a>
                            <?php if((isset($user_sub_module) && in_array("Inventory Adjustment", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                              <a href="inventory-adjustment.php" class="btn btn-dark active">Inventory Adjustment</a>
                            <?php } if((isset($user_sub_module) && in_array("Update Inventory", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                              <!--<a href="#" class="btn btn-dark">Update Inventory </a>-->
                            <?php } if((isset($user_sub_module) && in_array("Inventory Setting", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                              <!--<a href="#" class="btn btn-dark">Inventory Setting </a>-->
                            <?php } if((isset($user_sub_module) && in_array("Product Master", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                              <a href="product-master.php" class="btn btn-dark">Product Master </a>
                            <?php } if((isset($user_sub_module) && in_array("Self Consumption", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                              <a href="inventory-self-consumption.php" class="btn btn-dark">Self Consumption </a>
                            <?php } ?>
                          </div>           
                      </div> 
                      
                      <!--<div class="col-12 col-md-2">
                        <button type="button" class="btn btn-grey-1 btn-rounded pull-right"><strong>*HSN Code Reference</strong></button>
                      </div>-->
                    
                    </div>

                <form action="" method="POST" autocomplete="off">
                    <div class="card-body">
                      
                    <div id="self-more">
                      <div class="self-sub-more">
                          <div class="form-group row">
                        
                            <div class="col-12 col-md-2">
                              <label for="product_name">Product Name<span class="text-danger">*</span></label>
                              <?php 
                              if(isset($edit['product_id'])){
                                $productQry = "SELECT * FROM `product_master` WHERE id='".$edit['product_id']."'";
                                $product = mysqli_query($conn,$productQry);
                                $product = mysqli_fetch_assoc($product);
                              }
                              ?>
                              <input type="text" value="<?php echo (isset($product['product_name'])) ? $product['product_name'] : ''; ?>" class="form-control tags" required="" name="product_name[]" id="product_name" placeholder="Product Name"> 
                              <small class="text-danger producterror empty-message0"></small>
                              <input type="hidden" name="product_id[]" value="<?php echo (isset($edit['product_id'])) ? $edit['product_id'] : ''; ?>" class="product_id">
                              <input type="hidden" name="purchase_id[]" value="<?php echo (isset($edit['purchase_id'])) ? $edit['purchase_id'] : ''; ?>" class="purchase_id">
                            </div>
                            <div class="col-12 col-md-2">
                              <label for="batch">Batch</label>
                              <input type="text" readonly="" value="<?php echo (isset($edit['batch'])) ? $edit['batch'] : ''; ?>" class="form-control batch" name="batch[]"  id="batch" placeholder="Batch"> 
                            </div>
                            <div class="col-12 col-md-2">
                              <label for="qty">Qty</label>
                              <input type="text" readonly="" value="<?php echo (isset($edit['qty'])) ? $edit['qty'] : ''; ?>" class="form-control qty" name="qty[]" id="qty" placeholder="Qty"> 
                            </div>
                            <div class="col-12 col-md-2">
                              <label for="expiry">Expiry</label>
                              <input type="text" readonly="" value="<?php echo (isset($edit['expiry'])) ? date('d/m/Y',strtotime($edit['expiry'])) : ''; ?>" class="form-control expiry" name="expiry[]" id="expiry" placeholder="Expiry"> 
                            </div>
                            <div class="col-12 col-md-2">
                              <label for="gst">GST %</label>
                              <input type="text" readonly="" value="<?php echo (isset($edit['gst'])) ? $edit['gst'] : ''; ?>" class="form-control gst" name="gst[]" id="gst" placeholder="GST %"> 
                            </div>
                            <div class="col-12 col-md-2">
                              <label for="units_strip">Units/Strip</label>
                              <input type="text" readonly="" value="<?php echo (isset($edit['units_strip'])) ? $edit['units_strip'] : ''; ?>" class="form-control units_strip" name="units_strip[]"  id="units_strip" placeholder="Units/Strip"> 
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-12 col-md-2">
                                <label for="units_strip">Price/Strip</label>
                                <input type="text" readonly="" value="<?php echo (isset($edit['price_strip'])) ? $edit['price_strip'] : ''; ?>" class="form-control price_strip" name="price_strip[]"  id="price_strip" placeholder="Price/Strip"> 
                              </div>
                              <div class="col-12 col-md-2">
                                <label for="consumption">Consumption</label>
                                <input type="text" value="<?php echo (isset($edit['consumption'])) ? $edit['consumption'] : '1'; ?>" class="form-control consumption" name="consumption[]"  id="consumption" placeholder="Qty"> 
                              </div>
                              <div class="col-12 col-md-2">
                                <label for="consumption">Note</label>
                                <textarea class="form-control note" name="note[]" id="note"><?php echo (isset($edit['note'])) ? $edit['note'] : ''; ?></textarea>  
                              </div>
                              <?php 
                              if(!isset($_GET['id'])){
                              ?>
                              <div class="col-12 col-md-6 text-right" style="margin-top: 35px;">
                                  <a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product remove_last" style="display: none;"><i class="fa fa-close mr-0 ml-0"></i></a>
                                <a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a>
                              </div>
                            <?php } ?>
                          </div>
                          
                      </div>
                    </div>
                    <div class="col-md-12">
                          <a href="inventory-self-consumption.php" class="btn btn-light pull-left">Back</a>
                          <?php 
                          if(isset($_GET['id'])){
                            ?>
                            <button type="submit" name="edit" class="btn btn-success pull-right">Edit</button>
                            <?php
                          }else{
                          ?>
                        <button type="submit" name="submit" class="btn btn-success pull-right">Submit</button>
                        <?php } ?>
                      </div>
                  </div>
                </form>
                <div class="col mt-3">
                    <h4 class="card-title">Self Consumption</h4>
                    <hr class="alert-dark">
                       <div class="row">
                            <div class="col-12">
                              <table id="order-listing1" class="table datatable">
                                <thead>
                                  <tr>
                                      <th>Sr No</th>
                                      <th>Product Name</th>
                                      <th>Batch</th>
                                      <th>Consumption</th>
                                      <th>Expiry</th>
                                      <th>Price/Strip</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts -->   
                                  <?php 
                                  $i = 1;
                                  $financialQry = "SELECT slf.*, pm.product_name FROM `self_consumption` slf INNER JOIN product_master pm ON slf.product_id = pm.id WHERE slf.pharmacy_id = '".$pharmacy_id."' AND slf.financial_id = '".$financial_id."' ORDER BY slf.id DESC";
                                  $financial = mysqli_query($conn,$financialQry);
                                  while($row = mysqli_fetch_assoc($financial)){
                                  ?>
                                  <tr>
                                      <td><?php echo $i; ?></td>
                                      <td><?php echo $row['product_name']; ?></td>
                                      <td><?php echo $row['batch']; ?></td>
                                      <td><?php echo $row['consumption']; ?></td>
                                      <td><?php echo date("d-m-Y",strtotime($row['expiry'])); ?></td>
                                      <td><?php echo $row['price_strip']; ?></td>
                                      <td>
                                        <a class="btn  btn-behance p-2" href="inventory-self-consumption.php?id=<?php echo $row['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
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

        <div id="copy-html" style="display: none;">
          
          <div class="self-sub-more">
            <hr>
           <div class="form-group row">       
            <div class="col-12 col-md-2">
              <label for="product_name">Product Name<span class="text-danger">*</span></label>
              <input type="text" class="form-control tags" required="" name="product_name[]" id="product_name" placeholder="Product Name">
              <small class="text-danger producterror empty-message##PRODUCTCOUNT##"></small>
              <input type="hidden" name="product_id[]" class="product_id">
          <input type="hidden" name="purchase_id[]" class="purchase_id">
            </div>
            <div class="col-12 col-md-2">
              <label for="batch">Batch</label>
              <input type="text" readonly="" class="form-control batch" name="batch[]"  id="batch" placeholder="Batch"> 
            </div>
            <div class="col-12 col-md-2">
              <label for="qty">Qty</label>
              <input type="text" readonly="" class="form-control qty" name="qty[]" id="qty" placeholder="Qty"> 
            </div>
            <div class="col-12 col-md-2">
              <label for="expiry">Expiry</label>
              <input type="text" readonly="" class="form-control expiry" name="expiry[]" id="expiry" placeholder="Expiry"> 
            </div>
            <div class="col-12 col-md-2">
              <label for="gst">GST %</label>
              <input type="text" readonly="" class="form-control gst" name="gst[]" id="gst" placeholder="GST %"> 
            </div>
            <div class="col-12 col-md-2">
              <label for="units_strip">Units/Strip</label>
              <input type="text" readonly="" class="form-control units_strip" name="units_strip[]"  id="units_strip" placeholder="Units/Strip"> 
            </div>
          </div>
          <div class="form-group row">
            <div class="col-12 col-md-2">
                  <label for="units_strip">Price/Strip</label>
                  <input type="text" readonly="" class="form-control price_strip" name="price_strip[]"  id="price_strip" placeholder="Price/Strip"> 
              </div>
              <div class="col-12 col-md-2">
                  <label for="consumption">Consumption</label>
                  <input type="text" class="form-control consumption" value="1" name="consumption[]"  id="consumption" placeholder="Qty"> 
              </div>
              <div class="col-12 col-md-2">
                  <label for="consumption">Note</label>
                  <textarea class="form-control note" name="note[]" id="note"></textarea>  
              </div>
              <div class="col-12 col-md-6 text-right" style="margin-top: 35px;">
                <a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product"><i class="fa fa-close mr-0 ml-0"></i></a>
                <a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a>
              
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
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('.datatable').DataTable();
  </script>
  
  <script src="js/custom/inventory-self-consumption.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>
<script src="js/custom/statusupdate.js"></script>


  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  <!-- End custom js for this page-->
</body>


</html>
