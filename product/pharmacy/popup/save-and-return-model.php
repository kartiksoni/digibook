<div class="modal fade" id="save_and_return_model" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
      
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel">Sale Return</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" id="sale_return_form">
          <div class="modal-body">
            <div class="form-group row">

              <div class="col-12 col-md-2">
                <label>Credit Note No. <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="credit_note_no" id="credit_note_no" placeholder="Credit Note No." required>
              </div>

              <div class="col-12 col-md-2">
                <label>Credit Note Date</label>
                <input type="text" class="form-control datepicker" name="credit_note_date" id="credit_note_date" value="<?php echo date('d/m/Y'); ?>" placeholder="Credit Note Date">
              </div>

              <div class="col-12 col-md-2">
                <label>City</label>
                <input type="text" class="form-control" name="city_name" id="r_city_name" placeholder="City" readonly>
                <input type="hidden" name="city_id" id="r_city_id">
              </div>

              <div class="col-12 col-md-2">
                <label>Customer</label>
                <input type="text" class="form-control" name="customer_name" id="r_customer_name" placeholder="Customer" readonly>
                <input type="hidden" name="customer_id" id="r_customer_id">
              </div>

            </div>
            <div class="form-group row">
              <div class="col-md-12">
                <table class="table">
                  <thead>
                    <tr>
                      <th width="5%">Sr No.</th>
                      <th width="15%">Product</th>
                      <th>MFG Co.</th>
                      <th>Batch</th>
                      <th>Expiry</th>
                      <th>MRP</th>
                      <th>Qty</th>
                      <th>Discount</th>
                      <th>GST%</th>
                      <th>GST</th>
                      <th>Amount</th>
                      <th width="8%">Action</th>
                    </tr>
                  </thead>
                  <tbody id="sale-return-body">
                    <tr>
                      <td>1</td>
                      <td>
                        <input type="text" name="r_product[]" class="form-control r_product" placeholder="Product Name" required>
                        <small class="text-danger r_product_error"></small>
                        <input type="hidden" name="r_product_id[]" class="r_product_id">
                        <input type="hidden" name="r_tax_bill_id[]" class="r_tax_bill_id">
                      </td>
                      <td>
                        <input type="text" name="r_mfg_co[]" class="form-control r_mfg_co" placeholder="MFG Co.">
                      </td>
                      <td>
                        <input type="text" name="r_batch[]" class="form-control r_batch" placeholder="Batch">
                      </td>
                      <td>
                        <input type="text" name="r_expiry[]" class="form-control r_expiry" placeholder="Expiry">
                      </td>
                      <td>
                        <input type="text" name="r_mrp[]" class="form-control r_mrp" placeholder="MRP" readonly>
                      </td>
                      <td>
                        <input type="text" name="r_qty[]" class="form-control r_qty" placeholder="Qty" required>
                        <input type="hidden" name="r_qty_ratio[]" class="r_qty_ratio">
                        <input type="hidden" name="r_rate[]" class="r_rate">
                      </td>
                      <td>
                        <input type="text" name="r_discount[]" class="form-control r_discount" placeholder="Discount">
                      </td>
                      <td>
                        <input type="text" name="r_gst[]" class="form-control r_gst" placeholder="GST%" readonly>
                        <input type="hidden" name="r_igst[]" class="r_igst">
                        <input type="hidden" name="r_cgst[]" class="r_cgst">
                        <input type="hidden" name="r_sgst[]" class="r_sgst">
                      </td>
                      <td>
                        <input type="text" name="r_gst_tax[]" class="form-control r_gst_tax" placeholder="GST" readonly>
                      </td>
                      <td>
                        <input type="text" name="r_amount[]" class="form-control r_amount" placeholder="Amount" readonly required>
                      </td>
                      <td>
                        <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item-return">
                          <i class="fa fa-plus mr-0 ml-0"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-md-6">
                <label>Remarks / Reason for Return </label>
                <textarea class="form-control" name="remarks" id="r_remarks" rows="3"></textarea>
              </div>
              <div class="col-md-6" style="padding-top: 30px;">
                <table class="table table-striped" width="100%">
                  <tr>
                    <td class="text-right" width="70%">Total Amount</td>
                    <td width="30%">
                      <input type="text" name="finalamount" id="r_finalamount" class="form-control text-right onlynumber" placeholder="Taxable Amount" readonly>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          
          <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="btn-add-return">Save</button>
            <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
</div>

<div style="display: none;" id="hidden-sale-return">
  <table>
    <tr>
        <td>##SRNO##</td>
        <td>
          <input type="text" name="r_product[]" class="form-control r_product" placeholder="Product Name" required>
          <small class="text-danger r_product_error"></small>
          <input type="hidden" name="r_product_id[]" class="r_product_id">
          <input type="hidden" name="r_tax_bill_id[]" class="r_tax_bill_id">
        </td>
        <td>
          <input type="text" name="r_mfg_co[]" class="form-control r_mfg_co" placeholder="MFG Co.">
        </td>
        <td>
          <input type="text" name="r_batch[]" class="form-control r_batch" placeholder="Batch">
        </td>
        <td>
          <input type="text" name="r_expiry[]" class="form-control r_expiry" placeholder="Expiry">
        </td>
        <td>
          <input type="text" name="r_mrp[]" class="form-control r_mrp" placeholder="MRP" readonly>
        </td>
        <td>
          <input type="text" name="r_qty[]" class="form-control r_qty" placeholder="Qty" required>
          <input type="hidden" name="r_qty_ratio[]" class="r_qty_ratio">
          <input type="hidden" name="r_rate[]" class="r_rate">
        </td>
        <td>
          <input type="text" name="r_discount[]" class="form-control r_discount" placeholder="Discount">
        </td>
        <td>
          <input type="text" name="r_gst[]" class="form-control r_gst" placeholder="GST%" readonly>
          <input type="hidden" name="r_igst[]" class="r_igst">
          <input type="hidden" name="r_cgst[]" class="r_cgst">
          <input type="hidden" name="r_sgst[]" class="r_sgst">
        </td>
        <td>
          <input type="text" name="r_gst_tax[]" class="form-control r_gst_tax" placeholder="GST" readonly>
        </td>
        <td>
          <input type="text" name="r_amount[]" class="form-control r_amount" placeholder="Amount" readonly required>
        </td>
        <td>
          <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item-return">
            <i class="fa fa-plus mr-0 ml-0"></i>
          </button>
          <button type="button" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-item-return">
            <i class="fa fa-close mr-0 ml-0"></i>
          </button>
        </td>
      </tr>
  </table>
</div>