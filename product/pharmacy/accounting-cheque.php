<?php $title = "Bank Transaction"; ?>
<?php include('include/usertypecheck.php');
include('include/permission.php');

if (isset($_POST['submit'])) {
  $user_id = $_SESSION['auth']['id'];
  
  if(isset($_GET['id']) && $_GET['id'] != ''){
      $voucherno = $_POST['voucherno'];
  }else{
      $voucherno = getaccountingchequevoucher($_POST['voucher_type']);
  }
  $date = date('Y-m-d', strtotime(str_replace('/','-',$_POST['date'])));
  $bank = $_POST['bank'];
  $vouchertype = $_POST['voucher_type'];
  $cr_dr = $_POST['credit_debit'];
  $group = $_POST['group'];
  $perticular = $_POST['perticular'];
  $chequeno = $_POST['cheque'];
  $chequedate = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['cheque_date'])));
  $amount = $_POST['amount'];
  $remark = $_POST['remark'];
  $reverse_change = $_POST['reversechange'];
  $reverse_change_gst = $_POST['gst'];
  $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : NULL;
  $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : NULL;
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
  $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;

  if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
  
    $editid = $_REQUEST['id'];

    $editqry = "UPDATE `accounting_cheque` SET `voucher_no`= '".$voucherno."', `voucher_date`= '".$date."', `bank_name`= '".$bank."',`voucher_type`= '".$vouchertype."', `credit_debit`= '".$cr_dr."', `group_id`= '".$group."', `perticular`= '".$perticular."',`cheque_no`= '".$chequeno."', `cheque_date`= '".$chequedate."', `amount`= '".$amount."', `remark`= '".$remark."',`reverse_change`='".$reverse_change."',`reverse_change_gst`='".$reverse_change_gst."', `modified`= '".date('Y-m-d H:i:s')."', `modifiedby`= '".$user_id."' WHERE id = '".$editid."'";

    $editrun = mysqli_query($conn, $editqry);
    if($editrun){

      $_SESSION['msg']['success'] = 'Transaction Updated Successfully.';
      header('location:accounting-cheque-list.php');exit;
    }
    else{
      $_SESSION['msg']['fail'] = 'Transaction Updated fail.';
      header('location:accounting-cheque.php');exit;
    }

  }
  else{
  $addqry = "INSERT INTO `accounting_cheque`(`owner_id`, `admin_id`, `pharmacy_id`, `financial_id`, `voucher_no`, `voucher_date`, `bank_name`, `voucher_type`, `credit_debit`, `group_id`, `perticular`, `cheque_no`, `cheque_date`, `amount`, `remark`,`reverse_change`,`reverse_change_gst`,`created`, `createdby`) VALUES ('".$owner_id."', '".$admin_id."', '".$pharmacy_id."', '".$financial_id."', '" . $voucherno . "', '" . $date . "', '" . $bank . "', '" . $vouchertype . "', '" . $cr_dr . "', '" . $group . "', '" . $perticular . "', '" . $chequeno . "', '" . $chequedate . "', '" . $amount . "', '" . $remark . "','".$reverse_change."','".$reverse_change_gst."', '" . date('Y-m-d H:i:s') . "', '" . $user_id . "')";

  $addrun = mysqli_query($conn, $addqry);

  if ($addrun) {
    $_SESSION['msg']['success'] = 'Transaction Added Successfully.';
    header('location:accounting-cheque-list.php');exit;
  } else {
    $_SESSION['msg']['fail'] = 'Transaction Added Fail';
    header('location:accounting-cheque.php');exit;
  }
}
}
?>

<?php 
    $allBank = getBank();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks | <?php echo (isset($_GET['id']) && $_GET['id'] != '') ? 'Edit' : 'Add'; ?> Cheque</title>
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
        <!-- Left Navigation -->
        <?php include "include/sidebar-nav-left.php" ?>
        
        
      
      
      <div class="main-panel">
      
        <div class="content-wrapper">
          <span id="errormsg"></span>
            <form class="forms-sample" method="post" action="">
              <div class="row">
                <?php include "include/transaction_header.php"; ?>
                <!-- Form -->
                <div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                    
                      <!-- First Row  -->

                      <?php
                      if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
                        $id = $_REQUEST['id'];
                        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                        $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;
                        $accountdataqry = "select * from accounting_cheque where id = '".$id."' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."'";
                        $accountdatarun = mysqli_query($conn, $accountdataqry);
                        $accountdata = mysqli_fetch_assoc($accountdatarun);
                       
                        
                      }
                      ?>
                    
                      <div class="form-group row">
                        
                        <?php if(isset($_REQUEST['id'])){ ?>
                        <div class="col-12">
                          <label for="exampleInputName1" class="pull-right bg-success color-white p-2">Running Balance: <span id="bank_running">0</span></label>
                        </div>     
                      <?php } else {?>
                        <div class="col-12">
                          <label for="exampleInputName1" class="pull-right bg-success color-white p-2">Running Balance: <span id="bank_running">0</span></label>
                        </div>     
                        <?php } ?>
                      </div>
                      
                      <div class="form-group row">
                        
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Voucher No.<span class="text-danger">*</span></label>
                          <input type="text" class="form-control" id="voucherno" name="voucherno" placeholder="Voucher No." value="<?php if(isset($_REQUEST['id'])){echo $accountdata['voucher_no'];}else{echo getaccountingchequevoucher('payment'); } ?>" required="">
                        </div>
                        
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Date<span class="text-danger">*</span></label>
                          <div id="datepicker-popup1" class="input-group date datepicker">
                            <input type="text" class="form-control border" name="date" value="<?php if(isset($_REQUEST['id'])){echo date('d-m-Y', strtotime(str_replace('/', '-', $accountdata['voucher_date'])));}else{echo date("d/m/Y"); } ?>" required="" data-parsley-errors-container="#error-date">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          <span id="error-date"></span>
                        </div>
                        
                        
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Bank Name<span class="text-danger">*</span></label>
                          <select class="js-example-basic-single" id="bank" style="width:100%" name="bank" required="" data-parsley-errors-container="#error-bank">
                            <option value="">Select Bank</option>
                            <?php if(isset($allBank) && !empty($allBank)){ ?>
                                <?php foreach ($allBank as $key => $value) { ?>
                                  <option value="<?php echo $value['id']; ?>" <?php echo (isset($accountdata['bank_name']) && $accountdata['bank_name'] == $value['id']) ? 'selected' : ''; ?> > <?php echo $value['name']; ?> </option>
                                <?php } ?>
                            <?php } ?>
                          </select>
                          <span id="error-bank"></span>
                        </div>
                        
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Voucher Type<span class="text-danger">*</span></label>
                            <select class="js-example-basic-single voucher" style="width:100%" name="voucher_type"> 
                              <option value="payment" <?php if(isset($_REQUEST['id']) && $accountdata['voucher_type'] == "payment"){echo "selected";}?>>Payment</option>
                              <option value="receipt" <?php if(isset($_REQUEST['id']) && $accountdata['voucher_type'] == "receipt"){echo "selected";}?>>Receipt</option>
                            </select>
                        </div>
                      </div>
                      <div class="form-group row">
                          <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Reverse Change</label>
                          <div class="row no-gutters">
                                  
                            <div class="col-12 col-md-6">
                              <div class="form-radio">
                                <label class="form-check-label">
                                  <input type="radio" class="form-check-input reversechange" name="reversechange" value="no" checked 
                                  <?php if (isset($_REQUEST['id']) && $accountdata['reverse_change'] == "no"){echo "checked";} ?>>
                               		No
                                </label>
                              </div>
                            </div>
                            
                            <div class="col-12 col-md-6">
                              <div class="form-radio">
                                <label class="form-check-label">
                                  <input type="radio" class="form-check-input reversechange" name="reversechange" value="yes" <?php if (isset($_REQUEST['id']) && $accountdata['reverse_change'] == "yes") {echo "checked";} ?>>
                                  Yes
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-12 col-md-4" id="reversechangeper" <?php if (isset($_REQUEST['id']) && $accountdata['reverse_change'] == "yes") {} else { ?> style="display: none;"<?php } ?>>
                          <label for="exampleInputName1">GST%</label>
                            <div class="row no-gutters">
                              <div class="col-12 col-md-4">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="gst" id="" value="5" <?php if (isset($_REQUEST['id']) && $accountdata['reverse_change_gst'] == "5") {echo "checked";} ?>> 
                                    5%
                                  </label>
                                </div>
                              </div>

                              <div class="col-12 col-md-4">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="gst" id="" value="12" <?php if (isset($_REQUEST['id']) && $accountdata['reverse_change_gst'] == "12") {echo "checked";} ?>>
                                    12%
                                  </label>
                                </div>
                              </div>

                              <div class="col-12 col-md-4">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="gst" id="" value="18" <?php if (isset($_REQUEST['id']) && $accountdata['reverse_change_gst'] == "18") {echo "checked";} ?>>
                                    18%
                                  </label>
                                </div>
                              </div>
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
                                <th>Credit/Debit<span class="text-danger">*</span></th>
                                <th>Type<span class="text-danger">*</span></th>
                                <th>Perticulars<span class="text-danger">*</span></th>
                                <th>Cheque No.</th>
                                <th>Cheque Date<span class="text-danger">*</span></th>
                                <th>Amount<span class="text-danger">*</span></th>
                              </tr>
                            </thead>
                              <tbody>
                                <!-- Row Starts --> 	
                                <tr>
                                  <td><input type="text" class="form-control credit_debit" name="credit_debit" readonly required="" value="<?php if(isset($_REQUEST['id']) && $accountdata['credit_debit']) { echo ucfirst($accountdata['credit_debit']);} else { echo "Credit";}?>" >
                                  <!-- <select class="js-example-basic-single" name="credit_debit" style="width:100%" id="exampleInputName1" required="">
                                   <option value="credit">Credit </option>
                                            
                                   <option value="debit">Debit </option> 
                                  </select> -->
                                  </td>

                                  <td><select class="js-example-basic-single" name="group" style="width:100%" id="group" required="" data-parsley-errors-container="#error-type"> 
                                        <option value="">Please Select</option>
                                          <?php
                                          $dataqry = "SELECT * FROM `group`";
                                          $datarun = mysqli_query($conn, $dataqry);
                                          while ($data = mysqli_fetch_assoc($datarun)) { ?>
                                          <option value="<?php echo $data['id']; ?>" <?php echo (isset($accountdata['group_id']) && $accountdata['group_id'] == $data['id']) ? 'selected' : ''; ?>> <?php echo $data['name']; ?></option>
                                          <?php  } ?>     
                                      </select>
                                      <span id="error-type"></span>
                                  </td>

                                  <td>
                                    <select class="js-example-basic-single" name="perticular" style="width:100%" id="ledger" required="" data-parsley-errors-container="#error-perticular"> 
                                      <option value="">Please Select</option>
                                      <?php
                                      $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                                      $sqlqry = "SELECT * FROM `ledger_master` WHERE group_id = '".$accountdata['group_id']."' AND pharmacy_id = '".$p_id."'";
                                      $sqlqryrun = mysqli_query($conn, $sqlqry);
                                      while ($sqldata = mysqli_fetch_assoc($sqlqryrun)) { ?>
                                        <option value="<?php echo $sqldata['id']; ?>" <?php echo (isset($accountdata['perticular']) && $accountdata['perticular'] == $sqldata['id']) ? 'selected' : '';?>> <?php echo $sqldata['name']; ?> 
                                      </option>
                                      <?php } ?>
                                    </select>
                                    <div class="badge badge-primary pull-right display-none" id="ledger_running_balance">0</div>
                                    <span id="error-perticular"></span>
                                  </td>

                                  <td><input type="text" class="form-control" name="cheque" id="exampleInputName1" placeholder="Cheque No" autocomplete="off" value="<?php if(isset($_REQUEST['id'])){echo $accountdata['cheque_no']; }?>">
                                  </td>

                                  <td>
                                    <div id="datepicker-popup2" class="input-group date datepicker" >
                                      <input type="text" name="cheque_date" class="form-control border" value="<?php if(isset($_REQUEST['id'])){echo date('d/m/Y', strtotime(str_replace('/', '-', $accountdata['cheque_date'])));}else{echo date("d/m/Y"); }?>" required="" data-parsley-errors-container="#error-chequedate">
                                      <span class="input-group-addon input-group-append border-left">
                                        <span class="mdi mdi-calendar input-group-text"></span>
                                      </span>
                                    </div>
                                    <span id="error-chequedate"></span>
                                  </td>
                                      
                                  <td><input type="text" name="amount" class="form-control onlynumber" id="exampleInputName1" 
                                      placeholder="Enter Amount" data-parsley-type="number" required="" autocomplete="off" value="<?php if(isset($_REQUEST['id'])){echo $accountdata['amount']; }?>">
                                  </td>
                                      
                                </tr><!-- End Row --> 	
                                  
                                <tr>
                                    <td>
                                        <label for="exampleInputName1">Remarks</label>
                                        <textarea  class="form-control" name="remark" id="exampleInputName1" 
                                            placeholder="Remarks" rows="3"><?php if(isset($_REQUEST['id'])){echo $accountdata['remark']; }?>
                                        </textarea> 
                                    </td>
                                    <td></td>
                                    <td colspan="3">
                                        <div id="bank-detail" <?php if(isset($_REQUEST['id'])){ }else{?> class="display-none" <?php } ?>>
                                            <p>Bank Name : <span id="bank_name"></span></p>
                                            <p>Bank A/C No : <span id="bank_ac_no"></span></p>
                                            <p>Branch Name : <span id="branch_name"></span></p>
                                            <p>IFSC Code : <span id="ifsc_code"></span></p>
                                        </div>
                                    </td>
                                </tr>
                              </tbody>
                          </table>
                            
                            <div class="col-12">
                            	<a href="accounting-cheque-list.php" class="btn btn-light mt-30 pull-left">Back</a>
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
  <script src="js/custom/onlynumber.js"></script>
  
  <!-- Custom js for this page Modal Box-->
  <script src="js/modal-demo.js"></script>
  
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
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
