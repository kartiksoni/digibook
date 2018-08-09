// author : Kartik Champaneriya
// date   : 28-07-2018
$(document).ready(function(){

    // Add Product button js // 

    $('body').on('click', '.btn-addmore-product', function() {
        var totalproduct = $('.product-tr').length;//for product length
        var html = $('#html-copy').html();
        
        html = html.replace('##SRNO##',totalproduct);
        html = html.replace('##SRPRODUCT##',totalproduct);
        html = html.replace('##PRODUCTCOUNT##',totalproduct);
        html = html.replace('<table>','');
        html = html.replace('</table>','');
        html = html.replace('<tbody>','');
        html = html.replace('</tbody>','');
        $('#product-tbody').append(html);
        if(totalproduct <= '2'){
          $('.remove_last').show();
        }
        $(".product-select"+totalproduct).select2();
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
    });

    

    // End  Add Product button js //

    // Remove product button js //

    $('body').on('click', '.btn-remove-product', function(e) {
        e.preventDefault();
        $(this).closest ('tr').remove ();
        //$('.f_amount').trigger("change");
        $('.f_rate').trigger("change");
    });

    // End Remove product button js // 

    // Auto Compalete For getproduct //

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
    // End Auto Compalete For getproduct //

    // Rate,Discount,rate js //
    // created by kartik champaneriya//

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
      $(this).closest('tr').find('.ammout').val(parseFloat(total).toFixed(2));
      $('.ammout').trigger("change");
    });

    $('body').on('propertychange change keyup focusout past update', '.ammout', function() {
        var totalamount = 0;
        var cgst = 0;
        var sgst = 0;
        var igst = 0;
        var totalcgst = 0;
        var totaligst = 0;
        var totalsgst = 0;
        var statecode = $("#statecode").val();
        var cgst;
        $('.ammout').each(function() {
          var val = $.trim( $(this).val() );
          if(val){
              val = parseFloat( val.replace( /^\$/, "" ) );
              totalamount += !isNaN( val ) ? val : 0;
          }
        });
        if(statecode == "24"){
        /// Code For same State Code  Code ///  
          /// CGST Count Code  ///
          var f_cgst_count = $(".f_cgst").length - 1;
          $('.f_cgst').each(function() {
            var f_cgst = $.trim( $(this).val() );
            if(f_cgst){
                f_cgst = parseFloat( f_cgst.replace( /^\$/, "" ) );
                totalcgst += !isNaN( f_cgst ) ? f_cgst : 0;
            }
          });
          totalcgst = parseFloat(totalcgst);
          f_cgst_count = parseFloat(f_cgst_count);
          var cgst_total_avg = totalcgst/f_cgst_count;
          totalamount = parseFloat(totalamount);
          cgst_total_avg = parseFloat(cgst_total_avg);
          var cgst_total = ((totalamount * cgst_total_avg) / 100);
          $("#total_cgst").val(parseFloat(cgst_total).toFixed(2));
          $("#hidden_total_cgst").val(parseFloat(cgst_total).toFixed(2));

          /// End CGST Count Code ///

          /// SGST Count Code ///
          var f_sgst_count = $(".f_sgst").length - 1;
          $('.f_sgst').each(function() {
            var f_sgst = $.trim( $(this).val() );
            if(f_sgst){
                f_sgst = parseFloat( f_sgst.replace( /^\$/, "" ) );
                totalsgst += !isNaN( f_sgst ) ? f_sgst : 0;
            }
          });

          totalsgst = parseFloat(totalsgst);
          f_sgst_count = parseFloat(f_sgst_count);
          var sgst_total_avg = totalsgst/f_sgst_count;
          totalamount = parseFloat(totalamount);
          sgst_total_avg = parseFloat(sgst_total_avg);
          var sgst_total = ((totalamount * sgst_total_avg) / 100);
          $("#total_sgst").val(parseFloat(sgst_total).toFixed(2));
          $("#hidden_total_sgst").val(parseFloat(sgst_total).toFixed(2));

          /// End SGST Count Code ///


          /// IGST Count Code ///
          $("#total_igst").val(parseFloat(0).toFixed(2));
          $("#hidden_total_igst").val(parseFloat(0).toFixed(2));
          /// End IGST Count Code ///


          /// Total GST Count Code ///
          var final_sgst = parseFloat($("#total_sgst").val());
          var final_cgst = parseFloat($("#total_cgst").val());
          var final_igst = parseFloat($("#total_igst").val());
          var total_gst = final_sgst + final_cgst + final_igst;
          $("#total_tax").val(parseFloat(total_gst).toFixed(2));
          $("#hidden-total_tax").val(parseFloat(total_gst).toFixed(2));
          /// End Total Gst Count Code ///
        /// End Same State Code ///

        }else{
          /// Out State Code  ///

          /// IGST Count Code ///
          var f_igst_count = $(".f_igst").length - 1;
          $('.f_igst').each(function() {
            var f_igst = $.trim( $(this).val() );
            if(f_igst){
                f_igst = parseFloat( f_igst.replace( /^\$/, "" ) );
                totaligst += !isNaN( f_igst ) ? f_igst : 0;
            }
          });

          totaligst = parseFloat(totaligst);
          f_igst_count = parseFloat(f_igst_count);
          var igst_total_avg = totaligst/f_igst_count;
          totalamount = parseFloat(totalamount);
          igst_total_avg = parseFloat(igst_total_avg);
          var igst_total = ((totalamount * igst_total_avg) / 100);
          $("#total_igst").val(parseFloat(igst_total).toFixed(2));
          $("#hidden_total_igst").val(parseFloat(igst_total).toFixed(2));
          /// End IGST Count Code ///

          /// SGST Count Code ///
          $("#total_sgst").val(parseFloat(0).toFixed(2));
          $("#hidden_total_sgst").val(parseFloat(0).toFixed(2));
          /// End SGST Count Code ///

          /// CGST Count Code ///
          $("#total_cgst").val(parseFloat(0).toFixed(2));
          $("#hidden_total_cgst").val(parseFloat(0).toFixed(2));
          /// End CGST Count Code ///

          /// Total GST Count Code ///
          var final_sgst = parseFloat($("#total_sgst").val());
          var final_cgst = parseFloat($("#total_cgst").val());
          var final_igst = parseFloat($("#total_igst").val());
          var total_gst = final_sgst + final_cgst + final_igst;
          $("#total_tax").val(parseFloat(total_gst).toFixed(2));
          $("#hidden-total_tax").val(parseFloat(total_gst).toFixed(2));
          /// End Total Gst Count Code ///



          /// End Out State Code ///

        }
        $('#total_amount').val(parseFloat(totalamount).toFixed(2)); 
        $('.f_discount').trigger("change");
        $('#exampleFormControlSelect2').trigger("change");
        $('#total_courier').trigger("change");
        $('#purchase_amount').trigger("change");
    });

    /// Discount Count js /// 
    $('body').on('change keyup', '.f_discount', function() {
      $('#overall_value').trigger("change");
    });
    /// End Discount Count js ///


    /// Freight/Courier Charge  js ///
    /*$('body').on('change', '#exampleFormControlSelect2', function() {
        var val = $(this).val();
        if(val !== ''){
          var overall_value = $("#overall_value").val();
          if(overall_value !== '' && overall_value !== '0'){
            var o_total = (parseInt(overall_value)*parseInt(val)) / 100;
            var data_overall = (parseInt(overall_value)+parseInt(o_total));
          }else{
            var o_total = "0";
          }
          $('#total_courier').val(parseFloat(data_overall).toFixed(2));
        }
    });*/
    /// Freight/Courier Charge  js ///
    $("#total_courier").prop('disabled', true);
    $('body').on('change', '#courier_charge', function() {
          var courier_value = $(this).val();
          if(courier_value !== ''){
            $("#total_courier").prop('disabled', false);
          }
    });

    $('body').on('ropertychange change keyup focusout past update', '#total_courier', function() {

        var courier_charge = $("#courier_charge").val();
        courier_charge = (typeof courier_charge !== 'undefined' && courier_charge !== '') ? courier_charge : 0;
        var statecode = $("#statecode").val();
        if(statecode == "24"){
          /// SGST Count Code ///
            var total_value = $(this).val();
            total_value = (typeof total_value !== 'undefined' && total_value !== '') ? total_value : 0;
            var courier_charge_s = parseFloat(courier_charge) / 2;

            var stored_sgst = $("#hidden_total_sgst").val();
            var stored_total = $("#hidden-total_tax").val();

            if(stored_sgst !== 0 && stored_sgst !== ''){

              var count_sgst = parseFloat(total_value)*parseFloat(courier_charge_s) / 100;
              var f_count_sgst = parseFloat(count_sgst) + parseFloat(stored_sgst);
              $("#total_sgst").val(parseFloat(f_count_sgst).toFixed(2));
            }else{
              $("#total_sgst").val(parseFloat(0).toFixed(2));
            }

          /// End SGST Count Code ///

          /// CGST Count Code ///

            var stored_cgst = $("#hidden_total_cgst").val();

            if(stored_cgst !== 0 && stored_cgst !== ''){

              var count_cgst = parseFloat(total_value)*parseFloat(courier_charge_s) / 100;
              var f_count_cgst = parseFloat(count_cgst) + parseFloat(stored_cgst);
              $("#total_cgst").val(parseFloat(f_count_cgst).toFixed(2));

            }else{
              $("#total_cgst").val(parseFloat(0).toFixed(2));
            }

          /// End CGST Count Code ///

          /// Total Count Code ///

            var total_count = parseFloat(f_count_sgst) + parseFloat(f_count_cgst);
            $("#total_tax").val(parseFloat(total_count).toFixed(2));

          /// End Total Count Code ///
        }else{

          /// IGST Count Code ///

          var total_value = $(this).val();
          total_value = (typeof total_value !== 'undefined' && total_value !== '') ? total_value : 0;
          //var courier_charge_s = parseFloat(courier_charge) / 2;

          var stored_igst = $("#hidden_total_igst").val();
          var stored_total = $("#hidden-total_tax").val();

          if(stored_igst !== 0 && stored_igst !== ''){

            var count_igst = parseFloat(total_value)*parseFloat(courier_charge) / 100;
            var f_count_igst = parseFloat(count_igst) + parseFloat(stored_igst);
            $("#total_igst").val(parseFloat(f_count_igst).toFixed(2));

          }else{
            $("#total_igst").val(parseFloat(0).toFixed(2));
          }

          /// End IGST Count Code  ///

          /// Total Count Code ///

            $("#total_tax").val(parseFloat(f_count_igst).toFixed(2));

          /// End Total Count Code ///

        }
        $('#overall_value').trigger("change");
    });

    /// Freight/Courier Charge  js ///

    /// End Freight/Courier Charge  js ///


    /// Discount Count js ///
    $('body').on('ropertychange change keyup focusout past update', '#overall_value', function() {
        var Total = $("#total_amount").val();
        Total = (typeof Total !== 'undefined' && Total !== '') ? Total : 0;
        var total_tax = $("#total_tax").val();
        total_tax = (typeof total_tax !== 'undefined' && total_tax !== '') ? total_tax : 0;
        var k_total = parseFloat(Total) + parseFloat(total_tax);

        var type = $('input[name=minimal-radio]:checked').val();
        if(type == "per"){
          //var g1_total = 0;
          console.log(k_total);
          if(k_total !='' && k_total != 0){
            var f_discount = $('.f_discount').val();
            if(f_discount != '' && f_discount != 0){
              var total_bkp = (parseFloat(k_total)*parseFloat(f_discount)) / 100;
              k_total = (parseFloat(k_total)-parseFloat(total_bkp));
              
            }
          }
         //$('#overall_value').val(g1_total.toFixed(2));
        }

        if(type == "rs"){
          //var k_total = 0;
          if(k_total !=='' && k_total !== '0'){
            var f_discount = $('#rs_dis').val();
            console.log("f_discount"+f_discount);
            if(f_discount !== '' && f_discount !== '0'){
               k_total = (parseFloat(k_total)-parseFloat(f_discount));
            }
          }
         //$('#overall_value').val(parseFloat(g_total).toFixed(2));
        }
$("#hidden_total").val(parseFloat(k_total).toFixed(2));
        $("#overall_value").val(parseFloat(k_total).toFixed(2));

        var note_details = $('#note_details').val();
        var note_value = $('#note_value').val();
        note_value = (typeof note_value !== 'undefined' && note_value !== '') ? note_value : 0;
        if(note_details == "credit_note"){
          k_total = (parseFloat(k_total)+parseFloat(note_value));
        }
        if(note_details == "debit_note"){
          k_total = (parseFloat(k_total)-parseFloat(note_value));
        }




         
        $("#purchase_amount").val(parseFloat(k_total).toFixed(2)); 

        var math_round = Math.round(k_total);
         var round_value = math_round - k_total;
         $("#round_off").val(round_value);
         $("#total_total").val(parseFloat(math_round).toFixed(2));

        // $('#purchase_amount').val(k_total);


    });

    /// Creadit Note Js /// 

    $('body').on('ropertychange change keyup focusout past update', '.note_details', function() {
        $('#overall_value').trigger("change");
    });

    /// Creadit Note Js /// 

    /// End Discount Count js ///

    


});







// auther : Gautam Makwana
//date    : 30-7-2018
$( document ).ready(function() {
    $("#city").change(function(){
        var city_id = $(this).val();
        if(city_id !== ''){
            $.ajax({
                type: "POST",
                url: 'ajax.php',
                data: {'city_id':city_id, 'action':'getCityByVendor'},
                dataType: "json",
                success: function (data) {console.log(data);
                  if(data.status == true){
                    $('#vendor').children('option:not(:first)').remove();
                    $.each(data.result, function (i, item) {
                      $('#vendor').append($('<option>', { 
                          value: item.id,
                          text : item.name 
                      }));
                    });
                  }else{
                    $('#vendor').children('option:not(:first)').remove();
                  }
                },
                error: function () {
                  $('#vendor').children('option:not(:first)').remove();
                }
            });
        }else{
          $('#vendor').children('option:not(:first)').remove();
        }
        // $('#vendor').trigger("change");
    });
    
    
    $('#add-vendor').on('change', '#country', function() {
        var country_id = $(this).val();
        if(country_id !== ''){
          $.ajax({
              type: "POST",
              url: 'ajax.php',
              data: {'country_id':country_id, 'action':'getCountryByState'},
              dataType: "json",
              success: function (data) {console.log(data);
                if(data.status == true){
                  $('#state').children('option:not(:first)').remove();
                      $.each(data.result, function (i, item) {
                        $('#state').append($('<option>', { 
                            value: item.id,
                            text : item.name 
                        }));
                      });
                }else{
                  $('#state').children('option:not(:first)').remove();
                }
              },
              error: function () {
                $('#state').children('option:not(:first)').remove();
              }
            });
        }else{
          $('#state').children('option:not(:first)').remove();
        }
      $('#state').trigger("change");
    });

    $('#add-vendor').on('change', '#state', function() {
        var state_id = $(this).val();
        if(state_id !== ''){
          $.ajax({
              type: "POST",
              url: 'ajax.php',
              data: {'state_id':state_id, 'action':'getStateByCity'},
              dataType: "json",
              success: function (data) {
                if(data.status == true){
                  $('#vendorcity').children('option:not(:first)').remove();
                  $.each(data.result, function (i, item) {
                      $('#vendorcity').append($('<option>', { 
                          value: item.id,
                          text : item.name 
                      }));
                  });
                }else{
                    $('#vendorcity').children('option:not(:first)').remove();
                  }
                },
              error: function () {
                $('#vendorcity').children('option:not(:first)').remove();
              }
          });
        }else{
          $('#vendorcity').children('option:not(:first)').remove();
        }
    });


    $("#add-vendor").on("submit", function(event){
        event.preventDefault();
        var data = $(this).serializeArray();
        var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';

        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'addvendor', 'data': data},
            dataType: "json",
            beforeSend: function() {
              $('#btn-addvendor').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
              $('#btn-addvendor').prop('disabled', true);
            },
            success: function (data) {
              if(data.status == true){
                htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#purchase-addvendormodel').modal('toggle');
                $('#city').trigger("change");
                $('#add-vendor')[0].reset();
                
                
                $.ajax({
                  type: "POST",
                  url: 'ajax.php',
                  data: {'action':'getAllVendorCity'},
                  dataType: "json",
                  success: function (data) {console.log(data);
                    if(data.status == true){
                        $('#city').children('option:not(:first)').remove();
                        $.each(data.result, function (i, item) {
                            $('#city').append($('<option>', { 
                                value: item.id,
                                text : item.name 
                            }));
                        });
                    }else{
                      $('#city').children('option:not(:first)').remove();
                    }
                  },
                  error: function () {
                    $('#city').children('option:not(:first)').remove();
                  }
                });
                
                
                
              }else{
                htmlerror =  htmlerror.replace("##MSG##", data.message);
                $('#addvendor-errormsg').html(htmlerror);
                $("html, body").animate({ scrollTop: 0 }, "slow");
              }
              $('#btn-addvendor').html('Save');
              $('#btn-addvendor').prop('disabled', false);
            },
            error: function () {
              htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
              $('#addvendor-errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");

              $('#btn-addvendor').html('Save');
              $('#btn-addvendor').prop('disabled', false);
            }
        });

    });
    
    $("#add-product").on("submit", function(event){
        event.preventDefault();
        var data = $(this).serialize();
        var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';

        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'addproduct', 'data': data},
            dataType: "json",
            beforeSend: function() {
              $('#btn-addproduct').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
              $('#btn-addproduct').prop('disabled', true);
            },
            success: function (data) {
              if(data.status == true){
                htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#purchase-addproductmodel').modal('toggle');
                $('#add-product')[0].reset();
              }else{
                htmlerror =  htmlerror.replace("##MSG##", data.message);
                $('#addproduct-errormsg').html(htmlerror);
                $("html, body").animate({ scrollTop: 0 }, "slow");
              }
              $('#btn-addproduct').html('Save');
              $('#btn-addproduct').prop('disabled', false);
            },
            error: function () {
              htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
              $('#addvendor-errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");

              $('#btn-addproduct').html('Save');
              $('#btn-addproduct').prop('disabled', false);
            }
        });

    });
    
    $("#vendor").change(function(){
        var vendor_id = $(this).val();
        if(vendor_id !== ''){
            $.ajax({
                type: "POST",
                url: 'ajax.php',
                data: {'vendor_id':vendor_id, 'action':'getStatecodeByVendor'},
                dataType: "json",
                success: function (data) {
                  if(data.status == true){
                    $('#statecode').val(data.result.statecode);
                  }else{
                    $('#statecode').val('');
                  }
                },
                error: function () {
                  $('#statecode').val('');
                }
            });
        }else{
          $('#statecode').val('');
        }
    });

    $("#vendor").change(function(){
      var vendor_id = $(this).val();
      if(vendor_id !== ''){
          $.ajax({
                type: "POST",
                url: 'ajax.php',
                data: {'vendor_id':vendor_id, 'action':'getPoiByVendor'},
                dataType: "json",
                beforeSend: function() {
                    $('.vendor-loader').show();
                },
                success: function (data) {
                    $('.vendor-loader').hide();
                  if(data.status == true){
                    var html = $('#poi-tr-html').html();
                    html = html.replace("<table>", "");
                    html = html.replace("</table>", "");
                    html = html.replace("<tbody>", "");
                    html = html.replace("</tbody>", "");

                    var finalhtml = '';
                    $.each(data.result, function (i, item) {
                      var tmphtml = html;
                      tmphtml = tmphtml.replace("##SRNO##",i+1);
                      tmphtml = tmphtml.replace("##DATE##",item.date);
                      tmphtml = tmphtml.replace("##ORDER##",item.order_no);
                      tmphtml = tmphtml.replace("##PRODUCTNAME##",item.product_name);
                      tmphtml = tmphtml.replace("##PRODUCTID##",item.product_id);
                      tmphtml = tmphtml.replace("##GENERIC##",item.generic_name);
                      tmphtml = tmphtml.replace("##MFG##",item.mfg_company);
                      tmphtml = tmphtml.replace("##PURCHASEPRICE##",item.purchase_price);
                      tmphtml = tmphtml.replace("##GST##",item.gst);
                      tmphtml = tmphtml.replace("##UNIT##",item.unit);
                      tmphtml = tmphtml.replace("##QTY##",item.qty);
                      tmphtml = tmphtml.replace("##POIID##",item.id);
                      tmphtml = tmphtml.replace("##TABLE##",item.table);
                      finalhtml += tmphtml;
                    });
                    
                    $('#poi-body').empty();
                    $('#poi-body').html(finalhtml);
                    $('#poi-model').modal('show');
                  }else{
                    return false;
                  }
                },
                error: function () {
                    $('.vendor-loader').hide();
                    return false;
                }
            });
      }else{
        return false;
      }
    });

    $("#btn-addpoi").click(function(){
      var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      
      var i = 1;
      var data = [];
        $("input:checkbox[class=poi-checkbox]:checked").each(function (){
            var tmparray = [];
            tmparray['date'] = $(this).closest('tr').find('.poi-date').html();
            tmparray['product_name'] = $(this).closest('tr').find('.poi-pname').html();
            tmparray['product_id'] = $(this).closest('tr').find('.poi-pid').val();
            tmparray['generic_name'] = $(this).closest('tr').find('.poi-gname').html();
            tmparray['mfg_name'] = $(this).closest('tr').find('.poi-mfg').html();
            tmparray['purchase_price'] = $(this).closest('tr').find('.poi-pprice').html();
            tmparray['gst'] = $(this).closest('tr').find('.poi-gst').html();
            tmparray['unit'] = $(this).closest('tr').find('.poi-unit').html();
            tmparray['qty'] = $(this).closest('tr').find('.poi-qty').html();
            tmparray['id'] = $(this).closest('tr').find('.poi-id').val();
            tmparray['table'] = $(this).closest('tr').find('.poi-table').val();
            data.push(tmparray);
        });

      if(data.length > 0){
        
        html = $('#html-copy').html();
        html = html.replace("<table>", "");
        html = html.replace("</table>", "");
        html = html.replace("<tbody>", "");
        html = html.replace("</tbody>", "");

        $('#product-tbody').empty();
          var state_code = $('#statecode').val();
          console.log('state_code - '+state_code);
          $.each(data, function (i, item) {
              tmphtml = html;
              tmphtml = tmphtml.replace("##SRNO##",i+1);
              var append = $('#product-tbody').append(tmphtml);

              //$('#product-tbody tr:last').find('.btn-remove-product').hide(); 
              $('#product-tbody tr:last').find('.tags').val(item.product_name);
              $('#product-tbody tr:last').find('.product-id').val(item.product_id);
              $('#product-tbody tr:last').find('.mrp').val(item.purchase_price);
              $('#product-tbody tr:last').find('.mfg_co').val(item.mfg_name);
              $('#product-tbody tr:last').find('.qty').val(item.qty);

              if(state_code == 24){
                $('#product-tbody tr:last').find('.f_cgst').val(item.gst/2);
                $('#product-tbody tr:last').find('.f_sgst').val(item.gst/2);
                $('#product-tbody tr:last').find('.f_igst').val(0);
              }else{
                $('#product-tbody tr:last').find('.f_cgst').val(0);
                $('#product-tbody tr:last').find('.f_sgst').val(0);
                $('#product-tbody tr:last').find('.f_igst').val(item.gst);
              }
              $('#product-tbody tr:last').find('.f_poi_id').val(item.id);
              $('#product-tbody tr:last').find('.f_poi_table').val(item.table);
          });

        $('#poi-model').modal('hide');

      }else{
        htmlerror =  htmlerror.replace("##MSG##", 'Please select at least one item!');
        $('#poi-error').html(htmlerror);
        return false;
      }

    });
    
    $(".purchase_type").change(function(){
      var purchase_type = $(this).val();

        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'purchase_type':purchase_type, 'action':'getVoucherNoByType'},
            dataType: "json",
            success: function (data) {
              if(data.status == true){
                $('#voucher_no').val(data.result);
              }else{
                $('#voucher_no').val('');
              }
            },
            error: function () {
              $('#voucher_no').val('');
            }
        });
    });
    
});
    
    
    
    
    