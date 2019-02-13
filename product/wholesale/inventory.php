<?php $title = "Inventory"; ?>
<?php include('include/usertypecheck.php');

include('include/permission.php');
$pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
$financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

  $getExpiryMonthQuery = "SELECT near_by FROM general_settings WHERE pharmacy_id = '".$pharmacy_id."' ORDER BY id DESC LIMIT 1";
  $getExpiryMonthRes = mysqli_query($conn, $getExpiryMonthQuery);
  if($getExpiryMonthRes){
    $fetchMonth = mysqli_fetch_array($getExpiryMonthRes);
    $nearExpiryMonth = ($fetchMonth['near_by']) ? $fetchMonth['near_by'] : '';
  }
    /* SET SESSION START */

        // set alphabet in session
        if(isset($_GET['alphabet']) && $_GET['alphabet'] != ''){
          $_SESSION['inventory_alphabet'] = $_GET['alphabet'];
        }
        // set product in session [All, Available, Expiry, Zero Stock, Over Stock, Non Moving Stock, Reorder]
        if(isset($_GET['product']) && $_GET['product'] != ''){
          unset($_SESSION['search']);
          unset($_SESSION['searchid']);
          unset($_SESSION['selectsearch']);
          unset($_SESSION['inventory_alphabet']);
          $_SESSION['product'] = $_GET['product'];
        }

        // reset non moving month when select all product
        if(isset($_GET['product']) && $_GET['product'] == 'all'){
          $_SESSION['month'] = 1;
          $_SESSION['percentage'] = 60;
        }

        // set selectsearch in session [Product, MRP, Generic]
        if(isset($_GET['selectsearch']) && $_GET['selectsearch'] != ''){
          $_SESSION['selectsearch'] = $_GET['selectsearch'];
        }
        // set search and search ID in session
        if(isset($_GET['search']) && $_GET['search'] != ''){
          $_SESSION['search'] = $_GET['search'];
          if(isset($_GET['searchid']) && $_GET['searchid'] != ''){
            $_SESSION['searchid'] = $_GET['searchid'];
            unset($_SESSION['product']);
          }
        }elseif(isset($_GET['search']) && $_GET['search'] == ''){
          unset($_SESSION['search']);
          unset($_SESSION['searchid']);
        }
        // set month in session
        if(isset($_GET['month']) && $_GET['month'] != ''){
          $_SESSION['month'] = $_GET['month'];
        }elseif (isset($_GET['month']) && $_GET['month'] == '') {
          unset($_SESSION['month']);
        }

        // set percentage in session
        if(isset($_GET['percentage']) && $_GET['percentage'] != ''){
          $_SESSION['percentage'] = $_GET['percentage'];
        }elseif (isset($_GET['percentage']) && $_GET['percentage'] == '') {
          unset($_SESSION['percentage']);
        }

        /* if(isset($_GET['alphabet']) || isset($_GET['product']) || isset($_GET['selectsearch']) || isset($_GET['search']) || isset($_GET['searchid']) || isset($_GET['month']) || isset($_GET['percentage'])){
          header('Location: inventory.php');
          echo "<script>window.location.href='inventory.php';</script>";
        } */
    /* SET SESSION END */

    /* RESET ALL SEARCH SESSION START */
      if(isset($_GET['reset']) && $_GET['reset'] == 'all'){
        unset($_SESSION['inventory_alphabet']);
        unset($_SESSION['product']);
        unset($_SESSION['selectsearch']);
        unset($_SESSION['search']);
        unset($_SESSION['searchid']);
        unset($_SESSION['month']);
        unset($_SESSION['percentage']);
      }
    /* RESET ALL SEARCH SESSION END */
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Inventory</title>
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
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
  <link rel="stylesheet" href="css/parsley.css">
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
             <!-- Inventory Form ------------------------------------------------------------------------------------------------------>
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Inventory</h4>
                    <hr class="alert-dark">
                    <br>
                
                    <div class="row">
                    
                      <div class="col-12 col-md-10 col-sm-12">
                          <div class="enventory">
                            <a href="inventory.php?reset=all" class="btn btn-dark active">Inventory</a>
                            <?php if((isset($user_sub_module) && in_array("Inventory Adjustment", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                              <a href="inventory-adjustment.php" class="btn btn-dark active">Inventory Adjustment</a>
                            <?php } if((isset($user_sub_module) && in_array("Update Inventory", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                              <!--<a href="#" class="btn btn-dark">Update Inventory </a>-->
                            <?php } if((isset($user_sub_module) && in_array("Inventory Setting", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                              <!--<a href="#" class="btn btn-dark">Inventory Setting </a>-->
                            <?php } if((isset($user_sub_module) && in_array("Product Master", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                              <a href="product-master.php" class="btn btn-dark">Product Master </a>
                            <?php } if((isset($user_sub_module) && in_array("Self Consumption", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                              <a href="inventory-self-consumption.php" class="btn btn-dark">Self Consumption </a>
                            <?php } ?>
                          </div>     
                      </div> 
                      
                     <!-- <div class="col-12 col-md-2">
                        <button type="button" class="btn btn-grey-1 btn-rounded pull-right"><strong>*HSN Code Reference</strong></button>
                      </div>-->
                    
                    </div>

                    <hr>
                    
                    
                    <div class="row">
                    
                      <div class="col-md-6">
                        <form method="GET">
                            <div class="form-group row">
                            
                              <div class="col-12 col-md-5">
                                <label>Select anyone</label>
                                  <select class="js-example-basic-single" name="selectsearch" style="width:100%" id="selectsearch"> 
                                      <option value="product" <?php echo (isset($_SESSION['selectsearch']) && $_SESSION['selectsearch'] == 'product') ? 'selected' : ''; ?>>Product Name </option>
                                      <option value="mrp" <?php echo (isset($_SESSION['selectsearch']) && $_SESSION['selectsearch'] == 'mrp') ? 'selected' : ''; ?>>MRP</option>
                                      <!--<option value="generic" <?php //echo (isset($_SESSION['selectsearch']) && $_SESSION['selectsearch'] == 'generic') ? 'selected' : ''; ?>>Generic Name</option>-->
                                  </select>
                              </div>
                              
                              <div class="col-12 col-md-4">
                                  <?php 
                                    if(isset($_SESSION['selectsearch']) && $_SESSION['selectsearch'] == 'product'){
                                      $lbl = 'Enter Product Name';
                                    }elseif(isset($_SESSION['selectsearch']) && $_SESSION['selectsearch'] == 'mrp'){
                                      $lbl = 'Enter MRP';
                                    }elseif (isset($_SESSION['selectsearch']) && $_SESSION['selectsearch'] == 'generic') {
                                      $lbl = 'Enter Generic Name';
                                    }else{
                                      $lbl = 'Enter Product Name';
                                    }
                                  ?>
                                  <label id="search-lable"><?php echo $lbl; ?><span class="text-danger">*</span></label>
                                  <div id="bloodhound">
                                    <input class="form-control" name="search" id="search" type="text" placeholder="Start typing.." value="<?php echo (isset($_SESSION['search'])) ? $_SESSION['search'] : ''; ?>" required>
                                    <input type="hidden" name="searchid" id="searchid" value="<?php echo (isset($_SESSION['searchid'])) ? $_SESSION['searchid'] : ''; ?>">
                                  </div>
                                  <small class="empty-message text-danger"></small>
                              </div>
                              
                              <div class="col-12 col-md-2">
                                <button type="submit" class="btn btn-success mt-30">Search</button>
                              </div>
                            
                            </div>
                        </form>
                      </div>
                      
                      <div class="col-md-6">
                        <div class="row">
                          <div class="col-12">  
                            <label class="">Search by alphabet</label>
                          </div>
                          <div class="col-12">  
                            <a href="?alphabet=a" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'a') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">A</a>
                            <a href="?alphabet=b" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'b') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">B</a>
                            <a href="?alphabet=c" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'c') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">C</a>
                            <a href="?alphabet=d" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'd') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">D</a>
                            <a href="?alphabet=e" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'e') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">E</a>
                            <a href="?alphabet=f" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'f') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">F</a>
                            <a href="?alphabet=g" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'g') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">G</a>
                            <a href="?alphabet=h" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'h') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">H</a>
                            <a href="?alphabet=i" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'i') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">I</a>
                            <a href="?alphabet=j" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'j') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">J</a>
                            <a href="?alphabet=k" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'k') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">K</a>
                            <a href="?alphabet=l" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'l') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">L</a>
                            <a href="?alphabet=m" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'm') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">M</a>
                            <a href="?alphabet=n" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'n') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">N</a>
                            <a href="?alphabet=o" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'o') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">O</a>
                            <a href="?alphabet=p" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'p') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">P</a>
                            <a href="?alphabet=q" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'q') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">Q</a>
                            <a href="?alphabet=r" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'r') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">R</a>
                            <a href="?alphabet=s" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 's') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">S</a>
                            <a href="?alphabet=t" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 't') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">T</a>
                            <a href="?alphabet=u" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'u') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">U</a>
                            <a href="?alphabet=v" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'v') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">V</a>
                            <a href="?alphabet=w" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'w') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">W</a>
                            <a href="?alphabet=x" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'x') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">X</a>
                            <a href="?alphabet=y" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'y') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">Y</a>
                            <a href="?alphabet=z" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'z') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">Z</a>
                          </div>
                        </div>
                      </div>
                    
                    </div>
                
                  </div>
                </div>
            </div>
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <!-- TABLE Filters btn -->
                    <div class="col mt-4">
                        <?php $product_all = 0; $product_available = 0; $product_expiry = 0 ?>
                        <?php
                          $allQuery = "SELECT id FROM product_master WHERE status = 1 AND pharmacy_id = '".$pharmacy_id."'";
                          $allRes = mysqli_query($conn, $allQuery);
                          $product_all = ($allRes) ? mysqli_num_rows($allRes) : 0;
                         
                        ?>
                        <a href="?product=all" class="btn btn-sm <?php echo (!isset($_SESSION['product']) || (isset($_SESSION['product']) && $_SESSION['product'] == '') || (isset($_SESSION['product']) && $_SESSION['product'] == 'all')) ? 'btn-success' : 'btn-outline-success'; ?>">All (<?php echo $product_all; ?>)</a>
                        <?php
                          $availableQuery = "SELECT id FROM product_master WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 AND (ex_date >= '".date('Y-m-d')."' OR ex_date IS NULL OR ex_date = '0000-00-00')";
                          $availableRes = mysqli_query($conn, $availableQuery);
                          $product_available = ($availableRes) ? mysqli_num_rows($availableRes) : 0;
                        ?>
                        <a href="?product=available" class="btn btn-sm <?php echo (isset($_SESSION['product']) && $_SESSION['product'] == 'available') ? 'btn-success' : 'btn-outline-success'; ?>">Available (<?php echo $product_available; ?>)</a>

                        <?php
                          $nearExpiry = 0;
                          if(isset($nearExpiryMonth) && $nearExpiryMonth != '' && is_numeric($nearExpiryMonth)){
                            $nearExpiryMonthC = '+'.$nearExpiryMonth.' months';
                            $nearExpiryDate = date('Y-m-d', strtotime($nearExpiryMonthC));
                            $nearExpiryCountQuery = "SELECT * FROM product_master WHERE ex_date <= '".$nearExpiryDate."' AND ex_date >= '".date('Y-m-d')."' AND ex_date IS NOT NULL AND ex_date != '0000-00-00' AND pharmacy_id = '".$pharmacy_id."' AND status = 1";
                            $nearExpiryCountRes = mysqli_query($conn, $nearExpiryCountQuery);
                            $nearExpiry = ($nearExpiryCountRes) ? mysqli_num_rows($nearExpiryCountRes) : 0;
                          }
                        ?>

                        <a href="?product=nearexpiry" class="btn btn-sm <?php echo (isset($_SESSION['product']) && $_SESSION['product'] == 'nearexpiry') ? 'btn-success' : 'btn-outline-success'; ?>">Near Expiry (<?php echo $nearExpiry; ?>)</a>

                        <?php

                          $expiryQuery = "SELECT id FROM product_master WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 AND ex_date < '".date('Y-m-d')."' AND ex_date IS NOT NULL AND ex_date != '0000-00-00'";
                          $expiryRes = mysqli_query($conn, $expiryQuery);
                          $product_expiry = ($expiryRes) ? mysqli_num_rows($expiryRes) : 0;
                        ?> 
                        <a href="?product=expired" class="btn btn-sm <?php echo (isset($_SESSION['product']) && $_SESSION['product'] == 'expired') ? 'btn-success' : 'btn-outline-success'; ?>">Expired (<?php echo $product_expiry; ?>)</a>
                        <?php 
                          $zerostock = 0;
                          $zerostockData = getAllProductWithCurrentStock();
                          if(!empty($zerostockData)){
                            foreach ($zerostockData as $key => $value) {
                              if(isset($value['currentstock']) && $value['currentstock'] <= 0){
                                $zerostock++;
                              }
                            }
                          }
                        ?>
                        <a href="?product=zerostock" class="btn btn-sm <?php echo (isset($_SESSION['product']) && $_SESSION['product'] == 'zerostock') ? 'btn-success' : 'btn-outline-success'; ?>">Zero Stock (<?php echo (isset($zerostock)) ? $zerostock : 0; ?>)</a>   
                        <a href="?product=overstock" class="btn btn-sm <?php echo (isset($_SESSION['product']) && $_SESSION['product'] == 'overstock') ? 'btn-success' : 'btn-outline-success'; ?>">Over Stock</a>
                        <a href="?product=nonmovingstock" class="btn btn-sm <?php echo (isset($_SESSION['product']) && $_SESSION['product'] == 'nonmovingstock') ? 'btn-success' : 'btn-outline-success'; ?>">Non Moving Stock</a>   
                        <a href="?product=reorder" class="btn btn-sm <?php echo (isset($_SESSION['product']) && $_SESSION['product'] == 'reorder') ? 'btn-success' : 'btn-outline-success'; ?>">Reorder</a>
                    </div>
                    <hr/>

                    <!-- NON Moving stock month dropdown start -->
                    <?php if(isset($_SESSION['product']) && $_SESSION['product'] == 'nonmovingstock'){ ?>
                      <form method="GET">
                        <div class="col">
                          <div class="form-group row">
                            <div class="col-12 col-md-4">
                              <label>Select Month</label>
                              <select class="js-example-basic-single" style="width:100%" name="month">
                                <?php for ($i=0; $i < 12; $i++) { ?>
                                  <option value="<?php echo $i+1; ?>" <?php echo (isset($_SESSION['month']) && $_SESSION['month'] == $i+1) ? 'selected' : ''; ?> ><?php echo $i+1; ?> Month</option>
                                <?php } ?>
                              </select>
                            </div>
                            <div class="col-6 col-md-4 mt-4">
                              <button type="submit" class="btn btn-success mt-1">Search</button>
                            </div>
                          </div> 
                        </div>
                      </form>
                      <hr/>
                    <?php } ?>
                    <!-- NON Moving stock month dropdown end -->

                    <!-- Over stock percentage dropdown start -->
                    <?php if(isset($_SESSION['product']) && $_SESSION['product'] == 'overstock'){ ?>
                      <form method="GET">
                        <div class="col">
                          <div class="form-group row">
                            <div class="col-12 col-md-4">
                              <label>Sales Percentage wise</label>
                              <select class="js-example-basic-single" style="width:100%" name="percentage">
                                  <option value="60" <?php echo (isset($_SESSION['percentage']) && $_SESSION['percentage'] == 60) ? 'selected' : ''; ?> >60</option>
                                  <option value="75" <?php echo (isset($_SESSION['percentage']) && $_SESSION['percentage'] == 75) ? 'selected' : ''; ?> >75</option>
                                  <option value="90" <?php echo (isset($_SESSION['percentage']) && $_SESSION['percentage'] == 90) ? 'selected' : ''; ?> >90</option>
                              </select>
                            </div>
                            <div class="col-6 col-md-4 mt-4">
                              <button type="submit" class="btn btn-success mt-1">Search</button>
                            </div>
                          </div> 
                        </div>
                      </form>
                      <hr/>
                    <?php } ?>
                    <!-- Over stock percentage dropdown start -->
                    
                  
                    
                    <!-- INVENTORY TABLE STARTS -->
                    <div class="col mt-3">
                      <h4 class="card-title">Common Table</h4>
                      <hr class="alert-dark">
                        <div class="row">
                          <div class="col-12">
                            <table class="table datatable">
                              <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Product</th>
                                    <th>MRP</th>
                                    <th>GST</th>
                                    <th>MFG. Co.</th>
                                    <th>Batch</th>
                                    <th>Expiry</th>
                                    <th>Qty.</th>
                                    <th>Rack No.</th>
                                    <th>Self No.</th>
                                    <th>Box No.</th>
                                    <?php if(isset($_SESSION['product']) && $_SESSION['product'] == 'reorder'){ ?>
                                      <th>Avg</th>
                                      <th>Order</th>
                                    <?php } ?>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                  $data = [];

                                  if(isset($_SESSION['product']) && $_SESSION['product'] == 'nonmovingstock'){
                                    // query for non moving stock
                                    $nonmoving_month = (isset($_SESSION['month']) && $_SESSION['month'] != '') ? $_SESSION['month'] : 1;
                                    
                                    $nonmovingQ = "SELECT pd.*,ps.vouchar_date,DATE_ADD(ps.vouchar_date, INTERVAL ".$nonmoving_month." MONTH) as end_date FROM purchase_details pd JOIN purchase ps ON ps.id = pd.purchase_id WHERE ps.pharmacy_id ='".$pharmacy_id."' AND ps.financial_id = '".$financial_id."' GROUP BY pd.product_id ";
                                    $nonmovingR = mysqli_query($conn, $nonmovingQ);

                                    if($nonmovingR && mysqli_num_rows($nonmovingR) > 0){
                                      while ($nonmovingRow = mysqli_fetch_array($nonmovingR)) {
                                        // check product exist  or not
                                        //$existQ = "SELECT id FROM tax_billing_details WHERE product_id = '".$nonmovingRow['product_id']."'";
                                        $existQ = "SELECT td.id FROM tax_billing_details td INNER JOIN tax_billing tb ON td.tax_bill_id = tb.id WHERE td.product_id='".$nonmovingRow['product_id']."' AND tb.pharmacy_id ='".$pharmacy_id."' AND tb.financial_id = '".$financial_id."'";
                                        $existR = mysqli_query($conn, $existQ);
                                        $existRow = ($existR) ? mysqli_num_rows($existR) : 0;
                                        if($existRow > 0){
                                          //$nonmoving_subQ = "SELECT pm.* FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id INNER JOIN product_master pm ON tbd.product_id = pm.id WHERE tb.invoice_date > CAST('".$nonmovingRow['end_date']."' as DATE) AND tbd.product_id = '".$nonmovingRow['product_id']."'";
                                          $nonmoving_subQ = "SELECT pm.* FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id INNER JOIN product_master pm ON tbd.product_id = pm.id WHERE tb.invoice_date > CAST('".$nonmovingRow['end_date']."' as DATE) AND tbd.product_id = '".$nonmovingRow['product_id']."' AND tb.pharmacy_id='".$pharmacy_id."' AND tb.financial_id = '".$financial_id."'";
                                            if(isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] != ''){
                                              $nonmoving_subQ .= " AND LOWER(pm.product_name) LIKE '".strtolower($_SESSION['inventory_alphabet'])."%'";
                                            }
                                          $nonmoving_subR = mysqli_query($conn, $nonmoving_subQ);
                                        }else{
                                          //$nonmoving_subQ = "SELECT * FROM product_master WHERE id = '".$nonmovingRow['product_id']."'";
                                          $nonmoving_subQ = "SELECT * FROM product_master WHERE id = '".$nonmovingRow['product_id']."' AND pharmacy_id='".$pharmacy_id."'";

                                          if(isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] != ''){
                                            $nonmoving_subQ .= " AND LOWER(product_name) LIKE '".strtolower($_SESSION['inventory_alphabet'])."%'";
                                          }
                                          
                                          $nonmoving_subR = mysqli_query($conn, $nonmoving_subQ);
                                        }

                                          if($nonmoving_subR && mysqli_num_rows($nonmoving_subR) > 0){
                                            while ($nonmoving_subRow = mysqli_fetch_array($nonmoving_subR)) {
                                                
                                              $detail['id'] = (isset($nonmoving_subRow['id'])) ? $nonmoving_subRow['id'] : '';
                                              $detail['product_name'] = (isset($nonmoving_subRow['product_name'])) ? $nonmoving_subRow['product_name'] : '';
                                              $detail['mrp'] = (isset($nonmoving_subRow['mrp'])) ? $nonmoving_subRow['mrp'] : '';
                                              $detail['mfg_company'] = (isset($nonmoving_subRow['mfg_company'])) ? $nonmoving_subRow['mfg_company'] : '';
                                              $detail['ex_date'] = (isset($nonmoving_subRow['ex_date']) && $nonmoving_subRow['ex_date'] != '') ? date('d/m',strtotime($nonmoving_subRow['ex_date'])) : '';
                                              $detail['max_qty'] = (isset($nonmoving_subRow['max_qty'])) ? $nonmoving_subRow['max_qty'] : '';
                                              $detail['rack_no'] = (isset($nonmoving_subRow['rack_no']) && $value['rack_no'] != '') ? $nonmoving_subRow['rack_no'] : '-';
                                              $detail['self_no'] = (isset($nonmoving_subRow['self_no']) && $value['self_no'] != '') ? $nonmoving_subRow['self_no'] : '';
                                              $detail['box_no'] = (isset($nonmoving_subRow['box_no']) && $value['box_no'] != '') ? $nonmoving_subRow['box_no'] : '';
                                              $detail['batch_no'] = (isset($nonmoving_subRow['batch_no'])) ? $nonmoving_subRow['batch_no'] : '';
                                              $detail['currentstock'] = (isset($value['currentstock']) && $value['currentstock'] != '') ? $value['currentstock'] : 0;
                                              $detail['gst_name'] = (isset($value['gst_name'])) ? $value['gst_name'] : '';
                                              $data[] = $detail;
                                            }
                                          }

                                      }

                                    }


                                  }elseif(isset($_SESSION['product']) && $_SESSION['product'] == 'zerostock'){
                                    $stock = getAllProductWithCurrentStock((isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] != '') ? $_SESSION['inventory_alphabet'] : '');
                                    if(isset($stock) && !empty($stock)){
                                      foreach ($stock as $key => $value) {
                                        if(isset($value['currentstock']) && $value['currentstock'] <= 0){
                                          $detail['id'] = (isset($value['id'])) ? $value['id'] : '';
                                          $detail['product_name'] = (isset($value['product_name'])) ? $value['product_name'] : '';
                                          $detail['mrp'] = (isset($value['mrp'])) ? $value['mrp'] : '';
                                          $detail['mfg_company'] = (isset($value['mfg_company'])) ? $value['mfg_company'] : '';
                                          $detail['ex_date'] = (isset($value['ex_date']) && $value['ex_date'] != '') ? date('d/m',strtotime($value['ex_date'])) : '';
                                          $detail['max_qty'] = (isset($value['max_qty'])) ? $value['max_qty'] : '';
                                          $detail['rack_no'] = (isset($value['rack_no']) && $value['rack_no'] != '') ? $value['rack_no'] : '-';
                                          $detail['self_no'] = (isset($value['self_no']) && $value['self_no'] != '') ? $value['self_no'] : '';
                                          $detail['box_no'] = (isset($value['box_no']) && $value['box_no'] != '') ? $value['box_no'] : '';
                                          $detail['batch_no'] = (isset($value['batch_no'])) ? $value['batch_no'] : '';
                                          $detail['currentstock'] = (isset($value['currentstock']) && $value['currentstock'] != '') ? $value['currentstock'] : 0;
                                          $detail['gst_name'] = (isset($value['gst_name'])) ? $value['gst_name'] : '';
                                          $data[] = $detail;
                                        }
                                      }
                                    }
                                  }elseif(isset($_SESSION['product']) && $_SESSION['product'] == 'overstock'){
                                    $overstock = getAllProductWithCurrentStock((isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] != '') ? $_SESSION['inventory_alphabet'] : '');
                                    if(isset($overstock) && !empty($overstock)){
                                      $calper = (isset($_SESSION['percentage']) && $_SESSION['percentage'] != '') ? $_SESSION['percentage'] : 60;
                                      foreach ($overstock as $key => $value) {
                                         if(isset($value['currentstock']) && $value['currentstock'] > 0){
                                            $curstock = ($value['currentstock']-$value['sale']);
                                            $stockper = (100*$curstock/$value['currentstock']);
    
                                            if($stockper >= $calper){
                                              $detail['id'] = (isset($value['id'])) ? $value['id'] : '';
                                              $detail['product_name'] = (isset($value['product_name'])) ? $value['product_name'] : '';
                                              $detail['mrp'] = (isset($value['mrp'])) ? $value['mrp'] : '';
                                              $detail['mfg_company'] = (isset($value['mfg_company'])) ? $value['mfg_company'] : '';
                                              $detail['ex_date'] = (isset($value['ex_date']) && $value['ex_date'] != '') ? date('d/m',strtotime($value['ex_date'])) : '';
                                              $detail['max_qty'] = (isset($value['max_qty'])) ? $value['max_qty'] : '';
                                              $detail['rack_no'] = (isset($value['rack_no']) && $value['rack_no'] != '') ? $value['rack_no'] : '-';
                                              $detail['self_no'] = (isset($value['self_no']) && $value['self_no'] != '') ? $value['self_no'] : '';
                                              $detail['box_no'] = (isset($value['box_no']) && $value['box_no'] != '') ? $value['box_no'] : '';
                                              $detail['batch_no'] = (isset($value['batch_no'])) ? $value['batch_no'] : '';
                                              $detail['currentstock'] = (isset($value['currentstock']) && $value['currentstock'] != '') ? $value['currentstock'] : 0;
                                              $detail['gst_name'] = (isset($value['gst_name'])) ? $value['gst_name'] : '';
                                              $data[] = $detail;
                                            }
                                         }
                                      }
                                    }
                                  }elseif(isset($_SESSION['product']) && $_SESSION['product'] == 'reorder'){
                                    $reorder = getAllProductWithCurrentStock((isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] != '') ? $_SESSION['inventory_alphabet'] : '');
                                    if(isset($reorder) && !empty($reorder)){
                                      foreach ($reorder as $key => $value) {
                                        $r_openingstock = (isset($value['opening_qty']) && $value['opening_qty'] != '') ? $value['opening_qty'] : 0;
                                        $r_purchase = (isset($value['purchase']) && $value['purchase'] != '') ? $value['purchase'] : 0;
                                        $r_sale = (isset($value['sale']) && $value['sale'] != '') ? $value['sale'] : 0;
                                        $r_clearstock = ($r_openingstock+$r_purchase)-($r_sale);

                                        $r_avg = ($r_sale/3);//for 3 month sale average
                                        $sudgest_order = ($r_sale*1.5)-($r_clearstock);//for suggest order qty
                                        $sudgest_order = ($sudgest_order > 0) ? $sudgest_order : 0;


                                        $detail['id'] = (isset($value['id'])) ? $value['id'] : '';
                                        $detail['product_name'] = (isset($value['product_name'])) ? $value['product_name'] : '';
                                        $detail['mrp'] = (isset($value['mrp'])) ? $value['mrp'] : '';
                                        $detail['mfg_company'] = (isset($value['mfg_company'])) ? $value['mfg_company'] : '';
                                        $detail['ex_date'] = (isset($value['ex_date']) && $value['ex_date'] != '') ? date('d/m',strtotime($value['ex_date'])) : '';
                                        $detail['max_qty'] = (isset($value['max_qty'])) ? $value['max_qty'] : '';
                                        $detail['rack_no'] = (isset($value['rack_no']) && $value['rack_no'] != '') ? $value['rack_no'] : '-';
                                        $detail['self_no'] = (isset($value['self_no']) && $value['self_no'] != '') ? $value['self_no'] : '';
                                        $detail['box_no'] = (isset($value['box_no']) && $value['box_no'] != '') ? $value['box_no'] : '';
                                        $detail['batch_no'] = (isset($value['batch_no'])) ? $value['batch_no'] : '';
                                        $detail['currentstock'] = (isset($value['currentstock']) && $value['currentstock'] != '') ? $value['currentstock'] : 0;
                                        // new extra field
                                        $detail['average'] = (isset($r_avg) && $r_avg != '') ? round($r_avg, 2) : 0;
                                        $detail['suggest_order'] = (isset($sudgest_order) && $sudgest_order != '') ? round($sudgest_order, 2) : 0;
                                        $detail['gst_name'] = (isset($value['gst_name'])) ? $value['gst_name'] : '';
                                        $data[] = $detail;

                                      }
                                    }
                                  }else{
                                    $allProductQry = "SELECT id FROM product_master ";
                                    $where = array();
                                      if(isset($_SESSION['searchid']) && $_SESSION['searchid'] != ''){
                                        $where[] = "id = '".$_SESSION['searchid']."'";
                                      }
                                      if(isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] != ''){
                                        $where[] = "LOWER(product_name) LIKE '".strtolower($_SESSION['inventory_alphabet'])."%'";
                                      }
                                      /*if(isset($_SESSION['product']) && $_SESSION['product'] == 'available'){
                                        $where[] = "ex_date >= '".date('Y-m-d')."'";
                                      }*/
                                      if(isset($_SESSION['product']) && $_SESSION['product'] == 'expired'){
                                        $where[] = "ex_date < '".date('Y-m-d')."' AND ex_date IS NOT NULL AND ex_date != '0000-00-00'";
                                      }
                                      if(isset($_SESSION['product']) && $_SESSION['product'] == 'nearexpiry'){
                                        if(isset($nearExpiryMonth) && $nearExpiryMonth != '' && is_numeric($nearExpiryMonth)){
                                          $nearExpiryMonthList = '+'.$nearExpiryMonth.' months';
                                          $nearExpiryDateList = date('Y-m-d', strtotime($nearExpiryMonthList));
                                          $where[] = "ex_date <= '".$nearExpiryDateList."' AND ex_date >= '".date('Y-m-d')."' AND ex_date IS NOT NULL AND ex_date != '0000-00-00'";
                                        }
                                      }
                                      $where[] = "status = 1";
                                      $where[] = "pharmacy_id = '".$pharmacy_id."'";

                                      if(!empty($where)){
                                        $where = implode(" AND ",$where);
                                        $allProductQry .="WHERE ".$where;
                                      }
                                      if(isset($_SESSION['product']) && $_SESSION['product'] == 'available'){
                                          $allProductQry .= " AND (ex_date >= '".date('Y-m-d')."' OR ex_date IS NULL OR ex_date = '0000-00-00')";
                                      }
                                      $allProductQry .=' ORDER BY id DESC';
                                      
                                      $allProductRes = mysqli_query($conn, $allProductQry);

                                      if($allProductRes && mysqli_num_rows($allProductRes) > 0){
                                        $data_id = [];
                                          while ($productRow = mysqli_fetch_array($allProductRes)) {
                                            if(isset($productRow['id']) && $productRow['id'] != ''){
                                              $data_id[] = $productRow['id'];
                                            }
                                          }
                                          if(isset($data_id) && !empty($data_id)){
                                            $geAllProduct = getAllProductWithCurrentStock('','',0,$data_id);
                                            if(!empty($geAllProduct)){
                                              foreach ($geAllProduct as $key => $value) {
                                                $detail['id'] = (isset($value['id'])) ? $value['id'] : '';
                                                $detail['product_name'] = (isset($value['product_name'])) ? $value['product_name'] : '';
                                                $detail['mrp'] = (isset($value['mrp'])) ? $value['mrp'] : '';
                                                $detail['mfg_company'] = (isset($value['mfg_company'])) ? $value['mfg_company'] : '';
                                                $detail['ex_date'] = (isset($value['ex_date']) && $value['ex_date'] != '' && $value['ex_date'] != '0000-00-00') ? date('d/m',strtotime($value['ex_date'])) : '';
                                                $detail['max_qty'] = (isset($value['max_qty'])) ? $value['max_qty'] : '';
                                                $detail['rack_no'] = (isset($value['rack_no']) && $value['rack_no'] != '') ? $value['rack_no'] : '-';
                                                $detail['self_no'] = (isset($value['self_no']) && $value['self_no'] != '') ? $value['self_no'] : '';
                                                $detail['box_no'] = (isset($value['box_no']) && $value['box_no'] != '') ? $value['box_no'] : '';
                                                $detail['batch_no'] = (isset($value['batch_no'])) ? $value['batch_no'] : '';
                                                $detail['currentstock'] = (isset($value['currentstock']) && $value['currentstock'] != '') ? $value['currentstock'] : 0;
                                                $detail['gst_name'] = (isset($value['gst_name'])) ? $value['gst_name'] : '';
                                                $data[] = $detail;
                                              }
                                            }
                                          }
                                      }
                                  }
                                ?>
                                <?php if(isset($data) && !empty($data)){ ?>
                                  <?php foreach ($data as $key => $value) { ?>
                                    <tr>
                                      <td><?php echo $key+1; ?></td>
                                      <td><?php echo (isset($value['product_name'])) ? $value['product_name'] : ''; ?></td>
                                      <td class="text-right"><?php echo (isset($value['mrp']) && $value['mrp'] != '') ? amount_format(number_format($value['mrp'], 2, '.', '')) : ''; ?></td>
                                      <td><?php echo (isset($value['gst_name']) && $value['gst_name'] != '') ? $value['gst_name'] : '-'; ?></td>
                                      <td><?php echo (isset($value['mfg_company']) && $value['mfg_company'] != '') ? $value['mfg_company'] : '-'; ?></td>
                                      <td><?php echo (isset($value['batch_no']) && $value['batch_no'] != 'undefined' && $value['batch_no'] != '') ? $value['batch_no'] : '-'; ?></td>
                                      <td><?php echo (isset($value['ex_date']) && $value['ex_date'] != '') ? $value['ex_date'] : '-'; ?></td>
                                      <td><?php echo (isset($value['currentstock'])) ? $value['currentstock'] : ''; ?></td>
                                      <td><?php echo (isset($value['rack_no']) && $value['rack_no'] != '') ? $value['rack_no'] : '-'; ?></td>
                                      <td><?php echo (isset($value['self_no']) && $value['self_no'] != '') ? $value['self_no'] : '-'; ?></td>
                                      <td><?php echo (isset($value['box_no']) && $value['box_no'] != '') ? $value['box_no'] : '-'; ?></td>
                                      <?php if(isset($_SESSION['product']) && $_SESSION['product'] == 'reorder'){ ?>
                                        <td><?php echo (isset($value['average'])) ? $value['average'] : 0; ?></td>
                                        <td><?php echo (isset($value['suggest_order'])) ? $value['suggest_order'] : 0; ?></td>
                                      <?php } ?>
                                    </tr>
                                  <?php } ?>
                                <?php } ?>
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
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('.datatable').DataTable();
  </script>
  <script src="js/custom/inventory.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <!-- End custom js for this page-->

  <!-- script for custom validation -->
<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>
</body>


</html>
