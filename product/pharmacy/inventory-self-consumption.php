<!-- Author : Kartik Champaneriya -->
<!-- Date   : 03-08-2018 -->
<?php include('include/usertypecheck.php'); ?>

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
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
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
             <!-- Inventory Form ------------------------------------------------------------------------------------------------------>
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Self Consumption</h4>
                    <hr class="alert-dark">
                    <br>
                	<div id="self-more">
                		<div class="self-sub-more">
		                    <div class="form-group row">
		                  
		                      <div class="col-12 col-md-2">
		                        <label for="product_name">Product Name</label>
		                        <input type="text" class="form-control tags" name="product_name" id="product_name" placeholder="Product Name"> 
		                        <small class="text-danger empty-message0"></small>
		                      </div>
		                      <div class="col-12 col-md-2">
		                        <label for="batch">Batch</label>
		                        <input type="text" class="form-control" name="batch"  id="batch" placeholder="Batch"> 
		                      </div>
		                      <div class="col-12 col-md-2">
		                        <label for="qty">Qty</label>
		                        <input type="text" class="form-control" name="qty" id="qty" placeholder="Qty"> 
		                      </div>
		                      <div class="col-12 col-md-2">
		                        <label for="expiry">Expiry</label>
		                        <input type="text" class="form-control" name="expiry" id="expiry" placeholder="Expiry"> 
		                      </div>
		                      <div class="col-12 col-md-2">
		                        <label for="gst">GST %</label>
		                        <input type="text" class="form-control" name="gst" id="gst" placeholder="GST %"> 
		                      </div>
		                      <div class="col-12 col-md-2">
		                        <label for="units_strip">Units/Strip</label>
		                        <input type="text" class="form-control" name="units_strip"  id="units_strip" placeholder="Units/Strip"> 
		                      </div>
		                    </div>
		                    <div class="form-group row">
		                    	<div class="col-12 col-md-2">
			                        <label for="units_strip">Price/Strip</label>
			                        <input type="text" class="form-control" name="price_strip"  id="price_strip" placeholder="Price/Strip"> 
		                      	</div>
		                      	<div class="col-12 col-md-2">
			                        <label for="units_strip">Selling/Strip</label>
			                        <input type="text" class="form-control" name="selling_strip"  id="selling_strip" placeholder="Selling/Strip"> 
		                      	</div>
		                      	<div class="col-12 col-md-2">
			                        <label for="consumption">Consumption</label>
			                        <input type="text" class="form-control" name="consumption"  id="consumption" placeholder="Qty"> 
		                      	</div>
		                      	<div class="col-12 col-md-2">
			                        <label for="consumption">Note</label>
			                        <textarea class="form-control" name="note" id="note"></textarea>  
		                      	</div>
		                      	<div class="col-12 col-md-4 text-right" style="margin-top: 35px;">
		                      		<a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a>
		                      	</div>
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
	            <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Product Name"> 
	          </div>
	          <div class="col-12 col-md-2">
	            <label for="batch">Batch</label>
	            <input type="text" class="form-control" name="batch"  id="batch" placeholder="Batch"> 
	          </div>
	          <div class="col-12 col-md-2">
	            <label for="qty">Qty</label>
	            <input type="text" class="form-control" name="qty" id="qty" placeholder="Qty"> 
	          </div>
	          <div class="col-12 col-md-2">
	            <label for="expiry">Expiry</label>
	            <input type="text" class="form-control" name="expiry" id="expiry" placeholder="Expiry"> 
	          </div>
	          <div class="col-12 col-md-2">
	            <label for="gst">GST %</label>
	            <input type="text" class="form-control" name="gst" id="gst" placeholder="GST %"> 
	          </div>
	          <div class="col-12 col-md-2">
	            <label for="units_strip">Units/Strip</label>
	            <input type="text" class="form-control" name="units_strip"  id="units_strip" placeholder="Units/Strip"> 
	          </div>
	        </div>
	        <div class="form-group row">
	        	<div class="col-12 col-md-2">
	                <label for="units_strip">Price/Strip</label>
	                <input type="text" class="form-control" name="price_strip"  id="price_strip" placeholder="Price/Strip"> 
	          	</div>
	          	<div class="col-12 col-md-2">
	                <label for="units_strip">Selling/Strip</label>
	                <input type="text" class="form-control" name="selling_strip"  id="selling_strip" placeholder="Selling/Strip"> 
	          	</div>
	          	<div class="col-12 col-md-2">
	                <label for="consumption">Consumption</label>
	                <input type="text" class="form-control" name="consumption"  id="consumption" placeholder="Qty"> 
	          	</div>
	          	<div class="col-12 col-md-2">
	                <label for="consumption">Note</label>
	                <textarea class="form-control" name="note" id="note"></textarea>  
	          	</div>
	          	<div class="col-12 col-md-4 text-right" style="margin-top: 35px;">
	          		<a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product"><i class="fa fa-close mr-0 ml-0"></i></a>
	          		<a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a>
	        		
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
  <!-- End custom js for this page-->
</body>


</html>
