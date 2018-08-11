<?php include('include/usertypecheck.php');
if(isset($_GET['id'])){
  $id = $_GET['id'];
  $query = "SELECT * FROM `product_master`WHERE id ='".$_GET['id']."'";
  $result = mysqli_query($conn,$query);
  $data = mysqli_fetch_array($result);
}


if(isset($_POST['submit'])){
  $user_id = $_SESSION['auth']['id'];
  $financial_year_id = "0";
  $product_name = $_POST['product_name'];
  $generic_name = $_POST['generic_name'];
  $mfg_company = $_POST['mfg_company'];
  $schedule_cat = $_POST['schedule_cat'];
  $product_type = $_POST['product_type'];
  $product_cat = $_POST['product_cat'];
  $sub_cat = $_POST['sub_cat'];
  $hsn_code = $_POST['hsn_code'];
  $batch_no = $_POST['batch_no'];

  $ex_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST['ex_date'])));
  $opening_qty = $_POST['opening_qty'];
  $opening_qty_godown = $_POST['opening_qty_godown'];
  $give_mrp = $_POST['give_mrp'];
  $mrp = (isset($_POST['mrp']) && $_POST['mrp'] != '') ? $_POST['mrp'] : 0;
  $serial_no = $_POST['serial_no'];
  $igst = $_POST['igst'];
  $cgst = $_POST['cgst'];
  $sgst = $_POST['sgst'];
  $inward_rate = $_POST['inward_rate'];
  $distributor_rate = $_POST['distributor_rate'];
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
  $ratio = $_POST['ratio'];
  $status = $_POST['status'];

  $insQry = "INSERT INTO `product_master` (`user_id`, `finance_year_id`, `product_name`, `generic_name`, `mfg_company`, `schedule_cat`, `product_type`, `product_cat`, `sub_cat`, `hsn_code`, `batch_no`, `ex_date`, `opening_qty`, `opening_qty_godown`, `give_mrp`, `mrp`,`serial_no`, `igst`, `cgst`, `sgst`, `inward_rate`, `distributor_rate`, `sale_rate_local`, `sale_rate_out`, `rack_no`, `self_no`, `box_no`, `company_code`, `opening_stock`, `unit`, `min_qty`, `max_qty`,`ratio`,`status`, `created_at`, `created_by`) VALUES ('".$user_id."', '".$financial_year_id."', '".$product_name."', '".$generic_name."', '".$mfg_company."', '".$schedule_cat."', '".$product_type."', '".$product_cat."', '".$sub_cat."', '".$hsn_code."', '".$batch_no."', '".$ex_date."', '".$opening_qty."', '".$opening_qty_godown."', '".$give_mrp."', '".$mrp."','".$serial_no."', '".$igst."', '".$cgst."', '".$sgst."', '".$inward_rate."', '".$distributor_rate."', '".$sale_rate_local."', '".$sale_rate_out."', '".$rack_no."', '".$self_no."', '".$box_no."', '".$company_code."', '".$opening_stock."', '".$unit."', '".$min_qty."', '".$max_qty."','".$ratio."','".$status."', '".date('Y-m-d H:i:s')."', '".$user_id."')";
  $queryInsert = mysqli_query($conn,$insQry);
  if($queryInsert){

    $_SESSION['msg']['success'] = "Product Added Successfully.";
    header('location:product-master.php');exit;

  }else{

    $_SESSION['msg']['fail'] = "Product Added Failed.";
    header('location:product-master.php');exit;

  }
}

if(isset($_POST['update'])){

  $user_id = $_SESSION['auth']['id'];
  $financial_year_id = "0";
  $product_name = $_POST['product_name'];
  $generic_name = $_POST['generic_name'];
  $mfg_company = $_POST['mfg_company'];
  $schedule_cat = $_POST['schedule_cat'];
  $product_type = $_POST['product_type'];
  $product_cat = $_POST['product_cat'];
  $sub_cat = $_POST['sub_cat'];
  $hsn_code = $_POST['hsn_code'];
  $batch_no = $_POST['batch_no'];
  $ex_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST['ex_date'])));
  $opening_qty = $_POST['opening_qty'];
  $opening_qty_godown = $_POST['opening_qty_godown'];
  $give_mrp = $_POST['give_mrp'];
  $mrp = (isset($_POST['mrp']) && $_POST['mrp'] != '') ? $_POST['mrp'] : 0;
  $serial_no = $_POST['serial_no'];
  $igst = $_POST['igst'];
  $cgst = $_POST['cgst'];
  $sgst = $_POST['sgst'];
  $inward_rate = $_POST['inward_rate'];
  $distributor_rate = $_POST['distributor_rate'];
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
  $ratio = $_POST['ratio'];
  $status = $_POST['status'];

  $query = "SELECT * FROM `product_master`WHERE id ='".$_GET['id']."'";
  $result = mysqli_query($conn,$query);
  $data = mysqli_fetch_array($result);
  
  if($data['give_mrp'] == $give_mrp){
    $updateQry = "UPDATE `product_master` SET `product_name`='".$product_name."',`generic_name`='".$generic_name."',`mfg_company`='".$mfg_company."',`schedule_cat`='".$schedule_cat."',`product_type`='".$product_type."',`product_cat`='".$product_cat."',`sub_cat`='".$sub_cat."',`hsn_code`='".$hsn_code."',`batch_no`='".$batch_no."',`ex_date`='".$ex_date."',`opening_qty`='".$opening_qty."',`opening_qty_godown`='".$opening_qty_godown."',`give_mrp`='".$give_mrp."', `mrp` = '".$mrp."' ,`serial_no`='".$serial_no."',`igst`='".$igst."',`cgst`='".$cgst."',`sgst`='".$sgst."',`inward_rate`='".$inward_rate."',`distributor_rate`='".$distributor_rate."',`sale_rate_local`='".$sale_rate_local."',`sale_rate_out`='".$sale_rate_out."',`rack_no`='".$rack_no."',`self_no`='".$self_no."',`box_no`='".$box_no."',`company_code`='".$company_code."',`opening_stock`='".$opening_stock."',`unit`='".$unit."',`min_qty`='".$min_qty."',`max_qty`='".$max_qty."',`ratio`='".$ratio."',`status`='".$status."',`updated_at`='".date('Y-m-d H:i:s')."',`updated_by`='".$user_id."' WHERE id='".$_GET['id']."'";
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
    $insQry = "INSERT INTO `product_master` (`user_id`, `finance_year_id`, `product_name`, `generic_name`, `mfg_company`, `schedule_cat`, `product_type`, `product_cat`, `sub_cat`, `hsn_code`, `opening_qty`, `opening_qty_godown`, `give_mrp`, `mrp`,`serial_no`, `igst`, `cgst`, `sgst`, `inward_rate`, `distributor_rate`, `sale_rate_local`, `sale_rate_out`, `rack_no`, `self_no`, `box_no`, `company_code`, `opening_stock`, `unit`, `min_qty`, `max_qty`,`ratio`, `created_at`, `created_by`) VALUES ('".$user_id."', '".$financial_year_id."', '".$product_name."', '".$generic_name."', '".$mfg_company."', '".$schedule_cat."', '".$product_type."', '".$product_cat."', '".$sub_cat."', '".$hsn_code."','".$opening_qty."', '".$opening_qty_godown."', '".$give_mrp."', '".$mrp."','".$serial_no."', '".$igst."', '".$cgst."', '".$sgst."', '".$inward_rate."', '".$distributor_rate."', '".$sale_rate_local."', '".$sale_rate_out."', '".$rack_no."', '".$self_no."', '".$box_no."', '".$company_code."', '".$opening_stock."', '".$unit."', '".$min_qty."', '".$max_qty."','".$ratio."', '".date('Y-m-d H:i:s')."', '".$user_id."')";
      $queryInsert = mysqli_query($conn,$insQry);
      if($queryInsert){
        $_SESSION['msg']['success'] = "Duplicate Product Added Successfully.";
        header('Location: view-product-master.php');exit;
    }else{
      $_SESSION['msg']['fail'] = "Duplicate Product Added Failed.";
        header('Location: view-product-master.php');exit;
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
          <?php include('include/flash.php'); ?>
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
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Product Name<span class="text-danger">*</span></label>
                        <input type="text" value="<?php echo (isset($data['product_name'])) ? $data['product_name'] : '';?>" name="product_name" class="form-control" required id="exampleInputName1" placeholder="Product Name">
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Generic Name <span class="text-danger">*</span></label>
                      <input type="text" name="generic_name" required="" value="<?php echo (isset($data['generic_name'])) ? $data['generic_name'] : '';?>" class="form-control" id="exampleInputName1" placeholder="Generic Name ">
                      </div>
                      
                       <div class="col-12 col-md-4">
                        <label for="exampleInputName1">MFG. Company<span class="text-danger">*</span></label>
                        <input type="text" name="mfg_company" required="" value="<?php echo (isset($data['mfg_company'])) ? $data['mfg_company'] : '';?>" class="form-control" id="exampleInputName1" placeholder="MFG. Company">
                      </div>
                  </div>
                    
                  <div class="form-group row">
                     
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Schedule Category</label>
                        <select class="js-example-basic-single" name="schedule_cat" style="width:100%">
                        <?php $data['schedule_cat'] = (isset($data['schedule_cat'])) ? $data['schedule_cat'] : '';?>
                            <option value="">Select Schedule Category</option>
                            <option <?php if($data['schedule_cat'] == "Schedule1"){echo "selected";} ?> value="X">X</option>
                            <option <?php if($data['schedule_cat'] == "Schedule2"){echo "selected";} ?> value="H">H</option>
                        </select>
                  </div>
                      
                  <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Product  Type<span class="text-danger">*</span></label>
                    <select name="product_type" required="" class="js-example-basic-single" style="width:100%"> 
                      <option value="">Select Product Type</option>
                      <?php $data['product_type'] = (isset($data['product_type'])) ? $data['product_type'] : ''; 
                      $productTypeQry = "SELECT * FROM `product_type` WHERE status='1'";
                      $Product = mysqli_query($conn,$productTypeQry);
                      while($product_type = mysqli_fetch_assoc($Product)){
                      ?>
                        <option <?php if($data['product_type'] == $product_type['id']){echo "selected";} ?> value="<?php echo $product_type['id']; ?>"><?php echo $product_type['product_type']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                      
                   <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Product Category<span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" required="" style="width:100%" name="product_cat"> 
                            <option value="">Select Product Category</option>
                            <?php 
                            $data['product_cat'] = (isset($data['product_cat'])) ? $data['product_cat'] : '';
                            $productCatQry = "SELECT * FROM `product_category` WHERE status='1'";
                            $Category = mysqli_query($conn,$productCatQry);
                            while($product_category = mysqli_fetch_assoc($Category)){
                            ?>
                              <option <?php if($data['product_cat'] == $product_category['id']){echo "selected";} ?> value="<?php echo $product_category['id']; ?>"><?php echo $product_category['product_cat']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                        
                    </div>
                    
                   
                    
                    
                    <div class="form-group row">
                        
                        <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Sub Category</label>
                          <select name="sub_cat" class="js-example-basic-single" style="width:100%">
                                <option value="">Select Sub Category</option>
                                <?php $data['sub_cat'] = (isset($data['sub_cat'])) ? $data['sub_cat'] : '';?>
                                <option <?php if($data['sub_cat'] =="Sub Cat1"){echo"selected";} ?> value="Sub Cat1">Sub Cat1</option>
                                <option <?php if($data['sub_cat'] =="Sub Cat2"){echo"selected";} ?> value="Sub Cat2">Sub Cat2</option>
                                <option <?php if($data['sub_cat'] =="Sub Cat3"){echo"selected";} ?> value="Sub Cat3">Sub Cat3</option>
                            </select>
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">HSN Code<span class="text-danger">*</span></label>
                        <input name="hsn_code" type="text" required="" value="<?php echo (isset($data['hsn_code'])) ? $data['hsn_code'] : '';?>" class="form-control" id="exampleInputName1" placeholder="HSN Code">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Batch No</label>
                        <input type="text" name="batch_no" value="<?php echo (isset($data['batch_no'])) ? $data['batch_no'] : '';?>" class="form-control" id="exampleInputName1" placeholder="Batch No">
                        </div>
                        
                    </div>
                    
                    <div class="form-group row">
                        
                        
                        <div class="col-12 col-md-4">
                          <label for="serial_no">Serial No</label>
                          <input type="text" value="<?php echo (isset($data['serial_no'])) ? $data['serial_no'] : '';?>" name="serial_no" class="form-control" id="serial_no" placeholder="Serial No">
                        </div>
                        
                        <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Expiry Date</label>
                            <div class="input-group date datepicker">
                            <input name="ex_date" value="<?php echo (isset($data['ex_date']) && $data['ex_date'] != '') ? date("d/m/Y",strtotime(str_replace("-","/",$data['ex_date']))) : ''; ?>" type="text" class="form-control border" placeholder="dd/mm/yyyy" >
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Opening Qty<span class="text-danger">*</span></label>
                        <input type="text" required="" value="<?php echo (isset($data['opening_qty'])) ? $data['opening_qty'] : '';?>" name="opening_qty" class="form-control" id="exampleInputName1" placeholder="Opening Qty">
                        </div>
                        
                         
                    
                    </div>
                    
                    <div class="form-group row">
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Opening Qty in Godown<span class="text-danger">*</span></label>
                        <input type="text" required="" name="opening_qty_godown" value="<?php echo (isset($data['opening_qty_godown'])) ? $data['opening_qty_godown'] : '';?>" class="form-control" id="exampleInputName1" placeholder="Opening Qty in Godown">
                        </div>
                        
                       
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Give a New MRP</label>
                        <input type="text" value="<?php echo (isset($data['give_mrp'])) ? $data['give_mrp'] : '';?>" name="give_mrp" class="form-control onlynumber" id="exampleInputName1" placeholder="Give a New MRP">
                        </div>

                        <div class="col-12 col-md-4">
                        <label for="mrp">MRP</label>
                        <input type="text" value="<?php echo (isset($data['mrp'])) ? $data['mrp'] : '';?>" name="mrp" class="form-control onlynumber" id="mrp" placeholder="MRP">
                        </div>
                    
                    </div>
                    
                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">IGST</label>
                        <input type="text" name="igst"  value="<?php echo (isset($data['igst'])) ? $data['igst'] : '';?>" class="form-control onlynumber" id="exampleInputName1" placeholder="IGST">
                        </div>

                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">CGST</label>
                        <input type="text"  name="cgst" value="<?php echo (isset($data['cgst'])) ? $data['cgst'] : '';?>" class="form-control onlynumber" id="exampleInputName1" placeholder="CGST">
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">SGST</label>
                      <input type="text"  name="sgst" value="<?php echo (isset($data['sgst'])) ? $data['sgst'] : '';?>" class="form-control onlynumber" id="exampleInputName1" placeholder="SGST">
                      </div>
                    </div>
                    
                    
                    <div class="form-group row">

                        <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Inward Rate<span class="text-danger">*</span></label>
                        <input type="text" required="" name="inward_rate" value="<?php echo (isset($data['inward_rate'])) ? $data['inward_rate'] : '';?>" class="form-control onlynumber" id="exampleInputName1" placeholder="INWARD Rate">
                        </div>
                      
                        <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Distributor Rate<span class="text-danger">*</span></label>
                          <input type="text" required="" value="<?php echo (isset($data['distributor_rate'])) ? $data['distributor_rate'] : '';?>" name="distributor_rate" class="form-control onlynumber" id="exampleInputName1" placeholder="Distributor Rate">
                        </div>
                        <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Sales Rate<span class="text-danger">*</span></label>
                            <div class="row no-gutters">
                                <div class="col-12 col-md-6">
                               <input type="text" value="<?php echo (isset($data['sale_rate_local'])) ? $data['sale_rate_local'] : '';?>" name="sale_rate_local" required="" class="form-control onlynumber" id="exampleInputName1" placeholder="Local">
                                  </div>
                                 <div class="col-12 col-md-6">
                                  <input type="text" value="<?php echo (isset($data['sale_rate_out'])) ? $data['sale_rate_out'] : '';?>" name="sale_rate_out" required="" class="form-control onlynumber" id="exampleInputName1" placeholder="Out">
                                 </div>
                              </div>
                        </div>
                     
                    </div>
                    
                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Rack No<span class="text-danger">*</span></label>
                        <input type="text" required="" name="rack_no" value="<?php echo (isset($data['rack_no'])) ? $data['rack_no'] : '';?>" class="form-control" id="exampleInputName1" placeholder="Rack No">
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Self No<span class="text-danger">*</span></label>
                        <input type="text" name="self_no" required="" value="<?php echo (isset($data['self_no'])) ? $data['self_no'] : '';?>" class="form-control" id="exampleInputName1" placeholder="Self No">
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Box No<span class="text-danger">*</span></label>
                        <input type="text" name="box_no" required="" value="<?php echo (isset($data['box_no'])) ? $data['box_no'] : '';?>" class="form-control" id="exampleInputName1" placeholder="Box No">
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <div class="col-12 col-md-3">
                        <label for="exampleInputName1">Company Code</label>
                        <input type="text" name="company_code" value="<?php echo (isset($data['company_code'])) ? $data['company_code'] : '';?>" class="form-control" id="company_code" placeholder="Company Code">
                        <small class="empty-message text-danger"></small>
                      </div>
                      <div class="col-12 col-md-1">
                        <button type="button" data-target="#addcompany-model" data-toggle="modal" class="btn btn-outline-primary btn-sm pull-right" style="margin-top: 30px;"><i class="mdi mdi-plus"></i></button>
                      </div>
                            
                      <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Opening Stock Rs</label>
                          <input type="text" value="<?php echo (isset($data['opening_stock'])) ? $data['opening_stock'] : '';?>" name="opening_stock" class="form-control onlynumber" id="exampleInputName1" placeholder="Opening Stock Rs">
                      </div>
                            
                      <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Unit / Strip / Pack</label>
                          <input type="text" value="<?php echo (isset($data['unit'])) ? $data['unit'] : '';?>" name="unit" class="form-control" id="exampleInputName1" placeholder="Opening Stock Rs">
                      </div>
                    </div>
                    
                    <div class="form-group row">

                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Min Qty.<span class="text-danger">*</span></label>
                        <input type="text" required="" value="<?php echo (isset($data['min_qty'])) ? $data['min_qty'] : '';?>" name="min_qty" class="form-control onlynumber" id="exampleInputName1" placeholder="Min Qty.">
                      </div>
                        
                      <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Ratio<span class="text-danger">*</span></label>
                          <input type="text" required="" value="<?php echo (isset($data['ratio'])) ? $data['ratio'] : '';?>" name="ratio" class="form-control onlynumber" id="Ratio" placeholder="Ratio.">
                      </div>
                       
                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Max Qty.<span class="text-danger">*</span></label>
                        <input type="text" required="" value="<?php echo (isset($data['max_qty'])) ? $data['max_qty'] : '';?>" name="max_qty" class="form-control onlynumber" id="exampleInputName1" placeholder="Max Qty.">
                      </div>
                         
                    </div>

                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Status</label>
                          <?php $data['status'] = (isset($data['status'])) ? $data['status'] : '';?>
                        
                          <div class="row no-gutters">
                          
                              <div class="col">
                                <div class="form-radio">
                                <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" <?php if(isset($_GET['id'])){if($data['status'] == "1"){echo "checked";}  }else{echo"checked";} ?>>
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
                    
                    <br>
                    <a href="view-product-master.php" class="btn btn-light pull-left">Back</a>
                    <?php 
                    if(isset($_GET['id'])){
                      ?>
                    <button type="submit" name="update" class="btn btn-success mr-2 pull-left">Update</button>
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
  
</body>
</html>
