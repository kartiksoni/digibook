<?php $title = "Product Master"; ?>
<?php include('include/usertypecheck.php');
include('include/permission.php');
$p_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';

if(isset($_GET['delete'])){
    $delete = deleteproduct($_GET['delete']);
    if(!empty($delete)){
        if(isset($delete['status']) && $delete['status'] == 0){
            $_SESSION['msg']['fail'] = (isset($delete['message']) && $delete['message'] != '') ? $delete['message'] : 'Record Delete Fail! Try Again.';
        }else{
            $_SESSION['msg']['success'] = (isset($delete['message']) && $delete['message'] != '') ? $delete['message'] : 'Record Delete Succcfully.';
        }
    }else{
        $_SESSION['msg']['fail'] = "Record Delete Fail! Try Again.";
    }
    header('Location: view-product-master.php');exit;
}

if(isset($_GET['id'])){
  $id = $_GET['id'];
  $query = "SELECT * FROM `product_master`WHERE id ='".$_GET['id']."'";
  $result = mysqli_query($conn,$query);
  $data = mysqli_fetch_array($result);
}


if(isset($_POST['submit'])){
   /* echo"<pre>";
    print_r($_POST);exit;*/
  
  $user_id = $_SESSION['auth']['id'];
  $financial_year_id = $_SESSION['auth']['financial'];
  $product_code = getProductNo();
  $product_name = $_POST['product_name'];
  $generic_name = $_POST['generic_name'];
  $mfg_company = $_POST['mfg_company'];
   $bill_print_view = (isset($_POST['bill_print_view'])) ? $_POST['bill_print_view'] : '';
  $schedule_cat = $_POST['schedule_cat'];
  $product_type = $_POST['product_type'];
  $product_cat = $_POST['product_cat'];
  $sub_cat = $_POST['sub_cat'];
  $hsn_code = $_POST['hsn_code'];
  $batch_no = $_POST['batch_no'];

  $ex_date = (isset($_POST['ex_date']) && $_POST['ex_date'] != '') ? date("Y-m-d",strtotime(str_replace("/","-",$_POST['ex_date']))) : '';
  $opening_qty = $_POST['opening_qty'];
  $opening_qty_godown = $_POST['opening_qty_godown'];
  $give_mrp = $_POST['give_mrp'];
  $mrp = (isset($_POST['mrp']) && $_POST['mrp'] != '') ? $_POST['mrp'] : 0;
  $serial_no = $_POST['serial_no'];
  $gst_id = $_POST['gst_id'];
  $igst = $_POST['igst'];
  $cgst = $_POST['cgst'];
  $sgst = $_POST['sgst'];
  $inward_rate = $_POST['inward_rate'];
  $sale_rate_local = $_POST['sale_rate_local'];
  $sale_rate_out = $_POST['sale_rate_out'];
  $rack_no = $_POST['rack_no'];
  $self_no = $_POST['self_no'];
  $box_no = $_POST['box_no'];
  $company_code = $_POST['company_code'];
  $opening_stock = $_POST['opening_stock'];
  $unit = $_POST['unit'];
  $min_qty = $_POST['min_qty'];
  $max_qty = $_POST['max_qty'];
  $ratio = (isset($_POST['ratio']) && $_POST['ratio'] != '' && $_POST['ratio'] != 0) ? $_POST['ratio'] : 1;
  $status = $_POST['status'];
  $discount = $_POST['discount'];
  $discount_per = $_POST['discount_per'];

  $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : NULL;
  $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : NULL;
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;

  $insQry = "INSERT INTO `product_master` (`owner_id`, `admin_id`, `pharmacy_id`, `user_id`, `finance_year_id`,`product_code`,`product_name`, `generic_name`, `mfg_company`,`bill_print_view`, `schedule_cat`, `product_type`, `product_cat`, `sub_cat`, `hsn_code`, `batch_no`, `ex_date`, `opening_qty`, `opening_qty_godown`, `give_mrp`, `mrp`,`serial_no`,`gst_id`, `igst`, `cgst`, `sgst`, `inward_rate`, `sale_rate_local`, `sale_rate_out`, `rack_no`, `self_no`, `box_no`, `company_code`, `opening_stock`, `unit`, `min_qty`, `max_qty`,`ratio`,`status`,`discount`,`discount_per`, `created_at`, `created_by`) VALUES ('".$owner_id."', '".$admin_id."', '".$pharmacy_id."', '".$user_id."', '".$financial_year_id."','".$product_code."','".$product_name."', '".$generic_name."', '".$mfg_company."','".$bill_print_view."' ,'".$schedule_cat."', '".$product_type."', '".$product_cat."', '".$sub_cat."', '".$hsn_code."', '".$batch_no."', '".$ex_date."', '".$opening_qty."', '".$opening_qty_godown."', '".$give_mrp."', '".$mrp."','".$serial_no."','".$gst_id."','".$igst."', '".$cgst."', '".$sgst."', '".$inward_rate."', '".$sale_rate_local."', '".$sale_rate_out."', '".$rack_no."', '".$self_no."', '".$box_no."', '".$company_code."', '".$opening_stock."', '".$unit."', '".$min_qty."', '".$max_qty."','".$ratio."','".$status."','".$discount."','".$discount_per."','".date('Y-m-d H:i:s')."', '".$user_id."')";

  $queryInsert = mysqli_query($conn,$insQry);
  if($queryInsert){

    $_SESSION['msg']['success'] = "Product Added Successfully.";
    header('location:view-product-master.php');exit;

  }else{

    $_SESSION['msg']['fail'] = "Product Added Failed.";
    header('location:product-master.php');exit;

  }
}

if(isset($_POST['update'])){

  $user_id = $_SESSION['auth']['id'];
  $financial_year_id = $_SESSION['auth']['financial'];
  $product_code = $_POST['product_code'];
  $product_name = $_POST['product_name'];
  $generic_name = $_POST['generic_name'];
  $mfg_company = $_POST['mfg_company'];
  $bill_print_view = (isset($_POST['bill_print_view'])) ? $_POST['bill_print_view'] : '';
  $schedule_cat = $_POST['schedule_cat'];
  $product_type = $_POST['product_type'];
  $product_cat = $_POST['product_cat'];
  $sub_cat = $_POST['sub_cat'];
  $hsn_code = $_POST['hsn_code'];
  $batch_no = $_POST['batch_no'];
  $ex_date = (isset($_POST['ex_date']) && $_POST['ex_date'] != '') ? date("Y-m-d",strtotime(str_replace("/","-",$_POST['ex_date']))) : '';
  $opening_qty = $_POST['opening_qty'];
  $opening_qty_godown = $_POST['opening_qty_godown'];
  $give_mrp = $_POST['give_mrp'];
  $mrp = (isset($_POST['mrp']) && $_POST['mrp'] != '') ? $_POST['mrp'] : 0;
  $serial_no = $_POST['serial_no'];
  $gst_id = $_POST['gst_id'];
  $igst = $_POST['igst'];
  $cgst = $_POST['cgst'];
  $sgst = $_POST['sgst'];
  $inward_rate = $_POST['inward_rate'];
  $sale_rate_local = $_POST['sale_rate_local'];
  $sale_rate_out = $_POST['sale_rate_out'];
  $rack_no = $_POST['rack_no'];
  $self_no = $_POST['self_no'];
  $box_no = $_POST['box_no'];
  $company_code = $_POST['company_code'];
  $opening_stock = $_POST['opening_stock'];
  $unit = $_POST['unit'];
  $min_qty = $_POST['min_qty'];
  $max_qty = $_POST['max_qty'];
  $ratio = (isset($_POST['ratio']) && $_POST['ratio'] != '' && $_POST['ratio'] != 0) ? $_POST['ratio'] : 1;
  $status = $_POST['status'];
  $discount = $_POST['discount'];
  $discount_per = $_POST['discount_per'];

  $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : NULL;
  $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : NULL;
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;

  $query = "SELECT * FROM `product_master`WHERE id ='".$_GET['id']."'";
  $result = mysqli_query($conn,$query);
  $data = mysqli_fetch_array($result);
  
  if($data['give_mrp'] == $give_mrp){
    $updateQry = "UPDATE `product_master` SET `product_code`='".$product_code."',`product_name`='".$product_name."',`generic_name`='".$generic_name."',`mfg_company`='".$mfg_company."',`bill_print_view`='".$bill_print_view."',`schedule_cat`='".$schedule_cat."',`product_type`='".$product_type."',`product_cat`='".$product_cat."',`sub_cat`='".$sub_cat."',`hsn_code`='".$hsn_code."',`batch_no`='".$batch_no."',`opening_qty`='".$opening_qty."',`opening_qty_godown`='".$opening_qty_godown."',`give_mrp`='".$give_mrp."', `mrp` = '".$mrp."' ,`serial_no`='".$serial_no."',`gst_id`='".$gst_id."',`igst`='".$igst."',`cgst`='".$cgst."',`sgst`='".$sgst."',`inward_rate`='".$inward_rate."', `sale_rate_local`='".$sale_rate_local."',`sale_rate_out`='".$sale_rate_out."',`rack_no`='".$rack_no."',`self_no`='".$self_no."',`box_no`='".$box_no."',`company_code`='".$company_code."',`opening_stock`='".$opening_stock."',`unit`='".$unit."',`min_qty`='".$min_qty."',`max_qty`='".$max_qty."',`ratio`='".$ratio."',`status`='".$status."',`discount`='".$discount."', `discount_per`='".$discount_per."',`updated_at`='".date('Y-m-d H:i:s')."',`updated_by`='".$user_id."' WHERE id='".$_GET['id']."'";
    $queryUpdate = mysqli_query($conn,$updateQry);
    if($queryUpdate){
        $_SESSION['msg']['success'] = "Product Updated Successfully.";
        header('Location: view-product-master.php');exit;
        //header('location: view-product-master.php');exit;
    }else{
      $_SESSION['msg']['fail'] = "Product Updated Failed.";
        header('Location: view-product-master.php');exit;
      }
  }else{
      $product_code = getProductNo();
    $insQry = "INSERT INTO `product_master` (`owner_id`, `admin_id`, `pharmacy_id`, `user_id`, `finance_year_id`,`product_code`, `product_name`, `generic_name`, `mfg_company`, `schedule_cat`, `product_type`, `product_cat`, `sub_cat`, `hsn_code`, `opening_qty`, `opening_qty_godown`, `give_mrp`, `mrp`,`serial_no`, `igst`, `cgst`, `sgst`, `inward_rate`, `sale_rate_local`, `sale_rate_out`, `rack_no`, `self_no`, `box_no`, `company_code`, `opening_stock`, `unit`, `min_qty`, `max_qty`,`ratio`, `status`,`discount`,`discount_per`, `created_at`, `created_by`) VALUES ('".$owner_id."', '".$admin_id."', '".$pharmacy_id."', '".$user_id."', '".$financial_year_id."','".$product_code."', '".$product_name."', '".$generic_name."', '".$mfg_company."', '".$schedule_cat."', '".$product_type."', '".$product_cat."', '".$sub_cat."', '".$hsn_code."','".$opening_qty."', '".$opening_qty_godown."', '".$give_mrp."', '".$give_mrp."','".$serial_no."', '".$igst."', '".$cgst."', '".$sgst."', '".$inward_rate."', '".$sale_rate_local."', '".$sale_rate_out."', '".$rack_no."', '".$self_no."', '".$box_no."', '".$company_code."', '".$opening_stock."', '".$unit."', '".$min_qty."', '".$max_qty."','".$ratio."', '".$status."', '".$discount."','".$discount_per."','".date('Y-m-d H:i:s')."', '".$user_id."')";
      $queryInsert = mysqli_query($conn,$insQry);
      if($queryInsert){
        $_SESSION['msg']['success'] = "Duplicate Product Added Successfully.";
        header('Location: view-product-master.php');exit;
    }else{
      $_SESSION['msg']['fail'] = "Duplicate Product Added Failed.";
        header('Location: product-master.php');exit;
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
  <title>Digibooks | <?php echo (isset($_GET['id']) && $_GET['id'] != '') ? 'Edit' : 'Add'; ?> Product</title>
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
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
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
            
            <!-- Product Master Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Product Master</h4>
                    <!-- <a style="margin-top: -40px;" class="btn btn-info btn-rounded btn-fw pull-right" href="view-product-master.php">View</a> -->
                  <hr class="alert-dark">
                  <br>
                  <form class="forms-sample" method="post" autocomplete="off">
                  
                  <div class="form-group row">
                      
                      <div class="col-12 col-md-4" style="display:none;">
                        <label for="product_code">Product Code<span class="text-danger">*</span></label>
                        <input type="text" value="<?php echo (isset($data['product_code'])) ? $data['product_code'] : getProductNo(); ?>" readonly name="product_code" class="form-control" required id="product_code" placeholder="Product Code">
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="product_name">Product Name<span class="text-danger">*</span></label>
                        <input type="text" value="<?php echo (isset($data['product_name'])) ? $data['product_name'] : '';?>" name="product_name" class="form-control" id="product_name" required  placeholder="Product Name">
                      </div>
                          
                      <div class="col-12 col-md-4">
                        <label for="generic_name">Generic Name</label>
                      <input type="text" name="generic_name" value="<?php echo (isset($data['generic_name'])) ? $data['generic_name'] : '';?>" class="form-control" id="generic_name" placeholder="Generic Name ">
                      </div>
                      
                       <div class="col-12 col-md-4">
                        <label for="mfg_company">MFG. Company<span class="text-danger">*</span></label>
                        <input type="text" name="mfg_company" required="" value="<?php echo (isset($data['mfg_company'])) ? $data['mfg_company'] : '';?>" class="form-control" id="mfg_company" placeholder="MFG. Company">
                    </div>
                      
                  </div>
                    
                <div class="form-group row">
                     
                     <div class="col-12 col-md-4">
                              <label for="unit">Bill Print View</label>
                              <input type="text" value="<?php echo (isset($data['bill_print_view'])) ? $data['bill_print_view'] : '';?>" name="bill_print_view" class="form-control" id="unit" placeholder="Bill Print View" maxlength="5">
                     </div>
                     
                    <div class="col-12 col-md-4">
                        <label for="schedule_cat">Schedule Category</label>
                        <select class="js-example-basic-single" name="schedule_cat" style="width:100%">
                            <option value="">Select Schedule Category</option>
                            <?php 
                                $SheduleCategory = getSheduleCategory();
                                if(isset($SheduleCategory) && !empty($SheduleCategory)){
                                    foreach($SheduleCategory as $key => $value){
                            ?>
                                    <option value="<?php echo $value['id']; ?>" <?php echo (isset($data['schedule_cat']) && $data['schedule_cat'] == $value['id']) ? 'selected' : ''; ?> ><?php echo $value['name']; ?></option>
                            <?php
                                } 
                                    }
                            ?>
                        </select>
                  </div>
                      
                    <div class="col-12 col-md-3">
                    <label for="product_type">Product  Type<span class="text-danger">*</span></label>
                    <select name="product_type" required="" class="js-example-basic-single" style="width:100%" data-parsley-errors-container="#error-product-type" id="product_type"> 
                      <option value="">Select Product Type</option>
                      <?php 
                        $data['product_type'] = (isset($data['product_type'])) ? $data['product_type'] : '';
                        $productTypeQry = "SELECT * FROM `product_type` WHERE status='1' AND pharmacy_id = '".$p_id."'";
                        $Product = mysqli_query($conn,$productTypeQry);
                        while($product_type = mysqli_fetch_assoc($Product)){
                      ?>
                        <option <?php if($data['product_type'] == $product_type['id']){echo "selected";} ?> value="<?php echo $product_type['id']; ?>"><?php echo $product_type['product_type']; ?></option>
                      <?php } ?>
                    </select>
                    <span id="error-product-type"></span>
                  </div>
                  <div class="col-12 col-md-1">
                    <button type="button" data-target="#addproducttype-model" data-toggle="modal" class="btn btn-outline-primary btn-sm pull-right" style="margin-top: 30px;"><i class="mdi mdi-plus"></i></button>
                  </div>    
                    
                        
                </div>
                    
                   
                    
                    
                    <div class="form-group row">
                        
                        <div class="col-12 col-md-3">
                        <label for="product_cat">Product Category</label>
                            <select class="js-example-basic-single" style="width:100%" name="product_cat" id="product_cat"> 
                                <option value="">Select Product Category</option>
                                <?php 
                                $data['product_cat'] = (isset($data['product_cat'])) ? $data['product_cat'] : '';
                                $productCatQry = "SELECT * FROM `product_category` WHERE status='1' AND pharmacy_id = '".$p_id."'";
                                $Category = mysqli_query($conn,$productCatQry);
                                while($product_category = mysqli_fetch_assoc($Category)){
                                ?>
                                  <option <?php if($data['product_cat'] == $product_category['id']){echo "selected";} ?> value="<?php echo $product_category['id']; ?>"><?php echo $product_category['product_cat']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-1">
                          <button type="button" data-target="#addproductcategory-model" data-toggle="modal" class="btn btn-outline-primary btn-sm pull-right" style="margin-top: 30px;"><i class="mdi mdi-plus"></i></button>
                        </div>
                        
                        <div class="col-12 col-md-4">
                          <label for="sub_cat">Sub Category</label>
                          <select name="sub_cat" class="js-example-basic-single" style="width:100%" id="sub_cat">
                                <option value="">Select Sub Category</option>
                                <?php $data['sub_cat'] = (isset($data['sub_cat'])) ? $data['sub_cat'] : '';?>
                                <option <?php if($data['sub_cat'] =="Sub Cat1"){echo"selected";} ?> value="Sub Cat1">Sub Cat1</option>
                                <option <?php if($data['sub_cat'] =="Sub Cat2"){echo"selected";} ?> value="Sub Cat2">Sub Cat2</option>
                                <option <?php if($data['sub_cat'] =="Sub Cat3"){echo"selected";} ?> value="Sub Cat3">Sub Cat3</option>
                            </select>
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="hsn_code">HSN Code<span class="text-danger">*</span></label>
                        <input name="hsn_code" type="text" required="" value="<?php echo (isset($data['hsn_code'])) ? $data['hsn_code'] : '';?>" class="form-control" id="hsn_code" placeholder="HSN Code">
                        </div>
                        
                        
                        
                    </div>
                    
                    <div class="form-group row">
                        
                        
                        <div class="col-12 col-md-4">
                        <label for="batch_no">Batch No</label>
                        <input type="text" <?php if(isset($_REQUEST['id'])){echo"readonly";} ?> name="batch_no" value="<?php echo (isset($data['batch_no'])) ? $data['batch_no'] : '';?>" class="form-control" id="batch_no" placeholder="Batch No">
                        </div>
                        
                        <div class="col-12 col-md-4">
                            <label for="ex_date">Expiry Date</label>
                            <div class="input-group date datepicker">
                            <?php 
                                if(isset($_GET['id']) && $_GET['id'] != ''){
                                    $exdate_val = (isset($data['ex_date']) && $data['ex_date'] != '' && $data['ex_date'] != '0000-00-00') ? date("d/m/Y",strtotime(str_replace("-","/",$data['ex_date']))) : '';
                                }
                            ?>
                            <input name="ex_date" <?php if(isset($_REQUEST['id'])){echo"disabled";} ?> value="<?php echo (isset($exdate_val)) ? $exdate_val : ''; ?>" data-inputmask="'alias': 'date'" type="text" class="form-control border" placeholder="dd/mm/yyyy" >
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="opening_qty">Opening Qty</label>
                        <input type="text" <?php if(isset($_REQUEST['id'])){echo"readonly";} ?> value="<?php echo (isset($data['opening_qty'])) ? $data['opening_qty'] : '';?>" name="opening_qty" class="form-control opening_qty onlynumber" id="opening_qty" placeholder="Opening Qty">
                        </div>
                        
                         
                    
                    </div>
                    
                    <div class="form-group row">
                        
                        <div class="col-12 col-md-4">
                        <label for="opening_qty_godown">Opening Qty in Godown</label>
                        <input type="text" name="opening_qty_godown" value="<?php echo (isset($data['opening_qty_godown'])) ? $data['opening_qty_godown'] : '';?>" class="form-control onlynumber" id="opening_qty_godown" placeholder="Opening Qty in Godown">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="mrp">MRP</label>
                        <input type="text" value="<?php echo (isset($data['mrp'])) ? $data['mrp'] : '';?>" name="mrp" class="form-control onlynumber" id="mrp" placeholder="MRP">
                        </div>
                       
                        <div class="col-12 col-md-4">
                        <label for="give_mrp">Give a New MRP</label>
                        <input type="text" value="<?php echo (isset($data['give_mrp'])) ? $data['give_mrp'] : '';?>" name="give_mrp" class="form-control onlynumber" id="give_mrp" placeholder="Give a New MRP">
                        </div>

                        
                    
                    </div>
                    
                    <div class="form-group row">

                        <div class="col-12 col-md-4">
                          <label for="inward_rate">Inward Rate<span class="text-danger">*</span></label>
                          <input type="text" required="" name="inward_rate" value="<?php echo (isset($data['inward_rate'])) ? $data['inward_rate'] : '';?>" class="form-control onlynumber inward_rate" id="inward_rate" placeholder="INWARD Rate">
                        </div>
                        <!--<div class="col-12 col-md-4">-->
                        <!--  <label for="exampleInputName1">Sales Rate<span class="text-danger">*</span></label>-->
                        <!--    <div class="row no-gutters">-->
                        <!--        <div class="col-12 col-md-6">-->
                        <!--       <input type="text" value="<?php echo (isset($data['sale_rate_local'])) ? $data['sale_rate_local'] : '';?>" name="sale_rate_local" required="" class="form-control onlynumber" id="exampleInputName1" placeholder="Local">-->
                        <!--          </div>-->
                        <!--         <div class="col-12 col-md-6">-->
                        <!--          <input type="text" value="<?php echo (isset($data['sale_rate_out'])) ? $data['sale_rate_out'] : '';?>" name="sale_rate_out" required="" class="form-control onlynumber" id="exampleInputName1" placeholder="Out">-->
                        <!--         </div>-->
                        <!--      </div>-->
                        <!--</div>-->
                        <!--<div class="col-12 col-md-4">
                            <label for="rack_no">Rack No</label>
                            <input type="text" name="rack_no" value="<?php echo (isset($data['rack_no'])) ? $data['rack_no'] : '';?>" class="form-control" id="rack_no" placeholder="Rack No">
                        </div>!-->
                        
                        <div class="col-12 col-md-3">
                        <label for="company_code1">Company</label>
                        <select class="js-example-basic-single" id="company_code1" name="company_code" style="width:100%">
                            <option value="">Select Company</option>
                            <?php
                            $query = "SELECT * FROM `company_master` WHERE pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."'";
                            $result = mysqli_query($conn,$query);
                            while($row = mysqli_fetch_array($result)){
                                ?>
                            <option <?php if(isset($data['company_code']) && $data['company_code'] == $row['id']){echo "selected";} ?> value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <!--<input type="text" name="company_code" value="<?php //echo (isset($data['company_code'])) ? $data['company_code'] : '';?>" class="form-control" placeholder="Company Code">--> <!-- input id remove id="company_code"-->
                        <!--<small class="empty-message text-danger"></small>-->
                      </div>
                      <div class="col-12 col-md-1">
                        <button type="button" data-target="#addcompany-model" data-toggle="modal" class="btn btn-outline-primary btn-sm pull-right" style="margin-top: 30px;"><i class="mdi mdi-plus"></i></button>
                      </div>
                        
                         <div class="col-12 col-md-3">
                            <label for="gst_id">GST<span class="text-danger">*</span></label>
                            <select name="gst_id" required="" class="js-example-basic-single gst_id" id="gst_id" style="width:100%" data-parsley-errors-container="#error-gst-type"> 
                              <option value="">Select GST</option>
                              <?php 
                                $productTypeQry = "SELECT * FROM `gst_master` WHERE status='1' AND pharmacy_id = '".$p_id."' OR edit_status='1' ORDER BY id DESC";
                                $Product = mysqli_query($conn,$productTypeQry);
                                while($product_type = mysqli_fetch_assoc($Product)){
                              ?>
                                <option <?php if(isset($data['gst_id']) && $data['gst_id'] == $product_type['id']){echo "selected";} ?> value="<?php echo $product_type['id']; ?>"><?php echo $product_type['gst_name']; ?></option>
                              <?php } ?>
                            </select>
                            <span id="error-gst-type"></span>
                          </div>
                          <div class="col-12 col-md-1">
                            <button type="button" data-target="#addgst-model" data-toggle="modal" class="btn btn-outline-primary btn-sm pull-right" style="margin-top: 30px;"><i class="mdi mdi-plus"></i></button>
                         </div>

                    </div>

                    <div class="form-group row gst_show" style="<?php echo (isset($data['gst_id']) && $data['gst_id'] != '') ? '' : 'display:none;'; ?>">
                            <div class="col-12 col-md-4">
                                <label for="igst">IGST</label>
                                <input type="text" readonly name="igst"  value="<?php echo (isset($data['igst'])) ? $data['igst'] : '';?>" class="form-control onlynumber igst" id="igst" placeholder="IGST">
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="cgst">CGST</label>
                                <input type="text" readonly  name="cgst" value="<?php echo (isset($data['cgst'])) ? $data['cgst'] : '';?>" class="form-control onlynumber cgst" id="cgst" placeholder="CGST">
                            </div>
                          
                            <div class="col-12 col-md-4">
                                <label for="sgst">SGST</label>
                            <input type="text" readonly  name="sgst" value="<?php echo (isset($data['sgst'])) ? $data['sgst'] : '';?>" class="form-control onlynumber sgst" id="sgst" placeholder="SGST">
                          </div>
                          
                        </div>
                    
                    <div class="form-group row">
                        
                      <div class="col-12 col-md-4">
                        <label for="rack_no">Rack No</label>
                        <input type="text" name="rack_no" value="<?php echo (isset($data['rack_no'])) ? $data['rack_no'] : '';?>" class="form-control" id="rack_no" placeholder="Rack No">
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="self_no">Self No</label>
                        <input type="text" name="self_no" value="<?php echo (isset($data['self_no'])) ? $data['self_no'] : '';?>" class="form-control" id="self_no" placeholder="Self No">
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="box_no">Box No</label>
                        <input type="text" name="box_no" value="<?php echo (isset($data['box_no'])) ? $data['box_no'] : '';?>" class="form-control" id="box_no" placeholder="Box No">
                      </div>
                      
                      <!--<div class="col-12 col-md-3">
                        <label for="company_code1">Company</label>
                        <select class="js-example-basic-single" id="company_code1" name="company_code" style="width:100%">
                            <option value="">Select Company</option>
                            <?php
                            $query = "SELECT * FROM `company_master` WHERE pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."'";
                            $result = mysqli_query($conn,$query);
                            while($row = mysqli_fetch_array($result)){
                                ?>
                            <option <?php if(isset($data['company_code']) && $data['company_code'] == $row['id']){echo "selected";} ?> value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <!--<input type="text" name="company_code" value="<?php //echo (isset($data['company_code'])) ? $data['company_code'] : '';?>" class="form-control" placeholder="Company Code">--> <!-- input id remove id="company_code"-->
                        <!--<small class="empty-message text-danger"></small>-->
                      <!--</div>
                      <div class="col-12 col-md-1">
                        <button type="button" data-target="#addcompany-model" data-toggle="modal" class="btn btn-outline-primary btn-sm pull-right" style="margin-top: 30px;"><i class="mdi mdi-plus"></i></button>
                      </div>!--->
                    </div>
                    
                    <div class="form-group row">
                            
                      <div class="col-12 col-md-4">
                          <label for="opening_stock">Opening Stock Rs</label>
                          <input type="text" value="<?php echo (isset($data['opening_stock'])) ? $data['opening_stock'] : '';?>" name="opening_stock" readonly="" class="form-control onlynumber opening_stock" id="opening_stock" placeholder="Opening Stock Rs">
                      </div>
                            
                      <div class="col-12 col-md-3">
                          <label for="unit">Unit / Strip / Pack</label>
                            <select class="js-example-basic-single" id="unit" name="unit" style="width:100%">
                                <option value="">Select Unit</option>
                                <?php
                                $query = "SELECT * FROM `unit_master` WHERE pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."'";
                                $result = mysqli_query($conn,$query);
                                while($row = mysqli_fetch_array($result)){
                                    ?>
                                <option <?php if(isset($data['unit']) && $data['unit'] == $row['id']){echo "selected";} ?> value="<?php echo $row['id']; ?>"><?php echo $row['unit_name']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                      </div>
                      <div class="col-12 col-md-1">
                            <button type="button" data-target="#addunit-model" data-toggle="modal" class="btn btn-outline-primary btn-sm pull-right" style="margin-top: 30px;"><i class="mdi mdi-plus"></i></button>
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="min_qty">Min Qty.</label>
                        <input type="text" value="<?php echo (isset($data['min_qty'])) ? $data['min_qty'] : '';?>" name="min_qty" class="form-control onlynumber" id="min_qty" placeholder="Min Qty.">
                      </div>
                      
                    </div>
                    
                    <div class="form-group row">
                        
                      <div class="col-12 col-md-4">
                          <label for="ratio">Ratio<span class="text-danger">*</span></label>
                          <input type="text" required="" value="<?php echo (isset($data['ratio'])) ? $data['ratio'] : '1';?>" name="ratio" class="form-control onlynumber" id="Ratio" placeholder="Ratio.">
                      </div>
                       
                      <div class="col-12 col-md-4">
                        <label for="max_qty">Max Qty.</label>
                        <input type="text" value="<?php echo (isset($data['max_qty'])) ? $data['max_qty'] : '';?>" name="max_qty" class="form-control onlynumber" id="max_qty" placeholder="Max Qty.">
                      </div>
                      
                      <div class="col-12 col-md-4">
                          <label for="status">Status</label>
                          <?php $data['status'] = (isset($data['status'])) ? $data['status'] : '';?>
                        
                          <div class="row no-gutters">
                          
                              <div class="col">
                                <div class="form-radio">
                                <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" checked <?php if(isset($_GET['id'])){if($data['status'] == "1"){echo "checked";}  }else{echo"checked";} ?>>
                                Active
                                </label>
                                </div>
                              </div>
                              
                              <div class="col">
                                <div class="form-radio">
                                <label class="form-check-label">
                                <input type="radio" <?php if($data['status'] == "0"){echo "checked";} ?> class="form-check-input" name="status" id="optionsRadios2" value="0">
                                Deactive
                                </label>
                                </div>
                              </div>
                          
                          </div>
                        </div>
                         
                    </div>
                    
                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                          <label for="discount">Discount</label>
                          <div class="row no-gutters">
                          
                              <div class="col">
                                <div class="form-radio">
                                <label class="form-check-label">
                                <input type="radio" class="form-check-input" onclick="dis_yes();" name="discount" id="options1" value="1" <?php if(isset($data['discount']) && $data['discount'] == "1"){echo "checked";} ?>>
                                Yes
                                </label>
                                </div>
                              </div>
                              
                              <div class="col">
                                <div class="form-radio">
                                <label class="form-check-label">
                                <input type="radio"  class="form-check-input" name="discount" onclick="dis_no();" id="options2" value="0" <?php if(isset($_GET['id'])){if(isset($data['discount']) && $data['discount'] == "0"){echo "checked";}  } else{echo "checked";} ?>>
                                No
                                </label>
                                </div>
                              </div>
                          
                          </div>
                        </div>

                         <div class="col-12 col-md-4" id="per">
                          <label for="discount_per">Discount(%)<span class="text-danger"></span></label>
                          <input type="text" required value="<?php echo (isset($data['discount_per'])) ? $data['discount_per'] : '';?>"
                           name="discount_per" class="form-control onlynumber" id="discount_per" placeholder="%">
                         </div>
                       </div>
                    
                    <br>
                    <a href="view-product-master.php" class="btn btn-light pull-left">Back</a>
                    <?php 
                    if(isset($_GET['id'])){
                      ?>
                    <button type="submit" name="update" id="btn-update" class="btn btn-success mr-2 pull-right">Update</button>
                      <?php
                    }else{
                    ?>
                    <button type="submit" name="submit" class="btn btn-success mr-2 pull-right">Submit</button>
                  <?php } ?>
                    
                    
                  </form>
                </div>
              </div>
            </div>
            
         
       
            
          </div>
        </div>
        <!-- content-wrapper ends -->
        
        <!-- partial:partials/_footer.php -->
        <?php include "include/footer.php"?>
        <!-- partial -->

        <!-- Add Company model -->
        <?php include "include/addcompanymodel.php"?>
        <!-- Add GST Model -->
        <?php include "include/addgstmodel.php"?>
        <!--  Add Unit Model -->
        <?php include "include/addunitmodel.php"?>
        <!--  Add Product Type Model -->
        <?php include "include/addproducttype.php"; ?>
        <!--  Add Product Category Model -->
        <?php include "include/addproductcategory.php"; ?>
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
  
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>

  <script src="js/parsley.min.js"></script>
    <script type="text/javascript">
      $('form').parsley();
    </script>
  
 <script>
    $('.datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy',
      autoclose: true,
    });
 </script>
  <!-- Custom js for this page-->
  <script src="js/custom/onlynumber.js"></script>
  <script src="js/custom/product_master.js"></script>
  <script src="js/jquery-ui.js"></script>
  
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <?php 
  if(isset($_GET['type']) && $_GET['type'] == "view"){
      
      ?>
      <script>
          $('input[type="text"]').prop('readonly', true);
          $("input[type='radio']").attr('disabled',true);
          $('select').prop('disabled', 'disabled');
          $('#btn-update').hide();
      </script>
      <?php 
  }
  ?>
  
</body>
</html>
