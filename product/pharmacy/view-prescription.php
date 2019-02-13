<?php include('include/usertypecheck.php'); ?>
<?php
    $bill_type = (isset($_SESSION['auth']['pharmacy_user_type'])) ? $_SESSION['auth']['pharmacy_user_type'] : '';
    $group_id = (isset($_GET['id'])) ? $_GET['id'] : '';
    $type = (isset($_GET['type'])) ? $_GET['type'] : '';
    $data = [];
    
    if($bill_type == 'ihis' && $group_id != '' && $type != ''){
        $table = '';
        if($type == 'OPD'){$table = 'opd_prescription_details';}elseif($type == 'IPD'){$table = 'ipd_prescription_details';}elseif($type == 'ICU'){$table = 'icu_treatment_prescription';}elseif($type == 'OT'){$table = 'ot_prescription';}elseif($type == 'ICU Drugs' || $type == 'ICUDRUG'){$table = 'icu_treatment_drugs';}
        if($table != ''){
            
            if(isset($_REQUEST['prescriptionid']) && $_REQUEST['prescriptionid'] != ''){
                    $updateNotiFlagQ = "UPDATE ".$table." SET is_notification = 1 WHERE id = '".$_REQUEST['prescriptionid']."'";
                    mysqli_query($ihis_conn, $updateNotiFlagQ);
            }
            
            $query = "SELECT tbl1.*, CONCAT(pm.fname,' ',pm.lname) As patient_name, pm.patient_opd_id FROM ".$table." as tbl1 JOIN patient_master pm ON tbl1.patient_id = pm.id WHERE tbl1.group_id = '".$group_id."'";
            $res = mysqli_query($ihis_conn, $query);
            if($res && mysqli_num_rows($res) > 0){
                while($row = mysqli_fetch_assoc($res)){
                   $data[] = $row;
                }
            }
        }
    }elseif($bill_type == 'eclinic' && $group_id != '' && $type != ''){
        
        if(isset($_REQUEST['prescriptionid']) && $_REQUEST['prescriptionid'] != ''){
                $updateNotiFlagQ = "UPDATE ec_opd_prescription_details SET is_notification = 1 WHERE id = '".$_REQUEST['prescriptionid']."'";
                mysqli_query($eclinic_conn, $updateNotiFlagQ);
        }
        
        $query = "SELECT tbl1.*, CONCAT(pm.fname,' ',pm.lname) As patient_name, pm.patient_opd_id FROM ec_opd_prescription_details as tbl1 JOIN ec_patient_master pm ON tbl1.patient_id = pm.id WHERE tbl1.group_id = '".$group_id."'";
        $res = mysqli_query($eclinic_conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
               $data[] = $row;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks | View Prescription</title>
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
                                <td><?php echo (isset($data[0]['patient_name'])) ? $data[0]['patient_name'] : ''; ?></td>
                            </tr>
                            <tr>
                                <td style="width: 10%;"><b>Patient Type : </b></td>
                                <td><?php echo (isset($type)) ? $type : ''; ?></td>
                            </tr>
                            <tr>
                                <td style="width: 10%;"><b>Patient ID : </b></td>
                                <td><?php echo (isset($data[0]['patient_opd_id'])) ? $data[0]['patient_opd_id'] : ''; ?></td>
                            </tr>
                            <tr>
                                <td style="width: 10%;"><b>Doctor Name : </b></td>
                                <td><?php echo (isset($_GET['doctor_name']) && $_GET['doctor_name'] != '') ? 'Dr. '.$_GET['doctor_name'] : 'Unknown Doctor'; ?></td>
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
                                    <?php if(isset($data) && !empty($data)){ ?>
                                        <?php foreach ($data as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $key+1; ?></td>
                                                <?php
                                                    if(isset($value['products']) && $value['products'] != ''){
                                                        $productQ = "SELECT product_name FROM `product_master` WHERE id= '".$value['products']."'";
                                                        $prodcutR = mysqli_query($conn,$productQ);
                                                        $product_row = mysqli_fetch_array($prodcutR);
                                                    }
                                                ?>
                                                <td><?php echo (isset($product_row['product_name'])) ? $product_row['product_name'] : ''; ?></td>
                                                <td><?php echo (isset($value['morning'])) ? $value['morning'] : ''; ?></td>
                                                <td><?php echo (isset($value['noon'])) ? $value['noon'] : ''; ?></td>
                                                <td><?php echo (isset($value['evening'])) ? $value['evening'] : ''; ?></td>
                                                <td><?php echo (isset($value['night'])) ? $value['night'] : ''; ?></td>
                                                <td><?php echo (isset($value['duration'])) ? $value['duration'] : ''; ?></td>
                                                <td><?php echo (isset($value['duration_time'])) ? $value['duration_time'] : ''; ?></td>
                                                <td><?php echo (isset($value['qty'])) ? $value['qty'] : ''; ?></td>
                                                <td><?php echo (isset($value['food'])) ? $value['food'] : ''; ?></td>
                                                <td><?php echo (isset($value['route'])) ? $value['route'] : ''; ?></td>
                                                <td><?php echo (isset($value['units'])) ? $value['units'] : ''; ?></td>
                                            </tr> 
                                        <?php } ?>
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
  
   
 <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <!-- End custom js for this page-->
</body>


</html>
