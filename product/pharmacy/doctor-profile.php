<?php include('include/usertypecheck.php');

if(isset($_POST['submit']))
{
    $user_id = $_SESSION['auth']['id'];
    $doctor = $_POST['doctor_name'];
    $short = $_POST['short_name'];
    $mobile = $_POST['mobile_no'];
    $commission = $_POST['doctor_commossion'];
    $country = $_POST['country'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $pincode = $_POST['pincode'];
    $gst = $_POST['gst'];
    $status = $_POST['status'];

    if(isset($_REQUEST['id']) && $_REQUEST['id'] != '')
    {
        $editid = $_REQUEST['id'];
        $doctoreditqry = "UPDATE `doctor_profile` SET `name`= '".$doctor."',`shortname`= '".$short."',`mobile`= '".$mobile."',`commission`= '".$commission."',`country`= '".$country."',`state`= '".$state."',`city`= '".$city."',`address`= '".$address."',`pincode`= '".$pincode."', `status`= '".$status."', `modified`= '".date('Y-m-d H:i:s')."',`modifiedby`= '".$user_id."' WHERE id = '".$editid."'";

        $doctoreditrun = mysqli_query($conn, $doctoreditqry);

        if($doctoreditrun)
        {
            $_SESSION['msg']['success'] = 'Doctor Updated Successfully.';
            header('location:doctor-profile.php');
        }
        else
        {
            $_SESSION['msg']['fail'] = 'Updated fail.';
        }
    }else{
    $doctoraddqry = "INSERT INTO `doctor_profile`(`name`, `shortname`, `mobile`, `commission`, `country`, `state`, `city`, `address`, `pincode`, `gstno`, `status`, `created`, `createdby`, `modified`) VALUES ('".$doctor."', '".$short."', '".$mobile."', '".$commission."', '".$country."', '".$state."', '".$city."', '".$address."', '".$pincode."', '".$gst."', '".$status."', '".date('Y-m-d H:i:s')."', '".$user_id."', '".date('Y-m-d H:i:s')."')";

    $doctorrunqry = mysqli_query($conn, $doctoraddqry);

    if($doctorrunqry)
    {
        $_SESSION['msg']['success'] = 'Doctor Added Successfully.';
    }
    else
    {
        $_SESSION['msg']['fail'] = 'Added Fail';
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
  <title>DigiBooks</title>
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
          <?php include('include/flash.php'); ?>
          <span id="errormsg"></span>
          <div class="row">
            
     
            
            <!-- Doctor Profile Form -->
            <div class="col-md-12 grid-margin stretch-card">
              
              <div class="card">
                <div class="card-body">
                <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">SELECT ANYONE</label>
                    <select name="" class="js-example-basic-single" style="width:100%"> 
                        <option value="">Please Select</option> 
                    </select> 
                      </div> 

                       <div class="col-12 col-md-4">
                        <label for="exampleInputName1">SET DEFAULT DOCTOR</label>
                        <input type="text" name="" class="form-control" value="" placeholder="Enter Doctor Name">
                      </div>             
                </div>
              </div>
              
              
              <div class="card">
                <div class="card-body"> 
                    <h4 class="card-title">ADD NEW DOCTOR</h4>
                  <hr class="alert-dark">
                  <br>

                  <form class="forms-sample" method="post" action="">

                  <?php
                  if(isset($_REQUEST['id']) && $_REQUEST['id'] != '')
                  {
                    $id = $_REQUEST['id'];

                    $doctordataqry = "select * from doctor_profile where id = '".$id."'";
                    $doctordatarun = mysqli_query($conn, $doctordataqry);
                    $doctorrecord = mysqli_fetch_assoc($doctordatarun);
                  }
                  
                  ?>
                  
                  <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Doctor Name<span class="text-danger">*</span></label>
                        <input type="text" value="<?php if(isset($_REQUEST['id'])){echo $doctorrecord['name'];} ?>" name="doctor_name" class="form-control" id="exampleInputName1" placeholder="Enter Name" required="">
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Short Name</span></label>
                      <input type="text" name="short_name" value="<?php if(isset($_REQUEST['id'])){echo $doctorrecord['shortname']; }?>" class="form-control" id="exampleInputName1" placeholder="Enter Short Name">
                      </div>
                      
                       <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Mobile No.</label>
                        <input type="text" name="mobile_no"  value="<?php if(isset($_REQUEST['id'])){echo $doctorrecord['mobile']; }?>" class="form-control" id="exampleInputName1" placeholder="Mobile No." data-parsley-type="number" maxlength="10">
                      </div>
                  </div>
                    
                  <div class="form-group row">
                     
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Doctor Commission</label>
                        <input type="text" name="doctor_commossion" class="form-control" value="<?php if(isset($_REQUEST['id'])){echo $doctorrecord['commission']; }?>" placeholder="Commission %" data-parsley-type="number" maxlength="3">
                  </div>
   
                  <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Country</label>
                    <select name="country"  class="js-example-basic-single" id="country" style="width:100%" required="">  
                        <option value="">Select Country</option>
                        <?php 
                         $country = "SELECT * FROM `own_countries` order by name";
                         $countryrun = mysqli_query($conn, $country);
                         while($countrydata = mysqli_fetch_assoc($countryrun))
                         {   
                        ?>
                        <option value="<?php echo $countrydata['id'];?>" <?php echo (isset($doctorrecord['country']) && $doctorrecord['country'] == $countrydata['id']) ? 'selected' : ''; ?>> <?php echo $countrydata['name']; ?> </option>
                        <?php } ?>
                    </select>
                  </div>
                      
                   <div class="col-12 col-md-4">
                    <label for="exampleInputName1">State</label>
                        <select name="state"  class="js-example-basic-single" id="state" style="width:100%" required="">
                            <option value="">Select State</option>
                            <?php
                            if(isset($doctorrecord['country']) && $doctorrecord['country'] != ''){

                             $allstateqry = "SELECT id, name FROM own_states WHERE country_id = '".$doctorrecord['country']."' order by name ASC";
                              $allstaterun = mysqli_query($conn, $allstateqry);

                              if($allstaterun){
                                while($allstatedata = mysqli_fetch_assoc($allstaterun)){?>
                                  
                                  <option value="<?php echo $allstatedata['id']; ?>" <?php echo (isset($doctorrecord['state']) && $doctorrecord['state'] == $allstatedata['id']) ? 'selected' : ''; ?> ><?php echo $allstatedata['name']; ?></option>
                                  <?php } ?>
                                <?php } ?>
                              <?php } ?>
                        </select>
                    </div>
                        
                    </div>
                    
                    <div class="form-group row">
                        
                        <div class="col-12 col-md-4">
                          <label for="exampleInputName1">City</label>
                          <select name="city"  class="js-example-basic-single" id="city" style="width:100%" required="">
                            <option value="">Select City</option>
                            <?php
                            if(isset($doctorrecord['state']) && $doctorrecord['state'] != ''){

                              $allcityqry = "SELECT id, name FROM own_cities WHERE state_id = '".$doctorrecord['state']."' order by name ASC";
                              $allcityrun = mysqli_query($conn, $allcityqry);

                              if($allcityrun){
                                while($allcitydata = mysqli_fetch_assoc($allcityrun)){?>

                                <option value="<?php echo $allcitydata['id']; ?>" <?php echo (isset($doctorrecord['city']) && $doctorrecord['city'] == $allcitydata['id']) ? 'selected' : ''; ?> > <?php echo $allcitydata['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                                <?php } ?>
                          </select>
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Address</label>
                        <input type="text" name="address" value="<?php if(isset($_REQUEST['id'])){echo $doctorrecord['address']; }?>" class="form-control" id="exampleInputName1" placeholder="Enter Address">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">PinCode</label>
                        <input type="text" name="pincode" value="<?php if(isset($_REQUEST['id'])){echo $doctorrecord['pincode']; }?>" class="form-control" id="exampleInputName1" placeholder="Enter Pincode">
                        </div>
                        
                    </div>

                    <div class="form-group row">
                    <div class="col-12 col-md-4">
                        <label for="exampleInputName1">GSTIN No</label>
                        <input type="text" name="gst" value="<?php if(isset($_REQUEST['id'])){echo $doctorrecord['gstno']; }?>" class="form-control" id="exampleInputName1" placeholder="GSTIN No.">
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
                    <a href="#" class="btn btn-light pull-left">Back</a>
                    <button type="submit" name="submit" class="btn btn-success mr-2 pull-right">Submit</button>
                  </form>
                  </div>
              </div>
            </div>
        </div>
            </div>

            <!-- Table ------------------------------------------------------------------------------------------------------>
            <?php 

                $doctorqry = "select * from doctor_profile";
                $doctorrun = mysqli_query($conn, $doctorqry);
            
            ?>
            <div class="col mt-3">
                      <h4 class="card-title">Doctor List</h4>
                      <hr class="alert-dark">
                        <div class="row">
                          <div class="col-12">
                            <div class="table-responsive">
                              <table class="table datatable">
                              <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Doctor Name</th>
                                    <th>Short Name</th>
                                    <th>Mobile No</th>
                                    <th>Address</th>
                                    <th>Doctor Commission %</th>
                                    <th>GSTIN No.</th>
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
                                    <td><?php echo $doctordata['name']; ?></td>
                                    <td><?php echo $doctordata['shortname']; ?></td>
                                    <td><?php echo $doctordata['mobile']; ?></td>
                                    <td><?php echo $doctordata['address']; ?></td>
                                    <td><?php echo $doctordata['commission']; ?></td>
                                    <td><?php echo $doctordata['gstno']; ?></td>
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
                    <hr>    

             

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
  
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>

  <!-- Custom js for this page-->
  <script src="js/custom/onlynumber.js"></script>
  <script src="js/custom/product_master.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/custom/doctor-profile.js"></script>
  
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
</body>
</html>

  
