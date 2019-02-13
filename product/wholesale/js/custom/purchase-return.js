// author : Gautam Makwana
// date   : 22-12-2018
$(document).ready(function(){

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

  // Remove product button js //

    $('body').on('click', '.btn-remove-product', function(e) {
        e.preventDefault();
        $(this).closest ('tr').remove ();
        calculation();
    });

    // End Remove product button js // 

  
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
        var statecode = $('#statecode').val();
        var current_state_code = $('#current_state_code').val();

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
            }],

            // Event handler for when a list item is selected.
            select: function (event, ui) {
              this.value = (ui.item.product_name ? ui.item.product_name : '');

              $('#show-invoice-no-model').find('#model-invoice-no').html(ui.item.invoice_no);
              $('#show-invoice-no-model').find('#model-invoice-date').html(ui.item.invoice_date);
              $('#show-invoice-no-model').find('#model-invoice-amount').html(ui.item.invoice_amount);
              $('#show-invoice-no-model').modal('show');

              $(this).closest('tr').find('.product_id').val(ui.item.product_id);
              $(this).closest('tr').find('.purchase_id').val(ui.item.purchase_id);
              $(this).closest('tr').find('.mrp').val(ui.item.mrp);
              $(this).closest('tr').find('.mfg_co').val(ui.item.mfg_company);
              $(this).closest('tr').find('.batch_no').val(ui.item.batch_no);
              $(this).closest('tr').find('.expiry').val(ui.item.ex_date);
              $(this).closest('tr').find('.qty').val(ui.item.qty);
              $(this).closest('tr').find('.free_qty').val(ui.item.free_qty);
              $(this).closest('tr').find('.rate').val(ui.item.rate);
              $(this).closest('tr').find('.discount').val(ui.item.discount);
              $(this).closest('tr').find('.f_rate').val(ui.item.f_rate);

              if(statecode == current_state_code){
                $(this).closest('tr').find('.f_igst').val(0);
                $(this).closest('tr').find('.f_cgst').val(ui.item.f_cgst);
                $(this).closest('tr').find('.f_sgst').val(ui.item.f_sgst);
              }else{
                $(this).closest('tr').find('.f_igst').val(ui.item.f_igst);
                $(this).closest('tr').find('.f_cgst').val(0);
                $(this).closest('tr').find('.f_sgst').val(0);
              }
              $(this).closest('tr').find('.qty').trigger("change");
              return false;
            },

            // The rest of the options are for configuring the ajax webservice call.
            minLength: 1,
            
            source: function (request, response) {
                $.ajax({
                    url: 'ajax.php',
                    dataType: 'json',
                    type: "POST",
                    data: {query: request.term,vendor: $('#vendor_id').val(),action: "searchProductPurchaseReturn"},
                    success: function (data) {
                      if(data.status == true){
                        $($this).closest('tr').find('.empty-message').empty();
                        response(data.result)
                      }else{
                        $($this).closest('tr').find('.empty-message').text("No results found");
                      }
                    }
                });
            }
        });
  });

  $('#show-invoice-no-model').on('shown.bs.modal', function() {
      $('#btn-invoice-ok').focus();
  });

  $('body').on('change', '#vendor_id', function() {
    var statecode = $(this).find(':selected').attr('data-state');
    $('#statecode').val(statecode);
  });

  $('body').on('propertychange change keyup focusout past', '.qty, .free_qty, .rate, .discount', function() {
      var qty = $(this).closest('tr').find('.qty').val();
      var rate = $(this).closest('tr').find('.rate').val();
      var discount = $(this).closest('tr').find('.discount').val();
      var f_rate = 0;

      qty = (typeof qty !== 'undefined' && !isNaN(qty) && qty != '') ? parseFloat(qty) : 0;
      rate = (typeof rate !== 'undefined' && !isNaN(rate) && rate != '') ? parseFloat(rate) : 0;
      discount = (typeof discount !== 'undefined' && !isNaN(discount) && discount != '') ? parseFloat(discount) : 0;

      f_rate = (rate)-(rate*discount/100);
      var amount = (qty*f_rate);

      $(this).closest('tr').find('.f_rate').val(f_rate);
      $(this).closest('tr').find('.ammout').val(amount);
      calculation();
  });

  function calculation(){
    var taxable_amount = 0;
    var total_igst = 0;
    var total_cgst = 0;
    var total_sgst = 0;

    $('.ammout:visible').each(function() {
      var qty = $(this).closest('tr').find('.qty').val();
      var free_qty = $(this).closest('tr').find('.free_qty').val();
      var f_rate = $(this).closest('tr').find('.f_rate').val();

      qty = (typeof qty !== 'undefined' && !isNaN(qty) && qty != '') ? parseFloat(qty) : 0;
      free_qty = (typeof free_qty !== 'undefined' && !isNaN(free_qty) && free_qty != '') ? parseFloat(free_qty) : 0;
      f_rate = (typeof f_rate !== 'undefined' && !isNaN(f_rate) && f_rate != '') ? parseFloat(f_rate) : 0;

      taxable_amount += (qty*f_rate);

      var amount = (qty+free_qty)*(f_rate);

      var igst = $(this).closest('tr').find('.f_igst').val();
      var cgst = $(this).closest('tr').find('.f_cgst').val();
      var sgst = $(this).closest('tr').find('.f_sgst').val();

      igst = (typeof igst !== 'undefined' && !isNaN(igst) && igst != '') ? parseFloat(igst) : 0;
      cgst = (typeof cgst !== 'undefined' && !isNaN(cgst) && cgst != '') ? parseFloat(cgst) : 0;
      sgst = (typeof sgst !== 'undefined' && !isNaN(sgst) && sgst != '') ? parseFloat(sgst) : 0;

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

    