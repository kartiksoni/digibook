<?php $title = "Purchase Bill"; ?>
<?php include('include/usertypecheck.php');
include('include/permission.php');

    $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
    $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
    $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

    if(isset($_GET['id'])){
      $id = $_GET['id'];
      $purchaseQry = "SELECT * FROM `purchase` WHERE id='".$id."' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
      $purchase = mysqli_query($conn,$purchaseQry);
      $purchase_data = mysqli_fetch_assoc($purchase);
    
      $purchase_details_data = array();
      $editid = (isset($purchase_data['id'])) ? $purchase_data['id'] : '';
      $purchase_detailsQry = "SELECT * FROM `purchase_details` WHERE purchase_id='".$editid."'";
      $purchase_d = mysqli_query($conn,$purchase_detailsQry);
      while($rowe = mysqli_fetch_assoc($purchase_d)){
          $purchase_details_data[] = $rowe;
      }
    }
    
    /*------------GET ALL TRANSPORT NAME START-------------*/
    $allTransport = [];
    $getTransportQ = "SELECT id, name, t_code FROM transport_master WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 ORDER BY name";
    $getTransportR = mysqli_query($conn, $getTransportQ);
    if($getTransportR && mysqli_num_rows($getTransportR) > 0){
        while($getTransportRow = mysqli_fetch_assoc($getTransportR)){
            $allTransport[] = $getTransportRow;
        }
    }
    /*------------GET ALL TRANSPORT NAME START-------------*/
    
?>
<?php 
if(isset($_POST['save']) || isset($_POST['saveAndNext'])){
  $user_id = $_SESSION['auth']['id'];
  $vouchar_date = date('Y-m-d',strtotime(str_replace('/','-',$_POST['vouchar_date'])));
  //$voucher_no = $_POST['voucher_no'];
  
  if(isset($_GET['id']) && $_GET['id'] != '' ){
        $voucher_no = (isset($_POST['voucher_no'])) ? $_POST['voucher_no'] : '';
    }else{
        $voucher_no = getpurchaseinvoiceno((isset($_POST['purchase_type'])) ? $_POST['purchase_type'] : '');
  }
  
  
  $city = $_POST['city'];
  $vendor = $_POST['vendor'];
  $doctor = (isset($_POST['doctor'])) ? $_POST['doctor'] : '';
  $statecode = $_POST['statecode'];
  $invoice_date = date('Y-m-d',strtotime(str_replace('/','-',$_POST['invoice_date'])));
  $invoice_no = $_POST['invoice_no'];
  $lr_no = $_POST['lr_no'];
  $lr_date = date('Y-m-d',strtotime(str_replace('/','-',$_POST['lr_date'])));
  $transporter_name = $_POST['transporter_name'];
  $purchase_type = $_POST['purchase_type'];
  $total_amount = $_POST['total_amount'];
  $courier = $_POST['courier'];
  $total_courier = (isset($_POST['total_courier']) && $_POST['total_courier'] != '') ? $_POST['total_courier'] : '';
  $total_tax = $_POST['total_tax'];
  $hidden_total_tax = $_POST['hidden-total_tax'];
  $total_igst = $_POST['total_igst'];
  $hidden_total_igst = $_POST['hidden_total_igst'];
  $total_cgst = $_POST['total_cgst'];
  $hidden_total_cgst = $_POST['hidden_total_cgst'];
  $total_sgst = $_POST['total_sgst'];
  $hidden_total_sgst = $_POST['hidden_total_sgst'];
  $minimal_radio = $_POST['minimal-radio'];
  $per_discount = $_POST['per_discount'];
  $rs_discount = $_POST['rs_discount'];
  $overall_value = $_POST['overall_value'];
  $note_details = $_POST['note_details'];
  $note_value = $_POST['note_value'];
  $purchase_amount = $_POST['purchase_amount'];
  $round_off = $_POST['round_off'];
  $total_total = $_POST['total_total'];
  $cancel = 1;
    
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $insert = "UPDATE purchase SET ";
  }else{
      
       // ---------------------------------Count voucher number------- start------@shreya---------------------//
    /*$new_voucher = getVoucherNoByType();
      if($new_voucher != ''){
         if($new_voucher == $_POST['voucher_no']){
               $voucher_no = $new_voucher;
         }if($new_voucher != $_POST['voucher_no']){
          $voucher_no = $new_voucher;
         }
      }else{
        $voucher_no = $_POST['voucher_no'];
      } */ 
    // ---------------------------------Count voucher number------- end------@shreya---------------------//
    
    
    //$insert = "INSERT INTO purchase SET ";
    $insert = "INSERT INTO purchase SET owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', financial_id='".$financial_id."', ";
  }

  $insert .= "vouchar_date ='".$vouchar_date."',
                     voucher_no ='".$voucher_no."',
                     city ='".$city."',
                     vendor ='".$vendor."',
                     doctor = '".$doctor."',
                     statecode ='".$statecode."',
                     invoice_date ='".$invoice_date."',
                     invoice_no ='".$invoice_no."',
                     lr_no ='".$lr_no."',
                     lr_date ='".$lr_date."',
                     transporter_name ='".$transporter_name."',
                     purchase_type ='".$purchase_type."',
                     total_amount ='".$total_amount."',
                     courier = '".$courier."',
                     total_courier = '".$total_courier."',
                     total_tax = '".$total_tax."',
                     hidden_total_tax = '".$hidden_total_tax."',
                     total_igst = '".$total_igst."',
                     hidden_total_igst = '".$hidden_total_igst."',
                     total_cgst = '".$total_cgst."',
                     hidden_total_cgst = '".$hidden_total_cgst."',
                     total_sgst = '".$total_sgst."',
                     hidden_total_sgst = '".$hidden_total_sgst."',
                     minimal_radio = '".$minimal_radio."',
                     per_discount = '".$per_discount."',
                     rs_discount = '".$rs_discount."',
                     overall_value = '".$overall_value."',
                     note_details = '".$note_details."',
                     note_value = '".$note_value."',
                     purchase_amount = '".$purchase_amount."',
                     round_off = '".$round_off."',
                     total_total = '".$total_total."',
                     cancel = '".$cancel."'";
                     
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
        $delete = "DELETE FROM purchase_details WHERE purchase_id='".$last_id."'";
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
        
        $expiry = "";
        if(isset($_POST["expiry"][$i]) && $_POST["expiry"][$i] != ''){
            $expiry = date('Y-m-d',strtotime(str_replace('/','-',$_POST["expiry"][$i])));
        }
        $productid = (isset($_POST["product_id"][$i])) ? $_POST["product_id"][$i] : '';
        if($productid != ''){
          $expiry1 = (isset($_POST["expiry"][$i]) && $_POST["expiry"][$i] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST["expiry"][$i]))) : '0000-00-00';
          $batch1 = $_POST["batch"][$i];
          $mrp1 = $_POST['mrp'][$i];
          $productqry = "select * from product_master where id = '".$productid."'";
          $productrun = mysqli_query($conn, $productqry);
          $productdata = mysqli_fetch_assoc($productrun);
          if($productdata['batch_no'] == '' || $productdata['ex_date'] == ''){
            $editproduct = "UPDATE `product_master` SET `batch_no`= '". $batch1."', `ex_date`= '".$expiry1."', `updated_at` = '".date('Y-m-d H:i:s')."', `updated_by` = '".$user_id."' WHERE id = '".$productid."'";
            $runproduct = mysqli_query($conn, $editproduct);
          }elseif($productdata['batch_no'] != $_POST['batch'][$i] || $productdata['mrp'] != $_POST["mrp"][$i] || $productdata['ex_date'] != $expiry1){
            $productquery = "select * from product_master where id = '".$productid."'";
            $run = mysqli_query($conn, $productquery);
            $data = mysqli_fetch_assoc($run);
            $product_code = getProductNo();
            $addproduct = "INSERT INTO `product_master`(`owner_id`, `admin_id`, `pharmacy_id`, `user_id`, `finance_year_id`,`product_code` ,`product_name`, `generic_name`, `mfg_company`, `schedule_cat`, `product_type`, `product_cat`, `sub_cat`, `hsn_code`, `batch_no`, `ex_date`, `opening_qty`, `opening_qty_godown`, `give_mrp`, `mrp`, `serial_no`, `igst`, `cgst`, `sgst`, `inward_rate`, `sale_rate_local`, `sale_rate_out`, `rack_no`, `self_no`, `box_no`, `company_code`, `opening_stock`, `unit`, `min_qty`, `max_qty`, `ratio`, `status`, `created_at`, `created_by`) VALUES ('".$data['owner_id']."', '".$data['admin_id']."', '".$data['pharmacy_id']."', '".$data['user_id']."', '".$data['finance_year_id']."','".$product_code."' ,'".$data['product_name']."', '".$data['generic_name']."', '".$data['mfg_company']."', '".$data['schedule_cat']."', '".$data['product_type']."', '".$data['product_cat']."', '".$data['sub_cat']."', '".$data['hsn_code']."', '".$batch1."', '".$expiry1."', '".$data['opening_qty']."', '".$data['opening_qty_godown']."', '".$data['give_mrp']."', '".$mrp1."', '".$data['serial_no']."', '".$data['igst']."', '".$data['cgst']."', '".$data['sgst']."', '".$data['inward_rate']."', '".$data['sale_rate_local']."', '".$data['sale_rate_out']."', '".$data['rack_no']."', '".$data['self_no']."', '".$data['box_no']."', '".$data['company_code']."', '".$data['opening_stock']."', '".$data['unit']."', '".$data['min_qty']."', '".$data['max_qty']."', '".$data['ratio']."', '".$data['status']."', '".date('Y-m-d H:i:s')."', '".$user_id."')";
            $addproductrun = mysqli_query($conn, $addproduct);
            $product = mysqli_insert_id($conn);
          }
        }

        
        $mrp = "";
        if(isset($_POST["mrp"][$i])){
            $mrp = $_POST["mrp"][$i];
        }

        $mfg_co = "";
        if(isset($_POST["mfg_co"][$i])){
            $mfg_co = $_POST["mfg_co"][$i];
        }

        $batch = "";
        if(isset($_POST["batch"][$i])){
            $batch = $_POST["batch"][$i];
        }

        $qty = "";
        if(isset($_POST["qty"][$i])){
            $qty = $_POST["qty"][$i];
        }

        $qty_ratio = 1;
        if(isset($_POST["qty_ratio"][$i])){
            $qty_ratio = ($_POST["qty_ratio"][$i] != '' && $_POST["qty_ratio"][$i] != 0) ? $_POST["qty_ratio"][$i] : 1;
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

        $f_igst = "";
        if(isset($_POST["f_igst"][$i])){
            $f_igst = $_POST["f_igst"][$i];
        }

        $f_cgst = "";
        if(isset($_POST["f_cgst"][$i])){
            $f_cgst = $_POST["f_cgst"][$i];
        }

        $f_sgst = "";
        if(isset($_POST["f_sgst"][$i])){
            $f_sgst = $_POST["f_sgst"][$i];
        }

        $poi_id = (isset($_POST["poi_id"][$i])) ? $_POST["poi_id"][$i] : '';
        if($poi_id != ''){
          $sql = "UPDATE orders SET status=0 WHERE id='".$poi_id."'";
          mysqli_query($conn,$sql);
        }

        $ins_product = "INSERT INTO `purchase_details` (`purchase_id`, `product_id`, `mrp`, `mfg_co`, `batch`, `expiry`,`qty`, `qty_ratio`, `free_qty`, `rate`, `discount`, `f_rate`,`ammout`,`f_igst`,`f_cgst`,`f_sgst`,`created`,`createdby`) VALUES ('".$last_id."','".$product_id."',  '".$mrp."', '".$mfg_co."', '".$batch."', '".$expiry."','".$qty."', '".$qty_ratio."', '".$free_qty."', '".$rate."', '".$discount."', '".$f_rate."', '".$ammout."', '".$f_igst."', '".$f_cgst."','".$f_sgst."','".date('Y-m-d H:i:s')."','".$user_id."')";
        //print_r($ins_product);exit;

        mysqli_query($conn,$ins_product);

      }
      if(isset($_GET['id']) && $_GET['id'] != ''){
        $_SESSION['msg']['success'] = 'Purchase Updated Successfully.';
      }else{
        $_SESSION['msg']['success'] = 'Purchase Added Successfully.';
      }
      
      if(isset($_POST['save'])){
          header('location:view-purchase.php');exit;
      }elseif(isset($_POST['saveAndNext'])){
          header('location:purchase.php');exit;
      }
    }else{
      if(isset($_GET['id']) && $_GET['id'] != ''){
        $_SESSION['msg']['fail'] = 'Purchase Updated Failed.';
      }else{
        $_SESSION['msg']['fail'] = 'Purchase Added Failed.';
      }
      //header('location:purchase.php');exit; 
    }

  /*$count = count($_POST['product']);*/

  /*for($i=1;$i<=$count;$i++){

  }*/

        /*$product_id = $_POST["product_id"];
        if(isset($product_id) && $product_id != ''){
          $expiry = date('Y-m-d',strtotime(str_replace('/','-',$_POST["expiry"])));
          $batch = $_POST["batch"];
          $productqry = "select * from product_master where id = '".$product_id."'";
          $productrun = mysqli_query($conn, $productqry);
          $productdata = mysqli_fetch_assoc($productrun);

          if($productdata['batch_no'] == ''){
            $editproduct = "UPDATE `product_master` SET `batch_no`= '". $batch."', `ex_date`= '".$expiry."' WHERE id = '".$product_id."'";
            $runproduct = mysqli_query($conn, $editproduct);

            if($runproduct){
              $_SESSION['msg']['success'] = 'Product Updated Successfully.';
            }else{
              $_SESSION['msg']['fail'] = 'Product Updated Fail.';
            }
          }
        } */


}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Purchase Bill</title>
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
   <link rel="stylesheet" href="vendors/iconfonts/simple-line-icon/css/simple-line-icons.css">
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
          /*function getVoucherNoByCash(){
            global $pharmacy_id;
            global $financial_id;
            global $conn;
            $voucher_no = '';

            $cashQuery = "SELECT voucher_no, invoice_no FROM purchase WHERE purchase_type = 'Cash' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
            $cashRes = mysqli_query($conn, $cashQuery);
            if($cashRes){
              $count = mysqli_num_rows($cashRes);
              if($count !== '' && $count !== 0){
                $row = mysqli_fetch_array($cashRes);
                $voucherno = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';

                if($voucherno != ''){
                  $vouchernoarr = explode('-',$voucherno);
                  $voucherno = $vouchernoarr[1];
                  $voucherno = $voucherno + 1;
                  $voucherno = sprintf("%05d", $voucherno);
                  $voucher_no = 'CV-'.$voucherno;
                }

              }else{
                $voucherno = sprintf("%05d", 1);
                $voucher_no = 'CV-'.$voucherno;
              }
            }
            return $voucher_no;
          }
          
        // ---------------------------------Count voucher number------- start------@shreya---------------------//
          function getVoucherNoByType() {
          global $pharmacy_id;
            global $financial_id;
            global $conn;
   
         if(isset($_POST['purchase_type'])){
            $purchase_type = $_POST['purchase_type']  ;
        $query = "SELECT voucher_no FROM purchase WHERE purchase_type = '".$purchase_type."' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
        $res = mysqli_query($conn, $query);
        if($res){
          $voucher_no = '';
          $code = ($purchase_type == 'Cash') ? 'CV-' : 'DV-';
          $count = mysqli_num_rows($res);
          if($count !== '' && $count !== 0){
            $row = mysqli_fetch_array($res);
            $voucherno = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';
           
            if($voucherno != ''){
              $vouchernoarr = explode('-',$voucherno);
              $voucherno = $vouchernoarr[1];
              $voucherno = $voucherno + 1;
              $voucherno = sprintf("%05d", $voucherno);
              $voucher_no = $code.''.$voucherno;

            }else{
              $voucherno = sprintf("%05d", 1);
              $voucher_no = $code.''.$voucherno;
            }

          }else{
            $voucherno = sprintf("%05d", 1);
            $voucher_no = $code.''.$voucherno;
          }
        }
     
     }   return $voucher_no;
    } */
    
    // ---------------------------------Count voucher number------- end------@shreya---------------------//
        ?>
    
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
    
        <!-- Right Sidebar -->
        <?php include "include/sidebar-right.php" ?>
        
       
       <!-- Left Navigation -->
        <?php include "include/sidebar-nav-left.php" ?>
        
        
      
      
      <div class="main-panel">
      
        <div class="content-wrapper">
         
        <span id="errormsg"></span>
        <form action="" method="POST" autocomplete="off">
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
                  
                  
                  <div class="form-group row">
                  
                      <div class="col-12 col-md-2">
                        <label for="exampleInputName1">Voucher Date<span class="text-danger">*</span></label>
                            <div class="input-group date datepicker">
                            <input type="text" name="vouchar_date" required="" data-parsley-errors-container="#error-vochar-date" class="form-control border" value="<?php if(isset($_GET['id'])){ echo date("d/m/Y",strtotime(str_replace("-","/",$purchase_data['vouchar_date'])));}else{echo date("d/m/Y");} ?>">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          <span id="error-vochar-date"></span>
                      </div>
                      
                      <div class="col-12 col-md-2">
                        <label for="exampleInputName1">Voucher No<span class="text-danger">*</span></label>
                        <?php 
                          //$voucherVal = getVoucherNoByCash();
                        ?>
                        <input type="text" required="" <?php if(isset($_GET['id'])){echo"readonly";} ?> class="form-control" name="voucher_no" id="voucher_no" value="<?php if(isset($_GET['id'])){ echo $purchase_data['voucher_no']; }else{echo getpurchaseinvoiceno('Debit'); } ?>" placeholder="Voucher No">
                      </div>
                      
                      <div class="col-12 col-md-2">
                        <label for="city">Select City<span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" style="width:100%" data-parsley-errors-container="#error-city" required="" name="city" id="city"> 
                                <option value="">Select City</option>
                                <?php 
                                  //$getAllCityQuery = "SELECT id, name FROM own_cities ct WHERE status='1' ORDER BY name";

                                  $getAllCityQuery = "SELECT DISTINCT lgr.city, ct.id as cityid, ct.name as cityname FROM  `ledger_master` lgr INNER JOIN `own_cities` ct ON lgr.city = ct.id WHERE lgr.group_id = '14' AND lgr.pharmacy_id = '".$pharmacy_id."' ORDER BY ct.name ASC";
                                  
                                  $getAllCityRes = mysqli_query($conn, $getAllCityQuery);
                                  if($getAllCityRes && mysqli_num_rows($getAllCityRes) > 0){
                                    while ($rowofcity = mysqli_fetch_array($getAllCityRes)) {
                                        
                                ?>
                                  <option <?php if(isset($purchase_data)){ if($purchase_data['city'] == $rowofcity['cityid']){echo "selected";} }?> value="<?php echo $rowofcity['cityid']; ?>"> <?php echo $rowofcity['cityname']; ?> </option>
                                <?php
                                    }
                                  }
                                ?>
                            </select>
                            <span id="error-city"></span>
                      </div>
                      
                      <div class="col-12 col-md-2">
                        <label for="vendor">Select Vendor<span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" data-parsley-errors-container="#error-vendor" required="" style="width:100%" name="vendor" id="vendor"> 
                            <option value="">Select Vendor</option>
                            <?php 
                            if(isset($purchase_data)){
                              $query = 'SELECT id, name FROM ledger_master WHERE city = '.$purchase_data['city'].' AND pharmacy_id = '.$pharmacy_id.' AND status=1 AND group_id=14 order by name';
                              $result = mysqli_query($conn,$query);
                              while ($rowofvender = mysqli_fetch_array($result)) {
                                ?>
                                <option <?php if($purchase_data['vendor'] == $rowofvender['id']){echo "selected";} ?> value="<?php echo $rowofvender['id']; ?>"><?php echo $rowofvender['name']; ?></option>
                                <?php
                              }
                            }
                            ?>
                            <option value="add_new_vendor">+ Add new Vendor</option>
                        </select>
                        <span id="error-vendor"></span>
                        <i class="fa fa-spin fa-refresh vendor-loader display-none" style="position: absolute;top: 40px;right: 40px;"></i>
                        <input type="hidden" value="<?php echo (isset($purchase_data['statecode'])) ? $purchase_data['statecode'] : ''; ?>" name="statecode" id="statecode">
                      </div>
                      
                        <!--<div class="col-12 col-md-2">
                            <label for="doctor">Select Doctor</label>
                            <select class="js-example-basic-single" style="width:100%" name="doctor" id="doctor"> 
                                <option value="">Select Doctor</option>
                                <?php //if(isset($purchaseDoctor) && !empty($purchaseDoctor)){ ?>
                                    <?php //foreach($purchaseDoctor as $key => $value){ ?>
                                        <option value="<?php //echo $value['id']; ?>" <?php //echo (isset($purchase_data['doctor']) && $purchase_data['doctor'] == $value['id']) ? 'selected' : ''; ?> ><?php //echo $value['name']; ?></option>
                                    <?php //} ?>
                                <?php //} ?>
                            </select>
                        </div>-->
                      
                        <!--<div class="col-12 col-md-2">-->
                        <!--    <button type="button" class="btn btn-primary mt-30" data-toggle="modal" data-target="#purchase-addvendormodel" data-whatever="@mdo"><i class="fa fa-plus"></i> Add New Vendor</button>-->
                        <!--</div>-->
                        <div class="col-12 col-md-2">
                            <button type="button" class="btn btn-primary pull-right mt-30" data-toggle="modal" data-target="#purchase-addproductmodel" data-whatever="@mdo"><i class="fa fa-plus"></i> Add New Product </button>
                        </div>
                    
                  </div> 
                  
                  
                    <div class="form-group row">
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Invoice Date<span class="text-danger">*</span></label>
                          <div class="input-group date <?php if(!isset($_GET['id'])){echo"datepicker";} ?> ">
                            <input type="text" <?php if(isset($_GET['id'])){echo"readonly";} ?> name="invoice_date" class="form-control border" value="<?php if(isset($_GET['id'])){ echo date("d/m/Y",strtotime(str_replace("-","/",$purchase_data['invoice_date']))); }else{echo date("d/m/Y");} ?>" required>
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                        </div>
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Invoice No.<span class="text-danger">*</span></label>
                          <input type="text" name="invoice_no" required class="form-control" id="exampleInputName1" value="<?php echo (isset($purchase_data['invoice_no'])) ? $purchase_data['invoice_no'] : ''; ?>" placeholder="Invoice No">
                        </div>
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">LR No</label>
                          <input type="text" name="lr_no" value="<?php echo (isset($purchase_data['lr_no'])) ? $purchase_data['lr_no'] : ''; ?>" class="form-control" id="exampleInputName1" placeholder="LR No">
                        </div>
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">LR Date</label>
                          <div class="input-group date datepicker">
                            <input type="text" name="lr_date" class="form-control border" value="<?php if(isset($_GET['id'])){ echo date("d/m/Y",strtotime(str_replace("-","/",$purchase_data['lr_date']))); }else{echo date("d/m/Y");}?>">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                        </div>
                        <div class="col-12 col-md-2">
                            <label for="transporter_name">Transporter Name</label>
                            <select class="js-example-basic-single" style="width:100%" name="transporter_name" id="transporter_name"> 
                                <option value="">Select Transport</option>
                                <?php if(isset($allTransport) && !empty($allTransport)){ ?>
                                    <?php foreach($allTransport as $key => $value){ ?>
                                        <option value="<?php echo $value['id']; ?>" <?php echo (isset($purchase_data['transporter_name']) && $purchase_data['transporter_name'] == $value['id']) ? 'selected' : ''; ?> ><?php echo $value['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                                 <option value="add_new_transporter">+ Add new Transporter</option>
                            </select>
                        </div>
                       <!-- <div class="col-12 col-md-2">-->
                       <!--     <button type="button" class="btn btn-primary mt-30" data-toggle="modal" data-target="#add-transport-model" data-whatever="@mdo" style="padding-right:7px;"><i class="fa fa-plus"></i> Add New Transport</button>-->
                       <!--</div>-->
                    </div>
                    <div class="form-group row">
                      <div class="col-12 col-md-2">
                        <label for="exampleInputName1">Purchase Type  </label>
                        <div class="row no-gutters">
                          <div class="col">
                              <div class="form-radio">
                              <label class="form-check-label">
                              <input type="radio" class="form-check-input purchase_type" name="purchase_type" value="Cash"  <?php if(isset($purchase_data) && $purchase_data['purchase_type'] == "Cash"){echo "checked";} ?>>
                             Cash
                              </label>
                              </div>
                          </div>

                          <div class="col">
                              <div class="form-radio">
                              <label class="form-check-label">
                              <input type="radio" class="form-check-input purchase_type" name="purchase_type" value="Debit" <?php if(!isset($_GET['id'])){echo "checked";} ?> <?php if(isset($purchase_data) && $purchase_data['purchase_type'] == "Debit"){echo "checked";} ?>>
                              Debit
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
                              <div class="add_show" style="display: none;">
                                <a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product pull-right add_show"><i class="fa fa-plus mr-0 ml-0"></i></a>
                              </div>
                            <table id="order-listing1" class="table">
                              <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th width="15%">Product</th>
                                    <th>MRP</th>
                                    <th>MFG. Co.</th>
                                    <th>Batch</th>
                                    <th>Expiry</th>
                                    <th>Qty.</th>
                                    <th>Free Qty</th>
                                    <th>Rate</th>
                                    <th>Discount</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                    <th>&nbsp;</th>
                                </tr>
                              </thead>
                              <tbody id="product-tbody">
                                <?php if(!isset($_GET['id'])){ ?>
                                <tr class="product-tr">
                                    <td>1</td>
                                    <td>
                                      <input type="text" placeholder="Product" class="tags form-control" required="" name="product[]">
                                      <input type="hidden" class="product-id" name="product_id[]">

                                      <small class="text-danger empty-message"></small>
                                    </td>
                                    <td>
                                      <input type="text" name="mrp[]" class="form-control mrp onlynumber" id="mrp" placeholder="MRP" autocomplete="off">
                                    </td>
                                    <td>
                                      <input type="text" name="mfg_co[]" class="form-control mfg_co" id="mfg_co" placeholder="MFG. Co." autocomplete="off">
                                    </td>
                                    <td>
                                      <input type="text" name="batch[]" class="form-control batch" id="batch" placeholder="Batch" autocomplete="off">
                                    </td>
                                    <td>
                                      <input type="text" name="expiry[]" class="form-control datepicker-ex expiry" style="width: 80px;" id="expiry" placeholder="Expiry" autocomplete="off">
                                      <small class="text-danger expired"></small>
                                    </td>
                                    <td>
                                      <input type="text" name="qty[]" class="form-control qty onlynumber" id="qty" placeholder="Qty." required>
                                      <input type="hidden" class="qty-value" name="qty_ratio[]" autocomplete="off">
                                    </td>
                                    <td>
                                      <input type="text" name="free_qty[]" class="form-control free_qty onlynumber" id="free_qty" placeholder="Free Qty" autocomplete="off">
                                    </td>
                                    <td>
                                      <input type="text" name="rate[]" class="form-control rate onlynumber" id="rate" placeholder="Rate" autocomplete="off">
                                    </td>
                                    <td>
                                      <input type="text" name="discount[]" class="form-control discount onlynumber" id="discount" placeholder="Discount" autocomplete="off">
                                    </td>
                                    <td>
                                      <input type="text" name=f_rate[] class="form-control f_rate onlynumber" id="f_rate" placeholder="Rate" autocomplete="off" required>
                                    </td>
                                    <td>
                                      <input type="text" name=ammout[] class="form-control ammout onlynumber" id="ammout" placeholder="Ammount" autocomplete="off">
                                      <input type="hidden" name="f_igst[]" class="f_igst">
                                      <input type="hidden" name="f_cgst[]" class="f_cgst">
                                      <input type="hidden" name="f_sgst[]" class="f_sgst">
                                    </td>
                                    <td><a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a>
                                    <a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product remove_last" style="display: none;"><i class="fa fa-close mr-0 ml-0"></i></a>
                                    </td>
                                </tr>
                              <?php }else{ ?>
                                <?php 
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
                                      <input type="text" placeholder="Product" class="tags form-control" value="<?php echo $rowp['product_name']; ?>" required="" name="product[]">
                                      <input type="hidden" class="product-id" value="<?php echo $value['product_id']; ?>" name="product_id[]">

                                      <small class="text-danger empty-message"></small>
                                    </td>
                                    <td>
                                      <input type="text" value="<?php echo $value['mrp']; ?>" name="mrp[]" class="form-control mrp priceOnly" id="mrp" placeholder="MRP" autocomplete="off">
                                    </td>
                                    <td>
                                      <input type="text" name="mfg_co[]" value="<?php echo $value['mfg_co']; ?>" class="form-control mfg_co" id="mfg_co" placeholder="MFG. Co." autocomplete="off">
                                    </td>
                                    <td>
                                      <input type="text" name="batch[]" value="<?php echo $value['batch']; ?>" class="form-control batch" id="batch" placeholder="Batch" autocomplete="off">
                                    </td>
                                    <td>
                                      <input type="text" name="expiry[]" value="<?php echo (isset($value['expiry']) && $value['expiry'] != '' && $value['expiry'] != '0000-00-00') ? date("d/m/Y",strtotime(str_replace("-","/",$value['expiry']))) : ''; ?>" class="form-control datepicker-ex expiry" style="width: 80px;" id="expiry" placeholder="Expiry" autocomplete="off">
                                        <small class="text-danger expired"></small>
                                    </td>
                                    <td>
                                      <input type="text" value="<?php echo $value['qty']; ?>" name="qty[]" class="form-control qty onlynumber" id="qty" placeholder="Qty." required>
                                      <input type="hidden" value="<?php echo $value['qty_ratio']; ?>" class="qty-value" name="qty_ratio[]" autocomplete="off">
                                    </td>
                                    <td>
                                      <input type="text" value="<?php echo $value['free_qty']; ?>" name="free_qty[]" class="form-control free_qty onlynumber" id="free_qty" placeholder="Free Qty" autocomplete="off">
                                    </td>
                                    <td>
                                      <input type="text" value="<?php echo $value['rate']; ?>" name="rate[]" class="form-control rate onlynumber" id="rate" placeholder="Rate" autocomplete="off">
                                    </td>
                                    <td>
                                      <input type="text" value="<?php echo $value['discount']; ?>" name="discount[]" class="form-control discount onlynumber" id="discount" placeholder="Discount" autocomplete="off">
                                    </td>
                                    <td>
                                      <input type="text" name=f_rate[] value="<?php echo $value['f_rate']; ?>" class="form-control f_rate onlynumber" id="f_rate" placeholder="Rate" autocomplete="off" required>
                                    </td>
                                    <td>
                                      <input type="text" value="<?php echo $value['ammout']; ?>" name=ammout[] class="form-control ammout onlynumber" id="ammout" placeholder="Ammount" autocomplete="off">
                                      <input type="hidden" value="<?php echo $value['f_igst']; ?>" name="f_igst[]" class="f_igst">
                                      <input type="hidden" value="<?php echo $value['f_cgst']; ?>" name="f_cgst[]" class="f_cgst">
                                      <input type="hidden" value="<?php echo $value['f_sgst']; ?>" name="f_sgst[]" class="f_sgst">
                                    </td>
                                    <td><a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a>
                                    <a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product "><i class="fa fa-close mr-0 ml-0"></i></a>
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
                                      <td align="right"><input class="form-control" type="text" name="total_amount" id="total_amount" value="<?php echo (isset($purchase_data['total_amount'])) ? $purchase_data['total_amount'] : ''; ?>" readonly="">
                                       
                                      </td>
                                    </tr>
                                    
                                   
                                    
                                     
                                    
                                     <tr>
                                      <td align="right">
                                       
                                       <div class="input-group">
                                          <input type="text" value="<?php echo (isset($purchase_data['per_discount'])) ? $purchase_data['per_discount'] : ''; ?>"  name="per_discount"  class="form-control f_discount priceOnly onlynumber" id="exampleInputName1" placeholder="%" style="display:inline-block;width:80px;">
                                          <div class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-percent"></i></span>
                                          </div>
                                        </div>
                                      </td>
                                      <td align="right">
                                          <div class="input-group">
                                          <input type="text" value="<?php echo (isset($purchase_data['rs_discount'])) ? $purchase_data['rs_discount'] : ''; ?>" name="rs_discount" class="form-control f_discount_rs priceOnly onlynumber" id="rs_dis" placeholder="Rs." style="display:inline-block;width:80px;">
                                          <div class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-rupee"></i></span>
                                          </div>
                                        </div>    
                                      </td>
                                      
                                    </tr>
                                    
                                    <tr>
                                      <td align="right">
                                        <select class="form-control" name="courier" id="courier_charge" style="width:250px;">
                                              <option value="">Freight/Courier Charge </option>
                                              <?php if(isset($purchaseCourierCharge) && !empty($purchaseCourierCharge)){ ?>
                                                <?php foreach($purchaseCourierCharge as $key => $value){ ?>
                                                    <option value="<?php echo $value; ?>" <?php echo (isset($purchase_data['courier']) && $purchase_data['courier'] == $value) ? 'selected' : ''; ?> ><?php echo $value; ?> %</option>
                                                <?php } ?> 
                                              <?php } ?>
                                          </select>
                                      </td>
                                      <td align="right"> <input type="text" name="total_courier" class="form-control" value="<?php echo (isset($purchase_data['total_courier'])) ? $purchase_data['total_courier'] : ''; ?>" id="total_courier"></td>
                                    </tr>
                                    
                                    <tr>
                                      <td align="right">
                                       Taxable Value
                                      </td>
                                      <td align="right"><input type="text" readonly="" name="overall_value" class="form-control" value="<?php echo (isset($purchase_data['overall_value'])) ? $purchase_data['overall_value'] : ''; ?>"  id="overall_value"></td>
                                    </tr>
                                    
                                    <tr>
                                      <td align="right">
                                        Total Tax (GST)
                                      </td>
                                      <td align="right">
                                        <input type="text" class="form-control" readonly="" name="total_tax" value="<?php echo (isset($purchase_data['total_tax'])) ? $purchase_data['total_tax'] : ''; ?>" id="total_tax">
                                        <input type="hidden" value="<?php echo (isset($purchase_data['hidden_total_tax'])) ? $purchase_data['hidden_total_tax'] : ''; ?>" id="hidden-total_tax" name="hidden-total_tax">
                                      </td>
                                    </tr>
                                    <tr>
                                      <td align="right">
                                        IGST
                                      </td>
                                      <td align="right">
                                        <input type="text" value="<?php echo (isset($purchase_data['total_igst'])) ? $purchase_data['total_igst'] : ''; ?>" class="form-control" readonly="" name="total_igst" id="total_igst">
                                        <input type="hidden" value="<?php echo (isset($purchase_data['hidden_total_igst'])) ? $purchase_data['hidden_total_igst'] : ''; ?>" id="hidden_total_igst" name="hidden_total_igst">
                                      </td>
                                    </tr>
                                    <tr>
                                      <td align="right">
                                        CGST
                                      </td>
                                      <td align="right">
                                        <input type="text" class="form-control" value="<?php echo (isset($purchase_data['total_cgst'])) ? $purchase_data['total_cgst'] : ''; ?>" readonly="" name="total_cgst" id="total_cgst">
                                        <input type="hidden" value="<?php echo (isset($purchase_data['hidden_total_cgst'])) ? $purchase_data['hidden_total_cgst'] : ''; ?>" id="hidden_total_cgst" name="hidden_total_cgst">
                                      </td>
                                    </tr>
                                    
                                    <tr>
                                      <td align="right">
                                        SGST
                                      </td>
                                      <td align="right">
                                        <input type="text" class="form-control" value="<?php echo (isset($purchase_data['total_sgst'])) ? $purchase_data['total_sgst'] : ''; ?>" readonly="" name="total_sgst" id="total_sgst">
                                        <input type="hidden" value="<?php echo (isset($purchase_data['hidden_total_sgst'])) ? $purchase_data['hidden_total_sgst'] : ''; ?>" id="hidden_total_sgst" name="hidden_total_sgst">
                                      </td>
                                    </tr>
                                    <input type="hidden" id="hidden_total">
                                    
                                    
                                   
                                    
                                     <tr>
                                      <td align="right">
                                        <select class="form-control note_details " name="note_details" id="note_details" style="width:250px;">
                                              <option <?php if(isset($purchase_data) && $purchase_data['note_details'] == "credit_note"){echo "selected";} ?> value="credit_note">Credit Note</option>
                                              <option <?php if(isset($purchase_data) && $purchase_data['note_details'] == "debit_note"){echo "selected";} ?> value="debit_note">Debit Note</option>
                                          </select>
                                      </td>
                                      <td align="right">
                                        <i class="fa fa-rupee"></i>&nbsp;
                                        <input type="text" name="note_value" class="form-control note_details onlynumber priceOnly" value="<?php echo (isset($purchase_data['note_value'])) ? $purchase_data['note_value'] : ''; ?>" id="note_value">
                                      </td>
                                    </tr>
                                    <tr style="background:#ececec;">
                                      <td align="right">
                                        Total Amount
                                      </td>
                                      <td align="right">
                                        <input type="text" value="<?php echo (isset($purchase_data['purchase_amount'])) ? $purchase_data['purchase_amount'] : ''; ?>" class="form-control" readonly="" name="purchase_amount" id="purchase_amount">
                                      </td>
                                    </tr>
                                    
                                    <tr style="background:#e0e0e0;">
                                      <td align="right">
                                        Round off
                                      </td>
                                      <td align="right">
                                        <input type="text" value="<?php echo (isset($purchase_data['round_off'])) ? $purchase_data['round_off'] : ''; ?>" class="form-control" readonly="" name="round_off" id="round_off">
                                      </td>
                                    </tr>
                                    
                                    <tr style="background:#0062ab;color:#fff;">
                                      <td align="right">
                                        <strong>NET VALUE</strong>
                                      </td>
                                      <td align="right">
                                       <i class="fa fa-rupee"></i>&nbsp;<input type="text" class="form-control" readonly="" value="<?php echo (isset($purchase_data['total_total'])) ? $purchase_data['total_total'] : ''; ?>" name="total_total" id="total_total">
                                      </td>
                                    </tr>
                                    
                                  </tbody>
                                </table>
                              </div>
                          </div>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <a href="view-purchase.php" class="btn btn-light pull-left">Back</a>
                      <button type="submit" name="save" class="btn btn-success mr-2 pull-right">Save</button>
                      <button type="submit" name="saveAndNext" class="btn btn-success mr-2 pull-right">Save & Next</button>
                      <!--<button type="submit" name="saveAndPrint" class="btn btn-success mr-2 pull-right">Save & Print</button>-->
                    </div>
                </div>
              </div>
            </div>
          </div>
        </form>
        </div>

        <!-- copy html kartik champaneriya 
           author : Kartik Champaneriya
           date   : 28-07-2018
        -->
        <div id="html-copy" style="display: none;">
          <table>
            <tr class="product-tr">
              <td>##SRNO##</td>
              <td>
                <input type="text" name="product[]" placeholder="Product" required="" class="tags form-control">
                <input type="hidden" class="product-id" name="product_id[]">
                <small class="text-danger empty-message"></small>
              </td>
              <td>
                <input type="text" name="mrp[]" class="form-control mrp onlynumber" id="mrp" placeholder="MRP" autocomplete="off">
              </td>
              <td>
                <input type="text" name="mfg_co[]" class="form-control mfg_co" id="mfg_co" placeholder="MFG. Co." autocomplete="off">
              </td>
              <td>
                <input type="text" name="batch[]" class="form-control batch" id="batch" placeholder="Batch" autocomplete="off">
              </td>
              <td>
                <input type="text" name="expiry[]" class="form-control datepicker-ex expiry" style="width: 80px;" id="expiry" placeholder="Expiry" autocomplete="off">
                <small class="text-danger expired"></small>
              </td>
              <td>
                <input type="text" name="qty[]" class="form-control qty onlynumber" id="qty" placeholder="Qty." autocomplete="off" required>
                <input type="hidden" class="qty-value" name="qty_ratio[]">
              </td>
              <td>
                <input type="text" name="free_qty[]" class="form-control free_qty onlynumber" id="free_qty" placeholder="Free Qty" autocomplete="off">
              </td>
              <td>
                <input type="text" name="rate[]" class="form-control rate onlynumber" id="rate" placeholder="Rate" autocomplete="off">
              </td>
              <td>
                <input type="text" name="discount[]" class="form-control discount onlynumber" id="discount" placeholder="Discount" autocomplete="off">
              </td>
              <td>
                <input type="text" name=f_rate[] class="form-control f_rate onlynumber" id="f_rate" placeholder="Rate" autocomplete="off" required>
              </td>
              <td>
                <input type="text" name=ammout[] class="form-control ammout onlynumber" id="ammout" placeholder="Ammount" autocomplete="off">
                <input type="hidden" name="f_igst[]" class="f_igst">
                <input type="hidden" name="f_cgst[]" class="f_cgst">
                <input type="hidden" name="f_sgst[]" class="f_sgst">
                <input type="hidden" name="poi_id[]" class="f_poi_id">
              </td>
              <td><a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a><a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product"><i class="fa fa-close mr-0 ml-0"></i></a></td>
          </tr><!-- End Row --> 
          </table>
        </div>
        <!-- copy html end kartik champaneriya -->

        
        <!-- partial:partials/_footer.php -->
        <?php include "include/footer.php" ?>
        <!-- partial -->
        
        <input type="hidden" name="cur_statecode" id="cur_statecode" value="<?php echo (isset($_SESSION['state_code'])) ? $_SESSION['state_code'] : ''; ?>" >
        
        <!-- Add new vendor Model -->
        <?php include('include/addvendormodel.php')?>
        
         <!-- Add new Product Model -->
        <?php include('include/addproductmodel.php');?>
        
        <!-- get last bill Model -->
        <?php include('popup/vendor-last-bill-model.php'); ?>
        
        <!-- Add transport Model -->
        <?php include('popup/add-transport-model.php'); ?>
        
        <!--ADD COMPANY MODEL-->
        <?php include "include/addcompanymodel.php"?>
        
        <!--ADD GST MODEL-->
        <?php include "include/addgstmodel.php"?>
        
        <!--ADD Unit MODEL-->
        <?php include "include/addunitmodel.php"?>

        <!-- Add Area Model -->
        <?php include "include/addarea-model.php"?>

        <!-- PURCHASE ORDER ITEM POPUP -->
        <div class="modal fade" id="poi-model" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true" style="display: none;">
          <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Select Purchase Order Item</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                  <div class="modal-body">
                    <span id="poi-error"></span>
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th><input type="checkbox" id="poi-checkbox-all"></th>
                          <th>Sr. No</th>
                          <th>Date</th>
                          <th>Order No</th>
                          <th>Product Name</th>
                          <th>Batch No.</th>
                          <th>Expiry</th>
                          <th>Generic Name</th>
                          <th>Manufacturer Name</th>
                          <th>Purchase Price</th>
                          <th>GST(%)</th>
                          <th>Unit/Strip/Packing</th>
                          <th>Quentity</th>
                        </tr>
                      </thead>
                      <tbody id="poi-body">
                        
                      </tbody>
                    </table>
                  </div>
                  <div class="modal-footer row">
                    <div class="col-md-12">
                      <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancel</button>
                      <button type="button" class="btn btn-success pull-right" id="btn-addpoi">Add</button>
                    </div>
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
  
  

  
  <!-- Hidden HTML for poi items start -->
  <div id="poi-tr-html" style="display: none;">
      <table>
        <tr>
          <td><input type="checkbox" name="item" class="poi-checkbox"></td>
          <td>##SRNO##</td>
          <td class="poi-date">##DATE##</td>
          <td class="poi-order">##ORDER##</td>
          <td class="poi-pname">##PRODUCTNAME##</td>
          <td class="poi-batch">##BATCH##</td>
          <td class="poi-expiry">##EXPIRY##</td>
          <td class="poi-gname">##GENERIC##</td>
          <td class="poi-mfg">##MFG##</td>
          <td class="poi-pprice">##PURCHASEPRICE##</td>
          <td class="poi-gst">##GST##</td>
          <td class="poi-unit">##UNIT##</td>
          <td class="poi-qty">##QTY##</td>
          <input type="hidden" class="poi-pid" value="##PRODUCTID##">
          <input type="hidden" class="poi-id" value="##POIID##">
        </tr>
      </table>
  </div>
  <!-- Hidden HTML for poi items end -->

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
  <script src="js/custom/onlyalphabet.js"></script>
  
  
  <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
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
 <script src="js/jquery-ui.js"></script>
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script>
  <script src="js/custom/purchase.js"></script>
  <script src="js/custom/product-gst-change.js"></script>
  
  <!-- script for custom validation -->
<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>

  
  
  <!-- End custom js for this page-->
</body>


</html>
