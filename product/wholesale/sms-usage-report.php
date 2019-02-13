<?php $title = "SMS Usage Report"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php //include('include/permission.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Comapnay Code Master</title>
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
                    <h4 class="card-title">View SMS Config</h4>
                    <hr class="alert-dark">
                    <div class="row">
                        <div class="col-12">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th width="10%">Sr No</th>
                                        <th>Pharmacy Name</th>
                                        <th width="15%">Total Remaining SMS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts -->   
                                  <?php
                                    $getAllConfigQ = "SELECT conf.*, pp.pharmacy_name  FROM `sms_config` conf LEFT JOIN pharmacy_profile pp ON conf.pharmacy = pp.id WHERE conf.createdby = '".$_SESSION['auth']['id']."' ORDER BY conf.id DESC";
                                    $getAllConfigR = mysqli_query($conn,$getAllConfigQ);
                                  ?>
                                  <?php if($getAllConfigR && mysqli_num_rows($getAllConfigR) > 0){ $i = 1; ?>
                                    <?php while ($row = mysqli_fetch_array($getAllConfigR)) { ?>
                                      <tr>
                                          <td><?php echo $i; ?></td>
                                          <td><?php echo (isset($row['pharmacy_name'])) ? $row['pharmacy_name'] : ''; ?></td>
                                          <td><?php echo (isset($row['sender_id'])) ? $row['sender_id'] : ''; ?></td>
                                          <td><?php echo (isset($row['server_url'])) ? $row['server_url'] : ''; ?></td>
                                          <td><?php echo (isset($row['auth_key'])) ? $row['auth_key'] : ''; ?></td>
                                          <td>
                                            <button type="button" class="btn btn-sm btn-toggle changestatus <?php echo (isset($row['status']) && $row['status'] == 1) ? 'active' : ''; ?>" data-table="sms_config" data-id="<?php echo $row['id']; ?>" data-toggle="button" aria-pressed="<?php echo (isset($row['status']) && $row['status'] == 1) ? true : false; ?>" autocomplete="off">
                                              <div class="handle"></div>
                                            </button>
                                          </td>
                                          <td>
                                            <a class="btn  btn-behance p-2" href="sms-config.php?id=<?php echo $row['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                            <a class="btn  btn-danger p-2" href="sms-config.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure want to delete?')" title="Delete"><i class="fa fa-trash-o mr-0"></i></a>
                                          </td>
                                      </tr>
                                      <?php $i++; } ?>
                                  <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
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
  
  <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
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
  <!-- End custom js for this page-->
  
</body>


</html>
