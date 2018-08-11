// author : Kartik Champaneriya
// date   : 10-08-2018
$(document).ready(function(){
  
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

  $(".tags").mcautocomplete({
        // These next two options are what this plugin adds to the autocomplete widget.
        showHeader: true,
        columns: [{
            name: 'Name',
            width: '100px;',
            valueField: 'name'
        }, {
            name: 'Qty',
            width: '100px',
            valueField: 'total_qty'
        }, {
            name: 'Batch',
            width: '100px',
            valueField: 'batch'
        }, {
            name: 'Generic Name',
            width: '100px',
            valueField: 'generic_name'
        }, {
            name: 'MRP',
            width: '100px',
            valueField: 'mrp'
        }, {
            name: 'Expiry Date',
            width: '150px',
            valueField: 'expiry'
        }],

        // Event handler for when a list item is selected.
        select: function (event, ui) {
            this.value = (ui.item ? ui.item.name : '');
            //$('#results').text(ui.item ? 'Selected: ' + ui.item.name + ', ' + ui.item.purchase_id + ', ' + ui.item.batch : 'Nothing selected, input was ' + this.value);
            console.log(ui);
            $(this).closest('tr').find('.product-id').val(ui.item.id);
            $(this).closest('tr').find('.qty-value').val(ui.item.ratio);
            $(this).closest('tr').find('.f_igst').val(ui.item.igst);
            $(this).closest('tr').find('.f_cgst').val(ui.item.cgst);
            $(this).closest('tr').find('.f_sgst').val(ui.item.sgst);
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
                    action: "getproduct_purchase"
                },
                // The success event handler will display "No match found" if no items are returned.
                success: function (data) {
                  if(data.length === 0){
                    $(".empty-message0").text("No results found");
                  }else{
                    $(".empty-message0").empty();
                    var result;
                    if (data.length < 0) {
                        result = [{
                            label: 'No match found.'
                        }];
                    } else {
                        result = data;
                    }
                    response(result);
                }
              }
            });
        }
  }); 
});

    