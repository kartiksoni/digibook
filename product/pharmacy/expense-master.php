<?php $title = "Expense Master"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php //include('include/permission.php'); ?>

<?php 
  $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
?>

<?php 

  if(isset($_POST['submit'])){
    
    $ex_date = (isset($_POST['ex_date']) && $_POST['ex_date'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['ex_date']))) : '';
    $transport = (isset($_POST['transport'])) ? $_POST['transport'] : '';
    $amount = (isset($_POST['amount'])) ? $_POST['amount'] : 0;
    $remarks = (isset($_POST['remarks'])) ? $_POST['remarks'] : '';
    $status = (isset($_POST['status']) && $_POST['status'] != '') ? $_POST['status'] : 1;
    
    $is_file = (isset($_POST['is_file']) && $_POST['is_file'] == 1) ? 1 : 0;
      
    if(($is_file == 1) && (isset($_FILES['file_name']) && !empty($_FILES['file_name']))){
        if (!file_exists('expense_file')) {
          mkdir('expense_file', 0777, true);
        }
  
        $filename = $_FILES['file_name']['name'];
        $filename = preg_replace("/\.[^.]+$/", "", $filename);
        $ext = pathinfo($_FILES['file_name']['name'], PATHINFO_EXTENSION);
        $final_filename = $filename . '_'. mt_rand(100000,999999) . "." . $ext;
        $target_path = 'expense_file/'.$final_filename;
        if(!move_uploaded_file($_FILES['file_name']['tmp_name'], $target_path)) {  
            $_SESSION['msg']['fail'] = "Record save fail! Because file not uploaded please try again.";
                header('Location:'.basename($_SERVER['PHP_SELF']));exit;
        }
    }else{
        $final_filename = '';
    }
    
    $uid = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : '';
    $date = date('Y-m-d H:i:s');
   
    if(isset($_GET['id']) && $_GET['id'] != ''){
        $query = "UPDATE expense_master SET ";
    }else{
        $query = "INSERT INTO expense_master SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', ";
    }
    $query .= "ex_date = '".$ex_date."', transport = '".$transport."', amount = '".$amount."', remarks = '".$remarks."', is_file = '".$is_file."', file_name = '".$final_filename."', status = '".$status."', ";
    
    if(isset($_GET['id']) && $_GET['id'] != ''){
        $query .= "modified = '".$date."', modifiedby = '".$uid."' WHERE id = '".$_GET['id']."'";
    }else{
        $query .= "created = '".$date."', createdby = '".$uid."'";
    }
    
    $res = mysqli_query($conn, $query);
    if($res){
        if(isset($_GET['id']) && $_GET['id'] != ''){
            $_SESSION['msg']['success'] = 'Expense Update successfully.';
        }else{
            $_SESSION['msg']['success'] = 'Expense Add successfully.';
        }
    }else{
        if(isset($_GET['id']) && $_GET['id'] != ''){
            $_SESSION['msg']['fail'] = 'Expense Update Fail!';
        }else{
            $_SESSION['msg']['fail'] = 'Expense Add Fail!';
        }
    }
    header('location:expense-master.php');exit;
  }
    if(isset($_GET['id']) && $_GET['id'] != ''){
        $editQuery = "SELECT * FROM expense_master WHERE id = '".$_GET['id']."' AND pharmacy_id = '".$pharmacy_id."'";
        $editRes = mysqli_query($conn, $editQuery);
        if($editRes && mysqli_num_rows($editRes) > 0){
            $data = mysqli_fetch_assoc($editRes);
        }else{
            $_SESSION['msg']['fail'] = 'Invalid Request! Try Again.';
            header('location:expense-master.php');exit;
        }
    }
  
    if(isset($_GET['download']) && $_GET['download'] != ''){
        download('expense_file/'.$_GET['download']);
    }
    
    if(isset($_GET['remove']) && $_GET['remove'] != ''){
        $findFileQ = "SELECT id, file_name FROM expense_master WHERE pharmacy_id = '".$pharmacy_id."' AND id='".$_GET['remove']."'";
        $findFileR = mysqli_query($conn, $findFileQ);
        if($findFileR && mysqli_num_rows($findFileR) > 0){
            $findFileRow = mysqli_fetch_assoc($findFileR);
            $filename = (isset($findFileRow['file_name'])) ? $findFileRow['file_name'] : '';
            if(file_exists('expense_file/'.$filename)){
                if(!unlink('expense_file/'.$filename)){
                    $_SESSION['msg']['fail'] = "File Remove Fail! Try again.";
                    header('Location:expense-master.php?id='.$_GET['remove']);exit;
                }
            }
            $update = mysqli_query($conn, "UPDATE expense_master SET is_file = 0, file_name = '' WHERE id = '".$_GET['remove']."'");
            if($update){
                $_SESSION['msg']['success'] = "File Remove Successfully";
                header('Location:expense-master.php?id='.$_GET['remove']);exit;
            }else{
                $_SESSION['msg']['fail'] = "File Remove Fail! Try again.";
                header('Location:expense-master.php?id='.$_GET['remove']);exit;
            }
        }else{
            $_SESSION['msg']['fail'] = "Somthing Want Wrong! Try again.";
            header('Location:expense-master.php?id='.$_GET['remove']);exit;
        }
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Expense Master</title>
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
          <span id="errormsg"></span>
          <div class="row">
            
         
            
            <!-- Service Master Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Expense Master</h4>
                    <hr class="alert-dark">
                    <br>
                    <form class="forms-sample" method="POST" autocomplete="off" enctype="multipart/form-data">
                        <div class="form-group row">
                            <div class="col-12 col-md-4">
                              <label for="ex_date">Expense Date <span class="text-danger">*</span></label>
                              <input type="text" name="ex_date" class="form-control datepicker" placeholder="Expense Date" value="<?php echo (isset($data['ex_date']) && $data['ex_date'] != '') ? date('d/m/Y',strtotime($data['ex_date'])) : date('d/m/Y'); ?>" required>
                            </div>
                            
                            <div class="col-12 col-md-4">
                              <label for="transport">Select Transport <span class="text-danger">*</span></label>
                              <select class="js-example-basic-single" style="width:100%" name="transport" required  data-parsley-errors-container="#error-transport">
                                <?php 
                                  $allTransport = [];
                                  $getTransportQ = "SELECT id, name FROM transport_master WHERE pharmacy_id = '".$pharmacy_id."' ORDER BY name";
                                  $getTransportR = mysqli_query($conn, $getTransportQ);
                                  if($getTransportR && mysqli_num_rows($getTransportR) > 0){
                                    while($getTransportRow = mysqli_fetch_assoc($getTransportR)){
                                        $allTransport[] = $getTransportRow;
                                    }
                                  }
                                ?>
                                <option value="">Select Transport</option>
                                <?php if(isset($allTransport) && !empty($allTransport)){ ?>
                                    <?php foreach ($allTransport as $key => $value) { ?>
                                      <option value="<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>" <?php echo (isset($data['transport']) && $data['transport'] == $value['id']) ? 'selected' : ''; ?> ><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></option>
                                    <?php } ?>
                                <?php } ?>
                              </select>
                              <span id="error-transport"></span>
                            </div>
                            
                            
                            
                            <div class="col-12 col-md-4">
                              <label for="amount">Amount(Rs) <span class="text-danger">*</span></label>
                              <input type="text" name="amount" class="form-control onlynumber" placeholder="Amount(Rs)" value="<?php echo (isset($data['amount'])) ? $data['amount'] : ''; ?>" required>
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <div class="col-12 col-md-4">
                              <label for="auth_key">Remarks</label>
                              <textarea class="form-control" placeholder="Remarks" name="remarks"><?php echo (isset($data['remarks'])) ? $data['remarks'] : ''; ?></textarea>
                            </div>
                            
                            <div class="col-12 col-md-3">
                                <label for="exampleInputName1">Status</label>
                                <div class="row no-gutters">
                                
                                    <div class="col">
                                        <div class="form-radio">
                                        <label class="form-check-label">
                                        <?php
                                        $active = '';
                                          if(isset($_GET['id']) && $_GET['id'] != ''){
                                            $active = (isset($data['status']) && $data['status'] == 1) ? 'checked' : '';
                                          }else{
                                            $active = 'checked';
                                          }
    
                                        ?>
                                        <input type="radio" class="form-check-input" name="status" value="1" <?php echo $active; ?> >
                                        Active
                                        </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col">
                                        <div class="form-radio">
                                        <label class="form-check-label">
                                          <?php 
                                            $deactive = '';
                                            if(isset($_GET['id']) && $_GET['id'] != ''){
                                              $deactive = (isset($data['status']) && $data['status'] == 0) ? 'checked' : '';
                                            }
                                          ?>
                                        <input type="radio" class="form-check-input" name="status" value="0" <?php echo $deactive; ?> >
                                        Deactive
                                        </label>
                                        </div>
                                    </div>
                                
                                </div>
                            </div>
                            
                            <div class="col-md-1">
                                <div class="sales-filter-btns-right display-3" style="margin-top:40px;">
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input <?php if(isset($data['is_file']) && $data['is_file'] == "1"){echo "checked"; } ?> type="checkbox"  id="is_file" value="1" class="form-check-input ModuleChange" name="is_file">
                                        File
                                      </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 <?php echo (isset($data['is_file']) && $data['is_file'] == 1) ? 'display-block' : 'display-none'; ?>" id="file_upload_div" style="padding-top:15px;">
                                <?php if((isset($data['file_name']) && $data['file_name'] != '') && file_exists('expense_file/'.$data['file_name'])){ ?>
                                    <div style="margin-top:20px;"><i class="fa fa-paperclip"></i> <a href="?download=<?php echo $data['file_name']; ?>"><?php echo $data['file_name']; ?></a>  &nbsp;&nbsp;<a href="?remove=<?php echo $data['id']; ?>" class="text-danger" onclick="return confirm('Are you sure you want to remove this attechment?');"><i class="fa fa-trash-o"></i></a></div>
                                <?php }else{ ?>
                                    <label for="file_name">Select File</label>
                                    <input type="file" name="file_name" class="form-control">
                                <?php } ?>
                            </div>
                            
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <a href="configuration.php" class="btn btn-light">Cancel</a>
                            <button name="submit" type="submit" name="submit" class="btn btn-success">Submit</button>
                          </div>
                        </div>
                    </form>
                    
                    <h4 class="card-title" style="margin-top:50px;">View Expense Master</h4>
                    <hr class="alert-dark">
                    <div class="row">
                        <div class="col-12">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Expense Date</th>
                                        <th>Transport Name</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts -->   
                                  <?php
                                    $getAllExpenseQ = "SELECT ex.*, t.name as transport_name  FROM `expense_master` ex LEFT JOIN transport_master t ON ex.transport = t.id WHERE ex.pharmacy_id = '".$pharmacy_id."' ORDER BY ex.id DESC";
                                    $getAllExpenseR = mysqli_query($conn,$getAllExpenseQ);
                                  ?>
                                  <?php if($getAllExpenseR && mysqli_num_rows($getAllExpenseR) > 0){ $i = 1; ?>
                                    <?php while ($row = mysqli_fetch_array($getAllExpenseR)) { ?>
                                      <tr>
                                          <td><?php echo $i; ?></td>
                                          <td><?php echo (isset($row['ex_date']) && $row['ex_date'] != '') ? date('d/m/Y',strtotime($row['ex_date'])) : ''; ?></td>
                                          <td><?php echo (isset($row['transport_name'])) ? $row['transport_name'] : ''; ?></td>
                                          <td class="text-right"><?php echo (isset($row['amount']) && $row['amount'] != '') ? amount_format(number_format($row['amount'], 2, '.', '')) : ''; ?></td>
                                          <td>
                                            <button type="button" class="btn btn-sm btn-toggle changestatus <?php echo (isset($row['status']) && $row['status'] == 1) ? 'active' : ''; ?>" data-table="expense_master" data-id="<?php echo $row['id']; ?>" data-toggle="button" aria-pressed="<?php echo (isset($row['status']) && $row['status'] == 1) ? true : false; ?>" autocomplete="off">
                                              <div class="handle"></div>
                                            </button>
                                          </td>
                                          <td>
                                            <a class="btn  btn-behance p-2" href="expense-master.php?id=<?php echo $row['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                            <!--<a class="btn  btn-danger p-2" href="sms-config.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure want to delete?')" title="Delete"><i class="fa fa-trash-o mr-0"></i></a>-->
                                          </td>
                                      </tr>
                                      <?php $i++; } ?>
                                  <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                     <hr>
                   <br>
                   <a href="configuration.php" class="btn btn-light">Back</a>
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
     $('.datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy',
      autoclose : true
    });
  </script>
  
  <!-- change status js -->
  <script src="js/custom/expense_master.js"></script>
  <script src="js/custom/statusupdate.js"></script>
  <script src="js/custom/onlynumber.js"></script>
  
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  <!-- End custom js for this page-->
  
</body>


</html>
