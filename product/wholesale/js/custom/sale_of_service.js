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
        data: {'bill_type':bill_type, 'action':'sale_of_service'},
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
                //$('#customer_mobile').val(typeof data.result.mobile != 'undefined' ? data.result.mobile : '');
                //$('#customer_email').val(typeof data.result.email != 'undefined' ? data.result.email : '');
                $('#statecode').val(typeof data.result.statecode != 'undefined' ? data.result.statecode : '');
                $('.customer-name-div').find('#salesman_id').val(typeof data.result.salesman_id != 'undefined' ? data.result.salesman_id : '');
                $('.customer-name-div').find('#rate_id').val(typeof data.result.rate_id != 'undefined' ? data.result.rate_id : '');
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

    $('body').on('keyup click', '.service ', function () {
        $(this).autocomplete({
              source: function (query, result) {
                  $.ajax({
                      url: "ajax.php",
                      data: {'query': query.term,'action': 'searchService'},            
                      dataType: "json",
                      type: "POST",
                      success: function (data) {
                        if(data.status == true){
                            result($.map(data.result, function (item) {
                              return {
                                    label: item.name,
                                    value: item.id,
        	                        sac: item.sac_code,
        	                        rate: item.inward_rate,
        	                        totalamount : item.inward_rate,
        	                        gst : item.gst,
        	                        sgst: item.sgst,
        	                        igst: item.igst,
        	                        cgst: item.cgst
                              }
                          }));
                        }else{
                            $($this).closest('tr').find(".producterror").text("No results found");
                        }
                      }
                  });
                },
                focus: function( query, result ) {
                  $(this).val( result.item.label );
                //   $(this).closest('tr').find('.service').val(result.item.label);
                  return false;
                },
                select: function( query, result ) {
                    $(this).closest('tr').find('.service_id').val(result.item.value);
                    $(this).closest('tr').find('.sac_code').val(result.item.sac);
                    $(this).closest('tr').find('.rate').val(result.item.rate);
                    $(this).closest('tr').find('.totalamount').val(result.item.totalamount);
                     if($(this).closest('tr').find('.gst').length){
                        if(statecode == cur_statecode){
                          $(this).closest('tr').find('.c_igst').val(0);
                          $(this).closest('tr').find('.c_cgst').val(result.item.cgst);
                          $(this).closest('tr').find('.c_sgst').val(result.item.sgst);
                          $(this).closest('tr').find('.gst').val(parseFloat(result.item.cgst)+parseFloat(result.item.sgst));
                          $('#totaligst').val(0);
                          $('#totalsgst').val(parseFloat(result.item.sgst).toFixed(2));
                          $('#totalcgst').val(parseFloat(result.item.cgst).toFixed(2));
                        }else{
                          $(this).closest('tr').find('.c_igst').val(result.item.igst);
                          $(this).closest('tr').find('.c_cgst').val(0);
                          $(this).closest('tr').find('.c_sgst').val(0);
                          $(this).closest('tr').find('.gst').val(parseFloat(result.item.igst));
                        }
                    }
                var totalamounts = 0;
           var gst = 0;
           $('.totalamount').each(function() {
          var val = $.trim( $(this).val() );
          if(val){
            val = parseFloat( val.replace( /^\$/, "" ) );
            totalamounts += !isNaN( val ) ? val : 0;
          
            
            if($(this).closest('tr').find('.gst').length){
                    var tmpigst = $(this).closest('tr').find('.gst').val();
                    var tmprate = $(this).closest('tr').find('.totalamount').val();
                    
                    tmpigst = (typeof tmpigst !== 'undefined' && tmpigst != '' && !isNaN(tmpigst)) ? parseFloat(tmpigst) : 0;
                    tmprate = (typeof tmprate !== 'undefined' && tmprate != '' && !isNaN(tmprate)) ? parseFloat(tmprate) : 0;
                    var tmpamount = tmprate;
                    tmpamount = (typeof tmpamount !== 'undefined' && !isNaN( tmpamount ) && tmpamount != '') ? parseFloat(tmpamount) : 0;
                    
                    //console.log('tmpamount => '+tmpamount+' tmpigst => '+tmpigst);
                    gst += (tmpamount*tmpigst/100);
                }
          }
        }); 
          
          $('#alltotalamount').val(totalamounts.toFixed(2));
         var courierFinalCharge = 0;
        if($('#couriercharge').val() != '' && $('#courier').val() != ''){
          var courierChargePer = $('#couriercharge').val();
          var courierChargeAmount = $('#courier').val();
          var courierFinalCharge = (courierChargeAmount*courierChargePer/100);
          totalamounts += (typeof courierChargeAmount !== 'undefined' && courierChargeAmount != '' && !isNaN(courierChargeAmount)) ? parseFloat(courierChargeAmount) : 0;
        }
        
        // count discount
        var dis_type = $("input[name='discount_type']:checked").val();
        if(dis_type == 'per'){
          var dis_per = $("#discount_per").val();
          var dis_per = (dis_per != '' && !isNaN(dis_per)) ? parseFloat(dis_per) : 0;
          var dis_value = (totalamounts*dis_per/100);
          totalamounts -= dis_value;
        }else if(dis_type == 'rs'){
          var dis_rs = $("#discount_rs").val();
          dis_rs = (dis_rs != '' && !isNaN(dis_rs)) ? parseFloat(dis_rs) : 0;
          totalamounts -= dis_rs;
        }

        $('#overalldiscount').val(totalamounts.toFixed(2));
        
        // count GST
        gst = (typeof gst !== 'undefined' && gst != '' && !isNaN(gst)) ? gst : 0;
        sgst = (typeof sgst !== 'undefined' && sgst != '' && !isNaN(sgst)) ? sgst : 0;
        cgst = (typeof cgst !== 'undefined' && cgst != '' && !isNaN(cgst)) ? cgst : 0;
        igst = (typeof igst !== 'undefined' && igst != '' && !isNaN(igst)) ? igst : 0;
        
        var statecode = $('#statecode').val();
        var cur_statecode = $('#cur_statecode').val();
        courierFinalCharge = (typeof courierFinalCharge !== 'undefined' && courierFinalCharge !== '' && !isNaN(courierFinalCharge)) ? parseFloat(courierFinalCharge) : 0;
        gst += courierFinalCharge;
        totalamounts += gst;

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

        $('#purchase_amount').val(totalamounts.toFixed(2));

        var round_amount = (Math.round(totalamounts)-totalamounts);

        $('#roundoff_amount').val((round_amount).toFixed(2));

        $('#final_amount').val(Math.round(totalamounts).toFixed(2));
                    return false;
                }
        });
    });
   
   
     $('body').on('change keyup past', '.totalamount ', function () {
          var $this = $('.totalamount').val();
         
         
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
                if($(this).closest('tr').find('.gst').length){
                    var tmpigst = $(this).closest('tr').find('.gst').val();
                    var tmprate = $(this).closest('tr').find('.totalamount').val();
                    
                   tmpigst = (typeof tmpigst !== 'undefined' && tmpigst != '' && !isNaN(tmpigst)) ? parseFloat(tmpigst) : 0;
                    tmprate = (typeof tmprate !== 'undefined' && tmprate != '' && !isNaN(tmprate)) ? parseFloat(tmprate) : 0;
                    var tmpamount = tmprate;
                    tmpamount = (typeof tmpamount !== 'undefined' && !isNaN( tmpamount ) && tmpamount != '') ? parseFloat(tmpamount) : 0;
                    
                    //console.log('tmpamount => '+tmpamount+' tmpigst => '+tmpigst);
                    gst += (tmpamount*tmpigst/100);
                }
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
        gst = (typeof gst !== 'undefined' && gst != '' && !isNaN(gst)) ? gst : 0;
        sgst = (typeof sgst !== 'undefined' && sgst != '' && !isNaN(sgst)) ? sgst : 0;
        cgst = (typeof cgst !== 'undefined' && cgst != '' && !isNaN(cgst)) ? cgst : 0;
        igst = (typeof igst !== 'undefined' && igst != '' && !isNaN(igst)) ? igst : 0;
        
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

    /*----add gst and discount start-------*/
    $('body').on('click', '#btn-ptr-discount ', function () {
      var tr_id = $('#ptr-discount-model').find('#tr-id').val();
      var rate = $('#ptr-discount-model').find('#rate').val();
      var discount = $('#ptr-discount-model').find('#discount').val();
      var rateamount = 0;
      if(tr_id !== ''){
        rate = (rate !== '') ? rate : 0;
        if(discount !== ''){
          var disAmount = (rate*discount/100);
          rateamount = (rate-disAmount);
        }else{
          rateamount = rate;
        }
        // $('#tr-'+tr_id).find('.ptr').val(ptr);
        $('#tr-'+tr_id).find('.discount').val(discount);
        $('#tr-'+tr_id).find('.rate').val(rateamount.toFixed(2));
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
    $('body').on('change keyup past', '.freeqty ', function () {
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
                $('#salesman_id').val(ui.item.salesman_id);
                $('#rate_id').val(ui.item.rate_id);
                $('#customer_name').val(ui.item.name);
                $('#bytempcust').val(ui.item.id);                  //for template
                $('#customer_mobile').val(ui.item.mobile);
                $('#customer_email').val(ui.item.email);
                $('#statecode').val(ui.item.state);
                $('#customer_u_id').val(ui.item.temp_customer);
                $('#limit_total').val(ui.item.limit_total);
                $('#customer_city').val(ui.item.city).trigger('change');
                
                /*if(typeof ui.item.id != 'undefined' && ui.item.id != ''){
                  //PanddingBillAmount(ui.item.id);
                  //ShowCustomerBill(ui.item.id);
                }*/
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
                    $('#salesman_id').val(ui.item.salesman_id);
                    $('#rate_id').val(ui.item.rate_id);
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
                      ShowCustomerBill(ui.item.id);
                    }
                }else{
                  //Acciones si el usuario no confirma
                    $("#customer_name").val('');
                    $('#salesman_id').val('');
                    $('#rate_id').val('');
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
      // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
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
          showDangerToast('Stock not available in this product!');
         // htmlerror = htmlerror.replace("##MSG##", 'Stock not available in this product!');
         // $('#lastbill-errormsg').html(htmlerror);
        }
      }else{
        $(this).closest('tr').css({"background-color": uncheck_color});
      }
      lastbillbutton();
    });

    // for check all order product
    $('body').on('click', '.lastbill-check-all', function () {
      // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
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
              showDangerToast('Some product stock is not available!');
            // var htmlerrortmp = htmlerror;
            // htmlerrortmp = htmlerrortmp.replace("##MSG##", 'Some product stock is not available!');
            // $('#lastbill-errormsg').html(htmlerrortmp);
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