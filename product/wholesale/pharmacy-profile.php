<?php include('include/usertypecheck.php');?>
<?php 
if($_SESSION['auth']['user_type'] != "owner"){
    header('Location:index.php');
    exit;
}
?>

<?php 
if(isset($_GET['id'])){
  $id = $_GET['id'];
  $query = "SELECT * FROM `pharmacy_profile`WHERE id ='".$id."'";
  $result = mysqli_query($conn,$query);
  $data = mysqli_fetch_array($result);
  
  
  $year = "SELECT * FROM `financial` WHERE id='".$data['financialyear_id']."'";
  $yearresult = mysqli_query($conn,$year);
  $yeardetail = mysqli_fetch_array($yearresult);

  $sub_data = array();
  $query1 = "SELECT * FROM `pharmacy_profile_details`WHERE pharmacy_id ='".$id."'";
  $result1 = mysqli_query($conn,$query1);
  while($row = mysqli_fetch_array($result1)){
      $sub_data[] = $row;
  }
  
//   $bank_data = array();
//   $query2 = "SELECT * FROM pharmacy_bank_details WHERE pharmacy_id = '".$id."'";
//   $qryrun = mysqli_query($conn, $query2);
//   while($bankrow = mysqli_fetch_assoc($qryrun)){
//     $bank_data[] = $bankrow;
//   }
  
}


if(isset($_POST['submit'])){
  /*echo"<pre>";
  print_r($_FILES);exit;*/
  
  $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : NULL;
  $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : NULL;
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
   $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : '';
  $uplode_url = "logo_images/";
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
  $adhar_no = $_POST['adhar_no'];
  $telephone_no = $_POST['telephone_no'];
  $mobile_no = $_POST['mobile_no'];
  $email = $_POST['email'];
//   $tin_no = $_POST['tin_no'];
  $pan_no = $_POST['pan_no'];
  $gst_no = $_POST['gst_no'];
  $dl_no1 = $_POST['dl_no1'];
  $dl_no2 = $_POST['dl_no2'];
  $drug_lic = date("Y-m-d",strtotime(str_replace("/","-",$_POST['drug_lic'])));
  $drug_lic_renewal_date = $_POST['drug_lic_renewal_date'];
  $shop_act_license = $_POST['shop_act_license'];
  $exp_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST['exp_date'])));
  $shop_act_renewal_date = $_POST['shop_act_renewal_date'];
  $cin_no = $_POST['cin_no']; 
  $print_type = $_POST['print_type'];
  $letter_pad = $_POST['letter_pad'];
  $close_type = $_POST['close_type'];
  $financial_year = $_POST['financial_name'];
  $start_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST['start_date'])));
  $end_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST['end_date'])));
    // if (!file_exists('company_logo')) {
    //     mkdir('company_logo', 0777, true);
    // }
    $target_Path = $uplode_url.basename( $_FILES["company_logo"]['name'] );
    $filename = basename( $_FILES["company_logo"]['name']);
  if (move_uploaded_file($_FILES['company_logo']['tmp_name'], $target_Path)) {

   
 
 $insQry = "INSERT INTO `pharmacy_profile`(`firm_name`, `pharmacy_name`, `contact_person_name`, `address1`, `address2`, `address3`, `countryId`, `stateId`, `district_name`, `city_name`, `pin_code`, `telephone_no`, `mobile_no`, `email`, `pan_no`, `gst_no`,`adhar_no` ,`dl_no1`, `dl_no2`, `drug_lic`,`drug_lic_renewal_date`,`shop_act_license`,`shop_act_renewal_date`,`exp_date`,`print_type`,`letter_pad`,`close_type`,`logo_url` ,`cin_no`,`created_at`, `created_by`) VALUES ('".$firm_id."','".$pharmacy_name."','".$contact_person_name."','".$address1."','".$address2."','".$address3."','".$countryId."','".$stateId."','".$district_name."','".$city_name."','".$pin_code."','".$telephone_no."','".$mobile_no."','".$email."','".$pan_no."','".$gst_no."','".$adhar_no."','".$dl_no1."','".$dl_no2."','".$drug_lic."','".$drug_lic_renewal_date."','".$shop_act_license."','".$shop_act_renewal_date."','".$exp_date."','".$print_type."','".$letter_pad."','".$close_type."','".$filename."','".$cin_no."','".date('Y-m-d H:i:s')."','".$user_id."')";

  $queryInsert = mysqli_query($conn,$insQry);
  $last_id = mysqli_insert_id($conn);
  
 $instrpyd = "INSERT INTO `financial` (`owner_id`,`admin_id`,`pharmacy_id`,`financial_id`,`user_id`, `f_name`, `start_date`, `end_date`, `status`, `created_at`, `created_by`) VALUES ('".$owner_id."', '".$admin_id."', '".$last_id."','".$financial_id."', '".$user_id."', '".$financial_year."', '".$start_date."', '".$end_date."', '1', '".date('Y-m-d H:i:s')."', '".$user_id."')";
 
  $instrpydR = mysqli_query($conn,$instrpyd);
  $yearlast_id = mysqli_insert_id($conn);
   
   $updt = "UPDATE `pharmacy_profile` SET financialyear_id = '".$yearlast_id."' where id = '".$last_id."'"; 

 $updtR = mysqli_query($conn,$updt);
   
  if($updtR){

    // $count = count($_POST['pharmacist_name']);

    // if(!empty($_POST['pharmacist_name'][0])){
    //   $uplode_dir = "profile";
    //   for ($i = 0; $i < $count; $i++){

    //       $pharmacist_name = "";
    //       if(isset($_POST["pharmacist_name"][$i])){
    //           $pharmacist_name = $_POST["pharmacist_name"][$i];
    //       }

    //       $pharmacist_reg_no = "";
    //       if(isset($_POST["pharmacist_reg_no"][$i])){
    //           $pharmacist_reg_no = $_POST["pharmacist_reg_no"][$i];
    //       }

    //       $pharmacist_reg_date = "";
    //       if(isset($_POST["pharmacist_reg_date"][$i])){
    //           $pharmacist_reg_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST["pharmacist_reg_date"][$i])));
    //       }

    //       $p_address1 = "";
    //       if(isset($_POST["p_address1"][$i])){
    //           $p_address1 = $_POST["p_address1"][$i];
    //       }

    //       $p_address2 = "";
    //       if(isset($_POST["p_address2"][$i])){
    //           $p_address2 = $_POST["p_address2"][$i];
    //       }

    //       $p_address3 = "";
    //       if(isset($_POST["p_address3"][$i])){
    //           $p_address3 = $_POST["p_address3"][$i];
    //       }

    //       $city = "";
    //       if(isset($_POST["city"][$i])){
    //           $city = $_POST["city"][$i];
    //       }

    //       $contact_no = "";
    //       if(isset($_POST["contact_no"][$i])){
    //           $contact_no = $_POST["contact_no"][$i];
    //       }

    //       $img = "";
    //       if(isset($_POST["img"][$i])){
    //           $img = $_POST["img"][$i];
    //       }

    //       $aadhar_card_no = "";
    //       if(isset($_POST["aadhar_card_no"][$i])){
    //           $aadhar_card_no = $_POST["aadhar_card_no"][$i];
    //       }

    //       $p_exp_date = "";
    //       if(isset($_POST["p_exp_date"][$i])){
    //           $p_exp_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST["p_exp_date"][$i])));
    //       }

    //       $photo_name = "";
    //       if(isset($_FILES['img']['name'][$i])){
    //         $photo_name = $_FILES['img']['name'][$i];
    //       }

    //       $photo_tmp_name = "";
    //       if(isset($_FILES['img']['tmp_name'][$i])){
    //         $photo_tmp_name = $_FILES['img']['tmp_name'][$i];
    //       }

    //       $file_basename = substr($photo_name, 0, strripos($photo_name, '.')); // get file extention
    //       $file_ext = substr($photo_name, strripos($photo_name, '.')); // get file name
    //       $newfilename = $file_basename.rand() . $file_ext;
          

    //       move_uploaded_file($photo_tmp_name,"$uplode_dir/$newfilename");
         
    //       $insQry = "INSERT INTO `pharmacy_profile_details`(`pharmacy_id`, `pharmacist_name`, `pharmacist_reg_no`, `pharmacist_reg_date`, `p_address1`, `p_address2`, `p_address3`, `city`, `contact_no`, `img`, `aadhar_card_no`, `p_exp_date`, `created_at`, `created_by`) VALUES ('".$last_id."','".$pharmacist_name."','".$pharmacist_reg_no."','".$pharmacist_reg_date."','".$p_address1."','".$p_address2."','".$p_address3."','".$city."','".$contact_no."','".$newfilename."','".$aadhar_card_no."','".$p_exp_date."','".date('Y-m-d H:i:s')."','".$user_id."')";
          
    //       $queryInsert = mysqli_query($conn,$insQry);
         
    //   }
    // }
    
    //Add Bank Query Start
    // $count1 = count($_POST['bank']);
    // if(!empty($_POST['bank'][0])){
        
    //     for($x = 0; $x < $count1; $x++){

    //     $bankname = "";
    //     if(isset($_POST['bank'][$x])){
    //       $bankname = $_POST['bank'][$x];
    //     }

    //     $ifsc = "";
    //     if(isset($_POST['ifsc'][$x])){
    //       $ifsc = $_POST['ifsc'][$x];
    //     }

    //     $accountnumber = "";
    //     if(isset($_POST['account'][$x])){
    //       $accountnumber = $_POST['account'][$x];
    //     }

    //     $bankaddress = "";
    //     if(isset($_POST['bank_address'][$x])){
    //       $bankaddress = $_POST['bank_address'][$x];
    //     }

    //     $opening = "";
    //     if(isset($_POST['opening'][$x])){
    //       $opening = $_POST['opening'][$x];
    //     }

    //     $type = "";
    //     if(isset($_POST['type'][$x])){
    //       $type = $_POST['type'][$x];
    //     }

    //     $insQry = "INSERT INTO `pharmacy_bank_details`(`pharmacy_id`, `bank_name`, `ifsc_code`, `account_number`, `bank_address`, `opening_balance`, `opening_balance_type`, `created`, `createdby`) VALUES ('".$last_id."', '".$bankname."', '".$ifsc."','".$accountnumber."', '".$bankaddress."', '".$opening."', '".$type."', '".date('Y-m-d H:i:s')."', '".$user_id."')";

    //     $queryInsert = mysqli_query($conn, $insQry);
    //   }

            // if($queryInsert){
            //     $_SESSION['msg']['success'] = "Company Profile Added Successfully.";
            //     header('location:pharmacy-profile.php');exit;
            // }else{
            //     $_SESSION['msg']['fail'] = "Company Profile Added Failed.";
            //     header('location:pharmacy-profile.php');exit;
            // }
    // }

     $_SESSION['msg']['success'] = "Company Profile Added Successfully.";
      header('location:pharmacy-profile.php');exit;
  }
  }else{

    $_SESSION['msg']['fail'] = "Company Profile Added Failed.";
    header('location:pharmacy-profile.php');exit;

  }
}

if(isset($_POST['update'])){
 $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : NULL;
  $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : NULL;
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
   $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : '';
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
//   $tin_no = $_POST['tin_no'];
  $pan_no = $_POST['pan_no'];
  $gst_no = $_POST['gst_no'];
  $adhar_no = $_POST['adhar_no'];
  $dl_no1 = $_POST['dl_no1'];
  $dl_no2 = $_POST['dl_no2'];
  $drug_lic = date("Y-m-d",strtotime(str_replace("/","-",$_POST['drug_lic'])));
  $drug_lic_renewal_date = $_POST['drug_lic_renewal_date'];
  $shop_act_license = $_POST['shop_act_license'];
  $exp_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST['exp_date'])));
  $shop_act_renewal_date = $_POST['shop_act_renewal_date'];
  $cin_no = $_POST['cin_no']; 
  $print_type = $_POST['print_type'];
  $letter_pad = $_POST['letter_pad'];
  $close_type = $_POST['close_type'];
  $financial_year = $_POST['financial_name'];
  $start_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST['start_date'])));
  $end_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST['end_date'])));
  
  if (isset($_FILES["company_logo"]["name"]) && $_FILES["company_logo"]["name"] != '') {
  $uplode_url = "logo_images/";
  $target_Path = $uplode_url.basename( $_FILES["company_logo"]['name'] );
  $filename = basename( $_FILES["company_logo"]['name']);
  move_uploaded_file($_FILES['company_logo']['tmp_name'], $target_Path);
  }else{
     $filename = $data['logo_url']; 
  }
   
  
  $updqry = "UPDATE `pharmacy_profile` SET `firm_name`='".$firm_id."',`pharmacy_name`='".$pharmacy_name."',`contact_person_name`='".$contact_person_name."',`address1`='".$address1."',`address2`='".$address2."',`address3`='".$address3."',`countryId`='".$countryId."',`stateId`='".$stateId."',`district_name`='".$district_name."',`city_name`='".$city_name."',`pin_code`='".$pin_code."',`telephone_no`='".$telephone_no."',`mobile_no`='".$mobile_no."',`email`='".$email."',`pan_no`='".$pan_no."',`gst_no`='".$gst_no."',`adhar_no` ='".$adhar_no."',`dl_no1`='".$dl_no1."',`dl_no2`='".$dl_no2."',`drug_lic`='".$drug_lic."',`drug_lic_renewal_date` = '".$drug_lic_renewal_date."',`shop_act_license`='".$shop_act_license."',`exp_date`='".$exp_date."',`shop_act_renewal_date`='".$shop_act_renewal_date."',`cin_no`='".$cin_no."' ,`print_type`='".$print_type."',`letter_pad`='".$letter_pad."',`close_type`='".$close_type."',`logo_url`='".$filename."',`financialyear_id` = '".$yeardetail['id']."',`updated_at`='".date('Y-m-d H:i:s')."',`updated_by`='".$user_id."' WHERE id = '".$last_id."'";
  $queryUpdate = mysqli_query($conn,$updqry);
  
  $updateQry = "UPDATE `financial` SET `user_id`='".$user_id."',`f_name`='".$financial_year."',`start_date`='".$start_date."',`end_date`='".$end_date."',`status`='1',`updated_at`='".date('Y-m-d H:i:s')."',`updated_by`='".$user_id."' WHERE id='".$yeardetail['id']."'";
  $updateInsert = mysqli_query($conn,$updateQry);
  
  
  if($queryUpdate){

    // $sqlQry = "DELETE FROM pharmacy_profile_details WHERE pharmacy_id='".$last_id."'";
    // mysqli_query($conn,$sqlQry);
    // $count = count($_POST['pharmacist_name']);

    // if(!empty($_POST['pharmacist_name'][0])){
    //   $uplode_dir = "profile";
    //   for ($i = 0; $i < $count; $i++){

    //       $pharmacist_name = "";
    //       if(isset($_POST["pharmacist_name"][$i])){
    //           $pharmacist_name = $_POST["pharmacist_name"][$i];
    //       }

    //       $pharmacist_reg_no = "";
    //       if(isset($_POST["pharmacist_reg_no"][$i])){
    //           $pharmacist_reg_no = $_POST["pharmacist_reg_no"][$i];
    //       }

    //       $pharmacist_reg_date = "";
    //       if(isset($_POST["pharmacist_reg_date"][$i])){
    //           $pharmacist_reg_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST["pharmacist_reg_date"][$i])));
    //       }

    //       $p_address1 = "";
    //       if(isset($_POST["p_address1"][$i])){
    //           $p_address1 = $_POST["p_address1"][$i];
    //       }

    //       $p_address2 = "";
    //       if(isset($_POST["p_address2"][$i])){
    //           $p_address2 = $_POST["p_address2"][$i];
    //       }

    //       $p_address3 = "";
    //       if(isset($_POST["p_address3"][$i])){
    //           $p_address3 = $_POST["p_address3"][$i];
    //       }

    //       $city = "";
    //       if(isset($_POST["city"][$i])){
    //           $city = $_POST["city"][$i];
    //       }

    //       $contact_no = "";
    //       if(isset($_POST["contact_no"][$i])){
    //           $contact_no = $_POST["contact_no"][$i];
    //       }

    //       $img = "";
    //       if(isset($_POST["img"][$i])){
    //           $img = $_POST["img"][$i];
    //       }

    //       $update_img = "";
    //       if(isset($_POST['update_img'][$i])){
    //         $update_img = $_POST['update_img'][$i];
    //       }

    //       $aadhar_card_no = "";
    //       if(isset($_POST["aadhar_card_no"][$i])){
    //           $aadhar_card_no = $_POST["aadhar_card_no"][$i];
    //       }

    //       $p_exp_date = "";
    //       if(isset($_POST["p_exp_date"][$i])){
    //           $p_exp_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST["p_exp_date"][$i])));
    //       }

    //       $photo_name = "";
    //       if(isset($_FILES['img']['name'][$i])){
    //         $photo_name = $_FILES['img']['name'][$i];
    //       }

    //       $photo_tmp_name = "";
    //       if(isset($_FILES['img']['tmp_name'][$i])){
    //         $photo_tmp_name = $_FILES['img']['tmp_name'][$i];
    //       }

    //       if(empty($photo_name)){
    //         $photo_name = $update_img;
    //       }else{
    //         unlink("$uplode_dir/$update_img");
    //         $file_basename = substr($photo_name, 0, strripos($photo_name, '.')); // get file extention
    //         $file_ext = substr($photo_name, strripos($photo_name, '.')); // get file name
    //         $newfilename = $file_basename.rand() . $file_ext;
    //         move_uploaded_file($photo_tmp_name,"$uplode_dir/$newfilename");
    //       }
         
    //       $insQry = "INSERT INTO `pharmacy_profile_details`(`pharmacy_id`, `pharmacist_name`, `pharmacist_reg_no`, `pharmacist_reg_date`, `p_address1`, `p_address2`, `p_address3`, `city`, `contact_no`, `img`, `aadhar_card_no`, `p_exp_date`, `created_at`, `created_by`) VALUES ('".$last_id."','".$pharmacist_name."','".$pharmacist_reg_no."','".$pharmacist_reg_date."','".$p_address1."','".$p_address2."','".$p_address3."','".$city."','".$contact_no."','".$newfilename."','".$aadhar_card_no."','".$p_exp_date."','".date('Y-m-d H:i:s')."','".$user_id."')";
          
    //       $queryInsert = mysqli_query($conn,$insQry);
         
    //   }
    // }
    
    //Update Bank Query Start
    //       $delqry = "DELETE FROM `pharmacy_bank_details` WHERE pharmacy_id ='".$last_id."'";
    //       mysqli_query($conn,$delqry);
          
    //       $count1 = count($_POST['bank']);
    //     if(!empty($_POST['bank'][0])){
        
    //     for($x = 0; $x < $count1; $x++){

    //     $bankname = "";
    //     if(isset($_POST['bank'][$x])){
    //       $bankname = $_POST['bank'][$x];
    //     }

    //     $ifsc = "";
    //     if(isset($_POST['ifsc'][$x])){
    //       $ifsc = $_POST['ifsc'][$x];
    //     }

    //     $accountnumber = "";
    //     if(isset($_POST['account'][$x])){
    //       $accountnumber = $_POST['account'][$x];
    //     }

    //     $bankaddress = "";
    //     if(isset($_POST['bank_address'][$x])){
    //       $bankaddress = $_POST['bank_address'][$x];
    //     }

    //     $opening = "";
    //     if(isset($_POST['opening'][$x])){
    //       $opening = $_POST['opening'][$x];
    //     }

    //     $type = "";
    //     if(isset($_POST['type'][$x])){
    //       $type = $_POST['type'][$x];
    //     }

    //     $insQry = "INSERT INTO `pharmacy_bank_details`(`pharmacy_id`, `bank_name`, `ifsc_code`, `account_number`, `bank_address`, `opening_balance`, `opening_balance_type`, `created`, `createdby`) VALUES ('".$last_id."', '".$bankname."', '".$ifsc."','".$accountnumber."', '".$bankaddress."', '".$opening."', '".$type."', '".date('Y-m-d H:i:s')."', '".$user_id."')";

    //     $queryInsert = mysqli_query($conn, $insQry);
    //   }

            // if($queryInsert){
            //     $_SESSION['msg']['success'] = "Company Profile Updated Successfully.";
            //     header('location:view-pharmacy-profile.php');exit;
            // }else{
            //     $_SESSION['msg']['fail'] = "Company Profile Updated Failed.";
            //     header('location:view-pharmacy-profile.php');exit;
            // }
    // }
                $_SESSION['msg']['success'] = "Company Profile Updated Successfully.";
                header('location:view-pharmacy-profile.php');exit;
  }else{
    $_SESSION['msg']['fail'] = "Company Profile Updated Failed.";
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
  <style>
      .dropify-wrapper{
          height: 114px;
      }
  </style>
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
            
           
             <!-- Pharmacy Profile Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Company Profile</h4>
                  <hr class="alert-dark">
                  <br>
                  <form class="" method="POST" enctype="multipart/form-data" autocomplete="off">
                  <h5 class="card-title">Company Details</h5>
                  <hr>
                  
                    <div class="form-group row">
                    
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Company Name<span class="text-danger">*</span></label>
                    <input name="pharmacy_name" value="<?php echo (isset($data['pharmacy_name'])) ? $data['pharmacy_name'] : ''; ?>" type="text" required class="form-control" id="companyname" placeholder="Pharmacy Name">
                     <ul class="parsley-errors-list filled" id="nameget" style="display: none;">
                          <li class="parsley-required">Comapny name is already exist.</li>
                        </ul>
                    </div>
                    
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Contact Person Name</label>
                    <input type="text" name="contact_person_name" value="<?php echo (isset($data['contact_person_name'])) ? $data['contact_person_name'] : ''; ?>" class="form-control" id="exampleInputName1" placeholder="Contact Person Name">
                    </div>
                    
                    <?php  if(isset($data['logo_url'])){
                        $url_img = "http://digibooks.cloud/product/wholesale/logo_images/".$data['logo_url']; 
                        } ?>
                        <div class="col-md-4">
                            <label for="article_image">Logo <span class="text-danger">*</span></label>
                            <input type="file" name="company_logo" id="company_logo" class="dropify"
                                data-allowed-file-extensions="jpg png jpeg"  data-default-file="<?php echo (isset($url_img)) ? $url_img : ''; ?>"  data-show-remove="false" <?php echo (isset($url_img)) ? '': 'required'; ?>>
                        </div>
                    
                    
                    </div>
                    
                       <div class="form-group row">
                      <div class="col-12 col-md-4">
                            <label for="exampleInputName1">Select Firm<span class="text-danger">*</span></label>
                            <select name="firm_id" class="js-example-basic-single firm_name" required style="width:100%"> 
                            <option <?php if(isset($data['firm_name']) && $data['firm_name'] == "Regular"){echo "selected";} ?> value="Regular">GST registered- Regular</option>
                            <option <?php if(isset($data['firm_name']) && $data['firm_name'] == "Unregistered"){echo "selected";} ?> value="Unregistered">GST unregistered</option>
                            <option <?php if(isset($data['firm_name']) && $data['firm_name'] == "Composition"){echo "selected";} ?> value="Composition">GST registered- Composition</option>
                            <option <?php if(isset($data['firm_name']) && $data['firm_name'] == "Consumer"){echo "selected";} ?> value="Consumer">Consumer</option>
                            <option <?php if(isset($data['firm_name']) && $data['firm_name'] == "Overseas"){echo "selected";} ?> value="Overseas">Overseas</option>
                            <option <?php if(isset($data['firm_name']) && $data['firm_name'] == "SEZ"){echo "selected";} ?> value="SEZ">SEZ</option>
                            <option <?php if(isset($data['firm_name']) && $data['firm_name'] == "Deemed"){echo "selected";} ?> value="Deemed">Deemed exports- EOU's, STP's EHTP's etc</option>
                            </select>
                        </div>
                        
                     <div class="col-12 col-md-4">
                        <label for="exampleInputName1">GST No<span class="text-danger">*</span></label>
                        <input type="text" name="gst_no" required class="form-control gst_no" id="gst_no" value="<?php echo (isset($data['gst_no'])) ? $data['gst_no'] : ''; ?>" placeholder="GST No" data-parsley-pattern="^([0]{1}[1-9]{1}|[1-2]{1}[0-9]{1}|[3]{1}[0-7]{1})([a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}[1-9a-zA-Z]{1}[zZ]{1}[0-9a-zA-Z]{1})+$" data-parsley-pattern-message="Enter valid GST No." maxlength="15">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">PAN No</label>
                        <input type="text" name="pan_no" class="form-control" id="pan_no" value="<?php echo (isset($data['pan_no'])) ? $data['pan_no'] : ''; ?>" placeholder="PAN No"  readonly>
                        </div>
                        
                        
                        
                        
                    
                    </div>
                    
                    <div class="form-group row">
                       
                       <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Adhar No</label>
                        <input type="text" name="adhar_no" class="form-control onlynumber" id="adhar_no" value="<?php echo (isset($data['adhar_no'])) ? $data['adhar_no'] : ''; ?>" placeholder="Adhar No"  data-parsley-pattern="^\d{4}\d{4}\d{4}$" data-parsley-pattern-message="Enter valid Adhar No." >
                        </div>
                        
                       <div class="col-12 col-md-4">
                        <label for="exampleInputName1">DL No</label>
                        <input type="text" name="dl_no1" value="<?php echo (isset($data['dl_no1'])) ? $data['dl_no1'] : ''; ?>" class="form-control" id="exampleInputName1" placeholder="DL No">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">DL No</label>
                        <input type="text" name="dl_no2" class="form-control" value="<?php echo (isset($data['dl_no2'])) ? $data['dl_no2'] : ''; ?>" id="exampleInputName1" placeholder="DL No">
                        </div>
                        
                         
                        
                        
                       
                    
                    </div>
                    
                    <div class="form-group row">
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Drug Lic. Ex. Date</label>
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
                        
                        <div class="col-12 col-md-4">
                             <label for="renewal">Drug Lic. Renewal Date</label>
                              <select style="width: 100%" class="form-control js-example-basic-single" name="drug_lic_renewal_date" id="renewal_date" data-parsley-errors-container="#error-licrenewal">
                                  <option value="">Select Renewal Date</option>
                                  <option <?php if(isset($data['drug_lic_renewal_date']) && $data['drug_lic_renewal_date'] == '1month'){echo "selected";}?> value="1month">1 month before</option>
                                  <option <?php if(isset($data['drug_lic_renewal_date']) && $data['drug_lic_renewal_date'] == '2month'){echo "selected";}?> value="1month">2 month before</option>
                                </select>
                                <span id="error-licrenewal"></sapn>
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Shop Act License</label>
                        <input type="text" value="<?php echo (isset($data['shop_act_license'])) ?$data['shop_act_license'] : ''; ?>" name="shop_act_license" class="form-control" id="exampleInputName1" placeholder="Shop Act License">
                        </div>
                        
                        
                       
                       
                      
                        
                        
                    </div>
                    
                    
                     <div class="form-group row">
                         
                         <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Shop act - Exp. Date</label>
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
                        
                          <div class="col-12 col-md-4">
                         <label for="renewal">Shop act - renewal Date</label>
                          <select style="width: 100%" class="form-control js-example-basic-single" name="shop_act_renewal_date" id="shop_act_renewal_date" data-parsley-errors-container="#error-act">
                              <option value="">Select Renewal Date</option>
                              <option <?php if(isset($data['shop_act_renewal_date']) && $data['shop_act_renewal_date'] == '1month'){echo "selected";}?> value="1month">1 month before</option>
                              <option <?php if(isset($data['drug_lic_renewal_date']) && $data['drug_lic_renewal_date'] == '2month'){echo "selected";}?> value="1month">2 month before</option>
                            </select>
                            <span id="error-act"></sapn>
                        </div>
                        
                           <div class="col-12 col-md-4">
                        <label for="exampleInputName1">CIN No.</label>
                        <input type="text" value="<?php echo (isset($data['cin_no'])) ?$data['cin_no'] : ''; ?>" name="cin_no" class="form-control" id="exampleInputName1" placeholder="CIN No.">
                        </div>
                        
                        
                        
                     </div>
                    
                    
                    
                    
                    
                  <br>  
                  <h5 class="card-title">Contact Details</h5>
                  <hr>
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
                    <select style="width:100%" class="js-example-basic-single" required name="countryId" id="countryId" onChange="ownergetState(this.value);" required data-parsley-errors-container="#error-container">
                              <option value="">Select Country</option>
                              <?php
                                if(!isset($_REQUEST['id'])){
                                    $data['countryId'] = '101';
                                    $data['stateId'] = '12';
                                }
                              ?>
                                <?php 
                                    $st_qry = "SELECT * FROM own_countries" ;
                                    $states = mysqli_query($conn,$st_qry);
                                    while($row = mysqli_fetch_assoc($states)){
                                    ?>
                                <option <?php if(isset($data['countryId']) && $data['countryId'] == $row['id']){echo "selected";} ?> value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                <?php } ?>
                            </select>
                            <span id="error-container"></span>          
                    </div>
                    
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">State<span class="text-danger">*</span></label>
                    <select style="width: 100%" required class="form-control js-example-basic-single" name="stateId" id="allstatevalue" onChange="ownergetCity(this.value);" required data-parsley-errors-container="#error-state">
                              <option value="">Select State</option>
                              <?php 
                                    $st_qry = "SELECT * FROM own_states WHERE country_id = ".$data['countryId']; 
                                    $states = mysqli_query($conn,$st_qry);
                                    while($row = mysqli_fetch_assoc($states)){
                                    ?>
                             <option <?php if(isset($data['stateId']) && $data['stateId'] == $row['id']){echo "selected";} ?> value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                              <?php } ?>
                            </select>
                            <span id="error-state"></sapn>
                    </div>
                    
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">District</label>
                    <input type="text" name="district_name" class="form-control onlyalphabet" id="district" value="<?php echo (isset($data['district_name'])) ? $data['district_name'] : ''; ?>" placeholder="District">
                    </div>
                    
                    </div>
                    
                    <div class="form-group row">
                    <div class="col-12 col-md-4">
                    <label for="exampleInputName1">City<span class="text-danger">*</span></label>
                    <select style="width: 100%" required class="form-control js-example-basic-single" name="city_name" id="allcityvalue"  required data-parsley-errors-container="#error-city">
                              <option value="">Select City</option>
                              <?php 
                                    $ct_qry = "SELECT * FROM own_cities WHERE state_id = ".$data['stateId']; 
                                    $city = mysqli_query($conn,$ct_qry);
                                    while($row = mysqli_fetch_assoc($city)){
                                    ?>
                             <option <?php if(isset($data['city_name']) && $data['city_name'] == $row['id']){echo "selected";} ?> value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                              <?php } ?>
                            </select>
                            <span id="error-city"></sapn>
                    <!--<input type="text" name="city_name" required class="form-control" id="allcityvalue" value="<?php echo (isset($data['city_name'])) ? $data['city_name'] : ''; ?>" placeholder="City">-->
                    </div>
                    
                    <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Pincode<span class="text-danger">*</span></label>
                        <input data-parsley-type="number" type="text" name="pin_code" required class="form-control onlynumber" id="exampleInputName1" value="<?php echo (isset($data['pin_code'])) ? $data['pin_code'] : ''; ?>" placeholder="Pincode">
                    </div>
                    <!--<div class="col-12 col-md-4">-->
                    <!--    <label for="tin_no">TIN No</label>-->
                    <!--    <input data-parsley-type="number" type="text" name="tin_no" class="form-control onlynumber"  value="<?php/* echo (isset($data['tin_no'])) ? $data['tin_no'] : ''; */?>" placeholder="TIN No">-->
                    <!--</div>-->
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Email</label>
                        <input class="form-control" name="email" value="<?php echo (isset($data['email'])) ? $data['email'] :''; ?>"  data-inputmask="'alias': 'email'" />
                        </div>
                    
                    </div>
                    
                    <div class="form-group row">
                    
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Telephone No</label>
                        <input type="text" name="telephone_no" class="form-control" id="exampleInputName1" value="<?php echo (isset($data['telephone_no'])) ? $data['telephone_no'] : ''; ?>" placeholder="Telephone No" data-parsley-pattern-message="Enter valid Telephone No.">
                        </div>
                        
                        <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Mobile No<span class="text-danger">*</span></label>
                        <input data-parsley-type="number" type="text" name="mobile_no" required class="form-control onlynumber" id="exampleInputName1" value="<?php echo (isset($data['mobile_no'])) ? $data['mobile_no'] : ''; ?>" placeholder="Mobile No" data-parsley-length="[10, 10]" maxlength="10" data-parsley-length-message = "Mobile No should be 10 charatcers long.">
                        </div>
                        
                       
                    
                    </div>
                   
                    
                    
                  <!--<br>  -->
                  <!--<h5 class="card-title">Financial Details</h5>-->
                  <!--<hr>-->
                  
                
                    
                    <br>  
                    <h5 class="card-title">Settings</h5>
                    <hr>
                    
                    <div class="form-group row">
                        <!--<div class="col-12 col-md-4">
                          <label for="exampleInputName1">Logo</label>
                          <input type="file" value="" name="logo" class="file-upload-default">
                          <div class="input-group col-xs-12">
                              <input type="text" value="" class="form-control file-upload-info" disabled placeholder="Upload Image">
                              <span class="input-group-append">
                              <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                              </span>
                          </div>
                         </div>-->
                        <div class="col-12 col-md-4">
                         <label for="renewal">Print Type<span class="text-danger">*</span></label>
                          <select style="width: 100%" class="form-control js-example-basic-single" name="print_type" id="print_type" required data-parsley-errors-container="#error-a4">
                              <option value="">Select Print Type</option>
                              <!--a4 -> A4 Size
                              a42 -> A4 Size Half-->
                              <option <?php if(isset($data['print_type']) && $data['print_type'] == 'a4'){echo "selected";}?> value="a4">A4 Size</option>
                              <option <?php if(isset($data['print_type']) && $data['print_type'] == 'a42'){echo "selected";}?> value="a42">A4 Size Half</option>
                            </select>
                            <span id="error-a4"></sapn>
                        </div>
                        <div class="col-12 col-md-4">
                         <label for="renewal">Print On Letter pad<span class="text-danger">*</span></label>
                          <select style="width: 100%" class="form-control js-example-basic-single" name="letter_pad" id="letter_pad" required data-parsley-errors-container="#error-letter_pad">
                              <option value="">Select Print Type</option>
                              <!--a4 -> A4 Size
                              a42 -> A4 Size Half-->
                              <option <?php if(isset($data['letter_pad']) && $data['letter_pad'] == 'yes'){echo "selected";}?> value="yes">Yes</option>
                              <option <?php if(isset($data['letter_pad']) && $data['letter_pad'] == 'no'){echo "selected";}?> value="no">No</option>
                            </select>
                            <span id="error-letter_pad"></sapn>
                        </div>
                        <div class="col-12 col-md-4">
                             <label for="renewal">Closing stock Type<span class="text-danger">*</span></label>
                              <select style="width: 100%" class="form-control js-example-basic-single" name="close_type" id="close_type" required data-parsley-errors-container="#error-close_type">
                                  <option value="">Select Closing Stock</option>
                                  <option <?php if(isset($data['close_type']) && $data['close_type'] == 'LIFO'){echo "selected";}?> value="LIFO">LIFO</option>
                                  <option <?php if(isset($data['close_type']) && $data['close_type'] == 'FIFO'){echo "selected";}?> value="FIFO">FIFO</option>
                                  <option <?php if(isset($data['close_type']) && $data['close_type'] == 'AVERAGE'){echo "selected";}?> value="AVERAGE">Average</option>
                                  <option <?php if(isset($data['close_type']) && $data['close_type'] == 'ACTUAL'){echo "selected";}?> value="ACTUAL">Actual</option>
                                </select>
                                <span id="error-close_type"></sapn>
                            </div>
                    </div>
                   
                    <br>  
                    <h5 class="card-title">Finacial year Details</h5>
                    <hr>
                      <div class="form-group row">
                                <div class="col-12 col-md-4">
                                  <label for="financial_year">Financial year <span class="text-danger">*</span></label>
                                  <input type="text" class="form-control" name="financial_name" id="financial_year" placeholder="Financial year" value="<?php echo (isset($yeardetail['f_name'])) ? $yeardetail['f_name'] : ''; ?>" required="">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                    <div id="datepicker-popup1" class="input-group date datepicker">
                                        <?php 
                                            if(isset($_GET['id']) && $_GET['id'] != ''){
                                                $startdate_val = (isset($yeardetail['start_date']) && $yeardetail['start_date'] != '') ? date('d/m/Y',strtotime($yeardetail['start_date'])) : '';
                                            }else{
                                                $startdate_val = date('d/m/Y');
                                            }
                                        ?>
                                        <input type="text" value="<?php echo (isset($startdate_val)) ? $startdate_val : ''; ?>" name="start_date" class="form-control" data-parsley-errors-container="#error-startdate" required>
                                        <span class="input-group-addon input-group-append border-left">
                                            <span class="mdi mdi-calendar input-group-text"></span>
                                        </span>
                                    </div>
                                    <span id="error-startdate"></span>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="end_date">End Date <span class="text-danger">*</span></label>
                                    <div id="datepicker-popup2" class="input-group date datepicker">
                                        <?php
                                            if(isset($_GET['id']) && $_GET['id'] != ''){
                                                $enddate_val = (isset($yeardetail['end_date']) && $yeardetail['end_date'] != '') ? date('d/m/Y',strtotime($yeardetail['end_date'])) : '';
                                            }else{
                                                $enddate_val = date('d/m/Y');
                                            }
                                        ?>
                                        <input type="text" value="<?php echo (isset($enddate_val)) ? $enddate_val : ''; ?>" data-parsley-errors-container="#error-enddate" required name="end_date" class="form-control">
                                        <span class="input-group-addon input-group-append border-left">
                                            <span class="mdi mdi-calendar input-group-text"></span>
                                        </span>
                                    </div>
                                    <span id="error-enddate"></span>
                                </div>
                                
                            </div>
                    
                  <!--<br>-->
                    
                  <!--<h5 class="card-title">Pharmacist Details </h5>-->
                  
                  <!--<?php/* -->
                 
                  <!--  $count = count($sub_data);-->
                  <!--  $j=0;-->
                  <!--  */?>-->
                  <!--  <div class="pharmacist-detail-section">-->
                  <!--  <?php/*-->
                  <!--  foreach ($sub_data as $row) {-->
                  <!--  */?>-->
                  <!--  <div class="content-add-more">-->
                      
                  <!--      <?php/* -->
                  <!--      if($j >= 1){-->
                  <!--        */?>-->
                  <!--        <div class="row">-->
                  <!--          <div class="col-md-12">-->
                  <!--            <a class="btn btn-danger btn-sm pull-right btn-remove-product" style="color:#fff;"><i class="fa fa-plus"></i> Remove</a>-->
                  <!--          </div>-->
                  <!--        </div>-->
                  <!--        <?php/*-->
                  <!--      }-->
                  <!--      */?>-->
                  <!--      <hr>-->
                      
                  <!--      <div class="form-group row">-->
                  <!--          <input type="hidden" value="<?php/* echo $row['id']; */?>" name="id[]">-->
                  <!--          <div class="col-12 col-md-4">-->
                  <!--          <label for="exampleInputName1">Pharmacist Name<span class="text-danger">*</span></label>-->
                  <!--          <input type="text" required name="pharmacist_name[]" class="form-control" id="exampleInputName1" value="<?php echo (isset($row['pharmacist_name'])) ? $row['pharmacist_name'] : ''; ?>" placeholder="Pharmacist Name">-->
                  <!--          </div>-->
                            
                  <!--          <div class="col-12 col-md-4">-->
                  <!--          <label for="exampleInputName1">Pharmacist Reg. No<span class="text-danger">*</span></label>-->
                  <!--          <input type="text" required="" name="pharmacist_reg_no[]" class="form-control" id="exampleInputName1" value="<?php echo (isset($row['pharmacist_reg_no'])) ? $row['pharmacist_reg_no'] : ''; ?>" placeholder="Pharmacist Reg. No">-->
                  <!--          </div>-->
                            
                  <!--          <div class="col-12 col-md-4">-->
                  <!--          <label for="exampleInputName1">Pharmacist Reg. No Exp. Date<span class="text-danger">*</span></label>-->
                  <!--          <div id="" class="input-group date datepicker datepicker-popup">-->
                  <!--            <input type="text" value="<?php echo(isset($row['pharmacist_reg_date']) && $row['pharmacist_reg_date'] != '') ? date("d/m/Y",strtotime(str_replace("-","/",$row['pharmacist_reg_date']))) : ''; ?>" name="pharmacist_reg_date[]" class="form-control" placeholder="Pharmacist Reg. No Exp. Date">-->
                  <!--            <span class="input-group-addon input-group-append border-left">-->
                  <!--              <span class="mdi mdi-calendar input-group-text"></span>-->
                  <!--            </span>-->
                  <!--          </div>-->
                            <!-- <input type="text" required name="pharmacist_reg_date[]" class="form-control" id="exampleInputName1" placeholder="Pharmacist Reg. No Exp. Date"> -->
                  <!--          </div>-->
                  <!--      </div>-->
                  <!--      <div class="form-group row">-->
                  <!--          <div class="col-12 col-md-4">-->
                  <!--          <label for="exampleInputName1">Address</label>-->
                  <!--          <input type="text" name="p_address1[]" class="form-control" id="exampleInputName1" value="<?php echo (isset($row['p_address1'])) ? $row['p_address1'] : ''; ?>" placeholder="Address line1">-->
                  <!--          </div>-->
                  <!--          <div class="col-12 col-md-4">-->
                  <!--          <label for="exampleInputName1">&nbsp;</label>-->
                  <!--          <input type="text" name="p_address2[]" class="form-control" id="exampleInputName1" value="<?php echo (isset($row['p_address2'])) ? $row['p_address2'] : ''; ?>" placeholder="Address line2">-->
                  <!--          </div>-->
                            
                  <!--          <div class="col-12 col-md-4">-->
                  <!--          <label for="exampleInputName1">&nbsp;</label>-->
                  <!--          <input type="text" name="p_address3[]" class="form-control" id="exampleInputName1" value="<?php echo (isset($row['p_address3'])) ? $row['p_address3'] : ''; ?>" placeholder="Address line3">-->
                  <!--          </div>-->
                  <!--      </div>-->
                  <!--      <div class="form-group row">-->
                        
                  <!--          <div class="col-12 col-md-4">-->
                  <!--          <label for="exampleInputName1">City<span class="text-danger">*</span></label>-->
                  <!--          <input type="text" required name="city[]" class="form-control" id="exampleInputName1" value="<?php echo (isset($row['city'])) ? $row['city'] : ''; ?>" placeholder="City">-->
                  <!--          </div>-->
                            
                  <!--           <div class="col-12 col-md-4">-->
                  <!--          <label for="exampleInputName1">Contact No</label>-->
                  <!--          <input type="text" name="contact_no[]" class="form-control onlynumber" id="exampleInputName1" value="<?php echo (isset($row['contact_no'])) ? $row['contact_no'] : ''; ?>" placeholder="Contact No">-->
                  <!--          </div>-->
                            
                  <!--           <div class="col-12 col-md-4">-->
                  <!--          <label for="exampleInputName1">Photo</label>-->
                  <!--          <input type="file" value="<?php echo (isset($row['img'])) ? $row['img'] : ''; ?>" name="img[]" class="file-upload-default">-->
                  <!--          <div class="input-group col-xs-12">-->
                  <!--          <input type="text" value="<?php echo(isset($row['img'])) ? $row['img'] : ''; ?>" class="form-control file-upload-info" disabled placeholder="Upload Image">-->
                  <!--          <span class="input-group-append">-->
                  <!--            <button class="file-upload-browse btn btn-info" type="button">Upload</button>-->
                  <!--          </span>-->
                  <!--      </div>-->
                  <!--      </div>-->
                  <!--      <input type="hidden" name="update_img[]" value="<?php echo(isset($row['img'])) ? $row['img'] : ''; ?>">-->
                  <!--      </div>-->
                  <!--      <div class="form-group row">-->
                        
                  <!--          <div class="col-12 col-md-4">-->
                  <!--          <label for="exampleInputName1">Aadhar Card No<span class="text-danger">*</span></label>-->
                  <!--          <input type="text" required name="aadhar_card_no[]" class="form-control onlynumber" id="exampleInputName1" value="<?php echo (isset($row['aadhar_card_no'])) ? $row['aadhar_card_no'] : ''; ?>" placeholder="Aadhar Card No" data-parsley-length="[12, 12]" data-parsley-length-message = "Enter valid adhar card no.">-->
                  <!--          </div>-->
                            
                  <!--          <div class="col-12 col-md-4">-->
                  <!--          <label for="exampleInputName1">Exp. Date</label>-->
                  <!--          <div id="" class="input-group date datepicker datepicker-popup">-->
                  <!--            <input type="text" value="<?php echo (isset($row['p_exp_date']) && $row['p_exp_date'] != '') ? date("d/m/Y",strtotime(str_replace("-","/",$row['p_exp_date']))) : ''; ?>" name="p_exp_date[]" class="form-control" placeholder="Exp.Date">-->
                  <!--            <span class="input-group-addon input-group-append border-left">-->
                  <!--              <span class="mdi mdi-calendar input-group-text"></span>-->
                  <!--            </span>-->
                  <!--          </div>-->
                            <!-- <input type="text" name="p_exp_date[]" class="form-control" id="exampleInputName1" placeholder="Exp.Date"> -->
                  <!--          </div>-->
                  <!--      </div> -->
                      
                  <!--  </div>-->
                  <!--  <?php/* $j++; } */?>-->
                  <!--  </div>-->
                    
                 <!--   <div class="row">-->
                 <!--<div class="col pt-1">-->
                 <!--	<a class="btn btn-primary btn-sm btn-addmore-product" style="color:#fff;"><i class="fa fa-plus"></i> Add more</a>-->
                 <!--</div>   -->
                 <!--</div>-->
                 
                 

                  <!--<div>&nbsp;</div>      -->
                  <!--<h5 class="card-title">Bank Details</h5>-->
                  
                  <!--<?php /*
                  <!--  if(isset($_GET['id'])){-->
                  <!--    $number = count($bank_data);-->
                  <!--    $y = 0;-->
                  <!--*/?>-->

                    <!--<div class="bank-details-section">-->
                   

                  <!--<div class="content-add-more">-->

                 
                  <!--<hr>  -->
                  <!--    <div class="form-group row">-->
                  <!--      <div class="col-12 col-md-4">-->
                  <!--      <label for="exampleInputName1">Bank Name<span class="text-danger">*</span></label>-->
                  <!--      <input type="text" name="bank[]" required class="form-control" id="exampleInputName1" placeholder="Bank Name" value="<?php echo (isset($bankrow['bank_name'])) ? $bankrow['bank_name'] : ''; ?>">-->
                  <!--      </div>-->
                        
                  <!--      <div class="col-12 col-md-4">-->
                  <!--      <label for="exampleInputName1">IFSC Code<span class="text-danger">*</span></label>-->
                  <!--      <input type="text" name="ifsc[]" required class="form-control" id="exampleInputName1" placeholder="IFSC" value="<?php echo (isset($bankrow['ifsc_code'])) ? $bankrow['ifsc_code'] : ''; ?>">-->
                  <!--      </div>-->
                        
                  <!--      <div class="col-12 col-md-4">-->
                  <!--      <label for="exampleInputName1">Account Number<span class="text-danger">*</span></label>-->
                  <!--      <input type="text" name="account[]" required class="form-control onlynumber" id="exampleInputName1" placeholder="Account Number" value="<?php echo (isset($bankrow['account_number'])) ? $bankrow['account_number'] : ''; ?>">-->
                  <!--      </div>-->
                  <!--    </div>-->

                  <!--    <div class="form-group row">  -->
                  <!--      <div class="col-12 col-md-4">-->
                  <!--      <label for="exampleInputName1">Bank Address</label>-->
                  <!--      <input type="text" name="bank_address[]" class="form-control" id="exampleInputName1" placeholder="Account Number" value="<?php echo (isset($bankrow['bank_address'])) ? $bankrow['bank_address'] : ''; ?>">-->
                  <!--      </div>-->

                  <!--      <div class="col-12 col-md-4">-->
                  <!--      <label for="exampleInputName1">Opening Balance</label>-->
                  <!--      <input type="text" name="opening[]" class="form-control" id="exampleInputName1" placeholder="Account Number" value="<?php echo (isset($bankrow['opening_balance'])) ? $bankrow['opening_balance'] : ''; ?>">-->
                  <!--      </div>-->

                  <!--      <div class="col-12 col-md-4">-->
                  <!--        <label for="exampleInputName1">Opening Balance Type</label>-->
                  <!--        <select class="form-control" name="type[]" style="width:100%"> -->
                  <!--          <option value="CR" <?php// echo (isset($bankrow['opening_balance_type']) && $bankrow['opening_balance_type'] == "CR") ? 'selected': '';?>>CR </option>-->
                  <!--          <option value="DB" <?php// echo (isset($bankrow['opening_balance_type']) && $bankrow['opening_balance_type'] == "DB") ? 'selected': '';?>>DB </option>-->
                  <!--      </select>-->
                  <!--      </div>-->
                  <!--    </div>  -->
                  <!--</div>-->
                  

                 <!--   <div class="pharmacist-detail-section">-->
                 <!--     <hr>-->
                    
                 <!--     <div class="form-group row">-->
                      
                 <!--         <div class="col-12 col-md-4">-->
                 <!--         <label for="exampleInputName1">Pharmacist Name<span class="text-danger">*</span></label>-->
                 <!--         <input type="text" required name="pharmacist_name[]" class="form-control" id="exampleInputName1" placeholder="Pharmacist Name">-->
                 <!--         </div>-->
                          
                 <!--         <div class="col-12 col-md-4">-->
                 <!--         <label for="exampleInputName1">Pharmacist Reg. No<span class="text-danger">*</span></label>-->
                 <!--         <input type="text" required="" name="pharmacist_reg_no[]" class="form-control" id="exampleInputName1" placeholder="Pharmacist Reg. No">-->
                 <!--         </div>-->
                          
                 <!--         <div class="col-12 col-md-4">-->
                 <!--         <label for="exampleInputName1">Pharmacist Reg. No Exp. Date<span class="text-danger">*</span></label>-->
                 <!--         <div id="" class="input-group date datepicker datepicker-popup">-->
                 <!--           <input type="text" value="" name="pharmacist_reg_date[]" class="form-control" placeholder="Pharmacist Reg. No Exp. Date">-->
                 <!--           <span class="input-group-addon input-group-append border-left">-->
                 <!--             <span class="mdi mdi-calendar input-group-text"></span>-->
                 <!--           </span>-->
                 <!--         </div>-->
                          <!-- <input type="text" required name="pharmacist_reg_date[]" class="form-control" id="exampleInputName1" placeholder="Pharmacist Reg. No Exp. Date"> -->
                 <!--         </div>-->
                 <!--     </div>-->
                 <!--     <div class="form-group row">-->
                 <!--         <div class="col-12 col-md-4">-->
                 <!--         <label for="exampleInputName1">Address</label>-->
                 <!--         <input type="text" name="p_address1[]" class="form-control" id="exampleInputName1" placeholder="Address line1">-->
                 <!--         </div>-->
                 <!--         <div class="col-12 col-md-4">-->
                 <!--         <label for="exampleInputName1">&nbsp;</label>-->
                 <!--         <input type="text" name="p_address2[]" class="form-control" id="exampleInputName1" placeholder="Address line2">-->
                 <!--         </div>-->
                          
                 <!--         <div class="col-12 col-md-4">-->
                 <!--         <label for="exampleInputName1">&nbsp;</label>-->
                 <!--         <input type="text" name="p_address3[]" class="form-control" id="exampleInputName1" placeholder="Address line3">-->
                 <!--         </div>-->
                 <!--     </div>-->
                 <!--     <div class="form-group row">-->
                      
                 <!--         <div class="col-12 col-md-4">-->
                 <!--         <label for="exampleInputName1">City<span class="text-danger">*</span></label>-->
                 <!--         <input type="text" required name="city[]" class="form-control" id="exampleInputName1" placeholder="City">-->
                 <!--         </div>-->
                          
                 <!--          <div class="col-12 col-md-4">-->
                 <!--         <label for="exampleInputName1">Contact No</label>-->
                 <!--         <input type="text" name="contact_no[]" class="form-control onlynumber" id="exampleInputName1" placeholder="Contact No">-->
                 <!--         </div>-->
                          
                 <!--          <div class="col-12 col-md-4">-->
                 <!--         <label for="exampleInputName1">Photo</label>-->
                 <!--         <input type="file" name="img[]" class="file-upload-default">-->
                 <!--       	<div class="input-group col-xs-12">-->
                 <!--         <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">-->
                 <!--         <span class="input-group-append">-->
                 <!--           <button class="file-upload-browse btn btn-info" type="button">Upload</button>-->
                 <!--         </span>-->
                 <!--     </div>-->
                 <!--     </div>-->
                      
                 <!--     </div>-->
                 <!--     <div class="form-group row">-->
                      
                 <!--         <div class="col-12 col-md-4">-->
                 <!--         <label for="exampleInputName1">Aadhar Card No<span class="text-danger">*</span></label>-->
                 <!--         <input type="text" required name="aadhar_card_no[]" class="form-control onlynumber" id="exampleInputName1" placeholder="Aadhar Card No" data-parsley-length="[12, 12]" data-parsley-length-message = "Enter valid adhar card no.">-->
                 <!--         </div>-->
                          
                 <!--         <div class="col-12 col-md-4">-->
                 <!--         <label for="exampleInputName1">Exp. Date</label>-->
                 <!--         <div id="" class="input-group date datepicker datepicker-popup">-->
                 <!--           <input type="text" value="" name="p_exp_date[]" class="form-control" placeholder="Exp.Date">-->
                 <!--           <span class="input-group-addon input-group-append border-left">-->
                 <!--             <span class="mdi mdi-calendar input-group-text"></span>-->
                 <!--           </span>-->
                 <!--         </div>-->
                          <!-- <input type="text" name="p_exp_date[]" class="form-control" id="exampleInputName1" placeholder="Exp.Date"> -->
                 <!--         </div>-->
                 <!--     </div> -->
                 <!--   </div>-->
                    
                 <!--   <div class="row">-->
                 <!--<div class="col pt-1">-->
                 <!--	<a class="btn btn-primary btn-sm btn-addmore-product" style="color:#fff;"><i class="fa fa-plus"></i> Add more</a>-->
                 <!--</div>   -->
                 <!--</div>  -->
                  
                  <!--<div>&nbsp;</div>-->
                 <!--<h5 class="card-title">Bank Details</h5>  -->
                  <!--  <div class="bank-details-section">-->
                  <!--  <hr>  -->

                  <!--  <div class="form-group row">-->
                    
                  <!--      <div class="col-12 col-md-4">-->
                  <!--      <label for="exampleInputName1">Bank Name<span class="text-danger">*</span></label>-->
                  <!--      <input type="text" name="bank[]" required class="form-control" id="exampleInputName1" placeholder="Bank Name">-->
                  <!--      </div>-->
                        
                  <!--      <div class="col-12 col-md-4">-->
                  <!--      <label for="exampleInputName1">IFSC Code<span class="text-danger">*</span></label>-->
                  <!--      <input type="text" name="ifsc[]" required class="form-control" id="exampleInputName1" placeholder="IFSC">-->
                  <!--      </div>-->
                        
                  <!--      <div class="col-12 col-md-4">-->
                  <!--      <label for="exampleInputName1">Account Number<span class="text-danger">*</span></label>-->
                  <!--      <input type="text" name="account[]" required class="form-control onlynumber" id="exampleInputName1" placeholder="Account Number">-->
                  <!--      </div>-->
                  <!--  </div>-->

                  <!--  <div class="forem-group row">    -->
                  <!--      <div class="col-12 col-md-4">-->
                  <!--      <label for="exampleInputName1">Bank Address</label>-->
                  <!--      <input type="text" name="bank_address[]" class="form-control" id="exampleInputName1" placeholder="Bank Address">-->
                  <!--      </div>-->

                  <!--      <div class="col-12 col-md-4">-->
                  <!--      <label for="exampleInputName1">Opening Balance</label>-->
                  <!--      <input type="text" name="opening[]" class="form-control onlynumber" id="exampleInputName1" placeholder="Opening Balance">-->
                  <!--      </div>-->

                  <!--      <div class="col-12 col-md-4">-->
                  <!--        <label for="exampleInputName1">Opening Balance Type</label>-->
                  <!--        <select class="form-control" name="type[]" style="width:100%"> -->
                  <!--          <option value="CR">CR</option>-->
                  <!--          <option value="DB">DB</option>-->
                  <!--      </select>-->
                  <!--      </div>-->
                  <!--  </div>-->
                  <!--</div>-->
                 
                  <div>&nbsp;</div>  
                 
                 <!--<div class="row">-->
                 <!--<div class="col pt-1">-->
                 <!--	<a class="btn btn-primary btn-sm btn-addmore-bank" style="color:#fff;"><i class="fa fa-plus"></i> Add more</a>-->
                 <!--</div>   -->
                 <!--</div>-->
                      
                    <br>
                    <a href="view-pharmacy-profile.php" class="btn btn-light pull-left">Back</a>
                    <?php 
                    if(isset($_GET['id'])){
                      ?>
                    <button type="submit" name="update" class="btn btn-success mr-2 pull-right">Update</button>
                      <?php
                    }else{
                    ?>
                    <button type="submit" name="submit" id="submit" class="btn btn-success mr-2 pull-right">Submit</button>
                    <?php } ?>

                    
                    
                  </form>
                </div>
              </div>
            </div>
            
  
            
          </div>
        </div>
        <!--<div id="copy_html" class="" style="display: none">-->
        <!--          <div class="content-add-more">-->
        <!--          <hr>-->
        <!--          <div class="row">-->
        <!--            <div class="col-md-12">-->
        <!--              <a class="btn btn-danger btn-sm pull-right btn-remove-product" style="color:#fff;"><i class="fa fa-plus"></i> Remove</a>-->
        <!--            </div>-->
        <!--          </div>-->
        <!--            <div class="form-group row">-->
                    
        <!--                <div class="col-12 col-md-4">-->
        <!--                <label for="exampleInputName1">Pharmacist Name<span class="text-danger">*</span></label>-->
        <!--                <input type="text" name="pharmacist_name[]" required class="form-control" id="exampleInputName1" placeholder="Pharmacist Name">-->
        <!--                </div>-->
                        
        <!--                <div class="col-12 col-md-4">-->
        <!--                <label for="exampleInputName1">Pharmacist Reg. No<span class="text-danger">*</span></label>-->
        <!--                <input type="text" name="pharmacist_reg_no[]" required class="form-control" id="exampleInputName1" placeholder="Pharmacist Reg. No">-->
        <!--                </div>-->
                        
        <!--                <div class="col-12 col-md-4">-->
        <!--                <label for="exampleInputName1">Pharmacist Reg. No Exp. Date<span class="text-danger">*</span></label>-->
        <!--                <div id="" class="input-group date datepicker datepicker-popup">-->
        <!--                  <input type="text" value="" name="pharmacist_reg_date[]" class="form-control" placeholder="Pharmacist Reg. No Exp. Date">-->
        <!--                  <span class="input-group-addon input-group-append border-left">-->
        <!--                    <span class="mdi mdi-calendar input-group-text"></span>-->
        <!--                  </span>-->
        <!--                </div>-->
        <!--                </div>-->
                    
        <!--            </div>-->
                    
        <!--            <div class="form-group row">-->
        <!--                <div class="col-12 col-md-4">-->
        <!--                <label for="exampleInputName1">Address</label>-->
        <!--                <input type="text" name="p_address1[]" class="form-control" id="exampleInputName1" placeholder="Address line1">-->
        <!--                </div>-->
        <!--                <div class="col-12 col-md-4">-->
        <!--                <label for="exampleInputName1">&nbsp;</label>-->
        <!--                <input type="text" name="p_address2[]" class="form-control" id="exampleInputName1" placeholder="Address line2">-->
        <!--                </div>-->
                        
        <!--                <div class="col-12 col-md-4">-->
        <!--                <label for="exampleInputName1">&nbsp;</label>-->
        <!--                <input type="text" name="p_address3[]" class="form-control" id="exampleInputName1" placeholder="Address line3">-->
        <!--                </div>-->
        <!--            </div>-->
                    
        <!--            <div class="form-group row">-->
                    
        <!--                <div class="col-12 col-md-4">-->
        <!--                <label for="exampleInputName1">City<span class="text-danger">*</span></label>-->
        <!--                <input type="text" name="city[]" required class="form-control" id="exampleInputName1" placeholder="City">-->
        <!--                </div>-->
                        
        <!--                 <div class="col-12 col-md-4">-->
        <!--                <label for="exampleInputName1">Contact No</label>-->
        <!--                <input type="text" name="contact_no[]" class="form-control onlynumber" id="exampleInputName1" placeholder="Contact No">-->
        <!--                </div>-->
                        
        <!--                 <div class="col-12 col-md-4">-->
        <!--                <label for="exampleInputName1">Photo</label>-->
        <!--                <input type="file" name="img[]" class="file-upload-default">-->
        <!--                <div class="input-group col-xs-12">-->
        <!--                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">-->
        <!--                <span class="input-group-append">-->
        <!--                  <button class="file-upload-browse btn btn-info" type="button">Upload</button>-->
        <!--                </span>-->
        <!--              </div>-->
        <!--                </div>-->
                    
        <!--            </div>-->
                    
        <!--            <div class="form-group row">-->
                    
        <!--                <div class="col-12 col-md-4">-->
        <!--                <label for="exampleInputName1">Aadhar Card No<span class="text-danger">*</span></label>-->
        <!--                <input type="text" name="aadhar_card_no[]" required class="form-control onlynumber" id="exampleInputName1" placeholder="Aadhar Card No" data-parsley-length="[12, 12]" data-parsley-length-message = "Enter valid adhar card no.">-->
        <!--                </div>-->
                        
        <!--                <div class="col-12 col-md-4">-->
        <!--                <label for="exampleInputName1">Exp. Date</label>-->
        <!--                <div id="" class="input-group date datepicker datepicker-popup">-->
        <!--                  <input type="text" value="" name="p_exp_date[]" class="form-control" placeholder="Exp.Date">-->
        <!--                  <span class="input-group-addon input-group-append border-left">-->
        <!--                    <span class="mdi mdi-calendar input-group-text"></span>-->
        <!--                  </span>-->
        <!--                </div>-->
                        <!-- <input type="text" name="p_exp_date[]" class="form-control" id="exampleInputName1" placeholder="Exp. Date"> -->
        <!--                </div>-->
                    
        <!--            </div> -->
        <!--            </div>-->
        <!--         </div> -->
                 
                 <!--<div id="bank_html" class="" style="display: none">-->
                 <!--   <div class="content-add-bank">-->
                 <!--   <hr>   -->
                 <!--   <div class="row">-->
                 <!--     <div class="col-md-12">-->
                 <!--       <a class="btn btn-danger btn-sm pull-right btn-remove-bank" style="color:#fff;"><i class="fa fa-plus"></i> Remove</a>-->
                 <!--     </div>-->
                 <!--   </div>-->

                  <!--  <div class="form-group row">-->
                    
                  <!--      <div class="col-12 col-md-4">-->
                  <!--      <label for="exampleInputName1">Bank Name<span class="text-danger">*</span></label>-->
                  <!--      <input type="text" name="bank[]" required class="form-control" id="exampleInputName1" placeholder="Bank Name">-->
                  <!--      </div>-->
                        
                  <!--      <div class="col-12 col-md-4">-->
                  <!--      <label for="exampleInputName1">IFSC Code<span class="text-danger">*</span></label>-->
                  <!--      <input type="text" name="ifsc[]" required class="form-control" id="exampleInputName1" placeholder="IFSC">-->
                  <!--      </div>-->
                        
                  <!--      <div class="col-12 col-md-4">-->
                  <!--      <label for="exampleInputName1">Account Number<span class="text-danger">*</span></label>-->
                  <!--      <input type="text" name="account[]" required class="form-control onlynumber" id="exampleInputName1" placeholder="Account Number">-->
                  <!--      </div>-->
                  <!--  </div>-->

                  <!--  <div class="forem-group row">    -->
                  <!--      <div class="col-12 col-md-4">-->
                  <!--      <label for="exampleInputName1">Bank Address</label>-->
                  <!--      <input type="text" name="bank_address[]" class="form-control" id="exampleInputName1" placeholder="Bank Address">-->
                  <!--      </div>-->

                  <!--      <div class="col-12 col-md-4">-->
                  <!--      <label for="exampleInputName1">Opening Balance</label>-->
                  <!--      <input type="text" name="opening[]" class="form-control onlynumber" id="exampleInputName1" placeholder="Opening Balance">-->
                  <!--      </div>-->

                  <!--      <div class="col-12 col-md-4">-->
                  <!--        <label for="exampleInputName1">Opening Balance Type</label>-->
                  <!--        <select class="form-control" name="type[]" style="width:100%"> -->
                  <!--          <option value="CR">CR</option>-->
                  <!--          <option value="DB">DB</option>-->
                  <!--        </select>-->
                  <!--      </div>-->
                  <!--  </div>-->

                    
                  <!--</div>-->

                  <!--</div>    -->
                 
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
    
     $('.date').datepicker({
      enableOnReadonly: true,
      autoclose : true,
      todayHighlight: true,
      format: 'dd/mm/yyyy'
    });
 </script>
 <script>
  $('body').on('change keyup past', '#gst_no ', function () {
      var gstno = $(this).val();
   var pan = gstno.substring(2,12);
   $('#pan_no').val(pan);
   
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
  function ownergetCity(state){
  var that = $(this);
    var id =  state;
    if(id!="")
    {
      var action = "ownergetCityDetails"; 
       $.ajax({
         type: "POST",
         url: "ajax.php",
         dataType: 'json',  
         data: {action:action , id:id},
         success: function(data){
        if(data.error == 0)
        { 
          $("#allcityvalue").html(data.html);  
     
        }
      },
       error : function(data) {  that.parent().parent().siblings('.subCategories').html(""); }
       });
    }
  }
    </script>
  
  <script>
   $("#submit").prop('disabled', true);
      $("#companyname").keyup(function(){
  var companyname = $(this).val();
   var action = "getcomanyname"; 
   $.ajax({
    type:"POST",
     url: "ajax.php",
     data : {action:action ,companyname:companyname},
     success: function(data){
         console.log(data);
    if(data == "existing"){
      $("#nameget").show();
     $("#submit").prop('disabled', true);
    }else if(data == "notexisting"){
      $("#nameget").hide();
      $("#submit").prop('disabled', false);
    }
   }
   });
});
  </script>
  <script>
    $(".firm_name").change(function(){
	    var firm_name = $(this).val();
	    $(".gst_no").removeAttr('readonly');
	
	    $(".gst_no").prop('required',true);
	    if(firm_name == "Unregistered"){
	        $(".gst_no").attr('readonly', true);
	        $(".gst_no").removeAttr('required');
	    }
	    if(firm_name == "Consumer"){
	        $(".gst_no").attr('readonly', true);
	        $(".gst_no").removeAttr('required');
	    }
	    if(firm_name == "Overseas"){
	        $(".gst_no").attr('readonly', true);
	        $(".gst_no").removeAttr('required');
	    }
	});  
  </script>
  <script type="text/javascript">
    // $('body').on('click', '.btn-addmore-product', function() {
    //     var totalproduct = $('.product-tr').length;//for product length
    //     var html = $('#copy_html').html();
        
    //     $('.pharmacist-detail-section').append(html);
    //     $('.datepicker-popup').datepicker({
    //       enableOnReadonly: true,
    //       autoclose : true,
    //       todayHighlight: true,
    //       format: 'dd/mm/yyyy'
    //     });
    //     (function($) {
    //         'use strict';
    //         $(function() {
    //           $('.file-upload-browse').on('click', function() {
    //             var file = $(this).parent().parent().parent().find('.file-upload-default');
    //             file.trigger('click');
    //           });
    //           $('.file-upload-default').on('change', function() {
    //             $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
    //           });
    //         });
    //       })(jQuery);
    // });

    // $('body').on('click', '.btn-remove-product', function(e) {
    //     e.preventDefault();
    //     $(this).closest ('.content-add-more').remove ();
        
    // });
    
    // $('body').on('click', '.btn-addmore-bank', function() {
    //     //var totalproduct = $('.product-tr').length;//for product length
    //     var html = $('#bank_html').html();
        
    //     $('.bank-details-section').append(html);
    // });

    // $('body').on('click', '.btn-remove-bank', function(e) {
    //     e.preventDefault();
    //     $(this).closest ('.content-add-bank').remove ();
        
    // });
  </script>
  <!-- script for custom validation -->
<script src="js/custom/onlynumber.js"></script>
<script src="js/custom/onlyalphabet.js"></script>
<script src="js/parsley.min.js"></script>
 <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
<script type="text/javascript">
  $('form').parsley({
    excluded:':hidden'
  });
</script>
</body>


</html>
