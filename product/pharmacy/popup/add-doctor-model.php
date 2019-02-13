<div class="modal fade" id="add_doctor_model" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
      
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel">Add New Doctor</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="add-doctor-form" autocomplete="off">
          <div class="modal-body">
            <span id="adddoctor-errormsg"></span>
            <div class="form-group row">
              <div class="col-12 col-md-4">
                <label for="name">Doctor Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control onlyalphabet" name="doctor_name" placeholder="Doctor Name" required>   
              </div>
              
              <div class="col-12 col-md-4">
                <label for="mobile">Mobile No</label>
                <input type="text" class="form-control onlynumber" name="mobile_no" placeholder="Mobile" maxlength="10" data-parsley-length="[10, 10]" data-parsley-length-message = "Mobile No should be 10 charatcers long.">   
              </div>
              
              <div class="col-12 col-md-4">
                <label for="email">Doctor Commition</label>
                <input type="text" class="form-control onlynumber" name="doctor_commossion" placeholder="Commission %" maxlength="3">   
              </div>
              
              <div class="col-12 col-md-4">
                <label for="country">Select Country <span class="text-danger">*</span></label>
                  <select class="js-example-basic-single" style="width:100%" name="country" id="do_country" data-parsley-errors-container="#error_do_country" required> 
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
                  <span id="error_do_country"></span>
              </div>

              <div class="col-12 col-md-4">
                <label for="state">Select State <span class="text-danger">*</span></label>
                  <select class="js-example-basic-single" style="width:100%" name="state" id="do_state" data-parsley-errors-container="#error_do_state" required> 
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
                  <span id="error_do_state"></span>
              </div>

              <div class="col-12 col-md-4">
                <label for="city">Select City <span class="text-danger">*</span></label>
                  <select class="js-example-basic-single" style="width:100%" name="city" id="do_city" data-parsley-errors-container="#error_do_city" required> 
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
                  <span id="error_do_city"></span>
              </div>

              <div class="col-12 col-md-4">
                <label for="opening_balance">Address</label>
                <input type="text" class="form-control" name="address" placeholder="Address">   
              </div>
              
              <div class="col-12 col-md-4">
                <label for="opening_balance">Pin Code</label>
                <input type="text" class="form-control onlynumber" name="pincode" placeholder="Pin Code" maxlength="6">   
              </div>

              <!--<div class="col-12 col-md-4">
                <label for="opening_balance">GST No</label>
                <input type="text" class="form-control" name="gst" placeholder="GST No" data-parsley-pattern="^([0]{1}[1-9]{1}|[1-2]{1}[0-9]{1}|[3]{1}[0-7]{1})([a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}[1-9a-zA-Z]{1}[zZ]{1}[0-9a-zA-Z]{1})+$" data-parsley-pattern-message="Enter valid GST No." maxlength="15">   
              </div>-->
              
            </div>
          </div>
          
          <div class="modal-footer row">
            <div class="col-md-12">
              <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success pull-right" id="btn-adddoctor">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
</div>