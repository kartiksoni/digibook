<style>
    .ui-autocomplete { z-index:2147483647; }
</style>
<div class="modal fade" id="purchase-addproductmodel" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <?php $popupPharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : ''; ?>
      <form id="add-product" autocomplete="off">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel">Add new Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span><style>
    .ui-autocomplete { z-index:2147483647; }
</style>
<div class="modal fade" id="purchase-addproductmodel" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <?php $popupPharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : ''; ?>
      <form id="add-product" autocomplete="off">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalLabel">Add new Product</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          </div>
        
          <div class="modal-body">
              <span id="addproduct-errormsg"></span>

              <div class="form-group row">
                <div class="col-12 col-md-4">
                  <label for="product_name">Product Name<span class="text-danger">*</span></label>
                  <input type="text" name="product_name" class="form-control" required id=" popup_product_name" placeholder="Product Name">
                  <small class="product-error text-danger"></small>
                </div>
                      
                <div class="col-12 col-md-4">
                    <label for="popup_mfg_company">MFG. Company</label>
                    <input type="text" name="mfg_company"  class="form-control" id="popup_mfg_company" placeholder="MFG. Company">
                </div>
                        
                <div class="col-12 col-md-4">
                  <label for="unit">Bill Print View</label>
                  <input type="text" name="bill_print_view" class="form-control" id="unit" placeholder="Bill Print View" maxlength="5">
                </div>
              </div>

              <div class="form-group row">

                <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Batch No</label>
                    <input type="text" name="batch_no" class="form-control" id="exampleInputName1" placeholder="Batch No">
                </div>
                        
                <div class="col-12 col-md-4">
                    <label for="popup_serial_no">Serial No.</label>
                    <input type="text" name="serial_no" class="form-control" id="popup_serial_no" placeholder="Serial No">
                </div>
                        
                <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Expiry Date</label>
                    <div class="input-group date datepicker">
                    
                    <input name="ex_date" type="text" class="form-control border" placeholder="dd/mm/yyyy" >
                    <span class="input-group-addon input-group-append border-left">
                      <span class="mdi mdi-calendar input-group-text"></span>
                    </span>
                  </div>
                </div>

            </div>

            <div class="form-group row">
                        
              <div class="col-12 col-md-4">
                  <label for="opening_qty">Opening Qty</label>
                  <input type="text" value="" name="opening_qty" class="form-control opening_qty onlynumber" id="opening_qty" placeholder="Opening Qty">
              </div>
              
              <div class="col-12 col-md-4">
                  <label for="popup_opening_qty_godown">Opening Qty in Godown</label>
                  <input type="text" name="opening_qty_godown" value="" class="form-control onlynumber" id="popup_opening_qty_godown" placeholder="Opening Qty in Godown">
              </div>
              
              <div class="col-12 col-md-4">
                  <label for="popup_mrp">MRP</label>
                  <input type="text" value="" name="mrp" class="form-control onlynumber" id="popup_mrp" placeholder="MRP">
              </div>
                
            </div>

            <div class="form-group row">
                <div class="col-12 col-md-4">
                    <label for="give_mrp">Give a New MRP</label>
                    <input type="text" value="" name="give_mrp" class="form-control onlynumber" id="give_mrp" placeholder="Give a New MRP">
                </div>
                
                <div class="col-12 col-md-4">
                  <label for="inward_rate">Inward Rate<span class="text-danger">*</span></label>
                  <input type="text" required="" name="inward_rate" value="" class="form-control onlynumber inward_rate" id="inward_rate" placeholder="INWARD Rate">
                </div>
                 <div class="col-12 col-md-3">
                    <label for="popup_gst_id">GST<span class="text-danger">*</span></label>
                    <select name="gst_id" required="" class="js-example-basic-single popup_gst_id" id="popup_gst_id" style="width:100%" data-parsley-errors-container="#error-gst-type"> 
                      <option value="">Select GST</option>
                      <?php 
                        $productTypeQry = "SELECT * FROM `gst_master` WHERE status='1' AND pharmacy_id = '".$p_id."' OR edit_status='1' ORDER BY id DESC";
                        $Product = mysqli_query($conn,$productTypeQry);
                        while($product_type = mysqli_fetch_assoc($Product)){
                      ?>
                        <option value="<?php echo $product_type['id']; ?>"><?php echo $product_type['gst_name']; ?></option>
                      <?php } ?>
                    </select>
                    <span id="error-gst-type"></span>
                  </div>
                  <div class="col-12 col-md-1">
                    <button type="button" data-target="#addgst-model" data-toggle="modal" class="btn btn-outline-primary btn-sm pull-right" style="margin-top: 30px;"><i class="mdi mdi-plus"></i></button>
                 </div>
            </div>

            <div class="form-group row gst_show" style="display:none;">
                <div class="col-12 col-md-4">
                    <label for="igst">IGST</label>
                    <input type="text" readonly name="igst"  value="" class="form-control onlynumber igst" id="igst" placeholder="IGST">
                </div>

                <div class="col-12 col-md-4">
                    <label for="cgst">CGST</label>
                    <input type="text" readonly  name="cgst" value="" class="form-control onlynumber cgst" id="cgst" placeholder="CGST">
                </div>
              
                <div class="col-12 col-md-4">
                    <label for="sgst">SGST</label>
                    <input type="text" readonly  name="sgst" value="" class="form-control onlynumber sgst" id="sgst" placeholder="SGST">
                </div>
              
            </div>

            <div class="form-group row">
                <div class="col-12 col-md-4">
                    <label for="rack_no">Rack No</label>
                    <input type="text" name="rack_no" value="<?php echo (isset($data['rack_no'])) ? $data['rack_no'] : '';?>" class="form-control" id="rack_no" placeholder="Rack No">
                </div>
              
                <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Self No</label>
                    <input type="text" name="self_no" value="<?php echo (isset($data['self_no'])) ? $data['self_no'] : '';?>" class="form-control" id="self_no" placeholder="Self No">
                </div>

                <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Box No</label>
                    <input type="text" name="box_no" value="<?php echo (isset($data['box_no'])) ? $data['box_no'] : '';?>" class="form-control" id="box_no" placeholder="Box No">
                </div>
              
            </div>

            <div class="form-group row">
                      
              <div class="col-12 col-md-4">
                <label for="popup_min_qty">Min Qty.</label>
                <input type="text" value="" name="min_qty" class="form-control onlynumber" id="popup_min_qty" placeholder="Min Qty.">
              </div>
              
               <div class="col-12 col-md-4">
                <label for="popup_max_qty">Max Qty.</label>
                <input type="text" value="" name="max_qty" class="form-control onlynumber" id="popup_max_qty" placeholder="Max Qty.">
              </div>
              
              <div class="col-12 col-md-3">
                <label for="company_code1">Company Name</label>
                <select class="js-example-basic-single" id="company_code1" name="company_code" style="width:100%">
                    <option value="">Select Company Name</option>
                    <?php
                    $query = "SELECT * FROM `company_master` WHERE pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."'";
                    $result = mysqli_query($conn,$query);
                    while($row = mysqli_fetch_array($result)){
                        ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                        <?php
                    }
                    ?>
                </select>
              </div>
              <div class="col-12 col-md-1">
                <button type="button" data-target="#addcompany-model" data-toggle="modal" class="btn btn-outline-primary btn-sm pull-right" style="margin-top: 30px;"><i class="mdi mdi-plus"></i></button>
             </div>
              
            </div>

            <div class="form-group row">
                      
                <div class="col-12 col-md-4">
                    <label for="opening_stock">Opening Stock Rs</label>
                    <input type="text" value="" name="opening_stock" readonly="" class="form-control onlynumber opening_stock" id="opening_stock" placeholder="Opening Stock Rs">
                </div>
                      
                <div class="col-12 col-md-3">
                  <label for="popup_unit">Unit / Strip / Pack</label>
                    <select class="js-example-basic-single" id="popup_unit" name="unit" style="width:100%">
                        <option value="">Select Unit</option>
                        <?php
                        $query = "SELECT * FROM `unit_master` WHERE pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."'";
                        $result = mysqli_query($conn,$query);
                        while($row = mysqli_fetch_array($result)){
                            ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['unit_name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-12 col-md-1">
                  <button type="button" data-target="#addunit-model" data-toggle="modal" class="btn btn-outline-primary btn-sm pull-right" style="margin-top: 30px;"><i class="mdi mdi-plus"></i></button>
                </div>
                
                <div class="col-12 col-md-4">
                    <label for="popup_hsn_code">HSN Code<span class="text-danger">*</span></label>
                    <input name="hsn_code" type="text" required="" value="" class="form-control" id="popup_hsn_code" placeholder="HSN Code">
                    <label>Not sure about HSN code ? <a target="_blank" href="http://www.cbic.gov.in/resources//htdocs-cbec/gst/goods-rates-booklet-03July2017.pdf">Look up here</a> </label>
                </div>

            </div>

            <div class="form-group row">
                        
                <div class="col-12 col-md-4 m-t-20 resellerprice-div">
                  <label for="reseller_price_local">Sale Price</label>
                    <div class="row no-gutters">
                      <div class="col-12 col-md-6">
                          <input type="text" value="" name="sale_rate_local" class="form-control onlynumber"  placeholder="Local" data-parsley-type="number">
                        </div>
                       <div class="col-12 col-md-6">
                          <input type="text" value="" name="sale_rate_out" class="form-control onlynumber" placeholder="Out" data-parsley-type="number">
                       </div>
                      </div>
                  </div>
              
                <div class="col-12 col-md-4">
                  <label for="exampleInputName1">Status</label>
                  <div class="row no-gutters">
                  
                      <div class="col">
                        <div class="form-radio">
                        <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" checked>
                        Active
                        </label>
                        </div>
                      </div>
                      
                      <div class="col">
                        <div class="form-radio">
                        <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="status" id="optionsRadios2" value="0">
                        Deactive
                        </label>
                        </div>
                      </div>
                  
                  </div>
                </div>

                <div class="col-12 col-md-4">
                    <label for="exampleInputName1">Discount</label>
                    <div class="row no-gutters">
                    
                        <div class="col">
                          <div class="form-radio">
                          <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="discount" id="options1" value="1" <?php if(isset($data['discount']) && $data['discount'] == "1"){echo "checked";} ?>>
                          Yes
                          </label>
                          </div>
                        </div>
                        
                        <div class="col">
                          <div class="form-radio">
                          <label class="form-check-label">
                          <input type="radio"  class="form-check-input" name="discount" id="options2" value="0" <?php if(isset($_GET['id'])){if(isset($data['discount']) && $data['discount'] == "0"){echo "checked";}  } else{echo "checked";} ?>>
                          No
                          </label>
                          </div>
                        </div>
                    
                    </div>
                  </div>
            </div>

            <div class="form-group row">
                 <div class="col-12 col-md-4" id="per">
                  <label for="discount_per"><span class="text-danger"></span></label>
                  <input type="text" required value="<?php echo (isset($data['discount_per'])) ? $data['discount_per'] : '';?>" name="discount_per" class="form-control onlynumber" id="discount_per" placeholder="%">
                 </div>
            </div>
            <div class="modal-footer row">
              <div class="col-md-12">
                 <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success pull-right" id="btn-addproduct">Save</button>
              </div>
            </div>
        </div>
      </form>
      </div>
  </div>
</div>

      <!-- Add Company model -->
        <?php include "include/addcompanymodel.php"?>

        <!-- Add GST Model -->
        <?php include "include/addgstmodel.php"?>

        <!--  Add Unit Model -->
        <?php include "include/addunitmodel.php"?>