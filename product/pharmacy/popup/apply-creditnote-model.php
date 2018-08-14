<div class="modal fade" id="apply-creditnote-model" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel">Apply Credit Note</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="add-company-form">
          <div class="modal-body">
            <span id="addcompany-errormsg"></span>
            <div class="form-group row">
              <div class="col-12 col-md-6">
                <label for="name">Vendor Cr Note No. <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="credit_note_no" name="vendor_credit_note_no" placeholder="Vendor Credit Note Number" required>   
              </div>
              
              <div class="col-12 col-md-6">
                <label for="code">Vendor Cr Note Date. <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="credit_note_date" placeholder="Vendor Credit Note Date" required>   
              </div>
            </div>
            <div class="form-group row">
              <div class="col-12 col-md-12">
                <label for="name">Remarks</label>
                <textarea class="form-control" id="name" name="remarks" placeholder="Remarks"></textarea>  
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <div class="col-md-12">
              <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success pull-right" id="btn-addcompany">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
</div>