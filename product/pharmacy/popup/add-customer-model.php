<style type="text/css">
  .c_hidden{display: none;}
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
            <span id="addcustomer-errormsg"></span>
            <div class="form-group row">
                      
                      
                      <div class="col-12 col-md-4">
                        <label for="name">Company Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Company Name" >   
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="companyname">Person Name<span class="text-danger">*</span></label>
                        <div class="input-group mb-3">
                            <!--<div class="input-group-prepend" style="border: 1px solid #dadada;">
                                <select class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" name="persional_title">
                                <?php 
                                foreach ($leger_tital as $key => $value) {
                                ?>
                                  <option <?php echo (isset($ledgerdata['persional_title']) && $ledgerdata['persional_title'] == $value) ? 'selected' : ''; ?> value="<?php echo $value; ?>" ><?php echo $value; ?></option>
                                <?php
                                }
                                ?>
                                </select>
                            </div>-->
                            <input type="text" class="form-control" id="companyname" name="companyname" placeholder="Person Name" required>   
                        </div>
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="designation">Designation</label>
                        <select class="form-control" name="designation" style="width:100%" data-parsley-errors-container="#error-ven-designation" required>
                            <option value="">Select Designation</option>
                            <?php foreach ($designation as $key => $value) { ?>
                            <option value="<?php echo $value; ?>" ><?php echo $value; ?></option>
                            <?php } ?> 
                        </select>
                        <span id="error-ven-designation"></span>
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="opening_balance">Opening Balance</label>
                        <input type="text" class="form-control onlynumber" name="opening_balance" placeholder="Opening Balance" >   
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="opening_balance_type">Opening Balance Type</label>
                          <select class="form-control" style="width:100%" name="opening_balance_type" data-parsley-errors-container="#opening_balance_type" required> 
                                  <option value="DB">DB</option>
                                  <option value="CR">CR</option>
                          </select>
                          <span id="opening_balance_type"></span>
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="under">Under <span class="text-danger">*</span></label>
                        <select class="form-control" name="under" style="width:100%" data-parsley-errors-container="#error-ven-Under" required>
                          <option value="">Select Under Group</option>
                          <option value="1">Trading A/C</option>
                          <option value="2">P &amp; L A/C</option>
                          <option value="3">Balance Sheet</option>
                        </select>
                        <span id="error-ven-Under"></span>
                      </div>

                       <div class="col-12 col-md-4">
                        <label for="customer_type">Customer Type </label>
                        <select class="form-control customer_type" name="customer_type" style="width:100%" data-parsley-errors-container="#error-ven-customer" >
                          <option value="">Select Type</option>
                          <option value="GST_Regular">GST registered- Regular</option>
                          <option value="GST_Composition">GST registered- Composition</option>
                          <option value="GST_unregistered">GST unregistered</option>
                          <option value="Consumer">Consumer</option>
                          <option value="Overseas">Overseas</option>
                          <option value="SEZ">SEZ</option>
                          <option value="Deemed">Deemed exports- EOU's, STP's EHTP's etc</option>
                        </select>
                        <span id="error-ven-customer"></span>
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
                      
                      <div class="col-12 col-md-4">
                        <label for="crlimit">Cr Limits</label>
                        <input type="text" name="crlimit" class="form-control" placeholder="Cr Limits (Rs.)" value="" >
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="crdays">Cr Days</label>
                        <input type="text" class="form-control onlynumber" name="crdays" placeholder="Cr Days" data-parsley-type="number" value="" >
                      </div>

                    </div>

                    <br>
                    <h5 class="card-title">Contact Details</h5>
                    <hr>

                    <div class="form-group row">
                      <div class="col-12 col-md-4">
                        <label for="mobile">Mobile<span class="text-danger">*</span></label>
                        <input type="text" class="form-control onlynumber" name="mobile" placeholder="Mobile" maxlength="10" data-parsley-length="[10, 10]" data-parsley-length-message = "Mobile No should be 10 charatcers long." required>   
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="email">Email </label>
                        <input type="text" class="form-control" name="email" placeholder="Email" parsley-type="email" >   
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="phone">Phone No</label>
                        <input type="text" class="form-control" name="phone" placeholder="Phone No" >   
                      </div>
                      
                      <div class="col-12 col-md-4">
                        <label for="addressline1">Address</label>
                        <input type="text" class="form-control" name="addressline1" placeholder="Address Line 1" >   
                      </div>

                      <div class="col-12 col-md-4">
                        <br>
                        <input type="text" class="form-control" name="addressline2" placeholder="Address Line 2" >   
                      </div>

                      <div class="col-12 col-md-4">
                        <br>
                        <input type="text" class="form-control" name="addressline3" placeholder="Address Line 3" >   
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="country">Select Country <span class="text-danger">*</span></label>
                          <select class="js-example-basic-single" style="width:100%" name="country" id="country" data-parsley-errors-container="#error-country" required> 
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
                          <select class="js-example-basic-single" style="width:100%" name="state" id="state" data-parsley-errors-container="#error-ven-state" required> 
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
                          <span id="error-ven-state"></span>
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="district">District<span class="text-danger">*</span></label>
                        <input type="text" class="form-control onlyalphabet" name="district" placeholder="District">   
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="city">Select City <span class="text-danger">*</span></label>
                          <select class="js-example-basic-single" style="width:100%" name="city" id="city" data-parsley-errors-container="#error-ven-city" required> 
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
                          <span id="error-ven-city"></span>
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="pincode">Pincode</label>
                        <input type="text" class="form-control onlynumber" maxlength="6" name="pincode"placeholder="Pincode" >
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="faxno">Fax no</label>
                        <input type="text" class="form-control" name="faxno" placeholder="Fax no" >
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
                    </div>

                    <!--<br>-->
                    <!--<h5 class="card-title">Bank Details</h5>-->
                    <!--<hr>-->

                    <!--<div class="form-group row">-->
                    <!--  <div class="col-12 col-md-4">-->
                    <!--    <label for="bank_name">Bank Name</label>-->
                    <!--    <input type="text" class="form-control" name="bank_name" placeholder="Bank Name" value="" required>-->
                    <!--  </div>-->

                    <!--  <div class="col-12 col-md-4">-->
                    <!--    <label for="bank_ac_no">Bank A/c No</label>-->
                    <!--    <input type="text" class="form-control onlynumber" name="bank_ac_no" placeholder="Bank A/c No" value="" required>-->
                    <!--  </div>-->

                    <!--  <div class="col-12 col-md-4">-->
                    <!--    <label for="branch_name">Branch Name</label>-->
                    <!--    <input type="text" class="form-control" name="branch_name" placeholder="Branch Name" value="" required>-->
                    <!--  </div>-->

                    <!--  <div class="col-12 col-md-4">-->
                    <!--    <label for="ifsc_code">IFSC Code</label>-->
                    <!--    <input type="text" class="form-control" name="ifsc_code" placeholder="IFSC Code" value="" required>-->
                    <!--  </div>-->
                    <!--</div>-->
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