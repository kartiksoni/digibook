<?php include('include/usertypecheck.php'); ?>

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
                  <a href="ledger-management.php" class="btn btn-success p-2 pull-right" title="Add Ledger"><i class="mdi mdi-plus-circle-outline"></i>Add Ledger</a>
                  <h4 class="card-title">View Ledger Master</h4>
                  <hr class="alert-dark">
                  <br>
                  <div class="col mt-3">
                       <div class="row">
                            <div class="col-12">
                              <div class="table-responsive">
                                <table class="table datatable">
                                  <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Account Name</th>
                                        <!--<th>Company Name</th>-->
                                        <th>Group</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                      $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
                                      $qry = "SELECT lgr.id, lgr.account_type, lgr.group_id, lgr.name,lgr.companyname, lgr.acc_flag,lgr.status,lgr.created,grp.name as groupname FROM  `ledger_master` lgr LEFT JOIN `group` grp ON lgr.group_id = grp.id WHERE lgr.pharmacy_id = '".$pharmacy_id."' AND lgr.is_cash = 0 order by lgr.id DESC";
                                      $res = mysqli_query($conn, $qry);
                                      if($res){
                                        $i = 1;
                                        while ($row = mysqli_fetch_array($res)) {
                                    ?>
                                      <tr>
                                          <td><?php echo $i; ?></td>
                                          <td><?php echo (isset($row['name']) && $row['name'] != '') ? ucwords(strtolower($row['name'])) : ''; ?></td>
                                          <!--<td><?php/* echo (isset($row['companyname']) && $row['companyname'] != '') ? ucwords(strtolower($row['companyname'])) : ''; */?></td>-->
                                          <td><?php echo (isset($row['groupname']) && $row['groupname'] != '') ? ucwords(strtolower($row['groupname'])) : ''; ?></td>
                                          <td>
                                              <?php
                                                $accFlag = (isset($row['acc_flag']) && $row['acc_flag'] != '') ? $row['acc_flag'] : 0;
                                                if($accFlag < 1){ 
                                            ?>
                                            
                                            <button type="button" class="btn btn-sm btn-toggle changestatus <?php echo (isset($row['status']) && $row['status'] == 1) ? 'active' : ''; ?>" data-table="ledger_master" data-id="<?php echo $row['id']; ?>" data-toggle="button" aria-pressed="<?php echo (isset($row['status']) && $row['status'] == 1) ? true : false; ?>" autocomplete="off">
                                              <div class="handle"></div>
                                            </button>
                                            
                                            <?php } ?>
                                          </td>
                                          <td>
                                            <a class="btn  btn-behance p-2" href="ledger-management.php?type=<?php echo $row['account_type']; ?>&subtype=<?php echo $row['group_id']; ?>&id=<?php echo $row['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                            <?php
                                                $accFlag = (isset($row['acc_flag']) && $row['acc_flag'] != '') ? $row['acc_flag'] : 0;
                                                if($accFlag < 1){ 
                                            ?>
                                                <a href="javascript:void(0);" class="btn btn-danger p-2 delete" title="Delete" data-id="<?php echo $row['id']; ?>" data-action="deleteLedger"><i class="fa fa-trash-o mr-0"></i></a>
                                                <!--<a class="btn  btn-danger p-2" href="view-ledger-management.php?delete=<?php echo $row['id']; ?>" title="Delete" onclick="return confirm('Are you sure want to delete?')"><i class="fa fa-trash-o mr-0"></i></a>-->
                                            <?php } ?>
                                          </td>
                                      </tr>
                                    <?php
                                      $i++;
                                        }
                                      }
                                    ?>
                                  </tbody>
                                </table>
                                  
                              </div>
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
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('.datatable').DataTable();
  </script>
  <!-- End custom js for this page-->
  
  <!-- change status js -->
  <script src="js/custom/statusupdate.js"></script>
  <script src="js/custom/delete.js"></script>
</body>


</html>
