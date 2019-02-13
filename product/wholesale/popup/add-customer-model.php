<style type="text/css">
  /*.c_hidden{display: none;}*/
</style>
<div class="modal fade" id="add_customer_model" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
      
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel">Add New Customer</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="add-customer-form" autocomplete="off">
          <div class="modal-body">
            <h6 style="font-weight: 600;">Company Profile</h6>
            <hr>
            <span id="addcustomer-errormsg"></span>
            <div class="form-group row">
              <div class="col-12 col-md-4">
                <label for="name">Company Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control onlyalphabet" name="name" placeholder="Company Name" required>   
              </div>
              <div class="col-12 col-md-4">
                 <label for="person_name">Person Name </label>
                 <input type="text" name="companyname" class="form-control" placeholder="Person Name" value="" data-parsley-pattern="^[A-Za-z -.,:'_]*$"  data-parsley-pattern-message="Enter only alphabets and specific symbols.">
              </div>
              <div class="col-12 col-md-4">
                  <label for="designation">Designation</label>
                  <input type="text" class="form-control onlyalphabet" name="designation" value="" placeholder="Designation">
              </div>
              <div class="col-12 col-md-4">
                <label for="opening_balance">Opening Balance</label>
                <input type="text" class="form-control onlynumber" name="opening_balance" placeholder="Opening Balance">   
              </div>
              <div class="col-12 col-md-4">
                <label for="opening_balance_type">Opening Balance Type</label>
                  <select class="form-control" style="width:100%" name="opening_balance_type"> 
                          <option value="DB">DB</option>
                          <option value="CR">CR</option>
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
              <div class="col-12 col-md-4">
                <label for="customer_type">Customer Type <span class="text-danger">*</span></label>
                <select class="form-control customer_type" name="customer_type" style="width:100%" required>
                  <option value="">Select Type</option>
                  <option value="GST_Regular">GST registered- Regular</option>
                  <option value="GST_Composition">GST registered- Composition</option>
                  <option value="GST_unregistered">GST unregistered</option>
                  <option value="Consumer">Consumer</option>
                  <option value="Overseas">Overseas</option>
                  <option value="SEZ">SEZ</option>
                  <option value="Deemed">Deemed exports- EOU's, STP's EHTP's etc</option>
                </select>
              </div>
               <div class="col-12 col-md-4 m-t-20 gstno-div ">
                        <label for="gstno">GST No</label>
                        <input type="text" class="form-control gstno" id="gst_no" name="gstno" placeholder="GST No" value="<?php echo (isset($ledgerdata['gstno'])) ? $ledgerdata['gstno'] : ''; ?>" data-parsley-pattern="^([0]{1}[1-9]{1}|[1-2]{1}[0-9]{1}|[3]{1}[0-7]{1})([a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}[1-9a-zA-Z]{1}[zZ]{1}[0-9a-zA-Z]{1})+$" data-parsley-pattern-message="Enter valid GST No." maxlength="15">
                       <div class="gst_error text-warning"><label>Note :- Enter a valid GSTIN to save the details</label></div>
                        
                      </div>
                      
                      <div class="col-12 col-md-4 m-t-20 panno-div">
                        <label for="panno">Pan No</label>
                        <input type="text" class="form-control" name="panno" id="pan_num" placeholder="Pan No" value="<?php echo (isset($ledgerdata['panno'])) ? $ledgerdata['panno'] : ''; ?>" data-parsley-pattern="[A-Za-z]{5}\d{4}[A-Za-z]{1}" data-parsley-pattern-message="Enter valid PAN No." readonly>
                      </div>
              <!--<div class="col-12 col-md-4 c_hidden">-->
              <!--  <label for="adharno">Aadhar Card No</label>-->
              <!--  <input type="text" class="form-control" name="adharno" placeholder="Aadhar Card No" data-parsley-length="[12, 12]" data-parsley-length-message = "Enter valid adhar card no.">-->
              <!--</div>-->
              <!--<div class="col-12 col-md-4">-->
              <!--  <label for="customer_role">Customer Role</label>-->
              <!--  <select class="form-control" name="customer_role" id="customer_role" style="width:100%">-->
                  <!--<option value="Enduser">Enduser</option>-->
              <!--    <option value="Reseller">Reseller</option>-->
              <!--  </select>-->
              <!--</div>-->
              <div class="col-12 col-md-4">
                <label for="crdays">Cr Limit</label>
                <input type="text" class="form-control onlynumber" name="crlimit" placeholder="Cr Limit(Rs)" data-parsley-type="number" value="0">
              </div>
              <div class="col-12 col-md-4">
                <label for="crdays">Cr Days</label>
                <input type="text" class="form-control onlynumber" name="crdays" placeholder="Cr Days" data-parsley-type="number" value="">
              </div>
              <!--<div class="col-12 col-md-4 c_hidden">-->
              <!--  <label for="dl_no1">DL No 1</label>-->
              <!--  <input type="text" name="dl_no1" class="form-control" placeholder="DL No 1" value="">-->
              <!--</div>-->
              <!--<div class="col-12 col-md-4 c_hidden">-->
              <!--  <label for="dl_no2">DL No 2</label>-->
              <!--  <input type="text" name="dl_no2" class="form-control" placeholder="DL No 2" value="">-->
              <!--</div>-->
              <!--<div class="col-12 col-md-4">-->
              <!--    <label for="reseller_price_local">Sell Price</label>-->
              <!--      <select class="form-control js-example-basic-single" name="reseller_price" style="width:100%"> -->
              <!--          <option value="">Select Reseller Price</option>-->
              <!--          <option value="Local">Local</option>-->
              <!--          <option value="Out">Out</option>-->
              <!--      </select>-->
              <!--</div>-->
              <div class="col-12 col-md-4">
                <label for="salesman_id">Select Salesman</label>
                <select class="form-control js-example-basic-single" name="salesman_id" style="width:100%"> 
                  <option value="Regular">Select Salesman</option>
                  <?php 
                  $menQuery = "SELECT id,CONCAT(series_no,' - ',fname,' ',lname) as name FROM `salesman` WHERE pharmacy_id='".$_SESSION['auth']['pharmacy_id']."'";
                  $menRes = mysqli_query($conn, $menQuery);
                  while ($menRow = mysqli_fetch_array($menRes)) {
                      ?>
                  <option <?php echo (isset($ledgerdata['salesman_id']) && $ledgerdata['salesman_id'] == $menRow['id']) ? 'selected' : ''; ?> value="<?php echo $menRow['id']; ?>"><?php echo $menRow['name']; ?></option>
                      <?php
                  }
                  ?>
                </select>
              </div>
              <div class="col-12 col-md-4">
                <label for="rate_id">Select Rate</label>
                <select class="form-control js-example-basic-single" name="rate_id" style="width:100%" data-parsley-errors-container="#error-rate"> 
                    <option value="">Select Rate</option>
                        <?php $getAllRate = getAllRate(); ?>
                        <?php if(isset($getAllRate) && !empty($getAllRate)){ ?>
                            <?php foreach($getAllRate as $ratekey => $ratevalue){ ?>
                                <option value="<?php echo (isset($ratevalue['id'])) ? $ratevalue['id'] : ''; ?>" >
                                    <?php echo (isset($ratevalue['name'])) ? $ratevalue['name'] : ''; ?>
                                </option>
                            <?php } ?>
                        <?php } ?>
                </select>
                <span id="error-rate"></span>
              </div>
              <div class="col-12 col-md-12" style="padding: 14px;">
              <h6 style="font-weight: 600;">Contact Details</h6>
              <hr>
              </div>
              <div class="col-12 col-md-4">
                <label for="mobile">Mobile<span class="text-danger">*</span></label>
                <input type="text" class="form-control onlynumber" name="mobile" placeholder="Mobile" maxlength="10" data-parsley-length="[10, 10]" data-parsley-length-message = "Mobile No should be 10 charatcers long." required>   
              </div>
              <div class="col-12 col-md-4">
                  <label for="phone">Phone No</label>
                  <input type="text" class="form-control onlynumber" name="phone" placeholder="Phone No" value="">
              </div>
              <div class="col-12 col-md-4">
                <label for="email">Email</label>
                <input type="text" class="form-control" name="email" placeholder="Email" parsley-type="email">   
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
                  <select class="js-example-basic-single" style="width:100%" name="country" id="country" required data-parsley-errors-container="#error-country"> 
                          <option value="">Select Country</option>
                          <?php 
                            $countryQuery = "SELECT id, name FROM own_countries order by name ASC";
                            $counteryRes = mysqli_query($conn, $countryQuery);
                          ?>
                          <?php if($counteryRes){ ?>
                            <?php while ($countryRow = mysqli_fetch_array($counteryRes)) { ?>
                              <option value="<?php echo $countryRow['id']; ?>" <?php echo (isset($countryRow['id']) && $countryRow['id'] == 101) ? 'selected' : ''; ?> ><?php echo $countryRow['name']; ?></option>
                            <?php } ?>
                          <?php } ?>
                  </select>
                  <span id="error-country"></span>
              </div>
              <div class="col-12 col-md-4">
                <label for="state">Select State <span class="text-danger">*</span></label>
                  <select class="js-example-basic-single" style="width:100%" name="state" id="state" required data-parsley-errors-container="#error-state"> 
                         <option value="">Select State</option>
                         <?php 
                          $stateQuery = "SELECT id, name FROM own_states WHERE country_id = '101' order by name ASC";
                          $stateRes = mysqli_query($conn, $stateQuery);
                        ?>
                        <?php if($stateRes){ ?>
                            <?php while ($stateRow = mysqli_fetch_array($stateRes)) { ?>
                                <option value="<?php echo $stateRow['id']; ?>" <?php echo (isset($stateRow['id']) && $stateRow['id'] == 12) ? 'selected' : ''; ?> ><?php echo $stateRow['name']; ?></option>
                            <?php } ?>
                        <?php } ?>
                  </select>
                  <span id="error-state"></span>
              </div>

              <div class="col-12 col-md-4">
                <label for="district">District</label>
                <input type="text" class="form-control onlyalphabet" name="district" placeholder="District">   
              </div>

              <div class="col-12 col-md-4">
                <label for="city">Select City <span class="text-danger">*</span></label>
                  <select class="js-example-basic-single" style="width:100%" name="city" id="city" required data-parsley-errors-container="#error-city"> 
                         <option value="">Select City</option>
                         <?php 
                            $cityQuery = "SELECT id, name FROM own_cities WHERE state_id = '12' order by name ASC";
                            $cityRes = mysqli_query($conn, $cityQuery);
                        ?>
                        <?php if($cityRes){ ?>
                            <?php while ($cityRow = mysqli_fetch_array($cityRes)) { ?>
                                <option value="<?php echo $cityRow['id']; ?>"><?php echo $cityRow['name']; ?></option>
                            <?php } ?>
                        <?php } ?>
                  </select>
                  <span id="error-city"></span>
              </div>
              <div class="col-12 col-md-4">
                  <label for="pin_code">Pincode</label>
                  <input type="text" class="form-control onlynumber" maxlength="6" name="pincode"  placeholder="Pincode">
              </div>
              <div class="col-12 col-md-4">
                  <label for="faxno">Fax no</label>
                  <input type="text" class="form-control" name="faxno" value="" placeholder="Fax no">
              </div>
              
              <div class="col-12 col-md-3">
                          <label for="Area">Area Name</label>
                          <select class="js-example-basic-single" id="Area" name="area_name" style="width:100%" data-parsley-errors-container="#error-ven-area" >
                              <option value="">Select Area Name</option>
                              <?php
                            echo  $query = "SELECT * FROM `area_master` WHERE pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."'";
                              $result = mysqli_query($conn,$query);
                              while($row = mysqli_fetch_array($result)){
                                  ?>
                              <option value="<?php echo $row['id']; ?>"><?php echo $row['area_name']; ?></option>
                                  <?php
                              }
                              ?>
                          </select>
                          <span id="error-ven-area"></span>
                        </div>
                        <div class="col-12 col-md-1">
                          <button type="button" data-target="#addarea-model" data-toggle="modal" class="btn btn-outline-primary btn-sm pull-right" style="margin-top: 30px;"><i class="mdi mdi-plus"></i></button>
                        </div>
                        
              <!--<div class="col-12 col-md-12" style="padding: 14px;">-->
              <!--<h6 style="font-weight: 600;">Bank Details</h6>-->
              <!--<hr>-->
              <!--</div>-->
              <!--<div class="col-12 col-md-4 c_hidden">-->
              <!--  <label for="bank_name">Bank Name</label>-->
              <!--  <input type="text" class="form-control" name="bank_name" placeholder="Bank Name" value="">-->
              <!--</div>-->
              <!--<div class="col-12 col-md-4 c_hidden">-->
              <!--  <label for="bank_ac_no">Bank A/c No</label>-->
              <!--  <input type="text" class="form-control" name="bank_ac_no" placeholder="Bank A/c No" value="">-->
              <!--</div>-->
              <!--<div class="col-12 col-md-4 c_hidden">-->
              <!--  <label for="branch_name">Branch Name</label>-->
              <!--  <input type="text" class="form-control" name="branch_name" placeholder="Branch Name" value="">-->
              <!--</div>-->

              <!--<div class="col-12 col-md-4 c_hidden">-->
              <!--  <label for="ifsc_code">IFSC Code</label>-->
              <!--  <input type="text" class="form-control" name="ifsc_code" placeholder="IFSC Code" value="">-->
              <!--</div>-->
              
            </div>
          </div>
          
          <div class="modal-footer row">
            <div class="col-md-12">
              <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success pull-right" id="btn-addcustomer">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
</div>