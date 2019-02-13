<style type="text/css">
  #newgroup-form .bs-placeholder{border: 1px solid !important;}
  #newgroup-form .dropdown-toggle{border: 1px solid !important;}
  #newgroup-form .bootstrap-select{width: 100% !important;}
  #newgroup-form div.dropdown-menu{border: 1px solid grey !important;}
</style>
<div class="modal fade" id="new-group-model" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
      
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel">Create New Group</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="newgroup-form" method="POST">
          <div class="modal-body">
            <span id="newgroup-errormsg"></span>
              <div class="form-group row">

                <div class="col-12 col-md-12">
                  <label for="name">Group Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="name" id="group_name" placeholder="Enter Group Name" autocomplete="off" required>
                </div>

                <div class="col-12 col-md-12">
                  <label for="user">Select User <span class="text-danger">*</span></label>
                  <select class="selectpicker" data-live-search="true" multiple data-selected-text-format="count" data-style="btn-default" name="user[]" data-parsley-errors-container="#error-group" required>
                    <?php if(isset($getAllUserData) && !empty($getAllUserData)){ ?>
                      <?php foreach ($getAllUserData as $key => $value) { ?>
                        <?php if(isset($value['is_group']) && $value['is_group'] == 0){ ?>
                            <option value="<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>"><?php echo (isset($value['name'])) ? $value['name'] : ''; ?><?php echo (isset($value['user_type']) && $value['user_type'] != '') ? ' - '.$value['user_type'] : ''; ?></option>
                        <?php } ?>
                      <?php } ?>
                    <?php } ?>
                  </select>
                  <span id="error-group"></span>
                </div>
                
              </div>
            
          </div>
        
          <div class="modal-footer">
            <button type="button" class="btn btn-light text-left" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success text-right" id="btn-addgroup">Add</button>
          </div>

        </form>
      </div>
    </div>
</div>