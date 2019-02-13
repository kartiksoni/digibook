<?php include('include/usertypecheck.php');?>
<?php
	$pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';

	if(isset($_POST['submit'])){
		if(isset($_POST['message']) && $_POST['message'] != ''){
			if(isset($_SESSION['smsdata']) && !empty($_SESSION['smsdata'])){
			    $message = $_POST['message'];
			    $data = $_SESSION['smsdata'];
			    foreach ($data as $key => $value) {
              	    # code...
              	    $send_sms = send_text_message($value['mobile'],$message);
              	    //print_r($send_sms);exit;
                }
				$_SESSION['msg']['success'] = 'Message sent succesfully.';
				header('location:sms-send.php');exit;
			}else{
				$_SESSION['msg']['error'] = 'Somthing was wrong! Try again.';
				header('location:sms-send.php');exit;
			}
		}else{
			$_SESSION['msg']['warning'] = 'Message is required!';
			header('Location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);exit;
		}
	}

	$_SESSION['smsdata'] = [];
	/*--------------------------------------------------SEND MSG VENDOR START--------------------------------------------------------*/
	if((isset($_GET['type']) && $_GET['type'] == 'selected_vendor') && (isset($_GET['data']) && !empty($_GET['data']))){
		$id = implode(',', $_GET['data']);
		$query = "SELECT id, name, mobile FROM ledger_master WHERE id IN (".$id.") AND pharmacy_id = '".$pharmacy_id."' AND status = 1 AND group_id = 14 ORDER BY name";
		$res = mysqli_query($conn, $query);
		
		if($res && mysqli_num_rows($res) > 0){
			$data = [];
			while ($row = mysqli_fetch_assoc($res)) {
				$arr['id'] = $row['id'];
				$arr['name'] = $row['name'];
				$arr['mobile'] = $row['mobile'];
				$arr['type'] = $_GET['type'];
				$data[] = $arr;
			}
			$_SESSION['smsdata'] = $data;
		}
	}elseif(isset($_GET['type']) && $_GET['type'] == 'all_vendor'){
		$query = "SELECT id, name, mobile FROM ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 AND group_id = 14 ORDER BY name";
		$res = mysqli_query($conn, $query);
		
		if($res && mysqli_num_rows($res) > 0){
			$data = [];
			while ($row = mysqli_fetch_assoc($res)) {
				$arr['id'] = $row['id'];
				$arr['name'] = $row['name'];
				$arr['mobile'] = $row['mobile'];
				$arr['type'] = $_GET['type'];
				$data[] = $arr;
			}
			$_SESSION['smsdata'] = $data;
		}
	}
	/*--------------------------------------------------SEND MSG VENDOR START--------------------------------------------------------*/


	/*--------------------------------------------------SEND MSG CUSTOMER START--------------------------------------------------------*/
	if((isset($_GET['type']) && $_GET['type'] == 'selected_customer') && (isset($_GET['data']) && !empty($_GET['data']))){
		$id = implode(',', $_GET['data']);
		$query = "SELECT id, name, mobile FROM ledger_master WHERE id IN (".$id.") AND pharmacy_id = '".$pharmacy_id."' AND status = 1 AND group_id = 10 ORDER BY name";
		$res = mysqli_query($conn, $query);
		
		if($res && mysqli_num_rows($res) > 0){
			$data = [];
			while ($row = mysqli_fetch_assoc($res)) {
				$arr['id'] = $row['id'];
				$arr['name'] = $row['name'];
				$arr['mobile'] = $row['mobile'];
				$arr['type'] = $_GET['type'];
				$data[] = $arr;
			}
			$_SESSION['smsdata'] = $data;
		}
	}elseif(isset($_GET['type']) && $_GET['type'] == 'all_customer'){
		$query = "SELECT id, name, mobile FROM ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 AND group_id = 10 AND is_cash = 0 ORDER BY name";
		$res = mysqli_query($conn, $query);
		
		if($res && mysqli_num_rows($res) > 0){
			$data = [];
			while ($row = mysqli_fetch_assoc($res)) {
				$arr['id'] = $row['id'];
				$arr['name'] = $row['name'];
				$arr['mobile'] = $row['mobile'];
				$arr['type'] = $_GET['type'];
				$data[] = $arr;
			}
			$_SESSION['smsdata'] = $data;
		}
	}
	/*--------------------------------------------------SEND MSG CUSTOMER START--------------------------------------------------------*/

	/*--------------------------------------------------SEND MSG DOCTOR START--------------------------------------------------------*/
	if((isset($_GET['type']) && $_GET['type'] == 'selected_doctor') && (isset($_GET['data']) && !empty($_GET['data']))){
		$id = implode(',', $_GET['data']);
		$query = "SELECT id, name, mobile FROM doctor_profile WHERE id IN (".$id.") AND pharmacy_id = '".$pharmacy_id."' AND status = 1 ORDER BY name";
		$res = mysqli_query($conn, $query);
		
		if($res && mysqli_num_rows($res) > 0){
			$data = [];
			while ($row = mysqli_fetch_assoc($res)) {
				$arr['id'] = $row['id'];
				$arr['name'] = $row['name'];
				$arr['mobile'] = $row['mobile'];
				$arr['type'] = $_GET['type'];
				$data[] = $arr;
			}
			$_SESSION['smsdata'] = $data;
		}
	}elseif(isset($_GET['type']) && $_GET['type'] == 'all_doctor'){
		$query = "SELECT id, name, mobile FROM doctor_profile WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 ORDER BY name";
		$res = mysqli_query($conn, $query);
		
		if($res && mysqli_num_rows($res) > 0){
			$data = [];
			while ($row = mysqli_fetch_assoc($res)) {
				$arr['id'] = $row['id'];
				$arr['name'] = $row['name'];
				$arr['mobile'] = $row['mobile'];
				$arr['type'] = $_GET['type'];
				$data[] = $arr;
			}
			$_SESSION['smsdata'] = $data;
		}
	}
	/*--------------------------------------------------SEND MSG DOCTOR START--------------------------------------------------------*/


	/*--------------------------------------------------SEND MSG CONTACT START--------------------------------------------------------*/
	if((isset($_GET['type']) && $_GET['type'] == 'selected_contact') && (isset($_GET['data']) && !empty($_GET['data']))){
		$id = implode(',', $_GET['data']);
		$query = "SELECT id, name, mobile FROM sms_phonebook WHERE id IN (".$id.") AND pharmacy_id = '".$pharmacy_id."' AND status = 1 ORDER BY name";
		$res = mysqli_query($conn, $query);
		
		if($res && mysqli_num_rows($res) > 0){
			$data = [];
			while ($row = mysqli_fetch_assoc($res)) {
				$arr['id'] = $row['id'];
				$arr['name'] = $row['name'];
				$arr['mobile'] = $row['mobile'];
				$arr['type'] = $_GET['type'];
				$data[] = $arr;
			}
			$_SESSION['smsdata'] = $data;
		}
	}elseif((isset($_GET['type']) && $_GET['type'] == 'selected_group') && (isset($_GET['data']) && !empty($_GET['data']))){
		$group = implode(',', $_GET['data']);
		$query = "SELECT id, name, mobile FROM sms_phonebook WHERE `group` IN (".$group.") AND pharmacy_id = '".$pharmacy_id."' AND status = 1 ORDER BY name";
		$res = mysqli_query($conn, $query);
		
		if($res && mysqli_num_rows($res) > 0){
			$data = [];
			while ($row = mysqli_fetch_assoc($res)) {
				$arr['id'] = $row['id'];
				$arr['name'] = $row['name'];
				$arr['mobile'] = $row['mobile'];
				$arr['type'] = $_GET['type'];
				$data[] = $arr;
			}
			$_SESSION['smsdata'] = $data;
		}
	}elseif(isset($_GET['type']) && $_GET['type'] == 'all_contact'){
		$query = "SELECT id, name, mobile FROM sms_phonebook WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 ORDER BY name";
		$res = mysqli_query($conn, $query);
		
		if($res && mysqli_num_rows($res) > 0){
			$data = [];
			while ($row = mysqli_fetch_assoc($res)) {
				$arr['id'] = $row['id'];
				$arr['name'] = $row['name'];
				$arr['mobile'] = $row['mobile'];
				$arr['type'] = $_GET['type'];
				$data[] = $arr;
			}
			$_SESSION['smsdata'] = $data;
		}
	}
	// /pr($_SESSION['smsdata']);exit;
	/*--------------------------------------------------SEND MSG CONTACT START--------------------------------------------------------*/
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Send Message</title>
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
	          	<div class="row">
	            	<!-- Service Master Form -->
		            <div class="col-md-12 grid-margin stretch-card">
		              <div class="card">
		                <div class="card-body">
		                	<?php 
		                		$countmessage = (isset($_SESSION['smsdata']) && !empty($_SESSION['smsdata'])) ? count($_SESSION['smsdata']) : 0;
		                		$lable = '';
		                		if(isset($_GET['type'])){
		                			if($_GET['type'] == 'selected_vendor' || $_GET['type'] == 'all_vendor'){
		                				$lable = 'Vendor';
		                			}elseif($_GET['type'] == 'selected_customer' || $_GET['type'] == 'all_customer'){
		                				$lable = 'Customer';
		                			}elseif($_GET['type'] == 'selected_doctor' || $_GET['type'] == 'all_doctor'){
		                				$lable = 'Doctor';
		                			}elseif($_GET['type'] == 'selected_doctor' || $_GET['type'] == 'all_doctor'){
		                				$lable = 'Doctor';
		                			}elseif($_GET['type'] == 'selected_contact' || $_GET['type'] == 'all_contact' || $_GET['type'] == 'selected_group'){
		                				$lable = 'Contact';
		                			}

		                		}
		                	?>
		                	<h4 class="card-title">Send Message to <?php echo (isset($countmessage)) ? $countmessage : 0; ?> <?php echo (isset($lable)) ? $lable : ''; ?><?php echo (isset($countmessage) && $countmessage > 1) ? 's' : ''; ?></h4><hr class="alert-dark"><br>
			                <form class="forms-sample" method="POST" autocomplete="off">
			                    <div class="form-group row">
			                        <div class="col-12 col-md-4">
			                          <label for="exampleInputName1">Message <span class="text-danger">*</span></label>
			                          <textarea name="message" class="form-control" placeholder="Enter Message.." rows="5" required></textarea>
			                        </div>
			                    </div>
			                    <div class="row">
			                      <div class="col-md-12">
			                        <a href="sms-send.php" class="btn btn-light">Cancel</a>
			                        <button name="submit" type="submit" name="submit" class="btn btn-success">Send</button>
			                      </div>
			                    </div>
			                </form>
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
