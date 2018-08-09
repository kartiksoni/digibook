<div class="modal fade" id="purchase-addproductmodel" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
      <form id="add-product">
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
                  <input type="text" name="product_name" class="form-control" placeholder="Product Name" required>
                </div>

                <div class="col-12 col-md-4">
                  <label for="generic_name">Generic Name <span class="text-danger">*</span></label>
                <input type="text" name="generic_name" class="form-control" placeholder="Generic Name " required>
                </div>
                
                 <div class="col-12 col-md-4">
                  <label for="exampleInputName1">MFG. Company<span class="text-danger">*</span></label>
                  <input type="text" name="mfg_company" class="form-control" placeholder="MFG. Company" required>
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
                  <label for="give_mrp">Give a New MRP</label>
                  <input type="text" name="give_mrp" class="form-control" placeholder="Give a New MRP" data-parsley-type="number">
                </div>
              
                <div class="col-12 col-md-4">
                  <label for="ex_duty">Ex. Duty</label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-dark">
                      <span class="input-group-text bg-transparent"><i class="mdi mdi-percent text-white"></i></span>
                    </div>
                    <input type="text" name="ex_duty" class="form-control" placeholder="Ex. Duty" aria-label="Ex. Duty">
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