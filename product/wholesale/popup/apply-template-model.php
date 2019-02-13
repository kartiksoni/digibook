<div class="modal fade" id="apply_template_model" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
      
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel">Template</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <form id="templateapply-form">
          <div class="modal-body">
            <span id="template-errormsg"></span>
            <div class="form-group row">
              <div class="col-12 col-md-12">
                <label for="mis_product">Product <span class="text-danger">*</span></label>
                <select class="js-example-basic-single" style="width:100%" name="template" id="template" data-parsley-errors-container="#template-err" required>
                  <option value="">Select Template</option>
                  <?php
                    $tmpPrmcy = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
                    $tmpfinance = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
                    $tempQ = "SELECT * FROM sales_template WHERE status = 1 AND pharmacy_id = '".$tmpPrmcy."' AND financial_id = '".$tmpfinance."' ORDER BY no";
                    $tempR = mysqli_query($conn, $tempQ);
                    if($tempR && mysqli_num_rows($tempR) > 0){
                      while ($tempRow = mysqli_fetch_array($tempR)) {
                        echo "<option value='".$tempRow['id']."'>".$tempRow['no']." - ".$tempRow['name']."</option>";
                      }
                    }
                  ?>
                </select>
                <span id="template-err"></span>
              </div>
               <input type="hidden" name="customer" id="bytempcust">
            </div>
          </div>
        
          <div class="modal-footer">
              <button type="button" class="btn btn-light text-left" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success text-right" id="btn-addtemplate">Apply</button>
          </div>
        </form>
      </div>
    </div>
</div>