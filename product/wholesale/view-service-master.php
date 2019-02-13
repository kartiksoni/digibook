<?php include('include/usertypecheck.php'); ?>

<?php
    $data = [];
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
    $query = "SELECT sm.*, gm.gst_name FROM service_master sm LEFT JOIN gst_master gm ON sm.gst_id = gm.id WHERE sm.pharmacy_id = '".$pharmacy_id."'";
    $res = mysqli_query($conn, $query);
    if($res && mysqli_num_rows($res) > 0){
        while($row = mysqli_fetch_assoc($res)){
            $data[] = $row;
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
            
     
            
            <!-- Product Master Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <a href="service-master.php" class="btn btn-success p-2 pull-right" title="Add Service Master"><i class="mdi mdi-plus-circle-outline"></i>Add Service Master</a>
                  <h4 class="card-title">View Service Master</h4>
                  <hr class="alert-dark">
                  <br>
                  <div class="col mt-3">
                       <div class="row">
                            <div class="col-12">
                              <table id="order-listing1" class="table">
                                <thead>
                                  <tr>
                                      <th>Sr No</th>
                                      <th>Service Name</th>
                                      <th>SAC Code</th>
                                      <th>GST</th>
                                      <th>Status</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($data) && !empty($data)){ ?>
                                        <?php foreach($data as $key => $value){ ?>
                                            <tr>
                                                <td><?php echo $key+1; ?></td>
                                                <td><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></td>
                                                <td><?php echo (isset($value['sac_code'])) ? $value['sac_code'] : ''; ?></td>
                                                <td><?php echo (isset($value['gst_name'])) ? $value['gst_name'] : ''; ?></td>
                                                <?php $checked = (isset($value['status']) && $value['status'] == 1) ? 'checked' : ''; ?>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-toggle changestatus <?php echo (isset($value['status']) && $value['status'] == 1) ? 'active' : ''; ?>" data-table="service_master" data-id="<?php echo $value['id']; ?>" data-toggle="button" aria-pressed="<?php echo (isset($value['status']) && $value['status'] == 1) ? true : false; ?>" autocomplete="off">
                                                        <div class="handle"></div>
                                                    </button>
                                                </td>
                                                <td>
                                                    <a class="btn  btn-behance p-2" href="service-master.php?id=<?php echo $value['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>  
                                </tbody>
                              </table>
                            </div>
                          </div>
                    </div>
                   <hr>
                   <br>
                   <a href="configuration.php" class="btn btn-light">Back</a>
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
  
 <script>
    $('#datepicker-popup1').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy'
    });
 </script>
 
 <script>
    $('#datepicker-popup2').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy'
    });
 </script>
 
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('#order-listing2').DataTable();
  </script>
  
  <script>
     $('#order-listing1').DataTable();
  </script>
  
  <!-- change status js -->
  <script src="js/custom/statusupdate.js"></script>
  
  <!-- End custom js for this page-->
</body>


</html>
