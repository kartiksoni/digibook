<?php $title = "Add Courier Party"; ?>
<?php include('include/usertypecheck.php');
include('include/permission.php');

if(isset($_POST['submit'])){
  $user_id = $_SESSION['auth']['id'];
  
  if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
    $voucher_number = $_POST['voucherno'];
  }else{
    $voucher_number = getcourierpaymentvoucherno();
  }
  
  $voucher_date = date('Y-m-d',strtotime(str_replace('/','-',$_POST['voucher_date'])));
  $party = $_POST['party'];
  $payment_type = $_POST['payment_type'];
  $cheque_no = $_POST['cheque_no'];
  $bankname = $_POST['bankname'];
  $status = $_POST['status'];
  $lrnumber = $_POST['lr_number'];
  //$particular = $_POST['particular'];
  //$hsn_sac = $_POST['hsn_sac'];
  $taxable = $_POST['taxable'];
  $sgst = $_POST['sgst%'];
  $sgst_amount = $_POST['sgst_amount'];
  $cgst = $_POST['cgst%'];
  $cgst_amount = $_POST['cgst_amount'];
  $igst = $_POST['igst%'];
  $igst_amount = $_POST['igst_amount'];
  $remark = $_POST['remark'];
  $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : NULL;
  $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : NULL;
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
  $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;

  if(isset($_REQUEST['id'])){
    $editid = $_REQUEST['id'];
    $editpartyqry = "UPDATE `courier_payment` SET `voucher_no`= '".$voucher_number."', `voucher_date`= '".$voucher_date."', `party`= '".$party."', `payment_type`= '".$payment_type."', `cheque_no` = '".$cheque_no."', `bankname` = '".$bankname."' ,`lr_number`= '".$lrnumber."', `taxable_value`= '".$taxable."', `sgst%`= '".$sgst."', `sgst_amount`= '".$sgst_amount."', `cgst%`= '".$cgst."',`cgst_amount`= '".$cgst_amount."', `igst%`= '".$igst."', `igst_amount`= '".$igst_amount."', `status`= '".$status."', `remark`= '".$remark."', `modified`= '".date('Y-m-d H:i:s')."', `modifiedby`= '".$user_id."' WHERE id = '".$editid."'";

    $editpartyrun = mysqli_query($conn, $editpartyqry);

    if($editpartyrun){
      $_SESSION['msg']['success'] = 'Payment Updated Successfully.';
      header('location:courier-payment.php');exit;
    }
    else{
      $_SESSION['msg']['fail'] = 'Payment Updated Fail.';
      header('location:courier-payment.php');exit;
    }
  }
  else{
    $addpartyqry = "INSERT INTO `courier_payment`(`owner_id`, `admin_id`, `pharmacy_id`, `financial_id`, `voucher_no`, `voucher_date`, `party`, `payment_type`, `cheque_no`,`bankname`,`lr_number`, `taxable_value`, `sgst%`, `sgst_amount`, `cgst%`, `cgst_amount`, `igst%`, `igst_amount`, `status`, `remark`, `created`, `createdby`) VALUES ('".$owner_id."', '".$admin_id."', '".$pharmacy_id."', '".$financial_id."', '".$voucher_number."', '".$voucher_date."', '".$party."', '".$payment_type."', '".$cheque_no."','".$bankname."','".$lrnumber."', '".$taxable."', '".$sgst."', '".$sgst_amount."', '".$cgst."', '".$cgst_amount."', '".$igst."', '". $igst_amount."', '".$status."', '".$remark."', '".date('Y-m-d H:i:s')."', '".$user_id."')";

    $addpartyrun = mysqli_query($conn, $addpartyqry);

    if($addpartyrun){
      $_SESSION['msg']['success'] = 'Payment Added Successfully.';
      header('location:courier-payment.php');exit;
    }
    else{
      $_SESSION['msg']['fail'] = 'Payment Added Fail.';
      header('location:courier-payment.php');exit;
    }
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
                            if(isset($user_sub_module) && in_array("Courier Transport", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ 
                            ?>
                            <a href="courier-transport.php" class="btn btn-dark active">Courier Transport</a>
                            <?php }
                            if(isset($user_sub_module) && in_array("Add Courier Party", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){
                            ?>
                            <!--<a href="courier-party.php" class="btn btn-dark  btn-fw">Add Courier Party</a>-->
                            <?php }
                            if(isset($user_sub_module) && in_array("Add Payment Voucher", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){
                            ?>
                            <a href="add-payment-voucher.php" class="btn btn-dark  btn-fw">Add Payment Voucher</a>
                            <?php } 
                            if(isset($user_sub_module) && in_array("Courier Details", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){
                            ?>
                            <a href="courier-details.php" class="btn btn-dark  btn-fw">Courier Details</a>
                            <?php } ?>
                            <a href="courier-payment.php" class="btn btn-dark  btn-fw">Payment To Courier</a>
                    </div>   
                  </div> 
                </div>
                <div>&nbsp;</div>
                <h4 class="card-title">Courier Entry</h4>
                <hr>

                <br>

                <?php
                if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
                  $id = $_REQUEST['id'];
                  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                  $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;
                  $alldataqry = "SELECT * FROM courier_payment WHERE id = '".$id."' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."'";
              
                  $alldatarun = mysqli_query($conn, $alldataqry);
                  $partydata = mysqli_fetch_assoc($alldatarun);
    
                }
                ?>

                <!-- First Row  -->
                <form class="forms-sample" method="post" action="" autocomplete="off">

                  <div class="form-group row">
                    <div class="col-12 col-md-3">
                      <label for="exampleInputName1">Voucher No<span class="text-danger">*</span></label>
                      <input type="text" name="voucherno" class="form-control" id="exampleInputName1" required="" value="<?php if(isset($_REQUEST['id'])){echo $partydata['voucher_no']; } else {echo getcourierpaymentvoucherno(); } ?>">
                    </div>                   
                  </div>

                  <div class="form-group row">         
                    <div class="col-12 col-md-3">
                     <label for="exampleInputName1">Voucher Date<span class="text-danger">*</span></label>
                     <div id="" class="input-group date datepicker">
                      <input type="text" class="form-control border" name="voucher_date"  required="" data-parsley-errors-container="#error-date" value="<?php if(isset($_REQUEST['id'])){echo date("d/m/Y",strtotime(str_replace("-","/",$partydata['voucher_date']))); } else { echo date("d/m/Y"); } ?>">
                      <span class="input-group-addon input-group-append border-left">
                        <span class="mdi mdi-calendar input-group-text"></span>
                      </span>
                    </div>
                    <span id="error-date"></span>
                  </div>
                </div>                     
                
                <div class="form-group row">         
                  <div class="col-12 col-md-5">
                   <label for="exampleInputName1">Party<span class="text-danger">*</span></label>
                   <select class="js-example-basic-single state" name="party" id="group" style="width:100%" required="" data-parsley-errors-container="#error-container" onChange="gettransporterAddress(this.value);"> 
                    <option value="">Please Select</option>
                    <?php
                    $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;

                    $dataqry = "SELECT * FROM courier_transport WHERE pharmacy_id = ".$p_id." order by name asc";

                    $datarun = mysqli_query($conn, $dataqry);
                    while ($data = mysqli_fetch_assoc($datarun)) { ?>                
                      <option value="<?php echo $data['id']; ?>" <?php echo (isset($partydata['party']) && $partydata['party'] == $data['id']) ? 'selected': ''?>> <?php echo $data['name']; ?></option>
                    <?php  } ?>
                  </select>
                  <span id="error-container"></span>
                  <input type="hidden" name="state_code" id="state_gst_code" value="">
                </div>
                  <?php if(!isset($_REQUEST['id'])) {?>
                <a href="javascript:;" data-toggle="modal" data-target="#add_new_partyModel">Add New Party</a>
                   <?php } ?>
              </div>

              <div class="form-group row"> 
               <div class="col-12">
               
               </div>              
                <center><address class="mb-15" id="transporterAddress" style="margin-left: 70px;"></address></center>
              </div>
              
                <?php if(isset($_REQUEST['id'])) {
                   $Qtotl = "SELECT SUM(taxable_value) AS total FROM courier_payment WHERE party = '".$partydata['party']."' AND pharmacy_id = '".$pharmacy_id."' AND  financial_id = '".$financial_id."'" ;
    $toResult = mysqli_query($conn,$Qtotl);
    $rcmvalue = mysqli_fetch_assoc($toResult);
      $totalAmount = $rcmvalue['total'];
  
    if($totalAmount == NULL){
    $totalAmount = 0;
    }
    $totalAmount = number_format($totalAmount, 2, '.', ''); 
             ?>   
              <div class="form-group row">
                <div class="col-12 col-md-5">
            <label for="exampleInputName1" id="Amount" class="pull-right bg-success color-white p-2" style="margin-left: 70px;"><?php if(isset($_REQUEST['id'])){ echo $totalAmount; }?></label>  
                </div>
              </div>
        <?php   } else {?>
           <div class="form-group row">
                <div class="col-12 col-md-5">
            <label for="exampleInputName1" id="Amount" class="pull-right bg-success color-white p-2" style="margin-left: 70px;"></label>  
                </div>
              </div>

<?php } ?>

              <div class="form-group row">         
                <div class="col-12 col-md-2">
                 <label for="exampleInputName1">Type</label>
                 <div class="row no-gutters">
                  <div class="col">
                    <div class="form-radio">
                      <label class="form-check-label">
                       <input type="radio" class="form-check-input" name="payment_type" id="optionsRadios1" value="cash" onClick="showBankDetails(this.value);" checked <?php if(isset($_REQUEST['id']) && $partydata['payment_type'] == "cash"){echo "checked"; } ?>>
                       Cash
                     </label>
                   </div>
                 </div>

                 <div class="col">
                  <div class="form-radio">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="payment_type" id="optionsRadios2" value="bank" onClick="showBankDetails(this.value);" <?php if(isset($_REQUEST['id']) && $partydata['payment_type'] == "bank"){echo "checked"; }?>>
                      Bank
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 col-md-2">                  
              <label for="exampleInputName1">Status</label>
              <div class="row no-gutters">
                <div class="col">
                  <div class="form-radio">
                    <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" checked <?php if(isset($_REQUEST['id']) && $partydata['status'] == "1"){echo "checked"; } ?>>
                     Active
                   </label>
                 </div>
               </div>

               <div class="col">
                <div class="form-radio">
                  <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="status" id="optionsRadios2" value="0" <?php if(isset($_REQUEST['id']) && $partydata['status'] == "0"){echo "checked"; }?>>
                    Deactive
                  </label>
                </div>
              </div>
            </div>
          </div> 
        </div>


       
           <div id="bankdetails">  
          <div class="form-group row" >
           <div class="col-12 col-md-5">
             <label for="inputEmail3">Bank Name<span class="text-danger">*</span></label>

             <select class="js-example-basic-single" name="bankname" id="bankname">
              <option value = "">Select Bank</option>
                <?php $allBank = getBank(); ?>
                <?php if(isset($allBank) && !empty($allBank)){ ?>
                    <?php foreach($allBank as $key => $value){ ?>
                        <option value="<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>" <?php echo (isset($partydata['bankname']) && $partydata['bankname'] == $value['id']) ? 'selected' : '';?> ><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></option>
                    <?php } ?>
                <?php } ?>
           </select>

         </div> 
       </div> 

       <div class="form-group row">
        <div class="col-12 col-md-3">
          <label for="inputEmail3">Cheque No:<span class="text-danger">*</span></label>
          <input type="text" class="form-control" value="<?php if(isset($_REQUEST['id'])){echo $partydata['cheque_no']; }?>"  placeholder="Cheque Number" name="cheque_no" id="cheque_no" >
        </div>

      </div>
    </div>


    <div class="form-group row">        
      <div class="col-12 col-md-5">
        <label for="exampleInputName1">LR Number</label>
        <input type="text" name="lr_number" class="form-control" id="exampleInputName1" placeholder="LR Number" value="<?php if(isset($_REQUEST['id'])){echo $partydata['lr_number']; }?>">
      </div>          
    </div>

    <?php 
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
    $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;
    $rcmtotal =0;
    $Qrcm = "SELECT SUM(taxable_value) AS total FROM courier_payment WHERE pharmacy_id = '".$pharmacy_id."' AND  financial_id = '".$financial_id."'" ;
    $RCMResult = mysqli_query($conn,$Qrcm);

    $rcmvalue = mysqli_fetch_assoc($RCMResult);
    $total = $rcmvalue['total'];
    ?>
   <!--  <div type="button" id="partyAmount" class="btn btn-xs btn-purple" style="font-size:1.1em;font-family:Verdana;">Total Transportation Charges : <?php echo number_format($total, 2, '.', ''); ?></div>  -->

     <label for="exampleInputName1" id="partyAmount" class="bg-success color-white p-2">Total Transportation Charges : <?php echo number_format($total, 2, '.', ''); ?></label>  
    <div>&nbsp;</div>

    <!-- Table ------------------------------------------------------------------------------------------------------>

    <!-- TABLE STARTS -->
    <div class="col mt-3">
      <div class="row">
        <div class="col-12">
          <table id="order-listing1" class="table">
            <thead>
              <tr>
                <th>TAXABLE VALUE<span class="text-danger">*</span></th>
                <th colspan="2" class="text-center">SGST %</th>
                <th colspan="2" class="text-center">CGST %</th>
                <th colspan="2" class="text-center">IGST %</th>
              </tr>
            </thead>
            <tbody>
              <!-- Row Starts -->   
              <tr>
                <td><input type="text" class="form-control taxable onlynumber" name="taxable" id="taxable" required=""
                placeholder="0" autocomplete="off" value="<?php if(isset($_REQUEST['id'])){echo $partydata['taxable_value']; }?>">
                </td>


              <td>   
                <input type="text" class="form-control taxable onlynumber" name="sgst%" id="sgst" 
                placeholder="Enter SGST %" autocomplete="off" value="<?php if(isset($_REQUEST['id'])){echo $partydata['sgst%']; }?>">

                <td>
                  <input type="text" class="form-control onlynumber" name="sgst_amount" id="total_sgst" 
                  placeholder="SGST Amount" autocomplete="off" value="<?php if(isset($_REQUEST['id'])){echo $partydata['sgst_amount']; }?>">
                </td>

              </td>

              <td>
                <input type="text" class="form-control taxable onlynumber" name="cgst%" id="cgst" 
                placeholder="Enter CGST %" autocomplete="off" value="<?php if(isset($_REQUEST['id'])){echo $partydata['cgst%']; }?>">

                <td>
                  <input type="text" class="form-control onlynumber" name="cgst_amount" id="total_cgst" 
                  placeholder="CGST Amount" autocomplete="off" value="<?php if(isset($_REQUEST['id'])){echo $partydata['cgst_amount']; }?>">
                </td>          
              </td>

              <td><input type="text" class="form-control taxable onlynumber" name="igst%" id="igst" 
                placeholder="Enter IGST %" autocomplete="off" value="<?php if(isset($_REQUEST['id'])){echo $partydata['igst%']; }?>">

                <td>
                  <input type="text" class="form-control onlynumber" name="igst_amount" id="total_igst" 
                  placeholder="IGST Amount" autocomplete="off" value="<?php if(isset($_REQUEST['id'])){echo $partydata['igst_amount']; }?>">
                </td>          
              </td>

            </tr><!-- End Row -->   

            <tr>
              <td>
                <label for="exampleInputName1">Remarks</label>
                <textarea  class="form-control" name="remark" id="exampleInputName1" 
                placeholder="Enter Remark" rows="4" cols="45"><?php if(isset($_REQUEST['id'])){echo $partydata['remark']; }?>
              </textarea> 
            </td>
            <td colspan="3">&nbsp;</td>
          </tr>
        </tbody>
      </table>

      <div class="col-12">
          <a href="view-courier-payment.php" class="btn btn-light mt-30 pull-left">Back</a>
        <button type="submit" name="submit" class="btn btn-success mt-30 pull-right">Submit</button>
      </div>                
    </div>   
  </div>
</div>
</div> 
</form>
</div>
</div>
</div>
<!-- content-wrapper ends -->

<!-- partial:partials/_footer.php -->
<?php include "include/footer.php" ?>
<?php include "popup/add-party-courier-transport.php"?>
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
<script src="js/custom/courier-payment.js"></script>


<!-- Custom js for this page Modal Box-->
<script src="js/modal-demo.js"></script>
<script type="text/javascript">
  $("#bankdetails").hide();
</script>>

<!-- Datepicker Initialise-->

<script>
  $('.datepicker').datepicker({
    enableOnReadonly: true,
    todayHighlight: true,
    format: 'dd/mm/yyyy',
    autoclose: true
  });
</script>
<script>

  function gettransporterAddress(val){
     
    $.ajax({
      type: "POST",
      url: "ajax.php",
      data:{'action':'courier_amount', 'id': val},
      dataType: "json",
      success: function(data){
         
        $("#transporterAddress").html(data.address); 
        $("#Amount").html(data.total); 

      }
    });
  }


  function showBankDetails(value){

    if(value == 'bank'){
      $("#bankdetails").show();
    } else {
      $("#bankdetails").hide(); 
        $("#cheque_no").val('');
      $("#bankname").val('');
    }

  }

</script>
<script type="text/javascript">
  $( document ).ready(function() {
     var radioValue = $("input[name='payment_type']:checked").val();
   
     if(radioValue == "bank"){
       $("#bankdetails").show();
     }


   var  val = '<?php echo $partydata['party'];?>';
    $.ajax({
      type: "POST",
      url: "ajax.php",
      data:{'action':'AmountOfCourier', 'id': val},
      dataType: "json",
      success: function(data){
        $("#transporterAddress").html(data.address); 
        // $("#Amount").html(data.total); 

      }
    });
 

});
</script>


<!-- script for custom validation -->
<script src="js/custom/onlynumber.js"></script>
<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>

<!-- End custom js for this page-->
<!-- change status js -->
<script src="js/custom/statusupdate.js"></script>


  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>


<script type="text/javascript">

  $('#add-new-party').on("submit", function(event){
      event.preventDefault();
      var data = $(this).serializeArray();

      // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
       //  var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      

       $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'AddNewParty', 'data': data},
            dataType: "json",
             beforeSend: function() {
              $('#btn-addparty').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
              $('#btn-addparty').prop('disabled', true);
            },          
            success: function (data) {
               if(data.status == true){
               showSuccessToast(data.message);
              // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
               // $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#add_new_partyModel').modal('toggle');
                $('#add-new-party')[0].reset();
                window.location.reload();
               
            }else{
            showDangerToast(data.message);
             // htmlerror = htmlerror.replace("##MSG##", data.message);
             //   $('#addparty-errormsg').html(htmlerror);
                $("html, body").animate({ scrollTop: 0 }, "slow");

                 $('#btn-addparty').html('Save');
              $('#btn-addparty').prop('disabled', false);
            }
          },
          error: function () {
          showDangerToast('Somthing Want Wrong! Try again.');
            //  htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
             // $('#addparty-errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");

              $('#btn-addparty').html('Save');
              $('#btn-addparty').prop('disabled', false);
            }
      });
     });
  
</script>

</body>


</html>
