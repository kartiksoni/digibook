<div class="modal fade" id="alternate_product_model" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
      
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel">Product Alternate Selection</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
            <span id="alternate-error"></span>
            <div class="form-group row">
              <div class="col-12 col-md-4">
                <label for="alt_product">Product</label>
                <input type="text" data-name="product" class="form-control alt-input" id="alt_product" placeholder="Product Name">
                <small class="alt-empty text-danger"></small>
              </div>
              
              <div class="col-12 col-md-4">
                <label for="alt_generic">Generic Name</label>
                <input type="text" data-name="generic" class="form-control alt-input" id="alt_generic" placeholder="Generic Name">
                <small class="alt-empty text-danger"></small>
              </div>
              
              <!-- <div class="col-12 col-md-6">
                <label for="exampleInputName1">Type</label>
                <input type="text" data-name="type" class="form-control" id="alt_type" placeholder="Type">
                <small class="alt-empty text-danger"></small>
              </div>
              
              <div class="col-12 col-md-6">
                <label for="exampleInputName1">Schedule</label>
                <input type="text" data-name="schedule" class="form-control" id="alt_schedule" placeholder="Schedule">
                <small class="alt-empty text-danger"></small>
              </div> -->
              
              <div class="col-12 col-md-4">
                <label for="alt_manufacturer">Manufacturer</label>
                <input type="text" data-name="manufacturer" class="form-control alt-input" id="alt_manufacturer" placeholder="Manufacturer">
                <small class="alt-empty text-danger"></small>
              </div>
            </div>

          <div class="row">
            <div class="col-12">
              <table class="table">
                <thead>
                  <tr>
                      <th>#</th>
                      <th>Product</th>
                      <th>Generic Name</th>
                      <th>Manufacturer</th>
                      <th>Expiry Date</th>
                      <th>Batch</th>
                      <th>MRP</th>
                      <th>Unit/Strip/Pack</th>
                  </tr>
                </thead>
                <tbody id="alternate-tbody">
                </tbody>
              </table>            
            </div>
          </div>

        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="btn-add-alternate" disabled>Add</button>
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
</div>

<div id="hidden-alternate" style="display: none;">
  <table>
     <tr>
        <td>
          <input type="checkbox" name="product_id" class="alt_product_id" value="##PRODUCTID##">
        </td>
        <td class="alt_product_name">##PRODUCTNAME##</td>
        <td class="alt_product_generic">##GENERICNAME##</td>
        <td class="alt_product_mfg">##MFG##</td>
        <td>
            <span class="alt_product_expiry">##EXPDATE##</span>
            <small class="expired text-danger">##EXPIRED##</small>
        </td>
        <td class="alt_product_batch">##BATCH##</td>
        <td class="alt_product_mrp">##MRP##</td>
        <td class="alt_product_unit">##UNIT##</td>
        <input type="hidden" class="alt_product_igst" value="##IGST##">
        <input type="hidden" class="alt_product_cgst" value="##CGST##">
        <input type="hidden" class="alt_product_sgst" value="##SGST##">
        <input type="hidden" class="alt_product_ratio" value="##RATIO##">
        <input type="hidden" class="alt_product_ptr" value="##PTR##">
        <input type="hidden" class="alt_product_discount" value="##DISCOUNT##">
        <input type="hidden" class="alt_product_rate" value="##RATE##">
        <input type="hidden" class="alt_product_stock" value="##STOCK##">
    </tr>
  </table>
</div>