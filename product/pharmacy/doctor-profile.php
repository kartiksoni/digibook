<?php $title = "Docter Profile"; ?>
<?php include('include/usertypecheck.php');
include('include/permission.php');

if(isset($_POST['submit']))
{
    $user_id = $_SESSION['auth']['id'];
    $title = $_POST['personal_title'];
    $doctor = $_POST['doctor_name'];
    // $short = $_POST['short_name'];
    $mobile = $_POST['mobile_no'];
    $commission = $_POST['doctor_commossion'];
    $country = $_POST['country'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $pincode = $_POST['pincode'];
    $status = $_POST['status'];
    $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : NULL;
    $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : NULL;
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
    $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;

    if(isset($_REQUEST['id']) && $_REQUEST['id'] != '')
    {
        $editid = $_REQUEST['id'];
        $doctoreditqry = "UPDATE `doctor_profile` SET `personal_title`='".$title."',`name`= '".$doctor."',`shortname`= '".$short."',`mobile`= '".$mobile."',`commission`= '".$commission."',`country`= '".$country."',`state`= '".$state."',`city`= '".$city."',`address`= '".$address."',`pincode`= '".$pincode."', `status`= '".$status."', `modified`= '".date('Y-m-d H:i:s')."',`modifiedby`= '".$user_id."' WHERE id = '".$editid."'";

        $doctoreditrun = mysqli_query($conn, $doctoreditqry);

        if($doctoreditrun)
        {
            $_SESSION['msg']['success'] = 'Doctor Updated Successfully.';
            header('location:doctor-profile.php');exit;
        }
        else
        {
            $_SESSION['msg']['fail'] = 'Updated fail.';
            header('location:doctor-profile.php');exit;
        }
    }else{
        $doctoraddqry = "INSERT INTO `doctor_profile`(`owner_id`,`admin_id`,`pharmacy_id`,`financial_id`, `personal_title`, `name`, `mobile`, `commission`, `country`, `state`, `city`, `address`, `pincode`, `status`, `created`, `createdby`, `modified`) VALUES ('".$owner_id."', '".$admin_id."', '".$pharmacy_id."','".$financial_id."', '".$title."', '".$doctor."', '".$mobile."', '".$commission."', '".$country."', '".$state."', '".$city."', '".$address."', '".$pincode."', '".$status."', '".date('Y-m-d H:i:s')."', '".$user_id."', '".date('Y-m-d H:i:s')."')";

        $doctorrunqry = mysqli_query($conn, $doctoraddqry);

        if($doctorrunqry)
        {
            $_SESSION['msg']['success'] = 'Doctor Added Successfully.';
            header('location:doctor-profile.php');exit;
        }
        else
        {
            $_SESSION['msg']['fail'] = 'Added Fail';
            header('location:doctor-profile.php');exit;
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
  <title>DigiBooks | Doctor Profile</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/iconfonts/puse-icons-feather/feather.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" />
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
  <link rel="stylesheet" href="css/parsley.css">
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
            
     
            
            <!-- Doctor Profile Form -->
            <div class="col-md-12 grid-margin stretch-card">
              
              <div class="card">
              
              <div class="card">
                <div class="card-body"> 
                    <h4 class="card-title">Doctor Profile</h4>
                  <hr class="alert-dark">
                  <br>

                  <form class="forms-sample" method="post" action="" autocomplete="off">

                  <?php
                  if(isset($_REQUEST['id']) && $_REQUEST['id'] != '')
                  {
                    $id = $_REQUEST['id'];
                    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                    $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;
                    $doctordataqry = "select * from doctor_profile where id = '".$id."' AND pharmacy_id = '".$pharmacy_id."'";
                    $doctordatarun = mysqli_query($conn, $doctordataqry);
                    $doctorrecord = mysqli_fetch_assoc($doctordatarun);
                  }
                  
                  ?>
                  
                  <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Doctor Name<span class="text-danger">*</span></label>
                        <div class="input-group mb-3">
                          <div class="input-group-prepend" style="border: 1px solid #dadada;">
                            <select class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" name="personal_title">
                                <option class="dropdown-item" value="Dr">Dr.</option>
                            </select>
                          </div>
                        <input type="text" value="<?php if(isset($_REQUEST['id'])){echo $doctorrecord['name'];} ?>" name="doctor_name" class="form-control" id="exampleInputName1" placeholder="Enter Name" required="" data-parsley-errors-container="#error-dr">
                      </div>
                      <span id="error-dr"></span>
                      </div>
                      <!--<div class="col-12 col-md-4">
                        <label for="exampleInputName1">Short Name</span></label>
                      <input type="text" name="short_name" value="<?php if(isset($_REQUEST['id'])){echo $doctorrecord['shortname']; }?>" class="form-control" id="exampleInputName1" placeholder="Enter Short Name">
                      </div>-->
                      
                       <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Mobile No.</label>
                        <input type="text" name="mobile_no"  value="<?php if(isset($_REQUEST['id'])){echo $doctorrecord['mobile']; }?>" class="form-control onlynumber" id="exampleInputName1" placeholder="Mobile No." data-parsley-type="number" data-parsley-length="[10, 10]" data-parsley-length-message = "Mobile No should be 10 charatcers long.">
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Doctor Commission</label>
                        <input type="text" name="doctor_commossion" class="form-control onlynumber" value="<?php if(isset($_REQUEST['id'])){echo $doctorrecord['commission']; }?>" placeholder="Commission %" data-parsley-type="number" maxlength="3">
                      </div>
                      
                  </div>
                    
                  <div class="form-group row">
                      
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Country<span class="text-danger">*</span></label>
                        <select name="country"  class="js-example-basic-single" id="country" style="width:100%" required="" data-parsley-errors-container="#error-country">  
                            <option value="">Select Country</option>
                            <?php 
                             $country = "SELECT * FROM `own_countries` order by name";
                             $countryrun = mysqli_query($conn, $country);
                             while($countrydata = mysqli_fetch_assoc($countryrun))
                             {   
                            ?>
                            <option <?php if(!isset($_REQUEST['id']) && $countrydata['id'] == "101"){echo "selected";} ?> value="<?php echo $countrydata['id'];?>" <?php echo (isset($doctorrecord['country']) && $doctorrecord['country'] == $countrydata['id']) ? 'selected' : ''; ?>> <?php echo $countrydata['name']; ?> </option>
                            <?php } ?>
                        </select>
                        <span id="error-country"></span>
                      </div>
                      
                       <div class="col-12 col-md-4">
                        <label for="exampleInputName1">State<span class="text-danger">*</span></label>
                            <select name="state"  class="js-example-basic-single" id="state" style="width:100%" required="" data-parsley-errors-container="#error-state">
                                <option value="">Select State</option>
                                <?php
                                if(!isset($_REQUEST['id'])){
                                 $allstateqry = "SELECT id, name FROM own_states WHERE country_id = '101' order by name ASC";
                                 $allstaterun = mysqli_query($conn, $allstateqry);
                                  if($allstaterun){
                                    while($allstatedata = mysqli_fetch_assoc($allstaterun)){?>
                                      
                                      <option <?php if(!isset($_REQUEST['id']) && $allstatedata['id'] == '12'){echo "selected";}?> value="<?php echo $allstatedata['id']; ?>" <?php echo (isset($doctorrecord['state']) && $doctorrecord['state'] == $allstatedata['id']) ? 'selected' : ''; ?> ><?php echo $allstatedata['name']; ?></option>
                                      <?php
                                       }
                                     }
                                }else{
                                $allstateqry = "SELECT id, name FROM own_states WHERE country_id = '".$doctorrecord['country']."'order by name ASC";
                                  $allstaterun = mysqli_query($conn, $allstateqry);  
                                  if($allstaterun){
                                    while($allstatedata = mysqli_fetch_assoc($allstaterun)){?>
                                      
                                      <option <?php if(!isset($_REQUEST['id']) && $allstatedata['id'] == '12'){echo "selected";}?> value="<?php echo $allstatedata['id']; ?>" <?php echo (isset($doctorrecord['state']) && $doctorrecord['state'] == $allstatedata['id']) ? 'selected' : ''; ?> ><?php echo $allstatedata['name']; ?></option>
                                      <?php } ?>
                                    <?php } ?>
                                  <?php } ?>
                            </select>
                            <span id="error-state"></span>
                        </div>
                        
                        
                        <div class="col-12 col-md-4">
                          <label for="exampleInputName1">City<span class="text-danger">*</span></label>
                          <select name="city"  class="js-example-basic-single" id="city" style="width:100%" required="" data-parsley-errors-container="#error-city">
                            <option value="">Select City</option>
                            <?php
                            if(!isset($_REQUEST['id'])){
                              $allcityqry = "SELECT id, name FROM own_cities WHERE state_id = '12' AND country_id = '101' order by name ASC";
                              $allcityrun = mysqli_query($conn, $allcityqry);

                              if($allcityrun){
                                while($allcitydata = mysqli_fetch_assoc($allcityrun)){?>

                                <option value="<?php echo $allcitydata['id']; ?>" <?php echo (isset($doctorrecord['city']) && $doctorrecord['city'] == $allcitydata['id']) ? 'selected' : ''; ?> > <?php echo $allcitydata['name']; ?></option>
                                <?php } 
                                }
                              }else{
                                $allcityqry = "SELECT id, name FROM own_cities WHERE state_id = '".$doctorrecord['state']."' order by name ASC";
                              $allcityrun = mysqli_query($conn, $allcityqry);

                              if($allcityrun){
                                while($allcitydata = mysqli_fetch_assoc($allcityrun)){?>

                                <option value="<?php echo $allcitydata['id']; ?>" <?php echo (isset($doctorrecord['city']) && $doctorrecord['city'] == $allcitydata['id']) ? 'selected' : ''; ?> > <?php echo $allcitydata['name']; ?></option>
                                <?php } 
                                }
                              } ?>
                          </select>
                          <span id="error-city"></span>
                        </div>
                        
                    </div>
                    
                    <div class="form-group row">
                        
                        <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Address</label>
                            <input type="text" name="address" value="<?php if(isset($_REQUEST['id'])){echo $doctorrecord['address']; }?>" class="form-control" id="exampleInputName1" placeholder="Enter Address">
                        </div>
                        
                        <div class="col-12 col-md-4">
                            <label for="exampleInputName1">PinCode</label>
                            <input type="text" name="pincode" value="<?php if(isset($_REQUEST['id'])){echo $doctorrecord['pincode']; }?>" class="form-control onlynumber" id="exampleInputName1" placeholder="Enter Pincode">
                        </div>
                        
                        <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Status</label>
                        
                          <div class="row no-gutters">
                          
                              <div class="col">
                                <div class="form-radio">
                                <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" checked <?php if(isset($_REQUEST['id']) && $doctorrecord['status'] == "1"){echo "checked"; }?>>
                                Active
                                </label>
                                </div>
                              </div>
                              
                              <div class="col">
                                <div class="form-radio">
                                <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="status" id="optionsRadios2" value="0" <?php if(isset($_REQUEST['id']) && $doctorrecord['status'] == "0"){echo "checked"; }?>>
                                Deactive
                                </label>
                                </div>
                              </div>
                          
                          </div>
                        </div>
                        
                    </div>
                    
                    <br>
                    <a href="configuration.php" class="btn btn-light pull-left">Back</a>
                    <button type="submit" name="submit" class="btn btn-success mr-2 pull-right">Submit</button>
                  </form>
                  </div>
              </div>
            
          </div>
            </div>

            <!-- Table ------------------------------------------------------------------------------------------------------>
            <?php 
                $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
                $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;
                $doctorqry = "SELECT * FROM doctor_profile WHERE pharmacy_id = '".$p_id."' ORDER BY id DESC";
                $doctorrun = mysqli_query($conn, $doctorqry);
            
            ?>

            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">  
                <div class="card-body">                  
                  <h4 class="card-title">View Doctor Profile</h4>
                  <hr class="alert-dark">
                    <div class="col mt-3">            
                      <div class="row">
                        <div class="col-12">
                          <div class="table-responsive">
                            <table class="table datatable">
                              <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Doctor Name</th>
                                    <!--<th>Short Name</th>-->
                                    <th>Mobile No</th>
                                    <th>Address</th>
                                    <th>Doctor Commission %</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>

                              <!-- Row Starts --> 

                              <?php
                              if($doctorrun)
                              {
                                   $count = 0;
                                  while($doctordata = mysqli_fetch_assoc($doctorrun))
                                  {
                                      $count++;
                              ?>  
                              <tr>
                                    <td><?php echo $count; ?></td>
                                    <?php 
                                    if($doctordata['personal_title'] != ''){
                                    ?>
                                    <td><?php echo $doctordata['personal_title'].". ".$doctordata['name']; ?></td>
                                    <?php 
                                    }else{
                                    ?>
                                    <td><?php echo $doctordata['name']; ?></td>
                                    <?php } ?>
                                    <!--<td><?php echo $doctordata['shortname']; ?></td>-->
                                    <td><?php echo $doctordata['mobile']; ?></td>
                                    <td><?php echo $doctordata['address']; ?></td>
                                    <td><?php echo $doctordata['commission']; ?></td>
                                    <td>
                                    <button type="button" class="btn btn-sm btn-toggle changestatus <?php echo (isset($doctordata['status']) && $doctordata['status'] == 1) ? 'active' : ''; ?>" data-table="doctor_profile" data-id="<?php echo $doctordata['id']; ?>" data-toggle="button" aria-pressed="<?php echo (isset($doctordata['status']) && $doctordata['status'] == 1) ? true : false; ?>" autocomplete="off">
                                    <div class="handle"></div>
                                    </button>
                                    </td>
                                    <td> 
                                    <a class="btn  btn-behance p-2" href="doctor-profile.php?id=<?php echo $doctordata['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                              </tr><!-- End Row -->
                                  <?php } } ?>
                              </tbody>
                            </table>
                          </div>
                        </div>        
                      </div>
                    </div>   
                </div>                    
              </div>                      
            </div>
            </div>    
            </div>                      
             

        <!-- partial:partials/_footer.php -->
        <?php include "include/footer.php"?>
        <!-- partial -->

        <!-- Add Company model -->
        <?php include "include/addcompanymodel.php"?>
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
  <script src="js/custom/onlynumber.js"></script>
  
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>

  <!-- Custom js for this page-->
  <script src="js/custom/onlynumber.js"></script>
  <script src="js/custom/product_master.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/custom/doctor-profile.js"></script>
  <?php 
  if(!isset($_REQUEST['id'])){
  ?>
  <script>
      $("#country option[value="+101+"]").prop("selected", true);
  </script>
  <?php } ?>
  
   <!-- script for custom validation -->
   <script src="js/parsley.min.js"></script>
   <script type="text/javascript">
  $('form').parsley();
   </script>

   <!-- Custom js for this page Datatables-->
   <script src="js/data-table.js"></script>
   <script>
     $('.datatable').DataTable();
  </script>

  <!-- change status js -->
  <script src="js/custom/statusupdate.js"></script>
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
</body>
</html>

  
