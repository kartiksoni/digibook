<?php include('include/usertypecheck.php');?>

<?php 
if(isset($_GET['id'])){
  $id = $_GET['id'];
  $query = "SELECT * FROM `pharmacy_profile`WHERE id ='".$id."'";
  $result = mysqli_query($conn,$query);
  $data = mysqli_fetch_array($result);
  

  $sub_data = array();
  $query1 = "SELECT * FROM `pharmacy_profile_details`WHERE pharmacy_id ='".$id."'";
  $result1 = mysqli_query($conn,$query1);
  while($row = mysqli_fetch_array($result1)){
      $sub_data[] = $row;
  }
  
}


if(isset($_POST['submit'])){
  /*echo"<pre>";
  print_r($_FILES);exit;*/
  $user_id = $_SESSION['auth']['id'];
  $firm_id = $_POST['firm_id'];
  $pharmacy_name = $_POST['pharmacy_name'];
  $contact_person_name = $_POST['contact_person_name'];
  $address1 = $_POST['address1'];
  $address2 = $_POST['address2'];
  $address3 = $_POST['address3'];
  $countryId = $_POST['countryId'];
  $stateId = $_POST['stateId'];
  $district_name = $_POST['district_name'];
  $city_name = $_POST['city_name'];
  $pin_code = $_POST['pin_code'];
  $telephone_no = $_POST['telephone_no'];
  $mobile_no = $_POST['mobile_no'];
  $email = $_POST['email'];
  $pan_no = $_POST['pan_no'];
  $gst_no = $_POST['gst_no'];
  $dl_no1 = $_POST['dl_no1'];
  $dl_no2 = $_POST['dl_no2'];
  $drug_lic = date("Y-m-d",strtotime(str_replace("/","-",$_POST['drug_lic'])));
  $shop_act_license = $_POST['shop_act_license'];
  $exp_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST['exp_date'])));

  $insQry = "INSERT INTO `pharmacy_profile`(`firm_name`, `pharmacy_name`, `contact_person_name`, `address1`, `address2`, `address3`, `countryId`, `stateId`, `district_name`, `city_name`, `pin_code`, `telephone_no`, `mobile_no`, `email`, `pan_no`, `gst_no`, `dl_no1`, `dl_no2`, `drug_lic`, `shop_act_license`, `exp_date`, `created_at`, `created_by`) VALUES ('".$firm_id."','".$pharmacy_name."','".$contact_person_name."','".$address1."','".$address2."','".$address3."','".$countryId."','".$stateId."','".$district_name."','".$city_name."','".$pin_code."','".$telephone_no."','".$mobile_no."','".$email."','".$pan_no."','".$gst_no."','".$dl_no1."','".$dl_no2."','".$drug_lic."','".$shop_act_license."','".$exp_date."','".date('Y-m-d H:i:s')."','".$user_id."')";
  $queryInsert = mysqli_query($conn,$insQry);
  $last_id = mysqli_insert_id($conn);
  if($queryInsert){

    $count = count($_POST['pharmacist_name']);

    if(!empty($_POST['pharmacist_name'][0])){
      $uplode_dir = "profile";
      for ($i = 0; $i < $count; $i++){

          $pharmacist_name = "";
          if(isset($_POST["pharmacist_name"][$i])){
              $pharmacist_name = $_POST["pharmacist_name"][$i];
          }

          $pharmacist_reg_no = "";
          if(isset($_POST["pharmacist_reg_no"][$i])){
              $pharmacist_reg_no = $_POST["pharmacist_reg_no"][$i];
          }

          $pharmacist_reg_date = "";
          if(isset($_POST["pharmacist_reg_date"][$i])){
              $pharmacist_reg_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST["pharmacist_reg_date"][$i])));
          }

          $p_address1 = "";
          if(isset($_POST["p_address1"][$i])){
              $p_address1 = $_POST["p_address1"][$i];
          }

          $p_address2 = "";
          if(isset($_POST["p_address2"][$i])){
              $p_address2 = $_POST["p_address2"][$i];
          }

          $p_address3 = "";
          if(isset($_POST["p_address3"][$i])){
              $p_address3 = $_POST["p_address3"][$i];
          }

          $city = "";
          if(isset($_POST["city"][$i])){
              $city = $_POST["city"][$i];
          }

          $contact_no = "";
          if(isset($_POST["contact_no"][$i])){
              $contact_no = $_POST["contact_no"][$i];
          }

          $img = "";
          if(isset($_POST["img"][$i])){
              $img = $_POST["img"][$i];
          }

          $aadhar_card_no = "";
          if(isset($_POST["aadhar_card_no"][$i])){
              $aadhar_card_no = $_POST["aadhar_card_no"][$i];
          }

          $p_exp_date = "";
          if(isset($_POST["p_exp_date"][$i])){
              $p_exp_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST["p_exp_date"][$i])));
          }

          $photo_name = "";
          if(isset($_FILES['img']['name'][$i])){
            $photo_name = $_FILES['img']['name'][$i];
          }

          $photo_tmp_name = "";
          if(isset($_FILES['img']['tmp_name'][$i])){
            $photo_tmp_name = $_FILES['img']['tmp_name'][$i];
          }

          $file_basename = substr($photo_name, 0, strripos($photo_name, '.')); // get file extention
          $file_ext = substr($photo_name, strripos($photo_name, '.')); // get file name
          $newfilename = $file_basename.rand() . $file_ext;
          

          move_uploaded_file($photo_tmp_name,"$uplode_dir/$newfilename");
         
          $insQry = "INSERT INTO `pharmacy_profile_details`(`pharmacy_id`, `pharmacist_name`, `pharmacist_reg_no`, `pharmacist_reg_date`, `p_address1`, `p_address2`, `p_address3`, `city`, `contact_no`, `img`, `aadhar_card_no`, `p_exp_date`, `created_at`, `created_by`) VALUES ('".$last_id."','".$pharmacist_name."','".$pharmacist_reg_no."','".$pharmacist_reg_date."','".$p_address1."','".$p_address2."','".$p_address3."','".$city."','".$contact_no."','".$newfilename."','".$aadhar_card_no."','".$p_exp_date."','".date('Y-m-d H:i:s')."','".$user_id."')";
          
          $queryInsert = mysqli_query($conn,$insQry);
         
      }
            if($queryInsert){
                $_SESSION['msg']['success'] = "Pharmacy Profile Added Successfully.";
                header('location:pharmacy-profile.php');exit;
            }else{
                $_SESSION['msg']['fail'] = "Product Added Failed.";
                header('location:pharmacy-profile.php');exit;
            }
    }

  }else{

    $_SESSION['msg']['fail'] = "Product Added Failed.";
    header('location:pharmacy-profile.php');exit;

  }
}

if(isset($_POST['update'])){

  $last_id = $_GET['id'];
  $user_id = $_SESSION['auth']['id'];
  $firm_id = $_POST['firm_id'];
  $pharmacy_name = $_POST['pharmacy_name'];
  $contact_person_name = $_POST['contact_person_name'];
  $address1 = $_POST['address1'];
  $address2 = $_POST['address2'];
  $address3 = $_POST['address3'];
  $countryId = $_POST['countryId'];
  $stateId = $_POST['stateId'];
  $district_name = $_POST['district_name'];
  $city_name = $_POST['city_name'];
  $pin_code = $_POST['pin_code'];
  $telephone_no = $_POST['telephone_no'];
  $mobile_no = $_POST['mobile_no'];
  $email = $_POST['email'];
  $pan_no = $_POST['pan_no'];
  $gst_no = $_POST['gst_no'];
  $dl_no1 = $_POST['dl_no1'];
  $dl_no2 = $_POST['dl_no2'];
  $drug_lic = date("Y-m-d",strtotime(str_replace("/","-",$_POST['drug_lic'])));
  $shop_act_license = $_POST['shop_act_license'];
  $exp_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST['exp_date'])));

  $updqry = "UPDATE `pharmacy_profile` SET `firm_name`='".$firm_id."',`pharmacy_name`='".$pharmacy_name."',`contact_person_name`='".$contact_person_name."',`address1`='".$address1."',`address2`='".$address2."',`address3`='".$address3."',`countryId`='".$countryId."',`stateId`='".$stateId."',`district_name`='".$district_name."',`city_name`='".$city_name."',`pin_code`='".$pin_code."',`telephone_no`='".$telephone_no."',`mobile_no`='".$mobile_no."',`email`='".$email."',`pan_no`='".$pan_no."',`gst_no`='".$gst_no."',`dl_no1`='".$dl_no1."',`dl_no2`='".$dl_no2."',`drug_lic`='".$drug_lic."',`shop_act_license`='".$shop_act_license."',`exp_date`='".$exp_date."',`updated_at`='".date('Y-m-d H:i:s')."',`updated_by`='".$user_id."' WHERE id = '".$last_id."'";
  $queryUpdate = mysqli_query($conn,$updqry);
  if($queryUpdate){

    $sqlQry = "DELETE FROM pharmacy_profile_details WHERE pharmacy_id='".$last_id."'";
    mysqli_query($conn,$sqlQry);
    $count = count($_POST['pharmacist_name']);

    if(!empty($_POST['pharmacist_name'][0])){
      $uplode_dir = "profile";
      for ($i = 0; $i < $count; $i++){

          $pharmacist_name = "";
          if(isset($_POST["pharmacist_name"][$i])){
              $pharmacist_name = $_POST["pharmacist_name"][$i];
          }

          $pharmacist_reg_no = "";
          if(isset($_POST["pharmacist_reg_no"][$i])){
              $pharmacist_reg_no = $_POST["pharmacist_reg_no"][$i];
          }

          $pharmacist_reg_date = "";
          if(isset($_POST["pharmacist_reg_date"][$i])){
              $pharmacist_reg_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST["pharmacist_reg_date"][$i])));
          }

          $p_address1 = "";
          if(isset($_POST["p_address1"][$i])){
              $p_address1 = $_POST["p_address1"][$i];
          }

          $p_address2 = "";
          if(isset($_POST["p_address2"][$i])){
              $p_address2 = $_POST["p_address2"][$i];
          }

          $p_address3 = "";
          if(isset($_POST["p_address3"][$i])){
              $p_address3 = $_POST["p_address3"][$i];
          }

          $city = "";
          if(isset($_POST["city"][$i])){
              $city = $_POST["city"][$i];
          }

          $contact_no = "";
          if(isset($_POST["contact_no"][$i])){
              $contact_no = $_POST["contact_no"][$i];
          }

          $img = "";
          if(isset($_POST["img"][$i])){
              $img = $_POST["img"][$i];
          }

          $update_img = "";
          if(isset($_POST['update_img'][$i])){
            $update_img = $_POST['update_img'][$i];
          }

          $aadhar_card_no = "";
          if(isset($_POST["aadhar_card_no"][$i])){
              $aadhar_card_no = $_POST["aadhar_card_no"][$i];
          }

          $p_exp_date = "";
          if(isset($_POST["p_exp_date"][$i])){
              $p_exp_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST["p_exp_date"][$i])));
          }

          $photo_name = "";
          if(isset($_FILES['img']['name'][$i])){
            $photo_name = $_FILES['img']['name'][$i];
          }

          $photo_tmp_name = "";
          if(isset($_FILES['img']['tmp_name'][$i])){
            $photo_tmp_name = $_FILES['img']['tmp_name'][$i];
          }

          if(empty($photo_name)){
            $photo_name = $update_img;
          }else{
            unlink("$uplode_dir/$update_img");
            $file_basename = substr($photo_name, 0, strripos($photo_name, '.')); // get file extention
            $file_ext = substr($photo_name, strripos($photo_name, '.')); // get file name
            $newfilename = $file_basename.rand() . $file_ext;
            move_uploaded_file($photo_tmp_name,"$uplode_dir/$newfilename");
          }
         
          $insQry = "INSERT INTO `pharmacy_profile_details`(`pharmacy_id`, `pharmacist_name`, `pharmacist_reg_no`, `pharmacist_reg_date`, `p_address1`, `p_address2`, `p_address3`, `city`, `contact_no`, `img`, `aadhar_card_no`, `p_exp_date`, `created_at`, `created_by`) VALUES ('".$last_id."','".$pharmacist_name."','".$pharmacist_reg_no."','".$pharmacist_reg_date."','".$p_address1."','".$p_address2."','".$p_address3."','".$city."','".$contact_no."','".$newfilename."','".$aadhar_card_no."','".$p_exp_date."','".date('Y-m-d H:i:s')."','".$user_id."')";
          
          $queryInsert = mysqli_query($conn,$insQry);
         
      }
            if($queryInsert){
                $_SESSION['msg']['success'] = "Pharmacy Profile Updated Successfully.";
                header('location:view-pharmacy-profile.php');exit;
            }else{
                $_SESSION['msg']['fail'] = "Product Updated Failed.";
                header('location:view-pharmacy-profile.php');exit;
            }
    }

  }else{
    $_SESSION['msg']['fail'] = "Pharmacy Profile Updated Failed.";
    header('location:view-pharmacy-profile.php');exit;
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
            
           
             <!-- Pharmacy Profile Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Pharmacy Profile</h4>
                  <hr class="alert-dark">
                  <br>
                  <form class="" method="POST" enctype="multipart/form-data">
                  <h5 class="card-title">Pharmacy Details</h5>
                  <hr>
                  
                    <div class="form-group row">
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Select Firm<span class="text-danger">*</span></label>
                    <select name="firm_id" class="js-example-basic-single" required style="width:100%"> 
                    <option <?php if(isset($data['firm_name']) && $data['firm_name'] == "Regular"){echo "selected";} ?> value="Regular">Regular</option>
                    <option <?php if(isset($data['firm_name']) && $data['firm_name'] == "Unregistered"){echo "selected";} ?> value="Unregistered">Unregistered</option>
                    <option <?php if(isset($data['firm_name']) && $data['firm_name'] == "Composition"){echo "selected";} ?> value="Composition">Composition</option>
                    </select>
                    </div>
                    
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Pharmacy Name<span class="text-danger">*</span></label>
                    <input name="pharmacy_name" value="<?php echo (isset($data['pharmacy_name'])) ? $data['pharmacy_name'] : ''; ?>" type="text" required class="form-control" id="exampleInputName1" placeholder="Pharmacy Name">
                    </div>
                    
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Contact Person Name <span class="text-danger">*</span></label>
                    <input type="text" required name="contact_person_name" value="<?php echo (isset($data['contact_person_name'])) ? $data['contact_person_name'] : ''; ?>" class="form-control" id="exampleInputName1" placeholder="Contact Person Name">
                    </div>
                    
                    
                    
                    
                    </div>
                    
                    <div class="form-group row">
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Address</label>
                    <input type="text" name="address1" value="<?php echo (isset($data['address1'])) ? $data['address1'] : ''; ?>" class="form-control" id="exampleInputName1" placeholder="Address line1">
                    </div>
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">&nbsp;</label>
                    <input type="text" name="address2" value="<?php echo (isset($data['address2'])) ? $data['address2'] : ''; ?>" class="form-control" id="exampleInputName1" placeholder="Address line2">
                    </div>
                    
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">&nbsp;</label>
                    <input type="text" name="address3" value="<?php echo (isset($data['address3'])) ? $data['address3'] : ''; ?>" class="form-control" id="exampleInputName1" placeholder="Address line3">
                    </div>
                    
                    </div>
                    
                    <div class="form-group row">
                    
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Country<span class="text-danger">*</span></label>
                    <select style="width:100%" class="js-example-basic-single" required name="countryId" id="countryId" onChange="ownergetState(this.value);" required>
                              <option value="">Select Country</option>
                                <?php 
                                    $st_qry = "SELECT * FROM own_countries" ;
                                    $states = mysqli_query($conn,$st_qry);
                                    while($row = mysqli_fetch_assoc($states)){
                                    ?>
                                <option <?php if(isset($data['countryId']) && $data['countryId'] == $row['id']){echo "selected";} ?> value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                <?php } ?>
                            </select>
                    
                    </div>
                    
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">State<span class="text-danger">*</span></label>
                    <select style="width: 100%" required class="form-control js-example-basic-single" name="stateId" id="allstatevalue" required>
                              <option value="">Select State</option>
                              <?php 
                                    $st_qry = "SELECT * FROM own_states WHERE country_id = ".$data['countryId']; 
                                    $states = mysqli_query($conn,$st_qry);
                                    while($row = mysqli_fetch_assoc($states)){
                                    ?>
                             <option <?php if(isset($data['stateId']) && $data['stateId'] == $row['id']){echo "selected";} ?> value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                              <?php } ?>
                            </select>
                    </div>
                    
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">District<span class="text-danger">*</span></label>
                    <input type="text" name="district_name" required class="form-control" id="exampleInputName1" value="<?php echo (isset($data['district_name'])) ? $data['district_name'] : ''; ?>" placeholder="District">
                    </div>
                    
                    </div>
                    
                    <div class="form-group row">
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">City<span class="text-danger">*</span></label>
                    <input type="text" name="city_name" required class="form-control" id="exampleInputName1" value="<?php echo (isset($data['city_name'])) ? $data['city_name'] : ''; ?>" placeholder="City">
                    </div>
                    
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Pincode<span class="text-danger">*</span></label>
                    <input data-parsley-type="number" type="text" name="pin_code" required class="form-control" id="exampleInputName1" value="<?php echo (isset($data['pin_code'])) ? $data['pin_code'] : ''; ?>" placeholder="Pincode">
                    </div>
                    
                    </div>
                    
                  <br>  
                  <h5 class="card-title">Contact Details</h5>
                  <hr>
                  
                    <div class="form-group row">
                    
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Telephone No</label>
                        <input type="text" name="telephone_no" class="form-control" id="exampleInputName1" value="<?php echo (isset($data['telephone_no'])) ? $data['telephone_no'] : ''; ?>" placeholder="Telephone No">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Mobile No<span class="text-danger">*</span></label>
                        <input data-parsley-type="number" type="text" name="mobile_no" required class="form-control" id="exampleInputName1" value="<?php echo (isset($data['mobile_no'])) ? $data['mobile_no'] : ''; ?>" placeholder="Mobile No">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Email<span class="text-danger">*</span></label>
                        <input class="form-control" name="email" value="<?php echo (isset($data['email'])) ? $data['email'] :''; ?>" required data-inputmask="'alias': 'email'" />
                        </div>
                    
                    </div>
                    
                    
                    
                  <br>  
                  <h5 class="card-title">Financial Details</h5>
                  <hr>
                  
                    <div class="form-group row">
                    
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">PAN No<span class="text-danger">*</span></label>
                        <input type="text" name="pan_no" required class="form-control" id="exampleInputName1" value="<?php echo (isset($data['pan_no'])) ? $data['pan_no'] : ''; ?>" placeholder="PAN No">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">GST No<span class="text-danger">*</span></label>
                        <input type="text" name="gst_no" required class="form-control" id="exampleInputName1" value="<?php echo (isset($data['gst_no'])) ? $data['gst_no'] : ''; ?>" placeholder="GST No">
                        </div>
                        
                        <div class="col-12 col-md-4">


                        <label for="exampleInputName1">DL No</label>
                        <input type="text" name="dl_no1" value="<?php echo (isset($data['dl_no1'])) ? $data['dl_no1'] : ''; ?>" class="form-control" id="exampleInputName1" placeholder="DL No">
                        </div>
                    
                    </div>
                    
                    <div class="form-group row">
                    
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">DL No</label>
                        <input type="text" name="dl_no2" class="form-control" value="<?php echo (isset($data['dl_no2'])) ? $data['dl_no2'] : ''; ?>" id="exampleInputName1" placeholder="DL No">
                        </div>
                        
                         <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Drug Lic. Ex. Date<span class="text-danger">*</span></label>
                        <div id="" class="input-group date datepicker datepicker-popup">
                          <input type="text" value="<?php 
                          if(isset($_GET['id'])){
                          echo date("d/m/Y",strtotime(str_replace("-","/",$data['drug_lic']))); 
                          }
                          ?>" name="drug_lic" class="form-control" placeholder="Drug Lic. Ex. Date">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        <!-- <input type="text" name="drug_lic" required class="form-control" id="exampleInputName1" placeholder="Drug Lic. Ex. Date"> -->
                        </div>
                        
                       
                    
                    </div>
                    
                    <div class="form-group row">
                    
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Shop Act License</label>
                        <input type="text" value="<?php echo (isset($data['shop_act_license'])) ?$data['shop_act_license'] : ''; ?>" name="shop_act_license" class="form-control" id="exampleInputName1" placeholder="Shop Act License">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Exp. Date</label>
                        <div id="" class="input-group date datepicker datepicker-popup">
                          <input type="text" value="<?php 
                          if(isset($_GET['id'])){
                          echo date("d/m/Y",strtotime(str_replace("-","/",$data['exp_date']))); 
                          }
                          ?>" name="exp_date" class="form-control" placeholder="Exp. Date">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        <!-- <input type="text" name="exp_date" class="form-control" id="exampleInputName1" placeholder="Exp. Date"> -->
                        </div>
                    
                    </div>
                    
                    
                    
                  <br>
                    
                  <h5 class="card-title">Pharmacist Details </h5>
                  
                  <?php 
                  if(isset($_GET['id'])){
                    $count = count($sub_data);
                    $j=0;
                    ?>
                    <div class="pharmacist-detail-section">
                    <?php
                    foreach ($sub_data as $row) {
                    ?>
                    <div class="content-add-more">
                      
                        <?php 
                        if($j >= 1){
                          ?>
                          <div class="row">
                            <div class="col-md-12">
                              <a class="btn btn-danger btn-sm pull-right btn-remove-product" style="color:#fff;"><i class="fa fa-plus"></i> Remove</a>
                            </div>
                          </div>
                          <?php
                        }
                        ?>
                        <hr>
                      
                        <div class="form-group row">
                            <input type="hidden" value="<?php echo $row['id']; ?>" name="id[]">
                            <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Pharmacist Name<span class="text-danger">*</span></label>
                            <input type="text" required name="pharmacist_name[]" class="form-control" id="exampleInputName1" value="<?php echo (isset($row['pharmacist_name'])) ? $row['pharmacist_name'] : ''; ?>" placeholder="Pharmacist Name">
                            </div>
                            
                            <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Pharmacist Reg. No<span class="text-danger">*</span></label>
                            <input type="text" required="" name="pharmacist_reg_no[]" class="form-control" id="exampleInputName1" value="<?php echo (isset($row['pharmacist_reg_no'])) ? $row['pharmacist_reg_no'] : ''; ?>" placeholder="Pharmacist Reg. No">
                            </div>
                            
                            <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Pharmacist Reg. No Exp. Date<span class="text-danger">*</span></label>
                            <div id="" class="input-group date datepicker datepicker-popup">
                              <input type="text" value="<?php echo(isset($row['pharmacist_reg_date']) && $row['pharmacist_reg_date'] != '') ? date("d/m/Y",strtotime(str_replace("-","/",$row['pharmacist_reg_date']))) : ''; ?>" name="pharmacist_reg_date[]" class="form-control" placeholder="Pharmacist Reg. No Exp. Date">
                              <span class="input-group-addon input-group-append border-left">
                                <span class="mdi mdi-calendar input-group-text"></span>
                              </span>
                            </div>
                            <!-- <input type="text" required name="pharmacist_reg_date[]" class="form-control" id="exampleInputName1" placeholder="Pharmacist Reg. No Exp. Date"> -->
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Address</label>
                            <input type="text" name="p_address1[]" class="form-control" id="exampleInputName1" value="<?php echo (isset($row['p_address1'])) ? $row['p_address1'] : ''; ?>" placeholder="Address line1">
                            </div>
                            <div class="col-12 col-md-4">
                            <label for="exampleInputName1">&nbsp;</label>
                            <input type="text" name="p_address2[]" class="form-control" id="exampleInputName1" value="<?php echo (isset($row['p_address2'])) ? $row['p_address2'] : ''; ?>" placeholder="Address line2">
                            </div>
                            
                            <div class="col-12 col-md-4">
                            <label for="exampleInputName1">&nbsp;</label>
                            <input type="text" name="p_address3[]" class="form-control" id="exampleInputName1" value="<?php echo (isset($row['p_address3'])) ? $row['p_address3'] : ''; ?>" placeholder="Address line3">
                            </div>
                        </div>
                        <div class="form-group row">
                        
                            <div class="col-12 col-md-4">
                            <label for="exampleInputName1">City<span class="text-danger">*</span></label>
                            <input type="text" required name="city[]" class="form-control" id="exampleInputName1" value="<?php echo (isset($row['city'])) ? $row['city'] : ''; ?>" placeholder="City">
                            </div>
                            
                             <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Contact No</label>
                            <input type="text" name="contact_no[]" class="form-control" id="exampleInputName1" value="<?php echo (isset($row['contact_no'])) ? $row['contact_no'] : ''; ?>" placeholder="Contact No">
                            </div>
                            
                             <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Photo</label>
                            <input type="file" value="<?php echo (isset($row['img'])) ? $row['img'] : ''; ?>" name="img[]" class="file-upload-default">
                            <div class="input-group col-xs-12">
                            <input type="text" value="<?php echo(isset($row['img'])) ? $row['img'] : ''; ?>" class="form-control file-upload-info" disabled placeholder="Upload Image">
                            <span class="input-group-append">
                              <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                            </span>
                        </div>
                        </div>
                        <input type="hidden" name="update_img[]" value="<?php echo(isset($row['img'])) ? $row['img'] : ''; ?>">
                        </div>
                        <div class="form-group row">
                        
                            <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Aadhar Card No<span class="text-danger">*</span></label>
                            <input type="text" required name="aadhar_card_no[]" class="form-control" id="exampleInputName1" value="<?php echo (isset($row['aadhar_card_no'])) ? $row['aadhar_card_no'] : ''; ?>" placeholder="Aadhar Card No">
                            </div>
                            
                            <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Exp. Date</label>
                            <div id="" class="input-group date datepicker datepicker-popup">
                              <input type="text" value="<?php echo (isset($row['p_exp_date']) && $row['p_exp_date'] != '') ? date("d/m/Y",strtotime(str_replace("-","/",$row['p_exp_date']))) : ''; ?>" name="p_exp_date[]" class="form-control" placeholder="Exp.Date">
                              <span class="input-group-addon input-group-append border-left">
                                <span class="mdi mdi-calendar input-group-text"></span>
                              </span>
                            </div>
                            <!-- <input type="text" name="p_exp_date[]" class="form-control" id="exampleInputName1" placeholder="Exp.Date"> -->
                            </div>
                        </div> 
                      
                    </div>
                    <?php $j++; } ?>
                    </div>
                    <?php
                  }else{
                  ?>

                    <div class="pharmacist-detail-section">
                      <hr>
                    
                      <div class="form-group row">
                      
                          <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Pharmacist Name<span class="text-danger">*</span></label>
                          <input type="text" required name="pharmacist_name[]" class="form-control" id="exampleInputName1" placeholder="Pharmacist Name">
                          </div>
                          
                          <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Pharmacist Reg. No<span class="text-danger">*</span></label>
                          <input type="text" required="" name="pharmacist_reg_no[]" class="form-control" id="exampleInputName1" placeholder="Pharmacist Reg. No">
                          </div>
                          
                          <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Pharmacist Reg. No Exp. Date<span class="text-danger">*</span></label>
                          <div id="" class="input-group date datepicker datepicker-popup">
                            <input type="text" value="" name="pharmacist_reg_date[]" class="form-control" placeholder="Pharmacist Reg. No Exp. Date">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          <!-- <input type="text" required name="pharmacist_reg_date[]" class="form-control" id="exampleInputName1" placeholder="Pharmacist Reg. No Exp. Date"> -->
                          </div>
                      </div>
                      <div class="form-group row">
                          <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Address</label>
                          <input type="text" name="p_address1[]" class="form-control" id="exampleInputName1" placeholder="Address line1">
                          </div>
                          <div class="col-12 col-md-4">
                          <label for="exampleInputName1">&nbsp;</label>
                          <input type="text" name="p_address2[]" class="form-control" id="exampleInputName1" placeholder="Address line2">
                          </div>
                          
                          <div class="col-12 col-md-4">
                          <label for="exampleInputName1">&nbsp;</label>
                          <input type="text" name="p_address3[]" class="form-control" id="exampleInputName1" placeholder="Address line3">
                          </div>
                      </div>
                      <div class="form-group row">
                      
                          <div class="col-12 col-md-4">
                          <label for="exampleInputName1">City<span class="text-danger">*</span></label>
                          <input type="text" required name="city[]" class="form-control" id="exampleInputName1" placeholder="City">
                          </div>
                          
                           <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Contact No</label>
                          <input type="text" name="contact_no[]" class="form-control" id="exampleInputName1" placeholder="Contact No">
                          </div>
                          
                           <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Photo</label>
                          <input type="file" name="img[]" class="file-upload-default">
                        	<div class="input-group col-xs-12">
                          <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                          <span class="input-group-append">
                            <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                          </span>
                      </div>
                      </div>
                      
                      </div>
                      <div class="form-group row">
                      
                          <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Aadhar Card No<span class="text-danger">*</span></label>
                          <input type="text" required name="aadhar_card_no[]" class="form-control" id="exampleInputName1" placeholder="Aadhar Card No">
                          </div>
                          
                          <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Exp. Date</label>
                          <div id="" class="input-group date datepicker datepicker-popup">
                            <input type="text" value="" name="p_exp_date[]" class="form-control" placeholder="Exp.Date">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          <!-- <input type="text" name="p_exp_date[]" class="form-control" id="exampleInputName1" placeholder="Exp.Date"> -->
                          </div>
                      </div> 
                    </div>
                  <?php } ?>
                    
                 
                 <div class="row">
                 <div class="col pt-1">
                 	<a class="btn btn-primary btn-sm btn-addmore-product" style="color:#fff;"><i class="fa fa-plus"></i> Add more</a>
                 </div>   
                 </div>
                      
                    
                    
                  
                    
                    <br>
                    <a href="view-pharmacy-profile.php" class="btn btn-light pull-left">Back</a>
                    <?php 
                    if(isset($_GET['id'])){
                      ?>
                    <button type="submit" name="update" class="btn btn-success mr-2 pull-right">Update</button>
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
        <div id="copy_html" class="" style="display: none">
                  <div class="content-add-more">
                  <hr>
                  <div class="row">
                    <div class="col-md-12">
                      <a class="btn btn-danger btn-sm pull-right btn-remove-product" style="color:#fff;"><i class="fa fa-plus"></i> Remove</a>
                    </div>
                  </div>
                    <div class="form-group row">
                    
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Pharmacist Name<span class="text-danger">*</span></label>
                        <input type="text" name="pharmacist_name[]" required class="form-control" id="exampleInputName1" placeholder="Pharmacist Name">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Pharmacist Reg. No<span class="text-danger">*</span></label>
                        <input type="text" name="pharmacist_reg_no[]" required class="form-control" id="exampleInputName1" placeholder="Pharmacist Reg. No">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Pharmacist Reg. No Exp. Date<span class="text-danger">*</span></label>
                        <div id="" class="input-group date datepicker datepicker-popup">
                          <input type="text" value="" name="pharmacist_reg_date[]" class="form-control" placeholder="Pharmacist Reg. No Exp. Date">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        </div>
                    
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Address</label>
                        <input type="text" name="p_address1[]" class="form-control" id="exampleInputName1" placeholder="Address line1">
                        </div>
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">&nbsp;</label>
                        <input type="text" name="p_address2[]" class="form-control" id="exampleInputName1" placeholder="Address line2">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">&nbsp;</label>
                        <input type="text" name="p_address3[]" class="form-control" id="exampleInputName1" placeholder="Address line3">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                    
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">City<span class="text-danger">*</span></label>
                        <input type="text" name="city[]" required class="form-control" id="exampleInputName1" placeholder="City">
                        </div>
                        
                         <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Contact No</label>
                        <input type="text" name="contact_no[]" class="form-control" id="exampleInputName1" placeholder="Contact No">
                        </div>
                        
                         <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Photo</label>
                        <input type="file" name="img[]" class="file-upload-default">
                        <div class="input-group col-xs-12">
                        <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                        <span class="input-group-append">
                          <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                        </span>
                      </div>
                        </div>
                    
                    </div>
                    
                    <div class="form-group row">
                    
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Aadhar Card No<span class="text-danger">*</span></label>
                        <input type="text" name="aadhar_card_no[]" required class="form-control" id="exampleInputName1" placeholder="Aadhar Card No">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Exp. Date</label>
                        <div id="" class="input-group date datepicker datepicker-popup">
                          <input type="text" value="" name="p_exp_date[]" class="form-control" placeholder="Exp.Date">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        <!-- <input type="text" name="p_exp_date[]" class="form-control" id="exampleInputName1" placeholder="Exp. Date"> -->
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
  
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>
  
 <script>
    $('.datepicker-popup').datepicker({
      enableOnReadonly: true,
      autoclose : true,
      todayHighlight: true,
      format: 'dd/mm/yyyy'
    });
 </script>
 
  <!-- Custom js for this page-->
  

  <script type="text/javascript">
    function ownergetState(country){
      
    var that = $(this);
    var id =  country;

    if(id!="")
    {             
      var action = "ownergetStateDetails";
    
      $.ajax({
      type: "POST",
      url: "ajax.php",
      dataType: 'json',
      data: {action:action , id:id},
      success: function(data){
        if(data.error == 0)
        { 
          $("#allstatevalue").html(data.html);  
          $("#allcities").html('<option>No Cities Found<\/option>');  
        }
      },
      error : function(data) {  that.parent().parent().siblings('.subCategories').html(""); }
      });
    }
  }
  </script>
  <script type="text/javascript">
    $('body').on('click', '.btn-addmore-product', function() {
        var totalproduct = $('.product-tr').length;//for product length
        var html = $('#copy_html').html();
        
        $('.pharmacist-detail-section').append(html);
        $('.datepicker-popup').datepicker({
          enableOnReadonly: true,
          autoclose : true,
          todayHighlight: true,
          format: 'dd/mm/yyyy'
        });
        (function($) {
            'use strict';
            $(function() {
              $('.file-upload-browse').on('click', function() {
                var file = $(this).parent().parent().parent().find('.file-upload-default');
                file.trigger('click');
              });
              $('.file-upload-default').on('change', function() {
                $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
              });
            });
          })(jQuery);
    });

    $('body').on('click', '.btn-remove-product', function(e) {
        e.preventDefault();
        $(this).closest ('.content-add-more').remove ();
        
    });
  </script>
  <!-- script for custom validation -->
<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>
</body>


</html>
