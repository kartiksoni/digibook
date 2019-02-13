<div class="modal fade" id="addgst-model" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel">Add New GST</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="add-gst-form">
          <div class="modal-body">
            <span id="addgst-errormsg"></span>
            <div class="form-group row">
              <div class="col-12 col-md-6">
                <label for="name">GST Name <span class="text-danger">*</span></label>
                  <input type="text" required name="gst_name" value="<?php echo (isset($data['gst_name'])) ? $data['gst_name'] : ''; ?>" class="form-control" placeholder="GST Name">
              </div>
              
              <div class="col-12 col-md-6">
                <label for="code">IGST<span class="text-danger">*</span></label>
               <input type="text" required name="igst" value="<?php echo (isset($data['igst'])) ? $data['igst'] : ''; ?>" class="form-control igstpop onlynumber" placeholder="IGST %">
              </div>
            </div>
            
            <div class="form-group row">
              <div class="col-12 col-md-6">
                <label for="name">SGST <span class="text-danger">*</span></label>
                 <input type="text" required name="sgst" readonly value="<?php echo (isset($data['sgst'])) ? $data['sgst'] : ''; ?>" class="form-control sgstpop" placeholder="SGST %">
              </div>
              
              <div class="col-12 col-md-6">
                <label for="code">CGST<span class="text-danger">*</span></label>
               <input type="text" required name="cgst" readonly value="<?php echo (isset($data['cgst'])) ? $data['cgst'] : ''; ?>" class="form-control cgstpop" placeholder="CGST %">
              </div>
            </div>
            
            
          </div>
          <div class="modal-footer">
            <div class="col-md-12">
              <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success pull-right" id="btn-addgst">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
</div>
