<div class="modal fade" id="add-template-model" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

        <form id="add-new-template-form" method="POST" autocomplete="off">

          <div class="modal-header">
            <h5 class="modal-title" id="ModalLabel">Create New Template</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          
          <div class="modal-body">
            <span id="template-errormsg"></span>
            <div class="form-group row">
              <div class="col-12 col-md-12">
                <label for="mis_product">Template Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="template_name" placeholder="Enter Template Name" required>
              </div>
            </div>
          </div>
          
          <div class="modal-footer">
              <button type="button" class="btn btn-light text-left" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success text-right" id="btn-add-template">Save</button>
          </div>

        </form>

      </div>
    </div>
</div>