<style>
    .ui-autocomplete { z-index:2147483647; }
</style>
<div class="modal fade" id="purchase-addproductmodel" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
      <?php $p_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : ''; ?>
      <form id="add-product" autocomplete="off">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel">Add new Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
          <div class="modal-body">

              <span id="addproduct-errormsg" class="product-error"></span>
              <div class="form-group row">
                      
                <div class="col-12 col-md-4">
                  <label for="product_name">Product Name<span class="text-danger">*</span></label>
                  <input type="text" name="product_name" class="form-control" id="popup_product_name" required  placeholder="Product Name">
                </div>
                      
                <div class="col-12 col-md-4">
                  <label for="generic_name">Generic Name</label>
                <input type="text" name="generic_name" class="form-control" value="<?php echo (isset($data['generic_name'])) ? $data['generic_name'] : '';?>" id="popup_generic_name" placeholder="Generic Name ">
                </div>
                      
              </div>
                    
              <div class="form-group row">
                  
                <div class="col-12 col-md-4">
                    <label for="mfg_company">MFG. Company<span class="text-danger">*</span></label>
                    <input type="text" name="mfg_company" required class="form-control" id="popup_mfg_company" placeholder="MFG. Company">
                </div>
                   
                <div class="col-12 col-md-4">
                    <label for="schedule_cat">Schedule Category</label>
                    <select class="js-example-basic-single" name="schedule_cat" style="width:100%" id="popup_schedule_cat">
                        <option value="">Select Schedule Category</option>
                        <?php 
                            $SheduleCategory = getSheduleCategory();
                            if(isset($SheduleCategory) && !empty($SheduleCategory)){
                                foreach($SheduleCategory as $key => $value){
                        ?>
                        <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                        <?php
                          } 
                             }
                        ?>
                    </select>
                </div>
                    
                <div class="col-12 col-md-3">

                  <label for="product_type">Product  Type<span class="text-danger">*</span></label>
                  <select name="product_type" required="" class="js-example-basic-single" style="width:100%" data-parsley-errors-container="#error-product-type" id="popup_product_type"> 
                    <option value="">Select Product Type</option>
                    <?php 
                      $data['product_type'] = (isset($data['product_type'])) ? $data['product_type'] : '';
                      $productTypeQry = "SELECT * FROM `product_type` WHERE status='1' AND pharmacy_id = '".$p_id."'";
                      $Product = mysqli_query($conn,$productTypeQry);
                      while($product_type = mysqli_fetch_assoc($Product)){
                    ?>
                      <option value="<?php echo $product_type['id']; ?>"><?php echo $product_type['product_type']; ?></option>
                    <?php } ?>
                  </select>
                  <span id="error-product-type"></span>
                </div>
              <div class="col-12 col-md-1">
                <button type="button" data-target="#addproducttype-model" data-toggle="modal" class="btn btn-outline-primary btn-sm pull-right" style="margin-top: 30px;"><i class="mdi mdi-plus"></i></button>
              </div>
              
            </div>
                    
              <div class="form-group row">
                        
                <div class="col-12 col-md-3">

                  <label for="product_cat">Product Category<span class="text-danger">*</span></label>
                  <select class="js-example-basic-single" required="" style="width:100%" name="product_cat" data-parsley-errors-container="#error-product-cat" id="popup_product_cat"> 

                    <option value="">Select Product Category</option>
                    <?php 
                    $data['product_cat'] = (isset($data['product_cat'])) ? $data['product_cat'] : '';
                    $productCatQry = "SELECT * FROM `product_category` WHERE status='1' AND pharmacy_id = '".$p_id."'";
                    $Category = mysqli_query($conn,$productCatQry);
                    while($product_category = mysqli_fetch_assoc($Category)){
                    ?>
                      <option value="<?php echo $product_category['id']; ?>"><?php echo $product_category['product_cat']; ?></option>
                    <?php } ?>

                  </select>
                  <span id="error-product-cat"></span>
                </div>
                <div class="col-12 col-md-1">
                  <button type="button" data-target="#addproductcategory-model" data-toggle="modal" class="btn btn-outline-primary btn-sm pull-right" style="margin-top: 30px;"><i class="mdi mdi-plus"></i></button>
                </div>
                        
                <div class="col-12 col-md-4">
                  <label for="sub_cat">Sub Category</label>
                  <select name="sub_cat" class="js-example-basic-single" style="width:100%" id="popup_sub_cat">
                        <option value="">Select Sub Category</option>
                        <?php $data['sub_cat'] = (isset($data['sub_cat'])) ? $data['sub_cat'] : '';?>
                        <option value="Sub Cat1">Sub Cat1</option>
                        <option value="Sub Cat2">Sub Cat2</option>
                        <option value="Sub Cat3">Sub Cat3</option>
                    </select>
                </div>
                        
                <div class="col-12 col-md-4">
                  <label for="hsn_code">HSN Code<span class="text-danger">*</span></label>
                  <input name="hsn_code" type="text" required=""  class="form-control" id="popup_hsn_code" placeholder="HSN Code">
                </div>  
                        
              </div>
                    
              <div class="form-group row">
                <div class="col-12 col-md-4">
                  <label for="batch_no">Batch No</label>
                  <input type="text" <?php if(isset($_REQUEST['id'])){echo"readonly";} ?> name="batch_no"  class="form-control" id="popup_batch_no" placeholder="Batch No">
                </div>
                        
                <div class="col-12 col-md-4">
                  <label for="ex_date">Expiry Date</label>
                  <div class="input-group date datepicker">
                    <?php 
                        if(isset($_GET['id']) && $_GET['id'] != ''){
                            $exdate_val = (isset($data['ex_date']) && $data['ex_date'] != '') ? date("d/m/Y",strtotime(str_replace("-","/",$data['ex_date']))) : '';
                        }else{
                            $exdate_val = date('d/m/Y');
                        }
                    ?>
                    <input name="ex_date" <?php if(isset($_REQUEST['id'])){echo"disabled";} ?> data-inputmask="'alias': 'date'" value="<?php echo (isset($exdate_val)) ? $exdate_val : ''; ?>" type="text" class="form-control border" placeholder="dd/mm/yyyy" >
                    <span class="input-group-addon input-group-append border-left">
                      <span class="mdi mdi-calendar input-group-text"></span>
                    </span>
                  </div>
                </div>
                        
                <div class="col-12 col-md-4">
                  <label for="opening_qty">Opening Qty</label>
                  <input type="text" name="opening_qty" class="form-control opening_qty onlynumber" id="popup_opening_qty" placeholder="Opening Qty">
                </div>  
                    
              </div>
                    
              <div class="form-group row">
                  
                  <div class="col-12 col-md-4">
                    <label for="opening_qty_godown">Opening Qty in Godown</label>
                    <input type="text" name="opening_qty_godown" class="form-control onlynumber" id="popup_opening_qty_godown" placeholder="Opening Qty in Godown">
                  </div>
                  
                  <div class="col-12 col-md-4">
                    <label for="mrp">MRP</label>
                    <input type="text"  name="mrp" class="form-control onlynumber" id="popup_mrp" placeholder="MRP">
                  </div>
                 
                  <div class="col-12 col-md-4">
                    <label for="give_mrp">Give a New MRP</label>
                    <input type="text" name="give_mrp" class="form-control onlynumber" id="popup_give_mrp" placeholder="Give a New MRP">
                  </div>
              
              </div>
                    
              <div class="form-group row">

                <div class="col-12 col-md-4">
                  <label for="inward_rate">Inward Rate<span class="text-danger">*</span></label>
                  <input type="text" required="" name="inward_rate" class="form-control onlynumber inward_rate" id="popup_inward_rate" placeholder="INWARD Rate">
                </div>

                <!--<div class="col-12 col-md-4">
                    <label for="rack_no">Rack No</label>
                    <input type="text" name="rack_no" class="form-control" id="popup_rack_no" placeholder="Rack No">
                </div>!-->
                
                <div class="col-12 col-md-3">
                  <label for="company_code1">Company</label>
                  <select class="js-example-basic-single company_code" id="popup_company_code" name="company_code" style="width:100%">
                      <option value="">Select Company</option>
                      <?php
                      $query = "SELECT * FROM `company_master` WHERE pharmacy_id = '".$p_id."'";
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


                <div class="col-12 col-md-3">
                  <label for="gst_id">GST<span class="text-danger">*</span></label>
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

              <div class="form-group row gstdiv" style="<?php echo (isset($data['gst_id']) && $data['gst_id'] != '') ? '' : 'display:none;'; ?>">
                  <div class="col-12 col-md-4">
                      <label for="igst">IGST</label>
                      <input type="text" readonly name="igst"  class="form-control onlynumber igst" id="popup_igst" placeholder="IGST">
                  </div>

                  <div class="col-12 col-md-4">
                      <label for="cgst">CGST</label>
                      <input type="text" readonly  name="cgst" class="form-control onlynumber cgst" id="popup_cgst" placeholder="CGST">
                  </div>
                
                  <div class="col-12 col-md-4">
                      <label for="sgst">SGST</label>
                      <input type="text" readonly  name="sgst"  class="form-control onlynumber sgst" id="popup_sgst" placeholder="SGST">
                  </div>
              </div>
                    
              <div class="form-group row">
                  
                <div class="col-12 col-md-4">
                    <label for="rack_no">Rack No</label>
                    <input type="text" name="rack_no" class="form-control" id="popup_rack_no" placeholder="Rack No">
                </div>

                <div class="col-12 col-md-4">
                  <label for="self_no">Self No</label>
                  <input type="text" name="self_no" class="form-control" id="popup_self_no" placeholder="Self No">
                </div>

                <div class="col-12 col-md-4">
                  <label for="box_no">Box No</label>
                  <input type="text" name="box_no" class="form-control" id="popup_box_no" placeholder="Box No">
                </div>

                <!--<div class="col-12 col-md-3">
                  <label for="company_code1">Company</label>
                  <select class="js-example-basic-single company_code" id="popup_company_code" name="company_code" style="width:100%">
                      <option value="">Select Company</option>
                      <?php
                      $query = "SELECT * FROM `company_master` WHERE pharmacy_id = '".$p_id."'";
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
                </div>!--->

              </div>
                    
              <div class="form-group row">
                      
                <div class="col-12 col-md-4">
                  <label for="opening_stock">Opening Stock Rs</label>
                  <input type="text" name="opening_stock" readonly="" class="form-control onlynumber opening_stock" id="popup_opening_stock" placeholder="Opening Stock Rs">
                </div>
                      
                <div class="col-12 col-md-3">
                  <label for="unit">Unit / Strip / Pack</label>
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
                  <label for="min_qty">Min Qty.</label>
                  <input type="text" name="min_qty" class="form-control onlynumber" id="popup_min_qty" placeholder="Min Qty.">
                </div>
                
              </div>
                    
              <div class="form-group row">
                  
                <div class="col-12 col-md-4">
                  <label for="ratio">Ratio<span class="text-danger">*</span></label>
                  <input type="text" required name="ratio" class="form-control onlynumber" id="popup_ratio" value="1" placeholder="Ratio.">
                </div>
                 
                <div class="col-12 col-md-4">
                  <label for="max_qty">Max Qty.</label>
                  <input type="text" name="max_qty" class="form-control onlynumber" id="popup_max_qty" placeholder="Max Qty.">
                </div>
                
                <div class="col-12 col-md-4">
                    <label for="status">Status</label>
                    <?php $data['status'] = (isset($data['status'])) ? $data['status'] : '';?>
                  
                    <div class="row no-gutters">
                    
                        <div class="col">
                          <div class="form-radio">
                          <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="status" id="popup_optionsRadios1" value="1" checked <?php if(isset($_GET['id'])){if($data['status'] == "1"){echo "checked";}  }else{echo"checked";} ?>>
                          Active
                          </label>
                          </div>
                        </div>
                        
                        <div class="col">
                          <div class="form-radio">
                          <label class="form-check-label">
                          <input type="radio" <?php if($data['status'] == "0"){echo "checked";} ?> class="form-check-input" name="status" id="popup_optionsRadios2" value="0">
                          Deactive
                          </label>
                          </div>
                        </div>
                    
                    </div>
                </div>

              </div>
                    
              <div class="form-group row">

                <div class="col-12 col-md-4">
                  <label for="discount">Discount</label>
                  <div class="row no-gutters">
                  
                      <div class="col">
                        <div class="form-radio">
                        <label class="form-check-label">
                        <input type="radio" class="form-check-input discount" name="discount" id="options1" value="1" data-parsley-multiple="discount">
                        Yes
                        <i class="input-helper"></i></label>
                        </div>
                      </div>
                      
                      <div class="col">
                        <div class="form-radio">
                        <label class="form-check-label">
                        <input type="radio" class="form-check-input discount" name="discount" id="options2" value="0" checked="" data-parsley-multiple="discount">
                        No
                        <i class="input-helper"></i></label>
                        </div>
                      </div>
                  
                  </div>
                </div>
                
                <div class="col-12 col-md-4" id="per" style="display:none;">
                    <label for="discount_per">Discount(%)</label>
                    <input type="text" value="0" name="discount_per" class="form-control onlynumber" id="popup_discount_per" placeholder="Discount(%)" required="">
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


        <!-- Add Company model -->
        <?php include "addcompanymodel.php"?>

        <!-- Add GST Model -->
        <?php include "addgstmodel.php"?>

        <!--  Add Unit Model -->
        <?php include "addunitmodel.php"?>
        
        <!--  Add Product Type Model -->
        <?php include "addproducttype.php"; ?>

        <!--  Add Product Category Model -->
        <?php include "addproductcategory.php"; ?>