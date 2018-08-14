<?php include('include/usertypecheck.php');
if(isset($_GET['id'])){
  $id = $_GET['id'];
  $purchaseQry = "SELECT * FROM `purchase_return` WHERE id='".$id."' ORDER BY id DESC LIMIT 1";
  $purchase = mysqli_query($conn,$purchaseQry);
  $purchase_data = mysqli_fetch_assoc($purchase);
  

  $purchase_details_data = array();
  $purchase_detailsQry = "SELECT * FROM `purchase_return_detail` WHERE pr_id='".$id."'";
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
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $insert = "UPDATE purchase_return SET ";
  }else{
    $insert = "INSERT INTO purchase_return SET ";
  }
  $insert .= "debit_note_date ='".$debit_date."',
                     debit_note_no ='".$debit_note_number."',
                     vendor_id ='".$vendor_id."',
                     remarks ='".$remarks."',
                     debit_note_settle ='".$debit_ac."'";
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

        $ammout = "";
        if(isset($_POST["ammout"][$i])){
            $ammout = $_POST["ammout"][$i];
        }

        $ins_product = "INSERT INTO `purchase_return_detail` (`pr_id`, `product_id`, `mrp`, `mfg_co`, `batchno`, `expiry`,`qty`, `free_qty`, `rate`, `discount`, `final_rate`,`amount`,`created`,`createdby`) VALUES ('".$last_id."','".$product_id."',  '".$mrp."', '".$mfg_no."', '".$batch_no."', '".$expiry."','".$qty."','".$free_qty."', '".$rate."', '".$discount."', '".$f_rate."', '".$ammout."','".date('Y-m-d H:i:s')."','".$user_id."')";
        
       

        mysqli_query($conn,$ins_product);
      }
      if(isset($_GET['id']) && $_GET['id'] != ''){
        $_SESSION['msg']['success'] = 'Purchase Return Updated Successfully.';
      }else{
        $_SESSION['msg']['success'] = 'Purchase Return Added Successfully.';
      }
      header('location:purchase-return.php');exit;
      
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
</head>
<body>
  <div class="container-scroller">
  
    <!-- Topbar -->
        <?php include "include/topbar.php" ?>
        <?php 
         function getDebitNoteNumber(){
            global $conn;
            $voucher_no = '';

            $cashQuery = "SELECT * FROM `purchase_return` ORDER BY id DESC LIMIT 1";
            $cashRes = mysqli_query($conn, $cashQuery);
            if($cashRes){
              $count = mysqli_num_rows($cashRes);
              
              if($count !== '' && $count !== 0){
                if($count != ''){
                  $vouchernoarr = explode('-',$count);
                  $voucherno = $vouchernoarr[0];
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
                            <a href="purchase.php" class="btn btn-dark active">Purchase Bill</a>
                            <a href="purchase-return.php" class="btn btn-dark">Purchase Return</a>
                            <a href="purchase-return-list.php" class="btn btn-dark">Purchase Return List</a>
                            <a href="#" class="btn btn-dark btn-fw">Cancel List</a>
                            <a href="purchase-history.php" class="btn btn-dark btn-fw">History</a>
                            <a href="#" class="btn btn-dark btn-fw">Settings</a>
                        </div>   
                    </div> 
                    </div>
                    <hr>
                    
                
                
                 <br>
                  <form class="forms-sample" method="post">
                  
                  <div class="form-group row">
                  
                      <div class="col-12 col-md-2">
                       <label for="debit_date">Debit Note Date</label>
                            <div id="" class="input-group date datepicker">
                            <input type="text" class="form-control border debit_date" value="<?php echo (isset($purchase_data['debit_note_date'])) ? date("d/m/Y",strtotime(str_replace("-","/",$purchase_data['debit_note_date']))) : ''; ?>" name="debit_date" >
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                      </div>
                      <?php 
                          $voucherVal = getDebitNoteNumber();
                      ?>
                      <div class="col-12 col-md-2">
                       <label for="debit_note_number">Debit Note Number</label>
                            <input type="text" class="form-control" value="<?php echo (isset($purchase_data['debit_note_no'])) ? $purchase_data['debit_note_no'] : $voucherVal; ?>" name="debit_note_number" id="debit_note_number" placeholder="Number">
                      </div>
                      
                      <div class="col-12 col-md-3">
                      <label>Select Vendor</label>
                          <select class="js-example-basic-single vendor_id" style="width:100%" name="vendor_id" id="vendor_id" data-parsley-errors-container="#error-vendor" required> 
                              <option value="">Please select</option>
                              <?php 
                                $getAllVendorQuery = "SELECT id, name FROM ledger_master WHERE status=1 AND group_id=14 order by name";
                                $getAllVendorRes = mysqli_query($conn, $getAllVendorQuery);
                              ?>
                              <?php if($getAllVendorRes && mysqli_num_rows($getAllVendorRes) > 0){ ?>
                                <?php while ($getAllVendorRow = mysqli_fetch_array($getAllVendorRes)) { ?>
                                  <option <?php if(isset($purchase_data['vendor_id']) && $purchase_data['vendor_id'] == $getAllVendorRow['id']){echo "selected";} ?> value="<?php echo $getAllVendorRow['id']; ?>"><?php echo $getAllVendorRow['name']; ?></option>
                                <?php } ?>
                              <?php } ?>
                          </select>
                      
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
                                            <small class="text-danger empty-message0"></small>
                                          </td>
                                            <td><input name="mrp[]" type="text" class="form-control mrp" id="mrp" placeholder="MRP"></td>
                                            <td><input name="mfg_no[]" type="text" class="form-control mfg_no" id="mfg_no" placeholder="MFG. Co."></td>
                                            <td><input name="batch_no[]" type="text" class="form-control batch_no" id="batch_no" placeholder="Batch No."></td>
                                            <td><input name="expiry[]" type="text" class="form-control expiry" id="expiry" placeholder="Expiry"></td>
                                            <td><input name="qty[]" type="text" class="form-control qty" id="qty" placeholder="Qty."></td>
                                            <td><input name="free_qty[]" type="text" class="form-control free_qty" id="free_qty" placeholder="Free Qty"></td>
                                            <td><input name="rate[]" type="text" class="form-control rate" id="rate" placeholder="Rate"></td>
                                            <td><input name="discount[]" type="text" class="form-control discount" id="discount" placeholder="Discount"></td>
                                            <td><input type="text" name=f_rate[] class="form-control f_rate priceOnly" id="f_rate" placeholder="Rate" autocomplete="off"></td>
                                            <td><input name="ammout[]" type="text" class="form-control ammout" readonly="" id="ammout" placeholder="Ammount"></td>
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
                                            <small class="text-danger empty-message0"></small>
                                          </td>
                                            <td><input name="mrp[]" value="<?php echo $value['mrp']; ?>" type="text" class="form-control mrp" id="mrp" placeholder="MRP"></td>
                                            <td><input name="mfg_no[]" value="<?php echo $value['mfg_co']; ?>" type="text" class="form-control mfg_no" id="mfg_no" placeholder="MFG. Co."></td>
                                            <td><input name="batch_no[]" value="<?php echo $value['batchno']; ?>" type="text" class="form-control batch_no" id="batch_no" placeholder="Batch No."></td>
                                            <td><input name="expiry[]" type="text" value="<?php echo date("d/m/Y",strtotime(str_replace("-","/",$value['expiry']))); ?>" class="form-control expiry datepicker-ex" id="expiry" placeholder="Expiry"></td>
                                            <td><input name="qty[]" type="text" value="<?php echo $value['qty']; ?>" class="form-control qty" id="qty" placeholder="Qty."></td>
                                            <td><input name="free_qty[]" type="text" value="<?php echo $value['free_qty'] ?>" class="form-control free_qty" id="free_qty" placeholder="Free Qty"></td>
                                            <td><input name="rate[]" type="text" value="<?php echo $value['rate']; ?>" class="form-control rate" id="rate" placeholder="Rate"></td>
                                            <td><input name="discount[]" type="text" value="<?php echo $value['discount']; ?>" class="form-control discount" id="discount" placeholder="Discount"></td>
                                            <td><input type="text" name="f_rate[]" value="<?php echo $value['final_rate']; ?>" class="form-control f_rate priceOnly" id="f_rate" placeholder="Rate" autocomplete="off"></td>
                                            <td><input name="ammout[]" type="text" value="<?php echo $value['amount']; ?>" class="form-control ammout" readonly="" id="ammout" placeholder="Ammount"></td>
                                            <td><a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a><a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product remove_last" style="display: none;"><i class="fa fa-close mr-0 ml-0"></i></a></td>
                                        </tr><!-- End Row -->
                                        <?php
                                      }
                                      } ?> 	
                                      
                                   
                                      
                                     
                                    </tbody>
                                  </table>
                      </div>
                      
                      
                      <div class="col-12 col-md-6">
                      <label for="exampleInputName1">Remarks / Reason for Return </label>
                        <textarea class="form-control" name="remarks" id="remarks" rows="3"><?php echo (isset($purchase_data['remarks'])) ? $purchase_data['remarks'] : ''; ?></textarea>
                      </div>
                      
                     
                      
                      <div class="col-12 col-md-12">
                       <hr>
                      	<div class="row no-gutters">
                      
                          <div class="col-12 col-md-2">
                              <label for="exampleInputName1">Debit Note Settle in A/c.</label>
                            <div class="row no-gutters">
                                    
                                        <div class="col">
                                            <div class="form-radio">
                                            <label class="form-check-label">
                                            <input type="radio" class="form-check-input debit_ac" name="debit_ac" id="optionsRadios1" value="1" <?php if(isset($purchase_data['debit_note_settle']) && $purchase_data['debit_note_settle'] == "1"){echo "checked";}else{echo "checked";} ?> >
                                           Yes
                                            </label>
                                            </div>
                                        </div>
                                        
                                        <div class="col">
                                            <div class="form-radio">
                                            <label class="form-check-label">
                                            <input type="radio" class="form-check-input debit_ac" name="debit_ac" id="optionsRadios2" value="0" <?php if(isset($purchase_data['debit_note_settle']) && $purchase_data['debit_note_settle'] == "0"){echo "checked";} ?> >
                                            No
                                            </label>
                                            </div>
                                        </div>
                                    
                                    </div>
                          </div>
                      
                      <div class="col">
	                      <button type="submit" name="submit" class="btn btn-success mt-30 pull-right">Save</button>
                      </div>
                      
                      </div>
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
                              <small class="text-danger empty-message##PRODUCTCOUNT##"></small>
                            </td>
                              <td><input name="mrp[]" type="text" class="form-control mrp" id="mrp" placeholder="MRP"></td>
                              <td><input name="mfg_no[]" type="text" class="form-control mfg_no" id="mfg_no" placeholder="MFG. Co."></td>
                              <td><input name="batch_no[]" type="text" class="form-control batch_no" id="batch_no" placeholder="Batch No."></td>
                              <td><input name="expiry[]" type="text" class="form-control expiry datepicker-ex" id="expiry" placeholder="Expiry"></td>
                              <td><input name="qty[]" type="text" class="form-control qty" id="qty" placeholder="Qty."></td>
                              <td><input name="free_qty[]" type="text" class="form-control free_qty" id="free_qty" placeholder="Free Qty"></td>
                              <td><input name="rate[]" type="text" class="form-control rate" id="rate" placeholder="Rate"></td>
                              <td><input name="discount[]" type="text" class="form-control discount" id="discount" placeholder="Discount"></td>
                              <td><input type="text" name=f_rate[] class="form-control f_rate priceOnly" id="f_rate" placeholder="Rate" autocomplete="off"></td>
                              <td><input name="ammout[]" type="text" class="form-control ammout" id="ammout" readonly="" placeholder="Ammount"></td>
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
    $('#datepicker-popup3').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
    });
 </script>
 
 <script>
    $('#datepicker-popup4').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
    });
 </script>
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
  	 $('#order-listing2').DataTable();
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
  
  
  <!-- End custom js for this page-->
</body>


</html>
