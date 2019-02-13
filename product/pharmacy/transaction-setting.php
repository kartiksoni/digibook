<?php include('include/usertypecheck.php');?>
<?php 
    
  if(isset($_POST['submit'])){
    $user_id = $_SESSION['auth']['id'];
    $setting_group = $_POST['setting_group'];
    $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : NULL;
    $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : NULL;
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
    $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;

    //TRANSACTION SETTING UPDATE QUERY START
    $ins_qry = "UPDATE setting_group SET transaction_setting = '".$setting_group."', modified = '".date('Y-m-d H:i:s')."', modifiedby = '".$user_id."' WHERE pharmacy_id = '".$pharmacy_id."'";
    $ins_run = mysqli_query($conn, $ins_qry);

    if($ins_run){
      $_SESSION['msg']['success'] = 'Transaction Setting Updated Successfully.';
      header('location:transaction-setting.php');exit;
    }else{
      $_SESSION['msg']['fail'] = 'Transaction Setting Updated Failed.';
      header('location:transaction-setting.php');exit;
    }
    //TRANSACTION SETTING UPDATE QUERY END
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks | Transaction Setting</title>
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
        <?php include "include/topbar.php"; ?>
    
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
        <?php include "include/sidebar-right.php"; ?>
        
       
       <!-- Left Navigation -->
        <?php include "include/sidebar-nav-left.php"; ?>
        
        
      
      
      <div class="main-panel">
      
        <div class="content-wrapper">
        <?php //include('include/flash.php'); ?>
          <span id="errormsg"></span>
          <div class="row">
            <?php include "include/setting-header.php"; 

                $dataqry = "SELECT * FROM setting_group WHERE pharmacy_id = '".$pharmacy_id."'";
                $datarun = mysqli_query($conn, $dataqry);
                $data = mysqli_fetch_assoc($datarun);
            ?>
            
          
           <!-- Form -->
  <div class="col-md-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Transaction Setting</h4>
      <form class="forms-sample" method="post" action="" autocomplete="off">
        <div class="form-group row">     
          <div class="col-12">
              <div class="row no-gutters">
                <div class="col">
                  <div class="form-radio">
                    <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="setting_group" id="optionsRadios1" value="0" checked <?php if($data['transaction_setting'] == 0 && $data != ''){ echo "checked"; } ?>>Cash Payment, &nbsp;&nbsp;&nbsp;Cash Receipt, &nbsp;&nbsp;&nbsp;Bank Payment, &nbsp;&nbsp;&nbsp;Bank Receipt</label>
                  </div>
                </div>
              </div>
          </div> 
        </div> 


        <div class="form-group row">     
          <div class="col-12">
              <div class="row no-gutters">
                <div class="col">
                  <div class="form-radio">
                    <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="setting_group" id="optionsRadios1" value="1" <?php if($data['transaction_setting'] == 1 && $data != ''){ echo "checked"; } ?>>Cash Transaction, &nbsp;&nbsp;&nbsp;Bank Transaction</label>
                  </div>
                </div>
              </div>
          </div> 
        </div> 

        <div class="form-group row">     
          <div class="col-12">
              <div class="row no-gutters">
                <div class="col">
                  <div class="form-radio">
                    <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="setting_group" id="optionsRadios1" value="2" <?php if($data['transaction_setting'] == 2 && $data != ''){ echo "checked"; } ?>>Payment, &nbsp;&nbsp;&nbsp;Receipt</label>
                  </div>
                </div>
              </div>
          </div> 

          <div class="col-12 col-md-2">
            <button type="submit" name="submit" class="btn btn-success mt-30">Update</button>
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
  <script src="js/custom/setting.js"></script>
  
  <!-- Custom js for this page Modal Box-->
  <script src="js/modal-demo.js"></script>

  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>

 <!-- script for custom validation -->
 <script src="js/custom/onlynumber.js"></script>
 <script src="js/parsley.min.js"></script>
 <script type="text/javascript"></script>
 
 
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script> 
  
  <!-- End custom js for this page-->
  <!-- change status js -->
  <script src="js/custom/statusupdate.js"></script>
</body>


</html>
