<?php include('include/usertypecheck.php');?>
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
  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" />
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
	                    <div class="row">
		                    <div class="col-12">
		                        <div class="enventory">
		                            <a href="order.php" class="btn btn-dark btn-fw active">Order</a>
		                            <a href="order-list-tab.php" class="btn btn-dark btn-fw">List</a>
		                            <a href="missed-sales-order.php" class="btn btn-dark btn-fw">Missed Sales Order</a>
		                            <a href="#" class="btn btn-dark btn-fw">Settings</a>
		                        </div>  
		                    </div> 
	                    </div>
                	</div>
               	</div>
            </div>
            
             <!-- Table ------------------------------------------------------------------------------------------------------>
            
            <div class="col-md-12 grid-margin stretch-card">
              	<div class="card">
	                <div class="card-body">
	                    <div class="col mt-3">
		                	<div class="row">
		                    	<div class="col-12">
		                          <table class="table datatable">
		                            <thead>
		                              <tr>
		                                  <th>Sr. No</th>
		                                  <th>Date</th>
		                                  <th>Product Name</th>
		                                  <th>Quantity</th>
		                                  <th>Unit/Strip</th>
		                              </tr>
		                            </thead>
		                            <tbody>
		                            	<?php 
		                            		$query = "SELECT mi.id, mi.qty, mi.unit, mi.created, pm.product_name FROM missed_order mi INNER JOIN product_master pm ON mi.product_id = pm.id ORDER BY mi.id DESC";
		                            		
		                            		$res = mysqli_query($conn, $query);
		                            		if($res && mysqli_num_rows($res) > 0){
		                            			$i = 1;
		                            			while ($row = mysqli_fetch_array($res)) {
		                            	?>
		                            		<tr>
		                            			<td><?php echo $i; ?></td>
		                            			<td><?php echo (isset($row['created']) && $row['created'] != '') ? date('d/m/Y', strtotime($row['created'])) : ''; ?></td>
		                            			<td><?php echo (isset($row['product_name'])) ? $row['product_name'] : ''; ?></td>
		                            			<td><?php echo (isset($row['qty'])) ? $row['qty'] : ''; ?></td>
		                            			<td><?php echo (isset($row['unit'])) ? $row['unit'] : ''; ?></td>
		                            		</tr>
		                            	<?php $i++; } } ?>
		                            </tbody>
		                          </table>
		                        </div>
		                     </div>
	                    </div>
	                    <hr>
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
  <script src="js/custom/order_list_tab.js"></script>
  <script src="js/jquery-ui.js"></script>

  
  <!-- Custom js for this page Modal Box-->
  <script src="js/modal-demo.js"></script>
  
  
  <!-- Datepicker Initialise-->
 <script>
    $('.datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      autoclose: true,
      dateFormat: "dd/mm/yyyy"
    });
 </script>
 
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script> 
  
  <script>
  	 $('.datatable').DataTable();
  </script>
  
  
  <!-- End custom js for this page-->
</body>


</html>
