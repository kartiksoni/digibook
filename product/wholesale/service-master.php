<?php $title = "Service Master"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php //include('include/permission.php'); ?>

<?php 
  $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
?>

<?php
    if(isset($_POST['submit'])){
        $name = (isset($_POST['name'])) ? $_POST['name'] : '';
        $sac_code = (isset($_POST['sac_code'])) ? $_POST['sac_code'] : '';
        $inward_rate = (isset($_POST['inward_rate']) && $_POST['inward_rate'] != '') ? $_POST['inward_rate'] : 0;
        $gst_id = (isset($_POST['gst_id'])) ? $_POST['gst_id'] : '';
        $cgst = (isset($_POST['cgst']) && $_POST['cgst'] != '') ? $_POST['cgst'] : 0;
        $sgst = (isset($_POST['sgst']) && $_POST['sgst'] != '') ? $_POST['sgst'] : 0;
        $igst = (isset($_POST['igst']) && $_POST['igst'] != '') ? $_POST['igst'] : 0;
        $company_code = (isset($_POST['company_code'])) ? $_POST['company_code'] : '';
        $status = (isset($_POST['status'])) ? $_POST['status'] : 0;
        $uid = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : '';
        $date = date('Y-m-d H:i:s');
    
        if(isset($_GET['id']) && $_GET['id'] != ''){
            $query = "UPDATE service_master SET ";
            $msgSuccess = "Service Update Successfully.";
            $msgFail = "Service Update Fail!.";
        }else{
            $query = "INSERT INTO service_master SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', ";
            $msgSuccess = "Service Added Successfully.";
            $msgFail = "Service Added Fail!.";
        }
    
        $query .= "name = '".$name."', sac_code = '".$sac_code."', inward_rate = '".$inward_rate."', gst_id = '".$gst_id."', cgst = '".$cgst."', sgst = '".$sgst."', igst = '".$igst."', company_code = '".$company_code."', status = '".$status."', ";
        
        if(isset($_GET['id']) && $_GET['id'] != ''){
            $query .= "modified = '".$date."', modifiedby = '".$uid."' WHERE id = '".$_GET['id']."'";
        }else{
            $query .= "created = '".$date."', createdby = '".$uid."'";
        }
        
        $res = mysqli_query($conn, $query);
        if($res){
            $_SESSION['msg']['success'] = $msgSuccess;
            header('location:view-service-master.php');exit;
        }else{
            $_SESSION['msg']['fail'] = $msgFail;
            header('location:service-master.php');exit;
        }
    }
    
    if(isset($_GET['id']) && $_GET['id'] != ''){
        $editQ = "SELECT * FROM service_master WHERE pharmacy_id = '".$pharmacy_id."' AND id = '".$_GET['id']."'";
        $editR = mysqli_query($conn, $editQ);
        if($editR && mysqli_num_rows($editR) > 0){
            $data = mysqli_fetch_assoc($editR);
        }else{
            $_SESSION['msg']['fail'] = 'Invalid Request!';
            header('location:view-service-master.php');exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Service Master</title>
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
          <span id="errormsg"></span>
          <div class="row">
            
         
            
            <!-- Service Master Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Service Master</h4><hr class="alert-dark">

                    <form class="forms-sample" method="POST" autocomplete="off">
                        <div class="form-group row">
                            <div class="col-12 col-md-3">
                              <label for="name">Service Name <span class="text-danger">*</span></label>
                              <input type="text" required name="name" value="<?php echo (isset($data['name'])) ? $data['name'] : ''; ?>" class="form-control" placeholder="Service Name">
                            </div>
                            <div class="col-12 col-md-3">
                              <label for="sac_code">SAC Code</label>
                              <input type="text" name="sac_code" value="<?php echo (isset($data['sac_code'])) ? $data['sac_code'] : ''; ?>" class="form-control" placeholder="SAC Code">
                              <label>Not sure about SAC code ? <a target="_blank" href="http://www.cbec.gov.in/htdocs-cbec/gst/Classification%20Scheme%20for%20Services%20under%20GST.xlsx">Look up here</a></label>
                            </div>
                            <div class="col-12 col-md-3">
                              <label for="inward_rate">Inward Rate <span class="text-danger">*</span></label>
                              <input type="text" name="inward_rate" value="<?php echo (isset($data['inward_rate'])) ? $data['inward_rate'] : ''; ?>" class="form-control onlynumber" placeholder="Inward Rate" required>
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="gst_id">GST<span class="text-danger">*</span></label>
                                <select name="gst_id" class="js-example-basic-single" id="gst_id" style="width:100%" data-parsley-errors-container="#error-gst-type" required> 
                                  <option value="">Select GST</option>
                                  <?php 
                                    $getGstRateQ = "SELECT * FROM `gst_master` WHERE status='1' AND pharmacy_id = '".$pharmacy_id."' OR edit_status='1' ORDER BY id DESC";
                                    $getGstRateR = mysqli_query($conn,$getGstRateQ);
                                    if($getGstRateR && mysqli_num_rows($getGstRateR) > 0){
                                        while($getGstRateRow = mysqli_fetch_assoc($getGstRateR)){
                                  ?>
                                        <option data-igst="<?php echo (isset($getGstRateRow['igst'])) ? $getGstRateRow['igst'] : ''; ?>" data-cgst="<?php echo (isset($getGstRateRow['cgst'])) ? $getGstRateRow['cgst'] : ''; ?>" data-sgst="<?php echo (isset($getGstRateRow['sgst'])) ? $getGstRateRow['sgst'] : ''; ?>" <?php if(isset($data['gst_id']) && $data['gst_id'] == $getGstRateRow['id']){echo "selected";} ?> value="<?php echo $getGstRateRow['id']; ?>"><?php echo $getGstRateRow['gst_name']; ?></option>
                                  <?php
                                    }
                                        }
                                  ?>
                                </select>
                                <span id="error-gst-type"></span>
                            </div>
                            
                            <div class="col-12 col-md-3 gstdiv" <?php echo (isset($data['gst_id']) && $data['gst_id'] > 3) ? '' : 'style="display:none"'; ?> >
                                <label for="igst">IGST</label>
                                <input type="text" readonly name="igst"  value="<?php echo (isset($data['igst'])) ? $data['igst'] : '';?>" class="form-control onlynumber" id="igst" placeholder="IGST">
                            </div>

                            <div class="col-12 col-md-3 gstdiv" <?php echo (isset($data['gst_id']) && $data['gst_id'] > 3) ? '' : 'style="display:none"'; ?> >
                                <label for="cgst">CGST</label>
                                <input type="text" readonly  name="cgst" value="<?php echo (isset($data['cgst'])) ? $data['cgst'] : '';?>" class="form-control onlynumber" id="cgst" placeholder="CGST">
                            </div>
                      
                            <div class="col-12 col-md-3 gstdiv" <?php echo (isset($data['gst_id']) && $data['gst_id'] > 3) ? '' : 'style="display:none"'; ?> >
                                <label for="sgst">SGST</label>
                                <input type="text" readonly  name="sgst" value="<?php echo (isset($data['sgst'])) ? $data['sgst'] : '';?>" class="form-control onlynumber" id="sgst" placeholder="SGST">
                            </div>
                            
                            <div class="col-12 col-md-3">
                                <label for="company_code">Company Code</label>
                                <select class="js-example-basic-single" id="company_code" name="company_code" style="width:100%">
                                    <option value="">Select Company Code</option>
                                    <?php
                                    $query = "SELECT * FROM `company_master` WHERE pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."'";
                                    $result = mysqli_query($conn,$query);
                                    while($row = mysqli_fetch_array($result)){
                                        ?>
                                    <option <?php if(isset($data['company_code']) && $data['company_code'] == $row['id']){echo "selected";} ?> value="<?php echo $row['id']; ?>"><?php echo $row['code']; ?> - <?php echo $row['name']; ?></option>
                                        <?php
                                    }
                                    ?>
                                    <option value="add_new_companycode">+ Add Company Code</option>
                                </select>
                            </div>
                            <!--<div class="col-12 col-md-1">-->
                            <!--    <button type="button" data-target="#addcompany-model" data-toggle="modal" class="btn btn-outline-primary btn-sm pull-right" style="margin-top: 30px;"><i class="mdi mdi-plus"></i></button>-->
                            <!--</div>-->
                            
                            <div class="col-12 col-md-3">
                              <label for="exampleInputName1">Status</label>
                              <?php $data['status'] = (isset($data['status'])) ? $data['status'] : '';?>
                            
                              <div class="row no-gutters">
                              
                                  <div class="col">
                                    <div class="form-radio">
                                    <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" checked <?php if(isset($_GET['id'])){if($data['status'] == "1"){echo "checked";}  }else{echo"checked";} ?>>
                                    Active
                                    </label>
                                    </div>
                                  </div>
                                  
                                  <div class="col">
                                    <div class="form-radio">
                                    <label class="form-check-label">
                                    <input type="radio" <?php if($data['status'] == "0"){echo "checked";} ?> class="form-check-input" name="status" id="optionsRadios2" value="0">
                                    Deactive
                                    </label>
                                    </div>
                                  </div>
                              
                              </div>
                            </div>
                        </div>
                    
                        <br/>
                    
                        <div class="row">
                          <div class="col-md-12">
                            <a href="view-service-master.php" class="btn btn-light">Cancel</a>
                            <button name="submit" type="submit" name="submit" class="btn btn-success">Submit</button>
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

  <script src="js/parsley.min.js"></script>
    <script type="text/javascript">
      $('form').parsley();
    </script>
 
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script>
  
   <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <script>
     $('.datatable').DataTable();
  </script>
  
  <!-- change status js -->
  <script src="js/custom/onlynumber.js"></script>
  <script src="js/custom/service_master.js"></script>
  
  <!-- End custom js for this page-->
  
</body>


</html>
