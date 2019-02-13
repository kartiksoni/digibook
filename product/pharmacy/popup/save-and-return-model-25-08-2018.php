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
          <form method="POST">
            <div class="form-group row">
          
                  <div class="col-12 col-md-2">
                    <label for="sar_product">Product</label>
                    <input type="text" class="form-control" id="sar_product" placeholder="Product Name">
                    <input type="hidden" class="form-control" id="sar_product_id">
                  </div>
                  
                  
                  <div class="col-12 col-md-1">
                    <label for="sar_qty">Quantity</label>
                    <input type="text" class="form-control onlynumber" id="sar_qty" placeholder="00">
                  </div>
                  
                  <div class="col-12 col-md-1">
                    <label for="sar_batch">Batch</label>
                    <input type="text" class="form-control" id="sar_batch" placeholder="Batch">
                    <!-- <select class="js-example-basic-single" style="width:100%"> 
                        <option value="Regular">19</option>
                    </select> -->
                  </div>
                  
                  <div class="col-12 col-md-1">
                    <label for="sar_discount">Disc%</label>
                    <input type="text" class="form-control onlynumber" id="sar_discount" placeholder="00">
                  </div>
                  
                  <div class="col-12 col-md-2">
                    <label for="sar_expiry">Expiry</label>
                    <input type="text" class="form-control datepicker" id="sar_expiry" placeholder="dd/mm/yyyy">
                  </div>
                  
                  <div class="col-12 col-md-1">
                    <label for="sar_gst">GST%</label>
                    <input type="text" class="form-control onlynumber" id="sar_gst" placeholder="00">
                  </div>
                  
                  <div class="col-12 col-md-1">
                    <label for="sar_mrp">Amount</label>
                    <input type="text" class="form-control onlynumber" id="sar_mrp" placeholder="00">
                  </div>
                  
                  <div class="col-12 col-md-3">
                   <button type="submit" class="btn btn-primary mt-30">Return</button>
                   <button type="reset" class="btn btn-grey-1 mt-30">Clear</button>
                  </div>
                  
                  <div class="col-12 col-md-9 mt-1">
                    <label for="exampleInputName1" class="color-green"><strong>Total Qty.:</strong> 15</label>&nbsp;
                    <label for="exampleInputName1" class="pull-right"><strong>Purchase Price:</strong> 1200</label>
                  </div>
                 
                
              </div>
          </form>
          
          <div class="row">
            <div class="col-12">
              <table id="order-listing1" class="table">
                <thead>
                  <tr align="left">
                      <th>Sr No.</th>
                      <th>Product</th>
                      <th>Qty.</th>
                      <th>Disc.%</th>
                      <th>Ammount</th>
                  </tr>
                </thead>
                <tbody>
                  <tr >
                      <td>O133</td>
                      <td>A O FORTE</td>
                      <td>25</td>
                      <td>5</td>
                      <td>125</td>
                  </tr>
                </tbody>
              </table>            
            </div>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-success">Return Items</button>
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
</div>