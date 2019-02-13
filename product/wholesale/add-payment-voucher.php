<?php $title = "Add Payment Voucher"; ?>
<?php include('include/usertypecheck.php');
include('include/permission.php');

  if(isset($_REQUEST['id'])){
    $id = $_REQUEST['id'];
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
    $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;
    $query = "SELECT * FROM add_payment_voucher WHERE id='".$id."' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    $sub_data = array();
    $query1 = "SELECT * FROM add_payment_voucher_details WHERE pv_id = '".$id."'";
    $result1 = mysqli_query($conn, $query1);
    while($voucherdata = mysqli_fetch_assoc($result1)){
      $sub_data[] = $voucherdata;
    }

  }

  if(isset($_POST['submit'])){
    $user_id = $_SESSION['auth']['id'];
    
    if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
      $voucher = $_POST['voucherno'];
    }else{
      $voucher = getpaymentvoucher($_POST['voucher_type']);
    }
    
    $voucher_date = date("Y-m-d", strtotime(str_replace("/","-",$_POST['voucher_date'])));
    $voucher_type = $_POST['voucher_type'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $transporter_name = $_POST['party'];
    $ledger_id = $_POST['party1'];
    $transporter_invoice = $_POST['invoice'];
    $total_amount = $_POST['total_amount'];
    $igst = $_POST['igst'];
    $igst_amount = $_POST['total_igst'];
    $cgst = $_POST['cgst'];
    $cgst_amount = $_POST['total_cgst'];
    $sgst = $_POST['sgst'];
    $sgst_amount = $_POST['total_sgst'];
    $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : NULL;
    $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : NULL;
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
    $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;

    $addvoucherqry = "INSERT INTO `add_payment_voucher`(`owner_id`, `admin_id`, `pharmacy_id`, `financial_id`, `voucher_no`, `voucher_date`, `voucher_type`, `state_id`, `district`, `transporter_id`, `ledger_id`, `transporter_invoice_number`, `total`, `igst`, `igst_amount`, `cgst`, `cgst_amount`, `sgst`, `sgst_amount`, `created`, `createdby`) VALUES ('".$owner_id."', '".$admin_id."', '".$pharmacy_id."', '".$financial_id."', '".$voucher."', '".$voucher_date."', '".$voucher_type."', '".$state."', '".$district."', '".$transporter_name."', '".$ledger_id."', '".$transporter_invoice."', '".$total_amount."', '".$igst."', '".$igst_amount."', '".$cgst."', '".$cgst_amount."', '".$sgst."', '".$sgst_amount."', '".date('Y-m-d H:i:s')."', '".$user_id."')";

    $addvoucherrun = mysqli_query($conn, $addvoucherqry);
    $last_id = mysqli_insert_id($conn);

    if($addvoucherrun){

      $count = count($_POST['product_description']);

      if(!empty($_POST['product_description'][0])){

        for($i = 0; $i < $count; $i++){

          $product_description = "";
          if(isset($_POST['product_description'][$i])){
            $product_description = $_POST['product_description'][$i];
          }

          $hsn_sac = "";
          if(isset($_POST['hsn_sac'][$i])){
            $hsn_sac = $_POST['hsn_sac'][$i];
          }

          $taxable_value = "";
          if(isset($_POST['taxable'][$i])){
            $taxable_value = $_POST['taxable'][$i];
          }

          $addvoucherqry = "INSERT INTO `add_payment_voucher_details`(`pv_id`, `product_description`, `hsn_sac`, `taxable_amount`, `created`, `createdby`) VALUES ('".$last_id."', '".$product_description."', '".$hsn_sac."', '".$taxable_value."', '".date('Y-m-d H:i:s')."','".$user_id."')";

          $addvoucherrun = mysqli_query($conn, $addvoucherqry);
        }

        if($addvoucherrun){
          $_SESSION['msg']['success'] = "Payment Voucher Added Successfully.";
          header('location: add-payment-voucher.php');exit;
        }
        else{
          $_SESSION['msg']['fail'] = "Payment Voucher Added Fail.";
          header('location: add-payment-voucher.php');exit;
        }
      }
    }
  }

  if(isset($_POST['update'])){
    $last_id = $_REQUEST['id'];
    $user_id = $_SESSION['auth']['id'];
    $voucher = $_POST['voucherno'];
    $voucher_date = date("Y-m-d", strtotime(str_replace("/","-",$_POST['voucher_date'])));
    $voucher_type = $_POST['voucher_type'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $transporter_name = $_POST['party'];
    $ledger_id = $_POST['party1'];
    $transporter_invoice = $_POST['invoice'];
    $total_amount = $_POST['total_amount'];
    $igst = $_POST['igst'];
    $igst_amount = $_POST['total_igst'];
    $cgst = $_POST['cgst'];
    $cgst_amount = $_POST['total_cgst'];
    $sgst = $_POST['sgst'];
    $sgst_amount = $_POST['total_sgst'];

    $editvoucherqry = "UPDATE `add_payment_voucher` SET `voucher_no`= '".$voucher."', `voucher_date`= '".$voucher_date."', 
    `voucher_type`= '".$voucher_type."', `state_id`= '".$state."', `district`= '".$district."', `transporter_id`= '".$transporter_name."', `ledger_id` = '".$ledger_id."', `transporter_invoice_number`= '".$transporter_invoice."', `total`= '".$total_amount."', `igst`= '".$igst."', `igst_amount`= '".$igst_amount."', `cgst`= '".$cgst."', `cgst_amount`= '".$cgst_amount."', `sgst`= '".$sgst."', `sgst_amount`= '".$sgst_amount."',  `modified`= '".date('Y-m-d H:i:s')."', `modifiedby`= '".$user_id."' WHERE id = '".$last_id."'";

    $editvoucherrun = mysqli_query($conn, $editvoucherqry);

    if($editvoucherrun){

      $delqry = "DELETE FROM `add_payment_voucher_details` WHERE pv_id = '".$last_id."'";
      mysqli_query($conn, $delqry);

      $count = count($_POST['product_description']);

      if(!empty($_POST['product_description'][0])){

        for($i = 0; $i < $count; $i++){

          $product_description = "";
          if(isset($_POST['product_description'][$i])){
            $product_description = $_POST['product_description'][$i];
          }

          $hsn_sac = "";
          if(isset($_POST['hsn_sac'][$i])){
            $hsn_sac = $_POST['hsn_sac'][$i];
          }

          $taxable_value = "";
          if(isset($_POST['taxable'][$i])){
            $taxable_value = $_POST['taxable'][$i];
          }

          $addvoucherqry = "INSERT INTO `add_payment_voucher_details`(`pv_id`, `product_description`, `hsn_sac`, `taxable_amount`, `created`, `createdby`) VALUES ('".$last_id."', '".$product_description."', '".$hsn_sac."', '".$taxable_value."', '".date('Y-m-d H:i:s')."','".$user_id."')";

          $addvoucherrun = mysqli_query($conn, $addvoucherqry);
        }

        if($addvoucherrun){
          $_SESSION['msg']['success'] = "Payment Voucher Updated Successfully.";
          header('location: add-payment-voucher.php');exit;
        }
        else{
          $_SESSION['msg']['fail'] = "Payment Voucher Updated Fail.";
          header('location: add-payment-voucher.php');exit;
        }
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
  <link rel="stylesheet" href="css/toggle/style.css">
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
          <form method="post" action="" autocomplete="off">
          <div class="row">
          
          
           <!-- Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                <!-- Main Catagory -->
                <div class="row">
                      <div class="col-12">
                        <div class="purchase-top-btns">
                        <?php 
                            if(isset($user_sub_module) && in_array("Courier Transport", $user_sub_module)){ 
                            ?>
                            <a href="courier-transport.php" class="btn btn-dark active">Courier Transport</a>
                            <?php }
                            if(isset($user_sub_module) && in_array("Add Courier Party", $user_sub_module)){
                            ?>
                            <!--<a href="courier-party.php" class="btn btn-dark  btn-fw">Add Courier Party</a>-->
                            <?php }
                            if(isset($user_sub_module) && in_array("Add Payment Voucher", $user_sub_module)){
                            ?>
                            <a href="add-payment-voucher.php" class="btn btn-dark  btn-fw">Add Payment Voucher</a>
                            <?php } 
                            if(isset($user_sub_module) && in_array("Courier Details", $user_sub_module)){
                            ?>
                            <a href="courier-details.php" class="btn btn-dark  btn-fw">Courier Details</a>
                            <?php } ?>
                            <a href="courier-payment.php" class="btn btn-dark  btn-fw">Payment To Courier</a>
                        </div>   
                      </div> 
                    </div>
                    <div>&nbsp;</div>
                    <h4 class="card-title">Add Payment Voucher</h4>
                    <hr>
                    
                   <br>
                    
                    <!-- First Row  -->
            

                <div class="form-group row">
                    <div class="col-12 col-md-4">
                       <label for="exampleInputName1">Voucher No<span class="text-danger">*</span></label>
                       <input type="text" name="voucherno" class="form-control" id="voucherno" required="" value="<?php if(isset($_REQUEST['id'])){echo $data['voucher_no']; } else { echo getpaymentvoucher('tax'); }?>">
                    </div>

                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                    
                    <div class="col-12 col-md-3">
                        <label for="exampleInputName1">State<span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" name="state" id="" style="width:100%" required="" data-parsley-errors-container="#error"> 
                        <option value="">Select State</option>
                        <?php
                            if(!isset($_REQUEST['id'])){
                                $data['state_id'] = '12';
                            }
                        ?>
                        <?php
                          $allstate = "SELECT * FROM `own_states` WHERE country_id = 101";
                          $allstaterun = mysqli_query($conn, $allstate);
                          while($allstatedata = mysqli_fetch_assoc($allstaterun)){ ?>
                          <option value="<?php echo $allstatedata['id'];?>" <?php echo (isset($data['state_id']) && $data['state_id'] == $allstatedata['id']) ? 'selected' : '';?>> <?php echo $allstatedata['name'];?></option>
                          <?php } ?>
                        </select>
                        <span id="error"></span>
                    </div>                  
                </div>
                 
                <div class="form-group row">         
                    <div class="col-12 col-md-4">
                       <label for="exampleInputName1">Voucher Date<span class="text-danger">*</span></label>
                       <div id="" class="input-group date datepicker">
  		                    <input type="text" class="form-control border" name="voucher_date"  required="" value="<?php if(isset($_REQUEST['id'])){echo date("d/m/Y",strtotime(str_replace("-","/",$data['voucher_date']))); } else { echo date("d/m/Y"); } ?>" data-parsley-errors-container="#error-voucher">
  		                    <span class="input-group-addon input-group-append border-left">
  		                        <span class="mdi mdi-calendar input-group-text"></span>
  		                    </span>
  		                </div>
  		                <span id="error-voucher"></span>
                    </div>
                    
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                  
                    <div class="col-12 col-md-3">
                        <label for="exampleInputName1">District</label>
                        <input type="text" name="district" class="form-control onlyalphabet" value="<?php if(isset($_REQUEST['id'])){echo $data['district']; }?>" data-parsley-pattern="^[a-zA-Z]+$" data-parsley-type="alphanum">
                    </div>     
                </div>                     
                
                  <div class="form-group row"> 
                    <div class="col-12 col-md-3">
                       <label for="exampleInputName1">Voucher Type<span class="text-danger">*</span></label>
                        <div class="row no-gutters">
                            <div class="col-12 col-md-6">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                   <input type="radio" class="form-check-input tax" name="voucher_type" id="optionsRadios1" value="tax" checked <?php if(isset($_REQUEST['id']) && $data['voucher_type'] == "tax"){ echo "checked"; }?>>
                                   TAX
                                  </label>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input tax" name="voucher_type" id="optionsRadios2" value="tax_free" <?php if(isset($_REQUEST['id']) && $data['voucher_type'] == "tax_free"){ echo "checked"; }?>>
                                    TAX FREE
                                  </label>
                                </div>
                            </div>
                        </div>
                    </div> 

                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>  
                    <div class="col-12 col-md-4">
                    <?php 
                    if(!isset($_REQUEST['id'])){
                      $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                      $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;

                      ?><div id="tax"><?php
                      $qry = "SELECT voucher_no FROM `add_payment_voucher` WHERE voucher_type = 'tax' AND pharmacy_id = '".$p_id."' AND financial_id = '".$financial_id."' ORDER BY voucher_no DESC LIMIT 1";
                      $run = mysqli_query($conn, $qry);
                      $taxdata = mysqli_fetch_assoc($run);
                      echo "Last TAX Voucher Number: ".$taxdata['voucher_no']."<br>";?></div>
                      
                      <div id="tax-free" style="display: none;">                      
                      <?php
                      $voucherqry = "SELECT voucher_no FROM `add_payment_voucher` WHERE voucher_type = 'tax_free' AND pharmacy_id = '".$p_id."' AND financial_id = '".$financial_id."' ORDER BY voucher_no DESC LIMIT 1";
                      $voucherrun = mysqli_query($conn, $voucherqry);
                      $taxfreedata = mysqli_fetch_assoc($voucherrun);
                      echo "Last TAX FREE Voucher Number: ".$taxfreedata['voucher_no'];?></div>
                      <?php
                    }
                    ?>
                    </div>

                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>  

                    <div class="col-12 col-md-3">
                       <label for="exampleInputName1">Customer Name<span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" name="party1" id="group" style="width:100%"> 
                          <option value="">Select Customer</option>
                          <?php
                            $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                          
                            $partyqry = "SELECT id, name FROM `ledger_master` WHERE pharmacy_id = '".$p_id."' AND group_id = '10,14' ORDER BY name";
                            $partyrun = mysqli_query($conn, $partyqry);
                            while ($allparty = mysqli_fetch_assoc($partyrun)) { ?>                
                          <option value="<?php echo $allparty['id']; ?>" <?php echo (isset($data['ledger_id']) && $data['ledger_id'] == $allparty['id']) ? 'selected': ''?>> <?php echo $allparty['name']; ?></option>
                          <?php  } ?>
                        </select>
                    </div> 
                  </div>  

                <div class="form-group row">         
                    <div class="col-12 col-md-4">
                       <label for="exampleInputName1">Transporter / Supplier Name<span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" name="party" id="group" style="width:100%" required="" data-parsley-errors-container="#error-supplier" onChange="gettransporterAddress(this.value);"> 
                          <option value="">Select Transporter</option>
                          <?php
                            $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                            $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;
                            $dataqry = "SELECT id, name FROM `courier_transport` WHERE pharmacy_id = '".$p_id."' AND financial_id = '".$financial_id."' ORDER BY name";
                            $datarun = mysqli_query($conn, $dataqry);
                            while ($alldata = mysqli_fetch_assoc($datarun)) { ?>                
                          <option value="<?php echo $alldata['id']; ?>" <?php echo (isset($data['transporter_id']) && $data['transporter_id'] == $alldata['id']) ? 'selected': ''?>> <?php echo $alldata['name']; ?></option>
                          <?php  } ?>
                        </select>
                        <span id="error-supplier"></span>
                    </div>

                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>              
                <center><address class="mb-15" id="transporterAddress"></address></center>
                </div>

                <div class="form-group row">
                    <div class="col-12 col-md-4">
                       <label for="exampleInputName1">Transporter / Supplier Invoice Number<span class="text-danger">*</span></label>
                       <input type="text" name="invoice" class="form-control" id="exampleInputName1" value="<?php if(isset($_REQUEST['id'])){echo $data['transporter_invoice_number']; }?>" required="">
                    </div>                   
                </div> 
                </div>
                </div>                             
            </div>

                <!-- Table ------------------------------------------------------------------------------------------------------>
                <div class="col-md-12 grid-margin stretch-card">
                  <!-- TABLE STARTS -->
                  <div class="card">
                    <div class="card-body">
                      <div class="col mt-3">
                        <div class="row">
                          <div class="col-12">
                            <div class="add_show" style="display: none;">
                            <a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product pull-right add_show"><i class="fa fa-plus mr-0 ml-0"></i></a>
                            </div>
                            <table id="order-listing" class="table datatable">
                            <thead>
                              <tr>
                                <th>NO</th>
                                <th>PRODUCT OR SERVICE DESCRIPTION</th>
                                <th>HSN OR SAC</th>
                                <th>TAXABLE VALUE</th>
                                <th>&nbsp;</th>
                              </tr>
                            </thead>
                            <tbody id="product-tbody">
                                <!-- Row Starts --> 	
                                <?php if(!isset($_REQUEST['id'])) { ?>
                                <tr class="product-tr"> 
                                    <td>1</td>
                                    <td><input type="text" name="product_description[]" required="" class="form-control" id="exampleInputName1" placeholder="PRODUCT OR SERVICE DESCRIPTION" ></td>
                                    <td><input type="text" name="hsn_sac[]" class="form-control" placeholder="HSN OR SAC"></td>
                                    <td><input type="text" name="taxable[]" class="form-control taxable onlynumber" placeholder="TAXABLE VALUE" autocomplete="off"></td> 
                                    <td><a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a>
                                    <a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product remove_last" style="display: none;"><i class="fa fa-close mr-0 ml-0"></i></a>
                                    </td>   
                                </tr><!-- End Row -->
                                <?php } else {?>
                                <?php foreach($sub_data as $key => $value) { ?>
                                  <tr class="product-tr"> 
                                    <td><?php echo $key+1; ?></td>
                                    <td><input type="text" name="product_description[]" required="" class="form-control" id="exampleInputName1" value="<?php echo (isset($value['product_description'])) ? $value['product_description'] : '';?>" ></td>
                                    <td><input type="text" name="hsn_sac[]" class="form-control" placeholder="HSN OR SAC" value="<?php echo (isset($value['hsn_sac'])) ? $value['hsn_sac'] : '';?>"></td>
                                    <td><input type="text" name="taxable[]" class="form-control taxable onlynumber" placeholder="TAXABLE VALUE" autocomplete="off" value="<?php echo (isset($value['taxable_amount'])) ? $value['taxable_amount'] : '';?>"></td> 
                                    <td><a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a>
                                    <a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product remove_last"><i class="fa fa-close mr-0 ml-0"></i></a>
                                    </td>   
                                </tr>
                                <?php } ?>
                                <?php } ?>
                            </tbody>
                            </table>
                          </div>                
                        </div>   
                      </div>

                      <hr>
                      <div class="col-12">
                      <div class="row">
                          <div class="col-md-4 offset-8">
                              <div class="form-group row">
                                <table class="table table-striped">
                                  <tbody>
                                    <tr>
                                      <td align="right" style="width:100px;">
                                        Total
                                      </td>
                                      <td align="right"><input class="form-control" type="text" name="total_amount" id="total_amount" value="<?php echo (isset($data['total'])) ? $data['total'] : ''; ?>" readonly="">
                                      </td>
                                    </tr>
                                    
                                    <tr class="tax_free">
                                      <td align="right">
                                        IGST %
                                      </td>
                                      <td align="right">
                                      <div class="form-group row">
                                        <div class="col-12 col-md-6">
                                        <input type="text" class="form-control onlynumber" value="<?php echo (isset($data['igst'])) ? $data['igst'] : ''; ?>" class="form-control" name="igst" id="igst">
                                        </div>
                                        <div class="col-12 col-md-6">
                                        <input type="text" class="form-control" id="total_igst" name="total_igst" value="<?php echo (isset($data['igst_amount'])) ? $data['igst_amount'] : ''; ?>"readonly="">
                                        </div>
                                        </div>
                                      </td>
                                    </tr>

                                    <tr class="tax_free">
                                      <td align="right">
                                        CGST %
                                      </td>
                                      <td align="right">
                                      <div class="form-group row">
                                        <div class="col-12 col-md-6">
                                        <input type="text" class="form-control onlynumber" value="<?php echo (isset($data['cgst'])) ? $data['cgst'] : ''; ?>" name="cgst" id="cgst">
                                        </div>
                                        <div class="col-12 col-md-6">
                                        <input type="text" class="form-control" id="total_cgst" name="total_cgst" value="<?php echo (isset($data['cgst_amount'])) ? $data['cgst_amount'] : ''; ?>"readonly="">
                                        </div>
                                        </div>
                                      </td>
                                    </tr>
                                    
                                    <tr class="tax_free">
                                      <td align="right">
                                        SGST %
                                      </td>
                                      <td align="right">
                                      <div class="form-group row">
                                        <div class="col-12 col-md-6">
                                        <input type="text" class="form-control onlynumber" value="<?php echo (isset($data['sgst'])) ? $data['sgst'] : ''; ?>" name="sgst" id="sgst">
                                        </div>
                                        <div class="col-12 col-md-6">
                                        <input type="text" class="form-control" id="total_sgst" name="total_sgst" value="<?php echo (isset($data['sgst_amount'])) ? $data['sgst_amount'] : '';?> "readonly="">
                                        </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                          </div>
                      </div>
                    </div>
      
                    <div class="col-md-12">
                      <a href="view-payment-voucher.php" class="btn btn-light pull-left">Back</a>
                      <?php
                      if(isset($_GET['id'])){
                        ?>
                      <button type="submit" name="update" class="btn btn-success pull-right">Edit</button>
                        <?php 
                      }else{
                      ?>
                      <button type="submit" name="submit" class="btn btn-success pull-right">Submit</button>
                      <?php } ?>
                    </div>
                </div> 
            </form>
          </div>
        </div>
        </div>
        </div>

        <div id="html-copy" style="display: none;">
            <table>
            <tr class="product-tr">
            <td>##SRNO##</td>
            <td><input type="text" name="product_description[]" class="form-control" placeholder="PRODUCT OR SERVICE DESCRIPTION" autocomplete="off" required=""></td>
            <td><input type="text" name="hsn_sac[]" class="form-control" placeholder="HSN OR SAC" autocomplete="off"></td>
            <td><input type="text" name="taxable[]" class="form-control taxable onlynumber" placeholder="TAXABLE VALUE" autocomplete="off"></td> 
            <td><a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a>
            <a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product remove_last"><i class="fa fa-close mr-0 ml-0"></i></a>
            </td>   
            </tr>                        
            </table>                        
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
  <script src="js/custom/add-payment-voucher.js"></script>
  
  <!-- Custom js for this page Modal Box-->
  <script src="js/modal-demo.js"></script>
  <script type="text/javascript">
    $(".product-select2").select2();
  </script>
  
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


 <!-- script for custom validation -->
 <script src="js/custom/onlynumber.js"></script>
 <script src="js/custom/onlyalphabet.js"></script>
 <script src="js/parsley.min.js"></script>
 <script type="text/javascript">
  $('form').parsley({
    excluded:':hidden'
  });

  function gettransporterAddress(val){
      $.ajax({
      type: "POST",
      url: "ajax.php",
      data:{'action':'partyaddress', 'id': val},
      dataType: "json",
      success: function(data){
        $("#transporterAddress").html(data.address); 
      }
      });
  }
 </script>
   
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
<!-- End custom js for this page-->
<!-- change status js -->
  <script src="js/custom/statusupdate.js"></script>
</body>


</html>
