<?php include('include/usertypecheck.php'); ?>

<?php
  /* START CODE FOR DATA INSERT AND UPDATE  */
  if(isset($_POST['submit'])){
    
    $data['account_type'] = (isset($_POST['account_type'])) ? $_POST['account_type'] : '';
    $data['companyname'] = (isset($_POST['companyname'])) ? $_POST['companyname'] : '';
    $data['name'] = (isset($_POST['name'])) ? $_POST['name'] : '';
    $data['designation'] = (isset($_POST['designation'])) ? $_POST['designation'] : '';
    $data['mobile'] = (isset($_POST['mobile'])) ? $_POST['mobile'] : '';
    $data['phone'] = (isset($_POST['phone'])) ? $_POST['phone'] : '';
    $data['email'] = (isset($_POST['email'])) ? $_POST['email'] : '';
    $data['addressline1'] = (isset($_POST['addressline1'])) ? $_POST['addressline1'] : '';
    $data['addressline2'] = (isset($_POST['addressline2'])) ? $_POST['addressline2'] : '';
    $data['addressline3'] = (isset($_POST['addressline3'])) ? $_POST['addressline3'] : '';
    $data['country'] = (isset($_POST['country'])) ? $_POST['country'] : '';
    $data['state'] = (isset($_POST['state'])) ? $_POST['state'] : '';
    $data['district'] = (isset($_POST['district'])) ? $_POST['district'] : '';
    $data['city'] = (isset($_POST['city'])) ? $_POST['city'] : '';
    $data['pincode'] = (isset($_POST['pincode'])) ? $_POST['pincode'] : '';
    $data['faxno'] = (isset($_POST['faxno'])) ? $_POST['faxno'] : '';
    $data['opening_balance'] = (isset($_POST['opening_balance']) && $_POST['opening_balance'] != '') ? $_POST['opening_balance'] : 0;
    $data['opening_balance_type'] = (isset($_POST['opening_balance_type'])) ? $_POST['opening_balance_type'] : 'DR';
    $data['group_id'] = (isset($_GET['subtype'])) ? $_GET['subtype'] : '';
    $data['panno'] = (isset($_POST['panno'])) ? $_POST['panno'] : '';
    $data['gstno'] = (isset($_POST['gstno'])) ? $_POST['gstno'] : '';
    $data['bank_name'] = (isset($_POST['bank_name'])) ? $_POST['bank_name'] : '';
    $data['bank_ac_no'] = (isset($_POST['bank_ac_no'])) ? $_POST['bank_ac_no'] : '';
    $data['branch_name'] = (isset($_POST['branch_name'])) ? $_POST['branch_name'] : '';
    $data['ifsc_code'] = (isset($_POST['ifsc_code'])) ? $_POST['ifsc_code'] : '';
    $data['dl_no1'] = (isset($_POST['dl_no1'])) ? $_POST['dl_no1'] : '';
    $data['dl_no2'] = (isset($_POST['dl_no2'])) ? $_POST['dl_no2'] : '';
    $data['vendor_type'] = (isset($_POST['vendor_type'])) ? $_POST['vendor_type'] : '';
    $data['under'] = (isset($_POST['under'])) ? $_POST['under'] : '';
    
    $data['adharno'] = (isset($_POST['adharno'])) ? $_POST['adharno'] : '';
    $data['customer_type'] = (isset($_POST['customer_type'])) ? $_POST['customer_type'] : '';
    $data['customer_role'] = (isset($_POST['customer_role'])) ? $_POST['customer_role'] : '';
    $data['crdays'] = (isset($_POST['crdays'])) ? $_POST['crdays'] : '';

    $data['reseller_price_local'] = (isset($_POST['reseller_price_local']) && $_POST['reseller_price_local'] != '') ? $_POST['reseller_price_local'] : 0;
    $data['reseller_price_out'] = (isset($_POST['reseller_price_out']) && $_POST['reseller_price_out'] != '') ? $_POST['reseller_price_out'] : 0;

    $data['status'] = (isset($_POST['status'])) ? $_POST['status'] : 0;
    
    

      if(isset($_GET['id']) && $_GET['id'] != ''){
        $query = "UPDATE ledger_master SET";
      }else{
        $query = "INSERT INTO ledger_master SET";
      }

      foreach ($data as $key => $value) {
        $query .= " ".$key." = '".$value."', ";
      }

      if(isset($_GET['id']) && $_GET['id'] != ''){
        $query .= "modified = '".date('Y-m-d H:i:s')."', modifiedby = '".$_SESSION['auth']['id']."'";
        $query .= "where id = '".$_GET['id']."'";
      }else{
        $query .= "created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
      }
      
      $result = mysqli_query($conn, $query);
      
      if($result){
        if(isset($_GET['id']) && $_GET['id'] != ''){
          $_SESSION['msg']['success'] = "Ledger Updated Successfully.";
        }else{
          $_SESSION['msg']['success'] = "Ledger Added Successfully.";
        }
        header('Location: view-ledger-management.php');exit;
      }else{
        if(isset($_GET['id']) && $_GET['id'] != ''){
          $_SESSION['msg']['error'] = "Ledger Updated Failed.";
        }else{
          $_SESSION['msg']['error'] = "Ledger Added Failed.";
        }
      }
  }
  /* END CODE FOR DATA INSERT AND UPDATE  */

  /* START CODE FOR EDIT RECORD GET VALUE */
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $getLedgerQuery = "SELECT * FROM ledger_master WHERE id = '".$_GET['id']."'";
    $getLedgerRes = mysqli_query($conn, $getLedgerQuery);
    if($getLedgerRes && mysqli_num_rows($getLedgerRes) > 0){
      $ledgerdata = mysqli_fetch_array($getLedgerRes);
    }else{
      $_SESSION['msg']['error'] = "Somthing Want Wrong! Please Try Again.";
      header('Location: view-ledger-management.php');exit;
    }
  }
  /* END CODE FOR EDIT RECORD GET VALUE */

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
    
        
        
        <!-- partial:partials/_settings-panel.html -->
        
        <!--<div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="mdi mdi-settings"></i></div>
        <div id="theme-settings" class="settings-panel">
        <i class="settings-close mdi mdi-close"></i>
        <p class="settings-heading">SIDEBAR SKINS</p>
        <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Light</div>
        <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark</div>
        <p class="settings-heading mt-2">HEADER SKINS</p>
        <div class="color-tiles mx-0 px-4">
          <div class="tiles primary"></div>
          <div class="tiles success"></div>
          <div class="tiles warning"></div>
          <div class="tiles danger"></div>
          <div class="tiles pink"></div>
          <div class="tiles info"></div>
          <div class="tiles dark"></div>
          <div class="tiles default"></div>
        </div>
        </div>
        </div>-->
        
        
        <!-- Right Sidebar -->
        <?php include "include/sidebar-right.php" ?>
        
       
       <!-- Left Navigation -->
        <?php include "include/sidebar-nav-left.php" ?>
        
        
      <div class="main-panel">
      
        <div class="content-wrapper">
          <?php include('include/flash.php'); ?>
          <div class="row">
            
            <!-- Vendor Managment Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <?php
                    $type = '';
                    if(isset($_GET['type']) && $_GET['type'] != ''){
                      $getmasterGroup = "SELECT name from group_master where id = '".$_GET['type']."'";
                      $resmasterGroup = mysqli_query($conn, $getmasterGroup);
                      if($resmasterGroup){
                        $fetchgroupmaster = mysqli_fetch_array($resmasterGroup);
                        $type = (isset($fetchgroupmaster['name']) && $fetchgroupmaster['name'] != '') ? ' - '.ucwords(strtolower($fetchgroupmaster['name'])) : '';
                      }
                    }
                    $subtype = '';
                    if(isset($_GET['subtype']) && $_GET['subtype'] != ''){
                      $getSubtypeQ = "SELECT name from `group` where id = '".$_GET['subtype']."'";
                      $getSubtypeR = mysqli_query($conn, $getSubtypeQ);
                      if($getSubtypeR){
                        $fetchgetSubtype = mysqli_fetch_array($getSubtypeR);
                        $subtype = (isset($fetchgetSubtype['name']) && $fetchgetSubtype['name'] != '') ? ' - '.ucwords(strtolower($fetchgetSubtype['name'])) : '';
                      }
                    }
                  ?>
                  <h4 class="card-title">Ledger Management <?php echo (isset($type)) ? $type : ''; ?> <?php echo (isset($subtype)) ? $subtype : ''; ?></h4>
                 <hr class="alert-dark">
                 <br>
                  <form action="" method="POST">
                  
                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="companyname">Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="companyname" class="form-control" placeholder="Company Name" value="<?php echo (isset($ledgerdata['companyname'])) ? $ledgerdata['companyname'] : ''; ?>" Required>
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="name">Contact Person Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" placeholder="Contact Person Name" value="<?php echo (isset($ledgerdata['name'])) ? $ledgerdata['name'] : ''; ?>" Required>
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="designation">Designation</label>
                      <input type="text" class="form-control" name="designation" value="<?php echo (isset($ledgerdata['designation'])) ? $ledgerdata['designation'] : ''; ?>" placeholder="Designation">
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="mobile">Mobile No <span class="text-danger">*</span></label>
                        <input type="text" name="mobile" class="form-control" data-parsley-type="number" placeholder="Mobile No" value="<?php echo (isset($ledgerdata['mobile'])) ? $ledgerdata['mobile'] : ''; ?>" required>
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="phone">Phone No </label>
                      <input type="text" class="form-control" name="phone" placeholder="Phone No" value="<?php echo (isset($ledgerdata['phone'])) ? $ledgerdata['phone'] : ''; ?>">
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" placeholder="Email" parsley-type="email" value="<?php echo (isset($ledgerdata['email'])) ? $ledgerdata['email'] : ''; ?>" required>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="addressline1">Address</label>
                        <input type="text" class="form-control" name="addressline1" placeholder="Address line1" value="<?php echo (isset($ledgerdata['addressline1'])) ? $ledgerdata['addressline1'] : ''; ?>">
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="addressline2">&nbsp;</label>
                        <input type="text" class="form-control" name="addressline2" placeholder="Address line2" value="<?php echo (isset($ledgerdata['addressline2'])) ? $ledgerdata['addressline2'] : ''; ?>">
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="addressline3">&nbsp;</label>
                        <input type="text" class="form-control" name="addressline3" placeholder="Address line3" value="<?php echo (isset($ledgerdata['addressline3'])) ? $ledgerdata['addressline3'] : ''; ?>">
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="country">Country <span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" name="country" id="country" style="width:100%" required>
                          <option value="">Select Country</option>
                          <?php 
                            $countryQuery = "SELECT id, name FROM own_countries order by name ASC";
                            $counteryRes = mysqli_query($conn, $countryQuery);
                          ?>
                          <?php if($counteryRes){ ?>
                            <?php while ($countryRow = mysqli_fetch_array($counteryRes)) { ?>
                              <option value="<?php echo $countryRow['id']; ?>" <?php echo (isset($ledgerdata['country']) && $ledgerdata['country'] == $countryRow['id']) ? 'selected' : ''; ?> ><?php echo $countryRow['name']; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="state">State <span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" id="state" name="state" style="width:100%" required>
                          <option value="">Select State</option>
                          <?php if(isset($ledgerdata['country']) && $ledgerdata['country'] != ''){ ?>
                            <?php 
                              $stateQuery = "SELECT id, name FROM own_states WHERE country_id = '".$ledgerdata['country']."' order by name ASC";
                              $stateRes = mysqli_query($conn, $stateQuery);
                            ?>
                            <?php if($stateRes){ ?>
                              <?php while ($stateRow = mysqli_fetch_array($stateRes)) { ?>
                                <option value="<?php echo $stateRow['id']; ?>" <?php echo (isset($ledgerdata['state']) && $ledgerdata['state'] == $stateRow['id']) ? 'selected' : ''; ?> ><?php echo $stateRow['name']; ?></option>
                              <?php } ?>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="district">District <span class="text-danger">*</span></label>
                        <input type="text" name="district" class="form-control" placeholder="District" value="<?php echo (isset($ledgerdata['district'])) ? $ledgerdata['district'] : ''; ?>" required>
                      </div>
                    </div>

                    <div class="form-group row">
                      
                      <div class="col-12 col-md-4">
                        <label for="city">City <span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" id="city" name="city" style="width:100%" required>
                          <option value="">Select City</option>
                          <?php if(isset($ledgerdata['state']) && $ledgerdata['state'] != ''){ ?>
                            <?php 
                              $cityQuery = "SELECT id, name FROM own_cities WHERE state_id = '".$ledgerdata['state']."' order by name ASC";
                              $cityRes = mysqli_query($conn, $cityQuery);
                            ?>
                            <?php if($cityRes){ ?>
                              <?php while ($cityRow = mysqli_fetch_array($cityRes)) { ?>
                                <option value="<?php echo $cityRow['id']; ?>" <?php echo (isset($ledgerdata['city']) && $ledgerdata['city'] == $cityRow['id']) ? 'selected' : ''; ?> ><?php echo $cityRow['name']; ?></option>
                              <?php } ?>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="pincode">Pincode</label>
                        <input type="text" class="form-control" name="pincode" value="<?php echo (isset($ledgerdata['pincode'])) ? $ledgerdata['pincode'] : ''; ?>" placeholder="Pincode">
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="faxno">Fax no</label>
                        <input type="text" class="form-control" name="faxno" value="<?php echo (isset($ledgerdata['faxno'])) ? $ledgerdata['faxno'] : ''; ?>" placeholder="Fax no">
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="opening_balance">Opening Balance</label>
                        <input type="text" name="opening_balance" class="form-control" placeholder="Opening Balance" value="<?php echo (isset($ledgerdata['opening_balance'])) ? $ledgerdata['opening_balance'] : ''; ?>" data-parsley-type="number">
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="opening_balance_type">Opening Balance Type</label>
                        <select class="form-control" name="opening_balance_type" style="width:100%">
                          <option value="DB" <?php echo (isset($ledgerdata['opening_balance_type']) && $ledgerdata['opening_balance_type'] == 'DB') ? 'selected' : ''; ?>>DB</option>
                          <option value="CR" <?php echo (isset($ledgerdata['opening_balance_type']) && $ledgerdata['opening_balance_type'] == 'CR') ? 'selected' : ''; ?>>CR</option>
                        </select>
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="under">Under</label>
                        <select class="form-control" name="under" style="width:100%" required>
                          <option value="">Select Under Group</option>
                          <option value="1" <?php echo (isset($ledgerdata['under']) && $ledgerdata['under'] == '1') ? 'selected' : ''; ?>>Trading A/C</option>
                          <option value="2" <?php echo (isset($ledgerdata['under']) && $ledgerdata['under'] == '2') ? 'selected' : ''; ?>>P & L A/C</option>
                          <option value="3" <?php echo (isset($ledgerdata['under']) && $ledgerdata['under'] == '3') ? 'selected' : ''; ?>>Balance Sheet</option>
                        </select>
                      </div>
                    </div>
                    
                    
                    <div class="form-group row" style="margin-top: -25px;">

                      
                      <div class="col-12 col-md-4 m-t-20 <?php echo (isset($_GET['subtype']) && in_array($_GET['subtype'], [10])) ? 'display-block' : 'display-none'; ?>">
                        <label for="customer_type">Customer Type</label>
                        <select class="form-control" name="customer_type" style="width:100%"> 
                          <option value="Regular" <?php echo (isset($ledgerdata['customer_type']) && $ledgerdata['customer_type'] == 'Regular') ? 'selected' : ''; ?>>Regular</option>
                          <option value="Unregistered" <?php echo (isset($ledgerdata['customer_type']) && $ledgerdata['customer_type'] == 'Unregistered') ? 'selected' : ''; ?>>Unregistered</option>
                          <option value="Composition" <?php echo (isset($ledgerdata['customer_type']) && $ledgerdata['customer_type'] == 'Composition') ? 'selected' : ''; ?>>Composition</option>
                        </select>
                      </div>
                      

                      
                      <div class="col-12 col-md-4 m-t-20 <?php echo (isset($_GET['subtype']) && in_array($_GET['subtype'], [10])) ? 'display-block' : 'display-none'; ?>">
                        <label for="customer_role">Customer Role</label>
                        <select class="form-control" name="customer_role" id="customer_role" style="width:100%">
                          <option value="Enduser" <?php echo (isset($ledgerdata['customer_role']) && $ledgerdata['customer_role'] == 'Enduser') ? 'selected' : ''; ?>>End User</option>
                          <option value="Reseller" <?php echo (isset($ledgerdata['customer_role']) && $ledgerdata['customer_role'] == 'Reseller') ? 'selected' : ''; ?>>Reseller</option>
                        </select>
                      </div>

                      
                      <div class="col-12 col-md-4 m-t-20 <?php echo (isset($_GET['subtype']) && in_array($_GET['subtype'], [10,14])) ? 'display-block' : 'display-none'; ?>">
                        <label for="crdays">Cr Days</label>
                        <input type="text" class="form-control" name="crdays" placeholder="Cr Days" data-parsley-type="number" value="<?php echo (isset($ledgerdata['crdays'])) ? $ledgerdata['crdays'] : ''; ?>">
                      </div>
                      

                      <div class="col-12 col-md-4 m-t-20 adharno-div <?php echo (isset($ledgerdata['customer_role']) && $ledgerdata['customer_role'] == 'Reseller') ? 'display-block' : 'display-none'; ?>">
                        <label for="adharno">Aadhar Card No</label>
                        <input type="text" class="form-control" name="adharno" placeholder="Aadhar Card No" value="<?php echo (isset($ledgerdata['adharno'])) ? $ledgerdata['adharno'] : ''; ?>">
                      </div>

                      
                      <div class="col-12 col-md-4 m-t-20 panno-div <?php echo (isset($_GET['subtype']) && in_array($_GET['subtype'], [14]) || (isset($ledgerdata['customer_role']) && $ledgerdata['customer_role'] == 'Reseller')) ? 'display-block' : 'display-none'; ?>">
                        <label for="panno">Pan No</label>
                        <input type="text" class="form-control" name="panno" placeholder="Pan No" value="<?php echo (isset($ledgerdata['panno'])) ? $ledgerdata['panno'] : ''; ?>">
                      </div>
                      

                      
                      <div class="col-12 col-md-4 m-t-20 gstno-div <?php echo (isset($_GET['subtype']) && in_array($_GET['subtype'], [14]) || (isset($ledgerdata['customer_role']) && $ledgerdata['customer_role'] == 'Reseller')) ? 'display-block' : 'display-none'; ?>">
                        <label for="gstno">GST No</label>
                        <input type="text" class="form-control" name="gstno" placeholder="GST No" value="<?php echo (isset($ledgerdata['gstno'])) ? $ledgerdata['gstno'] : ''; ?>">
                      </div>
                      

                      
                      <div class="col-12 col-md-4 m-t-20 bankname-div <?php echo (isset($_GET['subtype']) && in_array($_GET['subtype'], [14,5,22]) || (isset($ledgerdata['customer_role']) && $ledgerdata['customer_role'] == 'Reseller')) ? 'display-block' : 'display-none'; ?>">
                        <label for="bank_name">Bank Name</label>
                        <input type="text" class="form-control" name="bank_name" placeholder="Bank Name" value="<?php echo (isset($ledgerdata['bank_name'])) ? $ledgerdata['bank_name'] : ''; ?>">
                      </div>
                      

                      
                      <div class="col-12 col-md-4 m-t-20 bankacno-div <?php echo (isset($_GET['subtype']) && in_array($_GET['subtype'], [14,5,22]) || (isset($ledgerdata['customer_role']) && $ledgerdata['customer_role'] == 'Reseller')) ? 'display-block' : 'display-none'; ?>">
                        <label for="bank_ac_no">Bank A/c No</label>
                        <input type="text" class="form-control" name="bank_ac_no" placeholder="Bank A/c No" value="<?php echo (isset($ledgerdata['bank_ac_no'])) ? $ledgerdata['bank_ac_no'] : ''; ?>">
                      </div>
                      

                                        
                      <div class="col-12 col-md-4 m-t-20 branchname-div <?php echo (isset($_GET['subtype']) && in_array($_GET['subtype'], [14,5,22]) || (isset($ledgerdata['customer_role']) && $ledgerdata['customer_role'] == 'Reseller')) ? 'display-block' : 'display-none'; ?>">
                        <label for="branch_name">Branch Name</label>
                        <input type="text" class="form-control" name="branch_name" placeholder="Branch Name" value="<?php echo (isset($ledgerdata['branch_name'])) ? $ledgerdata['branch_name'] : ''; ?>">
                      </div>
                      

                      
                      <div class="col-12 col-md-4 m-t-20 ifsccode-div <?php echo (isset($_GET['subtype']) && in_array($_GET['subtype'], [14,5,22]) || (isset($ledgerdata['customer_role']) && $ledgerdata['customer_role'] == 'Reseller')) ? 'display-block' : 'display-none'; ?>">
                        <label for="ifsc_code">IFSC Code</label>
                        <input type="text" class="form-control" name="ifsc_code" placeholder="IFSC Code" value="<?php echo (isset($ledgerdata['ifsc_code'])) ? $ledgerdata['ifsc_code'] : ''; ?>">
                      </div>
                      

                      
                      <div class="col-12 col-md-4 m-t-20 dlno1-div <?php echo (isset($_GET['subtype']) && in_array($_GET['subtype'], [14]) || (isset($ledgerdata['customer_role']) && $ledgerdata['customer_role'] == 'Reseller')) ? 'display-block' : 'display-none'; ?>">
                        <label for="dl_no1">DL No 1</label>
                        <input type="text" name="dl_no1" class="form-control" placeholder="DL No 1" value="<?php echo (isset($ledgerdata['dl_no1'])) ? $ledgerdata['dl_no1'] : ''; ?>">
                      </div>
                      

                      
                      <div class="col-12 col-md-4 m-t-20 dlno2-div <?php echo (isset($_GET['subtype']) && in_array($_GET['subtype'], [14]) || (isset($ledgerdata['customer_role']) && $ledgerdata['customer_role'] == 'Reseller')) ? 'display-block' : 'display-none'; ?>">
                        <label for="dl_no2">DL No 2</label>
                        <input type="text" name="dl_no2" class="form-control" placeholder="DL No 2" value="<?php echo (isset($ledgerdata['dl_no2'])) ? $ledgerdata['dl_no2'] : ''; ?>">
                      </div>
                      

                      
                      <div class="col-12 col-md-4 m-t-20 <?php echo (isset($_GET['subtype']) && in_array($_GET['subtype'], [14])) ? 'display-block' : 'display-none'; ?>">
                        <label for="vendor_type">Vender Type</label>
                        <select class="form-control" name="vendor_type" style="width:100%"> 
                          <option value="Regular" <?php echo (isset($ledgerdata['vendor_type']) && $ledgerdata['vendor_type'] == 'Regular') ? 'selected' : ''; ?>>Regular</option>
                          <option value="Unregistered" <?php echo (isset($ledgerdata['vendor_type']) && $ledgerdata['vendor_type'] == 'Unregistered') ? 'selected' : ''; ?>>Unregistered</option>
                          <option value="Composition" <?php echo (isset($ledgerdata['vendor_type']) && $ledgerdata['vendor_type'] == 'Composition') ? 'selected' : ''; ?>>Composition</option>
                        </select>
                      </div>

                      <div class="col-12 col-md-4 m-t-20 resellerprice-div <?php echo (isset($ledgerdata['customer_role']) && $ledgerdata['customer_role'] == 'Reseller') ? 'display-block' : 'display-none'; ?>">
                        <label for="reseller_price_local">Reseller Price</label>
                          <div class="row no-gutters">
                              <div class="col-12 col-md-6">
                             <input type="text" name="reseller_price_local" class="form-control"  placeholder="Local" data-parsley-type="number" value="<?php echo (isset($ledgerdata['reseller_price_local'])) ? $ledgerdata['reseller_price_local'] : ''; ?>">
                                </div>
                               <div class="col-12 col-md-6">
                                <input type="text" name="reseller_price_out" class="form-control" placeholder="Out" data-parsley-type="number" value="<?php echo (isset($ledgerdata['reseller_price_out'])) ? $ledgerdata['reseller_price_out'] : ''; ?>">
                               </div>
                            </div>
                      </div>

                    </div>
                    <div class="form-group row">

                      <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Status</label>
                        <div class="row no-gutters">
                            <div class="col">
                                <div class="form-radio">
                                <label class="form-check-label">
                                  <?php
                                    if(isset($_GET['id'])){
                                      $active = (isset($ledgerdata['status']) && $ledgerdata['status'] == 1)  ? 'checked' : '';
                                    }else{
                                      $active = 'checked';
                                    }
                                  ?>
                                <input type="radio" class="form-check-input" name="status" value="1" <?php echo $active; ?>>
                                Active
                                </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-radio">
                                <label class="form-check-label">
                                  <?php
                                    $deactive = (isset($ledgerdata['status']) && $ledgerdata['status'] == 0)  ? 'checked' : '';
                                  ?>
                                <input type="radio" class="form-check-input" name="status" value="0" <?php echo $deactive; ?>>
                                Deactive
                                </label>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                    <input type="hidden" name="account_type" value="<?php echo (isset($_GET['type']) && $_GET['type'] != '') ? $_GET['type'] : NULL; ?>">
                    <br>

                    <a href="view-ledger-management.php" class="btn btn-light pull-left">Back</a>
                    <button type="submit" class="btn btn-success mr-2 pull-right" name="submit">Submit</button>

                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade" id="ledger-accounttype-model" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel">Select Account Type</h5>
                </div>
                <form method="get">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Select Account</label>
                          <select class="js-example-basic-single" name="type" style="width:100%" id="type" required>
                              <option value="">Select Account</option>
                              <?php 
                                $selectQry = "SELECT * FROM `group_master`";
                                $select = mysqli_query($conn,$selectQry);
                                while($row = mysqli_fetch_assoc($select)){
                              ?>
                              <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                              <?php } ?>
                          </select>
                      </div>
                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Select Group</label>
                          <select class="js-example-basic-single" name="subtype" style="width:100%" id="subtype" required>
                              <option value="">Select Group</option>
                          </select>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <div class="col-md-12">
                            <a href="view-ledger-management.php" type="button" class="btn btn-light pull-left">Back</a>
                            <button type="submit" class="btn btn-success pull-right">Submit</button>
                        </div>
                  </div>
              </form>
            
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

  <script type="text/javascript">
    <?php 
    if(!isset($_GET['type']) || !in_array($_GET['type'], [1,2,3,4,5]) || !isset($_GET['subtype']) || $_GET['subtype'] == ''){
    ?>
    $(window).on('load',function(){
        $('#ledger-accounttype-model').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    });
    <?php } ?>
</script>

<!-- page js -->
<script src="js/custom/ledger_management.js"></script>

<!-- script for custom validation -->
<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>
  
  <!-- End custom js for this page-->
  <?php //include('include/usertypecheck.php'); ?>
</body>


</html>
