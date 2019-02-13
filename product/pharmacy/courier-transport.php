<?php $title = "Courier Transport"; ?>
<?php include('include/usertypecheck.php');
include('include/permission.php');

if (isset($_POST['submit'])) {
   $user_id = $_SESSION['auth']['id'];
   $name = $_POST['name'];
   $c_name = $_POST['c_name'];
   $phone = $_POST['phone'];
   $mobile = $_POST['mobile'];
   $address = $_POST['address'];
   $address1 = $_POST['address1'];
   $address2 = $_POST['address2'];
   $state = $_POST['state'];
   $city = $_POST['city']; 
   $pin = $_POST['pincode'];
   $status = $_POST['status'];
   $email = $_POST['mail'];
   $fax = $_POST['fax'];
   $pan = $_POST['pan'];
   $gst = $_POST['gst'];
   $remark = $_POST['remark'];
   $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : NULL;
   $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : NULL;
   $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
   $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;

   if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
     $editid = $_REQUEST['id'];
     $editqry = "UPDATE `courier_transport` SET `name`= '".$name."',`c_name`='".$c_name."', `phone_no`= '".$phone."', `mobile_no`= '".$mobile."', `address`= '".$address."', `address_line1`= '". $address1."', `address_line2`= '".$address2."', `state`= '".$state."', `city`= '".$city."',`pincode`= '".$pin."', `status`= '".$status."', `email`= '". $email."', `fax_no`= '".$fax."', `pan_no`= '".$pan."', `gst_no`=      '".$gst."', `remark`= '".$remark."', `modified`= '".date('Y-m-d H:i:s')."', `modifiedby`= '".$user_id."' WHERE id = '".$editid."'";

     $editrun = mysqli_query($conn, $editqry);

     if($editrun){
      $_SESSION['msg']['success'] = 'Courier-transport Updated Successfully.';
      header('location:courier-transport.php');exit;
     }
     else{
      $_SESSION['msg']['fail'] = 'Courier-transport Updated fail.';
      header('location:courier-transport.php');exit;
     }
   }

   else{
   $addqry = "INSERT INTO `courier_transport`(`owner_id`, `admin_id`, `pharmacy_id`, `financial_id`, `name`,`c_name`, `phone_no`, `mobile_no`, `address`, `address_line1`, `address_line2`, `state`, `city`, `pincode`, `status`, `email`, `fax_no`, `pan_no`, `gst_no`, `remark`, `created`, `createdby`) VALUES ('".$owner_id."', '".$admin_id."', '".$pharmacy_id."', '".$financial_id."', '".$name."','".$c_name."' ,'".$phone."', '".$mobile."', '".$address."', '".$address1."', '".$address2."', '".$state."', '".$city."', '".$pin."', '".$status."', '".$email."', '".$fax."', '".$pan."', '".$gst."', '".$remark."', '".date('Y-m-d H:i:s')."', '".$user_id."')";
   
   $addrun = mysqli_query($conn, $addqry);
   
   if($addrun){
    $_SESSION['msg']['success'] = 'Courier-transport insert Successfully.';
    header('location:courier-transport.php');exit;
   }
   else{
    $_SESSION['msg']['fail'] = 'Courier-transport added fail.';
    header('location:courier-transport.php');exit;
   }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks | Courier Transport</title>
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
  <link rel="stylesheet" href="vendors/iconfonts/simple-line-icon/css/simple-line-icons.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/toggle/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
  

  
</head>
<body>
  <div class="container-scroller">
  
    <!-- Topbar -->
        <?php include "include/topbar.php" ?>
    
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
    
        
        
        <!-- partial:partials/_settings-panel.html -->
        
        <!--<div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="mdi mdi-settings"></i></div>
        <div id="theme-settings" class="settings-panel">
        <i class="settings-close mdi mdi-close"></i>
        <p class="settings-heading">SIDEBAR SKINS</p>
        <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Light</div>
        <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark</div>
        <p class="settings-heading mt-2">HEADER SKINS</p>
        <div class="color-tiles mx-0 px-4">
          <div class="tiles primary"></div>
          <div class="tiles success"></div>
          <div class="tiles warning"></div>
          <div class="tiles danger"></div>
          <div class="tiles pink"></div>
          <div class="tiles info"></div>
          <div class="tiles dark"></div>
          <div class="tiles default"></div>
        </div>
        </div>
        </div>-->
        
        
        <!-- Right Sidebar -->
        <?php include "include/sidebar-right.php" ?>
        
       
       <!-- Left Navigation -->
        <?php include "include/sidebar-nav-left.php" ?>
        
        
      
      
      <div class="main-panel">
      
        <div class="content-wrapper">
          <span id="errormsg"></span>
          <div class="row">
          
          
           <!-- Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                <!-- Main Catagory -->  
                <div class="row">
                      <div class="col-12">
                        <div class="purchase-top-btns">
                            <?php 
                            if(isset($user_sub_module) && in_array("Courier Transport", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ 
                            ?>
                            <a href="courier-transport.php" class="btn btn-dark active">Courier Transport</a>
                            <?php }
                            if(isset($user_sub_module) && in_array("Add Courier Party", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){
                            ?>
                            <!--<a href="courier-party.php" class="btn btn-dark  btn-fw">Add Courier Party</a>-->
                            <?php }
                            if(isset($user_sub_module) && in_array("Add Payment Voucher", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){
                            ?>
                            <a href="add-payment-voucher.php" class="btn btn-dark  btn-fw">Add Payment Voucher</a>
                            <?php } 
                            if(isset($user_sub_module) && in_array("Courier Details", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){
                            ?>
                            <a href="courier-details.php" class="btn btn-dark  btn-fw">Courier Details</a>
                            <?php } ?>
                            <a href="courier-payment.php" class="btn btn-dark  btn-fw">Payment To Courier</a>
                        </div>   
                      </div> 
                    </div>
                    <div>&nbsp;</div>
                    <h4 class="card-title">Courier Transport Details</h4>
                    <hr>                
                    
                    <!-- First Row  -->
                    <form class="forms-sample" method="post" action="" autocomplete="off">

                     <?php
                      if(isset($_REQUEST['id']) && $_REQUEST['id'] != '')
                      {
                      $id = $_REQUEST['id'];
                      $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                      $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;
                      $courierdataqry = "select * from courier_transport where id = '".$id."' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."'";
                      $courierdatarun = mysqli_query($conn, $courierdataqry);
                      $courierrecord = mysqli_fetch_assoc($courierdatarun);
                      }
                  
                  ?>
                  
                <div class="form-group row">
                  
                    <div class="col-12 col-md-2">
                       <label for="exampleInputName1">Courier/Transport Name<span class="text-danger">*</span></label>
                       <input type="text" name="name" class="form-control" id="exampleInputName1" placeholder="Name" required="" value="<?php if(isset($_REQUEST['id'])){echo $courierrecord['name'];}?>">
                      </div>
                      
                    <div class="col-12 col-md-2">
                       <label for="exampleInputName1">Contact Person Name<span class="text-danger">*</span></label>
                       <input type="text" name="c_name" class="form-control" id="exampleInputName1" placeholder="Contact Person Name" required="" value="<?php if(isset($_REQUEST['id'])){echo $courierrecord['c_name'];}?>">
                     </div>
                      
                    <div class="col-12 col-md-2">
                           <label for="exampleInputName1">Phone No</label>
                           <input type="text" name="phone" class="form-control onlynumber" id="exampleInputName1" placeholder="Phone No" maxlength="10"  data-parsley-length="[10, 10]" data-parsley-length-message = "Phone No should be 10 charatcers long." value="<?php if(isset($_REQUEST['id'])){echo $courierrecord['phone_no'];}?>">
                        </div>
                      
                      
                </div>
                 
                      
                      
                <div class="form-group row">     
                      
                    <div class="col-12 col-md-2">
                       <label for="exampleInputName1">Mobile No<span class="text-danger">*</span></label>
                       <input type="text" name="mobile" class="form-control onlynumber" id="exampleInputName1" placeholder="Mobile No" data-parsley-type="number" maxlength="10"  data-parsley-length="[10, 10]" data-parsley-length-message = "Mobile No should be 10 charatcers long." required="" value="<?php if(isset($_REQUEST['id'])){echo 
                        $courierrecord['mobile_no'];}?>">
                      </div>
                      
                    <div class="col-12 col-md-2">
                       <label for="exampleInputName1">Address<span class="text-danger">*</span></label>
                       <input type="text" name="address" class="form-control" id="exampleInputName1" placeholder="Address" required="" value="<?php if(isset($_REQUEST['id'])){echo $courierrecord['address'];}?>">
                      </div>
                      
                    <div class="col-12 col-md-2">
                       <input type="text" name="address1"class="form-control mt-30" id="exampleInputName1" placeholder="Address line 1" value="<?php if(isset($_REQUEST['id'])){echo $courierrecord['address_line1'];}?>">
                      </div>
                      
                      
               </div>
               
               <div class="form-group row">
                   
                    <div class="col-12 col-md-2">
                       <input type="text" name="address2" class="form-control mt-30" id="exampleInputName1" placeholder="Address line 2" value="<?php if(isset($_REQUEST['id'])){echo $courierrecord['address_line2'];}?>">
                    </div>     
                      
                    <div class="col-12 col-md-2">
                       <label for="exampleInputName1">State<span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" name="state" id="state" style="width:100%" required="" data-parsley-errors-container="#error-container"> 
                          <option value="">Select State</option>
                          <?php
                            if(!isset($_REQUEST['id'])){
                                $courierrecord['state'] = '12';
                            }
                          ?>        
                          <?php 
                            $stateqry = "SELECT id, name FROM `own_states` WHERE country_id = 101";
                            $staterun = mysqli_query($conn, $stateqry);

                            if($staterun){
                            while($statedata = mysqli_fetch_assoc($staterun)){
                          ?>  
                          <option value="<?php echo $statedata['id'];?>" <?php echo (isset($courierrecord['state']) && $courierrecord['state'] == $statedata['id']) ? 'selected' : ''; ?>><?php echo $statedata['name'];?> </option>
                          <?php } } ?>
                        </select>
                        <span id="error-container"></span>        
                      </div>
                      
                    <div class="col-12 col-md-2">
                       <label for="exampleInputName1">City<span class="text-danger">*</span></label>
                        <select name="city"  class="js-example-basic-single" id="city" style="width:100%" required="" data-parsley-errors-container="#error">     
                          <option value="">Select City</option>
                          <?php 
                            if(isset($courierrecord['state']) && $courierrecord['state'] != ''){

                              $allcityqry = "SELECT id, name FROM own_cities WHERE state_id = '".$courierrecord['state']."' order by name ASC";
                              $allcityrun = mysqli_query($conn, $allcityqry);

                              if($allcityrun){
                                while($allcitydata = mysqli_fetch_assoc($allcityrun)){?>

                                  <option value="<?php echo $allcitydata['id']; ?>" <?php echo (isset($courierrecord['city']) && $courierrecord['city'] == $allcitydata['id']) ? 'selected' : ''; ?> > <?php echo $allcitydata['name']; ?>
                                  </option>
                                  <?php } ?>
                                  <?php } ?>
                                  <?php } ?>
                        </select>  
                        <span id="error"></span>
                      </div>
                      
                    
                      
               </div>
               
                <div class="form-group row">
                    
                    <div class="col-12 col-md-2">
                       <label for="exampleInputName1">Pincode<span class="text-danger">*</span></label>
                       <input type="text" name="pincode" class="form-control onlynumber" id="exampleInputName1" placeholder="Pincode" maxlength="6" data-parsley-length="[6, 6]" data-parsley-length-message = "Mobile No should be 10 charatcers long." required="" value="<?php if(isset($_REQUEST['id'])){echo $courierrecord['pincode'];}?>">
                    </div>
                             
                    
                      
                    <div class="col-12 col-md-2">
                       <label for="exampleInputName1">Email<span class="text-danger">*</span></label>
                       <input type="email" name="mail" class="form-control" id="exampleInputName1" placeholder="Email" value="<?php if(isset($_REQUEST['id'])){echo $courierrecord['email'];}?>" required="">
                      </div>
                      
                    
                      
                </div>
              
                <div class="form-group row">  
                    
                    <div class="col-12 col-md-2">
                       <label for="exampleInputName1">FAX No</label>
                       <input type="text" name="fax" class="form-control onlynumber" id="exampleInputName1" placeholder="FAX No" value="<?php if(isset($_REQUEST['id'])){echo $courierrecord['fax_no'];}?>">
                    </div>
                    
                    <div class="col-12 col-md-2">
                       <label for="exampleInputName1">PAN No</label>
                       <input type="text" name="pan" class="form-control" id="exampleInputName1" placeholder="PAN No" value="<?php if(isset($_REQUEST['id'])){echo $courierrecord['pan_no'];}?>" data-parsley-pattern="[A-Za-z]{5}\d{4}[A-Za-z]{1}" data-parsley-pattern-message="Enter valid PAN No.">
                      </div>
                      
                    <div class="col-12 col-md-2">
                       <label for="exampleInputName1">GST No</label>
                       <input type="text" name="gst" class="form-control" id="exampleInputName1" placeholder="GST No" value="<?php if(isset($_REQUEST['id'])){echo $courierrecord['gst_no'];}?>" data-parsley-pattern="^([0]{1}[1-9]{1}|[1-2]{1}[0-9]{1}|[3]{1}[0-7]{1})([a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}[1-9a-zA-Z]{1}[zZ]{1}[0-9a-zA-Z]{1})+$" data-parsley-pattern-message="Enter valid GST No." maxlength="15">
                      </div>
                      
                </div>
             
              <div class="form-group row">         
                      
                      <div class="col-12 col-md-4">
                       <label for="exampleInputName1">Remarks</label>
                       <textarea class="form-control" name="remark" placeholder="Remarks" rows="3"><?php if(isset($_REQUEST['id'])){echo $courierrecord['remark'];}?></textarea>	
                      </div>
                      </div>
               <div class="form-group row">     
                      <div class="col-12 col-md-4">
                       <label for="exampleInputName1">Status</label>
                          <div class="row no-gutters">
                            <div class="col">
                              <div class="form-radio">
                                <label class="form-check-label">
                                  <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" checked <?php if(isset($_REQUEST['id']) && $courierrecord['status'] == "1"){echo "checked"; }?>>
                                  Active
                                </label>
                              </div>
                            </div>

                              <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="status" id="optionsRadios2" value="0" <?php if(isset($_REQUEST['id']) && $courierrecord['status'] == "0"){echo "checked"; }?>>
                                    Deactive
                                  </label>
                                </div>
                              </div>
                          </div>
                      </div> 
                      
                      
                      <div class="col-12 col-md-2">
                      	<button type="submit" name="submit" class="btn btn-success mt-30">Submit</button>
                      </div>
           </div> 
                  
                 
                   
                   
                  </form>
                 
                 
                </div>
              </div>
            </div>
            
        <!-- Table ------------------------------------------------------------------------------------------------------>
            
            <div class="col-md-12 grid-margin stretch-card">
              	<div class="card">
                <div class="card-body">
                
                	<!-- TABLE Filters btn -->
                   <?php
                     $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;    
                     $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : '';
                     $courierqry = "SELECT own_cities.name AS city, courier_transport.name, courier_transport.id, courier_transport.mobile_no, courier_transport.status FROM own_cities INNER JOIN courier_transport ON own_cities.id = courier_transport.city WHERE courier_transport.pharmacy_id = '".$p_id."' AND courier_transport.financial_id = '".$financial_id."' ORDER BY courier_transport.id DESC";
                     $courierrun = mysqli_query($conn, $courierqry);         
                   ?>
                    
                    <!-- TABLE STARTS -->
                    <div class="col mt-3">
                    	 <div class="row">
                            <div class="col-12">
                              <table id="order-listing1" class="table">
                                <thead>
                                  <tr>
                                      <th>Sr No</th>
                                      <th>Name</th>
                                      <th>Contact</th>
                                      <th>City</th>
                                      <th>Status</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts --> 	
                                  <?php
                                    if($courierrun){
                                      $count = 0;
                                      while($data = mysqli_fetch_assoc($courierrun)){
                                        $count++;
                                  ?>
                                  <tr>
                                      <td><?php echo $count; ?></td>
                                      <td><?php echo $data['name']; ?></td>
                                      <td><?php echo $data['mobile_no']; ?></td>
                                      <td><?php echo $data['city']; ?></td>
                                      <td><button type="button" class="btn btn-sm btn-toggle changestatus <?php echo (isset($data['status']) && $data['status'] == 1) ? 'active' : ''; ?>" data-table="courier_transport" data-id="<?php echo $data['id']; ?>" data-toggle="button" aria-pressed="<?php echo (isset($data['status']) && $data['status'] == 1) ? true : false; ?>" autocomplete="off">
                                    <div class="handle"></div>
                                    </button>
                                      </td>
                                      <td><a href="courier-transport.php?id=<?php echo $data['id'];?>" class="btn  btn-behance p-2"><i class="fa fa-pencil mr-0"></i></a></td>
                                  </tr><!-- End Row --> 	
                                  
                               
                                  
                                  <?php } } ?>
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
  <script src="js/custom/courier-transport.js"></script>
  
  <!-- Custom js for this page Modal Box-->
  <script src="js/modal-demo.js"></script>
  
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  
  <!-- Datepicker Initialise-->
 <script>
    $('#datepicker-popup1').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
    });
 </script>
 
 <script>
    $('#datepicker-popup2').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
    });
 </script>

 <!-- script for custom validation -->
 <script src="js/custom/onlynumber.js"></script>
 <script src="js/parsley.min.js"></script>
 <script type="text/javascript">
  $('form').parsley();
 </script>
 
 
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script> 
  
  <script>
  	 $('#order-listing2').DataTable();
  </script>
  
  <script>
  	 $('#order-listing1').DataTable();
  </script>
  
  
  <!-- End custom js for this page-->
  <!-- change status js -->
  <script src="js/custom/statusupdate.js"></script>
</body>


</html>
