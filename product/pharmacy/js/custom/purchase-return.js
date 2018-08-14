// author : Kartik Champaneriya
// date   : 10-08-2018
$(document).ready(function(){

  $('body').on('click', '.btn-addmore-product', function() {
        var totalproduct = $('.product-tr').length;//for product length
        var html = $('#html-copy').html();
        html = html.replace('##SRPRODUCT##',totalproduct);
       /* html = html.replace('##SRNO##',totalproduct);
        html = html.replace('##SRPRODUCT##',totalproduct);
        html = html.replace('##PRODUCTCOUNT##',totalproduct);*/
        html = html.replace('##PRODUCTCOUNT##',totalproduct);
        html = html.replace('<table>','');
        html = html.replace('</table>','');
        html = html.replace('<tbody>','');
        html = html.replace('</tbody>',''); 
        $('#product-tbody').append(html);
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
        if(totalproduct <= '2'){
          $('.remove_last').show();
        }
  });

  // Remove product button js //

    $('body').on('click', '.btn-remove-product', function(e) {
        e.preventDefault();
        $(this).closest ('tr').remove ();
        //$('.f_amount').trigger("change");
        $('.f_rate').trigger("change");
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

  $('body').on('propertychange change keyup focusout past', '.qty', function() {
      var f_rate = $(this).closest('tr').find('.f_rate').val();
      var qty = $(this).val();
       if(f_rate !== ''&& f_rate !== NaN && f_rate !== "undifined"){
          f_rate = (typeof f_rate !== "undifined" && f_rate !== '' && f_rate !== NaN) ? f_rate : 0;
          qty = (typeof qty !== "undifined" && qty !== '' && qty !== NaN) ? qty : 0;
          var total = (parseInt(qty)*parseInt(f_rate));
       }else{
        var total ="0";
       }
      $(this).closest('tr').find('.ammout').val(total);
      $(this).closest('tr').find('.f_rate').trigger("change");
      //$('.ammout').trigger("change");
  });

  $('body').on('propertychange change keyup focusout past', '.f_rate', function() {
      var qty = $(this).closest('tr').find('.qty').val();
      var f_rate = $(this).val();
       if(qty !== ''&& qty !== NaN && qty !== "undifined"){
          f_rate = (typeof f_rate !== "undifined" && f_rate !== '' && f_rate !== NaN) ? f_rate : 0;
          qty = (typeof qty !== "undifined" && qty !== '' && qty !== NaN) ? qty : 0;
          var total = (parseInt(qty)*parseInt(f_rate));
       }else{
        var total ="0";
       }
       console.log(total);
      $(this).closest('tr').find('.ammout').val(parseFloat(total).toFixed(2));
      $('.ammout').trigger("change");
  });

  $('body').on('propertychange change keyup focusout past', '.discount', function() {
      var totalamount = 0;
      var discount = $(this).val();
      var rate = $(this).closest('tr').find('.rate').val();
      if(rate !== ''&& rate !== NaN && rate !== "undifined"){
        rate = (typeof rate !== "undifined" && rate !== '' && rate !== NaN) ? rate : 0;
        discount = (typeof discount !== "undifined" && discount !== '' && discount !== NaN) ? discount : 0;
        var total = (parseInt(rate)-parseInt(discount));
      }else{
        var total = "0";
      }
      $(this).closest('tr').find('.f_rate').val(total);
      $(this).closest('tr').find('.f_rate').trigger("change");
      //$('.ammout').trigger("change");
  });

  $('body').on('propertychange change keyup focusout past', '.rate', function() {
      var totalamount = 0;
      var rate = $(this).val();
      var discount = $(this).closest('tr').find('.discount').val();
      rate = (typeof rate !== "undifined" && rate !== '' && rate !== NaN) ? rate : 0;
      discount = (typeof discount !== "undifined" && discount !== '' && discount !== NaN) ? discount : 0;
      var total = (parseInt(rate)-parseInt(discount));
      $(this).closest('tr').find('.f_rate').val(total);
      $(this).closest('tr').find('.f_rate').trigger("change");
      //$('.ammout').trigger("change");
    });

});

    