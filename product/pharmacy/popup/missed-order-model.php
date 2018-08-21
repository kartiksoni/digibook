<div class="modal fade" id="missed-order-model" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
      
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel">Missed Orders</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          <span id="missedorder-errormsg"></span>
          <form id="missed-order-tmpform" method="POST">
            <div class="form-group row">
              <div class="col-12 col-md-6">
                <label for="mis_product">Product <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="mis_product" id="mis_product" placeholder="Product" required>
                <small class="text-danger" id="mis-empty-error"></small>
                <input type="hidden" class="form-control" name="mis_product_id" id="mis_product_id" placeholder="Product">
              </div>
              
              <div class="col-12 col-md-3">
                <label for="mis_qty">Qty.</label>
                <input type="text" class="form-control onlynumber" name="mis_qty" id="mis_qty" placeholder="00" value="1">
              </div>
              
              <div class="col-12 col-md-3">
                <label for="mis_unit">Unit/Strip</label>
                <input type="text" class="form-control onlynumber" name="mis_unit" id="mis_unit" placeholder="00">
              </div>
              
              <div class="col-12 col-md-3">
                <input type="hidden" name="editid" id="editid">
               <button type="submit" class="btn btn-success btn-xs mt-30" id="btn-addmissorder-tmp" disabled>Add</button>
              </div>
              
              <div class="col-12 col-md-3 mt-30">
               <a href="#" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#exampleModal-7" data-whatever="">Add New Product</a>
              </div>
            </div>
          </form>
          <form id="missed-order-form" method="POST">
          <div class="row">
            <div class="col-12">
              <table id="missedorder-table" class="table" style="display: none;">
                <thead>
                  <tr>
                      <th>Product</th>
                      <th>Qty.</th>
                      <th>Unit/MRP</th>
                      <th>Action</th>
                  </tr>
                </thead>
                <tbody id="missedorder-tmp-tbody">  
                  
                </tbody>
              </table>            
            </div>
          </div>
        </div>
        
          <div class="modal-footer">
              <button type="button" class="btn btn-light text-left" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success text-right" id="btn-addmissorder" disabled>Add Order</button>
          </div>
        </form>
      </div>
    </div>
</div>

<!-- hidden HTML -->

<div id="missed-hidden-html" style="display: none;">
  <table>
    <tr id="##ID##">
      <td>
        ##PRODUCTNAME##
        <input type="hidden" class="product" name="product[]" value="##PRODUCTNAME##">
        <input type="hidden" class="product_id" name="product_id[]" value="##PRODUCTID##">
      </td>
      <td>
        ##QTY##
        <input type="hidden" class="qty" name="qty[]" value="##QTY##">
      </td>
      <td>
        ##UNIT##
        <input type="hidden" class="unit" name="unit[]" value="##UNIT##">
      </td>
      <td>
        <button type="button" class="btn btn-success p-2 btn-mis-edit"><i class="icon-pencil mr-0"></i></button>
        <button type="button" class="btn btn-danger p-2 btn-mis-delete"><i class="icon-close mr-0"></i></button>
      </td>
    </tr>
  </table>
</div>