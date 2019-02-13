<?php $title="Phoneboook"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>


<?php
  $p_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $editdataQ = "SELECT * FROM sms_phonebook WHERE id='".$_GET['id']."' AND pharmacy_id = '".$p_id."'";
    $editdataR = mysqli_query($conn, $editdataQ);
    if($editdataR && mysqli_num_rows($editdataR) > 0){
      $editData = mysqli_fetch_assoc($editdataR);
    }
  }
?>

<?php 
  if($_POST){
    $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : NULL;
    $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : NULL;
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
    $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;
    $group = (isset($_POST['group']) && $_POST['group'] != '') ? $_POST['group'] : NULL;
    $name = (isset($_POST['name'])) ? $_POST['name'] : '';
    $mobile = (isset($_POST['mobile'])) ? $_POST['mobile'] : '';
    $status = (isset($_POST['status'])) ? $_POST['status'] : 0;
    $date = date('Y-m-d H:i:s');
    $userid = $_SESSION['auth']['id'];

    if($group != '' && $name != '' && $mobile != ''){
      if(isset($_GET['id']) && $_GET['id'] != ''){
        $query = "UPDATE sms_phonebook SET `group` = '".$group."', `name` = '".$name."', `mobile` = '".$mobile."', `status` = '".$status."', `modified` = '".$date."', `modifiedby` = '".$userid."' WHERE id = '".$_GET['id']."'";
      }else{
        $query = "INSERT INTO sms_phonebook SET `financial_id` = '".$financial_id."', `owner_id` = '".$owner_id."', `admin_id` = '".$admin_id."', `pharmacy_id` = '".$pharmacy_id."', `group` = '".$group."', `name` = '".$name."', `mobile` = '".$mobile."', `status` = '".$status."', `created` = '".$date."', `createdby` = '".$userid."'";
      }
      $res = mysqli_query($conn, $query);
      if($res){
        if(isset($_GET['id']) && $_GET['id'] != ''){
          $_SESSION['msg']['success'] = 'Contact update successfully.';
        }else{
          $_SESSION['msg']['success'] = 'Contact added successfully.';
        }
      }else{
        if(isset($_GET['id']) && $_GET['id'] != ''){
          $_SESSION['msg']['fail'] = 'Contact update fail! Try again.';
        }else{
          $_SESSION['msg']['fail'] = 'Contact added fail! Try again.';
        }
      }
    }else{
      $_SESSION['msg']['warning'] = 'Some field is required!';
    }
    header('location:sms-phonebook.php');exit;
  }

?>

<?php 
  $allcontact = [];
  $allcontactQ = "SELECT ph.id, ph.name, ph.mobile, ph.status, grp.name as groupname FROM sms_phonebook ph INNER JOIN sms_group grp ON ph.group = grp.id WHERE ph.pharmacy_id = '".$p_id."' ORDER BY ph.id DESC";
  $allcontactR = mysqli_query($conn, $allcontactQ);
  if($allcontactR && mysqli_num_rows($allcontactR) > 0){
    while ($allcontactRow = mysqli_fetch_assoc($allcontactR)) {
      $allcontact[] = $allcontactRow;
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
          <span id="errormsg"></span>

          <div class="row">
          
           <!-- Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <!-- Main Catagory -->
                  <h4 class="card-title">Phone Book</h4><hr class="alert-dark"><br>
                  <form method="POST" autocomplete="off">
                    <div class="form-group row">
                      <div class="col-md-12">
                        <div class="row">

                          <div class="col-12 col-md-3">
                            <label>Group <span class="text-danger">*</span></label>
                            <select name="group"  class="js-example-basic-single" id="group" style="width:100%" data-parsley-errors-container="#error-group" required>  
                                <option value="">Select Group</option>
                                <?php
                                  $groupdata = [];
                                  $group = "SELECT id, name FROM `sms_group` WHERE pharmacy_id = '".$p_id."' AND status = 1 ORDER BY name";
                                  $groupres = mysqli_query($conn, $group);
                                  if($groupres && mysqli_num_rows($groupres) > 0){
                                    while ($grouprow = mysqli_fetch_assoc($groupres)) {
                                      $groupdata[] = $grouprow;
                                    }
                                  } 
                                ?>
                                <?php
                                  if(isset($groupdata) && !empty($groupdata)){
                                    foreach ($groupdata as $key => $value) {
                                ?>
                                  <option value="<?php echo $value['id']; ?>" <?php echo (isset($editData['group']) && $editData['group'] == $value['id']) ? 'selected' : ''; ?> ><?php echo $value['name']; ?></option>
                                <?php 
                                    }
                                  }
                                ?>
                            </select>
                            <span id="error-group"></span>
                          </div>
                        
                          <div class="col-12 col-md-3">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" value="<?php echo (isset($editData['name'])) ? $editData['name'] : ''; ?>" required>
                          </div>

                          <div class="col-12 col-md-3">
                            <label for="mobile">Mobile <span class="text-danger">*</span></label>
                            <input type="text" class="form-control onlynumber" name="mobile" id="mobile" placeholder="Enter Mobile" value="<?php echo (isset($editData['mobile'])) ? $editData['mobile'] : ''; ?>" data-parsley-length="[10, 10]" data-parsley-length-message = "Mobile No should be 10 charatcers long." required>
                          </div>

                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Status</label>
                            <div class="row no-gutters">
                              <div class="col">
                                <div class="form-radio">
                                <label class="form-check-label">
                                <?php 
                                  if(isset($_GET['id']) && $_GET['id'] != ''){
                                    $active = (isset($editData['status']) && $editData['status'] == 1) ? 'checked' : '';
                                  }else{
                                    $active = 'checked';
                                  }
                                ?>
                                <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" <?php echo (isset($active)) ? $active : ''; ?> >
                                Active
                                </label>
                                </div>
                              </div>
                              <div class="col">
                                <div class="form-radio">
                                <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="status" id="optionsRadios2" value="0" <?php echo (isset($editData['status']) && $editData['status'] == 0) ? 'checked' : ''; ?>>
                                Deactive
                                </label>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-12 col-md-6">
                            <button type="submit" class="btn btn-success mt-30"><?php echo (isset($_GET['id']) && $_GET['id'] != '') ? 'Update' : 'Add'; ?></button>
                            <a href="sms-phonebook.php" class="btn btn-light mt-30">Cancel</a>
                          </div>

                        </div> 
                      </div>
                    </div> 
                  </form>

                </div>
              </div>
            </div>

           

            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="col mt-3">
                       <div class="row">
                          <div class="col-12">
                              <table class="table datatable">
                                <thead>
                                  <tr>
                                      <th width="10%">Sr. No</th>
                                      <th>Group</th>
                                      <th>Name</th>
                                      <th>Mobile</th>
                                      <th width="15%">Status</th>
                                      <th width="15%">Action</th>
                                  </tr> 
                                </thead>
                                <tbody>
                                  <?php if(isset($allcontact) && !empty($allcontact)){ ?>
                                    <?php foreach ($allcontact as $key => $value) { ?>
                                      <tr>
                                        <td><?php echo $key+1; ?></td>
                                        <td><?php echo (isset($value['groupname'])) ? $value['groupname'] : ''; ?></td>
                                        <td><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></td>
                                        <td><?php echo (isset($value['mobile'])) ? $value['mobile'] : ''; ?></td>
                                        <td>
                                          <button type="button" class="btn btn-sm btn-toggle changestatus <?php echo (isset($value['status']) && $value['status'] == 1) ? 'active' : ''; ?>" data-table="sms_phonebook" data-id="<?php echo $value['id']; ?>" data-toggle="button" aria-pressed="<?php echo (isset($value['status']) && $value['status'] == 1) ? true : false; ?>" autocomplete="off">
                                            <div class="handle"></div>
                                          </button>
                                        </td>
                                        <td>
                                          <a class="btn btn-behance p-2" href="sms-phonebook.php?id=<?php echo $value['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                        </td>
                                      </tr>
                                    <?php } ?>
                                  <?php } ?>
                                </tbody>
                              </table>
                          </div>
                        </div>
                    </div>
                    <hr>
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
  
  
  <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
  <!-- Custom js for this page Modal Box-->
  <script src="js/modal-demo.js"></script>
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script> 
  <script>
     $('.datatable').DataTable();
  </script>

  <script src="js/parsley.min.js"></script>
  <script type="text/javascript">
    $('form').parsley();
  </script>
  <script src="js/custom/onlynumber.js"></script>
  <script src="js/custom/statusupdate.js"></script>
  
  
  <!-- End custom js for this page-->
</body>


</html>
