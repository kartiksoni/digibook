<div class="modal fade" id="save_and_return_model" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
      
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel">Product Return</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          <form method="POST" id="saveandreturn-tmp-form">
            <div class="form-group row">
          
                  <div class="col-12 col-md-2">
                    <label for="sar_product">Product <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="sar_product" id="sar_product" placeholder="Product Name" required>
                    <input type="hidden" class="form-control" name="sar_product_id" id="sar_product_id">
                    <small class="text-danger sar_product_error"></small>
                  </div>

                  <div class="col-12 col-md-1">
                    <label for="sar_batch">Batch</label>
                    <input type="text" class="form-control" name="sar_batch" id="sar_batch" placeholder="Batch">
                  </div>

                  <div class="col-12 col-md-2">
                    <label for="sar_expiry">Expiry</label>
                    <input type="text" class="form-control datepicker" name="sar_expiry" id="sar_expiry" placeholder="dd/mm/yyyy">
                  </div>
                  
                  
                  <div class="col-12 col-md-1">
                    <label for="sar_qty">Quantity</label>
                    <input type="text" class="form-control onlynumber" name="sar_qty" id="sar_qty" placeholder="00">
                    <input type="hidden" name="sar_rate" id="sar_rate">
                  </div>
                  
                  <div class="col-12 col-md-1">
                    <label for="sar_discount">Disc</label>
                    <input type="text" class="form-control onlynumber" name="sar_discount" id="sar_discount" placeholder="00 (RS)">
                  </div>
                  
                  <div class="col-12 col-md-1">
                    <label for="sar_gst">GST%</label>
                    <input type="text" class="form-control onlynumber" name="sar_gst" id="sar_gst" placeholder="00 (%)">

                    <input type="hidden" class="form-control" name="sar_igst" id="sar_igst">
                    <input type="hidden" class="form-control" name="sar_cgst" id="sar_cgst">
                    <input type="hidden" class="form-control" name="sar_sgst" id="sar_sgst">
                  </div>
                  
                  <div class="col-12 col-md-1">
                    <label for="sar_amount">Amount</label>
                    <input type="text" class="form-control onlynumber" name="sar_amount" id="sar_amount" placeholder="00">
                    <input type="hidden" name="sar_mrp" id="sar_mrp">
                    <input type="hidden" name="sar_mfg_co" id="sar_mfg_co">
                    <input type="hidden" name="editid" id="editid">
                  </div>
                  
                  <div class="col-12 col-md-3">
                   <button type="submit" class="btn btn-primary mt-30" id="btn-tmp-return" disabled>Return</button>
                   <button type="button" class="btn btn-grey-1 mt-30" id="btn-tmp-return-clear">Clear</button>
                  </div>
                  
                  <!-- <div class="col-12 col-md-9 mt-1">
                    <label for="exampleInputName1" class="color-green"><strong>Total Qty.:</strong> 15</label>&nbsp;
                    <label for="exampleInputName1" class="pull-right"><strong>Purchase Price:</strong> 1200</label>
                  </div> -->
                
              </div>
          </form>
          
          <div class="row">
            <div class="col-12">
              <table id="sar-table" class="table" style="display: none;">
                <thead>
                  <tr align="left">
                      <th>Product</th>
                      <th>Batch</th>
                      <th>Expiry</th>
                      <th>Qty.</th>
                      <th>Disc</th>
                      <th>GST</th>
                      <th>Amount</th>
                      <th width="8%">Action</th>
                  </tr>
                </thead>
                <tbody id="sar-tbody">
                  
                </tbody>
              </table>            
            </div>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="btn-add-return" disabled>Return Items</button>
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
</div>

<div style="display: none;" id="sar-hidden-html">
  <table>
    <tr id="##ID##">
      <td><span class="td-sr-product">##PRODUCTNAME##</span><input type="hidden" class="td-sr-product-id" value="##PRODUCTID##"></td>
      <td class="td-sr-batch">##BATCH##</td>
      <td class="td-sr-expiry">##EXPIRY##</td>
      <td>
        <span class="td-sr-qty">##QTY##</span>
        <input type="hidden" class="td-sr-rate" value="##RATE##">
      </td>
      <td class="td-sr-disc">##DISC##</td>
      <td>
        <span class="td-sr-gst">##GST##</span>
        <input type="hidden" class="td-sr-igst" value="##IGST##">
        <input type="hidden" class="td-sr-cgst" value="##CGST##">
        <input type="hidden" class="td-sr-sgst" value="##SGST##">
      </td>
      <td>
        <span class="td-sr-amount">##AMOUNT##</span>
        <input type="hidden" class="td-sr-mrp" value="##MRP##">
        <input type="hidden" class="td-sr-mfg" value="##MFG##">
      </td>
      <td>
        <button type="button" class="btn btn-success p-2 btn-sar-edit"><i class="icon-pencil mr-0"></i></button>
        <button type="button" class="btn btn-danger p-2 btn-sar-delete"><i class="icon-close mr-0"></i></button>
      </td>
    </tr>
  </table>
</div>