<div class="modal fade" id="addarea-model" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel">Add New Area</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="add-area-form">
          <div class="modal-body">
            <span id="addarea-errormsg"></span>
            <div class="form-group row">
              <div class="col-12 col-md-12">
                <label for="name">Area Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Area Name" required>   
              </div>
              
            </div>
          </div>
          <div class="modal-footer">
            <div class="col-md-12">
              <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success pull-right" id="btn-addarea">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
</div>