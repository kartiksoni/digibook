<?php
include('include/usertypecheck.php');

$owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
$admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
$pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
$financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';


if (isset($_POST['search'])){
   
    $type = (isset($_POST['type'])) ? $_POST['type'] : '';
    $company_name = (isset($_POST['company_name'])) ? $_POST['company_name'] : '';
    $company_id = (isset($_POST['company_id'])) ? $_POST['company_id'] : '';
    $selectedcompany = (isset($_POST['selectedcompany'])) ? $_POST['selectedcompany'] : '';
    $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : ''; 
    $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';
    $stock = $_POST['stock'];
    $searchdata = reorderQuantityReport($type,$company_name,$selectedcompany,$from,$to,$stock);
    
    // echo "<pre>";
    // print_r($searchdata);exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks | Reorder Quantity Report</title>
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
  <link rel="stylesheet" href="css/parsley.css">

  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
  <style type="text/css">
     .ui-autocomplete { z-index:2147483647 !important; }
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
          
            <!-- Inventory Form ------------------------------------------------------------------------------------------------------>
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Reorder Quantity Report</h4>
                    <hr class="alert-dark">
                 
                  <form class="forms-sample" method="post" autocomplete="off" id="search" >
                    <div class="col-md-12">
                      <div class="form-group row">
                        <div class="col-12 col-md-8 col-sm-12">
                            <label for="exampleInputName1">Select Type  </label>
                            <div class="row no-gutters">
                              <div class="col">
                                  <div class="form-radio">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input" name="type" id="company" value="1" required <?php echo (isset($_POST['type']) && $_POST['type'] == 1) ? 'checked' : '' ?>>
                                      Company wise  
                                    </label>
                                  </div>
                              </div>

                              <div class="col">
                                  <div class="form-radio">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input" name="type" id="allcompany" value="2" required <?php echo (isset($_POST['type']) && $_POST['type'] == 2) ? 'checked' : '' ?>>
                                      All Company wise
                                    </label>
                                  </div>
                              </div>
                              <div class="col">
                                  <div class="form-radio">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input" name="type" id="selectedcompany" value="3" required <?php echo (isset($_POST['type']) && $_POST['type'] == 3) ? 'checked' : '' ?>>
                                      Selected Company wise
                                    </label>
                                  </div>
                              </div>
                            </div>
                        </div>
                      </div>

                       <div class="form-group row" >
                        <div class="col-12 col-md-2" id= "trans_com" <?php echo (isset($_POST['type']) && $_POST['type'] == 1) ? "style='display:block;'" : "style='display:none;'" ?>>
                          <label for="exampleInputName1">Select Company</label>
                          <input type="text" class="form-control" placeholder="Company" id = "company_list" name = "company_name" autocomplete="nope" data-parsley-errors-container="#error-company_id" autocomplete="off" value="<?php echo (isset($_POST['company_name']) && !empty($_POST['company_name'])) ? $_POST['company_name'] : '' ?>">
                           <small class="companyerror text-danger"></small>
                          <div id="error-company_id"></div>
                        </div>
                        <input type="hidden" name="company_id" id= "company_id" value="<?php echo (isset($_POST['type']) && $_POST['type'] == 1) ? $_POST['company_id'] : '' ?>">
                        <div class="col-12 col-md-2" id= "trans_all" <?php echo (isset($_POST['type']) && $_POST['type'] == 3) ? "style='display:block;'" : "style='display:none;'" ?>>
                          <label for="exampleInputName1">Select Company wise</label>
                          
                          <?php if(isset($_POST['type']) && $_POST['type'] == 3){?>
                          
                              <select class="js-example-basic-single" style="width:100%" name="selectedcompany[]" id="companyall" multiple="multiple" data-parsley-errors-container="#error-com">
                                  
                                 
                                        <option value="" <?php if(in_array("", $selectedcompany)){ echo "selected";}?> >Select All company</option>
                                        
                                        <?php
                                          $allCompany = [];
                                          $dmfgQ = "SELECT id , mfg_company FROM `product_master` WHERE pharmacy_id = '".$pharmacy_id."' GROUP BY mfg_company ORDER BY mfg_company";
                                          $mfgR = mysqli_query($conn, $dmfgQ);
                                          if($mfgR && mysqli_num_rows($mfgR) > 0){
                                            while ($mfgRow = mysqli_fetch_array($mfgR)) {
                                              $dtr['id'] = (isset($mfgRow['id'])) ? $mfgRow['id'] : '';
                                              $dtr['company'] = (isset($mfgRow['mfg_company'])) ? $mfgRow['mfg_company'] : '';
                                              $allCompany[] = $dtr;
                                            }
                                          }
                                     
                                        if(isset($allCompany) && !empty($allCompany)){
                                            foreach ($allCompany as $key => $value) {?>
                                                <option value="<?php echo $value['company'];?>" <?php if(in_array($value['company'], $selectedcompany)){ echo "selected";}?> ><?php echo $value['company']; ?></option><?php   
                                            }
                                        }?>
                              </select>
                              
                          <?php } else {?>
                                <select class="js-example-basic-single" style="width:100%" name="selectedcompany[]" id="companyall" multiple="multiple" data-parsley-errors-container="#error-com">
                                    <option value="">Select All company</option>
                                    
                                    <?php
                                      $allCompany = [];
                                      $dmfgQ = "SELECT id , mfg_company FROM `product_master` WHERE pharmacy_id = '".$pharmacy_id."' GROUP BY mfg_company ORDER BY mfg_company";
                                      $mfgR = mysqli_query($conn, $dmfgQ);
                                      if($mfgR && mysqli_num_rows($mfgR) > 0){
                                        while ($mfgRow = mysqli_fetch_array($mfgR)) {
                                          $dtr['id'] = (isset($mfgRow['id'])) ? $mfgRow['id'] : '';
                                          $dtr['company'] = (isset($mfgRow['mfg_company'])) ? $mfgRow['mfg_company'] : '';
                                          $allCompany[] = $dtr;
                                        }
                                      }
                                     
                                    if(isset($allCompany) && !empty($allCompany)){
                                        foreach ($allCompany as $key => $value) {?>
                                            <option value="<?php echo $value['company'] ?>" ><?php echo $value['company']; ?></option><?php   
                                        }
                                    }?>
                              </select>
                          <?php }?>
                          <div id="error-com"></div>
                        </div>
                      </div>

                      <div class="form-group row">
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">From Date</label>
                          <div class="input-group date datepicker">
                            <input type="text" class="form-control border" placeholder="dd/mm/yyyy" name = "from" required data-parsley-errors-container="#error-from" value="<?php echo (isset($_POST['from'])) ? $_POST['from'] : date('d/m/Y'); ?>"> 
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          <div id="error-from"></div>
                        </div>
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName2">To Date</label>
                          <div class="input-group date datepicker1">
                            <input type="text" class="form-control border" placeholder="dd/mm/yyyy" name = "to" required data-parsley-errors-container="#error-to" value="<?php echo (isset($_POST['to'])) ? $_POST['to'] : date('d/m/Y'); ?>">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          <div id="error-to"></div>
                        </div>
                        <div class="col-12 col-md-2">
                            <label >Stock &#37; Of Sales</label>
                            <input type="text" class="form-control onlynumber" placeholder="0.00" name = "stock" value="<?php echo (isset($_POST['stock']) && !empty($_POST['stock'])) ? $_POST['stock'] : '' ?>" required> 
                        </div>
                        <div class="col-12 col-md-4">
                          <button type = "submit" name="search" class="btn btn-success mt-30" style="margin-top:30px;">Search</button>
                          <?php if(isset($searchdata)){

                            $selectedcompanystring = "";
                            if(isset($_POST['selectedcompany'])){
                                foreach($_POST['selectedcompany'] as $company){
                                    $selectedcompanystring .= "&selectedcompany[]=$company";
                                }
                            }
                          ?>
                            <a href="reorder-qty-report-print.php?from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>&type=<?php echo (isset($_POST['type'])) ? $_POST['type'] : '';?>&company_name=<?php echo (isset($_POST['company_name'])) ? $_POST['company_name'] : '';?>&stock=<?php echo (isset($_POST['stock'])) ? $_POST['stock'] : '';?><?php echo $selectedcompanystring;?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </form>
                
                </div>
              </div>
            </div>
            
            <!-------------------------------- Table ------------------------------------>
            <?php if(isset($searchdata)){?>
                <div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                        <!-- TABLE STARTS -->
                        <div class="col mt-3">
                            <form id="reorder_form" action="">
                                <div class="row">
                                    <div class="col-12">
                                      <table class="table reordertable">
                                        <thead>
                                            <tr>
                                                <th style="padding:13px!important;text-align: center;"><input type="checkbox" id="pr-checkbox-all"></th>
                                                <th>Sr No</th>
                                                <th>Product Name</th>
                                                <th>Opening Qty</th>
                                                <th>Purchase Qty</th>
                                                <th>Sale Qty</th>
                                                <th>Closing Qty</th>
                                                <th>Expiry Date</th>
                                                <th>Rem</th>
                                                <th>Average Sale</th>
                                                <th>Order Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(!empty($searchdata)){?>
                                                <?php foreach($searchdata as $i => $dataRow){?>
                                                <tr>
                                                    <td style="padding:13px!important;text-align: center;"><input type="checkbox" name="product" class="pr-checkbox"></td>
                                                    <td><?php echo $i + 1;?></td>
                                                    <td><?php echo $dataRow['product_name'];?></td>
                                                    <td><?php echo $dataRow['opening_qty'];?></td>
                                                    <td><?php echo $dataRow['purchase_qty'];?></td>
                                                    <td><?php echo $dataRow['sale_qty'];?></td>
                                                    <td><?php echo $dataRow['closing_qty'];?></td>
                                                    <td><?php echo $dataRow['ex_date'];?></td>
                                                    <td><?php echo $dataRow['rem_string'];?></td>
                                                    <td><?php echo $dataRow['average_sale'];?></td>
                                                    <td><?php echo $dataRow['order_new'];?></td>
                                                </tr>
                                                <?php }?>
                                            <?php } else {?>
                                                <tr style="text-align: center;">
                                                    <td colspan="9"><?php echo "No Data Found.";?></td>                                            
                                                </tr>
                                            <?php }?>
                                        </tbody>
                                      </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-right mt-3">
                                      <button type="submit" id="btn-add-po" class="btn btn-success">Add Purchase Order</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                  </div>
                </div>
            <?php }?>

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
   <script src="js/jquery-ui.js"></script>
 
  <!-- Custom js for this page Datatables-->
  <!--<script src="js/data-table.js"></script>-->
  <script src="js/custom/reorder-qty-report.js"></script>
    <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
 
  <script src="js/parsley.min.js"></script>
  <script type="text/javascript">
    $('form').parsley();
  </script>
  <script src="js/custom/onlynumber.js"></script>
  <!-- End custom js for this page-->
</body>
</html>