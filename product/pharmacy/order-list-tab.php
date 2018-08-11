<?php include('include/usertypecheck.php'); 

if(isset($_REQUEST['id']) && $_REQUEST['id'] != '')
{  
  $id = $_REQUEST['id'];

  $sql_email = "SELECT orders.id, orders.order_no as orderno, orders.created as orderdate, product_master.product_name as productname,               product_master.generic_name as genericname, product_master.mfg_company as manufacturername, orders.purchase_price as                 purchaseprice, orders.gst as gst, orders.unit as unit, orders.qty as quantity, ledger_master.name as vendorname,ledger_master.mobile as mobile, ledger_master.email as email from((orders INNER JOIN product_master ON orders.product_id = product_master.id) INNER JOIN ledger_master ON orders.vendor_id = ledger_master.id) WHERE orders.status = '1' AND orders.id='".$id."'";

  $sqlqryrun_email = mysqli_query($conn, $sql_email);
  $sqldata = mysqli_fetch_assoc($sqlqryrun_email);
  $email = $sqldata['email'];

  require_once "PHPMailer/PHPMailer/PHPMailerAutoload.php";
  $message = " Hello ".ucwords($sqldata['vendorname'])."<br>";
  $message .= ucwords("Your Order Summary")."<br>";
  $message .= ucwords("All Summary Description");
  
    $mail = new PHPMailer;
    //Tell PHPMailer to use SMTP
    $mail->isSMTP();
    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 2;
    //Set the hostname of the mail server
    $mail->Host = 'smtp.gmail.com';
    // use
    // $mail->Host = gethostbyname('smtp.gmail.com');
    // if your network does not support SMTP over IPv6
    //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $mail->Port = 587;
    //Set the encryption system to use - ssl (deprecated) or tls
    $mail->SMTPSecure = 'tls';
    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;
    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = "viragrakholiya95@gmail.com";
    //Password to use for SMTP authentication
    $mail->Password = "virag123";
    //Set who the message is to be sent from
    $mail->setFrom('viragrakholiya95@gmail.com', 'Digiwallet');
    //Set an alternative reply-to address
    //$mail->addReplyTo('replyto@example.com', 'First Last');
    //Set who the message is to be sent to
    $mail->addAddress($email, 'Digibook');
    //Set the subject line
    $mail->Subject = 'Digibook Order Summary';
    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $mail->msgHTML($message, __DIR__);
    //Replace the plain text body with one created manually
    $mail->AltBody = 'This is a plain-text message body';
    //Attach an image file
    //$mail->addAttachment('images/phpmailer_mini.png');
    //send the message, check for errors
    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Message sent!";
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
  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" />
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
                   	
                    <!-- Main Catagory -->
                    <div class="row">
                    <div class="col-12">
                        <div class="enventory">
                            <a href="order.php" class="btn btn-dark btn-fw active">Order</a>
                            <a href="order-list-tab.php" class="btn btn-dark btn-fw">List</a>
                            <a href="#" class="btn btn-dark btn-fw">Missed Sales Order</a>
                            <a href="#" class="btn btn-dark btn-fw">Settings</a>
                        </div>  
                    </div> 
                    </div>
                    <hr>
                    
                    <!-- Sub Catagory Catagory -->
                    <div class="row">
                    <div class="col-12 bg-inverse-light" >
                        <p>Order Search</p>
                    </div> 
                    </div>

                    
                    <form class="forms-sample" method="post" action="" autocomplete="off">
                    
                    
                    <div class="form-group row">
                    
                    <div class="col-12 col-md-3">
                    <div class="row no-gutters">
                        <div  class="col-md-10">
                            <label class="col-12 row">Select Vendor</label>
                            <select class="js-example-basic-single" name="vender_id" style="width:100%"> 
                            <option value="">Please select</option>
                            <?php 
                            $sql = "SELECT id, name FROM ledger_master WHERE status=1 AND group_id=14 order by name";
                            $re_sql = mysqli_query($conn, $sql);
                            while($vender_data = mysqli_fetch_assoc($re_sql)){
                            ?>
                            <option value="<?php echo $vender_data['id']; ?>"><?php echo $vender_data['name']; ?></option>
                            <?php } ?>
                            </select>
                       </div>     
                    </div>    
                    </div>
                    
                    
                    
                    <div class="col-12 col-md-3">
                    <div class="row no-gutters">
                        <div  class="col-md-10">
                            <label class="col-12 row">Mobile</label>
                            <input type="text" class="form-control auto" id="mobile" data-name = "mobile" placeholder="Mobile" name="mobile" value="<?php if(isset($_REQUEST['search'])){echo $_REQUEST['mobile']; }?>">
                            <small class="empty-message text-danger"></small>
                       </div>     
                    </div>    
                    </div>
                    
                    
                    <div class="col-12 col-md-3">
                    <div class="row no-gutters">
                        <div  class="col-md-10">
                            <label class="col-12 row">Order No.</label>
                            <input type="text" class="form-control auto"  placeholder="Order No." data-name="orderno" name="orderno" value="<?php if(isset($_REQUEST['search'])){echo $_REQUEST['orderno']; }?>">
                            <small class="empty-message text-danger" id="empty-message"></small>
                       </div>     
                       </div>    
                    </div>
                    
                    
                    <div class="col-12 col-md-3">
                    <div class="row no-gutters">
                        <div  class="col-md-10">
                            <label class="col-12 row">Email ID</label>
                            <input type="text" class="form-control auto"  placeholder="Email ID" data-name = "email" name="email" value="<?php if(isset($_REQUEST['search'])){echo $_REQUEST['email']; }?>">
                            <small class="empty-message text-danger"></small>
                       </div>     
                    </div>    
                    </div>
                    
                    
                    </div>
                    
                     <div class="form-group row">
                    
                    <div class="col-12 col-md-3">
                    <div class="row no-gutters">
                    	<div  class="col-md-12">
                        <label class="col-12 row">From Date</label>
                       <div  class="input-group date datepicker">
                        <input type="text" class="form-control" name="fromdate" value="<?php if(isset($_REQUEST['search'])){echo $_REQUEST['fromdate']; }?>">
                        <span class="input-group-addon input-group-append border-left">
                          <span class="mdi mdi-calendar input-group-text"></span>
                        </span>
                      </div>
                    	</div>
                    </div>    
                    </div>
                    
                    
                      <div class="col-12 col-md-3">
                    <div class="row no-gutters">
                    	<div  class="col-md-12">
                        <label class="col-12 row">To Date</label>
                       <div class="input-group date datepicker">
                        <input type="text" class="form-control" name="todate" value="<?php if(isset($_REQUEST['search'])){echo $_REQUEST['todate']; }?>">
                        <span class="input-group-addon input-group-append border-left">
                          <span class="mdi mdi-calendar input-group-text"></span>
                        </span>
                      </div>
                    	</div>
                    </div>    
                    </div>
                    
                    <div class="col-12 col-md-3">
	                    <button type="submit" name="search" class="btn btn-success mt-30" style="margin-top:30px;">Search</button>
                    </div>
              
                    </div>   
                    </form>
                
                </div>
                </div>
              
                  
            </div>
            
             <!-- Table ------------------------------------------------------------------------------------------------------>
            
            <div class="col-md-12 grid-margin stretch-card">
              	<div class="card">
                <div class="card-body">
                
                	<!-- TABLE Filters btn -->
                    
                    
                    <!-- TABLE STARTS -->
                    <?php 

                        $data = [];
                        /// by vender ///
                        $sqlqry = "SELECT orders.id, orders.order_no as orderno, orders.created as orderdate, product_master.product_name as productname, ledger_master.name as vendorname, ledger_master.mobile as mobile, ledger_master.email as email from((orders INNER JOIN product_master ON orders.product_id = product_master.id) INNER JOIN ledger_master ON orders.vendor_id = ledger_master.id) WHERE orders.status = '1'";
                        
                        if(isset($_REQUEST['vender_id']) && $_REQUEST['vender_id'] != ''){
                          $sqlqry .= "AND (ledger_master.id = '".$_REQUEST['vender_id']."')";
                        }
                        
                    
                        if(isset($_REQUEST['mobile']) && $_REQUEST['mobile'] != ''){
                         $sqlqry .= "AND (ledger_master.mobile = '".$_REQUEST['mobile']."') ";
                         //$sql .= "AND (ledger_master.mobile = '".$_REQUEST['mobile']."') ";
                        }

                        if(isset($_REQUEST['orderno']) && $_REQUEST['orderno'] != '')
                        {
                          $sqlqry .= "AND (orders.order_no = '".$_REQUEST['orderno']."') ";
                          //$sql .= "AND (byproduct.order_no = '".$_REQUEST['orderno']."') ";
                        }

                        if(isset($_REQUEST['email']) && $_REQUEST['email'] != '')
                        {
                          $sqlqry .= "AND (ledger_master.email = '".$_REQUEST['email']."') ";
                          //$sql .= "AND (ledger_master.email = '".$_REQUEST['email']."') ";
                        }

                        if((isset($_REQUEST['fromdate']) && $_REQUEST['fromdate'] != '') && (isset($_REQUEST['todate']) && $_REQUEST['todate'] != ''))  
                        {
                          $from = date('Y-m-d',strtotime($_REQUEST['fromdate']));
                          $to = date('Y-m-d',strtotime($_REQUEST['todate']));
                          $sqlqry .= "AND DATE_FORMAT(orders.created,'%Y-%m-%d') >= '".$from."' AND DATE_FORMAT(orders.created,'%Y-%m-%d') <= '".$to."' ";
                        }

                        $sqlqryrun = mysqli_query($conn, $sqlqry);
                    ?>
                    <div class="col mt-3">
                    	 <div class="row">
                            <div class="col-12">
                              <table id="order-listing1" class="table">
                                <thead>
                                  <tr>
                                      <th>Order No</th>
                                      <th>Order Date</th>
                                      <th>Product Name</th>
                                      <th>Vendor Name</th>
                                      <th>Mobile</th>
                                      <th>Email</th>
                                      <th>&nbsp;</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts --> 
                                    
                                    <?php
                                      if($sqlqryrun){ 
                                        while($sqldata = mysqli_fetch_assoc($sqlqryrun)){ ?>
                                      <tr>
                                          <td><?php echo $sqldata['orderno'];?></td>
                                          <td><?php echo date('d/m/Y',strtotime($sqldata['orderdate']));?></td>
                                          <td><?php echo $sqldata['productname'];?></td>
                                          <td><?php echo $sqldata['vendorname']?></td>
                                          <td><?php echo $sqldata['mobile'];?></td>
                                          <td><?php echo $sqldata['email']?></td>
                                          <td>
                                            <a href="order-list-tab.php?id=<?php echo $sqldata['id']; ?>" class="btn btn-warning p-2" title="Email">
                                              <i class="fa fa-envelope mr-0"></i>
                                            </a>
                                          </td>
                                          <td>
                                            <a href="#" class="btn btn-primary p-2" title="Print">
                                              <i class="fa fa-print mr-0"></i>
                                            </a>
                                            <a href="#" class="btn btn-primary p-2" title="CSV">
                                              <i class="fa fa-file mr-0"></i>
                                            </a>
                                          </td>
                                      </tr><!-- End Row -->
                                      <?php } } ?>
                                 
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
  <script src="js/custom/order_list_tab.js"></script>
  <script src="js/jquery-ui.js"></script>

  
  <!-- Custom js for this page Modal Box-->
  <script src="js/modal-demo.js"></script>
  
  
  <!-- Datepicker Initialise-->
 <script>
    $('.datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      autoclose: true,
    });
 </script>
 
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script> 
  
  <script>
  	 $('#order-listing2').DataTable();
  </script>
  
  <script>
  	 $('#order-listing1').DataTable();
  </script>
  
  
  <!-- End custom js for this page-->
</body>


</html>
