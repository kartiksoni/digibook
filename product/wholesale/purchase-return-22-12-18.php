<?php $title = "Credit Note / Debit Note"; ?>
<?php include('include/usertypecheck.php');
include('include/permission.php');

$owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
$admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
$pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
$financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

if(isset($_GET['bill'])){
    $bill_id = $_GET['bill'];
    $billQ = "SELECT * FROM `purchase` WHERE id = '".$bill_id."' AND pharmacy_id = '".$pharmacy_id."'";
    $billR = mysqli_query($conn, $billQ);
    $bill_row = mysqli_fetch_assoc($billR);
    $purchase_data['vendor_id'] = $bill_row['vendor'];
   
    
}

if(isset($_GET['id'])){
  $id = $_GET['id'];
  $purchaseQry = "SELECT pr.*, st.state_code_gst as statecode FROM `purchase_return` pr LEFT JOIN ledger_master lg ON pr.vendor_id = lg.id LEFT JOIN own_states st ON lg.state = st.id WHERE pr.id='".$id."' AND pr.pharmacy_id = '".$pharmacy_id."' AND pr.financial_id = '".$financial_id."' ORDER BY pr.id DESC LIMIT 1";
  $purchase = mysqli_query($conn,$purchaseQry);
  $purchase_data = mysqli_fetch_assoc($purchase);
  
  $purchase_details_data = array();
  $editid = (isset($purchase_data['id'])) ? $purchase_data['id'] : '';
  $purchase_detailsQry = "SELECT * FROM `purchase_return_detail` WHERE pr_id='".$editid."'";
  $purchase_d = mysqli_query($conn,$purchase_detailsQry);
  while($rowe = mysqli_fetch_assoc($purchase_d)){
      $purchase_details_data[] = $rowe;
  }
}
if(isset($_POST['submit'])){
  
 
  $user_id = $_SESSION['auth']['id'];
  $debit_date = date('Y-m-d',strtotime(str_replace('/','-',$_POST["debit_date"])));
  $debit_note_number = $_POST['debit_note_number'];
  $vendor_id = $_POST['vendor_id'];
  $remarks = $_POST['remarks'];
  $debit_ac = $_POST['debit_ac'];
  $bill_no = $_POST['bill_no'];
  $bill_date = date('Y-m-d',strtotime(str_replace('/','-',$_POST["bill_date"])));

  $totalamount = (isset($_POST['totalamount']) && $_POST['totalamount'] != '') ? $_POST['totalamount'] : 0;
  $totaligst = (isset($_POST['totaligst']) && $_POST['totaligst'] != '') ? $_POST['totaligst'] : 0;
  $totalcgst = (isset($_POST['totalcgst']) && $_POST['totalcgst'] != '') ? $_POST['totalcgst'] : 0;
  $totalsgst = (isset($_POST['totalsgst']) && $_POST['totalsgst'] != '') ? $_POST['totalsgst'] : 0;
  $finalamount = (isset($_POST['finalamount']) && $_POST['finalamount'] != '') ? $_POST['finalamount'] : 0;

  if(isset($_GET['id']) && $_GET['id'] != ''){
    $insert = "UPDATE purchase_return SET ";
  }else{
      
      // ---------------------------------Count voucher number------- start------@shreya---------------------//
  $new_debit_note_number = getDebitNoteNumber();
  
      if($new_debit_note_number != ''){
         if($new_debit_note_number == $_POST['debit_note_number']){
               $debit_note_number = $new_debit_note_number;
         }if($new_voucher != $_POST['debit_note_number']){
          $debit_note_number = $new_debit_note_number;
         }
      }else{
        $debit_note_number = $_POST['debit_note_number'];
      }
      // ---------------------------------Count voucher number------- end------@shreya---------------------//
      
    $insert = "INSERT INTO purchase_return SET owner_id = '".$owner_id."', admin_id = '".$admin_id."',pharmacy_id = '".$pharmacy_id."',financial_id='".$financial_id."', ";
  }
  $insert .= "debit_note_date ='".$debit_date."',
                     debit_note_no ='".$debit_note_number."',
                     bill_no = '".$bill_no."',
                     bill_date = '".$bill_date."',
                     vendor_id ='".$vendor_id."',
                     remarks ='".$remarks."',
                     debit_note_settle ='".$debit_ac."',
                     totalamount = '".$totalamount."',
                     igst = '".$totaligst."',
                     cgst = '".$totalcgst."',
                     sgst = '".$totalsgst."',
                     finalamount = '".$finalamount."'";
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $insert .= ",modified ='".date('Y-m-d H:i:s')."',modifiedby = '".$user_id."' WHERE id='".$_GET['id']."'";
  }else{
    $insert .= ",created ='".date('Y-m-d H:i:s')."',createdby = '".$user_id."'";
  }

  $result = mysqli_query($conn,$insert);

  $last_id = mysqli_insert_id($conn);

  if($result){
    if(isset($_GET['id']) && $_GET['id'] != ''){
      $last_id = $_GET['id'];
      $delete = "DELETE FROM purchase_return_detail WHERE pr_id='".$last_id."'";
      mysqli_query($conn,$delete);
    }
    $count = count($_POST['product']);
      for($i=0;$i<$count;$i++){

        $product = "";
        if(isset($_POST["product"][$i])){
            $product = $_POST["product"][$i];
        }

        $product_id = "";
        if(isset($_POST["product_id"][$i])){
            $product_id = $_POST["product_id"][$i];
        }

        $purchase_id="";
        if(isset($_POST["purchase_id"][$i])){
          $purchase_id = $_POST["purchase_id"][$i];
        }

        $mrp = "";
        if(isset($_POST["mrp"][$i])){
            $mrp = $_POST["mrp"][$i];
        }

        $mfg_no = "";
        if(isset($_POST["mfg_no"][$i])){
            $mfg_no = $_POST["mfg_no"][$i];
        }
        
        $batch_no = "";
        if(isset($_POST["batch_no"][$i])){
            $batch_no = $_POST["batch_no"][$i];
        }

        $expiry = "";
        if(isset($_POST["expiry"][$i])){
            $expiry = date('Y-m-d',strtotime(str_replace('/','-',$_POST["expiry"][$i])));
        }

        $qty = "";
        if(isset($_POST["qty"][$i])){
            $qty = $_POST["qty"][$i];
        }

        $free_qty = "";
        if(isset($_POST["free_qty"][$i])){
            $free_qty = $_POST["free_qty"][$i];
        }

        $rate = "";
        if(isset($_POST["rate"][$i])){
            $rate = $_POST["rate"][$i];
        }

        $discount = "";
        if(isset($_POST["discount"][$i])){
            $discount = $_POST["discount"][$i];
        }

        $f_rate = "";
        if(isset($_POST["f_rate"][$i])){
            $f_rate = $_POST["f_rate"][$i];
        }

        $igst = 0;
        if(isset($_POST["igst"][$i]) && $_POST["igst"][$i] != ''){
            $igst = $_POST["igst"][$i];
        }

        $cgst = 0;
        if(isset($_POST["cgst"][$i]) && $_POST["cgst"][$i] != ''){
            $cgst = $_POST["cgst"][$i];
        }

        $sgst = 0;
        if(isset($_POST["sgst"][$i]) && $_POST["sgst"][$i] != ''){
            $sgst = $_POST["sgst"][$i];
        }

        $ammout = "";
        if(isset($_POST["ammout"][$i])){
            $ammout = $_POST["ammout"][$i];
        }

        $ins_product = "INSERT INTO `purchase_return_detail` (`pr_id`, `product_id`,`purchase_id`, `mrp`, `mfg_co`, `batchno`, `expiry`,`qty`, `free_qty`, `rate`, `discount`, `final_rate`,`igst`,`cgst`,`sgst`,`amount`,`created`,`createdby`) VALUES ('".$last_id."','".$product_id."', '".$purchase_id."', '".$mrp."', '".$mfg_no."', '".$batch_no."', '".$expiry."','".$qty."','".$free_qty."', '".$rate."', '".$discount."', '".$f_rate."', '".$igst."', '".$cgst."', '".$sgst."', '".$ammout."','".date('Y-m-d H:i:s')."','".$user_id."')";
        mysqli_query($conn,$ins_product);
      }
      if(isset($_GET['id']) && $_GET['id'] != ''){
        $_SESSION['msg']['success'] = 'Purchase Return Updated Successfully.';
      }else{
        $_SESSION['msg']['success'] = 'Purchase Return Added Successfully.';
      }
      header('location:purchase-return-list.php');exit;
      
  }else{
      if(isset($_GET['id']) && $_GET['id'] != ''){
          $_SESSION['msg']['fail'] = 'Purchase Return Updated Failed.';
        }else{
          $_SESSION['msg']['fail'] = 'Purchase Return Added Failed.';
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
  <title>Purchase Return</title>
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
  <link rel="stylesheet" href="css/parsley.css">
</head>
<body>
  <div class="container-scroller">
  
    <!-- Topbar -->
        <?php include "include/topbar.php" ?>
        <?php 
         function getDebitNoteNumber(){
            global $financial_id;
            global $pharmacy_id;
            global $conn;
            $voucher_no = '';

            $cashQuery = "SELECT * FROM `purchase_return` WHERE `pharmacy_id` = '".$pharmacy_id."' ORDER BY id DESC LIMIT 1";
            $cashRes = mysqli_query($conn, $cashQuery);
            if($cashRes){
              $count = mysqli_num_rows($cashRes);
              if($count !== '' && $count !== 0){
                if($count != ''){
                  $row = mysqli_fetch_array($cashRes);
                  $vouchernoarr = explode('-',$row['debit_note_no']);
                  $voucherno = $vouchernoarr[1];
                  $voucherno = $voucherno + 1;
                  $voucherno = sprintf("%05d", $voucherno);
                  $voucher_no = 'DB-'.$voucherno;
                }

              }else{
                $voucherno = sprintf("%05d", 1);
                $voucher_no = 'DB-'.$voucherno;
              }
            }
            return $voucher_no;
          }

        ?>
    
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        
        <!-- Right Sidebar -->
        <?php include "include/sidebar-right.php" ?>
        
       
       <!-- Left Navigation -->
        <?php include "include/sidebar-nav-left.php" ?>
        
        
      
      
      <div class="main-panel">
      
        <div class="content-wrapper">
          <?php include('include/flash.php'); ?>
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
                  <form class="forms-sample" method="post" autocomplete="off">
                  
                  <div class="form-group row">
                  
                      <div class="col-12 col-md-2">
                       <label for="debit_date">Debit Note Date <span class="text-danger">*</span></label>
                            <div id="" class="input-group date datepicker">
                            <?php 
                                if(isset($_GET['id']) && $_GET['id'] != ''){
                                    $db_note_date_val = (isset($purchase_data['debit_note_date']) && $purchase_data['debit_note_date'] != '') ? date("d/m/Y",strtotime($purchase_data['debit_note_date'])) : '';
                                }else{
                                    $db_note_date_val = date('d/m/Y');
                                }
                            
                            ?>
                            <input type="text" class="form-control border debit_date" value="<?php echo (isset($db_note_date_val)) ? $db_note_date_val : ''; ?>" name="debit_date" data-parsley-errors-container="#error-dbnote" required>
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          <span id="error-dbnote"></span>
                      </div>
                      <?php 
                          $voucherVal = getDebitNoteNumber();
                      ?>
                      <div class="col-12 col-md-2">
                        <label for="debit_note_number">Debit Note Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="<?php echo (isset($purchase_data['debit_note_no'])) ? $purchase_data['debit_note_no'] : $voucherVal; ?>" name="debit_note_number" id="debit_note_number" placeholder="Number" required>
                      </div>
                      
                      <div class="col-12 col-md-3">
                      <label>Select Vendor <span class="text-danger">*</span></label>
                          <select class="js-example-basic-single vendor_id" style="width:100%" name="vendor_id" id="vendor_id" data-parsley-errors-container="#error-vendor" required> 
                              <option value="">Please select</option>
                              <?php 
                                $getAllVendorQuery = "SELECT lgr.id, lgr.name, st.state_code_gst as statecode FROM ledger_master lgr LEFT JOIN own_states st ON lgr.state = st.id WHERE lgr.status=1 AND lgr.pharmacy_id = '".$pharmacy_id."' AND lgr.group_id=14 order by lgr.name";
                                $getAllVendorRes = mysqli_query($conn, $getAllVendorQuery);
                              ?>
                              <?php if($getAllVendorRes && mysqli_num_rows($getAllVendorRes) > 0){ ?>
                                <?php while ($getAllVendorRow = mysqli_fetch_array($getAllVendorRes)) { ?>
                                  <option <?php if(isset($purchase_data['vendor_id']) && $purchase_data['vendor_id'] == $getAllVendorRow['id']){echo "selected";} ?> value="<?php echo $getAllVendorRow['id']; ?>" data-id="<?php echo (isset($getAllVendorRow['statecode'])) ? $getAllVendorRow['statecode'] : ''; ?>"><?php echo $getAllVendorRow['name']; ?></option>
                                <?php } ?>
                              <?php } ?>
                              
                             

                          </select>
                          <span id="error-vendor"></span>
                          <input type="hidden" name="statecode" id="statecode" value="<?php echo (isset($purchase_data['statecode'])) ? $purchase_data['statecode'] : ''; ?>">
                          <input type="hidden" name="current_statecode" id="current_statecode" value="<?php echo (isset($_SESSION['state_code'])) ? $_SESSION['state_code'] : '';  ?>">
                      </div>
                      
                      <div class="col-12 col-md-2">
                        <label for="debit_note_number">Bill No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="bill_no" id="bill_no" value="<?php echo (isset($purchase_data['bill_no'])) ? $purchase_data['bill_no'] : ''; ?>" placeholder="Bill No" required>
                      </div>
                      
                      <div class="col-12 col-md-2">
                       <label for="debit_date">Bill Date <span class="text-danger">*</span></label>
                            <div id="" class="input-group date datepicker">
                            <?php 
                                if(isset($_GET['id']) && $_GET['id'] != ''){
                                    $db_note_date_val = (isset($purchase_data['bill_date']) && $purchase_data['bill_date'] != '') ? date("d/m/Y",strtotime($purchase_data['bill_date'])) : '';
                                }else{
                                    $db_note_date_val = date('d/m/Y');
                                }
                            
                            ?>
                            <input type="text" class="form-control border debit_date" value="<?php echo (isset($db_note_date_val)) ? $db_note_date_val : ''; ?>" name="bill_date" data-parsley-errors-container="#error-dbnote" required>
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          <span id="error-dbnote"></span>
                      </div>
                      
                      
                      
                      <div class="col-12 col-md-12 mt-30 mb-30">
                          <table id="order-listing1" class="table">
                                    <thead>
                                      <tr>
                                          <th>Sr No.</th>
                                          <th>Product</th>
                                          <th>MRP</th>
                                          <th>MFG. Co.</th>
                                          <th>Batch No</th>
                                          <th>Expiry</th>
                                          <th>Qty</th>
                                          <th>Free Qty</th>
                                          <th>Rate</th>
                                          <th>Discount</th>
                                          <th>Rate</th>
                                          <th>Amount</th>
                                          <th>&nbsp;</th>
                                      </tr>
                                    </thead>
                                    <tbody id="product-tbody">
                                      <!-- Row Starts -->
                                      <?php if(!isset($_GET['id'])){ ?>
                                      <tr class="product-tr">
                                          <td>1</td>
                                          <td>
                                            <input type="text" placeholder="Product" class="tags form-control" required="" name="product[]">
                                            <input type="hidden" class="product-id" name="product_id[]">
                                             <input type="hidden" value="" class="purchase-id" name="purchase_id[]">
                                            <small class="text-danger empty-message0"></small>
                                          </td>
                                            <td><input name="mrp[]" type="text" class="form-control mrp onlynumber" id="mrp" placeholder="MRP"></td>
                                            <td><input name="mfg_no[]" type="text" class="form-control mfg_no" id="mfg_no" placeholder="MFG. Co."></td>
                                            <td><input name="batch_no[]" type="text" class="form-control batch_no" id="batch_no" placeholder="Batch No."></td>
                                            <td>
                                                <input name="expiry[]" type="text" class="form-control expiry datepicker-ex" id="expiry" placeholder="Expiry">
                                                <small class="text-danger expired"></small>
                                            </td>
                                            <td><input name="qty[]" type="text" class="form-control onlynumber qty" id="qty" placeholder="Qty." required></td>
                                            <td><input name="free_qty[]" type="text" class="form-control onlynumber free_qty" id="free_qty" placeholder="Free Qty"></td>
                                            <td><input name="rate[]" type="text" class="form-control onlynumber rate" id="rate" placeholder="Rate"></td>
                                            <td><input name="discount[]" type="text" class="form-control onlynumber discount" id="discount" placeholder="Discount"></td>
                                            <td><input type="text" name=f_rate[] class="form-control f_rate onlynumber priceOnly" id="f_rate" placeholder="Rate" autocomplete="off" required></td>
                                            <td>

                                              <!-- FOR GST START -->
                                              <input type="hidden" name="igst[]" class="f_igst">
                                              <input type="hidden" name="cgst[]" class="f_cgst">
                                              <input type="hidden" name="sgst[]" class="f_sgst">
                                              <!-- FOR GST END -->

                                              <input name="ammout[]" type="text" class="form-control onlynumber ammout" readonly="" id="ammout" placeholder="Ammount">
                                            </td>
                                            <td><a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a><a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product remove_last" style="display: none;"><i class="fa fa-close mr-0 ml-0"></i></a></td>
                                      </tr><!-- End Row -->
                                      <?php }else{
                                        foreach ($purchase_details_data as $key => $value) {
                                        ?>
                                        <tr class="product-tr">
                                          <td><?php echo $key+1; ?></td>
                                          <td>
                                            <?php 
                                            $productQry = "SELECT * FROM `product_master` WHERE id='".$value['product_id']."'";
                                            $product_id = mysqli_query($conn, $productQry);
                                            $rowp = mysqli_fetch_array($product_id);
                                            ?>
                                            <input type="text" placeholder="Product" value="<?php echo $rowp['product_name']; ?>" class="tags form-control" required="" name="product[]">
                                            <input type="hidden" value="<?php echo $value['product_id']; ?>" class="product-id" name="product_id[]">
                                            <input type="hidden" value="<?php echo $value['purchase_id']; ?>" class="purchase-id" name="purchase_id[]">
                                            <small class="text-danger empty-message0"></small>
                                          </td>
                                            <td><input name="mrp[]" value="<?php echo $value['mrp']; ?>" type="text" class="form-control mrp" id="mrp" placeholder="MRP"></td>
                                            <td><input name="mfg_no[]" value="<?php echo $value['mfg_co']; ?>" type="text" class="form-control mfg_no" id="mfg_no" placeholder="MFG. Co."></td>
                                            <td><input name="batch_no[]" value="<?php echo $value['batchno']; ?>" type="text" class="form-control batch_no" id="batch_no" placeholder="Batch No."></td>
                                            <td>
                                                <input name="expiry[]" type="text" value="<?php echo date("d/m/Y",strtotime(str_replace("-","/",$value['expiry']))); ?>" class="form-control expiry datepicker-ex" id="expiry" placeholder="Expiry">
                                                <small class="text-danger expired"></small>
                                            </td>
                                            <td><input name="qty[]" type="text" value="<?php echo $value['qty']; ?>" class="form-control qty" id="qty" placeholder="Qty." required></td>
                                            <td><input name="free_qty[]" type="text" value="<?php echo $value['free_qty'] ?>" class="form-control free_qty" id="free_qty" placeholder="Free Qty"></td>
                                            <td><input name="rate[]" type="text" value="<?php echo $value['rate']; ?>" class="form-control rate" id="rate" placeholder="Rate"></td>
                                            <td><input name="discount[]" type="text" value="<?php echo $value['discount']; ?>" class="form-control discount" id="discount" placeholder="Discount"></td>
                                            <td><input type="text" name="f_rate[]" value="<?php echo $value['final_rate']; ?>" class="form-control f_rate priceOnly" id="f_rate" placeholder="Rate" autocomplete="off" required></td>
                                            <td>
                                              <!-- FOR GST START -->
                                              <input type="hidden" name="igst[]" class="f_igst" value="<?php echo (isset($value['igst'])) ? $value['igst'] : ''; ?>">
                                              <input type="hidden" name="cgst[]" class="f_cgst" value="<?php echo (isset($value['cgst'])) ? $value['cgst'] : ''; ?>">
                                              <input type="hidden" name="sgst[]" class="f_sgst" value="<?php echo (isset($value['sgst'])) ? $value['sgst'] : ''; ?>">
                                              <!-- FOR GST END -->

                                              <input name="ammout[]" type="text" value="<?php echo $value['amount']; ?>" class="form-control ammout" readonly="" id="ammout" placeholder="Ammount">
                                            </td>
                                            <td><a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a><a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product remove_last"><i class="fa fa-close mr-0 ml-0"></i></a></td>
                                        </tr><!-- End Row -->
                                        <?php
                                      }
                                      } ?> 
                                    </tbody>
                                  </table>
                      </div>
                      
                      <div class="col-md-6">
                        <div class="col-12 col-md-12">
                          <label for="exampleInputName1">Remarks / Reason for Return </label>
                          <textarea class="form-control" name="remarks" id="remarks" rows="3"><?php echo (isset($purchase_data['remarks'])) ? $purchase_data['remarks'] : ''; ?>
                          </textarea>
                        </div>
                        
                       
                        
                        <div class="col-12 col-md-12">
                          <hr>
                          <div class="row no-gutters">
                            <div class="col-12 col-md-12">
                                <label for="exampleInputName1">Debit Note Settle in A/c.</label>
                                <div class="row no-gutters">
                                      
                                  <div class="col">
                                      <div class="form-radio">
                                      <label class="form-check-label">
                                      <input type="radio" class="form-check-input debit_ac" name="debit_ac" id="optionsRadios1" value="1" <?php if(isset($purchase_data['debit_note_settle']) && $purchase_data['debit_note_settle'] == "1"){echo "checked";}else{echo "checked";} ?> >
                                     ON HOLD
                                      </label>
                                      </div>
                                  </div>
                                  
                                  <div class="col">
                                      <div class="form-radio">
                                      <label class="form-check-label">
                                      <input type="radio" class="form-check-input debit_ac" name="debit_ac" id="optionsRadios2" value="0" <?php if(isset($purchase_data['debit_note_settle']) && $purchase_data['debit_note_settle'] == "0"){echo "checked";} ?> >
                                      EFFECT IN PARTY LEDGER
                                      </label>
                                      </div>
                                  </div>
                                
                                </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <table class="table table-striped" width="100%">
                          <tbody>
                            <tr>
                              <td width="70%" align="right">Total Amount</td>
                              <td width="30%">
                                <input type="text" name="totalamount" id="totalamount" class="form-control text-right onlynumber" placeholder="Total Amount" value="<?php echo (isset($purchase_data['totalamount'])) ? $purchase_data['totalamount'] : ''; ?>" readonly="">
                              </td>
                            </tr>
                            <tr>
                              <td width="70%" align="right">IGST</td>
                              <td width="30%">
                                <input type="text" name="totaligst" id="totaligst" class="form-control text-right onlynumber" placeholder="Total IGST" value="<?php echo (isset($purchase_data['igst'])) ? $purchase_data['igst'] : ''; ?>" readonly="">
                              </td>
                            </tr>
                            <tr>
                              <td width="70%" align="right">CGST</td>
                              <td width="30%">
                                <input type="text" name="totalcgst" id="totalcgst" class="form-control text-right onlynumber" value="<?php echo (isset($purchase_data['cgst'])) ? $purchase_data['cgst'] : ''; ?>" placeholder="Total CGST" readonly="">
                              </td>
                            </tr>
                            <tr>
                              <td width="70%" align="right">SGST</td>
                              <td width="30%">
                                <input type="text" name="totalsgst" id="totalsgst" class="form-control text-right onlynumber" value="<?php echo (isset($purchase_data['sgst'])) ? $purchase_data['sgst'] : ''; ?>" placeholder="Total SGST" readonly="">
                              </td>
                            </tr>
                            <tr>
                              <td width="70%" align="right">Final Amount</td>
                              <td width="30%">
                                <input type="text" name="finalamount" id="finalamount" class="form-control text-right onlynumber" value="<?php echo (isset($purchase_data['finalamount'])) ? $purchase_data['finalamount'] : ''; ?>" placeholder="Final Amount" readonly="">
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>

                      <div class="col-md-12">
                          <button type="submit" name="submit" class="btn btn-success mt-30 pull-right">Save</button>
                      </div>
                        
                     
                    
                  </div> 
                  
                 
                   
                   
                  </form>
                  <div id="html-copy" style="display: none;">
                    <table>
                      <tr class="product-tr">
                            <td>##SRPRODUCT##</td>
                            <td>
                              <input type="text" placeholder="Product" class="tags form-control" required="" name="product[]">
                              <input type="hidden" class="product-id" name="product_id[]">
                              <input type="hidden" value="  " class="purchase-id" name="purchase_id[]">
                              <small class="text-danger empty-message##PRODUCTCOUNT##"></small>
                            </td>
                              <td><input name="mrp[]" type="text" class="form-control onlynumber mrp" id="mrp" placeholder="MRP"></td>
                              <td><input name="mfg_no[]" type="text" class="form-control mfg_no" id="mfg_no" placeholder="MFG. Co."></td>
                              <td><input name="batch_no[]" type="text" class="form-control batch_no" id="batch_no" placeholder="Batch No."></td>
                              <td>
                                  <input name="expiry[]" type="text" class="form-control expiry datepicker-ex" id="expiry" placeholder="Expiry">
                                  <small class="text-danger expired"></small>
                              </td>
                              <td><input name="qty[]" type="text" class="form-control onlynumber qty" id="qty" placeholder="Qty." required></td>
                              <td><input name="free_qty[]" type="text" class="form-control onlynumber free_qty" id="free_qty" placeholder="Free Qty"></td>
                              <td><input name="rate[]" type="text" class="form-control onlynumber rate" id="rate" placeholder="Rate"></td>
                              <td><input name="discount[]" type="text" class="form-control onlynumber discount" id="discount" placeholder="Discount"></td>
                              <td><input type="text" name=f_rate[] class="form-control onlynumber f_rate priceOnly" id="f_rate" placeholder="Rate" autocomplete="off" required></td>
                              <td>
                                <!-- FOR GST START -->
                                <input type="hidden" name="igst[]" class="f_igst">
                                <input type="hidden" name="cgst[]" class="f_cgst">
                                <input type="hidden" name="sgst[]" class="f_sgst">
                                <!-- FOR GST END -->
                                <input name="ammout[]" type="text" class="form-control onlynumber ammout" id="ammout" readonly="" placeholder="Ammount">
                              </td>
                              <td><a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a><a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product "><i class="fa fa-close mr-0 ml-0"></i></a></td>
                        </tr><!-- End Row -->   
                    </table>
                  </div>
                </div>
              </div>
            </div>
            
           
            
             <!-- Table ------------------------------------------------------------------------------------------------------>
            
              
            
            
            
            
      
            
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
 
 <script type="text/javascript">
   $('.datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy',
      autoclose : true
    });
 </script>
 <script type="text/javascript">
  $(document).on('focus',".datepicker-ex", function(){ //bind to all instances of class "date". 
    $(this).datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy',
      autoclose : true
    });
    $(this).datepicker("refresh");
});
</script>
 
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('.datatable').DataTable();
  </script>
  <script src="js/jquery-ui.js"></script>
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script>
  <script src="js/custom/purchase-return.js"></script>
  
  <!-- script for custom validation -->
<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>
<script src="js/custom/onlynumber.js"></script>
  
  
  <!-- End custom js for this page-->
</body>


</html>
