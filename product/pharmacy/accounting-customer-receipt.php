<?php include('include/usertypecheck.php');
if(isset($_POST['add_receipt'])){
  
  $user_id = $_SESSION['auth']['id'];
  $cash_receipt_no = $_POST['cash_receipt_no'];
  $customer = $_POST['customer'];
  $payment_date = date('Y-m-d',strtotime(str_replace('/','-',$_POST['payment_date'])));
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

 if(isset($_GET['id']) && $_GET['id'] != ''){
    $insert = "UPDATE cash_receipt SET ";
  }else{
    $insert = "INSERT INTO cash_receipt SET ";
  }

  $insert .= "cash_receipt_no ='".$cash_receipt_no."',
                     customer ='".$customer."',
                     payment_date ='".$payment_date."',
                     payment_mode ='".$payment_mode."',
                     cheque_no ='".$cheque_no."',
                     deposit_bank_cheque ='".$deposit_bank_cheque."',
                     dd_no ='".$dd_no."',
                     deposit_bank_dd ='".$deposit_bank_dd."',
                     utr_number ='".$utr_number."',
                     deposit_bank_net_banking ='".$deposit_bank_net_banking."',
                     card_number = '".$card_number."',
                     deposit_bank_credit_debit_card = '".$deposit_bank_credit_debit_card."',
                     name_on_card = '".$name_on_card."',
                     reference = '".$reference."',
                     amount = '".$amount."',
                     remarks = '".$remarks."'";
                    if(isset($cheque_date) && $cheque_date != ''){
                      $insert .= ",cheque_date ='".$cheque_date."'";
                    }
                    if(isset($dd_date) && $dd_date != ''){
                      $insert .= ",dd_date ='".$dd_date."'";
                    }

                     
                    if(isset($_GET['id']) && $_GET['id'] != ''){
                      $insert .= ",updated_at ='".date('Y-m-d H:i:s')."',updated_by = '".$user_id."' WHERE id='".$_GET['id']."'";
                    }else{
                      $insert .= ",creted_at ='".date('Y-m-d H:i:s')."',created_by = '".$user_id."'";
                    }
        
    $result = mysqli_query($conn,$insert);
    if($result){
      if(isset($_GET['id']) && $_GET['id'] != ''){
        $_SESSION['msg']['success'] = 'Customer Receipt Updated Successfully.';
      }else{
        $_SESSION['msg']['success'] = 'Customer Receipt Added Successfully.';
      }
      header('location:accounting-customer-receipt.php');exit; 
    }else{
      if(isset($_GET['id']) && $_GET['id'] != ''){
        $_SESSION['msg']['fail'] = 'Customer Receipt Updated Failed.';
      }else{
        $_SESSION['msg']['fail'] = 'Customer Receipt Added Failed.';
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
                      function getcashrecipt(){
                        global $conn;
                        $voucher_no = '';

                        $cashQuery = "SELECT * FROM `cash_receipt` ORDER BY id DESC LIMIT 1";
                        $cashRes = mysqli_query($conn, $cashQuery);
                        if($cashRes){
                          $count = mysqli_num_rows($cashRes);
                          if($count !== '' && $count !== 0){
                            $row = mysqli_fetch_array($cashRes);
                            $voucherno = (isset($row['cash_receipt_no'])) ? $row['cash_receipt_no'] : '';

                            if($voucherno != ''){
                              $vouchernoarr = explode('-',$voucherno);
                              $voucherno = $vouchernoarr[1];
                              $voucherno = $voucherno + 1;
                              $voucherno = sprintf("%05d", $voucherno);
                              $voucher_no = 'CR-'.$voucherno;
                            }

                          }else{
                            $voucherno = sprintf("%05d", 1);
                            $voucher_no = 'CR-'.$voucherno;
                          }
                        }
                        return $voucher_no;
                      }
                    ?>
                    
                    <!-- First Row  -->
                    <form class="forms-sample" method="post">
                        <div class="form-group row">
                        	<div class="col-12 col-md-3">
                            <?php 
                              $getcashrecipt = getcashrecipt();
                            ?>
			                        <label for="exampleInputName1">Cash Receipt No.</label>
			                        <input type="text" name="cash_receipt_no" value="<?php echo $getcashrecipt; ?>" class="form-control" id="exampleInputName1" placeholder="Cash Receipt No.">
                        	</div>
                        
                  
                         <div class="col-12 col-md-9">
                              <label for="exampleInputName1" class="pull-right bg-success color-white p-2">Running Balance: 123456</label>
                         </div>     
                        
                        </div>
                      
                        <div class="form-group row">
                        
                        
                         <!-- CHEQUE FIELDS -->
                        <div class="col-12 col-md-2">
                        <label for="exampleInputName1">Customer</label>
                        <select class="js-example-basic-single" name="customer" style="width:100%"> 
                            <option value="">Select</option>
                            <?php 
                              $customerQuery = "SELECT * FROM `ledger_master` WHERE group_id = '10' ORDER BY name"; 
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
                            <input type="text" class="form-control border" name="payment_date" >
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                        </div>
                        
                        <div class="col-12 col-md-2">
	                        <label for="exampleInputName1">Payment Mode</label>
	                        <select id="payment_mode" class="js-example-basic-single payment_mode" name="payment_mode" style="width:100%"> 
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
  		                        <input type="text" class="form-control" id="cheque_no" name="cheque_no" placeholder="Cheque No">
  	                        </div>
  	                        <div class="col-12 col-md-2">
  		                        <label for="deposit_bank">Deposit Bank</label>
  		                        <select id="deposit_bank" name="deposit_bank_cheque" class="js-example-basic-single deposit_bank" style="width:100%"> 
  			                        <option value="">Select Any One </option>
                                <?php
                                $bankQuery = "SELECT * FROM `ledger_master` WHERE group_id IN (5,22) ORDER BY name"; 
                                $bankRes = mysqli_query($conn, $bankQuery);
                                while ($bankRow = mysqli_fetch_array($bankRes)) {
                                ?>
  			                        <option value="<?php echo $bankRow['id']; ?>"><?php echo $bankRow['name']; ?></option>
  			                        <?php } ?>
  			                      </select>
  	                        </div>
  	                        <div class="col-12 col-md-2">
  		                        <label for="cheque_date">Cheque Date</label>
  		                        <div id="" class="input-group date datepicker">
  		                            <input type="text" class="form-control border" name="cheque_date">
  		                            <span class="input-group-addon input-group-append border-left">
  		                              <span class="mdi mdi-calendar input-group-text"></span>
  		                            </span>
  		                          </div>
  		                    </div>
                        	
                        </div>

                        <div class="form-group row div_dd" style="display: none;">
                        	
                      		<div class="col-12 col-md-2">
  		                        <label for="dd_no">DD No</label>
  		                        <input type="text" class="form-control" id="dd_no" name="dd_no" placeholder="DD No">
  	                        </div>
  	                        <div class="col-12 col-md-2">
  		                        <label for="deposit_bank">Deposit Bank</label>
  		                        <select id="deposit_bank" name="deposit_bank_dd" class="js-example-basic-single deposit_bank" style="width:100%"> 
  			                       <option value="">Select Any One </option>
                                <?php
                                $bankQuery = "SELECT * FROM `ledger_master` WHERE group_id IN (5,22) ORDER BY name"; 
                                $bankRes = mysqli_query($conn, $bankQuery);
                                while ($bankRow = mysqli_fetch_array($bankRes)) {
                                ?>
                                <option value="<?php echo $bankRow['id']; ?>"><?php echo $bankRow['name']; ?></option>
                                <?php } ?>
  			                    </select>
  	                        </div>
  	                        <div class="col-12 col-md-2">
  		                        <label for="dd_date">DD Date</label>
  		                        <div id="" class="input-group date datepicker">
  		                            <input type="text" class="form-control border" name="dd_date">
  		                            <span class="input-group-addon input-group-append border-left">
  		                              <span class="mdi mdi-calendar input-group-text"></span>
  		                            </span>
  		                          </div>
  		                    </div>
                        	
                        </div>

                        <div class="form-group row div_net_banking" style="display: none;">
                          <div class="col-12 col-md-2">
                              <label for="utr_number">UTR Number</label>
                              <input type="text" class="form-control" id="utr_number" name="utr_number" placeholder="UTR Number">
                          </div>
                          
                          <div class="col-12 col-md-2">
                              <label for="deposit_bank">Deposit Bank</label>
                              <select id="deposit_bank" name="deposit_bank_net_banking" class="js-example-basic-single deposit_bank" style="width:100%"> 
                                <option value="">Select Any One </option>
                                <?php
                                $bankQuery = "SELECT * FROM `ledger_master` WHERE group_id IN (5,22) ORDER BY name"; 
                                $bankRes = mysqli_query($conn, $bankQuery);
                                while ($bankRow = mysqli_fetch_array($bankRes)) {
                                ?>
                                <option value="<?php echo $bankRow['id']; ?>"><?php echo $bankRow['name']; ?></option>
                                <?php } ?>
                            </select>
                          </div>
                        </div>

                        <div class="form-group row div_credit_debit_card" style="display: none;">
                          <div class="col-12 col-md-2">
                              <label for="card_number">Card Number</label>
                              <input type="text" class="form-control" id="card_number" name="card_number" placeholder="Card Number">
                          </div>
                          <div class="col-12 col-md-2">
                              <label for="deposit_bank">Deposit Bank</label>
                              <select id="deposit_bank" name="deposit_bank_credit_debit_card" class="js-example-basic-single deposit_bank" style="width:100%"> 
                               <option value="">Select Any One </option>
                                <?php
                                $bankQuery = "SELECT * FROM `ledger_master` WHERE group_id IN (5,22) ORDER BY name"; 
                                $bankRes = mysqli_query($conn, $bankQuery);
                                while ($bankRow = mysqli_fetch_array($bankRes)) {
                                ?>
                                <option value="<?php echo $bankRow['id']; ?>"><?php echo $bankRow['name']; ?></option>
                                <?php } ?>
                              </select>
                          </div>
                           <div class="col-12 col-md-2">
                              <label for="name_on_card">Name On Card</label>
                              <input type="text" class="form-control" id="name_on_card" name="name_on_card" placeholder="Name On Card">
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
                        <input type="text" class="form-control" id="amount" name="amount" placeholder="Amount">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="remarks">Remarks</label>
                        <textarea  class="form-control" id="remarks" name="remarks" placeholder="Remarks" rows="3"></textarea>
                        </div>
                        
                        
                        
                        <!--- CASH FIELD --->
                        
                        
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
                                      <th>Bill of Supply Ammount</th>
                                      <th>Total Ammount</th>
                                      <th>Ammount Received</th>
                                      <th>Pending Ammount</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts --> 	
                                  <tr>
                                  <td>1</td>
                                      <td>Johnn Doe</td>
                                      <td>13643.00</td>
                                      <td>35009.00</td>
                                      <td>0.00</td>
                                      <td>35009.00</td>
                                      <td>45009.00</td>
                                      <td>0.00</td>
                                      <td><a href="'#" class="btn btn-dark pt-2 pb-2"><i class="icon-pencil  mr-0"></i></a></td>
                                      
                                  </tr><!-- End Row --> 
                                  
                                  <!-- Row Starts --> 	
                                  <tr>
                                  <td>1</td>
                                      <td>Johnn Doe</td>
                                      <td>13643.00</td>
                                      <td>35009.00</td>
                                      <td>0.00</td>
                                      <td>35009.00</td>
                                      <td>45009.00</td>
                                      <td>0.00</td>
                                      <td><a href="'#" class="btn btn-dark pt-2 pb-2"><i class="icon-pencil  mr-0"></i></a></td>
                                      
                                  </tr><!-- End Row --> 	
                                  
                                  
                                 
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
        
        
        <!-- Add Customer Model -->
        <div class="modal fade" id="exampleModal-4" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
              
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel">Add Customer</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <div class="modal-body">
                  <form>
                  
                    <div class="form-group row">
                  
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Vendor Name</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Vendor Name">   
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">DC Date</label>
                            <div id="datepicker-popup3" class="input-group date datepicker">
                            <input type="text" class="form-control border" >
                            <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                            </div>
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">DC No.</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="DC No.">
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Product</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Product">
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Batch</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Batch">
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Expiry Date</label>
	                        <div id="datepicker-popup4" class="input-group date datepicker">
                            <input type="text" class="form-control border" >
                            <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                            </div>

                          </div>
                          
                           <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Units/Strip</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Units/Strip">
                          </div>
                          
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">No. of Strips</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="No. of Strips">
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Free Strips</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Free Strips">
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">GST Total%</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">H</option>
                                    <option value="Unregistered">H1</option>
                                </select>
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Price/Strip</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Price/Strip">
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Selling/Strip</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Selling/Strip">
                          </div>
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">MRP/Strip</label>
                           <input type="text" class="form-control" id="exampleInputName1" placeholder="MRP/Strip">
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Discount%</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Discount%">
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">HSN Code</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="HSN Code">
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Rack No.</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Rack No.">
                          </div>
                          
                          <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Box No.</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Box No.">
                          </div>
                          
                          
                          
                          
                          
                        
                          
                         
                        
                      </div>
                    
                    
                  </form>
                </div>
                
                <div class="modal-footer">
                  <button type="button" class="btn btn-success">Send message</button>
                  <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
        </div>
        
        <!-- Alternates Model -->
        <div class="modal fade" id="exampleModal-5" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
              
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel">Product Alternate Selection</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <div class="modal-body">
                  <form>
                  
                    <div class="form-group row">
                  
                          <div class="col-12 col-md-6">
                            <label for="exampleInputName1">Product</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">Search</option>
                                    <option value="Unregistered">abc</option>
                                </select>
                          </div>
                          
                          <div class="col-12 col-md-6">
                            <label for="exampleInputName1">Generic Name</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">Search</option>
                                    <option value="Unregistered">H1</option>
                                </select>
                          </div>
                          
                          <div class="col-12 col-md-6">
                            <label for="exampleInputName1">Type</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Type">
                          </div>
                          
                          <div class="col-12 col-md-6">
                            <label for="exampleInputName1">Schedule</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Schedule">
                          </div>
                          
                          <div class="col-12 col-md-6">
                            <label for="exampleInputName1">Manufacturer</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Manufacturer">
                          </div>
         
                        
                      </div>
                    
                    
                  </form>
                </div>
                
                <div class="modal-footer">
                  <button type="button" class="btn btn-success">Add</button>
                  <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
        </div>
        
        <!-- Missed Order Model -->
        <div class="modal fade" id="exampleModal-6" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
              
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel">Missed Orders</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <div class="modal-body">
                  <form>
                  
                    <div class="form-group row">
                  
                          <div class="col-12 col-md-6">
                            <label for="exampleInputName1">Product</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">Search</option>
                                    <option value="Unregistered">abc</option>
                                </select>
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Qty.</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="00">
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Unit/MRP</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="00">
                          </div>
                          
                          <div class="col-12 col-md-3">
                           <button type="button" class="btn btn-success btn-xs mt-30">Add</button>
                          </div>
                          
                           <div class="col-12 col-md-3 mt-30">
                           <a href="#" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#exampleModal-7" data-whatever="">Add New Product</a>
                          </div>
                          
                         
                        
                      </div>
                    
                    
                  </form>
                  
                  <div class="row">
                  <div class="col-12">
                  	<table id="order-listing1" class="table">
                                <thead>
                                  <tr>
                                      <th>Product</th>
                                      <th>Qty.</th>
                                      <th>Unit/MRP</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts --> 	
                                  <tr>
                                      <td>O133</td>
                                      <td> 14/07/2018</td>
                                      <td>567 / O133</td>
                                      <td>
                                      	
                                        <a href="#" class="btn  btn-success p-2"><i class="icon-pencil mr-0"></i></a>
                                        <a href="#" class="btn  btn-danger p-2"><i class="icon-close mr-0"></i></a>
                                      </td>
                                  </tr><!-- End Row --> 	
                                  
                                
                                  
                                 
                                </tbody>
                              </table>            
                  </div>
                  </div>
                  
                  
                  
                  
                  
                  
                  
                </div>
                
                <div class="modal-footer">
                  <button type="button" class="btn btn-success">Add Order</button>
                  <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
        </div>
        
        
    	<!-- Missed order sub - Add new product Model -->
        <div class="modal fade" id="exampleModal-7" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
              
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel">New Product</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <div class="modal-body">
                        <form>
                           <div class="form-group">
                           
                            <div class="row">
                  
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Name of Product</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Name of Product">   
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Generic Name</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Generic Name">
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Manufacturer</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Manufacturer">
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Scheduled Category</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">select</option>
                                    <option value="Unregistered">H1</option>
                                </select>
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Type</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Type">
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Catagory</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Catagory">
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Sub Catagory</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">select</option>
                                    <option value="Unregistered">H1</option>
                                </select>
                          </div>
                          
                          </div>
                          
                          
                          <div class="row">
                          
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">HSN</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="HSN">
                          </div>
                          
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">GST Total%</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">5%</option>
                                    <option value="Unregistered">10%</option>
                                </select>
                          </div>
                          
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">CGST %</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="0.00">
                          </div>
                          
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">SGST %</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="0.00">
                          </div>
                          
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">IGST %</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="0.00">
                          </div>
                          
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">Units/Strip</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Units/Strip">
                          </div>
                          
                          </div>
                        
                      </div>
                        </form>
                    </div>
                
                <div class="modal-footer">
                  <button type="button" class="btn btn-success">Add</button>
                  <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
        </div>
        
        
        <!-- Save & Return Model -->
        <div class="modal fade" id="exampleModal-8" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
              
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel">Product Return</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <div class="modal-body">
                  <form>
                    <div class="form-group row">
                  
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">Product</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">Search</option>
                                    <option value="Unregistered">abc</option>
                                </select>
                          </div>
                          
                          
                          <div class="col-12 col-md-1">
                            <label for="exampleInputName1">Quantity</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="00">
                          </div>
                          
                          <div class="col-12 col-md-1">
                            <label for="exampleInputName1">Batch</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">19</option>
                                </select>
                          </div>
                          
                          <div class="col-12 col-md-1">
                            <label for="exampleInputName1">Disc%</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="00">
                          </div>
                          
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">Expiry</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="12/19">
                          </div>
                          
                          <div class="col-12 col-md-1">
                            <label for="exampleInputName1">GST%</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="00">
                          </div>
                          
                          <div class="col-12 col-md-1">
                            <label for="exampleInputName1">MRP</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="5">
                          </div>
                          
                          <div class="col-12 col-md-3">
                           <a href="#" class="btn btn-primary mt-30">Return</a>
                           <a href="#" class="btn btn-grey-1 mt-30">Clear</a>
                          </div>
                          
                          <div class="col-12 col-md-9 mt-1">
                          	<label for="exampleInputName1" class="color-green"><strong>Total Qty.:</strong> 15</label>&nbsp;
                            <label for="exampleInputName1" class="pull-right"><strong>Purchase Price:</strong> 1200</label>
                          </div>
                         
                        
                      </div>
                  </form>
                  
                  <div class="row">
                  <div class="col-12">
                  	<table id="order-listing1" class="table">
                                <thead>
                                  <tr align="left">
                                      <th>Sr No.</th>
                                      <th>Product</th>
                                      <th>Qty.</th>
                                      <th>Disc.%</th>
                                      <th>Ammount</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts --> 	
                                  <tr >
                                      <td>O133</td>
                                      <td>A O FORTE</td>
                                      <td>25</td>
                                      <td>5</td>
                                      <td>125</td>
                                  </tr><!-- End Row --> 	
                                  
                                
                                  
                                 
                                </tbody>
                              </table>            
                  </div>
                  </div>
                  
                  
                  
                  
                  
                  
                  
                </div>
                
                <div class="modal-footer">
                  <button type="button" class="btn btn-success">Return Items</button>
                  <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
        </div>
    
        
     
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
    $('form').parsley();
  </script>
  <script src="js/custom/accounting-customer-receipt.js"></script>
  
  
  <!-- End custom js for this page-->
</body>


</html>
