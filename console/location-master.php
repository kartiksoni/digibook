<?php include('include/config.php');?>
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
  <link rel="stylesheet" href="css/parsley.css">
  <link rel="stylesheet" href="css/toggle/style.css">
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
  <style type="text/css">
    .dataTables_length{display: none !important;}
  </style>
</head>
<body>
  <div class="container-scroller">
    <!-- Topbar -->
        <?php include "include/topbar.php" ?>

        <?php 
          if(isset($_POST['addstate'])){
            $country_id = (isset($_POST['country_id'])) ? $_POST['country_id'] : '';
            $state = (isset($_POST['state'])) ? $_POST['state'] : '';
            $status = (isset($_POST['status'])) ? $_POST['status'] : 0;

            if(isset($_REQUEST['edit']) && $_REQUEST['edit'] == 'state'){
              $query = "UPDATE state SET ";
            }else{
              $query = "INSERT INTO state SET ";
            }

            $query .= "name = '".$state."', country_id = '".$country_id."', status = '".$status."'";
            if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
              $query .= " WHERE id='".$_REQUEST['id']."'";
            }

            $res = mysqli_query($conn, $query);
            if($res){
              if(isset($_REQUEST['edit']) && $_REQUEST['edit'] == 'state'){
                $_SESSION['msg']['success'] = "State Updated Successfully.";
              }else{
                $_SESSION['msg']['success'] = "StateState Added Successfully.";
              }
              header('Location: location-master.php');exit;
              
            }else{
                if(isset($_REQUEST['edit']) && $_REQUEST['edit'] == 'state'){
                  $_SESSION['msg']['error'] = "State Updated Fail!.";
                }else{
                  $_SESSION['msg']['error'] = "State Added Fail!.";
                }
            }
          }

          if(isset($_POST['addcity'])){
            $city = (isset($_POST['city'])) ? $_POST['city'] : '';
            $state_id = (isset($_POST['state_id'])) ? $_POST['state_id'] : '';
            $country_id = (isset($_POST['country_id'])) ? $_POST['country_id'] : '';
            $status = (isset($_POST['status'])) ? $_POST['status'] : '';

            
            if(isset($_REQUEST['edit']) && $_REQUEST['edit'] == 'city'){
              $query = "UPDATE city SET ";
            }else{
              $query = "INSERT INTO city SET ";
            }
            $query .= "name = '".$city."', state_id = '".$state_id."',country_id = '".$country_id."', status = '".$status."'";
            if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
              $query .= " WHERE id='".$_REQUEST['id']."'";
            }
            $res = mysqli_query($conn, $query);
            if($res){
              if(isset($_REQUEST['edit']) && $_REQUEST['edit'] == 'city'){
                $_SESSION['msg']['success'] = "City Updated Successfully.";
              }else{
                $_SESSION['msg']['success'] = "City Added Successfully.";
              }
              header('Location: location-master.php');exit;
            }else{
              if(isset($_REQUEST['edit']) && $_REQUEST['edit'] == 'city'){
                $_SESSION['msg']['error'] = "City Updated Fail!.";
              }else{
                $_SESSION['msg']['error'] = "City Added Fail!.";
              }
            }

          }
        ?>

        <?php 

          if((isset($_REQUEST['edit']) && $_REQUEST['edit'] == 'state') && (isset($_REQUEST['id']) && $_REQUEST['id'] != '')){
            $editStateQuery = "SELECT * FROM state WHERE id = '".$_REQUEST['id']."'";
            $editStateRes = mysqli_query($conn, $editStateQuery);
            if($editStateRes){
              $editStateRow = mysqli_fetch_array($editStateRes);
            }
          }

          if((isset($_REQUEST['edit']) && $_REQUEST['edit'] == 'city') && (isset($_REQUEST['id']) && $_REQUEST['id'] != '')){
            $editCityQuery = "SELECT * FROM city WHERE id = '".$_REQUEST['id']."'";
            $editCityRes = mysqli_query($conn, $editCityQuery);
            if($editCityRes){
              $editCityRow = mysqli_fetch_array($editCityRes);
            }
          }

        ?>
    
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
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    <!-- INVENTORY TABLE STARTS -->
                    <div class="col mt-3">
                      <h4 class="card-title">Add State</h4>
                      <hr class="alert-dark">
                        <form method="POST">
                          <div class="form-group row">
                            <div class="col-12 col-md-6">
                              <label for="country_id">Country <span class="text-danger">*</span></label>
                              <select class="js-example-basic-single" name="country_id" id="addstate-country" style="width:100%" required>
                                <option value="">Select Country</option>
                                <?php 
                                  $countryQuery = "SELECT id, name FROM country order by name ASC";
                                  $counteryRes = mysqli_query($conn, $countryQuery);
                                ?>
                                <?php if($counteryRes){ ?>
                                  <?php while ($countryRow = mysqli_fetch_array($counteryRes)) { ?>
                                    <option value="<?php echo $countryRow['id']; ?>" <?php echo (isset($editStateRow['country_id']) && $editStateRow['country_id'] == $countryRow['id']) ? 'selected' : ''; ?> ><?php echo $countryRow['name']; ?></option>
                                  <?php } ?>
                                <?php } ?>
                              </select>
                            </div>
                            <div class="col-12 col-md-6">
                              <label for="state">State <span class="text-danger">*</span></label>
                              <input type="text" class="form-control" name="state" id="addstate-state" placeholder="Enter State" value="<?php echo (isset($editStateRow['name'])) ? $editStateRow['name'] : ''; ?>" required>
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-12 col-md-6">
                              <label for="exampleInputName1">Status</label>
                              <div class="row no-gutters">
                                  <div class="col">
                                      <div class="form-radio">
                                        <label class="form-check-label">
                                          <?php 
                                            if(isset($_REQUEST['edit']) && $_REQUEST['edit'] == 'state'){
                                              if(isset($editStateRow['status']) && $editStateRow['status'] == 1){
                                                $stateactive = 'checked';
                                              }else{
                                                $stateactive = '';
                                              }
                                            }else{
                                              $stateactive = 'checked';
                                            }

                                          ?>
                                          <input type="radio" class="form-check-input" name="status" value="1" <?php echo $stateactive; ?>>Active
                                        </label>
                                      </div>
                                  </div>

                                  <div class="col">
                                      <div class="form-radio">
                                        <label class="form-check-label">
                                          <?php $statedeactive = (isset($editStateRow['status']) && $editStateRow['status'] == 0) ? 'checked' : ''; ?>
                                          <input type="radio" class="form-check-input" name="status" value="0" <?php echo $statedeactive; ?>>Deactive
                                        </label>
                                      </div>
                                  </div>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                                <a href="location-master.php" class="btn btn-primary mr-2 pull-left">Cancel</a>
                                <button type="submit" class="btn btn-success mr-2 pull-right" name="addstate">Submit</button>
                            </div>
                          </div>
                        </form>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    <!-- INVENTORY TABLE STARTS -->
                    <div class="col mt-3">
                      <h4 class="card-title">View State</h4>
                      <hr class="alert-dark">
                	    <div class="row">
                        <div class="col-12">
                          <table class="table datatable">
                            <thead>
                              <tr>
                                  <th>Sr No</th>
                                  <th>State</th>
                                  <th>Country</th>
                                  <th>Status</th>
                                  <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php 
                                $getAllStateQuery = "SELECT st.id, st.name, st.status, cty.name as countryname FROM state st INNER JOIN country cty ON st.country_id = cty.id ORDER BY st.name";
                                $getAllStateRes = mysqli_query($conn, $getAllStateQuery);
                                if($getAllStateRes) {
                                  $i = 1;
                                  while ($row1 = mysqli_fetch_array($getAllStateRes)) {
                              ?>
                                <tr>
                                  <td><?php echo $i; ?></td>
                                  <td><?php echo (isset($row1['name'])) ? $row1['name'] : ''; ?></td>
                                  <td><?php echo (isset($row1['countryname'])) ? $row1['countryname'] : ''; ?></td>
                                  <td>
                                    <button type="button" class="btn btn-sm btn-toggle changestatus <?php echo (isset($row1['status']) && $row1['status'] == 1) ? 'active' : ''; ?>" data-table="state" data-id="<?php echo $row1['id']; ?>" data-toggle="button" aria-pressed="<?php echo (isset($row1['status']) && $row1['status'] == 1) ? true : false; ?>" autocomplete="off">
                                    <div class="handle"></div>
                                    </button>
                                  </td>
                                  <td class="text-center">
                                    <a href="?edit=state&id=<?php echo $row1['id'] ?>"><i class="fa fa-pencil menu-icon"></i></a>
                                  </td>
                                </tr>
                                  <?php $i++; } ?>
                              <?php } ?>
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

          <div class="row" id="citydiv">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    <!-- INVENTORY TABLE STARTS -->
                    <div class="col mt-3">
                      <h4 class="card-title">Add City</h4>
                      <hr class="alert-dark">
                        <form method="POST">
                          <div class="form-group row">
                            <div class="col-12 col-md-6">
                              <label for="country_id">Country <span class="text-danger">*</span></label>
                              <select class="js-example-basic-single" name="country_id" id="addcity-country" style="width:100%" required>
                                <option value="">Select Country</option>
                                <?php 
                                  $countryQuery = "SELECT id, name FROM country order by name ASC";
                                  $counteryRes = mysqli_query($conn, $countryQuery);
                                ?>
                                <?php if($counteryRes){ ?>
                                  <?php while ($countryRow = mysqli_fetch_array($counteryRes)) { ?>
                                    <option value="<?php echo $countryRow['id']; ?>" <?php echo (isset($editCityRow['country_id']) && $editCityRow['country_id'] == $countryRow['id']) ? 'selected' : ''; ?> ><?php echo $countryRow['name']; ?></option>
                                  <?php } ?>
                                <?php } ?>
                              </select>
                            </div>

                            <div class="col-12 col-md-6">
                              <label for="state_id">State <span class="text-danger">*</span></label>
                              <select class="js-example-basic-single" name="state_id" id="addcity-state" style="width:100%" required>
                                <option value="">Select State</option>
                                <?php 
                                  if(isset($editCityRow['country_id']) && $editCityRow['country_id'] != ''){
                                    $editSt = "SELECT id, name FROM state WHERE country_id = '".$editCityRow['country_id']."'";
                                    $editStRes = mysqli_query($conn, $editSt);
                                    if($editStRes){
                                      while ($editStRow = mysqli_fetch_array($editStRes)) {
                                ?>
                                  <option value="<?php echo $editStRow['id']; ?>" <?php echo (isset($editCityRow['state_id']) && $editCityRow['state_id'] == $editStRow['id']) ? 'selected' : ''; ?>><?php echo $editStRow['name']; ?></option>
                                <?php
                                      }
                                    }
                                  }

                                ?>
                              </select>
                            </div>
                            
                          </div>
                          <div class="form-group row">
                            <div class="col-12 col-md-6">
                              <label for="city">City <span class="text-danger">*</span></label>
                              <input type="text" class="form-control" name="city" placeholder="Enter City" id="addcity-city" value="<?php echo (isset($editCityRow['name'])) ? $editCityRow['name'] : ''; ?>" required>
                            </div>
                            <div class="col-12 col-md-6">
                              <label for="exampleInputName1">Status</label>
                              <div class="row no-gutters">
                                  <div class="col">
                                      <div class="form-radio">
                                        <label class="form-check-label">
                                          <?php
                                            if(isset($_REQUEST['edit']) && $_REQUEST['edit'] == 'city'){
                                              $cityactive = (isset($editCityRow['status']) && $editCityRow['status'] == 1) ? 'checked' : '';
                                            }else{
                                              $cityactive = 'checked';
                                            }
                                          ?>

                                          <input type="radio" class="form-check-input" name="status" value="1" <?php echo $cityactive; ?>>Active
                                        </label>
                                      </div>
                                  </div>

                                  <div class="col">
                                      <div class="form-radio">
                                        <label class="form-check-label">
                                          <?php $citydeactive = (isset($editCityRow['status']) && $editCityRow['status'] == 0) ? 'checked' : ''; ?>
                                          <input type="radio" class="form-check-input" name="status" value="0" <?php echo $citydeactive;?>>Deactive
                                        </label>
                                      </div>
                                  </div>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                                <a href="location-master.php" class="btn btn-primary mr-2 pull-left">Cancel</a>
                                <button type="submit" class="btn btn-success mr-2 pull-right" name="addcity">Submit</button>
                            </div>
                          </div>
                        </form>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    <!-- INVENTORY TABLE STARTS -->
                    <div class="col mt-3">
                      <h4 class="card-title">View City</h4>
                      <hr class="alert-dark">
                      <div class="row">
                        <div class="col-12">
                          <table class="table datatable">
                            <thead>
                              <tr>
                                  <th>Sr No</th>
                                  <th>City</th>
                                  <th>State</th>
                                  <th>Country</th>
                                  <th>Status</th>
                                  <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php 
                                $getAllCityQuery = "SELECT ct.id, ct.name, ct.status,st.name as statename, ctry.name as countryname FROM city ct INNER JOIN state st ON ct.state_id = st.id INNER JOIN country ctry ON ct.country_id = ctry.id ORDER BY ct.name";
                                $getAllCityRes = mysqli_query($conn, $getAllCityQuery);
                                if($getAllCityRes) {
                                  $j = 1;
                                  while ($row2 = mysqli_fetch_array($getAllCityRes)) {

                              ?>
                                <tr>
                                  <td><?php echo $j; ?></td>
                                  <td><?php echo (isset($row2['name'])) ? $row2['name'] : ''; ?></td>
                                  <td><?php echo (isset($row2['statename'])) ? $row2['statename'] : ''; ?></td>
                                  <td><?php echo (isset($row2['countryname'])) ? $row2['countryname'] : ''; ?></td>
                                  <td>
                                    <button type="button" class="btn btn-sm btn-toggle changestatus <?php echo (isset($row2['status']) && $row2['status'] == 1) ? 'active' : ''; ?>" data-table="city" data-id="<?php echo $row2['id']; ?>" data-toggle="button" aria-pressed="<?php echo (isset($row2['status']) && $row2['status'] == 1) ? true : false; ?>" autocomplete="off">
                                    <div class="handle"></div>
                                    </button>
                                  </td>
                                  <td class="text-center">
                                    <a href="?edit=city&id=<?php echo $row2['id'] ?>#citydiv"><i class="fa fa-pencil menu-icon"></i></a>
                                  </td>
                                </tr>
                                  <?php $j++; } ?>
                              <?php } ?>
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
  
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>
  
 <script>
    $('.datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
    });
 </script>
 
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  <script>
  	 $('.datatable').DataTable({
      "iDisplayLength": 5,
     });
  </script>

<script src="js/custom/locationmaster.js"></script>

<!-- change status js -->
<script src="js/custom/statusupdate.js"></script>

<!-- script for custom validation -->
<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>
</body>


</html>
