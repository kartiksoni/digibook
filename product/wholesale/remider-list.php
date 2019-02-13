<?php $title = "Remider List"; ?>
<?php include('include/usertypecheck.php');
      include('include/permission.php');
if(isset($_REQUEST['remider_id'])){
    $remider_id = $_REQUEST['remider_id'];
    $remiderQ = "UPDATE `tax_billing` SET `remider_status`='1' WHERE id='".$remider_id."'";
    $res = mysqli_query($conn, $remiderQ);
    if($res){
        $_SESSION['msg']['success'] = "Remider Status Stop Sucuessfully";
        header('location:remider-list.php');exit;
    }else{
        $_SESSION['msg']['fail'] = "Error For Remider Status";
        header('location:remider-list.php');exit;
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
                  <h4 class="card-title">Remider List</h4>
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
                                        <th>Invoice No</th>
                                        <th>Invoice Date</th>
                                        <th>Customer Name</th>
                                        <th>Total Amount</th>
                                        <th>Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                      $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
                                      $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

                                      $qry = "SELECT * FROM `tax_billing` WHERE remider='1'AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."'";
                                      $res = mysqli_query($conn, $qry);
                                      if($res){
                                        $i = 1;
                                        while ($row = mysqli_fetch_array($res)) {
                                    ?>
                                      <tr>
                                          <td><?php echo $i; ?></td>
                                          <td><?php echo $row['invoice_no']; ?></td>
                                          <td><?php echo $row['invoice_date']; ?></td>
                                          <?php
                                          $Cqry = "SELECT `name` FROM `ledger_master` WHERE id='".$row['customer_id']."'";
                                          $Cres = mysqli_query($conn, $Cqry);
                                          $Crow = mysqli_fetch_array($Cres)
                                          ?>
                                          <td><?php echo $Crow['name']; ?></td>
                                          <td><?php echo $row['final_amount']; ?></td>
                                          <td>
                                              <?php 
                                              if($row['remider_status'] == "0"){
                                              ?>
                                              <a href="remider-list.php?remider_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure want to stop reminder?')" class="btn btn-danger p-2" title="stop"><i class="fa fa-stop mr-0"></i></a>
                                              <?php }else{ ?>
                                              <a href="javascript:void(0);" class="btn  btn-success p-2" title="Stoped">Stoped</a>
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
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <script>
     $('.datatable').DataTable();
  </script>
  <!-- End custom js for this page-->
</body>


</html>
