<?php include('include/usertypecheck.php'); ?>

<?php include('include/config-ihis.php'); ?>

<?php 
if(isset($_GET['id'])){
    $group_id = $_GET['id'];
    if($_GET['type'] == "OPD"){
        $Query = "SELECT * FROM `opd_prescription_details` WHERE group_id = '".$group_id."'";
        $NameQ = "SELECT CONCAT(pm.fname,' ',pm.lname) As name,pm.patient_opd_id FROM `opd_prescription_details` opd JOIN patient_master pm ON opd.patient_id = pm.id WHERE group_id = '".$group_id."' GROUP BY group_id";
        $nameR = mysqli_query($ihis_conn,$NameQ);
        $name_row = mysqli_fetch_array($nameR);
    }elseif($_GET['type'] == "IPD"){
        $Query = "SELECT * FROM `ipd_prescription_details` WHERE group_id = '".$group_id."'";
        $NameQ = "SELECT CONCAT(pm.fname,' ',pm.lname) As name,pm.patient_opd_id FROM `ipd_prescription_details` opd JOIN patient_master pm ON opd.patient_id = pm.id WHERE group_id = '".$group_id."' GROUP BY group_id";
        $nameR = mysqli_query($ihis_conn,$NameQ);
        $name_row = mysqli_fetch_array($nameR);
    }elseif($_GET['type'] == "ICU"){
        $Query = "SELECT * FROM `icu_treatment_prescription` WHERE group_id = '".$group_id."'";
        $NameQ = "SELECT CONCAT(pm.fname,' ',pm.lname) As name,pm.patient_opd_id FROM `icu_treatment_prescription` opd JOIN patient_master pm ON opd.patient_id = pm.id WHERE group_id = '".$group_id."' GROUP BY group_id";
        $nameR = mysqli_query($ihis_conn,$NameQ);
        $name_row = mysqli_fetch_array($nameR);
    }elseif($_GET['type'] == "OT"){
        $Query = "SELECT * FROM `ot_prescription` WHERE group_id = '".$group_id."'";
        $NameQ = "SELECT CONCAT(pm.fname,' ',pm.lname) As name,pm.patient_opd_id FROM `ot_prescription` opd JOIN patient_master pm ON opd.patient_id = pm.id WHERE group_id = '".$group_id."' GROUP BY group_id";
        $nameR = mysqli_query($ihis_conn,$NameQ);
        $name_row = mysqli_fetch_array($nameR);
    }
    $result1 = mysqli_query($ihis_conn,$Query);
    //$data = ($result1 && mysqli_num_rows($result1) > 0) ? mysqli_fetch_array($result1) : [];
    $data = array();
    while($row = mysqli_fetch_array($result1)){
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
  <style>
      .table td {
            border: none;
        }
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
        
        
      
      
      <div class="main-panel">
      
        <div class="content-wrapper">
          <span id="errormsg"></span>
          
          <div class="row">
            
     
            
            <!-- Product Master Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    
                  <h4 class="card-title">View Prescription</h4>
                
                  <hr class="alert-dark">
                  <br>
                  <div class="row">
                    <div class="col-12">
                        <table class="table" border="0">
                            <tr>
                                <td style="width: 10%;"><b>Patient Name : </b></td>
                                <td><?php echo $name_row['name']; ?></td>
                            </tr>
                            <tr>
                                <td style="width: 10%;"><b>Patient Type : </b></td>
                                <td><?php echo $_GET['type']; ?></td>
                            </tr>
                            <tr>
                                <td style="width: 10%;"><b>Patient ID : </b></td>
                                <td><?php echo $name_row['patient_opd_id']; ?></td>
                            </tr>
                            <tr>
                                <td style="width: 10%;"><b>Doctor Name : </b></td>
                                <td><?php echo "Dr. "; ?><?php echo $_GET['doctor_name']; ?></td>
                            </tr>
                        </table>
                    </div>
                  </div>
                  <br>
                  <div class="col">
                       <div class="row">
                            <div class="col-12">
                              <table id="" class="table">
                                <thead>
                                  <tr>
                                      <th>Sr No</th>
                                      <th>PRODUCT</th>
                                      <th>MORNING</th>
                                      <th>NOON</th>
                                      <th>EVENING</th>
                                      <th>NIGHT</th>
                                      <th>DURATION</th>
                                      <th>TIME</th>
                                      <th>QTY</th>
                                      <th>FOOD</th>
                                      <th>ROUTE</th>
                                      <th>UNITS</th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($data as $key => $value) {
                                ?>
                                  <tr>
                                      <td><?php echo $key+1; ?></td>
                                      <?php 
                                      $productQ = "SELECT product_name FROM `product_master` WHERE id= '".$value['products']."'";
                                      $prodcutR = mysqli_query($conn,$productQ);
                                      $product_row = mysqli_fetch_array($prodcutR);
                                      ?>
                                      <td><?php echo $product_row['product_name']; ?></td>
                                      <td><?php echo $value['morning']; ?></td>
                                      <td><?php echo $value['noon']; ?></td>
                                      <td><?php echo $value['evening']; ?></td>
                                      <td><?php echo $value['night']; ?></td>
                                      <td><?php echo $value['duration']; ?></td>
                                      <td><?php echo $value['duration_time']; ?></td>
                                      <td><?php echo $value['qty']; ?></td>
                                      <td><?php echo $value['food']; ?></td>
                                      <td><?php echo $value['route']; ?></td>
                                      <td><?php echo $value['units']; ?></td>
                                  </tr> 
                                <?php } ?>
                                </tbody>
                              </table>
                            </div>
                            <div class="modal-footer row">
                            <div class="col-md-12">
                              <a href="index.php" class="btn btn-light pull-left">Back</a>
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
  
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <!-- End custom js for this page-->
</body>


</html>
