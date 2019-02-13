$( document ).ready(function() {

	// GET ALL CUSTOMER RELATED TO CITY
	 $("#city_id").change(function(){
	      var city_id = $(this).val();

	      if(city_id != ''){
	        $.ajax({
	              type: "POST",
	              url: 'ajax.php',
	              data: {'city_id':city_id, 'action':'getAllCustomerByCity'},
	              dataType: "json",
	              success: function (data) {
	                if(data.status == true){
	                  $('#customer_id').children('option:not(:first)').remove();
	                  $.each(data.result, function (i, item) {
        	              $('#customer_id').append($('<option>', { 
        	                  value: item.id,
        	                  text : item.name 
        	              }));
        	          });
	                }else{
	                  $('#customer_id').children('option:not(:first)').remove();
	                }
	              },
	              error: function () {
	                $('#customer_id').children('option:not(:first)').remove();
	              }
	            });
	      }else{
	        $('#customer_id').children('option:not(:first)').remove();
	      }
	  });

	 // ADD MORE ITEMS
	 $('body').on('click', '.btn-add-more-item', function () {
      var trlength = $('#item-tbody tr').length+1;
      var html = $('#hidden-return-tr').html();
      html = html.replace('<table>','');
      html = html.replace('</table>','');
      html = html.replace('<tbody>','');
      html = html.replace('</tbody>','');
      html = html.replace('##SRNO##',trlength);
      $('#item-tbody').append(html);
    });

	 // ADD MORE ITEM
    $('body').on('click', '.btn-remove-item', function () {
      $(this).closest ('tr').remove ();
      calculation();
    });

    // AUTO COMPLETE PRODUCT
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

    // SEARCH PRODUCT
    $('body').on('keyup click', '.product', function () {
        var $this = $(this);
        $(this).mcautocomplete({
        // These next two options are what this plugin adds to the autocomplete widget.
            showHeader: true,
            columns: [{
                name: 'Product Name',
                width: '200px;',
                valueField: 'product_name'
            },{
                name: 'Batch',
                width: '200px',
                valueField: 'batch_no'
            }, {
                name: 'Qty',
                width: '100px;',
                valueField: 'qty'
            }, {
                name: 'amount',
                width: '100px',
                valueField: 'totalamount'
            }],

            // Event handler for when a list item is selected.
            select: function (event, ui) {
                this.value = (ui.item ? ui.item.product_name : '');
                $($this).closest('tr').find('.product_id').val(ui.item.product_id);
                $($this).closest('tr').find('.tax_bill_id').val(ui.item.tax_bill_id);
                $($this).closest('tr').find('.mrp').val(ui.item.mrp);
                $($this).closest('tr').find('.mfg').val(ui.item.mfg_company);
                $($this).closest('tr').find('.batch').val(ui.item.batch_no);
                $($this).closest('tr').find('.qty').val(ui.item.qty);
                $($this).closest('tr').find('.free_qty').val(ui.item.freeqty);
                $($this).closest('tr').find('.rate').val(ui.item.rate);
                $($this).closest('tr').find('.gst').val(ui.item.gst);
                $($this).closest('tr').find('.igst').val(ui.item.igst);
                $($this).closest('tr').find('.cgst').val(ui.item.cgst);
                $($this).closest('tr').find('.sgst').val(ui.item.sgst);
                // $($this).closest('tr').find('.amount').val(ui.item.totalamount);
                $($this).closest('tr').find('.qty').trigger('change');
                $('#show-invoice-no-model').find('#model-invoice-no').html(ui.item.invoice_no);
                $('#show-invoice-no-model').find('#model-invoice-date').html(ui.item.invoice_date);
                $('#show-invoice-no-model').find('#model-invoice-amount').html(ui.item.final_amount);
                $('#show-invoice-no-model').modal('show');
                // $('#show-invoice-no-model').find('#btn-invoice-ok').focus();
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
                        customer_id : $('#customer_id').val(),
                        action: "getProductForCustomerReturn"
                    },
                    // The success event handler will display "No match found" if no items are returned.
                    success: function (data) {
                      if(data.status == true){
                        $($this).closest('tr').find('.product_error').empty();
                        var result;
                        if (data.result.length < 0) {
                            result = [{
                                label: 'No match found.'
                            }];
                        } else {
                            result = data.result;
                        }
                        response(result);
                        
                      }else{
                        $($this).closest('tr').find('.product_error').text("No results found");
                      }

                    }
                });
            }
        });
    });

    $('#show-invoice-no-model').on('shown.bs.modal', function() {
      $('#btn-invoice-ok').focus();
    });

    $('body').on('change keyup past', '.qty, .rate', function () {
    	var qty = $(this).closest('tr').find('.qty').val();
      qty = (typeof qty !== 'undefined' && !isNaN(qty) && qty != '') ? parseFloat(qty) : 0;
      var rate = $(this).closest('tr').find('.rate').val();
      rate = (typeof rate !== 'undefined' && !isNaN(rate) && rate != '') ? parseFloat(rate) : 0;

      $(this).closest('tr').find('.amount').val((qty*rate).toFixed(2));
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

        total_igst += (val*igst/100);
        total_cgst += (val*cgst/100);
        total_sgst += (val*sgst/100);
      });

      $('#taxable_amount').val(taxable_amount.toFixed(2));
      $('#totaligst').val(total_igst.toFixed(2));
      $('#totalcgst').val(total_cgst.toFixed(2));
      $('#totalsgst').val(total_sgst.toFixed(2));

      $('#finalamount').val((taxable_amount+total_igst+total_cgst+total_sgst).toFixed(2));

    }
});