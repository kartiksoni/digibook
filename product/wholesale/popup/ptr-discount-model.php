<div class="modal fade" id="ptr-discount-model" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
        
        <div class="modal-body">
            <div class="form-group row">
              <div class="col-12 col-md-6">
                <label for="ptr">Rate</label>
                <input type="text" class="form-control" readonly name="rate" id="rate" placeholder="Rate" required>
              </div>

              <div class="col-12 col-md-6">
                <label for="discount">Discount</label>
                <input type="text" class="form-control" name="discount" id="discount" placeholder="Discount" required>
                <input type="hidden" name="tr-id" id="tr-id">
              </div>
            </div>
        </div>
        
          <div class="modal-footer">
              <button type="submit" class="btn btn-success" id="btn-ptr-discount" >Add</button>
          </div>
      </div>
    </div>
</div>