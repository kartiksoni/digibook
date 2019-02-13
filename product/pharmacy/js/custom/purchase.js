// author : Kartik Champaneriya
// date   : 28-07-2018
$(document).ready(function(){
    var cur_statecode = $('#cur_statecode').val();
    
    // Add Product button js // 

    $('body').on('click', '.btn-addmore-product', function() {
        var totalproduct = $('.product-tr').length;//for product length
        if(totalproduct > 0){
          $(".add_show").hide();
        }
        var html = $('#html-copy').html();
        
        html = html.replace('##SRNO##',totalproduct);
        //html = html.replace('##SRPRODUCT##',totalproduct);
        //html = html.replace('##PRODUCTCOUNT##',totalproduct);
        html = html.replace('<table>','');
        html = html.replace('</table>','');
        html = html.replace('<tbody>','');
        html = html.replace('</tbody>','');
        $('#product-tbody').append(html);
        if(totalproduct <= '2'){
          $('.remove_last').show();
        }
    });

    

    // End  Add Product button js //

    // Remove product button js //

    $('body').on('click', '.btn-remove-product', function(e) {
        e.preventDefault();
        $(this).closest ('tr').remove ();
        var totalproduct = $('.product-tr').length;//for product length
          if(totalproduct <= 1){
            $(".add_show").show();
             $(':input[type="submit"]').prop('disabled', true);
          }else{
            $(".add_show").hide();
          }
        calculationTotalAmount();
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
    
    $('body').on('keyup click', '.tags ', function () {
        var $this = $(this);
        var purchase_mrp = $(this).closest('tr').find('.mrp').val();
        console.log('purchase_mrp => '+purchase_mrp);
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
          },{
              name: 'GST',
              width: '50px',
              valueField: 'igst'
          }],

          // Event handler for when a list item is selected.
          select: function (event, ui) {
              var vendor = $('#vendor').val();
              if(vendor !== ''){
                  $('#errormsg').empty();
                  this.value = (ui.item ? ui.item.name : '');
                  $(this).closest('tr').find('.product-id').val(ui.item.id);
                  $(this).closest('tr').find('.qty-value').val(ui.item.ratio);
                  $(this).closest('tr').find('.f_igst').val(ui.item.igst);
                  $(this).closest('tr').find('.f_cgst').val(ui.item.cgst);
                  $(this).closest('tr').find('.f_sgst').val(ui.item.sgst);
                  $(this).closest('tr').find('.mrp').val(ui.item.mrp);
                  $(this).closest('tr').find('.batch').val(ui.item.batch);
                  $(this).closest('tr').find('.expiry').val(ui.item.expiry);
                  $(this).closest('tr').find('.mfg_co').val(ui.item.mfg_company);
                  $(this).closest('tr').find('.expired').text((typeof ui.item.expired !== 'undefined' && ui.item.expired == 1) ? 'Expired!' : '');
                  $(this).closest('tr').find('.rate').val((typeof ui.item.inward_rate !== 'undefined') ? ui.item.inward_rate : '');
                  addLastThreeRateForProduct(ui.item.name);
              }else{
                  $('#errormsg').html('<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>Please Select Vendor!</div></div></div>');
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
                      mrp: purchase_mrp,
                      action: "searchProductWithExpired"
                  },
                  // The success event handler will display "No match found" if no items are returned.
                  success: function (data) {
                    if(data.status == true){
                       $($this).closest('tr').find('.empty-message').html(null);
                      response(data.result);
                    }else{
                      $($this).closest('tr').find('.empty-message').html('No results found');
                    }
                  }
              });
          }
        });
    });
    // End Auto Compalete For getproduct //
    
    function addLastThreeRateForProduct(product_name = null){
        $.ajax({
            url: 'ajax_second.php',
            dataType: 'json',
            type: "POST",
            data: {'product_name' : product_name, 'vendor_id':$('#vendor').val() ,'action': "getLastPurchaseRateByProduct"},
            success: function (data) {
                if(data.status == true){
                    var lable = product_name+' Last Rate : ';
                    $.each(data.result, function (key, value) {
                        lable += parseFloat(value.rate).toFixed(2);
                        lable += ', ';
                    });
                    lable = lable.slice(0, lable.lastIndexOf(", "));
                    
                    $('.purchase-rate-lable').show();
                    $('.purchase-rate-lable').html(lable);
                }else{
                    $('.purchase-rate-lable').empty();
                    $('.purchase-rate-lable').hide();
                }
            },error: function () {
                $('.purchase-rate-lable').empty();
                $('.purchase-rate-lable').hide();
            }
        });
    }
    
    /*---------------COUNT ROW AND COLUMN WISE AMOUNT CALCULATION - START - GAUTAM MAKWANA----------------*/
    $('body').on('change keyup past', '.qty, .free_qty, .rate, .discount, .f_rate', function() {
      var qty = $(this).closest('tr').find('.qty').val();
      var rate = $(this).closest('tr').find('.rate').val();
      var discount = $(this).closest('tr').find('.discount').val();

      qty = (typeof qty !== 'undefined' && !isNaN(qty) && qty != '') ? parseFloat(qty) : 0;
      rate = (typeof rate !== 'undefined' && !isNaN(rate) && rate != '') ? parseFloat(rate) : 0;
      discount = (typeof discount !== 'undefined' && !isNaN(discount) && discount != '') ? parseFloat(discount) : 0;

      var finalRate = (rate)-(rate*discount/100);
      var finalAmount = (qty*finalRate);
      $(this).closest('tr').find('.f_rate').val(finalRate.toFixed(2));
      $(this).closest('tr').find('.ammout').val(finalAmount.toFixed(2));
      calculationTotalAmount();
    });

    function calculationTotalAmount(){
      var statecode = $('#statecode').val();
      var cur_statecode = $('#cur_statecode').val()
      var totalAmount = 0;
      var totalIgst = 0;
      var totalCgst = 0;
      var totalSgst = 0;

      $('.ammout').each(function() {
        var amount = $.trim( $(this).val() );
        amount = (typeof amount !== 'undefined' && !isNaN(amount) && amount != '') ? parseFloat(amount) : 0;
        totalAmount += amount;

        var qty = $(this).closest('tr').find('.qty').val();
        var freeqty = $(this).closest('tr').find('.free_qty').val();
        var rate = $(this).closest('tr').find('.f_rate').val();

        qty = (typeof qty !== 'undefined' && !isNaN(qty) && qty != '') ? parseFloat(qty) : 0;
        freeqty = (typeof freeqty !== 'undefined' && !isNaN(freeqty) && freeqty != '') ? parseFloat(freeqty) : 0;
        rate = (typeof rate !== 'undefined' && !isNaN(rate) && rate != '') ? parseFloat(rate) : 0;

        var tmpAmount = (qty+freeqty)*(rate);


        var igst = $(this).closest('tr').find('.f_igst').val();
        var cgst = $(this).closest('tr').find('.f_cgst').val();
        var sgst = $(this).closest('tr').find('.f_sgst').val();

        igst = (typeof igst !== 'undefined' && !isNaN(igst) && igst != '') ? parseFloat(igst) : 0;
        cgst = (typeof cgst !== 'undefined' && !isNaN(cgst) && cgst != '') ? parseFloat(cgst) : 0;
        sgst = (typeof sgst !== 'undefined' && !isNaN(sgst) && sgst != '') ? parseFloat(sgst) : 0;

        if(statecode == cur_statecode){
          totalCgst += (tmpAmount*cgst/100);
          totalSgst += (tmpAmount*sgst/100);
        }else{
          totalIgst += (tmpAmount*igst/100);
        }

      });
      $('#total_amount').val(totalAmount.toFixed(2));

      /*-------------COUNT DISCOUNT START--------------*/
      var discount = $('#rs_discount').val();
      discount = (typeof discount !== 'undefined' && !isNaN(discount) && discount != '') ? parseFloat(discount) : 0;
      totalAmount -= discount;
      /*-------------COUNT DISCOUNT END--------------*/

      /*-------------COUNT FREIGHT START-------------*/
      var freight = $('#courier_charge').val();
      var freightAmount = $('#total_courier').val();

      if(freight != '' && freightAmount != ''){
        freightAmount = (typeof freightAmount !== 'undefined' && !isNaN(freightAmount) && freightAmount != '') ? parseFloat(freightAmount) : 0;
        totalAmount += freightAmount;

        var freightGst = (freightAmount*freight/100);
        if(statecode == cur_statecode){
          totalCgst += (freightGst/2);
          totalSgst += (freightGst/2);
        }else{
          totalIgst += freightGst;
        }
      }
      /*-------------COUNT FREIGHT END-------------*/
      $('#overall_value').val(totalAmount.toFixed(2));
      $('#total_tax').val((totalIgst+totalCgst+totalSgst).toFixed(2));

      $('#total_igst').val(totalIgst.toFixed(2));
      $('#total_cgst').val(totalCgst.toFixed(2));
      $('#total_sgst').val(totalSgst.toFixed(2));
      
      //add gst
      totalAmount += (totalIgst+totalCgst+totalSgst);

      /*------------COUNT CREDIT/DEBIT NOTE START------------*/
      var noteAmount = $('#note_value').val();
      if(typeof noteAmount !== 'undefined' && !isNaN(noteAmount) && noteAmount != ''){
        var cr_type = $('#note_details').val();
        if(cr_type == 'credit_note'){
          totalAmount += parseFloat(noteAmount);
        }else{
          totalAmount -= parseFloat(noteAmount);
        }
      }
      /*------------COUNT CREDIT/DEBIT NOTE END------------*/

      var round_amount = (Math.round(totalAmount)-totalAmount);
      $('#purchase_amount').val(totalAmount.toFixed(2));
      $('#round_off').val((round_amount).toFixed(2));
      $('#total_total').val(Math.round(totalAmount).toFixed(2));
    }

    $('body').on('change keyup past', '#per_discount', function() {
      var per = $(this).val();
      var total_amount = $('#total_amount').val();
      per = (typeof per !== 'undefined' && !isNaN(per) && per != '') ? parseFloat(per) : 0;
      total_amount = (typeof total_amount !== 'undefined' && !isNaN(total_amount) && total_amount != '') ? parseFloat(total_amount) : 0;

      var dis_amount = (total_amount*per/100);
      $('#rs_discount').val(dis_amount.toFixed(2));

      calculationTotalAmount();
    });
    $('body').on('change keyup past', '#rs_discount', function() {
      calculationTotalAmount();
    });
    $('body').on('change', '#courier_charge', function() {
      var val = $(this).val();
      if(val != ''){
        $("#total_courier").prop('disabled', false);
      }else{
        $("#total_courier").val(null)
        $("#total_courier").prop('disabled', true);
      }
      calculationTotalAmount();
    });
    $('body').on('change keyup past', '#total_courier, #note_value, #note_details', function() {
      calculationTotalAmount();
    });
    /*---------------COUNT ROW AND COLUMN WISE AMOUNT CALCULATION - END - GAUTAM MAKWANA----------------*/

});







// auther : Gautam Makwana
//date    : 30-7-2018
$( document ).ready(function() {
    $("#city").change(function(){
        var city_id = $(this).val();
        if(city_id !== ''){
            getVendorByCity(city_id);
        }else{
          $('#vendor').children('option:not(:first)').remove();
        }
        // $('#vendor').trigger("change");
    });
    
    function getVendorByCity(city_id){
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'city_id':city_id, 'action':'getCityByVendor'},
            dataType: "json",
            beforeSend: function() {
                $('.vendor-loader').show();
            },
            success: function (data) {
              $('.vendor-loader').hide();
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
              $('.vendor-loader').hide();
              $('#vendor').children('option:not(:first)').remove();
            }
        });
    }
    
    
    $('#add-vendor').on('change', '#country', function() {
        var country_id = $(this).val();
        if(country_id !== ''){
          $.ajax({
              type: "POST",
              url: 'ajax.php',
              data: {'country_id':country_id, 'action':'getCountryByState'},
              dataType: "json",
              success: function (data) {
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
        // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';

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
                 showSuccessToast(data.message);
               // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
               // $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#purchase-addvendormodel').modal('toggle');
                // $('#city').trigger("change");
                var city_id = $('#add-vendor').find('#vendorcity').val();
                $('#add-vendor')[0].reset();
                
                
                $.ajax({
                  type: "POST",
                  url: 'ajax.php',
                  data: {'action':'getAllVendorCity'},
                  dataType: "json",
                  success: function (data) {
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
                
                if(city_id !== ''){
                    
                    setTimeout(function(){                  
                        $("#city").val(city_id).select2();
                        getVendorByCity(city_id);        
                    },2000);
                    
                    setTimeout(function(){                  
                        $('#vendor').val(data.result).select2();
                        $('#vendor').trigger("change");
                    },3000);
                }
                
                
              }else{
                showDangerToast(data.message);
                // htmlerror =  htmlerror.replace("##MSG##", data.message);
                // $('#addvendor-errormsg').html(htmlerror);
                $("html, body").animate({ scrollTop: 0 }, "slow");
              }
              $('#btn-addvendor').html('Save');
              $('#btn-addvendor').prop('disabled', false);
            },
            error: function () {
                showDangerToast('Somthing Want Wrong!');
              //htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
              //$('#addvendor-errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");

              $('#btn-addvendor').html('Save');
              $('#btn-addvendor').prop('disabled', false);
            }
        });

    });
    
    // FOR PRODUCT POPUP OPENING STOCK RS OPENING QTY * INWART RATE = OPENING STOCK RS
    $('body').on('keyup', '#opening_qty, #inward_rate', function () {
        var openingqty = $('#add-product').find('#opening_qty').val();
        openingqty = (typeof openingqty !== 'undefined' && !isNaN(openingqty) && openingqty !== '') ? openingqty : 0;
        
        var inwartrate = $('#add-product').find('#inward_rate').val();
        inwartrate = (typeof inwartrate !== 'undefined' && !isNaN(inwartrate) && inwartrate !== '') ? inwartrate : 0;
        
        var opening_stock_rs = (openingqty*inwartrate);
        $('#add-product').find('#opening_stock_rs').val(opening_stock_rs);
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
                    
                    //date:- 11/01/2019 CREATED BY VIRAG RAKHOLIYA
                    var state_gst_code = $('#statecode').val();
                    var cur_statecode = $('#cur_statecode').val()
                    
                    if(state_gst_code == cur_statecode){
                      $('#igst').hide();
                      $('#cgst').show();
                      $('#sgst').show();
                    } else{
                      $('#igst').show();
                      $('#cgst').hide();
                      $('#sgst').hide();
                    }
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
          getAllOrder(vendor_id);
      }else{
        return false;
      }
    });
    
    // $('#poi-model').on('hidden.bs.modal', function () {
    //   addLastBill($('#vendor').val());
    // });
    
    function getAllOrder(vendor_id = null){
      if(vendor_id !== ''){
          $.ajax({
                type: "POST",
                url: 'ajax.php',
                data: {'vendor_id':vendor_id, 'action':'getAllOrdersByVendorID'},
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

                      tmphtml = tmphtml.replace("##BATCH##",item.batch_no);
                      tmphtml = tmphtml.replace("##EXPIRY##",item.expiry);

                      tmphtml = tmphtml.replace("##PRODUCTID##",item.product_id);
                      tmphtml = tmphtml.replace("##GENERIC##",item.generic_name);
                      tmphtml = tmphtml.replace("##MFG##",item.mfg_company);
                      tmphtml = tmphtml.replace("##PURCHASEPRICE##",item.purchase_price);
                      tmphtml = tmphtml.replace("##GST##",item.gst);
                      tmphtml = tmphtml.replace("##UNIT##",item.unit);
                      tmphtml = tmphtml.replace("##QTY##",item.qty);
                      tmphtml = tmphtml.replace("##POIID##",item.id);
                      finalhtml += tmphtml;
                    });
                    
                    $('#poi-body').empty();
                    $('#poi-body').html(finalhtml);
                    $('#poi-model').modal('show');
                  }else{
                    //addLastBill(vendor_id);
                    return false;
                  }
                },
                error: function () {
                    $('.vendor-loader').hide();
                    //addLastBill(vendor_id);
                    return false;
                }
          });
      }
    }

    function addLastBill(vendor_id = null){
      if(vendor_id !== ''){
          $.ajax({
                type: "POST",
                url: 'ajax.php',
                data: {'vendor_id':vendor_id, 'action':'getLastPurchaseBill'},
                dataType: "json",
                beforeSend: function() {
                    $('.vendor-loader').show();
                },
                success: function (data) {
                    $('.vendor-loader').hide();
                  if(data.status == true){


                    var html = $('#hidden-vendor-lastbill').html();
                    html = html.replace("<table>", "");
                    html = html.replace("</table>", "");
                    html = html.replace("<tbody>", "");
                    html = html.replace("</tbody>", "");

                    var finalhtml = '';
                    $.each(data.result, function (key, value) {
                      var tmphtml = html;
                      tmphtml = tmphtml.replace("##SRNO##",key+1);
                      tmphtml = tmphtml.replace("##PRODUCTNAME##",value.product_name);
                      tmphtml = tmphtml.replace("##PRODUCTID##",value.product_id);
                      tmphtml = tmphtml.replace("##MRP##",value.mrp);
                      tmphtml = tmphtml.replace("##MFG##",value.mfg_co);
                      tmphtml = tmphtml.replace("##BATCH##",value.batch);
                      tmphtml = tmphtml.replace("##EXPIRY##",value.expiry);
                      tmphtml = tmphtml.replace("##EXPIRED##",(value.expired == 1) ? 'Expired!' : '');
                      tmphtml = tmphtml.replace("##QTY##",value.qty);
                      tmphtml = tmphtml.replace("##RATIO##",value.qty_ratio);
                      tmphtml = tmphtml.replace("##FREEQTY##",value.free_qty);
                      tmphtml = tmphtml.replace("##RATE##",value.rate);
                      tmphtml = tmphtml.replace("##DISCOUNT##",value.discount);
                      tmphtml = tmphtml.replace("##FINALRATE##",value.f_rate);
                      tmphtml = tmphtml.replace("##AMOUNT##",value.ammout);

                      tmphtml = tmphtml.replace("##CGST##",value.f_cgst);
                      tmphtml = tmphtml.replace("##SGST##",value.f_sgst);
                      tmphtml = tmphtml.replace("##IGST##",value.f_igst);

                      finalhtml += tmphtml;
                    });
                    
                    $('#vendor-lastbill-product-tbody').empty();
                    $('#vendor-lastbill-product-tbody').html(finalhtml);
                    $('#vendor_lastbill_model').modal('show');
                  }else{
                    return false;
                  }
                },
                error: function () {
                    $('.vendor-loader').hide();
                    return false;
                }
            });
      }
    }
    
    // for check all last bill product
    $('body').on('click', '.lastbill-check-all', function () {
      if(this.checked){
        $("#vendor-lastbill-product-tbody .lastbill-check").each(function(){
          this.checked=true;
          $(this).closest('tr').css("background-color", "#A9A9A9");
        })              
      }else{
        $("#vendor-lastbill-product-tbody .lastbill-check").each(function(){
          this.checked=false;
          $(this).closest('tr').css("background-color", "#FFFFFF");
        })              
      }

      var length = $(".lastbill-check:checked").length;
      console.log(length);
      if(length > 0){
        $('#btn-add-vendor-lastbill').prop('disabled', false);
      }else{
        $('#btn-add-vendor-lastbill').prop('disabled', true);
      }
    });

    $('body').on('click', '.lastbill-check', function () {
      if($(this).prop('checked')==true){
        $(this).closest('tr').css("background-color", "#A9A9A9");
      }else{
        $(this).closest('tr').css("background-color", "#FFFFFF");
      }
      var length = $(".lastbill-check:checked").length;
      if(length > 0){
        $('#btn-add-vendor-lastbill').prop('disabled', false);
      }else{
        $('#btn-add-vendor-lastbill').prop('disabled', true);
      }
    });

    $("#btn-add-vendor-lastbill").click(function(){
      var data = [];
        $("input:checkbox[class=lastbill-check]:checked").each(function (){
            var tmparray = [];
            tmparray['product_name'] = $(this).closest('tr').find('.product_name').html();
            tmparray['product_id'] = $(this).closest('tr').find('.product_id').val();
            tmparray['mrp'] = $(this).closest('tr').find('.mrp').html();
            tmparray['mfg'] = $(this).closest('tr').find('.mfg').html();
            tmparray['batch_no'] = $(this).closest('tr').find('.batch').html();
            tmparray['expiry'] = $(this).closest('tr').find('.expiry').html();
            tmparray['expired'] = $(this).closest('tr').find('.expired').html();
            tmparray['qty'] = $(this).closest('tr').find('.qty').html();
            tmparray['ratio'] = $(this).closest('tr').find('.ratio').val();
            tmparray['freeqty'] = $(this).closest('tr').find('.freeqty').html();
            tmparray['rate'] = $(this).closest('tr').find('.rate').html();
            tmparray['discount'] = $(this).closest('tr').find('.discount').html();
            tmparray['finalrate'] = $(this).closest('tr').find('.finalrate').html();
            tmparray['amount'] = $(this).closest('tr').find('.amount').html();
            tmparray['cgst'] = $(this).closest('tr').find('.cgst').val();
            tmparray['sgst'] = $(this).closest('tr').find('.sgst').val();
            tmparray['igst'] = $(this).closest('tr').find('.igst').val();
            data.push(tmparray);
        });

        if(data.length > 0){
          var html = $('#html-copy').html();
          var firsttr = $('#product-tbody tr:first').find('.product-id').val();
          if(firsttr == ''){
            $('#product-tbody').empty();
          }
          var rowCount = ($('#product-tbody tr').length)+1;
          $.each(data, function (i, item) {
            tmphtml = html;
            tmphtml = tmphtml.replace('##SRNO##',rowCount);
            //tmphtml = tmphtml.replace('##SRPRODUCT##','');
            //tmphtml = tmphtml.replace('##PRODUCTCOUNT##','');
            tmphtml = tmphtml.replace('<table>','');
            tmphtml = tmphtml.replace('</table>','');
            tmphtml = tmphtml.replace('<tbody>','');
            tmphtml = tmphtml.replace('</tbody>','');
            $('#product-tbody').append(tmphtml);

            $('#product-tbody tr:last').find('.tags').val(item.product_name);
            $('#product-tbody tr:last').find('.product-id').val(item.product_id);
            $('#product-tbody tr:last').find('.mrp').val(item.mrp);
            $('#product-tbody tr:last').find('.mfg_co').val(item.mfg);
            $('#product-tbody tr:last').find('.batch').val(item.batch_no);
            $('#product-tbody tr:last').find('.expiry').val(item.expiry);
            $('#product-tbody tr:last').find('.expired').text(item.expired);
            $('#product-tbody tr:last').find('.qty').val(item.qty);
            $('#product-tbody tr:last').find('.qty-value').val(item.ratio);
            $('#product-tbody tr:last').find('.free_qty').val(item.freeqty);
            $('#product-tbody tr:last').find('.rate').val(item.rate);
            $('#product-tbody tr:last').find('.discount').val(item.discount);
            $('#product-tbody tr:last').find('.f_rate').val(item.finalrate);
            $('#product-tbody tr:last').find('.ammout').val(item.amount);
            $('#product-tbody tr:last').find('.f_igst').val(item.igst);
            $('#product-tbody tr:last').find('.f_cgst').val(item.cgst);
            $('#product-tbody tr:last').find('.f_sgst').val(item.sgst);
            $('#product-tbody tr:last').find('.qty').trigger('change');

            rowCount++;
          });
        }
      $('#vendor_lastbill_model').modal('hide');
    });

    $("#btn-addpoi").click(function(){
      // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
     // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      
      var i = 1;
      var data = [];
        $("input:checkbox[class=poi-checkbox]:checked").each(function (){
            var tmparray = [];
            tmparray['date'] = $(this).closest('tr').find('.poi-date').html();
            tmparray['product_name'] = $(this).closest('tr').find('.poi-pname').html();
            tmparray['batch_no'] = $(this).closest('tr').find('.poi-batch').html();
            tmparray['expiry'] = $(this).closest('tr').find('.poi-expiry').html();
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
              $('#product-tbody tr:last').find('.batch').val(item.batch_no);
              $('#product-tbody tr:last').find('.expiry').val(item.expiry);

              if(state_code == cur_statecode){
                $('#product-tbody tr:last').find('.f_cgst').val(item.gst/2);
                $('#product-tbody tr:last').find('.f_sgst').val(item.gst/2);
                $('#product-tbody tr:last').find('.f_igst').val(0);
              }else{
                $('#product-tbody tr:last').find('.f_cgst').val(0);
                $('#product-tbody tr:last').find('.f_sgst').val(0);
                $('#product-tbody tr:last').find('.f_igst').val(item.gst);
              }
              $('#product-tbody tr:last').find('.f_poi_id').val(item.id);
          });

        $('#poi-model').modal('hide');

      }else{
         showDangerToast('Please select at least one item!');
        // htmlerror =  htmlerror.replace("##MSG##", 'Please select at least one item!');
       // $('#poi-error').html(htmlerror);
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
    
    // for check all order product
    $('body').on('click', '#poi-checkbox-all', function () {
      if(this.checked){
        $("#poi-body .poi-checkbox").each(function(){
          this.checked=true;
          $(this).closest('tr').css("background-color", "#A9A9A9");
        })              
      }else{
        $("#poi-body .poi-checkbox").each(function(){
          this.checked=false;
          $(this).closest('tr').css("background-color", "#FFFFFF");
        })              
      }
    });

    $('body').on('click', '.poi-checkbox', function () {
      if($(this).prop('checked')==true){
        $(this).closest('tr').css("background-color", "#A9A9A9");
      }else{
        $(this).closest('tr').css("background-color", "#FFFFFF");
      }
    });
    
    /*---------------ADD TRANSPORT START--------------------*/
    $("#add-transport-form").on("submit", function(event){
        event.preventDefault();
        var data = $(this).serialize();
        // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';

        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'addTransport', 'data': data},
            dataType: "json",
            beforeSend: function() {
              $('#btn-transport').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
              $('#btn-transport').prop('disabled', true);
            },
            success: function (data) {
              if(data.status == true){
                 showSuccessToast(data.message);
                // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                // $('#transport-errormsg').html(htmlsuccess);
                
                $('#transporter_name').append($('<option>', { 
                    value: data.result.id,
                    text : data.result.name 
                }));
                $('#transporter_name').val(data.result.id).trigger('change');
                $('#add-transport-form')[0].reset();
                $('#add-transport-model').modal('toggle');
              }else{
                showDangerToast(data.message);
                // htmlerror =  htmlerror.replace("##MSG##", data.message);
                // $('#transport-errormsg').html(htmlerror);
              }
              $('#btn-transport').html('Save');
              $('#btn-transport').prop('disabled', false);
            },
            error: function () {
                showDangerToast('Somthing Want Wrong!');
              // htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
              // $('#transport-errormsg').html(htmlerror);

              $('#btn-transport').html('Save');
              $('#btn-transport').prop('disabled', false);
            }
        });

    });
    /*---------------ADD TRANSPORT START--------------------*/
    
    //--------------Add Area-----------------------
    $("#add-area-form").on("submit", function(event){
    event.preventDefault();
    var data = $(this).serialize();
    var dataarr = JSON.parse('{"' + decodeURI(data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
    $.ajax({
        type: "POST",
        url: 'ajax.php',
        data: {'action':'addarea', 'data': dataarr},
        dataType: "json",
        beforeSend: function() {
          $('#btn-addarea').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
          $('#btn-addarea').prop('disabled', true);
        },
        success: function (data) {
          if(data.status == true){
            $('#btn-addarea').html('Save');
            $('#btn-addarea').prop('disabled', false);
            $('#add-area-form')[0].reset();
            
            $('#Area').append($('<option>', { 
                value: data.result,
                text : dataarr.name 
            }));
            $('#Area').val(data.result).trigger('change');
            $('#addarea-model').modal('hide');
            showSuccessToast(data.message);
          }else{
            $('#btn-addarea').html('Save');
            $('#btn-addarea').prop('disabled', false);
            showDangerToast(data.message);
          }
        },
        error: function () {
          $('#btn-addarea').html('Save');
          $('#btn-addarea').prop('disabled', false);
          showDangerToast('Somthing Want Wrong! Try again.');
        }
    });

  });

    $(".customer_type").change(function(){
      var customer_type = $(this).val();
      $(".gstno").removeAttr('readonly');
      $(".gst_error").show();
      $(".gstno").prop('required',true);
      if(customer_type == "GST_unregistered"){
          $(".gst_error").hide();
          $(".gstno").attr('readonly', true);
          $(".gstno").removeAttr('required');
      }
      if(customer_type == "Consumer"){
          $(".gst_error").hide();
          $(".gstno").attr('readonly', true);
          $(".gstno").removeAttr('required');
      }
      if(customer_type == "Overseas"){
          $(".gst_error").hide();
          $(".gstno").attr('readonly', true);
          $(".gstno").removeAttr('required');
      }
    });
    
});

$("#gst_no").keyup(function(){
    var gst_value = $(this).val();
    if(gst_value != ''){
        if (gst_value.match(/^([0-9]{2}[a-zA-Z]{4}([a-zA-Z]{1}|[0-9]{1})[0-9]{4}[a-zA-Z]{1}([a-zA-Z]|[0-9]){3}){0,15}$/)) {
            $("#gst_no").removeClass("parsley-error");
            $("#gst_no").addClass("parsley-success");
        }else{
            $("#gst_no").addClass("parsley-error");  
        }
    }else{
        $("#gst_no").addClass("parsley-error");
    }
    
});

 $('body').on('change keyup past', '#gst_no ', function () {
      var gstno = $(this).val();
   var pan = gstno.substring(2,12);
   $('#pan_num').val(pan);
   
  });