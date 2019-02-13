<?php $title = "Hospital Prescription"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php include('include/permission.php'); ?>

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
            
            <?php
                if(isset($_SESSION['auth']['pharmacy_user_type']) && $_SESSION['auth']['pharmacy_user_type'] == 'ihis'){
                    $data = IpdPrescriptionData(0);
                }elseif(isset($_SESSION['auth']['pharmacy_user_type']) && $_SESSION['auth']['pharmacy_user_type'] == 'eclinic'){
                    $data = eClinicPrescriptionData(0);
                }
                
            ?>
            
            <!-- Product Master Form -->
            <div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Hospital Patient Prescription</h4><hr class="alert-dark">
                      <div class="col mt-3">
                        <div class="row">
                          <div class="col-12">
                            <div class="table-responsive">
                              <table class="table datatable">
                                <thead>
                                  <tr>
                                      <th>Sr No.</th>
                                      <th>Patient ID</th>
                                      <th>Doctor Name</th>
                                      <th>Patient Name</th>
                                      <th>Email</th>
                                      <th>Mobile No</th>
                                      <th>Type</th>
                                      <th width="17%">Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($data) && !empty($data)){ ?>
                                        <?php foreach ($data as $key => $value) { ?>
                                            <tr title="<?php echo (isset($value['type'])) ? $value['type'] : ''; ?>">
                                              <td><?php echo $key+1; ?></td>
                                              <td><?php echo (isset($value['patient_opd_id']) ) ? $value['patient_opd_id'] : ''; ?></td>
                                              <td><?php echo (isset($value['doctor_name']) ) ? 'Dr.'.$value['doctor_name'] : ''; ?></td>
                                              <td><?php echo (isset($value['p_name']) ) ? $value['p_name'] : ''; ?></td>
                                              <td><?php echo (isset($value['email'])) ? $value['email'] : ''; ?></td>
                                              <td><?php echo (isset($value['mobile_no'])) ? $value['mobile_no'] : ''; ?></td>
                                              <td><?php echo (isset($value['type'])) ? $value['type'] : ''; ?></td>
                                              <td>
                                                  <a class="btn  btn-behance p-2" title="View Prescription" href="view-prescription.php?billtype=<?php echo (isset($value['bill_type'])) ? $value['bill_type'] : ''; ?>&id=<?php echo $value['group_id']; ?>&type=<?php echo $value['type']; ?>&doctor_name=<?php echo $value['doctor_name']; ?>"><i class="fa fa-eye mr-0"></i></a>
                                                  <?php if(isset($value['is_pharmacy_bill']) && $value['is_pharmacy_bill'] == 1){ ?>
                                                    <a href="javascript:void(0);" class="btn  btn-success p-2" title="Bill Already Generated">Bill Generated</a>
                                                    <?php
                                                    $tax_idQ = "SELECT id FROM `tax_billing` WHERE prec_group='".$value['group_id']."' AND type='".$value['type']."' AND ihis_patient_id='".$value['ihis_patient_id']."'";
                                                    $tax_idR = mysqli_query($conn, $tax_idQ);
                                                    $tax_idrow = mysqli_fetch_array($tax_idR);
                                                    ?>
                                                    <a href="print-sales-tax-billing.php?id=<?php echo $tax_idrow['id']; ?>" class="btn btn-primary p-2" title="Print" target="_blank"><i class="fa fa-print mr-0"></i></a>
                                                  <?php }else{ ?>
                                                    <a class="btn  btn-success p-2" title="Generate Bill" target="_blank" href="sales-tax-billing.php?billtype=<?php echo (isset($value['bill_type'])) ? $value['bill_type'] : ''; ?>&patient=<?php echo $value['patient_primary_id']; ?>&group=<?php echo $value['group_id']; ?>&doctor=<?php echo (isset($value['doctor_id'])) ? $value['doctor_id'] : ''; ?>&doctormobile=<?php echo (isset($value['doctor_mobile'])) ? $value['doctor_mobile'] : ''; ?>&type=<?php echo $value['type']; ?>&ihis_firm_id=<?php echo (isset($value['ihis_firm_id'])) ? $value['ihis_firm_id'] : ''; ?>&ihis_user_id=<?php echo (isset($value['ihis_user_id'])) ? $value['ihis_user_id'] : ''; ?>&ihis_ipd_id=<?php echo (isset($value['ihis_ipd_id'])) ? $value['ihis_ipd_id'] : ''; ?>&ihis_treatment_by=<?php echo (isset($value['ihis_treatment_by'])) ? $value['ihis_treatment_by'] : ''; ?>&ihis_patient_id=<?php echo (isset($value['ihis_patient_id'])) ? $value['ihis_patient_id'] : ''; ?>&ihis_followup_id=<?php echo (isset($value['ihis_followup_id'])) ? $value['ihis_followup_id'] : ''; ?>&register_type=<?php echo (isset($value['register_type'])) ? $value['register_type'] : ''; ?>&infertility_register_type=<?php echo (isset($value['infertility_register_type'])) ? $value['infertility_register_type'] : ''; ?>"><i class="fa fa-mail-forward mr-0"></i></a>
                                                  <?php } ?>
                                              </td>
                                            </tr>
                                        <?php } ?>
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
  
  <script>
     $('.datatable').DataTable();
  </script>
  
  
 <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  <!-- End custom js for this page-->
</body>


</html>
