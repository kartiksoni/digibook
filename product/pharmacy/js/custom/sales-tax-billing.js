$( document ).ready(function() {

  // ON CHABGE CITY TO GET ALL CUSTOMER RELATED TO CITY
    $("#city_id").change(function(){
      var city_id = $(this).val();

      if(city_id != ''){
        $.ajax({
              type: "POST",
              url: 'ajax.php',
              data: {'city_id':city_id, 'action':'getAllCustomerByCity'},
              dataType: "json",
              success: function (data) {console.log(data);
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

  // ON CHABGE CITY TO GET ALL CUSTOMER RELATED TO CITY
    $("#customer_id").change(function(){
      var customer_id = $(this).val();
      if(customer_id != ''){
        $.ajax({
              type: "POST",
              url: 'ajax.php',
              data: {'customer_id':customer_id, 'action':'getCustomerAddressById'},
              dataType: "json",
              success: function (data) {
                if(data.status == true){
                  $('#c_addr_1').val((typeof data.result.addressline1 !== 'undefined') ? data.result.addressline1 : '');
                  $('#c_addr_2').val((typeof data.result.addressline2 !== 'undefined') ? data.result.addressline2 : '');
                  $('#c_addr_3').val((typeof data.result.addressline3 !== 'undefined') ? data.result.addressline3 : '');
                  $('#statecode').val((typeof data.result.statecode !== 'undefined') ? data.result.statecode : '');
                }else{
                  blankAddress();
                }
              },
              error: function () {
                blankAddress();
              }
            });
      }else{
        blankAddress();
      }
  });

    //THIS FUNCTION IS USED TO BLANK ADDRLINE 1 2 AND 3
  function blankAddress(){
    $('#c_addr_1').val(null);
      $('#c_addr_2').val(null);
      $('#c_addr_3').val(null);
  }

  // ON CHANGE BILL TYPE TO CHANGE INVOICE NUMBER
  $(".bill_type").change(function(){
    var bill_type = $("input[name='bill_type']:checked").val();
    $.ajax({
        type: "POST",
        url: 'ajax.php',
        data: {'bill_type':bill_type, 'action':'getInvoiceNo'},
        dataType: "json",
        success: function (data) {
          $('#invoice_no').val(data.result);
        },
        error: function () {
          $('#invoice_no').val(null);
        }
      });
  });


  // save customer to database

  $("#add-customer-form").on("submit", function(event){
        event.preventDefault();
        var data = $(this).serializeArray();
        var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'addcustomer', 'data': data},
            dataType: "json",
            beforeSend: function() {
              $('#btn-addcustomer').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
              $('#btn-addcustomer').prop('disabled', true);
            },
            success: function (data) {
              if(data.status == true){
                htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#add_customer_model').modal('toggle');
                $('#add-customer-form')[0].reset();

                $('#add-customer-form').find('#country').val('').trigger('change');
                $('#add-customer-form').find('#state').val('').trigger('change');
                $('#add-customer-form').find('#city').val('').trigger('change');
              }else{
                htmlerror =  htmlerror.replace("##MSG##", data.message);
                $('#addcustomer-errormsg').html(htmlerror);
                $("html, body").animate({ scrollTop: 0 }, "slow");
              }
              $('#btn-addcustomer').html('Save');
              $('#btn-addcustomer').prop('disabled', false);
            },
            error: function () {
              htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
              $('#addcustomer-errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");

              $('#btn-addcustomer').html('Save');
              $('#btn-addcustomer').prop('disabled', false);
            }
        });

    });

    // ADD MORE ITEM
    $('body').on('click', '.btn-add-more-item', function () {
      var trlength = $('#item-tbody tr').length+1;
      var html = $('#hiddenItemHtml').html();
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
      $('.totalamount').trigger("change");
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

    $('body').on('keyup click', '.product ', function () {
        var $this = $(this);
        var statecode = $('#statecode').val();
        var cur_statecode = $('#cur_statecode').val();
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
                width: '100px',
                valueField: 'batch'
            }, {
                name: 'Generic Name',
                width: '250px;',
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
                
                $(this).closest('tr').find('.product_id').val(ui.item.id);
                $(this).closest('tr').find('.mrp').val(ui.item.mrp);
                $(this).closest('tr').find('.mfg').val(ui.item.mfg_company);
                $(this).closest('tr').find('.batch').val(ui.item.batch);
                $(this).closest('tr').find('.expiry').val(ui.item.expiry);

                $(this).closest('tr').find('.qty').val(ui.item.total_qty);
                $(this).closest('tr').find('.qty_ratio').val(ui.item.ratio);

                var igst = (typeof ui.item.igst != 'undefined' && ui.item.igst != '' && !isNaN(ui.item.igst)) ? ui.item.igst : 0;
                var cgst = (typeof ui.item.cgst != 'undefined' && ui.item.cgst != '' && !isNaN(ui.item.cgst)) ? ui.item.cgst : 0;
                var sgst = (typeof ui.item.sgst != 'undefined' && ui.item.sgst != '' && !isNaN(ui.item.sgst)) ? ui.item.sgst : 0;

                if(statecode == cur_statecode){
                  $(this).closest('tr').find('.c_igst').val(0);
                  $(this).closest('tr').find('.c_cgst').val(cgst);
                  $(this).closest('tr').find('.c_sgst').val(sgst);
                  $(this).closest('tr').find('.gst').val(parseFloat(cgst)+parseFloat(sgst));
                }else{
                  $(this).closest('tr').find('.c_igst').val(igst);
                  $(this).closest('tr').find('.c_cgst').val(0);
                  $(this).closest('tr').find('.c_sgst').val(0);
                  $(this).closest('tr').find('.gst').val(parseFloat(igst));
                }

                //$(this).closest('tr').find('.btn-add-more-item').prop("disabled", false);

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
                        action: "getproduct_purchase_return"
                    },
                    // The success event handler will display "No match found" if no items are returned.
                    success: function (data) {
                      if(data.length === 0){
                        //$(".producterror").text("No results found");
                        $($this).closest('tr').find('.producterror').text("No results found");
                      }else{
                        $($this).closest('tr').find('.producterror').empty();
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
    })


    /*-----------------------------------JS FOR PRODUCT ITEMS COUNT CHARGES START-----------------------------------*/

    $('body').on('change keyup past', '.qty ', function () {
      var qty = $(this).val();
      var rate = $(this).closest('tr').find('.rate').val();
      var discount = $(this).closest('tr').find('.discount').val();
      var gst = $(this).closest('tr').find('.gst').val();

      qty = (qty != '' && !isNaN(qty)) ? parseFloat(qty) : 0;
      rate = (rate != '' && !isNaN(rate)) ? parseFloat(rate) : 0;
      discount = (discount != '' && !isNaN(discount)) ? parseFloat(discount) : 0;
      gst = (gst != '' && !isNaN(gst)) ? parseFloat(gst) : 0;

      var amount = (qty * (rate-discount));
      amount = (amount != '') ? parseFloat(amount) : 0;
      $(this).closest('tr').find('.amount').val(parseFloat(amount).toFixed(2));

      var gstrate = (amount*gst/100);
      gstrate = (gstrate != '') ? parseFloat(gstrate) : 0;

      var totalamount = amount+gstrate;
      $(this).closest('tr').find('.totalamount').val(parseFloat(totalamount).toFixed(2));

      $('.totalamount').trigger("change");
    });

    $('body').on('change keyup past', '.rate ', function () {
        $(this).closest('tr').find('.qty').trigger("change");
    });

    $('body').on('change keyup past', '.discount ', function () {
        $(this).closest('tr').find('.qty').trigger("change");
    });

    $('body').on('change keyup past', '.gst ', function () {
        $(this).closest('tr').find('.qty').trigger("change");
    });

    // count all amount total
    $('body').on('change keyup past', '.totalamount ', function () {
        var totalamount = 0;
        var amount = 0;
        var igst = 0;
        var sgst = 0;
        var cgst = 0;
        var statecode = $('#statecode').val();
        var cur_statecode = $('#cur_statecode').val();
        var length = $('#item-tbody tr').length;

        $('.totalamount').each(function() {
          var val = $.trim( $(this).val() );
          if(val){
              val = parseFloat( val.replace( /^\$/, "" ) );
              totalamount += !isNaN( val ) ? val : 0;
          }
        });
        $('#alltotalamount').val(parseFloat(totalamount).toFixed(2));

        
        $('.amount').each(function() {
          var val = $.trim( $(this).val() );
          if(val){
              val = parseFloat( val.replace( /^\$/, "" ) );
              amount += !isNaN( val ) ? val : 0;
          }
        });

        $('.c_igst').each(function() {
          var val = $.trim( $(this).val() );
          if(val){
              val = parseFloat( val.replace( /^\$/, "" ) );
              igst += !isNaN( val ) ? val : 0;
          }
        });

        $('.c_cgst').each(function() {
          var val = $.trim( $(this).val() );
          if(val){
              val = parseFloat( val.replace( /^\$/, "" ) );
              cgst += !isNaN( val ) ? val : 0;
          }
        });

        $('.c_sgst').each(function() {
          var val = $.trim( $(this).val() );
          if(val){
              val = parseFloat( val.replace( /^\$/, "" ) );
              sgst += !isNaN( val ) ? val : 0;
          }
        });

        var couriercharge = $('#couriercharge').val();

        var courierper = 0;
        var courier = 0;
          if(couriercharge != ''){
            courier = $('#courier').val();
            courier = (courier != '' && !isNaN(courier)) ? parseFloat(courier) : 0;
            
            couriercharge = (!isNaN(couriercharge)) ? couriercharge : 0;
            courierper = (courier*couriercharge/100);
          }
            

          if(statecode == cur_statecode){
            $('#totaligst').val(0);
            finalcgst = cgst/length;
            finalsgst = sgst/length;
            //courierper = courierper/2;

            $('#totalcgst').val(((amount*finalcgst/100)+(courierper/2)).toFixed(2));
            $('#totalsgst').val(((amount*finalsgst/100)+(courierper/2)).toFixed(2));

            $('#totaltaxgst').val((((amount*finalcgst/100)+(courierper/2)) + ((amount*finalsgst/100)+(courierper/2))).toFixed(2));
          }else{
            finaligst = igst/length;
            $('#totaligst').val(((amount*finaligst/100)+courierper).toFixed(2));
            $('#totalcgst').val(0);
            $('#totalsgst').val(0);

            $('#totaltaxgst').val(((amount*finaligst/100)+courierper).toFixed(2));
          }

        var overalldiscount = parseFloat(totalamount)+parseFloat(courierper)+parseFloat(courier);

        var dis_type = $("input[name='discount_type']:checked").val();
        var dis_value = 0;
        if(dis_type == 'per'){
          var dis = $('#discount_per').val();
          dis = (dis != '' && !isNaN(dis)) ? parseFloat(dis) : 0;
          dis_value = (overalldiscount*dis/100);
        }else if(dis_type == 'rs'){
          var dis = $('#discount_rs').val();
          dis = (dis != '' && !isNaN(dis)) ? parseFloat(dis) : 0;
          dis_value = dis;
        }

        var final_overalldiscount = (overalldiscount-dis_value)

        $('#overalldiscount').val(final_overalldiscount.toFixed(2));//For overall discount value

        var cr_db_type = $('#cr_db_type').val();
        var cr_db_val = $('#cr_db_val').val();
        cr_db_val = (cr_db_val != '' && !isNaN(cr_db_val)) ? parseFloat(cr_db_val) : 0;

        if(cr_db_type == 'credit'){
          final_overalldiscount = (final_overalldiscount+cr_db_val);
        }else if(cr_db_type == 'debit'){
          final_overalldiscount = (final_overalldiscount-cr_db_val);
        }

        $('#purchase_amount').val(final_overalldiscount);

        var round_amount = (Math.round(final_overalldiscount)-final_overalldiscount);

        $('#roundoff_amount').val((round_amount).toFixed(2));

        $('#final_amount').val(Math.round(final_overalldiscount).toFixed(2));

    });

    $('body').on('change keyup past', '#courier', function () {
        $('.totalamount').trigger("change");
    });

    $('body').on('change keyup past', '#couriercharge', function () {
      if($(this).val() !== ''){
        $('#courier').removeAttr("readonly");
      }else{
        $('#courier').attr("readonly","");
        $('#courier').val('');
      }
      $('.totalamount').trigger("change");
    });

    $('body').on('change keyup past', '.discount_type, #discount_per, #discount_rs', function () {
        $('.totalamount').trigger("change");
    });

    $('body').on('change keyup past', '#cr_db_type, #cr_db_val', function () {
        $('.totalamount').trigger("change");
    });

    /*-----------------------------------JS FOR PRODUCT ITEMS COUNT CHARGES END-------------------------------------*/


    /*-----------------------------------JS FOR MISSED ORDER START------------------------------------------------*/

    $( "#mis_product" ).autocomplete({
      source: function (query, result) {
          $.ajax({
              url: "ajax.php",
              data: {'query': query, 'type': 'product','action': 'getProductMrpGeneric'},            
              dataType: "json",
              type: "POST",
              success: function (data) {
                if(data.status == true){
                  $("#mis-empty-error").empty();
                    result($.map(data.result, function (item) {
                      return {
                          label: item.name,
                          value: item.id,
                          unit: item.unit,
                      }
                  }));
                }else{
                    $("#mis-empty-error").text("No results found");
                }
              }
          });
        },
        focus: function( query, result ) {
          $( "#mis_product" ).val( result.item.label );
          return false;
        },
        select: function( query, result ) {
            $('#mis_product_id').val(result.item.value);
            $('#mis_unit').val(result.item.unit);
            $('#btn-addmissorder-tmp').prop('disabled', false);
            return false;
        }
    });



    $("#missed-order-tmpform").on("submit", function(event){
      event.preventDefault();
      var data = $(this).serialize();
      var dataarr = JSON.parse('{"' + decodeURI(data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
      var randomnumber = Math.floor((Math.random()*1000) + 1);
      
      var html = $('#missed-hidden-html').html();
      html = html.replace('<table>','');
      html = html.replace('</table>','');
      html = html.replace('<tbody>','');
      html = html.replace('</tbody>','');

      if(typeof dataarr.editid != 'undefined' && dataarr.editid != ''){
        html = html.replace('<tr id="##ID##">', "");
        html = html.replace("</tr>", "");
      }else{
        html = html.replace("##ID##", 'tr-'+randomnumber);
      }

      html = html.replace(/##PRODUCTNAME##/g, (typeof dataarr.mis_product !== 'undefined') ? dataarr.mis_product : '');
      html = html.replace(/##PRODUCTID##/g, (typeof dataarr.mis_product_id !== 'undefined') ? dataarr.mis_product_id : '');
      html = html.replace(/##QTY##/g, (typeof dataarr.mis_qty !== 'undefined') ? dataarr.mis_qty : '');
      html = html.replace(/##UNIT##/g, (typeof dataarr.mis_unit !== 'undefined') ? dataarr.mis_unit : '');

      if($('#missedorder-tmp-tbody tr').length == 0){
        $('#missedorder-table').show();
        $('#btn-addmissorder').prop('disabled', false);
      }

      if(typeof dataarr.editid != 'undefined' && dataarr.editid != ''){
        $('#'+dataarr.editid).html(html);
        $('#'+dataarr.editid).css("background-color", "#FFFFFF");
      }else{
        $('#missedorder-tmp-tbody').append(html);
      }
     
      $('#missed-order-tmpform')[0].reset();
      $('#mis_product_id').val(null);
      $('#editid').val(null);
      $('#btn-addmissorder-tmp').prop('disabled', true);
    });


    $('body').on('click', '.btn-mis-delete', function () {
      $(this).closest('tr').remove();
      var rowCount = $('#missedorder-tmp-tbody tr').length;
        if(rowCount == 0){
          $('#btn-addmissorder').prop('disabled', true);
          $('#missedorder-table').hide();
        }
    });

    $('body').on('click', '.btn-mis-edit', function () {
      var id = $(this).closest ('tr').attr('id');
      var product = $(this).closest ('tr').find('.product').val();
      var product_id = $(this).closest ('tr').find('.product_id').val();
      var qty = $(this).closest ('tr').find('.qty').val();
      var unit = $(this).closest ('tr').find('.unit').val();

      $('#missed-order-tmpform').find('#editid').val(id);
      $('#missed-order-tmpform').find('#mis_product').val(product);
      $('#missed-order-tmpform').find('#mis_product_id').val(product_id);
      $('#missed-order-tmpform').find('#mis_qty').val(qty);
      $('#missed-order-tmpform').find('#mis_unit').val(unit);

      $('#btn-addmissorder-tmp').prop('disabled', false);


      var deletebtn = $(this).closest('tr').find('.btn-mis-delete');
      $(deletebtn).hide();
      $('.btn-mis-delete').not(deletebtn).show();
      $('#missedorder-tmp-tbody tr').css("background-color", "#FFFFFF")
      $('#'+id).css("background-color", "#A9A9A9");
      $("html, body").animate({ scrollTop: 0 }, "slow");
    });


    $("#missed-order-form").on("submit", function(event){
      event.preventDefault();
      var data = $(this).serialize();
      var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';


      $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'addMissOrder', 'data': data},
            dataType: "json",
            beforeSend: function() {
              $('#btn-addmissorder').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
              $('#btn-addmissorder').prop('disabled', true);
            },
            success: function (data) {
              if(data.status == true){
                htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                $('#missedorder-errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#btn-addmissorder').html('Add Order');
                //$('#btn-addmissorder').prop('disabled', false);
                $('#missedorder-tmp-tbody').empty();
                $('#missedorder-table').hide();

                setTimeout(function(){
                    $('#missed-order-model').modal('hide');
                    $('#missedorder-errormsg').empty();
                }, 2000);
              }else{
                htmlerror =  htmlerror.replace("##MSG##", data.message);
                $('#missedorder-errormsg').html(htmlerror);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#btn-addmissorder').html('Add Order');
                $('#btn-addmissorder').prop('disabled', false);
              }
            },
            error: function () {
              htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
              $('#missedorder-errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");

              $('#btn-addmissorder').html('Add Order');
              $('#btn-addmissorder').prop('disabled', false);
            }
        });

    });


    /*-----------------------------------JS FOR MISSED ORDER END--------------------------------------------------*/

});