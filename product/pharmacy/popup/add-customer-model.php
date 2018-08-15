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
        <form id="add-customer-form">
          <div class="modal-body">
            <span id="addcustomer-errormsg"></span>
            <div class="form-group row">
              <div class="col-12 col-md-4">
                <label for="name">Customer Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" placeholder="Vendor Name" required>   
              </div>
              
              <div class="col-12 col-md-4">
                <label for="mobile">Mobile</label>
                <input type="text" class="form-control onlynumber" parsley-type="email" name="mobile" placeholder="Mobile" maxlength="10">   
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
                  <select class="js-example-basic-single" style="width:100%" name="city" id="city" required> 
                          <option value="">Select City</option>
                  </select>
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
                <label for="customer_type">Customer Type</label>
                <select class="form-control" name="customer_type" style="width:100%">
                  <option value="Regular">Regular</option>
                  <option value="Unregistered">Unregistered</option>
                  <option value="Composition">Composition</option>
                </select>
              </div>

              <div class="col-12 col-md-4">
                <label for="customer_role">Customer Role</label>
                <select class="form-control" name="customer_role" id="customer_role" style="width:100%">
                  <option value="Enduser">Enduser</option>
                  <option value="Reseller">Reseller</option>
                </select>
              </div>

              <div class="col-12 col-md-4">
                <label for="crdays">Cr Days</label>
                <input type="text" class="form-control onlynumber" name="crdays" placeholder="Cr Days" data-parsley-type="number" value="">
              </div>

              <div class="col-12 col-md-4 c_hidden">
                <label for="adharno">Aadhar Card No</label>
                <input type="text" class="form-control" name="adharno" placeholder="Aadhar Card No">
              </div>

              <div class="col-12 col-md-4 c_hidden">
                <label for="panno">Pan No</label>
                <input type="text" class="form-control" name="panno" placeholder="Pan No" value="">
              </div>

              <div class="col-12 col-md-4 c_hidden">
                <label for="gstno">GST No</label>
                <input type="text" class="form-control" name="gstno" placeholder="GST No" value="">
              </div>

              <div class="col-12 col-md-4 c_hidden">
                <label for="bank_name">Bank Name</label>
                <input type="text" class="form-control" name="bank_name" placeholder="Bank Name" value="">
              </div>

              <div class="col-12 col-md-4 c_hidden">
                <label for="bank_ac_no">Bank A/c No</label>
                <input type="text" class="form-control" name="bank_ac_no" placeholder="Bank A/c No" value="">
              </div>

              <div class="col-12 col-md-4 c_hidden">
                <label for="branch_name">Branch Name</label>
                <input type="text" class="form-control" name="branch_name" placeholder="Branch Name" value="">
              </div>

              <div class="col-12 col-md-4 c_hidden">
                <label for="ifsc_code">IFSC Code</label>
                <input type="text" class="form-control" name="ifsc_code" placeholder="IFSC Code" value="">
              </div>

              <div class="col-12 col-md-4 c_hidden">
                <label for="dl_no1">DL No 1</label>
                <input type="text" name="dl_no1" class="form-control" placeholder="DL No 1" value="">
              </div>

              <div class="col-12 col-md-4 c_hidden">
                <label for="dl_no2">DL No 2</label>
                <input type="text" name="dl_no2" class="form-control" placeholder="DL No 2" value="">
              </div>

              <div class="col-12 col-md-4 c_hidden">
                <label for="reseller_price_local">Reseller Price</label>
                  <div class="row no-gutters">
                    <div class="col-12 col-md-6">
                      <input type="text" name="reseller_price_local" class="form-control onlynumber"  placeholder="Local" data-parsley-type="number">
                    </div>
                    <div class="col-12 col-md-6">
                      <input type="text" name="reseller_price_out" class="form-control onlynumber" placeholder="Out" data-parsley-type="number">
                    </div>
                  </div>
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
              <button type="submit" class="btn btn-success pull-right" id="btn-addcustomer">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
</div>