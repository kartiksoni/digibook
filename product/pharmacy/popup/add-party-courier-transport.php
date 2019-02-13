<style type="text/css">
.c_hidden{display: none;}
</style>
<div class="modal fade" id="add_new_partyModel" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">Add New Party</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="add-new-party" autocomplete="off">
        <div class="modal-body">
          <span id="addparty-errormsg"></span>

          <div class="form-group row">

            <div class="col-12 col-md-4">
             <label for="exampleInputName1">Name<span class="text-danger">*</span></label>
             <input type="text" name="name" class="form-control" id="exampleInputName1" placeholder="Name" required="" value="<?php if(isset($_REQUEST['id'])){echo $courierrecord['name'];}?>">
           </div>

           <div class="col-12 col-md-4">
             <label for="exampleInputName1">Mobile No<span class="text-danger">*</span></label>
             <input type="text" name="mobile_no" class="form-control onlynumber" id="exampleInputName1" placeholder="Mobile No" data-parsley-type="number" maxlength = 10 data-parsley-length="[10, 10]" data-parsley-length-message = "Mobile No should be 10 charatcers long." required="" value="<?php if(isset($_REQUEST['id'])){echo 
              $courierrecord['mobile_no'];}?>">
            </div>
          </div>

          <div class="form-group row">     

            <div class="col-12 col-md-4">
             <label for="exampleInputName1">Address<span class="text-danger">*</span></label>
             <input type="text" name="address" class="form-control" id="exampleInputName1" placeholder="Address" required="" value="<?php if(isset($_REQUEST['id'])){echo $courierrecord['address'];}?>">
           </div>

           <div class="col-12 col-md-4">
             <input type="text" name="address_line1"class="form-control mt-30" id="exampleInputName1" placeholder="Address line 1" value="<?php if(isset($_REQUEST['id'])){echo $courierrecord['address_line1'];}?>">
           </div>

           <div class="col-12 col-md-4">
             <input type="text" name="address_line2" class="form-control mt-30" id="exampleInputName1" placeholder="Address line 2" value="<?php if(isset($_REQUEST['id'])){echo $courierrecord['address_line2'];}?>">
           </div>

         </div>

         <div class="form-group row">       

          <div class="col-12 col-md-4">
           <label for="exampleInputName1">State<span class="text-danger">*</span></label>
           <select class="js-example-basic-single" name="state" id="state" style="width:100%" required="" data-parsley-errors-container="#error-container"> 
            <option value="">Select State</option>
            <?php
            if(!isset($_REQUEST['id'])){
              $courierrecord['state'] = '12';
            }
            ?>        
            <?php 
            $stateqry = "SELECT id, name FROM `own_states` WHERE country_id = 101";
            $staterun = mysqli_query($conn, $stateqry);

            if($staterun){
              while($statedata = mysqli_fetch_assoc($staterun)){
                ?>  
                <option value="<?php echo $statedata['id'];?>" <?php echo (isset($courierrecord['state']) && $courierrecord['state'] == $statedata['id']) ? 'selected' : ''; ?>><?php echo $statedata['name'];?> </option>
              <?php } } ?>
            </select>
            <span id="error-container"></span>        
          </div>

          <div class="col-12 col-md-4">
           <label for="exampleInputName1">City<span class="text-danger">*</span></label>
           <select name="city"  class="js-example-basic-single" id="city" style="width:100%" required="" data-parsley-errors-container="#error">     
            <option value="">Select City</option>
            <?php 
            if(isset($courierrecord['state']) && $courierrecord['state'] != ''){

              $allcityqry = "SELECT id, name FROM own_cities WHERE state_id = '".$courierrecord['state']."' order by name ASC";
              $allcityrun = mysqli_query($conn, $allcityqry);

              if($allcityrun){
                while($allcitydata = mysqli_fetch_assoc($allcityrun)){?>

                  <option value="<?php echo $allcitydata['id']; ?>" <?php echo (isset($courierrecord['city']) && $courierrecord['city'] == $allcitydata['id']) ? 'selected' : ''; ?> > <?php echo $allcitydata['name']; ?>
                </option>
              <?php } ?>
            <?php } ?>
          <?php } ?>
        </select>  
        <span id="error"></span>
      </div>

    </div>

    <div class="form-group row"> 

      <div class="col-12 col-md-4">
       <label for="exampleInputName1">GST No</label>
       <input type="text" name="gst_no" class="form-control" id="exampleInputName1" placeholder="GST No" value="<?php if(isset($_REQUEST['id'])){echo $courierrecord['gst_no'];}?>" data-parsley-pattern="^([0]{1}[1-9]{1}|[1-2]{1}[0-9]{1}|[3]{1}[0-7]{1})([a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}[1-9a-zA-Z]{1}[zZ]{1}[0-9a-zA-Z]{1})+$" data-parsley-pattern-message="Enter valid GST No." maxlength="15">
     </div>
   
   <input type="hidden" name="status" value="1" >
   </div>


 </div>

 <div class="modal-footer row">
  <div class="col-md-12">
    <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-success pull-right" id="btn-addparty">Save</button>
  </div>
</div>
</form>
</div>
</div>
</div>