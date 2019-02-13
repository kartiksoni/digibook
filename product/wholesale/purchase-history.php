<?php $title = "History"; ?>
<?php 
    include('include/usertypecheck.php');
    include('include/permission.php');
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
    
        
        
        <!-- partial:partials/_settings-panel.html -->
        
        <!--<div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="mdi mdi-settings"></i></div>
        <div id="theme-settings" class="settings-panel">
        <i class="settings-close mdi mdi-close"></i>
        <p class="settings-heading">SIDEBAR SKINS</p>
        <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Light</div>
        <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark</div>
        <p class="settings-heading mt-2">HEADER SKINS</p>
        <div class="color-tiles mx-0 px-4">
          <div class="tiles primary"></div>
          <div class="tiles success"></div>
          <div class="tiles warning"></div>
          <div class="tiles danger"></div>
          <div class="tiles pink"></div>
          <div class="tiles info"></div>
          <div class="tiles dark"></div>
          <div class="tiles default"></div>
        </div>
        </div>
        </div>-->
        
        
        <!-- Right Sidebar -->
        <?php include "include/sidebar-right.php" ?>
        
       
       <!-- Left Navigation -->
        <?php include "include/sidebar-nav-left.php" ?>
        
        
      
      
      <div class="main-panel">
      
        <div class="content-wrapper">
          <div class="row">
          
          
           <!-- Bank Management Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                
                  <!-- Main Catagory -->
                    <div class="row">
                    <div class="col-12">
                        <div class="purchase-top-btns">
                          <?php if((isset($user_sub_module) && in_array("Purchase Bill", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="purchase.php" class="btn btn-dark active">Purchase Bill</a>
                          <?php } if((isset($user_sub_module) && in_array("Purchase Return", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="purchase-return.php" class="btn btn-dark">Purchase Return</a>
                          <?php } if((isset($user_sub_module) && in_array("Purchase Return List", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="purchase-return-list.php" class="btn btn-dark">Purchase Return List</a>
                          <?php } if((isset($user_sub_module) && in_array("Cancel List", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="purchase-cancel-list.php" class="btn btn-dark btn-fw">Cancel List</a>
                          <?php } if((isset($user_sub_module) && in_array("History", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="purchase-history.php" class="btn btn-dark btn-fw">History</a>
                          <?php }  if((isset($user_sub_module) && in_array("Settings", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <!--<a href="#" class="btn btn-dark btn-fw">Settings</a>-->
                          <?php } ?>
                        </div>     
                    </div> 
                    </div>
                    <hr>
                    
                
                
                 <br>
                  <form class="forms-sample">
                  
                  <div class="form-group row">
                  
                      <!------<div class="col-12 col-md-3">
                       <label for="exampleInputName1">Select anyone</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                <option value="Regular">Alaska</option>
                                <option value="Unregistered">Alaska</option>
                            </select>
                      </div> 
                      
                      <div class="col-12 col-md-3">
                        <button type="submit" class="btn btn-success mt-30">Submit</button>
                      </div> !----->
                      
                     
                    
                  </div> 
                  
                 
                   
                   
                  </form>
                </div>
              </div>
            </div>
            
           
            
             <!-- Table ------------------------------------------------------------------------------------------------------>
            
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                <div class="card-body">
                
                  <!-- TABLE Filters btn -->
                   
                    
                    <!-- TABLE STARTS -->
                    <div class="col mt-3">
                       <div class="row">
                            <div class="col-12">
                              <table id="order-listing1" class="table">
                                <thead>
                                  <tr>
                                      <th>Sr No.</th>
                                      <th>Invoice No.</th>
                                      <th>Inv. date</th>
                                      <th>Vendor</th>
                                      <th>Mobile</th>
                                      <th>Taxable Amount</th>
                                      <th>Tax Amount</th>
                                      <th>Inv. total amount</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php
                                  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
                                  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

                                  $alldataqry = "SELECT pr.id, pr.invoice_no,pr.total_tax as tax_amount, pr.invoice_date, pr.total_total as invoce_total_amount, pr.cancel,pr.total_amount,lg.name as vendor_name, lg.mobile, lg.id as lg_id FROM purchase pr INNER JOIN ledger_master lg ON pr.vendor = lg.id WHERE pr.pharmacy_id = '".$pharmacy_id."' AND pr.financial_id = '".$financial_id."' ORDER BY pr.id DESC";
                                  $alldatarun = mysqli_query($conn, $alldataqry);

                                  if($alldatarun){
                                    $count = 0;
                                    while($data = mysqli_fetch_assoc($alldatarun)){
                                      $count++;
                                ?>
                                  <!-- Row Starts -->   
                                  <tr>
                                      <td><?php echo $count; ?></td>
                                      <td><?php echo (isset($data['invoice_no'])) ? $data['invoice_no']: '';?></td>
                                      <td><?php echo (isset($data['invoice_date']) && $data['invoice_date'] != '') ? date('d/m/Y', strtotime($data['invoice_date'])) : '';?></td>
                                      <td><?php echo (isset($data['vendor_name'])) ? $data['vendor_name']: '';?></td>
                                      <td><?php echo (isset($data['mobile'])) ? $data['mobile']: '';?></td>
                                      <td class="text-right"><?php echo (isset($data['total_amount'])) ? amount_format(number_format($data['total_amount'], 2, '.', '')): '';?></td>
                                      <td class="text-right"><?php echo (isset($data['tax_amount'])) ? amount_format(number_format($data['tax_amount'], 2, '.', '')): '';?></td>
                                      <td class="text-right"><?php echo (isset($data['invoce_total_amount']) && $data['invoce_total_amount'] != '') ? amount_format(number_format($data['invoce_total_amount'], 2, '.', '')) : '';?></td>
                                      <td class="action">
                                        <?php if(isset($data['cancel']) && $data['cancel'] == 1){ ?>
                                          <a href="purchase-history-print.php?id=<?php echo $data['lg_id']; ?>" target="_blank" class="btn  btn-behance p-2"><i class="fa fa-print mr-0"></i></a>
                                          <a href="purchase.php?id=<?php echo $data['id']; ?>" class="btn btn-primary btn-xs pt-2 pb-2"><i class="fa fa-pencil mr-0 ml-0"></i></a>
                                          <a href="purchase-return.php?bill=<?php echo $data['id']; ?>" class="btn btn-primary btn-xs pt-2 pb-2">Return</a>
                                          <button type="button" data-id="<?php echo $data['id']; ?>" class="btn btn-danger btn-xs pt-2 pb-2 btn-cancel-bill">Cancel</button>
                                        <?php }else{ ?>
                                          <a href="javascript:void(0);" class="btn btn-danger btn-xs pt-2 pb-2">Cancelled Bill</a>
                                        <?php } ?>
                                      </td>
                                  </tr><!-- End Row --> 
                                    <?php } } ?>
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
  
  <!-- Custom js for this page Modal Box-->
  <script src="js/modal-demo.js"></script>
  <script src="js/custom/purchase-history.js"></script>
  
  <!-- Datepicker Initialise-->
 <script>
    $('#datepicker-popup1').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
    });
 </script>
 
 <script>
    $('#datepicker-popup2').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
    });
 </script>

 
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('#order-listing2').DataTable();
  </script>
  
  <script>
     $('#order-listing1').DataTable();
  </script>
  
  
  <!-- End custom js for this page-->
</body>


</html>
