<div class="modal fade" id="mi-modal" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
      
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel">Conform</h5>
        </div>
        <div class="modal-body">
          <span id="limit_sms"></span>
        </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-default" id="modal-btn-si">Yes</button>
              <button type="button" class="btn btn-primary" id="modal-btn-no">No</button>
          </div>
        </form>
      </div>
    </div>
</div>

<!-- hidden HTML -->

<div id="missed-hidden-html" style="display: none;">
  <table>
    <tr id="##ID##">
      <td>
        ##PRODUCTNAME##
        <input type="hidden" class="product" name="product[]" value="##PRODUCTNAME##">
        <input type="hidden" class="product_id" name="product_id[]" value="##PRODUCTID##">
      </td>
      <td>
        ##QTY##
        <input type="hidden" class="qty" name="qty[]" value="##QTY##">
      </td>
      <td>
        ##UNIT##
        <input type="hidden" class="unit" name="unit[]" value="##UNIT##">
      </td>
      <td>
        <button type="button" class="btn btn-success p-2 btn-mis-edit"><i class="icon-pencil mr-0"></i></button>
        <button type="button" class="btn btn-danger p-2 btn-mis-delete"><i class="icon-close mr-0"></i></button>
      </td>
    </tr>
  </table>
</div>