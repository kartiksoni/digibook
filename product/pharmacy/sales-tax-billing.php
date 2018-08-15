<?php include('include/usertypecheck.php');?>
<?php 
  function getInvoiceNo(){
    global $conn;
    $invoice_no = '';
    $getInvoiceNoQ = "SELECT invoice_no FROM tax_billing WHERE bill_type = 'Cash' ORDER BY id DESC LIMIT 1";
    $getInvoiceNoR = mysqli_query($conn, $getInvoiceNoQ);
    if($getInvoiceNoR){
      $countInvoice = mysqli_num_rows($getInvoiceNoR);
      if($countInvoice !== '' && $countInvoice !== 0){
        $row = mysqli_fetch_array($getInvoiceNoR);
        $invoice_no = (isset($row['invoice_no'])) ? $row['invoice_no'] : '';

        $invoice_noarr = explode('-',$invoice_no);
        $invoice_no = $invoice_noarr[1];
        $invoice_no = $invoice_no + 1;
        $invoice_no = sprintf("%05d", $invoice_no);
        $invoice_no = 'C-'.$invoice_no;
      }else{
        $invoice_no = sprintf("%05d", 1);
        $invoice_no = 'C-'.$invoice_no;
      }
    }
    return $invoice_no;
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
</head>
<body>
  <div class="container-scroller">
  
    <!-- Topbar -->
    <?php include "include/topbar.php"; ?>
    
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
           <!-- Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <!-- Main Catagory -->
                    <div class="row">
                      <div class="col-12">
                          <div class="purchase-top-btns">
                              <a href="sales-tax-billing.php" class="btn btn-dark active">Sales</a>
                              <a href="#" class="btn btn-dark">Sales Return</a>
                              <a href="#" class="btn btn-dark">Sales Return List</a>
                              <a href="#" class="btn btn-dark">Cancellation List</a>
                                  <a href="#" class="btn btn-dark dropdown-toggle" id="dropdownMenuButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Order</a>
                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton4">
                                    <a class="dropdown-item" href="sales-order.php">Order/Estimate/Templates</a>
                                    <a class="dropdown-item" href="#">History</a>
                                  </div>
                              <a href="sales-history.php" class="btn btn-dark">History</a>
                              <a href="#" class="btn btn-dark">Settings</a>
                          </div>   
                      </div> 
                    </div>
                </div>
              </div>
            </div>
              <!-- Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                 <br>
                  <form class="forms-sample">
                    <div class="form-group row">
                      <div class="col-12 col-md-2">
                        <label>Select City</label>
                        <select class="js-example-basic-single" style="width:100%" name="city_id" id="city_id">
                          <option value="">Select City</option>
                            <?php 
                              $getCustomerCityQ = "SELECT ct.id, ct.name FROM ledger_master lgr INNER JOIN own_cities ct ON lgr.city = ct.id WHERE group_id = 10 GROUP BY lgr.city ORDER BY ct.name";
                              $getCustomerCityR = mysqli_query($conn, $getCustomerCityQ);
                              if($getCustomerCityR && mysqli_num_rows($getCustomerCityR) > 0){
                                while ($cityRow = mysqli_fetch_array($getCustomerCityR)) {
                            ?>
                              <option value="<?php echo (isset($cityRow['id'])) ? $cityRow['id'] : ''; ?>"><?php echo (isset($cityRow['name'])) ? $cityRow['name'] : ''; ?></option>
                          <?php } } ?>
                        </select>
                      </div>
                      
                      <div class="col-12 col-md-2">
                        <label for="customer_id">Select Customer</label>
                        <select class="js-example-basic-single" style="width:100%" name="customer_id" id="customer_id"> 
                          <option value="">Select Customer</option>
                        </select>
                      </div>
                      
                      <div class="col-12 col-md-2">
                        <label for="exampleInputName1">Invoice Date</label>
                        <div class="input-group date datepicker">
                          <input type="text" class="form-control border" name="invoice_date" autocomplete="off" value="<?php echo date('d/m/Y'); ?>">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                      </div>
                      
                      <div class="col-12 col-md-2">
                       <label for="exampleInputName1">Invoice No</label>
                       <input type="text" class="form-control" name="invoice_no" id="invoice_no" placeholder="Invoice No" value="<?php echo getInvoiceNo(); ?>">
                      </div>
                      
                      <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Bill Type</label>
                        	<div class="row no-gutters">
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input bill_type" name="bill_type" value="Cash" checked>
                               		   CASH
                                  </label>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input bill_type" name="bill_type" value="Debit">
                                    DEBIT
                                </label>
                                </div>
                            </div>
                          </div>
                      </div>

                      <div class="col-12 col-md-2">
                        <a href="#" class="btn btn-primary btn-xs mt-30" data-toggle="modal" data-target="#add_customer_model" data-whatever="@mdo">Add Customer</a>
                      </div>
                      
                      <div class="col-12 col-md-12">
                        <div class="row">
                      	  <div class="col-12 col-md-12">
                            <label for="exampleInputName1">Add Shipping Address</label>
                          </div>
                          <div class="col-12 col-md-3">
                           	<input type="text" class="form-control" name="c_addr_1" id="c_addr_1" placeholder="Address line 1">
                          </div>
                          <div class="col-12 col-md-3">
                           	<input type="text" class="form-control" name="c_addr_2" id="c_addr_2" placeholder="Address line 2">
                          </div>
                          <div class="col-12 col-md-3">
                            <input type="text" class="form-control" name="c_addr_3" id="c_addr_3" placeholder="Address line 3">
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- Table -------------->
            <div class="col-md-12 grid-margin stretch-card">
            	<div class="card">
                <div class="card-body">
                	<!-- TABLE Filters btn -->
                  <!-- TABLE STARTS -->
                  <div class="col mt-3">
                  	<div class="row">
                      <div class="col-12">
                        <table class="table">
                          <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Product</th>
                                <th>MRP</th>
                                <th>MFG. Co.</th>
                                <th>Batch</th>
                                <th>Expiry</th>
                                <th>Qty.</th>
                                <th>Free Qty</th>
                                <th>Rate</th>
                                <th>Discount</th>
                                <th>Ammount</th>
                                <th>GST</th>
                                <th>Total Ammount</th>
                                <th>&nbsp;</th>
                            </tr>
                          </thead>
                          <tbody id="item-tbody">
                            <!-- Row Starts --> 	
                            <tr>
                                <td>1</td>
                                <td><input type="text" class="form-control" id="exampleInputName1" placeholder="Product"></td>
                                <td><input type="text" class="form-control" id="exampleInputName1" placeholder="MRP"></td>
                                <td><input type="text" class="form-control" id="exampleInputName1" placeholder="MFG. Co."></td>
                                <td><input type="text" class="form-control" id="exampleInputName1" placeholder="Batch"></td>
                                <td><input type="text" class="form-control" id="exampleInputName1" placeholder="Expiry"></td>
                                <td><input type="text" class="form-control" id="exampleInputName1" placeholder="Qty."></td>
                                <td><input type="text" class="form-control" id="exampleInputName1" placeholder="Free Qty"></td>
                                <td><input type="text" class="form-control" id="exampleInputName1" placeholder="Rate"></td>
                                <td><input type="text" class="form-control" id="exampleInputName1" placeholder="Discount"></td>
                                <td><input type="text" class="form-control" id="exampleInputName1" placeholder="Ammount"></td>
                                <td><input type="text" class="form-control" id="exampleInputName1" placeholder="GST"></td>
                                <td><input type="text" class="form-control" id="exampleInputName1" placeholder="0.00"></td>
                                <td><button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item"><i class="fa fa-plus mr-0 ml-0"></i></button></td>
                            </tr>
                            <!-- End Row --> 	
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="col-12">
                  	<div class="row">
                    	<div class="col-md-8">
                      	<div class="sales-filter-btns-right display-3" style="display:inline-block">
                              <a href="#" class="btn btn-primary-light-green btn-xs" data-toggle="modal" data-target="#exampleModal-5" data-whatever="">Alternates</a>
                              <a href="#" class="btn btn-primary-light-green btn-xs" data-toggle="modal" data-target="#exampleModal-6" data-whatever="">Missed Order</a>
                              <a href="#" class="btn btn-primary-light-green btn-xs" data-toggle="modal" data-target="#exampleModal-8" data-whatever="">Save &amp; Return </a>
                              <a href="#" class="btn btn-primary-light-green btn-xs">By Min Reorder</a>
                              <a href="#" class="btn btn-primary-light-green btn-xs">By Product</a>
                        </div>
                    
                    		<div class="sales-filter-btns-right display-3" style="display:inline-block">
                          <a href="#" class="btn btn-primary-light-green btn-xs">Branch Stock</a>
                          <a href="#" class="btn btn-grey-1 btn-xs">Cancel</a>
                          <a href="#" class="btn btn-primary-light-green btn-xs">Complete Sale</a>
                    		</div>
                      </div>
                        
                    	<div class="col-md-4">
                        <div class="form-group row">
                        	<table class="table table-striped" width="100%">
                            <tbody>
                            
                              <tr>
                                <td align="right" style="width:100px;">
                                  Total
                                </td>
                                <td align="right">
                                  0.00
                                </td>
                              </tr>
                              
                              <tr >
                                <td align="right">
                                  Discount
                                </td>
                                <td align="right" width="158px">
                                    <div class="radio-inline">
                                         <div class="icheck" style="display:inline-block">
                                            <input tabindex="7" type="radio" id="minimal-radio-1" name="minimal-radio">
                                            <label for="minimal-radio-1" class="mt-0" ></label>
                                          </div>
                                          <input type="text" class="form-control" id="exampleInputName1" placeholder="%" style="display:inline-block;width:80px;">
                                    </div>
                                    
                                                
                                    <div class="radio-inline ml-2">            
                                        <div class="icheck" style="display:inline-block">
                                            <input tabindex="8" type="radio" id="minimal-radio-2" name="minimal-radio" checked>
                                            <label for="minimal-radio-2" class="mt-0"></label>
                                        </div>
                                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Rs" style="display:inline-block;width:80px;">
                                    </div>
                                      
                    
                                            
                                </td>
                              </tr>
                              
                              
                              <tr>
                                <td align="right">
                                 Overall Dis. Value
                                </td>
                                <td align="right">
                                  0.00
                                </td>
                              </tr>
                              
                              
                              <tr>
                                <td align="right">
                                  <select class="form-control" id="exampleFormControlSelect2" style="width:160px;">
                                        <option value="">Freight/Courier Charge </option>
                                        <option value="">5</option>
                                        <option value="">12</option>
                                        <option value="">18</option>
                                    </select>
                                </td>
                                <td align="right">
                                  0.00
                                </td>
                              </tr>
                              
                              
                              <tr>
                                <td align="right">
                                  Total Tax (GST)
                                </td>
                                <td align="right">
                                  0.00
                                </td>
                              </tr>
                              
                              
                              <tr>
                                <td align="right">
                                  IGST
                                </td>
                                <td align="right">
                                  0.00
                                </td>
                              </tr>
                              
                              <tr>
                                <td align="right">
                                  CGST
                                </td>
                                <td align="right">
                                  0.00
                                </td>
                              </tr>
                              
                              <tr>
                                <td align="right">
                                  SGST
                                </td>
                                <td align="right">
                                  0.00
                                </td>
                              </tr>
                              
                              <tr>
                                <td align="right">
                                  <select class="form-control" id="exampleFormControlSelect2" style="width:160px;">
                                        <option value="">Credit Note</option>
                                        <option value="">Debit Note</option>
                                    </select>
                                </td>
                                <td align="right">
                                  <i class="fa fa-rupee"></i>&nbsp;0.00
                                </td>
                              </tr>
                              
                              <tr style="background:#ececec;">
                                <td align="right">
                                  Purchase Ammount
                                </td>
                                <td align="right">
                                  0.00
                                </td>
                              </tr>
                              
                              <tr style="background:#e0e0e0;">
                                <td align="right">
                                  Round off
                                </td>
                                <td align="right">
                                  0.00
                                </td>
                              </tr>
                              
                              <tr style="background:#0062ab;color:#fff;">
                                <td align="right">
                                  <strong>NET VALUE</strong>
                                </td>
                                <td align="right">
                                 <strong> 0.00</strong>
                                </td>
                              </tr>
                              
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
        <?php include "include/footer.php"?>
        <!-- partial -->
        <?php include "popup/add-customer-model.php"?>
        <!-- Alternates Model -->
        <div class="modal fade" id="exampleModal-5" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
              
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel">Product Alternate Selection</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <div class="modal-body">
                  <form>
                  
                    <div class="form-group row">
                  
                          <div class="col-12 col-md-6">
                            <label for="exampleInputName1">Product</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">Search</option>
                                    <option value="Unregistered">abc</option>
                                </select>
                          </div>
                          
                          <div class="col-12 col-md-6">
                            <label for="exampleInputName1">Generic Name</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">Search</option>
                                    <option value="Unregistered">H1</option>
                                </select>
                          </div>
                          
                          <div class="col-12 col-md-6">
                            <label for="exampleInputName1">Type</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Type">
                          </div>
                          
                          <div class="col-12 col-md-6">
                            <label for="exampleInputName1">Schedule</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Schedule">
                          </div>
                          
                          <div class="col-12 col-md-6">
                            <label for="exampleInputName1">Manufacturer</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Manufacturer">
                          </div>
         
                        
                      </div>
                    
                    
                  </form>
                </div>
                
                <div class="modal-footer">
                  <button type="button" class="btn btn-success">Add</button>
                  <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
        </div>
        
        <!-- Missed Order Model -->
        <div class="modal fade" id="exampleModal-6" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
              
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel">Missed Orders</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <div class="modal-body">
                  <form>
                  
                    <div class="form-group row">
                  
                          <div class="col-12 col-md-6">
                            <label for="exampleInputName1">Product</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">Search</option>
                                    <option value="Unregistered">abc</option>
                                </select>
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Qty.</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="00">
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Unit/MRP</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="00">
                          </div>
                          
                          <div class="col-12 col-md-3">
                           <button type="button" class="btn btn-success btn-xs mt-30">Add</button>
                          </div>
                          
                           <div class="col-12 col-md-3 mt-30">
                           <a href="#" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#exampleModal-7" data-whatever="">Add New Product</a>
                          </div>
                          
                         
                        
                      </div>
                    
                    
                  </form>
                  
                  <div class="row">
                  <div class="col-12">
                  	<table id="order-listing1" class="table">
                                <thead>
                                  <tr>
                                      <th>Product</th>
                                      <th>Qty.</th>
                                      <th>Unit/MRP</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts --> 	
                                  <tr>
                                      <td>O133</td>
                                      <td> 14/07/2018</td>
                                      <td>567 / O133</td>
                                      <td>
                                      	
                                        <a href="#" class="btn  btn-success p-2"><i class="icon-pencil mr-0"></i></a>
                                        <a href="#" class="btn  btn-danger p-2"><i class="icon-close mr-0"></i></a>
                                      </td>
                                  </tr><!-- End Row --> 	
                                  
                                
                                  
                                 
                                </tbody>
                              </table>            
                  </div>
                  </div>
                  
                  
                  
                  
                  
                  
                  
                </div>
                
                <div class="modal-footer">
                  <button type="button" class="btn btn-success">Add Order</button>
                  <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
        </div>
        
        
    	   <!-- Missed order sub - Add new product Model -->
        <div class="modal fade" id="exampleModal-7" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
              
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel">New Product</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <div class="modal-body">
                        <form>
                           <div class="form-group">
                           
                            <div class="row">
                  
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Name of Product</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Name of Product">   
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Generic Name</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Generic Name">
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Manufacturer</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Manufacturer">
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Scheduled Category</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">select</option>
                                    <option value="Unregistered">H1</option>
                                </select>
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Type</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Type">
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Catagory</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Catagory">
                          </div>
                          
                          <div class="col-12 col-md-3">
                            <label for="exampleInputName1">Sub Catagory</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">select</option>
                                    <option value="Unregistered">H1</option>
                                </select>
                          </div>
                          
                          </div>
                          
                          
                          <div class="row">
                          
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">HSN</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="HSN">
                          </div>
                          
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">GST Total%</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">5%</option>
                                    <option value="Unregistered">10%</option>
                                </select>
                          </div>
                          
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">CGST %</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="0.00">
                          </div>
                          
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">SGST %</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="0.00">
                          </div>
                          
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">IGST %</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="0.00">
                          </div>
                          
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">Units/Strip</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Units/Strip">
                          </div>
                          
                          </div>
                        
                      </div>
                        </form>
                    </div>
                
                <div class="modal-footer">
                  <button type="button" class="btn btn-success">Add</button>
                  <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
        </div>
        
        
        <!-- Save & Return Model -->
        <div class="modal fade" id="exampleModal-8" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
              
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel">Product Return</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <div class="modal-body">
                  <form>
                    <div class="form-group row">
                  
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">Product</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">Search</option>
                                    <option value="Unregistered">abc</option>
                                </select>
                          </div>
                          
                          
                          <div class="col-12 col-md-1">
                            <label for="exampleInputName1">Quantity</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="00">
                          </div>
                          
                          <div class="col-12 col-md-1">
                            <label for="exampleInputName1">Batch</label>
                            <select class="js-example-basic-single" style="width:100%"> 
                                    <option value="Regular">19</option>
                                </select>
                          </div>
                          
                          <div class="col-12 col-md-1">
                            <label for="exampleInputName1">Disc%</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="00">
                          </div>
                          
                          <div class="col-12 col-md-2">
                            <label for="exampleInputName1">Expiry</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="12/19">
                          </div>
                          
                          <div class="col-12 col-md-1">
                            <label for="exampleInputName1">GST%</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="00">
                          </div>
                          
                          <div class="col-12 col-md-1">
                            <label for="exampleInputName1">MRP</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="5">
                          </div>
                          
                          <div class="col-12 col-md-3">
                           <a href="#" class="btn btn-primary mt-30">Return</a>
                           <a href="#" class="btn btn-grey-1 mt-30">Clear</a>
                          </div>
                          
                          <div class="col-12 col-md-9 mt-1">
                          	<label for="exampleInputName1" class="color-green"><strong>Total Qty.:</strong> 15</label>&nbsp;
                            <label for="exampleInputName1" class="pull-right"><strong>Purchase Price:</strong> 1200</label>
                          </div>
                         
                        
                      </div>
                  </form>
                  
                  <div class="row">
                  <div class="col-12">
                  	<table id="order-listing1" class="table">
                                <thead>
                                  <tr align="left">
                                      <th>Sr No.</th>
                                      <th>Product</th>
                                      <th>Qty.</th>
                                      <th>Disc.%</th>
                                      <th>Ammount</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Row Starts --> 	
                                  <tr >
                                      <td>O133</td>
                                      <td>A O FORTE</td>
                                      <td>25</td>
                                      <td>5</td>
                                      <td>125</td>
                                  </tr><!-- End Row --> 	
                                  
                                
                                  
                                 
                                </tbody>
                              </table>            
                  </div>
                  </div>
                  
                  
                  
                  
                  
                  
                  
                </div>
                
                <div class="modal-footer">
                  <button type="button" class="btn btn-success">Return Items</button>
                  <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
        </div>
     
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  
<!-- -------------------------------------------HIDDEN TR START----------------------------------------------------- -->
  <div id="hiddenItemHtml" style="display: none;">
    <table>
      <tr>
        <td>##SRNO##</td>
        <td><input type="text" class="form-control" id="exampleInputName1" placeholder="Product"></td>
        <td><input type="text" class="form-control" id="exampleInputName1" placeholder="MRP"></td>
        <td><input type="text" class="form-control" id="exampleInputName1" placeholder="MFG. Co."></td>
        <td><input type="text" class="form-control" id="exampleInputName1" placeholder="Batch"></td>
        <td><input type="text" class="form-control" id="exampleInputName1" placeholder="Expiry"></td>
        <td><input type="text" class="form-control" id="exampleInputName1" placeholder="Qty."></td>
        <td><input type="text" class="form-control" id="exampleInputName1" placeholder="Free Qty"></td>
        <td><input type="text" class="form-control" id="exampleInputName1" placeholder="Rate"></td>
        <td><input type="text" class="form-control" id="exampleInputName1" placeholder="Discount"></td>
        <td><input type="text" class="form-control" id="exampleInputName1" placeholder="Ammount"></td>
        <td><input type="text" class="form-control" id="exampleInputName1" placeholder="GST"></td>
        <td><input type="text" class="form-control" id="exampleInputName1" placeholder="0.00"></td>
        <td>
          <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item"><i class="fa fa-plus mr-0 ml-0"></i></button>
          <button type="button" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-item" style=""><i class="fa fa-close mr-0 ml-0"></i></button>
        </td>
    </tr>
    </table>
  </div>
<!-- -------------------------------------------HIDDEN TR END----------------------------------------------------- -->
  
  
  

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
      format: 'dd/mm/yyyy',
      autoclose: true
    });
 </script>

<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>

<script src="js/custom/sales-tax-billing.js"></script>
<script src="js/custom/add-customer-popup.js"></script>
<script src="js/custom/onlynumber.js"></script>
  
  <!-- End custom js for this page-->
</body>


</html>
