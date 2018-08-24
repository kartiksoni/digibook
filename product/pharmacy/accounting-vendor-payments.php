<?php include('include/usertypecheck.php');
      //include('include/permission.php'); 
if(isset($_POST['add_receipt'])){
  
  $user_id = $_SESSION['auth']['id'];
  $vendor_receipt_no = $_POST['vendor_receipt_no'];
  $customer = $_POST['customer'];
  $payment_date = $_POST['payment_date'];
  $payment_mode = $_POST['payment_mode'];
  $cheque_no = $_POST['cheque_no'];
  $deposit_bank_cheque = $_POST['deposit_bank_cheque'];
  $cheque_date = (isset($_POST['cheque_date']) && $_POST['cheque_date'] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['cheque_date']))) : NULL ;
  $dd_no = $_POST['dd_no'];
  $deposit_bank_dd = $_POST['deposit_bank_dd'];
  $dd_date = (isset($_POST['dd_date']) && $_POST['dd_date'] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['dd_date']))) : NULL ;
  $utr_number = $_POST['utr_number'];
  $deposit_bank_net_banking = $_POST['deposit_bank_net_banking'];
  $card_number = $_POST['card_number'];
  $deposit_bank_credit_debit_card = $_POST['deposit_bank_credit_debit_card'];
  $name_on_card = $_POST['name_on_card'];
  $reference = $_POST['reference'];
  $amount = $_POST['amount'];
  $remarks = $_POST['remarks'];

  $vendoraddqry = "INSERT INTO `accounting_vendor_payment`(`vendor_receipt_no`, `customer`, `payment_date`, `payment_mode`, `deposit_bank_cheque`, `cheque_no`, `cheque_date`, `dd_no`, `dd_date`, `deposit_bank_dd`, `utr_number`, `deposit_bank_net_banking`, `card_number`, `deposit_bank_credit_debit_card`, `name_on_card`, `reference`, `amount`, `remarks`, `creted_at`, `created_by`) VALUES ('".$vendor_receipt_no."', '".$customer."', '".$payment_date."', '".$payment_mode."', '".$deposit_bank_cheque."', '".$cheque_no."', '".$cheque_date."', '".$dd_no."', '".$dd_date."', '".$deposit_bank_dd."', '".$utr_number."', '".$deposit_bank_net_banking."', '".$card_number."', '".$deposit_bank_credit_debit_card."', '".$name_on_card."','".$reference."', '".$amount."', '".$remarks."', '".date('Y-m-d H:i:s')."', '".$user_id."')";  

  $vendoraddrun = mysqli_query($conn, $vendoraddqry);

  if($vendoraddrun){
    $_SESSION['msg']['success'] = 'Vendor Receipt Added Successfully.';
  }
  else{
    $_SESSION['msg']['fail'] = 'Vendor Receipt Added Fail';
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
        <!-- Right Sidebar -->
        <?php include "include/sidebar-right.php" ?>
        
       
       <!-- Left Navigation -->
        <?php include "include/sidebar-nav-left.php" ?>
        
        
      
      
      <div class="main-panel">
      <?php include('include/flash.php'); ?>
        <div class="content-wrapper">
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
                            <a href="accounting-customer-receipt.php" class="btn btn-dark btn-fw active">Customer Receipt</a>
                            <a href="accounting-cheque.php" class="btn btn-dark  btn-fw ">Cheque</a>
                            <a href="accounting-vendor-payments.php" class="btn btn-dark  btn-fw">Vendor Payment</a>
                            <a href="financial-year.php" class="btn btn-dark  btn-fw">Financial Year Settings</a>
                            <a href="purchase-return.php" class="btn btn-dark  btn-fw">Credit Note / Purchase Note</a>
                            <a href="quotation-estimate-proformo-invoice.php" class="btn btn-dark  btn-fw">Quotation / Estimate / Proformo Invoice</a>
                            
                        </div>   
                    </div> 
                    </div>
                    <hr>
                    <?php
                      function getvendorrecipt(){
                        global $conn;
                        $voucher_no = '';

                        $cashQuery = "SELECT * FROM `accounting_vendor_payment` ORDER BY id DESC LIMIT 1";
                        $cashRes = mysqli_query($conn, $cashQuery);
                        if($cashRes){
                          $count = mysqli_num_rows($cashRes);
                          if($count !== '' && $count !== 0){
                            $row = mysqli_fetch_array($cashRes);
                            $voucherno = (isset($row['vendor_receipt_no'])) ? $row['vendor_receipt_no'] : '';

                            if($voucherno != ''){
                              $vouchernoarr = explode('-',$voucherno);
                              $voucherno = $vouchernoarr[1];
                              $voucherno = $voucherno + 1;
                              $voucherno = sprintf("%05d", $voucherno);
                              $voucher_no = 'VR-'.$voucherno;
                            }

                          }else{
                            $voucherno = sprintf("%05d", 1);
                            $voucher_no = 'VR-'.$voucherno;
                          }
                        }
                        return $voucher_no;
                      }
                    ?>
                    
                    <!-- First Row  -->
                    <form class="forms-sample form" method="post">
                        <div class="form-group row">
                        	<div class="col-12 col-md-3">
                            <?php 
                              $getcashrecipt = getvendorrecipt();
                            ?>
			                        <label for="exampleInputName1">Vendor Receipt No.</label>
			                        <input type="text" name="vendor_receipt_no" value="<?php echo $getcashrecipt; ?>" class="form-control" id="exampleInputName1" placeholder="Vendor Receipt No." required="">
                        	</div>
                        
                  
                         <div class="col-12 col-md-9">
                              <label for="exampleInputName1" class="pull-right bg-success color-white p-2">Running Balance: 123456</label>
                         </div>     
                        
                        </div>
                      
                        <div class="form-group row">
                        
                        
                         <!-- CHEQUE FIELDS -->
                        <div class="col-12 col-md-2">
                        <label for="exampleInputName1">Vendor</label>
                        <select class="js-example-basic-single" name="customer" style="width:100%" required=""> 
                            <option value="">Select</option>
                            <?php 
                              $customerQuery = "SELECT id, name FROM `ledger_master` ORDER BY name"; 
                              $customerRes = mysqli_query($conn, $customerQuery);
                              while ($customerRow = mysqli_fetch_array($customerRes)) {
                            ?>
                            <option value="<?php echo $customerRow['id']; ?>"><?php echo $customerRow['name']; ?></option>
                            <?php } ?>
                        </select>
                        </div>
                        
                        <div class="col-12 col-md-2">
                        <label for="exampleInputName1">Payment Date</label>
                        <div id="" class="input-group date datepicker">
                            <input type="text" class="form-control border" name="payment_date" value="<?php echo date("d/m/Y");?>" required="">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                        </div>
                        
                        <div class="col-12 col-md-2">
	                        <label for="exampleInputName1">Payment Mode</label>
	                        <select id="payment_mode" class="js-example-basic-single payment_mode" name="payment_mode" style="width:100%" required=""> 
		                        <option value="">Select Any One </option>
		                        <option value="cash">Cash</option>
		                        <option value="cheque">Cheque</option>
		                        <option value="dd">DD</option>
		                        <option value="net_banking">Net Banking</option>
		                        <option value="credit_debit_card">Credit/Debit Card</option>
		                        <!-- <option value="on_account">On Account</option> -->
		                        <option value="other">Other</option>
		                    </select>
                        </div>
                        
                        </div>
                        
                        <div class="form-group row div_cheque" style="display: none;">
                        	
                      		  <div class="col-12 col-md-2">
  		                        <label for="cheque_no">Cheque No</label>
  		                        <input type="text" class="form-control" id="cheque_no" name="cheque_no" placeholder="Cheque No" 
                              required="">
  	                        </div>
  	                        <div class="col-12 col-md-2">
  		                        <label for="deposit_bank">Deposit Bank</label>
  		                        <select id="deposit_bank" name="deposit_bank_cheque" class="js-example-basic-single deposit_bank" style="width:100%" required=""> 
  			                        <option value="">Select Any One </option>
                                <?php
                                $bankQuery = "SELECT id, bank_name FROM `ledger_master` WHERE group_id IN (5,22) ORDER BY name"; 
                                $bankRes = mysqli_query($conn, $bankQuery);
                                while ($bankRow = mysqli_fetch_array($bankRes)) {
                                ?>
  			                        <option value="<?php echo $bankRow['id']; ?>"><?php echo $bankRow['bank_name']; ?></option>
  			                        <?php } ?>
  			                      </select>
  	                        </div>
  	                        <div class="col-12 col-md-2">
  		                        <label for="cheque_date">Cheque Date</label>
  		                        <div id="" class="input-group date datepicker">
  		                            <input type="text" class="form-control border" name="cheque_date" value="<?php echo date("d/m/Y");?>" required="">
  		                            <span class="input-group-addon input-group-append border-left">
  		                              <span class="mdi mdi-calendar input-group-text"></span>
  		                            </span>
  		                          </div>
  		                    </div>
                        	
                        </div>

                        <div class="form-group row div_dd" style="display: none;">
                        	
                      		<div class="col-12 col-md-2">
  		                        <label for="dd_no">DD No</label>
  		                        <input type="text" class="form-control" id="dd_no" name="dd_no" placeholder="DD No" required="">
  	                        </div>
  	                        <div class="col-12 col-md-2">
  		                        <label for="deposit_bank">Deposit Bank</label>
  		                        <select id="deposit_bank" name="deposit_bank_dd" class="js-example-basic-single deposit_bank" style="width:100%" required=""> 
  			                       <option value="">Select Any One </option>
                                <?php
                                $bankQuery = "SELECT id, bank_name FROM `ledger_master` WHERE group_id IN (5,22) ORDER BY name"; 
                                $bankRes = mysqli_query($conn, $bankQuery);
                                while ($bankRow = mysqli_fetch_array($bankRes)) {
                                ?>
                                <option value="<?php echo $bankRow['id']; ?>"><?php echo $bankRow['bank_name']; ?></option>
                                <?php } ?>
  			                    </select>
  	                        </div>
  	                        <div class="col-12 col-md-2">
  		                        <label for="dd_date">DD Date</label>
  		                        <div id="" class="input-group date datepicker">
  		                            <input type="text" class="form-control border" name="dd_date" value="<?php echo date("d/m/Y");?>" required="">
  		                            <span class="input-group-addon input-group-append border-left">
  		                              <span class="mdi mdi-calendar input-group-text"></span>
  		                            </span>
  		                          </div>
  		                    </div>
                        	
                        </div>

                        <div class="form-group row div_net_banking" style="display: none;">
                          <div class="col-12 col-md-2">
                              <label for="utr_number">UTR Number</label>
                              <input type="text" class="form-control" id="utr_number" name="utr_number" placeholder="UTR Number" required="">
                          </div>
                          
                          <div class="col-12 col-md-2">
                              <label for="deposit_bank">Deposit Bank</label>
                              <select id="deposit_bank" name="deposit_bank_net_banking" class="js-example-basic-single deposit_bank" style="width:100%" required=""> 
                                <option value="">Select Any One </option>
                                <?php
                                $bankQuery = "SELECT id, bank_name FROM `ledger_master` WHERE group_id IN (5,22) ORDER BY name"; 
                                $bankRes = mysqli_query($conn, $bankQuery);
                                while ($bankRow = mysqli_fetch_array($bankRes)) {
                                ?>
                                <option value="<?php echo $bankRow['id']; ?>"><?php echo $bankRow['bank_name']; ?></option>
                                <?php } ?>
                            </select>
                          </div>
                        </div>

                        <div class="form-group row div_credit_debit_card" style="display: none;">
                          <div class="col-12 col-md-2">
                              <label for="card_number">Card Number</label>
                              <input type="text" class="form-control" id="card_number" name="card_number" placeholder="Card Number" required="">
                          </div>
                          <div class="col-12 col-md-2">
                              <label for="deposit_bank">Deposit Bank</label>
                              <select id="deposit_bank" name="deposit_bank_credit_debit_card" class="js-example-basic-single deposit_bank" style="width:100%" required=""> 
                               <option value="">Select Any One </option>
                                <?php
                                $bankQuery = "SELECT id, bank_name FROM `ledger_master` WHERE group_id IN (5,22) ORDER BY name"; 
                                $bankRes = mysqli_query($conn, $bankQuery);
                                while ($bankRow = mysqli_fetch_array($bankRes)) {
                                ?>
                                <option value="<?php echo $bankRow['id']; ?>"><?php echo $bankRow['bank_name']; ?></option>
                                <?php } ?>
                              </select>
                          </div>
                           <div class="col-12 col-md-2">
                              <label for="name_on_card">Name On Card</label>
                              <input type="text" class="form-control" id="name_on_card" name="name_on_card" placeholder="Name On Card" required="">
                          </div>
                        </div>

                        <div class="form-group row div_other" style="display: none;">
                          <div class="col-12 col-md-2">
                              <label for="reference">Reference</label>
                              <input type="text" class="form-control" id="reference" name="reference" placeholder="Reference">
                          </div>
                        </div>
                        
                       
                       
                       <div class="form-group row">
                       
                       <div class="col-12 col-md-2">
                        <label for="amount">Amount</label>
                        <input type="text" class="form-control" id="amount" name="amount" placeholder="Amount" required="" data-parsley-type="number">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="remarks">Remarks</label>
                        <textarea  class="form-control" id="remarks" name="remarks" placeholder="Remarks" rows="3"></textarea>
                        </div>
                        
                        
                        
                        
                        <div class="col-12">	
                        <button type="submit" name="add_receipt" class="btn btn-success mt-30">Add Receipt</button>                        
                       	<!--<a href="'#" class="btn btn-dark mt-30">Back</a>-->
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

                    <?php 

                        $dataqry = "SELECT ledger_master.name AS name, ledger_master.opening_balance, accounting_vendor_payment.amount             FROM ledger_master INNER JOIN accounting_vendor_payment ON ledger_master.id =                                  accounting_vendor_payment.customer";
                        $datarun = mysqli_query($conn, $dataqry);
                    ?>                
                    <!-- TABLE STARTS -->
                    <div class="col mt-3">
                    	 <div class="row">
                            <div class="col-12">
                              <table id="order-listing1" class="table">
                                <thead>
                                  <tr>
                                      <th>Sr No.</th>
                                      <th>Customer</th>
                                      <th>Opening Balance</th>
                                      <th>Bill Ammount</th>
                                      <th>Total Ammount</th>
                                      <th>Ammount Received</th>
                                      <th>Pending Ammount</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts --> 
                                  <?php
                                    if($datarun){
                                        $count = 0;
                                        while($data = mysqli_fetch_assoc($datarun)){
                                        $count++;    
                                  ?>	
                                  <tr>
                                      <td><?php echo $count;?></td>
                                      <td><?php echo $data['name']; ?></td>
                                      <td><?php echo $data['opening_balance']; ?></td>
                                      <td><?php ?></td>
                                      <td><?php ?></td>
                                      <td><?php echo $data['amount']; ?></td>
                                      <td>0.00</td>
                                      <td><a href="'#" class="btn btn-dark pt-2 pb-2"><i class="icon-pencil  mr-0"></i></a></td>
                                      
                                  </tr><!-- End Row --> 
                                  
                                    <?php } }?>
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
  	 $('#order-listing2').DataTable();
  </script>
  
  <script>
  	 $('#order-listing1').DataTable();
  </script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/parsley.min.js"></script>
  <script type="text/javascript">
     $('form').parsley({
    excluded:':hidden'
    });

  </script>
  <script src="js/custom/accounting-customer-receipt.js"></script>
  
  
  <!-- End custom js for this page-->
</body>


</html>
