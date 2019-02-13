<?php $title = "Notification Master";?>
<?php include('include/config.php');?>
<?php
   if(isset($_POST['submit'])){
       $data['module'] = (isset($_POST['module'])) ? $_POST['module'] : '';
       $data['module_icon'] = (isset($_POST['module_icon'])) ? htmlentities($_POST['module_icon']) : '';
       if(isset($_GET['id']) && $_GET['id'] != ''){
           $query = "UPDATE pharmacy_module SET";
       }else{
           $query = "INSERT INTO pharmacy_module SET";
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
                $_SESSION['msg']['success'] = "Pharmacy module Updated Successfully.";
            }else{
                $_SESSION['msg']['success'] = "Pharmacy module Added Successfully.";
            }
            header('Location: add-module.php');exit;
            }else{
            if(isset($_GET['id']) && $_GET['id'] != ''){
                $_SESSION['msg']['fail'] = "Pharmacy module Updated Failed.";
            }else{
                $_SESSION['msg']['fail'] = "Pharmacy module Added Failed.";
            }
        }
   }
?>

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Add Module</title>
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
        
        
        <!-- Right Sidebar -->
        <?php include "include/sidebar-right.php" ?>
        
       
       <!-- Left Navigation -->
        <?php include "include/sidebar-nav-left.php" ?>
        
        
      
      
      <div class="main-panel">
      
        <div class="content-wrapper">
          <?php include('include/flash.php'); ?>
          <div class="row">
            
       
            
            <!-- Financial Year Form -->
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add Module</h4><hr class="alert-dark"><br>
                        <form  method="post" autocomplete="off">
                            <div class="form-group row">
                                <div class="col-12 col-md-4">
                                  <label for="customer_reminder">Module <span class="text-danger">*</span></label>
                                  <input type="text" class="form-control" name="module" id="module" placeholder="Module" value="<?php echo (isset($editData['module'])) ? $editData['module'] : ''; ?>" required="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12 col-md-4">
                                  <label for="customer_reminder">Module Icon </label>
                                 <input type="text" class="form-control" name="module_icon" id="module_icon" placeholder="Module Icon" value="<?php echo (isset($editData['module_icon'])) ? $editData['module_icon'] : ''; ?>">
                                  <p class="text-info">Example :   &lt;i class="fa fa-phone"&gt;&lt;/i&gt;</p>
                                </div>
                            </div>
                            <div class="from-group row">
                                <div class="col-12 col-md-4">
                                    <label for="exampleInputName1">Status</label>
                                    <div class="row no-gutters">
                                        <div class="col">
                                            <div class="form-radio">
                                                <label class="form-check-label">
                                                    <input type="radio" <?php if(isset($edit['status']) && $edit['status'] == "1"){echo "checked";}else{echo"checked";} ?> class="form-check-input" name="action" id="optionsRadios1" value="1">
                                                    Active
                                                </label>
                                            </div>
                                        </div>
                                              
                                        <div class="col">
                                            <div class="form-radio">
                                                <label class="form-check-label">
                                                    <input type="radio" <?php if(isset($edit['status']) && $edit['status'] == "0"){echo "checked";} ?> class="form-check-input" name="action" id="optionsRadios2" value="0">
                                                    Deactive
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <a href="configuration.php" class="btn btn-light">Back</a>
                                <button name="submit" type="submit" class="btn btn-success mr-2">Submit</button>
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

  <script src="js/parsley.min.js"></script>
  <script type="text/javascript">
    $('form').parsley();
  </script>
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>
  <script src="js/custom/onlynumber.js"></script>
  
  <!-- End custom js for this page-->
</body>


</html>
