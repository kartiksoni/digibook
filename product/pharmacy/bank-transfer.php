<?php $title="Journal Vouchar"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>
<?php

  $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
  
  if(isset($_GET['id'])){
    $editquery = "SELECT id, voucher_date, voucher_no, remarks FROM `bank_transfer` WHERE id ='".$_GET['id']."' AND pharmacy_id = '".$pharmacy_id."'";
    $editresult = mysqli_query($conn,$editquery);

    if($editresult && mysqli_num_rows($editresult) > 0){
      $editdata = mysqli_fetch_assoc($editresult);
      if(isset($editdata['id']) && $editdata['id'] != ''){
        $editsubQuery = "SELECT * FROM bank_transfer_details WHERE voucher_id = '".$editdata['id']."'";
        $editsubRes = mysqli_query($conn, $editsubQuery);
        if($editsubRes && mysqli_num_rows($editsubRes) > 0){
          while ($editsubRow = mysqli_fetch_assoc($editsubRes)) {
            $editdata['detail'][] = $editsubRow;
          }
        }
      }
    }
  }

  
  if(isset($_POST['submit'])){
    $count = (isset($_POST['bank']) && !empty($_POST['bank'])) ? count($_POST['bank']) : 0;
    if($count > 0){

      if(isset($_GET['id']) && $_GET['id'] != ''){
        $voucher_no = (isset($_POST['voucher_no'])) ? $_POST['voucher_no'] : '';
      }else{
        $voucher_no = getBankTransferVoucherNo();
      }

      $voucher_date = (isset($_POST['voucher_date']) && $_POST['voucher_date'] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['voucher_date']))) : NULL;
      
      if(isset($_GET['id']) && $_GET['id'] != ''){
        $query = "UPDATE bank_transfer SET ";
      }else{
        $query = "INSERT INTO bank_transfer SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', ";
      }
      $query .= "voucher_date = '".$voucher_date."', voucher_no = '".$voucher_no."', ";

      if(isset($_GET['id']) && $_GET['id'] != ''){
        $query .= "modified = '".date('Y-m-d H:i:s')."', modifiedby = '".$_SESSION['auth']['id']."' WHERE id = '".$_GET['id']."'";
      }else{
        $query .= "created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
      }
      $res = mysqli_query($conn, $query);
      if($res){
          $voucher_id = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : mysqli_insert_id($conn);
          if(isset($_GET['id']) && $_GET['id'] != ''){
            $deleteQ = "DELETE FROM bank_transfer_details WHERE voucher_id = '".$_GET['id']."'";
            mysqli_query($conn, $deleteQ);
          }
          for ($i=0; $i < $count; $i++) { 
            $bank = (isset($_POST['bank'][$i])) ? $_POST['bank'][$i] : '';
            $debit = (isset($_POST['debit'][$i]) && $_POST['debit'][$i] != '') ? $_POST['debit'][$i] : 0;
            $credit = (isset($_POST['credit'][$i]) && $_POST['credit'][$i] != '') ? $_POST['credit'][$i] : 0;
            $remarks = (isset($_POST['remarks'][$i])) ? $_POST['remarks'][$i] : NULL;

            $subquery = "INSERT INTO bank_transfer_details SET voucher_id = '".$voucher_id."', bank_name = '".$bank."', debit = '".$debit."', credit = '".$credit."', remarks = '".$remarks."', created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
            mysqli_query($conn, $subquery);
          }
          if(isset($_GET['id']) && $_GET['id'] != ''){
            $_SESSION['msg']['success'] = "Bank Transfer update successfully";
          }else{
            $_SESSION['msg']['success'] = "Bank Transfer added successfully";
          }
          header('Location: view-bank-transfer.php');exit;
      }else{
        if(isset($_GET['id']) && $_GET['id'] != ''){
          $_SESSION['msg']['fail'] = "Bank Transfer fail! Try again.";
        }else{
          $_SESSION['msg']['fail'] = "Bank Transfer fail! Try again.";
        }
      }
    header('Location:'.basename($_SERVER['PHP_SELF']));exit;
    }else{
      $_SESSION['msg']['fail'] = "Somthing Want Wrong! Try again.";
      header('Location:'.basename($_SERVER['PHP_SELF']));exit;
    }
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks | <?php echo (isset($_GET['id']) && $_GET['id'] != '') ? 'Edit' : 'Add'; ?>Bank Transfer</title>
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
  <link rel="stylesheet" href="vendors/iconfonts/simple-line-icon/css/simple-line-icons.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />

  <link rel="stylesheet" href="css/parsley.css">
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
          <div class="row">
            <?php include "include/transaction_header.php"; ?>
           <!-- Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <!-- Main Catagory -->
                  
                  <form method="POST" autocomplete="off" enctype="multipart/form-data">
                    <div class="form-group row">
                      <div class="col-12 col-md-2">
                        <label>Voucher No. <span class="text-danger">*</span></label>
                        <input type="text" name="voucher_no" class="form-control" value="<?php echo (isset($editdata['voucher_no'])) ? $editdata['voucher_no'] : getBankTransferVoucherNo(); ?>" required>
                      </div>
                      <div class="col-12 col-md-2">
                        <label>Voucher Date. <span class="text-danger">*</span></label>
                        <div class="input-group date datepicker">
                          <input type="text" class="form-control" name="voucher_date"  value="<?php echo (isset($editdata['voucher_date']) && $editdata['voucher_date'] != '') ? date('d/m/Y',strtotime($editdata['voucher_date'])) : date('d/m/Y'); ?>" placeholder="DD/MM/YYYY" data-parsley-errors-container="#error-voucher-date" required>
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        <span id="error-voucher-date"></span>
                      </div>
                    </div>
                    <table id="entryTable" class="table">
                      <thead>
                        <tr>
                          <th width="20%">Bank</th>
                          <th class="investment-item <?php echo (isset($editdata['is_investment']) && $editdata['is_investment'] == 1) ? '' : 'display-none'; ?> ">Qty</th>
                          <th class="investment-item <?php echo (isset($editdata['is_investment']) && $editdata['is_investment'] == 1) ? '' : 'display-none'; ?>">Rate</th>
                          <th width="15%">Debit</th>
                          <th width="15%">Credit</th>
                          <th width="15%">Remarks</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>

                          <td>
                            <select class="form-control js-example-basic-single bank_transfer" data-name="first" style="width:100%" name="bank[]" required data-parsley-errors-container="#error-bank_transfer">
                              <option value="">Select Bank</option>
                              <?php 
                                $getbank = "SELECT id, name FROM ledger_master WHERE account_type = '5' AND status = 1 AND pharmacy_id = '".$pharmacy_id."'";
                                $getBankTransaction = mysqli_query($conn, $getbank);
                                if($getBankTransaction && mysqli_num_rows($getBankTransaction) > 0){
                                  while ($getBankTransferow = mysqli_fetch_assoc($getBankTransaction)) {
                            ?>
                                <option <?php echo (isset($editdata['detail'][0]['bank_name']) && $editdata['detail'][0]['bank_name'] == $getBankTransferow['id']) ? 'selected' : ''; ?> value="<?php echo $getBankTransferow['id']; ?>"><?php echo $getBankTransferow['name']; ?></option>
                              <?php } } ?>
                            </select>
                            <span id="error-bank_transfer"></span>
                          </td>

                          <td>
                            <input type="text" name="debit[]" class="form-control debit onlynumber" id="debit" placeholder="enter debit amount" value = "<?php echo (isset($editdata['detail'][0]['debit'])) ? $editdata['detail'][0]['debit'] : ''; ?>"  >
                          </td>

                          <td>
                            <input type="text" name="credit[]" class="form-control credit onlynumber" id="credit" placeholder="enter credit amount"  value = "<?php echo (isset($editdata['detail'][0]['credit'])) ? $editdata['detail'][0]['credit'] : ''; ?>" >
                          </td>

                          <td>
                            <textarea name="remarks[]" class="form-control" placeholder="Enter Remarks" rows="1" id="remarks" style="resize:vertical;"><?php echo (isset($editdata['detail'][0]['remarks'])) ? $editdata['detail'][0]['remarks'] : ''; ?></textarea>
                          </td>

                        </tr>

                        <tr>
                          <td>
                            <select class="form-control js-example-basic-single bank_transfer" data-name="second" style="width:100%" name="bank[]" required data-parsley-errors-container="#error-bank_transfer1">
                              <option value="">Select Bank</option>
                              <?php 
                                $getbank = "SELECT id, name FROM ledger_master WHERE account_type = '5' AND status = 1 AND pharmacy_id = '".$pharmacy_id."'";
                                  $getBankTransaction = mysqli_query($conn, $getbank);
                                  if($getBankTransaction && mysqli_num_rows($getBankTransaction) > 0){
                                  while ($getBankTransferow1 = mysqli_fetch_assoc($getBankTransaction)) {
                              ?>
                                <option <?php echo (isset($editdata['detail'][0]['bank_name']) && $editdata['detail'][1]['bank_name'] == $getBankTransferow1['id']) ? 'selected' : ''; ?> value="<?php echo $getBankTransferow1['id']; ?>"><?php echo $getBankTransferow1['name']; ?></option>
                              <?php } } ?>
                            </select>
                            <span id="error-bank_transfer1"></span>
                          </td>
                          
                          <td>
                            <input type="text" name="debit[]"  value = "<?php echo (isset($editdata['detail'][1]['debit'])) ? $editdata['detail'][1]['debit'] : ''; ?>" class="form-control debit1 onlynumber" id="debit1" placeholder="enter debit amount">
                          </td>

                          <td>
                            <input type="text" name="credit[]" value = "<?php echo (isset($editdata['detail'][1]['credit'])) ? $editdata['detail'][1]['credit'] : ''; ?>" class="form-control credit1 onlynumber" id = "credit1" placeholder="enter credit amount">
                          </td>

                          <td>
                            <textarea name="remarks[]" class="form-control" placeholder="Enter Remarks" id="remarks1" rows="1" style="resize:vertical;"><?php echo (isset($editdata['detail'][1]['remarks'])) ? $editdata['detail'][1]['remarks'] : ''; ?></textarea>
                          </td>
                       
                        </tr>

                      </tbody>
                    </table>
                    <a href="view-bank-transfer.php" class="btn btn-light mt-30 pull-left" name="submit">Back</a>
                    <button name="submit" type="submit" class="btn btn-success mt-30 pull-right" style="float:right;"><?php echo (isset($_GET['id']) && $_GET['id'] != '') ? 'Update Bank Transfer' : 'Add Bank Transfer'; ?></button>
                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        
        <!-- partial:partials/_footer.php -->
        <?php include "include/footer.php" ?>
        <!-- partial -->
        
          
        <!-- Add New Product Model -->
        <?php include "include/addproductmodel.php" ?>
     
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
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script> 
  <script>
     $('.datatable').DataTable();
     $('.datepicker').datepicker({
        enableOnReadonly: true,
        todayHighlight: true,
        format: 'dd/mm/yyyy',
        autoclose : true
      });
  </script>

  <script src="js/parsley.min.js"></script>
  <script type="text/javascript">
    $('form').parsley();
  </script>
  <script src="js/custom/onlynumber.js"></script>
  <script src="js/custom/bank_transfer.js"></script>
  
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  
  <!-- End custom js for this page-->
</body>


</html>
