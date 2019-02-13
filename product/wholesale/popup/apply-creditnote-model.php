<div class="modal fade" id="apply-creditnote-model" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel">Apply Credit Note</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="add-creditnote-form">
          <div class="modal-body">
            <span id="addcreditnote-errormsg"></span>
            <div class="form-group row">
              <div class="col-12 col-md-6">
                <label class="radio-inline"><input type="radio" name="type" class="credittype" value="Billing" checked>Billing</label>
                &nbsp;&nbsp;&nbsp;
                <label class="radio-inline"><input type="radio" name="type" class="credittype" value="Direct">Direct</label>
              </div>
              <div class="col-12 col-md-6">
                <label for="cr_no" class="cr_no_lable">Invoice No<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="cr_no" name="cr_no" placeholder="Vendor Credit Note Number" required>   
              </div>
            </div>

            <div class="form-group row">
              <div class="col-12 col-md-6">
               <label for="cr_date" class="cr_date_lable">Invoice date<span class="text-danger">*</span></label>
                 <div class="input-group date datepicker">
                    <input type="text" class="form-control border datepicker" data-parsley-errors-container="#error-date" name="cr_date" id="cr_date" autocomplete="off" placeholder="DD/MM/YYYY" required>
                    <span class="input-group-addon input-group-append border-left">
                      <span class="mdi mdi-calendar input-group-text"></span>
                    </span>
                  </div>
                  <span id='error-date'></span>
              </div>
              <div class="col-12 col-md-6">
                <label for="amount" class="cr_amount_lable">Amount<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="amount" name="amount" placeholder="Amount" value="0" required>   
              </div>
            </div>
            <div class="form-group row">
              <div class="col-12 col-md-12">
                <label for="name">Remarks</label>
                <textarea class="form-control" id="name" name="remarks" placeholder="Remarks"></textarea>  
              </div>
            </div>
            <input type="hidden" name="purchase_return_id" id="purchase_return_id">
          </div>
          <div class="modal-footer">
            <div class="col-md-12">
              <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success pull-right" id="btn-addcreditnote">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
</div>