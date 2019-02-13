<style type="text/css">
  #lastbill-product-tbody tr td {border: 1px solid #ccc !important;}
</style>
<div class="modal fade" id="lastbill_model" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
      
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel">Previous Bill Products</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
            <span id="lastbill-errormsg"></span>
            <div class="row">
              <div class="col-12">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                        <th><input type="checkbox" class="lastbill-check-all"></th>
                        <th>Sr No.</th>
                        <th>Product</th>
                        <th>MFG Co.</th>
                        <th>Batch</th>
                        <th>Expiry</th>
                        <th>MRP</th>
                        <th>Qty</th>
                        <th>Discount(%)</th>
                        <th>GST(%)</th>
                        <th>GST</th>
                        <th>Amount</th>
                    </tr>
                  </thead>
                  <tbody id="lastbill-product-tbody">
                    
                  </tbody>
                </table>            
              </div>
            </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="btn-add-lastbill" disabled>Add</button>
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
</div>

<div id="hidden-lastbill" style="display: none;">
  <table>
      <tr>
        <td><input type="checkbox" class="lastbill-check"></td>
        <td>##SRNO##</td>
        <td>
          <span class="product_name">##PRODUCTNAME##</span>
          <input type="hidden" class="product_id" value="##PRODUCTID##">
        </td>
        <td><span class="mfg">##MGF##</span></td>
        <td><span class="batch">##BATCH##</span></td>
        <td>
            <span class="expiry">##EXPIRY##</span>
            <small class="expired text-danger">##EXPIRED##</small>
        </td>
        <td><span class="mrp">##MRP##</span></td>
        <td>
          <span class="qty">##QTY##</span>
          <input type="hidden" class="ratio" value="##RATIO##">
          <input type="hidden" class="stock" value="##STOCK##">
          <input type="hidden" class="rate" value="##RATE##">
        </td>
        <td><span class="discount">##DISCOUNT##</span></td>
        <td>
          <span class="gst">##GST##</span>
          <input type="hidden" class="cgst" value="##CGST##">
          <input type="hidden" class="sgst" value="##SGST##">
          <input type="hidden" class="igst" value="##IGST##">
        </td>
        <td><span class="gst_tax">##GSTTAX##</span></td>
        <td><span class="amount">##AMOUNT##</span></td>
      </tr>
  </table>
</div>