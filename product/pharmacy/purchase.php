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
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
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
        <?php include "include/topbar.php" ?>
        
        <?php
          function getVoucherNoByCash(){
            global $conn;
            $voucher_no = '';

            $cashQuery = "SELECT voucher_no, invoice_no FROM purchase WHERE purchase_type = 'Cash' ORDER BY id DESC LIMIT 1";
            $cashRes = mysqli_query($conn, $cashQuery);
            if($cashRes){
              $count = mysqli_num_rows($cashRes);
              if($count !== '' && $count !== 0){
                $row = mysqli_fetch_array($cashRes);
                $voucherno = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';

                if($voucherno != ''){
                  $vouchernoarr = explode('-',$voucherno);
                  $voucherno = $vouchernoarr[1];
                  $voucherno = $voucherno + 1;
                  $voucherno = sprintf("%05d", $voucherno);
                  $voucher_no = 'CV-'.$voucherno;
                }

              }else{
                $voucherno = sprintf("%05d", 1);
                $voucher_no = 'CV-'.$voucherno;
              }
            }
            return $voucher_no;
          }
        ?>
    
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
                  
                      <div class="col-12 col-md-2">
                        <label for="exampleInputName1">Voucher Date</label>
                            <div class="input-group date datepicker">
                            <input type="text" class="form-control border" value="<?php echo date('d/m/Y'); ?>">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                      </div>
                      
                      <div class="col-12 col-md-2">
                        <label for="exampleInputName1">Voucher No</label>
                        <?php 
                          $voucherVal = getVoucherNoByCash();
                        ?>
                        <input type="text" class="form-control" id="voucher_no" value="<?php echo $voucherVal; ?>" placeholder="Voucher No">
                      </div>
                      
                      <div class="col-12 col-md-2">
                        <label for="city">Select City</label>
                        <select class="js-example-basic-single" style="width:100%" name="city" id="city"> 
                                <option value="">Select City</option>
                                <?php 
                                  //$getAllCityQuery = "SELECT id, name FROM own_cities ct WHERE status='1' ORDER BY name";

                                  $getAllCityQuery = "SELECT lgr.city,ct.id as cityid,ct.name as cityname FROM  `ledger_master` lgr INNER JOIN `own_cities` ct ON lgr.city = ct.id where lgr.group_id = '14' order by ct.name ASC";
                                  
                                  $getAllCityRes = mysqli_query($conn, $getAllCityQuery);
                                  if($getAllCityRes && mysqli_num_rows($getAllCityRes) > 0){
                                    while ($rowofcity = mysqli_fetch_array($getAllCityRes)) {
                                ?>
                                  <option value="<?php echo $rowofcity['cityid']; ?>"> <?php echo $rowofcity['cityname']; ?> </option>
                                <?php
                                    }
                                  }
                                ?>
                            </select>
                      </div>
                      
                      <div class="col-12 col-md-2">
                        <label for="vendor">Select Vendor</label>
                        <select class="js-example-basic-single" style="width:100%" name="vendor" id="vendor"> 
                            <option value="">Select Vendor</option>
                        </select>
                        <input type="hidden" name="statecode" id="statecode">
                      </div>
                      
                       <div class="col-12 col-md-4">
                            <button type="button" class="btn btn-primary mt-30" data-toggle="modal" data-target="#purchase-addvendormodel" data-whatever="@mdo"><i class="fa fa-plus"></i> Add New Vendor</button>
                            <button type="button" class="btn btn-primary mt-30" data-toggle="modal" data-target="#purchase-addproductmodel" data-whatever="@mdo"><i class="fa fa-plus"></i> Add New Product</button>
                       </div>
                    
                  </div> 
                  
                  
                    <div class="form-group row">
                        <div class="col-12 col-md-2">
                        <label for="exampleInputName1">Invoice Date</label>
                            <div class="input-group date datepicker">
                            <input type="text" class="form-control border" value="<?php echo date('d/m/Y'); ?>">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                        </div>
                      
                      <div class="col-12 col-md-2">
                        <label for="exampleInputName1">Invoice No.</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Invoice No">
                      </div>
                      
                       <div class="col-12 col-md-2">
                        <label for="exampleInputName1">LR No</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="LR No">
                      </div>
                      
                       <div class="col-12 col-md-2">
                        <label for="exampleInputName1">LR Date</label>
                        <div class="input-group date datepicker">
                        <input type="text" class="form-control border" value="<?php echo date('d/m/Y'); ?>">
                        <span class="input-group-addon input-group-append border-left">
                          <span class="mdi mdi-calendar input-group-text"></span>
                        </span>
                      </div>
                      </div>
                        <div class="col-12 col-md-2">
                        <label for="exampleInputName1">Transporter Name</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Transporter Name">
                      </div>
                      
                     
                   
                    </div>
                   
                    <div class="form-group row">
                      <div class="col-12 col-md-2">
                        <label for="exampleInputName1">Purchase Type  </label>
                        <div class="row no-gutters">
                          <div class="col">
                              <div class="form-radio">
                              <label class="form-check-label">
                              <input type="radio" class="form-check-input purchase_type" name="purchase_type" value="Cash" checked>
                             Cash
                              </label>
                              </div>
                          </div>
                          
                          <div class="col">
                              <div class="form-radio">
                              <label class="form-check-label">
                              <input type="radio" class="form-check-input purchase_type" name="purchase_type" value="Debit">
                              Debit
                              </label>
                              </div>
                          </div>
                        </div>
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
                                      <th>Rate</th>
                                      <th>Ammount</th>
                                      <th>&nbsp;</th>
                                  </tr>
                                </thead>
                                <tbody id="product-tbody">
                                  <!-- Row Starts -->   
                                  <tr class="product-tr">
                                      <td>1</td>
                                      <td>
                                        <input type="text" placeholder="Product" class="tags form-control" name="product[]">
                                        <input type="hidden" class="product-id" name="product[]">

                                        <small class="text-danger empty-message0"></small>
                                      </td>
                                      <td>
                                        <input type="text" name="mrp[]" class="form-control mrp" id="mrp" placeholder="MRP" autocomplete="off">
                                      </td>
                                      <td>
                                        <input type="text" name="mfg_co[]" class="form-control mfg_co" id="mfg_co" placeholder="MFG. Co." autocomplete="off">
                                      </td>
                                      <td>
                                        <input type="text" name="batch[]" class="form-control batch" id="batch" placeholder="Batch" autocomplete="off">
                                      </td>
                                      <td>
                                        <input type="text" name="expiry[]" class="form-control datepicker-ex expiry" style="width: 80px;" id="expiry" placeholder="Expiry" autocomplete="off">
                                      </td>
                                      <td>
                                        <input type="text" name="qty[]" class="form-control qty" id="qty" placeholder="Qty.">
                                        <input type="hidden" class="qty-value" name="qty_value[]" autocomplete="off">
                                      </td>
                                      <td>
                                        <input type="text" name="free_qty[]" class="form-control free_qty" id="free_qty" placeholder="Free Qty" autocomplete="off">
                                      </td>
                                      <td>
                                        <input type="text" name="rate[]" class="form-control rate" id="rate" placeholder="Rate" autocomplete="off">
                                      </td>
                                      <td>
                                        <input type="text" name="discount[]" class="form-control discount" id="discount" placeholder="Discount" autocomplete="off">
                                      </td>
                                      <td>
                                        <input type="text" name=f_rate[] class="form-control f_rate" id="f_rate" placeholder="Rate" autocomplete="off">
                                      </td>
                                      <td>
                                        <input type="text" name=ammout[] class="form-control ammout" id="ammout" placeholder="Ammount" autocomplete="off">
                                        <input type="hidden" name="f_igst[]" class="f_igst">
                                        <input type="hidden" name="f_cgst[]" class="f_cgst">
                                        <input type="hidden" name="f_sgst[]" class="f_sgst">
                                      </td>
                                      <td><a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a></td>
                                  </tr><!-- End Row --> 
                                  
                                 
                                  
                                 
                                </tbody>
                              </table>
                            </div>
                          </div>
                    </div>
                    
                    
                    <hr>
                    <div class="col-12">
                      <div class="row">
                          <div class="col-md-6 offset-6">
                              <div class="form-group row">
                                
                                
                                
                                
                                <table class="table table-striped">
                   
                     <tbody>
                    
                      <tr>
                        <td align="right" style="width:100px;">
                          Total
                        </td>
                        <td align="right"><input class="form-control" type="text" name="total_amount[]" id="total_amount" readonly="">
                         
                        </td>
                      </tr>
                      
                      
                      
                      
                       <tr>
                        <td align="right">
                          <select class="form-control" name="courier" id="courier_charge" style="width:250px;">
                                <option value="">Freight/Courier Charge </option>
                                <option value="5">5</option>
                                <option value="12">12</option>
                                <option value="18">18</option>
                            </select>
                        </td>
                        <td align="right"> <input type="text" name="total_courier" class="form-control" id="total_courier"></td>
                      </tr>
                      
                      
                       <tr>
                        <td align="right">
                          Total Tax (GST)
                        </td>
                        <td align="right">
                          <input type="text" class="form-control" readonly="" name="total_tax" id="total_tax">
                          <input type="hidden" id="hidden-total_tax">
                        </td>
                      </tr>
                      
                      
                       <tr>
                        <td align="right">
                          IGST
                        </td>
                        <td align="right">
                          <input type="text" class="form-control" readonly="" name="total_igst" id="total_igst">
                          <input type="hidden" id="hidden_total_igst">
                        </td>
                      </tr>
                      
                      <tr>
                        <td align="right">
                          CGST
                        </td>
                        <td align="right">
                          <input type="text" class="form-control" readonly="" name="total_cgst" id="total_cgst">
                          <input type="hidden" id="hidden_total_cgst">
                        </td>
                      </tr>
                      
                      <tr>
                        <td align="right">
                          SGST
                        </td>
                        <td align="right">
                          <input type="text" class="form-control" readonly="" name="total_sgst" id="total_sgst">
                          <input type="hidden" id="hidden_total_sgst">
                        </td>
                      </tr>
                      <input type="hidden" id="hidden_total">
                      <tr >
                        <td align="right">
                          Discount
                        </td>
                        <td align="right">
                          <div class="radio-inline">
                                 <div class="icheck" style="display:inline-block">
                                    <input tabindex="7"  type="radio" id="minimal-radio-1" value="per" name="minimal-radio" checked>
                                    <label for="minimal-radio-1" class="mt-0" >%</label>
                                  </div>
                                  <input type="text"  class="form-control f_discount" id="exampleInputName1" placeholder="%" style="display:inline-block;width:80px;">
                          </div>
                            
                                        
                          <div class="radio-inline ml-2">            
                                <div class="icheck" style="display:inline-block">
                                    <input tabindex="8" type="radio" id="minimal-radio-2" value="rs" name="minimal-radio" >
                                    <label for="minimal-radio-2" class="mt-0">Rs.</label>
                                </div>
                                <input type="text" class="form-control f_discount" id="exampleInputName1" placeholder="Rs." style="display:inline-block;width:80px;">
                            </div>
                              
                                    
                        </td>
                      </tr>
                      
                      
                      <tr>
                        <td align="right">
                         Overall Dis. Value
                        </td>
                        <td align="right"><input type="text" readonly="" name="overall_value" class="form-control" id="overall_value"></td>
                      </tr>
                      
                       <tr>
                        <td align="right">
                          <select class="form-control" id="note_details" style="width:250px;">
                                <option value="credit_note">Credit Note</option>
                                <option value="debit_note">Debit Note</option>
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
                          <input type="text" class="form-control" readonly="" name="purchase_amount" id="purchase_amount">
                        </td>
                      </tr>
                      
                      <tr style="background:#e0e0e0;">
                        <td align="right">
                          Round off
                        </td>
                        <td align="right">
                          <input type="text" class="form-control" readonly="" name="round_off" id="round_off">
                        </td>
                      </tr>
                      
                      <tr style="background:#0062ab;color:#fff;">
                        <td align="right">
                          <strong>NET VALUE</strong>
                        </td>
                        <td align="right">
                         <i class="fa fa-rupee"></i>&nbsp;<input type="text" class="form-control" readonly="" name="total_total" id="total_total">
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

        <!-- copy html kartik champaneriya 
           author : Kartik Champaneriya
           date   : 28-07-2018
        -->
        <div id="html-copy" style="display: none;">
          <table>
            <tr class="product-tr">
              <td>##SRNO##</td>
              <td>
                <input type="text" name="product[]" placeholder="Product" class="tags form-control">
                <input type="hidden" class="product-id" name="product[]">
                <small class="text-danger empty-message##PRODUCTCOUNT##"></small>
              </td>
              <td>
                <input type="text" name="mrp[]" class="form-control mrp" id="mrp" placeholder="MRP" autocomplete="off">
              </td>
              <td>
                <input type="text" name="mfg_co[]" class="form-control mfg_co" id="mfg_co" placeholder="MFG. Co." autocomplete="off">
              </td>
              <td>
                <input type="text" name="batch[]" class="form-control batch" id="batch" placeholder="Batch" autocomplete="off">
              </td>
              <td>
                <input type="text" name="expiry[]" class="form-control datepicker-ex expiry" style="width: 80px;" id="expiry" placeholder="Expiry" autocomplete="off">
              </td>
              <td>
                <input type="text" name="qty[]" class="form-control qty" id="qty" placeholder="Qty." autocomplete="off">
                <input type="hidden" class="qty-value" name="qty_value[]">
              </td>
              <td>
                <input type="text" name="free_qty[]" class="form-control free_qty" id="free_qty" placeholder="Free Qty" autocomplete="off">
              </td>
              <td>
                <input type="text" name="rate[]" class="form-control rate" id="rate" placeholder="Rate" autocomplete="off">
              </td>
              <td>
                <input type="text" name="discount[]" class="form-control discount" id="discount" placeholder="Discount" autocomplete="off">
              </td>
              <td>
                <input type="text" name=f_rate[] class="form-control f_rate" id="f_rate" placeholder="Rate" autocomplete="off">
              </td>
              <td>
                <input type="text" name=ammout[] class="form-control ammout" id="ammout" placeholder="Ammount" autocomplete="off">
                <input type="hidden" name="f_igst[]" class="f_igst">
                <input type="hidden" name="f_cgst[]" class="f_cgst">
                <input type="hidden" name="f_sgst[]" class="f_sgst">
              </td>
              <td><a href="javascript:;" class="btn btn-primary btn-xs pt-2 pb-2 btn-addmore-product"><i class="fa fa-plus mr-0 ml-0"></i></a><a href="javascript:;" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-product"><i class="fa fa-close mr-0 ml-0"></i></a></td>
          </tr><!-- End Row --> 
          </table>
        </div>
        <!-- copy html end kartik champaneriya -->

        
        <!-- partial:partials/_footer.php -->
        <?php include "include/footer.php" ?>
        <!-- partial -->
        
        
        
        <!-- Add new vendor Model -->
        <div class="modal fade" id="purchase-addvendormodel" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
              
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel">Add New Vendor</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form id="add-vendor">
                  <div class="modal-body">
                    <span id="addvendor-errormsg"></span>
                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="name">Vendor Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Vendor Name" required>   
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="mobile">Mobile</label>
                        <input type="text" class="form-control" name="mobile" placeholder="Mobile">   
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="email" placeholder="Email" required>   
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="addressline1">Address Line 1</label>
                        <input type="text" class="form-control" name="addressline1" placeholder="Address Line 1">   
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="addressline2">Address Line 2</label>
                        <input type="text" class="form-control" name="addressline2" placeholder="Address Line 2">   
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="addressline3">Address Line 3</label>
                        <input type="text" class="form-control" name="addressline3" placeholder="Address Line 3">   
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="country">Select Country <span class="text-danger">*</span></label>
                          <select class="js-example-basic-single" style="width:100%" name="country" id="country" required> 
                                  <option value="">Select Country</option>
                                  <?php 
                                    $countryQuery = "SELECT id, name FROM own_countries order by name ASC";
                                    $counteryRes = mysqli_query($conn, $countryQuery);
                                  ?>
                                  <?php if($counteryRes){ ?>
                                    <?php while ($countryRow = mysqli_fetch_array($counteryRes)) { ?>
                                      <option value="<?php echo $countryRow['id']; ?>" <?php echo (isset($ledgerdata['country']) && $ledgerdata['country'] == $countryRow['id']) ? 'selected' : ''; ?> ><?php echo $countryRow['name']; ?></option>
                                    <?php } ?>
                                  <?php } ?>
                          </select>
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="state">Select State <span class="text-danger">*</span></label>
                          <select class="js-example-basic-single" style="width:100%" name="state" id="state" required> 
                                  <option value="">Select State</option>
                          </select>
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="district">District</label>
                        <input type="text" class="form-control" name="district" placeholder="District">   
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="city">Select City <span class="text-danger">*</span></label>
                          <select class="js-example-basic-single" style="width:100%" name="city" id="vendorcity" required> 
                                  <option value="">Select City</option>
                          </select>
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="opening_balance">Opening Balance</label>
                        <input type="text" class="form-control" name="opening_balance" placeholder="Opening Balance">   
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="opening_balance_type">Opening Balance Type</label>
                          <select class="form-control" style="width:100%" name="opening_balance_type"> 
                                  <option value="DB">DB</option>
                                  <option value="CR">CR</option>
                          </select>
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="crdays">Cr Days</label>
                        <input type="text" class="form-control" name="crdays" placeholder="Cr Days" data-parsley-type="number" value="">
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="panno">Pan No</label>
                        <input type="text" class="form-control" name="panno" placeholder="Pan No" value="">
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="gstno">GST No</label>
                        <input type="text" class="form-control" name="gstno" placeholder="GST No" value="">
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="bank_name">Bank Name</label>
                        <input type="text" class="form-control" name="bank_name" placeholder="Bank Name" value="">
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="bank_ac_no">Bank A/c No</label>
                        <input type="text" class="form-control" name="bank_ac_no" placeholder="Bank A/c No" value="">
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="branch_name">Branch Name</label>
                        <input type="text" class="form-control" name="branch_name" placeholder="Branch Name" value="">
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="ifsc_code">IFSC Code</label>
                        <input type="text" class="form-control" name="ifsc_code" placeholder="IFSC Code" value="">
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="dl_no1">DL No 1</label>
                        <input type="text" name="dl_no1" class="form-control" placeholder="DL No 1" value="">
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="dl_no2">DL No 2</label>
                        <input type="text" name="dl_no2" class="form-control" placeholder="DL No 2" value="">
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="vendor_type">Vender Type</label>
                        <select class="form-control" name="vendor_type" style="width:100%"> 
                          <option value="Regular">Regular</option>
                          <option value="Unregistered">Unregistered</option>
                          <option value="Composition">Composition</option>
                        </select>
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="under">Under <span class="text-danger">*</span></label>
                        <select class="form-control" name="under" style="width:100%" required>
                          <option value="">Select Under Group</option>
                          <option value="1">Trading A/C</option>
                          <option value="2">P &amp; L A/C</option>
                          <option value="3">Balance Sheet</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  
                  <div class="modal-footer row">
                    <div class="col-md-12">
                      <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-success pull-right" id="btn-addvendor">Save</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
        </div>
        
         <!-- Add new Product Model -->
        <div class="modal fade" id="purchase-addproductmodel" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
              
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel">Add new Product</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form id="add-product">
                  <div class="modal-body">
                      <span id="addproduct-errormsg"></span>
                      <div class="form-group row">
                
                        <div class="col-12 col-md-4">
                          <label for="product_name">Product Name<span class="text-danger">*</span></label>
                          <input type="text" name="product_name" class="form-control" placeholder="Product Name" required>
                        </div>

                        <div class="col-12 col-md-4">
                          <label for="generic_name">Generic Name <span class="text-danger">*</span></label>
                        <input type="text" name="generic_name" class="form-control" placeholder="Generic Name " required>
                        </div>
                        
                         <div class="col-12 col-md-4">
                          <label for="exampleInputName1">MFG. Company<span class="text-danger">*</span></label>
                          <input type="text" name="mfg_company" value="<?php echo $data['mfg_company']; ?>" class="form-control" placeholder="MFG. Company" required>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="schedule_cat">Schedule Category</label>
                            <select class="js-example-basic-single" name="schedule_cat" style="width:100%">
                                <option value="">Select Schedule Category</option>
                                <option value="Schedule1">Schedule1</option>
                                <option value="Schedule2">Schedule2</option>
                            </select>
                        </div>
                    
                        <div class="col-12 col-md-4">
                          <label for="product_type">Product  Type<span class="text-danger">*</span></label>
                          <select name="product_type" class="js-example-basic-single" style="width:100%" required> 
                            <option value="">Select Product Type</option>
                            <?php 
                            $productTypeQry = "SELECT id, product_type FROM `product_type` WHERE status='1'";
                            $ProductTypeRes = mysqli_query($conn,$productTypeQry);
                            if($ProductTypeRes){
                              while($product_type = mysqli_fetch_assoc($ProductTypeRes)){
                            ?>
                              <option value="<?php echo $product_type['id']; ?>"><?php echo $product_type['product_type']; ?></option>
                              <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                    
                        <div class="col-12 col-md-4">
                          <label for="product_cat">Product Category<span class="text-danger">*</span></label>
                            <select class="js-example-basic-single" style="width:100%" name="product_cat" required> 
                                <option value="">Select Product Category</option>
                                <?php 
                                $productCatQry = "SELECT id, product_cat  FROM `product_category` WHERE status='1'";
                                $productCatRes = mysqli_query($conn,$productCatQry);
                                if($productCatRes){
                                  while($product_category = mysqli_fetch_assoc($productCatRes)){
                                ?>
                                  <option value="<?php echo $product_category['id']; ?>"><?php echo $product_category['product_cat']; ?></option>
                                  <?php } ?>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-12 col-md-4">
                          <label for="sub_cat">Sub Category</label>
                          <select name="sub_cat" class="js-example-basic-single" style="width:100%">
                                <option value="">Select Sub Category</option>
                                <option value="Sub Cat1">Sub Cat1</option>
                                <option value="Sub Cat2">Sub Cat2</option>
                                <option value="Sub Cat3">Sub Cat3</option>
                            </select>
                        </div>
                      
                        <div class="col-12 col-md-4">
                          <label for="hsn_code">HSN Code<span class="text-danger">*</span></label>
                          <input name="hsn_code" type="text" class="form-control" placeholder="HSN Code" required>
                        </div>
                      
                        <div class="col-12 col-md-4">
                          <label for="batch_no">Batch No</label>
                          <input type="text" name="batch_no" class="form-control" placeholder="Batch No">
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="ex_date">Expiry Date</label>
                            <div id="datepicker-popup1" class="input-group date datepicker">
                            <input name="ex_date" type="text" class="form-control border" placeholder="dd/mm/yyyy" autocomplete="off">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                        </div>
                      
                        <div class="col-12 col-md-4">
                          <label for="opening_qty">Opening Qty<span class="text-danger">*</span></label>
                          <input type="text" name="opening_qty" class="form-control" placeholder="Opening Qty" data-parsley-type="number" required>
                        </div>
                      
                        <div class="col-12 col-md-4">
                          <label for="opening_qty_godown">Opening Qty in Godown<span class="text-danger">*</span></label>
                          <input type="text" name="opening_qty_godown" class="form-control" placeholder="Opening Qty in Godown" data-parsley-type="number" required>
                        </div>

                        <div class="col-12 col-md-4">
                          <label for="give_mrp">Give a New MRP<span class="text-danger">*</span></label>
                          <input type="text" name="give_mrp" class="form-control" placeholder="Give a New MRP" data-parsley-type="number" required>
                        </div>
                      
                        <div class="col-12 col-md-4">
                          <label for="ex_duty">Ex. Duty<span class="text-danger">*</span></label>
                          <div class="input-group">
                            <div class="input-group-prepend bg-dark">
                              <span class="input-group-text bg-transparent"><i class="mdi mdi-percent text-white"></i></span>
                            </div>
                            <input type="text" name="ex_duty" class="form-control" placeholder="Ex. Duty" aria-label="Ex. Duty" required>
                          </div>
                        </div>
                      
                        <div class="col-12 col-md-4">
                          <label for="igst">IGST</label>
                          <input type="text" name="igst" class="form-control numberOnly" placeholder="IGST" data-parsley-type="number">
                        </div>

                        <div class="col-12 col-md-4">
                          <label for="cgst">CGST</label>
                          <input type="text"  name="cgst" class="form-control numberOnly" placeholder="CGST" data-parsley-type="number">
                        </div>
                        <div class="col-12 col-md-4">
                          <label for="sgst">SGST</label>
                          <input type="text"  name="sgst" class="form-control numberOnly" placeholder="SGST" data-parsley-type="number">
                        </div>
                        
                        <div class="col-12 col-md-4">
                          <label for="inward_rate">Inward Rate<span class="text-danger">*</span></label>
                          <input type="text" name="inward_rate" class="form-control numberOnly" placeholder="INWARD Rate" required data-parsley-type="number">
                        </div>

                        <div class="col-12 col-md-4">
                          <label for="distributor_rate">Distributor Rate<span class="text-danger">*</span></label>
                          <input type="text" name="distributor_rate" class="form-control numberOnly" placeholder="Distributor Rate" data-parsley-type="number" required>
                        </div>

                        <div class="col-12 col-md-4">
                          <label for="sale_rate_local">Sales Rate<span class="text-danger">*</span></label>
                            <div class="row no-gutters">
                                <div class="col-12 col-md-6">
                               <input type="text" name="sale_rate_local" class="form-control numberOnly" placeholder="Local" data-parsley-type="number" required>
                                  </div>
                                 <div class="col-12 col-md-6">
                                  <input type="text" name="sale_rate_out" class="form-control numberOnly" placeholder="Out" data-parsley-type="number" required>
                                 </div>
                              </div>
                        </div>
                        
                        <div class="col-12 col-md-4">
                          <label for="exampleInputName1">Rack No<span class="text-danger">*</span></label>
                          <input type="text" name="rack_no" class="form-control" placeholder="Rack No" required>
                        </div>

                        <div class="col-12 col-md-4">
                          <label for="self_no">Self No<span class="text-danger">*</span></label>
                          <input type="text" name="self_no" class="form-control" placeholder="Self No" required> 
                        </div>
                        <div class="col-12 col-md-4">
                          <label for="box_no">Box No<span class="text-danger">*</span></label>
                          <input type="text" name="box_no" class="form-control" placeholder="Box No" required>
                        </div>
                        
                        <div class="col-12 col-md-4">
                          <label for="company_code">Company Code</label>
                          <input type="text" name="company_code" class="form-control" placeholder="Company Code">
                        </div>

                        <div class="col-12 col-md-4">
                          <label for="opening_stock">Opening Stock Rs</label>
                          <input type="text" name="opening_stock" class="form-control numberOnly" placeholder="Opening Stock Rs">
                        </div>
                          
                        <div class="col-12 col-md-4">
                          <label for="unit">Unit / Strip / Pack</label>
                          <input type="text" name="unit" class="form-control" placeholder="Opening Stock Rs">
                        </div>
                        
                        <div class="col-12 col-md-4">
                          <label for="min_qty">Min Qty.<span class="text-danger">*</span></label>
                          <input type="text" name="min_qty" class="form-control numberOnly" placeholder="Min Qty." data-parsley-type="number" required>
                        </div>

                        <div class="col-12 col-md-4">
                          <label for="ratio">Ratio<span class="text-danger">*</span></label>
                          <input type="text" name="ratio" class="form-control numberOnly" id="Ratio" placeholder="Ratio." data-parsley-type="number" required>
                        </div>
                     
                        <div class="col-12 col-md-4">
                          <label for="max_qty">Max Qty.<span class="text-danger">*</span></label>
                          <input type="text" name="max_qty" class="form-control numberOnly" data-parsley-type="number" placeholder="Max Qty." required>
                        </div>
                      
                      </div>
                  </div>
                  
                  <div class="modal-footer row">
                    <div class="col-md-12">
                      <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-success pull-right" id="btn-addproduct">Save</button>
                    </div>
                  </div>
                </form>
              </div>
          </div>
        </div>
        
                        
                        
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
  
  <script type="text/javascript">
    $(".product-select2").select2();
  </script>
  <!-- Datepicker Initialise-->
 <script>
    $('.datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy'
    });
 </script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <!-- Custom js for this page Datatables-->
  <script src="js/data-table.js"></script>
  <script src="js/custom/purchase.js"></script>
  
  <!-- script for custom validation -->
<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>
  
  
  <!-- End custom js for this page-->
</body>


</html>
