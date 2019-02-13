<?php $title="Send SMS"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>

<?php 
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  // function for get all vendor
  function getAllVendor(){
    global $conn;
    global $pharmacy_id;
    $data = [];

    $query = "SELECT id, name FROM ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 AND group_id = 14 ORDER BY name";
    $res = mysqli_query($conn, $query);

    if($res && mysqli_num_rows($res) > 0){
      while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
      }
    }
    return $data;
  }

  function getAllCustomer(){
    global $conn;
    global $pharmacy_id;
    $data = [];

    $query = "SELECT id, name FROM ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 AND group_id = 10 AND is_cash = 0 ORDER BY name";
    $res = mysqli_query($conn, $query);

    if($res && mysqli_num_rows($res) > 0){
      while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
      }
    }
    return $data;
  }

  function getAllDoctor(){
    global $conn;
    global $pharmacy_id;
    $data = [];

    $query = "SELECT id, name FROM doctor_profile WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 ORDER BY name";
    $res = mysqli_query($conn, $query);

    if($res && mysqli_num_rows($res) > 0){
      while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
      }
    }
    return $data;
  }

  function getAllContact(){
    global $conn;
    global $pharmacy_id;
    $data = [];

    $query = "SELECT id, name FROM sms_phonebook WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 ORDER BY name";
    $res = mysqli_query($conn, $query);

    if($res && mysqli_num_rows($res) > 0){
      while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
      }
    }
    return $data;
  }

  function getAllGroup(){
    global $conn;
    global $pharmacy_id;
    $data = [];

    $query = "SELECT id, name FROM sms_group WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 ORDER BY name";
    $res = mysqli_query($conn, $query);

    if($res && mysqli_num_rows($res) > 0){
      while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
      }
    }
    return $data;
  }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks | Send SMS</title>
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

  <link rel="stylesheet" href="css/parsley.css">
  <link rel="stylesheet" href="css/bootstrap-select.min.css">
  <style type="text/css">
    .sendsms .bs-placeholder{border: 1px solid !important;}
    .sendsms .dropdown-toggle{border: 1px solid !important;}
    .sendsms .bootstrap-select{width: 100% !important;}
    .sendsms div.dropdown-menu{border: 1px solid grey !important;}
  </style>
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
      
      <div class="main-panel sendsms">
        <div class="content-wrapper">
          <span id="errormsg"></span>

          <div class="row">
           <!-- Form -->
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <!-- Main Catagory -->
                  <h5 class="card-title">Send Message to all vendor</h5><hr class="alert-dark"><br>
                  <form method="GET" action="sms-send-detail.php">
                    <div class="form-group row">
                      <div class="col-md-12 text-center">
                          <input type="hidden" name="type" value="all_vendor">
                          <button type="submit" class="btn btn-success btn-rounded btn-fw">Send Message</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            
              <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <!-- Main Catagory -->
                    <h5 class="card-title">Send Message to selected vendor</h5><hr class="alert-dark"><br>
                    <form method="GET" action="sms-send-detail.php">
                      <div class="form-group row">
                        <div class="col-md-12 text-center">
                            <select class="selectpicker" data-live-search="true" multiple data-selected-text-format="count" data-style="btn-default" name="data[]" data-parsley-errors-container="#error-vendor" required>
                              <?php 
                                $allvendor = getAllVendor();
                                if(!empty($allvendor)){
                                    foreach ($allvendor as $key => $value) {
                              ?>
                                <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                              <?php
                                  }
                                }
                              ?>
                            </select>
                            <span id="error-vendor"></span>
                            <input type="hidden" name="type" value="selected_vendor">
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-success btn-rounded btn-fw">Send Message</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            
          </div>

          <div class="row">
           <!-- Form -->
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <!-- Main Catagory -->
                  <h5 class="card-title">Send Message to all customer</h5><hr class="alert-dark"><br>
                  <form method="GET" action="sms-send-detail.php">
                    <div class="form-group row">
                      <div class="col-md-12 text-center">
                          <input type="hidden" name="type" value="all_customer">
                          <button type="submit" class="btn btn-success btn-rounded btn-fw">Send Message</button>
                      </div>
                    </div>
                  </form> 
                </div>
              </div>
            </div>

            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <!-- Main Catagory -->
                  <h5 class="card-title">Send Message to selected customer</h5><hr class="alert-dark"><br>
                  <form method="GET" action="sms-send-detail.php">
                    <div class="form-group row">
                      <div class="col-md-12 text-center">
                          <select class="selectpicker" data-live-search="true" multiple data-selected-text-format="count" data-style="btn-default" name="data[]" data-parsley-errors-container="#error-customer" required>
                            <?php 
                              $allcustomer = getAllCustomer();
                              if(!empty($allcustomer)){
                                  foreach ($allcustomer as $key => $value) {
                            ?>
                              <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                            <?php
                                }
                              }
                            ?>
                          </select>
                          <span id="error-customer"></span>
                          <input type="hidden" name="type" value="selected_customer">
                      </div>
                      <div class="col-md-12 text-center">
                          <button type="submit" class="btn btn-success btn-rounded btn-fw">Send Message</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <!-- Main Catagory -->
                  <h5 class="card-title">Send Message to all doctor</h5><hr class="alert-dark"><br>
                  <form method="GET" action="sms-send-detail.php">
                    <div class="form-group row">
                      <div class="col-md-12 text-center">
                          <input type="hidden" name="type" value="all_doctor">
                          <button type="submit" class="btn btn-success btn-rounded btn-fw">Send Message</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <!-- Main Catagory -->
                  <h5 class="card-title">Send Message to selected doctor</h5><hr class="alert-dark"><br>
                  <form method="GET" action="sms-send-detail.php">
                    <div class="form-group row">
                      <div class="col-md-12 text-center">
                          <select class="selectpicker" data-live-search="true" multiple data-selected-text-format="count" data-style="btn-default" name="data[]" data-parsley-errors-container="#error-doctor" required>
                            <?php 
                              $alldoctor = getAllDoctor();
                              if(!empty($alldoctor)){
                                  foreach ($alldoctor as $key => $value) {
                            ?>
                              <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                            <?php
                                }
                              }
                            ?>
                          </select>
                          <span id="error-doctor"></span>
                          <input type="hidden" name="type" value="selected_doctor">
                      </div>
                      <div class="col-md-12 text-center">
                          <button type="submit" class="btn btn-success btn-rounded btn-fw">Send Message</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <!-- Main Catagory -->
                  <h5 class="card-title">Send Message to all contacts</h5><hr class="alert-dark"><br>
                  <form method="GET" action="sms-send-detail.php">
                    <div class="form-group row">
                      <div class="col-md-12 text-center">
                          <input type="hidden" name="type" value="all_contact">
                          <button type="submit" class="btn btn-success btn-rounded btn-fw">Send Message</button>
                      </div>
                    </div>
                  </form> 
                </div>
              </div>
            </div>

            <div class="col-md-3 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <!-- Main Catagory -->
                  <h5 class="card-title">Send Message to selected contact</h5><hr class="alert-dark"><br>
                  <form method="GET" action="sms-send-detail.php">
                    <div class="form-group row">
                      <div class="col-md-12 text-center">
                          <select class="selectpicker" data-live-search="true" multiple data-selected-text-format="count" data-style="btn-default" name="data[]" data-parsley-errors-container="#error-contact" required>
                            <?php 
                              $allcontact = getAllContact();
                              if(!empty($allcontact)){
                                  foreach ($allcontact as $key => $value) {
                            ?>
                              <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                            <?php
                                }
                              }
                            ?>
                          </select>
                          <span id="error-contact"></span>
                          <input type="hidden" name="type" value="selected_contact">
                      </div>
                      <div class="col-md-12 text-center">
                          <button type="submit" class="btn btn-success btn-rounded btn-fw">Send Message</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <div class="col-md-3 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <!-- Main Catagory -->
                  <h5 class="card-title">Send Message to selected group</h5><hr class="alert-dark"><br>
                  <form method="GET" action="sms-send-detail.php">
                    <div class="form-group row">
                      <div class="col-md-12 text-center">
                          <select class="selectpicker" data-live-search="true" multiple data-selected-text-format="count" data-style="btn-default" name="data[]" data-parsley-errors-container="#error-group" required>
                            <?php 
                              $allgroup = getAllGroup();
                              if(!empty($allgroup)){
                                  foreach ($allgroup as $key => $value) {
                            ?>
                              <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                            <?php
                                }
                              }
                            ?>
                          </select>
                          <span id="error-group"></span>
                          <input type="hidden" name="type" value="selected_group">
                      </div>
                      <div class="col-md-12 text-center">
                          <button type="submit" class="btn btn-success btn-rounded btn-fw">Send Message</button>
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
        
          
        <!-- Add New Product Model -->
        <?php include "include/addproductmodel.php" ?>
     
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
  
  <!-- Custom js for this page Modal Box-->
  <script src="js/modal-demo.js"></script>
  <!-- Custom js for this page Datatables-->

  <script src="js/parsley.min.js"></script>
  <script type="text/javascript">
    $('form').parsley();
  </script>

  <script src="js/bootstrap-select.min.js"></script>
  
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <!-- End custom js for this page-->
</body>


</html>
