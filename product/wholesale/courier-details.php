<?php $title = "Courier Details"; ?>
<?php include('include/usertypecheck.php'); 
include('include/permission.php'); ?>
<?php 
  $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : '';
  $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
  
if(isset($_POST['submit'])){
    $from = date('Y-m-d',strtotime(str_replace('/','-',$_POST['from'])));
    $to = date('Y-m-d',strtotime(str_replace('/','-',$_POST['to'])));
    $party = $_POST['party'];
                        
 $selectQ = "SELECT cp.id as editid, apv.voucher_date , apv.voucher_no , apv.ledger_id , apv.transporter_id ,lm.name, cp.payment_type, oc.name as ctname , ct.name as party_name from add_payment_voucher apv inner join courier_payment cp on apv.transporter_id = cp.party inner JOIN ledger_master lm on apv.ledger_id = lm.id inner join courier_transport ct on apv.transporter_id = ct.id inner join own_cities oc on oc.id = ct.city where apv.transporter_id ='".$party."' and apv.voucher_date >= '".$from."' and apv.voucher_date <='".$to."'" ;                       
  $resultQ = mysqli_query($conn, $selectQ);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks | Courier Details</title>
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
  <link rel="stylesheet" href="vendors/iconfonts/simple-line-icon/css/simple-line-icons.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/toggle/style.css">
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
          <span id="errormsg"></span>
          <div class="row">
          
          
           <!-- Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                <!-- Main Catagory -->
                <div class="row">
                      <div class="col-12">
                        <div class="purchase-top-btns">
                          <?php 
                            if(isset($user_sub_module) && in_array("Courier Transport", $user_sub_module)){ 
                            ?>
                            <a href="courier-transport.php" class="btn btn-dark active">Courier Transport</a>
                            <?php }
                            if(isset($user_sub_module) && in_array("Add Courier Party", $user_sub_module)){
                            ?>
                            <!--<a href="courier-party.php" class="btn btn-dark  btn-fw">Add Courier Party</a>-->
                            <?php }
                            if(isset($user_sub_module) && in_array("Add Payment Voucher", $user_sub_module)){
                            ?>
                            <a href="add-payment-voucher.php" class="btn btn-dark  btn-fw">Add Payment Voucher</a>
                            <?php } 
                            if(isset($user_sub_module) && in_array("Courier Details", $user_sub_module)){
                            ?>
                            <a href="courier-details.php" class="btn btn-dark  btn-fw">Courier Details</a>
                            <?php } ?>
                            <a href="courier-payment.php" class="btn btn-dark  btn-fw">Payment To Courier</a>
                        </div>   
                      </div> 
                    </div>
                    <div>&nbsp;</div>
                    <h4 class="card-title">Courier Details</h4>
                    <hr>

                   <br>
                  
                    
                    <!-- First Row  -->
            <form class="forms-sample" method="post" action="" autocomplete="off">
                 
                <div class="form-group row">         
                    <div class="col-12 col-md-3">
                       <label for="exampleInputName1">From</label>
                       <div id="" class="input-group date datepicker">
  		                    <input type="text" class="form-control border" name="from" value="<?php echo (isset($_POST['from'])) ? $_POST['from'] : date('d/m/Y'); ?>">
  		                    
  		                    <span class="input-group-addon input-group-append border-left">
  		                        <span class="mdi mdi-calendar input-group-text"></span>
  		                    </span>
  		                </div>
                    </div>
                                           
                    <div class="col-12 col-md-3">
                       <label for="exampleInputName1">To</label>
                       <div id="" class="input-group date datepicker">
  		                    <input type="text" class="form-control border" name="to" value="<?php echo (isset($_POST['to'])) ? $_POST['to'] : date('d/m/Y'); ?>">
  		                    <span class="input-group-addon input-group-append border-left">
  		                        <span class="mdi mdi-calendar input-group-text"></span>
  		                    </span>
  		                </div>
                    </div>
                

                    <div class="col-12 col-md-3">
                       <label for="exampleInputName1">Party</label>
                        <select class="js-example-basic-single" name="party" id="group" style="width:100%" data-parsley-errors-container="#error-party" required> 
                          <option value="">Please Select</option>
                          <?php
                            $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
                            $dataqry = "SELECT * FROM courier_transport WHERE pharmacy_id = ".$p_id." order by name asc";
                            $datarun = mysqli_query($conn, $dataqry);
                            while ($data = mysqli_fetch_assoc($datarun)) { ?>
                                            
                          <option value="<?php echo $data['id']; ?>" <?php if(isset($_POST['party']) && $_POST['party'] == $data['id']){echo "selected";}?>> <?php echo $data['name']; ?></option>
                          <?php  } ?>
                        </select>
                         <span id="error-party"></span>
                    </div>

                    <div class="col-12 col-md-3">
                        <button type="submit" name="submit" class="btn btn-success mt-30">Submit</button>
                    </div>
                </div>
            </form>
          </div>
        </div>
        </div>

        <div class="col-md-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Courier List</h4>
              <hr class="alert-dark">
              <br>

                <div class="col mt-3">
                  <div class="row">
                    <div class="col-12">
          
                      <div class="table-responsive">
                      <table id="order-listing" class="table datatable">
                      <thead>
                      <tr>
                       <th>Sr No</th>
                        <th>Voucher Date</th>
                        <th>Voucher No</th>
                        <th>Party Name</th>
                        <th>vendor</th>
                        <th>Payment Type</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>

                    
                    <?php if(isset($resultQ)){
                           $count = 1;
                        while($data = mysqli_fetch_assoc($resultQ)){
                      ?>
                       <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo date('d/m/Y',strtotime($data['voucher_date'])); ?></td>
                        <td><?php echo $data['voucher_no']; ?></td>
                        <td><?php echo $data['party_name']; ?></td>
                        <td><?php echo $data['name']?>
                        <td><?php echo $data['payment_type']; ?></td>
                        <!--<td><button type="button" class="btn btn-sm btn-toggle changestatus <?php echo (isset($courierdata['status']) && $courierdata['status'] == 1) ? 'active' : ''; ?>" data-table="courier_party" data-id="<?php echo $courierdata['id']; ?>" data-toggle="button" aria-pressed="<?php echo (isset($courierdata['status']) && $courierdata['status'] == 1) ? true : false; ?>" autocomplete="off">-->
                        <!--<div class="handle"></div></button>-->
                        <!--</td>-->
                        <td><a href="courier-payment.php?id=<?php echo $data['editid'];?>" class="btn  btn-behance p-2"><i class="fa fa-pencil mr-0"></i></a></td>
                      </tr>
                      <?php  $count++;
                        }
                      } ?>
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
  <script src="js/custom/onlynumber.js"></script>
  
  <!-- Custom js for this page Modal Box-->
  <script src="js/modal-demo.js"></script>
  
  
  <!-- Datepicker Initialise-->
 
 <script>
    $('.datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy',
      autoclose: true
    });
 </script>
 
 <!-- Custom js for this page Datatables-->
<script src="js/data-table.js"></script>
<script>
   $('.datatable').DataTable();
</script>

 <!-- script for custom validation -->
 <script src="js/custom/onlynumber.js"></script>
 <script src="js/parsley.min.js"></script>
 <script type="text/javascript">
  $('form').parsley();
 </script>
 
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
   
<!-- End custom js for this page-->
<!-- change status js -->
  <script src="js/custom/statusupdate.js"></script>
</body>


</html>
