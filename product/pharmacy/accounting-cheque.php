<?php include('include/usertypecheck.php');

if (isset($_POST['submit'])) {

  $user_id = $_SESSION['auth']['id'];
  $voucherno = $_POST['voucherno'];
  $date = $_POST['date'];
  $bank = $_POST['bank'];
  $vouchertype = $_POST['voucher_type'];
  $cr_dr = $_POST['cr_dr'];
  $group = $_POST['group'];
  $perticular = $_POST['perticular'];
  $chequeno = $_POST['cheque'];
  $chequedate = $_POST['cheque_date'];
  $amount = $_POST['amount'];
  $remark = $_POST['remark'];

  if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
  
    $editid = $_REQUEST['id'];

    $editqry = "UPDATE `accounting_cheque` SET `voucher_no`= '".$voucherno."', `voucher_date`= '".$date."', `bank_name`= '".$bank."',`voucher_type`= '".$vouchertype."', `credit_debit`= '".$cr_dr."', `group_id`= '".$group."', `perticular`= '".$perticular."',`cheque_no`= '".$chequeno."', `cheque_date`= '".$chequedate."', `amount`= '".$amount."', `remark`= '".$remark."', `modified`= '".date('Y-m-d H:i:s')."', `modifiedby`= '".$user_id."' WHERE id = '".$editid."'";

    $editrun = mysqli_query($conn, $editqry);
    if($editrun){

      $_SESSION['msg']['success'] = 'Data Updated Successfully.';
      header('location:accounting-cheque.php');
      exit;
    }
    else{
      $_SESSION['msg']['fail'] = 'Updated fail.';
    }

  }
  else{
  $addqry = "INSERT INTO `accounting_cheque`(`voucher_no`, `voucher_date`, `bank_name`, `voucher_type`, `credit_debit`, `group_id`, `perticular`, `cheque_no`, `cheque_date`, `amount`, `remark`, `created`, `createdby`) VALUES ('" . $voucherno . "', '" . $date . "', '" . $bank . "', '" . $vouchertype . "', '" . $cr_dr . "', '" . $group . "', '" . $perticular . "', '" . $chequeno . "', '" . $chequedate . "', '" . $amount . "', '" . $remark . "', '" . date('Y-m-d H:i:s') . "', '" . $user_id . "')";

  $addrun = mysqli_query($conn, $addqry);

  if ($addrun) {

    $_SESSION['msg']['success'] = 'Data Added Successfully.';
  } else {

    $_SESSION['msg']['fail'] = 'Added Fail';
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
  <title>DigiBooks</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/iconfonts/puse-icons-feather/feather.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" />
  <link rel="stylesheet" href="css/parsley.css">
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
        <?php function getcashpaymentno()
        {
          global $conn;
          $voucher_no = '';

          $voucherqry = "SELECT * FROM accounting_cheque ORDER BY id DESC LIMIT 1";
          $voucherrun = mysqli_query($conn, $voucherqry);
          if ($voucherrun) {
            $count = mysqli_num_rows($voucherrun);
            if ($count !== '' && $count !== 0) {
              $row = mysqli_fetch_assoc($voucherrun);
              $voucherno = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';

              if ($voucherno != '') {
                $vouchernoarr = explode('-', $voucherno);
                $voucherno = $vouchernoarr[1];
                $voucherno = $voucherno + 1;
                $voucherno = sprintf("%05d", $voucherno);
                $voucher_no = 'CP-' . $voucherno;
              }
            } else {
              $voucherno = sprintf("%05d", 1);
              $voucher_no = 'CP-' . $voucherno;
            }
          }
          return $voucher_no;
        }
        ?>
       
       <!-- Left Navigation -->
        <?php include "include/sidebar-nav-left.php" ?>
        
        
      
      
      <div class="main-panel">
      
        <div class="content-wrapper">
          <?php include('include/flash.php'); ?>
          <span id="errormsg"></span>
            <form class="forms-sample" method="post" action="">
              <div class="row">
          
          
                <!-- Form -->
                <div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                
                      <!-- Main Catagory -->
                      <div class="row">
                        <div class="col-12">
                          <div class="purchase-top-btns">
                            <a href="accounting-cash-management.php" class="btn btn-dark ">Cash Management</a>
                            <a href="accounting-customer-receipt.php" class="btn btn-dark btn-fw">Customer Receipt</a>
                            <a href="accounting-cheque.php" class="btn btn-dark  btn-fw active">Cheque</a>
                            <a href="accounting-vendor-payments.php" class="btn btn-dark  btn-fw">Vendor Payment</a>
                            <a href="financial-year.php" class="btn btn-dark  btn-fw">Financial Year Settings</a>
                            <a href="purchase-return.php" class="btn btn-dark  btn-fw">Credit Note / Purchase Note</a>
                            <a href="quotation-estimate-proformo-invoice.php" class="btn btn-dark  btn-fw">Quotation / Estimate / Proformo Invoice</a>    
                          </div>   
                        </div> 
                      </div>
                      <hr>
                    
                      <!-- First Row  -->

                      <?php
                      if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
                        $id = $_REQUEST['id'];
                        $accountdataqry = "select * from accounting_cheque where id = '".$id."'";
                        $accountdatarun = mysqli_query($conn, $accountdataqry);
                        $accountdata = mysqli_fetch_assoc($accountdatarun);
                      }
                      ?>
                    
                      <div class="form-group row">
                        
                        <div class="col-12">
                          <label for="exampleInputName1" class="pull-right bg-success color-white p-2">Running Balance: 123456</label>
                        </div>     
                        
                      </div>
                      
                      <div class="form-group row">
                        
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Voucher No.</label>
                          <?php
                          $voucher = getcashpaymentno();
                          ?>
                          <input type="text" class="form-control" id="exampleInputName1" name="voucherno" placeholder="Voucher No." value="<?php if(isset($_REQUEST['id'])){echo $accountdata['voucher_no'];}else{echo $voucher; } ?>">
                        </div>
                        
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Date</label>
                          <div id="datepicker-popup1" class="input-group date datepicker">
                            <input type="text" class="form-control border" name="date" value="<?php if(isset($_REQUEST['id'])){echo $accountdata['voucher_date'];}else{echo date("d/m/Y"); } ?>">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                        </div>
                        
                        
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Bank Name</label>
                          <select class="js-example-basic-single" style="width:100%" name="bank"> 
                            <option value="SBI" <?php if(isset($_REQUEST['id']) && $accountdata['bank_name'] == "SBI"){echo "selected";}?>>SBI</option>
                            <option value="AXIS" <?php if(isset($_REQUEST['id']) && $accountdata['bank_name'] == "AXIS"){echo "selected";}?>>AXIS</option>
                          </select>
                        </div>
                        
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Voucher Type</label>
                            <select class="js-example-basic-single" style="width:100%" name="voucher_type"> 
                              <option value="payment" <?php if(isset($_REQUEST['id']) && $accountdata['voucher_type'] == "payment"){echo "selected";}?>>Payment</option>
                              <option value="receipt" <?php if(isset($_REQUEST['id']) && $accountdata['voucher_type'] == "receipt"){echo "selected";}?>>Receipt</option>
                            </select>
                        </div>
                      </div>
                    </div>    
                  </div>
                </div>
              </div>
            
     
            
               <!-- Table ------------------------------------------------------------------------------------------------------>
            
              <div class="col-md-12 grid-margin stretch-card">
              	<div class="card">
                  <div class="card-body">
                
                    <!-- TABLE STARTS -->
                    <div class="col mt-3">
                    	<div class="row">
                        <div class="col-12">
                          <table id="order-listing1" class="table">
                            <thead>
                              <tr>
                                <th>Credit/Debit</th>
                                <th>Type</th>
                                <th>Perticulars</th>
                                <th>Cheque No.</th>
                                <th>Cheque Date</th>
                                <th>Amount</th>
                              </tr>
                            </thead>
                              <tbody>
                                <!-- Row Starts --> 	
                                <tr>
                                  <td><input type="text" class="form-control" id="exampleInputName1" name="cr_dr" placeholder="Cr/Dr"       required="" value="<?php if(isset($_REQUEST['id'])){echo $accountdata['credit_debit']; }?>">
                                  </td>

                                  <td><select class="js-example-basic-single" name="group" style="width:100%" id="group" required=""> 
                                        <option value="">Please Select</option>
                                          <?php
                                          $dataqry = "SELECT * FROM `group`";
                                          $datarun = mysqli_query($conn, $dataqry);
                                          while ($data = mysqli_fetch_assoc($datarun)) { ?>
                                          <option value="<?php echo $data['id']; ?>" <?php echo (isset($accountdata['group_id']) && $accountdata['group_id'] == $data['id']) ? 'selected' : ''; ?>> <?php echo $data['name']; ?></option>
                                          <?php  } ?>     
                                      </select>
                                  </td>

                                  <td>
                                    <select class="js-example-basic-single" name="perticular" style="width:100%" id="ledger" required=""> 
                                      <option value="">Please Select</option>
                                      <?php
                                      $sqlqry = "SELECT * FROM `ledger_master` WHERE group_id = '".$accountdata['group_id']."'";
                                      $sqlqryrun = mysqli_query($conn, $sqlqry);
                                      while ($sqldata = mysqli_fetch_assoc($sqlqryrun)) { ?>
                                        <option value="<?php echo $sqldata['id']; ?>" <?php echo (isset($accountdata['perticular']) && $accountdata['perticular'] == $sqldata['id']) ? 'selected' : '';?>> <?php echo $sqldata['name']; ?> 
                                      </option>
                                      <?php } ?>
                                    </select>
                                  </td>

                                  <td><input type="text" class="form-control" name="cheque" id="exampleInputName1" placeholder="Cheque      No" value="<?php if(isset($_REQUEST['id'])){echo $accountdata['cheque_no']; }?>">
                                  </td>

                                  <td>
                                    <div id="datepicker-popup2" class="input-group date datepicker" >
                                      <input type="text" name="cheque_date" class="form-control border" value="<?php if(isset($_REQUEST['id'])){echo $accountdata['cheque_date'];}else{echo date("d/m/Y"); }?>" required="">
                                      <span class="input-group-addon input-group-append border-left">
                                        <span class="mdi mdi-calendar input-group-text"></span>
                                      </span>
                                    </div>
                                  </td>
                                      
                                  <td><input type="text" name="amount" class="form-control" id="exampleInputName1" 
                                      placeholder="Enter Amount" data-parsley-type="number" required="" value="<?php if(isset($_REQUEST['id'])){echo $accountdata['amount']; }?>">
                                  </td>
                                      
                                </tr><!-- End Row --> 	
                                  
                                <tr>
                                  <td>
                                    <label for="exampleInputName1">Remarks</label>
                                      <textarea  class="form-control" name="remark" id="exampleInputName1" 
                                        placeholder="Remarks" rows="3"><?php if(isset($_REQUEST['id'])){echo $accountdata['remark']; }?>
                                      </textarea> 
                                  </td>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
                              </tbody>
                          </table>
                            
                            <div class="col-12">
                            	<a href="accounting-cheque-list.php" class="btn btn-dark mt-30 pull-left">Back</a>
                              <button type="submit" name="submit" class="btn btn-success mt-30 pull-right">Submit</button>
                            </div>                
                        </div>   
                      </div>
                    </div>
                  </div>
                </div>  
              </div>
            </form>
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
  
  
  <!-- Datepicker Initialise-->
 <script>
    $('#datepicker-popup1').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy',
      autoclose : true
    });
 </script>
 
 <script>
    $('#datepicker-popup2').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy',
      autoclose : true
    });
 </script>

 <script src="js/jquery-ui.js"></script>
 <script src="js/custom/accounting-cheque.js"></script>

  <!-- script for custom validation -->
  <script src="js/parsley.min.js"></script>
  <script type="text/javascript">
  $('form').parsley();
  </script>
  
  <!-- End custom js for this page-->
</body>


</html>
