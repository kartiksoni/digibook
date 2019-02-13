<div class="modal fade" id="set-reminder-model" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
      
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel">Order Reminder</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          <span id="reminder-errormsg"></span>
          
            <div class="form-group row">
              <div class="col-12 col-md-12">
                <label for="day">Enter Reminder Day <span class="text-danger">*</span></label>
                <input type="text" class="form-control onlynumber" name="day" id="day" placeholder="Reminder Day" autocomplete="off">
                <input type="hidden" name="reminderid" id="reminderid">
              </div>
            </div>
          
        </div>
        
          <div class="modal-footer" style="display: block;">
              <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-success pull-right" id="btn-reminder">Resend</button>
          </div>
      </div>
    </div>
</div>