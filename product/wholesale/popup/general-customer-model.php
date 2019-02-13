<style>
    .ui-autocomplete { z-index:2147483647; }
</style>
<div class="modal fade" id="general-customer-model" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
      
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel">General Customer</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          <span id="customer-errormsg"></span>
          <form id="general-customer-form" method="POST">
            <div class="form-group row">
              <div class="col-12 col-md-6">
                <label for="customer_name">Customer Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control customer_name" name="customer_name" placeholder="Customer Name" autocomplete="off" required>
              </div>
              
              <div class="col-12 col-md-6">
                <label for="customer_mobile">Mobile</label>
                <input type="text" class="form-control customer_mobile onlynumber" name="customer_mobile" placeholder="Mobile No" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
                <div class="col-12 col-md-6">
                    <label for="customer_city">City</label>
                    <input type="text" class="form-control customer_city" name="customer_city" id="general_customer_city" placeholder="Customer City" autocomplete="off">
                    <i class="fa fa-spin fa-refresh city-loader display-none" style="position: absolute;top: 40px;right: 40px;"></i>
                    <input type="hidden" name="city_id" class="city_id">
                    <input type="hidden" name="state_code" class="state_code">
                </div>
            </div>
        </div>
        
         <div class="modal-footer">
              <button type="button" class="btn btn-light text-left" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success text-right" id="btn-add-generalcustomer">Add</button>
         </div>
         </form>
      </div>
    </div>
</div>