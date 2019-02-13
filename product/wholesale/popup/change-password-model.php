<div class="modal fade" id="change-password-model" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
      
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel">Change Password</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <form id="change-password-form" method="POST">
            <div class="modal-body">
            
                <div class="form-group row">
              
                    <div class="col-12 col-md-4" style="padding-right: 5px;padding-left: 5px;">
                        <label for="old_password">Old Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="old_password" id="old_password" placeholder="Old Password" required>
                    </div>
                  
                    <div class="col-12 col-md-4" style="padding-right: 5px;padding-left: 5px;">
                        <label for="password">New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password" id="new_password" placeholder="New Password" required>
                    </div>
                    
                    <div class="col-12 col-md-4" style="padding-right: 5px;padding-left: 5px;">
                        <label for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" data-parsley-equalto="#new_password" placeholder="Confirm Password" required>
                    </div>
                    
                </div>
            
            </div>
        
            <div class="modal-footer">
                <button type="button" class="btn btn-light text-left" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success text-right" id="btn-change-password">Reset</button>
            </div>
        </form>
      </div>
    </div>
</div>