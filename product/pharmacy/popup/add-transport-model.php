<div class="modal fade" id="add-transport-model" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
      
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel">Add New Transport</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="add-transport-form" autocomplete="off">
          <div class="modal-body">
            <span id="transport-errormsg"></span>
            <div class="form-group row">
              <div class="col-12 col-md-12">
                <label for="name">Transport Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" placeholder="Transport Name" required>   
              </div>
            </div>
          </div>
          
          <div class="modal-footer row">
            <div class="col-md-12">
              <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success pull-right" id="btn-transport">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
</div>