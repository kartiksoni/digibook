<?php include('include/usertypecheck.php'); ?>

<?php
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
    $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
    
    if(isset($_GET['delete']) && $_GET['delete'] != ''){
        $deleteQ = "DELETE FROM delivery_challan WHERE pharmacy_id = '".$pharmacy_id."' AND id = '".$_GET['delete']."'";
        $deleteR = mysqli_query($conn, $deleteQ);
        if($deleteR){
            mysqli_query($conn, "DELETE FROM delivery_challan_details WHERE challan_id = '".$_GET['delete']."'");
            $_SESSION['msg']['success'] = "Delivery challan delete successfully!";
        }else{
            $_SESSION['msg']['fail'] = "Delivery challan delete fail!";
        }
        header('Location: view-delivery-challan.php');exit;
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>View Delivery Challan - DigiBooks</title>
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
                  <a href="delivery-challan.php" class="btn btn-success p-2 pull-right" title="Add Bill"><i class="mdi mdi-plus-circle-outline"></i>Add Delivery Challan</a>
                  <h4 class="card-title">View Delivery Challan</h4>
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
                                        <th>Invoice Date</th>
                                        <th>Name</th>
                                        <th>Invoice No</th>
                                        <th>Bill Type</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                      $qry = "SELECT dc.id, dc.invoice_date, dc.invoice_no, dc.bill_type, dc.customer_name, dc.final_amount, lgr.name as l_customer_name FROM delivery_challan dc LEFT JOIN ledger_master lgr ON dc.customer_id = lgr.id WHERE dc.pharmacy_id = '".$pharmacy_id."' AND dc.financial_id = '".$financial_id."' ORDER BY dc.id DESC";
                                      $res = mysqli_query($conn, $qry);
                                      if($res){
                                        $i = 1;
                                        while ($row = mysqli_fetch_array($res)) {
                                    ?>
                                      <tr>
                                          <td><?php echo $i; ?></td>
                                          <td><?php echo (isset($row['invoice_date']) && $row['invoice_date'] != '') ? date('d/m/Y',strtotime($row['invoice_date'])) : ''; ?></td>
                                          <td>
                                            <?php
                                                echo (isset($row['l_customer_name']) && $row['l_customer_name'] != '') ? ucwords(strtolower($row['l_customer_name'])) : 'Unknown Customer';
                                                //echo (isset($row['is_general_sale']) && $row['is_general_sale'] == 1) ? '<small class="text-primary"> (General Sale)</small>' : '';
                                            ?>
                                          </td>
                                          <td><?php echo (isset($row['invoice_no'])) ? $row['invoice_no'] : ''; ?></td>
                                          <td><?php echo (isset($row['bill_type'])) ? $row['bill_type'] : ''; ?></td>
                                          <td class="text-right"><?php echo (isset($row['final_amount']) && $row['final_amount'] != '') ? amount_format(number_format($row['final_amount'], 2, '.', '')) : ''; ?></td>
                                          <td class="text-center">
                                              <!--<a href="print-delivery-challan.php?id=<?php //echo $row['id']; ?>" class="btn btn-primary p-2" title="Print" target="_blank"><i class="fa fa-print mr-0"></i></a>-->
                                            <a class="btn btn-behance p-2" href="delivery-challan.php?id=<?php echo $row['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                            <a class="btn  btn-danger p-2" href="view-delivery-challan.php?delete=<?php echo $row['id']; ?>" title="Delete" onclick="return confirm('Are you sure want to delete?')"><i class="fa fa-trash-o mr-0"></i></a>
                                            <a class="btn btn-info p-2" href="sales-tax-billing.php?challan=<?php echo $row['id']; ?>" title="Make Sale Bill">Make Sale Bill</a>
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
  
  <script>
     $('.datatable').DataTable();
  </script>
   
 <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <!-- End custom js for this page-->
</body>


</html>
