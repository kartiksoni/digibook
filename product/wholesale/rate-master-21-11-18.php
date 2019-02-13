<?php $title = "Rate Master"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php //include('include/permission.php'); ?>


<?php
  $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : NULL;
  $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : NULL;
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : NULL;
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : NULL;

    if(isset($_POST['submit'])){
        $date = date('Y-m-d H:i:s');
        if(isset($_POST['product_id']) && $_POST['product_id'] != ''){
            if((isset($_POST['rate_id']) && !empty($_POST['rate_id'])) && (isset($_POST['rate_per']) && !empty($_POST['rate_per']))){
                $count = count($_POST['rate_id']);
                $output = [];
                for ($i=0; $i < $count; $i++) {
                    $product_id = (isset($_POST['product_id'])) ? $_POST['product_id'] : '';
                    $rate_id = (isset($_POST['rate_id'][$i])) ? $_POST['rate_id'][$i] : '';
                    $rate_per = (isset($_POST['rate_per'][$i]) && $_POST['rate_per'][$i] != '') ? $_POST['rate_per'][$i] : 0;
                    $existQ = "SELECT id FROM rate_master WHERE pharmacy_id = '".$pharmacy_id."' AND product_id = '".$product_id."' AND rate_id = '".$rate_id."'";
                    $existR = mysqli_query($conn, $existQ);
                    if($existR && mysqli_num_rows($existR) > 0){
                        $existRow = mysqli_fetch_assoc($existR);
                        $query = "UPDATE rate_master SET product_id = '".$product_id."', rate_id = '".$rate_id."', rate_per = '".$rate_per."', modified = '".$date."', modifiedby = '".$_SESSION['auth']['id']."' WHERE id = '".$existRow['id']."'";
                    }else{
                        $query = "INSERT INTO rate_master SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', product_id = '".$product_id."', rate_id = '".$rate_id."', rate_per = '".$rate_per."', created = '".$date."', createdby = '".$_SESSION['auth']['id']."'";
                    }
                    $res = mysqli_query($conn, $query);
                    if($res){
                        $output[] = 1;
                    }else{
                        $output[] = 0;
                    }
                }
            
                if(in_array(0, $output)){
                    $_SESSION['msg']['fail'] = 'Some Rate Save Fail!Please Try Again.';
                    header('location:rate-master.php?id='.$product_id);exit;
                }else{
                    $_SESSION['msg']['success'] = 'Record Save Successfully.';
                }
            }else{
                $_SESSION['msg']['fail'] = 'Record Save Fail!';
            }
        }else{
            $_SESSION['msg']['fail'] = 'Record Save Fail! Please select Product.';
        }
        header('location:rate-master.php');exit;
    }
    if(isset($_GET['id']) && $_GET['id'] != ''){
        $editQuery = "SELECT * FROM rate_master WHERE product_id = '".$_GET['id']."' AND pharmacy_id = '".$pharmacy_id."'";
        $resEdit = mysqli_query($conn, $editQuery);
        if($resEdit && mysqli_num_rows($resEdit) > 0){
            $editdata = [];
            while($resRow = mysqli_fetch_assoc($resEdit)){
                $editdata['product_id'] = $resRow['product_id'];
                $editdata['detail'][$resRow['rate_id']] = $resRow['rate_per'];
            }
        }else{
            $_SESSION['msg']['fail'] = 'Invalid Request! Please Try again.';
            header('location:rate-master.php');exit;
        }
    }
    
    function getAllProductRate(){
        global $conn;
        global $pharmacy_id;
        $data = [];
        
        $query = "SELECT rm.id, rm.product_id, GROUP_CONCAT(DISTINCT rm.rate_id SEPARATOR ',') as rate_id, GROUP_CONCAT(rm.rate_per SEPARATOR ',') as rate_per, pm.product_name, pm.batch_no FROM rate_master rm LEFT JOIN product_master pm ON rm.product_id = pm.id WHERE rm.pharmacy_id = '".$pharmacy_id."' GROUP BY rm.product_id";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
                $tmpdata = [];
                $tmpdata['id'] = $row['id'];
                $tmpdata['product_id'] = $row['product_id'];
                $tmpdata['product_name'] = $row['product_name'];
                $tmpdata['batch_no'] = $row['batch_no'];
                $tmpdata['detail'] = [];
                
                $rateid = (isset($row['rate_id'])) ? $row['rate_id'] : '';
                $rateper = (isset($row['rate_per'])) ? $row['rate_per'] : '';
                
                $rateidArray = explode(",",$rateid);
                $rateperArray = explode(",",$rateper);
                
                if(!empty($rateidArray)){
                    foreach($rateidArray as $key => $value){
                        $findRateQ = "SELECT id, name FROM rate_group_master WHERE pharmacy_id = '".$pharmacy_id."' AND id = '".$value."'";
                        $findRateR = mysqli_query($conn, $findRateQ);
                        if($findRateR && mysqli_num_rows($findRateR) > 0){
                            $findRateRow = mysqli_fetch_assoc($findRateR);
                            $tmpdatasec['id'] = (isset($findRateRow['id'])) ? $findRateRow['id'] : '';
                            $tmpdatasec['name'] = (isset($findRateRow['name'])) ? $findRateRow['name'] : '';
                            $tmpdatasec['per'] = (isset($rateperArray[$key])) ? $rateperArray[$key] : '';
                            $tmpdata['detail'][] = $tmpdatasec;
                        }
                    }
                }
                $data[] = $tmpdata;
            }
        }
        return $data;
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Rate Master</title>
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
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
  <link rel="stylesheet" href="css/parsley.css">
  <link rel="stylesheet" href="css/toggle/style.css">
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
          <span id="errormsg"></span>
          <div class="row">
            
         
            
            <!-- Service Master Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Rate Master</h4>
                  <hr class="alert-dark">
                  <br>
                  <form class="forms-sample" method="POST" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-12 col-md-4">
                          <label for="product_id">Select Product</label>
                          <select class="js-example-basic-single" name="product_id" style="width:100%" data-parsley-errors-container="#error-product" required>
                            <option value="">Select Product</option>
                            <?php 
                              $getProductQ = "SELECT id, product_name, batch_no FROM product_master WHERE pharmacy_id = '".$pharmacy_id."'";
                              $getProductR = mysqli_query($conn, $getProductQ);
                              if($getProductR && mysqli_num_rows($getProductR) > 0){
                                while ($getProductRow = mysqli_fetch_assoc($getProductR)) {
                            ?>
                              <option value="<?php echo (isset($getProductRow['id'])) ? $getProductRow['id'] : ''; ?>" <?php echo (isset($editdata['product_id']) && $editdata['product_id'] == $getProductRow['id']) ? 'selected' : ''; ?> ><?php echo (isset($getProductRow['product_name'])) ? $getProductRow['product_name'] : ''; ?><?php echo (isset($getProductRow['batch_no']) && $getProductRow['batch_no'] != '') ? ' - '.$getProductRow['batch_no'] : ''; ?></option>
                            <?php
                                }
                              }
                            ?>
                          </select>
                          <span id="error-product"></span>
                        </div>

                        <?php 
                          $getAllRateQ = "SELECT id, name FROM rate_group_master WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1";
                          $getAllRateR = mysqli_query($conn, $getAllRateQ);
                          if($getAllRateR && mysqli_num_rows($getAllRateR) > 0){
                            while ($getAllRateRow = mysqli_fetch_assoc($getAllRateR)) {
                        ?>
                          <div class="col-12 col-md-4">
                              <label><?php echo (isset($getAllRateRow['name']) && $getAllRateRow['name'] != '') ? ucwords(strtolower($getAllRateRow['name'])).' (%)' : 'Unknown Rate'; ?><span class="text-danger">*</span></label>
                              <input type="hidden" name="rate_id[]" value="<?php echo (isset($getAllRateRow['id'])) ? $getAllRateRow['id'] : ''; ?>">
                              <input type="text" name="rate_per[]" value="<?php echo (isset($editdata['detail'][$getAllRateRow['id']])) ? $editdata['detail'][$getAllRateRow['id']] : ''; ?>" class="form-control onlynumber" placeholder="<?php echo (isset($getAllRateRow['name']) && $getAllRateRow['name'] != '') ? ucwords(strtolower($getAllRateRow['name'])).' (%)' : 'Unknown Rate'; ?>" required>
                          </div>
                        <?php
                            }
                          }
                        ?>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <a href="rate-master.php" class="btn btn-light">Cancel</a>
                        <button name="submit" type="submit" name="submit" class="btn btn-success">Submit</button>
                      </div>
                    </div>
                  </form>
                  <div class="col mt-30">
                    <h4 class="card-title">View Rate Master</h4>
                    <hr class="alert-dark">
                       <div class="row">
                            <div class="col-12">
                              <table class="table datatable">
                                <thead>
                                  <tr>
                                      <th class="text-center">Sr No.</th>
                                      <th>Product Name</th>
                                      <th>Batch No.</th>
                                      <th>
                                          <span class="pull-left">Rate Name</span>
                                          <span class="pull-right">Rate Percentage(%)</span>
                                      </th>
                                      <th class="text-center">Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $data = getAllProductRate();
                                            if(!empty($data)){
                                                foreach($data as $key => $value){
                                    ?>
                                           <tr>
                                               <td><?php echo $key+1; ?></td>
                                               <td><?php echo (isset($value['product_name'])) ? $value['product_name'] : ''; ?></td>
                                               <td><?php echo (isset($value['batch_no'])) ? $value['batch_no'] : ''; ?></td>
                                               
                                               <?php if(isset($value['detail']) && !empty($value['detail'])){ ?>
                                                <td>
                                                    <table width="100%">
                                                        <?php foreach($value['detail'] as $k1 => $v1){ ?>
                                                            <tr>
                                                                <td>
                                                                    <span class="pull-left"><?php echo (isset($v1['name'])) ? $v1['name'] : ''; ?></span>
                                                                    <span class="pull-right"><?php echo (isset($v1['per'])) ? $v1['per'] : ''; ?></span>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </table>
                                                </td>
                                               <?php }else{ ?>
                                                <td></td>
                                               <?php } ?>
                                               <td class="text-center">
                                                   <a class="btn  btn-behance p-2" href="rate-master.php?id=<?php echo (isset($value['product_id'])) ? $value['product_id'] : ''; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                               </td>
                                           </tr>         
                                    <?php
                                                }
                                            } 
                                    ?>
                                  
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

  <script src="js/parsley.min.js"></script>
    <script type="text/javascript">
      $('form').parsley();
    </script>
 
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('.datatable').DataTable();
  </script>
  
  <!-- change status js -->
  <script src="js/custom/statusupdate.js"></script>
  <script src="js/custom/onlynumber.js"></script>
  <!-- End custom js for this page-->
  
</body>


</html>
