<?php include('include/usertypecheck.php');?>
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
          <?php include('include/flash.php'); ?>
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
                          <a href="purchase.php" class="btn btn-dark active">Purchase Bill</a>
                          <a href="purchase-return.php" class="btn btn-dark">Purchase Return</a>
                          <a href="purchase-return-list.php" class="btn btn-dark">Purchase Return List</a>
                          <a href="#" class="btn btn-dark btn-fw">Cancel List</a>
                          <a href="purchase-history.php" class="btn btn-dark btn-fw">History</a>
                          <a href="#" class="btn btn-dark btn-fw">Settings</a>
                      </div>   
                    </div> 
                  </div>

                  <hr/>
                  <br/>
                  <form class="forms-sample" method="POST">
                  
                    <div class="form-group row">
                    
                      <div class="col-12 col-md-3">
                        <label for="exampleInputName1">Vendor Name</label>
                        <select class="js-example-basic-single" style="width:100%" name="vendor_id"> 
                            <option value="">Select Vendor</option>
                            <?php 
                              $vendorQuery = "SELECT id, name FROM ledger_master WHERE group_id = 14 AND status = 1 ORDER BY name"; 
                              $vendorRes = mysqli_query($conn, $vendorQuery);
                              if($vendorRes && mysqli_num_rows($vendorRes)){
                                while ($vendorRow = mysqli_fetch_array($vendorRes)) {
                            ?>
                              <option value="<?php echo $vendorRow['id']; ?>" <?php echo (isset($_POST['vendor_id']) && $_POST['vendor_id'] == $vendorRow['id']) ? 'selected' : ''; ?>><?php echo $vendorRow['name']; ?></option>
                            <?php } } ?>
                        </select>
                      </div>
                        
                      <!-- <div class="col-12 col-md-2">
                       <label for="invoice_no">Invoice Number</label>
                       <input type="text" name="invoice_no" class="form-control" placeholder="Invoice Number">
                      </div> -->
                        
                      <div class="col-12 col-md-3">
                       <label for="product">Product</label>
                       <input type="text" class="form-control tags" id="product" placeholder="Product" id="product">
                       <input type="hidden" class="pr_id" name="pr_id">
                       <small class="text-danger empty-message0"></small>
                      </div>
                        
                      <!-- <div class="col-12 col-md-2">
                       <label for="gr_no">GR.No</label>
                       <input type="text" class="form-control" id="gr_no" name="gr_no" placeholder="GR.No">
                      </div> -->

                      <!-- <div class="col-12 col-md-2">
                       <label for="batch">Batch</label>
                       <input type="text" class="form-control" id="batch" name="batch" placeholder="Batch Number">
                      </div> -->
                        
                      <div class="col-12 col-md-2">
                       <label for="return_date">Returned Date</label>
                         <div class="input-group date datepicker">
                            <input type="text" class="form-control border datepicker" name="return_date" value="<?php echo (isset($_POST['return_date']) && $_POST['return_date'] != '') ? $_POST['return_date'] : ''; ?>" autocomplete="off">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                      </div>
                        
                      <div class="col-12 col-md-12">
                        <button type="submit" class="btn btn-success mt-30 pull-right">Submit</button>
                      </div>

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
                        <table class="table datatable">
                          <thead>
                            <tr>
                                <th>Return No</th>
                                <th>Return Date</th>
                                <!-- <th>Invoice/GR</th> -->
                                <!-- <th>Invoice Date</th> -->
                                <th>Vendor</th>
                                <th>Mobile</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <!-- <th>Payment Status</th>
                                <th>Payment Remarks</th> -->
                                <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <!-- Row Starts -->
                            <?php 
                              $getAllDataQuery = "SELECT pr.id, pr.debit_note_date, pr.debit_note_no, pr.remarks, pr.debit_note_settle, lgr.name as vendor_name, lgr.mobile FROM purchase_return pr INNER JOIN ledger_master lgr ON pr.vendor_id = lgr.id ";

                              $where = array();

                                if(isset($_POST['vendor_id']) && $_POST['vendor_id'] != ''){
                                  $where[] .= "lgr.id=".$_POST['vendor_id'];
                                }
                                if(isset($_POST['return_date']) && $_POST['return_date'] != ''){
                                  $where[] .= "pr.debit_note_date='".date('Y-m-d',strtotime(str_replace('/','-',$_POST['return_date'])))."'";
                                  
                                }
                                if(isset($_POST['pr_id']) && $_POST['pr_id'] != ''){
                                  $where[] .= "pr.id=".$_POST['pr_id'];
                                }
                                
                              if(!empty($where)){
                                $where = implode(" AND ",$where);
                                $getAllDataQuery .="WHERE ".$where;
                              }
                              $getAllDataQuery .= " ORDER BY pr.id DESC";
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
                                    <td><?php echo (isset($row['remarks'])) ? $row['remarks'] : ''; ?></td>
                                    <td>
                                      <span class="status">
                                        <?php 
                                          if(isset($row['debit_note_settle']) && $row['debit_note_settle'] == 0){
                                            echo '<div class="badge badge-outline-warning">On Hold</div>';
                                          }elseif(isset($row['debit_note_settle']) && $row['debit_note_settle'] == 1){
                                            echo '<div class="badge badge-outline-success">Effect In Party Ledger</div>';
                                          }else{
                                            echo '<div class="badge badge-outline-danger">Close</div>';
                                          } 
                                        ?>
                                      </span>
                                    </td>
                                    <!-- <td>-</td>
                                    <td>-</td> -->
                                    <td>
                                      <a href="purchase-return-print.php?id=<?php echo $row['id']; ?>" class="btn  btn-behance p-2"><i class="fa fa-print mr-0"></i></a>
                                      <a href="purchase-return.php?id=<?php echo $row['id']; ?>" class="btn  btn-behance p-2"><i class="fa fa-pencil mr-0"></i></a>
                                      <a href="#" class="btn  btn-danger p-2">Cancel</a>
                                      <button class="btn btn-primary p-2 btn-applycr" data-id="<?php echo $row['id']; ?>">Apply Credit Note</button>
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
  
  <script>
     $('.datatable').DataTable();
  </script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/parsley.min.js"></script>
  <script type="text/javascript">
    $('form').parsley();
  </script>
  <script src="js/custom/purchase-return-list.js"></script>
  <!-- End custom js for this page-->
</body>


</html>
