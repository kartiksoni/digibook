<?php $title="Journal Vouchar"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>
<?php

  $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
  
  if(isset($_GET['id'])){
    $editquery = "SELECT id, voucher_date, voucher_no, remarks, is_file, file_name, is_investment FROM `journal_vouchar`WHERE id ='".$_GET['id']."' AND pharmacy_id = '".$pharmacy_id."'";
    $editresult = mysqli_query($conn,$editquery);

    if($editresult && mysqli_num_rows($editresult) > 0){
      $editdata = mysqli_fetch_assoc($editresult);
      if(isset($editdata['id']) && $editdata['id'] != ''){
        $editsubQuery = "SELECT * FROM journal_vouchar_details WHERE voucher_id = '".$editdata['id']."'";
        $editsubRes = mysqli_query($conn, $editsubQuery);
        if($editsubRes && mysqli_num_rows($editsubRes) > 0){
          while ($editsubRow = mysqli_fetch_assoc($editsubRes)) {
            $ledger = countRunningBalance($editsubRow['particular']);
            $editsubRow['running_balance'] = (isset($ledger['running_balance']) && $ledger['running_balance'] != '') ? $ledger['running_balance'] : 0;
            $editdata['detail'][] = $editsubRow;
          }
        }
      }
    }
  }

  
  if(isset($_POST['submit'])){
    $count = (isset($_POST['type']) && !empty($_POST['type'])) ? count($_POST['type']) : 0;
    if($count > 0){

      if(isset($_GET['id']) && $_GET['id'] != ''){
        $voucher_no = (isset($_POST['voucher_no'])) ? $_POST['voucher_no'] : '';
      }else{
        $voucher_no = getJournalVoucherNo();
      }

      $voucher_date = (isset($_POST['voucher_date']) && $_POST['voucher_date'] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['voucher_date']))) : NULL;
      $is_investment = (isset($_POST['is_investment']) && $_POST['is_investment'] != '') ? $_POST['is_investment'] : 0;
      // $remarks = (isset($_POST['remarks'])) ? $_POST['remarks'] : '';
      
      $is_file = (isset($_POST['is_file']) && $_POST['is_file'] == 1) ? 1 : 0;
      
      if(($is_file == 1) && (isset($_FILES['file_name']) && !empty($_FILES['file_name']))){
        if (!file_exists('journal_voucher_file')) {
          mkdir('journal_voucher_file', 0777, true);
        }
    
        $filename = $_FILES['file_name']['name'];
        $filename = preg_replace("/\.[^.]+$/", "", $filename);
        $ext = pathinfo($_FILES['file_name']['name'], PATHINFO_EXTENSION);
        $final_filename = $filename . '_'. mt_rand(100000,999999) . "." . $ext;
        $target_path = 'journal_voucher_file/'.$final_filename;
        if(!move_uploaded_file($_FILES['file_name']['tmp_name'], $target_path)) {  
            $_SESSION['msg']['fail'] = "Record save fail! Because file not uploaded please try again.";
                header('Location:'.basename($_SERVER['PHP_SELF']));exit;
        }
      }else{
          $final_filename = '';
      }
      
      if(isset($_GET['id']) && $_GET['id'] != ''){
        $query = "UPDATE journal_vouchar SET ";
      }else{
        $query = "INSERT INTO journal_vouchar SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', ";
      }
      $query .= "voucher_date = '".$voucher_date."', voucher_no = '".$voucher_no."', is_file = '".$is_file."', file_name = '".$final_filename."', is_investment = '".$is_investment."', ";

      if(isset($_GET['id']) && $_GET['id'] != ''){
        $query .= "modified = '".date('Y-m-d H:i:s')."', modifiedby = '".$_SESSION['auth']['id']."' WHERE id = '".$_GET['id']."'";
      }else{
        $query .= "created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
      }
      
      $res = mysqli_query($conn, $query);
      if($res){
          $voucher_id = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : mysqli_insert_id($conn);
          if(isset($_GET['id']) && $_GET['id'] != ''){
            $deleteQ = "DELETE FROM journal_vouchar_details WHERE voucher_id = '".$_GET['id']."'";
            mysqli_query($conn, $deleteQ);
          }
          for ($i=0; $i < $count; $i++) { 
            $type = (isset($_POST['type'][$i])) ? $_POST['type'][$i] : NULL;
            $particular = (isset($_POST['particular'][$i])) ? $_POST['particular'][$i] : NULL;
            $qty = (isset($_POST['qty'][$i]) && $_POST['qty'][$i] != '') ? $_POST['qty'][$i] : 0;
            $rate = (isset($_POST['rate'][$i]) && $_POST['rate'][$i] != '') ? $_POST['rate'][$i] : 0;
            $debit = (isset($_POST['debit'][$i]) && $_POST['debit'][$i] != '') ? $_POST['debit'][$i] : 0;
            $credit = (isset($_POST['credit'][$i]) && $_POST['credit'][$i] != '') ? $_POST['credit'][$i] : 0;
            $remarks = (isset($_POST['remarks'][$i])) ? $_POST['remarks'][$i] : NULL;

            $subquery = "INSERT INTO journal_vouchar_details SET voucher_id = '".$voucher_id."', type = '".$type."', particular = '".$particular."', qty = '".$qty."', rate = '".$rate."', debit = '".$debit."', credit = '".$credit."', remarks = '".$remarks."', created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
            mysqli_query($conn, $subquery);
          }
          if(isset($_GET['id']) && $_GET['id'] != ''){
            $_SESSION['msg']['success'] = "Voucher update successfully";
          }else{
            $_SESSION['msg']['success'] = "Voucher added successfully";
          }
          header('Location: view-journal-vouchar.php');exit;
      }else{
        if(isset($_GET['id']) && $_GET['id'] != ''){
          $_SESSION['msg']['fail'] = "Voucher update fail! Try again.";
        }else{
          $_SESSION['msg']['fail'] = "Voucher added fail! Try again.";
        }
      }
    header('Location:'.basename($_SERVER['PHP_SELF']));exit;
    }else{
      $_SESSION['msg']['fail'] = "Somthing Want Wrong! Try again.";
      header('Location:'.basename($_SERVER['PHP_SELF']));exit;
    }
  }
?>

<?php
    if(isset($_GET['download']) && $_GET['download'] != ''){
        download('journal_voucher_file/'.$_GET['download']);
    }
    if(isset($_GET['remove']) && $_GET['remove'] != ''){
        $findFileQ = "SELECT id, file_name FROM journal_vouchar WHERE pharmacy_id = '".$pharmacy_id."' AND id='".$_GET['remove']."'";
        $findFileR = mysqli_query($conn, $findFileQ);
        if($findFileR && mysqli_num_rows($findFileR) > 0){
            $findFileRow = mysqli_fetch_assoc($findFileR);
            $filename = (isset($findFileRow['file_name'])) ? $findFileRow['file_name'] : '';
            if(file_exists('journal_voucher_file/'.$filename)){
                if(!unlink('journal_voucher_file/'.$filename)){
                    $_SESSION['msg']['fail'] = "Attechment Remove Fail! Try again.";
                    header('Location:journal-vouchar.php?id='.$_GET['remove']);exit;
                }
            }
            $update = mysqli_query($conn, "UPDATE journal_vouchar SET is_file = 0, file_name = '' WHERE id = '".$_GET['remove']."'");
            if($update){
                $_SESSION['msg']['success'] = "Attechment Remove Successfully";
                header('Location:journal-vouchar.php?id='.$_GET['remove']);exit;
            }else{
                $_SESSION['msg']['fail'] = "Attechment Remove Fail! Try again.";
                header('Location:journal-vouchar.php?id='.$_GET['remove']);exit;
            }
        }else{
            $_SESSION['msg']['fail'] = "Somthing Want Wrong! Try again.";
            header('Location:journal-vouchar.php?id='.$_GET['remove']);exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks | <?php echo (isset($_GET['id']) && $_GET['id'] != '') ? 'Edit' : 'Add'; ?> Journal Voucher</title>
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
          <div class="row">
            <?php include "include/transaction_header.php"; ?>
           <!-- Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <!-- Main Catagory -->
                  
                  <form method="POST" autocomplete="off" enctype="multipart/form-data">
                    <div class="form-group row">
                      <div class="col-12 col-md-2">
                        <label>Voucher No. <span class="text-danger">*</span></label>
                        <input type="text" name="voucher_no" class="form-control" value="<?php echo (isset($editdata['voucher_no'])) ? $editdata['voucher_no'] : getJournalVoucherNo(); ?>" required>
                      </div>
                      <div class="col-12 col-md-2">
                        <label>Voucher Date. <span class="text-danger">*</span></label>
                        <div class="input-group date datepicker">
                          <input type="text" class="form-control" name="voucher_date"  value="<?php echo (isset($editdata['voucher_date']) && $editdata['voucher_date'] != '') ? date('d/m/Y',strtotime($editdata['voucher_date'])) : date('d/m/Y'); ?>" placeholder="DD/MM/YYYY" data-parsley-errors-container="#error-voucher-date" required>
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        <span id="error-voucher-date"></span>

                        <input type="hidden" name="is_investment" value="<?php echo (isset($editdata['is_investment'])) ? $editdata['is_investment'] : 0; ?>" id="is_investment">
                      </div>
                    </div>
                    <table id="entryTable" class="table">
                      <thead>
                        <tr>
                          <th width="20%">Type</th>
                          <th width="20%">Particulars</th>
                          <th class="investment-item <?php echo (isset($editdata['is_investment']) && $editdata['is_investment'] == 1) ? '' : 'display-none'; ?> ">Qty</th>
                          <th class="investment-item <?php echo (isset($editdata['is_investment']) && $editdata['is_investment'] == 1) ? '' : 'display-none'; ?>">Rate</th>
                          <th width="15%">Debit</th>
                          <th width="15%">Credit</th>
                          <th width="15%">Remarks</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <select class="form-control js-example-basic-single type type1" style="width:100%" name="type[]" required data-parsley-errors-container="#error-type">
                              <option value="">Select</option>
                              <?php 
                                $groupQry = "SELECT * FROM `group`";
                                $group = mysqli_query($conn,$groupQry);
                                  while($group_data = mysqli_fetch_assoc($group)){
                              ?>
                                <option value="<?php echo $group_data['id']; ?>"  <?php echo (isset($editdata['detail'][0]['type']) && $editdata['detail'][0]['type'] == $group_data['id']) ? 'selected' : ''; ?> ><?php echo $group_data['name']; ?></option>  
                              <?php } ?>         
                            </select>
                            <span id="error-type"></span>
                          </td>

                          <td>
                            <?php 
                              if(isset($editdata['detail'][0]['type']) && $editdata['detail'][0]['type'] != ''){
                                $editPerticularData = [];
                                $getPerticularQ = "SELECT id, name FROM ledger_master WHERE group_id = '".$editdata['detail'][0]['type']."' AND status = 1 AND pharmacy_id = '".$pharmacy_id."'";
                                $getPerticularR = mysqli_query($conn, $getPerticularQ);
                                if($getPerticularR && mysqli_num_rows($getPerticularR) > 0){
                                  while ($getPerticularRow = mysqli_fetch_assoc($getPerticularR)) {
                                    $editPerticularData[] = $getPerticularRow;
                                  }
                                }
                              }
                            ?>
                            <select class="form-control js-example-basic-single particular" data-name="first" style="width:100%" name="particular[]" required data-parsley-errors-container="#error-particular">
                              <option value="">Select Particulars</option>
                              <?php 
                                if(isset($editPerticularData) && !empty($editPerticularData)){
                                  foreach ($editPerticularData as $keys => $values) {
                              ?>
                                <option value="<?php echo $values['id']; ?>"  <?php echo (isset($editdata['detail'][0]['particular']) && $editdata['detail'][0]['particular'] == $values['id']) ? 'selected' : ''; ?> ><?php echo $values['name']; ?></option>
                              <?php } } ?>
                            </select>
                            <span id="error-particular"></span>
                            <div class="badge badge-primary pull-right ledger_running_balance"></div>
                              <?php  if(isset($_GET['id'])){ ?>
                                <div class="badge badge-primary pull-right ledger_running_balance">
                                  <?php 
                                    if(isset($editdata['detail'][0]['running_balance']) && $editdata['detail'][0]['running_balance'] != ''){
                                      echo amount_format(number_format(abs($editdata['detail'][0]['running_balance']), 2, '.', ''));
                                      echo ($editdata['detail'][0]['running_balance'] > 0) ? ' Dr' : ' Cr';
                                    }
                                  ?>
                                </div>
                              <?php  } ?>
                          </td>

                          <td class="investment-item <?php echo (isset($editdata['is_investment']) && $editdata['is_investment'] == 1) ? '' : 'display-none'; ?>">
                            <input type="text" name="qty[]" class="form-control qty onlynumber" id="qty" placeholder="enter QTY" value = "<?php echo (isset($editdata['detail'][0]['qty'])) ? $editdata['detail'][0]['qty'] : ''; ?>"  >
                          </td>

                          <td class="investment-item <?php echo (isset($editdata['is_investment']) && $editdata['is_investment'] == 1) ? '' : 'display-none'; ?>">
                            <input type="text" name="rate[]" class="form-control rate onlynumber" id="rate" placeholder="enter Rate" value = "<?php echo (isset($editdata['detail'][0]['rate'])) ? $editdata['detail'][0]['rate'] : ''; ?>"  >
                          </td>

                          <td>
                            <input type="text" name="debit[]" class="form-control debit onlynumber" id="debit" placeholder="enter debit amount" value = "<?php echo (isset($editdata['detail'][0]['debit'])) ? $editdata['detail'][0]['debit'] : ''; ?>"  >
                          </td>

                          <td>
                            <input type="text" name="credit[]" class="form-control credit onlynumber" id="credit" placeholder="enter credit amount"  value = "<?php echo (isset($editdata['detail'][0]['credit'])) ? $editdata['detail'][0]['credit'] : ''; ?>" >
                          </td>

                          <td>
                            <textarea name="remarks[]" class="form-control" placeholder="Enter Remarks" rows="1" id="remarks" style="resize:vertical;"><?php echo (isset($editdata['detail'][0]['remarks'])) ? $editdata['detail'][0]['remarks'] : ''; ?></textarea>
                          </td>

                        </tr>

                        <tr>
                          <td>
                            <select class="form-control js-example-basic-single type type1" style="width:100%" name="type[]" required data-parsley-errors-container="#error-type1">
                              <option value="">Select</option>
                              <?php 
                                $groupQry = "SELECT * FROM `group`";
                                $group = mysqli_query($conn,$groupQry);
                                while($group_data = mysqli_fetch_assoc($group)){
                              ?>

                                <option value="<?php echo $group_data['id']; ?>"  <?php echo (isset($editdata['detail'][1]['type']) && $editdata['detail'][1]['type'] == $group_data['id']) ? 'selected' : ''; ?> ><?php echo $group_data['name']; ?></option>

                              <?php } ?>         
                            </select>
                            <span id="error-type1"></span>
                          </td>

                          <td>
                           <?php 
                              if(isset($editdata['detail'][1]['type']) && $editdata['detail'][1]['type'] != ''){
                                $editPerticularData = [];
                                $getPerticularQ = "SELECT id, name FROM ledger_master WHERE group_id = '".$editdata['detail'][1]['type']."' AND status = 1 AND pharmacy_id = '".$pharmacy_id."'";
                                $getPerticularR = mysqli_query($conn, $getPerticularQ);
                                if($getPerticularR && mysqli_num_rows($getPerticularR) > 0){
                                  while ($getPerticularRow = mysqli_fetch_assoc($getPerticularR)) {
                                    $editPerticularData[] = $getPerticularRow;
                                  }
                                }
                              }
                            ?>
                            <select class="form-control js-example-basic-single particular" data-name="second" style="width:100%" name="particular[]" required data-parsley-errors-container="#error-particular1">
                              <option value="">Select Particulars</option>
                              <?php 
                                if(isset($editPerticularData) && !empty($editPerticularData)){
                                  foreach ($editPerticularData as $keys => $values) {
                              ?>
                                <option value="<?php echo $values['id']; ?>"  <?php echo (isset($editdata['detail'][1]['particular']) && $editdata['detail'][1]['particular'] == $values['id']) ? 'selected' : ''; ?> ><?php echo $values['name']; ?></option>
                              <?php } } ?>
                            </select>
                            <span id="error-particular1"></span>
                            <div class="badge badge-primary pull-right ledger_running_balance"></div>
                            <?php  if(isset($_GET['id'])){ ?>
                              <div class="badge badge-primary pull-right ledger_running_balance">
                                <?php 
                                  if(isset($editdata['detail'][1]['running_balance']) && $editdata['detail'][1]['running_balance'] != ''){
                                    echo amount_format(number_format(abs($editdata['detail'][1]['running_balance']), 2, '.', ''));
                                    echo ($editdata['detail'][1]['running_balance'] > 0) ? ' Dr' : ' Cr';
                                  }
                                ?>
                              </div>
                            <?php }?>
                          </td>

                          <td class="investment-item <?php echo (isset($editdata['is_investment']) && $editdata['is_investment'] == 1) ? '' : 'display-none'; ?>">
                            <input type="text" name="qty[]" class="form-control qty onlynumber" id="qty1" placeholder="enter QTY" value = "<?php echo (isset($editdata['detail'][1]['qty'])) ? $editdata['detail'][1]['qty'] : ''; ?>"  >
                          </td>

                          <td class="investment-item <?php echo (isset($editdata['is_investment']) && $editdata['is_investment'] == 1) ? '' : 'display-none'; ?>">
                            <input type="text" name="rate[]" class="form-control rate onlynumber" id="rate1" placeholder="enter Rate" value = "<?php echo (isset($editdata['detail'][1]['rate'])) ? $editdata['detail'][1]['rate'] : ''; ?>"  >
                          </td>

                          <td>
                            <input type="text" name="debit[]"  value = "<?php echo (isset($editdata['detail'][1]['debit'])) ? $editdata['detail'][1]['debit'] : ''; ?>" class="form-control debit1 onlynumber" id="debit1" placeholder="enter debit amount">
                          </td>

                          <td>
                            <input type="text" name="credit[]" value = "<?php echo (isset($editdata['detail'][1]['credit'])) ? $editdata['detail'][1]['credit'] : ''; ?>" class="form-control credit1 onlynumber" id = "credit1" placeholder="enter credit amount">
                          </td>

                          <td>
                            <textarea name="remarks[]" class="form-control" placeholder="Enter Remarks" id="remarks1" rows="1" style="resize:vertical;"><?php echo (isset($editdata['detail'][1]['remarks'])) ? $editdata['detail'][1]['remarks'] : ''; ?></textarea>
                          </td>
                       
                        </tr>

                      </tbody>
                    </table>
                    <div class="row form-group">
                        <!-- <div class="col-md-6">
                            <textarea class="form-control" name="remarks" id="remarks" placeholder="Ener Remarks"><?php //echo (isset($editdata['remarks'])) ? $editdata['remarks'] : ''; ?></textarea>    
                        </div> -->
                        <div class="col-md-2">
                            <div class="sales-filter-btns-right display-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input <?php if(isset($editdata['is_file']) && $editdata['is_file'] == "1"){echo "checked"; } ?> type="checkbox"  id="is_file" value="1" class="form-check-input ModuleChange" name="is_file">
                                    Uplod Document 
                                  </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 <?php echo (isset($editdata['is_file']) && $editdata['is_file'] == 1) ? 'display-block' : 'display-none'; ?>" id="file_upload_div" style="padding-top:15px;">
                            <?php if((isset($editdata['file_name']) && $editdata['file_name'] != '') && file_exists('journal_voucher_file/'.$editdata['file_name'])){ ?>
                                <span><i class="fa fa-paperclip"></i> <a href="?download=<?php echo $editdata['file_name']; ?>"><?php echo $editdata['file_name']; ?></a>  &nbsp;&nbsp;<a href="?remove=<?php echo $editdata['id']; ?>" class="text-danger" onclick="return confirm('Are you sure you want to remove this attechment?');"><i class="fa fa-trash-o"></i></a></span>
                            <?php }else{ ?>
                                <input type="file" name="file_name" class="form-control">
                            <?php } ?>
                        </div>
                    </div>
                    <a href="view-journal-vouchar.php" class="btn btn-light mt-30 pull-left" name="submit">Back</a>
                    <button name="submit" type="submit" class="btn btn-success mt-30 pull-right" style="float:right;"><?php echo (isset($_GET['id']) && $_GET['id'] != '') ? 'Update Voucher' : 'Add Voucher'; ?></button>
                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        
        <!-- partial:partials/_footer.php -->
        <?php include "include/footer.php" ?>
        <!-- partial -->
        
          
        <!-- Add New Product Model -->
        <?php include "include/addproductmodel.php" ?>
     
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->


  <div class="modal fade" id="addinvestment-model" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
      
        <div class="modal-header" style="padding: 15px;">
          <h5 class="modal-title" id="ModalLabel">Confirm</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body" style="padding: 15px;">
          <p>Are you sure want to add rate and qty field?</p>
        </div>
        
          <div class="modal-footer" style="padding: 15px;">
              <button type="button" class="btn btn-light text-left" id="btn-investment-yes" data-dismiss="modal">Yes</button>
              <button type="submit" class="btn btn-success text-right" id="btn-investment-no" data-dismiss="modal">No</button>
          </div>
      </div>
    </div>
  </div>


  <div id="product-tr" style="display: none">
    <table>
      <tr id="##PRODUCTCOUNT##"  >
        <td>
          <select class="form-control type" style="width: 100%;" name="type[]" required>
              <option value="">Select</option>
              <?php 
                $groupQry = "SELECT * FROM `group`";
                $group = mysqli_query($conn,$groupQry);
                while($group_data = mysqli_fetch_assoc($group)){
              ?>
                  <option value="<?php echo $group_data['id']; ?>"  ><?php echo $group_data['name']; ?></option>   
              <?php } ?>         
          </select>
        </td>
                      
        <td>
          <select class="form-control particular" style="width: 100%;" name="particular[]" required>
            <option value="">Select Particulars</option>
          </select>
          <div class="badge badge-primary pull-right ledger_running_balance"></div>
        </td>

        <td>   
          <input type="text" name="debit[]" class="form-control debit onlynumber" placeholder="enter debit amount">
        </td>

        <td>
          <input type="text" name="credit[]"  class="form-control credit onlynumber" placeholder="enter credit amount">
        </td>

        <td>
          <a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product addmore"><i class="fa fa-plus mr-0 ml-0"></i></a>
          <a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product "><i class="fa fa-close mr-0 ml-0"></i></a>
        </td>   
      </tr>
    </table>
  </div>
  

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
  <script src="js/modal-demo.js"></script>
  <!-- Custom js for this page Datatables-->
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

  <script src="js/parsley.min.js"></script>
  <script type="text/javascript">
    $('form').parsley();
  </script>
  <script src="js/custom/onlynumber.js"></script>
  <script src="js/custom/journal_vouchar.js"></script>
  
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  
  <!-- End custom js for this page-->
</body>


</html>
