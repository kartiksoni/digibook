<?php $title = "Salesman"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php include('include/permission.php'); ?>
<?php 
$financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
?>

<?php
  /* START CODE FOR DATA INSERT AND UPDATE  */
  if(isset($_POST['submit'])){
      
            $data['fname'] = (isset($_POST['fname'])) ? $_POST['fname'] : '';
            $data['lname'] = (isset($_POST['lname'])) ? $_POST['lname'] : '';
            $data['email'] = (isset($_POST['name'])) ? $_POST['name'] : '';
            $data['mobile'] = (isset($_POST['mobile'])) ? $_POST['mobile'] : '';
            $data['email'] = (isset($_POST['email'])) ? $_POST['email'] : '';
            $data['address'] = (isset($_POST['address'])) ? $_POST['address'] : '';
            $data['country'] = (isset($_POST['country'])) ? $_POST['country'] : '';
            $data['state'] = (isset($_POST['state'])) ? $_POST['state'] : '';
            $data['district'] = (isset($_POST['district'])) ? $_POST['district'] : '';
            $data['city'] = (isset($_POST['city'])) ? $_POST['city'] : '';
            $data['birth_date'] = (isset($_POST['birth_date']) && $_POST['birth_date'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['birth_date']))) : '';
            $data['join_date'] = (isset($_POST['join_date']) && $_POST['join_date'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['join_date']))) : '';
            if(isset($_GET['id'])){
                $data['series_no'] = (isset($_POST['series_no'])) ? $_POST['series_no'] : '';
            }else{
                $data['series_no'] = getSeries();
            }
            $data['status'] = (isset($_POST['status'])) ? $_POST['status'] : 0;
            
            
        
              if(isset($_GET['id']) && $_GET['id'] != ''){
                $query = "UPDATE salesman SET";
              }else{
                $query = "INSERT INTO salesman SET";
                if(isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != ''){
                  $query .= " owner_id = '".$_SESSION['auth']['owner_id']."', ";
                }
                if(isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != ''){
                  $query .= " admin_id = '".$_SESSION['auth']['admin_id']."', ";
                }
                if(isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != ''){
                  $query .= " pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."', ";
                }
                if(isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != ''){
                  $query .= " financial_id = '".$_SESSION['auth']['financial']."', ";   
                }
              }
        
              foreach ($data as $key => $value) {
                $query .= " ".$key." = '".$value."', ";
              }
        
              if(isset($_GET['id']) && $_GET['id'] != ''){
                $query .= "modified = '".date('Y-m-d H:i:s')."', modifiedby = '".$_SESSION['auth']['id']."'";
                $query .= "where id = '".$_GET['id']."'";
              }else{
                $query .= "created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
              }
              
              $result = mysqli_query($conn, $query);
              
              if($result){
                if(isset($_GET['id']) && $_GET['id'] != ''){
                  $_SESSION['msg']['success'] = "Salesman Updated Successfully.";
                }else{
                  $_SESSION['msg']['success'] = "Salesman Added Successfully.";
                }
                header('Location: view-salesman.php');exit;
              }else{
                if(isset($_GET['id']) && $_GET['id'] != ''){
                  $_SESSION['msg']['fail'] = "Salesman Updated Failed.";
                }else{
                  $_SESSION['msg']['fail'] = "Salesman Added Failed.";
                }
              }
  }
  /* END CODE FOR DATA INSERT AND UPDATE  */

  /* START CODE FOR EDIT RECORD GET VALUE */
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $e_p_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
    $getSalsmanQuery = "SELECT * FROM salesman WHERE id = '".$_GET['id']."' AND pharmacy_id = '".$e_p_id."'";
    $getSalsmanRes = mysqli_query($conn, $getSalsmanQuery);
    if($getSalsmanRes && mysqli_num_rows($getSalsmanRes) > 0){
      $getSalsmanData = mysqli_fetch_array($getSalsmanRes);
    }else{
      $_SESSION['msg']['fail'] = "Somthing Want Wrong! Please Try Again.";
      header('Location: view-salesman.php');exit;
    }
  }
  /* END CODE FOR EDIT RECORD GET VALUE */
  
  function getSeries(){
    global $conn;
    $series = '';
    
    $query = "SELECT series_no FROM salesman WHERE pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."' ORDER BY id DESC LIMIT 1";
    $res = mysqli_query($conn, $query);
    if($res && mysqli_num_rows($res) > 0){
      $row = mysqli_fetch_array($res);
      $series = (isset($row['series_no']) && $row['series_no'] != '') ? $row['series_no'] : 0;
      $series = $series + 1;
      $series = sprintf("%05d", $series);
    }else{
        $series = sprintf("%05d", 1);
    }
    return $series;
  }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Salesman - DigiBooks</title>
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
          <div class="row">
            
            <!-- Vendor Managment Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Salesman</h4>
                 <hr class="alert-dark">
                 <br>
                  <form action="" method="POST" autocomplete="off">
                  
                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="fname">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="fname" class="form-control onlyalphabet" placeholder="First Name" value="<?php echo (isset($getSalsmanData['fname'])) ? $getSalsmanData['fname'] : ''; ?>" autocomplete="nope" required>
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="lname">Last Name</label>
                        <input type="text" class="form-control onlyalphabet" name="lname" placeholder="Last Name" value="<?php echo (isset($getSalsmanData['lname'])) ? $getSalsmanData['lname'] : ''; ?>" autocomplete="nope">
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Email" parsley-type="email" value="<?php echo (isset($getSalsmanData['email'])) ? $getSalsmanData['email'] : ''; ?>" autocomplete="nope">
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="mobile">Mobile No <span class="text-danger">*</span></label>
                        <input type="text" name="mobile" class="form-control onlynumber" data-parsley-type="number" data-parsley-length="[10, 10]" data-parsley-length-message = "Mobile No should be 10 charatcers long." placeholder="Mobile No" value="<?php echo (isset($getSalsmanData['mobile'])) ? $getSalsmanData['mobile'] : ''; ?>" autocomplete="nope" required>
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="address">Address </label>
                        <textarea class="form-control" autocomplete="nope" name="address" placeholder="Address" rows="1" style="resize: both;"><?php echo (isset($getSalsmanData['address'])) ? $getSalsmanData['address'] : ''; ?></textarea>
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="country">Country <span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" name="country" id="country" style="width:100%" data-parsley-errors-container="#error-country" required>
                          <option value="">Select Country</option>
                          <?php
                            if(!isset($_GET['id'])){
                              $getSalsmanData['country'] = '101';
                              $getSalsmanData['state'] = '12';
                            }
                            $countryQuery = "SELECT id, name FROM own_countries order by name ASC";
                            $counteryRes = mysqli_query($conn, $countryQuery);
                          ?>
                          <?php if($counteryRes){ ?>
                            <?php while ($countryRow = mysqli_fetch_array($counteryRes)) { ?>
                              <option value="<?php echo $countryRow['id']; ?>" <?php echo (isset($getSalsmanData['country']) && $getSalsmanData['country'] == $countryRow['id']) ? 'selected' : ''; ?> ><?php echo $countryRow['name']; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                        <span id="error-country"></span>
                      </div>
                    </div>

                    <div class="form-group row">
                      
                      <div class="col-12 col-md-4">
                        <label for="state">State <span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" id="state" name="state" style="width:100%" data-parsley-errors-container="#error-state" required>
                          <option value="">Select State</option>
                          <?php if(isset($getSalsmanData['country']) && $getSalsmanData['country'] != ''){ ?>
                            <?php 
                              $stateQuery = "SELECT id, name FROM own_states WHERE country_id = '".$getSalsmanData['country']."' order by name ASC";
                              $stateRes = mysqli_query($conn, $stateQuery);
                            ?>
                            <?php if($stateRes){ ?>
                              <?php while ($stateRow = mysqli_fetch_array($stateRes)) { ?>
                                <option value="<?php echo $stateRow['id']; ?>" <?php echo (isset($getSalsmanData['state']) && $getSalsmanData['state'] == $stateRow['id']) ? 'selected' : ''; ?> ><?php echo $stateRow['name']; ?></option>
                              <?php } ?>
                            <?php } ?>
                          <?php } ?>
                        </select>
                        <span id="error-state"></span>
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="city">City <span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" id="city" name="city" style="width:100%" data-parsley-errors-container="#error-city" required>
                          <option value="">Select City</option>
                          <?php if(isset($getSalsmanData['state']) && $getSalsmanData['state'] != ''){ ?>
                            <?php 
                              $cityQuery = "SELECT id, name FROM own_cities WHERE state_id = '".$getSalsmanData['state']."' order by name ASC";
                              $cityRes = mysqli_query($conn, $cityQuery);
                            ?>
                            <?php if($cityRes){ ?>
                              <?php while ($cityRow = mysqli_fetch_array($cityRes)) { ?>
                                <option value="<?php echo $cityRow['id']; ?>" <?php echo (isset($getSalsmanData['city']) && $getSalsmanData['city'] == $cityRow['id']) ? 'selected' : ''; ?> ><?php echo $cityRow['name']; ?></option>
                              <?php } ?>
                            <?php } ?>
                          <?php } ?>
                        </select>
                        <span id="error-city"></span>
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="district">District</label>
                        <input type="text" name="district" class="form-control onlyalphabet" autocomplete="nope" placeholder="District" value="<?php echo (isset($getSalsmanData['district'])) ? $getSalsmanData['district'] : ''; ?>">
                      </div>
                    </div>

                    <div class="form-group row">

                      <div class="col-12 col-md-4">
                        <label for="birth_date">Birth Date</label>
                        <input type="text" class="form-control datepicker" name="birth_date" value="<?php echo (isset($getSalsmanData['birth_date']) && $getSalsmanData['birth_date'] != '') ? date('d/m/Y',strtotime($getSalsmanData['birth_date'])) : date('d/m/Y'); ?>" placeholder="Birthdate">
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="join_date">Join Date</label>
                        <input type="text" class="form-control datepicker" name="join_date" value="<?php echo (isset($getSalsmanData['join_date']) && $getSalsmanData['join_date'] != '') ? date('d/m/Y',strtotime($getSalsmanData['join_date'])) : date('d/m/Y'); ?>" placeholder="Join Date">
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="series_no">Series</label>
                        <input type="text" class="form-control" name="series_no" value="<?php echo (isset($getSalsmanData['series_no'])) ? $getSalsmanData['series_no'] : getSeries(); ?>" placeholder="Series" readonly>
                      </div>
                    </div>

                    
                    <div class="form-group row">

                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Status</label>
                        <div class="row no-gutters">
                            <div class="col">
                                <div class="form-radio">
                                <label class="form-check-label">
                                  <?php
                                    if(isset($_GET['id'])){
                                      $active = (isset($getSalsmanData['status']) && $getSalsmanData['status'] == 1)  ? 'checked' : '';
                                    }else{
                                      $active = 'checked';
                                    }
                                  ?>
                                <input type="radio" class="form-check-input" name="status" value="1" <?php echo $active; ?>>
                                Active
                                </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-radio">
                                <label class="form-check-label">
                                  <?php
                                    $deactive = (isset($getSalsmanData['status']) && $getSalsmanData['status'] == 0)  ? 'checked' : '';
                                  ?>
                                <input type="radio" class="form-check-input" name="status" value="0" <?php echo $deactive; ?>>
                                Deactive
                                </label>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                    
                    <br>

                    <a href="view-salesman.php" class="btn btn-light pull-left">Back</a>
                    <button type="submit" class="btn btn-success mr-2 pull-right" name="submit">Submit</button>

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
  
 <script>
    $('.datepicker').datepicker({
        enableOnReadonly: true,
        todayHighlight: true,
        format: 'dd/mm/yyyy',
        autoclose : true
    });
 </script>

<!-- page js -->
<script src="js/custom/salesman.js"></script>

<!-- script for custom validation -->
<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>
<script src="js/custom/onlynumber.js"></script>
<script src="js/custom/onlyalphabet.js"></script>

 <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <!-- End custom js for this page-->
  <?php //include('include/usertypecheck.php'); ?>
</body>


</html>
