// author : Gautam Makwana
// date   : 29-12-2018
$(document).ready(function(){

  var current_statecode = $('#current_statecode').val();

  $('body').on('click', '.btn-addmore-product', function() {
        var totalproduct = $('.product-tr').length;//for product length
        var html = $('#html-copy').html();
        html = html.replace('##SRNO##',totalproduct);
        html = html.replace('<table>','');
        html = html.replace('</table>','');
        html = html.replace('<tbody>','');
        html = html.replace('</tbody>',''); 
        $('#product-tbody').append(html);
  });

  $('body').on('click', '.btn-remove-product', function() {
      $(this).closest ('tr').remove ();
      calculation();
  });
  
  $.widget('custom.mcautocomplete', $.ui.autocomplete, {
          _create: function () {
              this._super();
              this.widget().menu("option", "items", "> :not(.ui-widget-header)");
          },
          _renderMenu: function (ul, items) {
              var self = this,
                  thead;
              if (this.options.showHeader) {
                  table = $('<div class="ui-widget-header" style="width:100%"></div>');
                  $.each(this.options.columns, function (index, item) {
                      table.append('<span style="padding:0 4px;float:left;width:' + item.width + ';">' + item.name + '</span>');
                  });
                  table.append('<div style="clear: both;"></div>');
                  ul.append(table);
              }
              $.each(items, function (index, item) {
                  self._renderItem(ul, item);
              });
          },
          _renderItem: function (ul, item) {
              var t = '',
                  result = '';
              $.each(this.options.columns, function (index, column) {
                  t += '<span style="padding:0 4px;float:left;width:' + column.width + ';">' + item[column.valueField ? column.valueField : index] + '</span>'
              });
              result = $('<li></li>')
                  .data('ui-autocomplete-item', item)
                  .append('<a class="mcacAnchor">' + t + '<div style="clear: both;"></div></a>')
                  .appendTo(ul);
              return result;
          }
  });


  $('body').on('keyup click', '.product ', function () {
      var $this = $(this);
      $(this).mcautocomplete({
      // These next two options are what this plugin adds to the autocomplete widget.
          showHeader: true,
          columns: [{
              name: 'Name',
              width: '200px;',
              valueField: 'product_name'
          }, {
              name: 'Qty',
              width: '100px',
              valueField: 'qty'
          }, {
              name: 'Batch',
              width: '200px',
              valueField: 'batch_no'
          }, {
              name: 'MRP',
              width: '100px',
              valueField: 'mrp'
          }, {
              name: 'Expiry Date',
              width: '150px',
              valueField: 'ex_date'
          }, {
              name: 'GST',
              width: '50px',
              valueField: 'igst'
          }],

          // Event handler for when a list item is selected.
          select: function (event, ui) {
            this.value = (ui.item ? ui.item.product_name : '');

            $(this).closest('tr').find('.product_id').val(ui.item.id);
            $(this).closest('tr').find('.purchase_id').val(ui.item.purchase_id);
            $(this).closest('tr').find('.mrp').val(ui.item.mrp);
            $(this).closest('tr').find('.mfg_co').val(ui.item.mfg_company);
            $(this).closest('tr').find('.batch').val(ui.item.batch_no);
            $(this).closest('tr').find('.expiry').val(ui.item.ex_date);
            $(this).closest('tr').find('.qty').val(ui.item.qty);
            $(this).closest('tr').find('.free_qty').val(ui.item.free_qty);
            $(this).closest('tr').find('.rate').val(ui.item.rate);
            $(this).closest('tr').find('.discount').val(ui.item.discount);

            if($('#statecode').val() == current_statecode){
              $(this).closest('tr').find('.igst').val(0);
              $(this).closest('tr').find('.cgst').val(ui.item.cgst);
              $(this).closest('tr').find('.sgst').val(ui.item.sgst);
            }else{
              $(this).closest('tr').find('.igst').val(ui.item.igst);
              $(this).closest('tr').find('.cgst').val(0);
              $(this).closest('tr').find('.sgst').val(0);
            }

            $('#model-invoice-no').text((typeof ui.item.invoice_no !== 'undefined' && ui.item.invoice_no != '') ? ui.item.invoice_no : '-');
            $('#model-invoice-date').text((typeof ui.item.invoice_date !== 'undefined' && ui.item.invoice_date != '') ? ui.item.invoice_date : '-');
            $('#model-invoice-amount').text((typeof ui.item.invoice_amount !== 'undefined' && ui.item.invoice_amount != '' && !isNaN(ui.item.invoice_amount)) ? parseFloat(ui.item.invoice_amount).toFixed(2) : '0');
            $('#show-invoice-no-model').modal('show');

            $($this).closest('tr').find('.qty').trigger('change');
            return false;
          },

          // The rest of the options are for configuring the ajax webservice call.
          minLength: 1,
          source: function (request, response) {
              $.ajax({
                  url: 'ajax.php',
                  dataType: 'json',
                  type: "POST",
                  data: {
                      query: request.term,
                      vendor_id: $('#vendor_id').val(),
                      action: "searchProductPurchaseReturn"
                  },
                  // The success event handler will display "No match found" if no items are returned.
                  success: function (data) {
                    if(data.status == true){
                      $($this).closest('tr').find('.product-error').empty();
                      response(data.result)
                    }else{
                      $($this).closest('tr').find('.product-error').text("No Results Found!");
                    }

                  }
              });
          }
      });
  });

  $('body').on('change', '#vendor_id', function() {
    var statecode = $(this).find(':selected').attr('data-state');
    $('#statecode').val(statecode);
  });

  $('#show-invoice-no-model').on('shown.bs.modal', function() {
    $('#btn-invoice-ok').focus();
  });

  $('body').on('change keyup past', '.qty, .free_qty, .discount, .rate', function () {

    var qty = $(this).closest('tr').find('.qty').val();
    var discount = $(this).closest('tr').find('.discount').val();
    var rate = $(this).closest('tr').find('.rate').val();

    qty = (typeof qty !== 'undefined' && !isNaN(qty) && qty != '') ? parseFloat(qty) : 0;
    discount = (typeof discount !== 'undefined' && !isNaN(discount) && discount != '') ? parseFloat(discount) : 0;
    rate = (typeof rate !== 'undefined' && !isNaN(rate) && rate != '') ? parseFloat(rate) : 0;

    var discount_amount = (rate*discount/100);
    var final_rate = (rate-discount_amount);

    $(this).closest('tr').find('.final_rate').val(final_rate.toFixed(2));
    $(this).closest('tr').find('.amount').val((qty*final_rate).toFixed(2));

    calculation();
  });

  function calculation(){
    var taxable_amount = 0;
    var total_igst = 0;
    var total_cgst = 0;
    var total_sgst = 0;

    $('.amount').each(function() {
      var val = $.trim( $(this).val() );
      val = (typeof val !== 'undefined' && !isNaN(val) && val != '') ? parseFloat(val) : 0;

      taxable_amount += val;

      var igst = $(this).closest('tr').find('.igst').val();
      igst = (typeof igst !== 'undefined' && !isNaN(igst) && igst != '') ? parseFloat(igst) : 0;
      var cgst = $(this).closest('tr').find('.cgst').val();
      cgst = (typeof cgst !== 'undefined' && !isNaN(cgst) && cgst != '') ? parseFloat(cgst) : 0;
      var sgst = $(this).closest('tr').find('.sgst').val();
      sgst = (typeof sgst !== 'undefined' && !isNaN(sgst) && sgst != '') ? parseFloat(sgst) : 0;

      var qty = $(this).closest('tr').find('.qty').val();
      qty = (typeof qty !== 'undefined' && !isNaN(qty) && qty != '') ? parseFloat(qty) : 0;
      var free_qty = $(this).closest('tr').find('.free_qty').val();
      free_qty = (typeof free_qty !== 'undefined' && !isNaN(free_qty) && free_qty != '') ? parseFloat(free_qty) : 0;
      var final_rate = $(this).closest('tr').find('.final_rate').val();
      final_rate = (typeof final_rate !== 'undefined' && !isNaN(final_rate) && final_rate != '') ? parseFloat(final_rate) : 0;

      var amount = ((qty+free_qty)*final_rate);

      total_igst += (amount*igst/100);
      total_cgst += (amount*cgst/100);
      total_sgst += (amount*sgst/100);
    });

    $('#totalamount').val(taxable_amount.toFixed(2));
    $('#totaligst').val(total_igst.toFixed(2));
    $('#totalcgst').val(total_cgst.toFixed(2));
    $('#totalsgst').val(total_sgst.toFixed(2));

    $('#finalamount').val((taxable_amount+total_igst+total_cgst+total_sgst).toFixed(2));
  }

});

    