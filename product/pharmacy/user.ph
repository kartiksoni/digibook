<?php include('include/usertypecheck.php');
//define('BASE_URL', 'http://localhost/digibook/product/api/api.php' );
//define('BASE_URL', 'http://digibooks.cloud/product/api/api.php' );
if(isset($_GET['id'])){
  $id = $_GET['id'];
  $url= BASE_URL."?action=edit_user&id=".$id."";  
  $edit_data = file_get_contents($url);
  $edit = json_decode($edit_data,true);

}


if(isset($_POST['submit'])){
  $owner_id = $_SESSION['auth']['id'];
  $pharmacy_id = $_POST['pharmacy_id'];
  $user_name = $_POST['user_name'];
  $email = $_POST['email'];
  $mobile = $_POST['mobile'];
  $password = md5($_POST['password']);
  $status = $_POST['status'];
  $url = BASE_URL."?action=add_user&name=$user_name&owner_id=$owner_id&pharmacy_id=$pharmacy_id&user_type=admin&user_name=$user_name&email=$email&mobile=$mobile&password=$password&type=PHARMACY_WHOLESALE&status=$status";

  $data = file_get_contents($url);
  
  if($data){
      
      $html ="<html><head>
           <title>User Details</title>
           </head>
           <body>";
        $html .= "<h3>Welcome ".$user_name."</h3>";
        $html .= "<h4>Thank You For Registration</h4>";
        $html .= "<p>For Reference, Here's Your Login Information:</p>";
        $html .= "<p>Login Page : <a href=". $_SERVER['SERVER_NAME']."/product/login.php>". $_SERVER['SERVER_NAME']."/product/login.php</a>";
        $html .= "<p>Email : ".$_POST['email']."</p>";
        $html .= "<p>Password : ".$_POST['password']."</p>";
        $html .= "<p>All The Best,</p>";
        $html .= "<p>The Digibooks Team.</p>";
        $html .= "</body></html>";
        
       smtpmail($email, '', '', 'Digibook User Details', $html, '', '');
      
     
    $_SESSION['msg']['success'] = "Admin Add successfully.";
    header('location:user.php?pharmacy_id='.$pharmacy_id.'');exit;
  }else{
    $_SESSION['msg']['fail'] = "Admin Not Add.";
    header('location:user.php?pharmacy_id='.$pharmacy_id.'');exit;
  }
}

if(isset($_POST['edit'])){
  $pharmacy_id = $_POST['pharmacy_id'];
  $id = $_REQUEST['id'];
  $user_name = $_POST['user_name'];
  $email = $_POST['email'];
  $mobile = $_POST['mobile'];
  $password = md5($_POST['password']);
  $status = $_POST['status'];

  $url = BASE_URL."?action=edit_user_data&name=$user_name&user_name=$user_name&email=$email&mobile=$mobile&password=$password&status=$status&pharmacy_id=$pharmacy_id&id=$id";
  $data = file_get_contents($url);

  if($data){

    $_SESSION['msg']['success'] = "Admin Update successfully.";
    header('location:user.php?pharmacy_id='.$pharmacy_id.'');exit;

  }else{

    $_SESSION['msg']['success'] = "Admin Year Not Update.";
    header('location:user.php?pharmacy_id='.$pharmacy_id.'');exit;

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
                  <h4 class="card-title">User Profile</h4>
                  <hr class="alert-dark">
                  <br>
                  <form class="forms-sample" class="" method="post" action="" autocomplete="off">
                  
                  <div class="form-group row">
                    <div class="col-12 col-md-4">
                        <label for="exampleInputName1">Select Pharmacy<span class="text-danger">*</span></label>
                        <select name="pharmacy_id" class="js-example-basic-single" required style="width:100%"> 
                            <option value="">Select Pharmacy</option>
                            <?php 
                            $financialQry = "SELECT * FROM `pharmacy_profile` WHERE created_by='".$_SESSION['auth']['id']."' ORDER BY id DESC";
                            $financial = mysqli_query($conn,$financialQry);
                            while($row = mysqli_fetch_assoc($financial)){
                            ?>
                            <option <?php if(isset($edit['pharmacy_id']) && $edit['pharmacy_id'] == $row['id']){echo "selected";} ?> value="<?php echo $row['id']; ?>"><?php echo $row['pharmacy_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                      <div class="col-12 col-md-4">
                        <label for="user_name">User Name<span class="text-danger">*</span></label>
                        <input type="text" required name="user_name" value="<?php echo (isset($edit['username'])) ? $edit['username'] : ''; ?>" class="form-control" id="user_name" placeholder="User Name">
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="email">Email<span class="text-danger">*</span></label>
                      <input type="email" required name="email" value="<?php echo (isset($edit['email'])) ? $edit['email'] : ''; ?>" class="form-control" id="email" placeholder="Email">
                      </div> 

                      
                  </div>
                    
                  <div class="form-group row">
                    
                      <div class="col-12 col-md-4">
                        <label for="email">Mobile<span class="text-danger">*</span></label>
                      <input type="text" required name="mobile" minlength="10" maxlength ="10" value="<?php echo (isset($edit['mobile'])) ? $edit['mobile'] : ''; ?>" class="form-control onlynumber" id="mobile" placeholder="Mobile" data-parsley-length="[10, 10]" data-parsley-length-message = "Mobile No should be 10 charatcers long.">
                      </div> 
                      
                      <div class="col-12 col-md-4">
                        <label for="password">Password<span class="text-danger">*</span></label>
                        <input type="password" required name="password" value="<?php echo (isset($edit['password'])) ? $edit['password'] : ''; ?>" class="form-control" id="password" placeholder="Password">
                      </div>
                        
                  </div>
                  <div class="form-group row">
                       
                       <div class="col-12 col-md-4">
                                  <label for="exampleInputName1">Status</label>
                                  
                                  <div class="row no-gutters">
                                  
                                      <div class="col">
                                          <div class="form-radio">
                                          <label class="form-check-label">
                                          <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" <?php if(isset($_GET['id'])){if(isset($edit['status']) && $edit['status'] == "1"){echo "checked";}  }else{echo"checked";} ?>>
                                          Active
                                          </label>
                                          </div>
                                      </div>
                                      
                                      <div class="col">
                                          <div class="form-radio">
                                          <label class="form-check-label">
                                          <input type="radio" <?php if(isset($edit['status']) && $edit['status'] == "0"){echo "checked";} ?> class="form-check-input" name="status" id="optionsRadios2" value="0">
                                          Deactive
                                          </label>
                                          </div>
                                      </div>
                                  
                                  </div>
                              </div>
                          
                      </div>
                    
                    <br>
                    <a href="configuration.php" class="btn btn-light">Cancel</a>
                    <?php 
                      if(isset($_GET['id'])){
                        ?>
                      <button name="edit" type="submit" class="btn btn-success mr-2">Edit</button>
                        <?php
                      }else{
                      ?>
                      <button name="submit" type="submit" class="btn btn-success mr-2">Submit</button>
                      <?php } ?>
                    
                    
                  </form>
                  <div class="col mt-3">
                    <h4 class="card-title">User List</h4>
                    <hr class="alert-dark">
                       <div class="row">
                            <div class="col-12">
                              <table id="order-listing1" class="table">
                                <thead>
                                  <tr>
                                      <th>Sr No</th>
                                      <th>Pharmacy Name</th>
                                      <th>User Name</th>
                                      <th>Email</th>
                                      <th>Mobile</th>
                                      <th>User Role</th>
                                      <!-- <th>Status</th> -->
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts -->   
                                  <?php 
                                  $url = BASE_URL."?action=view_user&owner_id=".$_SESSION['auth']['id']."";
                                  $data = file_get_contents($url);
                                  $data_array = json_decode($data,true);
                                  foreach ($data_array as $key => $value) {
                                  ?>
                                  <tr>
                                      <td><?php echo $key+1; ?></td>
                                      <?php 
                                      $selectQry = "SELECT pharmacy_name FROM `pharmacy_profile` WHERE id='".$value['pharmacy_id']."'";
		                              $res = mysqli_query($conn, $selectQry);
		                              $row = mysqli_fetch_assoc($res);
                                      ?>
                                      <td><?php echo $row['pharmacy_name']; ?></td>
                                      <td><?php echo $value['username']; ?></td>
                                      <td><?php echo $value['email']; ?></td>
                                      <td><?php echo $value['mobile']; ?></td>
                                      <td><?php echo $value['user_type']; ?></td>
                                      <td>
                                        <a class="btn btn-behance p-2" href="user.php?id=<?php echo $value['id'] ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-danger p-2 delete" title="Delete" data-id="<?php echo $value['id']; ?>" data-action="deleteAdmin"><i class="fa fa-trash-o mr-0"></i></a>
                                        <a class="btn btn-info p-2" href="admin_rights.php?user_id=<?php echo $value['id']; ?>" title="Admin Rights">Admin Rights</a>
                                          
                                        <!--<a href="user.php?pharmacy_id=<?php //echo $value['pharmacy_id'];?>&id=<?php //echo $value['id'] ?>" title="edit"><i class="fa fa-edit"></i></a>
                                        <a href="admin_rights.php?pharmacy_id=<?php //echo $value['pharmacy_id']; ?>&user_id=<?php //echo $value['id']; ?>">admin rights </a>-->
                                      </td>
                                  </tr><!-- End Row --> 
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

  <script src="js/parsley.min.js"></script>
    <script type="text/javascript">
      $('form').parsley();
    </script>
  
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
 
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('#order-listing2').DataTable();
  </script>
  
  <script>
     $('#order-listing1').DataTable();
  </script>
  
  <!-- change status js -->
  <script src="js/custom/statusupdate.js"></script>
  <script src="js/custom/onlynumber.js"></script>
  <script src="js/custom/delete.js"></script>
  
  <!-- End custom js for this page-->
  
</body>


</html>
