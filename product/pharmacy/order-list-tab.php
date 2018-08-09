<?php include('include/usertypecheck.php'); ?>
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
          <div class="row">
          
           <!-- Inventory Form ------------------------------------------------------------------------------------------------------>
            <div class="col-md-12 grid-margin stretch-card">
            
                <div class="card">
                <div class="card-body">
                   	
                    <!-- Main Catagory -->
                    <div class="row">
                    <div class="col-12">
                        <div class="enventory">
                            <a href="order.php" class="btn btn-dark">Order</a>
                            <a href="order-list-tab.php" class="btn btn-dark  active">List</a>
                            <a href="#" class="btn btn-dark">Missed Sales Order</a>
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

                    
                    <form class="forms-sample" method="post" action="">
                    
                    
                    <div class="form-group row">
                    
                    <div class="col-12 col-md-3">
                    <div class="row no-gutters">
                        <div  class="col-md-10">
                            <label class="col-12 row">Select Vendor</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                            <option value="Regular">Please select</option>
                            <option value="Unregistered">MRP</option>
                            <option value="Composition">Product Name </option>
                            <option value="Composition">Generic Name</option>
                            </select>
                       </div>     
                    </div>    
                    </div>
                    
                    
                    
                    <div class="col-12 col-md-3">
                    <div class="row no-gutters">
                        <div  class="col-md-10">
                            <label class="col-12 row">Mobile</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Mobile" name="mobile" value="<?php if(isset($_REQUEST['search'])){echo $_REQUEST['mobile']; }?>">
                       </div>     
                    </div>    
                    </div>
                    
                    
                    <div class="col-12 col-md-3">
                    <div class="row no-gutters">
                        <div  class="col-md-10">
                            <label class="col-12 row">Order No.</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Order No." name="orderno" value="<?php if(isset($_REQUEST['search'])){echo $_REQUEST['orderno']; }?>">
                       </div>     
                       </div>    
                    </div>
                    
                    
                    <div class="col-12 col-md-3">
                    <div class="row no-gutters">
                        <div  class="col-md-10">
                            <label class="col-12 row">Email ID</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Email ID" name="email" value="<?php if(isset($_REQUEST['search'])){echo $_REQUEST['email']; }?>">
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
                        $sqlqry = "SELECT byvendor.order_no as orderno, byvendor.created as orderdate, product_master.product_name as productname, ledger_master.name as vendorname, ledger_master.mobile as mobile, ledger_master.email as email from((byvendor INNER JOIN product_master ON byvendor.product_id = product_master.id) INNER JOIN ledger_master ON byvendor.vendor_id = ledger_master.id) WHERE byvendor.status = '0' ";

                        $sql = "SELECT byproduct.order_no as orderno, byproduct.created as orderdate, product_master.product_name as productname, ledger_master.name as vendorname, ledger_master.mobile as mobile, ledger_master.email as email from((byproduct INNER JOIN product_master ON byproduct.product_id = product_master.id) INNER JOIN ledger_master ON byproduct.vendor_id = ledger_master.id) WHERE byproduct.status = '0'";
                        
                        $productqry = "";
                    
                        if(isset($_REQUEST['mobile']) && $_REQUEST['mobile'] != ''){
                         $sqlqry .= "AND (ledger_master.mobile = '".$_REQUEST['mobile']."') ";
                         $sql .= "AND (ledger_master.mobile = '".$_REQUEST['mobile']."') ";
                        }

                        if(isset($_REQUEST['orderno']) && $_REQUEST['orderno'] != '')
                        {
                          $sqlqry .= "AND (byvendor.order_no = '".$_REQUEST['orderno']."') ";
                          $sql .= "AND (byproduct.order_no = '".$_REQUEST['orderno']."') ";
                        }

                        if(isset($_REQUEST['email']) && $_REQUEST['email'] != '')
                        {
                          $sqlqry .= "AND (ledger_master.email = '".$_REQUEST['email']."') ";
                          $sql .= "AND (ledger_master.email = '".$_REQUEST['email']."') ";
                        }

                        if((isset($_REQUEST['fromdate']) && $_REQUEST['fromdate'] != '') && (isset($_REQUEST['todate']) && $_REQUEST['todate'] != ''))  
                        {
                          $from = date('Y-m-d',strtotime($_REQUEST['fromdate']));
                          $to = date('Y-m-d',strtotime($_REQUEST['todate']));
                          $sqlqry .= "AND DATE_FORMAT(byvendor.created,'%Y-%m-%d') >= '".$from."' AND DATE_FORMAT(byvendor.created,'%Y-%m-%d') <= '".$to."' ";
                          $sql .= "AND DATE_FORMAT(byproduct.created,'%Y-%m-%d') >= '".$from."' AND DATE_FORMAT(byproduct.created,'%Y-%m-%d') <= '".$to."' ";
                        }
                        $sqlqryrun = mysqli_query($conn, $sqlqry);

                        if($sqlqryrun){
                          while($sqldata = mysqli_fetch_assoc($sqlqryrun)){
                            $arr['orderno'] = $sqldata['orderno'];
                            $arr['orderdate'] = $sqldata['orderdate'];
                            $arr['productname'] = $sqldata['productname'];
                            $arr['vendorname'] = $sqldata['vendorname'];
                            $arr['mobile'] = $sqldata['mobile'];
                            $arr['email'] = $sqldata['email'];
                            array_push($data, $arr);
                          }
                        }

                        $insql = mysqli_query($conn, $sql);

                        if($insql){
                          while($sqldata1 = mysqli_fetch_assoc($insql)){
                            $arr1['orderno'] = $sqldata1['orderno'];
                            $arr1['orderdate'] = $sqldata1['orderdate'];
                            $arr1['productname'] = $sqldata1['productname'];
                            $arr1['vendorname'] = $sqldata1['vendorname'];
                            $arr1['mobile'] = $sqldata1['mobile'];
                            $arr1['email'] = $sqldata1['email'];
                            array_push($data, $arr1);
                          }
                        }                      
                
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
                                  <?php if(isset($data) && !empty($data)){ ?>
                                    <?php foreach ($data as $key => $value){ ?>
                                      <tr>
                                          <td><?php echo $value['orderno'];?></td>
                                          <td><?php echo date('d/m/Y',strtotime($value['orderdate']));?></td>
                                          <td><?php echo $value['productname'];?></td>
                                          <td><?php echo $value['vendorname']?></td>
                                          <td><?php echo $value['mobile'];?></td>
                                          <td><?php echo $value['email']?></td>
                                          <td>
                                            <a href="email_send.php?email=<?php echo $value['email'];?>" class="btn btn-warning p-2" title="Email">
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
