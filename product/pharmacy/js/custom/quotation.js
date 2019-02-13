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
        // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        
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
                 showSuccessToast(data.message);
                // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                // $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#add_customer_model').modal('toggle');
                $('#add-customer-form')[0].reset();

                $('#add-customer-form').find('#country').val('').trigger('change');
                $('#add-customer-form').find('#state').val('').trigger('change');
                $('#add-customer-form').find('#city').val('').trigger('change');
              }else{
                showDangerToast(data.message);
               // htmlerror =  htmlerror.replace("##MSG##", data.message);
               // $('#addcustomer-errormsg').html(htmlerror);
                $("html, body").animate({ scrollTop: 0 }, "slow");
              }
              $('#btn-addcustomer').html('Save');
              $('#btn-addcustomer').prop('disabled', false);
            },
            error: function () {
              showDangerToast('Somthing Want Wrong! Try again.');
             // htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
             // $('#addcustomer-errormsg').html(htmlerror);
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
      var return_length = $('#item-return-tbody tr').length;
      if(return_length == 0 || return_length == ''){
        $('#return-table').hide();
      }
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
                $(this).closest('tr').find('.ptr').val(ui.item.ptr);
                $(this).closest('tr').find('.discount').val(ui.item.discount);
                $(this).closest('tr').find('.rate').val(ui.item.rate);

                //$(this).closest('tr').find('.qty').val(ui.item.total_qty);
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
                        action: "searchProductWithoutExpired"
                    },
                    // The success event handler will display "No match found" if no items are returned.
                    success: function (data) {
                        if(data.status == true){
                            $($this).closest('tr').find('.producterror').empty();
                            response(data.result)
                        }else{
                            $($this).closest('tr').find('.producterror').text("No results found");
                        }
                    },error: function () {
                      $($this).closest('tr').find('.producterror').text("No results found");
                    }
                });
            }
        });
    })


    /*-----------------------------------JS FOR PRODUCT ITEMS COUNT CHARGES START-----------------------------------*/

    $('body').on('change keyup past', '.qty ', function () {
      var qty = $(this).val();
      var rate = $(this).closest('tr').find('.rate').val();
      // Changes By Kartik /// 
      //var discount = $(this).closest('tr').find('.discount').val();
      var gst = $(this).closest('tr').find('.gst').val();

      var ptr = $(this).closest('tr').find('.ptr').val();
      ptr = (ptr != '' && !isNaN(ptr)) ? parseFloat(ptr) : 0;
      var discount = $(this).closest('tr').find('.discount').val();
      var total_p = ptr * discount / 100;
      var total_ptr = ptr - total_p;
      $(this).closest('tr').find('.rate').val(parseFloat(total_ptr).toFixed(2));

      //// changes By kartik ///
      qty = (qty != '' && !isNaN(qty)) ? parseFloat(qty) : 0;
      rate = (rate != '' && !isNaN(rate)) ? parseFloat(rate) : 0;
      //discount = (discount != '' && !isNaN(discount)) ? parseFloat(discount) : 0;
      gst = (gst != '' && !isNaN(gst)) ? parseFloat(gst) : 0;

      var amount = (qty * (rate));
      amount = (amount != '') ? parseFloat(amount) : 0;

    /*  $(this).closest('tr').find('.amount').val(parseFloat(amount).toFixed(2));*/

      /*var gstrate = (amount*gst/100);
      gstrate = (gstrate != '') ? parseFloat(gstrate) : 0;*/

      /*var totalamount = amount+gstrate;*/
      $(this).closest('tr').find('.totalamount').val(parseFloat(amount).toFixed(2));

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
        var total_gst = 0;
        var igst = 0;
        var sgst = 0;
        var cgst = 0;
        var statecode = $('#statecode').val();
        var cur_statecode = $('#cur_statecode').val();
        var length = $('#item-tbody tr').length;
        var total_return_gst = 0;

        $('.totalamount').each(function() {
          var val = $.trim( $(this).val() );
          if(val){
              val = parseFloat( val.replace( /^\$/, "" ) );
              totalamount += !isNaN( val ) ? val : 0;

              /// Changes By kartik /// 
              var temp_gst = $(this).closest('tr').find('.gst').val();
              temp_gst = (!isNaN( temp_gst ) && temp_gst != '') ? temp_gst : 0;
              var temp_amount =  (!isNaN( val ) && val != '') ? val : 0;
              total_gst += (temp_gst * temp_amount) / 100;

          }
        });
        var totalamount_without_return = totalamount;
        $('#alltotalamount').val(parseFloat(totalamount).toFixed(2));

        var total_return_amount = 0;
        var return_length = $('#item-return-tbody').find('.r_amount').length;

        if(return_length != '' && return_length > 0){
          $('.r_amount').each(function() {
            var val = $.trim( $(this).val() );
            if(val){
                val = parseFloat( val.replace( /^\$/, "" ) );
                total_return_amount += !isNaN( val ) ? val : 0;
            }
            var return_qty = $(this).closest('tr').find('.r_qty').val();
            return_qty = (!isNaN(return_qty) && return_qty != '') ? return_qty : 0;

            var return_rate = $(this).closest('tr').find('.r_rate').val();
            return_rate = (!isNaN(return_rate) && return_rate != '') ? return_rate : 0;

            var return_gst_per = $(this).closest('tr').find('.r_gst').val();
            return_gst_per = (!isNaN(return_gst_per) && return_gst_per != '') ? return_gst_per : 0;

            var return_amount = (return_qty*return_rate);
            return_amount = (!isNaN(return_amount) && return_amount != '') ? return_amount : 0;

            total_return_gst += (return_amount*return_gst_per/100);
          });
        }
        

        $('#total_return_amount').val(total_return_amount.toFixed(2));
        totalamount = (totalamount-total_return_amount);
        
        $('.amount').each(function() {
          var val = $.trim( $(this).val() );
          if(val){
              val = parseFloat( val.replace( /^\$/, "" ) );
              amount += !isNaN( val ) ? val : 0;
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
            //var totalgst = totalamount_without_return-amount;
            cgst = (total_gst/2) - (total_return_gst/2);
            sgst = (total_gst/2) - (total_return_gst/2);
            //courierper = courierper/2;

            $('#totalcgst').val(((cgst)+(courierper/2)).toFixed(2));
            $('#totalsgst').val(((sgst)+(courierper/2)).toFixed(2));
            
            $('#totaltaxgst').val(((total_gst-total_return_gst) + (courierper)).toFixed(2));
          }else{
            //var totalgst = totalamount_without_return-amount;
            $('#totaligst').val(((total_gst-total_return_gst)+courierper).toFixed(2));
            $('#totalcgst').val(0);
            $('#totalsgst').val(0);

            $('#totaltaxgst').val(((total_gst-total_return_gst) + (courierper)).toFixed(2));
          }

        var overalldiscount = parseFloat(totalamount)+parseFloat(courierper)+parseFloat(courier)+parseFloat(total_gst);

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


    /*-----------------------------------JS FOR SAVE NEW PRODUCT UNDER MISSED ORDER START--------------------------------------------------*/

    $("#add-product").on("submit", function(event){
      event.preventDefault();
      var data = $(this).serialize();
      var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      var dataarr = JSON.parse('{"' + decodeURI(data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');

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
            $('#missedorder-errormsg').html(htmlsuccess);
            $("html, body").animate({ scrollTop: 0 }, "slow");
            $('#purchase-addproductmodel').modal('toggle');
            
            $('#add-product')[0].reset();

            $('#mis_product_id').val(data.result);
            $('#mis_product').val(dataarr.product_name);
            $('#mis_unit').val(dataarr.unit);
            
            $('#btn-addmissorder-tmp').prop('disabled', false);
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
          $('#addproduct-errormsg').html(htmlerror);
          $("html, body").animate({ scrollTop: 0 }, "slow");

          $('#btn-addproduct').html('Save');
          $('#btn-addproduct').prop('disabled', false);
        }
      });
    });

    /*-----------------------------------JS FOR SAVE NEW PRODUCT UNDER MISSED ORDER END--------------------------------------------------*/


    /*-----------------------------------JS FOR ALTERNATE PRODUCT SEARCH START------------------------------------------------*/
    $('body').on('change click keyup', '.alt-input', function () {
      var searchby = $(this).attr('data-name');
      var $this = $(this);
        $(this).autocomplete({
          source: function (query, result) {
              $.ajax({
                  url: "ajax.php",
                  data: {'query': query, 'type': searchby,'action': 'getAlternativeProduct'},            
                  dataType: "json",
                  type: "POST",
                  success: function (data) {
                    
                    if(data.status == true){
                      $(".alt-empty").empty();
                        result($.map(data.result, function (item) {
                          return {
                              label: item.name,
                              value: item.id,
                              products: item.totalproducts
                          }
                      }));
                    }else{
                        $($this).closest('div').find('.alt-empty').text("No results found");
                    }
                  }
              });
            },
            focus: function( query, result ) {
              $($this).val( result.item.label );
              return false;
            },
            select: function( query, result ) {
              if(typeof result.item.products !== 'undefined' && result.item.products.length > 0){
                var html = $('#hidden-alternate').html();
                html = html.replace('<table>','');
                html = html.replace('</table>','');
                html = html.replace('<tbody>','');
                html = html.replace('</tbody>','');
                $.each(result.item.products, function (i, item) {
                    var finalhtml = html;
                    finalhtml = finalhtml.replace(/##PRODUCTID##/g, (typeof item.id !== 'undefined') ? item.id : '');
                    finalhtml = finalhtml.replace(/##PRODUCTNAME##/g, (typeof item.product_name !== 'undefined') ? item.product_name : '');
                    finalhtml = finalhtml.replace(/##GENERICNAME##/g, (typeof item.generic_name !== 'undefined') ? item.generic_name : '');
                    finalhtml = finalhtml.replace(/##MFG##/g, (typeof item.mfg_company !== 'undefined') ? item.mfg_company : '');
                    finalhtml = finalhtml.replace(/##EXPDATE##/g, (typeof item.ex_date !== 'undefined') ? item.ex_date : '');
                    finalhtml = finalhtml.replace(/##BATCH##/g, (typeof item.batch_no !== 'undefined') ? item.batch_no : '');
                    finalhtml = finalhtml.replace(/##MRP##/g, (typeof item.mrp !== 'undefined') ? item.mrp : '');
                    finalhtml = finalhtml.replace(/##UNIT##/g, (typeof item.unit !== 'undefined') ? item.unit : '');
                    finalhtml = finalhtml.replace(/##IGST##/g, (typeof item.igst !== 'undefined') ? item.igst : '');
                    finalhtml = finalhtml.replace(/##CGST##/g, (typeof item.cgst !== 'undefined') ? item.cgst : '');
                    finalhtml = finalhtml.replace(/##SGST##/g, (typeof item.sgst !== 'undefined') ? item.sgst : '');
                    finalhtml = finalhtml.replace(/##RATIO##/g, (typeof item.ratio !== 'undefined') ? item.ratio : '');
                    finalhtml = finalhtml.replace(/##PTR##/g, (typeof item.ptr !== 'undefined') ? item.ptr : '0');
                    finalhtml = finalhtml.replace(/##DISCOUNT##/g, (typeof item.discount !== 'undefined') ? item.discount : '0');
                    finalhtml = finalhtml.replace(/##RATE##/g, (typeof item.rate !== 'undefined') ? item.rate : '0');
                    $('#alternate-tbody').append(finalhtml);
                });
                $('.alt-input').val(null);
                
              }
              return false;
            }
        });
    });

    $('body').on('click', '#btn-alternate', function () {
      $('#alternate-tbody').empty();
    });

    $('body').on('click', '.alt_product_id', function () {
      if($(this).is(':checked')){
        $(this).closest('tr').css("background-color", "#A9A9A9");
      }else{
        $(this).closest('tr').css("background-color", "#FFFFFF");
      }
        
      var length = $(".alt_product_id:checked").length;
      if(length > 0){
        $('#btn-add-alternate').prop('disabled', false);
      }else{
        $('#btn-add-alternate').prop('disabled', true);
      }
    });


    $("#btn-add-alternate").click(function(){
      var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';

       var data = [];
        $("input:checkbox[class=alt_product_id]:checked").each(function (){
            var tmparray = [];
            tmparray['product_id'] = $(this).val();
            tmparray['product_name'] = $(this).closest('tr').find('.alt_product_name').html();
            tmparray['generic_name'] = $(this).closest('tr').find('.alt_product_generic').html();
            tmparray['mfg'] = $(this).closest('tr').find('.alt_product_mfg').html();
            tmparray['expiry'] = $(this).closest('tr').find('.alt_product_expiry').html();
            tmparray['batch'] = $(this).closest('tr').find('.alt_product_batch').html();
            tmparray['mrp'] = $(this).closest('tr').find('.alt_product_mrp').html();
            tmparray['unit'] = $(this).closest('tr').find('.alt_product_unit').html();
            tmparray['igst'] = $(this).closest('tr').find('.alt_product_igst').val();
            tmparray['cgst'] = $(this).closest('tr').find('.alt_product_cgst').val();
            tmparray['sgst'] = $(this).closest('tr').find('.alt_product_sgst').val();
            tmparray['ratio'] = $(this).closest('tr').find('.alt_product_ratio').val();
            tmparray['ptr'] = $(this).closest('tr').find('.alt_product_ptr').val();
            tmparray['discount'] = $(this).closest('tr').find('.alt_product_discount').val();
            tmparray['rate'] = $(this).closest('tr').find('.alt_product_rate').val();
            data.push(tmparray);
        });

        if(data.length > 0){
        
          html = $('#hiddenItemHtml').html();
          html = html.replace("<table>", "");
          html = html.replace("</table>", "");
          html = html.replace("<tbody>", "");
          html = html.replace("</tbody>", "");

        
          var state_code = $('#statecode').val();
          var curstate_code = $('#cur_statecode').val();
          var tr = $('#item-tbody tr:first').find('.product_id').val();
          if(tr == ''){
            $('#item-tbody').empty();
          }
          var tbllength = $("#item-tbody tr").length;
          $.each(data, function (i, item) {
              tmphtml = html;
              tmphtml = tmphtml.replace("##SRNO##",tbllength+i+1);
              var append = $('#item-tbody').append(tmphtml);

              //$('#product-tbody tr:last').find('.btn-remove-product').hide(); 
              $('#item-tbody tr:last').find('.product').val(item.product_name);
              $('#item-tbody tr:last').find('.product_id').val(item.product_id);
              $('#item-tbody tr:last').find('.mrp').val(item.mrp);
              $('#item-tbody tr:last').find('.mfg').val(item.mfg);
              $('#item-tbody tr:last').find('.batch').val(item.batch);
              $('#item-tbody tr:last').find('.expiry').val(item.expiry);
              $('#item-tbody tr:last').find('.qty_ratio').val(item.ratio);
              $('#item-tbody tr:last').find('.ptr').val(item.ptr);
              $('#item-tbody tr:last').find('.discount').val(item.discount);
              $('#item-tbody tr:last').find('.rate').val(item.rate);

              var igst = (typeof item.igst != 'undefined' && item.igst != '') ? parseFloat(item.igst) : 0;
              var cgst = (typeof item.cgst != 'undefined' && item.cgst != '') ? parseFloat(item.cgst) : 0;
              var sgst = (typeof item.sgst != 'undefined' && item.sgst != '') ? parseFloat(item.sgst) : 0;

              if(state_code == curstate_code){
                $('#item-tbody tr:last').find('.c_igst').val(0);
                $('#item-tbody tr:last').find('.c_cgst').val(cgst);
                $('#item-tbody tr:last').find('.c_sgst').val(sgst);

                $('#item-tbody tr:last').find('.gst').val(cgst+sgst);
              }else{
                $('#item-tbody tr:last').find('.c_igst').val(igst);
                $('#item-tbody tr:last').find('.c_cgst').val(0);
                $('#item-tbody tr:last').find('.c_sgst').val(0);

                $('#item-tbody tr:last').find('.gst').val(igst);
              }

              if(tr == '' && i == 0){
                $('#item-tbody tr:last').find('.btn-remove-item').hide();
              }
          });

        $('#alternate_product_model').modal('hide');

      }else{
        htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong! Try Again.');
        $('#alternate-error').html(htmlerror);
        return false;
      }

    });

    /*-----------------------------------JS FOR ALTERNATE PRODUCT SEARCH END--------------------------------------------------*/


    /* -------------------------------------------------JS FOR SAVE & RETURN START----------------------------------------------------- */

    $( "#btn-saveandreturn" ).click(function() {
      $('#sar-tbody').empty();
      $('#btn-add-return').prop("disabled", true);
      $('#saveandreturn-tmp-form')[0].reset();
      $('#saveandreturn-tmp-form').find('input[type=hidden]').val(null);
      $('#btn-tmp-return').prop('disabled', true);
    });

    $('body').on('keyup click', '#sar_product ', function () {
        var $this = $(this);
        var statecode = $('#statecode').val();
        var cur_statecode = $('#cur_statecode').val();
        $(this).mcautocomplete({
        // These next two options are what this plugin adds to the autocomplete widget.
            showHeader: true,
            columns: [{
                name: 'Product Name',
                width: '200px;',
                valueField: 'product_name'
            }, {
                name: 'Generic Name',
                width: '200px',
                valueField: 'generic_name'
            }, {
                name: 'Batch',
                width: '100px',
                valueField: 'batch_no'
            }, {
                name: 'Qty',
                width: '100px;',
                valueField: 'qty'
            }, {
                name: 'amount',
                width: '100px',
                valueField: 'totalamount'
            }, {
                name: 'Expiry Date',
                width: '150px',
                valueField: 'ex_date'
            }],

            // Event handler for when a list item is selected.
            select: function (event, ui) {
                this.value = (ui.item ? ui.item.product_name : '');
                //$('#results').text(ui.item ? 'Selected: ' + ui.item.name + ', ' + ui.item.purchase_id + ', ' + ui.item.batch : 'Nothing selected, input was ' + this.value);
                
                $('#saveandreturn-tmp-form').find('#sar_product_id').val(ui.item.id);
                $('#saveandreturn-tmp-form').find('#sar_batch').val(ui.item.batch_no);
                $('#saveandreturn-tmp-form').find('#sar_expiry').val(ui.item.ex_date);
                $('#saveandreturn-tmp-form').find('#sar_mfg_co').val(ui.item.mfg_company);

                $('#saveandreturn-tmp-form').find('#sar_igst').val(ui.item.igst);
                $('#saveandreturn-tmp-form').find('#sar_cgst').val(ui.item.cgst);
                $('#saveandreturn-tmp-form').find('#sar_sgst').val(ui.item.sgst);
                $('#saveandreturn-tmp-form').find('#sar_gst').val(ui.item.gst);
                $('#saveandreturn-tmp-form').find('#sar_mrp').val(ui.item.mrp);
                $('#saveandreturn-tmp-form').find('#sar_rate').val(ui.item.rate);

                $('#btn-tmp-return').prop("disabled", false);
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
                        customer_id : $('#customer_id').val(),
                        action: "getProductForCustomerReturn"
                    },
                    // The success event handler will display "No match found" if no items are returned.
                    success: function (data) {
                      if(data.status == true){
                        $($this).closest('div').find('.sar_product_error').empty();
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
                        $($this).closest('div').find('.sar_product_error').text("No results found");
                      }

                    }
                });
            }
        });
    });
    
    $('body').on('click', '#btn-tmp-return-clear', function () {
      var editid = $('#saveandreturn-tmp-form').find('#editid').val();
      if(editid != ''){
        $('#'+editid).css("background-color", "#FFFFFF");
        $('#'+editid).find('.btn-sar-delete').show();
      }

      $('#btn-tmp-return').attr("disabled", "");
      $('#saveandreturn-tmp-form')[0].reset();
      $('#saveandreturn-tmp-form').find('input[type=hidden]').val(null);
    });


    $("#saveandreturn-tmp-form").on("submit", function(event){
      event.preventDefault();
      var data = $(this).serialize();
      var dataarr = JSON.parse('{"' + decodeURI(data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
      var randomnumber = Math.floor((Math.random()*1000) + 1);

      

      
      var html = $('#sar-hidden-html').html();
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
      

      html = html.replace(/##PRODUCTID##/g, (typeof dataarr.sar_product_id !== 'undefined') ? dataarr.sar_product_id : '');
      html = html.replace(/##PRODUCTNAME##/g, (typeof dataarr.sar_product !== 'undefined') ? dataarr.sar_product : '');
      html = html.replace(/##BATCH##/g, (typeof dataarr.sar_batch !== 'undefined') ? dataarr.sar_batch : '');
      html = html.replace(/##EXPIRY##/g, (typeof dataarr.sar_expiry !== 'undefined') ? decodeURIComponent(dataarr.sar_expiry) : '');
      html = html.replace(/##QTY##/g, (typeof dataarr.sar_qty !== 'undefined') ? dataarr.sar_qty : '');
      html = html.replace(/##RATE##/g, (typeof dataarr.sar_rate !== 'undefined') ? dataarr.sar_rate : '');
      html = html.replace(/##DISC##/g, (typeof dataarr.sar_discount !== 'undefined') ? dataarr.sar_discount : '');
      html = html.replace(/##GST##/g, (typeof dataarr.sar_gst !== 'undefined') ? dataarr.sar_gst : '');
      html = html.replace(/##IGST##/g, (typeof dataarr.sar_igst !== 'undefined') ? dataarr.sar_igst : '');
      html = html.replace(/##CGST##/g, (typeof dataarr.sar_cgst !== 'undefined') ? dataarr.sar_cgst : '');
      html = html.replace(/##SGST##/g, (typeof dataarr.sar_sgst !== 'undefined') ? dataarr.sar_sgst : '');
      html = html.replace(/##MRP##/g, (typeof dataarr.sar_mrp !== 'undefined') ? dataarr.sar_mrp : '');
      html = html.replace(/##AMOUNT##/g, (typeof dataarr.sar_amount !== 'undefined') ? dataarr.sar_amount : '');
      html = html.replace(/##MFG##/g, (typeof dataarr.sar_mfg_co !== 'undefined') ? dataarr.sar_mfg_co : '');

      if($('#sar-tbody tr').length == 0){
        $('#sar-table').show();
        $('#btn-add-return').prop('disabled', false);
      }

      if(typeof dataarr.editid != 'undefined' && dataarr.editid != ''){
        $('#'+dataarr.editid).html(html);
        $('#'+dataarr.editid).css("background-color", "#FFFFFF");
      }else{
        $('#sar-tbody').append(html);
      }
     
      $('#saveandreturn-tmp-form')[0].reset();
      $('#saveandreturn-tmp-form').find('input[type=hidden]').val(null);
      $('#btn-tmp-return').prop('disabled', true);
    });

    $('body').on('click', '.btn-sar-delete', function () {
      $(this).closest('tr').remove();
      if($('#sar-tbody tr').length == 0){
        $('#sar-table').hide();
        $('#btn-add-return').prop('disabled', true);
      }
    });

    $('body').on('click', '.btn-sar-edit', function () {
      // get value
        var editid = $(this).closest('tr').attr('id');
        var product_id = $(this).closest('tr').find('.td-sr-product-id').val();
        var product_name = $(this).closest('tr').find('.td-sr-product').html();
        var batch = $(this).closest('tr').find('.td-sr-batch').html();
        var expiry = $(this).closest('tr').find('.td-sr-expiry').html();
        var qty = $(this).closest('tr').find('.td-sr-qty').html();
        var rate = $(this).closest('tr').find('.td-sr-rate').val();
        var discount = $(this).closest('tr').find('.td-sr-disc').html();
        var gst = $(this).closest('tr').find('.td-sr-gst').html();
        var igst = $(this).closest('tr').find('.td-sr-igst').val();
        var sgst = $(this).closest('tr').find('.td-sr-sgst').val();
        var cgst = $(this).closest('tr').find('.td-sr-cgst').val();
        var mrp = $(this).closest('tr').find('.td-sr-mrp').val();
        var amount = $(this).closest('tr').find('.td-sr-amount').html();
        var mfg = $(this).closest('tr').find('.td-sr-mfg').val();

      // set value
        $('#saveandreturn-tmp-form').find('#editid').val(editid);
        $('#saveandreturn-tmp-form').find('#sar_product_id').val(product_id);
        $('#saveandreturn-tmp-form').find('#sar_product').val(product_name);
        $('#saveandreturn-tmp-form').find('#sar_batch').val(batch);
        $('#saveandreturn-tmp-form').find('#sar_expiry').val(expiry);
        $('#saveandreturn-tmp-form').find('#sar_qty').val(qty);
        $('#saveandreturn-tmp-form').find('#sar_rate').val(rate);
        $('#saveandreturn-tmp-form').find('#sar_discount').val(discount);
        $('#saveandreturn-tmp-form').find('#sar_gst').val(gst);
        $('#saveandreturn-tmp-form').find('#sar_igst').val(igst);
        $('#saveandreturn-tmp-form').find('#sar_cgst').val(cgst);
        $('#saveandreturn-tmp-form').find('#sar_sgst').val(sgst);
        $('#saveandreturn-tmp-form').find('#sar_mrp').val(mrp);
        $('#saveandreturn-tmp-form').find('#sar_amount').val(amount);
        $('#saveandreturn-tmp-form').find('#sar_mfg_co').val(mfg);

        $('#btn-tmp-return').prop('disabled', false);

        var deletebtn = $(this).closest('tr').find('.btn-sar-delete');
        $(deletebtn).hide();
        $('.btn-sar-delete').not(deletebtn).show();
        $('#sar-tbody tr').css("background-color", "#FFFFFF")
        $('#'+editid).css("background-color", "#A9A9A9");
    });


    $('body').on('change keyup past', '#sar_qty', function () {
      var qty = $(this).val();
      var rate = $('#saveandreturn-tmp-form').find('#sar_rate').val();
      var discount = $('#saveandreturn-tmp-form').find('#sar_discount').val();
      var gst = $('#saveandreturn-tmp-form').find('#sar_gst').val();

      qty = (qty != '' && !isNaN(qty)) ? parseFloat(qty) : 0;
      rate = (rate != '' && !isNaN(rate)) ? parseFloat(rate) : 0;
      discount = (discount != '' && !isNaN(discount)) ? parseFloat(discount) : 0;
      gst = (gst != '' && !isNaN(gst)) ? parseFloat(gst) : 0;

      var amount = (qty * (rate-discount));
      amount = (amount != '') ? parseFloat(amount) : 0;

      var gstrate = (amount*gst/100);
      gstrate = (gstrate != '') ? parseFloat(gstrate) : 0;

      var totalamount = amount+gstrate;
      $('#saveandreturn-tmp-form').find('#sar_amount').val(parseFloat(totalamount).toFixed(2));

    });

    $('body').on('change keyup past', '#sar_discount', function () {
        $('#sar_qty').trigger("change");
    });

    $('body').on('change keyup past', '#sar_gst', function () {
        $('#sar_qty').trigger("change");
    });

    $('body').on('click', '#btn-add-return', function () {
      var data = [];
        $("#sar-tbody > tr").each(function (){
            var tmparray = [];
            tmparray['product_id'] = $(this).find('.td-sr-product-id').val();
            tmparray['product_name'] = $(this).find('.td-sr-product').html();
            tmparray['batch'] = $(this).find('.td-sr-batch').html();
            tmparray['expiry'] = $(this).find('.td-sr-expiry').html();
            tmparray['qty'] = $(this).find('.td-sr-qty').html();
            tmparray['rate'] = $(this).find('.td-sr-rate').val();
            tmparray['discount'] = $(this).find('.td-sr-disc').html();
            tmparray['gst'] = $(this).find('.td-sr-gst').html();
            tmparray['igst'] = $(this).find('.td-sr-igst').val();
            tmparray['sgst'] = $(this).find('.td-sr-sgst').val();
            tmparray['cgst'] = $(this).find('.td-sr-cgst').val();
            tmparray['mrp'] = $(this).find('.td-sr-mrp').val();
            tmparray['amount'] = $(this).find('.td-sr-amount').html();
            tmparray['mfg'] = $(this).find('.td-sr-mfg').val();
            data.push(tmparray);
        });
        
        if(data.length > 0){
          var html = $('#hiddenReturnItemHtml').html();
          html = html.replace('<table>','');
          html = html.replace('</table>','');
          html = html.replace('<tbody>','');
          html = html.replace('</tbody>','');

          $.each(data, function (i, item) {
              var length = $('#item-return-tbody tr').length;
              if(length == 0){
                $('#return-table').show();
              }
              tmphtml = html;
              tmphtml = tmphtml.replace("##SRNO##",length+1);
              $('#item-return-tbody').append(tmphtml);

              // assign value
              $('#item-return-tbody tr:last').find('.r_product').val(item.product_name);
              $('#item-return-tbody tr:last').find('.r_product_id').val(item.product_id);
              $('#item-return-tbody tr:last').find('.r_mfg_co').val(item.mfg);
              $('#item-return-tbody tr:last').find('.r_batch').val(item.batch);
              $('#item-return-tbody tr:last').find('.r_expiry').val(item.expiry);
              $('#item-return-tbody tr:last').find('.r_qty').val(item.qty);
              $('#item-return-tbody tr:last').find('.r_rate').val(item.rate);
              $('#item-return-tbody tr:last').find('.r_discount').val(item.discount);
              $('#item-return-tbody tr:last').find('.r_gst').val(item.gst);
              $('#item-return-tbody tr:last').find('.r_igst').val(item.igst);
              $('#item-return-tbody tr:last').find('.r_cgst').val(item.cgst);
              $('#item-return-tbody tr:last').find('.r_sgst').val(item.sgst);
              $('#item-return-tbody tr:last').find('.r_amount').val(item.amount);
          });
          $('#save_and_return_model').modal('hide');
          $('.totalamount').trigger("change");
        }
    });
    /* -------------------------------------------------JS FOR SAVE & RETURN START----------------------------------------------------- */
    
    /*--------------------------------------------------JS FOR APPLY TEMPLATE START-----------------------------------------------------*/

    
    $("#templateapply-form").on("submit", function(event){
      event.preventDefault();
      var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      var data = $(this).serialize();
      var dataarr = JSON.parse('{"' + decodeURI(data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
      var template_id = (typeof dataarr.template !== 'undefined' && dataarr.template !== '') ? dataarr.template : '';
      var customer_id = (typeof dataarr.customer !== 'undefined' && dataarr.customer !== '') ? dataarr.customer : '';

      if(template_id !== ''){
        $.ajax({
          type: "POST",
          url: 'ajax.php',
          data: {'action':'getAllTemplateProduct', 'template_id': template_id,'customer_id': customer_id},
          dataType: "json",
          beforeSend: function() {
            $('#btn-addtemplate').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
            $('#btn-addtemplate').prop('disabled', true);
          },
          success: function (data) {
            if(data.status == true){
              html = $('#hiddenItemHtml').html();
              html = html.replace("<table>", "");
              html = html.replace("</table>", "");
              html = html.replace("<tbody>", "");
              html = html.replace("</tbody>", "");

            
              var state_code = $('#statecode').val();
              var curstate_code = $('#cur_statecode').val();
              var tr = $('#item-tbody tr:first').find('.product_id').val();
              if(tr == ''){
                $('#item-tbody').empty();
              }
              var tbllength = $("#item-tbody tr").length;
              $.each(data.result, function (i, item) {
                tmphtml = html;
                tmphtml = tmphtml.replace("##SRNO##",tbllength+i+1);
                var append = $('#item-tbody').append(tmphtml);


                // assign value
                //$('#product-tbody tr:last').find('.btn-remove-product').hide(); 
                $('#item-tbody tr:last').find('.product').val(item.product_name);
                $('#item-tbody tr:last').find('.product_id').val(item.id);
                $('#item-tbody tr:last').find('.mrp').val(item.mrp);
                $('#item-tbody tr:last').find('.mfg').val(item.mfg_company);
                $('#item-tbody tr:last').find('.batch').val(item.batch_no);
                $('#item-tbody tr:last').find('.expiry').val(item.ex_date);
                $('#item-tbody tr:last').find('.qty_ratio').val(item.ratio);
                $('#item-tbody tr:last').find('.qty').val(item.qty);

                var igst = (typeof item.igst != 'undefined' && item.igst != '') ? parseFloat(item.igst) : 0;
                var cgst = (typeof item.cgst != 'undefined' && item.cgst != '') ? parseFloat(item.cgst) : 0;
                var sgst = (typeof item.sgst != 'undefined' && item.sgst != '') ? parseFloat(item.sgst) : 0;

                if(state_code == curstate_code){
                  $('#item-tbody tr:last').find('.c_igst').val(0);
                  $('#item-tbody tr:last').find('.c_cgst').val(cgst);
                  $('#item-tbody tr:last').find('.c_sgst').val(sgst);

                  $('#item-tbody tr:last').find('.gst').val(cgst+sgst);
                }else{
                  $('#item-tbody tr:last').find('.c_igst').val(igst);
                  $('#item-tbody tr:last').find('.c_cgst').val(0);
                  $('#item-tbody tr:last').find('.c_sgst').val(0);

                  $('#item-tbody tr:last').find('.gst').val(igst);
                }

                if(tr == '' && i == 0){
                  $('#item-tbody tr:last').find('.btn-remove-item').hide();
                }
              });
              $('#apply_template_model').modal('hide');
            }else{
              htmlerror =  htmlerror.replace("##MSG##", data.message);
              $('#template-errormsg').html(htmlerror);
            }
            $('#btn-addtemplate').html('Apply');
            $('#btn-addtemplate').prop('disabled', false);
          },
          error: function () {
            htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
            $('#template-errormsg').html(htmlerror);
            $('#btn-addtemplate').html('Apply');
            $('#btn-addtemplate').prop('disabled', false);
          }
        });
      }else{
        return false;  
      }
    });

    /*--------------------------------------------------JS FOR APPLY TEMPLATE END-----------------------------------------------------*/
    
    /*--------------------------------------------------JS FOR ADD DOCTOR START---------------------------------------------------*/

    // for get country wise state
    $("#do_country").change(function(){
      var country_id = $(this).val();
      if(country_id !== ''){
        $.ajax({
          type: "POST",
          url: 'ajax.php',
          data: {'country_id':country_id, 'action':'getCountryByState'},
          dataType: "json",
          success: function (data) {console.log(data);
            if(data.status == true){
              $('#do_state').children('option:not(:first)').remove();
              $.each(data.result, function (i, item) {
                $('#do_state').append($('<option>', { 
                    value: item.id,
                    text : item.name 
                }));
              });
            }else{
              $('#do_state').children('option:not(:first)').remove();
            }
          },
          error: function () {
            $('#do_state').children('option:not(:first)').remove();
          }
        });
      }else{
        $('#do_state').children('option:not(:first)').remove();
      }
      $('#do_state').trigger("change");
    });


    // for get state wise city
    $("#do_state").change(function(){
      var state_id = $(this).val();
      if(state_id !== ''){
        $.ajax({
          type: "POST",
          url: 'ajax.php',
          data: {'state_id':state_id, 'action':'getStateByCity'},
          dataType: "json",
          success: function (data) {
            if(data.status == true){
              $('#do_city').children('option:not(:first)').remove();
              $.each(data.result, function (i, item) {
                $('#do_city').append($('<option>', { 
                    value: item.id,
                    text : item.name 
                }));
              });
            }else{
              $('#do_city').children('option:not(:first)').remove();
            }
          },
          error: function () {
            $('#do_city').children('option:not(:first)').remove();
          }
        });
      }else{
        $('#do_city').children('option:not(:first)').remove();
      }
    });

    // ADD NEW DOCTOR IN DATABASE

    $("#add-doctor-form").on("submit", function(event){
      event.preventDefault();
      var data = $(this).serialize();
      var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      var dataarr = JSON.parse('{"' + decodeURI(data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');

      $.ajax({
        type: "POST",
        url: 'ajax.php',
        data: {'action':'adddoctor', 'data': data},
        dataType: "json",
        beforeSend: function() {
          $('#btn-adddoctor').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
          $('#btn-adddoctor').prop('disabled', true);
        },
        success: function (data) {
          if(data.status == true){
            htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
            $('#errormsg').html(htmlsuccess);
            $("html, body").animate({ scrollTop: 0 }, "slow");
            $('#add_doctor_model').modal('toggle');

            // set doctor value
            $('#doctor').append($('<option>', { 
                value: data.result,
                text : dataarr.doctor_name 
            }));
            $('#doctor').val(data.result).trigger('change');
            
            $('#add-doctor-form')[0].reset();
            $('#add-doctor-form').find('#do_country').val('').trigger('change');
            $('#add-doctor-form').find('#do_state').val('').trigger('change');
            $('#add-doctor-form').find('#do_city').val('').trigger('change');
          }else{
            htmlerror =  htmlerror.replace("##MSG##", data.message);
            $('#adddoctor-errormsg').html(htmlerror);
          }
          $('#btn-adddoctor').html('Save');
          $('#btn-adddoctor').prop('disabled', false);
        },
        error: function () {
          htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
          $('#adddoctor-errormsg').html(htmlerror);

          $('#btn-adddoctor').html('Save');
          $('#btn-adddoctor').prop('disabled', false);
        }
      });
    });

    // AUTO COMPLETE Customer
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

    $('body').on('keyup click', '#customer_name', function () {

        var $this = $(this);
        $(this).mcautocomplete({
        // These next two options are what this plugin adds to the autocomplete widget.
            showHeader: true,
            columns: [{
                name: 'Name',
                width: '200px',
                valueField: 'name'
            }, {
                name: 'Mobile',
                width: '200px',
                valueField: 'mobile'
            },
            {
                name: 'Customer ID',
                width: '200px;',
                valueField: 'customer_id'
            }],

            // Event handler for when a list item is selected.
            select: function (event, ui) {
                this.value = (ui.item ? ui.item.name : '');
                $('#customer_id').val(ui.item.id);
                $('#bytempcust').val(ui.item.id);                  //for template
                $('#customer_mobile').val(ui.item.mobile);
                $('#statecode').val(ui.item.state);
                if(ui.item.customer_id !== ''){
                  $('#customer_u_id').val(ui.item.customer_id);
                }
                //$('#results').text(ui.item ? 'Selected: ' + ui.item.name + ', ' + ui.item.purchase_id + ', ' + ui.item.batch : 'Nothing selected, input was ' + this.value);
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
                        action: "searchCustomer"
                    },
                    // The success event handler will display "No match found" if no items are returned.
                    success: function (data) {
                        if(data.status == true){
                            $('.customererror').empty();
                            $($this).closest('tr').find('.customererror').empty();
                            response(data.result)
                        }else{
                            $('.customererror').text("No results found");
                        }
                    },error: function () {
                      $('.customererror').text("No results found");
                    }
                });
            }
        });
    })


    $('body').on('change', '#doctor', function () {
      var doctor_id = $(this).val();
      $.ajax({
          type: "POST",
          url: 'ajax.php',
          data: {'doctor_id':doctor_id, 'action':'getdoctornumber'},
          dataType: "json",
          success: function (data) {
            $('#doctor_mobile').val(data);
          },
          error: function () {
            $('#doctor_mobile').val();
          }
        });
    });

    /*--------------------------------------------------JS FOR ADD DOCTOR END-----------------------------------------------------*/

});