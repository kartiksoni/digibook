$( document ).ready(function() {
  $('body').on('click', '.btn-addmore-product', function() {
        var totalproduct = $('.self-sub-more').length;
        var html = $('#copy-html').html();
        if(totalproduct <= '2'){
          $('.remove_last').show();
        }
        html = html.replace('##PRODUCTCOUNT##',totalproduct);
        $('#self-more').append(html);
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
        $(this).closest('.self-sub-more').find('.product_id').val(ui.item.id);
        $(this).closest('.self-sub-more').find('.purchase_id').val(ui.item.purchase_id);
        $(this).closest('.self-sub-more').find('.batch').val(ui.item.batch);
        $(this).closest('.self-sub-more').find('.qty').val(ui.item.total_qty);
        $(this).closest('.self-sub-more').find('.expiry').val(ui.item.expiry);
        $(this).closest('.self-sub-more').find('.gst').val(ui.item.gst);
        $(this).closest('.self-sub-more').find('.units_strip').val(ui.item.unit);
        $(this).closest('.self-sub-more').find('.price_strip').val(ui.item.count_per);
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
                action: "getproduct_self_changes"
            },
            // The success event handler will display "No match found" if no items are returned.
            success: function (data) {
              if(data.length === 0){
                $(".empty-message"+totalproduct).text("No results found");
              }else{
                $(".empty-message"+totalproduct).empty();
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

  $('body').on('click', '.btn-remove-product', function(e) {
        e.preventDefault();
        $(this).closest ('.self-sub-more').remove ();
  });

  /*$( ".tags" ).autocomplete({
     source: function (query, result) {
          $.ajax({
              url: "ajax.php",
              data: {'query': query, 'action': 'getproduct_self'},            
              dataType: "json",
              type: "POST",
              success: function (data) {
                console.log(data);
                if(data.length === 0){
                  $(".empty-message0").text("No results found");
                }else{
                  $(".empty-message0").empty();
                  result($.map(data, function (item) {
                    return {
                        label: item.name,
                        value: item.id,
                        batch: item.batch,
                        purchase_id: item.purchase_id,
                        qty : item.total_qty,
                        expiry: item.expiry,
                        gst: item.gst,
                        unit: item.unit,
                        count_per: item.count_per  // EDIT
                    }
                  }));
                }
              }
          });
      },
      focus: function( query, result ) {
        $(this).closest('.self-sub-more').find('.tags').val(result.item.label);
        //$( ".tags" ).val( result.item.label );
          return false;
        },
      select: function( query, result ) {
          $(this).closest('.self-sub-more').find('.product_id').val(result.item.value);
          $(this).closest('.self-sub-more').find('.purchase_id').val(result.item.purchase_id);
          $(this).closest('.self-sub-more').find('.batch').val(result.item.batch);
          $(this).closest('.self-sub-more').find('.qty').val(result.item.qty);
          $(this).closest('.self-sub-more').find('.expiry').val(result.item.expiry);
          $(this).closest('.self-sub-more').find('.gst').val(result.item.gst);
          $(this).closest('.self-sub-more').find('.units_strip').val(result.item.unit);
          $(this).closest('.self-sub-more').find('.price_strip').val(result.item.count_per);
          return false;
        }
  });*/

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
        $(this).closest('.self-sub-more').find('.product_id').val(ui.item.id);
        $(this).closest('.self-sub-more').find('.purchase_id').val(ui.item.purchase_id);
        $(this).closest('.self-sub-more').find('.batch').val(ui.item.batch);
        $(this).closest('.self-sub-more').find('.qty').val(ui.item.total_qty);
        $(this).closest('.self-sub-more').find('.expiry').val(ui.item.expiry);
        $(this).closest('.self-sub-more').find('.gst').val(ui.item.gst);
        $(this).closest('.self-sub-more').find('.units_strip').val(ui.item.unit);
        $(this).closest('.self-sub-more').find('.price_strip').val(ui.item.count_per);
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
                action: "getproduct_self_changes"
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
