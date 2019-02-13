<?php $title = "Purchase Return List"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>
<?php 
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Purchase Return List</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/iconfonts/puse-icons-feather/feather.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
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
  <!-- parsley css for validation -->
  <link rel="stylesheet" href="css/parsley.css">
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

                  <hr/>
                  <br/>
                  
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
                        <table class="table datatable">
                          <thead>
                            <tr>
                                <th>Return No</th>
                                <th>Return Date</th>
                                <th>Vendor</th>
                                <th>Mobile</th>
                                <th>Tax Amount</th>
                                <th>Taxable Amount</th>
                                <th>Final Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <!-- Row Starts -->
                            <?php 
                              $getAllDataQuery = "SELECT pr.id, pr.debit_note_date, pr.debit_note_no, pr.remarks,pr.totalamount,(pr.igst + pr.cgst + pr.sgst) as tax_amount,pr.finalamount, pr.debit_note_settle, lgr.name as vendor_name, lgr.id as lg_id, lgr.mobile FROM purchase_return pr INNER JOIN ledger_master lgr ON pr.vendor_id = lgr.id ";
                            
                              $where = array();

                                if(isset($_POST['vendor_id']) && $_POST['vendor_id'] != ''){
                                  $where[] .= "lgr.id=".$_POST['vendor_id'];
                                }
                                if(isset($_POST['return_date']) && $_POST['return_date'] != ''){
                                  $where[] .= "pr.debit_note_date='".date('Y-m-d',strtotime(str_replace('/','-',$_POST['return_date'])))."'";
                                }
                              $where[] .= "pr.pharmacy_id = '".$pharmacy_id."'";
                              $where[] .= "pr.financial_id = '".$financial_id."'";
                              if(!empty($where)){
                                $where = implode(" AND ",$where);
                                $getAllDataQuery .="WHERE ".$where;
                              }

                              $getAllDataQuery .= " ORDER BY pr.id DESC";
                              //print_r($getAllDataQuery);exit;
                              $getAllDataRes = mysqli_query($conn, $getAllDataQuery );
                            ?>

                            <?php if($getAllDataRes && mysqli_num_rows($getAllDataRes) > 0){ ?>
                              <?php while ($row = mysqli_fetch_array($getAllDataRes)) { ?>
                                <tr id="tr-<?php echo $row['id']; ?>">
                                    <td><?php echo (isset($row['debit_note_no'])) ? $row['debit_note_no'] : ''; ?></td>
                                    <td>
                                      <?php echo (isset($row['debit_note_date']) && $row['debit_note_date'] != '') ? date('d/m/Y',strtotime($row['debit_note_date'])) : ''; ?>
                                    </td>
                                    <td><?php echo (isset($row['vendor_name'])) ? $row['vendor_name'] : ''; ?></td>
                                    <td><?php echo (isset($row['mobile'])) ? $row['mobile'] : ''; ?></td>
                                    <td class="text-right"><?php echo (isset($row['totalamount']) && $row['totalamount'] != '') ? amount_format(number_format($row['totalamount'], 2, '.', '')) : ''; ?></td>
                                    <td class="text-right"><?php echo (isset($row['tax_amount']) && $row['tax_amount'] != '') ? amount_format(number_format($row['tax_amount'], 2, '.', '')) : ''; ?></td>
                                    <td class="text-right"><?php echo (isset($row['finalamount']) && $row['finalamount'] != '') ? amount_format(number_format($row['finalamount'], 2, '.', '')) : ''; ?></td>
                                    <!--<td><?php echo (isset($row['remarks'])) ? $row['remarks'] : ''; ?></td>-->
                                    <td>
                                      <span class="status">
                                        <?php 
                                          if(isset($row['debit_note_settle']) && $row['debit_note_settle'] == 1){
                                            echo '<div class="badge badge-outline-warning">On Hold</div>';
                                          }elseif(isset($row['debit_note_settle']) && $row['debit_note_settle'] == 0){
                                            echo '<div class="badge badge-outline-success">Effect In Party Ledger</div>';
                                          }else{
                                            echo '<div class="badge badge-outline-danger">Close</div>';
                                          } 
                                        ?>
                                      </span>
                                    </td>
                                    <!--<td>-</td>
                                    <td>-</td>-->
                                    <td>
                                      <a href="purchase-return-print.php?id=<?php echo $row['lg_id']; ?>" class="btn  btn-behance p-2"><i class="fa fa-print mr-0"></i></a>
                                      <a href="purchase-return.php?id=<?php echo $row['id']; ?>" class="btn  btn-behance p-2"><i class="fa fa-pencil mr-0"></i></a>
                                      <a href="#" class="btn  btn-danger p-2">Cancel</a>
                                      <!--<button class="btn btn-primary p-2 btn-applycr" data-id="<?php //echo $row['id']; ?>">Apply Credit Note</button>-->
                                    </td>
                                </tr><!-- End Row -->
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
        <!-- content-wrapper ends -->
        
        <!-- partial:partials/_footer.php -->
        <?php include "include/footer.php";?>
        <!-- partial -->

        <?php include "popup/apply-creditnote-model.php";?>
    
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
  
  
  <!-- Datepicker Initialise-->
 <script>
    $('.datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy',
      autoclose: true
    });
 </script>

 
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script> 
  <script src="js/jquery-ui.js"></script>
  
  <script>
     $('.datatable').DataTable();
  </script>

  <script src="js/parsley.min.js"></script>
  <script type="text/javascript">
    $('form').parsley();
  </script>
  <script src="js/custom/purchase-return-list.js"></script>
  
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <!-- End custom js for this page-->
</body>


</html>
