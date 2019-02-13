$( document ).ready(function() {
  
    setTimeout(function(){
        $('.totalamount').trigger("change");
    }, 2000);

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
        data: {'bill_type':bill_type, 'action':'getInvoiceNoForQuatation'},
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
                
                $('#customer_name').val(typeof data.result.name != 'undefined' ? data.result.name : '');
                $('#customer_id').val(typeof data.result.id != 'undefined' ? data.result.id : '');
                $('#customer_mobile').val(typeof data.result.mobile != 'undefined' ? data.result.mobile : '');
                $('#customer_email').val(typeof data.result.email != 'undefined' ? data.result.email : '');
                $('#statecode').val(typeof data.result.statecode != 'undefined' ? data.result.statecode : '');
                $('#customer_city').val(typeof data.result.city != 'undefined' ? data.result.city : '').trigger('change');
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
                showDangerToast('Somthing Want Wrong!');
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
      html = html.replace(/##SRNO##/g,trlength);
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
        var sale_mrp = $(this).closest('tr').find('.mrp').val();
        var tr = $(this).closest('tr').attr('data-id');

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
                width: '150px',
                valueField: 'igst'
            }],

            // Event handler for when a list item is selected.
            select: function (event, ui) {
                var customer_name = $('#customer_name').val();
                if(customer_name !== ''){
                    $('#errormsg').empty();
                    this.value = (ui.item ? ui.item.name : '');
                    $(this).closest('tr').find('.product_id').val(ui.item.id);
                    $(this).closest('tr').find('.mrp').val(ui.item.mrp);
                    $(this).closest('tr').find('.mfg').val(ui.item.mfg_company);
                    $(this).closest('tr').find('.batch').val(ui.item.batch);
                    $(this).closest('tr').find('.expiry').val(ui.item.expiry);

                    //$(this).closest('tr').find('.ptr').val(ui.item.ptr);
                    //$(this).closest('tr').find('.discount').val(ui.item.discount);
                    //$(this).closest('tr').find('.rate').val(ui.item.rate);
                    $('#ptr-discount-model').find('#ptr').val((typeof ui.item.ptr !== 'undefined' && ui.item.ptr !== '') ? ui.item.ptr : 0);
                    $('#ptr-discount-model').find('#discount').val((typeof ui.item.discount !== 'undefined' && ui.item.discount !== '') ? ui.item.discount : 0);
                    $('#ptr-discount-model').find('#tr-id').val(tr);
                    $(this).closest('tr').find('.current_qty').val(ui.item.total_qty);
                    
    
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

                    
                    $('#ptr-discount-model').modal({
                        backdrop: 'static',
                        keyboard: false,
                        show: true
                    });

                }else{
                    $('#errormsg').html('<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>Please Select Customer!</div></div></div>');
                    $($this).val(null);
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                }
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
                        mrp: sale_mrp,
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
    });

    /*----add gst and discount start-------*/
    $('body').on('click', '#btn-ptr-discount ', function () {
      var tr_id = $('#ptr-discount-model').find('#tr-id').val();
      var ptr = $('#ptr-discount-model').find('#ptr').val();
      var discount = $('#ptr-discount-model').find('#discount').val();
      var rate = 0;
      if(tr_id !== ''){
        ptr = (ptr !== '') ? ptr : 0;
        if(discount !== ''){
          var disAmount = (ptr*discount/100);
          rate = (ptr-disAmount);
        }else{
          rate = ptr;
        }
        $('#tr-'+tr_id).find('.ptr').val(ptr);
        $('#tr-'+tr_id).find('.discount').val(discount);
        $('#tr-'+tr_id).find('.rate').val(rate.toFixed(2));
        $('#tr-'+tr_id).find('.qty').focus();
        $('#ptr-discount-model').modal('hide');
      }else{
        return false;
      }
    });
    /*----add gst and discount end-------*/


    /*-----------------------------------JS FOR PRODUCT ITEMS COUNT CHARGES START-----------------------------------*/

    $('body').on('change keyup past', '.qty ', function () {
      var qty = $(this).val();
      var rate = $(this).closest('tr').find('.rate').val();
      
      qty = (qty != '' && !isNaN(qty)) ? parseFloat(qty) : 0;
      rate = (rate != '' && !isNaN(rate)) ? parseFloat(rate) : 0;

      var amount = (qty * rate);
      amount = (amount != '') ? parseFloat(amount) : 0;
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

    $('body').on('change keyup past', '.totalamount ', function () {
        var totalamount = 0;
        var igst = 0;
        var sgst = 0;
        var cgst = 0;
        var gst = 0;

        // count total amount
        $('.totalamount').each(function() {
          var val = $.trim( $(this).val() );
          if(val){
            val = parseFloat( val.replace( /^\$/, "" ) );
            totalamount += !isNaN( val ) ? val : 0;

            // count gst amount
            var tmpigst = $(this).closest('tr').find('.gst').val();
            tmpgst = (typeof tmpigst !== 'undefined' && tmpigst != '') ? tmpigst : 0;
            amount = (!isNaN( val ) && val != '') ? val : 0;
            gst += (amount*tmpigst/100);
          }
        });
        $('#alltotalamount').val(totalamount.toFixed(2));

        // count courier charge
        var courierFinalCharge = 0;
        if($('#couriercharge').val() != '' && $('#courier').val() != ''){
          var courierChargePer = $('#couriercharge').val();
          var courierChargeAmount = $('#courier').val();
          var courierFinalCharge = (courierChargeAmount*courierChargePer/100);
          totalamount += (typeof courierChargeAmount !== 'undefined' && courierChargeAmount != '' && !isNaN(courierChargeAmount)) ? parseFloat(courierChargeAmount) : 0;
        }

        // count discount
        var dis_type = $("input[name='discount_type']:checked").val();
        if(dis_type == 'per'){
          var dis_per = $("#discount_per").val();
          var dis_per = (dis_per != '' && !isNaN(dis_per)) ? parseFloat(dis_per) : 0;
          var dis_value = (totalamount*dis_per/100);
          totalamount -= dis_value;
        }else if(dis_type == 'rs'){
          var dis_rs = $("#discount_rs").val();
          dis_rs = (dis_rs != '' && !isNaN(dis_rs)) ? parseFloat(dis_rs) : 0;
          totalamount -= dis_rs;
        }

        $('#overalldiscount').val(totalamount.toFixed(2));

        // count GST
        var statecode = $('#statecode').val();
        var cur_statecode = $('#cur_statecode').val();
        courierFinalCharge = (typeof courierFinalCharge !== 'undefined' && courierFinalCharge !== '' && !isNaN(courierFinalCharge)) ? parseFloat(courierFinalCharge) : 0;
        gst += courierFinalCharge;
        totalamount += gst;

        if(statecode == cur_statecode){
          sgst = (gst/2);
          cgst = (gst/2);
          $('#totaligst').val(0);
          $('#totalsgst').val(parseFloat(sgst).toFixed(2));
          $('#totalcgst').val(parseFloat(cgst).toFixed(2));
        }else{
          $('#totaligst').val(parseFloat(gst).toFixed(2));
          $('#totalsgst').val(0);
          $('#totalcgst').val(0);
        }
        $('#totaltaxgst').val(parseFloat(gst).toFixed(2));

        $('#purchase_amount').val(totalamount.toFixed(2));

        var round_amount = (Math.round(totalamount)-totalamount);

        $('#roundoff_amount').val((round_amount).toFixed(2));

        $('#final_amount').val(Math.round(totalamount).toFixed(2));

    });

    // count all amount total
    /*$('body').on('change keyup past', '.totalamount ', function () {
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

    });*/
    setInterval(function() {
      var f = $("#final_amount").val();
      var limit_total = $("#limit_total").val();
      limit_total = (typeof limit_total !== 'undefined' && limit_total !== '') ? parseFloat(limit_total) : 0;
      var limit_status = $("#limit_status").val();
        if(limit_total <= parseFloat(f) && limit_total != 0 && f != 0){
          if(limit_status == 0){
            var popup_status = $("#popup_status").val();
            if(popup_status == 0){
                var modalConfirm = function(callback){
                  $("#limit_sms").html('This Customer Cr Limit is Over '+limit_total+'. Are You Continue With Bill?');
                   $("#mi-modal").modal({
                      backdrop: 'static',
                      keyboard: false,
                      show: true
                   });

                  $("#modal-btn-si").on("click", function(){
                      callback(true);
                      $("#mi-modal").modal('hide');
                  });

                  $("#modal-btn-no").on("click", function(){
                    callback(false);
                    $("#mi-modal").modal('hide');
                  });
                };
                modalConfirm(function(confirm){
                if(confirm){
                  //Acciones si el usuario confirma
                  $("#limit_status").val('1');
                  $("#popup_status").val('1');
                  $(':input[type="submit"]').prop('disabled', false);
                }else{
                  //Acciones si el usuario no confirma
                  $("#limit_status").val('0');
                  $("#popup_status").val('1');
                  $(':input[type="submit"]').prop('disabled', true);
                }
              });
            }
          }
        }else{
          $(':input[type="submit"]').prop('disabled', false);
        } 
    }, 1000);

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

    $('body').on('keyup click', '#customer_name, #customer_mobile, #customer_email', function () {

        var $this = $(this);
        var field = $(this).attr('data-name');
        var city_id = $('#customer_city').val();
        
        var customersearch = [{
                name: 'Name',
                width: '200px',
                valueField: 'name'
            }, {
                name: 'Mobile',
                width: '130px',
                valueField: 'mobile'
            },
            {
                name: 'Customer ID',
                width: '150px;',
                valueField: 'customer_id'
            }];
            
        var mobilesearch = [{
                name: 'Name',
                width: '200px',
                valueField: 'name'
            }, {
                name: 'Mobile',
                width: '130px',
                valueField: 'mobile'
            }];
            
        var emailsearch = [{
                name: 'Name',
                width: '200px',
                valueField: 'name'
            },{
                name: 'Email',
                width: '400px',
                valueField: 'email'
            }];
        var columndata = [];
        if(field == 'name'){
            columndata = customersearch;
        }else if(field == 'mobile'){
            columndata = mobilesearch;
        }else{
            columndata = emailsearch;
        }
        $(this).mcautocomplete({
        // These next two options are what this plugin adds to the autocomplete widget.
            showHeader: true,
            columns: columndata,

            // Event handler for when a list item is selected.
            select: function (event, ui) {
                
              if(ui.item.limit_total > 0){
                $('#customer_id').val(ui.item.id);
                $('#customer_name').val(ui.item.name);
                $('#bytempcust').val(ui.item.id);                  //for template
                $('#customer_mobile').val(ui.item.mobile);
                $('#customer_email').val(ui.item.email);
                $('#statecode').val(ui.item.state);
                $('#customer_u_id').val(ui.item.temp_customer);
                $('#limit_total').val(ui.item.limit_total);
                $('#customer_city').val(ui.item.city).trigger('change');
                
                if(typeof ui.item.id != 'undefined' && ui.item.id != ''){
                  PanddingBillAmount(ui.item.id);
                //   ShowCustomerBill(ui.item.id);
                }
              }else{
                var modalConfirm1 = function(callback){
                  $("#limit_sms").html('This Customer Cr Limit is Over '+ui.item.limit_total+'. Are You Continue With Bill?');
                   $("#mi-modal").modal({
                      backdrop: 'static',
                      keyboard: false,
                      show: true
                   });

                  $("#modal-btn-si").on("click", function(){
                      callback(true);
                      $("#mi-modal").modal('hide');
                  });

                  $("#modal-btn-no").on("click", function(){
                    callback(false);
                    $("#mi-modal").modal('hide');
                  });
                };
                modalConfirm1(function(confirm){
                if(confirm){
                  //Acciones si el usuario confirma
                    $('#customer_id').val(ui.item.id);
                    $('#customer_name').val(ui.item.name);
                    $('#bytempcust').val(ui.item.id);                  //for template
                    $('#customer_mobile').val(ui.item.mobile);
                    $('#customer_email').val(ui.item.email);
                    $('#statecode').val(ui.item.state);
                    $('#customer_u_id').val(ui.item.temp_customer);
                    $('#limit_total').val(ui.item.limit_total);
                    $('#customer_city').val(ui.item.city).trigger('change');
                    $('#limit_status').val('1');
                    
                    if(typeof ui.item.id != 'undefined' && ui.item.id != ''){
                      PanddingBillAmount(ui.item.id);
                    //   ShowCustomerBill(ui.item.id);
                    }
                }else{
                  //Acciones si el usuario no confirma
                    $("#customer_name").val('');
                    $("#customer_id").val('');
                    $("#customer_mobile").val('');
                    $('#customer_email').val('');
                    $('#customer_u_id').val(ui.item.getcustmerID);
                    $('#limit_total').val('');
                    $('#limit_status').val('0');
                    $('#customer_city').val('').trigger('change');
                    return false;
                }
              });
              }

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
                        field : field,
                        city : city_id,
                        action: "searchCustomerForSale"
                    },
                    // The success event handler will display "No match found" if no items are returned.
                    success: function (data) {
                        if(data.status == true){
                            $('.customererror').empty();
                            response(data.result)
                        }else{
                            $('.customererror').empty();
                            $($this).closest('div').find('.customererror').text("No results found");
                        }
                    },error: function () {
                      $($this).closest('div').find('.customererror').text("No results found");
                    }
                });
            }
        });
    });
    
    
    /*----------GAUTAM MAKWANA | SHOW LAST 6 BILL OF CUSTOMER START----------------*/
    function ShowCustomerBill(customer_id = null){
      $.ajax({
          type: "POST",
          url: 'ajax.php',
          data: {'customer_id':customer_id, 'action':'ShowCustomerBill'},
          dataType: "json",
          beforeSend: function() {
              $('#customer_loader').show();
          },
          success: function (data) {
            if(data.status == true){
                var html = $('#hidden-lastbill').html();
                html = html.replace("<table>", "");
                html = html.replace("</table>", "");
                html = html.replace("<tbody>", "");
                html = html.replace("</tbody>", "");

                $('#lastbill-product-tbody').empty();
                $.each(data.result, function (key, value) {
                    var finalhtml = html;
                    finalhtml = finalhtml.replace(/##SRNO##/g, key+1);
                    finalhtml = finalhtml.replace(/##PRODUCTNAME##/g, (typeof value.product_name != 'undefined') ?  value.product_name : '');
                    finalhtml = finalhtml.replace(/##PRODUCTID##/g, (typeof value.product_id != 'undefined') ?  value.product_id : '');
                    finalhtml = finalhtml.replace(/##MRP##/g, (typeof value.mrp != 'undefined' && value.mrp != '') ?  value.mrp : 0);
                    finalhtml = finalhtml.replace(/##MGF##/g, (typeof value.mfg_co != 'undefined') ?  value.mfg_co : '');
                    finalhtml = finalhtml.replace(/##BATCH##/g, (typeof value.batch != 'undefined') ?  value.batch : '');
                    finalhtml = finalhtml.replace(/##EXPIRY##/g, (typeof value.expiry != 'undefined') ?  value.expiry : '');
                    finalhtml = finalhtml.replace(/##QTY##/g, (typeof value.qty != 'undefined' && value.qty != '') ?  value.qty : 0);
                    finalhtml = finalhtml.replace(/##RATIO##/g, (typeof value.qty_ratio != 'undefined' && value.qty_ratio != '') ?  value.qty_ratio : 0);
                    finalhtml = finalhtml.replace(/##STOCK##/g, (typeof value.currentstock != 'undefined' && value.currentstock != '') ?  value.currentstock : 0);
                    finalhtml = finalhtml.replace(/##FREEQTY##/g, (typeof value.freeqty != 'undefined' && value.freeqty != '') ?  value.freeqty : 0);
                    finalhtml = finalhtml.replace(/##PTR##/g, (typeof value.ptr != 'undefined' && value.ptr != '') ?  value.ptr : 0);
                    finalhtml = finalhtml.replace(/##DISCOUNT##/g, (typeof value.discount != 'undefined' && value.discount != '') ?  value.discount : 0);
                    finalhtml = finalhtml.replace(/##RATE##/g, (typeof value.rate != 'undefined' && value.rate != '') ?  value.rate : 0);
                    finalhtml = finalhtml.replace(/##GST##/g, (typeof value.gst != 'undefined' && value.gst != '') ?  value.gst : 0);
                    finalhtml = finalhtml.replace(/##CGST##/g, (typeof value.cgst != 'undefined' && value.cgst != '') ?  value.cgst : 0);
                    finalhtml = finalhtml.replace(/##SGST##/g, (typeof value.sgst != 'undefined' && value.sgst != '') ?  value.sgst : 0);
                    finalhtml = finalhtml.replace(/##IGST##/g, (typeof value.igst != 'undefined' && value.igst != '') ?  value.igst : 0);
                    finalhtml = finalhtml.replace(/##AMOUNT##/g, (typeof value.totalamount != 'undefined' && value.totalamount != '') ?  value.totalamount : 0);
                    finalhtml = finalhtml.replace(/##EXPIRED##/g, (typeof value.expired != 'undefined' && value.expired == 1) ?  'Expired!' : '');
                    $('#lastbill-product-tbody').append(finalhtml);
                  });
                  $('#lastbill_model').modal('show');
            }
            $('#customer_loader').hide();
          },
          error: function () {
            $('#customer_loader').hide();
            return false;
          }
      });
    }
    
    function PanddingBillAmount(customer_id = null){
      $.ajax({
          type: "POST",
          url: 'ajax.php',
          data: {'customer_id':customer_id, 'action':'PanddingBillAmount'},
          dataType: "json",
          success: function (data) {
            if(data != ''){
             $.each(data, function (i, item) {
                alert("Bill No:-"+item.invoice_no+", Total Bill Amount:- "+item.total_bill+", Total Paymnent:- "+item.total_payment+" , Total Remaining Amount:-"+item.total_remaining+"");
             });
            }
          },
          error: function () {
            return false;
          }
      });
    }

    $('body').on('click', '.lastbill-check', function () {
      var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      var checked_color = '#A9A9A9';
      var uncheck_color = '#FFFFFF';

      if($(this).prop("checked") == true){

        var qty = $(this).closest('tr').find('.qty').html();
        qty = (typeof qty !== 'undefined' && qty != '') ? parseFloat(qty) : 0;

        var stock = $(this).closest('tr').find('.stock').val();
        stock = (typeof stock !== 'undefined' && stock != '') ? parseFloat(stock) : 0;
        
        if(qty <= stock){
          $(this).closest('tr').css({"background-color": checked_color});
        }else{
          $(this).prop('checked', false);
          $(this).closest('tr').css({"background-color": uncheck_color});
          $(this).closest('tr').css({"color": 'red'});
          htmlerror = htmlerror.replace("##MSG##", 'Stock not available in this product!');
          $('#lastbill-errormsg').html(htmlerror);
        }
      }else{
        $(this).closest('tr').css({"background-color": uncheck_color});
      }
      lastbillbutton();
    });

    // for check all order product
    $('body').on('click', '.lastbill-check-all', function () {
      var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      var checked_color = '#A9A9A9';
      var uncheck_color = '#FFFFFF';
      if(this.checked){
        $("#lastbill-product-tbody .lastbill-check").each(function(){
          var qty = $(this).closest('tr').find('.qty').html();
          qty = (typeof qty !== 'undefined' && qty != '') ? parseFloat(qty) : 0;

          var stock = $(this).closest('tr').find('.stock').val();
          stock = (typeof stock !== 'undefined' && stock != '') ? parseFloat(stock) : 0;

          if(qty <= stock){
            this.checked=true;
            $(this).closest('tr').css({"background-color": checked_color});
          }else{
            this.checked=false;
            // $(this).prop('checked', false);
            $(this).closest('tr').css({"background-color": uncheck_color});
            $(this).closest('tr').css({"color": 'red'});
            var htmlerrortmp = htmlerror;
            htmlerrortmp = htmlerrortmp.replace("##MSG##", 'Some product stock is not available!');
            $('#lastbill-errormsg').html(htmlerrortmp);
          }
        })              
      }else{
        $("#lastbill-product-tbody .lastbill-check").each(function(){
          this.checked=false;
          $(this).closest('tr').css("background-color", uncheck_color);
        })              
      }
      lastbillbutton();
    });

    function lastbillbutton(){
      var checked_length = $('.lastbill-check:checked').length;
      if(checked_length > 0){
        $('#btn-add-lastbill').attr('disabled',false);
      }else{
        $('#btn-add-lastbill').attr('disabled',true);
      }
    }

    $('body').on('click', '#btn-add-lastbill', function () {
      
      var finaldata = [];
        $('.lastbill-check:checked').each(function(key) {
          var data = [];
          data.product = $(this).closest('tr').find('.product_name').html();
          data.product_id = $(this).closest('tr').find('.product_id').val();
          data.mrp = $(this).closest('tr').find('.mrp').html();
          data.mfg = $(this).closest('tr').find('.mfg').html();
          data.batch = $(this).closest('tr').find('.batch').html();
          data.expiry = $(this).closest('tr').find('.expiry').html();
          data.expired = $(this).closest('tr').find('.expired').html();
          data.qty = $(this).closest('tr').find('.qty').html();
          data.stock = $(this).closest('tr').find('.stock').val();
          data.ratio = $(this).closest('tr').find('.ratio').val();
          data.stock = $(this).closest('tr').find('.stock').val();
          data.freeqty = $(this).closest('tr').find('.freeqty').html();
          data.ptr = $(this).closest('tr').find('.ptr').html();
          data.discount = $(this).closest('tr').find('.discount').html();
          data.rate = $(this).closest('tr').find('.rate').html();
          data.gst = $(this).closest('tr').find('.gst').html();
          data.igst = $(this).closest('tr').find('.igst').val();
          data.cgst = $(this).closest('tr').find('.cgst').val();
          data.sgst = $(this).closest('tr').find('.sgst').val();
          data.totalamount = $(this).closest('tr').find('.amount').html();
          finaldata.push(data);
        });

        if(finaldata.length > 0){
          var html = $('#hiddenItemHtml').html();
          html = html.replace("<table>", "");
          html = html.replace("</table>", "");
          html = html.replace("<tbody>", "");
          html = html.replace("</tbody>", "");
          $('#item-tbody').empty();
          $.each(finaldata, function (key, value) {
            var htmltmp = html;
            htmltmp = htmltmp.replace(/##SRNO##/g, key+1);
            $('#item-tbody').append(htmltmp);

            $('#item-tbody tr:last').find('.product').val(value.product);
            $('#item-tbody tr:last').find('.product_id').val(value.product_id);
            $('#item-tbody tr:last').find('.mrp').val(value.mrp);
            $('#item-tbody tr:last').find('.mfg').val(value.mfg);
            $('#item-tbody tr:last').find('.batch').val(value.batch);
            $('#item-tbody tr:last').find('.expiry').val(value.expiry);
            $('#item-tbody tr:last').find('.expired').html(value.expired);
            $('#item-tbody tr:last').find('.qty').val(value.qty);
            $('#item-tbody tr:last').find('.current_qty').val(value.stock);
            $('#item-tbody tr:last').find('.qty_ratio').val(value.ratio);
            $('#item-tbody tr:last').find('.freeqty').val(value.freeqty);
            $('#item-tbody tr:last').find('.ptr').val(value.ptr);
            $('#item-tbody tr:last').find('.discount').val(value.discount);
            $('#item-tbody tr:last').find('.rate').val(value.rate);
            $('#item-tbody tr:last').find('.gst').val(value.gst);
            $('#item-tbody tr:last').find('.c_igst').val(value.igst);
            $('#item-tbody tr:last').find('.c_cgst').val(value.cgst);
            $('#item-tbody tr:last').find('.c_sgst').val(value.sgst);
            $('#item-tbody tr:last').find('.totalamount').val(value.totalamount);
          });
        }
      $('.totalamount').trigger("change");
      $('#lastbill_model').modal('hide');
    });

    /*----------GAUTAM MAKWANA | SHOW LAST 6 BILL OF CUSTOMER END----------------*/
    
    /*--------------------------------------------------JS FOR Remider Start-----------------------------------------------------*/
    //$(".remider_div").hide();
    $('#remider'). click(function(){
        if($(this). prop("checked") == true){
            $(".remider_div").show();
        }else if($(this). prop("checked") == false){
            $(".remider_div").hide();
        }
    });
    
    /*--------------------------------------------------JS FOR Remider END-----------------------------------------------------*/
    /*--------------------------------------------------JS FOR Check Qty Start-----------------------------------------------------*/
    $('body').on('keyup', '.qty ', function () {
        var qty = $(this).val();
        var current_qty = $(this).closest('tr').find('.current_qty').val();
        $(this).closest('tr').find('.qty_error').empty();
        if(parseInt(qty) > parseInt(current_qty)){
            $(this).closest('tr').find('.qty_error').html("Only available Stock "+current_qty);
            $(this).closest('tr').find('.qty').val(current_qty);
            
        }
    });
        
    /*--------------------------------------------------JS FOR Check Qty END-----------------------------------------------------*/
    
    /*-----------ONCHANGE CITY TO AUTO ASSIGN STATE CODE USED FOR GST START--------------------*/
    $('#customer_city').on("change",function(){
        var statecode = $("#customer_city option:selected").attr('data-code');
        var applystatecode = $('#statecode').val();
        if(applystatecode == ''){
            $('#statecode').val(statecode);
        }
    });
    /*-----------ONCHANGE CITY TO AUTO ASSIGN STATE CODE USED FOR GST END---------------------*/

});