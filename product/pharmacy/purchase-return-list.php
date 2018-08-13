<?php include('include/usertypecheck.php');?>
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
  <!-- parsley css for validation -->
  <link rel="stylesheet" href="css/parsley.css">
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
          <div class="row">
           <!-- Bank Management Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                
                  <!-- Main Catagory -->
                    <div class="row">
                      <div class="col-12">
                        <div class="purchase-top-btns">
                            <a href="purchase.php" class="btn btn-dark active">Purchase Bill</a>
                            <a href="purchase-return.php" class="btn btn-dark">Purchase Return</a>
                            <a href="purchase-return-list.php" class="btn btn-dark">Purchase Return List</a>
                            <a href="#" class="btn btn-dark btn-fw">Cancel List</a>
                            <a href="purchase-history.php" class="btn btn-dark btn-fw">History</a>
                            <a href="#" class="btn btn-dark btn-fw">Settings</a>
                        </div>   
                    </div> 
                    </div>
                    <hr>
                    
                
                
                 <br>
                  <form class="forms-sample">
                  
                  <div class="form-group row">
                  
                      <div class="col-12 col-md-3">
                       <label for="exampleInputName1">Vendor Name</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                <option value="Regular">Alaska</option>
                                <option value="Unregistered">Alaska</option>
                            </select>
                      </div>
                      
                      <div class="col-12 col-md-2">
                       <label for="exampleInputName1">Invoice Number</label>
                       <input type="text" class="form-control" id="exampleInputName1" placeholder="Invoice Number">
                      </div>
                      
                      <div class="col-12 col-md-3">
                       <label for="exampleInputName1">Product</label>
                       <input type="text" class="form-control" id="exampleInputName1" placeholder="Product">
                      </div>
                      
                      <div class="col-12 col-md-2">
                       <label for="exampleInputName1">GR.No</label>
                       <input type="text" class="form-control" id="exampleInputName1" placeholder="GR.No">
                      </div>
                      
                      <div class="col-12 col-md-2">
                       <label for="exampleInputName1">Returned Date</label>
                         <div id="datepicker-popup" class="input-group date datepicker">
                            <input type="text" class="form-control border" >
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                      </div>
                      
                      <div class="col-12 col-md-3">
                       <label for="exampleInputName1">Batch</label>
                       <input type="text" class="form-control" id="exampleInputName1" placeholder="Batch Number">
                      </div>
                      
                      <div class="col-12 col-md-3">
                      	<button type="submit" class="btn btn-success mt-30">Submit</button>
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
                    <div class="col mt-3">
                    	 <div class="row">
                            <div class="col-12">
                              <table id="order-listing1" class="table">
                                <thead>
                                  <tr>
                                      <th>Return No</th>
                                      <th>Return Date</th>
                                      <th>Invoice/GR</th>
                                      <th>Invoice Date</th>
                                      <th>Vendor</th>
                                      <th>Mobile</th>
                                      <th>Reason</th>
                                      <th>Payment Status</th>
                                      <th>Payment Remarks</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts --> 	
                                  <tr>
                                      <td>O133</td>
                                      <td> 14/07/2018</td>
                                      <td>567 / O133</td>
                                      <td>14/07/2018</td>
                                      <td>Agarwal Distributors</td>
                                      <td>-</td>
                                      <td>Damage</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>
                                      	<a href="#" class="btn  btn-behance p-2"><i class="fa fa-print mr-0"></i></a>
                                        <a href="#" class="btn  btn-behance p-2"><i class="fa fa-pencil mr-0"></i></a>
                                        <a href="#" class="btn  btn-danger p-2">Cancel</a>
                                        <a href="#" class="btn btn-primary  p-2" data-toggle="modal" data-target="#exampleModal-4" data-whatever="@mdo">Apply Credit Note</a>
                                      </td>
                                  </tr><!-- End Row --> 	
                                  
                                
                                  
                                 
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
  
  <!-- Custom js for this page Modal Box-->
  <script src="js/modal-demo.js"></script>
  
  
  <!-- Datepicker Initialise-->
 <script>
    $('#datepicker-popup1').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
    });
 </script>

 
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script> 
  
  <script>
  	 $('#order-listing2').DataTable();
  </script>
  <script type="text/javascript">
    $('form').parsley();
  </script>
  
  <!-- End custom js for this page-->
</body>


</html>
