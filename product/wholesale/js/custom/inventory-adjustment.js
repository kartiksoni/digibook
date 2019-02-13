$( document ).ready(function() {
    $('body').on('click', '.btn-addmore-product', function() {
        var totalproduct = $('.self-sub-more').length;
        var html = $('#copy-html').html();
        html = html.replace('##PRODUCTCOUNT##',totalproduct);
        $('#self-more').append(html);
        if(totalproduct <= '2'){
          $('.remove_last').show();
        }
    });

    $('body').on('click', '.btn-remove-product', function(e) {
        e.preventDefault();
        $(this).closest ('.self-sub-more').remove ();
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

    $('body').on('keyup click', '.tags ', function () {
        var $this = $(this);
        $(this).mcautocomplete({
            // These next two options are what this plugin adds to the autocomplete widget.
            showHeader: true,
            columns: [{
                name: 'Name',
                width: '200px;',
                valueField: 'name'
            }, {
                name: 'Qty',
                width: '100px',
                valueField: 'total_qty'
            }, {
                name: 'Batch',
                width: '200px',
                valueField: 'batch'
            }/*, {
                name: 'Generic Name',
                width: '250px;',
                valueField: 'generic_name'
            }*/, {
                name: 'MRP',
                width: '100px',
                valueField: 'mrp'
            }, {
                name: 'Expiry Date',
                width: '150px',
                valueField: 'expiry'
            }, {
                name: 'GST',
                width: '50px',
                valueField: 'igst'
            }],

            // Event handler for when a list item is selected.
            select: function (event, ui) {
                this.value = (ui.item ? ui.item.name : '');
                //$('#results').text(ui.item ? 'Selected: ' + ui.item.name + ', ' + ui.item.purchase_id + ', ' + ui.item.batch : 'Nothing selected, input was ' + this.value);
                $(this).closest('.self-sub-more').find('.product_id').val(ui.item.id);
                $(this).closest('.self-sub-more').find('.purchase_id').val('');
                $(this).closest('.self-sub-more').find('.batch_no').val(ui.item.batch);
                $(this).closest('.self-sub-more').find('.mrp').val(ui.item.mrp);
                $(this).closest('.self-sub-more').find('.expiry').val(ui.item.expiry);
                $(this).closest('.self-sub-more').find('.mfg_co').val(ui.item.mfg_company);
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
                        action: "searchProductWithoutExpired"
                    },
                    // The success event handler will display "No match found" if no items are returned.
                    success: function (data) {
                      if(data.status == true){
                        $($this).closest('div').find('.producterror').empty();
                        response(data.result)
                      }else{
                        $($this).closest('div').find('.producterror').text("No results found");
                      }
                    }
                });
            }
        });
    }); 
  
});
