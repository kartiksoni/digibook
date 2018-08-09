<?php include('include/usertypecheck.php');
if(isset($_GET['id'])){
	$id = $_GET['id'];
  	$editQry = "SELECT * FROM `adjustment` WHERE id='".$id."' ORDER BY id DESC LIMIT 1";
  	$edit = mysqli_query($conn,$editQry);
  	$edit = mysqli_fetch_assoc($edit);

}
 
if(isset($_POST['submit'])){
	$user_id = $_SESSION['auth']['id'];
	$count = count($_POST['product_name']);
  $type = $_POST['action'];
	for($i=0;$i<$count;$i++){

		$product_id = "";
        if(isset($_POST["product_id"][$i])){
            $product_id = $_POST["product_id"][$i];
        }

        $purchase_id = "";
        if(isset($_POST["purchase_id"][$i])){
            $purchase_id = $_POST["purchase_id"][$i];
        }

        $mrp = "";
        if(isset($_POST["mrp"][$i])){
            $mrp = $_POST["mrp"][$i];
        }

        $mfg_co = "";
        if(isset($_POST["mfg_co"][$i])){
            $mfg_co = $_POST["mfg_co"][$i];
        }

        $batch_no = "";
        if(isset($_POST["batch_no"][$i])){
            $batch_no = $_POST["batch_no"][$i];
        }

        $expiry = "";
        if(isset($_POST["expiry"][$i])){
            $expiry = $_POST["expiry"][$i];
        }

        $qty = "";
        if(isset($_POST["qty"][$i])){
            $qty = $_POST["qty"][$i];
        }

        $remark = "";
        if(isset($_POST["remark"][$i])){
            $remark = $_POST["remark"][$i];
        }

        $ins_product = "INSERT INTO `adjustment` (`product_id`, `purchase_id`, `mrp`, `mfg_co`, `batch_no`, `expiry`,`qty`, `remark`,`type`, `created_at`, `created_by`) VALUES ('".$product_id."','".$purchase_id."',  '".$mrp."', '".$mfg_co."', '".$batch_no."', '".$expiry."','".$qty."', '".$remark."','".$type."', '".date('Y-m-d H:i:s')."', '".$user_id."')";	
        $in = mysqli_query($conn,$ins_product);

	}

	if($in){
		$_SESSION['msg']['success'] = 'Adjustment Added Successfully.';
		header('location:inventory-adjustment.php');exit;
	}else{
		$_SESSION['msg']['fail'] = 'Adjustment Added Failed.';
		header('location:inventory-adjustment.php');exit; 
	}
	/*$_SESSION['msg']['success'] = 'Self Consumption Added Successfully.';
	$_SESSION['msg']['fail'] = 'Self Consumption Added Failed.';
	header('location:purchase.php');exit; */
}
?>

<?php 
if(isset($_POST['edit'])){
  $type = $_POST['action'];
	$user_id = $_SESSION['auth']['id'];
	$product_id = $_POST["product_id"][0];
	$purchase_id = $_POST["purchase_id"][0];
  $mrp = $_POST["mrp"][0];
  $mfg_co = $_POST["mfg_co"][0];
  $batch_no = $_POST["batch_no"][0];
  $expiry = $_POST["expiry"][0];
  $qty = $_POST["qty"][0];
  $remark = $_POST["remark"][0];

	$updateQry = "UPDATE `adjustment` SET `product_id`='".$product_id."',`purchase_id`='".$purchase_id."',`mrp`='".$mrp."',`mfg_co`='".$mfg_co."',`batch_no`='".$batch_no."',`expiry`='".$expiry."',`qty`='".$qty."',`remark`='".$remark."',`type`='".$type."',`updated_at`='".date('Y-m-d H:i:s')."',`updated_by`='".$user_id."' WHERE id='".$_GET['id']."'";
  	$updateInsert = mysqli_query($conn,$updateQry);
  	if($updateInsert){
  		$_SESSION['msg']['success'] = 'Adjustment Updated Successfully.';
		header('location:inventory-adjustment.php');exit;
  	}else{
  		$_SESSION['msg']['fail'] = 'Adjustment Updated Failed.';
		header('location:inventory-adjustment.php');exit; 
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
        	<?php include('include/flash.php'); ?>
         	<div class="row">
            
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Inventory Adjustment</h4>
                      <hr class="alert-dark">
                      <br>
                  <div class="row">
                    
                      <div class="col-12 col-md-10 col-sm-12">
                          <div class="enventory">
                              <a href="inventory.php" class="btn btn-dark">Inventory</a>
                              <a href="inventory-adjustment.php" class="btn btn-dark active">Inventory Adjustment</a>
                              <a href="#" class="btn btn-dark">Update Inventory </a>
                              <a href="#" class="btn btn-dark">Inventory Setting </a>
                              <a href="#" class="btn btn-dark">Product Master </a>
                              <a href="inventory-self-consumption.php" class="btn btn-dark">Self Consumption </a>
                          </div>          
                      </div> 
                      
                      <div class="col-12 col-md-2">
                        <button type="button" class="btn btn-grey-1 btn-rounded pull-right"><strong>*HSN Code Reference</strong></button>
                      </div>
                    
                    </div>

                <form action="" method="POST">
                  	<div class="card-body">
	                    <div class="form-group row">
                          <div class="col-12 col-md-4">
                              <div class="row no-gutters">
                                  <div class="col">
                                      <div class="form-radio">
                                      <label class="form-check-label">
                                      <input type="radio" <?php if(isset($edit['type']) && $edit['type'] == "inward"){echo "checked";}else{echo"checked";} ?> class="form-check-input" name="action" id="optionsRadios1" value="inward">
                                      Inward
                                      </label>
                                      </div>
                                  </div>
                                  
                                  <div class="col">
                                      <div class="form-radio">
                                      <label class="form-check-label">
                                      <input type="radio" <?php if(isset($edit['type']) && $edit['type'] == "outward"){echo "checked";} ?> class="form-check-input" name="action" id="optionsRadios2" value="outward">
                                      Outward
                                      </label>
                                      </div>
                                  </div>
                              
                              </div>
                          </div>
                      </div>
	                	<div id="self-more">
	                		<div class="self-sub-more">
			                    <div class="form-group row">
			                  
			                      <div class="col-12 col-md-2">
			                        <label for="product_name">Product Name</label>
			                        <?php 
			                        if(isset($edit['product_id'])){
				                        $productQry = "SELECT * FROM `product_master` WHERE id='".$edit['product_id']."'";
          									  	$product = mysqli_query($conn,$productQry);
          									  	$product = mysqli_fetch_assoc($product);
          								  	}
			                        ?>
			                        <input type="text" value="<?php echo (isset($product['product_name'])) ? $product['product_name'] : ''; ?><?php if(isset($_GET['id'])){echo "-";} ?><?php echo (isset($edit['batch_no'])) ? $edit['batch_no'] : ''; ?>" class="form-control tags" required="" name="product_name[]" id="product_name" placeholder="Product Name"> 
			                        <small class="text-danger empty-message0"></small>
			                        <input type="hidden" name="product_id[]" value="<?php echo $edit['product_id']; ?>" class="product_id">
			                        <input type="hidden" name="purchase_id[]" value="<?php echo $edit['purchase_id']; ?>" class="purchase_id">
			                      </div>
			                      <div class="col-12 col-md-2">
			                        <label for="mrp">MRP</label>
			                        <input type="text" readonly=""  value="<?php echo (isset($edit['mrp'])) ? $edit['mrp'] : ''; ?>" class="form-control mrp" name="mrp[]"  id="mrp" placeholder="MRP"> 
			                      </div>
			                      <div class="col-12 col-md-2">
			                        <label for="mfg_co">Mfg Co</label>
			                        <input type="text" readonly="" value="<?php echo (isset($edit['mfg_co'])) ? $edit['mfg_co'] : ''; ?>" class="form-control mfg_co" name="mfg_co[]" id="mfg_co" placeholder="Mfg Co"> 
			                      </div>
                             <div class="col-12 col-md-2">
                              <label for="batch_no">Batch No</label>
                              <input type="text" readonly="" value="<?php echo (isset($edit['batch_no'])) ? $edit['batch_no'] : ''; ?>" class="form-control batch_no" name="batch_no[]" id="batch_no" placeholder="Batch No"> 
                            </div>
			                      <div class="col-12 col-md-2">
			                        <label for="expiry">Expiry</label>
			                        <input type="text" readonly="" value="<?php echo (isset($edit['expiry'])) ? $edit['expiry'] : ''; ?>" class="form-control expiry" name="expiry[]" id="expiry" placeholder="Expiry"> 
			                      </div>
                            <div class="col-12 col-md-2">
                                <label for="qty">Qty</label>
                                <input type="text" value="<?php echo (isset($edit['qty'])) ? $edit['qty'] : ''; ?>" class="form-control qty" name="qty[]"  id="qty" placeholder="Qty"> 
                              </div>
			                      
			                    </div>
			                    <div class="form-group row">
			                      	
			                      	<div class="col-12 col-md-2">
				                        <label for="consumption">Remark</label>
				                        <textarea class="form-control remark" name="remark[]" id="remark"><?php echo (isset($edit['remark'])) ? $edit['remark'] : ''; ?></textarea>  
			                      	</div>
                              <div class="col-12 col-md-2"></div>
                              <div class="col-12 col-md-2"></div>
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
	                      	<a href="view-purchase.php" type="button" class="btn btn-light pull-left">Back</a>
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
                    <h4 class="card-title">Inventory Adjustment</h4>
                    <hr class="alert-dark">
                       <div class="row">
                            <div class="col-12">
                              <table id="order-listing1" class="table datatable">
                                <thead>
                                  <tr>
                                      <th>Sr No</th>
                                      <th>Product Name</th>
                                      <th>MRP</th>
                                      <th>Mfg Co</th>
                                      <th>Qty</th>
                                      <th>Type</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts -->   
                                  <?php 
                                  $i = 1;
                                  $adjustmentQry = "SELECT * FROM `adjustment` ORDER BY id DESC";
                                  $adjustment = mysqli_query($conn,$adjustmentQry);
                                  while($row = mysqli_fetch_assoc($adjustment)){
                                  ?>
                                  <tr>
                                      <td><?php echo $i; ?></td>
                                      <?php 
                                      $product_id = "SELECT * FROM `product_master` WHERE id='".$row['product_id']."'";
                                  	  $product = mysqli_query($conn,$product_id);
                                  	  $row1 = mysqli_fetch_assoc($product);
                                      ?>
                                      <td><?php echo $row1['product_name']; ?></td>
                                      <td><?php echo $row['mrp']; ?></td>
                                      <td><?php echo $row['mfg_co']; ?></td>
                                      <td><?php echo $row['qty']; ?></td>
                                      <td><?php echo $row['type']; ?></td>
                                      <td>
                                        <a href="inventory-adjustment.php?id=<?php echo $row['id']; ?>" title="edit"><i class="fa fa-edit"></i></a>
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
	            <label for="product_name">Product Name</label>
	            <input type="text" class="form-control tags" required="" name="product_name[]" id="product_name" placeholder="Product Name">
	            <small class="text-danger empty-message##PRODUCTCOUNT##"></small>
	            <input type="hidden" name="product_id[]" class="product_id">
			        <input type="hidden" name="purchase_id[]" class="purchase_id">
	          </div>
	          <div class="col-12 col-md-2">
              <label for="mrp">MRP</label>
              <input type="text" readonly="" class="form-control mrp" name="mrp[]"  id="mrp" placeholder="MRP"> 
            </div>
	          <div class="col-12 col-md-2">
              <label for="mfg_co">Mfg Co</label>
              <input type="text" readonly="" class="form-control mfg_co" name="mfg_co[]" id="mfg_co" placeholder="Mfg Co"> 
            </div>
	          <div class="col-12 col-md-2">
              <label for="batch_no">Batch No</label>
              <input type="text" readonly="" class="form-control batch_no" name="batch_no[]" id="batch_no" placeholder="Batch No"> 
            </div>
	          <div class="col-12 col-md-2">
              <label for="expiry">Expiry</label>
              <input type="text" readonly="" class="form-control expiry" name="expiry[]" id="expiry" placeholder="Expiry"> 
            </div>
	          <div class="col-12 col-md-2">
              <label for="qty">Qty</label>
              <input type="text" value="" class="form-control qty" name="qty[]"  id="qty" placeholder="Qty"> 
            </div>
	        </div>
	        <div class="form-group row">
	        	<div class="col-12 col-md-2">
              <label for="consumption">Remark</label>
              <textarea class="form-control remark" name="remark[]" id="remark"></textarea>  
            </div>
            <div class="col-12 col-md-2"></div>
            <div class="col-12 col-md-2"></div>
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
  <script src="js/custom/inventory-adjustment.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>
<script src="js/custom/statusupdate.js"></script>
  <!-- End custom js for this page-->
</body>


</html>
